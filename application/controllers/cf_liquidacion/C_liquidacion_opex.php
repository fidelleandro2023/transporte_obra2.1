<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * @author CARLOS ZAVALA C.
 * 18/01/2018
 *
 */
class C_liquidacion_opex extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_liquidacion/m_liquidacion_opex');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->library('excel');
        $this->load->helper('url');
    }
    
	public function index(){
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
    	       $data['listaEECC'] = $this->m_utils->getAllEECC();
        	   $data['listaZonal'] = $this->m_utils->getAllZonalGroup();
        	   $data['listaSubProy'] = $this->m_utils->getAllSubProyecto();
        	   $data['listafase'] = $this->m_utils->getAllFase();
               $data['tablaAsigGrafo'] = $this->makeHTLMTablaAsignarGrafo($this->m_liquidacion_opex->getPoOpexToAprobacion());               
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
               $permisos =  $this->session->userdata('permisosArbol');
        	   #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_BANDEJAS, ID_PERMISO_HIJO_BANDEJA_APROB);
               $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_NUEVO_MODELO_GESTION_VR, 296, ID_MODULO_PAQUETIZADO);
               $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	         $this->load->view('vf_liquidacion/v_liquidacion_opex',$data);
        	   }else{
        	       redirect('login','refresh');
        	   }
    	 }else{
        	 redirect('login','refresh');
	    }
             
    }
    
    public function asignarGrafo(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $codigo_po  = $this->input->post('id_ptr');
            $vale_re    = $this->input->post('vale_reserva');
            
            $this->db->trans_begin();            
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }
           
            $infoPo = $this->m_liquidacion_opex->getInfoPoByCodPo($codigo_po);        
                
            $dataPo = array('estado_po'     => 3,
                            'vale_reserva'  =>  $vale_re
            );
            $dataLog_po = array('codigo_po'         =>  $codigo_po,
                                'itemplan'          =>  $infoPo['itemplan'],
                                'idUsuario'         =>  $this->session->userdata('idPersonaSession'),
                                'fecha_registro'    =>  $this->fechaActual(),
                                'idPoestado'        =>  3,
                                'controlador'       => 'BANDEJA APROB OPEX'
            );
            
            $dataItemplan = array('idEstadoPlan'    => ID_ESTADO_PLAN_EN_OBRA ,
                                    'usu_upd'       =>  $this->session->userdata('idPersonaSession'),
                                    'fecha_upd'     =>   $this->fechaActual(),   
                                    'descripcion'   =>  'VR OPEX'
           );
           
            $data = $this->m_liquidacion_opex->asignarVrOpex($dataPo, $dataLog_po, $dataItemplan, $infoPo);
           /*##################### INICIANDO TRAMA SIOM 13.05.2019 CZAVALACAS ###############################*/        
            if($data['error']==EXIT_SUCCESS){
                $count = $this->m_utils->getCountSiom($infoPo['itemplan'], $infoPo['idEstacion']);
                if($count == 0) {//SI AUN NO HAY REGISTRO REALIZAMOS EL ENVIO A SIOM
                    $se_envio_fo = true;
                    $emplazamiento =  $this->m_liquidacion_opex->getEmplazamientoIdSiomByidCentral($infoPo['idCentralPqt']);//OBTENEMOS EL ID DEZPLAZAMIENTO DE LA TABLA SIOM_NODOS POR EL ID CENTRAL DE LA PO
                    if($emplazamiento['cant'] >= 1){// SE ENCONTRO NODO
                        $codigo_siom = $this->sendDataToSiom($infoPo['id_eecc_reg'], $infoPo['idEstacion'], $infoPo['estacionDesc'], $infoPo['itemplan'], $emplazamiento['empl_id'], $codigo_po);
                                                       
                        //validar si no viene nulo
                        if($codigo_siom != null){
                            $dataSiom = array(  'itemplan'          => $infoPo['itemplan'],
                                                'idEstacion'        => $infoPo['idEstacion'],
                                                'ptr'               => $codigo_po,
                                                'fechaRegistro'     => $this->fechaActual(),
                                                'idUsuarioRegistro' => $this->session->userdata('idPersonaSession'),
                                                'codigoSiom'        => $codigo_siom,
                                                'ultimo_estado'     => 'CREADA',
                                                'fecha_ultimo_estado' => $this->fechaActual()
                                            );
                            
                            $dataLogPo = array( 'tabla'            => 'Siom',
                                                'actividad'        => 'Registrar Siom',
                                                'itemplan'         => $infoPo['itemplan'],
                                                'fecha_registro'   => $this->fechaActual(),
                                                'id_usuario'       => $this->session->userdata('idPersonaSession')
                            );
                            
                            $dataEstado = array('codigo_siom'           => $codigo_siom,
                                                'estado_desc'           => 'CREADA',
                                                'fechaRegistro'         => $this->fechaActual(),
                                                'usuario_registro'      => $this->session->userdata('usernameSession'),
                                                'estado_transaccion'    => 1
                            );
                            $this->m_liquidacion_opex->insertSiom($dataSiom, $dataLogPo, $dataEstado);
                        }else{
                            log_message('error', 'No se recepciono un codigo siom');
                        }
                    }else{
                        
                        $motivoError = 'NO SE ENCONTRO EMPLAZAMIENTO ID PARA ESE NODO';
                        $estadoError = 4;//NODO NO ENCONTRADO = 4
                       
                        $dataLogSiom = array(   'ptr'           => $codigo_po,
                                                'itemplan'      => $infoPo['itemplan'],
                                                'usuario_envio' => $this->session->userdata('usernameSession'),
                                                'fecha_envio'   => $this->fechaActual());
                        $dataLogSiom['estado']   =  $estadoError;//NODO NO ENCONTRADO = 4
                        $dataLogSiom['mensaje']  =  $motivoError;
                        
                         $dataSiom = array( 'itemplan'              => $infoPo['itemplan'],
                                            'idEstacion'            => $infoPo['idEstacion'],
                                            'ptr'                   => $codigo_po,
                                            'fechaRegistro'         => $this->fechaActual(),
                                            'idUsuarioRegistro'     => $this->session->userdata('idPersonaSession'),
                                            'codigoSiom'            => null,
                                            'ultimo_estado'         => $motivoError,
                                            'fecha_ultimo_estado'   => $this->fechaActual()
                                        );
                                    
                        $this->m_liquidacion_opex->insertLogTramaSiom($dataLogSiom, $dataSiom);
                    }                            
                    
                }else{
                    log_message('error', 'ESTA EN EL SWITCH PERO YA CUENTA CON REGISTRO EN SIOM OBRA');
                }
            }
            ##FIN ENVIO A SIOM##      
            $this->db->trans_commit();
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaAsignarGrafo($this->m_liquidacion_opex->getPoOpexToAprobacion()); 
            log_message('error', 'afeter:'.print_r($data,true));
        }catch(Exception $e){
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function makeHTLMTablaAsignarGrafo($listaPTR){
        
        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th></th>
                            <th>Proyecto</th>
                            <th>Sub Proyecto</th>
                            <th>Itemplan</th>
                            <th>PTR</th>                                                        
                            <th>Costo Total</th>
                            <th>Estado PO</th>
                            <th>Estacion</th>
                            <th>EECC</th>
                            <th>Fase</th>
                            <th>Situacion</th>
                            <th>Ceco</th>
                            <th>Cuenta</th>                           
                            <th>Area Funcional</th>
                        </tr>
                    </thead>                    
                    <tbody>';
        
                foreach($listaPTR->result() as $row){                   
                    $arrayRow = explode('-', $row->codigo_po);
					$btnCheck = '';
					if($row->ceco_opex != null){
						$btnCheck = '<a data-ptr ="'.$row->codigo_po.'" onclick="addValeReserva(this)"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/circle-check-128.png"></a>';
					}
                    if($arrayRow[0] > 2018) {                           
                        $btnDownload = '<a data-ptr ="' . $row->codigo_po . '" data-eecc ="' . $row->id_eecc_reg . '" data-itemplan ="' . $row->itemplan . '" onclick="generarExcelMat(this);"><i class="zmdi zmdi-hc-2x zmdi-case-download"></i></a>';
                    }
                 
                $html .=' <tr>
                            <td>'.$btnCheck.'</td>
							<td>'.$row->proyectoDesc.'</td>
							<td> '.$row->subProyectoDesc.'</td>					
							<td>'.$row->itemplan.'</td>
							<td>'.$row->codigo_po.'</td>
							<td>'.$row->costo_total.'</td>
						    <td>'.$row->estado.'</td>
							<td>'.$row->estacionDesc.'</td>
							<th>'.$row->empresaColabDesc.'</th>
							<th>'.$row->faseDesc.'</th>
					        <th>'.(($row->ceco_opex == null) ? 'SIN PRESUPUESTO' : 'CON PRESPUESTO'). '</th>
							<th>'.$row->ceco_opex.'</th>
							<th>'.$row->cuenta_opex.'</th>
                            <th>'.$row->area_funcional_opex.'</th>                            
						</tr>';
                 }
			 $html .='</tbody>
                </table>';
                    
        return $html;
    }
    /*
    function filtrarTabla(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $SubProy = $this->input->post('subProy');
            $eecc = $this->input->post('eecc');
            $zonal = $this->input->post('zonal');
            $itemPlan = $this->input->post('item');
            $mesEjec = $this->input->post('mes');
            $area = $this->input->post('area');
            $estado = $this->input->post('estado');
            $ano= $this->input->post('ano');
            $idFase= $this->input->post('idFase');
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaAsignarGrafo($this->m_liquidacion_opex->getPtrToLiquidacion($SubProy,$eecc,$zonal,$itemPlan,$mesEjec,$area,$estado,FROM_BANDEJA_APROBACION,$ano,$idFase,2));
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
*/
    
    function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone','America/Lima');
        setlocale(LC_TIME, "es_ES","esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }
    
     public function getExcelPOMatAprob()
    {

        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $codigoPO = $this->input->post('codigoPO');
            $itemplan = $this->input->post('itemplan');
            $idEmpresaColab = $this->input->post('idEmpresaColab');

            $arrayMateriales = $this->m_liquidacion_opex->getMatSapByPO_IP_EECC($itemplan,$codigoPO,$idEmpresaColab);

            ini_set('max_execution_time', 10000);
            ini_set('memory_limit', '2048M');

            $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
            $cacheSettings = array('memoryCacheSize ' => '5000MB', 'cacheTime' => '1000');
            PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

            $this->excel->setActiveSheetIndex(0);
            $this->excel->getActiveSheet()->setTitle('ValeDatos');
            $contador = 1;
            $titulosColumnas = array('MATERIAL','CENTRO', 'CTD', '', '', 'T', 'R', 'ALMACEN', '', '', '', 'FECHA', '', 'OBSERV', '', 'CODIGO PO');

            $this->excel->setActiveSheetIndex(0);

            // Se agregan los titulos del reporte
            $this->excel->setActiveSheetIndex(0)
                ->setCellValue('A1', utf8_encode($titulosColumnas[0]))
                ->setCellValue('B1', utf8_encode($titulosColumnas[1]))
                ->setCellValue('C1', utf8_encode($titulosColumnas[2]))
                ->setCellValue('D1', utf8_encode($titulosColumnas[3]))
                ->setCellValue('E1', utf8_encode($titulosColumnas[4]))
                ->setCellValue('F1', utf8_encode($titulosColumnas[5]))
                ->setCellValue('G1', utf8_encode($titulosColumnas[6]))
                ->setCellValue('H1', utf8_encode($titulosColumnas[7]))
                ->setCellValue('I1', utf8_encode($titulosColumnas[8]))
                ->setCellValue('J1', utf8_encode($titulosColumnas[9]))
                ->setCellValue('K1', utf8_encode($titulosColumnas[10]))
                ->setCellValue('L1', utf8_encode($titulosColumnas[11]))
                ->setCellValue('M1', utf8_encode($titulosColumnas[12]))
                ->setCellValue('N1', utf8_encode($titulosColumnas[13]))
				->setCellValue('O1', utf8_encode($titulosColumnas[14]))
                ->setCellValue('P1', utf8_encode($titulosColumnas[15]));

            foreach ($arrayMateriales as $row) {
                $contador++;
                $this->excel->getActiveSheet()->setCellValue("A{$contador}", $row->codigo_material)
                    ->setCellValue("B{$contador}", $row->codCentro)
                    ->setCellValue("C{$contador}", $row->cantidad_ingreso)
                    ->setCellValue("D{$contador}", null)
                    ->setCellValue("E{$contador}", null)
                    ->setCellValue("F{$contador}", $row->t)
                    ->setCellValue("G{$contador}", null)
                    ->setCellValue("H{$contador}", $row->codAlmacen)
                    ->setCellValue("I{$contador}", null)
                    ->setCellValue("J{$contador}", null)
                    ->setCellValue("K{$contador}", null)
                    ->setCellValue("L{$contador}", $row->fecha)
                    ->setCellValue("M{$contador}", null)
                    ->setCellValue("N{$contador}", null)
					->setCellValue("O{$contador}", null)
                    ->setCellValue("P{$contador}", $row->codigo_po);
            }

            $estiloTituloColumnas = array(
                'font' => array(
                    'name' => 'Calibri',
                    'bold' => true,
                    'color' => array(
                        'rgb' => '000000',
                    ),
                ));

            $this->excel->getActiveSheet()->getStyle('A1:AB1')->applyFromArray($estiloTituloColumnas);

            //Le ponemos un nombre al archivo que se va a generar.
            $archivo = 'ValeDatos'.rand() .'.xls';
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$archivo.'"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
            //Hacemos una salida al navegador con el archivo Excel.
            $objWriter->save('download/detalleMatSAP/' . $archivo);
            // $objWriter->save('php://output');
            // readfile('download/detalleMatSAP/ValeDatos.xls');
            $data['rutaExcel'] ='download/detalleMatSAP/'.$archivo;
            $data['error'] = EXIT_SUCCESS;
          
        } catch (Exception $e) {
            $data['msj'] = 'Error interno, al crear archivo vale datos';
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function sendDataToSiom($idEECC, $idEstacion, $estacion_desc, $itemplan, $emplazamiento_id, $ptr){
        
        try{
            $codigo_siom = null;
            $idEEEC_post = ID_EECC_TELEFONICA_SIOM;//POR DEFECTO TDP
            if($idEECC  ==  ID_EECC_COBRA){
                $idEEEC_post    =   ID_EECC_COBRA_SIOM;
            }else if($idEECC  ==  ID_EECC_LARI){
                $idEEEC_post    =   ID_EECC_LARI_SIOM;
            }else if($idEECC  ==  ID_EECC_DOMINION){
                $idEEEC_post    =   ID_EECC_DOMINION_SIOM;
            }else if($idEECC  ==  ID_EECC_EZENTIS){
                $idEEEC_post    =   ID_EECC_EZENTIS_SIOM;
            }else if($idEECC  ==  ID_EECC_COMFICA){
                $idEEEC_post    =   ID_EECC_COMFICA_SIOM;
            }else if($idEECC  ==  ID_EECC_LITEYCA){
                $idEEEC_post    =   ID_EECC_LITEYCA_SIOM;
            }
            
            $idSubEspecialidad_post = null;
            if($idEstacion  ==  ID_ESTACION_FO || $idEstacion  ==  ID_ESTACION_FO_ALIM || $idEstacion  ==  ID_ESTACION_FO_DIST){
                $idSubEspecialidad_post     = ID_SUB_ESPECIALIDAD_FO_SIOM;
                $idFormulario               = ID_FORMULARIO_FO_SIOM;
            }else if($idEstacion  ==  ID_ESTACION_COAXIAL){
                $idSubEspecialidad_post     = ID_SUB_ESPECIALIDAD_COAXIAL_SIOM;
                $idFormulario               = ID_FORMULARIO_COAXIAL_SIOM;
            }else if($idEstacion  ==  ID_ESTACION_OC_FO){
                $idSubEspecialidad_post     = ID_SUB_ESPECIALIDAD_OBRA_CIVIL_SIOM;
                $idFormulario               = ID_FORMULARIO_OBRA_CIVIL_SIOM;
            }else if($idEstacion  ==  ID_ESTACION_OC_COAXIAL){
                $idSubEspecialidad_post     = ID_SUB_ESPECIALIDAD_OBRA_CIVIL_SIOM;
                $idFormulario               = ID_FORMULARIO_OBRA_CIVIL_SIOM;
            }else if($idEstacion  ==  ID_ESTACION_UM || $idEstacion ==  ID_ESTACION_AC_CLIENTE){
                $idSubEspecialidad_post     = ID_SUB_ESPECIALIDAD_ULTIMA_MILLA;
                $idFormulario               = ID_FORMULARIO_ULTIMA_MILLA;
            }else if($idEstacion  ==  ID_ESTACION_FUENTE){
                $idSubEspecialidad_post     = ID_SUB_ESPECIALIDAD_ENERGIA;
                $idFormulario               = ID_FORMULARIO_ENERGIA;
            }
            
            $dataSend = ['cont_id' 		        => ID_CONTRATO_TELFONICA_SIOM,//CODIGO DE CONTRATO = 21
                        'empl_id'               => $emplazamiento_id,//CODIGO DE NODO EN BASE A SU TABLA EMPLAZAMIENTO
                        'empr_id' 		        => $idEEEC_post,//EEECC 23 = LARI, 31 = DOMINION, 32 = COBRA, 33 = EZENTIS
                        'formularios' 	        => [$idFormulario],//idFormulario
                        'orse_descripcion' 	    => $itemplan.' '.$estacion_desc,//ITEMPLAN_ESTACION
                        'orse_fecha_creacion'   => $this->fechaActual(),//NOW
                        'orse_fecha_solicitud'	=> $this->fechaActual(),//NOW
                        'orse_indisponibilidad' => 'SI',//siempre si
                        'orse_tag' 		        => 111,//????
                        'orse_tipo' 		    => 'OSGN',//OSGN SIEMPRE
                        'sube_id' 		        => $idSubEspecialidad_post,//SUBESPACIALIDAD EN BASE A LA ESPACIALIDAD ESTACION
                        'usua_login_creador'    => 'WSPO2019',
                        'usua_pass_creador' 	=> 'WSPO2019' ];
            
            $dataLogSiom = array('data_send'     => json_encode($dataSend),
                                'ptr'           => $ptr,
                                'itemplan'      => $itemplan,
                                'usuario_envio' => $this->session->userdata('usernameSession'),
                                'fecha_envio'   => $this->fechaActual());
            
            //$url = 'http://3.215.20.37:8080/crearOS-1.0/api/v1/CrearOS';//QA
              $url = 'http://54.86.187.150:8080/crearOS-1.0/api/v1/CrearOS';//PRODUCCION
            $response = $this->m_utils->sendDataToURLTypePUT($url, json_encode($dataSend));
            log_message('error', 'siom:'.print_r($response, true));
            if($response->codigo == EXIT_SUCCESS){//SE CREO LA OS
                $codigo_siom = $response->orseid;
                $dataLogSiom['codigo']  =  $response->codigo;
                $dataLogSiom['mensaje'] =  $response->mensaje;
                $dataLogSiom['orseid']  =  $response->orseid; 
                $dataLogSiom['estado']  =  1;                
                $this->m_liquidacion_opex->insertLogTramaSiomSoloLog($dataLogSiom);
                log_message('error', 'TODO BIEN!');
            }else{//NO SE CREO LA OS
                $dataLogSiom['codigo']  =  $response->codigo;
                $dataLogSiom['mensaje'] =  $response->mensaje;
                $dataLogSiom['estado']  =  2;
                log_message('error', 'TODO MAL!');
                $this->m_liquidacion_opex->insertLogTramaSiomSoloLog($dataLogSiom);
            }
        }catch(Exception $e){//ERROR AL ACCEDER AL SERVIDOR
            log_message('error', 'ERROR EN EL SERVIDOR!!');
            $dataLogSiom['estado']  =  3;
            $this->m_liquidacion_opex->insertLogTramaSiomSoloLog($dataLogSiom);
        }
        return $codigo_siom;
    }
    
    function reenviarTramaSiom(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $ptr            = $this->input->post('ptr');
            $itemplan       = $this->input->post('itemplan');
            $nuevoIdCentral = $this->input->post('selectMDF');
            $id_siom_obra   = $this->input->post('id_siom_obra');
            $idEstacion     = $this->input->post('idEstacion');
            $estacionDesc   = $this->input->post('estacionDesc');
            
            $infoItemplan = $this->m_utils->getInfoItemplanLiquidacionSisegosWebPo($ptr, $itemplan);
            if($infoItemplan==null){                
                $infoItemplan = $this->m_utils->getInfoItemplanLiquidacionSisegos($ptr, $itemplan);
            }
            
            if($infoItemplan!=null){
                $emplazamiento =  $this->m_liquidacion_opex->getEmplazamientoIdSiomByidCentral($nuevoIdCentral);//OBTENEMOS EL ID DEZPLAZAMIENTO DE LA TABLA SIOM_NODOS POR EL ID CENTRAL DE LA PO
                if($emplazamiento['cant'] >= 1){// SE ENCONTRO NODO
                    $codigo_siom = $this->sendDataToSiom($infoItemplan['idEecc'], $idEstacion, $estacionDesc, $itemplan, $emplazamiento['empl_id'], $ptr);
                    //validar si no viene nulo
                    if($codigo_siom != null){
                        $dataSiom = array('itemplan'          => $itemplan,
                                        'idEstacion'        => $idEstacion,
                                        'ptr'               => $ptr,
                                        'fechaRegistro'     => $this->fechaActual(),
                                        'idUsuarioRegistro' => $this->session->userdata('idPersonaSession'),
                                        'codigoSiom'        => $codigo_siom,
                                        'ultimo_estado'       => 'CREADA',
                                        'fecha_ultimo_estado' => $this->fechaActual()
                        );
                
                        $dataLogPo = array( 'tabla'            => 'Siom',
                                            'actividad'        => 'Registrar Siom',
                                            'itemplan'         => $itemplan,
                                            'fecha_registro'   => $this->fechaActual(),
                                            'id_usuario'       => $this->session->userdata('idPersonaSession')
                                        );
                
                        $dataEstado = array('codigo_siom'           => $codigo_siom,
                                            'estado_desc'           => 'CREADA',
                                            'fechaRegistro'         => $this->fechaActual(),
                                            'usuario_registro'      => $this->session->userdata('usernameSession'),
                                            'estado_transaccion'    => 1
                                        );
                        $data = $this->m_liquidacion_opex->updateSiom($dataSiom, $dataLogPo, $dataEstado, $id_siom_obra);
                        $data['codigo_siom'] = $codigo_siom;
                    }else{
                        throw new Exception('No se recepciono un codigo siom');
                    }
                }else{
                    throw new Exception('No se encontro un nodo Valido');
                }
            }else{
                throw new Exception('No se encontraron Datos Itemplan - PO');
            }
      
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function nuevaOSTramaSiom(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $idSiom            = $this->input->post('id_siom');
            $idEstacion        = $this->input->post('id_estacion');
            $estacionDesc        = $this->m_utils->getEstaciondescByIdEstacion($idEstacion);
            $dataSiom = $this->m_utils->getSiomDataFromIdSiom($idSiom);
            if($dataSiom==null){
                throw new Exception('Error al obtener informacion Codigo Siom:'.$idSiom);
            }
            $ptr    = $dataSiom['ptr'];
            $itemP  = $dataSiom['itemplan'];
            $infoItemplan = $this->m_utils->getInfoItemplanLiquidacionSisegosWebPo($ptr, $itemP);
            if($infoItemplan==null){
                $infoItemplan = $this->m_utils->getInfoItemplanLiquidacionSisegos($ptr, $itemP);
            }
    
            if($infoItemplan!=null){
                $emplazamiento =  $this->m_liquidacion_opex->getEmplazamientoIdSiomByidCentral($infoItemplan['idCentral']);//OBTENEMOS EL ID DEZPLAZAMIENTO DE LA TABLA SIOM_NODOS POR EL ID CENTRAL DE LA PO
                if($emplazamiento['cant'] >= 1){// SE ENCONTRO NODO
                    $codigo_siom = $this->sendDataToSiom($infoItemplan['idEecc'], $idEstacion, $estacionDesc, $itemP, $emplazamiento['empl_id'], $ptr);
                    //$codigo_siom = 9876;
                    //validar si no viene nulo
                    if($codigo_siom != null){
                        $dataSiom = array('itemplan'          => $itemP,
                            'idEstacion'        => $idEstacion,
                            'ptr'               => $ptr,
                            'fechaRegistro'     => $this->fechaActual(),
                            'idUsuarioRegistro' => $this->session->userdata('idPersonaSession'),
                            'codigoSiom'        => $codigo_siom,
                            'ultimo_estado'       => 'CREADA',
                            'fecha_ultimo_estado' => $this->fechaActual()
                        );
                        
                        $dataLogPo = array( 'tabla'            => 'Siom',
                                            'actividad'        => 'Registrar Siom',
                                            'itemplan'         => $itemP,
                                            'fecha_registro'   => $this->fechaActual(),
                                            'id_usuario'       => $this->session->userdata('idPersonaSession')
                        );
                        
                        $dataEstado = array('codigo_siom'           => $codigo_siom,
                                            'estado_desc'           => 'CREADA',
                                            'fechaRegistro'         => $this->fechaActual(),
                                            'usuario_registro'      => $this->session->userdata('usernameSession'),
                                            'estado_transaccion'    => 1
                        );                       
                        $data = $this->m_liquidacion_opex->insertSiom($dataSiom, $dataLogPo, $dataEstado);
                        $data['codigo_siom'] = $codigo_siom;
                    }else{
                        log_message('error', 'No se recepciono un codigo siom');
                    }
                }else{
                    
                    $motivoError = 'No se encontro emplazamiento ID para ese nodo';
                    $estadoError = 4; //NODO NO ENCONTRADO = 4                                    
                    $dataLogSiom = array(
                        'ptr'           => $ptr,
                        'itemplan'      => $itemP,
                        'usuario_envio' => $this->session->userdata('usernameSession'),
                        'fecha_envio'   => $this->fechaActual());
                    $dataLogSiom['estado']  =  $estadoError;//NODO NO ENCONTRADO = 4
                    $dataLogSiom['mensaje']  =  $motivoError;
                    
                    $dataSiom = array('itemplan'          => $itemP,
                                    'idEstacion'        => $idEstacion,
                                    'ptr'               => $ptr,
                                    'fechaRegistro'     => $this->fechaActual(),
                                    'idUsuarioRegistro' => $this->session->userdata('idPersonaSession'),
                                    'codigoSiom'        => null,
                                    'ultimo_estado'       => $motivoError,
                                    'fecha_ultimo_estado' => $this->fechaActual()
                                );
                    $data = $this->m_liquidacion_opex->insertLogTramaSiom($dataLogSiom, $dataSiom);
                }
            }else{
                throw new Exception('No se encontraron Datos Itemplan - PO');
            }
    
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
        
    function reenviarNuevaUMForced($ptr, $itemP){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $infoItemplan = $this->m_utils->getInfoItemplanLiquidacionSisegosWebPo($ptr, $itemP);
            if($infoItemplan==null){
                $infoItemplan = $this->m_utils->getInfoItemplanLiquidacionSisegos($ptr, $itemP);
            }
    
            if($infoItemplan!=null){
                $emplazamiento =  $this->m_liquidacion_opex->getEmplazamientoIdSiomByidCentral($infoItemplan['idCentral']);//OBTENEMOS EL ID DEZPLAZAMIENTO DE LA TABLA SIOM_NODOS POR EL ID CENTRAL DE LA PO
                if($emplazamiento['cant'] >= 1){// SE ENCONTRO NODO
                    $codigo_siom = $this->sendDataToSiom($infoItemplan['idEecc'], 13, 'UM', $itemP, $emplazamiento['empl_id'], $ptr);
                    //$codigo_siom = 9876;
                    //validar si no viene nulo
                    if($codigo_siom != null){
                        $dataSiom = array('itemplan'          => $itemP,
                            'idEstacion'        => 13,
                            'ptr'               => $ptr,
                            'fechaRegistro'     => $this->fechaActual(),
                            'idUsuarioRegistro' => $this->session->userdata('idPersonaSession'),
                            'codigoSiom'        => $codigo_siom,
                            'ultimo_estado'       => 'CREADA',
                            'fecha_ultimo_estado' => $this->fechaActual()
                        );
    
                        $dataLogPo = array( 'tabla'            => 'Siom',
                            'actividad'        => 'Registrar Siom',
                            'itemplan'         => $itemP,
                            'fecha_registro'   => $this->fechaActual(),
                            'id_usuario'       => $this->session->userdata('idPersonaSession')
                        );
    
                        $dataEstado = array('codigo_siom'           => $codigo_siom,
                            'estado_desc'           => 'CREADA',
                            'fechaRegistro'         => $this->fechaActual(),
                            'usuario_registro'      => $this->session->userdata('usernameSession'),
                            'estado_transaccion'    => 1
                        );
                        $data = $this->m_liquidacion_opex->insertSiom($dataSiom, $dataLogPo, $dataEstado);
                        $data['codigo_siom'] = $codigo_siom;
                    }else{
                        log_message('error', 'No se recepciono un codigo siom');
                    }
                }else{
    
                    $motivoError = 'No se encontro emplazamiento ID para ese nodo';
                    $estadoError = 4; //NODO NO ENCONTRADO = 4
                    $dataLogSiom = array(
                        'ptr'           => $ptr,
                        'itemplan'      => $itemP,
                        'usuario_envio' => $this->session->userdata('usernameSession'),
                        'fecha_envio'   => $this->fechaActual());
                    $dataLogSiom['estado']  =  $estadoError;//NODO NO ENCONTRADO = 4
                    $dataLogSiom['mensaje']  =  $motivoError;
    
                    $dataSiom = array('itemplan'          => $itemP,
                        'idEstacion'        => 13,
                        'ptr'               => $ptr,
                        'fechaRegistro'     => $this->fechaActual(),
                        'idUsuarioRegistro' => $this->session->userdata('idPersonaSession'),
                        'codigoSiom'        => null,
                        'ultimo_estado'       => $motivoError,
                        'fecha_ultimo_estado' => $this->fechaActual()
                    );
                    $data = $this->m_liquidacion_opex->insertLogTramaSiom($dataLogSiom, $dataSiom);
                }
            }else{
                throw new Exception('No se encontraron Datos Itemplan - PO');
            }
    
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
    function enviarTramaIndividualPerzonalizada(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $ptr            = '2020-010600335';
            $itemplan       = '20-0120400007';
            //$nuevoIdCentral = $this->input->post('selectMDF');
            $id_siom_obra   = 5026;
            
            $infoItemplan = $this->m_utils->getInfoItemplanLiquidacionSisegosWebPo($ptr, $itemplan);
            if($infoItemplan==null){
                $infoItemplan = $this->m_utils->getInfoItemplanLiquidacionSisegos($ptr, $itemplan);
            }
            
            if($infoItemplan!=null){
                $emplazamiento =  $this->m_liquidacion_opex->getEmplazamientoIdSiomByidCentral($infoItemplan['idCentral']);//OBTENEMOS EL ID DEZPLAZAMIENTO DE LA TABLA SIOM_NODOS POR EL ID CENTRAL DE LA PO
                if($emplazamiento['cant'] >= 1){// SE ENCONTRO NODO
                   $codigo_siom = $this->sendDataToSiom($infoItemplan['idEecc'], $infoItemplan['idEstacion'], $infoItemplan['estacionDesc'], $itemplan, $emplazamiento['empl_id'], $ptr);
                 //$codigo_siom = 0;
                    //validar si no viene nulo
                    if($codigo_siom != null){
                        $dataSiom = array('itemplan'          => $itemplan,
                            'idEstacion'        => $infoItemplan['idEstacion'],
                            'ptr'               => $ptr,
                            'fechaRegistro'     => $this->fechaActual(),
                            'idUsuarioRegistro' => $this->session->userdata('idPersonaSession'),
                            'codigoSiom'        => $codigo_siom,
                            'ultimo_estado'       => 'CREADA',
                            'fecha_ultimo_estado' => $this->fechaActual()
                        );
            
                        $dataLogPo = array( 'tabla'            => 'Siom',
                            'actividad'        => 'Registrar Siom',
                            'itemplan'         => $itemplan,
                            'fecha_registro'   => $this->fechaActual(),
                            'id_usuario'       => $this->session->userdata('idPersonaSession')
                        );
            
                        $dataEstado = array('codigo_siom'           => $codigo_siom,
                            'estado_desc'           => 'CREADA',
                            'fechaRegistro'         => $this->fechaActual(),
                            'usuario_registro'      => $this->session->userdata('usernameSession'),
                            'estado_transaccion'    => 1
                        );
                        $data = $this->m_liquidacion_opex->updateSiom($dataSiom, $dataLogPo, $dataEstado, $id_siom_obra);
                        $data['codigo_siom'] = $codigo_siom;
                    }else{
                        throw new Exception('No se recepciono un codigo siom');
                    }
                }else{
                    throw new Exception('No se encontro un nodo Valido');
                }
            }else{
                throw new Exception('No se encontraron Datos Itemplan - PO');
            }
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
         echo json_encode(array_map('utf8_encode', $data));
    }
    
    function enviarTramasByItemplanPTR(){//$ptr, $itemP
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
           
            /*$listaPtrItem = $this->m_liquidacion_opex->getItemPtrSendSiom();
            $listaPtrItem = null;
            foreach($listaPtrItem->result() as $row){*/
                $ptr = '2020-031902635';
                $itemP = '20-0321500090';
                $infoItemplan = $this->m_utils->getInfoItemplanLiquidacionSisegosWebPo($ptr, $itemP);
                if($infoItemplan==null){
                    $infoItemplan = $this->m_utils->getInfoItemplanLiquidacionSisegos($ptr, $itemP);
                }
        
                if($infoItemplan!=null){
                    $emplazamiento =  $this->m_liquidacion_opex->getEmplazamientoIdSiomByidCentral($infoItemplan['idCentral']);//OBTENEMOS EL ID DEZPLAZAMIENTO DE LA TABLA SIOM_NODOS POR EL ID CENTRAL DE LA PO
                    if($emplazamiento['cant'] >= 1){// SE ENCONTRO NODO
                        $codigo_siom = $this->sendDataToSiom($infoItemplan['idEecc'], $infoItemplan['idEstacion'], $infoItemplan['estacionDesc'], $itemP, $emplazamiento['empl_id'], $ptr);
                        //$codigo_siom = 9876;
                        //validar si no viene nulo
                        if($codigo_siom != null){
                            $dataSiom = array('itemplan'          => $itemP,
                                'idEstacion'        => $infoItemplan['idEstacion'],
                                'ptr'               => $ptr,
                                'fechaRegistro'     => $this->fechaActual(),
                                'idUsuarioRegistro' => $this->session->userdata('idPersonaSession'),
                                'codigoSiom'        => $codigo_siom,
                                'ultimo_estado'       => 'CREADA',
                                'fecha_ultimo_estado' => $this->fechaActual()
                            );
        
                            $dataLogPo = array( 'tabla'            => 'Siom',
                                'actividad'        => 'Registrar Siom',
                                'itemplan'         => $itemP,
                                'fecha_registro'   => $this->fechaActual(),
                                'id_usuario'       => $this->session->userdata('idPersonaSession')
                            );
        
                            $dataEstado = array('codigo_siom'           => $codigo_siom,
                                'estado_desc'           => 'CREADA',
                                'fechaRegistro'         => $this->fechaActual(),
                                'usuario_registro'      => $this->session->userdata('usernameSession'),
                                'estado_transaccion'    => 1
                            );
                            $data = $this->m_liquidacion_opex->insertSiom($dataSiom, $dataLogPo, $dataEstado);
                            $data['codigo_siom'] = $codigo_siom;
                        }else{
                            log_message('error', 'No se recepciono un codigo siom');
                        }
                    }else{
        
                        $motivoError = 'No se encontro emplazamiento ID para ese nodo';
                        $estadoError = 4; //NODO NO ENCONTRADO = 4
                        $dataLogSiom = array(
                            'ptr'           => $ptr,
                            'itemplan'      => $itemP,
                            'usuario_envio' => $this->session->userdata('usernameSession'),
                            'fecha_envio'   => $this->fechaActual());
                        $dataLogSiom['estado']  =  $estadoError;//NODO NO ENCONTRADO = 4
                        $dataLogSiom['mensaje']  =  $motivoError;
        
                        $dataSiom = array('itemplan'          => $itemP,
                            'idEstacion'        => $infoItemplan['idEstacion'],
                            'ptr'               => $ptr,
                            'fechaRegistro'     => $this->fechaActual(),
                            'idUsuarioRegistro' => $this->session->userdata('idPersonaSession'),
                            'codigoSiom'        => null,
                            'ultimo_estado'       => $motivoError,
                            'fecha_ultimo_estado' => $this->fechaActual()
                        );
                        $data = $this->m_liquidacion_opex->insertLogTramaSiom($dataLogSiom, $dataSiom);
                    }
                }else{
                    throw new Exception('No se encontraron Datos Itemplan - PO');
                }
            //}
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
}