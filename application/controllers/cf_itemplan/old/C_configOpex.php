<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class C_configOpex extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_itemplan/M_configOpex', 'configOpex');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index() {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {
            $data['evento'] = $this->configOpex->getEvento();
            $data['eventos'] = $this->configOpex->getEvento();
            $data['fases'] = $this->configOpex->getFases();
            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $data['tablaConsultaConfigOpex'] = $this->tablaOpexVacia();
            $permisos = $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, 238, 282, ID_MODULO_ADMINISTRATIVO);
            $data['opciones'] = $result['html'];
            $this->load->view('vf_itemplan/v_configOpex', $data);
        } else {
            redirect('login', 'refresh');
        }
    }

    function tablaOpexVacia() {
        $dataConsulta = $this->configOpex->getTablaOpexAll();
        $html = '';

        if (count($dataConsulta) == 0) {
            $html .= '<table id="data-table" class="table table-bordered" style="width:100%">
                                                <thead class="thead-default">
                                                    <tr>
                                                        <th>ACCION</th>
                                                        <th>SUBPROYECTO</th>
                                                        <th>CECO</th>
                                                        <th>CUENTA</th>
                                                        <th>AREA FUNCIONAL</th>
                                                        <th>DESCRIPCION</th>
                                                        <th>MONTO PRESUPUESTADO</th>
                                                        <th>MONTO PROYECTADO</th>
                                                        <th>MONTO RESERVADO</th>
                                                        <th>MONTO CONSUMIDO</th>
                                                        <th>MONTO DISPONIBLE</th>
                                                        <th>A&Ncaron;O</th>
                                                        <th>ESTADO</th>
                                                    </tr>
                                                </thead>                    
                    <tbody id="tb_body"></tbody></table>';
        } else {
            $html .= '<table id="data-table" class="table table-bordered" style="width:100%">
                                                <thead class="thead-default">
                                                    <tr>
                                                        <th style="width:10%">ACCION</th>
                                                        <th>SUBPROYECTO</th>
                                                        <th>CECO</th>
                                                        <th>CUENTA</th>
                                                        <th>AREA FUNCIONAL</th>
                                                        <th>DESCRIPCION</th>
                                                        <th>MONTO PRESUPUESTADO</th>
                                                        <th>MONTO PROYECTADO</th>
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
                        . '&nbsp;&nbsp;<a onclick="eliminar_opex(' . "'" . $row->idOpex . "'" . ')"><i class="zmdi zmdi-close-circle zmdi-hc-2x mdc-text-red"></i></a>'
                        . '&nbsp;&nbsp;<a onclick="historialOpex(' . "'" . $row->ceco . "'" . "," . "'" . $row->cuenta . "'" . "," . "'" . $row->areaFuncional . "'" . ')"><i class="zmdi zmdi-search zmdi-hc-2x mdc-text-red"></i></a>'
                        . '&nbsp;&nbsp;<a onclick="historialLogOpex(' . "'" . $row->idOpex . "'" . ')"><i class="zmdi zmdi-hourglass-alt zmdi-hc-2x mdc-text-red"></i></a></div>';
                $html .= '<tr>
                            <td>' . $accion . '</td>
                            <td>' . $this->selectEventoOpex($row->idOpex) . '</td>
                            <td>' . $row->ceco . '</td>
                            <td>' . $row->cuenta . '</td>
                            <td>' . $row->areaFuncional . '</td>
                            <td>' . $row->opexDesc . '</td>
                            <td> S/. ' . $row->monto_inicial_for . '</td>
                            <td> S/. ' . $row->monto_temporal_for . '</td>
                            <td> S/. ' . $row->monto_reservado_for . '</td>
                            <td> S/. ' . $row->monto_consumido_for . '</td>
                            <td>' . $row->monto_real_for . '</td>
                            <td>' . $row->anho . '</td>
                            <td>' . $estado . '</td>';
            }
            $html .= '</tbody></table>';
        }
        return utf8_decode($html);
    }

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

    function consultaTablaOpexNull() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $selectEvento = $this->input->post('selectEvento');
            $selectAno = $this->input->post('selectAno');
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
                                                        <th>SUBPROYECTO</th>
                                                        <th>CECO</th>
                                                        <th>CUENTA</th>
                                                        <th>AREA FUNCIONAL</th>
                                                        <th>DESCRIPCION</th>
                                                        <th>MONTO PRESUPUESTADO</th>
                                                        <th>MONTO PROYECTADO</th>
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
                                                         <th style="width:10%">ACCION</th>
                                                         <th>SUBPROYECTO</th>
                                                        <th>CECO</th>
                                                        <th>CUENTA</th>
                                                        <th>AREA FUNCIONAL</th>
                                                        <th>DESCRIPCION</th>
                                                        <th>MONTO PRESUPUESTADO</th>
                                                        <th>MONTO PROYECTADO</th>
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
                        . '&nbsp;&nbsp;<a onclick="eliminar_opex(' . "'" . $row->idOpex . "'" . ')"><i class="zmdi zmdi-close-circle zmdi-hc-2x mdc-text-red"></i></a>'
                        . '&nbsp;&nbsp;<a onclick="historialOpex(' . "'" . $row->idOpex . "'" . ')"><i class="zmdi zmdi-search zmdi-hc-2x mdc-text-red"></i></a>'
                        . '&nbsp;&nbsp;<a onclick="historialLogOpex(' . "'" . $row->idOpex . "'" . ')"><i class="zmdi zmdi-hourglass-alt zmdi-hc-2x mdc-text-red"></i></a></div>';
                $html .= '<tr>
                            <td>' . $accion . '</td>
                            <td>' . $this->selectEventoOpex($row->idOpex) . '</td>
                            <td>' . $row->ceco . '</td>
                            <td>' . $row->cuenta . '</td>
                            <td>' . $row->areaFuncional . '</td>
                            <td>' . $row->opexDesc . '</td>
                          <td> S/. ' . $row->monto_inicial_for . '</td>
                            <td> S/. ' . $row->monto_temporal_for . '</td>
                            <td> S/. ' . $row->monto_reservado_for . '</td>
                            <td> S/. ' . $row->monto_consumido_for . '</td>
                            <td>' . $row->monto_real_for . '</td>
                            <td>' . $row->anho . '</td>
                            <td>' . $estado . '</td>';
            }
            $html .= '</tbody></table>';
        }
        return utf8_decode($html);
    }

    public function selectEventoOpex($idOpex) {
        $eventoOpex = $this->configOpex->selectEventoOpex($idOpex);
        $div = '<div class="text-center">';
        foreach ($eventoOpex as $row) {
            $div .= '<span style="font-weight: bold;display: inline-block;font-size: 10px;margin-bottom: 5px;padding: 10px;;" href="#" class="badge badge-pill badge-success">' . $row->subProyectoDesc . '</span>&nbsp;&nbsp;';
        }
        $div .= '</div>';
        return utf8_decode($div);
    }

    //20-01-2022
    public function selectEventoOpexSub() {
        $idSubproyecto = $this->input->post('idsubproyecto');
        $eventoOpex = $this->configOpex->selectEventoOpexSub($idSubproyecto);
       
        echo json_encode($eventoOpex);
    }


    public function selectEventoOpexBolsaPep() {
       // $data['msj'] = null;
        //$data['error'] = EXIT_ERROR;
        try {
            $idProyecto     =  $this->input->post('idproyecto');
            $idSubProyecto  = $this->input->post('idsubproyecto');
        
            $data= $this->configOpex->getPepBolsaList($idProyecto, $idSubProyecto);
          
        } catch(Exception $e) {
          
        }
        echo json_encode($data);
    }



    
    public function selectEventoPepCorrelativo() {
        // $data['msj'] = null;
         //$data['error'] = EXIT_ERROR;
         try {
             $pep1     =  $this->input->post('pep1');
            
             $data= $this->configOpex->getPepCorrelativo($pep1);
           
         } catch(Exception $e) {
           
         }
         echo json_encode($data);
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
            //
            $arrayDataOP = array(
                'idOpex' => null,
                'ceco' => $inputCecoAdd,
                'cuenta' => $inputCuentaAdd,
                'areaFuncional' => $inputAreaFuncional,
                'opexDesc' => $inputDescAdd,
                'monto_inicial' => $inputMontoFinalAdd,
                'monto_temporal' => $inputMontoFinalAdd,
                'monto_real' => $inputMontoFinalAdd,
                'fecha_registro' => $this->fechaActual(),
                'anho' => $selectAnhoAdd,
                'idEstadoOpex' => 1
            );
            $data = $this->configOpex->saveConfigOpex($arrayDataOP,$inputCecoAdd,$inputCuentaAdd,$selectAnhoAdd,$inputAreaFuncional,$selectEventoAdd);
            if ($data['idOpex']) {
                //
                if (!$pos) {
                    $arrayEventoOpex = array(
                        'idSubproyectoOpex' => null,
                        'idOpex' => $data['idOpex'],
                        'idSubproyecto' => $selectEventoAdd
                    );
                    $this->configOpex->saveEventoOpex($arrayEventoOpex);
                } else {
                    $selectEventoAddStr = explode(",", $selectEventoAdd);
                    foreach ($selectEventoAddStr as $row) {
                        $arrayEventoOpex = array(
                            'idSubproyectoOpex' => null,
                            'idOpex' => $data['idOpex'],
                            'idSubproyecto' => $row
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
            $inputMontoFinalorigin = $this->input->post('inputMontoFinalorigin');
            $montoAdicional = $this->input->post('montoAdicional');
            $inputMontoTemporalorigin = $this->input->post('inputMontoTemporalorigin');
            //$selectEventoAddSplit = str_split($selectEventoAddStr);
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
                'areaFuncional' => $inputAreaFuncional,
                'opexDesc' => $inputDescAdd,
                'monto_inicial' => $inputMontoFinalAdd,
                'monto_temporal' => $inputMontoTemporalorigin + $montoAdicional,
                'monto_real' => $inputMontoFinalorigin + $montoAdicional,
                'fecha_registro' => $this->fechaActual(),
                'anho' => $selectAnhoAdd,
                'idEstadoOpex' => 1
            );

            $dataUpdateLog = array(
                'idOpex' => $idOpex,
                'monto_actual' => $inputMontoFinalorigin,
                'monto_update' => $inputMontoFinalAdd,
                'usr' => $this->session->userdata('idPersonaSession'),
                'comentario' => 'ACTUALIZACION',
                'fecha_registro' => $this->fechaActual()
            );

            $data = $this->configOpex->updateTableOpex($dataUpdate, $idOpex, $dataUpdateLog,$inputCecoAdd,$inputCuentaAdd,$selectAnhoAdd,$inputAreaFuncional,$selectEventoAdd);
            $this->configOpex->deleteEventoOpex($idOpex);
            if ($idOpex) {
                //
                if (!$pos) {
                    $arrayEventoOpex = array(
                        'idSubproyectoOpex' => null,
                        'idOpex' => $idOpex,
                        'idSubproyecto' => $selectEventoAdd
                    );
                    $this->configOpex->saveEventoOpex($arrayEventoOpex);
                } else {
                    $selectEventoAddStr = explode(",", $selectEventoAdd);
                    foreach ($selectEventoAddStr as $row) {
                        $arrayEventoOpex = array(
                            'idSubproyectoOpex' => null,
                            'idOpex' => $idOpex,
                            'idSubproyecto' => $row
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

    function log() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $id = $this->input->post('idOpex');
            $data['tablaLog'] = $this->historialLog($id);
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function historialDetalle($id) {
        $ceco = $this->input->post('ceco');
        $cuenta = $this->input->post('cuenta');
        $area = $this->input->post('area');
        $dataConsulta = $this->configOpex->historialOpex($ceco, $cuenta, $area);
        $html = '';
        $count = 0;
        if (count($dataConsulta) == 0) {
            $html .= '<table id="tbOpexDetalle" class="table table-bordered" style="width:100%">
                                                <thead class="thead-default">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>MONTO DE TRANSACCION</th>
                                                        <th>CODIGO</th>
                                                        <th>FECHA DE TRANSACCION</th>
                                                        <th>ESTADO</th>
                                                        <th>FUENTE</th>
                                                    </tr>
                                                </thead>                    
                    <tbody id="tb_body"></tbody></table>';
        } else {
            $html .= '<table id="tbOpexDetalle" class="table table-bordered" style="width:100%">
                                                <thead class="thead-default">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>MONTO DE TRANSACCION</th>
                                                        <th>CODIGO</th>
                                                        <th>FECHA DE TRANSACCION</th>
                                                        <th>ESTADO</th>
                                                        <th>FUENTE</th>
                                                    </tr>
                                                </thead>                    
                                                <tbody id="tb_body">';

            foreach ($dataConsulta as $row) {
                $estado = $row->estadoTransaccion == '1' ? 'PENDIENTE' : 'APROBADO';
                $fuente = $row->tipo == '1' ? 'PO' : 'ORDEN DE COMPRA';
                $count++;
                $html .= '<tr>
                            <td>' . $count . '</td>
                            <td>' . $row->montoTransaccion . '</td>
                            <td>' . $row->codigo . '</td>
                            <td>' . $row->fechaTransaccion . '</td>
                            <td>' . $estado . '</td>
                            <td>' . $fuente . '</td></tr>';
            }
            $html .= '</tbody></table>';
        }
        return utf8_decode($html);
    }

    public function historialLog($id) {
        $id = $this->input->post('idOpex');
        $dataConsulta = $this->configOpex->historialLog($id);
        $html = '';
        $count = 0;
        if (count($dataConsulta) == 0) {
            $html .= '<table id="tbOpexDetalleLog" class="table table-bordered" style="width:100%">
                                                <thead class="thead-default">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>USUARIO</th>
                                                        <th>MONTO</th>
                                                        <th>MONTO UPDATE</th>
                                                        <th>FECHA</th>
                                                    </tr>
                                                </thead>                    
                    <tbody id="tb_body"></tbody></table>';
        } else {
            $html .= '<table id="tbOpexDetalleLog" class="table table-bordered" style="width:100%">
                                                <thead class="thead-default">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>USUARIO</th>
                                                        <th>MONTO</th>
                                                        <th>MONTO UPDATE</th>
                                                        <th>FECHA</th>
                                                    </tr>
                                                </thead>                    
                                                <tbody id="tb_body">';

            foreach ($dataConsulta as $row) {
                $count++;
                $html .= '<tr>
                            <td>' . $count . '</td>
                            <td>' . $row->usuario . '</td>
                            <td>' . $row->monto_actual . '</td>
                            <td>' . $row->monto_update . '</td>
                            <td>' . $row->fecha_registro . '</td></tr>';
            }
            $html .= '</tbody></table>';
        }
        return utf8_decode($html);
    }

}
