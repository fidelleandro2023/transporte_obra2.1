<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * @author CARLOS ZAVALA C.
 * 18/01/2018
 *
 */
class C_bandeja_pre_aprob_mo_2_consulta extends CI_Controller {

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
	    if($logedUser != null){
        	   $data['listaEECC'] = $this->m_utils->getAllEECC();
        	   $data['listaZonal'] = $this->m_utils->getAllZonal();
        	   $data['listaSubProy'] = $this->m_utils->getAllSubProyecto();
               $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo(null);	
               $data['itemplanList'] = $this->makeHTMLOptionsChoice($this->m_bandeja_pre_certifica->getItemplanExpediente());
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');	
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');	               
        	   $permisos =  $this->session->userdata('permisosArbol');
        	   
        	   $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_CERTIFICACION_MO, ID_PERMISO_HIJO_BANDEJA_PRE_CERTIFICACION_II_CONSULTA);
        	   
        	   /*
        	   $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_BANDEJAS, ID_PERMISO_HIJO_BANDEJA_PRE_CERTIFICACION_II_CONSULTA);*/

        	   
        	   
        	   $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	       $this->load->view('vf_liquidacion/v_bandeja_pre_aprob_mo_2_consulta',$data);
        	   }else{
        	       redirect('login','refresh');
	           }
	   }else{
	       redirect('login','refresh');
	   }
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
                            <th>Fec. Prevista</th>
                            <th>Estado</th>
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
                            <th>'.$row->fechaPrevEjec.'</th> 
                            <th>'.$row->estadoPlanDesc.'</th>  
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
            if($SubProy=='' && $eecc=='' && $zonal=='' && $itemPlan=='' && $mesEjec=='' && $expediente==''){
                $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo(null);
                
            }else{
                $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_bandeja_pre_certifica->getBandejaPreMo($SubProy,$eecc,$zonal,$itemPlan,$mesEjec,$expediente));
                
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
            $addExpe = $this->m_bandeja_pre_certifica->haveActivo($itemplan, $row->idEstacion);
            $html .= '<div class="tab-pane '.$activa.' fade show" id="tab'.$row->idEstacion.'" role="tabpanel">
                                  <div class="row">';
            $html .= $this->makeHTLMTablaPtrByItemplan($this->m_bandeja_pre_certifica->getPtrByItemplan($itemplan,$row->idEstacion),$row->porcentaje, $row->certificado);
            $html .= '<br><br><br>
                    <div style="text-align: center;width: 100%;padding-top: 20px">
                        <label style="font-weight: bold;">EXPEDIENTE</label>
                    </div>
               <div id="contBtnCerti'.$row->idEstacion.'" style="text-align: right; width: 95%;'.(($addExpe=='0' && $row->porcentaje=='100')? '' : 'display: none;').'" class="tab-container">
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
    public function makeHTLMTablaPtrByItemplan($listaPTR, $porcentaje, $certificado){
         
        $html = '<table id="data-table2" class="table table-bordered" style="font-size: smaller;">
                    <thead class="thead-default">
                        <tr>
                            <th></th>
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
            
            $estadoFinal = '';
            if($row->estado_wu == $row->estado_wud){
                $estadoFinal = $row->estado_wud;
            }else{
                $estadoFinal = $row->estado_wu;
            }
            
            $html .=' <tr '.((substr($estadoFinal,0,3) == '003' || substr($estadoFinal,0,3) == '001' ) ? '' : ' style = "background-color: gainsboro;"').'>
                           <td> <label class="custom-control custom-checkbox">
                                <input onclick="return false;" disabled value="'.$row->ptr.','.$row->itemplan.'" type="checkbox" class="custom-control-input"  '.(($row->hasPtrExpe==1) ? 'checked' : '').'>
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
        return utf8_decode($html);
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
                            <th></th>                                         
                        </tr>
                    </thead>
    
                    <tbody>';
         
        foreach($listaPTR->result() as $row){
    
            $html .='<tr '.(($row->estado!='ACTIVO') ? '' : ' style = "background-color: #33a2264a;"').'>          
                        <td></td>    
						<td>'.$row->fecha.'</td>
                        <td>'.$row->usuario.'</td>
						<td>'.$row->comentario.'</td>
						<td>'.$row->estado_final.'</td>
                        <td></td>
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
            $data['tabCerti'] = $this->makeHTLMTablaCertificacionByITemplan($this->m_bandeja_pre_certifica->getCertificadoByItemPlan($itemplan, $estacion), $estacion);
            
            //$data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_bandeja_pre_certifica->getBandejaPreMo($SubProy,$eecc,$zonal,$itemPlanFil,$mesEjec,$expediente));
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
            $dato = $this->input->post('dato');
            
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
    
}