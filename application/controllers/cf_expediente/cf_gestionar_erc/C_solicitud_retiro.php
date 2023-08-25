<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_solicitud_retiro extends CI_Controller
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
            $data['listaZonal'] = $this->m_consulta->getAllZonalIndex($zonas);
            $data['listaSubProy'] = $this->m_utils->getAllSubProyecto();
            $data['tablaBolsaPresupuesto'] = $this->makeHTLMTablaConsulta($this->M_bolsa_presupuesto->getAllSolicitudesRetiro(null, null, null, null, null));
            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $permisos = $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_GESTION_ERC, ID_PERMISO_HIJO_SOLICITUD_RETIRO);
            $data['opciones'] = $result['html'];
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_gestion_erc/v_solicitud_retiro', $data);
            } else {
                redirect('login', 'refresh');
            }
        } else {

            redirect('login', 'refresh');
        }

    }

    public function registrarSolicitudRetiro()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['msjAviso'] = null;

        try {

            $idBolsa = $this->input->post('idBolsa');
            $itemplan = $this->input->post('itemplan');
            $motivo = $this->input->post('motivo');
            $monto = $this->input->post('monto');
            $idPersonaSession = $this->session->userdata('idPersonaSession');

            $flgRetiro = $this->M_bolsa_presupuesto->getCountRetiro($idBolsa, $itemplan);

            $montoStock = $this->M_bolsa_presupuesto->getMontoStockByIdBolsa($idBolsa);

            if ($montoStock == 0) {
                throw new Exception('Ya no puede solicitar un retiro porque no hay presupuesto!!');
            }

            if ($monto > $montoStock) {
                throw new Exception('El monto solicitado no puede exceder al monto de la bolsa!!');
            }

            $this->M_bolsa_presupuesto->deleteLogTempRetiro();

            $nroRetiro = $this->M_bolsa_presupuesto->generarCodigoRetiro();

            $arrayInsert = array(
                "idBolsa" => $idBolsa,
                "itemplan" => $itemplan,
                "nro_retiro" => $nroRetiro,
                "motivo" => $motivo,
                "id_usuario_solic" => $idPersonaSession,
                "monto_solicitado" => $monto,
                "flg_solicitud" => 0,
                "fecha_registro" => date("Y-m-d H:i:s"),
            );
            $data = $this->M_bolsa_presupuesto->insertarSolicitudRetiro($arrayInsert);

            if ($flgRetiro > 0) {
                $data['msjAviso'] = 'Ya existe una solicitud pendiente de retiro con ese itemplan!!';
            }

            $data['nroRetiro'] = $this->M_bolsa_presupuesto->obtenerUltimoRegistroRetiro();

            $data['tbSoliRetiro'] = $this->makeHTLMTablaConsulta($this->M_bolsa_presupuesto->getAllSolicitudesRetiro(null, null, null, null, null));

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
                            <th>USUARIO SOLICITANTE</th>
                            <th>MONTO SOLICITADO</th>
                            <th>ESTADO</th>
                            <th>FECHA DE REGISTRO</th>
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
            $fechaRegistro = $this->input->post('fechaRegi');

            $data['tablaSoliRet'] = $this->makeHTLMTablaConsulta($this->M_bolsa_presupuesto->getAllSolicitudesRetiro($itemPlan, $nroRetiro, $estadoSoli, $fechaRegistro, null));
            $data['error'] = EXIT_SUCCESS;

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    // public function searchBolsaPresupuesto()
    // {
    //     $data['error'] = EXIT_ERROR;
    //     $data['msj'] = null;
    //     try {
    //         $descCuenta = $this->input->post('descCuenta') ? $this->input->post('descCuenta') : null;
    //         if ($descCuenta != null) {
    //             $data = $this->makeHTLMTablaBolsaPresupuesto($this->M_bolsa_presupuesto->getAllBolsaPresupuesto($descCuenta, null));
    //             $data['tablaBolsaPresu'] = $data['tablaHTML'];
    //             $data['idBolsa'] = $data['idBolsa'];
    //             $data['error'] = EXIT_SUCCESS;
    //         }

    //     } catch (Exception $e) {
    //         $data['msj'] = $e->getMessage();
    //     }
    //     echo json_encode(array_map('utf8_encode', $data));
    // }

    public function makeHTLMBolsaPresupuesto($listaBolsaPresu)
    {
        // $html = '<table style="font-size: 10px;" id="tb_det_bolsa" class="table table-bordered">
        //             <thead class="thead-default">
        //                 <tr role="row">
        //                     <th style="text-align:center">DESC. CUENTA</th>
        //                     <th style="text-align:center">MONTO DISPONIBLE</th>
        //                     <th style="text-align:center">RESPONSABLE</th>
        //                 </tr>
        //             </thead>

        //         <tbody>';
        $html = '<option value="">Seleccionar Cuenta</option>';

        // $idBolsa = null;

        foreach ($listaBolsaPresu as $row) {

            // $idBolsa = $row->idBolsa;

            // $html .= '<tr>
            //             <td>' . $row->desc_cuenta . '</td>
            //             <td>' . $row->monto_stock . '</td>
            //             <td>' . $row->desc_responsable . '</td>
            //           </tr>';

            $html .= '<option value="' . $row->idBolsa . '">' . $row->desc_cuenta . '</option>';
        }
        // $html .= '</tbody>
        //           </table>';

        // $data['idBolsa'] = $idBolsa;
        $data['comboHTML'] = utf8_decode($html);
        return $data;
    }

    public function searchItemPlan()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $itemplan = $this->input->post('itemplan') ? $this->input->post('itemplan') : null;
            if ($itemplan != null) {
                $data = $this->makeHTLMTablaItemPLanDetalle($this->M_bolsa_presupuesto->getItemPlanById($itemplan));
                $data['tablaDetItemPlan'] = $data['tablaHTML'];
                if ($data['tablaDetItemPlan'] == '') {
                    $data['flgMsj'] = 0;
                } else {
                    $data['flgMsj'] = 1;
                }
                $data['itemplan'] = $data['idItemPlan'];
                $data['error'] = EXIT_SUCCESS;
            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function makeHTLMTablaItemPLanDetalle($listaItemPlan)
    {
        $itemplan = null;
        if (count($listaItemPlan) > 0) {
            $html = '<table style="font-size: 10px;" id="tabla_detalle" class="table table-bordered">
                        <thead class="thead-default">
                            <tr role="row">
                                <th style="text-align:center">ITEMPLAN</th>
                                <th style="text-align:center">PROYECTO</th>
                                <th style="text-align:center">EECC</th>
                                <th style="text-align:center">SUBPROYECTO</th>
                                <th style="text-align:center">INDICADOR</th>
                            </tr>
                        </thead>

                    <tbody>';

            foreach ($listaItemPlan as $row) {

                $itemplan = $row->itemPlan;

                $html .= '<tr>
                                    <td>' . $row->itemPlan . '</td>
                                    <td>' . $row->nombreProyecto . '</td>
                                    <td>' . $row->empresaColabDesc . '</td>
                                    <td>' . $row->subProyectoDesc . '</td>
                                    <td>' . $row->indicador . '</td>
                                  </tr>';
            }
            $html .= '</tbody>
                              </table>';
        } else {
            $html = '';
        }

        $data['idItemPlan'] = $itemplan;
        $data['tablaHTML'] = utf8_decode($html);
        return $data;
    }

    public function getAllBolsaPresupuesto()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $data = $this->makeHTLMBolsaPresupuesto($this->M_bolsa_presupuesto->getAllBolsaPresupuesto(null, null, 1));
            $data['comboBolsaPresu'] = $data['comboHTML'];
            $data['error'] = EXIT_SUCCESS;

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

}
