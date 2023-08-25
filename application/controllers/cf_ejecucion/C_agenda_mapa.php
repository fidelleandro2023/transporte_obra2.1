<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_agenda_mapa extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('mf_ejecucion/M_agenda_mapa');
        $this->load->helper('url');
    }
    
    public function index(){
        $logedUser = $this->session->userdata('usernameSession');
        if($logedUser != null){
            $data["c"]=$this->M_agenda_mapa->MapaIdAgenda($_GET["id"]);
            $data["r"]=explode(",",$data["c"]["coordenadas"]);
            
            
            $this->load->view('vf_ejecucion/v_agenda_mapa',$data);
            $this->load->view('recursos_sinfix/js');
         }else{
             redirect('login','refresh');
        }
             
    }
    
    
    
    
}