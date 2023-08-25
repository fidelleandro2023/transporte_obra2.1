<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class C_cambiar_eecc_subpro_itemplan_masivo extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_pqt_plan_obra/M_cambiar_eecc_subpro_itemplan_masivo');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->library('excel');
        $this->load->helper('url');
    }

    public function index(){
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {
            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $data['tablaSolOC']    = $this->basicHtml();
            $permisos = $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, 241, 318, ID_MODULO_MANTENIMIENTO);
            $data['opciones'] = $result['html'];
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_pqt_plan_obra/v_cambiar_eecc_subpro_itemplan_masivo', $data);
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
                    $titulosColumnas = array('ITEMPLAN','EECC','SUBPROYECTO');

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

                    $nombreFile = 'download/detalleMatPO/cambio_estado' . date("YmdHis") . '.xls';
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
                            <th colspan="1">ESTADO PLAN</th>
                            <th colspan="1">EECC ACTUAL</th>
                            <th colspan="1">SUBPROYECTO ACTUAL</th>
                            <th colspan="1">EECC NUEVO</th>
                            <th colspan="1">SUB PROYECTO NUEVO</th>
                            <th colspan="1">OBSERVACION</th>
                        </tr>
                    </thead>
                    <tbody id="contBodyTable">
                     
                    </tbody>
                </table>';
            return $html;
    }
    
    public function getHtmlOk($indice, $A, $B, $C, $infoSol, $comentario){
        return '<tr id="tr' . $indice . '" >
                	<th style="color:black">'.$A.'</th>
                	<th style="color:black">'.$infoSol['estadoPlanDesc'].'</th>    
                	<th style="color:black">'.$infoSol['empresaColabDesc'].'</th>
                	<th style="color:black">'.$infoSol['subProyectoDesc'].'</th>
                	<th style="color:black">'.$B.'</th>
				    <th style="color:black">'.$C.'</th>
			        <th style="color:black">'.$comentario.'</th>
            	</tr>';
    }
    
    public function getHtmlError($indice, $A, $B, $C, $infoSol, $comentario){
        return '<tr style="background-color: #ffcece;" id="tr' . $indice . '" >
                	<th style="color:black">'.$A.'</th>
            	    <th style="color:black">'.$infoSol['estadoPlanDesc'].'</th>
                	<th style="color:black">'.$infoSol['empresaColabDesc'].'</th>    
                	<th style="color:black">'.$infoSol['subProyectoDesc'].'</th>    
                	<th style="color:black">'.$B.'</th>
				    <th style="color:black">'.$C.'</th>
			        <th style="color:black">'.$comentario.'</th>
            	</tr>';
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
                            <th colspan="1">ESTADO PLAN</th>
                            <th colspan="1">EECC ACTUAL</th>
                            <th colspan="1">SUBPROYECTO ACTUAL</th>
                            <th colspan="1">EECC NUEVO</th>
                            <th colspan="1">SUB PROYECTO NUEVO</th>
                            <th colspan="1">OBSERVACION</th>
                        </tr>
                    </thead>
                <tbody id="contBodyTable">';
        for ($i = 1; $i <= $row_dimension['row']; $i++) {//COMIENZA DESDE LA FILA 1
            $A = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, $i, true)->getValue();
            $B = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1, $i, true)->getValue();
            $C = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2, $i, true)->getValue();
            $total = 0;
            $array_eecc_integral = array(ID_EECC_CAMPERU, ID_EECC_QUANTA);
            if ($A != 'ITEMPLAN') {
                $infoSol = $this->M_cambiar_eecc_subpro_itemplan_masivo->getInfoItemplanToEditEECC(trim($A));
                if($infoSol != null){             
                    if($infoSol['idEstadoPlan'] == ESTADO_PLAN_PRE_REGISTRO){
                        if($infoSol['idProyecto']==ID_PROYECTO_SISEGOS){
                            $html .= $this->getHtmlError($indice, $A, $B, $C, $infoSol, 'No esta permitido cambios para SISEGO');
                            $indice++;
                        }else{
                            if($infoSol['solicitud_oc']!=null){
                                $html .= $this->getHtmlError($indice, $A, $B, $C, $infoSol, 'La obra cuenta con solicitud OC');
                                $indice++;
                            }else{
                                $getNewEecc = $this->M_cambiar_eecc_subpro_itemplan_masivo->getEECCByDescripcion(trim($B));
                                if($getNewEecc==null){
                                    $html .= $this->getHtmlError($indice, $A, $B, $C, $infoSol, 'Dato incorrecto en campo eecc nuevo');
                                    $indice++;
                                }else{
                                    if(trim($C)!=null && trim($C)!=''){
                                        $getNewSubProyecto = $this->M_cambiar_eecc_subpro_itemplan_masivo->getSubProyectoByDescripcion(trim($C));
                                        if($getNewSubProyecto==null){
                                            $html .= $this->getHtmlError($indice, $A, $B, $C, $infoSol, 'Nuevo Subproyecto no reconocido');
                                            $indice++;
                                        }else{
                                            
                                            if($getNewSubProyecto['idTipoSubProyecto']    ==  1){//BUCLE
                                          //      log_message('error', $getNewEecc['idEmpresaColab']);
                                            //    log_message('error', print_r($array_eecc_integral, true));
                                                if(in_array($getNewEecc['idEmpresaColab'], $array_eecc_integral)){
                                                    $html .= $this->getHtmlError($indice, $A, $B, $C, $infoSol, 'EECC No permitida para el subproyecto seleccionado');
                                                    $indice++;
                                                }else{
                                                    $html .= $this->getHtmlOk($indice, $A, $B, $C, $infoSol, 'OK');
                                                    $pre_array = array($A, $getNewEecc['idEmpresaColab'], $getNewSubProyecto['idSubProyecto'], 1, $infoSol['idEmpresaColab'], $infoSol['idSubProyecto']);//eecc y subpro
                                                    array_push($array_full, $pre_array);
                                                    $indice++;
                                                }
                                            }else if($getNewSubProyecto['idTipoSubProyecto']    ==  2){//INTEGRAL
                                                if(in_array($getNewEecc['idEmpresaColab'], $array_eecc_integral)){
                                                    $html .= $this->getHtmlOk($indice, $A, $B, $C, $infoSol, 'OK');
                                                    $pre_array = array($A, $getNewEecc['idEmpresaColab'], $getNewSubProyecto['idSubProyecto'], 1, $infoSol['idEmpresaColab'], $infoSol['idSubProyecto']);//eecc y subpro
                                                    array_push($array_full, $pre_array);
                                                    $indice++;
                                                }else{
                                                    $html .= $this->getHtmlError($indice, $A, $B, $C, $infoSol, 'EECC No permitida para el subproyecto seleccionado');
                                                    $indice++;
                                                }
                                            }else{//CUALQUIER OTRA OBRA NORMAL
                                                $html .= $this->getHtmlOk($indice, $A, $B, $C, $infoSol, 'OK');
                                                $pre_array = array($A, $getNewEecc['idEmpresaColab'], $getNewSubProyecto['idSubProyecto'], 1, $infoSol['idEmpresaColab'], $infoSol['idSubProyecto']);//eecc y subpro
                                                array_push($array_full, $pre_array);
                                                $indice++;
                                            }
                                        }
                                    }else{
                                        if($infoSol['idTipoSubProyecto']    ==  1){//BUCLE
                                            if(in_array($getNewEecc['idEmpresaColab'], $array_eecc_integral)){
                                                $html .= $this->getHtmlError($indice, $A, $B, $C, $infoSol, 'EECC No permitada para el subproyecto actual');
                                                $indice++;
                                            }else{
                                                $html .= $this->getHtmlOk($indice, $A, $B, $C, $infoSol, 'OK');
                                                $pre_array = array($A, $getNewEecc['idEmpresaColab'], null, 2, $infoSol['idEmpresaColab'], $infoSol['idSubProyecto']);//eecc y subpro
                                                array_push($array_full, $pre_array);
                                                $indice++;
                                            }
                                        }else if($infoSol['idTipoSubProyecto']    ==  2){//INTEGRAL
                                            if(in_array($getNewEecc['idEmpresaColab'], $array_eecc_integral)){
                                                $html .= $this->getHtmlOk($indice, $A, $B, $C, $infoSol, 'OK');
                                                $pre_array = array($A, $getNewEecc['idEmpresaColab'], null, 2, $infoSol['idEmpresaColab'], $infoSol['idSubProyecto']);//eecc y subpro
                                                array_push($array_full, $pre_array);
                                                $indice++;
                                            }else{
                                                $html .= $this->getHtmlError($indice, $A, $B, $C, $infoSol, 'EECC No permitada para el subproyecto actual');
                                                $indice++;
                                            }
                                        }else{//CUALQUIER OTRA OBRA NORMAL
                                            $html .= $this->getHtmlOk($indice, $A, $B, $C, $infoSol, 'OK');
                                            $pre_array = array($A, $getNewEecc['idEmpresaColab'], null, 2, $infoSol['idEmpresaColab'], $infoSol['idSubProyecto']);//solo eecc
                                            array_push($array_full, $pre_array);
                                            $indice++;
                                        }
                                        
                                    }                                    
                                }
                            }
                        }
                    }else{
                        $html .= $this->getHtmlError($indice, $A, $B, $C, $infoSol, 'Itemplan en estado no valido, debe estar en Pre Registro');       
                        $indice++;
                    }                   
                                                     
                }else{//INVALIDO ITEMPLAN NO RECONOCIDO
                    $html .= $this->getHtmlError($indice, $A, $B, $C, $infoSol, 'Itemplan No reconocido o no existe.');
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
                    $arrayPoToUpdate =    array();     
                    $arrayLogEdiPO  = array();
                    if ($arrayFile != null) {
                        
                        foreach ($arrayFile as $datos) {
                            if ($datos != null) {                                
                               // $itemplan   = $datos[0];   
                               if($datos[3] ==  1){//con subproyecto
                                   $infoItemplan = array('itemplan'     => $datos[0],
                                                        'idEmpresaColab'=> $datos[1],
                                                        'idSubProyecto' => $datos[2]
                                   );                             
                               }else if($datos[3] ==  2){//solo eecc
                                   $infoItemplan = array('itemplan'         => $datos[0],
                                                        'idEmpresaColab'    => $datos[1]
                                   );
                               }else{
                                   throw new Exception('Se detectaron datos corruptos, comuniquese con CAP');
                               }
                               array_push($arrayPoToUpdate, $infoItemplan);
                               
                               // save log
                               $datalog = array('itemplan'          =>  $datos[0],
                                                'old_eecc'          =>  $datos[4],
                                                'old_subproyecto'   =>  $datos[5],
                                                'new_eecc'          =>  $datos[1],
                                                'new_subproyecto'   =>  $datos[2],
                                                'usuario'           =>  $idUsuario,
                                                'fecha'             =>  $this->fechaActual()
                               );
                               array_push($arrayLogEdiPO, $datalog);#log_cambio_eecc_sub_pro_masivo
                            }
                        }
                    } else {
                        throw new Exception('No se pudo procesar el archivo, refresque la pagina y vuelva a intentarlo.');
                    }
                    log_message('error', print_r($arrayPoToUpdate, true));
                    $data = $this->M_cambiar_eecc_subpro_itemplan_masivo->updatePlanObraMasivo($arrayPoToUpdate, $arrayLogEdiPO);
            } else {
                throw new Exception('Su sesion expiro, porfavor vuelva a logearse.');
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }	

}
