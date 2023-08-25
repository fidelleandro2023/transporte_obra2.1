<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_reporte_ba_vr extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_reportes_v/m_reporte_ba_vr');       
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }
    
    public function index(){
        $logedUser = $this->session->userdata('usernameSession');
        if($logedUser != null){
            //$data['listaEECC'] = $this->m_utils->getAllEECC();
            
            $data['tablaAsigGrafo'] = $this->makeHTLReporteBaVr(null);
            $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
            $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
            $permisos =  $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_REPORTES_V, ID_PERMISO_HIJO_REPORTE_BA_VR);
            $data['opciones'] = $result['html'];
            if($result['hasPermiso'] == true){
                $this->load->view('vf_reportes_v/v_reporte_ba_vr',$data);
            }else{
                redirect('login','refresh');
            }
        }else{
            redirect('login','refresh');
        }
    }
       
     public function makeHTLReporteBaVr($intervalDays, $fechaInicio, $fechaFin){
        if($intervalDays!=null){
            
            $listUsuarios = $this->m_reporte_ba_vr->getUSuariosAprobByInterval($fechaInicio, $fechaFin);
            $listUsuariosVR = $this->m_reporte_ba_vr->getUSuariosAprobByIntervalVR($fechaInicio, $fechaFin);
            $html = '<table style="font-size: 10px;" id="data-table" class="table table-bordered">
                        <thead class="thead-default">
                            <tr>
                                <th>Modulo</th>
                                <th>Usuario</th>
                                ';
            foreach($intervalDays->result() as $row){
                $html .= '<th>'.$row->dia.'</th>';
            }
             $html .= '</tr>
                        </thead>        
                        <tbody>';
             
        
            foreach($listUsuarios->result() as $row){
                $html .=' <tr>
                                <td>APROBACION</td>
    							<td>'.$row->usua_asig_grafo.'</td>';
                $aprobList = $this->m_reporte_ba_vr->getCantAprobByUsuarioAndInterval($fechaInicio, $fechaFin, $row->usua_asig_grafo);
                foreach($aprobList->result() as $row2){
                     $html .='<td>'.(($row2->total_aprob !=null) ? $row2->total_aprob : '0').'</td>';
                }                            
    			$html .='</tr>';
            }
            
            foreach($listUsuariosVR->result() as $row){
                $html .=' <tr>
                                <td>GESTION VR</td>
    							<td>'.$row->usuario.'</td>';
                $aprobList = $this->m_reporte_ba_vr->getCantAprobByUsuarioAndIntervalVR($fechaInicio, $fechaFin, $row->idUsuario);
                foreach($aprobList->result() as $row2){
                    $html .='<td>'.(($row2->total_aprob !=null) ? $row2->total_aprob : '0').'</td>';
                }
                $html .='</tr>';
            }
            $html .='</tbody>
                    </table>';
        }
        return utf8_decode($html);
    }
    
    public function filtrarTabla(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            
            //$eecc           = $this->input->post('eecc');
            $fechaInicio    = $this->input->post('fechaInicio');
            $fechaFin       = $this->input->post('fechaFin');
            //$itemplan       = $this->input->post('itemplan');            
            
            $data['tablaAsigGrafo'] = $this->makeHTLReporteBaVr($this->m_reporte_ba_vr->getDaysInterval($fechaInicio, $fechaFin), $fechaInicio, $fechaFin);
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }          
   
}