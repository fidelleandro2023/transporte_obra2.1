<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_aprobacion_solicitud_retiro extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_plan_obra/m_consulta');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_gestion_erc/M_bolsa_presupuesto');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index()
    {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {

            // Trayendo zonas permitidas al usuario
            $zonas = $this->session->userdata('zonasSession');
            $idPersonaSession = $this->session->userdata('idPersonaSession');
            $descPerfilSesion = $this->session->userdata('descPerfilSession');

            if ($descPerfilSesion == 'ADMINISTRADOR') {
                $idPersonaSession = null;
            }

            $data['listaZonal'] = $this->m_consulta->getAllZonalIndex($zonas);
            $data['listaSubProy'] = $this->m_utils->getAllSubProyecto();

            $data['tablaBolsaPresupuesto'] = $this->makeHTLMTablaConsulta($this->M_bolsa_presupuesto->getAllSolicitudesRetiro(null, null, 0, null, $idPersonaSession));
            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $permisos = $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_GESTION_ERC, ID_PERMISO_HIJO_APROBACION_RETIRO);
            $data['opciones'] = $result['html'];
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_gestion_erc/v_aprobacion_solicitud_retiro', $data);
            } else {
                redirect('login', 'refresh');
            }
        } else {

            redirect('login', 'refresh');
        }

    }

    public function updateSolicitudRetiro()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $nroRetiro = $this->input->post('nroRetiro');
            $idBolsa = $this->input->post('idBolsa');
            $montoSoli = $this->input->post('montoSoli');
            $montoAprob = $this->input->post('montoAprob');
            $idPersonaSession = $this->session->userdata('idPersonaSession');
            $descPerfilSesion = $this->session->userdata('descPerfilSession');
            $idUsuarioSessionUpdate = $idPersonaSession;

            if ($descPerfilSesion == 'ADMINISTRADOR') {
                $idPersonaSession = null;
            }
            if($montoAprob > $montoSoli){
                throw new Exception('El monto a aprobar no puede ser mayor al solicitado!!');
            }

            $arrayUpdateSoliRet = array(
                "id_usuario_aprob" => $idUsuarioSessionUpdate,
                "monto_aprob" => $montoAprob,
                "flg_solicitud" => 1,
                "fecha_aprobacion" => date("Y-m-d H:i:s"),
            );

            $montoStock = $this->M_bolsa_presupuesto->getMontoStockByIdBolsa($idBolsa);
            $diferencia = $montoStock - $montoAprob;
            if ($diferencia < 0) {
                throw new Exception('No puede aprobar un monto que exceda a la bolsa!!');
            }
            if ($montoStock > 0) {
                $data = $this->M_bolsa_presupuesto->updateSolicitudRetiro($nroRetiro, $arrayUpdateSoliRet);
                if ($data['error'] == EXIT_SUCCESS) {
                    $arrayUpdateBolsaPresu = array(
                        "monto_stock" => ($montoStock - $montoAprob),
                    );
                    $data = $this->M_bolsa_presupuesto->updateBolsaPresupuesto($idBolsa, $arrayUpdateBolsaPresu);
                }

            } else {
                $data['msj'] = "Ya no hay presupuesto para aprobar la solicitud!!";
                $data['error'] = EXIT_ERROR;
            }

            $data['tbSoliRetiro'] = $this->makeHTLMTablaConsulta($this->M_bolsa_presupuesto->getAllSolicitudesRetiro(null, null, 0, null, $idPersonaSession));

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function makeHTLMTablaConsulta($listaSolicitudRetiro)
    {

        $html = '
                <table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>CUENTA SOLIC.</th>
                            <th>ITEMPLAN</th>
                            <th># RETIRO</th>
                            <th>MOTIVO</th>
                            <th>USU.SOLIC.</th>
                            <th>MONTO SOLIC.</th>
                            <th>ESTADO</th>
                            <th>FECHA DE REGISTRO</th>
                            <th>ACCI&Oacute;N</th>
                        </tr>
                    </thead>

                    <tbody>';
        if ($listaSolicitudRetiro != '') {
            foreach ($listaSolicitudRetiro as $row) {

                $html .= '
                        <tr>
                            <td>' . $row->desc_cuenta . '</td>
                            <td>' . $row->itemplan . '</td>
                            <td>' . $row->nro_retiro . '</td>
                            <td>' . $row->motivo . '</td>
                            <td>' . $row->usu_solicitante . '</td>
                            <td>' . $row->monto_solicitado . '</td>
                            <td>' . $row->estado_solicitud . '</td>
                            <td>' . $row->fecha_registro . '</td>
                            <td>' . ($row->flg_solicitud == 0 ?
                    '<div class="row">
                        <div class="col-sm-6 col-md-5">
                            <a data-has_coax="1" data-has_fo="1" data-nroretiro="' . $row->nro_retiro . '"  data-idbolsa="' . $row->idBolsa . '" data-nroretiro="' . $row->nro_retiro . '"  data-montosoli="' . $row->monto_solicitado . '" data-montosolicalcu="' . $row->montoSoli_calcu . '" onclick="aprobarSolicitud(this)" style="margin-left: 30%;"><img alt="Editar" height="25px" width="25px" src="public/img/iconos/check_24016.png"></a>
                        </div>
                        <div class="col-sm-6 col-md-5">
                            <a data-has_coax="1" data-has_fo="1" data-nroretiro="' . $row->nro_retiro . '"  data-idbolsa="' . $row->idBolsa . '" data-nroretiro="' . $row->nro_retiro . '" data-itemplan="' . $row->itemplan . '" data-montosoli="' . $row->monto_solicitado . '" onclick="openModalAlertDesaprob(this)" style="margin-left: 30%;"><img alt="Editar" height="25px" width="25px" src="public/img/iconos/cancelar_equis.png"></a>
                        </div>
                        </div>'
                    : ($row->flg_solicitud == 1 ? '<button type="button" class="btn btn-primary" data-nroretiro="' . $row->nro_retiro . '" data-montoaprob="' . $row->monto_aprob . '" data-montosoli="' . $row->monto_solicitado . '"  data-fechaaprob="' . $row->fecha_aprobacion . '"  onclick="verMontoAprob(this)">Visualizar</button>' . '</td></tr>' : ''));
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

            $itemPlan = $this->input->post('itemplan');
            $nroRetiro = $this->input->post('nroRetiro');
            $estadoSoli = $this->input->post('estadoSoli');
            $fechaRegistro = $this->input->post('fechaRegi');
            $idPersonaSession = $this->session->userdata('idPersonaSession');
            $descPerfilSesion = $this->session->userdata('descPerfilSession');

            if ($descPerfilSesion == 'ADMINISTRADOR') {
                $idPersonaSession = null;
            }

            $data['tablaSoliRet'] = $this->makeHTLMTablaConsulta($this->M_bolsa_presupuesto->getAllSolicitudesRetiro($itemPlan, $nroRetiro, $estadoSoli, $fechaRegistro, $idPersonaSession));
            $data['error'] = EXIT_SUCCESS;

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function desaprobSoliRetiro()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $nroRetiro = $this->input->post('nroRetiro');
            $idBolsa = $this->input->post('idBolsa');
            $itemplan = $this->input->post('itemplan');

            $idPersonaSession = $this->session->userdata('idPersonaSession');
            $descPerfilSesion = $this->session->userdata('descPerfilSession');

            $arrayUpdateSoliRet = array(
                "id_usuario_aprob" => $idPersonaSession,
                "flg_solicitud" => 3,
                "fecha_aprobacion" => date("Y-m-d H:i:s"),
            );
            
            $data = $this->M_bolsa_presupuesto->updateSolicitudRetiro($nroRetiro, $arrayUpdateSoliRet);

            if ($descPerfilSesion == 'ADMINISTRADOR') {
                $idPersonaSession = null;
            }

            $data['tbSoliRetiro'] = $this->makeHTLMTablaConsulta($this->M_bolsa_presupuesto->getAllSolicitudesRetiro(null, null, 0, null, $idPersonaSession));

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

}
