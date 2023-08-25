<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_control_atencion_cotizacion extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_panel_control/m_control_atencion_cotizacion');
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
            $data['title'] = 'COTIZACI&Oacute;N';
            $data['tablaAtencionCotizacion'] = $this->tablaAtencionCotizacion();
            // $data['tablaSinPo']             = $this->tablaSinPo();
            //$data['listCmbItemplan'] = $this->m_utils->getListItemplanxEecc($idEcc);
            //$data['opciones'] = $result['html'];
            $this->load->view('vf_panel_control/v_control_atencion_cotizacion',$data);
	   //}else{
	       //redirect('login','refresh');
	   //}
    }

    function tablaAtencionCotizacion() {
        $dataTablaGestion = $this->m_control_atencion_cotizacion->getTablaTramaAtencionCotizacion();
        $html = '
                <table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                            <tr role="row">
                                <th style="text-align: center;">ESTADO</th>
                                <th style="text-align: center; background-color: black;">0 - 6 HORAS</th>
                                <th style="text-align: center; background-color: darksalmon;">7 - 12 HORAS</th>
                                <th style="text-align: center; background-color: lightblue;"> 13 - 24 HORAS</th>
                                <th style="text-align: center; background-color: green;"> 25 - 48 HORAS</th>
                                <th style="text-align: center; background-color: green;"> > 48 HORAS</th>
                                <th style="text-align: center; background-color: green;">TOTAL</th>
                            </tr
                        </thead>                  
                    <tbody>';
        
        $style = null;
        $cont = 0;
        foreach($dataTablaGestion as $row){
            $cont++;
            $html .='   <tr>
                            <td>'.$row['estado_desc'].'</td>
                            <td style="background:#D4FABC"><a style="color:blue"  data-estado="'.$row['estado'].'" data-intervalo_h="1" onclick="getDetalleCotiAten($(this))">'.$row['menor_6h'].'</a></td>
                            <td style="background:#D4FABC"><a style="color:blue"  data-estado="'.$row['estado'].'" data-intervalo_h="2" onclick="getDetalleCotiAten($(this))">'.$row['7h_12h'].'</a></td>
                            <td style="background:#D4FABC"><a style="color:blue"  data-estado="'.$row['estado'].'" data-intervalo_h="3" onclick="getDetalleCotiAten($(this))">'.$row['13h_24h_exitoso'].'</a></td>
                            <td style="background:#D4FABC"><a style="color:blue"  data-estado="'.$row['estado'].'" data-intervalo_h="4" onclick="getDetalleCotiAten($(this))">'.$row['25h_48h_exitoso'].'</a></td> 
                            <td style="background:#D4FABC"><a style="color:blue"  data-estado="'.$row['estado'].'" data-intervalo_h="5" onclick="getDetalleCotiAten($(this))">'.$row['mayor_48h_exitoso'].'</a></td>
                            <td style="background:#D4FABC"><a style="color:blue"  data-intervalo_h="6" onclick="getDetalleCotiAten($(this))">'.$row['total'].'</a></td> 	                             
                        </tr>';
        }
            $html .='</tbody>
            </table>';
        return $html;
    }

    function getDataGrafCotizacion() {
        $dataTablaGestion = $this->m_control_atencion_cotizacion->getTablaTramaAtencionCotizacion();
        $arrayDataGraf = array();

        $colores = array(
                         'PENDIENTE' => '#8BC34A', 
                         'PDTE APROBACION' => '#FFEB3B', 
                         'APROBADO'  => '#673AB7', 
                         'RECHAZADO'  => '#757575', 
                         'PENDIENTE DE CONFIRMACION' => '#FF9800' 
                        );

        foreach($dataTablaGestion as $row){
            $JSON['name']  = $row['estado_desc'];
            $JSON['y']     = $row['total'];
            $JSON['color'] = $colores[$row['estado_desc']];
            $JSON['events']['click'] = '';
            array_push($arrayDataGraf, $JSON);
        }

        $data['arrayGrafPie'] = $arrayDataGraf;

        echo json_encode($data, JSON_NUMERIC_CHECK);
    }

    function getDetalleCotiAten() {
        $data['msj'] = '';
        $data['error'] = EXIT_ERROR;
        try {
            $estado = $this->input->post('estado');
            $intervalo_h = $this->input->post('intervalo_h');

            if($estado == null || $estado == null) {
                throw new Exception("Comunicarse con el programador a cargo (Carlos Cuya).");
            }

            $data['tablaDetalleCotiAten'] = $this->tablaDetalleCotiAten($estado, $intervalo_h);
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function tablaDetalleCotiAten($estado, $intervalo_h) {
        $dataTablaDetalle = $this->m_control_atencion_cotizacion->getDetalleCotiAten($estado, $intervalo_h);
        $html = '
                <table id="tbDetalleCotiAten" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>NRO.</th>
                            <th>C&Oacute;DIGO</th>
                            <th>CLIENTE</th>
                            <th>LATITUD</th>    
                            <th>LONGITUD</th> 
                            <th>FECHA</th> 
                        </tr>
                    </thead>                    
                    <tbody>';
        
        $style = null;
        $cont = 0;
        foreach($dataTablaDetalle as $row){
            $cont++;
            $style = '#D4FABC';


            $html .='   <tr>
                            <td style="background:'.$style.'">'.$cont.'</td>
                            <td style="background:'.$style.'">'.$row['codigo_cluster'].'</td>
                            <td style="background:'.$style.'">'.$row['cliente'].'</td>
                            <td style="background:'.$style.'">'.$row['latitud'].'</td>
                            <th style="background:'.$style.'">'.$row['longitud'].'</th>
                            <td style="background:'.$style.'">'.$row['fecha'].'</td>                          
                        </tr>';
        }
            $html .='</tbody>
            </table>';
            return utf8_decode($html);
    }
}
