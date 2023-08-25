<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * @author CARLOS ZAVALA C.
 * 18/01/2018
 *
 */
class C_valereserva extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_valereserva/m_valereserva');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_extractor/m_extractor');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }
    
	public function index(){
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){    	       
           $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
           $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
           $permisos =  $this->session->userdata('permisosArbol');
    	   $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_VALERESERVA, ID_PERMISO_HIJO_VALERESERVA);
    	   $data['opciones'] = $result['html'];
    	   if($result['hasPermiso'] == true){
    	         $this->load->view('vf_valereserva/v_carga_vr_correccion',$data);
    	   }else{
    	       redirect('login','refresh');
    	   }
    	 }else{
        	 redirect('login','refresh');
	    }
             
    }
    
      public function dwnFileVR(){
        $data ['error']= EXIT_ERROR;
        $data['msj'] = null;
        try{
            $uploaddir =  'uploads/valereservacorreccion/';//ruta final del file
            $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);        
            if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
                $fp = fopen($uploadfile, "r");
                $linea = fgets($fp);   
                $comp = preg_split("/[\t]/", $linea);
                fclose($fp); 
                if(count($comp)==NUM_COLUM_TXT_VALERESERVA){
                    $this->session->set_flashdata('rutaFileVR',$uploadfile);
                    $data['error'] = EXIT_SUCCESS;
                }else{
                    throw new Exception('El archivo no cuenta con la estructura correcta.');
                }
                
            } else {
               throw new Exception('Hubo un problema con la carga del archivo al servidor, comuniquese con el administrador.');
            }
            
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }       
        echo json_encode($data);
    }
    
   public function uploadVR(){
        $data ['error']= EXIT_ERROR;
        $data['msj'] = null;
        try{
             
        $data = $this->m_valereserva->getImportValeReserva($this->session->flashdata('rutaFileVR'));       
        if($data['error']==EXIT_ERROR){
            throw new Exception("ERROR CARGA getImportValeReserva");
        } 
       
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }       
        echo json_encode($data);
    }
    
  
    public function upWUExtrVR(){
        $data ['error']= EXIT_ERROR;
        $data['msj'] = null;
        try{
            $data = $this->m_valereserva->exeUpdateWUfromVR();    
            if($data['error'] == EXIT_ERROR){
                throw new Exception('Error en exeUpdateWUfromVR()');
            }else if ($data['error'] == EXIT_SUCCESS){

                $this->crearCSVItemValeReserva();
                $data = $this->m_valereserva->saveLogUpdateVRenWU();
                
           }     
           
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }
        
    public function crearCSVItemValeReserva(){
        $data['error']= EXIT_ERROR;
        $data['msj'] = null;
        try{
            $user = $this->session->userdata('idPersonaSession');
            $idPerfil = $this->session->userdata('idPerfilSession');
           
            $resultado = $this->m_extractor->getItemplanValeReserva();
            
            if($resultado->num_rows()  > 0){


                $file = fopen(PATH_FILE_UPLOAD_VALE_RESERVA, "w");
                /****************************************miguel rios 02072018
                fputcsv($file,  explode('\t',"ITEMPLAN"."\t"."ESTADO"."\t"."PTR"."\t"."ESTADO PTR"."\t"."FECHA APROB"."\t"."PORCENTAJE"."\t"."AREA"."\t"."ESTACION"."\t"."VALE RESERVA"."\t"."VALORIZ_MATERIAL"."\t"."VALORIZ_MO"));
                foreach ($resultado->result() as $row){                    
                    fputcsv($file, explode('\t', utf8_decode($row->itemplan."\t". $row->estado."\t". $row->ptr."\t". $row->estadoptr."\t".$row->fechaaprob."\t".
                        $row->porcentaje."\t". $row->desc_area."\t". $row->estacion."\t". $row->valereserva."\t".$row->valor_material."\t".
                        $row->valor_mo)));
                }***************************************************/
                fputcsv($file,  explode('\t',"ITEMPLAN"."\t"."ESTADO"."\t"."PTR"."\t"."ESTADO PTR"."\t"."FECHA APROB"."\t"."AREA"."\t"."VALE RESERVA"."\t"."VALORIZ_MATERIAL"."\t"."VALORIZ_MO"));

                foreach ($resultado->result() as $row){                    
                    fputcsv($file, explode('\t', utf8_decode($row->itemplan."\t". $row->estado."\t". $row->ptr."\t". $row->estadoptr."\t".$row->fechaaprob."\t".$row->desc_area."\t". $row->valereserva."\t".$row->valor_material."\t".$row->valor_mo)));
                }


                fclose($file);
            }
        }catch (Exception $e){
            $data['msj'] = 'Error interno, al crear archivo de vale de reserva';
        }
        return $data;
    }





}