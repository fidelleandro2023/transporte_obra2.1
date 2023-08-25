<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class C_bandeja_consulta extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_itemplan_madre/m_bandeja_consulta');
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
            $data['tablaItemMadre'] = $this->getTbItemsMadre(null);
            $data['cmbProyecto'] = __buildComboProyecto();
            //Mandamos el Json al view
            $data['jsonCoordenadas'] = $this->coordenadas_utils->getJsonCoordenadas();
            #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_PLAN_DE_OBRA, ID_PERMISO_HIJO_PO_REG_GRAFICOS);
            $result = $this->lib_utils->getHTMLPermisos($permisos, 298, 292, 2);
            $data['opciones'] = $result['html'];

            $this->load->view('vf_itemaplan_madre/v_bandeja_consulta', $data);
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
            $result = $this->m_bandeja_consulta->getPepItemplanMadre($this->input->post('cmbSubProyecto'));
            if (count($result) === 0) {
                throw new Exception('No encontro ninguna PEP');
            }
            foreach ($result as $row) {
                $pep = $this->m_bandeja_consulta->getSAPdetalle($row['pep1']);
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

    function consultaTablaItemplanMadre() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $ItemplanmMadre = $this->input->post('itemplanMadre');

            $data['tablaItemMadre'] = $this->getTbItemsMadre(trim($ItemplanmMadre));
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function getTbItemsMadre($ItemplanmMadre) {

        $cont = 0;
        $arrayData = $this->m_bandeja_consulta->getDataTablaItemMadre($ItemplanmMadre);
//<td width="10%"><div><a><i title="VALIDAR DISE&Ntilde;O" style="color:#A4A4A4" class="zmdi zmdi-hc-2x zmdi-check-circle"></i></a>&nbsp;&nbsp;<a><i title="VALIDAR OPERACIONES" style="color:#A4A4A4" class="zmdi zmdi-hc-2x zmdi-check-circle"></i></a>&nbsp;&nbsp;<a onclick="modalItemplanHijos(' . "'" . $row['itemplan_m'] . "'" . ')"><i title="VER ITEMPLAN HIJOS" style="color:#A4A4A4" class="zmdi zmdi-hc-2x zmdi-eye"></i></a></div></td>

        $html = '<table id="data-table" class="table table-bordered" style="width:100%">
                    <thead class="thead-default">
                        <tr>
                            <th style="width:50px"></th>
                            <th>ITEMPLAN MADRE</th>
                            <th>ITEMPLAN HIJOS</th>
                            <th>NOMBRE</th>
                            <th  width="40%">PEP</th>
                            <th>PROYECTO</th>
                            <th>SUB PROYECTO</th>
                            <th>PRIORIDAD</th>
                            <th>MONTO</th>
                            <th>SOLICITUD</th>
                            <th>ORDEN DE COMPRA</th>
                            <th>EECC</th>
                            <th>ENCARGADO</th>
                            <th>NUMERO DE CARTA</th>
                            <th>FECHA DE RECEPCION</th>
                            <th>ESTADO</th>
                        </tr>
                    </thead>

                    <tbody>';
        foreach ($arrayData as $row) {
            $cont++;
            $prioridad = '';
            $descarga = '';
            $orden_compra = '';
            $gestion = '';

            if ($row['idEstadoPlan'] == 7) {
                $gestion = '<a href="gestionObraPublica?item=' . $row['itemplan_m'] . '" target="_blank"><i title="Gestion" style="color:#A4A4A4" class="zmdi zmdi-hc-2x zmdi-city-alt"></i></a>';
            } else {
                $gestion = '';
            }

            if ($row['orden_compra'] == '') {
                $orden_compra = 'SOLICITUD DE OC EN PROCESO';
            } else {
                $orden_compra = $row['orden_compra'];
            }

            if ($row['carta_pdf'] == '') {
                $descarga = '<a onclick="alert(' . "'" . 'SIN PDF PARA DESCARGAR' . "'" . ')" ><i title="CARTA" style="color:#A4A4A4" class="zmdi zmdi-hc-2x zmdi-download"></i></a>&nbsp;&nbsp;';
            } else {
                $descarga = '<a download href="' . base_url() . $row['carta_pdf'] . '"><i title="CARTA" style="color:#A4A4A4" class="zmdi zmdi-hc-2x zmdi-download"></i></a>&nbsp;&nbsp;';
            }
            if ($row['idPrioridad'] == '1') {
                $prioridad = '<a onclick="modalConPrioridad(' . "'" . $row['itemplan_m'] . "'" . ')"><i title="EDITAR CON PRIORIDAD" style="color:#A4A4A4" class="zmdi zmdi-hc-2x zmdi-edit"></i></a>&nbsp;&nbsp;';
            } else {
                $orden_compra = '';
                $prioridad = '<a onclick="modalSinPrioridad(' . "'" . $row['itemplan_m'] . "'" . ')"><i title="EDITAR SIN PRIORIDAD" style="color:#A4A4A4" class="zmdi zmdi-hc-2x zmdi-edit"></i></a>&nbsp;&nbsp;';
            }

            $html .= '<tr>
                                        <td width="auto"><div>' . $descarga . '<a onclick="modalItemplanHijos(' . "'" . $row['itemplan_m'] . "'" . ')"><i title="VER ITEMPLAN HIJOS" style="color:#A4A4A4" class="zmdi zmdi-hc-2x zmdi-eye"></i></a></div>' . $gestion . '</td>
                                        <td>' . $row['itemplan_m'] . '</td>  
                                        <td>' . $this->m_bandeja_consulta->cantidadItemplanHijos($row['itemplan_m']) . '</td>  
                                        <td>' . utf8_decode($row['nombre']) . '</td>
                                        <td>' . utf8_decode($row['pep1']) . '</td>
                                        <td>' . utf8_decode(mb_strtoupper($row['proyectoDesc'])) . '</td>
                                        <td>' . $row['subDesc'] . '</td>
                                        <td>' . $row['prioridad'] . '</td>
                                        <td>' . $row['costoEstimado'] . '</td>
                                        <td>' . $row['codigo_solicitud'] . '</td>
                                        <td style="color:red">' . $orden_compra . '</td>
                                        <td>' . $row['empresaColabDesc'] . '</td>
                                        <td>' . $row['nombre_cliente'] . '</td>
                                        <td>' . $row['numero_carta'] . '</td>
                                        <td>' . $row['fecha_registro'] . '</td>
                                        <td>' . $row['estadoPlanDesc'] . '</td>
                                    </tr>';
        }
        $html .= '</tbody></table>';
        return utf8_decode($html);
    }

    function gestionOP() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone', 'America/Lima');
        setlocale(LC_TIME, "es_ES", "esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }

    function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone', 'America/Lima');
        setlocale(LC_TIME, "es_ES", "esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }

    ///-------- Hijos ------///////


    function hijosItemMadre() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $id = $this->input->post('itemplanMadre');
            $data['tablaItemHijos'] = $this->hijosItemMadreDetalle($id);
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function hijosItemMadreDetalle($id) {
        $dataConsulta = $this->m_bandeja_consulta->hijosItemMadreDetalle($id);
        $html = '';
        $totalMA = 0;
        $totalMo = 0;
        if (count($dataConsulta) == 0) {
            $html .= '<table id="data-table2" class="table table-bordered" style="width:100%">
                                                <thead class="thead-default">
                                                    <tr>
                                                        <th></th>
                                                        <th>ITEMPLAN</th>
                                                        <th>PROYECTO</th>
                                                        <th>SUBPROYECTO</th>
                                                        <th>EECC</th>
                                                        <th>ESTADO</th>
                                                        <th>COSTO MATERIALES</th>
                                                        <th>COSTO MANO DE OBRA</th>
                                                    </tr>
                                                </thead>                    
                    <tbody id="tb_body"></tbody></table>';
        } else {
            $html .= '<table id="data-table2" class="table table-bordered" style="width:100%">
                                                <thead class="thead-default">
                                                    <tr>
                                                        <th></th>
                                                        <th>ITEMPLAN</th>
                                                        <th>PROYECTO</th>
                                                        <th>SUBPROYECTO</th>
                                                        <th>EECC</th>
                                                        <th>ESTADO</th>
                                                        <th>COSTO MATERIALES</th>
                                                        <th>COSTO MANO DE OBRA</th>
                                                    </tr>
                                                </thead>                    
                                                <tbody id="tb_body">';

            foreach ($dataConsulta as $row) {
                $totalMA = $totalMA + $this->montoToltalMA($row->itemPlan);
                $totalMo = $totalMo + $this->montoToltalMO($row->itemPlan);
                $html .= '<tr>
                            <td width="5%"><div><a href="getAnalisisEconomico?itemplan=' . $row->itemPlan . '" target="_blank"><i title="DETALLE" style="color:#A4A4A4" class="zmdi zmdi-hc-2x zmdi-money"></i></a></div></td>
                            <td>' . $row->itemPlan . '</td>
                            <td>' . $row->proyectoDesc . '</td>
                            <td>' . $row->subProyectoDesc . '</td>
                            <td>' . $row->empresaColabDesc . '</td>
                            <td>' . $row->estadoPlanDesc . '</td>
                            <td>' . $this->montoToltalMA($row->itemPlan) . '</td>
                            <td>' . $this->montoToltalMO($row->itemPlan) . '</td></tr>';
            }
            $html .= '</tbody><tfoot style="    background: #004061;color: white;" class="thead-default">
    <tr>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td>TOTAL</td>
      <td><b>' . $totalMA . '</b></td>
      <td><b>' . $totalMo . '</b></td>
    </tr>
  </tfoot></table>';
        }
        return utf8_decode($html);
    }

    function montoToltalMA($id) {
        $montoToltal = $this->m_bandeja_consulta->montoToltal($id);
        $total = 0;
        foreach ($montoToltal as $row) {
            $total = $total + $row->total_mat;
        }
        return $total;
    }

    function montoToltalMO($id) {
        $montoToltal = $this->m_bandeja_consulta->montoToltal($id);
        $total = 0;
        foreach ($montoToltal as $row) {
            $total = $total + $row->total_mo;
        }
        return $total;
    }

    function updateConPrioridad() {

        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            $itemplanM = $this->input->post('itemplanMadre');
            $textNombreMadre = $this->input->post('textNombreMadre');
            $fecRecepcion = $this->input->post('fecRecepcion');
            $inputNomCli = $this->input->post('inputNomCli');
            $inputNumCar = $this->input->post('inputNumCar');

            $uploaddir = 'uploads/obra_publica/' . $itemplanM . '/'; //ruta final del file
            if (!is_dir($uploaddir)) {
                mkdir($uploaddir, 0777);
            }

            $config = [
                "upload_path" => $uploaddir,
                'allowed_types' => "pdf|PDF"
            ];

            if ($this->input->post('carta_pdf') == null || $this->input->post('carta_pdf') == '') {
                $this->load->library("upload", $config);

                if (!$this->upload->do_upload('fileuploadOP')) {
                    //*** ocurrio un error
                    throw new Exception($this->upload->display_errors());
                }

                $avanze = array("upload_data" => $this->upload->data());
                $fileuploadOP = $avanze['upload_data']['file_name'];
                $ruta = "/uploads/obra_publica/" . $itemplanM . '/' . $fileuploadOP;
            } else {
                $ruta = $this->input->post('carta_pdf');
            }



            $objReg = array(
                'carta_pdf' => $ruta,
                'nombre' => $textNombreMadre,
                'fecha_upd' => $this->fechaActual(),
                'usu_upd' => $this->session->userdata('idPersonaSession'),
                'descripcion' => 'ACTUALIZACIÓN'
            );

            $objRegDetalle = array(
                'itemplan' => $itemplanM,
                'ruta_carta_pdf' => $ruta,
                'fecha_recepcion' => $fecRecepcion,
                'nombre_cliente' => $inputNomCli,
                'numero_carta' => $inputNumCar
            );

            $countItemplanMadre = $this->m_bandeja_consulta->countItemplanMadre($itemplanM);

            if ($countItemplanMadre == 0) {
                $data = $this->m_bandeja_consulta->saveDetalleObraPublica($objRegDetalle);
            } else {
                $data = $this->m_bandeja_consulta->updateConPrioridadDetalle($objRegDetalle, $itemplanM);
            }

            if ($data['error'] == EXIT_ERROR) {
                throw new Exception('Error al Actualziar Itemplan Madre');
            } else {
                $this->m_bandeja_consulta->updateConPrioridad($objReg, $itemplanM);
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function getEditItemplanMadre() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $id = $this->input->post('itemplanMadre');
            $data['datos'] = $this->m_bandeja_consulta->getEditItemplanMadre($id);
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

    function updateSinPrioridad() {

        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            $itemplanM = $this->input->post('itemplanMadre');
            $textNombreMadre = $this->input->post('textNombreMadre');
            $fecRecepcion = $this->input->post('fecRecepcion');
            $inputNomCli = $this->input->post('inputNomCli');
            $inputNumCar = $this->input->post('inputNumCar');
            //
            $textMonto = $this->input->post('textMonto');
            $selectPrioridad = $this->input->post('selectPrioridad');
            // IdOpex
            $idpep = $this->input->post('idpep');


            $uploaddir = 'uploads/obra_publica/' . $itemplanM . '/'; //ruta final del file
            if (!is_dir($uploaddir)) {
                mkdir($uploaddir, 0777);
            }

            $config = [
                "upload_path" => $uploaddir,
                'allowed_types' => "pdf|PDF"
            ];

            if ($this->input->post('carta_pdf') == null || $this->input->post('carta_pdf') == '') {
                $this->load->library("upload", $config);

                if (!$this->upload->do_upload('fileuploadOP')) {
                    //*** ocurrio un error
                    throw new Exception($this->upload->display_errors());
                }

                $avanze = array("upload_data" => $this->upload->data());
                $fileuploadOP = $avanze['upload_data']['file_name'];
                $ruta = "/uploads/obra_publica/" . $itemplanM . '/' . $fileuploadOP;
            } else {
                $ruta = $this->input->post('carta_pdf');
            }



            $objReg = array(
                'carta_pdf' => $ruta,
                'nombre' => $textNombreMadre,
                'costoEstimado' => $textMonto,
                'idPrioridad' => $selectPrioridad,
                'fecha_upd' => $this->fechaActual(),
                'usu_upd' => $this->session->userdata('idPersonaSession'),
                'descripcion' => 'ACTUALIZACIÓN'
            );

            $objRegDetalle = array(
                'ruta_carta_pdf' => $ruta,
                'fecha_recepcion' => $fecRecepcion,
                'nombre_cliente' => $inputNomCli,
                'numero_carta' => $inputNumCar
            );

            $data = $this->m_bandeja_consulta->updateConPrioridadDetalle($objRegDetalle, $itemplanM);
            if ($data['error'] == EXIT_ERROR) {
                throw new Exception('Error al Actualziar Itemaplan Madre');
            } else {
                $this->m_bandeja_consulta->updateConPrioridad($objReg, $itemplanM);
                if ($idpep) {
                    if ($selectPrioridad === '1') {
                        _log('idpep : ' . $idpep);
                        $this->m_bandeja_consulta->OCregistroItemplanMadre($itemplanM, $idpep);
                    }
                }
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

}
