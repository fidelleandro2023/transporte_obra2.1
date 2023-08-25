<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_carga_masiva_dp extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_detalle_obra/m_carga_masiva_dp');
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
               $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_PLAN_DE_OBRA, ID_PERMISO_HIJO_CARGA_MASIVA_DETALLE_PLAN);
               $data['opciones'] = $result['html'];
               if($result['hasPermiso'] == true){
                     $this->load->view('vf_detalle_obra/v_carga_masiva_dp',$data);
               }else{
                   redirect('login','refresh');
               }
         }else{
             redirect('login','refresh');
        }
    }
    
    public function uploadDP(){
        $data ['error']= EXIT_ERROR;
        $data['msj'] = null;
        try{
            $uploaddir =  'uploads/dp/';//ruta final del file
            $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);        
            if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
                $fp = fopen($uploadfile, "r");
                $linea = fgets($fp);   
                $comp = preg_split("/[\t]/", $linea);
                fclose($fp);

                if(count($comp)==NUM_COLUM_TXT_MASIVO_DETALLEPLAN){ //...
                    $this->session->set_flashdata('rutaFileDP',$uploadfile);
                    $data['error'] = EXIT_SUCCESS;
                }else{
                    throw new Exception('El archivo no cuenta con la estructura correcta (3 columnas separados por tabulaciones.)');
                }
                
            } else {
               throw new Exception('Hubo un problema con la carga del archivo de ptrs al servidor, comuniquese con el administrador.');
            }
            
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }       
        echo json_encode($data);
    }
    
    public function uploadDP2(){
        $data ['error']= EXIT_ERROR;
        $data['msj'] = null;
        try{
        $data = $this->m_carga_masiva_dp->loadDataImportDetalleObra($this->session->flashdata('rutaFileDP'));       
        }catch(Exception $e){
            $data['msj'] = 'Error interno, comuniquese con el administrador (Upload2)';;
        }       
        echo json_encode($data);
    }


   public function uploadDP3(){
        $data ['error']= EXIT_ERROR;
        $data['msj'] = null;
        try{
            $data = $this->m_carga_masiva_dp->execImportDetallePlanMasivo();  
            $succes = $this->makeSuccesFile();
            $data['numsuc'] = $succes['numsuc'];
            $error = $this->makeErrorFile();
            $data['numerr'] = $error['numerr'];
        }catch(Exception $e){
            $data['msj'] = 'Error interno, comuniquese con el administrador (Upload3)';
        }
        echo json_encode($data);
    }
    
    public function uploadDP4(){
        $data ['error']= EXIT_ERROR;
        $data['msj'] = null;
        $idUser = $this->session->userdata('idPersonaSession');
        //$test = array();
        try{
            // trayendo ptrs
            $arrayPTR = $this->m_carga_masiva_dp->ptrGroup();
            $size = count($arrayPTR->result());
            $cont = 1;
            $stringINPTR = ''; 
            $stringIN = ''; 

            // Trayendo las ptr como cadena por comas
            foreach ($arrayPTR->result() as $row){
                if($size == $cont){
                    $stringINPTR .= '"'.$row->ptr.'"';
                    $this->m_carga_masiva_dp->deleteEnWebUnitDet($stringINPTR);
                    $this->m_carga_masiva_dp->selectDet($stringINPTR);
                    //$this->m_carga_masiva_dp->getGrafoOnePTR("'".$stringINPTR."'");
                    $this->m_carga_masiva_dp->insertPTRenLog($row->item, $row->ptr, $idUser);
                }else if($cont == 40){
                    $stringINPTR .= '"'.$row->ptr.'"';
                    $this->m_carga_masiva_dp->deleteEnWebUnitDet($stringINPTR);
                    $this->m_carga_masiva_dp->selectDet($stringINPTR);
                    //$this->m_carga_masiva_dp->getGrafoOnePTR("'".$stringINPTR."'");
                    $this->m_carga_masiva_dp->insertPTRenLog($row->item, $row->ptr, $idUser);
                    $stringINPTR = '';
                    $cont = 1;
                }else{
                    $stringINPTR .= '"'.$row->ptr.'", ';
                    $this->m_carga_masiva_dp->insertPTRenLog($row->item, $row->ptr, $idUser);
                }               
                $cont++;
            }
            $data ['error']= EXIT_SUCCESS;
            


            
        }catch(Exception $e){
            $data['msj'] = 'Error interno, comuniquese con el administrador (Upload4)';
        }
        echo json_encode($data);
    }



    
   public function makeSuccesFile(){       
        $data ['error']= EXIT_ERROR;
        try{            
        
            $file = fopen(PATH_FILE_UPLOAD_PO_SUCCESS, "w");
            $success = $this->m_carga_masiva_dp->getUploadPoSuccess();
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
            $error = $this->m_carga_masiva_dp->getUploadPoError();
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