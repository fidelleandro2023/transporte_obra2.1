<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * @author CARLOS ZAVALA C.
 * 18/01/2018
 *
 */
class C_registrar_cotizacion extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_cotizacion/m_registrar_cotizacion');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->library('zip');
        $this->load->helper('url');
    }
    
	public function index()
	{  	   
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
        	  
	             	   
               $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_registrar_cotizacion->getItemplanPreRegistro($this->session->userdata('eeccSession')));	           
               $data['listaTiCen'] = $this->m_utils->getAllCentral();
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');	
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');	               
        	   $permisos =  $this->session->userdata('permisosArbol');
        	   #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_COTIZACION, ID_PERMISO_HIJO_REGISTRO_COTIZACION);
        	   $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_NUEVO_MODELO_COTIZACION, ID_PERMISO_HIJO_REGISTRO_COTIZACION, ID_MODULO_PAQUETIZADO);
        	   $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	       $this->load->view('vf_cotizacion/v_registrar_cotizacion',$data);
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
        $html = '<table id="data-table" class="table table-bordered" style="font-size: 10px;">
                    <thead class="thead-default">
                        <tr>     
                            <th style="width:9%">Enviar</th>                         
                            <th>Editar</th>                            
                            <th>Item Plan</th>  
                            <th>Monto MO</th> 
                            <th>Monto MAT</th> 
                            <th>TOTAL</th>      
                            <th>Proyecto</th>  
                            <th style="text-align:center">Sub Proyecto</th>
                            <th>EECC</th>
                            <th>Jefatura</th>
                            <th>Region</th>        
                            <th style="text-align:center">Estado Plan</th>
                            <th style="text-align:center">Fecha Creacion.</th>
                            <th>Situacion.</th>
                            <th style="text-align:center;width: 2%;">Enviar EECC</th> 
                        </tr>
                    </thead>
                   
                    <tbody>';
            if($listaPTR!=null){																                                                   
                foreach($listaPTR->result() as $row){        
                    if($row->estado == 1){
                        $estado = 'SIN EVIDENCIA';
                    }else if($row->estado == 2){
                        $estado = 'CON EVIDENCIA';
                    }else if($row->estado == 3){
                        $estado = 'PDT DE APROBACION';
                    }else if($row->estado == 4){
                        $estado = 'APROBADO';
                    }else if($row->estado == 5){
                        $estado = 'DEVUELTO';
                    }else if($row->estado == 6){
                        $estado = 'RECHAZADO';
                    }
               if($this->session->userdata('eeccSession') != 0 && $this->session->userdata('eeccSession') !=6){//EECC
                        $responsable = 2;
                    }else{//TDP
                        $responsable = 1;
                    }
                $html .=' <tr>
                            <td>'.(($responsable == $row->responsable) ? (($row->estado == 2) ? '<a data-itemplan ="'.$row->itemplan.'" data-idCen="'.$row->idCentral.'"  onclick="enviarCotizacion(this)"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/circle-check-128.png"></a>' : '').
                                '<!--<a href="'.$row->path_pdf_to_cotiza.'" target="_blank"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/iconview.svg"></a>-->'.'
                                '.(($row->estado != 1) ? '<a href="'.$row->ruta_pdf.'" target="_blank"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/pdf.png"></a>' : '').'': 'EECC').'
                            </td>                                
                            <td>'.
                            (($responsable == $row->responsable) ?  (($row->estado == 1 || $row->estado == 2 || $row->estado == 5) ? '<a data-itemplan="'.$row->itemplan.'" onclick="openUploadFile(this)"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/editar.ico"></a>' : '') : 'EECC')
                            .'</td>
							<td>'.$row->itemplan.'</td>	
							<td>'.$row->monto_mo.'</td>
						    <td>'.$row->monto_mat.'</td>
					        <td>'.$row->monto_total.'</td>
							<td>'.$row->proyectoDesc.'</td>	
                            <td>'.$row->subProyectoDesc.'</td>	
                            <td>'.$row->empresaColabDesc.'</td>	
                            <td>'.$row->jefatura.'</td>		
                            <td>'.$row->region.'</td>								
                            <td>'.$row->estadoPlanDesc.'</td>   
                            <td>'.$row->fecha_creacion.'</td>
                            <td>'.$estado.'</td>
                            <td style="text-align:center">'.(($row->responsable ==  '1' && ($row->estado == 1 || $row->estado == 2 )) ?  '<a data-itemplan="'.$row->itemplan.'" data-idCen="'.$row->idCentral.'" onclick="sendToEECC(this)"><i class="zmdi zmdi-mail-reply"></i></a>' : '').'</td>		
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
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_registrar_cotizacion->getItemplanList($itemPlan,$SubProy));
            $data['error']    = EXIT_SUCCESS;            
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }   

    
  
    /**PARA LINUX MANDAR DEFRENTE SIN UTF8 POR LOS CARACTERES**/
    /**PARA WINDOWS UTF8_DECODE***/
    /****SOLO EN EL MOVIMIENTO DE ARCHIVO, PARA BD TODO DEFRENTE CUALQUIER SO*****/
    function insertEvidenciaByItemplan() {
        //$itemplan =  $this->session->userdata('itemplanEvi');
        $itemplan   = $this->input->post('itemplan');
        $monto_mo   = $this->input->post('monto_mo');
        $monto_mat  = $this->input->post('monto_mat');
        $file = $_FILES ["file"] ["name"];
        $filetype = $_FILES ["file"] ["type"];
        $filesize = $_FILES ["file"] ["size"];
     
        $subCarpeta = 'uploads/cotizacion/'.$itemplan.'/';
        
        
        $file2 = utf8_decode($file);//le generamos un nombreAleatorio
       
        if (! is_dir ( $subCarpeta))
            mkdir ( $subCarpeta, 0777 );
            if ($file && move_uploaded_file ( $_FILES ["file"] ["tmp_name"], $subCarpeta. $file )) {
                   $this->m_registrar_cotizacion->saveFileCotizacion($itemplan, $subCarpeta.$file, $monto_mo, $monto_mat);                  
                }
            
        $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_registrar_cotizacion->getItemplanPreRegistro($this->session->userdata('eeccSession')));
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
            $data = $this->m_registrar_cotizacion->saveItemplanEvidencia($itemplan,$fileName);
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
    
    function enviarCotizacion() {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $itemplan = $this->input->post('itemplan');            
            $data = $this->m_registrar_cotizacion->enviarCotizacion($itemplan);
            if($data['error']==EXIT_ERROR){
                throw new Exception('Error Interno');
            }
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_registrar_cotizacion->getItemplanPreRegistro($this->session->userdata('eeccSession')));	           
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
        
    }
    
    function actualizarCentral() {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $itemplan   = $this->input->post('itemplan');
            $idCentral  = $this->input->post('idCentral');
            $idZonal    = $this->input->post('idZonal');
            $idEECC     = $this->input->post('idEECC');
            $data = $this->m_registrar_cotizacion->updatePlanObraCentral($itemplan, $idCentral, $idZonal, $idEECC);
            if($data['error']==EXIT_ERROR){
                throw new Exception('Error Interno');
            }
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_registrar_cotizacion->getItemplanPreRegistro($this->session->userdata('eeccSession')));
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    
    }
    
    function sendToEECC() {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $itemplan   = $this->input->post('itemplan');
            $data = $this->m_registrar_cotizacion->sendCotiToEECC($itemplan);
            if($data['error']==EXIT_ERROR){
                throw new Exception('Error Interno');
            }
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_registrar_cotizacion->getItemplanPreRegistro($this->session->userdata('eeccSession')));
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    
    }
    
    function updateMontoCoti() {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $itemplan   = $this->input->post('itemplan');
            $monto      = $this->input->post('monto');
            $monto_mat      = $this->input->post('monto_mat');
            $data = $this->m_registrar_cotizacion->updateMonto($itemplan, $monto, $monto_mat);
            if($data['error']==EXIT_ERROR){
                throw new Exception('Error Interno');
            }
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_registrar_cotizacion->getItemplanPreRegistro($this->session->userdata('eeccSession')));
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));    
    }
    
    
}