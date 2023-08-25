<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_bandeja_material extends CI_Controller
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

            $data['tablaMaterial'] = $this->makeHTLMTablaConsulta($this->m_utils->getAllMateriales());
            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $permisos = $this->session->userdata('permisosArbol');
            #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_MANTENIMIENTO, ID_PERMISO_HIJO_MANT_MATERIAL);
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_MANTENIMIENTO_NUEVO_MODELO, ID_PERMISO_HIJO_MANT_MATERIAL, ID_MODULO_MANTENIMIENTO);
            $data['opciones'] = $result['html'];
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_mantenimiento/v_bandeja_material', $data);
            } else {
                redirect('login', 'refresh');
            }
        } else {

            redirect('login', 'refresh');
        }

    }

    public function registrarMaterial()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $codigoMaterial = $this->input->post('codigoMaterial') ? $this->input->post('codigoMaterial') : null;
            $descripMat = $this->input->post('descripMat') ? $this->input->post('descripMat') : null;
            $costoMat = $this->input->post('costoMat') ? $this->input->post('costoMat') : null;
            $estadoMaterial = $this->input->post('estadoMaterial') ? $this->input->post('estadoMaterial') : null;
            $tipoMaterial = $this->input->post('tipoMaterial') ? $this->input->post('tipoMaterial') : null;
            $unidad_medida = $this->input->post('unidadMedida') ? $this->input->post('unidadMedida') : null;
            $descUDM = $this->input->post('descUDM') ? $this->input->post('descUDM') : null;

            if ($codigoMaterial == null || $descripMat == null || $costoMat == null || $estadoMaterial == null || $tipoMaterial == null || $unidad_medida == null || $descUDM == null) {
                throw new Exception('Hubo un error al traer los datos a registrar, intentelo de nuevo!!');
            }

            $flgExisteMaterial = $this->m_utils->countMaterial($codigoMaterial);

            $arrayInsertGlob = array();

            if ($flgExisteMaterial == 0) {

                $arrayInsertMat = array(
                    "id_material" => $codigoMaterial,
                    "descrip_material" => strtoupper($descripMat),
                    "costo_material" => $costoMat,
                    "estado_material" => ($estadoMaterial == 1 ? 'Activo' : ($estadoMaterial == 2 ? 'Inactivo' : 'Phase Out')),
                    "flg_tipo" => ($tipoMaterial == 1 ? '1' : '0'),
                    "unidad_medida" => trim($descUDM),
                    "id_udm" => $unidad_medida,
                );

                $data = $this->m_utils->insertarMaterial($arrayInsertMat);
                if ($data['error'] == EXIT_SUCCESS) {
                    $data['tbMateriales'] = $this->makeHTLMTablaConsulta($this->m_utils->getAllMateriales());
                }

            } else {
                throw new Exception('Ya existe este material, ingrese otro por favor!!');
            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function updateMaterial()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $codigoMaterial = $this->input->post('codigoMaterial') ? $this->input->post('codigoMaterial') : null;
            $descripMat = $this->input->post('descripMat') ? $this->input->post('descripMat') : null;
            $costoMat = $this->input->post('costoMat') ? $this->input->post('costoMat') : null;
            $estadoMaterial = $this->input->post('estadoMaterial') ? $this->input->post('estadoMaterial') : null;
            $tipoMaterial = $this->input->post('tipoMaterial') ? $this->input->post('tipoMaterial') : null;
            $unidad_medida = $this->input->post('unidadMedida') ? $this->input->post('unidadMedida') : null;
            $descUDM = $this->input->post('descUDM') ? $this->input->post('descUDM') : null;

            if ($codigoMaterial == null || $descripMat == null || $costoMat == null || $estadoMaterial == null || $tipoMaterial == null || $unidad_medida == null || $descUDM == null) {
                throw new Exception('Hubo un error al traer los datos a registrar, intentelo de nuevo!!');
            }

            $arrayUpdateMat = array(
                "descrip_material" => strtoupper($descripMat),
                "costo_material" => $costoMat,
                "estado_material" => ($estadoMaterial == 1 ? 'Activo' : ($estadoMaterial == 2 ? 'Inactivo' : 'Phase Out')),
                "flg_tipo" => ($tipoMaterial == 1 ? '1' : '0'),
                "unidad_medida" => trim($descUDM),
                "id_udm" => $unidad_medida,
            );

            $data = $this->m_utils->updateMaterial($codigoMaterial,$arrayUpdateMat);
            if ($data['error'] == EXIT_SUCCESS) {
                $data['tbMateriales'] = $this->makeHTLMTablaConsulta($this->m_utils->getAllMateriales());
            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function makeHTLMTablaConsulta($listaMateriales)
    {

        $html = '
                <table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th style="text-align: center">#</th>
                            <th style="text-align: center">CODIGO</th>
                            <th style="text-align: center">MATERIAL</th>
                            <th style="text-align: center">COSTO</th>
                            <th style="text-align: center">ESTADO</th>
                            <th style="text-align: center">TIPO</th>
                            <th style="text-align: center">UDM</th>
                            <th style="text-align: center">ACCI&Oacute;N</th>
                        </tr>
                    </thead>

                    <tbody>';
        $count = 1;

        if ($listaMateriales != '') {
            foreach ($listaMateriales as $row) {

                $html .= '
                        <tr>
                            <td style="text-align: center">' . $count . '</td>
                            <td>' . $row->id_material . '</td>
                            <td>' . utf8_decode($row->descrip_material) . '</td>
                            <td style="text-align: center">' . number_format($row->costo_material, 2) . '</td>
                            <td style="text-align: center">' . $row->estado_material . '</td>
                            <td style="text-align: center">' . $row->tipo_material . '</td>
                            <td>' . $row->unidad_medida . '</td>
                            <th style="text-align: center">
                                <a style="color:var(--verde_telefonica)" data-idmaterial="' . $row->id_material . '" onclick="openEditMaterial(this)"><i class="zmdi zmdi-hc-2x zmdi-edit"></i></a>
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

    public function getDetalleMaterial()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $codigoMaterial = $this->input->post('codigoMaterial') ? $this->input->post('codigoMaterial') : null;

            if ($codigoMaterial == null) {
                throw new Exception('Hubo un error al traer el material!!');
            }
            $arrayMaterial = $this->m_utils->getDetalleMaterial($codigoMaterial);
            $data = $this->makeHTLMUDM($this->m_utils->getUDMMaterial(), $arrayMaterial['id_udm']);
            $data['cmbUDM'] = $data['comboHTML'];
            $data['codMat'] = $arrayMaterial['id_material'];
            $data['descrip_material'] = $arrayMaterial['descrip_material'];
            $data['costoMaterial'] = $arrayMaterial['costo_material'];
            $data['flg_estado'] = $arrayMaterial['flg_estado'];
            $data['tipoMat'] = ($arrayMaterial['flg_tipo'] == '1' ? 1 : 2);
            $data['error'] = EXIT_SUCCESS;

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    // public function makeHTLMSubProy($listaSubProy)
    // {
    //     $html = '<option value="">Seleccionar SubProyecto</option>';

    //     foreach ($listaSubProy as $row) {
    //         $html .= '<option value="' . $row->idSubProyecto . '">' . $row->subProyectoDesc . '</option>';
    //     }
    //     $data['comboHTML'] = utf8_decode($html);
    //     return $data;
    // }

    public function getComboUDM()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $data = $this->makeHTLMUDM($this->m_utils->getUDMMaterial());
            $data['cmbUDM'] = $data['comboHTML'];
            $data['error'] = EXIT_SUCCESS;

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function makeHTLMUDM($listaUDM, $idUdm = null)
    {
        $html = '<option value="">Seleccionar UDM</option>';

        foreach ($listaUDM as $row) {
            $selected = ($row->id_udm == $idUdm) ? 'selected' : null;
            $html .= '<option value="' . $row->id_udm . '" ' . $selected . ' >' . $row->descrip_udm . '</option>';
        }
        $data['comboHTML'] = utf8_decode($html);
        return $data;
    }

}
