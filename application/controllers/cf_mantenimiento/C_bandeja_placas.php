<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_bandeja_placas extends CI_Controller
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

            $data['tablaPlacas'] = $this->makeHTLMTablaConsulta($this->m_utils->getPlacas());

            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $permisos = $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_MANTENIMIENTO, ID_PERMISO_HIJO_MANT_PLACAS);
            $data['opciones'] = $result['html'];
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_mantenimiento/v_bandeja_placas', $data);
            } else {
                redirect('login', 'refresh');
            }
        } else {

            redirect('login', 'refresh');
        }

    }

    public function registrarPlacas()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $placa = $this->input->post('placa') ? strtoupper($this->input->post('placa')) : null;
            $marca = $this->input->post('marca') ? strtoupper($this->input->post('marca')) : null;
            $modelo = $this->input->post('modelo') ? strtoupper($this->input->post('modelo')) : null;
            $estado = $this->input->post('estado') ? $this->input->post('estado') : null;

            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;

            $this->db->trans_begin();

            if ($idUsuario == null) {
                throw new Exception('Su sesion ha experiado, ingrese nuevamente!!');
            }

            if ($placa == null) {
                throw new Exception('Debe ingresar la placa para poder registrar!!');
            }
            if($marca == ''){
                $marca = null;
            }
            if($modelo == ''){
                $modelo = null;
            }
            if($estado == ''){
                $estado = null;
            }

            $flgExistePlaca = $this->m_utils->countPlaca($placa);

            if ($flgExistePlaca == 0) {

                $arrayInsertPlaca = array(
                    "placa" => $placa,
                    "marca" => $marca,
                    "modelo" => $modelo,
                    "estado" => $estado
                );

                $data = $this->m_utils->insertarPlaca($arrayInsertPlaca);
                if ($data['error'] == EXIT_SUCCESS) {
                    $arrayInsertLog = array(
                        "placa" => $placa,
                        "desc_actividad" => 'insert',
                        "id_usuario" => $idUsuario,
                        "fecha_registro" => $this->m_utils->fechaActual(),
                    );

                    $data = $this->m_utils->insertarLogPlaca($arrayInsertLog);
                    if ($data['error'] == EXIT_SUCCESS) {
                        $this->db->trans_commit();
                        $data['tbPlacas'] = $this->makeHTLMTablaConsulta($this->m_utils->getPlacas());
                    }

                }

            } else {
                throw new Exception('Ya existe esta placa, ingrese otra por favor!!');
            }

        } catch (Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function updatePlaca()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $placa = $this->input->post('placa') ? strtoupper($this->input->post('placa')) : null;
            $marca = $this->input->post('marca') ? strtoupper($this->input->post('marca')) : null;
            $modelo = $this->input->post('modelo') ? strtoupper($this->input->post('modelo')) : null;
            $estado = $this->input->post('estado') ? $this->input->post('estado') : null;
            
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;

            $this->db->trans_begin();

            if ($idUsuario == null) {
                throw new Exception('Su sesion ha expirado, ingrese nuevamente!!');
            }

            if ($placa == null) {
                throw new Exception('Debe ingresar la placa para poder registrar!!');
            }
            if($marca == ''){
                $marca = null;
            }
            if($modelo == ''){
                $modelo = null;
            }
            if($estado == ''){
                $estado = null;
            }

            $arrayUpdatePlaca = array(
                "marca" => $marca,
                "modelo" => $modelo,
                "estado" => $estado
            );

            $data = $this->m_utils->updatePlaca($placa, $arrayUpdatePlaca);
            if ($data['error'] == EXIT_SUCCESS) {
                $arrayInsertLog = array(
                    "placa" => $placa,
                    "desc_actividad" => 'update',
                    "id_usuario" => $idUsuario,
                    "fecha_registro" => $this->m_utils->fechaActual(),
                );

                $data = $this->m_utils->insertarLogPlaca($arrayInsertLog);
                if ($data['error'] == EXIT_SUCCESS) {
                    $this->db->trans_commit();
                    $data['tbPlacas'] = $this->makeHTLMTablaConsulta($this->m_utils->getPlacas());
                }
            }

        } catch (Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function makeHTLMTablaConsulta($listaPlacas)
    {
        $html = '
                <table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th style="text-align: center">#</th>
                            <th style="text-align: center">PLACA</th>
                            <th style="text-align: center">MARCA</th>
                            <th style="text-align: center">MODELO</th>
                            <th style="text-align: center">ESTADO</th>
                            <th style="text-align: center">ACCI&Oacute;N</th>
                        </tr>
                    </thead>

                    <tbody>';
        $count = 1;

        if ($listaPlacas != '') {
            foreach ($listaPlacas as $row) {

                $html .= '
                        <tr>
                            <td style="text-align: center">' . $count . '</td>
                            <td style="text-align: center">' . $row->placa . '</td>
                            <td style="text-align: center">' . utf8_decode($row->marca) . '</td>
                            <td style="text-align: center">' . utf8_decode($row->modelo) . '</td>
                            <td style="text-align: center">' . ($row->estado == 1 ? 'ACTIVO' : ($row->estado == 2 ? 'MANTENIMIENTO' : ($row->estado == 3 ? 'MALOGRADO' : '-') ) ) . '</td>
                            <th style="text-align: center">
                                <a style="color:var(--verde_telefonica)" data-placa="' . $row->placa . '" onclick="openEditPlaca(this)"><i class="zmdi zmdi-hc-2x zmdi-edit"></i></a>
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

    public function getDetallePlaca()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $placa = $this->input->post('placa') ? $this->input->post('placa') : null;

            if ($placa == null) {
                throw new Exception('Hubo un error al traer la partida!!');
            }
            $arrayPlaca = $this->m_utils->getPlacas($placa);
            $data['placa'] = $arrayPlaca['placa'];
            $data['marca'] = $arrayPlaca['marca'];
            $data['modelo'] = $arrayPlaca['modelo'];
            $data['estado'] = $arrayPlaca['estado'];

            $data['error'] = EXIT_SUCCESS;

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function getComboPrecDiseno()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $data = $this->makeHTMLPrecDiseno($this->m_utils->getAllPrecDiseno());
            $data['cmbPrecDiseno'] = $data['comboHTML'];
            $data['error'] = EXIT_SUCCESS;

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function makeHTMLPrecDiseno($listaPrecDiseno, $idPrecioDiseno = null)
    {
        $html = '<option value="">Seleccionar Tipo de Precio</option>';

        foreach ($listaPrecDiseno as $row) {
            $selected = ($row->idPrecioDiseno == $idPrecioDiseno) ? 'selected' : null;
            $html .= '<option value="' . $row->idPrecioDiseno . '" ' . $selected . ' >' . $row->codigo_precio . '-' . $row->descPrecio . '</option>';
        }
        $data['comboHTML'] = utf8_decode($html);
        return $data;
    }


    public function getCombos()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $idPartida = $this->input->post('idPartida') ? $this->input->post('idPartida') : null;

            if ($idPartida == null) {
                throw new Exception('Hubo un error al traer la partida!!');
            }

            $data['cmbProyecto'] = $this->makeCmbProyecto($this->m_utils->getAllProyecto());
            $data['cmbEstacion'] = $this->makeCmbEstacion($this->m_utils->getEstacion());
            $data['cmbPartida'] = $this->makeCmbPartida($this->m_utils->getPartidas(),$idPartida);

            $data['error'] = EXIT_SUCCESS;

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }


    public function makeCmbProyecto($listaProyectos, $idProyecto = null)
    {
        $html = '<option>&nbsp;</option>';

        foreach ($listaProyectos->result() as $row) {
            $selected = ($row->idProyecto == $idProyecto) ? 'selected' : null;
            $html .= '<option value="' . $row->idProyecto . '" ' . $selected . ' >' . $row->proyectoDesc . '</option>';
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

    public function makeCmbPartida($listaPartidas, $idActividad = null)
    {
        $html = '<option>&nbsp;</option>';

        foreach ($listaPartidas as $row) {
            $selected = ($row->idActividad == $idActividad) ? 'selected' : null;
            $html .= '<option value="' . $row->idActividad . '" ' . $selected . ' >' . $row->descripcion . '</option>';
        }
        return utf8_decode($html);
    }

    public function registrarProyEstPart()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $idProyecto = $this->input->post('idProyecto') ? $this->input->post('idProyecto') : null;
            $idEstacion = $this->input->post('idEstacion') ? $this->input->post('idEstacion') : null;
            $idPartida = $this->input->post('idPartida') ? $this->input->post('idPartida') : null;
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;

            $this->db->trans_begin();

            if ($idUsuario == null) {
                throw new Exception('Su sesion ha experiado, ingrese nuevamente!!');
            }

            if ($idProyecto == null || $idEstacion == null || $idPartida == null) {
                throw new Exception('Hubo un error al traer los datos a registrar, intentelo de nuevo!!');
            }

            $flgExisteProyEstPart = $this->m_utils->countProyEstPart($idProyecto, $idEstacion, $idPartida);
            $ids = array();
            $arrayInsertLogGlob = array();

            if ($flgExisteProyEstPart == 0) {
                $arrayEstaciones = explode(",", $idEstacion);
                foreach ($arrayEstaciones as $row) {
                    $arrayInsertTemp = array(
                        "idProyecto" => $idProyecto,
                        "idEstacion" => $row,
                        "idPartida" => $idPartida,
                    );
                    $data = $this->m_utils->insertarProyEstPart($arrayInsertTemp);
                    $ids[] = $data['id'];
                    if ($data['error'] == EXIT_SUCCESS) {
                        $arrayInsertLogTemp = array(
                            "id" => $data['id'],
                            "actividad" => 'insert',
                            "idProyecto" => $idProyecto,
                            "idEstacion" => $row,
                            "idPartida" => $idPartida,
                            "id_usuario" => $idUsuario,
                            "fecha_registro" => $this->m_utils->fechaActual(),
                        );
                        array_push($arrayInsertLogGlob, $arrayInsertLogTemp);
                    }
                }

                if (count($arrayInsertLogGlob) > 0) {

                    $data = $this->m_utils->insertarLogProyEstPart($arrayInsertLogGlob);
                    if ($data['error'] == EXIT_SUCCESS) {
                        $this->db->trans_commit();
                        $data['arrayIdAct'] = implode(',',$ids);
                        log_message('error',$data['arrayIdAct']);
                    }
                }

            } else {
                throw new Exception('Ya existe este Proyecto-Estacion-Partida, ingrese otra por favor!!');
            }

        } catch (Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

}
