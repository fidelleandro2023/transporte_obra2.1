<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * 
 * 
 *
 */
class C_subproyecto_partida_pi extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_plantaInterna/m_subproyecto_partida_pi');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }
    
	public function index()
	{  	   
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
                $pepsub = 0;
            
               $data['listasubproyecto']=$this->m_utils->getAllSubProyectoPI();
               $data['listartabla'] = $this->makeHTMLTablaCentral($this->m_subproyecto_partida_pi->getAllPartidasSubproyecto());
               
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');	
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');	               
        	   $permisos =  $this->session->userdata('permisosArbol');
        	   #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_PLANTA_INTERNA, ID_PERMISO_HIJO_BANDEJA_MANT_SUB_ACT);
        	   $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_MANTENIMIENTO_CERTIFICACION, ID_PERMISO_HIJO_BANDEJA_MANT_SUB_ACT, ID_MODULO_MANTENIMIENTO);
			   $data['opciones'] = $result['html'];        	 
        	   if($result['hasPermiso'] == true){
        	       $this->load->view('vf_plantaInterna/V_subproyecto_partida_pi',$data);
        	   }else{
        	       redirect('login','refresh');
	           }
	   }else{
	       redirect('login','refresh');
	   }
    }
   
    
    public function makeHTMLTablaCentral($listartabla){
     
        $html = ' <table id="data-table" class="table table-bordered">
                      <thead class="thead-default">
                        <tr>
                          <th> </th>                     
                            <th>CODIGO</th>
                            <th>PARTIDA</th>
                            <th>BAREMO</th>
                            <th>KIT MATERIAL</th>
                            <th>COSTO MATERIAL</th>
                             <th>SUBPROYECTOS</th>
                            <th>ESTADO</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>';
                                                                                               
                foreach($listartabla->result() as $row){ 
                    
                $html .=' <tr>
                            <td><button class="btn btn-warning" data-toggle="modal"  data-id="'.$row->idActividad.'" data-target="#modal-large" onclick="editPartida(this)">Editar</button></td>
                            <td>'.$row->codigo.'</td>
                            <td>'.$row->descripcion.'</td> 
                            <td>'.$row->baremo.'</td> 
                            <td>'.$row->kit_material.'</td>  
                            <td>'.$row->costo_material.'</td>
                             <td>'.str_replace(",","<BR>",$row->subproyecto).'</td>
                            <td>'.($row->estado == 1 ? 'ACTIVO' : 'INACTIVO').'</td>
                           
                            
                            <td> 
                                                       
                            '.($row->estado == 1 ? '<a data-id ="'.$row->idActividad.'" data-estado="" class="btn btn-danger" data-toggle="modal" style="color:white; font-size:10px;width:75px;" data-target="#inactivar" onclick="desactivar(this)">Desactivar</a>' : '<a data-id ="'.$row->idActividad.'" class="btn btn-primary" data-toggle="modal" style="color:white; font-size:10px;width:75px;" data-target="#activar" onclick="activar(this)">Activar</a>').'</td>
						</tr>';
                 }
			 $html .='</tbody>
                </table>';
                    
        return utf8_decode($html);
    }
    
    
    
        
   public function getInfoPartida(){
         
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $id_partida = $this->input->post('id');            
            $partida = $this->m_subproyecto_partida_pi->getPartidaInfo($id_partida);           
      
            $data['descripcion'] = $partida['descripcion'];
            $data['kitmaterial'] = $partida['kit_material'];
            $data['baremo'] = $partida['baremo'];

            $data['costomaterial'] = $partida['costo_material'];

            $subproyparti=$this->m_subproyecto_partida_pi->getSubProyPartidaInfo($id_partida); 
            $listaSubp="";
            $indca=0;


            foreach($subproyparti->result() as $row){
                if ($indca==0){
                    $listaSubp=$row->idSubProyecto;
                    $indca=1;
                }else{
                    $listaSubp.=",".$row->idSubProyecto;
                }

            }

            $data['subproyecto']=$listaSubp;
            
            $this->session->set_flashdata('idpartida',$id_partida);
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

        
    //DESACTIVAR USUARIO
    public function upddescActPI(){
        
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $id = $this->input->post('id');
            $data = $this->m_subproyecto_partida_pi->updatePartidaEstado($id,0);
            
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
      
    //ACTIVAR USUARIO
    public function updactActPI(){
 
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $id = $this->input->post('id');
            $data = $this->m_subproyecto_partida_pi->updatePartidaEstado($id,1);
            
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
       
    public function updatePartida(){
         
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
           
           
            $idactividad = $this->input->post('inputidPartida');
            $descripcion = $this->input->post('inputDescripcion2');
            $kitmaterial = $this->input->post('inputKitMaterial2');            
            $subproyecto = $this->input->post('selectSubproy2');
            $subproyectoactual = $this->input->post('selectSubproy2');

            $baremo = $this->input->post('inputBaremo2');            
            $CostoMaterial = $this->input->post('inputCostoMaterial2');

            $data = $this->m_subproyecto_partida_pi->editPartida($idactividad, $descripcion,$kitmaterial,$baremo,$CostoMaterial);
            if($data['error']==EXIT_ERROR){
                throw new Exception($data['msj']);
            }else{
                /*

                 $data3=$this->m_subproyecto_partida_pi->EliminaSubProyPartida($idactividad);
                 if($data3['error']==EXIT_ERROR){
                      throw new Exception($data3['msj']);
                 }*/

                foreach($subproyecto as $subproy){

                    $resultadoV=$this->m_subproyecto_partida_pi->VerificaExisteSubProyPartida($idactividad,$subproy);

                    if ($resultadoV==0){
                        $data4=$this->m_subproyecto_partida_pi->AddSubProyPartida($idactividad,$subproy);
                        if($data4['error']==EXIT_ERROR){
                            throw new Exception($data4['msj']);
                        }
                    }
                }

            }

            $data['listartabla'] = $this->makeHTMLTablaCentral($this->m_subproyecto_partida_pi->getAllPartidasSubproyecto());
    
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }

        echo json_encode(array_map('utf8_encode', $data));
        
    }
    



     public function ingresarPartida(){
         
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
           
            $codigo = $this->input->post('inputCodigoPartida');
            $descripcion = $this->input->post('inputDescripcion');
            $kitmaterial = $this->input->post('inputKitMaterial');            
            $subproyecto = $this->input->post('selectSubproy');
            $baremo = $this->input->post('inputBaremo');            
            $CostoMaterial = $this->input->post('inputCostoMaterial');
           
            $this->session->set_flashdata('codigopartida',"");
            $this->session->set_flashdata('codigopartida',$codigo);

            $data = $this->m_subproyecto_partida_pi->addPartida($codigo, $descripcion,$kitmaterial,$baremo,$CostoMaterial);

            if($data['error']==EXIT_ERROR){
                throw new Exception($data['msj']);
            }else{

                $idpartida=$this->m_subproyecto_partida_pi->getidPartida($this->session->flashdata('codigopartida'));
                
                foreach($subproyecto as $sub){
                    
                    $data4=$this->m_subproyecto_partida_pi->AddSubProyPartida($idpartida,$sub);
                    if($data4['error']==EXIT_ERROR){
                        throw new Exception($data4['msj']);
                    }
                 }

            }

            $data['listartabla'] = $this->makeHTMLTablaCentral($this->m_subproyecto_partida_pi->getAllPartidasSubproyecto());


        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }

        echo json_encode(array_map('utf8_encode', $data));
       
    }

    public function existeCodigoPartida(){
         
       
        try{
            $codigo = $this->input->post('codigo');            
            $resultado = $this->m_subproyecto_partida_pi->existeCodigo($codigo);      
            $cant=$resultado;     
            if($cant>=1){
               $cant=1;
            }else{
                $cant=0;
            }
        }catch(Exception $e){
            $cant=1;
        }
       echo $cant;
    }


    public function existeNombrePartida(){
         
        
        try{
            $partida = $this->input->post('partida');            
            $resultado = $this->m_subproyecto_partida_pi->existeNombre($partida);           
             $cant=$resultado;

            if($cant>=1){
               $cant=1;
            }else{
                $cant=0;
            }
        }catch(Exception $e){
            $cant=1;
        }
        echo $cant;
    }



    
    
    
    
    
}