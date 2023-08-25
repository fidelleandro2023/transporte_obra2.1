<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 *
 */
class C_nuevo_usuario extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_mantenimiento/m_nuevo_usuario');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index()
    {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {


            $data['notify'] = (isset($_GET['flag']) ? '<div class="alert alert-success" role="alert">
                                <strong>Usuario creado</strong> satisfactoriamente.
                            </div>' : '');


            /*$data['listaperfiles'] = $this->m_utils->getAllPerfiles();*/
            $data['listaperfiles'] = $this->m_utils->getAllPerfilessinAdmin();
            $data['listaeecc'] = $this->m_utils->getAllEECCINCLTDP();
            $data['listazonas'] = $this->m_utils->getAllZonal();

            $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
            $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
            $permisos =  $this->session->userdata('permisosArbol');
            #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_PLAN_DE_OBRA, ID_PERMISO_HIJO_BANDEJA_PRE_APROB);
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_MANTENIMIENTO_NUEVO_MODELO, ID_PERMISO_HIJO_MANTENIMIENTO_USUARIO, ID_MODULO_MANTENIMIENTO);
            $data['opciones'] = $result['html'];
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_mantenimiento/v_nuevo_usuario', $data);
            } else {
                redirect('login', 'refresh');
            }
        } else {
            redirect('login', 'refresh');
        }
    }


    public function getUsuario()
    {

        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            // Datos personales
            $nombres = trim($this->input->post('nombres'));
            $paterno = trim($this->input->post('paterno'));
            $materno = trim($this->input->post('materno'));
            $dni = $this->input->post('dni');
            $email = $this->input->post('email');
            $nombre = $nombres . ' ' . $paterno;

            // Perfil

            /***************Miguel Rios 11062018*******************/
            $accesoSINFIX = $this->input->post('accesoSinFix');
            /**************************************/

            $perfil = $this->input->post('perfil');
            $perfiles = implode(",", $perfil);
            $empresa = ($this->input->post('empresa') != '' ? $this->input->post('empresa') : '0');
            $zonasArray = $this->input->post('zonas');

            if ($zonasArray != '') {
                $zonas = implode(",", $zonasArray);
            } else {
                $zonas = '';
            }

            $usuario = $this->creaUsuario($nombres, $paterno);
            //$password = $this->creaPassword();
            $password = password_hash($dni, PASSWORD_DEFAULT);

            if ($accesoSINFIX == "on") {
                $accesoSINFIX = "1";
            }

            // Firma digital
            $rutaFirma = 'public/img';
            $firma = null;
            if ($_FILES['fileFirma']['name'] != null) {
                $upload = $rutaFirma . '/' . basename($_FILES['fileFirma']['name']);

                if (move_uploaded_file($_FILES['fileFirma']['tmp_name'], $upload)) {
                    $firma = basename($_FILES['fileFirma']['name']);
                    chmod($upload, 0777);
                } else {
                    throw new Exception('Hubo un problema con la carga del archivo 1 al servidor, comuniquese con el administrador.');
                }
            }

            $this->m_nuevo_usuario->insertUsuario($nombre, $usuario, $password, $empresa, $perfiles, $nombres, $materno, $paterno, $dni, $email, $zonas, $accesoSINFIX, $firma);
            /******************************/
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        redirect('mNuevoUsuario?flag=1', 'refresh');
        // if($data['error'] == 0){
        //     redirect('mNuevoUsuario?flag=1','refresh');
        // }else{
        //     echo json_encode(array_map('utf8_encode', $data));
        // }

    }

    public function creaUsuario($nombres, $paterno)
    {

        $nombreMinuscula = strtolower($nombres);
        $paternoMinuscula = strtolower($paterno);

        $username = substr($nombreMinuscula, 0, 1) . $paternoMinuscula;

        $dato = $this->m_nuevo_usuario->verificaUsername($username);

        if ($dato != null && $dato > 0) {
            $dato++;
            $username .= $dato;
        }

        return utf8_decode($username);
    }

    public function creaPassword()
    {
        $pass = 'temporal';
        return utf8_decode($pass);
    }
}
