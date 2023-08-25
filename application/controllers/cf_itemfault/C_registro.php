<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class C_registro extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_itemfault/M_registro', 'registro');
        $this->load->model('mf_pqt_mantenimiento/m_pqt_central');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->library('map_utils/coordenadas_utils');
    }

    public function index() {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {
            $data['servicio'] = $this->registro->getServicio();
            $data['listaTiCen'] = $this->m_pqt_central->getPqtAllCentral();
            $data['evento'] = $this->registro->getEvento();
            $data['monto_mo'] = $this->registro->getOptionMonto();
            $data['corte'] = $this->registro->getCorte();
            $data['gerencia'] = $this->registro->getGerencia();
            $data['listaeelec'] = $this->registro->getAllEELEC();
            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            //Mandamos el Json al view
            $data['jsonCoordenadas'] = $this->coordenadas_utils->getJsonCoordenadas();
            // Marker
            $info = $this->getMarkersCV();
            $data['marcadores'] = $info['markers'];
            $data['info_markers'] = $info['info_markers'];
            //
            $permisos = $this->session->userdata('permisosArbol');
            // permiso para registro individual modificar
//            $this->load->view('vf_itemfault/v_registro', $data);
            $result = $this->lib_utils->getHTMLPermisos($permisos, 260, 261, 6);
            $data['opciones'] = $result['html'];
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_itemfault/v_registro', $data);
            } else {
                redirect('login', 'refresh');
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function getServicoElemento() {
        $id = $this->input->post('idServicio');
        $niveles = $this->registro->getServicoElemento($id);
        $resp = '<option value="">&nbsp;</option>';
        foreach ($niveles as $row) {
            $resp .= '<option value="' . $row ['idServicioElemento'] . '">' . $row ['elementoDesc'] . '</option>';
        }
        echo json_encode($resp);
    }

    public function getSubEvento() {
        $id = $this->input->post('idEvento');
        $niveles = $this->registro->getSubEvento($id);
        $resp = '<option value="">&nbsp;</option>';
        foreach ($niveles as $row) {
            $resp .= '<option value="' . $row ['idSubEvento'] . '">' . $row ['subEventoDesc'] . '</option>';
        }
        echo json_encode($resp);
    }

    public function codigoItemfault($selectServicio, $selectElementoServicio) {
        (string) $id = $this->registro->countItemfault();
        return '20-' . 'ITF' . $selectServicio . $selectElementoServicio . '000' . $id;
    }

    public function saveItemfault() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $selectServicio = $this->input->post('selectServicio');
            $selectElementoServicio = $this->input->post('selectElementoServicio');
            $identificacion = $this->input->post('identificacion');
            $selectGerencia = $this->input->post('selectGerencia');
            $inputNombrePlan = $this->input->post('inputNombrePlan');
            $selectEvento = $this->input->post('selectEvento');
            $selectCorte = $this->input->post('selectCorte');
            $selectSubEvento = $this->input->post('selectSubEvento');
            $selectEmpresaColab = $this->input->post('selectEmpresaColab');
            $remedy = $this->input->post('remedy');
            $inputFechaAveria = $this->input->post('inputFechaAveria');
            $inputHoraAveria = $this->input->post('inputHoraAveria');
            $inputUraInicial = $this->input->post('inputUraInicial');
            $inputUraFinal = $this->input->post('inputUraFinal');
            $textObservacion = $this->input->post('textObservacion');
            $inputCodigoInicial = $this->input->post('inputCodigoInicial');
            $inputCodigoFinal = $this->input->post('inputCodigoFinal');
            $inputBandejaInicial = $this->input->post('inputBandejaInicial');
            $inputBandejaFinal = $this->input->post('inputBandejaFinal');
            $inputFibraInicial = $this->input->post('inputFibraInicial');
            $inputFibraFinal = $this->input->post('inputFibraFinal');
            $inputPotenciaInicial = $this->input->post('inputPotenciaInicial');
            $inputPotenciaFinal = $this->input->post('inputPotenciaFinal');
            $selectZonal = $this->input->post('selectZonal');
            $inputCoordX = $this->input->post('inputCoordX');
            $inputCoordY = $this->input->post('inputCoordY');
            $itemplan = $this->input->post('itemplan');
            $idCentral = $this->input->post('selectCentral');
            //$inputImagenes = $this->input->post('inputImagenes');
            $inputMontoMO = $this->input->post('inputMontoMO');
            $inputMontoMAT = $this->input->post('inputMontoMAT');
            $inputPrecioU = $this->input->post('inputPrecioU');
            $inputCantidad = $this->input->post('inputCantidad');

            $config = [
                "upload_path" => "./dist/img/itemfault",
                'allowed_types' => "png|jpg|PNG|JPG"
            ];

            $this->load->library("upload", $config);

            $arrayDataOP = array(
                'itemfault' => $this->codigoItemfault($selectServicio, $selectElementoServicio),
                'itemplan' => $itemplan,
                'idServicio' => $selectServicio,
                'idServicioElemento' => $selectElementoServicio,
                'identificacion' => $identificacion,
                'idGerencia' => $selectGerencia,
                'nombre' => $inputNombrePlan,
                'idEvento' => $selectEvento,
                'idCorteServicio' => $selectCorte,
                'idSubEvento' => $selectSubEvento,
                'idEmpresaColab' => $selectEmpresaColab,
                'remedy' => $remedy,
                'FechaAveria' => $inputFechaAveria,
                'HoraAveria' => $inputHoraAveria,
                'UraInicial' => $inputUraInicial,
                'UraFinal' => $inputUraFinal,
                'Observacion' => $textObservacion,
                'CodigoInicial' => $inputCodigoInicial,
                'CodigoFinal' => $inputCodigoFinal,
                'BandejaInicial' => $inputBandejaInicial,
                'BandejaFinal' => $inputBandejaFinal,
                'FibraInicial' => $inputFibraInicial,
                'FibraFinal' => $inputFibraFinal,
                'PotenciaInicial' => $inputPotenciaInicial,
                'PotenciaFinal' => $inputPotenciaFinal,
                'idZonal' => $selectZonal,
                'CoordX' => $inputCoordX,
                'CoordY' => $inputCoordY,
                'precioPq' => $inputPrecioU,
                'MontoPq' => $inputCantidad,
                'montoMO' => $inputMontoMO,
                'montoMAT' => $inputMontoMAT,
                'pdf_propuesta_uno' => '',
                'pdf_propuesta_dos' => '',
                'fecha_registro' => $this->fechaActual(),
                'idEstadoItemfault' => 8,
                'idSituacion' => 1,
                'idCentral' => $idCentral,
                "idUsuario" => $this->session->userdata('idPersonaSession')
            );

            $idOpex = $this->registro->idOpex($selectEvento, $this->fechaActual());

            if ($idOpex) {
                $data = $this->registro->saveItemfault($arrayDataOP);
                if ($data['error'] == EXIT_ERROR) {
                    throw new Exception('Error al Insertar Itemfault');
                } else {
                    $itemfaultData = $this->registro->obtenerUltimoRegistro();
                    $result = $this->registro->funcionOrden($itemfaultData, $idOpex, $this->session->userdata('idPersonaSession'));
                    log_message('error', $itemfaultData);
                    log_message('error', $idOpex);
                    log_message('error', $this->session->userdata('idPersonaSession'));
                    $data['itemfaultnuevo'] = $itemfaultData;
                }
            } else {
                throw new Exception('Error al encontrar cuenta Opex al Itemfault');
            }
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

    public function getMarkersCV() {
        //$data = array();
        $markers = array();
        $infoMarkers = array();
        $informacion = $this->registro->getItemplan();
        foreach ($informacion->result() as $row) {
            $temp = array($row->nombre_proyecto, $row->coordenada_y, $row->coordenada_x, $row->idEstadoPlan);
            array_push($markers, $temp);

            $temp2 = array('<table style="text-align: left;">
                                <tbody>
                                    <tr>
                                        <td><strong>Itemplan:</strong></td>
                                        <td>' . $row->itemplan . '</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Estado Plan:</strong></td>
                                        <td>' . $row->estadoPlanDesc . '</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Sub Proyecto:</strong></td>
                                        <td>' . $row->subProyectoDesc . '</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Nombre Proyecto:</strong></td>
                                        <td>' . $row->nombre_proyecto . '</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Coordenada X:</strong></td>
                                        <td>' . $row->coordenada_x . '</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Coordenada Y:</strong></td>
                                        <td> ' . $row->coordenada_y . '</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Contrata:</strong></td>
                                        <td> ' . $row->empresaColabDesc . '</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><button class="btn btn-success waves-effect" onclick="vincularItemplan(' . "'" . $row->itemplan . "'" . ')">VINCULAR</button></td>
                                    </tr>
                                </tbody>
                            </table>');

            array_push($infoMarkers, $temp2);
        }
        $data['markers'] = $markers;
        $data['info_markers'] = $infoMarkers;
        return $data;
    }

}
