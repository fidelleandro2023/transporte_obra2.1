<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class C_control_tramas_bloq extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_panel_control/M_control_tramas_bloq', 'trama');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index() {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {
            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $data['tablaConsultaConfigOpex'] = $this->getTramaBloq();
            $permisos = $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_GESTION_MANTENIMIENTO, ID_PERMISO_HIJO_REGISTRO_OPEX, ID_MODULO_GESTION_MANTENIMIENTO, ID_PERMISO_PADRE_MODULO_OPEX);
            $data['opciones'] = $result['html'];
            $this->load->view('vf_panel_control/v_control_tramas_bloq', $data);
        } else {
            redirect('login', 'refresh');
        }
    }

    function getTramaBloq() {
        $dataConsulta = $this->trama->getTramaBloq();
        $html = '';

        if (count($dataConsulta) == 0) {
            $html .= '<table id="data-table" class="table table-bordered" style="width:100%">
                                                <thead class="thead-default">
                                                    <tr>
                                                        <th>CODIGO PO</th>
                                                        <th>PEP 1</th>
                                                        <th>PEP 1</th>
                                                        <th>GRAFO</th>
                                                        <th>FECHA</th>
                                                    </tr>
                                                </thead>                    
                    <tbody id="tb_body"></tbody></table>';
        } else {
            $html .= '<table id="data-table" class="table table-bordered" style="width:100%">
                                                <thead class="thead-default">
                                                    <tr>
                                                        <th>CODIGO PO</th>
                                                        <th>PEP 1</th>
                                                        <th>PEP 1</th>
                                                        <th>GRAFO</th>
                                                        <th>FECHA</th>
                                                    </tr>
                                                </thead>                    
                                                <tbody id="tb_body">';

            foreach ($dataConsulta as $row) {
                $html .= '<tr>
                            <td>' . $row->codigo_po . '</td>
                            <td>' . $row->pep1 . '</td>
                            <td>' . $row->pep2 . '</td>
                            <td>' . $row->grafo . '</td>
                            <td>' . $row->fecha_registro . '</td></tr>';
            }
            $html .= '</tbody></table>';
        }
        return utf8_decode($html);
    }

}
