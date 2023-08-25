<?php

defined('BASEPATH') or exit('No direct script access allowed');

class C_detalle_itemfault extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_detalle_obra/m_detalle_obra');
        $this->load->model('mf_reportes_v/m_itemplan_ptr');
        $this->load->model('mf_itemfault/M_consulta', 'consulta');
        $this->load->model('mf_itemfault/m_detalle_itemfault');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index() {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {

            $item = (isset($_GET['item']) ? $_GET['item'] : '');
            $est = (isset($_GET['from']) ? $_GET['from'] : '');
            $idEstacionEje = (isset($_GET['estacion']) ? $_GET['estacion'] : '');


            $data['item'] = $item;
            $data['from'] = $est;

            $data['estadoItemplan'] = $this->m_detalle_obra->getItemEstado($item);
            $data['idSubProy'] = $this->m_detalle_obra->getIPSubProy($item);

            $data['listaEstaciones'] = $this->makeHTLMTEstaciones($item, $est, $data['estadoItemplan'], $idEstacionEje, $data['idSubProy']);

            //EDITAR
            $data['listaEstacionesEdit'] = $this->makeHTLMTEstacionesEdit($this->consulta->getAllEstaciones($item), $item);
            //AGREGAR
            $data['listaEstacionesInsert'] = $this->makeHTLMTEstacionesInsert($this->consulta->getAllEstaciones($item), $item);

            $data['listaEECC'] = $this->m_utils->getAllEECC();
            $data['listaZonal'] = $this->m_utils->getAllZonal();
            $data['listaSubProy'] = $this->m_utils->getAllSubProyecto();

            $data['tablaAsigGrafo'] = $this->makeHTLMTablaItemPtr($this->m_itemplan_ptr->getWebUnificadaFa('', '', '', ''));
            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $permisos = $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_REPORTES_V, ID_PERMISO_HIJO_DETALLE_OBRA, ID_MODULO_GESTION_OBRA);
            $data['opciones'] = $result['html'];

            $this->load->view('vf_itemfault/v_detalle_itemfault', $data);
        } else {
            redirect('login', 'refresh');
        }
    }

    // bloque agregar

    public function makeHTLMTEstacionesInsert($listaEstaciones, $item) {
        $html = '';
        foreach ($listaEstaciones->result() as $row) {
            if ($row->has_vali == 0) {
                $html .= '
                <div class="col-md-6">
                    <div class="card" style="border: 1px solid lightgrey">
                        <div class="card-header">
                            <h2 class="card-title text-center">' . $row->estacionDesc . '</h2>
                        </div>
                        <div class="card-block">
                            <div class="row">';

                $html .= $this->makeHTLMTAreasInsert($this->m_detalle_obra->getAllAreasByEstacion($item, $row->estacionDesc), $row->estacionDesc, $item);

                $html .= '</div>
                        </div>


                    </div>
                </div>
                ';
            }
        }
        return utf8_decode($html);
    }

    public function makeHTLMTAreasInsert($data, $estacionDesc, $item) {

        $htmlI = '';

        foreach ($data->result() as $row) {

            $htmlI .= '
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>' . utf8_encode($row->areaDesc) . '</label>
                            <input type="text" data-tipo="' . $row->tipoArea . '" data-item="' . $item . '" data-subproyectoestacion="' . $row->idSubProyectoEstacion . '" data-area="' . utf8_encode($row->areaDesc) . '" class="form-control input-mask insertar" name="ptrInsert[]" placeholder="" style="    border-bottom: 1px solid grey;">
                            <i class="form-group__bar"></i>
                        </div>
                    </div>';
        }

        return utf8_decode($htmlI);
    }

    public function makeHTLMTEstaciones($item, $est = null, $estadoItemplan, $idEstacionEje = null, $idSubProyecto) {
        $dataArray = $this->consulta->getDataDiseno($item);
        $html = '';
        $cards = 0;
        $tituloEstacion = '';

        foreach ($dataArray as $row) {
            $hrefPo = null;

            $arrayAreas_1 = explode(',', $row->concat_areas);

            $tituloEstacion = '<h2 class="card-title text-center">' . $row->estacionDesc . '</h2>';
            $cards += 1;
            $html .= '
                        <div class="col-md-3">
                            <div class="card" style="border: 1px solid lightgrey">
                                <div class="card-header" style="padding: 10px">
                                    ' . $tituloEstacion . '
                                </div>
                                <div class="row">';
            for ($i = 0; count($arrayAreas_1) - 1 >= $i; $i++) {
                $arrayAreas_2 = explode('|', $arrayAreas_1[$i]);
                $idArea = $arrayAreas_2[0];
                if ($est == 1) {
                    $hrefPo = null;
                } else {
                    $hrefPo = 'href="getRegItemfaultPo?item=' . $item . '&tipo_po=' . utf8_decode($arrayAreas_2[2]) . '&form=1&estaciondesc=' . utf8_decode($row->estacionDesc) . '&estacion=' . $row->idEstacion . '&area=' . $idArea . '"';
                }

                $html .= '<div class="col-md-6">
                                                    <table class="table mb-0">
                                                        <thead >
                                                        <tr>
                                                            <a style="color:#8a8a5c" ' . $hrefPo . ' target="_blank">' . utf8_decode($arrayAreas_2[1]) . '</a>
                                                        </tr>
                                                        </thead>
                                                        <tbody>';
                $arrayPo = $this->consulta->getItemfaultPo($item, $idArea, $row->idEstacion);
                foreach ($arrayPo as $r) {
                    $html .= '
                                                                <tr style="background-color: ' . $r['fondo'] . '; color: black">
                                                                    <td><a class="" data-ptr="' . $r['codigo_po'] . '" data-item="' . $r['itemfault'] . '"  data-estacion="' . $row->idEstacion . '" data-id_area="' . $idArea . '" onclick="poDetalleItemfault(this)" id="' . $r['codigo_po'] . '">' . $r['codigo_po'] . '</a></td>
                                                                </tr>
                                                                ';
                }
                $html .= '</tbody>
                                                        </table>
                                                    </div>';
            }

            // $html .= $this->makeHTLMTAreas($this->m_detalle_obra->getAllAreasByEstacion($item, $row->estacionDesc), $row->estacionDesc, $item, $est, $estadoItemplan, $row->idEstacion, $idEstacionEje, $row->has_vali, $idSubProyecto);
            $html .= '
                                </div>
                            </div>
                        </div>';
            $tituloEstacion = '';
        }
        if ($cards == 0) {
            $html = '
                    <div class="col-md-12">
                        <div class="panel-info">
                            <h3 class="text-center">No hay asociaciones disponibles. </h3>
                            <hr>
                            <h4 class="text-center">Agregue una estacion al subproyecto para continuar.</h4>
                        </div>
                    </div>
                    ';
        }
        return utf8_decode($html);
    }

    public function poDetalleItemfault() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $ptrAjax = $this->input->post('codigo_po');
            $itemplan = $this->input->post('itemfault');
            $idEstacion = $this->input->post('idEstacion');
            $areaDesc = $this->input->post('idArea');

            $datos = $this->m_detalle_obra->getAllWebUnificada($ptrAjax);
            $html = '';
            $htmlLOG = '';
            $htmlPreCancel = '';
            $htmlDetallePO = '';
            $htmlPresupuesto = '';
            $idSubProyectoEstacion = null;
            $pep1 = null;
            $vr = null;
            $grafo = null;
            $htmlPartidas = '';

            $arrayDetPO = null;

            $rowDataPo = $this->m_detalle_itemfault->getDataPo($ptrAjax);


            $arrayLogPO = $this->m_detalle_itemfault->getLogPoItemfault($ptrAjax);

            $htmlLOG .= '<table id="tabla_log" class="table table-bordered">
                                <thead class="thead-default">
                                    <tr>
                                        <th>ESTADO</th>
                                        <th>FECHA</th>
                                        <th>USUARIO</th>
                                    </tr>
                                </thead>
                                <tbody>';

            foreach ($arrayLogPO as $row) {
                $htmlLOG .= ' <tr>
                                    <th>' . $row['estado'] . '</th>
                                    <td>' . $row['fecha_registro'] . '</td>
                                    <td>' . $row['nombre'] . '</td>
                                </tr>';
            }
            $htmlLOG .= '</tbody>
                        </table>';

            $ListaDetallePO = $this->m_detalle_itemfault->getDetallePoMat($ptrAjax);

            $htmlDetallePO .= '<table id="tbValeReserva" class="table table-bordered">
                                    <thead class="thead-default">
                                        <tr>
                                            <th>MATERIAL</th>
                                            <th>DESCRIPCION</th>
                                            <th>COSTO MAT</th>
                                            <th>CANT. ING.</th>
											<th>TOTAL</th>
                                        </tr>
                                    </thead>
                                    <tbody>';

            foreach ($ListaDetallePO as $row) {
                $htmlDetallePO .= ' <tr>
                                            <th>' . $row['codigo_material'] . '</th>
                                            <td>' . utf8_decode($row['descrip_material']) . '</td>
                                            <td>' . $row['costo_material'] . '</td>
                                            <td>' . $row['cantidad_final'] . '</td>
											<td>' . $row['totalxMat'] . '</td>
                                        </tr>';
            }
            $htmlDetallePO .= '</tbody>
                    </table>';



            $htmlPresupuesto .= ' <table id="table-presupuesto" class="table table-bordered">
                                        <thead class="thead-default">
                                            <tr>
                                            <th style="font-size:12px;">PEP1</th>
                                            <th style="font-size:12px;">PEP2</th>
                                            <th style="font-size:12px;">GRAFO</th>
                                            <th style="font-size:12px;">NRO CERT.</th>
                                            <th style="font-size:12px;">ORDEN DE COMPRA</th>
                                            <th style="font-size:12px;">VALE DE RESERVA</th>
                                            <th style="font-size:12px;">MONTO</th>
                                        </tr>
                                        </thead>';

            if ($rowDataPo['flg_tipo_area'] == 1) { // PO MATERIAL
                $htmlPresupuesto .= '
                                        <tbody>
                                            <tr>
                                                <th></th>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td>' . $rowDataPo['vr'] . '</td>
                                                <td>' . number_format($rowDataPo['costo_total'], 2) . '</td>
                                            </tr>
                                        </tbody>
                                        </table>';
            } else if ($rowDataPo['flg_tipo_area'] == 2) {// PO MO
                $arrayDetMO = $this->m_detalle_obra->getDetalleCertMOByPO($ptrAjax);
                $htmlPresupuesto .= '
                                        <tbody>
                                            <tr>
                                                <th>' . $arrayDetMO['pep1'] . '</th>
                                                <td></td>
                                                <td></td>
                                                <td>' . $arrayDetMO['nro_certificacion'] . '</td>
                                                <td>' . $arrayDetMO['orden_compra'] . '</td>
                                                <td></td>
                                                <td>' . number_format($arrayDetMO['monto_mo'], 2) . '</td>
                                            </tr>
                                        </tbody>
                                        </table>';
            }




            if ($arrayDetPO['idPoestado'] != PO_PRECANCELADO && $arrayDetPO['idPoestado'] != PO_CANCELADO && $arrayDetPO['idPoestado'] != PO_LIQUIDADO && $arrayDetPO['idPoestado'] != PO_VALIDADO && $arrayDetPO['idPoestado'] != PO_CERTIFICADO && $arrayDetPO['idTipoPlanta'] != 2) {
                $htmlPreCancel .= '<div class="col-sm-4 col-md-4">
                                            <div class="form-group">
                                                <label class="control-label"></label>
                                                <button id="btnPreCancelar" type="button" class="btn btn-danger form-control" onclick="openModalMotivoPreCancelacion()">Solicitar Cancelacion</button>
                                            </div>
                                        </div>';
            }
            $needSiropeAndFT = true;
            $infoProySubProy = $this->m_utils->getProyectoSubProyectoByItemplan($itemplan);
            if ($infoProySubProy['idProyecto'] == ID_PROYECTO_OBRA_PUBLICA || $infoProySubProy['idSubProyecto'] == 146/* MEGAPROYECTO FTTH */ || $infoProySubProy['idSubProyecto'] == 182/* FTTH MIXTO */ || $infoProySubProy['idSubProyecto'] == 7/* FTTH AMPLIACION */ || $infoProySubProy['idSubProyecto'] == 8/* FTTH NUEVO */) {
                $needSiropeAndFT = false;
            }
            if ($arrayDetPO['idPoestado'] == PO_REGISTRADO && $arrayDetPO['flg_tipo_area'] == 2 && $arrayDetPO['idEstacion'] != ID_ESTACION_DISENIO && ($arrayDetPO['porcentaje'] == '100' || !$needSiropeAndFT)) {//MO 
                $htmlPreCancel .= '<div class="col-sm-4 col-md-4">
                                            <div class="form-group">
                                                <label class="control-label"></label>
                                                <a href="liquiMo?item=' . $arrayDetPO['itemPlan'] . '&from=1&estaciondesc=' . $arrayDetPO['estacionDesc'] . '&estacion=' . $arrayDetPO['idEstacion'] . '&poCod=' . $arrayDetPO['codigo_po'] . '" type="button" class="btn btn-success form-control">Liquidar PO MO</a>
                                            </div>
                                        </div>';
            }

            $html .= '  <div class="col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label>PO</label>
                                    <input id="contCodigoPO" type="text" class="form-control" value="' . $rowDataPo['codigo_itemfault_po'] . '" disabled>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-2">
                                <div class="form-group">
                                    <label>ESTADO</label>
                                    <input id="contEstadoPO" type="text" class="form-control" value="' . $rowDataPo['estado'] . '" disabled>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-6">
                                <div class="form-group">
                                    <label>NOMBRE ITEMFAULT</label>
                                    <input id="contProyecto" type="text" class="form-control" value="' . $rowDataPo['nom_itemfault'] . '" disabled>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4">
                                <div class="form-group has-feedback" style="">
                                    <label>SERVICIO ELEM.</label>
                                    <input id="contSubProyecto" type="text" class="form-control"  value="' . $rowDataPo['elementoDesc'] . '" disabled>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4">
                                <div class="form-group has-feedback" style="">
                                    <label>VR</label>
                                    <input id="contVR" type="text" class="form-control" value="' . $rowDataPo['vr'] . '" disabled>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label class="control-label">AREA</label>
                                    <input id="contAreaDesc" type="text" class="form-control" value="' . $rowDataPo['areaDesc'] . '" disabled>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label class="control-label">ZONAL</label>
                                    <input id="contJefatura" type="text" class="form-control" value="' . $rowDataPo['zonalDesc'] . '" disabled>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label class="control-label">EE.CC.</label>
                                    <input id="contEmpresacolab" type="text" class="form-control" value="' . $rowDataPo['empresaColabDesc'] . '" disabled>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label class="control-label">MONTO</label>
                                    <input id="contoMontoTotal" type="text" class="form-control" value="' . number_format($rowDataPo['costo_total'], 2) . '" disabled>
                                </div>
                            </div>


                             ' . $htmlPreCancel . ' ';

            /*             * ************************** detalle partidas ****************** */
            $listaPartidasMO = $this->m_detalle_itemfault->getDetallePoMo($ptrAjax);

            $htmlPartidas .= '<table id="tabla_partidas" class="table table-bordered">
                                        <thead class="thead-default">
                                            <tr>
                                                <th>CODIGO</th>
                                                <th>DESCRIPCION</th>
                                                <th>TIPO</th>
                                                <th>COSTO</th>
                                                <th>BAREMO</th>
                                                <th>CANTIDAD INICIAL</th>
                                                <th>CANTIDAD FINAL</th>
                                                <th>TOTAL</th>
                                            </tr>
                                        </thead>
                                        <tbody>';

            foreach ($listaPartidasMO as $row) {
                $htmlPartidas .= ' <tr>
                                                <th>' . $row['codigo'] . '</th>
                                                <td>' . utf8_decode($row['descripcion']) . '</td>
                                                <td>' . $row['descPrecio'] . '</td>
                                                <td>' . $row['costo'] . '</td>
                                                <td>' . $row['baremo'] . '</td>
                                                <td>' . $row['cantidad_inicial'] . '</td>
                                                <td>' . $row['cantidad_final'] . '</td>
                                                <td>' . $row['monto_final'] . '</td>                                                            
                                            </tr>';
            }
            $htmlPartidas .= '</tbody>
                        </table>';

            $data['prueba'] = $html;
            $data['tablaLOG'] = $htmlLOG;
            $data['tablaVR'] = $htmlDetallePO;
            $data['tablaPresu'] = $htmlPresupuesto;
            $data['tablaPartidas'] = $htmlPartidas;
            $data['idSubProEsta'] = $idSubProyectoEstacion;
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function getColorFromEstado($data, $est) {

        foreach ($data->result() as $row) {
            $estados = explode(",", $row->rangoPoDesc);
            if (in_array(trim(substr($est, 0, 3)), $estados, true)) { //ARRAY 1
                return $row->colorPo;
            }
        }
        return '#ffffff';
    }

    public function makeHTLMTablaItemPtr($listaPTR) {
        $data = $this->m_itemplan_ptr->getRangoPtr();
        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>ITEM PLAN</th>
                            <th>CENTRAL</th>
                            <th>INDICADOR</th>
                            <th>ZONAL</th>
                            <th>EECC</th>
                            <th>MAT_COAX</th>
                            <th>MAT_COAX_OC</th>
                            <th>MAT_FUENTE</th>
                            <th>MAT_FO</th>
                            <th>MAT_FO_OC</th>
                            <th>MAT_ENER</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ITEM PLAN</th>
                            <th>CENTRAL</th>
                            <th>INDICADOR</th>
                            <th>ZONAL</th>
                            <th>EECC</th>
                            <th>MAT_COAX</th>
                            <th>MAT_COAX_OC</th>
                            <th>MAT_FUENTE</th>
                            <th>MAT_FO</th>
                            <th>MAT_FO_OC</th>
                            <th>MAT_ENER</th>
                        </tr>
                    </tfoot>
                    <tbody>';

        foreach ($listaPTR->result() as $row) {

            $html .= ' <tr>
                            <th>' . $row->itemPlan . '</th>
                            <td>' . $row->cod_central . '</td>
                            <td>' . $row->indicador . '</td>
                            <td>' . $row->zonal . '</td>
                            <td>' . $row->eecc . '</td>
                            <td style="background-color:' . $this->getColorFromEstado($data, $row->mat_coax_est) . '; color:white">' . $row->mat_coax_ptr . '</td>
                            <td style="background-color:' . $this->getColorFromEstado($data, $row->mat_coax_oc_est) . '; color:white">' . $row->mat_coax_oc_ptr . '</td>
                            <td style="background-color:' . $this->getColorFromEstado($data, $row->mat_fuente_est) . '; color:white">' . $row->mat_fuente_ptr . '</td>
                            <td style="background-color:' . $this->getColorFromEstado($data, $row->mat_fo_est) . '; color:white">' . $row->mat_fo_ptr . '</td>
                            <th style="background-color:' . $this->getColorFromEstado($data, $row->mat_fo_oc_est) . '; color:white">' . $row->mat_fo_oc_ptr . '</th>
                            <th style="background-color:' . $this->getColorFromEstado($data, $row->mat_ener_est) . '; color:white">' . $row->mat_ener_ptr . '</th>
                        </tr>';
        }
        $html .= '</tbody>
                </table>';

        return utf8_decode($html);
    }

    public function filtrarTabla() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $SubProy = $this->input->post('subProy');
            $eecc = $this->input->post('eecc');
            $zonal = $this->input->post('zonal');
            $mesEjec = $this->input->post('mes');
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaItemPtr($this->m_itemplan_ptr->getWebUnificadaFa($SubProy, $eecc, $zonal, $mesEjec));
            $data['error'] = EXIT_SUCCESS;
            //log_message('error', '-->$SubProy:'.$SubProy.' $eecc:'.$eecc.' $zonal:'.$zonal,true);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    // Metodos de editar

    public function makeHTLMTEstacionesEdit($listaEstaciones, $item) {
        $htmlE = '';
        foreach ($listaEstaciones->result() as $row) {
            if ($row->has_vali == 0) {
                $htmlE .= '
                <div class="col-md-6">
                    <div class="card" style="border: 1px solid #C9CDCF;">
                        <div class="card-header">
                            <h2 class="card-title text-center">' . $row->estacionDesc . '</h2>

                        </div>
                        <div class="card-block">
                            <div class="row">';
                $htmlE .= $this->makeHTLMTAreasEdit($this->m_detalle_obra->getAllAreasByEstacion($item, $row->estacionDesc), $row->estacionDesc, $item);

                $htmlE .= '</div>
                        </div>
                    </div>
                </div>
                ';
            }
        }
        return utf8_decode($htmlE);
    }

    public function makeHTLMTAreasEdit($data, $estacionDesc, $item) {
        $html = '';
        foreach ($data->result() as $row) {
            $html .= '
        <div class="col-sm-6">
            <label>' . $row->areaDesc . '</label>';
            $html .= $this->makeHTLMPTREdit($this->m_detalle_obra->getAllPTRbyArea($item, $estacionDesc, $row->areaDesc), $item);

            $html .= '</div>
        ';
        }
        return $html;
    }

    public function makeHTLMPTREdit($data, $item) {
        $html = '';
        foreach ($data->result() as $row) {
            $html .= '
            <div class="form-group">

                <input type="text" data-item="' . $item . '" data-idsubproyestacion="' . $row->idSubProyectoEstacion . '" class="form-control input-mask editar" name="ptrEdit[]"  value="' . $row->poCod . '" style="    border-bottom: 1px solid grey;">
                <i class="form-group__bar"></i>

            </div>
            ';
        }
        return $html;
    }

    public function recogeEditar() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;

        try {
            $itemTitle = $this->input->post('itemTitle');
            $jsonNamesEdit = $this->input->post('jsonNamesEdit');
            $arrayNamesEdit = json_decode($jsonNamesEdit, true);

            $i = 0;

            foreach ($arrayNamesEdit as $row) {

                //$row[$i];
                //log_message('error', '-->dato de row es:'.$row,true);
                $subrows = explode("/", $row);

                $defaultValueEdit = $subrows[0];
                $newValueEdit = $subrows[1];
                $itemEdit = $subrows[2];
                $idsubproyestacionEdit = $subrows[3];

                if ($defaultValueEdit != $newValueEdit) {
                    if ($newValueEdit == '') {
                        //delete
                        //log_message('error', 'Se hara un delete a esta ptr');

                        $data = $this->m_detalle_obra->deletePTR($itemEdit, $defaultValueEdit, $idsubproyestacionEdit);

                        /*                         * *************log ptr eliminacion***************** */
                        $modificacion = "Eliminacion PTR DE " . $idsubproyestacionEdit;

                        $dataLog = $this->m_detalle_obra->updatePTRenLog($itemEdit, $modificacion, $defaultValueEdit, $this->session->userdata('idPersonaSession'));

                        /*                         * ************************************** */
                    } else {
                        $data = $this->m_detalle_obra->updatePTR($newValueEdit, $itemEdit, $defaultValueEdit, $idsubproyestacionEdit);

                        /*                         * *************log ptr edicion***************** */
                        $modificacion = "Modificacion de nro de PTR DE " . $defaultValueEdit . " a " . $newValueEdit . ".";
                        $dataLog = $this->m_detalle_obra->updatePTRenLog($itemEdit, $modificacion, $defaultValueEdit, $this->session->userdata('idPersonaSession'));
                        /*                         * ************************************** */
                    }
                    $i++;
                }
            }
            $idEstadoPlan = $this->m_detalle_obra->getItemEstado($itemTitle);
            $data['listaEstaciones'] = $this->makeHTLMTEstaciones($this->consulta->getAllEstaciones($itemTitle), $itemTitle, null, $idEstadoPlan);
            $data['listaEstacionesEdit'] = $this->makeHTLMTEstacionesEdit($this->consulta->getAllEstaciones($itemTitle), $itemTitle);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        echo json_encode(array_map('utf8_encode', $data));
    }

    public function recogeInsertar() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        $resultado = 'resultado';
        $idUser = $this->session->userdata('idPersonaSession');
        try {

            $jsonNamesInsert = $this->input->post('jsonNamesInsert');
            $arrayNamesInsert = json_decode($jsonNamesInsert, true);

            $i = 0;

            foreach ($arrayNamesInsert as $row) {

                //$row[$i];
                $sub_rows = explode("/", $row);

                $valueInsert = $sub_rows[0];
                $itemInsert = $sub_rows[1];
                $idsubproyectoestacionInsert = $sub_rows[2];
                $areaInsert = $sub_rows[3];

                // Segunda Validacion
                $flag = $this->validacion2($this->m_detalle_obra->findPTR($valueInsert));

                if ($flag == 'libre') {
                    $this->db->trans_begin();
                    $data = $this->m_detalle_obra->insertPTR($itemInsert, $valueInsert, $idsubproyectoestacionInsert);
                    // Insertar en DET SI NO ESTA WU
                    $exists = $this->findEnWU($this->m_detalle_obra->findEnWU($valueInsert));

                    if ($exists == 0) {
                        if ($this->db->trans_status() === false) {
                            $this->db->trans_rollback();
                            throw new Exception("ERROR4");
                        } else {

                            $this->m_detalle_obra->insertEnWU($valueInsert);
                            if ($this->db->trans_status() === false) {
                                $this->db->trans_rollback();
                                throw new Exception("ERROR5");
                            } else {
                                $this->m_detalle_obra->insertEnWUDet($valueInsert);
                                if ($this->db->trans_status() === false) {
                                    $this->db->trans_rollback();
                                    throw new Exception("ERROR6");
                                } else {
                                    $this->m_detalle_obra->getGrafoOnePTR($valueInsert);
                                    if ($this->db->trans_status() === false) {
                                        $this->db->trans_rollback();
                                        throw new Exception("ERROR6");
                                    } else {
                                        $this->db->trans_commit();
                                        $data['error'] = EXIT_SUCCESS;
                                        $data['msj'] = 'Se registro correctamente.';
                                    }
                                }
                            }
                        }
                        log_message('error', 'FIN EN Procedimiento, mensaje es : ' . $data['msj'], true);
                    } else {

                        log_message('error', 'YA existe en WU,SE DEBE CONTINUAR, existe es: ' . $exists, true);

                        if ($this->db->trans_status() === false) {
                            $this->db->trans_rollback();
                            throw new Exception("ERROR1");
                        } else {
                            $this->m_detalle_obra->deleteEnWebUnitDet($valueInsert);
                            if ($this->db->trans_status() === false) {
                                $this->db->trans_rollback();
                                throw new Exception("ERROR2");
                            } else {
                                $this->m_detalle_obra->selectDet($valueInsert);
                                if ($this->db->trans_status() === false) {
                                    $this->db->trans_rollback();
                                    throw new Exception("ERROR3");
                                } else {
                                    $this->m_detalle_obra->getGrafoOnePTR($valueInsert);
                                    if ($this->db->trans_status() === false) {
                                        $this->db->trans_rollback();
                                        throw new Exception("ERROR6");
                                    } else {
                                        $this->db->trans_commit();
                                        $data['error'] = EXIT_SUCCESS;
                                        $data['msj'] = 'Se registro correctamente.';
                                    }
                                }
                            }
                        }
                    }

                    // Insert creacion en Log
                    $this->m_detalle_obra->insertPTRenLog($itemInsert, $valueInsert, $idUser);
                    // Ejecutando getGrafoOnePTR()
                } else {

                    // TERCERA VALIDACION
                    if ($areaInsert == $flag) {
                        $this->db->trans_begin();
                        $data = $this->m_detalle_obra->insertPTR($itemInsert, $valueInsert, $idsubproyectoestacionInsert);
                        // Insertar en DET SI NO ESTA EN WU
                        $exists = $this->findEnWU($this->m_detalle_obra->findEnWU($valueInsert));
                        if ($exists == 0) {
                            // insertar en WUDET
                            if ($this->db->trans_status() === false) {
                                $this->db->trans_rollback();
                                throw new Exception("ERROR4");
                            } else {

                                $this->m_detalle_obra->insertEnWU($valueInsert);
                                if ($this->db->trans_status() === false) {
                                    $this->db->trans_rollback();
                                    throw new Exception("ERROR5");
                                } else {
                                    $this->m_detalle_obra->insertEnWUDet($valueInsert);
                                    if ($this->db->trans_status() === false) {
                                        $this->db->trans_rollback();
                                        throw new Exception("ERROR6");
                                    } else {
                                        $this->m_detalle_obra->getGrafoOnePTR($valueInsert);
                                        if ($this->db->trans_status() === false) {
                                            $this->db->trans_rollback();
                                            throw new Exception("ERROR6");
                                        } else {
                                            $this->db->trans_commit();
                                            $data['error'] = EXIT_SUCCESS;
                                            $data['msj'] = 'Se registro correctamente.';
                                        }
                                    }
                                }
                            }
                            log_message('error', 'FIN EN Procedimiento, mensaje es : ' . $data['msj'], true);
                        } else {
                            log_message('error', 'YA existe en WU,SE DEBE CONTINUAR, existe es: ' . $exists, true);

                            if ($this->db->trans_status() === false) {
                                $this->db->trans_rollback();
                                throw new Exception("ERROR1");
                            } else {
                                $this->m_detalle_obra->deleteEnWebUnitDet($valueInsert);
                                if ($this->db->trans_status() === false) {
                                    $this->db->trans_rollback();
                                    throw new Exception("ERROR2");
                                } else {
                                    $this->m_detalle_obra->selectDet($valueInsert);
                                    if ($this->db->trans_status() === false) {
                                        $this->db->trans_rollback();
                                        throw new Exception("ERROR3");
                                    } else {
                                        $this->db->trans_commit();
                                        $data['error'] = EXIT_SUCCESS;
                                        $data['msj'] = 'Se actualizo correctamente!';
                                    }
                                }
                            }
                        }

                        // Insert creacion en Log
                        $this->m_detalle_obra->insertPTRenLog($itemInsert, $valueInsert, $idUser);
                        // Ejecutando getGrafoOnePTR()
                        //$this->m_detalle_obra->getGrafoOnePTR("'".$valueInsert."'");
                    } else {
                        log_message('error', 'No se debe cargar', true);
                    }
                }

                $i++;
                log_message('error', '-->fin vuelta-----------------------', true);
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        //log_message('error', '-->registros editados:'.$i,true);

        return $resultado;
    }

    public function validacion2($data) {
        $flag = 'libre';
        foreach ($data->result() as $row) {
            $flag = $row->areaDesc;
        }
        return $flag;
    }

    public function findEnWU($data) {
        $exists = 0;
        foreach ($data->result() as $row) {
            $exists++;
        }
        return $exists;
    }

    public function getDetalleItemplan() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {

            $itemplan = $this->input->post('itemplan');

            $dataArrayIP = $this->m_detalle_obra->getDetalleIP($itemplan);
            $data['idSubProyecto'] = $dataArrayIP['idSubProyecto'];
            $data['idProyecto'] = $dataArrayIP['idProyecto'];
            $data['idEmpresaColab'] = $dataArrayIP['idEmpresaColab'];
            $data['proyectoDesc'] = $dataArrayIP['nombreProyecto'];
            $data['subProyectoDesc'] = $dataArrayIP['subProyectoDesc'];
            $data['jefatura'] = $dataArrayIP['jefatura'];
            $data['empresaColabDesc'] = $dataArrayIP['empresaColabDesc'];
            $data['centralDesc'] = $dataArrayIP['tipoCentralDesc'];

            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function preCancelarPO() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $itemplan = $this->input->post('itemplan') ? $this->input->post('itemplan') : null;
            $codigoPO = $this->input->post('codigoPO') ? $this->input->post('codigoPO') : null;
            $idEstacion = $this->input->post('idEstacion') ? $this->input->post('idEstacion') : null;
            $motivo = $this->input->post('motivo') ? $this->input->post('motivo') : null;
            $observacion = $this->input->post('observacion');
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;

            $this->db->trans_begin();

            if ($idUsuario == null) {
                throw new Exception('La sesion de usuario a expirado, ingrese nuevamente porfavor!!');
            }
            if ($itemplan == null || $codigoPO == null || $idEstacion == null || $motivo == null || $observacion == null) {
                throw new Exception('Hubo un error y no se cargaron los datos!!');
            }
            $arrayUpdatePPO = array(
                "estado_po" => 7
            );

            $data = $this->m_detalle_obra->updatePO($itemplan, $codigoPO, $idEstacion, $arrayUpdatePPO);
            if ($data['error'] == EXIT_SUCCESS) {
                $arrayInsertLOGPPO = array(
                    "codigo_po" => $codigoPO,
                    "itemplan" => $itemplan,
                    "idUsuario" => $idUsuario,
                    "fecha_registro" => $this->fechaActual(),
                    "idPoestado" => 7,
                    "controlador" => 'C_detalle_obra'
                );
                $data = $this->m_detalle_obra->insertarLOGPO($arrayInsertLOGPPO);
                if ($data['error'] == EXIT_SUCCESS) {
                    $arrayInsertPOCancelar = array(
                        "itemplan" => $itemplan,
                        "codigo_po" => $codigoPO,
                        "idMotivo" => $motivo,
                        "observacion" => $observacion,
                        "fecha_registro" => $this->fechaActual(),
                        "id_usuario" => $idUsuario,
                        "idPoestado" => 7
                    );
                    $data = $this->m_detalle_obra->insertarPOCancelar($arrayInsertPOCancelar);
                    if ($data['error'] == EXIT_SUCCESS) {
                        $this->db->trans_commit();
                        $data['msj'] = "Se pre-cancelo correctamente la PO!!";
                    }
                }
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function getComboMotivoPreCancela() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $itemplan = $this->input->post('itemplan') ? $this->input->post('itemplan') : null;
            $codigoPO = $this->input->post('codigoPO') ? $this->input->post('codigoPO') : null;
            $idEstacion = $this->input->post('idEstacion') ? $this->input->post('idEstacion') : null;
            $idSubProyectoEstacion = $this->input->post('idSubProyEsta') ? $this->input->post('idSubProyEsta') : null;

            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;
            $flgCommit = null;
            $flgAccion = null;


            $this->db->trans_begin();

            if ($idUsuario == null) {
                throw new Exception('La sesion de usuario a expirado, ingrese nuevamente porfavor!!');
            }
            if ($itemplan == null || $codigoPO == null || $idEstacion == null || $idSubProyectoEstacion == null) {
                throw new Exception('Hubo un error y no se cargaron los datos!!');
            }
            $estadoPO = $this->m_detalle_obra->getEstadoPO($itemplan, $codigoPO, $idEstacion);
            if ($estadoPO == null) {
                throw new Exception('Hubo un error en traer el estado de la PO!!');
            }

            if ($estadoPO == PO_REGISTRADO) {
                $infoPO = $this->m_utils->getInfoPoByCodigoPo($codigoPO);
                if ($infoPO['flg_tipo_area'] == 2 && $idEstacion != ID_ESTACION_DISENIO) {//SI ES MO Y DIFERENTE DE DISENO
                    $data = $this->m_detalle_obra->deletePOMONoDiseno($itemplan, $codigoPO, $idEstacion, $idSubProyectoEstacion);
                    if ($data['error'] == EXIT_SUCCESS) {
                        $arrayUpdateLOGPPO = array(
                            "flg_eliminar" => '1',
                        );
                        $data = $this->m_detalle_obra->updateLOGPPO($itemplan, $codigoPO, $arrayUpdateLOGPPO);
                        if ($data['error'] == EXIT_SUCCESS) {

                            $arrayInserLogPlanobra = array(
                                "tabla" => 'detalleplan',
                                "actividad" => 'eliminar',
                                "itemplan" => $itemplan,
                                "ptr" => $codigoPO,
                                "ptr_default" => 'idSubProyectoEstacion:' . $idSubProyectoEstacion,
                                "fecha_registro" => $this->fechaActual(),
                                "id_usuario" => $idUsuario
                            );
                            $data = $this->m_detalle_obra->insertarPO_LogPlanobra($arrayInserLogPlanobra);
                            if ($data['error'] == EXIT_SUCCESS) {
                                $flgAccion = 1;
                                $flgCommit = 1;
                            }
                        }
                    }
                } else {
                    $data = $this->m_detalle_obra->deletePO($itemplan, $codigoPO, $idEstacion, $idSubProyectoEstacion);
                    if ($data['error'] == EXIT_SUCCESS) {

                        $arrayUpdateLOGPPO = array(
                            "flg_eliminar" => '1',
                        );

                        $data = $this->m_detalle_obra->updateLOGPPO($itemplan, $codigoPO, $arrayUpdateLOGPPO);
                        if ($data['error'] == EXIT_SUCCESS) {

                            $arrayInserLogPlanobra = array(
                                "tabla" => 'detalleplan',
                                "actividad" => 'eliminar',
                                "itemplan" => $itemplan,
                                "ptr" => $codigoPO,
                                "ptr_default" => 'idSubProyectoEstacion:' . $idSubProyectoEstacion,
                                "fecha_registro" => $this->fechaActual(),
                                "id_usuario" => $idUsuario
                            );
                            $data = $this->m_detalle_obra->insertarPO_LogPlanobra($arrayInserLogPlanobra);
                            if ($data['error'] == EXIT_SUCCESS) {
                                $flgAccion = 1;
                                $flgCommit = 1;
                            }
                        }
                    }
                }
            } else if ($estadoPO == PO_PREAPROBADO) {

                $arrayUpdatePPO = array(
                    "estado_po" => 8,
                );

                $data = $this->m_detalle_obra->updatePO($itemplan, $codigoPO, $idEstacion, $arrayUpdatePPO);
                if ($data['error'] == EXIT_SUCCESS) {
                    $arrayInsertLOGPPO = array(
                        "codigo_po" => $codigoPO,
                        "itemplan" => $itemplan,
                        "idUsuario" => $idUsuario,
                        "fecha_registro" => $this->fechaActual(),
                        "idPoestado" => 8,
                        "controlador" => 'C_detalle_obra',
                    );
                    $data = $this->m_detalle_obra->insertarLOGPO($arrayInsertLOGPPO);
                    if ($data['error'] == EXIT_SUCCESS) {
                        $arrayInsertPOCancelar = array(
                            "itemplan" => $itemplan,
                            "codigo_po" => $codigoPO,
                            "idPoestado" => 8,
                            "fecha_cancelacion" => $this->fechaActual(),
                            "id_usuario_cance" => $idUsuario
                        );
                        $data = $this->m_detalle_obra->insertarPOCancelar($arrayInsertPOCancelar);
                        if ($data['error'] == EXIT_SUCCESS) {
                            $flgAccion = 2;
                            $flgCommit = 1;
                        }
                    }
                }
            } else if ($estadoPO == PO_APROBADO) {
                $data = $this->makeComboMotivo($this->m_utils->getMotivoAll(3));
                $flgAccion = 3;
                $flgCommit = 1;
                $data['comboMotivo'] = $data['comboHTML'];
                $data['error'] = EXIT_SUCCESS;
            }

            if ($flgCommit != null) {
                $this->db->trans_commit();
                $data['flgAccion'] = $flgAccion;
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function makeComboMotivo($listaMotivos) {

        $html = '<option value="">Seleccionar Motivo</option>';

        foreach ($listaMotivos as $row) {

            $html .= '<option value="' . $row->idMotivo . '">' . $row->motivoDesc . '</option>';
        }
        $data['comboHTML'] = utf8_decode($html);
        return $data;
    }

    public function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone', 'America/Lima');
        setlocale(LC_TIME, "es_ES", "esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }

}
