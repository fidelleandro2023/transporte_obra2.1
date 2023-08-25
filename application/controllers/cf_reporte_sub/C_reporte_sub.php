<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class C_reporte_sub extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_reporte_sub/M_reporte_sub', 'reporteSub');
        $this->load->library('lib_utils');
        $this->load->library('excel');
        $this->load->helper('url');
    }

    public function index() {
        $logedUser = $this->session->userdata('usernameSession');
        $idEcc = $this->session->userdata('eeccSession');
        if ($logedUser != null) {
            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $permisos = $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_CERTIFICACION_MO, ID_PERMISO_HIJO_REPORTE_GESTION_OC, ID_MODULO_ADMINISTRATIVO);
            $data['opciones'] = $result['html'];
            $this->load->view('vf_reporte_sub/v_reporte_sub', $data);
        } else {
            redirect('login', 'refresh');
        }
    }

    function tablaSub() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $cmbEstado = $this->input->post('cmbEstado');
            if ($cmbEstado == null) {
                throw new Exception('Tiene que selecionar un Estado');
            }
            $data['tablaSub'] = $this->getTablaSub($cmbEstado);
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function getTablaSub($cmbEstado) {
        $cont = 0;
        $t_cinco = 0;
        $t_seis = 0;
        $t_once = 0;
        $t_veinte = 0;
        $t_total = 0;
        $estado = 0;
        $dataMaterial = $this->reporteSub->obtenerSub($cmbEstado);
        _log(print_r($dataMaterial, true));
        log_message("error", print_r($dataMaterial, true));
        $html = '<table id="tbDataSub" class="table table-bordered" style="width:100%">
                                                <thead class="thead-default">
                                                    <tr>
                                                        <th>PROYECTO</th>
                                                        <th>0 A 5 DIAS</th>
                                                        <th>6 A 10 DIAS</th>
                                                        <th>11 A 20 DIAS</th>
                                                        <th>21 A + DIAS</th>
                                                        <th>TOTAL</th>
                                                    </tr>
                                                </thead>                    
                                                <tbody id="tb_body">

                                                ';


        foreach ($dataMaterial as $row) {
            $cont++;
            $html .= '<tr>
                            <td style="background-color: #e4f6ff;">' . $row->proyectoDesc . '</td>
                            <td style="cursor: pointer;" onclick="detalleSub(' . $row->idProyecto . ',' . $row->cinco . ',' . $row->estado . ',1)">' . $row->cinco . '</td>
                            <td style="cursor: pointer;" onclick="detalleSub(' . $row->idProyecto . ',' . $row->seis . ',' . $row->estado . ',2)">' . $row->seis . '</td>
                            <td style="cursor: pointer;" onclick="detalleSub(' . $row->idProyecto . ',' . $row->once . ',' . $row->estado . ',3)">' . $row->once . '</td>
                            <td style="cursor: pointer;" onclick="detalleSub(' . $row->idProyecto . ',' . $row->veinte . ',' . $row->estado . ',4)">' . $row->veinte . '</td>
                            <td style="background-color: #e4f6ff;" >' . $row->total . '</td>
                            </tr>';


            $t_cinco = $t_cinco + $row->cinco;
            $t_seis = $t_seis + $row->seis;
            $t_once = $t_once + $row->once;
            $t_veinte = $t_veinte + $row->veinte;
            $t_total = $t_total + $row->total;
            $estado = $row->estado;
        }
        $html .= '</tbody> 
            <tfoot>
             <tr style="background-color: #e4f6ff;">
                <td>TOTAL</td>
                <td>' . $t_cinco . '</td>
                <td>' . $t_seis . '</td>
                <td>' . $t_once . '</td>
                <td>' . $t_veinte . '</td>
                <td style="cursor: pointer;" onclick="detalleSub(null,null,' . $estado . ',null)">' . $t_total . '</td>
            </tr>
             <tr style="background-color: #7dc8ee;">
                <td>PORCENTAJE</td>
                <td>' . round((($t_cinco / $t_total) * 100), 2, PHP_ROUND_HALF_UP) . '%</td>
                <td>' . round((($t_seis / $t_total) * 100), 2, PHP_ROUND_HALF_UP) . '%</td>
                <td>' . round((($t_once / $t_total) * 100), 2, PHP_ROUND_HALF_UP) . '%</td>
                <td>' . round((($t_veinte / $t_total) * 100), 2, PHP_ROUND_HALF_UP) . '%</td>
                <td>' . round((($t_total / $t_total) * 100), 2) . '%</td>
            </tr>
           
            </tfoot>
            </table>';
        log_message("error", $html);
        return utf8_decode($html);
    }

    function tablaDetalleSub() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $idSubProyecto = $this->input->post('idSubProyecto');
            $estado = $this->input->post('estado');
            $dias = $this->input->post('dias');
            $data['tablaDetalleSub'] = $this->gettablaDetalleSub($idSubProyecto, $estado, $dias);
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function gettablaDetalleSub($idSubProyecto, $estado, $dias) {
        $dataMaterial = $this->reporteSub->obtenerDetalleSub($idSubProyecto, $estado, $dias);
        $html = '';
        log_message("error", count($dataMaterial));
        if (count($dataMaterial) == 0) {
            $html .= '';
        } else {
            $html .= '<table id="tbDetalleSub" class="table table-bordered" style="width:100%">
                                                <thead class="thead-default">
                                                    <tr>
                                                        <th>SOLICITUD</th>
                                                        <th>PROYECTO</th>
                                                        <th>SUBPROYECTO</th>
                                                        <th>EECC</th>
                                                        <th>PLAN</th>
                                                        <th>PEP 1</th>
                                                        <th>PEP 2</th>
                                                        <th>FECHA CREACION</th>
                                                        <th>FECHA</th>
                                                        <th>DIAS PENDIENTES</th>
                                                    </tr>
                                                </thead>                    
                                                <tbody id="tb_body">';
            $cont = 0;

            foreach ($dataMaterial as $row) {

                $cont++;
                $html .= '<tr>
                            <td>' . $row->codigo_solicitud . '</td>
                            <td>' . $row->proyectoDesc . '</td>
                            <td>' . $row->subProyectoDesc . '</td>
                            <td>' . $row->empresaColabDesc . '</td>
                            <td>' . $row->plan . '</td>
                            <td>' . $row->pep1 . '</td>
                            <td>' . $row->pep2 . '</td>
                            <td>' . $row->fecha_creacion . '</td>
                            <td>' . $row->fecha_actual . '</td>
                            <td>' . $row->dias . '</td>
                            </tr>';
            }
            $html .= '</tbody></table>';
            log_message("error", $html);
        }
        return utf8_decode($html);
    }

    function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone', 'America/Lima');
        setlocale(LC_TIME, "es_ES", "esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }

    public function exportarExcel() {
        $cmbEstado = (isset($_GET['cmbEstado']) ? $_GET['cmbEstado'] : null);
        $dataMaterial = $this->reporteSub->obtenerSub($cmbEstado);
        // Inicializar el excel
        $hoja = $this->excel->setActiveSheetIndex(0);
        $hoja->setShowGridlines(false);
        $hoja->setTitle('REPORTE');
        $hoja->getStyle('B3:G3')->getFill()->getStartColor()->setRGB('FF0000');
        $hoja->getStyle('B3:G3')->getFill()->setFillType('solid')->getStartColor()->setRGB('80D1FF');
        //
        $hoja->setCellValue('A1', 'REPORTE');
        $hoja->getStyle('A1')->getFont()->setSize(20);
        $hoja->getStyle('A1')->getFont()->setBold(true);
        $hoja->mergeCells('A1:G1');
        $hoja->getStyle('A1:G1')->getAlignment()->setHorizontal('center');
        $hoja->getStyle('A1:G1')->getAlignment()->setVertical('center');
        // Segunda fila
        $hoja->setCellValue('B2', '');
        $hoja->mergeCells('A2:G2');
        // Tercera fila
        $hoja->getStyle('A3:G3')->getAlignment()->setHorizontal('center');
        $hoja->getStyle('A3:G3')->getAlignment()->setVertical('center');
        $hoja->setCellValue('B3', 'PROYECTO')->getStyle('B3')->getFont()->setSize(13)->setBold(true);
        $hoja->setCellValue('C3', '0 A 5 DIAS')->getStyle('C3')->getFont()->setSize(13)->setBold(true);
        $hoja->setCellValue('D3', '6 A 10 DIAS')->getStyle('D3')->getFont()->setSize(13)->setBold(true);
        $hoja->setCellValue('E3', '11 A 20 DIAS')->getStyle('E3')->getFont()->setSize(13)->setBold(true);
        $hoja->setCellValue('F3', '21 A + DIAS')->getStyle('F3')->getFont()->setSize(13)->setBold(true);
        $hoja->setCellValue('G3', 'TOTAL')->getStyle('G3')->getFont()->setSize(13)->setBold(true);
        //
        $hoja->getColumnDimension('A')->setAutoSize(true);
        $hoja->getColumnDimension('B')->setAutoSize(true);
        $hoja->getColumnDimension('C')->setAutoSize(true);
        $hoja->getColumnDimension('D')->setAutoSize(true);
        $hoja->getColumnDimension('E')->setAutoSize(true);
        $hoja->getColumnDimension('F')->setAutoSize(true);
        $hoja->getColumnDimension('G')->setAutoSize(true);
        //
        $cont = 4;
        foreach ($dataMaterial as $row) {
            $hoja->setCellValue('B' . $cont, $row->proyectoDesc);
            $hoja->setCellValue('C' . $cont, $row->cinco);
            $hoja->setCellValue('D' . $cont, $row->seis);
            $hoja->setCellValue('E' . $cont, $row->once);
            $hoja->setCellValue('F' . $cont, $row->veinte);
            $hoja->setCellValue('G' . $cont, $row->total);
            $cont++;
        }
        //
        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => '000000'),
                ),
            ),
        );
        $hoja->getStyle('B3' . ':' . 'G' . ($cont - 1))->applyFromArray($styleArray);
        //
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="ReporteExcel.xls"');
        header('Cache-Control: max-age=0'); //no cache         
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        // Forzamos a la descarga         
        $objWriter->save('php://output');
    }

    public function exportarExcelDetalle() {
        $idSubProyecto = (isset($_GET['idSubProyecto']) ? $_GET['idSubProyecto'] : null);
        $number = (isset($_GET['number']) ? $_GET['number'] : null);
        $estado = (isset($_GET['estado']) ? $_GET['estado'] : null);
        $dias = (isset($_GET['dias']) ? $_GET['dias'] : null);
        $dataMaterial = $this->reporteSub->obtenerDetalleSub($idSubProyecto, $estado, $dias);
        // Inicializar el excel
        $hoja = $this->excel->setActiveSheetIndex(0);
        $hoja->setShowGridlines(false);
        $hoja->setTitle('DETALLE');
        $hoja->getStyle('B3:K3')->getFill()->getStartColor()->setRGB('FF0000');
        $hoja->getStyle('B3:K3')->getFill()->setFillType('solid')->getStartColor()->setRGB('80D1FF');
        //
        $hoja->setCellValue('A1', 'REPORTE DETALLE');
        $hoja->getStyle('A1')->getFont()->setSize(20);
        $hoja->getStyle('A1')->getFont()->setBold(true);
        $hoja->mergeCells('A1:K1');
        $hoja->getStyle('A1:K1')->getAlignment()->setHorizontal('center');
        $hoja->getStyle('A1:K1')->getAlignment()->setVertical('center');
        // Segunda fila
        $hoja->setCellValue('B2', '');
        $hoja->mergeCells('A2:K2');
        // Tercera fila
        $hoja->getStyle('A3:J3')->getAlignment()->setHorizontal('center');
        $hoja->getStyle('A3:J3')->getAlignment()->setVertical('center');
        $hoja->setCellValue('B3', 'CODIGO DE SOLICITUD')->getStyle('B3')->getFont()->setSize(13)->setBold(true);
        $hoja->setCellValue('C3', 'PROYECTO')->getStyle('C3')->getFont()->setSize(13)->setBold(true);
        $hoja->setCellValue('D3', 'SUBPROYECTO')->getStyle('D3')->getFont()->setSize(13)->setBold(true);
        $hoja->setCellValue('E3', 'EMPRESA COLABORADORA')->getStyle('E3')->getFont()->setSize(13)->setBold(true);
        $hoja->setCellValue('F3', 'PLAN')->getStyle('F3')->getFont()->setSize(13)->setBold(true);
        $hoja->setCellValue('G3', 'PEP 1')->getStyle('G3')->getFont()->setSize(13)->setBold(true);
        $hoja->setCellValue('H3', 'PEP 2')->getStyle('H3')->getFont()->setSize(13)->setBold(true);
        $hoja->setCellValue('I3', 'FECHA DE CREACION')->getStyle('I3')->getFont()->setSize(13)->setBold(true);
        $hoja->setCellValue('J3', 'FECHA')->getStyle('J3')->getFont()->setSize(13)->setBold(true);
        $hoja->setCellValue('K3', 'DIAS PENDIENTES')->getStyle('K3')->getFont()->setSize(13)->setBold(true);
        //
        $hoja->getColumnDimension('A')->setAutoSize(true);
        $hoja->getColumnDimension('B')->setAutoSize(true);
        $hoja->getColumnDimension('C')->setAutoSize(true);
        $hoja->getColumnDimension('D')->setAutoSize(true);
        $hoja->getColumnDimension('E')->setAutoSize(true);
        $hoja->getColumnDimension('F')->setAutoSize(true);
        $hoja->getColumnDimension('G')->setAutoSize(true);
        $hoja->getColumnDimension('H')->setAutoSize(true);
        $hoja->getColumnDimension('I')->setAutoSize(true);
        $hoja->getColumnDimension('J')->setAutoSize(true);
        $hoja->getColumnDimension('K')->setAutoSize(true);
        //
        $cont = 4;
        foreach ($dataMaterial as $row) {
            $hoja->setCellValue('B' . $cont, $row->codigo_solicitud);
            $hoja->setCellValue('C' . $cont, $row->proyectoDesc);
            $hoja->setCellValue('D' . $cont, $row->subProyectoDesc);
            $hoja->setCellValue('E' . $cont, $row->empresaColabDesc);
            $hoja->setCellValue('F' . $cont, $row->plan);
            $hoja->setCellValue('G' . $cont, $row->pep1);
            $hoja->setCellValue('H' . $cont, $row->pep2);
            $hoja->setCellValue('I' . $cont, $row->fecha_creacion);
            $hoja->setCellValue('J' . $cont, $row->fecha_actual);
            $hoja->setCellValue('K' . $cont, $row->dias);
            $cont++;
        }
        //
        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => '000000'),
                ),
            ),
        );
        $hoja->getStyle('B3' . ':' . 'K' . ($cont - 1))->applyFromArray($styleArray);
        //
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="ReporteDetalleExcel.xls"');
        header('Cache-Control: max-age=0'); //no cache         
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        // Forzamos a la descarga         
        $objWriter->save('php://output');
    }

}
