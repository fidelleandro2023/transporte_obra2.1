<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * @author CARLOS ZAVALA C.
 * 18/01/2018
 *
 */
class C_bandeja_pre_aprob_diseno extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_liquidacion/m_liquidacion');
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
        	   $data['tablaAsigGrafo'] = $this->makeHTLMTablaAsignarGrafo($this->m_liquidacion->getPtrToLiquidacion('','','','SI','','','',FROM_BANDEJA_PRE_APROBACION_DISENO,''));	
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');	
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');	               
        	   $permisos =  $this->session->userdata('permisosArbol');
        	   #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_BANDEJAS, ID_PERMISO_HIJO_BANDEJA_PRE_APROB_DISENO);
        	   $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_NUEVO_MODELO_GESTION_INTEGRAL, ID_PERMISO_HIJO_BANDEJA_PRE_APROB_DISENO, ID_MODULO_PAQUETIZADO);
        	   $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	       $this->load->view('vf_liquidacion/v_bandeja_pre_aprob_diseno',$data);
        	   }else{
        	       redirect('login','refresh');
	           }
	   }else{
	       redirect('login','refresh');
	   }
    }
    
    public function updateTo01(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $ptr = $this->input->post('id_ptr');
            $grafo = $this->input->post('grafo');
            $itemplan = $this->input->post('itemplan');
            $idArea = $this->input->post('idArea');

            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception('Su sesi&oacute;n a expirado, cargue la p&acute;gina y vuelva a logearse.');
            }
            
            $arrayPtr = explode('-', $ptr);            
            if($arrayPtr[0] > 2018) {  
                if($idArea == null) {
                    throw new Exception('error, Area Null, comunicarse con el programador.');
                }
            }
            
            $data = $this->m_liquidacion->updatePtrTo01Diseno($ptr,$grafo, $this->fechaActual(), $itemplan, $idArea);
            
            $SubProy = $this->input->post('subProy');
            $eecc = $this->input->post('eecc');
            $zonal = $this->input->post('zonal');
            $itemPlan = $this->input->post('item');
            $mesEjec = $this->input->post('mes');
            $area = $this->input->post('area');      
            $ano= $this->input->post('ano');
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaAsignarGrafo($this->m_liquidacion->getPtrToLiquidacion($SubProy,$eecc,$zonal,$itemPlan,$mesEjec,$area,'',FROM_BANDEJA_PRE_APROBACION_DISENO,$ano));
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function makeHTLMTablaAsignarGrafo($listaPTR){
     
        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th></th>
                            <th>PTR</th>
                            <th>Item Plan</th>                            
                            <th>Monto Mat</th>
                            <th>Monto MO</th>
                            <th>Sub Proy</th>
                            <th>Zonal</th>
                            <th>EECC</th>
                            <th>Fec Sol.</th>
                            <th>Tpo. Esp</th>
                            <th>Mes. Previsto</th>
                            <th>A&ntilde;o. Previsto</th>
                            <th>Area</th>
                            <th>Estado</th>    
                        </tr>
                    </thead>                    
                    <tbody>';
		   																			                                                   
                foreach($listaPTR->result() as $row){              
                    
                $html .=' <tr>
                            <th><a data-ptr ="'.$row->ptr.'" data-grafo ="'.$row->grafo.'" data-itemplan="'.$row->itemPlan.'" 
                                   data-id_area="'.$row->idArea.'" onclick="asignarGrafo(this)"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/circle-check-128.png"></a></th>
							<td>'.$row->ptr.'</td>
							<td>'.$row->itemPlan.'</td>							
							<td>'.$row->valor_material.'</td>
							<td>'.$row->valor_m_o.'</td>
							<td>'.$row->subProy.'</td>
							<td>'.$row->zonal.'</td>
							<th>'.$row->eecc.'</th>
							<th>'.$row->fecSol.'</th> 
                            <th>'.substr($row->horas,0,-3).'</th> 
                            <th>'.$row->mesEjec.'</th>
                            <th>'.$row->ano_ejec.'</th>                                
                            <td>'.utf8_decode($row->area_desc).'</td>   
                            <td>'.substr($row->estado,0,3).'</td>
						</tr>';
                 }
			 $html .='</tbody>
                </table>';
                    
        return $html;
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
            $estado = $this->input->post('estado');
            $ano= $this->input->post('ano');
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaAsignarGrafo($this->m_liquidacion->getPtrToLiquidacion($SubProy,$eecc,$zonal,$itemPlan,$mesEjec,$area,$estado,FROM_BANDEJA_PRE_APROBACION_DISENO,$ano));
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function fechaActual()
    {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone', 'America/Lima');
        setlocale(LC_TIME, "es_ES", "esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }

}