<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class C_atencion_solicitud_oc_masivo_opex extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=UTF8');
        $this->load->model('mf_certificacion/m_atencion_solicitud_oc_masivo_opex');
        $this->load->model('mf_pre_diseno/m_bandeja_adjudicacion');
        $this->load->model('mf_servicios/M_integracion_sirope');
		$this->load->model('mf_pqt_terminado/m_pqt_terminado');
		$this->load->model('mf_control_presupuestal/m_pqt_reg_mat_x_esta_pqt');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->library('excel');
        $this->load->helper('url');
    }

    public function index() {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {
            //$solicitud = (isset($_GET['sol']) ? $_GET['sol'] : '');
            //PONER VALIDACIONES
            //$data['solicitud'] = $solicitud;
            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $data['tablaSolOC']    = $this->basicHtml();
            $permisos = $this->session->userdata('permisosArbolTransporte');
            $result = $this->lib_utils->getHTMLPermisos($permisos, 308, 328, ID_MODULO_ADMINISTRATIVO);
            $data['opciones'] = utf8_encode($result['html']);
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_certificacion/v_atencion_solicitud_oc_masivo_opex', $data);
            } else {
                redirect('login', 'refresh');
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function getExcelMasivoSolOpex() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
          
            ini_set('max_execution_time', 10000);
            ini_set('memory_limit', '2048M');

            $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
            $cacheSettings = array('memoryCacheSize ' => '5000MB', 'cacheTime' => '1000');
            PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

            $this->excel->setActiveSheetIndex(0);
            $this->excel->getActiveSheet()->setTitle('ValeDatos');
            $contador = 1;
            $titulosColumnas = array('CODIGO SOLICITUD', 'CESTA', 'ORDEN COMPRA', 'COSTO SAP');

            $this->excel->setActiveSheetIndex(0);

            // Se agregan los titulos del reporte
            $this->excel->setActiveSheetIndex(0)
                    ->setCellValue('A1', utf8_encode($titulosColumnas[0]))
                    ->setCellValue('B1', utf8_encode($titulosColumnas[1]))
                    ->setCellValue('C1', utf8_encode($titulosColumnas[2]))
                    ->setCellValue('D1', utf8_encode($titulosColumnas[3]));

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
            $archivo = "solicitud_oc.xls";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $archivo . '"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
            //Hacemos una salida al navegador con el archivo Excel.

            $nombreFile = 'download/detalleMatPO/formato_carga_sol_' . date("YmdHis") . '.xls';
            $objWriter->save($nombreFile);
            $data['rutaExcel'] = $nombreFile;
            $data['error'] = EXIT_SUCCESS;
            
        } catch (Exception $e) {
            $data['msj'] = 'Error interno, al crear archivo partidas MO';
        }
        // return $data;
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function procesarSolOpexMasivo() {
        $data ['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $uploaddir = 'uploads/oc_masivo/'; //ruta final del file
            $uploadfile = $uploaddir . basename($_FILES['file']['name']);
            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
				_log("ENTROO1111");
                $objPHPExcel = PHPExcel_IOFactory::load($uploadfile);

                $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
                $row_dimension = $objPHPExcel->getActiveSheet()->getHighestRowAndColumn();

                $info_2 = $this->makeHTMLBodyTable($row_dimension, $objPHPExcel);
				_log("ENTROO222");
                $data['tablaData'] = $info_2['html'];
                $data['jsonDataFIle'] = json_encode($info_2['array_full']);
                $data['error'] = EXIT_SUCCESS;
            } else {
                throw new Exception('Hubo un problema con la carga del archivo al servidor, comuniquese con el administrador.');
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function basicHtml(){
        $html = '<table style="font-size: 10px" id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr role="row">
                        <th colspan="1">CODIGO SOLICITUD</th>
                        <th colspan="1">CESTA</th>
                        <th colspan="1">ORDEN DE COMPRA</th>
                        <th colspan="1">COSTO SAP</th>
                        <th colspan="1">OBSERVACION</th>
                        </tr>
                    </thead>
                    <tbody id="contBodyTable">
                     
                    </tbody>
                </table>';
            return $html;
    }
    
    public function makeHTMLBodyTable($row_dimension, $objPHPExcel) {
        $data['html'] = '';
        $data['array_full'] = '';
        $html = '';
        $indice = 0;
        $array_not_found = array();
        $array_full = array();
		$arrayOC = array();
        $html .= '<table style="font-size: 10px" id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr role="row">
                        <th colspan="1">CODIGO SOLICITUD</th>
                        <th colspan="1">CESTA</th>
                        <th colspan="1">ORDEN DE COMPRA</th>
                        <th colspan="1">COSTO SAP</th>
                        <th colspan="1">OBSERVACION</th>
                        </tr>
                    </thead>
                <tbody id="contBodyTable">';
        for ($i = 1; $i <= $row_dimension['row']; $i++) {//COMIENZA DESDE LA FILA 1
            $A = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, $i, true)->getValue();
            $B = null;
            $C = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2, $i, true)->getValue();
            $D = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(3, $i, true)->getValue();
            $total = 0;
            if ($C != '' && $D != '' && $A != 'CODIGO SOLICITUD') {
                $infoSol = $this->m_atencion_solicitud_oc_masivo_opex->getInfoSolicitudOCCreaByCodigo(trim($A), 1);
                if($infoSol != null && $infoSol['itemplan'] != null){
					//if(!in_array($C,$arrayOC)){
						if($infoSol['estado']==1 && $infoSol['cant'] == 1){
							$hasOcActiva = $this->m_atencion_solicitud_oc_masivo_opex->existeItemplanConOC($C);
							// if($hasOcActiva == 0){
								$html .= '<tr id="tr' . $indice . '" >
											<th style="color:black">' . $A . '</th>
											<th style="color:black">' . $B . '</th>
											<th style="color:black">' . $C . '</th>
											<th style="color:black">' . $D . '</th>
											<th style="width: 5px;">OK</th>
										</tr>';
								$indice++;
			
								$pre_array = array($A, $B, $C, $D, $infoSol['itemplan'], $infoSol['idEstadoPlan'], $infoSol['idSubProyecto'], $infoSol['idTipoPlanta'], $infoSol['paquetizado_fg']);
								array_push($array_full, $pre_array);
							// }else{
								// $html .= '<tr style="background-color: #ffcece;" id="tr' . $indice . '" >
											// <th style="color:black">' . $A . '</th>
											// <th style="color:black">' . $B . '</th>
											// <th style="color:black">' . $C . '</th>
											// <th style="color:black">' . $D . '</th>
											// <th style="width: 5px;">LA OC YA SE ENCUENTRA EN UN ITEMPLAN</th>
										// </tr>';
								// $indice++;
							// }
							
							
						}else{//IVALIDO ATENDIDA O CANCELADA
							if($infoSol['cant']==0 || $infoSol['cant']==null){
								$msj = 'SOLICITUD SIN ITMEPLAN ASOCIADO';
							}else if($infoSol['cant'] > 1){
								$msj = 'SOLICITUD CON MAS DE 1 ITEMPLAN ASOCIADO';
							}else if($infoSol['estado']==2){
								$msj = 'SOLICITUD ATENDIDA';
							}else if($infoSol['estado']==3){
								$msj = 'SOLICITUD CANCELADA';
							}
		
							$html .= '<tr style="background-color: #ffcece;" id="tr' . $indice . '" >
										<th style="color:black">' . $A . '</th>
										<th style="color:black">' . $B . '</th>
										<th style="color:black">' . $C . '</th>
										<th style="color:black">' . $D . '</th>
										<th style="width: 5px;">'.$msj.'</th>
									</tr>';
							$indice++;
						}
						$arrayOC[]= $C;
					/*}else{
						$html .= '<tr style="background-color: #ffcece;" id="tr' . $indice . '" >
									<th style="color:black">' . $A . '</th>
									<th style="color:black">' . $B . '</th>
									<th style="color:black">' . $C . '</th>
									<th style="color:black">' . $D . '</th>
									<th style="width: 5px;">ORDEN DE COMPRA REPETIDA!!</th>
								  </tr>';
						$indice++;

					}  
					*/
                }else{//INVALIDO SOLICITUD NO EXISTE
                    $html .= '<tr style="background-color: #ffcece;" id="tr' . $indice . '" >
                            	<th style="color:black">' . $A . '</th>
                            	<th style="color:black">' . $B . '</th>
                            	<th style="color:black">' . $C . '</th>
    							<th style="color:black">' . $D . '</th>
    						    <th style="width: 5px;">SOLICITUD NO RECONOCIDA</th>
                        	</tr>';
                    $indice++;
                }
            } else if($A != 'CODIGO SOLICITUD'){
				$msj = null;
				if($A == null || $A == ''){
					$msj = 'DEBE INGRESAR CODIGO';
				}else if($C == null || $C == ''){
					$msj = 'DEBE INGRESAR LA OC';
				}else if($D == null || $D == ''){
					$msj = 'DEBE INGRESAR EL COSTO SAP';
				}

				$html .= '<tr style="background-color: #ffcece;" id="tr' . $indice . '" >
							<th style="color:black">' . $A . '</th>
							<th style="color:black">' . $B . '</th>
							<th style="color:black">' . $C . '</th>
							<th style="color:black">' . $D . '</th>
							<th style="width: 5px;">'.$msj.'</th>
						</tr>';
				$indice++;
			}
        }
        $html .= '</tbody>
                </table>';    
        $data['html'] = $html;
        $data['array_full'] = $array_full;
        return $data;
    }

    public function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone', 'America/Lima');
        setlocale(LC_TIME, "es_ES", "esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }

    function generarExcelErrores() {
        $listNF = $this->session->userdata('sirope_NF');
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('test worksheet');
        //set cell A1 content with some text
        $i = 1;
        foreach ($listNF as $row) {
            $this->excel->getActiveSheet()->setCellValue('A' . $i, $row[0]);
            $this->excel->getActiveSheet()->setCellValue('B' . $i, $row[1]);
            $this->excel->getActiveSheet()->setCellValue('C' . $i, $row[2]);
            $this->excel->getActiveSheet()->setCellValue('D' . $i, $row[3]);
            $this->excel->getActiveSheet()->setCellValue('E' . $i, $row[4]);
            $this->excel->getActiveSheet()->setCellValue('F' . $i, $row[5]);
            $this->excel->getActiveSheet()->setCellValue('G' . $i, $row[6]);
            $this->excel->getActiveSheet()->setCellValue('H' . $i, $row[7]);
            $this->excel->getActiveSheet()->setCellValue('I' . $i, $row[8]);
            $this->excel->getActiveSheet()->setCellValue('J' . $i, $row[9]);
            $this->excel->getActiveSheet()->setCellValue('K' . $i, $row[10]);
            $this->excel->getActiveSheet()->setCellValue('L' . $i, $row[11]);
            $i++;
        }
        $filename = 'just_some_random_name.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
    }

	 public function atencionMasivoSolicitudOcOpex() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;
            if ($idUsuario != null) {               
                    $jsonDataFile = $this->input->post('jsonDataFile');
                    $arrayFile = json_decode($jsonDataFile);                   
                    if ($arrayFile != null) {
                        $itemplanList       = array();//itemplans a atender    
                        $solicitudesList    = array();//solicitudes a atender
                        $pre_disenosList    = array();
                        $goSiropeList       = array();
                        $itemplanListPoPqt  = array();
                        $ptrsPlantaInterna  = array();
                        $listaItemEdifEval	= array();
						$itemplanNuevoFlujoList = array();
                        foreach ($arrayFile as $datos) {
                            $be_adjudicacion = false;
                            if ($datos != null) {
                                
                                $codigo_solicitud   = $datos[0];
                                $cesta              = $datos[1];
                                $orden_compra       = $datos[2];
                                $costo_sap          = $datos[3];
                                $itemplan           = $datos[4];
                                $idEstadoPlan       = $datos[5];                           
                                $idSubProyecto      = $datos[6];							
                                // $idTipoPlanta       = $datos[7];
                                $paquetizado_fg     = $datos[8];
								$idTipoPlanta = $this->m_utils->getTipoPlantaByItemplan($itemplan);
								$getInfoSubProyecto = $this->m_utils->getInfoSubProyectoByIdSubProyecto($idSubProyecto);	
								if($idTipoPlanta == null || $idTipoPlanta == '') {
									throw new Exception('no cuenta con un tipo de planta, verificar.');
								}
                                
								$itemplanData = array(  'itemplan'      => $itemplan,
								                        'orden_compra'  => $orden_compra,
								                        'posicion'      => 1,
								                        'cesta'         => $cesta,
								                        'estado_sol_oc' => 'ATENDIDO',								                        
								                        'costo_sap'     => $costo_sap,
								                        'solicitud_oc'  => $codigo_solicitud,
														'solicitud_oc_anula_pos'		=>	null,
														'costo_unitario_mo_anula_pos'	=>	null,
														'estado_oc_anula_pos'			=>	null,
														'solicitud_oc_dev' => null,
														'estado_oc_dev' => null
								                    );
								/*if(in_array($idSubProyecto, array(663,665))){//CZAVALA 5.03.2021 NUEVO EDIFICIO
								        $itemplanData['idEstadoPlan']   = ID_ESTADO_PRE_REGISTRO;
										array_push($listaItemEdifEval, $itemplan);
								    }*/
								if($getInfoSubProyecto['flg_sin_diseno'] == 1 && ($idEstadoPlan == ID_ESTADO_PRE_REGISTRO || $idEstadoPlan == ID_ESTADO_DISENIO)) {
									$itemplanData['idEstadoPlan']   = ID_ESTADO_PLAN_EN_OBRA;
									$itemplanData['usu_upd']        = $idUsuario;
								    $itemplanData['fecha_upd']      = $this->fechaActual();
								    $itemplanData['descripcion']    = 'ORDEN DE COMPRA ATENDIDA - SIN DISENO';
								} else {
									if($idEstadoPlan == ID_ESTADO_PRE_REGISTRO){//ACTUALIZAMOS ESTADOPLAN
										$itemplanData['usu_upd']        = $idUsuario;
										$itemplanData['fecha_upd']      = $this->fechaActual();
										$itemplanData['descripcion']    = 'ORDEN DE COMPRA ATENDIDA';
										if(in_array($idSubProyecto, array(279,283,554,579,582,702,714,718))){//subrpoyectos rutas y balanceo to en obra
											$itemplanData['idEstadoPlan']   = ID_ESTADO_PLAN_EN_OBRA;
										}else if(in_array($idSubProyecto, array(693))){//nuevo flujo
											$itemplanData['idEstadoPlan']   = 20;
											array_push($itemplanListPoPqt, $itemplan);
											$itemplanNuevoFlujoList[] = $datos[0];
											$be_adjudicacion = true;
										}else{//to diseno
											$itemplanData['idEstadoPlan']   = ID_ESTADO_DISENIO;
											$itemplanData['fec_ult_adju_diseno'] = $this->fechaActual();
											$be_adjudicacion = true;
										}
									}if($idEstadoPlan == ESTADO_PLAN_DISENO_EJECUTADO){//SOLO SI ES DISENO EJECUTADO 2021-04-30 CZAVALA
										$idProyecto = $this->m_utils->getIdProyectoByItemplan($itemplan);									
										if ($idProyecto == 4) {//OBRA_PUBLICA
											 $countNoRequiereLicencia = $this->m_utils->getCountRequiereLicencia($itemplan);										
											 $itemplanData['usu_upd']        = $idUsuario;
											 $itemplanData['fecha_upd']      = $this->fechaActual();
											 if($countNoRequiereLicencia == 0) {
												$itemplanData['descripcion']  = 'VALIDACION OC A LICENCIA';
												$itemplanData['idEstadoPlan'] = 19;
											 } else {
												$itemplanData['descripcion']  = 'VALIDACION OC A APROBACION';
												$itemplanData['idEstadoPlan'] = 20; 
											 }
										}
									}else if($idEstadoPlan == ID_ESTADO_DISENIO || $idEstadoPlan == ESTADO_PLAN_PDT_OC){
										// if($idTipoPlanta == 2) {//PLANTA INTERNA
											$itemplanData['usu_upd']        = $idUsuario;
											$itemplanData['fecha_upd']      = $this->fechaActual();
											$itemplanData['descripcion']    = 'ORDEN DE COMPRA ATENDIDA';
											$itemplanData['idEstadoPlan']   = ID_ESTADO_PLAN_EN_OBRA;
											
											$ptrPI = array(
															"itemplan"            => $itemplan,
															"estado"              => ESTADO_02_TEXTO,
															"ultimo_estado"       => ESTADO_01_TEXTO,
															"fecha_ultimo_estado" => $this->fechaActual(),
															"usua_ultimo_estado"  => $this->session->userdata('userSession'),
															"fecha_aprob"         => $this->fechaActual(),
															"usua_aprob"          => $this->session->userdata('userSession'),
															"rangoPtr"            => 2,
															"flg_rechazado"       => FLG_APROBADO
														);
											
											array_push($ptrsPlantaInterna, $ptrPI);
											
											 /****************nuevo para vertical 11.01.2021****************/								     
											// if($idSubProyecto ==  653 || $idSubProyecto ==  658){//SISEGOS PIN O JUMPEO EDIFICIOS FTTH
											// 	$nuevafecha2 = strtotime('+7 day', strtotime($this->fechaActual()));
											// 	$idFechaPreAtencionFo2 = date('Y-m-j', $nuevafecha2);
											// 	$this->M_integracion_sirope->execWs($itemplan, $itemplan . 'FO', $this->fechaActual(), $idFechaPreAtencionFo2,'PROJECT');
											// }
											/**************************************************************/
										// }
									}
								}
								

								array_push($itemplanList, $itemplanData);
								
							    if($be_adjudicacion){//solo si acaba de pasar de pre registro a diseno adjudico y registro ot
							        $has_ancla  = false;
							        $has_fo     = false;
							        $has_coax   = false;
							        $infoAnclasByItemplan = $this->m_atencion_solicitud_oc_masivo_opex->getEstacionesAnclasByItemplan($itemplan);
							        if($infoAnclasByItemplan['coaxial']    >   0){
							            $has_coax  = true;
							            $has_ancla = true;
							        }
							        if($infoAnclasByItemplan['fo']    >   0){
							            $has_fo    = true;
							            $has_ancla = true;
							        }
							    
							        if($has_ancla){//si tiene anclas obtenemos sus idas de adjudicacion
							            
							            $dias = $this->m_atencion_solicitud_oc_masivo_opex->getDiaAdjudicacionBySubProyecto($idSubProyecto);
							            if($dias == null){//si no tiene por defecto 4
							                $dias = 4;
							            }
							            $curHour = date('H');
							            if ($curHour >= 13) {//13:00 PM
							                $dias = ($dias + 1);
							            }
							            $nuevafecha = strtotime('+' . $dias . ' day', strtotime($this->fechaActual()));
							            $idFechaPreAtencion = date('yy-m-d', $nuevafecha);
							            
							            if($has_fo){
								            $infoAdjudicacion = array ( 'itemPlan'                => $itemplan,
                        								                'idEstacion'              => ID_ESTACION_FO,
                        								                'fecha_prevista_atencion' => $idFechaPreAtencion,
                        								                'fecha_adjudicacion'	  => $this->fechaActual(),
                        								                'estado'                  => ESTADO_PLAN_DISENO,
                        								                'usuario_adjudicacion'    => 'ORDEN COMPRA ATENDIDA'
								                                        );				
								            array_push($pre_disenosList, $infoAdjudicacion);
								            
								            $siropeBod['itemplan']   = $itemplan;
								            $siropeBod['codigoOT']   = $itemplan.'FO';
								            $siropeBod['fecActual']  = $this->fechaActual();
								            $siropeBod['fechaPrev']  = $idFechaPreAtencion;
								            $siropeBod['idSubProyecto']	=	$idSubProyecto;
								            array_push($goSiropeList, $siropeBod);//OBRAS TO GO SIROPE PORQUE TIENEN FO
							            }
							            
							            if($has_coax){
							                $infoAdjudicacion = array ( 'itemPlan'                => $itemplan,
                    								                    'idEstacion'              => ID_ESTACION_COAXIAL,
                    								                    'fecha_prevista_atencion' => $idFechaPreAtencion,
                    								                    'fecha_adjudicacion'	  => $this->fechaActual(),
                    								                    'estado'                  => ESTADO_PLAN_DISENO,
                    								                    'usuario_adjudicacion'    => 'ORDEN COMPRA ATENDIDA'
                    								                    );
							                array_push($pre_disenosList, $infoAdjudicacion);
							            }
							            
							            if($paquetizado_fg   ==  2){//es paquetizada
							                if($has_ancla){//tiene estacion ancla 
							                    array_push($itemplanListPoPqt, $itemplan);//almacenamos el itemplan para postererormente generarle su po							                    							       
							                }
							            }
							        }
							    }								
								  /****************nuevo para vertical 11.01.2021****************/
								    							    
								    if($getInfoSubProyecto['idProyecto']  ==  ID_PROYECTO_CRECIMIENTO_VERTICAL){
										$nuevafecha2 = strtotime('+7 day', strtotime($this->fechaActual()));
										$idFechaPreAtencionFo2 = date('Y-m-j', $nuevafecha2);
								        $this->M_integracion_sirope->execWs($datos[4], $datos[4] . 'AC', $this->fechaActual(), $idFechaPreAtencionFo2,'UPDATE_DATABASE');
								    }
								/*******************************************************************/
								$dataSolicitud = array();
								$dataSolicitud['codigo_solicitud']  = $codigo_solicitud;
								$dataSolicitud['usuario_valida']    = $idUsuario;
								$dataSolicitud['fecha_valida']      = $this->fechaActual();
								$dataSolicitud['estado']            = 2;
								$dataSolicitud['cesta']             = $cesta;
								$dataSolicitud['orden_compra']      = $orden_compra;
								$dataSolicitud['costo_sap']         = $costo_sap;								
								array_push($solicitudesList, $dataSolicitud);
                            }
                        }
                      
                        $data = $this->m_atencion_solicitud_oc_masivo_opex->masiveUpdateAtencionCreateSolOC($itemplanList, $solicitudesList, $pre_disenosList, $ptrsPlantaInterna);                       
                        
                        if($data['error']   ==  EXIT_SUCCESS){//SI GENERO LAS SOLICITUDES

							if(count($itemplanNuevoFlujoList) > 0) {
								foreach($itemplanNuevoFlujoList as $item){
									$this->M_integracion_sirope->execWs($item, $item.'FO',date('Y-m-d'),date('Y-m-d',strtotime('+' . 7 . ' day', strtotime(date('Y-m-d')))),'PROJECT');
								}
							}
							
                            foreach ($itemplanListPoPqt as $item){//por cada itemplan generarle su po pqt
								$estacionesAnclas = $this->m_pqt_terminado->getEstacionesAnclasByItemplan($item);
								foreach ($estacionesAnclas as $estacion){
									$hasPoPqtACtive = $this->m_utils->hasPoPqtActive($item, $estacion->idEstacion);
									if($hasPoPqtACtive == 0){
										$this->savePoMo($item, $estacion->idEstacion);										
									}
								}								
                            }
                            foreach ($goSiropeList as $sirope){//por cada itemplan generarle su po pqt
                                //log_message('error', 'SEND SIROPE:'.$sirope['itemplan'].'-'.$sirope['codigoOT'].'-'.$sirope['fecActual'].'-'.$sirope['fechaPrev']);//generate
                                $this->M_integracion_sirope->execWs($sirope['itemplan'], $sirope['itemplan'] . 'FO', $this->fechaActual(), $sirope['fechaPrev'],'PROJECT');
								if($sirope['idSubProyecto']==51){//PARTICIONES SATURACION GENERA OT COAXIAL CZAVALA 01.10.2020
									$this->M_integracion_sirope->execWs($sirope['itemplan'], $sirope['itemplan'] . 'COAX', $this->fechaActual(), $sirope['fechaPrev'],'PROJECT');
								}
                            }
							

                        }
                    } else {
                        throw new Exception('No se pudo procesar el archivo, refresque la pagina y vuelva a intentarlo.');
                    }
            } else {
                throw new Exception('Su sesion expiro, porfavor vuelva a logearse.');
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
	
	function getArrayPartidasToPO($idEstacion, $idSubProyecto, $idEmpresaColab, $isLima, $itemplan){
        $partidasOutPut = array();
    
        /*Obtenemos paquetizados*/
        $listaPartidasPqt = $this->m_pqt_terminado->getPartidasPaquetizadasByItemEccJefaTuraEstacion($idEstacion, $idSubProyecto, $idEmpresaColab, $isLima, $itemplan);
        $arrayActividades = array();
        foreach ($listaPartidasPqt as $row){
            if($row->id_tipo_partida == 1 ||$row->id_tipo_partida == 5){#DISEÑO FO y COAXIAL      
				if(!in_array($row->idActividad, $arrayActividades)){
					$dataCMO['idActividad']      = $row->idActividad;
					$dataCMO['baremo']           = $row->baremo;
					$dataCMO['costo']            = $row->costo;
					$dataCMO['cantidad_inicial'] = $row->cantFactorPlanificado;
					$dataCMO['monto_inicial']    = $row->total;
					$dataCMO['cantidad_final']   = $row->cantFactorPlanificado;
					$dataCMO['monto_final']      = $row->total;
					array_push($arrayActividades, $row->idActividad);//metemos idActividad
					array_push($partidasOutPut, $dataCMO);              
                }
            }else if($row->id_tipo_partida == 2 ||$row->id_tipo_partida == 6){#LICENCIA FO y COAXIAL               
				if(!in_array($row->idActividad, $arrayActividades)){
					$dataCMO['idActividad']      = $row->idActividad;
					$dataCMO['baremo']           = $row->baremo;
					$dataCMO['costo']            = $row->costo;
					$dataCMO['cantidad_inicial'] = $row->cantFactorPlanificado;
					$dataCMO['monto_inicial']    = $row->total;
					$dataCMO['cantidad_final']   = $row->cantFactorPlanificado;
					$dataCMO['monto_final']      = $row->total;
					array_push($arrayActividades, $row->idActividad);//metemos idActividad
					array_push($partidasOutPut, $dataCMO);                
                }
            }else if($row->id_tipo_partida == 3 ||$row->id_tipo_partida == 4 || $row->id_tipo_partida == 7 ||$row->id_tipo_partida == 8){#TENDIDO Y EMPALMADOR FO Y COAXIAL
          
				if(!in_array($row->idActividad, $arrayActividades)){
					$dataCMO['idActividad']      = $row->idActividad;
					$dataCMO['baremo']           = $row->baremo;
					$dataCMO['costo']            = $row->costo;
					$dataCMO['cantidad_inicial'] = $row->cantFactorPlanificado;
					$dataCMO['monto_inicial']    = $row->total;
					$dataCMO['cantidad_final']   = $row->cantFactorPlanificado;
					$dataCMO['monto_final']      = $row->total;
					array_push($arrayActividades, $row->idActividad);//metemos idActividad
					array_push($partidasOutPut, $dataCMO);
                    
                }
            } else if($row->id_tipo_partida == 9){#FUENTE Y ENERGIA             
				if(!in_array($row->idActividad, $arrayActividades)){
					$dataCMO['idActividad']      = $row->idActividad;
					$dataCMO['baremo']           = $row->baremo;
					$dataCMO['costo']            = $row->costo;
					$dataCMO['cantidad_inicial'] = $row->cantFactorPlanificado;
					$dataCMO['monto_inicial']    = $row->total;
					$dataCMO['cantidad_final']   = $row->cantFactorPlanificado;
					$dataCMO['monto_final']      = $row->total;
					array_push($arrayActividades, $row->idActividad);//metemos idActividad
					array_push($partidasOutPut, $dataCMO);
				}
            }else if($row->id_tipo_partida == 10    ||  $row->id_tipo_partida == 11){#INTEGRAL E INTEGRAL OVERLAY           
				if(!in_array($row->idActividad, $arrayActividades)){
					$dataCMO['idActividad']      = $row->idActividad;
					$dataCMO['baremo']           = $row->baremo;
					$dataCMO['costo']            = $row->costo;
					$dataCMO['cantidad_inicial'] = $row->cantFactorPlanificado;
					$dataCMO['monto_inicial']    = $row->total;
					$dataCMO['cantidad_final']   = $row->cantFactorPlanificado;
					$dataCMO['monto_final']      = $row->total;
					array_push($arrayActividades, $row->idActividad);//metemos idActividad
					array_push($partidasOutPut, $dataCMO);
				}
            }
        }       
        return $partidasOutPut;
    }
	
	public function savePoMo($itemplan_in, $idestacion_in){
		$data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        $global_codigo_po = null;
        try{
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;
            if($idUsuario   !=     null){
                 
                $itemplan       = $itemplan_in;
                $idEstacion     = $idestacion_in;
    
                $arrayPartidasInsert = array();
    
                $infoItm = $this->m_pqt_terminado->getInfoBasicToGeneratePartidasByItemplan($itemplan);
                if($infoItm==null){
                    throw new Exception('Excepcion detectada, comuniquese con soporte.');
                }
                $listaPartidasToCreate = $this->getArrayPartidasToPO($idEstacion, $infoItm['idSubProyecto'], $infoItm['idEmpresaColab'], $infoItm['isLima'], $itemplan);
    
                if($listaPartidasToCreate!=null){
                     
                    $codigoPO = $this->m_utils->getCodigoPO($itemplan);
                    if ($codigoPO == null) {
                        throw new Exception('Hubo un error al generar el codigo PO ');
                    }
                    $global_codigo_po = $codigoPO;
                    $costoTotalPOMO = 0;
                    foreach($listaPartidasToCreate as $datos){
                        $partidaInfo = array();
                        $partidaInfo = $datos;
                        $partidaInfo['codigo_po']  = $codigoPO;
                        array_push($arrayPartidasInsert, $partidaInfo);
                        $costoTotalPOMO = $costoTotalPOMO + $datos['monto_final'];
                    }
                    
                    /****nuevo de ferreteria******/
                    $infoCostoMax = $this->m_pqt_reg_mat_x_esta_pqt->getCostoMaxMatAndCostoMByItemplan($itemplan, $idEstacion);
                    if($infoCostoMax['monto']==null){
                        throw new Exception('El subproyecto no cuenta con un costo KIT MATERIAL configurado.');
                    }
                    
                    $partidasOutPut = array();
                    $arrayActividades = array();
                    $codigoFerreteria = '69901-2';#ponerlo como constante
                    $infoPartida =  $this->m_pqt_terminado->getInfoPartidaByCodPartida($itemplan, $idEstacion, $codigoFerreteria);
                    if($infoPartida != null){
                        $precioTmp = $infoPartida['costo'];
                    }
                    
                    $totalFerr = $infoCostoMax['monto'];
                    $cantidadFerr = 0;
                    $cantidadFerr = ($totalFerr/$precioTmp);
                    
                    if(!in_array($infoPartida['idActividad'], $arrayActividades)){
                        $partidaFerreteria = array();
                        $partidaFerreteria['codigo_po']        = $codigoPO;
                        $partidaFerreteria['idActividad']      = $infoPartida['idActividad'];
                        $partidaFerreteria['baremo']           = $infoPartida['baremo'];
                        $partidaFerreteria['costo']            = $precioTmp;
                        $partidaFerreteria['cantidad_inicial'] = $cantidadFerr;
                        $partidaFerreteria['monto_inicial']    = $totalFerr;
                        $partidaFerreteria['cantidad_final']   = $cantidadFerr;
                        $partidaFerreteria['monto_final']      = $totalFerr;
                        array_push($arrayPartidasInsert, $partidaFerreteria);//metemos idActividad
                        $costoTotalPOMO = $costoTotalPOMO + $totalFerr;
                    }
                    /***********fin de ferreteria*******/
                    
                    $idEecc = $infoItm['idEmpresaColab'];
                    $from = 1;//harcodeamos oepraciones
    
                    $dataPO = array(
                        'itemplan'      => $itemplan,
                        'codigo_po'     => $codigoPO,
                        'estado_po'     => PO_REGISTRADO, //ESTADO REGISTRADO
                        'idEstacion'    => $idEstacion,
                        'from'          => $from,
                        'costo_total'   => $costoTotalPOMO,
                        'idUsuario'     => $idUsuario,
                        'fechaRegistro' => $this->fechaActual(),
                        'estado_asig_grafo' => 0,
                        'flg_tipo_area' => 2,//MANO DE OBRA
                        'id_eecc_reg'   => $idEecc,
                        'isPoPqt'       =>  1//que es paquetizada la po
                    );
    
                    $dataLogPO = array();
                    $dataLogPO_tmp = array(
                        'codigo_po'         =>  $codigoPO,
                        'itemplan'          =>  $itemplan,
                        'idUsuario'         =>  $idUsuario,
                        'fecha_registro'    =>  $this->fechaActual(),
                        'idPoestado'        =>  PO_REGISTRADO,
                        'controlador'       =>  (($from ==  1) ? 'consulta' : 'diseno')
                    );
    
                    array_push($dataLogPO, $dataLogPO_tmp);
                     
                    $subProyectoEstacion = $this->m_pqt_terminado->getSubProyectoEstacionByItemplanEstacion($itemplan, $idEstacion);
                    if($subProyectoEstacion ==  null){
                        throw new Exception('Hubo un error obtener el subproyecto - estacion');
                    }
                    $dataDetalleplan = array('itemPlan' =>  $itemplan,
                        'poCod'    => $codigoPO,
                        'idSubProyectoEstacion' =>  $subProyectoEstacion);
    
    
                    $dataPqtTmp = array (   'itemplan'          =>  $itemplan,
                        'idEstacion'        =>  $idEstacion,
                        'estado'            =>  1,//validado
                        'codigo_po'         =>  $codigoPO,
                        'fecha_validado'    =>  $this->fechaActual(),
                        'usuario_valida'    =>  $idUsuario
                    );
                    $tipoTmpPoCreate = 1;//1 = insert;
                    $hasPtrCreado = $this->m_pqt_terminado->getEstatusEstacionItemplan($itemplan, $idEstacion);
                    if($hasPtrCreado != null){
                        $tipoTmpPoCreate = 2;//2 = update
                        $dataPqtTmp['id_pqt_partidas'] = $hasPtrCreado['id_pqt_partidas'];
                    }
    
                    /***porsiacaso**/
                    $dataUpdateSolicitud = array ('estado'            => 2,
                        'usua_val_nivel_2'  => $this->session->userdata('idPersonaSession'),
                        'fec_val_nivel_2'   => $this->fechaActual(),
                        'itemplan'          =>  $itemplan,
                        'idEstacion'        =>  $idEstacion
                    );
                    /****/
                    $data = $this->m_pqt_terminado->createPoMO($dataPO, $dataLogPO, $dataDetalleplan, $arrayPartidasInsert, $tipoTmpPoCreate, $dataPqtTmp, $dataUpdateSolicitud);
                    if($data['error']   ==  EXIT_ERROR){
                        throw new Exception('Hubo un error interno, en la transaccion');
                    }
    
                    $data['codigoPO']    =   $codigoPO;
                    $data['error']       = EXIT_SUCCESS;
                }else{
                    throw new Exception('No se encontro partidas paquetizadas para la configuracion.> idEstacion:'.$idEstacion.' | idSubProyecto:'.$infoItm['idSubProyecto'].' | idEmpresaColab:'.$infoItm['idEmpresaColab'].' | tipoJefatura:'.$infoItm['isLima'].' | itemplan:'.$itemplan);
                }
            }else{
                throw new Exception('Su sesion expiro, porfavor vuelva a logearse.');
            }
             
        }catch(Exception $e){
            $this->m_atencion_solicitud_oc_masivo_opex->insertLogErrorCreacioPO(array('itemplan' => $itemplan_in, 'idEstacion' =>$idestacion_in, 'mensaje' => $e->getMessage(), 'estado' => 1, 'codigo_po' => $global_codigo_po));
            // $data['msj'] = $e->getMessage();
        }
        //echo json_encode(array_map('utf8_encode', $data));
        return $data;
	}
    /*
    public function savePoMo($itemplan_in, $idestacion_in){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        $global_codigo_po = null;
        try{
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;
            if($idUsuario   !=     null){
               
                $itemplan       = $itemplan_in;
                $idEstacion     = $idestacion_in;
    
                $arrayPartidasInsert = array();
    
                $infoItm = $this->m_pqt_terminado->getInfoBasicToGeneratePartidasByItemplan($itemplan);
                if($infoItm==null){
                    throw new Exception('Excepcion detectada, comuniquese con soporte.');
                }
                $listaPartidasToCreate = $this->getArrayPartidasToPO($idEstacion, $infoItm['idSubProyecto'], $infoItm['idEmpresaColab'], $infoItm['isLima'], $itemplan);
    
                if($listaPartidasToCreate!=null){
                     
                    $codigoPO = $this->m_utils->getCodigoPO($itemplan);
                    if ($codigoPO == null) {
                        throw new Exception('Hubo un error al generar el codigo PO ');
                    }
                    $global_codigo_po = $codigoPO;
                    $costoTotalPOMO = 0;
                    foreach($listaPartidasToCreate as $datos){
                        $partidaInfo = array();
                        $partidaInfo = $datos;
                        $partidaInfo['codigo_po']  = $codigoPO;
                        array_push($arrayPartidasInsert, $partidaInfo);
                        $costoTotalPOMO = $costoTotalPOMO + $datos['monto_final'];
                    }
    
                    $idEecc = $infoItm['idEmpresaColab'];
                    $from = 1;//harcodeamos oepraciones
    
                    $dataPO = array(
                        'itemplan'      => $itemplan,
                        'codigo_po'     => $codigoPO,
                        'estado_po'     => PO_REGISTRADO, //ESTADO REGISTRADO
                        'idEstacion'    => $idEstacion,
                        'from'          => $from,
                        'costo_total'   => $costoTotalPOMO,
                        'idUsuario'     => $idUsuario,
                        'fechaRegistro' => $this->fechaActual(),
                        'estado_asig_grafo' => 0,
                        'flg_tipo_area' => 2,//MANO DE OBRA
                        'id_eecc_reg'   => $idEecc,
                        'isPoPqt'       =>  1//que es paquetizada la po
                    );
    
                    $dataLogPO = array();
                    $dataLogPO_tmp = array(
                        'codigo_po'         =>  $codigoPO,
                        'itemplan'          =>  $itemplan,
                        'idUsuario'         =>  $idUsuario,
                        'fecha_registro'    =>  $this->fechaActual(),
                        'idPoestado'        =>  PO_REGISTRADO,
                        'controlador'       =>  (($from ==  1) ? 'consulta' : 'diseno')
                    );
    
                    array_push($dataLogPO, $dataLogPO_tmp);
                    	
                    $subProyectoEstacion = $this->m_pqt_terminado->getSubProyectoEstacionByItemplanEstacion($itemplan, $idEstacion);
                    if($subProyectoEstacion ==  null){
                        throw new Exception('Hubo un error obtener el subproyecto - estacion');
                    }
                    $dataDetalleplan = array('itemPlan' =>  $itemplan,
                        'poCod'    => $codigoPO,
                        'idSubProyectoEstacion' =>  $subProyectoEstacion);
    
    
                    $dataPqtTmp = array (   'itemplan'          =>  $itemplan,
                        'idEstacion'        =>  $idEstacion,
                        'estado'            =>  1,//validado
                        'codigo_po'         =>  $codigoPO,
                        'fecha_validado'    =>  $this->fechaActual(),
                        'usuario_valida'    =>  $idUsuario
                    );
                    $tipoTmpPoCreate = 1;//1 = insert;
                    $hasPtrCreado = $this->m_pqt_terminado->getEstatusEstacionItemplan($itemplan, $idEstacion);
                    if($hasPtrCreado != null){
                        $tipoTmpPoCreate = 2;//2 = update
                        $dataPqtTmp['id_pqt_partidas'] = $hasPtrCreado['id_pqt_partidas'];
                    }
    
                  
                    $dataUpdateSolicitud = array ('estado'            => 2,
                        'usua_val_nivel_2'  => $this->session->userdata('idPersonaSession'),
                        'fec_val_nivel_2'   => $this->fechaActual(),
                        'itemplan'          =>  $itemplan,
                        'idEstacion'        =>  $idEstacion
                    );
                 
                    $data = $this->m_pqt_terminado->createPoMO($dataPO, $dataLogPO, $dataDetalleplan, $arrayPartidasInsert, $tipoTmpPoCreate, $dataPqtTmp, $dataUpdateSolicitud);
                    if($data['error']   ==  EXIT_ERROR){
                        throw new Exception('Hubo un error interno, en la transaccion');
                    }
    
                    $data['codigoPO']    =   $codigoPO;
                    $data['error']       = EXIT_SUCCESS;
                }else{
                    throw new Exception('No se encontro partidas paquetizadas para la configuracion.> idEstacion:'.$idEstacion.' | idSubProyecto:'.$infoItm['idSubProyecto'].' | idEmpresaColab:'.$infoItm['idEmpresaColab'].' | tipoJefatura:'.$infoItm['isLima'].' | itemplan:'.$itemplan);
                }
            }else{
                throw new Exception('Su sesion expiro, porfavor vuelva a logearse.');
            }
             
        }catch(Exception $e){
            $this->m_atencion_solicitud_oc_masivo_opex->insertLogErrorCreacioPO(array('itemplan' => $itemplan_in, 'idEstacion' =>$idestacion_in, 'mensaje' => $e->getMessage(), 'estado' => 1, 'codigo_po' => $global_codigo_po));
            // $data['msj'] = $e->getMessage();
        }
        //echo json_encode(array_map('utf8_encode', $data));
        return $data;
    }   
	*/
}
