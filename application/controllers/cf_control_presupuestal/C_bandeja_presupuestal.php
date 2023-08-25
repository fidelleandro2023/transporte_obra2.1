<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class C_bandeja_presupuestal extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_control_presupuestal/m_control_presupuestal');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index() {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {
            $data['tablaSiom'] = $this->getTablaControlPresupuestal(null, null, null, null);
            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $permisos = $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, 270, 275, ID_MODULO_ADMINISTRATIVO);
//            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_PLAN_DE_OBRA, ID_PERMISO_HIJO_CONSULTAS);

            $data['opciones'] = $result['html'];
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_control_presupuestal/v_bandeja_presupuestal', $data);
            } else {
                redirect('login', 'refresh');
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    function getTablaControlPresupuestal($situacion, $area, $itemplan, $idBandeja) {
        if ($situacion == null && $area == null && $itemplan == null) {
            $data = null;
        } else {
            $data = $this->m_control_presupuestal->getBandejaControlPresupuestal($situacion, $area, $itemplan);
        }
        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th></th>
                            <th>ITEMPLAN</th>
							<th>TIPO SOLICITUD</th>
							<th>PO</th>
							<th>PROYECTO</th> 
                            <th>SUBPROYECTO</th> 
							<th>ESTACION</th>   							
                            <th>TIPO AREA</th>
							<th>EECC</th>
							<th>ZONAL</th>
                            <th>COSTO ACTUAL</th>                            
                            <th>EXCEDENTE SOL.</th>                         
                            <th>COSTO FINAL</th>
                            <th>USUA. SOLICITA</th>
                            <th>FEC. SOLICITA</th>
                            <th>USUA. VALIDA</th>
                            <th>FEC. VALIDA</th>
                            <th>SITUACION</th>
                        </tr>
                    </thead>                    
                    <tbody>';
        if ($data != null) {
			$btnArchivo = null;
			$btnVerDetalle = null;
            foreach ($data as $row) {
				if($row->url_archivo != null) {
					$btnArchivo = '<a href="'.base_url().'/'.$row->url_archivo.'" download>
									<i title="Descargar" class="zmdi zmdi-hc-2x zmdi-case-download"></i>
								   </a>';
				}
				$btnVerDetalle = '<a data-id_solicitud ="'.$row->id_solicitud.'" data-origen ="'.$row->origen.'"
									onclick="openMdlDetalleExceso($(this));">
									<i style="color:blue" title="ver detalle" class="zmdi  zmdi-hc-2x zmdi-eye"></i>
								  </a>';
                $accion = '';
                $html .= ' <tr>              
                            <td>'.$btnVerDetalle.' '.$btnArchivo.'</td>              
                            <td>' . $row->itemplan . '</td>
							<td>' . $row->tipo_origen.'</td>
							<td>' . (($row->codigo_po==null || $row->codigo_po=='null') ? '' : $row->codigo_po) .'</td>
							<td>' . $row->proyectoDesc . '</td>
                            <td>' . $row->subProyectoDesc . '</td>
							<td>' . $row->estacionDesc . '</td>
                            <td>' . $row->tipo_po . '</td>
							<td>' . $row->eecc . '</td>
							<td>' . $row->zonalDesc . '</td>
                            <td>' . $row->costoActualPo . '</td>
                            <td>' . $row->excesoPo . '</td>
                            <td>' . $row->costo_final . '</td>
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
                $data = $this->m_control_presupuestal->rejectSolicitud($dataUpdateSolicitud, $idSolicitud);
            } else if ($accion == 1) {//aprobar
                $infoObra = $this->m_control_presupuestal->getInfoObraByIdSolicitud($idSolicitud);
                if ($infoObra == null) {
                    throw new Exception('Ocurrio un error al obetener la informacion de la solicitud, refresque la pagina y vuelva a intentarlo.');
                }
                if ($infoObra['tipo_po'] == 1) {//material{
                    if ($infoObra['costo_unitario_mat'] > $costoFinal) {
                        throw new Exception('El costo ingresado es menor al costo actual ' . number_format($infoObra['costo_unitario_mat'], 2) . ', favor de ingresar un costo mayor');
                    }
                    $dataItemplan = array('costo_unitario_mat' => $costoFinal);
                } else if ($infoObra['tipo_po'] == 2) {//mano_obra
                    if ($infoObra['costo_unitario_mo'] > $costoFinal) {
                        throw new Exception('El costo ingresado es menor al costo actual ' . number_format($infoObra['costo_unitario_mo'], 2) . ', favor de ingresar un costo mayor');
                    }
                    $dataItemplan = array('costo_unitario_mo' => $costoFinal);
                } else {
                    throw new Exception('Ocurrio un error al obetener la informacion del tipo de la solicitud, refresque la pagina y vuelva a intentarlo.');
                }
                if ($infoObra['itemplan'] == null) {
                    throw new Exception('Ocurrio un error al obetener el itemplan de la solicitud, refresque la pagina y vuelva a intentarlo.');
                }
                $data = $this->m_control_presupuestal->aprobSolicitud($dataItemplan, $infoObra['itemplan'], $dataUpdateSolicitud, $idSolicitud);
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

    function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone', 'America/Lima');
        setlocale(LC_TIME, "es_ES", "esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }

    function generarSolicitud() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            $itemplan = $this->input->post('itemplan');
            $tipo_po = $this->input->post('tipo_po');
            $costo_inicial = $this->input->post('costo_inicial');
            $exceso = $this->input->post('exceso_solicitado');
            $costo_final = $this->input->post('costo_final');

            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;
            if ($idUsuario == null) {
                throw new Exception('Su sesion expiro, porfavor vuelva a logearse.');
            }
            $dataInsert = array('itemplan' => $itemplan,
                'tipo_po' => $tipo_po,
                'costo_inicial' => $costo_inicial,
                'exceso_solicitado' => $exceso,
                'costo_final' => $costo_final,
                'usuario_solicita' => $idUsuario,
                'fecha_solicita' => $this->fechaActual()
            );

            $data = $this->m_control_presupuestal->registrarSolicitudCP($dataInsert);
            //$data['tablaBandejaSiom'] = $this->getTablaSiom(null,null,null,null,null,null);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function filtrarTablaCP() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            $situacion = ($this->input->post('situacion') == '') ? null : $this->input->post('situacion');
            $area = ($this->input->post('area') == '') ? null : $this->input->post('area');
            $itemplan = ($this->input->post('itemplan') == '') ? null : $this->input->post('itemplan');
            $idBandeja = ($this->input->post('idBandeja') == '') ? null : $this->input->post('idBandeja');
            $data['tablaBandejaCP'] = $this->getTablaControlPresupuestal($situacion, $area, $itemplan, $idBandeja);
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

}
