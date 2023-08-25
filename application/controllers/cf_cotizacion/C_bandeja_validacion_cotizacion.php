<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_bandeja_validacion_cotizacion extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_cotizacion/m_bandeja_validacion_cotizacion');
        $this->load->model('mf_cluster/m_bandeja_cluster');
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
               $data['tablaAsigGrafo'] = $this->tablaBandejaValidCotizacion(null, null, null, null);
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');	
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');              
        	   $permisos =  $this->session->userdata('permisosArbol');
        	   #$result = $this->lib_utils->getHTMLPermisos($permisos, 167, 187);
        	   $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_NUEVO_MODELO_COTIZACION, 187, ID_MODULO_PAQUETIZADO);
        	   $data['opciones'] = $result['html'];
        	//    if($result['hasPermiso'] == true){
        	       $this->load->view('vf_cotizacion/v_bandeja_validacion_cotizacion',$data);
        	//    }else{
        	//        redirect('login','refresh');
	        //    }
	   }else{
	       redirect('login','refresh');
	   }
    }
        
    function tablaBandejaValidCotizacion($idSubProyecto, $codigo, $idJefatura, $idEmpresaColab){
        $data = $this->m_bandeja_validacion_cotizacion->getBandejaDataValidacion($idSubProyecto, $codigo, $idJefatura, $idEmpresaColab);
        
        $html = '<table id="data-table" class="table table-bordered" style="font-size: 10px;">
                    <thead class="thead-default">
                        <tr>                 
                            <th>ACCI&Oacute;N</th>                            
                            <th>CODIGO</th>
                            <th>SUBPROYECTO</th>
                            <th>SISEGO</th>
                            <th>EECC</th>
                            <th>MDF</th>
                            <th>JEFATURA</th>
                            <th>Costo Mat</th>
                            <th>Costo Mo</th>
                            <th>Total</th>
                            <th style="text-align:center">Fecha Creacion.</th>
                            <th style="text-align:center">ESTADO</th>
                        </tr>
                    </thead>
                   
                    <tbody>';
                                                            
                foreach($data AS $row) {
                    $btnConfirmar = null;
                    $btnRechazar  = null;
                    $btnCvDoc     = null;
                    
                    $btnDetalleSisego = '<i style="color:#A4A4A4;cursor:pointer" class="zmdi zmdi-hc-2x zmdi-assignment-o" data-codigo_cotizacion="'.$row['codigo_cluster'].'" title="Detalle" onclick="openModalDatosSisegos($(this))"></i>';
                 
                    if($row['flg_validacion'] != 1) {
                        $btnConfirmar = '<i style="color:#A4A4A4;cursor:pointer;" data-codigo_cluster="'.$row['codigo_cluster'].'" 
                                            data-costo_total_mo="'.$row['costo_mano_obra_sisego'].'" data-nodo="'.$row['nodo'].'" data-costo_mat="'.$row['costo_materiales'].'"
                                            data-id_tipo_diseno="'.$row['id_tipo_diseno'].'" data-duracion="'.$row['duracion'].'" data-costo_diseno="'.$row['costo_diseno'].'"
                                            data-cod_ebc="'.$row['cod_ebc'].'" class="zmdi zmdi-hc-2x zmdi-check-circle" title="Confirmar" 
                                        onclick="openModalAlertaConfirmacion($(this))"></i>';
                        
                        $btnRechazar  = '<i style="color:#A4A4A4;cursor:pointer;" data-codigo_cluster="'.$row['codigo_cluster'].'"  
                                            class="zmdi zmdi-hc-2x zmdi-close-circle" title="Rechazar" onclick="openModalAlertaRechazar($(this))"></i>';
                    }     
                    $btnCvDoc =   '<a><i style="color:#A4A4A4;cursor:pointer"  data-codigo_cotizacion="'.$row['codigo_cluster'].'" 
                                       class="zmdi zmdi-hc-2x zmdi-case-download" onclick="zipArchivosForm($(this));" title="Descargar Documentos"></i></a>';
                                        
                    $html .=' <tr>                                                   
                                <td>'.$btnConfirmar.' '.$btnRechazar.' '.$btnCvDoc.' '.$btnDetalleSisego.'</td>
                                <td>'.$row['codigo_cluster'].'</td>
                                <td>'.$row['subProyectoDesc'].'</td>
                                <td>'.$row['sisego'].'</td>
                                <td>'.$row['empresaColabDesc'].'</td>
                                <td>'.$row['codigoMdf'].'</td>
                                <td>'.$row['jefatura'].'</td>
                                <td>'.$row['costo_materiales'].'</td>
                                <td>'.$row['costoTotalMo'].'</td>
                                <td>'.$row['costo_total'].'</td>
                                <td>'.$row['fecha_registro'].'</td>
                                <td>'.$row['estadoDesc'].'</td>	
                            </tr>';
                }  
   			  
			 $html .='</tbody>
                </table>';
                    
        return utf8_decode($html);
    }

    function validarEnviarCotizacion() {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try{
            $this->db->trans_begin();

            $codigoCotizacion = $this->input->post('codigo_cotizacion');
            $costo_mo_total   = $this->input->post('costo_mo_total');   
            $nodo             = $this->input->post('nodo');   
            $costo_mat        = $this->input->post('costo_mat');   
            $idTipoDiseno     = $this->input->post('idTipoDiseno');   
            $duracion         = $this->input->post('duracion');   
            $costo_diseno     = $this->input->post('costo_diseno');
			$cod_ebc          = $this->input->post('cod_ebc');
            
            $idUsuario = $this->session->userdata('idPersonaSession');
            if($idUsuario == null || $idUsuario == '') {
                 throw new Exception('La sesi&oacute;n espir&oacute;, recargue la p&aacute;gina.');   
            }

            if($codigoCotizacion == null) {
                throw new Exception('error, no tiene codigo de cotizaci&oacute;n.'); 
            }

            if($idTipoDiseno == null) {
                throw new Exception('error, no tiene tipo disen&ntilde;o.'); 
            }

            $arrayData = array  ( 
                                    'fecha_validacion'  => $this->fechaActual(),
                                    'id_usuario_valida' => $idUsuario,
                                    'flg_validacion'    => 1
                                );

            $data = $this->m_bandeja_validacion_cotizacion->updateValid($codigoCotizacion, $arrayData);

            if($data['error'] == EXIT_ERROR){
                throw new Exception('Error interno al registrar.');
            } else {
                $dataUpdate = array(
                                        'estado'                      => 1,
                                        'flg_rech_conf_ban_conf'      => 1,
                                        'fecha_envio_cotizacion'      => $this->fechaActual(),
                                        'usuario_envio_cotizacion'    => $idUsuario
                                    );

                $data = $this->m_bandeja_cluster->updateClusterPadre($codigoCotizacion, $dataUpdate);
                

                if($data['error'] == EXIT_ERROR) {
                    throw new Exception('Error interno al registrar.');
                } else {
                    $dataArray = array  (
                                            'codigo_cluster' => $codigoCotizacion,
                                            'fecha'          => $this->fechaActual(),
                                            'id_usuario'     => $idUsuario,//SISEGO
                                            'estado'         => 5
                                        );
                    $data = $this->m_utils->insertLogCotizacionInd($dataArray);
                    if($data['error'] == EXIT_ERROR){
                        throw new Exception('error, al insertar el log.');
                    }else{
                        $infoCoti = $this->m_utils->getRowCotizacion($codigoCotizacion);
                        $dataSend = [   'codigo' 		=> $codigoCotizacion,
                                        'materiales'    => $costo_mat,
                                        'mano_obra'     => $costo_mo_total,
                                        'nodo'          => $nodo,
                                        'duracion'      => $duracion,
                                        'tipo_diseno'   => $idTipoDiseno,
                                        'diseno'        => $costo_diseno,
                                        'comentario'    => $infoCoti['comentario'],
										'ebc'           => $cod_ebc
                                    ];
        
                        $url = 'https://172.30.5.10:8080/sisego/Requerimientos/cotizarEstudio';
        
                        $response = $this->m_utils->sendDataToURL($url, $dataSend);

                        if($response->error == EXIT_SUCCESS){
                            $dataArray = array  (
                                                    'codigo_cluster' => $codigoCotizacion,
                                                    'fecha'          => $this->fechaActual(),
                                                    'id_usuario'     => $idUsuario,//SISEGO
                                                    'estado'         => 1
                                                );
                            $data = $this->m_utils->insertLogCotizacionInd($dataArray);
                            if($data['error'] == EXIT_ERROR) {
                                throw new Exception('error');
                                $this->db->trans_rollback();
                            } else {
                                $this->db->trans_commit();
                                $this->m_utils->saveLogSigoplus('COTIZACION INDIVIDUAL VALIDA', $codigoCotizacion, NULL, NULL, NULL, NULL, NULL, 'TRAMA COMPLETADA', 'OPERACION REALIZADA CON EXITO: '.strtoupper($response->mensaje), 1, 3, $response);
                            }
                        }else{
                            $data['error'] = EXIT_ERROR;
                            $data['msj']   = 'No llega la cotizaci&oacute;n a sisego web';
                            $this->db->trans_rollback();
                            $this->m_utils->saveLogSigoplus('COTIZACION INDIVIDUAL VALIDA', $codigoCotizacion, NULL, NULL, NULL, NULL, NULL, 'FALLA EN LA RESPUESTA DEL HOSTING', strtoupper($response->mensaje), 2, 3, $response);
                        }
                    }
                }
            }

            $data['tablaBandeja'] = $this->tablaBandejaValidCotizacion(null, null, null, null);
        } catch(Exception $e){
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function rechazarCotizacion() {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try{
            $this->db->trans_begin();

            $codigoCotizacion = $this->input->post('codigo_cotizacion');
            $observacion      = $this->input->post('observacion');

            $idUsuario = $this->session->userdata('idPersonaSession');
            if($idUsuario == null || $idUsuario == '') {
                 throw new Exception('La sesi&oacute;n espir&oacute;, recargue la p&aacute;gina.');   
            }

            if($codigoCotizacion == null) {
                throw new Exception('error, no tiene codigo de cotizaci&oacute;n.'); 
            }
            
            $arrayData = array  ( 
                                    'fecha_validacion'  => $this->fechaActual(),
                                    'id_usuario_valida' => $idUsuario,
                                    'flg_validacion'    => 2,
                                    'observacion'       => $observacion
                                );

            $data = $this->m_bandeja_validacion_cotizacion->updateValid($codigoCotizacion, $arrayData);

            if($data['error'] == EXIT_ERROR){
                throw new Exception('Error interno al registrar.');
            } else {
                $dataUpdate = array(
                                    'nodo_principal'              => NULL,
                                    'nodo_respaldo'               => NULL,
                                    'facilidades_de_red'          => NULL,
                                    'cant_cto'                    => NULL,
                                    'metro_tendido_aereo'         => NULL,
                                    'metro_tendido_subterraneo'   => NULL,
                                    'metors_canalizacion'         => NULL,
                                    'cant_camaras_nuevas'         => NULL,
                                    'cant_postes_nuevos'          => NULL,
                                    'cant_postes_apoyo'           => NULL,
                                    'requiere_seia'               => NULL,
                                    'requiere_aprob_mml_mtc'      => NULL,
                                    'requiere_aprob_inc'          => NULL,
                                    'costo_materiales'            => NULL,
                                    'costo_mano_obra'             => NULL,
                                    'costo_diseno'                => NULL,
                                    'costo_expe_seia_cira_pam'    => NULL,
                                    'costo_adicional_rural'       => NULL,
                                    'costo_total'                 => NULL,
                                    'tiempo_ejecu_planta_externa' => NULL,
                                    'estado'                      => 0,
                                    'fecha_envio_cotizacion'      => NULL,
                                    'usuario_envio_cotizacion'    => NULL,
                                    'duracion'                    => NULL,
                                    'id_tipo_diseno'              => NULL,
                                    'ubic_perfil'                 => NULL,
                                    'ubic_sisego'                 => NULL,
                                    'ubic_rutas'                  => NULL,
                                    'cant_apertura_camara'        => NULL,
                                    'flg_rech_conf_ban_conf'      => 2
                                );

                $data = $this->m_bandeja_cluster->updateClusterPadre($codigoCotizacion, $dataUpdate);
                
                if($data['error'] == EXIT_ERROR){
                    throw new Exception('Error interno al registrar.');
                }else{
                    $dataArray = array  (
                                            'codigo_cluster' => $codigoCotizacion,
                                            'fecha'          => $this->fechaActual(),
                                            'id_usuario'     => $idUsuario,//SISEGO
                                            'estado'         => 6
                                        );
                    $data = $this->m_utils->insertLogCotizacionInd($dataArray);
                    if($data['error'] == EXIT_ERROR) {
                        $this->db->trans_rollback();
                    } else {
                        $this->db->trans_commit();
                    }
                }
            }

            $data['tablaBandeja'] = $this->tablaBandejaValidCotizacion(null, null, null, null);
        } catch(Exception $e){
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
            //$this->m_utils->saveLogSigoplus('TRAMA COTIZACION INDIVIDUAL VALIDA', $codigoCotizacion, NULL, NULL, NULL, NULL, NULL, 'ERROR EN RECEPCION DE TRAMA', $e->getMessage(), 2, 3);
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function filtrarValidCotizacion()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $idSubPro       = $this->input->post('idSubPro');
            $idEmpresaColab = $this->input->post('idEmpresaColab');
            $idJefatura     = $this->input->post('idJefatura');
            $idSituacion    = $this->input->post('idSituacion');
            
            $idSubProyecto  = ($idSubPro       == '') ? NULL : $idSubPro;
            $idEmpresaColab = ($idEmpresaColab == '') ? NULL : $idEmpresaColab;
            $idSituacion    = ($idSituacion    == '') ? NULL : $idSituacion;
            $idJefatura     = ($idJefatura     == '') ? NULL : $idJefatura;

            $data['tablaAsigGrafo'] = $this->tablaBandejaCotizacion($idSubProyecto, NULL, $idSituacion, $idJefatura, $idEmpresaColab);
            
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    } 

    public function fechaActual()
    {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone', 'America/Lima');
        setlocale(LC_TIME, "es_ES", "esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }
        
}