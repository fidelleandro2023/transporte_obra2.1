<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 *
 *
 */
class C_consulta extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
		$this->load->model('mf_ejecucion/M_porcentaje');
        $this->load->model('mf_plan_obra/m_consulta');
        $this->load->model('mf_pqt_plan_obra/m_pqt_consulta');
        $this->load->model('mf_pqt_plan_obra/m_pqt_planobra');
		$this->load->model('mf_pqt_pre_liquidacion/M_pqt_pre_liquidacion');
		$this->load->model('mf_plantaInterna/M_aprobacion_interna');  
		$this->load->model('mf_plantaInterna/M_plantaInterna');
		$this->load->model('mf_control_presupuestal/m_control_presupuestal');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_log/m_log_ingfix');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index() {
        $logedUser = $this->session->userdata('usernameSession');

        if ($logedUser != null) {
            $data['listaNodos'] = $this->m_utils->getAllNodos();
            $data['listaProyectos'] = $this->m_utils->getProyectoCmb();
            $data['listaEstados'] = $this->m_utils->getEstadosItemplan();
            $data['listaTipoPlanta'] = $this->m_utils->getAllTipoPlantal();
            $data['listafase'] = $this->m_utils->getAllFase();

            $data['listaMotivos'] = $this->m_utils->getAllMotivos();
            // Trayendo zonas permitidas al usuario
            $zonas = $this->session->userdata('zonasSession');
            $data['listaZonal'] = $this->m_utils->getAllZonal();
            $data['listaSubProy'] = '';
            $data['tablaAsigGrafo'] = '';
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaConsulta('');
            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $permisos = $this->session->userdata('permisosArbolTransporte');
            $result = $this->lib_utils->getHTMLPermisos($permisos, 54, ID_PERMISO_HIJO_PQT_CONSULTAS, ID_MODULO_PAQUETIZADO);
            $data['opciones'] = $result['html'];
/////////////////////
            $data['listaSubProy'] = $this->m_utils->getAllSubProyecto();
            $data['listacentral'] = $this->m_utils->getAllNodos();
            $data['listEECCDi'] = $this->m_utils->getAllEECC();
            ///////////////
			 $listEstacion = $this->m_utils->getEstacionDBSAM();
			 _log(print_r($listEstacion,true));

            $this->load->view('vf_pqt_plan_obra/v_consulta', $data);
        } else {

            redirect('login', 'refresh');
        }
    }

    public function asignarExpediente() {
        $logedUser = $this->session->userdata('usernameSession');

        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {

            $jsonptr = $this->input->post('jsonptr');
            $comentario = $this->input->post('comentario');

            $arrayPTRItem = json_decode($jsonptr, true);
            //log_message('error', 'loged user es: '.$logedUser);
            $data = $this->m_consulta->insertExpediente($comentario, $logedUser);

            foreach ($arrayPTRItem as $row) {
                $subrows = explode("%", $row);
                $ptr = $subrows[0];
                $item = (($subrows[1] != null) ? $subrows[1] : null);
                $fecsol = $subrows[2];
                $subproyecto = $subrows[3];
                $zonal = $subrows[4];
                $eecc = $subrows[5];
                $area = $subrows[6];

                //aquir recibir en una variable la wu.f_ult_est para enviar al insert()
                /*
                  log_message('error', '===============================================================');

                  log_message('error', '-->ptr recibida en controllador es : '.$ptr);
                  log_message('error', '-->itemplan decodificado es : '.$item);
                  log_message('error', '-->comentario recibido en controllador es : '.$comentario);
                  log_message('error', '-->fecsol recibido en controllador es : '.$fecsol);

                  log_message('error', '-->subproyecto decodificado es : '.$subproyecto);
                  log_message('error', '-->zonal recibido en controllador es : '.$zonal);
                  log_message('error', '-->eecc recibido en controllador es : '.$eecc);
                  log_message('error', '-->area recibido en controllador es : '.$area);
                  log_message('error', '==============================================================='); */
                $this->m_consulta->insertPTR($ptr, $item, $fecsol, $subproyecto, $zonal, $eecc, $area);
            }
            //  log_message('error', 'fin foreach ');
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function makeHTLMTablaConsulta($listaPTR) {

        $html = '
                <table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>ACCI&Oacute;N</th>
                            <th>ITEMPLAN</th>
							<th>GESTOR</th>
							<th>INDICADOR</th>
							<th>ORDEN COMPRA</th>
							<th>ESTADO OC</th>
                            <th>SUBPROYECTO</th>
                            <th>NOMBRE DE CLIENTE</th>
							<th>CONTRATO PADRE</th>
                            <th>CONTRATO MARCO</th>
							<th>C&Oacute;DIGO UNICO</th>
                            <th>EECC</th>
                            <th>A&Ntilde;O</th>
                            <th>FEC. INICIO</th>
                            <th>FEC. PREV. EJECUCION</th>
                            <th>FEC LIQUIDA.</th>
							<th>SOLICITUD OC CREA</th>
                            <th>ESTADO</th>
							<th>COSTO SAP</th>
							<th>COSTO MO INICIAL</th>
							<th>COSTO MO FINAL</th>
                            <th>MONEDA</th>							
                            <th>PRESUPUESTO</th>		
                            <th>PEP2</th>		
                            <th>CECO</th>		
                            <th>CUENTA</th>		
                            <th>AREA FUNCIONAL</th>	
                        </tr>
                    </thead>

                    <tbody>';
        if ($listaPTR != '') {
            foreach ($listaPTR->result() as $row) {
                $botonCancelar = '';
                $botonSuspender = '';
                $botonReanudar = '';
                $botonTruncar = '';

                if ($row->idEstadoPlan == ID_ESTADO_CANCELADO) {
                    $botonCancelar = '';
                    $botonSuspender = '';
                    $botonReanudar = '';
                    $botonTruncar = '';
                }

                if ($row->idEstadoPlan == ID_ESTADO_TRUNCO) {
                    $botonCancelar = '';
                    $botonReanudar = '<a data-item="' . $row->itemPlan . '" data-accion="reanudar-trunco" onclick="abrirModalReanudar($(this))"><img alt="Reanudar" height="20px" width="20px" src="public/img/iconos/reanudar.png" title="Reanudar"></a>';
                    $botonReanudar = '';
                    $botonTruncar = '';
                }

                if ($row->idEstadoPlan == ID_ESTADO_SUSPENDIDO) {
                    $botonCancelar = '';
                    $botonSuspender = '';
                    if ($this->session->userdata('eeccSession') == 6 || $this->session->userdata('eeccSession') == 0) {
                        $botonReanudar = '<a data-item="' . $row->itemPlan . '" data-accion="reanudar-suspendido" onclick="abrirModalReanudar($(this))"><img alt="Reanudar" height="20px" width="20px" src="public/img/iconos/reanudar.png" title="Reanudar"></a>';
                    } else {
                        $botonReanudar = '';
                    }

                    $botonTruncar = '';
                }

                //PARA LOS PRIMEROS ESTADOS, PERMITIR SUSPENDER, CANCELAR Y TRUNCAR
                if ($row->idEstadoPlan == ID_ESTADO_PRE_DISENIO || $row->idEstadoPlan == ID_ESTADO_DISENIO || $row->idEstadoPlan == ID_ESTADO_EN_LICENCIA || $row->idEstadoPlan == ID_ESTADO_EN_APROBACION || $row->idEstadoPlan == ID_ESTADO_PRE_REGISTRO || $row->idEstadoPlan == ESTADO_PLAN_PDT_OC) {
                    $botonCancelar = '<a data-item="' . $row->itemPlan . '" data-accion="cancelar" onclick="abrirModalDetener($(this))"><img alt="Cancelar" height="20px" width="20px" src="public/img/iconos/cancelar.png" title="Cancelar"></a>';
                    if ($this->session->userdata('eeccSession') == 6 || $this->session->userdata('eeccSession') == 0) {// le quite el suspendido a todos czavala 01-10-2020
                     //   $botonSuspender = '<a data-item="' . $row->itemPlan . '" data-accion="suspender" onclick="abrirModalDetener($(this))"><img alt="Suspender" height="22px" width="22px" src="public/img/iconos/suspender.png" title="Suspender"></a>';
                    } else {
                        $botonSuspender = '';
                    }

                    $botonReanudar = '';
                    $botonTruncar = '';
                }

                //PARA DEMAS ESTADOS, PERMITIR CANCELAR Y TRUNCAR
                if ($row->idEstadoPlan == ID_ESTADO_PLAN_EN_OBRA || $row->idEstadoPlan == ID_ESTADO_PRE_LIQUIDADO || $row->idEstadoPlan == ID_ESTADO_EN_VALIDACION || $row->idEstadoPlan == ID_ESTADO_TERMINADO || $row->idEstadoPlan == ID_ESTADO_EN_CERTIFICACION) {
                    $botonCancelar = '<a data-item="' . $row->itemPlan . '" data-accion="cancelar" onclick="abrirModalDetener($(this))"><img alt="Cancelar" height="20px" width="20px" src="public/img/iconos/cancelar.png" title="Cancelar"></a>';
                    $botonSuspender = '';
                    $botonReanudar = '';
                    $botonTruncar = '<a data-item="' . $row->itemPlan . '" data-accion="truncar" onclick="abrirModalDetener($(this))"><img alt="Truncar" height="20px" width="20px" src="public/img/iconos/truncar.png" title="Truncar"></a>';
                }

                //comentado 13.02.2020 owen solicito no se desparalice ni motivo presupuesto ni el nuievo de certificaicon
                //$countParalizados = $this->m_utils->countParalizados($row->itemPlan, FLG_ACTIVO, ORIGEN_WEB_PO);
                /*                 * nuevo 13.02.2020* */
                $infoParaliza = $this->m_utils->countParalizados_v2($row->itemPlan, FLG_ACTIVO, ORIGEN_WEB_PO);
                $countParalizados = $infoParaliza['count'];
                $idMotivoParaliza = $infoParaliza['idMotivo'];
                /** fin nuevo 13.02.2020* */
                $countSiom = $this->m_utils->countSiomByItemplan($row->itemPlan);
                $analisisEco = '<i style="color:#A4A4A4;cursor:pointer" data-itemplan ="' . $row->itemPlan . '" class="zmdi zmdi-hc-2x zmdi-money-box" title="An&aacute;lisis Econ&oacute;mico" onclick="getAnalisisEconomico($(this));"></i>';
                //$analisisEco = '';

                $iconsSiom = '';
                if ($countSiom > 0) {
                    $iconsSiom = '<i style="color:#A4A4A4;cursor:pointer" class="zmdi zmdi-hc-2x zmdi-laptop" data-itemplan="' . $row->itemPlan . '" title="C&oacute;digo Siom" onclick="openModalCodigoSion($(this))"></i>';
                }
                $btnDatosSisego = null;
                $btnParalizacion = '';
                if (!in_array($row->idEstadoPlan, array(ID_ESTADO_TRUNCO, ID_ESTADO_PRE_LIQUIDADO, ID_ESTADO_TERMINADO, ID_ESTADO_CANCELADO, ID_ESTADO_EN_CERTIFICACION, ID_ESTADO_SUSPENDIDO))) {
                    if ($row->idProyecto == ID_PROYECTO_SISEGOS && $countParalizados == 0 && ($this->session->userdata('eeccSession') == 6 || $this->session->userdata('eeccSession') == 0)) {
                        $btnParalizacion = '<i style="color:#A4A4A4;cursor:pointer" class="zmdi zmdi-hc-2x zmdi-paypal-alt" data-itemplan="' . $row->itemPlan . '" title="paralizar" onclick="openModalParalizacion($(this), ' . ORIGEN_WEB_PO . ')"></i>';
                    }
                }
                $btnCvDoc = '';
                if ($countParalizados > 0) {
                    $btnParalizacion = '<i style="color:#A4A4A4;cursor:pointer" class="zmdi zmdi-hc-2x zmdi-repeat-one" data-itemplan="' . $row->itemPlan . '" title="Revertir" onclick="openModalAlert($(this))"></i>';
                    /*                     * nuevo czavala 24.06.2020 ningun parlaizado puede gestionarse* */
                    $botonCancelar = '';
                    $botonSuspender = '';
                    $botonReanudar = '';
                    $botonTruncar = '';
                }

                /** nuevo 13.02.2020 && 21.02.2020* */
                $countExitParaliza = $this->m_utils->getCountSisegoParalizacionExitosa($row->itemPlan);

                if ($countParalizados > 0 && ($idMotivoParaliza == 66 || $idMotivoParaliza == 70)) {
                    $btnParalizacion = '';
                }
                /** fin nuevo 13.02.2020 * */
                if ($row->idProyecto == ID_PROYECTO_SISEGOS) {
                    $btnDatosSisego = '<i style="color:#A4A4A4;cursor:pointer" class="zmdi zmdi-hc-2x zmdi-assignment-o" data-itemplan="' . $row->itemPlan . '" title="Datos sisego" onclick="openModalDatosSisegos($(this))"></i>';
                }
                $flg_bandeja_siom = 'NO';
                if ($row->flg_bandeja_siom == 1) {
                    $flg_bandeja_siom = 'SI';
                }

                if ($row->idTipoSubProyecto == 2) {//INTEGRALES
                    $btnCvDoc = '<a><i style="color:#A4A4A4;cursor:pointer" data-ubic_tss_cv="' . $row->ubic_tss_cv . '" data-ubic_exped_cv="' . $row->ubic_exped_cv . '" data-comentario_cv="' . $row->comentario_cv . '" class="zmdi zmdi-hc-2x zmdi-case-download" onclick="getOpenModalDocumentosCV($(this));" title="Documentos CV"></i></a>';
                }

                $btnGoGestionOP = '';
                if ($row->idProyecto == ID_PROYECTO_OBRA_PUBLICA) {
                    if ($row->orden_compra == null && ($row->idEstadoPlan == 1 || $row->idEstadoPlan == 8)) {
                        $btnGoGestionOP = '<a target="_blank" onclick="openModalAlertaOc();">
												<i style="color:#A4A4A4;cursor:pointer" class="zmdi zmdi-hc-2x zmdi-city-alt" title="Gestion Obra Publica"></i>
											 </a>';
                    } else if ($row->idEstadoPlan == ID_ESTADO_CERRADO || $row->idEstadoPlan == ID_ESTADO_CANCELADO) {
                        $btnGoGestionOP = '<a onclick="showMessageIsObraCerrada(' . $row->idEstadoPlan . ')">
											<i style="color:#A4A4A4;cursor:pointer" class="zmdi zmdi-hc-2x zmdi-city-alt" title="Gestion Obra Publica"></i>
										  </a>';
                    } else {
                        $btnGoGestionOP = '<a href="gestionOP?item=' . $row->itemPlan . '" target="_blank">
											<i style="color:#A4A4A4;cursor:pointer" class="zmdi zmdi-hc-2x zmdi-city-alt" title="Gestion Obra Publica"></i>
										   </a>';
                    }
                }

                $has_formu = $this->m_utils->getInfoItemplanSisegoPlanObra($row->itemPlan);
                $linkSisegosFormu = '';
                if ($has_formu >= 1) {
                    $linkSisegosFormu = '<a href="infoSisego?item=' . $row->itemPlan . '" target="_blank"><i title="Datos de Formulario" style="color:#A4A4A4" title="ver" class="zmdi  zmdi-hc-2x zmdi-file-text"></i>';
                }

                //AGREGADO POR GUSTAVO SEDANO 2019 08 26
                /* $btnBandejaAdjudicacion = '';

                  $perfilUsuario = $this->session->userdata('idPerfilSession');
                  if($perfilUsuario == ID_PERFIL_ADMINISTRADOR || $perfilUsuario == ID_PERFIL_DISENO){
                  $listaBandejaAdjudicacion = $this->m_pqt_consulta->getBandejaDeAdjudicacionXItemPlan($row->itemPlan);
                  foreach($listaBandejaAdjudicacion->result() as $row2){
                  $countParalizados = $this->m_utils->countParalizados($row2->itemplan, FLG_ACTIVO, ORIGEN_WEB_PO);
                  $btnBandejaAdjudicacion = '<a data-has_coax="'.$row2->coaxial.'" data-has_fo="'.$row2->fo.'" data-item="'.$row2->itemplan.'" onclick="adjudicarDiseno($(this))"><img alt="Editar" height="25px" width="25px" src="public/img/iconos/icono_adjudicar.png" title="Adjudicar"></a>';

                  if($countParalizados > 0) {
                  $btnBandejaAdjudicacion  = 'PARALIZADO';
                  }
                  }
                  }
                 */
                //$btnVerProgreso = '<a data-est-plan="'.$row->idEstadoPlan.'" data-item="'.$row->itemPlan.'" onclick="verProgreso($(this))"><img alt="Ver Progreso" height="25px" width="25px" src="public/img/iconos/progress.png" title="Ver Progreso"></a>';
                if ($row->idTipoSubProyecto == 2) {//SI ES INTEGRAL
                    $btnVerProgreso = NULL;
                } else {
                     if (count($this->m_pqt_consulta->getDataDiseno($row->itemPlan)) == 0 && $row->idProyecto == ID_PROYECTO_OBRA_PUBLICA && $row->idEstadoPlan == ID_ESTADO_DISENIO_EJECUTADO) {
                        $btnVerProgreso = '<a onclick="showMessageIsObraPublica()"><img height="35px" width="35px" src="public/img/iconos/progress.png" title="Gestion"></a>';
                    } else {
                        $btnVerProgreso = ($row->idEstadoPlan == ID_ESTADO_CERRADO || $row->idEstadoPlan == ID_ESTADO_CANCELADO) ? '<a onclick="showMessageIsObraCerrada(' . $row->idEstadoPlan . ')"><img height="35px" width="35px" src="public/img/iconos/progress.png" title="Gestion"></a>' : '<a href="pqt_gestionarObra?item=' . $row->itemPlan . '&est=' . $row->idEstadoPlan . '"  target="_blank"><img height="35px" width="35px" src="public/img/iconos/progress.png" title="Gestion"></a>';
                    }
                }

                #$botonCancelar
				$btnLogOc = '<a data-itemplan="' . $row->itemPlan . '" onclick="openModalLogOc($(this))"><i title="ver Log OC" style="color:#A4A4A4" class="zmdi zmdi-hc-2x zmdi-search-in-file"></i></a>';
				
				 /**nuevo borrar ot actualizacion**/
                $btnDeleteOTAC = '';
                if(in_array($this->session->userdata('idPersonaSession'), array(8,33,3,1850,5,1806,1677))){//solo ciertos usuarios
                    $has_ot_ac = $this->m_utils->hasOTACRecibida($row->itemPlan, $row->itemPlan.'AC');
                    if($has_ot_ac > 0){
                        $btnDeleteOTAC = '<a><i style="color:#A4A4A4;cursor:pointer" class="zmdi zmdi-hc-2x zmdi-time-restore-setting" data-itemplan = '.$row->itemPlan.' onclick="deleteOtActualizacion($(this));" title="Revertir OT AC"></i></a>';
                    }else{
                        $has_ot_ac_2 = $this->m_utils->hasOTACCreada($row->itemPlan, $row->itemPlan.'AC');
                        if($has_ot_ac_2 > 0){
                            $btnDeleteOTAC = '<a><i style="color:#A4A4A4;cursor:pointer" class="zmdi zmdi-hc-2x zmdi-time-restore-setting" data-itemplan = '.$row->itemPlan.' onclick="deleteOtActualizacion($(this));" title="Revertir OT AC"></i></a>';
                        }
                    }
                }
				
				$btnCotizacion = null;
				// if($row->idEstadoPlan == 1 && $row->idContrato == 1) {
					// $btnCotizacion = '<a href="plantaInterna?item=' . $row->itemPlan . '&idSub=' . $row->idSubProyectoEstacion . '"  target="_blank"><i title="Ir a cotizacion" style="color:#A4A4A4" class="zmdi zmdi-hc-2x zmdi-money"></i></a>';
				// }
				
				$btnCheck = null;
				$dataPoPin = $this->m_utils->getPtrPlantaInterna($row->itemPlan);
				$perfilUsuario = $this->session->userdata('idPerfilSession');
				$idEmpresaColab = $this->session->userdata('eeccSession');
				if($perfilUsuario == ID_PERFIL_ADMINISTRADOR || $perfilUsuario == 3 || $perfilUsuario == 16 || $idEmpresaColab == 0 || $idEmpresaColab == 6) {
					if($row->orden_compra == NULL && $row->solicitud_oc == NULL && ($row->idEstadoPlan == 2 || $row->idEstadoPlan == 20) && $dataPoPin['ptr'] != null) {
						$btnCheck = '<a data-tipo="'.$dataPoPin['flg_tipo'].'" data-ptr ="'.$dataPoPin['ptr'].'" data-itmpl ="'.$row->itemPlan.'" onclick="openModalAprobacion(this)" title="aprobaci&oacute;n">
										<i style="color:black" class="zmdi zmdi-hc-2x zmdi-check-circle"></i>
									</a>';
					}
				}
				
				/**nuevo borrar ot actualizacion**/
				$btnPorcentraje = null;
				if($row->idEstadoPlan == ESTADO_PLAN_EN_OBRA) {
					$btnPorcentraje = '<a data-toggle="tooltip" data-trigger="hover" data-placement="bottom" data-original-title="Porcentaje" data-item_plan ="'.$row->itemPlan.'"  onclick="openModalPorcentaje($(this));" title="Liquidar Obra"><i class="zmdi zmdi-hc-2x zmdi zmdi-mail-reply-all"></i></a>';
				}
				
				//$idEECC = $this->session->userdata('eeccSession');
				$btnCargaEvi = '';
                /*if(($idEECC != 6 && $idEECC != 0) && $row->has_evidencia === '0'){
                    $btnCargaEvi = ' <a data-itemplan="'.$row->itemPlan.'" onclick="openModalCargaEvidencia(this);">
                                        <i style="color:#A4A4A4;" class="zmdi zmdi-hc-2x zmdi-upload" title="Carga Evidencias"></i>
                                     </a>';
                }*/
				
				
				$html .= '
                        <tr>
                              <td><div class="size" style="text-align: center;">' . (($countParalizados > 0 ? '<b>PARALIZADO</b> <br>' : '')) . $botonReanudar . ' ' . $botonCancelar . ' ' . $botonTruncar .' ' . $btnCvDoc . ' ' . $btnDatosSisego . ' ' . $iconsSiom . ' ' . $btnParalizacion . ' ' . (($row->idProyecto == ID_PROYECTO_SISEGOS) ? '<a href="http://200.48.131.32/obras2/general/estudio_itemplan.php?itemplan=' . $row->itemPlan . '" target="_blank"><i style="color:#A4A4A4" title="ver" class="zmdi  zmdi-hc-2x zmdi-eye"></i>' : '') . ' <a data-itemplan ="' . $row->itemPlan . '" onclick="zipItemPlan($(this));"><i style="color:#A4A4A4" title="evidencia" class="zmdi zmdi-hc-2x zmdi-folder-outline"></i></a>
                                                        <a data-idlog ="' . $row->itemPlan . '" onclick="mostrarLog(this)"><i title="Movimientos" style="color:#A4A4A4" class="zmdi zmdi-hc-2x zmdi-search"></i>
                                                        '.$btnCheck.' '.$btnLogOc.' '.$btnDeleteOTAC.' '.$btnCotizacion.' '.$btnPorcentraje.' '.$btnCargaEvi.'
								  </div></td> 
                            <td><a ' . (($row->idEstadoPlan == ID_ESTADO_CERRADO || $row->idEstadoPlan == ID_ESTADO_CANCELADO) ? 'onclick="showMessageIsObraCerrada(' . $row->idEstadoPlan . ')"' : 'href="' . (($row->idTipoPlanta == 1) ? 'pqt_detalleObra?item=' . $row->itemPlan . '&from=1' : 'detallePI?item=' . $row->itemPlan . '&from=1') . '"        target="_blank"') . '>' . $row->itemPlan . '</a></td>
                            <td>' .$row->usua_registro.'</td>
							<td>'.$row->indicador.'</td>
							<td style="color:red">' . (($row->solicitud_oc == null) ? NULL : (($row->estado_sol_oc == 'ATENDIDO') ? $row->orden_compra : $row->orden_compra )) . '</td>
							<td>' .$row->estado_oc.'</td>
							<td>' . $row->subProyectoDesc . '</td>
                            <td>' . $row->nombreProyecto . '</td>
							<td>' . $row->contrato_padre . '</td>
                            <td>' . $row->contrato_marco . '</td>
							<td>' . $row->codigo_unico . '</td>
                            <td>' . $row->empresaColabDesc . '</td>
                            <td>' . $row->faseDesc . '</td>
                            <th>' . $row->fechaInicio . '</th>
                            <th>' . $row->fechaPrevEjec . '</th>
                            <th>' . $row->fechaEjecucion . '</th>
							<th>' . $row->solicitud_oc . '</th>
                            <th>' . $row->estadoPlanDesc . '</th>
							<th>' . $row->costo_sap . '</th>
							<th>' . $row->costo_unitario_mo_crea_oc. '</th>
							<th>' . $row->costo_po_fin . '</th>
                            <th>' . $row->tipo_moneda. '</th>
							                            <th>' . $row->flg_opex. '</th>
                            <th>' . $row->pep2. '</th>
                            <th>' . $row->ceco. '</th>
                            <th>' . $row->cuenta. '</th>
                            <th>' . $row->area_funcional. '</th>
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
	
    public function filtrarTabla() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {

            $itemPlan = $this->input->post('itemplan');
            $nombreproyecto = $this->input->post('nombreproyecto');
            $proy = $this->input->post('proy');
            $subProy = $this->input->post('subProy');
            $tipoPlanta = $this->input->post('tipoPlanta');
            $idFase = $this->input->post('idFase');
            $idEECC = $this->session->userdata("eeccSession");
            $itemMadre = $this->input->post('itemMadre');
			$gestorObra = $this->input->post('gestorObra');
			
            if ($idEECC == null || $idEECC == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }

            // log_message('error',' -->EL FILTRO RECIBIDO SERIA: '.$filtroPrevEjec);
            //log_message('error', '-->datos enviados al modelo son : itemplan '.$itemPlan.' , nombreproyecto'.$nombreproyecto.' , nodo es '.$nodo.' , zonal es '.$zonal.' , proyecto es '.$proy.' , subproyecto es '.$subProy.' ,estado es :'.$estado.' , selectMesPrevEejec es '.$selectMesPrevEjec);
            //$estado = $this->input->post('estado');

            $data['tablaAsigGrafo'] = $this->makeHTLMTablaConsulta($this->m_pqt_consulta->getPtrConsultaPqt($itemPlan, $nombreproyecto, $proy, $subProy, $tipoPlanta, $idEECC, null, null, $idFase, $itemMadre, $gestorObra));

            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    /*     * **********************LOG SISTEMA************************* */

    public function mostrarLogItemPlanConsulta() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {

            $itemPlan = $this->input->post('itemplan');

            $this->session->set_flashdata('itemPlan', $itemPlan);

            // log_message('error',' ejecuto dato1');
            #$data['listaLog'] = $this->getHtmlLogPqt($itemPlan);
            $data['listaLog'] = $this->getHtmlLogPqt_v2($itemPlan);

            // log_message('error',' ejecuto dato');

            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function getHtmlLogPqt($itemPlan) {
        $html = '<h3>' . $itemPlan . '</h3>';
        $list = $this->m_pqt_consulta->mostrarLog($itemPlan);
        $html .= ' <table id="table-cotizacion" class="table table-bordered">
                                    <thead class="thead-default">
                                        <tr>
                                        <th style="font-size:12px;">ESTADO</th>
                                        <th style="font-size:12px;">FECHA</th>
                                        <th style="font-size:12px;">USUARIO.</th>
                                        <th style="font-size:12px;">ESTADO ANTERIOR A CANCELACION/TRUNCAR/PARALIZAR</th>
                                        <th style="font-size:12px;">MOTIVO</th>
                                    </tr>
                                    </thead>
                                    <tbody>';
        foreach ($list->result() as $row) {
            $html .= '<tr>';
            $html .= '<td>' . utf8_decode($row->estadoPlanDesc) . '</td>';
            $html .= '<td>' . $row->fecha_registro . '</td>';
            $html .= '<td>' . utf8_decode($row->usuario) . '</td>';
            $html .= '<td>' . $row->estado_ant_a_c_t_p . '</td>';
            $html .= '<td><span title="' . $row->comentario . '">' . $row->motivo . '</span></td>';
            $html .= '</tr>';
        }
        $html .= '</tbody>
                  </table>';
        return $html;
    }

    public function getHtmlLogPqt_v2($itemPlan) {
        $html = '<h3>' . $itemPlan . '</h3>';
        $list = $this->m_pqt_consulta->mostrarLog2_0($itemPlan);
        $html .= ' <table id="table-cotizacion" class="table table-bordered">
                                    <thead class="thead-default">
                                        <tr>
                                        <th style="font-size:12px;">ESTADO</th>
                                        <th style="font-size:12px;">FECHA</th>
                                        <th style="font-size:12px;">USUARIO.</th>
                                        <th style="font-size:12px;">MOTIVO.</th>
                                        <th style="font-size:12px;">COMENTARIO</th>
                                    </tr>
                                    </thead>
                                    <tbody>';
        foreach ($list->result() as $row) {
            $html .= '<tr>';
            $html .= '<td>' . utf8_decode($row->estadoPlanDesc) . '</td>';
            $html .= '<td>' . $row->fecha_upd . '</td>';
            $html .= '<td>' . utf8_decode($row->usuario) . '</td>';
            $html .= '<td>' . $row->motivo . '</td>';
            $html .= '<td>' . $row->comentario . '</td>';
            $html .= '</tr>';
        }
        $html .= '</tbody>
                  </table>';
        return $html;
    }

    public function getHTMLTabsLog() {

        $planobralog = $this->getAllDataLogPlanObra2();
        //$ptrlog = $this->getAllDataLogPTR();
        $porcentajelog = $this->getAllDataLogPorcentajeAvance();
        //$poVRlog = $this->getAllDataLogPOVR();

        $html = '';
        $htmlTabCoti = '';
        $htmlCotizacion = '';
        $flgCotizacion = $this->m_log_ingfix->getFlgCotizacion($this->session->flashdata('itemPlan'));

        if ($flgCotizacion == '1') {

            $htmlTabCoti = '<li class="nav-item">
                                    <a class="nav-link " data-toggle="tab" href="#tabCoti" role="tab">COTIZACION</a>
                            </li>';

            $arrayCotizacion = $this->m_log_ingfix->getDetalleCotizacion($this->session->flashdata('itemPlan'));
            $htmlCotizacion .= ' <table id="table-cotizacion" class="table table-bordered">
                                    <thead class="thead-default">
                                        <tr>
                                        <th style="font-size:12px;">ESTADO</th>
                                        <th style="font-size:12px;">FECHA</th>
                                        <th style="font-size:12px;">USUARIO.</th>
                                        <th style="font-size:12px;">PDF</th>
                                        <th style="font-size:12px;">MONTO</th>
                                    </tr>
                                    </thead>
                                    <tbody>';


            $htmlCotizacion .= '<tr>
                                                    <th>COTIZACION SOLICITADA</th>
                                                    <td>' . $arrayCotizacion['fecha_creacion'] . '</td>
                                                    <td>' . $arrayCotizacion['usuario_registra'] . '</td>
                                                    <td style="text-align:center">
                                                        <a href="' . $arrayCotizacion['path_pdf_to_cotiza'] . '" download><i class="zmdi zmdi-hc-2x zmdi-collection-pdf"></i></a>
                                                    </td>
                                                    <td>-</td>
                                            </tr>';

            if ($arrayCotizacion['confirma_recep'] == 1) {
                $htmlCotizacion .= '<tr>                        
                                                    <th>SOLICITUD RECEPCIONADA</th>
                                                    <td>' . $arrayCotizacion['fecha_confirma_recep'] . '</td>
                                                    <td>' . $arrayCotizacion['usuario_confirma_recep'] . '</td>
                                                    <td style="text-align:center">
                                                        -
                                                    </td>
                                                    <td>-</td>
                                            </tr>';
            }
            if ($arrayCotizacion['fecha_registro_pdf'] != null) {
                $htmlCotizacion .= '<tr>
                                                    <th>COTIZACION ELABORADA</th>
                                                    <td>' . $arrayCotizacion['fecha_registro_pdf'] . '</td>
                                                    <td>' . $arrayCotizacion['usuario_registro_pdf'] . '</td>
                                                    <td style="text-align:center">
                                                        <a href="' . $arrayCotizacion['ruta_pdf'] . '" download><i class="zmdi zmdi-hc-2x zmdi-collection-pdf"></i></a>
                                                    </td>
                                                    <td>' . number_format($arrayCotizacion['monto'], 2) . '</td>
                                            </tr>';
            }
            if ($arrayCotizacion['fecha_envio_cotizacion'] != null && $arrayCotizacion['estado'] >= 3) {
                $htmlCotizacion .= '<tr>
                                                    <th>COTIZACION ENVIADA</th>
                                                    <td>' . $arrayCotizacion['fecha_envio_cotizacion'] . '</td>
                                                    <td>' . $arrayCotizacion['usuario_envio_cotizacion'] . '</td>
                                                    <td style="text-align:center">
                                                         -
                                                    </td>
                                                    <td>' . number_format($arrayCotizacion['monto'], 2) . '</td>
                                            </tr>';
            }

            if ($arrayCotizacion['fecha_aprueba_cotizacion'] != null && $arrayCotizacion['estado'] >= 4) {
                if ($arrayCotizacion['estado'] == 4) {
                    $estadoCot = 'COTIZACION APROBADA';
                } else if ($arrayCotizacion['estado'] == 5) {
                    $estadoCot = 'COTIZACION DEVUELTA';
                } else if ($arrayCotizacion['estado'] == 6) {
                    $estadoCot = 'COTIZACION RECHAZADA';
                }
                $htmlCotizacion .= '<tr>
                                                    <th>' . $estadoCot . '</th>
                                                    <td>' . $arrayCotizacion['fecha_aprueba_cotizacion'] . '</td>
                                                    <td>' . $arrayCotizacion['usua_aprueba_cotizacion'] . '</td>
                                                    <td style="text-align:center">
                                                         -
                                                    </td>
                                                    <td>' . number_format($arrayCotizacion['monto'], 2) . '</td>
                                            </tr>';
            }


            $htmlCotizacion .= '</tbody>
                            </table>';
        }


        $html0 = '<div class="tab-container">
                            <ul class="nav nav-tabs nav-fill" role="tablist">
                                <li class="nav-item">
                                     <a class="nav-link" data-toggle="tab" href="#tabDetallePO" role="tab">DETALLE IP</a>
                                </li>
                                <li class="nav-item">
                                     <a class="nav-link active" data-toggle="tab" href="#tabPlanObra" role="tab">PLAN DE OBRA</a>
                                </li>
                                <li class="nav-item">
                                     <a class="nav-link " data-toggle="tab" href="#tabAvance" role="tab">% AVANCE</a>
                                </li>
                                ' . $htmlTabCoti . '
                            </ul>

                        <div class="tab-content">';

        $arrayDetIP = $this->m_log_ingfix->getDetalleIP($this->session->flashdata('itemPlan'));


        $htmlP = '<div class="tab-pane fade show" id="tabDetallePO" role="tabpanel">
                        <div class="row">
                            <div class="col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label>FECHA DE CREACION</label>
                                    <input id="fechaCreacion" type="text" class="form-control" value="' . $arrayDetIP['fecha_creacion'] . '" disabled>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label>FECHA DE INICIO</label>
                                    <input id="fechaInicio" type="text" class="form-control" value="' . $arrayDetIP['fechaInicio'] . '" disabled>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label>FECHA PREV. EJEC.</label>
                                    <input id="fechaPrevEjec" type="text" class="form-control" value="' . $arrayDetIP['fechaPrevEjec'] . '" disabled>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label>FECHA TERMINO</label>
                                    <input id="fechaEjecucion" type="text" class="form-control" value="' . $arrayDetIP['fechaEjecucion'] . '" disabled>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label>FECHA PRE LIQUIDACION</label>
                                    <input id="fechaPreliquidacion" type="text" class="form-control" value="' . $arrayDetIP['fechaPreliquidacion'] . '" disabled>
                                </div>
                            </div>
                        </div>
                  </div>';

        $htmlA = '<div class="tab-pane active fade show" id="tabPlanObra" role="tabpanel">
                                  <div class="row" style="overflow-y: scroll;height: 300px;">' . $planobralog . '</div>
                        </div>';

        // $htmlB = '<div class="tab-pane  fade show" id="tabPTR" role="tabpanel">
        //                           <div class="row" style="overflow-y: scroll;height: 300px;">' . $ptrlog . '</div>
        //                 </div>';

        $htmlC = '<div class="tab-pane  fade show" id="tabAvance" role="tabpanel">
                                  <div class="row" style="overflow-y: scroll;height: 300px;">' . $porcentajelog . '</div>
                        </div>';

        // $htmlD = '<div class="tab-pane  fade show" id="tabVR" role="tabpanel">
        //                 <div class="row" style="overflow-y: scroll;height: 300px;">' . $poVRlog . '</div>
        //              </div>';

        $htmlE = '<div class="tab-pane fade show" id="tabCoti" role="tabpanel">
                        <div class="row" style="overflow-y: scroll;height: 300px;">' . $htmlCotizacion . '</div>
                    </div>';

        $html = $html0 . $htmlP . $htmlA . $htmlC . $htmlE . '</div></div>';

        return $html;
    }

    public function getAllDataLogPlanObra() {

        try {

            $logPreRegistro = $this->m_log_ingfix->getPreRegistroLog($this->session->flashdata('itemPlan'));
            $logPreDiseno = $this->m_log_ingfix->getPreDisenoLog($this->session->flashdata('itemPlan'));
            $logPredisenoUpdate = $this->m_log_ingfix->getPreDisenoUpdateLog($this->session->flashdata('itemPlan'));
            $logDisenoUpdate = $this->m_log_ingfix->getDisenoUpdateLog($this->session->flashdata('itemPlan'));

            $logDiseno = $this->m_log_ingfix->getDisenoLog($this->session->flashdata('itemPlan'));
            $logDisenoEjecutado = $this->m_log_ingfix->getDisenoEjecutadoLog($this->session->flashdata('itemPlan'));
            $logDisenoParcial = $this->m_log_ingfix->getDisenoParcialLog($this->session->flashdata('itemPlan'));

            $logEnObra = $this->m_log_ingfix->getEnObraLog($this->session->flashdata('itemPlan'));
            $logPreLiquidacion = $this->m_log_ingfix->getPreLiquidacionLog($this->session->flashdata('itemPlan'));
            $logTerminado = $this->m_log_ingfix->getTerminadoLog($this->session->flashdata('itemPlan'));

            $logTrunco = $this->m_log_ingfix->getTruncoLog($this->session->flashdata('itemPlan'));
            $logCancelado = $this->m_log_ingfix->getCanceladoLog($this->session->flashdata('itemPlan'));
            $logCerrado = $this->m_log_ingfix->getCerradoLog($this->session->flashdata('itemPlan'));

            $logFichaTecnicaReg = $this->m_log_ingfix->getFichaTecnicaLogReg($this->session->flashdata('itemPlan'));
            $logFichaTecnicaVal = $this->m_log_ingfix->getFichaTecnicaLogVal($this->session->flashdata('itemPlan'));

            $logCertificacion = $this->m_log_ingfix->getCertificacionLog($this->session->flashdata('itemPlan'));

            $htmlCabcer = '
                             <table id="data-tablePO"  class="table table-bordered"  style="font-size: smaller;">
                                <thead class="thead-default">
                                    <tr>
                                       <th onclick="w3.sortHTML(' . "'" . '#data-tablePO' . "'" . ', ' . "'" . '.item' . "'" . ', ' . "'" . 'td:nth-child(3)' . "'" . ')" style="cursor:pointer">ACCION <i class="zmdi zmdi-unfold-more"></i></th>

                                         <th onclick="w3.sortHTML(' . "'" . '#data-tablePO' . "'" . ', ' . "'" . '.item' . "'" . ', ' . "'" . 'td:nth-child(2)' . "'" . ')" style="cursor:pointer">ESTADO <i class="zmdi zmdi-unfold-more"></i></th>

                                        <th>USUARIO</th>

                                         <th onclick="w3.sortHTML(' . "'" . '#data-tablePO' . "'" . ', ' . "'" . '.item' . "'" . ', ' . "'" . 'td:nth-child(1)' . "'" . ')" style="cursor:pointer">FECHA REGISTRO <i class="zmdi zmdi-unfold-more"></i></th>

                                        <th>OBSERVACIONES</th>
                                    </tr>
                                </thead>
                                <tbody>';

            $htmlPreReg = "";
            $htmlPreDiseno = "";
            $htmlPreDisenoUpdate = "";
            $htmlDisenoUpdate = "";
            $htmlDiseno = "";
            $htmlDisenoEjecu = "";
            $htmlDisenoParcial = "";

            $htmlFichaTecnicaReg = "";
            $htmlFichaTecnicaVal = "";
            $htmlCertificacion = "";

            $htmlEnObra = "";
            $htmlPreLiquida = "";
            $htmlTerminado = "";
            $htmlTrunco = "";
            $htmlCancelado = "";
            $htmlCerrado = "";

            /*

              $arrayLog1 = array();
              $arrayLog2 = array();
              $arrayLog3 = array();
              $arrayLog4 = array();
              $arrayLog5 = array();
              $arrayLog6 = array();
              $arrayLog7 = array();
              $arrayLog8 = array();
              $arrayLog9 = array();
              $arrayLog10 = array();
              $arrayLog11 = array();
              $arrayLog12 = array();
              $arrayLog13 = array();

              if(count($logPreRegistro->result()) >0){
              foreach($logPreRegistro->result() as $row){
              array_push($arrayLog1,$row->fecha_creacion, $row->registro,$row->estado,$row->usua_crea,'');
              }
              }

              if(count($logPreDiseno->result()) >0){
              foreach($logPreDiseno->result() as $row){
              array_push($arrayLog2,$row->fecha_creacion, $row->registro, $row->estado,$row->nombre,'');
              }
              }

              if(count($logPredisenoUpdate->result()) >0){
              foreach($logPredisenoUpdate->result() as $row){
              array_push($arrayLog3, $row->fecha_creacion,'REGISTRO',$row->estado,$row->nombre,
              'RETORNO A PREDISEÑO PARA REASIGNACION DE DISEÑO');

              }
              }

              if(count($logDisenoUpdate->result()) >0){
              foreach($logDisenoUpdate->result() as $row){
              array_push($arrayLog4,$row->fecha_registro, strtoupper($row->estado),  $row->estado,$row->nombre,
              'RETORNO A DISEÑO PARA MODIFICACION');
              }
              }

              if(count($logDiseno->result()) >0){
              foreach($logDisenoUpdate->result() as $row){
              array_push($arrayLog5,$row->fecha_adjudicacion, $row->registro,  $row->estado,$row->usuario_adjudicacion,
              'ADJUDICACION EN LA ESTACION '.$row->estacionDesc);
              }
              }

              if(count($logDisenoEjecutado->result()) >0){
              foreach($logDisenoEjecutado->result() as $row){
              array_push($arrayLog6,$row->fecha_ejecucion, $row->registro, $row->estado,$row->usuario_ejecucion,
              'EJECUCION EN LA ESTACION '.$row->estacionDesc);
              }
              }

              if(count($logDisenoParcial->result()) >0){
              foreach($logDisenoParcial->result() as $row){
              array_push($arrayLog7, $row->fecha_registro, $row->registro,  $row->estado,$row->nombre,
              '');
              }
              }

              if(count($logEnObra->result()) >0){
              foreach($logEnObra->result() as $row){
              array_push($arrayLog8, $row->fecha_registro, $row->registro, $row->estado,$row->nombre,
              '');
              }
              }

              if(count($logPreLiquidacion->result()) >0){

              foreach($logPreLiquidacion->result() as $row){
              $fecha=$row->fecha_registro;

              if($this->validaFechaTermino($fecha)){
              array_push($arrayLog9,$row->fecha_registro, $row->registro,  $row->estado,$row->nombre,
              '');
              }else{
              array_push($arrayLog9, $row->fecha_registro,$row->registro, 'Terminado',$row->nombre,
              '');
              }
              }
              }

              if(count($logTerminado->result()) >0){

              foreach($logTerminado->result() as $row){
              array_push($arrayLog10,$row->fecha_registro, $row->registro,  $row->estado,$row->nombre,
              '');
              }

              }

              if(count($logTrunco->result()) >0){

              foreach($logTrunco->result() as $row){

              $logMotivoTrunco= $this->m_log_ingfix->getMotivoTruncoCanceladoLog($this->session->flashdata('itemPlan'),
              $row->fecha_registro,10);
              $boton='';
              if(count($logMotivoTrunco->result()) >0){
              $boton='<button data-fechaT='.$row->fecha_registro.' data-itemT='.$this->session->flashdata('itemPlan').' style="color: white;background-color: var(--verde_telefonica)" class="btn btn-secondary waves-effect" data-toggle="modal" onclick="verMotivoTrunco(this)">Ver Motivo</button>';
              }
              array_push($arrayLog11, $row->fecha_registro, 'OBRA TRUNCA', tr_replace("|","<br>",$row->accion),$row->nombre,
              $boton);

              }

              }

              if(count($logCancelado->result()) >0){

              foreach($logCancelado->result() as $row){

              $logMotivoCancelado= $this->m_log_ingfix->getMotivoTruncoCanceladoLog($this->session->flashdata('itemPlan'),
              $row->fecha_registro,6);
              $boton='';
              if(count($logMotivoCancelado->result()) >0){
              $boton='<button data-fechaC='.$row->fecha_registro.' data-itemC='.$this->session->flashdata('itemPlan').' style="color: white;background-color: var(--verde_telefonica);" class="btn btn-secondary waves-effect" data-toggle="modal" onclick="verMotivoCancelar(this)">Ver Motivo</button>';
              }
              array_push($arrayLog12,$row->fecha_registro, 'OBRA CANCELADA',  $row->estado,$row->nombre,
              $boton);
              }

              }

              if(count($logCerrado->result()) >0){

              foreach($logCerrado->result() as $row){
              array_push($arrayLog13,$row->fecha_registro, $row->registro,  $row->estado,$row->nombre,
              '');
              }

              }

              $arrayLogMatriz = array($arrayLog1,
              $arrayLog2,
              $arrayLog3,
              $arrayLog4,
              $arrayLog5,
              $arrayLog6,
              $arrayLog7,
              $arrayLog8,
              $arrayLog9,
              $arrayLog10,
              $arrayLog11,
              $arrayLog12,
              $arrayLog13);

              array_multisort($arrayLogMatriz, SORT_DESC,$arrayLogMatriz[0][1] );
             */

            // array_multisort( $arrayLogMatriz);

            /*             * ******************************************************************************************************** */

            if (count($logPreRegistro->result()) > 0) {

                foreach ($logPreRegistro->result() as $row) {
                    $htmlPreReg .= '<tr class="item">
                                   <td>' . $row->registro . '</td>
                                   <td>' . $row->estado . '</td>
                                   <td>' . $row->usua_crea . '</td>
                                   <td>' . $row->fecha_creacion . '</td>
                                   <td> </td>
                                   </tr>';
                }
            }

            if (count($logPreDiseno->result()) > 0) {

                foreach ($logPreDiseno->result() as $row) {
                    $htmlPreDiseno .= '<tr class="item">
                                   <td>' . $row->registro . '</td>
                                   <td>' . $row->estado . '</td>
                                   <td>' . $row->nombre . '</td>
                                   <td>' . $row->fecha_registro . '</td>
                                   <td> </td>
                                   </tr>';
                }
            }

            if (count($logPredisenoUpdate->result()) > 0) {

                foreach ($logPredisenoUpdate->result() as $row) {
                    $htmlPreDisenoUpdate .= '<tr class="item">
                                   <td>REGISTRO</td>
                                   <td>' . $row->estado . '</td>
                                   <td>' . $row->nombre . '</td>
                                   <td>' . $row->fecha_registro . '</td>
                                   <td> RETORNO A PREDISEÑO PARA REASIGNACION DE DISEÑO </td>
                                   </tr>';
                }
            }

            if (count($logDisenoUpdate->result()) > 0) {

                foreach ($logDisenoUpdate->result() as $row) {
                    $htmlDisenoUpdate .= '<tr class="item">
                                   <td>' . strtoupper($row->estado) . '</td>
                                   <td>' . $row->estado . '</td>
                                   <td>' . $row->nombre . '</td>
                                   <td>' . $row->fecha_registro . '</td>
                                   <td> RETORNO A DISEÑO PARA MODIFICACION</td>
                                   </tr>';
                }
            }

            if (count($logDiseno->result()) > 0) {

                foreach ($logDiseno->result() as $row) {
                    $htmlDiseno .= '<tr class="item">
                                   <td>' . $row->registro . '</td>
                                   <td>' . $row->estado . '</td>
                                   <td>' . $row->usuario_adjudicacion . '</td>
                                   <td>' . $row->fecha_adjudicacion . '</td>
                                   <td>ADJUDICACION EN LA ESTACION ' . $row->estacionDesc . ' </td>
                                   </tr>';
                }
            }

            if (count($logDisenoEjecutado->result()) > 0) {

                foreach ($logDisenoEjecutado->result() as $row) {
                    $htmlDisenoEjecu .= '<tr class="item">
                                   <td>' . $row->registro . '</td>
                                   <td>' . $row->estado . '</td>
                                   <td>' . $row->usuario_ejecucion . '</td>
                                   <td>' . $row->fecha_ejecucion . '</td>
                                   <td>EJECUCION EN LA ESTACION ' . $row->estacionDesc . ' </td>
                                   </tr>';
                }
            }

            if (count($logDisenoParcial->result()) > 0) {

                foreach ($logDisenoParcial->result() as $row) {
                    $htmlDisenoParcial .= '<tr class="item">
                                   <td>' . $row->registro . '</td>
                                   <td>' . $row->estado . '</td>
                                   <td>' . $row->nombre . '</td>
                                   <td>' . $row->fecha_registro . '</td>
                                   <td> </td>
                                   </tr>';
                }
            }

            if (count($logEnObra->result()) > 0) {

                foreach ($logEnObra->result() as $row) {
                    $htmlEnObra .= '<tr class="item">
                                   <td>' . $row->registro . '</td>
                                   <td>' . $row->estado . '</td>
                                   <td>' . $row->nombre . '</td>
                                   <td>' . $row->fecha_registro . '</td>
                                   <td> </td>
                                   </tr>';
                }
            }

            if (count($logPreLiquidacion->result()) > 0) {

                foreach ($logPreLiquidacion->result() as $row) {
                    $fecha = $row->fecha_registro;

                    if ($this->validaFechaTermino($fecha)) {
                        $htmlPreLiquida .= '<tr class="item">
                                   <td>' . $row->registro . '</td>
                                   <td>' . $row->estado . '</td>
                                   <td>' . $row->nombre . '</td>
                                   <td>' . $row->fecha_registro . '</td>
                                   <td> </td>
                                   </tr>';
                    } else {
                        $htmlPreLiquida .= '<tr class="item">
                                   <td>' . $row->registro . '</td>
                                   <td>Terminado</td>
                                   <td>' . $row->nombre . '</td>
                                   <td>' . $row->fecha_registro . '</td>
                                   <td> </td>
                                   </tr>';
                    }
                }
            }

            if (count($logTerminado->result()) > 0) {

                foreach ($logTerminado->result() as $row) {
                    $htmlTerminado .= '<tr class="item">
                                   <td>' . $row->registro . '</td>
                                   <td>' . $row->estado . '</td>
                                   <td>' . $row->nombre . '</td>
                                   <td>' . $row->fecha_registro . '</td>
                                   <td> </td>
                                   </tr>';
                }
            }

            if (count($logTrunco->result()) > 0) {

                foreach ($logTrunco->result() as $row) {

                    $logMotivoTrunco = $this->m_log_ingfix->getMotivoTruncoCanceladoLog($this->session->flashdata('itemPlan'), $row->fecha_registro, 10);
                    $boton = '';
                    if (count($logMotivoTrunco->result()) > 0) {
                        $boton = '<button data-fechaT=' . $row->fecha_registro . ' data-itemT=' . $this->session->flashdata('itemPlan') . ' style="color: white;background-color: var(--verde_telefonica)" class="btn btn-secondary waves-effect" data-toggle="modal" onclick="verMotivoTrunco(this)">Ver Motivo</button>';
                    }

                    $htmlTrunco .= '<tr class="item">
                                   <td>OBRA TRUNCA</td>
                                   <td>' . str_replace("|", "<br>", $row->accion) . '</td>
                                   <td>' . $row->nombre . '</td>
                                   <td>' . $row->fecha_registro . '</td>
                                    <td>' . $boton . ' </td>
                                   </tr>';
                }
            }

            if (count($logCancelado->result()) > 0) {

                foreach ($logCancelado->result() as $row) {

                    $logMotivoCancelado = $this->m_log_ingfix->getMotivoTruncoCanceladoLog($this->session->flashdata('itemPlan'), $row->fecha_registro, 6);
                    $boton = '';
                    if (count($logMotivoCancelado->result()) > 0) {
                        $boton = '<button data-fechaC=' . $row->fecha_registro . ' data-itemC=' . $this->session->flashdata('itemPlan') . ' style="color: white;background-color: var(--verde_telefonica);" class="btn btn-secondary waves-effect" data-toggle="modal" onclick="verMotivoCancelar(this)">Ver Motivo</button>';
                    }

                    $htmlCancelado .= '<tr class="item">
                                   <td>OBRA CANCELADA</td>
                                   <td>' . $row->estado . '</td>
                                   <td>' . $row->nombre . '</td>
                                   <td>' . $row->fecha_registro . '</td>
                                   <td>' . $boton . ' </td>
                                   </tr>';
                }
            }

            if (count($logCerrado->result()) > 0) {

                foreach ($logCerrado->result() as $row) {
                    $htmlCerrado .= '<tr class="item">
                                   <td>' . $row->registro . '</td>
                                   <td>' . $row->estado . '</td>
                                   <td>' . $row->nombre . '</td>
                                   <td>' . $row->fecha_registro . '</td>
                                   <td> </td>
                                   </tr>';
                }
            }

            if (count($logFichaTecnicaReg->result()) > 0) {

                foreach ($logFichaTecnicaReg->result() as $row) {
                    $htmlFichaTecnicaReg .= '<tr class="item">
                                   <td>' . $row->registro . '</td>
                                   <td>' . $row->estadoPlanDesc . '</td>
                                   <td>' . $row->nombre . '</td>
                                   <td>' . $row->fecha_registro . '</td>
                                   <td> </td>
                                   </tr>';
                }
            }

            if (count($logFichaTecnicaVal->result()) > 0) {

                foreach ($logFichaTecnicaVal->result() as $row) {
                    $htmlFichaTecnicaVal .= '<tr class="item">
                                   <td>' . $row->validacion . '</td>
                                   <td>' . $row->estadoPlanDesc . '</td>
                                   <td>' . $row->nombre . '</td>
                                   <td>' . $row->fecha_validacion . '</td>
                                   <td> </td>
                                   </tr>';
                }
            }

            if (count($logCertificacion->result()) > 0) {

                foreach ($logCertificacion->result() as $row) {
                    $htmlCertificacion .= '<tr class="item">
                                   <td>' . $row->accion . '</td>
                                   <td>' . $row->estado_final . '</td>
                                   <td>' . $row->nombre . '</td>
                                   <td>' . $row->fecha_valida . '</td>
                                   <td>' . $row->observacion . '</td>
                                   </tr>';
                }
            }

            $html = trim($htmlCabcer . $htmlPreReg . $htmlPreDiseno . $htmlPreDisenoUpdate .
                    $htmlDisenoUpdate . $htmlDiseno . $htmlDisenoEjecu . $htmlDisenoParcial . $htmlEnObra . $htmlPreLiquida .
                    $htmlTerminado . $htmlTrunco . $htmlCancelado . $htmlCerrado . $htmlFichaTecnicaReg . $htmlFichaTecnicaVal . $htmlCertificacion . '</tbody>
                         </table><br><br>');

            return utf8_decode($html);
        } catch (Exception $e) {
            throw new Exception('Hubo un error ejecutarse la funcion getAllDataLogPlanObra');
            return utf8_decode("null");
        }
    }

    public function getAllDataLogPOVR() {
        try {
            $logCertificacion = $this->m_log_ingfix->getSolicitudVRByIP($this->session->flashdata('itemPlan'));

            $html = '<table id="data-tableVR"  class="table table-bordered"  style="font-size: smaller;">
                        <thead class="thead-default">
                            <th>VALE DE RESERVA</th>
                            <th>PTR</th>
                            <th>COD MATERIAL</th>
                            <th>DESCRIPCION</th>
                            <th>ESTADO</th>
                            <th>USUARIO</th>
                            <th>FECHA DE REGISTRO</th>
                            <th>FECHA DE ATENCION</th>
                            <th>ACCION</th>
                        </thead>
                        <tbody>';
            if (count($logCertificacion) > 0) {
                foreach ($logCertificacion as $row) {
                    $html .= '
                        <tr>
                            <td>' . $row->vr . '</td>
                            <td>' . $row->ptr . '</td>
                            <td>' . $row->material . '</td>
                            <td>' . $row->desc_material . '</td>
                            <td>' . $row->estado . '</td>
                            <td>' . $row->nombre . '</td>
                            <td>' . $row->fecha_registro . '</td>
                            <td>' . $row->fecha_atencion . '</td>
                            <td style="text-align:center">
                                <a style="cursor:pointer; color: var(--verde_telefonica)" data-idsolvr="' . $row->idSolicitudValeReserva . '" onclick="openModalDetLogVR(this)"><i class="zmdi zmdi-hc-2x zmdi-eye"></i></a>
                            </td>
                        </tr>
                        ';
                }
            }
            $html .= '</tbody>
                </table>';

            return utf8_decode($html);
        } catch (Exception $e) {
            throw new Exception('Hubo un error ejecutarse la funcion getAllDataLogPOVR');
            return utf8_decode("null");
        }
    }

    public function getDetalleVRById() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {

            $idSolVR = $this->input->post('idSolVR');

            $data['tablaDetVR'] = $this->makeHTLMTablaDetVR($this->m_log_ingfix->getDetalleLogVR($idSolVR));

            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function makeHTLMTablaDetVR($listaDetalleVR) {

        $html = '<table id="data-tableDetVR"  class="table table-bordered"  style="font-size: smaller;">
                    <thead class="thead-default">
                        <th>PTR</th>
                        <th>USUARIO</th>
                        <th>FECHA DE REGISTRO</th>
                        <th>COMENTARIO</th>
                        <th>ESTADO</th>
                    </thead>
                    <tbody>';

        if (count($listaDetalleVR) > 0) {
            foreach ($listaDetalleVR as $row) {
                $html .= '
                        <tr>
                            <td>' . $row->ptr . '</td>
                            <td>' . $row->nombre . '</td>
                            <td>' . $row->fecha_registro . '</td>
                            <td>' . $row->comentario . '</td>
                            <td>' . $row->estado . '</td>
                        </tr>
                        ';
            }
        }
        $html .= '</tbody>
                </table>';

        return utf8_decode($html);
    }

    public function getMotivoCancelConsulta() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        try {

            $itemPlan = $this->input->post('itemplan');
            $fechaCancel = $this->input->post('fecha');

            $this->session->set_flashdata('fechaCancel', $fechaCancel);
            $this->session->set_flashdata('itemPlan', $itemPlan);

            // log_message('error', 'fecha'.$this->session->flashdata('fechaCancel'));

            $data['motivoCancel'] = $this->gettHTMLMotivoCancel();

            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function getMotivoTruncoConsulta() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        try {

            $itemPlan = $this->input->post('itemplan');
            $fechaTrunco = $this->input->post('fecha');

            $this->session->set_flashdata('fechaTrunco', $fechaTrunco);
            $this->session->set_flashdata('itemPlan', $itemPlan);

            $data['motivoTrunco'] = $this->gettHTMLMotivoTrunco();

            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function gettHTMLMotivoCancel() {

        $htmlCabcerMotivo = ' <div>MOTIVO CANCELACION PLAN OBRA</div>
                            <table class="table table-bordered" >
                                <thead class="thead-default">
                                    <tr>
                                        <th>COMENTARIO</th>
                                        <th>USUARIO</th>
                                        <th>FECHA REGISTRO</th>
                                    </tr>
                                </thead>
                                <tbody>';

        $logMotivoCancel = $this->m_log_ingfix->getMotivoTruncoCanceladoLog($this->session->flashdata('itemPlan'), $this->session->flashdata('fechaCancel'), 6);
        $htmlMotivoCancel = "";

        if (count($logMotivoCancel->result()) > 0) {

            foreach ($logMotivoCancel->result() as $row) {
                $htmlMotivoCancel .= '<tr>
                                   <td>' . $row->comentario . '</td>
                                   <td>' . $row->nombre . '</td>
                                   <td>' . $row->fecha . '</td>
                                   </tr>';
            }

            $htmlCancel = $htmlCabcerMotivo . $htmlMotivoCancel . '</tbody>
                         </table><br><br>';

            return utf8_decode($htmlCancel);
        } else {
            $htmlCancel = 'No existe motivo';
            return utf8_decode($htmlCancel);
        }
    }

    public function gettHTMLMotivoTrunco() {

        $htmlCabcerMotivo = ' <div>MOTIVO TRUNCO PLAN OBRA</div>
                            <table class="table table-bordered" >
                                <thead class="thead-default">
                                    <tr>
                                        <th>COMENTARIO</th>
                                        <th>USUARIO</th>
                                        <th>FECHA REGISTRO</th>
                                    </tr>
                                </thead>
                                <tbody>';

        $logMotivoTrunco = $this->m_log_ingfix->getMotivoTruncoCanceladoLog($this->session->flashdata('itemPlan'), $this->session->flashdata('fechaTrunco'), 10);
        $htmlMotivoTrunco = "";

        if (count($logMotivoTrunco->result()) > 0) {

            foreach ($logMotivoTrunco->result() as $row) {
                $htmlMotivoTrunco .= '<tr>
                                   <td>' . $row->comentario . '</td>
                                   <td>' . $row->nombre . '</td>
                                   <td>' . $row->fecha . '</td>
                                   </tr>';
            }

            $htmlTrunco = $htmlCabcerMotivo . $htmlMotivoTrunco . '</tbody>
                         </table><br><br>';

            return utf8_decode($htmlTrunco);
        } else {
            $htmlCancel = 'No existe motivo';
            return utf8_decode($htmlTrunco);
        }
    }

    /*     * **************************************************TABPTR************************************************************* */

    public function getAllDataLogPTR() {

        try {

            $logPTR = $this->m_log_ingfix->getPTRMOVIMIENTO($this->session->flashdata('itemPlan'));
            $htmlPTRTRAMA = "";

            $htmlPTR = "";

            /*

              $logPTR= $this->m_log_ingfix->getListaLogPTR( $this->session->flashdata('itemPlan'));
              $logPTRTrama= $this->m_log_ingfix->getListaLogPTRTrama( $this->session->flashdata('itemPlan'));
              $logPTRWebUnificada= $this->m_log_ingfix->getListaLogWebUnificadaDet( $this->session->flashdata('itemPlan'));

              $html="";
              $htmlPTR="";
              $htmlPTRTRAMA="";
              $htmlPTRWUDET="";

              if(count($logPTRTrama->result()) >0){
              $htmlPTR = '<div>LOG TRAMA SIGOPLUS</div>
              <table id="data-tablePTRTrama" class="table table-bordered" style="font-size: smaller;">
              <thead class="thead-default">
              <tr>
              <th>ORIGEN</th>
              <th>PTR</th>
              <th>DESCRIPCION</th>
              <th>FECHA REGISTRO</th>
              </tr>
              </thead>
              <tbody>';
              foreach($logPTRTrama->result() as $row){
              $htmlPTR .='<tr>
              <td>'.$row->origen.'</td>
              <td>'.$row->ptr.'</td>
              <td>'.$row->descripcion.'</td>
              <td>'.$row->fecha_registro.'</td>
              </tr>';
              }
              $htmlPTR     .='</tbody>
              </table><br><br>';

              }

              if(count($logPTR->result()) >0){
              $htmlPTR = '<div>LOG PTR</div>
              <table id="data-tablePTR" class="table table-bordered" style="font-size: smaller;">
              <thead class="thead-default">
              <tr>
              <th>PTR</th>
              <th>USUARIO</th>
              <th>FECHA REGISTRO</th>

              </tr>
              </thead>
              <tbody>';
              foreach($logPTR->result() as $row){
              $htmlPTR .='<tr>
              <td>'.$row->ptr.'</td>
              <td>'.$row->usuario.'</td>
              <td>'.$row->fecha_registro.'</td>
              </tr>';
              }
              $htmlPTR     .='</tbody>
              </table><br><br>';

              }

              if(count($logPTRWebUnificada->result()) >0){

              $htmlPTRWUDET = '<div>LOG WEB UNIFICADA DET</div>
              <table id="data-tableWUDET" class="table table-bordered" style="font-size: smaller;">
              <thead class="thead-default">
              <tr>
              <th>ESTADO</th>
              <th>PTR</th>
              <th>USUARIO APROBADOR</th>
              <th>FECHA APROBACION</th>
              </tr>
              </thead>
              <tbody>';
              foreach($logPTRWebUnificada->result() as $row){
              $htmlPTRWUDET .='<tr>
              <td>'.$row->ESTADO.'</td>
              <td>'.$row->PTR.'</td>
              <td>'.$row->USUA_APROB.'</td>
              <td>'.$row->fec_aprob.'</td>
              </tr>';
              }
              $htmlPTRWUDET     .='</tbody>
              </table><br><br>';

              }

              $html=  $htmlPTRTRAMA.$htmlPTR.$htmlPTRWUDET;
             */

            $varaux = '';

            if (count($logPTR->result()) > 0) {
                $htmlPTR = '
                                <table id="data-tablePTR" class="table table-bordered" style="font-size: smaller;">
                                 <tbody>';
                foreach ($logPTR->result() as $row) {

                    if ($varaux == '') {
                        $varaux = $row->poCod;
                        $htmlPTR .= '<tr class="thead-default"><th colspan=3>' . $varaux . '</th></tr><tr class="thead-default"><th>ACCION</th><th>USUARIO</th><th>FECHA DE REGISTRO</th></tr>';
                    } else {
                        if ($varaux != $row->poCod) {
                            $varaux = $row->poCod;
                            $htmlPTR .= '<tr class="thead-default"><th colspan=3>' . $varaux . '</th></tr><tr class="thead-default"><th>ACCION</th><th>USUARIO</th><th>FECHA DE REGISTRO</th></tr>';
                        }
                    }

                    $htmlPTR .= '<tr>
                                   <td>' . $row->registro . '</td>
                                   <td>' . $row->usuario . '</td>
                                   <td>' . $row->fecharegistro . '</td>
                                   </tr>';
                }
                $htmlPTR .= '</tbody>
                         </table><br><br>';
            }

            $html = $htmlPTR;

            if ($html != '') {
                return utf8_decode($html);
            } else {
                $html = 'No cuenta con registro de PTR';
                return utf8_decode($html);
            }
        } catch (Exception $e) {
            throw new Exception('Hubo un error ejecutarse la funcion getAllDataLogPlanObra');
            return utf8_decode("null");
        }
    }

    public function getAllDataLogPorcentajeAvance() {

        try {

            $logPorcentajeAvance = $this->m_log_ingfix->getListaLogPorcentajeAvance2($this->session->flashdata('itemPlan'));

            $html = "";
            $htmlPorcentajeAvance = "";

            if (count($logPorcentajeAvance->result()) > 0) {
                $htmlPorcentajeAvance = '
                                <table id="data-tableAvance" class="table table-bordered" style="font-size: smaller;">
                                <thead class="thead-default">
                                    <tr>
                                        <th>ESTACION</th>
                                        <th>PORCENTAJE</th>
                                        <th>NOMBRE</th>
                                        <th>FECHA REGISTRO</th>
                                    </tr>
                                </thead>
                                <tbody>';
                foreach ($logPorcentajeAvance->result() as $row) {
                    $htmlPorcentajeAvance .= '<tr>
                                   <td>' . $row->estacionDesc . '</td>
                                   <td>' . $row->porcentaje . '</td>
                                   <td>' . $row->nombre . '</td>
                                   <td>' . $row->fecha_registro . '</td>
                                   </tr>';
                }
                $htmlPorcentajeAvance .= '</tbody>
                         </table><br><br>';
            }

            $html = $htmlPorcentajeAvance;

            if ($html != '') {
                return utf8_decode($html);
            } else {
                $html = 'No cuenta con porcentaje de avances';
                return utf8_decode($html);
            }

            return utf8_decode($html);
        } catch (Exception $e) {
            throw new Exception(' un error ejecutarse la funcion getAllDataLogPlanObra');
            return utf8_decode("null");
        }
    }

    /*     * ********************FIN LOG PLANOBRA**************************** */

    public function getHTMLProyectoConsulta() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $idplanta = $this->input->post('tipoplanta');
            $listaProy = $this->m_utils->getProyectoxTipoPlanta($idplanta);
            $html = '<option>&nbsp;</option>';
            foreach ($listaProy->result() as $row) {
                $html .= '<option value="' . $row->idproyecto . '">' . $row->proyectoDesc . '</option>';
            }
            $data['listaProyectos'] = $html;
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function getHTMLSubProyectoConsulta() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $idProyecto = $this->input->post('proyecto');

            $listaSubProy = $this->m_utils->getAllSubProyectoByProyecto($idProyecto);
            $html = '<option>&nbsp;</option>';
            foreach ($listaSubProy->result() as $row) {
                $html .= '<option value="' . $row->idSubProyecto . '">' . $row->subProyectoDesc . '</option>';
            }
            $data['listaSubProy'] = $html;
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function validaFechaTermino($fecha1) {
        $fecha2 = '2018-07-06';
        return (strtotime($fecha1) > strtotime($fecha2));
    }

    public function sort_by_orden($a, $b) {
        return $a['orden'] - $b['orden'];
    }

    function getDataSiom() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            $itemplan = $this->input->post('itemplan');

            if ($itemplan == null) {
                throw new Exception("ERROR ITEMPLAN");
            }
            $tablaSiom = $this->getTablaConsultaSiom($itemplan);
            $data['error'] = EXIT_SUCCESS;
            $data['tablaSiom'] = $tablaSiom;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function getTablaConsultaSiom($itemplan) {
        $array = $this->m_utils->getDataSiomAll($itemplan);
        $cont = 0;

        if ($this->session->userdata('eeccSession') == 6 || $this->session->userdata('eeccSession') == 0) {
            $disabled = NULL;
        } else {
            $disabled = 'disabled';
        }
        /*
          $html = '<table id="data-table" class="table table-bordered container">
          <thead class="thead-default">
          <tr>
          <th>ITEMPLAN</th>
          <th>ESTACI&Oacute;N</th>
          <th>C&Oacute;DIGO SIOM</th>
          <th>ACTUALIZAR</th>
          </tr>
          </thead>

          <tbody>';
          foreach($array as $row){
          $cont++;
          $html .=' <tr>
          <td>'.$row->itemplan.'</td>
          <td>'.$row->estacionDesc.'</td>
          <td><input id="inputCodigoSiom_'.$cont.'" type="text" class="form-control" value="'.$row->codigoSiom.'"/></td>
          <td><button class="btn btn-success" data-itemplan="'.$row->itemplan.'" data-codigo_siom="'.$row->codigoSiom.'"
          data-id_estacion="'.$row->idEstacion.'" onclick="actualizarCodigoSiom($(this),'.$cont.')" '.$disabled.'>Aceptar</button></td>
          </tr>';
          } */
        $html = '<table id="data-table" class="table table-bordered container">
                        <thead class="thead-default">
                            <tr>
                                <th>ITEMPLAN</th>
                                <th>ESTACI&Oacute;N</th>                            
                                <th>C&Oacute;DIGO SIOM</th>
                                <th>ULTIMO ESTADO</th>
                                <th>FEC. ULTIMO ESTADO</th>
                            </tr>
                        </thead>
                        
                        <tbody>';
        foreach ($array as $row) {
            $cont++;
            $html .= ' <tr>
                            <td>' . $row->itemplan . '</td>      
                            <td>' . $row->estacionDesc . '</td>  
                            <td>' . $row->codigoSiom . '</td>
                            <td>' . $row->ultimo_estado . '</td>  
                            <td>' . $row->fecha_ultimo_estado . '</td>
                         </tr>';
        }

        $html .= '</tbody>
                </table>';
        return utf8_decode($html);
    }

    function hasDisenoAdjudicado() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $itemplan = $this->input->post('itemplan');
            $hasAdju = $this->m_utils->hasDisenoAdjudicado($itemplan);
            $data['hasAdju'] = $hasAdju;
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function getDataSisego() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $itemplan = $this->input->post('itemplan');

            if ($itemplan == null) {
                throw new Exception('itemplan null, comunicarse con el programador');
            }

            $arrayPlanObra = $this->m_utils->getInfoItemplan($itemplan);
            $html .= '<div class="row form-group">
                        <div class="col-md-6">
                            <label>OPERADOR: </label>
                            <label style="color:blue">' . strtoupper($arrayPlanObra['operador']) . '</label>
                        </div>
                        <div class="col-md-6">
                            <label>TIPO DISE&Ntilde;O:</label>
                            <label style="color:blue">' . strtoupper($arrayPlanObra['tipo_diseno']) . '</label>
                        </div>
                      </div>
                      <div class="row form-group">
                        <div class="col-md-6">
                            <label>NOMBRE ESTUDIO: </label>
                            <label style="color:blue">' . strtoupper($arrayPlanObra['nombre_estudio']) . '</label>
                        </div>
                        <div class="col-md-6">
                            <label>DURACI&Oacute;N: </label>
                            <label style="color:blue">' . strtoupper($arrayPlanObra['duracion']) . '</label>
                        </div>
                      </div>
                     
                      <div class="row form-group">
                        <div class="col-md-6">
                            <label>ACCESO CLIENTE: </label>
                            <label style="color:blue">' . strtoupper($arrayPlanObra['acceso_cliente']) . '</label>
                        </div>
                        <div class="col-md-6">
                            <label>TENDIDO EXTERNO: </label>
                            <label style="color:blue">' . strtoupper($arrayPlanObra['tendido_externo']) . '</label>
                        </div>
                      </div>
                      <div class="row form-group">
                         <div class="col-md-6">
                            <label>TIPO CLIENTE: </label>
                            <label style="color:blue">' . strtoupper($arrayPlanObra['tipo_cliente']) . '</label>
                        </div>
                      </div>';
            $data['dataInfoSisego'] = $html;
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function actualizarCodigoSiom() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $itemplan = $this->input->post('itemplan');
            $idEstacion = $this->input->post('idEstacion');
            $codigoSiom = $this->input->post('codigoSiom');

            $this->db->trans_begin();
            if ($itemplan == null) {
                throw new Exception('itemplan null, comunicarse con el programador');
            }

            if ($idEstacion == null) {
                throw new Exception('idEstacion null, comunicarse con el programador');
            }

            if ($codigoSiom == null) {
                throw new Exception('codigoSiom null, comunicarse con el programador');
            }

            $data = $this->m_consulta->actualizarCodigoSiom($itemplan, $idEstacion, $codigoSiom);
            $arrayDataLog = array(
                'tabla' => 'siom_obra',
                'actividad' => 'Actualizar Codigo Siom',
                'itemplan' => $itemplan,
                'fecha_registro' => $this->fechaActual(),
                'id_usuario' => $this->session->userdata('idPersonaSession'),
                'codigo_siom' => $codigoSiom
            );

            $countRows = $this->m_utils->registrarLogPlanObra($arrayDataLog);
            if ($countRows == 0) {
                throw new Exception('error no se registro el log');
            }

            $this->db->trans_commit();
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
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

    public function getAllDataLogPlanObra2() {

        try {

            $logCreacionIP = $this->m_log_ingfix->getLogCreacionIP($this->session->flashdata('itemPlan'));
            $logAdjudicacion = $this->m_log_ingfix->getAdjuLog($this->session->flashdata('itemPlan'));
            $logEjecDiseno = $this->m_log_ingfix->getDisenoEjectLog($this->session->flashdata('itemPlan'));
            $logDisenoParcial = $this->m_log_ingfix->getDisenoParcialLog2($this->session->flashdata('itemPlan'));
            $logEnObra = $this->m_log_ingfix->getEnObraLog($this->session->flashdata('itemPlan'));
            $logPreLiquidacion = $this->m_log_ingfix->getPreLiquidacionLog2($this->session->flashdata('itemPlan'));
            $logTerminado = $this->m_log_ingfix->getTerminadoLog2($this->session->flashdata('itemPlan'));

            $logTrunco = $this->m_log_ingfix->getTruncoLog($this->session->flashdata('itemPlan'));
            $logCancelado = $this->m_log_ingfix->getCanceladoLog($this->session->flashdata('itemPlan'));
            $logCerrado = $this->m_log_ingfix->getCerradoLog($this->session->flashdata('itemPlan'));

            $logFichaTecnicaReg = $this->m_log_ingfix->getFichaTecnicaLogReg($this->session->flashdata('itemPlan'));
            $logFichaTecnicaVal = $this->m_log_ingfix->getFichaTecnicaLogVal($this->session->flashdata('itemPlan'));

            $logCertificacion = $this->m_log_ingfix->getCertificacionLog($this->session->flashdata('itemPlan'));

            $htmlCabcer = '
                             <table id="data-tablePO"  class="table table-bordered"  style="font-size: smaller;">
                                <thead class="thead-default">
                                    <tr>
                                       <th onclick="w3.sortHTML(' . "'" . '#data-tablePO' . "'" . ', ' . "'" . '.item' . "'" . ', ' . "'" . 'td:nth-child(3)' . "'" . ')" style="cursor:pointer">ACCION <i class="zmdi zmdi-unfold-more"></i></th>
                                       <th>USUARIO</th>
                                       <th onclick="w3.sortHTML(' . "'" . '#data-tablePO' . "'" . ', ' . "'" . '.item' . "'" . ', ' . "'" . 'td:nth-child(1)' . "'" . ')" style="cursor:pointer">FECHA REGISTRO <i class="zmdi zmdi-unfold-more"></i></th>
                                       <th>OBSERVACIONES</th>
                                    </tr>
                                </thead>
                                <tbody>';

            //NUEVAS VARIABLES

            $htmlCreacionIP = "";
            $htmlAjud = "";
            $htmlDisenoEjecu = "";
            $htmlDisenoParcial = "";
            $htmlEnObra = "";
            $htmlPreLiquida = "";
            $htmlTerminado = "";
            $htmlTrunco = "";
            $htmlCancelado = "";
            $htmlCerrado = "";
            $htmlFichaTecnicaReg = "";
            $htmlFichaTecnicaVal = "";
            $htmlCertificacion = "";


            if (count($logCreacionIP) > 0) {

                foreach ($logCreacionIP as $row) {
                    $htmlCreacionIP .= '<tr class="item">
                                        <td>CREACION</td>
                                        <td>' . $row->usuario . '</td>
                                        <td>' . $row->fecha_registro . '</td>
                                        <td></td>
                                 </tr>';
                }
            }

            if (count($logAdjudicacion) > 0) {

                foreach ($logAdjudicacion as $row) {
                    $htmlAjud .= '<tr class="item">
                                        <td>ADJUDICACION</td>
                                        <td>' . $row->usuario_adjudicacion . '</td>
                                        <td>' . $row->fecha_adjudicacion . '</td>
                                        <td> ESTACION ' . $row->estacionDesc . ' </td>
                                 </tr>';
                }
            }

            if (count($logEjecDiseno) > 0) {

                foreach ($logEjecDiseno as $row) {
                    $htmlDisenoEjecu .= '<tr class="item">
                                            <td>EJECUCION</td>
                                            <td>' . $row->usuario_ejecucion . '</td>
                                            <td>' . $row->fecha_ejecucion . '</td>
                                            <td>ESTACION ' . $row->estacionDesc . (($row->path_expediente_diseno != null) ?
                            '<br>EXPEDIENTE     <a href="' . $row->path_expediente_diseno . '" download=""><i class="zmdi zmdi-hc-2x zmdi-download"></i></a>' : '') . '</td>
                                        </tr>';
                }
            }

            if (count($logDisenoParcial) > 0) {

                foreach ($logDisenoParcial as $row) {
                    $arrayDescrip = explode('|', $row->itemplan_default);

                    $htmlDisenoParcial .= '<tr class="item">
                                   <td>DISENO PARCIAL</td>
                                   <td>' . $row->usuario . '</td>
                                   <td>' . $row->fecha_registro . '</td>
                                   <td>' . (count($arrayDescrip) > 0 ? $arrayDescrip[1] : '') . '</td>
                                   </tr>';
                }
            }

            if (count($logEnObra->result()) > 0) {

                foreach ($logEnObra->result() as $row) {
                    $htmlEnObra .= '<tr class="item">
                                   <td>' . $row->registro . '</td>
                                   <td>' . $row->nombre . '</td>
                                   <td>' . $row->fecha_registro . '</td>
                                   <td> </td>
                                   </tr>';
                }
            }

            if (count($logPreLiquidacion->result()) > 0) {

                foreach ($logPreLiquidacion->result() as $row) {
                    $fecha = $row->fecha_registro;
                    $htmlPreLiquida .= '<tr class="item">
                                <td>LIQUIDACION</td>
                                <td>' . $row->usuario . '</td>
                                <td>' . $row->fecha_registro . '</td>
                                <td> </td>
                                </tr>';
                }
            }

            if (count($logTerminado->result()) > 0) {

                foreach ($logTerminado->result() as $row) {
                    $htmlTerminado .= '<tr class="item">
                                   <td>' . $row->registro . '</td>
                                   <td>' . $row->estado . '</td>
                                   <td>' . $row->nombre . '</td>
                                   <td>' . $row->fecha_registro . '</td>
                                   <td> </td>
                                   </tr>';
                }
            }

            if (count($logTrunco->result()) > 0) {

                foreach ($logTrunco->result() as $row) {

                    $logMotivoTrunco = $this->m_log_ingfix->getMotivoTruncoCanceladoLog($this->session->flashdata('itemPlan'), $row->fecha_registro, 10);
                    $boton = '';
                    if (count($logMotivoTrunco->result()) > 0) {
                        $boton = '<button data-fechaT=' . $row->fecha_registro . ' data-itemT=' . $this->session->flashdata('itemPlan') . ' style="color: white;background-color: var(--verde_telefonica)" class="btn btn-secondary waves-effect" data-toggle="modal" onclick="verMotivoTrunco(this)">Ver Motivo</button>';
                    }

                    $htmlTrunco .= '<tr class="item">
                                   <td>OBRA TRUNCA</td>
                                   <td>' . str_replace("|", "<br>", $row->accion) . '</td>
                                   <td>' . $row->nombre . '</td>
                                   <td>' . $row->fecha_registro . '</td>
                                    <td>' . $boton . ' </td>
                                   </tr>';
                }
            }

            if (count($logCancelado->result()) > 0) {

                foreach ($logCancelado->result() as $row) {

                    $logMotivoCancelado = $this->m_log_ingfix->getMotivoTruncoCanceladoLog($this->session->flashdata('itemPlan'), $row->fecha_registro, 6);
                    $boton = '';
                    if (count($logMotivoCancelado->result()) > 0) {
                        $boton = '<button data-fechaC=' . $row->fecha_registro . ' data-itemC=' . $this->session->flashdata('itemPlan') . ' style="color: white;background-color: var(--verde_telefonica);" class="btn btn-secondary waves-effect" data-toggle="modal" onclick="verMotivoCancelar(this)">Ver Motivo</button>';
                    }

                    $htmlCancelado .= '<tr class="item">
                                   <td>OBRA CANCELADA</td>
                                   <td>' . $row->estado . '</td>
                                   <td>' . $row->nombre . '</td>
                                   <td>' . $row->fecha_registro . '</td>
                                   <td>' . $boton . ' </td>
                                   </tr>';
                }
            }

            if (count($logCerrado->result()) > 0) {

                foreach ($logCerrado->result() as $row) {
                    $htmlCerrado .= '<tr class="item">
                                   <td>' . $row->registro . '</td>
                                   <td>' . $row->estado . '</td>
                                   <td>' . $row->nombre . '</td>
                                   <td>' . $row->fecha_registro . '</td>
                                   <td> </td>
                                   </tr>';
                }
            }

            if (count($logFichaTecnicaReg->result()) > 0) {

                foreach ($logFichaTecnicaReg->result() as $row) {
                    $htmlFichaTecnicaReg .= '<tr class="item">
                                   <td>' . $row->registro . '</td>
                                   <td>' . $row->usuario . '</td>
                                   <td>' . $row->fecha_registro . '</td>
                                   <td> </td>
                                   </tr>';
                }
            }

            if (count($logFichaTecnicaVal->result()) > 0) {

                foreach ($logFichaTecnicaVal->result() as $row) {
                    $htmlFichaTecnicaVal .= '<tr class="item">
                                   <td>' . $row->validacion . '</td>
                                   <td>' . $row->usuario . '</td>
                                   <td>' . $row->fecha_validacion . '</td>
                                   <td> </td>
                                   </tr>';
                }
            }

            if (count($logCertificacion->result()) > 0) {

                foreach ($logCertificacion->result() as $row) {
                    $htmlCertificacion .= '<tr class="item">
                                   <td>' . $row->accion . '</td>
                                   <td>' . $row->usuario . '</td>
                                   <td>' . $row->fecha_valida . '</td>
                                   <td>' . $row->observacion . '</td>
                                   </tr>';
                }
            }


            $html = trim($htmlCabcer . $htmlCreacionIP . $htmlAjud . $htmlDisenoEjecu . $htmlDisenoParcial . $htmlEnObra .
                    $htmlPreLiquida . $htmlTerminado . $htmlTrunco . $htmlCancelado . $htmlCerrado . $htmlFichaTecnicaReg .
                    $htmlFichaTecnicaVal . $htmlCertificacion . '</tbody></table><br><br>');

            return utf8_decode($html);
        } catch (Exception $e) {
            throw new Exception('Hubo un error ejecutarse la funcion getAllDataLogPlanObra');
            return utf8_decode("null");
        }
    }

    public function detenerItemplan() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;

            $itemplan = $this->input->post('hfItemPlan');
            $comentario = $this->input->post('txtComentario');
            $accionDetener = $this->input->post('hfAccionDeDetener');
            $selectMotivo = $this->input->post('selectMotivo');

            $fileName = '';
            $archivoFinal = '';
            if (!empty($_FILES["fEvidenciaDetener"]["name"])) {
                $file = $_FILES["fEvidenciaDetener"]["name"];
                $archivo = $_FILES["fEvidenciaDetener"]["tmp_name"];

                $fileName = basename($_FILES["fEvidenciaDetener"]["name"]);
                //ALMACENAR EN UNA RUTA Y LUEGO GUARDAR SU RUTA.
                $ubicacion = 'uploads/detener_plan_obra/' . $this->getFechaISO() . '_' . $accionDetener . '_' . $itemplan;
                if (!is_dir($ubicacion)) {
                    mkdir($ubicacion, 0777);
                }
                $archivoFinal = $ubicacion . "/" . $fileName;
                if ($file != null) {
                    move_uploaded_file($archivo, $ubicacion . "/" . $fileName);
                }
            }

            $idEstadoADetener = '';
            $arrayUpdate = null;
            if ($accionDetener == "suspender") {
                $idEstadoADetener = ID_ESTADO_SUSPENDIDO;

                $arrayUpdate = array(
                    "descripcion" => $comentario,
                    "idMotivo" => $selectMotivo,
                    "ruta_archivo" => $archivoFinal,
                    "idEstadoPlan" => $idEstadoADetener,
                    "usu_upd" => $idUsuario,
                    "fecha_upd" => $this->fechaActual()
                );
            } else if ($accionDetener == "cancelar") {
                $idEstadoADetener = ID_ESTADO_CANCELADO;

                $arrayUpdate = array(
                    "descripcion" => $comentario,
                    "idMotivo" => $selectMotivo,
                    "ruta_archivo" => $archivoFinal,
                    "idEstadoPlan" => $idEstadoADetener,
                    "usu_upd" => $idUsuario,
                    "fecha_upd" => $this->fechaActual(),
                    "fechaCancelacion" => $this->fechaActual()
                );

                $idProyecto = $this->m_utils->getProyectoByItemplan($itemplan);

                if ($idProyecto == ID_PROYECTO_SISEGOS) {
                    $url = 'https://172.30.5.10:8080/obras2/recibir_can.php';

                    $dataSend = ['itemplan' => $itemplan,
                        'estado' => FLG_CANCELACION_CONFIRMADA];

                    $data = _trama_sisego($dataSend, $url, 10, $itemplan, 'CANCELACION CONSULTA', NULL);
                }
				
				$this->m_utils->generarSolicitudCertiAnulEdiOC($itemplan, 'PLAN', 4, null);//CREAMOS SOLICITUD DE ANULACION
				$this->getLogicaParalizado($itemplan);
            } else if ($accionDetener == "truncar") {
                $idEstadoADetener = ID_ESTADO_TRUNCO;

                $arrayUpdate = array(
                    "descripcion" => $comentario,
                    "idMotivo" => $selectMotivo,
                    "ruta_archivo" => $archivoFinal,
                    "idEstadoPlan" => $idEstadoADetener,
                    "usu_upd" => $idUsuario,
                    "fecha_upd" => $this->fechaActual(),
                    "fechaTrunca" => $this->fechaActual()
                );
				
				$this->m_utils->generarSolicitudCertiAnulEdiOC($itemplan, 'PLAN', 4, null);//CREAMOS SOLICITUD DE ANULACION
				$this->getLogicaParalizado($itemplan);
            }
			
            $data = $this->m_pqt_planobra->updDetenerPlanObra($itemplan, $arrayUpdate);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            log_message('error', 'Error: ' . $e->getMessage());
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
	
	function getLogicaParalizado($itemplan) {
		$flg_paralizado = $this->m_utils->countParalizados($itemplan, 1, NULL);
		$data['flg_des_paralizado'] = 0;		
		if($flg_paralizado > 0) {
			$data['flg_des_paralizado'] = 1;
			$arrayDataItem = array('has_paralizado' => null,
									'fecha_paralizado'  => null,
									'motivo_paralizado' =>  null,
									'fecha_reactiva_paralizado' =>  $this->m_utils->fechaActual()
								  );
			
			$data = $this->m_utils->simpleUpdatePlanObra($itemplan, $arrayDataItem);

			$dataArray = array('flg_activo'        => FLG_INACTIVO,
							   'fechaReactivacion' => $this->m_utils->fechaActual(),
							   'idUsuarioReac'     => 1645);

			$data = $this->m_utils->updateFlgParalizacion($itemplan, FLG_ACTIVO, $dataArray);
			
			$data['flg_des_paralizado'] = 1;
		}
		
		return $data;
	}

    function getFechaISO() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone', 'America/Lima');
        setlocale(LC_TIME, "es_ES", "esp");
        $hoy = strftime("%Y%m%d%H%M%S");
        return $hoy;
    }

    public function reanudarItemplan() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;

            $itemplan = $this->input->post('hfItemPlanR');
            $comentario = $this->input->post('txtComentarioR');
            $accionDetener = $this->input->post('hfAccionDeReanudar');

            $fileName = '';
            $archivoFinal = '';
            if (!empty($_FILES["fEvidenciaReanudar"]["name"])) {
                $file = $_FILES["fEvidenciaReanudar"]["name"];
                $archivo = $_FILES["fEvidenciaReanudar"]["tmp_name"];

                $fileName = basename($_FILES["fEvidenciaReanudar"]["name"]);
                //ALMACENAR EN UNA RUTA Y LUEGO GUARDAR SU RUTA.
                $ubicacion = 'uploads/detener_plan_obra/' . $this->getFechaISO() . '_' . $accionDetener . '_' . $itemplan;
                if (!is_dir($ubicacion)) {
                    mkdir($ubicacion, 0777);
                }
                $archivoFinal = $ubicacion . "/" . $fileName;
                if ($file != null) {
                    move_uploaded_file($archivo, $ubicacion . "/" . $fileName);
                }
            }

            $procederConActualizacion = 0;

            //OBTENER EL ESTADO ANTERIOR
            $idEstadoAnteriorAReanudar = null;
            $idEstadoActual = null;
            foreach ($this->m_pqt_planobra->obtEstadoAnterior($itemplan)->result() as $row) {
                $idEstadoAnteriorAReanudar = $row->idEstadoPlanAnt;
                $idEstadoActual = $row->idEstadoPlan;
            }

            if ($accionDetener == "reanudar-suspendido") {
                if ($idEstadoActual == null && $idEstadoAnteriorAReanudar == null) {
                    $data['error'] = EXIT_ERROR;
                    $data['msj'] = 'Hubo un error al obtener los datos. Comunicarse con el area de TI';
                    $procederConActualizacion = 0;
                } else if ($idEstadoActual != ID_ESTADO_SUSPENDIDO) {
                    $data['error'] = EXIT_ERROR;
                    $data['msj'] = 'El estado actual no es el estado suspendido. Comunicarse con el area de TI';
                    $procederConActualizacion = 0;
                } else {
                    $procederConActualizacion = 1;
                }
            } else if ($accionDetener == "reanudar-trunco") {
                if ($idEstadoActual == null && $idEstadoAnteriorAReanudar == null) {
                    $data['error'] = EXIT_ERROR;
                    $data['msj'] = 'Hubo un error al obtener los datos. Comunicarse con el area de TI';
                    $procederConActualizacion = 0;
                } else if ($idEstadoActual != ID_ESTADO_TRUNCO) {
                    $data['error'] = EXIT_ERROR;
                    $data['msj'] = 'El estado actual no es el estado trunco. Comunicarse con el area de TI';
                    $procederConActualizacion = 0;
                } else {
                    $procederConActualizacion = 1;
                }
            }

            if ($procederConActualizacion == 1) {
                $arrayUpdate = array(
                    "descripcion" => $comentario,
                    "ruta_archivo" => $archivoFinal,
                    "idEstadoPlan" => $idEstadoAnteriorAReanudar,
                    "usu_upd" => $idUsuario,
                    "fecha_upd" => $this->fechaActual()
                );
                $data = $this->m_pqt_planobra->updDetenerPlanObra($itemplan, $arrayUpdate);
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            log_message('error', 'Error: ' . $e->getMessage());
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function validarItemplanPerteneceAPaquetizado() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            #_log("ENTRO AL LOG");
            $itemPlan = $this->input->post('itemplan');
            $indicador = $this->input->post('nombreproyecto');
            $itemMadre = $this->input->post('itemMadre');

            if (!$itemPlan) {
                $itemPlan = null;
            }

            if (!$indicador) {
                $indicador = null;
            }

            if (!$itemMadre) {
                $itemMadre = null;
            }

            #_log("ENTRO - SISEGO :".$indicador);
            $dato = $this->m_pqt_consulta->isContratoPaquetizadoItemPlan($itemPlan, $indicador, $itemMadre);
            //SI EL VALOR DE COUNT ES 0 --> PERMITIR CONSULTAR
            //SI EL VALOR DE COUNT ES MAYOR IGUAL A 1 --> NO PERMITIR CONSULTAR
            $data['permitir'] = $dato['count'];
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
	
	function openModalLogOc() {
		$itemplan = $this->input->post('itemplan');
		$tablaLogOc = $this->getTablaLogOc($itemplan);
		
		$data['tbLogOc'] = $tablaLogOc;
		
		echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getTablaLogOc($itemplan) {
		$arrayData = $this->m_utils->getLogSolicitudOc($itemplan);
		
		$html = '
                <table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>ITEMPLAN</th>
                            <th>COD SOLICITUD</th>
							<th>TIPO SOLICITUD</th>
                            <th>ESTADO</th>
                            <th>FECHA REGISTRO</th>
                            <th>FECHA VALIDA</th>
                            <th>USUARIO VALIDA</th>	
							<th>MONTO</th>							
                        </tr>
                    </thead>

                    <tbody>';
					
			foreach ($arrayData as $row){
			$html .= '
                        <tr>
                            <td>' . $row['itemplan']. '</td>
                            <td>' . $row['codigo_solicitud']. '</td>
                            <th>' . $row['tipo_solicitud']. '</th>
                            <th>' . $row['estado']. '</th>
							<th>' . $row['fecha_creacion']. '</th>
							<th>' . $row['fecha_valida']. '</th>
							<th>' . $row['usuario_valida']. '</th>
							<th>' . $row['costo_unitario_mo']. '</th>
                        </tr>
                        ';
            }
            $html .= '</tbody>
                </table>';
				
			return utf8_decode($html);
	}
	
	function delOtActualizacion() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            $itemplan = $this->input->post('itemplan');
            $idUsuario = $this->session->userdata('idPersonaSession');
            if($idUsuario == null){
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }
            if ($itemplan == null) {
                throw new Exception("ERROR ITEMPLAN NO DETECTADO");
            }
            $log_planobra = array(  'tabla'  => 'log_tramas_sirope',
                                    'actividad' => 'elimnar ot actualizacion',
                                    'itemplan' => $itemplan,
                                    'fecha_registro' => $this->fechaActual(),
                                    'id_usuario' => $idUsuario);
            $data = $this->m_utils->removeOTAC($itemplan, $itemplan.'AC', $log_planobra);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
	
	function actualizarPo() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
			$this->db->trans_begin();
			
			$flg_preliquidado = null;
			
            $costoMO         = $this->input->post('costoMO');
            $total           = $this->input->post('total');
            $cantidadFinal   = $this->input->post('cantidadFinal');
            $ptr             = $this->input->post('ptr');
            $itemplan        = $this->input->post('itemplan');
            $arrayData       = $this->input->post('arrayData');
            $arrayDataInsert = $this->input->post('arrayDataInsert');
            $idEstadoPlan    = $this->input->post('idEstadoPlan');

            if($cantidadFinal == null || $cantidadFinal == '') {
                throw new Exception('debe Ingresar la cantidad');
            }
			
			$idUsuario = $this->session->userdata('idPersonaSession');
			
            // $arrayData = array(
            //     'costo_mat'      => $costoMA,
            //     'costo_mo'       => $costoMO,
            //     'total'          => $total,
            //     'cantidad_final' => $cantidadFinal
            // );

			if($arrayDataInsert) {
				if(count($arrayDataInsert) > 0) {
					$val1 = $this->m_utils->insertEditPo($arrayDataInsert);

					if($val1['error'] == EXIT_SUCCESS) {
						$data['error'] = EXIT_SUCCESS;
						$arrayDataLog = array(
							'tabla'            => 'liquidacion de itemplan',
							'actividad'        => 'PO insertando',
							'itemplan'         => $itemplan,
							'fecha_registro'   => $this->fechaActual(),
							'id_usuario'       => $this->session->userdata('idPersonaSession')
						 );

						$this->m_utils->registrarLogPlanObra($arrayDataLog);
					}
				}
			}
			
			if($arrayData) {
				if(count($arrayData) > 0) {		
					$val = $this->m_utils->insertEditPo($arrayData);

					if($val['error'] == EXIT_SUCCESS) {
						$data['error'] = EXIT_SUCCESS;
						$arrayDataLog = array(
							'tabla'            => 'liquidacion de itemplan',
							'actividad'        => 'PO editado existente',
							'itemplan'         => $itemplan,
							'fecha_registro'   => $this->fechaActual(),
							'id_usuario'       => $this->session->userdata('idPersonaSession')
						 );

						$this->m_utils->registrarLogPlanObra($arrayDataLog);
					} else {
						$data['error'] = EXIT_ERROR; 
						throw new Exception('No ingreso el costo correctamente');
					}
				}
			}
			
            
            $tablaConsultaPtr = $this->getTablaConsultaPTR($itemplan, $idEstadoPlan);
            $data['tablaConsultaPtr'] = $tablaConsultaPtr;
			
			$dataItemplanEstacionAvance = $this->m_utils->getPorcentajeAvanceByItemplanEstacion($itemplan, NULL);
			
			if($dataItemplanEstacionAvance['porcentaje'] == 100 && $idEstadoPlan == 3) {
				$arrayData = array(
										'usu_upd'      => $idUsuario,
										'fecha_upd'    => $this->fechaActual(),
										'descripcion'  => 'SE CORRIGE Y SE PRELIQUIDA',
										'idEstadoPlan' => 9
									);
				$data = $this->m_utils->simpleUpdatePlanObra($itemplan, $arrayData);
				
				if($data['error'] == EXIT_ERROR) {
					throw new Exception($data['msj']);
				}
				
				$flg_preliquidado = 1;
			} else {
				$arrayData = array(
										'flg_edit_po' => 1
									);
				$data = $this->m_utils->simpleUpdatePlanObra($itemplan, $arrayData);
			}
			
			if($data['error'] == EXIT_ERROR) {
				throw new Exception($data['msj']);
			}
				
			$arrayDataInsert = array(
										'itemplan'       => $itemplan,
										'codigo_po'      => $ptr,
										'id_usuario'     => $idUsuario,
										'fecha_registro' => $this->fechaActual(),
										'descripcion'    => 'SE EDITO LA PO',
										'flg_editar'     => 1
									);
			$data = $this->m_utils->insertLogEditPo($arrayDataInsert);
			
			if($data['error'] == EXIT_ERROR) {
				throw new Exception($data['msj']);
			}
			
			$data['flg_preliquidado'] = $flg_preliquidado;
			$this->db->trans_commit();
        } catch(Exception $e) {
			$this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }  
        echo json_encode(array_map('utf8_encode', $data));
    }
	
	function getNoEditPo() {
		$data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
			$itemplan = $this->input->post('itemplan');
			$ptr      = $this->input->post('codigo_po');
			$idUsuario = $this->session->userdata('idPersonaSession');
			
			$arrayData = array(
									'flg_edit_po' => 1
								);
			$data = $this->m_utils->simpleUpdatePlanObra($itemplan, $arrayData);
			
			if($data['error'] == EXIT_ERROR) {
				throw new Exception($data['msj']);
			}
			
			$arrayDataInsert = array(
										'itemplan'       => $itemplan,
										'codigo_po'      => $ptr,
										'id_usuario'     => $idUsuario,
										'fecha_registro' => $this->fechaActual(),
										'descripcion'    => 'NO EDITO LA PO',
										'flg_editar'     => 2
									);
			$data = $this->m_utils->insertLogEditPo($arrayDataInsert);
			
			if($data['error'] == EXIT_ERROR) {
				throw new Exception($data['msj']);
			}
		} catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }  
        echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getTablaConsultaPTR($itemplan, $idEstadoPlan) {
        $tb = null;
        $arrayPtr = $this->M_porcentaje->getPtrByItemplan($itemplan);
        $tb .= '<table class="table">
                    <thead>
                        <th>PTR</th>
						<th>Total Inicial</th>
                        <th>Total Final</th>
                        <th>Acci&oacute;n</th>
                    <thead>
                    <tbody>';
        foreach($arrayPtr as $row) {
            //if($idEstadoPlan == ID_ESTADO_TERMINADO) {
              //  $btnEditar = null;
            //} else {
                $btnEditar = '<input type="button" class="btn-danger" data-costo_mo="'.$row->total.'" 
								data-ptr="'.$row->ptr.'" data-itemplan="'.$itemplan.'" value="editar" onclick="openModalEditarPTR($(this));"/>';                
            //}
            $tb .= '<tr>
                        <td>'.$row->ptr.'</td>
						<td>'.$row->total_anterior.'</td>
                        <td>'.$row->total.'</td>
                        <td>'.$btnEditar.'</td>
                    </tr>';
        }
            $tb .= '    </tbody>
                    </table>';
        return $tb;        
    }
	
	function getDetallePoEdit() {
        $itemplan      = $this->input->post('itemplan');
        $ptr           = $this->input->post('ptr');
        $idSubProyecto = $this->input->post('idSubProyecto');
        $cont = 0;
        $arrayData = $this->m_utils->getDataPoTransp($itemplan, $ptr);
        $totalInicial = 0;
		$totalFinal   = 0;
		$idContrato = null;
        $html = '<table id="tablaPtr" class="table">
                    <thead class="thead-default">
                        <tr>
							<th>Codigo</th>
                            <th>Actividad</th>
                            <th>Precio</th>
                            <th>Baremo</th>                            
                            <th style="background:#E9C603;color:white">Cantidad Inicial</th>
                            <th style="background:#E9C603;color:white">Cantidad Final</th>
                            <th>Total Inicial</th> 
							<th>Total Final</th> 							
                        </tr>
                    </thead>                    
                    <tbody>';
        foreach($arrayData as $row) {
			$idContrato = $row->idContrato;
			$totalInicial = $row->total + $totalInicial;
			if($row->total_editado == null) {
				$totalFinal   = $row->total + $totalFinal;
			} else {
				$totalFinal   = $row->total_editado + $totalFinal;
			}
			
			
            $cont++;
            $html.= '<tr id="'.$cont.'">
						<td>'.$row->codigo.'</td>
                        <td>'.utf8_decode($row->descripcion).'</td>
                        <td id="precio_'.$cont.'">'.$row->precio.'</td>
                        <td id="baremo_'.$cont.'">'.$row->baremo.'</td>
                        <td style="background:#E9C603;color:white"><input id="cantidad_in_'.$cont.'" class="form-control" value="'.$row->cantidad.'" disabled></td>
                        <td style="background:#E9C603;color:white"><input id="cantidad_'.$cont.'" type="number" data-descripcion="'.utf8_decode($row->descripcion).'" data-cont="'.$cont.'" data-id_actividad="'.$row->id_actividad.'" data-id_ptrxactividad_zonal="'.$row->id_ptr_x_actividades_x_zonal.'" class="form-control" value="'.$row->cantidad_editado.'" onchange="calculoCantidad($(this));"></td>
                        <td id="costoTotal_'.$cont.'">'.$row->total.'</td>
						<td id="costoTotal_edit_'.$cont.'">'.$row->total_editado.'</td>
                    </tr>';
        }
        $html .=' 
					</tbody>
					<tfoot>
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td id="costoTotalInicial">'.$totalInicial.'</td>
							<td><input id="costoTotalFinal" value="'.$totalFinal.'" disabled/></td>
					  </tr>					
					</tfoot>
            </table>';
        $tbActividad = $this->getTablaActividadesxSubProyecto($idSubProyecto, $ptr, $itemplan, $idContrato);

        $data['tablaActividad'] = $tbActividad;    
        $data['tablaEditarPtr'] = $html;      
        echo json_encode(array_map('utf8_encode', $data));    
    }
	
	function getTablaActividadesxSubProyecto($idSubProyecto, $ptr, $itemplan, $idContrato) {
        $tb = null;
        $array = $this->m_utils->getPartidasByProyectoEstacion($itemplan, null, $idContrato);
        $tb .= '<table id="tablaActividad" class="table">
                    <thead>
						<th>Codigo</th>
                        <th>Actividad</th>
                        <th>Baremo</th>
                        <th>costo</th>
                        <th>Acci&oacute;n</th>
                    <thead>
                    <tbody>';
        foreach($array as $row) {
            $tb .= '<tr>
						<td>'.$row->codigo.'</td>
                        <td>'.$row->descripcion.'</td>
                        <td>'.$row->baremo.'</td>
                        <td>'.$row->costo.'</td>
                        <td><input type="button" class="btn-info" data-codigo="'.$row->codigo.'" data-descripcion="'.$row->descripcion.'" data-id_actividad="'.$row->idActividad.'" data-baremo="'.$row->baremo.'"
                         data-costo_kit="'.$row->costo.'" 
                         value="Agregar" onclick="addActividad($(this));"/>
                    </tr>';
        }
            $tb .= '    </tbody>
                    </table>';
        return $tb;  
    }
	
	function getPoByItemplan() {
        $itemplan      = $this->input->post('itemplan');
        $idEstadoPlan  = $this->input->post('idEstadoPlan');
        $tb          = $this->getTablaConsultaPTR($itemplan, $idEstadoPlan); 

        $data['tablaConsultaPtr'] = $tb;  

        echo json_encode(array_map('utf8_encode', $data));
    }
	
	function ingresarEvidenciaLiquiTransp() {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try {
    
            $itemplan     = $this->input->post('itemplan');
            $idEstacion   = $this->input->post('idEstacion');

            $descEstacion = $this->m_utils->getEstaciondescByIdEstacion($idEstacion);
            $idUsuario    = $this->session->userdata('idPersonaSession');
            
            $fileNamePruebas = $_FILES["filePruebas"]["name"];
            $filePruebasTemp = $_FILES['filePruebas']['tmp_name'];

            $fileNamePerfil  = $_FILES['filePerfil']['name'];
            $filePerfilTemp  = $_FILES['filePerfil']['tmp_name'];

            $fechaActual = $this->fechaActual();
            $data = $this->cargarArchivoEvidencia($itemplan, $idEstacion, $descEstacion, $fileNamePruebas, $filePruebasTemp, $fileNamePerfil, $filePerfilTemp);
			
			if($data['error'] == EXIT_ERROR) {
				throw new Exception($data['msj']);
			}
            // $countExistItemplansEstacAvanc = $this->m_utils->countItemplanEstacionAvance($itemplan, $idEstacion);
            
            // if($countExistItemplansEstacAvanc > 0) {
                // $dataItemplanEstacionAvance    = $this->m_utils->getPorcentajeAvanceByItemplanEstacion($itemplan, $idEstacion);
                // if($dataItemplanEstacionAvance['flg_evidencia'] == null || $dataItemplanEstacionAvance['flg_evidencia'] == '') {
                    // $dataArrayPorcentaje = array(
                                                    // 'flg_evidencia' => 1,
													// 'porcentaje'    => 100
                                                // );
                    // $data = $this->m_utils->updateItemplanEstacionAvance($itemplan, $idEstacion, $dataArrayPorcentaje);
                // } else {
                    // $data['error'] = EXIT_SUCCESS;
                // }
            // } else {
				
                $dataArrayPorcentaje = array('itemplan'       => $itemplan,
                                             'idEstacion'      => $idEstacion,
                                             'porcentaje'      => 100,
                                             'fecha'           => $fechaActual,
                                             'id_usuario_log'  => $idUsuario,
                                             'flg_evidencia'   => 1);
                $data = $this->m_utils->insertPorcentajeLiqui($dataArrayPorcentaje);
				// $dataItemplanEstacionAvance = $this->m_utils->getPorcentajeAvanceByItemplanEstacion($itemplan, $idEstacion);
				
				if($data['error'] == EXIT_ERROR) {
					throw new Exception($data['msj']);
				}
				
				$arrayUpdate = array(
					"idEstadoPlan" => ID_ESTADO_PRE_LIQUIDADO,
					"usu_upd" => $this->session->userdata('idPersonaSession'),
					"fecha_upd" => $this->fechaActual(),
					"descripcion" => 'PRE LIQUIDACION',
					"fechaPreLiquidacion" =>  $this->fechaActual()
				);
				$data = $this->M_pqt_pre_liquidacion->updateEstadoPlanObraToPreLiquidado($itemplan, $arrayUpdate);
				if($data['error'] == EXIT_ERROR){
					throw new Exception($data['msj']);
				}else{
				
					$arrayDataLog = array(
						'tabla'            => 'planobra',
						'actividad'        => 'Obra Pre-Liquidada',
						'itemplan'         => $itemplan,
						'fecha_registro'   => $this->fechaActual(),
						'id_usuario'       => $this->session->userdata('idPersonaSession'),
						'idEstadoPlan'     => ID_ESTADO_PRE_LIQUIDADO
					);
					$this->m_utils->registrarLogPlanObra($arrayDataLog);
				}
        }catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
	
	function cargarArchivoEvidencia($itemplan, $idEstacion, $descEstacion, $fileNamePruebas, $filePruebasTemp, $fileNamePerfil, $filePerfilTemp) {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        //DE NO EXISTIR LA CARPETA ITEMPLAN LA CREAMOS
        try {
            $pathItemplan = 'uploads/evidencia_fotos/'.$itemplan;
            if (!is_dir($pathItemplan)) {
                mkdir ($pathItemplan, 0777);
            }
            
            //DE NO EXISTIR LA CARPETA ITEMPLAN ESTACION LA CREAMOS
            $pathItemEstacion = $pathItemplan.'/'.$descEstacion;
			
			_log("pathItemEstacion : ".$pathItemEstacion);
			
            if(is_dir($pathItemEstacion)) {
                $this->rrmdir($pathItemEstacion);    
            }
			
			
            if (!is_dir($pathItemEstacion)) {
                mkdir ($pathItemEstacion, 0777);
            }
            
            //CREAMOS CARPETA DE PRUEBAS REFLECTOMETRICAS
            $pathReflectometricas = $pathItemEstacion.'/P_REFLECTOMETRICAS';
            if (!is_dir($pathReflectometricas)) {
                mkdir ($pathReflectometricas, 0777);
            }
            
            //CREAMOS CARPETA DE PRUEBAS DE PERFIL
            $pathPerfil = $pathItemEstacion.'/P_PERFIL';
            if (!is_dir($pathPerfil)) {
                mkdir ($pathPerfil, 0777);
            }

            $uploadfile1 = $pathReflectometricas.'/'. basename($fileNamePruebas);

            if (move_uploaded_file($filePruebasTemp, $uploadfile1)) {
                log_message('error', 'Se movio el archivo a la ruta 1.'.$uploadfile1);
            }else {
                throw new Exception('Hubo un problema con la carga del archivo 1 al servidor, comuniquese con el administrador.');
            }

            $uploadfile2 = $pathPerfil.'/'. basename($fileNamePerfil);
           
            if (move_uploaded_file($filePerfilTemp, $uploadfile2)) {
                log_message('error', 'Se movio el archivo a la ruta 2.'.$uploadfile2);
            }else {
                throw new Exception('Hubo un problema con la carga del archivo 2 al servidor, comuniquese con el administrador.');
            }
            
            $dataFormularioEvidencias = array(
                'itemplan'          =>  $itemplan,
                'fecha_registro'    => $this->fechaActual(),
                'usuario_registro'  => $this->session->userdata('idPersonaSession'),
                'idEstacion'        => $idEstacion,
                'path_pdf_pruebas'  =>  $uploadfile1,
                'path_pdf_perfil'   =>  $uploadfile2
            );

            $data = $this->M_pqt_pre_liquidacion->registrarEvidencias($dataFormularioEvidencias);
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }    
        return $data;     
    }
	
	function rrmdir($src) {
        $dir = opendir($src);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                $full = $src . '/' . $file;
                if ( is_dir($full) ) {
                    $this->rrmdir($full);
                }
                else {
                    unlink($full);
                }
            }
        }
        closedir($dir);
        rmdir($src);
    }
	
	 public function registrarEvidencias()
    {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {

			$itemplan = $this->input->post('itemplan') ? $this->input->post('itemplan') : null;
			$idUsuarioSession = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;
			$idEECC = $this->session->userdata("eeccSession");
            $idUsuario = $this->session->userdata('idPersonaSession');

            $this->db->trans_begin();

			if ($itemplan == null) {
				throw new Exception('Hubo un error al recibir el itemplan!!');
			}
			if ($idEECC == null || $idEECC == '') {
                throw new Exception("Su sesión a caducado, actualizar  la página");
            }

			if(count($_FILES) != 2){
				throw new Exception('Debe añadir los archivos para guardar!!');
			}

			if (!file_exists("uploads/evidencia_fotos/".$itemplan)) {
                if (!mkdir("uploads/evidencia_fotos/".$itemplan)) {
                    throw new Exception('Hubo un error al crear la carpeta!!');
                }
            }

			$rutaExcel = null;
			$rutaPdf = null;
			foreach($_FILES as $key => $file){
				$nombreArchivo = $file['name'];
            	$tipoArchivo = $file['type'];
            	$nombreArchivoTemp = $file['tmp_name'];
            	$tamano_archivo = $file['size'];
				$nombreFinalArchivo = date("Y_m_d_His_").$nombreArchivo;
				$rutaFinalArchivo = "uploads/evidencia_fotos/".$itemplan."/".$nombreFinalArchivo;
				if($key == 'file1'){
					$rutaExcel = $rutaFinalArchivo;
				}else if($key == 'file2'){
					$rutaPdf = $rutaFinalArchivo;
				}
				if (!move_uploaded_file($nombreArchivoTemp, $rutaFinalArchivo)) {
					throw new Exception('No se pudo subir el archivo: ' . $nombreFinalArchivo . ' !!');
				}
			}
			$arrayUpdatePO = array(
				"ruta_excel_evidencia" => $rutaExcel,
				"ruta_pdf_evidencia" => $rutaPdf,
                "has_evidencia" => '1'
			);

			$data = $this->m_pqt_consulta->updateItemplan($itemplan, $arrayUpdatePO);
            if($data['error'] == EXIT_ERROR) {
                throw new Exception($data['msj']);
            }

            /*$arrayDataUpdatePO = array(
                'fecha_valida'  	=> $this->fechaActual(),
                'id_usuario_valida' => $idUsuario,
                'estado'     		=> 1
             );
			$data = $this->m_utils->insertEditPoEnDetallePo($itemplan, $arrayDataUpdatePO);
			if($data['error'] == EXIT_ERROR) {
                throw new Exception($data['msj']);
            }*/

            $arrayDataLog = array(
                'tabla'            => 'plantaInterna',
                'actividad'        => 'En certificacion obra-Validado',
                'itemplan'         => $itemplan,
                'fecha_registro'   => $this->fechaActual(),
                'id_usuario'       => $idUsuario,
                'idEstadoPlan'     => 4
             );

            $flg = $this->m_utils->registrarLogPlanObra($arrayDataLog);

            if($flg == 0) {
                throw new Exception('No se registro el log');
            }

            $data = $this->M_plantaInterna->validaItemplan($itemplan, $this->fechaActual());

            if($data['error'] == EXIT_ERROR) {
                throw new Exception('No se valido');
            }

            $countPendienteExceso = $this->m_control_presupuestal->getCountValida($itemplan, NULL);

            if($countPendienteExceso > 0) {
                $data['error'] = EXIT_ERROR;
                throw new Exception('Esta obra tiene una solicitud de exceso pendiente.');
            }

            $countPendienteEdicOc = $this->m_control_presupuestal->getValidOcEdic($itemplan);

            if($countPendienteEdicOc > 0) {
                $data['error'] = EXIT_ERROR;
                throw new Exception("tiene una solicitud OC de edicion pendiente.");
            }

            $infoItemplan = $this->m_utils->getInfoItemplanWhitSubProyecto($itemplan);
			
            $arrayData   = $this->m_utils->getDataSolicitudOc($itemplan); //OC EDICION
            $dataFlgEdic = $this->m_utils->getDataPoByItemplan($itemplan);

            if($arrayData == null){
                $data['error'] = EXIT_ERROR;
                throw new Exception('El itemplan no cuenta con solicitud de oc activa!!');
            }

			$estadoCerti = 1;
			$fechaActual = $this->m_utils->fechaActual();
            if($dataFlgEdic['flg_solicitud_edic'] == 1) {// SI ES DIFERENTE AL TOTAL SE GENERA UNA EDICION
				$cod_solicitud = $this->m_utils->getCodSolicitudOC();
				$estadoCerti = 4;
                if($arrayData['pep1'] != null && $arrayData['pep1'] != '') { 
                    $data = $this->m_utils->insertSolicitudOcEdi($arrayData, $fechaActual, $cod_solicitud, $dataFlgEdic['costo_mo'], $itemplan);
                
                    if($data['error'] == EXIT_ERROR) {
                        throw new Exception('No se ingreso la solicitud de edicion.');
                    }
                }
            }  
			if($arrayData['pep1'] != null && $arrayData['pep1'] != '') {
				$cod_solicitud = $this->m_utils->getCodSolicitudOC();
				$data = $this->m_utils->insertSolicitudOcCerti($arrayData, $fechaActual, $cod_solicitud, $itemplan, $dataFlgEdic['costo_mo'], $estadoCerti);	
				
				if($data['error'] == EXIT_ERROR) {
					throw new Exception('No se ingreso la solicitud de certificacion.');
				}
			} else {
				throw new Exception('No se ingresar la solicitud de certificacion.');
			}
            $this->db->trans_commit();
			$data['tablaAsigGrafo'] = $this->makeHTLMTablaConsulta($this->m_pqt_consulta->getPtrConsultaPqt($itemplan, '', '', '', '', $idEECC, null, null, null, null,null));
            
        } catch (Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }

        echo json_encode($data);
    }
}
