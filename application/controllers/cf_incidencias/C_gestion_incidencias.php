<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * 
 * 
 *
 */
class C_gestion_incidencias extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_incidencias/m_gestion_incidencias');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }
    
    public function index()
    {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {
    
            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;
            
            $listaModulosPorResponsable = $this->m_gestion_incidencias->getModulosOfUsuarioResponsable($idUsuario);
            $esUsuarioResponsable = $this->isUsuarioResponsable($listaModulosPorResponsable);
            
            $usuario_regular = null;
            $usuario_responsable = null;
            
            if($esUsuarioResponsable){
                $usuario_responsable = $idUsuario;
            }else{
                $usuario_regular = $idUsuario;
            }
            
            $codigoIncidente = ($esUsuarioResponsable?'':' ');
            $estado = NULL;
            $modulo = '';
            $tipoIncidente = '';
            
            $data['listaTipoIncidentes'] = $this->m_gestion_incidencias->getTipoIncidentes();
            $data['listaModulos'] = $this->m_gestion_incidencias->getModulos();
            $data['listaEstados'] = $this->m_gestion_incidencias->getAllEstadosIncidente();
            $data['tbIncidencias'] = $this->makeHTLMTablaIncidencias(
                $this->m_gestion_incidencias->getIncidentes($usuario_regular, $usuario_responsable,
                    $codigoIncidente, $estado, $modulo, $tipoIncidente)
                , $idUsuario, $esUsuarioResponsable, $listaModulosPorResponsable);
            
            $permisos = $this->session->userdata('permisosArbol');
            #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_CAP_WORKFLOW, ID_PERMISO_HIJO_PQT_MANTE_PROYECTO, ID_MODULO_CAP);
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_CAP_WORKFLOW, ID_PERMISO_HIJO_PQT_MANTE_PROYECTO, ID_MODULO_CAP);
            $data['opciones'] = $result['html'];
            /*if ($result['hasPermiso'] == true) {
                $this->load->view('vf_pqt_mantenimiento/v_proyecto', $data);
            } else {
                redirect('login', 'refresh');
            }*/
            $idUsuario = $this->session->userdata('idPersonaSession');
			$data['idUsuario'] = $idUsuario;
            $this->load->view('vf_incidencias/v_gestion_incidencias', $data);
        } else {
            redirect('login', 'refresh');
        }
    
    }
    
    function makeHTLMTablaIncidencias($listaIncidentes, $idUsuario, $esUsuarioResponsable, $listaModulosPorResponsable){
        $idPerfil = $this->session->userdata('idPerfilSession');
		
		$html = '';
        
        // cabecera de usuario normal
        $html = '<table id="data-table" class="table table-bordered">;
					<thead class="thead-default">
						<tr>
							<th>CODIGO OBSERVACION</th>
							<th>ITEMPLAN</td>
							<th>MODULO</th>
							<th>TIPO OBSERVACION</th>
							<th>ESTADO</th>
							<th>APROBADO POR RESPONSABLE?</th>
                            <th>FECHA SOLICITADA</th>
                            <th>FECHA APROBADA</th>
							<th>FECHA ENVIO SOPORTE</th>
							<th>FECHA CIERRE</th>
							<th>SOLICITANTE</th>
							<th>RESPONSABLE</th>
							</tr>
					</thead>
					<tbody>';
        
        foreach ($listaIncidentes->result() as $row){
			$concatItm = null;
            if($row->itemplan) {
                $concatItm = ' - '.$row->itemplan;  
            }
			
			// $comentario = str_replace('"','', $row->comentario);
			// $s= str_replace(':','', $comentario); 
			// $s= str_replace('.','', $comentario); 
			// $s= str_replace(',','', $comentario); 
			// $s= str_replace(';','', $comentario);
						
            $html .= '
						<tr>
							<td>
								<a data-codigo_incidente="'.$row->codigo_incidente.'"
								   data-aprobado_fg="'.$row->procede_fg.'"
								   data-id_estado_incidente="'.$row->id_estado_incidente.'"
								   data-estado="'.$row->estado.'"
								   data-fecha_solicitada="'.$row->fecha_solicitada.'"
								   data-motivo_rechazo="'.$row->motivo_rechazo.'"
								   data-adjunto_resp="'.$row->adjunto_resp.'"
								   data-fecha_aprobada="'.$row->fecha_aprobada.'"
								   data-fecha_atendida="'.$row->fecha_atendida.'"
								   data-comentario_responsable="'.utf8_decode($row->resultado_final).'"
								   data-comentario_solicitante="'.str_replace(',',' ',utf8_decode($row->comentario)).$concatItm.'"
								  style="color: blue;" onclick="abrirModalInfo(this)">'.$row->codigo_incidente.'</a>
							</td>
							<td>'.$row->itemplan.'</td>
							<td>'.utf8_decode($row->modulo).'</td>
							<td>'.$row->tipo_incidente.'</td>';

            if(($esUsuarioResponsable || $idUsuario == 3 || $idUsuario == 1677)&& $row->id_estado_incidente == 3 && ($row->id_responsable== $idUsuario || in_array(4, array($idPerfil)))){
                $html .= '<td style="color: orange;"><strong>'.$row->estado.'</strong>';
                $html .= '<a data-codigo_incidente="' . $row->codigo_incidente . '" onclick="openModalCerrarIncidente(this)"><i title="Cerrar Incidencia" class="zmdi zmdi-hc-3x zmdi-sign-in"></i></a> ';
                $html .= '<a data-codigo_incidente="' . $row->codigo_incidente . '" onclick="openModalEscalamiento(this)"><i title="Enviar Soporte" class="zmdi zmdi-hc-3x zmdi-wrench"></i></a>';
                $html .= '</td>';
            } else if(($esUsuarioResponsable || $idUsuario == 3 || $idUsuario == 1677) && $row->id_estado_incidente == 5 && ($row->id_responsable== $idUsuario|| $idPerfil == 4)){
                $html .= '<td style="color: brown;"><strong>'.$row->estado.'</strong>';
                $html .= '<a data-codigo_incidente="' . $row->codigo_incidente . '" onclick="openModalCerrarIncidente(this)"><i title="Cerrar Incidencia" class="zmdi zmdi-hc-3x zmdi-sign-in "></i></a> ';
                $html .= '</td>';
            } else{
                $color='black';
                if($row->procede_fg == '0'){
                    $color='red';
                }else if($row->id_estado_incidente == 4){
                    $color='green';
                }else if($row->id_estado_incidente == 3){
                    $color='orange';
                }else{
                    $color='black';
                }
                $html .= '<td style="color: '.$color.';"><strong>'.$row->estado.'</strong></td>';
            }
            
            //PROCEDE FG INI
            if(($esUsuarioResponsable || $idUsuario == 3 || $idUsuario == 1677) && $row->id_estado_incidente == 2 && ($row->id_responsable== $idUsuario || $idPerfil == 4)){
                $html .= '<td>';
				$html .= '<a data-codigo_incidente="' . $row->codigo_incidente . '" onclick="openModalRechazoIncidente(this)"><img alt="Rechazar" title="Rechazar" height="20px" width="20px" src="public/img/iconos/cancelar.png"></a>        ';
                $html .= '<a data-codigo_incidente="' . $row->codigo_incidente . '" onclick="openModalAprobarIncidente(this)"><img alt="Aprobar " title="Aprobar" height="20px" width="20px" src="public/img/iconos/check_24016.png"></a>';
                $html .= '</td>';
            }else{
                if($row->procede_fg == '1'){
                    $html .= '<td style="color: green;"><strong>APROBADO</strong></td>';
                }else if($row->procede_fg == '0'){
                    $html .= '<td style="color: red;"><strong>RECHAZADO</strong></td>';
                }else{
                    $html .= '<td>PENDIENTE</td>';
                }
            }
            //PROCEDE FG FIN
            
            $html .= '<td>'.$row->fecha_solicitada.'</td>';
            $html .= '<td>'.$row->fecha_aprobada.'</td>';
			$html .= '<td>'.$row->fecha_envio_soporte.'</td>';
			$html .= '<td>'.$row->fecha_atendida.'</td>';
            $html .= '<td>'.$row->solicitante.' ('.$row->empColabDescSolicitante.')</td>';
			
            $html .= '<td>'.$row->responsable.'</td>';
            $html .= '</tr>';
        }
        
        $html .= '</tbody></table>';
        return $html;
    }
    
    function isUsuarioResponsable($listaModulosPorResponsable){
        $i = 0;
        foreach ($listaModulosPorResponsable->result() as $row){
            $i += 1;
        }
        
        return $i!=0;
    }
    
    function obtModulosByResponsable($listaModulosPorResponsable){
        $mapModulos = array();
        foreach ($listaModulosPorResponsable->result() as $row){
            $mapModulos[$row->id_modulo] = $row->id_modulo;
        }
    
        return $mapModulos;
    }
	
    function registrarIncidente(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try {
        
            $tipoIncidente = $this->input->post('selectTipoIncidente');
            $modulo 	   = $this->input->post('selectModulo');
            $comentario    = $this->input->post('txtComentario');
            $itemplan      = $this->input->post('txtItemplan');
			
            $codigoIncidente = $this->obtenerCodigoIncidente();
            
			if($itemplan != null && $itemplan != '') {
				$id = $this->m_utils->getEstadoPlanByItemplan($itemplan);
				
				if($id == null || $id == '') {
					throw new Exception('No existe el itemplan, registrar correctamente.');
				}				
			}
			
            //DE NO EXISTIR LA CARPETA ITEMPLAN LA CREAMOS
            $pathItemplan = 'uploads/incidencias/'.$codigoIncidente;
            if (!is_dir($pathItemplan)) {
                mkdir ($pathItemplan, 0777);
            }
            $uploadfile1 = null;
			if(isset($_FILES['fileAdjunto'])){
				$uploadfile1 = $pathItemplan.'/'. basename($_FILES['fileAdjunto']['name']);
			
				if (move_uploaded_file($_FILES['fileAdjunto']['tmp_name'], $uploadfile1)) {
					log_message('error', 'Se movio el archivo a la ruta 1.'.$uploadfile1);
				}else {
					throw new Exception('Hubo un problema con la carga del archivo 1 al servidor, comuniquese con el administrador.');
				}
            }
            $dataFormularioIncidente = array(
                'codigo_incidente'          => $codigoIncidente,
                'id_prioridad_incidente'    => 3,
                'id_tipo_incidente'         => $tipoIncidente,
                'id_modulo'                 => $modulo,
                'comentario'                => $comentario,
                'id_solicitante'            => $this->session->userdata('idPersonaSession'),
                'fecha_solicitada'          => $this->fechaActual(),
                'id_estado_incidente'       => 1,
                'adjunto_sol'               => $uploadfile1,
				'itemplan'                  => $itemplan
            );
        
            $data = $this->m_gestion_incidencias->registrarIncidente($dataFormularioIncidente);
            $this->asignarIncidenteAResponsable($codigoIncidente, $modulo, $tipoIncidente);
            
            //log_message('error', print_r($dataFormularioIncidente,true));
            $data['codigo_incidente']      = $codigoIncidente;
        
        }catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone','America/Lima');
        setlocale(LC_TIME, "es_ES","esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }
    
    function obtenerCodigoIncidente(){
        $codigoIncidente = '';
        try {
            $this->db->trans_begin();
            $zonahoraria = date_default_timezone_get();
            ini_set('date.timezone','America/Lima');
            setlocale(LC_TIME, "es_ES","esp");
            $fechaiso = strftime("%Y%m%d");
            
            $correlativo = 0;
            
            foreach ($this->m_gestion_incidencias->obtenerCorrelativo($fechaiso)->result() as $row){
                $correlativo = $row->correlativo;
            }
            
            if($correlativo == 0){
                //CORRELATIVO NO EXISTE, DEBEMOS DE REGISTRAR
                $correlativo = $correlativo + 1;
                $insert = array(
                    'correlativo' => $correlativo,
                    'fechaiso'    => $fechaiso
                );
                $this->m_gestion_incidencias->insertCorrelativo($insert);
            }else{
                //CORRELATIVO SI EXISTE, DEBEMOS DE INCREMENTAR EN 1
                $correlativo = $correlativo + 1;
                $update = array(
                    'correlativo' => $correlativo,
                    'fechaiso'    => $fechaiso
                );
                $this->m_gestion_incidencias->updateCorrelativo($update, $fechaiso);
            }
            
            $codigoIncidente = $fechaiso.'-'.sprintf('%06d', $correlativo);
            
            $this->db->trans_commit();
        } catch (Exception $e) {
            $this->db->trans_rollback();
            $codigoIncidente = '';
        }
        return $codigoIncidente;
    }
    
    function asignarIncidenteAResponsable($codigoIncidente, $idModulo, $tipoIncidente){
        //DEBEMOS DE OBTENER EL ID DEL RESPONSABLE
        $idResponsable = 0;
        
		if($idModulo == 14) {
			$idResponsable = $this->m_gestion_incidencias->obtenerResponsablexTipo($tipoIncidente);
		} else {
			foreach ($this->m_gestion_incidencias->obtenerResponsableLibre($idModulo)->result() as $row){
				$idResponsable = $row->id_responsable;
			}
		}
        
        $update = array(
            'id_responsable'        => $idResponsable,
            'fecha_asignada'        => $this->fechaActual(),
            'id_estado_incidente'   => 2
        );
        
        $this->m_gestion_incidencias->asignarResponsable($update, $codigoIncidente);
    }
    
    function aprobarIncidente(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try {
        
            $codigoIncidente = $this->input->post('codigo_incidente');
        
            $update = array(
                'id_estado_incidente'   => 3,
                'procede_fg'            => 1,
                'fecha_aprobada'        => $this->fechaActual()
            );
        
            $data = $this->m_gestion_incidencias->aprobarIncidente($update, $codigoIncidente);
        
        }catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function rechazarIncidente(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try {
        
            $codigoIncidente = $this->input->post('codigo_incidente');
            $motivo = $this->input->post('txtMotivo');
        
            $update = array(
                'id_estado_incidente'   => 4,
                'procede_fg'            => 0,
                'motivo_rechazo'        => $motivo,
                'fecha_atendida'        => $this->fechaActual(),
                'fecha_aprobada'        => $this->fechaActual(),
                'resultado_final'       => 'SE RECHAZO EL TICKET DE INCIDENTE'
            );
        
            $data = $this->m_gestion_incidencias->rechazarIncidente($update, $codigoIncidente);
        
        }catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function cerrarIncidente(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try {
        
            $comentario = $this->input->post('txtComentarioFinal');
            $codigoIncidente = $this->input->post('codigo_incidente');
            
            $uploadfile1 = '';
            $archivo = (isset($_FILES['fileAdjunto']) ? $_FILES['fileAdjunto'] : '');
            #log_message('error', '$archivo '.$archivo);
            if($archivo != ""){
                //DE NO EXISTIR LA CARPETA ITEMPLAN LA CREAMOS
                $pathItemplan = 'uploads/incidencias/atencion/'.$codigoIncidente;
                if (!is_dir($pathItemplan)) {
                    mkdir ($pathItemplan, 0777);
                }
                
                $uploadfile1 = $pathItemplan.'/'. basename($_FILES['fileAdjunto']['name']);
                
                if (move_uploaded_file($_FILES['fileAdjunto']['tmp_name'], $uploadfile1)) {
                    log_message('error', 'Se movio el archivo a la ruta 1.'.$uploadfile1);
                }else {
                    throw new Exception('Hubo un problema con la carga del archivo 1 al servidor, comuniquese con el administrador.');
                }
            }
            
            $update = array(
                'resultado_final'     => $comentario,
                'fecha_atendida'      => $this->fechaActual(),
                'id_estado_incidente' => 4,
                'adjunto_resp'        => $uploadfile1
            );
        
            $data = $this->m_gestion_incidencias->cerrarIncidente($update, $codigoIncidente);
            
        }catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function descargarAdjunto(){
        $codigoIncidente = (isset($_GET['codigo_incidente']) ? $_GET['codigo_incidente'] : '');
        $tipoSolicitud = (isset($_GET['tipo_solicitud']) ? $_GET['tipo_solicitud'] : '');
        $archivoDescarga = '';
        foreach ($this->m_gestion_incidencias->getArchivosDescargaByCodigoIncidente($codigoIncidente)->result() as $row){
            if($tipoSolicitud == '0'){
                $archivoDescarga = $row->adjunto_resp;
            }else if($tipoSolicitud == '1'){
                $archivoDescarga = $row->adjunto_sol;
            }
        }
        //download file from directory
        // Process download
        if(file_exists($archivoDescarga)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($archivoDescarga).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($archivoDescarga));
            flush(); // Flush system output buffer
            readfile($archivoDescarga);
            exit;
        }
    }
    
    function filtrarTablaIncidentes(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try {
            $codigoIncidente = $this->input->post('codigoIncidente');
            $estado = $this->input->post('estado');
            $modulo = $this->input->post('modulo');
            $tipoIncidente = $this->input->post('tipoIncidente');
            
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;
            
            $listaModulosPorResponsable = $this->m_gestion_incidencias->getModulosOfUsuarioResponsable($idUsuario);
            $esUsuarioResponsable = $this->isUsuarioResponsable($listaModulosPorResponsable);
            
            $usuario_regular = null;
            $usuario_responsable = null;
            
            if($esUsuarioResponsable){
                $usuario_responsable = $idUsuario;
            }else{
                $usuario_regular = $idUsuario;
            }
            
            $data['tbIncidencias'] = $this->makeHTLMTablaIncidencias(
                $this->m_gestion_incidencias->getIncidentes($usuario_regular, $usuario_responsable, $codigoIncidente, $estado, $modulo, $tipoIncidente), 
                $idUsuario, $esUsuarioResponsable, $listaModulosPorResponsable);
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
        
    }

    function enviarSoporte(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try {
        
            $comentario = $this->input->post('comentarioEnvio');
            $codigoIncidente = $this->input->post('codigo_incidente');
            
            if($codigoIncidente == '' || $codigoIncidente == null) {
                throw new Exception('codigo Incidencia null');
            }
            
            $update = array(
                                'comentario_envio_soporte' => $comentario,
                                'fecha_envio_soporte'      => $this->fechaActual(),
                                'id_estado_incidente'      => 5
                            );
        
            $data = $this->m_gestion_incidencias->cerrarIncidente($update, $codigoIncidente);
            
        }catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
	function getTipoInc() {
		$idModulo = $this->input->post('idModulo');
		$cmbTipo = __buildComboTipoIncidente($idModulo);
		
		$data['cmbTipoInc'] = $cmbTipo;
		
		echo json_encode(array_map('utf8_encode', $data));
	}
}