<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class C_bandeja_presupuestal_mo extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_liquidacion_mo/m_control_presupuestal_mo');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index() {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {
            $data['tablaSiom'] = $this->getTablaControlPresupuestalMo(null, null, null, null);
            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $permisos = $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, 270, 276, ID_MODULO_ADMINISTRATIVO);
            $data['opciones'] = $result['html'];
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_liquidacion_mo/v_bandeja_presupuestal_mo', $data);
            } else {
                redirect('login', 'refresh');
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    function getTablaControlPresupuestalMo($itemplan, $codigoSolicitud, $situacion, $idBandeja) {

        $data = $this->m_control_presupuestal_mo->getBandejaControlPresupuestalMo($itemplan, $codigoSolicitud, $situacion);

        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>ACCION</th>
                            <th>CODIGO SOLICITUD</th>
                            <th>ITEMPLAN</th>
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
                            <th>SITUACI&Oacute;N</th>				
                        </tr>
                    </thead>                    
                    <tbody>';
        if ($data != null) {
            foreach ($data as $row) {
                $btnVerDetalle = '<a data-codigo_po ="'.$row['codigo_po'].'" data-codigo_solicitud="' . $row['codigo_solicitud'] . '" onclick="openModalDetSol($(this));">
									<i style="color:#A4A4A4" title="ver detalle" class="zmdi  zmdi-hc-2x zmdi-eye"></i>
								  </a>';
                $accion = '';
                $html .= ' <tr>              
                            <td>' . $btnVerDetalle . '</td>              
                            <td>' . $row['codigo_solicitud'] . '</td>
                            <td>' . $row['itemplan'] . '</td>
							<td>' . $row['codigo_po'] . '</td>
							<td>' . $row['proyectoDesc'] . '</td>
							<td>' . $row['subProyectoDesc'].'</td>
							<td>' . $row['estacionDesc'] . '</td>
							<td>    MO</td>
							<td>' . $row['empresaColabDesc'] . '</td>
							<td>' . $row['zonalDesc'] . '</td>
							<td>' . $row['total_actual'] . '</td>
                            <td>' . $row['total_excede'] . '</td>
                            <td>' . $row['total'] . '</td>
                            <td>' . $row['usuarioReg'] . '</td>
                            <td>' . $row['fechaRegistro'] . '</td>
							<td>' . utf8_decode($row['usua_valida']) . '</td>
                            <td>' . $row['fechaValida'] . '</td>
                            <td>' . $row['situacion'] . '</td>
                        </tr>';
            }
        }
        $html .= '</tbody>
                </table>';

        return $html;
    }

    function validarSolicitud() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {

            $flgValida = $this->input->post('flgValida');
            $codSolicitud = $this->input->post('codSolicitud');
            $comentario = $this->input->post('comentario');
            $codigoPo = $this->input->post('codigoPo');
            $costoTotal = $this->input->post('costoTotal');

            $idUsuario = $this->session->userdata('idPersonaSession');
            if ($idUsuario == null || $idUsuario == '') {
                throw new Exception('Su sesion expiro, porfavor vuelva a logearse.');
            }
            if ($flgValida == null || $codSolicitud == null || $codigoPo == null || $costoTotal == null) {
                throw new Exception('Datos Invalidos, refresque la pagina y vuelva a intentarlo.');
            }
            if ($comentario == null || $comentario == '') {
                throw new Exception('Ingresar comentario');
            }

            $dataUpdateSolicitud = array('idUsuarioValida' => $idUsuario,
                'fechaValida' => $this->fechaActual(),
                'estado' => $flgValida, //1=APROBADO  2=RECHAZADO
                'comentario_valida' => utf8_decode(strtoupper($comentario)));

            if ($flgValida == 2) {//rechazar
                $data = $this->m_control_presupuestal_mo->updateEstadoSolicitudMo($dataUpdateSolicitud, $codSolicitud);
            } else if ($flgValida == 1) {//aprobar
                $data = $this->m_control_presupuestal_mo->updateEstadoSolicitudMoValida($dataUpdateSolicitud, $codSolicitud, $codigoPo, $costoTotal);
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

    function filtrarTablaPre() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            $flgEstado = $this->input->post('flgEstado');
            $codigoSoli = $this->input->post('codigoSoli');
            $itemplan = $this->input->post('itemplan');
            $idBandeja = $this->input->post('idBandeja');


            $flgEstado = ($flgEstado == '') ? null : $flgEstado;
            $codigoSoli = ($codigoSoli == '') ? null : $codigoSoli;
            $itemplan = ($itemplan == '') ? null : $itemplan;
            $idBandeja = ($idBandeja == '') ? null : $idBandeja;

            $data['tablaBandejaPresupuesto'] = $this->getTablaControlPresupuestalMo($itemplan, $codigoSoli, $flgEstado, $idBandeja);
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function openMdlDetConsultaPdtPago() {
		$data['msj'] = null;
        $data['error'] = EXIT_ERROR;
		try {
			$codigo_po 	      = $this->input->post('codigo_po');
			$codigo_solicitud = $this->input->post('codigo_solicitud');

			if($codigo_po == null || $codigo_po == '') {
				throw new Exception('sin codigo po, comunicarse con el programador a cargo.');
			}
			
			if($codigo_solicitud == null || $codigo_solicitud == '') {
				throw new Exception('sin codigo solicitud, comunicarse con el programador a cargo.');
			}
		
			
			// $tablaDetallePo 	   = $this->getTablaDetallePo($codigo_po);
			list($tablaDetalleSolicitud, $htmlComentario) = $this->getTablaDetalleSolicitudPo($codigo_po, $codigo_solicitud);
			
			$data['error'] = EXIT_SUCCESS;
			
			// $data['tablaDetallePo'] 	   = $tablaDetallePo;
			$data['tablaDetalleSolicitud'] = $tablaDetalleSolicitud;
			$data['htmlComentario']		   = $htmlComentario;
		} catch(Exception $e) {
			$data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));		
    }
    
    function getTablaDetalleSolicitudPo($codigo_po, $codigo_sol) {
		$ListaDetallePO = $this->m_control_presupuestal_mo->getDataSolicitudMo($codigo_po, $codigo_sol);
		$htmlDetallePO  = null;
		$comentario     = null;
		$htmlDetallePO .= '
						<table id="tbDetalleSolicitud" class="table table-bordered">
							<thead class="thead-default">
								<tr>
									<th>CODIGO</th>
									<th>DESCRIPCION</th>
									<th>BAREMO</th>
									<th>COSTO</th>
									<th>CANTIDAD ACTUAL</th>
									<th>TOTAL ACTUAL</th>
									<th>CANTIDAD NUEVA</th>
									<th>TOTAL NUEVO</th>
								</tr>
							</thead>
							<tbody>';

		foreach ($ListaDetallePO as $row) {
            if($row['comentario_valida'] != null) {
                $comentario = $row['comentario_valida'];
            }

			$htmlDetallePO .= ' <tr>
									<th>' . $row['codigo']. '</th>
									<td>' . utf8_decode($row['descripcion']) . '</td>
									<td>' . $row['baremo'] . '</td>
									<td>' . $row['costo'] . '</td>
									<td>' . $row['cantidad_actual'] . '</td>
									<td>' . $row['total_actual'] . '</td>
									<td style="background:#FAB8AA">' . $row['cantidad_final'] . '</td>
									<td style="background:#FAB8AA">' . $row['total_partida'] . '</td>
								</tr>';
		}
		$htmlDetallePO .= '</tbody>
                    </table>';
					
		$areaComentario = '<textarea class="form-control input-mask" rows="4" disabled>'.utf8_decode($comentario).'</textarea>';			
		return array($htmlDetallePO, $areaComentario) ;
	}

    function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone', 'America/Lima');
        setlocale(LC_TIME, "es_ES", "esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }

    //--------- Ivan Joel More Flores -------//

 

}
