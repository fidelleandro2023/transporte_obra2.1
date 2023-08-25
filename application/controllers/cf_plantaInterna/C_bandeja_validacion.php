<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_bandeja_validacion extends CI_Controller {
    private $_idZonal  = null;
    private $_itemPlan = null;
    function __construct(){
        parent::__construct();
        $this->load->model('mf_ejecucion/M_pendientes');
        $this->load->model('mf_ejecucion/M_generales');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_ejecucion/M_actualizar_porcentaje');   
        $this->load->model('mf_plantaInterna/M_aprobacion_interna');
        $this->load->model('mf_plantaInterna/M_plantaInterna'); 
		$this->load->model('mf_pqt_terminado/m_pqt_terminado');
        $this->load->model('mf_control_presupuestal/m_control_presupuestal');
		$this->load->model('mf_servicios/M_integracion_sirope');
        $this->load->helper('url');
        $this->load->library('lib_utils');
        $this->load->library('zip');
        $this->load->library('table');
    }

    function index() {
        $logedUser = $this->session->userdata('usernameSession');
        if($logedUser != null){
            $data['listaSubProy']  = $this->m_utils->getAllSubProyecto(ID_TIPO_PLANTA_INTERNA);
            $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
            $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
            $data['tablaBandejaValidacion'] = $this->getBandejaValidacion('', '');
            $data['title'] = 'BANDEJA DE VALIDACI&Oacute;N';
            $permisos =  $this->session->userdata('permisosArbolTransporte');
            #$result   = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_PLANTA_INTERNA, ID_PERMISO_HIJO_BANDEJA_VALIDACION);
            $result   = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_PLANTA_INTERNA, ID_PERMISO_HIJO_BANDEJA_VALIDACION, ID_MODULO_PAQUETIZADO);
            $data['opciones'] = $result['html'];
            if($result['hasPermiso'] == true){
                $this->load->view('vf_plantaInterna/V_bandeja_validacion',$data);
            }else{
                redirect('login','refresh');
            }
        }else{
            redirect('login','refresh');
        }
    }
    

    function getBandejaValidacion($itemplan, $idSubProyecto) {
        $arrayEstadoPlan = array(9, 4);
        $arrayData = $this->M_pendientes->getListaPendiente($itemplan, NULL, $idSubProyecto, NULL, ID_ESTADO_PRE_LIQUIDADO, ID_ESTADO_PRE_LIQUIDADO, ID_TIPO_PLANTA_INTERNA, $arrayEstadoPlan);
        $btnTerminar = null;
		$subproNeedSiropePIN = array(658, 653);
        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr style="color: white ; background-color: #3b5998" width="10%">
                            <th>Acci&oacute;n</th>
                            <th>ItemPlan</th>
                            <th>Subproyecto</th>
                            <th>Indicador</th>
                            <th>Estado</th>
                            <th>Central</th>  
                            <th>F. PreLiquidaci&oacute;n</th>  
                            <th>F. Validaci&oacute;n</th>  
                            <th>F. Fin Prev</th>
                            <th>Compromiso</th>
                            <th>F. Inicio Prev</th>
                            <th>eecc</th>
                            <th>Jefatura</th>                 
                        </tr>
                    </thead>                    
                    <tbody>';
    foreach($arrayData->result() as $row) {
		$btnTerminar = '';
        if($row->fechaPrevEjec){
            $fasig=explode("-",$row->fechaPrevEjec);
            $rfecha=$fasig[2]."/".$fasig[1]."/".$fasig[0];
       }else{
           $rfecha="";
       }

       if($row->fechaInicio){    
            $nuevafecha = strtotime ( '+30 day' , strtotime ( $row->fechaInicio ) ) ;
            $nuevafecha = date ( 'd-m-Y' , $nuevafecha );    
        }else{
            $nuevafecha = "";    
        }
        $botonZipEvidencia = ' <a data-toggle="tooltip" data-trigger="hover" data-original-title="descarga zip de las evidencias" data-item_plan="'.$row->itemPlan.'" style="cursor:pointer" onclick="zipItemPlan($(this));"><i class="zmdi zmdi-hc-2x zmdi zmdi-download"></i></a>';
        if($row->idEstadoPlan == ID_ESTADO_PRE_LIQUIDADO && ((in_array($row->idSubProyecto, $subproNeedSiropePIN)) ? $row->has_sirope == 1  : true) /*&& $row->has_evidencia === null*/) {        
            $btnTerminar = '<a data-toggle="tooltip" data-trigger="hover" data-original-title="Terminar Obra"  data-itemplan="'.$row->itemPlan.'" data-id_subproyecto="'.$row->idSubProyecto.'" data-tipo_planta="'.ID_TIPO_PLANTA_INTERNA.'" 
                            data-has_evidencia = "'.$row->has_evidencia.'" onclick="validarObra($(this));"><i style="color:'.($row->has_evidencia === '1' ? 'green' : '').'" class="zmdi zmdi-hc-2x zmdi-check-circle"></i>
                            </a>';
        }
        
		$btnRechazar = '<a data-toggle="tooltip" data-trigger="hover" data-original-title="Rechazar" title="Rechazar" data-itemplan="'.$row->itemPlan.'" data-id_subproyecto="'.$row->idSubProyecto.'" data-tipo_planta="'.ID_TIPO_PLANTA_INTERNA.'" onclick="rechazarItemplan($(this));"><i class="zmdi zmdi-hc-2x zmdi-delete"></i></a>';
		
        $html.= '<tr>
                    <td>
                    <a data-toggle="tooltip" data-trigger="hover" data-original-title="Cotizaci&oacute;n" href="#" class="ver_ptr" data-itemplan="'.$row->itemPlan.'" data-id_estado_plan="'.$row->idEstadoPlan.'" data-id_subproyecto="'.$row->idSubProyecto.'" onclick="openModalPTR($(this));"><i class="zmdi zmdi-hc-2x zmdi-money-box"></i></a>
                    '.$botonZipEvidencia.$btnTerminar.$btnRechazar.'
                    
                    </td>
                    <td>'.$row->itemPlan.'</td> 
                    <td>'.$row->subProyectoDesc.'</td>                
                    <td>'.$row->indicador.'</td>
                    <td>'.$row->estadoPlanDesc.'</td>
                    <td>'.$row->tipoCentralDesc.'</td>
                    <td>'.$row->fechaPreLiquidacion.'</td>
                    <td>'.$row->fechaEjecucion.'</td>
                    <td>'.$rfecha.'</td>
                    <td>'.$nuevafecha.'</td>
                    <td>'.$row->fechaInicio.'</td>
                    <td>'.$row->empresaColabDesc.'</td>
                    <td>'.$row->zonalDesc.'</td>
                </tr>';
    }
        $html .='   </tbody>
            </table>';

    return utf8_decode($html);
    }
    
    
    function ejecValidacion() {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $this->db->trans_begin();
            $itemplan = $this->input->post('itemplan');
			
			$idUsuario = $this->session->userdata('idPersonaSession');
			
			$arrayDataUpdatePO = array(
                'fecha_valida'  	=> $this->fechaActual(),
                'id_usuario_valida' => $idUsuario,
                'estado'     		=> 1
             );
			$data = $this->m_utils->insertEditPoEnDetallePo($itemplan, $arrayDataUpdatePO);
			if($data['error'] == EXIT_ERROR) {
                throw new Exception($data['msj']);
            }
			
            /*$arrayData = array(
			    'has_evidencia' => '0'
			);
			$data = $this->m_utils->simpleUpdatePlanObra($itemplan, $arrayData);
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
			 
			$estadoplan = 22;

            $flg = $this->m_utils->registrarLogPlanObra($arrayDataLog);

            if($flg == 0) {
                throw new Exception('No se registro el log');
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
			
			$estadoCerti = 5;
			$fechaActual = $this->m_utils->fechaActual();
            if($dataFlgEdic['flg_solicitud_edic'] == 1) {// SI ES DIFERENTE AL TOTAL SE GENERA UNA EDICION
				$cod_solicitud = $this->m_utils->getCodSolicitudOC();
				
				if($infoItemplan['flg_opex']==2) {// POR PEDIDO PARA OPEX NO SE GENERA EDICION Y CERTI AL MISMO TIEMPO
					
					$infoCreateSol = $this->m_pqt_terminado->getInfoSolCreacionByItemOPEX($itemplan);//getinfo solicitud de creacion Opex
					
					$rsp = $this->m_utils->generarSolicitudCertiEdicionOPEX($itemplan, $idUsuario, $dataFlgEdic['costo_mo'], 2, $infoCreateSol['idCuetnaOpex']);
					
					if($rsp == null || $rsp == 6) {
						throw new Exception('No se genero las Solicitud OC Opex.');
					}
				} else {
					if($infoItemplan['flg_opex']==1) {
						if($arrayData['pep1'] != null && $arrayData['pep1'] != '') {
							
							$this->m_utils->actualizarEstadoSolicitudCapexByItemplan($itemplan, 2, 3); /*cancelo las solicitud edicion pdte */	
							$rsp = $this->m_utils->generarSolicitudCertiAnulEdiOC($itemplan, 'PLAN', 2, $dataFlgEdic['costo_mo'], $arrayData['pep1'], $idUsuario);
						
							if($rsp != 1) {
								$data['error'] = EXIT_ERROR;
								throw new Exception('No se genero la solicitud de oc edicion, verificar.');
							}
							$estadoplan = 4;
							// $data = $this->m_utils->insertSolicitudOcEdi($arrayData, $fechaActual, $cod_solicitud, $dataFlgEdic['costo_mo'], $itemplan);
						
							// if($data['error'] == EXIT_ERROR) {
								// throw new Exception('No se genero la solicitud de edicion, verificar el presupuesto de la pep.');
							// }
						}
					}
					
				}
            }

			if($infoItemplan['flg_opex']==1) {
				if($arrayData['pep1'] != null && $arrayData['pep1'] != '') {
					$estadoCerti   = 4;
					#$estadoplan = 4;
					$rsp = $this->m_utils->generarSolicitudCertiAnulEdiOC($itemplan, 'PLAN', 3, $dataFlgEdic['costo_mo'], $arrayData['pep1'], $idUsuario);

                    if($rsp != 1) {
                        $data['error'] = EXIT_ERROR;
                        throw new Exception('No se genero la solicitud de oc certificacion, verificar.');
                    }
					// $cod_solicitud = $this->m_utils->getCodSolicitudOC();
					// $data = $this->m_utils->insertSolicitudOcCerti($arrayData, $fechaActual, $cod_solicitud, $itemplan, $dataFlgEdic['costo_mo'], $estadoCerti);	
					
					// if($data['error'] == EXIT_ERROR) {
						// throw new Exception('No se genero la solicitud de certificacion.');
					// }
				} else {
					throw new Exception('No se encontro una pep asociado, verificar.');
				}
			} else {
				$infoCreateSol = $this->m_pqt_terminado->getInfoSolCreacionByItemOPEX($itemplan);
				$rsp = $this->m_utils->generarSolicitudCertiEdicionOPEX($itemplan, $idUsuario, $dataFlgEdic['costo_mo'], 3, $infoCreateSol['idCuetnaOpex']);
				
				if($rsp == null || $rsp == 6) {
					throw new Exception('No se genero las Solicitud OC Opex.');
				}
			}
			
			$data = $this->M_plantaInterna->validaItemplan($itemplan, $this->fechaActual(), $estadoplan);

            if($data['error'] == EXIT_ERROR) {
                throw new Exception('No se valido');
            }
			
            $this->db->trans_commit();
        }catch(Exception $e){
            $this->db->trans_rollback();
			$data['error'] = EXIT_ERROR;
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function filtrarTablaValid() {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $itemplan      = $this->input->post('itemplan');
            $idSubProyecto = $this->input->post('idSubProyecto');

            $itemplan      = ($itemplan == '') ? NULL : $itemplan;
            $idSubProyecto = ($idSubProyecto == '') ? NULL : $idSubProyecto;
            $data['tablaValid'] = $this->getBandejaValidacion($itemplan, $idSubProyecto);
        }catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
	
	function rechazarItemplanValid() {
		$data['error']    = EXIT_ERROR;
        $data['msj']      = null;
		try {
			$itemplan = $this->input->post('itemplan');
		
			$idUsuario = $this->session->userdata('idPersonaSession');
			
			$arrayData = array(
									'usu_upd'      => $idUsuario,
									'fecha_upd'    => $this->fechaActual(),
									'descripcion'  => 'SE RECHAZO Y VUELVE A OBRA',
									'idEstadoPlan' => 3
								);
			$data = $this->m_utils->simpleUpdatePlanObra($itemplan, $arrayData);
			
			if($data['error'] == EXIT_ERROR) {
				throw new Exception($data['msj']);
			}
		} catch(Exception $e) {
			$data['msj'] = $e->getMessage();
		}
		echo json_encode(array_map('utf8_encode', $data));
	}
    
    function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone','America/Lima');
        setlocale(LC_TIME, "es_ES","esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }

    function habilitarCargaEvidencia() {
		$data['error']    = EXIT_ERROR;
        $data['msj']      = null;
		try {
			$itemplan = $this->input->post('itemplan');
			$idUsuario = $this->session->userdata('idPersonaSession');
			
			$arrayData = array(
			    'has_evidencia' => '0'
			);
			$data = $this->m_utils->simpleUpdatePlanObra($itemplan, $arrayData);
			if($data['error'] == EXIT_ERROR) {
				throw new Exception($data['msj']);
			}
            $data['tablaValid'] = $this->getBandejaValidacion($itemplan, null);
		} catch(Exception $e) {
			$data['msj'] = $e->getMessage();
		}
		echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getPtrByItemplan() {
        $itemplan      = $this->input->post('itemplan');
        $idEstadoPlan  = $this->input->post('idEstadoPlan');
        $tb = $this->getTablaConsultaPTR($itemplan, $idEstadoPlan); 

        $data['tablaConsultaPtr'] = $tb;  

        echo json_encode($data);
    }

    function getTablaConsultaPTR($itemplan, $idEstadoPlan) {
        $tb = null;
        $arrayPtr = $this->m_utils->getPtrByItemplanForBanValidacion($itemplan);
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
	
	   function generarEdicionMasivoManual() {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            
            $arrayItemplan = array('23-5620500004','23-7720500002');
			$idUsuario = 2341;
			$fechaActual = $this->m_utils->fechaActual();
			$this->db->trans_begin();
			
			foreach($arrayItemplan as $itemplan){
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

				
				if($dataFlgEdic['flg_solicitud_edic'] == 1) {// SI ES DIFERENTE AL TOTAL SE GENERA UNA EDICION
					
					if($infoItemplan['flg_opex'] == 2) {// POR PEDIDO PARA OPEX NO SE GENERA EDICION Y CERTI AL MISMO TIEMPO
						
						$infoCreateSol = $this->m_pqt_terminado->getInfoSolCreacionByItemOPEX($itemplan);//getinfo solicitud de creacion Opex
						
						$rsp = $this->m_utils->generarSolicitudCertiEdicionOPEX($itemplan, $idUsuario, $dataFlgEdic['costo_mo'], 2, $infoCreateSol['idCuetnaOpex']);
						if($rsp == null || $rsp == 6) {
							throw new Exception('No se genero las Solicitud OC Opex.');
						}
					} else if($infoItemplan['flg_opex'] == 1) {
						
						if($arrayData['pep1'] != null && $arrayData['pep1'] != '') {
							$this->m_utils->actualizarEstadoSolicitudCapexByItemplan($itemplan, 2, 3); /*cancelo las solicitud edicion pdte */	
							$rsp = $this->m_utils->generarSolicitudCertiAnulEdiOC($itemplan, 'PLAN', 2, $dataFlgEdic['costo_mo'], $arrayData['pep1'], $idUsuario);
							if($rsp != 1) {
								$data['error'] = EXIT_ERROR;
								throw new Exception('No se genero la solicitud de oc edicion, verificar.');
							}else{
								$data['error'] = EXIT_SUCCESS;
								$data['msj'] = 'Se genero correctamente la solicitud de edicion.';
							}
						}	
					}
				}
			}
			
			if($data['error'] == EXIT_SUCCESS){
				$this->db->trans_commit();
			}
            
        }catch(Exception $e){
            $this->db->trans_rollback();
			$data['error'] = EXIT_ERROR;
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }
}