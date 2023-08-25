<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_terminado_v2 extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_pqt_terminado/m_pqt_terminado');
        $this->load->model('mf_detalle_obra/m_registro_po_mo');
        $this->load->model('mf_pqt_liquidacion_mat/m_pqt_reg_mat_x_esta');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index(){
        log_message('error','Ingreso C_terminado');
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
	           $itemplan = (isset($_GET['itemplan']) ? $_GET['itemplan'] : '');
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
               $permisos =  $this->session->userdata('permisosArbol');
        	   #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_GESTION_SIOM, ID_PERMISO_HIJO_BANDEJA_SIOM);
        	   $data['contenidoBlock']     = $this->getContTabs($itemplan);
        	   $data['item'] = "'".$itemplan."'";
        	   #$data['opciones'] = $result['html'];
        	   $this->load->view('vf_pqt_gestion_obra_terminado/v_terminado',$data);
        	   /*if($result['hasPermiso'] == true){
        	         $this->load->view('vf_pqt_gestion_obra_en_validacion/v_en_validacion',$data);
        	   }else{
        	       redirect('login','refresh');
        	   }*/
    	 }else{
        	 redirect('login','refresh');
	    }
    }

    function getContTabs($itemplan) {
        $infoFullItemplan = $this->m_utils->getInfoItemplan($itemplan);
        $infoItm = $this->m_pqt_terminado->getInfoBasicToGeneratePartidasByItemplan($itemplan);
        $html = '';
        if($infoItm==null){//PRIMERO QUE EL IP TENGA INFORMACION
            $html = '<h4 class="text-center" style="color:red">Excepcion detectada, comuniquese con soporte.</h4>';
        }else if(in_array($infoFullItemplan['idSubProyecto'], array(155, 279, 283, 553, 554, 579, 582))){//si es ruta
            $estaAnclas = $this->m_pqt_terminado->getEstacionesAnclasRutas($itemplan);
            $html = '<div class="tab-container">
                        <ul class="nav nav-tabs nav-fill" role="tablist">';
            $active = 'active';
            foreach ($estaAnclas as $row){
                $html .= '<li class="nav-item">
                                            <a class="nav-link '.$active.'" data-toggle="tab" href="#tab'.$row->idEstacion.'" role="tab">'.$row->estacionDesc.'</a>
                                          </li>';
                $active = '';
            }
            $html .= '  </ul>
                        <div class="tab-content" style="margin-left: 20px;margin-right: 20px;">';
            $active = 'active';
            foreach ($estaAnclas as $row){
                $havePdt = $this->m_pqt_terminado->haveSolPdtValidacion($itemplan, $row->idEstacion);
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
                $estados_no_acepted = array(PO_REGISTRADO,PO_PREAPROBADO,PO_APROBADO,PO_PRECANCELADO,PO_CERTIFICADO);
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
                }else if($havePdt > 0){
                    $html .= '<h4 class="text-center" style="color:red">La estacion ya cuenta con una solicitud pdt de validacion.</h4>';
                }else if($has_po==0){
                    $html .= '<h4 class="text-center" style="color:red">La estacion no cuenta con PO Trabajadas.</h4>';
                }else if($has_pos_no_aceptados > 0){
                    $html .= '<h4 class="text-center" style="color:red">Las PO de MO deben estar Liquidadas.</h4>';
                }else{
                    $html .= '<div class="row">
                                    <div class="col-sm-6 col-md-6">
                                        <label style="color: red" class="control-label mb-10 text-left">Adjuntar Expediente.</label><br>
                                        <input id="fileEvidencia" name="fileEvidencia" type="file" class="file" data-show-preview="false">
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <button style="margin-top: 20px;" data-costo_total="'.$row2->costo_total.'" data-idEs="'.$row->idEstacion.'" class="btn btn-success sendValiRuta" type="button">ENVIAR EXPEDIENTE</button>
                                    </div>                                      
                             </div>';
                }
                $html .= '</div></div>';
                $active = '';
            }
            $html .='</div></div>';
        }else if($infoFullItemplan['paquetizado_fg'] == 1){//OBRAS NO PAQUETIZADAS INCLUYE OPEX            
           /* $estaAnclas = $this->m_pqt_terminado->getEstacionHasPoToExpediente($itemplan);//OBTENEMOS LAS AREA QUE SE TRABAJARON
          
            foreach ($estaAnclas as $row){*/
            $havePdt = $this->m_pqt_terminado->haveSolPdtValidacionByObra($itemplan);
            $havePdtAprob = $this->m_pqt_terminado->haveSolAprobadaByObra($itemplan);
                
            $listaPoFull = $this->m_pqt_terminado->getAllPoMoBySoloItemplan($itemplan);
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
                $estados_no_acepted = array(PO_REGISTRADO,PO_PREAPROBADO,PO_APROBADO,PO_PRECANCELADO,PO_CERTIFICADO);
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
                }else if($havePdt > 0){
                    $html .= '<h4 class="text-center" style="color:red">La estacion ya cuenta con una solicitud pdt de validacion.</h4>';
                }else if($has_po==0){
                    $html .= '<h4 class="text-center" style="color:red">La estacion no cuenta con PO Trabajadas.</h4>';
                }else if($has_pos_no_aceptados > 0){
                    $html .= '<h4 class="text-center" style="color:red">Las PO de MO deben estar Liquidadas.</h4>';
                }else if($havePdtAprob > 0){
                    $html .= '<h4 class="text-center" style="color:red">Solicitud Aprobada.</h4>';
                }else{
                    $html .= '<div class="row">
                                    <div class="col-sm-6 col-md-6">
                                        <label style="color: red" class="control-label mb-10 text-left">Adjuntar Expediente.</label><br>
                                        <input id="fileEvidencia" name="fileEvidencia" type="file" class="file" data-show-preview="false">
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <button style="margin-top: 20px;" data-costo_total="'.$costo_final_obra.'" class="btn btn-success sendValiNoPqt" type="button">ENVIAR EXPEDIENTE</button>
                                    </div>                                      
                             </div>';
                }
                $html .= '</div>';              
         //   }
       }else{
            $estaAnclas = $this->m_pqt_terminado->getEstacionesAnclasByItemplan($itemplan);
            $html = '<div class="tab-container">
                        <ul class="nav nav-tabs nav-fill" role="tablist">';
                            $active = 'active';
                            foreach ($estaAnclas as $row){
                                $html .= '<li class="nav-item">
                                            <a class="nav-link '.$active.'" data-toggle="tab" href="#tab'.$row->idEstacion.'" role="tab">'.$row->estacionDesc.'</a>
                                          </li>';
                                $active = '';
                            }
            $html .= '  </ul>    
                        <div class="tab-content" style="margin-left: 20px;margin-right: 20px;">';
            $active = 'active';
            foreach ($estaAnclas as $row){
                      $contPartidasPqt = $this->getPartidasPaquetizadasV2($row->idEstacion, $infoItm['idSubProyecto'], $infoItm['idEmpresaColab'], $infoItm['isLima'], $itemplan);
                      $html .= '<div class="tab-pane '.$active.' fade show" id="tab'.$row->idEstacion.'" role="tabpanel">
                                    <p style="font-size: larger;color: #007bff;text-align: left;font-weight: bold;">Partidas Paquetizadas</p>      
                                    '.$contPartidasPqt['html'];      
                      
                      #inicio botones..
                      $poValidada = false;
                      $poPndtValidacion = false;
                      $canCreateMarteriales = true;
                      $valPartidasAdicionales = '';
                      $valEEcc = '';
                      $msjEECC = '';
                      $valTDP = 'style="display:none"';                  
                      $showButtons = true;
                      $hasPtrCreado = $this->m_pqt_terminado->getEstatusEstacionItemplan($itemplan, $row->idEstacion);
                      if($hasPtrCreado != null){
                          if($hasPtrCreado['estado']==1){//validado con po                         
                              $showButtons = false;
							  $hasSolActivo 	= $this->m_utils->hasSolExceActivo($itemplan, TIPO_PO_MANO_OBRA);
                             // $hasOcAtendido 	= $this->m_utils->hasSolOCAtendido($itemplan);
							  if($hasSolActivo > 0){
                                  $msjEECC = '<h4 class="text-center" style="color:red">La Obra con Solicitud de Exceso PDT de aprobacion.</h4>';                                   
                              }else {
									$msjEECC = '<div class="row">
                                        <div class="col-sm-4 col-md-4">            
                                            <label style="color: red" class="control-label mb-10 text-left">Adjuntar Expediente.</label><br>            
                                            <input id="fileEvidencia" name="fileEvidencia" type="file" class="file" data-show-preview="false">                           
                                        </div>  
                                        <div class="col-sm-4 col-md-4">
                                            <button style="margin-top: 20px;" data-idEs="'.$row->idEstacion.'" data-po="'.$hasPtrCreado['codigo_po'].'" class="btn btn-success sendVali" type="button">APROBAR PROPUESTA</button>
                                        </div>
                                        <div class="col-sm-4 col-md-4">
                                            <a style="margin-top: 20px;" class="btn btn-success" href="pqt_gestion_incidencias" target="_blank" type="button"">OBSERVAR</a>
                                        </div>
                                    </div>';
							  }
                              $poValidada = true;
                              $canCreateMarteriales = false;                          
                          }else if($hasPtrCreado['estado']==0){//con partidas para enviar  a validacion                     
                                  $valEEcc = 'style="display:none"';    
                                  $valTDP = '';
                                  $poPndtValidacion = true;              
                          }else if($hasPtrCreado['estado']==2){//pdts de validacion   
                               $showButtons = false;    
                               $canCreateMarteriales = false;
                               $msjEECC = '<h4 class="text-center" style="color:red">Partidas Adicionales Pendientes de Validacion TDP.</h4>';
                          }else if($hasPtrCreado['estado']==4){//pdts de validacion   
                               $showButtons = false;    
                               $canCreateMarteriales = false;
                               $msjEECC = '';
                          }
                      }
                      
                      if($canCreateMarteriales){//si existe la opcion de crear materiales
                          $ferreteriaInfo = $this->m_pqt_terminado->getGroupFerreteria($itemplan, $row->idEstacion);
                          if($ferreteriaInfo != null){
                              if($ferreteriaInfo['estado']==0){//PDT VALIDACION
                                  $valEEcc = 'style="display:none"';
                                  $valTDP = '';
                              }else if($ferreteriaInfo['estado']==3){//PDT VALIDACION    
                                  $canCreateMarteriales = false;
                                  $showButtons = false;
                                  $msjEECC = '<h4 class="text-center" style="color:red">Partidas Adicionales Pendientes de Validacion TDP.</h4>';
                              }else if($ferreteriaInfo['estado']==4){//PDT VALIDACION    
                                  $canCreateMarteriales = false;
                                  $showButtons = false;
                                  $msjEECC = '';
                              }
                          }
                      }
                      
                      $idEstacionOC = 0;
                      if($row->idEstacion==ID_ESTACION_COAXIAL){
                          $idEstacionOC = ID_ESTACION_OC_COAXIAL;                      
                      }else if($row->idEstacion==ID_ESTACION_FO){
                          $idEstacionOC = ID_ESTACION_OC_FO;
                      }
                      $listaPoOC = $this->m_pqt_terminado->getPoOCByItemplan($itemplan, $idEstacionOC);//obtenemos po de la obra civil
                      
                      $contenidoOC = '';
                      if(count($listaPoOC)>0){
                          $has_pos_no_aceptados = 0;
                          
                          $contenidoOC .= '<p style="font-size: larger;color: #007bff;text-align: left;font-weight: bold;">Obra Civil</p>';
                          $contenidoOC .= '<table class="table table-bordered">
                                    <thead class="thead-default">
                                        <tr>
                                            <th>AREA</th>
                                            <th>TIPO</th>
                                            <th>CODIGO PO</th>
                                            <th>ESTADO PO</th>                                       
                                            <th>COSTO TOTAL</th>
                                       <tr></thead>                   
                        <tbody>';
                          foreach ($listaPoOC as $row2){
                   $contenidoOC .= '<tr>
                                    <th>'.$row2->estacionDesc.'</th>
                                    <th>'.$row2->tipoPo.'</th>
                                    <th>'.$row2->codigo_po.'</th>
                                    <th>'.$row2->estado.'</th>                               
                                    <th>'.number_format($row2->costo_total, 2).'</th>
        		                  </tr>';
                            $estados_no_acepted = array(PO_REGISTRADO,PO_PREAPROBADO,PO_APROBADO,PO_PRECANCELADO,PO_CERTIFICADO);
                            if(in_array($row2->estado_po, $estados_no_acepted)){
                                $has_pos_no_aceptados++;
                            }
                          }
                         $contenidoOC .= '   </tbody>
                          </table>';
                         
                             $canSendValidatePartidas = true;
                             if($has_pos_no_aceptados>0){//tiene por con estados bloqueantes
                                 $canSendValidatePartidas = false;
                                 $msjEECC = '<h4 class="text-center" style="color:red">Las PO de La obra Civil deben estar Liquidadas.</h4>';
                             }else if($has_pos_no_aceptados==0){//no tiene por con estados bloqueantes
                                 $itemplanEstacionAvance = $this->m_utils->getPorcentajeAvanceByItemplanEstacion($itemplan, $idEstacionOC);
                                 if($itemplanEstacionAvance==null){//no tiene porcentaje al 100%
                                     $canSendValidatePartidas = false;
                                     $msjEECC = '<h4 class="text-center" style="color:red">La Obra Civil no se encuentra Liquidada (100%).</h4>';
                                 }else{
                                     if($itemplanEstacionAvance['porcentaje']==100){
                                         $canSendValidatePartidas = true;
                                     }else{//no tiene porcentaje al 100%
                                         $canSendValidatePartidas = false;
                                         $msjEECC = '<h4 class="text-center" style="color:red">La Obra Civil no se encuentra Liquidada (100%).</h4>';
                                     }
                                 }
                                 
                             }
                      
                           if(!$canSendValidatePartidas){
                               $canCreateMarteriales = false;
                               $showButtons = false;                           
                           }                      
                       }
                      /**********validacion de la po deOC****/
                       /**********RECHAZADOS*********/
                       $solicitudPartAdic = $this->m_pqt_terminado->getSolicitudPartidasAdicionales($itemplan, $row->idEstacion);
                       if($solicitudPartAdic!=null){
                           if($solicitudPartAdic['estado']==3){
                               $canCreateMarteriales = true;
                               $valEEcc = 'style="display:none"';
                               $valTDP = '';
                               $showButtons = true;
                               $msjEECC = '<h4 class="text-center" style="color:red">La Solicitud previa fue rechazada, puede ver el Motivo <a class="getRechazado" data-esta="'.$row->idEstacion.'" data-item="'.$itemplan.'" style="color:blue">Aqui <i title="Movimientos" style="color:#A4A4A4" class="zmdi zmdi-hc-1x zmdi-search"></i></a></h4>';         
								$msjEECC .= '<div class="row">
                                        <div class="col-sm-4 col-md-4">
                                            <label style="color: red" class="control-label mb-10 text-left">Adjuntar Expediente.</label><br>
                                            <input id="fileEvidencia" name="fileEvidencia" type="file" class="file" data-show-preview="false">
                                        </div>
                                        <div class="col-sm-4 col-md-4">
                                            <button style="margin-top: 20px;" data-idEs="'.$row->idEstacion.'" data-po="'.$hasPtrCreado['codigo_po'].'" class="btn btn-success sendVali" type="button">APROBAR PROPUESTA</button>
                                        </div>
                                        <div class="col-sm-4 col-md-4">
                                            <a style="margin-top: 20px;" class="btn btn-success" href="pqt_gestion_incidencias" target="_blank" type="button"">OBSERVAR</a>
                                        </div>
                                    </div>';							   
                           }else if($solicitudPartAdic['estado']==4){
                               $canCreateMarteriales = true;
                               $valEEcc = 'style="display:none"';
                               $valTDP = '';
                               $showButtons = true;
                               $msjEECC = '<h4 class="text-center" style="color:red">La Solicitud previa fue rechazada, puede ver el Motivo <a class="getRechazado" data-esta="'.$row->idEstacion.'" data-item="'.$itemplan.'" style="color:blue">Aqui <i title="Movimientos" style="color:#A4A4A4" class="zmdi zmdi-hc-1x zmdi-search"></i></a></h4>'; 
								$msjEECC .= '<div class="row">
                                        <div class="col-sm-4 col-md-4">
                                            <label style="color: red" class="control-label mb-10 text-left">Adjuntar Expediente.</label><br>
                                            <input id="fileEvidencia" name="fileEvidencia" type="file" class="file" data-show-preview="false">
                                        </div>
                                        <div class="col-sm-4 col-md-4">
                                            <button style="margin-top: 20px;" data-idEs="'.$row->idEstacion.'" data-po="'.$hasPtrCreado['codigo_po'].'" class="btn btn-success sendVali" type="button">APROBAR PROPUESTA</button>
                                        </div>
                                        <div class="col-sm-4 col-md-4">
                                            <a style="margin-top: 20px;" class="btn btn-success" href="pqt_gestion_incidencias" target="_blank" type="button"">OBSERVAR</a>
                                        </div>
                                    </div>';							   
                           }
                       }
                       /*****************************/
                                            
                      $outPutAdicionales = $this->getPartidasAdicionalesV2($itemplan, $row->idEstacion, $poValidada, $poPndtValidacion, $canCreateMarteriales, $hasPtrCreado['codigo_po']);
                      $html .= '<p style="font-size: larger;color: #007bff;text-align: left;font-weight: bold;">Partidas Adicionales</p>      
                                    '.$outPutAdicionales['html'];     
								
					  $estadoPoPqt = $hasPtrCreado['estado_po'];
					  $estados_no_acepted_pqt = array(PO_REGISTRADO,PO_PREAPROBADO,PO_APROBADO,PO_CERTIFICADO);
					  if(in_array($estadoPoPqt, $estados_no_acepted_pqt)){
						$canSendValidatePartidas = false;
						$msjEECC = '<h4 class="text-center" style="color:red">Las PO Paquetizada debe estar Liquidada.</h4>';
					  }
                      $html.= '<h4 class="text-center" style="color:green">Costo Total Actual de la PO '.$hasPtrCreado['codigo_po'].' :  S./ '.number_format(($contPartidasPqt['costo_total']+$outPutAdicionales['costo_total']),2).'</h4>';
                      $html.= $msjEECC;
                      $html.= $contenidoOC.'</div>';;
                      $active = '';
            }
            
             $html .= ' </div>
                     </div>';
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
            }else if($solicitudPartAdic['estado']    ==  3 || $solicitudPartAdic['estado']    ==  4){
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
                                '.(($pathEvidencia != null) ? '<a href="'.$pathEvidencia.'" download=""><i title="Descargar" class="zmdi zmdi-hc-2x zmdi-download"></i></a>
                                                               <a class="getMateEsta" data-esta="'.$idEstacion.'" data-item="'.$itemplan.'"><i title="buscar" style="color:#A4A4A4" class="zmdi zmdi-hc-2x zmdi-eye"></i></a>' : '').'
                                '.(($canCreateMarteriales) ? '<a onclick="cargarRegMateriales(\''.$itemplan.'\','.$idEstacion.')"><i title="Registrar Materiales Paquetizados" class="zmdi zmdi-hc-2x zmdi-edit"></i></a>' : '').'
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
            if($iconEstado  ==  1){
                $totalPartAdic = $totalPartAdic + $totalFerr;
            }
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
                        <th style="font-weight: bolder;">'.number_format($totalPartAdic,2).'</th>
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
                IF($datoLiquidacionDiseno!=NULL){
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
                            if($datoLiquidacionDiseno['liquido_licencia']==1){
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
    
    
    function getArrayPartidasToPO($idEstacion, $idSubProyecto, $idEmpresaColab, $isLima, $itemplan){
        $partidasOutPut = array();
        
        /*Obtenemos paquetizados*/
        $listaPartidasPqt = $this->m_pqt_terminado->getPartidasPaquetizadasByItemEccJefaTuraEstacion($idEstacion, $idSubProyecto, $idEmpresaColab, $isLima, $itemplan);
        $arrayActividades = array();
        foreach ($listaPartidasPqt as $row){
            if($row->id_tipo_partida == 1 ||$row->id_tipo_partida == 5){#DISEÑO FO y COAXIAL
                $datoLiquidacionDiseno = $this->m_pqt_terminado->haveLiquidacionDiseno($itemplan, $idEstacion);
                IF($datoLiquidacionDiseno!=NULL){
                    if($datoLiquidacionDiseno['fecha_ejecucion']!=NULL){
                        if(!in_array($row->idActividad, $arrayActividades)){
                            $dataCMO['idActividad']      = $row->idActividad;
                            $dataCMO['baremo']           = $row->baremo;
                            $dataCMO['costo']            = $row->costo;
                            $dataCMO['cantidad_inicial'] = $row->cantFactorPlanificado;
                            $dataCMO['monto_inicial']    = $row->total;
                            $dataCMO['cantidad_final']   = $row->cantFactorPlanificado;
                            $dataCMO['monto_final']      = $row->total;
                            array_push($arrayActividades, $row->idActividad);//metemos idActividad
                            array_push($partidasOutPut, $dataCMO);
                        }
                    }
                }
            }else if($row->id_tipo_partida == 2 ||$row->id_tipo_partida == 6){#LICENCIA FO y COAXIAL
                $datoLiquidacionDiseno = $this->m_pqt_terminado->haveLiquidacionDiseno($itemplan, $idEstacion);
                IF($datoLiquidacionDiseno!=NULL){
                    if($datoLiquidacionDiseno['requiere_licencia']!=2){
                        if($datoLiquidacionDiseno['liquido_licencia']==1){
                            if(!in_array($row->idActividad, $arrayActividades)){
                                $dataCMO['idActividad']      = $row->idActividad;
                                $dataCMO['baremo']           = $row->baremo;
                                $dataCMO['costo']            = $row->costo;
                                $dataCMO['cantidad_inicial'] = $row->cantFactorPlanificado;
                                $dataCMO['monto_inicial']    = $row->total;
                                $dataCMO['cantidad_final']   = $row->cantFactorPlanificado;
                                $dataCMO['monto_final']      = $row->total;
                                array_push($arrayActividades, $row->idActividad);//metemos idActividad
                                array_push($partidasOutPut, $dataCMO);
                            }
                        }
                    }
                }
            }else if($row->id_tipo_partida == 3 ||$row->id_tipo_partida == 4 || $row->id_tipo_partida == 7 ||$row->id_tipo_partida == 8){#TENDIDO Y EMPALMADOR FO Y COAXIAL
                $mensaje_texto = 'Estacion no Liquidada.';
                $datoLiquidacionEstacion =  $this->m_pqt_terminado->haveEstaLiquidada($itemplan, $idEstacion);
                IF($datoLiquidacionEstacion!=NULL){
                    if($datoLiquidacionEstacion['porcentaje']=='100'){//si esta al 100% se paga
                            if(!in_array($row->idActividad, $arrayActividades)){
                                $dataCMO['idActividad']      = $row->idActividad;
                                $dataCMO['baremo']           = $row->baremo;
                                $dataCMO['costo']            = $row->costo;
                                $dataCMO['cantidad_inicial'] = $row->cantFactorPlanificado;
                                $dataCMO['monto_inicial']    = $row->total;
                                $dataCMO['cantidad_final']   = $row->cantFactorPlanificado;
                                $dataCMO['monto_final']      = $row->total;
                                array_push($arrayActividades, $row->idActividad);//metemos idActividad
                                array_push($partidasOutPut, $dataCMO);
                            }
                    }
                }
            }//CUANDO SE LE PAGABA LA FUENTE Y ENERGIA??? EN COAXIAL??
            else if($row->id_tipo_partida == 9){#FUENTE Y ENERGIA
                $mensaje_texto = 'Estacion Fuente o Energia No liquidadas.';
                $datoLiquidacionEstacion =  $this->m_pqt_terminado->haveEstaLiquidada($itemplan, ID_ESTACION_FUENTE);
                IF($datoLiquidacionEstacion!=NULL){
                    if($datoLiquidacionEstacion['porcentaje']=='100'){//si esta al 100% se paga
                        if(!in_array($row->idActividad, $arrayActividades)){
                                $dataCMO['idActividad']      = $row->idActividad;
                                $dataCMO['baremo']           = $row->baremo;
                                $dataCMO['costo']            = $row->costo;
                                $dataCMO['cantidad_inicial'] = $row->cantFactorPlanificado;
                                $dataCMO['monto_inicial']    = $row->total;
                                $dataCMO['cantidad_final']   = $row->cantFactorPlanificado;
                                $dataCMO['monto_final']      = $row->total;
                                array_push($arrayActividades, $row->idActividad);//metemos idActividad
                                array_push($partidasOutPut, $dataCMO);
                            }
                    }
                }else{
                    $datoLiquidacionEstacion =  $this->m_pqt_terminado->haveEstaLiquidada($itemplan, ID_ESTACION_ENERGIA);
                    IF($datoLiquidacionEstacion!=NULL){
                        if($datoLiquidacionEstacion['porcentaje']=='100'){//si esta al 100% se paga
                            if(!in_array($row->idActividad, $arrayActividades)){
                                $dataCMO['idActividad']      = $row->idActividad;
                                $dataCMO['baremo']           = $row->baremo;
                                $dataCMO['costo']            = $row->costo;
                                $dataCMO['cantidad_inicial'] = $row->cantFactorPlanificado;
                                $dataCMO['monto_inicial']    = $row->total;
                                $dataCMO['cantidad_final']   = $row->cantFactorPlanificado;
                                $dataCMO['monto_final']      = $row->total;
                                array_push($arrayActividades, $row->idActividad);//metemos idActividad
                                array_push($partidasOutPut, $dataCMO);
                            }
                        }
                    }
                }
            }
        }
                              
        /**obtenemos ferreteria**/
        $codigoFerreteria = '69901-2';#ponerlo como constante
        $infoPartida =  $this->m_pqt_terminado->getInfoPartidaByCodPartida($itemplan, $idEstacion, $codigoFerreteria);
        if($infoPartida != null){
            $precioTmp = $infoPartida['costo'];
        }
        
        $ferreteriaInfo = $this->m_pqt_terminado->getGroupFerreteria($itemplan, $idEstacion);
        $cantidadFerr = 0;
        $totalFerr = 0;
        if($ferreteriaInfo != null){
            $totalFerr = $ferreteriaInfo['costo_total_final'];
            $cantidadFerr = ($totalFerr/$precioTmp);
            
            if(!in_array($infoPartida['idActividad'], $arrayActividades)){
                $dataCMO['idActividad']      = $infoPartida['idActividad'];
                $dataCMO['baremo']           = $infoPartida['baremo'];
                $dataCMO['costo']            = $precioTmp;
                $dataCMO['cantidad_inicial'] = $cantidadFerr;
                $dataCMO['monto_inicial']    = $totalFerr;
                $dataCMO['cantidad_final']   = $cantidadFerr;
                $dataCMO['monto_final']      = $totalFerr;
                array_push($arrayActividades, $infoPartida['idActividad']);//metemos idActividad
                array_push($partidasOutPut, $dataCMO);
            }
        }
        
        /**partidas dicionales**/
        
        $partidasAdicionales = $this->m_pqt_terminado->getPartidasAdicionalesTmpByItemplanEstacion($itemplan, $idEstacion);
        foreach ($partidasAdicionales as $row){
            
            if(!in_array($row->idActividad, $arrayActividades)){
                $dataCMO['idActividad']      = $row->idActividad;
                $dataCMO['baremo']           = $row->baremo;
                $dataCMO['costo']            = $row->costo;
                $dataCMO['cantidad_inicial'] = $row->cantidad;
                $dataCMO['monto_inicial']    = $row->total;
                $dataCMO['cantidad_final']   = $row->cantidad;
                $dataCMO['monto_final']      = $row->total;
                array_push($arrayActividades, $row->idActividad);//metemos idActividad
                array_push($partidasOutPut, $dataCMO);
            }        
        }
        return $partidasOutPut;
    }
        
    public function savePoMo(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;
            if($idUsuario   !=     null){
                $itemplan       = $this->input->post('itemplan');
                $idEstacion     = $this->input->post('idEstacion');
                $codigo_po      = $this->input->post('codigo_po');
                $arrayPartidasInsert = array();
                
                $infoItm = $this->m_pqt_terminado->getInfoBasicToGeneratePartidasByItemplan($itemplan);
                if($infoItm==null){
                    throw new Exception('Excepcion detectada, comuniquese con soporte.');
                }
                $listaPartidasToCreate = $this->getArrayPartidasToPO($idEstacion, $infoItm['idSubProyecto'], $infoItm['idEmpresaColab'], $infoItm['isLima'], $itemplan);
                
                if($listaPartidasToCreate!=null){
                   
                   // $costoTotalPOMO = 0;
                    foreach($listaPartidasToCreate as $datos){
                        $partidaInfo = array();
                        $partidaInfo = $datos;
                        $partidaInfo['codigo_po']  = $codigo_po;
                        array_push($arrayPartidasInsert, $partidaInfo);
                        //$costoTotalPOMO = $costoTotalPOMO + $datos['monto_final'];                           
                    }
                
                    $data = $this->m_pqt_terminado->updatePartidasPoPqt($arrayPartidasInsert, $codigo_po);
                    if($data['error']   ==  EXIT_ERROR){
                        throw new Exception('Hubo un error interno, por favor volver a intentar.');
                    }
                    
                    /*AQUI LUEGO DE CREAR LA PO SE DEBE GENERAR UNA SOLICITUD DE EDICACION CERTIFICACION DE ORDEN DE COMPRA SI LA TIENE Y SI NO VA A LA BOLSA***/
                    //$data['codigoPO']    =   $codigo_po;
                    $data['error']       = EXIT_SUCCESS;
                }else{
                    throw new Exception('No se pudo procesar el archivo, refresque la pagina y vuelva a intentarlo.');
                }
            }else{
                throw new Exception('Su sesion expiro, porfavor vuelva a logearse.');
            }
             
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function fechaActual()
    {   
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone', 'America/Lima');
        setlocale(LC_TIME, "es_ES", "esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }
    
    public function sendValidatePartidasAdicionales(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;
            if($idUsuario   !=     null){
                $itemplan         = $this->input->post('itemplan');
                $idEstacion       = $this->input->post('idEstacion');
                $costo_total      = $this->input->post('costo_total');
                $costo_inicial    = $this->input->post('costo_inicial');
                $costo_adicional  = $this->input->post('costo_adicional');
                $codigo_po        = $this->input->post('codigo_po');
                
                $pathItemplan = 'uploads/itemplan_expediente/'.$itemplan;
                if (!is_dir($pathItemplan)) {
                    mkdir ($pathItemplan, 0777);
                }
                
                $descEstacion = $this->m_utils->getEstaciondescByIdEstacion($idEstacion);
                //DE NO EXISTIR LA CARPETA ITEMPLAN ESTACION LA CREAMOS
                $pathItemEstacion = $pathItemplan.'/'.$descEstacion;
                if (!is_dir($pathItemEstacion)) {
                    mkdir ($pathItemEstacion, 0777);
                }
                
                $uploadfile1 = $pathItemEstacion.'/'. basename($_FILES['fileEvi']['name']);
                
                if (move_uploaded_file($_FILES['fileEvi']['tmp_name'], $uploadfile1)) {
                    log_message('error', 'se movio:'.$uploadfile1);
                
                    $arrayPqPartAdicionales = array('estado'     => 2,
                                                    'itemplan'   => $itemplan,
                                                    'idEstacion' => $idEstacion,
                                                    'fec_envio_validacion'  => $this->fechaActual(),
                                                    'usua_envio_validacion' => $this->session->userdata('idPersonaSession')
                    );
                    
                    $arrayFerreteria = array('estado'       => 3,
                                            'itemplan'      => $itemplan,
                                            'idEstacion'    => $idEstacion,
                                            'fec_envio_validacion'  => $this->fechaActual(),
                                            'usua_envio_validacion' => $this->session->userdata('idPersonaSession')
                    );
                    
                    
                    $dataSolValidacion = array('fec_registro'       =>  $this->fechaActual(),   
                                                'usua_registro'     =>  $this->session->userdata('idPersonaSession'),
                                                'estado'            =>  0,
                                                'costo_total'       =>  $costo_total,
                                                'itemplan'          =>  $itemplan,
                                                'idEstacion'        =>  $idEstacion,
                                                'costo_inicial'     =>  $costo_inicial,
                                                'costo_adicional'   =>  $costo_adicional,
                                                'activo'            =>  1                    
                    );
                    
                    $data_expediente = array('itemplan'     =>  $itemplan,
                                             'fecha'        =>  $this->fechaActual(),
                                             'comentario'   =>  'VALIDACION PQT',
                                             'usuario'      =>  $this->session->userdata('usernameSession'),
                                             'estado'       =>  'ACTIVO',
                                             'estado_final' =>  'PENDIENTE',
                                             'path_expediente'  => $uploadfile1,
                                             'idEstacion'   =>  $idEstacion
                    );   
                    
                    
                    $arrayPartidasInsert = array();
                    $infoItm = $this->m_pqt_terminado->getInfoBasicToGeneratePartidasByItemplan($itemplan);
                    if($infoItm==null){
                        throw new Exception('Excepcion detectada, comuniquese con soporte.');
                    }
                    $listaPartidasToCreate = $this->getArrayPartidasToPOPqt($idEstacion, $infoItm['idSubProyecto'], $infoItm['idEmpresaColab'], $infoItm['isLima'], $itemplan);
                    
                    if($listaPartidasToCreate!=null){
                         
                        // $costoTotalPOMO = 0;
                        foreach($listaPartidasToCreate as $datos){
                            $partidaInfo = array();
                            $partidaInfo = $datos;
                            $partidaInfo['codigo_po']  = $codigo_po;
                            array_push($arrayPartidasInsert, $partidaInfo);
                            //$costoTotalPOMO = $costoTotalPOMO + $datos['monto_final'];
                        }
                    }
                    
                    $data = $this->m_pqt_terminado->sendValidarPartidasAdicionales($itemplan, $idEstacion, $arrayFerreteria, $arrayPqPartAdicionales, $dataSolValidacion, $data_expediente, $arrayPartidasInsert, $codigo_po);
                    $data['error']       = EXIT_SUCCESS;   
                }else{
                    throw new Exception('No se puede cargar el archivo expediente, refresque la pantalla y vuelva a intentarlo.');
                }            
            }else{
                throw new Exception('Su sesion expiro, porfavor vuelva a logearse.');
            }
             
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getRechazadoByidSolicitud() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            if($this->session->userdata('idPersonaSession') != null){
                $itemplan       = ($this->input->post('itemplan')=='')      ? null : $this->input->post('itemplan');
                $idEstacion     = ($this->input->post('idEstacion')=='')    ? null : $this->input->post('idEstacion');
                $data['tablaRechazado'] = $this->getTablaRechazado($this->m_pqt_terminado->getSolicitudPartidasAdicionales($itemplan, $idEstacion));
                $data['error'] = EXIT_SUCCESS;
            }else{
                throw new Exception('La session expiro, vuelva a iniciar Sesion.');
            }
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getTablaRechazado($solicitudPartAdic) {
        $html = '';
                   if($solicitudPartAdic!=null){
                       if($solicitudPartAdic['estado']==3){
                           $canCreateMarteriales = true;
                           $valEEcc = 'style="display:none"';
                           $valTDP = '';
                           $showButtons = true;
                           $msjEECC = '<h4 class="text-center" style="color:red">La Solicitud previa fue rechazada, puede ver el Motivo <a style="color:blue">Aqui <i title="Movimientos" style="color:#A4A4A4" class="zmdi zmdi-hc-1x zmdi-search"></i></a></h4>';
                           $html .= '<div>
                                      <table class="table table-bordered">
                                        <thead class="thead-default">
                                            <tr>
                                                <th></th>
                                                <th>Usuario Rechazo</th>
                                                <th>Fecha Rechazo</th>
                                                <th>Comentario</th>
                                            <tr></thead>
                                            <tbody>
                                              <tr>
                                                <th></th>
                                                <th>'.$solicitudPartAdic['usuario_nivel_1'].'</th>
                                                <th>'.$solicitudPartAdic['fec_val_nivel_1'].'</th>
                                                <th>'.$solicitudPartAdic['comentario'].'</th>
            		                          </tr>
                                            </tbody>
                                        </table>
                                     </div>';
                       }else if($solicitudPartAdic['estado']==4){
                           $canCreateMarteriales = true;
                           $valEEcc = 'style="display:none"';
                           $valTDP = '';
                           $showButtons = true;
                           $msjEECC = '<h4 class="text-center" style="color:red">La Solicitud previa fue rechazada, puede ver el Motivo <a style="color:blue">Aqui <i title="Movimientos" style="color:#A4A4A4" class="zmdi zmdi-hc-1x zmdi-search"></i></a></h4>';
                           $html .= '<div>
                                      <table class="table table-bordered">
                                        <thead class="thead-default">
                                            <tr>
                                                <th></th>
                                                <th>Usuario Rechazo</th>
                                                <th>Fecha Rechazo</th>
                                                <th>Comentario</th>
                                            <tr></thead>
                                            <tbody>
                                              <tr>
                                                <th></th>
                                                <th>'.$solicitudPartAdic['usuario_nivel_2'].'</th>
                                                <th>'.$solicitudPartAdic['fec_val_nivel_2'].'</th>
                                                <th>'.$solicitudPartAdic['comentario'].'</th>
            		                          </tr>
                                            </tbody>
                                        </table>
                                     </div>';
                       }
                   }
                   return $html;
    }
    
    
    /***nuevo ***/
    
    function getArrayPartidasToPOPqt($idEstacion, $idSubProyecto, $idEmpresaColab, $isLima, $itemplan){
        $partidasOutPut = array();
    
        /*Obtenemos paquetizados*/
        $listaPartidasPqt = $this->m_pqt_terminado->getPartidasPaquetizadasByItemEccJefaTuraEstacion($idEstacion, $idSubProyecto, $idEmpresaColab, $isLima, $itemplan);
        $arrayActividades = array();
        foreach ($listaPartidasPqt as $row){
            if($row->id_tipo_partida == 1 ||$row->id_tipo_partida == 5){#DISEÑO FO y COAXIAL
                $datoLiquidacionDiseno = $this->m_pqt_terminado->haveLiquidacionDiseno($itemplan, $idEstacion);
                IF($datoLiquidacionDiseno!=NULL){
					$hasPoDisenoActivo = $this->m_pqt_terminado->hasPoDisenoActivo($itemplan);
                    if($datoLiquidacionDiseno['fecha_ejecucion']!=NULL && $hasPoDisenoActivo == 0){
                        if(!in_array($row->idActividad, $arrayActividades)){
                            $dataCMO['idActividad']      = $row->idActividad;
                            $dataCMO['baremo']           = $row->baremo;
                            $dataCMO['costo']            = $row->costo;
                            $dataCMO['cantidad_inicial'] = $row->cantFactorPlanificado;
                            $dataCMO['monto_inicial']    = $row->total;
                            $dataCMO['cantidad_final']   = $row->cantFactorPlanificado;
                            $dataCMO['monto_final']      = $row->total;
                            array_push($arrayActividades, $row->idActividad);//metemos idActividad
                            array_push($partidasOutPut, $dataCMO);
                        }
                    }
                }
            }else if($row->id_tipo_partida == 2 ||$row->id_tipo_partida == 6){#LICENCIA FO y COAXIAL
                $datoLiquidacionDiseno = $this->m_pqt_terminado->haveLiquidacionDiseno($itemplan, $idEstacion);
                IF($datoLiquidacionDiseno!=NULL){
                    if($datoLiquidacionDiseno['requiere_licencia']!=2){
						$hasPoLicenciaActivo = $this->m_pqt_terminado->hasPoLicenciaActivo($itemplan);
                        if($datoLiquidacionDiseno['liquido_licencia']==1 && $hasPoLicenciaActivo == 0){
                            if(!in_array($row->idActividad, $arrayActividades)){
                                $dataCMO['idActividad']      = $row->idActividad;
                                $dataCMO['baremo']           = $row->baremo;
                                $dataCMO['costo']            = $row->costo;
                                $dataCMO['cantidad_inicial'] = $row->cantFactorPlanificado;
                                $dataCMO['monto_inicial']    = $row->total;
                                $dataCMO['cantidad_final']   = $row->cantFactorPlanificado;
                                $dataCMO['monto_final']      = $row->total;
                                array_push($arrayActividades, $row->idActividad);//metemos idActividad
                                array_push($partidasOutPut, $dataCMO);
                            }
                        }
                    }
                }
            }else if($row->id_tipo_partida == 3 ||$row->id_tipo_partida == 4 || $row->id_tipo_partida == 7 ||$row->id_tipo_partida == 8){#TENDIDO Y EMPALMADOR FO Y COAXIAL
                $mensaje_texto = 'Estacion no Liquidada.';
                $datoLiquidacionEstacion =  $this->m_pqt_terminado->haveEstaLiquidada($itemplan, $idEstacion);
                IF($datoLiquidacionEstacion!=NULL){
                    if($datoLiquidacionEstacion['porcentaje']=='100'){//si esta al 100% se paga
                        if(!in_array($row->idActividad, $arrayActividades)){
                            $dataCMO['idActividad']      = $row->idActividad;
                            $dataCMO['baremo']           = $row->baremo;
                            $dataCMO['costo']            = $row->costo;
                            $dataCMO['cantidad_inicial'] = $row->cantFactorPlanificado;
                            $dataCMO['monto_inicial']    = $row->total;
                            $dataCMO['cantidad_final']   = $row->cantFactorPlanificado;
                            $dataCMO['monto_final']      = $row->total;
                            array_push($arrayActividades, $row->idActividad);//metemos idActividad
                            array_push($partidasOutPut, $dataCMO);
                        }
                    }
                }
            }//CUANDO SE LE PAGABA LA FUENTE Y ENERGIA??? EN COAXIAL??
            else if($row->id_tipo_partida == 9){#FUENTE Y ENERGIA
                $mensaje_texto = 'Estacion Fuente o Energia No liquidadas.';
                $datoLiquidacionEstacion =  $this->m_pqt_terminado->haveEstaLiquidada($itemplan, ID_ESTACION_FUENTE);
                IF($datoLiquidacionEstacion!=NULL){
                    if($datoLiquidacionEstacion['porcentaje']=='100'){//si esta al 100% se paga
                        if(!in_array($row->idActividad, $arrayActividades)){
                            $dataCMO['idActividad']      = $row->idActividad;
                            $dataCMO['baremo']           = $row->baremo;
                            $dataCMO['costo']            = $row->costo;
                            $dataCMO['cantidad_inicial'] = $row->cantFactorPlanificado;
                            $dataCMO['monto_inicial']    = $row->total;
                            $dataCMO['cantidad_final']   = $row->cantFactorPlanificado;
                            $dataCMO['monto_final']      = $row->total;
                            array_push($arrayActividades, $row->idActividad);//metemos idActividad
                            array_push($partidasOutPut, $dataCMO);
                        }
                    }
                }else{
                    $datoLiquidacionEstacion =  $this->m_pqt_terminado->haveEstaLiquidada($itemplan, ID_ESTACION_ENERGIA);
                    IF($datoLiquidacionEstacion!=NULL){
                        if($datoLiquidacionEstacion['porcentaje']=='100'){//si esta al 100% se paga
                            if(!in_array($row->idActividad, $arrayActividades)){
                                $dataCMO['idActividad']      = $row->idActividad;
                                $dataCMO['baremo']           = $row->baremo;
                                $dataCMO['costo']            = $row->costo;
                                $dataCMO['cantidad_inicial'] = $row->cantFactorPlanificado;
                                $dataCMO['monto_inicial']    = $row->total;
                                $dataCMO['cantidad_final']   = $row->cantFactorPlanificado;
                                $dataCMO['monto_final']      = $row->total;
                                array_push($arrayActividades, $row->idActividad);//metemos idActividad
                                array_push($partidasOutPut, $dataCMO);
                            }
                        }
                    }
                }
            }
        }   
       
        return $partidasOutPut;
    }
    
    function getPartidasAdicionalesV2($itemplan, $idEstacion, $poValidada, $poPndtValidacion, $canCreateMarteriales, $codigo_po){
    
        $totalPartAdic = 0;
        $totalPartAdicNoVali = 0;
       
    
        $html = '<table class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th></th>
                            <th>Codigo</th>
                            <th>Descripcion</th>
                            <th>Baremo</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>Total</th>
                        </tr></thead>
                    <tbody>';
        
        /**iniciamos partidas tmp**/
        $partidasAdicionales = $this->m_pqt_terminado->getPartidasAdicionalesTmpByItemplanEstacionv2($codigo_po);
        foreach ($partidasAdicionales as $row){
           
            $html .= '<tr>
                        <th>'.(($row->codigo == '69901-2') ? '<a class="getMateEsta" data-esta="'.$idEstacion.'" data-item="'.$itemplan.'"><i title="buscar" style="color:#A4A4A4" class="zmdi zmdi-hc-2x zmdi-eye"></i></a>' : '').'</th>
                        <th>'.$row->codigo.'</th>
                        <th>'.$row->descripcion.'</th>
                        <th>'.$row->baremo.'</th>
                        <th>'.$row->cantidad_final.'</th>
                        <th>'.$row->monto_final.'</th>
                        <th>'.$row->total_form.'</th>';    
            $html .= '</tr>';
             
            $totalPartAdicNoVali = $totalPartAdicNoVali + $row->monto_final;
        }
    
        $html .= '<tr style="color: white;background: var(--verde_telefonica);">
                        <th></th>
                        <th style="font-weight: bolder;">TOTAL</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th style="font-weight: bolder;">'.number_format($totalPartAdicNoVali,2).'</th>';
        $html .='</tr>
                    </tbody>
                </table>';
        
        
        $output['html']             = $html;
        $output['costo_total'] = $totalPartAdicNoVali;
        return $output;
      
    }
    
    
    function getPartidasPaquetizadasv2($idEstacion, $idSubProyecto, $idEmpresaColab, $isLima, $itemplan){
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
                //$para todos las anclas czavala 360.06.2020
                $hasPoDisenoActivo = $this->m_pqt_terminado->hasPoDisenoActivo($itemplan);
                if($hasPoDisenoActivo > 0){
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
        $outPut['html'] =   $html;
        $outPut['costo_total']  = $total_pqt;
        return $outPut;
    }
    
    public function validarPropuestaNivel2(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;
            if($idUsuario   !=     null){
                $itemplan       = $this->input->post('itemplan');
                $idEstacion     = $this->input->post('idEstacion');
				$arraySolicitud         = array();
                $arrayItemXSolicitud    = array();
                $infoCreateSol      = $this->m_pqt_terminado->getInfoSolCreacionByItem($itemplan);//getinfo solicitud de creacion
                if($infoCreateSol    ==  null){//si no tiene sol creacion atendida realizamos lo siguiente.

                    $infoPoPqt          = $this->m_pqt_terminado->getInfoPoPQT($itemplan, $idEstacion);//po paquetizada
                    if($infoPoPqt    ==  null){
                        throw new Exception('No se pudo obtener el codigo po PQT de la obra.');
                    }
                    $arrayPoUpdate = array();
                    $arrayPoInserLogPo = array();
                    $listaPosPdtValidar = array();
                    if($idEstacion == ID_ESTACION_FO){
                        $listaPosPdtValidar = $this->m_pqt_terminado->getPOToValidateToFOByItemplan($itemplan);
                    }else if($idEstacion    ==  ID_ESTACION_COAXIAL){
                        $listaPosPdtValidar = $this->m_pqt_terminado->getPOToValidateToCOAXByItemplan($itemplan);
                    }
                    if($listaPosPdtValidar!=null){
                        foreach($listaPosPdtValidar as $po_val){
                            $dataLogPO = array(
                                'codigo_po'         =>  $po_val->codigo_po,
                                'itemplan'          =>  $itemplan,
                                'idUsuario'         =>  $idUsuario,
                                'fecha_registro'    =>  $this->fechaActual(),
                                'idPoestado'        =>  PO_VALIDADO,
                                'controlador'       =>  'VALIDACION PQT 2D NIVEL'
                            );
                            array_push($arrayPoInserLogPo, $dataLogPO);
                            $dataUpdatePo = array(
                                'codigo_po'     => $po_val->codigo_po,
                                'itemplan'      => $po_val->itemplan,
                                'estado_po'     => PO_VALIDADO
                            );
                            array_push($arrayPoUpdate, $dataUpdatePo);
                        }
                    }
                    $dataUpdateSolicitud = array ('estado'            => 2,
                        'usua_val_nivel_2'  => $idUsuario,
                        'fec_val_nivel_2'   => $this->fechaActual(),
                        'itemplan'          =>  $itemplan,
                        'idEstacion'        =>  $idEstacion
                    );
                    
                    
                    $dataExpediente = array('estado_final'  => 'FINALIZADO',
                        'fecha_valida'  => $this->fechaActual() ,
                        'usuario_valida'=> $idUsuario);
                    
                    $data = $this->m_pqt_terminado->validarEstacionFOPqt2NivelsINoc($arrayPoInserLogPo, $itemplan, $arrayPoUpdate, $infoPoPqt['codigo_po'], $idEstacion, $dataUpdateSolicitud, $dataExpediente);                   
                
                }else{//SI YA CUENTA CON OC DE CREACION
                    
                    $infoCertiEdicionOC = $this->m_pqt_terminado->getDataToSolicitudEdicionCertiOC($itemplan);//costos mo
                    if($infoCertiEdicionOC    ==  null){
                        throw new Exception('No se pudo obtener los costos de MO para la obra.');
                    }
                    $infoPoPqt          = $this->m_pqt_terminado->getInfoPoPQT($itemplan, $idEstacion);//po paquetizada
                    if($infoPoPqt    ==  null){
                        throw new Exception('No se pudo obtener el codigo po PQT de la obra.');
                    }
                    //sol edicion
                    $codigo_solicitud   = $this->m_pqt_terminado->getNextCodigoSolicitud();//nuevo cod solicitud
                    if($codigo_solicitud    ==  null){
                        throw new Exception('No se pudo obtener el codigo de Solicitud refresque la pantalla y vuelva a intentarlo.');
                    }
                    
                    $solicitud_oc_edi_certi = array('codigo_solicitud'  => $codigo_solicitud,
                                                    'idEmpresaColab'    =>  $infoCreateSol['idEmpresaColab'],
                                                    'estado'            =>  1,//pendiente
                                                    'fecha_creacion'    =>  $this->fechaActual(),
                                                    'idSubProyecto'     =>  $infoCreateSol['idSubProyecto'],
                                                    'plan'              =>  $infoCreateSol['plan'],
                                                    'pep1'              =>  $infoCreateSol['pep1'],
                                                    'pep2'              =>  $infoCreateSol['pep2'],
                                                    'cesta'             =>  $infoCreateSol['cesta'],
                                                    'orden_compra'      =>  $infoCreateSol['orden_compra'],
                                                    'estatus_solicitud' => 'NUEVO',
                                                    'tipo_solicitud'    =>  2//tipo edicion
                        
                    );
                    array_push($arraySolicitud, $solicitud_oc_edi_certi);
                    
                    $item_x_sol = array('itemplan'              =>   $itemplan,
                                        'codigo_solicitud_oc'   => $codigo_solicitud,
                                        'costo_unitario_mo'     => $infoCertiEdicionOC['total']
                    );
                    
                    array_push($arrayItemXSolicitud, $item_x_sol);
                    //sol certificacion
                    $codigo_solicitud_2   = $this->m_pqt_terminado->getNextCodigoSolicitud();//nuevo cod solicitud
                    if($codigo_solicitud_2    ==  null){
                        throw new Exception('No se pudo obtener el codigo de Solicitud refresque la pantalla y vuelva a intentarlo.');
                    }
                    
                    $solicitud_oc_edi_certi_2 = array('codigo_solicitud'  => $codigo_solicitud_2,
                                                    'idEmpresaColab'    =>  $infoCreateSol['idEmpresaColab'],
                                                    'estado'            =>  4,//pendiente
                                                    'fecha_creacion'    =>  $this->fechaActual(),
                                                    'idSubProyecto'     =>  $infoCreateSol['idSubProyecto'],
                                                    'plan'              =>  $infoCreateSol['plan'],
                                                    'pep1'              =>  $infoCreateSol['pep1'],
                                                    'pep2'              =>  $infoCreateSol['pep2'],
                                                    'cesta'             =>  $infoCreateSol['cesta'],
                                                    'orden_compra'      =>  $infoCreateSol['orden_compra'],
                                                    'estatus_solicitud' => 'NUEVO',
                                                    'tipo_solicitud'    =>  3//tipo certificacion
                                                
                                                );
                    array_push($arraySolicitud, $solicitud_oc_edi_certi_2);
                    
                    $item_x_sol_2 = array('itemplan'            =>  $itemplan,
                                        'codigo_solicitud_oc'   =>  $codigo_solicitud_2,
                                        'costo_unitario_mo'     =>  $infoCertiEdicionOC['total']
                                    );                                
                    array_push($arrayItemXSolicitud, $item_x_sol_2);
                    /**HASTA AQUI cambio edl 30.06.2020 czavala **/
    				
                    if($infoCreateSol['idEstadoPlan']==ID_ESTADO_TERMINADO){//pasar a en certificacion
                        $updatePlanObra = array('idEstadoPlan'  => ID_ESTADO_EN_CERTIFICACION,
                                                'usu_upd'       => $idUsuario,
                                                'fecha_upd'     => $this->fechaActual(),
                                                'descripcion'   => 'VALIDACION PQT 2D NIVEL',
                                                'solicitud_oc_certi' => $codigo_solicitud_2,
                                                'costo_unitario_mo_certi' => $infoCertiEdicionOC['total'],
                                                'solicitud_oc_dev' => $codigo_solicitud,
                                                'costo_devolucion'  =>  $infoCertiEdicionOC['total']);
                    }else{
                        $updatePlanObra = array('solicitud_oc_certi' => $codigo_solicitud_2,
                                                'costo_unitario_mo_certi' => $infoCertiEdicionOC['total'],
                                                'solicitud_oc_dev' => $codigo_solicitud,
                                                'costo_devolucion'  =>  $infoCertiEdicionOC['total']);                    
                    }
                    
                    $arrayPoUpdate = array();
                    $arrayPoInserLogPo = array();
                    $listaPosPdtValidar = array();
                    if($idEstacion == ID_ESTACION_FO){
                        $listaPosPdtValidar = $this->m_pqt_terminado->getPOToValidateToFOByItemplan($itemplan);
                    }else if($idEstacion    ==  ID_ESTACION_COAXIAL){
                        $listaPosPdtValidar = $this->m_pqt_terminado->getPOToValidateToCOAXByItemplan($itemplan);
                    }
                    if($listaPosPdtValidar!=null){
                        foreach($listaPosPdtValidar as $po_val){
    						$dataLogPO = array(
                                'codigo_po'         =>  $po_val->codigo_po,
                                'itemplan'          =>  $itemplan,
                                'idUsuario'         =>  $idUsuario,
                                'fecha_registro'    =>  $this->fechaActual(),
                                'idPoestado'        =>  PO_VALIDADO,
                                'controlador'       =>  'VALIDACION PQT 2D NIVEL'
                            );
                            array_push($arrayPoInserLogPo, $dataLogPO);
                            $dataUpdatePo = array(
                                'codigo_po'     => $po_val->codigo_po,
                                'itemplan'      => $po_val->itemplan,
                                'estado_po'     => PO_VALIDADO
                            );
                            array_push($arrayPoUpdate, $dataUpdatePo);			
    					}           
                    }
                    $dataUpdateSolicitud = array ('estado'            => 2,
                        'usua_val_nivel_2'  => $idUsuario,
                        'fec_val_nivel_2'   => $this->fechaActual(),
                        'itemplan'          =>  $itemplan,
                        'idEstacion'        =>  $idEstacion
                    );
                    
                    
                    $dataExpediente = array('estado_final'  => 'FINALIZADO',
                                            'fecha_valida'  => $this->fechaActual() ,
                                            'usuario_valida'=> $idUsuario);
                    
                    $data = $this->m_pqt_terminado->validarEstacionFOPqt2Nivel($arraySolicitud, $arrayItemXSolicitud, $updatePlanObra, $itemplan, $arrayPoInserLogPo, $arrayPoUpdate, $infoPoPqt['codigo_po'], $idEstacion, $dataUpdateSolicitud, $dataExpediente);
                    
                }
            }else{
                throw new Exception('Su sesion expiro, porfavor vuelva a logearse.');
            }
             
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
	
	public function sendValidateRutas(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;
            if($idUsuario   !=     null){
                $itemplan         = $this->input->post('itemplan');
                $idEstacion       = $this->input->post('idEstacion');
                $costo_inicial    = $this->input->post('costo_total');
    
                $pathItemplan = 'uploads/itemplan_expediente/'.$itemplan;
                if (!is_dir($pathItemplan)) {
                    mkdir ($pathItemplan, 0777);
                }
    
                $descEstacion = $this->m_utils->getEstaciondescByIdEstacion($idEstacion);
                //DE NO EXISTIR LA CARPETA ITEMPLAN ESTACION LA CREAMOS
                $pathItemEstacion = $pathItemplan.'/'.$descEstacion;
                if (!is_dir($pathItemEstacion)) {
                    mkdir ($pathItemEstacion, 0777);
                }
    
                $uploadfile1 = $pathItemEstacion.'/'. basename($_FILES['fileEvi']['name']);
    
                if (move_uploaded_file($_FILES['fileEvi']['tmp_name'], $uploadfile1)) {                    
    
                    $dataSolValidacion = array('fec_registro'       =>  $this->fechaActual(),
                        'usua_registro'     =>  $this->session->userdata('idPersonaSession'),
                        'estado'            =>  0,
                        'itemplan'          =>  $itemplan,
                        'idEstacion'        =>  $idEstacion,
                        'costo_inicial'     =>  $costo_inicial,
                        'activo'            =>  1
                    );
    
                    $data_expediente = array('itemplan'     =>  $itemplan,
                        'fecha'        =>  $this->fechaActual(),
                        'comentario'   =>  'VALIDACION PQT',
                        'usuario'      =>  $this->session->userdata('usernameSession'),
                        'estado'       =>  'ACTIVO',
                        'estado_final' =>  'PENDIENTE',
                        'path_expediente'  => $uploadfile1,
                        'idEstacion'   =>  $idEstacion
                    );
                   
                    $data = $this->m_pqt_terminado->sendValidarRutas($dataSolValidacion, $data_expediente);
                    $data['error']       = EXIT_SUCCESS;
                }else{
                    throw new Exception('No se puede cargar el archivo expediente, refresque la pantalla y vuelva a intentarlo.');
                }
            }else{
                throw new Exception('Su sesion expiro, porfavor vuelva a logearse.');
            }
             
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
	
	public function validarPropuestaNivel2Ruta(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;
            if($idUsuario   !=     null){
                $itemplan       = $this->input->post('itemplan');
                $idEstacion     = $this->input->post('idEstacion');
                $arraySolicitud         = array();
                $arrayItemXSolicitud    = array();
                $infoCreateSol      = $this->m_pqt_terminado->getInfoSolCreacionByItem($itemplan);//getinfo solicitud de creacion
                if($infoCreateSol    ==  null){
                    
                    $arrayPoUpdate = array();
                    $arrayPoInserLogPo = array();
                    $listaPosPdtValidar = array();
                    $listaPosPdtValidar = $this->m_pqt_terminado->getPOToValidateToItemplanRuta($itemplan, $idEstacion);
                    if($listaPosPdtValidar!=null){
                        foreach($listaPosPdtValidar as $po_val){
                            $dataLogPO = array(
                                'codigo_po'         =>  $po_val->codigo_po,
                                'itemplan'          =>  $itemplan,
                                'idUsuario'         =>  $idUsuario,
                                'fecha_registro'    =>  $this->fechaActual(),
                                'idPoestado'        =>  PO_VALIDADO,
                                'controlador'       =>  'VALIDACION PQT 2D NIVEL'
                            );
                            array_push($arrayPoInserLogPo, $dataLogPO);
                            $dataUpdatePo = array(
                                'codigo_po'     => $po_val->codigo_po,
                                'itemplan'      => $po_val->itemplan,
                                'estado_po'     => PO_VALIDADO
                            );
                            array_push($arrayPoUpdate, $dataUpdatePo);
                        }
                    }
                    
                    $dataUpdateSolicitud = array (  'estado'            => 2,
                        'usua_val_nivel_2'  => $idUsuario,
                        'fec_val_nivel_2'   => $this->fechaActual(),
                        'itemplan'          =>  $itemplan,
                        'idEstacion'        =>  $idEstacion
                    );
                    
                    
                    $dataExpediente = array('estado_final'  => 'FINALIZADO',
                        'fecha_valida'  => $this->fechaActual() ,
                        'usuario_valida'=> $idUsuario);
                    
                    $data = $this->m_pqt_terminado->validarEstacionFOPqt2NivelRutasSinOc($itemplan, $arrayPoInserLogPo, $arrayPoUpdate, $idEstacion, $dataUpdateSolicitud, $dataExpediente);
                }else{
                    $infoCertiEdicionOC = $this->m_pqt_terminado->getDataToSolicitudEdicionCertiOC($itemplan);//costos mo
                    if($infoCertiEdicionOC    ==  null){
                        throw new Exception('No se pudo obtener los costos de MO para la obra.');
                    }                
                    //sol edicion
                    $codigo_solicitud   = $this->m_pqt_terminado->getNextCodigoSolicitud();//nuevo cod solicitud
                    if($codigo_solicitud    ==  null){
                        throw new Exception('No se pudo obtener el codigo de Solicitud refresque la pantalla y vuelva a intentarlo.');
                    }
        
                    $solicitud_oc_edi_certi = array('codigo_solicitud'  => $codigo_solicitud,
                        'idEmpresaColab'    =>  $infoCreateSol['idEmpresaColab'],
                        'estado'            =>  1,//pendiente
                        'fecha_creacion'    =>  $this->fechaActual(),
                        'idSubProyecto'     =>  $infoCreateSol['idSubProyecto'],
                        'plan'              =>  $infoCreateSol['plan'],
                        'pep1'              =>  $infoCreateSol['pep1'],
                        'pep2'              =>  $infoCreateSol['pep2'],
                        'cesta'             =>  $infoCreateSol['cesta'],
                        'orden_compra'      =>  $infoCreateSol['orden_compra'],
                        'estatus_solicitud' => 'NUEVO',
                        'tipo_solicitud'    =>  2//tipo edicion
        
                    );
                    array_push($arraySolicitud, $solicitud_oc_edi_certi);
        
                    $item_x_sol = array('itemplan'              =>   $itemplan,
                        'codigo_solicitud_oc'   => $codigo_solicitud,
                        'costo_unitario_mo'     => $infoCertiEdicionOC['total']
                    );
        
                    array_push($arrayItemXSolicitud, $item_x_sol);
                    //sol certificacion
                    $codigo_solicitud_2   = $this->m_pqt_terminado->getNextCodigoSolicitud();//nuevo cod solicitud
                    if($codigo_solicitud_2    ==  null){
                        throw new Exception('No se pudo obtener el codigo de Solicitud refresque la pantalla y vuelva a intentarlo.');
                    }
        
                    $solicitud_oc_edi_certi_2 = array('codigo_solicitud'  => $codigo_solicitud_2,
                        'idEmpresaColab'    =>  $infoCreateSol['idEmpresaColab'],
                        'estado'            =>  4,//pendiente
                        'fecha_creacion'    =>  $this->fechaActual(),
                        'idSubProyecto'     =>  $infoCreateSol['idSubProyecto'],
                        'plan'              =>  $infoCreateSol['plan'],
                        'pep1'              =>  $infoCreateSol['pep1'],
                        'pep2'              =>  $infoCreateSol['pep2'],
                        'cesta'             =>  $infoCreateSol['cesta'],
                        'orden_compra'      =>  $infoCreateSol['orden_compra'],
                        'estatus_solicitud' => 'NUEVO',
                        'tipo_solicitud'    =>  3//tipo certificacion
        
                    );
                    array_push($arraySolicitud, $solicitud_oc_edi_certi_2);
        
                    $item_x_sol_2 = array('itemplan'            =>  $itemplan,
                        'codigo_solicitud_oc'   =>  $codigo_solicitud_2,
                        'costo_unitario_mo'     =>  $infoCertiEdicionOC['total']
                    );
                    array_push($arrayItemXSolicitud, $item_x_sol_2);
                    /**HASTA AQUI cambio edl 30.06.2020 czavala **/
        
                    if($infoCreateSol['idEstadoPlan']==ID_ESTADO_TERMINADO){//pasar a en certificacion
                        $updatePlanObra = array('idEstadoPlan'  => ID_ESTADO_EN_CERTIFICACION,
                            'usu_upd'       => $idUsuario,
                            'fecha_upd'     => $this->fechaActual(),
                            'descripcion'   => 'VALIDACION PQT 2D NIVEL',
                            'solicitud_oc_certi' => $codigo_solicitud_2,
                            'costo_unitario_mo_certi' => $infoCertiEdicionOC['total'],
                            'solicitud_oc_dev' => $codigo_solicitud,
                            'costo_devolucion'  =>  $infoCertiEdicionOC['total']);
                    }else{
                        $updatePlanObra = array(
                            'solicitud_oc_certi' => $codigo_solicitud_2,
                            'costo_unitario_mo_certi' => $infoCertiEdicionOC['total'],
                            'solicitud_oc_dev' => $codigo_solicitud,
                            'costo_devolucion'  =>  $infoCertiEdicionOC['total']);
                        }
                    
        
                    $arrayPoUpdate = array();
                    $arrayPoInserLogPo = array();
                    $listaPosPdtValidar = array();
                    $listaPosPdtValidar = $this->m_pqt_terminado->getPOToValidateToItemplanRuta($itemplan, $idEstacion);                
                    if($listaPosPdtValidar!=null){
                        foreach($listaPosPdtValidar as $po_val){
                            $dataLogPO = array(
                                'codigo_po'         =>  $po_val->codigo_po,
                                'itemplan'          =>  $itemplan,
                                'idUsuario'         =>  $idUsuario,
                                'fecha_registro'    =>  $this->fechaActual(),
                                'idPoestado'        =>  PO_VALIDADO,
                                'controlador'       =>  'VALIDACION PQT 2D NIVEL'
                            );
                            array_push($arrayPoInserLogPo, $dataLogPO);
                            $dataUpdatePo = array(
                                'codigo_po'     => $po_val->codigo_po,
                                'itemplan'      => $po_val->itemplan,
                                'estado_po'     => PO_VALIDADO
                            );
                            array_push($arrayPoUpdate, $dataUpdatePo);
                        }
                    }
        
                    $dataUpdateSolicitud = array (  'estado'            => 2,
                        'usua_val_nivel_2'  => $idUsuario,
                        'fec_val_nivel_2'   => $this->fechaActual(),
                        'itemplan'          =>  $itemplan,
                        'idEstacion'        =>  $idEstacion
                    );
        
        
                    $dataExpediente = array('estado_final'  => 'FINALIZADO',
                        'fecha_valida'  => $this->fechaActual() ,
                        'usuario_valida'=> $idUsuario);
        
                    $data = $this->m_pqt_terminado->validarEstacionFOPqt2NivelRutas($arraySolicitud, $arrayItemXSolicitud, $updatePlanObra, $itemplan, $arrayPoInserLogPo, $arrayPoUpdate, $idEstacion, $dataUpdateSolicitud, $dataExpediente);
                }
            }else{
                throw new Exception('Su sesion expiro, porfavor vuelva a logearse.');
            }
             
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
	
	public function sendValidateNoPqt(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;
            if($idUsuario   !=     null){
                $itemplan         = $this->input->post('itemplan');
                //$idEstacion       = $this->input->post('idEstacion');
                $costo_inicial    = $this->input->post('costo_total');
    
                $pathItemplan = 'uploads/itemplan_expediente/'.$itemplan;
                if (!is_dir($pathItemplan)) {
                    mkdir ($pathItemplan, 0777);
                }
    
                //$descEstacion = $this->m_utils->getEstaciondescByIdEstacion($idEstacion);
                //DE NO EXISTIR LA CARPETA ITEMPLAN ESTACION LA CREAMOS
                $pathItemEstacion = $pathItemplan;
                if (!is_dir($pathItemEstacion)) {
                    mkdir ($pathItemEstacion, 0777);
                }
    
                $uploadfile1 = $pathItemEstacion.'/'. basename($_FILES['fileEvi']['name']);
    
                if (move_uploaded_file($_FILES['fileEvi']['tmp_name'], $uploadfile1)) {
    
                    $dataSolValidacion = array('fec_registro'       =>  $this->fechaActual(),
                        'usua_registro'     =>  $this->session->userdata('idPersonaSession'),
                        'estado'            =>  0,
                        'itemplan'          =>  $itemplan,
                        'idEstacion'        =>  null,
                        'costo_inicial'     =>  $costo_inicial,
                        'activo'            =>  1
                    );
    
                    $data_expediente = array('itemplan'     =>  $itemplan,
                        'fecha'        =>  $this->fechaActual(),
                        'comentario'   =>  'VALIDACION PQT',
                        'usuario'      =>  $this->session->userdata('usernameSession'),
                        'estado'       =>  'ACTIVO',
                        'estado_final' =>  'PENDIENTE',
                        'path_expediente'  => $uploadfile1,
                        'idEstacion'   =>  null
                    );
                     
                    $data = $this->m_pqt_terminado->sendValidarRutas($dataSolValidacion, $data_expediente);
                    $data['error']       = EXIT_SUCCESS;
                }else{
                    throw new Exception('No se puede cargar el archivo expediente, refresque la pantalla y vuelva a intentarlo.');
                }
            }else{
                throw new Exception('Su sesion expiro, porfavor vuelva a logearse.');
            }
             
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
	
	public function validarPropuestaNivel2NoPqt(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;
            if($idUsuario   !=     null){
                $itemplan       = $this->input->post('itemplan');
                $idEstacion     = $this->input->post('idEstacion');
                $arraySolicitud         = array();
                $arrayItemXSolicitud    = array();
                
                $infoItemplan = $this->m_utils->getInfoItemplanWhitSubProyecto($itemplan);//czavala
                if($infoItemplan['flg_opex']==2){//OPEX   
                   $infoCreateSol      = $this->m_pqt_terminado->getInfoSolCreacionByItemOPEX($itemplan);//getinfo solicitud de creacion Opex
                 }else{
                   $infoCreateSol      = $this->m_pqt_terminado->getInfoSolCreacionByItem($itemplan);//getinfo solicitud de creacion
                }
               
                
                if($infoCreateSol    ==  null){
    
                    $arrayPoUpdate = array();
                    $arrayPoInserLogPo = array();
                    $listaPosPdtValidar = array();
                    $listaPosPdtValidar = $this->m_pqt_terminado->getPOToValidateToItemplanRuta($itemplan, $idEstacion);
                    if($listaPosPdtValidar!=null){
                        foreach($listaPosPdtValidar as $po_val){
                            $dataLogPO = array(
                                'codigo_po'         =>  $po_val->codigo_po,
                                'itemplan'          =>  $itemplan,
                                'idUsuario'         =>  $idUsuario,
                                'fecha_registro'    =>  $this->fechaActual(),
                                'idPoestado'        =>  PO_VALIDADO,
                                'controlador'       =>  'VALIDACION PQT 2D NIVEL'
                            );
                            array_push($arrayPoInserLogPo, $dataLogPO);
                            $dataUpdatePo = array(
                                'codigo_po'     => $po_val->codigo_po,
                                'itemplan'      => $po_val->itemplan,
                                'estado_po'     => PO_VALIDADO
                            );
                            array_push($arrayPoUpdate, $dataUpdatePo);
                        }
                    }
    
                    $dataUpdateSolicitud = array (  'estado'            => 2,
                                                    'usua_val_nivel_2'  => $idUsuario,
                                                    'fec_val_nivel_2'   => $this->fechaActual(),
                                                    'itemplan'          =>  $itemplan,
                                                    'idEstacion'        =>  $idEstacion
                    );
    
    
                    $dataExpediente = array('estado_final'  => 'FINALIZADO',
                                            'fecha_valida'  => $this->fechaActual() ,
                                            'usuario_valida'=> $idUsuario);
    
                    $data = $this->m_pqt_terminado->validarEstacionFOPqt2NivelRutasSinOc($itemplan, $arrayPoInserLogPo, $arrayPoUpdate, $idEstacion, $dataUpdateSolicitud, $dataExpediente);
                }else{
                    if($infoItemplan['flg_opex']==2){//OPEX
                        //logica de creacionde oc editar y certificar opex.
                        $infoCertiEdicionOC = $this->m_pqt_terminado->getDataToSolicitudEdicionCertiOC($itemplan);//costos mo
                        if($infoCertiEdicionOC    ==  null){
                            throw new Exception('No se pudo obtener los costos de MO para la obra.');
                        }
                        //sol edicion
                        $codigo_solicitud   = $this->m_pqt_terminado->getNextCodigoSolicitud();//nuevo cod solicitud
                        if($codigo_solicitud    ==  null){
                            throw new Exception('No se pudo obtener el codigo de Solicitud refresque la pantalla y vuelva a intentarlo.');
                        }
                        
                        $solicitud_oc_edi_certi = array('codigo_solicitud'  => $codigo_solicitud,
                                                        'idEmpresaColab'    =>  $infoCreateSol['idEmpresaColab'],
                                                        'estado'            =>  1,//pendiente
                                                        'fecha_creacion'    =>  $this->fechaActual(),
                                                        'idSubProyecto'     =>  $infoCreateSol['idSubProyecto'],
                                                        'plan'              =>  $infoCreateSol['plan'],
                                                        'pep1'              =>  $infoCreateSol['pep1'],
                                                        'pep2'              =>  $infoCreateSol['pep2'],
                                                        'cesta'             =>  $infoCreateSol['cesta'],
                                                        'orden_compra'      =>  $infoCreateSol['orden_compra'],
                                                        'idCuetnaOpex'      =>  $infoCreateSol['idCuetnaOpex'],
                                                        'tipo_solicitud'    =>  2//tipo edicion
                                                    
                                                    );
                        array_push($arraySolicitud, $solicitud_oc_edi_certi);
                       
                        $item_x_sol = array('itemplan'              => $itemplan,
                                            'codigo_solicitud_oc'   => $codigo_solicitud,
                                            'costo_unitario_mo'     => $infoCertiEdicionOC['total']
                                        );
                       
                        array_push($arrayItemXSolicitud, $item_x_sol);
                         
                        //sol certificacion
                        $codigo_solicitud_2   = $this->m_pqt_terminado->getNextCodigoSolicitud();//nuevo cod solicitud
                        if($codigo_solicitud_2    ==  null){
                            throw new Exception('No se pudo obtener el codigo de Solicitud refresque la pantalla y vuelva a intentarlo.');
                        }
                        
                        $solicitud_oc_edi_certi_2 = array('codigo_solicitud'  => $codigo_solicitud_2,
                            'idEmpresaColab'    =>  $infoCreateSol['idEmpresaColab'],
                            'estado'            =>  4,//pendiente
                            'fecha_creacion'    =>  $this->fechaActual(),
                            'idSubProyecto'     =>  $infoCreateSol['idSubProyecto'],
                            'plan'              =>  $infoCreateSol['plan'],
                            'pep1'              =>  $infoCreateSol['pep1'],
                            'pep2'              =>  $infoCreateSol['pep2'],
                            'cesta'             =>  $infoCreateSol['cesta'],
                            'orden_compra'      =>  $infoCreateSol['orden_compra'],
                            'idCuetnaOpex'      =>  $infoCreateSol['idCuetnaOpex'],
                            'tipo_solicitud'    =>  3//tipo certificacion
                        
                        );
                        array_push($arraySolicitud, $solicitud_oc_edi_certi_2);
                        
                        $item_x_sol_2 = array('itemplan'            =>  $itemplan,
                            'codigo_solicitud_oc'   =>  $codigo_solicitud_2,
                            'costo_unitario_mo'     =>  $infoCertiEdicionOC['total']
                        );
                        array_push($arrayItemXSolicitud, $item_x_sol_2);
                        
                        /**HASTA AQUI cambio edl 30.06.2020 czavala **/
                        
                        if($infoCreateSol['idEstadoPlan']==ID_ESTADO_TERMINADO){//pasar a en certificacion
                            $updatePlanObra = array('idEstadoPlan'  => ID_ESTADO_EN_CERTIFICACION,
                                'usu_upd'       => $idUsuario,
                                'fecha_upd'     => $this->fechaActual(),
                                'descripcion'   => 'VALIDACION PQT 2D NIVEL',
                                'solicitud_oc_certi' => $codigo_solicitud_2,
                                'costo_unitario_mo_certi' => $infoCertiEdicionOC['total'],
                                'solicitud_oc_dev' => $codigo_solicitud,
                                'costo_devolucion'  =>  $infoCertiEdicionOC['total']);
                        }else{
                            $updatePlanObra = array(
                                'solicitud_oc_certi' => $codigo_solicitud_2,
                                'costo_unitario_mo_certi' => $infoCertiEdicionOC['total'],
                                'solicitud_oc_dev' => $codigo_solicitud,
                                'costo_devolucion'  =>  $infoCertiEdicionOC['total']);
                        }
                        
                        
                        $arrayPoUpdate = array();
                        $arrayPoInserLogPo = array();
                        $listaPosPdtValidar = array();
                        $listaPosPdtValidar = $this->m_pqt_terminado->getPOToValidateToItemplanNoPqt($itemplan);
                        if($listaPosPdtValidar!=null){
                            foreach($listaPosPdtValidar as $po_val){
                                $dataLogPO = array(
                                    'codigo_po'         =>  $po_val->codigo_po,
                                    'itemplan'          =>  $itemplan,
                                    'idUsuario'         =>  $idUsuario,
                                    'fecha_registro'    =>  $this->fechaActual(),
                                    'idPoestado'        =>  PO_VALIDADO,
                                    'controlador'       =>  'VALIDACION PQT 2D NIVEL'
                                );
                                array_push($arrayPoInserLogPo, $dataLogPO);
                                $dataUpdatePo = array(
                                    'codigo_po'     => $po_val->codigo_po,
                                    'itemplan'      => $po_val->itemplan,
                                    'estado_po'     => PO_VALIDADO
                                );
                                array_push($arrayPoUpdate, $dataUpdatePo);
                            }
                        }
                        
                        $dataUpdateSolicitud = array (  'estado'            => 2,
                            'usua_val_nivel_2'  => $idUsuario,
                            'fec_val_nivel_2'   => $this->fechaActual(),
                            'itemplan'          =>  $itemplan,
                            'idEstacion'        =>  $idEstacion
                        );
                        
                        
                        $dataExpediente = array('estado_final'  => 'FINALIZADO',
                            'fecha_valida'  => $this->fechaActual() ,
                            'usuario_valida'=> $idUsuario);
                        
                        $data = $this->m_pqt_terminado->validarEstacionFOPqt2NivelNopqtOpex($arraySolicitud, $arrayItemXSolicitud, $updatePlanObra, $itemplan, $arrayPoInserLogPo, $arrayPoUpdate, $idEstacion, $dataUpdateSolicitud, $dataExpediente);
                        
                        
                    }else{
                        $infoCertiEdicionOC = $this->m_pqt_terminado->getDataToSolicitudEdicionCertiOC($itemplan);//costos mo
                        if($infoCertiEdicionOC    ==  null){
                            throw new Exception('No se pudo obtener los costos de MO para la obra.');
                        }
                        //sol edicion
                        $codigo_solicitud   = $this->m_pqt_terminado->getNextCodigoSolicitud();//nuevo cod solicitud
                        if($codigo_solicitud    ==  null){
                            throw new Exception('No se pudo obtener el codigo de Solicitud refresque la pantalla y vuelva a intentarlo.');
                        }
        
                        $solicitud_oc_edi_certi = array('codigo_solicitud'  => $codigo_solicitud,
                            'idEmpresaColab'    =>  $infoCreateSol['idEmpresaColab'],
                            'estado'            =>  1,//pendiente
                            'fecha_creacion'    =>  $this->fechaActual(),
                            'idSubProyecto'     =>  $infoCreateSol['idSubProyecto'],
                            'plan'              =>  $infoCreateSol['plan'],
                            'pep1'              =>  $infoCreateSol['pep1'],
                            'pep2'              =>  $infoCreateSol['pep2'],
                            'cesta'             =>  $infoCreateSol['cesta'],
                            'orden_compra'      =>  $infoCreateSol['orden_compra'],
                            'estatus_solicitud' => 'NUEVO',
                            'tipo_solicitud'    =>  2//tipo edicion
        
                        );
                        array_push($arraySolicitud, $solicitud_oc_edi_certi);
        
                        $item_x_sol = array('itemplan'              =>   $itemplan,
                            'codigo_solicitud_oc'   => $codigo_solicitud,
                            'costo_unitario_mo'     => $infoCertiEdicionOC['total']
                        );
        
                        array_push($arrayItemXSolicitud, $item_x_sol);
                        //sol certificacion
                        $codigo_solicitud_2   = $this->m_pqt_terminado->getNextCodigoSolicitud();//nuevo cod solicitud
                        if($codigo_solicitud_2    ==  null){
                            throw new Exception('No se pudo obtener el codigo de Solicitud refresque la pantalla y vuelva a intentarlo.');
                        }
        
                        $solicitud_oc_edi_certi_2 = array('codigo_solicitud'  => $codigo_solicitud_2,
                            'idEmpresaColab'    =>  $infoCreateSol['idEmpresaColab'],
                            'estado'            =>  4,//pendiente
                            'fecha_creacion'    =>  $this->fechaActual(),
                            'idSubProyecto'     =>  $infoCreateSol['idSubProyecto'],
                            'plan'              =>  $infoCreateSol['plan'],
                            'pep1'              =>  $infoCreateSol['pep1'],
                            'pep2'              =>  $infoCreateSol['pep2'],
                            'cesta'             =>  $infoCreateSol['cesta'],
                            'orden_compra'      =>  $infoCreateSol['orden_compra'],
                            'estatus_solicitud' => 'NUEVO',
                            'tipo_solicitud'    =>  3//tipo certificacion
        
                        );
                        array_push($arraySolicitud, $solicitud_oc_edi_certi_2);
        
                        $item_x_sol_2 = array('itemplan'            =>  $itemplan,
                            'codigo_solicitud_oc'   =>  $codigo_solicitud_2,
                            'costo_unitario_mo'     =>  $infoCertiEdicionOC['total']
                        );
                        array_push($arrayItemXSolicitud, $item_x_sol_2);
                        /**HASTA AQUI cambio edl 30.06.2020 czavala **/
        
                        if($infoCreateSol['idEstadoPlan']==ID_ESTADO_TERMINADO){//pasar a en certificacion
                            $updatePlanObra = array('idEstadoPlan'  => ID_ESTADO_EN_CERTIFICACION,
                                'usu_upd'       => $idUsuario,
                                'fecha_upd'     => $this->fechaActual(),
                                'descripcion'   => 'VALIDACION PQT 2D NIVEL',
                                'solicitud_oc_certi' => $codigo_solicitud_2,
                                'costo_unitario_mo_certi' => $infoCertiEdicionOC['total'],
                                'solicitud_oc_dev' => $codigo_solicitud,
                                'costo_devolucion'  =>  $infoCertiEdicionOC['total']);
                        }else{
                            $updatePlanObra = array(
                                'solicitud_oc_certi' => $codigo_solicitud_2,
                                'costo_unitario_mo_certi' => $infoCertiEdicionOC['total'],
                                'solicitud_oc_dev' => $codigo_solicitud,
                                'costo_devolucion'  =>  $infoCertiEdicionOC['total']);
                        }
        
        
                        $arrayPoUpdate = array();
                        $arrayPoInserLogPo = array();
                        $listaPosPdtValidar = array();
                        $listaPosPdtValidar = $this->m_pqt_terminado->getPOToValidateToItemplanRuta($itemplan, $idEstacion);
                        if($listaPosPdtValidar!=null){
                            foreach($listaPosPdtValidar as $po_val){
                                $dataLogPO = array(
                                    'codigo_po'         =>  $po_val->codigo_po,
                                    'itemplan'          =>  $itemplan,
                                    'idUsuario'         =>  $idUsuario,
                                    'fecha_registro'    =>  $this->fechaActual(),
                                    'idPoestado'        =>  PO_VALIDADO,
                                    'controlador'       =>  'VALIDACION PQT 2D NIVEL'
                                );
                                array_push($arrayPoInserLogPo, $dataLogPO);
                                $dataUpdatePo = array(
                                    'codigo_po'     => $po_val->codigo_po,
                                    'itemplan'      => $po_val->itemplan,
                                    'estado_po'     => PO_VALIDADO
                                );
                                array_push($arrayPoUpdate, $dataUpdatePo);
                            }
                        }
        
                        $dataUpdateSolicitud = array (  'estado'            => 2,
                            'usua_val_nivel_2'  => $idUsuario,
                            'fec_val_nivel_2'   => $this->fechaActual(),
                            'itemplan'          =>  $itemplan,
                            'idEstacion'        =>  $idEstacion
                        );
        
        
                        $dataExpediente = array('estado_final'  => 'FINALIZADO',
                            'fecha_valida'  => $this->fechaActual() ,
                            'usuario_valida'=> $idUsuario); 
        
                        $data = $this->m_pqt_terminado->validarEstacionFOPqt2NivelNoPqtCapex($arraySolicitud, $arrayItemXSolicitud, $updatePlanObra, $itemplan, $arrayPoInserLogPo, $arrayPoUpdate, $idEstacion, $dataUpdateSolicitud, $dataExpediente);
                    }
                }
            }else{
                throw new Exception('Su sesion expiro, porfavor vuelva a logearse.');
            }
             
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
}