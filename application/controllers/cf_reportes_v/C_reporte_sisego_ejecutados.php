<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_reporte_sisego_ejecutados extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('mf_reportes_v/m_reporte_sisego_ejecutados');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->library('excel');
        $this->load->helper('url');
    }

    function index() {
		$from = (isset($_GET['from']) ? $_GET['from'] : null);
        $data['tablaReporte'] = $this->getTablaReporteSisego($from);		
        $this->load->view('vf_reportes_v/v_reporte_sisego_ejecutados', $data);
    }

    function getTablaReporteSisego($from) {
        $arrayData = $this->m_reporte_sisego_ejecutados->getTablaReporteSisego();
        $total_sin_ppto = 0;        $tota_pnd_getec = 0;        $total_des_dise = 0;        $total_des_lice = 0;        $total_des_apro = 0;        $total_des_obra = 0;
        $total_des_acel = 0;        $total_paraliza = 0;        $total_con_ppto = 0;        $total_en_gesti = 0;        $total_terminad = 0;        $total_trunco   = 0;
        $total_final    = 0;        $total_des_x_zonal = 0;     $total_cancela = 0;
        $style_total = 'style="color: white;    background-color: #5eb3dc;    font-weight: bolder;    text-align: center;"';
        $style_cabecera = 'style="color: white; background-color: #004062;  font-weight: bolder;    text-align: center;"';
        $style_numeros = 'style="font-weight: bolder;    text-align: center;"';
        $styleCancelados =     'style="background-color: #bfb03f;    color: white;    font-weight: bold;"';
        $html = '<table id="data-table2" class="table table-bordered">
                    <thead class="thead-default">
                        <tr role="row">                    
                            <th '.$style_cabecera.' rowspan="2">REGION</th>
                            <th '.$style_cabecera.' rowspan="2">SIN PRESUPUESTO</th>
                            <th '.$style_cabecera.' rowspan="2">PEND. OC (GETEC)</th>
                            <th '.$style_cabecera.' colspan="5">DESPLIEGUE RED</th>
                            <th '.$style_cabecera.' colspan="1" rowspan="2">DESPLIEGUE ACELERA</th>
                            <th '.$style_cabecera.' colspan="1" rowspan="2">PARALIZADOS</th>
                            <th '.$style_cabecera.' colspan="1" rowspan="2">TOTAL CON PPTO</th>
                            <th '.$style_cabecera.' colspan="1" rowspan="2">TOTAL EN GESTION</th>
                            <th style="text-align: center;  font-weight: bold;  color: #e7f518;" colspan="1" rowspan="2"><a onclick="'.(($from==null ? 'getTerminados2()' : 'getTerminados()')).'">IP TERMINADO</a></th>
                            <th '.$style_cabecera.' colspan="1" rowspan="2">IP TRUNCO</th>
                            <th '.$style_cabecera.' colspan="1" rowspan="2">TOTAL</th> 
                            <th '.$style_cabecera.' colspan="1" rowspan="2"></th> 
                            <th '.$style_cabecera.' colspan="1" rowspan="2">CANCELADO</th>               
                       </tr>
                       <tr role="row">
                            
                            
                            <th '.$style_cabecera.' colspan="1">DISENO</th>                            
                            <th '.$style_cabecera.' colspan="1">EN LICENCIA</th>                          
                            <th '.$style_cabecera.' colspan="1">EN APROBACION</th>
	                        <th '.$style_cabecera.' colspan="1">EN OBRA</th>	
                            <th '.$style_cabecera.' colspan="1">TOTAL</th>                    
                            
                        </tr>
                    </thead>                    
                    <tbody>';
                    if($arrayData!= null){                                                                                                     
                        foreach($arrayData as $row){    
                            $total_con_ppto_tmp = 0;
                            $total_con_ppto_tmp = ($row->pdt_gtec+$row->despliege_diseno+$row->despliege_licencia+$row->despliege_aprob+$row->despliege_obra+$row->acelera+$row->despliege_paralizado);       
                            $total_gestion_tmp = ($total_con_ppto_tmp+$row->sin_ppto);
                            $total_final_tmp = ($total_con_ppto_tmp+$row->terminado+$row->trunco+$row->sin_ppto);
                            $total_x_zonal_despligue = ($row->despliege_diseno+$row->despliege_licencia+$row->despliege_aprob+$row->despliege_obra);
                            
                            $html .=' <tr '.$style_numeros.'>
                                        <td '.$style_cabecera.'>'.$row->region.'</td>
                                        <td><a data-tipo="12" data-region="'.$row->region.'" onclick="exportDetalleReporte(this)">'.$row->sin_ppto.'</a></td>
                                        <td><a data-tipo="13" data-region="'.$row->region.'" onclick="exportDetalleReporte(this)">'.$row->pdt_gtec.'</a></td>
                                        <td><a data-tipo="14" data-region="'.$row->region.'" onclick="exportDetalleReporte(this)">'.$row->despliege_diseno.'</a></td>
                                        <td><a data-tipo="15" data-region="'.$row->region.'" onclick="exportDetalleReporte(this)">'.$row->despliege_licencia.'</a></td>
                                        <td><a data-tipo="16" data-region="'.$row->region.'" onclick="exportDetalleReporte(this)">'.$row->despliege_aprob.'</a></td>
                                        <td><a data-tipo="17" data-region="'.$row->region.'" onclick="exportDetalleReporte(this)">'.$row->despliege_obra.'</a></td>
                                        <td '.$style_total.'><a data-tipo="18" data-region="'.$row->region.'" onclick="exportDetalleReporte(this)">'.$total_x_zonal_despligue.'</a></td>
                                        <td><a data-tipo="19" data-region="'.$row->region.'" onclick="exportDetalleReporte(this)">'.$row->acelera.'</a></td>
                                        <td><a data-tipo="20" data-region="'.$row->region.'" onclick="exportDetalleReporte(this)">'.$row->despliege_paralizado.'</a></td>
                                        <td '.$style_total.'>'.$total_con_ppto_tmp.'</td>
                                        <td '.$style_total.'>'.$total_gestion_tmp.'</td>  
                                        <td><a data-tipo="21" data-region="'.$row->region.'" onclick="exportDetalleReporte(this)">'.$row->terminado.'</a></td>
                                        <td><a data-tipo="22" data-region="'.$row->region.'" onclick="exportDetalleReporte(this)">'.$row->trunco.'</a></td>  
                                        <td '.$style_total.'>'.$total_final_tmp.'</td>   
                                        <td></td>
                                        <td '.$styleCancelados.'><a data-tipo="24" data-region="'.$row->region.'" onclick="exportDetalleReporte(this)">'.$row->cancelado.'</a></td>                                  
                                    </tr>';
                            $total_sin_ppto =   ($total_sin_ppto +   $row->sin_ppto);
                            $tota_pnd_getec =   ($tota_pnd_getec +   $row->pdt_gtec);
                            $total_des_dise =   ($total_des_dise +   $row->despliege_diseno);
                            $total_des_lice =   ($total_des_lice +   $row->despliege_licencia);
                            $total_des_apro =   ($total_des_apro +   $row->despliege_aprob);
                            $total_des_obra =   ($total_des_obra +   $row->despliege_obra);
                            $total_des_acel =   ($total_des_acel +   $row->acelera);
                            $total_paraliza =   ($total_paraliza +   $row->despliege_paralizado);
                            $total_con_ppto =   ($total_con_ppto +   $total_con_ppto_tmp);
                            $total_en_gesti =   ($total_en_gesti +   $total_gestion_tmp);
                            $total_terminad =   ($total_terminad +   $row->terminado);
                            $total_trunco   =   ($total_trunco   +   $row->trunco);
                            $total_final    =   ($total_final    +   $total_final_tmp);
                            $total_cancela  =   ($total_cancela  +   $row->cancelado);
                            $total_des_x_zonal  =   ($total_des_x_zonal + $total_x_zonal_despligue);
                            
                        }
                    }
                    
                    $html .=' <tr role="row" '.$style_cabecera.'>
                                        <td '.$style_cabecera.' rowspan="2">TOTAL ITEMPLAN</td>
                                        <td '.$style_cabecera.' rowspan="2"><a data-tipo="1" onclick="exportDetalleReporte(this)">'.$total_sin_ppto.'</a></td>
                                        <td '.$style_cabecera.' rowspan="2"><a data-tipo="2" onclick="exportDetalleReporte(this)">'.$tota_pnd_getec.'</a></td>
                                        <td '.$style_cabecera.' ><a data-tipo="8" onclick="exportDetalleReporte(this)">'.$total_des_dise.'</a></td>
                                        <td '.$style_cabecera.' ><a data-tipo="9" onclick="exportDetalleReporte(this)">'.$total_des_lice.'</a></td>
                                        <td '.$style_cabecera.' ><a data-tipo="10" onclick="exportDetalleReporte(this)">'.$total_des_apro.'</a></td>
                                        <td '.$style_cabecera.' ><a data-tipo="11" onclick="exportDetalleReporte(this)">'.$total_des_obra.'</a></td>
                                        <td '.$style_cabecera.' ><a data-tipo="3" onclick="exportDetalleReporte(this)">'.$total_des_x_zonal.'</a></td>
                                        <td '.$style_cabecera.' rowspan="2"><a data-tipo="4" onclick="exportDetalleReporte(this)">'.$total_des_acel.'</a></td>
                                        <td '.$style_cabecera.' rowspan="2"><a data-tipo="5" onclick="exportDetalleReporte(this)">'.$total_paraliza.'</a></td>
                                        <td '.$style_cabecera.' rowspan="2">'.$total_con_ppto.'</td>
                                        <td '.$style_cabecera.' rowspan="2">'.$total_en_gesti.'</td>
                                        <td '.$style_cabecera.' rowspan="2"><a data-tipo="6" onclick="exportDetalleReporte(this)">'.$total_terminad.'</a></td>
                                        <td '.$style_cabecera.' rowspan="2"><a data-tipo="7" onclick="exportDetalleReporte(this)">'.$total_trunco.'</a></td>
                                        <td '.$style_cabecera.' rowspan="2">'.$total_final.'</td>
                                        <td '.$style_cabecera.' rowspan="2"></td>    
                                        <td '.$style_cabecera.' rowspan="2"><a data-tipo="23" onclick="exportDetalleReporte(this)">'.$total_cancela.'</a></td>    $
                                    </tr>
                            <!--<tr role="row" style="text-align: center; color: white; background-color: #004062;">
                                <th colspan="4">'.($total_des_dise+$total_des_lice+$total_des_apro+$total_des_obra).'</th>
                            </tr>-->';
                $html .='</tbody>
                    </table>';
                    log_message('error', $html);
            return $html;
    }
    
    public function generarCsvDetalleReporte() {_log("ENTRO REPORTE");
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $tipo   = $this->input->post('tipo')    ? $this->input->post('tipo') : null;
            $region = $this->input->post('region')  ? $this->input->post('region') : null;
            log_message('error', 'TIPO3:'.$tipo);
            ini_set('max_execution_time', 10000);
            ini_set('memory_limit', '2048M');
        
            $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
            $cacheSettings = array('memoryCacheSize ' => '5000MB', 'cacheTime' => '1000');
            PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
        
            //$this->excel->setActiveSheetIndex(0);
            $this->excel->getActiveSheet()->setTitle('Detalle Sisegos');
            //$this->excel->setActiveSheetIndex(0);        
            // Se agregan los titulos del reporte
            $this->excel->setActiveSheetIndex(0)
            ->setCellValue('A1', utf8_encode('ITEMPLAN'))
            ->setCellValue('B1', utf8_encode('SUB PROYECTO'))
            ->setCellValue('C1', utf8_encode('INDICADOR'))
            ->setCellValue('D1', utf8_encode('NOMBRE'))
            ->setCellValue('E1', utf8_encode('JEFATURA'))
            ->setCellValue('F1', utf8_encode('ZONA'))
            ->setCellValue('G1', utf8_encode('EECC'))
            ->setCellValue('H1', utf8_encode('ESTADO IP'))
            ->setCellValue('I1', utf8_encode('MARCA PARALIZADO'))
            ->setCellValue('J1', utf8_encode('MOTIVO PARALIZADO'))
            ->setCellValue('K1', utf8_encode('MARCA PRESUPUESTO'))
            ->setCellValue('L1', utf8_encode('RESPONSABILIDAD'))
            ->setCellValue('M1', utf8_encode('SOLICITUD OC'))
            ->setCellValue('N1', utf8_encode('ORDEN COMPRA'))
            ->setCellValue('O1', utf8_encode('ESTADO OC'))
            ->setCellValue('P1', utf8_encode('MONTO OC'))
            ->setCellValue('Q1', utf8_encode('PEP2'))
            ->setCellValue('R1', utf8_encode('GRAFO'))
            ->setCellValue('S1', utf8_encode('FECHA TERMINADO'))
            ->setCellValue('T1', utf8_encode('FECHA TRUNCO O CANCELADO'));
        
            $estiloTituloColumnas = array(
                'font' => array(
                    'name' => 'Calibri',
                    'bold' => true,
                    'color' => array(
                        'rgb' => '000000',
                    ),
                ));
        
            $this->excel->getActiveSheet()->getStyle('A1:AB1')->applyFromArray($estiloTituloColumnas);
            
            $infoRerpot = $this->m_reporte_sisego_ejecutados->getDataToExcelReport($tipo, $region);
          
            $index_excel = 2;
            if($infoRerpot != null){
                foreach($infoRerpot as $row){
                    $this->excel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$index_excel, utf8_encode($row->itemplan))
                    ->setCellValue('B'.$index_excel, utf8_encode($row->subProyectoDesc))
                    ->setCellValue('C'.$index_excel, utf8_encode($row->indicador))
                    ->setCellValue('D'.$index_excel, utf8_encode($row->nombreProyecto))
                    ->setCellValue('E'.$index_excel, utf8_encode($row->jefatura))
                    ->setCellValue('F'.$index_excel, utf8_encode($row->zonalDesc))
                    ->setCellValue('G'.$index_excel, utf8_encode($row->empresaColabDesc))
                    ->setCellValue('H'.$index_excel, utf8_encode($row->estadoPlanDesc))
                    ->setCellValue('I'.$index_excel, utf8_encode($row->has_paralizado))
                    ->setCellValue('J'.$index_excel, utf8_encode($row->motivoDesc))
                    ->setCellValue('K'.$index_excel, utf8_encode($row->has_presupuesto))
                    ->setCellValue('L'.$index_excel, utf8_encode($row->responsabilidad))
                    ->setCellValue('M'.$index_excel, utf8_encode($row->sol_crea))
                    ->setCellValue('N'.$index_excel, utf8_encode($row->orden_compra))
                    ->setCellValue('O'.$index_excel, utf8_encode($row->estado_oc))
                    ->setCellValue('P'.$index_excel, utf8_encode($row->monto_oc))
                    ->setCellValue('Q'.$index_excel, utf8_encode($row->pep2))
                    ->setCellValue('R'.$index_excel, utf8_encode($row->grafo))
                    ->setCellValue('S'.$index_excel, utf8_encode($row->fechaPreliquidacion))
                    ->setCellValue('T'.$index_excel, utf8_encode($row->fec_trunc_cancel));
                    $index_excel++;
                }
            }                       
            
            //Le ponemos un nombre al archivo que se va a generar.
            $archivo = "solicitud_oc.xls";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $archivo . '"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
            //Hacemos una salida al navegador con el archivo Excel.
        
            $nombreFile = 'download/reporte_sisego/detalle_reporte_' . date("YmdHis") . '.xls';
            $objWriter->save($nombreFile);
            $data['rutaExcel'] = $nombreFile;
            $data['error'] = EXIT_SUCCESS;
        
        } catch (Exception $e) {
            $data['msj'] = 'Error interno, al crear archivo detalle reporte sisegos!';
        }
        // return $data;
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function getDetalleTerminados()
    {_log("ENTRO REPORTE TERMINADO");
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {   
                $jsonArray = array();
                $lisaTerminados = $this->m_reporte_sisego_ejecutados->getDetalleTerminados();
                $data['tablaDetItemplan'] = $this->makeHTLMTablaDetalletecnico($lisaTerminados);
                
                $litaDatos = $this->m_reporte_sisego_ejecutados->getDataPieTerminadosPPTO();
                $total_obras = 0;
                foreach($litaDatos as $row){
                   $total_obras = ($total_obras+$row->num);
                }
                
                foreach($litaDatos as $row){               
                    $dato1 = array();
                    $dato1['name']  = $row->has_ppto;
                    $dato1['y']     = (float)(($row->num*100)/$total_obras);
                    array_push($jsonArray, $dato1);                  
                }                
          
                $data['dataPie'] = json_encode($jsonArray);
                $data['totalObras'] = $total_obras;
                $data['error'] = EXIT_SUCCESS;            
    
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function makeHTLMTablaDetalletecnico($listaPTR){
        
        $total_pdt_expe = 0;        $total_en_validacion = 0;        $total_en_certifica = 0;        $total_certificado = 0; $total_responsable = 0;
        $html = '<table id="data-table2" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th></th>
                            <th>PDTE ENTREGA EXPEDIENTE</th>
                            <th>EN VALIDACION</th>
                            <th>EN CERTIFICACION</th>
                            <th>CERTIFICADO</th>
                            <th>TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>';
        foreach($listaPTR as $row){
    
            $total_tmp              = ($row->pdt_expe+$row->en_vali+$row->en_certi+$row->certi);
            $total_pdt_expe         = ($total_pdt_expe+$row->pdt_expe);
            $total_en_validacion      = ($total_en_validacion+$row->en_vali);
            $total_en_certifica     = ($total_en_certifica+$row->en_certi);
            $total_certificado    = ($total_certificado+$row->certi);
            $total_responsable      = ($total_responsable+$total_tmp);
            $html .=' <tr>                         
                            <td style="text-align: center;">'.$row->origen.'</td>
                            <td style="text-align: center;">'.$row->pdt_expe.'</td>
                            <td style="text-align: center;">'.$row->en_vali.'</td>
                            <td style="text-align: center;">'.$row->en_certi.'</td>
                            <td style="text-align: center;">'.$row->certi.'</td>
                            <td style="text-align: center;">'.$total_tmp.'</td>
                      </tr>';
            
        }
        $html .=' <tr style="color: white;background: var(--celeste_telefonica);">
                            <td>TOTAL</td>
                            <td style="text-align: center;"><a data-tipo="1" onclick="exportDetalleReportePdtCert(this)">'.$total_pdt_expe.'</a></td>
                            <td style="text-align: center;"><a data-tipo="2"  onclick="exportDetalleReportePdtCert(this)">'.$total_en_validacion.'</a></td>
                            <td style="text-align: center;"><a data-tipo="3" onclick="exportDetalleReportePdtCert(this)">'.$total_en_certifica.'</a></td>
                            <td style="text-align: center;"><a data-tipo="4" onclick="exportDetalleReportePdtCert(this)">'.$total_certificado.'</a></td>
                            <td style="text-align: center;">'.$total_responsable.'</td>
                      </tr>';
        $html .='</tbody>
                </table>';
        return $html;
    }
    
    public function generarCsvDetallePie() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $situacion   = $this->input->post('situacion')    ? $this->input->post('situacion') : null;
            log_message('error', 'TIPO1:'.$situacion);
            ini_set('max_execution_time', 10000);
            ini_set('memory_limit', '2048M');
    
            $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
            $cacheSettings = array('memoryCacheSize ' => '5000MB', 'cacheTime' => '1000');
            PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
    
            //$this->excel->setActiveSheetIndex(0);
            $this->excel->getActiveSheet()->setTitle('Detalle Sisegos');
            //$this->excel->setActiveSheetIndex(0);
            // Se agregan los titulos del reporte
            $this->excel->setActiveSheetIndex(0)
            ->setCellValue('A1', utf8_encode('ITEMPLAN'))
            ->setCellValue('B1', utf8_encode('INDICADOR'))
            ->setCellValue('C1', utf8_encode('NOMBRE'))
            ->setCellValue('D1', utf8_encode('JEFATURA'))
            ->setCellValue('E1', utf8_encode('ZONA'))
            ->setCellValue('F1', utf8_encode('EECC'))
            ->setCellValue('G1', utf8_encode('ESTADO IP'))
            ->setCellValue('H1', utf8_encode('MARCA PARALIZADO'))
            ->setCellValue('I1', utf8_encode('MOTIVO PARALIZADO'))
            ->setCellValue('J1', utf8_encode('MARCA PRESUPUESTO'))
            ->setCellValue('K1', utf8_encode('RESPONSABILIDAD'))
            ->setCellValue('L1', utf8_encode('SOLICITUD OC'))
            ->setCellValue('M1', utf8_encode('ORDEN COMPRA'))
            ->setCellValue('N1', utf8_encode('ESTADO OC'))
            ->setCellValue('O1', utf8_encode('MONTO OC'))
            ->setCellValue('P1', utf8_encode('PEP2'))
            ->setCellValue('Q1', utf8_encode('GRAFO'))
            ->setCellValue('R1', utf8_encode('FECHA TERMINADO'))
            ->setCellValue('S1', utf8_encode('FECHA TRUNCO O CANCELADO'));
    
            $estiloTituloColumnas = array(
                'font' => array(
                    'name' => 'Calibri',
                    'bold' => true,
                    'color' => array(
                        'rgb' => '000000',
                    ),
                ));
    
            $this->excel->getActiveSheet()->getStyle('A1:AB1')->applyFromArray($estiloTituloColumnas);
    
            $infoRerpot = $this->m_reporte_sisego_ejecutados->getDataPieExcel($situacion);
    
            $index_excel = 2;
            if($infoRerpot != null){
                foreach($infoRerpot as $row){
                    $this->excel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$index_excel, utf8_encode($row->itemplan))
                    ->setCellValue('B'.$index_excel, utf8_encode($row->indicador))
                    ->setCellValue('C'.$index_excel, utf8_encode($row->nombreProyecto))
                    ->setCellValue('D'.$index_excel, utf8_encode($row->jefatura))
                    ->setCellValue('E'.$index_excel, utf8_encode($row->zonalDesc))
                    ->setCellValue('F'.$index_excel, utf8_encode($row->empresaColabDesc))
                    ->setCellValue('G'.$index_excel, utf8_encode($row->estadoPlanDesc))
                    ->setCellValue('H'.$index_excel, utf8_encode($row->has_paralizado))
                    ->setCellValue('I'.$index_excel, utf8_encode($row->motivoDesc))
                    ->setCellValue('J'.$index_excel, utf8_encode($row->has_presupuesto))
                    ->setCellValue('K'.$index_excel, utf8_encode($row->responsabilidad))
                    ->setCellValue('L'.$index_excel, utf8_encode($row->sol_crea))
                    ->setCellValue('M'.$index_excel, utf8_encode($row->orden_compra))
                    ->setCellValue('N'.$index_excel, utf8_encode($row->estado_oc))
                    ->setCellValue('O'.$index_excel, utf8_encode($row->monto_oc))
                    ->setCellValue('P'.$index_excel, utf8_encode($row->pep2))
                    ->setCellValue('Q'.$index_excel, utf8_encode($row->grafo))
                    ->setCellValue('R'.$index_excel, utf8_encode($row->fechaPreliquidacion))
                    ->setCellValue('S'.$index_excel, utf8_encode($row->fec_trunc_cancel));
                    $index_excel++;
                }
            }
             
    
            //Le ponemos un nombre al archivo que se va a generar.
            $archivo = "solicitud_oc.xls";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $archivo . '"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
            //Hacemos una salida al navegador con el archivo Excel.
    
            $nombreFile = 'download/reporte_sisego/detalle_reporte_' . date("YmdHis") . '.xls';
            $objWriter->save($nombreFile);
            $data['rutaExcel'] = $nombreFile;
            $data['error'] = EXIT_SUCCESS;
    
        } catch (Exception $e) {
            $data['msj'] = 'Error interno, al crear archivo detalle reporte sisegos!';
        }
        // return $data;
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function generarCsvDetalleReporteDetalleTerminados() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $tipo   = $this->input->post('tipo')    ? $this->input->post('tipo') : null;
           // $region = $this->input->post('region')  ? $this->input->post('region') : null;
            log_message('error', 'TIPO2:'.$tipo);
            ini_set('max_execution_time', 10000);
            ini_set('memory_limit', '2048M');
    
            $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
            $cacheSettings = array('memoryCacheSize ' => '5000MB', 'cacheTime' => '1000');
            PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
    
            //$this->excel->setActiveSheetIndex(0);
            $this->excel->getActiveSheet()->setTitle('Detalle Sisegos');
            //$this->excel->setActiveSheetIndex(0);
            // Se agregan los titulos del reporte
            $this->excel->setActiveSheetIndex(0)
            ->setCellValue('A1', utf8_encode('ITEMPLAN'))
            ->setCellValue('B1', utf8_encode('INDICADOR'))
            ->setCellValue('C1', utf8_encode('NOMBRE'))
            ->setCellValue('D1', utf8_encode('JEFATURA'))
            ->setCellValue('E1', utf8_encode('ZONA'))
            ->setCellValue('F1', utf8_encode('EECC'))
            ->setCellValue('G1', utf8_encode('ESTADO IP'))
            ->setCellValue('H1', utf8_encode('MARCA PARALIZADO'))
            ->setCellValue('I1', utf8_encode('MOTIVO PARALIZADO'))
            ->setCellValue('J1', utf8_encode('MARCA PRESUPUESTO'))
            ->setCellValue('K1', utf8_encode('RESPONSABILIDAD'))
            ->setCellValue('L1', utf8_encode('SOLICITUD OC'))
            ->setCellValue('M1', utf8_encode('ORDEN COMPRA'))
            ->setCellValue('N1', utf8_encode('ESTADO OC'))
            ->setCellValue('O1', utf8_encode('MONTO OC'))
            ->setCellValue('P1', utf8_encode('PEP2'))
            ->setCellValue('Q1', utf8_encode('GRAFO'))
            ->setCellValue('R1', utf8_encode('FECHA TERMINADO'))
            ->setCellValue('S1', utf8_encode('FECHA TRUNCO O CANCELADO'));
    
            $estiloTituloColumnas = array(
                'font' => array(
                    'name' => 'Calibri',
                    'bold' => true,
                    'color' => array(
                        'rgb' => '000000',
                    ),
                ));
    
            $this->excel->getActiveSheet()->getStyle('A1:AB1')->applyFromArray($estiloTituloColumnas);
    
            $infoRerpot = $this->m_reporte_sisego_ejecutados->getDataTablaDetalleTerminados($tipo);
    
            $index_excel = 2;
            if($infoRerpot != null){
                foreach($infoRerpot as $row){
                    $this->excel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$index_excel, utf8_encode($row->itemplan))
                    ->setCellValue('B'.$index_excel, utf8_encode($row->indicador))
                    ->setCellValue('C'.$index_excel, utf8_encode($row->nombreProyecto))
                    ->setCellValue('D'.$index_excel, utf8_encode($row->jefatura))
                    ->setCellValue('E'.$index_excel, utf8_encode($row->zonalDesc))
                    ->setCellValue('F'.$index_excel, utf8_encode($row->empresaColabDesc))
                    ->setCellValue('G'.$index_excel, utf8_encode($row->estadoPlanDesc))
                    ->setCellValue('H'.$index_excel, utf8_encode($row->has_paralizado))
                    ->setCellValue('I'.$index_excel, utf8_encode($row->motivoDesc))
                    ->setCellValue('J'.$index_excel, utf8_encode($row->has_presupuesto))
                    ->setCellValue('K'.$index_excel, utf8_encode($row->responsabilidad))
                    ->setCellValue('L'.$index_excel, utf8_encode($row->sol_crea))
                    ->setCellValue('M'.$index_excel, utf8_encode($row->orden_compra))
                    ->setCellValue('N'.$index_excel, utf8_encode($row->estado_oc))
                    ->setCellValue('O'.$index_excel, utf8_encode($row->monto_oc))
                    ->setCellValue('P'.$index_excel, utf8_encode($row->pep2))
                    ->setCellValue('Q'.$index_excel, utf8_encode($row->grafo))
                    ->setCellValue('R'.$index_excel, utf8_encode($row->fechaPreliquidacion))
                    ->setCellValue('S'.$index_excel, utf8_encode($row->fec_trunc_cancel));
                    $index_excel++;
                }
            }
             
    
            //Le ponemos un nombre al archivo que se va a generar.
            $archivo = "solicitud_oc.xls";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $archivo . '"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
            //Hacemos una salida al navegador con el archivo Excel.
    
            $nombreFile = 'download/reporte_sisego/detalle_reporte_' . date("YmdHis") . '.xls';
            $objWriter->save($nombreFile);
            $data['rutaExcel'] = $nombreFile;
            $data['error'] = EXIT_SUCCESS;
    
        } catch (Exception $e) {
            $data['msj'] = 'Error interno, al crear archivo detalle reporte sisegos!';
        }
        // return $data;
        echo json_encode(array_map('utf8_encode', $data));
    }
}