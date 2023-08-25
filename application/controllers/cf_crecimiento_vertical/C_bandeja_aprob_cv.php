<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_bandeja_aprob_cv extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_crecimiento_vertical/m_bandeja_aprob_cv');       
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }
    
    public function index(){
        $logedUser = $this->session->userdata('usernameSession');
        if($logedUser != null){
            $data['listaEECC'] = $this->m_utils->getAllEECC();
            $data['listaZonal'] = $this->m_utils->getAllZonal();
            $data['listaSubProy'] = $this->m_utils->getAllSubProyecto();
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_bandeja_aprob_cv->getAllCVPreRegistro());
            $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
            $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
            $permisos =  $this->session->userdata('permisosArbol');
            #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_CV, ID_PERMISO_BANDEJA_APROB_CV);
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_NUEVO_MODELO_CRECIMIENTO_VERTICAL, ID_PERMISO_BANDEJA_APROB_CV, ID_MODULO_PAQUETIZADO);
            $data['opciones'] = $result['html'];
            if($result['hasPermiso'] == true){
                $this->load->view('vf_crecimiento_vertical/v_bandeja_aprob_cv',$data);
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
                            <th>Nombre Proyecto</th>
                            <th>SubProyecto</th>
                            <th>Constructora</th>
                        </tr>
                    </thead>
                    
                    <tbody>';
        
        foreach($listaPTR->result() as $row){
            
            $html .=' <tr>
                            <th>'.(($row->estado_aprob == '0') ? '<a data-itemplan ="'.$row->itemplan.'"  onclick="aprobItemPlan(this)"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/circle-check-128.png"></a>': '' ).'</th>
							<td>'.$row->itemplan.'</td>							
							<td>'.$row->nombre_proyecto.'</td>
							<td>'.$row->subProyectoDesc.'</td>
							<td>'.$row->nombre_constructora.'</td>
						</tr>';
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
            $idEstadoPlan = $this->m_utils->getEstadoPlanByItemplan($itemplan);
            $data = $this->m_bandeja_aprob_cv->aprobarItemplan(ESTADO_CV_APROBADO, $itemplan, $idEstadoPlan);
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_bandeja_aprob_cv->getAllCVPreRegistro());
            
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    
}