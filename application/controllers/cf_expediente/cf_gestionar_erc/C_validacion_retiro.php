<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_validacion_retiro extends CI_Controller
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

            $data['tablaBolsaPresupuesto'] = $this->makeHTLMTablaConsulta($this->M_bolsa_presupuesto->getAllSoliRetLiquidados(null, null, null, null, $idPersonaSession));
            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $permisos = $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_GESTION_ERC, ID_PERMISO_HIJO_VALIDACION_RETIRO);
            $data['opciones'] = $result['html'];
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_gestion_erc/v_validacion_retiro', $data);
            } else {
                redirect('login', 'refresh');
            }
        } else {

            redirect('login', 'refresh');
        }

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
                            <th>MOTIVO RETIRO</th>
                            <th>USU. LIQUIDA.</th>
                            <th>MONTO APROB.</th>
                            <th>MONTO LIQUI.</th>
                            <th>ESTADO</th>
                            <th>FEC. LIQUI</th>
                            <th>#. COMPRO.</th>
                            <th>EVIDENCIA</th>
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
                            <td>' . $row->usu_liquida . '</td>
                            <td>' . $row->monto_aprob . '</td>
                            <td>' . $row->monto_liquidado . '</td>
                            <td>' . $row->estado_solicitud . '</td>
                            <td>' . $row->fecha_liquidacion . '</td>
                            <td>' . $row->desc_comprobante . '</td>
                            <td style="text-align:center">
                                <a href="' . $row->ruta_pdf . '" download="Descargando Evidencia (.pdf)"><i class="zmdi zmdi-hc-2x zmdi-collection-pdf"></i></a>
                            </td>
                            <td>' . ($row->flg_solicitud == 2 ?
                                           '<button type="button" class="btn btn-primary" data-idbolsa="' . $row->idBolsa . '"  data-itemplan="' . $row->itemplan . '" data-nro_retiro="' . $row->nro_retiro . '" data-montoliqui="' . $row->monto_liquidado . '" data-montoliquical="' . $row->montoLiqui_calculo . '" data-montoaprob="'.$row->montoAprob_calculo.'" data-montoaprobvista="'.$row->monto_aprob.'" onclick="abrirModalAlertValida(this)">Validar</button>'
                                         : '<button type="button" class="btn btn-primary" data-fechavalida="' . $row->fecha_validacion . '" data-montovalida="' . $row->monto_validado . '" data-montodevuelto="'.($row->montoAprob_calculo - $row->monto_validado).'" data-montoaprob="'.$row->monto_aprob.'" onclick="abrirModalValida(this)">Vizualizar</button>') . '</td>
                        </tr>';
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
            $fechaLiqui = $this->input->post('fechaLiqui');
            $idPersonaSession = $this->session->userdata('idPersonaSession');
            $descPerfilSesion = $this->session->userdata('descPerfilSession');

            if ($descPerfilSesion == 'ADMINISTRADOR') {
                $idPersonaSession = null;
            }

            $data['tablaLiquiRet'] = $this->makeHTLMTablaConsulta($this->M_bolsa_presupuesto->getAllSoliRetLiquidados($itemPlan, $nroRetiro, $estadoSoli, $fechaLiqui, $idPersonaSession));
            $data['error'] = EXIT_SUCCESS;

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function updateRetiroBolsa()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $idBolsa = $this->input->post('idBolsa');
            $itemplan = $this->input->post('itemplan');
            $nroRetiro = $this->input->post('nroRetiro');
            $montoAprob = $this->input->post('montoAprob');
            $montoValida = $this->input->post('montoValida');

            $idPersonaSession = $this->session->userdata('idPersonaSession');
            $descPerfilSesion = $this->session->userdata('descPerfilSession');

            $diferencia = $montoAprob - $montoValida;
            $montoStock = $this->M_bolsa_presupuesto->getMontoStockByIdBolsa($idBolsa);

            $arrayUpdateBolsaPresu = array(
                "monto_stock" => ($montoStock + $diferencia),
            );


            $data = $this->M_bolsa_presupuesto->updateBolsaPresupuesto($idBolsa, $arrayUpdateBolsaPresu);
            if ($data['error'] == EXIT_SUCCESS) {
                $arrayUpdateRetiro = array(
                    "flg_solicitud" => 4,
                    "id_usuario_valida" => $idPersonaSession,
                    "monto_validado" => $montoValida,
                    "fecha_validacion" => date("Y-m-d H:i:s"),
                );
                $data = $this->M_bolsa_presupuesto->updateSolicitudRetiro($nroRetiro, $arrayUpdateRetiro);
            }

            if ($descPerfilSesion == 'ADMINISTRADOR') {
                $idPersonaSession = null;
            }

            $data['tbLiquiRetiro'] = $this->makeHTLMTablaConsulta($this->M_bolsa_presupuesto->getAllSoliRetLiquidados(null, null, null, null, $idPersonaSession));

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

}
