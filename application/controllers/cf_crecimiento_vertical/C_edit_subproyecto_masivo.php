<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_edit_subproyecto_masivo extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_crecimiento_vertical/m_bandeja_edit_cv');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index()
    {
        $logedUser = $this->session->userdata('usernameSession');
        $idEcc = $this->session->userdata('eeccSession');
        if ($logedUser != null) {
            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $permisos = $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_CV, ID_PERMISO_HIJO_EDITAR_SUBPROYECTO_MASIVO);
            $data['title'] = 'EDITAR SUBPROYECTO MASIVO CV';
            $data['opciones'] = $result['html'];
            $this->load->view('vf_crecimiento_vertical/v_edit_subproyecto_masivo', $data);
        } else {
            redirect('login', 'refresh');
        }
    }

    public function getComboPtr()
    {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            $cmbPtr = null;
            $itemplan = $this->input->post('itemplan');

            if ($itemplan == null) {
                throw new Exception('ND');
            }
            $arrayData = $this->m_utils->getPtrByItemplan($itemplan);

            $cmbPtr .= "<option value=''>Seleccionar Ptr</option>";
            foreach ($arrayData as $row) {
                if ($row->ptr != null && $row->ptrEstacion != null) {
                    $data['empresacolab'] = $row->empresaColabDesc;
                    $data['jefatura'] = $row->jefatura;
                    $dataAlmCen = explode('|', $row->dataJefaturaEmp);
                    $data['codAlmacen'] = $dataAlmCen[0];
                    $data['codCentro'] = $dataAlmCen[1];
                    $data['idEmpresaColab'] = $dataAlmCen[3];
                    $data['idJefatura'] = $dataAlmCen[2];
                    $data['vr'] = $row->vr;
                    $cmbPtr .= "<option value='" . $row->ptr . "_" . $row->est_innova . "'>$row->ptrEstacion</option>";
                }
            }
            $data['error'] = EXIT_SUCCESS;
            $data['cmbPtr'] = $cmbPtr;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function getVr()
    {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            $ptr_estado = $this->input->post('ptr');
            $ptr = explode('_', $ptr_estado);
            if ($ptr == null) {
                throw new Exception('ptr registrado');
            }
            $vr = $this->m_utils->getVrByPtr($ptr[0]);
            $data['error'] = EXIT_SUCCESS;
            $data['vr'] = $vr;

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function insertSap()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $file = $_FILES["file"]["name"];
            $filetype = $_FILES["file"]["type"];
            $filesize = $_FILES["file"]["size"];
            $tmp = $_FILES["file"]["tmp_name"];

            $idPersonaSession = $this->session->userdata('idPersonaSession');
            $usuarioAdju = $this->m_bandeja_edit_cv->getUserName($idPersonaSession);

            $file2 = utf8_decode($file);
            $ubicacion = 'uploads/edit_subproyecto_masivo';
            if (!is_dir($ubicacion)) {
                mkdir('uploads/edit_subproyecto_masivo', 0777);
            }
            if (move_uploaded_file($_FILES['file']['tmp_name'], $ubicacion . "/" . $file2)) {

            } else {
                throw new Exception('ND');
            }

            $handle = fopen($ubicacion . "/" . $file2, "r");
            $linea = fgets($handle);
            $arrayData = array();
            $comp = preg_split("/[\t]/", $linea);
            $count = 0;

            while ($line = fgets($handle)) {

                $count++;
                $comp = preg_split("/[\t]/", $line);
                $countColumn = count($comp);

                if (strlen(trim($comp[0])) == 13) {
                    array_push($arrayData, trim($comp[0]));
                }
            }
            if(count($arrayData) == 0){
                throw new Exception('Error al subir el documento, formato incorrecto');
            }

            $arrayItemplans = $this->m_bandeja_edit_cv->getItemplanMasivoCV($arrayData);

            if (count($arrayItemplans) > 0) {

                foreach ($arrayItemplans as $row) {

                    $idSubProyectoNew = null;
                    $idEstadoPlanNew = null;
                    $idEmpresaColabNew = null;

                    if ($row->avance >= 50) {
                        if ($row->idSubProyecto == ID_SUB_PROYECTO_CV_BUCLE && ($row->idEstadoPlan != 10)) {
                            $idSubProyectoNew = ID_SUB_PROYECTO_CV_INTEGRAL;
                            $idEmpresaColabNew = $row->idEmpresaColabCV;
                            $idEstadoPlanNew = 3;
                        } else if ($row->idSubProyecto == ID_SUB_PROYECTO_CV_INTEGRAL && ($row->idEstadoPlan == 2 || $row->idEstadoPlan == 3)) {
                            $idSubProyectoNew = ID_SUB_PROYECTO_CV_BUCLE;
                            $idEmpresaColabNew = $row->idEmpresaColab;
                            $idEstadoPlanNew = 2;

                            $countPreDiseno = $this->m_bandeja_edit_cv->getCountPreDisenoByItemplan($row->itemPlan);
                            $fecha = date('Y-m-d H:i:s');
                            $nuevafecha = strtotime('+7 day', strtotime($fecha));
                            $nuevafecha = date('Y-m-d H:i:s', $nuevafecha);

                            if ($countPreDiseno == 0) {

                                $arrayInsertPrediseno = array(
                                    "itemPlan" => $row->itemPlan,
                                    "fecha_adjudicacion" => $nuevafecha,
                                    "estado" => 2,
                                    "idEstacion" => 5,
                                    "fecha_prevista_atencion" => $fecha,
                                    "usuario_adjudicacion" => $usuarioAdju,
                                    "usuario_ejecucion" => "",
                                );

                                $data = $this->m_bandeja_edit_cv->insertarPreDiseno($arrayInsertPrediseno);
                            } else {
                                $arrayUpdate = array(
                                    "fecha_adjudicacion" => $nuevafecha,
                                );
                                $data = $this->m_bandeja_edit_cv->updatePreDiseno($row->itemPlan, $arrayUpdate);
                            }
                        }

                        $arrayUpdatePlanObra = array(
                            "idSubProyecto" => $idSubProyectoNew,
                            "idEmpresaColab" => $idEmpresaColabNew,
                            "idEstadoPlan" => $idEstadoPlanNew,
                            "idEmpresaColabDiseno" => $idEmpresaColabNew,
                        );

                        $data = $this->m_bandeja_edit_cv->updatePlanObra($row->itemPlan, $arrayUpdatePlanObra);

                        if ($data['error'] == EXIT_SUCCESS) {
                            $arrayInsertLogPlanObra = array(
                                "tabla" => "planobra",
                                "actividad" => "ingresar",
                                "itemplan" => $row->itemPlan,
                                "itemplan_default" => 'idSubproyecto=' . $idSubProyectoNew . '|AUTOMATICO',
                                "fecha_registro" => date("Y-m-d H:i:s"),
                                "id_usuario" => $idPersonaSession,
                            );
                            $data = $this->m_bandeja_edit_cv->insertarLogPlanObra($arrayInsertLogPlanObra);
                        }
                    }
                }
            } else {
                throw new Exception('La listado de itemplan no cumple con las reglas necesarias para el cambio de subproyecto!!');
            }

            $data['tablaBloc'] = $this->tablaBlocNotas($arrayItemplans);

            fclose($handle);

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function tablaBlocNotas($arrayItemplans)
    {
        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>ITEMPLAN</th>
                            <th>SUBPROYECTO</th>
                            <th>PROYECTO</th>
                            <th>MDF/NODO</th>
                            <th>ZONAL</th>
                            <th>EECC/th>
                            <th>% AVANCE</th>
                            <th>ESTADO</th>
                        </tr>
                    </thead>
                    <tbody>';

        $style = null;

        foreach ($arrayItemplans as $row) {

            if ($row->avance < 50 || $row->idEstadoPlan == 10) {
                $style = '#ff0000';
            } else {
                $style = '#66ff66';
            }
            $html .= '   <tr>
                            <td style="background:' . $style . '">' . $row->itemPlan . '</td>
                            <td style="background:' . $style . '">' . $row->subProyectoDesc . '</td>
                            <td style="background:' . $style . '">' . $row->nombreProyecto . '</td>
                            <td style="background:' . $style . '">' . $row->codigo . ' - ' . $row->tipoCentralDesc . '</td>
                            <td style="background:' . $style . '">' . $row->zonalDesc . '</td>
                            <td style="background:' . $style . '">' . $row->empresaColabDesc . '</td>
                            <td style="background:' . $style . '; text-align:center">' . $row->avance . '</td>
                            <td style="background:' . $style . '">' . $row->estadoPlanDesc . '</td>
                        </tr>';
        }
        $html .= '</tbody>
            </table>';
        return utf8_decode($html);
    }

    public function fechaActual()
    {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone', 'America/Lima');
        setlocale(LC_TIME, "es_ES", "esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }
}
