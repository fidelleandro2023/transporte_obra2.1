<?php

defined('BASEPATH') OR exit('No direct script access allowed');
 
class C_obras_excel extends CI_Controller {
 
    function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');

        // Controller
        //$this->load->controller('c_utils');

        // Models
        $this->load->model('mf_reportes_v/m_seguimiento_pdo');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');


        $this->load->library('excel');
    }
    
    public function index(){
        $logedUser = $this->session->userdata('usernameSession');
        $lista = array();

        $listaJS = $this->input->post('listaJS');
        $lista = json_decode($listaJS, true);

        //if(sizeof($lista) !== 0){
            //_log('modelo de llamada '. $lista[0]['subProyectoDesc']);
        //}else{
            //_log('hay 0 resultados no se enviara excel');
        //}
        
        
        $style = array(
        'font' => array(
                'bold'      => true
            ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
            )
        );


        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('SeguimientoPO');
        $this->excel->getActiveSheet()->setCellValue('A1', 'Seguimiento PO.');
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
        $this->excel->getActiveSheet()->mergeCells('A1:AJ1');
        $this->excel->getActiveSheet()->getStyle("A1:AJ1")->applyFromArray($style);

                        
        // Se agregan los Subtitulos del reporte
        
        $this->excel->getActiveSheet()->setCellValue('A2',  'DESCRIPCION');
        $this->excel->getActiveSheet()->mergeCells('A2:C2');
        $this->excel->getActiveSheet()->getStyle("A2:C2")->applyFromArray($style);

        $this->excel->getActiveSheet()->setCellValue('D2',  'OBRAS');
        $this->excel->getActiveSheet()->mergeCells('D2:F2');
        $this->excel->getActiveSheet()->getStyle("D2:F2")->applyFromArray($style);

        $this->excel->getActiveSheet()->setCellValue('G2',  'MAT_COAX');
        $this->excel->getActiveSheet()->mergeCells('G2:K2');
        $this->excel->getActiveSheet()->getStyle("G2:K2")->applyFromArray($style);

        $this->excel->getActiveSheet()->setCellValue('L2',  'MAT_FO');
        $this->excel->getActiveSheet()->mergeCells('L2:P2');
        $this->excel->getActiveSheet()->getStyle("L2:P2")->applyFromArray($style);

        $this->excel->getActiveSheet()->setCellValue('Q2',  'MAT_FUENTE');
        $this->excel->getActiveSheet()->mergeCells('Q2:U2');
        $this->excel->getActiveSheet()->getStyle("Q2:U2")->applyFromArray($style);

        $this->excel->getActiveSheet()->setCellValue('V2',  'MAT_FO_OC');
        $this->excel->getActiveSheet()->mergeCells('V2:Z2');
        $this->excel->getActiveSheet()->getStyle("V2:Z2")->applyFromArray($style);

        $this->excel->getActiveSheet()->setCellValue('AA2',  'MAT_COAX_OC');
        $this->excel->getActiveSheet()->mergeCells('AA2:AE2');
        $this->excel->getActiveSheet()->getStyle("AA2:AE2")->applyFromArray($style);

        $this->excel->getActiveSheet()->setCellValue('AF2',  'MAT_ENER');
        $this->excel->getActiveSheet()->mergeCells('AF2:AJ2');
        $this->excel->getActiveSheet()->getStyle("AF2:AJ2")->applyFromArray($style);

        $this->excel->getActiveSheet()->setCellValue('A3',  'SUB PROYECTO');
        $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('B3',  'ZONAL');
        $this->excel->getActiveSheet()->getStyle('B3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('C3',  'EECC');
        $this->excel->getActiveSheet()->getStyle('C3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('D3',  'TOTAL OBRAS');
        $this->excel->getActiveSheet()->getStyle('D3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('E3',  'EN OBRA');
        $this->excel->getActiveSheet()->getStyle('E3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('F3',  'TERMINADO');
        $this->excel->getActiveSheet()->getStyle('F3')->getFont()->setBold(true);
        
        $this->excel->getActiveSheet()->setCellValue('G3',  'CREADO');
        $this->excel->getActiveSheet()->getStyle('G3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('H3',  'VR APROB');
        $this->excel->getActiveSheet()->getStyle('H3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('I3',  '% VR');
        $this->excel->getActiveSheet()->getStyle('I3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('J3',  'VR ADIC');
        $this->excel->getActiveSheet()->getStyle('J3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('K3',  'TPO. APROB');
        $this->excel->getActiveSheet()->getStyle('K3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('L3',  'CREADO');
        $this->excel->getActiveSheet()->getStyle('L3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('M3',  'VR APROB');
        $this->excel->getActiveSheet()->getStyle('M3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('N3',  '% VR');
        $this->excel->getActiveSheet()->getStyle('N3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('O3',  'VR ADIC');
        $this->excel->getActiveSheet()->getStyle('O3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('P3',  'TPO. APROB');
        $this->excel->getActiveSheet()->getStyle('P3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('Q3',  'CREADO');
        $this->excel->getActiveSheet()->getStyle('Q3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('R3',  'VR APROB');
        $this->excel->getActiveSheet()->getStyle('R3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('S3',  '% VR');
        $this->excel->getActiveSheet()->getStyle('S3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('T3',  'VR ADIC');
        $this->excel->getActiveSheet()->getStyle('T3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('U3',  'TPO. APROB');
        $this->excel->getActiveSheet()->getStyle('U3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('V3',  'CREADO');
        $this->excel->getActiveSheet()->getStyle('V3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('W3',  'VR APROB');
        $this->excel->getActiveSheet()->getStyle('W3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('X3',  '% VR');
        $this->excel->getActiveSheet()->getStyle('X3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('Y3',  'VR ADIC');
        $this->excel->getActiveSheet()->getStyle('Y3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('Z3',  'TPO. APROB');
        $this->excel->getActiveSheet()->getStyle('Z3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('AA3',  'CREADO');
        $this->excel->getActiveSheet()->getStyle('AA3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('AB3',  'VR APROB');
        $this->excel->getActiveSheet()->getStyle('AB3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('AC3',  '% VR');
        $this->excel->getActiveSheet()->getStyle('AC3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('AD3',  'VR ADIC');
        $this->excel->getActiveSheet()->getStyle('AD3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('AE3',  'TPO. APROB');
        $this->excel->getActiveSheet()->getStyle('AE3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('AF3',  'CREADO');
        $this->excel->getActiveSheet()->getStyle('AF3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('AG3',  'VR APROB');
        $this->excel->getActiveSheet()->getStyle('AG3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('AH3',  '% VR');
        $this->excel->getActiveSheet()->getStyle('AH3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('AI3',  'VR ADIC');
        $this->excel->getActiveSheet()->getStyle('AI3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('AJ3',  'TPO. APROB');
        $this->excel->getActiveSheet()->getStyle('AJ3')->getFont()->setBold(true);

        $i = 4;
        for ($j=0; $j < sizeof($lista) ; $j++) { 
         $newtotal =  $lista[$j]['total_obras'] - $lista[$j]['cancelado'];

            $this->excel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$i,  utf8_encode($lista[$j]['subProyectoDesc']))
                        ->setCellValue('B'.$i,  utf8_encode($lista[$j]['zonal']))
                        ->setCellValue('C'.$i,  utf8_encode($lista[$j]['eecc']))
                        ->setCellValue('D'.$i,  utf8_encode($newtotal))
                        ->setCellValue('E'.$i,  utf8_encode($lista[$j]['en_obra']))
                        ->setCellValue('F'.$i,  utf8_encode($lista[$j]['terminado']))
                        //->setCellValue('G'.$i,  utf8_encode($lista[$j]['cancelado']))

            // MAT COAX
                        ->setCellValue('G'.$i,  utf8_encode($lista[$j]['mat_coax_crea']))
                        ->setCellValue('H'.$i,  utf8_encode($lista[$j]['mat_coax_apro']))
                        ->setCellValue('I'.$i,  $this->getPorcentaje($newtotal,$lista[$j]['mat_coax_apro']))
                        ->setCellValue('J'.$i,  (($lista[$j]['mat_coax_vradic']!=null) ? $lista[$j]['mat_coax_vradic'] : '0'))
                        ->setCellValue('K'.$i,  (($lista[$j]['mat_coax_tpo_apro']!=null) ? $this->before(':',  $lista[$j]['mat_coax_tpo_apro'] ) : '0'))
            //  MAT_ FO
                        ->setCellValue('L'.$i,  utf8_encode($lista[$j]['mat_fo_crea']))
                        ->setCellValue('M'.$i,  utf8_encode($lista[$j]['mat_fo_apro']))
                        ->setCellValue('N'.$i,  $this->getPorcentaje($newtotal,$lista[$j]['mat_fo_apro']))
                        ->setCellValue('O'.$i,  (($lista[$j]['mat_fo_vradic']!=null) ? $lista[$j]['mat_fo_vradic'] : '0'))
                        ->setCellValue('P'.$i,  (($lista[$j]['mat_fo_tpo_apro']!=null) ? $this->before(':',  $lista[$j]['mat_fo_tpo_apro'] ) : '0'))
            
            //  MAT_FUENTE
                        ->setCellValue('Q'.$i,  utf8_encode($lista[$j]['mat_fuente_crea']))
                        ->setCellValue('R'.$i,  utf8_encode($lista[$j]['mat_fuente_apro']))
                        ->setCellValue('S'.$i,  $this->getPorcentaje($newtotal,$lista[$j]['mat_fuente_apro']))
                        ->setCellValue('T'.$i,  (($lista[$j]['mat_fuente_vradic']!=null) ? $lista[$j]['mat_fuente_vradic'] : '0'))
                        ->setCellValue('U'.$i,  (($lista[$j]['mat_fuente_tpo_apro']!=null) ? $this->before(':',  $lista[$j]['mat_fuente_tpo_apro'] ) : '0'))

            //  MAT_FO_OC
                        ->setCellValue('V'.$i,  utf8_encode($lista[$j]['mat_fo_oc_crea']))
                        ->setCellValue('W'.$i,  utf8_encode($lista[$j]['mat_fo_oc_apro']))
                        ->setCellValue('X'.$i,  $this->getPorcentaje($newtotal,$lista[$j]['mat_fo_oc_apro']))
                        ->setCellValue('Y'.$i,  (($lista[$j]['mat_fo_oc_vradic']!=null) ? $lista[$j]['mat_fo_oc_vradic'] : '0'))
                        ->setCellValue('Z'.$i,  (($lista[$j]['mat_fo_oc_tpo_apro']!=null) ? $this->before(':',  $lista[$j]['mat_fo_oc_tpo_apro'] ) : '0'))

            //  MAT_COAX_OC
                        ->setCellValue('AA'.$i,  utf8_encode($lista[$j]['mat_coax_oc_crea']))
                        ->setCellValue('AB'.$i,  utf8_encode($lista[$j]['mat_coax_oc_apro']))
                        ->setCellValue('AC'.$i,  $this->getPorcentaje($newtotal,$lista[$j]['mat_coax_oc_apro']))
                        ->setCellValue('AD'.$i,  (($lista[$j]['mat_coax_oc_vradic']!=null) ? $lista[$j]['mat_coax_oc_vradic'] : '0'))
                        ->setCellValue('AE'.$i,  (($lista[$j]['mat_coax_oc_tpo_apro']!=null) ? $this->before(':',  $lista[$j]['mat_coax_oc_tpo_apro'] ) : '0'))

            //  MAT_ENER
                        ->setCellValue('AF'.$i,  utf8_encode($lista[$j]['mat_ener_crea']))
                        ->setCellValue('AG'.$i,  utf8_encode($lista[$j]['mat_ener_apro']))
                        ->setCellValue('AH'.$i,  $this->getPorcentaje($newtotal,$lista[$j]['mat_ener_apro']))
                        ->setCellValue('AI'.$i,  (($lista[$j]['mat_ener_vradic']!=null) ? $lista[$j]['mat_ener_vradic'] : '0'))
                        ->setCellValue('AJ'.$i,  (($lista[$j]['mat_ener_tpo_apro']!=null) ? $this->before(':',  $lista[$j]['mat_ener_tpo_apro'] ) : '0'));


            $i++;
            
        }
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="SeguimientoPO.xls"');
        header('Cache-Control: max-age=0'); //no cache
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

        // Forzamos a la descarga
        //$objWriter->save('php://output');

        //....
        ob_start();
        $objWriter->save("php://output");
        $xlsData = ob_get_contents();
        ob_end_clean();
         
        $opResult = array(
                'status' => 1,
                'data'=>"data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
             );
        echo json_encode($opResult);
    }

    public function before ($val, $inthat)
    {
        return substr($inthat, 0, strpos($inthat, $val));
    }

    public function getPorcentaje($max, $min){
        if($max!=0){
            return round(($min*100)/$max, 0).'%';
        }else{
            return '0%';
        }
        
    }
 
}