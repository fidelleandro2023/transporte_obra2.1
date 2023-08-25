<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_carga_update_fech extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_plan_obra/m_carga_update_fech');
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
               $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_PLAN_DE_OBRA, ID_PERMISO_HIJO_CARGA_UPDATE_FECH);
               $data['opciones'] = $result['html'];

                 $this->load->view('vf_plan_obra/v_carga_update_fech',$data);
               
         }else{
             redirect('login','refresh');
        }
    }
    
    public function uploadFech(){
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

                if(count($comp)==NUM_COLUM_TXT_MASIVO_UPDATEFECH){ //...
                    $this->session->set_flashdata('rutaFileDP',$uploadfile);
                    $data['error'] = EXIT_SUCCESS;
                }else{
                    throw new Exception('El Documento no tiene la estructura correcta (3 Columnas Separados por Tabulaciones.)');
                }
                
              } else {
                throw new Exception('Hubo un problema con la carga del archivo de PTRS al servidor, comuniquese con el administrador.');
            }
            
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }       
        echo json_encode($data);
    }
    
    public function uploadFech2(){
        $data ['error']= EXIT_ERROR;
        $data['msj'] = null;
        try{
        $data = $this->m_carga_update_fech->loadDataImportDetalleObra($this->session->flashdata('rutaFileDP'));       
        }catch(Exception $e){
            $data['msj'] = 'Error interno, comuniquese con el administrador (Upload2)';;
        }       
        echo json_encode($data);
    }
    
    public function uploadFech3(){
        $data ['error']= EXIT_ERROR;
        $data['msj'] = null;
        try{
            $data = $this->m_carga_update_fech->updateFechasSQL();            
            $data['msj'] = 'Se completo la carga!';
        }catch(Exception $e){
            $data['msj'] = 'Error interno, comuniquese con el administrador (Upload3)';;
        }
        echo json_encode($data);
    }

}
