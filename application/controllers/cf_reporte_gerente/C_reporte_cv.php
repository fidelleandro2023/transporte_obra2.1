<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_reporte_cv extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('mf_reporte_gerente/m_reporte_sinfix');
         $this->load->model('mf_reporte_gerente/m_reporte_cv_jef_eecc');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    function index() {
        $this->load->view('vf_reporte_gerente/v_reporte_cv');
    }

    function getTablaReporteCv() {
        $arraySubProy = array(96,97);
        $data['todoTablaDetalle'] = $this->m_reporte_sinfix->getDataPlanObra($arraySubProy);
        echo json_encode($data);
    }

    function getDataPlanObra($arraySubProy) {
        $arrayData = $this->m_reporte_sinfix->getDataPlanObra($arraySubProy);
        return $arrayData;
     }
     
     //////////////////////////////////////////////////////////////////////
     function getDataCVJefEECC() {
        $data['listaCVJefEECC']=$this->m_reporte_cv_jef_eecc->getReportCVJefEECCOnline();
        echo json_encode($data);
     }

     function detJefEECCCVOnline() {

        $mes = $this->input->post('mes');
        $jefatura = $this->input->post('jefatura');
        $eecc = $this->input->post('eecc'); 

        $arrayDataDetalle=$this->m_reporte_cv_jef_eecc->getDetalleCVJefEECCOnline($jefatura,$eecc,$mes);

        $arrayData = array();
    
         foreach($arrayDataDetalle as $row) {
            array_push($arrayData, $row);
         }
         $data['listaDetCVJefEECC'] = $arrayData;
         echo json_encode($data);
     }
     
     //////////////////////02102018//////////////////////////////////
     function getDataPlanObraCVOnline() {

         $jefatura = $this->input->post('jefatura');
        $eecc = $this->input->post('eecc'); 
        $arrayEstado = $this->input->post('estado');
        $arraySubProy = array(96,97);
        $hoy='';
        if ($arrayEstado==2){
            $arrayEstado = array(1,2,7,11);
        }

         if ($arrayEstado==8){
            $arrayEstado = array(8);
        }

        if ($arrayEstado==3){
            $arrayEstado = array(3);
        }

        if ($arrayEstado==9){
            $arrayEstado = array(9);
            $hoy='1';
        }

        
        $arrayDataDetalle = $this->m_reporte_sinfix->getDetalleDataPlanObraCV($jefatura,$eecc,$arraySubProy,$arrayEstado,$hoy);

        $arrayData = array();
    
         foreach($arrayDataDetalle as $row) {
            array_push($arrayData, $row);
         }
         $data['TablaDetallePOCV'] = $arrayData;
         echo json_encode($data);
     }
     
     
     
}