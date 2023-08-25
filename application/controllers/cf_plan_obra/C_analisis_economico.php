<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_analisis_economico extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_plan_obra/m_analisis_economico');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index(){
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
            $itemplan = (isset($_GET['itemplan']) ? $_GET['itemplan'] : '');
            
			
			$idProyecto = $this->m_utils->getProyectoByItemplan($itemplan);
			
			$idEstadoPlan = $this->m_utils->getEstadoPlanByItemplan($itemplan);
			$data['tablaAnalisisSisego'] = null;
			if($idEstadoPlan == 5) {//CERRADO
				$data['tablaAnalisisEconomico'] = $this->getTablaAnalisisEconomicoCerrado($itemplan);
			} else {
				$data['tablaAnalisisEconomico'] = $this->getTablaAnalisisEconomico($itemplan);
				if($idProyecto == 3) {
					$data['tablaAnalisisSisego'] = $this->getTablaAnalisisEconomicoSisego($itemplan);
				}
			}
			
			
            $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
            $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
            $permisos =  $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_PLAN_DE_OBRA, ID_PERMISO_HIJO_CONSULTAS);
            $data['opciones'] = $result['html'];
            if($result['hasPermiso'] == true){
                    $this->load->view('vf_plan_obra/v_analisis_economico',$data);
            }else{
                redirect('login','refresh');
            }
    	 }else{
        	 redirect('login','refresh');
	    }
             
    }

    function getTablaAnalisisEconByItemplan() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            $itemplan   = $this->input->post('itemplan');

            if($itemplan == null) {
                throw new Exception('ND');
            }

            $data['tablaAnalisisEconomico'] = $this->getTablaAnalisisEconomico($itemplan);
            $data['error'] = EXIT_SUCCESS;
        } catch(Exceptio $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function getTablaAnalisisEconomico($itemplan) {
        $sumMat = 0;
        $sumMo  = 0;
        $data = $this->m_analisis_economico->getDataTablaAnalisis($itemplan);
        $total = $this->m_analisis_economico->getTotalAnalisis($itemplan);
        $html = '<div style="color:red">                                    
                    TOTAL : '.$total.'
                </div>
                <table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>ITEMPLAN</th>
                            <th>PO</th>
                            <th>ESTACI&Oacute;N</th>
                            <th>TIPO</th>
                            <th>ESTADO</th>                            
                            <th>PEP2</th>
                            <th>GRAFO</th>
                            <th>TOTAL MAT</th>
                            <th>TOTAL MO</th>
                        </tr>
                    </thead>                    
                    <tbody>';
                                                                                                                                        
                foreach($data as $row){              
                $html .=' <tr>
                            <td>'.$itemplan.'</td>
                            <td>'.$row->codigo_po.'</td>
                            <td>'.utf8_decode($row->estacionDesc).'</td>
                            <td>'.$row->tipoArea.'</td>					
                            <td>'.$row->estado.'</td>
                            <td>'.$row->pep2.'</td>
                            <td>'.$row->grafo.'</td>
                            <td>'.$row->total_mat.'</td>
                            <td>'.$row->total_mo.'</td>
                        </tr>';
                        if($row->estado != 'Cancelado') {
                            $sumMat = $row->total_mat + $sumMat;
                            $sumMo  = $row->total_mo + $sumMo;
                        }
                }
                $html .=' <tr>
                            <td colspan="7">TOTAL</td>
                            <td>'.$sumMat.'</td>
                            <td>'.$sumMo.'</td>
                        </tr>';
            $html .='</tbody>
                </table>';
                    
            return $html;
    }
	
	function getTablaAnalisisEconomicoSisego($itemplan) {
        $data = $this->m_analisis_economico->getDataAnalisisSisego($itemplan);
        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>ITEMPLAN</th>
                            <th>SISEGO</th>
                            <th>PEP2</th>
                            <th>GRAFO</th>
							<th>MONTO DISPONIBLE</th>
							<th>COSTO TOTAL OBRA</th>
							<th>TIPO</th>							
                            <th>COSTO</th>
							<th>ESTADO PRESUP.</th>
							<th>ESTADO GESTI&Oacute;N</th>
							<th>PARALIZADO</th>
							<th>MOTIVO</th>
                        </tr>
                    </thead>                    
                    <tbody>';
                                                                                                                                        
                foreach($data as $row){              
                $html .=' <tr>
                            <td>'.$itemplan.'</td>
                            <td>'.$row->indicador.'</td>
                            <td>'.$row->pep2.'</td>				
                            <td>'.$row->grafo.'</td>
							<td>'.$row->monto_disponible.'</td>
							<td>'.$row->costo_total_obra.'</td>
							<td>'.$row->tipo_area.'</td>
                            <td>'.$row->costo_unitario.'</td>
							<td>'.$row->estado_presu.'</td>
							<td>'.$row->estado_gestion.'</td>
							<td>'.$row->paralizado.'</td>
							<td>'.$row->motivoDesc.'</td>
                        </tr>';
                }
            $html .='</tbody>
                </table>';
                    
            return $html;
    }
	
    function getTablaAnalisisEconomicoCerrado($itemplan) {
        $sumMat = 0;
        $sumMo  = 0;
        $data = $this->m_analisis_economico->getDataTablaItemCerrado($itemplan);
        $total = $this->m_analisis_economico->getTotalAnalisis($itemplan);
        $html = '<div style="color:red">                                    
                    TOTAL : '.$total.'
                </div>
                <table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>ITEMPLAN</th>
                            <th>TOTAL MAT</th>
                            <th>TOTAL MO</th>
                        </tr>
                    </thead>                    
                    <tbody>';
                                                                                                                                        
                foreach($data as $row){              
                $html .=' <tr>
                            <td>'.$itemplan.'</td>
                            <td>'.$row->total_mat.'</td>
                            <td>'.$row->total_mo.'</td>
                        </tr>';
                }
            $html .='</tbody>
                </table>';
                    
            return $html;
    }
	
    function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone','America/Lima');
        setlocale(LC_TIME, "es_ES","esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }
}