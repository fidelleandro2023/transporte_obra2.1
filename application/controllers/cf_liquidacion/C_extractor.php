<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_extractor extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('mf_liquidacion/M_extractor');
        $this->load->model('mf_ejecucion/M_generales');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }
    
    public function index(){
        $logedUser = $this->session->userdata('usernameSession');
        if($logedUser != null){
            ini_set('max_execution_time', 4000);
            $data["extra"]='<link rel="stylesheet" href="'.base_url().'public/fancy/source/jquery.fancybox.css" type="text/css" media="screen"><link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.0/css/buttons.dataTables.min.css">';
            
            $data["pagina"]="toro";
           $this->tablaExtractor();
            $permisos =  $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_REPORTES_V, ID_PERMISO_HIJO_DETALLE_OBRA);
               $data['opciones'] = $result['html'];
            
            $this->load->view('vf_layaout_feix/header',$data);
            $this->load->view('vf_layaout_feix/cabecera');
            $this->load->view('vf_layaout_feix/menu',$data);

            $this->load->view('vf_liquidacion/v_Extractor');

            $this->load->view('vf_layaout_sinfix/footer');
            $this->load->view('recursos_feix/js');
            $this->load->view('recursos_feix/datatable',$data);
            $this->load->view('recursos_sinfix/fancy',$data);
         }else{
             redirect('login','refresh');
        }
             
    }
    
    
    /********************************
    
    public function tablaExtractor(){
     $toro=$this->M_extractor->ListarExtractor();
     $file = fopen(PATH_FILE_UPLOAD_EXTRACTOR_LIQUIDACION, "w");
              fputcsv($file,  explode('\t',"ItemPlan"."\t"."PTR"."\t"."AREA"."\t"."indicador"."\t"."Estacion"."\t"."Porcentaje"."\t"."Proyecto"."\t"."SubProyecto"."\t"."Ejecucion"."\t"."Prevista"."\t"."Estado PTR"."\t"."Estado IP"."\t"."Colab IP"."\t"."Colab PTR"."\t"."Zonal"."\t"."Jefatura"."\t"."Monto Mat"."\t"."Monto Mo"."\t"."Ultimo Estado"."\t"."Usuario Ultimo Estado"."\t"."Identificador"."\t"."Expediente"."\t"."Fecha Expediente"."\t"."Check"."\t"."Validado"."\t"."Fecha Valida"."\t"."Usuario Valida"));  
    foreach ($toro->result() as $row) {
            
      if($row->idEstadoPlan==4){
        $certificable="C";
      }else{
      $certificable=$this->M_extractor->VerificarPtrEstacion($row->idEstacion,$row->itemplan);
      }
      $valida=$this->M_extractor->VerificaExpedienteEstacion($row->idEstacion,$row->itemplan);

      $porcentaje=$this->M_extractor->Porcentaje($row->idEstacion,$row->itemplan);
      
      if($valida){
        $validae=1;
        $valida_fecha=$valida["fecha"];
        if($valida["estado"]=="ACTIVO"&&$valida["estado_final"]=="FINALIZADO"){
          $estado_ex=1;
          $fecha_valida=$valida["fecha_valida"];
          $usuario_valida=$valida["usuario_valida"];
        }else{
          $estado_ex=0;
          $fecha_valida="";
          $usuario_valida="";
        }
      }else{
        $validae=0;
        $valida_fecha="";
        $estado_ex=0;
        $fecha_valida="";
        $usuario_valida="";
      }
      $ptr_expediente=$this->M_extractor->VerificaCheck($row->poCod,$row->itemplan);
              fputcsv($file, explode('\t', utf8_decode($row->itemplan."\t". $row->poCod."\t".$row->tipoArea."\t". $row->indicador."\t". $row->estacionDesc."\t". $porcentaje."\t".
                        $row->proyectoDesc."\t". $row->subProyectoDesc."\t". $row->fechaEjecucion."\t". $row->fechaPrevEjec."\t".$row->est_innova."\t".
                        $row->estadoPlanDesc."\t". $row->empresaColabDesc."\t". $row->ptrcolab."\t". $row->zonalDesc."\t". $row->jefatura."\t". $row->valoriz_material."\t".$row->valoriz_m_o."\t".
                        $row->f_ult_est."\t". $row->usu_registro."\t". $certificable."\t". $validae."\t". $valida_fecha."\t". $ptr_expediente."\t". $estado_ex."\t". $fecha_valida
                        ."\t". $usuario_valida)));   
    }   

    fclose($file); 
    
    }******************************/
    
    public function tablaExtractor(){
     $toro=$this->M_extractor->ListarExtractor();
     $file = fopen(PATH_FILE_UPLOAD_EXTRACTOR_LIQUIDACION, "w");
              fputcsv($file,  explode('\t',"ItemPlan"."\t"."PTR"."\t"."AREA"."\t"."indicador"."\t"."Estacion"."\t"."Porcentaje"."\t"."Proyecto"."\t"."SubProyecto"."\t"."Fecha Creacion IP"."\t"."Ejecucion"."\t"."Prevista"."\t"."Estado PTR"."\t"."Estado IP"."\t"."Colab IP"."\t"."Colab PTR"."\t"."Zonal"."\t"."Jefatura"."\t"."Monto Mat"."\t"."Monto Mo"."\t"."Ultimo Estado"."\t"."Usuario Ultimo Estado"."\t"."Identificador"."\t"."Expediente"."\t"."Fecha Expediente"."\t"."Check"."\t"."Validado"."\t"."Fecha Valida"."\t"."Usuario Valida"));  
    foreach ($toro->result() as $row) {
            
      if($row->idEstadoPlan==4){
        $certificable="C";
      }else{
      $certificable=$this->M_extractor->VerificarPtrEstacion($row->idEstacion,$row->itemplan);
      }
      $valida=$this->M_extractor->VerificaExpedienteEstacion($row->idEstacion,$row->itemplan);

      $porcentaje=$this->M_extractor->Porcentaje($row->idEstacion,$row->itemplan);
      
      if($valida){
        $validae=1;
        $valida_fecha=$valida["fecha"];
        if($valida["estado"]=="ACTIVO"&&$valida["estado_final"]=="FINALIZADO"){
          $estado_ex=1;
          $fecha_valida=$valida["fecha_valida"];
          $usuario_valida=$valida["usuario_valida"];
        }else{
          $estado_ex=0;
          $fecha_valida="";
          $usuario_valida="";
        }
      }else{
        $validae=0;
        $valida_fecha="";
        $estado_ex=0;
        $fecha_valida="";
        $usuario_valida="";
      }
      $ptr_expediente=$this->M_extractor->VerificaCheck($row->poCod,$row->itemplan);
              fputcsv($file, explode('\t', utf8_decode($row->itemplan."\t". $row->poCod."\t".$row->tipoArea."\t". $row->indicador."\t". $row->estacionDesc."\t". $porcentaje."\t".
                        $row->proyectoDesc."\t". $row->subProyectoDesc."\t". $row->fecha_registro."\t". $row->fechaEjecucion."\t". $row->fechaPrevEjec."\t".$row->est_innova."\t".
                        $row->estadoPlanDesc."\t". $row->empresaColabDesc."\t". $row->ptrcolab."\t". $row->zonalDesc."\t". $row->jefatura."\t". $row->valoriz_material."\t".$row->valoriz_m_o."\t".
                        $row->f_ult_est."\t". $row->usu_registro."\t". $certificable."\t". $validae."\t". $valida_fecha."\t". $ptr_expediente."\t". $estado_ex."\t". $fecha_valida
                        ."\t". $usuario_valida)));   
    }   

    fclose($file); 
    
    }
    
    
    }