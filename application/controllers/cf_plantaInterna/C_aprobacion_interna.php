<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * 
 * @author CRISTOBAL ARTETA .
 * 18/01/2018
 *
 */
class C_aprobacion_interna extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_plantaInterna/M_aprobacion_interna');
        $this->load->model('mf_cotizacion/m_validar_cotizacion'); //czavala
        $this->load->model('mf_reportes_v/m_itemplan_ptr');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index()
    {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {
            $data['listaEECC']    = $this->m_utils->getAllEECC();
            $data['listaZonal']   = $this->m_utils->getAllZonalGroup();
            $data['listaSubProy'] = $this->m_utils->getAllSubProyecto(ID_TIPO_PLANTA_INTERNA);
            $data['listUsuarios'] = $this->m_utils->getUsuarioRegistroItemplanPIN();
            $data['tablaasigGrafoInterna'] = $this->makeHTLMTablaasignarGrafoInterna($this->M_aprobacion_interna->getPtrToLiquidacion('', '', '', 'SI', '', '', '', NULL, '01'));
            $data['title'] = 'BANDEJA DE APROBACI&Oacute;N';
            $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
            $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
            $permisos =  $this->session->userdata('permisosArbolTransporte');
            #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_PLANTA_INTERNA, ID_PERMISO_HIJO_BANDEJA_APROBACION_PLANTA_INTERNA);
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_PLANTA_INTERNA, ID_PERMISO_HIJO_BANDEJA_APROBACION_PLANTA_INTERNA, ID_MODULO_PAQUETIZADO);
            $data['opciones'] = $result['html'];
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_plantaInterna/V_aprobacion_interna', $data);
            } else {
                redirect('login', 'refresh');
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    /*
    public function asignarGrafoInterna(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $ptr     = $this->input->post('id_ptr');
            $vale_re = $this->input->post('vale_reserva');
            $itemP   = $this->input->post('itemPl');

            $idEstadoPlan = $this->m_utils->getEstadoPlanByItemplan($itemP);
            $estado     = $this->input->post('estado');
            $tipo_po    = $this->input->post('tipo_po');
            $needValidation = $this->M_aprobacion_interna->hasMatPinValidationByItemplanAprob($itemP);

			$idUsuario = $this->session->userdata('idPersonaSession');
			
			if($idUsuario == null || $idUsuario == '') {
				throw new Exception('Su session a caducado, refrescar la pagina por favor.');
			}
			
            if($tipo_po == 2){//tipo MO REALIZA TODO LO QUE HACIA 
                if ($estado == 1){//APROBADO
					$infoItemplan = $this->m_utils->getInfoItemplanWhitSubProyecto($itemP);//czavala
					if($infoItemplan['flg_opex']==2){//OPEX                  
                        $costoMo = $this->M_aprobacion_interna->getCostoMoPin($ptr);                        
                        if($costoMo == null || $costoMo == '') {
                            throw new Exception('No se ingreso la cotizacio PIN.');
                        }
                        $counOpex = $this->m_validar_cotizacion->countOpex($infoItemplan['idSubProyecto']);
                        if ($counOpex > 0) {
                            $dataOpex = $this->m_validar_cotizacion->getOpex($infoItemplan['idSubProyecto'], $costoMo);
                            if (count($dataOpex) != 1) {
                                throw new Exception('Cuenta OPEX sin MONTO DISPONIBLE');
                            }
                        } else {
                            throw new Exception('No tiene cuenta OPEX registrada');
                        }        
                        
                        $dataPlanobra = array("itemplan"                    => $itemP,
                                                "costo_unitario_mo"         => $costoMo,
                                                "costo_unitario_mo_crea_oc" => $costoMo
                                            );
						log_message('error', 'pi:>1');
                        $data = $this->M_aprobacion_interna->updateDetalleProductoOPEX($ptr, $itemP, $vale_re, ESTADO_02_TEXTO, ESTADO_01_TEXTO, '2', FLG_APROBADO, $dataPlanobra, $dataOpex[0]->idOpex, $idUsuario, $costoMo);                        
                          log_message('error', 'pi:>2');
						  log_message('error', 'pi:>2'.print_r($data, true));
                    }else{//CAPEX
						$data = $this->M_aprobacion_interna->updateDetalleProducto($ptr, $itemP, $vale_re, ESTADO_02_TEXTO, ESTADO_01_TEXTO, '2', FLG_APROBADO);
					}
                    $arrayDataLog = array(
                        'tabla'            => 'Planta Interna',
                        'actividad'        => 'ptr Aprobada',
                        'itemplan'         => $itemP,
                        'ptr'              => $ptr,
                        'fecha_registro'   => $this->fechaActual(),
                        'id_usuario'       => $this->session->userdata('idPersonaSession'),
                        'tipoPlanta'       => 2
                     );
                    $this->m_utils->registrarLogPlanObra($arrayDataLog);
                }else{           //RECHAZADO                             
                    $data = $this->M_aprobacion_interna->updateDetalleProducto($ptr, $itemP, $vale_re, ESTADO_01_TEXTO, ESTADO_01_TEXTO, '6', FLG_RECHAZADO);
                    if($needValidation  ==  0){//no necsita po mat
                        $data = $this->m_utils->updateEstadoPlanObra($itemP, ESTADO_PLAN_PRE_DISENO);    
                    }
                    $arrayDataLog = array(
                        'tabla'            => 'Planta Interna',
                        'actividad'        => 'ptr Rechazada',
                        'itemplan'         => $itemP,
                        'ptr'              => $ptr,
                        'fecha_registro'   => $this->fechaActual(),
                        'id_usuario'       => $this->session->userdata('idPersonaSession'),
                        'tipoPlanta'       => 2
                     );
                    $this->m_utils->registrarLogPlanObra($arrayDataLog);
                }
            }else if($tipo_po == 1){//tipo Material
                if ($estado == 1){//aprobar
                    $arrayUpdate = array(
                        "estado_po" => PO_PREAPROBADO
                    );
                    $data = $this->M_aprobacion_interna->aprobOCanlPoMatPI($ptr, $itemP, $arrayUpdate, $this->fechaActual(), PO_PREAPROBADO);
                    if($data['error']==EXIT_ERROR){
                        throw new Exception('Ocurrio un error al pre aprobar la Po. comuniquese con Soporte.');
                    }
                }else{//RECHAZAR
                    $arrayUpdate = array(
                        "estado_po" => PO_CANCELADO
                    );
                    $data = $this->M_aprobacion_interna->aprobOCanlPoMatPI($ptr, $itemP, $arrayUpdate, $this->fechaActual(), PO_CANCELADO);
                    if($data['error']==EXIT_ERROR){
                        throw new Exception('Ocurrio un error al cancelar la Po. comuniquese con Soporte.');
                    }
                }
            }

            $SubProy = $this->input->post('subProy');
            $eecc    = $this->input->post('eecc');
            $zonal   = $this->input->post('zonal');
         // $itemPlan = $this->input->post('item');
            $mesEjec = $this->input->post('mes');
            $area = $this->input->post('area');                                                                                        
            $data['tablaasigGrafoInterna'] = $this->makeHTLMTablaasignarGrafoInterna($this->M_aprobacion_interna->getPtrToLiquidacion($SubProy,$eecc,$zonal,'SI',$mesEjec,$area,'',null, '01'));
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
	
	*/
    //cambio czavala 06.10.2020
    public function asignarGrafoInterna()
    {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try {
            $ptr     = $this->input->post('id_ptr');
            $vale_re = $this->input->post('vale_reserva');
            $itemP   = $this->input->post('itemPl');

            $idEstadoPlan = $this->m_utils->getEstadoPlanByItemplan($itemP);
            $estado     = $this->input->post('estado');
            $tipo_po    = $this->input->post('tipo_po');
            $needValidation = $this->M_aprobacion_interna->hasMatPinValidationByItemplanAprob($itemP);

            $idUsuario = $this->session->userdata('idPersonaSession');

            if ($idUsuario == null || $idUsuario == '') {
                throw new Exception('Su session a caducado, refrescar la pagina por favor.');
            }

            $array_deuda_angela = array(
                '22-6421500001',
                '22-5321500003',
                '22-5320200001',
                '22-6421300001',
                '22-6421300002',
                '22-5321500002',
                '22-5320200005',
                '22-5321300004',
                '22-5321300005',
                '22-5320700009',
                '22-5320700010',
                '22-5320700011',
                '22-5320700001',
                '22-5320700002',
                '22-5320700003',
                '22-5320700004',
                '22-5320700005',
                '22-6410900001',
                '22-5320200004',
                '22-5321300003',
                '22-6411100001',
                '22-6411100002',
                '22-6411200001',
                '22-5320700006',
                '22-5320700007',
                '22-5320700008',
                '22-5320200003',
                '22-5320200002',
                '22-5321300001',
                '22-5321300002',
                '22-6421300003',
                '22-5421300001',
                '22-5420200001',
                '22-5520500001',
                '22-5520500002',
                '22-5620500002',
                '22-5620600001',
                '22-5620500001',
                '22-5620500003',
                '22-5721500002',
                '22-5720600001',
                '22-5720500001',
                '22-5720500002',
                '22-5721500001',
                '22-5721500003',
                '22-5720500003'
            );
			_log($tipo_po);
			_log($estado);

            if ($tipo_po == 2) { //tipo MO REALIZA TODO LO QUE HACIA	
                if ($estado == 1) { //APROBADO

                    $itemplan = $itemP;

                    //obtiene datos de itemplan
                    $infoItemplan = $this->m_utils->getInfoItemplanWhitSubProyecto($itemP); //czavala


                    if ($infoItemplan['flg_opex'] == 1) {
                        $countTipoSoliCrea = $this->m_utils->getCountTipoSolicitudCapex($itemplan, 1, 1);

                        if ($countTipoSoliCrea > 0) {
                            throw new Exception('Ya tiene una solicitud de creacion pendiente.');
                        }

                        $infoPlan = $this->m_validar_cotizacion->getInfoItemplan($itemplan);
                        $infoCotizacion = $this->m_validar_cotizacion->getInfoPtrxactividad($itemplan);
                        $monto_mo = 0;
                        $monto_mat = 0;
                        if ($infoCotizacion != null) {
                            $monto_mo = $infoCotizacion['monto_po'];
                        } else {
                            $monto_mo = 0;
                            $monto_mat = 0;
                        }

                        if ($infoItemplan['flg_reg_item_capex_opex'] == 1) { // TRAIDO LA PEP DESDE PLANOBRA
                            $dataPoPe = $this->m_validar_cotizacion->getPlanObraSap($itemplan);

                            if ($dataPoPe['pep1'] == null || $dataPoPe['pep1'] == '') {
                                throw new Exception('No se encontro la pep registrada en itemplan.');
                            }
                            $pep1_co = $dataPoPe['pep1'];
                            $monto_mo  = $this->M_aprobacion_interna->getCostoMoPin($ptr);
                            $monto_mat = 0;
                            $infoPlan = $this->m_utils->getInfoItemplan($itemplan);

                            $countPres = $this->m_utils->getCountPresupuestoSap($pep1_co, $monto_mo);
                            //$montopep         = $dataPoPe['monto_temporal'];
                            //$monto_actulizado = $montopep - $monto_mo;


                            // $margen = $this->m_validar_cotizacion->getMargen($pep1_co);
                            // $guia = $margen['margen'];


                            $pep1_co = $pep1_co;
                            // $pep2_co = $pep1_co;


                            $pep1 = $pep1_co;
                            $pep2 = $infoItemplan['pep2'];


                            // registro OCe

                            $codigo_solicitud = $this->m_utils->getNextCodSolicitud();
                            if ($codigo_solicitud == null) {
                                throw new Exception('Hubo problemas al obtener el codigo de solicitud OC, vuelva a intentarlo o genere un ticket CAP');
                            }

                            if ($countPres == 0) {
                                $dataPlanobra = array(
                                    "itemplan"                      => $itemplan,
                                    "costo_unitario_mo"             => $monto_mo,
                                    "costo_unitario_mat"             => $monto_mat,
                                    "solicitud_oc"                     => $codigo_solicitud,
                                    "estado_sol_oc"                 => 'PENDIENTE',
                                    "fec_registro_sol_creacion_oc"     => $this->fechaActual(),
                                    "flg_presupuesto_oc"             => 1,
                                    "fecha_presupuesto_oc"          => $this->fechaActual(),
                                    "comentario_presupuesto_oc"     => 'FALTA DE PRESUPUESTO APROB.',
                                    "idMotivoQuiebre"     => 91,
                                    "comentarioQuiebre"     => 'FALTA DE PRESUPUESTO APROB.'
                                );

                                $this->m_utils->insertLogBandejaQuiebre([
                                    'usuario_log' => $this->session->userdata('idPersonaSession'),
                                    'fecha_log' => _fechaActual(),
                                    'tipo_log' => 'RECHAZADO',
                                    'modulo_log' => 'MAS DESPLIEGUE',
                                    'itemplan' => $itemplan,
                                    'codigo_solicitud' => $codigo_solicitud,
                                    'motivo' => 'FALTA DE PRESUPUESTO APROB.',
                                    'comentario' => 'FALTA DE PRESUPUESTO APROB.',
                                ]);
                                
                                $this->m_utils->updatePlanObraQuiebre([
                                    'itemplan' => $itemplan,
                                    'codigo_solicitud' => $codigo_solicitud,
                                    'emisor' => 'HUBLEAN',
                                    'usuario_rechazo' => $this->session->userdata('idPersonaSession'),
                                    'fecha_rechazo' => _fechaActual(),
                                    'usuario_liberacion' => NULL,
                                    'fecha_liberacion' => NULL,
                                ]);
                            } else {
								
                                $dataPlanobra = array(
                                    "itemplan"  => $itemplan,
                                    "costo_unitario_mo"             => $monto_mo,
                                    "costo_unitario_mat"             => $monto_mat,
                                    "solicitud_oc"                     => $codigo_solicitud,
                                    "estado_sol_oc"                 => 'PENDIENTE',
                                    "fec_registro_sol_creacion_oc"     => $this->fechaActual()
                                );
                            }


                            $solicitud_oc_creacion = array(
                                'codigo_solicitud' => $codigo_solicitud,
                                'idEmpresaColab' => $infoPlan['idEmpresaColab'],
                                'estado' => 1, //pendiente
                                'fecha_creacion' => $this->fechaActual(),
                                'idSubProyecto' => $infoPlan['idSubProyecto'],
                                'plan' => 'OC',
                                'pep1' => $pep1,
                                'pep2' => $pep2,
                                'estatus_solicitud' => 'NUEVO',
                                'tipo_solicitud' => 1 //creacion
                            );

                            $item_x_sol = array(
                                'itemplan' => $itemplan,
                                'codigo_solicitud_oc' => $codigo_solicitud,
                                'costo_unitario_mo' => $monto_mo
                            );

                            // $dataSapDetalle = array('monto_temporal' => $monto_tmp_final,
                            // 'pep1' => $pep1
                            // );
                            $data = $this->M_aprobacion_interna->aprobarCotizacionPI($dataPlanobra, $solicitud_oc_creacion, $item_x_sol);
                            if ($data['error'] == EXIT_ERROR) {
                                throw new Exception($data['msj']);
                            }
                        } else {
                            $infoBobp = $this->m_validar_cotizacion->getPEPSBolsaPepByItemplanConsulta($itemplan);

                            $pep1_co = "";
                            $pep2_co = "";
                            $accedio_contador = 0;
                            $margen_contador = 0;

                            if (count($infoBobp) > 0) {
                                foreach ($infoBobp as $pep) {
                                    if (in_array($itemplan, $array_deuda_angela)    ||    $infoItemplan['flg_reg_item_capex_opex'] != 1) {
                                        $montopep         = $pep->monto_temporal;
                                        $monto_actulizado = $montopep - $monto_mo;
                                        $pep1_co = $pep->pep1;

                                        $margen = $this->m_validar_cotizacion->getMargen($pep1_co);

                                        $guia = $margen['margen'];

                                        if ($guia ==    '' &&    $infoItemplan['flg_reg_item_capex_opex'] != 1) {
                                            throw new Exception('No se asigno un MARGEN DE MANIOBRA');
                                        }
                                        // throw new Exception('si me queda'.$montopep.'--'.$monto_mo.'----'.$monto_actulizado.'--'.$guia );
                                        if ($monto_actulizado >= $guia    ||    in_array($itemplan, $array_deuda_angela)) {
                                            $data['pep_estado'] = "Se Encontro una PEP que cumple con el monto";
                                            $data['pep_encontrada'] = $montopep;
                                            $data['monto_pep'] = $pep->monto_temporal;

                                            if ($infoItemplan['flg_reg_item_capex_opex'] != 1) {
                                                $estadoBobpep = $this->m_validar_cotizacion->gettTipoCorrelativo($pep1_co);

                                                if ($estadoBobpep['tipo'] == 'SIN TIPO') {
                                                    throw new Exception('No se asigno a Pep un tipo: CORRELATIVO O FIJO');
                                                }

                                                if ($estadoBobpep['tipo'] == 'CORRELATIVO') {
                                                    $iter = $this->m_validar_cotizacion->getEstadoCorrelativo($pep1_co);

                                                    $pep2_ = $iter['correlativo'] + 1;
                                                    $pep2 = str_pad($pep2_, 3, '0', STR_PAD_LEFT);

                                                    $pep2_co = "-" . $pep2;

                                                    $actualizarPlanObra = $this->m_validar_cotizacion->actualizarPlanObra($itemplan, $pep2, $pep1_co, $pep2_);
                                                } else {
                                                    $actualizarBob = $this->m_validar_cotizacion->actualizarBobPep($itemplan, $pep1_co);

                                                    $pep2_co = "-001";
                                                }
                                            } else {
                                                $pep1_co = $pep1_co;
                                                $pep2_co = $pep1_co;
                                            }


                                            // consultar actualizar monto
                                            $this->m_validar_cotizacion->actualizarMontoSap($monto_actulizado, $pep1_co);

                                            $monto_mo  = $this->M_aprobacion_interna->getCostoMoPin($ptr);
                                            $monto_mat = 0;
                                            $infoPlan = $this->m_utils->getInfoItemplan($itemplan);

                                            $pep1 = $pep1_co;
                                            $pep2 = $pep1_co . $pep2_co;



                                            // registro OCe

                                            $codigo_solicitud = $this->m_utils->getNextCodSolicitud();
                                            if ($codigo_solicitud == null) {
                                                throw new Exception('Hubo problemas al obtener el codigo de solicitud OC, vuelva a intentarlo o genere un ticket CAP');
                                            }

                                            $countPres = $this->m_utils->getCountPresupuestoSap($pep1_co, $monto_mo);

                                            if ($countPres == 0) {
                                                $dataPlanobra = array(
                                                    "itemplan"                      => $itemplan,
                                                    "costo_unitario_mo"             => $monto_mo,
                                                    "costo_unitario_mat"             => $monto_mat,
                                                    "solicitud_oc"                     => $codigo_solicitud,
                                                    "estado_sol_oc"                 => 'PENDIENTE',
                                                    "fec_registro_sol_creacion_oc"     => $this->fechaActual(),
                                                    "flg_presupuesto_oc"             => 1,
                                                    "fecha_presupuesto_oc"          => $this->fechaActual(),
                                                    "comentario_presupuesto_oc"     => 'FALTA DE PRESUPUESTO APROB.',
                                                    "idMotivoQuiebre"     => 91,
                                                    "comentarioQuiebre"     => 'FALTA DE PRESUPUESTO APROB.'
                                                );

                                                $this->m_utils->insertLogBandejaQuiebre([
                                                    'usuario_log' => $this->session->userdata('idPersonaSession'),
                                                    'fecha_log' => _fechaActual(),
                                                    'tipo_log' => 'RECHAZADO',
                                                    'modulo_log' => 'MAS DESPLIEGUE',
                                                    'itemplan' => $itemplan,
                                                    'codigo_solicitud' => $codigo_solicitud,
                                                    'motivo' => 'FALTA DE PRESUPUESTO APROB.',
                                                    'comentario' => 'FALTA DE PRESUPUESTO APROB.',
                                                ]);
                                                
                                                $this->m_utils->updatePlanObraQuiebre([
                                                    'itemplan' => $itemplan,
                                                    'codigo_solicitud' => $codigo_solicitud,
                                                    'emisor' => 'HUBLEAN',
                                                    'usuario_rechazo' => $this->session->userdata('idPersonaSession'),
                                                    'fecha_rechazo' => _fechaActual(),
                                                    'usuario_liberacion' => NULL,
                                                    'fecha_liberacion' => NULL,
                                                ]);
                                            } else {
                                                $dataPlanobra = array(
                                                    "itemplan"  => $itemplan,
                                                    "costo_unitario_mo"             => $monto_mo,
                                                    "costo_unitario_mat"             => $monto_mat,
                                                    "solicitud_oc"                     => $codigo_solicitud,
                                                    "estado_sol_oc"                 => 'PENDIENTE',
                                                    "fec_registro_sol_creacion_oc"     => $this->fechaActual()
                                                );
                                            }

                                            $solicitud_oc_creacion = array(
                                                'codigo_solicitud' => $codigo_solicitud,
                                                'idEmpresaColab' => $infoPlan['idEmpresaColab'],
                                                'estado' => 1, //pendiente
                                                'fecha_creacion' => $this->fechaActual(),
                                                'idSubProyecto' => $infoPlan['idSubProyecto'],
                                                'plan' => 'OC',
                                                'pep1' => $pep1,
                                                'pep2' => $pep2,
                                                'estatus_solicitud' => 'NUEVO',
                                                'tipo_solicitud' => 1 //creacion
                                            );

                                            $item_x_sol = array(
                                                'itemplan' => $itemplan,
                                                'codigo_solicitud_oc' => $codigo_solicitud,
                                                'costo_unitario_mo' => $monto_mo
                                            );

                                            // $dataSapDetalle = array('monto_temporal' => $monto_tmp_final,
                                            // 'pep1' => $pep1
                                            // );
                                            $data = $this->M_aprobacion_interna->aprobarCotizacionPI($dataPlanobra, $solicitud_oc_creacion, $item_x_sol);
                                            if ($data['error'] == EXIT_ERROR) {
                                                throw new Exception($data['msj']);
                                            }
                                            break;
                                        } else {
                                            $margen_contador = $margen_contador + 1;
                                        }
                                    } else {
                                        $accedio_contador = $accedio_contador + 1;
                                    }
                                }
                            } else {
                                throw new Exception('No se pudo procesar la peticion, validar que exista una configuracion en Bolsa Pep para MO y que la pep asociada cuente con el disponble requerido.');
                            }

                            if ($accedio_contador == count($infoBobp)) {
                                throw new Exception('No Se Encontro una PEP que cumpla con el monto');
                            }

                            if ($margen_contador == count($infoBobp)) {
                                throw new Exception('No Se Encontro una PEP que cumpla con el monto');
                            }
                        }

                        // if($sisego!=""){
                        //     $infoBobp = $this->m_validar_cotizacion->getPEPCapexPepByItemplanConsulta($itemplan);
                        //     //throw new Exception('Es Capex');
                        // }else{
                        //     $infoBobp = $this->m_validar_cotizacion->getPEPSBolsaPepByItemplanConsulta($itemplan);
                        //     //throw new Exception('No es Caex');
                        // }


                    } else if ($infoItemplan['flg_opex'] == 2) { //OPEX

                        $countTipoSoliCrea = $this->m_utils->getCountTipoSolicitud($itemplan, 1, 1);

                        if ($countTipoSoliCrea > 0) {
                            throw new Exception('Ya tiene una solicitud de creacion pendiente.');
                        }

                        $SubProy = $infoItemplan['idSubProyecto'];
                        $fase    = $infoItemplan['idFase'];

                        $info_fase = $this->m_validar_cotizacion->getFase($fase);

                        $guia = $info_fase['faseDesc'];


                        $monto_mo = $this->M_aprobacion_interna->getCostoMoPin($ptr);
                        $monto_mat = 0;
                        if ($monto_mo == null || $monto_mo == '') {
                            throw new Exception('No se ingreso la PO.');
                        }

                        $codigo_solicitud = $this->m_utils->getNextCodSolicitud();
                        $accedio_contador_opex = 0;

                        if ($infoItemplan['flg_reg_item_capex_opex'] != 2) {
                            $infoBobp = $this->m_validar_cotizacion->getOpexConsulta($SubProy, $fase);
							
							if (count($infoBobp) == 0) {
								throw new Exception('No Se Encontro una configuracion.');
							}
							
                            foreach ($infoBobp as $opex) {
                                if ($opex->disponible_proyectado >= $monto_mo) {
                                    if ($codigo_solicitud == null) {
                                        throw new Exception('Hubo problemas al obtener el codigo de solicitud OC, vuelva a intentarlo o genere un ticket CAP');
                                    }

                                    $dataPlanobra = array(
                                        "itemplan"  => $itemplan,
                                        "costo_unitario_mo"             => $monto_mo,
                                        "costo_unitario_mat"             => $monto_mat,
                                        "solicitud_oc"                     => $codigo_solicitud,
                                        "estado_sol_oc"                 => 'PENDIENTE',
                                        "costo_unitario_mo_crea_oc"     => $monto_mo,
                                        "costo_unitario_mat_crea_oc"     => $monto_mat,
                                        "fec_registro_sol_creacion_oc"     => $this->fechaActual(),
                                        "ceco"                             => $opex->ceco,
                                        "cuenta"                         => $opex->cuenta,
                                        "area_funcional"                 => $opex->areafuncional
                                        //agregar 3 camos
                                    );

                                    $solicitud_oc_creacion = array(
                                        'codigo_solicitud' => $codigo_solicitud,
                                        'idEmpresaColab' => $infoItemplan['idEmpresaColab'],
                                        'estado' => 1, //pendiente
                                        'fecha_creacion' => $this->fechaActual(),
                                        'idSubProyecto'  => $infoItemplan['idSubProyecto'],
                                        'plan'              => 'OC',
                                        'estatus_solicitud' => 'NUEVO',
                                        'tipo_solicitud' => 1, //creacion
                                        "cuenta"          => $opex->cuenta,
                                        "ceco"            =>    $opex->ceco,
                                        "area_funcional" => $opex->areafuncional
                                    );

                                    $item_x_sol = array(
                                        'itemplan' => $itemplan,
                                        'codigo_solicitud_oc' => $codigo_solicitud,
                                        'costo_unitario_mo' => $monto_mo
                                    );

                                    // llenar itemplan con ceco cuenta y area funcional que se creo


                                    $monto_final = ($opex->disponible_proyectado) - $monto_mo;

                                    $this->m_validar_cotizacion->actualizarConfiOpex($monto_final, $opex->idlineaopex_fase);

                                    break;

                                    if ($data['error'] == EXIT_ERROR) {
                                        throw new Exception($data['msj']);
                                    }
                                } else {
                                    $accedio_contador_opex++;
                                }
                            }
							
                            if ($accedio_contador_opex == count($infoBobp)) {
                                throw new Exception('El monto de la linea opex es menor al monto solicitado, verificar.');
                            }
                        } else {

                            $infoPlanobra = $this->m_utils->getInfoItemplanWhitSubProyecto($itemplan);

                            if ($infoPlanobra['ceco'] == null  || $infoPlanobra['cuenta'] == null || $infoPlanobra['area_funcional'] == null) {
                                throw new Exception('No ingresaron los parametros opex en el registro de itemplan (ceco, cuenta, area funcional)');
                            }

                            $dataPlanobra = array(
                                "itemplan"  => $itemplan,
                                "costo_unitario_mo"             => $monto_mo,
                                "costo_unitario_mat"             => $monto_mat,
                                "solicitud_oc"                     => $codigo_solicitud,
                                "estado_sol_oc"                 => 'PENDIENTE',
                                "costo_unitario_mo_crea_oc"     => $monto_mo,
                                "costo_unitario_mat_crea_oc"     => $monto_mat,
                                "fec_registro_sol_creacion_oc"     => $this->fechaActual(),
                                "ceco"                             => $infoPlanobra['ceco'],
                                "cuenta"                         => $infoPlanobra['cuenta'],
                                "area_funcional"                 => $infoPlanobra['area_funcional']
                            );

                            $solicitud_oc_creacion = array(
                                'codigo_solicitud' => $codigo_solicitud,
                                'idEmpresaColab'    => $infoItemplan['idEmpresaColab'],
                                'estado'            => 1, //pendiente
                                'fecha_creacion'    => $this->fechaActual(),
                                'idSubProyecto'     => $infoItemplan['idSubProyecto'],
                                'plan'                 => 'OC',
                                'estatus_solicitud' => 'NUEVO',
                                'tipo_solicitud'    => 1, //creacion
                                "ceco"                 => $infoPlanobra['ceco'],
                                "cuenta"             => $infoPlanobra['cuenta'],
                                "area_funcional"     => $infoPlanobra['area_funcional']
                            );

                            $item_x_sol = array(
                                'itemplan' => $itemplan,
                                'codigo_solicitud_oc' => $codigo_solicitud,
                                'costo_unitario_mo'   => $monto_mo
                            );
                        }

                        $data = $this->M_aprobacion_interna->aprobarCotizacionPIOpex($dataPlanobra, $solicitud_oc_creacion, $item_x_sol);
                    } else {


                        /*
                        
                        //MODIFICADO CZAVALA 06.10.2020                        
                        $monto_mo  = $this->M_aprobacion_interna->getCostoMoPin($ptr);
                        $monto_mat = 0;
                        $infoPlan = $this->m_utils->getInfoItemplan($itemplan);
                        $listaPepNoPPT = array();
                        $hasSomePep = false;
                        $hasSomePepWiPresu = false;
                        $pep1 = null;
                        $monto_tmp_final = 0;
                        // $itemPepGrafo = $this->m_validar_cotizacion->getPEPSITemplanPep2GrafoByItemplan($itemplan);
                        // if (count($itemPepGrafo) > 0) {
                            // foreach ($itemPepGrafo as $pep) {
                                // if ($pep->monto_temporal >= $monto_mo) {
                                    // $hasSomePepWiPresu = true;
                                    // $pep1 = $pep->pep1;
                                    // $monto_tmp_final = ($pep->monto_temporal - $monto_mo);
                                    // break;
                                // } else {
                                    // array_push($listaPepNoPPT, $pep->pep1);
                                // }
                            // }
                            // $hasSomePep = true;
                        // }
                        
                        // if (!$hasSomePepWiPresu) {
                            // $itemBolsaPep = $this->m_validar_cotizacion->getPEPSBolsaPepByItemplan($itemplan);
                            // if (count($itemBolsaPep) > 0) {
                                // foreach ($itemBolsaPep as $pep) {
                                    // if ($pep->monto_temporal >= $monto_mo) {
                                        // $hasSomePepWiPresu = true;
                                        // $pep1 = $pep->pep1;
                                        // $monto_tmp_final = ($pep->monto_temporal - $monto_mo);
                                        // break;
                                    // } else {
                                        // array_push($listaPepNoPPT, $pep->pep1);
                                    // }
                                // }
                                // $hasSomePep = true;
                            // }
                        // }
                       
                       // verificacion-consulta
                        //$pep1 = $infoItemplan['pep1'];
						//$pep2 = $infoItemplan['pep2'];

                        $pep1 = $pep1_co;
						$pep2 = $pep1_co.$pep2_co;



                        if (!$pep1) {
                            throw new Exception('La obra no cuenta con PEP configurada.');
                        } else {
							$codigo_solicitud = $this->m_utils->getNextCodSolicitud();
							if ($codigo_solicitud == null) {
								throw new Exception('Hubo problemas al obtener el codigo de solicitud OC, vuelva a intentarlo o genere un ticket CAP');
							}
					
							$dataPlanobra = array(	"itemplan" 						=> $itemplan,
								"costo_unitario_mo" 			=> $monto_mo,
								"costo_unitario_mat" 			=> $monto_mat,
								"solicitud_oc" 					=> $codigo_solicitud,
								"estado_sol_oc" 				=> 'PENDIENTE',
								"costo_unitario_mo_crea_oc" 	=> $monto_mo,
								"costo_unitario_mat_crea_oc" 	=> $monto_mat,
								"fec_registro_sol_creacion_oc" 	=> $this->fechaActual()
							);
					
							$solicitud_oc_creacion = array('codigo_solicitud' => $codigo_solicitud,
								'idEmpresaColab' => $infoPlan['idEmpresaColab'],
								'estado' => 1, //pendiente
								'fecha_creacion' => $this->fechaActual(),
								'idSubProyecto' => $infoPlan['idSubProyecto'],
								'plan' => 'OC',
								'pep1' => $pep1,
								'pep2' => $pep2,
								'estatus_solicitud' => 'NUEVO',
								'tipo_solicitud' => 1//creacion
							);
					
							$item_x_sol = array('itemplan' => $itemplan,
								'codigo_solicitud_oc' => $codigo_solicitud,
								'costo_unitario_mo' => $monto_mo
							);
					
							// $dataSapDetalle = array('monto_temporal' => $monto_tmp_final,
								// 'pep1' => $pep1
							// );
					
							$data = $this->M_aprobacion_interna->aprobarCotizacionPI($dataPlanobra, $solicitud_oc_creacion, $item_x_sol);
							
							if($data['error'] == EXIT_ERROR) {
								throw new Exception($data['msj']);
							}
                        }
                         //FIN CAMBIO CZAVALA 06-10-2020

                         */
                    }
                    $arrayDataLog = array(
                        'tabla'            => 'Planta Interna',
                        'actividad'        => 'ptr Aprobada',
                        'itemplan'         => $itemP,
                        'ptr'              => $ptr,
                        'fecha_registro'   => $this->fechaActual(),
                        'id_usuario'       => $this->session->userdata('idPersonaSession'),
                        'tipoPlanta'       => 2
                    );
                    $this->m_utils->registrarLogPlanObra($arrayDataLog);

                    $data = $this->m_utils->updateEstadoPlanObra($itemplan, ESTADO_PLAN_PDT_OC);

                    if ($data['error'] == EXIT_ERROR) {
                        throw new Exception($data['msj']);
                    }
                } else {          //RECHAZADO
                    $data = $this->M_aprobacion_interna->updateDetalleProducto($ptr, $itemP, $vale_re, ESTADO_07_TEXTO, ESTADO_01_TEXTO, '6', FLG_RECHAZADO);
                    //if ($needValidation  ==  0) { //no necsita po mat
                        $data = $this->m_utils->updateEstadoPlanObra($itemP, ESTADO_PLAN_PRE_DISENO);
                    //}
                    $arrayDataLog = array(
                        'tabla'            => 'Planta Interna',
                        'actividad'        => 'ptr Rechazada',
                        'itemplan'         => $itemP,
                        'ptr'              => $ptr,
                        'fecha_registro'   => $this->fechaActual(),
                        'id_usuario'       => $this->session->userdata('idPersonaSession'),
                        'tipoPlanta'       => 2
                    );
                    $this->m_utils->registrarLogPlanObra($arrayDataLog);
                }
            } else if ($tipo_po == 1) { //tipo Material
                if ($estado == 1) { //aprobar
                    $arrayUpdate = array(
                        "estado_po" => PO_PREAPROBADO
                    );
                    $data = $this->M_aprobacion_interna->aprobOCanlPoMatPI($ptr, $itemP, $arrayUpdate, $this->fechaActual(), PO_PREAPROBADO);
                    if ($data['error'] == EXIT_ERROR) {
                        throw new Exception('Ocurrio un error al pre aprobar la Po. comuniquese con Soporte.');
                    }
                } else { //RECHAZAR
                    $arrayUpdate = array(
                        "estado_po" => PO_CANCELADO
                    );
                    $data = $this->M_aprobacion_interna->aprobOCanlPoMatPI($ptr, $itemP, $arrayUpdate, $this->fechaActual(), PO_CANCELADO);
                    if ($data['error'] == EXIT_ERROR) {
                        throw new Exception('Ocurrio un error al cancelar la Po. comuniquese con Soporte.');
                    }
                }
            }

            $SubProy = $this->input->post('subProy');
            $eecc    = $this->input->post('eecc');
            $zonal   = $this->input->post('zonal');
            // $itemPlan = $this->input->post('item');
            $mesEjec = $this->input->post('mes');
            $area = $this->input->post('area');
            //desmarcar
            // $data['tablaasigGrafoInterna'] = $this->makeHTLMTablaasignarGrafoInterna($this->M_aprobacion_interna->getPtrToLiquidacion($SubProy,$eecc,$zonal,'SI',$mesEjec,$area,'',null, '01'));
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }


    public function aprobacion_presupuesto()
    {
    }

    public function makeHTLMTablaasignarGrafoInterna($listaPTR)
    {

        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>Acci&oacute;n</th>
                            <th>PTR</th>
                            <th>Item Plan</th>                            
                            <th>Sub Proy</th>
                            <th>Zonal</th>
                            <th>EECC</th>
                            <th>DES. Area</th>
                            <th>Fec. Prevista</th>
                            <th>Valor MAT</th>
                            <th>Valor MO</th>
                            <th>TOTAL</th>
                            <th>Estado</th>                            
                        </tr>
                    </thead>                    
                    <tbody>';

        foreach ($listaPTR->result() as $row) {
            if ($row->orden_compra == NULL && $row->solicitud_oc == NULL) {
                $btnCheck = '<a data-tipo="' . $row->tipo_po . '" data-ptr ="' . $row->ptr . '" data-itmpl ="' . $row->itemplan . '" onclick="addValeReserva(this)" title="aprobaci&oacute;n"><i style="color:black" class="zmdi zmdi-hc-2x zmdi-check-circle"></i></a>';
            } else {
                $btnCheck = 'PEND. CARGA OC';
            }
            $btnConsulta = '<a data-tipo="' . $row->tipo_po . '" data-ptr ="' . $row->ptr . '" data-itemplan ="' . $row->itemplan . '" onclick="consultarCotizacionPtr($(this))" title="cotizaci&oacute;n"><i style="color:green" class="zmdi zmdi-hc-2x zmdi-money-box"></i></a>';
            $html .= ' <tr>
                                <td>' . $btnCheck . ' ' . $btnConsulta . '</td>
                                <td>' . $row->ptr . '</td>
                                <td>' . $row->itemplan . '</td>							
                                <td>' . $row->subProyectoDesc . '</td>
                                <td>' . $row->zonalDesc . '</td>
                                <th>' . $row->empresaColabDesc . '</th>
                                <th>' . $row->areaDesc . '</th>							
                                <th>' . $row->fechaPrevEjec . '</th>                              
                                <td>' . (($row->costo_mat != null) ? number_format($row->costo_mat, 2, '.', ',') : '') . '</td>
                                <td>' . (($row->costo_mo != null) ? number_format($row->costo_mo, 2, '.', ',') : '') . '</td>
                                <td>' . (($row->total != null) ? number_format($row->total, 2, '.', ',') : '') . '</td>
                                <td>' . $row->estado . '</td>
                            </tr>';
        }
        $html .= '</tbody>
                </table>';

        return utf8_decode($html);
    }

    function getCotizacionPtr()
    {
        $ptr      = $this->input->post('ptr');
        $itemplan = $this->input->post('itemplan');
        $tipo_po  = $this->input->post('tipo_po');
        $tabla = '';
        if ($tipo_po ==  2) { //DETALLE MO
            $tabla = $this->getTablaCotizacionPtr($ptr, $itemplan);
        } else if ($tipo_po ==  1) { //DETALLE MATERIAL
            $tabla = $this->getDetalleMaterialesPTR($ptr);
        }
        $data['tablaCotizacion'] = $tabla;

        echo json_encode(array_map('utf8_encode', $data));
    }

    function getTablaCotizacionPtr($ptr, $itemplan)
    {
        $arrayData = $this->M_aprobacion_interna->consultarCostoPtr($itemplan, $ptr);

        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr style="color: white ; background-color: #3b5998">
                            <th>Actividad</th>
                            <th>Precio</th>
                            <th>Baremo</th>                            
                            <th>Cantidad</th>
                            <th>Costo MO</th>
                            <th>Precio kit</th>
                            <th>Costo MAT</th>
                            <th>Total</th>                       
                        </tr>
                    </thead>                    
                    <tbody>';
        foreach ($arrayData as $row) {
            $html .= '<tr>
                        <td>' . utf8_decode($row->descripcion) . '</td>
                        <td>' . $row->precio . '</td>
                        <td>' . $row->baremo . '</td>
                        <td>' . $row->cantidad . '</td>
                        <td>' . $row->costo_mo . '</td>
                        <td>' . $row->costo_material . '</td>
                        <td>' . $row->costo_mat . '</td>
                        <td>' . $row->total . '</td>
                    </tr>';
        }
        $html .= '   </tbody>
                </table>';

        return $html;
    }

    function getDetalleMaterialesPTR($ptr)
    {

        $ListaDetallePO = $this->M_aprobacion_interna->getPPODetalle($ptr);
        $htmlDetallePO = '<table id="data-table" class="table table-bordered">
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
        return $htmlDetallePO;
    }
    function filtrarTablaInterna()
    {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try {
            $SubProy   = $this->input->post('subProy');
            $eecc      = $this->input->post('eecc');
            $zonal     = $this->input->post('zonal');
            $itemPlan  = $this->input->post('item');
            $mesEjec   = $this->input->post('mes');
            $area      = $this->input->post('area');
            $estado    = $this->input->post('estado');
            $idUsuario = $this->input->post('idUsuario');
            $idUsuario = isset($idUsuario) ? $idUsuario : null;

            $data['tablaasigGrafoInterna'] = $this->makeHTLMTablaasignarGrafoInterna($this->M_aprobacion_interna->getPtrToLiquidacion($SubProy, $eecc, $zonal, $itemPlan, $mesEjec, $area, $estado, $idUsuario, '01'));
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function fechaActual()
    {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone', 'America/Lima');
        setlocale(LC_TIME, "es_ES", "esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }
	
	
	 public function generarSolOcMasivoManual()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
		
        try {
				$arrayItemplan = $this->M_aprobacion_interna->getListadoItemplan();
				$idUsuario = 2341;
				$fechaActual = $this->fechaActual();
				
				$monto_mo = 0;
                $monto_mat = 0;
				
				$pep1 = null;
				$pep2 = null;
				
				foreach($arrayItemplan as $row){
					
					$monto_mo = 0;
					$pep1 = null;
					$pep2 = null;
					
					$infoPlan = $this->m_utils->getInfoItemplan($row['itemplan']);
					if ($infoPlan == null) {
						throw new Exception('Hubo un error al traer la informacion del itemplan.');
					}
				
					$codigo_solicitud = $this->m_utils->getNextCodSolicitud();
					if ($codigo_solicitud == null) {
						throw new Exception('Hubo problemas al obtener el codigo de solicitud OC, vuelva a intentarlo o genere un ticket CAP.');
					}
					$monto_mo = $row['importe_real'];
					$pep1 = $row['pep1'];
					$pep2 = $row['pep2'];
					
					if($monto_mo == null || $monto_mo == 0){
						throw new Exception('Hubo problemas al recibir el monto mo.');
					}
					if($pep1 == null || $pep1 == ''){
						throw new Exception('Hubo problemas al recibir la pep1.');
					}
					if($pep2 == null || $pep2 == ''){
						throw new Exception('Hubo problemas al recibir la pep2.');
					}
					
					
					$dataPlanobra = array(
						"itemplan"  => $row['itemplan'],
						"costo_unitario_mo" => $monto_mo,
						"costo_unitario_mat" => $monto_mat,
						"solicitud_oc" => $codigo_solicitud,
						"estado_sol_oc"  => 'PENDIENTE',
						"fec_registro_sol_creacion_oc" => $fechaActual,
						"idEstadoPlan" => ESTADO_PLAN_PDT_OC,
						"usu_upd" => $idUsuario,
						"fecha_upd" => $fechaActual
					);
					
					$solicitud_oc_creacion = array(
						'codigo_solicitud' => $codigo_solicitud,
						'idEmpresaColab' => $infoPlan['idEmpresaColab'],
						'estado' => 1,
						'fecha_creacion' => $this->fechaActual(),
						'idSubProyecto' => $infoPlan['idSubProyecto'],
						'plan' => 'OC',
						'pep1' => $pep1,
						'pep2' => $pep2,
						'estatus_solicitud' => 'NUEVO',
						'tipo_solicitud' => 1,
						'usuario_registro' => $idUsuario
					);

					$item_x_sol = array(
						'itemplan' => $row['itemplan'],
						'codigo_solicitud_oc' => $codigo_solicitud,
						'costo_unitario_mo' => $monto_mo
					);

					$data = $this->M_aprobacion_interna->aprobarCotizacionPI($dataPlanobra, $solicitud_oc_creacion, $item_x_sol);
					if ($data['error'] == EXIT_ERROR) {
						throw new Exception($data['msj']);
					}
					/*
					$arrayDataLog = array(
                        'tabla'            => 'Planta Interna',
                        'actividad'        => 'ptr Aprobada',
                        'itemplan'         => $itemplan,
                        'ptr'              => $ptr,
                        'fecha_registro'   => $fechaActual,
                        'id_usuario'       => $idUsuario,
                        'tipoPlanta'       => 2
                    );
                    $this->m_utils->registrarLogPlanObra($arrayDataLog);*/

				}
				
				
				
		} catch (Exception $e) {
			$data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
	}
    
}
