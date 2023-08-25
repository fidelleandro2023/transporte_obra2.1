<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_mantenimiento_motivo_siom extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_mantenimiento/m_mantenimiento_motivo_siom');
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
            #$result = $this->lib_utils->getHTMLPermisos($permisos, 24, 225);
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_MANTENIMIENTO_NUEVO_MODELO, 225, ID_MODULO_MANTENIMIENTO);
            $data['opciones'] = $result['html'];   
            $data['tablaMotivoSiom']           = $this->getTablaMotivoSiom();
            $this->load->view('vf_mantenimiento/v_mantenimiento_motivo_siom', $data);
        } else {
            $this->session->sess_destroy();
            redirect('login', 'refresh');
        }
    }

    function getTablaMotivoSiom() {
        $data = $this->m_mantenimiento_motivo_siom->getDataMotivoSiom();

        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>ACCI&Oacute;N</th>
                            <th>MOTIVO</th>
                            <th>TIPO</th>
                            <th>ESTADO</th>
                            <th>FECHA REGISTRO</th>
                        </tr>
                    </thead>                    
                    <tbody>';
                                                                                                                                        
                foreach($data as $row){
                    $btnEditar = '<a><i style="color:#A4A4A4;cursor:pointer" data-estado="2"
                                        class="zmdi zmdi-hc-2x zmdi-edit" data-id_motivo_siom="'.$row['id'].'" title="Editar" onclick="openModalEditar($(this));"></i></a>';
                    
                

                    $html .=' <tr>
                                <td>'.$btnEditar.'</td>
                                <td>'.utf8_decode($row['descripcion']).'</td>
                                <td>'.utf8_decode($row['tipo']).'</td>
                                <td>'.utf8_decode($row['estado']).'</td>
                                <td>'.$row['fecha_registro'].'</td>
                            </tr>';
                    }
                $html .='</tbody>
                    </table>';
        
        return $html;
    }

    function registrarMotivo() {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try {
            $namMotivo = $this->input->post('nomMotivo');
            $flgTipo   = $this->input->post('flgTipo');

            $idUsuario = $this->session->userdata('idPersonaSession');
            if($idUsuario == null || $idUsuario == '') {
                 throw new Exception('La sesi&oacute;n espir&oacute;, recargue la p&aacute;gina.');   
            }
            $arrayData =   array(
                                    'descripcion'    => $namMotivo,
                                    'flg_tipo'       => implode(",", $flgTipo),
                                    'fecha_registro' => $this->m_utils->fechaActual(),
                                    'id_usuario_reg' => $idUsuario,
                                    'estado'         => 1
                                );
            $data = $this->m_mantenimiento_motivo_siom->insertMotivo($arrayData);
            $data['tablaMotivoSiom'] = $this->getTablaMotivoSiom();
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));

    }

    function getEditMotivoSiom() {
        $idMotivoMantenimiento = $this->input->post('idMotivoMantenimiento');

        $arrayData = $this->m_mantenimiento_motivo_siom->getDataMotivoMantSiom($idMotivoMantenimiento);

        $data['arrayData'] = $arrayData;
        echo json_encode($data);
    }

    function actualizarMotivoSiom() {
        try {
            $idMotivoMantenimiento = $this->input->post('idMotivoMantenimiento');
            $estado                = $this->input->post('estado');
            $tipo                  = $this->input->post('tipo');
            $nomMotivo             = $this->input->post('nomMotivo');

            $idUsuario = $this->session->userdata('idPersonaSession');
            if($idUsuario == null || $idUsuario == '') {
                 throw new Exception('La sesi&oacute;n espir&oacute;, recargue la p&aacute;gina.');   
            }

            $dataUpdate =  array(
                                    'estado'          => $estado,
                                    'flg_tipo'        => implode(",", $tipo),
                                    'descripcion'     => $nomMotivo,
                                    'id_usuario_edit' => $idUsuario,
                                    'fecha_edit'      => $this->m_utils->fechaActual()
                                );
            $data = $this->m_mantenimiento_motivo_siom->actualizarMotivoSiom($idMotivoMantenimiento, $dataUpdate);                    
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
}