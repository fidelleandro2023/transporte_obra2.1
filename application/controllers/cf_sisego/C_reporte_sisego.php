<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class C_reporte_sisego extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_sisego/M_reporte_sisego', 'sisego');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index() {




        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {
//            $data['evento'] = $this->configOpex->getEvento();
//            $data['eventos'] = $this->configOpex->getEvento();
            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $data['tabla'] = $this->tablaOpexVacia();
            $permisos = $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_GESTION_MANTENIMIENTO, ID_PERMISO_HIJO_REGISTRO_OPEX, ID_MODULO_GESTION_MANTENIMIENTO, ID_PERMISO_PADRE_MODULO_OPEX);
            $data['opciones'] = $result['html'];
            $this->load->view('vf_sisego/v_reporte_sisego', $data);
        } else {
            redirect('login', 'refresh');
        }
    }
    
    function tablaOpexVacia() {
//        $dataConsulta = $this->sisego->getTablaOpexAll();
        $html = '';


        $html .= '<table id="myTable" class="table table-bordered" style="width:100%">
                   <thead class="thead-default">                    
                    <tr>
                        <th rowspan="2">REGION</th>
                        <th rowspan="2">SIN PRESUPUESTO</th>
                        <th rowspan="2">TOTAL PENDIENTE</th>
                        <th rowspan="2">PEND. OC (GETEC)</th>
                        <th colspan="4">DESPLIEGE RED</th>
                        <th rowspan="2">DESPLIEGE ACELERA</th>
                        <th rowspan="2">PARALIZADOS</th>
                        <th rowspan="2">TOTAL CON PPTO</th>
                        <th rowspan="2">TOTAL EN GESTION</th>
                        <th rowspan="2">IP TERMINADO</th>
                        <th rowspan="2">IP TRUNCO</th>
                        <th rowspan="2">TOTAL</th>
                    </tr>
                    <tr>
                        <th>DISEÑO</th>
                        <th>EN LICENCIA</th>
                        <th>EN APROBACION</th>
                        <th>EN OBRA</th>
                    </tr>
                    <tbody>
                      <tr>
                        <th scope="row">LIMA</th>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                      <tr>
                        <th scope="row">NOR CENTRO</th>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                      <tr>
                        <th scope="row">NOR ORIENTE</th>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                      <tr>
                        <th scope="row">SUR/th>
                        <td></td>
                       <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                      <tr>
                        <th scope="row">TOTAL ITEMPLAN</th>
                        <td>' . ($this->sisego->sin_presupuesto_para() !== null ? count($this->sisego->sin_presupuesto_para()) : 0 ) . '</td>
                        <td>' . ($this->sisego->sin_presupuesto_para() !== null ? count($this->sisego->sin_presupuesto_para()) : 0 ) . '</td>
                        <td>' . ($this->sisego->pendiente() !== null ? count($this->sisego->pendiente()) : 0 ) . '</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>' . ($this->sisego->ip_terminado() !== null ? count($this->sisego->ip_terminado()) : 0 ) . '</td>
//                      <td>' . ($this->sisego->ip_trunco() !== null ? count($this->sisego->ip_trunco()) : 0 ) . '</td>
                        <td></td>
                      </tr>
                    </tbody>
                    </thead>
            </table>';

        return utf8_decode($html);
    }

//    function tablaOpexVacia() {
////        $dataConsulta = $this->sisego->getTablaOpexAll();
//        $html = '';
//
//
//        $html .= '<table id="myTable" class="table table-bordered" style="width:100%">
//                                    <thead class="thead-default">
//                                        <tr>
//                                            <th></th>
//                                            <th></th>
//                                            <th></th>
//                                            <th></th>
//                                            <th></th>
//                                            <th></th>
//                                            <th></th>
//                                            <th></th>
//                                            <th></th>
//                                            <th></th>
//                                            <th></th>
//                                            <th></th>
//                                            <th></th>
//                                        </tr>
//                                    </thead>
//                                    <tbody>
//                                        <tr>
//                                            <th>SIN PRESUPUESTO</th>
//                                            <td>PARALIZADOS</td>
//                                            <td>' . ($this->sisego->sin_presupuesto_para() !== null ? count($this->sisego->sin_presupuesto_para()) : 0 ) . '</td>
//                                            <td>NO PARALIZADOS</td>
//                                            <td>' . ($this->sisego->sin_presupuesto_no_para() !== null ? count($this->sisego->sin_presupuesto_no_para()) : 0 ) . '</td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                        </tr>
//                                        <tr>
//                                            <th>TOTAL PENDIENTE</th>
//                                            <td>PARALIZADOS</td>
//                                            <td>' . ($this->sisego->sin_presupuesto_para() !== null ? count($this->sisego->sin_presupuesto_para()) : 0 ) . '</td>
//                                            <td>NO PARALIZADOS</td>
//                                            <td>' . ($this->sisego->sin_presupuesto_no_para() !== null ? count($this->sisego->sin_presupuesto_no_para()) : 0 ) . '</td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                        </tr>
//                                        <tr>
//                                            <th>PENDT OC (GTEC)</th>
//                                            <td>LIMA</td>
//                                            <td>' . ($this->sisego->pendiente_lima() !== null ? count($this->sisego->pendiente_lima()) : 0 ) . '</td>
//                                            <td>PROVINCIA</td>
//                                            <td>' . ($this->sisego->pendiente_no_lima() !== null ? count($this->sisego->pendiente_no_lima()) : 0 ) . '</td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                        </tr>
//                                        <tr>
//                                            <th>DESPLIEGUE RED</th>
//                                            <td>DISEÑO</td>
//                                            <td>1</td>
//                                            <td>EN LICENICA</td>
//                                            <td>1</td>
//                                            <td>EN APROBACION</td>
//                                            <td>1</td>
//                                            <td>EN OBRA</td>
//                                            <td>1</td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                        </tr>
//                                        <tr>
//                                            <th>DESPLIEGUE ACELERA</th>
//                                            <td>COBRA LIMA</td>
//                                            <td>1</td>
//                                            <td>LARI LIMA</td>
//                                            <td>1</td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                        </tr>
//                                        <tr>
//                                            <th>PARALIZADOS</th>
//                                            <td>LIMA (EXC. COBRA Y LARI)</td>
//                                            <td>1</td>
//                                            <td>PROVINCIA</td>
//                                            <td>1</td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                        </tr>
//                                        <tr>
//                                            <th>TOTAL CON PPTO</th>
//                                            <td>1</td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                        </tr>
//                                        <tr>
//                                            <th>TOTAL EN GESTION</th>
//                                            <td>1</td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                        </tr>
//                                        <tr>
//                                            <th>IP TERMINADOS</th>
//                                            <td>CERRADOS</td>
//                                            <td>' . ($this->sisego->ip_cerrado() !== null ? count($this->sisego->ip_cerrado()) : 0 ) . '</td>
//                                            <td>CERTIFICADOS</td>
//                                            <td>' . ($this->sisego->ip_certificado() !== null ? count($this->sisego->ip_certificado()) : 0 ) . '</td>
//                                            <td>EN CERTIFICACION</td>
//                                            <td>' . ($this->sisego->ip_en_certificacion() !== null ? count($this->sisego->ip_en_certificacion()) : 0 ) . '</td>
//                                            <td>EN VERFICACION</td>
//                                            <td>' . ($this->sisego->ip_en_verificacion() !== null ? count($this->sisego->ip_en_verificacion()) : 0 ) . '</td>
//                                            <td>PRE LIQUIDADOS</td>
//                                            <td>' . ($this->sisego->ip_pre_liquidado() !== null ? count($this->sisego->ip_pre_liquidado()) : 0 ) . '</td>
//                                            <td>TERMINADOS</td>
//                                            <td>' . ($this->sisego->ip_terminado() !== null ? count($this->sisego->ip_terminado()) : 0 ) . '</td>
//                                        </tr>
//                                        <tr>
//                                            <th>IP TRUNCO</th>
//                                            <td>' . ($this->sisego->ip_trunco() !== null ? count($this->sisego->ip_trunco()) : 0 ) . '</td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                        </tr>
//                                        <tr>
//                                            <th>TOTAL</th>
//                                            <td>1</td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                            <td></td>
//                                        </tr>
//                                    </tbody>
//                                </table>';
//
//        return utf8_decode($html);
//    }

    function consultaTablaOpex() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $selectEvento = $this->input->post('selectEvento');
            $selectAno = $this->input->post('selectAno');
            if ($selectEvento == '' && $selectAno == '') {
                throw new Exception('Debe de seleccionar al menos un filtro');
            }
            $data['tablaConsultaConfigOpex'] = $this->getTablaOpex($selectEvento, $selectAno);
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function getTablaOpex($selectEvento, $selectAno) {
        $dataConsulta = $this->configOpex->getTablaOpex($selectEvento, $selectAno);
        $html = '';

        if (count($dataConsulta) == 0) {
            $html .= '<table id="tbOpexDatos" class="table table-bordered" style="width:100%">
                                                <thead class="thead-default">
                                                    <tr>
                                                        <th>ACCION</th>
                                                        <th>CECO</th>
                                                        <th>CUENTA</th>
                                                        <th>AREA FUNCIONAL</th>
                                                        <th>DESCRIPCION</th>
                                                        <th>MONTO PRESUPUESTADO</th>
                                                        <th>MONTO RESERVADO</th>
                                                        <th>MONTO CONSUMIDO</th>
                                                        <th>MONTO DISPONIBLE</th>
                                                        <th>A&Ncaron;O</th>
                                                        <th>ESTADO</th>
                                                    </tr>
                                                </thead>                    
                    <tbody id="tb_body"></tbody></table>';
        } else {
            $html .= '<table id="tbOpexDatos" class="table table-bordered" style="width:100%">
                                                <thead class="thead-default">
                                                    <tr>
                                                        <th>ACCION</th>
                                                        <th>CECO</th>
                                                        <th>CUENTA</th>
                                                        <th>AREA FUNCIONAL</th>
                                                        <th>DESCRIPCION</th>
                                                        <th>MONTO PRESUPUESTADO</th>
                                                        <th>MONTO RESERVADO</th>
                                                        <th>MONTO CONSUMIDO</th>
                                                        <th>MONTO DISPONIBLE</th>
                                                        <th>A&Ncaron;O</th>
                                                        <th>ESTADO</th>
                                                    </tr>
                                                </thead>                    
                                                <tbody id="tb_body">';

            foreach ($dataConsulta as $row) {
                $estado = $row->idEstadoOpex == 1 ? 'ACTIVO' : 'INACTIVO';
                $accion = '<div class="text-center"><a onclick="editar_opex(' . "'" . $row->idOpex . "'" . ')"><i class="zmdi zmdi-edit zmdi-hc-2x"></i></a>'
                        . '&nbsp;&nbsp;&nbsp;<a onclick="eliminar_opex(' . "'" . $row->idOpex . "'" . ')"><i class="zmdi zmdi-close-circle zmdi-hc-2x mdc-text-red"></i></a>'
                        . '&nbsp;&nbsp;&nbsp;<a onclick="historialOpex(' . "'" . $row->idOpex . "'" . ')"><i class="zmdi zmdi-search zmdi-hc-2x mdc-text-red"></i></a></div>';
                $html .= '<tr>
                            <td>' . $accion . '</td>
                            <td>' . $row->ceco . '</td>
                            <td>' . $row->cuenta . '</td>
                            <td>' . $row->areFuncional . '</td>
                            <td>' . $row->opexDesc . '</td>
                            <td>' . $row->montoFinal . '</td>
                            <td>' . $row->montoPro . '</td>
                            <td>' . $row->montoReal . '</td>
                            <td>' . $row->montoDisp . '</td>
                            <td>' . $row->anho . '</td>
                            <td>' . $estado . '</td></tr>';
            }
            $html .= '</tbody></table>';
        }
        return utf8_decode($html);
    }

    public function selectEventoOpex($idOpex) {
        $eventoOpex = $this->configOpex->selectEventoOpex($idOpex);
        $div = '<div class="text-center">';
        foreach ($eventoOpex as $row) {
            $div .= '<span href="#" class="badge badge-pill badge-primary">' . $row->EventoDesc . '</span>&nbsp;&nbsp;';
        }
        $div .= '</div>';
        return utf8_decode($div);
    }

    public function saveTableOpex() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            //
            $inputCecoAdd = $this->input->post('inputCecoAdd');
            $inputCuentaAdd = $this->input->post('inputCuentaAdd');
            $inputAreaFuncional = $this->input->post('inputAreaFuncional');
            $inputMontoFinalAdd = $this->input->post('inputMontoFinalAdd');
            $inputDescAdd = $this->input->post('inputDescAdd');
            $selectAnhoAdd = $this->input->post('selectAnhoAdd');
            //
            $selectEventoAdd = $this->input->post('selectEventoAdd');
            if ($selectEventoAdd[0] == ',') {
                $selectEventoAdd = substr($selectEventoAdd, 1);
            }
            $pos = strpos($selectEventoAdd, ",");
            //
            $arrayDataOP = array(
                'idOpex' => null,
                'ceco' => $inputCecoAdd,
                'cuenta' => $inputCuentaAdd,
                'areFuncional' => $inputAreaFuncional,
                'opexDesc' => $inputDescAdd,
                'monto_final' => $inputMontoFinalAdd,
                'monto_provisional' => 0,
                'monto_real' => 0,
                'monto_dispo' => $inputMontoFinalAdd,
                'fecha_registro' => $this->fechaActual(),
                'anho' => $selectAnhoAdd,
                'idEstadoOpex' => 1
            );
            $data = $this->configOpex->saveConfigOpex($arrayDataOP);
            if ($data['idOpex']) {
                //
                if ($pos === false) {
                    $arrayEventoOpex = array(
                        'idEventoOpex' => null,
                        'idOpex' => $data['idOpex'],
                        'idEvento' => $selectEventoAdd
                    );
                    $this->configOpex->saveEventoOpex($arrayEventoOpex);
                } else {
//                     $selectEventoAddStr = str_replace(",", "", $selectEventoAdd);
                    $selectEventoAddStr = str_replace(",", "", $selectEventoAdd);
                    $selectEventoAddSplit = str_split($selectEventoAddStr);
                    foreach ($selectEventoAddSplit as $row) {
                        $arrayEventoOpex = array(
                            'idEventoOpex' => null,
                            'idOpex' => $data['idOpex'],
                            'idEvento' => $row
                        );
                        $this->configOpex->saveEventoOpex($arrayEventoOpex);
                    }
                }
                //
            } else {
                throw new Exception('Error al Insertar OPEX');
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function getTableOpexId() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $idOpex = $this->input->post('idOpex');
            $data['cuentaOpex'] = $this->configOpex->getTableOpexId($idOpex);
            $data['eventoOpex'] = $this->configOpex->getEventoOpex($idOpex);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

    function updateTableOpex() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            //
            $inputCecoAdd = $this->input->post('inputCecoAdd');
            $inputCuentaAdd = $this->input->post('inputCuentaAdd');
            $inputAreaFuncional = $this->input->post('inputAreaFuncional');
            $inputMontoFinalAdd = $this->input->post('inputMontoFinalAdd');
            $inputDescAdd = $this->input->post('inputDescAdd');
            $idOpex = $this->input->post('idOpex');
            $selectAnhoAdd = $this->input->post('selectAnhoAdd');
            //
            //
            $selectEventoAdd = $this->input->post('selectEventoAdd');
            if ($selectEventoAdd[0] == ',') {
                $selectEventoAdd = substr($selectEventoAdd, 1);
            }
            $pos = strpos($selectEventoAdd, ",");
            //
            $dataUpdate = array(
                'ceco' => $inputCecoAdd,
                'cuenta' => $inputCuentaAdd,
                'areFuncional' => $inputAreaFuncional,
                'opexDesc' => $inputDescAdd,
                'monto_final' => $inputMontoFinalAdd,
                'fecha_registro' => $this->fechaActual(),
                'anho' => $selectAnhoAdd,
                'idEstadoOpex' => 1
            );
            $data = $this->configOpex->updateTableOpex($dataUpdate, $idOpex);
            $this->configOpex->deleteEventoOpex($idOpex);
            if ($idOpex) {
                //
                if ($pos === false) {
                    $arrayEventoOpex = array(
                        'idEventoOpex' => null,
                        'idOpex' => $idOpex,
                        'idEvento' => $selectEventoAdd
                    );
                    $this->configOpex->saveEventoOpex($arrayEventoOpex);
                } else {
//                     $selectEventoAddStr = str_replace(",", "", $selectEventoAdd);
                    $selectEventoAddStr = str_replace(",", "", $selectEventoAdd);
                    $selectEventoAddSplit = str_split($selectEventoAddStr);
                    foreach ($selectEventoAddSplit as $row) {
                        $arrayEventoOpex = array(
                            'idEventoOpex' => null,
                            'idOpex' => $idOpex,
                            'idEvento' => $row
                        );
                        $this->configOpex->saveEventoOpex($arrayEventoOpex);
                    }
                }
                //
            } else {
                throw new Exception('Error al Actualizar OPEX');
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function deleteTableOpex() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            //
            $idOpex = $this->input->post('idOpex');
            //
            $dataUpdate = array(
                'idEstadoOpex' => 3
            );
            $data = $this->configOpex->updateTableOpex($dataUpdate, $idOpex);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone', 'America/Lima');
        setlocale(LC_TIME, "es_ES", "esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }

    function historial() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $id = $this->input->post('idOpex');
            $data['tablaDetalle'] = $this->historialDetalle($id);
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function historialDetalle($id) {
        $id = $this->input->post('idOpex');
        $dataConsulta = $this->configOpex->historialOpex($id);
        $html = '';
        $count = 0;
        if (count($dataConsulta) == 0) {
            $html .= '<table id="tbOpexDetalle" class="table table-bordered" style="width:100%">
                                                <thead class="thead-default">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>TIPO OPERACION</th>
                                                        <th>MONTO DE TRANSACCION</th>
                                                        <th>CODIGO</th>
                                                        <th>FECHA DE TRANSACCION</th>
                                                        <th>TIPO DE ACCION</th>
                                                        <th>ESTADO</th>
                                                    </tr>
                                                </thead>                    
                    <tbody id="tb_body"></tbody></table>';
        } else {
            $html .= '<table id="tbOpexDetalle" class="table table-bordered" style="width:100%">
                                                <thead class="thead-default">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>TIPO DE OPERACION</th>
                                                        <th>MONTO DE TRANSACCION</th>
                                                        <th>CODIGO</th>
                                                        <th>FECHA DE TRANSACCION</th>
                                                        <th>TIPO DE ACCION</th>
                                                        <th>ESTADO</th>
                                                    </tr>
                                                </thead>                    
                                                <tbody id="tb_body">';

            foreach ($dataConsulta as $row) {
                $estado = $row->estadoTransaccion == '1' ? 'PENDIENTE' : 'APROBADO';
                $tipo = $row->tipo == '1' ? 'MAT' : 'MO';
                if ($row->estadoTransaccion == '1') {
                    $fuente = $row->tipo == '1' ? 'REGISTRO DE PO MAT' : 'REGISTRO DE IF';
                } else {
                    $fuente = $row->tipo == '1' ? 'APROBACION DE PO MAT' : 'APROBACION DE OC';
                }

                $count++;
                $html .= '<tr>
                            <td>' . $count . '</td>
                                <td>' . $tipo . '</td>
                            <td>' . $row->montoTransaccion . '</td>
                            <td>' . $row->codigo . '</td>
                            <td>' . $row->fechaTransaccion . '</td>
                            <td>' . $fuente . '</td>
                            <td>' . $estado . '</td></tr>';
            }
            $html .= '</tbody></table>';
        }
        return utf8_decode($html);
    }

}
