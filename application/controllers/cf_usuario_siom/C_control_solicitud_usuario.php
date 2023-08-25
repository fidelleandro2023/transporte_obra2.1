<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_control_solicitud_usuario extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('mf_usuario_siom/m_control_solicitud_usuario');
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
            #$result = $this->lib_utils->getHTMLPermisos($permisos, 144, 217);
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_CAP_WORKFLOW, 217, ID_MODULO_CAP);
            $data['opciones'] = $result['html'];
            
            $data['tabla']           = $this->getTablaBandeja();
            $this->load->view('vf_usuario_siom/v_control_solicitud_usuario', $data);
        } else {
            $this->session->sess_destroy();
            redirect('login', 'refresh');
        }
    }

    function getTablaBandeja() {
        $idEcc = $this->session->userdata('eeccSession');
        $data = $this->m_control_solicitud_usuario->getDataTablaControlSolUSiom($idEcc);
        $cont=1;

        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>Nro</th>
							<th>PERFIL</th>
                            <th>Usuarios Activos</th>
                        </tr>
                    </thead>                    
                    <tbody>';
                                                                                                                                        
                foreach($data as $row){
                    $html .=' <tr>
                                <td>'.$cont.'</td>
                                <td>'.utf8_decode($row['desc_perfil']).'</td>
                                <td><a style="color:blue;"data-id_perfil="'.$row['id_perfil'].'" onclick="openModalUsuariosActivos($(this));">'.$row['usuariosAct'].'</a></td>
                            </tr>';
                    $cont++;
                }
                $html .='</tbody>
                    </table>';
                    
            return $html;
    }

    function getDataUsuarioSiomAct() {
        $id_perfil = $this->input->post('id_perfil');
        $tablaUsuario = $this->getTablaUsuarioDetalle($id_perfil);

        $data['tablaUsuario'] = $tablaUsuario;
        echo json_encode(array_map('utf8_encode', $data)); 
    }

    function getTablaUsuarioDetalle($idPerfil) {
        $idEcc = $this->session->userdata('eeccSession');
        $data = $this->m_control_solicitud_usuario->getDataTablaUsuariosActivos($idPerfil, $idEcc);
        $cont=1;

        $html = '<table id="data-table_detalle" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>Nro</th>
							<th>NOMBRE</th>
                            <th>USUARIO</th>
                            <th>EMAIL</th>
                            <th>CELULAR</th>
                            <th>IMEI</th>
                            <th>EECC</th>
                            <th>FECHA REGISTRO</th>
                        </tr>
                    </thead>                    
                    <tbody>';
                                                                                                                                        
                foreach($data as $row){
                    $html .=' <tr>
                                <td>'.$cont.'</td>
                                <td>'.utf8_decode($row['nombre']).'</td>
                                <td>'.utf8_decode($row['usuario']).'</td>
                                <td>'.utf8_decode($row['email']).'</td>
                                <td>'.utf8_decode($row['celular']).'</td>
                                <td>'.utf8_decode($row['imei']).'</td>
                                <td>'.utf8_decode($row['empresaColabDesc']).'</td>
                                <td>'.$row['fecha_registro'].'</td>
                            </tr>';
                    $cont++;
                }
                $html .='</tbody>
                    </table>';
                    
            return $html;
    }
}