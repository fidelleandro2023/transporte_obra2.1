<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_obra_terminar extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('mf_ejecucion/M_obra_terminar');
        $this->load->model('mf_ejecucion/M_generales');
        $this->load->library('lib_sinfix');
        $this->load->helper('url');
    }
    
    public function index(){
        $logedUser = $this->session->userdata('usernameSession');
        if($logedUser != null){
            if(@$_GET["pagina"]=="eliminar_t"){
                $this->M_obra_terminar->EliminarArchivos($_GET["id_planobra_terminar"]);
            }
            $data["extra"]='<link href="'.base_url().'public/jquery.filer/css/jquery.filer.css" type="text/css" rel="stylesheet" /><link href="'.base_url().'public/jquery.filer/css/themes/jquery.filer-dragdropbox-theme.css" type="text/css" rel="stylesheet" />';
            $data["obra"]=$this->M_generales->ItemPlanId($_GET["id"]);
            $data["lista"]=$this->ListarArchivos($_GET["id"]);
            $data["pagina"]="obra_terminar";

            $this->load->view('vf_layaout_sinfix/header',$data);
            $this->load->view('vf_ejecucion/v_obra_terminar',$data);
            $this->load->view('recursos_sinfix/js');
            $this->load->view('recursos_sinfix/filer',$data);
         }else{
             redirect('login','refresh');
        }
             
    }
public function ListarArchivos($id){
$archivo=$this->M_obra_terminar->ListarArchivos($id); 
$html="";   
if($archivo){
$html.='<div class="row">
<table class="table table-striped table-bordered nowrap">
<thead>
<th>#</th>    
<th>Archivo</th>
<th>Usuario</th>
<th>Fecha</th>
<th>Acc.</th>
</thead>    
<tbody>';
$l=0;
foreach($archivo->result() as $i){
$l++;
$f=$this->lib_sinfix->fecha_hora_a("/",$i->fecha);
$im=substr($i->nombre,11);
$html.='
<tr><td>'.$l.'</td>    
<td><a download="'.$im.'" href="uploads/sinfix/obra/'.$i->nombre.'">'.$im.'</a></td>    
<td>'.$i->usuario.'</td>    
<td>'.$f[0].' '.$f[1].'</td>
<td>
<a href="obra_terminar?pagina=eliminar_t&id='.$id.'&id_planobra_terminar='.$i->id_planobra_terminar.'"><i class="fa fa-trash"></i></a>
</td>  
</tr>';    
}
$html.='</tbody>
</table>
</div>'; 
    }
return $html;    
  }  
    
}