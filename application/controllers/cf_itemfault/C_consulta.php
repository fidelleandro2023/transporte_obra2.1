<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class C_consulta extends CI_Controller {

    //put your code here

    function __construct() {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_itemfault/M_registro', 'registro');
        $this->load->model('mf_itemfault/M_consulta', 'consulta');
        $this->load->model('mf_pqt_mantenimiento/m_pqt_central');
        $this->load->library('lib_utils');
        $this->load->model('mf_login/m_login');
        $this->load->library('map_utils/coordenadas_utils');
    }

    public function index() {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {
            $data['servicio'] = $this->registro->getServicio();
            $data['listaTiCen'] = $this->m_pqt_central->getPqtAllCentral();
            $data['evento'] = $this->registro->getEvento();
            $data['corte'] = $this->registro->getCorte();
            $data['estado'] = $this->consulta->getEstado();
            $data['gerencia'] = $this->registro->getGerencia();
            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $data['tablaConsultaItemfault'] = $this->tablaConsultaVacia();
            //Mandamos el Json al view
            $data['jsonCoordenadas'] = $this->coordenadas_utils->getJsonCoordenadas();
            $permisos = $this->session->userdata('permisosArbol');
            // permiso para registro individual modificar
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_GESTION_MANTENIMIENTO, ID_PERMISO_HIJO_CONSULTA_ITEMFAULT, ID_MODULO_GESTION_MANTENIMIENTO);
            $data['opciones'] = $result['html'];
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_itemfault/v_consulta', $data);
            } else {
                redirect('login', 'refresh');
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    function AprobarDiseno() {
        $itemfault = $this->input->post('idItemfault');
        $idEstadoItemfault = $this->input->post('idEstadoItemfault');
        $arrayDataOP = array(
            'idEstadoItemfault' => $idEstadoItemfault,
        );
        $data = $this->consulta->AprobarDiseno($itemfault, $arrayDataOP);
        echo json_encode(array_map('utf8_encode', $data));
    }

    function tablaConsultaVacia() {
        $html = '<table id="data-table" class="table table-bordered" style="width:100%">
                                                <thead class="thead-default">
                                                    <tr>
                                                        <th>ACCION</th>
                                                        <th>ITEMFAULT</th>
                                                        <th>NOMBRE DE URA</th>
                                                        <th>SERVICIO DE RED</th>
                                                        <th>ELEMENTO DE RED DE SERVICIO</th>
                                                        <th>EVENTO</th>
                                                        <th>EECC</th>
                                                        <th>FECHA DE CREACION</th>
                                                        <th>ESTADO</th>
                                                    </tr>
                                                </thead>                    
                    <tbody id="tb_body"></tbody></table>';
        return utf8_decode($html);
    }

    function tablaConsulta() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $selectServicio = $this->input->post('selectServicio');
            $selectElementoServicio = $this->input->post('selectElementoServicio');
            $inputcreacion = $this->input->post('inputcreacion');
            $inputItemfaut = $this->input->post('inputItemfaut');
            $inputNombrePlan = $this->input->post('inputNombrePlan');
            //
            $selectEstado = $this->input->post('selectEstado');
            $selectGerencia = $this->input->post('selectGerencia');
            $selectEvento = $this->input->post('selectEvento');
            $selectSubEvento = $this->input->post('selectSubEvento');
            if ($inputcreacion == null && $selectServicio == null && $selectElementoServicio == null && $inputItemfaut == null && $inputNombrePlan == null && $selectEstado == null && $selectGerencia == null && $selectEvento == null && $selectSubEvento == null) {
                throw new Exception('Debe de seleccionar al menos un filtro');
            }
            $data['tablaConsultaItemfault'] = $this->getTablaConsulta($selectServicio, $selectElementoServicio, $inputcreacion, $inputItemfaut, $inputNombrePlan, $selectEstado, $selectGerencia, $selectEvento, $selectSubEvento);
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function getTablaConsulta($selectServicio, $selectElementoServicio, $inputcreacion, $inputItemfaut, $inputNombrePlan, $selectEstado, $selectGerencia, $selectEvento, $selectSubEvento) {
        $dataConsulta = $this->consulta->getTablaConsulta($selectServicio, $selectElementoServicio, $inputcreacion, $inputItemfaut, $inputNombrePlan, $selectEstado, $selectGerencia, $selectEvento, $selectSubEvento);
        $html = '';

        if (count($dataConsulta) == 0) {
            $html .= '<table id="data-table" class="table table-bordered" style="width:100%">
                                                <thead class="thead-default">
                                                    <tr>
                                                        <th>ACCION</th>
                                                        <th>ITEMFAULT</th>
                                                        <th>NOMBRE DE URA</th>
                                                        <th>SERVICIO DE RED</th>
                                                        <th>ELEMENTO DE RED DE SERVICIO</th>
                                                        <th>EVENTO</th>
                                                        <th>EECC</th>
                                                        <th>FECHA DE CREACION</th>
                                                        <th>ESTADO</th>
                                                    </tr>
                                                </thead>                    
                    <tbody id="tb_body"></tbody></table>';
        } else {
            $html .= '<table id="data-table" class="table table-bordered" style="width:100%">
                                                <thead class="thead-default">
                                                    <tr>
                                                        <th>ACCION</th>
                                                        <th>ITEMFAULT</th>
                                                        <th>NOMBRE DE URA</th>
                                                        <th>SERVICIO DE RED</th>
                                                        <th>ELEMENTO DE RED DE SERVICIO</th>
                                                        <th>EVENTO</th>
                                                        <th>EECC</th>
                                                        <th>FECHA DE CREACION</th>
                                                        <th>ESTADO</th>
                                                    </tr>
                                                </thead>                    
                                                <tbody id="tb_body">';
            $cont = 0;
            $accion = null;
            $itemfault = null;


            foreach ($dataConsulta as $row) {


                if ($row->idEstadoPlan == 1) {
                    $accion = '';
                    $itemfault = $row->itemfault;
                } else {
                    if ($row->estado_sol_oc === 'ATENDIDO') {
                        $accion = '<div class="text-center"><a href="gestionarItemfault?item=' . $row->itemfault . '&est=' . $row->idEstadoPlan . '"  target="_blank"><i class="zmdi zmdi-hourglass-alt zmdi-hc-2x"></i></a></div>';
                        $itemfault = '<a href="' . 'detalleItemfault?item=' . $row->itemfault . '&from=1&estacion=null"        target="_blank">' . $row->itemfault . '</a>';
                    } else {
                        $accion = '<div class="text-center"><a style="color:#e9426e" onclick=mensaje()><i class="zmdi zmdi-hourglass-alt zmdi-hc-2x"></i></a></div>';
                        $itemfault = '<a>' . $row->itemfault . '</a>';
                    }
                }

                $cont++;
                $html .= '<tr>
                            <td>' . $accion . '</td>
                            <td>' . $itemfault . '</td>
                            <td>' . $row->nombre . '</td>
                            <td>' . $row->servicioDesc . '</td>
                            <td>' . $row->elementoDesc . '</td>
                            <td>' . $row->EventoDesc . '</td>
                            <td>' . $row->empresaColabDesc . '</td>
                            <td>' . $row->fecha_registro . '</td>
                            <td>' . $row->estadoPlanDesc . '</td>';
            }
            $html .= '</tbody></table>';
        }
        return utf8_decode($html);
    }

    function actualizarPropuesta() {
        $config = [
            "upload_path" => "./dist/img/itemfault",
            'allowed_types' => "pdf"
        ];

        $this->load->library("upload", $config);
        //
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $itemfault = $this->input->post('itemfault');
            $inputMontoMO = $this->input->post('inputMontoMO');
            $inputMontoMAT = $this->input->post('inputMontoMAT');
            if ($inputMontoMO == null) {
                throw new Exception('Ingrese monto de Mano de Obra');
            }
            if ($inputMontoMAT == null) {
                throw new Exception('Ingrese monto de Material');
            }
            if (!$this->upload->do_upload('archivo_pdf')) {
                //*** ocurrio un error
                throw new Exception($this->upload->display_errors());
            }

            $arrayDataOP = array(
                'montoMO' => $inputMontoMO,
                'montoMAT' => $inputMontoMAT,
                'pdf_propuesta_uno' => '',
                'pdf_propuesta_dos' => '',
//                'idEstadoItemfault' => 2,
            );
            $data = $this->consulta->actualizarPropuesta($itemfault, $arrayDataOP);
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
