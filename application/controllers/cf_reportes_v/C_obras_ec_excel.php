<?php

defined('BASEPATH') OR exit('No direct script access allowed');
 
class C_obras_ec_excel extends CI_Controller {
 
    function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');

        // Controller
        //$this->load->controller('c_utils');

        // Models
        $this->load->model('mf_reportes_v/m_seguimiento_ec');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');


        $this->load->library('excel');
    }

    public function index(){
        $style = array(
        'font' => array(
                'bold'      => true
            ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
            )
        );


        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('SeguimientoEC');
        $this->excel->getActiveSheet()->setCellValue('A1', 'Detalle Seguimiento EC.');
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
        $this->excel->getActiveSheet()->mergeCells('A1:N1');
        $this->excel->getActiveSheet()->getStyle("A1:N1")->applyFromArray($style);

                        
        // Se agregan los Subtitulos del reporte
        
        $this->excel->getActiveSheet()->setCellValue('A2',  'PROYECTO');
        $this->excel->getActiveSheet()->getStyle("A2")->applyFromArray($style);

        $this->excel->getActiveSheet()->setCellValue('B2',  'SUBPROYECTO');
        $this->excel->getActiveSheet()->getStyle("B2")->applyFromArray($style);

        $this->excel->getActiveSheet()->setCellValue('C2',  'ITEMPLAN');
        $this->excel->getActiveSheet()->getStyle("C2")->applyFromArray($style);

        $this->excel->getActiveSheet()->setCellValue('D2',  'FECHA PREVISTA EJEC');
        $this->excel->getActiveSheet()->getStyle("D2")->applyFromArray($style);

        $this->excel->getActiveSheet()->setCellValue('E2',  'CENTRAL');
        $this->excel->getActiveSheet()->getStyle("E2")->applyFromArray($style);

        $this->excel->getActiveSheet()->setCellValue('F2',  'INDICADOR');
        $this->excel->getActiveSheet()->getStyle("F2")->applyFromArray($style);

        $this->excel->getActiveSheet()->setCellValue('G2',  'ZONAL');
        $this->excel->getActiveSheet()->getStyle("G2")->applyFromArray($style);

        $this->excel->getActiveSheet()->setCellValue('H2',  'EECC');
        $this->excel->getActiveSheet()->getStyle("H2")->applyFromArray($style);

        $this->excel->getActiveSheet()->setCellValue('I2',  'MAT_COAX');
        $this->excel->getActiveSheet()->getStyle("I2")->applyFromArray($style);
        $this->excel->getActiveSheet()->setCellValue('J2',  'MAT_COAX_OC');
        $this->excel->getActiveSheet()->getStyle("J2")->applyFromArray($style);
        $this->excel->getActiveSheet()->setCellValue('K2',  'MAT_FUENTE');
        $this->excel->getActiveSheet()->getStyle("K2")->applyFromArray($style);
        $this->excel->getActiveSheet()->setCellValue('L2',  'MAT_FO');
        $this->excel->getActiveSheet()->getStyle("L2")->applyFromArray($style);

        $this->excel->getActiveSheet()->setCellValue('M2',  'MAT_FO_OC');
        $this->excel->getActiveSheet()->getStyle('M2')->getFont()->setBold(true);

        $this->excel->getActiveSheet()->setCellValue('N2',  'MAT_ENER');
        $this->excel->getActiveSheet()->getStyle('N2')->getFont()->setBold(true);

        $listaDatos = $this->m_seguimiento_ec->getDetalle();

        $i = 3;
        foreach ($listaDatos->result() as $dato) {

            $this->excel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$i,  utf8_encode($dato->proyectoDesc))
                        ->setCellValue('B'.$i,  utf8_encode($dato->subProyectoDesc))
                        ->setCellValue('C'.$i,  utf8_encode($dato->itemPlan))
                        ->setCellValue('D'.$i,  utf8_encode($dato->fechaPrevEjec))
                        ->setCellValue('E'.$i,  utf8_encode($dato->cod_central))
                        ->setCellValue('F'.$i,  utf8_encode($dato->indicador))
                        ->setCellValue('G'.$i,  utf8_encode($dato->zonal))
                        ->setCellValue('H'.$i,  utf8_encode($dato->eecc))
                        ->setCellValue('I'.$i,  utf8_encode($dato->mat_coax_ptr))
                        ->setCellValue('J'.$i,  utf8_encode($dato->mat_coax_oc_ptr))
                        ->setCellValue('K'.$i,  utf8_encode($dato->mat_fuente_ptr))
                        ->setCellValue('L'.$i,  utf8_encode($dato->mat_fo_ptr))
                        ->setCellValue('M'.$i,  utf8_encode($dato->mat_fo_oc_ptr))
                        ->setCellValue('M'.$i,  utf8_encode($dato->mat_ener_ptr));

            // MAT COAX



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