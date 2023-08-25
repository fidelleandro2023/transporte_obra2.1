<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * @author CARLOS ZAVALA C.
 * 18/01/2018
 *
 */
class C_bandeja_pre_aprob_mo extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_liquidacion/m_liquidacion');
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
               $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_liquidacion->getBandejaPreMo('','','','','',''));	
               $data['itemplanList'] = $this->m_liquidacion->getItemplanExpediente();
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');	
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');	               
        	   $permisos =  $this->session->userdata('permisosArbol');
        	   $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_BANDEJAS, ID_PERMISO_HIJO_BANDEJA_PRE_CERTIFICACION);
        	   $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	       $this->load->view('vf_liquidacion/v_bandeja_pre_aprob_mo',$data);
        	   }else{
        	       redirect('login','refresh');
	           }
	   }else{
	       redirect('login','refresh');
	   }
    }
    
    public function makeHTLMTablaBandejaAprobMo($listaPTR){     
        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th style="width: 10px;"></th>  
                            <th style="width: 10px;"></th>                             
                            <th>Item Plan</th>    
                            <th>Indicador</th>     
                            <th>Sub Proy</th>
                            <th>Zonal</th>
                            <th>EECC</th>
                            <th>Fec. Prevista</th>
                        </tr>
                    </thead>
                   
                    <tbody>';
		   																			                                                   
                foreach($listaPTR->result() as $row){              
                    
                $html .=' <tr>
                            <th>'.(($row->hasExpe=='1' && $row->hasDise=='0') ? '<a data-itemplan ="'.$row->itemPlan.'"  onclick="aprobarCertificado(this)"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/circle-check-128.png"></a>' : '').'</th>
                            <td>'.(($row->hasExpe=='1') ? '<img alt="Editar" height="20px" width="20px" src="public/img/iconos/expediente.png">' : '').'</td>
							<td style="color : blue"><a data-itm ="'.$row->itemPlan.'" onclick="getPTRSByItemplan(this)">'.$row->itemPlan.'</a></td>	
                            <td>'.$row->indicador.'</td>							
                            <td>'.$row->subProyectoDesc.'</td>
							<td>'.$row->zonalDesc.'</td>
							<th>'.$row->empresaColabDesc.'</th>							
                            <th>'.$row->fechaPrevEjec.'</th>   
						</tr>';
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
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_liquidacion->getBandejaPreMo($SubProy,$eecc,$zonal,$itemPlan,$mesEjec,$expediente));
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
            $data['tabPtrItm'] = $this->makeHTLMTablaPtrByItemplan($this->m_liquidacion->getPtrByItemplan($itemplan));
            $data['tabCerti'] = $this->makeHTLMTablaCertificacionByITemplan($this->m_liquidacion->getCertificadoByItemPlan($itemplan));
            $data['hasActivo'] = $this->m_liquidacion->haveActivo($itemplan);
            $data['error']    = EXIT_SUCCESS;
           
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function makeHTLMTablaPtrByItemplan($listaPTR){
         
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
                                <input '.((utf8_decode($row->estacionDesc)=='DISEÑO') ? 'onclick="return false;" disabled ': 'onclick="chequed(this)"').' value="'.$row->ptr.','.$row->itemplan.'" type="checkbox" class="custom-control-input"  '.(($row->hasPtrExpe==1) ? 'checked' : '').'>
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

    public function makeHTLMTablaCertificacionByITemplan($listaPTR){
         
        $html = '<table id="data-table3" class="table table-bordered" style="font-size: smaller;">
                    <thead class="thead-default">
                        <tr>    
                            <th></th>
                            <th>FECHA</th>                            
                            <th>USUARIO</th>
                            <th>COMENTARIO</th>
                            <th>ESTADO</th>                                         
                        </tr>
                    </thead>
    
                    <tbody>';
         
        foreach($listaPTR->result() as $row){
    
            $html .='<tr '.(($row->estado!='ACTIVO') ? '' : ' style = "background-color: #33a2264a;"').'>          
                        <td>'.(($row->estado=='ACTIVO') ? '<a data-id="'.$row->id.'" data-itemplan="'.$row->itemplan.'" onclick="cancelCertificado(this)"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/delete.png"></a>' : '').'</td>    
						<td>'.$row->fecha.'</td>
                        <td>'.$row->usuario.'</td>
						<td>'.$row->comentario.'</td>
						<td>'.$row->estado.'</td>
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
            
            $id = $this->input->post('id');   
            $itemplan = $this->input->post('itemplan');   
            $data = $this->m_liquidacion->cancelCertificado($id);  
            if($data['error']==EXIT_ERROR){
                throw new Exception('ERROR AL CANCELAR CERTIFICADO');
            }  
            $data['tabCerti'] = $this->makeHTLMTablaCertificacionByITemplan($this->m_liquidacion->getCertificadoByItemPlan($itemplan));    
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_liquidacion->getBandejaPreMo($SubProy,$eecc,$zonal,$itemPlanFil,$mesEjec,$expediente));
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
            
            $itemplan = $this->input->post('itemplan');
            $fecha = $this->input->post('fecha');
            $comentario = $this->input->post('comentario');
            $data = $this->m_liquidacion->saveCertificado($itemplan,$fecha,strtoupper($comentario));
            if($data['error']==EXIT_ERROR){
                throw new Exception('ERROR AL INSERTAR CERTIFICADO');
            }
            $data['tabCerti'] = $this->makeHTLMTablaCertificacionByITemplan($this->m_liquidacion->getCertificadoByItemPlan($itemplan));  
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_liquidacion->getBandejaPreMo($SubProy,$eecc,$zonal,$itemPlanFil,$mesEjec,$expediente));
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
            
            $data = $this->m_liquidacion->preAprobarTerminados($itemplan);
            if($data['error']==EXIT_ERROR){
                throw new Exception('ERROR AL aprobCertiFicado');
            }
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_liquidacion->getBandejaPreMo($SubProy,$eecc,$zonal,$itemPlanFil,$mesEjec,$expediente));
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
            $data = $this->m_liquidacion->insertOrDeletePtrExpediente($accion, $ptr, $itemplan);
            if($data['error']==EXIT_ERROR){
                throw new Exception('ERROR AL checkPtrItem');
            }
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
}