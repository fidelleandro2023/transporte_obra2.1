<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * 
 * 
 *
 */
class C_valereserva_eecc extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_mantenimiento/m_valereserva_eecc');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }
    
	public function index()
	{  	   
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
                /*carga de la tabla */           
               $data['listartabla'] = $this->makeHTMLTablaCentral($this->m_valereserva_eecc->getAllVREmpresaCC());
                /*carga perfiles*/
               $data['listaEECCVR'] = $this->m_utils->getAllVREECC();
               /*carga permisos*/
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');	
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');	               
        	   $permisos =  $this->session->userdata('permisosArbol');
               // permiso para registro individual modificar
			$result = $this->lib_utils->getHTMLPermisos($permisos, 253, 112, ID_MODULO_MANTENIMIENTO);
        	   
               $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	       $this->load->view('vf_mantenimiento/v_valereserva_eecc',$data);
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
                                                        
                            <th>Nro Vale Reserva</th>
                            <th>Empresa colaboradora</th>
                             <th></th>
                        </tr>
                    </thead>
                    <tbody>';
																	                           
                foreach($listartabla->result() as $row){ 
                    
                $html .=' <tr>
							<td>'.$row->codigo_vr.'</td> 
                            <td>'.$row->empresaColabDesc.'</td> 
                            <td>
                            <button class="btn btn-warning" data-toggle="modal"  data-id="'.$row->codigo_vr.'" data-target="#modal-large" onclick="editValeReservaEECC(this)">Editar</button>
                            <button class="btn btn-danger" data-toggle="modal"  data-id="'.$row->codigo_vr.'" data-target="#modal-large" onclick="deleteValeReservaEECC(this)">Eliminar</button>
                            </td>
       
						</tr>';
                 }
			 $html .='</tbody>
                </table>';
                    
        return utf8_decode($html);
    }
    
    public function createValeReservaEECC(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $valereserva = $this->input->post('valereserva');
            $eecc = $this->input->post('selecteecc');
                    
            $data = $this->m_valereserva_eecc->insertarValeReservaEECC($valereserva,$eecc);
            
            if($data['error']==EXIT_ERROR){
                throw new Exception('Error al Insertar createPermisoPerfil');
            }
           
            $data['listartabla'] = $this->makeHTMLTablaCentral($this->m_valereserva_eecc->getAllVREmpresaCC());
    
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    /*ENLAZA CON VIEW PARA ENVIAR A MODEL Y recibir DATOS PARA LA EDICION*/

    public function getInfoValeReservaEECC(){
         
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $valereserva = $this->input->post('id');            
            $valereecc = $this->m_valereserva_eecc->getValeRerservaEECC($valereserva);           
            /*empresa electrica*/

            $data['codigovr'] = $valereecc['codigo_vr'];
            /*estado del plan*/
            $data['eecc'] = $valereecc['idEmpresaColab'];
            
            $this->session->set_flashdata('valereserva',$valereserva);
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    



    public function editValeReservaEECC(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            /*recibe empresa electrica*/
           $valereserva = $this->input->post('valereserva2');
           $eecc = $this->input->post('selecteecc2');
            
            $codigovr = $this->session->flashdata('valereserva');
            
            $data = $this->m_valereserva_eecc->editarValeReservaEECC($codigovr,$eecc);
            
            if($data['error']==EXIT_ERROR){
                throw new Exception('Error al editar permiso por perfil');
            }
            $data['listartabla'] = $this->makeHTMLTablaCentral($this->m_valereserva_eecc->getAllVREmpresaCC());
        
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
   }


    public function delValeReservaEECC(){
         $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try{
            /*recibe empresa electrica*/
            $valereserva = $this->input->post('id');

               
            $data = $this->m_valereserva_eecc->eliminarValeReservaEECC($valereserva);
            
            if($data['error']==EXIT_ERROR){
                throw new Exception('Error al editar permiso por perfil');
            }
            $data['listartabla'] = $this->makeHTMLTablaCentral($this->m_valereserva_eecc->getAllVREmpresaCC());
        
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));

    }

    public function validaValeReserva(){
         $valereserva = $this->input->post('valereserva');  
         
        $cant = null;
        if($valereserva!= null){
            $res  = $this->m_valereserva_eecc->existeValeResevaEECC($valereserva);

            $cant=$res;

            if($res>=1){
               $cant=1;
            }else{
                $cant=0;
            }

        }else{
            $cant = '1';//Si hay un error que simule que si existe
        }
        echo $cant;
    }



}