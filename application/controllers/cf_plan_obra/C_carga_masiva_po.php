<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * @author CARLOS ZAVALA C.
 * 18/01/2018
 *
 */
class C_carga_masiva_po extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_plan_obra/m_carga_masiva_po');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }
    
	public function index(){
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){    	       
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
               $permisos =  $this->session->userdata('permisosArbol');
        	   $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_PLAN_DE_OBRA, ID_PERMISO_HIJO_CARGA_MASIVA_PO);
        	   $data['opciones'] = $result['html'];
        	  
        	         $this->load->view('vf_plan_obra/v_carga_masiva_po',$data);
        	  
    	 }else{
        	 redirect('login','refresh');
	    }
             
    }
    
    public function uploadPo(){
        $data ['error']= EXIT_ERROR;
        $data['msj'] = null;
        try{
            $uploaddir =  'uploads/po/';//ruta final del file
            $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);        
            if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
                $fp = fopen($uploadfile, "r");
                $linea = fgets($fp);   
                $comp = preg_split("/[\t]/", $linea);
                fclose($fp);
                if(count($comp)==NUM_COLUM_TXT_MASIVO_PLANOBRA){
                    $this->session->set_flashdata('rutaFilePo',$uploadfile);
                    $data['error'] = EXIT_SUCCESS;
                }else{
                    throw new Exception('El archivo no cuenta con la estructura correcta (37 columnas separados por tabulaciones.)');
                }
                
            } else {
               throw new Exception('Hubo un problema con la carga del archivo al servidor, comuniquese con el administrador.');
            }
            
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }       
        echo json_encode($data);
    }
    
    public function uploadPo2(){
        $data ['error']= EXIT_ERROR;
        $data['msj'] = null;
        try{
        $data = $this->m_carga_masiva_po->loadDataImportPlanObra($this->session->flashdata('rutaFilePo'));       
       
        }catch(Exception $e){
            $data['msj'] = 'Error interno, comuniquese con el administrador (Upload2)';;
        }       
        echo json_encode($data);
    }
    
   public function uploadPo3(){
        $data ['error']= EXIT_ERROR;
        $data['msj'] = null;
        try{
            /*******************MODIFICACION MIGUEL RIOS 24052018************************************************/
            $valida1=$this->m_carga_masiva_po->verificaSubproyecto();
            $valida2=$this->m_carga_masiva_po->verificaFechaInicio();
            $valida3=$this->m_carga_masiva_po->verificaCentral();
            $valida4=$this->m_carga_masiva_po->verificaEEELEC();
            $valida5=$this->m_carga_masiva_po->verificaSubproyectoAntiguos();
            
            $validaT=$valida1+$valida2+$valida3+$valida4;
            $msjerror='';
            if ($validaT==0){
                /***creacion del plan de obra*******************/
                $data = $this->m_carga_masiva_po->execImportPlaoObraMasivo();  
                $succes = $this->makeSuccesFile();
                $data['numsuc'] = $succes['numsuc'];
                $error = $this->makeErrorFile();
                $data['numerr'] = $error['numerr'];
                /***********************************************/
            }else{
               
                if($valida1>0){
                    $msjerror= 'Error en data: El nombre del subproyecto no debe ser vacio.<br>';
                }
                if($valida2>0){
                    $msjerror.= 'Error en data: La fecha de inicio del plan no debe ser vacio.<br>'; 
                }
               
                if($valida3>0){
                   $msjerror.= 'Error en data: El valor de la central no debe ser vacio.<br>';
                }
                if($valida4>0){
                $msjerror.='Error en data: El valor de la empresa electrica no debe ser vacio. valor como minimo = SIN EMPRESA.<br>';
                }
                
                if($valida5>0) {
                    $msjerror.='Error en data: No permite ingresar subproyectos 2016 y 2017<br>';
                }
                $data['msj']=$msjerror;
               
            }
            /****************************************************************************************/
        }catch(Exception $e){
            $data['msj'] = 'Error interno, comuniquese con el administrador (Upload3)';
        }
        echo json_encode($data);
    }
    
   public function makeSuccesFile(){       
        $data ['error']= EXIT_ERROR;
        try{            
        
            $file = fopen(PATH_FILE_UPLOAD_PO_SUCCESS, "w");
            $success = $this->m_carga_masiva_po->getUploadPoSuccess();
            $data['numsuc'] = count($success->result());   
            fputcsv($file, explode('\t',"ITEMPLAM"."\t"."NOMBRE PROYECTO"."\t"."INDICADOR"."\t"."FECHA INICIO"));
            
            foreach ($success->result() as $row){
                fputcsv($file, explode('\t',utf8_decode($row->itemplan ."\t".$row->nombreProyecto."\t".$row->indicador."\t".$row->fecha_inicio)));
            }               
            fclose($file);
            $data ['error']= EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = 'Error interno, comuniquese con el administrador (#120)';
        }
        return $data;        
    }
    
    
    public function makeErrorFile(){
        $data ['error']= EXIT_ERROR;
        try{
        
            $file = fopen(PATH_FILE_UPLOAD_PO_ERROR, "w");
            $error = $this->m_carga_masiva_po->getUploadPoError();
            $data['numerr'] = count($error->result());
            fputcsv($file,  explode('\t',"NOMBRE PROYECTO" ."\t". "SUBPROYECTO"."\t"."COORDX"."\t"."COORDY"."\t"."INDICADOR"."\t".
            "UIP" ."\t". "FECHA INICIO"."\t". "FECHA PREVISTA"."\t". "FECHA EJECUCION"."\t"."FECHA CANCELACION"."\t".
            "ESTADO PLAN" ."\t". "FASE"."\t". "CENTRAL"."\t". "EMPRESA ELECTRICA"."\t"."PROVINCIA"."\t".
            "DEPARTAMENTO" ."\t". "CANTIDAD TROBA"));
            foreach ($error->result() as $row){
                fputcsv($file, explode('\t', utf8_decode($row->nombre_proyecto ."\t". $row->subproyecto."\t". $row->cordx."\t". $row->cordy."\t".$row->indicador."\t".
                              $row->uip ."\t". $row->fecha_inicio."\t". $row->fecha_prevista."\t". $row->fecha_ejecucion."\t".$row->fecha_cancelacion."\t".
                              $row->estado_plan ."\t". $row->fase."\t". $row->central."\t". $row->empresa_electrica."\t".$row->provincia."\t".
                              $row->departamento ."\t". $row->cantidad_troba)));
            }
            fclose($file);
            $data ['error']= EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = 'Error interno, comuniquese con el administrador (#121)';
        }
        return $data;
    }
    

}