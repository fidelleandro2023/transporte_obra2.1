<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_toroExtractor extends CI_Controller {

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
            if(@$_GET["pagina"]=="descargar"){
              $this->ExtractorToro();
              exit;
            }
            $data["extra"]='';
            
            $data["pagina"]="extractortoro";
            $permisos =  $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_REPORTES_V, ID_PERMISO_HIJO_DETALLE_OBRA);
               $data['opciones'] = $result['html'];           
            $this->load->view('vf_layaout_feix/header',$data);
            $this->load->view('vf_layaout_feix/cabecera');
            $this->load->view('vf_layaout_feix/menu',$data);

            $this->load->view('vf_toro/v_ExtractorToro',$data);

            $this->load->view('vf_layaout_sinfix/footer');
            $this->load->view('recursos_feix/js',$data);
         }else{
             redirect('login','refresh');
        }
             
    }
    public function ExtractorToro(){
    $toro=$this->M_toro->ListarToro("","");
    
    $file = fopen(PATH_FILE_UPLOAD_EXTRACTOR_TORO, "w");
              fputcsv($file,  explode('\t',"Cod. Toro"."\t"."AE"."\t"."Proyecto"."\t"."Total"."\t"."Consumo"."\t"."Disponible"));
    $t=0;       
    foreach ($toro->result() as $row) {
    $t++;
    $rpep=$this->M_toro->ListarToroDetalle($row->id_toro);
    $psuma=0;
    if($rpep){
      foreach ($rpep->result() as $sroww) {
        $psuma=$psuma+str_replace(",","",str_replace('"', '', $sroww->presupuesto));
      }
    }
              fputcsv($file, explode('\t', utf8_decode($row->id_toro."\t". $row->ae."\t".$row->proyectoDesc."\t". number_format((float)$row->monto,2,".",",")."\t". number_format($psuma,2,".",",")."\t". number_format($row->monto-$psuma,2,".",","))));

  } 
  fclose($file); 
  header("Content-disposition: attachment; filename=".PATH_FILE_UPLOAD_EXTRACTOR_TORO);
  header("Content-type: application/csv");
  readfile(PATH_FILE_UPLOAD_EXTRACTOR_TORO);                         
    }
       }