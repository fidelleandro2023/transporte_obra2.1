<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_toroEditarPep extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('mf_toro/M_toro');
        $this->load->model('mf_ejecucion/M_generales');
        $this->load->model('mf_utils/m_utils');
        $this->load->helper('url');
    }
    
    public function index(){
        $logedUser = $this->session->userdata('usernameSession');
        if($logedUser != null){
          if(@$_GET["pagina"]=="geteditarpep"){
              $peptoro=$this->M_toro->detallePep($_POST["id_pep"]);
              $oldSubPro = $peptoro["idSubProyecto"];
                $this->M_toro->ActualizarPEP($_POST["id_pep"],$_POST["id_toro"],$_POST["precio"],$_POST["cantidad"],$_POST["detalle"],$_POST["subproyecto"],$_POST["tipo"], $_POST['fec_programacion'], $_POST["selectArea"], $oldSubPro);
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
            $data["pep"]=$this->M_toro->GetPepId($_GET["id"]);
            $peptoro=$this->M_toro->detallePep($_GET["id"]);
            $data["extra"]='<link href="'.base_url().'public/vendors/bower_components/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css"/>';           
            $data["pagina"]="editarpep";
            
            $data["toro"]=$this->ListarToro($data["pep"]["id_toro"]);
            $data["subproyecto"]=$this->ListarSubProyecto($peptoro["idSubProyecto"]);
            $data["tipo"]=$this->PepTipo($peptoro["id_tipo_toro"]);
            $data['fec_programacion']   = $peptoro['fecha_programacion'];
            $listaAreasPep = $this->M_toro->getAreasBySubProyectoPep($peptoro["idSubProyecto"], $_GET["id"]);
            $data["areas"]  =   $this->listarAllAreas($listaAreasPep, $peptoro["idSubProyecto"]);
            
            $this->load->view('vf_layaout_sinfix/header',$data);

            $this->load->view('vf_toro/v_EditarPep',$data);

            $this->load->view('recursos_sinfix/js');

            $this->load->view('recursos_sinfix/select2');
         }else{
             redirect('login','refresh');
        }
             
    }
   public function ListarToro($val){
      $toro=$this->M_toro->ListarToroId();
        $html='<select class="form-control select2" name="id_toro">
               <option value="0">Seleccione Toro</option>';
      foreach ($toro->result() as $row ){
        $extra="";
        if($val==$row->id_toro){$extra="selected";} 
        $html.='<option '.$extra.' value="'.$row->id_toro.'">'.$row->id_toro.'</option>';
      }
        $html.="</select>";
        return $html;
    }
   public function PepTipo($val){
      $tipo=$this->M_toro->ListarTipoPep();
        $html='<select class="form-control select2" name="tipo">
               <option value="0">Seleccione Tipo</option>';
      foreach ($tipo->result() as $row ){
        $extra="";
        if($val==$row->id_tipo_toro){$extra="selected";} 
        $html.='<option '.$extra.' value="'.$row->id_tipo_toro.'">'.$row->nombre.'</option>';
      }
        $html.="</select>";
        return $html;
    }
   public function ListarSubProyecto($val){
   
    $html='<select class="form-control select2" id="subproyecto" name="subproyecto" onchange="changueProyect()">
          <option>Seleccione SubProyecto</option>';
   $proyecto=$this->M_generales->ListarSubProyectoNo2017Edit($val);
   
       foreach($proyecto->result() as $row){
          $extra="";
          if($val==$row->idSubProyecto){$extra="selected";}    
          $html.='<option '.$extra.' value="'.$row->idSubProyecto.'">'.$row->subProyectoDesc.'</option>';
        }
    
       $html.="</select>";
       return $html;  
   }
   
   public function listarAllAreas($listaAreasPep, $idSubPro){
       // log_message('error', print_r($listaAreasPep, true));
       $html='<select multiple id="selectArea" class="form-control select2" name="selectArea[]">
                <option>Seleccione Area</option>';
       $areas   =   $this->m_utils->getAllIdEstacionAreaByAreaBySubPro($idSubPro);
        //log_message('error', print_r($areas->result(), true));
               
       foreach($areas->result() as $row){
           //log_message('error', $row->idArea.'-'.in_array($row->idArea, $listaAreasPep));
           $html.='<option '.((in_array($row->idArea, $listaAreasPep))? 'selected' : '').' value="'.$row->idArea.'">'.$row->areaDesc.'</option>';
       }
        
       $html.="</select>";
       return $html;
   }

    }