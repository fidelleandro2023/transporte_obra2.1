<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_terminado extends CI_Controller {

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
        $infoItm = $this->m_pqt_terminado->getInfoBasicToGeneratePartidasByItemplan($itemplan);
        if($infoItm==null){
            $html = '<h4 class="text-center" style="color:red">Excepcion detectada, comuniquese con soporte.</h4>';
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
                      $html .= '<div class="tab-pane '.$active.' fade show" id="tab'.$row->idEstacion.'" role="tabpanel">
                                    <p style="font-size: larger;color: #007bff;text-align: left;font-weight: bold;">Partidas Paquetizadas</p>      
                                    '.$this->getPartidasPaquetizadas($row->idEstacion, $infoItm['idSubProyecto'], $infoItm['idEmpresaColab'], $infoItm['isLima'], $itemplan);                 
                      
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
                              $msjEECC = '<h4 class="text-center" style="color:green">La estacion se encuentra Validada y se genero la PO: '.$hasPtrCreado['codigo_po'].'</h4>';
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
                      
                      if(count($listaPoOC)>0){
                          $has_pos_no_aceptados = 0;
                          
                          $html .= '<p style="font-size: larger;color: #007bff;text-align: left;font-weight: bold;">Obra Civil</p>';
                          $html .= '<table class="table table-bordered">
                                    <thead class="thead-default">
                                        <tr>
                                            <th></th>
                                            <th>CODIGO PO</th>
                                            <th>ESTADO PO</th>                                       
                                            <th>COSTO TOTAL</th>
                                       <tr></thead>                   
                        <tbody>';
                          foreach ($listaPoOC as $row2){
                   $html .=    '<tr>
                                    <th></th>
                                    <th>'.$row2->codigo_po.'</th>
                                    <th>'.$row2->estado.'</th>                               
                                    <th>'.number_format($row2->costo_total, 2).'</th>
        		              </tr>';
                            $estados_no_acepted = array(PO_REGISTRADO,PO_PREAPROBADO,PO_APROBADO,PO_PRECANCELADO);
                            if(in_array($row2->estado_po, $estados_no_acepted)){
                                $has_pos_no_aceptados++;
                            }
                          }
                         $html .= '   </tbody>
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
                           }else if($solicitudPartAdic['estado']==4){
                               $canCreateMarteriales = true;
                               $valEEcc = 'style="display:none"';
                               $valTDP = '';
                               $showButtons = true;
                               $msjEECC = '<h4 class="text-center" style="color:red">La Solicitud previa fue rechazada, puede ver el Motivo <a class="getRechazado" data-esta="'.$row->idEstacion.'" data-item="'.$itemplan.'" style="color:blue">Aqui <i title="Movimientos" style="color:#A4A4A4" class="zmdi zmdi-hc-1x zmdi-search"></i></a></h4>';                           
                           }
                       }
                       /*****************************/
                      $html.= $msjEECC;
                      if($showButtons){
                      $html .= '<div class="row">                                    
                                    <div class="col-sm-4 col-md-4" '.$valEEcc.'>
                                        <button data-idEs="'.$row->idEstacion.'" class="btn btn-success gpoMo" type="button">VALIDAR EECC</button>
                                    </div>
                                    <div class="col-sm-4 col-md-4" '.$valPartidasAdicionales.'>
                                        <button class="btn btn-success waves-effect" type="button" onclick="cargarPartidasAdic(\''.$itemplan.'\','.$row->idEstacion.')">ADIC. PARTIDAS</button>
                                    </div>
                                    <div class="col-sm-4 col-md-4" '.$valEEcc.'>
                                        <button class="btn btn-success sendCap" type="button"">OBSERVAR</button>
                                    </div>
                                    <div class="col-sm-4 col-md-4" '.$valTDP.'>
                                        <button data-idEs="'.$row->idEstacion.'" class="btn btn-success sendVali" type="button">ENVIAR A VALIDAR</button>
                                    </div>
                                </div>';
                      #fin botones
                      }
                      
                      $html .= '<p style="font-size: larger;color: #007bff;text-align: left;font-weight: bold;">Partidas Adicionales</p>      
                                    '.$this->getPartidasAdicionales($itemplan, $row->idEstacion, $poValidada, $poPndtValidacion, $canCreateMarteriales).'                          
                                </div>';      
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
                
                $arrayPartidasInsert = array();
                
                $infoItm = $this->m_pqt_terminado->getInfoBasicToGeneratePartidasByItemplan($itemplan);
                if($infoItm==null){
                    throw new Exception('Excepcion detectada, comuniquese con soporte.');
                }
                $listaPartidasToCreate = $this->getArrayPartidasToPO($idEstacion, $infoItm['idSubProyecto'], $infoItm['idEmpresaColab'], $infoItm['isLima'], $itemplan);
                
                if($listaPartidasToCreate!=null){
                   
                    $codigoPO = $this->m_utils->getCodigoPO($itemplan);
                    if ($codigoPO == null) {
                        throw new Exception('Hubo un error al generar el codigo PO ');
                    }

                    $costoTotalPOMO = 0;
                    foreach($listaPartidasToCreate as $datos){
                        $partidaInfo = array();
                        $partidaInfo = $datos;
                        $partidaInfo['codigo_po']  = $codigoPO;
                        array_push($arrayPartidasInsert, $partidaInfo);
                        $costoTotalPOMO = $costoTotalPOMO + $datos['monto_final'];                           
                    }
                    
                    $idEecc = $infoItm['idEmpresaColab'];
                    $from = 1;//harcodeamos operaciones
                    /*$infoEECC = $this->m_pqt_terminado->getEeccDisenoOperaByItemPlan($itemplan);
                    if($infoEECC!=null) {
                        if($infoEECC['jefatura'] == 'LIMA' && $idEstacion == 4) {
                            $idEecc = $infoEECC['idEmpresaColabFuente'];
                        } else {
                            if($from==1){
                                if($infoEECC['idEmpresaColab']!=null){
                                    $idEecc = $infoEECC['idEmpresaColab'];
                                }
                            }else if($from==2){
                                if($infoEECC['idEmpresaColabDiseno']!=null){
                                    $idEecc = $infoEECC['idEmpresaColabDiseno'];
                                }
                            }
                        }
                    }*/
                                        
                    $dataPO = array(
                        'itemplan'      => $itemplan,
                        'codigo_po'     => $codigoPO,
                        'estado_po'     => PO_VALIDADO, //ESTADO REGISTRADO
                        'idEstacion'    => $idEstacion,
                        'from'          => $from,
                        'costo_total'   => $costoTotalPOMO,
                        'idUsuario'     => $idUsuario,
                        'fechaRegistro' => $this->fechaActual(),
                        'estado_asig_grafo' => 0,
                        'flg_tipo_area' => 2,//MANO DE OBRA
                        'id_eecc_reg'   => $idEecc
                    );
                    log_message('error', 'eeee->'.print_r($dataPO, true));
                    
                    $dataLogPO = array();
                    $dataLogPO_tmp = array(
                        'codigo_po'         =>  $codigoPO,
                        'itemplan'          =>  $itemplan,
                        'idUsuario'         =>  $idUsuario,
                        'fecha_registro'    =>  $this->fechaActual(),
                        'idPoestado'        =>  PO_REGISTRADO,
                        'controlador'       =>  (($from ==  1) ? 'consulta' : 'diseno')
                    );
                    
                    array_push($dataLogPO, $dataLogPO_tmp);
                    
                    $dataLogPO_tmp = array(
                        'codigo_po'         =>  $codigoPO,
                        'itemplan'          =>  $itemplan,
                        'idUsuario'         =>  $idUsuario,
                        'fecha_registro'    =>  $this->fechaActual(),
                        'idPoestado'        =>  PO_VALIDADO,
                        'controlador'       =>  (($from ==  1) ? 'consulta' : 'diseno')
                    );
                    array_push($dataLogPO, $dataLogPO_tmp);
                    
                    log_message('error', '-------------------------');
                    $subProyectoEstacion = $this->m_pqt_terminado->getSubProyectoEstacionByItemplanEstacion($itemplan, $idEstacion);
                    log_message('error', print_r('4.$idEstacion:'.$idEstacion, true));
                    if($subProyectoEstacion ==  null){
                        throw new Exception('Hubo un error obtener el subproyecto - estacion');
                    }
                    $dataDetalleplan = array('itemPlan' =>  $itemplan,
                        'poCod'    => $codigoPO,
                        'idSubProyectoEstacion' =>  $subProyectoEstacion);
                    
                    
                    $dataPqtTmp = array (   'itemplan'          =>  $itemplan,
                                            'idEstacion'        =>  $idEstacion,
                                            'estado'            =>  1,//validado
                                            'codigo_po'         =>  $codigoPO,
                                            'fecha_validado'    =>  $this->fechaActual(),
                                            'usuario_valida'    =>  $idUsuario
                                        );
                    $tipoTmpPoCreate = 1;//1 = insert;              
                    $hasPtrCreado = $this->m_pqt_terminado->getEstatusEstacionItemplan($itemplan, $idEstacion);
                    if($hasPtrCreado != null){
                        $tipoTmpPoCreate = 2;//2 = update     
                        $dataPqtTmp['id_pqt_partidas'] = $hasPtrCreado['id_pqt_partidas'];
                    }
                    
                    /***porsiacaso**/
                    $dataUpdateSolicitud = array ('estado'            => 2,
                                                  'usua_val_nivel_2'  => $this->session->userdata('idPersonaSession'),
                                                  'fec_val_nivel_2'   => $this->fechaActual(),
                                                  'itemplan'          =>  $itemplan,
                                                  'idEstacion'        =>  $idEstacion
                    );
                    /****/
                    $data = $this->m_pqt_terminado->createPoMO($dataPO, $dataLogPO, $dataDetalleplan, $arrayPartidasInsert, $tipoTmpPoCreate, $dataPqtTmp, $dataUpdateSolicitud);
                    if($data['error']   ==  EXIT_ERROR){
                        throw new Exception('Hubo un error interno, por favor volver a intentar.');
                    }
                    
                    /*AQUI LUEGO DE CREAR LA PO SE DEBE GENERAR UNA SOLICITUD DE EDICACION CERTIFICACION DE ORDEN DE COMPRA SI LA TIENE Y SI NO VA A LA BOLSA***/
                    $data['codigoPO']    =   $codigoPO;
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
                
                $data = $this->m_pqt_terminado->sendValidarPartidasAdicionales($itemplan, $idEstacion, $arrayFerreteria, $arrayPqPartAdicionales, $dataSolValidacion);
                $data['error']       = EXIT_SUCCESS;               
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
    
}