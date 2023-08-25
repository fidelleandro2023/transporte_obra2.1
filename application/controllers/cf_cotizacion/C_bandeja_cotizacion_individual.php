<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_bandeja_cotizacion_individual extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_cotizacion/m_bandeja_cotizacion_individual');
        $this->load->model('mf_plan_obra/m_planobra');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->library('zip');
        $this->load->helper('url');
    }
    
	public function index()
	{  	   
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
        	  
	           $data['listaZonal'] = $this->m_utils->getAllZonal();
	           $data['listaJefatura']  = $this->m_utils->getNewAllJefatura();
               $data['listaEECC']      = $this->m_utils->getAllEECC();
               $data['tablaAsigGrafo'] = $this->tablaBandejaCotizacion(null, null, null, null, null, null);
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');	
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');              
        	   $permisos =  $this->session->userdata('permisosArbol');
        	   #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_CLUSTER_SISEGO, ID_PERMISO_HIJO_BANDEJA_COTIZACION_INDIVIDUAL);
        	   $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_NUEVO_MODELO_COTIZACION, ID_PERMISO_HIJO_BANDEJA_COTIZACION_INDIVIDUAL, ID_MODULO_PAQUETIZADO);
        	   $data['opciones'] = $result['html'];
        	//    if($result['hasPermiso'] == true){
        	       $this->load->view('vf_cotizacion/v_bandeja_cotizacion_individual',$data);
        	//    }else{
        	//        redirect('login','refresh');
	        //    }
	   }else{
	       redirect('login','refresh');
	   }
    }
        
    function tablaBandejaCotizacion($idSubProyecto, $codigo, $estado, $idJefatura, $idEmpresaColab, $flgBandConf){
        $data = $this->m_bandeja_cotizacion_individual->getItemplanPreRegistro($idSubProyecto, $codigo, $estado, $idJefatura, $idEmpresaColab, $flgBandConf);
        $idUsuario = $this->session->userdata('idPersonaSession');
        $html = '<table id="data-table" class="table table-bordered" style="font-size: 10px;">
                    <thead class="thead-default">
                        <tr>                 
                            <th>ACCI&Oacute;N</th>                            
                            <th>CODIGO</th>
                            <th>SUBPROYECTO</th>
                            <th>SISEGO</th>
                            <th>EECC</th>
                            <th>MDF</th>
                            <th>DISTRITO</th>
                            <th>JEFATURA</th>
                            <th>TIPO SISEGO</th>
                            <th>Costo Mat</th>
                            <th>Costo Mo</th>
                            <th>Clasificaci&oacute;n</th>
                            <th style="text-align:center">Fecha Creacion.</th>
							<th>BANDEJA</th>
                            <th>BAND. CONFIRMACI&Oacute;N</th>
                            <th>FECHA ENVIO COTIZACI&Oacute;N</th>
                            <th>USUARIO ENVIO COTIZACI&Oacute;N</th>
                            <th style="text-align:center">ESTADO</th>
                        </tr>
                    </thead>
                   
                    <tbody>';
															                                                   
                foreach($data AS $row) {
                    $btnEditaEmpresaColab = null;
                    $btnObs = null;
                    $rechazadoBandjConf = null;
					$btnRechazar = null;
					$btnCambioFlgRobot = null;
                    if($this->session->userdata("eeccSession") == 0 || $this->session->userdata("eeccSession") == 6) {
                        if($row['estado'] == 0) {
                            // $btnEditaEmpresaColab = '<i style="color:#A4A4A4;cursor:pointer;" data-distrito="'.$row['distrito'].'" data-codigo_coti="'.$row['codigo'].'"
                                                        // class="zmdi zmdi-hc-2x zmdi zmdi-truck" title="Actualizar EECC" onclick="openEditarMdf($(this))"></i>';
                                                        
                            /*$btnRechazar = '<i style="color:#A4A4A4;cursor:pointer;" data-codigo_cotizacion="'.$row['codigo'].'" data-eecc="'.$row['empresaColabDesc'].'"
                                                class="zmdi zmdi-hc-2x zmdi zmdi-delete" title="Rechazar" onclick="openRechazar($(this))"></i>'; */
							
							if($row['flg_robot'] == 1 && ($idUsuario == 3 || $idUsuario == 77 || $idUsuario == 5 || $idUsuario == 1806)) {
								$btnCambioFlgRobot = '<i style="color:#A4A4A4;cursor:pointer;" data-codigo_cotizacion="'.$row['codigo'].'" data-eecc="'.$row['empresaColabDesc'].'"
												class="zmdi zmdi-hc-2x zmdi zmdi-assignment" title="Cambiar a EECC" onclick="cambiarFlgRobot($(this))"></i>';  					
							}							
                        } else {
                            $btnRechazar = null;
                        }
                    }
                    
                    if($row['fecha_validacion']) {
                        $btnObs = '<i style="color:#A4A4A4;cursor:pointer;" data-fecha_rechazo="'.$row['fecha_validacion'].'" data-observacion="'.$row['observacion'].'" data-usuario="'.$row['usuarioConfirmaRechaz'].'"
                                            class="zmdi zmdi-hc-2x zmdi zmdi-assignment-o" title="Datos de Rechazo" onclick="openModalObservacionRech($(this))"></i>';
                    }
                    
                    if($row['statusBandjConfirmacion'] == 2) {
                        $rechazadoBandjConf = 'RECHAZADO EN BANDEJA DE CONFIRMACI&Oacute;N';
                    }
                  
                $html .=' <tr>                                                         
                            <td>'.$btnCambioFlgRobot.' '.$btnRechazar.' '.$btnEditaEmpresaColab.' '.$btnObs.' '.(($row['estado'] == 0) ? '<a href="getFormCotizacionIndividual?cod='.$row['codigo'].'&&estado='.$row['estado'].'&&flg_principal='.$row['flg_principal'].'"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/editar.ico"></a>' : '').'</td>
							<td>'.$row['codigo'].'</td>
							<td>'.$row['subProyectoDesc'].'</td>
							<td>'.$row['sisego'].'</td>
							<td>'.$row['empresaColabDesc'].'</td>
                            <td>'.$row['codigoMdf'].'</td>
                            <td>'.$row['distrito'].'</td>
							<td>'.$row['jefatura'].'</td>
							<td>'.$row['tipoSisego'].'</td>
						    <td>'.$row['costo_materiales'].'</td>
					        <td>'.$row['costo_mano_obra'].'</td>
					        <td>'.$row['clasificacion'].'</td>
                            <td>'.$row['fecha_registro'].'</td>
							<td>'.$row['bandejEecRobot'].'</td>
                            <td>'.$row['statusBandjConfirmacion'].'</td>
                            <td>'.$row['fecha_envio_cotizacion'].'</td>
                            <td>'.$row['usuarioEnviaCotizacion'].'</td>
                            <td>'.$row['estadoDesc'].'</td>		
						</tr>';
                    }  
   			  
			 $html .='</tbody>
                </table>';
                    
        return utf8_decode($html);
    }

    function getEeccByDistrito(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $distrito = $this->input->post('distrito');
            $listaEECC = $this->m_utils->getEECCByDistrito($distrito);
            $html = '<option value="">.:Seleccionar:.</option> ';
            foreach($listaEECC as $row){
                $html .= '<option value="'.$row->idEmpresaColab.'">'.$row->empresaColabDesc.'</option>';
            }
            $data['listaEecc'] = $html;
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function getMdfCotizacionInd(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $data['comboMdf'] = __buildCmbMdf();
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }


    function updateEmpresaColab() {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $idCentral      = $this->input->post('idCentral');
            $distrito       = $this->input->post('distrito');
            $codCotizacion  = $this->input->post('codCotizacion');

            // $infoCentra = $this->m_utils->getInfocentralByDistritoAndEECC($distrito, $idEmpresaColab);
            $data = $this->m_bandeja_cotizacion_individual->updateEmpresaColab($codCotizacion, $idCentral);
            $data['tablaBanCotizacion'] = $this->tablaBandejaCotizacion(null, null, null, null, null, null);
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function filtrarCotizacionInd()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $idSubPro       = $this->input->post('idSubPro');
            $idEmpresaColab = $this->input->post('idEmpresaColab');
            $idJefatura     = $this->input->post('idJefatura');
            $idSituacion    = $this->input->post('idSituacion');
            $flgBandConf    = $this->input->post('flgBandConf');

            $idSubProyecto  = ($idSubPro       == '') ? NULL : $idSubPro;
            $idEmpresaColab = ($idEmpresaColab == '') ? NULL : $idEmpresaColab;
            $idSituacion    = ($idSituacion    == '') ? NULL : $idSituacion;
            $idJefatura     = ($idJefatura     == '') ? NULL : $idJefatura;
            $flgBandConf    = ($flgBandConf     == '') ? NULL : $flgBandConf;

            $data['tablaAsigGrafo'] = $this->tablaBandejaCotizacion($idSubProyecto, NULL, $idSituacion, $idJefatura, $idEmpresaColab, $flgBandConf);
            
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getMotivoRechazoCotizacion() {
        $flg =1; //SI NECESITO EL ID SISEGO
        $data['cmbMotivo'] = __buildCmbMotivo(FLG_MOTIVO_RECHAZO_COTIZACION, $flg);
        echo json_encode(array_map('utf8_encode', $data));
    }

    function rechazarCotizacionSisego() {
        $data['msj']   = NULL;
        $data['error'] = EXIT_ERROR;
        try {
            $this->db->trans_begin();

            $idMotivoSisego   = $this->input->post('idMotivoSisego');
            $idMotivoPlanObra = $this->input->post('idMotivoPlanObra');
            $codigoCluster    = $this->input->post('codigo_cluster');
            $comentario       = $this->input->post('comentario');

            $idUsuario     = $this->session->userdata('idPersonaSession');

            if($idUsuario == null || $idUsuario == '') {
                throw new Exception('error, ah caducado la sesi&oacute;n, cargue la p&aacute;gina y vuelva a logearse.');
            }
             
            if($codigoCluster == null || $codigoCluster == '') {
                throw new Exception('error, codigo null, comunicarse con el programador.'); 
            }
             
            if($idMotivoSisego == null || $idMotivoSisego == null || $idMotivoPlanObra == null || $idMotivoPlanObra == '') {
                throw new Exception('error, No se ingreso el motivo.');
            }



            $datoArray = array  (
                                    "estado"             => 3,//cotizacion cancelado
                                    'fecha_aprobacion'   => $this->fechaActual(),
                                    'idMotivo'           => $idMotivoPlanObra,
                                    'comentario_rechazo' => $comentario
                                );
            $data = $this->m_planobra->updateEstadoCluster($codigoCluster, $datoArray);

            if($data['error'] == EXIT_ERROR) {
                throw new Exception('error, no se rechaz&oacute;');
            } else {
                $dataSend = [   'codigo' 		=> $codigoCluster,
                                'motivo'        => $idMotivoSisego,
                                'detalle'       => $comentario,
                                'persona'       => $this->session->userdata('usernameSession') ];

                $url = 'https://172.30.5.10:8080/sisego/Requerimientos/rechazarEstudios';

                $response = $this->m_utils->sendDataToURL($url, $dataSend);

                if($response->error == EXIT_SUCCESS){
                    $dataArray = array(
                                            'codigo_cluster'     => $codigoCluster,
                                            'fecha'              => $this->fechaActual(),
                                            'id_usuario'         => $idUsuario,//SISEGO
                                            'estado'             => 3,
                                            'idMotivo'           => $idMotivoPlanObra,
                                            'comentario_rechazo' => $comentario
                                        );
                    $data = $this->m_utils->insertLogCotizacionInd($dataArray);

                    if($data['error'] == EXIT_ERROR) {_log("ENTRO1");
                        throw new Exception('error');
                        $this->db->trans_rollback();
                    } else {
                        $this->db->trans_commit();
                        $this->m_utils->saveLogSigoplus('COTIZACION INDIVIDUAL RECHAZO', $codigoCluster, NULL, NULL, NULL, NULL, NULL, 'TRAMA COMPLETADA', 'OPERACION REALIZADA CON EXITO', 1, 3);
                    }
                    $data['codigo'] = $codigoCluster;                   
                }else{
                    $data['error'] = EXIT_ERROR; 
                    $data['msj'] = 'error al enviar informaci&oacute;n a sisego';
                    $this->db->trans_rollback();
                    $this->m_utils->saveLogSigoplus('COTIZACION INDIVIDUAL RECHAZO', $codigoCluster, NULL, NULL, NULL, NULL, NULL, 'FALLA EN LA RESPUESTA DEL HOSTING', 'OPERACION NO COMPLETADA ERROR EN EL SERVIDOR DEL CLIENTE:'. strtoupper($response->mensaje), '2', 3);
                }
            }
        } catch(Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
	
	function cambiarFlgRobotCoti() {
		$data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $codCotizacion  = $this->input->post('codigo_cotizacion');

            $data = $this->m_bandeja_cotizacion_individual->updateFlgRobot($codCotizacion);
            $data['tablaBanCotizacion'] = $this->tablaBandejaCotizacion(null, null, null, null, null, null);
        } catch(Exception $e) {
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
}