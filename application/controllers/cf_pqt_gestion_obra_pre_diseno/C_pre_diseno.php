<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *
 * @author Gustavo Sedano L.
 * 05/09/2019
 *
 */
class C_pre_diseno extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_pre_diseno/m_bandeja_adjudicacion');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
        $this->load->library('zip');
        $this->load->library('table');
    }

	public function index()
	{
        $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
	           $listaEECC = $this->m_utils->getAllEECC();
        	   $data['listaSubProy']   = $this->m_utils->getAllSubProyecto();
        	   $data['listacentral']   = $this->m_utils->getAllNodos();
        	   $data['listEECCDi']     = $listaEECC;
               
        	   $itemplan = (isset($_GET['itemplan']) ? $_GET['itemplan'] : '');
        	   $has_coax = (isset($_GET['has_coax']) ? $_GET['has_coax'] : '');
        	   $has_fo = (isset($_GET['has_fo']) ? $_GET['has_fo'] : '');
        	   
        	   $data['itemplan'] = ''.$itemplan.'';
        	   $data['has_coax'] = $has_coax;
        	   $data['has_fo'] = $has_fo;
        	   
               $data['nombreUsuario']  =  $this->session->userdata('usernameSession');
               $data['perfilUsuario']  =  $this->session->userdata('descPerfilSession');
               
        	   $permisos =  $this->session->userdata('permisosArbol');
        	   $result = $this->lib_utils->getHTMLPermisos($permisos, NULL, ID_PERMISO_HIJO_PQT_PRE_DISENO, ID_MODULO_PAQUETIZADO);
        	   
        	   if($result['hasPermiso'] == true){
        	       $this->load->view('vf_pqt_gestion_obra_pre_diseno/v_pre_diseno',$data);
        	   }else{
        	       $data['modulo']  =  "Pre Diseño";
        	       $this->load->view('v_permiso_denegado.php',$data);
	           }
	   }else{
	       log_message('error', '-->C_pre_diseno Usuario Error');
	       redirect('login','refresh');
	   }
    }

}