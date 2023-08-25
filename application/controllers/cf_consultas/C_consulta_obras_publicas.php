<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_consulta_obras_publicas extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_consultas/m_consultas');
        $this->load->model('mf_ficha_tecnica/m_bandeja_ficha_tecnica');
        $this->load->library('lib_utils');
        $this->load->helper('url');       
    }
    function index() {
        $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
	           $data["extra"]=' <link rel="stylesheet" href="'.base_url().'public/bower_components/notify/pnotify.custom.min.css">
                                <link rel="stylesheet" href="'.base_url().'public/bower_components/bootstrap-validator/bootstrapValidator.min.css"></link>   
                                <link href="'.base_url().'public/vendors/bower_components/datatables/media/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/><link rel="stylesheet" href="'.base_url().'public/fancy/source/jquery.fancybox.css" type="text/css" media="screen">
                                <link rel="stylesheet" href="'.base_url().'public/css/jasny-bootstrap.min.css">';
	           $data["pagina"]="consultas";
        	   $permisos =  $this->session->userdata('permisosArbol');
        	  // $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_INSPECCIONES, ID_PERMISO_HIJO_REGISTRO_FICHA);
        	  // $data['opciones'] = $result['html'];
        	  // if($result['hasPermiso'] == true){
                $this->load->view('vf_layaout_sinfix/header',$data);
                $this->load->view('vf_layaout_sinfix/cabecera');
                $this->load->view('vf_layaout_sinfix/menu');
                $this->load->view('vf_consultas/v_consulta_obras_publicas',$data);
               // $this->load->view('vf_layaout_sinfix/footer');
        	       
        	  // }else{
        	  //     redirect('login','refresh');
	          // }
	   }else{
	       redirect('login','refresh');
	   }
    }

    function getDataObp() {
        $array = $this->m_consultas->getTablaConsultaObraPub();

        $data['arrayDataObrasPub'] = $array;

        echo json_encode($data);
    }

    function getDataUpdate() {
        $itemplan   = $this->input->post('itemplan');
        $idEstacion = $this->input->post('idEstacion');
        $array = $this->m_consultas->getTablaConsultByItemplanEstacion($itemplan, $idEstacion);

        $data['arrayDataUpdate'] = $array;

        echo json_encode($data);
    }

    function updateData() {
        $arrayJson = $this->input->post('jsonFormObrasP');
        $this->m_consultas->updateData($arrayJson);

    }
}