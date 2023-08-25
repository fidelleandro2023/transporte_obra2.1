<?php
defined('BASEPATH') or exit('No direct script access allowed');
class C_licencias extends CI_Controller
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
        if ($logedUser != null) {
            //$data['tabla'] = $this->makeHTLMTablaBandeja($this->m_bandeja_itemplan_estacion->getBandejaItemPlanEstacion());
            $data["extra"] = ' <link rel="stylesheet" href="' . base_url() . 'public/bower_components/notify/pnotify.custom.min.css">
            <link rel="stylesheet" href="' . base_url() . 'public/bower_components/bootstrap-validator/bootstrapValidator.min.css"></link>
            <link href="' . base_url() . 'public/vendors/bower_components/datatables/media/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/><link rel="stylesheet" href="' . base_url() . 'public/fancy/source/jquery.fancybox.css" type="text/css" media="screen">
            <link rel="stylesheet" href="' . base_url() . 'public/css/jasny-bootstrap.min.css">';

            $permisos = $this->session->userdata('permisosArbol');
            $this->load->view('vf_layaout_sinfix/header', $data);
            $this->load->view('vf_layaout_sinfix/cabecera');
            $this->load->view('vf_layaout_sinfix/menu');
            $this->load->view('vf_licencias/v_bandeja_licencias', $data);
            //$this->load->view('vf_layaout_sinfix/footer');

            // $this->load->view('recursos_sinfix/js');
            // $this->load->view('recursos_sinfix/datatable2', $data);
            //$this->load->view('recursos_sinfix/js_registro_itemPlan_estacion',$data);
            //$this->load->view('recursos_sinfix/fancy', $data);
        } else {
            redirect('login', 'refresh');
        }
    }

    public function getDistritos()
    {
        $listaDistritos = $this->M_bandeja_itemplan_estacion->getAllDistritos();
        $data['arrayDistritos'] = $listaDistritos;
        echo json_encode($data);
    }

    public function getProyectos()
    {
        $listaProyectos = $this->M_generales->ListarProyecto();
        $data['arrayProyectos'] = $listaProyectos->result();
        echo json_encode($data);
    }

    public function getSubProyectos()
    {
        $idProyecto = $this->input->post('idProyecto');
        $listaSubProyectos = $this->M_generales->ListarSubProyecto($idProyecto);
        $data['arraySubProyectos'] = $listaSubProyectos->result();
        echo json_encode($data);
    }

    public function getRegiones() //son jefaturas, ya no regiones

    {
        $listaJefaturas = $this->m_utils->getJefaturaCmb();
        $data['arrayJefaturas'] = $listaJefaturas;
        echo json_encode($data);
    }

    public function getEmpresasColab()
    {
        $listaEmpresasColab = $this->M_bandeja_itemplan_estacion->getEmpresasColab();
        $data['arrayEmpresasColab'] = $listaEmpresasColab;
        echo json_encode($data);
    }
    
    public function getFase()
    {
        $listaFase = $this->M_bandeja_itemplan_estacion->getFase();
        $data['arrayFase'] = $listaFase;
        echo json_encode($data);
    }
    
    public function getTablaItemPlanUsuario()
    {
        $idEmpresaColab = $this->session->userdata('eeccSession');
        $query = null;
        $listaBandejaItemPlan = $this->M_bandeja_itemplan_estacion->getBandejaItemPlanEstacion($query, $idEmpresaColab);
        $data['tablaItems'] = $listaBandejaItemPlan;
        echo json_encode($data);
    }
    public function getTablaItemPlan()
    {
        $arrayFiltros = $this->input->post('arrayFiltros');
        $idEmpresaColab = null;
        $query = null;
        if (is_array($arrayFiltros)) {
            if ($arrayFiltros['idEmpresaColab'] != 0) {
                $query .= " AND tb.idEmpresaColab = " . $arrayFiltros['idEmpresaColab'] . "";
            }
            if ($arrayFiltros['idProyecto'] != 0) {
                $query .= " AND tb.idProyecto = " . $arrayFiltros['idProyecto'] . "";
            }
            if ($arrayFiltros['idSubProyecto'] != 0) {
                $query .= " AND tb.idSubProyecto = " . $arrayFiltros['idSubProyecto'] . "";
            }
            if ($arrayFiltros['jefatura'] != null) {
                $query .= " AND tb.jefatura = " . "'" . $arrayFiltros['jefatura'] . "'" . "";
            }
            if ($arrayFiltros['idFase'] != null) {
                $query .= " AND tb.idFase = " . "'" . $arrayFiltros['idFase'] . "'" . "";
            }
        }
        $listaBandejaItemPlan = $this->M_bandeja_itemplan_estacion->getBandejaItemPlanEstacion($query, $idEmpresaColab);
        $data['tablaItems'] = $listaBandejaItemPlan;
        echo json_encode($data);
    }

    public function getInfoEntidades()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $itemPlan = $this->input->post('itemPlan');
            $idEstacion = $this->input->post('idEstacion');
            $listaItemPlanDetalle = $this->M_bandeja_itemplan_estacion->getItemPLanEstacionLincenciaDet($itemPlan, $idEstacion);
            if ($listaItemPlanDetalle != null && is_array($listaItemPlanDetalle)) {
                $data['error'] = 0;
            }
            $data['tablaItemPlanDetalle'] = $listaItemPlanDetalle;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

    public function setUserDataEvidencia()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $idDetalle = $this->input->post('idDetalle') ? $this->input->post('idDetalle') : null;
            $flgTipo = $this->input->post('flgTipo');
            $idItemPlanEstaDetalle = $this->input->post('idItemPlanEstaDetalle');
            $descReembolso = $this->input->post('descReembolso');

            if ($flgTipo == 1) {
                unset($_SESSION["idItemPlanEstaDet"]);
                $this->session->set_userdata('idItemPlanEstaDet', $idDetalle);
                $data['error'] = EXIT_SUCCESS;
            } else if ($flgTipo == 2) {

                if ($idDetalle != null) {
                    // $arrayUpdate = array('desc_reembolso' => $descReembolso,
                    //     'fecha_modificacion' => date("Y-m-d"));
                    // $data = $this->M_bandeja_itemplan_estacion->updateComprobanteLicencia($idDetalle, $arrayUpdate);
                    unset($_SESSION["idComprobanteDet"]);
                    $this->session->set_userdata('idComprobanteDet', $idDetalle);
                    $data['error'] = EXIT_SUCCESS;
                } else {
                    $arrayInsert = array('desc_reembolso' => $descReembolso,
                        'fecha_emision' => null,
                        'monto' => null,
                        'fecha_registro' => date("Y-m-d"),
                        'fecha_modificacion' => null,
                        'ruta_foto' => null,
                        'estado_valida' => 0,
                        'iditemplan_estacion_licencia_det' => $idItemPlanEstaDetalle);
                    $data = $this->M_bandeja_itemplan_estacion->insertarComprobanteLicencia($arrayInsert);
                    if (isset($data['idReembolso'])) {
                        $data['idComprobante'] = $data['idReembolso'];
                        unset($_SESSION["idComprobanteDet"]);
                        $this->session->set_userdata('idComprobanteDet', $data['idReembolso']);
                    }
                }

            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

    public function subirEvidenciaItemPlanDetalle()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $idItemPlanEstaDetalle = $this->session->userdata('idItemPlanEstaDet');

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
            } else { //si existe borramos el archivo existente
                $filesExist = scandir($ubicEvidencia); //trae arreglo de archivos existentes en esa carpeta
                $ficherosEliminados = 0;
                foreach ($filesExist as $f) {
                    if (is_file($ubicEvidencia . "/" . $f)) {
                        if (unlink($ubicEvidencia . "/" . $f)) {
                            $ficherosEliminados++;
                        }
                    }
                }
            }

            $file2 = utf8_decode($file);

            if (utf8_decode($file) && move_uploaded_file($archivo, $ubicEvidencia . "/" . $file2)) {
                $arrayData = array('ruta_pdf' => $ubicEvidencia . "/" . $file2);
                $data = $this->M_bandeja_itemplan_estacion->updateRutaImagenItemPlanEstaLicencia($idItemPlanEstaDetalle, $arrayData);
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function subirFotoComprobanteDetalle()
    {
        $data['error'] = EXIT_ERROR;

        $idComprobanteDetalle = $this->session->userdata('idComprobanteDet');

        $file = $_FILES["file"]["name"];
        $filetype = $_FILES["file"]["type"];
        $filesize = $_FILES["file"]["size"];
        $imagen = $_FILES['file']['tmp_name'];

        $ubicacion = 'uploads/licencias';
        if (!is_dir($ubicacion)) {
            mkdir('uploads/licencias', 0777);
        }
        $subCarpeta = 'uploads/licencias/evidencia_fotos';
        if (!is_dir($subCarpeta)) {
            mkdir('uploads/licencias/evidencia_fotos', 0777);
        }
        $subCarpeta2 = 'uploads/licencias/evidencia_fotos/comprobantes';
        if (!is_dir($subCarpeta2)) {
            mkdir('uploads/licencias/evidencia_fotos/comprobantes', 0777);
        }
        $ubicComprobante = 'uploads/licencias/evidencia_fotos/comprobantes/comprobante' . $idComprobanteDetalle;

        if (!is_dir($ubicComprobante)) {
            mkdir('uploads/licencias/evidencia_fotos/comprobantes/comprobante' . $idComprobanteDetalle, 0777);
        } else { //si existe borramos el archivo existente
            $filesExist = scandir($ubicComprobante); //trae arreglo de archivos existentes en esa carpeta
            $ficherosEliminados = 0;
            foreach ($filesExist as $f) {
                if (is_file($ubicComprobante . "/" . $f)) {
                    if (unlink($ubicComprobante . "/" . $f)) {
                        $ficherosEliminados++;
                    }
                }
            }
        }

        $file2 = utf8_decode($file);
        if (utf8_decode($file) && move_uploaded_file($imagen, $ubicComprobante . "/" . $file2)) {
            $arrayData = array('ruta_foto' => $ubicComprobante . "/" . $file2,
                'fecha_modificacion' => date("Y-m-d"));
            $data = $this->M_bandeja_itemplan_estacion->updateComprobanteLicencia($idComprobanteDetalle, $arrayData);

        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function getComprobantesxItemPlanDetalle()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $idItemPlanDetalle = $this->input->post('idItemPlanEstaDetalle');
            $listaComprobantes = $this->M_bandeja_itemplan_estacion->getComprobantesxItemPlanDet($idItemPlanDetalle);
            if ($listaComprobantes != null && is_array($listaComprobantes)) {
                $data['error'] = EXIT_SUCCESS;
            }
            $data['arrayTablaComprobantes'] = $listaComprobantes;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

    public function updateItemPlanEstacionLicenciaDetalle()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $listaItemPlanDetalle = $this->input->post('listaItemPlanDetalle');

            $listaItemPlanDetalle['flg_validado'] = 1;

            if ($listaItemPlanDetalle['idDistrito'] == 0 || !$listaItemPlanDetalle['idDistrito']) {
                $listaItemPlanDetalle['idDistrito'] = null;
            }
            if ($listaItemPlanDetalle['flg_tipo'] == 0 || !$listaItemPlanDetalle['flg_tipo']) {
                $listaItemPlanDetalle['flg_tipo'] = null;
            }

            $data = $this->M_bandeja_itemplan_estacion->updateRutaImagenItemPlanEstaLicencia($listaItemPlanDetalle['iditemplan_estacion_licencia_det'], $listaItemPlanDetalle);

            // $this->db->update_batch('itemplan_estacion_licencia_det', $listaItemPlanDetalle, 'iditemplan_estacion_licencia_det');

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

    public function updateComprobantePreliquidado()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $listaComprobante = $this->input->post('listaComprobante');
            $flgValidaEvi = $this->input->post('flgValidaEvi');
            $idPersonaSession = $this->session->userdata('idPersonaSession');
            $idReembolso = $listaComprobante['idReembolso'];

            if ($flgValidaEvi == 1) {
                $listaComprobante['flg_valida_evidencia'] = 1;
                $listaComprobante['estado_valida'] = 2;
            } else {
                $listaComprobante['flg_valida_evidencia'] = 0;
            }
            unset($listaComprobante['ruta_foto']);
            unset($listaComprobante['idReembolso']);

            $data = $this->M_bandeja_itemplan_estacion->updateComprobanteLicencia($idReembolso, $listaComprobante);
            $arrayUpdate = array('flg_validado' => 2, 'id_usuario_valida' => $idPersonaSession, 'fecha_valida' => date("Y-m-d"));
            $data = $this->M_bandeja_itemplan_estacion->updateRutaImagenItemPlanEstaLicencia($listaComprobante['iditemplan_estacion_licencia_det'], $arrayUpdate);

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

    public function saveComprobanteDetalle()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $dataInsert = $this->input->post('objInsertComprobante');
            $idItemPlanEstaDetalle = $this->input->post('idItemPlanEstaDetalle');
            $idPersonaSession = $this->session->userdata('idPersonaSession');
            $flgValidaEvi = $this->input->post('flgValidaEvi');
            $idReembolso = $dataInsert['idReembolso'];

            if ($flgValidaEvi == 1) {
                $dataInsert['flg_valida_evidencia'] = 1;
                $dataInsert['estado_valida'] = 2;
            } else {
                $dataInsert['flg_valida_evidencia'] = 0;
                $dataInsert['estado_valida'] = 1;
            }

            $dataInsert['iditemplan_estacion_licencia_det'] = $idItemPlanEstaDetalle;
            $dataInsert['fecha_modificacion'] = date("Y-m-d");

            unset($dataInsert['ruta_foto']);
            unset($dataInsert['idReembolso']);

            $data = $this->M_bandeja_itemplan_estacion->updateComprobanteLicencia($idReembolso, $dataInsert);
            $arrayUpdate = array('flg_validado' => 2, 'id_usuario_valida' => $idPersonaSession, 'fecha_valida' => date("Y-m-d"));
            $data = $this->M_bandeja_itemplan_estacion->updateRutaImagenItemPlanEstaLicencia($idItemPlanEstaDetalle, $arrayUpdate);

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

    public function saveComproAdministrativo()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $dataInsert = $this->input->post('objInsertComprobante');
            $idItemPlanEstaDetalle = $this->input->post('idItemPlanEstaDetalle');
            $idPersonaSession = $this->session->userdata('idPersonaSession');
            $flg_preliqui_admin = $this->input->post('flg_preliqui_admin');
            $idReembolso = $this->input->post('idComprobante') ? $this->input->post('idComprobante') : null;

            if ($flg_preliqui_admin == 1) {
                $dataInsert['flg_preliqui_admin'] = 1;
                $dataInsert['estado_valida'] = 2;
            } else {
                $dataInsert['flg_preliqui_admin'] = 0;
                $dataInsert['estado_valida'] = 1;
            }
            unset($dataInsert['idReembolso']);
            unset($dataInsert['ruta_foto']);
            unset($dataInsert['flg_valida_evidencia']);

            if (isset($idReembolso)) { // si esta seteado el valor y no es nulo
                $dataInsert['fecha_modificacion'] = date("Y-m-d");
                $data = $this->M_bandeja_itemplan_estacion->updateComprobanteLicencia($idDetalle, $arrayUpdate);
            } else {
                $dataInsert['iditemplan_estacion_licencia_det'] = $idItemPlanEstaDetalle;
                $dataInsert['fecha_registro'] = date("Y-m-d");
                $data = $this->M_bandeja_itemplan_estacion->insertarComprobanteLicencia($dataInsert);

            }
            if ($data['error'] == EXIT_SUCCESS) {
                $arrayUpdate = array('flg_validado' => 2, 'id_usuario_valida' => $idPersonaSession, 'fecha_valida' => date("Y-m-d"));
                $data = $this->M_bandeja_itemplan_estacion->updateRutaImagenItemPlanEstaLicencia($idItemPlanEstaDetalle, $arrayUpdate);
            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

    public function getRutaEvidenciaItemPlanEsta()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $idItemPlanEstaDetalle = $this->input->post('idItemPlanEstaDetalle');
            $rutaEvidencia = $this->M_bandeja_itemplan_estacion->getRutaEvidencia($idItemPlanEstaDetalle, 1, null);
            if (isset($rutaEvidencia)) {
                $data['error'] = EXIT_SUCCESS;
                $data['rutaImagen'] = $rutaEvidencia;
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

    public function getRutaEvidenciaReembolso()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $idReembolso = $this->input->post('idReembolso');
            $rutaEvidencia = $this->M_bandeja_itemplan_estacion->getRutaEvidencia($idReembolso, 2, null);
            if (isset($rutaEvidencia)) {
                $data['error'] = EXIT_SUCCESS;
                $data['rutaImagen'] = $rutaEvidencia;
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

    public function getAcotacionesxItemPlanDetalle()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $idItemPlanDetalle = $this->input->post('idItemPlanEstaDetalle');
            $listaAcotaciones = $this->M_bandeja_itemplan_estacion->getAcotacionesxItemPlanDet($idItemPlanDetalle);
            if ($listaAcotaciones != null && is_array($listaAcotaciones)) {
                $data['error'] = EXIT_SUCCESS;
            }
            $data['arrayTablaAcotaciones'] = $listaAcotaciones;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

    public function setUserDataEvidenciaAcota()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $idAcotacion = $this->input->post('idAcotacion') ? $this->input->post('idAcotacion') : null;
            $descAcotacion = $this->input->post('descAcota');
            $idItemPlanEstaDetalle = $this->input->post('idItemPlanEstaDetalle');

            if ($idAcotacion != null) {
                $arrayUpdate = array('fecha_modificacion' => date("Y-m-d"),
                    'desc_acotacion' => $descAcotacion);
                $data = $this->M_bandeja_itemplan_estacion->updateAcotacionLicencia($idAcotacion, $arrayUpdate);
                unset($_SESSION["idAcotacion"]);
                $this->session->set_userdata('idAcotacion', $idAcotacion);
            } else {
                $arrayInsert = array('desc_acotacion' => $descAcotacion,
                    'fecha_acotacion' => null,
                    'monto' => null,
                    'fecha_registro' => date("Y-m-d"),
                    'fecha_modificacion' => null,
                    'ruta_foto' => null,
                    'estado_valida' => 0,
                    'iditemplan_estacion_licencia_det' => $idItemPlanEstaDetalle);
                $data = $this->M_bandeja_itemplan_estacion->insertarAcotacionLicencia($arrayInsert);
                $idAcotacionTemp = $data['idAcotacion'];
                if (isset($idAcotacionTemp)) {
                    $data['idAcotacion'] = $idAcotacionTemp;
                    unset($_SESSION["idAcotacion"]);
                    $this->session->set_userdata('idAcotacion', $idAcotacionTemp);
                }
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

    public function subirFotoAcotaDetalle()
    {
        $data['error'] = EXIT_ERROR;

        $idAcotacion = $this->session->userdata('idAcotacion') ? $this->session->userdata('idAcotacion') : null;

        $file = $_FILES["file"]["name"];
        $filetype = $_FILES["file"]["type"];
        $filesize = $_FILES["file"]["size"];

        $imagen = $_FILES['file']['tmp_name'];

        $ubicacion = 'uploads/licencias';
        if (!is_dir($ubicacion)) {
            mkdir('uploads/licencias', 0777);
        }
        $subCarpeta = 'uploads/licencias/evidencia_fotos';
        if (!is_dir($subCarpeta)) {
            mkdir('uploads/licencias/evidencia_fotos', 0777);
        }
        $subCarpeta2 = 'uploads/licencias/evidencia_fotos/acotaciones';
        if (!is_dir($subCarpeta2)) {
            mkdir('uploads/licencias/evidencia_fotos/acotaciones', 0777);
        }
        $ubicAcota = 'uploads/licencias/evidencia_fotos/acotaciones/acotacion' . $idAcotacion;
        if (!is_dir($ubicAcota)) {
            mkdir('uploads/licencias/evidencia_fotos/acotaciones/acotacion' . $idAcotacion, 0777);
        } else { //si existe borramos el archivo existente
            $filesExist = scandir($ubicAcota); //trae arreglo de archivos existentes en esa carpeta
            $ficherosEliminados = 0;
            foreach ($filesExist as $f) {
                if (is_file($ubicAcota . "/" . $f)) {
                    if (unlink($ubicAcota . "/" . $f)) {
                        $ficherosEliminados++;
                    }
                }
            }
        }

        $file2 = utf8_decode($file);
        if (utf8_decode($file) && move_uploaded_file($imagen, $ubicAcota . "/" . $file2)) {
            $arrayData = array('ruta_foto' => $ubicAcota . "/" . $file2,
                'fecha_modificacion' => date("Y-m-d"));

            $data = $this->M_bandeja_itemplan_estacion->updateAcotacionLicencia($idAcotacion, $arrayData);
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function getRutaEvidenciaAcotacion()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $idAcotacion = $this->input->post('idAcotacion');
            $rutaEvidencia = $this->M_bandeja_itemplan_estacion->getRutaEvidencia($idAcotacion, 3, null);
            if (isset($rutaEvidencia)) {
                $data['error'] = EXIT_SUCCESS;
                $data['rutaImagen'] = $rutaEvidencia;
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

    public function saveAcotacionDetalle()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $dataInsert = $this->input->post('objInsertAcota');
            $idItemPlanEstaDetalle = $this->input->post('idItemPlanEstaDetalle');
            $idAcotacion_temp = $this->session->userdata('idAcotacion');
            $idPersonaSession = $this->session->userdata('idPersonaSession');

            $dataInsert['iditemplan_estacion_licencia_det'] = $idItemPlanEstaDetalle;
            $dataInsert['fecha_modificacion'] = date("Y-m-d");
            $dataInsert['estado_valida'] = 1;
            unset($dataInsert['ruta_foto']);
            $data = $this->M_bandeja_itemplan_estacion->updateAcotacionLicencia($idAcotacion_temp, $dataInsert);
            if ($data['error'] == EXIT_SUCCESS) {
                $arrayUpdate = array('flg_acotacion_valida' => 1, 'id_usuario_valida_acota' => $idPersonaSession);
                $data = $this->M_bandeja_itemplan_estacion->updateRutaImagenItemPlanEstaLicencia($idItemPlanEstaDetalle, $arrayUpdate);
            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

    public function updateAcotacionPreliquidado()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $listaAcotacion = $this->input->post('listaAcotacion');
            $idPersonaSession = $this->session->userdata('idPersonaSession');
            $data = $this->M_bandeja_itemplan_estacion->updateAcotacionLicencia($listaAcotacion['idAcotacion'], $listaAcotacion);
            if ($data['error'] == EXIT_SUCCESS) {
                $arrayUpdate = array('flg_acotacion_valida' => 1, 'id_usuario_valida_acota' => $idPersonaSession);
                $data = $this->M_bandeja_itemplan_estacion->updateRutaImagenItemPlanEstaLicencia($listaAcotacion['iditemplan_estacion_licencia_det'], $arrayUpdate);
            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

    public function updateItemPlanEstaDetByNroCheque()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $idItemPlanEstaDetalle = $this->input->post('idItemPlanEstaDetalle');
            $nroCheque = $this->input->post('nroCheque');
            $arrayUpdate = array('nro_cheque' => $nroCheque);
            $data = $this->M_bandeja_itemplan_estacion->updateRutaImagenItemPlanEstaLicencia($idItemPlanEstaDetalle, $arrayUpdate);

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

    public function deleteComprobante()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        try {
            $idReembolso = $this->input->post('idReembolso');
            $idItemPlanEstaDetalle = $this->input->post('idItemPlanDet');
            $rutaImg = $this->M_bandeja_itemplan_estacion->getRutaEvidencia($idReembolso, 2, null);
            $flgValidaEvidencia = $this->M_bandeja_itemplan_estacion->getFlgValidaComprobante($idReembolso);
            if ($flgValidaEvidencia == 0 || $flgValidaEvidencia == null) {
                $data = $this->M_bandeja_itemplan_estacion->deleteComprobante($idReembolso);

            } else {
                throw new Exception("No puede eliminar un comprobante liquidado");
            }

            if ($data['error'] == EXIT_SUCCESS && isset($rutaImg)) {
                $ubicComprobante = 'uploads/licencias/evidencia_fotos/comprobantes/comprobante' . $idReembolso;
                $filesExist = scandir($ubicComprobante); //trae arreglo de archivos existentes en esa carpeta
                $ficherosEliminados = 0;
                foreach ($filesExist as $f) {
                    if (is_file($ubicComprobante . "/" . $f)) {
                        if (unlink($ubicComprobante . "/" . $f)) {
                            $ficherosEliminados++;
                        }
                    }
                }
                rmdir($ubicComprobante);
                //updatear en item plan detalle
                $arrayUpdate = array('flg_validado' => 0, 'id_usuario_valida' => null, 'fecha_valida' => null);
                $data = $this->M_bandeja_itemplan_estacion->updateRutaImagenItemPlanEstaLicencia($idItemPlanEstaDetalle, $arrayUpdate);
            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

    public function makeFORMEntidades($listaEntidades)
    {
        // $html = '';
        $html = '<option value="">Seleccionar Entidad(es)</option>';

        foreach ($listaEntidades as $row) {
            // $html .= '  <div class="col-4" id="' . $row->idEntidad . '" >
            //                 <input type="checkbox" id="Ent' . $row->idEntidad . '" class="custom-control-input" data-ent="' . $row->idEntidad . '" onchange="agregarEntidades(' . $row->idEntidad . ',' . ($row->disabled == null ? 0 : 1) . ')" ' . ($row->marcado == 1 && $row->desc_entidad != 'MTC' ? 'checked' : '') . '  ' . ($row->disabled == 1 && $row->desc_entidad != 'MTC' ? 'disabled' : '') . '>
            //                 <label for="Ent' . $row->idEntidad . '" >' . $row->desc_entidad . '</label>
            //            </div>';
            $html .= '<option value="' . $row->idEntidad . '">' . $row->desc_entidad . '</option>';
        }
        $data['html'] = utf8_decode($html);
        return $data;
    }

    public function getEntidadesLicencia()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        try {
            $itemPlan = $this->input->post('itemplan');
            $idEstacion = $this->input->post('idEstacion');
            // $countEnt = $this->M_bandeja_itemplan_estacion->getCountEntValida($itemPlan, $idEstacion);
            // $dataEntidades = $this->makeFORMEntidades($this->M_bandeja_itemplan_estacion->getEntidadesForRegi($itemPlan, $idEstacion, $countEnt));
            $dataEntidades = $this->makeFORMEntidades($this->M_bandeja_itemplan_estacion->getAllEntidades());
            $data['htmlEntidades'] = $dataEntidades['html'];
            if (isset($data['htmlEntidades'])) {
                $data['error'] = EXIT_SUCCESS;
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

    public function registrarEntidades()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        try {
            $itemPlan = $this->input->post('itemplan');
            $idEstacion = $this->input->post('idEstacion');
            $idPersonaSession = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;
            $idEntidad = $this->input->post('idEntidad') ? $this->input->post('idEntidad') : null;

            if ($idPersonaSession == null) {
                throw new Exception('Su sesion ha experiado, ingrese nuevamente!!');
            }
            if ($itemPlan == null || $idEstacion == null || $idEntidad == null) {
                throw new Exception('Hubo un error en traer los datos de registro!!');
            }

            // $arrayIdEntidades = $this->input->post('arrayIdEntidades');
            // $arrayInsert = array();
            // if (is_array($arrayIdEntidades) && count($arrayIdEntidades) > 0) {

            // foreach ($arrayIdEntidades as $row) {
            //     array_push($arrayInsert,
            //         array('idEntidad' => $row[0],
            //             'idEstacion' => $idEstacion,
            //             'itemPlan' => $itemPlan,
            //             'ruta_pdf' => null,
            //             'fecha_inicio' => null,
            //             'fecha_fin' => null,
            //             'id_usuario_reg' => $idPersonaSession,
            //             'fecha_registro' => date("Y-m-d"),
            //             'fecha_valida' => null,
            //             'flg_validado' => 0,
            //             'id_usuario_valida' => null,
            //         )
            //     );
            // }
            $arrayInsert = array();
            array_push($arrayInsert,
                array('idEntidad' => $idEntidad,
                    'idEstacion' => $idEstacion,
                    'itemPlan' => $itemPlan,
                    'ruta_pdf' => null,
                    'fecha_inicio' => null,
                    'fecha_fin' => null,
                    'id_usuario_reg' => $idPersonaSession,
                    'fecha_registro' => date("Y-m-d"),
                    'fecha_valida' => null,
                    'flg_validado' => 0,
                    'id_usuario_valida' => null,
                )
            );  
            $data = $this->M_bandeja_itemplan_estacion->registrarEntidadesItemPlanEstaLic($arrayInsert);
            if ($data['error'] == EXIT_SUCCESS) {
                $listaItemPlanDetalle = $this->M_bandeja_itemplan_estacion->getItemPLanEstacionLincenciaDet($itemPlan, $idEstacion);
                $data['tablaItemPlanDetalle'] = $listaItemPlanDetalle;
            }
            // } else {
            //     $data['msj'] = 'Debe seleccionar  como m&iacute;nimo una entidad nueva';
            // }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

}
