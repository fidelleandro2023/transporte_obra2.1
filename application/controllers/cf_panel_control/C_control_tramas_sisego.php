<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_control_tramas_sisego extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_crecimiento_vertical/m_bandeja_diseno_cv');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
        $this->load->library('zip');
    }

    public function index(){
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
            $data['tablaTramas'] = $this->getTablaTramas(3, NULL);
            $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
            $data['listaJefatura']  = $this->m_utils->getNewAllJefatura();
            $data['listaEECC']      = $this->m_utils->getAllEECC();
            $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
            $permisos =  $this->session->userdata('permisosArbol');
            #$result = $this->lib_utils->getHTMLPermisos($permisos, 167, 185);
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_NUEVO_MODELO_COTIZACION, 185, ID_MODULO_PAQUETIZADO);
            $data['opciones'] = $result['html'];
            if($result['hasPermiso'] == true){
                    $this->load->view('vf_panel_control/v_control_tramas_sisego',$data);
            }else{
                redirect('login','refresh');
            }
    	 }else{
        	 redirect('login','refresh');
	    }
             
    }

    function getTablaTramas($flgTipoTrama, $flg_exito) {
        $data = $this->m_utils->getDataAllTramaSisego($flgTipoTrama, $flg_exito);

        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>ORIGEN</th>
                            <th>ITEMPLAN</th>
                            <th>C&Oacute;DIGO</th>
                            <th>FECHA REGISTRO</th>
                            <th>STATUS</th>
                            <th>DESCRIPCI&Oacute;N</th>
                        </tr>
                    </thead>                    
                    <tbody>';
                                                                                                                                        
                foreach($data as $row){
                    $html .=' <tr>
                                <td>'.utf8_decode($row['origen']).'</td>
                                <td>'.utf8_decode($row['itemplan']).'</td>
                                <td>'.utf8_decode($row['ptr']).'</td>
                                <td>'.utf8_decode($row['fecha_registro']).'</td>                                
                                <td>'.utf8_decode($row['motivo_error']).'</td>
                                <td>'.utf8_decode($row['descripcion']).'</td>
                            </tr>';
                    }
                $html .='</tbody>
                    </table>';
                    
            return $html;
    }

    function filtrarTablaTramaSisego(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;

        try{ 
            $tipoTramaSisego = $this->input->post('tipoTramaSisego');
            $flg_exito = $this->input->post('flg_exito');

            $tipoTramaSisego  = ($tipoTramaSisego       == '') ? NULL : $tipoTramaSisego;
            $flg_exito        = ($flg_exito       == '') ? NULL : $flg_exito;
            
            $data['tablaTramaSisego'] = $this->getTablaTramas($tipoTramaSisego, $flg_exito);
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
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