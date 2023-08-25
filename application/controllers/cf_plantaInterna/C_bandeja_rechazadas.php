<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * @author CRISTOBAL ARTETA .
 * 18/01/2018
 *
 */
class C_bandeja_rechazadas extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_plantaInterna/M_aprobacion_interna');
        $this->load->model('mf_reportes_v/m_itemplan_ptr');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index(){
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
    	       $data['listaEECC']    = $this->m_utils->getAllEECC();
        	   $data['listaZonal']   = $this->m_utils->getAllZonalGroup();
        	   $data['listaSubProy'] = $this->m_utils->getAllSubProyecto(ID_TIPO_PLANTA_INTERNA);
               $data['listUsuarios'] = $this->m_utils->getUsuarioRegistroItemplanPIN();
               $data['tablaasigGrafoInterna'] = $this->makeHTLMTablaasignarGrafoInterna($this->M_aprobacion_interna->getPtrToLiquidacion('','','','SI','','','',NULL,'02'));

               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
               $data['title'] = 'BANDEJA DE PTR RECHAZADAS';
               $permisos =  $this->session->userdata('permisosArbolTransporte');
        	   #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_PLANTA_INTERNA, ID_PERMISO_HIJO_BANDEJA_RECHAZADAS);
               $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_PLANTA_INTERNA, ID_PERMISO_HIJO_BANDEJA_RECHAZADAS, ID_MODULO_PAQUETIZADO);
               $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	         $this->load->view('vf_plantaInterna/V_bandeja_rechazadas',$data);
        	   }else{
        	       redirect('login','refresh');
        	   }
    	 }else{
        	 redirect('login','refresh');
	    }
             
    }

    public function makeHTLMTablaasignarGrafoInterna($listaPTR){
     
        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>Acción</th>
                            <th>PTR</th>
                            <th>Item Plan</th>                            
                            <th>Sub Proy</th>
                            <th>Zonal</th>
                            <th>EECC</th>
                            <th>DES. Area</th>
                            <th>Fec. Prevista</th>
                            <th>Valor MAT</th>
                            <th>Valor MO</th>
                            <th>TOTAL</th>
                            <th>Estado</th>                            
                        </tr>
                    </thead>                    
                    <tbody>';

    foreach($listaPTR->result() as $row){
        $btnCheck = null;
        $btnEdit     = '<a data-ptr ="'.$row->ptr.'" data-item ="'.$row->itemplan.'" data-flg_rechazado="'.$row->flg_rechazado.'" title="editar PTR"  onclick="modificarPTRPI(this)"><i class="zmdi zmdi-hc-2x zmdi-border-color"></i></a>';
        $btnConsulta = '<a data-ptr ="'.$row->ptr.'" data-itemplan ="'.$row->itemplan.'" onclick="consultarCotizacionPtr($(this))" title="cotizaci&oacute;n"><i style="color:green" class="zmdi zmdi-hc-2x zmdi-money-box"></i></a>'; 
        $html .=' <tr>
                    <td>'.$btnCheck.' '.$btnConsulta.' '.$btnEdit.'</td>
                    <td>'.$row->ptr.'</td>
                    <td>'.$row->itemplan.'</td>							
                    <td>'.$row->subProyectoDesc.'</td>
                    <td>'.$row->zonalDesc.'</td>
                    <th>'.$row->empresaColabDesc.'</th>
                    <th>'.$row->areaDesc.'</th>							
                    <th>'.$row->fechaPrevEjec.'</th>                              
                    <td>'.(($row->costo_mat!=null) ? number_format($row->costo_mat, 2, '.', ',') : '' ).'</td>
                    <td>'.(($row->costo_mo!=null) ? number_format($row->costo_mo, 2, '.', ',') : '' ).'</td>
                    <td>'.(($row->total!=null) ? number_format($row->total, 2, '.', ',') : '' ).'</td>
                    <td>'.$row->estado.'</td>
                </tr>';
                 }
        $html .='</tbody>
        </table>';
                    
        return utf8_decode($html);
    }

    function filtrarTablaRechazadas() {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $SubProy   = $this->input->post('subProy');
            $eecc      = $this->input->post('eecc');
            $zonal     = $this->input->post('zonal');
            $itemPlan  = $this->input->post('item');
            $mesEjec   = $this->input->post('mes');
            $area      = $this->input->post('area');
            $estado    = $this->input->post('estado');
            $idUsuario = $this->input->post('idUsuario');
            $idUsuario = isset($idUsuario) ? $idUsuario : NULL;
            
            $data['tablaasigGrafoInterna'] = $this->makeHTLMTablaasignarGrafoInterna($this->M_aprobacion_interna->getPtrToLiquidacion($SubProy,$eecc,$zonal,$itemPlan,$mesEjec,$area,$estado, $idUsuario, '02'));
            $data['error'] = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
}
