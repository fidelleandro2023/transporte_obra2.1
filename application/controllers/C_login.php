<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class C_login extends CI_Controller {

    var $login;

    function __construct() {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_login/m_login');
        $this->output->set_header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');
        $this->load->library('table');
        $this->load->model('mf_utils/m_utils');
        $this->login = 'v_login'; //comentado por problemas GS 2019-08-23
        //$this->login     = 'v_mantenimiento';
        $this->notify404 = 'v_404';
        $this->load->library('Lib_utils');
    }

    public function index() {
        $data['error'] = null;
        $logedUser = $this->session->userdata('usernameSession');

        if ($logedUser != null) {
            $str = $this->m_login->getIdPerfil($this->session->userdata('userSession'));
            $flg_perfil = explode(",", $str);
            if ((in_array("49", $flg_perfil) || in_array("48", $flg_perfil) || in_array("50", $flg_perfil)) && count($flg_perfil) == 1) {
                redirect('getPanel', 'refresh'); //comentado por problemas GS 2019-08-23
                $this->load->view($this->login, $data);
            } else {
                redirect('consulta', 'refresh'); //comentado por problemas GS 2019-08-23
                $this->load->view($this->login, $data);
            }
        } else {

            $this->load->view($this->login, $data);
        }
    }

    public function logear() {
        $pagina = null;
        $flag = 0;
        $flagLog = 0;
        $flagcont = 0;
        $ruta = 'consultaItemfault';
        $user = $this->input->post('user');
        $pasw = $this->input->post('passwrd');
        $flg_panel = $this->input->post('flgPanel');
        $flagSINFIX = null;
        $data2 = array();
        $rutaSession = 'consultaItemfault';
        $resultado = $this->m_login->getUserInfo($user);
        $str = $this->m_login->getIdPerfil($user);
        $flg_perfil = explode(",", $str);
        log_message('error', count($flg_perfil));
if ((in_array("49", $flg_perfil) || in_array("48", $flg_perfil) || in_array("50", $flg_perfil)) && count($flg_perfil) == 1) {
            
//            echo "Existe Irix";
            if (in_array("50", $flg_perfil)) {
                $rutaSession = 'ItemFaultOC';
                $flg_perfil_gestion_mantenimiento = 1;
            } else {
                $rutaSession = 'consultaItemfault';
                $flg_perfil_gestion_mantenimiento = 1;
            }
            log_message('error', '-----------ssss---');
            log_message('error', $rutaSession);
        } else {
            $flg_perfil_gestion_mantenimiento = 0;
//            echo "No existe Irix";
        }

        //ACESO EXCLUSIVO POR PROBLEMAS DEL SERVIDOR, CUANDO SE SOLUCIONE BORRAR ESTO
        //$flgAccess = $this->m_utils->getIdUsuarioAccess($user);
        //if($flgAccess == 1) {
        #log_message('error',print_r($resultado, true));
        if ($resultado != null) {

            foreach ($resultado->result() as $row) {
                if (password_verify($pasw, $row->pass)) {
                    $this->session->set_userdata(array('idPersonaSession' => $row->id_usuario,
                        'usernameSession' => $row->nombres . ' ' . $row->ape_paterno,
                        'idPerfilSession' => $row->id_perfil,
                        'descPerfilSession' => $row->desc_perfil,
                        'zonasSession' => $row->zonas,
                        'userSession' => $row->usuario,
                        'eeccSession' => $row->id_eecc,
                        'idUsuarioSinfix' => $row->idUsuarioSinfix,
                        'correo' => $row->email,
                        'flg_flujo' => $flg_panel,
                        'isPerfilModMank' => $rutaSession,
                        'isPerfilModMant' => $flg_perfil_gestion_mantenimiento));

                    $this->session->set_userdata('permisosArbolTransporte', $this->makeListaPermisos($row->id_perfil, $flg_panel));
                    #_log(print_r($this->session->userdata('permisosArbol'), true));
                    $flagSINFIX = $row->idUsuarioSinfix;
                    $flagcont++;
                } else {
                    _log(print_r('no password_verify', true));
                }
            }

            if ($flagcont > 0) {

                $data2['flg'] = 0;
                if ($flagSINFIX != null) {

                    $data2['usuaSinfix'] = '1';
                    $data2['url'] = base_url() . 'c_panel';
                    $data2['encode'] = base64_encode($this->session->userdata('idUsuarioSinfix'));
                } else {
                    $data2['usuaSinfix'] = '0';
                    $data2['url'] = base_url() . 'c_panel';
                }
            } else {
                $data2['flg'] = '1';
            }
        } else {
            $data2['flg'] = '1';
        }
        //}    
        echo json_encode(array_map('utf8_encode', $data2));
    }

    function logOut() {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {
            $this->session->sess_destroy();
            // redirect('', 'refresh');
			//redirect('https://www.plandeobras.com/obra2.0', 'refresh');
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

    public function Prelogear() {
        $pagina = null;
        $flag = 0;
        $flagLog = 0;
        $user = $this->input->post('user');
        $pasw = $this->input->post('passwrd');

        $dataA = $this->m_login->getUserInfo($user);
        $data['flgCP'] = 0;
        // if ($dataA != null) {
        // $valor = $this->m_login->getVerificaPassword($user);
        // if ($valor == 1) {
        // $data['flgCP'] = 0;
        // } else {
        // $data['flgCP'] = 1;
        // }
        // } else {
        // $data['flgCP'] = -1;
        // }

        echo json_encode(array_map('utf8_encode', $data));
    }

    public function cambioPassword() {
        $user = $this->input->post('user');
        $dni = $this->input->post('dni');
        $newpass = $this->input->post('newpass');

        $data = $this->m_login->cambioContrasena($user, $dni, $newpass);

        if ($data['error'] == EXIT_SUCCESS) {
            $data1['flgNPass'] = 0;
        } else {
            $data1['flgNPass'] = 1;
        }

        echo json_encode(array_map('utf8_encode', $data1));
    }

    public function cambioPasswordI() {

        $newpass = $this->input->post('newpass');
        $user = $this->session->userdata('userSession');
        $dni = "";

        $data = $this->m_login->cambioContrasena($user, $dni, $newpass);

        if ($data['error'] == EXIT_SUCCESS) {
            $data1['flgNPass'] = 0;
        } else {
            $data1['flgNPass'] = 1;
        }
        echo json_encode(array_map('utf8_encode', $data1));
    }

}
