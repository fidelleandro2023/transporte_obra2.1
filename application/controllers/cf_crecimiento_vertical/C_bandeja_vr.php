<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_bandeja_vr extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_crecimiento_vertical/m_bandeja_edit_cv');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index()
    {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {
            //$data['listaEECC'] = $this->m_utils->getAllEECC();
            // $data['listaZonal'] = $this->m_utils->getAllZonal();
            //$data['listaSubProy'] = $this->m_utils->getAllSubProyecto();
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaVR($this->m_bandeja_edit_cv->getReporteVR(null, null, null, null));
            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $permisos = $this->session->userdata('permisosArbol');
            #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_CV, ID_PERMISO_HIJO_BANDEJA_EDIT);
			$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_NUEVO_MODELO_CRECIMIENTO_VERTICAL, 107, ID_MODULO_PAQUETIZADO);
			$data['opciones'] = $result['html'];
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_crecimiento_vertical/v_bandeja_vr', $data);
            } else {
                redirect('login', 'refresh');
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function makeHTLMTablaBandejaVR($listaReporteVR)
    {

        $html = '<table style="font-size: 10px;" id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>EECC</th>
                            <th>C&oacute;digo Material</th>
                            <th>Material</th>
                            <th>Cantidad VR</th>
                            <th>VR Registrada</th>
                            <th>VR Contabilizado</th>
                            <th>VR Pendiente</th>
                            <th>Liquidaci&oacute;n PO</th>
                            <th>Stock Actual</th>
                            <th>% Consumo</th>
                        </tr>
                    </thead>

                    <tbody>';

        foreach ($listaReporteVR as $row) {

            $html .= ' <tr>
                            <td>' . $row->empresaColabDesc . '</td>
                            <td>' . $row->id_material . '</td>
                            <td>' . $row->descrip_material . '</td>
                            <td>' . $row->cantidad_VR . '</td>
							<td>' . $row->total_cant_nec . '</td>
							<td>' . $row->total_cant_red . '</td>
							<td>' . $row->total_cant_dif . '</td>
                            <td>
                                <a style="color:blue" data-idmaterial="' . $row->id_material . '"  data-ideecc="' . $row->idEmpresaColab . '" data-desceecc="' . $row->empresaColabDesc . '" onclick="getDetalleItemPlans(this)">' . $row->total_liqui . '</a>
                            </td>
                            <td>' . $row->stock_actual . '</td>
                            <td>' . $row->porcentaje_consumo . '</td>
						</tr>';
        }
        $html .= '</tbody>
                </table>';

        return utf8_decode($html);
    }

    public function getDetItemsPlan()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $idMaterial = $this->input->post('idMaterial') ? $this->input->post('idMaterial') : null;
            $idEmpresaColab = $this->input->post('idEECC') ? $this->input->post('idEECC') : null;
            $descEmpresaColab = $this->input->post('descEECC');
            if ($idMaterial != null && $idEmpresaColab != null) {
                $data['descEmpresaColab'] = $descEmpresaColab;
                $data['tablaDetalleItemPlan'] = $this->makeHTLMTablaItemPLanDetalle($this->m_bandeja_edit_cv->getDetalleItemsPlanByMaterial($idMaterial, $idEmpresaColab));
                $data['error'] = EXIT_SUCCESS;
            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function makeHTLMTablaItemPLanDetalle($listaItemsPlan)
    {
        $html = '<table style="font-size: 10px;" id="tabla_detalle" class="table table-bordered">
                    <thead class="thead-default">
                        <tr role="row">
                            <th style="text-align:center">ITEMPLAN</th>
                            <th style="text-align:center">PROYECTO</th>
                            <th style="text-align:center">TOTAL MATERIAL</th>
                        </tr>
                    </thead>

                <tbody>';

        foreach ($listaItemsPlan as $row) {

            $html .= '<tr>
                        <td>' . $row->itemPlan . '</td>
                        <td>' . $row->nombreProyecto . '</td>
                        <td>' . $row->total . '</td>
                      </tr>';
        }
        $html .= '</tbody>
                  </table>';

        return utf8_decode($html);
    }

    public function getReportVRByFiltros()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $idEmpresaColab = $this->input->post('idEmpresaColab') ? trim($this->input->post('idEmpresaColab'), "'") : null;
            $fechaInicio = $this->input->post('fechaInicio');
            $fechaFin = $this->input->post('fechaFin');
            
            $data['tablaHTML'] = $this->makeHTLMTablaBandejaVR($this->m_bandeja_edit_cv->getReporteVR($idEmpresaColab, null, $fechaInicio, $fechaFin));
            $data['error'] = EXIT_SUCCESS;

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

}
