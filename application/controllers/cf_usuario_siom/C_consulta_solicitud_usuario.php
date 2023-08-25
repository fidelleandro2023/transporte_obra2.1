<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_consulta_solicitud_usuario extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('mf_usuario_siom/m_bandeja_solicitud_usuario');
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
            #$result = $this->lib_utils->getHTMLPermisos($permisos, 144, 218);
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_CAP_WORKFLOW, 218, ID_MODULO_CAP);
            $data['opciones'] = $result['html'];
            
            $data['cmbContratos']    = __buildCmbContratosAll();
            $data['cmbZona']         = __buildCmbZona();
            $data['cmbPerfil']       = __buildCmbPerfil();
            $data['tabla']           = $this->getTablaBandeja(null, null, null);
            $this->load->view('vf_usuario_siom/v_consulta_solicitud_usuario', $data);
        } else {
            $this->session->sess_destroy();
            redirect('login', 'refresh');
        }
    }

    function getTablaBandeja($dni, $idEmpresaColab, $estado) {
        $idEcc     = $this->session->userdata('eeccSession');
        $data = $this->m_bandeja_solicitud_usuario->getBandejaSolicitudUsuario($dni, $idEmpresaColab, $estado, $idEcc);


        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>DNI</th>
                            <th>NOMBRE</th>
                            <th>IMEI</th>
                            <th>PEFIL</th>
                            <th>ZONA</th>
                            <th>EMAIL</th>
                            <th>TELEFONO</th>
                            <th>TIPO SOLICITUD</th>
                            <th>ESTADO</th>
							<th>USUARIO</th>
							<th>PASS</th>
							<th>OBSERVASI&Oacute;N</th>
                        </tr>
                    </thead>                    
                    <tbody>';
                                                                                                                                        
                foreach($data as $row){
                    $btnRechazar = null;
                    $btnAprobar  = null;

                    $html .=' <tr>
                                <td>'.utf8_decode($row['dni']).'</td>
                                <td>'.utf8_decode($row['nombre']).'</td>
                                <td>'.utf8_decode($row['imei']).'</td>
                                <td>'.utf8_decode($row['arrayPerfilDesc']).'</td>
                                <td>'.utf8_decode($row['arrayZonaDesc']).'</td>
                                <td>'.utf8_decode($row['email']).'</td>
                                <td>'.utf8_decode($row['telefono']).'</td>
                                <td>'.utf8_decode($row['flg_tipo_estado']).'</td>
                                <td>'.utf8_decode($row['estado']).'</td>
								<td>'.$row['usuario'].'</td>
								<td>'.$row['clave'].'</td>
								<td>'.$row['observacion_rechazo'].'</td>
                            </tr>';
                    }
                $html .='</tbody>
                    </table>';
                    
            return $html;
    }

    function filtrarTablaSolicitudUsuario() {
        $estado         = $this->input->post('estado');
        $dni            = $this->input->post('dni');
        $idEmpresaColab = $this->input->post('idEmpresaColab');

        $estado         = ($estado       == '') ? NULL : $estado;
        $dni            = ($dni == '') ? NULL : $dni;
        $idEmpresaColab = ($idEmpresaColab     == '') ? NULL : $idEmpresaColab;

        $data['tabla'] = $this->getTablaBandeja($dni, $idEmpresaColab, $estado);
        echo json_encode(array_map('utf8_encode', $data)); 
    }
}