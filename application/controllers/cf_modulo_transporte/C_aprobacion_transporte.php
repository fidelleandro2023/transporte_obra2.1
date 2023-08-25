<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * @author CRISTOBAL ARTETA .
 * 18/01/2018
 *
 */
class C_aprobacion_transporte extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_transporte/m_aprobacion_transporte');
		$this->load->model('mf_cotizacion/m_validar_cotizacion');//czavala
        $this->load->model('mf_reportes_v/m_itemplan_ptr');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }
    
	public function index(){
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
    	       $data['listaEECC']    = $this->m_utils->getAllEECC();
        	   $data['listaZonal']   = $this->m_utils->getAllZonalGroup();
        	   $data['listaSubProy'] = $this->m_utils->getAllSubProyectoTrasporte();
               $data['listUsuarios'] = $this->m_utils->getUsuarioRegistroItemplanPIN();
               $data['tablaasigGrafoInterna'] = $this->makeHTLMTablaasignarGrafoInterna($this->m_aprobacion_transporte->getPtrToLiquidacion('','','','SI','','','',NULL,'01'));
               $data['title'] = 'BANDEJA DE APROBACION TRANSPORTE';
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
               $permisos =  $this->session->userdata('permisosArbol');
        	   #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_PLANTA_INTERNA, ID_PERMISO_HIJO_BANDEJA_APROBACION_PLANTA_INTERNA);
               $result = $this->lib_utils->getHTMLPermisos($permisos, 309, 312, 8);
               $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	         $this->load->view('vf_modulo_transporte/v_aprobacion_transporte',$data);
        	   }else{
        	       redirect('login','refresh');
        	   }
    	 }else{
        	 redirect('login','refresh');
	    }        
    }
    
    public function aprobarTransporte(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $ptr     = $this->input->post('id_ptr');
            $vale_re = $this->input->post('vale_reserva');
            $itemP   = $this->input->post('itemPl');
            $estado     = $this->input->post('estado');
            $tipo_po    = $this->input->post('tipo_po');
            $needValidation = $this->m_aprobacion_transporte->hasMatPinValidationByItemplanAprob($itemP);

			$idUsuario = $this->session->userdata('idPersonaSession');
			
			if($idUsuario == null || $idUsuario == '') {
				throw new Exception('Su session a caducado, refrescar la pagina por favor.');
			}
			
            if($tipo_po == 2){//tipo MO REALIZA TODO LO QUE HACIA 
                if ($estado == 1){//APROBADO
					$infoItemplan = $this->m_utils->getInfoItemplanWhitSubProyecto($itemP);//czavala
					if($infoItemplan['flg_opex']==2){//OPEX                  
                        $costoMo = $this->m_aprobacion_transporte->getCostoMoPin($ptr);                        
                        if($costoMo == null || $costoMo == '') {
                            throw new Exception('No se ingreso la cotizacio PIN.');
                        }
                        $counOpex = $this->m_validar_cotizacion->countOpex($infoItemplan['idSubProyecto']);
                        if ($counOpex > 0) {
                            $dataOpex = $this->m_validar_cotizacion->getOpex($infoItemplan['idSubProyecto'], $costoMo);
                            if (count($dataOpex) != 1) {
                                throw new Exception('Cuenta OPEX sin MONTO DISPONIBLE');
                            }
                        } else {
                            throw new Exception('No tiene cuenta OPEX registrada');
                        }        
                        
                        $dataPlanobra = array(
                                                "itemplan"                  => $itemP,
                                                "costo_unitario_mo"         => $costoMo,
                                                "costo_unitario_mo_crea_oc" => $costoMo
                                            );

                        $data = $this->m_aprobacion_transporte->updateDetalleProductoOPEX($ptr, $itemP, $vale_re, ESTADO_02_TEXTO, ESTADO_01_TEXTO, '2', FLG_APROBADO, $dataPlanobra, $dataOpex[0]->idOpex, $idUsuario, $costoMo);
                    }else{//CAPEX
						$monto_mo  = $this->m_aprobacion_transporte->getCostoMoPin($ptr);
                        $monto_mat = 0;
                        $itemplan = $itemP;
                        $infoPlan = $this->m_utils->getInfoItemplan($itemplan);
                        $listaPepNoPPT = array();
                        $hasSomePep = false;
                        $hasSomePepWiPresu = false;
                        $pep1 = null;
                        $monto_tmp_final = 0;
                        $itemPepGrafo = $this->m_validar_cotizacion->getPEPSITemplanPep2GrafoByItemplan($itemplan);
                        if (count($itemPepGrafo) > 0) {
                            foreach ($itemPepGrafo as $pep) {
                                if ($pep->monto_temporal >= $monto_mo) {
                                    $hasSomePepWiPresu = true;
                                    $pep1 = $pep->pep1;
                                    $monto_tmp_final = ($pep->monto_temporal - $monto_mo);
                                    break;
                                } else {
                                    array_push($listaPepNoPPT, $pep->pep1);
                                }
                            }
                            $hasSomePep = true;
                        }
                        
                        if (!$hasSomePepWiPresu) {
                            $itemBolsaPep = $this->m_validar_cotizacion->getPEPSBolsaPepByItemplan($itemplan);
                            if (count($itemBolsaPep) > 0) {
                                foreach ($itemBolsaPep as $pep) {
                                    if ($pep->monto_temporal >= $monto_mo) {
                                        $hasSomePepWiPresu = true;
                                        $pep1 = $pep->pep1;
                                        $monto_tmp_final = ($pep->monto_temporal - $monto_mo);
                                        break;
                                    } else {
                                        array_push($listaPepNoPPT, $pep->pep1);
                                    }
                                }
                                $hasSomePep = true;
                            }
                        }
                        
                        if (!$hasSomePep) {
                            throw new Exception('La obra no cuenta con PEP configurada.');
                        } else {
                            if ($hasSomePepWiPresu) {//se aprueba la cotizacion y generas oc y toda la vaina
                                $codigo_solicitud = $this->m_utils->getNextCodSolicitud();
                                if ($codigo_solicitud == null) {
                                    throw new Exception('Hubo problemas al obtener el codigo de solicitud OC, vuelva a intentarlo o genere un ticket CAP');
                                }
                        
                                $dataPlanobra = array(	"itemplan" 						=> $itemplan,
                                    "costo_unitario_mo" 			=> $monto_mo,
                                    "costo_unitario_mat" 			=> $monto_mat,
                                    "solicitud_oc" 					=> $codigo_solicitud,
                                    "estado_sol_oc" 				=> 'PENDIENTE',
                                    "costo_unitario_mo_crea_oc" 	=> $monto_mo,
                                    "costo_unitario_mat_crea_oc" 	=> $monto_mat,
                                    "fec_registro_sol_creacion_oc" 	=> $this->fechaActual()
                                );
                        
                                $solicitud_oc_creacion = array('codigo_solicitud' => $codigo_solicitud,
                                    'idEmpresaColab' => $infoPlan['idEmpresaColab'],
                                    'estado' => 1, //pendiente
                                    'fecha_creacion' => $this->fechaActual(),
                                    'idSubProyecto' => $infoPlan['idSubProyecto'],
                                    'plan' => 'COTIZACION PI',
                                    'pep1' => $pep1,
                                    'pep2' => $pep1 . '-001',
                                    'estatus_solicitud' => 'NUEVO',
                                    'tipo_solicitud' => 1//creacion
                                );
                        
                                $item_x_sol = array('itemplan' => $itemplan,
                                    'codigo_solicitud_oc' => $codigo_solicitud,
                                    'costo_unitario_mo' => $monto_mo
                                );
                        
                                $dataSapDetalle = array('monto_temporal' => $monto_tmp_final,
                                    'pep1' => $pep1
                                );
                        
                                $data = $this->m_aprobacion_transporte->aprobarCotizacion($dataPlanobra, $solicitud_oc_creacion, $item_x_sol, $dataSapDetalle);
                            } else {
                                $html = '';
                                foreach ($listaPepNoPPT as $pp) {
                                    $html .= '<a>' . $pp . ' : SIN PRESUPUESTO</a><br>';
                                }
                                throw new Exception($html);
                            }
                        }
                         //FIN CAMBIO CZAVALA 06-10-2020
					}
                    $arrayDataLog = array(
                        'tabla'            => 'Planta Interna',
                        'actividad'        => 'ptr Aprobada',
                        'itemplan'         => $itemP,
                        'ptr'              => $ptr,
                        'fecha_registro'   => $this->fechaActual(),
                        'id_usuario'       => $this->session->userdata('idPersonaSession'),
                        'tipoPlanta'       => 2
                     );
                    $this->m_utils->registrarLogPlanObra($arrayDataLog);
                }else{           //RECHAZADO                             
                    $data = $this->m_aprobacion_transporte->updateDetalleProducto($ptr, $itemP, $vale_re, ESTADO_01_TEXTO, ESTADO_01_TEXTO, '6', FLG_RECHAZADO);
                    if($needValidation  ==  0){//no necsita po mat
                        $data = $this->m_utils->updateEstadoPlanObra($itemP, ESTADO_PLAN_PRE_DISENO);    
                    }
                    $arrayDataLog = array(
                        'tabla'            => 'Planta Interna',
                        'actividad'        => 'ptr Rechazada',
                        'itemplan'         => $itemP,
                        'ptr'              => $ptr,
                        'fecha_registro'   => $this->fechaActual(),
                        'id_usuario'       => $this->session->userdata('idPersonaSession'),
                        'tipoPlanta'       => 2
                     );
                    $this->m_utils->registrarLogPlanObra($arrayDataLog);
                }
            }else if($tipo_po == 1){//tipo Material
                if ($estado == 1){//aprobar
                    $arrayUpdate = array(
                        "estado_po" => PO_PREAPROBADO
                    );
                    $data = $this->m_aprobacion_transporte->aprobOCanlPoMatPI($ptr, $itemP, $arrayUpdate, $this->fechaActual(), PO_PREAPROBADO);
                    if($data['error']==EXIT_ERROR){
                        throw new Exception('Ocurrio un error al pre aprobar la Po. comuniquese con Soporte.');
                    }
                }else{//RECHAZAR
                    $arrayUpdate = array(
                        "estado_po" => PO_CANCELADO
                    );
                    $data = $this->m_aprobacion_transporte->aprobOCanlPoMatPI($ptr, $itemP, $arrayUpdate, $this->fechaActual(), PO_CANCELADO);
                    if($data['error']==EXIT_ERROR){
                        throw new Exception('Ocurrio un error al cancelar la Po. comuniquese con Soporte.');
                    }
                }
            }

            $SubProy = $this->input->post('subProy');
            $eecc    = $this->input->post('eecc');
            $zonal   = $this->input->post('zonal');
         // $itemPlan = $this->input->post('item');
            $mesEjec = $this->input->post('mes');
            $area = $this->input->post('area');                                                                                        
            $data['tablaasigGrafoInterna'] = $this->makeHTLMTablaasignarGrafoInterna($this->m_aprobacion_transporte->getPtrToLiquidacion($SubProy,$eecc,$zonal,'SI',$mesEjec,$area,'',null, '01'));
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function makeHTLMTablaasignarGrafoInterna($listaPTR){
     
        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>Acción</th>
                            <th>PTR</th>
                            <th>Item Plan</th>                            
                            <th>Sub Proy</th>
                            <th>Zonal</th>
                            <th>EECC</th>
                            <th>DES. Area</th>
                            <th>Fec. Prevista</th>
                            <th>Valor MAT</th>
                            <th>Valor MO</th>
                            <th>TOTAL</th>
                            <th>Estado</th>                            
                        </tr>
                    </thead>                    
                    <tbody>';

                 foreach($listaPTR->result() as $row){
					if($row->orden_compra == NULL && $row->solicitud_oc == NULL) {
                        $btnCheck = '<a data-tipo="'.$row->tipo_po.'" data-ptr ="'.$row->ptr.'" data-itmpl ="'.$row->itemplan.'" onclick="addValeReserva(this)" title="aprobaci&oacute;n"><i style="color:black" class="zmdi zmdi-hc-2x zmdi-check-circle"></i></a>';
					} else {
						$btnCheck = 'PEND. CARGA OC';
					}
                    $btnConsulta = '<a data-tipo="'.$row->tipo_po.'" data-ptr ="'.$row->ptr.'" data-itemplan ="'.$row->itemplan.'" onclick="consultarCotizacionPtr($(this))" title="cotizaci&oacute;n"><i style="color:green" class="zmdi zmdi-hc-2x zmdi-money-box"></i></a>'; 
                    $html .=' <tr>
                                <td>'.$btnCheck.' '.$btnConsulta.'</td>
                                <td>'.$row->ptr.'</td>
                                <td>'.$row->itemplan.'</td>							
                                <td>'.$row->subProyectoDesc.'</td>
                                <td>'.$row->zonalDesc.'</td>
                                <th>'.$row->empresaColabDesc.'</th>
                                <th>'.$row->areaDesc.'</th>							
                                <th>'.$row->fechaPrevEjec.'</th>                              
                                <td>'.(($row->costo_mat!=null) ? number_format($row->costo_mat, 2, '.', ',') : '' ).'</td>
                                <td>'.(($row->costo_mo!=null) ? number_format($row->costo_mo, 2, '.', ',') : '' ).'</td>
                                <td>'.(($row->total!=null) ? number_format($row->total, 2, '.', ',') : '' ).'</td>
                                <td>'.$row->estado.'</td>
                            </tr>';
                 }
			 $html .='</tbody>
                </table>';
                    
        return utf8_decode($html);
    }

    function consultaCotiTransp() {
        $ptr      = $this->input->post('ptr');
        $itemplan = $this->input->post('itemplan');
        $tipo_po  = $this->input->post('tipo_po');
        $tabla = '';
        if($tipo_po ==  2){//DETALLE MO
            $tabla = $this->getTablaCotizacionPtr($ptr, $itemplan);            
        }else if($tipo_po ==  1){//DETALLE MATERIAL
            $tabla = $this->getDetalleMaterialesPTR($ptr);
        }
        $data['tablaCotizacion'] = $tabla;

        echo json_encode(array_map('utf8_encode', $data));
    }

    function getTablaCotizacionPtr($ptr, $itemplan) {
        $arrayData = $this->m_aprobacion_transporte->consultarCostoPtr($itemplan, $ptr);

        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr style="color: white ; background-color: #3b5998">
                            <th>Actividad</th>
                            <th>Precio</th>
                            <th>Baremo</th>                            
                            <th>Cantidad</th>
                            <th>Costo MO</th>
                            <th>Precio kit</th>
                            <th>Costo MAT</th>
                            <th>Total</th>                       
                        </tr>
                    </thead>                    
                    <tbody>';
        foreach($arrayData as $row) {
            $html.= '<tr>
                        <td>'.utf8_decode($row->descripcion).'</td>
                        <td>'.$row->precio.'</td>
                        <td>'.$row->baremo.'</td>
                        <td>'.$row->cantidad.'</td>
                        <td>'.$row->costo_mo.'</td>
                        <td>'.$row->costo_material.'</td>
                        <td>'.$row->costo_mat.'</td>
                        <td>'.$row->total.'</td>
                    </tr>';
        }
        $html .='   </tbody>
                </table>';
            
        return $html;
    }
    
    function getDetalleMaterialesPTR($ptr) {
        
        $ListaDetallePO = $this->m_aprobacion_transporte->getPPODetalle($ptr);        
        $htmlDetallePO = '<table id="data-table" class="table table-bordered">
                                        <thead class="thead-default">
                                            <tr>
                                                <th>MATERIAL</th>
                                                <th>DESCRIPCION</th>
                                                <th>UDM</th>
                                                <th>CANT. ING.</th>
                                            </tr>
                                        </thead>
                                        <tbody>';
        
        foreach ($ListaDetallePO as $row) {
            $htmlDetallePO .= ' <tr>
                                                <th>' . $row->codigo_material. '</th>
                                                <td>' . utf8_decode($row->descrip_material) . '</td>
                                                <td>' . $row->unidad_medida . '</td>
                                                <td>' . $row->cantidad_final . '</td>
                                            </tr>';
        }
        $htmlDetallePO .= '</tbody>
                        </table>';
        return $htmlDetallePO;
    }
    function filtrarTransporteAprob(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $SubProy   = $this->input->post('subProy');
            $eecc      = $this->input->post('eecc');
            $zonal     = $this->input->post('zonal');
            $itemPlan  = $this->input->post('item');
            $mesEjec   = $this->input->post('mes');
            $area      = $this->input->post('area');
            $estado    = $this->input->post('estado');
            $idUsuario = $this->input->post('idUsuario');
            $idUsuario = isset($idUsuario) ? $idUsuario : null;
            
            $data['tablaasigGrafoInterna'] = $this->makeHTLMTablaasignarGrafoInterna($this->m_aprobacion_transporte->getPtrToLiquidacion($SubProy,$eecc,$zonal,$itemPlan,$mesEjec,$area,$estado, $idUsuario, '01'));
            $data['error'] = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone','America/Lima');
        setlocale(LC_TIME, "es_ES","esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }
}