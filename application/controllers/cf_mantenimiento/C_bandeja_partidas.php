<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_bandeja_partidas extends CI_Controller
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

            $data['tablaPartidas'] = $this->makeHTLMTablaConsulta($this->m_utils->getPartidas());

            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $permisos = $this->session->userdata('permisosArbol');
            #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_MANTENIMIENTO, ID_PERMISO_HIJO_MANT_PARTIDA);
			$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_MANTENIMIENTO_CERTIFICACION, ID_PERMISO_HIJO_MANT_PARTIDA, ID_MODULO_MANTENIMIENTO);            
            $data['opciones'] = $result['html'];
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_mantenimiento/v_bandeja_partidas', $data);
            } else {
                redirect('login', 'refresh');
            }
        } else {

            redirect('login', 'refresh');
        }

    }

    public function registrarPartida()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $codigo = $this->input->post('codigo') ? $this->input->post('codigo') : null;
            $descPartida = $this->input->post('descPartida') ? $this->input->post('descPartida') : null;
            $baremo = $this->input->post('baremo') ? $this->input->post('baremo') : null;
            $descKitMat = $this->input->post('descKitMat') ? $this->input->post('descKitMat') : null;
            $costoMat = $this->input->post('costoMat') ? $this->input->post('costoMat') : null;
            $precioDiseno = $this->input->post('precioDiseno') ? $this->input->post('precioDiseno') : null;
            $tipoPLanta = $this->input->post('tipoPLanta') ? $this->input->post('tipoPLanta') : null;
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;

            $this->db->trans_begin();

            if ($idUsuario == null) {
                throw new Exception('Su sesion ha experiado, ingrese nuevamente!!');
            }

            if ($codigo == null || $descPartida == null || $baremo == null /*|| $descKitMat == null || $costoMat == null*/ || $precioDiseno == null || $tipoPLanta == null) {
                throw new Exception('Hubo un error al traer los datos a registrar, intentelo de nuevo!!');
            }

            $flgExistePartida = $this->m_utils->countPartida($codigo);

            if ($flgExistePartida == 0) {

                $arrayInsertPartida = array(
                    "codigo" => $codigo,
                    "descripcion" => strtoupper(utf8_decode($descPartida)),
                    "baremo" => number_format($baremo, 2),
                    "kit_material" => strtoupper(utf8_decode($descKitMat)),
                    "costo_material" => number_format($costoMat, 2),
                    "estado" => 1,
                    "idPrecioDiseno" => $precioDiseno,
                    "flg_tipo" => $tipoPLanta,
                );

                $data = $this->m_utils->insertarPartida($arrayInsertPartida);
                if ($data['error'] == EXIT_SUCCESS) {
                    $idActividadNew =  $data['idActividadNew'];
                    $arrayInsertLog = array(
                        "idActividad" => $data['idActividadNew'],
                        "desc_actividad" => 'insert',
                        "id_usuario" => $idUsuario,
                        "fecha_registro" => $this->m_utils->fechaActual(),
                    );

                    $data = $this->m_utils->insertarLogPartida($arrayInsertLog);
                    if ($data['error'] == EXIT_SUCCESS) {
                        $this->db->trans_commit();
                        $data['tbPartidas'] = $this->makeHTLMTablaConsulta($this->m_utils->getPartidas());
                        $data['idActividad'] = $idActividadNew;

                    }

                }

            } else {
                throw new Exception('Ya existe esta partida, ingrese otra por favor!!');
            }

        } catch (Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function updatePartida()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $idActividad = $this->input->post('idActividad') ? $this->input->post('idActividad') : null;
            $codigo = $this->input->post('codigo') ? $this->input->post('codigo') : null;
            $descPartida = $this->input->post('descPartida') ? $this->input->post('descPartida') : null;
            $baremo = $this->input->post('baremo') ? $this->input->post('baremo') : null;
            $descKitMat = $this->input->post('descKitMat') ? $this->input->post('descKitMat') : null;
            $costoMat = $this->input->post('costoMat') ? $this->input->post('costoMat') : null;
            $precioDiseno = $this->input->post('precioDiseno') ? $this->input->post('precioDiseno') : null;
            $tipoPLanta = $this->input->post('tipoPLanta') ? $this->input->post('tipoPLanta') : null;
            $estado = $this->input->post('estado') ? $this->input->post('estado') : null;
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;

            $this->db->trans_begin();

            if ($idUsuario == null) {
                throw new Exception('Su sesion ha experiado, ingrese nuevamente!!');
            }

            if ($idActividad == null || $codigo == null || $descPartida == null || $baremo == null || $precioDiseno == null || $tipoPLanta == null || $estado == null) {
                throw new Exception('Hubo un error al traer los datos a registrar, intentelo de nuevo!!');
            }

            $arrayUpdatePartida = array(
                "codigo" => $codigo,
                "descripcion" => strtoupper(utf8_decode($descPartida)),
                "baremo" => number_format($baremo, 2),
                "kit_material" => strtoupper(utf8_decode($descKitMat)),
                "costo_material" => number_format($costoMat, 2),
                "estado" => $estado,
                "idPrecioDiseno" => $precioDiseno,
                "flg_tipo" => $tipoPLanta,
            );

            $data = $this->m_utils->updatePartida($idActividad, $arrayUpdatePartida);
            if ($data['error'] == EXIT_SUCCESS) {
                $arrayInsertLog = array(
                    "idActividad" => $idActividad,
                    "desc_actividad" => 'update',
                    "id_usuario" => $idUsuario,
                    "fecha_registro" => $this->m_utils->fechaActual(),
                );

                $data = $this->m_utils->insertarLogPartida($arrayInsertLog);
                if ($data['error'] == EXIT_SUCCESS) {
                    $this->db->trans_commit();
                    $data['tbPartidas'] = $this->makeHTLMTablaConsulta($this->m_utils->getPartidas());
                }
            }

        } catch (Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function makeHTLMTablaConsulta($listaPartidas)
    {
        $html = '
                <table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th style="text-align: center">#</th>
                            <th style="text-align: center">CODIGO</th>
                            <th style="text-align: center">DESCRIPCION</th>
                            <th style="text-align: center">BAREMO</th>
                            <th style="text-align: center">KIT MATERIAL</th>
                            <th style="text-align: center">COSTO MATERIAL</th>
                            <th style="text-align: center">PLIEGO</th>
                            <th style="text-align: center">ESTADO</th>
                            <th style="text-align: center">TIPO PRECIO</th>
                            <th style="text-align: center">ACCI&Oacute;N</th>
                        </tr>
                    </thead>

                    <tbody>';
        $count = 1;

        if ($listaPartidas != '') {
            foreach ($listaPartidas as $row) {

                $html .= '
                        <tr>
                            <td style="text-align: center">' . $count . '</td>
                            <td>' . $row->codigo . '</td>
                            <td>' . utf8_decode($row->descripcion) . '</td>
                            <td style="text-align: center">' . number_format($row->baremo, 2) . '</td>
                            <td>' . utf8_decode($row->kit_material) . '</td>
                            <td style="text-align: center">' . number_format($row->costo_material, 2) . '</td>
                            <td style="text-align: center">' . $row->pliego . '</td>
                            <td style="text-align: center">' . ($row->estado == 1 ? 'ACTIVO' : 'INACTIVO') . '</td>
                            <td style="text-align: center">' . $row->codigo_precio . '-' . $row->descPrecio . '</td>
                            <th style="text-align: center">
                                <a style="color:var(--verde_telefonica)" data-idactividad="' . $row->idActividad . '" onclick="openEditPartida(this)"><i class="zmdi zmdi-hc-2x zmdi-edit"></i></a>
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

    public function getDetallePartida()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $idActividad = $this->input->post('idActividad') ? $this->input->post('idActividad') : null;

            if ($idActividad == null) {
                throw new Exception('Hubo un error al traer la partida!!');
            }
            $arrayPartida = $this->m_utils->getPartidas($idActividad);
            $data = $this->makeHTMLPrecDiseno($this->m_utils->getAllPrecDiseno(), $arrayPartida['idPrecioDiseno']);
            $data['cmbPrecioDiseno'] = $data['comboHTML'];
            $data['codigo'] = $arrayPartida['codigo'];
            $data['descripcion'] = $arrayPartida['descripcion'];
            $data['baremo'] = $arrayPartida['baremo'];
            $data['kit_material'] = $arrayPartida['kit_material'];
            $data['costo_material'] = $arrayPartida['costo_material'];
            $data['flg_tipo'] = $arrayPartida['flg_tipo'];
            $data['estado'] = ($arrayPartida['estado'] == '1' ? 1 : 2);
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
