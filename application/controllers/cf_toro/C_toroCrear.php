<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_toroCrear extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('mf_toro/M_toro');
        $this->load->model('mf_ejecucion/M_generales');
        $this->load->helper('url');
    }
    
    public function index(){
        $logedUser = $this->session->userdata('usernameSession');
        if($logedUser != null){
            $data["extra"]='<link href="'.base_url().'public/vendors/bower_components/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css"/>';
            
            $data["pagina"]="creartoro";
            $data["proyecto"]=$this->ListarProyecto();
           if(@$_GET["pagina"]=="crear"){
           $this->M_toro->CrearToro(trim($_POST["id_toro"]),str_replace(",","",$_POST["monto"]),$_POST["ae"],$_POST["proyecto"],$this->session->userdata('idPersonaSession'));
           ?>
           <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
            <script type="text/javascript">
            $(document).ready(function(){
                parent.$.fancybox.close();parent.location.reload(); 
            }); 
            </script>
           <?php
           exit; 
           }
            
            $this->load->view('vf_layaout_sinfix/header',$data);

            $this->load->view('vf_toro/v_CrearToro',$data);

            $this->load->view('recursos_sinfix/js');
            $this->load->view('recursos_sinfix/select2');
         }else{
             redirect('login','refresh');
        }
             
    }
   public function ListarProyecto(){
   
   $html='<select class="form-control select2" name="proyecto"><option value="0">Seleccione Proyecto</option>';
   $proyecto=$this->M_generales->ListarProyecto();
   foreach($proyecto->result() as $row){
   $html.='<option value="'.$row->idProyecto.'">'.$row->proyectoDesc.'</option>';     
    }
   $html.="</select>";
   return $html;  
   }
    }