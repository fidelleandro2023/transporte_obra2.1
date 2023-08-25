<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * @author CARLOS ZAVALA C.
 * 18/01/2018
 *
 */
class C_bandeja_pre_aprob extends CI_Controller {

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
               $data['listafase'] = $this->m_utils->getAllFase();
               $data['tablaAsigGrafo'] = $this->makeHTLMTablaAsignarGrafo($this->m_liquidacion->getPtrToLiquidacion('','','','SI','','','',FROM_BANDEJA_PRE_APROBACION,'','',1));	
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');	
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');	               
        	   $permisos =  $this->session->userdata('permisosArbol');
        	   #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_BANDEJAS, ID_PERMISO_HIJO_BANDEJA_PRE_APROB);
        	   $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_NUEVO_MODELO_GESTION_VR, ID_PERMISO_HIJO_BANDEJA_PRE_APROB, ID_MODULO_PAQUETIZADO);
        	   $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	       $this->load->view('vf_liquidacion/v_bandeja_pre_aprob',$data);
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
            $ptr = $this->input->post('id_ptr') ? $this->input->post('id_ptr') : null;
            $grafo = $this->input->post('grafo') ? $this->input->post('grafo') : null;
            $itemplanPO = $this->input->post('ipPO') ? $this->input->post('ipPO') : null;
            $origen = $this->input->post('origen') ? $this->input->post('origen') : null;
            
            if($origen ==   1){//web_unificada
                $data = $this->m_liquidacion->updatePtrTo01($ptr,$grafo);
            }else if($origen ==   2){//web po
                $arrayUpdate = array(
                    "estado_po" => 2
                );
                $fechaActual = $this->fechaActual();
                $data = $this->m_liquidacion->updatePOTo2($ptr,$itemplanPO,$arrayUpdate,$fechaActual);
            }
            
            /**05.07.2019 czavala, despuest de pre aprobar la po aplicarle presupuesto**/
            $data = $this->m_utils->execGetGrafosOnePtr($ptr);
            /********************************************************/
            
            $SubProy = $this->input->post('subProy');
            $eecc = $this->input->post('eecc');
            $zonal = $this->input->post('zonal');
            $itemPlan = $this->input->post('item');
            $mesEjec = $this->input->post('mes');
            $area = $this->input->post('area');    
            $ano= $this->input->post('ano');
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaAsignarGrafo($this->m_liquidacion->getPtrToLiquidacion($SubProy,$eecc,$zonal,$itemPlan,$mesEjec,$area,'',FROM_BANDEJA_PRE_APROBACION, $ano,'',1));
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
                            <th>PO</th>
                            <th>Item Plan</th>                            
                            <th>Monto Mat</th>
                            <th>Monto MO</th>
                            <th>Sub Proy</th>
                            <th>Zonal</th>
                            <th>EECC</th>
                            <th>FASE</th>
                            <th>Fec Sol.</th>
                            <th>Tpo. Esp</th>
                            <th>Mes. Previsto</th>
                            <th>AÃ±o. Previsto</th>
                            <th>Area</th>  
                            <TH>Tipo</th>
                            <TH>Estado</th>  
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th>PO</th>
                            <th>Item Plan</th>                            
                            <th>Monto Mat</th>
                            <th>Monto MO</th>
                            <th>Sub Proy</th>
                            <th>Zonal</th>
                            <th>EECC</th>
                            <th>FASE</th>
                            <th>Fec Sol.</th>
                            <th>Tpo. Esp</th>
                            <th>Mes. Previsto</th>
                            <th>Año Previsto</th>
                            <th>Area</th>
                            <th>Estado</th>                            
                        </tr>
                    </tfoot>
                    <tbody>';
		   																			                                                   
                foreach($listaPTR->result() as $row){              
                    
                $html .=' <tr>
                            <th><a data-origen="'.$row->origen.'" data-ptr ="'.$row->ptr.'" data-grafo ="'.$row->grafo.'" data-itemplan ="'.$row->itemPlan.'" onclick="asignarGrafo(this)"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/circle-check-128.png"></a></th>
							<td>'.$row->ptr.'</td>
							<td'.(($row->esta_validada == 1) ? ' style="color: red;"' : '').'>'.$row->itemPlan.'</td>					
							<td>'.$row->valor_material.'</td>
							<td>'.$row->valor_m_o.'</td>
							<td>'.$row->subProy.'</td>
							<td>'.$row->zonal.'</td>
                            <th>'.$row->eecc.'</th>
                            <th>'.$row->fase_desc.'</th>
							<th>'.$row->fecSol.'</th>
                            <th>'.substr($row->horas,0,-3).'</th>
                            <th>'.$row->mesEjec.'</th>
                            <th>'.$row->ano_ejec.'</th>             
                            <td>'.utf8_decode($row->area_desc).'</td>
                            <td>'.utf8_decode($row->desc_area).'</td>
                            <td>'.($row->grafo != '-' ? substr($row->estado,0,3) : $row->estado).'</td>
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
            $idFase = $this->input->post('idFase'); 
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaAsignarGrafo($this->m_liquidacion->getPtrToLiquidacion($SubProy,$eecc,$zonal,$itemPlan,$mesEjec,$area,$estado,FROM_BANDEJA_PRE_APROBACION, $ano, $idFase,1));
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