<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_toroNuevaPep extends CI_Controller {

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
          if(@$_GET["pagina"]=="addNewPep"){
              
            $result = $this->M_toro->savePepToro($_POST['pep2'],$_POST["id_toro"],$_POST["subproyecto"],$_POST["detalle"],$_POST["precio"],$_POST["cantidad"],$_POST["tipo"], $_POST["fec_programacion"], $_POST["selectArea"]);
                
            ?>
            <script src="https://code.jquery.com/jquery-1.12.4.js"></script>          
            <script type="text/javascript">
              
            
            <?php 
            if($result['error']   == EXIT_ERROR){
            ?>
            alert('<?php echo $result['msj'] ?>');
            
             //alert('Ocurrio un error interno al registrar los datos,  vuelva a intentarlo o comuníquese con el administrador!');
            <?php
            }
            ?>
            $(document).ready(function(){
                parent.$.fancybox.close();parent.location.reload(); 
            }); 
            </script>
            <?php
            exit;
          }
          /*
            $data["pep"]=$this->M_toro->GetPepId($_GET["id"]);
            $peptoro=$this->M_toro->detallePep($_GET["id"]);
            */
            $data["extra"]='<link href="'.base_url().'public/vendors/bower_components/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css"/>';           
            $data["pagina"]="editarpep";
            
            $data["toro"]=$this->ListarToro();
            $data["subproyecto"]=$this->ListarSubProyecto();
            $data["tipo"]=$this->PepTipo();
            $data["areas"]  =   $this->listarAllAreas(null);

            $this->load->view('vf_layaout_sinfix/header',$data);

            $this->load->view('vf_toro/v_NuevaPep',$data);

            $this->load->view('recursos_sinfix/js');

            $this->load->view('recursos_sinfix/select2');
         }else{
             redirect('login','refresh');
        }
             
    }
   public function ListarToro(){
      $toro=$this->M_toro->ListarToroId();
        $html='<select required="required" class="form-control select2" name="id_toro">
               <option value="0">Seleccione Toro</option>';
      foreach ($toro->result() as $row ){
      
        $html.='<option value="'.$row->id_toro.'">'.$row->id_toro.'</option>';
      }
        $html.="</select>";
        return $html;
    }
   public function PepTipo(){
      $tipo=$this->M_toro->ListarTipoPep();
        $html='<select class="form-control select2" name="tipo">
               <option value="0">Seleccione Tipo</option>';
      foreach ($tipo->result() as $row ){       
        $html.='<option value="'.$row->id_tipo_toro.'">'.strtoupper($row->nombre).'</option>';
      }
        $html.="</select>";
        return $html;
    }
   public function ListarSubProyecto(){
   
   $html='<select class="form-control select2" id="subproyecto" name="subproyecto" onchange="changueProyect()">
          <option>Seleccione SubProyecto</option>';
   $proyecto=$this->M_generales->ListarSubProyectoNo2017(null);
   
   foreach($proyecto->result() as $row){   
      $html.='<option value="'.$row->idSubProyecto.'">'.$row->subProyectoDesc.'</option>';
    }

   $html.="</select>";
   return $html;  
   }

   public function listarAllAreas($idSubPro){
        
       $html='<select multiple id="selectArea" class="form-control select2" name="selectArea[]">
                <option>Seleccione Area</option>';
       $areas   =   $this->m_utils->getAllIdEstacionAreaByAreaBySubPro($idSubPro);
        
       foreach($areas->result() as $row){
           $html.='<option value="'.$row->idArea.'">'.utf8_decode($row->areaDesc).'</option>';
       }
   
       $html.="</select>";
       return $html;
   }
   
   function filtrarSubProyecto(){
       $data['error']    = EXIT_ERROR;
       $data['msj']      = null;
       $data['cabecera'] = null;
       try{
           $SubProy = $this->input->post('subProy');
           $data['areas'] = $this->listarAllAreas($SubProy);
           $data['error']    = EXIT_SUCCESS;
       }catch(Exception $e){
           $data['msj'] = $e->getMessage();
       }
       echo json_encode(array_map('utf8_encode', $data));
   }

    function validateExistePep(){
       $data['error']    = EXIT_ERROR;
       $data['msj']      = null;
       $data['cabecera'] = null;
       try{
           $pep = $this->input->post('pep2');
           $exist = $this->m_utils->existPepInPepToro($pep);
           $data['exist']    = $exist;
           $data['error']    = EXIT_SUCCESS;
       }catch(Exception $e){
           $data['msj'] = $e->getMessage();
       }
       echo json_encode(array_map('utf8_encode', $data));
   }
    }