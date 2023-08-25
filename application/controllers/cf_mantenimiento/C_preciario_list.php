<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class C_preciario_list extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_mantenimiento/M_preciario_list', 'preciario');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index() {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {
            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $data['tablaConsultaConfigOpex'] = $this->getPreciario();
            $permisos = $this->session->userdata('permisosArbol');
           $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_MANTENIMIENTO_NUEVO_MODELO, 287, ID_MODULO_MANTENIMIENTO);
        	   $data['opciones'] = $result['html'];
            $this->load->view('vf_mantenimiento/v_preciario_list', $data);
        } else {
            redirect('login', 'refresh');
        }
    }

    function getPreciario() {
        $dataConsulta = $this->preciario->getPreciario();
        $html = '';

        if (count($dataConsulta) == 0) {
            $html .= '<table id="data-table" class="table table-bordered" style="width:100%">
                                                <thead class="thead-default">
                                                    <tr>
                                                        <th>EMPRESA COLABORADORA</th>
                                                        <th>TIPO PRECIARIO</th>
                                                        <th>JEFATURA</th>
                                                        <th>COSTO</th>
                                                    </tr>
                                                </thead>                    
                    <tbody id="tb_body"></tbody></table>';
        } else {
            $html .= '<table id="data-table" class="table table-bordered" style="width:100%">
                                                <thead class="thead-default">
                                                    <tr>
                                                       <th>EMPRESA COLABORADORA</th>
                                                        <th>TIPO PRECIARIO</th>
                                                        <th>JEFATURA</th>
                                                        <th>COSTO</th>
                                                    </tr>
                                                </thead>                    
                                                <tbody id="tb_body">';

            foreach ($dataConsulta as $row) {
                $html .= '<tr>
                            <td>' . $row->empresaColabDesc . '</td>
                            <td>' . $row->tipo . '</td>
                            <td>' . $row->jefa . '</td>
                            <td>' . $row->costo . '</td></tr>';
            }
            $html .= '</tbody></table>';
        }
        return utf8_decode($html);
    }

}
