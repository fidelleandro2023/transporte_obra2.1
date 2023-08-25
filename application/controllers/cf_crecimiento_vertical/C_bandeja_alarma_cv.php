<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_bandeja_alarma_cv extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_plan_obra/m_consulta');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_crecimiento_vertical/m_bandeja_edit_cv');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index()
    {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {
            $data['limiteGroupConcat'] = $this->m_bandeja_edit_cv->quitarLimiteGroupConcat();
            $data['tablaReporte'] = $this->makeHTLMTablaConsultaCounts($this->m_bandeja_edit_cv->getCantidadesReporteCV(ID_EECC_QUANTA));
            $data['tablaConstruccion'] = $this->makeHTLMTablaConstruccion(ID_EECC_QUANTA);
            $data['tablaReporte2'] = $this->makeHTLMTablaReporte2(ID_EECC_QUANTA);

            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $permisos = $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_CV, ID_PERMISO_HIJO_BANDEJA_ALARMA_CV);
            $data['opciones'] = $result['html'];
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_crecimiento_vertical/v_bandeja_alarma_cv', $data);
            } else {
                redirect('login', 'refresh');
            }
        } else {

            redirect('login', 'refresh');
        }

    }

    public function makeHTLMTablaConsultaCounts($listaCounts)
    {
        $string = 'NRO DPTOS <=4';
        $html = '
                <table id="tabla_reporte" class="table table-bordered">
                    <tbody>';

        if (count($listaCounts) > 0) {
            $html .= '
                        <tr>
                            <td colspan="3" style="background-color: var(--celeste_telefonica);color: white;text-align: center;">RESUMEN</td>
                        </tr>
                        <tr>
                            <td colspan="1" style="background-color: var(--celeste_telefonica);color: white;text-align: center;">DESCRIPCI&Oacute;N</td>
                            <td colspan="1" style="background-color: var(--celeste_telefonica);color: white;text-align: center;">CANTIDAD</td>
                            <td colspan="1" style="background-color: var(--celeste_telefonica);color: white;text-align: center;">PORCENTAJE</td>
                        </tr>
                        <tr>
                            <td colspan="1" style="background-color: var(--celeste_telefonica);color: white;">ITEMPLAN</td>
                            <td colspan="1"  style="text-align: center;">' . $listaCounts['cant_itemplan'] . '</td>
                            <td colspan="1"  style="text-align: center;">' . ($this->getPorcentaje($listaCounts['cant_itemplan'], $listaCounts['cant_itemplan'])) . '</td>
                        </tr>
                        <tr>
                            <td colspan="1" style="background-color: var(--celeste_telefonica);color: white;">' . $string . '</td>
                            <td colspan="1" style="text-align: center;">' . $listaCounts['cant_dpto'] . '</td>
                            <td colspan="1" style="text-align: center;">' . ($this->getPorcentaje($listaCounts['cant_itemplan'], $listaCounts['cant_dpto'])) . '</td>
                        </tr>
                        <tr>
                            <td colspan="1" style="background-color: var(--celeste_telefonica);color: white;">BUSQUEDA</td>
                            <td colspan="1" style="text-align: center;">' . $listaCounts['cant_ip_pre_regi'] . '</td>
                            <td colspan="1" style="text-align: center;">' . ($this->getPorcentaje($listaCounts['cant_itemplan'], $listaCounts['cant_ip_pre_regi'])) . '</td>
                        </tr>
                        <tr>
                            <td colspan="1" style="background-color: var(--celeste_telefonica);color: white;">EN OBRA</td>
                            <td colspan="1" style="text-align: center;">' . $listaCounts['cant_ip_obra'] . '</td>
                            <td colspan="1" style="text-align: center;">' . ($this->getPorcentaje($listaCounts['cant_itemplan'], $listaCounts['cant_ip_obra'])) . '</td>
                        </tr>
                        <tr>
                            <td colspan="1"></td>
                            <td colspan="1" style="background-color: var(--celeste_telefonica);color: white;text-align: center;">X</td>
                            <td colspan="1" style="background-color: var(--celeste_telefonica);color: white;text-align: center;">Y</td>
                        </tr>
                        <tr>
                            <td colspan="1" style="background-color: var(--celeste_telefonica);color: white;">COORDENADAS</td>
                            <td colspan="1" style="text-align: center;">' . $listaCounts['cant_coord_x'] . '</td>
                            <td colspan="1" style="text-align: center;">' . $listaCounts['cant_coord_y'] . '</td>
                        </tr>
                        ';
            $html .= '</tbody>
                </table>';

        } else {
            $html = '';
        }

        return utf8_decode($html);
    }

    public function makeHTLMTablaConstruccion($idEmpresaColab)
    {

        $arrayMeses = $this->m_bandeja_edit_cv->getMesesCV($idEmpresaColab);

        $html = '<table style="font-size: 10px;" id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr role="row">
                            <th colspan="1" style="text-align: center;"></th>
                ';

        $count = 1;
        // $htmlCabe = '<th colspan="1">SIN FECHA</th>';
        $htmlCabe = '';
        $html0Porcen = '';
        $html25Porcen = '';
        $html50Porcen = '';
        $html76Porcen = '';
        $html100Porcen = '';

        $count0 = 0;
        $count25 = 0;
        $count50 = 0;
        $count76 = 0;
        $count100 = 0;

        $htmlCabeAnio = '<tr role="row">
                            <th colspan="1" style="text-align: center;"></th>
                            <th colspan="1" style="text-align: center;"></th>';
        $countAnio = 1;

        $arrayAnios = array();

        $stringIP0 = '';
        $stringIP25 = '';
        $stringIP50 = '';
        $stringIP76 = '';
        $stringIP100 = '';

        foreach ($arrayMeses as $row) {

            $arrayTotalesxMes = $this->m_bandeja_edit_cv->getCountxMesCV($idEmpresaColab, $row->num_mes, $row->anio);

            if (count($arrayTotalesxMes) > 0) {

                $arrayData0 = explode('|', $arrayTotalesxMes['cant_ip_avance_0']);
                $arrayData25 = explode('|', $arrayTotalesxMes['cant_ip_avance_25']);
                $arrayData50 = explode('|', $arrayTotalesxMes['cant_ip_avance_50']);
                $arrayData76 = explode('|', $arrayTotalesxMes['cant_ip_avance_75']);
                $arrayData100 = explode('|', $arrayTotalesxMes['cant_ip_avance_100']);

                if ($row->num_mes == '' || $row->num_mes == null) {

                    $htmlCabe = '<th colspan="1" style="text-align:center">' . $row->mes . '</th>';

                    $count0 = $count0 + $arrayData0[0];
                    $count25 = $count25 + $arrayData25[0];
                    $count50 = $count50 + $arrayData50[0];
                    $count76 = $count76 + $arrayData76[0];
                    $count100 = $count100 + $arrayData100[0];
                    if ($arrayData0[1] != '0') {
                        $stringIP0 .= $arrayData0[1] . ',';
                    }
                    if ($arrayData25[1] != '0') {
                        $stringIP25 .= $arrayData25[1] . ',';
                    }
                    if ($arrayData50[1] != '0') {
                        $stringIP50 .= $arrayData50[1] . ',';
                    }
                    if ($arrayData76[1] != '0') {
                        $stringIP76 .= $arrayData76[1] . ',';
                    }
                    if ($arrayData100[1] != '0') {
                        $stringIP100 .= $arrayData100[1] . ',';
                    }

                    $html0Porcen = '<td style="text-align:center"><a style="color:blue" data-arryip="' . $stringIP0 . '"      ' . ($stringIP0 == '' ? '' : 'onclick="getDetalleItemPlans(this)"') . '    >' . $count0 . '</a></td>';
                    $html25Porcen = '<td style="text-align:center"><a style="color:blue" data-arryip="' . $stringIP25 . '"    ' . ($stringIP25 == '' ? '' : 'onclick="getDetalleItemPlans(this)"') . '   >' . $count25 . '</a></td>';
                    $html50Porcen = '<td style="text-align:center"><a style="color:blue" data-arryip="' . $stringIP50 . '"    ' . ($stringIP50 == '' ? '' : 'onclick="getDetalleItemPlans(this)"') . '   >' . $count50 . '</a></td>';
                    $html76Porcen = '<td style="text-align:center"><a style="color:blue" data-arryip="' . $stringIP76 . '"    ' . ($stringIP76 == '' ? '' : 'onclick="getDetalleItemPlans(this)"') . '   >' . $count76 . '</a></td>';
                    $html100Porcen = '<td style="text-align:center"><a style="color:blue" data-arryip="' . $stringIP100 . '"  ' . ($stringIP100 == '' ? '' : 'onclick="getDetalleItemPlans(this)"') . '  >' . $count100 . '</a></td>';

                } else {
                    $htmlCabe .= '<th colspan="1" style="text-align:center">' . $row->mes . '</th>';
                    array_push($arrayAnios, $row->anio);

                    $html0Porcen .= '<td style="text-align:center"><a style="color:blue" data-arryip="' . $arrayData0[1] . '"      ' . ($arrayData0[1] == '0' ? '' : 'onclick="getDetalleItemPlans(this)"') . '    >' . $arrayData0[0] . '</a></td>';
                    $html25Porcen .= '<td style="text-align:center"><a style="color:blue" data-arryip="' . $arrayData25[1] . '"    ' . ($arrayData25[1] == '0' ? '' : 'onclick="getDetalleItemPlans(this)"') . '   >' . $arrayData25[0] . '</a></td>';
                    $html50Porcen .= '<td style="text-align:center"><a style="color:blue" data-arryip="' . $arrayData50[1] . '"    ' . ($arrayData50[1] == '0' ? '' : 'onclick="getDetalleItemPlans(this)"') . '   >' . $arrayData50[0] . '</a></td>';
                    $html76Porcen .= '<td style="text-align:center"><a style="color:blue" data-arryip="' . $arrayData76[1] . '"    ' . ($arrayData76[1] == '0' ? '' : 'onclick="getDetalleItemPlans(this)"') . '   >' . $arrayData76[0] . '</a></td>';
                    $html100Porcen .= '<td style="text-align:center"><a style="color:blue" data-arryip="' . $arrayData100[1] . '"  ' . ($arrayData100[1] == '0' ? '' : 'onclick="getDetalleItemPlans(this)"') . '  >' . $arrayData100[0] . '</a></td>';
                }

                $count++;
            }

        }

        $valores = array_count_values($arrayAnios);
        $arrayAnios = array_unique($arrayAnios);
        $arrayAnios = array_values($arrayAnios);
        $contCabeAnio = 0;

        foreach ($valores as $row) {
            $htmlCabeAnio .= '<th colspan="' . $row . '" style="text-align: center;">' . $arrayAnios[$contCabeAnio] . '</th>';
            $contCabeAnio++;
        }
        $htmlCabeAnio .= '</tr>';

        $html .= '   <th colspan="' . $count . '" style="text-align: center;">FECHA DE CONSTRUCCION</th>
                 </tr>' . $htmlCabeAnio;

        $html .= '<tr role="row">
                    <th colspan="1">% AVANCE</th>' . $htmlCabe;

        $html .= '       </tr>
                    </thead>';

        $html .= '
                    <tbody>
                        <tr>
                            <td style="text-align:center">0%</td>' . $html0Porcen . '
                        </tr>
                        <tr>
                            <td style="text-align:center">1% - 25%</td>' . $html25Porcen . '
                        </tr>
                        <tr>
                            <td style="text-align:center">26% - 50%</td>' . $html50Porcen . '
                        </tr>
                        <tr>
                            <td style="text-align:center">51% - 75%</td>' . $html76Porcen . '
                        </tr>
                        <tr>
                            <td style="text-align:center">76% - 100%</td>' . $html100Porcen . '
                        </tr>
                    </tbody>
                </table>';

        // $html = ''; //PARA POBRAR

        return utf8_decode($html);
    }

    public function makeHTLMTablaReporte2($idEmpresaColab)
    {
        $arrayCountIPxMes = $this->m_bandeja_edit_cv->getMesesPODetLOGCV($idEmpresaColab);
        $arrayAnios = array();
        $arrayMeses = array();

        $html = '<table style="font-size: 10px;" id="table-rep2" class="table table-bordered">
                    <thead class="thead-default">
                        <tr role="row">
                            <th colspan="2" style="text-align: center;"></th>
                ';

        $htmlCabeMes = '';
        $htmlBody = '';
        $count = 1;
        $espaciado = '';
        $flgLimpiar = '';
        foreach ($arrayCountIPxMes as $row) {
            array_push($arrayAnios, $row->anio);
            array_push($arrayMeses, $row->desc_mes);

            $flgLimpiar = $row->anio;

            $htmlCabeMes .= '<th colspan="1" style="text-align: center;">' . $row->desc_mes . '</th>';
            $htmlBody .= '
                        <tr>
                            <td colspan="1" style="background-color: var(--celeste_telefonica);color: white;">' . $row->anio . '</td>
                            <td colspan="1" style="background-color: var(--celeste_telefonica);color: white;">' . $row->desc_mes . '</td>
                            ' . $espaciado . '
                            <td colspan="1" style="text-align:center"><a style="color:blue" data-arryip="' . $row->cadena_ip . '" onclick="getDetalleItemPlans(this)" >' . $row->cant_ip . '</a></td>
                        </tr>';
            $count++;
            if ($count == 2) {
                $espaciado = '<td colspan="1"></td>';
            } else {
                $espaciado .='<td colspan="1"></td>';
            }
            
        }
        $html .= '  <th colspan="' . $count . '" style="text-align: center;">FECHA DE CONSTRUCCION</th>
                  </tr>
                  <tr role="row">
                    <th colspan="2" style="text-align: center;"></th>';

        $arrayAnioVertical = array_unique($arrayAnios);
        $valores = array_count_values($arrayAnios);
        $countCabeAnio = 0;
        $htmlCabeAnio = '';

        foreach ($valores as $row) {
            $htmlCabeAnio .= '<th colspan="' . $row . '" style="text-align: center;">' . $arrayAnioVertical[$countCabeAnio] . '</th>';
            $countCabeAnio++;
        }
        $html .= $htmlCabeAnio . '</tr>
                    <tr role="row">
                    <th colspan="2" style="text-align: center;"></th>
                    ' . $htmlCabeMes . '
                    </tr>
                </thead>
                <tbody>
                ' . $htmlBody . '
                </tbody>
                </table>';

        return utf8_decode($html);
    }

    public function getReporteCVByEECC()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {

            $idEmpresaColab = $this->input->post('idEmpresaColab');
            $data['limiteGroupConcat'] = $this->m_bandeja_edit_cv->quitarLimiteGroupConcat();
            $data['tablaCounts'] = $this->makeHTLMTablaConsultaCounts($this->m_bandeja_edit_cv->getCantidadesReporteCV($idEmpresaColab));
            $data['tablaConstrucc'] = $this->makeHTLMTablaConstruccion($idEmpresaColab);
            $data['tablaReporte2'] = $this->makeHTLMTablaReporte2($idEmpresaColab);
            $data['error'] = EXIT_SUCCESS;

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function getPorcentaje($max, $min)
    {
        if ($max != 0) {
            return round(($min * 100) / $max, 0) . '%';
        } else {
            return '0%';
        }

    }

    public function getDetItemsPlan()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $stringIPS = $this->input->post('stringIPS') ? trim($this->input->post('stringIPS'), ',') : null;
            $arrayIPconsulta = array();
            if ($stringIPS != null) {
                $arrayIPS = explode(',', $stringIPS);
                foreach ($arrayIPS as $row) {
                    $itemplan = "'" . $row . "'";
                    array_push($arrayIPconsulta, $itemplan);
                }

                $data['tablaDetItemplan'] = $this->makeHTLMTablaItemPLanDetalle($this->m_bandeja_edit_cv->getDetIPSByArryIPS($arrayIPS));
                $data['error'] = EXIT_SUCCESS;
            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function makeHTLMTablaItemPLanDetalle($listaIPS)
    {
        $html = '<table style="font-size: 10px;" id="tabla_detalle" class="table table-bordered">
                    <thead class="thead-default">
                        <tr role="row">
                            <th style="text-align:center">ITEMPLAN</th>
                            <th style="text-align:center">PROYECTO</th>
                            <th style="text-align:center">MDF/NODO</th>
                            <th style="text-align:center">ZONAL</th>
                            <th style="text-align:center">EECC</th>
                            <th style="text-align:center">% AVANCE</th>
                            <th style="text-align:center">FEC. COSNTRUC.</th>
                            <th style="text-align:center">ESTADO EDIFICIO</th>
                            <th style="text-align:center">COORDENADAS X/Y</th>
                            <th style="text-align:center">ESTADO</th>
                        </tr>
                    </thead>

                <tbody>';

        foreach ($listaIPS as $row) {

            $html .= '<tr>
                        <td>' . $row->itemPlan . '</td>
                        <td>' . $row->nombreProyecto . '</td>
                        <td>' . $row->codigo . ' - ' . $row->tipoCentralDesc . '</td>
                        <td>' . $row->zonalDesc . '</td>
                        <td>' . $row->empresaColabDesc . '</td>
                        <td>' . $row->avance . '</td>
                        <td>' . $row->fec_termino_constru . '</td>
                        <td>' . $row->estado_edifico . '</td>
                        <td>' . $row->coordenada_x . ' / ' . $row->coordenada_y . '</td>
                        <td>' . $row->estadoPlanDesc . '</td>
                      </tr>';
        }
        $html .= '</tbody>
                  </table>';

        return utf8_decode($html);
    }

    // public function makeHTLMTablaReporte2($idEmpresaColab)
    // {

    //     $arrayMeses = $this->m_bandeja_edit_cv->getMesesCV($idEmpresaColab);

    //     // $arrayMeses = $this->m_bandeja_edit_cv->getMesesPODetLOGCV($idEmpresaColab);

    //     $html = '<table style="font-size: 10px;" id="table-rep2" class="table table-bordered">
    //                 <thead class="thead-default">
    //                     <tr role="row">
    //                         <th colspan="1" style="text-align: center;"></th>
    //             ';

    //     $count = 1;
    //     // $htmlCabe = '<th colspan="1">SIN FECHA</th>';
    //     $htmlCabe = '';
    //     $html0Porcen = '';
    //     $html25Porcen = '';
    //     $html50Porcen = '';
    //     $html76Porcen = '';
    //     $html100Porcen = '';

    //     $count0 = 0;
    //     $count25 = 0;
    //     $count50 = 0;
    //     $count76 = 0;
    //     $count100 = 0;

    //     $htmlCabeAnio = '<tr role="row">
    //                         <th colspan="1" style="text-align: center;"></th>
    //                         ' . ($tipoReporte == 2 ? '' : '<th colspan="1" style="text-align: center;"></th>') . '';
    //     $countAnio = 1;

    //     $arrayAnios = array();

    //     $stringIP0 = '';
    //     $stringIP25 = '';
    //     $stringIP50 = '';
    //     $stringIP76 = '';
    //     $stringIP100 = '';

    //     foreach ($arrayMeses as $row) {

    //         if ($tipoReporte == 1) {
    //             $arrayTotalesxMes = $this->m_bandeja_edit_cv->getCountxMesCV($idEmpresaColab, $row->num_mes, $row->anio);
    //         } else {
    //             $arrayTotalesxMes = $this->m_bandeja_edit_cv->getCountReporte2CV($idEmpresaColab, $row->num_mes, $row->anio);
    //         }

    //         if (count($arrayTotalesxMes) > 0) {

    //             $arrayData0 = explode('|', $arrayTotalesxMes['cant_ip_avance_0']);
    //             $arrayData25 = explode('|', $arrayTotalesxMes['cant_ip_avance_25']);
    //             $arrayData50 = explode('|', $arrayTotalesxMes['cant_ip_avance_50']);
    //             $arrayData76 = explode('|', $arrayTotalesxMes['cant_ip_avance_75']);
    //             $arrayData100 = explode('|', $arrayTotalesxMes['cant_ip_avance_100']);

    //             if ($row->num_mes == '' || $row->num_mes == null) {

    //                 $htmlCabe = '<th colspan="1" style="text-align:center">' . $row->mes . '</th>';

    //                 $count0 = $count0 + $arrayData0[0];
    //                 $count25 = $count25 + $arrayData25[0];
    //                 $count50 = $count50 + $arrayData50[0];
    //                 $count76 = $count76 + $arrayData76[0];
    //                 $count100 = $count100 + $arrayData100[0];
    //                 if ($arrayData0[1] != '0') {
    //                     $stringIP0 .= $arrayData0[1] . ',';
    //                 }
    //                 if ($arrayData25[1] != '0') {
    //                     $stringIP25 .= $arrayData25[1] . ',';
    //                 }
    //                 if ($arrayData50[1] != '0') {
    //                     $stringIP50 .= $arrayData50[1] . ',';
    //                 }
    //                 if ($arrayData76[1] != '0') {
    //                     $stringIP76 .= $arrayData76[1] . ',';
    //                 }
    //                 if ($arrayData100[1] != '0') {
    //                     $stringIP100 .= $arrayData100[1] . ',';
    //                 }

    //                 $html0Porcen = '<td style="text-align:center"><a style="color:blue" data-arryip="' . $stringIP0 . '"      ' . ($stringIP0 == '' ? '' : 'onclick="getDetalleItemPlans(this)"') . '    >' . $count0 . '</a></td>';
    //                 $html25Porcen = '<td style="text-align:center"><a style="color:blue" data-arryip="' . $stringIP25 . '"    ' . ($stringIP25 == '' ? '' : 'onclick="getDetalleItemPlans(this)"') . '   >' . $count25 . '</a></td>';
    //                 $html50Porcen = '<td style="text-align:center"><a style="color:blue" data-arryip="' . $stringIP50 . '"    ' . ($stringIP50 == '' ? '' : 'onclick="getDetalleItemPlans(this)"') . '   >' . $count50 . '</a></td>';
    //                 $html76Porcen = '<td style="text-align:center"><a style="color:blue" data-arryip="' . $stringIP76 . '"    ' . ($stringIP76 == '' ? '' : 'onclick="getDetalleItemPlans(this)"') . '   >' . $count76 . '</a></td>';
    //                 $html100Porcen = '<td style="text-align:center"><a style="color:blue" data-arryip="' . $stringIP100 . '"  ' . ($stringIP100 == '' ? '' : 'onclick="getDetalleItemPlans(this)"') . '  >' . $count100 . '</a></td>';

    //             } else {
    //                 $htmlCabe .= '<th colspan="1" style="text-align:center">' . $row->mes . '</th>';
    //                 array_push($arrayAnios, $row->anio);

    //                 $html0Porcen .= '<td style="text-align:center"><a style="color:blue" data-arryip="' . $arrayData0[1] . '"      ' . ($arrayData0[1] == '0' ? '' : 'onclick="getDetalleItemPlans(this)"') . '    >' . $arrayData0[0] . '</a></td>';
    //                 $html25Porcen .= '<td style="text-align:center"><a style="color:blue" data-arryip="' . $arrayData25[1] . '"    ' . ($arrayData25[1] == '0' ? '' : 'onclick="getDetalleItemPlans(this)"') . '   >' . $arrayData25[0] . '</a></td>';
    //                 $html50Porcen .= '<td style="text-align:center"><a style="color:blue" data-arryip="' . $arrayData50[1] . '"    ' . ($arrayData50[1] == '0' ? '' : 'onclick="getDetalleItemPlans(this)"') . '   >' . $arrayData50[0] . '</a></td>';
    //                 $html76Porcen .= '<td style="text-align:center"><a style="color:blue" data-arryip="' . $arrayData76[1] . '"    ' . ($arrayData76[1] == '0' ? '' : 'onclick="getDetalleItemPlans(this)"') . '   >' . $arrayData76[0] . '</a></td>';
    //                 $html100Porcen .= '<td style="text-align:center"><a style="color:blue" data-arryip="' . $arrayData100[1] . '"  ' . ($arrayData100[1] == '0' ? '' : 'onclick="getDetalleItemPlans(this)"') . '  >' . $arrayData100[0] . '</a></td>';
    //             }

    //             $count++;
    //         }

    //     }

    //     $valores = array_count_values($arrayAnios);
    //     $arrayAnios = array_unique($arrayAnios);
    //     $arrayAnios = array_values($arrayAnios);
    //     $contCabeAnio = 0;

    //     foreach ($valores as $row) {
    //         $htmlCabeAnio .= '<th colspan="' . $row . '" style="text-align: center;">' . $arrayAnios[$contCabeAnio] . '</th>';
    //         $contCabeAnio++;
    //     }
    //     $htmlCabeAnio .= '</tr>';

    //     $html .= '   <th colspan="' . $count . '" style="text-align: center;">' . ($tipoReporte == 2 ? 'FECHA DE VISITA' : 'FECHA DE CONSTRUCCION') . '</th>
    //              </tr>' . $htmlCabeAnio;

    //     $html .= '<tr role="row">
    //                 <th colspan="1">% AVANCE</th>' . $htmlCabe;

    //     $html .= '       </tr>
    //                 </thead>';

    //     $html .= '
    //                 <tbody>
    //                     <tr>
    //                         <td style="text-align:center">0%</td>' . $html0Porcen . '
    //                     </tr>
    //                     <tr>
    //                         <td style="text-align:center">1% - 25%</td>' . $html25Porcen . '
    //                     </tr>
    //                     <tr>
    //                         <td style="text-align:center">26% - 50%</td>' . $html50Porcen . '
    //                     </tr>
    //                     <tr>
    //                         <td style="text-align:center">51% - 75%</td>' . $html76Porcen . '
    //                     </tr>
    //                     <tr>
    //                         <td style="text-align:center">76% - 100%</td>' . $html100Porcen . '
    //                     </tr>
    //                 </tbody>
    //             </table>';

    //     // $html = ''; //PARA POBRAR

    //     return utf8_decode($html);
    // }

}
