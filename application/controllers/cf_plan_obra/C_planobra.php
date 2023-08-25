<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 * 
 * 
 *
 */
class C_planobra extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_plan_obra/m_planobra');
        $this->load->model('mf_mantenimiento/m_subproy_pep_grafo');
        $this->load->model('mf_pre_diseno/m_bandeja_adjudicacion');
        $this->load->model('mf_servicios/M_integracion_sirope');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_ejecucion/M_pendientes');
        $this->load->library('lib_utils');
        $this->load->helper('url');
        $this->load->model('mf_ficha_tecnica/m_bandeja_ficha_tecnica');
        $this->load->model('mf_ejecucion/M_porcentaje');
        $this->load->model('mf_pqt_pre_liquidacion/M_pqt_pre_liquidacion');
    }

    public function index() {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {
            /* carga de la tabla */
            //$data['listartabla'] = $this->makeHTMLTablaCentral($this->m_planobra->getAllPlanesObra());
            /* carga de proyecto */
            /*             * *******miguel rios 13062018********** */
            /*             * *$data['listaProy'] = $this->m_utils->getAllProyecto();** */
            $data['listaProy'] = $this->m_utils->getAllProyectoExcepcion();
            /*             * ************************************* */
            $data['listaTiCen'] = $this->m_utils->getAllCentral();
            /* carga de empresas electricas */
            $data['listaeelec'] = $this->m_utils->getAllEELEC();
            /* carga de fase */
            $data['listafase'] = $this->m_utils->getAllFase();
            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $permisos = $this->session->userdata('permisosArbol');
            // permiso para registro individual modificar
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_PLAN_DE_OBRA, ID_PERMISO_HIJO_REGIND_OBRA);

            $data['opciones'] = $result['html'];
            $this->load->view('vf_plan_obra/v_registro_individual', $data);
        } else {
            redirect('login', 'refresh');
        }
    }

    public function makeHTMLTablaCentral($listartabla) {

        $html = '
        <table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>Itemplan</th>                            
                            <th>Nombre Plan</th>
                            <th>Subproyecto</th>
                            <th>Central</th>
                            <th>Zonal</th>
                            <th>EECC</th>
                            <th>fecha Inicio</th>
                            <th>fecha PrevEjecucion</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>';

        foreach ($listartabla->result() as $row) {

            $html .= ' <tr>
							<td>' . $row->ItemPlan . '</td> 
                            <td>' . $row->Nombre . '</td> 
                            <td>' . $row->Subproyecto . '</td>                                 
                            <td>' . $row->Central . '</td>
                            <td>' . $row->Zonal . '</td>
                            <td>' . $row->EmpresaColab . '</td>
                            <td>' . $row->fechaInicio . '</td>
                            <td>' . $row->fechaPreviaEjecucion . '</td>
                            <td>' . $row->Estado . '</td>      
                                               
						</tr>';
        }
        $html .= '</tbody>
                </table>';

        return utf8_decode($html);
    }

    /**
     * ultima modificacion 20.06.2019 czavalacas
     * @throws Exception
     */
    public function createPlanobra() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $idProy = $this->input->post('selectProy');
            $idSubproy = $this->input->post('selectSubproy');
            $idCentral = $this->input->post('selectCentral');
            $idzonal = $this->input->post('selectZonal');
            $eecc = $this->input->post('selectEmpresaColab');
            $eelec = $this->input->post('selectEmpresaEle');
            $fase = $this->input->post('selectFase') ? $this->input->post('selectFase') : null;
            $indicador = $this->input->post('inputIndicador');
            //$cantidadTroba  = $this->input->post('inputCantObra');
            $cantidadTroba = '0';
            $fechaInicio = $this->input->post('inputFechaInicio');
            $nombreplan = $this->input->post('inputNombrePlan');
            //$uip            = $this->input->post('inputUIP');
            $uip = '0';
            $cordx = $this->input->post('inputCoordX');
            $cordy = $this->input->post('inputCoordY');
            $has_coti = $this->input->post('selectCotizacion');
            $itemMadre = $this->input->post('inputItemMadre');

            if ($fase == null) {
                throw new Exception('Debe seleccionar una fase!!');
            }
            /*             * CZAVALA 27.05.2020* */
            $infoSubProyecto = $this->m_utils->getInfoSubProyectoByIdSubProyecto($idSubproy);
            if ($infoSubProyecto == null) {
                throw new Exception('Error al obtener la informacion del subproyecto!!');
            }
            /*             * ******************* */
            /*             * VALIDAMOS SI CUENTA CON LA CONFIGURACION DE CREARSE EN OBRA 20.06.2019 czavala* */
            $hasAutoPlanEnObra = $this->m_utils->getIdEstadoPlanCambio($idSubproy);

            if ($hasAutoPlanEnObra == null) {//SI NO TIENE LA CONFIGURACION SE TOMA EN CUENTA LA COTIZACION DE CASO CONTRARIO NO SE TOMA EN CUENTA COTIZACION
                if ($has_coti == '1') {//SI REQUIERE COTIZACION NACE EN PRE REGISTRO
                    $estadoplan = ESTADO_PLAN_PRE_REGISTRO;
                    if ($idzonal == '') {
                        $idzonal = 0;
                    }
                } else {//SI NO REQUERIE COTIZACION NACE EN PRE DISENO
                    $estadoplan = ESTADO_PLAN_PRE_DISENO;
                }
            } else {//SI TIENE CONFIGURACION DE NACER EN OBRA PRIMERO SE REGISTRA EN ESTADO PRE DISENO
                $estadoplan = ESTADO_PLAN_PRE_DISENO;
            }


            $this->m_planobra->deleteLogImportPlanObraSub();
            $itemplan = $this->m_planobra->generarCodigoItemPlan($idProy, $idzonal);
            $data = $this->m_planobra->insertarPlanobra($itemplan, $idProy, $idSubproy, $idCentral, $idzonal, $eecc, $eelec, ESTADO_PLAN_PRE_REGISTRO, $fase, $fechaInicio, $nombreplan, $indicador, $uip, $cordx, $cordy, $cantidadTroba, $has_coti, $itemMadre, null, null, null, null, null, null, null, null, null, null, null, $infoSubProyecto);


            if ($data['error'] == EXIT_ERROR) {
                throw new Exception('Error al Insertar planobra');
            } else {
                $itemplanData = $this->m_planobra->obtenerUltimoRegistro();
                $this->m_planobra->saveItemPlanEstadoCreado($itemplanData, $estadoplan, $this->fechaActual(), 1);
                $data2 = $this->m_planobra->insertarLogPlanObra($itemplanData, $this->session->userdata('idPersonaSession'), ID_TIPO_PLANTA_EXTERNA);
                if ($data2['error'] == EXIT_ERROR) {
                    throw new Exception('Error al Insertar en el log de planobra');
                }
                $data['itemplannuevo'] = $itemplanData;

                if ($hasAutoPlanEnObra == null) {//SI NO TIENE AUTO REGISTRO EN OBRA TOMAR EN CUENTA LA COTIZACION DE CASO CONTRARIO NO TOMAR EN CUENTA
                    if ($has_coti == '1') {//SI TIENE COTIZACION
                        $uploaddir = 'uploads/cotizacion/' . $itemplanData . '/'; //ruta final del file
                        $uploadfile = $uploaddir . basename($_FILES['file']['name']);
                        if (!is_dir($uploaddir))
                            mkdir($uploaddir, 0777);

                        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
                            $succ = $this->m_planobra->saveFileCotizacionInit($itemplanData, $uploadfile);
                        } else {
                            throw new Exception('Hubo un problema con la carga del archivo al servidor, comuniquese con el administrador.');
                        }
                    }
                }
                //INSERT DETALLE OBRAS PUBLICAS

                if ($idProy == ID_PROYECTO_OBRA_PUBLICA) {


                    $departamento = $this->input->post('txt_departamento');
                    $provincia = $this->input->post('txt_provincia');
                    $distrito = $this->input->post('txt_distrito');
                    $fec_recepcion = $this->input->post('fecRecepcionOP');
                    $nomCliente = $this->input->post('inputNomCli');
                    $numCarta = $this->input->post('inputNumCar');
                    $ano = $this->input->post('selectAno');
                    $numCartaFin = $this->input->post('numCartaFin');
                    $kickOff = $this->input->post('selectKickOff');

                    $uploaddir = 'uploads/obra_publica/' . $itemplanData . '/'; //ruta final del file
                    $uploadfile = $uploaddir . basename($_FILES['fileOP']['name']);
                    if (!is_dir($uploaddir))
                        mkdir($uploaddir, 0777);

                    if (move_uploaded_file($_FILES['fileOP']['tmp_name'], $uploadfile)) {
                        $arrayDataOP = array(
                            'itemplan' => $itemplanData,
                            'departamento' => $departamento,
                            'provincia' => $provincia,
                            'distrito' => $distrito,
                            'fecha_recepcion' => $this->fechaActual(),
                            'nombre_cliente' => $nomCliente,
                            'numero_carta' => $numCarta,
                            'ano' => $ano,
                            'numero_carta_pedido' => $numCartaFin,
                            'ruta_carta_pdf' => $uploadfile,
                            'usuario_envio_carta' => $this->session->userdata('idPersonaSession'),
                            'has_kickoff' => $kickOff,
                            'estado_kickoff' => (($kickOff == 1) ? 'PENDIENTE' : null)
                        );
                        $this->m_planobra->saveDetalleObraPublica($arrayDataOP);
                    } else {
                        throw new Exception('Hubo un problema con la carga del archivo al servidor, comuniquese con el administrador.');
                    }
                }
                if ($hasAutoPlanEnObra == null) {//SI NO TIENE CONFIGURACION AUTO CREACION EN OBRA PREGUNTA SI TIENE ADJUDICACION AUTOMATICA EN CASO CONTRARIO NO SE ADJUDICARA DICHA OBRA.
                    if ($has_coti != '1') {//SOLO SI NO TIENE ADJUDICACION ES DECIR NACE EN PRE DISENO PREGUNTAR LA AUTO ADJUDICACION
                        /////////////////////////////AUTO ADJUDICAR ITEMPLAN
                        $conEsta = $this->m_bandeja_adjudicacion->countFOAndCoaxByItemplan($itemplanData);
                        $this->session->set_userdata('has_fo', $conEsta['fo']);
                        $this->session->set_userdata('has_coax', $conEsta['coaxial']);
                        $dias = $this->m_bandeja_adjudicacion->getDiaAdjudicacionBySubProyecto($idSubproy);
                        if ($dias != null) {//SOLO SI TIENE DIAS LO ADJUDICAMOS
                            $curHour = date('H');
                            if ($curHour >= 13) {//13:00 PM
                                $dias = ($dias + 1);
                            }
                            $nuevafecha = strtotime('+' . $dias . ' day', strtotime($fechaInicio));
                            $idFechaPreAtencionFo = date('Y-m-j', $nuevafecha);
                            $info = $this->m_bandeja_adjudicacion->adjudicarItemplan($itemplanData, $idSubproy, $idCentral, $eecc, $idFechaPreAtencionFo, $idFechaPreAtencionFo);

                            //AQUI ENVIO A SIROPE....!
                            /*                             * 19.09.2019 czavalacas - sisegos al tener adjudicacion automatica crea su ot en sirope* */
                            if ($conEsta['fo'] == 1) {//si cuenta con estacion FO
                                $dataCentral = $this->m_utils->getInfocentralByIdCentral($idCentral);
                                if ($dataCentral != null) {
                                    #if(($dataCentral['jefatura']=='LIMA' || $dataCentral['jefatura']=='PIURA' || $dataCentral['jefatura']=='CAJAMARCA' || $dataCentral['jefatura']=='TRUJILLO' || $dataCentral['jefatura']=='CHIMBOTE' || $dataCentral['jefatura']=='AREQUIPA' || $dataCentral['jefatura']=='CUSCO') && $idProy != ID_PROYECTO_CRECIMIENTO_VERTICAL && $idProy != ID_PROYECTO_SISEGOS){//validacion temporal solo lima
                                    //comentado25.03.2020czavalaonuevomodelonoenviatrama
                                    //$this->M_integracion_sirope->execWs($itemplanData, $itemplanData.'FO', $fechaInicio, $idFechaPreAtencionFo);
                                    #}
                                } else {
                                    //no se detecto un idCentral..
                                }
                            }
                            /*                             * *************************************************************************************** */
                        }
                        ///////////////////////////////////////////////////////////////
                    }
                } else {//SI TIENE CONFIGURACION DE CREACION EN OBRA SE CAMBIA EL ESTADO A EN OBRA
                    $datoEnObra = $this->m_planobra->changeEstadoEnObraWithLog($itemplanData);
                }
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    /* ENLAZA CON VIEW PARA ENVIAR A MODEL Y recibir DATOS DEL ITEMPLAN PARA LA EDICION */

    public function getHTMLChoiceSubProy() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $idProyecto = $this->input->post('proyecto');
            $listaSubProy = $this->m_utils->getSubProyByProyRegIP($idProyecto, 1);
            $html = '<option value="">&nbsp;</option>';
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

    public function getFechaPreEjecuCalculo() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        try {
            $fechaInicio = $this->input->post('fecha');
            $subproy = $this->input->post('subproyecto');

            $fechaCalculado = $this->m_utils->getCalculoTiempoSubproyecto($fechaInicio, $subproy);

            $data['fechaCalculado'] = $fechaCalculado;
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function getHTMLChoiceZonal() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $idCentral = $this->input->post('central');
            $listaZonal = $this->m_utils->getZonalXCentral($idCentral);
            $html = '';
            $idzonalselect = '';
            foreach ($listaZonal->result() as $row) {
                $html .= '<option value="' . $row->idzonal . '">' . $row->zonalDesc . '</option>';
                $idzonalselect = $row->idzonal;
            }
            $data['listaZonal'] = $html;
            $data['idZonalSelec'] = $idzonalselect;

            $html = '';
            $listaEECC = $this->m_utils->getEECCXCentral($idCentral);
            $idEECCselect = '';
            foreach ($listaEECC->result() as $row) {
                $html .= '<option value="' . $row->idEmpresaColab . '">' . $row->empresaColabDesc . '</option>';
                $idEECCselect = $row->idEmpresaColab;
            }
            $data['listaEECC'] = $html;
            $data['idEECCSelec'] = $idEECCselect;

            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    /*
      public function getHTMLChoiceEECC(){
      $data['error']    = EXIT_ERROR;
      $data['msj']      = null;
      $data['cabecera'] = null;
      try{
      $idCentral = $this->input->post('central');
      $listaEECC = $this->m_utils->getEECCXCentral($idCentral);
      $html = '';
      $idEECCselect='';
      foreach($listaEECC->result() as $row){
      $html .= '<option value="'.$row->idEmpresaColab.'">'.$row->empresaColabDesc.'</option>';
      $idEECCselect=$row->idEmpresaColab;
      }
      $data['listaEECC'] = $html;
      $data['idEECCSelec'] = $idEECCselect;
      $data['error']    = EXIT_SUCCESS;
      }catch(Exception $e){
      $data['msj'] = $e->getMessage();
      }
      echo json_encode(array_map('utf8_encode', $data));
      }
     */

    function createPlanObraFromSisego() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            header('Access-Control-Allow-Origin: *');
            /*             * *DATOS FROM SISEGOS** */
            $id = $this->input->post('id');
            $indicador = $this->input->post('sisego');
            $pep = $this->input->post('pep');
            $fecha_envio = $this->input->post('envio');
            $mdf = $this->input->post('mdf');
            $segmento = $this->input->post('segmento');
            $cliente = $this->input->post('cliente');
            $eecc = $this->input->post('eecc');
            $jefatura = $this->input->post('jefatura');
            $region = $this->input->post('region');
            $dias = $this->input->post('dias');
            $cod_cotiz = $this->input->post('sinfix');
            $tipo_cliente = utf8_decode($this->input->post('tipo_cliente'));
            $tipo_diseno = utf8_decode($this->input->post('tipo_diseno'));
            $tipo_requerimiento = utf8_decode($this->input->post('tipo_requerimiento'));
            $nombre_estudio = utf8_decode($this->input->post('nombre_estudio'));
            $duracion = utf8_decode($this->input->post('duracion'));
            $acceso_cliente = utf8_decode($this->input->post('acceso_cliente'));
            $tendido_externo = utf8_decode($this->input->post('tendido_externo'));
            $tipo_sede = utf8_decode($this->input->post('tipo_sede'));
            $per = utf8_decode($this->input->post('per'));

            $coordenada_x = $this->input->post('latitud');
            $coordenada_y = $this->input->post('longitud');
            //AGREGADO 13-02-2020
            $pep2 = trim($this->input->post('pep2'));
            $grafo = trim($this->input->post('grafo'));
            $itemplan = $this->input->post('itemplan');
            $flg_update_pep = $this->input->post('flg_update_pep');

            $flg_tipo_gasto = $this->input->post('flg_tipo_gasto');
            $ceco = $this->input->post('seco');
            $cuenta = $this->input->post('cuenta');
            $area_funcional = $this->input->post('area_funcional');
            $JsonLog = array(
								'id' => $id,
								'sisego' => $indicador,
								'pep' => $pep,
								'fecha_envio' => $fecha_envio,
								'mdf' => $mdf,
								'segmento' => $segmento,
								'tipo_diseno' => utf8_decode($tipo_diseno),
								'nombre_estudio' => utf8_decode($nombre_estudio),
								'tipo_requerimiento' => utf8_decode($tipo_requerimiento),
								'duracion' => $duracion,
								'acceso_cliente' => utf8_decode($acceso_cliente),
								'tendido_externo' => utf8_decode($tendido_externo),
								'tipo_sede' => utf8_decode($tipo_sede),
								'sinfix' => $cod_cotiz,
								'cliente' => utf8_decode($cliente),
								'eecc' => $eecc,
								'jefatura' => $jefatura,
								'region' => $region,
								'dias' => $dias,
								'tipo_cliente' => utf8_decode($tipo_cliente),
								'per' => $per,
								'coordenada_x' => $coordenada_x,
								'coordenada_y' => $coordenada_y,
								'pep2' => $pep2,
								'grafo' => $grafo,
								'flg_update_pep' => $flg_update_pep,
								'flg_tipo_gasto' => $flg_tipo_gasto,
								'ceco' => $ceco,
								'cuenta' => $cuenta,
								'area_funcional' => $area_funcional
							);

            $this->m_utils->saveLogSigoplus('CREAR ITEMPLAN - DATA SISEGO', $cod_cotiz, null, '', $indicador, $eecc, null, 'TRAMA COMPLETADA', 'INFO', null, 6, json_encode($JsonLog), json_encode($data));

            if ($indicador == '' || $indicador == null) {
                throw new Exception('No se envio el sisego');
            }

            if ($pep2 == '' || $pep2 == null) {
                throw new Exception('No se envio la PEP2');
            }

            if ($grafo == null || $grafo == '') {
                throw new Exception('No se envio el grafo');
            }

            if ($cod_cotiz == null || $cod_cotiz == '') {
                throw new Exception('No se envio el codigo de cotizacion');
            }
			
			$countExiste = $this->m_utils->getCountMismoTipoCoti(NULL, $indicador);//VALIDAMOS SI EL SISEGO PO (-1, -0) EXISTE.
            if ($countExiste == 0) {
                throw new Exception('Codigo Sisego PO no existe o SMART envio codigo cotizacion no asociado a Sisego PO (Principal / respaldo).');
            }
			
			$countCotiExiste = $this->m_utils->getCountCotizacionByCod($cod_cotiz, NULL);//VALIDAMOS QUE EL CODIGO COTIZACION EXISTA.

            if ($countCotiExiste == 0) {
                throw new Exception('SMART envió Código de cotizacion no existente.');
            }
			
			$arraySisego = explode('-', $indicador);
            $flg_principal = $arraySisego[3];

            $countMismoTipo = $this->m_utils->getCountMismoTipoCoti($cod_cotiz, $indicador);
            if ($countMismoTipo == 0) {
                throw new Exception('SMART envio codigo cotizacion no asociado a Sisego PO (Principal / respaldo).'); //VALIDAMOS QUE SI ENVIAN UN PRINCIPAL O RESPALDO SEA IGUAL EN LA COTIZACION.
            }
			
			$countCotiAsoc = $this->m_utils->getCountCotiAsociadoItem($cod_cotiz);

            if ($countCotiAsoc > 0) {
                throw new Exception('Codigo cotizacion ya cuenta con un Itemplan activo asociado.');
            }

            $count = $this->m_utils->getCountCotizacionByCod($cod_cotiz, 2);

            if ($count == 0) {
                throw new Exception('Codigo de cotizacion no esta aprobado en Web PO.');
            }

            if ($flg_tipo_gasto == 1) {
                $arrayPep = explode('-', $pep2);

                if (count($arrayPep) != 6) {
                    throw new Exception('Formato de PEP no valido: ' . $pep2);
                }

                $pep1 = $arrayPep[0] . '-' . $arrayPep[1] . '-' . $arrayPep[2] . '-' . $arrayPep[3] . '-' . $arrayPep[4];

                $countExitPep = $this->m_utils->getCountExistPep($pep1);
                $fecha_actual = $this->m_utils->fechaActual();
                $data['flg_evaulua_pep'] = 0;
                if ($countExitPep == 0) {
                    $arrayPepEvalua = array(
											'sisego' => $indicador,
											'pep' => $pep1,
											'grafo' => $grafo,
											'data_json' => json_encode($JsonLog),
											'fecha_registro' => $fecha_actual,
											'flg_estado' => 0
										);
                    $data = $this->m_utils->insertEvaluaPep($arrayPepEvalua);

                    if ($data['error'] == EXIT_ERROR) {
                        $this->m_utils->saveLogSigoplus('TRAMA CREAR ITEMPLAN NUEVAS PEPS SISEGO', NULL, $itemplan, '', $indicador, $eecc, $jefatura, 'ERROR EN RECEPCION DE TRAMA', 'ERROR', 2, 20, $arrayPepEvalua);
                    }

                    $data['flg_evaulua_pep'] = 1;
                    $data['error'] = EXIT_ERROR;
					
					$msjHorario = $this->m_utils->getMensajeHorarioRegItemSisego();
                    throw new Exception($msjHorario);
                }

                $countPresupuesto = $this->m_utils->getCountPresupuesto($pep1, $cod_cotiz);

                if ($countPresupuesto == 0) {
                    throw new Exception('La pep ' . $pep1 . ', no cuenta con presupuesto.');
                }
				
				$idSubproy = $this->m_utils->getIdSubProyectoBySubProyectoDesc(strtoupper($segmento));
            } else {
				$idSubproy = $this->m_utils->getSubProyectoOpex(strtoupper($segmento));
			}

            // $data = $this->m_utils->actualizarMontoDisponible($pep1, $cod_cotiz);
            // if($data['error'] == EXIT_ERROR) {
            // throw new Exception($data['msj']);
            // }

            $fecha = $this->m_utils->fechaActual();

            $dataArray = array(
								'tipo_diseno' => utf8_decode($tipo_diseno),
								'nombre_estudio' => utf8_decode($nombre_estudio),
								'tipo_requerimiento' => utf8_decode($tipo_requerimiento),
								'duracion' => $duracion,
								'acceso_cliente' => utf8_decode($acceso_cliente),
								'tendido_externo' => utf8_decode($tendido_externo),
								'tipo_sede' => utf8_decode($tipo_sede)
							);


            $itemplan = null;
            /*             * *DATOS COMPLMENTARIOS ITEMPLAN** */
            $idProy = '3'; //ID PROYECTO SISEGOS = 3
            
            if ($idSubproy == null) {
                throw new Exception('segmento no reconocido.');
            }

            if ($coordenada_x == null || $coordenada_x == '' || $coordenada_y == null || $coordenada_y == '') {
                throw new Exception('Debe enviar coord x, coord y.');
            }

            // $dataCentral = _getDataKmz($coordenada_y, $coordenada_x);

            $dataCoti = $this->m_utils->getDataCotizacionByCod($cod_cotiz);

            if ($dataCoti['idCentral'] == null || $dataCoti['idCentral'] == '') {
                throw new Exception('MDF no registrado.');
            }
            $this->db->trans_begin();

            $idCentral = $dataCoti['idCentral'];
            $idzonal   = $dataCoti['idZonal'];
            $eecc      = $dataCoti['idEmpresaColab'];
            $codigo    = $dataCoti['codigo'];
            $jefatura  = $dataCoti['jefatura'];

            // $idCentral = $dataCentral[0]['idCentral'];
            // $codigo = $dataCentral[0]['codigo'];
            // $idzonal = $dataCentral[0]['idZonal'];
            // $eecc = $dataCentral[0]['idEmpresaColab'];
            // $jefatura = $dataCentral[0]['jefatura'];

            // if ($eecc == 11 || $eecc == 4) {// SI ES LITEYCA O EZENTIS
                // $eecc = 12;
            // }

            // if ($idCentral == null) {
                // throw new Exception('central no registrado.');
            // }

            // if ($idzonal == null) {
                // throw new Exception('zonal no registrado.');
            // }

            // if ($eecc == null) {
                // throw new Exception('eecc no registrado.');
            // }

            // if ($jefatura == null) {
                // throw new Exception('jefatura no registrado.');
            // }

            $infoSubProyecto = $this->m_utils->getInfoSubProyectoByIdSubProyecto($idSubproy);
            if ($infoSubProyecto == null) {
                throw new Exception('Error al obtener la informacion del subproyecto!!');
            }
			
			$infoSubProyecto['paquetizado_fg'] = $dataCoti['flg_paquetizado'];
			
            $eelec = 6;
            //$estadoplan     = ESTADO_PLAN_PRE_DISENO;
            $fase = ID_FASE_ANIO_CREATE_ITEMPLAN; //2020
            $cantidadTroba = 0;
            $fechaInicio = $fecha_envio;
            $nombreplan = $indicador . " - " . $cliente;
            $uip = 0;
            $cordx = $coordenada_x;
            $cordy = $coordenada_y;
            $has_coti = '0'; //sisegos no requiere cotizacion

            /*             * VALIDAMOS SI CUENTA CON LA CONFIGURACION DE CREARSE EN OBRA 20.06.2019 czavala* */
            $hasAutoPlanEnObra = $this->m_utils->getIdEstadoPlanCambio($idSubproy);

            // if($hasAutoPlanEnObra == null) {//SI NO TIENE LA CONFIGURACION SE TOMA EN CUENTA LA COTIZACION DE CASO CONTRARIO NO SE TOMA EN CUENTA COTIZACION
            // //SI REQUIERE COTIZACION NACE EN PRE REGISTRO
            // $estadoplan     = ESTADO_PLAN_PRE_REGISTRO;
            // if($idzonal==''){
            // $idzonal = 0;
            // }                               
            // }else{//SI TIENE CONFIGURACION DE NACER EN OBRA PRIMERO SE REGISTRA EN ESTADO PRE DISENO
            // $estadoplan     = ESTADO_PLAN_PRE_DISENO;
            // }
            $estadoplan = ESTADO_PLAN_PRE_REGISTRO;
            $this->m_planobra->deleteLogImportPlanObraSub();

            $itemplan = $this->m_planobra->generarCodigoItemPlan($idProy, $idzonal);

            $dataCostoUni = $this->m_utils->getDataCotizacionCostos($cod_cotiz);

            $data = $this->m_planobra->insertarPlanobra($itemplan, $idProy, $idSubproy, $idCentral, $idzonal, $eecc, $eelec, ESTADO_PLAN_PRE_REGISTRO, $fase, $fechaInicio, $nombreplan, $indicador, $uip, $cordx, $cordy, $cantidadTroba, $has_coti, null, $tipo_requerimiento, $tipo_diseno, $nombre_estudio, $duracion, $acceso_cliente, $tendido_externo, $tipo_sede, $tipo_cliente, $per, $dataCostoUni['costo_materiales'], $dataCostoUni['costo_mo'], $infoSubProyecto);

            if ($data['error'] == EXIT_ERROR) {
                throw new Exception('1) Error interno al registrar el itemplan.');
            } else {
                $itemplanData = $this->m_planobra->obtenerUltimoRegistro();
                $this->m_planobra->saveItemPlanEstadoCreado($itemplanData, $estadoplan, $this->fechaActual(), 2);

                if ($itemplanData) {
                    if ($flg_tipo_gasto == 1) {
                        if ($eecc != 12) {
                            $data = $this->m_subproy_pep_grafo->insertSisegoPep2Grafo($indicador, $pep2, $grafo, $itemplanData, $fecha);

                            if ($data['error'] == EXIT_SUCCESS) {
                                $this->m_utils->saveLogSigoplus('SISEGO PEP2 GRAFO', '', $itemplanData, '', $indicador, '', '', 'TRAMA COMPLETADA', 'OPERACION REALIZADA CON EXITO', '1', null, $JsonLog);
                            } else {
                                throw new Exception('No ingreso la pep y grafo.');
                            }


                            $resp = $this->m_utils->crearOcSisegoByItemplan($itemplanData, $pep2);

                            if ($resp == 2) {
                                throw new Exception('La PEP ' . $pep2 . ' no tiene presupuesto');
                            }

                            if ($resp == 3) {
                                throw new Exception('El itemplan se encuentra paralizado.');
                            }

                            if ($resp == 4) {
                                throw new Exception('Hay un error interno ya sea porque no tiene cotizacion o tiene costo 0');
                            }
                        }
                    } else {
						if ($area_funcional == null || $area_funcional == '') {
                            throw new Exception('No se envió el area funcional');
                        }

                        if ($ceco == null || $ceco == '') {
                            throw new Exception('No se envió ceco');
                        }

                        if ($cuenta == null || $cuenta == '') {
                            throw new Exception('No se envió cuenta');
                        }
						
                        if (!is_numeric($area_funcional)) {//DEBE SER NUMERICO
                            throw new Exception('Cuenta OPEX no cuenta con formato SAP');
                        }

                        if (!is_numeric($ceco)) {//DEBE SER NUMERICO
                            throw new Exception('Cuenta OPEX no cuenta con formato SAP');
                        }

                        if (!is_numeric($cuenta)) {//DEBE SER NUMERICO
                            throw new Exception('Cuenta OPEX no cuenta con formato SAP');
                        }

			//Vali Ivan More

                        if ((strlen($ceco) !== 8)) {
                            throw new Exception('El ceco no cuenta con 8 numeros : ' . $ceco);
                        }

//                        if ((substr($ceco, 0, 2) == 55)) {
//                            throw new Exception('El ceco no empieza en 55');
//                        }


                        $dataArray = array(
                            'sisego' => $indicador,
                            'ceco' => $ceco,
                            'cuenta' => $cuenta,
                            'area_funcional' => $area_funcional,
                            'estado' => 1,
                            'fecha_registro' => $this->m_utils->fechaActual(),
                            'monto' => $dataCostoUni['costo_mo']
                        );

                        $idOpex = $this->m_planobra->idCuentaOpex($ceco, $cuenta, $area_funcional);

                        if ($idOpex) {

                            $this->m_planobra->funcionOCopex($itemplanData, $idOpex, $this->session->userdata('idPersonaSession'));
                        } else {

                            $arrayDataOP = array(
													'ceco' => $ceco,
													'cuenta' => $cuenta,
													'areaFuncional' => $area_funcional,
													'opexDesc' => 'SISEGO',
													'monto_inicial' => floatval($dataCostoUni['costo_mo']) + floatval($dataCostoUni['costo_materiales']),
													'monto_temporal' => floatval($dataCostoUni['costo_mo']) + floatval($dataCostoUni['costo_materiales']),
													'monto_real' => floatval($dataCostoUni['costo_mo']) + floatval($dataCostoUni['costo_materiales']),
													'fecha_registro' => $this->fechaActual(),
													'anho' => '2020',
													'idEstadoOpex' => 1
												);

                            $data = $this->m_planobra->saveConfigOpex($arrayDataOP);

                            $arrayEventoOpex = array(
														'idOpex'        => $data['idOpex'],
														'idSubproyecto' => $idSubproy
													);

                            $this->m_planobra->saveEventoOpex($arrayEventoOpex);
                            $this->m_planobra->funcionOCopex($itemplanData, $data['idOpex'], $this->session->userdata('idPersonaSession'));
                        }

                        $this->m_planobra->insertSisegoOpex($dataArray);
                        //$this->m_planobra->funcionOCopexSisego($itemplanData, $ceco, $cuenta, $area_funcional, $this->session->userdata('idPersonaSession'));
                    }


                    $this->m_planobra->insertarLogPlanObra($itemplanData, 0, ID_TIPO_PLANTA_EXTERNA); //ID USUARIO = 0 FROM SISEGO              
                    $this->m_utils->saveLogSigoplus('TRAMA CREAR ITEMPLAN FROM SISEGO', NULL, $itemplanData, '', $indicador, $eecc, $jefatura, 'TRAMA COMPLETADA', 'OPERACION REALIZADA CON EXITO', 1, 1, $JsonLog);
                    //APROBAMOS LA COTIZACION
                    if ($cod_cotiz) {
                        $datoArray = array(
                            "itemplan" => $itemplanData
                        );
                        $data = $this->m_planobra->updateEstadoCluster($cod_cotiz, $datoArray);

                        if ($data['error'] == EXIT_ERROR) {
                            $this->m_utils->saveLogSigoplus('TRAMA CREAR ITEMPLAN', $cod_cotiz, $itemplanData, '', $indicador, $eecc, null, 'TRAMA COMPLETADA', 'ERROR NO SE ASIGNO EL ITEMPLAN', 2, 6, $JsonLog);
                            throw new Exception('No se asocio la cotizacion al itemplan.');
                        }
                    }


                    //////////////////////////////
                } else if ($itemplanData == null) {
                    $data['itemplan'] = null;
                    throw new Exception('Error al obtener el itemplan.');
                }
                $data['id'] = $id;


                //METODO MOMENTANEO PARA PaRALIZAR
                // $data = $this->insertParalizacion(66, 'AUTOMATICO', $itemplanData, 'Pendiente entrega cronograma Certificacion', 1);
                // if($data['error'] == EXIT_ERROR) {
                // throw new Exception('no se paraliza correctamente.');
                // }

                $countParalizaSi = $this->m_utils->getCountSisegoParaliza($indicador);
                if ($countParalizaSi > 0) {
                    $data['flg_paralizado'] = 1;
                } else {
                    $data['flg_paralizado'] = 0;
                }

                $data['itemplan'] = $itemplanData;
                $dataEmpresa = $this->m_utils->getEECCXCentralPqt($idCentral, 1);
                $data['empresacolab'] = $dataEmpresa['empresaColabDesc'];
                $data['msj'] = 'Registro Exitoso.';
                /////////////////////////////
                $this->db->trans_commit();
            }
            /////////////////////////////AUTO ADJUDICAR ITEMPLAN 
            // $conEsta = $this->m_bandeja_adjudicacion->countFOAndCoaxByItemplan($itemplanData);
            // $this->session->set_userdata('has_fo', $conEsta['fo']);   
            // $this->session->set_userdata('has_coax', $conEsta['coaxial']); 
            // $dias = $this->m_bandeja_adjudicacion->getDiaAdjudicacionBySubProyecto($idSubproy);
            // $curHour = date('H');
            // if($curHour >= 13){//13:00 PM
            // $dias = ($dias + 1);
            // }
            // $nuevafecha = strtotime ( '+'.$dias.' day' , strtotime ( $fechaInicio) ) ;
            // $idFechaPreAtencionFo= date ( 'Y-m-j' , $nuevafecha );           
            // $info = $this->m_bandeja_adjudicacion->adjudicarItemplan($itemplanData, $idSubproy, $idCentral, $eecc, null, $idFechaPreAtencionFo);
            /////////////////////////////////////////////////////////////// 

            /*             * 19.09.2019 czavalacas - sisegos al tener adjudicacion automatica crea su ot en sirope* */
            #if($jefatura=='LIMA' || $jefatura=='PIURA' || $jefatura=='CAJAMARCA' || $jefatura=='TRUJILLO' || $jefatura=='CHIMBOTE' || $jefatura=='AREQUIPA' || $jefatura=='CUSCO'){//validacion temporal sisegos solo lima
            //comentado25.03.2020czavalaonuevomodelonoenviatrama
            //$this->M_integracion_sirope->execWs($itemplanData, $itemplanData.'FO', $fechaInicio, $idFechaPreAtencionFo);
            #}
        } catch (Exception $e) {
            $data['error'] = EXIT_ERROR;
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
            $this->m_utils->saveLogSigoplus('TRAMA CREAR ITEMPLAN FROM SISEGO', NULL, $itemplan, '', $indicador, $eecc, $jefatura, 'ERROR EN RECEPCION DE TRAMA', $e->getMessage(), 2, 1, json_encode($JsonLog), json_encode($data));
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function createPlanObraFromSisegoForzarCreacion() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            header('Access-Control-Allow-Origin: *');
            /*             * *DATOS FROM SISEGOS** */
            $id = $this->input->post('id');
            $indicador = $this->input->post('sisego');
            $pep = $this->input->post('pep');
            $fecha_envio = $this->input->post('envio');
            $mdf = $this->input->post('mdf');
            $segmento = $this->input->post('segmento');
            $cliente = $this->input->post('cliente');
            $eecc = $this->input->post('eecc');
            $jefatura = $this->input->post('jefatura');
            $region = $this->input->post('region');
            $dias = $this->input->post('dias');
            $cod_cotiz = $this->input->post('sinfix');
            $tipo_cliente = utf8_decode($this->input->post('tipo_cliente'));
            $tipo_diseno = utf8_decode($this->input->post('tipo_diseno'));
            $tipo_requerimiento = utf8_decode($this->input->post('tipo_requerimiento'));
            $nombre_estudio = utf8_decode($this->input->post('nombre_estudio'));
            $duracion = utf8_decode($this->input->post('duracion'));
            $acceso_cliente = utf8_decode($this->input->post('acceso_cliente'));
            $tendido_externo = utf8_decode($this->input->post('tendido_externo'));
            $tipo_sede = utf8_decode($this->input->post('tipo_sede'));
            $per = utf8_decode($this->input->post('per'));

            $coordenada_x = $this->input->post('latitud');
            $coordenada_y = $this->input->post('longitud');
            //AGREGADO 13-02-2020
            $pep2 = trim($this->input->post('pep2'));
            $grafo = trim($this->input->post('grafo'));
            $itemplan = $this->input->post('itemplan');
            $flg_update_pep = $this->input->post('flg_update_pep');

            $JsonLog = array(
                'id' => $id,
                'sisego' => $indicador,
                'pep' => $pep,
                'fecha_envio' => $fecha_envio,
                'mdf' => $mdf,
                'segmento' => $segmento,
                'tipo_diseno' => utf8_decode($tipo_diseno),
                'nombre_estudio' => utf8_decode($nombre_estudio),
                'tipo_requerimiento' => utf8_decode($tipo_requerimiento),
                'duracion' => $duracion,
                'acceso_cliente' => utf8_decode($acceso_cliente),
                'tendido_externo' => utf8_decode($tendido_externo),
                'tipo_sede' => utf8_decode($tipo_sede),
                'sinfix' => $cod_cotiz,
                'cliente' => utf8_decode($cliente),
                'eecc' => $eecc,
                'jefatura' => $jefatura,
                'region' => $region,
                'dias' => $dias,
                'tipo_cliente' => utf8_decode($tipo_cliente),
                'per' => $per,
                'coordenada_x' => $coordenada_x,
                'coordenada_y' => $coordenada_y,
                'pep2' => $pep2,
                'grafo' => $grafo,
                'flg_update_pep' => $flg_update_pep
            );

            $this->m_utils->saveLogSigoplus('CREAR ITEMPLAN - DATA SISEGO', $cod_cotiz, null, '', $indicador, $eecc, null, 'TRAMA COMPLETADA', 'INFO', null, 6, $JsonLog, json_encode($data));

            if ($indicador == '' || $indicador == null) {
                throw new Exception('No se envio el sisego');
            }

            if ($pep2 == '' || $pep2 == null) {
                throw new Exception('No se envio la PEP2');
            }

            if ($grafo == null || $grafo == '') {
                throw new Exception('No se envio el grafo');
            }

            if ($cod_cotiz == null || $cod_cotiz == '') {
                throw new Exception('No se envio el codigo de cotizacion');
            }

            // $count = $this->m_utils->getCountCotizacionByCod($cod_cotiz);
            // if($count == 0) {
            // throw new Exception('El sisego '.$indicador.' no tiene cotizacion.');
            // }

            $arraySisego = explode('-', $indicador);
            $flg_principal = $arraySisego[3];

            $countMismoTipo = $this->m_utils->getCountMismoTipoCoti($cod_cotiz, $indicador);
            if ($countMismoTipo == 0) {
                throw new Exception('La cotizacion no tiene el mismo tipo de sisego que el que se esta mandando (principal/respaldo).');
            }

            // $countCotiAsoc = $this->m_utils->getCountCotiAsociadoItem($cod_cotiz);
            // if($countCotiAsoc > 0) {
            // throw new Exception('La cotizacion ya esta asociado a un itemplan.');
            // }

            $arrayPep = explode('-', $pep2);

            // if(count($arrayPep)!=6){
            // throw new Exception('Formato de PEP no valido: '.$pep2);
            // }

            $pep1 = null;

            // $countExitPep = $this->m_utils->getCountExistPep($pep1);
            // $fecha_actual = $this->m_utils->fechaActual();
            // $data['flg_evaulua_pep'] = 0;
            // if($countExitPep == 0) {
            // $arrayPepEvalua = array(
            // 'sisego' 	    => $indicador,
            // 'pep' 	 	    => $pep1,
            // 'grafo'          => $grafo,
            // 'data_json'      => json_encode($JsonLog),
            // 'fecha_registro' => $fecha_actual,
            // 'flg_estado'     => 0
            // );
            // $data = $this->m_utils->insertEvaluaPep($arrayPepEvalua);
            // if($data['error'] == EXIT_ERROR) {
            // $this->m_utils->saveLogSigoplus('TRAMA CREAR ITEMPLAN NUEVAS PEPS SISEGO', NULL, $itemplan, '', $indicador, $eecc, $jefatura, 'ERROR EN RECEPCION DE TRAMA', 'ERROR', 2, 20, $arrayPepEvalua);
            // }
            // $data['flg_evaulua_pep'] = 1;				
            // $data['error'] = EXIT_ERROR;
            // throw new Exception('La pep es nueva, se esta evaluando, se enviara el itemplan max en 24h.');
            // }
            // $countPresupuesto = $this->m_utils->getCountPresupuesto($pep1, $cod_cotiz);
            // if($countPresupuesto == 0) {
            // throw new Exception('La pep '.$pep1.', no cuenta con presupuesto.');
            // }
            // $data = $this->m_utils->actualizarMontoDisponible($pep1, $cod_cotiz);
            // if($data['error'] == EXIT_ERROR) {
            // throw new Exception($data['msj']);
            // }

            $fecha = $this->m_utils->fechaActual();

            $dataArray = array(
                'tipo_diseno' => utf8_decode($tipo_diseno),
                'nombre_estudio' => utf8_decode($nombre_estudio),
                'tipo_requerimiento' => utf8_decode($tipo_requerimiento),
                'duracion' => $duracion,
                'acceso_cliente' => utf8_decode($acceso_cliente),
                'tendido_externo' => utf8_decode($tendido_externo),
                'tipo_sede' => utf8_decode($tipo_sede)
            );


            $itemplan = null;
            /*             * *DATOS COMPLMENTARIOS ITEMPLAN** */
            $idProy = '3'; //ID PROYECTO SISEGOS = 3
            $idSubproy = $this->m_utils->getIdSubProyectoBySubProyectoDesc(strtoupper($segmento));
            if ($idSubproy == null) {
                throw new Exception('segmento no reconocido.');
            }

            if ($coordenada_x == null || $coordenada_x == '' || $coordenada_y == null || $coordenada_y == '') {
                throw new Exception('Debe enviar coord x, coord y.');
            }

            $dataCentral = _getDataKmz($coordenada_y, $coordenada_x);

            if ($dataCentral == null) {
                throw new Exception('MDF no registrado.');
            }
            $this->db->trans_begin();

            $idCentral = $dataCentral[0]['idCentral'];
            $codigo = $dataCentral[0]['codigo'];
            $idzonal = $dataCentral[0]['idZonal'];
            $eecc = $dataCentral[0]['idEmpresaColab'];
            $jefatura = $dataCentral[0]['jefatura'];

            if ($idCentral == null) {
                throw new Exception('central no registrado.');
            }

            if ($idzonal == null) {
                throw new Exception('zonal no registrado.');
            }

            if ($eecc == null) {
                throw new Exception('eecc no registrado.');
            }

            if ($jefatura == null) {
                throw new Exception('jefatura no registrado.');
            }
            // $dataCentral = $this->m_utils->getIdCentralByCentralDesc($mdf);
            // if($dataCentral == null){
            // throw new Exception('MDF no registrado.');
            // }

            $existSisego = $this->m_utils->existeSisego($indicador);
            if ($existSisego['count'] >= 1) {
                $data['itemplan'] = $existSisego['itemplan'];
                throw new Exception('SISEGO ya se encuentra registrado.');
            }

            // $idCentral      = $dataCentral['idCentral'];
            // $idzonal        = $dataCentral['idZonal'];            
            // $eecc           = $dataCentral['idEmpresaColab'];             
            $eelec = 6;
            //$estadoplan     = ESTADO_PLAN_PRE_DISENO;
            $fase = ID_FASE_ANIO_CREATE_ITEMPLAN; //2020
            $cantidadTroba = 0;
            $fechaInicio = $fecha_envio;
            $nombreplan = $indicador . " - " . $cliente;
            $uip = 0;
            $cordx = $coordenada_x;
            $cordy = $coordenada_y;
            $has_coti = '0'; //sisegos no requiere cotizacion

            /*             * VALIDAMOS SI CUENTA CON LA CONFIGURACION DE CREARSE EN OBRA 20.06.2019 czavala* */
            $hasAutoPlanEnObra = $this->m_utils->getIdEstadoPlanCambio($idSubproy);

            if ($hasAutoPlanEnObra == null) {//SI NO TIENE LA CONFIGURACION SE TOMA EN CUENTA LA COTIZACION DE CASO CONTRARIO NO SE TOMA EN CUENTA COTIZACION
                //SI REQUIERE COTIZACION NACE EN PRE REGISTRO
                $estadoplan = ESTADO_PLAN_PRE_REGISTRO;
                if ($idzonal == '') {
                    $idzonal = 0;
                }
            } else {//SI TIENE CONFIGURACION DE NACER EN OBRA PRIMERO SE REGISTRA EN ESTADO PRE DISENO
                $estadoplan = ESTADO_PLAN_PRE_DISENO;
            }

            $this->m_planobra->deleteLogImportPlanObraSub();

            $itemplan = $this->m_planobra->generarCodigoItemPlan($idProy, $idzonal);

            // $dataCostoUni = $this->m_utils->getDataCotizacionCostos($cod_cotiz);

            $data = $this->m_planobra->insertarPlanobra($itemplan, $idProy, $idSubproy, $idCentral, $idzonal, $eecc, $eelec, ESTADO_PLAN_PRE_REGISTRO, $fase, $fechaInicio, $nombreplan, $indicador, $uip, $cordx, $cordy, $cantidadTroba, $has_coti, null, $tipo_requerimiento, $tipo_diseno, $nombre_estudio, $duracion, $acceso_cliente, $tendido_externo, $tipo_sede, $tipo_cliente, $per, null, null);

            if ($data['error'] == EXIT_ERROR) {
                throw new Exception('1) Error interno al registrar el itemplan.');
            } else {
                $itemplanData = $this->m_planobra->obtenerUltimoRegistro();
                $this->m_planobra->saveItemPlanEstadoCreado($itemplanData, $estadoplan, $this->fechaActual(), 3);
                if ($itemplanData) {
                    $data = $this->m_subproy_pep_grafo->insertSisegoPep2Grafo($indicador, $pep2, $grafo, $itemplanData, $fecha);

                    if ($data['error'] == EXIT_SUCCESS) {
                        $this->m_utils->saveLogSigoplus('SISEGO PEP2 GRAFO', '', $itemplanData, '', $indicador, '', '', 'TRAMA COMPLETADA', 'OPERACION REALIZADA CON EXITO', '1', null, $JsonLog);
                    } else {
                        throw new Exception('No ingreso la pep y grafo.');
                    }


                    // $resp = $this->m_utils->crearOcSisegoByItemplan($itemplanData, $pep2);
                    // if($resp == 2) {
                    // throw new Exception('La PEP '.$pep2.' no tiene presupuesto');
                    // }

                    $this->m_planobra->insertarLogPlanObra($itemplanData, 0, ID_TIPO_PLANTA_EXTERNA); //ID USUARIO = 0 FROM SISEGO              
                    $this->m_utils->saveLogSigoplus('TRAMA CREAR ITEMPLAN FROM SISEGO', NULL, $itemplanData, '', $indicador, $eecc, $jefatura, 'TRAMA COMPLETADA', 'OPERACION REALIZADA CON EXITO', 1, 1, $JsonLog);
                    //APROBAMOS LA COTIZACION
                    // if($cod_cotiz) {
                    // $datoArray = array( 
                    // "estado" => 2,//cluster aprobado
                    // "itemplan" => $itemplanData,
                    // "fecha_aprobacion" => $this->fechaActual(),
                    // "idCentral"  => $idCentral
                    // );
                    // $data = $this->m_planobra->updateEstadoCluster($cod_cotiz, $datoArray);
                    // if($data['error'] == EXIT_SUCCESS) {
                    // $dataArray = array(
                    // 'codigo_cluster' => $cod_cotiz,
                    // 'fecha'          => $this->fechaActual(),
                    // 'id_usuario'     => 1645,//SISEGO
                    // 'estado'         => 2
                    // );
                    // $data = $this->m_utils->insertLogCotizacionInd($dataArray);
                    // } else {
                    // $this->m_utils->saveLogSigoplus('TRAMA APROBAR COTIZACION - CREAR ITEMPLAN', $cod_cotiz, $itemplanData, '', $indicador, $eecc, null, 'TRAMA COMPLETADA', 'ERROR NO SE APROBO LA COTIZACION', 2, 6, $JsonLog);
                    // throw new Exception('No se aprobo la cotizacion.');
                    // }
                    // }
                    //////////////////////////////
                } else if ($itemplanData == null) {
                    $data['itemplan'] = null;
                    throw new Exception('Error al obtener el itemplan.');
                }
                $data['id'] = $id;


                //METODO MOMENTANEO PARA PaRALIZAR
                // $data = $this->insertParalizacion(66, 'AUTOMATICO', $itemplanData, 'Pendiente entrega cronograma Certificacion', 1);
                // if($data['error'] == EXIT_ERROR) {
                // throw new Exception('no se paraliza correctamente.');
                // }
                // $countParalizaSi = $this->m_utils->getCountSisegoParaliza($indicador);
                // if($countParalizaSi > 0) {
                // $data['flg_paralizado'] = 1;
                // } else {
                // $data['flg_paralizado'] = 0;
                // }

                $data['itemplan'] = $itemplanData;
                $dataEmpresa = $this->m_utils->getEECCXCentralPqt($idCentral, 1);
                $data['empresacolab'] = $dataEmpresa['empresaColabDesc'];
                $data['msj'] = 'Registro Exitoso.';
                /////////////////////////////
                $this->db->trans_commit();
            }
        } catch (Exception $e) {
            $data['error'] = EXIT_ERROR;
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
            $this->m_utils->saveLogSigoplus('TRAMA CREAR ITEMPLAN FROM SISEGO', NULL, $itemplan, '', $indicador, $eecc, $jefatura, 'ERROR EN RECEPCION DE TRAMA', $e->getMessage(), 2, 1, $JsonLog, json_encode($data));
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    // function enviarItemPlanSisego() {
    // $data['msj']   = null;    
    // $data['error'] = EXIT_SUCCESS;
    // try {
    // $jsonItem = $this->input->post('');
    // $itemplan = $this->input->post('');
    // $sisego   = $this->input->post('');
    // $pep1     = $this->input->post('');
    // $jsonItem = json_decode($jsonItem); 
    // if($itemplan == '' || $itemplan == null) {
    // throw new Exception('ingresar el itemplan');
    // }
    // if($sisego == null || $sisego =='') {
    // throw new Exception('ingresar el sisego');
    // }
    // if($pep1 == null || $pep1 =='') {
    // throw new Exception('ingresar la pep1');
    // }
    // $countPresupuesto = $this->m_utils->getCountPresupuesto($jsonItem['pep1'], $jsonItem['sinfix']);
    // if($countPresupuesto == 0) {
    // throw new Exception('La pep '.$pep1.', no cuenta con presupuesto.');
    // }
    // $data = $this->m_utils->actualizarMontoDisponible($pep1, $cod_cotiz);
    // if($data['error'] == EXIT_ERROR) {
    // throw new Exception($data['msj']);
    // }
    // $fecha = $this->m_utils->fechaActual();
    // $dataArray = array( 
    // 'tipo_diseno'        => utf8_decode($jsonItem['tipo_diseno']),
    // 'nombre_estudio'     => utf8_decode($nombre_estudio),
    // 'tipo_requerimiento' => utf8_decode($tipo_requerimiento),
    // 'duracion'           => $duracion,
    // 'acceso_cliente'     => utf8_decode($acceso_cliente),
    // 'tendido_externo'    => utf8_decode($tendido_externo),
    // 'tipo_sede'          => utf8_decode($tipo_sede)
    // );
    // $itemplan = null;
    // /***DATOS COMPLMENTARIOS ITEMPLAN***/
    // $idProy     = '3';//ID PROYECTO SISEGOS = 3
    // $idSubproy  =   $this->m_utils->getIdSubProyectoBySubProyectoDesc(strtoupper($segmento));
    // if($idSubproy == null){
    // throw new Exception('segmento no reconocido.');
    // }
    // if($coordenada_x == null || $coordenada_x == '' || $coordenada_y == null || $coordenada_y == '') {
    // throw new Exception('Debe enviar coord x, coord y.');
    // }
    // $dataCentral = _getDataKmz($coordenada_y, $coordenada_x);
    // if($dataCentral == null){
    // throw new Exception('MDF no registrado.');
    // }
    // $this->db->trans_begin();
    // $idCentral = $dataCentral[0]['idCentral'];
    // $codigo    = $dataCentral[0]['codigo'];
    // $idzonal   = $dataCentral[0]['idZonal'];
    // $eecc      = $dataCentral[0]['idEmpresaColab'];
    // $jefatura  = $dataCentral[0]['jefatura'];
    // if($idCentral == null) {
    // throw new Exception('central no registrado.');
    // }
    // if($idzonal == null) {
    // throw new Exception('zonal no registrado.');
    // }
    // if($eecc == null) {
    // throw new Exception('eecc no registrado.');
    // }
    // if($jefatura == null) {
    // throw new Exception('jefatura no registrado.');
    // }			
    // $existSisego = $this->m_utils->existeSisego($indicador);
    // if($existSisego['count'] >= 1){
    // $data['itemplan']   =   $existSisego['itemplan'];
    // throw new Exception('SISEGO ya se encuentra registrado.');
    // }
    // $eelec          = 6;            
    // //$estadoplan     = ESTADO_PLAN_PRE_DISENO;
    // $fase           = ID_FASE_ANIO_CREATE_ITEMPLAN;//2020
    // $cantidadTroba  = 0;
    // $fechaInicio    = $fecha_envio;
    // $nombreplan     = $indicador." - ".$cliente;
    // $uip            = 0;
    // $cordx          = $coordenada_x;
    // $cordy          = $coordenada_y;
    // $has_coti       = '0';//sisegos no requiere cotizacion
    // /**VALIDAMOS SI CUENTA CON LA CONFIGURACION DE CREARSE EN OBRA 20.06.2019 czavala**/
    // $hasAutoPlanEnObra = $this->m_utils->getIdEstadoPlanCambio($idSubproy);
    // if($hasAutoPlanEnObra == null) {//SI NO TIENE LA CONFIGURACION SE TOMA EN CUENTA LA COTIZACION DE CASO CONTRARIO NO SE TOMA EN CUENTA COTIZACION
    // //SI REQUIERE COTIZACION NACE EN PRE REGISTRO
    // $estadoplan     = ESTADO_PLAN_PRE_REGISTRO;
    // if($idzonal==''){
    // $idzonal = 0;
    // }                               
    // }else{//SI TIENE CONFIGURACION DE NACER EN OBRA PRIMERO SE REGISTRA EN ESTADO PRE DISENO
    // $estadoplan     = ESTADO_PLAN_PRE_DISENO;
    // }
    // $this->m_planobra->deleteLogImportPlanObraSub();               
    // $itemplan   =   $this->m_planobra->generarCodigoItemPlan($idProy,$idzonal);
    // $dataCostoUni = $this->m_utils->getDataCotizacionCostos($cod_cotiz);
    // $data = $this->m_planobra->insertarPlanobra($itemplan,$idProy, $idSubproy, $idCentral, $idzonal, $eecc, $eelec, $estadoplan, $fase, $fechaInicio, 
    // $nombreplan,$indicador ,$uip, $cordx, $cordy ,$cantidadTroba, $has_coti, null,
    // $tipo_requerimiento,$tipo_diseno,$nombre_estudio,$duracion,$acceso_cliente,$tendido_externo, $tipo_sede, $tipo_cliente, 
    // $per, $dataCostoUni['costo_materiales'], $dataCostoUni['costo_mo']);
    // if($data['error'] == EXIT_ERROR){
    // throw new Exception('1) Error interno al registrar el itemplan.');
    // }else{
    // $itemplanData= $this->m_planobra->obtenerUltimoRegistro();
    // $dataSend = [  'itemplan' => $itemplan];
    // $url = '';
    // $response = $this->m_utils->sendDataToURL($url, $dataSend);
    // if($response->error == EXIT_SUCCESS){
    // $this->m_utils->saveLogSigoplus('TRAMA EVALUAR PEP - REG ITEMPLAN', NULL, $itemplan, '', $indicador, $eecc, $jefatura, 'ERROR EN RECEPCION DE TRAMA', $e->getMessage(), 2, 1, $response);
    // } else {
    // $this->m_utils->saveLogSigoplus('TRAMA EVALUAR PEP - REG ITEMPLAN', NULL, $itemplan, '', $indicador, $eecc, $jefatura, 'OPERACION REALIZADA CON EXITO', $e->getMessage(), 1, 1, $response);
    // }
    // if($itemplanData){
    // $this->m_planobra->insertarLogPlanObra($itemplanData,0,ID_TIPO_PLANTA_EXTERNA);//ID USUARIO = 0 FROM SISEGO              
    // $this->m_utils->saveLogSigoplus('TRAMA CREAR ITEMPLAN FROM SISEGO', NULL, $itemplanData, '', $indicador, $eecc, $jefatura, 'TRAMA COMPLETADA', 'OPERACION REALIZADA CON EXITO', 1, 1,$JsonLog);
    // //APROBAMOS LA COTIZACION
    // if($cod_cotiz) {
    // $datoArray = array( 
    // "estado" => 2,//cluster aprobado
    // "itemplan" => $itemplanData,
    // "fecha_aprobacion" => $this->fechaActual(),
    // "idCentral"  => $idCentral
    // );
    // $data = $this->m_planobra->updateEstadoCluster($cod_cotiz, $datoArray);
    // if($data['error'] == EXIT_SUCCESS) {
    // $dataArray = array(
    // 'codigo_cluster' => $cod_cotiz,
    // 'fecha'          => $this->fechaActual(),
    // 'id_usuario'     => 1645,//SISEGO
    // 'estado'         => 2
    // );
    // $data = $this->m_utils->insertLogCotizacionInd($dataArray);
    // if($data['error'] == EXIT_SUCCESS) {
    // //SE AGREGO EL 13-02-2020
    // // if($flg_update_pep == 1) {
    // // $data = $this->m_subproy_pep_grafo->updateSisegoPep2Grafo($indicador, $pep2, $grafo, $itemplanData, $fecha);
    // // } else {
    // $data = $this->m_subproy_pep_grafo->insertSisegoPep2Grafo($indicador, $pep2, $grafo, $itemplanData, $fecha);
    // // }
    // if($data['error'] == EXIT_SUCCESS){
    // $this->m_utils->saveLogSigoplus('SISEGO PEP2 GRAFO', '', $itemplanData, '', $indicador, '', '', 'TRAMA COMPLETADA', 'OPERACION REALIZADA CON EXITO', '1',null, $JsonLog);                
    // } else {
    // throw new Exception('No ingreso la pep y grafo.');
    // }
    // } else {
    // throw new Exception('No ingreso el log correctamente.');
    // }
    // } else {
    // $this->m_utils->saveLogSigoplus('TRAMA APROBAR COTIZACION - CREAR ITEMPLAN', $cod_cotiz, $itemplanData, '', $indicador, $eecc, null, 'TRAMA COMPLETADA', 'ERROR NO SE APROBO LA COTIZACION', 2, 6, $JsonLog);
    // throw new Exception('No se aprobo la cotizacion.');
    // }
    // } else {
    // //SE AGREGO EL 13-02-2020
    // // if($flg_update_pep == 1) {
    // // $data = $this->m_subproy_pep_grafo->updateSisegoPep2Grafo($indicador, $pep2, $grafo, $itemplanData, $fecha);
    // // } else {
    // $data = $this->m_subproy_pep_grafo->insertSisegoPep2Grafo($indicador, $pep2, $grafo, $itemplanData, $fecha);
    // // }
    // if($data['error'] == EXIT_SUCCESS){
    // $this->m_utils->saveLogSigoplus('SISEGO PEP2 GRAFO', '', $itemplanData, '', $indicador, '', '', 'TRAMA COMPLETADA', 'OPERACION REALIZADA CON EXITO', '1');                
    // } else {
    // throw new Exception('No ingreso la pep y grafo.');
    // }
    // }
    // //////////////////////////////
    // }else if($itemplanData==null){
    // $data['itemplan']   = null;
    // throw new Exception('Error al obtener el itemplan.');
    // }               
    // $data['id']         = $id;
    // //METODO MOMENTANEO PARA PaRALIZAR
    // // $data = $this->insertParalizacion(66, 'AUTOMATICO', $itemplanData, 'Pendiente entrega cronograma Certificacion', 1);
    // // if($data['error'] == EXIT_ERROR) {
    // // throw new Exception('no se paraliza correctamente.');
    // // }
    // $countParalizaSi = $this->m_utils->getCountSisegoParaliza($indicador);
    // if($countParalizaSi > 0) {
    // $data['flg_paralizado'] = 1;
    // } else {
    // $data['flg_paralizado'] = 0;
    // }
    // $data['itemplan']   = $itemplanData;
    // $dataEmpresa = $this->m_utils->getEECCXCentralPqt($idCentral, 1);
    // $data['empresacolab'] = $dataEmpresa['empresaColabDesc'];
    // $this->m_utils->crearOcSisegoByItemplan($itemplanData);
    // $data['msj'] = 'Registro Exitoso.';
    // /////////////////////////////
    // }
    // /////////////////////////////AUTO ADJUDICAR ITEMPLAN 
    // $conEsta = $this->m_bandeja_adjudicacion->countFOAndCoaxByItemplan($itemplanData);
    // $this->session->set_userdata('has_fo', $conEsta['fo']);   
    // $this->session->set_userdata('has_coax', $conEsta['coaxial']); 
    // $dias = $this->m_bandeja_adjudicacion->getDiaAdjudicacionBySubProyecto($idSubproy);
    // $curHour = date('H');
    // if($curHour >= 13){//13:00 PM
    // $dias = ($dias + 1);
    // }
    // $nuevafecha = strtotime ( '+'.$dias.' day' , strtotime ( $fechaInicio) ) ;
    // $idFechaPreAtencionFo= date ( 'Y-m-j' , $nuevafecha );           
    // $info = $this->m_bandeja_adjudicacion->adjudicarItemplan($itemplanData, $idSubproy, $idCentral, $eecc, null, $idFechaPreAtencionFo);
    // /////////////////////////////////////////////////////////////// 
    // } catch(Exception $e) {
    // $data['error']    = EXIT_ERROR;
    // $data['msj'] = $e->getMessage();
    // }
    // echo json_encode(array_map('utf8_encode', $data));
    // }

    /*     * METODO REGISTRO DETALLE SISEGO* */
    function getComboTipoObra() {
        $arrayFichaTecSisego = $this->m_bandeja_ficha_tecnica->getTrabajosFichaTecnica(3);
        $arrayTipoFichaSisego = $this->m_bandeja_ficha_tecnica->getTipoTrabajoFichaTecnica();
        $arrayFicha = array();
        foreach ($arrayFichaTecSisego->result() as $row) {
            array_push($arrayFicha, $row);
        }

        $arrayTipoFicha = array();

        foreach ($arrayTipoFichaSisego->result() as $row) {
            array_push($arrayTipoFicha, $row);
        }
        $arrayDataTipoObra = $this->m_planobra->getComboTipoObra();
        $arrayCmbTipo = array();
        foreach ($arrayDataTipoObra AS $row) {
            array_push($arrayCmbTipo, $row);
        }
        $data['cmbTipoObra'] = $arrayCmbTipo;
        $data['arrayFicha'] = $arrayFicha;
        $data['arrayTipoFicha'] = $arrayTipoFicha;
        echo json_encode($data);
    }

    function getComboCodigo() {
        $arrayCmbCodigo = array();
        $jefatura = $this->input->post('jefatura');
        $arrayCodigo = $this->m_utils->getCodigoCentral($jefatura);
        foreach ($arrayCodigo AS $row) {
            array_push($arrayCmbCodigo, $row);
        }
        $data['cmbCodigo'] = $arrayCmbCodigo;
        echo json_encode($data);
    }

    function saveSisegoPlanObra() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            //log_message('error', "Ingresa a saveSisegoPlanObra");
            /** FROM
             * 1 = 'bandeja pendiente de Ejecucion',
              2 = 'termino obra Sinfix'
             */
            $itemplan = $this->input->post('itemplan');
            $from = $this->input->post('from');
            $tipo_obra = $this->input->post('tipo_obra');
            $nap_nombre = $this->input->post('nap_nombre');
            $nap_num_troncal = $this->input->post('nap_num_troncal');
            $nap_cant_hilos_habi = $this->input->post('nap_cant_hilos_habi');
            $nap_nodo = $this->input->post('nap_nodo');
            $nap_coord_x = $this->input->post('nap_coord_x');
            $nap_coord_y = $this->input->post('nap_coord_y');
            $nap_ubicacion = $this->input->post('nap_ubicacion');
            $nap_num_pisos = $this->input->post('nap_num_pisos');
            $nap_zona = $this->input->post('nap_zona');
            $fo_oscu_cant_hilos = $this->input->post('fo_oscu_cant_hilos');
            $fo_oscu_cant_nodos = $this->input->post('fo_oscu_cant_nodos');
            $trasla_re_cable_externo = $this->input->post('trasla_re_cable_externo');
            $trasla_re_cable_interno = $this->input->post('trasla_re_cable_interno');
            $fo_tra_cant_hilos = $this->input->post('fo_tra_cant_hilos');
            $fo_tra_cant_hilos_hab = $this->input->post('fo_tra_cant_hilos_hab');
            $nap_idCmbUbicacion = $this->input->post('nap_idCmbUbi');
            $licenciaAfirm = $this->input->post('licenciaAfirm');
            $descEmpresaColab = $this->input->post('descEmpresaColab');
            $indicador = $this->input->post('indicador');
            $jefatura = $this->input->post('jefatura');
            /*             * NODOS QUE PROVIENEN DEL FORMULARIO  "N"* */
            $listaNomNodos = json_decode($this->input->post('nodos'), true);
            $idEstacion = $this->input->post('idEstacion');
            $idEstadoPlan = $this->input->post('idEstadoPlan');

            $pisoGlobal = $this->input->post('pisoGlobal');
            $sala = $this->input->post('sala');
            $nroODF = $this->input->post('nroODF');
            $bandeja = $this->input->post('bandeja');
            $nroHilo = $this->input->post('nroHilo');
            //DATA FICHA TECNICA
            $arrayJson = $this->input->post('arrayJsonData');
            $observacion = $this->input->post('observacion');
            $idEstacion = $this->input->post('idEstacion');
            $idFichaTecnicaBase = $this->input->post('idFichaTecnicaBase');

            if ($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception('La sesi&oacute;n caduc&oacute;, recargue la p&aacute;gina nuevamente.');
            }

            if ($licenciaAfirm == NULL) {
                throw new Exception('Confirmar si es con licencia o no.');
            }

            if ($tipo_obra == NULL || $tipo_obra == 0) {
                throw new Exception('Seleccionar tipo de obra.');
            }
            $arrayNodos = array();
            if ($tipo_obra == ID_TIPO_OBRA_CREACION_NAP) {
                if ($nap_nombre == null || $nap_num_troncal == null || $nap_cant_hilos_habi == null || $nap_nodo == null || $nap_coord_x == null || $nap_coord_y == null || $nap_idCmbUbicacion == 0) {
                    throw new Exception('Faltan ingresar datos');
                }
                if ($nap_idCmbUbicacion == 3) {
                    if ($nap_num_pisos == null) {
                        throw new Exception('No ingreso el n&uacute;mero de pisos');
                    }
                } else if ($nap_idCmbUbicacion == 4) {
                    if ($nap_zona == null) {
                        throw new Exception('No ingreso zona');
                    }
                }
            } else if ($tipo_obra == ID_TIPO_OBRA_FO_OSCURA) {
                if (count($listaNomNodos) == 0) {
                    throw new Exception('ingresar nombre de nodos');
                }
                if ($fo_oscu_cant_hilos == null || $fo_oscu_cant_nodos == null) {
                    throw new Exception('Faltan ingresar datos');
                }
            } else if ($tipo_obra == ID_TIPO_OBRA_TRASLADO) {
                if ($trasla_re_cable_externo == null || $trasla_re_cable_interno == null) {
                    throw new Exception('falta ingresar datos');
                }
            } else if ($tipo_obra == ID_TIPO_OBRA_FO_TRADICIONAL) {
                if ($fo_tra_cant_hilos == null || $fo_tra_cant_hilos_hab == null) {
                    throw new Exception('falta ingresar datos');
                }
            }

            foreach ($listaNomNodos as $nodo) {
                $nodo_tmp = array();
                $nodo_tmp['itemplan'] = $itemplan;
                $nodo_tmp['origen'] = $from;
                $nodo_tmp['nodo'] = $nodo['value'];
                array_push($arrayNodos, $nodo_tmp);
            }

            $data = $this->m_planobra->saveSisegoPlanObra($itemplan, $from, $tipo_obra, $nap_nombre, $nap_num_troncal, $nap_cant_hilos_habi, $nap_nodo, $nap_coord_x, $nap_coord_y, $nap_ubicacion, $nap_num_pisos, $nap_zona, $fo_oscu_cant_hilos, $fo_oscu_cant_nodos, $trasla_re_cable_externo, $trasla_re_cable_interno, $fo_tra_cant_hilos, $fo_tra_cant_hilos_hab, $arrayNodos, $licenciaAfirm, $pisoGlobal, $sala, $nroODF, $bandeja, $nroHilo);
            if ($data['error'] == EXIT_ERROR) {
                throw new Exception('1) Error interno al registrar el SisegoPlanObra.');
            } else {
                $arrayDataLog = array(
                    'tabla' => 'sinfix',
                    'actividad' => 'Registro Formulario',
                    'itemplan' => $itemplan,
                    'fecha_registro' => $this->fechaActual(),
                    'id_usuario' => $this->session->userdata('idPersonaSession'),
                );
                $this->m_utils->registrarLogPlanObra($arrayDataLog);

                $countFichaTec = $this->m_utils->countFichaTecnica($itemplan);

                if ($countFichaTec == 0) {
                    $this->registrarFichaSinfix($arrayJson, $itemplan, $observacion, $idEstacion, $idFichaTecnicaBase);
                }

                if ($from == 2 && $idEstadoPlan == ID_ESTADO_PLAN_EN_OBRA) {
                    $cant = $this->M_porcentaje->cantPorcentajeRegistroSisego($itemplan, $idEstacion);
                    if (isset($cant->porcentaje)) {
                        $porcentaje = ($cant->porcentaje >= 90) ? 100 : $cant->porcentaje + 10;
                        // if($cant->porcentaje == 90) {

                        if ($porcentaje == 100) {
                            $this->M_porcentaje->updateEstadoPO($itemplan, $idEstacion);
                            $arrayData = array('idEstadoPlan' => ID_ESTADO_PRE_LIQUIDADO,
                                'fechaPreLiquidacion' => $this->fechaActual(),
                                'id_usuario_preliquidacion' => $this->session->userdata('idPersonaSession'));

                            $flgValid = $this->M_porcentaje->getValidaSubProyecto($itemplan, $idEstacion, $porcentaje);

                            if ($flgValid->flg_focoaxial == 1 || $flgValid->flg_focoaxial == 2 || $flgValid->flg_focoaxial == 3 || $flgValid->flg_focoaxial == 4 || $flgValid->flg_acti_fo == 2) {
                                $flg = $this->M_porcentaje->updateEstadoPlanObra($itemplan, $arrayData);
                            } else {
                                $flg = null;
                            }

                            $countTrama = $this->M_porcentaje->countTrama($itemplan, 'LIQUIDACION OBRA');
                            if ($countTrama == 0 && $flgValid->flg_sisego == 1) {
                                $this->enviarTrama($itemplan, $indicador, 2, $jefatura, $descEmpresaColab);
                            }
                            if ($flg == 0) {
                                _log("FALLO AL ACTUALIZAR EL ESTADO");
                            } else if ($flg == 1) {
                                $arrayDataLog = array(
                                    'tabla' => 'planobra',
                                    'actividad' => 'Obra Pre-Liquidada',
                                    'itemplan' => $itemplan,
                                    'fecha_registro' => $this->fechaActual(),
                                    'id_usuario' => $this->session->userdata('idPersonaSession'),
                                    'idEstadoPlan' => ID_ESTADO_PRE_LIQUIDADO
                                );

                                $this->m_utils->registrarLogPlanObra($arrayDataLog);
                            }
                        }
                        $arrayData = array(
                            'porcentaje' => $porcentaje,
                            'fecha' => $this->fechaActual(),
                        );
                        $this->M_porcentaje->updateItemPlanEstacionAvance($arrayData, $itemplan, $idEstacion);
                    } else {
                        $arrayData = array(
                            'itemplan' => $itemplan,
                            'idEstacion' => $idEstacion,
                            'porcentaje' => 10,
                            'fecha' => $this->fechaActual(),
                        );
                        $this->M_porcentaje->insertItemPlanEstacionAvance($arrayData);
                    }

                    if ($from == 2 && $idEstacion == ID_ESTACION_FO) {

                        $ubicacion = 'uploads/evidencia_fotos/' . $itemplan . '/' . 'FO';
                        $cdir = scandir($ubicacion);
                        foreach ($cdir as $key => $value) {
                            if (!in_array($value, array(".", ".."))) {
                                $pendiente = $this->M_pqt_pre_liquidacion->getEvidenciasXEstacionItemPlan($itemplan, $idEstacion);
                                $i = 0;
                                foreach ($pendiente->result() as $row) {
                                    if ($row->path_pdf_pruebas == $ubicacion . '/' . $value || $row->path_pdf_perfil == $ubicacion . '/' . $value) {
                                        $i = $i + 1;
                                    }
                                }
                                if ($i == 0) {
                                    $dataFormularioEvidencias = array(
                                        'itemplan' => $itemplan,
                                        'fecha_registro' => $this->fechaActual(),
                                        'usuario_registro' => $this->session->userdata('idPersonaSession'),
                                        'idEstacion' => $idEstacion,
                                        'path_pdf_pruebas' => ($this->startsWith($value, 'PR_') ? $ubicacion . '/' . $value : null),
                                        'path_pdf_perfil' => ($this->startsWith($value, 'PP_') ? $ubicacion . '/' . $value : null)
                                    );

                                    $data = $this->M_pqt_pre_liquidacion->registrarEvidencias($dataFormularioEvidencias);
                                }
                            }
                        }
                    }
                }
            }
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            $data = null;
            $data['error'] = EXIT_ERROR;
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function startsWith($string, $startString) {
        $len = strlen($startString);
        return (substr($string, 0, $len) === $startString);
    }

    function registrarFichaSinfix($arrayJson, $itemPlan, $observacion, $idEstacion, $idFichaTecnicaBase) {
        $val = $this->registrarFicha($itemPlan, $observacion, $idEstacion, $idFichaTecnicaBase, $arrayJson);

        if ($val == 1) {
            $data['error'] = EXIT_SUCCESS;
            $arrayDataLog = array(
                'tabla' => 'sinfix',
                'actividad' => 'Registro Ficha',
                'itemplan' => $itemPlan,
                'fecha_registro' => $this->fechaActual(),
                'id_usuario' => $this->session->userdata('idPersonaSession'),
            );

            $this->m_utils->registrarLogPlanObra($arrayDataLog);
        } else {
            $data['error'] = EXIT_ERROR;
        }
    }

    function registrarFicha($itemPlan, $observacion, $idEstacion, $idFichaTecnicaBase, $arrayJson) {
        $idCuadrilla = $this->M_porcentaje->getCuadrillaOne($itemPlan, $idEstacion);
        $coordenada = $this->M_porcentaje->getCoordenadas($itemPlan);
        $dataInsert = array(
            'jefe_c_nombre' => $idCuadrilla,
            'observacion' => $observacion,
            'itemplan' => $itemPlan,
            'fecha_registro' => date("Y-m-d H:m:s"),
            'usuario_registro' => $this->session->userdata('idPersonaSession'),
            'coordenada_x' => $coordenada['coordX'],
            'coordenada_y' => $coordenada['coordY'],
            'flg_activo' => '1',
            'id_ficha_tecnica_base' => $idFichaTecnicaBase,
            'id_estacion' => $idEstacion
        );

        $val = $this->M_porcentaje->isertFichaTecnica($dataInsert, $arrayJson);
        return $val;
    }

    function enviarTrama($itemPlan, $indicador, $from, $jefatura, $descEmpresaColab) {
        // $data['error']    = EXIT_ERROR;
        // $data['msj']      = null;
        // $data['cabecera'] = null;
        // try{        
        // if($data['error'] == EXIT_ERROR){
        //     throw new Exception($data['msj']);
        // }           
        $dataSend = ['itemplan' => $itemPlan,
            'fecha' => $this->fechaActual(),
            'sisego' => $indicador];

        $url = 'https://172.30.5.10:8080/obras2/recibir_eje.php';

        $response = $this->m_utils->sendDataToURL($url, $dataSend);
        //if($response['error'] == EXIT_SUCCESS){
        $this->m_utils->saveLogSigoplus('LIQUIDACION OBRA', null, $itemPlan, null, $indicador, $descEmpresaColab, $jefatura, 'TRAMA COMPLETADA', 'OPERACION REALIZADA CON EXITO', 1, 4);
        //}else{
        // $this->m_utils->saveLogSigoplus('SINFIX', null, $itemPlan, null, $indicador, $descEmpresaColab, $jefatura, 'FALLA EN LA RESPUESTA DEL HOSTING', 'OPERACION NO COMPLETADA ERROR EN EL SERVIDOR DEL CLIENTE:'. strtoupper($response->mensaje), '2');
        //}
        //$data['tablaAsigGrafo'] = $this->makeHTLMTablaAsignarGrafo($this->m_liquidacion->getPtrToLiquidacion($SubProy,$eecc,$zonal,$itemPlan,$mesEjec,$area,$estado,FROM_BANDEJA_APROBACION,$ano));
        // }catch(Exception $e){
        //     $data['msj'] = $e->getMessage();
        // }
        // echo json_encode(array_map('utf8_encode', $data));
    }

    function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone', 'America/Lima');
        setlocale(LC_TIME, "es_ES", "esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }

    function createClusteFromSisego() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            header('Access-Control-Allow-Origin: *');
            /*             * *DATOS FROM SISEGOS** */
            $id_trama = $this->input->post('id');
            $hijos = $this->input->post('hijos');
            $sisego = $this->input->post('sisego');
            $segmento = $this->input->post('segmento');
            $fecha_envio = $this->input->post('fecha_envio');
            $mdf = $this->input->post('mdf');
            $coordX = $this->input->post('longitud');
            $coordY = $this->input->post('latitud');
            $cliente = $this->input->post('cliente');
            $idSubproy = $this->m_utils->getIdSubProyectoBySubProyectoDesc(strtoupper($segmento));
            if ($idSubproy == null) {
                throw new Exception('segmento no reconocido.');
            }

            $dataCentral = $this->m_utils->getIdCentralByCentralDesc($mdf);
            if ($dataCentral == null) {
                throw new Exception('MDF no registrado.');
            }
            $idCentral = $dataCentral['idCentral'];

            $existSisego = $this->m_utils->existeSisego($sisego);
            if ($existSisego['count'] >= 1) {
                $data['itemplan'] = $existSisego['itemplan'];
                throw new Exception('SISEGO ya se encuentra registrado.');
            }

            $codigo_cluster = $this->m_utils->getCodCluster();
            $dataPadre = array(
                'sisego' => $sisego,
                'fecha_envio' => $fecha_envio,
                'id_trama' => $id_trama,
                'segmento' => $segmento,
                'idCentral' => $idCentral,
                'idSubProyecto' => $idSubproy,
                'fecha_registro' => $this->fechaActual(),
                'estado' => 0,
                'codigo_cluster' => $codigo_cluster,
                'coordX' => $coordX,
                'coordY' => $coordY,
                'cliente' => $cliente,
                'flg_tipo' => 1
            );

            $hijosArray = array();
            $hijosArray = json_decode($hijos);
            //log_message('error', print_r($hijosArray, true));
            if ($hijosArray != null) {
                foreach ($hijosArray as $row) {
                    $row->codigo_cluster = $codigo_cluster;
                }
            }
            $data = $this->m_planobra->insertClusterFromSisego($dataPadre, $hijosArray);
            if ($data['error'] == EXIT_ERROR) {
                throw new Exception('Error interno al registrar el planobra_cluster - hijos.');
            } else {
                $data['codigo'] = $codigo_cluster;
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function aprobarCancelarCluster() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            header('Access-Control-Allow-Origin: *');
            /*             * *DATOS FROM SISEGOS** */
            $codigo = $this->input->post('codigo');
            $sisego = $this->input->post('sisego');
            $estado = $this->input->post('estado'); //1 aprobado  y 2 cancelado

            $arrayLog = array('codigo' => $codigo,
                'sisego' => $sisego,
                'estado' => $estado);

            $this->m_utils->saveLogSigoplus('TRAMA APROB - RECHAZO DATA', $codigo, null, NULL, $sisego, NULL, null, 'TRAMA COMPLETADA', 'INFO', null, 6, $arrayLog, json_encode($data));

            if ($estado == 1) {
                $datoArray = array(
                    "estado" => 2,
                    "fecha_aprobacion" => $this->fechaActual()
                );

                $data = $this->m_planobra->updateEstadoClusterByEstado($codigo, $datoArray, 1);
                // $data['msj']    = 'Se cancelo el cluster';
                $dataArray = array(
                    'codigo_cluster' => $codigo,
                    'fecha' => $this->fechaActual(),
                    'id_usuario' => 1645, //SISEGO
                    'estado' => 2
                );

                if ($data['error'] == EXIT_SUCCESS) {
                    $this->m_utils->insertLogCotizacionInd($dataArray);
                    $this->m_utils->saveLogSigoplus('TRAMA APROBAR COTIZACION', $codigo, 'APROBADO', null, $sisego, null, null, 'TRAMA COMPLETADA', 'OPERACION REALIZADA CON EXITO', 1, 6, $arrayLog, json_encode($data));
                }
            } else if ($estado == 2) {//cluster cancelado
                //UPDATE CLUSTER
                $datoArray = array("estado" => 3, //cluster cancelado
                    "fecha_aprobacion" => $this->fechaActual()
                );
                $data = $this->m_planobra->updateEstadoCluster($codigo, $datoArray);
                // $data['msj']    = 'Se cancelo el cluster';
                $dataArray = array(
                    'codigo_cluster' => $codigo,
                    'fecha' => $this->fechaActual(),
                    'id_usuario' => 1645, //SISEGO
                    'estado' => 3
                );
                $this->m_utils->insertLogCotizacionInd($dataArray);
                $this->m_utils->saveLogSigoplus('TRAMA RECHAZAR COTIZACION', $codigo, 'CANCELADO', null, null, null, null, 'TRAMA COMPLETADA', 'OPERACION REALIZADA CON EXITO', 1, 6, $arrayLog, json_encode($data));
            } else if ($estado == 3) {//si se pide rechazar varias cotizaciones de un sisego
                if ($sisego == null) {
                    throw new Exception('No envio el sisego');
                }

                $datoArray = array(
                    "estado" => 3,
                    "fecha_aprobacion" => $this->fechaActual()
                );
                $data = $this->m_planobra->updateEstadoBySisegoCotizacion($sisego, $datoArray, 2);
                // $data['msj']    = 'Se cancelo el cluster';
                if ($data['error'] == EXIT_SUCCESS) {
                    $this->m_utils->insertLogCotizacionIndBySisego($sisego);
                    $this->m_utils->saveLogSigoplus('TRAMA RECHAZAR COTIZACION', $codigo, 'CANCELADO', null, $sisego, null, null, 'TRAMA COMPLETADA', 'OPERACION REALIZADA CON EXITO', 1, 6, $arrayLog, json_encode($data));
                }
            } else {
                throw new Exception('ESTADO RECIBIDO INVALIDO:' . $estado);
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->m_utils->saveLogSigoplus('TRAMA APROBAR COTIZACION FROM SISEGO', $codigo, null, null, $sisego, null, null, 'ERROR EN RECEPCION DE TRAMA', $e->getMessage(), 2, 6, $arrayLog, json_encode($data));
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function tramaCrearSAM() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            header('Access-Control-Allow-Origin: *');
            /*             * *DATOS FROM SISEGOS** */
            $departamento = $this->input->post('departamento');
            $pronvincia = $this->input->post('pronvincia');
            log_message('error', '$codigo:' . $departamento);
            log_message('error', '$estado:' . $pronvincia);
            $data['itemplan'] = '18-00000001';
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    //METODO MOMENTANEO PARA PARALIZAR SISEGOS 20/02/2020

    function insertParalizacion($idMotivo, $comentario, $itemplan, $motivo, $origen) {
        $ubicacion = null;
        $dataArray = array(
            'itemplan' => $itemplan,
            'idMotivo' => $idMotivo,
            'comentario' => $comentario,
            'fechaRegistro' => $this->fechaActual(),
            'idUsuario' => 265,
            'flg_activo' => FLG_ACTIVO,
            'ubicacionEvidencia' => $ubicacion,
            'flgEstado' => $origen
        );
        $data = $this->M_pendientes->insertParalizacion($dataArray);

        $arrayDataItem = array('has_paralizado' => 1,
            'fecha_paralizado' => $this->fechaActual(),
            'motivo_paralizado' => $idMotivo,
            'fecha_reactiva_paralizado' => null
        );
        $data = $this->m_utils->simpleUpdatePlanObra($itemplan, $arrayDataItem);

        $arrayDataLog = array(
            'tabla' => 'planobra',
            'actividad' => 'Paralizacion desde crea. itemplan',
            'itemplan' => $itemplan,
            'fecha_registro' => $this->fechaActual(),
            'id_usuario' => 265
        );

        $this->m_utils->registrarLogPlanObra($arrayDataLog);

        // $dataSend = ['itemplan'      => $itemplan,
        // 'fecha'         => $this->fechaActual(),
        // 'flg_activo'    => FLG_ACTIVO,
        // 'motivo'        => $motivo,
        // 'nombreUsuario' => 'AUTOMATICO',
        // 'correo'        => null,
        // 'comentario'    => $comentario];
        // $url = 'https://172.30.5.10:8080/obras2/recibir_par.php';
        // $data = _trama_sisego($dataSend, $url, 7, $itemplan, 'ENVIAR PARALIZACION DESDE CREA. ITEM.', NULL);    
        return $data;
    }

}
