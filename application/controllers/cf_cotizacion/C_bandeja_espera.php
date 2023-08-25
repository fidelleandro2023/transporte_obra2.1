<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class C_bandeja_espera extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_cotizacion/m_bandeja_espera');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index() {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {
            $data['tablaConsulta'] = $this->tablaConsulta();
            $permisos = $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_NUEVO_MODELO_COTIZACION, 295, ID_MODULO_PAQUETIZADO);
            $data['opciones'] = $result['html'];
            $this->load->view('vf_cotizacion/v_bandeja_espera', $data);
        } else {
            redirect('login', 'refresh');
        }
    }

    function tablaConsulta() {
		$dataConsulta = $this->m_bandeja_espera->getTablaAll();
        $html = '';

		$html .= '<table id="data-table" class="table table-bordered">
                                                <thead class="thead-default">
                                                    <tr>
                                                        <th>COD COTIZACION</th>
                                                        <th>SISEGO</th>
                                                        <th>EECC</th>
														<th>TIPO</th>
														<th>ESTADO</th>
														<th>FECHA REGISTRO</th>
                                                    </tr>
                                                </thead>                    
                                                <tbody id="tb_body">';

		foreach ($dataConsulta as $row) {
				$html .= '<tr>
                            <td>' . $row['codigo_cluster']. '</td>
                            <td>' . $row['sisego']. '</td>
                            <td>' . $row['empresaColabDesc'] . '</td>
							<td>' . $row['flg_principal'] . '</td>
							<td>' . $row['estadoDesc'] . '</td>
							<td>' . $row['fecha_registro'] . '</td>
						</tr>';
            }
            $html .= '</tbody></table>';
        return utf8_decode($html);
    }

}
