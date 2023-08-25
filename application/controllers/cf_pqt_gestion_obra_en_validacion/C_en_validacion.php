<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_en_validacion extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_pqt_en_validacion/m_pqt_en_validacion');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index(){
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
	           $itemplan = (isset($_GET['itemplan']) ? $_GET['itemplan'] : '');
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
               $permisos =  $this->session->userdata('permisosArbol');
        	   $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_GESTION_SIOM, ID_PERMISO_HIJO_BANDEJA_SIOM);
        	   $data['tablaSiom']     = $this->getInfoEnVerificacion($itemplan);
        	   $data['opciones'] = $result['html'];
        	    $this->load->view('vf_pqt_gestion_obra_en_validacion/v_en_validacion',$data);
    	 }else{
        	 redirect('login','refresh');
	    }
    }
    
    function getInfoEnVerificacion($itemplan){
        
        $estacionesTrabajadas = $this->m_pqt_en_validacion->getEstacionesConPoNoCanceladas($itemplan);        
        $contenidoEstaciones ='';
        $hasVrPdt       = false;
        $hasPoNoLiqui   = false;
        foreach ($estacionesTrabajadas as $row){
            $contenidoEstaciones   .=              '<div class="tab-pane fade" id="tab'.$row->idEstacion.'" role="tabpanel">';
            $contenidoEstaciones   .=                   $this->getTablaSiom($itemplan, $row->idEstacion);
            $infoTablaPoByEstacion  = $this->geTablasPo($itemplan, $row->idEstacion);
            if($infoTablaPoByEstacion['numVrPdt'] > 0){
                $hasVrPdt = true;
            }
            if($infoTablaPoByEstacion['numPoNoLiqui'] > 0){
                $hasPoNoLiqui = true;
            }
            $contenidoEstaciones   .=    $infoTablaPoByEstacion['html'];               
            $contenidoEstaciones   .=              '</div>';
        }        
        
        $outPutContenidoSirope = $this->getContenidoSirope($itemplan);
        $contenidoSirope =  $outPutContenidoSirope['html'];
        
        $otPrincipal = true;
        
        $html = '<div class="card" style="MARGIN-TOP: -28px;">
                    <div class="card-block">
                        <div class="tab-container">
                            <ul class="nav nav-tabs nav-fill" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#contResu" role="tab">RESUMEN</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#contSirope" role="tab">SIROPE</a>
                                </li>';
                            $numEsta = 0;
                            $numEstaLiquidadas = 0;
                            foreach ($estacionesTrabajadas as $row){
                                $style='';
                                if($row->porcentaje=='100'){
                                    $numEstaLiquidadas++;
                                }else{
                                    $style='style="color:red"';
                                }
        $html   .=              '<li class="nav-item">
                                    <a '.$style.' class="nav-link" data-toggle="tab" href="#tab'.$row->idEstacion.'" role="tab">'.utf8_decode($row->estacionDesc).' ('.(($row->porcentaje == null) ? '0' : $row->porcentaje).'%)</a>
                                </li>';
                                
                                $numEsta++;
                            }
                            $estacionesLiquidadas = false;
                            if($numEsta > 0 && $numEstaLiquidadas==$numEsta){//validacion todas estaciones liquidadas.
                                $estacionesLiquidadas = true;
                            }
        $html   .=         '</ul>
                            <div class="tab-content">
                                <div class="tab-pane active fade show" id="contResu" role="tabpanel">';
        $html   .=                  $this->contenidoChecks($estacionesLiquidadas, $outPutContenidoSirope, $hasVrPdt, $hasPoNoLiqui, $itemplan);
        $html   .=             '</div>
                                <div class="tab-pane fade" id="contSirope" role="tabpanel">';
        $html   .=                  $contenidoSirope;       
        $html   .=              '</div>';
        $html   .=                  $contenidoEstaciones; 
        $html   .=        '</div>
                        </div>
                    </div>
                </div>';
        return $html;
    }
    
    function getTablaSiom($itemPlan, $idEstacion) {
        $data = $this->m_pqt_en_validacion->getOSSiomFromItemplanEstacion($itemPlan, $idEstacion);
        $html = '<p class="color_en_Verificacion_fuente">SIOM</p>
                    <table class="table table-bordered">
                        <thead class="thead-default">
                            <tr>
                                <th>CODIGO PO</th>
                                <th>ESTACION</th>
                                <th>CODIGO SIOM</th>
                                <th>ESTADO</th>
                                <th>FECHA ULTIMO ESTADO</th>  
                            </tr>
                        </thead>
                        <tbody>';
        if($data!=null){
            foreach($data as $row){
    
                $html .='<tr>
                                <th>'.$row->ptr.'</th>
                                <th>'.$row->estacionDesc.'</th>
                                <th>'.$row->codigoSiom.'</th>
                                <th>'.$row->ultimo_estado.'</th>
                                <th>'.$row->fecha_ultimo_estado.'</th>
                            </tr>';
            }
        }else{
            $html .='<tr>
                                <th>---</th>
                                <th>---</th>
                                <th>SIN ORDEN DE SERVICIO</th>
                                <th>---</th>
                                <th>---</th>
                            </tr>';
        }
        $html .='</tbody>
                    </table>';
    
        return $html;
    }

    function geTablasPo($itemPlan, $idEstacion) {
        $numVRPdt = 0;
        $numPoMONoLiquidada = 0;
        $data = $this->m_pqt_en_validacion->getPoByItemplanEstacion($itemPlan, $idEstacion);
        $html = '<p class="color_en_Verificacion_fuente">PO</p>
                <table class="table table-bordered">
                        <thead class="thead-default">
                            <tr>
                                <th>CODIGO PO</th>
                                <th>TIPO</th>
                                <th>ESTACION</th>
                                <th>COSTO TOTAL</th>
                                <th>ESTADO</th>
                                <th>VR PDT</th>
                            </tr>
                        </thead>
                        <tbody>';
            if($data!=null){
                foreach($data as $row){
                    $style='';
                    $hasVR = '---';
                    if($row->flg_tipo_area==1){
                        $inFoVR = $this->m_pqt_en_validacion->getcodSolVRPDTByItemPanPtr($itemPlan, $row->codigo_po);
                        if($inFoVR == NULL){
                            $hasVR = 'NO';
                        }else{
                            $style='style="color:red"';
                            $hasVR = $inFoVR['codigo'];
                            $numVRPdt++;
                        }
                    }else if($row->flg_tipo_area==2){
                        if($row->estado_po == PO_REGISTRADO || $row->estado_po == PO_PREAPROBADO || $row->estado_po == PO_APROBADO){
                            $numPoMONoLiquidada++;
                            $style='style="color:red"';
                        }
                    }
                    $html .='<tr '.$style.'>
                                <th>'.$row->codigo_po.'</th>
                                <th>'.$row->tipoArea.'</th>
                                <th>'.utf8_decode($row->estacionDesc).'</th>
                                <th>'.$row->costo_total.'</th>   
                                <th>'.$row->estado.'</th>
                                <th>'.$hasVR.'</th>
                            </tr>';
                }
            }
            $html .='</tbody>
                    </table>';
            $outPut = array();
            $outPut['html']             = $html;
            $outPut['numVrPdt']         = $numVRPdt;
            $outPut['numPoNoLiqui']     = $numPoMONoLiquidada;
            return $outPut;
        return $outPut;
    }
    
    function getContenidoSirope($itemplan) {
        
        $has_ot_prin    = false;
        $ot_prin_4      = false;
        $has_ot_ac      = false;
        $ot_ac_4        = false;
        $has_ot_coax    = false;
        $ot_coax_4      = false;
        
        $inforSirope = $this->m_pqt_en_validacion->getInfoSiropeByItemplan($itemplan);
        
        $codigo_ot_principal = 'NO TIENE';
        $estado_ot_principal = '---';
        if($inforSirope['ult_codigo_sirope']!=null){
            $has_ot_prin = true;
            $ot_prin_4 = (($inforSirope['has_sirope']==1) ? true : false);
            $codigo_ot_principal = $inforSirope['ult_codigo_sirope'];
            $estado_ot_principal = $inforSirope['ult_estado_sirope'];
        }else if($inforSirope['ot_prin']!=null){
            $has_ot_prin = true;            
            $codigo_ot_principal = $inforSirope['ot_prin'];
            $estado_ot_principal = 'PDT DE ACTUALIZACION';
        }
        
        //LOGICA COAXIAL
        $codigo_ot_coaxial = 'NO TIENE';
        $estado_ot_coaxial = 'PDT DE GENERAR OT';
        $requiereOTCoax = $this->m_utils->requiereOTCoaxial($inforSirope['idSubProyecto']);
        if($requiereOTCoax > 0){
            if($inforSirope['ult_codigo_sirope_coax']!=null){
                $has_ot_coax = true;
                $ot_coax_4 = (($inforSirope['has_sirope_coax']==1) ? true : false);
                $codigo_ot_coaxial = $inforSirope['ult_codigo_sirope_coax'];
                $estado_ot_coaxial = $inforSirope['ult_estado_sirope_coax'];
            }else if($inforSirope['ot_coax']!=null){           
                $has_ot_coax = true;
                $codigo_ot_coaxial = $inforSirope['ot_coax'];
                $estado_ot_coaxial = 'PDT DE ACTUALIZACION';
            }            
        }
        //FIN LOGICA COAXIAL
        
        $codigo_ot_actualizacion = 'NO TIENE';
        $estado_ot_actualizacion = '---';
        if($inforSirope['utilmo_codigo_sirope_ac']!=null){
            $has_ot_ac      = true;
            $ot_ac_4 = (($inforSirope['has_sirope_ac']==1) ? true : false);
            $codigo_ot_actualizacion = $inforSirope['utilmo_codigo_sirope_ac'];
            $estado_ot_actualizacion = $inforSirope['ultimo_estado_sirope_ac'];
        }else if($inforSirope['ot_ac']!=null){
            $has_ot_ac      = true;
            $codigo_ot_actualizacion = $inforSirope['ot_ac'];
            $estado_ot_actualizacion = 'PDT DE ACTUALIZACION';
        }
        $html = '<div class="row">
                     <div class="col-sm-6 col-md-6">
                            <p class="color_en_Verificacion_fuente">OT FO PRINCIPAL</p>
                            <table class="table table-bordered">
                                <thead class="thead-default">
                                    <tr>
                                        <th>CODIGO OT</th>
                                        <th>ESTADO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th>'.$codigo_ot_principal.'</th>
                                        <th>'.utf8_decode($estado_ot_principal).'</th>
                                    </tr>
                                </tbody>
                            </table>
                    </div>
                    <div class="col-sm-6 col-md-6">
                             <p class="color_en_Verificacion_fuente">OT ACTUALIZACION</p>
                            <table class="table table-bordered">
                                <thead class="thead-default">
                                    <tr>
                                        <th>CODIGO OT</th>
                                        <th>ESTADO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th>'.$codigo_ot_actualizacion.'</th>
                                        <th>'.utf8_decode($estado_ot_actualizacion).'</th>
                                    </tr>
                                </tbody>
                            </table>
                    </div>';
        if($requiereOTCoax > 0){
            $html .= '<div class="col-sm-6 col-md-6">
                             <p class="color_en_Verificacion_fuente">OT COAXIAL</p>
                            <table class="table table-bordered">
                                <thead class="thead-default">
                                    <tr>
                                        <th>CODIGO OT</th>
                                        <th>ESTADO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th>'.$codigo_ot_coaxial.'</th>
                                        <th>'.utf8_decode($estado_ot_coaxial).'</th>
                                    </tr>
                                </tbody>
                            </table>
                    </div>';
        }
        
        $html .= '</div>';
        $outPut = array();
        $outPut['html']         = $html;
        $outPut['has_ot_prin']  = $has_ot_prin;
        $outPut['ot_prin_4']    = $ot_prin_4;
        $outPut['has_ot_ac']    = $has_ot_ac;
        $outPut['ot_ac_4']      = $ot_ac_4;
        return $outPut;
    }
    
    function contenidoChecks($estacionesLiquidadas, $infoSirope, $hasVrPdt, $hasPoNoLiqui, $itemplan) {      
		$idSubProyecto     = $this->m_utils->getInfoItemplan($itemplan)['idSubProyecto'];
		$noRequiereOTFO    = $this->m_utils->NorequiereOTFO($idSubProyecto);
		$requiereOTCoaxial = $this->m_utils->requiereOTCoaxial($idSubProyecto);
		$msj_sirope = 'OT SIROPE FO EN 04?';
		//if(in_array($idSubProyecto,array(155, 279, 283, 553, 554, 579, 582))){//VALIDACION PARA RUTAS CZAVALA 14.07.2020
		if($noRequiereOTFO    >   0){
			$infoSirope['ot_prin_4'] = TRUE;
			$msj_sirope = 'NO REQUIERE SIROPE';
		}
        $html = '<div class="row">
                    <div class="col-sm-4 col-md-4">
                    </div>
                     <div class="col-sm-4 col-md-4" style="text-align: left;">
                             <p class="color_en_Verificacion_fuente">CONDICIONES PARA VALIDACION</p>
                            <br>
                           <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" '.(($estacionesLiquidadas) ? 'checked' : '').' disabled>
                                <span class="custom-control-indicator"></span>
                                <span '.(($estacionesLiquidadas) ? '' : 'style="color:red"').' class="custom-control-description">ESTACIONES LIQUIDADAS (100%)?</span>
                            </label>
                            <div class="clearfix mb-2"></div>
                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" '.(($infoSirope['ot_prin_4']) ? 'checked' : '').' disabled>
                                <span class="custom-control-indicator"></span>
                                <span '.(($infoSirope['ot_prin_4']) ? '' : 'style="color:red"').' class="custom-control-description">'.$msj_sirope.'</span>
                            </label>';
                            if($infoSirope['has_ot_ac']){
          $html .= '        <div class="clearfix mb-2"></div>
                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" '.(($infoSirope['ot_ac_4']) ? 'checked' : '').' disabled>
                                <span class="custom-control-indicator"></span>
                                <span '.(($infoSirope['ot_ac_4']) ? '' : 'style="color:red"').' class="custom-control-description">OT SIROPE ACTUALIZACION EN 04?</span>
                            </label>';
                            }
                            if($requiereOTCoaxial   >   0){
          $html .= '        <div class="clearfix mb-2"></div>
                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" '.(($infoSirope['ot_ac_4']) ? 'checked' : '').' disabled>
                                <span class="custom-control-indicator"></span>
                                <span '.(($infoSirope['ot_ac_4']) ? '' : 'style="color:red"').' class="custom-control-description">OT SIROPE COAXIAL EN 04?</span>
                            </label>';
                            }
          $html .= '        <div class="clearfix mb-2"></div>
                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" '.((!$hasVrPdt) ? 'checked' : '').' disabled>
                                <span class="custom-control-indicator"></span>
                                <span '.((!$hasVrPdt) ? '' : 'style="color:red"').' class="custom-control-description">SIN VALE RESERVA PENDIENTE?</span>
                            </label> 
                            <div class="clearfix mb-2"></div>  
                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" '.((!$hasPoNoLiqui) ? 'checked' : '').' disabled>
                                <span class="custom-control-indicator"></span>
                                <span '.((!$hasPoNoLiqui) ? '' : 'style="color:red"').' class="custom-control-description">TODAS PO MO LIQUIDADOS?</span>
                            </label>                         
                    </div>
                    <div class="col-sm-4 col-md-4">
                    </div>
                </div>';
       
        return $html;
    }
    
    
}