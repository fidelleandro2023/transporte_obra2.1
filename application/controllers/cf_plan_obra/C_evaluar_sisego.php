<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class C_evaluar_sisego extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_plan_obra/M_evaluar_sisego', 'sisego');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index() {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {
            $data['tablaConsulta'] = $this->tablaConsulta();
            $permisos = $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, 238, 281, ID_MODULO_ADMINISTRATIVO);
            $data['opciones'] = $result['html'];
            $this->load->view('vf_plan_obra/v_evaluar_sisego', $data);
        } else {
            redirect('login', 'refresh');
        }
    }

    function tablaConsulta() {//1 evaluo
        // $dataConsulta = $this->sisego->getTablaAll();
		$dataConsulta = $this->sisego->getTablaPepConsulta();
        $html = '';

        if (count($dataConsulta) == 0) {
            $html .= '<table id="data-table" class="table table-bordered">
						<thead class="thead-default">
							<tr>
								<th>PEP</th>
								<th>FECHA DE REGISTRO</th>
								<th>ESTADO</th>
							</tr>
						</thead>                    
                    <tbody id="tb_body"></tbody></table>';
        } else {
            $html .= '<table id="tbOpexDatos" class="table table-bordered">
                                                <thead class="thead-default">
                                                    <tr>
                                                        <th>PEP</th>
                                                        <th>FECHA DE REGISTRO</th>
                                                        <th>ESTADO</th>
                                                    </tr>
                                                </thead>                    
                                                <tbody id="tb_body">';

            foreach ($dataConsulta as $row) {
//                $estado = $row->idEstadoOpex == 1 ? 'ACTIVO' : 'INACTIVO';
//                $accion = '<div class="text-center"><a onclick="editar_opex(' . "'" . $row->idOpex . "'" . ')"><i class="zmdi zmdi-edit zmdi-hc-2x"></i></a>'
//                        . '&nbsp;&nbsp;&nbsp;<a onclick="eliminar_opex(' . "'" . $row->idOpex . "'" . ')"><i class="zmdi zmdi-close-circle zmdi-hc-2x mdc-text-red"></i></a>'
//                        . '&nbsp;&nbsp;&nbsp;<a onclick="historialOpex(' . "'" . $row->idOpex . "'" . ')"><i class="zmdi zmdi-hourglass-alt zmdi-hc-2x mdc-text-red"></i></a></div>';
                $html .= '<tr>
                            <td>' . $row->pep . '</td>
                            <td>' . $row->fecha_registro . '</td>
                            <td>' . $row->estado . '</td>
						</tr>';
            }
            $html .= '</tbody></table>';
        }
        return utf8_decode($html);
    }

}
