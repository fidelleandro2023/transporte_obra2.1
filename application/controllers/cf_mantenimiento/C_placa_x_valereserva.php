<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_placa_x_valereserva extends CI_Controller
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

            $data['tablaPlacaxVR'] = $this->makeHTLMTablaConsulta($this->m_utils->getPlacasxVR());

            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $permisos = $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_MANTENIMIENTO, ID_PERMISO_HIJO_MANT_PLACA_X_VR);
            $data['opciones'] = $result['html'];
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_mantenimiento/v_placa_x_valereserva', $data);
            } else {
                redirect('login', 'refresh');
            }
        } else {

            redirect('login', 'refresh');
        }

    }

    public function registrarPlacaxVR()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $placa = $this->input->post('placa') ? strtoupper($this->input->post('placa')) : null;
            $codigoVR = $this->input->post('codigoVR') ? trim($this->input->post('codigoVR')) : null;

            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;

            $this->db->trans_begin();

            if ($idUsuario == null) {
                throw new Exception('Su sesion ha expirado, ingrese nuevamente!!');
            }

            if ($placa == null && $codigoVR == null) {
                throw new Exception('Hubo un error al traer los datos!!');
            }

            $flgExistePlacaxVR = $this->m_utils->countPlacaxVR($codigoVR,$placa);

            if ($flgExistePlacaxVR == 0) {

                $arrayInsertPlacaxVR = array(
                    "placa" => $placa,
                    "codigo_vale_reserva" => $codigoVR,
                );

                $data = $this->m_utils->insertarPlacaxVR($arrayInsertPlacaxVR);
                if ($data['error'] == EXIT_SUCCESS) {
                    $arrayInsertLog = array(
                        "placa" => $placa,
                        "codigo_vale_reserva"=> $codigoVR,
                        "desc_actividad" => 'insert',
                        "id_usuario" => $idUsuario,
                        "fecha_registro" => $this->m_utils->fechaActual(),
                    );

                    $data = $this->m_utils->insertarLogPlacaxVR($arrayInsertLog);
                    if ($data['error'] == EXIT_SUCCESS) {
                        $this->db->trans_commit();
                        $data['tbPlacaxVR'] = $this->makeHTLMTablaConsulta($this->m_utils->getPlacasxVR());
                    }

                }

            } else {
                throw new Exception('Ya existe ese VR asociado con la placa, ingrese otra por favor!!');
            }

        } catch (Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function updatePlacaxVR()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $placa = $this->input->post('placa') ? strtoupper($this->input->post('placa')) : null;
            $codigoVR = $this->input->post('codigoVR') ? trim($this->input->post('codigoVR')) : null;

            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;

            $this->db->trans_begin();

            if ($idUsuario == null) {
                throw new Exception('Su sesion ha expirado, ingrese nuevamente!!');
            }

            if ($codigoVR == null) {
                throw new Exception('Hubo un error al traer lo datos!!');
            }

            if ($placa == null) {
                throw new Exception('Debe ingresar la placa para poder registrar!!');
            }

            $arrayUpdatePlaca = array(
                "placa" => $placa
            );
            log_message('error',$codigoVR);
            log_message('error',$placa);
            $flgExistePlacaxVR = $this->m_utils->countPlacaxVR($codigoVR,$placa);

            if ($flgExistePlacaxVR == 0) {
                $data = $this->m_utils->updatePlacaxVR($codigoVR,$placa, $arrayUpdatePlaca);
                if ($data['error'] == EXIT_SUCCESS) {
                    $arrayInsertLog = array(
                        "placa" => $placa,
                        "codigo_vale_reserva"=> $codigoVR,
                        "desc_actividad" => 'update',
                        "id_usuario" => $idUsuario,
                        "fecha_registro" => $this->m_utils->fechaActual(),
                    );

                    $data = $this->m_utils->insertarLogPlacaxVR($arrayInsertLog);
                    if ($data['error'] == EXIT_SUCCESS) {
                        $this->db->trans_commit();
                        $data['tbPlacaxVR'] = $this->makeHTLMTablaConsulta($this->m_utils->getPlacasxVR());
                    }
                }
            }else{
                throw new Exception('Ya existe ese VR asociado con la placa, ingrese otra por favor!!');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function makeHTLMTablaConsulta($listaPlacaxVR)
    {
        $html = '
                <table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th style="text-align: center">#</th>
                            <th style="text-align: center">PLACA</th>
                            <th style="text-align: center">VALE DE RESERVA</th>
                            <th style="text-align: center">ACCI&Oacute;N</th>
                        </tr>
                    </thead>

                    <tbody>';
        $count = 1;

        if ($listaPlacaxVR != '') {
            foreach ($listaPlacaxVR as $row) {

                $html .= '
                        <tr>
                            <td style="text-align: center">' . $count . '</td>
                            <td style="text-align: center">' . $row->placa . '</td>
                            <td style="text-align: center">' . utf8_decode($row->codigo_vale_reserva) . '</td>
                            <th style="text-align: center">
                                <a style="color:var(--verde_telefonica)" data-codigovr="' . $row->codigo_vale_reserva . '" data-placa="' . $row->placa . '" onclick="openEditPlacaxVR(this)"><i class="zmdi zmdi-hc-2x zmdi-edit"></i></a>
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

    public function getDetallePlacaxVR()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $placa = $this->input->post('placa') ? $this->input->post('placa') : null;
            $codigoVR = $this->input->post('codigoVR') ? $this->input->post('codigoVR') : null;

            if ($placa == null && $codigoVR == null) {
                throw new Exception('Hubo un error al traer los datos!!');
            }
            $arrayPlacaxVR = $this->m_utils->getPlacasxVR($codigoVR,$placa);
            $data['cmbPlaca'] = $this->makeHTMLPlaca($this->m_utils->getPlacas(),$arrayPlacaxVR['placa']);
            $data['codigoVR'] = $arrayPlacaxVR['codigo_vale_reserva'];

            $data['error'] = EXIT_SUCCESS;

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function getComboPlaca()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $data['cmbPlaca'] = $this->makeHTMLPlaca($this->m_utils->getPlacas());
            $data['error'] = EXIT_SUCCESS;

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function makeHTMLPlaca($listaPlacas, $placa = null)
    {
        $html = '<option value="">Seleccionar placa</option>';

        foreach ($listaPlacas as $row) {
            $selected = ($row->placa == $placa) ? 'selected' : null;
            $html .= '<option value="' . $row->placa . '" ' . $selected . ' >' . $row->placa . '</option>';
        }

        return utf8_decode($html);
    }


}
