<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_control_diseno extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_panel_control/m_control_diseno');
        $this->load->library('lib_utils');
        $this->load->library('excel');
        $this->load->helper('url');
    }

	public function index() {  	   
        $logedUser = $this->session->userdata('usernameSession');
        $idEcc     = $this->session->userdata('eeccSession');
	    //if($logedUser != null){
            $data['nombreUsuario'] =  $this->session->userdata('usernameSession');	
            $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');	               
            $permisos =  $this->session->userdata('permisosArbol');
            //$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_MANTENIMIENTO, ID_PERMISO_HIJO_MANTENIMIENTO_KIT_EXT);
            $data['title'] = 'CONTROL DISE&Ntilde;O';
            $data['tablaSinEstadoValidado'] = $this->tablaSinEstadoValidado();
            $data['tablaSinPo']             = $this->tablaSinPo();
            //$data['listCmbItemplan'] = $this->m_utils->getListItemplanxEecc($idEcc);
            //$data['opciones'] = $result['html'];
            $this->load->view('vf_panel_control/v_control_diseno',$data);
	   //}else{
	       //redirect('login','refresh');
	   //}
    }

    function tablaSinEstadoValidado() {
        $dataTablaValid = $this->m_control_diseno->getDataSinValid();
        $html = '
                <table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>Nro.</th>
                            <th>ITEMPLAN</th>
                            <th>SUBPROYECTO</th>    
                            <th>PO</th>                         
                            <th>ESTADO</th>            
                        </tr>
                    </thead>                    
                    <tbody>';
        
        $style = null;
        $cont = 0;
        foreach($dataTablaValid as $row){
            $cont++;
            $html .='   <tr>
                            <td style="background:'.$style.'">'.$cont.'</td>
                            <td style="background:'.$style.'">'.$row['itemplan'].'</td>
                            <td style="background:'.$style.'">'.$row['subProyectoDesc'].'</td>
                            <th style="background:'.$style.'">'.$row['codigo_po'].'</th>
                            <td style="background:'.$style.'">'.$row['estado'].'</td>	                            
                        </tr>';
        }
            $html .='</tbody>
            </table>';
            return utf8_decode($html);
    }

    function tablaSinPo() {
        $dataSinPo = $this->m_control_diseno->getDataSinPoDiseno();
        $html = '<table id="tbSinPO" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>Acci&oacute;n</th>
                            <th>Nro.</th>
                            <th>ITEMPLAN</th>
                            <th>SUBPROYECTO</th>
                            <th>ESTACION</th>                            
                            <th>COMPLEJIDAD</th> 
                            <th>CANTIDAD PO-MAT/MO</th> 
                        </tr>
                    </thead>                    
                    <tbody>';
        
        $style = null;
        $cont = 0;
        foreach($dataSinPo as $row){
            $count = $this->m_control_diseno->getCountComplejAltaPO($row['itemplan'], $row['idEstacion']);
            if($row['countMoMat'] != '' && $row['countMoMat'] != 0)  {
                if($row['idTipoComplejidad'] == 2) {
                    if( $count != 0) {
                        $style = '#D4FABC';
                    } else {
                        $style = null;
                    }
                } else {
                     $style = '#D4FABC';
                }
            } else {
                $style = null;
            }

            $cont++;
            $html .='   <tr>
                            <td style="background:'.$style.'"><i class="zmdi zmdi-hc-2x zmdi-wrench" style="cursor:pointer" data-itemplan="'.$row['itemplan'].'" 
                                                                title="generar po" data-id_estacion="'.$row['idEstacion'].'"  onclick="generarPOControl($(this));"></i></td>
                            <td style="background:'.$style.'">'.$cont.'</td>
                            <td style="background:'.$style.'">'.$row['itemplan'].'</td>
                            <td style="background:'.$style.'">'.$row['subProyectoDesc'].'</td>
                            <td style="background:'.$style.'">'.$row['estacionDesc'].'</td>
                            <td style="background:'.$style.'">'.$row['complejidadDesc'].'</td>
                            <td style="background:'.$style.'">'.$row['countMoMat'].'</td>							
                        </tr>';
        }
            $html .='</tbody>
            </table>';
            return utf8_decode($html);
    }

    function generarPOControl() {
        $data['msj'] = '';
        $data['error'] = EXIT_ERROR;
        try {
            $itemplan   = $this->input->post('itemplan');
            $idEstacion = $this->input->post('idEstacion');

            if($itemplan == null || $itemplan == '') {
                throw new Exception('error, No se detecto el itemplan, comunicarse con el programador.');
            } 

            if($idEstacion == null || $idEstacion == '') {
                throw new Exception('error, No se detecto el estaci&oacute;n, comunicarse con el programador.');
            }

            $resp = $this->m_utils->generarPODiseno($itemplan, $idEstacion, 0, 0);

            if($resp == 5) {
                $data['error'] = EXIT_ERROR;
                throw new Exception('error, Error al generar la PO DISE&Ntilde;O');
            } 

            if($resp == 3) {
                $data['error'] = EXIT_ERROR;
                throw new Exception('No tiene PO material');
            } 

            if($resp == null || $resp == '') {
                $data['error'] = EXIT_ERROR;
                throw new Exception('Error Comunicarse con el programador');
            }
            
            $data['tablaSinPO'] = $this->tablaSinPo();
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone','America/Lima');
        setlocale(LC_TIME, "es_ES","esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }
}