<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_bandeja_partida_subproyecto extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_utils/m_utils');
		$this->load->library('excel');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index()
    {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {
            // Trayendo zonas permitidas al usuario
   
            $listaPartidaSubProy = $this->m_utils->getPartidaSubProy();

            $zonas = $this->session->userdata('zonasSession');
            $data['listaSubProy'] = $this->m_utils->getAllSubProyecto(2);
            $data['listaPartidas'] = $this->m_utils->getPartidas();

            $idPersonaSession = $this->session->userdata('idPersonaSession');
            $descPerfilSesion = $this->session->userdata('descPerfilSession');

            $data['tablaPartidaSubProy'] = $this->makeHTLMTablaConsulta($listaPartidaSubProy);

            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $permisos = $this->session->userdata('permisosArbol');
            #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_MANTENIMIENTO, ID_PERMISO_HIJO_MANT_PARTIDA_SUBPROY);
			$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_MANTENIMIENTO_CERTIFICACION, 297, ID_MODULO_MANTENIMIENTO);
			$data['opciones'] = $result['html'];
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_mantenimiento/v_bandeja_partida_subproyecto', $data);
            } else {
                redirect('login', 'refresh');
            }
        } else {

            redirect('login', 'refresh');
        }

    }

    public function registrarPartidaSubProy()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $idSubProyecto = $this->input->post('idSubProyecto') ? $this->input->post('idSubProyecto') : null;
            $idPartida = $this->input->post('idPartida') ? $this->input->post('idPartida') : null;
            
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;

            $this->db->trans_begin();

            if ($idUsuario == null) {
                throw new Exception('Su sesion ha experiado, ingrese nuevamente!!');
            }

            if ($idSubProyecto == null || $idPartida == null) {
                throw new Exception('Hubo un error al traer los datos a registrar, intentelo de nuevo!!');
            }

            $flgExistePartidaSubProy = $this->m_utils->countPartSubProyPin($idPartida,$idSubProyecto);

            $ids = array();
            $arrayInsertLogGlob = array();

            if ($flgExistePartidaSubProy == 0) {
                $arraySubProys = explode(",", $idSubProyecto);
                foreach ($arraySubProys as $row) {
                    $arrayInsertTemp = array(
                        "idActividad" => $idPartida,
                        "idSubProyecto" => $row
                    );
                    $data = $this->m_utils->insertarPartSubProyPin($arrayInsertTemp);
                    if ($data['error'] == EXIT_SUCCESS) {
                        $arrayInsertLogTemp = array(
                            "idPartida" => $idPartida,
                            "idSubProyecto" => $row,
                            "desc_actividad" => 'insert',
                            "id_usuario" => $idUsuario,
                            "fecha_registro" => $this->m_utils->fechaActual()
                        );
                        array_push($arrayInsertLogGlob, $arrayInsertLogTemp);
                    }
                }

                if (count($arrayInsertLogGlob) > 0) {

                    $data = $this->m_utils->insertarLogPartSubProy($arrayInsertLogGlob);
                    if ($data['error'] == EXIT_SUCCESS) {
                        $this->db->trans_commit();
                        $data['tbPartSubProy'] = $this->makeHTLMTablaConsulta($this->m_utils->getPartidaSubProy($idPartida));
                    }
                }

            } else {
                throw new Exception('Ya existe esta Partida con esos SubProyectos, ingrese otros por favor!!');
            }

        } catch (Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function updatePartSubProy()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            
            $idPartida = $this->input->post('idPartida') ? $this->input->post('idPartida') : null;
            $idSubProyecto = $this->input->post('idSubProyecto') ? $this->input->post('idSubProyecto') : null;
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;

            $this->db->trans_begin();

            if ($idUsuario == null) {
                throw new Exception('Su sesion ha experiado, ingrese nuevamente!!');
            }

            if ($idSubProyecto == null || $idPartida == null) {
                throw new Exception('Hubo un error al traer los datos a actualizar, intentelo de nuevo!!');
            }

            $arrayUpdatePartSubProy = array(
                "cantidad" => $cantidad
            );

            $data = $this->m_utils->updatePartSubProy($idPartida,$idSubProyecto, $arrayUpdatePartSubProy);
            if ($data['error'] == EXIT_SUCCESS) {
                $arrayInsertLogGlob = array();
                $arrayInsertLog = array(
                    "idPartida" => $idPartida,
                    "idSubProyecto" => $idSubProyecto,
                    "desc_actividad" => 'update',
                    "id_usuario" => $idUsuario,
                    "fecha_registro" => $this->m_utils->fechaActual()
                );
                array_push($arrayInsertLogGlob, $arrayInsertLog);

                $data = $this->m_utils->insertarLogPartSubProy($arrayInsertLogGlob);

                if ($data['error'] == EXIT_SUCCESS) {
                    $this->db->trans_commit();
                    $data['tbPartSubProy'] = $this->makeHTLMTablaConsulta($this->m_utils->getPartidaSubProy($idPartida,$idSubProyecto));
                }
            }

        } catch (Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function makeHTLMTablaConsulta($listaPartidaSubProy)
    {
        $html = '
                <table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th style="text-align: center">#</th>
                            <th style="text-align: center">PARTIDA</th>
                            <th style="text-align: center">SUBPROYECTO</th>
                            <th style="text-align: center">ACCI&Oacute;N</th>
                        </tr>
                    </thead>

                    <tbody>';
        $count = 1;

        if ($listaPartidaSubProy != '') {
            foreach ($listaPartidaSubProy as $row) {

                $html .= '
                        <tr>
                            <td style="text-align: center">' . $count . '</td>
                            <td>' .$row->codigo.' | '. strtoupper($row->actividad) . '</td>
                            <td style="text-align: center">' . utf8_decode($row->subProyectoDesc) . '</td>
                            <th style="text-align: center">
                                <a style="color:var(--verde_telefonica)" data-idpartida="' . $row->idActividad . '" data-idsubproyecto="' . $row->idSubProyecto . '"  onclick="openEditPartSubproy(this)"><i class="zmdi zmdi-hc-2x zmdi-edit"></i></a>
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
            $idSubProyecto = $this->input->post('subProyecto') ? $this->input->post('subProyecto') : null;
            $idPartida = $this->input->post('partida') ? $this->input->post('partida') : null;

            $data['tablaPartidaSubProy'] = $this->makeHTLMTablaConsulta($this->m_utils->getPartidaSubProy($idPartida, $idSubProyecto));
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function getDetPartSubProy()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $idPartida = $this->input->post('idPartida') ? $this->input->post('idPartida') : null;
            $idSubProyecto = $this->input->post('idSubProyecto') ? $this->input->post('idSubProyecto') : null;

            if ($idPartida == null || $idSubProyecto == null) {
                throw new Exception('Hubo un error al traer el detalle!!');
            }
            $arrayPartSubProy = $this->m_utils->getPartidaSubProy($idPartida, $idSubProyecto,1);
            $data['cmbPartida'] = $this->makeCmbPartida($this->m_utils->getPartidas(), $arrayPartSubProy['idPartida']);
            $data['cmbSubProy'] = $this->makeCmbSubProyecto($this->m_utils->getAllSubProyecto(), $arrayPartSubProy['idSubProyecto']);
            $data['cantidad'] = $arrayPartSubProy['cantidad'];
            
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
            $data['error'] = EXIT_SUCCESS;

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function makeCmbSubProyecto($listaSubProyectos, $idSubProyecto = null)
    {
        $html = '<option>&nbsp;</option>';

        foreach ($listaSubProyectos->result() as $row) {
            $selected = ($row->idSubProyecto == $idSubProyecto) ? 'selected' : null;
            $html .= '<option value="' . $row->idSubProyecto . '" ' . $selected . ' >' . $row->subProyectoDesc . '</option>';
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

 

    public function getSubProyectosByPart()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $idPartida = $this->input->post('idPartida') ? $this->input->post('idPartida') : null;

            if ($idPartida == null) {
                throw new Exception('Hubo un error en recibir los datos!!');
            }
            $data['cmbSubProy'] = $this->makeCmbSubProyecto($this->m_utils->getSubProyByPartida($idPartida, 2));

            $data['error'] = EXIT_SUCCESS;

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
	
	function insertPartidasMasivaPin() {
		$data['msj'] = '';
        $data['error'] = EXIT_ERROR;
        try {
            $arrayJson = array();
			$arrayPartidasNoExit = array();
            $cont = 0;
            $idEstacion = null;
            $flgValida = 0;
            $idSubProyecto = $this->input->post('idSubProyecto');
			$flgPartidasNoExist = 0;
            if($this->session->userdata('idPersonaSession') == null) {
                throw new Exception('La sesi&oacute;n a expirado, recargue la p&aacute;gina');
            }

            if($idSubProyecto == null || $idSubProyecto == '') {
                throw new Exception('Debe seleccionar proyecto');
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
							$countExist = $this->m_utils->countPartSubProyPin($idPartida, $idSubProyecto);
						
							if($countExist == 0) {
								$arrayJson[] = array(
														'idActividad'  => $idPartida,
														'idSubProyecto' => $idSubProyecto
													);
							}  
						}
					}
                    if(count($arrayJson) > 0) {
						$data = $this->m_utils->insertMasivoPartidaSubProPin($arrayJson);
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
