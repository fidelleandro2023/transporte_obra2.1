<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_diagnostico_pep_sisego extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_presupuesto/m_diagnostico_pep_sisego');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index(){
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
			/*
    	       $data['listaEECC']     = $this->m_utils->getAllEECC();
               $data['listaZonal']    = $this->m_utils->getAllZonalGroup();
               $data['cmbJefatura']   = $this->m_utils->getJefaturaCmb();
        	   $data['listaSubProy']  = $this->m_utils->getAllSubProyecto();
        	   $data['listafase']     = $this->m_utils->getAllFase();*/
               $data['tablaSiom']     = $this->getTablaHojaGestion(null);               
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
               $permisos =  $this->session->userdata('permisosArbol');
        	   $result = $this->lib_utils->getHTMLPermisos($permisos, 238, 258, ID_MODULO_ADMINISTRATIVO);
        	   $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	         $this->load->view('vf_presupuesto/v_diagnostico_pep_sisego',$data);
        	   }else{
        	       redirect('login','refresh');
        	   }
    	 }else{
        	 redirect('login','refresh');
	    }
             
    }
    
    function getTablaHojaGestion($listaHojaGestion) {
        
        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
							<th>Subproyecto</th>                            
							<th>Itemplan</th>
							<th>Sisego</th>
                            <th>EECC</th>
                            <th>Estado Plan</th>
                            <th>Fase</th>
							<th>Costo MAT</th>	
                            <th>Costo MO</th>
							<th>Costo TOTAL</th>
							<th>PEP 2</th>
							<th>Grafo</th>			
                            <th>SOLICITUD OC</th>
                            <th>SITUACION</th>
							<th>ORDEN COMPRA</th>
							<th>VALE RESERVA</th>
							<th>EJEC. DISENO</th>
                        </tr>
                    </thead>                    
                    <tbody>';
             if($listaHojaGestion != null){                                                                        
                foreach($listaHojaGestion as $row){
                    $html .=' <tr>                           
        							<td>'.$row->subProyectoDesc.'</td>							
        							<td>'.$row->itemplan.'</td>
									<td>'.$row->indicador.'</td>
                                    <td>'.$row->empresaColabDesc.'</td>
        							<td>'.$row->estadoPlanDesc.'</td>
        							<td>'.$row->faseDesc.'</td>                                    
        							<td>'.$row->costo_unitario_mat.'</td>
									<td>'.$row->costo_unitario_mo.'</td>
        							<td>'.$row->total.'</td>
                                    <td>'.$row->pep2.'</td>
                                    <td>'.$row->grafo.'</td>
        							<td>'.$row->solicitud_oc.'</td>
        							<td>'.$row->situacion.'</td>
									<td>'.$row->con_oc.'</td>
        							<td>'.$row->has_vr_aprob.'</td>
        							<td>'.$row->ejec_diseno.'</td> 									
                            </tr>';
                }
             }
            $html .='</tbody>
                </table>';
                    
            return $html;
    }
    
    function filtrarTabaDPS() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            $pep1      = ($this->input->post('pep1')=='')          ? null : $this->input->post('pep1');
            $data['dispoProy'] = $this->m_diagnostico_pep_sisego->getPepPresupuestoProy2($pep1);
            $data['tablaBandejaHG'] = $this->getTablaHojaGestion($this->m_diagnostico_pep_sisego->getBandejaPepSisego($pep1));
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
}