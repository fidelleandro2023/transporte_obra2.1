<?php

defined('BASEPATH') or exit('No direct script access allowed');

class C_detalle_obra extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_detalle_obra/m_detalle_obra');
        $this->load->model('mf_reportes_v/m_itemplan_ptr');
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
            $data['idProyecto'] = $this->m_utils->getProyectoByItemplan($item);
            $data['listaEstaciones'] = $this->makeHTLMTEstaciones($this->m_detalle_obra->getAllEstaciones($item), $item, $est, $data['estadoItemplan'], $idEstacionEje, $data['idSubProy']);

            //EDITAR
            $data['listaEstacionesEdit'] = $this->makeHTLMTEstacionesEdit($this->m_detalle_obra->getAllEstaciones($item), $item);
            //AGREGAR
            $data['listaEstacionesInsert'] = $this->makeHTLMTEstacionesInsert($this->m_detalle_obra->getAllEstaciones($item), $item);

            $data['listaEECC'] = $this->m_utils->getAllEECC();
            $data['listaZonal'] = $this->m_utils->getAllZonal();
            $data['listaSubProy'] = $this->m_utils->getAllSubProyecto();

            $data['tablaAsigGrafo'] = $this->makeHTLMTablaItemPtr($this->m_itemplan_ptr->getWebUnificadaFa('', '', '', ''));
            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
			$infoItemplan = $this->m_utils->getInfoItemplan($item);
			$isObraPqt = 0;
			if($infoItemplan['paquetizado_fg'] == 2){
				$isObraPqt = 1;
			}
            $data['poMoPaquetizado'] = $isObraPqt; //$this->m_utils->isObraPaquetizadaNoOPNoTrans($item);
            $permisos = $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_REPORTES_V, ID_PERMISO_HIJO_DETALLE_OBRA, ID_MODULO_GESTION_OBRA);
            $data['opciones'] = $result['html'];

            $this->load->view('vf_pqt_detalle_obra/v_detalle_obra', $data);
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
                    </div>
                    ';
        }

        return utf8_decode($htmlI);
    }

    public function makeHTLMTEstaciones($listaEstaciones, $item, $est = null, $estadoItemplan, $idEstacionEje = null, $idSubProyecto) {
        $html = '';
        $cards = 0;
        $tituloEstacion = '';

        foreach ($listaEstaciones->result() as $row) {

            $tituloEstacion = '<h2 class="card-title text-center">' . $row->estacionDesc . (($row->has_vali >= 1) ? (($row->has_pen == 1) ? ' (CON EXP.)' : (($row->has_fin == 1) ? ' (VALIDADO)' : '')) : '') . '</h2>';
            $cards += 1;
            $html .= '
        <div class="col-md-3">
            <div class="card" style="border: 1px solid lightgrey">
                <div class="card-header" style="padding: 10px">
                     ' . $tituloEstacion . '
                </div>
                <div class="row">';
            $html .= $this->makeHTLMTAreas($this->m_detalle_obra->getAllAreasByEstacion($item, $row->estacionDesc), $row->estacionDesc, $item, $est, $estadoItemplan, $row->idEstacion, $idEstacionEje, $row->has_vali, $idSubProyecto);
            $html .= '</div>
            </div>
        </div>
        ';
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

    public function makeHTLMTAreas($data, $estacionDesc, $item, $est = null, $estadoItemplan, $idEstacion, $idEstacionEje = null, $hasValidacion, $idSubProyecto) {
		$array_item_execption = array('19-0310900903','21-0320500045', '22-0320200007','22-0111100085');
        //log_message('error', ' -->makeHTLMTAreas $estacionDesc ' . $estacionDesc);
        $htmlAreas = '';
        $tituloArea = '';
        $idProyecto = $this->m_utils->getProyectoByItemplan($item);
		$infoItemplan = $this->m_utils->getInfoItemplan($item);
		$isObraPqt = 0;
		if($infoItemplan['paquetizado_fg'] == 2){
			$isObraPqt = 1;
		}
		$canCreateUm = false;
		if($idProyecto == ID_PROYECTO_MOVILES){
			$canCreateUm = true;
		}
		
        foreach ($data->result() as $row) {
            if ($row->tipoArea == 'MAT') {
                if ($est == '1' && $idEstacion != ID_ESTACION_DISENIO && $hasValidacion < 1 && (($idEstacion == ID_ESTACION_UM) ? $canCreateUm : true)) {
                    if ($estadoItemplan == ID_ESTADO_PLAN_EN_OBRA || $estadoItemplan == ID_ESTADO_EN_APROBACION || $estadoItemplan == ID_ESTADO_EN_LICENCIA || in_array($item,$array_item_execption)) {
                        $tituloArea = '<th style="font-size: 10px; text-align: center"><a style="color:#8a8a5c" href="regIndiPO?item=' . $item . '&form=1&estaciondesc=' . utf8_decode($estacionDesc) . '&estacion=' . $idEstacion . '"    target="_blank">' . utf8_decode($row->areaDesc) . '</a></th>';
                    } else {
                        $tituloArea = '<th style="font-size: 10px; text-align: center">' . utf8_decode($row->areaDesc) . '</th>';
                    }
                } else if ($est == '2' && $idEstacion != 1 && $hasValidacion < 1) {
                    if ($estadoItemplan == ID_ESTADO_PRE_DISENIO || $estadoItemplan == ID_ESTADO_DISENIO || $estadoItemplan == ID_ESTADO_EN_LICENCIA) {
                        if ($idEstacion != 2 && $idEstacion != 5) {
                            $tituloArea = '<th style="font-size: 10px; text-align: center"><a style="color:#8a8a5c" href="regIndiPO?item=' . $item . '&form=11&estaciondesc=' . utf8_decode($estacionDesc) . '&estacion=' . $idEstacion . '"    target="_blank">' . utf8_decode($row->areaDesc) . '</a></th>';
                        } else {
                            if ($idSubProyecto != 96 && $idSubProyecto != 99 && $idSubProyecto != 395) {
                                if ($idEstacion == $idEstacionEje) {
                                    $tituloArea = '<th style="font-size: 10px; text-align: center"><a style="color:#8a8a5c" href="regIndiPO?item=' . $item . '&form=11&estaciondesc=' . utf8_decode($estacionDesc) . '&estacion=' . $idEstacion . '"    target="_blank">' . utf8_decode($row->areaDesc) . '</a><th>';
                                } else {
                                    $tituloArea = '<th style="font-size: 10px; text-align: center">' . utf8_decode($row->areaDesc) . '</th>';
                                }
                            } else {
                                $tituloArea = '<th style="font-size: 10px; text-align: center"><a style="color:#8a8a5c" href="regIndiPO?item=' . $item . '&form=11&estaciondesc=' . utf8_decode($estacionDesc) . '&estacion=' . $idEstacion . '"    target="_blank">' . utf8_decode($row->areaDesc) . '</a><th>';
                            }
                        }
                    } else {
                        $tituloArea = '<th style="font-size: 10px; text-align: center">' . utf8_decode($row->areaDesc) . '</th>';
                    }
                } else {
                    $tituloArea = '<th style="font-size: 10px; text-align: center">' . utf8_decode($row->areaDesc) . '</th>';
                }
            } else if ($row->tipoArea == 'MO' && $hasValidacion == 0 && $estadoItemplan != ID_ESTADO_PRE_REGISTRO && (($idEstacion == ID_ESTACION_UM) ? $canCreateUm : true)) {
                $tituloArea = '<th style="font-size: 10px; text-align: center">' . utf8_decode($row->areaDesc) . '</th>';
                if ($est == '1') {//from consultas
				log_message('error', 'heree crear po..');
                    if ($estadoItemplan == 1 || $estadoItemplan == ID_ESTADO_PLAN_EN_OBRA || $estadoItemplan == ID_ESTADO_EN_APROBACION || $estadoItemplan == ID_ESTADO_EN_LICENCIA || $estadoItemplan == ESTADO_PLAN_DISENO_EJECUTADO || in_array($item,$array_item_execption)) {
					log_message('error', 'heree crear po 1..');
					  if ($idProyecto == ID_PROYECTO_OBRA_PUBLICA || $idProyecto == ID_PROYECTO_TRANSPORTE || $idProyecto == 43 || $isObraPqt == 0) {//proyectos varios tampoco
                            log_message('error', 'heree crear po 2..');
							$tituloArea = '<th style="font-size: 10px; text-align: center"><a href="rePoMo?item=' . $item . '&from=' . $est . '&estaciondesc=' . utf8_decode($row->estacionDesc) . '&estacion=' . $row->idEstacion . '"    target="_blank">' . utf8_decode($row->areaDesc) . '</a></th>';
                        } else {
							log_message('error', 'heree crear po 3..');
                            if ($idEstacion != ID_ESTACION_FO && $idEstacion != ID_ESTACION_COAXIAL && $idEstacion != ID_ESTACION_FO_ALIM && $idEstacion != ID_ESTACION_FO_DIST) {
                                log_message('error', 'heree crear po 4..');
								$hasPoMoActive = $this->m_utils->hasPtrMoActive($item, $row->idEstacion);
                                if ($hasPoMoActive == 0) {
									log_message('error', 'heree crear po 5..');
                                    $tituloArea = '<th style="font-size: 10px; text-align: center"><a href="rePoMo?item=' . $item . '&from=' . $est . '&estaciondesc=' . utf8_decode($row->estacionDesc) . '&estacion=' . $row->idEstacion . '"    target="_blank">' . utf8_decode($row->areaDesc) . '</a></th>';
                                }
                            }
                        }
                    }
                } else if ($est == '2') {//from diseno
                    if ($estadoItemplan == ID_ESTADO_PRE_DISENIO || $estadoItemplan == ID_ESTADO_DISENIO || $estadoItemplan == ID_ESTADO_EN_LICENCIA) {
                        if ($idProyecto == ID_PROYECTO_OBRA_PUBLICA || $idProyecto == ID_PROYECTO_TRANSPORTE  || $idProyecto == 43 || $isObraPqt == 0) {
                            $tituloArea = '<th style="font-size: 10px; text-align: center"><a href="rePoMo?item=' . $item . '&from=' . $est . '&estaciondesc=' . utf8_decode($row->estacionDesc) . '&estacion=' . $row->idEstacion . '"    target="_blank">' . utf8_decode($row->areaDesc) . '</a></th>';
                        } else {
                            if ($idEstacion != ID_ESTACION_FO && $idEstacion != ID_ESTACION_COAXIAL && $idEstacion != ID_ESTACION_FO_ALIM && $idEstacion != ID_ESTACION_FO_DIST) {
                                $hasPoMoActive = $this->m_utils->hasPtrMoActive($item, $row->idEstacion);
                                if ($hasPoMoActive == 0) {
                                    $tituloArea = '<th style="font-size: 10px; text-align: center"><a href="rePoMo?item=' . $item . '&from=' . $est . '&estaciondesc=' . utf8_decode($row->estacionDesc) . '&estacion=' . $row->idEstacion . '"    target="_blank">' . utf8_decode($row->areaDesc) . '</a></th>';
                                }
                            }
                        }
                    }
                }
            } else {
                $tituloArea = '<th style="font-size: 10px; text-align: center">' . utf8_decode($row->areaDesc) . '</th>';
            }




            $htmlAreas .= '
        <div class="col-md-6">
            <table class="table mb-0">
                <thead >
                <tr>
                   ' . $tituloArea . '
                </tr>
                </thead>
                <tbody>';
            $htmlAreas .= $this->makeHTLMPTR($this->m_detalle_obra->getAllPTRbyArea($item, $estacionDesc, $row->areaDesc));

            $htmlAreas .= '</tbody>
            </table>
        </div>
        ';
            $tituloArea = '';
        }
        return utf8_encode($htmlAreas);
    }

    public function infoWeb() {
		_log("1. PARTE");
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $ptrAjax = $this->input->post('ptr');
            $itemplan = $this->input->post('itemplan');
            $idEstacion = $this->input->post('idEstacion');
            $areaDesc = $this->input->post('areaDesc');
            $idSubProyecto = $this->input->post('idSubProyecto');

            $datos = $this->m_detalle_obra->getAllWebUnificada($ptrAjax);
            $html = '';
            $htmlLOG = '';
            $htmlPreCancel = '';
            $htmlDetallePO = '';
            $htmlPresupuesto = '';
			
			$htmlLogRpaSapAprob = '';
			
            $idSubProyectoEstacion = null;
            $pep1 = null;
            $vr = null;
            $grafo = null;
            $htmlPartidas = '';
            if ($datos == null) {
                $flgPaquetizado = $this->m_utils->getFlgPaquetizadoPo($itemplan);

                if ($flgPaquetizado == 2 || $flgPaquetizado == 1) {
                    $arrayDetPO = $this->m_detalle_obra->getDetallePOPqt($itemplan, $ptrAjax, $idEstacion);
                } else {
                    $arrayDetPO = $this->m_detalle_obra->getDetallePO($itemplan, $ptrAjax, $idEstacion);
                }

                $arrayLogPO = $this->m_detalle_obra->getDetalleLogPO($itemplan, $ptrAjax);

                $idSubProyectoEstacion = $arrayDetPO['idSubProyectoEstacion'];

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

                $ListaDetallePO = $this->m_detalle_obra->getPPODetalle($ptrAjax);

                $htmlDetallePO .= '<table id="tbValeReserva" class="table table-bordered">
                                    <thead class="thead-default">
                                        <tr>
                                            <th>MATERIAL</th>
                                            <th>DESCRIPCION</th>
                                            <th>UDM</th>
                                            <th>CANT. ING.</th>
                                        </tr>
                                    </thead>
                                    <tbody>';

                foreach ($ListaDetallePO as $row) {
                    $htmlDetallePO .= ' <tr>
                                            <th>' . $row->codigo_material . '</th>
                                            <td>' . utf8_decode($row->descrip_material) . '</td>
                                            <td>' . $row->unidad_medida . '</td>
                                            <td>' . $row->cantidad_final . '</td>
                                        </tr>';
                }
                $htmlDetallePO .= '</tbody>
                    </table>';

				
				$arrayLogRpaAprob = $this->m_utils->getLogRpaSapAprob($ptrAjax);
				_log("ENTRO1");
				$htmlLogRpaSapAprob .= '<table id="tbLogRpaSapAprob" class="table table-bordered">
                                    <thead class="thead-default">
                                        <tr>
                                            <th>PO</th>
                                            <th>DESCRIPCION</th>
                                            <th>FECHA</th>
                                        </tr>
                                    </thead>
                                    <tbody>';

                foreach ($arrayLogRpaAprob as $rowAprob) {
                    $htmlLogRpaSapAprob .= ' <tr>
                                            <th>' . $rowAprob['codigo_po'] . '</th>
                                            <td>' . utf8_decode($rowAprob['mensaje']) . '</td>
                                            <td>' . $rowAprob['fecha']. '</td>
                                        </tr>';
                }
                $htmlLogRpaSapAprob .= '</tbody>
                    </table>';
				_log($htmlLogRpaSapAprob);
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

                if ($arrayDetPO['flg_tipo_area'] == 1) { // PO MATERIAL
                    $htmlPresupuesto .= '
                                        <tbody>
                                            <tr>
                                                <th>' . $arrayDetPO['pep1'] . '</th>
                                                <td>' . $arrayDetPO['pep2'] . '</td>
                                                <td>' . $arrayDetPO['grafo'] . '</td>
                                                <td></td>
                                                <td></td>
                                                <td>' . $arrayDetPO['vale_reserva'] . '</td>
                                                <td>' . number_format($arrayDetPO['costo_total'], 2) . '</td>
                                            </tr>
                                        </tbody>
                                        </table>';
                } else if ($arrayDetPO['flg_tipo_area'] == 2) {// PO MO
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
                                    <input id="contCodigoPO" type="text" class="form-control" value="' . $arrayDetPO['codigo_po'] . '" disabled>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-2">
                                <div class="form-group">
                                    <label>ESTADO</label>
                                    <input id="contEstadoPO" type="text" class="form-control" value="' . $arrayDetPO['estado'] . '" disabled>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-6">
                                <div class="form-group">
                                    <label>NOMBRE PROYECTO</label>
                                    <input id="contProyecto" type="text" class="form-control" value="' . $arrayDetPO['nombreProyecto'] . '" disabled>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4">
                                <div class="form-group has-feedback" style="">
                                    <label>SUB PROYECTO</label>
                                    <input id="contSubProyecto" type="text" class="form-control"  value="' . $arrayDetPO['subProyectoDesc'] . '" disabled>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4">
                                <div class="form-group has-feedback" style="">
                                    <label>VR</label>
                                    <input id="contVR" type="text" class="form-control" value="' . $arrayDetPO['vale_reserva'] . '" disabled>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label class="control-label">AREA</label>
                                    <input id="contAreaDesc" type="text" class="form-control" value="' . $areaDesc . '" disabled>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label class="control-label">JEFATURA</label>
                                    <input id="contJefatura" type="text" class="form-control" value="' . $arrayDetPO['jefatura'] . '" disabled>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label class="control-label">EE.CC.</label>
                                    <input id="contEmpresacolab" type="text" class="form-control" value="' . $arrayDetPO['empresaColabDesc'] . '" disabled>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label class="control-label">CENTRAL</label>
                                    <input id="contCentral" type="text" class="form-control" value="' . $arrayDetPO['tipoCentralDesc'] . '" disabled>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label class="control-label">ALMACEN</label>
                                    <input id="codAlmacen" type="text" class="form-control" value="' . $arrayDetPO['codAlmacen'] . '" disabled>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label class="control-label">MONTO</label>
                                    <input id="contoMontoTotal" type="text" class="form-control" value="' . number_format($arrayDetPO['costo_total'], 2) . '" disabled>
                                </div>
                            </div>

                             ' . $htmlPreCancel . ' ';

                /*                 * ************************** detalle partidas ****************** */
                $listaPartidasMO = $this->m_detalle_obra->getPartidasBasicByPtr($arrayDetPO['codigo_po']);

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
                                                <th>' . $row->codigo . '</th>
                                                <td>' . utf8_decode($row->descripcion) . '</td>
                                                <td>' . $row->descPrecio . '</td>
                                                <td>' . $row->costo . '</td>
                                                <td>' . $row->baremo . '</td>
                                                <td>' . $row->cantidad_inicial . '</td>
                                                <td>' . $row->cantidad_final . '</td>
                                                <td>' . $row->monto_final . '</td>                                                            
                                            </tr>';
                }
                $htmlPartidas .= '</tbody>
                        </table>';
            } else {

                foreach ($datos as $row) {

                    $pep1 = $row->pep;
                    $vr = $row->vr;
                    $grafo = $row->grafo;

                    $html .= '
                                <div class="col-md-12">
                                    <h6 class="text-center" style="background-color: #1B84AC; color: white; padding: 2px">WEB UNIFICADA</h6>
                                    <div class="card">
                                        <table class="table table-bordered">
                                        <tr>
                                            <th style="font-size:12px;" ><b>PO</b></th>
                                            <td>' . $row->ptr . '</td>
                                            <th style="font-size:12px;" ><b>VR</b></th>
                                            <td>' . $row->vr . '</td>
                                            <th style="font-size:12px;" ><b>ESTADO</b></th>
                                            <td>' . $row->est_innova . '</td>
                                        </tr>
                                        <tr>
                                            <th style="font-size:12px;" ><b>TIPO PROYECTO</b></th>
                                            <td colspan="2">' . $row->tipo_proyecto . '</td>
                                            <th style="font-size:12px;" ><b>CATEGORIA</b></th>
                                            <td colspan="2">' . $row->categoria . '</td>
                                        </tr>
                                        <tr>
                                        <tr>
                                            <th style="font-size:12px;"><b>EE.CC</b></th>
                                            <td>' . $row->eecc . '</td>
                                            <th style="font-size:12px;"><b>MONTO MO</b></th>
                                            <td>S/. ' . $row->valoriz_m_o . '</td>
                                            <th style="font-size:12px;"><b>MONTO MATERIAL</b></th>
                                            <td>S/. ' . $row->valoriz_material . '</td>
                                        </tr>
                                        <tr>
                                            <th style="font-size:12px;"><b>ZONAL</b></th>
                                            <td>' . $row->zonal . '</td>
                                            <th style="font-size:12px;"><b>JEFATURA</b></th>
                                            <td>' . $row->jefatura . '</td>
                                            <th style="font-size:12px;"><b>MDF</b></th>
                                            <td>' . $row->mdf . '</td>
                                        </tr>
                                        <tr>
                                            <th style="font-size:12px;"><b>FECHA CREACION</b></th>
                                            <td>' . $row->f_creac_prop . '</td>
                                            <th style="font-size:12px;"><b>FECHA APROBADA</b></th>
                                            <td>' . $row->f_aprob . '</td>
                                            <th style="font-size:12px;"><b>USUARIO</b></th>
                                            <td>' . $row->usu_registro . '</td>
                                        </tr>
                                        </table>
                                    </div>
                                </div>
                            ';
                    //log_message('error', '-->html es:'.$row->est_innova,true);

                    if ($pep1 != null) {
                        // $arrayPresu = $this->m_detalle_obra->getDetallePresu_wu($itemplan, $ptrAjax, $idSubProyecto, substr($pep1,0,20));

                        $arrayPresu = $this->m_detalle_obra->getDetalleCertMOByPO($ptrAjax);
                        $htmlPresupuesto .= ' <table id="table_presupuesto" class="table table-bordered">
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
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th>' . $arrayPresu['pep1'] . '</th>
                                                <td>' . $pep1 . '</td>
                                                <td>' . $grafo . '</td>
                                                <td>' . $arrayPresu['nro_certificacion'] . '</td>
                                                <td>' . $arrayPresu['orden_compra'] . '</td>
                                                <td>' . $vr . '</td>
                                                <td>' . $arrayPresu['monto_mo'] . '</td>
                                            </tr>
                                        </tbody>
                                        </table>';
                    }
                }
            }

            if ($idEstacion == 1 || $idEstacion == 20) {
                $htmlPartidas = null;
                $total = 0;
                $detallePODisenio = $this->m_detalle_obra->getDetalleDisenioAutomatico($ptrAjax);
                $htmlPartidas .= '<table id="tabla_diseno_auto" class="table table-bordered">
                                <thead class="thead-default">
                                    <tr>
                                        <th>C&Oacute;DIGO PARTIDA</th>
                                        <th>PARTIDA</th>
                                        <th>TIPO</th>
                                        <th>BAREMO</th>
                                        <th>PRECIARIO</th>
                                        <th>CANTIDAD</th>
                                        <th>TOTAL</th>
                                        <th>ENTIDAD</th>
                                    </tr>
                                </thead>
                                <tbody>';

                foreach ($detallePODisenio as $row) {
                    $total = $row['costo_total'];
                    $htmlPartidas .= ' <tr>
                                                    <th>' . $row['codigo'] . '</th>
                                                    <td>' . utf8_decode($row['descPartida']) . '</td>
                                                    <td>' . $row['descPrecio'] . '</td>
                                                    <td>' . $row['baremo'] . '</td>
                                                    <td>' . $row['costo'] . '</td>
                                                    <td>' . $row['cantidad'] . '</td>
                                                    <td>' . $row['total'] . '</td>
                                                    <td>' . $row['descEntidad'] . '</td>
                                                </tr>';
                }
                $htmlPartidas .= '</tbody>
                                </table>
                                <a>TOTAL: ' . $total . '</a>';
            }
            $data['prueba'] = $html;
            $data['tablaLOG'] = $htmlLOG;
            $data['tablaVR'] = $htmlDetallePO;
            $data['tablaPresu'] = $htmlPresupuesto;
            $data['tablaPartidas'] = $htmlPartidas;
            $data['idSubProEsta'] = $idSubProyectoEstacion;
			_log($htmlLogRpaSapAprob);
			$data['tablaLogRpaSapAprob'] = $htmlLogRpaSapAprob;
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function makeHTLMPTR($data) {
        $html = '';

        foreach ($data->result() as $row) {
            if ($row->rangoPtr == 1) {
                $fondo = '#FF8989';
            } elseif ($row->rangoPtr == 2) {
                $fondo = '#13ab98';
            } elseif ($row->rangoPtr == 3) {
                $fondo = '#78E900';
            } elseif ($row->rangoPtr == 4) {
                $fondo = '#767680';
            } elseif ($row->rangoPtr == 5) {
                $fondo = '#d4d61c';
            } elseif ($row->rangoPtr == 6) {
                $fondo = 'steelblue';
            } else {
                $fondo = 'white';
            }

            $html .= '
            <tr style="background-color: ' . $fondo . '; color: black">
                <td><a class="" data-ptr="' . $row->poCod . '" data-itemplan="' . $row->itemPlan . '"  data-estacion="' . $row->idEstacion . '" data-areadesc="' . $row->areaDesc . '" data-idsubproy="' . $row->idSubProyecto . '" onclick="getPtr(this)" id="' . $row->poCod . '">' . $row->poCod . '</a></td>
            </tr>
            ';
        }
        return utf8_decode($html);
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
            $data['listaEstaciones'] = $this->makeHTLMTEstaciones($this->m_detalle_obra->getAllEstaciones($itemTitle), $itemTitle, null, $idEstadoPlan);
            $data['listaEstacionesEdit'] = $this->makeHTLMTEstacionesEdit($this->m_detalle_obra->getAllEstaciones($itemTitle), $itemTitle);
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
			
			$countExistPendiente = $this->m_utils->hasSolExceActivo($itemplan, NULL);
			
			if($countExistPendiente > 0) {
				throw new Exception('Tiene una solicitud de exceso pendiente, verificar.');
			}
			
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
			
			$countExistPendiente = $this->m_utils->hasSolExceActivo($itemplan, NULL);
			
			if($countExistPendiente > 0) {
				throw new Exception('Tiene una solicitud de exceso pendiente, verificar.');
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
