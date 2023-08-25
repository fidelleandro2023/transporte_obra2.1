<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 *
 *
 */
class C_integracion_siom extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_servicios/m_integracion_siom');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_log/m_log_ingfix');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index()
    {      $url = 'https://gicsapps.com:8080/obras2/recibir_eje.php';
        $this->enviarTramaToSisegoFromSiom('19-0310900769', '2019-08-137965-1', $url, 6);

    }

    public function cerrarOSFromSiom()
    {
        
        header("Access-Control-Allow-Origin: *");        
        $output['codigo'] = EXIT_ERROR;
        $output['mensaje'] = 'No se Cerro La OS';
        try {           
           $method = $_SERVER['REQUEST_METHOD'];
           if($method=='PUT' || $method=='POST'){               
               $inputJSON = file_get_contents('php://input');
               $input = json_decode( $inputJSON, TRUE );
               log_message('error', print_r($input,true));
               if(!isset($input['orseId'])){
                   throw new Exception('No se detecto codigo de OS en la trama de envio.');
               }
               $output['codigo'] = EXIT_SUCCESS;
               $output['mensaje'] = "Se Cerro La OS Nro. ".$input['orseId'];
           }else{
               $output = $this->getMsjTypeMethod($method, 'cerrarOs');
           }           
        } catch (Exception $e) {
            $output['mensaje'] = 'No se Cerro La OS, '.$e->getMessage();
        }
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($output));
    }
    
    public function cambiarEstadoOsFromSiom()
    {
    
        header("Access-Control-Allow-Origin: *");
        $output['codigo'] = EXIT_ERROR;
        $output['mensaje'] = 'No se actualizo La OS';
        try {
            $method = $_SERVER['REQUEST_METHOD'];
            if($method=='PUT' || $method=='POST'){
                $inputJSON = file_get_contents('php://input');
                log_message('error', ':::Cambio Estado:::'.print_r($inputJSON,true));
                $input = json_decode( $inputJSON, TRUE );
                log_message('error', ':::Cambio Estado:::'.print_r($input,true));
                if(!isset($input['orseId'])){
                    throw new Exception('No se detecto codigo de OS en la trama de envio.');
                }
                
                $dataSiomUpdate = array( 'codigoSiom'          => $input['orseId'],
                                         'ultimo_estado'       => $input['orseEstado'],
                                         'fecha_ultimo_estado' => $this->fechaActual());
                
                $dataEstadoInsert = array( 'codigo_siom'      => $input['orseId'],
                                           'estado_desc'           => $input['orseEstado'],
                                           'fechaRegistro'    => $this->fechaActual(),
                                           'usuario_registro' => 'SIOM WEB',
                                            'estado_transaccion' => 1
                );
                
                $data = $this->m_integracion_siom->cambiarEstadoSiom($dataSiomUpdate, $dataEstadoInsert, $input['orseId']);
                if($data['error']   ==  EXIT_SUCCESS){
                    $output['codigo'] = EXIT_SUCCESS;
                    $output['mensaje'] = "Se actualizo La OS Nro. ".$input['orseId'];
                    //$reUp = $this->updateAvanceItemPlanFromSiom($input['orseId'], $input['orseEstado']);
                }else{
                    $dataEstadoInsert = array( 'codigo_siom'      => $input['orseId'],
                                                'estado'           => $input['orseEstado'],
                                                'fechaRegistro'    => $this->fechaActual(),
                                                'usuario_registro' => 'SIOM WEB',
                                                'estado_transaccion' => 2,
                                                'motivo'            => $data['msj']
                    );
                    $this->m_integracion_siom->insertFailCambioEstado($dataEstadoInsert);
                    throw new Exception('Codigo de error: 0012 - WEB PO');
                }
            }else{
                $output = $this->getMsjTypeMethod($method, 'cambiarEstadoOs');
            }
        } catch (Exception $e) {
            $output['mensaje'] = 'No se actualizo La OS, '.$e->getMessage();
        }
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($output));
    }
    
    public function getMsjTypeMethod($type, $path){
        $arr = array('error' => 'Method Not Allowed', 'message' => "Request method '".$type."' not supported", 'path' => $path);
        return $arr;
    }
    
    function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone','America/Lima');
        setlocale(LC_TIME, "es_ES","esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }
    
    function updateAvanceItemPlanFromSiom($codigo_siom, $estado){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try{
            if($estado  ==  'VALIDANDO' ||  $estado  ==  'APROBADA' ||  $estado  ==  'RECHAZADA'  || $estado  ==  'ANULADA'){//SOLO ESTOS ESTADOS EJECUTAN UN CAMBIO
                $infSiom = $this->m_integracion_siom->getInfoSIomByCodigoSiom($codigo_siom);//OBTENEMOS ITEMPLAN Y PTR DEL CODIGO SIOM            
                if($infSiom!=null){
                    if($infSiom['esta_anclas_siom'] !=  0 && $infSiom['esta_anclas_siom'] !=null){//SI CUENTA CON ANCLAS
                        $listEstacionesOS = $this->m_integracion_siom->getDataEstados($infSiom['itemplan'], explode(',', $infSiom['esta_anclas_siom']));
                        if($estado  ==  'VALIDANDO' || $estado  ==  'ANULADA'){//pasamos a preliquidar
                            /***subimos porcentaje de la estacion al 100 % VALIDANDO QUE toads las OS esten validadas de esa estacion******/ 
                            $osByItemEstacion = $this->m_integracion_siom->getValidadosByItemplanEstacion($infSiom['itemplan'], $infSiom['idEstacion']);
                            if($osByItemEstacion['num_os'] >= 1 && ($osByItemEstacion['validados'] == $osByItemEstacion['num_os'])){
                                $infoAvance = $this->m_integracion_siom->getInfoItemplanEstacionAvance($codigo_siom);
                                if($infoAvance != null){//validamos que tenemos registrado la os en siom_obra
                                    $dataInsertAvance = array(
                                        "itemplan" => $infoAvance['itemplan'],
                                        "idEstacion" => $infoAvance['idEstacion'],
                                        "porcentaje" => '100',
                                        "fecha" => $this->fechaActual(),
                                        "comentario" => 'FROM SIOM',
                                        "flg_evidencia" => 1
                                    );
                                    
                                    if($infoAvance['idItemplanEstacion'] == null){//VALIDAMOS QUE EXISTE EL REGISTRO en itemplanestacionavance
                                        $data = $this->m_integracion_siom->insertItemplanEstacionAvance($dataInsertAvance);
                                    }else {//si existe update
                                        if($infoAvance['porcentaje'] != '100'){//si no esta al 100%
                                            $data = $this->m_integracion_siom->updateItemplanEstacionAvance($dataInsertAvance, $infoAvance['idItemplanEstacion']);
                                        }else{
                                            _log('Ya se encuentra al 100%');
                                        }
                                    }
                                    /***LIQUIDAR LAS PO MAT czavalacas 06.07.2019***/
                                     $data = $this->liquidarPoMatByItemplanEstacion($infSiom['itemplan'], $infSiom['idEstacion']);
                                     /*************************/
                                }else{
                                    _log('OS NO REGISTRADA.'.$codigo_siom);
                                }
                            }
							$flg_paquetizado = $this->m_utils->getFlgPaquetizadoPo($infSiom['itemplan']);
                            if($infSiom['idEstadoPlan']    ==  ID_ESTADO_PLAN_EN_OBRA){//SI ESTA EN OBRA Y PERTENECE AL NUEVO MODELO, PODREMOS PRE LIQUIDAR
                                $hasFoOS = false;
                                $hasUMValid = false;
                                
                                $hasUmOS = false;//24.06.2019 czavalacas FO SOLO TMB LIQUIDA LA OBRA DE SISEGOS.
                                $hasFOValid = false;
                                
                                $numEstaTotal = 0;
                                $numEstaOK = 0;
                                foreach ($listEstacionesOS as $row){
                                   if($row->num_os > 0 && ($row->validando > 0 || $row->aprobada > 0)){
                                       $numValAproRech = $row->validando + $row->aprobada + $row->rechazada + $row->anulada;
                                       if($row->num_os == $numValAproRech){
                                           $numEstaOK = $numEstaOK + 1;                                           
                                           if($row->idEstacion == ID_ESTACION_UM && $row->num_os > 0){//validamos si cuenta con OS en UM y validada o aprobada.
                                               $hasUMValid   =   true;
                                           }
                                           if($row->idEstacion == ID_ESTACION_FO && $row->num_os > 0){//validamos si cuenta con OS en UM y validada o aprobada.
                                               $hasFOValid   =   true;
                                           }
                                       }
                                   }
                                   $numEstaTotal = $numEstaTotal + 1;
                                   /**nueva validacion preliquidacion sisegos y aceleracion movil**/
                                   if($row->idEstacion == ID_ESTACION_FO && $row->num_os > 0 && ($row->num_os != ($row->rechazada + $row->anulada))){//validamos que almenos tenga 1 OS para que vaya por el flujo normal
                                       $hasFoOS   =   true;
                                   }
                                   if($row->idEstacion == ID_ESTACION_UM && $row->num_os > 0 && ($row->num_os != ($row->rechazada + $row->anulada))){//validamos que almenos tenga 1 OS De ultima milla 
                                       $hasUmOS   =   true;
                                   }
                                }
								if($infSiom['idEstacion'] == 2 || $infSiom['idEstacion'] == 5) {//SI ES FO o COAX... BUSCAMOS SI TIENE FICHA TECNICA. 19/02/20
									$countFichaTecnica = $this->m_utils->countFichaTecnicaByItemplanAndEstacion($infSiom['itemplan'], $infSiom['idEstacion']);
								} else {
									$countFichaTecnica = 1;
								}
                                
                                #EVALUACION PARA PASE A PRELIQUIDACION SISEGOS Y ACELERACION MOVIL
                                
                                if(($infSiom['idProyecto'] == ID_PROYECTO_MOVILES || $infSiom['idProyecto'] == ID_PROYECTO_SISEGOS) && !$hasFoOS && $hasUMValid){
                                    //preliquidar plan
                                    $arrayData = array( 'idEstadoPlan'        => ID_ESTADO_PRE_LIQUIDADO,
														'fechaPreLiquidacion' => $this->fechaActual(),
														'id_usuario_preliquidacion' => $this->session->userdata('idPersonaSession'));
                                    $data = $this->m_integracion_siom->updatePlanObra($infSiom['itemplan'], $arrayData);
									
									if($infSiom['idProyecto'] == ID_PROYECTO_SISEGOS) {
										if($infSiom['idEstacion'] == ID_ESTACION_UM){//SI ES UM
											$sendSise = $this->m_integracion_siom->validSenSisegoWeb($infSiom['itemplan']);
											if($sendSise['cant'] >= 1){//SI ESTA EN LA TABLA SWITCH VALIDAR SI NO SE ENVIO ANTES.
											   $beforeSend = $this->m_integracion_siom->validSendTramaSisego($infSiom['itemplan'], $infSiom['idEstacion']);
												 if($beforeSend['cant'] ==  0){
													 if($infSiom['idEstacion'] == ID_ESTACION_FO){
														 $url = 'https://172.30.5.10:8080/obras2/recibir_eje.php';                                                  
													 }else if($infSiom['idEstacion'] == ID_ESTACION_UM){
														 $url = 'https://172.30.5.10:8080/obras2/recibir_ejeUm.php';                                                  
													 }
													 $this->enviarTramaToSisegoFromSiom($infSiom['itemplan'], $infSiom['indicador'], $url, $infSiom['idEstacion']);
												}
											}                                    
										}
									}
                                }else if($infSiom['idProyecto'] == ID_PROYECTO_SISEGOS && !$hasUmOS && $hasFOValid && $countFichaTecnica > 0){
                                    $arrayData = array(	'idEstadoPlan'        => ID_ESTADO_PRE_LIQUIDADO,
														'fechaPreLiquidacion' => $this->fechaActual(),
														'id_usuario_preliquidacion' => $this->session->userdata('idPersonaSession'));
                                    $data = $this->m_integracion_siom->updatePlanObra($infSiom['itemplan'], $arrayData);
									
									 /**SI YA LLEGO AL 100 ENVIAR A SISEGO WEB**/
									if($infSiom['idEstacion'] == ID_ESTACION_FO || $infSiom['idEstacion'] == ID_ESTACION_UM){//SI ES FO O UM
										$sendSise = $this->m_integracion_siom->validSenSisegoWeb($infSiom['itemplan']);
										if($sendSise['cant'] >= 1){//SI ESTA EN LA TABLA SWITCH VALIDAR SI NO SE ENVIO ANTES.
										   $beforeSend = $this->m_integracion_siom->validSendTramaSisego($infSiom['itemplan'], $infSiom['idEstacion']);
											 if($beforeSend['cant'] ==  0){
												 if($infSiom['idEstacion'] == ID_ESTACION_FO){
													 $url = 'https://172.30.5.10:8080/obras2/recibir_eje.php';                                                  
												 }else if($infSiom['idEstacion'] == ID_ESTACION_UM){
													 $url = 'https://172.30.5.10:8080/obras2/recibir_ejeUm.php';                                                  
												 }
												 $this->enviarTramaToSisegoFromSiom($infSiom['itemplan'], $infSiom['indicador'], $url, $infSiom['idEstacion']);
											}
										}                                    
									}
									/******************************/
                                
                                }else{
                                    if(($numEstaTotal    ==  $numEstaOK) && $numEstaTotal > 0){//si todas las estaciones cumplen podemos preliquidar.
                                        //preliquidar plan
                                        $arrayData = array('idEstadoPlan'        => ID_ESTADO_PRE_LIQUIDADO,
                                            'fechaPreLiquidacion' => $this->fechaActual(),
                                            'id_usuario_preliquidacion' => $this->session->userdata('idPersonaSession'));
                                        $data = $this->m_integracion_siom->updatePlanObra($infSiom['itemplan'], $arrayData);
                                    
                                    }else{
                                        _log('SIOM:No cuenta con requisitos para PRELIQUIDAR ITEMPLAN.'.print_r($listEstacionesOS, true));
                                    }
                                }
                                
                            }else{
                                _log('VALIDANDO:No se encuentra en obra o no es del nuevo modelo');
                            }
                        }else if($estado  ==  'APROBADA'){//pasamos a terminar
                            if($infSiom['idEstadoPlan']    ==  ID_ESTADO_PRE_LIQUIDADO || $infSiom['idEstadoPlan']    ==  ID_ESTADO_EN_VALIDACION){//SI ESTA EN PRE LIQUIDADO PODREMOS TERMINAR LA OBRA.
                                #EVALUACION PARA PASE A TERMINADO
                                $numEstaTotal = 0;
                                $numEstaOK = 0;
                                foreach ($listEstacionesOS as $row){
                                    if($row->num_os > 0 && $row->aprobada > 0){//si tiene os y al menos una esta aprobada
                                        $numValAproRech = $row->aprobada + $row->rechazada;
                                        if($row->num_os == $numValAproRech){
                                            $numEstaOK = $numEstaOK + 1;
                                        }
                                    }
                                    $numEstaTotal = $numEstaTotal + 1;
                                }
                                if(($numEstaTotal    ==  $numEstaOK) && $numEstaTotal > 0){//si todas las estaciones cumplen podemos preliquidar.
                                    $terminarItemplan = false;//por defecto liquidar
                                    //preliquidar plan
                                    $listEstaDJ = $this->m_integracion_siom->getDJValidadasByItemplan($infSiom['itemplan'], explode(',', $infSiom['esta_anclas_siom']));
                                    if($listEstaDJ){//TIENE STACIONES ANCLAS VALIDEMOS SI CUENTA CON LA DJ VALIDADA
                                        foreach($listEstaDJ as $row){// VALIDA QUE TODAS SUS ESTACIONES ANCLAS QUE PASAN POR SIOM TENGAN DJ VALIDADA.
                                            if($row->id_ficha_tecnica !=  null){
                                                $terminarItemplan = true;
                                            }else{
                                                $terminarItemplan = false;
                                                break;
                                            }
                                        }
                                    }else{//preguntar si ,las que no tienen estaciones anclas
                                        log_message('error', 'NO TIENE ESTACIONES ANCLAS');
                                        $terminarItemplan = true;//por defecto liquidar
                                    }
                                    
                                    if($terminarItemplan){
                                        log_message('error', 'SIOM:TERMINAR ITEMPLAN'.print_r($listEstacionesOS, true));
                                        $arrayData = array( 'idEstadoPlan'        => ID_ESTADO_TERMINADO,
                                                            'fechaEjecucion' => $this->fechaActual(),
                                                            'fecha_upd' =>  $this->fechaActual(),
                                                            'usu_upd' =>  'SIOM WEB',
                                                            'descripcion' => 'FROM SIOM');
                                        $data = $this->m_integracion_siom->updatePlanObra($infSiom['itemplan'], $arrayData);
                                    }else{
                                        log_message('error', 'NO CUENTA CON DJ VALIDADAS'.print_r($listEstacionesOS, true));
                                    }
                                    
                                }else{
                                    log_message('error', 'SIOM:No cuenta con requisitos para terminar ITEMPLAN.'.print_r($listEstacionesOS, true));
                                }
                            }else{
                                log_message('error', 'APROBADA:no se encuentra en pre liquidado');
                            }
                        }else if($estado  ==  'RECHAZADA'){//pasamos a retornar a en obra.
                            if($infSiom['idEstadoPlan']    ==  ID_ESTADO_PRE_LIQUIDADO){//SI ESTA EN PRE LIQUIDADO PODREMOS RETORNAR A EN OBRA.
                                #EVALUACION PARA RETORNO A EN OBRA
                                $numEstaTotal = 0;
                                $numEstaOK = 0;
                                foreach ($listEstacionesOS as $row){
                                    if($row->num_os > 0 && $row->rechazada > 0){//si tiene os y al menos una esta aprobada
                                        $numValAproRech = $row->rechazada;
                                        if($row->num_os == $numValAproRech){
                                            $numEstaOK = $numEstaOK + 1;
                                        }
                                    }
                                    $numEstaTotal = $numEstaTotal + 1;
                                }
                                if(($numEstaTotal    ==  $numEstaOK) && $numEstaTotal > 0){//si todas las estaciones cumplen podemos preliquidar.
                                    //preliquidar plan
                                    log_message('error', 'SIOM:RETORNAR A EN OBRA'.print_r($listEstacionesOS, true));
                                    $arrayData = array( 'idEstadoPlan'           => ID_ESTADO_PLAN_EN_OBRA,
                                                        'fechaPreLiquidacion'    => null,
                                                        'id_usuario_preliquidacion' => null
                                    );
                                    $data = $this->m_integracion_siom->updatePlanObra($infSiom['itemplan'], $arrayData);
                                }else{
                                    log_message('error', 'SIOM:No cuenta con requisitos para RETORNAR A EN OBRA.'.print_r($listEstacionesOS, true));
                                }
                            }else{
                                log_message('error', 'RECHAZADA: no se encuentra en pre liquidado');
                            }
                        }
                    }else if($infSiom['esta_anclas_siom'] ==  0){//NO TIENE ANCLAS DEFINIDAS CUALQUIERA EJECUTA ACCION
                         //definir logica..   
                         log_message('error', 'SU ESTACION ANCLA ES 0.');
                         $listEstacionesOS = $this->m_integracion_siom->getDataEstadosAnclaCero($infSiom['itemplan']);
                         if($estado  ==  'VALIDANDO' || $estado  ==  'ANULADA'){//pasamos a preliquidar
                             
                             /***subimos porcentaje de la estacion al 100 % VALIDANDO QUE toads las OS esten validadas de esa estacion******/ 
                            $osByItemEstacion = $this->m_integracion_siom->getValidadosByItemplanEstacion($infSiom['itemplan'], $infSiom['idEstacion']);
                            if($osByItemEstacion['num_os'] >= 1 && ($osByItemEstacion['validados'] == $osByItemEstacion['num_os'])){
                                 $infoAvance = $this->m_integracion_siom->getInfoItemplanEstacionAvance($codigo_siom);
                                 if($infoAvance != null){//validamos que tenemos registrado la os en siom_obra
                                     $dataInsertAvance = array(
                                         "itemplan" => $infoAvance['itemplan'],
                                         "idEstacion" => $infoAvance['idEstacion'],
                                         "porcentaje" => '100',
                                         "fecha" => $this->fechaActual(),
                                         "comentario" => 'FROM SIOM',
                                         "flg_evidencia" => 1
                                     );
                                 
                                     if($infoAvance['idItemplanEstacion'] == null){//VALIDAMOS QUE EXISTE EL REGISTRO en itemplanestacionavance
                                         $data = $this->m_integracion_siom->insertItemplanEstacionAvance($dataInsertAvance);
                                     }else {//si existe update
                                         if($infoAvance['porcentaje'] != '100'){//si no esta al 100%
                                             $data = $this->m_integracion_siom->updateItemplanEstacionAvance($dataInsertAvance, $infoAvance['idItemplanEstacion']);
                                         }else{
                                             log_message('error', 'Ya se encuentra al 100%');
                                         }
                                     }
                                     /***LIQUIDAR LAS PO MAT czavalacas 06.07.2019***/
                                     $data = $this->liquidarPoMatByItemplanEstacion($infSiom['itemplan'], $infSiom['idEstacion']);
                                     /*************************/
                                 }else{
                                     log_message('error', 'OS NO REGISTRADA.'.$codigo_siom);
                                 }
                            }
                             if($infSiom['idEstadoPlan']    ==  ID_ESTADO_PLAN_EN_OBRA){//SI ESTA EN OBRA PODREMOS PRE LIQUIDAR
                                 #EVALUACION PARA PASE A PRELIQUIDACION
                                 $numEstaTotal = 0;
                                 $numEstaOK = 0;
                                 foreach ($listEstacionesOS as $row){
                                     if($row->num_os > 0 && ($row->validando > 0 || $row->aprobada > 0)){
                                         $numValAproRech = $row->validando + $row->aprobada + $row->rechazada;
                                         if($row->num_os == $numValAproRech){
                                             $numEstaOK = $numEstaOK + 1;
                                         }
                                     }
                                     $numEstaTotal = $numEstaTotal + 1;
                                 }
                                 if(($numEstaTotal    ==  $numEstaOK) && $numEstaTotal > 0){//si todas las estaciones cumplen podemos preliquidar.
                                     //preliquidar plan
                                     log_message('error', 'SIOM:PRELIQUIDAR ITEMPLAN'.print_r($listEstacionesOS, true));                                     
                                     $arrayData = array( 'idEstadoPlan'        => ID_ESTADO_PRE_LIQUIDADO,
                                                         'fechaPreLiquidacion' => $this->fechaActual(),
                                                         'id_usuario_preliquidacion' => $this->session->userdata('idPersonaSession'));
                                     $data = $this->m_integracion_siom->updatePlanObra($infSiom['itemplan'], $arrayData);
                                 }else{
                                     log_message('error', 'SIOM:No cuenta con requisitos para PRELIQUIDAR ITEMPLAN.'.print_r($listEstacionesOS, true));
                                 }
                         
                             }else{
                                 log_message('error', 'VALIDANDO:No se encuentra en obra');
                             }
                         }else if($estado  ==  'APROBADA'){//pasamos a terminar
                             if($infSiom['idEstadoPlan']    ==  ID_ESTADO_PRE_LIQUIDADO || $infSiom['idEstadoPlan']    ==  ID_ESTADO_EN_VALIDACION){//SI ESTA EN PRE LIQUIDADO PODREMOS TERMINAR LA OBRA.
                                 #EVALUACION PARA PASE A TERMINADO
                                 $numEstaTotal = 0;
                                 $numEstaOK = 0;
                                 $esta_ancla_apro = array();
                                 foreach ($listEstacionesOS as $row){
                                     if($row->num_os > 0 && $row->aprobada > 0){//si tiene os y al menos una esta aprobada
                                         $numValAproRech = $row->aprobada + $row->rechazada;
                                         if($row->num_os == $numValAproRech){
                                             $numEstaOK = $numEstaOK + 1;                                            
                                         }                                         
                                     }
                                     $numEstaTotal = $numEstaTotal + 1;    

                                     if($row->num_os != $row->rechazada){//hay almenos una os y no esta rechazada y es estacion ancla
                                         if($row->idEstacion == 2 || $row->idEstacion = 5){
                                             array_push($esta_ancla_apro, $row->idEstacion);//la ponemos en lista de estaciones anclas
                                         }
                                     } 
                                 }
                                 if(($numEstaTotal    ==  $numEstaOK) && $numEstaTotal > 0){//si todas las estaciones cumplen podemos preliquidar.
                                     //preliquidar plan
                                     $terminarItemplan = false;//por defecto liquidar
                                     //preliquidar plan
                                     $listEstaDJ = $this->m_integracion_siom->getDJValidadasByItemplan($infSiom['itemplan'], $esta_ancla_apro);
                                     if($listEstaDJ){//TIENE STACIONES ANCLAS VALIDEMOS SI CUENTA CON LA DJ VALIDADA
                                         foreach($listEstaDJ as $row){// VALIDA QUE TODAS SUS ESTACIONES ANCLAS QUE PASAN POR SIOM TENGAN DJ VALIDADA.
                                             if($row->id_ficha_tecnica !=  null){
                                                 $terminarItemplan = true;
                                             }else{
                                                 $terminarItemplan = false;
                                                 break;
                                             }
                                         }
                                     }else{//preguntar si ,las que no tienen estaciones anclas
                                         log_message('error', 'NO TIENE ESTACIONES ANCLAS');
                                         $terminarItemplan = true;//por defecto liquidar
                                     }
                                     
                                     if($terminarItemplan){
                                         log_message('error', 'SIOM:TERMINAR ITEMPLAN'.print_r($listEstacionesOS, true));
                                         $arrayData = array( 'idEstadoPlan'        => ID_ESTADO_TERMINADO,
                                                             'fechaEjecucion' => $this->fechaActual(),
                                                            'fecha_upd' =>  $this->fechaActual(),
                                                            'usu_upd' =>  'SIOM WEB',
                                                            'descripcion' => 'FROM SIOM');
                                         $data = $this->m_integracion_siom->updatePlanObra($infSiom['itemplan'], $arrayData);
                                     }else{
                                         log_message('error', 'NO CUENTA CON DJ VALIDADAS'.print_r($listEstacionesOS, true));
                                     }
                                 }else{
                                     log_message('error', 'SIOM:No cuenta con requisitos para terminar ITEMPLAN.'.print_r($listEstacionesOS, true));
                                 }
                             }else{
                                 log_message('error', 'APROBADA:no se encuentra en pre liquidado');
                             }
                         }else if($estado  ==  'RECHAZADA'){//pasamos a retornar a en obra.
                             if($infSiom['idEstadoPlan']    ==  ID_ESTADO_PRE_LIQUIDADO){//SI ESTA EN PRE LIQUIDADO PODREMOS RETORNAR A EN OBRA.
                                 #EVALUACION PARA RETORNO A EN OBRA
                                 $numEstaTotal = 0;
                                 $numEstaOK = 0;
                                 foreach ($listEstacionesOS as $row){
                                     if($row->num_os > 0 && $row->rechazada > 0){//si tiene os y al menos una esta aprobada
                                         $numValAproRech = $row->rechazada;
                                         if($row->num_os == $numValAproRech){
                                             $numEstaOK = $numEstaOK + 1;
                                         }
                                     }
                                     $numEstaTotal = $numEstaTotal + 1;
                                 }
                                 if(($numEstaTotal    ==  $numEstaOK) && $numEstaTotal > 0){//si todas las estaciones cumplen podemos preliquidar.
                                     //preliquidar plan
                                     log_message('error', 'SIOM:RETORNAR A EN OBRA'.print_r($listEstacionesOS, true));
                                     $arrayData = array( 'idEstadoPlan'              => ID_ESTADO_PLAN_EN_OBRA,
                                                         'fechaPreLiquidacion'       => null,
                                                         'id_usuario_preliquidacion' => null
                                     );
                                     $data = $this->m_integracion_siom->updatePlanObra($infSiom['itemplan'], $arrayData);
                                 }else{
                                     log_message('error', 'SIOM:No cuenta con requisitos para RETORNAR A EN OBRA.'.print_r($listEstacionesOS, true));
                                 }
                             }else{
                                 log_message('error', 'RECHAZADA: no se encuentra en pre liquidado');
                             }
                         }
                    }else{
                        log_message('error', 'no cuenta con estacion ancla configurada.');
                        // no cuenta con estacion ancla configurada.
                    }
                }
            }
            log_message('error', 'saliendo...');
            $data['error']  = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        return json_encode(array_map('utf8_encode', $data));
    }
    
    function testFromHere(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try{
           // $this->updateAvanceItemPlanFromSiom(4564, 'APROBADA');
           log_message('error', 'test from here!');
        }catch(Exception $e){
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
     /**nuevo para envio de liquidacion de estacion a sisego web **/
    
    function enviarTramaToSisegoFromSiom($itemPlan, $indicador, $url, $idEstacion){
        
        $dataSend = ['itemplan' => $itemPlan,
                    'fecha'    => $this->fechaActual(),
                    'sisego'   => $indicador];
  
        $response = $this->m_utils->sendDataToURL($url, $dataSend);       
        if($response->error == EXIT_SUCCESS){
            $datosInsert = array(
                "origen"           => 'INTEGRACION SIOM - LIQUIDACION OBRA',
                "itemplan"         => $itemPlan,
                "sisego"           => $indicador,
                "fecha_registro"   => $this->fechaActual(),
                "motivo_error"     => 'TRAMA COMPLETADA',
                "descripcion"      => 'OPERACION REALIZADA CON EXITO',
                "estado"           => 1,
                "idEstacion"       => $idEstacion,
				"flg_tipo"         => 4
            );
            $this->m_integracion_siom->saveLogSigoplusFromSIOM($datosInsert);
        }else{
            $datosInsert = array(
                "origen"           => 'INTEGRACION SIOM - LIQUIDACION OBRA',
                "itemplan"         => $itemPlan,
                "sisego"           => $indicador,
                "fecha_registro"   => $this->fechaActual(),
                "motivo_error"     => 'FALLA EN LA RESPUESTA DEL HOSTING',
                "descripcion"      => 'OPERACION NO COMPLETADA ERROR EN EL SERVIDOR DEL CLIENTE:'. strtoupper($response->mensaje),
                "estado"           => 2,
                "idEstacion"       => $idEstacion,
				"flg_tipo"         => 4
            );
            $this->m_integracion_siom->saveLogSigoplusFromSIOM($datosInsert);
        }        
    }
    
    function liquidarPoMatByItemplanEstacion($itemplan, $idEstacion){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try{
            $listaPoMat = $this->m_integracion_siom->getMatPoListByitemplanEstacion($itemplan, $idEstacion);
            $updateDataPo = array();
            $insertDataLog = array();
            foreach($listaPoMat as $row){
                $dataUp = array('estado_po' =>  PO_LIQUIDADO,
                                'codigo_po' =>  $row->codigo_po
                );
                array_push($updateDataPo, $dataUp);
                $dataIn = array('codigo_po' =>  $row->codigo_po,
                                'itemplan' =>  $row->itemplan,
                                'idUsuario' =>  ID_USUARIO_SIOM_WEB,
                                'fecha_registro' => $this->fechaActual(),
                                'idPoestado'    =>  PO_LIQUIDADO,
                                'controlador'   => 'SIOM WEB'
                );
                array_push($insertDataLog, $dataIn);
                
            }            
            $data = $this->m_integracion_siom->liquidarPoMateriales($updateDataPo, $insertDataLog);
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
	
	 public function testUpdateOSFromSIOMTREE()
    {
        
        header("Access-Control-Allow-Origin: *");        
        $output['codigo'] = EXIT_ERROR;
        $output['mensaje'] = 'No se Cerro La OS';
        try {           
           $method = $_SERVER['REQUEST_METHOD'];
           if($method=='PUT' || $method=='POST'){               
               $inputJSON = file_get_contents('php://input');
               $input = json_decode( $inputJSON, TRUE );
               log_message('error', 'errorrr:'.print_r($input,true));
               if(!isset($input['orseId'])){
                   throw new Exception('No se detecto codigo de OS en la trama de envio.');
               }
               $output['codigo'] = EXIT_SUCCESS;
               $output['mensaje'] = "Se Actualizo el la OS Nro. ".$input['orseId'];
           }else{
               $output = $this->getMsjTypeMethod($method, 'cerrarOs');
           }           
        } catch (Exception $e) {
            $output['mensaje'] = 'No se Cerro La OS, '.$e->getMessage();
        }
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($output));
    }
}