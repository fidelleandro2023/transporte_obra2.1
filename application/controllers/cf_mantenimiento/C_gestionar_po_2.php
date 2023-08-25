<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * @author CARLOS ZAVALA C.
 * 18/01/2018
 *
 */
class C_gestionar_po_2 extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_mantenimiento/m_gestionar_po_2');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->library('zip');
        $this->load->helper('url');
    }
    
	public function index()
	{  	   
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
        	  
	           $listaItemplan = $this->m_gestionar_po_2->getItemplanList('','');        	   
               $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo(null);	
               $data['itemplanList'] = $listaItemplan;
               $data['listaEstaciones'] = $this->m_utils->getAllEstacionNoDiseno();
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');	
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');	               
        	   $permisos =  $this->session->userdata('permisosArbol');
        	   $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_PLAN_DE_OBRA, ID_PERMISO_HIJO_GESTIONAR_PO_II);
        	   $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	       $this->load->view('vf_mantenimiento/v_gestionar_po_2',$data);
        	   }else{
        	       redirect('login','refresh');
	           }
	   }else{
	       redirect('login','refresh');
	   }
    }
    
    public function makeHTMLTablaEvidencias($listaEvidencias){
        $html = '<table id="data-table2" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th></th>
                            <th>Itemplan</th>
                            <th>Nombre archivo</th>
                            <th>Fecha Registro</th>
                            <th>Usuario</th>
                        </tr>
                    </thead>
          
                    <tbody>';
        if($listaEvidencias!=null){
            foreach($listaEvidencias->result() as $row){
    
                $html .=' <tr>
                         <td></td>
							<td>'.$row->itemplan.'</td>
							<td><a href="'.base_url().'\\uploads\\evidencias\\'.$row->itemplan.'\\'.$row->file_name.'">'.$row->file_name.'</a></td>
                            <td>'.$row->fecha_registro.'</td>
                            <td>'.$row->usuario.'</td>
						</tr>';
            }
        }
        $html .='</tbody>
                </table>';
    
        return utf8_decode($html);
    }
    
    public function makeHTLMTablaBandejaAprobMo($listaPTR){     
        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th style="width:10%"></th>                            
                            <th>Item Plan</th>        
                            <th>Proyecto</th>  
                            <th>Sub Proyecto</th>
                            <th>EECC</th>
                            <th>Jefatura</th>
                            <th>Region</th>        
                            <th>Estado Plan</th>
                            <th>% Prom.</th>
                        </tr>
                    </thead>
                   
                    <tbody>';
            if($listaPTR!=null){																                                                   
                foreach($listaPTR->result() as $row){        
                    
                $html .=' <tr>
                         <td>'.(($this->canEdit($this->session->userdata('idPerfilSession'))) ? '<a data-itemplan="'.$row->itemPlan.'" onclick="editEstado(this)"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/editar.ico"></a>
                                &nbsp;&nbsp': '').'
                                <!--
                                <a data-itemplan="'.$row->itemPlan.'" data-jefatura="'.$row->jefatura.'" onclick="editPorcentaje(this)"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/porcentaje.png"></a>
                                &nbsp;&nbsp<a data-itemplan="'.$row->itemPlan.'" data-jefatura="'.$row->jefatura.'" onclick="openUploadFile(this)"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/iconpicplus.svg"></a>
                                -->
                                </td>
							<td>'.$row->itemPlan.'</td>	
							<td>'.$row->nombreProyecto.'</td>	
                            <td>'.$row->subProyectoDesc.'</td>	
                            <td>'.$row->empresaColabdesc.'</td>	
                            <td>'.$row->jefatura.'</td>		
                            <td>'.$row->region.'</td>								
                            <td>'.$row->estadoPlanDesc.'</td>   
                            <td>'.(($row->porcentaje=='') ? '0%': $row->porcentaje.'%').'</td>		
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
            $itemPlan = $this->input->post('itemplanFil');        
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_gestionar_po_2->getItemplanList($itemPlan,$SubProy));
            $data['error']    = EXIT_SUCCESS;            
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }   

    function canEdit($perfiles){
        $edit = '0';
        $listaPerfil = explode(",", $perfiles);
        foreach($listaPerfil as $row){           
            if($row == '3' || $row == '4' || $row == '10' || $row == '9'|| $row == '5'|| $row == '17'){//TELEFONICA O ADMINISTRADOR
                $edit = '1';
            }
        }
        return $edit;
    }
    
    function getInfoItemPlan(){        
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $itemplan = $this->input->post('itemplan');
            $infoItem = $this->m_gestionar_po_2->getInfoItemplanToEdit($itemplan);
            $data['itemplan'] = $infoItem['itemplan'];
            $data['nombreProyecto'] = $infoItem['nombreProyecto'];
            $data['fechaInicio'] = $infoItem['fechaInicio'];
            $data['fechaPrevEjec'] = $infoItem['fechaPrevEjec'];
            $data['idEstadoPlan'] = $infoItem['idEstadoPlan'];
            $data['subProyectoDesc'] = $infoItem['subProyectoDesc'];
            $data['empresaColabDesc'] = $infoItem['empresaColabDesc'];
            if($infoItem['hasAdelanto']==''){$infoItem['hasAdelanto']=0;}
            $data['hasAdelanto'] = $infoItem['hasAdelanto'];
            $data['fechaEjec'] = $infoItem['fechaEjecucion'];
            $data['fechaCan'] = $infoItem['fechaCancelacion'];
            $estado=$this->m_utils->getEstadoPlanByItemplanNombre($itemplan);
            /*
            $extra="";
            if($infoItem['idEstadoPlan']==3){$extra='<option value="4">Terminado</option>';}
            $data['estadosList'] = 
            '<option value="'.$estado["idEstadoPlan"].'">'.utf8_decode($estado["estadoPlanDesc"]).'</option>'.$extra.$this->getHTMLChoiceEstados($this->m_utils->getAllEstadosPlan($itemplan));
            
            */
            $data['estadosList'] = '<option value="'.$estado["idEstadoPlan"].'">'.utf8_decode($estado["estadoPlanDesc"]).'</option>                                    
                                    '.(($estado["idEstadoPlan"] != ID_ESTADO_TRUNCO) ? '<option value="'.ID_ESTADO_TRUNCO.'">Trunco</option>' : '').'
                                    '.(($estado["idEstadoPlan"] != ID_ESTADO_CANCELADO) ? '<option value="'.ID_ESTADO_CANCELADO.'">Cancelado</option>' : '');
            
            if($data['idEstadoPlan'] == ID_ESTADO_CANCELADO ||$data['idEstadoPlan'] == ID_ESTADO_TRUNCO){
                 $motivo=$this->m_gestionar_po_2->ListarMotivo($itemplan);
                     if($motivo){
                     $data["motivo"]=$motivo["comentario"];   
                     $data["idmotivo"]=$motivo["motivo"];   
                }
            }
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getPorcentajeEstacion(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $itemplan = $this->input->post('itemplan');
            $listaEstacion = $this->m_gestionar_po_2->getEstacionPorcentajeByItemPlan($itemplan)->result();
            $data['listaEstaPor'] = json_encode($listaEstacion);      
            $data['htmlEstaciones'] = $this->makeHtmlContChoice($listaEstacion);
            $data['encode'] = base64_encode($this->session->userdata('idUsuarioSinfix').'|'.$itemplan);
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function makeHtmlContChoice($listaEstaciones){
        $html='';
        $edit = $this->canEdit($this->session->userdata('idPerfilSession'));
        foreach($listaEstaciones as $row){
        $html .= ' 	<div class="col-sm-6 col-md-6">
                     	<div class="form-group">
                                    <label>'.$row->estacionDesc.'</label>
                                    <select data-id_esta="'.$row->idEstacion.'" data-estacion="'.$row->estacionDesc.'" data-value="'.$row->porcentaje.'" onchange="addField(this)" id="selectEstacion'.$row->idEstacion.'" name="selectEstacion'.$row->idEstacion.'" class="select2  form-control" >
                                        '.(($row->porcentaje <= 0 || $edit=='1') ? '<option value="0">0%</option>' : '').'
                                        '.(($row->porcentaje <= 25 || $edit=='1') ? '<option value="25">25%</option>' : '').'
                                        '.(($row->porcentaje <= 50 || $edit=='1') ? '<option value="50">50%</option>' : '').'
                                        '.(($row->porcentaje <= 75 || $edit=='1') ? '<option value="75">75%</option>' : '').'
                                        '.(($row->porcentaje <= 100 || $edit=='1') ? '<option value="100">100%</option>' : '').'
                                        '.(($row->porcentaje <= 0 || $edit=='1') ? '<option value="NR">NO REQUIERE</option>' : '').'
                                    </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6">
                     	<div class="form-group">
                                    <label>'.$row->estacionDesc.' - CUADRILLA</label>
                                    <select  id="selectCuadrilla'.$row->idEstacion.'" name="selectCuadrilla'.$row->idEstacion.'" class="select2  form-control" >
                                        <option value="">&nbsp</option>
                                        <option value="CP">PROPIA</option>';
                                        $listaCuadrilla = $this->m_gestionar_po_2->getCuadriallasByEeccZonalEstacion($row->idEmpresaColab, $row->idZonal, $row->idEstacion);
                                        foreach($listaCuadrilla->result() as $row2){
                     $html .= '  <option value ="'.$row2->idCuadrilla.'" >'.$row2->descripcion.'</option>';
                                        }
                   $html .= ' </select>
                        </div>
                    </div>';
        }
        
        return $html;
    }
    
     function getHTMLChoiceEstados($listaEstados){
            $html = '';

            foreach($listaEstados->result() as $row){
                $html .= '<option value="'.$row->idEstadoPlan.'">'.utf8_decode($row->estadoPlanDesc).'</option>';
            }
           return $html;
    }
    
    function changueEstadoPlan(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $itemplan = $this->input->post('itemplan');
            $idEstadoPlan = $this->input->post('selectEstaItem');
            $hasAdelanto = $this->input->post('selectAdelanto');
            $fecEjecucion = $this->input->post('inputFecEjec');
            $fecTermino = $this->input->post('inputFecTerm');
            $motivo = $this->input->post('motivoet');
            $motivo_v=$this->input->post('rmotivo');
            if(!$idEstadoPlan){
            $infoItem = $this->m_gestionar_po_2->getInfoItemplanToEdit($itemplan);
            $idEstadoPlan = $infoItem['idEstadoPlan'];    
            }
            $data =  $this->m_gestionar_po_2->changeEstadoPlan($itemplan,$idEstadoPlan,$hasAdelanto,$fecEjecucion,$fecTermino);
            if($this->m_gestionar_po_2->ExisteStop($itemplan)){
                $this->m_gestionar_po_2->EditarStop($itemplan,$motivo_v);
            }else{
                $this->m_gestionar_po_2->CrearPlanObraStop("",$itemplan,$motivo_v,$motivo,$this->session->userdata("idPersonaSession"),$idEstadoPlan);        
            }
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_gestionar_po_2->getItemplanList($itemplan,''));
          
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function savePorcentajeEstacion(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $itemplan = $this->input->post('itemplan');
            $listaEstacion = $this->m_gestionar_po_2->getEstacionPorcentajeByItemPlan($itemplan);
            $arrayInsert =  array();
            $arrayUpdate = array();
            $arrayInsertCuadrilla = array();
            foreach($listaEstacion->result() as $row){
                $datatrans = array();
                $dataCuadrilla = array();
                if($row->idItemplanEstacion == NULL){                                        
                    $datatrans['porcentaje'] = $this->input->post('selectEstacion'.$row->idEstacion);
                    $datatrans['idEstacion'] = $row->idEstacion;
                    $datatrans['itemplan'] = $row->itemplan;
                    array_push($arrayInsert, $datatrans);
                }else{                    
                    $datatrans['idItemplanEstacion'] = $row->idItemplanEstacion;
                    $datatrans['porcentaje'] = $this->input->post('selectEstacion'.$row->idEstacion);
                    array_push($arrayUpdate, $datatrans);
                }
                
                $dataCuadrilla['idCuadrilla'] =  $this->input->post('selectCuadrilla'.$row->idEstacion);
                $dataCuadrilla['porcentaje'] = $this->input->post('selectEstacion'.$row->idEstacion);
                $dataCuadrilla['fecha_registro'] = date('Y-m-d H:i:s');
                $dataCuadrilla['usuario_registro'] = $this->session->userdata('idPersonaSession');	
                $dataCuadrilla['itemplan'] = $itemplan;
                $dataCuadrilla['idEstacion'] = $row->idEstacion;
                array_push($arrayInsertCuadrilla, $dataCuadrilla);
                
            }
            $data =  $this->m_gestionar_po_2->savePorcentaje($arrayInsert, $arrayUpdate, $arrayInsertCuadrilla);            
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_gestionar_po_2->getItemplanList($itemplan,''));
            
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function insertEvidenciaByItemplan() {
        $itemplan =  $this->session->userdata('itemplanEvi');
        $file = $_FILES ["file"] ["name"];
        $filetype = $_FILES ["file"] ["type"];
        $filesize = $_FILES ["file"] ["size"];
        $directorio = 'uploads/evidencias/'.$itemplan;
        if (! is_dir ( 'uploads/evidencias/'.$itemplan)){
            mkdir ( 'uploads/evidencias/'.$itemplan, 0777 );
        }
        
        $subCarpeta = 'uploads/evidencias/'.$itemplan.'/'.$itemplan.'_tmp/';
        
        $this->session->set_userdata('subCarpetaEvi',$subCarpeta);
        
        $file2 = utf8_decode($file);//le generamos un nombreAleatorio
       
        if (! is_dir ( $subCarpeta))
            mkdir ( $subCarpeta, 0777 );
            if (utf8_decode($file) && move_uploaded_file ( $_FILES ["file"] ["tmp_name"], $subCarpeta. $file2 )) {
                    
                    
                   // $this->zip->add_data("uploads/evidencias/imagenes/" . $file2, "uploads/evidencias/imagenes/" . $file2);
                   // $this->zip->read_file("uploads/evidencias/imagenes/" . $file2);
                    
                 /*   $dataimg = array (
                        "file_name" => utf8_decode($file),
                        "file_type" => 'img',
                        "ruta_mostrar" => 'uploads/evidencias/imagenes/' . $file2,
                    );*/
                }
               // $result = $this->m_consultarEscuela->insertvidencia( $dataimg );
                    
        
      //  $this->zip->archive('uploads/evidencias/imagenes/'.rand(1, 100).date("dmhis").'my_info.zip');
       $data['error'] = EXIT_SUCCESS;
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function zipTempFiles(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $itemplan =  $this->session->userdata('itemplanEvi');
            $subCarpeta = $this->session->userdata('subCarpetaEvi');
            $this->zip->read_dir($subCarpeta,false);
            $fileName = $itemplan.'_'.rand(1, 100).date("dmhis").'.zip';
            $this->zip->archive('uploads/evidencias/'.$itemplan.'/'.$fileName);   
            $data = $this->m_gestionar_po_2->saveItemplanEvidencia($itemplan,$fileName);
            $this->rrmdir($subCarpeta);
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function rrmdir($src) {
        $dir = opendir($src);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                $full = $src . '/' . $file;
                if ( is_dir($full) ) {
                    $this->rrmdir($full);
                }
                else {
                    unlink($full);
                }
            }
        }
        closedir($dir);
        rmdir($src);
    }
    
    function saveItemplan() {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $itemplan = $this->input->post('itemplan');
            $this->session->set_userdata('itemplanEvi',$itemplan);
            $listaFiles = $this->m_gestionar_po_2->getEvidenciasByItemPlan($itemplan);
            $data['dablaEvidencias']  = $this->makeHTMLTablaEvidencias($listaFiles);        
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
        
    }
    
}