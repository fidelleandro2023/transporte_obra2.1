<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_bandeja_valida_po_pqt extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_pqt_terminado/m_bandeja_valida_po_pqt');
        $this->load->model('mf_pqt_terminado/m_pqt_terminado');
        $this->load->model('mf_pqt_liquidacion_mat/m_pqt_reg_mat_x_esta');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index(){
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
	        /*
               $data['listaZonal']    = $this->m_utils->getAllZonalGroup();
               $data['cmbJefatura']   = $this->m_utils->getJefaturaCmb();
        	   $data['listaSubProy']  = $this->m_utils->getAllSubProyecto();
        	   $data['listafase']     = $this->m_utils->getAllFase();
			   $data['listaMotivoObs']     = $this->m_utils->getMotivosObserValidados();*/
	           $data['listaEECC']     = $this->m_utils->getAllEECC();
               $data['tablaSiom']     = $this->getTablaPdtValidar($this->m_bandeja_valida_po_pqt->getBandejaValidacionPoPqt(null,null));               
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
               $permisos =  $this->session->userdata('permisosArbol');
               $result = $this->lib_utils->getHTMLPermisos($permisos, 250, 286, ID_MODULO_ADMINISTRATIVO);
               $data['opciones'] = $result['html'];
        	   /*if($result['hasPermiso'] == true){*/
        	         $this->load->view('vf_pqt_gestion_obra_terminado/v_bandeja_valida_po_pqt',$data);
        	  /* }else{
        	       redirect('login','refresh');
        	   }*/
    	 }else{
        	 redirect('login','refresh');
	    }
             
    }

    function getTablaPdtValidar($data) {
        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th style="width:10%"></th>
                            <th>Files</th>
                            <th>Proyecto</th> 
                            <th>SubProyecto</th>   
                            <th>Itemplan</th>                         
                            <th>Indicador</th>
                            <th>EECC</th>
                            <th>JEFATURA</th> 
                            <!--<th>Estacion</th>--> 
                            <th>Costo Total</th>
                            <!--<th>Costo Pdt Validar</th>
                            <th>Costo Total</th>-->
                            <th>Fecha Registro</th>  
                            <th>Usuario Registro</th>   
                            <th>Estado</th> 
                        </tr>
                    </thead>                    
                    <tbody>';
               if($data!=null){                                                                                      
                    foreach($data as $row){
                        $iconosValiaciones = '';
                        if($row->estado == 0){
                            $iconosValiaciones .= '<a data-idSol="'.$row->id_solicitud.'" data-item="'.$row->itemplan.'" data-esta="'.$row->idEstacion.'" onclick="viewDetallePartidas(this)"><i title="Editar" class="zmdi zmdi-hc-2x zmdi-eye" style="color: green;"></i></a>';
                        }else if($row->estado == 1){
                            $iconosValiaciones .= '<a data-idSol="'.$row->id_solicitud.'" data-item="'.$row->itemplan.'" data-esta="'.$row->idEstacion.'" onclick="viewDetallePartidas(this)"><i title="Editar" class="zmdi zmdi-hc-2x zmdi-eye" style="color: green;"></i></a>';  
                            $iconosValiaciones .= '<a data-idSol="'.$row->id_solicitud.'" data-item="'.$row->itemplan.'" data-esta="'.$row->idEstacion.'" onclick="getPTRSByItemplan(this)"><i title="Editar" class="zmdi zmdi-hc-2x zmdi-assignment-o" style="color: green;"></i></a>'; 
                        }else{
                            $iconosValiaciones .= '<a data-idSol="'.$row->id_solicitud.'" data-item="'.$row->itemplan.'" data-esta="'.$row->idEstacion.'" onclick="viewDetallePartidas(this)"><i title="Editar" class="zmdi zmdi-hc-2x zmdi-eye" style="color: green;"></i></a>';                            
                        }
                        $html .=' <tr>     
                                    <th style="width:7%">
                                        '.$iconosValiaciones.'
                                    </th>
                                    <td>
                                        <div>
                                            <a style="color: #005C84" onclick=cotizacion("' . $row->itemplan . '")>Cotiza&nbsp;&nbsp</a>
                                            <a style="color: #E51318" onclick=disenho("' . $row->itemplan . '")>Expe_Dise&nbsp;&nbsp</a>
                                            <a style="color: #954b97" onclick=licencias("' . $row->itemplan . '")>Licenc&nbsp;&nbsp</a>
                                            <a style="color: #5bc500" onclick=liquidacion("' . $row->itemplan . '")>Liquid&nbsp;&nbsp</a>
                                            <a style="color: #121311" onclick=expedienteLiqui("' . $row->itemplan . '","'.$row->idEstacion.'")>Expe_Liqui&nbsp;&nbsp</a>    
                                        </div>    
                                    </td>
                                    <td>'.$row->proyectoDesc.'</td>         
                                    <td>'.$row->subProyectoDesc.'</td>
                                    <td>'.$row->itemplan.'</td>
                                    <td>'.$row->indicador.'</td>
                                    <td>'.$row->empresaColabDesc.'</td>
                                    <td>'.$row->jefatura.'</td>
                                    <!--<td>estacionDesc</td>-->
                                    <td>'.$row->costo_inicial_form.'</td>
                                    <!--<td>'.$row->costo_adicional_form.'</td>
                                    <td>'.$row->costo_total_form.'</td>-->
                                    <td>'.$row->fec_registro.'</td>
                                    <td>'.$row->nombreCompleto.'</td>     
                                    <td>'.$row->situacion.'</td>
                                </tr>';
                    }
               }
            $html .='</tbody>
                </table>';
                    
            return $html;
    }

    function filtraTabla() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            $idEmpresaColab = ($this->input->post('eecc')=='') ? null : $this->input->post('eecc');
            $itemplan = ($this->input->post('itemplan')=='') ? null : $this->input->post('itemplan');
            $data['tablaBandeja'] = $this->getTablaPdtValidar($this->m_bandeja_valida_po_pqt->getBandejaValidacionPoPqt($itemplan, $idEmpresaColab));
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e) {
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
    
    function getPartidasPdtValidacion() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            if($this->session->userdata('idPersonaSession') != null){
                $itemplan       = ($this->input->post('itemplan')=='')      ? null : $this->input->post('itemplan');
                $idEstacion     = ($this->input->post('idEstacion')=='')    ? null : $this->input->post('idEstacion');
				$idSolicitud    = ($this->input->post('idSol')=='')         ? null : $this->input->post('idSol');
                $data['tablaPdt'] = $this->getContModal($itemplan, $idEstacion, $idSolicitud);
                $data['error'] = EXIT_SUCCESS;
            }else{
                throw new Exception('La session expiro, vuelva a iniciar Sesion.');
            }
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    
    function getContModal($itemplan, $idEstacion, $idSolicitud) {
		$infoFullItemplan = $this->m_utils->getInfoItemplan($itemplan);
        $infoItm = $this->m_pqt_terminado->getInfoBasicToGeneratePartidasByItemplan($itemplan);
        if($infoItm==null){
            $html = '<h4 class="text-center" style="color:red">Excepcion detectada, comuniquese con soporte.</h4>';
        }else{
            if(in_array($infoFullItemplan['idSubProyecto'], array(155, 279, 283, 553, 554, 579, 582))){//si es ruta
    
                $estaAnclas = $this->m_pqt_terminado->getEstacionesAnclasRutas($itemplan);
                $html = '<div class="tab-container">
                        <ul class="nav nav-tabs nav-fill" role="tablist">';
                $active = 'active';
                foreach ($estaAnclas as $row){
                    if($row->idEstacion == $idEstacion){
                        $html .= '<li class="nav-item">
                                    <a class="nav-link '.$active.'" data-toggle="tab" href="#tab'.$row->idEstacion.'" role="tab">'.$row->estacionDesc.'</a>
                                  </li>';
                        $active = '';
                    }
                }
                $html .= '  </ul>
                        <div class="tab-content" style="margin-left: 20px;margin-right: 20px;">';
                $active = 'active';
                foreach ($estaAnclas as $row){
                    if($row->idEstacion == $idEstacion){
                        //$havePdt = $this->m_pqt_terminado->haveSolPdtValidacion($itemplan, $row->idEstacion);
                        $solicitudPartAdic = $this->m_pqt_terminado->getSolicitudPartidasAdicionales($itemplan, $idEstacion);
                        $html .= '<div class="tab-pane '.$active.' fade show" id="tab'.$row->idEstacion.'" role="tabpanel">';
                    
                        $listaPoFull = $this->m_pqt_terminado->getAllPoMoByItemplan($itemplan, $row->idEstacion);
                        $has_pos_no_aceptados = 0;
                        $costo_final_obra = 0;
                        $has_po = 0;
                        $html .= '<div style="margin-bottom: 5%;margin-top: 5%;margin-left: 5%;margin-right: 5%;">
                        <p style="font-size: larger;color: #007bff;text-align: left;font-weight: bold;">PO MO En la Obra</p>
                        <table class="table table-bordered">
                                        <thead class="thead-default">
                                            <tr>
                                                <th>AREA</th>
                                                <th>TIPO</th>
                                                <th>CODIGO PO</th>
                                                <th>ESTADO PO</th>
                                                <th>COSTO TOTAL</th>
                                           <tr></thead>
                            <tbody>';
                        foreach ($listaPoFull as $row2){
                            $html .= '<tr>
                                        <th>'.$row2->estacionDesc.'</th>
                                        <th>'.$row2->tipoPo.'</th>
                                        <th>'.$row2->codigo_po.'</th>
                                        <th>'.$row2->estado.'</th>
                                        <th>'.number_format($row2->costo_total, 2).'</th>
            		                  </tr>';
                            $estados_no_acepted = array(PO_REGISTRADO,PO_PREAPROBADO,PO_APROBADO,PO_PRECANCELADO);
                            if(in_array($row2->estado_po, $estados_no_acepted)){
                                $has_pos_no_aceptados++;
                            }else{
                                $costo_final_obra = $costo_final_obra+$row2->costo_total;
                                $has_po ++;
                            }
                        }
                        $html .= '   </tbody>
                              </table>';
                        
                        $hasSolActivo = $this->m_utils->hasSolExceActivo($itemplan, TIPO_PO_MANO_OBRA);                    
                        if($hasSolActivo > 0){
                            $html .= '<h4 class="text-center" style="color:red">Obra con Solicitud de Exceso PDT de aprobacion.</h4>';
                        }else if($has_po==0){
                            $html .= '<h4 class="text-center" style="color:red">La estacion no cuenta con PO Trabajadas.</h4>';
                        }else if($has_pos_no_aceptados > 0){
                            $html .= '<h4 class="text-center" style="color:red">Las PO de MO deben estar Liquidadas.</h4>';
                        }else{
                            if($solicitudPartAdic['estado']==0){//pdt validar nivel 1
                            $infoExpediente = $this->m_bandeja_valida_po_pqt->getInfoExpedienteLiquidacion($itemplan, $idEstacion);
                            $html .= '<div class="row">';
                                        if($infoExpediente['path_expediente']!=null){
                            $html .= '<div class="col-sm-4 col-md-4" style="TEXT-ALIGN: CENTER;">
                                           <a href="'.$infoExpediente['path_expediente'].'" download>DESCARGAR EXPEDIENTE <i class="zmdi zmdi-hc-1x zmdi-download"></i></a>
                                        </div>';
                                        }
                            $html .= '      <div class="col-sm-4 col-md-4" style="TEXT-ALIGN: CENTER;">
                                                <button style="background-color: #008000;" data-idSol="'.$solicitudPartAdic['id_solicitud'].'" data-idEs="'.$row->idEstacion.'" data-item="'.$itemplan.'" class="btn btn-success valNi1" type="button">APROBAR PROPUESTA</button>
                                            </div>
                                            <div class="col-sm-4 col-md-4" style="TEXT-ALIGN: CENTER;">
                                                <button style="background-color: red;" data-from="1" data-idSol="'.$solicitudPartAdic['id_solicitud'].'" data-idEs="'.$row->idEstacion.'" data-item="'.$itemplan.'" class="btn btn-success rejectSol" type="button">RECHAZAR</button>
                                            </div>
                                      </div>';
                            }else if($solicitudPartAdic['estado']==1){//pdt validar nivel 2
                                $infoExpediente = $this->m_bandeja_valida_po_pqt->getInfoExpedienteLiquidacion($itemplan, $idEstacion);
                                $html .= '<div class="row">';
                                if($infoExpediente['path_expediente']!=null){
                                    $html .= '<div class="col-sm-4 col-md-4" style="TEXT-ALIGN: CENTER;">
                                           <a href="'.$infoExpediente['path_expediente'].'" download>DESCARGAR EXPEDIENTE <i class="zmdi zmdi-hc-1x zmdi-download"></i></a>
                                        </div>';
                                }
                                $html .= '      <div class="col-sm-4 col-md-4" style="TEXT-ALIGN: CENTER;">
                                                <button style="background-color: #008000;" data-idSol="'.$solicitudPartAdic['id_solicitud'].'" data-idEs="'.$row->idEstacion.'" data-item="'.$itemplan.'" class="btn btn-success valNi2Ruta" type="button">APROBAR PROPUESTA</button>
                                            </div>
                                            <div class="col-sm-4 col-md-4" style="TEXT-ALIGN: CENTER;">
                                                <button style="background-color: red;" data-from="1" data-idSol="'.$solicitudPartAdic['id_solicitud'].'" data-idEs="'.$row->idEstacion.'" data-item="'.$itemplan.'" class="btn btn-success rejectSol" type="button">RECHAZAR</button>
                                            </div>
                                      </div>';
                            }
                        }
                        $html .= '</div></div>';
                        $active = '';
                    }
                }
                $html .='</div></div>';
                
            }else if ($infoFullItemplan['paquetizado_fg']   ==  1){
              
                        $solicitudPartAdic = $this->m_pqt_terminado->getSolicitudPartidasAdicionalesByItemplanSolo($idSolicitud);
                        $listaPoFull = $this->m_pqt_terminado->getAllPoMoBySoloItemplan($itemplan);
                        $has_pos_no_aceptados = 0;
                        $costo_final_obra = 0;
                        $has_po = 0;
                        $html = '<div style="margin-bottom: 5%;margin-top: 5%;margin-left: 5%;margin-right: 5%;">
                        <p style="font-size: larger;color: #007bff;text-align: left;font-weight: bold;">PO MO En la Obra</p>
                        <table class="table table-bordered">
                                        <thead class="thead-default">
                                            <tr>
                                                <th>AREA</th>
                                                <th>TIPO</th>
                                                <th>CODIGO PO</th>
                                                <th>ESTADO PO</th>
                                                <th>COSTO TOTAL</th>
                                           <tr></thead>
                            <tbody>';
                        foreach ($listaPoFull as $row2){
                            $html .= '<tr>
                                        <th>'.$row2->estacionDesc.'</th>
                                        <th>'.$row2->tipoPo.'</th>
                                        <th>'.$row2->codigo_po.'</th>
                                        <th>'.$row2->estado.'</th>
                                        <th>'.number_format($row2->costo_total, 2).'</th>
            		                  </tr>';
                            $estados_no_acepted = array(PO_REGISTRADO,PO_PREAPROBADO,PO_APROBADO,PO_PRECANCELADO);
                            if(in_array($row2->estado_po, $estados_no_acepted)){
                                $has_pos_no_aceptados++;
                            }else{
                                $costo_final_obra = $costo_final_obra+$row2->costo_total;
                                $has_po ++;
                            }
                        }
                        $html .= '   </tbody>
                              </table>';
                
                        $hasSolActivo = $this->m_utils->hasSolExceActivo($itemplan, TIPO_PO_MANO_OBRA);
                        if($hasSolActivo > 0){
                            $html .= '<h4 class="text-center" style="color:red">Obra con Solicitud de Exceso PDT de aprobacion.</h4>';
                        }else if($has_po==0){
                            $html .= '<h4 class="text-center" style="color:red">La estacion no cuenta con PO Trabajadas.</h4>';
                        }else if($has_pos_no_aceptados > 0){
                            $html .= '<h4 class="text-center" style="color:red">Las PO de MO deben estar Liquidadas.</h4>';
                        }else{
                            if($solicitudPartAdic['estado']==0){//pdt validar nivel 1
                                $infoExpediente = $this->m_bandeja_valida_po_pqt->getInfoExpedienteLiquidacionNoPqtByItem($itemplan);
                                $html .= '<div class="row">';
                                if($infoExpediente['path_expediente']!=null){
                                    $html .= '<div class="col-sm-4 col-md-4" style="TEXT-ALIGN: CENTER;">
                                           <a href="'.$infoExpediente['path_expediente'].'" download>DESCARGAR EXPEDIENTE <i class="zmdi zmdi-hc-1x zmdi-download"></i></a>
                                        </div>';
                                }
                                $html .= '  <div class="col-sm-4 col-md-4" style="TEXT-ALIGN: CENTER;">
                                                <button style="background-color: #008000;" data-idSol="'.$solicitudPartAdic['id_solicitud'].'" data-item="'.$itemplan.'" class="btn btn-success valNi1" type="button">APROBAR PROPUESTA</button>
                                            </div>
                                            <div class="col-sm-4 col-md-4" style="TEXT-ALIGN: CENTER;">
                                                <button style="background-color: red;" data-from="1" data-idSol="'.$solicitudPartAdic['id_solicitud'].'" data-item="'.$itemplan.'" class="btn btn-success rejectSol" type="button">RECHAZAR</button>
                                            </div>
                                      </div>';
                            }else if($solicitudPartAdic['estado']==1){//pdt validar nivel 2
                                $infoExpediente = $this->m_bandeja_valida_po_pqt->getInfoExpedienteLiquidacionNoPqtByItem($itemplan);
                                $html .= '<div class="row">';
                                if($infoExpediente['path_expediente']!=null){
                                    $html .= '<div class="col-sm-4 col-md-4" style="TEXT-ALIGN: CENTER;">
                                           <a href="'.$infoExpediente['path_expediente'].'" download>DESCARGAR EXPEDIENTE <i class="zmdi zmdi-hc-1x zmdi-download"></i></a>
                                        </div>';
                                }
                                $html .= '      <div class="col-sm-4 col-md-4" style="TEXT-ALIGN: CENTER;">
                                                <button style="background-color: #008000;" data-idSol="'.$solicitudPartAdic['id_solicitud'].'" data-item="'.$itemplan.'" class="btn btn-success valNi2NoPqt" type="button">APROBAR PROPUESTA</button>
                                            </div>
                                            <div class="col-sm-4 col-md-4" style="TEXT-ALIGN: CENTER;">
                                                <button style="background-color: red;" data-from="1" data-idSol="'.$solicitudPartAdic['id_solicitud'].'" data-item="'.$itemplan.'" class="btn btn-success rejectSolNoPqt" type="button">RECHAZAR</button>
                                            </div>
                                      </div>';
                            }
                        }
                        $html .= '</div></div>';
                           
                $html .='</div></div>';
                
            }else{
				/***/
				$solicitudPartAdic = $this->m_pqt_terminado->getSolicitudPartidasAdicionales($itemplan, $idEstacion);
				/***/
				$estaAnclas = $this->m_utils->getInfoEstacionByIdEstacion($idEstacion);
				$html = '<div class="tab-container">
							<ul class="nav nav-tabs nav-fill" role="tablist">';
				$active = 'active';
				foreach ($estaAnclas->result() as $row){
					$html .= '<li class="nav-item">
												<a class="nav-link '.$active.'" data-toggle="tab" href="#tab'.$row->idEstacion.'" role="tab">'.$row->estacionDesc.'</a>
											  </li>';
					$active = '';
				}
				$html .= '  </ul>
							<div class="tab-content" style="margin-left: 20px;margin-right: 20px;">';
				$active = 'active';
				foreach ($estaAnclas->result() as $row){
					$html .= '<div class="tab-pane '.$active.' fade show" id="tab'.$row->idEstacion.'" role="tabpanel">
										<p style="font-size: larger;color: #007bff;text-align: left;font-weight: bold;">Partidas Paquetizadas</p>
										'.$this->getPartidasPaquetizadas($row->idEstacion, $infoItm['idSubProyecto'], $infoItm['idEmpresaColab'], $infoItm['isLima'], $itemplan);
					//INVOCAR AL ESTADO DE LA SOLICITUD...
					$poPndtValidacion = false;
					$canCreateMarteriales = false;
					$poValidada = false;
					$botenes = '<div class="row">';
					if($solicitudPartAdic!=null){
						$infoExpediente = $this->m_bandeja_valida_po_pqt->getInfoExpedienteLiquidacion($itemplan, $idEstacion);
						$expediente = '';
						if($infoExpediente['path_expediente']!=null){
							$expediente .= '<div class="col-sm-4 col-md-4" style="TEXT-ALIGN: CENTER;">
										   <a href="'.$infoExpediente['path_expediente'].'" download>DESCARGAR EXPEDIENTE <i class="zmdi zmdi-hc-1x zmdi-download"></i></a>
										</div>';
						}
						if($solicitudPartAdic['estado']==0){
							$hasSolActivo = $this->m_utils->hasSolExceActivo($itemplan, TIPO_PO_MANO_OBRA);
							if($hasSolActivo > 0){
								$botenes = '<h4 class="text-center" style="color:red">Obra con Solicitud de Exceso PDT de aprobacion.</h4>';                                               
							}  else {  
								$botenes .= $expediente.'<div class="col-sm-4 col-md-4" style="TEXT-ALIGN: CENTER;">
											<button style="background-color: #008000;" data-idSol="'.$solicitudPartAdic['id_solicitud'].'" data-idEs="'.$row->idEstacion.'" data-item="'.$itemplan.'" class="btn btn-success valNi1" type="button">APROBAR PROPUESTA</button>
										</div>
										<div class="col-sm-4 col-md-4" style="TEXT-ALIGN: CENTER;">
											<button style="background-color: red;" data-from="1" data-idSol="'.$solicitudPartAdic['id_solicitud'].'" data-idEs="'.$row->idEstacion.'" data-item="'.$itemplan.'" class="btn btn-success rejectSol" type="button">RECHAZAR</button>
										</div>';
							}
						}else if($solicitudPartAdic['estado']==1){
							$hasSolActivo = $this->m_utils->hasSolExceActivo($itemplan, TIPO_PO_MANO_OBRA);
							if($hasSolActivo > 0){
								$botenes = '<h4 class="text-center" style="color:red">Obra con Solicitud de Exceso PDT de aprobacion.</h4>';
												   
							}  else {  
								$botenes .= $expediente.'<div class="col-sm-8 col-md-8" style="TEXT-ALIGN: CENTER;">
												<button style="background-color: #008000;" data-idSol="'.$solicitudPartAdic['id_solicitud'].'" data-idEs="'.$row->idEstacion.'" data-item="'.$itemplan.'" class="btn btn-success gpoMo" type="button">APROBAR PROPUESTA</button>
										  </div>
										  <!--<div class="col-sm-6 col-md-6" style="TEXT-ALIGN: CENTER;">
												<button style="background-color: red;" data-from="2" data-idSol="'.$solicitudPartAdic['id_solicitud'].'" data-idEs="'.$row->idEstacion.'" data-item="'.$itemplan.'" class="btn btn-success rejectSol" type="button">RECHAZAR</button>
										  </div>-->';
							}
						}
						
					}
					$botenes .= '</div>';
					
					/**********inicio******************/
					$idEstacionOC = 0;
					if($row->idEstacion==ID_ESTACION_COAXIAL){
						$listaPoOC = $this->m_pqt_terminado->getPoOCByItemplanPqtV2Coax($itemplan);//obtenemos po de la obra civil
					}else if($row->idEstacion==ID_ESTACION_FO){
						$listaPoOC = $this->m_pqt_terminado->getPoOCByItemplanPqtV2FO($itemplan);//obtenemos po de la obra civil
					}
					
					$contenidoOC = '';
					if(count($listaPoOC)>0){
						$has_pos_no_aceptados = 0;
					
						$contenidoOC .= '<p style="font-size: larger;color: #007bff;text-align: left;font-weight: bold;">PO DE FIBRA</p>';
						$contenidoOC .= '<table class="table table-bordered">
										<thead class="thead-default">
											<tr>
												<th>ESTACION</th>
												<th>TIPO</th>
												<th>CODIGO PO</th>
												<th>ESTADO PO</th>
												<th>COSTO TOTAL</th>
										   <tr></thead>
							<tbody>';
						foreach ($listaPoOC as $row2){
							$contenidoOC .=    '<tr>
											<th>'.$row2->estacionDesc.'</th>
											<th>'.$row2->tipoPo.'</th>
											<th>'.$row2->codigo_po.'</th>
											<th>'.$row2->estado.'</th>
											<th>'.number_format($row2->costo_total, 2).'</th>
										</tr>';
							$estados_no_acepted = array(PO_REGISTRADO,PO_PREAPROBADO,PO_APROBADO,PO_PRECANCELADO);
							if(in_array($row2->estado_po, $estados_no_acepted)){
								$has_pos_no_aceptados++;
							}
						}
						$contenidoOC .= '   </tbody>
							  </table>';                
					}
					/**********validacion de la po deOC****/
					
					$html .= '<p style="font-size: larger;color: #007bff;text-align: left;font-weight: bold;">Partidas Adicionales</p>
										'.$this->getPartidasAdicionales($itemplan, $row->idEstacion, $poValidada, $poPndtValidacion, $canCreateMarteriales).'
									</div>';
					
					$html .= $botenes;
					$html .= $contenidoOC;
					$infoCertiEdicionOC = $this->m_pqt_terminado->getDataToSolicitudEdicionCertiOC($itemplan, $idEstacion);                
					$html.= '<div class="row">
								<div class="col-sm-6 col-md-6">
									<h4 class="text-center" style="color:green">Presupuesto Actual MO: S./'.number_format($infoCertiEdicionOC['costo_unitario_mo']).'</h4>
								</div>
								<div class="col-sm-6 col-md-6">
									<h4 class="text-center" style="color:green">Nuevo Costo MO: S./' .number_format($infoCertiEdicionOC['total']).'</h4>
								</div>
							 </div>';
					$active = '';
				}
			
				$html .= ' </div>
						 </div>';
			}
        }
        return $html;
    }
    
    function getPartidasAdicionales($itemplan, $idEstacion, $poValidada, $poPndtValidacion, $canCreateMarteriales){
       $totalPartAdic = 0;
        $totalPartAdicNoVali = 0;
        $codigoFerreteria = '69901-2';#ponerlo como constante   
        $precioTmp = 0;
        $canCrateRegMat = false;
        $infoFerreteria =  $this->m_pqt_terminado->getInfoPartidaByCodPartida($itemplan, $idEstacion, $codigoFerreteria);
        if($infoFerreteria != null){
            $precioTmp = $infoFerreteria['costo'];
        }
        $dataFerreteria = $this->m_pqt_terminado->getDataPartidaByCodigo($codigoFerreteria);
        
        /***/
        $solicitudPartAdic = $this->m_pqt_terminado->getSolicitudPartidasAdicionales($itemplan, $idEstacion);
        /***/
        $html = '<table class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th></th>
                            <th>Codigo</th>
                            <th>Descripcion</th>                            
                            <th>Baremo</th> 
                            <th>Cantidad</th>    
                            <th>Precio</th>
                            <th>Total</th>';
        
        if($solicitudPartAdic!=null){
            $html .='<th>1ra Val</th>
                     <th>2da Val</th>';
        }else{
             $html .= '<th></th>';
        }
                      
        $html .= '</tr></thead>                   
                    <tbody>';
        #info partida Ferreteria
        $showFerreteria = false;
        $ferreteriaInfo = $this->m_pqt_terminado->getGroupFerreteria($itemplan, $idEstacion);
        if($solicitudPartAdic!=null){
            if($ferreteriaInfo != null){//si tiene solicitud pendiente y ferreteria regitrada mostrar
                $showFerreteria = true;
            }
        }else if($solicitudPartAdic==null){
            $showFerreteria = true;
        }
        
        if($showFerreteria){
                $cantidadFerr = 0;
                $totalFerr = 0;
                $pathEvidencia = null;
                $textoIncono = 'No Cuenta con registro de Materiales Paquetizados.';
                $iconEstado = 2;#no hay dato
                if($ferreteriaInfo != null){
                    $totalFerr = $ferreteriaInfo['costo_total_final'];            
                    $cantidadFerr = number_format(($totalFerr/$precioTmp),2,'.', ',');
                    $pathEvidencia = $ferreteriaInfo['path_evidencia'];
                    if($ferreteriaInfo['estado']==1){#VALIDADO
                        $iconEstado = 1;
                    }else if($ferreteriaInfo['estado']==0){//PDT VALIDACION
                        $textoIncono = 'Partida Pendiente de Envio de Validacion.'; 
                    }else if($ferreteriaInfo['estado']==2){//PDT RECHAZADO
                        $textoIncono = 'Partida Rechazada.';
                    }else if($ferreteriaInfo['estado']==3){//PDT RECHAZADO
                        $textoIncono = 'Partida Pendiente de Validacion.';
                    }
                }
                
                $infoItemEsta = $this->m_pqt_reg_mat_x_esta->getInfoEstacionItemplanToRegMatXEsta($itemplan, $idEstacion);      
                $html .=    '<tr>
                                <th>
                                    '.(($pathEvidencia != null) ? '<a href="'.$pathEvidencia.'" download=""><i class="zmdi zmdi-hc-2x zmdi-download"></i></a>
                                                                   <a class="getMateEsta" data-esta="'.$idEstacion.'" data-item="'.$itemplan.'"><i title="buscar" style="color:#A4A4A4" class="zmdi zmdi-hc-2x zmdi-eye"></i></a>' : '').'
                                    '.(($infoItemEsta['id'] == null && $canCreateMarteriales) ? '<a onclick="cargarRegMateriales(\''.$itemplan.'\','.$idEstacion.')"><i title="Registrar Materiales Paquetizados" class="zmdi zmdi-hc-2x zmdi-edit"></i></a>' : '').'
                                </th>
                                <th>'.$dataFerreteria['codigo'].'</th> 
                                <th>'.utf8_decode($dataFerreteria['descripcion']).'</th>
                                <th>'.$dataFerreteria['baremo'].'</th> 
                                <th>'.$cantidadFerr.'</th> 
                                <th>'.$precioTmp.'</th> 
                                <th>'.number_format($totalFerr,2).'</th>';                       
                if($solicitudPartAdic!=null){
                    $html .='<th>'.(($solicitudPartAdic['estado']==1 || $solicitudPartAdic['estado']==2) ? $this->getIconPartidaPqt(1, null) : $this->getIconPartidaPqt(2, 'Pdt 1ra Validacion')).'</th>
                             <th>'.(($solicitudPartAdic['estado']==2) ? $this->getIconPartidaPqt(1, null) : $this->getIconPartidaPqt(2, 'Pdt 2da Validacion')).'</th>';
                    if($solicitudPartAdic['estado']==2){
                        $totalPartAdic = $totalPartAdic + $totalFerr;
                    }
                }else{
                   $html .=    '<th>'.$this->getIconPartidaPqt($iconEstado, $textoIncono).'</th>';
                }
                $html .=    '</tr>'; 
                $totalPartAdicNoVali = $totalPartAdicNoVali + $totalFerr;
        }
        #termino ferreteria#
        /**iniciamos partidas tmp**/        
        $partidasAdicionales = $this->m_pqt_terminado->getPartidasAdicionalesTmpByItemplanEstacion($itemplan, $idEstacion);
        foreach ($partidasAdicionales as $row){
            $iconEstado = 2;#no hay dato
            if($poPndtValidacion){
                $textoIncono = 'Partida Pendiente de Envio de Validacion.';                
            }else if($row->estado ==  1){#VALIDADO
                $iconEstado = 1;
            }else if($row->estado   ==  0){//PDT VALIDACION
                $textoIncono = 'Partida Pendiente de Validacion.'; 
            }else if($row->estado   ==  2){//PDT RECHAZADO
                $textoIncono = 'Partida Rechazada.';
            }      
            $html .= '<tr>
                        <th></th>
                        <th>'.$row->codigo.'</th>
                        <th>'.$row->descripcion.'</th>
                        <th>'.$row->baremo.'</th>
                        <th>'.$row->cantidad.'</th>
                        <th>'.$row->costo.'</th>
                        <th>'.$row->total_form.'</th>';                        
                if($solicitudPartAdic!=null){
                $html .='<th>'.(($solicitudPartAdic['estado']==1 || $solicitudPartAdic['estado']==2) ? $this->getIconPartidaPqt(1, null) : $this->getIconPartidaPqt(2, 'Pdt 1ra Validacion')).'</th>
                         <th>'.(($solicitudPartAdic['estado']==2) ? $this->getIconPartidaPqt(1, null) : $this->getIconPartidaPqt(2, 'Pdt 2da Validacion')).'</th>';
                    if($solicitudPartAdic['estado']==2){
                        $totalPartAdic = $totalPartAdic + $row->total;
                    }
                }else{
            $html .= '<th>'.$this->getIconPartidaPqt($iconEstado, $textoIncono).'</th>';
                        }
            $html .= '</tr>';
           
            $totalPartAdicNoVali = $totalPartAdicNoVali + $row->total;
        }
        
        $html .= '<tr style="color: white;background: var(--verde_telefonica);">
                        <th></th>
                        <th style="font-weight: bolder;">TOTAL</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th style="font-weight: bolder;">'.number_format($totalPartAdicNoVali,2).'</th>
                        <th><p data-cost="'.$totalPartAdicNoVali.'" id="tot_ad_'.$idEstacion.'" ></p></th>';
        if($solicitudPartAdic!=null){
            $html .=' <th></th>';
        }
                        
		  $html .='</tr>        
                    </tbody>
                </table>';
        return $html;
    }
    
    function getPartidasPaquetizadas($idEstacion, $idSubProyecto, $idEmpresaColab, $isLima, $itemplan){
        $listaPartidasPqt = $this->m_pqt_terminado->getPartidasPaquetizadasByItemEccJefaTuraEstacion($idEstacion, $idSubProyecto, $idEmpresaColab, $isLima, $itemplan);
        $mensaje_texto = '';
        $total_pqt = 0;
        $html = '<table class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>Tipo</th>
                            <th>Descripcion</th>
                            <th>Baremo</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>';
        foreach ($listaPartidasPqt as $row){
            $estadoIcono = 2;#NO OK
            if($row->id_tipo_partida == 1 ||$row->id_tipo_partida == 5){#DISEÑO FO y COAXIAL
                $mensaje_texto = 'Diseño No Ejecutado.';
                $datoLiquidacionDiseno = $this->m_pqt_terminado->haveLiquidacionDiseno($itemplan, $idEstacion);
                //$para todos las anclas czavala
                $hasPoDisenoActivo = $this->m_pqt_terminado->hasPoDisenoActivo($itemplan);
                if($hasPoDisenoActivo>0){
                    $mensaje_texto = 'Ya cuenta con PO en Diseño.';
                }else if($datoLiquidacionDiseno!=NULL){
                    if($datoLiquidacionDiseno['fecha_ejecucion']!=NULL){
                        $estadoIcono = 1;
                        $mensaje_texto = null;
                    }
                }
            }else if($row->id_tipo_partida == 2 ||$row->id_tipo_partida == 6){#LICENCIA FO y COAXIAL
                $mensaje_texto = 'Licencia no concluida.';
                $datoLiquidacionDiseno = $this->m_pqt_terminado->haveLiquidacionDiseno($itemplan, $idEstacion);
                IF($datoLiquidacionDiseno!=NULL){
                    if($datoLiquidacionDiseno['requiere_licencia']!=2){
                        $hasPoLicenciaActivo = $this->m_pqt_terminado->hasPoLicenciaActivo($itemplan);
                        if($hasPoLicenciaActivo > 0){
                            $mensaje_texto = 'Ya cuenta con PO en Licencia.';
                        }else if($datoLiquidacionDiseno['liquido_licencia']==1){
                            $estadoIcono = 1;
                            $mensaje_texto = null;
                        }
                    }
                }
            }else if($row->id_tipo_partida == 3 ||$row->id_tipo_partida == 4 || $row->id_tipo_partida == 7 ||$row->id_tipo_partida == 8){#TENDIDO Y EMPALMADOR FO Y COAXIAL
                $mensaje_texto = 'Estacion no Liquidada.';
                $datoLiquidacionEstacion =  $this->m_pqt_terminado->haveEstaLiquidada($itemplan, $idEstacion);
                IF($datoLiquidacionEstacion!=NULL){
                    if($datoLiquidacionEstacion['porcentaje']=='100'){//si esta al 100% se paga
                        $estadoIcono    = 1;
                        $mensaje_texto  = null;
                    }
                }
            }//CUANDO SE LE PAGABA LA FUENTE Y ENERGIA??? EN COAXIAL??
            else if($row->id_tipo_partida == 9){#FUENTE Y ENERGIA
                $mensaje_texto = 'Estacion Fuente o Energia No liquidadas.';
                $datoLiquidacionEstacion =  $this->m_pqt_terminado->haveEstaLiquidada($itemplan, ID_ESTACION_FUENTE);
                IF($datoLiquidacionEstacion!=NULL){
                    if($datoLiquidacionEstacion['porcentaje']=='100'){//si esta al 100% se paga
                        $estadoIcono = 1;
                        $mensaje_texto  = null;
                    }
                }else{
                    $datoLiquidacionEstacion =  $this->m_pqt_terminado->haveEstaLiquidada($itemplan, ID_ESTACION_ENERGIA);
                    IF($datoLiquidacionEstacion!=NULL){
                        if($datoLiquidacionEstacion['porcentaje']=='100'){//si esta al 100% se paga
                            $estadoIcono = 1;
                            $mensaje_texto  = null;
                        }
                    }
                }
            }
    
            $html .=    '<tr>
                        <th>'.$row->tipoPreciario.'</th>
                        <th>'.utf8_decode($row->partidaPqt).'</th>
                        <th>'.$row->baremo.'</th>
                        <th>'.$row->cantFactorPlanificado.'</th>
                        <th>'.$row->costo.'</th>
                        <th>'.(($estadoIcono == 1) ? $row->form : '0').'</th>
                        <th>'.$this->getIconPartidaPqt($estadoIcono,$mensaje_texto).'</th>
		             </tr>';
            if($estadoIcono ==  1){
                $total_pqt = $total_pqt + $row->total;
            }
        }
        $html .=    '<tr style="color: white;background: var(--verde_telefonica);">
                        <th style="font-weight: bolder;">TOTAL</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th style="font-weight: bolder;">'.number_format($total_pqt,2).'</th>
                        <th><p data-cost="'.$total_pqt.'" id="tot_pqt_'.$idEstacion.'" ></p></th>
		             </tr>';
        $html .='   </tbody>
                </table>';
        return $html;
    }
    
    function getIconPartidaPqt($estado, $texto){
        #<!--<i title="Invalido" class="zmdi zmdi-hc-2x zmdi-close-circle" style="color: red;">--> ALTERNATIVO CANCEL
        $html = '';
        if($estado == 1){#OK
            $html = '<i title="Validado" class="zmdi zmdi-hc-2x zmdi-check-circle" style="color: green;"></i>';
        }else{#NO OK
            $html = '<i title="'.$texto.'" class="zmdi zmdi-hc-2x zmdi-info-outline" style="color: red;"></i>';
        }
        return $html;
    }    
    
    function validarPartidasNivel1() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            if($this->session->userdata('idPersonaSession') != null){
                $itemplan       = ($this->input->post('itemplan')=='')      ? null : $this->input->post('itemplan');
                $idEstacion     = ($this->input->post('idEstacion')=='')    ? null : $this->input->post('idEstacion');
                $idSoli         = ($this->input->post('idSoli')=='')    ? null : $this->input->post('idSoli');
                
                if($idSoli  ==  null){
                    throw new Exception('No se detecto un ID Valido Refresque la pantalla de persistir genere un ticket CAP.');
                }
                $dataUpdate = array ('estado' => 1,
                                    'usua_val_nivel_1' => $this->session->userdata('idPersonaSession'),
                                    'fec_val_nivel_1' => $this->fechaActual(),
                                    'id_solicitud'  =>  $idSoli
                                    
                );
                $data = $this->m_bandeja_valida_po_pqt->validateNivel1($dataUpdate, $idSoli);
            }else{
                throw new Exception('La session expiro, vuelva a iniciar Sesion.');
            }
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function rechazarSolicitud() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            if($this->session->userdata('idPersonaSession') != null){
                $itemplan       = ($this->input->post('itemplan')=='')      ? null : $this->input->post('itemplan');
                $idEstacion     = ($this->input->post('idEstacion')=='')    ? null : $this->input->post('idEstacion');
                $idSoli         = ($this->input->post('idSoli')=='')        ? null : $this->input->post('idSoli');
                $from           = ($this->input->post('from')=='')          ? null : $this->input->post('from');
                $comentario     = ($this->input->post('comentario')=='')    ? null : $this->input->post('comentario');
                if($idSoli  ==  null){
                    throw new Exception('No se detecto un ID Valido, Refresque la pantalla de persistir genere un ticket CAP.');
                }
                
                if($from==1){
                        $dataUpdate = array ('estado'           => 3,
                                            'usua_val_nivel_1'  => $this->session->userdata('idPersonaSession'),
                                            'fec_val_nivel_1'   => $this->fechaActual(),
                                            'id_solicitud'      => $idSoli,
                                            'comentario'       => $comentario
                        );
                }else if($from==2){
                        $dataUpdate = array ('estado'           => 4,
                                            'usua_val_nivel_2'  => $this->session->userdata('idPersonaSession'),
                                            'fec_val_nivel_2'   => $this->fechaActual(),
                                            'id_solicitud'      => $idSoli,
                                            'comentario'       => $comentario
                        );
                }else{
                    throw new Exception('No se detecto el origen de la solicitud de rechazo, Refresque la pantalla de persistir genere un ticket CAP.');                    
                }
                
                //poner logica para guardar los datos rechazados en alguna tabla..
                $data = $this->m_bandeja_valida_po_pqt->rechazarSolicitud($dataUpdate, $idSoli, $itemplan, $idEstacion);
            }else{
                throw new Exception('La session expiro, vuelva a iniciar Sesion.');
            }
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
}