<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_changeEjec extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_plan_obra/M_changeEjec');
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
               $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_PLAN_DE_OBRA, ID_PERMISO_HIJO_LIQUIDADOR_MASIVO_CRI);
               $data['opciones'] = $result['html'];
               
                     $this->load->view('vf_plan_obra/v_changeEjec',$data);
               }else{
             redirect('login','refresh');
        }
    }
    // todo OK  no mirar arriba. 
    
    
    // VALIDADOR DE LA ESTRUCTURA
    
    public function uploadliqui(){
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

                if(count($comp)==NUM_COLUM_TXT_LIQUIDADOR_MASIVO){ // numero de columnas en el txt
                    $this->session->set_flashdata('rutaFileDP',$uploadfile);
                    $data['error'] = EXIT_SUCCESS;
                }else{
                    throw new Exception('El Documento no tiene la estructura correcta (2 Columnas Separados por Tabulaciones.)');
                }
               
              } else {
                throw new Exception('Hubo un problema con la carga del archivo de PTRS al servidor, comuniquese con el administrador.');
            }
            
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }       
        echo json_encode($data);
    }
    
    
    /////////////////
    public function uploadliqui1(){
        $data ['error']= EXIT_ERROR;
        $data['msj'] = null;
        try{
            $data = $this->M_changeEjec->loadDataImportDetalleObra($this->session->flashdata('rutaFileDP'));       
        }catch(Exception $e){
            $data['msj'] = 'Error interno, comuniquese con el administrador (Upload2)';;
        }       
        echo json_encode($data);
    }
    
    public function uploadliqui2(){
        $data ['error']= EXIT_ERROR;
        $data['msj'] = null;
        try{
            $data = $this->M_changeEjec->updateFechasSQL();            
            $data['msj'] = 'Se completo la carga!';
        }catch(Exception $e){
            $data['msj'] = 'Error interno, comuniquese con el administrador (Upload3)';;
        }
        echo json_encode($data);
    }

}
