<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class C_movilesPepProyecto extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('mf_movilesPep/M_movilesPepProyecto');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index() {

        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {
            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $data['tabla'] = $this->consultaTablaPep();
            $permisos = $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, 238, 290, ID_MODULO_ADMINISTRATIVO);
            $data['opciones'] = $result['html'];
            $this->load->view('vf_movilesPep/v_movilesPep_proyecto', $data);
        } else {
            redirect('login', 'refresh');
        }
    }

    public function consultaTablaPep() {
        $datos = $this->M_movilesPepProyecto->getMovilesPep();

        $html = '
            <table id="data-table" class="table table-bordered" style="width:100%"" >
                <thead  class="thead-default">
                  <tr>
                      <th>PROYECTO</th>
                          <th>PRESUPUESTO</th>
                          <th>REAL</th>
                          <th>COMPROMETIDO</th>
                          <th>PLANRES</th>
                          <th>DISPONIBLE</th> 
                          <th style="width: 5px">PORCENTAJE</th> 				  					  
                  </tr>                                                                       
                </thead>
            <tbody id="tb_body">';

        foreach ($datos->result() as $row) {
            $color = 'black';
            $back = 'none';
            if ($row->percent <= 30) {
                $color = 'white';
                $back = 'green';
            } else if ($row->percent <= 50) {
                $color = 'white';
                $back = '#c5c528';
            } else if ($row->percent <= 100) {
                $color = 'white';
                $back = 'red';
            }
            $html .= '<tr> 
                        <td><a href="mMovilesPepDetalle?pro=' . $row->idProyecto . '&&flg_tipo=' . $row->flg_tipo . '" target="_blank">' . utf8_decode($row->proyectoDesc) . '</a></td>
                        <td>' . number_format($row->presupuesto, 2, '.', ',') . '</td>
                        <td>' . number_format($row->reall, 2, '.', ',') . '</td>
                        <td>' . number_format($row->comprometido, 2, '.', ',') . '</td>    
                        <td>' . number_format($row->planresord, 2, '.', ',') . '</td>
                        <td>' . number_format($row->disponible, 2, '.', ',') . '</td>
                        <td style="color:' . $color . ';background:' . $back . ';text-align: center;">' . $row->percent . '%</td>
                   </tr>';
        }
        $html .= '</tbody></table>';


        return utf8_decode($html);
    }

    public function saveTablePepMoviles() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            //
            $pep2 = $this->input->post('pep2');
            $selecIdSubProyecto = $this->input->post('selecIdSubProyecto');
            $selecIdPromotor = $this->input->post('selecIdPromotor');
            $selecIdTipo = $this->input->post('selecIdTipo');
            //
            if ($pep2 == null) {
                throw new Exception('Debe de ingresar una PEP');
            }
            if ($selecIdSubProyecto == null) {
                throw new Exception('Debe de seleccionar al menos un subproyecto');
            }
            if ($selecIdPromotor == null) {
                throw new Exception('Debe de seleccionar al menos un promotor');
            }
            if ($selecIdTipo == null) {
                throw new Exception('Debe de seleccionar al menos un tipo');
            }
            //
            $arrayDataOP = array(
                'movilesPepDesc' => $pep2,
                'idSubproyecto' => $selecIdSubProyecto,
                'promotorPep' => $selecIdPromotor,
                'idTipoPep' => $selecIdTipo,
                'fecha_registro' => $this->fechaActual()
            );
            $data = $this->M_movilesPep->saveTablePepMoviles($arrayDataOP);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone', 'America/Lima');
        setlocale(LC_TIME, "es_ES", "esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }

}
