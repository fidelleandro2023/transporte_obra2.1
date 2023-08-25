<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class C_registro_oc_solicitud extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_certificacion/m_registro_oc_solicitud');
        $this->load->model('mf_pre_diseno/m_bandeja_adjudicacion');
		$this->load->model('mf_certificacion/m_registro_oc_solicitud_masivo_dev');
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
            $solicitud = (isset($_GET['sol']) ? $_GET['sol'] : '');
            //PONER VALIDACIONES
            $data['solicitud'] = $solicitud;
            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $permisos = $this->session->userdata('permisosArbolTransporte');
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_PLAN_DE_OBRA, ID_PERMISO_HIJO_CONSULTAS);
            $data['opciones'] = $result['html'];
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_certificacion/v_registro_oc_solicitud', $data);
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
            $solicitud = $this->input->post('solicitud');

            $arrayMateriales = $this->m_registro_oc_solicitud->getObrasBysolicitud($solicitud);
            if (is_array($arrayMateriales)) {

                if (count($arrayMateriales) > 0) {
                    ini_set('max_execution_time', 10000);
                    ini_set('memory_limit', '2048M');

                    $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
                    $cacheSettings = array('memoryCacheSize ' => '5000MB', 'cacheTime' => '1000');
                    PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle('ValeDatos');
                    $contador = 1;
                    $titulosColumnas = array('ITEMPLAN', 'CESTA', 'ORDEN COMPRA', 'POSICION', 'COSTO SAP');

                    $this->excel->setActiveSheetIndex(0);

                    // Se agregan los titulos del reporte
                    $this->excel->setActiveSheetIndex(0)
                            ->setCellValue('A1', utf8_encode($titulosColumnas[0]))
                            ->setCellValue('B1', utf8_encode($titulosColumnas[1]))
                            ->setCellValue('C1', utf8_encode($titulosColumnas[2]))
                            ->setCellValue('D1', utf8_encode($titulosColumnas[3]))
							->setCellValue('E1', utf8_encode($titulosColumnas[4]));

                    foreach ($arrayMateriales as $row) {
                        $contador++;
                        $this->excel->getActiveSheet()->setCellValue("A{$contador}", $row->itemPlan)
                                ->setCellValue("B{$contador}", '')
                                ->setCellValue("C{$contador}", '')
                                ->setCellValue("D{$contador}", '')
								->setCellValue("E{$contador}", '');
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
                    $archivo = "solicitud_oc.xls";
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="' . $archivo . '"');
                    header('Cache-Control: max-age=0');
                    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                    //Hacemos una salida al navegador con el archivo Excel.

                    $nombreFile = 'download/detalleMatPO/solicitud_oc_' . date("YmdHis") . '.xls';
                    $objWriter->save($nombreFile);
                    $data['rutaExcel'] = $nombreFile;
                    $data['error'] = EXIT_SUCCESS;
                } else {
                    $data['msj'] = "No se encontro asociacion entre la solicitud y el itemplan, comuniquese con CAP";
                }
            } else {
                $data['msj'] = "No se encontro asociacion entre la solicitud y el itemplan, comuniquese con CAP";
            }
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
            $solicitud = $this->input->post('item');

            $uploaddir = 'uploads/po_mo/'; //ruta final del file
            $uploadfile = $uploaddir . basename($_FILES['file']['name']);
            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {

                $objPHPExcel = PHPExcel_IOFactory::load($uploadfile);

                $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
                $row_dimension = $objPHPExcel->getActiveSheet()->getHighestRowAndColumn();

                $info_2 = $this->makeHTMLBodyTable($row_dimension, $objPHPExcel, $solicitud);
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

    public function makeHTMLBodyTable($row_dimension, $objPHPExcel, $solicitud) {
        $data['html'] = '';
        $data['array'] = '';
        $data['array_full'] = '';
        $html = '';
        $indice = 0;
        $cont_indice_valido = 0;
        $array_valido = array();
        $array_not_found = array();
        $indice_valido = '';
        $array_full = array();
        $total_final = 0;
        for ($i = 2; $i <= $row_dimension['row']; $i++) {//COMIENZA DESDE LA FILA 1            
            $A = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, $i, true)->getValue();
            log_message('error', '$A:' . $A);
            $B = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1, $i, true)->getValue();
            $C = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2, $i, true)->getValue();
            $D = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(3, $i, true)->getValue();
            $E = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(4, $i, true)->getValue();
            $F = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(5, $i, true)->getValue();
            $total = 0;
            if ($B != '' && $C != '' && $D != '' && $A != 'ITEMPLAN') {



                $html .= '<tr id="tr' . $indice . '" >
                            <th style="width: 5px;"><!--<a style="cursor:pointer;" ' . $indice_valido . ' data-indice="' . $indice . '" onclick="removeTR(this)"><img class="delete_ptr" alt="Eliminar" height="20px" width="20px" src="public/img/iconos/delete.png"></a>--></th>
                        	<th style="color:black">' . $A . '</th>
                        	<th style="color:black">' . $B . '</th>
                        	<th style="color:black">' . $C . '</th>
							<th style="color:black">' . $D . '</th>
							<th style="color:black">' . $E . '</th>
                    	</tr>';
                $indice++;

                $pre_array = array($A, $B, $C, $D, $E);
                array_push($array_full, $pre_array);
            }
        }

        $data['html'] = $html;
        $data['array_full'] = $array_full;

        return $data;
    }

    public function savePoMo($itemplan_in, $idestacion_in){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        $global_codigo_po = null;
        try{
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;
            if($idUsuario   !=     null){
                log_message('error', '$itemplan_in:'.$itemplan_in);
                log_message('error', '$idestacion_in:'.$idestacion_in);
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
                    log_message('error', 'eeee->'.print_r($dataPO, true));
    
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
                    log_message('error', print_r('4.$idEstacion:'.$idEstacion, true));
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
                        throw new Exception('Hubo un error interno, por favor volver a intentar.');
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
            $this->m_registro_oc_solicitud->insertLogErrorCreacioPO(array('itemplan' => $itemplan_in, 'idEstacion' =>$idestacion_in, 'mensaje' => $e->getMessage(), 'estado' => 1, 'codigo_po' => $global_codigo_po));
           // $data['msj'] = $e->getMessage();
        }
        //echo json_encode(array_map('utf8_encode', $data));
        return $data;
    }

    public function UpdateEstadoItemplan($itemplan) {
        $idEstadoPlan = $this->m_registro_oc_solicitud->getItemplanId($itemplan);
	$idEstadoPlan = ID_ESTADO_DISENIO;

        if ($this->m_registro_oc_solicitud->getItemplanIdFirst($itemplan) === null) {
            $idEstadoPlan = ID_ESTADO_DISENIO;
        } else {
            if ($idEstadoPlan === 8 || $idEstadoPlan === '8') {
                $idEstadoPlan = ID_ESTADO_DISENIO;
            } else {
                $idEstadoPlan = $idEstadoPlan;
            }
        }
        return $idEstadoPlan;
    }

    public function MasivoSavePo() {
        $MasivaData = $this->m_utils->MasivoGetItemPlam();
        foreach ($MasivaData as $datos) {
            if ($datos != null) {
                $countPrediseno = $this->m_utils->CountPredisenoByItemplan($datos->itemplan);
                if (count($countPrediseno) > 0) {
                    log_message('error', 'Count PreDiseno ' . count($countPrediseno));
                } else {
                    log_message('error', 'Count Else PreDiseno ' . count($countPrediseno));
                    /////////////////////////////////////////////////////////////// 
                    $conEsta = $this->m_bandeja_adjudicacion->countFOAndCoaxByItemplan($datos->itemplan);
                    $this->session->set_userdata('has_fo', $conEsta['fo']);
                    $this->session->set_userdata('has_coax', $conEsta['coaxial']);
                    $idSubproy = $this->m_utils->getPlanobraByItemplan($datos->itemplan);
                    $dias = $this->m_bandeja_adjudicacion->getDiaAdjudicacionBySubProyecto($idSubproy['idSubProyecto']);
                    $curHour = date('H');
                    if ($curHour >= 13) {//13:00 PM
                        $dias = ($dias + 1);
                    }
                    $nuevafecha = strtotime('+' . $dias . ' day', strtotime($this->fechaActual()));
                    $idFechaPreAtencionFo = date('Y-m-j', $nuevafecha);
                    $info = $this->m_bandeja_adjudicacion->adjudicarItemplan($datos->itemplan, $idSubproy['idSubProyecto'], $idSubproy['idCentral'], $idSubproy['idEmpresaColab'], null, $idFechaPreAtencionFo);
                    ///////////////////////////////////////////////////////////// 
//                    $this->M_integracion_sirope->execWs($datos->itemplan, $datos->itemplan . 'FO', $this->fechaActual(), $idFechaPreAtencionFo);
                }
            }
        }
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
	 public function createSOlOC() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;
            if ($idUsuario != null) {               
                $solicitud = $this->input->post('solicitud');
                $jsonDataFile = $this->input->post('jsonDataFile');

                $pathItemplan = 'uploads/certificacion/' . $solicitud;
                if (!is_dir($pathItemplan)) {
                    mkdir($pathItemplan, 0777);
                }
				
				if (count($_FILES) > 0) {
					$uploadfile1 = $pathItemplan . '/' . basename($_FILES['fileEvi']['name']);
					if (!move_uploaded_file($_FILES['fileEvi']['tmp_name'], $uploadfile1)) {
						throw new Exception("Error al momento de cargar el pdf");
					}
					$dataSolicitud['path_oc'] = $uploadfile1;
				}
                


                // if (move_uploaded_file($_FILES['fileEvi']['tmp_name'], $uploadfile1)) {//solo si semeuveel documento.
				$arrayFile = json_decode($jsonDataFile);
				$arrayFinalUpdate = array();
				$arrayPlanObra = array();
				$primerOC = null;
				$primerCesta = null;
				$costo_sap   = null;
				if ($arrayFile != null) {
					$itemplanList = array();//para generar las po pqt
					$itemplaArray = array();
					foreach ($arrayFile as $datos) {
						if ($datos != null) {
							$be_adjudicacion = false;
							if ($primerOC == null || $primerCesta == null) {
								$primerOC = $datos[2];
								$primerCesta = $datos[1];
								$costo_sap   = $datos[4];
							}
							$dataCMO = array();
							$dataCMO['itemplan'] = $datos[0];
							$dataCMO['cesta'] = $datos[1];
							$dataCMO['orden_compra'] = $datos[2];
							$dataCMO['posicion'] = $datos[3];
							$dataCMO['costo_sap'] = $datos[4];
							$dataCMO['estado_sol_oc'] = 'ATENDIDO';
							$idEstadoPlan = $this->m_registro_oc_solicitud->getEstadoByItemplan($datos[0]);
							$idTipoPlanta = $this->m_utils->getTipoPlantaByItemplan($datos[0]);
							
							$infoSol = $this->m_registro_oc_solicitud_masivo_dev->getInfoSolicitudOCCreaByCodigoCrea($solicitud);
										
							if($infoSol['tipo_moneda'] != 'USD') {
								$costo_resta = abs($infoSol['costo_unitario_mo'] - $datos[4]);
								if ($costo_resta > 1) {
									throw new Exception('LA DIFERENCIA DEL COSTO SAP SUPERA A 1 AL DE LA WEB PO.');
								}
							}

							 //IVAN
							 $arrayDataItem = array(
														'idEstadoPlan' => 19,
														'usu_upd' => $this->session->userdata('idPersonaSession'),
														'fecha_upd' => $this->fechaActual(),
														'descripcion' => 'DE DISEÑO EJECUTADO A LICENCIA',
													);
						   
							if ($this->m_registro_oc_solicitud->getIdSubproyecto($datos[0]) == 4) {
								$this->m_registro_oc_solicitud->updateItemplanObraPublica($arrayDataItem, $datos[0]);
							}
//					
							if($idEstadoPlan != ESTADO_PLAN_PDT_OC) {
								throw new Exception("Verificar el itemplan ya que no se encuentra en el estado correcto.");
							}
							
							
							if ($idEstadoPlan === ID_ESTADO_DISENIO || $idEstadoPlan === ESTADO_PLAN_PDT_OC) {
								// $dataItemplan = array();
								// $dataCMO['itemplan'] = $datos[0];
								$dataCMO['usu_upd'] = $this->session->userdata('idPersonaSession');
								$dataCMO['fecha_upd'] = $this->fechaActual();
								$dataCMO['descripcion'] = 'ORDEN DE COMPRA ATENDIDA';
								$dataCMO['idEstadoPlan'] = 3;
							}
							
							// array_push($itemplaArray, $dataItemplan);
							array_push($arrayPlanObra, $dataCMO);
						}
					}

					$dataSolicitud = array();
					$dataSolicitud['usuario_valida'] = $this->session->userdata('idPersonaSession');
					$dataSolicitud['fecha_valida'] = $this->fechaActual();
					$dataSolicitud['estado'] = 2;
					
					$dataSolicitud['cesta'] = $primerCesta;
					$dataSolicitud['orden_compra'] = $primerOC;
					$dataSolicitud['costo_sap'] = $costo_sap;

					/*creacion de po pqt*/
					$data = $this->m_registro_oc_solicitud->createOCAndAsiento($arrayPlanObra, $dataSolicitud, $solicitud, $idTipoPlanta, $datos[0]);

					/************************/
				} else {
					throw new Exception('No se pudo procesar el archivo, refresque la pagina y vuelva a intentarlo.');
				}
                // }
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
    
    
    /***test**/
    public function savePoMoTest(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        $global_codigo_po = null;
        try{
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;
            if($idUsuario   !=     null){
               // log_message('error', '$itemplan_in:'.$itemplan_in);
               // log_message('error', '$idestacion_in:'.$idestacion_in);
			   
			   $itemList = array('20-2120200022',
'20-2121500010',
'20-2111100811',
'20-2111100812',
'20-2111100813',
'20-2111100814');
				foreach($itemList as $itmmm){
					$itemplan       = $itmmm;
					$idEstacion     = 2;
		
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
						log_message('error', 'eeee->'.print_r($dataPO, true));
		
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
						log_message('error', '-------------------------');
						$subProyectoEstacion = $this->m_pqt_terminado->getSubProyectoEstacionByItemplanEstacion($itemplan, $idEstacion);
						log_message('error', print_r('4.$idEstacion:'.$idEstacion, true));
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
							throw new Exception('Hubo un error interno, por favor volver a intentar.');
						}
		
						$data['codigoPO']    =   $codigoPO;
						$data['error']       = EXIT_SUCCESS;
					}else{
						throw new Exception('No se pudo procesar el archivo, refresque la pagina y vuelva a intentarlo.');
					}
				}
            }else{
                throw new Exception('Su sesion expiro, porfavor vuelva a logearse.');
            }
             
        }catch(Exception $e){
           // $this->m_registro_oc_solicitud->insertLogErrorCreacioPO(array('itemplan' => $itemplan_in, 'idEstacion' =>$idestacion_in, 'mensaje' => $e->getMessage(), 'estado' => 1, 'codigo_po' => $global_codigo_po));
		   log_message('error','EXCEPCION:'.$e->getMessage());
            // $data['msj'] = $e->getMessage();
        }
        //echo json_encode(array_map('utf8_encode', $data));
        return $data;
    }
	
	 /***test n2**/
    public function crearPoPqtAndPArtidaFerreteria(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        $global_codigo_po = null;
        try{
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;
            if($idUsuario   !=     null){
                // log_message('error', '$itemplan_in:'.$itemplan_in);
                // log_message('error', '$idestacion_in:'.$idestacion_in);
				$itemList = array('20-0511000001');
				foreach($itemList as $itmmm){
                $itemplan       = $itmmm;
                $idEstacion     = 5;
    
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
                    log_message('error', 'eeee->'.print_r($dataPO, true));
    
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
                    log_message('error', '-------------------------');
                    $subProyectoEstacion = $this->m_pqt_terminado->getSubProyectoEstacionByItemplanEstacion($itemplan, $idEstacion);
                    log_message('error', print_r('4.$idEstacion:'.$idEstacion, true));
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
                        throw new Exception('Hubo un error interno, por favor volver a intentar.');
                    }
                    
                    
                    $infoCostoMax = $this->m_pqt_reg_mat_x_esta_pqt->getCostoMaxMatAndCostoMByItemplan($itemplan);
                    if($infoCostoMax['monto']==null){
                        throw new Exception('El subproyecto no cuenta con un costo KIT MATERIAL FO configurado.');
                    }
                    if($infoCostoMax['costo_unitario_mo']==null || $infoCostoMax['costo_unitario_mo']== 0 ){
                        throw new Exception('Costo MO de la obra no valido, comuniquese con CAP.');
                    }
                    $arrayFile = $this->m_pqt_reg_mat_x_esta_pqt->getMaterialesByIdEstacionItemplan($itemplan, $idEstacion);
                    $arrayFinalUpdate = array();
                    $materialesDetalle = array();
                    $costoTotalMateriales = 0;
                    $costoTotalMatReg = 0;
                    foreach($arrayFile as $row){
                        if($datos!=null){
                            $dataCMO = array();
                            $dataCMO['itemplan']                = $itemplan;
                            $dataCMO['idEstacion']              = $idEstacion;
                            $dataCMO['id_material']             = $row->id_material;
                            $dataCMO['costo_inicial_material']  = $row->costo_inicial_material;
                            $dataCMO['cantidad_inicial']        = $row->cantidad_inicial;
                            $dataCMO['monto_inicial']           = $row->monto_inicial;
                            $dataCMO['costo_final_material']    = $row->costo_final_material;
                            $dataCMO['cantidad_final']          = $row->cantidad_final;
                            $dataCMO['monto_final']             = $row->monto_final;
                            $dataCMO['usua_registro']           = $this->session->userdata('idPersonaSession');
                            $dataCMO['fecha_registro']          = $this->fechaActual();
                            array_push($materialesDetalle, $dataCMO);
                            $costoTotalMateriales = $costoTotalMateriales + $dataCMO['monto_final'];                                
                        }
                    }
                         
                    $partidaPadre = $this->m_pqt_reg_mat_x_esta_pqt->getPartidaPadre($itemplan, $idEstacion);
                        $materialesPadre = array(
                            'itemplan'              => $itemplan,
                            'idEstacion'            => $idEstacion,
                            'costo_total_inicial'   => $costoTotalMateriales,
                            'costo_total_final'     => $costoTotalMateriales,
                            'usua_registro'         => $partidaPadre['usua_registro'],
                            'fecha_registro'        => $partidaPadre['fecha_registro'],
                            'path_evidencia'        => $partidaPadre['path_evidencia'],
                            'estado'                => 0
                        );
                    
                        /*****************************obtenemos costo partida********************************/
                        $total_partida_ferreteria = 0;
                        if($costoTotalMateriales > $infoCostoMax['monto']){
                            $total_partida_ferreteria = $infoCostoMax['monto'];
                        }else{
                            $total_partida_ferreteria = $costoTotalMateriales;
                        }
                    
                        $infoCostosObra = $this->m_utils->getVariablesCostoUnitario($itemplan, TIPO_PO_MANO_OBRA, $codigoPO);
                        if($infoCostosObra==null){
                            throw new Exception('No se encontro informacion de la solicitud refresque y vuelva a intentarlo, de continuar el problema genere un ticket CAP.');
                        }                        
                        $infoPo = $this->m_pqt_reg_mat_x_esta_pqt->getInfoPo($codigoPO);
                        if($infoPo==null){
                            throw new Exception('No se encontro informacion de la PO refresque y vuelva a intentarlo, de continuar el problema genere un ticket CAP.');
                        }
                    
                        $costoTotalAllPo        =  $infoCostosObra['total'];//costo actual de todas las po
                        $costoTotalPo           = $total_partida_ferreteria + $infoPo['monto_pqt'];
                        $nuevoCostoTotalAllPo   = $costoTotalAllPo + $costoTotalPo;
                        $costo_unit_mo          = $infoCostosObra['costo_unitario_mo'];
                        $genOcEdic = true;
                        if($nuevoCostoTotalAllPo <= $costo_unit_mo){
                            $genOcEdic = false;
                        }
                    
                        if($genOcEdic){
                            $exceso          = $nuevoCostoTotalAllPo - $costo_unit_mo;
                            $costo_final_sol = $costo_unit_mo+$exceso;
                        }else{
                            $exceso = 0;
                            $costo_final_sol = $costo_unit_mo+$exceso;
                        }
                    
                        $partidasOutPut = array();
                        $arrayActividades = array();
                        $codigoFerreteria = '69901-2';#ponerlo como constante
                        $infoPartida =  $this->m_pqt_terminado->getInfoPartidaByCodPartida($itemplan, $idEstacion, $codigoFerreteria);
                        if($infoPartida != null){
                            $precioTmp = $infoPartida['costo'];
                        }
                    
                        //$ferreteriaInfo = $this->m_pqt_terminado->getGroupFerreteria($itemplan, $idEstacion);
                        $cantidadFerr = 0;
                        $totalFerr = 0;
                        // if($ferreteriaInfo != null){
                        $totalFerr = $total_partida_ferreteria;
                        $cantidadFerr = ($totalFerr/$precioTmp);
                    
                        if(!in_array($infoPartida['idActividad'], $arrayActividades)){
                            $dataCMO = array();
                            $dataCMO['codigo_po']        = $codigoPO;
                            $dataCMO['idActividad']      = $infoPartida['idActividad'];
                            $dataCMO['baremo']           = $infoPartida['baremo'];
                            $dataCMO['costo']            = $precioTmp;
                            $dataCMO['cantidad_inicial'] = $cantidadFerr;
                            $dataCMO['monto_inicial']    = $totalFerr;
                            $dataCMO['cantidad_final']   = $cantidadFerr;
                            $dataCMO['monto_final']      = $totalFerr;
                            array_push($arrayActividades, $infoPartida['idActividad']);//metemos idActividad
                            array_push($partidasOutPut, $dataCMO);
                        }
                        // }
                        /*************************************************************/
                        $dataSolExceso = array (   'itemplan'          =>  $itemplan,
                            'codigo_po'         =>  $codigoPO,
                            'idEstacion'        =>  $idEstacion,
                            'tipo_po'           =>  2,
                            'costo_inicial'     =>  $costo_unit_mo,
                            'exceso_solicitado' =>  $exceso,
                            'costo_final'       =>  $costo_final_sol,
                            'usuario_solicita'  =>  $idUsuario,
                            'fecha_solicita'    =>  $this->fechaActual(),
                            'origen'            =>  5,//ADICIONALES PQT,
                            'genSolEdic'        =>  (($genOcEdic) ? 1 : 0),
                            'isFerreteria'      =>  1
                        );
                    
                        $data = $this->m_pqt_reg_mat_x_esta_pqt->createSolExcesoPartidasAdicionalesPqt($partidasOutPut, $dataSolExceso, $itemplan, $idEstacion, $materialesPadre, $materialesDetalle);
                         $data['error']       = EXIT_SUCCESS;
                    
                    }else{
                        throw new Exception('No se pudo procesar el archivo de materiales, refresque la pagina y vuelva a intentarlo.');
                    } 
					
				}
            }else{
                throw new Exception('Su sesion expiro, porfavor vuelva a logearse.');
            }
             
        }catch(Exception $e){
				log_message('error', 'creacion pqt:'.$e->getMessage());
		// $data['msj'] = $e->getMessage();
        }
        //echo json_encode(array_map('utf8_encode', $data));
        return $data;
    }
	
	public function masiveAdjudicacionByItemplaList(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            
            $itemplanList = array('20-0120100011');
            
            foreach ($itemplanList as $itemplan){
                $countPrediseno = $this->m_utils->CountPredisenoByItemplan($itemplan);
                if (count($countPrediseno) > 0) {
                    log_message('error', 'Count PreDiseno ' . count($countPrediseno));
                } else {
                    ///////////////////////////////////////////////////////////////
                    $conEsta = $this->m_bandeja_adjudicacion->countFOAndCoaxByItemplan($itemplan);
                    $this->session->set_userdata('has_fo', $conEsta['fo']);
                    $this->session->set_userdata('has_coax', $conEsta['coaxial']);
                    $idSubproy = $this->m_utils->getPlanobraByItemplan($itemplan);
                    $dias = $this->m_bandeja_adjudicacion->getDiaAdjudicacionBySubProyecto($idSubproy['idSubProyecto']);
                    $curHour = date('H');
                    if ($curHour >= 13) {//13:00 PM
                        $dias = ($dias + 1);
                    }
                    $nuevafecha = strtotime('+' . $dias . ' day', strtotime($this->fechaActual()));
                    $idFechaPreAtencionFo = date('Y-m-j', $nuevafecha);
                    $info = $this->m_bandeja_adjudicacion->adjudicarItemplan($itemplan, $idSubproy['idSubProyecto'], $idSubproy['idCentral'], $idSubproy['idEmpresaColab'], null, $idFechaPreAtencionFo);
                    ///////////////////////////////////////////////////////////////
                    //$this->M_integracion_sirope->execWs($datos[0], $datos[0] . 'FO', $this->fechaActual(), $idFechaPreAtencionFo);
                }  
            }
        }catch(Exception $e){
				log_message('error', 'creacion pqt:'.$e->getMessage);
        }
        //echo json_encode(array_map('utf8_encode', $data));
        return $data;
    }
}
