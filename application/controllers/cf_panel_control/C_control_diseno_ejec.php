<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_control_diseno_ejec extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('mf_panel_control/m_control_diseno_ejec');
        $this->load->model('mf_utils/m_utils');

        $this->load->library('lib_utils');
        $this->load->helper('url');

    }

    function index() {
        $logedUser = $this->session->userdata('usernameSession');
        $idUsuario = $this->session->userdata('idPersonaSession');

        if ($logedUser != null) {
            $data['nombreUsuario'] =  $this->session->userdata('usernameSession');	
            $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
            $permisos =  $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, 1, 1);
            $data['opciones'] = $result['html'];
            $data['tablaControlTab2'] = $this->getTablaControlTab2();
            $data['tablaControlTab1'] = $this->getTablaControlTab1();

            $this->load->view('vf_panel_control/v_control_diseno_ejec', $data);
        } else {
            $this->session->sess_destroy();
            redirect('login', 'refresh');
        }
    }

    function getTablaControlTab1() {
        $data = $this->m_control_diseno_ejec->getTablaControlTab1();


        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>ACCI&Oacute;N</th>
                            <th>PROYECTO</th>
                            <th>ESTACI&Oacute;N</th>
                            <th>TOTAL</th>
                            <th>ESTADO</th>
                        </tr>
                    </thead>                    
                    <tbody>';
                                                                                                                                        
                foreach($data as $row){
                    $btnAprobar  = '<i style="color:#A4A4A4;cursor:pointer" data-id_proyecto="'.$row['idProyecto'].'"
                                        data-id_estacion="'.$row['idEstacion'].'" class="zmdi zmdi-hc-2x zmdi-assignment" 
                                        title="Detalle" onclick="openModalDetalleTab1($(this));"></i>';
                    
                   

                    $html .=' <tr>
                                <td>'.$btnAprobar.'</td>
                                <td>'.utf8_decode($row['proyectoDesc']).'</td>
                                <td>'.$row['estacionDesc'].'</td>
                                <td>'.$row['total'].'</td>
                                <td>'.$row['estadoPlanDesc'].'</td>
                            </tr>';
                    }
                $html .='</tbody>
                    </table>';
                    
            return $html;
    }

    function getTablaDetalleTab1() {
        $idProyecto = $this->input->post('idProyecto');
        $idEstacion = $this->input->post('idEstacion');

        $data['tablaDetalleTab1'] = $this->getDetalleModalTab1($idProyecto, $idEstacion);

        echo json_encode(array_map('utf8_encode', $data)); 
    }

    function getDetalleModalTab1($idProyecto, $idEstacion) {
        $data = $this->m_control_diseno_ejec->getDetalleModalTab1($idProyecto, $idEstacion);


        $html = '<table id="tabla_detalle_1" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>ITEMPLAN</th>
							<th>SUBPROYECTO</th>
                            <th>ESTACI&Oacute;N</th>
                            <th>FECHA ADJUDICACI&Oacute;N</th>
                            <th>FECHA PREVISTA ATENCI&Oacute;N</th>
                            <th>USUARIO ADJUDICACI&Oacute;N</th>
                        </tr>
                    </thead>                    
                    <tbody>';
                                                                                                                                        
                foreach($data as $row){
                    $html .=' <tr>
                                <td>'.$row['itemplan'].'</td>
								<td>'.$row['subProyectoDesc'].'</td>
                                <td>'.$row['estacionDesc'].'</td>
                                <td>'.$row['fecha_adjudicacion'].'</td>
                                <td>'.$row['fecha_prevista_atencion'].'</td>
                                <td>'.$row['usuario_adjudicacion'].'</td>
                            </tr>';
                    }
                $html .='</tbody>
                    </table>';
                    
            return $html;
    }

    
    function getTablaControlTab2() {
        $data = $this->m_control_diseno_ejec->getTablaControlTab2();


        $html = '<table id="data-table2" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>ITEMPLAN</th>
                            <th>SUBPROYECTO</th>
                            <th>ESTADO</th>
                            <th>FECHA CREACION</th>
                        </tr>
                    </thead>                    
                    <tbody>';
                                                                                                                                        
                foreach($data as $row){

                    $html .=' <tr>
                                <td>'.$row['itemplan'].'</td>
                                <td>'.$row['subProyectoDesc'].'</td>
                                <td>'.$row['estadoPlanDesc'].'</td>
                                <td>'.$row['fecha_creacion'].'</td>
                            </tr>';
                    }
                $html .='</tbody>
                    </table>';
                    
            return $html;
    }
}