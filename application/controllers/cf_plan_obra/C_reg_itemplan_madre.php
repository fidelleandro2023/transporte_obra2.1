<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 *
 *
 */
class C_reg_itemplan_madre extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_plan_obra/m_reg_itemplan_madre');
        $this->load->library('lib_utils');
        $this->load->library('map_utils/coordenadas_utils');
        $this->load->helper('url');
    }

    function index() {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {
            $zonas = $this->session->userdata('zonasSession');
            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $permisos = $this->session->userdata('permisosArbol');
            $data['tablaItemMadre'] = $this->getTbItemsMadre();
            $data['cmbProyecto'] = __buildComboProyecto();
            //Mandamos el Json al view
            $data['jsonCoordenadas'] = $this->coordenadas_utils->getJsonCoordenadas();
            #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_PLAN_DE_OBRA, ID_PERMISO_HIJO_PO_REG_GRAFICOS);
            $result = $this->lib_utils->getHTMLPermisos($permisos, 241, 285, ID_MODULO_MANTENIMIENTO);
            $data['opciones'] = $result['html'];

            $this->load->view('vf_plan_obra/v_reg_itemplan_madre', $data);
        } else {
            redirect('login', 'refresh');
        }
    }

    function getPepItemplanMadre() {
        $count = 0;
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            $textMonto = $this->input->post('textMonto');
            $result = $this->m_reg_itemplan_madre->getPepItemplanMadre($this->input->post('cmbSubProyecto'));
            if (count($result) === 0) {
                throw new Exception('No encontro ninguna PEP');
            }
            foreach ($result as $row) {
                $pep = $this->m_reg_itemplan_madre->getSAPdetalle($row['pep1']);
                foreach ($pep as $key) {
                    if ($count == 0) {
                        if ($key['monto_temporal'] > $textMonto) {
                            $count++;
                            $data['MontoPepe'] = $key['monto_temporal'];
                            $data['pep'] = $key['pep1'];
                            $data['error'] = EXIT_SUCCESS;
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function getSubProyectoItemMadre() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            $data['cmbSubproyecto'] = __buildSubProyecto(4, 1, 1);
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function regItemPlanMadre() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {

            $nomMadre = $this->input->post('nomMadre');
            $idProyecto = $this->input->post('idProyecto');
            $idSubProyecto = $this->input->post('idSubProyecto');
            $inputCoordX = $this->input->post('inputCoordX');
            $inputCoordY = $this->input->post('inputCoordY');
            $selectEmpresaColab = $this->input->post('selectEmpresaColab');
            $textMonto = $this->input->post('textMonto');
            $selectPrioridad = $this->input->post('selectPrioridad');
            // -- Grilla --//
            // IdOpex
            $idpep = $this->input->post('idpep');

            $itemplanM = $this->m_utils->generarItemMadre();
            $uploaddir = 'uploads/obra_publica/' . $itemplanM . '/'; //ruta final del file
            if (!is_dir($uploaddir)) {
                mkdir($uploaddir, 0777);
            }

            $config = [
                "upload_path" => $uploaddir,
                'allowed_types' => "pdf|PDF"
            ];

            $this->load->library("upload", $config);

            if (!$this->upload->do_upload('fileuploadOP')) {
                //*** ocurrio un error
                throw new Exception($this->upload->display_errors());
            }

            $avanze = array("upload_data" => $this->upload->data());
            $fileuploadOP = $avanze['upload_data']['file_name'];


            $objReg = array(
                'itemplan_m' => $itemplanM,
                'idProyecto' => $idProyecto,
                'idSubProyecto' => $idSubProyecto,
                'fecha_registro' => $this->fechaActual(),
                'id_usuario' => $this->session->userdata('idPersonaSession'),
                'nombre' => $nomMadre,
                'coordenadaX' => $inputCoordX,
                'coordenadaY' => $inputCoordY,
                'idEmpresaColab' => $selectEmpresaColab,
                'carta_pdf' => "/uploads/obra_publica/" . $itemplanM . '/' . $fileuploadOP,
                'idPrioridad' => ($idpep) ? $selectPrioridad : 2,
                'costoEstimado' => $textMonto,
                'idEstado' => 12
            );

            $data = $this->m_reg_itemplan_madre->regItemMadre($objReg);
            if ($idpep) {
                if ($selectPrioridad === '1') {
                    _log('idpep : ' . $idpep);
                    $this->m_reg_itemplan_madre->OCregistroItemplanMadre($itemplanM, $idpep);
                    $this->m_reg_itemplan_madre->updateMontoPEP($idpep, $textMonto);
                }
            }

            if ($data['error'] == EXIT_ERROR) {
                throw new Exception('Error al Insertar planobra');
            } else {
                $itemplanData = $this->m_reg_itemplan_madre->obtenerUltimoRegistro();

                $departamento = $this->input->post('txt_departamento');
                $provincia = $this->input->post('txt_provincia');
                $distrito = $this->input->post('txt_distrito');
                $fec_recepcion = $this->input->post('fecRecepcion');
                $nomCliente = $this->input->post('inputNomCli');
                $numCarta = $this->input->post('inputNumCar');
                $ano = $this->input->post('selectAno');
                $numCartaFin = $this->input->post('inputNumCar');
                $kickOff = NULL;

                $arrayDataOP = array(
                    'itemplan' => $itemplanData,
                    'departamento' => $departamento,
                    'provincia' => $provincia,
                    'distrito' => $distrito,
                    'fecha_recepcion' => $fec_recepcion,
                    'nombre_cliente' => $nomCliente,
                    'numero_carta' => $numCarta,
                    'ano' => $ano,
                    'numero_carta_pedido' => $numCartaFin,
                    'ruta_carta_pdf' => "/uploads/obra_publica/" . $itemplanM . '/' . $fileuploadOP,
                    'usuario_envio_carta' => $this->session->userdata('idPersonaSession'),
                    'has_kickoff' => $kickOff,
                    'estado_kickoff' => (($kickOff == 1) ? 'PENDIENTE' : null)
                );
                $data = $this->m_reg_itemplan_madre->saveDetalleObraPublica($arrayDataOP);

                if ($data['error'] == EXIT_ERROR) {
                    throw new Exception($data['msj']);
                }
            }

            $data['itemplanM'] = $itemplanM;
            $data['tbItemMadre'] = $this->getTbItemsMadre();
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function getTbItemsMadre() {
        $cont = 0;
        $arrayData = $this->m_reg_itemplan_madre->getDataTablaItemMadre();

        $html = '
                <table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>NRO</th>
                            <th>NOMBRE</th>
                            <th>PROYECTO</th>
                            <th>SUBPROYECTO</th>
                            <th>ITEMPLAN MADRE</th>
                            <th>FECHA REGISTRO</th>
                        </tr>
                    </thead>

                    <tbody>';
        foreach ($arrayData as $row) {
            $cont++;
            $html .= '<tr>
                                        <td>' . $cont . '</td>
                                        <td>' . utf8_decode($row['nombre']) . '</td>
                                        <td>' . utf8_decode(mb_strtoupper($row['proyectoDesc'])) . '</td>
                                        <td>' . $row['subDesc'] . '</td>
                                        <td>' . $row['itemplan_m'] . '</td>
                                        <td>' . $row['fecha_registro'] . '</td>
                                    </tr>';
        }
        '</tbody>
                </table>';
        return $html;
    }

    function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone', 'America/Lima');
        setlocale(LC_TIME, "es_ES", "esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }

}
