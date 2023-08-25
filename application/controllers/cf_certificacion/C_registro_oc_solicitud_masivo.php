<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class C_registro_oc_solicitud_masivo extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
		
		$this->load->model('mf_certificacion/m_registro_oc_solicitud_masivo_dev');
        $this->load->model('mf_certificacion/m_registro_oc_solicitud_masivo');
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
            $permisos = $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, 250, 301, ID_MODULO_ADMINISTRATIVO);
            $data['opciones'] = $result['html'];
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_certificacion/v_registro_oc_solicitud_masivo', $data);
            } else {
                redirect('login', 'refresh');
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function getExcelPartidasMO() {
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

    public function uploadPOMO() {
        $data ['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $uploaddir = 'uploads/oc_masivo/'; //ruta final del file
            $uploadfile = $uploaddir . basename($_FILES['file']['name']);
            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {

                $objPHPExcel = PHPExcel_IOFactory::load($uploadfile);

                $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
                $row_dimension = $objPHPExcel->getActiveSheet()->getHighestRowAndColumn();

                $info_2 = $this->makeHTMLBodyTable($row_dimension, $objPHPExcel);
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
            $A = $this->lib_utils->removeEnterYTabs($objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, $i, true)->getValue());
            $B = $this->lib_utils->removeEnterYTabs($objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1, $i, true)->getValue());
            $C = $this->lib_utils->removeEnterYTabs($objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2, $i, true)->getValue());
            $D = $this->lib_utils->removeEnterYTabs($objPHPExcel->getActiveSheet()->getCellByColumnAndRow(3, $i, true)->getValue());
            $total = 0;
            if ($B != '' && $C != '' && $D != '' && $A != 'CODIGO SOLICITUD') {
                $infoSol = $this->m_registro_oc_solicitud_masivo->getInfoSolicitudOCCreaByCodigo(trim($A));
				
				$costo_resta = abs($infoSol['costo_unitario_mo'] - (float)$D);
				
				if($infoSol['tipo_moneda'] == 'USD') {
					$costo_resta = null;
				}
				
                if($infoSol != null){
                    if($infoSol['estado']==1 && $infoSol['cant'] == 1 && ($costo_resta == null || $costo_resta > 1)){
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
                    }else{//IVALIDO ATENDIDA O CANCELADA
                        if($infoSol['cant']==0 || $infoSol['cant']==null){
                            $msj = 'SOLICITUD SIN ITMEPLAN ASOCIADO';
                        }else if($infoSol['cant'] > 1){
                            $msj = 'SOLICITUD CON MAS DE 1 ITEMPLAN ASOCIADO';
                        }else if($infoSol['estado']==2){
                            $msj = 'SOLICITUD ATENDIDA';
                        }else if($infoSol['estado']==3){
                            $msj = 'SOLICITUD CANCELADA';
                        }else if($costo_resta > 1) {
							$msj = 'LA DIFERENCIA DEL COSTO SAP SUPERA A 1 AL DE LA WEB PO';
							
							// $idUsuario = $this->session->userdata('idPersonaSession');
							
							// $dataArray = array(
													// 'codigo_solicitud' => $A,
													// 'fecha_registro'   => $this->fechaActual(),
													// 'estado'           => 1,
													// 'costo_sap'        => $C,
													// 'idUsuarioReg'     => $idUsuario,
													// 'costo_obra'       => $infoSol['costo_unitario_mo']
												// );
							// $this->m_utils->insertLogSapCosto($dataArray);
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

	/**czavala 21.06.2020**/
	 public function regMasiveOcSol() {
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
                                $idTipoPlanta       = $datos[7];
                                $paquetizado_fg     = $datos[8];
                                
								$itemplanData = array(  'itemplan'      => $itemplan,
								                        'orden_compra'  => $orden_compra,
								                        'posicion'      => 1,
								                        'cesta'         => $cesta,
								                        'estado_sol_oc' => 'ATENDIDO',								                        
								                        'costo_sap'     => $costo_sap,
								                        'solicitud_oc'  => $codigo_solicitud
								                    );
								
								if($idEstadoPlan == ID_ESTADO_PRE_REGISTRO){//ACTUALIZAMOS ESTADOPLAN
								    $itemplanData['usu_upd']        = $idUsuario;
								    $itemplanData['fecha_upd']      = $this->fechaActual();
								    $itemplanData['descripcion']    = 'ORDEN DE COMPRA ATENDIDA';
								    if(in_array($idSubProyecto, array(279,283,554,579,582))){//subrpoyectos rutas y balanceo to en obra
								        $itemplanData['idEstadoPlan']   = ID_ESTADO_PLAN_EN_OBRA;
								    }else{//to diseno
								        $itemplanData['idEstadoPlan']   = ID_ESTADO_DISENIO;
								        $be_adjudicacion = true;
								    }
								}else if($idEstadoPlan == ID_ESTADO_DISENIO || $idEstadoPlan == ESTADO_PLAN_PDT_OC){
								    //if($idTipoPlanta == 2) {//PLANTA INTERNA
								        $itemplanData['usu_upd']        = $idUsuario;
								        $itemplanData['fecha_upd']      = $this->fechaActual();
								        $itemplanData['descripcion']    = 'ORDEN DE COMPRA ATENDIDA';
								        $itemplanData['idEstadoPlan']   = ID_ESTADO_PLAN_EN_OBRA;
								        
								        $ptrPI = array(
								            "itemplan"                 => $itemplan,
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
								    //}
								}
								array_push($itemplanList, $itemplanData);
								
							    if($be_adjudicacion){//solo si acaba de pasar de pre registro a diseno adjudico y registro ot
							        $has_ancla  = false;
							        $has_fo     = false;
							        $has_coax   = false;
							        $infoAnclasByItemplan = $this->m_registro_oc_solicitud_masivo->getEstacionesAnclasByItemplan($itemplan);
							        if($infoAnclasByItemplan['coaxial']    >   0){
							            $has_coax  = true;
							            $has_ancla = true;
							        }
							        if($infoAnclasByItemplan['fo']    >   0){
							            $has_fo    = true;
							            $has_ancla = true;
							        }
							    
							        if($has_ancla){//si tiene anclas obtenemos sus idas de adjudicacion
							            
							            $dias = $this->m_registro_oc_solicitud_masivo->getDiaAdjudicacionBySubProyecto($idSubProyecto);
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
						
                        $data = $this->m_registro_oc_solicitud_masivo->masiveUpdateAtencionCreateSolOC($itemplanList, $solicitudesList, $pre_disenosList, $ptrsPlantaInterna);                       
                        
                        if($data['error']   ==  EXIT_SUCCESS){//SI GENERO LAS SOLICITUDES
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
                                $this->M_integracion_sirope->execWs($sirope['itemplan'], $sirope['itemplan'] . 'FO', $this->fechaActual(), $sirope['fechaPrev']);
								if($sirope['idSubProyecto']==51){//PARTICIONES SATURACION GENERA OT COAXIAL CZAVALA 01.10.2020
									$this->M_integracion_sirope->execWs($sirope['itemplan'], $sirope['itemplan'] . 'COAX', $this->fechaActual(), $sirope['fechaPrev']);
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
            $this->m_registro_oc_solicitud_masivo->insertLogErrorCreacioPO(array('itemplan' => $itemplan_in, 'idEstacion' =>$idestacion_in, 'mensaje' => $e->getMessage(), 'estado' => 1, 'codigo_po' => $global_codigo_po));
            // $data['msj'] = $e->getMessage();
        }
        //echo json_encode(array_map('utf8_encode', $data));
        return $data;
    }   

}
