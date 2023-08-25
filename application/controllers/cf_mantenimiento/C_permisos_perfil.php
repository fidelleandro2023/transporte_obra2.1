<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * 
 * 
 *
 */
class C_permisos_perfil extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_mantenimiento/m_permisos_perfil');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }
    
	public function index()
	{  	   
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
                /*carga de la tabla */           
               $data['listartabla'] = $this->makeHTMLTablaCentral($this->m_permisos_perfil->getAllPermisosPerfil());
                /*carga perfiles*/
               $data['listaPerfiles'] = $this->m_utils->getAllPerfil();
               /*carga permisos*/
               $data['listaPermisos'] = $this->m_utils->getAllPermisos();
             
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');	
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');	               
        	   $permisos =  $this->session->userdata('permisosArbol');
               // permiso para registro individual modificar
        	   #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_MANTENIMIENTO, ID_PERMISO_HIJO_PERMISO_PERFIL);
        	   $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_MANTENIMIENTO_NUEVO_MODELO, ID_PERMISO_HIJO_PERMISO_PERFIL, ID_MODULO_MANTENIMIENTO);
               $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	       $this->load->view('vf_mantenimiento/v_permisos_perfil',$data);
        	   }else{
        	       redirect('login','refresh');
	           }
	   }else{
	       redirect('login','refresh');
	   }
    }
   
    
    public function makeHTMLTablaCentral($listartabla){
     
        $html = '
        <table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>Perfil</th>                            
                            <th>Permiso</th>
                            <th>Accion</th>
                        </tr>
                    </thead>
                    <tbody>';
																	                           
                foreach($listartabla->result() as $row){ 
                    
                $html .=' <tr>
							<td>'.$row->perfil.'</td> 
                            <td>'.$row->permiso.'</td> 
                            <td>
                            <button class="btn btn-warning" data-toggle="modal"  data-id="'.$row->id.'" data-target="#modal-large" onclick="editPermisoPerfil(this)">Editar</button>
                            <button class="btn btn-danger" data-toggle="modal"  data-id="'.$row->id.'" data-perfil="'.$row->perfil.'" data-permiso="'.$row->permiso.'" data-target="#modal-large" onclick="deletePermisoPerfil(this)">Eliminar</button>
                            </td>
       
						</tr>';
                 }
			 $html .='</tbody>
                </table>';
                    
        return utf8_decode($html);
    }
    
    public function createPermisoPerfil(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $id_perfil = $this->input->post('selectPerfil');
            $id_permiso = $this->input->post('selectPermiso');
                    
            $data = $this->m_permisos_perfil->insertarPermisoPerfil($id_permiso,$id_perfil);
            
            if($data['error']==EXIT_ERROR){
                throw new Exception('Error al Insertar createPermisoPerfil');
            }
           
            $data['listartabla'] = $this->makeHTMLTablaCentral($this->m_permisos_perfil->getAllPermisosPerfil());
    
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    /*ENLAZA CON VIEW PARA ENVIAR A MODEL Y recibir DATOS PARA LA EDICION*/

    public function getInfoPermisoPerfil(){
         
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $id_permisoperfil = $this->input->post('id');            
            $permisoperfil = $this->m_permisos_perfil->getPermisoPerfilInfo($id_permisoperfil);           
            /*empresa electrica*/

            $data['permiso'] = $permisoperfil['id_permiso'];
            /*estado del plan*/
            $data['perfil'] = $permisoperfil['id_perfil'];
            
            $this->session->set_flashdata('idPerPerEdit',$id_permisoperfil);
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    



    public function editPermisoPerfil(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            /*recibe empresa electrica*/
            $id_perfil = $this->input->post('selectPerfil2');
            /*recibe estado del plan*/
            $id_permiso = $this->input->post('selectPermiso2');
            
            $id_permisoperfil = $this->session->flashdata('idPerPerEdit');
            
            $data = $this->m_permisos_perfil->editarPermisoPerfil($id_permisoperfil, $id_perfil, $id_permiso);
            
            if($data['error']==EXIT_ERROR){
                throw new Exception('Error al editar permiso por perfil');
            }
            $data['listartabla'] = $this->makeHTMLTablaCentral($this->m_permisos_perfil->getAllPermisosPerfil());
        
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
   }


    public function delPermisoPerfil(){
         $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try{
            /*recibe empresa electrica*/
            $id_permisoperfil = $this->input->post('id_permiso_perfil');

            $data = $this->m_permisos_perfil->eliminarPermisoPerfil($id_permisoperfil);
            
            if($data['error']==EXIT_ERROR){
                throw new Exception('Error al editar permiso por perfil');
            }
            $data['listartabla'] = $this->makeHTMLTablaCentral($this->m_permisos_perfil->getAllPermisosPerfil());
        
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));

    }

    public function validaPermisoPerfil(){
         $permiso = $this->input->post('permiso');  
         $perfil = $this->input->post('perfil');        
       
        $cant = null;
        if($permiso != null and $perfil != null){
            $res  = $this->m_permisos_perfil->existePermisoxPerfil($permiso,$perfil);
            $cant = $res->num_rows() == 1 ? ($res->row()->cant >= 1 ? '1' : '0') : '0';
        }else{
            $cant = '1';//Si hay un error que simule que si existe
        }
        echo $cant;
    }



}