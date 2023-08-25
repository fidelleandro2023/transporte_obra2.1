<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *
 *
 */
class C_ManLog extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_mantenimiento/M_manLog');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index(){
        $logedUser = $this->session->userdata('usernameSession');
        if($logedUser != null){
            //$data['listaitemplan'] = $this->m_utils->getAllitemplan();
//            $data['listaNombres'] = $this->m_utils->getAllNombreDeProyectos();
//            $data['listaEECC'] = $this->m_utils->getAllEECC();
//            $data['listaNodos'] = $this->m_utils->getAllNodos();
//            $data['listaProyectos'] = $this->m_utils->getAllProyecto();
//            $data['listaEstados'] = $this->m_utils->getEstadositemplan();
//            $data['listaTipoPlanta'] = $this->m_utils->getAllTipoPlantal();

            // Trayendo zonas permitidas al usuario
//            $zonas = $this->session->userdata('zonasSession');
//            $data['listaZonal'] = $this->M_ManLog->getAllZonalIndex($zonas);
//            $data['listaSubProy'] = $this->m_utils->getAllSubProyecto();
            $data['tablaAsigGrafo'] = '';
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaLog('');
            $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
            $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
            $permisos =  $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_PLAN_DE_OBRA, ID_PERMISO_HIJO_CONSULTAS);
            $data['opciones'] = $result['html'];
            //if($result['hasPermiso'] == true){
            $this->load->view('vf_mantenimiento/v_manLog',$data);
            //}else{
            //  redirect('login','refresh');
            //}
        }else{
            redirect('login','refresh');
        }

    }

    public function asignarExpediente(){
        $logedUser = $this->session->userdata('usernameSession');

        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{

            $jsonptr = $this->input->post('jsonptr');
            $comentario = $this->input->post('comentario');

            $arrayPTRItem = json_decode($jsonptr, true);
            $data = $this->M_manLog->insertExpediente($comentario,$logedUser);


            foreach ($arrayPTRItem as $row) {
                $subrows = explode("%", $row);
                $ptr = $subrows[0];
                $item = (($subrows[1] != null) ? $subrows[1] : null);
                $fecsol = $subrows[2];
                $subproyecto = $subrows[3];
                $zonal = $subrows[4];
                $eecc = $subrows[5];
                $area = $subrows[6];

                //aquir recibir en una variable la wu.f_ult_est para enviar al insert()
                $this->M_ManLog->insertPTR($ptr,$item,$fecsol,$subproyecto,$zonal,$eecc,$area);

            }
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function makeHTLMTablaLog($itemplan){

        $html = '
                <table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th style="background-color: #0154a0; color: white">itemplan</th>
                            <th style="background-color: #0154a0 ; color: white">PTR</th>
                            <th style="background-color: #0154a0 ; color: white">TABLA AFECTADA</th>
                            <th style="background-color: #0154a0 ; color: white">ACCION REALIZADA</th>
                            <th style="background-color: #0154a0 ; color: white">FEC. REGISTRO</th>
                            <th style="background-color: #0154a0 ; color: white">FEC. MODIFICACION</th>
                            <th style="background-color: #0154a0 ; color: white">NOMBRE USUARIO</th>
                            <th style="background-color: #0154a0 ; color: white">PERFIL</th>
                            <th style="background-color: #0154a0 ; color: white">PERMISO DEL USUARIO</th>  
                                                 
                        </tr>
                    </thead>
                    
                    <tbody>';
        if($itemplan != ''){
            foreach($itemplan->result() as $row){
                $html .=' 
                        <tr>
                            <td>'.$row->itemplan.'</td>
                            <td>'.$row->ptr.'</td>  
                            <td>'.$row->tabla.'</td>
                            <td>'.$row->actividad.'</td>
                            <td>'.$row->fecha_registro.'</td>
                            <td>'.$row->fecha_modificacion.'</td>
                            <th>'.$row->nombre.'</th>
                            <th>'.$row->desc_perfil.'</th> 
                            <th>'.$row->nombre_usuario_tipo.'</th>                            
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

    function getDataTableItemPlanLog(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{

            //$zonas = $this->session->userdata('zonasSession');
            $itemplan = $this->input->post('itemplan');
            $ptr= $this->input->post('ptr');
            $reg_fechaini  = $this->input->post('reg_fechaini');
            $reg_fechafin  = $this->input->post('reg_fechafin');
           /* $ptr = $this->input->post('ptr');
            $tabla = $this->input->post('tabla');
            $zonal = $this->input->post('zonal');
            $proy = $this->input->post('proy');
            $subProy = $this->input->post('subProy');
            $estado = $this->input->post('estado');
            $tipoPlanta = $this->input->post('tipoPlanta');
            //$selectMesPrevEjec = $this->input->post('selectMesPrevEjec');
            $filtroPrevEjec = $this->input->post('filtroPrevEjec');*/

            //$estado = $this->input->post('estado');

            $data['tablaAsigGrafo'] = $this->makeHTLMTablaLog($this->M_manLog->getItemplanLog($itemplan,$ptr,$reg_fechaini,$reg_fechafin));

            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

}