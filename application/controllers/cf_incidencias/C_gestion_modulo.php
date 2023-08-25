<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class C_gestion_modulo extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_incidencias/m_gestion_incidencias');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }
    
    public function index()
    {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {
    
            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;
            
            $data['tbIncidencias'] = $this->makeHTLMTablaModulo($this->m_gestion_incidencias->getAllModulos());
            
            $permisos = $this->session->userdata('permisosArbol');
            #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_CAP_CONFIGURACION, ID_PERMISO_HIJO_PQT_MANTE_PROYECTO, ID_MODULO_CAP);
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_CAP_CONFIGURACION, ID_PERMISO_HIJO_PQT_MANTE_PROYECTO, ID_MODULO_CAP);
            $data['opciones'] = $result['html'];
            /*if ($result['hasPermiso'] == true) {
                $this->load->view('vf_pqt_mantenimiento/v_proyecto', $data);
            } else {
                redirect('login', 'refresh');
            }*/
            
            $this->load->view('vf_incidencias/v_gestion_modulo', $data);
        } else {
            redirect('login', 'refresh');
        }
    
    }
    
    function makeHTLMTablaModulo($listaModulos){
        $html = '';
        
        // cabecera de usuario normal
        $html = '<table id="data-table" class="table table-bordered">';
        $html .= '<thead class="thead-default">';
        $html .= '<tr>';
        $html .= '<th>ID MODULO</th>';
        $html .= '<th>DESCRIPCION</th>';
        $html .= '<th>COMENTARIO</th>';
        $html .= '<th>ESTADO</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        
        foreach ($listaModulos->result() as $row){
            $html .= '<tbody>';
            $html .= '<tr>';
            $html .= '<td>'.$row->id_modulo.'</td>';
            $html .= '<td>';
            $html .= '<a '
                .'data-id_modulo="' . $row->id_modulo. '"'
                .'data-descripcion="' . $row->descripcion. '"'
                .'data-comentario="' . $row->comentario. '"'
                .'data-estado="' . $row->estado. '"'.
                ' style="color: blue;" onclick="abrirModalInfo(this)">'.$row->descripcion.'</a>';
            $html .= '</td>';
            $html .= '<td>'.$row->comentario.'</td>';
            $html .= '<td>'.$row->estado.'</td>';
            $html .= '</tr>';
            $html .= '</tbody>';
        }
        
        $html .= '</table>';
        return $html;
    }
	
    function registrarModulo(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try {
        
            $modulo = $this->input->post('txtModulo');
            $descripcion = $this->input->post('txtDescripcion');
            $comentario = $this->input->post('txtComentario');
            $estado = $this->input->post('selectEstado');
            $hTipoActividad = $this->input->post('hTipoActividad');

            log_message('error', '$txtModulo .'.$modulo);
            log_message('error', '$txtDescripcion .'.$descripcion);
            log_message('error', '$txtComentario .'.$comentario);
            log_message('error', '$selectEstado .'.$estado);
            log_message('error', '$hTipoActividad .'.$hTipoActividad);
            
            if($hTipoActividad == "REGISTRAR"){
                $insert = array(
                    'id_modulo' => $modulo,
                    'descripcion' => $descripcion,
                    'comentario' => $comentario,
                    'estado'    => $estado
                );
                
                $data = $this->m_gestion_incidencias->insertModulo($insert);
            }else if($hTipoActividad == "ACTUALIZAR"){
                $update = array(
                    'descripcion' => $descripcion,
                    'comentario' => $comentario,
                    'estado'    => $estado
                );
                
                $data = $this->m_gestion_incidencias->updateModulo($update, $modulo);
            }
        }catch(Exception $e) {
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
    
    function validarModulo(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try {
            $modulo = $this->input->post('txtModulo');
            $results = $this->m_gestion_incidencias->countModuloById($modulo);
            $count = 0;
            foreach ($results->result() as $row){
                $count = $row->count;
            }
            if($count==0){
               $data['esIdValido'] = 1;
            }else{
               $data['esIdValido'] = 0;
            }
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
}