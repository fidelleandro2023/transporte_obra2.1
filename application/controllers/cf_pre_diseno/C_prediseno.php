<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * @author CARLOS ZAVALA C.
 * 18/01/2018
 *
 */
class C_prediseno extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_pre_diseno/m_prediseno');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }
    
	public function index()
	{  	   
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
        	   $data['listaEECC'] = $this->m_utils->getAllEECC();
        	   $data['listaZonal'] = $this->m_utils->getAllZonal();
        	   $data['listaSubProy'] = $this->m_utils->getAllSubProyecto();
               $data['tablaPreDiseno'] = $this->makeHTLMTablaAsignarGrafo($this->m_prediseno->getPtrToLiquidacion('','','','SI','','',ESTADO_PRE_APROB));	
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');	
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');	               
        	   $permisos =  $this->session->userdata('permisosArbol');
        	   $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_PLAN_DE_OBRA, ID_PERMISO_HIJO_BANDEJA_PRE_APROB);
        	   $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	       $this->load->view('vf_prediseno/v_prediseno',$data);
        	   }else{
        	       redirect('login','refresh');
	           }
	   }else{
	       redirect('login','refresh');
	   }
    }
    /*
    public function updateTo01(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $ptr = $this->input->post('id_ptr');
            $grafo = $this->input->post('grafo');
            $data = $this->m_prediseno->updatePtrTo01($ptr,$grafo);
            
            $SubProy = $this->input->post('subProy');
            $eecc = $this->input->post('eecc');
            $zonal = $this->input->post('zonal');
            $itemPlan = $this->input->post('item');
            $mesEjec = $this->input->post('mes');
            $area = $this->input->post('area');            
            $data['tablaPreDiseno'] = $this->makeHTLMTablaAsignarGrafo($this->m_prediseno->getPtrToLiquidacion($SubProy,$eecc,$zonal,$itemPlan,$mesEjec,$area,ESTADO_PRE_APROB));
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }*/
    
    public function makeHTLMTablaAsignarGrafo($listaPTR){
     
        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th></th>
                            <th>Itemplan</th>
                            <th>Area</th>                            
                            <th>...</th>
                            <th>...</th>
                            <th>Sub Proy</th>
                            <th>Zonal</th>
                            <th>EECC</th>
                            <th>Mes Previsto Ejec.</th>
                            <th>Fecha adjudicación</th>
                            <th>...</th>
                            <th>...</th>    
                        </tr>
                    </thead>
                    
                    <tbody>';
		   																			                                                   
                foreach($listaPTR->result() as $row){              
                    
                $html .=' <tr>
                            <th><button class="btn btn-info btn--icon-text waves-effect" data-toggle="modal" data-target="#modal-adjudicar"><i class="zmdi zmdi-check"></i>ADJUDICAR</button></th>
							<td>'.$row->itemPlan.'</td>
							<td>'.$row->areaDesc.'</td>							
							<td>'.$row->tipoArea.'</td>
							<td>...</td>
							<td>'.$row->subProyectoDesc.'</td>
							<td>'.$row->estado.'</td>
							<th>'.$row->mesPrevEjec.'</th>
							 
                            <th>...</th>
                            <th>...--</th> 
                            <th>...</th>                              
                            <td>...</td>   
						</tr>';
                 }
			 $html .='</tbody>
                </table>';
                    
        return utf8_decode($html);
    }
    
    function filtrarTabla(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $SubProy = $this->input->post('subProy');
            $eecc = $this->input->post('eecc');
            $zonal = $this->input->post('zonal');
            $itemPlan = $this->input->post('item');
            $mesEjec = $this->input->post('mes');
            $area = $this->input->post('area');           
            $data['tablaPreDiseno'] = $this->makeHTLMTablaAsignarGrafo($this->m_prediseno->getPtrToLiquidacion($SubProy,$eecc,$zonal,$itemPlan,$mesEjec,$area,ESTADO_PRE_APROB));
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function adjudicar(){
        _log('Entro a adjudicar():');
    }

}