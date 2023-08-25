<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * 
 * 
 *
 */
class C_areaEstacion extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_areaEstacion/M_areaEstacion');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }
    
	public function index()
	{  	   
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){           
	           $data['listartablaEstacion'] = $this->makeHTMLTablaEstacion($this->M_areaEstacion->getAllEstac());//getAllEstaciones
	           $data['listartablaEstacionArea'] = $this->makeHTMLTablaEstacionArea($this->M_areaEstacion->getAllEstacArea());//getAllEstaciones
	           
	           $data['listartablaArea'] = $this->makeHTMLTablaArea($this->M_areaEstacion->getAllAreas());//getAllAreas

                $data['valorModalArea'] = $this->M_areaEstacion->getAllAreas();//getAllAreas
                $data['valorModalTipoArea'] = $this->M_areaEstacion->getAllTiposAreas();//getAllAreas
                $data['valorModalEstacion'] = $this->M_areaEstacion->getAllEstac();

	           $data['selectTipoArea'] = $this->M_areaEstacion->getAllTiposAreas();//getAllAreas
	           $data['selectArea'] = $this->M_areaEstacion->getAllAreas();//getAllAreas
	           $data['selectEstacion'] = $this->M_areaEstacion->getAllEstac();//getAllAreas
               
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');	
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');	               
        	   $permisos =  $this->session->userdata('permisosArbol');
        	   #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_MANTENIMIENTO, ID_PERMISO_HIJO_AREA_ESTACION_CRI);
        	   $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_MANTENIMIENTO_NUEVO_MODELO, ID_PERMISO_HIJO_AREA_ESTACION_CRI, ID_MODULO_MANTENIMIENTO);
        	   $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	       $this->load->view('vf_areaEstacion/v_areaEstacion',$data);
        	   }else{
        	       redirect('login','refresh');
	           }
	   }else{
	       redirect('login','refresh');
	   }
	}
   
	public function makeHTMLTablaEstacionArea($listartabla){
	    
	    $html = '
        <table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>Estacion</th>
                            <th>Area</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>';
	    
	    foreach($listartabla->result() as $row){
	        
	        $html .=' <tr>
							<td>'.$row->estacionDesc.'</td>
                            <td>'.$row->areaDesc.'</td>
                            <td><a data-id_estacionArea="'.$row->idEstacionArea.'" onclick="editEstacionArea(this)"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/editar1.ico"></a></td>
						</tr>';//onclick="editCentral
	    }
	    $html .='</tbody>
                </table>';
	    
	    return utf8_decode($html);
	}
	
    
    public function makeHTMLTablaEstacion($listartabla){
     
        $html = '
        <table id="data-table2" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>Estacion</th>                            
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>';
		   																			                           
                foreach($listartabla->result() as $row){ 
                    
                $html .=' <tr>
							<td>'.$row->estacionDesc.'</td> 
                            <td><a data-id_estacion="'.$row->idEstacion.'" onclick="editEstacion(this)"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/editar1.ico"></a></td>                   
						</tr>';//onclick="editCentral
                 }
			 $html .='</tbody>
                </table>';
                    
        return utf8_decode($html);
    }
    
    public function makeHTMLTablaArea($listartabla){
        
        $html = '
        <table id="data-table3" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>Area</th>
                            <th>Tipo Area</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>';
        
        foreach($listartabla->result() as $row){
            
            $html .=' <tr>
							<td>'.$row->areaDesc.'</td>
                            <td>'.$row->tipoArea.'</td>
                            <td><a data-id_area="'.$row->idArea.'" onclick="editArea(this)"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/editar1.ico"></a></td>
						</tr>';//onclick="editCentral
        }
        $html .='</tbody>
                </table>';
        
        return utf8_decode($html);
    }
    
    
    function existeCodigoEstacion(){
        $codigoEstacion = $this->input->post('codigo');       // $codigoCentral('codigo')
       
        $cant = null;
        if($codigoEstacion!= null){
            $res  = $this->M_areaEstacion->existeEstaci($codigoEstacion); //>existeCentral($codigoCentral)
            $cant = $res->num_rows() == 1 ? ($res->row()->cant >= 1 ? '1' : '0') : '0';
        }else{
            $cant = '1';//Si hay un error que simule que si existe
        }
        echo $cant;
    }
    
    function existeCodigoArea(){
        $codigoArea = $this->input->post('codigo');       // $codigoCentral('codigo')
        
        $cant = null;
        if($codigoArea != null){
            $res  = $this->M_areaEstacion->existeArea($codigoArea); //>existeCentral($codigoCentral)
            $cant = $res->num_rows() == 1 ? ($res->row()->cant >= 1 ? '1' : '0') : '0';
        }else{
            $cant = '1';//Si hay un error que simule que si existe
        }
        echo $cant;
    }
      
    public function createEstacion(){ //createCentral
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{           
            $estacion = $this->input->post('inputEstacion');
           
            $data = $this->M_areaEstacion->insertarEstacion($estacion);
            if($data['error']==EXIT_ERROR){
                throw new Exception('Error al Insertar createEstacion');//createCentral
            }
           
            $data['listartablaEstacion'] = $this->makeHTMLTablaEstacion($this->M_areaEstacion->getAllEstac());//getAllCentrales

        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function createArea(){ //createCentral
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $area = $this->input->post('inputArea');
            $tipoArea = $this->input->post('selectTipoArea');
            
            $data = $this->M_areaEstacion->insertarArea($area, $tipoArea);
            if($data['error']==EXIT_ERROR){
                throw new Exception('Error al Insertar createEstacion');//createCentral
            }

            $data['listartablaArea'] = $this->makeHTMLTablaArea($this->M_areaEstacion->getAllAreas());//getAllCentrales
            
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    
    public function createEstacionArea(){ //createCentral
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $estacion = $this->input->post('selectEstacion');
            $area = $this->input->post('selectArea');
            
            $data = $this->M_areaEstacion->insertarEstacionArea($area, $estacion);
            if($data['error']==EXIT_ERROR){
                throw new Exception('Error al Insertar createEstacion');//createCentral
            }
            
            $data['listartablaEstacionArea'] = $this->makeHTMLTablaEstacionArea($this->M_areaEstacion->getAllEstacArea());//getAllCentrales
            
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }


    // editado 04/04/2018
   public function getInfoEstacion(){ //getInfoCentral
         
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $id = $this->input->post('id');
            $estacion = $this->M_areaEstacion->getEstacionInfo($id); //$central
            // nombre dque se pone   // nombre de la base de datos
            $data['estacion'] = $estacion['estacionDesc'];
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function editEstacion(){ //editarCentral
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            // nombre se pone       // tipo select o imput
            $estacionDesc = $this->input->post('inputEstacion2');
            $id = $this->input->post('id');
            $data = $this->M_areaEstacion->editEstacionModelo($id, $estacionDesc);
            if($data['error']==EXIT_ERROR){//editEstacionModelo
                throw new Exception('Error al Insertar createEstacion');//createCentral
            }
            $data['listartablaEstacion'] = $this->makeHTMLTablaEstacion($this->M_areaEstacion->getAllEstac()); //makeHTMLTablaCentral //getAllCentrales
    
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    //////////////////////AREA  ////////////

    // editado 04/04/2018
    public function getInfoArea(){ //se modifica aca el getInfo

        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $id = $this->input->post('id');
            $area = $this->M_areaEstacion->getAreaInfo($id); // se modifica aca en M_areaEstacion getAreaInfo es el nombre del modelo
            // nombre dque se pone   // nombre de la base de datos
            $data['area'] = $area['areaDesc'];
            $data['tipoArea'] = $area['tipoArea'];
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function editArea(){ //editarCentral
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            // nombre se pone
            // de la base de datos
            //  el campo $id siempre va     // tipo select o imput
            $areaDesc = $this->input->post('inputArea2');
            $tipoArea = $this->input->post('selectTipoArea2');
            $id = $this->input->post('id');
            $data = $this->M_areaEstacion->editarAreaModelo($id, $areaDesc, $tipoArea); // se pone los campos de la base datos
            if($data['error']==EXIT_ERROR){//editEstacionModelo
                throw new Exception('Error al Insertar createArea');//createCentral
            }
           // se cambia el listartablaArea
            $data['listarTablaArea'] = $this->makeHTMLTablaArea($this->M_areaEstacion->getAllAreas()); // se cambia el (getAllAreas) se pone la ruta correcta

        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    /////////////////////////////ESTACION y  AREA/////////////////

// HASTA ACA SE AVANZO
    // editado 04/04/2018
    public function getInfoEstacionArea(){ //se modifica aca

        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $id = $this->input->post('id');
            $areaEstacion = $this->M_areaEstacion->getEstacionAreaInfo($id); // se modifica aca en M_areaEstacion
            // nombre dque se pone   // nombre de la base de datos
            $data['idEstacion'] = $areaEstacion['idEstacion'];
            $data['idArea'] = $areaEstacion['idArea'];
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function editEstacionArea(){ //editarCentral
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            // nombre se pone
            // de la base de datos
            //  el campo $id siempre va     // tipo select o imput
            $idEstacion = $this->input->post('selectEstacion3');
            $idArea = $this->input->post('selectArea3');
            $id = $this->input->post('id');
            $data = $this->M_areaEstacion->editEstacionAreaModelo($id,$idEstacion, $idArea); // se pone los campos de la base datos
            if($data['error']==EXIT_ERROR){//editEstacionModelo
                throw new Exception('Error al Insertar ModificarAreaEstacion');//createCentral
            }
            $data['listarTablaEstacionArea'] = $this->makeHTMLTablaEstacionArea($this->M_areaEstacion->getAllEstacArea());// se cambia el (getAllAreas) se pone la ruta correcta


        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }




}