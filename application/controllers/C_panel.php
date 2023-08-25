<?php defined('BASEPATH') OR exit('No direct script access allowed');

class C_panel extends CI_Controller {

    var $login;
	
	function __construct(){
		parent::__construct();
		$this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');	
		$this->load->model('mf_login/m_login');
		$this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
		$this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
		$this->output->set_header('Pragma: no-cache');
		$this->load->library('table');
		$this->load->library('user_agent');
		$this->load->model('mf_utils/m_utils');
		$this->login     = 'v_login'; //comentado por problemas GS 2019-08-23
		//$this->login     = 'v_mantenimiento';
		$this->notify404 = 'v_404';
		$this->load->library('Lib_utils');
    }

    function index() {
		
		//session_destroy();
	    // $logedUser = _getSesion('usuario');
  	    $data['titleHeader'] = 'Hola';

	    $val ="'modalInicioPadre'";
	    //publicacion para el de marketing : '.((_getSesion('roles')) == null ? null : ($logedUser != 'marketing' ? null : '<a href="#tab-2" onclick="showTabMain($(this));" class="mdl-layout__tab" style="cursor:pointer" id="tabPublicacion">Noticias</a>')).'
  	    $data['barraSec']    = '<div class="mdl-layout__tab-bar mdl-js-ripple-effect">
 
  	                                <a href="#tab-2" class="mdl-layout__tab" onclick="showTabMain($(this));">M&oacute;dulos - PO</a>
  	                                <a href="#tab-1" class="mdl-layout__tab">Informaci&oacute;n</a> 
									<a href="#tab-4" class="mdl-layout__tab">Tutoriales</a>
                                </div>';//href="#tab-3" href="#'.MURAL_PUBLICO.'"
  	    $data['btnSearch']   = '<a type="button" class="mdl-button mdl-js-button mdl-button--icon" onclick="setFocus(\'#searchMagic\')" id="openSearch">
                                    <i class="mdi mdi-magic md-0"></i>
                                </a>';
		/*
  	    $data['inputSearch'] = '<div class="mdl-header-input-group">
                                    <div class="mdl-icon">
                                        <i class="mdi mdi-magic md-0"></i>
                                    </div>
                                    <div class="mdl-textfield mdl-js-textfield" id="cont_inputtext_busqueda">
                                        <input class="mdl-textfield__input" type="text" id="searchMagic" onkeyup="activeSearchMagic()">
                                        <label class="mdl-textfield__label" for="searchMagic">Buscar un Sistema</label>
                                    </div>
                                    <div class="mdl-icon mdl-right">
                                        <a type="button" class="mdl-button mdl-js-button mdl-button--icon" id="closeSearch">
  	                                         <i class="mdi mdi-close"></i>
                                        </a>
                                    </div>
                                </div>';
*/
		$logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {
 	        $data['usuarioLogeado'] = $logedUser;

          	$roles  = null;
          	
	        $finalRoles = array();
	        $arrayTipoEnc = arraY();
	        $cantEnc     = 0;
			$cantFlgEnc  = 0;
			
      		$data['icono'] = "md md-star";
      		// $mod = null;

			//$data['tb']   = $this->createPanelPadre();
			//MENU
			//$data['arbolPermisosMantenimiento'] = __buildArbolPermisosPanel($this->session->userdata('idPersonaSession'), 24);
			$data['menu'] = $this->load->view('v_menu', $data, true);
			// $data['menu'] = $menu;
			$this->load->view('v_panel', $data);
          	
	    } else {
	        $this->session->sess_destroy();
	        redirect('login', 'refresh');
	    }
	}

	function createPanelPadre() {
		$idPersona = $this->session->userdata('idPersonaSession');
		$flgPanel  = $this->session->userdata('flg_flujo');

		$flgMostarPadreHijo = 1; // PADRE
		$arrayPadre = $this->m_utils->getDataPanelPermiso($idPersona, NULL, $flgMostarPadreHijo, $flgPanel);
        $result = null;
        $contador = 0;
        foreach ($arrayPadre as $row) {
            $opciones  = $this->createPanelHijos($row['id_padre'], $idPersona, $flgPanel);
			$arrayDataPadre = explode('|', $row['dataPadre']);

			$descPadre = utf8_decode($arrayDataPadre[0]);
			$logoIcon  = isset($arrayDataPadre[1]) ? $arrayDataPadre[1] : null;
            
            $opcionesHTML = null;
            $onclickHTML = null;
            $effectHTML = null;
            $not_app_style = null;
            $not_app_title = null;
			$app_icon  = "open_in_new";
			
			$opcionesHTML = $opciones;
			$onclickHTML = 'onclick="openSistema(\'card-main-'.$contador.'\');HeightCardMain($(this));"';

            
            $result .= '<div class="mdl-card mdl-app_content '.$not_app_style.'" id="card-main-'.$contador.'" '.$onclickHTML.' '.$not_app_title.'>
            		          <div class="mdl-card__supporting-text mdl-card__front inhr_overflow" '.$effectHTML.'>
            		              <img src="'.RUTA_IMG.$logoIcon.'">
            		              <h1 style="display:none;">'.$descPadre.'</h1>
            		              <div class="mdl-app_text">
            		                  <label>'.$descPadre.'</label>
            		                  <i class="mdi mdi-'.$app_icon.'"></i>
        		                  </div>
            		          </div>
            		          <div class="mdl-card__supporting-text mdl-card__back">
            		              <h4>'.$descPadre.'</h4>
            		              <ul>
            		                  '.$opcionesHTML.'
            		              </ul>
            		          </div>
            		      </div>';
            $contador++;
        }
        return $result;
	}
	

	function createPanelHijos($id_padre, $idPersona, $flgFlujo) {
        $opciones = null;
        $gris     = null;

		$flgTempNoNotas = 0;
		$flgMostarPadreHijo = 2;
		$arrayHijos = $this->m_utils->getDataPanelPermiso($idPersona, $id_padre, $flgMostarPadreHijo, $flgFlujo);
		foreach ($arrayHijos as $row) {
			$opciones .= '<li data-rippleria data-rippleria-duration="500" onclick="getRoute(\''.$row['route'].'\');" 
							data-toggle="tooltip" data-original-title="'.utf8_decode($row['descripcion']).'" data-placement="bottom">
							<i class="mdi mdi-open_in_new"></i><label>'.utf8_decode($row['descripcion']).'</label>
						  </li>';
		}
        
        return $opciones;
    }
}