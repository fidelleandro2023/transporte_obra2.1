<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * @author CARLOS ZAVALA C.
 * 18/01/2018
 *
 */
class C_liquidacion extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_liquidacion/m_liquidacion');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->library('excel');
        $this->load->helper('url');
    }
    
	public function index(){
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
    	       $data['listaEECC'] = $this->m_utils->getAllEECC();
        	   $data['listaZonal'] = $this->m_utils->getAllZonalGroup();
        	   $data['listaSubProy'] = $this->m_utils->getAllSubProyecto();
        	   $data['listafase'] = $this->m_utils->getAllFase();
               $data['tablaAsigGrafo'] = $this->makeHTLMTablaAsignarGrafo($this->m_liquidacion->getPtrToLiquidacion('','','','SI','','','',FROM_BANDEJA_APROBACION,'','',2));               
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
               $permisos =  $this->session->userdata('permisosArbol');
        	   #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_BANDEJAS, ID_PERMISO_HIJO_BANDEJA_APROB);
               $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_NUEVO_MODELO_GESTION_VR, ID_PERMISO_HIJO_BANDEJA_APROB, ID_MODULO_PAQUETIZADO);
               $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	         $this->load->view('vf_liquidacion/v_liquidacion',$data);
        	   }else{
        	       redirect('login','refresh');
        	   }
    	 }else{
        	 redirect('login','refresh');
	    }
             
    }
    
    public function asignarGrafo(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $ptr = $this->input->post('id_ptr');
            $grafo = $this->input->post('grafo');
            $from = $this->input->post('from');
            $vale_re = $this->input->post('vale_reserva');
            $area_desc = $this->input->post('areaDesc');
            $itemP = $this->input->post('itemPl');   
            $origen = $this->input->post('origen');
            $tipoArea = $this->input->post('tipo_area');
            
            $this->db->trans_begin();
            
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }
            
            if($origen  ==  1){//PTR DE WEB UNIFICADA
                $infoItemplan = $this->m_utils->getInfoItemplanLiquidacionSisegos($ptr, $itemP);
            }else if($origen    ==  2){// PTR DE WEB PO
                $infoItemplan = $this->m_utils->getInfoItemplanLiquidacionSisegosWebPo($ptr, $itemP);
            }
            
            if($tipoArea!='MO'){//SI NO ES MANO DE OBRA VALIDAR SI YA USARON EL VR
                if($infoItemplan['idProyecto'] != ID_PROYECTO_SISEGOS){
                    $existVr = $this->m_utils->existVROnAprobacion(trim($vale_re));
                    if($existVr > 0){
                        throw new Exception("El vale de Reserva ya se encuentra registrado.");
                    }                
                }
            }
            
            if($origen  ==  1){//PTR DE WEB UNIFICADA
                $data = $this->m_liquidacion->updateDetalleProducto($ptr,$grafo,$from,$vale_re,$area_desc,$itemP);
            }else if($origen    ==  2){// PTR DE WEB PO
                $data = $this->m_liquidacion->aprobarPOWebPO($ptr, $itemP, $this->fechaActual(), $from, $vale_re);
            }
            if($data['error'] == EXIT_ERROR){
                throw new Exception($data['msj']);
            }
            log_message('error', 'pASO TRHOW.');
            $SubProy = $this->input->post('subProy');
            $eecc = $this->input->post('eecc');
            $zonal = $this->input->post('zonal');
            $itemPlan = $this->input->post('item');
            $mesEjec = $this->input->post('mes');
            $area = $this->input->post('area');
            $estado = $this->input->post('estado');
            $ano= $this->input->post('ano');
            /*
            if($origen  ==  1){//PTR DE WEB UNIFICADA
                $infoItemplan = $this->m_utils->getInfoItemplanLiquidacionSisegos($ptr, $itemP);
            }else if($origen    ==  2){// PTR DE WEB PO
                $infoItemplan = $this->m_utils->getInfoItemplanLiquidacionSisegosWebPo($ptr, $itemP);
            }
            */
            //************************** ACTUALIZAR PLAN OBRA A TERMINADO ********************************//
           if( $infoItemplan['idEstadoPlan']    ==  ID_ESTADO_DISENIO || $infoItemplan['idEstadoPlan']    ==  ID_ESTADO_DISENIO_EJECUTADO || $infoItemplan['idEstadoPlan']    ==  ID_ESTADO_DISENIO_PARCIAL || $infoItemplan['idEstadoPlan']    ==  ID_ESTADO_EN_APROBACION){
                if($infoItemplan['idEstacion'] == ID_ESTACION_FO || $infoItemplan['idEstacion'] == ID_ESTACION_COAXIAL || $infoItemplan['idEstacion'] == ID_ESTACION_FO_ALIM || $infoItemplan['idEstacion'] == ID_ESTACION_FO_DIST){
                    if($infoItemplan['idEstacion'] == ID_ESTACION_FO_ALIM || $infoItemplan['idEstacion'] == ID_ESTACION_FO_DIST){// CAMBIO CZAVALACAS 16.10.2019
						$hasDisenoEjec = $this->m_liquidacion->estacionEjecutadaDiseno($itemP, ID_ESTACION_FO);                    
						if($hasDisenoEjec   >=  1){
							$data['error']= $this->m_liquidacion->changeEstadoEnObraPlan($itemP, ID_ESTADO_PLAN_EN_OBRA)['error'];
						}else if($hasDisenoEjec ==  0){  
							$data['error']= $this->m_liquidacion->changeEstadoEnObraPlan($itemP, ID_ESTADO_DISENIO_PARCIAL)['error'];
						}
					}else{
						$hasDisenoEjec = $this->m_liquidacion->estacionEjecutadaDiseno($itemP, $infoItemplan['idEstacion']);                    
						if($hasDisenoEjec   >=  1){
							$data['error']= $this->m_liquidacion->changeEstadoEnObraPlan($itemP, ID_ESTADO_PLAN_EN_OBRA)['error'];
						}else if($hasDisenoEjec ==  0){  
							$data['error']= $this->m_liquidacion->changeEstadoEnObraPlan($itemP, ID_ESTADO_DISENIO_PARCIAL)['error'];
						}
					}					
                }
            }
            log_message('error', 'pASO 1.');
            if( $infoItemplan['idProyecto'] == ID_PROYECTO_SISEGOS && 
                $infoItemplan['idEstacion'] == ID_ESTACION_FO && 
                $infoItemplan['tipoArea']   == 'MAT'){

                    $sisego_ptr         = $infoItemplan['poCod'];
                    $sisego_itemplan    = $infoItemplan['itemPlan'];
                    $sisego_eecc = '';
                    if($infoItemplan['eecc']    == 'DOMINIONPERU SOLUCIONES Y SERVICIOS S.A.C.'){
                        $sisego_eecc    = 'DOMINION';
                    }else if($infoItemplan['eecc']    == 'CALATEL'){
                        $sisego_eecc    = 'EZENTIS';
                    }else{
                        $sisego_eecc    = $infoItemplan['eecc'];
                    }                   
                    $sisego_jefatura    = $infoItemplan['jefatura_ptr'];
                    $sisego_fecha       = date("Y-m-d");
                    $sisego_vr          = $vale_re; 
                    $sisego_sisego      = $infoItemplan['indicador'];
                    
                    $dataSend = ['ptr' 		    => $sisego_ptr,
                                'itemplan'      => $sisego_itemplan,
                                'eecc' 		    => $sisego_eecc,
                                'jefatura' 	    => $sisego_jefatura,
                                'fecha' 		=> $sisego_fecha,
                                'vr' 			=> $sisego_vr,
                                'sisego' 		=> $sisego_sisego,
                                'region'        => $infoItemplan['region'],
                                'nodo'          => $infoItemplan['codigo'],
                                'nodoDesc'      => $infoItemplan['tipoCentralDesc']];
                    
                    $url = 'https://172.30.5.10:8080/obras2/recibir_dis.php';
                     log_message('error', 'pASO 4.');
                    $response = $this->m_utils->sendDataToURL($url, $dataSend);
                    if($response->error == EXIT_SUCCESS){log_message('error', 'pASO 5.');
                         $this->m_utils->saveLogSigoplus('BANDEJA DE APROBACIONT PTR FO - MAT', $sisego_ptr, $sisego_itemplan, $sisego_vr, $sisego_sisego, $sisego_eecc, $sisego_jefatura, 'TRAMA COMPLETADA', 'OPERACION REALIZADA CON EXITO:'. strtoupper($response->mensaje), 1, 2);
                    }else{log_message('error', 'pASO 6.');
                        $this->m_utils->saveLogSigoplus('BANDEJA DE APROBACIONT PTR FO - MAT', $sisego_ptr, $sisego_itemplan, $sisego_vr, $sisego_sisego, $sisego_eecc, $sisego_jefatura, 'FALLA EN LA RESPUESTA DEL HOSTING', 'OPERACION NO COMPLETADA ERROR EN EL SERVIDOR DEL CLIENTE:'. strtoupper($response->mensaje), '2', 2);
                    }
                     log_message('error', 'pASO 7.');
            }
            log_message('error', 'pASO 2.');
           /*##################### INICIANDO TRAMA SIOM 13.05.2019 CZAVALACAS ###############################*/
            $se_envio_fo = false;//nuevo para forzar envio de um
            if($infoItemplan['idEstadoPlan']    !=  ID_ESTADO_CANCELADO && $infoItemplan['idEstadoPlan']    !=  ID_ESTADO_CERRADO && $infoItemplan['idEstadoPlan']    !=  ID_ESTADO_TERMINADO && $infoItemplan['idEstadoPlan']    !=  ID_ESTADO_PRE_LIQUIDADO) {//NO REALIZAR ENVIOS DE ITEMPLANS CANCELADOS O CERRADOS.
                if($infoItemplan['tipoArea'] == 'MAT' && $infoItemplan['idEstacion'] != ID_ESTACION_DISENIO) {// SOLO SI LA PO O PTR ES MATERIAL DIFERENTE A DISENO
                    $ejecSiom = true;
                    if($infoItemplan['idEstacion'] == ID_ESTACION_FO || $infoItemplan['idEstacion'] == ID_ESTACION_COAXIAL){//SI ES FO O COAXIAL OBLIGAR LA EJECUCION DEL DISENO
                        $hasDisenoEjecutado = $this->m_liquidacion->estacionEjecutadaDiseno($itemP, $infoItemplan['idEstacion']);
                        //log_message('error', '$hasDisenoEjecutado:'.$hasDisenoEjecutado);
                        if($hasDisenoEjecutado == 0){
                            $ejecSiom  = false;
                            //log_message('error', '$ejecSiom::'.$ejecSiom);                            
                        }
                        if($infoItemplan['idSubProyecto'] == ID_SUBPROYECTO_CALIBRACION_PEXT){//calibracion pext nace en obra.
                            $ejecSiom = true;
                        }
                        if($infoItemplan['idEstadoPlan']    == ESTADO_PLAN_EN_OBRA){//SI YA SE ENCUENTRA EN OBRA NO TOMAR EN CUENTA LA LIQUIDACION DEL DISENO 19.06.2019
                            $ejecSiom = true;
                        }
                    }
                    $countSwitch = $this->m_utils->getCountSwitchSiom($infoItemplan['idEecc'], $infoItemplan['jefatura_ptr'], $infoItemplan['idSubProyecto']);
					if($countSwitch > 0) {//VALIDAMOS SI LA PO ESTA DENTRO DEL SWITCH POR SU EECC, JEFATURA Y SUBPROYECTO
                        $count = $this->m_utils->getCountSiom($itemP, $infoItemplan['idEstacion']);
                        if($count == 0) {//SI AUN NO HAY REGISTRO REALIZAMOS EL ENVIO A SIOM
                            $se_envio_fo = true;
                            $emplazamiento =  $this->m_liquidacion->getEmplazamientoIdSiomByidCentral($infoItemplan['idCentral']);//OBTENEMOS EL ID DEZPLAZAMIENTO DE LA TABLA SIOM_NODOS POR EL ID CENTRAL DE LA PO
                            if($emplazamiento['cant'] >= 1  && $ejecSiom){// SE ENCONTRO NODO
                                $codigo_siom = $this->sendDataToSiom($infoItemplan['idEecc'], $infoItemplan['idEstacion'], $infoItemplan['estacionDesc'], $itemP, $emplazamiento['empl_id'], $ptr);
                                #$codigo_siom = 9999;
                                
                                //validar si no viene nulo
                                if($codigo_siom != null){
                                    $dataSiom = array('itemplan'          => $itemP,
                                        'idEstacion'        => $infoItemplan['idEstacion'],
                                        'ptr'               => $ptr,
                                        'fechaRegistro'     => $this->fechaActual(),
                                        'idUsuarioRegistro' => $this->session->userdata('idPersonaSession'),
                                        'codigoSiom'        => $codigo_siom,
                                        'ultimo_estado'       => 'CREADA',
                                        'fecha_ultimo_estado' => $this->fechaActual()
                                    );
                                    
                                    $dataLogPo = array( 'tabla'            => 'Siom',
                                                        'actividad'        => 'Registrar Siom',
                                                        'itemplan'         => $itemP,
                                                        'fecha_registro'   => $this->fechaActual(),
                                                        'id_usuario'       => $this->session->userdata('idPersonaSession')
                                    );
                                    
                                    $dataEstado = array('codigo_siom'           => $codigo_siom,
                                                        'estado_desc'           => 'CREADA',
                                                        'fechaRegistro'         => $this->fechaActual(),
                                                        'usuario_registro'      => $this->session->userdata('usernameSession'),
                                                        'estado_transaccion'    => 1
                                    );
                                    $this->m_liquidacion->insertSiom($dataSiom, $dataLogPo, $dataEstado);
                                }else{
                                    log_message('error', 'No se recepciono un codigo siom');
                                }
                            }else{
                                if($ejecSiom){
                                    $motivoError = 'NO SE ENCONTRO EMPLAZAMIENTO ID PARA ESE NODO';
                                    $estadoError = 4;//NODO NO ENCONTRADO = 4
                                }else{
                                    $motivoError = 'ESTACION NO EJECUTADA';
                                    $estadoError = 5;//DISENO NO EJECUTADO = 5
                                    $se_envio_fo = false;
                                }
                                $dataLogSiom = array(
                                    'ptr'           => $ptr,
                                    'itemplan'      => $itemP,
                                    'usuario_envio' => $this->session->userdata('usernameSession'),
                                    'fecha_envio'   => $this->fechaActual());
                                $dataLogSiom['estado']   =  $estadoError;//NODO NO ENCONTRADO = 4
                                $dataLogSiom['mensaje']  =  $motivoError;
                                
                                 $dataSiom = array('itemplan'          => $itemP,
                                                'idEstacion'        => $infoItemplan['idEstacion'],
                                                'ptr'               => $ptr,
                                                'fechaRegistro'     => $this->fechaActual(),
                                                'idUsuarioRegistro' => $this->session->userdata('idPersonaSession'),
                                                'codigoSiom'        => null,
                                                'ultimo_estado'       => $motivoError,
                                                'fecha_ultimo_estado' => $this->fechaActual()
                                            );
                                            
                                $this->m_liquidacion->insertLogTramaSiom($dataLogSiom, $dataSiom);
                            }
                            
                            if($se_envio_fo && ID_SUBPROYECTO_ACELERACION_MOVIL == $infoItemplan['idSubProyecto'] && $infoItemplan['idEstacion'] == ID_ESTACION_FO){
                                $this->reenviarNuevaUMForced($ptr, $itemP);//reenviamos la trama para crear OS DE UM PARA ACELERACION MOVIL
                            }
                        }else{
                            log_message('error', 'ESTA EN EL SWITCH PERO YA CUENTA CON REGISTRO EN SIOM OBRA');
                        }
                    }else{
                        log_message('error', 'NO ESTA DENTRO DEL SWITCH');
                    }
                }else{
                    log_message('error', 'NO ES MATERIAL');
                }
                log_message('error', 'pASO 3.');
            }
            log_message('error', 'be:'.print_r($data,true));
            $this->db->trans_commit();
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaAsignarGrafo($this->m_liquidacion->getPtrToLiquidacion($SubProy,$eecc,$zonal,$itemPlan,$mesEjec,$area,$estado,FROM_BANDEJA_APROBACION,$ano,'',2));
            log_message('error', 'afeter:'.print_r($data,true));
        }catch(Exception $e){
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function makeHTLMTablaAsignarGrafo($listaPTR){
        
        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th></th>
                            <th>PTR</th>
                            <th>ItemPlan</th>  
							<th>Estado Plan</th>     							
                            <th>PEP 2</th>
                            <th>Grafo</th>
                            <th>Sub Proy</th>
                            <th>Zonal</th>
                            <th>EECC</th>
                            <th>Fase</th>
                            <th>PER</th>
                            <th>Fec Sol.</th>
                            <th>Tpo. Esp</th>
                            <th>Mes. Previsto</th>
                            <th>A&ntilde;o. Previsto</th>
                            <th>Area</th>  
                            <th>Tipo</th>
                            <th>Valor MAT</th>
                            <th>Valor MO</th>
                            <th>Estado</th>
                            <th>Priorizado</th>
                            <th>Nuevo Modelo</th>
                            <th>Con Licencia</th>
                        </tr>
                    </thead>                    
                    <tbody>';
		   		$btnDownload = null;																	                                                   
                foreach($listaPTR->result() as $row){
                    $info        =  $row->info == '' ? array() : explode('*', $row->info);
                    $hasLicencia = 'SI';
                    if(count($info) > 0) {
                        for($i = 0; $i < count($info); $i++) {
                            if($info[$i] != "2" ) {
                                $hasLicencia = "NO";
                            }
                        }
                    } else {
                        $hasLicencia = 'NO';
                    }
                    $countParalizados = $this->m_utils->countParalizadosPorPresupuesto($row->itemPlan, FLG_ACTIVO);
                    $arrayRow = explode('-', $row->ptr);
                    $btnCheck = '<a data-origen="'.$row->origen.'" data-ptr ="'.$row->ptr.'" data-tipo_area="'.utf8_decode($row->desc_area).'" data-grafo ="'.$row->grafo.'" data-from ="'.$row->grafo_from.'" data-area ="'.$row->area_desc.'" data-itmpl ="'.$row->itemPlan.'" onclick="addValeReserva(this)"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/circle-check-128.png"></a>';
                    if($arrayRow[0] > 2018) {                           
                        $btnDownload = '<a data-ptr ="' . $row->ptr . '" data-eecc ="' . $row->idEmpresaColab . '" data-itemplan ="' . $row->itemPlan . '" onclick="generarExcelMat(this);"><i class="zmdi zmdi-hc-2x zmdi-case-download"></i></a>';
                    }
                 
				$btnCheck = '';#PUESTO PARA QUITAR LA ACCION EN LA BANDEJA DE APROBACION
                if($row->desc_area == 'MAT'){
                    if($countParalizados > 0) {
                        $btnCheck = 'Paralizado';
                    }
                }
                $html .=' <tr>
                            <td>'.((($row->grafo!=null && $row->grafo!='SIN PRESUPUESTO' && $row->grafo!='NO HAY GRAFO' && $row->grafo!='SIN CONFIGURACION') || ($row->esta_validada == 1 && $row->desc_area == 'MO') || 
                                    ($row->esta_validada == 0 && $row->desc_area == 'MO' && $row->esta_validada2 == 1) || ($row->estacion_desc == 'DISEÑO' && $row->desc_area == 'MO')) ? 
                                     $btnCheck.' '.$btnDownload : (($countParalizados > 0) ? 'Paralizado' : '')).'</td>
							<td>'.$row->ptr.'</td>
							<td '.(($row->esta_validada >= 1) ? 'style="color: red;"' : '').'>'.$row->itemPlan.'</td>					
							<td>'.$row->estadoPlanDesc.'</td>
							<td>'.$row->pep2.'</td>
							<td>'.$row->grafo.'</td>
							<td>'.$row->subProy.'</td>
							<td>'.$row->zonal.'</td>
							<th>'.$row->eecc.'</th>
							<th>'.$row->fase_desc.'</th>
							<th>'.$row->per.' - '.$row->indicador_po.'</th>
							<th>'.$row->fecSol.'</th>
                            <th>'.substr($row->horas,0,-3).'</th>
                            <th>'.$row->mesEjec.'</th>
                            <th>'.$row->ano_ejec.'</th>
                            <td>'.utf8_decode($row->area_desc).'</td>
                            <td>'.utf8_decode($row->desc_area).'</td>
                             <td>' . (($row->valor_material != null && $row->valor_material != '-' && is_numeric($row->valor_material)) ? number_format($row->valor_material, 2, '.', ',') : '') . '</td>
                            <td>' . (($row->valor_m_o != null && $row->valor_m_o != '-' && is_numeric($row->valor_m_o)) ? number_format($row->valor_m_o, 2, '.', ',') : '') . '</td>
                            <td>'.substr($row->estado,0,3).'</td>
                            <td>'.(($row->hasAdelanto == 1) ? 'SI' : 'NO' ).'</td>
                            <td>'.(($row->paquetizado_fg == 2) ? 'SI' : 'NO' ).'</td>
                            <td>'.$hasLicencia.'</td>
						</tr>';
                 }
			 $html .='</tbody>
                </table>';
                    
        return $html;
    }
    
    function filtrarTabla(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $SubProy = $this->input->post('subProy');
            $eecc = $this->input->post('eecc');
            $zonal = $this->input->post('zonal');
            $itemPlan = $this->input->post('item');
            $mesEjec = $this->input->post('mes');
            $area = $this->input->post('area');
            $estado = $this->input->post('estado');
            $ano= $this->input->post('ano');
            $idFase= $this->input->post('idFase');
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaAsignarGrafo($this->m_liquidacion->getPtrToLiquidacion($SubProy,$eecc,$zonal,$itemPlan,$mesEjec,$area,$estado,FROM_BANDEJA_APROBACION,$ano,$idFase,2));
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

function saveLogConexionSinfix(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $itemplan = $this->input->post('itemplan');
            $ptr = $this->input->post('ptr');
            $error = $this->input->post('error');
            $data = $this->m_liquidacion->saveLogSinFix($itemplan, $ptr, $error);
            if($data['error'] == EXIT_ERROR){
                throw new Exception($data['msj']);
            }
  
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
    }
    
    function saveLogConexionSigoPlus(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $origen         = $this->input->post('origen');
            $ptr            = $this->input->post('ptr');
            $itemplan       = $this->input->post('itemplan');
            $vr             = $this->input->post('vr');
            $sisego         = $this->input->post('sisego');
            $eecc           = $this->input->post('eecc');
            $jefatura       = $this->input->post('jefatura');
            $motivo_error   = $this->input->post('motivo_error');
            $descripcion    = $this->input->post('descripcion');
            $estado         = $this->input->post('estado');
            
            $data = $this->m_utils->saveLogSigoplus($origen, $ptr, $itemplan, $vr, $sisego, $eecc, $jefatura, $motivo_error, $descripcion, $estado);
            if($data['error'] == EXIT_ERROR){
                throw new Exception($data['msj']);
            }
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
    }
    
    function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone','America/Lima');
        setlocale(LC_TIME, "es_ES","esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }
    
     public function getExcelPOMatAprob()
    {

        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $codigoPO = $this->input->post('codigoPO');
            $itemplan = $this->input->post('itemplan');
            $idEmpresaColab = $this->input->post('idEmpresaColab');

            $arrayMateriales = $this->m_liquidacion->getMatSapByPO_IP_EECC($itemplan,$codigoPO,$idEmpresaColab);

            ini_set('max_execution_time', 10000);
            ini_set('memory_limit', '2048M');

            $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
            $cacheSettings = array('memoryCacheSize ' => '5000MB', 'cacheTime' => '1000');
            PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

            $this->excel->setActiveSheetIndex(0);
            $this->excel->getActiveSheet()->setTitle('ValeDatos');
            $contador = 1;
            $titulosColumnas = array('MATERIAL','CENTRO', 'CTD', '', '', 'T', 'R', 'ALMACEN', '', '', '', 'FECHA', '', 'OBSERV', '', 'CODIGO PO');

            $this->excel->setActiveSheetIndex(0);

            // Se agregan los titulos del reporte
            $this->excel->setActiveSheetIndex(0)
                ->setCellValue('A1', utf8_encode($titulosColumnas[0]))
                ->setCellValue('B1', utf8_encode($titulosColumnas[1]))
                ->setCellValue('C1', utf8_encode($titulosColumnas[2]))
                ->setCellValue('D1', utf8_encode($titulosColumnas[3]))
                ->setCellValue('E1', utf8_encode($titulosColumnas[4]))
                ->setCellValue('F1', utf8_encode($titulosColumnas[5]))
                ->setCellValue('G1', utf8_encode($titulosColumnas[6]))
                ->setCellValue('H1', utf8_encode($titulosColumnas[7]))
                ->setCellValue('I1', utf8_encode($titulosColumnas[8]))
                ->setCellValue('J1', utf8_encode($titulosColumnas[9]))
                ->setCellValue('K1', utf8_encode($titulosColumnas[10]))
                ->setCellValue('L1', utf8_encode($titulosColumnas[11]))
                ->setCellValue('M1', utf8_encode($titulosColumnas[12]))
                ->setCellValue('N1', utf8_encode($titulosColumnas[13]))
				->setCellValue('O1', utf8_encode($titulosColumnas[14]))
                ->setCellValue('P1', utf8_encode($titulosColumnas[15]));

            foreach ($arrayMateriales as $row) {
                $contador++;
                $this->excel->getActiveSheet()->setCellValue("A{$contador}", $row->codigo_material)
                    ->setCellValue("B{$contador}", $row->codCentro)
                    ->setCellValue("C{$contador}", $row->cantidad_ingreso)
                    ->setCellValue("D{$contador}", null)
                    ->setCellValue("E{$contador}", null)
                    ->setCellValue("F{$contador}", $row->t)
                    ->setCellValue("G{$contador}", null)
                    ->setCellValue("H{$contador}", $row->codAlmacen)
                    ->setCellValue("I{$contador}", null)
                    ->setCellValue("J{$contador}", null)
                    ->setCellValue("K{$contador}", null)
                    ->setCellValue("L{$contador}", $row->fecha)
                    ->setCellValue("M{$contador}", null)
                    ->setCellValue("N{$contador}", null)
					->setCellValue("O{$contador}", null)
                    ->setCellValue("P{$contador}", $row->codigo_po);
            }

            $estiloTituloColumnas = array(
                'font' => array(
                    'name' => 'Calibri',
                    'bold' => true,
                    'color' => array(
                        'rgb' => '000000',
                    ),
                ));

            $this->excel->getActiveSheet()->getStyle('A1:AB1')->applyFromArray($estiloTituloColumnas);

            //Le ponemos un nombre al archivo que se va a generar.
            $archivo = 'ValeDatos'.rand() .'.xls';
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$archivo.'"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
            //Hacemos una salida al navegador con el archivo Excel.
            $objWriter->save('download/detalleMatSAP/' . $archivo);
            // $objWriter->save('php://output');
            // readfile('download/detalleMatSAP/ValeDatos.xls');
            $data['rutaExcel'] ='download/detalleMatSAP/'.$archivo;
            $data['error'] = EXIT_SUCCESS;
          
        } catch (Exception $e) {
            $data['msj'] = 'Error interno, al crear archivo vale datos';
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function sendDataToSiom($idEECC, $idEstacion, $estacion_desc, $itemplan, $emplazamiento_id, $ptr){
        
        try{
            $codigo_siom = null;
            $idEEEC_post = ID_EECC_TELEFONICA_SIOM;//POR DEFECTO TDP
            if($idEECC  ==  ID_EECC_COBRA){
                $idEEEC_post    =   ID_EECC_COBRA_SIOM;
            }else if($idEECC  ==  ID_EECC_LARI){
                $idEEEC_post    =   ID_EECC_LARI_SIOM;
            }else if($idEECC  ==  ID_EECC_DOMINION){
                $idEEEC_post    =   ID_EECC_DOMINION_SIOM;
            }else if($idEECC  ==  ID_EECC_EZENTIS){
                $idEEEC_post    =   ID_EECC_EZENTIS_SIOM;
            }else if($idEECC  ==  ID_EECC_COMFICA){
                $idEEEC_post    =   ID_EECC_COMFICA_SIOM;
            }else if($idEECC  ==  ID_EECC_LITEYCA){
                $idEEEC_post    =   ID_EECC_LITEYCA_SIOM;
            }
            
            $idSubEspecialidad_post = null;
            if($idEstacion  ==  ID_ESTACION_FO || $idEstacion  ==  ID_ESTACION_FO_ALIM || $idEstacion  ==  ID_ESTACION_FO_DIST){
                $idSubEspecialidad_post     = ID_SUB_ESPECIALIDAD_FO_SIOM;
                $idFormulario               = ID_FORMULARIO_FO_SIOM;
            }else if($idEstacion  ==  ID_ESTACION_COAXIAL){
                $idSubEspecialidad_post     = ID_SUB_ESPECIALIDAD_COAXIAL_SIOM;
                $idFormulario               = ID_FORMULARIO_COAXIAL_SIOM;
            }else if($idEstacion  ==  ID_ESTACION_OC_FO){
                $idSubEspecialidad_post     = ID_SUB_ESPECIALIDAD_OBRA_CIVIL_SIOM;
                $idFormulario               = ID_FORMULARIO_OBRA_CIVIL_SIOM;
            }else if($idEstacion  ==  ID_ESTACION_OC_COAXIAL){
                $idSubEspecialidad_post     = ID_SUB_ESPECIALIDAD_OBRA_CIVIL_SIOM;
                $idFormulario               = ID_FORMULARIO_OBRA_CIVIL_SIOM;
            }else if($idEstacion  ==  ID_ESTACION_UM || $idEstacion ==  ID_ESTACION_AC_CLIENTE){
                $idSubEspecialidad_post     = ID_SUB_ESPECIALIDAD_ULTIMA_MILLA;
                $idFormulario               = ID_FORMULARIO_ULTIMA_MILLA;
            }else if($idEstacion  ==  ID_ESTACION_FUENTE){
                $idSubEspecialidad_post     = ID_SUB_ESPECIALIDAD_ENERGIA;
                $idFormulario               = ID_FORMULARIO_ENERGIA;
            }
            
            $dataSend = ['cont_id' 		        => ID_CONTRATO_TELFONICA_SIOM,//CODIGO DE CONTRATO = 21
                        'empl_id'               => $emplazamiento_id,//CODIGO DE NODO EN BASE A SU TABLA EMPLAZAMIENTO
                        'empr_id' 		        => $idEEEC_post,//EEECC 23 = LARI, 31 = DOMINION, 32 = COBRA, 33 = EZENTIS
                        'formularios' 	        => [$idFormulario],//idFormulario
                        'orse_descripcion' 	    => $itemplan.' '.$estacion_desc,//ITEMPLAN_ESTACION
                        'orse_fecha_creacion'   => $this->fechaActual(),//NOW
                        'orse_fecha_solicitud'	=> $this->fechaActual(),//NOW
                        'orse_indisponibilidad' => 'SI',//siempre si
                        'orse_tag' 		        => 111,//????
                        'orse_tipo' 		    => 'OSGN',//OSGN SIEMPRE
                        'sube_id' 		        => $idSubEspecialidad_post,//SUBESPACIALIDAD EN BASE A LA ESPACIALIDAD ESTACION
                        'usua_login_creador'    => 'WSPO2019',
                        'usua_pass_creador' 	=> 'WSPO2019' ];
            
            $dataLogSiom = array('data_send'     => json_encode($dataSend),
                                'ptr'           => $ptr,
                                'itemplan'      => $itemplan,
                                'usuario_envio' => $this->session->userdata('usernameSession'),
                                'fecha_envio'   => $this->fechaActual());
            
            //$url = 'http://3.215.20.37:8080/crearOS-1.0/api/v1/CrearOS';//QA
              $url = 'http://54.86.187.150:8080/crearOS-1.0/api/v1/CrearOS';//PRODUCCION
            $response = $this->m_utils->sendDataToURLTypePUT($url, json_encode($dataSend));
            log_message('error', 'siom:'.print_r($response, true));
            if($response->codigo == EXIT_SUCCESS){//SE CREO LA OS
                $codigo_siom = $response->orseid;
                $dataLogSiom['codigo']  =  $response->codigo;
                $dataLogSiom['mensaje'] =  $response->mensaje;
                $dataLogSiom['orseid']  =  $response->orseid; 
                $dataLogSiom['estado']  =  1;                
                $this->m_liquidacion->insertLogTramaSiomSoloLog($dataLogSiom);
                log_message('error', 'TODO BIEN!');
            }else{//NO SE CREO LA OS
                $dataLogSiom['codigo']  =  $response->codigo;
                $dataLogSiom['mensaje'] =  $response->mensaje;
                $dataLogSiom['estado']  =  2;
                log_message('error', 'TODO MAL!');
                $this->m_liquidacion->insertLogTramaSiomSoloLog($dataLogSiom);
            }
        }catch(Exception $e){//ERROR AL ACCEDER AL SERVIDOR
            log_message('error', 'ERROR EN EL SERVIDOR!!');
            $dataLogSiom['estado']  =  3;
            $this->m_liquidacion->insertLogTramaSiomSoloLog($dataLogSiom);
        }
        return $codigo_siom;
    }
    
    function reenviarTramaSiom(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $ptr            = $this->input->post('ptr');
            $itemplan       = $this->input->post('itemplan');
            $nuevoIdCentral = $this->input->post('selectMDF');
            $id_siom_obra   = $this->input->post('id_siom_obra');
            $idEstacion     = $this->input->post('idEstacion');
            $estacionDesc   = $this->input->post('estacionDesc');
            
            $infoItemplan = $this->m_utils->getInfoItemplanLiquidacionSisegosWebPo($ptr, $itemplan);
            if($infoItemplan==null){                
                $infoItemplan = $this->m_utils->getInfoItemplanLiquidacionSisegos($ptr, $itemplan);
            }
            
            if($infoItemplan!=null){
                $emplazamiento =  $this->m_liquidacion->getEmplazamientoIdSiomByidCentral($nuevoIdCentral);//OBTENEMOS EL ID DEZPLAZAMIENTO DE LA TABLA SIOM_NODOS POR EL ID CENTRAL DE LA PO
                if($emplazamiento['cant'] >= 1){// SE ENCONTRO NODO
                    $codigo_siom = $this->sendDataToSiom($infoItemplan['idEecc'], $idEstacion, $estacionDesc, $itemplan, $emplazamiento['empl_id'], $ptr);
                    //validar si no viene nulo
                    if($codigo_siom != null){
                        $dataSiom = array('itemplan'          => $itemplan,
                                        'idEstacion'        => $idEstacion,
                                        'ptr'               => $ptr,
                                        'fechaRegistro'     => $this->fechaActual(),
                                        'idUsuarioRegistro' => $this->session->userdata('idPersonaSession'),
                                        'codigoSiom'        => $codigo_siom,
                                        'ultimo_estado'       => 'CREADA',
                                        'fecha_ultimo_estado' => $this->fechaActual()
                        );
                
                        $dataLogPo = array( 'tabla'            => 'Siom',
                                            'actividad'        => 'Registrar Siom',
                                            'itemplan'         => $itemplan,
                                            'fecha_registro'   => $this->fechaActual(),
                                            'id_usuario'       => $this->session->userdata('idPersonaSession')
                                        );
                
                        $dataEstado = array('codigo_siom'           => $codigo_siom,
                                            'estado_desc'           => 'CREADA',
                                            'fechaRegistro'         => $this->fechaActual(),
                                            'usuario_registro'      => $this->session->userdata('usernameSession'),
                                            'estado_transaccion'    => 1
                                        );
                        $data = $this->m_liquidacion->updateSiom($dataSiom, $dataLogPo, $dataEstado, $id_siom_obra);
                        $data['codigo_siom'] = $codigo_siom;
                    }else{
                        throw new Exception('No se recepciono un codigo siom');
                    }
                }else{
                    throw new Exception('No se encontro un nodo Valido');
                }
            }else{
                throw new Exception('No se encontraron Datos Itemplan - PO');
            }
      
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function nuevaOSTramaSiom(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $idSiom            = $this->input->post('id_siom');
            $idEstacion        = $this->input->post('id_estacion');
            $estacionDesc        = $this->m_utils->getEstaciondescByIdEstacion($idEstacion);
            $dataSiom = $this->m_utils->getSiomDataFromIdSiom($idSiom);
            if($dataSiom==null){
                throw new Exception('Error al obtener informacion Codigo Siom:'.$idSiom);
            }
            $ptr    = $dataSiom['ptr'];
            $itemP  = $dataSiom['itemplan'];
            $infoItemplan = $this->m_utils->getInfoItemplanLiquidacionSisegosWebPo($ptr, $itemP);
            if($infoItemplan==null){
                $infoItemplan = $this->m_utils->getInfoItemplanLiquidacionSisegos($ptr, $itemP);
            }
    
            if($infoItemplan!=null){
                $emplazamiento =  $this->m_liquidacion->getEmplazamientoIdSiomByidCentral($infoItemplan['idCentral']);//OBTENEMOS EL ID DEZPLAZAMIENTO DE LA TABLA SIOM_NODOS POR EL ID CENTRAL DE LA PO
                if($emplazamiento['cant'] >= 1){// SE ENCONTRO NODO
                    $codigo_siom = $this->sendDataToSiom($infoItemplan['idEecc'], $idEstacion, $estacionDesc, $itemP, $emplazamiento['empl_id'], $ptr);
                    //$codigo_siom = 9876;
                    //validar si no viene nulo
                    if($codigo_siom != null){
                        $dataSiom = array('itemplan'          => $itemP,
                            'idEstacion'        => $idEstacion,
                            'ptr'               => $ptr,
                            'fechaRegistro'     => $this->fechaActual(),
                            'idUsuarioRegistro' => $this->session->userdata('idPersonaSession'),
                            'codigoSiom'        => $codigo_siom,
                            'ultimo_estado'       => 'CREADA',
                            'fecha_ultimo_estado' => $this->fechaActual()
                        );
                        
                        $dataLogPo = array( 'tabla'            => 'Siom',
                                            'actividad'        => 'Registrar Siom',
                                            'itemplan'         => $itemP,
                                            'fecha_registro'   => $this->fechaActual(),
                                            'id_usuario'       => $this->session->userdata('idPersonaSession')
                        );
                        
                        $dataEstado = array('codigo_siom'           => $codigo_siom,
                                            'estado_desc'           => 'CREADA',
                                            'fechaRegistro'         => $this->fechaActual(),
                                            'usuario_registro'      => $this->session->userdata('usernameSession'),
                                            'estado_transaccion'    => 1
                        );                       
                        $data = $this->m_liquidacion->insertSiom($dataSiom, $dataLogPo, $dataEstado);
                        $data['codigo_siom'] = $codigo_siom;
                    }else{
                        log_message('error', 'No se recepciono un codigo siom');
                    }
                }else{
                    
                    $motivoError = 'No se encontro emplazamiento ID para ese nodo';
                    $estadoError = 4; //NODO NO ENCONTRADO = 4                                    
                    $dataLogSiom = array(
                        'ptr'           => $ptr,
                        'itemplan'      => $itemP,
                        'usuario_envio' => $this->session->userdata('usernameSession'),
                        'fecha_envio'   => $this->fechaActual());
                    $dataLogSiom['estado']  =  $estadoError;//NODO NO ENCONTRADO = 4
                    $dataLogSiom['mensaje']  =  $motivoError;
                    
                    $dataSiom = array('itemplan'          => $itemP,
                                    'idEstacion'        => $idEstacion,
                                    'ptr'               => $ptr,
                                    'fechaRegistro'     => $this->fechaActual(),
                                    'idUsuarioRegistro' => $this->session->userdata('idPersonaSession'),
                                    'codigoSiom'        => null,
                                    'ultimo_estado'       => $motivoError,
                                    'fecha_ultimo_estado' => $this->fechaActual()
                                );
                    $data = $this->m_liquidacion->insertLogTramaSiom($dataLogSiom, $dataSiom);
                }
            }else{
                throw new Exception('No se encontraron Datos Itemplan - PO');
            }
    
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function testTramaSigo(){
        
            //$infoItemplan = $this->m_utils->getInfoItemplanLiquidacionSisegos($ptr, $itemP);

            $infoItemplan = $this->m_utils->getInfoItemplanLiquidacionSisegosWebPo('2019-030603338', '19-0320400286');
        
            $vale_re = '999-NUEVO';
        
        if( $infoItemplan['idProyecto'] == ID_PROYECTO_SISEGOS &&
            $infoItemplan['idEstacion'] == ID_ESTACION_FO &&
            $infoItemplan['tipoArea']   == 'MAT'){
        
            $sisego_ptr         = $infoItemplan['poCod'];
            $sisego_itemplan    = $infoItemplan['itemPlan'];
            $sisego_eecc = '';
            if($infoItemplan['eecc']    == 'DOMINIONPERU SOLUCIONES Y SERVICIOS S.A.C.'){
                $sisego_eecc    = 'DOMINION';
            }else if($infoItemplan['eecc']    == 'CALATEL'){
                $sisego_eecc    = 'EZENTIS';
            }else{
                $sisego_eecc    = $infoItemplan['eecc'];
            }
            $sisego_jefatura    = $infoItemplan['jefatura_ptr'];
            $sisego_fecha       = date("Y-m-d");
            $sisego_vr          = $vale_re;
            $sisego_sisego      = $infoItemplan['indicador'];
        
            $dataSend = ['ptr' 		    => $sisego_ptr,
                'itemplan'      => $sisego_itemplan,
                'eecc' 		    => $sisego_eecc,
                'jefatura' 	    => $sisego_jefatura,
                'fecha' 		=> $sisego_fecha,
                'vr' 			=> $sisego_vr,
                'sisego' 		=> $sisego_sisego,
                'region'        => $infoItemplan['region'],
                'nodo'          => $infoItemplan['codigo'],
                'nodoDesc'      => $infoItemplan['tipoCentralDesc']
            ];
			
			log_message('error','PRUEBAWS:'.print_r($dataSend, true));
        
            //$url = 'https://gicsapps.com:8080/obras2/recibir_dis.php';
			  $url = 'https://172.30.5.10:8080/obras2/recibir_dis.php';
            $response = $this->m_utils->sendDataToURL($url, $dataSend);
						
            if($response->error == EXIT_SUCCESS){
                $this->m_utils->saveLogSigoplus('BANDEJA DE APROBACIONT PTR FO - MAT', $sisego_ptr, $sisego_itemplan, $sisego_vr, $sisego_sisego, $sisego_eecc, $sisego_jefatura, 'TRAMA COMPLETADA', 'OPERACION REALIZADA CON EXITO:'. strtoupper($response->mensaje), 1);
            }else{
                $this->m_utils->saveLogSigoplus('BANDEJA DE APROBACIONT PTR FO - MAT', $sisego_ptr, $sisego_itemplan, $sisego_vr, $sisego_sisego, $sisego_eecc, $sisego_jefatura, 'FALLA EN LA RESPUESTA DEL HOSTING', 'OPERACION NO COMPLETADA ERROR EN EL SERVIDOR DEL CLIENTE:'. strtoupper($response->mensaje), '2');
            }
        }
    }
    
    
    function reenviarNuevaUMForced($ptr, $itemP){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $infoItemplan = $this->m_utils->getInfoItemplanLiquidacionSisegosWebPo($ptr, $itemP);
            if($infoItemplan==null){
                $infoItemplan = $this->m_utils->getInfoItemplanLiquidacionSisegos($ptr, $itemP);
            }
    
            if($infoItemplan!=null){
                $emplazamiento =  $this->m_liquidacion->getEmplazamientoIdSiomByidCentral($infoItemplan['idCentral']);//OBTENEMOS EL ID DEZPLAZAMIENTO DE LA TABLA SIOM_NODOS POR EL ID CENTRAL DE LA PO
                if($emplazamiento['cant'] >= 1){// SE ENCONTRO NODO
                    $codigo_siom = $this->sendDataToSiom($infoItemplan['idEecc'], 13, 'UM', $itemP, $emplazamiento['empl_id'], $ptr);
                    //$codigo_siom = 9876;
                    //validar si no viene nulo
                    if($codigo_siom != null){
                        $dataSiom = array('itemplan'          => $itemP,
                            'idEstacion'        => 13,
                            'ptr'               => $ptr,
                            'fechaRegistro'     => $this->fechaActual(),
                            'idUsuarioRegistro' => $this->session->userdata('idPersonaSession'),
                            'codigoSiom'        => $codigo_siom,
                            'ultimo_estado'       => 'CREADA',
                            'fecha_ultimo_estado' => $this->fechaActual()
                        );
    
                        $dataLogPo = array( 'tabla'            => 'Siom',
                            'actividad'        => 'Registrar Siom',
                            'itemplan'         => $itemP,
                            'fecha_registro'   => $this->fechaActual(),
                            'id_usuario'       => $this->session->userdata('idPersonaSession')
                        );
    
                        $dataEstado = array('codigo_siom'           => $codigo_siom,
                            'estado_desc'           => 'CREADA',
                            'fechaRegistro'         => $this->fechaActual(),
                            'usuario_registro'      => $this->session->userdata('usernameSession'),
                            'estado_transaccion'    => 1
                        );
                        $data = $this->m_liquidacion->insertSiom($dataSiom, $dataLogPo, $dataEstado);
                        $data['codigo_siom'] = $codigo_siom;
                    }else{
                        log_message('error', 'No se recepciono un codigo siom');
                    }
                }else{
    
                    $motivoError = 'No se encontro emplazamiento ID para ese nodo';
                    $estadoError = 4; //NODO NO ENCONTRADO = 4
                    $dataLogSiom = array(
                        'ptr'           => $ptr,
                        'itemplan'      => $itemP,
                        'usuario_envio' => $this->session->userdata('usernameSession'),
                        'fecha_envio'   => $this->fechaActual());
                    $dataLogSiom['estado']  =  $estadoError;//NODO NO ENCONTRADO = 4
                    $dataLogSiom['mensaje']  =  $motivoError;
    
                    $dataSiom = array('itemplan'          => $itemP,
                        'idEstacion'        => 13,
                        'ptr'               => $ptr,
                        'fechaRegistro'     => $this->fechaActual(),
                        'idUsuarioRegistro' => $this->session->userdata('idPersonaSession'),
                        'codigoSiom'        => null,
                        'ultimo_estado'       => $motivoError,
                        'fecha_ultimo_estado' => $this->fechaActual()
                    );
                    $data = $this->m_liquidacion->insertLogTramaSiom($dataLogSiom, $dataSiom);
                }
            }else{
                throw new Exception('No se encontraron Datos Itemplan - PO');
            }
    
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
    function enviarTramaIndividualPerzonalizada(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $ptr            = '2020-010600335';
            $itemplan       = '20-0120400007';
            //$nuevoIdCentral = $this->input->post('selectMDF');
            $id_siom_obra   = 5026;
            
            $infoItemplan = $this->m_utils->getInfoItemplanLiquidacionSisegosWebPo($ptr, $itemplan);
            if($infoItemplan==null){
                $infoItemplan = $this->m_utils->getInfoItemplanLiquidacionSisegos($ptr, $itemplan);
            }
            
            if($infoItemplan!=null){
                $emplazamiento =  $this->m_liquidacion->getEmplazamientoIdSiomByidCentral($infoItemplan['idCentral']);//OBTENEMOS EL ID DEZPLAZAMIENTO DE LA TABLA SIOM_NODOS POR EL ID CENTRAL DE LA PO
                if($emplazamiento['cant'] >= 1){// SE ENCONTRO NODO
                   $codigo_siom = $this->sendDataToSiom($infoItemplan['idEecc'], $infoItemplan['idEstacion'], $infoItemplan['estacionDesc'], $itemplan, $emplazamiento['empl_id'], $ptr);
                 //$codigo_siom = 0;
                    //validar si no viene nulo
                    if($codigo_siom != null){
                        $dataSiom = array('itemplan'          => $itemplan,
                            'idEstacion'        => $infoItemplan['idEstacion'],
                            'ptr'               => $ptr,
                            'fechaRegistro'     => $this->fechaActual(),
                            'idUsuarioRegistro' => $this->session->userdata('idPersonaSession'),
                            'codigoSiom'        => $codigo_siom,
                            'ultimo_estado'       => 'CREADA',
                            'fecha_ultimo_estado' => $this->fechaActual()
                        );
            
                        $dataLogPo = array( 'tabla'            => 'Siom',
                            'actividad'        => 'Registrar Siom',
                            'itemplan'         => $itemplan,
                            'fecha_registro'   => $this->fechaActual(),
                            'id_usuario'       => $this->session->userdata('idPersonaSession')
                        );
            
                        $dataEstado = array('codigo_siom'           => $codigo_siom,
                            'estado_desc'           => 'CREADA',
                            'fechaRegistro'         => $this->fechaActual(),
                            'usuario_registro'      => $this->session->userdata('usernameSession'),
                            'estado_transaccion'    => 1
                        );
                        $data = $this->m_liquidacion->updateSiom($dataSiom, $dataLogPo, $dataEstado, $id_siom_obra);
                        $data['codigo_siom'] = $codigo_siom;
                    }else{
                        throw new Exception('No se recepciono un codigo siom');
                    }
                }else{
                    throw new Exception('No se encontro un nodo Valido');
                }
            }else{
                throw new Exception('No se encontraron Datos Itemplan - PO');
            }
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
         echo json_encode(array_map('utf8_encode', $data));
    }
    
    function enviarTramasByItemplanPTR(){//$ptr, $itemP
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
           
            /*$listaPtrItem = $this->m_liquidacion->getItemPtrSendSiom();
            $listaPtrItem = null;
            foreach($listaPtrItem->result() as $row){*/
                $ptr = '2020-431300048';
                $itemP = '20-4311100022';
                $infoItemplan = $this->m_utils->getInfoItemplanLiquidacionSisegosWebPo($ptr, $itemP);
                if($infoItemplan==null){
                    $infoItemplan = $this->m_utils->getInfoItemplanLiquidacionSisegos($ptr, $itemP);
                }
        
                if($infoItemplan!=null){
                    $emplazamiento =  $this->m_liquidacion->getEmplazamientoIdSiomByidCentral($infoItemplan['idCentral']);//OBTENEMOS EL ID DEZPLAZAMIENTO DE LA TABLA SIOM_NODOS POR EL ID CENTRAL DE LA PO
                    if($emplazamiento['cant'] >= 1){// SE ENCONTRO NODO
                        $codigo_siom = $this->sendDataToSiom($infoItemplan['idEecc'], $infoItemplan['idEstacion'], $infoItemplan['estacionDesc'], $itemP, $emplazamiento['empl_id'], $ptr);
                        //$codigo_siom = 9876;
                        //validar si no viene nulo
                        if($codigo_siom != null){
                            $dataSiom = array('itemplan'          => $itemP,
                                'idEstacion'        => $infoItemplan['idEstacion'],
                                'ptr'               => $ptr,
                                'fechaRegistro'     => $this->fechaActual(),
                                'idUsuarioRegistro' => $this->session->userdata('idPersonaSession'),
                                'codigoSiom'        => $codigo_siom,
                                'ultimo_estado'       => 'CREADA',
                                'fecha_ultimo_estado' => $this->fechaActual()
                            );
        
                            $dataLogPo = array( 'tabla'            => 'Siom',
                                'actividad'        => 'Registrar Siom',
                                'itemplan'         => $itemP,
                                'fecha_registro'   => $this->fechaActual(),
                                'id_usuario'       => $this->session->userdata('idPersonaSession')
                            );
        
                            $dataEstado = array('codigo_siom'           => $codigo_siom,
                                'estado_desc'           => 'CREADA',
                                'fechaRegistro'         => $this->fechaActual(),
                                'usuario_registro'      => $this->session->userdata('usernameSession'),
                                'estado_transaccion'    => 1
                            );
                            $data = $this->m_liquidacion->insertSiom($dataSiom, $dataLogPo, $dataEstado);
                            $data['codigo_siom'] = $codigo_siom;
                        }else{
                            log_message('error', 'No se recepciono un codigo siom');
                        }
                    }else{
        
                        $motivoError = 'No se encontro emplazamiento ID para ese nodo';
                        $estadoError = 4; //NODO NO ENCONTRADO = 4
                        $dataLogSiom = array(
                            'ptr'           => $ptr,
                            'itemplan'      => $itemP,
                            'usuario_envio' => $this->session->userdata('usernameSession'),
                            'fecha_envio'   => $this->fechaActual());
                        $dataLogSiom['estado']  =  $estadoError;//NODO NO ENCONTRADO = 4
                        $dataLogSiom['mensaje']  =  $motivoError;
        
                        $dataSiom = array('itemplan'          => $itemP,
                            'idEstacion'        => $infoItemplan['idEstacion'],
                            'ptr'               => $ptr,
                            'fechaRegistro'     => $this->fechaActual(),
                            'idUsuarioRegistro' => $this->session->userdata('idPersonaSession'),
                            'codigoSiom'        => null,
                            'ultimo_estado'       => $motivoError,
                            'fecha_ultimo_estado' => $this->fechaActual()
                        );
                        $data = $this->m_liquidacion->insertLogTramaSiom($dataLogSiom, $dataSiom);
                    }
                }else{
                    throw new Exception('No se encontraron Datos Itemplan - PO');
                }
            //}
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
}