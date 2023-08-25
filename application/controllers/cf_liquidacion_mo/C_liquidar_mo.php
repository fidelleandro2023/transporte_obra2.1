<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_liquidar_mo extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_liquidacion_mo/m_liquidar_mo'); 
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->library('excel');
        $this->load->helper('url');
    }
    
    public function index(){
        $logedUser = $this->session->userdata('usernameSession');
        if($logedUser != null){        
            
            $item           = (isset($_GET['item']) ? $_GET['item'] : '');            
            $idEstacion     = (isset($_GET['estacion']) ? $_GET['estacion'] : '');
            $estaciondesc   = (isset($_GET['estaciondesc']) ? $_GET['estaciondesc'] : '');
            $est            = (isset($_GET['from']) ? $_GET['from'] : '');
            $ptr            = (isset($_GET['poCod']) ? $_GET['poCod'] : '');
            $canEdit = false;
            $estadoPo = $this->m_liquidar_mo->gestEstadoPoByItemplanPoCod($item, $ptr);
            $totalSol = $this->m_liquidar_mo->getCostoSolicitudMo($ptr);
            if($estadoPo    ==  PO_LIQUIDADO){
                $infoExpedientes = $this->m_utils->getExpedientesInfo($item, $idEstacion);   
                if($infoExpedientes['has_activo'] == 0 && $infoExpedientes['has_devuelto'] > 0){
                    $canEdit = true;
                }
            }
            if($estadoPo    ==  PO_REGISTRADO || $canEdit){
                //PONER VALIDACIONES
				$data['totalSolEx']     = 	$totalSol;
                $data['idEstadoPo']     =   $estadoPo;
                $data['itemplan']       =   $item;
                $data['idEstacion']     =   $idEstacion;
                $data['estacionDesc']   =   $estaciondesc;
                $data['from']           =   $est;
                $data['codigo_po']      =   $ptr;
				$data['countPasadas']   =   $this->m_liquidar_mo->getCountObrasPasadas($ptr);
				$data['fase']           =   $this->m_utils->getFaseByItemplan($item);
                $datoPartidas           =   $this->makeHTMLTableBasic($this->m_liquidar_mo->getPartidasBasicByPtr($ptr));
                $data['contTableBasic'] =   $datoPartidas['html'];
                $data['costo_total']    =   $datoPartidas['costo_total'];
                $data['nombreUsuario']  =   $this->session->userdata('usernameSession');
                $data['perfilUsuario']  =   $this->session->userdata('descPerfilSession');
                $permisos =  $this->session->userdata('permisosArbol');
                $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_REPORTES_V, ID_PERMISO_HIJO_DETALLE_OBRA);
                $data['opciones'] = $result['html'];
                if($result['hasPermiso'] == true){
                    $this->load->view('vf_liquidacion_mo/v_liquidar_mo',$data);
                }else{
                    redirect('login','refresh');
                }
            }else{
                redirect('login','refresh');
            }
            
        }else{
            redirect('login','refresh');
        }             
    }
    
    public function makeHTMLTableBasic($arrayData){
        $html = '';
        $costo_total = 0;
            if($arrayData!=null){
                foreach($arrayData as $row){
            $html .= '<tr>
                            <th style="width: 5px;"></th>
                        	<th >'.$row->codigo.'</th>
                        	<th >'.utf8_decode($row->descripcion).'</th>
                        	<th >'.$row->descPrecio.'</th>
                    	    <th >'.$row->costo.'</th>
                	        <th style="text-align: center;">'.$row->baremo.'</th>
            	            <th style="text-align: center;">'.$row->cantidad_inicial.'</th>
        	                <th style="text-align: center;">'.$row->cantidad_final.'</th>
        	                <th style="text-align: right;">'.number_format($row->monto_final,2,'.', ',').'</th>
        	                <th >--</th>
                    	</tr>';
                    $costo_total = $costo_total + $row->monto_final;
                }                
            }      
            $data['html']   =   $html;
            $data['costo_total']    =   $costo_total;
        return $data;        
    }
    
    public function getExcelPartidasMO()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $itemplan   = $this->input->post('itemplan'); 
            $idEstacion = $this->input->post('idEstacion');
            $codigo_po  = $this->input->post('codigo_po');
            $arrayMateriales = $this->m_liquidar_mo->getPartidasByProyectoEstacion($codigo_po, $itemplan, $idEstacion);
    
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
                    $titulosColumnas = array('CODIGO', 'PARTIDA', 'TIPO', 'COSTO', 'BAREMO', 'CANTIDAD INGRESADA');
    
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
                        $contador++;
                        $costoEnd = $row->costo;
                        /***************************NUEVO PEDIDO OWEN 06.06.2019***************************/
                        /*if(trim($row->codigo) == '23108-8'){//log_message('error', 'ES IGUAL...');
                            $newCosto = $this->m_utils->getCostoFoPartidasByItemplan($itemplan);
                            if($newCosto!=null){
                                $costoEnd = $newCosto['costo'];
                            }
                        }*/
                        /******************************************************/
                        $this->excel->getActiveSheet()->setCellValue("A{$contador}", $row->codigo)
                        ->setCellValue("B{$contador}", $row->descripcion)
                        ->setCellValue("C{$contador}", $row->descPrecio)
                        ->setCellValue("D{$contador}", $costoEnd)
                        ->setCellValue("E{$contador}", $row->baremo)
                        ->setCellValue("F{$contador}", $row->cantidad_inicial);
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
                    $archivo = "modelo_carga_registro_individual_po.xls";
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="' . $archivo . '"');
                    header('Cache-Control: max-age=0');
                    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                    //Hacemos una salida al navegador con el archivo Excel.
                    $objWriter->save(PATH_FILE_UPLOAD_DETALLE_MATPO);
    
                    $data['rutaExcel'] = PATH_FILE_UPLOAD_DETALLE_MATPO;
                    $data['error'] = EXIT_SUCCESS;
    
     
                }else{
                    $data['msj'] = "No hay kit de materiales para esta estaci&oacute;n !!, porfavor conmunicarse con dise&ntilde;o";
                }
            } else {
                $data['msj'] = "No hay kit de materiales para esta estaci&oacute;n !!, porfavor conmunicarse con dise&ntilde;o";
            }
        } catch (Exception $e) {
            $data['msj'] = 'Error interno, al crear archivo kit materiales';
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
            $codigo_po  = $this->input->post('codigoPo');
            
            $uploaddir =  'uploads/po_mo/';//ruta final del file
            $uploadfile = $uploaddir . basename($_FILES['file']['name']);
            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {

                $objPHPExcel = PHPExcel_IOFactory::load($uploadfile);

                $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
                $row_dimension = $objPHPExcel->getActiveSheet()->getHighestRowAndColumn();
                
                $info_2 = $this->makeHTMLBodyTable($codigo_po, $row_dimension, $objPHPExcel, $itemplan, $idEstacion);
                $data['tablaData'] = $info_2['html'];
                $data['jsonDataFIleValido'] = json_encode($info_2['array']);
                $data['jsonDataFIle'] = json_encode($info_2['array_full']);
                $this->session->set_userdata('sirope_NF',$info_2['array_nf']);
                $data['total_final'] = $info_2['total_final'];
                $data['error'] = EXIT_SUCCESS;
    
               
            } else {
                throw new Exception('Hubo un problema con la carga del archivo al servidor, comuniquese con el administrador.');
            }
    
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function makeHTMLBodyTable($codigo_po, $row_dimension, $objPHPExcel, $itemplan, $idEstacion){
        $data['html'] = '';
        $data['array'] = '';
        $data['array_full'] = '';
        $html = '';
        $indice = 0;
        $cont_indice_valido = 0; 
        $total_final = 0;
        $array_valido = array();
        $array_not_found = array();
        $indice_valido = '';
        $array_full = array();
        for ($i = 1; $i <= $row_dimension['row']; $i++){//COMIENZA DESDE LA FILA 1
            $A = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0,$i,true)->getValue();
            $B = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1,$i,true)->getValue();
            $C = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2,$i,true)->getValue();
            $D = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(3,$i,true)->getValue();
            $E = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(4,$i,true)->getValue();
            $F = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(5,$i,true)->getValue();
            $total = 0;
            if($F!='' && $A!='CODIGO'){
                if(is_numeric($F)){
                    $infoPartida    =   $this->m_liquidar_mo->getInfoCodigoMaterial($codigo_po, $itemplan, $idEstacion, $A);
                    if($infoPartida!=null){
                        $color_row = "white";
                        $situacion = 'OK';
                        $D = $infoPartida['costo'];
                        /***********************************************/
                        /*NUEVO SE FORZA COSTO F.O PARA PARTIDA PEDIDO OWEN 05.06.2019**/
                       /* if(trim($A) == '23108-8'){//log_message('error', 'ES IGUAL...');
                            $newCosto = $this->m_utils->getCostoFoPartidasByItemplan($itemplan);
                            if($newCosto!=null){
                                $D = $newCosto['costo'];
                            }
                        }*/
                        /***********************************************/  
                        $E = $infoPartida['baremo'];
                        $total = ($D*$E*$F);
                        $pre_array = array($A, $B, $C, $D, $E, $F,$infoPartida['idActividad'] ,$total, (($infoPartida['cantidad_inicial']!=null) ? 1 : 2), $infoPartida['id_planobra_po_detalle_po']); //1 == update, 2 == insert
                        array_push($array_valido, $pre_array);
                        $indice_valido = 'data-indice_val="'.$cont_indice_valido.'"';
                        $cont_indice_valido++;     
                        $cantidad_inicial = (($infoPartida['cantidad_inicial']!=null) ? $infoPartida['cantidad_inicial'] : 0);    
                        $total_final = $total_final + $total;
                    }else{
                        $color_row = "#b3b37b";
                        $situacion = 'PARTIDA NO PERMITIDA';
                        $pre_array = array($A, $B, $C, $D, $E, $F, 2); //2 == erroneos
                        array_push($array_not_found, $pre_array);
                        $cantidad_inicial = 0;
                        log_message('error', 'Partida no asociada la proyecto - estacion'.$A);
                    }
                    
                }else{
                    $color_row = "#b3b37b";
                    $situacion = 'CANTIDAD INGRESADA INVALIDAD';
                    $pre_array = array($A, $B, $C, $D, $E, $F, 2); //2 == erroneos
                    array_push($array_not_found, $pre_array);
                    $cantidad_inicial = 0;
                    log_message('error', 'Partida no asociada la proyecto - estacion'.$A);
                    //log_message('error', 'no numerico'.$A);
                }      
                
                $html .= '<tr id="tr'.$indice.'" style="background-color:'.$color_row.'">
                            <th style="width: 5px;"><a style="cursor:pointer;" '.$indice_valido.' data-indice="'.$indice.'" onclick="removeTR(this)"><img class="delete_ptr" alt="Eliminar" height="20px" width="20px" src="public/img/iconos/delete.png"></a></th>
                        	<th style="color:black">'.$A.'</th>
                        	<th style="color:black">'.utf8_decode($B).'</th>
                        	<th style="color:black">'.$C.'</th>
                    	    <th style="color:black">'.$D.'</th>
                	        <th style="color:black">'.$E.'</th>                	        
            	            <th style="color:black">'.$cantidad_inicial.'</th>
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
                $codigo_po      = $this->input->post('codigoPo');/********************FALTA ***********/
                $arrayFile = json_decode($jsonDataFile);
                $arrayFinalUpdate = array();
                $arrayFinalInsert = array();
                
                $initActividades = $this->m_liquidar_mo->getActividadesByPo($codigo_po); 
                $arrayActividades = array();
                if($arrayFile!=null){                  
                    $costoTotalPOMO = 0;                   
                    
                    foreach($initActividades as $row){//PONER EN 0 LO QUE NO VINO
                        $exist  = false;
                        foreach($arrayFile as $datos){
                            if($row->idACtividad == $datos[6]){
                                $exist = true;
                                break;
                            }                            
                        }
                        if(!$exist){
                            $actividadUpdate = array();
                            $actividadUpdate['id_planobra_po_detalle_po']   = $row->id_planobra_po_detalle_po;
                            $actividadUpdate['cantidad_final']              = 0;
                            $actividadUpdate['monto_final']                 = 0;//si no vino se pone 0
                            $actividadUpdate['cantidad_editado_eecc']       = 0;
                            $actividadUpdate['monto_editado_eecc']          = 0;
                            array_push($arrayFinalUpdate, $actividadUpdate);
                            array_push($arrayActividades, $row->idACtividad);//metemos idActividad
                        }
                    }
                
                    foreach($arrayFile as $datos){
                        if($datos!=null){
                            if($datos[8]!=null){
                                if(!in_array($datos[6], $arrayActividades)){
                                    if($datos[8]    ==  1){//UPDATE
                                        $dataCMO = array();
                                        $dataCMO['id_planobra_po_detalle_po']   = $datos[9];
                                        $dataCMO['baremo']                      = $datos[4];
                                        $dataCMO['costo']                       = $datos[3];
                                        $dataCMO['cantidad_final']              = $datos[5];
                                        $dataCMO['monto_final']                 = $datos[7];
                                        $dataCMO['cantidad_editado_eecc']       = $datos[5];
                                        $dataCMO['monto_editado_eecc']          = $datos[7];
                                        array_push($arrayFinalUpdate, $dataCMO);
                                        $costoTotalPOMO = $costoTotalPOMO + $dataCMO['monto_final'];
                                        array_push($arrayActividades, $datos[6]);//metemos idActividad
                                    }else if($datos[8]    ==  2){//insert
                                        $dataCMO = array();
                                        $dataCMO['codigo_po']               = $codigo_po;
                                        $dataCMO['idActividad']             = $datos[6];
                                        $dataCMO['baremo']                  = $datos[4];
                                        $dataCMO['costo']                   = $datos[3];
                                        $dataCMO['cantidad_inicial']        = 0;
                                        $dataCMO['monto_inicial']           = 0;
                                        $dataCMO['cantidad_final']          = $datos[5];
                                        $dataCMO['monto_final']             = $datos[7];
                                        $dataCMO['cantidad_editado_eecc']   = $datos[5];
                                        $dataCMO['monto_editado_eecc']      = $datos[7];
                                        array_push($arrayFinalInsert, $dataCMO);
                                        $costoTotalPOMO = $costoTotalPOMO + $dataCMO['monto_final'];
                                        array_push($arrayActividades, $datos[6]);//metemos idActividad
                                    }
                                }
                            }
                        }
                    }
                                    
                    $dataLogPO = array(
                        'codigo_po'         =>  $codigo_po,
                        'itemplan'          =>  $itemplan,
                        'idUsuario'         =>  $idUsuario,
                        'fecha_registro'    =>  $this->fechaActual(),                        
                        'controlador'       =>  'editar/liquidar mo'
                    );
                    
                     $dataUpdatePO = array(
                        'costo_total'     => $costoTotalPOMO
                    );
                    
                    //log_message('error', print_r($dataDetalleplan, true));
                    $data = $this->m_liquidar_mo->updatePoMO($dataLogPO, $arrayFinalUpdate, $arrayFinalInsert, $dataUpdatePO, $itemplan, $codigo_po);
                    if($data['error']   ==  EXIT_ERROR){
                        throw new Exception('Hubo un error interno, por favor volver a intentar.');
                    }                    
                    $data['codigoPO']    =   $itemplan;
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
    
    function liquidarPOMO(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;
            
            

            if($idUsuario   !=     null){
                $itemplan       = $this->input->post('itemplan');
                $codigo_po      = $this->input->post('codigo_po');     
                
                $countValid = $this->m_liquidar_mo->getCountControlPresupuestal($codigo_po);

                if ($countValid > 0) {
                    throw new Exception('Ya tiene una solicitud pendiente de aprobacion en la obra.');
                }

                $dataLogPO = array(
                    'codigo_po'         =>  $codigo_po,
                    'itemplan'          =>  $itemplan,
                    'idUsuario'         =>  $idUsuario,
                    'fecha_registro'    =>  $this->fechaActual(),
                    'idPoestado'        =>  PO_LIQUIDADO,
                    'controlador'       =>  'editar/liquidar mo'
                );
                
                $dataUpdate = array(
                    'estado_po'     => PO_LIQUIDADO
                );
                
                $data = $this->m_liquidar_mo->liquidarPO($dataLogPO, $dataUpdate, $codigo_po, $itemplan);
                if($data['error']   ==  EXIT_ERROR){
                    throw new Exception('Hubo un error interno, por favor volver a intentar.');
                }
                $data['codigoPO']    =   $itemplan;
                $data['error']       = EXIT_SUCCESS;
             
            }else{
                throw new Exception('Su sesion expiro, porfavor vuelva a logearse.');
            }
             
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function regSolicitudPreMo(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try{
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;
            if($idUsuario   !=     null){
                $itemplan       = $this->input->post('itemplan');
                $idEstacion     = $this->input->post('idEstacion');
                $jsonDataFile   = $this->input->post('jsonDataFile');
                $codigoPO       = $this->input->post('codigoPo');
                $montoExcede    = $this->input->post('montoExcede');
                $montoPo        = $this->input->post('montoPo');
				$montoTotalMo   = $this->input->post('montoTotalMo');
				$comentario   	= $this->input->post('comentario');
				
                $arrayFile = json_decode($jsonDataFile);
                $dataDetalleSolicitud = array();

                if ($codigoPO == null) {
                    throw new Exception('Hubo un error, no tiene PO');
                }

                $codigoSolicitud = $this->m_liquidar_mo->getCodigoSolicitud();

                if ($codigoSolicitud == null) {
                    throw new Exception('No se genero el codigo solicitud');
                }

                $countValid = $this->m_liquidar_mo->getCountControlPresupuestal($codigoPO);

                if ($countValid > 0) {
                    throw new Exception('Ya tiene una solicitud registrada.');
                }
				
				$infoCU = $this->m_utils->getVariablesCostoUnitario($itemplan, 2, $codigoPO);
				$costoUnitarioObra = $infoCU['costo_unitario_mo'];
				$costoTotalAllPo    =  $infoCU['total'];
				
				// if($costoUnitarioObra==null || $costoUnitarioObra==0){
					// throw new Exception('La Obra no cuenta con Costo Unitario Registrado.');
				// }

                if($arrayFile!=null){
                    $costoTotalPOMO = 0;
                    $arrayActividades = array();
                    foreach($arrayFile as $datos){
                        if($datos!=null){
                            if($datos[8]!=null){
                                // if($datos[8]    ==  1){
                                    if(!in_array($datos[6], $arrayActividades)){
                                        $dataCMO = array();
                                        $dataCMO['codigo_solicitud'] = $codigoSolicitud;
                                        $dataCMO['codigo_po']        = $codigoPO;
                                        $dataCMO['idActividad']      = $datos[6];
                                        $dataCMO['baremo']           = $datos[4];
                                        $dataCMO['costo']            = $datos[3];
                                        $dataCMO['cantidad_inicial'] = ($datos[8] == 1) ? $datos[5] : 0;
                                        $dataCMO['monto_inicial']    = $datos[7];
                                        $dataCMO['cantidad_final']   = $datos[5];
                                        $dataCMO['monto_final']      = $datos[7];
                                        array_push($dataDetalleSolicitud, $dataCMO);
                                        $costoTotalPOMO = $costoTotalPOMO + $dataCMO['monto_inicial'];
                                        array_push($arrayActividades, $datos[6]);//metemos idActividad
                                    }
                                // }
                            }
                        }
                    }
					
					$montoExcede = $costoTotalPOMO - $montoPo;
					
					if($montoExcede <= 0) {
						throw new Exception("monto no admitido para una solicitud");
					}
 
                    $dataSolicitud = array(
                                            'codigo_solicitud' => $codigoSolicitud,
                                            'itemplan'      => $itemplan,
                                            'codigo_po'     => $codigoPO,
                                            'estado'        => 0, //ESTADO PENDIENTE
                                            'idEstacion'    => $idEstacion,
                                            'total'         => $costoTotalPOMO,
                                            'idUsuario'     => $idUsuario,
                                            'fechaRegistro' => $this->fechaActual(),
                                            'total_excede'  => $montoExcede,
                                            'total_actual'  => $montoPo,
											'comentario_reg' => $comentario
                                            );
											
					if (count($_FILES) > 0) {
						$uploaddir =  'uploads/solicitud_pdt_pago/'.$itemplan.'_'.$codigoSolicitud.'/';//ruta final del file
						$uploadfile = $uploaddir . basename($_FILES['file']['name']);
						if (! is_dir ( $uploaddir))
							mkdir ( $uploaddir, 0777 );
						
						if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
							$dataSolicitud['url_archivo'] = $uploadfile;
						}else {
							throw new Exception('Hubo un problema con la carga del archivo al servidor, comuniquese con el administrador.');
						}
					} else {
						throw new Exception('Subir el archivo de evidencia de exceso.');
					}
					
                    $data = $this->m_liquidar_mo->regSolicitudMO($dataSolicitud, $dataDetalleSolicitud);

                    if($data['error']   ==  EXIT_ERROR){
                        throw new Exception('Hubo un error interno, por favor volver a intentar.');
                    }
                    $data['codigoSolicitud']    =   $codigoSolicitud;
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
}