<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_mantenimiento_sbe extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=UTF-8');
        $this->load->model('mf_utils/m_utils');
		$this->load->model('mf_crecimiento_vertical/m_crecimiento_vertical');
		$this->load->model('mf_crecimiento_vertical/m_bandeja_reg_cv_negocio');
        $this->load->model('mf_plan_obra/m_planobra');
		$this->load->model('mf_servicios/M_integracion_sirope');
		$this->load->model('mf_pqt_terminado/m_pqt_terminado');
		$this->load->model('mf_certificacion/m_registro_oc_solicitud_masivo');
        $this->load->library('lib_utils');
		$this->load->library('excel');
        $this->load->helper('url');
        $this->load->library('zip');
    }

    public function index()
    {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {
            $zonas = $this->session->userdata('zonasSession');
            $idPersonaSession = $this->session->userdata('idPersonaSession');
            $descPerfilSesion = $this->session->userdata('descPerfilSession');
			
            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');

            $data['cmbEmpresaColab'] = __buildCmbEmpresaColab();
			$data['tablaItems'] = $this->getTablaItems(array());

            $permisos = $this->session->userdata('permisosArbolTransporte');
			$result = $this->lib_utils->getHTMLPermisos($permisos, 54, 334, ID_MODULO_PAQUETIZADO);
            $data['opciones'] = utf8_encode($result['html']);
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_adm_sbe/v_mantenimiento_sbe', $data);
            } else {
                redirect('login', 'refresh');
            }
        } else {

            redirect('login', 'refresh');
        }

    }

    function regPartidaMantSbe() {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null; 
        try {


            $arrayJson = array();
            $arrayItemplan = array();
			$arrayCotizacion = array();
            $cont = 0;
            $idEstacion = null;
            $flgValida = 0;
            $flgPartidasNoExist = 0;
			$arrayRegistro = array();
			$arrayLog = array();
			$idUsuario = $this->session->userdata('idPersonaSession');
			
            if($idUsuario == null) {
                throw new Exception('La sesi&oacute;n a expirado, recargue la p&aacute;gina');
            }

            if(isset($_FILES['file']['name'])) {
                $path   = $_FILES['file']['tmp_name'];
                $object = PHPExcel_IOFactory::load($path);
                foreach($object->getWorksheetIterator() as $worksheet) {
                    $highestRow    = $worksheet->getHighestRow();
                    $highestColumn = $worksheet->getHighestColumn();
					$cont = 1;
                    for($row=2; $row<=$highestRow; $row++) {
                        $codigo       		  = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                        $partidaDesc          = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
						$zona 		      	  = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
						$empDesc      		  = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
						$subProyDesc   		  = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
						$costo     		      = $worksheet->getCellByColumnAndRow(5, $row)->getValue();

						// $estado_edificio 		  = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
						// $tipo_proyecto 		      = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
						
						// $ruc       		          = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
						// $nomb_contructora         = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
						// $contacto_1 		 	  = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
						// $telefono_1 		      = $worksheet->getCellByColumnAndRow(14, $row)->getValue();
						// $telefono_1_2 		      = $worksheet->getCellByColumnAndRow(15, $row)->getValue();
						// $direccion 		          = $worksheet->getCellByColumnAndRow(16, $row)->getValue();
						// $distrito 				  = $worksheet->getCellByColumnAndRow(17, $row)->getValue();

						$idSubProyecto  = $this->m_utils->getIdSubProyectoBySubProyectoDesc($subProyDesc);
						$dataZona       = $this->m_utils->getZonaByDesc($zona);
						$idEmpresaColab = $this->m_utils->getEmpresaColabByDesc($empDesc);
						
						$idZona = $dataZona['id_zona'];
						
						$dataArray['observacion'] = null;

						if($idZona == null || $idZona == '') {
							$dataArray['observacion'] = 'Ingresar Zona.';
						}
						
						if($idSubProyecto == null || $idSubProyecto == '') {
							$dataArray['observacion'] = 'Ingresar SubProyecto.';
						}
						
						if($idEmpresaColab == null || $idEmpresaColab == '' || $idEmpresaColab == 0 ) {
							$dataArray['observacion'] = 'Ingresar Empresa colab.';
						}
						
						if($codigo == null || $codigo == '') {
							$dataArray['observacion'] = 'Ingresar codigo.';
						}
						$dataPartida = $this->m_utils->getPartidaZonaEm($partidaDesc, $idZona, $idEmpresaColab);
						
						
						$idPartida = $dataPartida['id_partida'];

						if($idPartida == 0 || $idPartida == null) {
							$idPartida = $this->m_utils->getPartidaByDesc($partidaDesc);
						}
						
						if($idPartida == 0 || $idPartida == null) {
							$dataArray['observacion'] = 'No se encuentra la partida.';
						}
						
						$countPartida = $this->m_utils->getCountPartidaSBE($idPartida, $idZona, $idEmpresaColab, $idSubProyecto);
						
						if($countPartida > 0) {
							$dataArray['observacion'] = 'Ya esta asignado la partida.';
						}

						if($dataArray['observacion'] != null) {
							$dataLog = array(
												"nro"         => $cont,
												"codigo"      => $codigo,
												"partida"     => $partidaDesc,
												"subproyecto" => $subProyDesc,
												"observacion" => $dataArray['observacion']				
											);
						} else {
							$dataRegistro = array(
													"id_partida"     => $idPartida,
													"idZona"         => $idZona,
													"idEmpresaColab" => $idEmpresaColab,
													"idSubProyecto"  => $idSubProyecto,
													"costo"          => $costo,
													"codigo"         => $codigo
												);
		
							array_push($arrayRegistro, $dataRegistro);
							
							$dataLog = array(
												"nro"         => $cont,
												"codigo"      => $codigo,
												"partida"     => $partidaDesc,
												"subproyecto" => $subProyDesc,
												"observacion" => 'OK'
											);
						}
						
						array_push($arrayLog, $dataLog);
						$cont++;
                    }
                }
            }
			// list($data, $dataTabla) = $this->insertItemplan($arrayRegistro);
			
			if(count($arrayRegistro) > 0) {
				$data = $this->m_utils->insertPartidaSbeMasivo($arrayRegistro);

				if($data['error'] == EXIT_ERROR) {
					throw new Exception($data['msj']);
				}
			}

			$data['error']    = EXIT_SUCCESS;
			$data['tablaItem'] = $this->getTablaItems($arrayLog);
        }catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }
	
	function getTablaItems($dataTabla) {
        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
							<th>NRO</th>
							<th>CODIGO</th>
                            <th>PARTIDA</th>
                            <th>SUBPROYECTO</th>
							<th>OBSERVACION</th>
                        </tr>
                    </thead>                    
                    <tbody>';
                                                                         
                foreach($dataTabla as $row){
                    $html .=' <tr>
                                <td>'.$row['nro'].'</td>
								<td>'.$row['codigo'].'</td>
                                <td>'.$row['partida'].'</td>
								<td>'.$row['subproyecto'].'</td>
								<td>'.$row['observacion'].'</td>
                            </tr>';   
				}
			$html .='
					</tbody>
                </table>';
                    
            return $html;
    }
	
	function insertItemplan($dataRegistro) {
		$data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
    
		$fase = ID_FASE_ANIO_CREATE_ITEMPLAN;
		$fechaActual = $this->m_utils->fechaActual();
		$arrayTablaItem = array();
		$idUsuario = $this->session->userdata('idPersonaSession');
		// json_encode($dataRegistro)
		$dataArray['data_cargada'] = NULL;
		foreach($dataRegistro as $row){
			$cant_cto       = $row['cant_cto']; /* Cuya */
			
			$idSubProyecto  = $row['idSubProyecto'];
			$idTipoUrba     = NULL;            
			$nombreUrba     = NULL;
			$idTipoVia      = NULL;
			$direccion      = $row['direccion'];
			$numero         = NULL;
			$manzana        = $row['manzana'];
			$lote           = $row['lote'];
			$nombreProyecto = $row['nom_proyecto'];
			$blocke         = NULL;
			$num_pisos      = $row['nro_pisos'];
			$num_depa       = $row['nro_dep'];
			$num_depa_habi  = NULL;
			$avance         = 10;
			$fec_termino    = NULL;     
			$observacion    = NULL;
			$ruc            = $row['ruc'];
			$nombre_constru = $row['nomb_contructora'];
			$contacto_1     = $row['contacto_1'];
			$telefono_1_1   = $row['telefono_1'];
			$telefono_1_2   = $row['telefono_1_2'];
			$email_1        = NULL;
			$contacto_2     = NULL;
			$telefono_2_1   = NULL;
			$telefono_2_2   = NULL;
			$email_2        = NULL;
			$coord_x        = $row['longitud'];
			$coord_y        = $row['latitud'];
			$departamento   = NULL;
			$provincia      = NULL;

			$accion         = 1;//INDICA QUE ES REGISTRO
			$estado_edifi   = $row['estado_edificio'];
		   
			$prioridad      = NULL;
			$competencia    = NULL;
			$per_actu       = NULL;
			
			$tipoSubProy    = $row['tipo_proyecto'];///HFC O FTTH
			$eelec = 6;
			$distrito       = $row['distrito'];
			/////
			$idCentral     = $row['idCentral'];
			$idSubProyecto = $row['idSubProyecto'];
			$idEECC        = $row['idEmpresaColab'];
			$idZonal       = $row['idZonal'];
			
			$idTipoSubProyecto = $this->input->post('idTipoSubProyecto');
			////
			$fechaInicio = $fechaActual;
			$indicador = '';
			$uip = 0;
			$cantidadTroba = 0;
			
			$dataArray['nro']      	     = $row['nro'];
			
			$dataArray['subproyecto']    = $row['subProyectoDesc'];
			$dataArray['fecha_registro'] = $fechaActual;
			$dataArray['id_usuario']     = $idUsuario;
						
 			if($idEECC == null || $idEECC == ''){
				$dataArray['observacion'] = 'VERIFICAR QUE ESTE BIEN ESCRITO LA CONTRATA, Y SE SE UBIQUE EN LA COLUMNA CORRECTA EN EL EXCEL SEGÚN EL MODELO.';
				$idEECC = null;
			} else 	if($idEECC == 7/* || $idEECC == 8*/) {
				$dataArray['observacion'] = 'NO SE PERMITE EL REGISTRO DE QUANTA.';
				$idEECC = null;
			} else if($coord_y <= -68 && $coord_y >= -81) {// SI LA COORDENADA Y SE ENCUENTRA EN EL REANGO DE LA LONG.
				$dataArray['observacion'] = 'La coordenada "X" (longitud), que se esta mandando pertenece a "Y" (latitud), enviar de manera correcta.';
				$idEECC = null;
			} else {
				$dataSubProyecto = $this->m_utils->getDataSubProyectoById($idSubProyecto);
			
				// if($dataSubProyecto['idTipoSubProyecto'] == 2) {
					// if($idEECC != 9 && $idEECC != 14 && $idEECC != 17 && $idEECC != 18) {
						// $dataArray['observacion'] = 'LA CONTRATA INGRESADA NO PERTENECE AL INTEGRAL.';
						// $idEECC = null;
					// }
				// }
				
				if($idSubProyecto == ID_SUBPROYECTO_CABLEADO_EDIFICACION_RESIDENCIA_INTEGRAL || $idSubProyecto == ID_SUBPROYECTO_CABLEADO_EDIFICACION_RESIDENCIA_OVERLAY_INTEGRAL
				   || $idSubProyecto == 670 ||  $idSubProyecto == 663 ||  $idSubProyecto == 665 || $idSubProyecto == 671 || $idSubProyecto == ID_SUBPROYECTO_CABLEADO_EDIFICIOS_CTO_ADICIONAL) {
					if($idEECC != 8 && $idEECC != 9 && $idEECC != 14 && $idEECC != 17 && $idEECC != 18) {
						$dataArray['observacion'] = 'LA CONTRATA INGRESADA, NO PUEDE PERTENECER A ESTE SUBPROYECTO.';
						$idEECC = null;
					}
					/**czavala 1.10.2021 si es cto adicional la uip siempre debe ser 1**/
					if($idSubProyecto == ID_SUBPROYECTO_CABLEADO_EDIFICIOS_CTO_ADICIONAL){
						$num_depa = 1;//siempre 1
					}
					
					/***/
					
					
					/* CUYA */
					// if($idSubProyecto == 663 ||  $idSubProyecto == 665) {
						// if($cant_cto == null || $cant_cto == '') {
							// $dataArray['observacion'] = 'DEBE INGRESAR CANTIDAD DE CTO.';
							// $idEECC = null;
						// }
					// }
					/*----------------------------------------------------- */
					
				} else {
					if($dataSubProyecto['idTipoSubProyecto'] == 1) {
						if($idEECC != 1 && $idEECC != 2 && $idEECC != 3 && $idEECC != 4 && $idEECC != 10 && $idEECC != 11) {
							$dataArray['observacion'] = 'LA CONTRATA INGRESADA NO PERTENECE AL CONTRATO BUCLE.';
							$idEECC = null;
						}
						
						// if($idEECC != $row['idEmpresaColabCentral']) { //SI LA CONTRATA QUE INGRESARON ES DISTINTA A LA DE LA CENTRAL
							// $dataArray['observacion'] = 'LA CONTRATA INGRESADA, NO PERTENECE LA ZONIFICACION QUE TIENE ESTA OBRA.';
							// $idEECC = null;
						// }
					}
				}
			}
										
			if($idSubProyecto == null){
				$dataArray['observacion'] = 'VERIFICAR QUE ESTE BIEN ESCRITO EL SUBPROYECTO.';
			}

			if($idCentral == null){
				$dataArray['observacion'] = 'ERROR INTERNO, CENTRAL NO ENCONTRADO.';
			}

			if($idZonal == null) {
				$dataArray['observacion'] = 'VERIFICAR QUE ESTE BIEN ESCRITO LA ZONAL.';
			}
			
			if($longitud == null || $longitud == '' || !is_numeric($longitud)) {
				$longitud = null;
				$dataArray['observacion'] = 'DEBE INGRESAR LA LONGITUD VALIDO1.';
			}
			
			if($latitud == null || $latitud == '' || !is_numeric($latitud)) {
				$latitud = null;
				$dataArray['observacion'] = 'DEBE INGRESAR LA LATITUD VALIDO.';
			}
					
			if($idZonal != null && $idCentral != null && $idSubProyecto != null && 
			   $idEECC != null && $longitud != null && $latitud != null) {
				
				$dataSubProyecto = $this->m_utils->getDataSubProyectoById($idSubProyecto);
				$paquetizado_fg = $dataSubProyecto['paquetizado_fg'];
				if($paquetizado_fg == null || $paquetizado_fg == '') {
					$paquetizado_fg = 1;
				}
				$idCentralPqt   = $idCentral;
				$infoSub    = $this->m_utils->getSubProyectoById(array($idSubProyecto), 1); // 1: flg que te va a traer solo una fila
				
				$idTipoSubProyectoBOI = $this->m_crecimiento_vertical->getBucleIntegralBySubProy($idSubProyecto);
				
				$base_itemplan = $this->m_planobra->generarCodigoItemPlan(ID_PROYECTO_CRECIMIENTO_VERTICAL, $idZonal);
				// $dataSubProyecto['costo_unitario_mat'];
				$costo_unitario_mat = 0;
				$data = NULL;
				$data['error'] = EXIT_ERROR;

				if(in_array($idSubProyecto, array(663,665,693,703))){
					$flgTipo = $this->m_bandeja_reg_cv_negocio->getFlgTipoByIdSubProy($idSubProyecto);
					$dataCTO = $this->m_bandeja_reg_cv_negocio->getCtoByFlgTipoAndNroDepa($num_depa, $flgTipo);
					if($dataCTO!=null){
						$cant_cto =  $dataCTO['nro_cto_colocar'];
					}else{
						$cant_cto	=	0;
					}		
				}
					
					
				$data = $this->m_bandeja_reg_cv_negocio->inserMasivoItemplan(ID_PROYECTO_CRECIMIENTO_VERTICAL, $idSubProyecto, ESTADO_PLAN_PRE_REGISTRO, $eelec, $fechaInicio, $nombreProyecto, $indicador, $uip, $coord_x, 
																  $coord_y,$cantidadTroba, $departamento, $provincia, $distrito, $idTipoUrba, $nombreUrba, $idTipoVia, 
																  $direccion, $numero, $manzana, $lote, $blocke, $num_pisos, $num_depa, $num_depa_habi, $avance, 
																  $fec_termino, $observacion, $ruc, $nombre_constru, $contacto_1, 
																  $telefono_1_1, $telefono_1_2, $email_1, $contacto_2, $telefono_2_1, $telefono_2_2, $email_2, 
																  $accion, $base_itemplan, $estado_edifi, $idCentral, $competencia, $prioridad, $idEECC, $tipoSubProy, $fase, $operador, 
																  $paquetizado_fg, $idCentralPqt, $costo_unitario_mat, $dataSubProyecto['costo_unitario_mo'], $idZonal, $cant_cto); /* cuya AGREGUE CANT_CTO */
				
				$last_itemplan = $this->m_utils->getLastItemplanByPrefijoItemplan($base_itemplan);
				
				if($data['error']  ==  EXIT_SUCCESS){
					$dataArray['itemplan']    = $last_itemplan;
					$dataArray['observacion'] = 'REGISTRO CORRECTO';
					// if(in_array($idSubProyecto, array(663,665,670,671))){
					// 	$this->M_integracion_sirope->execWs($last_itemplan, $last_itemplan.'FO',date('Y-m-d'),date('Y-m-d',strtotime('+' . 7 . ' day', strtotime(date('Y-m-d')))),'PROJECT');
					// 	$estacionesAnclas = $this->m_pqt_terminado->getEstacionesAnclasByItemplan($last_itemplan);
					// 	foreach ($estacionesAnclas as $estacion){
					// 		$hasPoPqtACtive = $this->m_utils->hasPoPqtActive($last_itemplan, $estacion->idEstacion);
					// 		if($hasPoPqtACtive == 0){
					// 			$dataPoPqt = $this->savePoMo($last_itemplan, $estacion->idEstacion);
					// 			if($dataPoPqt['error'] == EXIT_SUCCESS){//SI SE CREO LA PO PQT
					// 				$costo_mo = $dataPoPqt['costoTotalPoPqt'];
									
					// 			}
					// 		}
					// 	}
					// }

					// $this->db->trans_commit();
				}else{
					$dataArray['observacion'] = $data['msj'];
				}
			}
				 // $this->db->trans_begin();
			
			array_push($arrayTablaItem, $dataArray);	
		}
		
		// $this->m_utils->insertLogItemplanCvMasivo($arrayTablaItem);
		
		return array($data, $arrayTablaItem);
	}
	
	public function savePoMo($itemplan_in, $idestacion_in, $idusuario_in = null){
		$data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        $global_codigo_po = null;
        try{
			if($idusuario_in == null){
				$idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;
			}else{
				$idUsuario = $idusuario_in;
			}
			
            if($idUsuario != null){
                 
                $itemplan = $itemplan_in;
                $idEstacion  = $idestacion_in;
				$fechaActual = $this->m_utils->fechaActual();
                $arrayPartidasInsert = array();
    
                $infoItm = $this->m_pqt_terminado->getInfoBasicToGeneratePartidasByItemplan($itemplan);
                if($infoItm == null){
                    throw new Exception('Excepcion detectada, comuniquese con soporte.');
                }
                $listaPartidasToCreate = $this->getArrayPartidasToPO($idEstacion, $infoItm['idSubProyecto'], $infoItm['idEmpresaColab'], $infoItm['isLima'], $itemplan);
    
                if($listaPartidasToCreate!=null){
                     
                    $codigoPO = $this->m_utils->getCodigoPO($itemplan);
                    if ($codigoPO == null) {
                        throw new Exception('Hubo un error al generar el codigo PO ');
                    }
                    $global_codigo_po = $codigoPO;
                    $costoTotalPOMO = 0;
                    foreach($listaPartidasToCreate as $datos){
                        $partidaInfo = array();
                        $partidaInfo = $datos;
                        $partidaInfo['codigo_po']  = $codigoPO;
                        array_push($arrayPartidasInsert, $partidaInfo);
                        $costoTotalPOMO = $costoTotalPOMO + $datos['monto_final'];
                    }
                    
                    /****nuevo de ferreteria czavala 20.09.2021******/
                    if($infoItm['idTipoSubProyecto']!=3 ||	$infoItm['idTipoSubProyecto'] == null){
						$infoCostoMax = $this->m_pqt_reg_mat_x_esta_pqt->getCostoMaxMatAndCostoMByItemplan($itemplan, $idEstacion);
						if($infoCostoMax['monto']==null){
							throw new Exception('El subproyecto no cuenta con un costo KIT MATERIAL configurado.');
						}
					}else{
						$infoCostoMax = array(
							"monto" => null
						);
					}
                    
                    $partidasOutPut = array();
                    $arrayActividades = array();
                    $codigoFerreteria = '69901-2';#ponerlo como constante
                    $infoPartida =  $this->m_pqt_terminado->getInfoPartidaByCodPartida($itemplan, $idEstacion, $codigoFerreteria);
                    if($infoPartida != null){
                        $precioTmp = $infoPartida['costo'];
                    }else{
						$precioTmp = null;
					}
                    
                    $totalFerr = $infoCostoMax['monto'];
                    $cantidadFerr = 0;
					if(isset($totalFerr) && isset($precioTmp)){
						$cantidadFerr = ($totalFerr/$precioTmp);
                    }
                    if(!in_array($infoPartida['idActividad'], $arrayActividades)&& isset($totalFerr) && isset($precioTmp)){
                        $partidaFerreteria = array();
                        $partidaFerreteria['codigo_po']        = $codigoPO;
                        $partidaFerreteria['idActividad']      = $infoPartida['idActividad'];
                        $partidaFerreteria['baremo']           = $infoPartida['baremo'];
                        $partidaFerreteria['costo']            = $precioTmp;
                        $partidaFerreteria['cantidad_inicial'] = $cantidadFerr;
                        $partidaFerreteria['monto_inicial']    = $totalFerr;
                        $partidaFerreteria['cantidad_final']   = $cantidadFerr;
                        $partidaFerreteria['monto_final']      = $totalFerr;
                        array_push($arrayPartidasInsert, $partidaFerreteria);//metemos idActividad
                        $costoTotalPOMO = $costoTotalPOMO + $totalFerr;
                    }
                    /***********fin de ferreteria*******/
                    
                    $idEecc = $infoItm['idEmpresaColab'];
                    $from = 1;//harcodeamos oepraciones
    
                    $dataPO = array(
                        'itemplan'      => $itemplan,
                        'codigo_po'     => $codigoPO,
                        'estado_po'     => PO_REGISTRADO, //ESTADO REGISTRADO
                        'idEstacion'    => $idEstacion,
                        'from'          => $from,
                        'costo_total'   => $costoTotalPOMO,
                        'idUsuario'     => $idUsuario,
                        'fechaRegistro' => $fechaActual,
                        'estado_asig_grafo' => 0,
                        'flg_tipo_area' => 2,//MANO DE OBRA
                        'id_eecc_reg'   => $idEecc,
                        'isPoPqt'       =>  1//que es paquetizada la po
                    );
    
                    $dataLogPO = array();
                    $dataLogPO_tmp = array(
                        'codigo_po'         =>  $codigoPO,
                        'itemplan'          =>  $itemplan,
                        'idUsuario'         =>  $idUsuario,
                        'fecha_registro'    =>  $fechaActual,
                        'idPoestado'        =>  PO_REGISTRADO,
                        'controlador'       =>  (($from ==  1) ? 'consulta' : 'diseno')
                    );
    
                    array_push($dataLogPO, $dataLogPO_tmp);
                     
                    $subProyectoEstacion = $this->m_pqt_terminado->getSubProyectoEstacionByItemplanEstacion($itemplan, $idEstacion);
                    if($subProyectoEstacion ==  null){
                        throw new Exception('Hubo un error obtener el subproyecto - estacion');
                    }
                    $dataDetalleplan = array('itemPlan' =>  $itemplan,
                        'poCod'    => $codigoPO,
                        'idSubProyectoEstacion' =>  $subProyectoEstacion);
    
    
                    $dataPqtTmp = array (   
						'itemplan'          =>  $itemplan,
                        'idEstacion'        =>  $idEstacion,
                        'estado'            =>  1,//validado
                        'codigo_po'         =>  $codigoPO,
                        'fecha_validado'    =>  $fechaActual,
                        'usuario_valida'    =>  $idUsuario
                    );
                    $tipoTmpPoCreate = 1;//1 = insert;
                    $hasPtrCreado = $this->m_pqt_terminado->getEstatusEstacionItemplan($itemplan, $idEstacion);
                    if($hasPtrCreado != null){
                        $tipoTmpPoCreate = 2;//2 = update
                        $dataPqtTmp['id_pqt_partidas'] = $hasPtrCreado['id_pqt_partidas'];
                    }
    
                    /***porsiacaso**/
                    $dataUpdateSolicitud = array (
						'estado'            => 2,
                        'usua_val_nivel_2'  => $idUsuario,
                        'fec_val_nivel_2'   => $fechaActual,
                        'itemplan'          => $itemplan,
                        'idEstacion'        => $idEstacion
                    );
                    /****/
                    $data = $this->m_pqt_terminado->createPoMO($dataPO, $dataLogPO, $dataDetalleplan, $arrayPartidasInsert, $tipoTmpPoCreate, $dataPqtTmp, $dataUpdateSolicitud);
                    if($data['error']   ==  EXIT_ERROR){
                        throw new Exception('Hubo un error interno, en la transaccion');
                    }
					$data['costoTotalPoPqt'] = $costoTotalPOMO;
                    $data['codigoPO'] = $codigoPO;
                    $data['error'] = EXIT_SUCCESS;
                }else{
                    throw new Exception('No se encontro partidas paquetizadas para la configuracion.> idEstacion:'.$idEstacion.' | idSubProyecto:'.$infoItm['idSubProyecto'].' | idEmpresaColab:'.$infoItm['idEmpresaColab'].' | tipoJefatura:'.$infoItm['isLima'].' | itemplan:'.$itemplan);
                }
            }else{
                throw new Exception('Su sesion expiro, porfavor vuelva a logearse.');
            }
             
        }catch(Exception $e){
            $this->m_registro_oc_solicitud_masivo->insertLogErrorCreacioPO(array('itemplan' => $itemplan_in, 'idEstacion' =>$idestacion_in, 'mensaje' => $e->getMessage(), 'estado' => 1, 'codigo_po' => $global_codigo_po));
            // $data['msj'] = $e->getMessage();
        }
        //echo json_encode(array_map('utf8_encode', $data));
        return $data;
	}
	
	function getArrayPartidasToPO($idEstacion, $idSubProyecto, $idEmpresaColab, $isLima, $itemplan){
        $partidasOutPut = array();
    
        /*Obtenemos paquetizados*/
        $listaPartidasPqt = $this->m_pqt_terminado->getPartidasPaquetizadasByItemEccJefaTuraEstacionCrearPoPqt($idEstacion, $idSubProyecto, $idEmpresaColab, $isLima, $itemplan);
        $arrayActividades = array();
        foreach ($listaPartidasPqt as $row){
            if($row->id_tipo_partida == 1 ||$row->id_tipo_partida == 5){#DISEÑO FO y COAXIAL      
				if(!in_array($row->idActividad, $arrayActividades)){
					$dataCMO['idActividad']      = $row->idActividad;
					$dataCMO['baremo']           = $row->baremo;
					$dataCMO['costo']            = $row->costo;
					$dataCMO['cantidad_inicial'] = $row->cantFactorPlanificado;
					$dataCMO['monto_inicial']    = $row->total;
					$dataCMO['cantidad_final']   = $row->cantFactorPlanificado;
					$dataCMO['monto_final']      = $row->total;
					array_push($arrayActividades, $row->idActividad);//metemos idActividad
					array_push($partidasOutPut, $dataCMO);              
                }
            }else if($row->id_tipo_partida == 2 ||$row->id_tipo_partida == 6){#LICENCIA FO y COAXIAL               
				if(!in_array($row->idActividad, $arrayActividades)){
					$dataCMO['idActividad']      = $row->idActividad;
					$dataCMO['baremo']           = $row->baremo;
					$dataCMO['costo']            = $row->costo;
					$dataCMO['cantidad_inicial'] = $row->cantFactorPlanificado;
					$dataCMO['monto_inicial']    = $row->total;
					$dataCMO['cantidad_final']   = $row->cantFactorPlanificado;
					$dataCMO['monto_final']      = $row->total;
					array_push($arrayActividades, $row->idActividad);//metemos idActividad
					array_push($partidasOutPut, $dataCMO);                
                }
            }else if($row->id_tipo_partida == 3 ||$row->id_tipo_partida == 4 || $row->id_tipo_partida == 7 ||$row->id_tipo_partida == 8){#TENDIDO Y EMPALMADOR FO Y COAXIAL
          
				if(!in_array($row->idActividad, $arrayActividades)){
					$dataCMO['idActividad']      = $row->idActividad;
					$dataCMO['baremo']           = $row->baremo;
					$dataCMO['costo']            = $row->costo;
					$dataCMO['cantidad_inicial'] = $row->cantFactorPlanificado;
					$dataCMO['monto_inicial']    = $row->total;
					$dataCMO['cantidad_final']   = $row->cantFactorPlanificado;
					$dataCMO['monto_final']      = $row->total;
					array_push($arrayActividades, $row->idActividad);//metemos idActividad
					array_push($partidasOutPut, $dataCMO);
                    
                }
            } else if($row->id_tipo_partida == 9){#FUENTE Y ENERGIA             
				if(!in_array($row->idActividad, $arrayActividades)){
					$dataCMO['idActividad']      = $row->idActividad;
					$dataCMO['baremo']           = $row->baremo;
					$dataCMO['costo']            = $row->costo;
					$dataCMO['cantidad_inicial'] = $row->cantFactorPlanificado;
					$dataCMO['monto_inicial']    = $row->total;
					$dataCMO['cantidad_final']   = $row->cantFactorPlanificado;
					$dataCMO['monto_final']      = $row->total;
					array_push($arrayActividades, $row->idActividad);//metemos idActividad
					array_push($partidasOutPut, $dataCMO);
				}
            }else if($row->id_tipo_partida == 10    ||  $row->id_tipo_partida == 11){#INTEGRAL E INTEGRAL OVERLAY           
				if(!in_array($row->idActividad, $arrayActividades)){
					$dataCMO['idActividad']      = $row->idActividad;
					$dataCMO['baremo']           = $row->baremo;
					$dataCMO['costo']            = $row->costo;
					$dataCMO['cantidad_inicial'] = $row->cantFactorPlanificado;
					$dataCMO['monto_inicial']    = $row->total;
					$dataCMO['cantidad_final']   = $row->cantFactorPlanificado;
					$dataCMO['monto_final']      = $row->total;
					array_push($arrayActividades, $row->idActividad);//metemos idActividad
					array_push($partidasOutPut, $dataCMO);
				}
            }else if($row->id_tipo_partida == 12    ||  $row->id_tipo_partida == 13){#INTEGRAL megaproyecto E INTEGRAL OVERLAY   megaproyecto        
				if(!in_array($row->idActividad, $arrayActividades)){
					$dataCMO['idActividad']      = $row->idActividad;
					$dataCMO['baremo']           = $row->baremo;
					$dataCMO['costo']            = $row->costo;
					$dataCMO['cantidad_inicial'] = $row->cantFactorPlanificado;
					$dataCMO['monto_inicial']    = $row->total;
					$dataCMO['cantidad_final']   = $row->cantFactorPlanificado;
					$dataCMO['monto_final']      = $row->total;
					array_push($arrayActividades, $row->idActividad);//metemos idActividad
					array_push($partidasOutPut, $dataCMO);
				}
            }else if($row->id_tipo_partida == 14){#CTO ADICIONAL
				if(!in_array($row->idActividad, $arrayActividades)){
					$dataCMO['idActividad']      = $row->idActividad;
					$dataCMO['baremo']           = $row->baremo;
					$dataCMO['costo']            = $row->costo;
					$dataCMO['cantidad_inicial'] = $row->cantFactorPlanificado;
					$dataCMO['monto_inicial']    = $row->total;
					$dataCMO['cantidad_final']   = $row->cantFactorPlanificado;
					$dataCMO['monto_final']      = $row->total;
					array_push($arrayActividades, $row->idActividad);//metemos idActividad
					array_push($partidasOutPut, $dataCMO);
				}
            }
        }       
        return $partidasOutPut;
    }
	
	
}