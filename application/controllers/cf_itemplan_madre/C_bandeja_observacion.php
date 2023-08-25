<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class C_bandeja_observacion extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_itemplan_madre/m_bandeja_observacion');
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
            $result = $this->lib_utils->getHTMLPermisos($permisos, 298, 300, 2);
            $data['opciones'] = $result['html'];

            $this->load->view('vf_itemaplan_madre/v_bandeja_observacion', $data);
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
            $result = $this->m_bandeja_observacion->getPepItemplanMadre($this->input->post('cmbSubProyecto'));
            if (count($result) === 0) {
                throw new Exception('No encontro ninguna PEP');
            }
            foreach ($result as $row) {
                $pep = $this->m_bandeja_observacion->getSAPdetalle($row['pep1']);
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
        $arrayData = $this->m_bandeja_observacion->getDataTablaItemMadre($ItemplanmMadre);
//<td width="10%"><div><a><i title="VALIDAR DISE&Ntilde;O" style="color:#A4A4A4" class="zmdi zmdi-hc-2x zmdi-check-circle"></i></a>&nbsp;&nbsp;<a><i title="VALIDAR OPERACIONES" style="color:#A4A4A4" class="zmdi zmdi-hc-2x zmdi-check-circle"></i></a>&nbsp;&nbsp;<a onclick="modalItemplanHijos(' . "'" . $row['itemplan_m'] . "'" . ')"><i title="VER ITEMPLAN HIJOS" style="color:#A4A4A4" class="zmdi zmdi-hc-2x zmdi-eye"></i></a></div></td>

        $html = '<table id="data-table" class="table table-bordered" style="width:100%">
                    <thead class="thead-default">
                        <tr>
                            <th></th>
                            <th>ITEMPLAN MADRE</th>
                            <th>ITEMPLAN HIJOS</th>
                            <th>NOMBRE</th>
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
                        </tr>
                    </thead>

                    <tbody>';
        foreach ($arrayData as $row) {
            $cont++;
            $prioridad = '';
            $descarga = '<a download href="' . base_url() . $row['carta_pdf'] . '"><i title="CARTA" style="color:#A4A4A4" class="zmdi zmdi-hc-2x zmdi-download"></i></a>&nbsp;&nbsp;';

            if ($row['idPrioridad'] == '1') {
                $prioridad = '<a onclick="modalConPrioridad(' . "'" . $row['itemplan_m'] . "'" . ')"><i title="EDITAR CON PRIORIDAD" style="color:#A4A4A4" class="zmdi zmdi-hc-2x zmdi-edit"></i></a>&nbsp;&nbsp;';
            } else {
                $prioridad = '<a onclick="modalSinPrioridad(' . "'" . $row['itemplan_m'] . "'" . ')"><i title="EDITAR CON PRIORIDAD" style="color:#A4A4A4" class="zmdi zmdi-hc-2x zmdi-edit"></i></a>&nbsp;&nbsp;';
            }

            $html .= '<tr>
                                        <td><div><a onclick="modalItemplanHijos(' . "'" . $row['itemplan_m'] . "'" . ')"><i title="VER ITEMPLAN HIJOS" style="color:#A4A4A4" class="zmdi zmdi-hc-2x zmdi-refresh-alt"></i></a></div></td>
                                        <td>' . $row['itemplan_m'] . '</td>  
                                        <td>' . $this->m_bandeja_observacion->cantidadItemplanHijos($row['itemplan_m']) . '</td>  
                                        <td>' . utf8_decode($row['nombre']) . '</td>
                                        <td>' . utf8_decode(mb_strtoupper($row['proyectoDesc'])) . '</td>
                                        <td>' . $row['subDesc'] . '</td>
                                        <td>' . $row['prioridad'] . '</td>
                                        <td>' . $row['costoEstimado'] . '</td>
                                        <td>' . $row['codigo_solicitud'] . '</td>
                                        <td>' . $row['orden_compra'] . '</td>
                                        <td>' . $row['empresaColabDesc'] . '</td>
                                        <td>' . $row['nombre_cliente'] . '</td>
                                        <td>' . $row['numero_carta'] . '</td>
                                        <td>' . $row['fecha_registro'] . '</td>
                                    </tr>';
        }
        $html .= '</tbody></table>';
        return utf8_decode($html);
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
        $dataConsulta = $this->m_bandeja_observacion->hijosItemMadreDetalle($id);
        $html = '';
        $totalMA = 0;
        $totalMo = 0;
        if (count($dataConsulta) == 0) {
            $html .= '<table id="data-table2" class="table table-bordered" style="width:100%">
                                                <thead class="thead-default">
                                                    <tr>
                                                        <th>DISEÑO</th>
                                                        <th>OPERACION</th>
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
                                                        <th>DISEÑO</th>
                                                        <th>OPERACION</th>
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
                if ($row->estado_di == 2) {
                    $dise = '';
                } else if ($row->estado_di == 3) {
                    $dise = '<div style="cursor: pointer;"  title="Validar" class="text-center" onclick="modalValiOpe(' . "'" . $row->itemPlan . "'" . ',1)"><i class="zmdi zmdi-hc-2x zmdi-edit"></i></div>';
                } else {
                    $dise = '';
                }

                if ($row->estado_ope == 2) {
                    $ope = '';
                } else if ($row->estado_ope == 3) {
                    $ope = '<div style="cursor: pointer;"  title="Validar" class="text-center" onclick="modalValiOpe(' . "'" . $row->itemPlan . "'" . ',2)"><i class="zmdi zmdi-hc-2x zmdi-edit"></i></div>';
                } else {
                    $ope = '';
                }
//                <td><input  type="checkbox" checked></td>
//                <td><input checked class="custom-checkbox" type="checkbox" value="verde" id="input-verde" name="coloresPreferidos"><label for="input-verde"></label></td>


                $html .= '<tr>
                           <td>' . $dise . '</td>
                            <td>' . $ope . '</td>
                            <td width="5%"><div><a style="cursor: pointer;"  href="getAnalisisEconomico?itemplan=' . $row->itemPlan . '" target="_blank"><i title="Detalle economico" style="color:#A4A4A4" class="zmdi zmdi-hc-2x zmdi-money"></i></a></div></td>
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
        $montoToltal = $this->m_bandeja_observacion->montoToltal($id);
        $total = 0;
        foreach ($montoToltal as $row) {
            $total = $total + $row->total_mat;
        }
        return $total;
    }

    function montoToltalMO($id) {
        $montoToltal = $this->m_bandeja_observacion->montoToltal($id);
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
                'ruta_carta_pdf' => $ruta,
                'fecha_recepcion' => $fecRecepcion,
                'nombre_cliente' => $inputNomCli,
                'numero_carta' => $inputNumCar
            );

            $data = $this->m_bandeja_observacion->updateConPrioridadDetalle($objRegDetalle, $itemplanM);
            if ($data['error'] == EXIT_ERROR) {
                throw new Exception('Error al Actualziar Itemaplan Madre');
            } else {
                $this->m_bandeja_observacion->updateConPrioridad($objReg, $itemplanM);
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
            $data['datos'] = $this->m_bandeja_observacion->getEditItemplanMadre($id);
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

            $data = $this->m_bandeja_observacion->updateConPrioridadDetalle($objRegDetalle, $itemplanM);
            if ($data['error'] == EXIT_ERROR) {
                throw new Exception('Error al Actualziar Itemaplan Madre');
            } else {
                $this->m_bandeja_observacion->updateConPrioridad($objReg, $itemplanM);
                if ($idpep) {
                    if ($selectPrioridad === '1') {
                        _log('idpep : ' . $idpep);
                        $this->m_bandeja_observacion->OCregistroItemplanMadre($itemplanM, $idpep);
                    }
                }
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function validarItemplan() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {

            $objReg = array(
                'idUsuObs' => $this->session->userdata('idPersonaSession'),
                'observacion' => $this->input->post('textComentario'),
                'fecha_registro' => $this->fechaActual(),
                $this->input->post('colum') => null
            );

            $data = $this->m_bandeja_observacion->UpdatevalidarItemplan($objReg, $this->input->post('itemPlan'));
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

}
