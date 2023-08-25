<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_solicitud_vr extends CI_Controller
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

            // Trayendo zonas permitidas al usuario
            // $zonas = $this->session->userdata('zonasSession');
            // $data['listaZonal'] = $this->m_consulta->getAllZonalIndex($zonas);
            // $data['listaSubProy'] = $this->m_utils->getAllSubProyecto();
            $data['tabsECC'] = $this->makeHTLMTabsConsulta($this->m_bandeja_edit_cv->getSolicitudMatReq(8),$this->m_bandeja_edit_cv->getSolicitudMatReq(7));

            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $permisos = $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_CV, ID_PERMISO_HIJO_SOLICITUD_VR);
            $data['opciones'] = $result['html'];
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_crecimiento_vertical/v_solicitud_vr', $data);
            } else {
                redirect('login', 'refresh');
            }
        } else {

            redirect('login', 'refresh');
        }

    }


    public function makeHTLMTabsConsulta($listaCAMPERU,$listaQUANTA)
    {


        $html = '
                    <div class="tab-container">
                        <ul class="nav nav-tabs nav-fill" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#extract1" role="tab">CAMPERU</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#extract2" role="tab">QUANTA</a>
                            </li>
                        </ul>
                    </div>  
                    
                    <div class="tab-content">
                        <div class="tab-pane active fade show" id="extract1" role="tabpanel">
                            <table  class="table table-bordered" style="font-size: 10px;" id="tableCAM">
                                <thead class="thead-default">
                                    <tr>
                                        <th>C&oacute;digo Material</th>
                                        <th>Material</th>
                                        <th>Cantidad Solicitada</th>
                                    </tr>
                                </thread>
                                <tbody>';

        foreach ($listaCAMPERU as $row) {

            $html .= '              <tr>
                                        <td>' . $row->id_material . '</td>
                                        <td>' . $row->descrip_material . '</td>
                                        <td>' . $row->q_mat_pedido . '</td>
                                    </tr>';
        }
        $html .= '              </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="extract2" role="tabpanel">
                            <table  class="table table-bordered" style="font-size: 10px;" id="tableQUANTA">
                                <thead class="thead-default">
                                    <tr>
                                        <th>C&oacute;digo Material</th>
                                        <th>Material</th>
                                        <th>Cantidad Solicitada</th>
                                    </tr>
                                </thread>
                                <tbody>';
        foreach ($listaQUANTA as $row) {

            $html .= '              <tr>
                                        <td>' . $row->id_material . '</td>
                                        <td>' . $row->descrip_material . '</td>
                                        <td>' . $row->q_mat_pedido . '</td>
                                    </tr>';
        }
        $html .= '              </tbody>
                            </table>
                        </div>
                    </div>';

        return utf8_decode($html);
    }

    public function makeHTLMTablaConsulta($listaSolicitudRetiro)
    {

        $html = '
                <table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th style="background-color: #0154a0; color: white">CUENTA SOLIC.</th>
                            <th style="background-color: #0154a0; color: white">ITEMPLAN</th>
                            <th style="background-color: #0154a0 ; color: white"># RETIRO</th>
                            <th style="background-color: #0154a0 ; color: white">MOTIVO</th>
                            <th style="background-color: #0154a0 ; color: white">USUARIO SOLICITANTE</th>
                            <th style="background-color: #0154a0 ; color: white">MONTO SOLICITADO</th>
                            <th style="background-color: #0154a0 ; color: white">ESTADO</th>
                            <th style="background-color: #0154a0 ; color: white">FECHA DE REGISTRO</th>
                        </tr>
                    </thead>

                    <tbody>';
        if ($listaSolicitudRetiro != '') {
            foreach ($listaSolicitudRetiro as $row) {

                $html .= '
                        <tr>
                            <td>' . $row->desc_cuenta . '</td>
                            <td>' . $row->itemplan . '</td>
                            <td>' . $row->nro_retiro . '</td>
                            <td>' . $row->motivo . '</td>
                            <td>' . $row->usu_solicitante . '</td>
                            <td>' . $row->monto_solicitado . '</td>
                            <td>' . $row->estado_solicitud . '</td>
                            <td>' . $row->fecha_registro . '</td>
                        </tr>
                        ';
            }
            $html .= '</tbody>
                </table>';

        } else {
            $html .= '</tbody>
                </table>';
        }

        return utf8_decode($html);
    }

}
