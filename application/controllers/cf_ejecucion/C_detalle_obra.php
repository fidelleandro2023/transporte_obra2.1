<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_detalle_obra extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->model(array('mf_ejecucion/M_ejecucion_cuadrilla','mf_ejecucion/M_generales'));
        $this->load->library('lib_sinfix');
    }
    
    public function index(){
        $logedUser = $this->session->userdata('usernameSession');
        if($logedUser != null){
            $this->session->set_userdata('tiempo', time());
            $data["extra"]='<link rel="stylesheet" href="'.base_url().'public/fancy/source/jquery.fancybox.css" type="text/css" media="screen"><link rel="stylesheet" type="text/css" href="'.base_url().'public/ekko-lightbox/css/ekko-lightbox.css"><link rel="stylesheet" type="text/css" href="'.base_url().'public/lightbox2/css/lightbox.css">
';

            $data["pagina"]="detalle_obra";
            if(!$this->session->userdata("zonasSession")){
            $data["act"]=$this->M_ejecucion_cuadrilla->DetalleObra($_GET["id_planobra_actividad"]);
            }else{
            $data["act"]=$this->M_ejecucion_cuadrilla->DetalleObraZ($_GET["id_planobra_actividad"]);
            }
            if(!$this->session->userdata("zonasSession")){
            $porcentaje=$this->M_generales->Porcentaje($_GET["id_planobra_actividad"]);
            }else{
            $porcentaje=$this->M_generales->PorcentajeZ($_GET["id_planobra_actividad"]);    
            }
            if(!$porcentaje["valor"]) {
                $porcentaje["valor"]=0;
            }
            $data["porcentaje"]=$porcentaje["valor"];

            $data["visita"]=$this->M_generales->CantidadAgendaId($_GET["id_planobra_actividad"]);
            $i=0;

            $data["fecha_asignacion"]=$this->lib_sinfix->fecha_hora_a("-",$data["act"]["fecha"]);


            #$data["suba"]=$this->M_generales->SubactividadId($data["act"]["id_subactividad"]);
            $data["nuevafecha"]=$this->lib_sinfix->sumar_fecha("+30 day",$data["act"]["fechaInicio"]);
            if($data["act"]["fechaPrevEjec"]!="0000-00-00") {
                $data["nuevafechap"]=$this->lib_sinfix->sumar_fecha("+0 day",$data["act"]["fechaPrevEjec"]);
            } else {
                $data["nuevafechap"]="";  
            }

            $data["listaragenda"]=$this->ListarAgendaId($_GET["id_planobra_actividad"]);
            $data["imagenes"]=$this->ListarImagen($_GET["id_planobra_actividad"]);

            $this->load->view('vf_layaout_sinfix/header',$data);
            $this->load->view('vf_layaout_sinfix/cabecera');
            $this->load->view('vf_layaout_sinfix/menu');

            $this->load->view('vf_ejecucion/v_detalle_obra',$data);
            
            
            $this->load->view('recursos_sinfix/js');
            $this->load->view('recursos_sinfix/fancy',$data);
            $this->load->view('recursos_sinfix/lightbox',$data);
            $this->load->view('recursos_sinfix/upload',$data);
           
         }else{
             redirect('login','refresh');
        }
             
    }
public function ListarAgendaId($id_planobra_actividad){
$agenda=$this->M_generales->AgendaId($id_planobra_actividad);
$i=0;    
$html="";    
foreach($agenda->result() as $conv){
$usuario=$this->M_generales->UsuarioId($conv->id_usuario);
$i++;
if($this->session->userdata("zonasSession")){
$conv->id_agenda=$conv->id_agenda_z;
}
$agendaimg=$this->M_generales->AgendaImagenId($conv->id_agenda);

$c=0;
if($agendaimg){
foreach($agendaimg->result() as $img){
$h[$c]=$img->valor;
$c++;  
}
}  
$html.='
<div style="margin-bottom:10px;border-bottom:1px solid #d6d6d6;">
<div style="height:21px">
<span class="f-14">';
if($agendaimg){
$html.='<a href="uploads/sinfix/'.$h[0].'" data-lightbox="roadtrip" data-toggle="lightbox" data-gallery="fotos'.$i.'" style="color:#ec3305">Fotos</a>';
unset($h[0]);
foreach ($h as $key ) {
$html.='<a href="uploads/sinfix/'.$key.'" data-lightbox="roadtrip" data-toggle="lightbox" data-gallery="fotos'.$i.'"></a>';
}
unset($h);                    
}
$html.='
</span>
<span class="f-right pull-right">';
if($conv->coordenadas){
$html.='<a href="agenda_mapa?id='.$conv->id_agenda.'" style="color:#ec3305" class="mapa">Mapa</a>';
}
$html.='</span>
</div>
<div class="media col-xl-12">
<div class="media-body b-b-theme social-client-description">
<div class="chat-header ">Cuadrilla '.$usuario["usuario"].' : <span style="color:#714b44" class="">
    '.$conv->fecha.'</span></div>
<p class="text panel-title txt-dark pt-20 pb-10">'.$conv->conversacion.'</p>
</div>
<a class="media-right label label-inverse panel-title txt-dark" href="#" style="font-size:15px">'.$conv->porcentaje.'% </a>
</div></div>';
 } 
 return $html;    
    }
public function ListarImagen($id_planobra_actividad){
$imagen=$this->M_generales->ListarImagenAgenda($id_planobra_actividad);    
$html="";
foreach($imagen->result() as $row){
$html.='<a data-lightbox="roadtrip" data-toggle="lightbox" data-gallery="todas" href="uploads/sinfix/'.$row->valor.'"><img src="uploads/sinfix/'.$row->valor.'" class="img-fluid" style="" alt=""></a>';
}
return $html;    
}        
}