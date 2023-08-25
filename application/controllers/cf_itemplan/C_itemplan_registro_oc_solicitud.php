<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class C_itemplan_registro_oc_solicitud extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_itemplan/m_itemplan_registro_oc_solicitud');
		$this->load->model('mf_certificacion/m_registro_oc_solicitud');
        $this->load->model('mf_pre_diseno/m_bandeja_adjudicacion');
        $this->load->model('mf_servicios/M_integracion_sirope');
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
			$result = $this->lib_utils->getHTMLPermisos($permisos, 250, 283, ID_MODULO_ADMINISTRATIVO);
            $data['opciones'] = $result['html'];
			if ($result['hasPermiso'] == true) {
                $this->load->view('vf_itemplan/v_itemplan_registro_oc_solicitud', $data);
            } else {
                redirect('login', 'refresh');
            }
//            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_GESTION_MANTENIMIENTO, ID_PERMISO_HIJO_REGISTRO_OPEX, ID_MODULO_GESTION_MANTENIMIENTO, ID_PERMISO_PADRE_MODULO_OPEX);
//
//            $data['opciones'] = $result['html'];
//            if ($result['hasPermiso'] == true) {
//                $this->load->view('vf_itemplan/v_itemplan_registro_oc_solicitud', $data);
//            } else {
//                redirect('login', 'refresh');
//            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function getExcelPartidasMO() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $solicitud = $this->input->post('solicitud');

            $arrayMateriales = $this->m_itemplan_registro_oc_solicitud->getObrasBysolicitud($solicitud);
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
                    $titulosColumnas = array('ITEMPLAN', 'CESTA', 'ORDEN COMPRA', 'POSICION');

                    $this->excel->setActiveSheetIndex(0);

                    // Se agregan los titulos del reporte
                    $this->excel->setActiveSheetIndex(0)
                            ->setCellValue('A1', utf8_encode($titulosColumnas[0]))
                            ->setCellValue('B1', utf8_encode($titulosColumnas[1]))
                            ->setCellValue('C1', utf8_encode($titulosColumnas[2]))
                            ->setCellValue('D1', utf8_encode($titulosColumnas[3]));

                    foreach ($arrayMateriales as $row) {
                        $contador++;
                        $this->excel->getActiveSheet()->setCellValue("A{$contador}", $row->itemPlan)
                                ->setCellValue("B{$contador}", '')
                                ->setCellValue("C{$contador}", '')
                                ->setCellValue("D{$contador}", '');
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
                    $data['msj'] = "No hay partidas configuradas para esta estaci&oacute;n !!, porfavor conmunicarse con dise&ntilde;o";
                }
            } else {
                $data['msj'] = "No hay partidas configuradas para esta estaci&oacute;n !!, porfavor conmunicarse con dise&ntilde;o";
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
        for ($i = 1; $i <= $row_dimension['row']; $i++) {//COMIENZA DESDE LA FILA 1            
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
                    	</tr>';
                $indice++;

                $pre_array = array($A, $B, $C, $D);
                array_push($array_full, $pre_array);
            }
        }

        $data['html'] = $html;
        log_message('error', $html);
        $data['array_full'] = $array_full;

        return $data;
    }

    public function savePoMo() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;
            if ($idUsuario != null) {
                $solicitud = $this->input->post('solicitud');
                $jsonDataFile = $this->input->post('jsonDataFile');
				$itemplan_global = null;
                $tipoPlanta_global = null;
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


                //if (move_uploaded_file($_FILES['fileEvi']['tmp_name'], $uploadfile1)) {//solo si semeuveel documento.
				$arrayFile = json_decode($jsonDataFile);
				$arrayFinalUpdate = array();
				$arrayPlanObra = array();
				log_message('error', print_r('aqui', true));
				$primerOC = null;
				$primerCesta = null;
				if ($arrayFile != null) {
					$itemplaArray = array();
					foreach ($arrayFile as $datos) {
						if ($datos != null) {
							if ($primerOC == null || $primerCesta == null) {
								$primerOC = $datos[2];
								$primerCesta = $datos[1];
							}
							$idEstadoPlan = $this->m_registro_oc_solicitud->getEstadoByItemplan($datos[0]);
							$flg_opex = $this->m_utils->getTipoPlantaByItemplan($datos[0]);//nuevo 24.08.2020 czavala
							$itemplan_global = $datos[0];
							$tipoPlanta_global = $flg_opex;
							$dataCMO = array();
							$dataCMO['itemplan'] = $datos[0];
							$dataCMO['cesta'] = $datos[1];
							$dataCMO['orden_compra'] = $datos[2];
							$dataCMO['posicion'] = $datos[3];
							$dataCMO['estado_sol_oc'] = 'ATENDIDO';
							_log("ESTADO: ".$idEstadoPlan);
							if($idEstadoPlan != ESTADO_PLAN_PDT_OC) {
								throw new Exception("Verificar el itemplan ya que no se encuentra en el estado correcto1.");
							}
							
							if ($idEstadoPlan === ID_ESTADO_DISENIO || $idEstadoPlan === ESTADO_PLAN_PDT_OC) {
								_log("ENTRO CAMBIO ESTADO");
								$dataCMO['usu_upd'] = $this->session->userdata('idPersonaSession');
								$dataCMO['fecha_upd'] = $this->fechaActual();
								$dataCMO['descripcion'] = 'ORDEN DE COMPRA ATENDIDA';
								$dataCMO['idEstadoPlan'] = 3;
							}
							array_push($arrayPlanObra, $dataCMO);
						}
					}

					$dataSolicitud = array();
					$dataSolicitud['usuario_valida'] = $this->session->userdata('idPersonaSession');
					$dataSolicitud['fecha_valida'] = $this->fechaActual();
					$dataSolicitud['estado'] = 2;
			
					$dataSolicitud['cesta'] = $primerCesta;
					$dataSolicitud['orden_compra'] = $primerOC;

                    
					$data = $this->m_itemplan_registro_oc_solicitud->createOCAndAsiento($arrayPlanObra, $dataSolicitud, $solicitud, $tipoPlanta_global, $itemplan_global);
					
                   
                    $infoPlan = $this->m_itemplan_registro_oc_solicitud->getInfoItemplan($itemplan_global);
                    $infoPlan['ceco'];
                    $infoPlan['cuenta'];
                    $infoPlan['area_funcional'];
                    $infoPlan['idFase'];
                    $infoPlan['idSubProyecto'];


                  
                   

                    $lineaopex=$this->m_itemplan_registro_oc_solicitud->getLineaOPexCapturar($infoPlan['ceco'],$infoPlan['cuenta'],$infoPlan['area_funcional'],$infoPlan['idFase'],$infoPlan['idSubProyecto']);
                     $monto_real_capturado=$lineaopex['monto_real']; 
                    $lineaopex['idlineaopex_fase'];

                           

                    $costo_unitario=$this->m_itemplan_registro_oc_solicitud->actualizacionMonto($itemplan_global,$solicitud);
                    $propuesta=$costo_unitario['costo_unitario_mo'];

                    
                    $valor_actualizado_monto=$monto_real_capturado-$propuesta;
                    

                    $this->m_itemplan_registro_oc_solicitud->actualizarMontoReal($lineaopex['idlineaopex_fase'],$valor_actualizado_monto);




                    // $this->m_itemplan_registro_oc_solicitud->UpdateItemplan($itemplaArray);

				} else {
					throw new Exception('No se pudo procesar el archivo, refresque la pagina y vuelva a intentarlo.');
				}
                //}
            } else {
                throw new Exception('Su sesion expiro, porfavor vuelva a logearse.');
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function UpdateEstadoItemplan($itemplan) {
        $idEstadoPlan = $this->m_itemplan_registro_oc_solicitud->getItemplanId($itemplan);
        if ($idEstadoPlan === 8 || $idEstadoPlan === '8') {
            $idEstadoPlan = ID_ESTADO_DISENIO;
        } else {
            $idEstadoPlan = $idEstadoPlan;
        }
        return $idEstadoPlan;
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

    /*     * *********************************** */

    public function registrarPoDisenoManual() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $listaPOCreados = array();
            $idUsuario = 4; //jose aranda
            if ($idUsuario != null) {

                $listaItems = array();
                foreach ($listaItems as $itm) {
                    $itemplan = $itm;
                    $idEstacion = 1; //DISENO
                    $jsonDataFile = $this->input->post('jsonDataFile');
                    $from = 2; //DISENO
                    //   $arrayFile = json_decode($jsonDataFile);
                    $arrayFinalUpdate = array();
                    $arrayFinalInsert = array();
                    //    log_message('error', print_r('1.$idEstacion:'.$idEstacion, true));
                    //     log_message('error', print_r('aqui', true));
                    //      if($arrayFile!=null){
                    //          log_message('error', print_r('aqui2', true));
                    $codigoPO = $this->m_utils->getCodigoPO($itemplan);
                    if ($codigoPO == null) {
                        throw new Exception('Hubo un error al generar el codigo PO ');
                    }
                    //     log_message('error', print_r('aqui3', true));
                    $costoTotalPOMO = 0;
                    $idEecc = 0;

                    $infoEECC = $this->m_itemplan_registro_oc_solicitud->getEeccDisenoOperaByItemPlan($itemplan);
                    if ($infoEECC != null) {
                        if ($infoEECC['jefatura'] == 'LIMA' && $idEstacion == 4) {
                            $idEecc = $infoEECC['idEmpresaColabFuente'];
                        } else {
                            if ($from == 1) {
                                if ($infoEECC['idEmpresaColab'] != null) {
                                    $idEecc = $infoEECC['idEmpresaColab'];
                                }
                            } else if ($from == 2) {
                                if ($infoEECC['idEmpresaColabDiseno'] != null) {
                                    $idEecc = $infoEECC['idEmpresaColabDiseno'];
                                }
                            }
                        }
                    }
                    $infoPartida = $this->m_itemplan_registro_oc_solicitud->getPartidasByProyectoEstacionPODiseno($itemplan, ID_ESTACION_DISENIO);
                    $dataCMO = array();
                    $dataCMO['codigo_po'] = $codigoPO;
                    $dataCMO['idPartida'] = 441; //PARTIDA COTIZACION
                    $dataCMO['idPrecioDiseno'] = 1;
                    $dataCMO['idEmpresaColab'] = $idEecc;
                    $dataCMO['idZonal'] = $infoEECC['idZonal'];
                    $dataCMO['cantidad'] = 1;
                    $dataCMO['baremo'] = $infoPartida['baremo'];
                    $dataCMO['costo'] = $infoPartida['costo'];
                    $dataCMO['total'] = ($infoPartida['baremo'] * $infoPartida['costo']);
                    array_push($arrayFinalInsert, $dataCMO);
                    $costoTotalPOMO = $costoTotalPOMO + $dataCMO['total'];

                    // log_message('error', print_r('2.$idEstacion:'.$idEstacion, true));
                    $dataPO = array(
                        'itemplan' => $itemplan,
                        'codigo_po' => $codigoPO,
                        'estado_po' => PO_REGISTRADO, //ESTADO REGISTRADO
                        'idEstacion' => $idEstacion,
                        'from' => $from,
                        'costo_total' => $costoTotalPOMO,
                        'idUsuario' => $idUsuario,
                        'fechaRegistro' => $this->fechaActual(),
                        'estado_asig_grafo' => 0,
                        'flg_tipo_area' => 2, //MANO DE OBRA
                        'id_eecc_reg' => $idEecc
                    );
                    // log_message('error', print_r('3.$idEstacion:'.$idEstacion, true));
                    $dataLogPO = array(
                        'codigo_po' => $codigoPO,
                        'itemplan' => $itemplan,
                        'idUsuario' => $idUsuario,
                        'fecha_registro' => $this->fechaActual(),
                        'idPoestado' => PO_REGISTRADO,
                        'controlador' => (($from == 1) ? 'consulta' : 'diseno')
                    );

                    $subProyectoEstacion = $this->m_itemplan_registro_oc_solicitud->getSubProyectoEstacionByItemplanEstacionCotizacion($itemplan, $idEstacion);
                    //  log_message('error', print_r('4.$idEstacion:'.$idEstacion, true));
                    if ($subProyectoEstacion == null) {
                        throw new Exception('Hubo un error obtener el subproyecto - estacion');
                    }
                    $dataDetalleplan = array('itemPlan' => $itemplan,
                        'poCod' => $codigoPO,
                        'idSubProyectoEstacion' => $subProyectoEstacion);

                    //log_message('error', print_r($dataDetalleplan, true));
                    $data = $this->m_itemplan_registro_oc_solicitud->createPoMODiseno($dataPO, $dataLogPO, $dataDetalleplan, $arrayFinalInsert);
                    if ($data['error'] == EXIT_ERROR) {
                        throw new Exception('Hubo un error interno, por favor volver a intentar.');
                    }
                    $data['codigoPO'] = $codigoPO;
                    //    array_push($listaPOCreados, $codigoPO.'|'.$itm);
                    $data['error'] = EXIT_SUCCESS;
                    log_message('error', $codigoPO . '|' . $itm);
                }

                //  log_message('error', print_r($listaPOCreados, true));
            } else {
                throw new Exception('Su sesion expiro, porfavor vuelva a logearse.');
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

}
