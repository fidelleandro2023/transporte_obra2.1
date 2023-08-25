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
          if(@$_GET["pagina"]=="carga_masiva"){
            $data["resultado"]=$this->CargaMasiva($_FILES['masivo']);
            }
          if(@$_GET["pagina"]=="carga_masiva_toro"){
            $this->CargaMasivaToro($_FILES['masivo']);
          }
            $data["extra"]='<link rel="stylesheet" href="'.base_url().'public/fancy/source/jquery.fancybox.css" type="text/css" media="screen"><link rel="stylesheet" type="text/css" href="http://ludo.cubicphuse.nl/jquery-treetable/css/jquery.treetable.css"><link rel="stylesheet" type="text/css" href="http://ludo.cubicphuse.nl/jquery-treetable/css/jquery.treetable.theme.default.css">';
            
            $data["pagina"]="toro";
            $data["tabla"]=$this->tablaToro();
            $permisos =  $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_REPORTES_V, ID_PERMISO_HIJO_DETALLE_OBRA);
               $data['opciones'] = $result['html'];
            
            $this->load->view('vf_layaout_feix/header',$data);
            $this->load->view('vf_layaout_feix/cabecera');
            $this->load->view('vf_layaout_feix/menu',$data);

            $this->load->view('vf_toro/v_ListarToro',$data);

            $this->load->view('vf_layaout_sinfix/footer');
            $this->load->view('recursos_feix/js');
            $this->load->view('recursos_feix/tree');
            $this->load->view('recursos_sinfix/fancy',$data);
         }else{
             redirect('login','refresh');
        }
             
    }
    public function tablaToro(){
     $toro=$this->M_toro->ListarToroS();
     $html='
            <table id="simpletable" class="table table-hover display  pb-30 table-striped table-bordered nowrap" cellspacing="0" width="100%">
            <thead class="thead-default">
                          <tr class="table-primary">
                          <th>Acción</th>
                          <th>Cod. Toro</th>
                          <th>PEP</th>
                          <th>AE</th>
                          <th>Detalle</th>
                          <th>Proyecto</th>  
                          <th>Cantidad</th>
                          <th>Precio</th>
                          <th>Total</th>
                          <th>Consumo</th>
                          <th>Disponible</th>
                          <th>F. TORO Creación</th>';                 

    $html.='</tr>
            </thead>
            <tbody>';
    $anterior="";        
    $t=0;
    
    foreach ($toro->result() as $row) {
        $t++;
    $date = date_create($row->fecha);
    $suma=$this->M_toro->SumaDetalle($row->id_toro);
    if($anterior==""||$anterior!=$row->id_toro){
    $anterior=$row->id_toro;
    $toro=$row->id_toro;
    $rmonto=$row->cantidad*$row->precio-$row->monto_inicial;
    $rtotal=number_format((float)$row->cantidad*$row->precio,2,".",",");
    $rae=$row->ae;
    $rdetalle=$row->detalle;    
    $rproyecto=$row->proyectoDesc;
    $rcantidad=$row->cantidad;
    $rprecio=number_format((float)$row->precio,2,".",",");
    $rfecha=date_format($date, 'd-m-Y');
    
    $tr='<tr data-tt-id="'.$t.'">';
    $tt=$t;
    }else{
    $rmonto=$rmonto-$row->monto_inicial;
    $toro="";
    $rtotal=""; 
    $rae="";
    $rdetalle="";  
    $rproyecto="";
    $rcantidad="";
    $rprecio="";
    $rfecha="";
    $tr='<tr data-tt-id="'.$t.'" data-tt-parent-id="'.$tt.'" style="background:#ffc107">';   
    }
    $html.=$tr.'    
    <td>
    
    </td>
    <td>'.$toro.'</td>
    <td style="color:#32c787">'.$row->id_pep.'</td>    
    <td>'.$rae.'</td>
    <td>'.$rdetalle.'</td>
    <td>'.$rproyecto.'</td>
    <td>'.$rcantidad.'</td>
    <td>'.$rprecio.'</td>
    <td>'.$rtotal.'</td>
    <td style="color:#32c787">'.number_format($row->monto_inicial,2,".",",").'</td>
    <td style="color:#ec3305">'.number_format($rmonto,2,".",",").'</td>
    <td>'.$rfecha.'</td>';            
     $html.='</tr>';    
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

                $monto=$pep["monto_inicial"];
                $suma=$this->M_toro->SumaDetalle($comp[0]);
                $total_pep=$monto+$suma["valor"];
                $total=$toro["cantidad"]*$toro["precio"];

                if(bccomp($total, $total_pep,2) != 0){
                    if($total_pep>$total){
                        $error_suma[$o]="TORO : ".$comp[0]." PEP : ".$comp[1];
                        $o++;
                        continue;  
                    }
                }
                $this->M_toro->CrearToroDetalle('',trim($comp[0]),trim($comp[1]),$this->session->userdata("idPersonaSession"));
                $idSubProyecto=$this->M_generales->BuscarSubProyecto($comp[2]);
                $this->M_toro->Crearpeptoro('',trim($comp[1]),$idSubProyecto,0,0);    
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
                
                $proyecto=$this->M_generales->BuscarProyecto($comp[3]);
                if($proyecto){
                $proyecto=$proyecto["idProyecto"];    
                }
                $this->M_toro->CrearToro($comp[0],$comp[1],$comp[2],$proyecto,$comp[4],str_replace(",","",$comp[5]),$this->session->userdata("idPersonaSession"));
                
                
                }
               
                
                
                fclose($a);
                
                
            } 
                
    }
    }