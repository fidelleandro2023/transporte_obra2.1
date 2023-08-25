<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * @author GUSTAVO SEDANO L.
 * 17/09/2019
 *
 */
class C_en_aprobacion extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_pqt_plan_obra/m_pqt_en_aprobacion');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->library('excel');
        $this->load->helper('url');
    }
    
	public function index(){
	    log_message('error', 'Ingreso');
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
	           $itemplan = (isset($_GET['itemplan']) ? $_GET['itemplan'] : '');
               $data['tablaAsigGrafo'] = $this->makeHTLMTablaAsignarGrafo($this->m_pqt_en_aprobacion->getPtrToLiquidacion($itemplan));               
               $permisos =  $this->session->userdata('permisosArbol');
        	   $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_BANDEJAS, ID_PERMISO_HIJO_BANDEJA_APROB, ID_MODULO_GESTION_OBRA);
        	   log_message('error', 'salio result');
        	   $data['opciones'] = $result['html'];
        	   //if($result['hasPermiso'] == true){ 2019.11.05 se cayo en la validacion.. porque??
        	         $this->load->view('vf_pqt_gestion_obra_en_aprobacion/v_en_aprobacion',$data);
            /*}else{
        	       redirect('login','refresh');
        	   }*/
    	 }else{
        	 redirect('login','refresh');
	    }
             
    }
    
    public function makeHTLMTablaAsignarGrafo($listaPTR){
        
        $html = '<table class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>PTR</th>
                            <th>Estado</th>
                            <th>Itemplan</th>                            
                            <th>PEP 2</th>
                            <th>Grafo</th>
                            <th>Sub Proy</th>
                            <th>Zonal</th>
                            <th>EECC</th>
                            <th>Fase</th>
                            <th>PER</th>                            
                            <th>Area</th>  
                            <th>Valor MAT</th>
                            
                        </tr>
                    </thead>                    
                    <tbody>';
		   		$btnDownload = null;																	                                                   
                foreach($listaPTR->result() as $row){
                
                $html .=' <tr>
                            <td>'.$row->ptr.'</td>
                            <td>'.$row->estado.'</td>
							<td>'.$row->itemplan.'</td>					
							<td>'.$row->pep2.'</td>
							<td>'.$row->grafo.'</td>
							<td>'.$row->subProy.'</td>
							<td>'.$row->zonal.'</td>
							<th>'.$row->eecc.'</th>
							<th>'.$row->fase_desc.'</th>
							<th>'.$row->per.' - '.$row->indicador.'</th>
                            <td>'.utf8_decode($row->area_desc).'</td>
                            <td>' . (($row->valor_material != null && $row->valor_material != '-' && is_numeric($row->valor_material)) ? number_format($row->valor_material, 2, '.', ',') : '') . '</td>
						</tr>';
                 }
			 $html .='</tbody>
                </table>';
                    log_message('error', $html);
        return $html;
    }
    
    
}