<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class C_control_presupuestal extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_control_presupuestal/m_control_presupuestal');
        $this->load->model('mf_utils/m_utils');
		$this->load->model('mf_liquidacion/m_solicitud_Vr');
		$this->load->model('mf_detalle_obra/m_detalle_obra');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index() {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {
            $data['tablaSiom'] = $this->getTablaControlPresupuestal(null, null, null, null);
            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $permisos = $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, 270, 259, ID_MODULO_ADMINISTRATIVO);
            $data['opciones'] = $result['html'];
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_control_presupuestal/v_control_presupuestal', $data);
            } else {
                redirect('login', 'refresh');
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    function getTablaControlPresupuestal($situacion, $area, $itemplan, $idBandeja) {
        if ($situacion == null && $area == null && $itemplan == null) {
            $data = null;
        } else {
            $data = $this->m_control_presupuestal->getBandejaControlPresupuestal($situacion, $area, $itemplan);
        }
        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>ACCION</th>
                            <th>ITEMPLAN</th>
                            <th>TIPO SOLICITUD</th>
							<th>PO</th>
							<th>PROYECTO</th>
                            <th>SUBPROYECTO</th>    
                            <th>ESTACION</th>                           
                            <th>TIPO AREA</th>                            
							<th>EECC</th>
							<th>ZONAL</th>
                            <th>COSTO ACTUAL</th>                            
                            <th>EXCEDENTE SOL.</th>
						
                            <th>COSTO FINAL</th>
                            <th>USUA. SOLICITA</th>
                            <th>FEC. SOLICITA</th>							
                            <th>USUA. VALIDA</th>
                            <th>FEC. VALIDA</th>
                            <th>SITUACION</th>
                        </tr>
                    </thead>                    
                    <tbody>';
        if ($data != null) {
			$btnArchivo = null;
            foreach ($data as $row) {
				if($row->url_archivo != null) {
					$btnArchivo = '<a href="'.base_url().'/'.$row->url_archivo.'" download>
									<i title="Descargar" class="zmdi zmdi-hc-2x zmdi-case-download"></i>
								   </a>';
				}
				
				$btnVerDetalle = '<a data-id_solicitud ="'.$row->id_solicitud.'" data-origen ="'.$row->origen.'"
									onclick="openMdlDetalleExceso($(this));">
									<i style="color:blue" title="ver detalle" class="zmdi  zmdi-hc-2x zmdi-eye"></i>
								  </a>';
                $accion;
                if ($idBandeja == '1') {
                    $accion = (($row->situacion == 'PENDIENTE') ? '
                                <div class="row">
                                    <div class="col-sm-2">
										<a data-origen="'.$row->origen.'" data-id_estacion="'.$row->idEstacion.'" data-cos="' . $row->costo_final_nf . '" data-costo_po="'.$row->costoPo.'" data-sol="' . $row->id_solicitud . '" data-acc="1" onclick="openModalAtender($(this));">
											<i title="Aprobar"  class="zmdi zmdi-hc-2x zmdi-check-circle" style="color: green;"></i>
										</a>                    
                                    </div>
                                    <div class="col-sm-2">
										<a data-origen="'.$row->origen.'" data-id_estacion="'.$row->idEstacion.'" data-cos="' . $row->costo_final_nf . '" data-costo_po="'.$row->costoPo.'" data-sol="' . $row->id_solicitud . '" data-acc="2" onclick="openModalAtender($(this));">
											<i title="Rechazar" class="zmdi zmdi-hc-2x zmdi-close-circle" style="color: red;"></i>
										</a>
                                    </div>
                                </div>' : '') . ' ';
                } else {
                    $accion = '';
                }
                $html .= ' <tr>              
                            <td>' . $accion.' '.$btnVerDetalle.' '.$btnArchivo.'</td>              
                            <td>' . $row->itemplan . '</td>
                            <td>' . $row->tipo_origen.'</td>
							<td>' . (($row->codigo_po==null || $row->codigo_po=='null') ? '' : $row->codigo_po) .'</td>
							<td>' . $row->proyectoDesc .'</td>
                            <td>' . $row->subProyectoDesc . '</td>                            
                            <td>' . $row->estacionDesc . '</td>
                            <td>' . $row->tipo_po . '</td>
							<td>' . $row->eecc . '</td>
							<td>' . $row->zonalDesc . '</td>
                            <td>' . $row->costoActualPo . '</td>
                            <td>' . $row->excesoPo . '</td>
							
                            <td>' . $row->costo_final_f . '</td>
                            <td>' . utf8_decode($row->usua_solicita) . '</td>
                            <td>' . $row->fecha_solicita . '</td>							
                            <td>' . utf8_decode($row->usua_valida) . '</td>
                            <td>' . $row->fecha_valida . '</td>
                            <td>' . $row->situacion . '</td>
                        </tr>';
            }
        }
        $html .= '</tbody>
                </table>';

        return $html;
    }

    function validarControlPresupuestal() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {

            $accion = $this->input->post('accion');
            $idSolicitud = $this->input->post('solicitud');
            $comentario  = $this->input->post('comentario');
            $costoFinal  = $this->input->post('costoFinal');
			$origen	     = $this->input->post('origen');
			$idEstacion  = $this->input->post('idEstacion');
			$costoPo     = $this->input->post('costoPo');
			
            $idUsuario   = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;
            
			$this->db->trans_begin();
			
			if ($idUsuario == null) {
                throw new Exception('Su sesion expiro, porfavor vuelva a logearse.');
            }
            if ($accion == null || $idSolicitud == null) {
                throw new Exception('Datos Invalidos, refresque la pagina y vuelva a intentarlo.');
            }
            if ($comentario == null || $comentario == '') {
                throw new Exception('Ingresar comentario');
            }
						
			$infoObra = $this->m_control_presupuestal->getInfoObraByIdSolicitud($idSolicitud);
			// if ($origen == null || $origen == '') {
				// throw new Exception('Ingresar origen');
			// }

            $dataUpdateSolicitud = array(	'usuario_valida' => $this->session->userdata('idPersonaSession'),
											'fecha_valida' => $this->fechaActual(),
											'estado_valida' => $accion, //1=APROBADO  2=RECHAZADO
											'comentario_valida' => utf8_decode(strtoupper($comentario)));
			
            if ($accion == 2) {//rechazar
                $data = $this->m_control_presupuestal->rejectSolicitud($dataUpdateSolicitud, $idSolicitud);
				 if($origen == 5) {//ADICION PQT
                    if($infoObra['isFerreteria'] == 1){
                        $data = $this->m_control_presupuestal->deletTmpFerreteria($infoObra['itemplan'], $idEstacion);
                    }               
                }
            } else if ($accion == 1) {//aprobar
				if(($costoPo == null || $costoPo == 0) && $origen != 5 && $origen != 3  && $origen != 6) {
					throw new Exception('Costo PO Incorrecto, verificar.');
				}
				
				if($origen == null || $origen == '' || $idEstacion == null) {
					$itemplan = $infoObra['itemplan'];
					if ($infoObra['tipo_po'] == 1) {//material{
						if ($infoObra['costo_unitario_mat'] > $costoFinal) {
							throw new Exception('El costo ingresado es menor al costo actual ' . number_format($infoObra['costo_unitario_mat'], 2) . ', favor de ingresar un costo mayor');
						}
						$dataItemplan = array('costo_unitario_mat' => $costoFinal);
					} else if ($infoObra['tipo_po'] == 2) {//mano_obra
						if ($infoObra['costo_unitario_mo'] > $costoFinal) {
							throw new Exception('El costo ingresado es menor al costo actual ' . number_format($infoObra['costo_unitario_mo'], 2) . ', favor de ingresar un costo mayor');
						}
						$dataItemplan = array('costo_unitario_mo' => $costoFinal);
					} else {
						throw new Exception('Ocurrio un error al obetener la informacion del tipo de la solicitud, refresque la pagina y vuelva a intentarlo.');
					}
					$data = $this->m_control_presupuestal->aprobSolicitud($dataItemplan, $itemplan, $dataUpdateSolicitud, $idSolicitud);
				} else {
					
					$itemplan = $infoObra['itemplan'];
					
					if ($infoObra == null) {
						throw new Exception('Ocurrio un error al obetener la informacion de la solicitud, refresque la pagina y vuelva a intentarlo.');
					}
					if($itemplan == null) {
						throw new Exception('Error itemplan no encontrado');
					}
					
					if ($infoObra['tipo_po'] == 1) {//material{
						if ($infoObra['costo_unitario_mat'] > $costoFinal) {
							throw new Exception('El costo ingresado es menor al costo actual ' . number_format($infoObra['costo_unitario_mat'], 2) . ', favor de ingresar un costo mayor');
						}
						$dataItemplan = array('costo_unitario_mat' => $costoFinal);
					} else if ($infoObra['tipo_po'] == 2) {//mano_obra
						if ($infoObra['costo_unitario_mo'] > $costoFinal) {
							throw new Exception('El costo ingresado es menor al costo actual ' . number_format($infoObra['costo_unitario_mo'], 2) . ', favor de ingresar un costo mayor');
						}
						$dataItemplan = array('costo_unitario_mo' => $costoFinal);
					} else {
						throw new Exception('Ocurrio un error al obetener la informacion del tipo de la solicitud, refresque la pagina y vuelva a intentarlo.');
					}
					if ($infoObra['itemplan'] == null) {
						throw new Exception('Ocurrio un error al obetener el itemplan de la solicitud, refresque la pagina y vuelva a intentarlo.');
					}
					
					$genSolEdic = 1;//POR DEFECTOR 1
					if($origen == 5) {//ADICION PQT
						$ferreteria = false;
						if($infoObra['isFerreteria'] == 1){
							 $ferreteria = true;
						}else if($infoObra['isFerreteria']    ==  0){
							 $ferreteria = false;
						}else{
							 throw new Exception('Tipo de ferreteria no reconocido, genere un ticket CAP.');					         
						}
					    
						if($infoObra['genSolEdic'] == 1) {
							$genSolEdic == 1;
						} else {
							$genSolEdic == 0;//CAMBIA RECIEN SI genSolEdic NO ES IGUAL A 1 Y NO SE GENERA SU SOLICITUD OC
						}
						
						$codigo_po = $this->m_control_presupuestal->getCodPoSolicitudLiqui($idSolicitud);
						$data = $this->m_control_presupuestal->updateEstadoSolicitudLiquiAdocPqt($dataItemplan, $infoObra['itemplan'], $dataUpdateSolicitud, $idSolicitud, $codigo_po, $ferreteria);
					 }if($origen == 4) {//LIQUI
						$codigo_po = $this->m_control_presupuestal->getCodPoSolicitudLiqui($idSolicitud);
						$arrayPo = $this->m_utils->getInfoPoByCodigoPo($codigo_po);
						
						if($arrayPo['estado_po'] == 5 || $arrayPo['estado_po'] == 6) {
							throw new Exception('La PO ya se encuentra validada, por favor rechazar la solicitud.');
						}
						
						$data = $this->m_control_presupuestal->updateEstadoSolicitudLiqui($dataItemplan, $infoObra['itemplan'], $dataUpdateSolicitud, $idSolicitud, $codigo_po, $costoPo);
					} else if($origen == 2) {// REG MO
						$infoEECC = $this->m_control_presupuestal->getDataPlanObra($itemplan);
						if($infoEECC['jefatura'] == 'LIMA' && $idEstacion == 4) {
							$idEecc = $infoEECC['idEmpresaColabFuente'];
						} else { 
							$idEecc = $infoEECC['idEmpresaColab'];
						}
						
						$codigo_po = $this->m_utils->getCodigoPO($itemplan);
						
						if($codigo_po == null || $codigo_po == '') {
							throw new Exception('codigo PO no existe');
						}
						
						if($idEstacion == null || $idEstacion == '') {
							throw new Exception('estacion no existe');
						}
						
						$dataPO = array(
											'itemplan'      => $itemplan,
											'codigo_po'     => $codigo_po,
											'estado_po'     => PO_REGISTRADO, //ESTADO REGISTRADO
											'idEstacion'    => $idEstacion,
											'from'          => 4,
											'costo_total'   => $costoPo,
											'idUsuario'     => $idUsuario,
											'fechaRegistro' => $this->fechaActual(),
											'estado_asig_grafo' => 0,
											'flg_tipo_area' => 2,//MANO DE OBRA
											'id_eecc_reg'   => $idEecc
										);
					
						$dataLogPO = array	(
												'codigo_po'         =>  $codigo_po,
												'itemplan'          =>  $itemplan,
												'idUsuario'         =>  $idUsuario,
												'fecha_registro'    =>  $this->fechaActual(),
												'idPoestado'        =>  PO_REGISTRADO,
												'controlador'       =>  'VALIDADO EN LA BANDEJA DE EXCESOS'
											);
						
						$subProyectoEstacion = $this->m_utils->getIdSubProyectoEstacionByItemplanAndEstacion($itemplan, $idEstacion, 'MO');
					
						if($subProyectoEstacion ==  null){
							throw new Exception('Hubo un error obtener el subproyecto - estacion');
						}
						$dataDetalleplan = array(	'itemPlan' 				=> $itemplan,
													'poCod'    				=> $codigo_po,
													'idSubProyectoEstacion' => $subProyectoEstacion);               
					
						//log_message('error', print_r($dataDetalleplan, true));
						$data = $this->m_control_presupuestal->updateEstadoSolicitudRegMo(  $dataItemplan, $itemplan, $dataUpdateSolicitud, $idSolicitud,
																							$dataPO, $dataLogPO, $dataDetalleplan, $codigo_po);
						if($data['error']   ==  EXIT_ERROR){
							throw new Exception('Hubo un error interno, por favor volver a intentar.');
						}
						$data['codigoPO'] = $codigo_po;
					} else if($origen == 1) {//REG MAT
						$infoObra = $this->m_control_presupuestal->getInfoObraByIdSolicitud($idSolicitud);
						$flg_paquetizado = $this->m_utils->getFlgPaquetizadoPo($itemplan);
						
						if($flg_paquetizado == 2) {
							$countPo = $this->m_utils->getCountPo($itemplan, $idEstacion, 1);
							$idProyecto = $this->m_utils->getProyectoByItemplan($itemplan);
							$countCienPor = $this->m_utils->getPorcentajeByItemplanAndEstacion($itemplan, $idEstacion);
							
							if($countCienPor > 0) {
								throw new Exception('Estaci&oacute;n liquidada, ya no se puede crear m&aacute;s POs.');
							}
							
							if($countPo >= 2 && $idProyecto != 1 && $idProyecto != 4) {// HFC Y OBRA PUB. PUEDE CREAR MAS POs.
								throw new Exception('No se puede ingresar m&aacute;s de dos POs.');
							}				
						}
						
						$infoEECC = $this->m_control_presupuestal->getDataPlanObra($itemplan);
						if($infoEECC['jefatura'] == 'LIMA' && $idEstacion == 4) {
							$idEecc = $infoEECC['idEmpresaColabFuente'];
						} else { 
							$idEecc = $infoEECC['idEmpresaColab'];
						}
						
						$idSubProyecto = $infoEECC['idSubProyecto'];
						$codigo_po = $this->m_utils->getCodigoPO($itemplan);
						$idSubProyectoEstacion = $this->m_utils->getIdSubProyectoEstacionByItemplanAndEstacion($itemplan, $idEstacion, 'MAT');
						
						if ($codigo_po == null) {
							throw new Exception('Hubo un error al generar el codigo PO ');
						}
						
						if($idSubProyecto == null) {
							throw new Exception('Hubo un error interno, subproyecto vacio.');
						}
						
						if($idSubProyectoEstacion == null) {
							throw new Exception('Hubo un error interno, idSubProyectoEstacion vacio.');
						}
						
						$dataPO	 = array(
											"itemplan"    => $itemplan,
											"codigo_po"   => $codigo_po,
											"estado_po"   => 1,
											"idEstacion"  => $idEstacion,
											"from"        => 4,
											"costo_total" => $costoPo,
											"idUsuario"   => $idUsuario,
											"fechaRegistro" => $this->fechaActual(),
											"flg_tipo_area" => 1,
											"id_eecc_reg"   =>$idEecc
										);

						$dataDetalleplan = array(
													"itemPlan" 				=> $itemplan,
													"poCod" 				=> $codigo_po,
													"idSubProyectoEstacion" => $idSubProyectoEstacion,
													"fec_registro" 			=> $this->fechaActual()
												);
						$dataLogPO = array(
											"codigo_po" => $codigo_po,
											"itemplan" => $itemplan,
											"idUsuario" => $idUsuario,
											"fecha_registro" => $this->fechaActual(),
											"idPoestado" => 1,
											"controlador" => 'VALIDADO EN LA BANDEJA DE EXCESOS'
										  );
																	
						$data = $this->m_control_presupuestal->updateEstadoSolicitudRegMat(  $dataItemplan, $itemplan, $dataUpdateSolicitud, $idSolicitud,
																							$dataPO, $dataLogPO, $dataDetalleplan, $codigo_po);
						
						if ($data['error'] == EXIT_SUCCESS) {
							$flgExiste = $this->m_utils->getCountINConfigAutoProb($idSubProyecto);
							if ($flgExiste > 0) {

								$fechaPrevEjec = $this->m_utils->getFecPrevEjec($itemplan);
								if ($fechaPrevEjec != null) {

									$fechaPrevEjec = new DateTime($fechaPrevEjec);
									$dateActual = new DateTime($this->fechaActual());
									$dateActual = new DateTime($dateActual->format('Y-m-d'));
									$diferencia = $dateActual->diff($fechaPrevEjec);
									$diferencia = $diferencia->format('%R%a');

									if ($diferencia <= 60) {
										$arrayUpdatePPO = array (
																	"estado_po" => 2,
																);
										$data = $this->m_detalle_obra->updatePO($itemplan, $codigo_po, $idEstacion, $arrayUpdatePPO);
										if ($data['error'] == EXIT_SUCCESS) {
											
											$dataLogPO2 = array (
																	"codigo_po" => $codigo_po,
																	"itemplan" => $itemplan,
																	"idUsuario" => $idUsuario,
																	"fecha_registro" => $this->fechaActual(),
																	"idPoestado" => 2,
																	"controlador" => 'VALIDADO EN LA BANDEJA DE EXCESOS'
																);
										   
											$data = $this->m_detalle_obra->insertarLOGPO($dataLogPO2);
											 /************************Nuevo 05.07.2019 czavalacas: asignarle presupuesota las autoaprobaciones*******************************/
											if ($data['error'] == EXIT_SUCCESS) {//log_message('error', ' paso insertarLOGPO');
												$data = $this->m_utils->execGetGrafosOnePtr($codigo_po);
												if ($data['error'] == EXIT_SUCCESS) {//log_message('error', ' paso execGetGrafosOnePtr');
													$data = $this->m_utils->sendParalizadoSisego($itemplan);
													//log_message('error', ' paso sendParalizadoSisego:'.print_r($data,true));
												}
											}
											 /*******************************************************/
										}
									}
								}
							}	
						}

						if($data['error']   ==  EXIT_ERROR){
							throw new Exception('Hubo un error interno REG MAT, por favor volver a intentar.');
						}
						$data['codigoPO'] = $codigo_po;					
					} else if($origen == 3) {//REG VR
						$codigo = $this->m_solicitud_Vr->getCodigoSolicitudVr();

						if($codigo == null || $codigo == '') {
							throw new Exception('Error codigo vr comunicarse con el programador');
						}
						
						$data = $this->m_control_presupuestal->updateEstadoSolicitudVr(	$dataItemplan, $itemplan, $dataUpdateSolicitud, 
																						$idSolicitud, $codigo, $idUsuario);
					
						if($data['error']   ==  EXIT_ERROR){
							throw new Exception('realizo una accion incorrecta en VR, por favor volver a intentar.');
						}
					} else if($origen == 6) {//EDICION PIN
						// $codigo_po = $this->m_control_presupuestal->getCodPoSolicitudLiqui($idSolicitud);
						// $arrayPo = $this->m_utils->getInfoPoByCodigoPo($codigo_po);
						
						// if($arrayPo['estado_po'] == 5 || $arrayPo['estado_po'] == 6) {
						// 	throw new Exception('La PO ya se encuentra validada, por favor rechazar la solicitud.');
						// }
						
						$data = $this->m_control_presupuestal->updateEstadoSolicitudPin($dataItemplan, $infoObra['itemplan'], $dataUpdateSolicitud, $idSolicitud);
					}
				}

				$countPendienteEdicOc = $this->m_control_presupuestal->getValidOcEdic($itemplan);

				if($countPendienteEdicOc > 0) {
					$data['error'] = EXIT_ERROR;
					throw new Exception("No se puede generar una solicitud Edic. OC, ya que una solicitud del mismo tipo se encuentra pendiente de validacion.");
				}

				$arrayData = $this->m_control_presupuestal->getDataSolicitudOc($itemplan); //OC EDICION
				if($infoObra['tipo_po'] == 2) {//MO
					if($arrayData['pep1'] != null && $arrayData['pep1'] != '') {
						if($genSolEdic == 1) {
							if($infoObra['costo_unitario_mo_crea_oc'] <= $costoFinal) {
								$arrayExcedete = $this->m_control_presupuestal->getExcedente($idSolicitud, $arrayData['pep1']);
								
								if($arrayExcedete['flg_presupuesto'] == 1) {
									$fechaActual = $this->m_utils->fechaActual();
									$cod_solicitud = $this->m_utils->getCodSolicitudOC();
									$data = $this->m_control_presupuestal->insertSolicitudOcEdi($arrayData, $fechaActual, $cod_solicitud, $costoFinal, $itemplan);	
								
									if($data['error'] == EXIT_ERROR) {
										throw new Exception($data['msj']);
									}
									
									$data = $this->m_utils->actualizarMontoDisponibleAll($arrayData['pep1'], $arrayExcedete['excedente']);
									
									if($data['error'] == EXIT_ERROR) {
										throw new Exception($data['msj']);
									}
								} else {
									$data['error'] = EXIT_ERROR;
									throw new Exception("La pep ".$arrayData['pep1']." se encuentra sin presupuesto para este exceso.");
								}
							}
						}
					}
				}				
            } else {
                throw new Exception('Ocurrio un error al obetener la informacion del tipo de la accion a realizar, refresque la pagina y vuelva a intentarlo.');
            }
			
			$this->db->trans_commit();

            /*
              $data['tablaBandejaSiom'] = $this->getTablaSiom(null,null,null,null,null,null); */
            //
        } catch (Exception $e) {
			$this->db->trans_rollback();
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

    function generarSolicitud() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
			$origen	 	= $this->input->post('origen');
            $itemplan 	= $this->input->post('itemplan');
            $tipo_po 	= $this->input->post('tipo_po');
            $costo_inicial = $this->input->post('costo_inicial');
            $exceso 	= $this->input->post('exceso_solicitado');
            $costo_final = $this->input->post('costo_final');
			$codigo_po	   = $this->input->post('codigo_po');
			$data_json     = $this->input->post('data_json');
			$comentario    = $this->input->post('comentario');
			$idEstacion    = $this->input->post('idEstacion');

			$data_json = json_decode($data_json);
			$this->db->trans_begin();

            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;
            if ($idUsuario == null) {
                throw new Exception('Su sesion expiro, porfavor vuelva a logearse.');
            }
			
			if(count($data_json) == 0) {
				throw new Exception("No cuenta con detalle");
			}
            $dataInsert = array('itemplan' 		=> $itemplan,
								'codigo_po'     => $codigo_po,
								'tipo_po'  		=> $tipo_po,
								'costo_inicial' => $costo_inicial,
								'exceso_solicitado' => $exceso,
								'costo_final' => $costo_final,
								'usuario_solicita' => $idUsuario,
								'fecha_solicita' => $this->fechaActual(),
								'comentario_reg' => $comentario,
								'idEstacion'     => $idEstacion,
								'origen'	     =>	$origen,
								'url_archivo'    => null
								);

			if (count($_FILES) > 0) {
				$uploaddir =  'uploads/solicitud_paquetizado/'.$itemplan.'_'.$origen.'/';//ruta final del file
				$uploadfile = $uploaddir . basename($_FILES['file']['name']);
				if (! is_dir ( $uploaddir))
					mkdir ( $uploaddir, 0777 );
				
				if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
					$dataInsert['url_archivo'] = $uploadfile;
				}else {
					throw new Exception('Hubo un problema con la carga del archivo al servidor, comuniquese con el administrador.');
				}
			} else {
				throw new Exception('Subir el archivo de evidencia de exceso.');
			}
			
			if($codigo_po != null) {
				$countPendiente = $this->m_control_presupuestal->getCountValida($itemplan, $codigo_po);
				if($countPendiente > 0) {
					throw new Exception('Esta PO ya cuenta con una solicitud de exceso pendiente.');
				}
			}
			
			$data = $this->m_control_presupuestal->registrarSolicitudCP($dataInsert);
			
			$dataDetalleSolicitud = array();
			if($data['error'] == EXIT_SUCCESS) {
				if($origen == 6) {//EDICION PIN
					foreach($data_json as $row) {
						$arrayDetallePin = array(	"id_solicitud"                 => $data['id_solicitud'],
													"codigo_po" 				   => $row->ptr,
													"id_ptr_x_actividades_x_zonal" => $row->id_ptr_x_actividades_x_zonal,
													"idActividad" 				   => $row->id_actividad,
													"baremo"      				   => $row->baremo,
													"costo"       				   => $row->precio,
													"cantidad_inicial"	 		   => $row->cantidadInicial,
													"cantidad_final"   			   => $row->cantidad_final,
													"costo_kit"   			       => $row->costo_kit,
												    "costo_mat"                    => $row->costo_mat );
						array_push($dataDetalleSolicitud, $arrayDetallePin);							
					}
					
					if($codigo_po == null || $codigo_po == '') {
						throw new Exception('No ingreso la PO, comunicarse con el programador a cargo.');
					}
					
					$data = $this->m_control_presupuestal->regDetalleEditPin($dataDetalleSolicitud);
				} else if($origen == 4) {//LIQUIDACION MO
					if($codigo_po == null || $codigo_po == '') {
						throw new Exception('No ingreso la PO, comunicarse con el programador a cargo.');
					}
					$arrayActividades = array();
					foreach($data_json as $datos){
                        if($datos!=null){
                            if($datos[8]!=null){
                                // if($datos[8]    ==  1){
                                    if(!in_array($datos[6], $arrayActividades)){
                                        $dataCMO = array();
                                        $dataCMO['id_solicitud']     = $data['id_solicitud'];
                                        $dataCMO['codigo_po']        = $codigo_po;
                                        $dataCMO['idActividad']      = $datos[6];
                                        $dataCMO['baremo']           = $datos[4];
                                        $dataCMO['costo']            = $datos[3];
                                        $dataCMO['cantidad_inicial'] = ($datos[8] == 1) ? $datos[5] : 0;
                                        $dataCMO['monto_inicial']    = $datos[7];
                                        $dataCMO['cantidad_final']   = $datos[5];
                                        $dataCMO['monto_final']      = $datos[7];
                                        array_push($dataDetalleSolicitud, $dataCMO);
										array_push($arrayActividades, $datos[6]);
                                    }
                                // }
                            }
                        }
                    }
					$data = $this->m_control_presupuestal->regDetalleLiquiMo($dataDetalleSolicitud);
				} else if($origen == 1) {
					foreach($data_json as $row) {
						if($row->codigo_material!=null){
							$arrayDetallePOTemp = array(
															"id_solicitud" 	   => $data['id_solicitud'],
															"codigo_material"  => $row->codigo_material,
															"cantidad_ingreso" => $row->cantidad_ingresada,
															"cantidad_final"   => $row->cantidad_ingresada,
															"costo_material"   => $row->costo_material
														);
			
							array_push($dataDetalleSolicitud, $arrayDetallePOTemp);
						}
					}
					$data = $this->m_control_presupuestal->regDetalleRegPo($dataDetalleSolicitud);
				} else if($origen == 3) {										
					foreach($data_json as $row) {
						$row->id_solicitud = $data['id_solicitud'];
						array_push($dataDetalleSolicitud, $row);
					}

					$data = $this->m_control_presupuestal->regDetalleVr($dataDetalleSolicitud);
				} else if($origen == 2) {					
					$arrayActividades = array();
                    foreach($data_json as $datos){
                        if($datos!=null){
                            if($datos[8]!=null){
								if(!in_array($datos[6], $arrayActividades)){
									$dataCMO = array();
									$dataCMO['id_solicitud']     = $data['id_solicitud'];
									$dataCMO['idActividad']      = $datos[6];
									$dataCMO['baremo']           = $datos[4];
									$dataCMO['costo']            = $datos[3];
									$dataCMO['cantidad_inicial'] = $datos[5];
									$dataCMO['monto_inicial']    = $datos[7];
									$dataCMO['cantidad_final']   = $datos[5];
									$dataCMO['monto_final']      = $datos[7];
									array_push($dataDetalleSolicitud, $dataCMO);
									array_push($arrayActividades, $datos[6]);//metemos idActividad
								}   
                            }
                        }
                    }
					$data = $this->m_control_presupuestal->regDetallePoMo($dataDetalleSolicitud);
				}
			}
			
			if($data['error'] == EXIT_ERROR) {
				throw new Exception($data['msj']);
			}
			
			$this->db->trans_commit();
            //$data['tablaBandejaSiom'] = $this->getTablaSiom(null,null,null,null,null,null);
        } catch (Exception $e) {
			$data['error'] = EXIT_ERROR;
			$this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function filtrarTablaCP() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            $situacion = ($this->input->post('situacion') == '') ? null : $this->input->post('situacion');
            $area = ($this->input->post('area') == '') ? null : $this->input->post('area');
            $itemplan = ($this->input->post('itemplan') == '') ? null : $this->input->post('itemplan');
            $idBandeja = ($this->input->post('idBandeja') == '') ? null : $this->input->post('idBandeja');
            $data['tablaBandejaCP'] = $this->getTablaControlPresupuestal($situacion, $area, $itemplan, $idBandeja);
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
	
	function openMdlDetalleExceso() {
		$data['msj'] = null;
        $data['error'] = EXIT_ERROR;
		try {
			$id_solicitud = $this->input->post('id_solicitud');
			$origen       = $this->input->post('origen');
			
			if($id_solicitud == null || $id_solicitud == '') {
				throw new Exception('sin codigo po, comunicarse con el programador a cargo.');
			}
			
			if($origen == null || $origen == '') {
				throw new Exception('sin origen, comunicarse con el programador a cargo.');
			}

			if($origen == 4) {
				list($tablaDetalleSolicitud, $htmlComentario) = $this->getTablaDetalleLiqui($id_solicitud);
			} else if($origen == 1) {
				list($tablaDetalleSolicitud, $htmlComentario) = $this->getTablaDetalleRegMat($id_solicitud);
			} else if($origen == 2) {
				list($tablaDetalleSolicitud, $htmlComentario) = $this->getTablaDetalleRegMo($id_solicitud);
			} else if($origen == 3) {
				list($tablaDetalleSolicitud, $htmlComentario) = $this->getTablaDetalleVr($id_solicitud);
			} else if($origen == 5) {
				list($tablaDetalleSolicitud, $htmlComentario) = $this->getTablaDetalleAdicPqt($id_solicitud);
			} else if($origen == 6) {
				list($tablaDetalleSolicitud, $htmlComentario) = $this->getTablaDetallePin($id_solicitud);
			}

			$data['error'] = EXIT_SUCCESS;
			
			// $data['tablaDetallePo'] 	   = $tablaDetallePo;
			$data['tablaDetalleSolicitud'] = $tablaDetalleSolicitud;
			$data['htmlComentario']		   = $htmlComentario;
		} catch(Exception $e) {
			$data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));		
	}
	
	function getTablaDetalleLiqui($id_solicitud) {
		$ListaDetallePO = $this->m_control_presupuestal->getDataSolicitudLiqui($id_solicitud);
		$htmlDetallePO  = null;
		$comentario     = null;
		
		$htmlDetallePO .= '<table id="tbDetalleSolicitud" class="table table-bordered">
							<thead class="thead-default">
								<tr>
									<th>CODIGO</th>
									<th>DESCRIPCION</th>
									<th>BAREMO</th>
									<th>COSTO</th>
									<th>CANTIDAD ACTUAL</th>
									<th>TOTAL ACTUAL</th>
									<th>CANTIDAD NUEVA</th>
									<th>TOTAL NUEVO</th>
								</tr>
							</thead>
							<tbody>';

		foreach ($ListaDetallePO as $row) {
			$comentario = $row['comentario_reg'];
			$htmlDetallePO .= ' <tr>
									<th>' . $row['codigo']. '</th>
									<td>' . utf8_decode($row['descripcion']) . '</td>
									<td>' . $row['baremo'] . '</td>
									<td>' . $row['costo'] . '</td>
									<td>' . $row['cantidad_actual'] . '</td>
									<td>' . $row['total_actual'] . '</td>
									<td style="background:#FAB8AA">' . $row['cantidad_final'] . '</td>
									<td style="background:#FAB8AA">' . $row['total_partida'] . '</td>
								</tr>';
		}
		$htmlDetallePO .= '</tbody>
                    </table>';
		$areaComentario = '<textarea class="form-control input-mask" rows="4" disabled>'.utf8_decode($comentario).'</textarea>';			
		return array($htmlDetallePO, $areaComentario) ;
	}
	
	function getTablaDetalleRegMat($id_solicitud) {
		$ListaDetallePO = $this->m_control_presupuestal->getDataSolicitudRegMat($id_solicitud);
		$htmlDetallePO  = null;
		$comentario     = null;
		
		$htmlDetallePO .= '<table id="tbDetalleSolicitud" class="table table-bordered">
							<thead class="thead-default">
								<tr>
									<th>CODIGO MAT</th>
									<th>DESCRIPCION</th>
									<th>COSTO</th>
									<th>CANTIDAD</th>
									<th>TOTAL</th>
								</tr>
							</thead>
							<tbody>';

		foreach ($ListaDetallePO as $row) {
			$comentario = $row['comentario_reg'];
			$htmlDetallePO .= ' <tr>
									<th>' . $row['codigo_material']. '</th>
									<td>' . utf8_decode($row['descrip_material']) . '</td>
									<td>' . $row['costo_material'] . '</td>
									<td style="background:#FAB8AA">' . $row['cantidad_final'] . '</td>
									<td style="background:#FAB8AA">' . $row['total_mat'] . '</td>
								</tr>';
		}
		$htmlDetallePO .= '</tbody>
                    </table>';
		$areaComentario = '<textarea class="form-control input-mask" rows="4" disabled>'.utf8_decode($comentario).'</textarea>';			
		return array($htmlDetallePO, $areaComentario) ;
		
	}
	
	function getTablaDetalleRegMo($id_solicitud) {
		$ListaDetallePO = $this->m_control_presupuestal->getDataSolicitudRegMo($id_solicitud);
		$htmlDetallePO  = null;
		$comentario     = null;
		
		$htmlDetallePO .= '<table id="tbDetalleSolicitud" class="table table-bordered">
							<thead class="thead-default">
								<tr>
									<th>CODIGO</th>
									<th>DESCRIPCION</th>
									<th>BAREMO</th>
									<th>COSTO</th>
									<th>CANTIDAD NUEVA</th>
									<th>TOTAL NUEVO</th>
								</tr>
							</thead>
							<tbody>';

		foreach ($ListaDetallePO as $row) {
			$comentario = $row['comentario_reg'];
			$htmlDetallePO .= ' <tr>
									<th>' . $row['codigo']. '</th>
									<td>' . utf8_decode($row['descripcion']) . '</td>
									<td>' . $row['baremo'] . '</td>
									<td>' . $row['costo'] . '</td>
									<td style="background:#FAB8AA">' . $row['cantidad_final'] . '</td>
									<td style="background:#FAB8AA">' . $row['total_partida'] . '</td>
								</tr>';
		}
		$htmlDetallePO .= '</tbody>
                    </table>';
		$areaComentario = '<textarea class="form-control input-mask" rows="4" disabled>'.utf8_decode($comentario).'</textarea>';			
		return array($htmlDetallePO, $areaComentario) ;
		
	}
	
	function getTablaDetalleVr($id_solicitud) {
		$ListaDetallePO = $this->m_control_presupuestal->getDataSolicitudVr($id_solicitud);
		$htmlDetallePO  = null;
		$comentario     = null;
		
		$htmlDetallePO .= '<table id="tbDetalleSolicitud" class="table table-bordered">
							<thead class="thead-default">
								<tr>
									<th>CODIGO MAT</th>
									<th>DESCRIPCION</th>
									<th>COSTO</th>
									<th>CANTIDAD</th>
									<th>TIPO</th>
									<th>CANTIDAD INGRESADO SOL VR</th>
									<th>CANTIDAD FINAL</th>
									<th>TOTAL</th>
								</tr>
							</thead>
							<tbody>';

		foreach ($ListaDetallePO as $row) {
			$style = null;
			if($row['cantidadFin'] != null && $row['cantidadFin'] != '') {
				$style = 'style="background:#FAB8AA"';
			}
			$comentario = $row['comentario_reg'];
			$htmlDetallePO .= ' <tr>
									<th>' . $row['codigo_material']. '</th>
									<td>' . utf8_decode($row['descrip_material']) . '</td>
									<td>' . $row['costo_material'] . '</td>
									<td>' . $row['cantidad_final'] . '</td>
									<td '.$style.'>' . $row['desc_tipo_solicitud'] . '</td>
									<td '.$style.'>' . $row['cantidadIngresado'] . '</td>
									<td '.$style.'>' . $row['cantidadFin'] . '</td>
									<td '.$style.'>' . $row['totalSolVr'] . '</td>
								</tr>';
		}
		$htmlDetallePO .= '</tbody>
                    </table>';
		$areaComentario = '<textarea class="form-control input-mask" rows="4" disabled>'.utf8_decode($comentario).'</textarea>';			
		return array($htmlDetallePO, $areaComentario) ;
		
	}

    //////------- Ivan Joel More Flores -------///////

    public function index_consulta() {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {
            $data['tablaSiom'] = $this->getTablaControlPresupuestal(null, null, null, null);
            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $permisos = $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, 270, 259, ID_MODULO_ADMINISTRATIVO);
//            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_PLAN_DE_OBRA, ID_PERMISO_HIJO_CONSULTAS);

            $data['opciones'] = $result['html'];
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_control_presupuestal/v_bandeja_presupuestal', $data);
            } else {
                redirect('login', 'refresh');
            }
        } else {
            redirect('login', 'refresh');
        }
    }
	
	function validarFile($file){    
		$tipoArchivo = $_FILES['file']['type'];
		$tamañoArchivo = $_FILES['file']['size'];

		if (!((strpos($tipoArchivo, "png") || strpos($tipoArchivo, "jpeg")))){
			return false;
		}
		if ($tamañoArchivo > 600000){
			return false;
		}
		return true;
	}
	/**NUEVO**/
	function getTablaDetalleAdicPqt($id_solicitud) {
	    $ListaDetallePO = $this->m_control_presupuestal->getDataSolicitudAdicPqt($id_solicitud);
	    $htmlDetallePO  = null;
	    $comentario     = null;
	
	    $htmlDetallePO .= '<table id="tbDetalleSolicitud" class="table table-bordered">
							<thead class="thead-default">
								<tr>
									<th>CODIGO</th>
									<th>DESCRIPCION</th>
									<th>BAREMO</th>
									<th>COSTO</th>
									<th>CANTIDAD ACTUAL</th>
									<th>TOTAL ACTUAL</th>
									<th>CANTIDAD NUEVA</th>
									<th>TOTAL NUEVO</th>
								</tr>
							</thead>
							<tbody>';
	
	    foreach ($ListaDetallePO as $row) {
	        $comentario = $row['comentario_reg'];
	        $htmlDetallePO .= ' <tr>
									<th>' . $row['codigo']. '</th>
									<td>' . utf8_decode($row['descripcion']) . '</td>
									<td>' . $row['baremo'] . '</td>
									<td>' . $row['costo'] . '</td>
									<td>' . $row['cantidad_actual'] . '</td>
									<td>' . $row['total_actual'] . '</td>
									<td style="background:#FAB8AA">' . $row['cantidad_final'] . '</td>
									<td style="background:#FAB8AA">' . $row['total_partida'] . '</td>
								</tr>';
	    }
	    $htmlDetallePO .= '</tbody>
                    </table>';
	    $areaComentario = '<textarea class="form-control input-mask" rows="4" disabled>'.utf8_decode($comentario).'</textarea>';
	    return array($htmlDetallePO, $areaComentario) ;
	}
	
	function getTablaDetallePin($id_solicitud) {
		$ListaDetallePO = $this->m_control_presupuestal->getDataSolicitudPin($id_solicitud);
		$htmlDetallePO  = null;
		$comentario     = null;
		$total = 0;
		$totalActualMO = 0;
		$totalActualMAT = 0;
		$htmlDetallePO .= '<table id="tbDetalleSolicitud" class="table table-bordered">
							<thead class="thead-default">
								<tr>
									<th>CODIGO</th>
									<th>DESCRIPCION</th>
									<th>BAREMO</th>
									<th>COSTO</th>
									<th>CANTIDAD ACTUAL</th>
									<th>CANTIDAD NUEVA</th>
									<th>TOTAL ACTUAL MO</th>
									<th>TOTAL ACTUAL MAT</th>
									<th>TOTAL NUEVO</th>
								</tr>
							</thead>
							<tbody>';

		foreach ($ListaDetallePO as $row) {
			$total = $total + $row['total_partida'];
			$totalActualMO = $totalActualMO + $row['total_actual_mo'];
			$totalActualMAT = $totalActualMAT + $row['total_actual_mat'];
			$comentario = $row['comentario_reg'];
			$htmlDetallePO .= ' <tr>
									<th>' . $row['codigo']. '</th>
									<td>' . utf8_decode($row['descripcion']) . '</td>
									<td>' . $row['baremo'] . '</td>
									<td>' . $row['costo'] . '</td>
									<td>' . $row['cantidad_actual'] . '</td>
									<td style="background:#FAB8AA">' . $row['cantidad_final'] . '</td>
									<td>' . $row['total_actual_mo'] . '</td>
									<td>' . $row['total_actual_mat'] . '</td>
									<td style="background:#FAB8AA">' . $row['total_partida'] . '</td>
								</tr>';
		}
		$htmlDetallePO .= '</tbody>
							<tr>
								<th></th>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td>'.$totalActualMO.'</td>
								<td>'.$totalActualMAT.'</td>
								<td style="background:#FAB8AA">' . $total . '</td>
							</tr>
                    </table>';
		$areaComentario = '<textarea class="form-control input-mask" rows="4" disabled>'.utf8_decode($comentario).'</textarea>';			
		return array($htmlDetallePO, $areaComentario) ;
	}
}
