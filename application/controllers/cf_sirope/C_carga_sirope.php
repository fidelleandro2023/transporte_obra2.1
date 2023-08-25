<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_carga_sirope extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_sirope/m_carga_sirope');       
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->library('excel');
        $this->load->helper('url');
    }
    
    public function index(){
        $logedUser = $this->session->userdata('usernameSession');
        if($logedUser != null){
            /*
            $eecc = '';            
            $idEECC = $this->session->userdata('eeccSession');            
            if($idEECC  ==  ID_EECC_COBRA){
                $eecc   =   'COBRA';
            }else if($idEECC  ==  ID_EECC_LARI){
                $eecc   =   'LARI';
            }else if($idEECC  ==  ID_EECC_DOMINION){
                $eecc   =   'DOMINIONPERU SOLUCIONES Y SERVICIOS S.A.C.';             
            }else if($idEECC  ==  ID_EECC_EZENTIS){
                $eecc   =   'CALATEL';                
            }            
            $data['eecc']    =   $eecc;*/
           // $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_carga_sirope->getBandejaAlarmasMO($eecc));
            $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
            $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
            $permisos =  $this->session->userdata('permisosArbol');
            #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_TRANFERENCIAS, ID_PERMISO_HIJO_CARGA_MASIVA_SIROPE);
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_NUEVO_MODELO_TRANSFERENCIAS, ID_PERMISO_HIJO_CARGA_MASIVA_SIROPE, ID_MODULO_PAQUETIZADO);
            $data['opciones'] = $result['html'];
            if($result['hasPermiso'] == true){
                $this->load->view('vf_sirope/v_carga_sirope',$data);
            }else{
                redirect('login','refresh');
            }
        }else{
            redirect('login','refresh');
        }             
    }
    
    public function uploadFileOC(){
        $data ['error']= EXIT_ERROR;
        $data['msj'] = null;
        try{
            $uploaddir =  'uploads/orden_compra/';//ruta final del file
            $uploadfile = $uploaddir . basename($_FILES['file']['name']);
            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {

                $objPHPExcel = PHPExcel_IOFactory::load($uploadfile);

                $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
                $row_dimension = $objPHPExcel->getActiveSheet()->getHighestRowAndColumn();
                
                $info_2 = $this->makeHTMLBodyTable($row_dimension, $objPHPExcel);
                $data['tablaData'] = $info_2['html'];
                $data['jsonDataFIleValido'] = json_encode($info_2['array']);
                $data['jsonDataFIle'] = json_encode($info_2['array_full']);
                $this->session->set_userdata('sirope_NF',$info_2['array_nf']);
                $data['error'] = EXIT_SUCCESS;
    
               
            } else {
                throw new Exception('Hubo un problema con la carga del archivo al servidor, comuniquese con el administrador.');
            }
    
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function makeHTMLBodyTable($row_dimension, $objPHPExcel){
        $data['html'] = '';
        $data['array'] = '';
        $data['array_full'] = '';
        $html = '';
        $indice = 0;
        $cont_indice_valido = 0;        
        $array_valido = array();
        $array_not_found = array();
        $array_full = array();
        
        for ($i = 1; $i <= $row_dimension['row']; $i++){//COMIENZA DESDE LA FILA 1
            $A = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0,$i,true)->getValue();
            $B = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1,$i,true)->getValue();
            $C = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2,$i,true)->getValue();
            $D = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(3,$i,true)->getValue();
            $E = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(4,$i,true)->getValue();
            $F = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(5,$i,true)->getValue();
            $G = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(6,$i,true)->getValue();
            $H = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(6,$i,true)->getValue();//DEJO DE  VENIR 07.03.2019
            $I = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(7,$i,true)->getValue();
            $J = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(8,$i,true)->getValue();
            $K = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(9,$i,true)->getValue();
            $L = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(10,$i,true)->getValue();
        
             /* if(strpos(trim($A), 'INFRA') !== false) {
               $color_row = "red";//DATO INVALIDO O PTR NO ENCONTRADA
                $situacion = 'NO ENCONTRADO';
                $indice_valido = '';
            }else 
            */
            $is_valido = false;
            $codigo_ot = trim($A);
            $length_ot = strlen($codigo_ot);
            if($length_ot==15){//itemplanFO %% itemplanAC
                $ult_2 = substr(trim($A), 13, 15);
                if($ult_2 == 'FO' || $ult_2 == 'AC'){//validos
                    $is_valido = true;
                }
            }
            if($is_valido){
                $infoPtr = $this->m_carga_sirope->getInfoItem(substr(trim($A), 0, 13), $I);
               
                if($infoPtr!=null){
                    if($infoPtr['itemplan']!=null){
                        if($infoPtr['item_sirope']!=null){
                            $color_row = "orange";
                            $situacion = 'ACTUALIZAR';
                            $pre_array = array($A, $B, $C, $D, $E, $F, $G, $H, $I, $J, $K, $L, 1);// 1 == update
                            array_push($array_valido, $pre_array);
                            $indice_valido = 'data-indice_val="'.$cont_indice_valido.'"';
                            $cont_indice_valido++;
    
                        }else{
                            $color_row = "green";
                            $situacion = 'OK';
                            $pre_array = array($A, $B, $C, $D, $E, $F, $G, $H, $I, $J, $K, $L, 2); //2 == insert
                            array_push($array_valido, $pre_array);
                            $indice_valido = 'data-indice_val="'.$cont_indice_valido.'"';
                            $cont_indice_valido++;
                        }
                        
                    }else{
                        $color_row = "#b3b37b";
                        $situacion = 'SIN H.G';
                        $pre_array = array($A, $B, $C, $D, $E, $F, $G, $H, $I, $J, $K, $L, 3); //3 == erroneos
                    array_push($array_not_found, $pre_array);
                    }
                }else{
                    $color_row = "red";//DATO INVALIDO O PTR NO ENCONTRADA
                    $situacion = 'NO ENCONTRADO';
                    $indice_valido = '';
                }
            }else if(strpos(trim($A), 'INFRA') !== false){
                $color_row = "red";//DATO INVALIDO O PTR NO ENCONTRADA
                $situacion = 'NO VALIDO';
                $indice_valido = '';
            }else{
                $color_row = "red";//DATO INVALIDO O PTR NO ENCONTRADA
                $situacion = 'NO VALIDO';
                $indice_valido = '';
            }
            
            if($is_valido){
                $html .= '<tr id="tr'.$indice.'" style="background-color:'.$color_row.'">
                            <th style="width: 5px;"><a style="cursor:pointer;" '.$indice_valido.' data-indice="'.$indice.'" onclick="removeTR(this)"><img class="delete_ptr" alt="Eliminar" height="20px" width="20px" src="public/img/iconos/delete.png"></a></th>
                        	<th style="color:white">'.$A.'</th>
                        	<th style="color:white">'.$C.'</th>
                        	<th style="color:white">'.$E.'</th>
                    	    <th style="color:white">'.$situacion.'</th>
                    	</tr>';
            }
                $indice++;
                
                $pre_array = array($A, $B, $C, $D, $E, $F, $G, $H, $I, $J, $K, $L);
                array_push($array_full, $pre_array);
        }     
        
        $data['html'] = $html;
        $data['array'] = $array_valido;
        $data['array_nf']   =   $array_not_found;
        $data['array_full'] = $array_full;
        return $data;
    }
    
    
    public function saveSI(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $this->db->trans_begin();
            //$jsonDataFile   = $this->input->post('jsonDataFile');
            //$arrayFile = json_decode($jsonDataFile);
            $jsonDataFile   = file_get_contents('php://input');
            $arrayFile = json_decode( $jsonDataFile, TRUE );
            $arrayFinalUpdate = array();
            $arrayFinalInsert = array();
            if($arrayFile!=null){
                foreach($arrayFile as $datos){
                    //log_message("error", '$datos_minimos:'.print_r($datos[0], true));
                    if($datos!=null){
                        //log_message("error", '$datos Validos:'.print_r($datos, true));
                        //$infoPtr = $this->m_carga_sirope->getInfoItem(substr(trim($datos[0]), 0, 13),   $datos[8]);                         
                        if($datos[12]!=null){
                                $itemplan = substr(trim($datos[0]), 0, 13);
                                if($datos[12]   ==  1){
                                    $dataCMO = array();
                                    $dataCMO['codigo']                  = trim($datos[0]);
                                    $dataCMO['descripcion']             = $datos[1];
                                    $dataCMO['caracteristica']          = $datos[2];
                                    $dataCMO['distrito']                = $datos[3];
                                    $dataCMO['estado_actual']           = $datos[4];
                                    $dataCMO['fecha_implantacion']      = $datos[5];                                    
                                    $dataCMO['fecha_inicial']           = $datos[6];
                                    $dataCMO['fecha_inicial_1']         = $datos[7];
                                    $dataCMO['id']                      = $datos[8];
                                    $dataCMO['fecha_prevision_finalizacion']    = $datos[9];                                    
                                    $dataCMO['sitio']                   = $datos[10];
                                    $dataCMO['sub_estado']              = $datos[11];
                                    $dataCMO['itemplan']                = substr(trim($datos[0]), 0, 13);
                                    $dataCMO['fecha_actualizacion']     = date("Y-m-d H:m:s");
                                    $dataCMO['usuario_actualizacion']   = $this->session->userdata('userSession');
                                    array_push($arrayFinalUpdate, $dataCMO);
                                }else if ($datos[12]    ==  2){
                                    $dataCMO = array();
                                    $dataCMO['codigo']                  = trim($datos[0]);
                                    $dataCMO['descripcion']             = $datos[1];
                                    $dataCMO['caracteristica']          = $datos[2];
                                    $dataCMO['distrito']                = $datos[3];
                                    $dataCMO['estado_actual']           = $datos[4];
                                    $dataCMO['fecha_implantacion']      = $datos[5];                                    
                                    $dataCMO['fecha_inicial']           = $datos[6];
                                    $dataCMO['fecha_inicial_1']         = $datos[7];
                                    $dataCMO['id']                      = $datos[8];
                                    $dataCMO['fecha_prevision_finalizacion']    = $datos[9];                                    
                                    $dataCMO['sitio']                   = $datos[10];
                                    $dataCMO['sub_estado']              = $datos[11];
                                    $dataCMO['itemplan']                = substr(trim($datos[0]), 0, 13);
                                    $dataCMO['fecha_registro']          = date("Y-m-d H:m:s");
                                    $dataCMO['usua_registro']           = $this->session->userdata('userSession');
                                    array_push($arrayFinalInsert, $dataCMO);
                                }
                           
                        }
                    }
                }
            }
            $data = $this->m_carga_sirope->liquidarOCptrCertificacion($arrayFinalInsert, $arrayFinalUpdate);
            $this->m_utils->analizarSiropeDiseno(265);         
            $this->db->trans_commit();
            $data['error']    = EXIT_SUCCESS;        
        }catch(Exception $e){
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
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