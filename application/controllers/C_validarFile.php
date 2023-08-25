<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 *
 *
 */
class C_validarFile extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_plan_obra/m_consulta');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_log/m_log_ingfix');
		$this->load->model('mf_servicios/M_integracion_sisego_web');
        $this->load->library('lib_utils');
        $this->load->library('map_utils/coordenadas_utils');
        $this->load->helper('url');
    }
	
	public function index() {
		$logedUser = $this->session->userdata('usernameSession');
		if ($logedUser != null) {
			$rutaFile = $_GET['file'];
			$ruta = explode('/obra2.0/',$rutaFile);
			$data['ruta'] = base_url().$ruta[1];
			$this->load->view('v_validarFile', $data);
		} else {
			 redirect('404_override', 'refresh');
		}
	}
}