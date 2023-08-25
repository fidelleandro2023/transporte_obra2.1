<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class C_movilesPep extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('mf_movilesPep/M_movilesPep');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index() {

        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {
            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $data['evento'] = $this->M_movilesPep->getSubProyecto();
            $data['tipo'] = $this->M_movilesPep->getTipo();
//            $data['tabla'] = $this->tablaPepVacia(null, null);
            $permisos = $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, 238, 289, ID_MODULO_ADMINISTRATIVO);
            $data['opciones'] = $result['html'];
            $this->load->view('vf_movilesPep/v_movilesPep', $data);
        } else {
            redirect('login', 'refresh');
        }
    }

    function tablaPepVacia() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $subProy = $this->input->post('busSubPro');
            $Pep = $this->input->post('busPep');
//            if ($subProy == '' && $Pep == '') {
//                throw new Exception('Debe de seleccionar al menos un filtro');
//            }
            $data['tabla'] = $this->consultaTablaPep($subProy, $Pep);
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function consultaTablaPep($subProy, $Pep) {
        $pep = $this->M_movilesPep->getMovilesPep($subProy, $Pep);

        $html = '
            <table id="data-table" class="table table-bordered" style="width:100%"" >
                <thead  class="thead-default">
                  <tr>
                      <th></th>
                      <th>PEP</th>
                      <th>PROYECTO</th>                           
                      <th>SUBPROYECTO</th>
                      <th>TIPO</th>  
                      <th>FECHA</th>				  					  
                  </tr>                                                                       
                </thead>
            <tbody id="tb_body">';

        foreach ($pep as $row) {
//            $accion = '<div class="text-center"><a onclick="editar_opex(' . "'" . $row->movilesPepDesc . "'" . ')"><i class="zmdi zmdi-edit zmdi-hc-2x"></i></a>'
//                    . '&nbsp;&nbsp;&nbsp;<a onclick="eliminar_opex(' . "'" . $row->movilesPepDesc . "'" . ')"><i class="zmdi zmdi-close-circle zmdi-hc-2x mdc-text-red"></i></a>'
//                    . '&nbsp;&nbsp;&nbsp;<a onclick="historialOpex(' . "'" . $row->movilesPepDesc . "'" . ')"><i class="zmdi zmdi-search zmdi-hc-2x mdc-text-red"></i></a></div>';
            $html .= '<tr>
                            <td></td>
                            <td>' . $row->movilesPepDesc . '</td>
                            <td>' . $row->proyectoDesc . '</td>
                            <td>' . $row->subProyectoDesc . '</td>
                            <td>' . $row->tipoPep . '</td>
                            <td>' . $row->fecha_registro . '</td></tr>';
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
