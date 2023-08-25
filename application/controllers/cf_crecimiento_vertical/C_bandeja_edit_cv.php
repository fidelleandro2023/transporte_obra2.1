<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_bandeja_edit_cv extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_crecimiento_vertical/m_bandeja_edit_cv');       
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }
    
    public function index(){
        $logedUser = $this->session->userdata('usernameSession');
        if($logedUser != null){
            $data['listaMotivos'] = $this->m_utils->getMotivoAllByOrigen('0');//FROM CANCELAR WEB PO
            $data['listaEstadoPlan'] = $this->m_bandeja_edit_cv->getEstadosItemplan();
            //$data['listaSubProy'] = $this->m_utils->getAllSubProyecto();
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo(null);
            $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
            $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
            $permisos =  $this->session->userdata('permisosArbol');
            #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_CV, ID_PERMISO_HIJO_BANDEJA_EDIT);
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_NUEVO_MODELO_CRECIMIENTO_VERTICAL, ID_PERMISO_HIJO_BANDEJA_EDIT, ID_MODULO_PAQUETIZADO);
            $data['opciones'] = $result['html'];
            if($result['hasPermiso'] == true){
                $this->load->view('vf_crecimiento_vertical/v_bandeja_edit_cv',$data);
            }else{
                redirect('login','refresh');
            }
        }else{
            redirect('login','refresh');
        }             
    }
    
    public function makeHTLMTablaBandejaAprobMo($listaPTR){
        
        $html = '<table style="font-size: 10px;" id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th></th>
                            <th>Itemplan</th>
                            <th>Fase</th>
                            <th>Estado</th>
                            <th>Nombre Proyecto</th>
                            <th>SubProyecto</th>
                            <th>Operador</th>
                            <th>Constructora</th>
                            <th></th>
                        </tr>
                    </thead>
                    
                    <tbody>';
        if($listaPTR!=null){
            foreach($listaPTR->result() as $row){
                 // if(in_array($row->idSubProyecto, array(96,97,98,99))) { //RECIDENCIAL Y NEGOCIO 1
                //     $ubicHref = base_url().'editCV?item='.$row->itemplan;
                // } else { //INTEGRAL
                    $ubicHref = base_url().'editCV2?item='.$row->itemplan;
                // }
                $html .=' <tr>
                                <th><a data-itemplan ="'.$row->itemplan.'"  href="'.$ubicHref.'"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/editar.ico"></a></th>
    							<td>'.$row->itemplan.'</td>
    							<td>'.$row->fase_desc.'</td>
    							<td>'.$row->estadoPlanDesc.'</td>
    							<td>'.$row->nombre_proyecto.'</td>
    							<td>'.$row->subProyectoDesc.'</td>
    							<td>'.$row->operador.'</td>
    							<td>'.$row->nombre_constructora.'</td>	
    							<th>'.(($row->idEstadoPlan   !=  ID_ESTADO_CANCELADO) ? '<a data-itemplan ="'.$row->itemplan.'"  onclick="cancelItemplan(this)"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/cancelar_equis.png"></a>' : '').'</th>
    						</tr>';
            }
        }
        $html .='</tbody>
                </table>';
        
        return utf8_decode($html);
    }
    
    public function aprobarCV(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            
            $itemplan = $this->input->post('itemplan');
            $data = $this->m_bandeja_edit_cv->aprobarItemplan(ESTADO_CV_APROBADO, $itemplan);
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_bandeja_edit_cv->getAllCVPreRegistro());
            
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function cancelarItemplanCV(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
    
            $itemplan   = $this->input->post('itemplan');
            $comentario = $this->input->post('coment');
            $idMotivo   = $this->input->post('motivo');            
            /**filtros**/
            $itemplanFil = $this->input->post('itemplanFil');
            $nomProyecyo = $this->input->post('nomProy');
            $estadoPlan  = $this->input->post('estadoPlan');
            $operador    = $this->input->post('operador');
            
            $idEstadoPlan = $this->m_utils->getEstadoPlanByItemplan($itemplan);           
            
            $data = $this->m_bandeja_edit_cv->cancelItemplanCV($itemplan, $comentario, $idMotivo, $idEstadoPlan);
            if($itemplanFil==null && $nomProyecyo==null && $estadoPlan==null && $operador==null){
                $data['tablaCVResidencial'] = $this->makeHTLMTablaBandejaAprobMo(null);
            }else{
                $data['tablaCVResidencial'] = $this->makeHTLMTablaBandejaAprobMo($this->m_bandeja_edit_cv->getAllCVPreRegistro($itemplanFil, $nomProyecyo, $estadoPlan, $operador));
            }    
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function filtrarTabla(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $itemplan    = $this->input->post('itemplan');
            $nomProyecyo = $this->input->post('nomProy');
            $estadoPlan  = $this->input->post('estadoPlan');
            $operador    = $this->input->post('operador');
            if($itemplan==null && $nomProyecyo==null && $estadoPlan==null && $operador==null){
                $data['tablaCVResidencial'] = $this->makeHTLMTablaBandejaAprobMo(null);
            }else{
                $data['tablaCVResidencial'] = $this->makeHTLMTablaBandejaAprobMo($this->m_bandeja_edit_cv->getAllCVPreRegistro($itemplan, $nomProyecyo, $estadoPlan, $operador));
            }
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
   
}