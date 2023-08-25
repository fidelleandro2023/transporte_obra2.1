<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_bandeja_almacen extends CI_Controller
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
            
            $data['tablaAlmacen'] = $this->makeHTLMTablaConsulta($this->m_utils->getJefaturaSapxEECC());

            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $permisos = $this->session->userdata('permisosArbol');
            #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_MANTENIMIENTO, ID_PERMISO_HIJO_MANT_ALMACEN);
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_MANTENIMIENTO_NUEVO_MODELO, ID_PERMISO_HIJO_MANT_ALMACEN, ID_MODULO_MANTENIMIENTO);
            $data['opciones'] = $result['html'];
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_mantenimiento/v_bandeja_almacen', $data);
            } else {
                redirect('login', 'refresh');
            }
        } else {

            redirect('login', 'refresh');
        }

    }

    public function registrarAlmacen()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $codCentro = $this->input->post('codCentro') ? $this->input->post('codCentro') : null;
            $codAlmacen = $this->input->post('codAlmacen') ? $this->input->post('codAlmacen') : null;
            $idJefatura = $this->input->post('idJefatura') ? $this->input->post('idJefatura') : null;
            $idEECC = $this->input->post('idEECC') ? $this->input->post('idEECC') : null;
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;

            $this->db->trans_begin();

            if ($idUsuario == null) {
                throw new Exception('Su sesion ha experiado, ingrese nuevamente!!');
            }

            if ($codCentro == null || $codAlmacen == null || $idJefatura == null  || $idEECC == null ) {
                throw new Exception('Hubo un error al traer los datos a registrar, intentelo de nuevo!!');
            }

            $flgExisteAlmacen = $this->m_utils->countAlmacenByJefaEECC($idJefatura,$idEECC);

            if ($flgExisteAlmacen == 0) {

                $arrayInsertAlmacen = array(
                    "codCentro" => strtoupper($codCentro),
                    "codAlmacen" => strtoupper($codAlmacen),
                    "idJefatura" => $idJefatura,
                    "idEmpresaColab" => $idEECC
                );

                $data = $this->m_utils->insertarAlmacen($arrayInsertAlmacen);
                if ($data['error'] == EXIT_SUCCESS) {
                    $arrayInsertLog = array(
                        "idJefatura" => $idJefatura,
                        "idEmpresaColab" => $idEECC,
                        "desc_actividad" => 'insert',
                        "id_usuario" => $idUsuario,
                        "fecha_registro" => $this->m_utils->fechaActual()
                    );

                    $data = $this->m_utils->insertarLogAlmacen($arrayInsertLog);
                    if ($data['error'] == EXIT_SUCCESS) {
                        $this->db->trans_commit();
                        $data['tbAlmacen'] = $this->makeHTLMTablaConsulta($this->m_utils->getJefaturaSapxEECC());
                    }

                }

            } else {
                throw new Exception('Ya existe este almacen relacionado con esta jefatura y empresa colaboradora, ingrese otro porfavor!!');
            }

        } catch (Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function updateAlmacen()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $codCentro = $this->input->post('codCentro') ? $this->input->post('codCentro') : null;
            $codAlmacen = $this->input->post('codAlmacen') ? $this->input->post('codAlmacen') : null;
            $idJefatura = $this->input->post('idJefatura') ? $this->input->post('idJefatura') : null;
            $idEECC = $this->input->post('idEECC') ? $this->input->post('idEECC') : null;
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;

            $this->db->trans_begin();

            if ($idUsuario == null) {
                throw new Exception('Su sesion ha experiado, ingrese nuevamente!!');
            }

            if ($codCentro == null || $codAlmacen == null || $idJefatura == null  || $idEECC == null ) {
                throw new Exception('Hubo un error al traer los datos a registrar, intentelo de nuevo!!');
            }

            $arrayUpdateAlmacen = array(
                "codCentro" => strtoupper($codCentro),
                "codAlmacen" => strtoupper($codAlmacen)
            );

            $data = $this->m_utils->updateAlmacen($idJefatura, $idEECC, $arrayUpdateAlmacen);
            if ($data['error'] == EXIT_SUCCESS) {
                $arrayInsertLog = array(
                    "idJefatura" => $idJefatura,
                    "idEmpresaColab" => $idEECC,
                    "desc_actividad" => 'update',
                    "id_usuario" => $idUsuario,
                    "fecha_registro" => $this->m_utils->fechaActual()
                );

                $data = $this->m_utils->insertarLogAlmacen($arrayInsertLog);
                if ($data['error'] == EXIT_SUCCESS) {
                    $this->db->trans_commit();
                    $data['tbAlmacen'] = $this->makeHTLMTablaConsulta($this->m_utils->getJefaturaSapxEECC());
                }
            }

        } catch (Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function makeHTLMTablaConsulta($listaAlmacen)
    {
        $html = '
                <table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th style="text-align: center">#</th>
                            <th style="text-align: center">CODIGO CENTRO</th>
                            <th style="text-align: center">CODIGO ALMACEN</th>
                            <th style="text-align: center">JEFATURA</th>
                            <th style="text-align: center">EE.CC.</th>
                            <th style="text-align: center">ACCI&Oacute;N</th>
                        </tr>
                    </thead>

                    <tbody>';
        $count = 1;

        if ($listaAlmacen != '') {
            foreach ($listaAlmacen as $row) {

                $html .= '
                        <tr>
                            <td style="text-align: center">' . $count . '</td>
                            <td style="text-align: center">' . $row->codCentro . '</td>
                            <td style="text-align: center">' . $row->codAlmacen . '</td>
                            <td style="text-align: center">' . $row->descripcion . '</td>
                            <td style="text-align: center">' . $row->empresaColabDesc . '</td>
                            <th style="text-align: center">
                                <a style="color:var(--verde_telefonica)" data-idjefatura="' . $row->idJefatura . '" data-ideecc="' . $row->idEmpresaColab . '" onclick="openEditAlmacen(this)"><i class="zmdi zmdi-hc-2x zmdi-edit"></i></a>
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

    public function getDetalleAlmacen()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $idJefatura = $this->input->post('idJefatura') ? $this->input->post('idJefatura') : null;
            $idEECC = $this->input->post('idEECC') ? $this->input->post('idEECC') : null;

            if ($idJefatura == null && $idEECC == null) {
                throw new Exception('Hubo un error al traer el almacen!!');
            }
            $arrayAlmacen = $this->m_utils->getJefaturaSapxEECC($idJefatura,$idEECC);
            $data['cmbJefatura'] = $this->getJefaturaCmb($this->m_utils->getJefaturaTB(), $arrayAlmacen['idJefatura']);
            $data['cmbEECC'] = $this->getEECCCmb($this->m_utils->getAllEECC(), $arrayAlmacen['idEmpresaColab']);
            $data['codAlmacen'] = $arrayAlmacen['codAlmacen'];
            $data['codCentro'] = $arrayAlmacen['codCentro'];

            $data['error'] = EXIT_SUCCESS;

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function getCombosRegAlmacen()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $data['cmbJefatura'] = $this->getJefaturaCmb($this->m_utils->getJefaturaSapCmb());
            $data['cmbEECC'] = $this->getEECCCmb($this->m_utils->getAllEECC());
            $data['error'] = EXIT_SUCCESS;

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function getJefaturaCmb($listaJefatura, $idJefatura = null)
    {
        $html = '<option value="">Seleccionar Jefatura</option>';

        foreach ($listaJefatura as $row) {
            $selected = ($row->idJefatura == $idJefatura) ? 'selected' : null;
            $html .= '<option value="' . $row->idJefatura . '" ' . $selected . ' >' . $row->descripcion . '</option>';
        }

        return  utf8_decode($html);
    }

    public function getEECCCmb($listaEECC, $idEECC = null)
    {
        $html = '<option value="">Seleccionar EECC</option>';

        foreach ($listaEECC->result() as $row) {
            $selected = ($row->idEmpresaColab == $idEECC) ? 'selected' : null;
            $html .= '<option value="' . $row->idEmpresaColab . '" ' . $selected . ' >' . $row->empresaColabDesc . '</option>';
        }

        return  utf8_decode($html);
    }

}
