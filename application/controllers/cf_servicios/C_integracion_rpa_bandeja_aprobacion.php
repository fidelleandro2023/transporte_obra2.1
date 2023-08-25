<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 *
 *
 */
class C_integracion_rpa_bandeja_aprobacion extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_servicios/m_integracion_rpa_bandeja_aprobacion');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index()
    {      
        
    }
    
   /* v 1.0 1x1*
    public function getPoMaterialtoRPA()
    {
    
        header("Access-Control-Allow-Origin: *");
        $output['codigo'] = EXIT_ERROR;
        $output['mensaje'] = 'No se encontro PO material disponible';
        try {
            $method = $_SERVER['REQUEST_METHOD'];
            if($method=='GET'){
                    
                if (!isset($_SERVER['PHP_AUTH_USER']) && !isset($_SERVER['PHP_AUTH_PW'])) {
                    header('WWW-Authenticate: Basic realm="LOGIN REQUIRED"');
                    header('HTTP/1.0 401 Unauthorized');
                    $output['motivo'] = 'Access denied 401!';
                    throw new Exception('Usuario o clave Incorrectos');
                }else{
                    if($_SERVER['PHP_AUTH_USER'] != 'RPA_USER' || $_SERVER['PHP_AUTH_PW'] != 'T3l3f0n1c4' ){
                        header('WWW-Authenticate: Basic realm="LOGIN REQUIRED"');
                        header('HTTP/1.0 401 Unauthorized');
                        $output['motivo'] = 'Access denied 401!';
                        throw new Exception('Usuario o clave Incorrectos');
                    }
                }
                
                $poMatToSend =  $this->m_integracion_rpa_bandeja_aprobacion->getPoMaterialToRpa();
                if($poMatToSend != null){
                    $listaMateriales = $this->m_integracion_rpa_bandeja_aprobacion->getMaterialesToSap($poMatToSend['itemplan'],$poMatToSend['codigo_po']);
                    $numMateriales = count($listaMateriales);
                    if($numMateriales>0){
                        $output = $poMatToSend;
                        $output['materiales']   = $listaMateriales;
                        $output['codigo'] = EXIT_SUCCESS;
                        $output['mensaje'] = 'Po Material Enviada';
                        
                        $dataEstadoInsertLog = array ('codigo_po'  => $output['codigo_po'],
                            'itemplan'   => $output['itemplan'],
                            'fecha'     => $this->fechaActual(),
                            'estado'    => 5,
                            'mensaje'   => 'PO enviada',
                            'dataGet'    => json_encode($output)
                        );
                        $dataPlanobraPo = array ('activo_rpa' => 1);
                        $this->m_integracion_rpa_bandeja_aprobacion->tramaNoOkRpa($dataEstadoInsertLog, $dataPlanobraPo, $output['codigo_po'], $output['itemplan']);
                    }else{
                        $output['mensaje'] = 'No se encontro materiales para la PO';
                    }
                }        
            }else{
                $output = $this->getMsjTypeMethod($method, 'getPoMaterial');
            }
        } catch (Exception $e) {
            $output['mensaje'] = 'Error Interno, no se obtuvo una Po Material, '.$e->getMessage();
        }
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($output));
    }
    */
    /** v 2.0 Lista completa **/
    public function getPoMaterialtoRPA()
    {
		_log("ENTRO ACAAAA");
    
        header("Access-Control-Allow-Origin: *");
        $output['codigo'] = EXIT_ERROR;
        $output['mensaje'] = 'No se encontro PO material disponible';
        try {
            $method = $_SERVER['REQUEST_METHOD'];
            if($method=='GET'){
    
                if (!isset($_SERVER['PHP_AUTH_USER']) && !isset($_SERVER['PHP_AUTH_PW'])) {
                    header('WWW-Authenticate: Basic realm="LOGIN REQUIRED"');
                    header('HTTP/1.0 401 Unauthorized');
                    $output['motivo'] = 'Access denied 401!';
                    throw new Exception('Usuario o clave Incorrectos (isset get)');
                }else{
                    if($_SERVER['PHP_AUTH_USER'] != 'RPA_USER' || $_SERVER['PHP_AUTH_PW'] != 'T3l3f0n1c4' ){
                        header('WWW-Authenticate: Basic realm="LOGIN REQUIRED"');
                        header('HTTP/1.0 401 Unauthorized');
                        $output['motivo'] = 'Access denied 401!';
                        throw new Exception('Usuario o clave Incorrectos (credenciales get)');
                    }
                }
    
                $poMatToSendList =  $this->m_integracion_rpa_bandeja_aprobacion->getPoMaterialToRpaList();
                $listaLogPoEnvio = array();
                $listaPoFlgEnvio = array();
                $listaFinalToRPASend = array();
                $numPoSend = 0;
				 
                foreach($poMatToSendList as $poMaterial){
                    $outputIndividual = array();
						$flg_paquetizado = $this->m_utils->getFlgPaquetizadoPo($poMaterial->itemplan);
						if($flg_paquetizado == 2 || $flg_paquetizado == 1) {
							$listaMateriales = $this->m_integracion_rpa_bandeja_aprobacion->getMaterialesToSapPqt($poMaterial->itemplan,$poMaterial->codigo_po);
						} else {
							$listaMateriales = $this->m_integracion_rpa_bandeja_aprobacion->getMaterialesToSap($poMaterial->itemplan,$poMaterial->codigo_po);
						}
                        
                        $numMateriales = count($listaMateriales);
                        if($numMateriales>0){
                            $outputIndividual['itemplan']   = $poMaterial->itemplan;
                            $outputIndividual['codigo_po']  = $poMaterial->codigo_po;
                            $outputIndividual['pep1']       = $poMaterial->pep1;
                            $outputIndividual['pep2']       = $poMaterial->pep2;
                            $outputIndividual['grafo']      = $poMaterial->grafo;
                            $outputIndividual['materiales']   = $listaMateriales;
                            $dataEstadoInsertLog = array ('codigo_po'  => $outputIndividual['codigo_po'],
                                'itemplan'   => $outputIndividual['itemplan'],
                                'fecha'     => $this->fechaActual(),
                                'estado'    => 5,
                                'mensaje'   => 'PO enviada',
                                'dataGet'    => json_encode($outputIndividual)
                            );
                            $dataPlanobraPo = array ('activo_rpa' => 1,
                                                     'codigo_po' => $outputIndividual['codigo_po']);
                            
                            array_push($listaLogPoEnvio, $dataEstadoInsertLog);
                            array_push($listaPoFlgEnvio, $dataPlanobraPo);
                            array_push($listaFinalToRPASend, $outputIndividual);
							$numPoSend++;
                        }else{
                            log_message('error', '----->NO tiene materiales');
                        }
                }
                
                if(count($listaFinalToRPASend) > 0){//si hay para mandar
                    //$this->m_integracion_rpa_bandeja_aprobacion->tramaNoOkRpa($dataEstadoInsertLog, $dataPlanobraPo, $output['codigo_po'], $output['itemplan']);
                    $dataEstadoInsertLog = array ('codigo_po'  => '',
                        'itemplan'   => 'APROBACION MAT',
                        'fecha'     => $this->fechaActual(),
                        'estado'    => 5,//todo ok
                        'mensaje'   => 'Dato Enviado',
                        'dataGet'    =>  json_encode($listaFinalToRPASend),
                        'num_po_send'   =>  $numPoSend
                    );
                    $this->m_integracion_rpa_bandeja_aprobacion->tramaOkRpa($dataEstadoInsertLog);
					
                    $output['po_lista'] = $listaFinalToRPASend;
                    $output['codigo'] = EXIT_SUCCESS;
                    $output['mensaje'] = 'Po Material Enviada';
                }else{
                    $dataEstadoInsertLog = array ('codigo_po'  => '',
                        'itemplan'      => 'APROBACION MAT',
                        'fecha'         => $this->fechaActual(),
                        'estado'        => 5,//todo ok
                        'mensaje'       => 'Dato Enviado',
                        'dataGet'       =>  json_encode($listaFinalToRPASend),
                        'num_po_send'   =>  $numPoSend
                    );
                    $this->m_integracion_rpa_bandeja_aprobacion->tramaOkRpa($dataEstadoInsertLog);
                     
                    $output['po_lista'] = $listaFinalToRPASend;
                    $output['codigo'] = EXIT_SUCCESS;
                }
                
            }else{
                $output = $this->getMsjTypeMethod($method, 'getPoMaterial');
            }
        } catch (Exception $e) {
            $output['mensaje'] = 'Error Interno, no se obtuvo una Po Material, '.$e->getMessage();
        }
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($output));
    }
	
    /**aprobacion de po**/
    public function cambiarEstadoPOMaterial()
    {
    
        header("Access-Control-Allow-Origin: *");
        $output['codigo'] = EXIT_ERROR;
        $output['mensaje'] = 'No se actualizo la PO Material';
        try {
            $method = $_SERVER['REQUEST_METHOD'];
            if($method=='PUT' || $method=='POST'){
                
                if (!isset($_SERVER['PHP_AUTH_USER']) && !isset($_SERVER['PHP_AUTH_PW'])) {
                    header('WWW-Authenticate: Basic realm="LOGIN REQUIRED"');
                    header('HTTP/1.0 401 Unauthorized');
                    $output['motivo'] = 'Access denied 401!';
					$output['codigo'] = 2;
                    throw new Exception('Usuario o clave Incorrectos(isset)');
                }else{
                    if($_SERVER['PHP_AUTH_USER'] != 'RPA_USER' || $_SERVER['PHP_AUTH_PW'] != 'T3l3f0n1c4' ){
                        header('WWW-Authenticate: Basic realm="LOGIN REQUIRED"');
                        header('HTTP/1.0 401 Unauthorized');
                        $output['motivo'] = 'Access denied 401!';
						$output['codigo'] = 2;
                        throw new Exception('Usuario o clave Incorrectos (credenciales)');
                    }
                }
                
                $inputJSON = file_get_contents('php://input');
                log_message('error', ':::cambiarEstadoPOMaterial:::'.print_r($inputJSON,true));
                $input = json_decode( $inputJSON, TRUE );
                if(!isset($input['codigo_po'])){
                    throw new Exception('No se detecto codigo_po en la trama de envio.');
                }
                if(!isset($input['itemplan'])){
                    throw new Exception('No se detecto itemplan en la trama de envio.');
                }
                if(!isset($input['error'])){
                    throw new Exception('No se detecto un codigo de error en la trama de envio.');
                }
                if($input['error'] == 0){//todo bien
                    log_message('error', 'todo ok');
                    if(!isset($input['vale_reserva'])){
                        throw new Exception('No se detecto vale_reserva en la trama de envio.');
                    }                    
                    //si llega aqui ya no volver a enviar la po error de web po 
                    $dataAprob = $this->asignarGrafoFromRPA($input['itemplan'], $input['codigo_po'], $input['vale_reserva']);
					log_message('error','-->'.print_r($dataAprob,true));
                    if($dataAprob['error'] == EXIT_SUCCESS){//todo ok.
                        $dataEstadoInsertLog = array ('codigo_po'  => $input['codigo_po'],
                            'itemplan'   => $input['itemplan'],
                            'fecha'     => $this->fechaActual(),
                            'estado'    => 1,//todo ok
                            'mensaje'   => $input['mensaje'],
                            'dataGet'    => $inputJSON
                        );
                        $this->m_integracion_rpa_bandeja_aprobacion->tramaOkRpa($dataEstadoInsertLog);
                        
                    }else{//se aprobo en sap pero no en po realizar seguimiento.
                        $dataEstadoInsertLog = array ('codigo_po'  => $input['codigo_po'],
                            'itemplan'   => $input['itemplan'],
                            'fecha'     => $this->fechaActual(),
                            'estado'    => 3,//error interno
                            'mensaje'   => $input['mensaje'],
                            'exception'  => $dataAprob['msj'],
                            'dataGet'    => $inputJSON
                        );
                        $this->m_integracion_rpa_bandeja_aprobacion->tramaOkRpa($dataEstadoInsertLog);
                    }
                                    
                }else{//error liberar la PO         
                    $dataEstadoInsertLog = array ('codigo_po'  => $input['codigo_po'],
                                                  'itemplan'   => $input['itemplan'],
                                                   'fecha'     => $this->fechaActual(),
                                                   'estado'    => 2,
                                                   'mensaje'   => $input['mensaje'],
                                                   'dataGet'    => $inputJSON
                    );                    
                    $dataPlanobraPo = array ('activo_rpa' => 0);
                    $this->m_integracion_rpa_bandeja_aprobacion->tramaNoOkRpa($dataEstadoInsertLog, $dataPlanobraPo, $input['codigo_po'], $input['itemplan']);
                }
                $output['codigo'] = EXIT_SUCCESS;
                $output['mensaje'] = 'Se actualizo la Po Material';
            }else{
                $output = $this->getMsjTypeMethod($method, 'updatePoMaterial');
            }
        } catch (Exception $e) {
            $output['mensaje'] = 'Error Interno, No se actualizo la PO Material, '.$e->getMessage();
            $dataEstadoInsertLog = array (
                'fecha'      => $this->fechaActual(),
                'estado'     => 4,//otro error
                'exception'  => $e->getMessage(),
                'dataGet'    => isset($inputJSON)
            );
            $this->m_integracion_rpa_bandeja_aprobacion->tramaOkRpa($dataEstadoInsertLog);
        }
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($output));
    }
    
    public function getMsjTypeMethod($type, $path){
        $arr = array('error' => 'Method Not Allowed', 'message' => "Request method '".$type."' not supported", 'path' => $path);
        return $arr;
    }
        
    public function asignarGrafoFromRPA($itemP, $ptr, $vale_re){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            
            $origen = null;
			log_message('error', '1111111');
            $infoItemplan = $this->m_utils->getInfoItemplanLiquidacionSisegosWebPo($ptr, $itemP);//validamos si es PO
            if($infoItemplan==null){//si no hay valor siguiente validacion
                $infoItemplan = $this->m_utils->getInfoItemplanLiquidacionSisegos($ptr, $itemP);//validar si es ptr
                if($infoItemplan!=null){//si hay valor
                    $origen = 1;// es ptr
                }
            }else{//si hay valor
                $origen = 2;// es po
            }
            log_message('error', '222222');
            if($origen==null){
                //error interno no se encontro informacion
				 log_message('error', 'error interno');
            }
            log_message('error', '33333333333333');
            if($infoItemplan['idProyecto'] != ID_PROYECTO_SISEGOS){//validar si continuara esta validacion.
                $existVr = $this->m_utils->existVROnAprobacion(trim($vale_re));
                if($existVr > 0){
                    throw new Exception("El vale de Reserva ya se encuentra registrado.");
                }
            }            
            
            if($origen  ==  1){//PTR DE WEB UNIFICADA
				log_message('error', 'web unificada');
                $data = $this->m_integracion_rpa_bandeja_aprobacion->aprobarPtrFromWebUnificada($ptr, $vale_re, $itemP);
            }else if($origen    ==  2){// PTR DE WEB PO
				log_message('error', 'web po');
                $data = $this->m_integracion_rpa_bandeja_aprobacion->aprobarPOWebPO($ptr, $itemP, $vale_re);
            }
            log_message('error', 'be:'.print_r($data,true));
            if($data['error'] == EXIT_ERROR){
                throw new Exception($data['msj']);
            }
           log_message('error', 'aft:'.print_r($data,true));
            //************************** ACTUALIZAR PLAN OBRA A TERMINADO ********************************//
           if( $infoItemplan['idEstadoPlan']    ==  ID_ESTADO_DISENIO || $infoItemplan['idEstadoPlan']    ==  ID_ESTADO_DISENIO_EJECUTADO || $infoItemplan['idEstadoPlan']    ==  ID_ESTADO_DISENIO_PARCIAL || $infoItemplan['idEstadoPlan']    ==  ID_ESTADO_EN_APROBACION){
                if($infoItemplan['idEstacion'] == ID_ESTACION_FO || $infoItemplan['idEstacion'] == ID_ESTACION_COAXIAL || $infoItemplan['idEstacion'] == ID_ESTACION_FO_ALIM || $infoItemplan['idEstacion'] == ID_ESTACION_FO_DIST){
                    if($infoItemplan['paquetizado_fg'] == 1 || $infoItemplan['paquetizado_fg'] == 2){//modelo nuevo
						$data['error']= $this->m_integracion_rpa_bandeja_aprobacion->changeEstadoEnObraPlan($itemP, ID_ESTADO_PLAN_EN_OBRA)['error'];
					}else{//modelo antiguo					
						if($infoItemplan['idEstacion'] == ID_ESTACION_FO_ALIM || $infoItemplan['idEstacion'] == ID_ESTACION_FO_DIST){// CAMBIO CZAVALACAS 16.10.2019
							$hasDisenoEjec = $this->m_integracion_rpa_bandeja_aprobacion->estacionEjecutadaDiseno($itemP, ID_ESTACION_FO);                    
							if($hasDisenoEjec   >=  1){
								$data['error']= $this->m_integracion_rpa_bandeja_aprobacion->changeEstadoEnObraPlan($itemP, ID_ESTADO_PLAN_EN_OBRA)['error'];
							}else if($hasDisenoEjec ==  0){  
								$data['error']= $this->m_integracion_rpa_bandeja_aprobacion->changeEstadoEnObraPlan($itemP, ID_ESTADO_DISENIO_PARCIAL)['error'];
							}
						}else{
							$hasDisenoEjec = $this->m_integracion_rpa_bandeja_aprobacion->estacionEjecutadaDiseno($itemP, $infoItemplan['idEstacion']);                    
							if($hasDisenoEjec   >=  1){
								$data['error']= $this->m_integracion_rpa_bandeja_aprobacion->changeEstadoEnObraPlan($itemP, ID_ESTADO_PLAN_EN_OBRA)['error'];
							}else if($hasDisenoEjec ==  0){  
								$data['error']= $this->m_integracion_rpa_bandeja_aprobacion->changeEstadoEnObraPlan($itemP, ID_ESTADO_DISENIO_PARCIAL)['error'];
							}
						}		
					}					
                }
            }
			log_message('error', 'after diseno:'.print_r($data,true));
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
    
                $url = 'https://gicsapps.com:8080/obras2/recibir_dis.php';
                
                #log_message('error', 'Enviar Trama to Sisego:'.print_r($dataSend, true));
                
                $response = $this->m_utils->sendDataToURL($url, $dataSend);
                /*
				if($response->error == EXIT_SUCCESS){
                    $this->m_utils->saveLogSigoplus('BANDEJA DE APROBACIONT PTR FO - MAT', $sisego_ptr, $sisego_itemplan, $sisego_vr, $sisego_sisego, $sisego_eecc, $sisego_jefatura, 'TRAMA COMPLETADA', 'OPERACION REALIZADA CON EXITO:'. strtoupper($response->mensaje), 1);
                }else{
                    $this->m_utils->saveLogSigoplus('BANDEJA DE APROBACIONT PTR FO - MAT', $sisego_ptr, $sisego_itemplan, $sisego_vr, $sisego_sisego, $sisego_eecc, $sisego_jefatura, 'FALLA EN LA RESPUESTA DEL HOSTING', 'OPERACION NO COMPLETADA ERROR EN EL SERVIDOR DEL CLIENTE:'. strtoupper($response->mensaje), '2');
                }
                */
            }
    
            /*##################### INICIANDO TRAMA SIOM 13.05.2019 CZAVALACAS ###############################*/
			if(ID_ESTACION_FO	==	$infoItemplan['idEstacion'] ||
	ID_ESTACION_FO_ALIM	==	$infoItemplan['idEstacion']	||
	ID_ESTACION_FO_DIST	==	$infoItemplan['idEstacion']	||
	ID_ESTACION_COAXIAL	==	$infoItemplan['idEstacion']	||
	ID_ESTACION_OC_FO	==	$infoItemplan['idEstacion']	||
	ID_ESTACION_OC_COAXIAL	==	$infoItemplan['idEstacion']	||
	ID_ESTACION_UM	==	$infoItemplan['idEstacion']	||
	ID_ESTACION_AC_CLIENTE	==	$infoItemplan['idEstacion']	||
	ID_ESTACION_FUENTE	==	$infoItemplan['idEstacion']){
		
            $se_envio_fo = false;//nuevo para forzar envio de um
            if($infoItemplan['idEstadoPlan']    !=  ID_ESTADO_CANCELADO && $infoItemplan['idEstadoPlan']    !=  ID_ESTADO_CERRADO && $infoItemplan['idEstadoPlan']    !=  ID_ESTADO_TERMINADO && $infoItemplan['idEstadoPlan']    !=  ID_ESTADO_PRE_LIQUIDADO) {//NO REALIZAR ENVIOS DE ITEMPLANS CANCELADOS O CERRADOS.
                if($infoItemplan['tipoArea'] == 'MAT' && $infoItemplan['idEstacion'] != ID_ESTACION_DISENIO) {// SOLO SI LA PO O PTR ES MATERIAL DIFERENTE A DISENO
                    $ejecSiom = true;
                    if($infoItemplan['idEstacion'] == ID_ESTACION_FO || $infoItemplan['idEstacion'] == ID_ESTACION_COAXIAL){//SI ES FO O COAXIAL OBLIGAR LA EJECUCION DEL DISENO
                        $hasDisenoEjecutado = $this->m_integracion_rpa_bandeja_aprobacion->estacionEjecutadaDiseno($itemP, $infoItemplan['idEstacion']);
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
						
						if($infoItemplan['paquetizado_fg']==1 || $infoItemplan['paquetizado_fg']==2){
							$ejecSiom = true;
						}
                    }
					
                    $countSwitch = $this->m_utils->getCountSwitchSiom($infoItemplan['idEecc'], $infoItemplan['jefatura_ptr'], $infoItemplan['idSubProyecto']);
					if($countSwitch > 0) {//VALIDAMOS SI LA PO ESTA DENTRO DEL SWITCH POR SU EECC, JEFATURA Y SUBPROYECTO
                        $count = $this->m_utils->getCountSiom($itemP, $infoItemplan['idEstacion']);
                        if($count == 0) {//SI AUN NO HAY REGISTRO REALIZAMOS EL ENVIO A SIOM
                            $se_envio_fo = true;
                            $emplazamiento =  $this->m_integracion_rpa_bandeja_aprobacion->getEmplazamientoIdSiomByidCentral($infoItemplan['idCentral']);//OBTENEMOS EL ID DEZPLAZAMIENTO DE LA TABLA SIOM_NODOS POR EL ID CENTRAL DE LA PO
                            if($emplazamiento['cant'] >= 1  && $ejecSiom){// SE ENCONTRO NODO
                                $codigo_siom = $this->sendDataToSiom($infoItemplan['idEecc'], $infoItemplan['idEstacion'], $infoItemplan['estacionDesc'], $itemP, $emplazamiento['empl_id'], $ptr);
                                log_message('error', 'Enviar Trama to Siom:');
                                #$codigo_siom = 7788;//codigo harcodeado
                                //validar si no viene nulo
                                if($codigo_siom != null){
                                    $dataSiom = array('itemplan'          => $itemP,
                                        'idEstacion'        => $infoItemplan['idEstacion'],
                                        'ptr'               => $ptr,
                                        'fechaRegistro'     => $this->fechaActual(),
                                        'idUsuarioRegistro' => ID_USUARIO_RAP_SAP,
                                        'codigoSiom'        => $codigo_siom,
                                        'ultimo_estado'       => 'CREADA',
                                        'fecha_ultimo_estado' => $this->fechaActual()
                                    );
    
                                    $dataLogPo = array( 'tabla'            => 'Siom',
                                        'actividad'        => 'Registrar Siom',
                                        'itemplan'         => $itemP,
                                        'fecha_registro'   => $this->fechaActual(),
                                        'id_usuario'       => ID_USUARIO_RAP_SAP
                                    );
    
                                    $dataEstado = array('codigo_siom'           => $codigo_siom,
                                        'estado_desc'           => 'CREADA',
                                        'fechaRegistro'         => $this->fechaActual(),
                                        'usuario_registro'      => ID_USUARIO_RAP_SAP,
                                        'estado_transaccion'    => 1
                                    );
                                    $this->m_integracion_rpa_bandeja_aprobacion->insertSiom($dataSiom, $dataLogPo, $dataEstado);
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
                                    'usuario_envio' => ID_USUARIO_RAP_SAP,
                                    'fecha_envio'   => $this->fechaActual());
                                $dataLogSiom['estado']   =  $estadoError;//NODO NO ENCONTRADO = 4
                                $dataLogSiom['mensaje']  =  $motivoError;
    
                                $dataSiom = array('itemplan'          => $itemP,
                                    'idEstacion'        => $infoItemplan['idEstacion'],
                                    'ptr'               => $ptr,
                                    'fechaRegistro'     => $this->fechaActual(),
                                    'idUsuarioRegistro' => ID_USUARIO_RAP_SAP,
                                    'codigoSiom'        => null,
                                    'ultimo_estado'       => $motivoError,
                                    'fecha_ultimo_estado' => $this->fechaActual()
                                );
    
                                $this->m_integracion_rpa_bandeja_aprobacion->insertLogTramaSiom($dataLogSiom, $dataSiom);
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
            }
    
		}
		
        }catch(Exception $e){
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
    public function fechaActual()
    {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone', 'America/Lima');
        setlocale(LC_TIME, "es_ES", "esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
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
                $emplazamiento =  $this->m_integracion_rpa_bandeja_aprobacion->getEmplazamientoIdSiomByidCentral($infoItemplan['idCentral']);//OBTENEMOS EL ID DEZPLAZAMIENTO DE LA TABLA SIOM_NODOS POR EL ID CENTRAL DE LA PO
                if($emplazamiento['cant'] >= 1){// SE ENCONTRO NODO
                    $codigo_siom = $this->sendDataToSiom($infoItemplan['idEecc'], 13, 'UM', $itemP, $emplazamiento['empl_id'], $ptr);
                    //validar si no viene nulo
                    if($codigo_siom != null){
                        $dataSiom = array('itemplan'          => $itemP,
                            'idEstacion'        => 13,
                            'ptr'               => $ptr,
                            'fechaRegistro'     => $this->fechaActual(),
                            'idUsuarioRegistro' => ID_USUARIO_RAP_SAP,
                            'codigoSiom'        => $codigo_siom,
                            'ultimo_estado'       => 'CREADA',
                            'fecha_ultimo_estado' => $this->fechaActual()
                        );
    
                        $dataLogPo = array( 'tabla'            => 'Siom',
                            'actividad'        => 'Registrar Siom',
                            'itemplan'         => $itemP,
                            'fecha_registro'   => $this->fechaActual(),
                            'id_usuario'       => ID_USUARIO_RAP_SAP
                        );
    
                        $dataEstado = array('codigo_siom'           => $codigo_siom,
                            'estado_desc'           => 'CREADA',
                            'fechaRegistro'         => $this->fechaActual(),
                            'usuario_registro'      => ID_USUARIO_RAP_SAP,
                            'estado_transaccion'    => 1
                        );
                        $data = $this->m_integracion_rpa_bandeja_aprobacion->insertSiom($dataSiom, $dataLogPo, $dataEstado);
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
                        'usuario_envio' => ID_USUARIO_RAP_SAP,
                        'fecha_envio'   => $this->fechaActual());
                    $dataLogSiom['estado']  =  $estadoError;//NODO NO ENCONTRADO = 4
                    $dataLogSiom['mensaje']  =  $motivoError;
    
                    $dataSiom = array('itemplan'          => $itemP,
                        'idEstacion'        => 13,
                        'ptr'               => $ptr,
                        'fechaRegistro'     => $this->fechaActual(),
                        'idUsuarioRegistro' => ID_USUARIO_RAP_SAP,
                        'codigoSiom'        => null,
                        'ultimo_estado'       => $motivoError,
                        'fecha_ultimo_estado' => $this->fechaActual()
                    );
                    $data = $this->m_integracion_rpa_bandeja_aprobacion->insertLogTramaSiom($dataLogSiom, $dataSiom);
                }
            }else{
                throw new Exception('No se encontraron Datos Itemplan - PO');
            }
    
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        return $data;
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
                'usuario_envio' => 'RPA SAP User',
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
                $this->m_integracion_rpa_bandeja_aprobacion->insertLogTramaSiomSoloLog($dataLogSiom);
                log_message('error', 'TODO BIEN!');
            }else{//NO SE CREO LA OS
                $dataLogSiom['codigo']  =  $response->codigo;
                $dataLogSiom['mensaje'] =  $response->mensaje;
                $dataLogSiom['estado']  =  2;
                log_message('error', 'TODO MAL!');
                $this->m_integracion_rpa_bandeja_aprobacion->insertLogTramaSiomSoloLog($dataLogSiom);
            }
        }catch(Exception $e){//ERROR AL ACCEDER AL SERVIDOR
            log_message('error', 'ERROR EN EL SERVIDOR!!');
            $dataLogSiom['estado']  =  3;
            $this->m_integracion_rpa_bandeja_aprobacion->insertLogTramaSiomSoloLog($dataLogSiom);
        }
        return $codigo_siom;
    }
	
	/**********SERVICIO DE VALE DE RESEVA**************/
    
    /** v 2.0 Lista completa ROUTE : getVRMaterial **/
    public function getVRListToRPA()
    {
		_log("ENTRO ACAAAA22222");
    
        header("Access-Control-Allow-Origin: *");
        $output['codigo'] = EXIT_ERROR;
        $output['mensaje'] = 'No se encontro PO material disponible';
        try {
            $method = $_SERVER['REQUEST_METHOD'];
            if($method=='GET'){
    
                if (!isset($_SERVER['PHP_AUTH_USER']) && !isset($_SERVER['PHP_AUTH_PW'])) {
                    header('WWW-Authenticate: Basic realm="LOGIN REQUIRED"');
                    header('HTTP/1.0 401 Unauthorized');
                    $output['motivo'] = 'Access denied 401!';
                    throw new Exception('Usuario o clave Incorrectos (isset get)');
                }else{
                    if($_SERVER['PHP_AUTH_USER'] != 'RPA_USER' || $_SERVER['PHP_AUTH_PW'] != 'T3l3f0n1c4' ){
                        header('WWW-Authenticate: Basic realm="LOGIN REQUIRED"');
                        header('HTTP/1.0 401 Unauthorized');
                        $output['motivo'] = 'Access denied 401!';
                        throw new Exception('Usuario o clave Incorrectos (credenciales get)');
                    }
                }
    
                $poMatToSendList =  $this->m_integracion_rpa_bandeja_aprobacion->getVRToRpaList();
                $listaLogPoEnvio = array();
                $listaPoFlgEnvio = array();
                $listaFinalToRPASend = array();
                $numPoSend = 0;
                foreach($poMatToSendList as $poMaterial){
                    $outputIndividual = array();
                    $flg_paquetizado = $this->m_utils->getFlgPaquetizadoPo($poMaterial->itemplan);
                    if($flg_paquetizado == 2 || $flg_paquetizado == 1) {
                        $listaMateriales = $this->m_integracion_rpa_bandeja_aprobacion->getSolicitudToSapPqt($poMaterial->itemplan,$poMaterial->ptr, $poMaterial->codigo);
                    } else {
                        $listaMateriales = $this->m_integracion_rpa_bandeja_aprobacion->getSolicitudToSap($poMaterial->itemplan,$poMaterial->ptr, $poMaterial->codigo);
                    }
    
                    $numMateriales = count($listaMateriales);
                    if($numMateriales>0){
                        $outputIndividual['itemplan']   	= $poMaterial->itemplan;
                        $outputIndividual['codigo_po']  	= $poMaterial->ptr;
                        $outputIndividual['vale_reserva']   = $poMaterial->vale_reserva;
						$outputIndividual['solicitud']      = $poMaterial->codigo;
                        $outputIndividual['materiales']   	= $listaMateriales;
                        $dataEstadoInsertLog = array ('codigo_po'  => $outputIndividual['codigo_po'],
                            'itemplan'   => $outputIndividual['itemplan'],
                            'fecha'     => $this->fechaActual(),
                            'estado'    => 5,
                            'mensaje'   => 'PO enviada',
                            'dataGet'    => json_encode($outputIndividual)
                        );
                        $dataPlanobraPo = array ('activo_rpa' => 1,
                            'codigo_po' => $outputIndividual['codigo_po']);
    
                        array_push($listaLogPoEnvio, $dataEstadoInsertLog);
                        array_push($listaPoFlgEnvio, $dataPlanobraPo);
                        array_push($listaFinalToRPASend, $outputIndividual);
                        $numPoSend++;
                    }else{
                        log_message('error', '----->NO tiene materiales');
                    }
                }
    
                if(count($listaFinalToRPASend) > 0){//si hay para mandar
                    //$this->m_integracion_rpa_bandeja_aprobacion->tramaNoOkRpa($dataEstadoInsertLog, $dataPlanobraPo, $output['codigo_po'], $output['itemplan']);
                    $dataEstadoInsertLog = array ('codigo_po'  => '',
                        'itemplan'      => 'VALE RESERVA',
                        'fecha'         => $this->fechaActual(),
                        'estado'        => 5,//todo ok
                        'mensaje'       => 'Dato Enviado',
                        'dataGet'       =>  json_encode($listaFinalToRPASend),
                        'num_po_send'   =>  $numPoSend
                    );
                    $this->m_integracion_rpa_bandeja_aprobacion->tramaOkRpaVR($dataEstadoInsertLog);
                    	
                    $output['po_lista'] = $listaFinalToRPASend;
                    $output['codigo'] = EXIT_SUCCESS;
                    $output['mensaje'] = 'VR Material Enviada';
                }else{
                    $dataEstadoInsertLog = array ('codigo_po'  => '',
                        'itemplan'      => 'VALE RESERVA',
                        'fecha'         => $this->fechaActual(),
                        'estado'        => 5,//todo ok
                        'mensaje'       => 'Dato Enviado',
                        'dataGet'       =>  json_encode($listaFinalToRPASend),
                        'num_po_send'   =>  $numPoSend
                    );
                    $this->m_integracion_rpa_bandeja_aprobacion->tramaOkRpaVR($dataEstadoInsertLog);
                     
                    $output['po_lista'] = $listaFinalToRPASend;
                    $output['codigo'] = EXIT_SUCCESS;
                }
    
            }else{
                $output = $this->getMsjTypeMethod($method, 'getPoMaterial');
            }
        } catch (Exception $e) {
            $output['mensaje'] = 'Error Interno, no se obtuvo una Po Material, '.$e->getMessage();
        }
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($output));
    }
	
	 
    /** get estatus vale reserva **/
    
     public function cambiarEstadoVRFromWSRpaSapV2()
     {
    
         header("Access-Control-Allow-Origin: *");
         $output['codigo'] = EXIT_ERROR;
         $output['mensaje'] = 'No se actualizo el Vale de Reserva!';
         try {

             $arrayLogVr = array();
             $array      = array();
             $arrayDetallePo = array();
             $arrayUpdateSolicitud = array();
             $updateDetallePo = array();

             $method = $_SERVER['REQUEST_METHOD'];
             if($method=='PUT' || $method=='POST'){
    
                 if (!isset($_SERVER['PHP_AUTH_USER']) && !isset($_SERVER['PHP_AUTH_PW'])) {
                     header('WWW-Authenticate: Basic realm="LOGIN REQUIRED"');
                     header('HTTP/1.0 401 Unauthorized');
                     $output['motivo'] = 'Access denied 401!';
                    $output['codigo'] = 2;
                    throw new Exception('Usuario o clave Incorrectos(isset)');
                }else{
                     if($_SERVER['PHP_AUTH_USER'] != 'RPA_USER' || $_SERVER['PHP_AUTH_PW'] != 'T3l3f0n1c4' ){
                         header('WWW-Authenticate: Basic realm="LOGIN REQUIRED"');
                         header('HTTP/1.0 401 Unauthorized');
                         $output['motivo'] = 'Access denied 401!';
                         $output['codigo'] = 2;
                         throw new Exception('Usuario o clave Incorrectos (credenciales)');
                    }
                 }
    
                 $inputJSON = file_get_contents('php://input');
                 _log(':::cambiarEstadoVRFromWSRpaSap:::'.print_r($inputJSON,true));
                 $input = json_decode( $inputJSON, TRUE );

         
                     $dataEstadoInsertLog = array (
                         'fecha'     => $this->fechaActual(),
                         'estado'    => 1,
                         'dataGet'    => $inputJSON
                     );
                     $this->m_integracion_rpa_bandeja_aprobacion->tramaOkRpaVR($dataEstadoInsertLog);
                     $output['codigo'] = EXIT_SUCCESS;
                     $output['mensaje'] = 'Se actualizo El Vale de Reserva (PRUEBAS)';
  


             }else{
                 $output = $this->getMsjTypeMethod($method, 'updatePoMaterial');
             }

             $this->db->trans_commit();
         } catch (Exception $e) {
             $this->db->trans_rollback();
             $output['mensaje'] = 'Error Interno, No se actualizo el Vale de Reserva, '.$e->getMessage();
            
             $dataEstadoInsertLog = array (
                 'fecha'      => $this->fechaActual(),
                 'estado'     => 4,//otro error
                 'exception'  => $e->getMessage(),
                 'dataGet'    => isset($inputJSON)
             );
             $this->m_integracion_rpa_bandeja_aprobacion->tramaOkRpaVR($dataEstadoInsertLog);
           
         }
         $this->output->set_content_type('application/json');
         $this->output->set_output(json_encode($output));
     }

    function cambiarEstadoVRFromWSRpaSap() {
        header("Access-Control-Allow-Origin: *");
        $output['codigo'] = EXIT_ERROR;
        $output['mensaje'] = 'No se actualizo el Vale de Reserva!';
        try {
            $method = $_SERVER['REQUEST_METHOD'];
            if($method=='PUT' || $method=='POST'){
                // if (!isset($_SERVER['PHP_AUTH_USER']) && !isset($_SERVER['PHP_AUTH_PW'])) {
                    // header('WWW-Authenticate: Basic realm="LOGIN REQUIRED"');
                    // header('HTTP/1.0 401 Unauthorized');
                    // $output['motivo'] = 'Access denied 401!';
                    // $output['codigo'] = 2;
                    // throw new Exception('Usuario o clave Incorrectos(isset)');
                // }else{
                    // if($_SERVER['PHP_AUTH_USER'] != 'RPA_USER' || $_SERVER['PHP_AUTH_PW'] != 'T3l3f0n1c4' ){
                        // header('WWW-Authenticate: Basic realm="LOGIN REQUIRED"');
                        // header('HTTP/1.0 401 Unauthorized');
                        // $output['motivo'] = 'Access denied 401!';
                        // $output['codigo'] = 2;
                        // throw new Exception('Usuario o clave Incorrectos (credenciales)');
                    // }
                // }
                $this->db->trans_begin();

                $inputJSON = file_get_contents('php://input');

                $input = json_decode( $inputJSON, TRUE );

                $arrayLogVr = array();
                $arrayDetallePo = array();
                $arrayUpdateSolicitud = array();
                $updateDetallePo = array();

                if(!isset($input['itemplan'])){
                    throw new Exception('No existe el itemplan');
                                    }                
                if(!isset($input['codigo_po'])){
                    throw new Exception('No existe el codigo PO');
                                    }
                if(!isset($input['vale_reserva'])){
                    throw new Exception('no existe el vale de reserva');
                }            
                if(!isset($input['materiales'])){
                    throw new Exception('No existe materiales');
                }
				if(!isset($input['solicitud'])){
                    throw new Exception('No existe el codigo de solicitud');
                }

			//abres  for de materiales
                foreach($input['materiales'] as $row){
                    $materialInfo = $this->m_integracion_rpa_bandeja_aprobacion->validSendTramaSisego($input['solicitud'], $row['material']);
                    if($materialInfo!=null){
                        if($row['error']   ==  0){                     
                            if($materialInfo['flg_estado']==1){//
                                //log este material ya esta atendido...
                                throw new Exception('El material: '.$row['material'].' ya se encuentra atendido.');
                            }else{
                                $arrayUpdateSolicitud[] = array(
                                                                    'idSolicitudValeReserva' => $materialInfo['idSolicitudValeReserva'],
                                                                    'fecha_atencion' => $this->fechaActual(),
                                                                    'flg_estado'  => 1,
																	'vr_robot'    => $input['vale_reserva']
                                                                );   
                                $arrayLogVr[] = array (
                                                        'idSolicitudValeReserva' => $materialInfo['idSolicitudValeReserva'],
                                                        'itemplan'               => $materialInfo['itemplan'],
                                                        'ptr'                    => $materialInfo['ptr'],
                                                        'comentario'             => 'VALIDACION POR ROBOT SAP',
                                                        'flg_estado'             => 1,
                                                        'fecha_registro'         => $this->fechaActual(),
														'material'				=>	$row['material'],
														'mensaje'				=>	$row['mensaje'],
														'dataGet'				=>	$inputJSON
                                                      );
                                $flgExistPODetalle = $this->m_integracion_rpa_bandeja_aprobacion->getExistPoRpa($materialInfo['material'], $materialInfo['ptr']);
                                if($flgExistPODetalle == 1) {
                                    $updateDetallePo[] = array (
                                                                    'codigo_po'        => $materialInfo['ptr'],
                                                                    'codigo_material'  => $materialInfo['material'],
                                                                    'cantidad_final'   => $materialInfo['cantidadFin']
                                                                );
                                } else {
                                    $arrayDetallePo[] = array (
                                        'codigo_po'        => $materialInfo['ptr'],
                                        'codigo_material'  => $materialInfo['material'],
                                        'cantidad_ingreso' => $materialInfo['cantidadFin'],
                                        'cantidad_final'   => $materialInfo['cantidadFin'],
                                    );   
                                }   
								
								#realizas el proces..
                            }
                        }else if($row['error']   ==  1){
                            //el robot no pudo actualizarlo generas una alerta en web po.. va a la bandeja de tramas..
							$arrayLogVr = array (
													'idSolicitudValeReserva' => $materialInfo['idSolicitudValeReserva'],
													'itemplan'               => $materialInfo['itemplan'],
													'ptr'                    => $materialInfo['ptr'],
													'comentario'             => 'ERROR EN LA VALIDACION ROBOT SAP',
													'flg_estado'             => 0,
													'fecha_registro'         => $this->fechaActual(),
													'material'				=>	$row['material'],
													'mensaje'				=>	$row['mensaje'],
													'dataGet'				=>	$inputJSON
												  );
							$this->m_integracion_rpa_bandeja_aprobacion->insertLogVr($arrayLogVr);				  
							throw new Exception('El robot no pudo actualizar en el SAP.');
                        }
                    }else{
                        throw new Exception('No enviaron materiales');
                    }                
                }        

		//cierra for			
		
                $data = $this->m_integracion_rpa_bandeja_aprobacion->ingresarDetallePoRpa($arrayDetallePo, $arrayUpdateSolicitud, $updateDetallePo, $arrayLogVr);

                if($data['error'] == EXIT_SUCCESS) {
                    $data = $this->m_integracion_rpa_bandeja_aprobacion->updateTotalPoRpa($input['codigo_po'], $input['vale_reserva']);
    
                    if($data['error'] == EXIT_ERROR) {
                        throw new Exception($data['msj']);
                    } else {
                        $dataEstadoInsertLog = array (
                            'fecha'     => $this->fechaActual(),
                            'estado'    => 1,
                            'dataGet'    => $inputJSON
                        );
                        $this->m_integracion_rpa_bandeja_aprobacion->tramaOkRpaVR($dataEstadoInsertLog);
                        $output['codigo'] = EXIT_SUCCESS;
                        $output['mensaje'] = 'Se actualizo El Vale de Reserva (PRUEBAS)';   
                    }
                } else {
                    throw new Exception('error NO INGRESO EL VALE DE RESERVA');
                }
                           
                $this->db->trans_commit();
            }else{
                $output = $this->getMsjTypeMethod($method, 'updatePoMaterial');
            }
            
        } catch (Exception $e) {
            $this->db->trans_rollback();
            $output['mensaje'] = 'Error Interno, No se actualizo el Vale de Reserva, '.$e->getMessage();
            
            $dataEstadoInsertLog = array (
                'fecha'      => $this->fechaActual(),
                'estado'     => 2,//otro error
                'exception'  => $e->getMessage(),
                'dataGet'    => $inputJSON
            );
            $this->m_integracion_rpa_bandeja_aprobacion->tramaOkRpaVR($dataEstadoInsertLog);
            
        }
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($output));
    }
	
	function restaurarRobotVr() {
		$obj = $this->m_integracion_rpa_bandeja_aprobacion->getDataJson();
		
		foreach($obj as $row) {
			$arrayData[] = array($row['dataGet']);
		}
		
		$data['arrayJson'] = $arrayData;
		
		echo json_encode($data);
	}
}