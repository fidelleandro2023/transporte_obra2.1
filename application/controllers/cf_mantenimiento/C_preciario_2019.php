<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_preciario_2019 extends CI_Controller
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
        $idEcc = $this->session->userdata('eeccSession');

        if ($logedUser != null) {
            // Trayendo zonas permitidas al usuario
            $zonas = $this->session->userdata('zonasSession');
            $data['listaEECC'] = $this->m_utils->getECCbyidEmpresaSession($idEcc, null);
            $data['listaZonal'] = $this->m_utils->getAllZonal();
            $data['listaEstaciones'] = $this->m_utils->getEstacion();
            $data['listaTipoPrecio'] = $this->m_utils->getAllPrecDiseno();

            $data['tablaPreciario'] = $this->makeHTLMTablaConsulta('' /*$this->m_utils->getAllPreciario()*/);

            $permisos = $this->session->userdata('permisosArbol');
            #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_MANTENIMIENTO, ID_PERMISO_HIJO_MANT_PRECIARIOS);
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_MANTENIMIENTO_CERTIFICACION, ID_PERMISO_HIJO_MANT_PRECIARIOS, ID_MODULO_MANTENIMIENTO);
            $data['opciones'] = $result['html'];
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_mantenimiento/v_preciario_2019', $data);
            } else {
                redirect('login', 'refresh');
            }
        } else {

            redirect('login', 'refresh');
        }

    }

    public function registrarPreciario()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $idEECC = $this->input->post('idEECC') ? $this->input->post('idEECC') : null;
            $idZonal = $this->input->post('idZonal') ? $this->input->post('idZonal') : null;
            $idEstacion = $this->input->post('idEstacion') ? $this->input->post('idEstacion') : null;
            $idTipoPrecio = $this->input->post('idTipoPrecio') ? $this->input->post('idTipoPrecio') : null;
            $costo = $this->input->post('costo') ? $this->input->post('costo') : null;
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;

            $this->db->trans_begin();

            if ($idUsuario == null) {
                throw new Exception('Su sesion ha experiado, ingrese nuevamente!!');
            }

            if ($idEECC == null || $idZonal == null || $idEstacion == null || $idTipoPrecio == null || $costo == null) {
                throw new Exception('Hubo un error al traer los datos a registrar, intentelo de nuevo!!');
            }

            $flgExistePreciario = $this->m_utils->countPreciario($idEECC, $idZonal, $idEstacion, $idTipoPrecio);
            $arrayInsertLogGlob = array();

            if ($flgExistePreciario == 0) {
                $arrayEstaciones = explode(",", $idEstacion);
                foreach ($arrayEstaciones as $row) {
                    $arrayInsertTemp = array(
                        "idPrecioDiseno" => $idTipoPrecio,
                        "idZonal" => $idZonal,
                        "idEmpresacolab" => $idEECC,
                        "costo" => $costo,
                        "idEstacion" => $row,
                    );
                    $data = $this->m_utils->insertarPreciario($arrayInsertTemp);

                    if ($data['error'] == EXIT_SUCCESS) {
                        $arrayInsertLogTemp = array(
                            "actividad" => 'insert',
                            "idPrecioDiseno" => $idTipoPrecio,
                            "idZonal" => $idZonal,
                            "idEmpresacolab" => $idEECC,
                            "idEstacion" => $row,
                            "id_usuario" => $idUsuario,
                            "fecha_registro" => $this->m_utils->fechaActual(),
                        );
                        array_push($arrayInsertLogGlob, $arrayInsertLogTemp);
                    }
                }

                if (count($arrayInsertLogGlob) > 0) {

                    $data = $this->m_utils->insertarLogPreciario($arrayInsertLogGlob);
                    if ($data['error'] == EXIT_SUCCESS) {
                        $this->db->trans_commit();
                        $data['tablaPreciario'] = $this->makeHTLMTablaConsulta($this->m_utils->getAllPreciario2019($idEECC, $idZonal, $idEstacion, $idTipoPrecio));
                    }
                }

            } else {
                throw new Exception('Ya existe este Preciario, ingrese otra por favor!!');
            }

        } catch (Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function updatePreciario()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $idEECC = $this->input->post('idEECC') ? $this->input->post('idEECC') : null;
            $idZonal = $this->input->post('idZonal') ? $this->input->post('idZonal') : null;
            $idEstacion = $this->input->post('idEstacion') ? $this->input->post('idEstacion') : null;
            $idTipoPrecio = $this->input->post('idTipoPrecio') ? $this->input->post('idTipoPrecio') : null;
            $costo = $this->input->post('costo') ? $this->input->post('costo') : null;
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;

            $this->db->trans_begin();

            if ($idUsuario == null) {
                throw new Exception('Su sesion ha experiado, ingrese nuevamente!!');
            }

            if ($idEECC == null || $idZonal == null || $idEstacion == null || $idTipoPrecio == null || $costo == null) {
                throw new Exception('Hubo un error al traer los datos a registrar, intentelo de nuevo!!');
            }

            $arrayUpdatePreciario = array(
                "costo" => $costo,
            );

            $data = $this->m_utils->updatePreciario($idTipoPrecio, $idZonal, $idEECC, $idEstacion, $arrayUpdatePreciario);
            if ($data['error'] == EXIT_SUCCESS) {
                $arrayInsertLogGlob = array();
                $arrayInsertLog = array(
                    "actividad" => 'update',
                    "idPrecioDiseno" => $idTipoPrecio,
                    "idZonal" => $idZonal,
                    "idEmpresacolab" => $idEECC,
                    "idEstacion" => $idEstacion,
                    "id_usuario" => $idUsuario,
                    "fecha_registro" => $this->m_utils->fechaActual(),
                );
                array_push($arrayInsertLogGlob, $arrayInsertLog);

                $data = $this->m_utils->insertarLogPreciario($arrayInsertLogGlob);
                if ($data['error'] == EXIT_SUCCESS) {
                    $this->db->trans_commit();
                    $data['tablaPreciario'] = $this->makeHTLMTablaConsulta($this->m_utils->getAllPreciario2019($idEECC, $idZonal, $idEstacion, $idTipoPrecio));
                }
            }

        } catch (Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function makeHTLMTablaConsulta($listaPreciario)
    {
        $html = '
                <table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th style="text-align: center">#</th>
                            <th style="text-align: center">EECC</th>
                            <th style="text-align: center">ZONAL</th>
                            <th style="text-align: center">ESTACION</th>
                            <th style="text-align: center">TIPO PRECIO</th>
                            <th style="text-align: center">COSTO</th>
                        </tr>
                    </thead>

                    <tbody>';
        $count = 1;

        if ($listaPreciario != '') {
            foreach ($listaPreciario as $row) {

                $html .= '
                        <tr>
                            <td style="text-align: center">' . $count . '</td>
                            <td style="text-align: center">' . utf8_decode($row->empresaColabDesc) . '</td>
                            <td style="text-align: center">' . utf8_decode($row->zonalDesc) . '</td>
                            <td style="text-align: center">' . utf8_decode($row->estacionDesc) . '</td>
                            <td style="text-align: center">' . utf8_decode($row->descPrecio) . '</td>
                            <td style="text-align: center">' . number_format($row->costo, 2) . '</td>
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

    public function filtrarTablaPreciario2019()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $idEECC = $this->input->post('idEECC') ? $this->input->post('idEECC') : null;
            $idZonal = $this->input->post('idZonal') ? $this->input->post('idZonal') : null;
            $idEstacion = $this->input->post('idEstacion') ? $this->input->post('idEstacion') : null;
            $idPrecioDiseno = $this->input->post('idPrecioDiseno') ? $this->input->post('idPrecioDiseno') : null;

            $data['tablaPreciario'] = $this->makeHTLMTablaConsulta($this->m_utils->getAllPreciario2019($idEECC, $idZonal, $idEstacion, $idPrecioDiseno));
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function getDetPreciario()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $idEECC = $this->input->post('idEECC') ? $this->input->post('idEECC') : null;
            $idZonal = $this->input->post('idZonal') ? $this->input->post('idZonal') : null;
            $idEstacion = $this->input->post('idEstacion') ? $this->input->post('idEstacion') : null;
            $idTipoPrecio = $this->input->post('idTipoPrecio') ? $this->input->post('idTipoPrecio') : null;


            if ($idEECC == null || $idZonal == null || $idEstacion == null || $idTipoPrecio == null) {
                throw new Exception('Hubo un error al traer el preciario');
            }
            $arrayPreciario = $this->m_utils->getAllPreciario2019($idEECC, $idZonal, $idEstacion, $idTipoPrecio, 1);

            $data['cmbEECC'] = $this->makeCmbEECC($this->m_utils->getECCbyidEmpresaSession($idEECC, $idEECC), $arrayPreciario['idEmpresaColab']);
            $data['cmbZonal'] = $this->makeCmbZonal($this->m_utils->getAllZonal(), $arrayPreciario['idZonal']);
            $data['cmbEstacion'] = $this->makeCmbEstacion($this->m_utils->getEstacion(), $arrayPreciario['idEstacion']);
            $data['cmbTipoPrecio'] = $this->makeCmbTipoPrecio($this->m_utils->getAllPrecDiseno(), $arrayPreciario['idPrecioDiseno']);
            $data['costo'] = $arrayPreciario['costo'];

            $data['error'] = EXIT_SUCCESS;

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function getCombos()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $idEcc = $this->session->userdata('eeccSession');

            $data['cmbEECC'] = $this->makeCmbEECC($this->m_utils->getECCbyidEmpresaSession($idEcc, null));
            $data['cmbZonal'] = $this->makeCmbZonal($this->m_utils->getAllZonal());
            $data['cmbEstacion'] = $this->makeCmbEstacion($this->m_utils->getEstacion());
            $data['cmbTipoPrecio'] = $this->makeCmbTipoPrecio($this->m_utils->getAllPrecDiseno());

            $data['error'] = EXIT_SUCCESS;

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function makeCmbEECC($listaEECC, $idEmpresaColab = null)
    {
        $html = '<option>&nbsp;</option>';

        foreach ($listaEECC->result() as $row) {
            $selected = ($row->idEmpresaColab == $idEmpresaColab) ? 'selected' : null;
            $html .= '<option value="' . $row->idEmpresaColab . '" ' . $selected . ' >' . $row->empresaColabDesc . '</option>';
        }
        return utf8_decode($html);
    }

    public function makeCmbZonal($listaZonales, $idZonal = null)
    {
        $html = '<option>&nbsp;</option>';

        foreach ($listaZonales->result() as $row) {
            $selected = ($row->idZonal == $idZonal) ? 'selected' : null;
            $html .= '<option value="' . $row->idZonal . '" ' . $selected . ' >' . $row->zonalDesc . '</option>';
        }
        return utf8_decode($html);
    }

    public function makeCmbEstacion($listaEstaciones, $idEstacion = null)
    {
        $html = '<option>&nbsp;</option>';

        foreach ($listaEstaciones as $row) {
            $selected = ($row->idEstacion == $idEstacion) ? 'selected' : null;
            $html .= '<option value="' . $row->idEstacion . '" ' . $selected . ' >' . $row->estacionDesc . '</option>';
        }
        return utf8_decode($html);
    }

    public function makeCmbTipoPrecio($listaTipoPrecio, $idPrecioDiseno = null)
    {
        $html = '<option>&nbsp;</option>';

        foreach ($listaTipoPrecio as $row) {
            $selected = ($row->idPrecioDiseno == $idPrecioDiseno) ? 'selected' : null;
            $html .= '<option value="' . $row->idPrecioDiseno . '" ' . $selected . ' >' . $row->descPrecio . '</option>';
        }
        return utf8_decode($html);
    }

    public function deleteProyEstPart()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        try {
            // $idProyecto = $this->input->post('idProyecto') ? $this->input->post('idProyecto') : null;
            $id = $this->input->post('id') ? $this->input->post('id') : null;
            // $idEstacion = $this->input->post('idEstacion') ? $this->input->post('idEstacion') : null;
            // $idPartida = $this->input->post('idPartida') ? $this->input->post('idPartida') : null;
            if ($id == null) {
                throw new Exception('Hubo un error en recibir los datos!!');
            }

            $data = $this->m_utils->deleteProyEstPart($id);
            if ($data['error'] == EXIT_SUCCESS) {
                $data['tablaProyEstPart'] = $this->makeHTLMTablaConsulta('');
            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

}
