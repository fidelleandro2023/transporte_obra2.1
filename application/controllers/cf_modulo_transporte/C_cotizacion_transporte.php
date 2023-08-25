<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 *
 */
class C_cotizacion_transporte extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        // $this->load->model('mf_plan_obra/m_consulta');
        $this->load->model('mf_transporte/m_cotizacion_transporte');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_log/m_log_ingfix');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    function index(){
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
               //$data['listaItemplan'] = $this->m_utils->getAllItemplan();
               $data['listaNombres'] = $this->m_utils->getAllNombreDeProyectos();
    	       $data['listaEECC'] = $this->m_utils->getAllEECC();
               $data['listaNodos'] = $this->m_utils->getAllNodos();
               $data['listaProyectos'] = $this->m_utils->getAllProyecto();
               $data['listaEstados'] = $this->m_utils->getEstadosItemplan();
               $data['listaTipoPlanta'] = $this->m_utils->getAllTipoPlantal();
               $data['listUsuarios'] = $this->m_utils->getUsuarioRegistroItemplanPIN();
               // Trayendo zonas permitidas al usuario
               $zonas = $this->session->userdata('zonasSession');   
                $idEECC = $this->session->userdata("eeccSession");

                if ($idEECC == null || $idEECC == '') {
                    redirect('login', 'refresh');
                }
        	   $data['listaZonal']   = $this->m_utils->getAllZonalGroup();               
        	   $data['listaSubProy'] = $this->m_utils->getAllSubProyecto();
               $data['title'] = 'BANDEJA COTIZACI&Oacute;N';
               $data['tablaAsigGrafo'] = $this->makeHTLMTablaConsulta($this->m_cotizacion_transporte->getPtrConsulta('','','','','','',ID_ESTADO_PRE_DISENIO,'',ID_TIPO_PLANTA_INTERNA, $idEECC, null, null, null));
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
               $permisos =  $this->session->userdata('permisosArbol');
        	   #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_PLANTA_INTERNA, ID_PERMISO_HIJO_BANDEJA_COTIZACION_PLANTA_INTERNA);
               $result = $this->lib_utils->getHTMLPermisos($permisos, 309, 311, 8);
               $data['opciones'] = $result['html'];
        	
        	         $this->load->view('vf_modulo_transporte/v_cotizacion_transporte',$data);
        	
    	 }else{

        	 redirect('login','refresh');
	    }
             
    }

    public function makeHTLMTablaConsulta($listaPTR){
        $html = '
                <table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                        <th ></th>
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
                            <th>ADELANTO FEC</th>
                        </tr>
                    </thead>
                    
                    <tbody>';
        if($listaPTR != ''){
            foreach($listaPTR->result() as $row){              
                $html .=' 
                        <tr>
                                                            
                            <td>'.(($row->idProyecto == ID_PROYECTO_SISEGOS && $row->grafo != null) ? '<a href="http://200.48.131.32/obras2/general/estudio_itemplan.php?itemplan='.$row->itemPlan.'" target="_blank"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/iconview.svg">
                                <a data-itemplan ="'.$row->itemPlan.'" onclick="zipItemPlan($(this));"><img alt="Ver Log" height="20px" width="20px" src="public/img/iconos/expediente.png">' : '').'</a></td> 
                            <td><a href="'.'getDetallePoTransporte?item='.$row->itemPlan.'&from=1'.'" target="_blank">'.$row->itemPlan.'</a></td>
                            <td>'.$row->subProyectoDesc.'</td>  
                            <td>'.$row->nombreProyecto.'</td>
                            <td>'.$row->codigo.'-'.$row->tipoCentralDesc.'</td>
                            <td>'.$row->zonalDesc.'</td>
                            <td>'.$row->empresaColabDesc.'</td>
                            <th>'.$row->fechaInicio.'</th>
                            <th>'.$row->fechaPrevEjec.'</th> 
                            <th>'.$row->fechaEjecucion.'</th>
                            <th>'.$row->estadoPlanDesc.'</th>     
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

    function filtrarTablaCotizacion(){
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
            $idUsuario = !isset($idUsuario) ? NULL : $idUsuario;
            
            $query = $this->m_cotizacion_transporte->getPtrConsulta($itemPlan,'','',$zonal,'',$SubProy,ID_ESTADO_PRE_DISENIO,'',ID_TIPO_PLANTA_INTERNA,$eecc, $idUsuario, $mesEjec);
            $tabla = $this->makeHTLMTablaConsulta($query);
            
            $data['tablaAsigGrafo'] = $tabla;
            $data['error'] = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
}