<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class C_creacion_solicitud_creacion_masivo extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_certificacion/m_creacion_solicitud_creacion_masivo');
        $this->load->model('mf_pre_diseno/m_bandeja_adjudicacion');
        $this->load->model('mf_servicios/M_integracion_sirope');
		$this->load->model('mf_pqt_terminado/m_pqt_terminado');
		$this->load->model('mf_control_presupuestal/m_pqt_reg_mat_x_esta_pqt');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->library('excel');
        $this->load->helper('url');
    }

    public function index() {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {
            //$solicitud = (isset($_GET['sol']) ? $_GET['sol'] : '');
            //PONER VALIDACIONES
            //$data['solicitud'] = $solicitud;
            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $data['tablaSolOC']    = $this->basicHtml();
            $permisos = $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, 250, 307, ID_MODULO_ADMINISTRATIVO);
            $data['opciones'] = $result['html'];
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_certificacion/v_creacion_solicitud_creacion_masivo', $data);
            } else {
                redirect('login', 'refresh');
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function getExcelPartidasMO() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
          
                    ini_set('max_execution_time', 10000);
                    ini_set('memory_limit', '2048M');

                    $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
                    $cacheSettings = array('memoryCacheSize ' => '5000MB', 'cacheTime' => '1000');
                    PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle('ValeDatos');
                    $contador = 1;
                    $titulosColumnas = array('ITEMPLAN','COSTO MO', 'PEP 1');

                    $this->excel->setActiveSheetIndex(0);

                    // Se agregan los titulos del reporte
                    $this->excel->setActiveSheetIndex(0)
                            ->setCellValue('A1', utf8_encode($titulosColumnas[0]))
                            ->setCellValue('B1', utf8_encode($titulosColumnas[1]))
                            ->setCellValue('C1', utf8_encode($titulosColumnas[2]));

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
                    $archivo = "solicitud_oc.xls";
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="' . $archivo . '"');
                    header('Cache-Control: max-age=0');
                    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                    //Hacemos una salida al navegador con el archivo Excel.

                    $nombreFile = 'download/detalleMatPO/formato_carga_sol_' . date("YmdHis") . '.xls';
                    $objWriter->save($nombreFile);
                    $data['rutaExcel'] = $nombreFile;
                    $data['error'] = EXIT_SUCCESS;
            
        } catch (Exception $e) {
            $data['msj'] = 'Error interno, al crear archivo partidas MO';
        }
        // return $data;
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function uploadPOMO() {
        $data ['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $uploaddir = 'uploads/oc_masivo/'; //ruta final del file
            $uploadfile = $uploaddir . basename($_FILES['file']['name']);
            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {

                $objPHPExcel = PHPExcel_IOFactory::load($uploadfile);

                $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
                $row_dimension = $objPHPExcel->getActiveSheet()->getHighestRowAndColumn();

                $info_2 = $this->makeHTMLBodyTable($row_dimension, $objPHPExcel);
                $data['tablaData'] = $info_2['html'];
                $data['jsonDataFIle'] = json_encode($info_2['array_full']);
                $data['error'] = EXIT_SUCCESS;
            } else {
                throw new Exception('Hubo un problema con la carga del archivo al servidor, comuniquese con el administrador.');
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function basicHtml(){
        $html = '<table style="font-size: 10px" id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr role="row">
                        <th colspan="1">ITEMPLAN</th>
                        <th colspan="1">COSTO MO</th>
                        <th colspan="1">PEP 1</th>
                        <th colspan="1">OBSERVACION</th>
                        </tr>
                    </thead>
                    <tbody id="contBodyTable">
                     
                    </tbody>
                </table>';
            return $html;
    }
    
    public function makeHTMLBodyTable($row_dimension, $objPHPExcel) {
        $data['html'] = '';
        $data['array_full'] = '';
        $html = '';
        $indice = 0;
        $array_not_found = array();
        $array_full = array();
        $html .= '<table style="font-size: 10px" id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr role="row">
                        <th colspan="1">ITEMPLAN</th>
                        <th colspan="1">COSTO MO</th>
                        <th colspan="1">PEP 1</th>
                        <th colspan="1">OBSERVACION</th>
                        </tr>
                    </thead>
                <tbody id="contBodyTable">';
        for ($i = 1; $i <= $row_dimension['row']; $i++) {//COMIENZA DESDE LA FILA 1
            $A = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, $i, true)->getValue();
            $B = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1, $i, true)->getValue();
            $C = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2, $i, true)->getValue();
            $total = 0;
            if ($A != '' && $A != 'ITEMPLAN' && $B != '' && $C != '') {
                $infoItemplan = $this->m_utils->getInfoItemplanWhitSubProyecto(trim($A));
                if($infoItemplan != null){
                    $infoSol = $this->m_creacion_solicitud_creacion_masivo->getInfoSolicitudOCCreaByCodigo(trim($A));
					_log(print_r($infoSol, true));
                    if($infoSol != null){
                        if($infoSol['has_sol_creacion'] >   0	 && $infoSol['has_sol_anulacion_aten'] == 0){
                            if($infoSol['has_sol_creacion_aten']    >   0){
                                $html .= '<tr style="background-color: #ffcece;" id="tr' . $indice . '" >
                                        	<th style="color:black">' . $A . '</th>
                                        	<th style="color:black">' . $B . '</th>
                                    	    <th style="color:black">' . $C . '</th>
                						    <th style="width: 5px;">CON SOLICITUD CREACION OC ATENDIDA</th>
                                    	</tr>';
                                $indice++;
                            }else{
                                $html .= '<tr style="background-color: #ffcece;" id="tr' . $indice . '" >
                                        	<th style="color:black">' . $A . '</th>
                                        	<th style="color:black">' . $B . '</th>
                                    	    <th style="color:black">' . $C . '</th>
                    					    <th style="width: 5px;">CON SOLICITUD CREACION OC PENDIENTE DE ATENCION</th>
                                    	</tr>';
                                $indice++;
                            }
                        }else if($infoSol['has_sol_creacion_aten']    ==   0){//esta cancelada
							$html .= '<tr id="tr' . $indice . '" >
										<th style="color:black">' . $A . '</th>
										<th style="color:black">' . $B . '</th>
										<th style="color:black">' . $C . '</th>
										<th style="width: 5px;">OK</th>
									  </tr>';
									$indice++;
									
									$pre_array = array($infoItemplan['itemplan'], $B, $C, $infoItemplan['idProyecto'],$infoItemplan['idSubProyecto']);
									array_push($array_full, $pre_array);							
						}                      
                    }else{
                        if(is_numeric(trim($B))){
                            if(strlen(trim($C)) == 20){
                                if($infoItemplan['idProyecto']  ==  ID_PROYECTO_OBRA_PUBLICA){
                                    $infoOPublica = $this->m_creacion_solicitud_creacion_masivo->getInfoObrasPublicasByHijo(trim($A));
                                    if($infoOPublica['estado_factura_convenio'] ==  'EMITIDO'){
                                        $html .= '<tr id="tr' . $indice . '" >
                                                	<th style="color:black">' . $A . '</th>
                                                	<th style="color:black">' . $B . '</th>
                                            	    <th style="color:black">' . $C . '</th>
                        						    <th style="width: 5px;">OK</th>
                                            	  </tr>';
                                                    $indice++;
                                        
                                        $pre_array = array($infoItemplan['itemplan'], $B, $C, $infoItemplan['idProyecto']);
                                        array_push($array_full, $pre_array);
                                    }else{
                                        $html .= '<tr style="background-color: #ffcece;" id="tr' . $indice . '" >
                                                    	<th style="color:black">' . $A . '</th>
                                                    	<th style="color:black">' . $B . '</th>
                                                	    <th style="color:black">' . $C . '</th>
                            						    <th style="width: 5px;">ITEMPLAN MADRE SIN FACTURA EMITIDA</th>
                                                	</tr>';
                                        $indice++;
                                    }
                                }else{
                                    $html .= '<tr id="tr' . $indice . '" >
                                    	<th style="color:black">' . $A . '</th>
                                    	<th style="color:black">' . $B . '</th>
                                	    <th style="color:black">' . $C . '</th>
            						    <th style="width: 5px;">OK</th>
                                	  </tr>';
                                    $indice++;
                                    
                                    $pre_array = array($infoItemplan['itemplan'], $B, $C, $infoItemplan['idProyecto']);
                                    array_push($array_full, $pre_array);
                                }
                            }else{
                                $html .= '<tr style="background-color: #ffcece;" id="tr' . $indice . '" >
                                            	<th style="color:black">' . $A . '</th>
                                            	<th style="color:black">' . $B . '</th>
                                        	    <th style="color:black">' . $C . '</th>
                    						    <th style="width: 5px;">PEP 1 DEBE CONTENER 20 CARACTERES</th>
                                        	</tr>';
                                $indice++;
                            }                            
                        }else{
                            $html .= '<tr style="background-color: #ffcece;" id="tr' . $indice . '" >
                            	<th style="color:black">' . $A . '</th>
                            	<th style="color:black">' . $B . '</th>
                        	    <th style="color:black">' . $C . '</th>
    						    <th style="width: 5px;">MONTO MO DEBE SER NUMERICO</th>
                        	</tr>';
                            $indice++;
                        }
                        
                    }                                          
                }else{//INVALIDO ITEMPLAN NO RECONOCIDO
                    $html .= '<tr style="background-color: #ffcece;" id="tr' . $indice . '" >
                            	<th style="color:black">' . $A . '</th>
                            	<th style="color:black">' . $B . '</th>
                        	    <th style="color:black">' . $C . '</th>
    						    <th style="width: 5px;">ITEMPLAN NO RECONOCIDO</th>
                        	</tr>';
                    $indice++;
                }
            }
        }
        $html .= '</tbody>
                </table>';    
        $data['html'] = $html;
        $data['array_full'] = $array_full;
        return $data;
    }

    public function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone', 'America/Lima');
        setlocale(LC_TIME, "es_ES", "esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }

    function generarExcelErrores() {
        $listNF = $this->session->userdata('sirope_NF');
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('test worksheet');
        //set cell A1 content with some text
        $i = 1;
        foreach ($listNF as $row) {
            $this->excel->getActiveSheet()->setCellValue('A' . $i, $row[0]);
            $this->excel->getActiveSheet()->setCellValue('B' . $i, $row[1]);
            $this->excel->getActiveSheet()->setCellValue('C' . $i, $row[2]);
            $this->excel->getActiveSheet()->setCellValue('D' . $i, $row[3]);
            $this->excel->getActiveSheet()->setCellValue('E' . $i, $row[4]);
            $this->excel->getActiveSheet()->setCellValue('F' . $i, $row[5]);
            $this->excel->getActiveSheet()->setCellValue('G' . $i, $row[6]);
            $this->excel->getActiveSheet()->setCellValue('H' . $i, $row[7]);
            $this->excel->getActiveSheet()->setCellValue('I' . $i, $row[8]);
            $this->excel->getActiveSheet()->setCellValue('J' . $i, $row[9]);
            $this->excel->getActiveSheet()->setCellValue('K' . $i, $row[10]);
            $this->excel->getActiveSheet()->setCellValue('L' . $i, $row[11]);
            $i++;
        }
        $filename = 'just_some_random_name.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
    }

     public function regMasiveOcSol() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;
            if ($idUsuario != null) {               
                    $jsonDataFile = $this->input->post('jsonDataFile');
                    $arrayFile = json_decode($jsonDataFile);                   
                    if ($arrayFile != null) {           
                        $dataResult = array();                   
                        foreach ($arrayFile as $datos) {
                            if ($datos != null) {
                                $resultado = array();
                                //log_message('error', print_r($datos, true));
                                $itemplan       = $datos[0];   
                                $costoMo        = $datos[1];
                                $pep1           = $datos[2];
                                $idProyecto     = $datos[3];            
                                $resultado = array( 'itemplan'  =>  $datos[0],
                                                    'costo_mo'  =>  $datos[1],
                                                    'pep1'      =>  $datos[2]);
                               $monto_tempo_pep = $this->m_utils->getMontoTemporalByPep1($pep1);
                               
                               if(is_numeric($costoMo)){
                                   if($monto_tempo_pep != null){
                                       if($monto_tempo_pep >= $costoMo){
                                           $cod_solicitud = $this->m_creacion_solicitud_creacion_masivo->createSolCreacionOC($itemplan, $costoMo, $pep1); 
                                           if($cod_solicitud!=null){
                                               $resultado['error']              = EXIT_SUCCESS;
                                               $resultado['codigo_solicitud']   = $cod_solicitud;
                                               $resultado['mensaje']            = 'SE GENERO LA SOLICITUD';
                                               array_push($dataResult, $resultado);
                                           }else{
                                               $resultado['error']              = EXIT_ERROR;
                                               $resultado['codigo_solicitud']   = null;
                                               $resultado['mensaje']            = 'NO SE PUDO OBTENER CODIGO SOLICITUD';
                                               array_push($dataResult, $resultado);
                                           }
                                       }else{
                                           $resultado['error']              = EXIT_ERROR;
                                           $resultado['codigo_solicitud']   = null;
                                           $resultado['mensaje']            = 'PEP SIN PRESUPUESTO';
                                           array_push($dataResult, $resultado);
                                       }
                                   }else{
                                       $resultado['error']              = EXIT_ERROR;
                                       $resultado['codigo_solicitud']   = null;
                                       $resultado['mensaje']            = 'NO EXISTE PEP EN SAP';
                                       array_push($dataResult, $resultado);
                                   }                                    
                               }else{
                                   $resultado['error']              = EXIT_ERROR;
                                   $resultado['codigo_solicitud']   = null;
                                   $resultado['mensaje']            = 'COSTO MO INVALIDO';
                                   array_push($dataResult, $resultado);                                
                               }                              
                            }
                        }
                       $data['html_table'] = $this->makeTableResultado($dataResult);
                       $data['error'] = EXIT_SUCCESS;
                      // $data = $this->m_creacion_solicitud_creacion_masivo->generarSolicitudesDeAnulacion($arraySolicitud, $arrayItemXSolicitud, $arrayItemplan);                       
                    } else {
                        throw new Exception('No se pudo procesar el archivo, refresque la pagina y vuelva a intentarlo.');
                    }
            } else {
                throw new Exception('Su sesion expiro, porfavor vuelva a logearse.');
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }	
    
    public function makeTableResultado($arrayData){
        $html = '<table style="font-size: 10px" id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr role="row">
                        <th colspan="1">ITEMPLAN</th>
                        <th colspan="1">COSTO MO</th>
                        <th colspan="1">PEP 1</th>
                        <th colspan="1">CODIGO SOLICITUD</th>
                        <th colspan="1">OBSERVACION</th>
                        </tr>
                    </thead>
                    <tbody id="contBodyTable">';
                foreach($arrayData as $row){
             $html .= '<tr '.(($row['error']    ==  EXIT_ERROR)? 'style="background-color: #ffcece;"' : '').'>
                            <th style="color:black">' . $row['itemplan'] . '</th>
                        	<th style="color:black">' . $row['costo_mo'] . '</th>
                        	<th style="color:black">' . $row['pep1'] . '</th>
                    	    <th style="color:black">' . $row['codigo_solicitud'] . '</th>
						    <th style="color:black">'.  $row['mensaje'].'</th>
                    	</tr>';
                    }
        $html .=   '</tbody>
                </table>';
        return $html;
    }

}
