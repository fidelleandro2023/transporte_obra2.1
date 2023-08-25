<?php
defined('BASEPATH') or exit('No direct script access allowed');
class C_aprobacion_cotizacion extends CI_Controller
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
            $data["extra"] = '<link href="' . base_url() . 'public/vendors/bower_components/datatables/media/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/><link rel="stylesheet" href="' . base_url() . 'public/fancy/source/jquery.fancybox.css" type="text/css" media="screen">';
            $data["pagina"] = "pendiente";
            $data["tabla"] = $this->makeHTLMTablaConsulta($this->M_cotizaciones->getAllCotizaciones(0,null));
            $this->load->view('vf_layaout_sinfix/header', $data);
            $this->load->view('vf_layaout_sinfix/cabecera');
            $this->load->view('vf_layaout_sinfix/menu');

            $this->load->view('vf_ejecucion/v_aprobacion_cotizacion');

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
                            <th>DESCRIPCI&Oacute;N</th>
                            <th>COSTO</th>
                            <th>EVIDENCIA</th>
                            <th>USU. REGI.</th>
                            <th>FEC. REGI.</th>
                            <th>ESTADO</th>
                            <th>ACCI&Oacute;N</th>
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
                            <td style="text-align:center">
                                <div class="row">
                                    <div class="col-sm-6 col-md-5">
                                        <a style="cursor:pointer; color: #9ACD32" data-idcotizacion="' . $row->idCotizacion . '" data-itemplan="' . $row->itemPlan . '" onclick="abrirModalAprobCoti(this,1)"><i class="zmdi zmdi-hc-2x zmdi-check-all"></i></a>
                                    </div>
                                    <div class="col-sm-6 col-md-5">
                                        <a style="cursor:pointer; color: red" data-idcotizacion="' . $row->idCotizacion . '" data-itemplan="' . $row->itemPlan . '" onclick="abrirModalAprobCoti(this,2)"><i class="zmdi zmdi-hc-2x zmdi-close-circle"></i></a>
                                    </div>
                            </td>
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

    public function updateCotizacion()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        try {

            $idCotizacion = $this->input->post('idCotizacion') ? $this->input->post('idCotizacion') : null;
            $itemplan = $this->input->post('itemplan') ? $this->input->post('itemplan') : null;
            $flgAprob =$this->input->post('flgAprob') ? $this->input->post('flgAprob') : null;

            $idPersonaSession = $this->session->userdata('idPersonaSession');

            if ($idCotizacion != null && $itemplan != null && $flgAprob != null) {
                $arrayUpdate = array(
                    "id_usuario_aprob" => $idPersonaSession,
                    "fecha_aprob" => date("Y-m-d H:i:s"),
                    "flg_validado" => ($flgAprob == 1 ? 1 : 2 ),
                );

                $data = $this->M_cotizaciones->updateCotizacion($idCotizacion, $arrayUpdate);
                if($data['error'] == EXIT_SUCCESS){
                    if($flgAprob == 1){
                        $data['msj'] = "Se aprob&oacute; correctamente la cotizaci&oacute;n!!";
                    }else{
                        $data['msj'] = "Se rechaz&oacute; correctamente la cotizaci&oacute;n!!";
                    }                 
                }
                $data["tablaHTML"] = $this->makeHTLMTablaConsulta($this->M_cotizaciones->getAllCotizaciones(0,null));

            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

}
