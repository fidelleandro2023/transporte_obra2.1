<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_detalle_planta_interna extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_plantaInterna/M_detalle_planta_interna');
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

                $data['listaEstaciones'] = $this->makeHTLMTEstaciones($this->M_detalle_planta_interna->getAllEstaciones($item), $item);

                //EDITAR
                $data['listaEstacionesEdit'] = $this->makeHTLMTEstacionesEdit($this->M_detalle_planta_interna->getAllEstaciones($item), $item);
                //AGREGAR
                //$data['listaEstacionesInsert'] = $this->makeHTLMTEstacionesInsert($this->M_detalle_planta_interna->getAllEstaciones($item), $item);
                $data['listaEstacionesInsertPI'] = $this->makeHTLMTEstacionesInsertPI($this->M_detalle_planta_interna->getAllEstaciones($item), $item);

            $data['ItemEstado'] = $this->M_detalle_planta_interna->getItemEstado($item);


            $data['listaEECC'] = $this->m_utils->getAllEECC();
               $data['listaZonal'] = $this->m_utils->getAllZonal();
               $data['listaSubProy'] = $this->m_utils->getAllSubProyecto();

               $data['tablaAsigGrafo'] = $this->makeHTLMTablaItemPtr($this->m_itemplan_ptr->getWebUnificadaFa('','','',''));
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
               $permisos =  $this->session->userdata('permisosArbolTransporte');
               #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_PLANTA_INTERNA, ID_PERMISO_HIJO_DETALLE_PLANTA_INTERNA);
               $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_PLANTA_INTERNA, ID_PERMISO_HIJO_DETALLE_PLANTA_INTERNA, ID_MODULO_PAQUETIZADO);
               $data['opciones'] = $result['html'];
               //if($result['hasPermiso'] == true){
                     $this->load->view('vf_plantaInterna/v_detalle_planta_interna',$data);
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

            $html .= $this->makeHTLMTAreasInsert($this->M_detalle_planta_interna->getAllAreasByEstacion($item, $row->estacionDesc), $row->estacionDesc, $item); ;

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

            $html .= $this->makeHTLMTAreasInsertPI($this->M_detalle_planta_interna->getAllAreasByEstacion_old($item, $row->estacionDesc), $row->estacionDesc, $item); ;

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
			if($row->idContrato == 1) {
				$htmlAreas = '<input onclick="createPTRPI(this)" type="text" data-tipo="'.$row->tipoArea.'" data-item="'.$item.'" data-subproyectoestacion="'.$row->idSubProyectoEstacion.'" data-area="'.utf8_encode($row->areaDesc).'" class="form-control input-mask insertar" name="ptrInsert[]" placeholder="" style="    border-bottom: 1px solid grey;">';
			} else {
				$htmlAreas = '<th style="font-size: 10px; text-align: center"><a style="color:#8a8a5c" href="rePoMo?item=' . $row->itemPlan . '&form=1&estaciondesc=' . utf8_decode($estacionDesc) . '&estacion=' . $row->idEstacion . '"    target="_blank">' . utf8_decode($row->areaDesc) . '</a><th>';
				
			}
			
            $htmlI.= '
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>'.utf8_encode($row->areaDesc).'</label>
                            '.$htmlAreas.'
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
        $html  .= $this->makeHTLMTAreas($this->M_detalle_planta_interna->getAllAreasByEstacion($item, $row->idEstacion), $row->estacionDesc, $item);
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
			
			$has_ptr = $this->M_detalle_planta_interna->getCountPtrPlantaInterna($row->itemPlan, $row->idEstacion, $row->tipoArea);
			if($row->tipoArea == 'MO'){
				if($has_ptr > 0) {
					$htmlAreas.= ' <th style="text-align: center;"><a data-tipo="'.$row->tipoArea.'" data-item="'.$row->itemPlan.'" data-subproyectoestacion="'.$row->idSubProyectoEstacion.'" data-area="'.utf8_encode($row->areaDesc).'"  style="font-size: 10px; text-align: center">'.utf8_decode($row->areaDesc).'</a></th>';
				} else {
					$htmlAreas.= '<th style="font-size: 10px; text-align: center"><a style="color:#8a8a5c" href="rePoMo?item=' . $row->itemPlan . '&form=1&estaciondesc=' . utf8_decode($estacionDesc) . '&estacion=' . $row->idEstacion . '"    target="_blank">' . utf8_decode($row->areaDesc) . '</a><th>';
				}
				// if($row->idContrato == 1){
					// $onclick = '';
					// if($has_ptr == 0){
						// $onclick =   'onclick="createPTRPI(this)"';
					// }
					
					// $htmlAreas.= ' <th style="text-align: center;"><a '.$onclick.' data-tipo="'.$row->tipoArea.'" data-item="'.$row->itemPlan.'" data-subproyectoestacion="'.$row->idSubProyectoEstacion.'" data-area="'.utf8_encode($row->areaDesc).'"  style="font-size: 10px; text-align: center">'.utf8_decode($row->areaDesc).'</a></th>';
			
				// } else if($has_ptr == 0){
					//$htmlAreas.= '<th style="font-size: 10px; text-align: center"><a style="color:#8a8a5c" href="rePoMo?item=' . $row->itemPlan . '&form=1&estaciondesc=' . utf8_decode($estacionDesc) . '&estacion=' . $row->idEstacion . '"    target="_blank">' . utf8_decode($row->areaDesc) . '</a><th>';
				// }
			}else if($row->tipoArea == 'MAT'){
				$htmlAreas.= '<th style="font-size: 10px; text-align: center"><a style="color:#8a8a5c" href="regPinPO?item=' . $row->itemPlan . '&form=1&estaciondesc=' . utf8_decode($estacionDesc) . '&estacion=' . $row->idEstacion . '"    target="_blank">' . utf8_decode($row->areaDesc) . '</a><th>';
			}
			$htmlAreas.= '</tr>
					</thead>
					<tbody>';
			if($row->tipoArea == 'MO'){
				$htmlAreas.= $this->makeHTLMPTRV2($this->M_detalle_planta_interna->getAllPTRbyArea($item, $estacionDesc, $row->areaDesc),2);
			}else if($row->tipoArea == 'MAT'){
				$htmlAreas.= $this->makeHTLMPTRV2($this->M_detalle_planta_interna->getAllPTRbyAreaMAT($item, $estacionDesc, $row->areaDesc),1);
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
        $datos= $this->M_detalle_planta_interna->getInfoPtrInterna($ptrAjax);
        $btonEditarPtr = '';
        // if($datos['rangoPtr'] == 1  && $datos['tipoArea'] ==  'MO')
        // if(($datos['idEstadoPlan'] == 3) && $datos['tipoArea'] ==  'MO'){
            // $btonEditarPtr = '<button onclick="modificarPTRPI(this)" data-ptr="'.$datos['ptr'].'" data-item="'.$datos['itemplan'].'" style="color: #ffffff;background-color: #24ac1b;" type="button" class="btn btn-link" data-dismiss="modal">Editar PTR</button>';
         // }        
        
        
        
                $html ='
                    <div class="col-md-12">
                        <h6 class="text-center" style="background-color: #1B84AC; color: white; padding: 2px">DETALLE</h6>
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
                                   <td>. '.$datos['costo_mo'].'</td>
                                   <th style="font-size:12px;"><b>MONTO MATERIAL</b></th>
                                   <td>. '.$datos['costo_mat'].'</td>
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
						
						
				$htmlPartidas = null;
                $total = 0;
                $detallePODisenio = $this->m_utils->getDetallePoTransp($ptrAjax);
                $htmlPartidas .= '<table id="tabla_diseno_auto" class="table table-bordered">
                                <thead class="thead-default">
                                    <tr>
                                        <th>C&Oacute;DIGO PARTIDA</th>
                                        <th>PARTIDA</th>
                                        <th>BAREMO</th>
										<th>COSTO</th>
                                        <th>CANTIDAD INICIAL</th>
										<th>CANTIDAD FINAL</th>
                                        <th>TOTAL</th>
                                    </tr>
                                </thead>
                                <tbody>';

                foreach ($detallePODisenio as $row) {
                    $total = $row['total']+$total;
                    $htmlPartidas .= ' <tr>
                                                    <th>' . $row['codigo'] . '</th>
                                                    <td>' . utf8_decode($row['descPartida']) . '</td>
                                                    <td>' . $row['baremo'] . '</td>
                                                    <td>' . $row['precio'] . '</td>
                                                    <td>' . $row['cantidad_inicial'] . '</td>
													<td>' . $row['cantidad_final'] . '</td>
                                                    <td>' . $row['total'] . '</td>
                                                </tr>';
                }
                $htmlPartidas .= '</tbody>
                                </table>
                                <a>TOTAL: ' . number_format($total, 2, '.', ',') . '</a>';
            $data['prueba']= $html;
			$data['htmlPartidas']= $htmlPartidas;
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
             $htmlE .= $this->makeHTLMTAreasEdit($this->M_detalle_planta_interna->getAllAreasByEstacion($item, $row->estacionDesc), $row->estacionDesc, $item);

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
        $html .=  $this->makeHTLMPTREdit($this->M_detalle_planta_interna->getAllPTRbyArea($item, $estacionDesc, $row->areaDesc), $item);

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
                        $data = $this->M_detalle_planta_interna->deletePTR($itemEdit, $defaultValueEdit, $idsubproyestacionEdit);
                    }else{
                        $data = $this->M_detalle_planta_interna->updatePTR($newValueEdit, $itemEdit, $defaultValueEdit, $idsubproyestacionEdit);
                    }
                    $i++;
                }

            }
            $data['listaEstaciones'] = $this->makeHTLMTEstaciones($this->M_detalle_planta_interna->getAllEstaciones($itemTitle), $itemTitle);
            $data['listaEstacionesEdit'] = $this->makeHTLMTEstacionesEdit($this->M_detalle_planta_interna->getAllEstaciones($itemTitle), $itemTitle);



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
                $flag = $this->validacion2($this->M_detalle_planta_interna->findPTR($valueInsert));

                if($flag == 'libre'){
                    $this->db->trans_begin();
                    $data = $this->M_detalle_planta_interna->insertPTR($itemInsert, $valueInsert, $idsubproyectoestacionInsert);
                    // Insertar en DET SI NO ESTA WU
                    $exists = $this->findEnWU($this->M_detalle_planta_interna->findEnWU($valueInsert));


                    if($exists == 0){
                        // insertar en WUDET
                        if ($this->db->trans_status() === FALSE) {
                            $this->db->trans_rollback();
                            throw new Exception("ERROR4");

                        } else {

                            $this->M_detalle_planta_interna->insertEnWU($valueInsert);
                                if($this->db->trans_status() === FALSE){
                                    $this->db->trans_rollback();
                                    throw new Exception("ERROR5");
                                }else{
                                    $this->M_detalle_planta_interna->insertEnWUDet($valueInsert);
                                    if ($this->db->trans_status() === FALSE) {
                                        $this->db->trans_rollback();
                                        throw new Exception("ERROR6");
                                     }else{
                                        $this->M_detalle_planta_interna->getGrafoOnePTR($valueInsert);
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
                            $this->M_detalle_planta_interna->deleteEnWebUnitDet($valueInsert);
                             if($this->db->trans_status() === FALSE) {
                                 $this->db->trans_rollback();
                                 throw new Exception("ERROR2");
                             }else{
                                 $this->M_detalle_planta_interna->selectDet($valueInsert);
                                 if ($this->db->trans_status() === FALSE) {
                                    $this->db->trans_rollback();
                                    throw new Exception("ERROR3");
                                 }else{
                                     $this->M_detalle_planta_interna->getGrafoOnePTR($valueInsert);
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
                    $this->M_detalle_planta_interna->insertPTRenLog($itemInsert, $valueInsert, $idUser);
                     // Ejecutando getGrafoOnePTR()

                }else{


                    // TERCERA VALIDACION
                    if($areaInsert == $flag){
                        $this->db->trans_begin();
                        $data = $this->M_detalle_planta_interna->insertPTR($itemInsert, $valueInsert, $idsubproyectoestacionInsert);
                        // Insertar en DET SI NO ESTA EN WU
                        $exists = $this->findEnWU($this->M_detalle_planta_interna->findEnWU($valueInsert));
                        if($exists == 0){
                            // insertar en WUDET
                            if ($this->db->trans_status() === FALSE) {
                                $this->db->trans_rollback();
                                throw new Exception("ERROR4");

                            } else {

                                $this->M_detalle_planta_interna->insertEnWU($valueInsert);
                                if($this->db->trans_status() === FALSE){
                                    $this->db->trans_rollback();
                                    throw new Exception("ERROR5");
                                }else{
                                    $this->M_detalle_planta_interna->insertEnWUDet($valueInsert);
                                    if ($this->db->trans_status() === FALSE) {
                                        $this->db->trans_rollback();
                                        throw new Exception("ERROR6");
                                     }else{
                                         $this->M_detalle_planta_interna->getGrafoOnePTR($valueInsert);
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
                                $this->M_detalle_planta_interna->deleteEnWebUnitDet($valueInsert);
                                 if ($this->db->trans_status() === FALSE) {
                                     $this->db->trans_rollback();
                                     throw new Exception("ERROR2");
                                 }else{
                                     $this->M_detalle_planta_interna->selectDet($valueInsert);
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
                    	$this->M_detalle_planta_interna->insertPTRenLog($itemInsert, $valueInsert, $idUser);
                    	// Ejecutando getGrafoOnePTR()
                        //$this->M_detalle_planta_interna->getGrafoOnePTR("'".$valueInsert."'");
                        
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