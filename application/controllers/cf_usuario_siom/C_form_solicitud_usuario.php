<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_form_solicitud_usuario extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('mf_usuario_siom/m_form_solicitud_usuario');
        $this->load->model('mf_utils/m_utils');

        $this->load->library('lib_utils');
        $this->load->helper('url');

    }

    function index() {
        $logedUser = $this->session->userdata('usernameSession');
        $idUsuario = $this->session->userdata('idPersonaSession');

        if ($logedUser != null) {
            $data['nombreUsuario'] =  $this->session->userdata('usernameSession');	
            $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
            $permisos =  $this->session->userdata('permisosArbol');
            #$result = $this->lib_utils->getHTMLPermisos($permisos, 144, 216);
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_CAP_WORKFLOW, 216, ID_MODULO_CAP);
            $data['opciones'] = $result['html'];
            
            $data['cmbContratos']    = __buildCmbContratosAll();
            $data['cmbEmpresaColab'] = __buildCmbEmpresaColab(1);//FLG EMPRESACOLAB SOLICITUD
            $data['cmbZona']         = __buildCmbZona();
            $data['cmbPerfil']       = __buildCmbPerfil(1);

            $this->load->view('vf_usuario_siom/v_form_solicitud_usuario', $data);
        } else {
            $this->session->sess_destroy();
            redirect('login', 'refresh');
        }
    }

    function ingresarSolicitud() {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try{
            $cmbContrato     = $this->input->post('cmbContrato');    
            $cmbEmpresaColab = $this->input->post('cmbEmpresaColab');
            $inputNombreU    = $this->input->post('inputNombreU');
            $cmbPerfil       = $this->input->post('cmbPerfil');
            $inputDni        = $this->input->post('inputDni');
            $inputCorreo     = $this->input->post('inputCorreo');
            $inputTelfMov    = $this->input->post('inputTelfMov');
            $inputImei       = $this->input->post('inputImei');
            $cmbZona         = $this->input->post('cmbZona');
            $cmbTipoDoc      = $this->input->post('cmbTipoDoc');
            
            $countUsuarioPendiente = $this->m_utils->countSolicitudSiomByDni($inputDni, $estado=1);//PENDIENTE


            if($countUsuarioPendiente > 0) {
                throw new Exception('Ya tiene una solicitud enviada');        
            }

            $countUsuarioActivo = $this->m_utils->countSolicitudSiomByDni($inputDni, $estado=2);

            if($countUsuarioActivo > 0) {
                throw new Exception('Ya existe un usuario con este DNI');        
            }

            $countUsuarioActivo = $this->m_utils->countUsuarioActivo($inputDni);

            if($countUsuarioActivo > 0) {
                throw new Exception('Ya existe un usuario creado con este DNI');        
            }

            $idUsuario = $this->session->userdata('idPersonaSession');

            if($idUsuario == null) {
                throw new Exception('Su sesi&oacute;n a caducado, recargue nuevamente la pagina');
            }
			
			$codigoSolicitud = $this->m_form_solicitud_usuario->getCodigoSolicitud();

            $dataArray = array (
                                    'nombre'         => $inputNombreU,
                                    'idContrato'     => $cmbContrato,
                                    'array_perfil'   => implode(",", $cmbPerfil),
                                    'idEmpresaColab' => $cmbEmpresaColab,
                                    'dni'            => $inputDni,
                                    'email'          => $inputCorreo,
                                    'telefono'       => $inputTelfMov,
                                    'imei'           => $inputImei,
                                    'array_zona'     => implode(",", $cmbZona),
                                    //'usuario'        => $inputUsuario,
                                    //'clave'          => password_hash($pass, PASSWORD_DEFAULT),
                                    'estado'         => 1, //PENDIENTE
                                    'fecha_registro' => $this->m_utils->fechaActual(),
                                    'id_usuario'     => $idUsuario,
                                    'flg_tipo_solicitud' => 1,
                                    'codigo'             => $codigoSolicitud,
                                    'flg_tipo_doc'       => $cmbTipoDoc
                                );

            $data = $this->m_form_solicitud_usuario->insertSolicitudUsuario($dataArray);                    
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data)); 
    }

    function getDataModificacion() {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try{
            $dni = $this->input->post('dni');

            if($dni == null || $dni == '') {
                throw new Exception('ND');
            }
            $dataArray = $this->m_form_solicitud_usuario->getDataModificacion($dni);

            $countUsuarioPendiente = $this->m_utils->countSolicitudSiomByDni($dni, $estado=1);//PENDIENTE

            if($countUsuarioPendiente > 0) {
                throw new Exception('Ya tiene una solicitud enviada');        
            }

            if($dataArray['dni'] == null || $dataArray['dni'] == '') {
                throw new Exception('Este DNI no tiene un usuario que modificar');
            }
            
            $data['error']   = EXIT_SUCCESS;
            $data['dataMod'] = $dataArray;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

    function ingresoSolicitudModificacion() {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try{
            $cmbContrato     = $this->input->post('idContrato');    
            $cmbEmpresaColab = $this->input->post('idEmpresaColab');
            $inputNombreU    = $this->input->post('nombre');
            $cmbPerfil       = $this->input->post('arrayPerfil');
            $inputDni        = $this->input->post('inputDniM');
            $inputCorreo     = $this->input->post('inputCorreoM');
            $inputTelfMov    = $this->input->post('inputTelfMovM');
            $inputImei       = $this->input->post('imei');
            $cmbZona         = $this->input->post('cmbZonaM');
            // $inputUsuario    = $this->input->post('inputUsuarioM');
            // $pass            = $this->input->post('inputNuevaClave1');
            $flgTipoSol      = $this->input->post('cmbTipoSolicitud');
            $estado_usua     = $this->input->post('estado');
            $comentario      = $this->input->post('inputComentario');

            $countUsuarioPendiente = $this->m_utils->countSolicitudSiomByDni($inputDni, $estado=1);//PENDIENTE


            if($countUsuarioPendiente > 0) {
                throw new Exception('Ya tiene una solicitud enviada');        
            }

            $idUsuario = $this->session->userdata('idPersonaSession');

            if($idUsuario == null) {
                throw new Exception('Su sesi&oacute;n a caducado, recargue nuevamente la pagina');
            }
			
			$codigoSolicitud = $this->m_form_solicitud_usuario->getCodigoSolicitud();
			
            $dataArray = array (
                                    'nombre'         => $inputNombreU,
                                    'idContrato'     => $cmbContrato,
                                    'array_perfil'   => $cmbPerfil,
                                    'idEmpresaColab' => $cmbEmpresaColab,
                                    'dni'            => $inputDni,
                                    'email'          => $inputCorreo,
                                    'telefono'       => $inputTelfMov,
                                    'imei'           => $inputImei,
                                    'array_zona'     => implode(",", $cmbZona),
                                    'estado'         => 1, //PENDIENTE
                                    'fecha_registro' => $this->m_utils->fechaActual(),
                                    'id_usuario'     => $idUsuario,
                                    'flg_tipo_solicitud' => $flgTipoSol,
                                    'comentario'      => $comentario,
                                    'estado_usuario'  => $estado_usua,
									'codigo'          => $codigoSolicitud
                                );

            $data = $this->m_form_solicitud_usuario->insertSolicitudUsuario($dataArray);                    
        } catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data)); 
    }
}