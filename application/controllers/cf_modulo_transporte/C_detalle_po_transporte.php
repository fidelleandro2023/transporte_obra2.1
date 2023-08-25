<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_detalle_po_transporte extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_transporte/m_detalle_po_transporte');
        $this->load->model('mf_reportes_v/m_itemplan_ptr');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }
    
    public function index(){
        $logedUser = $this->session->userdata('usernameSession');
        if($logedUser != null){

                $item = (isset($_GET['item']) ? $_GET['item'] : '');
                //$est = (isset($_GET['est']) ? $_GET['est'] : '');


                $data['countPtr'] = $this->m_utils->getCountPtrPI($item);
                $data['item'] = $item;

                $data['listaEstaciones'] = $this->makeHTLMTEstaciones($this->m_detalle_po_transporte->getAllEstaciones($item), $item);

                //EDITAR
                $data['listaEstacionesEdit'] = $this->makeHTLMTEstacionesEdit($this->m_detalle_po_transporte->getAllEstaciones($item), $item);
                //AGREGAR
                //$data['listaEstacionesInsert'] = $this->makeHTLMTEstacionesInsert($this->m_detalle_po_transporte->getAllEstaciones($item), $item);
                $data['listaEstacionesInsertPI'] = $this->makeHTLMTEstacionesInsertPI($this->m_detalle_po_transporte->getAllEstaciones($item), $item);

            $data['ItemEstado'] = $this->m_detalle_po_transporte->getItemEstado($item);


            $data['listaEECC'] = $this->m_utils->getAllEECC();
               $data['listaZonal'] = $this->m_utils->getAllZonal();
               $data['listaSubProy'] = $this->m_utils->getAllSubProyecto();

               $data['tablaAsigGrafo'] = $this->makeHTLMTablaItemPtr($this->m_itemplan_ptr->getWebUnificadaFa('','','',''));
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
               $permisos =  $this->session->userdata('permisosArbol');
               #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_PLANTA_INTERNA, ID_PERMISO_HIJO_DETALLE_PLANTA_INTERNA);
               $result = $this->lib_utils->getHTMLPermisos($permisos, 309, 311, 8);
			   $data['opciones'] = $result['html'];
               //if($result['hasPermiso'] == true){
                     $this->load->view('vf_modulo_transporte/v_detalle_po_transporte',$data);
               //}else{
               //    redirect('login','refresh');
               //}
         }else{
             redirect('login','refresh');
        }

    }

        // bloque agregar

    public function makeHTLMTEstacionesInsert($listaEstaciones,$item){
        $html = '';
        foreach ($listaEstaciones->result() as $row) {
            $html .= '
            <div class="col-md-6">
                <div class="card" style="border: 1px solid lightgrey">
                    <div class="card-header">
                        <h2 class="card-title text-center">'.$row->estacionDesc.'</h2>
                    </div>
                    <div class="card-block">
                        <div class="row">';

            $html .= $this->makeHTLMTAreasInsert($this->m_detalle_po_transporte->getAllAreasByEstacion($item, $row->estacionDesc), $row->estacionDesc, $item); ;

            $html .= '</div>
                    </div>

                    
                </div>
            </div>
            ';
        }
        return utf8_decode($html);

    }

    public function makeHTLMTEstacionesInsertPI($listaEstaciones,$item){
        $html = '';
        foreach ($listaEstaciones->result() as $row) {
            $html .= '
            <div class="col-md-6">
                <div class="card" style="border: 1px solid lightgrey">
                    <div class="card-header">
                        <h2 class="card-title text-center">'.$row->estacionDesc.'</h2>
                    </div>
                    <div class="card-block">
                        <div class="row">';

            $html .= $this->makeHTLMTAreasInsertPI($this->m_detalle_po_transporte->getAllAreasByEstacion_old($item, $row->estacionDesc), $row->estacionDesc, $item); ;

            $html .= '</div>
                    </div>

                    
                </div>
            </div>
            ';
        }
        return utf8_decode($html);

    }
    public function makeHTLMTAreasInsert($data, $estacionDesc, $item){

        $htmlI ='';


        foreach($data->result() as $row){

            $htmlI.= '
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>'.utf8_encode($row->areaDesc).'</label>
                            <input type="text" data-tipo="'.$row->tipoArea.'" data-item="'.$item.'" data-subproyectoestacion="'.$row->idSubProyectoEstacion.'" data-area="'.utf8_encode($row->areaDesc).'" class="form-control input-mask insertar" name="ptrInsert[]" placeholder="" style="    border-bottom: 1px solid grey;">
                            <i class="form-group__bar"></i>
                        </div>
                    </div>
                    ';
            }

        return utf8_decode($htmlI);
    }

    public function makeHTLMTAreasInsertPI($data, $estacionDesc, $item){

        $htmlI ='';


        foreach($data->result() as $row){

            $htmlI.= '
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>'.utf8_encode($row->areaDesc).'</label>
                            <input onclick="createPTRPI(this)" type="text" data-tipo="'.$row->tipoArea.'" data-item="'.$item.'" data-subproyectoestacion="'.$row->idSubProyectoEstacion.'" data-area="'.utf8_encode($row->areaDesc).'" class="form-control input-mask insertar" name="ptrInsert[]" placeholder="" style="    border-bottom: 1px solid grey;">
                            <i class="form-group__bar"></i>
                        </div>
                    </div>
                    ';
        }

        return utf8_decode($htmlI);
    }
    public function makeHTLMTEstaciones($listaEstaciones, $item){
        //$data = $this->m_utils->getAllAreas($item);
        $html = '';
        //$html .= '';
        $cards = 0;
        foreach($listaEstaciones->result() as $row){
        $cards += 1;
        $html .= '
        <div class="col-md-3">                              
            <div class="card" style="border: 1px solid lightgrey">
                <div class="card-header" style="padding: 10px">
                    <h2 class="card-title text-center">'.$row->estacionDesc.'</h2>
                </div>
                <div class="row">';
        $html  .= $this->makeHTLMTAreas($this->m_detalle_po_transporte->getAllAreasByEstacion($item, $row->idEstacion), $row->estacionDesc, $item);
        $html.='</div>
            </div>                              
        </div>
        ';
        }
        if($cards == 0){
        	$html = '
                    <div class="col-md-12">
                        <div class="panel-info">
                            <h3 class="text-center">No hay asociaciones disponibles. </h3>
                            <hr>
                            <h4 class="text-center">Agregue una estacion al subproyecto para continuar.</h4>
                        </div>
                    </div>
                    ';
        }
        return utf8_decode($html);
    }
/** CZAVALACAS 18.07.2019**/
    
    public function makeHTLMTAreas($data, $estacionDesc, $item){
        $htmlAreas ='';
        foreach($data->result() as $row){
        $htmlAreas.= '
            <div class="col-md-6">
                <table class="table mb-0">
                    <thead >
                    <tr>';
            if($row->tipoArea == 'MO'){
                $onclick = '';
                $has_ptr = $this->m_detalle_po_transporte->getCountPoTransExist($row->itemPlan, $row->idEstacion, $row->tipoArea);
                if($has_ptr == 0){
                    $htmlAreas.= ' <th style="text-align: center;"><a href="getRegMoTransporte?item=' . $row->itemPlan . '&&idSub=' .$row->idSubProyectoEstacion.'" data-tipo="'.$row->tipoArea.'" data-item="'.$row->itemPlan.'" data-subproyectoestacion="'.$row->idSubProyectoEstacion.'" data-area="'.utf8_encode($row->areaDesc).'"  style="font-size: 10px; text-align: center">'.utf8_decode($row->areaDesc).'</a></th>';
                } else {
                    $htmlAreas.= ' <th style="text-align: center;"><a data-tipo="'.$row->tipoArea.'" data-item="'.$row->itemPlan.'" data-subproyectoestacion="'.$row->idSubProyectoEstacion.'" data-area="'.utf8_encode($row->areaDesc).'"  style="font-size: 10px; text-align: center">'.utf8_decode($row->areaDesc).'</a></th>';
                }
            }else if($row->tipoArea == 'MAT'){
                $htmlAreas.= '<th style="font-size: 10px; text-align: center"><a style="color:#8a8a5c" href="regPinPO?item=' . $row->itemPlan . '&form=1&estaciondesc=' . utf8_decode($estacionDesc) . '&estacion=' . $row->idEstacion . '"    target="_blank">' . utf8_decode($row->areaDesc) . '</a><th>';
            }
            $htmlAreas.= '</tr>
                    </thead>
                    <tbody>';
            if($row->tipoArea == 'MO'){
                $htmlAreas.= $this->makeHTLMPTRV2($this->m_detalle_po_transporte->getAllPTRbyArea($item, $estacionDesc, $row->areaDesc),2);
            }else if($row->tipoArea == 'MAT'){
                $htmlAreas.= $this->makeHTLMPTRV2($this->m_detalle_po_transporte->getAllPTRbyAreaMAT($item, $estacionDesc, $row->areaDesc),1);
            }        
            $htmlAreas.= '</tbody>
                </table>
            </div>
            ';

        }
        return utf8_encode($htmlAreas);
    }


    public function infoWebPi(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
        $ptrAjax = $this->input->post('ptr');
        $datos= $this->m_detalle_po_transporte->getInfoPtrInterna($ptrAjax);
        $btonEditarPtr = '';
        // if($datos['rangoPtr'] == 1  && $datos['tipoArea'] ==  'MO')
        if(($datos['idEstadoPlan'] == 3) && $datos['tipoArea'] ==  'MO'){
            $btonEditarPtr = '<button onclick="modificarPTRPI(this)" data-ptr="'.$datos['ptr'].'" data-item="'.$datos['itemplan'].'" style="color: #ffffff;background-color: #24ac1b;" type="button" class="btn btn-link" data-dismiss="modal">Editar PTR</button>';
         }        
        
        
        
        $html ='    <div class="col-md-12">
                        <h6 class="text-center" style="background-color: #1B84AC; color: white; padding: 2px">DETALLE PTR</h6>
                        <div class="card">
                            <table class="table table-bordered">
                               <tr>
                                   <th style="font-size:12px;" ><b>PO</b></th>
                                   <td>'.$datos['ptr'].'</td>
                                   <th style="font-size:12px;" ><b>VR</b></th>
                                   <td>'.$datos['vale_reserva'].'</td>
                                   <th style="font-size:12px;" ><b>ESTADO</b></th>
                                   <td>'.$datos['estado'].'</td>
                               </tr>
                             
                               <tr>
                                   <th style="font-size:12px;"><b>EE.CC</b></th>
                                   <td>'.$datos['empresaColabDesc'].'</td>
                                   <th style="font-size:12px;"><b>MONTO MO</b></th>
                                   <td>S/. '.$datos['costo_mo'].'</td>
                                   <th style="font-size:12px;"><b>MONTO MATERIAL</b></th>
                                   <td>S/. '.$datos['costo_mat'].'</td>
                               </tr>
                               <tr>
                                   <th style="font-size:12px;"><b>ZONAL</b></th>
                                   <td>'.$datos['zonalDesc'].'</td>
                                   <th style="font-size:12px;"><b>JEFATURA</b></th>
                                   <td>'.$datos['jefatura'].'</td>
                                   <th style="font-size:12px;"><b>AREA</b></th>
                                   <td>'.$datos['areaDesc'].'</td>
                               </tr>
                               <tr>
                                   <th style="font-size:12px;"><b>FECHA CREACION</b></th>
                                   <td>'.$datos['fecha_crea'].'</td>
                                   <th style="font-size:12px;"><b>FECHA APROBADA</b></th>
                                   <td>'.$datos['fecha_aprob'].'</td>
                                   <th style="font-size:12px;"><b>USUARIO</b></th>
                                   <td>'.$datos['usua_aprob'].'</td>
                               </tr>
                            </table>
                        </div>'.$btonEditarPtr.'</div>';
            $data['prueba']= $html;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
	
	
	function getDetallePoInfo(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
		
        try{
			$ptrAjax = $this->input->post('ptr');
			$htmlLOG = null;
			$html    = null;
			$arrayDetPO= $this->m_detalle_po_transporte->getDetallePOPqt($ptrAjax, ID_ESTACION_TRANSPORTE);
			
			// if($datos['rangoPtr'] == 1  && $datos['tipoArea'] ==  'MO')
						
			$arrayLogPO = $this->m_detalle_po_transporte->getDetalleLogPOTransporte($ptrAjax);
					
			$htmlLOG .= '<table id="tabla_log" class="table table-bordered">
							<thead class="thead-default">
								<tr>
									<th>ESTADO</th>
									<th>FECHA</th>
									<th>USUARIO</th>
								</tr>
							</thead>
							<tbody>';

			foreach ($arrayLogPO as $row) {
				$htmlLOG .= ' <tr>
								<th>' . $row['estado'] . '</th>
								<td>' . $row['fecha_registro'] . '</td>
								<td>' . $row['nombre'] . '</td>
							</tr>';
			}
			$htmlLOG .= '</tbody>
					</table>';	
			
			if($arrayDetPO['flg_tipo_area'] == 1){ // PO MATERIAL

				$htmlPresupuesto .='
									<tbody>
										<tr>
											<th>' . $arrayDetPO['pep1']. '</th>
											<td>' . $arrayDetPO['pep2']. '</td>
											<td>' . $arrayDetPO['grafo'] . '</td>
											<td></td>
											<td></td>
											<td>' . $arrayDetPO['vale_reserva'] . '</td>
											<td>' . number_format($arrayDetPO['costo_total'],2) . '</td>
										</tr>
									</tbody>
									</table>';
			}else if($arrayDetPO['flg_tipo_area'] == 2) {// PO MO
				$datos= $this->m_detalle_po_transporte->getInfoPOTransporteMo($ptrAjax);
				$btonEditarPtr = '';
				
				if($arrayDetPO['idEstadoPlan'] == 3) {
					$btonEditarPtr = '<button onclick="modificarPTRPI(this)" data-ptr="'.$datos['ptr'].'" data-item="'.$datos['itemplan'].'" style="color: #ffffff;background-color: #24ac1b;" type="button" class="btn btn-link" data-dismiss="modal">Editar PTR</button>';
				}
				$html ='<div class="col-md-12">
							<h6 class="text-center" style="background-color: #1B84AC; color: white; padding: 2px">DETALLE PO</h6>
							<div class="card">
								<table class="table table-bordered">
								   <tr>
									   <th style="font-size:12px;" ><b>PO</b></th>
									   <td>'.$datos['ptr'].'</td>
									   <th style="font-size:12px;" ><b>VR</b></th>
									   <td>'.$datos['vale_reserva'].'</td>
									   <th style="font-size:12px;" ><b>ESTADO</b></th>
									   <td>'.$datos['estado'].'</td>
								   </tr>
								 
								   <tr>
									   <th style="font-size:12px;"><b>EE.CC</b></th>
									   <td>'.$datos['empresaColabDesc'].'</td>
									   <th style="font-size:12px;"><b>MONTO MO</b></th>
									   <td>S/. '.$datos['costo_mo'].'</td>
									   <th style="font-size:12px;"><b>MONTO MATERIAL</b></th>
									   <td>S/. '.$datos['costo_mat'].'</td>
								   </tr>
								   <tr>
									   <th style="font-size:12px;"><b>ZONAL</b></th>
									   <td>'.$datos['zonalDesc'].'</td>
									   <th style="font-size:12px;"><b>JEFATURA</b></th>
									   <td>'.$datos['jefatura'].'</td>
									   <th style="font-size:12px;"><b>AREA</b></th>
									   <td>'.$datos['areaDesc'].'</td>
								   </tr>
								</table>
							</div>'.$btonEditarPtr.'</div>';
			}
        
        
        
            $data['prueba']= $html;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }


    public function makeHTLMPTRV2($data, $tipo){//$tipo 1 = material, $tipo 2 = MO
        $html = '';
        foreach ($data->result() as $row) {
            if($row->rangoPtr == 1){
                $fondo = '#FF8989';
            }elseif($row->rangoPtr == 2) {
                $fondo = '#13ab98';
            }elseif ($row->rangoPtr == 3) {
                $fondo = '#78E900';
            }elseif ($row->rangoPtr == 4) {
                $fondo = '#767680';
            }elseif ($row->rangoPtr == 5) {
                $fondo = '#d4d61c';
            }elseif ($row->rangoPtr == 6) {
                $fondo = 'steelblue';
            }
            else{
                $fondo = 'white';
            }
            
            $viewInfoPtr = '';
            if($tipo == 1){
                $viewInfoPtr    =   'onclick="getPtrInfoMat(this)"';
            }else if($tipo  ==  2){
                $viewInfoPtr    =   'onclick="getPtr(this)"';
            }
    
            $html .= '<tr style="background-color: '.$fondo.'; color: black">
                <td><a data-ptr="'.$row->ptr.'" data-estacion='.$row->idEstacion.' data-itemplan="'.$row->itemPlan.'" data-toggle="modal" '.$viewInfoPtr.' id="'.$row->ptr.'">'.$row->ptr.'</a></td>
            </tr>';
        }
        return utf8_decode($html);    
    }

    public function makeHTLMPTR($data){
        $html = '';
        foreach ($data->result() as $row) {
            if($row->rangoPtr == 1){                
                $fondo = '#FF8989';
            }elseif($row->rangoPtr == 2) {
                $fondo = '#13ab98';
            }elseif ($row->rangoPtr == 3) {
                $fondo = '#78E900';
            }elseif ($row->rangoPtr == 4) {
                $fondo = '#767680';
            }elseif ($row->rangoPtr == 5) {
                $fondo = '#d4d61c';
            }elseif ($row->rangoPtr == 6) {
                $fondo = 'steelblue';
            }
            else{
                $fondo = 'white';
            }

            $html .= '
            <tr style="background-color: '.$fondo.'; color: black">
                <td><a data-ptr="'.$row->ptr.'" data-toggle="modal" data-target="#modal-info" onclick="getPtr(this)" id="'.$row->ptr.'">'.$row->ptr.'</a></td>
            </tr>';         
        }
        return utf8_decode($html);


    }


    public function getColorFromEstado($data, $est){

        foreach($data->result() as $row){
            $estados = explode(",", $row->rangoPoDesc);
            if (in_array(trim(substr($est,0,3)), $estados, true)) {//ARRAY 1
                return $row->colorPo;
            }
        }
      return '#ffffff';
    }

    public function makeHTLMTablaItemPtr($listaPTR){
        $data = $this->m_itemplan_ptr->getRangoPtr();
       $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>                           
                            <th>ITEM PLAN</th>
                            <th>CENTRAL</th>
                            <th>INDICADOR</th>
                            <th>ZONAL</th>                            
                            <th>EECC</th>
                            <th>MAT_COAX</th>
                            <th>MAT_COAX_OC</th>
                            <th>MAT_FUENTE</th>
                            <th>MAT_FO</th>
                            <th>MAT_FO_OC</th>
                            <th>MAT_ENER</th>                          
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ITEM PLAN</th>
                            <th>CENTRAL</th>
                            <th>INDICADOR</th>
                            <th>ZONAL</th>                            
                            <th>EECC</th>
                            <th>MAT_COAX</th>
                            <th>MAT_COAX_OC</th>
                            <th>MAT_FUENTE</th>
                            <th>MAT_FO</th>
                            <th>MAT_FO_OC</th>
                            <th>MAT_ENER</th>           
                        </tr>
                    </tfoot>
                    <tbody>';

                foreach($listaPTR->result() as $row){

                $html .=' <tr>
                            <th>'.$row->itemPlan.'</th>
                            <td>'.$row->cod_central.'</td>
                            <td>'.$row->indicador.'</td>
                            <td>'.$row->zonal.'</td>
                            <td>'.$row->eecc.'</td>         
                            <td style="background-color:'.$this->getColorFromEstado($data, $row->mat_coax_est).'; color:white">'.$row->mat_coax_ptr.'</td>              
                            <td style="background-color:'.$this->getColorFromEstado($data, $row->mat_coax_oc_est).'; color:white">'.$row->mat_coax_oc_ptr.'</td>
                            <td style="background-color:'.$this->getColorFromEstado($data, $row->mat_fuente_est).'; color:white">'.$row->mat_fuente_ptr.'</td>
                            <td style="background-color:'.$this->getColorFromEstado($data, $row->mat_fo_est).'; color:white">'.$row->mat_fo_ptr.'</td>                          
                            <th style="background-color:'.$this->getColorFromEstado($data, $row->mat_fo_oc_est).'; color:white">'.$row->mat_fo_oc_ptr.'</th>
                            <th style="background-color:'.$this->getColorFromEstado($data, $row->mat_ener_est).'; color:white">'.$row->mat_ener_ptr.'</th>                            
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
            $mesEjec = $this->input->post('mes');
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaItemPtr($this->m_itemplan_ptr->getWebUnificadaFa($SubProy,$eecc,$zonal,$mesEjec));
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    // Metodos de editar

    public function makeHTLMTEstacionesEdit($listaEstaciones,$item){
        $htmlE = '';
        foreach ($listaEstaciones->result() as $row) {
            $htmlE .= '
            <div class="col-md-6">
                <div class="card" style="border: 1px solid #C9CDCF;">
                    <div class="card-header">
                        <h2 class="card-title text-center">'.$row->estacionDesc.'</h2>
                        
                    </div>
                    <div class="card-block">
                        <div class="row">';
             $htmlE .= $this->makeHTLMTAreasEdit($this->m_detalle_po_transporte->getAllAreasByEstacion($item, $row->estacionDesc), $row->estacionDesc, $item);

            $htmlE .=    '</div>
                    </div>
                </div>
            </div>
            ';
        }
        return utf8_decode($htmlE);

    }
    
    public function makeHTLMTAreasEdit($data, $estacionDesc, $item){
        $html ='';
        foreach($data->result() as $row){
        $html .= '
        <div class="col-sm-6">
            <label>'.$row->areaDesc.'</label>';
        $html .=  $this->makeHTLMPTREdit($this->m_detalle_po_transporte->getAllPTRbyArea($item, $estacionDesc, $row->areaDesc), $item);

        $html .= '</div>
        ';
        }
        return $html;
    }

    public function makeHTLMPTREdit($data,$item){
        $html = '';
        foreach ($data->result() as $row) {
            $html.= '
            <div class="form-group">
                 
                <input '.(($row->rangoPtr==1) ? 'onclick="modificarPTRPI(this)"' : 'disabled').' type="text" data-item="'.$item.'" data-ptr="'.$row->ptr.'" class="form-control input-mask editar" name="ptrEdit[]"  value="'.$row->ptr.'" style="    border-bottom: 1px solid grey;">
                <i class="form-group__bar"></i>               
             </div>
            ';
        }
        return $html;
    }

    public function makeHTLMPTREdits($data,$item){
        $html = '';
        foreach ($data->result() as $row) {
            $html.= '
            <div class="form-group">
                 
                <input '.(($row->idEstadoPlan==2) ? 'onclick="modificarPTRPI(this)"' : 'disabled').' type="text" data-item="'.$item.'" data-ptr="'.$row->ptr.'" class="form-control input-mask editar" name="ptrEdit[]"  value="'.$row->ptr.'" style="    border-bottom: 1px solid grey;">
                <i class="form-group__bar"></i>               
             </div>
            ';
        }
        return $html;
    }



    public function recogeEditarPi(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;

        try{
            $itemTitle = $this->input->post('itemTitle');
            $jsonNamesEdit = $this->input->post('jsonNamesEdit');
            $arrayNamesEdit = json_decode($jsonNamesEdit, true);
            $i= 0;


            foreach ($arrayNamesEdit as $row) {
                $subrows = explode("/", $row);

                $defaultValueEdit = $subrows[0];
                $newValueEdit = $subrows[1];
                $itemEdit = $subrows[2];
                $idsubproyestacionEdit = $subrows[3];

                if($defaultValueEdit != $newValueEdit){
                    if($newValueEdit == ''){
                        //delete
                        $data = $this->m_detalle_po_transporte->deletePTR($itemEdit, $defaultValueEdit, $idsubproyestacionEdit);
                    }else{
                        $data = $this->m_detalle_po_transporte->updatePTR($newValueEdit, $itemEdit, $defaultValueEdit, $idsubproyestacionEdit);
                    }
                    $i++;
                }

            }
            $data['listaEstaciones'] = $this->makeHTLMTEstaciones($this->m_detalle_po_transporte->getAllEstaciones($itemTitle), $itemTitle);
            $data['listaEstacionesEdit'] = $this->makeHTLMTEstacionesEdit($this->m_detalle_po_transporte->getAllEstaciones($itemTitle), $itemTitle);



        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function recogeInsertarPi(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        $resultado = 'resultado';
        $idUser = $this->session->userdata('idPersonaSession');
        try{

            $jsonNamesInsert = $this->input->post('jsonNamesInsert');
            $arrayNamesInsert = json_decode($jsonNamesInsert, true);


            $i= 0;

            foreach ($arrayNamesInsert as $row) {

                //$row[$i];
                $sub_rows = explode("/", $row);

                $valueInsert = $sub_rows[0];
                $itemInsert = $sub_rows[1];
                $idsubproyectoestacionInsert = $sub_rows[2];
                $areaInsert = $sub_rows[3];

                // Segunda Validacion
                $flag = $this->validacion2($this->m_detalle_po_transporte->findPTR($valueInsert));

                if($flag == 'libre'){
                    $this->db->trans_begin();
                    $data = $this->m_detalle_po_transporte->insertPTR($itemInsert, $valueInsert, $idsubproyectoestacionInsert);
                    // Insertar en DET SI NO ESTA WU
                    $exists = $this->findEnWU($this->m_detalle_po_transporte->findEnWU($valueInsert));


                    if($exists == 0){
                        // insertar en WUDET
                        if ($this->db->trans_status() === FALSE) {
                            $this->db->trans_rollback();
                            throw new Exception("ERROR4");

                        } else {

                            $this->m_detalle_po_transporte->insertEnWU($valueInsert);
                                if($this->db->trans_status() === FALSE){
                                    $this->db->trans_rollback();
                                    throw new Exception("ERROR5");
                                }else{
                                    $this->m_detalle_po_transporte->insertEnWUDet($valueInsert);
                                    if ($this->db->trans_status() === FALSE) {
                                        $this->db->trans_rollback();
                                        throw new Exception("ERROR6");
                                     }else{
                                        $this->m_detalle_po_transporte->getGrafoOnePTR($valueInsert);
                                        if ($this->db->trans_status() === FALSE) {
                                            $this->db->trans_rollback();
                                            throw new Exception("ERROR6");
                                        }else{
                                            $this->db->trans_commit();
                                            $data['error']    = EXIT_SUCCESS;
                                            $data['msj']      = 'Se registro correctamente.';
                                        }
                                     }
                                }

                        }
                    }else{
                        if ($this->db->trans_status() === FALSE) {
                            $this->db->trans_rollback();
                            throw new Exception("ERROR1");

                        } else{
                            $this->m_detalle_po_transporte->deleteEnWebUnitDet($valueInsert);
                             if($this->db->trans_status() === FALSE) {
                                 $this->db->trans_rollback();
                                 throw new Exception("ERROR2");
                             }else{
                                 $this->m_detalle_po_transporte->selectDet($valueInsert);
                                 if ($this->db->trans_status() === FALSE) {
                                    $this->db->trans_rollback();
                                    throw new Exception("ERROR3");
                                 }else{
                                     $this->m_detalle_po_transporte->getGrafoOnePTR($valueInsert);
                                     if ($this->db->trans_status() === FALSE) {
                                         $this->db->trans_rollback();
                                         throw new Exception("ERROR6");
                                     }else{
                                         $this->db->trans_commit();
                                         $data['error']    = EXIT_SUCCESS;
                                         $data['msj']      = 'Se registro correctamente.';
                                     }

                                 }
                             }
                        }
                    }



                    // Insert creacion en Log
                    $this->m_detalle_po_transporte->insertPTRenLog($itemInsert, $valueInsert, $idUser);
                     // Ejecutando getGrafoOnePTR()

                }else{


                    // TERCERA VALIDACION
                    if($areaInsert == $flag){
                        $this->db->trans_begin();
                        $data = $this->m_detalle_po_transporte->insertPTR($itemInsert, $valueInsert, $idsubproyectoestacionInsert);
                        // Insertar en DET SI NO ESTA EN WU
                        $exists = $this->findEnWU($this->m_detalle_po_transporte->findEnWU($valueInsert));
                        if($exists == 0){
                            // insertar en WUDET
                            if ($this->db->trans_status() === FALSE) {
                                $this->db->trans_rollback();
                                throw new Exception("ERROR4");

                            } else {

                                $this->m_detalle_po_transporte->insertEnWU($valueInsert);
                                if($this->db->trans_status() === FALSE){
                                    $this->db->trans_rollback();
                                    throw new Exception("ERROR5");
                                }else{
                                    $this->m_detalle_po_transporte->insertEnWUDet($valueInsert);
                                    if ($this->db->trans_status() === FALSE) {
                                        $this->db->trans_rollback();
                                        throw new Exception("ERROR6");
                                     }else{
                                         $this->m_detalle_po_transporte->getGrafoOnePTR($valueInsert);
                                         if ($this->db->trans_status() === FALSE) {
                                             $this->db->trans_rollback();
                                             throw new Exception("ERROR6");
                                         }else{
                                             $this->db->trans_commit();
                                             $data['error']    = EXIT_SUCCESS;
                                             $data['msj']      = 'Se registro correctamente.';
                                         }

                                     }
                                }

                            }
                        }else{
                            if ($this->db->trans_status() === FALSE) {
                                $this->db->trans_rollback();
                                throw new Exception("ERROR1");

                            } else {
                                $this->m_detalle_po_transporte->deleteEnWebUnitDet($valueInsert);
                                 if ($this->db->trans_status() === FALSE) {
                                     $this->db->trans_rollback();
                                     throw new Exception("ERROR2");
                                 }else{
                                     $this->m_detalle_po_transporte->selectDet($valueInsert);
                                     if ($this->db->trans_status() === FALSE) {
                                        $this->db->trans_rollback();
                                        throw new Exception("ERROR3");
                                     }else{
                                        $this->db->trans_commit();
                                        $data['error']    = EXIT_SUCCESS;
                                        $data['msj']      = 'Se actualizo correctamente!';

                                     }
                                 }
                            }

                        }




                        // Insert creacion en Log
                    	$this->m_detalle_po_transporte->insertPTRenLog($itemInsert, $valueInsert, $idUser);
                    	// Ejecutando getGrafoOnePTR()
                        //$this->m_detalle_po_transporte->getGrafoOnePTR("'".$valueInsert."'");
                        
                    }else{
                        _log('No se debe cargar');
                    }
                }
 
                $i++;
            }
            

        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        return $resultado;
    }

    public function validacion2($data){
        $flag = 'libre';

        foreach ($data->result() as $row) {
            $flag = $row->areaDesc;
        }
        return $flag;
    }

    public function findEnWU($data){
        $exists = 0;
        foreach ($data->result() as $row) {
            $exists++;
        }
        return $exists;


    }
}