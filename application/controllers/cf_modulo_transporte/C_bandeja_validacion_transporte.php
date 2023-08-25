<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_bandeja_validacion_transporte extends CI_Controller {
    private $_idZonal  = null;
    private $_itemPlan = null;
    function __construct(){
        parent::__construct();
        $this->load->model('mf_ejecucion/M_pendientes');
        $this->load->model('mf_ejecucion/M_generales');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_transporte/m_liquidacion_obra_transporte');
        $this->load->model('mf_ejecucion/M_actualizar_porcentaje');   
        $this->load->model('mf_plantaInterna/M_aprobacion_interna');
        $this->load->model('mf_plantaInterna/M_plantaInterna'); 
        $this->load->model('mf_control_presupuestal/m_control_presupuestal');
        $this->load->helper('url');
        $this->load->library('lib_utils');
        $this->load->library('zip');
        $this->load->library('table');
    }

    function index() {
        $logedUser = $this->session->userdata('usernameSession');
        if($logedUser != null){
            $data['listaSubProy']  = $this->m_utils->getAllSubProyectoTrasporte();
            $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
            $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
            $data['tablaBandejaValidacion'] = $this->getBandejaValidacion(NULL, NULL);
            $data['title'] = 'BANDEJA DE VALIDACI&Oacute;N';
            $permisos =  $this->session->userdata('permisosArbol');
            #$result   = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_PLANTA_INTERNA, ID_PERMISO_HIJO_BANDEJA_VALIDACION);
            $result = $this->lib_utils->getHTMLPermisos($permisos, 309, 315, 8);
            $data['opciones'] = $result['html'];
            if($result['hasPermiso'] == true){
                $this->load->view('vf_modulo_transporte/v_bandeja_validacion_transporte',$data);
            }else{
                redirect('login','refresh');
            }
        }else{
            redirect('login','refresh');
        }
    }
    

    function getBandejaValidacion($itemplan, $idSubProyecto) {
        $arrayEstadoPlan = array(9, 4);
        $arrayData = $this->m_liquidacion_obra_transporte->getDataPlanobraLiqui($itemplan, array(9,4), $idSubProyecto);
        $btnTerminar = null;
        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr style="color: white ; background-color: #3b5998" width="10%">
                            <th>Acci&oacute;n</th>
                            <th>ItemPlan</th>
                            <th>Subproyecto</th>
                            <th>Indicador</th>
                            <th>Estado</th>
                            <th>Central</th>  
                            <th>F. PreLiquidaci&oacute;n</th>  
                            <th>F. Validaci&oacute;n</th>  
                            <th>F. Fin Prev</th>
                            <th>Compromiso</th>
                            <th>F. Inicio Prev</th>
                            <th>eecc</th>
                            <th>Jefatura</th>                 
                        </tr>
                    </thead>                    
                    <tbody>';
        foreach($arrayData as $row) {
            if($row['fechaPrevEjec']){
                $fasig=explode("-",$row['fechaPrevEjec']);
                $rfecha=$fasig[2]."/".$fasig[1]."/".$fasig[0];
            }else{
                $rfecha="";
            }

            if($row['fechaInicio']){   
                $nuevafecha = strtotime ( '+30 day' , strtotime ( $row['fechaInicio']) ) ;
                $nuevafecha = date ( 'd-m-Y' , $nuevafecha );    
            }else{
                $nuevafecha = "";    
            }
            $botonZipEvidencia = ' <a data-toggle="tooltip" data-trigger="hover" data-original-title="descarga zip de las evidencias" data-item_plan="'.$row['itemplan'].'" style="cursor:pointer" onclick="zipItemPlan($(this));"><i class="zmdi zmdi-hc-2x zmdi zmdi-download"></i></a>';
            if($row['idEstadoPlan'] == ID_ESTADO_PRE_LIQUIDADO) {        
                $btnTerminar = '<a data-toggle="tooltip" data-trigger="hover" data-original-title="Terminar Obra"  data-itemplan="'.$row['itemplan'].'" data-id_subproyecto="'.$row['idSubProyecto'].'" data-tipo_planta="'.ID_TIPO_PLANTA_INTERNA.'" onclick="validarObra($(this));"><i class="zmdi zmdi-hc-2x zmdi-check-circle"></i></a>';
            }
            
            $html.= '<tr>
                        <td>
                        <a data-toggle="tooltip" data-trigger="hover" data-original-title="Cotizaci&oacute;n" href="#" class="ver_ptr" data-itemplan="'.$row['itemplan'].'" data-id_estado_plan="'.$row['idEstadoPlan'].'" data-id_subproyecto="'.$row['idSubProyecto'].'" onclick="openModalPTR($(this));"><i class="zmdi zmdi-hc-2x zmdi-money-box"></i></a>
                        '.$botonZipEvidencia.$btnTerminar.'
                        
                        </td>
                        <td>'.$row['itemplan'].'</td> 
                        <td>'.$row['subProyectoDesc'].'</td>                
                        <td>'.$row['indicador'].'</td>
                        <td>'.$row['estadoPlanDesc'].'</td>
                        <td>'.$row['tipoCentralDesc'].'</td>
                        <td>'.$row['fechaPreLiquidacion'].'</td>
                        <td>'.$row['fechaEjecucion'].'</td>
                        <td>'.$rfecha.'</td>
                        <td>'.$nuevafecha.'</td>
                        <td>'.$row['fechaInicio'].'</td>
                        <td>'.$row['empresaColabDesc'].'</td>
                        <td>'.$row['zonalDesc'].'</td>
                    </tr>';
        }
        $html .='   </tbody>
            </table>';

    return utf8_decode($html);
    }
    
    
    function ejecValidacionTransp() {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $this->db->trans_begin();
            $itemplan = $this->input->post('itemplan');

            $arrayDataLog = array(
                'tabla'            => 'plantaInterna',
                'actividad'        => 'Terminar obra-Validado',
                'itemplan'         => $itemplan,
                'fecha_registro'   => $this->fechaActual(),
                'id_usuario'       => $this->session->userdata('idPersonaSession'),
                'idEstadoPlan'     => 4
             );

            $flg = $this->m_utils->registrarLogPlanObra($arrayDataLog);

            if($flg == 0) {
                throw new Exception('No se registro el log');
            }
            
            $data = $this->M_plantaInterna->validaItemplan($itemplan, $this->fechaActual());

            if($data['error'] == EXIT_ERROR) {
                throw new Exception('No se valido');
            }

            $countPendienteExceso = $this->m_control_presupuestal->getCountValida($itemplan, NULL);

            if($countPendienteExceso > 0) {
                $data['error'] = EXIT_ERROR;
                throw new Exception('Esta obra tiene una solicitud de exceso pendiente.');
            }

            $countPendienteEdicOc = $this->m_control_presupuestal->getValidOcEdic($itemplan);

            if($countPendienteEdicOc > 0) {
                $data['error'] = EXIT_ERROR;
                throw new Exception("tiene una solicitud OC de edicion pendiente.");
            }
            
            $arrayData   = $this->m_control_presupuestal->getDataSolicitudOc($itemplan); //OC EDICION
            $dataFlgEdic = $this->m_utils->getDataPoByItemplan($itemplan);
			$estadoCerti = 5;
            if($dataFlgEdic['flg_solicitud_edic'] == 1) {// SI SE EDITO HACIA ABAJO SE GENERA DOS OC EDICIO Y CERTI
                if($arrayData['pep1'] != null && $arrayData['pep1'] != '') {     
                    $fechaActual = $this->m_utils->fechaActual();
                    $cod_solicitud = $this->m_utils->getCodSolicitudOC();
					$estadoCerti = 4;
                    $data = $this->m_control_presupuestal->insertSolicitudOcEdi($arrayData, $fechaActual, $cod_solicitud, $dataFlgEdic['costo_mo'], $itemplan);
                
                    if($data['error'] == EXIT_ERROR) {
                        throw new Exception('No se ingresar la solicitud de edicion.');
                    }
                }
            }  
            
            if($arrayData['pep1'] != null && $arrayData['pep1'] != '') {
                $fechaActual = $this->m_utils->fechaActual();
                $cod_solicitud = $this->m_utils->getCodSolicitudOC();
                $data = $this->m_control_presupuestal->insertSolicitudOcCerti($arrayData, $fechaActual, $cod_solicitud, $itemplan, $dataFlgEdic['costo_mo'], $estadoCerti);	
            
                if($data['error'] == EXIT_ERROR) {
                    throw new Exception('No se ingresar la solicitud de certificacion.');
                }
            }

            $this->db->trans_commit();
        }catch(Exception $e){
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function filtrarTablaValidTransp() {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $itemplan      = $this->input->post('itemplan');
            $idSubProyecto = $this->input->post('idSubProyecto');

            $itemplan      = ($itemplan == '') ? NULL : $itemplan;
            $idSubProyecto = ($idSubProyecto == '') ? NULL : $idSubProyecto;
            $data['tablaValid'] = $this->getBandejaValidacion($itemplan, $idSubProyecto);
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