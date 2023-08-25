<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_toro extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('mf_toro/M_toro');
        $this->load->model('mf_ejecucion/M_generales');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }
    
    public function index(){
        $logedUser = $this->session->userdata('usernameSession');
        if($logedUser != null){
          $id_toro="";
          $idProyecto="";
          
          /*************************27-06-2018****************/
          if(@$_GET["pagina"]=="eliminar_toro"){
            $this->M_toro->Eliminar_Toro($_GET["id"]);
            echo $_GET["id"];
            exit;
          }
           /*****************************************/
          
          
          
          
          
          
          
          if(@$_GET["pagina"]=="carga_masiva"){
            $data["resultado"]=$this->CargaMasiva($_FILES['masivo']);
            }
          if(@$_GET["pagina"]=="carga_masiva_toro"){
            $this->CargaMasivaToro($_FILES['masivo']);
          }
          if(@$_POST["pagina"]=="filtrar_proyecto"){
            echo $this->tablaToro($id_toro,$_POST["idProyecto"]);
            exit;
          }
          if(@$_POST["pagina"]=="buscar_proyecto"){
            echo $this->tablaToro($_POST["id_toro"],$idProyecto);
            exit;
          }
            $data["extra"]='<link href="'.base_url().'public/vendors/bower_components/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css"/><link rel="stylesheet" href="'.base_url().'public/fancy/source/jquery.fancybox.css" type="text/css" media="screen"><link rel="stylesheet" type="text/css" href="public/tree/jquery.treetable.css"><link rel="stylesheet" type="text/css" href="public/tree/jquery.treetable.theme.default.css?v='.time().'">';
            
            $data["pagina"]="toro";
            $data["tabla"]=$this->tablaToro($id_toro,$idProyecto);
            $data["filtrar_subproyecto"]=$this->ListarProyecto(0);
            $permisos =  $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_REPORTES_V, ID_PERMISO_HIJO_DETALLE_OBRA);
               $data['opciones'] = $result['html'];
            
            $this->load->view('vf_layaout_feix/header',$data);
            $this->load->view('vf_layaout_feix/cabecera');
            $this->load->view('vf_layaout_feix/menu',$data);

            $this->load->view('vf_toro/v_ListarToro',$data);

            $this->load->view('vf_layaout_sinfix/footer');
            $this->load->view('recursos_feix/js',$data);
            $this->load->view('recursos_sinfix/select2');
            $this->load->view('recursos_feix/tree');
            $this->load->view('recursos_sinfix/fancy',$data);
         }else{
             redirect('login','refresh');
        }
             
    }
    public function tablaToro($id_toro,$idProyecto){
     $toro=$this->M_toro->ListarToro($id_toro,$idProyecto);
     $html='
            <table id="simpletable" class="table table-hover display  pb-30 table-striped table-bordered nowrap" >
            <thead>
                          <tr class="table-primary" style="color:#fff;background-color:#8bc34a">
                          <th>Acci????n</th>
                          <th>Cod. Toro</th>
                          <th>AE</th>
                          <th>Detalle</th>
                          <th>Proyecto</th>  
                          <th>Total</th>
                          <th>Consumo</th>
                          <th>Disponible</th>
                          
                          </tr>
            </thead>
            <tbody>';
    $t=0;       
    /*<a href="detalle_toro?id='.$row->id_toro.'"><i class="zmdi zmdi-assignment zmdi-hc-fw"></i></a> */
    foreach ($toro->result() as $row) {
    $t++;
    $date = date_create($row->fecha);
    #$suma=$this->M_toro->SumaDetalle($row->id_toro);
    $rpep=$this->M_toro->ListarToroDetalle($row->id_toro);
    $psuma=0;
    if($rpep){
      foreach ($rpep->result() as $sroww) {
        $psuma=$psuma+str_replace(",","",str_replace('"', '', (($sroww->presupuesto == null) ? 0 : $sroww->presupuesto)));
      }
    }
    /**********antiguo 27-06-2018
    $html.='
    <tr data-tt-id="'.$t.'" style="background-color:#fff">
    <td>
    <a class="editar_toro" href="#"><i class="zmdi zmdi-edit zmdi-hc-fw"></i></a>    
    </td>
    <td>'.$row->id_toro.'</td>
    <td>'.$row->ae.'</td>
    <td></td>
    <td>'.$row->proyectoDesc.'</td>
    <td>'.number_format((float)$row->monto,2,".",",").'</td>
    <td>'.number_format($psuma,2,".",",").'</td>
    <td style="color:#ec3305">'.number_format($row->monto-$psuma,2,".",",").'</td>
    
    </tr>
    ';
    
    *******************/
    /********************nuevo 27-06-2018**************************/
    $html.='
    <tr data-tt-id="'.$t.'" id="'.$row->id_toro.'" style="background-color:#fff">
    <td>
    <a class="editar_toro" href="#"><i class="zmdi zmdi-edit zmdi-hc-fw"></i></a>    
    <a class="eliminar_toro" href="#"><i class="zmdi zmdi-delete zmdi-hc-fw"></i></a>    
    </td>
    <td>'.$row->id_toro.'</td>
    <td>'.$row->ae.'</td>
    <td></td>
    <td>'.$row->proyectoDesc.'</td>
    <td>'.number_format((float)$row->monto,2,".",",").'</td>
    <td>'.number_format($psuma,2,".",",").'</td>
    <td style="color:#ec3305">'.number_format((($row->monto==null) ? 0 : (float)$row->monto -(($psuma==null) ? 0 : $psuma)),2,".",",").'</td>
    
    </tr>
    ';
    
    
    /******************************************************************/
    
    if($rpep){
    foreach ($rpep->result() as $srow) {
     $html.='<tr data-tt-id="'.($t+1).'" data-tt-parent-id="'.$t.'" style="background:#2196F3">
     <td></td>
     <td style="color:#fff">'.$srow->id_pep.'</td>
     <td></td>
     <td style="color:#fff">'.$srow->detalle.'</td>
     <td></td>
     <td></td>
     <td style="color:#fff">'.str_replace('"', "", $srow->presupuesto).'</td>
     <td></td>
     
 
     </tr>';       
        }    
    }    
    }

    
     $html.="</tbody></table>";    
     return $html;
    }
    public function CargaMasiva($archivo){
    $carpeta="uploads/toro/". basename($archivo['name']);
            if(move_uploaded_file($archivo['tmp_name'], $carpeta )){
              $a = fopen($carpeta, "r");
                $i=0;
                $k=0;
                $l=0;
                $o=0;
                $z=0;
                while(!feof($a)){
                $linea=fgets($a);
                $comp = preg_split("/[\t]/", $linea);
                if(!$comp[0]){continue;}

                $toro=$this->M_toro->toroId(trim($comp[0]));
                if($toro==0){
                $error_toro[$i]="TORO : ".$comp[0]." PEP : ".$comp[1];
                $i++;
                continue;                
                }

                $pep=$this->M_toro->pepId(trim($comp[1]));
                if($pep==0){
                $error_pep[$k]="TORO : ".$comp[0]." PEP : ".$comp[1];
                $k++;
                continue;  
                }

                $pepr=$this->M_toro->pepExisteRegistro(trim($comp[0]),trim($comp[1]));
                if($pepr==2){
                continue;
                }
                if($pepr!=0){
                $error_epep[$l]="TORO : ".$comp[0]." PEP : ".$comp[1];
                $l++;
                continue;  
                }

                $monto=str_replace(",","",str_replace('"', '',$pep["presupuesto"]));
                #$suma=$this->M_toro->SumaDetalle($comp[0]);
                $rpep=$this->M_toro->ListarToroDetalle($comp[0]);
                $psuma=0;
                if($rpep){
                  foreach ($rpep->result() as $sroww) {
                    $psuma=$psuma+str_replace(",","",str_replace('"', '', $sroww->presupuesto));
                  }
                }

                $total_pep=$monto+$psuma;
                $total=$toro["monto"];
                if(bccomp($total, $total_pep,2) != 0){
                    if($total_pep>$total){
                        $error_suma[$o]="TORO : ".$comp[0]." PEP : ".$comp[1];
                        $o++;
                        continue;  
                    }
                }
                $this->M_toro->CrearToroDetalle('',trim($comp[0]),trim($comp[1]),trim($comp[2]),trim($comp[4]),trim($comp[5]),$this->session->userdata("idPersonaSession"));
                $idSubProyecto=$this->M_generales->BuscarSubProyecto(str_replace("'", "", (trim($comp[3]))));
                /*$this->M_toro->Crearpeptoro('',trim($comp[1]),$idSubProyecto,0,0); */   
                $exito[$z]="TORO : ".$comp[0]." PEP : ".$comp[1];
                $z++;
                }
                $total_error=0;
                $total_exito=0;
                $dato_error="";
                $dato_exito="";
                $dato_resumen="";
                if(@$error_toro){
                $total_error=$total_error+count($error_toro);
                foreach ($error_toro as $row) {
                  $dato_error.="Error Codigo TORO no existe en Registro : ".$row."<br>";
                }                }
                if(@$error_pep){
                $total_error=$total_error+count($error_pep);    
                foreach ($error_pep as $row) {
                  $dato_error.="Error Codigo PEP no existe en Registro : ".$row."<br>";
                }}
                if(@$error_epep){
                $total_error=$total_error+count($error_epep);    
                foreach ($error_epep as $row) {
                  $dato_error.="Error Codigo PEP ya existe en otro TORO en Registro : ".$row."<br>";
                }}
                if(@$error_suma){
                $total_error=$total_error+count($error_suma);    
                foreach ($error_suma as $row) {
                  $dato_error.="Error Codigo PEP excede al monto total TORO en Registro : ".$row."<br>";
                }}

                if(@$exito){
                $total_exito=count($exito);
                foreach ($exito as $row) {
                  $dato_exito.="Registro : ".$row." Se grabo Exitosamente <br>";
                }

            }
            $dato_resumen.="<br>Total Subido : ".$total_exito." Total Error : ".$total_error;
                fclose($a);
                
                
            } 
            return $dato_error."|".$dato_exito."|".$dato_resumen;
                
    }
    public function CargaMasivaToro($archivo){
    $carpeta="uploads/toro/". basename($archivo['name']);
            if(move_uploaded_file($archivo['tmp_name'], $carpeta )){
              $a = fopen($carpeta, "r");
                $i=0;
                $k=0;
                $l=0;
                $o=0;
                $z=0;
                while(!feof($a)){
                $linea=fgets($a);
                $comp = preg_split("/[\t]/", $linea);
                
                $toro=$this->M_toro->toroId(trim($comp[0]));
                $proyecto=$this->M_generales->BuscarProyecto(trim($comp[3]));
                $this->M_toro->CrearToro($comp[0],str_replace(",","",$comp[1]),$comp[2],$proyecto,$this->session->userdata("idPersonaSession"));
                
                
                }
               
                
                
                fclose($a);
                
                
            } 
                
    }
  public function ListarProyecto($id){
   
   $html='<select id="filtrar_proyecto" class="form-control select2" name="proyecto">
   <option value="-1">Seleccione Proyecto</option>
   <option value="0">En Blanco</option>';
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