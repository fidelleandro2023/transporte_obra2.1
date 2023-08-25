<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * @author CARLOS ZAVALA C.
 * 18/01/2018
 *
 */
class C_tranferencia_sap_coaxial extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_tranferencias/m_tranferencia_sap_coaxial');
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
        	   $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_TRANFERENCIAS, ID_PERMISO_HIJO_TRANFERENCIA_SAP_COAXIAL);
        	   $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	         $this->load->view('vf_tranferencias/v_tranferencia_sap_coaxial',$data);
        	   }else{
        	       redirect('login','refresh');
        	   }
    	 }else{
        	 redirect('login','refresh');
	    }
             
    }
    
    public function uploadSc1(){
        $data ['error']= EXIT_ERROR;
        $data['msj'] = null;
        try{
            $uploaddir =  'uploads/sap/';//ruta final del file
            $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);  
           
            if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
                
                $dataf = $this->readFileSapFija($uploadfile,PATH_FILE_UPLOAD_SAP_COAXIAL_EDIT);          
                if($dataf['error'] == 1){
                    throw new Exception('Ocurrio un problema a tratar de leer el archivo, vuelva a intentarlo o comuniquese con el administrador.');
                }
                if($dataf['countTmp'] != NUM_COLUM_TXT_SAP_COAXIAL){
                    throw  new Exception("El archivo no tiene la estructura esperada!");
                }else{
                    //$this->session->set_flashdata('rutaSapFija',PATH_FILE_UPLOAD_SAP_FIJA_EDIT);
                    $data['error'] = EXIT_SUCCESS;
                }                               
            } else {
               throw new Exception('Hubo un problema con la carga del archivo al servidor, comuniquese con el administrador.');
            }
            
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }               
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function readFileSapFija($inputFile, $outputFile){    
        $data ['error']= EXIT_ERROR;
        $data['msj'] = null;
        
        $trigger = false;
        $initPos = 0;    
        $countTmp = 0;
        
        try{
            $file = fopen($inputFile, "r") or exit("Unable to open file!");        
            $file2 = fopen($outputFile, "w");
        
            while(!feof($file))
            {
                $linea = fgets($file);
                $comp = preg_split("/[\t]/", $linea);
                if($trigger){
                    $this->addRow($initPos, $comp,$file2,$countTmp);
                   
                }else if(!$trigger){
                    if(trim($comp[0])==1){
                        $countTmp = count($comp);
                        $initPos = 0;
                        $this->addRow($initPos, $comp,$file2,$countTmp);                       
                        $trigger = true;
                    }else
                        if(trim($comp[1])==1){
                        $countTmp = count($comp);
                        $initPos = 1;
                        $this->addRow($initPos, $comp,$file2,$countTmp);
                        $trigger = true;
                    }else
                        if(trim($comp[2])==1){
                        $countTmp = count($comp);
                        $initPos = 2;
                        $this->addRow($initPos, $comp,$file2,$countTmp);
                        $trigger = true;
                    }else
                        if(trim($comp[3])==1){
                        $countTmp = count($comp);
                        $initPos = 3;
                        $this->addRow($initPos, $comp,$file2,$countTmp);
                        $trigger = true;       
                    }
        
                }
        
        
            }
        
            fclose($file2);
            fclose($file);
            $data ['error']= EXIT_SUCCESS;
            $data['countTmp'] = $countTmp;
        }catch(Exception $e){
           
            $data['msj'] = "ERROR LECTURA FILE!";
    }
    return $data;
    }
    
    function addRow($initPos, $comp, $file2,$countTmp){
        
            if(count($comp)==$countTmp){
                if($comp[$initPos] =='1'){
                    $_SESSION["padre"] = $comp[$initPos+1];
                     
                }else if($comp[$initPos] =='2'){
                    $_SESSION["hijo"] = $comp[$initPos+2];
                    $comp[$initPos+1] = $_SESSION["padre"];
                     
                }else if($comp[$initPos] =='3'){
                    $_SESSION["nieto"] = $comp[$initPos+3];
                    $comp[$initPos+1] = $_SESSION["padre"];
                    $comp[$initPos+2] = $_SESSION["hijo"];
            
                }else if($comp[$initPos] =='4'){
                    $comp[$initPos+1] = $_SESSION["padre"];
                    $comp[$initPos+2] = $_SESSION["hijo"];
                    $comp[$initPos+3] = $_SESSION["nieto"];
                }
            
                $linea ='';
            
                for($i=$initPos; $i<count($comp); $i++){
                    $linea .= $comp[$i]."\t";
                     
                }
                   
                fwrite($file2, trim($linea) . PHP_EOL); 
               
            }  
        }
        
        public function uploadSc2(){
            $data ['error']= EXIT_ERROR;
            $data['msj'] = null;
            try{
              
                $data = $this->m_tranferencia_sap_coaxial->loadDataImportSapCoaxial(PATH_FILE_UPLOAD_SAP_COAXIAL_EDIT);               
                             
            
            }catch(Exception $e){
                $data['msj'] = $e->getMessage();
            }
            echo json_encode(array_map('utf8_encode', $data));
            
        }
}