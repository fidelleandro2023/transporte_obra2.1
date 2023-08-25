<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_control_distancia_mdf extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_utils/m_utils');
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
            $data['title'] = 'CONTROL TRAMAS';
            $data['trablaDistanciaMdf'] = $this->trablaDistanciaMdf();
          
            // $data['tablaSinPo']             = $this->tablaSinPo();
            //$data['listCmbItemplan'] = $this->m_utils->getListItemplanxEecc($idEcc);
            //$data['opciones'] = $result['html'];
            $this->load->view('vf_panel_control/v_control_distancia_mdf',$data);
	   //}else{
	       //redirect('login','refresh');
	   //}
    }

    function trablaDistanciaMdf() {
        $arrayDataKmz = _getCoordenadasByCod();
        $html = '
                <table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr role="row">
                            <th colspan="1">N°</th>
                            <th colspan="1">CODIGO</th>
                            <th colspan="1"">LATITUD</th>
                            <th colspan="1">LONGITUD</th>
                            <th colspan="1">DISTANCIA</th>
                        </tr>
                    </thead>                  
                    <tbody>';
        
        $style = null;
        $cont = 0;
        $i = 0;
        foreach($arrayDataKmz as $row){
            $cont++;
            // if($row['estado'] == 1)  {
            //     $style = '#D4FABC';
            // } else {
            //     $style = '#F5D7C3';
            // }
            $html .='   <tr>
                            <td>'.$cont.'</td>
                            <td>'.$row['data']['mdf'].'</td>
                            <td style="background:#D4FABC"><a style="color:blue" data-flg_tipo="'.$row['data']['coord']['latitud'].'" data-exito="1" data-intervalo_h="1" onclick="getDetalle($(this))">'.$row['data']['coord']['latitud'].'</a></td>
                            <td style="background:#F5D7C3"><a style="color:blue" data-flg_tipo="'.$row['data']['coord']['longitud'].'" data-exito="0" data-intervalo_h="1" onclick="getDetalle($(this))">'.$row['data']['coord']['longitud'].'</a></td>
                            <td style="background:#D4FABC"><a style="color:blue" data-flg_tipo="'.$row['data']['distancia'].'" data-exito="1" data-intervalo_h="2" onclick="getDetalle($(this))">'.$row['data']['distancia'].'</a></td>
                        </tr>';
            $i++;
        }
        $html .='</tbody>
        </table>';
        return utf8_decode($html);
    }

}