<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_bolsa_presupuesto extends CI_Controller
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
            //$data['listaItemplan'] = $this->m_utils->getAllItemplan();
            // Trayendo zonas permitidas al usuario
            $zonas = $this->session->userdata('zonasSession');
            $data['listaZonal'] = $this->m_consulta->getAllZonalIndex($zonas);
            $data['listaSubProy'] = $this->m_utils->getAllSubProyecto();

            $idPersonaSession = $this->session->userdata('idPersonaSession');
            $descPerfilSesion = $this->session->userdata('descPerfilSession');

            if ($descPerfilSesion == 'ADMINISTRADOR') {
                $idPersonaSession = null;
            }

            $data['tablaBolsaPresupuesto'] = $this->makeHTLMTablaConsulta($this->M_bolsa_presupuesto->getAllBolsaPresupuesto(null, $idPersonaSession, null));
            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $permisos = $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_GESTION_ERC, ID_PERMISO_HIJO_BOLSA_PRESUPUESTO);
            $data['opciones'] = $result['html'];
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_gestion_erc/v_bolsa_presupuesto', $data);
            } else {
                redirect('login', 'refresh');
            }
        } else {

            redirect('login', 'refresh');
        }

    }

    public function registrarBolsaPresupuesto()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $descCuenta = $this->input->post('descripCuenta');
            $flgExisteCuenta = $this->M_bolsa_presupuesto->getCountBolsaPresupuesto($descCuenta);
            $idPersonaSession = $this->session->userdata('idPersonaSession');
            $descPerfilSesion = $this->session->userdata('descPerfilSession');

            if ($flgExisteCuenta == 0) {
                $idUsuarioResponsable = $this->input->post('idResponsable');
                $monto = $this->input->post('monto');

                $this->M_bolsa_presupuesto->deleteLogTempBolsa();

                $getNroCuenta = $this->M_bolsa_presupuesto->generarCodigoCuenta();

                $arrayInsert = array(
                    "desc_cuenta" => $descCuenta,
                    "id_usuario_responsable" => $idUsuarioResponsable,
                    "monto_inicial" => $monto,
                    "monto_stock" => $monto,
                    "id_usuario_regi" => $idPersonaSession,
                    "fecha_registro" => date("Y-m-d H:i:s"),
                    "nro_cuenta" => $getNroCuenta,
                );
                $data = $this->M_bolsa_presupuesto->insertarBolsaPresupuesto($arrayInsert);

                if ($data['error'] == EXIT_SUCCESS) {

                    $nroCuentaInsert = $this->M_bolsa_presupuesto->obtenerUltimoRegistroBolsaPresu();

                    $this->M_bolsa_presupuesto->deleteLogTempTransaccionBolsa();
                    $getNroTransacc = $this->M_bolsa_presupuesto->generarCodigoTransaccionBolsa();

                    $arrayInsertLog_bolsa = array(
                        "id_usuario_regis" => $idPersonaSession,
                        "monto_agregado" => $monto,
                        "fecha_modificacion" => date("Y-m-d H:i:s"),
                        "nro_cuenta" => $nroCuentaInsert,
                        "nro_transaccion" => $getNroTransacc,
                    );
                    $data = $this->M_bolsa_presupuesto->insertarLogBolsa($arrayInsertLog_bolsa);
                }

                if ($descPerfilSesion == 'ADMINISTRADOR') {
                    $idPersonaSession = null;
                }
                $data['tbBolsaPresupuesto'] = $this->makeHTLMTablaConsulta($this->M_bolsa_presupuesto->getAllBolsaPresupuesto(null, $idPersonaSession, null));
            } else {
                $data['msj'] = "Ya existe ese nombre de cuenta, ingrese otro por favor";
            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function updateBolsaPresupuesto()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $idBolsa = $this->input->post('idBolsa');
            $nroCuenta = $this->input->post('nroCuenta');
            $montoInicial = $this->input->post('montoInicial');
            $montoStock = $this->input->post('montoStock');
            $monto = $this->input->post('monto');
            $idPersonaSession = $this->session->userdata('idPersonaSession');
            $descPerfilSesion = $this->session->userdata('descPerfilSession');

            $arrayUpdate = array(
                "monto_inicial" => ($monto + $montoInicial),
                "monto_stock" => ($monto + $montoStock),
                "id_usuario_regi" => $idPersonaSession,
                "fecha_registro" => date("Y-m-d"),
            );
            $data = $this->M_bolsa_presupuesto->updateBolsaPresupuesto($idBolsa, $arrayUpdate);

            if ($data['error'] == EXIT_SUCCESS) {
                $this->M_bolsa_presupuesto->deleteLogTempTransaccionBolsa();
                $getNroTransacc = $this->M_bolsa_presupuesto->generarCodigoTransaccionBolsa();

                $arrayInsert = array(
                    "id_usuario_regis" => $idPersonaSession,
                    "monto_agregado" => $monto,
                    "fecha_modificacion" => date("Y-m-d H:i:s"),
                    "nro_cuenta" => $nroCuenta,
                    "nro_transaccion" => $getNroTransacc
                );
                $data = $this->M_bolsa_presupuesto->insertarLogBolsa($arrayInsert);
            }
            if ($descPerfilSesion == 'ADMINISTRADOR') {
                $idPersonaSession = null;
            }
            $data['tbBolsaPresupuesto'] = $this->makeHTLMTablaConsulta($this->M_bolsa_presupuesto->getAllBolsaPresupuesto(null, $idPersonaSession, null));

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function makeHTLMTablaConsulta($listaBolsaPresupuesto)
    {

        $html = '
                <table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th># CUENTA</th>
                            <th>CUENTA</th>
                            <th>RESPONSABLE</th>
                            <th>MONTO INICIAL</th>
                            <th>MONTO DISPONIBLE</th>
                            <th>FEC. REGISTRO</th>
                            <th>ACCI&Oacute;N</th>
                        </tr>
                    </thead>

                    <tbody>';
        if ($listaBolsaPresupuesto != '') {
            foreach ($listaBolsaPresupuesto as $row) {

                $html .= '
                        <tr>
                            <td>' . $row->nro_cuenta . '</td>
                            <td>' . $row->desc_cuenta . '</td>
                            <td>' . $row->desc_responsable . '</td>
                            <td>' . $row->monto_inicial . '</td>
                            <td>' . $row->monto_stock . '</td>
                            <td>' . $row->fecha_registro . '</td>
                            <th>
                                <div class="row">
                                <div class="col-sm-4 col-md-3">
                                    <a style="color:blue" data-idbolsa="' . $row->idBolsa . '" data-desc_cuenta="' . $row->desc_cuenta . '" data-montoinicial="' . $row->montoIni_calculo . '" data-montostock="' . $row->montoStock_calculo . '" data-nro_cuenta="' . $row->nro_cuenta . '" onclick="addMonto(this)"><i class="zmdi zmdi-plus-circle"></i></a>
                                </div>
                                <div class="col-sm-4 col-md-3">
                                    <a style="color:blue" data-idbolsa="' . $row->idBolsa . '" data-desc_cuenta="' . $row->desc_cuenta . '" data-montoinicial="' . $row->monto_inicial . '" data-montostock="' . $row->monto_stock . '" data-nro_cuenta="' . $row->nro_cuenta . '" onclick="abrirModalTransacc(this)"><i class="zmdi zmdi-search"></i></a>
                                </div>
                                </div>
                            </th>
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

    // public function filtrarTabla()
    // {
    //     $data['error'] = EXIT_ERROR;
    //     $data['msj'] = null;
    //     $data['cabecera'] = null;
    //     try {

    //         //$zonas = $this->session->userdata('zonasSession');
    //         $itemPlan = $this->input->post('itemplan');
    //         $nombreproyecto = $this->input->post('nombreproyecto');
    //         $nodo = $this->input->post('nodo');
    //         $zonal = $this->input->post('zonal');
    //         $proy = $this->input->post('proy');
    //         $subProy = $this->input->post('subProy');
    //         $estado = $this->input->post('estado');
    //         $tipoPlanta = $this->input->post('tipoPlanta');
    //         //$selectMesPrevEjec = $this->input->post('selectMesPrevEjec');
    //         $filtroPrevEjec = $this->input->post('filtroPrevEjec');

    //         $data['tablaAsigGrafo'] = $this->makeHTLMTablaConsulta($this->m_consulta->getPtrConsultaDiseno($itemPlan, $nombreproyecto, $nodo, $zonal, $proy, $subProy, $estado, $filtroPrevEjec, $tipoPlanta));

    //         $data['error'] = EXIT_SUCCESS;
    //     } catch (Exception $e) {
    //         $data['msj'] = $e->getMessage();
    //     }
    //     echo json_encode(array_map('utf8_encode', $data));
    // }

    public function getResponsables()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $data = $this->makeHTLMResponsables($this->M_bolsa_presupuesto->getAllResponsables());
            $data['comboResponsables'] = $data['comboHTML'];
            $data['error'] = EXIT_SUCCESS;

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function makeHTLMResponsables($listaReponsables)
    {
        $html = '<option value="">Seleccionar Responsable</option>';

        foreach ($listaReponsables as $row) {
            $html .= '<option value="' . $row->id_usuario . '">' . $row->nombre . '</option>';
        }
        $data['comboHTML'] = utf8_decode($html);
        return $data;
    }

    public function getTransacciones()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $nroCuenta = $this->input->post('nroCuenta') ? $this->input->post('nroCuenta') : null;
            if ($nroCuenta != null) {
                $data = $this->makeHTLMTablaTransacciones($this->M_bolsa_presupuesto->getTrasanccionesByNroCuenta($nroCuenta));
                $data['tablaTransacc'] = $data['tablaHTML'];
                $data['error'] = EXIT_SUCCESS;
            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function makeHTLMTablaTransacciones($listaTransacciones)
    {
        $html = '<table style="font-size: 10px;" id="tabla_detalle" class="table table-bordered">
                    <thead class="thead-default">
                        <tr role="row">
                            <th style="text-align:center"># TRANSACCI&Oacute;N</th>
                            <th style="text-align:center"># CUENTA</th>
                            <th style="text-align:center">MONTO AGREGADO</th>
                            <th style="text-align:center">FEC. REGISTRO</th>
                            <th style="text-align:center">USU. REGI.</th>
                            
                            
                        </tr>
                    </thead>

                <tbody>';

        foreach ($listaTransacciones as $row) {

            $html .= '<tr>
                        <td style="text-align:center">' . $row->nro_transaccion . '</td>
                        <td style="text-align:center">' . $row->nro_cuenta . '</td>
                        <td style="text-align:center">' . $row->monto_agregado . '</td>
                        <td style="text-align:center">' . $row->fecha_registro . '</td>
                        <td style="text-align:center">' . $row->nombre . '</td>
                      </tr>';
        }
        $html .= '</tbody>
                  </table>';

        $data['tablaHTML'] = utf8_decode($html);
        return $data;
    }
    
    
    public function probarTramaParalizacion()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            
                $arrayGlobLogTrama = array();
                $arrayGlobLogTramaDespara = array();
                $arrayIPParalizados = array();
                $arrayIPDespara = array();
                
                $listaIPParalizados = $this->m_utils->getIPParalizados();
                if ($listaIPParalizados != null) {
                    foreach ($listaIPParalizados as $row) {
                        $arrayTemp = array(
                            "origen" => 'MODELO TRANFERENCIA WU',
                            "itemplan" => $row->itemplan,
                            "sisego" => $row->indicador,
                            "fecha_registro" => date("Y-m-d h:m:s"),
                            "motivo_error" => null,
                            "descripcion" => null,
                            "estado" => null,
                        );
                        array_push($arrayIPParalizados, $row->itemplan);
                        array_push($arrayGlobLogTrama, $arrayTemp);

                    }
                } else {
                    $arrayIPParalizados = array();
                }
                $motivo = 'SIN CONFIGURACION PEP';
                $comentario = 'FALTA DE CONFIGURACION PEP';
                $nombreUsuario = $this->session->userdata('usernameSession');
                $correo = $this->session->userdata('correo');
                $flgJson = 1;
                if (count($arrayIPParalizados) > 0) {
                    log_message('error', 'entro al if');
                    $dataSend = [
                        'itemplan' => json_encode($arrayIPParalizados),
                        'motivo' => $motivo,
                        'flg_activo' => 1,
                        'comentario' => $comentario,
                        'nombreUsuario ' => $nombreUsuario,
                        'correo' => $correo,
                        'json' => $flgJson,
                        'fecha' => date("Y-m-d")];

                    $url = 'https://172.30.5.10:8080/obras2/recibir_par_masivo.php';
                    $response = $this->m_utils->sendDataToURL($url, $dataSend);
                    
                    $motivoError = '';
                    $descripcion = '';
                    $estado = null;
                    if ($response->error == EXIT_SUCCESS) {
                        log_message('error','entor al success');
                        $motivoError = 'TRAMA COMPLETADA';
                        $descripcion = 'OPERACION REALIZADA CON EXITO';
                        $estado = 1;
                    } else {
                        log_message('error','entor al error');
                        $motivoError = 'FALLA EN LA RESPUESTA DEL HOSTING';
                        $descripcion = 'OPERACION NO COMPLETADA ERROR EN EL SERVIDOR DEL CLIENTE:' . strtoupper($response->mensaje);
                        $estado = 2;
                    }
                    $arrayTempEnvio = array();
					$arrayGlobEnvio = array();
                    foreach ($arrayGlobLogTrama as $row) {
						$arrayTempEnvio = array(
							"origen" => $row['origen'],
                            "itemplan" => $row['itemplan'],
                            "sisego" => $row['sisego'],
                            "fecha_registro" => $row['fecha_registro'],
                            "motivo_error" => $motivoError,
                            "descripcion" => $descripcion,
                            "estado" => $estado
						);
						array_push($arrayGlobEnvio, $arrayTempEnvio);
					}
                    $data = $this->m_utils->insertBatchLogSigoplus($arrayGlobEnvio);
                }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

}
