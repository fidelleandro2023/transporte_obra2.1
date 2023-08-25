<?php

defined('BASEPATH') or exit('No direct script access allowed');

class C_certificacion_solicitud_oc_masivo_opex extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=UTF8');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_certificacion/m_registro_oc_solicitud_masivo_cert');
        $this->load->library('lib_utils');
        $this->load->library('excel');
        $this->load->helper('url');
    }

    public function index()
    {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {
            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $data['tablaSolOC']    = $this->basicHtml();
            $permisos = $this->session->userdata('permisosArbolTransporte');
            $result = $this->lib_utils->getHTMLPermisos($permisos, 308, 328, ID_MODULO_ADMINISTRATIVO);
            $data['opciones'] = utf8_encode($result['html']);
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_certificacion/v_certificacion_solicitud_oc_masivo_opex', $data);
            } else {
                redirect('login', 'refresh');
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function basicHtml()
    {
        $html = '<table style="font-size: 10px" id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr role="row">
                        <th colspan="1">CODIGO SOLICITUD</th>
                        <th colspan="1">CODIGO DE CERTIFICACION</th>
                        <th colspan="1">OBSERVACION</th>
                        </tr>
                    </thead>
                    <tbody id="contBodyTable">
                     
                    </tbody>
                </table>';
        return $html;
    }

    public function getExcelCertificacionMasivoOpex()
    {
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
            $titulosColumnas = array('CODIGO SOLICITUD', 'CODIGO DE CERTIFICACION');

            $this->excel->setActiveSheetIndex(0);

            // Se agregan los titulos del reporte
            $this->excel->setActiveSheetIndex(0)
                ->setCellValue('A1', utf8_encode($titulosColumnas[0]))
                ->setCellValue('B1', utf8_encode($titulosColumnas[1]));

            $estiloTituloColumnas = array(
                'font' => array(
                    'name' => 'Calibri',
                    'bold' => true,
                    'color' => array(
                        'rgb' => '000000',
                    ),
                )
            );

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

    public function uploadPOMO()
    {
        $data['error'] = EXIT_ERROR;
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

    public function makeHTMLBodyTable($row_dimension, $objPHPExcel)
    {
        $data['html'] = '';
        $data['array_full'] = '';
        $html = '';
        $indice = 0;
		$msj = null;
        $array_not_found = array();
        $array_full = array();
        $html .= '<table style="font-size: 10px" id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr role="row">
                        <th colspan="1">CODIGO SOLICITUD</th>
                        <th colspan="1">CODIGO DE CERTIFICACION</th>
                        <th colspan="1">OBSERVACION</th>
                        </tr>
                    </thead>
                <tbody id="contBodyTable">';
        for ($i = 1; $i <= $row_dimension['row']; $i++) { //COMIENZA DESDE LA FILA 1
            $A = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, $i, true)->getValue();
            $B = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1, $i, true)->getValue();
            $C = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2, $i, true)->getValue();
            $total = 0;
            if ($B != '' && $A != 'CODIGO SOLICITUD') {
                $infoSol = $this->m_registro_oc_solicitud_masivo_cert->getInfoSolicitudOCCreaByCodigoOpex(trim($A));
                if ($infoSol != null) {
                    if ($infoSol['estado'] == 1 && $infoSol['cant'] == 1) {
                        $html .= '<tr id="tr' . $indice . '" >
                                	<th style="color:black">' . $A . '</th>
                                	<th style="color:black">' . $B . '</th>
        						    <th style="width: 5px;">OK</th>
                            	</tr>';
                        $indice++;

                        $pre_array = array($A, $B, $infoSol['itemplan'], $infoSol['idEstadoPlan'], $infoSol['idSubProyecto'], $infoSol['idTipoPlanta'], $infoSol['paquetizado_fg']);
                        array_push($array_full, $pre_array);
                    } else { //IVALIDO ATENDIDA O CANCELADA
                        if($infoSol['cant']==0 || $infoSol['cant']==null){
                            $msj = 'SOLICITUD SIN ITMEPLAN ASOCIADO';
                        }else if($infoSol['cant'] > 1){
                            $msj = 'SOLICITUD CON MAS DE 1 ITEMPLAN ASOCIADO';
                        }else if($infoSol['estado']==2){
                            $msj = 'SOLICITUD ATENDIDA';
                        }else if($infoSol['estado']==3){
                            $msj = 'SOLICITUD CANCELADA';
                        }else if($infoSol['estado']==5){
                            $msj = 'SOLICITUD EN ESPERA DE ACTA';
                        }

                        $html .= '<tr style="background-color: #ffcece;" id="tr' . $indice . '" >
                                	<th style="color:black">' . $A . '</th>
                                	<th style="color:black">' . $B . '</th>
        						    <th style="width: 5px;">' . $msj . '</th>
                            	</tr>';
                        $indice++;
                    }
                } else { //INVALIDO SOLICITUD NO EXISTE
                    $html .= '<tr style="background-color: #ffcece;" id="tr' . $indice . '" >
                            	<th style="color:black">' . $A . '</th>
                            	<th style="color:black">' . $B . '</th>
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

    public function atencionMasivoSolicitudOcOpexCerti()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;
            if ($idUsuario != null) {
                $jsonDataFile = $this->input->post('jsonDataFile');
                $arrayFile = json_decode($jsonDataFile);
                if ($arrayFile != null) {
                    $itemplanList       = array(); //itemplans a atender    
                    $solicitudesList    = array(); //solicitudes a atender                      

                    foreach ($arrayFile as $datos) {
                        if ($datos != null) {


                            $codigo_solicitud   = $datos[0];
                            $codigo_certifica   = $datos[1];
                            $itemplan           = $datos[2];
                            $idEstadoPlan       = 23;
                            $idSubProyecto      = $datos[4];
                            $idTipoPlanta       = $datos[5];
                            $paquetizado_fg     = $datos[6];

                            $arrayDataObra = $this->m_utils->getDataItemplanByCodOcCerti($codigo_solicitud);

                            if ($arrayDataObra['idEstadoPlan'] == 10) {
                                $dataPlanObra = array(
                                    'itemplan'            => $itemplan,
                                    'solicitud_oc_certi'    => $codigo_solicitud,
                                    'estado_oc_certi'        => 'ATENDIDO',
                                    'fecha_certifica'        => _fechaActual(),
                                    'trunco_situacion'    => 23,
                                    'usu_upd'             => $idUsuario,
                                    'fecha_upd'           => _fechaActual(),
                                    'descripcion'         => 'CERTIFICADO OC-TRUNCO',
                                    'codigo_certificacion' => $codigo_certifica
                                );
                            } else { //SI NO ES TRUNCO SE CERTIFICA LA OBRA
                                $dataPlanObra = array(
                                    'itemplan'             => $itemplan,
                                    'solicitud_oc_certi'    => $codigo_solicitud,
                                    'estado_oc_certi'        => 'ATENDIDO',
                                    'fecha_certifica'        => _fechaActual(),
                                    'idEstadoPlan'        => 23,
                                    'usu_upd'             => $idUsuario,
                                    'fecha_upd'           => _fechaActual(),
                                    'descripcion'         => 'CERTIFICADO OC',
                                    'codigo_certificacion' => $codigo_certifica
                                );
                            }
                            /*
								$itemplanData = array(  'itemplan'                => $itemplan,								                        
								                        'estado_oc_anula_pos'     => 'ATENDIDO',								                        
								                        'solicitud_oc_anula_pos'  => $codigo_solicitud
								                    );								
								
								array_push($itemplanList, $itemplanData);							    						
								*/

                            array_push($itemplanList, $dataPlanObra);

                            $dataSolicitud = array();
                            $dataSolicitud['codigo_solicitud']      = $codigo_solicitud;
                            $dataSolicitud['usuario_valida']        = $idUsuario;
                            $dataSolicitud['fecha_valida']          = _fechaActual();
                            $dataSolicitud['estado']                = 2;
                            $dataSolicitud['codigo_certificacion']  = $codigo_certifica;
                            array_push($solicitudesList, $dataSolicitud);
                        }
                    }

                    $data = $this->m_registro_oc_solicitud_masivo_cert->masiveUpdateAtencionCreateSolOCOpex($itemplanList, $solicitudesList);
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
}
