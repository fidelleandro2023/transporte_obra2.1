<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_modulos_bucle extends CI_Controller{
    function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_utils/m_utils');
		$this->load->model('mf_login/m_login');
        $this->load->library('lib_utils');
        $this->load->helper('url');
        $this->load->library('excel'); 
    }
    
    public function moduloCAP(){
        $logedUser = $this->session->userdata('usernameSession');
        
        if($logedUser != null){
            $pepsub = 0;
            $user = $this->session->userdata('idPersonaSession');
            $zonasUser = $this->session->userdata('zonasSession');
        
            $fecha_dp = $this->m_utils->getMaxFechaFileDetallePlan();
            $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
            $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
            $permisos =  $this->session->userdata('permisosArbolTransporte');
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_PAQUETIZADO, ID_PERMISO_HIJO_PQT_BIENVENIDA, ID_MODULO_CAP);
            $data['opciones'] = $result['html'];
            $this->load->view('v_blank',$data);
        }else{
            redirect('login','refresh');
        }
    }
    
    public function moduloMantenimiento(){
		$nombreUsuariSession 	= $this->session->userdata('usernameSession');
		$idPersonaSession 		= $this->session->userdata('idPersonaSession'); //mientras el usuario en session sea el mismo de plan de obras
		$usuario 				= $this->session->userdata('userSession');
        if($idPersonaSession != null){
			$infoUsuario = $this->m_utils->getUsuarioByUsuario($usuario);
			
			if($infoUsuario != null) {
				$idPerfilesTransporte 		= $infoUsuario['id_perfil'];
				$idEmpresaColabTransporte 	= $infoUsuario['id_eecc'];
				
				$this->session->set_userdata(array('idPersonaSession' 		=> 	$idPersonaSession,
													'usernameSession' 		=> 	$nombreUsuariSession,
													'eeccSessionTransporte'	=>	$idEmpresaColabTransporte));//la eeecc de la bd transporte
													
				$data['nombreUsuario'] =  $this->session->userdata('nombreUsuariSession');
				$data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');			
				
				$this->session->set_userdata('permisosArbolTransporte', $this->makeListaPermisos($idPerfilesTransporte, 2));
				$permisos =  $this->session->userdata('permisosArbolTransporte');
				$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_PAQUETIZADO, ID_PERMISO_HIJO_PQT_BIENVENIDA, ID_MODULO_MANTENIMIENTO);
				$data['opciones'] = $result['html'];
				$this->load->view('v_blank',$data);
			} else {
				// redirect('login','refresh');
			}
			
        }else{
            redirect('login','refresh');
        }   
    }
    
    public function moduloAdministrativo(){
		$nombreUsuariSession 	= $this->session->userdata('usernameSession');
		$idPersonaSession 		= $this->session->userdata('idPersonaSession'); //mientras el usuario en session sea el mismo de plan de obras
		$usuario 				= $this->session->userdata('userSession');
        if($idPersonaSession != null){
			$infoUsuario = $this->m_utils->getUsuarioByUsuario($usuario);
			
			if($infoUsuario != null) {
				$idPerfilesTransporte 		= $infoUsuario['id_perfil'];
				$idEmpresaColabTransporte 	= $infoUsuario['id_eecc'];
				
				$this->session->set_userdata(array('idPersonaSession' 		=> 	$idPersonaSession,
													'usernameSession' 		=> 	$nombreUsuariSession,
													'eeccSessionTransporte'	=>	$idEmpresaColabTransporte));//la eeecc de la bd transporte
													
				$data['nombreUsuario'] =  $this->session->userdata('nombreUsuariSession');
				$data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');			
				
				$this->session->set_userdata('permisosArbolTransporte', $this->makeListaPermisos($idPerfilesTransporte, 2));
				$permisos =  $this->session->userdata('permisosArbolTransporte');
				$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_PAQUETIZADO, ID_PERMISO_HIJO_PQT_BIENVENIDA, ID_MODULO_ADMINISTRATIVO);
				$data['opciones'] = $result['html'];
				$this->load->view('v_blank',$data);
			} else {
				// redirect('login','refresh');
			}
			
        }else{
            redirect('login','refresh');
        }   
    }
	
	public function moduloTransporte(){		
		
        $nombreUsuariSession 	= $this->session->userdata('usernameSession');
		$idPersonaSession 		= $this->session->userdata('idPersonaSession'); //mientras el usuario en session sea el mismo de plan de obras
		$usuario 				= $this->session->userdata('userSession');
        if($idPersonaSession != null){
			$infoUsuario = $this->m_utils->getUsuarioByUsuario($usuario);
			
			if($infoUsuario != null) {
				$idPerfilesTransporte 		= $infoUsuario['id_perfil'];
				$idEmpresaColabTransporte 	= $infoUsuario['id_eecc'];
				
				$this->session->set_userdata(array('idPersonaSession' 		=> 	$idPersonaSession,
													'usernameSession' 		=> 	$nombreUsuariSession,
													'eeccSessionTransporte'	=>	$idEmpresaColabTransporte));//la eeecc de la bd transporte
													
				$data['nombreUsuario'] =  $this->session->userdata('nombreUsuariSession');
				$data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');			
				
				$this->session->set_userdata('permisosArbolTransporte', $this->makeListaPermisos($idPerfilesTransporte, 2));
				$permisos =  $this->session->userdata('permisosArbolTransporte');
				$result = $this->lib_utils->getHTMLPermisos($permisos, 54, ID_PERMISO_HIJO_PQT_CONSULTAS, ID_MODULO_PAQUETIZADO);
				$data['opciones'] = $result['html'];
				
				$this->load->view('v_blank',$data);
			} else {
				// redirect('login','refresh');
			}
			
        }else{
            redirect('login','refresh');
        }
    }
	
	function makeListaPermisos($idRol, $flg_panel) {
        $salida = array();

        $permisosPadres = $this->m_login->getPermisosPadre($idRol, $flg_panel);

        foreach ($permisosPadres->result() as $row) {
            $arrayFinal = array();
            $arrayMed = array();
            $arrayFinal['idPadre'] = $row->id_padre;
            $arrayFinal['nombrePadre'] = $row->descripcion;
            $arrayFinal['icono'] = $row->icono;
            $arrayFinal['fg_modulo'] = $row->fg_modulo;
            $arrayFinal['visible_fg'] = $row->visible_fg;
            $permisosHijos = $this->m_login->getPermisosHijos($idRol, $row->id_padre, $flg_panel);

            foreach ($permisosHijos->result() as $row) {
                $array = array();
                $array['id_permiso'] = $row->id_permiso;
                $array['nombreHijo'] = $row->descripcion;
                $array['route'] = $row->route;
                array_push($arrayMed, $array);
            }
            $arrayFinal['permisos'] = $arrayMed;
            array_push($salida, $arrayFinal);
        }
        return $salida;
    }
}

