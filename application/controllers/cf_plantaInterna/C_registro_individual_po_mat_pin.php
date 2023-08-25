<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_registro_individual_po_mat_pin extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_plantaInterna/m_detalle_obra_pin');
        $this->load->library('lib_utils');
        $this->load->library('excel');
        $this->load->helper('url');
    }

    public function index()
    {
        $logedUser = $this->session->userdata('usernameSession');
        $idEcc = $this->session->userdata('eeccSession');
        if ($logedUser != null) {

            $item = (isset($_GET['item']) ? $_GET['item'] : '');
            $est = (isset($_GET['form']) ? $_GET['form'] : '');
            $idEstacion = (isset($_GET['estacion']) ? $_GET['estacion'] : '');
            $estaciondesc = (isset($_GET['estaciondesc']) ? $_GET['estaciondesc'] : '');

            $data['item'] = $item;
            $data['form'] = $est;
            if ($item != null && $est != null) {

                $dataArrayIP = $this->m_detalle_obra_pin->getDetalleIP($item);
                $this->session->set_userdata(
                    array('itemplan_temp' => $item,
                        'idEstacion_temp' => $idEstacion,
                        'idEmpresaColab' => $dataArrayIP['idEmpresaColab'],
                        'from_temp' => $est,
                        'idSubProyecto_temp' => $dataArrayIP['idSubProyecto'],
                    ));
            }

            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $permisos = $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_REPORTES_V, ID_PERMISO_HIJO_REG_INDIVIDUAL_PO);

            $data['title'] = 'REGISTRO INDIVIDUAL PO MATERIALES: ' . $estaciondesc;

            $data['opciones'] = $result['html'];
            $this->load->view('vf_plantaInterna/v_registro_individual_po_mat_pin', $data);
        } else {
            redirect('login', 'refresh');
        }
    }

    public function cargarArchivoPO()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $file = $_FILES["file"]["name"];
            $filetype = $_FILES["file"]["type"];
            $filesize = $_FILES["file"]["size"];
            $path = $_FILES["file"]["tmp_name"];

            // $inputFileType = 'CSV';
            // $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            // $objPHPExcel = $objReader->load($path);

            $object = PHPExcel_IOFactory::load($path);

            $itemplan = $this->session->userdata('itemplan_temp') ? $this->session->userdata('itemplan_temp') : null;
            $idEstacion = $this->session->userdata('idEstacion_temp') ? $this->session->userdata('idEstacion_temp') : null;
            $idEmpresaColab = $this->session->userdata('idEmpresaColab') ? $this->session->userdata('idEmpresaColab') : null;
            $idSubProyecto = $this->session->userdata('idSubProyecto_temp') ? $this->session->userdata('idSubProyecto_temp') : null;
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;
			
            if ($itemplan == null || $idEstacion == null || $idEmpresaColab == null || $idUsuario == null || $idSubProyecto == null) {
                throw new Exception('ND1');
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

                    $existeMaterial = $this->m_utils->countMaterial($codMaterial);
                    $exitMatKit = $this->m_detalle_obra_pin->countMatxKit($codMaterial, $idSubProyecto,$idEstacion);
                    $detalleMat = $this->m_detalle_obra_pin->getDetalleMaterial($codMaterial, $idSubProyecto, $idEstacion);
                    $flgMatPhaseOut = $this->m_detalle_obra_pin->countMatPhaseOut($codMaterial);

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
                        }else if ($flgTipoMat == 1) {// SI ES BUCLE
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
            list($tabla, $arrayMat, $costoTotalGlob, $countError) = $this->getTablaExcelPO($arrayError);

            $data['tablaError'] = $tabla;
            $data['arrayMat'] = $arrayMat;
            $data['costoTotalGlob'] = $costoTotalGlob;
            $data['countError'] = $countError;

            if ($data['tablaError'] != null && $data['arrayMat'] != null) {
                $data['error'] = EXIT_SUCCESS;
            } else {
                throw new Exception('ND');
            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

    public function getTablaExcelPO($arrayError)
    {
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
                            <th style="text-align:center">' . (is_numeric($row['costo_total']) ? number_format($row['costo_total']) : '-') . '</th>
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
                     <th colspan="1" style="font-weight: bold; text-align:center">' . number_format($costoTotalGlob) . '</td>
                     <th colspan="2" style="background: var(--celeste_telefonica)"></td>
                  </tr>
                  </tfoot>
            </table>';
        return array(utf8_decode($html), $arrayError, $costoTotalGlob, $countError);
    }

    public function fechaActual()
    {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone', 'America/Lima');
        setlocale(LC_TIME, "es_ES", "esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }

    public function pushErrorExcel($codMaterial, $cantidad_ingresada, $desc_material, $unidad_medida, $cantidad_kit, $factorMaxPorcen, $costo_material, $costo_total, $motivo, $flgTipoMat, $arrayError)
    {
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

    public function deleteMatErroneo()
    {
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

    public function registPO()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $itemplan = $this->session->userdata('itemplan_temp') ? $this->session->userdata('itemplan_temp') : null;
            $from = $this->session->userdata('from_temp') ? $this->session->userdata('from_temp') : null;
            $idSubProyecto = $this->session->userdata('idSubProyecto_temp') ? $this->session->userdata('idSubProyecto_temp') : null;
            $idEstacion = $this->session->userdata('idEstacion_temp') ? $this->session->userdata('idEstacion_temp') : null;
            $idEmpresaColab = $this->session->userdata('idEmpresaColab') ? $this->session->userdata('idEmpresaColab') : null;
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;
            $arrayMat = $this->input->post('arrayMateriales') ? $this->input->post('arrayMateriales') : null;
            $costoTotalMat = $this->input->post('costoTotalMat') ? $this->input->post('costoTotalMat') : null;

            $tm = strlen($from);

            $msjValidacion = '';
            
            $idEecc = null;

            $infoEECC = $this->m_utils->getEeccDisenoOperaByItemPlanPin($itemplan);
            if ($tm == 1) {
                $msjValidacion = 'Operaci&oacute;n';
                if($infoEECC != null){
                    if ($infoEECC['idEmpresaColab'] != null) {
                        $idEecc = $infoEECC['idEmpresaColab'];
                    }
                }
            } else if ($tm == 2) {
                $msjValidacion = 'Dise&ntilde;o';
                if($infoEECC != null){
                    if ($infoEECC['idEmpresaColabDiseno'] != null) {
                        $idEecc = $infoEECC['idEmpresaColabDiseno'];
                    }
                }
            }
            if($idEstacion == 4 && $infoEECC['jefatura'] == 'LIMA'){//ESTACION FUENTE
                $idEecc = $infoEECC['idEmpresaColabFuente'];
                
                if($idEecc == 0){
                    throw new Exception('No se ha configurado una EECC FUENTE para esta central!!');
                }
            }
            $this->db->trans_begin();

            if($idEecc == NULL || $idEecc == ''){
                throw new Exception('No tiene una EECC DISE&Ntilde;O, Comunicarse con el administrador!!');
            }
            if ($itemplan == null || $arrayMat == null || $idEstacion == null || $idUsuario == null || $from == null || $idEecc == null) {
                throw new Exception('No se cargaron correctamente los datos para el registro');
            }
            $countCodPO = $this->m_detalle_obra_pin->countPOByItemplanAndEstacion($itemplan, $idEstacion, $tm);

            $idSubProyectoEstacion = $this->m_utils->getIdSubProyectoEstacionByItemplanAndEstacion($itemplan, $idEstacion, 'MAT');

            $codigoPO = $this->m_utils->getCodigoPO($itemplan);

            if ($codigoPO == null) {
                throw new Exception('Hubo un error al generar el codigo PO ');
            }

            $arrayInsertPO = array(
                "itemplan" => $itemplan,
                "codigo_po" => $codigoPO,
                "estado_po" => 1,
                "idEstacion" => $idEstacion,
                "from" => $tm,
                "costo_total" => $costoTotalMat,
                "idUsuario" => $idUsuario,
                "fechaRegistro" => $this->fechaActual(),
                "flg_tipo_area" => 1,
                "id_eecc_reg" =>$idEecc
            );

            if ($tm == 1) {
                if ($countCodPO >= 0 && $countCodPO <= 3) {
                    if ($costoTotalMat != null && $costoTotalMat > 0) {
                        $data = $this->m_detalle_obra_pin->insertarPO($arrayInsertPO);
                    } else {
                        throw new Exception('Solo se registraran PO con cantidades mayores a 0!!');
                    }
                } else {
                    throw new Exception('Solo puede registrar 3 PO en ' . $msjValidacion);
                }
            } else {
                if ($countCodPO == 0) {
                    if ($costoTotalMat != null && $costoTotalMat > 0) {
                        $data = $this->m_detalle_obra_pin->insertarPO($arrayInsertPO);
                    } else {
                        throw new Exception('Solo se registraran PO con cantidades mayores a 0!!');
                    }
                } else {
                    throw new Exception('Solo puede registrar una PO en ' . $msjValidacion);
                }
            }

            $arrayInsertDetallePOGlob = array();
            
            $hasPoMoActive = $this->m_detalle_obra_pin->countPtrPlantaInterna($itemplan);
            if($hasPoMoActive > 0){//si tiene po mo activo
                $idEstadoPlan = $this->m_utils->getEstadoPlanByItemplan($itemplan);
                if($idEstadoPlan==ESTADO_PLAN_PRE_DISENO){
                    $data = $this->m_utils->updateEstadoPlanObra($itemplan,ESTADO_PLAN_DISENO);
                }
            }
            
            if ($data['error'] == EXIT_SUCCESS) {
                foreach ($arrayMat as $row) {

                    $exitMatKit = $this->m_detalle_obra_pin->countMatxKit($row['codigo_material'], $idSubProyecto, $idEstacion);
                    if($row['flg_tipo'] == 0){ //NO BUCLE
                        if ($exitMatKit > 0) {
                            if ($row['codigo_material'] != null && $row['codigo_material'] != '' && $row['cantidad_ingresada'] != 0 && $row['motivo'] == '' && $row['cantidad_kit'] != '-' && $row['factor_porcentual'] != '-' && $row['costo_material'] != '-') {
                                $arrayDetallePOTemp = array(
                                    "codigo_po" => $codigoPO,
                                    "codigo_material" => $row['codigo_material'],
                                    "cantidad_ingreso" => $row['cantidad_ingresada'],
                                    "cantidad_final" => $row['cantidad_ingresada'],
                                    "costo_material" => $row['costo_material']
                                );
    
                                array_push($arrayInsertDetallePOGlob, $arrayDetallePOTemp);
                            }
                        }
                    }else if($row['flg_tipo'] == 1){ // BUCLE
                        if ($row['codigo_material'] != null && $row['codigo_material'] != '' && $row['cantidad_ingresada'] != 0 && $row['motivo'] == '' && $row['cantidad_kit'] != '-' && $row['factor_porcentual'] != '-' && $row['costo_material'] != '-') {
                            $arrayDetallePOTemp = array(
                                "codigo_po" => $codigoPO,
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
                    $data = $this->m_detalle_obra_pin->insertarDetallePO($arrayInsertDetallePOGlob);
                } else {
                    $data['error'] = EXIT_ERROR;
                    throw new Exception('Ningun material es valido para el registro!!');
                }

                if ($data['error'] == EXIT_SUCCESS) {
                    $arrayInsertLOGPO = array(
                        "codigo_po" => $codigoPO,
                        "itemplan" => $itemplan,
                        "idUsuario" => $idUsuario,
                        "fecha_registro" => $this->fechaActual(),
                        "idPoestado" => 1,
                        "controlador" => ($tm == 1 ? "consulta" : "diseno"),
                    );
                    $data = $this->m_detalle_obra_pin->insertarLOGPO($arrayInsertLOGPO);
                    if ($data['error'] == EXIT_SUCCESS) {
                        $arrayInsertDetallePlan = array(
                            "itemPlan" => $itemplan,
                            "poCod" => $codigoPO,
                            "idSubProyectoEstacion" => $idSubProyectoEstacion,
                            "fec_registro" => $this->fechaActual(),
                        );
                        $data = $this->m_detalle_obra_pin->insertarDetallePlan($arrayInsertDetallePlan);
                        if ($data['error'] == EXIT_SUCCESS) {
                            $arrayInserLogPlanobra = array(
                                "tabla" => 'detalleplan',
                                "actividad" => 'ingresar',
                                "itemplan" => $itemplan,
                                "ptr" => $codigoPO,
                                "fecha_registro" => $this->fechaActual(),
                                "id_usuario" => $idUsuario,
                            );
                            $data = $this->m_detalle_obra_pin->insertarPO_LogPlanobra($arrayInserLogPlanobra);
                            if ($data['error'] == EXIT_SUCCESS) {
                                
                                $flgExiste = $this->m_utils->getCountINConfigAutoProb($idSubProyecto);
                                if ($flgExiste > 0) {

                                    $fechaPrevEjec = $this->m_utils->getFecPrevEjec($itemplan);
                                    if ($fechaPrevEjec != null) {

                                        $fechaPrevEjec = new DateTime($fechaPrevEjec);
                                        $dateActual = new DateTime($this->fechaActual());
                                        $dateActual = new DateTime($dateActual->format('Y-m-d'));
                                        $diferencia = $dateActual->diff($fechaPrevEjec);
                                        $diferencia = $diferencia->format('%R%a');

                                        if ($diferencia <= 60) {
                                            $arrayUpdatePPO = array(
                                                "estado_po" => 2,
                                            );
                                            $data = $this->m_detalle_obra_pin->updatePO($itemplan, $codigoPO, $idEstacion, $arrayUpdatePPO);
                                            if ($data['error'] == EXIT_SUCCESS) {
                                                
                                                $arrayInsertLOGPO2 = array(
                                                    "codigo_po" => $codigoPO,
                                                    "itemplan" => $itemplan,
                                                    "idUsuario" => $idUsuario,
                                                    "fecha_registro" => $this->fechaActual(),
                                                    "idPoestado" => 2,
                                                    "controlador" => ($tm == 1 ? "consulta" : "diseno"),
                                                );
                                               
                                                $data = $this->m_detalle_obra_pin->insertarLOGPO($arrayInsertLOGPO2);
                                                 /************************Nuevo 05.07.2019 czavalacas: asignarle presupuesota las autoaprobaciones*******************************/
                                                if ($data['error'] == EXIT_SUCCESS) {//log_message('error', ' paso insertarLOGPO');
                                                    $data = $this->m_utils->execGetGrafosOnePtr($codigoPO);
                                                }
                                                 /*******************************************************/
                                            }
                                        }
                                    }

                                }
                                
                                if ($data['error'] == EXIT_SUCCESS) {
                                    $this->db->trans_commit();
                                    $data['codidoPO'] = $codigoPO;
                                    $data['itemplan'] = $itemplan;
                                }
                            }
                        }
                    }
                }
            }
            
            
            /********cambio estado itemplan PIN*******/

        } catch (Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function getExcelPOMat()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $idSubProyecto = $this->session->userdata('idSubProyecto_temp');
            $idEstacion = $this->session->userdata('idEstacion_temp');

            $arrayMateriales = $this->m_detalle_obra_pin->getMateriales_x_Material($idSubProyecto, $idEstacion);

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

                    // $file = fopen(PATH_FILE_UPLOAD_DETALLE_MATPO, "w");
                    // fputcsv($file, explode('\t', "CODIGO" . "\t" .
                    //     "MATERIAL" . "\t" .
                    //     "COSTO" . "\t" .
                    //     "KIT MAT" . "\t" .
                    //     "CANTIDAD INGRESADA"));
                    // foreach ($arrayMateriales as $row) {
                    //     fputcsv($file, explode('\t', utf8_decode($row->codigo_material . "\t" . $row->descrip_material . "\t" . $row->costo_material . "\t" . $row->cant_kit_material)));

                    // }
                    // fclose($file);
                    // header('Content-Type: text/csv');
                    // header('Content-Disposition: attachment;filename="' . basename(PATH_FILE_UPLOAD_DETALLE_MATPO) . '"');
                    // readfile(PATH_FILE_UPLOAD_DETALLE_MATPO);

                    // $data['error'] = EXIT_SUCCESS;
                    // $data['msj'] = 'Se genero correctamente el excel!!';
                }else{
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
}
