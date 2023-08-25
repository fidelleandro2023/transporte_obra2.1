<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class C_bandeja_preaprobacion extends CI_Controller {

    //put your code here

    function __construct() {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_itemfault/M_registro', 'registro');
        $this->load->model('mf_itemfault/M_consulta', 'consulta');
        $this->load->model('mf_pqt_mantenimiento/m_pqt_central');
        $this->load->library('excel');
        $this->load->library('lib_utils');
        $this->load->library('map_utils/coordenadas_utils');
        $this->load->library('zip');
    }

    public function index() {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {
            $data['servicio'] = $this->registro->getServicio();
            $data['listaTiCen'] = $this->m_pqt_central->getPqtAllCentral();
            $data['evento'] = $this->registro->getEvento();
            $data['corte'] = $this->registro->getCorte();
            $data['estado'] = $this->consulta->getEstado();
            $data['gerencia'] = $this->registro->getGerencia();
            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $data['tablaConsultaItemfault'] = $this->tablaConsultaVacia();
            //Mandamos el Json al view
            $data['jsonCoordenadas'] = $this->coordenadas_utils->getJsonCoordenadas();
            $permisos = $this->session->userdata('permisosArbol');
            // permiso para registro individual modificar
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_GESTION_VR_MANTENIMIENTO, ID_PERMISO_HIJO_BANDEJA_APROBACION_ITEMFAULT, ID_MODULO_GESTION_MANTENIMIENTO);
            $data['opciones'] = $result['html'];
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_itemfault/v_bandeja_preaprobacion', $data);
            } else {
                redirect('login', 'refresh');
            }
//            $this->load->view('vf_itemfault/v_bandeja_preaprobacion', $data);
        } else {
            redirect('login', 'refresh');
        }
    }

    function AprobarDiseno() {
        $itemfault = $this->input->post('idItemfault');
        $arrayDataOP = array(
            'idEstadoItemfault' => 2,
        );
        $data = $this->consulta->AprobarDiseno($itemfault, $arrayDataOP);
        echo json_encode(array_map('utf8_encode', $data));
    }

    function tablaConsultaVacia() {
        $html = '<table id="tbDataConsulta" class="table table-bordered" style="width:100%">
                                                <thead class="thead-default">
                                                    <tr>
                                                        <th></th>
                                                        <th>ITEMFAULT</th>
                                                        <th>PO</th>
                                                        <th>TIPO PO</th>
                                                        <th>NOMBRE DE URA</th>
                                                        <th>SERVICIO DE RED</th>
                                                        <th>ELEMENTO DE RED DE SERVICIO</th>
                                                        <th>EECC</th>
                                                        <th>FECHA DE CREACION</th>
                                                        <th>ESTADO</th>
                                                    </tr>
                                                </thead>                    
                    <tbody id="tb_body"></tbody></table>';
        return utf8_decode($html);
    }

    function consultaPreAprobItemfault() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $selectServicio = $this->input->post('selectServicio');
            $selectElementoServicio = $this->input->post('selectElementoServicio');
            $inputcreacion = $this->input->post('inputcreacion');
            $inputItemfaut = $this->input->post('inputItemfaut');
            $inputNombrePlan = $this->input->post('inputNombrePlan');
            //
            $selectEstado = $this->input->post('selectEstado');
            $selectGerencia = $this->input->post('selectGerencia');
            $selectEvento = $this->input->post('selectEvento');
            $selectSubEvento = $this->input->post('selectSubEvento');
            if ($inputcreacion == null && $selectServicio == null && $selectElementoServicio == null && $inputItemfaut == null && $inputNombrePlan == null && $selectEstado == null && $selectGerencia == null && $selectEvento == null && $selectSubEvento == null) {
                throw new Exception('Debe de seleccionar al menos un filtro');
            }
            $data['tablaConsultaItemfault'] = $this->getTablaConsulta($selectServicio, $selectElementoServicio, $inputcreacion, $inputItemfaut, $inputNombrePlan, $selectEstado, $selectGerencia, $selectEvento, $selectSubEvento);
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function getTablaConsulta($selectServicio, $selectElementoServicio, $inputcreacion, $inputItemfaut, $inputNombrePlan, $selectEstado, $selectGerencia, $selectEvento, $selectSubEvento) {
        $dataConsulta = $this->consulta->getTablaBandejaPreApro($selectServicio, $selectElementoServicio, $inputcreacion, $inputItemfaut, $inputNombrePlan, $selectEstado, $selectGerencia, $selectEvento, $selectSubEvento);
        $html = '';

        if (count($dataConsulta) == 0) {
            $html .= '<table id="tbDataConsulta" class="table table-bordered" style="width:100%">
                                                <thead class="thead-default">
                                                    <tr>
                                                        <th></th>
                                                        <th>ITEMFAULT</th>
                                                        <th>PO</th>
                                                        <th>TIPO PO</th>
							<th>COSTO PO</th>
                                                        <th>NOMBRE DE URA</th>
                                                        <th>SERVICIO DE RED</th>
                                                        <th>ELEMENTO DE RED DE SERVICIO</th>
							<th>EECC</th>
                                                        <th>FECHA DE CREACION</th>
                                                        <th>ESTADO</th>
                                                    </tr>
                                                </thead>                    
                    <tbody id="tb_body"></tbody></table>';
        } else {
            $html .= '<table id="tbDataConsulta" class="table table-bordered" style="width:100%">
                                                <thead class="thead-default">
                                                    <tr>
                                                        <th>ACCION</th>
                                                        <th>ITEMFAULT</th>
                                                        <th>PO</th>                                                        
                                                        <th>TIPO PO</th>
							<th>COSTO PO</th>
                                                        <th>NOMBRE DE URA</th>
                                                        <th>SERVICIO DE RED</th>
                                                        <th>ELEMENTO DE RED DE SERVICIO</th>
							<th>EECC</th>
                                                        <th>FECHA DE CREACION</th>
                                                        <th>ESTADO</th>
                                                    </tr>
                                                </thead>                    
                                                <tbody id="tb_body">';
            $cont = 0;
            $accion = null;
            foreach ($dataConsulta as $row) {
                $btnDownload = '<a data-codigo_po ="' . $row->codigo_itemfault_po . '" data-eecc ="' . $row->idEmpresaColab . '" data-itemfault ="' . $row->itemfault . '"  title="Descargar de Archivos" onclick="generarRar(' . "'" . $row->itemfault . "'" . ');"><i class="zmdi zmdi-hc-2x zmdi-folder-star"></i></a>&nbsp;&nbsp;';
                $btnDownload .= '<a data-codigo_po ="' . $row->codigo_itemfault_po . '" data-eecc ="' . $row->idEmpresaColab . '" data-itemfault ="' . $row->itemfault . '"  title="Descargar Materiales" onclick="generarExcelMat($(this));"><i class="zmdi zmdi-hc-2x zmdi-case-download"></i></a>';
                if ($row->idEstadoPlan == 20) {
                    if ($this->session->userdata('idPerfilSession') == 49) {
                        if ($row->idArea == 48) {
                            $accion = '<a data-codigo_po="' . $row->codigo_itemfault_po . '" data-itemfault="' . $row->itemfault . '" onclick="aprobar_itemfault_po(' . "'" . $row->itemfault . "'" . ',' . "'" . $row->codigo_itemfault_po . "'" . ')"><i class="zmdi zmdi-check-circle zmdi-hc-2x mdc-text-red"></i></a>';
                        } else {
                            $accion = '<a data-codigo_po="' . $row->codigo_itemfault_po . '" data-itemfault="' . $row->itemfault . '" onclick="aprobar_itemfault(' . "'" . $row->itemfault . "'" . ',' . "'" . $row->codigo_itemfault_po . "'" . ')"><i class="zmdi zmdi-check-circle zmdi-hc-2x mdc-text-red"></i></a>';
                        }
                    } else {
                        $accion = '';
                    }
                } else {
                    $accion = '';
                }

                $cont++;
                $html .= '<tr>
                            <td><div class="text-center">' . $btnDownload . '&nbsp;&nbsp;' . $accion . '</td></div>
                            <td>' . $row->itemfault . '</td>
                            <td>' . $row->codigo_itemfault_po . '</td>
                             <td>' . $row->tipoArea . '</td>
                            <td>' . $row->costo_total . '</td>							
                            <td>' . $row->nombre . '</td>
                            <td>' . $row->servicioDesc . '</td>
                            <td>' . $row->elementoDesc . '</td>
                            <td>' . $row->empresaColabDesc . '</td>
                            <td>' . $row->fecha_registro . '</td>
                            <td>' . $row->estadoPlanDesc . '</td>';
            }
            $html .= '</tbody></table>';
//            log_message("error", $row->idEstadoItemfault);
        }
        return utf8_decode($html);
    }

    function actualizarPropuesta() {
        $config = [
            "upload_path" => "./dist/img/itemfault",
            'allowed_types' => "pdf"
        ];

        $this->load->library("upload", $config);
        //
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $itemfault = $this->input->post('itemfault');
            $inputMontoMO = $this->input->post('inputMontoMO');
            $inputMontoMAT = $this->input->post('inputMontoMAT');
            if ($inputMontoMO == null) {
                throw new Exception('Ingrese monto de Mano de Obra');
            }
            if ($inputMontoMAT == null) {
                throw new Exception('Ingrese monto de Material');
            }
            if (!$this->upload->do_upload('archivo_pdf')) {
                //*** ocurrio un error
                throw new Exception($this->upload->display_errors());
            }

            $arrayDataOP = array(
                'montoMO' => $inputMontoMO,
                'montoMAT' => $inputMontoMAT,
                'pdf_propuesta_uno' => '',
                'pdf_propuesta_dos' => '',
//                'idEstadoItemfault' => 2,
            );
            $data = $this->consulta->actualizarPropuesta($itemfault, $arrayDataOP);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function getExcelPOMatAprobItemfault() {

        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $codigo_po = $this->input->post('codigoPO');
            $itemfault = $this->input->post('itemfault');
            $idEmpresaColab = $this->input->post('idEmpresaColab');

            _log("CODIGO: " . $codigo_po);
            if ($codigo_po == null) {
                throw new Exception('SIN PO');
            }
            $arrayMateriales = $this->consulta->getDataSapMat($codigo_po);

            ini_set('max_execution_time', 10000);
            ini_set('memory_limit', '2048M');

            $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
            $cacheSettings = array('memoryCacheSize ' => '5000MB', 'cacheTime' => '1000');
            PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

            $this->excel->setActiveSheetIndex(0);
            $this->excel->getActiveSheet()->setTitle('ValeDatos');
            $contador = 1;
            $titulosColumnas = array('MATERIAL', 'CENTRO', 'CTD', '', '', 'T', 'R', 'ALMACEN', '', '', '', 'FECHA', '', 'OBSERV', '', 'CODIGO PO');

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
            _log(print_r($arrayMateriales, true));
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
                        ->setCellValue("P{$contador}", $row->codigo_itemfault_po);
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
            $archivo = 'ValeDatos' . rand() . '.xls';
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $archivo . '"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
            //Hacemos una salida al navegador con el archivo Excel.
            $objWriter->save('download/detalleMatSAP/' . $archivo);
            // $objWriter->save('php://output');
            // readfile('download/detalleMatSAP/ValeDatos.xls');
            $data['rutaExcel'] = 'download/detalleMatSAP/' . $archivo;
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = 'Error interno, al crear archivo vale datos';
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function aprobarMatItemfault() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $codigoPo = $this->input->post('codigo_po');
            $itemfault = $this->input->post('itemfault');
            $vale_re = $this->input->post('vale_reserva');

            if ($codigoPo == null) {
                throw new Exception('No envio una PO');
            }

            if ($itemfault == null) {
                throw new Exception('No envio un itemfault');
            }

            if ($vale_re == null) {
                throw new Exception('No ingreso el VR');
            }

            $idUsuario = $this->session->userdata('idPersonaSession');
            if ($idUsuario == null || $idUsuario == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }
            $arrayDataPo = array('vr' => $vale_re,
                'estado_po' => 3);

            $arrayLogPo = array(
                "codigo_iteamfault_po" => $codigoPo,
                "itemfault" => $itemfault,
                "id_usuario" => $idUsuario,
                "fecha_registro" => $this->fechaActual(),
                "estado_po" => 3
            );
            $idOpex = $this->consulta->getIdOpex($codigoPo);
            log_message('error', $idOpex);
            $MontoPO = $this->consulta->getMontoOpex($codigoPo);
            log_message('error', $MontoPO);
            $this->consulta->UpdateTranss($codigoPo);
            $this->consulta->updateTransaccion($idOpex, $MontoPO);
            $data = $this->consulta->actualizarVr($codigoPo, $itemfault, $arrayDataPo, $arrayLogPo);
            $data['tablaConsultaItemfault'] = $this->getTablaConsulta(null, null, null, $itemfault, null, null, null, null, null);
        } catch (Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone', 'America/Lima');
        setlocale(LC_TIME, "es_ES", "esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }

    ////////////

    public function generarRar() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $filename = $this->input->post('itemFault');
            $pat = $this->consulta->generarRar($filename);
            log_message('error', $pat);
            if ($pat !== 0) {
                $data['path'] = 1;
            } else {
                $data['path'] = 2;
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function generarRar_download() {
        $filename = (isset($_GET['itemFault']) ? $_GET['itemFault'] : '');
        $pat = $this->consulta->generarRar_download($filename);
        log_message('error', $filename);
        $path = $pat . '/';
        $this->zip->read_dir($path, false);
        $this->zip->download($filename . '.zip');
    }

    
}
