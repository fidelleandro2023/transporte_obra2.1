<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_registro_po_mo extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
		$this->load->model('mf_plantaInterna/M_detalle_planta_interna');
        $this->load->model('mf_detalle_obra/m_registro_po_mo'); 
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->library('excel');
        $this->load->helper('url');
    }
    
    public function index(){
        $logedUser = $this->session->userdata('usernameSession');
        if($logedUser != null){  
            $item = (isset($_GET['item']) ? $_GET['item'] : '');            
            $idEstacion = (isset($_GET['estacion']) ? $_GET['estacion'] : '');
            $estaciondesc = (isset($_GET['estaciondesc']) ? $_GET['estaciondesc'] : '');
            $est = (isset($_GET['from']) ? $_GET['from'] : '');
            //PONER VALIDACIONES
            $data['itemplan']       =   $item;
            $data['idEstacion']     =   $idEstacion;
            $data['estacionDesc']   =   $estaciondesc;
            $data['from']           =   $est;
            $data['nombreUsuario']  =   $this->session->userdata('usernameSession');
            $data['perfilUsuario']  =   $this->session->userdata('descPerfilSession');
            $permisos =  $this->session->userdata('permisosArbolTransporte');
            $result = $this->lib_utils->getHTMLPermisos($permisos, 54, ID_PERMISO_HIJO_PQT_CONSULTAS, ID_MODULO_PAQUETIZADO);
            $data['opciones'] = $result['html'];
            if($result['hasPermiso'] == true){
                $idProyecto = $this->m_utils->getIdProyectoByItemplan($item);
                if($idProyecto == ID_PROYECTO_OBRA_PUBLICA || $idProyecto == ID_PROYECTO_FTTH){
                    $this->load->view('vf_detalle_obra/v_registro_po_mo',$data);                    
                }else{
                    $hasPoMoActive = $this->m_utils->hasPtrMoActive($item, $idEstacion);
                    if($hasPoMoActive == 0){
                        $this->load->view('vf_detalle_obra/v_registro_po_mo',$data);
                    }else{
                        redirect('login','refresh');
                    }
                }
            }else{
                redirect('login','refresh');
            }
        }else{
            redirect('login','refresh');
        }             
    }
    
    
    public function getExcelPartidasMO()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $itemplan   = $this->input->post('itemplan'); 
            $idEstacion = $this->input->post('idEstacion');
    
            $arrayMateriales = $this->m_registro_po_mo->getPartidasByProyectoEstacion($itemplan, $idEstacion);			
            if (is_array($arrayMateriales)) {
    
                if (count($arrayMateriales) > 0) {
                    ini_set('max_execution_time', 10000);
                    ini_set('memory_limit', '2048M');
    
                    $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
                    $cacheSettings = array('memoryCacheSize ' => '5000MB', 'cacheTime' => '1000');
                    PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
    
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle('ValeDatos');
                    $contador = 1;
                    $titulosColumnas = array('CODIGO', 'PARTIDA', 'CONTRATO', 'COSTO', 'BAREMO', 'CANTIDAD INGRESADA');
    
                    $this->excel->setActiveSheetIndex(0);
    
                    // Se agregan los titulos del reporte
                    $this->excel->setActiveSheetIndex(0)
                    ->setCellValue('A1', utf8_encode($titulosColumnas[0]))
                    ->setCellValue('B1', utf8_encode($titulosColumnas[1]))
                    ->setCellValue('C1', utf8_encode($titulosColumnas[2]))
                    ->setCellValue('D1', utf8_encode($titulosColumnas[3]))
                    ->setCellValue('E1', utf8_encode($titulosColumnas[4]))
                    ->setCellValue('F1', utf8_encode($titulosColumnas[5]));
    
                    foreach ($arrayMateriales as $row) {
                        
                         $costoEnd = $row->costo;
                        /***************************NUEVO PEDIDO OWEN 06.06.2019***************************/
						/*comentado 04.06.2020 usara costo empalmador coaxial 
                        if(trim($row->codigo) == '23108-8'){//log_message('error', 'ES IGUAL...');
                            $newCosto = $this->m_utils->getCostoFoPartidasByItemplan($itemplan);
                            if($newCosto!=null){
                                $costoEnd = $newCosto['costo'];
                            }
                        }*/
                        /******************************************************/
                        $contador++;
                        $this->excel->getActiveSheet()->setCellValue("A{$contador}", $row->codigo)
                        ->setCellValue("B{$contador}", $row->descripcion)
                        ->setCellValue("C{$contador}", $row->descPrecio)
                        ->setCellValue("D{$contador}", $costoEnd)
                        ->setCellValue("E{$contador}", $row->baremo);
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
                    $archivo = "partidas_mo_registro_individual_po.xls";
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="' . $archivo . '"');
                    header('Cache-Control: max-age=0');
                    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                    //Hacemos una salida al navegador con el archivo Excel.
                    $objWriter->save(PATH_FILE_UPLOAD_DETALLE_MATPO);
    
                    $data['rutaExcel'] = PATH_FILE_UPLOAD_DETALLE_MATPO;
                    $data['error'] = EXIT_SUCCESS;
    
     
                }else{
                    $data['msj'] = "No hay partidas configuradas para esta estaci&oacute;n !!, porfavor conmunicarse con dise&ntilde;o";
                }
            } else {
                $data['msj'] = "No hay partidas configuradas para esta estaci&oacute;n !!, porfavor conmunicarse con dise&ntilde;o";
            }
        } catch (Exception $e) {
            $data['msj'] = 'Error interno, al crear archivo partidas MO';
        }
        // return $data;
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    
    
    
    public function uploadPOMO(){
        $data ['error']= EXIT_ERROR;
        $data['msj'] = null;
        try{
            $itemplan   = $this->input->post('item'); 
            $idEstacion = $this->input->post('idEstacion');
            
            $uploaddir =  'uploads/po_mo/';//ruta final del file
            $uploadfile = $uploaddir . basename($_FILES['file']['name']);
            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {

                $objPHPExcel = PHPExcel_IOFactory::load($uploadfile);

                $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
                $row_dimension = $objPHPExcel->getActiveSheet()->getHighestRowAndColumn();
                
                $info_2 = $this->makeHTMLBodyTable($row_dimension, $objPHPExcel, $itemplan, $idEstacion);
                $data['tablaData'] = $info_2['html'];
                $data['jsonDataFIleValido'] = json_encode($info_2['array']);
                $data['jsonDataFIle'] = json_encode($info_2['array_full']);
                $this->session->set_userdata('sirope_NF',$info_2['array_nf']);
                $data['total_final'] = $info_2['total_final'];
               # $data['total_no_format'] = $info_2['total_no_format'];
                $data['error'] = EXIT_SUCCESS;
    
               
            } else {
                throw new Exception('Hubo un problema con la carga del archivo al servidor, comuniquese con el administrador.');
            }
    
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function makeHTMLBodyTable($row_dimension, $objPHPExcel, $itemplan, $idEstacion){
        $data['html'] = '';
        $data['array'] = '';
        $data['array_full'] = '';
        $html = '';
        $indice = 0;
        $cont_indice_valido = 0;        
        $array_valido = array();
        $array_not_found = array();
        $indice_valido = '';
        $array_full = array();
        $total_final = 0;
        for ($i = 1; $i <= $row_dimension['row']; $i++){//COMIENZA DESDE LA FILA 1
            $A = (string)($objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0,$i,true)->getValue());
            $B = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1,$i,true)->getValue();
            $C = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2,$i,true)->getValue();
            $D = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(3,$i,true)->getValue();
            $E = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(4,$i,true)->getValue();
            $F = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(5,$i,true)->getValue();
            $total = 0;
            if($F!='' && $A!='CODIGO'){
                if(is_numeric($F)){
                    $infoPartida    =   $this->m_registro_po_mo->getInfoCodigoMaterial($itemplan, $idEstacion, $A, $D);
                    if($infoPartida!=null){
                         $D = $infoPartida['costo'];
                        /***********************************************/
                        /*NUEVO SE FORZA COSTO F.O PARA PARTIDA PEDIDO OWEN 05.06.2019**/
						//SE COMENTO EL 04.06.2020 
                        /*if(trim($A) == '23108-8'){//log_message('error', 'ES IGUAL...');
                            $newCosto = $this->m_utils->getCostoFoPartidasByItemplan($itemplan);
                            if($newCosto!=null){
                                $D = $newCosto['costo'];
                            }
                        }*/
                        /***********************************************/
                        $color_row = "white";
                        $situacion = 'OK';

                        $E = $infoPartida['baremo'];
                        $total = ($D*$E*$F);
                        $pre_array = array($A, $B, $C, $D, $E, $F,$infoPartida['idActividad'],$total, 1); //1 == insert
                        array_push($array_valido, $pre_array);
                        $indice_valido = 'data-indice_val="'.$cont_indice_valido.'"';
                        $cont_indice_valido++;       
                        $total_final = $total_final + $total;
                    }else{
                        $color_row = "#b3b37b";
                        $situacion = 'PARTIDA NO PERMITIDA';
                        $pre_array = array($A, $B, $C, $D, $E, $F, 2); //2 == erroneos
                        array_push($array_not_found, $pre_array);
                        log_message('error', 'Partida no asociada la proyecto - estacion'.$A);
                    }
                    
                }else{
                    $color_row = "#b3b37b";
                    $situacion = 'CANTIDAD INGRESADA INVALIDAD';
                    $pre_array = array($A, $B, $C, $D, $E, $F, 2); //2 == erroneos
                    array_push($array_not_found, $pre_array);
                    log_message('error', 'Partida no asociada la proyecto - estacion'.$A);
                    log_message('error', 'no numerico'.$A);
                }      
                
                $html .= '<tr id="tr'.$indice.'" style="background-color:'.$color_row.'">
                            <th style="width: 5px;"><a style="cursor:pointer;" '.$indice_valido.' data-indice="'.$indice.'" onclick="removeTR(this)"><img class="delete_ptr" alt="Eliminar" height="20px" width="20px" src="public/img/iconos/delete.png"></a></th>
                        	<th style="color:black">'.$A.'</th>
                        	<th style="color:black">'.utf8_decode($B).'</th>
                        	<th style="color:black">'.$C.'</th>
                    	    <th style="color:black">'.$D.'</th>
                	        <th style="color:black">'.$E.'</th>
            	            <th style="color:black">'.$F.'</th>
        	                <th style="color:black">'.number_format($total,2,'.', ',').'</th>
        	                <th style="color:black">'.$situacion.'</th>
                    	</tr>';
                $indice++;
                
                $pre_array = array($A, $B, $C, $D, $E, $F);
                array_push($array_full, $pre_array);
            }
            
        }        
        
        $data['html']           = $html;
        $data['array']          = $array_valido;
        $data['array_nf']       = $array_not_found;
        $data['array_full']     = $array_full;
        log_message('error', '$total_final:'.$total_final);
        #$data['total_no_format']= $total_final;
        #$data['total_final']    = number_format($total_final,2,'.', ',');
        $data['total_final']    = $total_final;
        
        return $data;
    }
    
    
    public function savePoMo(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;
            if($idUsuario   !=     null){
                $itemplan       = $this->input->post('itemplan');
                $idEstacion     = $this->input->post('idEstacion');
                $jsonDataFile   = $this->input->post('jsonDataFile');
                $from           = $this->input->post('from');
                $arrayFile = json_decode($jsonDataFile);
                $arrayFinalUpdate = array();
                $arrayFinalInsert = array();
				
				$has_ptr = $this->M_detalle_planta_interna->getCountPtrPlantaInterna($itemplan, $idEstacion, 'MO');
                if($has_ptr > 0){
                    throw new Exception('el itemplan ya cuenta con una po!!');
                }

                if($arrayFile!=null){
                    $codigoPO = $this->m_utils->getCodigoPO($itemplan);
                    if ($codigoPO == null) {
                        throw new Exception('Hubo un error al generar el codigo PO ');
                    }
					
                    $costoTotalPOMO = 0;
                    $arrayActividades = array();
                    foreach($arrayFile as $datos){
                        if($datos!=null){
                            if($datos[8]!=null){
                                if($datos[8]    ==  1){//registrar
                                    if(!in_array($datos[6], $arrayActividades)){
                                        $dataCMO = array();
                                        $dataCMO['ptr']              = $codigoPO;
										$dataCMO['itemplan']         = $itemplan;
                                        $dataCMO['id_actividad']      = $datos[6];
                                        $dataCMO['baremo']           = $datos[4];
                                        $dataCMO['precio']            = $datos[3];
                                        $dataCMO['cantidad'] 		 = $datos[5];
                                        $dataCMO['cantidad_final']   = $datos[5];
                                        $dataCMO['costo_mo']         = $datos[7];
										$dataCMO['total']            = $datos[7];
										$dataCMO['descripcion']    = $datos[1];
										
                                        array_push($arrayFinalInsert, $dataCMO);
                                        $costoTotalPOMO = $costoTotalPOMO + $dataCMO['total'];
                                        array_push($arrayActividades, $datos[6]);//metemos idActividad
                                    }
                                }
                            }
                        }
                    }
                    
                    $idEecc = 0;
                    $infoEECC = $this->m_registro_po_mo->getEeccDisenoOperaByItemPlan($itemplan);
                    if($infoEECC!=null) {
                        if($infoEECC['jefatura'] == 'LIMA' && $idEstacion == 4) {
                            $idEecc = $infoEECC['idEmpresaColabFuente'];
                        } else { 
                            if($from==1){
                                if($infoEECC['idEmpresaColab']!=null){
                                    $idEecc = $infoEECC['idEmpresaColab'];
                                }else{
                                    throw new Exception('No se detecto EECC asociada a la obra, comuniquese con soporte CAP');
                                }
                            }else if($from==2){
                                if($infoEECC['idEmpresaColabDiseno']!=null){
                                    $idEecc = $infoEECC['idEmpresaColabDiseno'];
                                }else{
                                    $idEecc = $infoEECC['idEmpresaColab'];
                                }                     
                            }
                        }
                    }
					
					$subProyectoEstacion = $this->m_registro_po_mo->getSubProyectoEstacionByItemplanEstacion($itemplan, $idEstacion);
					
					if($subProyectoEstacion ==  null){
                        throw new Exception('Hubo un error obtener el subproyecto - estacion');
                    }  
					
                    $dataPO = array(
                        'itemplan'      => $itemplan,
                        'ptr'           => $codigoPO,
                        'rangoPtr'      => PO_REGISTRADO, //ESTADO REGISTRADO
                        'usua_crea'     => $idUsuario,
                        'fecha_crea' => $this->fechaActual(),
						"idSubProyectoEstacion" => $subProyectoEstacion
                    );
					

                    $dataLogPO = array(
                        'codigo_po'         =>  $codigoPO,
                        'itemplan'          =>  $itemplan,
                        'idUsuario'         =>  $idUsuario,
                        'fecha_registro'    =>  $this->fechaActual(),
                        'idPoestado'        =>  PO_REGISTRADO
                    );
                    //log_message('error', print_r($dataDetalleplan, true));
					$countPo = $this->m_utils->getCountPoMasDesp($itemplan);
					
					if($countPo > 1) {
						throw new Exception('Ya cuenta con una PO.');
					}
					
                    $data = $this->m_registro_po_mo->createPoMOPin($dataPO, $dataLogPO, $arrayFinalInsert);
                    if($data['error']   ==  EXIT_ERROR){
                        throw new Exception('Hubo un error interno, por favor volver a intentar.');
                    }
					
					$idEstadoPlan = $this->m_utils->getEstadoPlanByItemplan($itemplan);
					if($idEstadoPlan == ESTADO_PLAN_PRE_DISENO) {
                        $data = $this->m_utils->updateEstadoPlanObra($itemplan, ID_ESTADO_EN_APROBACION);
						
						if($data['error'] == EXIT_ERROR){
							throw new Exception($data['msj']);
						}
                     }
					
					$arrayDataItem = array(
						'costo_unitario_mo_crea_oc' => $costoTotalPOMO,
						'costo_unitario_mo'         => $costoTotalPOMO
					);
					$data = $this->m_utils->simpleUpdatePlanObra($itemplan, $arrayDataItem);
                    $data['codigoPO']    =   $codigoPO;
                    $data['error']       = EXIT_SUCCESS;
                }else{
                    throw new Exception('No se pudo procesar el archivo, refresque la pagina y vuelva a intentarlo.');
                }              
            }else{
                throw new Exception('Su sesion expiro, porfavor vuelva a logearse.');
            }
                 
        }catch(Exception $e){
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
    function generarExcelErrores(){
        $listNF = $this->session->userdata('sirope_NF');
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('test worksheet');
        //set cell A1 content with some text
       $i = 1;
        foreach($listNF as $row){
            $this->excel->getActiveSheet()->setCellValue('A'.$i, $row[0]);
            $this->excel->getActiveSheet()->setCellValue('B'.$i, $row[1]);
            $this->excel->getActiveSheet()->setCellValue('C'.$i, $row[2]);
            $this->excel->getActiveSheet()->setCellValue('D'.$i, $row[3]);
            $this->excel->getActiveSheet()->setCellValue('E'.$i, $row[4]);
            $this->excel->getActiveSheet()->setCellValue('F'.$i, $row[5]);
            $this->excel->getActiveSheet()->setCellValue('G'.$i, $row[6]);
            $this->excel->getActiveSheet()->setCellValue('H'.$i, $row[7]);
            $this->excel->getActiveSheet()->setCellValue('I'.$i, $row[8]);
            $this->excel->getActiveSheet()->setCellValue('J'.$i, $row[9]);
            $this->excel->getActiveSheet()->setCellValue('K'.$i, $row[10]);
            $this->excel->getActiveSheet()->setCellValue('L'.$i, $row[11]);
            $i++;
        }
        $filename='just_some_random_name.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
    }
    
    /**************************************/
    
    public function registrarPoDisenoManual(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $listaPOCreados = array();
            $idUsuario = 4;//jose aranda
            if($idUsuario   !=     null){
                
                $listaItems = array();
                foreach($listaItems as $itm){ 
                    $itemplan       = $itm;
                    $idEstacion     = 1;//DISENO
                    $jsonDataFile   = $this->input->post('jsonDataFile');
                    $from           = 2;//DISENO
                    //   $arrayFile = json_decode($jsonDataFile);
                    $arrayFinalUpdate = array();
                    $arrayFinalInsert = array();
                //    log_message('error', print_r('1.$idEstacion:'.$idEstacion, true));
            //     log_message('error', print_r('aqui', true));
                    
                    //      if($arrayFile!=null){
          //          log_message('error', print_r('aqui2', true));
                    $codigoPO = $this->m_utils->getCodigoPO($itemplan);
                    if ($codigoPO == null) {
                        throw new Exception('Hubo un error al generar el codigo PO ');
                    }
               //     log_message('error', print_r('aqui3', true));
                    $costoTotalPOMO = 0;
                    $idEecc = 0;
                    
                    $infoEECC = $this->m_registro_po_mo->getEeccDisenoOperaByItemPlan($itemplan);
                    if($infoEECC!=null) {
                        if($infoEECC['jefatura'] == 'LIMA' && $idEstacion == 4) {
                            $idEecc = $infoEECC['idEmpresaColabFuente'];
                        } else {
                            if($from==1){
                                if($infoEECC['idEmpresaColab']!=null){
                                    $idEecc = $infoEECC['idEmpresaColab'];
                                }
                            }else if($from==2){
                                if($infoEECC['idEmpresaColabDiseno']!=null){
                                    $idEecc = $infoEECC['idEmpresaColabDiseno'];
                                }
                            }
                        }
                    }
                    $infoPartida    =   $this->m_registro_po_mo->getPartidasByProyectoEstacionPODiseno($itemplan, ID_ESTACION_DISENIO);
                    $dataCMO = array();
                    $dataCMO['codigo_po']        = $codigoPO;
                    $dataCMO['idPartida']        = 441;//PARTIDA COTIZACION
                    $dataCMO['idPrecioDiseno']   = 1;
                    $dataCMO['idEmpresaColab']   = $idEecc;
                    $dataCMO['idZonal']          = $infoEECC['idZonal'];
                    $dataCMO['cantidad']         = 1;
                    $dataCMO['baremo']           = $infoPartida['baremo'];
                    $dataCMO['costo']            = $infoPartida['costo'];
                    $dataCMO['total']            = ($infoPartida['baremo']*$infoPartida['costo']);
                    array_push($arrayFinalInsert, $dataCMO);
                    $costoTotalPOMO = $costoTotalPOMO + $dataCMO['total'];
                    
                   // log_message('error', print_r('2.$idEstacion:'.$idEstacion, true));
                    $dataPO = array(
                        'itemplan'      => $itemplan,
                        'codigo_po'     => $codigoPO,
                        'estado_po'     => PO_REGISTRADO, //ESTADO REGISTRADO
                        'idEstacion'    => $idEstacion,
                        'from'          => $from,
                        'costo_total'   => $costoTotalPOMO,
                        'idUsuario'     => $idUsuario,
                        'fechaRegistro' => $this->fechaActual(),
                        'estado_asig_grafo' => 0,
                        'flg_tipo_area' => 2,//MANO DE OBRA
                        'id_eecc_reg'   => $idEecc
                    );
                   // log_message('error', print_r('3.$idEstacion:'.$idEstacion, true));
                    $dataLogPO = array(
                        'codigo_po'         =>  $codigoPO,
                        'itemplan'          =>  $itemplan,
                        'idUsuario'         =>  $idUsuario,
                        'fecha_registro'    =>  $this->fechaActual(),
                        'idPoestado'        =>  PO_REGISTRADO,
                        'controlador'       =>  (($from ==  1) ? 'consulta' : 'diseno')
                    );
                    
                    $subProyectoEstacion = $this->m_registro_po_mo->getSubProyectoEstacionByItemplanEstacionCotizacion($itemplan, $idEstacion);
                  //  log_message('error', print_r('4.$idEstacion:'.$idEstacion, true));
                    if($subProyectoEstacion ==  null){
                        throw new Exception('Hubo un error obtener el subproyecto - estacion');
                    }
                    $dataDetalleplan = array('itemPlan' =>  $itemplan,
                        'poCod'    => $codigoPO,
                        'idSubProyectoEstacion' =>  $subProyectoEstacion);
                    
                    //log_message('error', print_r($dataDetalleplan, true));
                    $data = $this->m_registro_po_mo->createPoMODiseno($dataPO, $dataLogPO, $dataDetalleplan, $arrayFinalInsert);
                    if($data['error']   ==  EXIT_ERROR){
                        throw new Exception('Hubo un error interno, por favor volver a intentar.');
                    }
                    $data['codigoPO']    =   $codigoPO;
                //    array_push($listaPOCreados, $codigoPO.'|'.$itm);
                    $data['error']       = EXIT_SUCCESS;     
                    log_message('error', $codigoPO.'|'.$itm);
                }
                
              //  log_message('error', print_r($listaPOCreados, true));
            }else{
                throw new Exception('Su sesion expiro, porfavor vuelva a logearse.');
            }
             
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
	
	
	
	public function registrarPoMoManual($itemplan, $idEstacion, $idUsuario, $fechaActual){
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
		
        try{
            if($idUsuario != null){
				
                $from = 1;
                $arrayFinalUpdate = array();
                $arrayFinalInsert = array();
				
				$has_ptr = $this->M_detalle_planta_interna->getCountPtrPlantaInterna($itemplan, $idEstacion, 'MO');
                if($has_ptr > 0){
                    throw new Exception('el itemplan ya cuenta con una po!!');
                }

				$codigoPO = $this->m_utils->getCodigoPO($itemplan);
				if ($codigoPO == null) {
					throw new Exception('Hubo un error al generar el codigo PO ');
				}
				
				$costoTotalPOMO = 0;
				$arrayDetallePo = $this->m_registro_po_mo->getDetallePoManual($itemplan);
				foreach($arrayDetallePo as $datos){
					
					$dataCMO = array();
					$dataCMO['ptr']              = $codigoPO;
					$dataCMO['itemplan']         = $itemplan;
					$dataCMO['id_actividad']     = $datos['id_partida'];
					$dataCMO['baremo']           = $datos['baremo'];
					$dataCMO['precio']           = $datos['costo'];
					$dataCMO['cantidad'] 		 = $datos['cantidad'];
					$dataCMO['cantidad_final']   = $datos['cantidad'];
					$dataCMO['costo_mo']         = $datos['total'];
					$dataCMO['total']            = $datos['total'];
					$dataCMO['descripcion']      = $datos['descripcion'];
					
					array_push($arrayFinalInsert, $dataCMO);
					$costoTotalPOMO = $costoTotalPOMO + $dataCMO['total'];
				}
				
				$idEecc = 0;
				$infoEECC = $this->m_registro_po_mo->getEeccDisenoOperaByItemPlan($itemplan);
				if($infoEECC != null) {
					if($infoEECC['jefatura'] == 'LIMA' && $idEstacion == 4) {
						$idEecc = $infoEECC['idEmpresaColabFuente'];
					} else { 
						if($from == 1){
							if($infoEECC['idEmpresaColab']!=null){
								$idEecc = $infoEECC['idEmpresaColab'];
							}else{
								throw new Exception('No se detecto EECC asociada a la obra, comuniquese con soporte CAP');
							}
						}else if($from == 2){
							if($infoEECC['idEmpresaColabDiseno']!=null){
								$idEecc = $infoEECC['idEmpresaColabDiseno'];
							}else{
								$idEecc = $infoEECC['idEmpresaColab'];
							}                     
						}
					}
				}
				
				$subProyectoEstacion = $this->m_registro_po_mo->getSubProyectoEstacionByItemplanEstacion($itemplan, $idEstacion);
				if($subProyectoEstacion ==  null){
					throw new Exception('Hubo un error obtener el subproyecto - estacion');
				}  
				
				$dataPO = array(
					'itemplan'      => $itemplan,
					'ptr'           => $codigoPO,
					'rangoPtr'      => PO_REGISTRADO, //ESTADO REGISTRADO
					'usua_crea'     => $idUsuario,
					'fecha_crea' => $fechaActual,
					"idSubProyectoEstacion" => $subProyectoEstacion
				);
				

				$dataLogPO = array(
					'codigo_po'         =>  $codigoPO,
					'itemplan'          =>  $itemplan,
					'idUsuario'         =>  $idUsuario,
					'fecha_registro'    =>  $fechaActual,
					'idPoestado'        =>  PO_REGISTRADO
				);
				
				$data = $this->m_registro_po_mo->createPoMOPin($dataPO, $dataLogPO, $arrayFinalInsert);
				if($data['error']   ==  EXIT_ERROR){
					throw new Exception('Hubo un error interno, por favor volver a intentar.');
				}
				
				$arrayDataItem = array(
					'costo_unitario_mo_crea_oc' => $costoTotalPOMO
				);
				$data = $this->m_utils->simpleUpdatePlanObra($itemplan, $arrayDataItem);
				$data['codigoPO'] = $codigoPO;
				$data['error'] = EXIT_SUCCESS;
				
				$arrayUpdatePo = array(
					"estado"              => '02 - VALORIZADA CON VALE DE RESERVA',
	                "ultimo_estado"       => '01 - APROBADA VALORIZADA',
	                "fecha_ultimo_estado" => $fechaActual,
	                "usua_ultimo_estado"  => 'rpalza',
	                "fecha_aprob"         => $fechaActual,
	                "usua_aprob"          => 'rpalza',
	                "rangoPtr"            => 2,
	                "flg_rechazado"       => 0
				);
				
				$data = $this->m_registro_po_mo->updatePoMo($itemplan, $codigoPO, $arrayUpdatePo);
				
            
            }else{
                throw new Exception('Su sesion expiro, porfavor vuelva a logearse.');
            }
                 
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }
	
	public function createPoMOManualSBE(){
		 	 
        $itemplanList = array('23-6620500003','23-1420500002','23-9920500001','23-5320500002','23-8020500003','23-1220500002','23-5920500003','23-1320300003','23-7720300001','23-8520300001','23-5920300002','23-5720300003','23-9420500007','23-1620500001','23-6420500003','23-3720500001','23-3320500005','23-7620500002','23-5220500001','23-5823700001','23-5627200001','23-5221700002','23-8221200001','23-3229300001','23-5527100001','23-4320500001','23-1720500001','23-9920500002','23-3120500001','23-2424000001','23-1425000001','23-7028100001','23-6029900001','23-9428600001','23-6025600001','23-5621700001','23-7020300002','23-2321300001','23-7720500002','23-5620500004','23-5024900001','23-3129500001','23-7926900001','23-8029200001','23-7925500001','23-3021400001','23-6329200001','23-1423800001','23-3726900002','23-8522000001','23-9622500001','23-9720600002','23-7720600002','23-2520600001','23-1820600002','23-2720600001','23-4820600002','23-7920500003');
		$fechaActual = $this->fechaActual();
		$idUsuario = 2341;
		
		foreach ($itemplanList as $item){//por cada itemplan generarle su po mo
			$infoEstacion = $this->m_registro_po_mo->getInfoEstacionByItemplan($item);
			$this->registrarPoMoManual($item, $infoEstacion['idEstacion'], $idUsuario, $fechaActual);																			
		}
    }
}