<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * 
 * 
 *
 */
class C_asignacion_modulo_responsable extends CI_Controller {

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
            
            $data['tbIncidencias'] = $this->makeHTLMTablaResponsableModulo($this->m_gestion_incidencias->getResponsablesActuales());
            
            $permisos = $this->session->userdata('permisosArbol');
            #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_CAP_CONFIGURACION, ID_PERMISO_HIJO_PQT_MANTE_PROYECTO, ID_MODULO_CAP);
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_CAP_CONFIGURACION, ID_PERMISO_HIJO_PQT_MANTE_PROYECTO, ID_MODULO_CAP);
            $data['opciones'] = $result['html'];
            /*if ($result['hasPermiso'] == true) {
                $this->load->view('vf_pqt_mantenimiento/v_proyecto', $data);
            } else {
                redirect('login', 'refresh');
            }*/
            
            $this->load->view('vf_incidencias/v_gestion_responsable_modulo', $data);
        } else {
            redirect('login', 'refresh');
        }
    
    }
    
    function makeHTLMTablaResponsableModulo($listaResponsableModulos){
        $html = '';
        
        // cabecera de usuario normal
        $html = '<table id="data-table" class="table table-bordered">';
        $html .= '<thead class="thead-default">';
        $html .= '<tr>';
        $html .= '<th>RESPONSABLE</th>';
        $html .= '<th>MODULOS</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        foreach ($listaResponsableModulos->result() as $row){
            $html .= '<tr>';
            $html .= '<td>';
            $html .= '<a '
                .'data-id_responsable="' . $row->id_responsable. '"'
                .'data-usuario="' . $row->usuario. '"'
                .'data-nombre="' . $row->nombre. '"'.
                ' style="color: blue;" onclick="abrirModalInfo(this)">'.$row->nombre.'</a>';
            $html .= '</td>';
            $html .= '<td>'.$row->modulos.'</td>';
            $html .= '</tr>';
        }
        $html .= '</tbody>';
        $html .= '</table>';
        return $html;
    }
    
    function makeHtmlModulos(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try {
            $idResponsable = $this->input->post('idUsuario');
            log_message('error', 'C_asignacion_modulo_responsable.makeHtmlModulos $idResponsable --> '.$idResponsable);
            $html = '';
            $listaCandidatos = $this->m_gestion_incidencias->getModulosLibresPorAsignar($idResponsable);
            $html .= "<option value=''></option>";
            foreach ($listaCandidatos->result() as $row){
                $html .= "<option value='".$row->id_modulo."'>".$row->descripcion."</option>";
            }
            $data['html'] = $html;
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function makeHtmlResponsablesCandidatos(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try {
            $idResponsable = $this->input->post('idUsuario');
            $html = '';
            $listaCandidatos = $this->m_gestion_incidencias->getAllUsuariosCandidatos();
            $html .= "<option value=''></option>";
            foreach ($listaCandidatos->result() as $row){
                if($idResponsable != "" && $idResponsable == $row->id_usuario){
                    $html .= "<option value='".$row->id_usuario."' selected>".$row->nombre." (".$row->usuario.")"."</option>";
                }else{
                    $html .= "<option value='".$row->id_usuario."'>".$row->nombre." (".$row->usuario.")"."</option>";
                }
            }
            $data['html'] = $html;
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
	
    function asignarResponsableModulo(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try {
        
            $idResponsable = $this->input->post('selectResponsable');
            $idModulo = $this->input->post('selectModulo');
            $estado = $this->input->post('selectEstado');
            
            $dataFormularioIncidente = array(
                'id_responsable'    => $idResponsable,
                'id_modulo'         => $idModulo,
                'estado'                 => $estado
            );
        
            $data = $this->m_gestion_incidencias->registrarIncidente($dataFormularioIncidente);
        
        }catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function makeHtmlTablaModulos(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try {
            $idResponsable = $this->input->post('idUsuario');
            log_message('error', 'C_asignacion_modulo_responsable.makeHtmlTablaModulos $idResponsable --> '.$idResponsable);
            $html = '';
            
            $html = '<table id="data-table" class="table table-bordered">';
            $html .= '<thead class="thead-default">';
            $html .= '<tr>';
            $html .= '<th>ID MODULO</th>';
            $html .= '<th>MODULO</th>';
            $html .= '<th>ESTADO</th>';
            $html .= '<th>CAMBIAR ESTADO</th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';
            if($idResponsable != ''){
                $listaCandidatos = $this->m_gestion_incidencias->getModulosByIdResponsable($idResponsable);
                foreach ($listaCandidatos->result() as $row){
                    $html .= '<tr>';
                    $html .= '<td>'.$row->id_modulo.'</td>';
                    $html .= '<td>'.$row->descripcion.'</td>';
                    $html .= '<td>'.$row->estado.'</td>';
                    
                    $html .= '<td>';
                    $html .= '<a data-id_responsable="' . $idResponsable . '"
                        data-id_modulo="' . $row->id_modulo . '"
                            data-estado="' . $row->estado . '" 
                        onclick="actualizarEstado(this)"><img alt="Cambiar Modulo" height="20px" width="20px" src="public/img/iconos/change.png"></a>        ';
                    $html .= '</td>';
                    
                    $html .= '</tr>';
                }
            }
            $html .= '</tbody>';
            $html .= '</table>';
            $data['html'] = $html;
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function asignarModulos(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try {
            $idResponsable = $this->input->post('idUsuario');
            $modulos = $this->input->post('modulos');
            $estado = $this->input->post('estado');
            $data = $this->m_gestion_incidencias->insertResponsableModulo($idResponsable, $modulos, $estado);
        }catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function cambiarEstadoModuloResponsable(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try {
            $idResponsable = $this->input->post('idUsuario');
            $modulo = $this->input->post('modulo');
            $estado = $this->input->post('estado');
            $nuevoEstado = $estado;
            
            if($estado == 'A'){
                $nuevoEstado = 'I';
            }else if($estado == 'I'){
                $nuevoEstado = 'A';
            }
            
            $update = array(
                'estado' => $nuevoEstado
            );
            
            $data = $this->m_gestion_incidencias->updateResponsableModulo($update, $idResponsable, $modulo);
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
}