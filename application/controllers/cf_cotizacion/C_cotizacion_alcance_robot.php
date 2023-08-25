<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_cotizacion_alcance_robot extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('mf_cotizacion/m_cotizacion_alcance_robot');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('map_utils/coordenadas_utils');

        $this->load->library('lib_utils');
        $this->load->helper('url');

    }

    function index() {
        $logedUser = $this->session->userdata('usernameSession');
        $idUsuario = $this->session->userdata('idPersonaSession');

        /*if ($logedUser != null) {
            $data['nombreUsuario'] =  $this->session->userdata('usernameSession');	
            $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
            $permisos =  $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, 1, 1);
            $data['opciones'] = $result['html'];*/
            $data['tablaCostoxAlcance'] = $this->getTablaCostosxIntervalo('','','','');
			$data['cmbSubProyecto']     = __buildSubProyecto(3, 1, 1);
			$data['cmbEmpresa']         = __buildCmbEmpresaColab();
			$data['cmbEstacion']        = __buildComboEstacionNewAll();
			// $infoCto     = $this->getCoordCTO();
			$infoCtoEdif = $this->getCoordCTOEdificios();
            $infoMdf     = $this->getCoordMDF();
            // $infoReserva = $this->getCoordReserva();
            // $infoEbc     = $this->getCoordEbc();
			
			// $data['marcadores_cto']    =  $infoCto['marcaCto'];
			// $data['info_markers_cto']  =  $infoCto['infoMarcaCto'];
			
			$data['marcadores_cto_edif']    =  $infoCtoEdif['marcaCtoEdif'];
			$data['info_markers_cto_edif']  =  $infoCtoEdif['infoMarcaCtoEdif'];
			
			$data['marcadores_mdf']    =  $infoMdf['marcaMdf'];
            $data['info_markers_mdf']  =  $infoMdf['infoMarcaMdf'];
            
            // $data['marcaReserva']     = $infoReserva['marcaReserva'];
            // $data['infoMarcaReserva'] = $infoReserva['infoMarcaReserva'];

            // $data['marcaEbc']     = $infoEbc['marcaEbc'];
            // $data['infoMarcaEbc'] = $infoEbc['infoMarcaEbc'];
            $this->load->view('vf_cotizacion/v_cotizacion_alcance_robot', $data);
        /*} else {
            $this->session->sess_destroy();
            redirect('login', 'refresh');
        } */
    }

    function getTablaAlcance() {
        $data = $this->m_cotizacion_alcance_robot->getDataTablaAlcance();


        $html = '<table id="data-table1" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>Nro</th>
                            <th>CLASIFICACI&Oacute;N</th>
                            <th>COTIZACI&Oacute;N AUTOM&Aacute;TICA</th>
                            <th>ENVIO SISEGO</th>
                            <th>CREAR ITEMPLAN AUTOM&Aacute;TICA</th>
                        </tr>
                    </thead>                    
                    <tbody>';
                $cont = 1;
                                                                                                                  
                foreach($data as $row){
                    $checked1 = null;
                    $checked2 = null;
                    $checked3 = null;  
                    if($row['flg_cotizacion_automatica'] == 1) {
                        $checked1 = 'checked';
                    }
                    $checkboxCotiAuto = '<input type="checkbox" id="" '.$checked1.'/>';

                    if($row['flg_envio_sisego'] == 1) {
                        $checked2 = 'checked';
                    }
                    $checkboxEnvioSi  = '<input type="checkbox" id="" '.$checked2.'/>';

                    if($row['flg_crea_itemplan'] == 1) {
                        $checked3 = 'checked';
                    }                    
                    $checkboxCreaItem = '<input type="checkbox" id="" '.$checked3.'/>';

                    $html .=' <tr>
                                <td>'.$cont.'</td>
                                <td>'.utf8_decode($row['clasificacion']).'</td>
                                <td>'.$checkboxCotiAuto.'</td>
                                <td>'.$checkboxEnvioSi.'</td>
                                <td>'.$checkboxCreaItem.'</td>
                            </tr>';
                        $cont++;    
                    }
                $html .='</tbody>
                    </table>';
                    
            return $html;
    }

    function getTablaCostosxIntervalo($idSubProyecto, $idEmpresaColab, $idEstacion, $jefatura) {
		// ini_set('memory_limit', '1000M');
		
        $data = $this->m_cotizacion_alcance_robot->getCostoPaquetizado($idSubProyecto, $idEmpresaColab, $idEstacion, $jefatura);

		$total = 0;
        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>NRO</th>
                            <th>TIPO PRECIARIO</th>
                            <th>PARTIDA</th>
                            <th>BAREMO</th>
                            <th>COSTO</th>
                            <th>TIPO JEFATURA</th>
                            <th>TOTAL</th>
                        </tr>
                    </thead>                    
                    <tbody>';
                $cont = 1;
                                                                                                                  
                foreach($data as $row){
					$total = $total + $row['total'];
                    $html .=' <tr>
                                <td>'.$cont.'</td>
                                <td>'.$row['tipoPreciario'].'</td>
                                <td>'.$row['partidaPqt'].'</td>
                                <td>'.$row['baremo'].'</td>
                                <td>'.$row['costo'].'</td>
                                <td>'.$row['tipoJefatura'].'</td>
                                <td>'.$row['form'].'</td>
                            </tr>';
                        $cont++;    
				}
					$html .='<tr>
								<td></td>
								<td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>TOTAL</td>
								<td>'.$total.'</td>
							</tr>
					</tbody>
                </table>';
                    
            return $html;
    }

    function getDataSimulacion() {
		$data['error'] = EXIT_ERROR;
		$data['msj']   = null;
		try {
			$latitud       = $this->input->post('latitud');
			$longitud      = $this->input->post('longitud');
			$clasificacion = $this->input->post('clasificacion');
			$tipo_cliente  = $this->input->post('tipo_cliente');

			$idCentral     = $this->input->post('idCentral');
			$latitudNodo   = $this->input->post('latitudNodo');
			$longitudNodo  = $this->input->post('longitudNodo');
            $flg_kmz_arque = $this->input->post('flg_kmz_arque');
			$flg_log_robot = $this->input->post('flg_log_robot');
			
            $distancia = null;
            $codigo    = null;
			$idSubProyecto = $this->input->post('idSubProyecto');
            
            $rowCtOTipoDiseno = $this->m_utils->getCTOCotizacion_v2($clasificacion, $tipo_cliente);
            $tipo      = null;
			
			if($flg_log_robot == 1) {
				list($dataRobot, $data) = __data_robot_coti_v2($clasificacion, $tipo_cliente, $longitud, $latitud, $idCentral, $idSubProyecto);
				
				if($data['flg_um'] == 1) {
					throw new Exception('La cotizacion pertenece a UM, la facilidad es : '.$dataRobot['fac_red']);
				}
			} else {
				list($dataRobot, $data) = __data_robot_coti_v2_simu($clasificacion, $tipo_cliente, $longitud, $latitud, $idCentral, $idSubProyecto);
			}
			
			$fac_red     = $dataRobot['fac_red'];
			
			$codigo    = $dataRobot['codigo'];
			 // $arrayIdCentral = _getDataKmz($longitud, $latitud); //ESTO ME SIRVE SOLO PARA SACAR LA EECC POR MEDIO DE LA CENTRAL
                // $idCentral = $arrayIdCentral[0]['idCentral'];
                // $codigo    = $arrayIdCentral[0]['codigo'];
			
			$distancia = $dataRobot['distancia'];
			$tipo      = $dataRobot['tipo'];
			$hilo_disp = $dataRobot['hilo_disp'];

			// $codigo_enviar = $codigo;
			// if($dataRobot['tipo'] == 'EBC') {
				// $codigo_enviar = $fac_red;
			// } else {
				// $codigo_enviar = $codigo;
			// }
			
			if($fac_red == null || $fac_red == '') {
				$fac_red = $codigo;
			}
			
			// $dataEnvio['materiales']  = $data['costo_mat_envio_sisego'];
			// $dataEnvio['mano_obra']   = $data['costo_mo_envio_sisego'];
			// $dataEnvio['nodo'] 	     = $codigo_enviar;
			// $dataEnvio['duracion']    = $data['duracion'];
			// $dataEnvio['tipo_diseno'] = $data['id_tipo_diseno'];
			// $dataEnvio['diseno'] 	 = $data['arrayCostos']['diseno_total'];
			// $dataEnvio['comentario']  = NULL;
			
			// _log(print_r($dataEnvio, true));	
			$data['fac_red'] = $dataRobot['fac_red'];
			$data['lat_ctoResEbcMdf'] = $dataRobot['lat_ctoResEbcMdf'];
			$data['lon_ctoResEbcMdf'] = $dataRobot['lon_ctoResEbcMdf'];			
					
            $metroTendidoAe = $this->getMetrosTendidos($distancia);
			
			if($clasificacion != 'ESTUDIO DE CAMPO') {
				if($metroTendidoAe > 5000) {
					throw new Exception('El metro tendido es mayor a 5000');
				}
			}

            if($codigo == null) {
                throw new Exception('El cliente no se encuentra en un area de algun MDF.');
            }

			if(!$rowCtOTipoDiseno) {
				throw new Exception('No se encuentra este emparejamiento en la matriz : '.$clasificacion.' - '.$tipo_cliente);
            }
            
            // if($flg_kmz_arque == 1) {
                // $inc_necesita = _getDataKmzArqueologico($longitud, $latitud);
            // } else {
                // $inc_necesita = 'NO';
            // }
			

            $data['tipo'] = $tipo;

			$data['error'] = EXIT_SUCCESS;
		} catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
		echo json_encode($data);
    }
	
	function getCostosCotizacionPedido() {
		$item          = $this->input->post('item'); //item del excel numeros
		$latitud       = $this->input->post('latitud');
		$longitud      = $this->input->post('longitud');
		
		$arrayIdCentral = _getDataKmz($longitud, $latitud); //ESTO ME SIRVE SOLO PARA SACAR LA EECC POR MEDIO DE LA CENTRAL
		$idCentral = $arrayIdCentral[0]['idCentral'];
				
		list($dataRobot, $data) = __data_robot_coti_v2('Estudio Especial Gris', 'Edificio Monocliente', $longitud, $latitud, $idCentral, 14);
		
		_log(print_r($dataRobot, true));
	}

    function getMetrosTendidos($distancia) {
        //$distancia = $this->ditanciaVicenty($latitud, $longitud, $latitudNodo, $longitudNodo);
        $metrosTendidos = $distancia + ($distancia * 0.30); //FORMULA PARA SACAR LOS METROS TENDIDOS

        return $metrosTendidos;
    }

    function ditanciaVicenty($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000) {
        // Se convierte a radianes
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo   = deg2rad($latitudeTo);
        $lonTo   = deg2rad($longitudeTo);
      
        $lonDelta = $lonTo - $lonFrom;
        $a = pow(cos($latTo) * sin($lonDelta), 2) +
             pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
        $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);
      
        $angle = atan2(sqrt($a), $b);
        return $angle * $earthRadius;
    }

    // function ditanciaVicenty($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000) {
    //     // Se convierte a radianes
    //     $latFrom = deg2rad($latitudeFrom);
    //     $lonFrom = deg2rad($longitudeFrom);
    //     $latTo = deg2rad($latitudeTo);
    //     $lonTo = deg2rad($longitudeTo);
      
    //     $latDelta = $latTo - $latFrom;
    //     $lonDelta = $lonTo - $lonFrom;
      
    //     $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
    //       cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
    //     return $angle * $earthRadius;
    // }

    function getDataInfoCotiCentral(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try{
        
            $longitud = $this->input->post('longitud');
            $latitud  = $this->input->post('latitud');

            $arrayIdCentral = _getDataKmz($longitud, $latitud);


            $data['dataCentral'] = $arrayIdCentral;
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

    function getDataByCodigoCotizacion() {
        $codigoCotizacion = $this->input->post('codigoCotizacion');
        $arrayEstados = array(1,2,3);
        $dataArray = $this->m_utils->getDataCotizacionIndividual(NULL, $codigoCotizacion, $flgDetalle=1, null, null, null, null, null);
        $data['data_array'] = $dataArray;
        echo json_encode($data);
    }
	
	function getCtosNormalSimu() {
		ini_set('memory_limit', '1000M');
		$infoCto = $this->getCoordCTO();
		
		$data['marcadores_cto']   = $infoCto['marcaCto'];
		$data['info_markers_cto'] = $infoCto['infoMarcaCto'];
		
		echo json_encode($data);
	}
	
	function getCoordCTO(){
        //$data = array();
        $marcaCto   = array();
        $infoMarcaCto = array();
        $dataArray = $this->m_utils->getAllCto();
        foreach($dataArray as $row){
            $temp = array(
                            'codigo'   => $row['codigo'],
                            'longitud' => $row['longitud'],
                            'latitud'  => $row['latitud'],
                            'icon_cto' => ($row['hilos_disponibles'] == 0) ? base_url().'public/img/iconos/cto_ult_2_red.png' : base_url().'public/img/iconos/cto_ult_2.png'
                         );
            //$temp = array($row['codigo'], $row['longitud'], $row['latitud']);
            array_push($marcaCto, $temp);
    
            $temp2 = array('<table style="text-align: left;">
                                <tbody>
                                    <tr>
                                        <td><strong>Tipo:</strong></td>
                                        <td>CTO</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Codigo:</strong></td>
                                        <td>'.$row['codigo'].'</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Hilos Disponibles:</strong></td>
                                        <td>'.$row['hilos_disponibles'].'</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total Hilos:</strong></td>
                                        <td>'.$row['total_hilos'].'</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Coordenadas:</strong></td>
                                        <td>('.$row['longitud'].','.$row['latitud'].')</td>
                                    </tr>
									<tr>
                                        <td><strong>Cant. aprob. Cotizaciones:</strong></td>
                                        <td>'.$row['cant_coti_aprob'].'</td>
                                    </tr>
									<tr>
                                        <td><strong>Tecnologia:</strong></td>
                                        <td>'.$row['tecnologia'].'</td>
                                    </tr>
									<tr>
                                        <td><strong>Ubicacion: </strong></td>
                                        <td>'.$row['ubicacion_cto'].'</td>
                                    </tr>
                                </tbody>
                            </table>');
            array_push($infoMarcaCto, $temp2);
        }
        $data['marcaCto'] 	  = $marcaCto;
        $data['infoMarcaCto'] = $infoMarcaCto;
        return $data;
    }
	
	function getCoordCTOEdificios(){
        //$data = array();
        $marcaCto   = array();
        $infoMarcaCto = array();
        $dataArray = $this->m_utils->getAllCtoEdificios();
        foreach($dataArray as $row){
            $temp = array(
                            'codigo'   => $row['codigo'],
                            'longitud' => $row['longitud'],
                            'latitud'  => $row['latitud'],
                            'icon_cto' => ($row['hilos_disponibles'] == 0) ? base_url().'public/img/iconos/cto_edif_2_red.png' : base_url().'public/img/iconos/cto_edif_2.png'
                         );
            //$temp = array($row['codigo'], $row['longitud'], $row['latitud']);
            array_push($marcaCto, $temp);
    
            $temp2 = array('<table style="text-align: left;">
                                <tbody>
                                    <tr>
                                        <td><strong>Tipo:</strong></td>
                                        <td>CTO</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Codigo:</strong></td>
                                        <td>'.$row['codigo'].'</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Hilos Disponibles:</strong></td>
                                        <td>'.$row['hilos_disponibles'].'</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total Hilos:</strong></td>
                                        <td>'.$row['total_hilos'].'</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Coordenadas:</strong></td>
                                        <td>('.$row['longitud'].','.$row['latitud'].')</td>
                                    </tr>
									<tr>
                                        <td><strong>Cant. aprob. Cotizaciones:</strong></td>
                                        <td>'.$row['cant_coti_aprob'].'</td>
                                    </tr>
						
									<tr>
                                        <td><strong>Tecnologia:</strong></td>
                                        <td>'.$row['tecnologia'].'</td>
                                    </tr>
									<tr>
                                        <td><strong>Ubicacion:</strong></td>
                                        <td>'.$row['tipo_pa'].'</td>
                                    </tr>
                                </tbody>
                            </table>');
            array_push($infoMarcaCto, $temp2);
        }
        $data['marcaCtoEdif'] 	  = $marcaCto;
        $data['infoMarcaCtoEdif'] = $infoMarcaCto;
        return $data;
    }
	
	function getReservaSimu() {
		$infoReserva = $this->getCoordReserva();
		
		$data['marcaReserva']     = $infoReserva['marcaReserva'];
		$data['infoMarcaReserva'] = $infoReserva['infoMarcaReserva'];
		
		echo json_encode($data);
	}

    function getCoordReserva(){
        //$data = array();
        $marcaReserva     = array();
        $infoMarcaReserva = array();
        $dataArray = $this->m_utils->getAllReservas();
        foreach($dataArray as $row){
            $temp = array(
                            'codigo'       => $row['codigo'],
                            'longitud'     => $row['longitud'],
                            'latitud'      => $row['latitud'],
                            'icon_reserva' => ($row['hilos_disponibles'] == 0) ? base_url().'public/img/iconos/reserva_ult.png' : base_url().'public/img/iconos/reserva_ult.png'
                         );
            //$temp = array($row['codigo'], $row['longitud'], $row['latitud']);
            array_push($marcaReserva, $temp);
    
            $temp2 = array('<table style="text-align: left;">
                                <tbody>
                                    <tr>
                                        <td><strong>Tipo:</strong></td>
                                        <td>RESERVA</td>
                                    </tr>
									<tr>
                                        <td><strong>Id Terminal:</strong></td>
                                        <td>'.$row['id_terminal'].'</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Codigo:</strong></td>
                                        <td>'.$row['codigo'].'</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Hilos Disponibles:</strong></td>
                                        <td>'.$row['hilos_disponibles'].'</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Ubicación:</strong></td>
                                        <td>'.$row['ubicacion_reserva'].'</td>
                                    </tr>
                                    <tr>
                                        <td><strong>COORDENADAS:</strong></td>
                                        <td>('.$row['longitud'].','.$row['latitud'].')</td>
                                    </tr>
                                </tbody>
                            </table>');
            array_push($infoMarcaReserva, $temp2);
        }
        $data['marcaReserva']     = $marcaReserva;
        $data['infoMarcaReserva'] = $infoMarcaReserva;
        return $data;
    }
	
	function getEbcSimu() {
		$infoEbc     = $this->getCoordEbc();
		
		$data['marcaEbc']     = $infoEbc['marcaEbc'];
		$data['infoMarcaEbc'] = $infoEbc['infoMarcaEbc'];
		
		echo json_encode($data);
	}

    function getCoordEbc(){
        //$data = array();
        $marcaEbc     = array();
        $infoMarcaEbc = array();
        $dataArray = $this->m_utils->getAllEbc();
        foreach($dataArray as $row){
            $temp = array(
                            'codigo'       => $row['codigo'],
                            'longitud'     => $row['longitud'],
                            'latitud'      => $row['latitud'],
                            'icon_reserva' => base_url().'public/img/iconos/ebc_ult.png' 
                         );
            //$temp = array($row['codigo'], $row['longitud'], $row['latitud']);
            array_push($marcaEbc, $temp);
    
            $temp2 = array('<table style="text-align: left;">
                                <tbody>
                                    <tr>
                                        <td><strong>Tipo:</strong></td>
                                        <td>EBC</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Codigo:</strong></td>
                                        <td>'.$row['codigo'].'</td>
                                    </tr>
                                    <tr>
                                        <td><strong>NOM. ESTACION:</strong></td>
                                        <td>'.$row['nom_estacion'].'</td>
                                    </tr>
                                    <tr>
                                        <td><strong>DIRECCION:</strong></td>
                                        <td>'.$row['direccion'].'</td>
                                    </tr>
                                    <tr>
                                        <td><strong>COORDENADAS:</strong></td>
                                        <td>('.$row['longitud'].','.$row['latitud'].')</td>
                                    </tr>
                                </tbody>
                            </table>');
            array_push($infoMarcaEbc, $temp2);
        }
        $data['marcaEbc']     = $marcaEbc;
        $data['infoMarcaEbc'] = $infoMarcaEbc;
        return $data;
    }
	
	function getCoordMDF() {
		$marcaMdf   = array();
        $infoMarcaMdf = array();
        $dataArray = $this->m_utils->getDataCoordenadasNodo();
        foreach($dataArray as $row){
            $temp = array($row['codigo'], $row['longitud'], $row['latitud']);
            array_push($marcaMdf, $temp);
    
            $temp2 = array('<table style="text-align: left;">
                                <tbody>
                                    <tr>
                                        <td><strong>Codigo:</strong></td>
                                        <td>'.$row['codigo'].'</td>
                                    </tr>
                                </tbody>
                            </table>');
            array_push($infoMarcaMdf, $temp2);
        }
        $data['marcaMdf'] 	  = $marcaMdf;
        $data['infoMarcaMdf'] = $infoMarcaMdf;
        return $data;
	}
	
	function getFiltrarCostoPaquetizadoSimu() {
		$idSubProyecto  = $this->input->post('idSubProyecto');
		$idEmpresaColab = $this->input->post('idEmpresaColab');
		$idEstacion     = $this->input->post('idEstacion');
		$jefatura       = $this->input->post('jefatura');
		
		$idSubProyecto  = ($idSubProyecto)? $idSubProyecto : null;
		$idEmpresaColab = ($idEmpresaColab)? $idEmpresaColab : null;
		$idEstacion     = ($idEstacion)? $idEstacion : null;
		$jefatura       = ($jefatura)? $jefatura : null;
		
		$data['tablaCostoPaq'] = $this->getTablaCostosxIntervalo($idSubProyecto, $idEmpresaColab, $idEstacion, $jefatura);
		
		echo json_encode($data);
	}
	
	function getSitiosArqueologicos() {
		$arrayKmzSitiosArqueo = _getCoordSitiosHistoricosFull();
		$arrayViasMetro       = _getCoordViasMetroFull();
		
		$data['arrayArqueo'] = $arrayKmzSitiosArqueo;
		$data['arrayViasMetro'] = $arrayViasMetro;

		echo json_encode($data);
	}
	
	function getDataSimulacionCv() {
		$data['error'] = EXIT_ERROR;
		$data['msj']   = null;
		try {
			$latitud       = $this->input->post('latitud');
			$longitud      = $this->input->post('longitud');
			$clasificacion = $this->input->post('clasificacion');
			$tipo_cliente  = $this->input->post('tipo_cliente');

			$idCentral     = $this->input->post('idCentral');
			$latitudNodo   = $this->input->post('latitudNodo');
			$longitudNodo  = $this->input->post('longitudNodo');
            $flg_kmz_arque = $this->input->post('flg_kmz_arque');
			$flg_log_robot = $this->input->post('flg_log_robot');
			
            $distancia = null;
            $codigo    = null;
			$idSubProyecto = $this->input->post('idSubProyecto');
            
            $rowCtOTipoDiseno = $this->m_utils->getCTOCotizacion_v2($clasificacion, $tipo_cliente);
            $tipo      = null;
			
			$arrayIdCentral = _getDataKmz($longitud, $latitud); //ESTO ME SIRVE SOLO PARA SACAR LA EECC POR MEDIO DE LA CENTRAL
			$idCentral = $arrayIdCentral[0]['idCentral'];
			
			list($dataRobot, $data) = __data_robot_coti_luis_pedido($clasificacion, $tipo_cliente, $longitud, $latitud, $idCentral, $idSubProyecto);
			// if($flg_log_robot == 1) {
				// list($dataRobot, $data) = __data_robot_coti_cv($clasificacion, $tipo_cliente, $longitud, $latitud, $idCentral, $idSubProyecto);
			// } else {
				// list($dataRobot, $data) = __data_robot_coti_v2_simu($clasificacion, $tipo_cliente, $longitud, $latitud, $idCentral, $idSubProyecto);
			// }
			
			$data['latitud'] = $latitud;
			$data['longitud'] = $longitud;
			$fac_red     = $dataRobot['fac_red'];
			
			$codigo    = $dataRobot['codigo'];
			 // $arrayIdCentral = _getDataKmz($longitud, $latitud); //ESTO ME SIRVE SOLO PARA SACAR LA EECC POR MEDIO DE LA CENTRAL
                // $idCentral = $arrayIdCentral[0]['idCentral'];
                // $codigo    = $arrayIdCentral[0]['codigo'];
			
			$distancia = $dataRobot['distancia'];
			$tipo      = $dataRobot['tipo'];
			$hilo_disp = $dataRobot['hilo_disp'];

			// $codigo_enviar = $codigo;
			// if($dataRobot['tipo'] == 'EBC') {
				// $codigo_enviar = $fac_red;
			// } else {
				// $codigo_enviar = $codigo;
			// }
			
			if($fac_red == null || $fac_red == '') {
				$fac_red = $codigo;
			}
			
			// $dataEnvio['materiales']  = $data['costo_mat_envio_sisego'];
			// $dataEnvio['mano_obra']   = $data['costo_mo_envio_sisego'];
			// $dataEnvio['nodo'] 	     = $codigo_enviar;
			// $dataEnvio['duracion']    = $data['duracion'];
			// $dataEnvio['tipo_diseno'] = $data['id_tipo_diseno'];
			// $dataEnvio['diseno'] 	 = $data['arrayCostos']['diseno_total'];
			// $dataEnvio['comentario']  = NULL;
			
			// _log(print_r($dataEnvio, true));	
			$data['fac_red'] = $dataRobot['fac_red'];
			$data['lat_ctoResEbcMdf'] = $dataRobot['lat_ctoResEbcMdf'];
			$data['lon_ctoResEbcMdf'] = $dataRobot['lon_ctoResEbcMdf'];	
			$data['distancia'] = $dataRobot['distancia'];
					
            $metroTendidoAe = $this->getMetrosTendidos($distancia);
			$data['metroTendidoAe'] = $metroTendidoAe;
			
			if($clasificacion != 'ESTUDIO DE CAMPO') {
				if($metroTendidoAe > 5000) {
					$data['error'] = EXIT_ERROR;
					$data['tipo'] = null;
					throw new Exception('El metro tendido es mayor a 5000');
				}
			}

            if($codigo == null) {
                throw new Exception('El cliente no se encuentra en un area de algun MDF.');
            }

			if(!$rowCtOTipoDiseno) {
				throw new Exception('No se encuentra este emparejamiento en la matriz : '.$clasificacion.' - '.$tipo_cliente);
            }
            
            // if($flg_kmz_arque == 1) {
                // $inc_necesita = _getDataKmzArqueologico($longitud, $latitud);
            // } else {
                // $inc_necesita = 'NO';
            // }
			
			$data['arrayCostos'] = $dataRobot;
            $data['tipo'] = $tipo;

			$data['error'] = EXIT_SUCCESS;
		} catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
		echo json_encode($data);
    }
	
	function insertDataSimuladorCv() {
		_log("ENTREOAOOOO11211");
		$objDataRobot = $this->input->post('dataRobot');
		$itemplan     = $this->input->post('itemplan');
		
		$arrayData = json_decode($objDataRobot);

		$dataCTO = $this->m_utils->getCtoByCoordV2($arrayData->latitud, $arrayData->longitud);
		if($arrayData->error == 0) {
			// foreach($dataCTO as $row) {
				$arrayInsert[] = array(
										'itemplan' 		   => $itemplan,
										'latitud'  		   => $arrayData->latitud,
										'longitud'		   => $arrayData->longitud,
										'distancia_lineal' => $dataCTO['distancia'],
										'facilidad' 	   => $dataCTO['codigo'],
										'tipo'      	   => $arrayData->tipo,
										'costo_oc'         => $arrayData->costo_oc,
										'costo_mo_pqt'     => $arrayData->total_mo_pqt,
										'costo_mat'        => $arrayData->costo_mat_envio_sisego,
										'tendido'          => $arrayData->metroTendidoAe,
										'error'      	   => $arrayData->error
									);
			// }
			
			// $dataDistancia = $this->m_utils->getCtoDistanciaUnCTOCV($arrayData->latitud, $arrayData->longitud);
			// $dataDistanciaCod = $dataCTO['concat_codigo'];
			// $distancia = $dataDistancia['distancia'];
			// $tendido   = $dataDistancia['tendido'];

			// $tipo = ($arrayData->tipo) ? $arrayData->tipo : NULL;
			// $arrayInsert = array(
									// 'itemplan' 		   => $itemplan,
									// 'latitud'  		   => $arrayData->latitud,
									// 'longitud'		   => $arrayData->longitud,
									// 'distancia_lineal' => $distancia,
									// 'tendido' 		   => $tendido,
									// 'facilidad' 	   => $dataDistanciaCod,
									// 'tipo'      	   => $tipo,
									// 'error'      	   => $arrayData->error
								// );
			
			$this->m_utils->insertRobotCv($arrayInsert);
		}
	
	}
}