<?php
defined('BASEPATH') or exit('No direct script access allowed');
class C_cotizaciones extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_ejecucion/M_cotizaciones');
        $this->load->helper('url');
    }

    public function index()
    {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {
            // $data["extra"] = '<link href="' . base_url() . 'public/vendors/bower_components/datatables/media/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>';
            $data["extra"]='<link href="'.base_url().'public/vendors/bower_components/datatables/media/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/><link rel="stylesheet" href="'.base_url().'public/fancy/source/jquery.fancybox.css" type="text/css" media="screen">';
            $data["pagina"] = "pendiente";
            $data["tabla"] = $this->makeHTLMTablaConsulta($this->M_cotizaciones->getAllCotizaciones(null,null));
            $this->load->view('vf_layaout_sinfix/header', $data);
            $this->load->view('vf_layaout_sinfix/cabecera');
            $this->load->view('vf_layaout_sinfix/menu');

            $this->load->view('vf_ejecucion/v_cotizaciones');

            // $this->load->view('vf_layaout_sinfix/footer');
            // $this->load->view('recursos_sinfix/js');
            // $this->load->view('recursos_sinfix/datatable', $data);

        } else {
            redirect('login', 'refresh');
        }

    }
    
    public function makeHTLMTablaConsulta($listaCotizaciones)
    {

        $html = '
                <table id="simpletable" class="table table-hover display  pb-30 table-striped table-bordered nowrap">
                    <thead>
                        <tr class="table-primary">
                            <th># COTIZACION</th>
                            <th>ITEMPLAN</th>
                            <th>DESCRIPCION</th>
                            <th>COSTO</th>
                            <th>EVIDENCIA</th>
                            <th>USU. REGI.</th>
                            <th>FEC. REGI.</th>
                            <th>ESTADO</th>
                        </tr>
                    </thead>

                    <tbody>';
        if ($listaCotizaciones != '') {
            foreach ($listaCotizaciones as $row) {

                $html .= '
                        <tr>
                            <td>' . $row->idCotizacion . '</td>
                            <td>' . $row->itemPlan . '</td>
                            <td>' . $row->desc_cotizacion . '</td>
                            <td>' . $row->costo . '</td>
                            <td style="text-align:center">
                                <a style="cursor:pointer; color: #9ACD32" data-rutapdf="' . $row->ruta_pdf . '" onclick="verEviLiqui(this)"><i class="zmdi zmdi-hc-2x zmdi-eye"></i></a>
                            </td>
                            <td>' . $row->usu_regi . '</td>
                            <td>' . $row->fecha_registro . '</td>
                            <td>' . $row->flg_estado . '</td>
                        </tr>';
            }
            $html .= '</tbody>
                </table>';

        } else {
            $html .= '</tbody>
                </table>';
        }

        return utf8_decode($html);
    }

    public function searchItemPlan()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $itemplan = $this->input->post('itemplan') ? $this->input->post('itemplan') : null;
            if ($itemplan != null) {
                $data = $this->makeHTLMTablaItemPLanDetalle($this->M_cotizaciones->getItemPlanById($itemplan));
                $data['tablaDetItemPlan'] = $data['tablaHTML'];
                $data['itemplan'] = $data['idItemPlan'];
                $data['error'] = EXIT_SUCCESS;
            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function makeHTLMTablaItemPLanDetalle($listaItemPlan)
    {
        $html = '<table style="font-size: 10px;" id="tabla_detalle" class="table table-bordered">
                    <thead class="thead-default">
                        <tr role="row">
                            <th style="text-align:center">ITEMPLAN</th>
                            <th style="text-align:center">PROYECTO</th>
                            <th style="text-align:center">EECC</th>
                            <th style="text-align:center">SUBPROYECTO</th>
                            <th style="text-align:center">INDICADOR</th>
                        </tr>
                    </thead>

                <tbody>';

        $itemplan = null;

        foreach ($listaItemPlan as $row) {

            $itemplan = $row->itemPlan;

            $html .= '<tr>
                        <td>' . $row->itemPlan . '</td>
                        <td>' . $row->nombreProyecto . '</td>
                        <td>' . $row->empresaColabDesc . '</td>
                        <td>' . $row->subProyectoDesc . '</td>
                        <td>' . $row->indicador . '</td>
                      </tr>';
        }
        $html .= '</tbody>
                  </table>';

        $data['idItemPlan'] = $itemplan;
        $data['tablaHTML'] = utf8_decode($html);
        return $data;
    }

    public function registrarCotizacion()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        try {
            $itemplan = $this->input->post('itemplan') ? $this->input->post('itemplan') : null;
            $descripcion = $this->input->post('descripcion');
            $costo = $this->input->post('costo');
            $idPersonaSession = $this->session->userdata('idPersonaSession');

            $this->M_cotizaciones->deleteLogTempCotizacion();

            $idCotizacion = $this->M_cotizaciones->generarCodigoCotizacion();
            if (isset($idCotizacion) && $itemplan != null) {
                $arrayInsert = array(
                    "idCotizacion" => $idCotizacion,
                    "itemPlan" => $itemplan,
                    "desc_cotizacion" => $descripcion,
                    "costo" => $costo,
                    "id_usuario_reg" => $idPersonaSession,
                    "fecha_registro" => date("Y-m-d"),
                    "flg_validado" => 0,
                );
                $data = $this->M_cotizaciones->insertarCotizacion($arrayInsert);

                $idCotizacionTemp = $this->M_cotizaciones->obtenerUltimoRegistroCotizacion();

                // if (isset($idCotizacionTemp)) {
                //     unset($_SESSION["idCotizacionTemp"]);
                //     $this->session->set_userdata('idCotizacionTemp', $idCotizacionTemp);
                //     $data['error'] = EXIT_SUCCESS;
                // }
            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function uploadEviCotizacion()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $itemplan = $this->input->post('itemplan') ? $this->input->post('itemplan') : null;
            $descripcion = $this->input->post('descripcion') ? $this->input->post('descripcion') : null;
            $costo = $this->input->post('costo') ? $this->input->post('costo') : null;
            $idResponsable = $this->input->post('idResponsable') ? $this->input->post('idResponsable') : null;
            $idPersonaSession = $this->session->userdata('idPersonaSession');


            if($itemplan == null){
                throw new Exception('Debe buscar un itemplan!!');
            }
            if($descripcion == null || $descripcion == ''){
                throw new Exception('Debe ingresar una descripcion!!');
            }
            if($costo == null || $costo == ''){
                throw new Exception('Debe ingresar un costo!!');
            }

            if($idResponsable == null || $idResponsable == ''){
                throw new Exception('Debe seleccionar un responsable!!');
            }

            $file = $_FILES["file"]["name"];
            $filetype = $_FILES["file"]["type"];
            $filesize = $_FILES["file"]["size"];
            $archivo = $_FILES["file"]["tmp_name"];

            $flgExisteCoti = $this->M_cotizaciones->getCountCotiByItemplan($itemplan);

            if($flgExisteCoti > 0){
                throw new Exception('Ya existe un itemplan asignado a esta cotizacion, debe buscar otro!!');
            }

            $this->M_cotizaciones->deleteLogTempCotizacion();

            $idCotizacion = $this->M_cotizaciones->generarCodigoCotizacion();
            if (isset($idCotizacion) && $itemplan != null) {
                $arrayInsert = array(
                    "idCotizacion" => $idCotizacion,
                    "itemPlan" => $itemplan,
                    "desc_cotizacion" => $descripcion,
                    "costo" => $costo,
                    "id_usuario_reg" => $idPersonaSession,
                    "fecha_registro" => date("Y-m-d"),
                    "flg_validado" => 0,
                    "id_responsable" => $idResponsable
                );
                $data = $this->M_cotizaciones->insertarCotizacion($arrayInsert);
            }

            $idCotizacionTemp = $this->M_cotizaciones->obtenerUltimoRegistroCotizacion();



            if (!isset($archivo) || $filesize == 0) {
                throw new Exception("Este archivo est&aacute; da&ntilde;ado, ingrese otro porfavor!!");
            }

            $ubicacionRaiz = 'uploads/cotizaciones';
            if (!is_dir($ubicacionRaiz)) {
                mkdir('uploads/cotizaciones', 0777);
            }
            $subCarpeta = 'uploads/cotizaciones/itemplan';
            if (!is_dir($subCarpeta)) {
                mkdir('uploads/cotizaciones/itemplan', 0777);
            }
            $ubicCotizacion = 'uploads/cotizaciones/itemplan/cotizacion' . $idCotizacionTemp;
            if (!is_dir($ubicCotizacion)) {
                mkdir('uploads/cotizaciones/itemplan/cotizacion' . $idCotizacionTemp, 0777);
            } else { //si existe borramos el archivo existente
                $filesExist = scandir($ubicCotizacion); //trae arreglo de archivos existentes en esa carpeta
                $ficherosEliminados = 0;
                foreach ($filesExist as $f) {
                    if (is_file($ubicCotizacion . "/" . $f)) {
                        if (unlink($ubicCotizacion . "/" . $f)) {
                            $ficherosEliminados++;
                        }
                    }
                }
            }

            $file2 = utf8_decode($file);

            if (utf8_decode($file) && move_uploaded_file($archivo, $ubicCotizacion . "/" . $file2)) {

                rename($ubicCotizacion . "/" . $file2, $ubicCotizacion . "/eviCoti_" . $idCotizacionTemp.".pdf");

                $arrayUpdate = array('ruta_pdf' => $ubicCotizacion . "/eviCoti_" . $idCotizacionTemp.".pdf");
                $data = $this->M_cotizaciones->updateCotizacion($idCotizacionTemp, $arrayUpdate);

                $data["tablaHTML"] = $this->makeHTLMTablaConsulta($this->M_cotizaciones->getAllCotizaciones(null, null));

            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
}
