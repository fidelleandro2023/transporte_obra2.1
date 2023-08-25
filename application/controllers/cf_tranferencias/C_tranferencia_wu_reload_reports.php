<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 *
 * @author CARLOS ZAVALA C.
 * 18/01/2018
 *
 */
class C_tranferencia_wu_reload_reports extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_tranferencias/m_tranferencia_wu');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_extractor/m_extractor');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index()
    {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {
            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $permisos = $this->session->userdata('permisosArbol');
		    $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_NUEVO_MODELO_TRANSFERENCIAS, 280, ID_MODULO_PAQUETIZADO);
            $data['opciones'] = $result['html'];
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_tranferencias/v_tranferencia_wu_reload_reports', $data);
            } else {
                redirect('login', 'refresh');
            }
        } else {
            redirect('login', 'refresh');
        }

    }

}
