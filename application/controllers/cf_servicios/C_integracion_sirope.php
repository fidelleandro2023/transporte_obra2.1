<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 *
 *
 */
class C_integracion_sirope extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_servicios/M_integracion_sirope');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index()
    {	$items = array('');
        
        foreach ($items as $var){
            $this->M_integracion_sirope->execWs($var, $var.'FO','2020-10-01','2020-10-08');
        }
        //$this->M_integracion_sirope->execWs('20-0210900007','20-0210900007FO','2020-02-18','2020-02-22');
		//$this->M_integracion_sirope->getXMLexecWs('19-0111100333', '19-0111100333FO', '2019-12-18', '2019-12-22');
        $this->load->view('vf_sirope/v_test_ws');
    
    }
	
}