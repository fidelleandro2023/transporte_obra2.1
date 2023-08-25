<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_registro_partidas_adicionales_mo extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_pqt_terminado/m_registro_partidas_adicionales_mo');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->library('excel');
        $this->load->helper('url');
    }
    
    public function index(){
        $logedUser = $this->session->userdata('usernameSession');
        if($logedUser != null){
            $item = (isset($_GET['itm']) ? $_GET['itm'] : '');
            $idEstacion = (isset($_GET['idEs']) ? $_GET['idEs'] : '');
            //PONER VALIDACIONES
            $data['itemplan']       =   $item;
            $data['idEstacion']     =   $idEstacion;   
            $data['estacionDesc']   =   $this->m_utils->getEstaciondescByIdEstacion($idEstacion);
            $data['nombreUsuario']  =   $this->session->userdata('usernameSession');
            $data['perfilUsuario']  =   $this->session->userdata('descPerfilSession');
            $this->load->view('vf_pqt_gestion_obra_terminado/v_registro_partidas_adicionales_mo',$data);            
           
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
    
            $arrayMateriales = $this->m_registro_partidas_adicionales_mo->getPartidasByProyectoEstacion($itemplan, $idEstacion);			
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
                        
                         $costoEnd = $row->costo;
                        /***************************NUEVO PEDIDO OWEN 06.06.2019***************************/
                        if(trim($row->codigo) == '23108-8'){//log_message('error', 'ES IGUAL...');
                            $newCosto = $this->m_utils->getCostoFoPartidasByItemplan($itemplan);
                            if($newCosto!=null){
                                $costoEnd = $newCosto['costo'];
                            }
                        }
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
            $A = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0,$i,true)->getValue();
            $B = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1,$i,true)->getValue();
            $C = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2,$i,true)->getValue();
            $D = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(3,$i,true)->getValue();
            $E = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(4,$i,true)->getValue();
            $F = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(5,$i,true)->getValue();
            $total = 0;
            if($F!='' && $A!='CODIGO'){
                if(is_numeric($F)){
                    $infoPartida    =   $this->m_registro_partidas_adicionales_mo->getInfoCodigoMaterial($itemplan, $idEstacion, $A);
                    if($infoPartida!=null){
                         $D = $infoPartida['costo'];
                        /***********************************************/
                        /*NUEVO SE FORZA COSTO F.O PARA PARTIDA PEDIDO OWEN 05.06.2019**/
                        if(trim($A) == '23108-8'){//log_message('error', 'ES IGUAL...');
                            $newCosto = $this->m_utils->getCostoFoPartidasByItemplan($itemplan);
                            if($newCosto!=null){
                                $D = $newCosto['costo'];
                            }
                        }
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
                $arrayFile = json_decode($jsonDataFile);
                $arrayFinalInsert = array();
                if($arrayFile!=null){
                    $arrayActividades = array();
                    foreach($arrayFile as $datos){
                        if($datos!=null){
                            if($datos[8]!=null){
                                if($datos[8]    ==  1){//registrar
                                    if(!in_array($datos[6], $arrayActividades)){
                                        $dataCMO = array();
                                        $dataCMO['itemplan']         = $itemplan;
                                        $dataCMO['idEstacion']       = $idEstacion;
                                        $dataCMO['idActividad']      = $datos[6];
                                        $dataCMO['baremo']           = $datos[4];
                                        $dataCMO['costo']            = $datos[3];
                                        $dataCMO['cantidad']         = $datos[5];
                                        $dataCMO['total']            = $datos[7];
                                        $dataCMO['estado']           = 0;
                                        array_push($arrayFinalInsert, $dataCMO);
                                        array_push($arrayActividades, $datos[6]);//metemos idActividad
                                    }
                                }
                            }
                        }
                    }
                    
                    $dataPqtTmp = array (   'itemplan'              =>  $itemplan,
                                            'idEstacion'            =>  $idEstacion,
                                            'estado'                =>  0,//pndt de validar
                                            'codigo_po'             =>  null,
                                            'fecha_pdt_validar'     =>  $this->fechaActual(),
                                            'usuario_pdt_validar'   =>  $idUsuario
                                        );
                                       
                    $data = $this->m_registro_partidas_adicionales_mo->createPartidasAdicionalesTmp($arrayFinalInsert, $dataPqtTmp, $itemplan, $idEstacion);
                    if($data['error']   ==  EXIT_ERROR){
                        throw new Exception('Hubo un error interno, por favor volver a intentar.');
                    }
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
   
}