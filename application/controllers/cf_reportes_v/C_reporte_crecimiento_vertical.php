<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_reporte_crecimiento_vertical extends CI_Controller
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

            $data['listaSubProy'] = $this->m_extractor->getAllSubProyectoCV();
            $data['listaJefatura'] = $this->m_utils->getJefaturaCmb();
            $data['listaEECC'] = $this->m_utils->getAllEECC();
            $data['listafase'] = $this->m_utils->getAllFase();

            $data['limiteGroupConcat'] = $this->m_extractor->quitarLimiteGroupConcat();
            $data['tablaReporte'] = $this->makeHTLMTablaReporte($this->m_extractor->getReporteCV());

            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $permisos = $this->session->userdata('permisosArbol');
            #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_CV, 173);
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_NUEVO_MODELO_CRECIMIENTO_VERTICAL, 173, ID_MODULO_PAQUETIZADO);
            $data['opciones'] = $result['html'];
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_reportes_v/v_reporte_crecimiento_vertical', $data);
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
                            <th colspan="1" style="text-align: center;">EE. CC.</th>
                            <th colspan="1" style="text-align: center;">JEFATURA</th>
                            <th colspan="1" style="text-align: center;">Pdte. Acceso</th>
                            <th colspan="1" style="text-align: center;">Diseno</th>
                            <th colspan="1" style="text-align: center;">En Obra</th>
                            <th colspan="1" style="text-align: center;">Ejecutado</th>
                            <th colspan="1" style="text-align: center;">Cancelado</th>
                        </tr>
                    </thead>
                    <tfoot>
                      <tr style="color:#fff;background-color:#2196F3">
                          <th colspan="2" style="text-align: center;" >TOTAL</th>
                ';

        $total_ip_pre_reg = 0;
        $total_ip_diseno = 0;
        $total_ip_obra = 0;
        $total_ip_ter = 0;
        $total_ip_cance = 0;
        $htmlBody = '';

        foreach ($arrayReporte as $row) {

            $htmlBody .= '<tr>

                            <td colspan="1" style="text-align:center">' . $row->empresaColabDesc_pre_reg . '</td>
                            <td colspan="1" style="text-align:center; background-color: #ccccb3;">' . $row->jefatura_pre_reg . '</td>
                            
                            <td colspan="1" style="text-align:center"><a style="color:blue" data-arryip="' . $row->string_ip_pre_reg . '"  ' . ($row->cant_ip_pre_reg == null ? '' : 'onclick="getDetalleItemPlans(this)"') . ' >' . ($row->cant_ip_pre_reg != null ? $row->cant_ip_pre_reg : '-') . '</a></td>
                            <td colspan="1" style="text-align:center"><a style="color:blue" data-arryip="' . $row->string_ip_diseno . '"  ' . ($row->cant_ip_diseno == null ? '' : 'onclick="getDetalleItemPlans(this)"') . ' >' . ($row->cant_ip_diseno != null ? $row->cant_ip_diseno : '-') . '</a></td>
                            <td colspan="1" style="text-align:center"><a style="color:blue" data-arryip="' . $row->string_ip_obra . '"  ' . ($row->cant_ip_obra == null ? '' : 'onclick="getDetalleItemPlans(this)"') . ' >' . ($row->cant_ip_obra != null ? $row->cant_ip_obra : '-') . '</a></td>
                            <td colspan="1" style="text-align:center"><a style="color:blue" data-arryip="' . $row->string_ip_ter . '"  ' . ($row->cant_ip_ter == null ? '' : 'onclick="getDetalleItemPlans(this)"') . ' >' . ($row->cant_ip_ter != null ? $row->cant_ip_ter : '-') . '</a></td>
                            <td colspan="1" style="text-align:center"><a style="color:blue" data-arryip="' . $row->string_ip_cance . '"  ' . ($row->cant_ip_cance == null ? '' : 'onclick="getDetalleItemPlans(this)"') . ' >' . ($row->cant_ip_cance != null ? $row->cant_ip_cance : '-') . '</a></td>
                           
                      </tr> ';
            
            $total_ip_pre_reg = $total_ip_pre_reg + ($row->cant_ip_pre_reg != null ? $row->cant_ip_pre_reg : 0);
            $total_ip_diseno = $total_ip_diseno + ($row->cant_ip_diseno != null ? $row->cant_ip_diseno : 0);
            $total_ip_obra = $total_ip_obra + ($row->cant_ip_obra != null ? $row->cant_ip_obra : 0);
            $total_ip_ter = $total_ip_ter + ($row->cant_ip_ter != null ? $row->cant_ip_ter : 0);
            $total_ip_cance = $total_ip_cance + ($row->cant_ip_cance != null ? $row->cant_ip_cance : 0);
        }
        $html .= '          <th colspan="1" style="text-align: center;" >'.$total_ip_pre_reg.'</th>
                            <th colspan="1" style="text-align: center;" >'.$total_ip_diseno.'</th>
                            <th colspan="1" style="text-align: center;" >'.$total_ip_obra.'</th>
                            <th colspan="1" style="text-align: center;" >'.$total_ip_ter.'</th>
                            <th colspan="1" style="text-align: center;" >'.$total_ip_cance.'</th>
                        </tr>
                    </tfoot>
                    <tbody>
                    ' .$htmlBody. '
                    </tbody>
                 </table>';

        return utf8_decode($html);
    }

    public function getDetalleIP()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $stringIPs = $this->input->post('stringIPs') ? trim($this->input->post('stringIPs'), ',') : null;

            if ($stringIPs != null) {
                $arrayIPS = explode(',', $stringIPs);
            }

            $data['tablaDetItemplan'] = $this->makeHTLMTablaItemPLanDetalle($this->m_extractor->getDetalleIPCV($arrayIPS));
            $data['error'] = EXIT_SUCCESS;
   

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function makeHTLMTablaItemPLanDetalle($listaItemplan)
    {

        $html = '<table style="font-size: 10px;" id="tabla_detalle" class="table table-bordered">
                    <thead class="thead-default">
                        <tr role="row">
                            <th style="text-align:center">ITEMPLAN</th>
                            <th style="text-align:center">PROYECTO</th>
                            <th style="text-align:center">SUBPROY</th>
                            <th style="text-align:center">ZONAL</th>
                            <th style="text-align:center">EE.CC.</th>
                            <th style="text-align:center">JEFATURA</th>
                            <th style="text-align:center">ESTADO PLAN</th>
                        </tr>
                    </thead>

                <tbody>';
        if (count($listaItemplan) > 0) {
            foreach ($listaItemplan as $row) {


                $html .= '<tr>
                            <td>' . $row->itemPlan . '</td>
                            <td>' . $row->nombreProyecto . '</td>
                            <td>' . $row->subProyectoDesc . '</td>
                            <td>' . $row->zonalDesc . '</td>
                            <td>' . $row->empresaColabDesc . '</td>
                            <td>' . $row->jefatura . '</td>
                            <td>' . $row->estadoPlanDesc . '</td>
                        </tr>';
            }
        }
        $html .= '</tbody>
                  </table>';

        return utf8_decode($html);
    }


    public function filtrarTabla()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $idSubProyecto = $this->input->post('idSubProyecto') ? $this->input->post('idSubProyecto') : null;
            $jefatura = $this->input->post('jefatura') ? $this->input->post('jefatura') : null;
            $idEECC = $this->input->post('idEECC') ? $this->input->post('idEECC') : null;
            $idFase = $this->input->post('idFase') ? $this->input->post('idFase') : null;
            $data['tablaReporte'] = $this->makeHTLMTablaReporte($this->m_extractor->getReporteCV($idSubProyecto,$jefatura,$idEECC,$idFase));
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }


}
