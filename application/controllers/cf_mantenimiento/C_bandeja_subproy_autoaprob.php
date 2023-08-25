<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_bandeja_subproy_autoaprob extends CI_Controller
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


            $data['tablaBolsaPresupuesto'] = $this->makeHTLMTablaConsulta($this->m_utils->getAllSubProyConfig());
            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $permisos = $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_MANTENIMIENTO, ID_PERMISO_HIJO_SUBPROY_AUTO_APROB);
            $data['opciones'] = $result['html'];
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_mantenimiento/v_bandeja_subproy_autoaprob', $data);
            } else {
                redirect('login', 'refresh');
            }
        } else {

            redirect('login', 'refresh');
        }

    }

    public function registrarSubProyAutoAprobPO()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $idSubProys = $this->input->post('idSubProys') ? $this->input->post('idSubProys') : null;

            if($idSubProys == null ){
                throw new Exception('Debe seleccionar como minimo un subproyecto valido!!');
            }
            $flgExisteSubProy = $this->m_utils->getCountSubProyInConfigAutoAprob($idSubProys);
            $arrayInsertGlob = array();

            if ($flgExisteSubProy == 0) {

                foreach($idSubProys as $row){
                    $arrayInsertTemp = array(
                        "idSubProyecto" => $row
                    );

                    array_push($arrayInsertGlob, $arrayInsertTemp);
                }

                if(count($arrayInsertGlob) > 0){
                    $data = $this->m_utils->insertarSubProyConfigAutoAprobPO($arrayInsertGlob);
                    if ($data['error'] == EXIT_SUCCESS) {
                        $data['tbSubProy'] = $this->makeHTLMTablaConsulta($this->m_utils->getAllSubProyConfig());
                    }
                }else{
                    throw new Exception('No se genero los subrpoyectos a insertar!!');
                }
                
            } else {
                $data['msj'] = "Ya existe(n) ese(os) subproyecto(s), ingrese(n) otro(s) por favor!!";
            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function deleteSubProyConfigAutoAprobPO()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $idSubProyecto = $this->input->post('idSubProyecto') ? $this->input->post('idSubProyecto') : null;

            if($idSubProyecto == null){
                throw new Exception('Hubo un error al traer el Subproyecto a eliminar!!');
            }

            $data = $this->m_utils->deleteSubProyConfigAutoArpobPO($idSubProyecto);

            if ($data['error'] == EXIT_SUCCESS) {
                $data['tbSubProy'] = $this->makeHTLMTablaConsulta($this->m_utils->getAllSubProyConfig());
            }
           
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function makeHTLMTablaConsulta($listaSubProyConfig)
    {

        $html = '
                <table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th style="text-align: center">#</th>
                            <th style="text-align: center">SUBPROYECTO</th>
                            <th style="text-align: center">TIEMPO</th>
                            <th style="text-align: center">ACCI&Oacute;N</th>
                        </tr>
                    </thead>

                    <tbody>';
        $count = 1;

        if ($listaSubProyConfig != '') {
            foreach ($listaSubProyConfig as $row) {

                $html .= '
                        <tr>
                            <td style="text-align: center">' . $count . '</td>
                            <td>' . $row->subProyectoDesc . '</td>
                            <td style="text-align: center">' . $row->tiempo . '</td>
                            <th style="text-align: center">
                                <a style="color:blue" data-idsubproy="' . $row->idSubProyecto . '"  onclick="deleteSubProy(this)"><i class="zmdi zmdi-hc-2x zmdi-delete"></i></a>
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


    public function getSubProyectos()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $data = $this->makeHTLMSubProy($this->m_utils->getAllSubProySinConfigPO());
            $data['comboSubProy'] = $data['comboHTML'];
            $data['error'] = EXIT_SUCCESS;

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function makeHTLMSubProy($listaSubProy)
    {
        $html = '<option value="">Seleccionar SubProyecto</option>';

        foreach ($listaSubProy as $row) {
            $html .= '<option value="' . $row->idSubProyecto . '">' . $row->subProyectoDesc . '</option>';
        }
        $data['comboHTML'] = utf8_decode($html);
        return $data;
    }

}
