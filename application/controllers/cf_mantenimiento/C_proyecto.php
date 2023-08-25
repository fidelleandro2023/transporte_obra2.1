<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 *
 * @author CRISTOBAL ARTETA
 * 18/01/2018
 *
 */
class C_proyecto extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_mantenimiento/m_proyecto');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index()
    {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {

            $data['listaTipoCentral'] = $this->m_utils->getAllTipoCentral();
            $data['listaTipoLabel'] = $this->m_utils->getAllTipoLabel();
            $data['listaAreas'] = $this->m_utils->getAllIdEstacionAreaByArea();
            $data['listaProyectos'] = $this->m_utils->getAllProyecto();
            $data['listaTipoPlanta'] = $this->m_utils->getAllTipoPlantal();
            $data['listaSubProyecto'] = $this->m_proyecto->getAllSubProyectoDesc();
            $data['listaActividad'] = $this->m_proyecto->getAllActividades();

            $data['tbSubproyecto'] = $this->makeHTLMTablaSubPro($this->m_utils->getAllSubProyectoDesc());
            $data['tbProyecto'] = $this->makeHTLMTablaProyecto($this->m_utils->getAllProyectoDesc());

            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $permisos = $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_MANTENIMIENTO, ID_PERMISO_HIJO_MANTE_PROYECTO);

            $data['opciones'] = $result['html'];
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_mantenimiento/v_proyecto', $data);
            } else {
                redirect('login', 'refresh');
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function makeHTLMTablaSubPro($lista)
    {

        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>PROYECTO</th>
                            <th>SUB PROYECTO</th>
                            <th>TIPO PLANTA</th>
                            <th>TIEMPO</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>';

        foreach ($lista->result() as $row) {

            $html .= ' <tr>

							<td>' . $row->proyectoDesc . '</td>
							<td>' . utf8_decode($row->subProyectoDesc) . '</td>
							<td>' . $row->tipoPlantadesc . '</td>
							<td>' . $row->tiempo . '</td>
						    <th><a data-id_spro="' . $row->idSubProyecto . '" onclick="editSubProyecto(this)"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/editar.ico"></a></th>

						</tr>';
        }
        $html .= '</tbody>
                </table>';

        return utf8_decode($html);
    }

    public function makeHTLMTablaActividadSubPro($lista)
    {

        $html = '<table id="data-table7" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>SUB PROYECTO</th>
                            <th>ACTIVIDAD</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>';
        if (!is_null($lista)) {
            foreach ($lista->result() as $row) {

                $html .= ' <tr>

							<td>' . utf8_decode($row->subProyectoDesc) . '</td>
							<td>' . $row->actividad . '</td>
						    <th><a data-id_sproact="' . $row->idactividad_x_subProyecto . '" onclick="editSubProyectoActividad(this)"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/editar.ico"></a></th>

						</tr>';
            }
        }
        $html .= '</tbody>
                </table>';

        return utf8_decode($html);
    }

    public function filtrarTabla()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $SubProy = $this->input->post('subProy');
            $data['tablaSubProyPep'] = $this->makeHTLMTablaPepSubPro($this->m_proyecto->getPep1SubProy($SubProy));
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function makeHTLMTablaProyecto($lista)
    {
        $html = '<table id="data-table4" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>

                            <th>PROYECTO</th>
                            <th>TIPO CENTRAL</th>
                            <th>TIPO LABEL</th>
                            <th class="text-center">GERENCIA</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>';

        foreach ($lista->result() as $row) {

            $html .= ' <tr>

							<td>' . $row->proyectoDesc . '</td>
							<td>' . $row->tipoCentralDesc . '</td>
						    <td>' . $row->tipoLabelDesc . '</td>
						    <td class="text-center">' . $row->gerenciaDesc . '</td>
						    <th><a data-id_pro="' . $row->idProyecto . '" onclick="editProyecto(this)"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/editar.ico"></a></th>
						</tr>';
        }
        $html .= '</tbody>
                </table>';

        return utf8_decode($html);
    }

    public function addProyecto()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {

            $decripcion = $this->input->post('inputDescPro');
            $tipoCentral = $this->input->post('selectTipoCentral');
            $tipoLabel = $this->input->post('selectTipoLabel');
            $idGerenciaProyecto = $this->input->post('selectGerenciaProyecto');

            $data = $this->m_proyecto->insertProyecto($decripcion, $tipoCentral, $tipoLabel, $idGerenciaProyecto);
            if ($data['error'] == EXIT_ERROR) {
                throw new Exception('Error al Insertar El proyecto');
            }

            //////////////////////////////28092018///////////////////////
            $data2 = $this->m_proyecto->insertLogProyecto('nuevo', '', $decripcion, $this->session->userdata('usernameSession'));
            if ($data2['error'] == EXIT_ERROR) {
                throw new Exception('Error al Insertar El proyecto');
            }

            $data['tbProyecto'] = $this->makeHTLMTablaProyecto($this->m_utils->getAllProyectoDesc());
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function getInfoProyecto()
    {

        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $id = $this->input->post('idProyecto');
            $proyecto = $this->m_proyecto->getProyectoInfo($id);

            $data['proyecto'] = $proyecto['proyectoDesc'];
            $data['central'] = $proyecto['idTipoCentral'];
            $data['label'] = $proyecto['idTipoLabel'];
            $data['idGerencia'] = $proyecto['idGerencia'];
            //$data['tbProyecto'] = $this->makeHTLMTablaProyecto($this->m_utils->getAllProyectoDesc());
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function updateProyecto()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {

            $decripcion = $this->input->post('inputDescPro2');
            $idTipocentral = $this->input->post('selectTipoCentral2');
            $tipoLabel = $this->input->post('selectTipoLabel2');
            $idGerencia = $this->input->post('selectGerenciaProyecto2');

            $id = $this->input->post('id');
            $data = $this->m_proyecto->editarProyecto($id, $idTipocentral, $tipoLabel, $decripcion, $idGerencia);
            //log_message('error', $id . '-' . $idTipocentral . '-');
            if ($data['error'] == EXIT_ERROR) {
                throw new Exception('Error al Insertar createCentral');
            }

            ////////////////////////////28092018////////////////////////
            $data2 = $this->m_proyecto->insertLogProyecto('update', $id, $decripcion, $this->session->userdata('usernameSession'));
            if ($data2['error'] == EXIT_ERROR) {
                throw new Exception('Error al Insertar El proyecto');
            }

            $data['tbProyecto'] = $this->makeHTLMTablaProyecto($this->m_utils->getAllProyectoDesc());
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function existeSubPepArea()
    {
        $subpro = $this->input->post('subpro');
        $area = $this->input->post('area');
        $pep = $this->input->post('pep');
        $cant = null;
        if ($subpro != null && $area != null && $pep != null) {
            $res = $this->m_proyecto->existPepSubArea($subpro, $area, $pep);
            $cant = $res->num_rows() == 1 ? ($res->row()->cant >= 1 ? '1' : '0') : '0';
        } else {
            $cant = '1'; //Si hay un error que simule que si existe
        }
        echo $cant;
    }

    public function addSubProyecto()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {

            $idProyecto = $this->input->post('selectProyecto2');
            $subProDesc = $this->input->post('inputDescSubPro');
            $tiempo = $this->input->post('selectTiempo');
            $planta = $this->input->post('selectTipoPlanta');
            //$areas = $this->input->post('selectAreas');
            $estaciones = $this->input->post('estaciones');
            $chbxFichaTecnica = $this->input->post('checkFichaTec') ? $this->input->post('checkFichaTec') : null;
            $idComplejidad = $this->input->post('idComplejidad') ? $this->input->post('idComplejidad') : null;
            $idTipoSubProyecto = $this->input->post('idTipoSubProyecto') ? $this->input->post('idTipoSubProyecto') : null;

            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;

            $idFichaTecnica = null;
            $arrayInsertGlobal = array();
            $arrayInsertSwithFormulario = array();
            $idSubProyNew = null;

            $this->db->trans_begin();

            if ($idUsuario == null) {
                throw new Exception('Su sesion ha expirado, ingrese nuevamente!!');
            }

            $flgExisteSubProy = $this->m_utils->countSubProyByDesc(strtoupper($subProDesc));

            if ($flgExisteSubProy > 0) {
                throw new Exception('Ya existe un subrpoyecto con ese nombre, ingrese otro porfavor!!');
            }

            $data = $this->m_proyecto->insertSubProyecto($idProyecto, $subProDesc, $tiempo, $planta, $estaciones, $idComplejidad, $idTipoSubProyecto);
            $idSubProyNew = $data['idSubProyectoNew'];

            $arrayZonales = $this->m_proyecto->getAllIdZonales();
            if ($data['error'] == EXIT_SUCCESS) {

                $stringEstaciones = $this->m_proyecto->getEstacionesByIdEstacionArea($estaciones);
                if ($stringEstaciones != null) {
                    $arrayEstaciones = explode(',', $stringEstaciones);
                    $arrayEstaciones = array_unique($arrayEstaciones);

                    if (count($arrayEstaciones) > 0) {
                        if ($chbxFichaTecnica == 1) { //FICHA GENERICA
                            foreach ($arrayEstaciones as $row) {
                                $arrayInsertFicha = array();
                                if ($row == ID_ESTACION_FO) {
                                    $arrayInsertFicha['id_ficha_tecnica_base'] = FICHA_FO_FTTH_Y_OP;
                                    $arrayInsertFicha['idEstacion'] = $row;
                                    $arrayInsertFicha['idSubProyecto'] = $data['idSubProyectoNew'];
                                    array_push($arrayInsertGlobal, $arrayInsertFicha);
                                } else if ($row == ID_ESTACION_COAXIAL || $row == 19) {
                                    $arrayInsertFicha['id_ficha_tecnica_base'] = FICHA_COAXIAL_GENERICA;
                                    $arrayInsertFicha['idEstacion'] = $row;
                                    $arrayInsertFicha['idSubProyecto'] = $data['idSubProyectoNew'];
                                    array_push($arrayInsertGlobal, $arrayInsertFicha);
                                }
                            }
                        } else if ($chbxFichaTecnica == 2 || $chbxFichaTecnica == 3) {

                            foreach ($arrayEstaciones as $row) {
                                $arrayInsertFicha = array();
                                if ($row == ID_ESTACION_FO) {
                                    $arrayInsertFicha['id_ficha_tecnica_base'] = ($chbxFichaTecnica == 2 ? FICHA_FO_SISEGOS_SMALLCELL_EBC : FICHA_FO_OBRAS_PUBLICAS);
                                    $arrayInsertFicha['idEstacion'] = $row;
                                    $arrayInsertFicha['idSubProyecto'] = $data['idSubProyectoNew'];
                                    array_push($arrayInsertGlobal, $arrayInsertFicha);
                                }
                            }

                            foreach ($arrayZonales as $row) {

                                $arrayInsertSW = array();
                                $arrayInsertSW['idSubProyecto'] = $data['idSubProyectoNew'];
                                $arrayInsertSW['idZonal'] = $row->idZonal;

                                if ($chbxFichaTecnica == 2) { //SISEGO
                                    $arrayInsertSW['flg_tipo'] = 1;
                                } else if ($chbxFichaTecnica == 3) { //OBRA PUBLICA
                                    $arrayInsertSW['flg_tipo'] = 2;
                                }
                                array_push($arrayInsertSwithFormulario, $arrayInsertSW);
                            }
                            if (count($arrayInsertSwithFormulario) > 0) {
                                $data = $this->m_proyecto->insertSwitchFormulario($arrayInsertSwithFormulario);
                            }
                        } else {
                            $arrayInsertLogSubProy = array(
                                "idSubProyecto" => $idSubProyNew,
                                "idUsuario" => $idUsuario,
                                "fecha_registro" => date("Y-m-d H:i:s"),
                                "actividad" => 'insert',
                            );
                            $data = $this->m_proyecto->insertarLogSubProy($arrayInsertLogSubProy);
                            if ($data['error'] == EXIT_SUCCESS) {
                                $this->db->trans_commit();
                            }
                        }

                        $this->m_proyecto->insertPartidaLicencia($idSubProyNew, $idTipoSubProyecto, $planta);
                    }
                }
                if (count($arrayInsertGlobal) > 0 && ($chbxFichaTecnica != null || $chbxFichaTecnica != 4)) {

                    $data = $this->m_proyecto->insertFichaTecSubProEstacion($arrayInsertGlobal);
                    if ($data['error'] == EXIT_SUCCESS) {
                        $arrayInsertLogSubProy = array(
                            "idSubProyecto" => $idSubProyNew,
                            "idUsuario" => $idUsuario,
                            "fecha_registro" => date("Y-m-d H:i:s"),
                            "actividad" => 'insert',
                            "idTipoSubProyecto" => $idTipoSubProyecto
                        );
                        $data = $this->m_proyecto->insertarLogSubProy($arrayInsertLogSubProy);
                    }
                }

                if ($data['error'] == EXIT_SUCCESS) {
                    $this->db->trans_commit();
                }
            } else {
                throw new Exception('Error al Insertar El proyecto');
            }

            $data['tbSubproyecto'] = $this->makeHTLMTablaSubPro($this->m_utils->getAllSubProyectoDesc());
        } catch (Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function getInfoSubProyecto()
    {

        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $id = $this->input->post('idSubProyecto');
            $subProyecto = $this->m_proyecto->getSubProyectoInfo($id);

            $data['descripcion'] = utf8_decode($subProyecto['subProyectoDesc']);
            $data['tiempo'] = $subProyecto['tiempo'];
            $data['proyecto'] = $subProyecto['idProyecto'];
            $data['tipoPlanta'] = $subProyecto['idTipoPlanta'];
            $data['areas'] = $subProyecto['estaciones'];
            $data['cmbTipoComplejidad'] = $this->makeHTMLTipoComplejidad($this->m_utils->getAllTipoComplejidad(), $subProyecto['idTipoComplejidad']);
            $data['cmbTipoSubProyecto'] = $this->makeHTMLTipoSubProyecto($this->m_utils->getAllTipoSubProyecto(), $subProyecto['idTipoSubProyecto']);

            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function updateSubProyecto()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {

            $estacionarea = $this->input->post('estaciones');
            $idTipoPlanta = $this->input->post('selectTipoPlanta2');
            $tiempo = $this->input->post('selectTiempo2');
            $descripcion = $this->input->post('inputDescSubPro2');
            $idProyecto = $this->input->post('selectProyecto3');
            $oldSubPro = $this->input->post('oldSubPro');
            $id = $this->input->post('id');
            $idComplejidad = $this->input->post('idComplejidad') ? $this->input->post('idComplejidad') : null;
            $idTipoSubProyecto = $this->input->post('idTipoSubProyecto') ? $this->input->post('idTipoSubProyecto') : null;

            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;

            $this->db->trans_begin();

            if ($idUsuario == null) {
                throw new Exception('Su sesion ha expirado, ingrese nuevamente!!');
            }

            $data = $this->m_proyecto->editarSubProyecto($id, $idProyecto, $descripcion, $tiempo, $idTipoPlanta, $estacionarea, $oldSubPro, $idComplejidad, $idTipoSubProyecto);

            if ($data['error'] == EXIT_ERROR) {
                throw new Exception('Error al Insertar createCentral');
            } else {
                $arrayInsertLogSubProy = array(
                    "idSubProyecto" => $id,
                    "idUsuario" => $idUsuario,
                    "fecha_registro" => date("Y-m-d H:i:s"),
                    "actividad" => 'update',
                    "idTipoSubProyecto" => $idTipoSubProyecto
                );
                $data = $this->m_proyecto->insertarLogSubProy($arrayInsertLogSubProy);
                if ($data['error'] == EXIT_SUCCESS) {
                    $this->db->trans_commit();
                    $data['tbSubproyecto'] = $this->makeHTLMTablaSubPro($this->m_utils->getAllSubProyectoDesc());
                }
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function getSubproActividades()
    {
        $idSubproyecto = $this->input->post('idSubProyecto');

        $data['tbSubProyectoActividad'] = $this->makeHTLMTablaActividadSubPro($this->m_proyecto->getAllSubproyectoActividad($idSubproyecto));

        echo json_encode(array_map('utf8_encode', $data));
    }

    public function addActiviadSubproyecto()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {

            $actividades = $this->input->post('actividades');
            $idSubProyecto = $this->input->post('idSubProyecto');

            $data = $this->m_proyecto->insertActividad($actividades, $idSubProyecto);
            if ($data['error'] == EXIT_ERROR) {
                throw new Exception('Error al Insertar El proyecto');
            }

            $data['tbSubProyectoActividad'] = $this->makeHTLMTablaActividadSubPro($this->m_proyecto->getAllSubproyectoActividad($idSubProyecto));
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function getInfoSubProyectoActividad()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $id = $this->input->post('idSubProyectoAct');
            $subProyecto = $this->m_proyecto->getSubProyectoActividadInfo($id);
            $data['idSubProyectoActividad'] = $id;
            $data['idSubProyecto'] = $subProyecto['idSubProyecto'];
            $data['idActividad'] = $subProyecto['idActividad'];
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function updateProyectoActividad()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $idSubProyecto = $this->input->post('selectSubProyecto2');
            $idActividad = $this->input->post('selectActividad');
            $id = $this->input->post('id');
            $exist = $this->m_proyecto->existSubProyectoActividad($idSubProyecto, $idActividad);
            if (!is_null($exist)) {
                throw new Exception('Ya se encuentra asociada esta actividad');
            }
            $data = $this->m_proyecto->editarSubProyectoActividad($id, $idSubProyecto, $idActividad);
            if ($data['error'] == EXIT_ERROR) {
                throw new Exception('Error al Insertar createCentral');
            }
            $data['tbSubProyectoActividad'] = $this->makeHTLMTablaActividadSubPro($this->m_proyecto->getAllSubproyectoActividad($idSubProyecto));
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    /////////////////////////////////////28092018///////////////////////////
    public function validaNombreSubProyecto()
    {
        try {
            $proyecto = $this->input->post('proyecto');
            $resultado = $this->m_proyecto->existeNombreProy($proyecto);
            $cant = $resultado;

            if ($cant >= 1) {
                $cant = 1;
            } else {
                $cant = 0;
            }
        } catch (Exception $e) {
            $cant = 1;
        }
        echo $cant;
    }

    public function getComboComplejidad()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $data['cmbTipoComplejidad'] = $this->makeHTMLTipoComplejidad($this->m_utils->getAllTipoComplejidad());
            $data['cmbTipoSubProyecto'] = $this->makeHTMLTipoSubProyecto($this->m_utils->getAllTipoSubProyecto());

            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function makeHTMLTipoComplejidad($listaComplejidad, $idTipoComplejidad = null)
    {
        $html = '<option>&nbsp;</option>';

        foreach ($listaComplejidad as $row) {
            $selected = ($row->idTipoComplejidad == $idTipoComplejidad) ? 'selected' : null;
            $html .= '<option value="' . $row->idTipoComplejidad . '" ' . $selected . ' >' . $row->complejidadDesc . '</option>';
        }

        return utf8_decode($html);
    }

    public function makeHTMLTipoSubProyecto($listaTipoSubProy, $idTipoSubProyecto = null)
    {
        $html = '<option>&nbsp;</option>';

        foreach ($listaTipoSubProy as $row) {
            $selected = ($row->id_tipo_subproyecto == $idTipoSubProyecto) ? 'selected' : null;
            $html .= '<option value="' . $row->id_tipo_subproyecto . '" ' . $selected . ' >' . $row->descripcion . '</option>';
        }

        return utf8_decode($html);
    }

    public function makeHTMLTipoFactorMedicion($listaTipoFactorMedicion, $idPqtTipoFactorMedicion = null)
    {
        $html = '<option>Hola</option>';

        foreach ($listaTipoFactorMedicion as $row) {
            //             $selected = ($row->idPqtTipoFactorMedicion == $idPqtTipoFactorMedicion) ? 'selected' : null;
            //             $html .= '<option value="' . $row->idPqtTipoFactorMedicion . '" ' . $selected . ' >' . $row->descPqtTipoFactorMedicion . '</option>';
            $html .= $row->idPqtTipoFactorMedicion . 'X';
        }

        return utf8_decode($html);
    }
}
