<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_reporte_certificacion_presupuesto extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_plan_obra/m_consulta');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_extractor/m_extractor');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index()
    {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {
            $data['listaProy'] = $this->m_utils->getAllProyecto();
            $data['listaJefatura'] = $this->m_utils->getJefaturaCmb();
            $data['listaEECC'] = $this->m_extractor->getAllEECCPTR();
            $data['listafase'] = $this->m_utils->getAllFase();

            $data['limiteGroupConcat'] = $this->m_extractor->quitarLimiteGroupConcat();

            $arrayReporte = $this->m_extractor->getReporteCertCVByJefatura2();
            $data['tablaReporte'] = $this->makeHTLMTablaReporte2($arrayReporte);

            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $permisos = $this->session->userdata('permisosArbol');
            #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_CERTIFICACION_MO, ID_PERMISO_HIJO_REPORTE_CERTI_CV_MO_PRESU);
            $result = $this->lib_utils->getHTMLPermisos($permisos, 250, ID_PERMISO_HIJO_REPORTE_CERTI_CV_MO_PRESU, ID_MODULO_ADMINISTRATIVO);
            $data['opciones'] = $result['html'];
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_reportes_v/v_reporte_certificacion_presupuesto', $data);
            } else {
                redirect('login', 'refresh');
            }
        } else {

            redirect('login', 'refresh');
        }

    }

    public function makeHTLMTablaReporte($arrayReporte)
    {

        $html = '<table style="font-size: 10px;" id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr role="row">
                            <th colspan="2" rowspan="2" style="text-align: center;"></th>
                            <th colspan="6" style="text-align: center;">CERTIFICABLE</th>
                            <th colspan="1" rowspan="2" style="text-align: center;"><button class="btn btn-success waves-effect" type="button" onclick="mostrarDetalle()">Detalle</button></th>
                        </tr>
                        <tr role="row">
                            <th colspan="2" style="text-align: center;">PDTE. VALIDACION</th>
                            <th colspan="2" style="text-align: center;">VALIDADO</th>
                            <th colspan="2" style="text-align: center;">CERTIFICADO</th>
                        </tr>
                        <tr role="row">
                            <th colspan="1" style="text-align: center;">JEFATURA</th>
                            <th colspan="1" style="text-align: center;">EXPEDIENTE</th>
                            
                            <th colspan="1" style="text-align: center;"># PTRS</th>
                            <th colspan="1" style="text-align: center;">MONTO TOTAL MO</th>
                            <th colspan="1" style="text-align: center;"># PTRS</th>
                            <th colspan="1" style="text-align: center;">MONTO TOTAL MO</th>
                            <th colspan="1" style="text-align: center;"># PTRS</th>
                            <th colspan="1" style="text-align: center;">MONTO TOTAL MO</th>
                            <th colspan="1" style="text-align: center;">TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                ';

        foreach ($arrayReporte as $row) {

            $html .= '<tr>

                            <td colspan="1" style="text-align:center; background-color: #ccccb3;">' . $row->jefatura_ve . '</td>
                            <td colspan="1" style="text-align:center">SI</td>

                            <td colspan="1" style="text-align:center"><a style="color:blue" data-arryip="' . $row->string_ips_nve . '"  data-arryptr="' . $row->string_ptrs_nve . '"   ' . ($row->cant_ptr_nve == '-' ? '' : 'onclick="getDetalleItemPlans(this)"') . '    >' . $row->cant_ptr_nve . '</a></td>
                            <td colspan="1" style="text-align:center">' . $row->total_monto_mo_nve . '</td>

                            <td colspan="1" style="text-align:center"><a style="color:blue" data-arryip="' . $row->string_ips_ve . '"  data-arryptr="' . $row->string_ptrs_ve . '"   ' . ($row->cant_ptr_ve == '-' ? '' : 'onclick="getDetalleItemPlans(this)"') . '    >' . $row->cant_ptr_ve . '</a></td>
                            <td colspan="1" style="text-align:center">' . $row->total_monto_mo_ve . '</td>

                            <td colspan="1" style="text-align:center"><a style="color:blue" data-arryip="' . $row->string_ips_cce . '"  data-arryptr="' . $row->string_ptrs_cce . '" data-flgcertificado="1"  ' . ($row->cant_ptr_cce == '-' ? '' : 'onclick="getDetalleItemPlans(this)"') . '    >' . $row->cant_ptr_cce . '</a></td>
                            <td colspan="1" style="text-align:center">' . $row->total_monto_mo_cce . '</td>
                            <td colspan="1" style="text-align:center">' . number_format( ( ($row->total_monto_mo_nve_sf) == '-' ? 0 : $row->total_monto_mo_nve_sf ) + ( ($row->total_monto_mo_ve_sf) == '-' ? 0 : $row->total_monto_mo_ve_sf ) , 2) . '</td>

                          </tr>
                          <tr>

                            <td colspan="1" style="text-align:center; background-color: #ccccb3;">' . $row->jefatura_ve . '</td>
                            <td colspan="1" style="text-align:center">NO</td>

                            <td colspan="1" style="text-align:center"><a style="color:blue" data-arryip="' . $row->string_ips_nvse . '"  data-arryptr="' . $row->string_ptrs_nvse . '"   ' . ($row->cant_ptr_nvse == '-' ? '' : 'onclick="getDetalleItemPlans(this)"') . '    >' . $row->cant_ptr_nvse . '</a></td>
                            <td colspan="1" style="text-align:center">' . $row->total_monto_mo_nvse . '</td>

                            <td colspan="1" style="text-align:center"><a style="color:blue" > - </a></td>
                            <td colspan="1" style="text-align:center"> - </td>

                            <td colspan="1" style="text-align:center"><a style="color:blue"> - </a></td>
                            <td colspan="1" style="text-align:center"> - </td>
                            <td colspan="1" style="text-align:center">' . $row->total_monto_mo_nvse . '</td>

                          </tr>

                          ';
        }
        $html .= '  </tbody>
                  </table>';

        return utf8_decode($html);
    }

    public function getDetPTRsCertCV()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $stringIPS = $this->input->post('stringIPS') ? trim($this->input->post('stringIPS'), ',') : null;
            $stringPTRS = $this->input->post('stringPTRS') ? trim($this->input->post('stringPTRS'), ',') : null;
            $flgCertificado = $this->input->post('flgCertificado') ? $this->input->post('flgCertificado') : null;
            $arrayIPconsulta = array();
            $arrayPTRconsulta = array();
            if ($stringIPS != null && $stringPTRS != null) {
                $arrayIPS = explode(',', $stringIPS);
                $arrayPTRS = explode(',', $stringPTRS);
                // $count = 0;
                // foreach ($arrayIPS as $row) {
                //     $itemplan = "'" . $row . "'";
                //     $ptr = "'" . $arrayPTRS[$count] . "'";
                //     array_push($arrayIPconsulta, $itemplan);
                //     array_push($arrayPTRconsulta, $ptr);
                //     $count++;
                // }

                $data['tablaDetItemplan'] = $this->makeHTLMTablaItemPLanDetalle($arrayIPS, $arrayPTRS, $flgCertificado);
                $data['error'] = EXIT_SUCCESS;
            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function makeHTLMTablaItemPLanDetalle($arrayIPS, $arrayPTRS, $flgCertificado = null)
    {

        $htmlCabeCertificado = '';
        $htmlBodyCertificado = '';

        if($flgCertificado != null && $flgCertificado == 1){

            $listaPTRs = $this->m_extractor->getDetPTRsCertificados($arrayIPS, $arrayPTRS);

            $htmlCabeCertificado = '<th style="text-align:center">HOJA DE GESTION</th>
                                    <th style="text-align:center">ORDEN DE COMPRA</th>
                                    <th style="text-align:center">NRO CERTIFICACION</th>';
        }else{
            $listaPTRs = $this->m_extractor->getDetPTRsByArryPTRsIPs($arrayIPS, $arrayPTRS);
        }

        $html = '<table style="font-size: 10px;" id="tabla_detalle" class="table table-bordered">
                    <thead class="thead-default">
                        <tr role="row">
                            <th style="text-align:center">ITEMPLAN</th>
                            <th style="text-align:center">PTR</th>
                            <th style="text-align:center">AREA</th>
                            <th style="text-align:center">ESTACION</th>
                            <th style="text-align:center">PROYECTO</th>
                            <th style="text-align:center">SUBPROYECTO/th>
                            <th style="text-align:center">ESTADO PTR</th>
                            <th style="text-align:center">ESTADO IP</th>
                            <th style="text-align:center">EECC</th>
                            <th style="text-align:center">JEFATURA</th>
                            <th style="text-align:center">MONTO MO</th>
                            '.$htmlCabeCertificado.'
                        </tr>
                    </thead>

                <tbody>';
        if (count($listaPTRs) > 0) {
            foreach ($listaPTRs as $row) {

                if($flgCertificado == 1){
                    $htmlBodyCertificado = '<td>' . $row->hoja_gestion . '</td>
                                            <td>' . $row->orden_compra . '</td>
                                            <td>' . $row->nro_certificacion . '</td>';
                }
                $html .= '<tr>
                            <td>' . $row->itemplan . '</td>
                            <td>' . $row->ptr . '</td>
                            <td>' . $row->area . '</td>
                            <td>' . $row->estacion . '</td>
                            <td>' . $row->proyecto . '</td>
                            <td>' . $row->subproyecto . '</td>
                            <td>' . $row->estado_ptr . '</td>
                            <td>' . $row->estado_ip . '</td>
                            <td>' . $row->desc_empresacolab . '</td>
                            <td>' . $row->jefatura . '</td>
                            <td>' . $row->monto_mo . '</td>
                            '.$htmlBodyCertificado.'
                        </tr>';
            }
        }
        $html .= '</tbody>
                  </table>';

        return utf8_decode($html);
    }

    public function getReport2CertMO()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $proyecto = $this->input->post('proyecto') ? $this->input->post('proyecto') : null;
            $jefatura = $this->input->post('jefatura') ? $this->input->post('jefatura') : null;
            $eecc = $this->input->post('eecc') ? $this->input->post('eecc') :null;
            $idFase = $this->input->post('idFase') ? $this->input->post('idFase') : null;
            $data['tablaReporte2'] = $this->makeHTLMTablaReporte2($this->m_extractor->getReporteCertCVByJefatura2($proyecto,$jefatura,$eecc,$idFase));
            $data['error'] = EXIT_SUCCESS;

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function makeHTLMTablaReporte2($arrayReporte)
    {
        $html = '<table style="font-size: 10px;" id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr role="row">
                            <th colspan="2" rowspan="2" style="text-align: center;"></th>
                            <th colspan="1" rowspan="2" style="text-align: center;"></th>
                            <th colspan="6" style="text-align: center;">CERTIFICABLE</th>
                            <th colspan="1" rowspan="2" style="text-align: center;"></th>
            
                        </tr>
                        <tr role="row">
                            <th colspan="2" style="text-align: center;">PDTE. VALIDACION</th>
                            <th colspan="2" style="text-align: center;">VALIDADO</th>
                            <th colspan="2" style="text-align: center;">CERTIFICADO</th>
                        </tr>
                        <tr role="row">
                            <th colspan="1" style="text-align: center;">JEFATURA</th>
                            <th colspan="1" style="text-align: center;">EXPEDIENTE</th>
            
                            <th colspan="1" style="text-align: center;">PRESUPUESTO</th>
            
                            <th colspan="1" style="text-align: center;"># PTRS</th>
                            <th colspan="1" style="text-align: center;">MONTO TOTAL MO</th>
                            <th colspan="1" style="text-align: center;"># PTRS</th>
                            <th colspan="1" style="text-align: center;">MONTO TOTAL MO</th>
                            <th colspan="1" style="text-align: center;"># PTRS</th>
                            <th colspan="1" style="text-align: center;">MONTO TOTAL MO</th>
                            <th colspan="1" style="text-align: center;">TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                ';

        foreach ($arrayReporte as $row) {
            //log_message('error', 'value:'.$row->total_con_presupuesto_nve);
            $html .= '<tr>

                            <td colspan="1" style="text-align:center; background-color: #ccccb3;">' . $row->jefatura_ve . '</td>
                            <td colspan="1" style="text-align:center">SI</td>
                            <td colspan="1" style="text-align:center">CON PRESUPUESTO</td>

                            <td colspan="1" style="text-align:center"><a style="color:blue" data-arryip="' . $row->string_item_con_presupuesto_nve . '"  data-arryptr="' . $row->string_ptrs_con_presupuesto_nve . '"   ' . ($row->con_presupuesto_nve == '-' ? '' : 'onclick="getDetalleItemPlans(this)"') . '    >' . $row->con_presupuesto_nve . '</a></td>
                            <td colspan="1" style="text-align:center">'.(($row->total_con_presupuesto_nve!='-') ? number_format($row->total_con_presupuesto_nve,2,'.', ',') : '0.00').'</td>

                            <td colspan="1" style="text-align:center"><a style="color:blue" data-arryip="' . $row->string_item_con_presupuesto_ve . '"  data-arryptr="' . $row->string_ptrs_con_presupuesto_ve . '"   ' . ($row->con_presupuesto_ve == '-' ? '' : 'onclick="getDetalleItemPlans(this)"') . '    >' . $row->con_presupuesto_ve . '</a></td>
                            <td colspan="1" style="text-align:center">' . (($row->total_con_presupuesto_ve!='-') ? number_format($row->total_con_presupuesto_ve,2,'.', ',') : '0.00'). '</td>

                            <td colspan="1" style="text-align:center"><a style="color:blue" data-arryip="' . $row->string_ips_cce . '"  data-arryptr="' . $row->string_ptrs_cce . '" data-flgcertificado="1"  ' . ($row->cant_ptr_cce == '-' ? '' : 'onclick="getDetalleItemPlans(this)"') . '    >' . $row->cant_ptr_cce . '</a></td>
                            <td colspan="1" style="text-align:center">' . $row->total_monto_mo_cce . '</td>
                                
                            <td colspan="1" style="text-align:center">' . number_format( ( ($row->total_con_presupuesto_nve) == '-' ? 0 : $row->total_con_presupuesto_nve ) + ( ($row->total_con_presupuesto_ve) == '-' ? 0 : $row->total_con_presupuesto_ve ) , 2) . '</td>

                          </tr>
                          <tr>

                            <td colspan="1" style="text-align:center; background-color: #ccccb3;">' . $row->jefatura_ve . '</td>
                            <td colspan="1" style="text-align:center">SI</td>
                            <td colspan="1" style="text-align:center">SIN PRESUPUESTO</td>
                                
                            <td colspan="1" style="text-align:center"><a style="color:blue" data-arryip="' . $row->string_item_sin_presupuesto_nve . '"  data-arryptr="' . $row->string_ptrs_sin_presupuesto_nve . '"   ' . ($row->sin_presupuesto_nve == '-' ? '' : 'onclick="getDetalleItemPlans(this)"') . '    >' . $row->sin_presupuesto_nve . '</a></td>
                            <td colspan="1" style="text-align:center">' .(($row->total_sin_presupuesto_nve!='-') ? number_format($row->total_sin_presupuesto_nve,2,'.', ',') : '0.00'). '</td>

                            <td colspan="1" style="text-align:center"><a style="color:blue" data-arryip="' . $row->string_item_sin_presupuesto_ve . '"  data-arryptr="' . $row->string_ptrs_sin_presupuesto_ve . '"   ' . ($row->sin_presupuesto_ve == '-' ? '' : 'onclick="getDetalleItemPlans(this)"') . '    >' . $row->sin_presupuesto_ve . '</a></td>
                            <td colspan="1" style="text-align:center">' . (($row->total_sin_presupuesto_ve!='-') ? number_format($row->total_sin_presupuesto_ve,2,'.', ',') : '0.00'). '</td>

                            <td colspan="1" style="text-align:center"><a style="color:blue"> - </a></td>
                            <td colspan="1" style="text-align:center"> - </td>
                            <td colspan="1" style="text-align:center">' . number_format( ( ($row->total_sin_presupuesto_nve) == '-' ? 0 : $row->total_sin_presupuesto_nve ) + ( ($row->total_sin_presupuesto_ve) == '-' ? 0 : $row->total_sin_presupuesto_ve ) , 2) . '</td>

                          </tr>
                           <tr>

                            <td colspan="1" style="text-align:center; background-color: #ccccb3;">' . $row->jefatura_ve . '</td>
                            <td colspan="1" style="text-align:center">NO</td>
                            <td colspan="1" style="text-align:center">CON PRESUPUESTO</td>
                                
                            <td colspan="1" style="text-align:center"><a style="color:blue" data-arryip="' . $row->string_item_con_presupuesto_nvse . '"  data-arryptr="' . $row->string_ptrs_con_presupuesto_nvse . '"   ' . ($row->con_presupuesto_nvse == '-' ? '' : 'onclick="getDetalleItemPlans(this)"') . '    >' . $row->con_presupuesto_nvse . '</a></td>
                            <td colspan="1" style="text-align:center">' . number_format($row->total_con_presupuesto_nvse,2,'.', ','). '</td>

                            <td colspan="1" style="text-align:center"><a style="color:blue" > - </a></td>
                            <td colspan="1" style="text-align:center"> - </td>

                            <td colspan="1" style="text-align:center"><a style="color:blue"> - </a></td>
                            <td colspan="1" style="text-align:center"> - </td>
                            <td colspan="1" style="text-align:center">' . number_format((($row->total_con_presupuesto_nvse) == '-' ? 0 : $row->total_con_presupuesto_nvse ),2,'.', ',') . '</td>

                          </tr>
                            <tr>

                            <td colspan="1" style="text-align:center; background-color: #ccccb3;">' . $row->jefatura_ve . '</td>
                            <td colspan="1" style="text-align:center">NO</td>
                            <td colspan="1" style="text-align:center">SIN PRESUPUESTO</td>
                                
                            <td colspan="1" style="text-align:center"><a style="color:blue" data-arryip="' . $row->string_item_sin_presupuesto_nvse . '"  data-arryptr="' . $row->string_ptrs_sin_presupuesto_nvse . '"   ' . ($row->sin_presupuesto_nvse == '-' ? '' : 'onclick="getDetalleItemPlans(this)"') . '    >' . $row->sin_presupuesto_nvse . '</a></td>
                            <td colspan="1" style="text-align:center">' .number_format($row->total_sin_presupuesto_nvse,2,'.', ','). '</td>

                            <td colspan="1" style="text-align:center"><a style="color:blue" > - </a></td>
                            <td colspan="1" style="text-align:center"> - </td>

                            <td colspan="1" style="text-align:center"><a style="color:blue"> - </a></td>
                            <td colspan="1" style="text-align:center"> - </td>
                            <td colspan="1" style="text-align:center">' . number_format((($row->total_sin_presupuesto_nvse) == '-' ? 0 : $row->total_sin_presupuesto_nvse ),2,'.', ',') . '</td>
                                

                          </tr>';
        }
        $html .= '  </tbody>
                  </table>';

        return utf8_decode($html);
    }

    public function filtrarTabla()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $proyecto = $this->input->post('proyecto') ? $this->input->post('proyecto') : null;
            $jefatura = $this->input->post('jefatura') ? $this->input->post('jefatura') : null;
            $eecc = $this->input->post('eecc') ? $this->input->post('eecc') :null;
            $idFase = $this->input->post('idFase') ? $this->input->post('idFase') : null;
            $data['tablaReporte1'] = $this->makeHTLMTablaReporte2($this->m_extractor->getReporteCertCVByJefatura2($proyecto,$jefatura,$eecc,$idFase));
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }


}
