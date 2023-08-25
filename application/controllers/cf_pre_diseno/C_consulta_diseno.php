<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 *
 */
class C_consulta_diseno extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_plan_obra/m_consulta');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }
    
	public function index(){
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
               //$data['listaItemplan'] = $this->m_utils->getAllItemplan();
               $data['listaNombres'] = $this->m_utils->getAllNombreDeProyectos();
    	       $data['listaEECC'] = $this->m_utils->getAllEECC();
               $data['listaNodos'] = $this->m_utils->getAllNodos();
               $data['listaProyectos'] = $this->m_utils->getAllProyecto();
               $data['listaEstados'] = $this->m_utils->getEstadosDiseno();
               $data['listaTipoPlanta'] = $this->m_utils->getAllTipoPlantal();
               
               // Trayendo zonas permitidas al usuario
               $zonas = $this->session->userdata('zonasSession');        
               $data['listaZonal'] = $this->m_consulta->getAllZonalIndex($zonas);
        	   $data['listaSubProy'] = $this->m_utils->getAllSubProyecto();
               $data['tablaAsigGrafo'] = '';
               $data['tablaAsigGrafo'] = $this->makeHTLMTablaConsulta('');
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
               $permisos =  $this->session->userdata('permisosArbol');
        	   #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_DISENO, ID_PERMISO_HIJO_CONSULTAS_DISENO);
               $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_NUEVO_MODELO_GESTION_INTEGRAL, ID_PERMISO_HIJO_CONSULTAS_DISENO, ID_MODULO_PAQUETIZADO);
               $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	       $this->load->view('vf_prediseno/v_consulta_diseno',$data);
        	   }else{
        	       redirect('login','refresh');
        	   }
    	 }else{

        	 redirect('login','refresh');
	    }
             
    }
    
    public function makeHTLMTablaConsulta($listaPTR){
     
        $html = '
                <table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>ITEMPLAN</th>
                            <th>SUBPROYECTO</th>                            
                            <th>NOMBRE</th>
                            <th>MDF/NODO</th>
                            <th>ZONAL</th>
                            <th>EECC</th>
                            <th>FEC. INICIO</th>
                            <th>FEC. PREV. EJECUCION</th>
                            <th>FEC EJEC.</th>
                            <th>ESTADO</th>
							<th>PAQUETIZADO</th>  
                            <th>ADELANTO FEC</th>                            
                        </tr>
                    </thead>
                    
                    <tbody>';
        if($listaPTR != ''){
            foreach($listaPTR->result() as $row){              
                    
                $html .=' 
                        <tr>
                                
                            
                            
                            <td><a style="color: blue;" onclick="changeEstadoPlan(this)" data-estado="'.$row->idEstadoPlan.'" data-fg_paquetizado="'.$row->paquetizado_fg.'" data-itmpl="'.$row->itemPlan.'">'.$row->itemPlan.'</a></td>
                            <td>'.$row->subProyectoDesc.'</td>  
                            <td>'.$row->nombreProyecto.'</td>
                            <td>'.$row->codigo.'-'.$row->tipoCentralDesc.'</td>
                            <td>'.$row->zonalDesc.'</td>
                            <td>'.$row->empresaColabDesc.'</td>
                            <th>'.$row->fechaInicio.'</th>
                            <th>'.$row->fechaPrevEjec.'</th> 
                            <th>'.$row->fechaEjecucion.'</th>
                            <th>'.$row->estadoPlanDesc.'</th> 
						    <th>'.$row->desc_paquetizado.'</th>							
                            <th style="text-align: center;'.(($row->hasAdelanto == '1') ? 'color: GREEN;font-size: large' : 'color: currentColor;font-size: initial ').';font-weight: bold;">'.(($row->hasAdelanto == '1') ? 'SI' : 'NO').'</th>           
                    
                        </tr>
                        ';
                 }
             $html .='</tbody>
                </table>';

        }else{
            $html .= '</tbody>
                </table>';
        }
		   																			                                                   
                
                    
        return utf8_decode($html);
    }
    
    function filtrarTabla(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{

            //$zonas = $this->session->userdata('zonasSession');
            $itemPlan = $this->input->post('itemplan');
            $nombreproyecto = $this->input->post('nombreproyecto');
            $nodo = $this->input->post('nodo');
            $zonal = $this->input->post('zonal');
            $proy = $this->input->post('proy');
            $subProy = $this->input->post('subProy');
            $estado = $this->input->post('estado');
            $tipoPlanta = $this->input->post('tipoPlanta');
            //$selectMesPrevEjec = $this->input->post('selectMesPrevEjec');
            $filtroPrevEjec = $this->input->post('filtroPrevEjec');

            $data['tablaAsigGrafo'] = $this->makeHTLMTablaConsulta($this->m_consulta->getPtrConsultaDiseno($itemPlan,$nombreproyecto,$nodo,$zonal,$proy,$subProy,$estado,$filtroPrevEjec,$tipoPlanta));
            
            $data['error']    = EXIT_SUCCESS;            
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function updateEstadoPlanDisenio(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $logedUser = $this->session->userdata('usernameSession');
			
			if(!$logedUser) {
				throw new Exception('SU SESION A EXPIRADO, CARGUE NUEVAMENTE');
			}
            //$zonas = $this->session->userdata('zonasSession');
            $itemPlan = $this->input->post('itemplan');
            $nombreproyecto = $this->input->post('nombreproyecto');
            $nodo = $this->input->post('nodo');
            $zonal = $this->input->post('zonal');
            $proy = $this->input->post('proy');
            $subProy = $this->input->post('subProy');
            $estado = $this->input->post('estado');
            $tipoPlanta = $this->input->post('tipoPlanta');
            //$selectMesPrevEjec = $this->input->post('selectMesPrevEjec');
            $filtroPrevEjec = $this->input->post('filtroPrevEjec');
            $itemSelect     = $this->input->post('itemSelect');
            $estadoSelect   = $this->input->post('estadoSelect');
            $idEstadoUpdate = '';
			
			
			$flg_paquetizado = $this->m_utils->getFlgPaquetizadoPo($itemSelect);
			if($flg_paquetizado == 2 || $flg_paquetizado == 1) {
				if($estadoSelect == ID_ESTADO_DISENIO || $estadoSelect == ID_ESTADO_EN_LICENCIA){
                    $idEstadoUpdate = ID_ESTADO_EN_APROBACION;
                    
                    // if($estadoSelect == ID_ESTADO_EN_LICENCIA) {
                        // $this->m_utils->deleteDataLicencia($itemSelect);
                    // }
					$countPO = $this->m_utils->getCountPo($itemSelect, NULL);
			
					if($countPO == 0) {
						throw new Exception('No tiene PO MAT');
					}
				} else if($estadoSelect == ID_ESTADO_EN_APROBACION) {
					$idEstadoUpdate = ESTADO_PLAN_EN_OBRA;
					
					$countPO = $this->m_utils->getCountPoSinAnclas($itemSelect);
			
					if($countPO == 0) {
						throw new Exception('No tiene PO MAT');
					}
				}
			} else {
				throw new Exception('No permite liquidar administrativamente, ya que es del antiguo modelo.');
				// if($estadoSelect == ID_ESTADO_DISENIO){
					// $idEstadoUpdate = ID_ESTADO_DISENIO_EJECUTADO;
				// }else if($estadoSelect == ID_ESTADO_DISENIO_EJECUTADO){
					// $idEstadoUpdate = ID_ESTADO_PLAN_EN_OBRA;
				// }else if($estadoSelect == ID_ESTADO_DISENIO_PARCIAL){
					// $idEstadoUpdate = ID_ESTADO_PLAN_EN_OBRA;
				// }
				
				// $countPO = $this->m_utils->getCountPo($itemSelect, NULL);
			
				// if($countPO == 0) {
					// throw new Exception('No tiene PO MAT');
				// }
			
			}
						
            $data = $this->m_consulta->changeEstadoPlanConsultaDiseno($itemSelect, $idEstadoUpdate, $flg_paquetizado);
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaConsulta($this->m_consulta->getPtrConsultaDiseno($itemPlan,$nombreproyecto,$nodo,$zonal,$proy,$subProy,$estado,$filtroPrevEjec,$tipoPlanta));
            
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    
    
}