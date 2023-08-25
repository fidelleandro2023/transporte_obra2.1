<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_bandeja_jefatura extends CI_Controller
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
            $data['listaSubProy'] = $this->m_utils->getAllSubProyecto();

            $idPersonaSession = $this->session->userdata('idPersonaSession');
            $descPerfilSesion = $this->session->userdata('descPerfilSession');

            $data['tablaJefatura'] = $this->makeHTLMTablaConsulta($this->m_utils->getAllJefatura());

            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $permisos = $this->session->userdata('permisosArbol');
            #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_MANTENIMIENTO, ID_PERMISO_HIJO_MANT_JEFATURA);
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_MANTENIMIENTO_NUEVO_MODELO, ID_PERMISO_HIJO_MANT_JEFATURA, ID_MODULO_MANTENIMIENTO);
            $data['opciones'] = $result['html'];
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_mantenimiento/v_bandeja_jefatura', $data);
            } else {
                redirect('login', 'refresh');
            }
        } else {

            redirect('login', 'refresh');
        }

    }

    public function registrarJefatura()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $jefatura = $this->input->post('jefatura') ? strtoupper($this->input->post('jefatura')) : null;
            $estado = $this->input->post('estado') ? $this->input->post('estado') : null;

            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;

            $this->db->trans_begin();

            if ($idUsuario == null) {
                throw new Exception('Su sesion ha expirado, ingrese nuevamente!!');
            }

            if ($jefatura == null) {
                throw new Exception('Debe ingresar la jefatura para poder registrar!!');
            }
            if($estado == ''){
                $estado = null;
            }

            $flgExisteJefatura = $this->m_utils->countJefatura($jefatura);

            if ($flgExisteJefatura == 0) {

                $arrayInsertJefatura = array(
                    "descripcion" => $jefatura,
                    "flgActivo" => $estado
                );

                $data = $this->m_utils->insertarJefatura($arrayInsertJefatura);
                if ($data['error'] == EXIT_SUCCESS) {
                    $arrayInsertLog = array(
                        "idJefatura" => $data['idJefatura'],
                        "descripcion" => $jefatura,
                        "desc_actividad" => 'insert',
                        "id_usuario" => $idUsuario,
                        "fecha_registro" => $this->m_utils->fechaActual()
                    );

                    $data = $this->m_utils->insertarLogJefatura($arrayInsertLog);
                    if ($data['error'] == EXIT_SUCCESS) {
                        $this->db->trans_commit();
                        $data['tbJefatura'] = $this->makeHTLMTablaConsulta($this->m_utils->getAllJefatura());
                    }

                }

            } else {
                throw new Exception('Ya existe esta jefatura, ingrese otra por favor!!');
            }

        } catch (Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function updateJefatura()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $idJefatura = $this->input->post('idJefatura') ? $this->input->post('idJefatura') : null;
            $jefatura = $this->input->post('jefatura') ? strtoupper($this->input->post('jefatura')) : null;
            $estado = $this->input->post('estado') ? $this->input->post('estado') : null;

            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;

            $this->db->trans_begin();


            if ($idUsuario == null) {
                throw new Exception('Su sesion ha experiado, ingrese nuevamente!!');
            }

            if ($idJefatura == null || $jefatura == null) {
                throw new Exception('Debe ingresar la jefatura para poder actualizar!!');
            }

            if($estado == ''){
                $estado = null;
            }

            $arrayUpdateJefatura = array(
                "descripcion" => $jefatura,
                "flgActivo" => $estado
            );

            $data = $this->m_utils->updateJefatura($idJefatura, $arrayUpdateJefatura);
            if ($data['error'] == EXIT_SUCCESS) {
                $arrayInsertLog = array(
                    "idJefatura" => $idJefatura,
                    "descripcion" => $jefatura,
                    "desc_actividad" => 'update',
                    "id_usuario" => $idUsuario,
                    "fecha_registro" => $this->m_utils->fechaActual()
                );

                $data = $this->m_utils->insertarLogJefatura($arrayInsertLog);
                if ($data['error'] == EXIT_SUCCESS) {
                    $this->db->trans_commit();
                    $data['tbJefatura'] = $this->makeHTLMTablaConsulta($this->m_utils->getAllJefatura());
                }
            }

        } catch (Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function makeHTLMTablaConsulta($listaJefatura)
    {
        $html = '
                <table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th style="text-align: center">#</th>
                            <th style="text-align: center">JEFATURA</th>
                            <th style="text-align: center">ESTADO</th>
                            <th style="text-align: center">ACCI&Oacute;N</th>
                        </tr>
                    </thead>

                    <tbody>';
        $count = 1;

        if ($listaJefatura != '') {
            foreach ($listaJefatura as $row) {

                $html .= '
                        <tr>
                            <td style="text-align: center">' . $count . '</td>
                            <td style="text-align: center">' . $row->descripcion . '</td>
                            <td style="text-align: center">' . ($row->flgActivo == 1 ? 'ACTIVO' :  'INACTIVO' ) . '</td>
                            <th style="text-align: center">
                                <a style="color:var(--verde_telefonica)" data-idjefatura="' . $row->idJefatura . '" onclick="openEditJefatura(this)"><i class="zmdi zmdi-hc-2x zmdi-edit"></i></a>
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

    public function getDetalleJefatura()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $idJefatura = $this->input->post('idJefatura') ? $this->input->post('idJefatura') : null;

            if ($idJefatura == null) {
                throw new Exception('Hubo un error al traer la jefatura!!');
            }
            $arrayJefatura = $this->m_utils->getDetJefatura($idJefatura);
            $data['descripcion'] = $arrayJefatura['descripcion'];
            $data['estado'] = $arrayJefatura['flgActivo'];

            $data['error'] = EXIT_SUCCESS;

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

}
