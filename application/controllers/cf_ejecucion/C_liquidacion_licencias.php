<?php
defined('BASEPATH') or exit('No direct script access allowed');
class C_liquidacion_licencias extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_ejecucion/M_generales');
        $this->load->model('mf_licencias/M_bandeja_itemplan_estacion');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index()
    {
        $logedUser = $this->session->userdata('usernameSession');
        $perfilUsuario = $this->session->userdata('descPerfilSession');
        if ($logedUser != null && ($perfilUsuario == 'ADMINISTRADOR' || $perfilUsuario == 'TELEFONICA')) {

            // $data['tabla'] = $this->makeHTLMTablaBandeja($this->m_bandeja_itemplan_estacion->getBandejaLicenciasPreLiqui());

            $data["extra"] = ' <link rel="stylesheet" href="' . base_url() . 'public/bower_components/notify/pnotify.custom.min.css">
            <link rel="stylesheet" href="' . base_url() . 'public/bower_components/bootstrap-validator/bootstrapValidator.min.css"></link>
            <link href="' . base_url() . 'public/vendors/bower_components/datatables/media/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/><link rel="stylesheet" href="' . base_url() . 'public/fancy/source/jquery.fancybox.css" type="text/css" media="screen">
            <link rel="stylesheet" href="' . base_url() . 'public/css/jasny-bootstrap.min.css">';

            $this->load->view('vf_layaout_sinfix/header', $data);
            $this->load->view('vf_layaout_sinfix/cabecera');
            $this->load->view('vf_layaout_sinfix/menu');
            $this->load->view('vf_licencias/v_bandeja_liquidacion_licencias', $data);

        } else {
            redirect('login', 'refresh');
        }
    }

    public function getItemPlanPreLiqui()
    {
        $idEmpresaColab = $this->session->userdata('eeccSession');
        $listaBandejaItemPlanPreliqui = $this->M_bandeja_itemplan_estacion->getItemPlansPreliquidados($idEmpresaColab);
        $data['tablaItemPlanPreliqui'] = $listaBandejaItemPlanPreliqui;
        echo json_encode($data);
    }

    public function getEntLicPreliqui()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $itemPlan = $this->input->post('itemPlan');
            $idEstacion = $this->input->post('idEstacion');
            $listaItemPlanDetalle = $this->M_bandeja_itemplan_estacion->getEntPreliquiLicDet($itemPlan, $idEstacion);
            if ($listaItemPlanDetalle != null && is_array($listaItemPlanDetalle)) {
                $data['error'] = EXIT_SUCCESS;
            }
            $data['tablaItemPlanDetalle'] = $listaItemPlanDetalle;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

    public function setIdItemPlanEvidencia()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $idItemPlanEstaLic = $this->input->post('idItemPlanEstaLic') ? $this->input->post('idItemPlanEstaLic') : null;
            unset($_SESSION["idItemPlanEstaDetPreliqui"]);
            $this->session->set_userdata('idItemPlanEstaDetPreliqui', $idItemPlanEstaLic);
            $data['error'] = EXIT_SUCCESS;

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

    public function subirEviLicPreliqui()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $idItemPlanEstaDetalle = $this->session->userdata('idItemPlanEstaDetPreliqui');

            $file = $_FILES["file"]["name"];
            $filetype = $_FILES["file"]["type"];
            $filesize = $_FILES["file"]["size"];
            $archivo = $_FILES["file"]["tmp_name"];

            if (!isset($archivo) || $filesize == 0) {
                throw new Exception("Este archivo est&aacute; da&ntilde;ado, ingrese otro porfavor!!");
            }

            $ubicacion = 'uploads/licencias';
            if (!is_dir($ubicacion)) {
                mkdir('uploads/licencias', 0777);
            }
            $subCarpeta = 'uploads/licencias/evidencia_fotos';
            if (!is_dir($subCarpeta)) {
                mkdir('uploads/licencias/evidencia_fotos', 0777);
            }
            $ubicEvidencia = 'uploads/licencias/evidencia_fotos/itemPlanEstaDet' . $idItemPlanEstaDetalle;
            if (!is_dir($ubicEvidencia)) {
                mkdir('uploads/licencias/evidencia_fotos/itemPlanEstaDet' . $idItemPlanEstaDetalle, 0777);
            }
            $ubicFinal = $ubicEvidencia . '/evidencia_liquidacion';
            if (!is_dir($ubicFinal)) {
                mkdir($ubicFinal, 0777);
            } else { //si existe borramos el archivo existente
                $filesExist = scandir($ubicFinal); //trae arreglo de archivos existentes en esa carpeta
                $ficherosEliminados = 0;
                foreach ($filesExist as $f) {
                    if (is_file($ubicFinal . "/" . $f)) {
                        if (unlink($ubicFinal . "/" . $f)) {
                            $ficherosEliminados++;
                        }
                    }
                }
            }

            $file2 = utf8_decode($file);

            if (utf8_decode($file) && move_uploaded_file($archivo, $ubicFinal . "/" . $file2)) {
                $arrayData = array('ruta_pdf_finalizacion' => $ubicFinal . "/" . $file2);
                $data = $this->M_bandeja_itemplan_estacion->updateRutaImagenItemPlanEstaLicencia($idItemPlanEstaDetalle, $arrayData);
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function getRutaEviLicPreliqui()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $idItemPlanEstaDetalle = $this->input->post('idItemPlanEstaDetalle');
            $rutaEvidencia = $this->M_bandeja_itemplan_estacion->getRutaEvidencia($idItemPlanEstaDetalle, 1, 1);
            if (isset($rutaEvidencia)) {
                $data['error'] = EXIT_SUCCESS;
                $data['rutaImagen'] = $rutaEvidencia;
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

    public function updateItemPLanLicPreliqui()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $idItemPlanEstaDetalle = $this->input->post('idItemPlanEstaDetalle');
            $idPersonaSession = $this->session->userdata('idPersonaSession');
            $rutaEvidencia = $this->M_bandeja_itemplan_estacion->getRutaEvidencia($idItemPlanEstaDetalle, 1, 1);
            if (isset($rutaEvidencia)) {
                $arrayUpdate = array('flg_validado' => 3, 'id_usuario_liquida' => $idPersonaSession, 'fecha_liquidacion'  => date("Y-m-d"));
                $data = $this->M_bandeja_itemplan_estacion->updateRutaImagenItemPlanEstaLicencia($idItemPlanEstaDetalle, $arrayUpdate);
            }else{
                throw new Exception("Debe subir el pdf de finalizaci&oacute;n para poder liquidar!!!");
            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

}
