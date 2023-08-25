<?php
defined('BASEPATH') or exit('No direct script access allowed');
class C_consulta_cotizaciones extends CI_Controller
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
        $idPersonaSession = $this->session->userdata('idPersonaSession');
        $descPerfilSesion = $this->session->userdata('descPerfilSession');

        if ($descPerfilSesion == 'ADMINISTRADOR') {
            $idPersonaSession = null;
        }

        if ($logedUser != null) {
            // $data["extra"] = '<link href="' . base_url() . 'public/vendors/bower_components/datatables/media/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>';
            $data["extra"] = '<link href="' . base_url() . 'public/vendors/bower_components/datatables/media/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/><link rel="stylesheet" href="' . base_url() . 'public/fancy/source/jquery.fancybox.css" type="text/css" media="screen">';
            $data["pagina"] = "pendiente";
            $data["tabla"] = $this->makeHTLMTablaConsulta($this->M_cotizaciones->getAllCotizaciones(null, $idPersonaSession));
            $this->load->view('vf_layaout_sinfix/header', $data);
            $this->load->view('vf_layaout_sinfix/cabecera');
            $this->load->view('vf_layaout_sinfix/menu');

            $this->load->view('vf_ejecucion/v_consulta_cotizaciones');

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
                            <th>USU. RESPON.</th>
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
                            <td>' . $row->usu_responsable . '</td>
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

}
