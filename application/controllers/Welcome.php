<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');	
;
		$this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
		$this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
		$this->output->set_header('Pragma: no-cache');
		$this->load->model('mf_utils/m_utils');
		$this->load->library('table');	
		$this->notify404 = 'v_404';
		$this->load->library('Lib_utils');
	}
	public function index()
	{
		
		$this->testReverParalizacion();
			
	}
	
	function testParalizacion(){
		$itemplan = '20-0311100072';
	     $dataSend = ['itemplan'      => $itemplan,
                         'fecha'         => $this->fechaActual(),
                         'flg_activo'    => FLG_INACTIVO,
                         'nombreUsuario' => $this->session->userdata('usernameSession'),
                         'correo'        => $this->session->userdata('email')];
    
            $url = 'https://172.30.5.10:8080/obras2/recibir_par.php';
            
            $response = $this->m_utils->sendDataToURL($url, $dataSend);
            if(is_object($response)){
				if($response->error == EXIT_SUCCESS){
					$this->m_utils->saveLogSigoplus('ENVIAR PARALIZACION', null , $itemplan, null, null, null, null, 'TRAMA COMPLETADA', 'OPERACION REALIZADA CON EXITO', 1, 7);
				}else{
					$this->m_utils->saveLogSigoplus('ENVIAR PARALIZACION', null, $itemplan, null, null, null, null, 'FALLA EN LA RESPUESTA DEL HOSTING', strtoupper($response->mensaje), 2, 7);
				} 
			}else{
				$this->m_utils->saveLogSigoplus('ENVIAR PARALIZACION', null, $itemplan, null, null, null, null, 'FALLA DE CONEXION', 'OPERACION NO COMPLETADA, SE PERDIO LA CONEXCION CON SIGOPLUS', 3, 7);
			}
	}
		
	
	function testReverParalizacion(){
		$itemplan = '20-0311100072';
		$dataSend = ['itemplan'      => $itemplan,
                         'fecha'         => $this->fechaActual(),
                         'flg_activo'    => FLG_INACTIVO,
                         'nombreUsuario' => $this->session->userdata('usernameSession'),
                         'correo'        => $this->session->userdata('email')];
    
            $url = 'https://172.30.5.10:8080/obras2/recibir_par.php';
            
            $response = $this->m_utils->sendDataToURL($url, $dataSend);
            if(is_object($response)){
				if($response->error == EXIT_SUCCESS){
					$this->m_utils->saveLogSigoplus('ENVIAR REVER PARALIZACION', null , $itemplan, null, null, null, null, 'TRAMA COMPLETADA', 'OPERACION REALIZADA CON EXITO', 1, 8);
				}else{
					$this->m_utils->saveLogSigoplus('ENVIAR REVER PARALIZACION', null, $itemplan, null, null, null, null, 'FALLA EN LA RESPUESTA DEL HOSTING', strtoupper($response->mensaje), 2, 8);
				} 
			}else{
				$this->m_utils->saveLogSigoplus('ENVIAR REVER PARALIZACION', null, $itemplan, null, null, null, null, 'FALLA DE CONEXION', 'OPERACION NO COMPLETADA, SE PERDIO LA CONEXCION CON SIGOPLUS', 3, 8);
			}
		
	}
	
	function testCancelacion(){
		
			$itemplan = '20-0311100072';
			$sisego = '2020-01-155804';

			$dataSend = ['itemplan' => $itemplan,
                         'fecha'    => $this->fechaActual(),
                         'estado'   => FLG_CANCELACION_CONFIRMADA];

            $url = 'https://172.30.5.10:8080/obras2/recibir_can.php';

            $response = $this->m_utils->sendDataToURL($url, $dataSend);
			if(is_object($response)){
				if($response->error == EXIT_SUCCESS){
					$this->m_utils->saveLogSigoplus('BANDEJA CANCELACION', null, $itemplan, null, $sisego, null, null, 'TRAMA COMPLETADA', 'OPERACION REALIZADA CON EXITO', 1, 10);
				}else{
					$this->m_utils->saveLogSigoplus('BANDEJA CANCELACION', null, $itemplan, null, $sisego, null, null, 'FALLA EN LA RESPUESTA DEL HOSTING', strtoupper($response->mensaje), 2, 10);
				}
			}else{
				$this->m_utils->saveLogSigoplus('BANDEJA CANCELACION', null, $itemplan, null, $sisego, null, null, 'FALLA DE CONEXION', 'OPERACION NO COMPLETADA, SE PERDIO LA CONEXCION CON SIGOPLUS', 3, 10);
			}			
	}

	function testLiquidacion(){
		
		$itemplan = '20-0311100072';
		$sisego = '2020-01-155804';

		$dataSend = ['itemplan' => $itemplan,
                     'fecha'    => $this->fechaActual(),
                     'sisego'   => $sisego];

		$url = 'https://172.30.5.10:8080/obras2/recibir_eje.php';
		
		$data = _trama_sisego($dataSend, $url, 4, $itemplan, 'LIQUIDACION - INTEGRACION SIOM', $sisego);

		_log(print_r($data, true));
	}	
	
	function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone','America/Lima');
        setlocale(LC_TIME, "es_ES","esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }
}
