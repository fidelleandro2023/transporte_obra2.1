<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * @author CARLOS ZAVALA C.
 * 18/01/2018
 *
 */
class C_gestionar_po extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_mantenimiento/m_gestionar_po');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }
    
	public function index()
	{  	   
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
        	  
	           $listaItemplan = $this->m_gestionar_po->getItemplanList('','');        	   
               $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo(null);	
               $data['itemplanList'] = $listaItemplan;
               $data['listaEstaciones'] = $this->m_utils->getAllEstacionNoDiseno();
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');	
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');	               
        	   $permisos =  $this->session->userdata('permisosArbol');
        	   $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_PLAN_DE_OBRA, ID_PERMISO_HIJO_GESTIONAR_PO);
        	   $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	       $this->load->view('vf_mantenimiento/v_gestionar_po',$data);
        	   }else{
        	       redirect('login','refresh');
	           }
	   }else{
	       redirect('login','refresh');
	   }
    }
    
    public function makeHTLMTablaBandejaAprobMo($listaPTR){     
        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th style="width:10%"></th>                            
                            <th>Item Plan</th>        
                            <th>Proyecto</thj>  
                            <th>Sub Proyecto</th>
                            <th>Jefatura</th>
                            <th>Region</th>        
                            <th>Estado Plan</th>
                            <th>% Prom.</th>
                        </tr>
                    </thead>
                   
                    <tbody>';
            if($listaPTR!=null){																                                                   
                foreach($listaPTR->result() as $row){        
                    
                $html .=' <tr>
                         <td>'.(($this->canEdit($this->session->userdata('idPerfilSession'))) ? '<a data-itemplan="'.$row->itemPlan.'" onclick="editEstado(this)"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/editar.ico"></a>
                                &nbsp;&nbsp;&nbsp;&nbsp': '').'
                                <a data-itemplan="'.$row->itemPlan.'" data-jefatura="'.$row->jefatura.'" onclick="editPorcentaje(this)"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/porcentaje.png"></a></td>
							<td>'.$row->itemPlan.'</td>	
							<td>'.$row->nombreProyecto.'</td>	
                            <td>'.$row->subProyectoDesc.'</td>	
                            <td>'.$row->jefatura.'</td>		
                            <td>'.$row->region.'</td>								
                            <td>'.$row->estadoPlanDesc.'</td>   
                            <td>'.(($row->porcentaje=='') ? '0%': $row->porcentaje.'%').'</td>		
						</tr>';
                    }  
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
            $itemPlan = $this->input->post('itemplanFil');        
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_gestionar_po->getItemplanList($itemPlan,$SubProy));
            $data['error']    = EXIT_SUCCESS;            
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }   

    function canEdit($perfiles){
        $edit = '0';
        $listaPerfil = explode(",", $perfiles);
        foreach($listaPerfil as $row){           
            if($row == '3' || $row == '4' || $row == '10' || $row == '9'){//TELEFONICA O ADMINISTRADOR
                $edit = '1';
            }
        }
        return $edit;
    }
    
    function getInfoItemPlan(){        
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $itemplan = $this->input->post('itemplan');
            $infoItem = $this->m_gestionar_po->getInfoItemplanToEdit($itemplan);
            $data['itemplan'] = $infoItem['itemplan'];
            $data['nombreProyecto'] = $infoItem['nombreProyecto'];
            $data['fechaInicio'] = $infoItem['fechaInicio'];
            $data['fechaPrevEjec'] = $infoItem['fechaPrevEjec'];
            $data['idEstadoPlan'] = $infoItem['idEstadoPlan'];
            $data['subProyectoDesc'] = $infoItem['subProyectoDesc'];
            $data['empresaColabDesc'] = $infoItem['empresaColabDesc'];
            $data['hasAdelanto'] = $infoItem['hasAdelanto'];
            $data['fechaEjec'] = $infoItem['fechaEjecucion'];
            $data['fechaCan'] = $infoItem['fechaCancelacion'];
            $data['estadosList'] = $this->getHTMLChoiceEstados($this->m_utils->getAllEstadosPlan($itemplan));
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getPorcentajeEstacion(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $itemplan = $this->input->post('itemplan');
            $listaEstacion = $this->m_gestionar_po->getEstacionPorcentajeByItemPlan($itemplan)->result();
            $data['listaEstaPor'] = json_encode($listaEstacion);      
            $data['htmlEstaciones'] = $this->makeHtmlContChoice($listaEstacion);
            $data['encode'] = base64_encode($this->session->userdata('idUsuarioSinfix').'|'.$itemplan);
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function makeHtmlContChoice($listaEstaciones){
        $html='';
        foreach($listaEstaciones as $row){
        $html .= ' 	<div class="col-sm-6 col-md-6">
                     	<div class="form-group">
                                    <label>'.$row->estacionDesc.'</label>
                                    <select id="selectEstacion'.$row->idEstacion.'" name="selectEstacion'.$row->idEstacion.'" class="select2  form-control" >
                                        '.(($row->porcentaje <= 0) ? '<option value="0">0%</option>' : '').'
                                        '.(($row->porcentaje <= 25) ? '<option value="25">25%</option>' : '').'
                                        '.(($row->porcentaje <= 50) ? '<option value="50">50%</option>' : '').'
                                        '.(($row->porcentaje <= 75) ? '<option value="75">75%</option>' : '').'
                                        '.(($row->porcentaje <= 100) ? '<option value="100">100%</option>' : '').'
                                        '.(($row->porcentaje <= 0) ? '<option value="NR">NO REQUIERE</option>' : '').'
                                    </select>
                        </div>
                    </div>';
        }
        
        return $html;
    }
    
     function getHTMLChoiceEstados($listaEstados){
            $html = '';
            foreach($listaEstados->result() as $row){
                $html .= '<option value="'.$row->idEstadoPlan.'">'.utf8_decode($row->estadoPlanDesc).'</option>';
            }
           return $html;
    }
    
    function changueEstadoPlan(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $itemplan = $this->input->post('itemplan');
            $idEstadoPlan = $this->input->post('selectEstaItem');
            $hasAdelanto = $this->input->post('selectAdelanto');
            $fecEjecucion = $this->input->post('inputFecEjec');
            $fecTermino = $this->input->post('inputFecTerm');
            $data =  $this->m_gestionar_po->changeEstadoPlan($itemplan,$idEstadoPlan,$hasAdelanto,$fecEjecucion,$fecTermino);        
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_gestionar_po->getItemplanList($itemplan,''));
          
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function savePorcentajeEstacion(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $itemplan = $this->input->post('itemplan');
            $listaEstacion = $this->m_gestionar_po->getEstacionPorcentajeByItemPlan($itemplan);
            $arrayInsert =  array();
            $arrayUpdate = array();
            foreach($listaEstacion->result() as $row){
                $datatrans = array();
                if($row->idItemplanEstacion == NULL){                                        
                    $datatrans['porcentaje'] = $this->input->post('selectEstacion'.$row->idEstacion);
                    $datatrans['idEstacion'] = $row->idEstacion;
                    $datatrans['itemplan'] = $row->itemplan;
                    array_push($arrayInsert, $datatrans);
                }else{                    
                    $datatrans['idItemplanEstacion'] = $row->idItemplanEstacion;
                    $datatrans['porcentaje'] = $this->input->post('selectEstacion'.$row->idEstacion);
                    array_push($arrayUpdate, $datatrans);
                }
            }
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_gestionar_po->getItemplanList($itemplan,''));
            
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    
}