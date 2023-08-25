<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_reporte_obras_publicas extends CI_Controller
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
            $data['listaSubProy'] = $this->m_utils->getAllSubProyectoByProyecto(4);
            $data['listaJefatura'] = $this->m_utils->getJefaturaCmb();
            $data['listafase'] = $this->m_utils->getAllFase();

            $arrayReporte = $this->m_extractor->getReporteOPxSubProy();
            $data['tablaReporte'] = $this->makeHTLMTablaReporte($arrayReporte);

            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $permisos = $this->session->userdata('permisosArbol');
            #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_CERTIFICACION_MO, ID_PERMISO_HIJO_REPORTE_OP_SUBPROY);
            $result = $this->lib_utils->getHTMLPermisos($permisos, 239, ID_PERMISO_HIJO_REPORTE_OP_SUBPROY, ID_MODULO_PAQUETIZADO);
            $data['opciones'] = $result['html'];
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_reportes_v/v_reporte_obras_publicas', $data);
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
                            <th>SUBPROYECTO</th>
                            <th>ITEMPLAN</th>
                            <th>FASE</th>
                            <th>JEFATURA</th>
                            <th style="background-color: #99ccff;">COSTO TOTAL MAT</th>
                            <th style="background-color: #99ccff;">COSTO TOTAL MO</th>
                            <th style="background-color: #99ccff;">COSTO DE OBRA</th>
                            <th>OP GRAVADA SIN IGV</th>
                            <th>OP GRAVADA RED SIN IGV</th>
                            <th>COSTO ADJUDICADO OBRA</th>
                            <th>IGV</th>
                            <th>IMPORTE TOTAL</th>
                            <th>SALDO</th>
                        </tr>
                    </thead>
                    <tbody>
                ';

        foreach ($arrayReporte as $row) {

            $html .= '<tr>
                            <td>' . $row->subProyectoDesc . '</td>
                            <td>' . $row->itemplan . '</td>
                            <td>' . $row->faseDesc . '</td>
                            <td>' . $row->jefatura . '</td>
                            <td style="text-align:center;">' . ($row->costo_total_mat_po != null ? number_format($row->costo_total_mat_po,2) : '-' ) . '</td>
                            <td style="text-align:center;">' . ($row->costo_total_mo_po != null ? number_format($row->costo_total_mo_po,2) : '-' ) . '</td>
                            <td style="text-align:center;">' . ($row->costo_obra != '-' ? number_format($row->costo_obra,2) : '-' ) . '</td>
                            <td style="text-align:center;">' . ($row->op_grabada_sin_igv != null ? number_format($row->op_grabada_sin_igv,2) : '-' ) . '</td>
                            <td style="text-align:center;">' . ($row->op_red_sin_igv_convenio != null ? number_format($row->op_red_sin_igv_convenio,2) : '-' ) . '</td>
                            <td style="text-align:center;">' . ($row->costo_obra_convenio != null ? number_format($row->costo_obra_convenio,2) : '-' ) . '</td>
                            <td style="text-align:center;">' . ($row->igv_convenio != null ? number_format($row->igv_convenio,2) : '-' ) . '</td>
                            <td style="text-align:center;">' . ($row->importe_total != null ? number_format($row->importe_total,2) : '-' ) . '</td>
                            <td style="text-align:center;">' . ($row->saldo_total != '-' ? number_format($row->saldo_total,2) : '-' ) . '</td>
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
            $subproyecto = $this->input->post('subproyecto') ? $this->input->post('subproyecto') : null;
            $jefatura = $this->input->post('jefatura') ? $this->input->post('jefatura') : null;
            $idFase = $this->input->post('idFase') ? $this->input->post('idFase') : null;
            $data['tablaReporte1'] = $this->makeHTLMTablaReporte($this->m_extractor->getReporteOPxSubProy($subproyecto,$jefatura,$idFase));
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

}
