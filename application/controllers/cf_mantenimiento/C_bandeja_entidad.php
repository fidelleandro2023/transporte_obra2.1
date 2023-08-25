<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_bandeja_entidad extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index()
    {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {
            // Trayendo zonas permitidas al usuario
            $zonas = $this->session->userdata('zonasSession');

            $idPersonaSession = $this->session->userdata('idPersonaSession');
            $descPerfilSesion = $this->session->userdata('descPerfilSession');

            $data['tablaEntidad'] = $this->makeHTLMTablaConsulta($this->m_utils->getAllEntidades());
            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $permisos = $this->session->userdata('permisosArbol');
            #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_MANTENIMIENTO, ID_PERMISO_HIJO_MANT_ENTIDAD);
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_MANTENIMIENTO_NUEVO_MODELO, ID_PERMISO_HIJO_MANT_ENTIDAD, ID_MODULO_MANTENIMIENTO);
            $data['opciones'] = $result['html'];
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_mantenimiento/v_bandeja_entidad', $data);
            } else {
                redirect('login', 'refresh');
            }
        } else {

            redirect('login', 'refresh');
        }

    }

    public function registrarEntidad()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $descEntidad = $this->input->post('descripEntidad') ? $this->input->post('descripEntidad') : null;
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;

            $this->db->trans_begin();

            if ($idUsuario == null) {
                throw new Exception('Su sesion ha experiado, ingrese nuevamente!!');
            }

            if ($descEntidad == null || $descEntidad == '') {
                throw new Exception('Debe ingresar una descripcion para registrar la entidad!!');
            }

            $flgExistEnt = $this->m_utils->countEntidad($descEntidad);

            $arrayInsertGlob = array();

            if ($flgExistEnt == 0) {

                $arrayInsertEnt = array(
                    "desc_entidad" => strtoupper(utf8_decode($descEntidad)),
                    "fecha_registro" => $this->m_utils->fechaActual(),
                );

                $data = $this->m_utils->insertarEntidad($arrayInsertEnt);

                if ($data['error'] == EXIT_SUCCESS) {

                    $arrayInsertLog = array(
                        "id_entidad" => $data['idEntidadNew'],
                        "id_usuario_reg" => $idUsuario,
                        "fecha_registro" => $this->m_utils->fechaActual(),
                    );

                    $data = $this->m_utils->insertarLogEntidad($arrayInsertLog);
                    if ($data['error'] == EXIT_SUCCESS) {
                        $this->db->trans_commit();
                        $data['tbEntidades'] = $this->makeHTLMTablaConsulta($this->m_utils->getAllEntidades());
                    }

                }

            } else {
                throw new Exception('Ya existe esta entidad, ingrese otra por favor!!');
            }

        } catch (Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function updateEntidad()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $idEntidad = $this->input->post('idEntidad') ? $this->input->post('idEntidad') : null;
            $descEntidad = $this->input->post('descripEntidad') ? $this->input->post('descripEntidad') : null;
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;

            $this->db->trans_begin();

            if ($idUsuario == null) {
                throw new Exception('Su sesion ha experiado, ingrese nuevamente!!');
            }

            if ($idEntidad == null || $descEntidad == null) {
                throw new Exception('Hubo un error al traer los datos a actualizar, intentelo de nuevo!!');
            }

            $arrayUpdateEnt = array(
                "desc_entidad" => strtoupper(utf8_decode($descEntidad)),
            );

            $data = $this->m_utils->updateEntidad($idEntidad, $arrayUpdateEnt);
            if ($data['error'] == EXIT_SUCCESS) {
                $arrayInsertLog = array(
                    "id_entidad" => $idEntidad,
                    "id_usuario_mod" => $idUsuario,
                    "desc_entidad_mod" => strtoupper(utf8_decode($descEntidad)),
                    "fecha_modificacion" => $this->m_utils->fechaActual(),
                );

                $data = $this->m_utils->insertarLogEntidad($arrayInsertLog);
                if ($data['error'] == EXIT_SUCCESS) {
                    $this->db->trans_commit();
                    $data['tbEntidades'] = $this->makeHTLMTablaConsulta($this->m_utils->getAllEntidades());
                }
            }

        } catch (Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function makeHTLMTablaConsulta($listaEntidad)
    {

        $html = '
                <table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th style="text-align: center">#</th>
                            <th style="text-align: center">ENTIDAD</th>
                            <th style="text-align: center">FECHA DE REGISTRO</th>
                            <th style="text-align: center">ACCI&Oacute;N</th>
                        </tr>
                    </thead>

                    <tbody>';
        $count = 1;

        if ($listaEntidad != '') {
            foreach ($listaEntidad as $row) {

                $html .= '
                        <tr>
                            <td style="text-align: center">' . $count . '</td>
                            <td style="text-align: center">' . utf8_decode($row->desc_entidad) . '</td>
                            <td style="text-align: center">' . $row->fecha_registro . '</td>
                            <th style="text-align: center">
                                <a style="color:var(--verde_telefonica)" data-identidad="' . $row->idEntidad . '" onclick="openEditEntidad(this)"><i class="zmdi zmdi-hc-2x zmdi-edit"></i></a>
                                <a style="color:var(--verde_telefonica)" data-identidad="' . $row->idEntidad . '" onclick="openModalConfiDelete(this)"><i class="zmdi zmdi-hc-2x zmdi-delete"></i></a>
                            </th>
                        </tr>
                        ';
                $count++;
            }
            $html .= '</tbody>
                </table>';

        } else {
            $html .= '</tbody>
                </table>';
        }

        return utf8_decode($html);
    }

    public function getDetalleEntidad()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $idEntidad = $this->input->post('idEntidad') ? $this->input->post('idEntidad') : null;

            if ($idEntidad == null) {
                throw new Exception('Hubo un error al traer la entidad!!');
            }
            $arrayEntidad = $this->m_utils->getAllEntidades($idEntidad);
            $data['descEntidad'] = $arrayEntidad['desc_entidad'];
            $data['error'] = EXIT_SUCCESS;

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function deleteEntidad()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        try {
            $idEntidad = $this->input->post('idEntidad') ? $this->input->post('idEntidad') : null;
            if ($idEntidad == null) {
                throw new Exception('Hubo un error en recibir los datos!!');
            }

            $flgExisteEntidad = $this->m_utils->countEntidadInIPEstDet($idEntidad);

            if ($flgExisteEntidad == 0) {
                $data = $this->m_utils->deleteEntidad($idEntidad);
                if ($data['error'] == EXIT_SUCCESS) {
                    $data['tbEntidades'] = $this->makeHTLMTablaConsulta($this->m_utils->getAllEntidades());
                }
            } else {
                throw new Exception('No puede eliminar una entidad que ha sido acosiada a itemplan-estacion');
            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

}
