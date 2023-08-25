<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_bandeja_espera_itemplan extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_pqt_plan_obra/m_bandeja_espera_itemplan');
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
               $data['tablaData']     = $this->getTablaHojaGestion($this->m_bandeja_espera_itemplan->getObrasToBandejaEspera());               
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
               $permisos =  $this->session->userdata('permisosArbol');
        	   $result = $this->lib_utils->getHTMLPermisos($permisos, 250, 293, ID_MODULO_ADMINISTRATIVO);
        	   $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	         $this->load->view('vf_pqt_plan_obra/v_bandeja_espera_itemplan',$data);
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
							<th>Itemplan</th>                            
							<th>Proyecto</th>
							<th>SubProyecto</th>
                            <th>EECC</th>
                            <th>Indicador</th>
                            <th>Fase</th>  
                            <th>Estado Plan</th>
                            <th>Jefatura</th>
							
                        </tr>
                    </thead>                    
                    <tbody>';
             if($listaHojaGestion != null){                                                                        
                foreach($listaHojaGestion as $row){
                    $html .=' <tr>                           
        							<td>'.$row->itemplan.'</td>							
        							<td>'.$row->proyectoDesc.'</td>
									<td>'.$row->subProyectoDesc.'</td>
								    <td>'.$row->empresaColabDesc.'</td>
								    <td>'.$row->indicador.'</td>
							        <td>'.$row->faseDesc.'</td>
                                    <td>'.$row->estadoPlanDesc.'</td>
        							<td>'.$row->jefatura.'</td>        							
                                    					
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
            $data['tablaBandejaHG'] = $this->getTablaHojaGestion($this->m_bandeja_espera_itemplan->getBandejaPepSisego($pep1));
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
}