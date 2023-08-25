<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 *
 */
class C_bandeja_pendientes extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_plantaInterna/M_bandeja_pendientes');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }
    
	public function index(){
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
               $data['listaItemplan'] = $this->m_utils->getAllItemplan();
               $data['listaNombres'] = $this->m_utils->getAllNombreDeProyectos();
    	       $data['listaEECC'] = $this->m_utils->getAllEECC();
               $data['listaNodos'] = $this->m_utils->getAllNodos();
               $data['listaProyectos'] = $this->m_utils->getAllProyecto();
               $data['listaEstados'] = $this->m_utils->getEstadosItemplan();
               $data['listaTipoPlanta'] = $this->m_utils->getAllTipoPlantal();
               $data['listaSubProy'] = $this->m_utils->getAllSubProyecto();
               // Trayendo zonas permitidas al usuario
               $zonas = $this->session->userdata('zonasSession');


               //$data['tablaPrincipal'] = '';  con esto inicializas la tabla vacia
               $data['tablaPrincipal'] = $this->makeHTLMTablaPrincipal($this->M_bandeja_pendientes->getTablePrincipal(''));

               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
               $permisos =  $this->session->userdata('permisosArbolTransporte');
        	   #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_PLANTA_INTERNA, ID_PERMISO_HIJO_BANDEJA_PENDIENTE_PIN);
               $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_PLANTA_INTERNA, ID_PERMISO_HIJO_BANDEJA_PENDIENTE_PIN, ID_MODULO_PAQUETIZADO);
               $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	         $this->load->view('vf_plantaInterna/V_bandeja_pendientes',$data);
        	   }else{
        	       redirect('login','refresh');
        	   }
    	 }else{
        	 redirect('login','refresh');
	    }
             
    }

    public function makeHTLMTablaPrincipal($listaPTR){

        $html = '
                <table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            
                            <th>ITEMPLAN</th>
                            <th>PTR</th>
                            <th>SUBPROYECTO</th>                            
                            <th>MDF/NODO</th>
                            <th>ZONAL</th>
                            <th>EECC</th>
                            <th>FEC EJEC.</th>
                            <th>COSTO MAT</th>
                            <th>COSTO MO</th>
                            <th>TOTAL</th>
                            <th>ESTADO</th>  
                                                        
                        </tr>
                    </thead>
                    
                    <tbody>';
        if($listaPTR != ''){
            foreach($listaPTR->result() as $row){              
                    
                $html .=' 
                        <tr>
                            
							<td>'.$row->itemplan.'</td>
							<td>'.$row->ptr.'</td>
							<td>'.$row->subProyectoDesc.'</td>
							<td>'.$row->codigo.'-'.$row->tipoCentralDesc.'</td>
							<td>'.$row->zonalDesc.'</td>
							<th>'.$row->empresaColabDesc.'</th>
							<th>'.$row->fechaPrevEjec.'</th>                              
                            <td>'.(($row->costo_mat!=null) ? number_format($row->costo_mat, 2, '.', ',') : '' ).'</td>
                            <td>'.(($row->costo_mo!=null) ? number_format($row->costo_mo, 2, '.', ',') : '' ).'</td>
                            <td>'.(($row->total!=null) ? number_format($row->total, 2, '.', ',') : '' ).'</td>
                            <td>'.$row->estado.'</td>
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

            $data['tablaAsigGrafo'] = $this->makeHTLMTablaConsulta($this->M_bandeja_pendientes->getPtrConsulta($itemPlan,$nombreproyecto,$nodo,$zonal,$proy,$subProy,$estado,$filtroPrevEjec,$tipoPlanta));

            $data['error']    = EXIT_SUCCESS;            
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

}