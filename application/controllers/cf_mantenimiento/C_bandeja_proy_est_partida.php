<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_bandeja_proy_est_partida extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
		$this->load->library('excel');
        $this->load->helper('url');
    }

    public function index()
    {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {
            // Trayendo zonas permitidas al usuario
            $arrayIdActidad = (isset($_GET['arryAct']) ? $_GET['arryAct'] : '');
            if ($arrayIdActidad != null) {
                $listaProyEstPart = $this->m_utils->getAllProyEstPartida(null, null, null, $arrayIdActidad);
            } else {
                $listaProyEstPart = '';
            }
            $zonas = $this->session->userdata('zonasSession');
            $data['listaProy'] = $this->m_utils->getAllProyecto();
            $data['listaEstaciones'] = $this->m_utils->getEstacion();
            $data['listaPartidas'] = $this->m_utils->getPartidas();

            $idPersonaSession = $this->session->userdata('idPersonaSession');
            $descPerfilSesion = $this->session->userdata('descPerfilSession');

            $data['tablaProyEstPart'] = $this->makeHTLMTablaConsulta($listaProyEstPart);

            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $permisos = $this->session->userdata('permisosArbol');
            #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_MANTENIMIENTO, ID_PERMISO_HIJO_MANT_PROY_EST_PARTIDA);
			$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_MANTENIMIENTO_CERTIFICACION, ID_PERMISO_HIJO_MANT_PROY_EST_PARTIDA, ID_MODULO_MANTENIMIENTO);
            $data['opciones'] = $result['html'];
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_mantenimiento/v_bandeja_proy_est_partida', $data);
            } else {
                redirect('login', 'refresh');
            }
        } else {

            redirect('login', 'refresh');
        }

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
                        $data['tbProyEstPart'] = $this->makeHTLMTablaConsulta($this->m_utils->getAllProyEstPartida($idProyecto, $idEstacion, $idPartida));
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

    public function updateProyEstPart()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $id = $this->input->post('id') ? $this->input->post('id') : null;
            $idProyecto = $this->input->post('idProyecto') ? $this->input->post('idProyecto') : null;
            $idEstacion = $this->input->post('idEstacion') ? $this->input->post('idEstacion') : null;
            $idPartida = $this->input->post('idPartida') ? $this->input->post('idPartida') : null;
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;

            $this->db->trans_begin();

            if ($idUsuario == null) {
                throw new Exception('Su sesion ha experiado, ingrese nuevamente!!');
            }

            if ($id == null || $idProyecto == null || $idEstacion == null || $idPartida == null) {
                throw new Exception('Hubo un error al traer los datos a registrar, intentelo de nuevo!!');
            }

            $arrayUpdateProyEstPart = array(
                "idProyecto" => $idProyecto,
                "idEstacion" => $idEstacion,
                "idPartida" => $idPartida,
            );

            $data = $this->m_utils->updateProyEstPart($id, $arrayUpdateProyEstPart);
            if ($data['error'] == EXIT_SUCCESS) {
                $arrayInsertLogGlob = array();
                $arrayInsertLog = array(
                    "id" => $id,
                    "actividad" => 'update',
                    "idProyecto" => $idProyecto,
                    "idEstacion" => $idEstacion,
                    "idPartida" => $idPartida,
                    "id_usuario" => $idUsuario,
                    "fecha_registro" => $this->m_utils->fechaActual(),
                );
                array_push($arrayInsertLogGlob, $arrayInsertLog);

                $data = $this->m_utils->insertarLogProyEstPart($arrayInsertLogGlob);
                if ($data['error'] == EXIT_SUCCESS) {
                    $this->db->trans_commit();
                    $data['tbProyEstPart'] = $this->makeHTLMTablaConsulta($this->m_utils->getAllProyEstPartida($idProyecto, $idEstacion, $idPartida));
                }
            }

        } catch (Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function makeHTLMTablaConsulta($listaProyEstPart)
    {
        $html = '
                <table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th style="text-align: center">#</th>
                            <th style="text-align: center">PARTIDA</th>
                            <th style="text-align: center">PROYECTO</th>
                            <th style="text-align: center">ESTACION</th>
                            <th style="text-align: center">ACCI&Oacute;N</th>
                        </tr>
                    </thead>

                    <tbody>';
        $count = 1;

        if ($listaProyEstPart != '') {
            foreach ($listaProyEstPart as $row) {

                $html .= '
                        <tr>
                            <td style="text-align: center">' . $count . '</td>
                            <td>' .$row->codigo.' | '. strtoupper($row->descripcion) . '</td>
                            <td style="text-align: center">' . utf8_decode($row->proyectoDesc) . '</td>
                            <td style="text-align: center">' . utf8_decode($row->estacionDesc) . '</td>
                            <th style="text-align: center">
                                <!-- a style="color:var(--verde_telefonica)" data-id="' . $row->id . '"  onclick="openEditProyEstPart(this)"><i class="zmdi zmdi-hc-2x zmdi-edit"></i></a -->
                                <a style="color:var(--verde_telefonica)" data-id="' . $row->id . '"  onclick="openModalConfiDelete(this)"><i class="zmdi zmdi-hc-2x zmdi-delete"></i></a>
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

    public function filtrarTabla()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $idProyecto = $this->input->post('proyecto') ? $this->input->post('proyecto') : null;
            $idEstacion = $this->input->post('estacion') ? $this->input->post('estacion') : null;
            $idPartida = $this->input->post('partida') ? $this->input->post('partida') : null;

            $data['tablaProyEstPart'] = $this->makeHTLMTablaConsulta($this->m_utils->getAllProyEstPartida($idProyecto, $idEstacion, $idPartida));
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function getDetProEstPart()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $id = $this->input->post('id') ? $this->input->post('id') : null;

            if ($id == null) {
                throw new Exception('Hubo un error al traer el Proy Est Partida!!');
            }
            $arrayProyEstPart = $this->m_utils->getAllProyEstPartida(null, null, null, $id, 1);
            $data['cmbProyecto'] = $this->makeCmbProyecto($this->m_utils->getAllProyecto(), $arrayProyEstPart['idProyecto']);
            $data['cmbEstacion'] = $this->makeCmbEstacion($this->m_utils->getEstacion(), $arrayProyEstPart['idEstacion']);
            $data['cmbPartida'] = $this->makeCmbPartida($this->m_utils->getPartidas(), $arrayProyEstPart['idPartida']);
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
            $data['cmbPartida'] = $this->makeCmbPartida($this->m_utils->getPartidas());
            $data['cmbProyecto'] = $this->makeCmbProyecto($this->m_utils->getAllProyecto());
            // $data['cmbEstacion'] = $this->makeCmbEstacion($this->m_utils->getEstacion());

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
            $html .= '<option value="' . $row->idActividad . '" ' . $selected . ' >' . $row->codigo . '  |  ' . $row->descripcion . '</option>';
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

    public function getEstacionesByPartProy()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $idProyecto = $this->input->post('idProyecto') ? $this->input->post('idProyecto') : null;
            $idPartida = $this->input->post('idPartida') ? $this->input->post('idPartida') : null;

            if ($idProyecto == null || $idPartida == null) {
                throw new Exception('Hubo un error en recibir los datos!!');
            }
            $data['cmbEstacion'] = $this->makeCmbEstacion($this->m_utils->getEstacionByPartProy($idPartida, $idProyecto));

            $data['error'] = EXIT_SUCCESS;

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
	
	function insertPartidasMasiva() {
        $data['msj'] = '';
        $data['error'] = EXIT_ERROR;
        try {
            $arrayJson = array();
			$arrayPartidasNoExit = array();
            $cont = 0;
            $idEstacion = null;
            $flgValida = 0;
            $idProyecto = $this->input->post('idProyecto');
            $idEstacion = $this->input->post('idEstacion');
			$flgPartidasNoExist = 0;
            if($this->session->userdata('idPersonaSession') == null) {
                throw new Exception('La sesi&oacute;n a expirado, recargue la p&aacute;gina');
            }

            if($idProyecto == null || $idProyecto == '') {
                throw new Exception('Debe seleccionar proyecto');
            }

            if($idEstacion == null || $idEstacion == '') {
                throw new Exception('Debe seleccionar estaci&oacute;n');
            }

            if(isset($_FILES['file']['name'])) {
                $path   = $_FILES['file']['tmp_name'];
                $object = PHPExcel_IOFactory::load($path);
                foreach($object->getWorksheetIterator() as $worksheet) {
                    $highestRow    = $worksheet->getHighestRow();
                    $highestColumn = $worksheet->getHighestColumn();
                    for($row=2; $row<=$highestRow; $row++) {
                        $codPartida = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
						$idPartida  = $this->m_utils->getIdPartidaByCod($codPartida);
						
						if($idPartida == null || $idPartida == '') {
							$arrayPartidasNoExit[] = $codPartida;
						} else{
							$countExist = $this->m_utils->countProyEstPart($idProyecto, $idEstacion, $idPartida);
						
							if($countExist == 0) {
								$arrayJson[] = array(
														'idPartida'  => $idPartida,
														'idProyecto' => $idProyecto,
														'idEstacion' => $idEstacion
													);
							}  
						}
					}
                    if(count($arrayJson) > 0) {
						$data = $this->m_utils->insertMasivoPartidaProyecto($arrayJson);
					} else {
						throw new Exception('Las partidas ya estan asignadas al proyecto, verificar.');
					}
					
					if(count($arrayPartidasNoExit) > 0) {
						$data['msj'] = 'Se subieron las partidas excepto la partida '.implode("','",$arrayPartidasNoExit).' ya que no existe, verificar.';
					}
                    
                }
            } 
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
}
