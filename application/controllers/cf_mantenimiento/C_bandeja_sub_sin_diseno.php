<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_bandeja_sub_sin_diseno extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_mantenimiento/m_bandeja_sub_sin_diseno');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

	public function index() {  	   
        $logedUser = $this->session->userdata('usernameSession');
        $idEcc     = $this->session->userdata('eeccSession');
	    if($logedUser != null){
            $data['nombreUsuario'] =  $this->session->userdata('usernameSession');	
            $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');	               
            $permisos =  $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_MANTENIMIENTO, ID_PERMISO_HIJO_MANTENIMIENTO_SUB_SIN_DISENO);
            $data['title'] = 'REGISTRAR KIT MATERIAL PLANTA EXTERNA';
            $data['listaSubProy']  = $this->m_utils->getAllSubProyecto();
            $data['tablaSubProyecto'] = $this->tablaSubProyecto();
            $data['tablaSinDiseno'] = $this->tablaSubSinDiseno();
            //$data['listCmbItemplan'] = $this->m_utils->getListItemplanxEecc($idEcc);
            $data['opciones'] = $result['html'];
            $this->load->view('vf_mantenimiento/v_bandeja_sub_sin_diseno',$data);
	   }else{
	       redirect('login','refresh');
	   }
    }

    function tablaSubProyecto() {
        $arraySubProyecto = $this->m_bandeja_sub_sin_diseno->getSubProyecto();
        $html = '<table id="tbSubProyecto" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>Nro.</th>
                            <th>SUBPROYECTO</th>
                            <th>ACCI&Oacute;N</th>              
                        </tr>
                    </thead>                    
                    <tbody>';
        
        $style = null;
        $arrayStyle = array();
        $cont = 0;
        foreach($arraySubProyecto as $row){
            $cont++;
            $html .='   <tr>
                            <td>'.$cont.'</td>
                            <td>'.$row['subProyectoDesc'].'</td>
                            <td><input id="checkSubProyecto_'.$cont.'" type="checkbox" onchange="getDataInsert('.$row['idSubProy'].', '.$cont.')"/></td>                                                		                        
                        </tr>';
        }
            $html .='</tbody>
            </table>';
            return utf8_decode($html);
    }

    function tablaSubSinDiseno() {
        $arraySubSinDiseno = $this->m_bandeja_sub_sin_diseno->getSubProyectoSinDiseno();
        $html = '
                <table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>Nro.</th>
                            <th>SUBPROYECTO</th> 
                            <th>ACCI&Oacute;N</th>              
                        </tr>
                    </thead>                    
                    <tbody>';
        
        $style = null;
        $arrayStyle = array();
        $cont = 0;
        foreach($arraySubSinDiseno as $row){
            $cont++;
            // $checked  = ($row['kitIdMaterial'] == 1) ? 'checked' : null;
            // $disabled = ($row['kitIdMaterial'] == 1) ? 'disabled' : null;
            $html .='   <tr>
                            <td style="background:'.$style.'">'.$cont.'</td>
                            <td style="background:'.$style.'">'.$row['subProyectoDesc'].'</td>								
                            <td><i class="zmdi zmdi-hc-2x zmdi-delete" style="cursor:pointer" data-id_subproyecto="'.$row['idSubProyecto'].'" onclick="openModalEliminarMat($(this));"></i></td>                                                 		                        
                        </tr>';
        }
            $html .='</tbody>
            </table>';
            return utf8_decode($html);
    }

    function insertSubProyecto() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $arraySelectSubProyecto = $this->input->post('arraySelectSubProyecto');
            
            if(count($arraySelectSubProyecto) == 0) {
                throw new Exception('error, seleccionar subproyecto');
            }

            $data = $this->m_bandeja_sub_sin_diseno->insertSubProyecto($arraySelectSubProyecto);
            $data['tablaSubProyecto'] = $this->tablaSubProyecto();
            $data['tablaSinDiseno']   = $this->tablaSubSinDiseno();
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function deleteSubProyecto() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idSubProyecto = $this->input->post('idSubProyecto');

            if($idSubProyecto == null) {
                throw new Exception('error, SubProyecto null');
            }

            $data = $this->m_bandeja_sub_sin_diseno->deleteSubProyectoSinDiseno($idSubProyecto);
            $data['tablaSubProyecto'] = $this->tablaSubProyecto();
            $data['tablaSinDiseno']   = $this->tablaSubSinDiseno();
           // $data['tablaMaterial'] = $this->tablaMaterial($idSubProyecto, $idEstacion);
            $data['error']    = EXIT_SUCCESS;
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