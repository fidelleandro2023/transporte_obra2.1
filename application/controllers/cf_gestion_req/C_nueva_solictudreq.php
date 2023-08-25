<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *
 */
class C_nueva_solictudreq extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_gestion_req/m_gestion_req');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }
    
	public function index()
	{  	   
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){

                $data['notify'] = (isset($_GET['flag']) ? '<div class="alert alert-success" role="alert">
                                <strong>Solicitud creada</strong> satisfactoriamente. Será atendida su solicitud a la brevedad.
                            </div>' : '');  
              
               $data['listatiporeq'] = $this->m_gestion_req->getListaTipoReq();
               
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');	
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');	               
        	   $permisos =  $this->session->userdata('permisosArbol');
        	   $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_GESTIOREQ, ID_PERMISO_HIJO_GESTIOREQ);
        	   $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	       $this->load->view('vf_gestion_req/v_nueva_solicitud',$data);
        	   }else{
        	       redirect('login','refresh');
	           }
	   }else{
	       redirect('login','refresh');
	   }
    }
   
    
    public function enviarSolicitudReq(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
             // Datos personales   
            $tiporeq = $this->input->post('tiporeq');
            $accion = $this->input->post('accion');
            $tablarela = $this->m_gestion_req->getTablaRefAccion($tiporeq);
            $observaciones = $this->input->post('observaciones');
            $solicitante=$this->session->userdata('idPersonaSession');
            $input_name='anexo';
           $uploadfile=null;
           $filename=null;
           
            if((isset($_FILES[$input_name]['error']) && $_FILES[$input_name]['error']=='UPLOAD_ERROR_OK')){
                $uploaddir =  'uploads/gestion_req/';//ruta final del file
                $filename=$_FILES[$input_name]['name'];
                //$uploadfile= $uploaddir.$_FILES[$input_name]['name'].".".pathinfo($_FILES[$input_name]['name'], PATHINFO_EXTENSION);
                $uploadfile= $uploaddir.$filename;
                move_uploaded_file($_FILES[$input_name]['tmp_name'],$uploadfile);
            }

            $this->m_gestion_req->insertNuevoReq($tiporeq,$accion,$tablarela,$observaciones,$solicitante, $filename);
           
                /******************************/
             $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        redirect('nsolgesreq?flag=1','refresh');
        
        
    }



    public function getHTMLLoadAccion(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $tiporeq = $this->input->post('tiporeq');
            
            $datosReq= $this->m_gestion_req->getTablaAccion($tiporeq);

            foreach($datosReq->result() as $row){
                $id=$row->idtablaaccion;
                $descripcion=$row->descriptablaaccion;
                $tabla=$row->tablaaccion;
            }

            $dataAccion=$this->m_gestion_req->getDatosCargaAccion($id,$descripcion,$tabla);

            $html = '';
            
            foreach($dataAccion->result() as $row){
                 $html .= '<option value="'.$row->id.'">'.$row->descripcion.'</option>';
        
            }

            $data['listaAccion'] = $html;

            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    
   
    
   
    
}