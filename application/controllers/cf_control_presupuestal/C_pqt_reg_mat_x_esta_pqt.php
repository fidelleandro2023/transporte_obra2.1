<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_pqt_reg_mat_x_esta_pqt extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_control_presupuestal/m_pqt_reg_mat_x_esta_pqt'); 
        $this->load->model('mf_pqt_terminado/m_pqt_terminado');        
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->library('excel');
        $this->load->helper('url');
    }
    
    public function index(){
        $logedUser = $this->session->userdata('usernameSession');
        if($logedUser != null){
            $item = (isset($_GET['ite']) ? $_GET['ite'] : '');            
            $idEstacion = (isset($_GET['est']) ? $_GET['est'] : '');  
            $codigo_po = (isset($_GET['po']) ? $_GET['po'] : '');
            $data['nombreUsuario']  =   $this->session->userdata('usernameSession');
            $data['perfilUsuario']  =   $this->session->userdata('descPerfilSession');
            $permisos =  $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, 237, 254, ID_MODULO_PAQUETIZADO);#padre,hijo,modelo
            $data['opciones'] = $result['html'];
			log_message('error','----->');
           // if($result['hasPermiso'] == true){//SI CUENTA CON LOS PERMISOS RECIEN INGRESAR                
                //PONER VALIDACIONES
                $infoItemEsta = $this->m_pqt_reg_mat_x_esta_pqt->getInfoEstacionItemplanToRegMatXEsta($item, $idEstacion);
                log_message('error', print_r($infoItemEsta,true));
                if($infoItemEsta!=null){
                    $data['itemplan']       =   $infoItemEsta['itemplan'];
                    $data['idEstacion']     =   $infoItemEsta['idEstacion'];
                    $data['estacionDesc']   =   $infoItemEsta['estacionDesc'];
                    $data['codigo_po']      =   $codigo_po;
                    $data['existReg']       =   (($infoItemEsta['id'] != null &&  ($infoItemEsta['estado'] == 3 || $infoItemEsta['estado'] == 1)) ? 1 : 0);
					log_message('error','<<<---->');
                    $this->load->view('vf_control_presupuestal/v_pqt_reg_mat_x_esta_pqt',$data);
                }else{
                    redirect('pqt_consulta','refresh');
                }
          /*                          
            }else{
                redirect('login','refresh');
            }*/
        }else{
            redirect('login','refresh');
        }             
    }
    
    
    public function getExcelMateriales()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
    
            $arrayMateriales = $this->m_pqt_reg_mat_x_esta_pqt->getMaterialesNuevoModelo();			
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
                    $titulosColumnas = array('TIPO MATERIAL','CODIGO MATERIAL', 'DESCRIPCION', 'COSTO', 'CANTIDAD INGRESADA');
    
                    $this->excel->setActiveSheetIndex(0);
    
                    // Se agregan los titulos del reporte
                    $this->excel->setActiveSheetIndex(0)
                    ->setCellValue('A1', utf8_encode($titulosColumnas[0]))
                    ->setCellValue('B1', utf8_encode($titulosColumnas[1]))
                    ->setCellValue('C1', utf8_encode($titulosColumnas[2]))
                    ->setCellValue('D1', utf8_encode($titulosColumnas[3]))
                    ->setCellValue('E1', utf8_encode($titulosColumnas[4]));
    
                    foreach ($arrayMateriales as $row) {
                        $contador++;
                        $this->excel->getActiveSheet()
                        ->setCellValue("A{$contador}", (($row->paquetizado == 1 ? 'NUEVO CONTRATO' : 'BUCLE')))
                        ->setCellValue("B{$contador}", $row->id_material)
                        ->setCellValue("C{$contador}", $row->descrip_material)
                        ->setCellValue("D{$contador}", $row->costo_material);
                        
                        $styleArray = array(
                            'font'  => array(                                
                                'color' => array('rgb' => ($row->paquetizado == 1 ? '#3c8823' : '#000000')),
                                'name'  => 'Calibri'
                            ));
                        $this->excel->getActiveSheet()->getStyle("A{$contador}")->applyFromArray($styleArray);
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
                    
                    $this->excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                    $this->excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                    $this->excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                    $this->excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                    
                    //Le ponemos un nombre al archivo que se va a generar.
                    $archivo = "partidas_mo_registro_individual_po.xls";
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="' . $archivo . '"');
                    header('Cache-Control: max-age=0');
                    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                    //Hacemos una salida al navegador con el archivo Excel.
                    $objWriter->save(PATH_FILE_UPLOAD_MATERIALES_X_ESTACION);
    
                    $data['rutaExcel'] = PATH_FILE_UPLOAD_MATERIALES_X_ESTACION;
                    $data['error'] = EXIT_SUCCESS;
    
     
                }else{
                    $data['msj'] = "No hay materiales configurados!!, porfavor conmunicarse con CAP";
                }
            } else {
                $data['msj'] = "No se encontraron materiales!!, porfavor conmunicarse con CAP";
            }
        } catch (Exception $e) {
            $data['msj'] = 'Error interno, al crear archivo Materiales.xls';
        }
        // return $data;
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function uploadMateriales(){
        $data ['error']= EXIT_ERROR;
        $data['msj'] = null;
        try{
    
            $uploaddir =  'uploads/mat_x_estacion/';//ruta final del file
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
    
    public function makeHTMLBodyTable($row_dimension, $objPHPExcel){
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
            $A = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1,$i,true)->getValue();#codigo_material
            $BB = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2,$i,true)->getValue();#descripcion
            $CC = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(3,$i,true)->getValue();#costo
            $D = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(4,$i,true)->getValue();#cantidad_registrada
            $A0 = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0,$i,true)->getValue();#tipo_material
            
            $total = 0;
            $B = '';
            $C = '';
            if($D!='' && $A!='CODIGO MATERIAL'){
                if(is_numeric($D)){
                    $infoPartida    =   $this->m_pqt_reg_mat_x_esta_pqt->getInfoCodigoMaterial($A);
                    if($infoPartida!=null){
                        $B = $infoPartida['descrip_material'];
                        $C = $infoPartida['costo_material'];
                        $color_row = "white";
                        $situacion = 'OK';
    
                        $total = ($C*$D);
                        $pre_array = array($A, $C, $D, $total, 1); //1 == insert
                        array_push($array_valido, $pre_array);
                        $indice_valido = 'data-indice_val="'.$cont_indice_valido.'"';
                        $cont_indice_valido++;
                        $total_final = $total_final + $total;
                    }else{
                        $color_row = "#b3b37b";
                        $situacion = 'MATERIAL NO PERTENECE AL NUEVO CONTRATO';
                        $B = $BB;#como no consultamos bd ponemos la descripcion del excel
                        $C = $CC;#como no consultamos bd ponemos el costo del excel
                        $pre_array = array($A, $D, 2); //2 == erroneos
                        array_push($array_not_found, $pre_array);
                    }
    
                }else{
                    $color_row = "#b3b37b";
                    $situacion = 'CANTIDAD INGRESADA INVALIDA, INGRESAR VALOR NUMERICO';
                    $B = $BB;#como no consultamos bd ponemos la descripcion del excel
                    $C = $CC;#como no consultamos bd ponemos el costo del excel
                    $pre_array = array($A, $D, 2); //2 == erroneos
                    array_push($array_not_found, $pre_array);
                }
    
                $html .= '<tr id="tr'.$indice.'" style="background-color:'.$color_row.'">
                            <th style="width: 5px;"><a style="cursor:pointer;" '.$indice_valido.' data-indice="'.$indice.'" onclick="removeTR(this)"><img class="delete_ptr" alt="Eliminar" height="20px" width="20px" src="public/img/iconos/delete.png"></a></th>
                        	<th style="color:black">'.$A0.'</th>
                            <th style="color:black">'.$A.'</th>
                        	<th style="color:black">'.utf8_decode($B).'</th>
                        	<th style="color:black">'.$C.'</th>
                    	    <th style="color:black">'.$D.'</th>
        	                <th style="color:black">'.number_format($total,2,'.', ',').'</th>
        	                <th style="color:black">'.$situacion.'</th>
                    	</tr>';
                $indice++;
    
                $pre_array = array($A, $B, $C, $D);
                array_push($array_full, $pre_array);
            }
    
        }
    
        $data['html']           = $html;
        $data['array']          = $array_valido;
        $data['array_nf']       = $array_not_found;
        $data['array_full']     = $array_full;
        $data['total_final']    = number_format($total_final,2,'.', ',');
        return $data;
    }
        
    public function fechaActual()
    {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone', 'America/Lima');
        setlocale(LC_TIME, "es_ES", "esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }
    
    
    public function saveMaterialesXEstacion(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;
            if($idUsuario   !=     null){
                $itemplan       = $this->input->post('item');
                $idEstacion     = $this->input->post('idEstacion');
                $jsonDataFile   = $this->input->post('jsonDataFile');
                $codigo_po      = $this->input->post('codigo_po');
                
                //primero validamos si cuenta con el disponible para el registro adicional de la partida.
                $infoCostoMax = $this->m_pqt_reg_mat_x_esta_pqt->getCostoMaxMatAndCostoMByItemplan($itemplan);
                if($infoCostoMax['monto']==null){
                    throw new Exception('El subproyecto no cuenta con un costo KIT MATERIAL FO configurado.');
                }
                if($infoCostoMax['costo_unitario_mo']==null || $infoCostoMax['costo_unitario_mo']== 0 ){
                    throw new Exception('Costo MO de la obra no valido, comuniquese con CAP.');
                }
                
                $hasSolActivo = $this->m_utils->hasSolExceActivo($itemplan, TIPO_PO_MANO_OBRA);
                if($hasSolActivo > 0){
                    throw new Exception('No se pueden aplicar los cambios, debido ah que cuenta con una Solicitud de Exceso Pendiente de Aprobacion.');
                }                
                
                $pathItemplan = 'uploads/evidencia_mat_x_estacion/'.$itemplan;
                if (!is_dir($pathItemplan)) {
                    mkdir ($pathItemplan, 0777);
                }
                    
                $descEstacion = $this->m_utils->getEstaciondescByIdEstacion($idEstacion);
                //DE NO EXISTIR LA CARPETA ITEMPLAN ESTACION LA CREAMOS
                $pathItemEstacion = $pathItemplan.'/'.$descEstacion;
                if (!is_dir($pathItemEstacion)) {
                    mkdir ($pathItemEstacion, 0777);
                }
                
                $uploadfile1 = $pathItemEstacion.'/'. basename($_FILES['fileEvi']['name']);
                    
                    if (move_uploaded_file($_FILES['fileEvi']['tmp_name'], $uploadfile1)) {
                        log_message('error', 'se movio:'.$uploadfile1);
                        $arrayFile = json_decode($jsonDataFile);
                        $arrayFinalUpdate = array();
                        $materialesDetalle = array();
                        $costoTotalMateriales = 0;
                        if($arrayFile!=null){
                            $costoTotalMatReg = 0;
                            foreach($arrayFile as $datos){
                                if($datos!=null){
                                    if($datos[4]!=null){
                                        if($datos[4]    ==  1){//registrar
                                            $dataCMO = array();
                                            $dataCMO['itemplan']                = $itemplan;
                                            $dataCMO['idEstacion']              = $idEstacion;
                                            $dataCMO['id_material']             = $datos[0];
                                            $dataCMO['costo_inicial_material']  = $datos[1];
                                            $dataCMO['cantidad_inicial']        = $datos[2];
                                            $dataCMO['monto_inicial']           = $datos[3];
                                            $dataCMO['costo_final_material']    = $datos[1];
                                            $dataCMO['cantidad_final']          = $datos[2];
                                            $dataCMO['monto_final']             = $datos[3];
                                            $dataCMO['usua_registro']           = $this->session->userdata('idPersonaSession');
                                            $dataCMO['fecha_registro']          = $this->fechaActual();
                                            array_push($materialesDetalle, $dataCMO);
                                            $costoTotalMateriales = $costoTotalMateriales + $dataCMO['monto_final'];
                                        }
                                    }
                                }
                            }
                             
                            $materialesPadre = array(
                                'itemplan'              => $itemplan,
                                'idEstacion'            => $idEstacion,
                                'costo_total_inicial'   => $costoTotalMateriales,
                                'costo_total_final'     => $costoTotalMateriales,
                                'usua_registro'         => $this->session->userdata('idPersonaSession'),
                                'fecha_registro'        => $this->fechaActual(),
                                'path_evidencia'        => $uploadfile1,
                                'estado'                => 0
                            );
                            
                            /*****************************obtenemos costo partida********************************/
                            $total_partida_ferreteria = 0;
                            if($costoTotalMateriales > $infoCostoMax['monto']){
                                $total_partida_ferreteria = $infoCostoMax['monto'];
                            }else{
                                $total_partida_ferreteria = $costoTotalMateriales;
                            }                             
                            
                            $infoCostosObra = $this->m_utils->getVariablesCostoUnitario($itemplan, TIPO_PO_MANO_OBRA, $codigo_po);
                            if($infoCostosObra==null){
                                throw new Exception('No se encontro informacion de la solicitud refresque y vuelva a intentarlo, de continuar el problema genere un ticket CAP.');
                            }
                            
                            $infoPo = $this->m_pqt_reg_mat_x_esta_pqt->getInfoPo($codigo_po);
                            if($infoPo==null){
                                throw new Exception('No se encontro informacion de la PO refresque y vuelva a intentarlo, de continuar el problema genere un ticket CAP.');
                            }
                            
                            $costoTotalAllPo        =  $infoCostosObra['total'];//costo actual de todas las po
                            $costoTotalPo           = $total_partida_ferreteria + $infoPo['monto_pqt'];
                            $nuevoCostoTotalAllPo   = $costoTotalAllPo + $costoTotalPo;
                            $costo_unit_mo          = $infoCostosObra['costo_unitario_mo'];
                            $genOcEdic = true;
                            if($nuevoCostoTotalAllPo <= $costo_unit_mo){
                                $genOcEdic = false;
                            }
                            
                            if($genOcEdic){
                                $exceso          = $nuevoCostoTotalAllPo - $costo_unit_mo;
                                $costo_final_sol = $costo_unit_mo+$exceso;
                            }else{
                                $exceso = $total_partida_ferreteria;//exceso de finta
                                $costo_final_sol = $costo_unit_mo+0;//0 porque no genera exceso
                            }
                                
                            $partidasOutPut = array();
                            $arrayActividades = array();
                            $codigoFerreteria = '69901-2';#ponerlo como constante
                            $infoPartida =  $this->m_pqt_terminado->getInfoPartidaByCodPartida($itemplan, $idEstacion, $codigoFerreteria);
                            if($infoPartida != null){
                                $precioTmp = $infoPartida['costo'];
                            }
                            
                            //$ferreteriaInfo = $this->m_pqt_terminado->getGroupFerreteria($itemplan, $idEstacion);
                            $cantidadFerr = 0;
                            $totalFerr = 0;
                           // if($ferreteriaInfo != null){
                                $totalFerr = $total_partida_ferreteria;
                                $cantidadFerr = ($totalFerr/$precioTmp);
                            
                                if(!in_array($infoPartida['idActividad'], $arrayActividades)){
                                    $dataCMO = array();
                                    $dataCMO['codigo_po']        = $codigo_po;
                                    $dataCMO['idActividad']      = $infoPartida['idActividad'];
                                    $dataCMO['baremo']           = $infoPartida['baremo'];
                                    $dataCMO['costo']            = $precioTmp;
                                    $dataCMO['cantidad_inicial'] = $cantidadFerr;
                                    $dataCMO['monto_inicial']    = $totalFerr;
                                    $dataCMO['cantidad_final']   = $cantidadFerr;
                                    $dataCMO['monto_final']      = $totalFerr;
                                    array_push($arrayActividades, $infoPartida['idActividad']);//metemos idActividad
                                    array_push($partidasOutPut, $dataCMO);
                                }
                           // }
                            /*************************************************************/
                                $dataSolExceso = array (   'itemplan'          =>  $itemplan,
                                    'codigo_po'         =>  $codigo_po,
                                    'idEstacion'        =>  $idEstacion,
                                    'tipo_po'           =>  2,
                                    'costo_inicial'     =>  $costo_unit_mo,
                                    'exceso_solicitado' =>  $exceso,
                                    'costo_final'       =>  $costo_final_sol,
                                    'usuario_solicita'  =>  $idUsuario,
                                    'fecha_solicita'    =>  $this->fechaActual(),
                                    'origen'            =>  5,//ADICIONALES PQT,
                                    'genSolEdic'        =>  (($genOcEdic) ? 1 : 0),
                                    'isFerreteria'      =>  1
                                );
                                
                                $data = $this->m_pqt_reg_mat_x_esta_pqt->createSolExcesoPartidasAdicionalesPqt($partidasOutPut, $dataSolExceso, $itemplan, $idEstacion, $materialesPadre, $materialesDetalle);
                                
                            //log_message('error', print_r($dataDetalleplan, true));
                            //$data = $this->m_pqt_reg_mat_x_esta_pqt->registrarMaterialesXEstacion($materialesPadre, $arrayFinalInsert, $itemplan, $idEstacion, $partidasOutPut);
                            if($data['error']   ==  EXIT_ERROR){
                                unlink($uploadfile1);
                                throw new Exception('Hubo un error interno, por favor volver a intentar.');
                            }
                            $data['error']       = EXIT_SUCCESS;
                        
                    }else{
                        unlink($uploadfile1);
                        throw new Exception('No se pudo procesar el archivo de materiales, refresque la pagina y vuelva a intentarlo.');
                    }
                }else {
                    throw new Exception('Hubo un problema con la carga del archivo 1 al servidor, comuniquese con el administrador.');
                }             
            }else{
                throw new Exception('Su sesion expiro, porfavor vuelva a logearse.');
            }
             
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getMaterialesPqt() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            if($this->session->userdata('idPersonaSession') != null){
                $itemplan       = ($this->input->post('itemplan')=='')      ? null : $this->input->post('itemplan');
                $idEstacion     = ($this->input->post('idEstacion')=='')    ? null : $this->input->post('idEstacion');
                log_message('error', $itemplan.' - '. $idEstacion);
                $data['tablaMateriales'] = $this->getTablaMateriales($this->m_pqt_reg_mat_x_esta_pqt->getMaterialesByIdEstacionItemplan($itemplan, $idEstacion));
                $data['error'] = EXIT_SUCCESS;
            }else{
                throw new Exception('La session expiro, vuelva a iniciar Sesion.');
            }
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getTablaMateriales($data) {
        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>                            
                            <th>CODIGO</th>
                            <th>MATERIAL</th>
                            <th>COSTO</th>
                            <th>CANTIDAD</th>
                            <th>TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>';
        if($data!=null){
            foreach($data as $row){
                $html .=' <tr>                                    
                            <td>'.$row->id_material.'</td>
                            <td>'.$row->descrip_material.'</td>
                            <td>'.$row->costo_final_material.'</td>
                            <td>'.$row->cantidad_final.'</td>
                            <td>'.number_format($row->monto_final,2).'</td>
                        </tr>';
            }
        }
        $html .='</tbody>
                </table>';
    
        return $html;
    }

}