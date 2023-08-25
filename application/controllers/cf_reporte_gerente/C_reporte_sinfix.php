<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_reporte_sinfix extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('mf_reporte_gerente/m_reporte_sinfix');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    function index() {
        $this->load->view('vf_reporte_gerente/v_reporte_sinfix');
    }

    function getTablaReporte() {
        
        $arrayDataTabla = array();
        
        $arrayForeach = $this->m_reporte_sinfix->getQueryTablaJefatura();

        foreach($arrayForeach as $row) {
            array_push($arrayDataTabla, $row);
        }
        $arrayDataTabla2 = array();        
        $arrayForeach2 = $this->m_reporte_sinfix->getQueryTablaEecc();
        foreach($arrayForeach2 as $row2) {
            array_push($arrayDataTabla2, $row2);
        }
        $data['arrayReporteTableJefatura'] = $arrayDataTabla;
        $data['arrayReporteTablaEecc']     = $arrayDataTabla2;
        $arraySubProy = array(13,14,15);
        $data['todoTablaDetalle']          = $this->getDataPlanObra($arraySubProy);
        //$data['arrayReporteParalizacion']  = $this->m_reporte_sinfix->getDataParalizacion();
        echo json_encode($data);
     }
     
    function getDetalleDataEmpreColab() {
         $idEmpresaColab = $this->input->post('idEmpresaColab');
         $flgProvLim     = $this->input->post('flg');
        
         $arrayDataDetalle = $this->m_reporte_sinfix->getDetalleDataEmpreColab($idEmpresaColab, $flgProvLim);
         $arrayData = array();
    
         foreach($arrayDataDetalle as $row) {
            array_push($arrayData, $row);
         }
         $data['arrayDetalle'] = $arrayData;
         echo json_encode($data);  
     }
     
    function getDataJefaturaEcc() {
        $arrayData = $this->m_reporte_sinfix->getDataJefaturaEcc();
        $arrayDataJson = array();
        $arrayDataJson2 = array();
        $cont = 0;
        $cont1 = 0;
        $idEmpresaColab =null;
        //$arrayDataZonal = array();
        foreach($arrayData as $row) {
            $data['countEnObra'] = null; 
            $data['countPreliquidado'] = null;
            $data['countTrunco'] = null;
            $data['idEmpresaColab']   = $row->idEmpresaColab;
            $data['empresaColabDesc'] = $row->empresaColabDesc;
            $cont++;
            $flg = 0;
            
            $arrayDataZonal = explode(',', $row->dataZonal);
            $data['descZonal'] = $row->zona;

            foreach($arrayDataZonal as $row1) {
                $arrayUlt1 = explode('|', $row1);
                // foreach($arrayUlt1 as $row3) {
                    //$arrayUlt2 = explode('|', $row3[0]);
               
                foreach($arrayUlt1 as $row2) {
                    if($arrayUlt1[0] == 9) {
                        $data['countPreliquidado'] = $arrayUlt1[1];
                        $flg++;
                    } 
                    
                    if($arrayUlt1[0] == 3) {
                        $data['countEnObra'] = $arrayUlt1[1];
                        $flg++;
                    }
                    
                    if($arrayUlt1[0] == 10) {
                        $flg++;
                        $data['countTrunco'] = $arrayUlt1[1];
                    }
                    $descZonal = $data['descZonal'];
                    $cont1++;   
                }

                //if($data['countEnObra'] != null && $data['countPreliquidado'] != null && $data['countTrunco'] != null) {
                    array_push($arrayDataJson, $data);
                //} 
                // }
  
            }
            
            // if($idEmpresaColab != $data['idEmpresaColab'] && $idEmpresaColab != null) {
                array_push($arrayDataJson2, $arrayDataJson);
                $arrayDataJson =array();
            // }
            //array_push($arrayDataJson2, $arrayDataJson);
            $idEmpresaColab = $data['idEmpresaColab'];
            
            // foreach($arrayUlt as $row1) {
            // }
        }
        return $arrayDataJson2;
     }
     
    function getDataPlanObra($arraySubProy) {
        $arrayData = $this->m_reporte_sinfix->getDataPlanObra($arraySubProy);

        $arrayTabla = array();
        foreach($arrayData AS $row) {
            array_push($arrayTabla, $row);
        }
        return $arrayTabla;
     }
}