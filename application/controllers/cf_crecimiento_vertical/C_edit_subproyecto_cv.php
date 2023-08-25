<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_edit_subproyecto_cv extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_plan_obra/m_consulta');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_crecimiento_vertical/m_bandeja_edit_cv');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index()
    {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {

            $data['tablaItemplan'] = $this->makeHTLMTablaConsulta('');
            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $permisos = $this->session->userdata('permisosArbol');
            #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_CV, ID_PERMISO_HIJO_EDITAR_SUBPROYECTO_CV);
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_NUEVO_MODELO_CRECIMIENTO_VERTICAL, ID_PERMISO_HIJO_EDITAR_SUBPROYECTO_CV, ID_MODULO_PAQUETIZADO);
            $data['opciones'] = $result['html'];
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_crecimiento_vertical/v_edit_subproyecto_cv', $data);
            } else {
                redirect('login', 'refresh');
            }
        } else {

            redirect('login', 'refresh');
        }

    }

    public function updateSubProyecto()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        try {

            $itemplan = $this->input->post('itemplan') ? $this->input->post('itemplan') : null;
            $idSubProyecto = $this->input->post('idSubProyecto') ? $this->input->post('idSubProyecto') : null;
            $idEmpresaColab = $this->input->post('idEmpresaColab') ? $this->input->post('idEmpresaColab') : null;
            $idEmpresaColabCV = $this->input->post('idEmpresaColabCV') ? $this->input->post('idEmpresaColabCV') : null;
            $idEstadoPlan = $this->input->post('idEstadoPlan') ? $this->input->post('idEstadoPlan') : null;
            

            $idPersonaSession = $this->session->userdata('idPersonaSession');

            $usuarioAdju = $this->m_bandeja_edit_cv->getUserName($idPersonaSession);
            

            $idSubProyectoNew = null;
            $idEstadoPlanNew = null;

            $arraySubProyIntegrales = array(97,98,396,463);
            
            
            $this->db->trans_begin();

            if ($itemplan == null && $idSubProyecto == null) {
                throw new Exception('Hubo un error al seleccionar el itemplan!!');
            }

            $arrayDetIP = $this->m_bandeja_edit_cv->getDetalleIPCV($itemplan);

            if ($idSubProyecto == ID_SUB_PROYECTO_CV_BUCLE) {
                $idSubProyectoNew = ID_SUB_PROYECTO_CV_INTEGRAL;
                $idEmpresaColabNew = $arrayDetIP['idEmpresaColabCV'];
                $idEstadoPlanNew = 3; // EN OBRA
            } else if ($idSubProyecto == ID_SUB_PROYECTO_CV_INTEGRAL) {
                $idSubProyectoNew = ID_SUB_PROYECTO_CV_BUCLE;
                $idEmpresaColabNew = $arrayDetIP['idEmpresaColab'];
                $idEstadoPlanNew = 2; // EN DISENO

            }else if ($idSubProyecto == ID_SUB_PROYECTO_CV_NEGOCIO_I_BUCLE){
                $idSubProyectoNew = ID_SUB_PROYECTO_CV_NEGOCIO_I_INTEGRAL;
                $idEmpresaColabNew = $arrayDetIP['idEmpresaColabCV'];
                $idEstadoPlanNew = 3; // EN OBRA
            }else if ($idSubProyecto == ID_SUB_PROYECTO_CV_NEGOCIO_I_INTEGRAL){
                $idSubProyectoNew = ID_SUB_PROYECTO_CV_NEGOCIO_I_BUCLE;
                $idEmpresaColabNew =$arrayDetIP['idEmpresaColab'];
                $idEstadoPlanNew = 2; // EN DISENO
            }else if ($idSubProyecto == ID_SUB_PROYECTO_CV_NEGOCIO_II_BUCLE){
                $idSubProyectoNew = ID_SUB_PROYECTO_CV_NEGOCIO_II_INTEGRAL;
                $idEmpresaColabNew = $arrayDetIP['idEmpresaColabCV'];
                $idEstadoPlanNew = 3; // EN OBRA
            }else if ($idSubProyecto == ID_SUB_PROYECTO_CV_NEGOCIO_II_INTEGRAL){
                $idSubProyectoNew = ID_SUB_PROYECTO_CV_NEGOCIO_II_BUCLE;
                $idEmpresaColabNew = $arrayDetIP['idEmpresaColab'];
                $idEstadoPlanNew = 2; // EN DISENO
            }else if ($idSubProyecto == ID_SUB_PROYECTO_CV_RESIDENCIAL_OVERLAY_BUCLE){
                $idSubProyectoNew = ID_SUB_PROYECTO_CV_RESIDENCIAL_OVERLAY_INTEGRAL;
                $idEmpresaColabNew = $arrayDetIP['idEmpresaColabCV'];
                $idEstadoPlanNew = 3; // EN OBRA
            }else if ($idSubProyecto == ID_SUB_PROYECTO_CV_RESIDENCIAL_OVERLAY_INTEGRAL){
                $idSubProyectoNew = ID_SUB_PROYECTO_CV_RESIDENCIAL_OVERLAY_BUCLE;
                $idEmpresaColabNew = $arrayDetIP['idEmpresaColab'];
                $idEstadoPlanNew = 2; // EN DISENO
            }

            $arrayUpdatePlanObra = array(
                "idSubProyecto" => $idSubProyectoNew,
                "idEmpresaColab" => $idEmpresaColabNew,
                "idEstadoPlan" => $idEstadoPlanNew,
                "idEmpresaColabDiseno" => $idEmpresaColabNew
            );
            //Evaluar si el empresacolabdiseño en planobra es nulo updatearle x el nuevo, sino no modificarle
            // $empresaColabDiseno = $this->m_bandeja_edit_cv->getEmpresaColabDiseno($itemplan);

            // if ($empresaColabDiseno == null) {
            //     $arrayUpdatePlanObra['idEmpresaColabDiseno'] = $idEmpresaColabNew;
            // }

            $data = $this->m_bandeja_edit_cv->updatePlanObra($itemplan, $arrayUpdatePlanObra);

            if ($data['error'] == EXIT_SUCCESS) {
                

                $arrayUpdatePlanObraDetCV = array(
                    "idSubProyecto" => $idSubProyectoNew,
                );
                $data = $this->m_bandeja_edit_cv->updatePlanObraDetCV($itemplan, $arrayUpdatePlanObraDetCV);
                
                if ($data['error'] == EXIT_SUCCESS) {
                    if (in_array($idSubProyecto, $arraySubProyIntegrales)) { //CUANDO ES UN SUBPROYECTO INTEGRAL Y PASA A BUCLE

                        $arryCountPreDiseno = $this->m_bandeja_edit_cv->getCountPreDisenoByItemplan($itemplan);
                        $fecha = date('Y-m-d H:i:s');
                        $nuevafecha = strtotime('+7 day', strtotime($fecha));
                        $nuevafecha = date('Y-m-d H:i:s', $nuevafecha);
                        $arrayInsertGlob = array();

                        if ($arryCountPreDiseno['conteo'] == 0) {

                            $arrayInsertPrediseno1 = array(
                                "itemPlan" => $itemplan,
                                "fecha_adjudicacion" => $nuevafecha,
                                "estado" => 2,
                                "idEstacion" => 5,
                                "fecha_prevista_atencion" => $fecha,
                                "usuario_adjudicacion" => $usuarioAdju,
                            );

                            $arrayInsertPrediseno2 = array(
                                "itemPlan" => $itemplan,
                                "fecha_adjudicacion" => $nuevafecha,
                                "estado" => 2,
                                "idEstacion" => 2,
                                "fecha_prevista_atencion" => $fecha,
                                "usuario_adjudicacion" => $usuarioAdju,
                            );

                            array_push($arrayInsertGlob, $arrayInsertPrediseno1, $arrayInsertPrediseno2);

                            $data = $this->m_bandeja_edit_cv->insertarPreDiseno($arrayInsertGlob);
                            
                            
                        } else {
                            $arrayEstaciones = explode(",", $arryCountPreDiseno['string_estaciones']);
                            if (count($arrayEstaciones) == 2) {
                                $arrayUpdate = array(
                                    "fecha_adjudicacion" => $nuevafecha,
                                );
                                $data = $this->m_bandeja_edit_cv->updatePreDiseno($itemplan, $arrayUpdate);
                            } else if (count($arrayEstaciones) == 1) {
                                $arrayInsertPrediseno = array(
                                    "itemPlan" => $itemplan,
                                    "fecha_adjudicacion" => $nuevafecha,
                                    "estado" => 2,
                                    "idEstacion" => $arrayEstaciones[0] == 5 ? 2 : 5,
                                    "fecha_prevista_atencion" => $fecha,
                                    "usuario_adjudicacion" => $usuarioAdju,
                                );
                                array_push($arrayInsertGlob, $arrayInsertPrediseno);
                                $data = $this->m_bandeja_edit_cv->insertarPreDiseno($arrayInsertGlob);
                            }

                        }

                    }else{

                        $arryCountPreDiseno = $this->m_bandeja_edit_cv->getCountPreDisenoByItemplan($itemplan);
                        if ($arryCountPreDiseno['conteo'] > 0) {
                            $data = $this->m_bandeja_edit_cv->deletePrediseno($itemplan, ID_ESTACION_COAXIAL);
                        }

                    }
                    if ($data['error'] == EXIT_SUCCESS) {
                        
                        $arrayInsertLogPlanObra = array(
                            "tabla" => "planobra",
                            "actividad" => "ingresar",
                            "itemplan" => $itemplan,
                            "itemplan_default" => 'idSubproyecto=' . $idSubProyectoNew . '|AUTOMATICO,INV',
                            "fecha_registro" => date("Y-m-d H:i:s"),
                            "id_usuario" => $idPersonaSession,
                        );
                        $data = $this->m_bandeja_edit_cv->insertarLogPlanObra($arrayInsertLogPlanObra);
                        
                        if ($data['error'] == EXIT_SUCCESS) {
                            $this->db->trans_commit();
                        }
                    }
                }
            }

            $data['tablaItemplan'] = $this->makeHTLMTablaConsulta($this->m_bandeja_edit_cv->getItemplanCV($itemplan));
            
        } catch (Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function makeHTLMTablaConsulta($listaItemplan)
    {
        $html = '
                <table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>ITEMPLAN</th>
                            <th>SUBPROYECTO</th>
                            <th>PROYECTO</th>
                            <th>MDF/NODO</th>
                            <th>ZONAL</th>
                            <th>EECC</th>
                            <th>% AVANCE</th>
                            <th>ESTADO</th>
                            <th>ACCI&Oacute;N</th>
                        </tr>
                    </thead>

                    <tbody>';

        $arraySubProyIntegrales = array(97,98,396,463);
        $arraySubProyBucles = array(96,99,395,464);

        if ($listaItemplan != '') {
            foreach ($listaItemplan as $row) {

                $html .= '
                        <tr>
                            <td>' . $row->itemPlan . '</td>
                            <td>' . $row->subProyectoDesc . '</td>
                            <td>' . $row->nombreProyecto . '</td>
                            <td>' . $row->codigo . ' - ' . $row->tipoCentralDesc . '</td>
                            <td>' . $row->zonalDesc . '</td>
                            <td>' . $row->empresaColabDesc . '</td>
                            <td style="text-align:center">' . $row->avance . '</td>
                            <td>' . $row->estadoPlanDesc . '</td>
                            <td style="text-align:center">
                                ' . ($row->avance >= 50 ?
                    (in_array($row->idSubProyecto, $arraySubProyBucles) && ($row->idEstadoPlan != 10) ?
                        '<a style="cursor:pointer; color: var(--verde_telefonica)" data-itemplan="' . $row->itemPlan . '"  data-estado="' . $row->idEstadoPlan . '" data-idsuproyect="' . $row->idSubProyecto . '" data-subproyec="' . $row->subProyectoDesc . '" data-ideecc="' . $row->idEmpresaColab . '" data-ideeccv="' . $row->idEmpresaColabCV . '" onclick="openModalDetItemplan(this)"><i class="zmdi zmdi-hc-2x zmdi-edit"></i></a>' :
                        (in_array($row->idSubProyecto, $arraySubProyIntegrales) && ($row->idEstadoPlan == 3) ?
                            '<a style="cursor:pointer; color: var(--verde_telefonica)" data-itemplan="' . $row->itemPlan . '"  data-estado="' . $row->idEstadoPlan . '" data-idsuproyect="' . $row->idSubProyecto . '" data-subproyec="' . $row->subProyectoDesc . '"  data-ideecc="' . $row->idEmpresaColab . '" data-ideeccv="' . $row->idEmpresaColabCV . '" onclick="openModalDetItemplan(this)"><i class="zmdi zmdi-hc-2x zmdi-edit"></i></a>' : '')) : '') . '
                            </td>
                        </tr>
                        ';
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

            $itemplan = $this->input->post('itemplan');

            $data['tablaItemplan'] = $this->makeHTLMTablaConsulta($this->m_bandeja_edit_cv->getItemplanCV($itemplan));

            $data['error'] = EXIT_SUCCESS;

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function verificaPTRCVByItemplan()
    {

        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['msjAviso'] = null;

        try {

            $itemplan = $this->input->post('itemplan') ? $this->input->post('itemplan') : null;
            $idSubProyecto = $this->input->post('idSubProyecto') ? $this->input->post('idSubProyecto') : null;
            $idEstadoPlan = $this->input->post('idEstadoPlan') ? $this->input->post('idEstadoPlan') : null;

            $arraySubProyIntegrales = array(97,98,396,463);
            $arraySubProyBucles = array(96,99,395,464);

            if ($itemplan == null || $idSubProyecto == null || $idEstadoPlan == null) {
                throw new Exception('Hubo un error al seleccionar el itemplan!!');
            }

            $countPTR = $this->m_bandeja_edit_cv->getCountPTRByItemplan($itemplan);

            $msjSuproyecto = (in_array($idSubProyecto, $arraySubProyBucles) ? "Integral" : (in_array($idSubProyecto, $arraySubProyIntegrales) ? "Bucle" : ""));

            if ($countPTR > 0) {
                // $data['msjAviso'] = "&iquestIP cuenta con PTR, debe liberar el itemplan de todas sus PTR para poder cambiar de subproyecto!!";
                throw new Exception('&iquestIP cuenta con PTR, debe liberar el itemplan de todas sus PTR para poder cambiar de subproyecto!!');
            } else {
                $data['msjAviso'] = "Se cambiara de subproyecto !!";
                $data['error'] = EXIT_SUCCESS;
            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

}
