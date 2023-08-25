<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class C_control_exceso extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_itemfault/M_exceso', 'exceso');
        $this->load->model('mf_pqt_mantenimiento/m_pqt_central');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->library('map_utils/coordenadas_utils');
    }

    public function index() {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {
            $data['tablaSiom'] = $this->getBandejaExceso(null, null, null, null);
            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $permisos = $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, 277, 278, 6);
            $data['opciones'] = $result['html'];
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_itemfault/v_control_exceso', $data);
            } else {
                redirect('login', 'refresh');
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    function filtrarTablaControlExceso() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            $situacion = ($this->input->post('situacion') == '') ? null : $this->input->post('situacion');
            $area = ($this->input->post('area') == '') ? null : $this->input->post('area');
            $itemplan = ($this->input->post('itemplan') == '') ? null : $this->input->post('itemplan');
            $data['tablaBandejaCP'] = $this->getBandejaExceso($situacion, $area, $itemplan);
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function getBandejaExceso($situacion, $area, $itemplan) {
        if ($situacion == null && $area == null && $itemplan == null) {
            $data = null;
        } else {
            $data = $this->exceso->getBandejaExceso($situacion, $area, $itemplan);
        }
        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th></th>
                            <th>ITEMFAULT</th>
                            <th>ELEMENTO DE SERVICIO</th>                               
                            <th>TIPO AREA</th>
                            <th>COSTO INICIAL</th>                           
                            <th>COSTO FINAL</th>                        
                            <th>COSTO EXCEDENTE</th>  
                            <th>USUA. SOLICITA</th>
                            <th>FEC. SOLICITA</th>
                            <th>USUA. VALIDA</th>
                            <th>FEC. VALIDA</th>
                            <th>SITUACION</th>
                        </tr>
                    </thead>                    
                    <tbody>';
        if ($data != null) {

            foreach ($data as $row) {
                $accion = (($row->situacion == 'PENDIENTE') ? '
                                <div class="row">
                                    <div class="col-sm-2">
                                      <a class="atenderSol" cos="' . $row->costo_final_nf . '"sol="' . $row->id_solicitud . '" acc="1"><i title="Aprobar" class="zmdi zmdi-hc-2x zmdi-check-circle" style="color: green;"></i></a>                    
                                    </div>
                                    <div class="col-sm-2">
                                      <a class="atenderSol" cos="' . $row->costo_final_nf . '" sol="' . $row->id_solicitud . '" acc="2"><i title="Rechazar" class="zmdi zmdi-hc-2x zmdi-close-circle" style="color: red;"></i></a>
                                    </div>
                                </div>' : '') . ' ';
                $html .= ' <tr>              
                            <td>' . $accion . '</td>              
                            <td>' . $row->itemfault . '</td>
                            <td>' . $row->elementoDesc . '</td>
                            <td>' . $row->tipo_po . '</td>
                            <td>' . $row->costo_inicial . '</td>
                            <td>' . $row->costo_final . '</td>
                            <td>' . $row->exceso_solicitado . '</td>
                            <td>' . utf8_decode($row->usua_solicita) . '</td>
                            <td>' . $row->fecha_solicita . '</td>
                            <td>' . utf8_decode($row->usua_valida) . '</td>
                            <td>' . $row->fecha_valida . '</td>
                            <td>' . $row->situacion . '</td>
                        </tr>';
            }
        }
        $html .= '</tbody>
                </table>';

        return $html;
    }

    function validarControlPresupuestal() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {

            $accion = $this->input->post('accion');
            $idSolicitud = $this->input->post('solicitud');
            $comentario = $this->input->post('comentario');
            $costoFinal = $this->input->post('costoFinal');
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;
            if ($idUsuario == null) {
                throw new Exception('Su sesion expiro, porfavor vuelva a logearse.');
            }
            if ($accion == null || $idSolicitud == null) {
                throw new Exception('Datos Invalidos, refresque la pagina y vuelva a intentarlo.');
            }
            if ($comentario == null || $comentario == '') {
                throw new Exception('Ingresar comentario');
            }

            $dataUpdateSolicitud = array('usuario_valida' => $this->session->userdata('idPersonaSession'),
                'fecha_valida' => $this->fechaActual(),
                'estado_valida' => $accion, //1=APROBADO  2=RECHAZADO
                'comentario_valida' => utf8_decode(strtoupper($comentario)));

            if ($accion == 2) {//rechazar
                $data = $this->exceso->rejectSolicitud($dataUpdateSolicitud, $idSolicitud);
            } else if ($accion == 1) {//aprobar
                $infoObra = $this->exceso->getInfoObraByIdSolicitud($idSolicitud);
                if ($infoObra == null) {
                    throw new Exception('Ocurrio un error al obetener la informacion de la solicitud, refresque la pagina y vuelva a intentarlo.');
                }
                if ($infoObra['tipo_po'] == 2) {//material{
                    if ($infoObra['montoMAT'] > $costoFinal) {
                        throw new Exception('El costo ingresado es menor al costo actual ' . number_format($infoObra['montoMAT'], 2) . ', favor de ingresar un costo mayor');
                    }
                    $dataItemplan = array('montoMAT' => $costoFinal);
                } else if ($infoObra['tipo_po'] == 1) {//mano_obra
                    if ($infoObra['montoMO'] > $costoFinal) {
                        throw new Exception('El costo ingresado es menor al costo actual ' . number_format($infoObra['montoMO'], 2) . ', favor de ingresar un costo mayor');
                    }
                    $dataItemplan = array('montoMO' => $costoFinal);
                } else {
                    throw new Exception('Ocurrio un error al obetener la informacion del tipo de la solicitud, refresque la pagina y vuelva a intentarlo.');
                }
                if ($infoObra['itemfault'] == null) {
                    throw new Exception('Ocurrio un error al obetener el itemplan de la solicitud, refresque la pagina y vuelva a intentarlo.');
                }
                $data = $this->exceso->aprobSolicitud($dataItemplan, $infoObra['itemfault'], $dataUpdateSolicitud, $idSolicitud);
            } else {
                throw new Exception('Ocurrio un error al obetener la informacion del tipo de la accion a realizar, refresque la pagina y vuelva a intentarlo.');
            }

            /*
              $data['tablaBandejaSiom'] = $this->getTablaSiom(null,null,null,null,null,null); */
            //
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function CreateExceso() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $itemfault = $this->input->post('itemfault');
            $costo_inicial = $this->input->post('costo_inicial');
            $exceso_solicitado = $this->input->post('exceso_solicitado');
            $costo_final = $this->input->post('costo_final');
            $tipo_po = $this->input->post('tipo_po');

            $arrayDataOP = array(
                'id_solicitud' => NULL,
                'itemfault' => $itemfault,
                'tipo_po' => $tipo_po,
                'costo_inicial' => $costo_inicial,
                'exceso_solicitado' => $exceso_solicitado,
                'costo_final' => $costo_final,
                'usuario_solicita' => $this->session->userdata('idPersonaSession'),
                'fecha_solicita' => $this->fechaActual(),
                'usuario_valida' => NULL,
                'fecha_valida' => NULL,
                'estado_valida' => NULL,
                'comentario_valida' => NULL
            );
            $data = $this->exceso->CreateExceso($arrayDataOP);
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
