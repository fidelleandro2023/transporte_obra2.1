<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_liquidacion_retiro extends CI_Controller
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

            $data['tablaBolsaPresupuesto'] = $this->makeHTLMTablaConsulta($this->M_bolsa_presupuesto->getAllSoliRet(null, null, null, null, $idPersonaSession));
            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $permisos = $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_GESTION_ERC, ID_PERMISO_HIJO_LIQUIDACION_RETIRO);
            $data['opciones'] = $result['html'];
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_gestion_erc/v_liquidacion_retiro', $data);
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
                            <th>MONTO SOLIC.</th>
                            <th>MONTO APROB.</th>
                            <th>ESTADO</th>
                            <th>FEC. APROB</th>
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
                            <td>' . $row->monto_solicitado . '</td>
                            <td>' . $row->monto_aprob . '</td>
                            <td>' . $row->estado_solicitud . '</td>
                            <td>' . $row->fecha_aprobacion . '</td>
                            <td>' . ($row->flg_solicitud == 1 ?
                    '<button type="button" class="btn btn-primary" data-idbolsa="' . $row->idBolsa . '"  data-itemplan="' . $row->itemplan . '" data-nroretiro="' . $row->nro_retiro . '" data-montoaprob="' . $row->montoAprob_calculo . '"  onclick="abrirModalLiquiSoli(this)">Liquidar</button>'
                    : '') . '</td>
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

            $itemPlan = $this->input->post('itemplan');
            $nroRetiro = $this->input->post('nroRetiro');
            $estadoSoli = $this->input->post('estadoSoli');
            $fechaAprob = $this->input->post('fechaAprob');
            $idPersonaSession = $this->session->userdata('idPersonaSession');
            $descPerfilSesion = $this->session->userdata('descPerfilSession');

            if ($descPerfilSesion == 'ADMINISTRADOR') {
                $idPersonaSession = null;
            }

            $data['tablaLiquiRet'] = $this->makeHTLMTablaConsulta($this->M_bolsa_presupuesto->getAllSoliRet($itemPlan, $nroRetiro, $estadoSoli, $fechaAprob, $idPersonaSession));
            $data['error'] = EXIT_SUCCESS;

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function setUserDataEvidLiquiRetiro()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $idBolsa = $this->input->post('idBolsa') ? $this->input->post('idBolsa') : null;
            $itemplan = $this->input->post('itemplan') ? $this->input->post('itemplan') : null;
            $nroRetiro = $this->input->post('nroRetiro') ? $this->input->post('nroRetiro') : null;

            if ($idBolsa != null && $itemplan != null && $nroRetiro != null) {

                unset($_SESSION["idBolsa"]);
                $this->session->set_userdata('idBolsaTemp', $idBolsa);

                unset($_SESSION["itemplan"]);
                $this->session->set_userdata('itemplanTemp', $itemplan);

                unset($_SESSION["itemplan"]);
                $this->session->set_userdata('nroRetiroTemp', $nroRetiro);

                $data['error'] = EXIT_SUCCESS;
            } else {
                $data['msj'] = "Hubo un error al crear los id en sesion";
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

    public function registrarLiquiRetiro()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $idBolsa = $this->input->post('idBolsa');
            $itemplan = $this->input->post('itemplan');
            $nroRetiro = $this->input->post('nroRetiro');
            $descCompro = $this->input->post('descCompro');
            $montoAprob = $this->input->post('montoAprob');
            $montoLiqui = $this->input->post('montoLiqui');

            $idPersonaSession = $this->session->userdata('idPersonaSession');
            $descPerfilSesion = $this->session->userdata('descPerfilSession');

            if ($montoLiqui > $montoAprob) {
                throw new Exception('El monto a liquidar no puede ser mayor al monto aprobado!!');
            }

            $arrayInsert = array(
                "idBolsa" => $idBolsa,
                "itemplan" => $itemplan,
                "nro_retiro" => $nroRetiro,
                "desc_comprobante" => $descCompro,
                "monto_liquidado" => $montoLiqui,
                "fecha_registro" => date("Y-m-d H:i:s"),
                "flg_validado" => 1,
                "id_usuario_regi" => $idPersonaSession,
            );

            $data = $this->M_bolsa_presupuesto->insertarLiquiRetiro($arrayInsert);
            if ($data['error'] == EXIT_SUCCESS) {

                // $diferencia = $montoAprob - $montoLiqui;
                // $montoStock = $this->M_bolsa_presupuesto->getMontoStockByIdBolsa($idBolsa);

                // $arrayUpdateBolsaPresu = array(
                //     "monto_stock" => ($montoStock + $diferencia),
                // );
                // $data = $this->M_bolsa_presupuesto->updateBolsaPresupuesto($idBolsa, $arrayUpdateBolsaPresu);
                // if ($data['error'] == EXIT_SUCCESS) {
                $arrayUpdateRetiro = array(
                    "flg_solicitud" => 2,
                );
                $data = $this->M_bolsa_presupuesto->updateSolicitudRetiro($nroRetiro, $arrayUpdateRetiro);
                // }

            }

            if ($descPerfilSesion == 'ADMINISTRADOR') {
                $idPersonaSession = null;
            }

            $data['tbLiquiRetiro'] = $this->makeHTLMTablaConsulta($this->M_bolsa_presupuesto->getAllSoliRet(null, null, null, null, $idPersonaSession));

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function uploadEviLiquiRet()
    {
        $data['error'] = EXIT_ERROR;

        $idBolsaTemp = $this->session->userdata('idBolsaTemp');
        $itemplanTemp = $this->session->userdata('itemplanTemp');
        $nroRetiroTemp = $this->session->userdata('nroRetiroTemp');

        $file = $_FILES["file"]["name"];
        $filetype = $_FILES["file"]["type"];
        $filesize = $_FILES["file"]["size"];

        $imagen = $_FILES['file']['tmp_name'];

        $ubicacion = 'uploads/liquidacion_retiro';
        if (!is_dir($ubicacion)) {
            mkdir('uploads/liquidacion_retiro', 0777);
        }
        $ubicBolsa = 'uploads/liquidacion_retiro/nro_retiro_' . $nroRetiroTemp;
        if (!is_dir($ubicBolsa)) {
            mkdir($ubicBolsa, 0777);
        } else { //si existe borramos el archivo existente
            $filesExist = scandir($ubicBolsa); //trae arreglo de archivos existentes en esa carpeta
            $ficherosEliminados = 0;
            foreach ($filesExist as $f) {
                if (is_file($ubicBolsa . "/" . $f)) {
                    if (unlink($ubicBolsa . "/" . $f)) {
                        $ficherosEliminados++;
                    }
                }
            }
        }

        $file2 = utf8_decode($file);
        if (utf8_decode($file) && move_uploaded_file($imagen, $ubicBolsa . "/" . $file2)) {

            rename($ubicBolsa . "/" . $file2, $ubicBolsa . "/evidencia_" . $nroRetiroTemp.".pdf");
            $arrayData = array('ruta_pdf' => $ubicBolsa . "/evidencia_" . $nroRetiroTemp.".pdf");

            $data = $this->M_bolsa_presupuesto->updateLiquiRetiro($idBolsaTemp, $itemplanTemp, $arrayData);
        }

        echo json_encode(array_map('utf8_encode', $data));
    }

}
