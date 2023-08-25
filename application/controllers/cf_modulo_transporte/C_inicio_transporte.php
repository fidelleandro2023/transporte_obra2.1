<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_inicio_transporte extends CI_Controller{
    function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
        $this->load->library('excel'); 
    }
    
    public function index(){
        $logedUser = $this->session->userdata('usernameSession');
        
        if($logedUser != null){
            $pepsub = 0;
            $user = $this->session->userdata('idPersonaSession');
            $zonasUser = $this->session->userdata('zonasSession');
        
            $fecha_dp = $this->m_utils->getMaxFechaFileDetallePlan();
            $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
            $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
            $permisos =  $this->session->userdata('permisosArbol');

            $result = $this->lib_utils->getHTMLPermisosV2($permisos, ID_PERMISO_PADRE_PAQUETIZADO, ID_PERMISO_HIJO_PQT_BIENVENIDA, 8);
            // $result      =  $this->lib_utils->getHTMLPermisosV2($permisos, ID_PERMISO_PADRE_NUEVO_MODELO_CRECIMIENTO_VERTICAL, ID_PERMISO_HIJO_PRE_REGISTRO_CV, ID_MODULO_PAQUETIZADO);

            $data['opciones'] = $result['html'];
            #if($result['hasPermiso'] == true){
                $this->load->view('vf_modulo_transporte/v_inicio_transporte_new',$data);
            #}else{
            #    redirect('login','refresh');
            #}
        }else{
            redirect('login','refresh');
        }
    }
}

