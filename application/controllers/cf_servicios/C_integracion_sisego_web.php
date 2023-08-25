<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 *
 *
 */
class C_integracion_sisego_web extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_plan_obra/m_consulta');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_log/m_log_ingfix');
		$this->load->model('mf_servicios/M_integracion_sisego_web');
        $this->load->library('lib_utils');
        $this->load->library('map_utils/coordenadas_utils');
        $this->load->helper('url');
	//
        $this->load->model('mf_plan_obra/m_planobra'); //m_subproy_pep_grafo
        $this->load->model('mf_mantenimiento/m_subproy_pep_grafo'); //m_bandeja_adjudicacion
        $this->load->model('mf_pre_diseno/m_bandeja_adjudicacion');
		$this->load->model('mf_cotizacion/m_cotizacion_alcance_robot');
    }

    public function index()
    {      
        //_log($this->coordenadas_utils->getBuscarArea([-77.016322, -12.108402]));
    }  
    
    /*public function crearCotizacionIndividual()
    {
    
        header("Access-Control-Allow-Origin: *");
        $output['codigo'] = EXIT_ERROR;
        $output['mensaje'] = 'No se creo la Cotizacion';
        try {
            $method = $_SERVER['REQUEST_METHOD'];
            if($method=='PUT' || $method=='POST'){
                $inputJSON = file_get_contents('php://input');
                $input = json_decode( $inputJSON, TRUE );
                log_message('error', 'COTIZACION:'.$this->input->post('sisego'));
                log_message('error', 'COTIZACION INDIVIDUAL:'.print_r($input,true));            
                $output['codigo'] = EXIT_SUCCESS;
                $output['mensaje'] = "Se creo la Cotizacion Nro. 222".$input['codigo'];
            }else{
                $output = $this->getMsjTypeMethod($method, 'createCotizacion');
            }
        } catch (Exception $e) {
            $output['mensaje'] = 'No se creo la Cotizacion, '.$e->getMessage();
        }
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($output));
    } */
    
    // public function crearCotizacionIndividual()
    // {
        // $data['error']    = EXIT_ERROR;
        // $data['msj']      = null;
        // try {
            // // $method = $_SERVER['REQUEST_METHOD'];
            // // if($method=='PUT' || $method=='POST'){
                // // $inputJSON = file_get_contents('php://input');
                // // $input = json_decode( $inputJSON, TRUE );

                // $codigo_cluster = $this->m_utils->getCodCluster();
   
                // $id              = $this->input->post('id');    
                // $sisego          = $this->input->post('sisego');
                // $segmento        = $this->input->post('segmento');
                // $cliente         = $this->input->post('cliente');
                // $descripcion     = $this->input->post('descripcion');
                // $servicios       = $this->input->post('servicios');
                // $acceso_cliente  = $this->input->post('acceso_cliente');
                // $tendido_externo = $this->input->post('tendido_externo');
                // $tipo_cliente    = $this->input->post('tipo_cliente');
                // $nro_pisos       = $this->input->post('nro_pisos');
                // $departamento    = $this->input->post('departamento');
                // $provincia       = $this->input->post('provincia');
                // $distrito        = $this->input->post('distrito');
                // $direccion       = $this->input->post('direccion');
                // $piso            = $this->input->post('piso');
                // $interior        = $this->input->post('interior');
                // $latitud         = $this->input->post('latitud');
                // $longitud        = $this->input->post('longitud');
                // $nombre_estudio  = $this->input->post('nombre_estudio');
                // $tipoRequerimiento = $this->input->post('tipo_requerimiento');
                // $clasificacion     = $this->input->post('clasificacion');
                // $tipoProyecto     = $this->input->post('tipo_proyecto');
                // $eecc             = $this->input->post('eecc');
                // $flg_principal    = $this->input->post('flg_principal');
                // $tipo_enlace      = $this->input->post('tipo_enlace');
				
				// $flg_lan_to_lan   = $this->input->post('flg_lan_to_lan');
                
                // $idSubproyecto   = $this->m_utils->getIdSubProyectoBySubProyectoDesc(strtoupper(trim($segmento)));            
                
                // $this->db->trans_begin();

                // if($idSubproyecto == null){
                    // throw new Exception('segmento no reconocido.');
                // }
                
                // // if($eecc != NULL || $eecc != '') {
                // //     $idEmpresaColab = $this->m_utils->getEmpresaColabByDesc($eecc);
                // //     $info = $this->m_utils->getInfoCentralByDistritoEECC($distrito, $idEmpresaColab);
                // //     $idCentral = $info['idCentral'];
                // //     if($idCentral == null || $idCentral == '') {
                // //         $info = $this->m_utils->getCentralByDistrito($distrito, 1);
                // //         $idCentral = $info['idCentral'];
                // //     }
                // // } else {
                // //     $info = $this->m_utils->getCentralByDistrito($distrito, 1);
                // //     $idCentral = $info['idCentral'];
                // // }
                
                // $codigo = $this->coordenadas_utils->getBuscarArea([$longitud, $latitud]);
                // $arrayIdCentral = $this->m_utils->getIdCentralByCentralDesc($codigo);

                // $idCentral = $arrayIdCentral['idCentral'];
                
                // if($idCentral == NULL || $idCentral == '') {
                    // if($eecc != NULL || $eecc != '') {
                        // $idEmpresaColab = $this->m_utils->getEmpresaColabByDesc($eecc);
                        // $info = $this->m_utils->getInfoCentralByDistritoEECC($distrito, $idEmpresaColab);
                        // $idCentral = $info['idCentral'];
                        // if($idCentral == null || $idCentral == '') {
                            // $info = $this->m_utils->getCentralByDistrito($distrito, 1);
                             // $idCentral = $info['idCentral'];
                        // }
                    // } else {
                        // $info = $this->m_utils->getCentralByDistrito($distrito, 1);
                        // $idCentral = $info['idCentral'];
                    // }
                // }
                
                // // $dataCentral = $this->m_utils->getIdCentralByCentralDesc($mdf);
                // // if($dataCentral == null){
                // //     throw new Exception('MDF no registrado.');
                // // }
                // // $idCentral      = $dataCentral['idCentral'];
                
                
                            
                // $existSisego = $this->m_utils->existeSisego($sisego);
                // if($existSisego['count'] >= 1) {
                    // $data['itemplan']   =   $existSisego['itemplan'];
                    // throw new Exception('SISEGO ya se encuentra registrado.');
                // }
                // //SI NO ES LAN TO LAN (SE GENERA DOS CODIGOS DE UN SISEGO PARA ESTE CASO)
				// if($flg_lan_to_lan != 1) {
					// $count = $this->m_utils->getDataSisego($sisego, $flg_principal);
					// if($count >= 1) {
						// throw new Exception('SISEGO ya envio solicitud de cotizaci&oacute;n.');
					// }
				// }

                
                // $arrayData = array(
                                    // 'codigo_cluster'  => $codigo_cluster,
                                    // 'id'              => $id,
                                    // 'sisego'          => $sisego,
                                    // 'segmento'        => $segmento,
                                    // 'cliente'         => $cliente,
                                    // 'descripcion'     => $descripcion,
                                    // 'servicios'       => $servicios,
                                    // 'acceso_cliente'  => $acceso_cliente,
                                    // 'tendido_externo' => $tendido_externo,
                                    // 'tipo_cliente'    => $tipo_cliente,
                                    // 'nro_pisos'       => $nro_pisos,
                                    // 'departamento'    => $departamento,
                                    // 'provincia'       => $provincia,
                                    // 'distrito'        => utf8_decode($distrito),
                                    // 'direccion'       => $direccion,
                                    // 'piso'            => $piso,
                                    // 'interior'        => $interior,
                                    // 'latitud'         => $latitud,
                                    // 'longitud'        => $longitud,
                                    // 'fecha_registro'  => $this->fechaActual(),
                                    // 'nombre_estudio'  => $nombre_estudio,
                                    // 'idSubProyecto'   => $idSubproyecto,
                                    // 'flg_tipo'        => 2,
                                    // 'estado'          => 0,
                                    // 'tipo_requerimiento' => $tipoRequerimiento,
                                    // 'clasificacion'      => $clasificacion,
                                    // 'tipo_proyecto'      => $tipoProyecto,
                                    // 'idCentral'          => $idCentral,
                                    // 'flg_principal'      => $flg_principal,
                                    // 'tipo_enlace'        => $tipo_enlace,
									// 'flg_lan_to_lan'     => $flg_lan_to_lan
                                // );

            // $flg = $this->m_utils->registrarCotIndividual($arrayData);

            // if($flg == 0) {
                // throw new Exception('no se registro la infomaci&oacute;n.');
            // } else{
                // $dataArray = array(
                                    // 'codigo_cluster' => $codigo_cluster,
                                    // 'fecha'          => $this->fechaActual(),
                                    // 'id_usuario'     => 1645,//SISEGO
                                    // 'estado'         => 0
                                  // );
                // $data = $this->m_utils->insertLogCotizacionInd($dataArray);
                // $this->m_utils->saveLogSigoplus('TRAMA REGISTRO COTIZACION INDIVIDUAL', $codigo_cluster, NULL, NULL, $sisego, NULL, NULL, 'TRAMA COMPLETADA', 'OPERACION REALIZADA CON EXITO', 1, 5);

                // if($data['error'] == EXIT_ERROR) {
                    // throw new Exception('error');
                // }
            // }
            // $this->db->trans_commit();
            // $data['codigo'] = $codigo_cluster;
            // $data['error']  = EXIT_SUCCESS;
        // } catch (Exception $e) {
            // $this->m_utils->saveLogSigoplus('TRAMA REGISTRO COTIZACION INDIVIDUAL', $codigo_cluster, NULL, NULL, $sisego, NULL, NULL, 'ERROR EN REGISTRAR COTIZACION', $e->getMessage(), 2, 5);
            // $this->db->trans_rollback();
            // $data['msj'] = $e->getMessage();
        // }
        // echo json_encode(array_map('utf8_encode', $data));
    // }
	
	public function crearCotizacionIndividual() {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try {
            // $method = $_SERVER['REQUEST_METHOD'];
            // if($method=='PUT' || $method=='POST'){
                // $inputJSON = file_get_contents('php://input');
                // $input = json_decode( $inputJSON, TRUE );

                $codigo_cluster = $this->m_utils->getCodCluster();
   
                $id              = $this->input->post('id');    
                $sisego          = $this->input->post('sisego');
                $segmento        = $this->input->post('segmento');
                $cliente         = $this->input->post('cliente');
                $descripcion     = $this->input->post('descripcion');
                $servicios       = $this->input->post('servicios');
                $acceso_cliente  = $this->input->post('acceso_cliente');
                $tendido_externo = $this->input->post('tendido_externo');
                $tipo_cliente    = $this->input->post('tipo_cliente');
                $nro_pisos       = $this->input->post('nro_pisos');
                $departamento    = $this->input->post('departamento');
                $provincia       = $this->input->post('provincia');
                $distrito        = $this->input->post('distrito');
                $direccion       = $this->input->post('direccion');
                $piso            = $this->input->post('piso');
                $interior        = $this->input->post('interior');
                $latitud         = $this->input->post('latitud');
                $longitud        = $this->input->post('longitud');
                $nombre_estudio  = $this->input->post('nombre_estudio');
                $tipoRequerimiento = $this->input->post('tipo_requerimiento');
                $clasificacion     = $this->input->post('clasificacion');
                $tipoProyecto     = $this->input->post('tipo_proyecto');
                $eecc             = $this->input->post('eecc');
                $flg_principal    = $this->input->post('flg_principal');
                $tipo_enlace      = $this->input->post('tipo_enlace');
				
				$flg_lan_to_lan   = $this->input->post('flg_lan_to_lan');
                
                $this->db->trans_begin();
				
				if($clasificacion == null || $clasificacion == ''){
                    throw new Exception('No envio la clasificacion.');
                }
                
				if($tipo_enlace == null || $tipo_enlace == ''){
                    throw new Exception('No envio el tipo enlace.');
                }
				
				if($nombre_estudio == null || $nombre_estudio == ''){
                    throw new Exception('No envio el nombre estudio.');
                }
				
				if($tendido_externo == null || $tendido_externo == '') {
					throw new Exception('No envio el tendido externo.');
				}
				
				if($segmento == null || $segmento == '') {
					throw new Exception('No envio el segmento.');
				}
				
				if($tipo_enlace == null || $tipo_enlace == '') {
					throw new Exception('No envio el tipo enlace.');
				}
				// $arrayFlgAlcance = $this->m_utils->getFlgAlcancRobotCotizacion($clasificacion);
                
                $idSubproyecto   = $this->m_utils->getIdSubProyectoBySubProyectoDesc(strtoupper(trim($segmento)));
                
				if($idSubproyecto == null){
                    throw new Exception('segmento no reconocido.');
                }
				
				if($longitud == null) {
					throw new Exception('no envio la longitud');
				}

				if($latitud == null) {
					throw new Exception('no envio la latitud');
				}
                // $codigo = $this->coordenadas_utils->getBuscarArea([$longitud, $latitud]);
                // $arrayIdCentral = $this->m_utils->getIdCentralByCentralDesc($codigo);
                $arrayIdCentral = _getDataKmz($longitud, $latitud); //ESTO ME SIRVE SOLO PARA SACAR LA EECC POR MEDIO DE LA CENTRAL
                $idCentral = $arrayIdCentral[0]['idCentral'];
                $codigo    = $arrayIdCentral[0]['codigo'];
				
                $nodoRespaldo  = null;
                $nodoPrincipal = null;
                if($flg_principal == 1) {
                    $nodoRespaldo  = $idCentral;
                    $nodoPrincipal = $idCentral;
                } else {
                    $nodoPrincipal  = $idCentral;
                }

                if($idCentral == NULL || $idCentral == '') {
                    if($eecc != NULL || $eecc != '') {
                        $idEmpresaColab = $this->m_utils->getEmpresaColabByDesc($eecc);
                        $info = $this->m_utils->getInfoCentralByDistritoEECC($distrito, $idEmpresaColab);
                        $idCentral = $info['idCentral'];
                        if($idCentral == null || $idCentral == '') {
                            $info = $this->m_utils->getCentralByDistrito($distrito, 1);
                             $idCentral = $info['idCentral'];
                        }
                    } else {
                        $info = $this->m_utils->getCentralByDistrito($distrito, 1);
                        $idCentral = $info['idCentral'];
                    }
                }
                
                // $dataCentral = $this->m_utils->getIdCentralByCentralDesc($mdf);
                // if($dataCentral == null){
                //     throw new Exception('MDF no registrado.');
                // }
                // $idCentral      = $dataCentral['idCentral'];
                                            
                $existSisego = $this->m_utils->existeSisego($sisego);
                if($existSisego['count'] >= 1) {
                    $data['itemplan']   =   $existSisego['itemplan'];
                    throw new Exception('SISEGO ya se encuentra registrado.');
                }
                //SI NO ES LAN TO LAN (SE GENERA DOS CODIGOS DE UN SISEGO PARA ESTE CASO)
				// if($flg_lan_to_lan != 1) {
					$count = $this->m_utils->getDataSisego($sisego, $flg_principal);
					if($count >= 1) {
						throw new Exception('SISEGO ya envio solicitud de cotizaci&oacute;n.');
					}
				// }
				
				if($tipo_cliente == null || $tipo_cliente == '') {
					throw new Exception('Se debe enviar el tipo de cliente.');
				}
                
                $fac_red = null;
                $inc_necesita  = null;
                $seia_necesita = null;
                $mtc_necesita  = null;
				$tipoRobot     = null;
				$id_terminal   = null;
                $rowCtOTipoDiseno = $this->m_utils->getCTOCotizacion_v2($clasificacion, $tipo_cliente);
				$countPrinResp = $this->m_utils->countCotiPrinResp($sisego, 0);
				
				list($dataRobot, $dataCosto) = __data_robot_coti_v2($clasificacion, $tipo_cliente, $longitud, $latitud);
				
				if($dataCosto['flg_um'] == 1) {
					throw new Exception('La cotizacion pertenece a UM, la facilidad es : '.$dataRobot['fac_red']);
				}
				
				$distancia = $dataRobot['distancia'];
				$flg_nom_estudio = $this->m_utils->getCountNomEstudio($nombre_estudio); // BUSCO SI EL NOMBRE DE ESTUDIO SE ENCUENTRA EN LA TABLA
				$flg_clasific_tendido_ext = $this->m_utils->getCountClasTendidoExt($clasificacion, $tendido_externo); // BUSCAMOS SI ESTA LA CLASIFICACION CON EL TENDIDO EXTERNO (aereo,mixto,cana. 100%)
				$metroTendidoAe = $this->getMetrosTendidos($distancia);
				
				if($rowCtOTipoDiseno != null && $rowCtOTipoDiseno != '' && $flg_principal == 0 && $metroTendidoAe <= 5000 && $rowCtOTipoDiseno['id_tipo_diseno'] != 6 && $segmento != 'Mayorista' && ($tipo_enlace == 'TDP - Cliente' ||
					$tipo_enlace == 'Principal') && $flg_nom_estudio > 0 && $flg_clasific_tendido_ext > 0) {
					
					$flg_robot = 1;	
				
					$fac_red     = $dataRobot['fac_red'];
					$tipoRobot   = $dataRobot['tipo'];
					$id_terminal = $dataRobot['id_terminal'];
					
                    if($fac_red == null || $fac_red == '') {
                        $fac_red = $codigo;
                    }
                    
                    $arrayDataCentral = $this->m_utils->getDataCentralById($idCentral);

                    $inc_necesita = _getDataKmzArqueologico($longitud, $latitud);
				} else {
					$flg_robot = 2;
				}
					
				
				// if(!$rowCtOTipoDiseno || $countPrinResp['count_resp'] > 0 || $flg_principal == 1 || $rowCtOTipoDiseno['id_tipo_diseno'] == 6 
				   // || $segmento == 'Mayorista' || $rowCtOTipoDiseno['id_tipo_diseno'] == 7)
				// {
					// $flg_robot = 2;
                // } else {
					// if($metroTendidoAe > 5000) {
						// $flg_robot = 2;
					// } else {
						// $flg_robot = 1;//LO DEBE COTIZAR EL ROBOT
					// }
					
					// // $fac_red = _getCtoInfo($longitud, $latitud);
					
					// $dataRobot = __data_robot_coti_v2($clasificacion, $tipo_cliente, $longitud, $latitud);
					// $fac_red     = $dataRobot['fac_red'];
					// $tipoRobot   = $dataRobot['tipo'];
					// $id_terminal = $dataRobot['id_terminal'];
					
                    // if($fac_red == null || $fac_red == '') {
                        // $fac_red = $codigo;
                    // }
                    
                    // $arrayDataCentral = $this->m_utils->getDataCentralById($idCentral);

                    // $inc_necesita = _getDataKmzArqueologico($longitud, $latitud);
                // }

                $arrayData = array(
                                'nodo_principal'   => $nodoPrincipal,
                                'nodo_respaldo'    => $nodoRespaldo,
                                'codigo_cluster'   => $codigo_cluster,
                                'id'               => $id,
                                'sisego'           => $sisego,
                                'segmento'         => $segmento,
                                'cliente'          => $cliente,
                                'descripcion'      => $descripcion,
                                'servicios'        => $servicios,
                                'acceso_cliente'   => $acceso_cliente,
                                'tendido_externo'  => $tendido_externo,
                                'tipo_cliente'     => $tipo_cliente,
                                'nro_pisos'        => $nro_pisos,
                                'departamento'     => $departamento,
                                'provincia'          => $provincia,
                                'distrito'           => utf8_decode($distrito),
                                'direccion'          => $direccion,
                                'piso'               => $piso,
                                'interior'           => $interior,
                                'latitud'            => $latitud,
                                'longitud'           => $longitud,
                                'fecha_registro'     => $this->fechaActual(),
                                'nombre_estudio'     => $nombre_estudio,
                                'idSubProyecto'      => $idSubproyecto,
                                'flg_tipo'        	 => 2,
                                'estado'             => 0,
                                'tipo_requerimiento' => $tipoRequerimiento,
                                'clasificacion'      => $clasificacion,
                                'tipo_proyecto'      => $tipoProyecto,
                                'idCentral'          => $idCentral,
                                'flg_principal'      => $flg_principal,
                                'tipo_enlace'        => $tipo_enlace,
                                'flg_lan_to_lan'     => $flg_lan_to_lan,
                                'flg_robot'          => $flg_robot,
                                'facilidades_de_red' => $fac_red,
                                'requiere_seia'      => $seia_necesita,
                                'requiere_aprob_mml_mtc' => $mtc_necesita,
                                'requiere_aprob_inc' => $inc_necesita,
								'flg_paquetizado'    => 2,
								'id_terminal'        => $id_terminal,
								'tipo'               => $tipoRobot
                            );


            $flg = $this->m_utils->registrarCotIndividual($arrayData);

            if($flg == 0) {
                throw new Exception('no se registro la infomaci&oacute;n.');
            } else{
                $dataArray = array(
                                    'codigo_cluster' => $codigo_cluster,
                                    'fecha'          => $this->fechaActual(),
                                    'id_usuario'     => 1645,//SISEGO
                                    'estado'         => 0
                                  );
                $data = $this->m_utils->insertLogCotizacionInd($dataArray);
                $this->m_utils->saveLogSigoplus('TRAMA REGISTRO COTIZACION INDIVIDUAL', $codigo_cluster, NULL, NULL, $sisego, NULL, NULL, 'TRAMA COMPLETADA', 'OPERACION REALIZADA CON EXITO', 1, 5);

                if($data['error'] == EXIT_ERROR) {
                    throw new Exception('error');
                }
            }
			
			// $data = $this->enviarCotizacionIndRobot($codigo_cluster);
			
            $this->db->trans_commit();
            $data['codigo'] = $codigo_cluster;
            $data['error']  = EXIT_SUCCESS;
        } catch (Exception $e) {
            $this->m_utils->saveLogSigoplus('TRAMA REGISTRO COTIZACION INDIVIDUAL', $codigo_cluster, NULL, NULL, $sisego, NULL, NULL, 'ERROR EN REGISTRAR COTIZACION', $e->getMessage(), 2, 5);
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
	
	function updateCotiMdfByKmz() {
		$dataCentral = $this->m_utils->getDataClusterInUpdateMdf();
		
		foreach($dataCentral as $row) {
			$arrayIdCentral = _getDataKmz($row['longitud'], $row['latitud']);
			$idCentral = $arrayIdCentral[0]['idCentral'];
			$codigo    = $arrayIdCentral[0]['codigo'];
			
			$nodoRespaldo  = null;
			$nodoPrincipal = null;
			if($row['flg_principal'] == 1) {
				$nodoRespaldo = $idCentral;
				$nodoPrincipal  = $idCentral;
			} else {
				$nodoPrincipal  = $idCentral;
			}
			
			$arrayCentral = array(
									'idCentral' => $idCentral,
									'nodo_principal'   => $nodoPrincipal,
									'nodo_respaldo'    => $nodoRespaldo,
								 );
			$this->m_utils->updateMdfCotizacion($row['codigo_cluster'], $arrayCentral);					 
		}
	}
	
    function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone','America/Lima');
        setlocale(LC_TIME, "es_ES","esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }
    
    public function getMsjTypeMethod($type, $path){
        $arr = array('error' => 'Method Not Allowed', 'mensaje' => "Request method '".$type."' not supported", 'path' => $path);
        return $arr;
    }
	
	 public function testTramaSisegoNuevoServer()
    {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try {
            // $method = $_SERVER['REQUEST_METHOD'];
            // if($method=='PUT' || $method=='POST'){
                 $inputJSON = file_get_contents('php://input');
                $input = json_decode( $inputJSON, TRUE );
				log_message('error','TEST TRAMA SISEGO:'.print_r($inputJSON, true));
              
            $data['codigo'] = 'exito';
            $data['error']  = EXIT_SUCCESS;
        } catch (Exception $e) {
           // $this->m_utils->saveLogSigoplus('TRAMA REGISTRO COTIZACION INDIVIDUAL', $codigo_cluster, NULL, NULL, $sisego, NULL, NULL, 'ERROR EN REGISTRAR COTIZACION', $e->getMessage(), 2, 5);
            //$this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
	
	function reenviarCotizacion() {
		$codigo_coti = $this->input->post('codigo_coti');
		
		$data = $this->enviarCotizacionIndRobot($codigo_coti);
		
		echo json_encode(array_map('utf8_encode', $data));
	}
	
    function getMetrosTendidos($distancia) {
        //$distancia = $this->ditanciaVicenty($latitud, $longitud, $latitudNodo, $longitudNodo);
        $metrosTendidos = $distancia + ($distancia * 0.30); //FORMULA PARA SACAR LOS METROS TENDIDOS

        return $metrosTendidos;
    }
	
	function updateFacilidadesRed() {
		$dataArray = $this->m_utils->getPlanobraClusterAll();
		$arrayUpdateIngresa = array();
		foreach($dataArray as $row) {
			$inc_necesita = _getDataKmzArqueologico($row['longitud'], $row['latitud']);
			$fac_red = _getCtoInfo($row['longitud'], $row['latitud']); 
			//$this->m_utils->getCtoByCoord($row['latitud'], $row['longitud']);
			
			if($fac_red == null || $fac_red == '') {
				$fac_red = $row['mdf'];
			}
			
			$dataUpdate = array(
									'id_planobra_cluster' => $row['id_planobra_cluster'],
									'facilidades_de_red'  => $fac_red,
									'requiere_aprob_inc' => $inc_necesita
								);
			array_push($arrayUpdateIngresa, $dataUpdate);						
		}
		
		$this->m_utils->update_cotizacion_fac_red($arrayUpdateIngresa);
	}
	
	function crearCotizacionItemplan() {
        $fechaActual = $this->m_utils->fechaActual();
        
        $arrayData = $this->m_utils->getDataCrearCotiByItemplan(); //FLG_ROBOT, ESTADO DE COTIZACION
		$arrayUpdateIngresa = array();
        $arrayLog = array();
        $arrayTrama = array();
		
		
        foreach($arrayData as $row) {
			$codigo_cluster = $this->m_utils->getCodCluster();
			list($arrayCen, $distancia) = _getDataKmz($row['coordY'], $row['coordX']);//TAIGO LA DISTANCIA DEL CLIENTE A LA CTO
			$metroTendidoAe = $this->getMetrosTendidos($distancia);
			$inc_necesita = _getDataKmzArqueologico($row['coordY'], $row['coordX']);
			$fac_red = _getCtoInfo($row['coordY'], $row['coordX']);
				if( $metroTendidoAe <= 5000 && 
				    $row['nombre_estudio'] == 'Estudio FO Principal' && 
				   ($row['tendido_externo'] == 'Aereo' || $row['tendido_externo'] == 'Mixto')) {
						$arrayDataCentral = $this->m_utils->getDataCentralById($row['idCentral']);
						$rowCtOTipoDiseno = $this->m_utils->getCTOCotizacion($row['clasificacion'], $row['tipo_cliente']);

						
						
						
						if($inc_necesita == null || $inc_necesita == '') {
							break;
						}
						
						$arrayDataDias = $this->m_utils->getDiasMatriz($metroTendidoAe, NULL, NULL, $inc_necesita, $arrayDataCentral['flg_tipo_zona'], $arrayDataCentral['jefatura']);
						
						
						$requiereSeia = $arrayDataDias['seia'];
						$requeAproMtc = $arrayDataDias['mtc'];
						$duracion = null;
						if($rowCtOTipoDiseno['tiempo'] == null) { // SI EL TIEMPO EN LA MATRIZ DE CTO ES NULL, MUESTRO LOS DIAS DE LA MATRIZ DIAS (ACTUAL).
							$duracion = $arrayDataDias['dias'];
						} else {
							$duracion = $rowCtOTipoDiseno['tiempo'];
						}
						
						if($rowCtOTipoDiseno['costo_total'] == null && $rowCtOTipoDiseno['cto'] != null) {
							$rowMatrizCosto = $this->m_utils->getCostosMatrizCotizacion($metroTendidoAe, $requiereSeia, $inc_necesita);
						} else {
							$rowMatrizCosto = array('total' 	=> $rowCtOTipoDiseno['costo_total'],
													'mo_total'  => $rowCtOTipoDiseno['costo_total'],
													'mat_total' => 0,
													'diseno_total' => 0,
													'inc_total' => 0,
													'eia_total' => 0);
						}


						$dataInsert = array(
												'codigo_cluster'         	  => $codigo_cluster,
												'itemplan'                    => $row['itemplan'],
												'flg_principal'               => $row['flg_principal'],
												'clasificacion'     		  => $row['clasificacion'],
												'tipo_cliente'				  => $row['tipo_cliente'],
												'cant_cto'                    => $rowCtOTipoDiseno['cto'],
												'metro_tendido_aereo'         => $metroTendidoAe,
												'requiere_seia'               => $requiereSeia,
												'requiere_aprob_mml_mtc'      => $requeAproMtc,
												'costo_materiales'            => $rowMatrizCosto['mat_total'],
												'costo_mano_obra'             => $rowMatrizCosto['mo_total'],
												'costo_diseno'                => $rowMatrizCosto['diseno_total'],
												'costo_expe_seia_cira_pam'    => $rowMatrizCosto['eia_total'],
												'costo_adicional_rural'       => $rowMatrizCosto['inc_total'],
												'costo_total'                 => $rowMatrizCosto['total'],
												'estado'                      => 1,
												'facilidades_de_red'  		  => $fac_red,
												'requiere_aprob_inc' 		  => $inc_necesita,
												'fecha_envio_cotizacion'      => $fechaActual,
												'usuario_envio_cotizacion'    => 1776, //ROBOT COTIZACION
												'duracion'                    => $duracion,
												'longitud'					  => $row['coordY'],
												'latitud'					  => $row['coordX'],
												'id_tipo_diseno'              => $rowCtOTipoDiseno['id_tipo_diseno']
											);
											
						array_push($arrayUpdateIngresa, $dataInsert);

						$dataArrayLog = array  (
													'codigo_cluster' => $codigo_cluster,
													'fecha'          => $fechaActual,
													'id_usuario'     => 1776,//ROBOT
													'estado'         => 1
												);
						array_push($arrayLog, $dataArrayLog);
						
				    }             
        }
        $this->m_utils->insertRobotCotizacionByItemplan($arrayUpdateIngresa, $arrayLog);
    }
	
	function envioCotizacionMasivoManual() {
		$this->m_utils->envioCotizacionMasivo();
	}
	
	public function crearCotizacionIndividualV2() {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try {
            // $method = $_SERVER['REQUEST_METHOD'];
            // if($method=='PUT' || $method=='POST'){
                // $inputJSON = file_get_contents('php://input');
                // $input = json_decode( $inputJSON, TRUE );
				$fac_red = null;
                $inc_necesita  = null;
                $seia_necesita = null;
                $mtc_necesita  = null;
				$tipoRobot     = null;
				$id_terminal   = null;
				
                $codigo_cluster = $this->m_utils->getCodCluster();
   
                $id              = $this->input->post('id');    
                $sisego          = $this->input->post('sisego');
                $segmento        = $this->input->post('segmento');
                $cliente         = $this->input->post('cliente');
                $descripcion     = $this->input->post('descripcion');
                $servicios       = $this->input->post('servicios');
                $acceso_cliente  = $this->input->post('acceso_cliente');
                $tendido_externo = $this->input->post('tendido_externo');
                $tipo_cliente    = $this->input->post('tipo_cliente');
                $nro_pisos       = $this->input->post('nro_pisos');
                $departamento    = $this->input->post('departamento');
                $provincia       = $this->input->post('provincia');
                $distrito        = $this->input->post('distrito');
                $direccion       = $this->input->post('direccion');
                $piso            = $this->input->post('piso');
                $interior        = $this->input->post('interior');
                $latitud         = $this->input->post('latitud');
                $longitud        = $this->input->post('longitud');
                $nombre_estudio  = $this->input->post('nombre_estudio');
                $tipoRequerimiento = $this->input->post('tipo_requerimiento');
                $clasificacion     = $this->input->post('clasificacion');
                $tipoProyecto     = $this->input->post('tipo_proyecto');
                $eecc             = $this->input->post('eecc');
                $flg_principal    = $this->input->post('flg_principal');
                $tipo_enlace      = $this->input->post('tipo_enlace');
				
				$flg_lan_to_lan   = $this->input->post('flg_lan_to_lan');
                
                $this->db->trans_begin();
				
				
				$arrayData = array(
                                'codigo_cluster'   => $codigo_cluster,
                                'id'               => $id,
                                'sisego'           => $sisego,
                                'segmento'         => $segmento,
                                'cliente'          => $cliente,
                                'descripcion'      => $descripcion,
                                'servicios'        => $servicios,
                                'acceso_cliente'   => $acceso_cliente,
                                'tendido_externo'  => $tendido_externo,
                                'tipo_cliente'     => $tipo_cliente,
                                'nro_pisos'        => $nro_pisos,
                                'departamento'     => $departamento,
                                'provincia'          => $provincia,
                                'distrito'           => utf8_decode($distrito),
                                'direccion'          => $direccion,
                                'piso'               => $piso,
                                'interior'           => $interior,
                                'latitud'            => $latitud,
                                'longitud'           => $longitud,
                                'fecha_registro'     => $this->fechaActual(),
                                'nombre_estudio'     => $nombre_estudio,
                                'flg_tipo'        	 => 2,
                                'estado'             => 0,
                                'tipo_requerimiento' => $tipoRequerimiento,
                                'clasificacion'      => $clasificacion,
                                'tipo_proyecto'      => $tipoProyecto,
                                'flg_principal'      => $flg_principal,
                                'tipo_enlace'        => $tipo_enlace,
                                'flg_lan_to_lan'     => $flg_lan_to_lan,
                                'facilidades_de_red' => $fac_red,
								'flg_paquetizado'    => 2
                            );
				$this->m_utils->saveLogSigoplus('INFO COTIZACION INDIVIDUAL', $codigo_cluster, NULL, NULL, $sisego, NULL, NULL, 'INFORMACION COTIZACION','INFO', 1, 5, $arrayData, json_encode($data));
				if($clasificacion == null || $clasificacion == ''){
                    throw new Exception('No envio la clasificacion.');
                }
                
				if($tipo_enlace == null || $tipo_enlace == ''){
                    throw new Exception('No envio el tipo enlace.');
                }
				
				if($nombre_estudio == null || $nombre_estudio == ''){
                    throw new Exception('No envio el nombre estudio.');
                }
				
				if($tendido_externo == null || $tendido_externo == '') {
					throw new Exception('No envio el tendido externo.');
				}
				
				if($segmento == null || $segmento == '') {
					throw new Exception('No envio el segmento.');
				}
				
				if($tipo_enlace == null || $tipo_enlace == '') {
					throw new Exception('No envio el tipo enlace.');
				}
				// $arrayFlgAlcance = $this->m_utils->getFlgAlcancRobotCotizacion($clasificacion);
                
				if($longitud == null) {
					throw new Exception('no envio la longitud');
				}

				if($latitud == null) {
					throw new Exception('no envio la latitud');
				}
				
				if($longitud == '0.0' || $longitud == 0.0 || $longitud == 0) {
					throw new Exception('no envio la longitud con el formato correcto, verificar.');
				}
				
				if($latitud == '0.0' || $latitud == 0.0 || $latitud == 0) {
					throw new Exception('no envio la latitud con el formato correcto, verificar.');
				}
				
                $idSubproyecto   = $this->m_utils->getIdSubProyectoBySubProyectoDesc(strtoupper(trim($segmento)));
                
				if($idSubproyecto == null){
                    throw new Exception('segmento no reconocido.');
                }
				
				if($flg_principal == null || $flg_principal == '') {
					throw new Exception('Enviar el tipo de cotizacion (principal o respaldo).');
				}
                // $codigo = $this->coordenadas_utils->getBuscarArea([$longitud, $latitud]);
                // $arrayIdCentral = $this->m_utils->getIdCentralByCentralDesc($codigo);
                $arrayIdCentral = _getDataKmz($longitud, $latitud); //ESTO ME SIRVE SOLO PARA SACAR LA EECC POR MEDIO DE LA CENTRAL
                $idCentral = $arrayIdCentral[0]['idCentral'];
                $codigo    = $arrayIdCentral[0]['codigo'];
				
                $nodoRespaldo  = null;
                $nodoPrincipal = null;
                if($flg_principal == 1) {
                    $nodoRespaldo  = $idCentral;
                    $nodoPrincipal = $idCentral;
                } else {
                    $nodoPrincipal  = $idCentral;
                }

                // if($idCentral == NULL || $idCentral == '') {
                    // if($eecc != NULL || $eecc != '') {
                        // $idEmpresaColab = $this->m_utils->getEmpresaColabByDesc($eecc);
                        // $info = $this->m_utils->getInfoCentralByDistritoEECC($distrito, $idEmpresaColab);
                        // $idCentral = $info['idCentral'];
                        // if($idCentral == null || $idCentral == '') {
                            // $info = $this->m_utils->getCentralByDistrito($distrito, 1);
                             // $idCentral = $info['idCentral'];
                        // }
                    // } else {
                        // $info = $this->m_utils->getCentralByDistrito($distrito, 1);
                        // $idCentral = $info['idCentral'];
                    // }
                // }
                                                            
                $existSisego = $this->m_utils->existeSisego($sisego);
                if($existSisego['count'] >= 1) {
                    $data['itemplan']   =   $existSisego['itemplan'];
                    throw new Exception('SISEGO ya se encuentra registrado.');
                }
                //SI NO ES LAN TO LAN (SE GENERA DOS CODIGOS DE UN SISEGO PARA ESTE CASO)
				// if($flg_lan_to_lan != 1) {
					$count = $this->m_utils->getDataSisego($sisego, $flg_principal);
					if($count >= 1) {
						throw new Exception('SISEGO ya envio solicitud de cotizaci&oacute;n.');
					}
				// }
				
				if($tipo_cliente == null || $tipo_cliente == '') {
					throw new Exception('Se debe enviar el tipo de cliente.');
				}

                $rowCtOTipoDiseno = $this->m_utils->getCTOCotizacion_v2($clasificacion, $tipo_cliente);
				$countPrinResp = $this->m_utils->countCotiPrinResp($sisego, 0);
				
				$flg_nom_estudio = $this->m_utils->getCountNomEstudio($nombre_estudio); // BUSCO SI EL NOMBRE DE ESTUDIO SE ENCUENTRA EN LA TABLA
				$flg_clasific_tendido_ext = $this->m_utils->getCountClasTendidoExt($clasificacion, $tendido_externo); // BUSCAMOS SI ESTA LA CLASIFICACION CON EL TENDIDO EXTERNO (aereo,mixto,cana. 100%)
				
				list($dataRobot, $dataCostos) = __data_robot_coti_v2($clasificacion, $tipo_cliente, $longitud, $latitud, $idCentral);
				
				$countUM = $this->m_utils->getCountSisegoUmCoti($sisego);
				if($countUM == 0) {
					if($dataCostos['flg_um'] == 1) {
						throw new Exception('La cotizacion pertenece a UM, la facilidad es : '.$dataRobot['fac_red']);
					}
				}
				
				
				$distancia = $dataRobot['distancia'];
				$metroTendidoAe = $this->getMetrosTendidos($distancia);
				$fac_red     = $dataRobot['fac_red'];
				$tipoRobot   = $dataRobot['tipo'];
				$id_terminal = $dataRobot['id_terminal'];
				
				$codigo_enviar = $codigo;
				$codEbc = 'xxxx';
				if($tipoRobot == 'EBC') {
					$codEbc = $fac_red;
				}
				
				if($fac_red == null || $fac_red == '') {
					$fac_red = $codigo;
				}
				
				$arrayDataCentral = $this->m_utils->getDataCentralPqtById($idCentral);
				if( $rowCtOTipoDiseno != null && $rowCtOTipoDiseno != '' && $flg_principal == 0 && $metroTendidoAe <= 5000 && $rowCtOTipoDiseno['id_tipo_diseno'] != 6 && $segmento != 'Mayorista' && ($tipo_enlace == 'TDP - Cliente' ||
					$tipo_enlace == 'Principal') && $flg_nom_estudio > 0 && $flg_clasific_tendido_ext > 0 && $sisego != '2020-07-170335') {
					
					$inc_necesita = _getDataKmzArqueologico($longitud, $latitud);
					
					list($dataRobot, $dataCostos) = __data_robot_coti_v2($clasificacion, $tipo_cliente, $longitud, $latitud, $idCentral, $idSubproyecto, $inc_necesita);
					
					$flg_robot = 1;	
			                    
					// if($dataCostos['costo_mat_envio_sisego'] == 0 || $dataCostos['costo_mat_envio_sisego'] == null) {
						// throw new exception('Costo mat se envia como 0, error');
					// }
					
					if($dataCostos['costo_mo_envio_sisego'] == 0 || $dataCostos['costo_mo_envio_sisego'] == null) {
						throw new exception('Costo mo se envia como 0, error');
					}
					
					$data['codigo']      = $codigo_cluster;
					$data['materiales']  = $dataCostos['costo_mat_envio_sisego'];
					$data['mano_obra']   = $dataCostos['costo_mo_envio_sisego'];
					$data['nodo'] 	     = $codigo;
					$data['ebc'] 	     = $codEbc;
					$data['duracion']    = $dataCostos['duracion'];
					$data['tipo_diseno'] = $dataCostos['id_tipo_diseno'];
					$data['diseno'] 	 = $dataCostos['arrayCostos']['diseno_total'];
					$data['comentario']  = NULL;
					$data['flg_eecc']    = $flg_robot;
										
					$arrayData = array(
											'nodo_principal'   => $nodoPrincipal,
											'nodo_respaldo'    => $nodoRespaldo,
											'codigo_cluster'   => $codigo_cluster,
											'id'               => $id,
											'sisego'           => $sisego,
											'segmento'         => $segmento,
											'cliente'          => $cliente,
											'descripcion'      => $descripcion,
											'servicios'        => $servicios,
											'acceso_cliente'   => $acceso_cliente,
											'tendido_externo'  => $tendido_externo,
											'tipo_cliente'     => $tipo_cliente,
											'nro_pisos'        => $nro_pisos,
											'departamento'     => $departamento,
											'provincia'          => $provincia,
											'distrito'           => utf8_decode($distrito),
											'direccion'          => $direccion,
											'piso'               => $piso,
											'interior'           => $interior,
											'latitud'            => $latitud,
											'longitud'           => $longitud,
											'fecha_registro'     => $this->fechaActual(),
											'nombre_estudio'     => $nombre_estudio,
											'idSubProyecto'      => $idSubproyecto,
											'flg_tipo'        	 => 2,
											'tipo_requerimiento' => $tipoRequerimiento,
											'clasificacion'      => $clasificacion,
											'tipo_proyecto'      => $tipoProyecto,
											'idCentral'          => $idCentral,
											'flg_principal'      => $flg_principal,
											'tipo_enlace'        => $tipo_enlace,
											'flg_lan_to_lan'     => $flg_lan_to_lan,
											'flg_robot'          => $flg_robot,
											'facilidades_de_red' => $fac_red,
											'requiere_aprob_inc' => $inc_necesita,
											'flg_paquetizado'    => 2,
											'id_terminal'        => $id_terminal,
											'tipo'               => $tipoRobot,
											
											'cant_cto'                    => $dataCostos['cant_cto'],
											'metro_tendido_aereo'         => $dataCostos['metrosTendidos'],
											'requiere_seia'               => $dataCostos['seia'],
											'distancia_lineal'			  => $dataCostos['distancia'],
											'requiere_aprob_mml_mtc'      => $dataCostos['mtc'],
											'costo_mo_edif'               => $dataCostos['costo_mo_edif'],
											'costo_mat_edif'              => $dataCostos['costo_mat_edif'],
											'costo_oc_edif'               => $dataCostos['costo_oc_edif'],   
											'crxa'      				  => $dataCostos['arrayCostos']['crxa'],
											'crxc'      				  => $dataCostos['arrayCostos']['crxc'],
											'postes'      			      => $dataCostos['arrayCostos']['postes'],
											'hilo_disp'                   => $dataCostos['hilo_disp'],
											'costo_materiales'            => $dataCostos['arrayCostos']['mat_total'],
											'costo_mano_obra'             => $dataCostos['arrayCostos']['mo_total'],
											'costo_diseno'                => $dataCostos['arrayCostos']['diseno_total'],
											'costo_expe_seia_cira_pam'    => $dataCostos['arrayCostos']['eia_total'],
											'costo_adicional_rural'       => $dataCostos['arrayCostos']['inc_total'],
											'costo_total'                 => $dataCostos['arrayCostos']['total'],
											'estado'                      => 1,
											'fecha_envio_cotizacion'      => $this->fechaActual(),
											'usuario_envio_cotizacion'    => 1776, //ROBOT COTIZACION
											'duracion'                    => $dataCostos['duracion'],
											'id_tipo_diseno'              => $dataCostos['id_tipo_diseno'],
											'metro_oc'					  => $dataCostos['arrayCostos']['metro_oc'],
											'idEmpresaColab'              => $arrayDataCentral['idEmpresaColab']
										);
					
				} else {
					list($dataRobot, $dataCostos) = __data_robot_coti_v2($clasificacion, $tipo_cliente, $longitud, $latitud, $idCentral, $idSubproyecto, $inc_necesita);
					
					$flg_robot = 2;
					
					$data['codigo']      = $codigo_cluster;
					$data['materiales']  = NULL;
					$data['mano_obra']   = NULL;
					$data['nodo'] 	     = NULL;
					$data['duracion']    = NULL;
					$data['tipo_diseno'] = NULL;
					$data['diseno'] 	 = NULL;
					$data['comentario']  = NULL;
					$data['flg_eecc']    = $flg_robot;
					
					$arrayData = array(
                                'nodo_principal'   => $nodoPrincipal,
                                'nodo_respaldo'    => $nodoRespaldo,
                                'codigo_cluster'   => $codigo_cluster,
                                'id'               => $id,
                                'sisego'           => $sisego,
                                'segmento'         => $segmento,
                                'cliente'          => $cliente,
                                'descripcion'      => $descripcion,
                                'servicios'        => $servicios,
                                'acceso_cliente'   => $acceso_cliente,
                                'tendido_externo'  => $tendido_externo,
                                'tipo_cliente'     => $tipo_cliente,
                                'nro_pisos'        => $nro_pisos,
                                'departamento'     => $departamento,
                                'provincia'          => $provincia,
                                'distrito'           => utf8_decode($distrito),
                                'direccion'          => $direccion,
                                'piso'               => $piso,
                                'interior'           => $interior,
                                'latitud'            => $latitud,
                                'longitud'           => $longitud,
                                'fecha_registro'     => $this->fechaActual(),
                                'nombre_estudio'     => $nombre_estudio,
                                'idSubProyecto'      => $idSubproyecto,
                                'flg_tipo'        	 => 2,
                                'estado'             => 0,
                                'tipo_requerimiento' => $tipoRequerimiento,
                                'clasificacion'      => $clasificacion,
                                'tipo_proyecto'      => $tipoProyecto,
                                'idCentral'          => $idCentral,
                                'flg_principal'      => $flg_principal,
                                'tipo_enlace'        => $tipo_enlace,
                                'flg_lan_to_lan'     => $flg_lan_to_lan,
                                'flg_robot'          => $flg_robot,
                                'facilidades_de_red' => $fac_red,
                                'requiere_seia'      => $seia_necesita,
                                'requiere_aprob_mml_mtc' => $mtc_necesita,
                                'requiere_aprob_inc' => $inc_necesita,
								'flg_paquetizado'    => 2,
								'id_terminal'        => $id_terminal,
								'tipo'               => $tipoRobot,
								'distancia_lineal'   => $dataCostos['distancia'],
								'idEmpresaColab'     => $arrayDataCentral['idEmpresaColab']
                            );
				}
					
            $flg = $this->m_utils->registrarCotIndividual($arrayData);

            if($flg == 0) {
                throw new Exception('no se registro la infomaci&oacute;n.');
            } else{	
				$dataArray = array(
									'codigo_cluster' => $codigo_cluster,
									'fecha'          => $this->fechaActual(),
									'id_usuario'     => 1645,//SISEGO
									'estado'         => 0
								  );
				$dataLog = $this->m_utils->insertLogCotizacionInd($dataArray);
				
				if($flg_robot == 1) {
					$dataArray = array(
										'codigo_cluster' => $codigo_cluster,
										'fecha'          => $this->fechaActual(),
										'id_usuario'     => 1776,//SISEGO
										'estado'         => 1
									  );
					$dataLog = $this->m_utils->insertLogCotizacionInd($dataArray);
				}
				$data['error'] = $dataLog['error'];
				$data['msj']   = $dataLog['msj'];
                
                $this->m_utils->saveLogSigoplus('TRAMA REGISTRO COTIZACION INDIVIDUAL', $codigo_cluster, NULL, NULL, $sisego, NULL, NULL, 'TRAMA COMPLETADA', 'OPERACION REALIZADA CON EXITO', 1, 5, $arrayData, json_encode($data));
				
                if($dataLog['error'] == EXIT_ERROR) {
                    throw new Exception('error');
                }
            }
			// $data = $this->enviarCotizacionIndRobot($codigo_cluster);
			
            $this->db->trans_commit();
            $data['error']  = EXIT_SUCCESS;
        } catch (Exception $e) {
            $this->m_utils->saveLogSigoplus('TRAMA REGISTRO COTIZACION INDIVIDUAL', $codigo_cluster, NULL, NULL, $sisego, NULL, NULL, 'ERROR EN REGISTRAR COTIZACION', $e->getMessage(), 2, 5, $arrayData, json_encode($data));
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
	
	function enviarCotizacionIndRobot($codigo_coti=null) {
		$data['error'] = EXIT_ERROR;
        $fechaActual = $this->m_utils->fechaActual();
        $arrayData = $this->m_utils->getSolicitudCotizacionRobot(1, 0, $codigo_coti); //FLG_ROBOT, ESTADO DE COTIZACION
        $arrayUpdateIngresa = array();
        $arrayLog = array();
        $arrayTrama = array();

        foreach($arrayData as $row) {
			$dataArrayTrama = array();
			$dataArrayLog   = array();
			

			$countPrinResp = $this->m_utils->countCotiPrinResp($row['sisego'], 0); //COUNT QUE ME INDICA SI TIENE PRINCIPAL Y RESPALDO DE ESTADO PENDIENTE
			list($arrayCen, $distancia) = _getDataKmz($row['longitud'], $row['latitud']);//TAIGO LA DISTANCIA DEL CLIENTE A LA CTO
			$metroTendidoAe = $this->getMetrosTendidos($distancia);
			
			// if($countPrinResp['count_prin'] >= 1 && $countPrinResp['count_resp'] == 0) {
			// if($row['flg_principal'] == 0) {
				// if( $metroTendidoAe <= 5000 && $row['id_tipo_diseno'] != 6 && $row['segmento'] != 'Mayorista' && ($row['tipo_enlace'] == 'TDP - Cliente' ||
					// $row['tipo_enlace'] == 'Principal') && $row['nombre_estudio'] == 'Estudio FO Principal' && ($row['tendido_externo'] == 'Aereo' || $row['tendido_externo'] == 'Mixto')) 
					// {
						
						if($row['requiere_aprob_inc'] == null || $row['requiere_aprob_inc'] == '') {
							break;
						}
						
						list($dataRobot, $dataCostos) = __data_robot_coti_v2($row['clasificacion'], $row['tipo_cliente'], $row['longitud'], $row['latitud'], $row['idCentral'], $row['idSubProyecto'], $row['requiere_aprob_inc']);

						$dataUpdate = array(
												'id_planobra_cluster'         => $row['id_planobra_cluster'],
												'cant_cto'                    => $dataCostos['cant_cto'],
												'metro_tendido_aereo'         => $dataCostos['metrosTendidos'],
												'requiere_seia'               => $dataCostos['seia'],
												'distancia_lineal'			  => $dataCostos['distancia'],
												'requiere_aprob_mml_mtc'      => $dataCostos['mtc'],
												'costo_mo_edif'               => $dataCostos['costo_mo_edif'],
												'costo_mat_edif'              => $dataCostos['costo_mat_edif'],
												'costo_oc_edif'               => $dataCostos['costo_oc_edif'],   
												'crxa'      				  => $dataCostos['arrayCostos']['crxa'],
												'crxc'      				  => $dataCostos['arrayCostos']['crxc'],
												'postes'      			      => $dataCostos['arrayCostos']['postes'],
												'hilo_disp'                   => $dataCostos['hilo_disp'],
												'costo_materiales'            => $dataCostos['arrayCostos']['mat_total'],
												'costo_mano_obra'             => $dataCostos['arrayCostos']['mo_total'],
												'costo_diseno'                => $dataCostos['arrayCostos']['diseno_total'],
												'costo_expe_seia_cira_pam'    => $dataCostos['arrayCostos']['eia_total'],
												'costo_adicional_rural'       => $dataCostos['arrayCostos']['inc_total'],
												'costo_total'                 => $dataCostos['arrayCostos']['total'],
												'estado'                      => 1,
												'fecha_envio_cotizacion'      => $fechaActual,
												'usuario_envio_cotizacion'    => 1776, //ROBOT COTIZACION
												'duracion'                    => $dataCostos['duracion'],
												'id_tipo_diseno'              => $dataCostos['id_tipo_diseno'],
												'metro_oc'					  => $dataCostos['arrayCostos']['metro_oc']
											);
											
						$dataArrayLog = array  (
													'codigo_cluster' => $row['codigo_cluster'],
													'fecha'          => $fechaActual,
													'id_usuario'     => 1776,//ROBOT
													'estado'         => 1
												);

						// $costoMOSisego  = $rowMatrizCosto['mo_total']+$rowMatrizCosto['eia_total']+$rowMatrizCosto['inc_total'];

						$dataArrayTrama = array (
													'codigo' 		=> $row['codigo_cluster'],
													'materiales'    => $dataCostos['arrayCostos']['mat_total'],
													'mano_obra'     => $dataCostos['arrayCostos']['mo_total']+$dataCostos['arrayCostos']['diseno_total']+$dataCostos['arrayCostos']['inc_total']+$dataCostos['arrayCostos']['eia_total'],
													'nodo'          => $row['codigo'],
													'duracion'      => $dataCostos['duracion'],
													'tipo_diseno'   => $dataCostos['id_tipo_diseno'],
													'diseno'        => $dataCostos['arrayCostos']['diseno_total'],
													'comentario'    => NULL
												);

						$data = $this->m_utils->updateRobotCotizacion($dataUpdate, $dataArrayLog, $dataArrayTrama);
					// } else {
					    // $data = $this->m_utils->updateCotiFlgRobot($row['codigo_cluster'], array('flg_robot' => 2));
					// }
			// } else {
				// $data = $this->m_utils->updateCotiFlgRobot($row['codigo_cluster'], array('flg_robot' => 2));
			// }           
        }
		return $data;
    }
	
	function enviarItemPlanSisego() {
        $data['msj'] = null;
        $data['error'] = EXIT_SUCCESS;
        $arrayItemSisegos = [];

        $arrayDataSisego = $this->m_utils->getDataEvaluaPeps();
        $JsonLog = $arrayDataSisego[0]['data_json'];
        foreach ($arrayDataSisego as $row) {

            $indicador = $row['sisego'];
            try {
                $jsonItem = json_decode($row['data_json']);
                $id = $jsonItem->id;
                $pep2 = $jsonItem->pep2;
                $grafo = $jsonItem->grafo;
                $fecha_envio = $jsonItem->fecha_envio;
                $segmento = $jsonItem->segmento;
                $tipo_requerimiento = $jsonItem->tipo_requerimiento;
                $tipo_diseno = $jsonItem->tipo_diseno;
                $nombre_estudio = $jsonItem->nombre_estudio;
                $duracion = $jsonItem->duracion;
                $acceso_cliente = $jsonItem->acceso_cliente;
                $tendido_externo = $jsonItem->tendido_externo;
                $tipo_sede = $jsonItem->tipo_sede;
                $tipo_cliente = $jsonItem->tipo_cliente;
                $per = $jsonItem->per;
                $cliente = $jsonItem->cliente;
                $cod_cotiz = $jsonItem->sinfix;
                $coordenada_x = $jsonItem->coordenada_x;
                $coordenada_y = $jsonItem->coordenada_y;

//                if ($itemplan == '' || $itemplan == null) {
//                    throw new Exception('ingresar el itemplan');
//                }
				$this->db->trans_begin();
				
                $data['sisego'] = $row['sisego'];
                if ($row['sisego'] == null || $row['sisego'] == '') {
                    throw new Exception('ingresar el sisego');
                }

                if ($row['pep'] == null || $row['pep'] == '') {
                    throw new Exception('ingresar la pep1');
                }

                $countPresupuesto = $this->m_utils->getCountPresupuesto($jsonItem->pep, $jsonItem->sinfix);

                if($countPresupuesto == 0) {
                    throw new Exception('La pep ' . $row['pep'] . ', no cuenta con presupuesto.');
                }

                //$data = $this->m_utils->actualizarMontoDisponible($row['pep'], $cod_cotiz);

                // if ($data['error'] == EXIT_ERROR) {
                    // throw new Exception($data['msj']);
                // }

                $fecha = $this->m_utils->fechaActual();

                $dataArray = array(
                    'tipo_diseno' => utf8_decode($jsonItem->tipo_diseno),
                    'nombre_estudio' => utf8_decode($nombre_estudio),
                    'tipo_requerimiento' => utf8_decode($tipo_requerimiento),
                    'duracion' => $duracion,
                    'acceso_cliente' => utf8_decode($acceso_cliente),
                    'tendido_externo' => utf8_decode($tendido_externo),
                    'tipo_sede' => utf8_decode($tipo_sede)
                );


                $itemplan = null;
                /*                 * *DATOS COMPLMENTARIOS ITEMPLAN** */
                $idProy = '3'; //ID PROYECTO SISEGOS = 3
                $idSubproy = $this->m_utils->getIdSubProyectoBySubProyectoDesc(strtoupper($segmento));
                if ($idSubproy == null) {
                    throw new Exception('segmento no reconocido.');
                }

                if ($coordenada_x == null || $coordenada_x == '' || $coordenada_y == null || $coordenada_y == '') {
                    throw new Exception('Debe enviar coord x, coord y.');
                }

				$dataCoti = $this->m_utils->getDataCotizacionByCod($cod_cotiz);
				
				if ($dataCoti['idCentral'] == null || $dataCoti['idCentral'] == '') {
					throw new Exception('MDF no registrado.');
				}
				$this->db->trans_begin();
			
				$idCentral      = $dataCoti['idCentral'];
				$idzonal        = $dataCoti['idZonal'];            
				$eecc           = $dataCoti['idEmpresaColab'];
				$codigo         = $dataCoti['codigo'];
				$jefatura       = $dataCoti['jefatura'];

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

                $existSisego = $this->m_utils->existeSisego($indicador);
				
				$infoSubProyecto = $this->m_utils->getInfoSubProyectoByIdSubProyecto($idSubproy);
				if($infoSubProyecto==null){
					throw new Exception('Error al obtener la informacion del subproyecto!!');
				}
			
                if ($existSisego['count'] >= 1) {
                    $data['itemplan'] = $existSisego['itemplan'];
                    throw new Exception('SISEGO ya se encuentra registrado.');
                }

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

                /*                 * VALIDAMOS SI CUENTA CON LA CONFIGURACION DE CREARSE EN OBRA 20.06.2019 czavala* */
                $hasAutoPlanEnObra = $this->m_utils->getIdEstadoPlanCambio($idSubproy);

                // if ($hasAutoPlanEnObra == null) {//SI NO TIENE LA CONFIGURACION SE TOMA EN CUENTA LA COTIZACION DE CASO CONTRARIO NO SE TOMA EN CUENTA COTIZACION
                    // //SI REQUIERE COTIZACION NACE EN PRE REGISTRO
                    
                    // if ($idzonal == '') {
                        // $idzonal = 0;
                    // }
                // } else {//SI TIENE CONFIGURACION DE NACER EN OBRA PRIMERO SE REGISTRA EN ESTADO PRE DISENO
                    // $estadoplan = ESTADO_PLAN_PRE_DISENO;
                // }
				
				$estadoplan = ESTADO_PLAN_PRE_REGISTRO;
                $this->m_planobra->deleteLogImportPlanObraSub();

                $itemplan = $this->m_planobra->generarCodigoItemPlan($idProy, $idzonal);

                $dataCostoUni = $this->m_utils->getDataCotizacionCostos($cod_cotiz);

                $data = $this->m_planobra->insertarPlanobra($itemplan, $idProy, $idSubproy, $idCentral, $idzonal, $eecc, $eelec, ESTADO_PLAN_PRE_REGISTRO, $fase, $fechaInicio, $nombreplan, $indicador, $uip, $cordx, $cordy, $cantidadTroba, 
				                                           $has_coti, null, $tipo_requerimiento, $tipo_diseno, $nombre_estudio, 
														   $duracion, $acceso_cliente, $tendido_externo, $tipo_sede, $tipo_cliente, $per, 
														   $dataCostoUni['costo_materiales'], $dataCostoUni['costo_mo'], $infoSubProyecto);
                
				$this->m_planobra->saveItemPlanEstadoCreado($itemplan, $estadoplan, $this->fechaActual(), ESTADO_PLAN_PRE_REGISTRO);
				if ($data['error'] == EXIT_ERROR) {
                    throw new Exception('1) Error interno al registrar el itemplan.');
                } else {
                    $itemplanData = $this->m_planobra->obtenerUltimoRegistro();
                    if ($itemplanData) {
						$data = $this->m_subproy_pep_grafo->insertSisegoPep2Grafo($indicador, $pep2, $grafo, $itemplanData, $fecha);
						
						if ($data['error'] == EXIT_SUCCESS) {
							$this->m_utils->saveLogSigoplus('SISEGO PEP2 GRAFO', '', $itemplanData, '', $indicador, '', '', 'TRAMA COMPLETADA', 'OPERACION REALIZADA CON EXITO', '1', null, null);
						} else {
							throw new Exception('No ingreso la pep y grafo.');
						}
						
						$resp = $this->m_utils->crearOcSisegoByItemplan($itemplanData);
					
						if($resp == 2) {
							throw new Exception('PEP sin presupuesto');
						}
						
                        $this->m_planobra->insertarLogPlanObra($itemplanData, 0, ID_TIPO_PLANTA_EXTERNA); //ID USUARIO = 0 FROM SISEGO              
                        $this->m_utils->saveLogSigoplus('TRAMA CREAR ITEMPLAN FROM SISEGO', NULL, $itemplanData, '', $indicador, $eecc, $jefatura, 'TRAMA COMPLETADA', 'OPERACION REALIZADA CON EXITO', 1, 1, $JsonLog);
                        //APROBAMOS LA COTIZACION
                        // if ($cod_cotiz) {
                            // $datoArray = array(
                                // "estado" => 2, //cluster aprobado
                                // "itemplan" => $itemplanData,
                                // "fecha_aprobacion" => $this->fechaActual(),
                                // "idCentral" => $idCentral
                            // );
                            // $data = $this->m_planobra->updateEstadoCluster($cod_cotiz, $datoArray);
                            // if ($data['error'] == EXIT_SUCCESS) {
                                // $dataArray = array(
														// 'codigo_cluster' => $cod_cotiz,
														// 'fecha' => $this->fechaActual(),
														// 'id_usuario' => 1645, //SISEGO
														// 'estado' => 2
													// );
                                // $data = $this->m_utils->insertLogCotizacionInd($dataArray);

                                // if ($data['error'] == EXIT_SUCCESS) {
                                    // //SE AGREGO EL 13-02-2020
                                    // // if($flg_update_pep == 1) {
                                    // // $data = $this->m_subproy_pep_grafo->updateSisegoPep2Grafo($indicador, $pep2, $grafo, $itemplanData, $fecha);
                                    // // } else {
                                    // $data = $this->m_subproy_pep_grafo->insertSisegoPep2Grafo($indicador, $pep2, $grafo, $itemplanData, $fecha);
                                    // // }
									// $this->m_utils->updateSisego($row['pep']);
                                    // if ($data['error'] == EXIT_SUCCESS) {
                                        // $this->m_utils->saveLogSigoplus('SISEGO PEP2 GRAFO', '', $itemplanData, '', $indicador, '', '', 'TRAMA COMPLETADA', 'OPERACION REALIZADA CON EXITO', '1', null, $JsonLog);
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
                        // }


                        //////////////////////////////
                    } else if ($itemplanData == null) {
                        $data['itemplan'] = null;
                        throw new Exception('Error al obtener el itemplan.');
                    }
                    $data['id'] = $id;

                    $countParalizaSi = $this->m_utils->getCountSisegoParaliza($indicador);
                    if ($countParalizaSi > 0) {
                        $data['flg_paralizado'] = 1;
                    } else {
                        $data['flg_paralizado'] = 0;
                    }


                    $dataEmpresa = $this->m_utils->getEECCXCentralPqt($idCentral, 1);
                    $data['empresacolab'] = $dataEmpresa['empresaColabDesc'];
                    $data['msj'] = 'Registro Exitoso.';
                    /////////////////////////////
                    $data['itemplan'] = $itemplanData;
                    $data['sinfix']   = $cod_cotiz;
                    $this->db->trans_commit();
                }
                $arrayItemSisegos[] = $data;
            } catch (Exception $e) {
				$data['itemplan'] = null;
                $this->db->trans_rollback();
                $data['error'] = EXIT_ERROR;
                $data['msj'] = $e->getMessage();
                $arrayItemSisegos[] = $data;
            }
        }
	   $this->m_utils->saveLogSigoplus('TRAMA EVALUAR PEP - REG ITEMPLAN', NULL, NULL, '', $indicador, NULL, NULL, 'INFO DE ENVIO', NULL, 1, 19, $arrayItemSisegos);

       $dataSend = json_encode($arrayItemSisegos);
       $url = 'https://172.30.5.10:8080/obras2/recibir_itemplan.php';

       $response = $this->m_utils->sendDataToURL($url, $dataSend);

	   if($response) {
			$this->m_utils->saveLogSigoplus('TRAMA EVALUAR PEP - REG ITEMPLAN', NULL, NULL, '', NULL, NULL, NULL, 'ERROR EN RECEPCION DE TRAMA', NULL, 2, 19, $response);
	   } else {
		   $this->m_utils->saveLogSigoplus('TRAMA EVALUAR PEP - REG ITEMPLAN', NULL, NULL, '', NULL, NULL, NULL, 'ERROR EN RECEPCION DE TRAMA', NULL, 2, 19, $response);
	   }
      
        echo json_encode($arrayItemSisegos);
    }
	
	
	public function forzarITemSinCoti() {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try {
			$array = $this->m_utils->getDataRegistroSinCoti();
				foreach($array AS $row) {
					$codigo_cluster = $this->m_utils->getCodCluster();
					
					$arrayData = array(
                                'nodo_principal'   => null,
                                'nodo_respaldo'    => null,
                                'codigo_cluster'   => $codigo_cluster,
                                'id'               => null,
                                'sisego'           => $row['sisego'],
                                'segmento'         => null,
                                'cliente'          => null,
                                'descripcion'      => null,
                                'servicios'        => null,
                                'acceso_cliente'   => null,
                                'tendido_externo'  => null,
                                'tipo_cliente'     => null,
                                'nro_pisos'        => null,
                                'departamento'     => null,
                                'provincia'          => null,
                                'distrito'           => null,
                                'direccion'          => null,
                                'piso'               => null,
                                'interior'           => null,
                                'latitud'            => '-',
                                'longitud'           => '-',
                                'fecha_registro'     => $row['fecha_creacion'],
                                'nombre_estudio'     => null,
                                'idSubProyecto'      => $row['idSubProyecto'],
                                'flg_tipo'        	 => 2,
                                'estado'             => 2,
                                'tipo_requerimiento' => null,
                                'clasificacion'      => null,
                                'tipo_proyecto'      => null,
                                'idCentral'          => $row['idCentral'],
                                'flg_principal'      => $row['flg_principal'],
                                'tipo_enlace'        => null,
                                'flg_lan_to_lan'     => null,
                                'flg_robot'          => 2,
                                'facilidades_de_red' => null,
                                'requiere_seia'      => null,
                                'requiere_aprob_mml_mtc' => null,
                                'requiere_aprob_inc' => null,
								'flg_paquetizado'    => $row['paquetizado_fg'],
								'costo_mano_obra'    => $row['costo_unitario_mo_crea_oc'],
								'costo_materiales'   => $row['costo_unitario_mat'],
								'costo_total'		 => $row['costo_total'],
								'itemplan'           => $row['itemPlan']
                            );
							
					$flg = $this->m_utils->registrarCotIndividual($arrayData);
					
					_log("itemplan: ".$row['itemPlan']." - RESULTADO: ".$flg);
				}
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
	
	function getMontoTemp() {
		$data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try {
			$pep1   = trim($this->input->post('pep1'));
			$sisego = trim($this->input->post('sisego'));
			
			if($pep1 == null || $pep1 == '') {
				throw new Exception('No enviaron la PEP1.');
			}
			
			if($sisego == null || $sisego == '') {
				throw new Exception('No enviaron el sisego.');
			}

			$monto_temporal = $this->m_utils->getMontoTemporalByPep1($pep1);
			$fecha_actual   = $this->m_utils->fechaActual();
			if($monto_temporal == null || $monto_temporal == '') {
				$count = $this->m_utils->getCountPep($pep1);
				
				if($count > 0) {
					$msjHorario = $this->m_utils->getMensajeHorarioRegItemSisego();
                    throw new Exception($msjHorario);
				}
				
				$arrayPepEvalua = array(
											'pep' => trim($pep1),
											'fecha_registro' => $fecha_actual,
											'flg_estado' => 2
										);

				$data = $this->m_utils->insertEvaluaPep($arrayPepEvalua);

				$data['msj'] = 'La PEP no existe esperar a que se consulte con SAP, se ingresara en menos de 24h.';
				$data['error'] = EXIT_ERROR; 
			} else {
				$data['error']    = EXIT_SUCCESS;
			}
			
			$data['monto_temp'] = $monto_temporal;
		} catch (Exception $e) {
			$this->m_utils->saveLogSigoplus('TRAMA MONTO TEMPORAL', $pep1, '', '', $sisego, '', '', 'ERROR EN LA TRAMA', $e->getMessage(), null, null, null);
			$data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
	}
	
	/**czavalacas 26.06.2020 nueva trama solicitada por O.Saravia
		variables definidas 
		tipo		1=CANCELADO; 2= TRUNCO; 3=LIQUIDADO
		error   	1=ERROR; 0=EXITO
		mensaje     'Detalle de la transaccion'
	**/
	function canelarItemplanSisego(){        
        header("Access-Control-Allow-Origin: *");        
        $output['error']  	= EXIT_ERROR_SISEGO;
        $output['mensaje'] 	= 'No se logro cancelar el itemplan.';       
        $output['tipo']  	= null;
        $itemplanGlobal 	= null;
        try {           
           $method = $_SERVER['REQUEST_METHOD'];
           if($method=='PUT' || $method=='POST'){
               $inputJSON = file_get_contents('php://input');
			   log_message('error', 'input_trama1:'.print_r($inputJSON,true));
			   //log_message('error', 'input_trama2:'.print($inputJSON,true));
               $input = json_decode( $inputJSON, TRUE );
               log_message('error', 'input_trama:'.print_r($input,true));
               if(!isset($input['itemplan'])){                  
                   throw new Exception('No se detecto el itemplan en la trama de envio.');
               }
               $infoItemplan = $this->M_integracion_sisego_web->getInfoItempla($input['itemplan']);
               if($infoItemplan == null){
                   throw new Exception('El Itemplan '.$input['itemplan'].' no existe en web PO.');
               }
               $itemplanGlobal = $infoItemplan['itemPlan'];
               $output =   $this->evaluarObraToCancel($infoItemplan);            
               
           }else{
               $output = $this->getMsjTypeMethod($method, 'Cancelar Itemplan (sisego)');
           }           
        } catch (Exception $e) {
            $output['mensaje'] = $e->getMessage();
            $this->m_utils->saveLogSigoplus('TRAMA SOLICITUD CANCELACION - SERVICIO', NULL, $itemplanGlobal, NULL , NULL, NULL, NULL, 'ERROR EN RECEPCION DE TRAMA', $e->getMessage(), 2, 11, $input);
		}
		//log_message('error', 'integracion sisego cancelar:'.print_r($output,true));
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($output));
	}	

	function evaluarObraToCancel($infoItemplan) {
	    $data['error']      = EXIT_ERROR_SISEGO;
	    $data['mensaje']    = null;
		$data['tipo']    	= null;
	    try {
	        $idEstadoPLan  = $infoItemplan['idEstadoPlan'];
	        $itemplan      = $infoItemplan['itemPlan'];
			$data['flg_des_paralizado'] = 0;
	        if(in_array($idEstadoPLan, array(ID_ESTADO_PRE_REGISTRO, ID_ESTADO_PRE_DISENIO))){//CANCELAR
	            $dataUpdateItem = array(	
											"itemplan"         => $itemplan,
                        	                "idEstadoPlan"     => ID_ESTADO_CANCELADO,
                        	                "usu_upd"          => "SISEGO WEB",
                        	                "fecha_upd"        => $this->fechaActual(),
                        	                "descripcion"      => "SOLICITUD DE SISEGO WEB",
                        	                "fechaCancelacion" => $this->fechaActual()
                        	            );
	            
	            $dataLogPlanObra = array(   'tabla'             =>  'planobra',
                                            'actividad'         =>  'update',
                                            'itemplan'          =>   $itemplan,
                                            'itemplan_default'  =>   'idEstadoPlan=6',
                                            'fecha_registro'    =>   $this->fechaActual(),
                                            'id_usuario'        =>   265,    
                                            'comentario'        =>  'SISEGO WEB',
                                            'idEstadoPlan'      =>   ID_ESTADO_CANCELADO
	                	                );
										
				$dataUp = $this->M_integracion_sisego_web->updateTramaCancelSisego($dataUpdateItem, $dataLogPlanObra);
	            if($dataUp['error']==EXIT_SUCCESS){
	                $data['error']    	= EXIT_SUCCESS_SISEGO;
	            }else{
	                throw new Exception('Error interno al intentar cancelar la obra');
	            }
	            $data['tipo']   	= 1;
				$data['mensaje']    = 'Se Cancelo La obra con exito.';
				$this->m_utils->generarSolicitudCertiAnulEdiOC($itemplan, 'PLAN', 4, null);//CREAMOS SOLICITUD DE ANULACION
				$data = $this->getLogicaParalizado($itemplan);
	        }else if(in_array($idEstadoPLan, array(ID_ESTADO_DISENIO, ID_ESTADO_DISENIO_EJECUTADO, ID_ESTADO_EN_APROBACION, ID_ESTADO_EN_LICENCIA, ID_ESTADO_PLAN_EN_OBRA))){//SE TRUNCA
	            $dataUpdateItem = array(	
											"itemplan"     => $itemplan,
                        	                "idEstadoPlan" => ID_ESTADO_TRUNCO,
                        	                "usu_upd"      => "SISEGO WEB",
                        	                "fecha_upd"    => $this->fechaActual(),
                        	                "descripcion"  => "SOLICITUD DE SISEGO WEB",
                        	                "fechaTrunca"  => $this->fechaActual()
                        	            );
	            
	            $dataLogPlanObra = array(   'tabla'             =>  'planobra',
                        	                'actividad'         =>  'update',
                        	                'itemplan'          =>   $itemplan,
                        	                'itemplan_default'  =>   'idEstadoPlan=10',
                        	                'fecha_registro'    =>   $this->fechaActual(),
                        	                'id_usuario'        =>   265,
                        	                'comentario'        =>  'SISEGO WEB',
                        	                'idEstadoPlan'      =>   ID_ESTADO_TRUNCO
	                	                );
										
	            $dataUp = $this->M_integracion_sisego_web->updateTramaCancelSisego($dataUpdateItem, $dataLogPlanObra);
				
	            if($dataUp['error']==EXIT_SUCCESS){
					$this->m_utils->generarSolicitudCertiAnulEdiOC($itemplan, 'PLAN', 4, null);//CREAMOS SOLICITUD DE ANULACION
					$data = $this->getLogicaParalizado($itemplan);
	                $data['error']    	= EXIT_SUCCESS_SISEGO;
	            }else{
	                throw new Exception('Error interno al intentar cancelar la obra');
	            }
	            $data['tipo']   	= 2;
				$data['mensaje']    = 'Se Trunco La obra con exito.';
	        }else if(in_array($idEstadoPLan, array(ID_ESTADO_PRE_LIQUIDADO, ID_ESTADO_EN_VALIDACION, ID_ESTADO_TERMINADO, ID_ESTADO_CERRADO, ID_ESTADO_EN_CERTIFICACION))){//SE REBOTA
	            $data['error']    	= EXIT_SUCCESS_SISEGO;
				$data['mensaje']    = 'La obra ya se encontraba Liquidada';
				$data['tipo']    	= 3;
	        }else if(in_array($idEstadoPLan, array(ID_ESTADO_TRUNCO))){//CANCELAR
	            $data['error']    	= EXIT_SUCCESS_SISEGO;
				$data['mensaje']    = 'La obra ya se encontraba Trunco';
				$data['tipo']    	= 2;
				
				$this->m_utils->generarSolicitudCertiAnulEdiOC($itemplan, 'PLAN', 4, null);//CREAMOS SOLICITUD DE ANULACION	
	        }else if(in_array($idEstadoPLan, array(ID_ESTADO_CANCELADO))){//CANCELAR
	            $data['error']    	= EXIT_SUCCESS_SISEGO;
				$data['mensaje']    = 'La obra ya se encontraba Cancelada';
				$data['tipo']    	= 1;
	        }else{
	            throw new Exception('Estado de la Obra no valida.');
	        }
	        
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    return $data;
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
							   'fechaReactivacion' => $this->m_utils->fechaActual());

			$data = $this->m_utils->updateFlgParalizacion($itemplan, FLG_ACTIVO, $dataArray);
			
			$data['flg_des_paralizado'] = 1;
		}
		
		return $data;
	}
	
	function asignarDistanciaCotizacion() {
		$dataArray = $this->m_utils->getDataCotizacionAll(0, 2);
		
		foreach($dataArray as $row) {
			list($arrayCen, $distancia) = _getDataKmz($row['longitud'], $row['latitud']);
			
			$this->m_utils->updateDistanciaCoti($row['codigo_cluster'], $distancia);
		}
	}
}