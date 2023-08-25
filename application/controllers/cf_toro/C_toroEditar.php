<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_toroEditar extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('mf_toro/M_toro');
        $this->load->model('mf_ejecucion/M_generales');
        $this->load->helper('url');
    }
    
    public function index(){
        $logedUser = $this->session->userdata('usernameSession');
        if($logedUser != null){
          if(@$_GET["pagina"]=="editar"){
          $ac=$this->M_toro->toroId($_POST["id_toro"]);
          $antes=$ac["id_toro"].",".$ac["ae"].",".$ac["idProyecto"].",".$ac["monto"];
          $despues=$_POST["id_toro"].",".$_POST["ae"].",".$_POST["proyecto"].",".$_POST["monto"];  
          $this->M_toro->ToroLog('',$antes,$despues,$this->session->userdata('idPersonaSession'));  
          $this->M_toro->ActualizarToro($_POST["id_toro"],$_POST["ae"],$_POST["proyecto"],str_replace(",","",$_POST["monto"]));
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
          $data["toro"]=$this->M_toro->toroId($_GET["id"]);
            $data["extra"]='<link href="'.base_url().'public/vendors/bower_components/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css"/>';           
            $data["pagina"]="editartoro";
            $data["proyecto"]=$this->ListarProyecto($data["toro"]["idProyecto"]);

            
            $this->load->view('vf_layaout_sinfix/header',$data);

            $this->load->view('vf_toro/v_EditarToro',$data);

            $this->load->view('recursos_sinfix/js');
            $this->load->view('recursos_sinfix/select2');
         }else{
             redirect('login','refresh');
        }
             
    }
    public function ListarProyecto($id){
   
   $html='<select class="form-control select2" name="proyecto"><option value="0">Seleccione Proyecto</option>';
   $proyecto=$this->M_generales->ListarProyecto();   
   foreach($proyecto->result() as $row){
    $extra="";
    if($row->idProyecto==$id){$extra="selected";}
   $html.='<option '.$extra.' value="'.$row->idProyecto.'">'.$row->proyectoDesc.'</option>';     
   unset($extra);
    }
   $html.="</select>";
   return $html;  
   }
    }