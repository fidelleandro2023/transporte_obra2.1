<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_toroDetalleCrear extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('mf_toro/M_toro');
        $this->load->model('mf_ejecucion/M_generales');
        $this->load->helper('url');
    }
    
    public function index(){
        $logedUser = $this->session->userdata('usernameSession');
        if($logedUser != null){
          if(@$_GET["pagina"]=="creardetalle"){
            $this->M_toro->CrearToroDetalle('',$_POST["id_toro"],$_POST["pep"],$_POST["subproyecto"],$this->session->userdata("idPersonaSession"));
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
          $toro=$this->M_toro->ToroId($_GET["id"]);
            $data["extra"]='<link href="'.base_url().'public/vendors/bower_components/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css"/>';
            
            $data["pagina"]="creartoro";
            $data["subproyecto"]=$this->ListarSubProyecto($toro["idProyecto"]);
            $data["pep"]=$this->ListarPep();
                       
            $this->load->view('vf_layaout_sinfix/header',$data);

            $this->load->view('vf_toro/v_CrearDetalleToro',$data);            

            $this->load->view('recursos_sinfix/js');
            $this->load->view('recursos_sinfix/select2');
         }else{
             redirect('login','refresh');
        }
             
    }
   public function ListarSubProyecto($id){
   
   $html='<select class="form-control select2" name="subproyecto"><option>Seleccione SubProyecto</option>';
   $proyecto=$this->M_generales->ListarSubProyectoId($id);
   foreach($proyecto->result() as $row){
   $html.='<option value="'.$row->idSubProyecto.'">'.$row->subProyectoDesc.'</option>';     
    }
   $html.="</select>";
   return $html;  
   }
   public function ListarPep(){
   $html='<select class="form-control select2" name="pep"><option>Seleccione PEP</option>';
   $proyecto=$this->M_toro->ListarPep();
   foreach($proyecto->result() as $row){
    if($row->tipo==1){
      $c=explode("-",$row->pep1);
      $c[0]=$c[0]."EP P";
      $pep=implode("-", $c);
      $r=$this->M_generales->PepDescf($pep);}
      if($row->tipo==2){ $pep="PEP ".$row->pep1; $r=$this->M_generales->PepDescc($pep);}
   $html.='<option value="'.$row->pep1.'">'.$row->pep1.' | '.$r["descripcion"].'</option>';     
    }
   $html.="</select>";
   return $html; 
   }
    }