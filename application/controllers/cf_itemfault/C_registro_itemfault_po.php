<?php

defined('BASEPATH') or exit('No direct script access allowed');

class C_registro_itemfault_po extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_detalle_obra/m_registro_po_mo');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_itemfault/m_detalle_itemfault');
        $this->load->model('mf_itemfault/m_registro_itemfault_po');
        $this->load->library('lib_utils');
        $this->load->library('excel');
        $this->load->helper('url');
    }

    public function index() {
        $logedUser = $this->session->userdata('usernameSession');
        $idEcc = $this->session->userdata('eeccSession');


        if ($logedUser != null) {

            $item = (isset($_GET['item']) ? $_GET['item'] : '');
            $est = (isset($_GET['form']) ? $_GET['form'] : '');
            $idEstacion = (isset($_GET['estacion']) ? $_GET['estacion'] : '');
            $estaciondesc = (isset($_GET['estaciondesc']) ? $_GET['estaciondesc'] : '');
            $idArea = (isset($_GET['area']) ? $_GET['area'] : '');
            $tipoPo = (isset($_GET['tipo_po']) ? $_GET['tipo_po'] : '');

            $data['item'] = $item;
            $data['form'] = $est;
            $data['tipo_po'] = $tipoPo;

            if ($item != null && $est != null) {

                $dataArrayIP = $this->m_detalle_itemfault->getDetalleItemfault($item);

                $this->session->set_userdata(
                        array('itemfault_temp' => $item,
                            'idEstacion_temp' => $idEstacion,
                            'idEmpresaColab' => $dataArrayIP['idEmpresaColab'],
                            'from_temp' => $est,
                            'idServicioElemento' => $dataArrayIP['idServicioElemento'],
                            'idArea' => $idArea,
                            'tipoPo' => $tipoPo
                ));
            }

            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $permisos = $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_REPORTES_V, ID_PERMISO_HIJO_REG_INDIVIDUAL_PO);

            $data['title'] = 'REGISTRO INDIVIDUAL PO: ' . $estaciondesc;

            $data['opciones'] = $result['html'];
            $this->load->view('vf_itemfault/v_registro_itemfault_po', $data);
        } else {
            redirect('login', 'refresh');
        }
    }

    public function getExcelPOMatItemfault() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $idServicioElemento = $this->session->userdata('idServicioElemento');
            $idEstacion = $this->session->userdata('idEstacion_temp');

            $arrayMateriales = $this->m_detalle_itemfault->getkitMateriales($idServicioElemento, $idEstacion);

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
                    $titulosColumnas = array('CODIGO', 'MATERIAL', 'UM', 'COSTO', 'KIT MAT', 'CANTIDAD INGRESADA');

                    $this->excel->setActiveSheetIndex(0);

                    // Se agregan los titulos del reporte
                    $this->excel->setActiveSheetIndex(0)
                            ->setCellValue('A1', utf8_encode($titulosColumnas[0]))
                            ->setCellValue('B1', utf8_encode($titulosColumnas[1]))
                            ->setCellValue('C1', utf8_encode($titulosColumnas[2]))
                            ->setCellValue('D1', utf8_encode($titulosColumnas[3]))
                            ->setCellValue('E1', utf8_encode($titulosColumnas[4]))
                            ->setCellValue('F1', utf8_encode($titulosColumnas[5]));

                    foreach ($arrayMateriales as $row) {
                        $contador++;
                        $this->excel->getActiveSheet()->setCellValue("A{$contador}", $row->codigo_material)
                                ->setCellValue("B{$contador}", $row->descrip_material)
                                ->setCellValue("C{$contador}", $row->unidad_medida)
                                ->setCellValue("D{$contador}", $row->costo_material)
                                ->setCellValue("E{$contador}", $row->cant_kit_material);
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
                    $archivo = "modelo_carga_registro_individual_po.xls";
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="' . $archivo . '"');
                    header('Cache-Control: max-age=0');
                    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                    //Hacemos una salida al navegador con el archivo Excel.
                    $objWriter->save(PATH_FILE_UPLOAD_DETALLE_MATPO);

                    $data['rutaExcel'] = PATH_FILE_UPLOAD_DETALLE_MATPO;
                    $data['error'] = EXIT_SUCCESS;
                } else {
                    $data['msj'] = "No hay kit de materiales para esta estaci&oacute;n !!, porfavor conmunicarse con dise&ntilde;o";
                }
            } else {
                $data['msj'] = "No hay kit de materiales para esta estaci&oacute;n !!, porfavor conmunicarse con dise&ntilde;o";
            }
        } catch (Exception $e) {
            $data['msj'] = 'Error interno, al crear archivo kit materiales';
        }
        // return $data;
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function cargarArchivoPOMatItemfault() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $file = $_FILES["file"]["name"];
            $filetype = $_FILES["file"]["type"];
            $filesize = $_FILES["file"]["size"];
            $path = $_FILES["file"]["tmp_name"];

            $object = PHPExcel_IOFactory::load($path);

            $itemfault = $this->session->userdata('itemfault_temp') ? $this->session->userdata('itemfault_temp') : null;
            $idEstacion = $this->session->userdata('idEstacion_temp') ? $this->session->userdata('idEstacion_temp') : null;
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;

            $idServicioElemento = $this->session->userdata('idServicioElemento');
            $idEmpresaColab = $this->session->userdata('idEmpresaColab');


            if ($itemfault == null || $idEstacion == null || $idEmpresaColab == null || $idUsuario == null || $idServicioElemento == null) {
                throw new Exception('ND111');
            }


            $file2 = utf8_decode($file);
            $ubicacion = 'uploads/registro_individual_po';
            if (!is_dir($ubicacion)) {
                mkdir('uploads/registro_individual_po', 0777);
            }
            if (move_uploaded_file($_FILES['file']['tmp_name'], $ubicacion . "/" . $file2)) {
                //log_message('error', 'subio el archivo');
            } else {
                throw new Exception('ND2');
            }

            $arrayError = array();

            foreach ($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();
                for ($row = 2; $row <= $highestRow; $row++) {

                    $motivo = '';
                    $codMaterial = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                    $cantidad_ingresada = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                    _log($cantidad_ingresada);
                    if ($cantidad_ingresada !== null) {
                        _log($cantidad_ingresada);
                        $existeMaterial = $this->m_utils->countMaterial($codMaterial);
                        $exitMatKit = $this->m_detalle_itemfault->countMatxKitItemfault($codMaterial, $idServicioElemento, $idEstacion);
                        $detalleMat = $this->m_detalle_itemfault->getDetalleMaterial($codMaterial, $idServicioElemento, $idEstacion);
                        $flgMatPhaseOut = $this->m_detalle_itemfault->countMatPhaseOut($codMaterial);


                        if (is_array($detalleMat)) {
                            $desc_material = $detalleMat['descrip_material'];
                            $unidad_medida = $detalleMat['unidad_medida'];
                            $cantidad_kit = $detalleMat['cant_kit_material'];
                            $factorMaxPorcen = $detalleMat['factor_porcentual'];
                            $costo_material = $detalleMat['costo_material'];
                            $costo_total = floatval($cantidad_ingresada) * floatval($costo_material);
                            $flgTipoMat = $detalleMat['flg_tipo'];
                        } else {
                            $desc_material = '-';
                            $unidad_medida = '-';
                            $cantidad_kit = '-';
                            $factorMaxPorcen = '-';
                            $costo_material = '-';
                            $costo_total = '-';
                            $flgTipoMat = '-';
                        }

                        $flgValida = 0;

                        // if ($countCodPO > 1) {
                        //     $motivo .= 'Solo puede tener m&aacute;s de dos PO por estaci&oacute;n, ';
                        //     $flgValida = 1;
                        // }

                        if ($existeMaterial == 0) {
                            $motivo .= 'No existe material, ';
                            $flgValida = 1;
                        } else {
                            if ($exitMatKit == 0 && $flgTipoMat == 0) {
                                $motivo .= 'Material no pertenece al kit, ';
                                $flgValida = 1;
                            } else if ($flgTipoMat == 1) {// SI ES BUCLE
                                if ($flgMatPhaseOut > 0) {
                                    $motivo .= 'Material Phase Out, ';
                                    $flgValida = 1;
                                }
                            }
                        }

                        if ($codMaterial == null) {
                            $motivo .= 'No tiene c&oacute;digo de material, ';
                            $flgValida = 1;
                        }
                        if ($cantidad_ingresada == null || $cantidad_ingresada == '' || !is_numeric($cantidad_ingresada)) {
                            // $motivo .= 'No tiene cantidad.';
                            $flgValida = 1;
                            $cantidad_ingresada = 0;
                        }

                        $arrayError = $this->pushErrorExcel($codMaterial, $cantidad_ingresada, $desc_material, $unidad_medida, $cantidad_kit, $factorMaxPorcen, $costo_material, $costo_total, $motivo, $flgTipoMat, $arrayError);
                    }
                }
            }
            list($tabla, $arrayMat, $costoTotalGlob, $countError) = $this->getTablaExcelPO($arrayError);

            $data['tablaError'] = $tabla;
            $data['arrayMat'] = $arrayMat;
            $data['costoTotalGlob'] = $costoTotalGlob;
            $data['countError'] = $countError;
            $data['jsonDataFIle'] = null;
            $data['jsonDataFIleValido'] = null;

            if ($data['tablaError'] != null && $data['arrayMat'] != null) {
                $data['error'] = EXIT_SUCCESS;
            } else {
                throw new Exception('ND3');
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

    public function getTablaExcelPO($arrayError) {
        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th colspan="1">CODIGO</th>
                            <th colspan="1">MATERIAL</th>
                            <th colspan="1">UDM</th>
                            <th colspan="1">CANT. KIT</th>
                            <th colspan="1">COSTO</th>
                            <th colspan="1">CANTIDAD</th>
                            <th colspan="1">COSTO TOTAL</th>
                            <th colspan="1">OBSERVACION</th>
                            <th colspan="1">ACCION</th>
                        </tr>
                    </thead>
                    <tbody>';

        $count = 0;
        $costoTotalGlob = 0;
        $countError = 0;
        $style = '';

        foreach ($arrayError as $row) {

            $style = '';

            if ($row['motivo'] != '') {
                $htmlColorFila = 'style="background:#FDBDBD"';
                $htmlIconoDelete = '<a style="cursor:pointer;color: #999966" data-posicion="' . $count . '"  onclick="deleteMatErroneo(this)"><i class="zmdi zmdi-hc-2x zmdi-delete"></i></a>';
                $countError++;
            } else {
                $htmlColorFila = '';
                $htmlIconoDelete = '';
            }

            if (is_numeric($row['cantidad_kit']) && is_numeric($row['factor_porcentual'])) {
                $cant_evaluar = ($row['cantidad_kit'] * $row['factor_porcentual']) / 100;

                if ($row['cantidad_ingresada'] > $row['cantidad_kit']) {
                    $style = ' color: #ff6600';
                } else if ($row['cantidad_ingresada'] < $cant_evaluar) {
                    $style = 'color: #00b300';
                } else {
                    $style = '';
                }
            }
            $html .= '   <tr ' . $htmlColorFila . '>
                            <td>' . $row['codigo_material'] . '</td>
                            <td>' . utf8_decode($row['descrip_material']) . '</td>
                            <td style="text-align:center">' . $row['unidad_medida'] . '</td>
                            <th style="text-align:center">' . $row['cantidad_kit'] . '</th>
                            <th style="text-align:center">' . (is_numeric($row['costo_material']) ? number_format($row['costo_material'], 2) : '-') . '</th>
                            <th style="text-align:center;font-weight: bold;' . $style . '">' . $row['cantidad_ingresada'] . '</th>
                            <th style="text-align:center">' . (is_numeric($row['costo_total']) ? number_format($row['costo_total'], 2) : '-') . '</th>
                            <th>
                                ' . $row['motivo'] . '
                            </th>
                            <th style="text-align:center">' . $htmlIconoDelete . '</th>
                        </tr>';
            if ($row['costo_total'] != '-') {
                $costoTotalGlob = round($costoTotalGlob + $row['costo_total'], 2);
            }
            $count++;
        }
        $html .= '</tbody>
                  <tfoot>
                  <tr>
                     <th colspan="6" style="background: var(--celeste_telefonica)"></td>
                     <th colspan="1" style="font-weight: bold; text-align:center">' . number_format($costoTotalGlob, 2) . '</td>
                     <th colspan="2" style="background: var(--celeste_telefonica)"></td>
                  </tr>
                  </tfoot>
            </table>';
        return array(utf8_decode($html), $arrayError, $costoTotalGlob, $countError);
    }

    public function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone', 'America/Lima');
        setlocale(LC_TIME, "es_ES", "esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }

    public function pushErrorExcel($codMaterial, $cantidad_ingresada, $desc_material, $unidad_medida, $cantidad_kit, $factorMaxPorcen, $costo_material, $costo_total, $motivo, $flgTipoMat, $arrayError) {
        $jsonError['codigo_material'] = $codMaterial;
        $jsonError['descrip_material'] = $desc_material;
        $jsonError['unidad_medida'] = $unidad_medida;
        $jsonError['cantidad_kit'] = $cantidad_kit;
        $jsonError['factor_porcentual'] = $factorMaxPorcen;
        $jsonError['costo_material'] = $costo_material;
        $jsonError['cantidad_ingresada'] = $cantidad_ingresada;
        $jsonError['costo_total'] = $costo_total;
        $jsonError['motivo'] = $motivo;
        $jsonError['flg_tipo'] = $flgTipoMat;

        array_push($arrayError, $jsonError);
        return $arrayError;
    }

    public function deleteMatErroneo() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $posicion = $this->input->post('posicion') ? $this->input->post('posicion') : null;
            $arrayMateriales = $this->input->post('arrayMateriales') ? $this->input->post('arrayMateriales') : null;

            if ($posicion != null && is_array($arrayMateriales)) {
                list($tabla, $arrayMat, $costoTotalGlob, $countError) = $this->getTablaExcelPO($arrayMateriales);
                $data['tablaError'] = $tabla;
                $data['arrayMat'] = $arrayMat;
                $data['costoTotalGlob'] = $costoTotalGlob;
                $data['countError'] = $countError;
                if ($data['tablaError'] != null && $data['arrayMat'] != null) {
                    $data['error'] = EXIT_SUCCESS;
                }
            } else {
                $data['msj'] = "Hubo un error al eliminar el material seleccionado!!";
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

    public function registPOIteamfault() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $itemfault = $this->session->userdata('itemfault_temp') ? $this->session->userdata('itemfault_temp') : null;
            $from = $this->session->userdata('from_temp') ? $this->session->userdata('from_temp') : null;
            $idEstacion = $this->session->userdata('idEstacion_temp') ? $this->session->userdata('idEstacion_temp') : null;
            $idArea = $this->session->userdata('idArea') ? $this->session->userdata('idArea') : null;
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;
            $arrayMat = $this->input->post('arrayMateriales') ? $this->input->post('arrayMateriales') : null;
            $costoTotalMat = $this->input->post('costoTotalMat') ? $this->input->post('costoTotalMat') : null;
            $idServicioElemento = $this->session->userdata('idServicioElemento') ? $this->session->userdata('idServicioElemento') : null;
            $countPo = $this->m_detalle_itemfault->getCountPoItemfault($itemfault, $idEstacion, $idArea);

            if ($countPo >= 1) {// HFC Y OBRA PUB. PUEDE CREAR MAS POs.
                throw new Exception('No se puede ingresar m&aacute;s de una PO.');
            }

            $rowCostos = $this->m_detalle_itemfault->getCostosItemfault($itemfault);

            if ($rowCostos['montoMat'] < $costoTotalMat) {
                $data['itemfault'] = $itemfault;
                $data['montoSuperado'] = 1;
                $data['montoMo'] = $rowCostos['montoMat'];
                $data['costoTotalPOMO'] = $costoTotalMat;
                throw new Exception('El costo de la PO de MAT supera al costo de la cotizacion.');
            }

            $idEmpresaColab = $this->session->userdata('idEmpresaColab') ? $this->session->userdata('idEmpresaColab') : null;
            $msjValidacion = '';

            $idEecc = $idEmpresaColab;



            $this->db->trans_begin();


            if ($idEecc == NULL || $idEecc == '') {
                throw new Exception('No tiene una EECC DISE&Ntilde;O, Comunicarse con el administrador!!');
            }
            if ($itemfault == null || $arrayMat == null || $idEstacion == null || $idUsuario == null || $idEecc == null || $idServicioElemento == null || $idArea == null) {
                throw new Exception('No se cargaron correctamente los datos para el registro');
            }

            // $countCodPO = $this->m_detalle_obra->countPOByItemplanAndEstacion($itemplan, $idEstacion, $tm);
            // $idSubProyectoEstacion = $this->m_utils->getIdSubProyectoEstacionByItemplanAndEstacion($itemplan, $idEstacion, 'MAT');

            $codigoPO = $this->m_utils->getCodigoPOItemfault($itemfault);

            if ($codigoPO == null) {
                throw new Exception('Hubo un error al generar el codigo PO ');
            }

            $arrayInsertPO = array(
                "itemfault" => $itemfault,
                "codigo_itemfault_po" => $codigoPO,
                "estado_po" => 1,
                "idEstacion" => $idEstacion,
                "costo_total" => $costoTotalMat,
                "id_usuario" => $idUsuario,
                "fecha_registro" => $this->fechaActual(),
                "idArea" => $idArea,
                "idEmpresaColab" => $idEecc,
                "flg_tipo_area" => 1
            );





            if ($costoTotalMat != null && $costoTotalMat > 0) {

                $idOpex = $this->m_detalle_itemfault->getIdOpex();

                $arrayInsertTransccion = array(
                    "idTransaccion" => null,
                    "idOpex" => $idOpex,
                    "montoTransaccion" => $costoTotalMat,
                    "codigo" => $codigoPO,
                    "fechaTransaccion" => $this->fechaActual(),
                    "id_usuario" => $idUsuario,
                    "estadoTransaccion" => 1,
                    "tipo" => 1
                );

                $data = $this->m_detalle_itemfault->insertarPOItemfault($arrayInsertPO);
                $this->m_detalle_itemfault->insertarTransaccion($arrayInsertTransccion);
                $this->m_detalle_itemfault->updateTransaccion($costoTotalMat, $idOpex);
            } else {
                throw new Exception('Solo se registraran PO con cantidades mayores a 0!!');
            }

            $arrayInsertDetallePOGlob = array();

            if ($data['error'] == EXIT_SUCCESS) {
                foreach ($arrayMat as $row) {
                    $exitMatKit = $this->m_detalle_itemfault->countMatxKitItemfault($row['codigo_material'], $idServicioElemento, $idEstacion);
                    if ($row['flg_tipo'] == 0) { //NO BUCLE
                        if ($exitMatKit > 0) {
                            if ($row['codigo_material'] != null && $row['codigo_material'] != '' && $row['cantidad_ingresada'] != 0 && $row['motivo'] == '' && $row['cantidad_kit'] != '-' && $row['factor_porcentual'] != '-' && $row['costo_material'] != '-') {
                                $arrayDetallePOTemp = array(
                                    "codigo_itemfault_po" => $codigoPO,
                                    "codigo_material" => $row['codigo_material'],
                                    "cantidad_ingreso" => $row['cantidad_ingresada'],
                                    "cantidad_final" => $row['cantidad_ingresada'],
                                    "costo_material" => $row['costo_material']
                                );

                                array_push($arrayInsertDetallePOGlob, $arrayDetallePOTemp);
                            }
                        }
                    } else if ($row['flg_tipo'] == 1) { // BUCLE
                        if ($row['codigo_material'] != null && $row['codigo_material'] != '' && $row['cantidad_ingresada'] != 0 && $row['motivo'] == '' && $row['cantidad_kit'] != '-' && $row['factor_porcentual'] != '-' && $row['costo_material'] != '-') {
                            $arrayDetallePOTemp = array(
                                "codigo_itemfault_po" => $codigoPO,
                                "codigo_material" => $row['codigo_material'],
                                "cantidad_ingreso" => $row['cantidad_ingresada'],
                                "cantidad_final" => $row['cantidad_ingresada'],
                                "costo_material" => $row['costo_material']
                            );

                            array_push($arrayInsertDetallePOGlob, $arrayDetallePOTemp);
                        }
                    }
                }
                if (count($arrayInsertDetallePOGlob) > 0) {
                    $data = $this->m_detalle_itemfault->insertarDetallePOItemfault($arrayInsertDetallePOGlob);
                } else {
                    $data['error'] = EXIT_ERROR;
                    throw new Exception('Ningun material es valido para el registro!!');
                }

                if ($data['error'] == EXIT_SUCCESS) {
                    $arrayInsertLOGPO = array(
                        "codigo_iteamfault_po" => $codigoPO,
                        "itemfault" => $itemfault,
                        "id_usuario" => $idUsuario,
                        "fecha_registro" => $this->fechaActual(),
                        "estado_po" => 1
                    );
                    $data = $this->m_detalle_itemfault->insertarLOGPOItemfault($arrayInsertLOGPO);
                    if ($data['error'] == EXIT_SUCCESS) {
                        $arrayInsertLOGPO_dos = array(
                            "codigo_iteamfault_po" => $codigoPO,
                            "itemfault" => $itemfault,
                            "id_usuario" => $idUsuario,
                            "fecha_registro" => $this->fechaActual(),
                            "estado_po" => 1
                        );
                        $data = $this->m_detalle_itemfault->insertarLOGPOItemfault($arrayInsertLOGPO_dos);
                        if ($data['error'] == EXIT_SUCCESS) {
                            $this->db->trans_commit();
                            $data['codidoPO'] = $codigoPO;
                            $data['itemfault'] = $itemfault;
                        } else {
                            throw new Exception($data['msj']);
                        }
                    } else {
                        throw new Exception($data['msj']);
                    }
                } else {
                    throw new Exception($data['msj']);
                }
            } else {
                throw new Exception($data['msj']);
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function getExcelPOMoItemfault() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $itemfault = $this->session->userdata('itemfault_temp');
            $idEstacion = $this->session->userdata('idEstacion_temp');
            _log($itemfault);
            _log($idEstacion);
            $arrayMateriales = $this->m_registro_itemfault_po->getPartidasByItemfault($itemfault, $idEstacion);
            _log(print_r($arrayMateriales, true));
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
                    $titulosColumnas = array('CODIGO', 'PARTIDA', 'TIPO', 'COSTO', 'BAREMO', 'CANTIDAD INGRESADA');

                    $this->excel->setActiveSheetIndex(0);

                    // Se agregan los titulos del reporte
                    $this->excel->setActiveSheetIndex(0)
                            ->setCellValue('A1', utf8_encode($titulosColumnas[0]))
                            ->setCellValue('B1', utf8_encode($titulosColumnas[1]))
                            ->setCellValue('C1', utf8_encode($titulosColumnas[2]))
                            ->setCellValue('D1', utf8_encode($titulosColumnas[3]))
                            ->setCellValue('E1', utf8_encode($titulosColumnas[4]))
                            ->setCellValue('F1', utf8_encode($titulosColumnas[5]));

                    foreach ($arrayMateriales as $row) {

                        $costoEnd = $row->costo;
                        /*                         * *************************NUEVO PEDIDO OWEN 06.06.2019************************** */
                        if (trim($row->codigo) == '23108-8') {//log_message('error', 'ES IGUAL...');
                            $newCosto = $this->m_utils->getCostoFoPartidasByItemplan($itemplan);
                            if ($newCosto != null) {
                                $costoEnd = $newCosto['costo'];
                            }
                        }
                        /*                         * *************************************************** */
                        $contador++;
                        $this->excel->getActiveSheet()->setCellValue("A{$contador}", $row->codigo)
                                ->setCellValue("B{$contador}", $row->descripcion)
                                ->setCellValue("C{$contador}", $row->descPrecio)
                                ->setCellValue("D{$contador}", $costoEnd)
                                ->setCellValue("E{$contador}", $row->baremo);
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
                    $archivo = "partidas_mo_registro_individual_po.xls";
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="' . $archivo . '"');
                    header('Cache-Control: max-age=0');
                    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                    //Hacemos una salida al navegador con el archivo Excel.
                    $objWriter->save(PATH_FILE_UPLOAD_DETALLE_MATPO);

                    $data['rutaExcel'] = PATH_FILE_UPLOAD_DETALLE_MATPO;
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

    public function cargarArchivoPOMoItemfault() {
        $data ['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $itemfault = $this->session->userdata('itemfault_temp');
            $idEstacion = $this->session->userdata('idEstacion_temp');

            // $arrayMateriales = $this->m_registro_itemfault_po->getPartidasByItemfault($itemfault, $idEstacion);		

            $uploaddir = 'uploads/po_mo/'; //ruta final del file
            $uploadfile = $uploaddir . basename($_FILES['file']['name']);

            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {

                $objPHPExcel = PHPExcel_IOFactory::load($uploadfile);

                $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
                $row_dimension = $objPHPExcel->getActiveSheet()->getHighestRowAndColumn();

                $info_2 = $this->makeHTMLBodyTable($row_dimension, $objPHPExcel, $itemfault, $idEstacion);
                $data['tablaError'] = $info_2['html'];
                $data['jsonDataFIleValido'] = json_encode($info_2['array']);
                $data['jsonDataFIle'] = json_encode($info_2['array_full']);
                $this->session->set_userdata('sirope_NF', $info_2['array_nf']);
                $data['total_final'] = $info_2['total_final'];
                $data['arrayMat'] = null;
                $data['costoTotalGlob'] = $info_2['total_final'];
                ;
                $data['countError'] = null;
                $data['error'] = EXIT_SUCCESS;
            } else {
                throw new Exception('Hubo un problema con la carga del archivo al servidor, comuniquese con el administrador.');
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function makeHTMLBodyTable($row_dimension, $objPHPExcel, $itemplan, $idEstacion) {
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

        $html .= '
					<table style="font-size: 10px" id="data-table2" class="table table-bordered">
                    	<thead class="thead-default">                        
                           <tr role="row">
                                <th colspan="1"></th> 
                                <th colspan="1">CODIGO</th> 
                                <th colspan="1">DESCRIPCION</th>  
                                <th colspan="1">TIPO</th>                        
                                <th colspan="1">COSTO</th>          
                                <th colspan="1">BAREMO</th> 
                                <th colspan="1">CANTIDAD INGRESADA</th> 
                                <th colspan="1">TOTAL</th>
                                <th colspan="1">SITUACION</th>                      
                            </tr>
                        </thead>  
                        <tbody id="contBodyTable">';
        for ($i = 1; $i <= $row_dimension['row']; $i++) {//COMIENZA DESDE LA FILA 1
            $A = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, $i, true)->getValue();
            $B = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1, $i, true)->getValue();
            $C = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2, $i, true)->getValue();
            $D = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(3, $i, true)->getValue();
            $E = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(4, $i, true)->getValue();
            $F = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(5, $i, true)->getValue();
            $total = 0;
            if ($F != '' && $A != 'CODIGO') {
                if (is_numeric($F)) {
                    $infoPartida = $this->m_registro_itemfault_po->getInfoPartidasByCod($itemplan, $idEstacion, $A);
                    if ($infoPartida != null) {
                        $D = $infoPartida['costo'];
                        $color_row = "white";
                        $situacion = 'OK';

                        $E = $infoPartida['baremo'];
                        $total = ($D * $E * $F);
                        $pre_array = array($A, $B, $C, $D, $E, $F, $infoPartida['idActividad'], $total, 1); //1 == insert
                        array_push($array_valido, $pre_array);
                        $indice_valido = 'data-indice_val="' . $cont_indice_valido . '"';
                        $cont_indice_valido++;
                        $total_final = $total_final + $total;
                    } else {
                        $color_row = "#b3b37b";
                        $situacion = 'PARTIDA NO PERMITIDA';
                        $pre_array = array($A, $B, $C, $D, $E, $F, 2); //2 == erroneos
                        array_push($array_not_found, $pre_array);
                        log_message('error', 'Partida no asociada la proyecto - estacion' . $A);
                    }
                } else {
                    $color_row = "#b3b37b";
                    $situacion = 'CANTIDAD INGRESADA INVALIDAD';
                    $pre_array = array($A, $B, $C, $D, $E, $F, 2); //2 == erroneos
                    array_push($array_not_found, $pre_array);
                }





                $html .= '
						<tr id="tr' . $indice . '" style="background-color:' . $color_row . '">
                            <th style="width: 5px;"><a style="cursor:pointer;" ' . $indice_valido . ' data-indice="' . $indice . '" onclick="removeTR(this)"><img class="delete_ptr" alt="Eliminar" height="20px" width="20px" src="public/img/iconos/delete.png"></a></th>
                        	<th style="color:black">' . $A . '</th>
                        	<th style="color:black">' . utf8_decode($B) . '</th>
                        	<th style="color:black">' . $C . '</th>
                    	    <th style="color:black">' . $D . '</th>
                	        <th style="color:black">' . $E . '</th>
            	            <th style="color:black">' . $F . '</th>
        	                <th style="color:black">' . number_format($total, 2, '.', ',') . '</th>
        	                <th style="color:black">' . $situacion . '</th>
                    	</tr>';

                $indice++;

                $pre_array = array($A, $B, $C, $D, $E, $F);
                array_push($array_full, $pre_array);
            }
        }
        $html .= '</tbody>
            
                <tfoot>
                  <tr>
                                <th colspan="1"></th> 
                                <th colspan="1"></th> 
                                <th colspan="1"></th>    
                                <th colspan="1"></th>                        
                                <th colspan="1"></th>          
                                <th colspan="1"></th> 
                                <th colspan="1"><b>TOTAL</b></th> 
                                <th colspan="1"><b>' . number_format($total_final, 2) . '</b></th>    
                                <th colspan="1"></th>                    
                  </tr>
                  </tfoot>
				</table>';
        $data['html'] = $html;
        $data['array'] = $array_valido;
        $data['array_nf'] = $array_not_found;
        $data['array_full'] = $array_full;
        $data['total_final'] = number_format($total_final, 2, '.', ',');
        return $data;
    }

    function registPOMoIteamfault() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        $data['montoSuperado'] = null;
        $data['montoMo'] = null;
        $data['costoTotalPOMO'] = null;
        $data['itemfault'] = null;
        try {
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;
            $itemfault = $this->session->userdata('itemfault_temp') ? $this->session->userdata('itemfault_temp') : null;
            $from = $this->session->userdata('from_temp') ? $this->session->userdata('from_temp') : null;
            $idEstacion = $this->session->userdata('idEstacion_temp') ? $this->session->userdata('idEstacion_temp') : null;
            $idArea = $this->session->userdata('idArea') ? $this->session->userdata('idArea') : null;
            $idEmpresaColab = $this->session->userdata('idEmpresaColab') ? $this->session->userdata('idEmpresaColab') : null;

            $this->db->trans_begin();

            if ($idEmpresaColab == NULL || $idEmpresaColab == '') {
                throw new Exception('No tiene una EECC DISE&Ntilde;O, Comunicarse con el administrador!!');
            }

            if ($itemfault == null || $idEstacion == null || $idUsuario == null || $idEmpresaColab == null || $idArea == null) {
                throw new Exception('No se cargaron correctamente los datos para el registro');
            }
            if ($idUsuario != null) {
                $jsonDataFile = $this->input->post('jsonDataFile');

                $arrayFile = json_decode($jsonDataFile);
                $arrayFinalUpdate = array();
                $arrayInsertDetallePO = array();

                if ($arrayFile != null) {
                    $codigoPO = $this->m_utils->getCodigoPOItemfault($itemfault);

                    if ($codigoPO == null) {
                        throw new Exception('Hubo un error al generar el codigo PO ');
                    }

                    $costoTotalPOMO = 0;
                    $arrayActividades = array();
                    foreach ($arrayFile as $datos) {
                        if ($datos != null) {
                            if ($datos[8] != null) {
                                if ($datos[8] == 1) {//registrar
                                    if (!in_array($datos[6], $arrayActividades)) {
                                        $dataCMO = array();
                                        $dataCMO['codigo_itemfault_po'] = $codigoPO;
                                        $dataCMO['idPartida'] = $datos[6];
                                        $dataCMO['baremo'] = $datos[4];
                                        $dataCMO['costo'] = $datos[3];
                                        $dataCMO['cantidad_inicial'] = $datos[5];
                                        $dataCMO['monto_inicial'] = $datos[7];
                                        $dataCMO['cantidad_final'] = $datos[5];
                                        $dataCMO['monto_final'] = $datos[7];
                                        array_push($arrayInsertDetallePO, $dataCMO);
                                        $costoTotalPOMO = $costoTotalPOMO + $dataCMO['monto_inicial'];
                                        array_push($arrayActividades, $datos[6]); //metemos idActividad
                                    }
                                }
                            }
                        }
                    }

                    $rowCostos = $this->m_detalle_itemfault->getCostosItemfault($itemfault);

                    if ($rowCostos['montoMo'] < $costoTotalPOMO) {
                        $data['itemfault'] = $itemfault;
                        $data['montoSuperado'] = 1;
                        $data['montoMo'] = $rowCostos['montoMo'];
                        $data['costoTotalPOMO'] = $costoTotalPOMO;
                        throw new Exception('El costo de la PO de MO supera al costo de la cotizacion.');
                    }

                    $arrayInsertPO = array(
                        "itemfault" => $itemfault,
                        "codigo_itemfault_po" => $codigoPO,
                        "estado_po" => 1,
                        "idEstacion" => $idEstacion,
                        "costo_total" => $costoTotalPOMO,
                        "id_usuario" => $idUsuario,
                        "fecha_registro" => $this->fechaActual(),
                        "idArea" => $idArea,
                        "idEmpresaColab" => $idEmpresaColab,
                        "flg_tipo_area" => 2
                    );
                    if ($costoTotalPOMO != null && $costoTotalPOMO > 0) {
                        $data = $this->m_detalle_itemfault->insertarPOItemfault($arrayInsertPO);
                    } else {
                        throw new Exception('Solo se registraran PO con cantidades mayores a 0!!');
                    }

                    if ($data['error'] == EXIT_SUCCESS) {
                        $data = $this->m_detalle_itemfault->insertarDetallePOMoItemfault($arrayInsertDetallePO);
                    } else {
                        throw new Exception($data['msj']);
                    }

                    if ($data['error'] == EXIT_SUCCESS) {
                        $arrayInsertLOGPO = array(
                            "codigo_iteamfault_po" => $codigoPO,
                            "itemfault" => $itemfault,
                            "id_usuario" => $idUsuario,
                            "fecha_registro" => $this->fechaActual(),
                            "estado_po" => 1
                        );
                        $data = $this->m_detalle_itemfault->insertarLOGPOItemfault($arrayInsertLOGPO);
                        if ($data['error'] == EXIT_SUCCESS) {
                            $arrayInsertLOGPO_dos = array(
                                "codigo_iteamfault_po" => $codigoPO,
                                "itemfault" => $itemfault,
                                "id_usuario" => $idUsuario,
                                "fecha_registro" => $this->fechaActual(),
                                "estado_po" => 1
                            );
                            $data = $this->m_detalle_itemfault->insertarLOGPOItemfault($arrayInsertLOGPO_dos);
                            if ($data['error'] == EXIT_SUCCESS) {
                                $this->db->trans_commit();
                                $data['codidoPO'] = $codigoPO;
                                $data['itemfault'] = $itemfault;
                            } else {
                                throw new Exception($data['msj']);
                            }
                        } else {
                            throw new Exception($data['msj']);
                        }
                    } else {
                        throw new Exception($data['msj']);
                    }
                } else {
                    throw new Exception('No se pudo procesar el archivo, refresque la pagina y vuelva a intentarlo.');
                }
            } else {
                throw new Exception('Su sesion expiro, porfavor vuelva a logearse.');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

}
