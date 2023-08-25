<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_control_tramas extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_panel_control/m_control_tramas');
		$this->load->model('mf_servicios/M_integracion_sirope');
        $this->load->library('lib_utils');
        $this->load->library('excel');
        $this->load->helper('url');
    }

	public function index() {  	   
        $logedUser = $this->session->userdata('usernameSession');
        $idEcc     = $this->session->userdata('eeccSession');
	    //if($logedUser != null){
            $data['nombreUsuario'] =  $this->session->userdata('usernameSession');	
            $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');	               
            $permisos =  $this->session->userdata('permisosArbol');
            //$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_MANTENIMIENTO, ID_PERMISO_HIJO_MANTENIMIENTO_KIT_EXT);
            $data['title'] = 'CONTROL TRAMAS';
            $data['tablaTramaCotizacion'] = $this->trablaTramasCotizacion();
            $data['trablaTramaSiom']      = $this->trablaTramaSiom();
            $data['tablaTransferencia']   = $this->tablaTramaTransferencia();
			$data['tablaTramaSirope']     = $this->tablaTramasCotizacion();
			$data['tablaTramaRpaSap']     = $this->getTablaTramasRpaSap();
			$data['tablaTramaRpaCotizacion'] = $this->getTablaTramasRpaCotizacion();
			$data['tablaTramaValeReserva']   = $this->trablaTramasVr();
            // $data['tablaSinPo']             = $this->tablaSinPo();
            //$data['listCmbItemplan'] = $this->m_utils->getListItemplanxEecc($idEcc);
            //$data['opciones'] = $result['html'];
            $this->load->view('vf_panel_control/v_control_tramas',$data);
	   //}else{
	       //redirect('login','refresh');
	   //}
    }

    function trablaTramasCotizacion() {
        $dataTablaGestion = $this->m_control_tramas->getBandejaTramaCotizacion();
        $html = '
                <table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                            <tr role="row">
                                <th colspan="2" style="text-align: center;"></th>
                                <th colspan="2" style="text-align: center; background-color: black;">0 - 4 HORAS</th>
                                <th colspan="2" style="text-align: center; background-color: darksalmon;">5 - 12 HORAS</th>
                                <th colspan="2" style="text-align: center; background-color: lightblue;"> 13 - 48 HORAS</th>
                                <th colspan="2" style="text-align: center; background-color: green;"> > 48 HORAS</th>
                            </tr
                            <tr role="row">
                                <th colspan="1">N°</th>
                                <th colspan="1">TIPO DE TRAMAS</th>
                                <th colspan="1" style="text-align:center">CORRECTO</th>
                                <th colspan="1" style="text-align:center">INCORRECTO</th>
                                <th colspan="1" style="text-align:center">CORRECTO</th>
                                <th colspan="1" style="text-align:center">INCORRECTO</th>
                                <th colspan="1" style="text-align:center">CORRECTO</th>
                                <th colspan="1" style="text-align:center">INCORRECTO</th>
                                <th colspan="1" style="text-align:center">CORRECTO</th>
                                <th colspan="1" style="text-align:center">INCORRECTO</th>
                            </tr>
                        </thead>                  
                    <tbody>';
        
        $style = null;
        $cont = 0;
        foreach($dataTablaGestion as $row){
            $cont++;
            // if($row['estado'] == 1)  {
            //     $style = '#D4FABC';
            // } else {
            //     $style = '#F5D7C3';
            // }
            

            $html .='   <tr>
                            <td>'.$row['orden'].'</td>
                            <td>'.$row['descTipo'].'</td>
                            <td style="background:#D4FABC"><a style="color:blue" data-flg_tipo="'.$row['flg_tipo'].'" data-exito="1" data-intervalo_h="1" onclick="getDetalle($(this))">'.$row['menor_4h_exitoso'].'</a></td>
                            <td style="background:#F5D7C3"><a style="color:blue" data-flg_tipo="'.$row['flg_tipo'].'" data-exito="0" data-intervalo_h="1" onclick="getDetalle($(this))">'.$row['menor_4h_no_exitoso'].'</a></td>
                            <td style="background:#D4FABC"><a style="color:blue" data-flg_tipo="'.$row['flg_tipo'].'" data-exito="1" data-intervalo_h="2" onclick="getDetalle($(this))">'.$row['5h_9h_exitoso'].'</a></td>
                            <td style="background:#F5D7C3"><a style="color:blue" data-flg_tipo="'.$row['flg_tipo'].'" data-exito="0" data-intervalo_h="2" onclick="getDetalle($(this))">'.$row['5h_9h_no_exitoso'].'</a></td>
                            <td style="background:#D4FABC"><a style="color:blue" data-flg_tipo="'.$row['flg_tipo'].'" data-exito="1" data-intervalo_h="3" onclick="getDetalle($(this))">'.$row['10h_13h_exitoso'].'</a></td>
                            <td style="background:#F5D7C3"><a style="color:blue" data-flg_tipo="'.$row['flg_tipo'].'" data-exito="0" data-intervalo_h="3" onclick="getDetalle($(this))">'.$row['10h_13h_no_exitoso'].'</a></td> 
                            <td style="background:#D4FABC"><a style="color:blue" data-flg_tipo="'.$row['flg_tipo'].'" data-exito="1" data-intervalo_h="4" onclick="getDetalle($(this))">'.$row['mayor_14h_exitoso'].'</a></td> 
                            <td style="background:#F5D7C3"><a style="color:blue" data-flg_tipo="'.$row['flg_tipo'].'" data-exito="0" data-intervalo_h="4" onclick="getDetalle($(this))">'.$row['mayor_14h_no_exitoso'].'</a></td> 	                             
                        </tr>';
        }
            $html .='</tbody>
            </table>';
            return utf8_decode($html);
    }

    function getTablaDetalle() {
        $data['msj'] = '';
        $data['error'] = EXIT_ERROR;
        try {
            $flgTipo = $this->input->post('flgTipo');
            $exito   = $this->input->post('exito');
            $intervalo_h = $this->input->post('intervalo_h');
            if($flgTipo == null || $exito == null) {
                throw new Exception("ND");
            }

            $data['tablaDetalle'] = $this->tablaDetalle($flgTipo, $exito, $intervalo_h);
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function tablaDetalle($flgTipo, $flgExito, $intervalo_h) {
        $dataTablaGestion = $this->m_control_tramas->getBandejaTramaDetalleCotizacion($flgTipo, $flgExito, $intervalo_h);
        $html = '
                <table id="tbDetalleControl" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
							<th>ENVIO TRAMA</th>
                            <th>NRO.</th>
                            <th>SISEGO</th>
                            <th>CODIGO</th>
                            <th>ORIGEN</th>    
                            <th>FECHA</th> 
                            <th>DESCRIPCION</th> 
                        </tr>
                    </thead>                    
                    <tbody>';
        
        $style = null;
        $cont = 0;
        foreach($dataTablaGestion as $row){
            $cont++;
            if($row['estado'] == 1)  {
                $style = '#D4FABC';
            } else {
                $style = '#F5D7C3';
            }
			$btn_envio_trama = null;
			$idUsuario =  $this->session->userdata('idPersonaSession');

			if($flgTipo == 3 && ($idUsuario == 3 || $idUsuario == 1806)) {
				$btn_envio_trama = '<a data-codigo_coti="'.$row['ptr'].'" onclick="reenviarCotizacion($(this));"><i class="zmdi zmdi-hc-3x zmdi-arrow-left"></i></a>';
			}
			
            $html .='   <tr>
							<td style="background:'.$style.'">'.$btn_envio_trama.'</td>
                            <td style="background:'.$style.'">'.$cont.'</td>
                            <td style="background:'.$style.'">'.$row['sisego'].'</td>
                            <td style="background:'.$style.'">'.$row['ptr'].'</td>
                            <td style="background:'.$style.'">'.$row['origen'].'</td>
                            <th style="background:'.$style.'">'.$row['fecha_registro'].'</th>
                            <td style="background:'.$style.'">'.$row['descripcion'].'</td>                          
                        </tr>';
        }
            $html .='</tbody>
            </table>';
            return utf8_decode($html);
    }

    function generarPOLicGestion() {
        $data['msj'] = '';
        $data['error'] = EXIT_ERROR;
        try {
            $itemplan   = $this->input->post('itemplan');
            $idEstacion = $this->input->post('idEstacion');

            if($itemplan == null || $itemplan == '') {
                throw new Exception('error, No se detecto el itemplan, comunicarse con el programador.');
            } 

            if($idEstacion == null || $idEstacion == '') {
                throw new Exception('error, No se detecto el estaci&oacute;n, comunicarse con el programador.');
            }

            $resp = $this->m_utils->generarPOLicenciaGestion($itemplan, 1493, $idEstacion);

            if($resp == 3) {
                $data['error'] = EXIT_ERROR;
                throw new Exception('Error, No tiene entidades trabajadas.');
            }

            if($resp == 2) {
                $data['error'] = EXIT_ERROR;
                throw new Exception('Error, No tiene porcentaje al 100%.');
            }

            if($resp == null || $resp == '') {
                $data['error'] = EXIT_ERROR;
                throw new Exception('Error al generar la PO.');
            }
            
            $data['tablaSinPO'] = $this->tablaGestionSinPO();
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function trablaTramaSiom() {
        $dataTablaSiom = $this->m_control_tramas->getBandejaTramaSiom();
        $html = '
                <table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                            <tr role="row">
                                <th colspan="1" style="text-align: center;"></th>
                                <th colspan="2" style="text-align: center; background-color: palegoldenrod;">0 - 4 HORAS</th>
                                <th colspan="2" style="text-align: center; background-color: darksalmon;">5 - 12 HORAS</th>
                                <th colspan="2" style="text-align: center; background-color: lightblue;"> 13 - 48 HORAS</th>
                                <th colspan="2" style="text-align: center; background-color: green;"> > 48 HORAS</th>
                            </tr
                            <tr role="row">
                             
                                <th colspan="1">TIPO DE TRAMAS</th>
                                <th colspan="1" style="text-align:center">CORRECTO</th>
                                <th colspan="1" style="text-align:center">INCORRECTO</th>
                                <th colspan="1" style="text-align:center">CORRECTO</th>
                                <th colspan="1" style="text-align:center">INCORRECTO</th>
                                <th colspan="1" style="text-align:center">CORRECTO</th>
                                <th colspan="1" style="text-align:center">INCORRECTO</th>
                                <th colspan="1" style="text-align:center">CORRECTO</th>
                                <th colspan="1" style="text-align:center">INCORRECTO</th>
                            </tr>
                        </thead>                  
                    <tbody>';
        
        $style = null;
        $cont = 0;
        foreach($dataTablaSiom as $row){
            $cont++;
            // if($row['estado'] == 1)  {
            //     $style = '#D4FABC';
            // } else {
            //     $style = '#F5D7C3';
            // }
            

            $html .='   <tr>
                          
                            <td>'.$row['descTipo'].'</td>
                            <td style="background:#D4FABC"><a style="color:blue" data-flg_tipo="'.$row['flg_tipo'].'" data-exito="1" data-intervalo_h="1" onclick="getDetalleSiom($(this))">'.$row['menor_4h_exitoso'].'</a></td>
                            <td style="background:#F5D7C3"><a style="color:blue" data-flg_tipo="'.$row['flg_tipo'].'" data-exito="0" data-intervalo_h="1" onclick="getDetalleSiom($(this))">'.$row['menor_4h_no_exitoso'].'</a></td>
                            <td style="background:#D4FABC"><a style="color:blue" data-flg_tipo="'.$row['flg_tipo'].'" data-exito="1" data-intervalo_h="2" onclick="getDetalleSiom($(this))">'.$row['5h_12h_exitoso'].'</a></td>
                            <td style="background:#F5D7C3"><a style="color:blue" data-flg_tipo="'.$row['flg_tipo'].'" data-exito="0" data-intervalo_h="2" onclick="getDetalleSiom($(this))">'.$row['5h_12h_no_exitoso'].'</a></td>
                            <td style="background:#D4FABC"><a style="color:blue" data-flg_tipo="'.$row['flg_tipo'].'" data-exito="1" data-intervalo_h="3" onclick="getDetalleSiom($(this))">'.$row['13h_48h_exitoso'].'</a></td>
                            <td style="background:#F5D7C3"><a style="color:blue" data-flg_tipo="'.$row['flg_tipo'].'" data-exito="0" data-intervalo_h="3" onclick="getDetalleSiom($(this))">'.$row['13h_48h_no_exitoso'].'</a></td> 
                            <td style="background:#D4FABC"><a style="color:blue" data-flg_tipo="'.$row['flg_tipo'].'" data-exito="1" data-intervalo_h="4" onclick="getDetalleSiom($(this))">'.$row['mayor_48h_exitoso'].'</a></td> 
                            <td style="background:#F5D7C3"><a style="color:blue" data-flg_tipo="'.$row['flg_tipo'].'" data-exito="0" data-intervalo_h="4" onclick="getDetalleSiom($(this))">'.$row['mayor_48h_no_exitoso'].'</a></td> 	                             
                        </tr>';
        }
            $html .='</tbody>
            </table>';
            return utf8_decode($html);
    }

    function getTablaDetalleTramaSiom() {
        $data['msj'] = '';
        $data['error'] = EXIT_ERROR;
        try {
            $flgTipo = $this->input->post('flgTipo');
            $exito   = $this->input->post('exito');
            $intervalo_h = $this->input->post('intervalo_h');
            if($flgTipo == null || $exito == null) {
                throw new Exception("ND");
            }

            if($flgTipo == 1) {
                $tabla = $this->tablaDetalleEnvioSiom($flgTipo, $exito, $intervalo_h);
            } else if($flgTipo == 2) {
                $tabla = $this->tablaDetalleEstadoSiom($flgTipo, $exito, $intervalo_h);
            }

            $data['tablaDetalle'] = $tabla;
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function tablaDetalleEnvioSiom($flgTipo, $flgExito, $flgIntervalo) {
        $dataTablaGestion = $this->m_control_tramas->getDetalleSiomEnvio($flgExito, $flgIntervalo);
        $html = '
                <table id="tbDetalleEnvioSiom" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>Nro.</th>
                            <th>ITEMPLAN</th>
                            <th>PTR</th>
                            <th>FECHA</th>    
                            <th>DESCRIPCION</th> 
                        </tr>
                    </thead>                    
                    <tbody>';
        
        $style = null;
        $cont = 0;
        foreach($dataTablaGestion as $row){
            $cont++;
            if($row['estado'] == 1)  {
                $style = '#D4FABC';
            } else {
                $style = '#F5D7C3';
            }

            $html .='   <tr>
                            <td style="background:'.$style.'">'.$cont.'</td>
                            <td style="background:'.$style.'">'.$row['itemplan'].'</td>
                            <td style="background:'.$style.'">'.$row['ptr'].'</td>
                            <td style="background:'.$style.'">'.$row['fecha_envio'].'</td>
                            <th style="background:'.$style.'">'.$row['mensaje'].'</th>                       
                        </tr>';
        }
            $html .='</tbody>
            </table>';
        return utf8_decode($html);
    }

    function tablaDetalleEstadoSiom($flgTipo, $flgExito, $flgIntervalo) {
        $dataTablaGestion = $this->m_control_tramas->getDetalleEstadoSiom($flgExito, $flgIntervalo);
        $html = '
                <table id="tbDetalleEnvioSiom" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>NRO.</th>
                            <th>CODIGO SIOM</th>
                            <th>ESTADO</th>
                            <th>USUARIO</th>    
                            <th>FECHA</th> 
                        </tr>
                    </thead>                    
                    <tbody>';
        
        $style = null;
        $cont = 0;
        foreach($dataTablaGestion as $row){
            $cont++;
            if($row['estado_transaccion'] == 1)  {
                $style = '#D4FABC';
            } else {
                $style = '#F5D7C3';
            }

            $html .='   <tr>
                            <td style="background:'.$style.'">'.$cont.'</td>
                            <td style="background:'.$style.'">'.$row['codigo_siom'].'</td>
                            <td style="background:'.$style.'">'.$row['estado_desc'].'</td>
                            <td style="background:'.$style.'">'.$row['usuario_registro'].'</td>
                            <td style="background:'.$style.'">'.$row['fechaRegistro'].'</td>                      
                        </tr>';
        }
            $html .='</tbody>
            </table>';
        return utf8_decode($html);
    }
    
    function tablaTramaTransferencia() {
        $dataTabla = $this->m_control_tramas->getTramaTransferencia();
        $html = '
        <table id="data-table" class="table table-bordered">
            <thead class="thead-default">
                <tr role="row">
                    <th colspan="2" style="text-align: center;"></th>
                    <th colspan="1" style="text-align: center;">HOY ('.date('d / m',strtotime($this->fechaActual())).')</th>
                    <th colspan="1" style="text-align: center;">AYER('.date('d / m',strtotime($this->fechaActual().' - 1 days')).') </th>
                    <th colspan="1" style="text-align: center;">ANTES DE AYER ('.date('d / m',strtotime($this->fechaActual().' - 2 days')).')</th>
                    <th colspan="1" style="text-align: center;">HACE 3d ('.date('d / m',strtotime($this->fechaActual().' - 3 days')).')</th>
                    <th colspan="1" style="text-align: center;">HACE 4d ('.date('d / m',strtotime($this->fechaActual().' - 4 days')).')</th>
                </tr>
            </thead>                  
            <tbody>';
        
        $style = null;
        $cont = 0;
        foreach($dataTabla as $row){
            $arrayRow = explode('|', $row['cant_dias']);

            if($row['flg_tipo'] == 1) {
                $title = 'PLAN OBRA';
                $row = 3;
            } else {
                $row = 3;
                $title = 'DET. PLAN';
            }
            $html .= '<tr role="row"> 
                        <th rowspan="'.$row.'">'.$title.'</th>
                      </tr>';
            foreach($arrayRow as $row1) {
                $row2 = explode(',', $row1);
                $html .='<tr>
                            <td>'.$row2[0].'</td>
                            <td style="background:#D4FABC">'.$row2[1].'</td>
                            <td style="background:#D4FABC">'.$row2[2].'</td>
                            <td style="background:#D4FABC">'.$row2[3].'</td>
                            <td style="background:#D4FABC">'.$row2[4].'</td>
                            <td style="background:#D4FABC">'.$row2[5].'</td>                    
                        </tr>';
            }
        }
            $html .='</tbody>
            </table>';
        return utf8_decode($html);
    }

    function getTablaDetalleTransferencia() {
        $data['msj'] = '';
        $data['error'] = EXIT_ERROR;
        try {
            $tipo     = $this->input->post('tipo');
            $dia      = $this->input->post('dia');
            $flgTabla = $this->input->post('tabla');
            $intervalo_h = $this->input->post('intervalo_h');
            if($tipo == null || $dia == null || $flgTabla == null) {
                throw new Exception("ND");
            }


            if($flgTabla == 1) {
                $tabla = $this->tablaDetalleTransferencia($tipo, $dia);
            } else if($flgTabla == 2) {
                $tabla = $this->tablaDetalleTransferencia($tipo, $dia);
            }

            $data['tablaDetalle'] = $tabla;
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function tablaDetalleTransferencia($tipo, $dia) {
        if($tipo == 'REGISTROS') {
            $dataTablaDetalle = $this->m_control_tramas->getCountRegistroPO($tipo, $dia);
        } else if($tipo == 'REPORTE PO') {
            $dataTablaDetalle = $this->m_control_tramas->getCountReportePO($tipo, $dia);
        } else if($tipo == 'DIFERENCIA') {
            $dataTablaDetalle = $this->m_control_tramas->getDetalleDiferenciaPO($dia);
        }
        $html = '
                <table id="tbDetalleTrans" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>NRO.</th>
                            <th>ITEMPLAN</th>
                            <th>SUBPROYECTO</th>
                            <th>FECHA</th>
                        </tr>
                    </thead>                    
                    <tbody>';
        
        $style = null;
        $cont = 0;
        foreach($dataTablaDetalle as $row){
            $cont++;
            $html .='   <tr>
                            <td style="background:'.$style.'">'.$cont.'</td>
                            <td style="background:'.$style.'">'.$row['itemplan'].'</td>
                            <td style="background:'.$style.'">'.$row['subproyectoDesc'].'</td>
                            <td style="background:'.$style.'">'.$row['fecha_creacion'].'</td>                  
                        </tr>';
        }
            $html .='</tbody>
            </table>';
        return utf8_decode($html);
    }
		
	function tablaTramasCotizacion() {
        $dataTablaGestion = $this->m_control_tramas->getTramaSirope();
        $html = '
                <table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                            <tr role="row">
                                <th colspan="2" style="text-align: center;"></th>
                                <th colspan="3" style="text-align: center; background-color: black;">0 - 4 HORAS</th>
                                <th colspan="3" style="text-align: center; background-color: darksalmon;">5 - 12 HORAS</th>
                                <th colspan="3" style="text-align: center; background-color: lightblue;"> 13 - 48 HORAS</th>
                                <th colspan="3" style="text-align: center; background-color: green;"> > 48 HORAS</th>
                            </tr
                            <tr role="row">
                                <th colspan="1">N°</th>
                                <th colspan="1">TIPO DE TRAMAS</th>
                                <th colspan="1" style="text-align:center">CORRECTO</th>
                                <th colspan="1" style="text-align:center">INCORRECTO</th>
								<th colspan="1" style="text-align:center">INCORRECTO COD EXISTE</th>
                                <th colspan="1" style="text-align:center">CORRECTO</th>
                                <th colspan="1" style="text-align:center">INCORRECTO</th>
								<th colspan="1" style="text-align:center">INCORRECTO COD EXISTE</th>
                                <th colspan="1" style="text-align:center">CORRECTO</th>
                                <th colspan="1" style="text-align:center">INCORRECTO</th>
								<th colspan="1" style="text-align:center">INCORRECTO COD EXISTE</th>
                                <th colspan="1" style="text-align:center">CORRECTO</th>
                                <th colspan="1" style="text-align:center">INCORRECTO</th>
								<th colspan="1" style="text-align:center">INCORRECTO COD EXISTE</th>
                            </tr>
                        </thead>                  
                    <tbody>';
        
        $style = null;
        $cont = 0;
        foreach($dataTablaGestion as $row){
            $cont++;
            // if($row['estado'] == 1)  {
            //     $style = '#D4FABC';
            // } else {
            //     $style = '#F5D7C3';
            // }
            

            $html .='   <tr>
                            <td>'.$cont.'</td>
                            <td>'.$row['descripcion'].'</td>
                            <td style="background:#D4FABC"><a style="color:blue" data-estado="1" data-exito="1" data-intervalo_h="1" onclick="getDetalleTramaSirope($(this))">'.$row['menor_4h_exitoso'].'</a></td>
                            <td style="background:#F5D7C3"><a style="color:blue" data-estado="2" data-exito="0" data-intervalo_h="1" onclick="getDetalleTramaSirope($(this))">'.$row['menor_4h_no_exitoso'].'</a></td>
							<td style="background:#F5D7C3"><a style="color:blue" data-estado="3" data-exito="0" data-intervalo_h="1" onclick="getDetalleTramaSirope($(this))">'.$row['menor_4h_no_exitoso_existe_codigo'].'</a></td>
                            <td style="background:#D4FABC"><a style="color:blue" data-estado="1" data-exito="1" data-intervalo_h="2" onclick="getDetalleTramaSirope($(this))">'.$row['5h_9h_exitoso'].'</a></td>
                            <td style="background:#F5D7C3"><a style="color:blue" data-estado="2" data-exito="0" data-intervalo_h="2" onclick="getDetalleTramaSirope($(this))">'.$row['5h_9h_no_exitoso'].'</a></td>
							<td style="background:#F5D7C3"><a style="color:blue" data-estado="3" data-exito="0" data-intervalo_h="2" onclick="getDetalleTramaSirope($(this))">'.$row['5h_9h_no_exitoso_existe_codigo'].'</a></td>
                            <td style="background:#D4FABC"><a style="color:blue" data-estado="1" data-exito="1" data-intervalo_h="3" onclick="getDetalleTramaSirope($(this))">'.$row['10h_13h_exitoso'].'</a></td>
                            <td style="background:#F5D7C3"><a style="color:blue" data-estado="2" data-exito="0" data-intervalo_h="3" onclick="getDetalleTramaSirope($(this))">'.$row['10h_13h_no_exitoso'].'</a></td> 
							<td style="background:#F5D7C3"><a style="color:blue" data-estado="3" data-exito="0" data-intervalo_h="3" onclick="getDetalleTramaSirope($(this))">'.$row['10h_13h_no_exitoso_existe_codigo'].'</a></td> 
                            <td style="background:#D4FABC"><a style="color:blue" data-estado="1" data-exito="1" data-intervalo_h="4" onclick="getDetalleTramaSirope($(this))">'.$row['mayor_14h_exitoso'].'</a></td> 
                            <td style="background:#F5D7C3"><a style="color:blue" data-estado="2" data-exito="0" data-intervalo_h="4" onclick="getDetalleTramaSirope($(this))">'.$row['mayor_14h_no_exitoso'].'</a></td> 
							<td style="background:#F5D7C3"><a style="color:blue" data-estado="3" data-exito="0" data-intervalo_h="4" onclick="getDetalleTramaSirope($(this))">'.$row['mayor_14h_no_exitoso_existe_codigo'].'</a></td> 							
                        </tr>';
        }
            $html .='</tbody>
            </table>';
            return utf8_decode($html);
    }
	
	function getDetalleTramaSirope() {
        $data['msj'] = '';
        $data['error'] = EXIT_ERROR;
        try {
            $estado  = $this->input->post('estado');
            $exito   = $this->input->post('exito');
            $intervalo_h = $this->input->post('intervalo_h');
            if($estado == null || $intervalo_h == null) {
                throw new Exception("ND");
            }

            $data['tablaDetalle'] = $this->tablaDetalleTramaSirope($estado, $intervalo_h);
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
	
	function tablaDetalleTramaSirope($estado, $intervalo_h) {
		$dataTablaGestion = $this->m_control_tramas->getTramaDetalleSirope($estado, $intervalo_h);
        $html = '
                <table id="tbDetalleSiropeDet" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>Nro.</th>
                            <th>ITEMPLAN</th>
                            <th>CODIGO OT</th>
                            <th>FECHA ENVIO</th>    
                            <th>MENSAJE</th>
							'.(($estado!=1) ? '<th>ACCION</th>' : '').'
                        </tr>
                    </thead>                    
                    <tbody>';
        
        $style = null;
        $cont = 0;
        foreach($dataTablaGestion as $row){
			$accion = '';
            $cont++;
            if($row['estado'] == 1)  {
                $style = '#D4FABC';
            } else {//si es error
                $style = '#F5D7C3';
				if(strtoupper($row['mensaje_recibido']) == 'THE MESSAGE HAS EXPIRED'){
					$accion = '<i title="Reenviar Trama" class="zmdi zmdi-hc-2x zmdi-replay" style="cursor:pointer" data-itemplan="'.$row['itemplan'].'" onclick="openModalReenviarTrama($(this));"></i>';
				}else if(strtoupper($row['mensaje_recibido']) == 'PREVISIóN DE TéRMINO ANTERIOR A FECHA DE INICIO.'){
					$accion = '<i title="Reenviar Trama" class="zmdi zmdi-hc-2x zmdi-replay" style="cursor:pointer" data-itemplan="'.$row['itemplan'].'" onclick="openModalReenviarTramaFecPrev($(this));"></i>';
				}
				//log_message('error','MENSAJE ERROR SIROPE:'.strtoupper($row['mensaje_recibido']));
            }

            $html .='   <tr>
                            <td style="background:'.$style.'">'.$cont.'</td>
                            <td style="background:'.$style.'">'.$row['itemplan'].'</td>
                            <td style="background:'.$style.'">'.$row['codigo_ot'].'</td>
                            <td style="background:'.$style.'">'.$row['fecha_envio'].'</td>
                            <th style="background:'.$style.'">'.$row['mensaje_recibido'].'</th> 
							'.(($estado!=1) ? '<th style="background:'.$style.'">'.$accion.'</th>' : '').'							
                        </tr>';
        }
            $html .='</tbody>
            </table>';
            return utf8_decode($html);
	}

    function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone','America/Lima');
        setlocale(LC_TIME, "es_ES","esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }
	
	
	function reenviarTramaSirope() {
        $data['msj'] = '';
        $data['error'] = EXIT_ERROR;
        try {
            $itemplan  = $this->input->post('itemplan');
			$InfoTrama = $this->m_utils->getInfoItemplanToSiropeTrama($itemplan);
			log_message('error', 'itemplan:'.$itemplan);
			if($InfoTrama	==	null){
				 throw new Exception("Hubo Problemas para obtener la Informacion del itemplan, comunicarse con el Administrador.");
			}
			if($InfoTrama['fecha_prevista_atencion'] == null){
				 throw new Exception("No cuenta con fecha prevista de Atencio FO.");
			}
			if($InfoTrama['fechaInicio'] == null){
				 throw new Exception("El itemplan no cuenta con fechaInicio.");
			}
			log_message('error', 'REENVIO SIROPE:'.$itemplan.'-->'.print_r($InfoTrama, true));
		    $data = $this->M_integracion_sirope->execWs($InfoTrama['itemplan'], $InfoTrama['itemplan'].'FO', $InfoTrama['fechaInicio'], $InfoTrama['fecha_prevista_atencion']);
            //$data['error'] = EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
	
	function reenviarTramaSiropeWitFechaPrevista() {
        $data['msj'] = '';
        $data['error'] = EXIT_ERROR;
        try {
            $itemplan  = $this->input->post('itemplan');
			$InfoTrama = $this->m_utils->getInfoItemplanToSiropeTrama($itemplan);
			if($InfoTrama	==	null){
				 throw new Exception("Hubo Problemas para obtener la Informacion del itemplan, comunicarse con el Administrador.");
			}
			if($InfoTrama['fecha_prevista_atencion'] == null){
				 throw new Exception("No cuenta con fecha prevista de Atencio FO.");
			}
			if($InfoTrama['fechaInicio'] == null){
				 throw new Exception("El itemplan no cuenta con fechaInicio.");
			}
			$dias = 4;//todos son 4 dias a partir del dia actual
			$nuevafecha = strtotime('+' . $dias . ' day', strtotime($this->fechaActual()));
			$idFechaPreAtencionFo = date('Y-m-j', $nuevafecha);
		    $data = $this->M_integracion_sirope->execWs($InfoTrama['itemplan'], $InfoTrama['itemplan'].'FO', $InfoTrama['fechaInicio'], $idFechaPreAtencionFo);
            //$data['error'] = EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
	
	/**nuevo getTablaTramasRpaSap czavalacas 20.11.2019***/
	
	function getTablaTramasRpaSap() {//ultimos 7 dias
		$dataTablaGestion = $this->m_control_tramas->getTramasRpaSap();
        $html = '
                <table id="tbDetalleSirope" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>Tipo Clasif.</th>
                            <th>Estado</th>
							<th>Enviados</th>
                            <th>Ingreso</th>
                            <th>Exitoso</th>    
                            <th>No Exitoso</th>
							<th>Eficiencia</th>
                        </tr>
                    </thead>                    
                    <tbody>';
        
        foreach($dataTablaGestion as $row){
            $total_horario_1 = ($row->horario_1_ok + $row->horario_1_no);
            $total_horario_2 = ($row->horario_2_ok + $row->horario_2_no);
            $total_horario_3 = ($row->horario_3_ok + $row->horario_3_no);
            $total_dia = ($total_horario_1 + $total_horario_2 + $total_horario_3);
            $total_ok_3_dias = ($row->horario_1_ok + $row->horario_2_ok + $row->horario_3_ok);
            $total_send  = ($row->horario_1_send + $row->horario_2_send + $row->horario_3_send);
            $html .='   <tr>
                            <td>'.$row->fecha.'</td>
                            <td>Horario 1</td>
                            <td><a data-fecha="'.$row->fecha.'" data-rango="1" data-estado="5" onclick="getDetalleTramaRpaSap($(this))">'.$row->horario_1_send.'</a></td>
                            <td>'.$total_horario_1.'</td>
                            <td><a data-fecha="'.$row->fecha.'" data-rango="1" data-estado="1" onclick="getDetalleTramaRpaSap($(this))">'.$row->horario_1_ok.'</a></td>
                            <td><a data-fecha="'.$row->fecha.'" data-rango="1" data-estado="2" onclick="getDetalleTramaRpaSap($(this))">'.$row->horario_1_no.'</a></td>    
                            <td>'.(($total_horario_1 > 0) ? number_format(((100*$row->horario_1_ok)/$total_horario_1),2) : 0).'%</td>                        		
                        </tr>
                        <tr>
                            <td></td>
                            <td>Horario 2</td>
                            <td><a data-fecha="'.$row->fecha.'" data-rango="2" data-estado="5" onclick="getDetalleTramaRpaSap($(this))">'.$row->horario_2_send.'</a></td>
                            <td>'.$total_horario_2.'</td>
                            <td><a data-fecha="'.$row->fecha.'" data-rango="2" data-estado="1" onclick="getDetalleTramaRpaSap($(this))">'.$row->horario_2_ok.'</a></td>
                            <td><a data-fecha="'.$row->fecha.'" data-rango="2" data-estado="2" onclick="getDetalleTramaRpaSap($(this))">'.$row->horario_2_no.'</a></td>  
                            <td>'.(($total_horario_2 > 0) ? number_format(((100*$row->horario_2_ok)/$total_horario_2),2) : 0).'%</td>                         		
                        </tr>
                        <tr>
                            <td></td>
                            <td>Horario 3</td>
                            <td><a data-fecha="'.$row->fecha.'" data-rango="3" data-estado="5" onclick="getDetalleTramaRpaSap($(this))">'.$row->horario_3_send.'</a></td>
                            <td>'.$total_horario_3.'</td>
                            <td><a data-fecha="'.$row->fecha.'" data-rango="3" data-estado="1" onclick="getDetalleTramaRpaSap($(this))">'.$row->horario_3_ok.'</a></td>
                            <td><a data-fecha="'.$row->fecha.'" data-rango="3" data-estado="2" onclick="getDetalleTramaRpaSap($(this))">'.$row->horario_3_no.'</a></td>   
                            <td>'.(($total_horario_3 > 0) ? number_format(((100*$row->horario_3_ok)/$total_horario_3),2) : 0).'%</td>                           		
                        </tr>
                        <tr style="font-weight: bolder;background-color: #f3f3f3;">
                            <td></td>
                            <td>Total</td>
                            <td>'.$total_send.'</td>   
                            <td>'.$total_dia.'</td>   
                            <td>'.$total_ok_3_dias.'</td>
                            <td>'.($row->horario_1_no + $row->horario_2_no + $row->horario_3_no).'</td>                            
                            <td>'.(($total_dia > 0) ? number_format(((100*$total_ok_3_dias)/$total_dia),2) : 0).'%</td>                           		
                        </tr>';
        }
            $html .='</tbody>
            </table>';
            return utf8_decode($html);
	}
	
	function getTablaDestalleRpaSap($fecha, $rango_horario, $exitoso) {
	    $dataTablaGestion = $this->m_control_tramas->getDetalleTramasRpaSap($fecha, $rango_horario, $exitoso);
	    $html = '<table id="tbDetalleSapRpa" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                	        <th>PROYECTO</th>
                	        <th>SUBPROYECTO</th>
	                        <th>ITEMPLAN</th>
                            <th>CODIGO PO</th>
							<th>ESTADO PO</th>
                            <th>FECHA ENVIO</th>
                            <th>MENSAJE</th>
                        </tr>
                    </thead>
                    <tbody>';
	
	    foreach($dataTablaGestion as $row){	
	        $html .='   <tr>
	                        <td >'.$row->proyectoDesc.'</td>
	                        <td >'.$row->subproyectoDesc.'</td>
	                        <td >'.$row->itemplan.'</td>
                            <td >'.$row->codigo_po.'</td>  
							<td >'.$row->estado.'</td>							
                            <td >'.$row->fecha.'</td>
                            <td >'.$row->mensaje.'</td>
                        </tr>';
	    }
	    $html .='</tbody>
            </table>';
	    return utf8_decode($html);
	}
	
	function getDetalleTramaRpaSap() {
	    $data['msj'] = '';
	    $data['error'] = EXIT_ERROR;
	    try {
	        $fecha             = $this->input->post('fecha');
	        $rango_horario     = $this->input->post('rango');
	        $exitoso           = $this->input->post('estado');
	        if($fecha == null || $rango_horario == null || $exitoso == null) {
	            throw new Exception("ND");
	        }	
	       if($exitoso==5){
	           $data['tablaDetalleRpa'] = $this->getTablaDestalleRpaSapSend($fecha, $rango_horario);
	        }else{
	           $data['tablaDetalleRpa'] = $this->getTablaDestalleRpaSap($fecha, $rango_horario, $exitoso);
	        }
	        $data['error'] = EXIT_SUCCESS;
	    } catch(Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	function getTablaDestalleRpaSapSend($fecha, $rango_horario) {
	    $dataSend = $this->m_control_tramas->getTramaSendByFechaRango($fecha, $rango_horario);
	    $html = '<table id="tbDetalleSapRpa" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                	        <th>ITEMPLAN</th>
                	        <th>CODIGO PO</th>
	                        <th>PEP 1</th>
                            <th>PEP 2</th>
							<th>GRAFO</th>
                            <th>FECHA ENVIO</th>
                            <th>MENSAJE</th>
                        </tr>
                    </thead>
                    <tbody>';
	    
	    #log_message('error', $dataSend['dataGet']);
	    $dataTablaGestion = json_decode($dataSend['dataGet'], true);
	    #log_message('error', 'array:'.print_r($dataTablaGestion, true));
	    foreach($dataTablaGestion as $row){
	        $html .='   <tr>
	                        <td >'.$row['itemplan'].'</td>
	                        <td >'.$row['codigo_po'].'</td>
	                        <td >'.$row['pep1'].'</td>
                            <td >'.$row['pep2'].'</td>
							<td >'.$row['grafo'].'</td>
                            <td >'.$dataSend['fecha'].'</td>
                            <td >'.$dataSend['mensaje'].'</td>
                        </tr>';
	    }
	    
	    $html .='</tbody>
            </table>';
	    return utf8_decode($html);
	}
	
	function getTablaTramasRpaCotizacion() {
		$dataTablaGestion = $this->m_control_tramas->getCotizacionRpa();
        $html = '
                <table id="tbDetalleSirope" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>Tipo Clasif.</th>
                            <th>Estado</th>
                            <th>Ingreso</th>
                            <th>Exitoso</th>    
                            <th>No Exitoso</th>
							<th>Eficiencia</th>
                        </tr>
                    </thead>                    
                    <tbody>';
        
        foreach($dataTablaGestion as $row){
            $total_horario_1 = ($row->horario_1_ok + $row->horario_1_no);
            $total_horario_2 = ($row->horario_2_ok + $row->horario_2_no);
            $total_horario_3 = ($row->horario_3_ok + $row->horario_3_no);
            $total_dia = ($total_horario_1 + $total_horario_2 + $total_horario_3);
            $total_ok_3_dias = ($row->horario_1_ok + $row->horario_2_ok + $row->horario_3_ok);
            $html .='   <tr>
                            <td>'.$row->fecha.'</td>
                            <td>09:50:00 - 14:50:00</td>
                            <td>'.$total_horario_1.'</td>
                            <td><a data-fecha="'.$row->fecha.'" data-rango="1" data-estado="1" onclick="getDetalleTramaRpaCoti($(this))">'.$row->horario_1_ok.'</a></td>
                            <td><a data-fecha="'.$row->fecha.'" data-rango="1" data-estado="2" onclick="getDetalleTramaRpaCoti($(this))">'.$row->horario_1_no.'</a></td>    
                            <td>'.(($total_horario_1 > 0) ? number_format(((100*$row->horario_1_ok)/$total_horario_1),2) : 0).'%</td>                        		
                        </tr>
                        <tr>
                            <td></td>
                            <td>14:50:00 - 17:50:00</td>
                            <td>'.$total_horario_2.'</td>
                            <td><a data-fecha="'.$row->fecha.'" data-rango="2" data-estado="1" onclick="getDetalleTramaRpaCoti($(this))">'.$row->horario_2_ok.'</a></td>
                            <td><a data-fecha="'.$row->fecha.'" data-rango="2" data-estado="2" onclick="getDetalleTramaRpaCoti($(this))">'.$row->horario_2_no.'</a></td>  
                            <td>'.(($total_horario_2 > 0) ? number_format(((100*$row->horario_2_ok)/$total_horario_2),2) : 0).'%</td>                         		
                        </tr>
                        <tr>
                            <td></td>
                            <td>17:50:00 - 20:50:00</td>
                            <td>'.$total_horario_3.'</td>
                            <td><a data-fecha="'.$row->fecha.'" data-rango="3" data-estado="1" onclick="getDetalleTramaRpaCoti($(this))">'.$row->horario_3_ok.'</a></td>
                            <td><a data-fecha="'.$row->fecha.'" data-rango="3" data-estado="2" onclick="getDetalleTramaRpaCoti($(this))">'.$row->horario_3_no.'</a></td>   
                            <td>'.(($total_horario_3 > 0) ? number_format(((100*$row->horario_3_ok)/$total_horario_3),2) : 0).'%</td>                           		
                        </tr>
                        <tr style="font-weight: bolder;background-color: #f3f3f3;">
                            <td></td>
                            <td>Total</td>
                            <td>'.$total_dia.'</td>   
                            <td>'.$total_ok_3_dias.'</td>
                            <td>'.($row->horario_1_no + $row->horario_2_no + $row->horario_3_no).'</td>                            
                            <td>'.(($total_dia > 0) ? number_format(((100*$total_ok_3_dias)/$total_dia),2) : 0).'%</td>                           		
                        </tr>';
        }
            $html .='</tbody>
            </table>';
            return utf8_decode($html);
    }
    
    function getDetalleRpaCotizacion() {
	    $data['msj'] = '';
	    $data['error'] = EXIT_ERROR;
	    try {
	        $fecha             = $this->input->post('fecha');
	        $rango_horario     = $this->input->post('rango');
	        $exitoso           = $this->input->post('estado');
	        if($fecha == null || $rango_horario == null || $exitoso == null) {
	            throw new Exception("ND");
	        }	
	        $data['tablaDetalleRpaCoti'] = $this->tablaDestalleRpaCotizacion($fecha, $rango_horario, $exitoso);
	        $data['error'] = EXIT_SUCCESS;
	    } catch(Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
    }
    
    function tablaDestalleRpaCotizacion($fecha, $rango_horario, $exitoso) {
	    $dataTablaGestion = $this->m_control_tramas->getDetalleTramasRpaCotizacion($fecha, $rango_horario, $exitoso);
	    $html = '<table id="tbDetalleCotiRpa" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                	        <th>PTR</th>
                	        <th>SISEGO</th>
	                        <th>REALIZO LA COTIZACION</th>
                            <th>MENSAJE</th>
                            <th>FECHA ENVIO</th>
                        </tr>
                    </thead>
                    <tbody>';
	
	    foreach($dataTablaGestion as $row){
			$arraySisCoti = explode(',', $row->array_hizo_coti_sisego);
	        $html .='   <tr>
	                        <td >'.$row->ptr.'</td>
	                        <td >'.$arraySisCoti[1].'</td>
                            <td >'.$arraySisCoti[0].'</td>
                            <td >'.$row->descripcion.'</td>  
                            <td >'.$row->fecha_registro.'</td>
                        </tr>';
	    }
	    $html .='</tbody>
            </table>';
	    return utf8_decode($html);
	}
	
	
	function trablaTramasVr() {
  		$dataTablaGestion = $this->m_control_tramas->getVrRpa();
        $html = '
                <table id="tbDetalleSirope" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>Tipo Clasif.</th>
                            <th>Ingreso</th>
                            <th>Exitoso</th>    
                            <th>No Exitoso</th>
							<th>Eficiencia</th>
                        </tr>
                    </thead>                    
                    <tbody>';
        
        foreach($dataTablaGestion as $row){
            $total_horario_1 = ($row->fecha_ok + $row->fecha_no);
            $total_dia = $total_horario_1;

            $html .='   <tr>
                            <td>'.$row->fecha.'</td>
                            <td>'.$total_dia.'</td>
                            <td><a data-fecha="'.$row->fecha.'" data-rango="1" data-estado="1" onclick="getDetalleTramaRpaVr($(this))">'.$row->fecha_ok.'</a></td>
                            <td><a data-fecha="'.$row->fecha.'" data-rango="1" data-estado="2" onclick="getDetalleTramaRpaVr($(this))">'.$row->fecha_no.'</a></td>    
                            <td>'.(($total_horario_1 > 0) ? number_format(((100*$row->fecha_ok)/$total_horario_1),2) : 0).'%</td>                        		
                        </tr>';
        }
            $html .='</tbody>
            </table>';
            return utf8_decode($html);
    }
	
	function getDetalleTramaRpaVr() {
	    $data['msj'] = '';
	    $data['error'] = EXIT_ERROR;
	    try {
	        $fecha             = $this->input->post('fecha');
	        $rango_horario     = $this->input->post('rango');
	        $exitoso           = $this->input->post('estado');
	        if($fecha == null || $rango_horario == null || $exitoso == null) {
	            throw new Exception("ND");
	        }	
	   
	   
	        $data['tablaDetalleRpaVr'] = $this->getTablaDestalleRpaVr($fecha, $rango_horario, $exitoso);
	        
	        $data['error'] = EXIT_SUCCESS;
	    } catch(Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    echo json_encode(array_map('utf8_encode', $data));
	}
	
	
	function getTablaDestalleRpaVr($fecha, $rango_horario, $exitoso) {
	    $dataTablaVr = $this->m_control_tramas->getDetalleTramasRpaVr($fecha, $rango_horario, $exitoso);
	    
		$html = '<table id="tbDetalleSapRpa" class="table table-bordered">
					<thead class="thead-default">
						<tr>
						   <th>ITEMPLAN</th>
						   <th>CODIGO PO</th>
						   <th>MATERIAL</th>
						   <th>MENSAJE SAP</th>
						   <th>FECHA REGISTRO</th>
						</tr>
					</thead>
					<tbody>';
		
		foreach($dataTablaVr as $row) {
			// if(!$row['dataGet']) {
				// $objVr = null;
			// } else {
				// $dataGet = $row['dataGet'];
				// $objVr = json_decode($row['dataGet']);
			// }
			
			$html .='   <tr>
							<td >'.$row['itemplan'].'</td>
	                        <td >'.$row['ptr'].'</td>
	                        <td >'.$row['material'].'</td>
							<td >'.$row['mensaje'].'</td>
							<td >'.$row['fecha_registro'].'</td>
                        </tr>';
		}
			$html .='</tbody>
		</table>';
	    return utf8_decode($html);
	}
}