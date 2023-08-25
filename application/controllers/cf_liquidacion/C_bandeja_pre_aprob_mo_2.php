<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * @author CARLOS ZAVALA C.
 * 18/01/2018
 *
 */
class C_bandeja_pre_aprob_mo_2 extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_liquidacion/m_bandeja_pre_certifica');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }
    
	public function index()
	{  	   
	    $logedUser = $this->session->userdata('usernameSession');
	    _log($this->session->userdata('usernameSesuion'));
	    if($logedUser != null){
        	   $data['listaEECC'] = $this->m_utils->getAllEECC();
        	   $data['listaZonal'] = $this->m_utils->getAllZonal();
        	   $data['listaSubProy'] = $this->m_utils->getAllSubProyecto();
        	   $data['listafase'] = $this->m_utils->getAllFase();
               $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo(null);	
               $filtroPerfil = null;
               $isEECC = $this->isEECC();
               if($isEECC){
                   $filtroPerfil = $this->session->userdata('eeccSession');
               }
               $data['isEECC'] =  $isEECC;
               $data['itemplanList'] = $this->makeHTMLOptionsChoice($this->m_bandeja_pre_certifica->getItemplanExpediente($filtroPerfil));
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');	
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');	               
        	   $permisos =  $this->session->userdata('permisosArbol');
        	   
        	   #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_CERTIFICACION_MO, ID_PERMISO_HIJO_BANDEJA_PRE_CERTIFICACION_II);
        	   $result = $this->lib_utils->getHTMLPermisos($permisos, 250, ID_PERMISO_HIJO_BANDEJA_PRE_CERTIFICACION_II, ID_MODULO_ADMINISTRATIVO);
        	   $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	       $this->load->view('vf_liquidacion/v_bandeja_pre_aprob_mo_2',$data);
        	   }else{
        	       redirect('login','refresh');
	           }
	   }else{
	       redirect('login','refresh');
	   }
    }
    
    public function isEECC(){
        $perfiles = explode(',', $this->session->userdata('idPerfilSession'));
        foreach($perfiles as $onePerfil){
            if($onePerfil ==  ID_PERFIL_EECC   || $onePerfil   ==  ID_PERFIL_EECC_DUO){                
               return true;
            }
        }
        return false;
    }
    
    public function makeHTMLOptionsChoice($itemplanList){
        $html = '';
        foreach($itemplanList->result() as $row){
            $html .= '<option value="'.$row->itemplan.'">'.$row->itemplan.'</option>';
        }       
        return $html;
    }

    public function makeHTLMTablaBandejaAprobMo($listaPTR){  
        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>';
                //            <th style="width: 10px;"></th>  
              //              <th style="width: 10px;"></th>                            
             $html .='      <th>Item Plan</th>    
                            <th>Indicador</th>     
                            <th>Sub Proy</th>
                            <th>Zonal</th>
                            <th>EECC</th>
                            <th>Fase</th>
                            <th>Fec. Prevista</th>
                            <th>Estado</th>
                            <th>Situacion</th>

                        </tr>
                    </thead>
                   
                    <tbody>';
	if($listaPTR!=null){			   																			                                                   
                foreach($listaPTR->result() as $row){              
                    
                $html .=' <tr>';
            //                <th>'.(($row->hasExpe=='1' && $row->hasDise=='0') ? '<a data-itemplan ="'.$row->itemPlan.'"  onclick="aprobarCertificado(this)"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/circle-check-128.png"></a>' : '').'</th>
             //               <td>'.(($row->hasExpe=='1') ? '<img alt="Editar" height="20px" width="20px" src="public/img/iconos/expediente.png">' : '').'</td>
               $html .='    <th style="color : blue"><a data-itm ="'.$row->itemPlan.'" onclick="getPTRSByItemplan(this)">'.$row->itemPlan.'</a></th>	
                            <th>'.$row->indicador.'</th>							
                            <th>'.$row->subProyectoDesc.'</th>
							<th>'.$row->zonalDesc.'</th>
							<th>'.$row->empresaColabDesc.'</th>
							<th>'.$row->fase_desc.'</th>
                            <th>'.$row->fechaPrevEjec.'</th> 
                            <th>'.$row->estadoPlanDesc.'</th>  
                            <th>'.$row->situacion.'</th>  
			</tr>';
                 }
        }
			 $html .='</tbody>
                </table>';
                    
        return utf8_decode($html);
    }
    
      function filtrarTabla(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $SubProy = $this->input->post('subProy');
            $eecc = $this->input->post('eecc');
            $zonal = $this->input->post('zonal');
            $itemPlan = $this->input->post('itemplanFil');
            $mesEjec = $this->input->post('mes');
            $expediente = $this->input->post('expediente');
            $idFase = $this->input->post('idFase');
            if($SubProy=='' && $eecc=='' && $zonal=='' && $itemPlan=='' && $mesEjec=='' && $expediente=='' && $idFase == ''){
                $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo(null);
                
            }else{
                $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_bandeja_pre_certifica->getBandejaPreMo($SubProy,$eecc,$zonal,$itemPlan,$mesEjec,$expediente,$idFase));
                
            }
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function getPtrsByItemPlan(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $itemplan    = $this->input->post('itemplan');            
            $listaEstaciones = $this->m_bandeja_pre_certifica->getEstacionPorcentajeByItemPlanWithDiseno($itemplan);          
            $data['estacionesTab'] = $this->getHTMLTabsEstaciones($itemplan, $listaEstaciones);
            //$data['tabPtrItm'] = $this->makeHTLMTablaPtrByItemplan($this->m_bandeja_pre_certifica->getPtrByItemplan($itemplan));
            //$data['tabCerti'] = $this->makeHTLMTablaCertificacionByITemplan($this->m_bandeja_pre_certifica->getCertificadoByItemPlan($itemplan));
            //$data['hasActivo'] = $this->m_bandeja_pre_certifica->haveActivo($itemplan);
            $data['error']    = EXIT_SUCCESS;           
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function getHTMLTabsEstaciones($itemplan, $listaEstaciones){
        $validarFichatecnica = false;
        $needSiropeAndFT = true;
        $infoITemplan = $this->m_utils->getInfoItemplan($itemplan);
        $fechaPreLiquidacion = $infoITemplan['fechaPreLiquidacion'];
        $fase = $infoITemplan['idFase'];
        if($fechaPreLiquidacion != null){
            if(date("Y-m-d", strtotime($fechaPreLiquidacion)) >= date("Y-m-d", strtotime('2018-07-07')) && $fase != ID_FASE_2017){
                $validarFichatecnica = true;
            }
        }else{
            if($fase != ID_FASE_2017){
                $validarFichatecnica = true;
            } 
        }       
        $infoProySubProy = $this->m_utils->getProyectoSubProyectoByItemplan($itemplan);
        if($infoProySubProy['idProyecto']   ==  ID_PROYECTO_OBRA_PUBLICA 
            || $infoProySubProy['idSubProyecto'] == 146/*MEGAPROYECTO FTTH*/
            || $infoProySubProy['idSubProyecto'] == 182/*FTTH MIXTO*/
            || $infoProySubProy['idSubProyecto'] == 7/*FTTH AMPLIACION*/
            || $infoProySubProy['idSubProyecto'] == 8/*FTTH NUEVO*/){
            $needSiropeAndFT = false;
        }
        $html = '<div class="tab-container">
                            <ul class="nav nav-tabs nav-fill" role="tablist">';
        $activa = 'active';
        foreach($listaEstaciones->result() as $row){
            $color = $this->getColorTabByPorcentaje($row->porcentaje);
            $html .= '<li class="nav-item">
                        <a  style="'.$color['color_font'].'" class="nav-link '.$activa.'" data-toggle="tab" href="#tab'.$row->idEstacion.'" role="tab">'.utf8_decode($row->estacionDesc).'</a>
                      </li>';
            $activa = '';
        }
        $html .= '</ul>
            
                    <div class="tab-content">';
        $activa = 'active';
        foreach($listaEstaciones->result() as $row){
            $showBtnEditMatPar = false;
            $showBtnExpe = false;
            $btnNeedDJ = '';
            $addExpe = $this->m_bandeja_pre_certifica->haveActivo($itemplan, $row->idEstacion);
            $isEECC = $this->isEECC(); 
      if($needSiropeAndFT){
            if($addExpe=='0' && $row->porcentaje=='100' && ($isEECC ||  $this->session->userdata('idPersonaSession')    ==  ID_USUARIO_ELSA_MEDINA ||  $this->session->userdata('idPersonaSession')    ==  ID_USUARIO_OWEN_SARAVIA ||  $this->session->userdata('idPersonaSession')    ==  1512)){//validacion para evitar agregar expediente
            $vrPendiente = $this->m_bandeja_pre_certifica->haveActivoVR($itemplan, $row->idEstacion);
                if($vrPendiente == '0'){
                                      
                        if($row->idEstacion == ID_ESTACION_FO ||
                           $row->idEstacion == ID_ESTACION_OC_FO ||
                           $row->idEstacion == ID_ESTACION_FO_DIST){
                            $has_sirope = $this->m_utils->getInfoItemplan($itemplan)['has_sirope'];
                            if($has_sirope  ==  1){
                                if($row->idEstacion == ID_ESTACION_FO && $validarFichatecnica){
                                    $has_ficha  =   $this->m_bandeja_pre_certifica->haveFichaValidadaPorTDP($itemplan, $row->idEstacion);
                                    if($has_ficha   >=  1){
                                        $contMO = $this->m_bandeja_pre_certifica->countNoValidadasSoloMO($itemplan, $row->idEstacion);
                if(($contMO['has_ptr'] == 0 || $contMO['has_ptr'] == $contMO['no_aprob']) && $addExpe=='0'&& ($isEECC ||  $this->session->userdata('idPersonaSession')    ==  ID_USUARIO_ELSA_MEDINA ||  $this->session->userdata('idPersonaSession')    ==  ID_USUARIO_OWEN_SARAVIA ||  $this->session->userdata('idPersonaSession')    ==  1512)){
                    $showBtnExpe = true;
                }else{
                    if($addExpe=='0'){//SE ESCAPABA CON AQUELLOS QUE YA TENIAN EXPEDIENTE
                                                    $btnNeedDJ = '<label style="color:red; font-weight: bold;">No cuenta con PO MO Liquidadas.</label>';
                                                }else{
                                                    $btnNeedDJ = '';
                                                }                
                } 
                                       // $showBtnExpe = true;
                                    }else{
                                        $btnNeedDJ = '<label style="color:red; font-weight: bold;">No con cuenta con declaracion jurada validada.</label>';
                                    }
                                }else{// parche 09.07.2019 no estaba validando las po MO para estaciones diferentes a FO
                                    $contMO = $this->m_bandeja_pre_certifica->countNoValidadasSoloMO($itemplan, $row->idEstacion);
                                    if(($contMO['has_ptr'] == 0 || $contMO['has_ptr'] == $contMO['no_aprob']) && $addExpe=='0'&& ($isEECC ||  $this->session->userdata('idPersonaSession')    ==  ID_USUARIO_ELSA_MEDINA ||  $this->session->userdata('idPersonaSession')    ==  ID_USUARIO_OWEN_SARAVIA ||  $this->session->userdata('idPersonaSession')    ==  1512)){
                                        $showBtnExpe = true;
                                    }else{
                                        if($addExpe=='0'){//SE ESCAPABA CON AQUELLOS QUE YA TENIAN EXPEDIENTE
                                            $btnNeedDJ = '<label style="color:red; font-weight: bold;">No cuenta con PO MO Liquidadas.</label>';
                                        }else{
                                            $btnNeedDJ = '';
                                        }
                                    }
                                    //$showBtnExpe = true;
                                }
                            }else{
                                $btnNeedDJ = '<label style="color:red; font-weight: bold;">No cuenta carga de archivo Sirope.</label>';
                            }                            
                        }else{
                            if($row->idEstacion   ==  ID_ESTACION_COAXIAL && $validarFichatecnica){
                                $has_ficha  =   $this->m_bandeja_pre_certifica->haveFichaValidadaPorTDP($itemplan, $row->idEstacion);
                                if($has_ficha   >=  1){
                                    $contMO = $this->m_bandeja_pre_certifica->countNoValidadasSoloMO($itemplan, $row->idEstacion);
                if(($contMO['has_ptr'] == 0 || $contMO['has_ptr'] == $contMO['no_aprob']) && $addExpe=='0'&& ($isEECC ||  $this->session->userdata('idPersonaSession')    ==  ID_USUARIO_ELSA_MEDINA ||  $this->session->userdata('idPersonaSession')    ==  ID_USUARIO_OWEN_SARAVIA ||  $this->session->userdata('idPersonaSession')    ==  1512)){
                    $showBtnExpe = true;
                }else{
                    if($addExpe=='0'){//SE ESCAPABA CON AQUELLOS QUE YA TENIAN EXPEDIENTE
                                                    $btnNeedDJ = '<label style="color:red; font-weight: bold;">No cuenta con PO MO Liquidadas.</label>';
                                                }else{
                                                    $btnNeedDJ = '';
                                                }               
                } 
                                  //  $showBtnExpe = true;
                                }else{
                                    $btnNeedDJ = '<label style="color:red; font-weight: bold;">No con cuenta con declaracion jurada validada.</label>';
                                }
                            }else{
                                 $contMO = $this->m_bandeja_pre_certifica->countNoValidadasSoloMO($itemplan, $row->idEstacion);
                                if(($contMO['has_ptr'] == 0 || $contMO['has_ptr'] == $contMO['no_aprob']) && $addExpe=='0'&& ($isEECC ||  $this->session->userdata('idPersonaSession')    ==  ID_USUARIO_ELSA_MEDINA ||  $this->session->userdata('idPersonaSession')    ==  ID_USUARIO_OWEN_SARAVIA ||  $this->session->userdata('idPersonaSession')    ==  1512)){
                                    $showBtnExpe = true;
                                }else{
                                   if($addExpe=='0'){//SE ESCAPABA CON AQUELLOS QUE YA TENIAN EXPEDIENTE
                                                    $btnNeedDJ = '<label style="color:red; font-weight: bold;">No cuenta con PO MO Liquidadas.</label>';
                                                }else{
                                                    $btnNeedDJ = '';
                                                }
                                }
                               // $showBtnExpe = true;
                            }
                        }                        
                  
                }else if($vrPendiente != '0'){
                    $btnNeedDJ = '<label style="color:red; font-weight: bold;">Pendiente de Vale de Reserva.</label>';                    
                }                
            }       
        }else{
            $contMO = $this->m_bandeja_pre_certifica->countNoValidadasSoloMO($itemplan, $row->idEstacion);
                if(($contMO['has_ptr'] == 0 || $contMO['has_ptr'] == $contMO['no_aprob']) && $addExpe=='0'&& ($isEECC ||  $this->session->userdata('idPersonaSession')    ==  ID_USUARIO_ELSA_MEDINA ||  $this->session->userdata('idPersonaSession')    ==  ID_USUARIO_OWEN_SARAVIA ||  $this->session->userdata('idPersonaSession')    ==  1512)){
                    $showBtnExpe = true;
                }else{
                    if($addExpe=='0'){//SE ESCAPABA CON AQUELLOS QUE YA TENIAN EXPEDIENTE
                                                    $btnNeedDJ = '<label style="color:red; font-weight: bold;">No cuenta con PO MO Liquidadas.</label>';
                                                }else{
                                                    $btnNeedDJ = '';
                                                }                    
                } 
        }
            if($addExpe != 0){
                $canValiUsua = $this->m_utils->canValidPoCertificacion($this->session->userdata('idPersonaSession'));
                if($canValiUsua>=1){
                    $actiFinalizado = $this->m_bandeja_pre_certifica->haveActivoFinalizado($itemplan, $row->idEstacion);
                    if($actiFinalizado  ==  0){
                        $showBtnEditMatPar =    true;
                    }                
                }
            }
            $html .= '<div class="tab-pane '.$activa.' fade show" id="tab'.$row->idEstacion.'" role="tabpanel">
                                      <div class="table-responsive">';
            			
			//$html .= $this->makeHTLMTablaPtrByItemplan($this->m_bandeja_pre_certifica->getPtrByItemplan($itemplan, $row->idEstacion),$row->porcentaje, $row->certificado, $itemplan, $row->idEstacion, $showBtnEditMatPar);
            $retorno = $this->makeHTLMTablaPtrByItemplan($this->m_bandeja_pre_certifica->getPtrByItemplan($itemplan, $row->idEstacion),$row->porcentaje, $row->certificado, $itemplan, $row->idEstacion, $showBtnEditMatPar, $fase);
            $html        .= $retorno['html'];
            $ponerMensaje = $retorno['bloquearEstacion'];
            /**NUEVA VALIDACION OWEN 21.02.2020 **/
            if($addExpe == 0){//luego de la validacion no mostrar si ya tiene expdiente   
                $mensajeNuevoOwen ='<label style="color:red; font-weight: bold;">Obra no certificable.</label>';
                if($ponerMensaje){
                    $btnNeedDJ = $mensajeNuevoOwen;
                    $showBtnExpe = false;
                }
            }
            /**FIN NUEVA VALIDACION**/
            
			$html .= '<br><br><br>
                    <div style="text-align: center;width: 100%;padding-top: 20px">
                        <label style="font-weight: bold;">EXPEDIENTE</label><br>
                         '.$btnNeedDJ.'
                    </div>
               <div id="contBtnCerti'.$row->idEstacion.'" style="text-align: right; width: 95%;'.(($showBtnExpe) ? '' : 'display: none;').'" class="tab-container">
            <button data-idesta='.$row->idEstacion.' data-item='.$itemplan.' style="color: white;background-color: #204382;" class="btn btn-secondary waves-effect" data-toggle="modal" onclick="addExpedienteEstacion(this)">Expediente</button>
            </div>
            <div style="width: 100%;" id="contTablaCerti'.$row->idEstacion.'">
            '.$this->makeHTLMTablaCertificacionByITemplan($this->m_bandeja_pre_certifica->getCertificadoByItemPlan($itemplan, $row->idEstacion), $row->idEstacion).'
            </div>';
            $html .= '</div>
                                </div>';
            $activa = '';
        }
        $html .= ' </div>
                    </div>';
        return $html;
    }
    
    public function getHTMLCollapseEstaciones($listaEstaciones){
        $html = '<div class="card-block">
                    <div class="accordion" role="tablist">';
        
        foreach($listaEstaciones->result() as $row){
            $color = $this->getColorTabByPorcentaje($row->porcentaje);
        $html .= '<div class="card" style="padding-top: 5px;">
                    <div class="card-header" role="tab" style="'.$color['header'].'">
                        <a style="text-align: center;'.$color['font'].'" class="card-title" data-toggle="collapse" data-parent="#accordionExample" href="#collapse'.$row->idEstacion.'">'.$row->estacionDesc.' '.$row->porcentaje.'%</a>
                    </div>
                    <div style="'.$color['body'].'" id="collapse'.$row->idEstacion.'" class="collapse">
                        <div class="card-block">
                            Contenido
                        </div>
                    </div>
                </div>';
        }
        $html .= '  </div>
                </div>';
        return $html;
    }
    
    public function getColorTabByPorcentaje($val){
        $color = array();        
        if($val>= 0 && $val<=49){
            $color['header'] = 'background-color:red;';
            $color['body'] = 'background-color:#ff000040;';
            $color['font'] = 'color:white';
            $color['color_font'] = 'color:red';
        }else if($val && $val<=74){
            $color['header']= 'background-color:yellow';           
            $color['body'] = 'background-color:#efef2373;';
            $color['font'] = 'color:black';
            $color['color_font'] = 'color:yellow';
        }else if($val >=75 && $val<=99){
            $color['header']= 'background-color:orange';
            $color['body'] = 'background-color:#ffa5007a;';
            $color['font'] = 'color:black';
            $color['color_font'] = 'color:orange';
        }else if($val==100){
            $color['header']= 'background-color:green;';
            $color['body'] = 'background-color:#0080004f;';
            $color['font'] = 'color:white';
            $color['color_font'] = 'color:green';
        }
        return $color;
    }
    
    public function makeHTLMTablaPtrByItemplan($listaPTR, $porcentaje, $certificado, $itemplan, $idEstacion, $showBtnExpe, $fase){
		/**NUEVA VALIDACION OWEN 21.02.2020 **/       
        $bloquearEstacion = false;
        /**FIN NUEVA VALIDACION**/
        $noVali= $this->m_bandeja_pre_certifica->countNoValidadas($itemplan, $idEstacion );
        $html = '<table id="data-table2" class="table table-bordered" style="font-size: smaller;">
                    <thead class="thead-default">
                        <tr>
                            <th><div id="contBtnDet" style="text-align: right; width: 95%;'.(($showBtnExpe && ($noVali['has_ptr'] == (($noVali['no_aprob'] == null) ? 0 : $noVali['no_aprob']))) ? '' : 'display: none;').'" class="tab-container">
                            <button  data-idEsta="'.$idEstacion.'" data-itm="'.$itemplan.'" style="color: white;background-color: #204382;" class="btn btn-secondary waves-effect" data-toggle="modal" onclick="getDetItemEstacion(this)">Det.</button>
            </div></th>
                            <th>PTR</th>
                            <th>AREA</th>
                            <th>ESTADO</th>                            
                            <th>EECC</th>
                            <th>ZONAL</th>
                            <th>V. MO</th>
                            <th>V. MAT</th>
                            <th>VALE RESERVA</th>
                        </tr>
                    </thead>
          
                    <tbody>';
         
        foreach($listaPTR->result() as $row){
            /**NUEVA VALIDACION OWEN 21.02.2020 **/
			if($row->idProyecto != ID_PROYECTO_OBRA_PUBLICA){
				if($fase != ID_FASE_2020 && $row->desc_area == 'MO'){//si no es 2020
					$canCertificable = $this->m_bandeja_pre_certifica->canCerticablesPasado($row->ptr);
					if(substr($row->estado_wu,0,3) != '007' && substr($row->estado_wu,0,3) != '04'){
						if($canCertificable == 0){//si no esta en su lista no dejar certificar
							$bloquearEstacion = true;
						}
					}
				}
			}
            /**FIN NUEVA VALIDACION**/
            $estadoFinal = '';
            if($row->estado_wu == $row->estado_wud){
                $estadoFinal = $row->estado_wud;
            }else{
                $estadoFinal = $row->estado_wu;
            }
            $html .=' <tr '.((substr($estadoFinal,0,3) == '003' || substr($estadoFinal,0,3) == '001' ) ? '' : ' style = "background-color: gainsboro;"').'>
                           <td> <label class="custom-control custom-checkbox">
                                <input '.((utf8_decode($row->estacionDesc)=='DISEÑO')||($porcentaje!='100')||($certificado>0) ? 'onclick="return false;" disabled ': 'onclick="chequed(this)"').' value="'.$row->ptr.','.$row->itemplan.'" type="checkbox" class="custom-control-input"  '.(($row->hasPtrExpe==1) ? 'checked' : '').'>
                                <span class="custom-control-indicator"></span>                                
                            </label></td>
							<td>'.$row->ptr.'</td>
							<td>'.$row->areaDesc.'</td>
							<td>'.$row->estado_wu.'</td>							 
							<th>'.$row->eecc.'</th>
                            <th>'.$row->jefatura.'</th>
                            <th>'.$row->valor_m_o.'</th>
                            <th>'.$row->valor_material.'</th>
                            <th>'.(($row->vr_wud != null) ? $row->vr_wud : $row->vr_wu).'</th>
						</tr>';
        }
        $html .='</tbody>
                </table>';    
        $retorno['html']             = utf8_decode($html);
        $bloquearEstacion = false;//agregado el 22.07.2020 pedido de owen ya no se va validar 
		$retorno['bloquearEstacion'] = $bloquearEstacion;
        return $retorno;
    }

    public function makeHTLMTablaCertificacionByITemplan($listaPTR, $idEstacion){
         
        $html = '<table id="tabla-certi'.$idEstacion.'" class="table table-bordered" style="font-size: smaller;">
                    <thead class="thead-default">
                        <tr>    
                            <th></th>
                            <th>FECHA</th>                            
                            <th>USUARIO</th>
                            <th>COMENTARIO</th>
                            <th>ESTADO</th>
                            <th>USUARIO VALIDA</th>
                            <th>FECHA VALIDA</th>
                            <th></th>                                         
                        </tr>
                    </thead>
    
                    <tbody>';
         
        foreach($listaPTR->result() as $row){
    
            $html .='<tr '.(($row->estado!='ACTIVO') ? '' : ' style = "background-color: #33a2264a;"').'>          
                        <td>'.(($row->estado=='ACTIVO' && $row->estado_final=='PENDIENTE' && !$this->isEECC()) ? '<a data-itemplan="'.$row->itemplan.'" data-estacion="'.$idEstacion.'" onclick="aprobarCertificado(this)"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/circle-check-128.png"></a>' : '').'</td>    
						<td>'.$row->fecha.'</td>
                        <td>'.$row->usuario.'</td>
						<td>'.$row->comentario.'</td>
						<td>'.$row->estado_final.'</td>
						<td>'.$row->usuario_valida.'</td>
						<td>'.$row->fecha_valida.'</td>
                        <td>'.(($row->estado=='ACTIVO' && $row->estado_final=='PENDIENTE' && !$this->isEECC()) ? '<a data-id="'.$row->id.'" data-estacion="'.$idEstacion.'" data-itemplan="'.$row->itemplan.'" onclick="cancelCertificado(this)"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/delete.png"></a>' : '').'</td>
					</tr>';                    
        }
        $html .='</tbody>
                </table>';
    
        return utf8_decode($html);
    }
    
    public function cancelCertificado(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{            
            $SubProy = $this->input->post('subProy');
            $eecc = $this->input->post('eecc');
            $zonal = $this->input->post('zonal');
            $itemPlanFil = $this->input->post('itemplanFil');
            $mesEjec = $this->input->post('mes');
            $expediente = $this->input->post('expediente');
            $estacion = $this->input->post('estacion');
            $id = $this->input->post('id');   
            $itemplan = $this->input->post('itemplan');   
            $data = $this->m_bandeja_pre_certifica->cancelCertificado($id);  
            if($data['error']==EXIT_ERROR){
                throw new Exception('ERROR AL CANCELAR CERTIFICADO');
            }
            $data['tabCerti'] = $this->makeHTLMTablaCertificacionByITemplan($this->m_bandeja_pre_certifica->getCertificadoByItemPlan($itemplan, $estacion), $estacion);    
           // $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_bandeja_pre_certifica->getBandejaPreMo($SubProy,$eecc,$zonal,$itemPlanFil,$mesEjec,$expediente));
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function saveCertificado(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            
            $SubProy = $this->input->post('subProy');
            $eecc = $this->input->post('eecc');
            $zonal = $this->input->post('zonal');
            $itemPlanFil = $this->input->post('itemplanFil');
            $mesEjec = $this->input->post('mes');
            $expediente = $this->input->post('expediente');
            $estacion= $this->input->post('estacion');
            
            $itemplan = $this->input->post('itemplan');
            $fecha = $this->input->post('fecha');
            $comentario = $this->input->post('comentario');
            $data = $this->m_bandeja_pre_certifica->saveCertificado($itemplan,$fecha,strtoupper($comentario),$estacion);
            if($data['error']==EXIT_ERROR){
                throw new Exception('ERROR AL INSERTAR CERTIFICADO');
            }
            $data['tabCerti'] = $this->makeHTLMTablaCertificacionByITemplan($this->m_bandeja_pre_certifica->getCertificadoByItemPlan($itemplan, $estacion), $estacion);  
            //$data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_bandeja_pre_certifica->getBandejaPreMo($SubProy,$eecc,$zonal,$itemPlanFil,$mesEjec,$expediente));
        }catch(Exception $e){
           $data['msj'] = $e->getMessage();                
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function aprobCertiFicado(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $logedUser = $this->session->userdata('usernameSession');
            if($logedUser != null){
                $SubProy = $this->input->post('subProy');
                $eecc = $this->input->post('eecc');
                $zonal = $this->input->post('zonal');
                $itemPlanFil = $this->input->post('itemplanFil');
                $mesEjec = $this->input->post('mes');
                $itemplan = $this->input->post('itemplan');
                $expediente = $this->input->post('expediente');
                $estacion = $this->input->post('estacion');
                
                $estacionDesc = $this->m_utils->getEstaciondescByIdEstacion($estacion);
                $data = $this->m_bandeja_pre_certifica->preAprobarTerminados($itemplan, $estacion, $estacionDesc);
                if($data['error']==EXIT_ERROR){
                    throw new Exception('ERROR AL aprobCertiFicado');
                }
                
                $arrayLogPo = array();
                $arrayPoToValidate = array();                
                $listaPoliquidados = $this->m_bandeja_pre_certifica->getPtrsByItemplanEstacionEstado($itemplan, $estacion, PO_LIQUIDADO);
                foreach($listaPoliquidados->result() as $row){
                    $toValidate = array('codigo_po' => $row->codigo_po,
                                        'estado_po' => PO_VALIDADO,
                                        'fecha_validacion'  =>  $this->fechaActual()
                    );
                    array_push($arrayPoToValidate, $toValidate);
                    
                    $dataLogPO = array(
                        'codigo_po'         =>  $row->codigo_po,
                        'itemplan'          =>  $itemplan,
                        'idUsuario'         =>  $this->session->userdata('idPersonaSession'),
                        'fecha_registro'    =>  $this->fechaActual(),
                        'idPoestado'        =>  PO_VALIDADO,
                        'controlador'       =>  'pre-certificacion'
                    );
                    array_push($arrayLogPo, $dataLogPO);
                }
                $data = $this->m_bandeja_pre_certifica->validarPoLiquidadas($arrayPoToValidate, $arrayLogPo);
                if($data['error']==EXIT_ERROR){
                    throw new Exception('Error al validar PO, vuelva a intentarlo. De continuar comuniquese con la persona a cargo.');
                }
                $data['tabCerti'] = $this->makeHTLMTablaCertificacionByITemplan($this->m_bandeja_pre_certifica->getCertificadoByItemPlan($itemplan, $estacion), $estacion);
                
                //$data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_bandeja_pre_certifica->getBandejaPreMo($SubProy,$eecc,$zonal,$itemPlanFil,$mesEjec,$expediente));
            }else{
                throw new Exception('Su sesion ha expirado, refresque la pagina y vuelva a inciar sesion.');
            }
        }catch(Exception $e){
           $data['msj'] = $e->getMessage();                
        }
            echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function checkPtrItem(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $accion = $this->input->post('accion');
            $dato   = $this->input->post('dato');
            $datoEx = explode(',', $dato);
            $ptr = $datoEx[0];
            $itemplan = $datoEx[1];           
            $data = $this->m_bandeja_pre_certifica->insertOrDeletePtrExpediente($accion, $ptr, $itemplan);
            if($data['error']==EXIT_ERROR){
                throw new Exception('ERROR AL checkPtrItem');
            }
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function getMaterialesPartidasByItem(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $itemplan       = $this->input->post('itemplan');
            $idEstacion     = $this->input->post('idEsta');
           // $listaEstaciones = $this->m_bandeja_pre_certifica->getEstacionPorcentajeByItemPlanWithDiseno($itemplan);
            $contenido = $this->makeHTLMDetalleMatPartidasByItem($itemplan, $idEstacion);
            $data['htmlDetParMat'] = $contenido['html'];
            $data['valiMate'] = json_encode($contenido['valiMateriales']);
            $data['valiMo'] = json_encode($contenido['valiManoObra']);            
            log_message('error', print_r($data['valiMo'],true));
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    public function makeHTLMDetalleMatPartidasByItem($itemplan, $idEstacion){
        $data = array();        
        $arrayInputMateRiales = array();
        $arrayInputManoObras = array();
        $html = '<div class="col-sm-12 col-md-12">
                    <div class="tab-container">
                        <ul class="nav nav-tabs nav-fill" role="tablist">   
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#materialesList" role="tab">MATERIALES</a>
                            </li>                      
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#partidasList" role="tab">PARTIDAS</a>
                            </li>    
                                                  
                        </ul>
                    </div> 
                    <div class="tab-content">    
               <div class="tab-pane active fade show" id="materialesList" role="tabpanel">
                            <div id="contTablaMaterialesList" class="table-responsive">';
        $listaMatDet    =   $this->m_bandeja_pre_certifica->getMaterialesPoMatByITemplan($itemplan, $idEstacion, 1);//order by cantidad
        $html .= '<form id="formEditPtrMat" method="get">
                  <table id="tabla-mat'.$idEstacion.'" class="table table-bordered" style="font-size: smaller;">
                    <thead class="thead-default">
                        <tr>                          
                            <th>CODIGO PO</th>
                            <th>COD MATERIAL</th>
                            <th>DESC MATERIAL</th>
                            <th>CANTIDAD INICIAL</th>
                            <th>CANTIDAD FINAL</th>
                            <th>CANTIDAD A DEVOLVER</th>                            
                        </tr>
                    </thead>
        
                    <tbody>';
         
        foreach($listaMatDet->result() as $row){
        
            $html .='<tr '.((round($row->cantidad_ingreso, 0, PHP_ROUND_HALF_DOWN) == 0) ? 'style="background-color:#ffe27a"' : '').'>                      
                        <td>'.$row->codigo_po.'</td>
						<td>'.$row->codigo_material.'</td>
						<td>'.$row->descrip_material.'</td>
						<td>'.(($row->cantidad_ingreso == null) ? 0 : round($row->cantidad_ingreso, 0, PHP_ROUND_HALF_DOWN)).'</td>
						<td>'.(($row->cantidad_final == null) ? 0 : round($row->cantidad_final, 0, PHP_ROUND_HALF_DOWN)).'</td>
                        <td><input type="number" class="form-control" name="'.$row->codigo_po.'|'.$row->codigo_material.'" placeholder="" style="border-bottom: 1px solid grey;"></td>
					</tr>';
            $materialValidator = array( 'name' => $row->codigo_po.'|'.$row->codigo_material,
                                        'min'  => (($row->cantidad_ingreso == null) ? 0 : round($row->cantidad_ingreso, 0, PHP_ROUND_HALF_DOWN)),
                                        'max'  => (($row->cantidad_final == null) ? 0 : round($row->cantidad_final, 0, PHP_ROUND_HALF_DOWN))
                                        );
            array_push($arrayInputMateRiales, $materialValidator);
        }
        $html .='</tbody>
        </table>
                <div id="mensajeFormMat"></div>
                <div class="form-group" style="text-align: right;">
                    <div class="col-sm-12">                                        
                        <button data-itemplan="'.$itemplan.'" data-idEstacion="'.$idEstacion.'" id="btnRegMat" type="submit" class="btn btn-primary">Save changes</button>                                    
                    </div>
                </div>            
             </form>
            </div>
        </div>';
            
     $html .= '<div class="tab-pane fade" id="partidasList" role="tabpanel">
        <div id="contTablaMaterialesList" class="table-responsive">';
        $listaMoDet    =   $this->m_bandeja_pre_certifica->getPartidasPoMotByITemplan($itemplan, $idEstacion, 1);//ORDER BY CANTIDAD
        $html .= '<form id="formEditMoPo" method="get">
                <table id="tabla-mat'.$idEstacion.'" class="table table-bordered" style="font-size: smaller;">
                <thead class="thead-default">
                <tr>
                    <th>CODIGO PO</th>
                    <th>COD MATERIAL</th>
                    <th>DESC MATERIAL</th>
                    <th>CANTIDAD INICIAL</th>
                    <th>CANTIDAD FINAL</th>
                    <th>EDITAR</th>
                </tr>
                </thead>
        
                <tbody>';
     
        foreach($listaMoDet->result() as $row){
        
            $html .='<tr '.(($row->flg_tipo == 3 ? 'style="background-color: lightgrey;"': (($row->cantidad_inicial == 0) ? 'style="background-color:#ffe27a"' : ''))).'>
                        <td>'.$row->codigo_po.'</td>
						<td>'.$row->codigo.'</td>
						<td>'.$row->descripcion.'</td>
						<td>'.$row->cantidad_inicial.'</td>
						<td>'.$row->cantidad_final.'</td>
                        <td><input '.(($row->flg_tipo == 3 ? 'disabled': '')).' class="form-control" name="'.$row->codigo_po.'|'.$row->idActividad.'" id="'.$row->codigo_po.'|'.$row->idActividad.'" placeholder="" style="border-bottom: 1px solid grey;"></td>
                    </tr>';
            $moValidator = array( 'name' => $row->codigo_po.'|'.$row->idActividad,
                                        'min'  => (($row->cantidad_inicial == null) ? 0 : $row->cantidad_inicial),
                                        'max'  => (($row->cantidad_final == null) ? 0 : $row->cantidad_final)
                                        );
            array_push($arrayInputManoObras, $moValidator);
        }
        $html .='</tbody>
                </table>
                <div id="mensajeFormMo"></div>
                <div class="form-group" style="text-align: right;">
                <div class="col-sm-12">
                <button data-itemplan="'.$itemplan.'" data-idEstacion="'.$idEstacion.'" id="btnRegMo" type="submit" class="btn btn-primary">Save changes</button>
                </div>
                </div>
                </form>
                </div>
                </div>';
            
     $html .= '    </div>
                </div>';
         $data['html'] = utf8_decode($html);
         $data['valiMateriales']    = $arrayInputMateRiales;
         $data['valiManoObra']      = $arrayInputManoObras;
        return $data;
    }
    
    public function updateMateriales(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            log_message('error', 'entro update');
            if($this->session->userdata('idPersonaSession') == null){
                throw new Exception('Su Sesion ha caducado, favor vuelva a iniciar sesion.');
            }
            $itemplan = $this->input->post('itemplan');
            $idEstacion = $this->input->post('idEstacion');
            //log_message('error', 'aqui updateMateriales!!!');
			$hasSolVrPdt = $this->m_bandeja_pre_certifica->getCountPendienteValidVr($itemplan);
            if($hasSolVrPdt>0){
                throw new Exception('No se puede realizar la accion, ya cuenta con una solicitud pendiente de Vale de Reserva');
            }
            $listaSolictudesFin = array();
            $listaCodigoVR = array();
            $listaMatDet    =   $this->m_bandeja_pre_certifica->getMaterialesPoMatByITemplan($itemplan, $idEstacion, 2);// 1=order by codigo_po
            $numero_suma = 1;
            $codigo_po_init = '';
            $codigo_solicitud_vr = '';
            $codigo_vr = null;
            $ptrNoAtendidas = array();
            log_message('error', 'entro update 1');
            $jefaturaData = $this->m_bandeja_pre_certifica->getJefaturaSapByItemplan($itemplan);
            if($jefaturaData == null){
                throw new Exception('Datos de Itemplan No encontrada, comuniquese con el Administrador.');
            }else{
                if($jefaturaData['jefatura_1'] == null && $jefaturaData['jefatura_2'] == null){
                    throw new Exception('Jefatura Sap No encontrada, comuniquese con el Administrador.');
                }
            }
            log_message('error', 'entro update 2');
            foreach($listaMatDet->result() as $row){
                $cantidad_ingresa   = $this->input->post($row->codigo_po.'|'.$row->codigo_material);
                $cantidad_fin       = round($row->cantidad_final, 0, PHP_ROUND_HALF_DOWN);
                if($cantidad_ingresa!= ''){
                    
                    if($codigo_po_init != $row->codigo_po){
                        $codigo_vr              = $this->m_utils->getVrByPtr($row->codigo_po);
                        $codigo_po_init         = $row->codigo_po;
                        if($codigo_vr   ==  null){
                            array_push($ptrNoAtendidas, $row->codigo_po);
                        }else{
                            $codigo_solicitud_vr    = $this->m_bandeja_pre_certifica->getCodigoSolicitudVrByNum($numero_suma);
                            $numero_suma++;
                            $solicitudGenerada = array('codigo_po' => $row->codigo_po,
                                                        'numero_soli' => $codigo_solicitud_vr
                            );
                            array_push($listaCodigoVR,$solicitudGenerada);                          
                        }                        
                    }
                    if($codigo_vr  != null){
                        $cantidad_devolver = ($cantidad_fin - $cantidad_ingresa);
                        $material_vr = array(   'itemplan'          => $itemplan,
                            'ptr'               => $row->codigo_po,
                            'idJefaturaSap'     => (($jefaturaData['jefatura_1']    !=  null) ? $jefaturaData['jefatura_1']   :   $jefaturaData['jefatura_2']),
                            'idEmpresaColab'    => $jefaturaData['idEmpresaColab'],
                            'material'          => $row->codigo_material,
                            'cantidadInicio'    => $cantidad_ingresa,
                            'cantidadFin'       => $cantidad_devolver,
                            'idUsuario'         => $this->session->userdata('idPersonaSession'),
                            'fecha_registro'    => $this->fechaActual(),
                            'flg_tipo_solicitud'=> 4,//devolucion
                            'vr'                => $codigo_vr,
                            'codigo'            => $codigo_solicitud_vr
                        );
                        array_push($listaSolictudesFin, $material_vr);
                    }                 
                }
            }
            log_message('error', 'entro update 3');
            $data = $this->m_bandeja_pre_certifica->createVRDevoluciones($listaSolictudesFin);
                   
            $data['soli_success']   = json_encode($listaCodigoVR);
            $data['soli_error']     = json_encode($ptrNoAtendidas);
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function updateMoPartidas(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            if($this->session->userdata('idPersonaSession') == null){
                throw new Exception('Su Sesion ha caducado, favor vuelva a iniciar sesion.');
            }
            $itemplan = $this->input->post('itemplan');
            $idEstacion = $this->input->post('idEstacion');
            $arrayPartidas      =   array();
            $arrayCostoTotal    =   array();
            $arrayLogPO         =   array();
            $listaMoParti       =   $this->m_bandeja_pre_certifica->getPartidasPoMotByITemplan($itemplan, $idEstacion, 2);//2 = ORDER BY CODIGO PO
            $costoTotalPOMO     =   0;
            $codigo_po_init     =   '';
            $cont_push           = 0;
            foreach($listaMoParti->result() as $row){
                $cantidad_ingresa       = $this->input->post($row->codigo_po.'|'.$row->idActividad);
                $cantidad_final         = $row->cantidad_final;
                if($cantidad_ingresa != $cantidad_final && $cantidad_ingresa!= ''){
                    
                    if($codigo_po_init != $row->codigo_po){
                        $initInfoPO      =  $this->m_utils->getInfoPoByCodigoPo($row->codigo_po);
                        $costoTotalPOMO  =  $initInfoPO['costo_total'];                        
                        if($codigo_po_init != ''){
                            $dataUpdatePO = array(
                                'codigo_po'       => $codigo_po_init,
                                'costo_total'     => $costoTotalPOMO
                            );
                            array_push($arrayCostoTotal, $dataUpdatePO);
                            
                            $dataLogPO = array(
                                'codigo_po'         =>  $row->codigo_po,
                                'itemplan'          =>  $itemplan,
                                'idUsuario'         =>  $this->session->userdata('idPersonaSession'),
                                'fecha_registro'    =>  $this->fechaActual(),
                                'controlador'       =>  'editar/pre-certificacion mo'
                            );                            
                            array_push($arrayLogPO, $dataLogPO);
                        }     
                        $codigo_po_init  =  $row->codigo_po;                        
                    }
                    
                   
                        
                    $dataCMO = array();
                    $dataCMO['id_planobra_po_detalle_po']   = $row->id_planobra_po_detalle_po;                    
                    $dataCMO['cantidad_final']              = $cantidad_ingresa;
                    $dataCMO['monto_final']                 = ($row->baremo*$row->costo*$cantidad_ingresa);
                    array_push($arrayPartidas, $dataCMO);
                    
                    $costoTotalPOMO = ($costoTotalPOMO - ($row->monto_final - $dataCMO['monto_final']));
                    
                    
                }
            }
            
            $dataUpdatePO = array(
                'codigo_po'       => $codigo_po_init,
                'costo_total'     => $costoTotalPOMO
            );
            array_push($arrayCostoTotal, $dataUpdatePO);
            
            $dataLogPO = array(
                'codigo_po'         =>  $row->codigo_po,
                'itemplan'          =>  $itemplan,
                'idUsuario'         =>  $this->session->userdata('idPersonaSession'),
                'fecha_registro'    =>  $this->fechaActual(),
                'controlador'       =>  'editar/pre-certificacion mo'
            );
            
            array_push($arrayLogPO, $dataLogPO);
            /*
            log_message('error', 'aqui updateMoPartidas!!!');            
            log_message('error', print_r($arrayPartidas,true));
            log_message('error', print_r($arrayCostoTotal,true));            
            log_message('error', print_r($arrayLogPO,true));
            */
            $data = $this->m_bandeja_pre_certifica->updatePartidasMO($arrayPartidas, $arrayCostoTotal, $arrayLogPO);
            $data['error']    = EXIT_SUCCESS;
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
     
    /***nuevo modelo pqt 17.06.2020 czavala**/
    public function getPtrsByItemPlanPqt(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $itemplan    = $this->input->post('itemplan');
            $listaEstaciones = $this->m_bandeja_pre_certifica->getEstacionPorcentajeByItemPlanWithDisenoModelPqt($itemplan);
            $data['estacionesTab'] = $this->getHTMLTabsEstacionesPqt($itemplan, $listaEstaciones);
            //$data['tabPtrItm'] = $this->makeHTLMTablaPtrByItemplan($this->m_bandeja_pre_certifica->getPtrByItemplan($itemplan));
            //$data['tabCerti'] = $this->makeHTLMTablaCertificacionByITemplan($this->m_bandeja_pre_certifica->getCertificadoByItemPlan($itemplan));
            //$data['hasActivo'] = $this->m_bandeja_pre_certifica->haveActivo($itemplan);
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function updateMaterialesPqt(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            log_message('error', 'entro update pqt');
            if($this->session->userdata('idPersonaSession') == null){
                throw new Exception('Su Sesion ha caducado, favor vuelva a iniciar sesion.');
            }
            $itemplan = $this->input->post('itemplan');
            $idEstacion = $this->input->post('idEstacion');
            
            $hasVrPdt = $this->m_bandeja_pre_certifica->haveVrPendiente($itemplan, $idEstacion);
            if($hasVrPdt > 0){
                throw new Exception('La Estacion ya cuenta con una solicitud de Vale de Reserva pendiente de aprobacion.');
            }
            
            //log_message('error', 'aqui updateMateriales!!!');
            $listaSolictudesFin = array();
            $listaCodigoVR = array();
            $listaMatDet    =   $this->m_bandeja_pre_certifica->getMaterialesPoMatByITemplan($itemplan, $idEstacion, 2);// 1=order by codigo_po
            $numero_suma = 1;
            $codigo_po_init = '';
            $codigo_solicitud_vr = '';
            $codigo_vr = null;
            $ptrNoAtendidas = array();
            log_message('error', 'entro update 1');
            $jefaturaData = $this->m_bandeja_pre_certifica->getJefaturaSapByItemplanPqt($itemplan);
            if($jefaturaData == null){
                throw new Exception('Datos de Itemplan No encontrada, comuniquese con el Administrador.');
            }else{
                if($jefaturaData['jefatura_1'] == null && $jefaturaData['jefatura_2'] == null){
                    throw new Exception('Jefatura Sap No encontrada, comuniquese con el Administrador.');
                }
            }
            log_message('error', 'entro update 2');
            foreach($listaMatDet->result() as $row){
                $cantidad_ingresa   = $this->input->post($row->codigo_po.'|'.$row->codigo_material);
                $cantidad_fin       = round($row->cantidad_final, 0, PHP_ROUND_HALF_DOWN);
                if($cantidad_ingresa!= ''){
    
                    if($codigo_po_init != $row->codigo_po){
                        $codigo_vr              = $this->m_utils->getVrByPtr($row->codigo_po);
                        $codigo_po_init         = $row->codigo_po;
                        if($codigo_vr   ==  null){
                            array_push($ptrNoAtendidas, $row->codigo_po);
                        }else{
                            $codigo_solicitud_vr    = $this->m_bandeja_pre_certifica->getCodigoSolicitudVrByNum($numero_suma);
                            $numero_suma++;
                            $solicitudGenerada = array('codigo_po' => $row->codigo_po,
                                'numero_soli' => $codigo_solicitud_vr
                            );
                            array_push($listaCodigoVR,$solicitudGenerada);
                        }
                    }
                    if($codigo_vr  != null){
                        $cantidad_devolver = ($cantidad_fin - $cantidad_ingresa);
                        $material_vr = array(   'itemplan'          => $itemplan,
                            'ptr'               => $row->codigo_po,
                            'idJefaturaSap'     => (($jefaturaData['jefatura_1']    !=  null) ? $jefaturaData['jefatura_1']   :   $jefaturaData['jefatura_2']),
                            'idEmpresaColab'    => $jefaturaData['idEmpresaColab'],
                            'material'          => $row->codigo_material,
                            'cantidadInicio'    => $cantidad_ingresa,
                            'cantidadFin'       => $cantidad_devolver,
                            'idUsuario'         => $this->session->userdata('idPersonaSession'),
                            'fecha_registro'    => $this->fechaActual(),
                            'flg_tipo_solicitud'=> 4,//devolucion
                            'vr'                => $codigo_vr,
                            'codigo'            => $codigo_solicitud_vr
                        );
                        array_push($listaSolictudesFin, $material_vr);
                    }
                }
            }
            log_message('error', 'entro update 3');
            $data = $this->m_bandeja_pre_certifica->createVRDevoluciones($listaSolictudesFin);
             
            $data['soli_success']   = json_encode($listaCodigoVR);
            $data['soli_error']     = json_encode($ptrNoAtendidas);
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function getHTMLTabsEstacionesPqt($itemplan, $listaEstaciones){
        $validarFichatecnica = false;
        $needSiropeAndFT = true;
        $infoITemplan = $this->m_utils->getInfoItemplan($itemplan);
        $fechaPreLiquidacion = $infoITemplan['fechaPreLiquidacion'];
        $fase = $infoITemplan['idFase'];
        if($fechaPreLiquidacion != null){
            if(date("Y-m-d", strtotime($fechaPreLiquidacion)) >= date("Y-m-d", strtotime('2018-07-07')) && $fase != ID_FASE_2017){
                $validarFichatecnica = true;
            }
        }else{
            if($fase != ID_FASE_2017){
                $validarFichatecnica = true;
            }
        }
        $infoProySubProy = $this->m_utils->getProyectoSubProyectoByItemplan($itemplan);
        if($infoProySubProy['idProyecto']   ==  ID_PROYECTO_OBRA_PUBLICA
            || $infoProySubProy['idSubProyecto'] == 146/*MEGAPROYECTO FTTH*/
            || $infoProySubProy['idSubProyecto'] == 182/*FTTH MIXTO*/
            || $infoProySubProy['idSubProyecto'] == 7/*FTTH AMPLIACION*/
            || $infoProySubProy['idSubProyecto'] == 8/*FTTH NUEVO*/){
            $needSiropeAndFT = false;
        }
        $html = '<div class="tab-container">
                            <ul class="nav nav-tabs nav-fill" role="tablist">';
        $activa = 'active';
        foreach($listaEstaciones->result() as $row){
            $color = $this->getColorTabByPorcentaje($row->porcentaje);
            $html .= '<li class="nav-item">
                        <a  style="'.$color['color_font'].'" class="nav-link '.$activa.'" data-toggle="tab" href="#tab'.$row->idEstacion.'" role="tab">'.utf8_decode($row->estacionDesc).'</a>
                      </li>';
            $activa = '';
        }
        $html .= '</ul>
    
                    <div class="tab-content">';
        $activa = 'active';
        foreach($listaEstaciones->result() as $row){
            $showBtnEditMatPar = false;
            $showBtnExpe = false;
            $btnNeedDJ = '';
            $addExpe = $this->m_bandeja_pre_certifica->haveActivo($itemplan, $row->idEstacion);
            $isEECC = $this->isEECC();
            if($needSiropeAndFT){
                if($addExpe=='0' && $row->porcentaje=='100' && ($isEECC ||  $this->session->userdata('idPersonaSession')    ==  ID_USUARIO_ELSA_MEDINA ||  $this->session->userdata('idPersonaSession')    ==  ID_USUARIO_OWEN_SARAVIA ||  $this->session->userdata('idPersonaSession')    ==  1512)){//validacion para evitar agregar expediente
                    $vrPendiente = $this->m_bandeja_pre_certifica->haveActivoVR($itemplan, $row->idEstacion);
                    if($vrPendiente == '0'){
    
                        if($row->idEstacion == ID_ESTACION_FO ||
                            $row->idEstacion == ID_ESTACION_OC_FO ||
                            $row->idEstacion == ID_ESTACION_FO_DIST){
                            $has_sirope = $this->m_utils->getInfoItemplan($itemplan)['has_sirope'];
                            if($has_sirope  ==  1){
                                if($row->idEstacion == ID_ESTACION_FO && $validarFichatecnica){
                                    $has_ficha  =   $this->m_bandeja_pre_certifica->haveFichaValidadaPorTDP($itemplan, $row->idEstacion);
                                    if($has_ficha   >=  1){
                                        $contMO = $this->m_bandeja_pre_certifica->countNoValidadasSoloMOPqt($itemplan, $row->idEstacion);
                                        if(($contMO['has_ptr'] == 0 || $contMO['has_ptr'] == $contMO['no_aprob']) && $addExpe=='0'&& ($isEECC ||  $this->session->userdata('idPersonaSession')    ==  ID_USUARIO_ELSA_MEDINA ||  $this->session->userdata('idPersonaSession')    ==  ID_USUARIO_OWEN_SARAVIA ||  $this->session->userdata('idPersonaSession')    ==  1512)){
                                            $showBtnExpe = true;
                                        }else{
                                            if($addExpe=='0'){//SE ESCAPABA CON AQUELLOS QUE YA TENIAN EXPEDIENTE
                                                $btnNeedDJ = '<label style="color:red; font-weight: bold;">No cuenta con PO MO Liquidadas.</label>';
                                            }else{
                                                $btnNeedDJ = '';
                                            }
                                        }
                                        // $showBtnExpe = true;
                                    }else{
                                        $btnNeedDJ = '<label style="color:red; font-weight: bold;">No con cuenta con declaracion jurada validada.</label>';
                                    }
                                }else{// parche 09.07.2019 no estaba validando las po MO para estaciones diferentes a FO
                                    $contMO = $this->m_bandeja_pre_certifica->countNoValidadasSoloMOPqt($itemplan, $row->idEstacion);
                                    if(($contMO['has_ptr'] == 0 || $contMO['has_ptr'] == $contMO['no_aprob']) && $addExpe=='0'&& ($isEECC ||  $this->session->userdata('idPersonaSession')    ==  ID_USUARIO_ELSA_MEDINA ||  $this->session->userdata('idPersonaSession')    ==  ID_USUARIO_OWEN_SARAVIA ||  $this->session->userdata('idPersonaSession')    ==  1512)){
                                        $showBtnExpe = true;
                                    }else{
                                        if($addExpe=='0'){//SE ESCAPABA CON AQUELLOS QUE YA TENIAN EXPEDIENTE
                                            $btnNeedDJ = '<label style="color:red; font-weight: bold;">No cuenta con PO MO Liquidadas.</label>';
                                        }else{
                                            $btnNeedDJ = '';
                                        }
                                    }
                                    //$showBtnExpe = true;
                                }
                            }else{
                                $btnNeedDJ = '<label style="color:red; font-weight: bold;">No cuenta carga de archivo Sirope.</label>';
                            }
                        }else{
                            if($row->idEstacion   ==  ID_ESTACION_COAXIAL && $validarFichatecnica){
                                $has_ficha  =   $this->m_bandeja_pre_certifica->haveFichaValidadaPorTDP($itemplan, $row->idEstacion);
                                if($has_ficha   >=  1){
                                    $contMO = $this->m_bandeja_pre_certifica->countNoValidadasSoloMOPqt($itemplan, $row->idEstacion);
                                    if(($contMO['has_ptr'] == 0 || $contMO['has_ptr'] == $contMO['no_aprob']) && $addExpe=='0'&& ($isEECC ||  $this->session->userdata('idPersonaSession')    ==  ID_USUARIO_ELSA_MEDINA ||  $this->session->userdata('idPersonaSession')    ==  ID_USUARIO_OWEN_SARAVIA ||  $this->session->userdata('idPersonaSession')    ==  1512)){
                                        $showBtnExpe = true;
                                    }else{
                                        if($addExpe=='0'){//SE ESCAPABA CON AQUELLOS QUE YA TENIAN EXPEDIENTE
                                            $btnNeedDJ = '<label style="color:red; font-weight: bold;">No cuenta con PO MO Liquidadas.</label>';
                                        }else{
                                            $btnNeedDJ = '';
                                        }
                                    }
                                    //  $showBtnExpe = true;
                                }else{
                                    $btnNeedDJ = '<label style="color:red; font-weight: bold;">No con cuenta con declaracion jurada validada.</label>';
                                }
                            }else{
                                $contMO = $this->m_bandeja_pre_certifica->countNoValidadasSoloMOPqt($itemplan, $row->idEstacion);
                                if(($contMO['has_ptr'] == 0 || $contMO['has_ptr'] == $contMO['no_aprob']) && $addExpe=='0'&& ($isEECC ||  $this->session->userdata('idPersonaSession')    ==  ID_USUARIO_ELSA_MEDINA ||  $this->session->userdata('idPersonaSession')    ==  ID_USUARIO_OWEN_SARAVIA ||  $this->session->userdata('idPersonaSession')    ==  1512)){
                                    $showBtnExpe = true;
                                }else{
                                    if($addExpe=='0'){//SE ESCAPABA CON AQUELLOS QUE YA TENIAN EXPEDIENTE
                                        $btnNeedDJ = '<label style="color:red; font-weight: bold;">No cuenta con PO MO Liquidadas.</label>';
                                    }else{
                                        $btnNeedDJ = '';
                                    }
                                }
                                // $showBtnExpe = true;
                            }
                        }
    
                    }else if($vrPendiente != '0'){
                        $btnNeedDJ = '<label style="color:red; font-weight: bold;">Pendiente de Vale de Reserva.</label>';
                    }
                }
            }else{
                $contMO = $this->m_bandeja_pre_certifica->countNoValidadasSoloMOPqt($itemplan, $row->idEstacion);
                if(($contMO['has_ptr'] == 0 || $contMO['has_ptr'] == $contMO['no_aprob']) && $addExpe=='0'&& ($isEECC ||  $this->session->userdata('idPersonaSession')    ==  ID_USUARIO_ELSA_MEDINA ||  $this->session->userdata('idPersonaSession')    ==  ID_USUARIO_OWEN_SARAVIA ||  $this->session->userdata('idPersonaSession')    ==  1512)){
                    $showBtnExpe = true;
                }else{
                    if($addExpe=='0'){//SE ESCAPABA CON AQUELLOS QUE YA TENIAN EXPEDIENTE
                        $btnNeedDJ = '<label style="color:red; font-weight: bold;">No cuenta con PO MO Liquidadas.</label>';
                    }else{
                        $btnNeedDJ = '';
                    }
                }
            }
            if($addExpe != 0){
                $canValiUsua = $this->m_utils->canValidPoCertificacion($this->session->userdata('idPersonaSession'));
                if($canValiUsua>=1){
                    $actiFinalizado = $this->m_bandeja_pre_certifica->haveActivoFinalizado($itemplan, $row->idEstacion);
                    if($actiFinalizado  ==  0){
                        $showBtnEditMatPar =    true;
                    }
                }
            }
            $html .= '<div class="tab-pane '.$activa.' fade show" id="tab'.$row->idEstacion.'" role="tabpanel">
                                      <div class="table-responsive">';
             
            //$html .= $this->makeHTLMTablaPtrByItemplan($this->m_bandeja_pre_certifica->getPtrByItemplan($itemplan, $row->idEstacion),$row->porcentaje, $row->certificado, $itemplan, $row->idEstacion, $showBtnEditMatPar);
            $retorno = $this->makeHTLMTablaPtrByItemplan($this->m_bandeja_pre_certifica->getPtrByItemplan($itemplan, $row->idEstacion),$row->porcentaje, $row->certificado, $itemplan, $row->idEstacion, $showBtnEditMatPar, $fase);
            $html        .= $retorno['html'];
            $ponerMensaje = $retorno['bloquearEstacion'];
            /**NUEVA VALIDACION OWEN 21.02.2020 **/
            if($addExpe == 0){//luego de la validacion no mostrar si ya tiene expdiente
                $mensajeNuevoOwen ='<label style="color:red; font-weight: bold;">Obra no certificable.</label>';
                if($ponerMensaje){
                    $btnNeedDJ = $mensajeNuevoOwen;
                    $showBtnExpe = false;
                }
            }
            /**FIN NUEVA VALIDACION**/
    
            $html .= '<br><br><br>
                    <div style="text-align: center;width: 100%;padding-top: 20px">
                        <label style="font-weight: bold;">EXPEDIENTE</label><br>
                         '.$btnNeedDJ.'
                    </div>
               <div id="contBtnCerti'.$row->idEstacion.'" style="text-align: right; width: 95%;'.(($showBtnExpe) ? '' : 'display: none;').'" class="tab-container">
            <button data-idesta='.$row->idEstacion.' data-item='.$itemplan.' style="color: white;background-color: #204382;" class="btn btn-secondary waves-effect" data-toggle="modal" onclick="addExpedienteEstacion(this)">Expediente</button>
            </div>
            <div style="width: 100%;" id="contTablaCerti'.$row->idEstacion.'">
            '.$this->makeHTLMTablaCertificacionByITemplan($this->m_bandeja_pre_certifica->getCertificadoByItemPlan($itemplan, $row->idEstacion), $row->idEstacion).'
            </div>';
            $html .= '</div>
                                </div>';
            $activa = '';
        }
        $html .= ' </div>
                    </div>';
        return $html;
    }
}