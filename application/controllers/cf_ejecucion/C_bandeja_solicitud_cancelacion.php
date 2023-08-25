<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_bandeja_cancelacion extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_plan_obra/m_consulta');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    function index() {
        $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
            $data["extra"]=' <link rel="stylesheet" href="'.base_url().'public/bower_components/notify/pnotify.custom.min.css">
                            <link rel="stylesheet" href="'.base_url().'public/bower_components/bootstrap-validator/bootstrapValidator.min.css"></link>   
                            <link href="'.base_url().'public/vendors/bower_components/datatables/media/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/><link rel="stylesheet" href="'.base_url().'public/fancy/source/jquery.fancybox.css" type="text/css" media="screen">
                            <link rel="stylesheet" href="'.base_url().'public/css/jasny-bootstrap.min.css">';
            $data["pagina"]="BANDEJA CANCELACION";
            $data['tablaSolicitudCancelados'] = $this->getBandejaSolicitud();
            $permisos =  $this->session->userdata('permisosArbol');
            $this->load->view('vf_layaout_sinfix/header',$data);
            $this->load->view('vf_layaout_sinfix/cabecera');
            $this->load->view('vf_layaout_sinfix/menu');
            $this->load->view('vf_ejecucion/v_bandeja_solicitud_cancelacion',$data);
            $this->load->view('vf_layaout_sinfix/footer');
	   }else{
	       redirect('login','refresh');
	   }
    }

    function getBandejaSolicitud() {
        $arrayData=$this->m_utils->getDataBandejaSolicitud(FLG_SOLICITUD_CANCELACION);   
        $html='
           <table id="simpletable" class="table table-hover display  pb-30 table-striped table-bordered nowrap" >
               <thead>
                     <tr class="table-primary">
                     <th>Acci&oacute;n</th>
                     <th>ItemPlan</th>
                     <th>Subproyecto</th>
                     <th>Estado Actual</th>
                     <th>Fecha Solicitud</th>
                     <th>eecc</th>
                     <th>Jefatura</th>
                     </tr>
               </thead>
               <tbody>';            
        foreach($arrayData as $row){
            $boton_truncar='<a data-toggle="tooltip" data-trigger="hover" data-placement="top" style="cursor:pointer"  data-original-title="Cancelar" 
                            data-id_estadoplan="'.$row->idEstadoPlan.'" data-itemplan="'.$row->itemplan.'" onclick="openModalAlert($(this))"><i class="fa fa-ban"></i></a>';
            $html.='<tr>
                    <td>
                    '.$boton_truncar.'                
                    </td>
                    <td>'.$row->itemplan.'</td> 
                    <td>'.$row->subProyectoDesc.'</td>
                    <td>'.$row->estadoPlanDesc.'</td>                 
                    <td>'.$row->fechaSolicitud.'</td>
                    <td>'.$row->empresaColabDesc.'</td>
                    <td>'.$row->jefatura.'</td>
                    </tr>';
        }
        $html.="</tbody>
            </table>";    
        return $html;
    }

    function tramaSolicitudCancelacion() {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null; 
        try {
            header('Access-Control-Allow-Origin: *');

            $itemplan = $this->input->post('itemplan');
            $sisego   = $this->input->post('sisego'); 

            if($itemplan == null || $itemplan == '') {
                throw new Exception('no tiene itemplan');
            }

            if($sisego == null || $sisego == '') {
                throw new Exception('no tiene indicador');
            }
			
			$flg_paquetizado = $this->m_utils->getFlgPaquetizadoPo($itemplan);
            $idEstadoPlan = $this->m_utils->getEstadoPlanByItemplan($itemplan);
			
			if($flg_paquetizado == 2) {
				if(in_array($idEstadoPlan, array(ID_ESTADO_EN_APROBACION, ID_ESTADO_EN_LICENCIA, ID_ESTADO_DISENIO))) {
                    if($data['error'] == EXIT_SUCCESS) {
                        $dataUpdate = array(
                                                "idEstadoPlan" => ID_ESTADO_CANCELADO,
                                                "usu_upd"      => "SISEGO WEB",
                                                "fecha_upd"    => $this->fechaActual(),
                                                "descripcion"  => "SOLICITUD DE SISEGO WEB",
												"fechaCancelacion" => $this->fechaActual()
                                            );
                        $data = $this->m_utils->simpleUpdateEstadoPlanObra($itemplan,  $dataUpdate);
                        
                        $data['tipo']  = FLG_CANCELACION_CONFIRMADA;
                        $this->m_utils->saveLogSigoplus('TRAMA CANCELACION CONFIRMADA', null , $itemplan, null, $sisego, null, null, 'TRAMA COMPLETADA', 'OPERACION REALIZADA CON EXITO', 1);
                    } else {
                        $this->m_utils->saveLogSigoplus('TRAMA CANCELACION CONFIRMADA', null, $itemplan, null, $sisego, null, null, 'FALLA EN LA RESPUESTA DEL HOSTING', 'OPERACION NO COMPLETADA ERROR EN EL SERVIDOR DEL CLIENTE:'. strtoupper($response->mensaje), '2');
                    }
                } else {
                    $dataArray['flgSolicitudCancelacion'] = FLG_SOLICITUD_CANCELACION; 
                    $dataArray['fechaSolicitud'] = $this->fechaActual();

                    // $data['tipo']  = FLG_SOLICITUD_CANCELACION;
                    $data = $this->m_utils->updateFlgCancelacion($itemplan, $dataArray);

                    if($data['error'] == EXIT_SUCCESS) {
                        $data['tipo']  = 2;
                        $this->m_utils->saveLogSigoplus('TRAMA SOLICITUD CANCELACION', null , $itemplan, null, $sisego, null, null, 'TRAMA COMPLETADA', 'OPERACION REALIZADA CON EXITO', 1);
                    } else {
                        $this->m_utils->saveLogSigoplus('TRAMA SOLICITUD CANCELACION', null, $itemplan, null, $sisego, null, null, 'FALLA EN LA RESPUESTA DEL HOSTING', 'OPERACION NO COMPLETADA ERROR EN EL SERVIDOR DEL CLIENTE:'. strtoupper($response->mensaje), '2');
                    }
                }
			} else {
                if(in_array($idEstadoPlan, array(ID_ESTADO_PRE_DISENIO, ID_ESTADO_DISENIO, ID_ESTADO_DISENIO_PARCIAL, ID_ESTADO_DISENIO_EJECUTADO))) {
                    $dataUpdate = array(
                        "idEstadoPlan" => ID_ESTADO_CANCELADO,
                        "descripcion"  => "SOLICITUD DE SISEGO WEB",
						"fechaCancelacion" => $this->fechaActual()
                    );
                    $data = $this->m_utils->simpleUpdateEstadoPlanObra($itemplan,  $dataUpdate);

                    if($data['error'] == EXIT_SUCCESS) {
                        $data['tipo']  = FLG_CANCELACION_CONFIRMADA;
                        $this->m_utils->saveLogSigoplus('TRAMA CANCELACION CONFIRMADA', null , $itemplan, null, $sisego, null, null, 'TRAMA COMPLETADA', 'OPERACION REALIZADA CON EXITO', 1);
                    } else {
                        $this->m_utils->saveLogSigoplus('TRAMA CANCELACION CONFIRMADA', null, $itemplan, null, $sisego, null, null, 'FALLA EN LA RESPUESTA DEL HOSTING', 'OPERACION NO COMPLETADA ERROR EN EL SERVIDOR DEL CLIENTE:'. strtoupper($response->mensaje), '2');
                    }

                } else {
                    $dataArray['flgSolicitudCancelacion'] = FLG_SOLICITUD_CANCELACION; 
                    $dataArray['fechaSolicitud'] = $this->fechaActual();

                    // $data['tipo']  = FLG_SOLICITUD_CANCELACION;
                    $data = $this->m_utils->updateFlgCancelacion($itemplan, $dataArray);

                    if($data['error'] == EXIT_SUCCESS) {
                        $data['tipo']  = 2;
                        $this->m_utils->saveLogSigoplus('TRAMA SOLICITUD CANCELACION', null , $itemplan, null, $sisego, null, null, 'TRAMA COMPLETADA', 'OPERACION REALIZADA CON EXITO', 1);
                    } else {
                        $this->m_utils->saveLogSigoplus('TRAMA SOLICITUD CANCELACION', null, $itemplan, null, $sisego, null, null, 'FALLA EN LA RESPUESTA DEL HOSTING', 'OPERACION NO COMPLETADA ERROR EN EL SERVIDOR DEL CLIENTE:'. strtoupper($response->mensaje), '2');
                    }
                } 
                // else if(in_array($idEstadoPlan, array(ID_ESTADO_PRE_LIQUIDADO, ID_ESTADO_TERMINADO))) {
                //     $dataSend = ['itemplan' => $itemplan,
                //                  'fecha'    => $this->fechaActual(),
                //                  'estado'   => FLG_TRUNCA_CONFIRMADA];

                //     $url = 'https://172.30.5.10:8080/obras2/recibir_can.php';
                    
                //     $response = $this->m_utils->sendDataToURL($url, $dataSend);

                //     if($response['error'] == EXIT_SUCCESS){
                //             $this->m_utils->saveLogSigoplus('TRAMA SOLICITUD - NO SE CANCELA', null , $itemplan, null, $sisego, null, null, 'TRAMA COMPLETADA', 'OPERACION REALIZADA CON EXITO', 1);
                //     }else{
                //         $this->m_utils->saveLogSigoplus('TRAMA SOLICITUD CANCELACION RESPUESTA', null, $itemplan, null, $sisego, null, null, 'FALLA EN LA RESPUESTA DEL HOSTING', 'OPERACION NO COMPLETADA ERROR EN EL SERVIDOR DEL CLIENTE:'. strtoupper($response->mensaje), '2');
                //     } 
                // }
            }
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->m_utils->saveLogSigoplus('TRAMA SOLICITUD CANCELACION',  NULL, $itemplan, '', $sisego, NULL, NULL, 'ERROR EN RECEPCION DE TRAMA', $e->getMessage(), 2);            
        }
    }

    function cancelarItemplan() {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null; 
        try {
            $itemplan     = $this->input->post('itemplan');  
            $idEstadoPlan = $this->input->post('idEstadoPlan');
			$idUsuario    = $this->session->userdata('idPersonaSession');
			
            if($itemplan == null || $itemplan == '') {
                $data['msj2'] = 'comunicarse con el programador';
                throw new Exception('No se ingreso el itemplan');
            }

            if($idEstadoPlan == null || $idEstadoPlan == '') {
                $data['msj2'] = 'comunicarse con el programador';
                throw new Exception('No se ingreso el estado plan');
            }

            if(in_array($idEstadoPlan, array(ID_ESTADO_PRE_LIQUIDADO, ID_ESTADO_TERMINADO, ID_ESTADO_EN_CERTIFICACION, ID_ESTADO_EN_VALIDACION, ID_ESTADO_EN_APROBACION))) {
                $data['msj2'] = 'motivo: itemplan terminado o preliquidado';
                throw new Exception('No se puede cancelar este itemplan ya que se encuentra en un estado imposible de efectuar esta acción.');
            }
			
			if($idUsuario == null || $idUsuario == '') {
                throw new Exception('sesi&oacute;n expirada, cargue la p&aacute;gina.');
            }

            $flg_paquetizado = $this->m_utils->getFlgPaquetizadoPo($itemplan);

            if($flg_paquetizado == 2) {
                $dataUpdate = array(
                    "idEstadoPlan" => ID_ESTADO_CANCELADO,
                    "usu_upd"      => "SISEGO WEB",
                    "fecha_upd"    => $this->fechaActual(),
                    "descripcion"  => "SOLICITUD DE SISEGO WEB",
					"fechaCancelacion" => $this->fechaActual()
                );

            } else {
                $dataUpdate = array(
                                        "idEstadoPlan" => ID_ESTADO_CANCELADO,
                                        "descripcion"  => "SOLICITUD DE SISEGO WEB",
										"fechaCancelacion" => $this->fechaActual()
                                    );
            }

            $data = $this->m_utils->simpleUpdateEstadoPlanObra($itemplan, $dataUpdate);
            $dataArray['flgSolicitudCancelacion'] = FLG_CANCELACION_CONFIRMADA;

            $data = $this->m_utils->updateFlgCancelacion($itemplan, $dataArray);


            $dataSend = ['itemplan' => $itemplan,
                            'estado'   => FLG_CANCELACION_CONFIRMADA];

            $url = 'https://172.30.5.10:8080/obras2/recibir_can.php';

            $response = $this->m_utils->sendDataToURL($url, $dataSend);

            if($response['error'] == EXIT_SUCCESS){
                $this->m_utils->saveLogSigoplus('TRAMA CANCELACION CONFIRMADA', null , $itemplan, null, $sisego, null, null, 'TRAMA COMPLETADA', 'OPERACION REALIZADA CON EXITO', 1);
            }else{
                $this->m_utils->saveLogSigoplus('TRAMA CANCELACION CONFIRMADA', null, $itemplan, null, $sisego, null, null, 'FALLA EN LA RESPUESTA DEL HOSTING', 'OPERACION NO COMPLETADA ERROR EN EL SERVIDOR DEL CLIENTE:'. strtoupper($response->mensaje), '2');
            } 
            
            $data['tablaSolicitudCancelados'] = $this->getBandejaSolicitud();
        }catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function truncarItemplan() {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null; 
        try {
            $itemplan     = $this->input->post('itemplan');  
            $idEstadoPlan = $this->input->post('idEstadoPlan');

            if($itemplan == null || $itemplan == '') {
                $data['msj2'] = 'comunicarse con el programador';
                throw new Exception('No se ingreso el itemplan');
            }
            
            $dataArray['flgSolicitudCancelacion'] = FLG_TRUNCA_CONFIRMADA;

            $data = $this->m_utils->updateFlgCancelacion($itemplan, $dataArray);

            $dataSend = ['itemplan' => $itemplan,
                         'fecha'    => $this->fechaActual(),
                         'estado'   => FLG_TRUNCA_CONFIRMADA];

            $url = 'https://172.30.5.10:8080/obras2/recibir_can.php';

            $response = $this->m_utils->sendDataToURL($url, $dataSend);

            if($response['error'] == EXIT_SUCCESS){
                $this->m_utils->saveLogSigoplus('BANDEJA CANCELACION TRUNCA', null , $itemplan, null, $sisego, null, null, 'TRAMA COMPLETADA', 'OPERACION REALIZADA CON EXITO', 1);
            }else{
                $this->m_utils->saveLogSigoplus('BANDEJA CANCELACION TRUNCA', null, $itemplan, null, $sisego, null, null, 'FALLA EN LA RESPUESTA DEL HOSTING', 'OPERACION NO COMPLETADA ERROR EN EL SERVIDOR DEL CLIENTE:'. strtoupper($response->mensaje), '2');
            } 
            $data['tablaSolicitudCancelados'] = $this->getBandejaSolicitud();
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
}