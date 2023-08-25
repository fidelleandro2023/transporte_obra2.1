<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_form_cotizacion_individual extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_cotizacion/m_bandeja_cotizacion_individual');
        $this->load->model('mf_cotizacion/m_form_cotizacion_individual');
		$this->load->model('mf_cotizacion/m_cotizacion_alcance_robot');
        $this->load->model('mf_cluster/m_bandeja_cluster');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->library('zip');
        $this->load->helper('url');
    }
    
	public function index()
	{  
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
               $codigo        = (isset($_GET['cod'])    ? $_GET['cod'] : '');
               $estado        = (isset($_GET['estado']) ? $_GET['estado'] : '');
               $flg_principal = (isset($_GET['flg_principal']) ? $_GET['flg_principal'] : '');
	           if($codigo!=null){
	               if($estado ==  0){ //pendiente de cotizacion
				       $arrayDataPqt = $this->m_utils->getDataPqtCostoByCodCoti($codigo);
					   $costoPqt = $this->m_utils->getCostoTotalPaquetizadoCotiEEcc($arrayDataPqt['idSubProyecto'], $arrayDataPqt['idEmpresaColab'], 5, $arrayDataPqt['jefatura']);
						
					   $data['flg_distancia_lineal'] = (($arrayDataPqt['distancia_lineal']+($arrayDataPqt['distancia_lineal']) * 0.30) > 5000 ? 1 : 0);
					   $data['costo_pqt_mo']    = $costoPqt['total_mo_pqt'];
					   $data['arrayTipoDiseno'] = $this->m_utils->getTipoDiseno(NULL);
                       $data['codigo']          =   $codigo;
                       $data['flg_principal']   = $flg_principal;
                       $data['tablaHijos'] = $this->makeHTLMTablaBandeja(null, $codigo);
					   
					   $idEmpresaColab = $this->session->userdata('eeccSession');
        	           $data['listaTiCen'] = $this->m_utils->getAllCentralPqt($idEmpresaColab);
        	           $data['nombreUsuario'] =  $this->session->userdata('usernameSession');	
                       $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
                	   $permisos =  $this->session->userdata('permisosArbol');
                	   $result = $this->lib_utils->getHTMLPermisos($permisos, 1, 1);
                	   $data['opciones'] = $result['html'];
                	   //if($result['hasPermiso'] == true){
                	       $this->load->view('vf_cotizacion/v_form_cotizacion_individual',$data);
                	//    }else{
                	//        redirect('login','refresh');
        	        //    }
	               }else{
	                   redirect('getBandejaCotizacionIndividual','refresh');
	               }
	           }else{
	               redirect('getBandejaCotizacionIndividual','refresh');
	           }
	   }else{
	       redirect('login','refresh');
	   }
    }
        
    function makeHTLMTablaBandeja($idSub, $codigo) {
        $data = $this->m_bandeja_cotizacion_individual->getItemplanPreRegistro($idSub, $codigo, null, null, null,null);

        $html = '<table class="table table-bordered" style="font-size: 10px;">
                    <thead class="thead-default">
                        <tr>                 
                            <th>SISEGO</th>                            
                            <th>ID</th>                              
                            <th>CLIENTE</th>  
                            <th>DESCRIPCION</th>                                   
                            <th>ACCESO CLIENTE</th>
                            <th>TENDIDO EXTERNO</th>
                            <th>TIPO CLIENTE</th>
                            <th>NRO PISOS</th>
                            <th>DEPARTAMENTO</th>
                            <th>PROVINCIA</th>
                            <th>DISTRITO</th>
                            <th>DIRECCION</th>
                            <th>PISO</th>
                            <th>INTERIOR</th>
                            <th>LATITUD</th>
                            <th>LONGITUD</th>
                            <th>NOMBRE ESTUDIO</th>
                        </tr>
                    </thead>
                   
                    <tbody>';															                                                   
            foreach($data as $row){        
                $html .='<tr>                                                         
                            <td>'.$row['sisego'].'</td>
                            <td>'.$row['id'].'</td>
                            <td>'.$row['cliente'].'</td>	
                            <td>'.$row['descripcion'].'</td>						
                            <td>'.$row['acceso_cliente'].'</td>   
                            <td>'.$row['tendido_externo'].'</td>
                            <td>'.$row['tipo_cliente'].'</td>
                            <td>'.$row['nro_pisos'].'</td>
                            <td>'.$row['departamento'].'</td>
                            <td>'.$row['provincia'].'</td>
                            <td>'.$row['distrito'].'</td>
                            <td>'.$row['direccion'].'</td>
                            <td>'.$row['piso'].'</td>
                            <td>'.$row['interior'].'</td>
                            <td>'.$row['latitud'].'</td>
                            <td>'.$row['longitud'].'</td>
                            <td>'.$row['nombre_estudio'].'</td>	
                        </tr>';
            }  
   			  
			 $html .='</tbody>
                </table>';                    
        return utf8_decode($html);
    }
    
    function sendCotizacionIndividual(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try{
            $this->db->trans_begin();
            $nodoPrincipal  = $this->input->post('selectCentral');    
            $nodoRespaldo   = $this->input->post('selectCentral2');
            $facilidadRed   = $this->input->post('inputFacRed');
            $cantCto        = $this->input->post('inputCantCTO');
            $metroTendidoAe = $this->input->post('inputMetroTenAereo');
            $metroTendidoSub = $this->input->post('inputMetroTenSubt');
            $metroCanali    = $this->input->post('inputMetroCana');
            $cantCamaraNue  = $this->input->post('cantCamaNue');
            $cantPostesNue  = $this->input->post('inputPostNue');
            $cantPostesApo  = $this->input->post('inputCantPostApo');
            $requiereSeia   = $this->input->post('reqSia');
            $requeAproMtc   = $this->input->post('reqMtc');
            $requeAproInc   = $this->input->post('reqInc');

            $nodoPrincipalDesc = $this->input->post('nodoPrincipal');
            $nodoRespaldoDesc  = $this->input->post('nodoRespaldo');
            
            $costoMat       = $this->input->post('inputCostoMat');
            $costmoMo       = floatval($this->input->post('costoMoPqt'));
            $costoDiseno    = 0;           
            $costoExpe      = floatval($this->input->post('costoEIA'));
            $costoAdic      = floatval($this->input->post('costoAdicZon'));
            $costoTotalMo   = $costmoMo+$costoExpe+$costoAdic;
            $costoMOSisego  = $costmoMo+$costoExpe+$costoAdic;
            $idTipoDiseno   = $this->input->post('cmbTipoDiseno');
            $duracion       = $this->input->post('duracion');
            $codigo         = $this->input->post('codigo');
            $filePerfil     = $this->input->post('filePerfil');
            $fileSisego     = $this->input->post('fileSisego');
            $fileRutas      = $this->input->post('fileRutas');
            $cantAperCamara = $this->input->post('inputCantAperCamara');
            $flgPrincipal   = $this->input->post('flgPrincipal');
            $tiempoEjec     = 30;//30 DIAS SISEGO ESTANDAR
            $comentario     = $this->input->post('textareaComentario');
            
            $uploaddir =  'uploads/sisego/cotizacion_individual/'.$codigo.'/';//ruta final del file Tss
            $uploadfilePerfil = $uploaddir . basename($_FILES['filePerfil']['name']);
            $uploadfileSisego = $uploaddir . basename($_FILES['fileSisego']['name']);
            $uploadfileRutas  = $uploaddir . basename($_FILES['fileRutas']['name']);

            $nodoP = explode('-', $nodoPrincipalDesc);
            $nodoR = explode('-', $nodoRespaldoDesc);
            
            $count = $this->m_utils->getCountConfirmaSisego($codigo);
			
            $flg_ebc = $this->input->post('flg_ebc');
            $costoOc = floatval($this->input->post('costoOc'));
	
            if($flg_ebc == null || $flg_ebc == '') {
                throw new Exception('Seleccionar si tiene EBC');
            }

			if($costoTotalMo == 0 || $costoTotalMo == '' || $costoTotalMo == null) {
				throw new Exception('Debe ingresar el costo MO.');    
			}

			if($costoMat == 0 || $costoMat == '' || $costoMat == null) {
				throw new Exception('Debe ingresar el costo MAT.');    
			}
			
            if($count >= 1) {
                throw new Exception('Ya tiene formulario registrado.');    
            }
            
            $nodoPrincipalDesc = $nodoP[0];
            $nodoRespaldoDesc  = $nodoR[0];
            if($nodoPrincipal == null || $nodoPrincipalDesc == null) {
                throw new Exception('Debe ingresar el Nodo');    
            }
            
            if($idTipoDiseno == null || $idTipoDiseno == null) {
                throw new Exception('Debe seleccionar tipo de dise&ntilde;o.');  
            }
            
            if($duracion == null) {
                throw new Exception('Debe ingresar la duraci&oacute;n');   
            }
            
            if($requeAproMtc    ==  'SI' || $requiereSeia == 'SI'){
                $tiempoEjec = 60;
            }
            if($requeAproInc    ==  'SI'){
                $tiempoEjec = 90;
            }
            $idUsuario = $this->session->userdata('idPersonaSession');
            if($idUsuario == null || $idUsuario == '') {
                 throw new Exception('La sesi&oacute;n espir&oacute;, recargue la p&aacute;gina.');   
            }
//19-0320600182
            if($flgPrincipal == null || $flgPrincipal == '') {
                throw new Exception('error Principal, comunicarse con el administrador.');   
            }
			
			$codEbc = 'xxxx';
            $tipo   = null;
            if($flg_ebc == 1) {
                $codEbc = $this->input->post('codEbc');

                if($codEbc == '' || $codEbc == null) {
                    throw new Exception('Debe seleccionar un EBC.');    
                }
                $tipo = 'EBC';
                $facilidadRed = $codEbc;
                if($flgPrincipal == 1) {
                    if($nodoRespaldo == null || $nodoRespaldoDesc == null) {
                        throw new Exception('Debe ingrear el nodo de respaldo.');   
                    }
                    $idCentral  = $nodoRespaldo;
                    $nodoEnviar = $nodoRespaldoDesc;
                } else {
                    $idCentral  = $nodoPrincipal;
                    $nodoEnviar = $nodoPrincipalDesc;
                }
            } else {
                $facilidadRed = $this->input->post('inputFacRed');

                if($flgPrincipal == 1) {
                    if($nodoRespaldo == null || $nodoRespaldoDesc == null) {
                        throw new Exception('Debe ingrear el nodo de respaldo.');   
                    }
                    $idCentral  = $nodoRespaldo;
                    $nodoEnviar = $nodoRespaldoDesc;
                } else {
                    $idCentral  = $nodoPrincipal;
                    $nodoEnviar = $nodoPrincipalDesc;
                }
            }

            if (!is_dir ( $uploaddir))
            mkdir ( $uploaddir, 0777 );
        
            $total = $costoTotalMo+$costoMat;
            if (move_uploaded_file($_FILES['filePerfil']['tmp_name'], $uploadfilePerfil) && move_uploaded_file($_FILES['fileSisego']['tmp_name'], $uploadfileSisego)
                && move_uploaded_file($_FILES['fileRutas']['tmp_name'], $uploadfileRutas)) {

                    if($total < 15000) {
                        $dataUpdate = array(
                                                'nodo_principal'              => $nodoPrincipal,
                                                'nodo_respaldo'               => $nodoRespaldo,
                                                'facilidades_de_red'          => $facilidadRed,
                                                'cant_cto'                    => $cantCto,
                                                'metro_tendido_aereo'         => $metroTendidoAe,
                                                'metro_tendido_subterraneo'   => $metroTendidoSub,
                                                'metors_canalizacion'         => $metroCanali,
                                                'cant_camaras_nuevas'         => $cantCamaraNue,
                                                'cant_postes_nuevos'          => $cantPostesNue,
                                                'cant_postes_apoyo'           => $cantPostesApo,
                                                'requiere_seia'               => $requiereSeia,
                                                'requiere_aprob_mml_mtc'      => $requeAproMtc,
                                                'requiere_aprob_inc'          => $requeAproInc,
                                                'costo_materiales'            => $costoMat,
                                                'costo_mano_obra'             => $costmoMo,
                                                'costo_diseno'                => $costoDiseno,
                                                'costo_expe_seia_cira_pam'    => $costoExpe,
                                                'costo_adicional_rural'       => $costoAdic,
                                                'costo_total'                 => ($costoTotalMo+$costoMat+$costoOc),
                                                'tiempo_ejecu_planta_externa' => $tiempoEjec,
                                                'estado'                      => 1,
                                                'fecha_envio_cotizacion'      => $this->fechaActual(),
                                                'usuario_envio_cotizacion'    => $idUsuario,
                                                'duracion'                    => $duracion,
                                                'id_tipo_diseno'              => $idTipoDiseno,
                                                'ubic_perfil'                 => $uploadfilePerfil,
                                                'ubic_sisego'                 => $uploadfileSisego,
                                                'ubic_rutas'                  => $uploadfileRutas,
                                                'cant_apertura_camara'        => $cantAperCamara,
                                                'idCentral'                   => $idCentral,
                                                'comentario'                  => $comentario,
                                                'tipo'                        => $tipo,
                                                'costo_oc'                    => $costoOc
                                            );
                                           
                        $data = $this->m_bandeja_cluster->updateClusterPadre($codigo, $dataUpdate);

                        if($data['error'] == EXIT_ERROR){
                            throw new Exception('Error interno al registrar.');
                        }else{
                            $dataSend = [   'codigo' 		=> $codigo,
                                            'materiales'    => $costoMat,
                                            'mano_obra'     => $costoMOSisego+$costoOc,
                                            'nodo'          => $nodoEnviar,
											'ebc'           => $codEbc,
                                            'duracion'      => $duracion,
                                            'tipo_diseno'   => $idTipoDiseno,
                                            'diseno'        => $costoDiseno,
                                            'comentario'    => $comentario];

                            $url = 'https://172.30.5.10:8080/sisego/Requerimientos/cotizarEstudio';

                            $response = $this->m_utils->sendDataToURL($url, $dataSend);
							_log(print_r($response, true));
							if($response == null || $response == '') {
								$data['error'] = EXIT_ERROR;
								throw new Exception('Error en sigoplus, respuesta vacia, informar al sistema.');
							}
							
							$this->m_utils->saveLogSigoplus('SOLO RESPUESTA COTI', $codigo, NULL, NULL, NULL, NULL, NULL, 'TRAMA COMPLETADA', 'OPERACION REALIZADA CON EXITO', 1, 3, $response, json_encode($dataSend));
                            if($response->error == EXIT_SUCCESS){
								_log("ENTRO1");
                                $dataArray = array(
                                                        'codigo_cluster' => $codigo,
                                                        'fecha'          => $this->fechaActual(),
                                                        'id_usuario'     => $idUsuario,//SISEGO
                                                        'estado'         => 1
                                                    );
                                $data = $this->m_utils->insertLogCotizacionInd($dataArray);

                                if($data['error'] == EXIT_ERROR) {
                                    throw new Exception('error');
                                    $this->db->trans_rollback();
                                } else {
                                    $this->db->trans_commit();
                                    $this->m_utils->saveLogSigoplus('COTIZACION ENVIO INDIVIDUAL', $codigo, NULL, NULL, NULL, NULL, NULL, 'TRAMA COMPLETADA', 'OPERACION REALIZADA CON EXITO', 1, 3, $response, json_encode($dataSend));
                                }
                                
                            }else{
								_log("ENTRO2");
								throw new Exception('Problemas con el sistema SMART, no esta recepcionando la cotizacion : '.$response->mensaje);
                                $data['error'] = EXIT_ERROR; 
                                $this->db->trans_rollback();
                                $this->m_utils->saveLogSigoplus('COTIZACION ENVIO INDIVIDUAL', $codigo, NULL, NULL, NULL, NULL, NULL, 'FALLA EN LA RESPUESTA DEL HOSTING', strtoupper($response->mensaje), 2, 3, $response, json_encode($dataSend));
                            }
                        }
                    } else {
                        $dataUpdate = array(
                                                'nodo_principal'              => $nodoPrincipal,
                                                'nodo_respaldo'               => $nodoRespaldo,
                                                'facilidades_de_red'          => $facilidadRed,
                                                'cant_cto'                    => $cantCto,
                                                'metro_tendido_aereo'         => $metroTendidoAe,
                                                'metro_tendido_subterraneo'   => $metroTendidoSub,
                                                'metors_canalizacion'         => $metroCanali,
                                                'cant_camaras_nuevas'         => $cantCamaraNue,
                                                'cant_postes_nuevos'          => $cantPostesNue,
                                                'cant_postes_apoyo'           => $cantPostesApo,
                                                'requiere_seia'               => $requiereSeia,
                                                'requiere_aprob_mml_mtc'      => $requeAproMtc,
                                                'requiere_aprob_inc'          => $requeAproInc,
                                                'costo_materiales'            => $costoMat,
                                                'costo_mano_obra'             => $costmoMo,
                                                'costo_diseno'                => $costoDiseno,
                                                'costo_expe_seia_cira_pam'    => $costoExpe,
                                                'costo_adicional_rural'       => $costoAdic,
                                                'costo_total'                 => ($costoTotalMo+$costoMat+$costoOc),
                                                'tiempo_ejecu_planta_externa' => $tiempoEjec,
                                                'estado'                      => 4,
                                                'fecha_envio_bandeja_val'     => $this->fechaActual(),
                                                'usuario_envio_bandeja_val'   => $idUsuario,
                                                'duracion'                    => $duracion,
                                                'id_tipo_diseno'              => $idTipoDiseno,
                                                'ubic_perfil'                 => $uploadfilePerfil,
                                                'ubic_sisego'                 => $uploadfileSisego,
                                                'ubic_rutas'                  => $uploadfileRutas,
                                                'cant_apertura_camara'        => $cantAperCamara,
                                                'idCentral'                   => $idCentral,
                                                'flg_rech_conf_ban_conf'      => 0,
                                                'comentario'                  => $comentario,
                                                'tipo'                        => $tipo,
												'costo_oc'                    => $costoOc
                                            );
                            
                        $data = $this->m_bandeja_cluster->updateClusterPadre($codigo, $dataUpdate);

                        if($data['error'] == EXIT_ERROR){
                            throw new Exception('Error interno al registrar.');
                        }else{
                            
                            $arrayData = array (
                                                    'codigo_cluster' => $codigo,
                                                    'fecha_registro' => $this->fechaActual(),
                                                    'id_usuario_reg' => $idUsuario,
                                                    'flg_validacion' => 0
                                                );
                            $data = $this->m_form_cotizacion_individual->insertBandejaCotizacionValidacion($arrayData);
                            if($data['error'] == EXIT_SUCCESS) {
                                $dataArray = array(
                                                        'codigo_cluster' => $codigo,
                                                        'fecha'          => $this->fechaActual(),
                                                        'id_usuario'     => $idUsuario,//SISEGO
                                                        'estado'         => 7
                                                    );
                                $data = $this->m_utils->insertLogCotizacionInd($dataArray);
                                if($data['error'] == EXIT_ERROR) {
                                    $this->db->trans_rollback();
                                } else {
                                    $this->db->trans_commit();
                                }
                            } else {
                                $this->db->trans_rollback();
                            }
                        }
                    }
            }         
            $data['codigo'] = $codigo;
        }catch(Exception $e){
			_log("INSERTAAA LA PPPPPP");
			$data['error'] = EXIT_ERROR;
			$this->db->trans_rollback();
			$this->m_utils->saveLogSigoplus('TRAMA FORM COTIZACION', $codigo, NULL, NULL, NULL, NULL, NULL, 'ERROR EN ENVIAR LA COTI', $e->getMessage(), 2, 3, NULL, json_encode($data));
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function fechaActual()
    {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone', 'America/Lima');
        setlocale(LC_TIME, "es_ES", "esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }

    function getDiasMatriz() {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try {
            $dia  = null;
            $seia = null;
            $mtc  = null;
            $inc  = null;

            $totalMetros = $this->input->post('totalMetros');
            $idCentral   = $this->input->post('idCentral');
            $seia        = $this->input->post('seia');
            $mtc         = $this->input->post('mtc');
            $inc         = $this->input->post('inc');
                
            if($totalMetros != null && $idCentral != null) {
                $arrayDataCentral = $this->m_utils->getDataCentralById($idCentral);

                $arrayData = $this->m_utils->getDiasMatriz($totalMetros, $seia, $mtc, $inc, $arrayDataCentral['flg_tipo_zona'], $arrayDataCentral['jefatura']);
                
                $dia  = $arrayData['dias'];
            }

            $data['dia']  = $dia;
        }catch(Exception $e){
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function getDataSeiaMtc() {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try {
            $dia  = null;
            $seia = null;
            $mtc  = null;
            $inc  = null;

            $totalMetros = $this->input->post('totalMetros');
            $idCentral   = $this->input->post('idCentral');
                
            if($totalMetros != null && $idCentral != null) {
                $arrayDataCentral = $this->m_utils->getDataCentralById($idCentral);

                $arrayData = $this->m_utils->getDiasMatriz($totalMetros, $seia, $mtc, $inc, $arrayDataCentral['flg_tipo_zona'], $arrayDataCentral['jefatura']);

                $seia = $arrayData['seia'];
                $mtc  = $arrayData['mtc'];
            }

            $data['seia'] = $seia;
            $data['mtc']  = $mtc;
        }catch(Exception $e){
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
	
	function getEbcByDistritoByDistrito() {
        $idCentral = $this->input->post('idCentral');

        $dataArray = $this->m_utils->getDataCentralPqtById($idCentral);

        $cmbEbc = __buildComboEBCs($dataArray['departamento']);
		
        $data['cmbEbc'] = $cmbEbc; 
		_log($data['cmbEbc']);
        echo json_encode(array_map('utf8_encode', $data));
    }
}