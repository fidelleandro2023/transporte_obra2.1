<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * @author CARLOS ZAVALA C.
 * 18/01/2018
 *
 */
class C_itemplan_ptr extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_reportes_v/m_itemplan_ptr');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }
    
	public function index(){
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
    	       $data['listaEECC'] = $this->m_utils->getAllEECC();
        	   $data['listaZonal'] = $this->m_utils->getAllZonal();
        	   $data['listaSubProy'] = $this->m_utils->getAllSubProyecto();
              
               $data['tablaAsigGrafo'] = $this->makeHTLMTablaItemPtr($this->m_itemplan_ptr->getWebUnificadaFa('','','',''));
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
               $permisos =  $this->session->userdata('permisosArbol');
        	   $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_REPORTES_V, ID_PERMISO_HIJO_REPORTE_V);
        	   $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	         $this->load->view('vf_reportes_v/v_itemplan_ptr',$data);
        	   }else{
        	       redirect('login','refresh');
        	   }
    	 }else{
        	 redirect('login','refresh');
	    }
             
    }
    
    public function getColorFromEstado($data, $est){
            
        foreach($data->result() as $row){
            $estados = explode(",", $row->rangoPoDesc);
            if (in_array(trim(substr($est,0,3)), $estados, true)) {//ARRAY 1
                return $row->colorPo;
            }                    
        }         
      return '#ffffff';
    }
    
    public function makeHTLMTablaItemPtr($listaPTR){
        $data = $this->m_itemplan_ptr->getRangoPtr();
       $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>                           
                            <th>ITEM PLAN</th>
                            <th>CENTRAL</th>
                            <th>INDICADOR</th>
                            <th>ZONAL</th>                            
                            <th>EECC</th>
                            <th>MAT_COAX</th>
                            <th>MAT_COAX_OC</th>
                            <th>MAT_FUENTE</th>
                            <th>MAT_FO</th>
                            <th>MAT_FO_OC</th>
                            <th>MAT_ENER</th>                          
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ITEM PLAN</th>
                            <th>CENTRAL</th>
                            <th>INDICADOR</th>
                            <th>ZONAL</th>                            
                            <th>EECC</th>
                            <th>MAT_COAX</th>
                            <th>MAT_COAX_OC</th>
                            <th>MAT_FUENTE</th>
                            <th>MAT_FO</th>
                            <th>MAT_FO_OC</th>
                            <th>MAT_ENER</th>           
                        </tr>
                    </tfoot>
                    <tbody>';
		   																			                                                   
                foreach($listaPTR->result() as $row){              
                    
                $html .=' <tr>
                            <th>'.$row->itemPlan.'</th>
                            <td>'.$row->cod_central.'</td>
                            <td>'.$row->indicador.'</td>
							<td>'.$row->zonal.'</td>
							<td>'.$row->eecc.'</td>			
							<td style="background-color:'.$this->getColorFromEstado($data, $row->mat_coax_est).'; color:white">'.$row->mat_coax_ptr.'</td>				
							<td style="background-color:'.$this->getColorFromEstado($data, $row->mat_coax_oc_est).'; color:white">'.$row->mat_coax_oc_ptr.'</td>
							<td style="background-color:'.$this->getColorFromEstado($data, $row->mat_fuente_est).'; color:white">'.$row->mat_fuente_ptr.'</td>
							<td style="background-color:'.$this->getColorFromEstado($data, $row->mat_fo_est).'; color:white">'.$row->mat_fo_ptr.'</td>							
							<th style="background-color:'.$this->getColorFromEstado($data, $row->mat_fo_oc_est).'; color:white">'.$row->mat_fo_oc_ptr.'</th>
							<th style="background-color:'.$this->getColorFromEstado($data, $row->mat_ener_est).'; color:white">'.$row->mat_ener_ptr.'</th>                            
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
            $mesEjec = $this->input->post('mes');    
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaItemPtr($this->m_itemplan_ptr->getWebUnificadaFa($SubProy,$eecc,$zonal,$mesEjec));
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

}