<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * @author CARLOS ZAVALA C.
 * 18/01/2018
 *
 */
class C_subproy_pep_grafo extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_mantenimiento/m_subproy_pep_grafo');
        $this->load->model('mf_tranferencias/m_tranferencia_wu');        
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }
    
	public function index(){
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){    	          
                /*
	           $data['listaSubProy'] = $this->m_utils->getAllSubProyecto();
	           $data['listaAreas'] = $this->m_utils->getAllAreas();
	           $data['listaPep1'] = $this->m_utils->getAllPep1();
	          
	           $data['tbPep1SubPro'] = $this->makeHTLMTablaPepSubPro($this->m_subproy_pep_grafo->getPep1SubProy(''));
	           $data['tbPep1Presu'] = $this->makeHTLMTablaPepPresupuesto($this->m_subproy_pep_grafo->getPepPresupuesto());
	           $data['tbPep1Pep2'] = $this->makeHTLMTablaPep1Pep2($this->m_subproy_pep_grafo->getPep1Pep2());	
	           */
	           $data['tbPepGrafo'] = $this->makeHTLMTablaPepGrafo($this->m_subproy_pep_grafo->getPep2Grafos());
	           $data['tbSisegoPepGrafo'] = $this->makeHTLMTablaSisegoPepGrafo($this->m_subproy_pep_grafo->getSisegoPep2Grafo());
	           $data['tbItemPepGrafo'] = $this->makeHTLMTablaItemPepGrafo($this->m_subproy_pep_grafo->getItemplanPep2Grafo());
	           
	           $data['tbPep1Pep2'] = $this->makeHTLMTablaPep1Pep2(null);	           
	           $data['tbPep1SubPro'] = $this->makeHTLMTablaPepSubPro(null);
	           $data['tbPep1Presu'] = $this->makeHTLMTablaPepPresupuesto(null);
	           /*
	           $data['tbPepGrafo'] = $this->makeHTLMTablaPepGrafo(null);
	           $data['tbSisegoPepGrafo'] = $this->makeHTLMTablaSisegoPepGrafo(null);
	           $data['tbItemPepGrafo'] = $this->makeHTLMTablaItemPepGrafo(null);
	           */	
			   $data['listaPep2'] = $this->m_utils->getAllPep2();
			   		 
	           $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
               $data['idUsuarioSesion'] =  $this->session->userdata('idPersonaSession');
               $permisos =  $this->session->userdata('permisosArbol');
        	   #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_MANTENIMIENTO, ID_PERMISO_HIJO_SUBPROY_PEP_GRAFO);
               $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_ADMINISTRATIVO_PRESUPUESTO, ID_PERMISO_HIJO_SUBPROY_PEP_GRAFO, ID_MODULO_ADMINISTRATIVO);
                
        	   $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	         $this->load->view('vf_mantenimiento/v_subproy_pep_grafo',$data);
        	   }else{
        	       redirect('login','refresh');
        	   }
    	 }else{
        	 redirect('login','refresh');
	    }
             
    }
    
    public function makeHTLMTablaPepSubPro($lista){
     
        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th style="width:10px;"></th>
                            <th>SUB PROYECTO</th>
                            <th>ESTACION</th>                            
                            <th>AREA</th>
                            <th>PEP 1</th> 
                            <th>FASE</th>                                                 
                        </tr>
                    </thead>                    
                    <tbody>';
				if($lista!=null){
                    foreach($lista->result() as $row){         
                        #|| $this->session->userdata('idPersonaSession') == ID_USUARIO_CYNTHIA_DIAZ 
    					#SOLO OWEN PUEDE ELIMINAR CONFIGURACION.
                    $html .=' <tr>
                                <th>'.(( $this->session->userdata('idPersonaSession')    == ID_USUARIO_OWEN_SARAVIA) ? '<a data-id_ps ="'.$row->id_pep1_subpro.'" onclick="deletesubPep(this)" ><img alt="Eliminar" height="20px" width="20px" src="public/img/iconos/delete.png"></a>' : '').'</th>
    							<td>'.$row->subproyecto.'</td>
    							<td>'.$row->estacion.'</td>							
    							<td>'.$row->area.'</td>
    							<td>'.$row->pep1.'</td>
    						    <td>'.$row->faseDesc.'</td>                       
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
            $data['tablaSubProyPep'] = $this->makeHTLMTablaPepSubPro($this->m_subproy_pep_grafo->getPep1SubProy($SubProy));
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function makeHTLMTablaPepPresupuesto($lista){         
        $html = '<table id="data-table2" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th></th>
                            <th>PEP1</th>
                            <th>MONTO INICIAL</th>
							<th>MONTO ASIGNADO A GRAFOS</th>
                            <th>MONTO ASIGNADO A SOL. OC</th>
                            <th>MONTO DISPONIBLE</th>
                        </tr>
                    </thead>
                    <tbody>';
        if($lista!=null){
            foreach($lista->result() as $row){        
                $html .=' <tr>
                                <th></th>
    							<td>'.$row->pep1.'</td>
    							<td>'.$row->monto_inicial.'</td>
    						    <td>'.$row->monto_material.'</td>
    							<td>'.$row->monto_solicitud_oc.'</td>
    							<td>'.$row->monto_temporal.'</td>
    						</tr>';    						
            }
        }
        $html .='</tbody>
                </table>';
    
        return utf8_decode($html);
    }
    
    public function makeHTLMTablaPepGrafo($lista){
        $html = '<table id="data-table3" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th></th>
                            <th>PEP1</th>
                            <th>PEP2</th>
                            <th>GRAFOS LIBRES</th>
                            <th>GRAFOS ASIGNADOS</th>
                            <th>TOTAL GRAFOS UTILIZADOS</th>
                            <th>TOTAL GRAFOS</th>
                        </tr>
                    </thead>
                    <tbody>';
        if($lista!=null){
            foreach($lista->result() as $row){        
                $html .=' <tr>
                                <th></th>
    							<td>'.$row->pep1.'</td>
    							<td>'.$row->pep2.'</td>
    						    <td>'.$row->libres.'</td>
    							<td>'.$row->temporales.'</td>
    							<td>'.$row->usados.'</td>
    							<td>'.$row->total.'</td>
    						</tr>';
            }
        }
        $html .='</tbody>
                </table>';
    
        return utf8_decode($html);
    }
    
    public function makeHTLMTablaPep1Pep2($lista){
        $html = '<table id="data-table4" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th style="width:10px;"></th>
                            <th>PEP1</th>
                            <th>PEP2</th>
                        </tr>
                    </thead>
                    <tbody>';
        if($lista!=null){
            foreach($lista->result() as $row){        
                $html .=' <tr>
                                <th>'.(( $this->session->userdata('idPersonaSession')    == ID_USUARIO_OWEN_SARAVIA) ? '<a data-id_pp ="'.$row->id_relacion.'" onclick="deletePep1Pep2(this)"><img alt="Eliminar" height="20px" width="20px" src="public/img/iconos/delete.png"></a>' : '').'</th>
    							<td>'.$row->pep1.'</td>
    							<td>'.$row->pep2.'</td>
    						</tr>';
            }
        }
        $html .='</tbody>
                </table>';
    
        return utf8_decode($html);
    }
    
    public function addSubProPep(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $subProyecto = $this->input->post('selectSubProy2');
            $area = $this->input->post('selectArea');
            $pep = $this->input->post('selectPep');
            $fase = $this->input->post('selectFase');
            
            $estacion = $this->m_subproy_pep_grafo->getEstacionByArea($area);
            if($estacion==null){
                throw new Exception('Error al encontrar la estacion para el area seleccionada.');
            }
            $data = $this->m_subproy_pep_grafo->insertPepSubProyecto($subProyecto, $estacion, $area, $pep, $fase);
            if($data['error']==EXIT_ERROR){
                throw new Exception('Error al Insertar El Subproyecto Pep');
            }
            $SubProy = $this->input->post('subProy');
            $data['tablaSubProyPep'] = $this->makeHTLMTablaPepSubPro($this->m_subproy_pep_grafo->getPep1SubProy($SubProy));
            
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function existeSubPepArea(){
        $subpro = $this->input->post('subpro');
        $area = $this->input->post('area');
        $pep = $this->input->post('pep');

        $cant = null;
        if($subpro != null && $area != null && $pep != null){
            $res  = $this->m_subproy_pep_grafo->existPepSubArea($subpro,$area,$pep);
            $cant = $res->num_rows() == 1 ? ($res->row()->cant >= 1 ? '1' : '0') : '0';
        }else{
            $cant = '1';//Si hay un error que simule que si existe
        }
        echo $cant;
    }
    
    
    
    public function delSubProPep(){
        $data['error']    = EXIT_ERROR;       
        $data['msj'] = null;
        try{
    
            $idSubPep = $this->input->post('id_subpep');
            $data = $this->m_subproy_pep_grafo->deleteSubPepArea($idSubPep);
            if($data['error'] == EXIT_ERROR){
                throw new Exception($data['msj']);               
            }
            $SubProy = $this->input->post('subProy');
            $data['tablaSubProyPep'] = $this->makeHTLMTablaPepSubPro($this->m_subproy_pep_grafo->getPep1SubProy($SubProy));
            
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
         
        echo json_encode(array_map('utf8_encode', $data));         
    }
    
    public function delPep1Pep2(){
        $data['error']    = EXIT_ERROR;
        $data['msj'] = null;
        try{
    
            $idPep1Pep2 = $this->input->post('id_pp');
            $data = $this->m_subproy_pep_grafo->deletePepPep2($idPep1Pep2);
            if($data['error'] == EXIT_ERROR){
                throw new Exception($data['msj']);
            }
             $data['tbPep1Pep2'] = $this->makeHTLMTablaPep1Pep2($this->m_subproy_pep_grafo->getPep1Pep2());	
    
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
         
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function addPep1Pep2(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $tipoPep = $this->input->post('radioPep');
            $inputP1S = $this->input->post('inputP1S');
            $inputCorreS = $this->input->post('inputCorreS');
            $inputP1P = $this->input->post('inputP1P');
            $inputCorreP = $this->input->post('inputCorreP');
            $finalPep1 = '';
            $finalPep2 = '';
            if($tipoPep=='P'){
                if($inputP1P !=null && $inputCorreP){
                    $finalPep1 = $inputP1P;
                    $finalPep2 = $inputP1P.'-'.$inputCorreP;
                }else{
                    throw new Exception('Datos erroneos P..');
                }
            }else if($tipoPep=='S'){
                if($inputP1S !=null && $inputCorreS){
                    $finalPep1 = $inputP1S;
                    $finalPep2 = $inputP1S.'-'.$inputCorreS;
                }else{
                    throw new Exception('Datos erroneos S..');
                }
            }else{
                throw new Exception('Tipo Pep invalido.');
            }
                       
            $data = $this->m_subproy_pep_grafo->insertPep1Pep2(strtoupper($finalPep1), strtoupper($finalPep2));     
            if($data['error'] == EXIT_ERROR){
                throw new Exception($data['msj']);
            }       
            $data['tbPep1Pep2'] = $this->makeHTLMTablaPep1Pep2($this->m_subproy_pep_grafo->getPep1Pep2());	            
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function refreshGrafo1(){
        $data ['error']= EXIT_ERROR;
        $data['msj'] = null;
        try{
            
            /********MIGUEL RIOS 13062018 update DE SISEGOS*************/
            $data = $this->m_tranferencia_wu->execUpdateSISEGOENM();
            if($data['error']==EXIT_ERROR){
                throw new Exception("ERROR execUpdateSISEGOENM");
            }else{
                $data = $this->m_tranferencia_wu->execGetUpdateExternos();
                if($data['error']==EXIT_ERROR){
                    throw new Exception("ERROR loadIdMatPendiente");
                }
            }
            /********************************************************/
            
            
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }
    
    public function refreshGrafo2(){
        $data ['error']= EXIT_ERROR;
        $data['msj'] = null;
        try{
            $data = $this->m_tranferencia_wu->execGetGrafos();
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }
    
    public function refreshGrafo3(){
        $data ['error']= EXIT_ERROR;
        $data['msj'] = null;
        try{
            $data = $this->m_tranferencia_wu->execLoadCertificacion();
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }
    
    public function refreshGrafo4(){
        $data ['error']= EXIT_ERROR;
        $data['msj'] = null;
        try{
            $data = $this->m_tranferencia_wu->execLoadCertificacionMO();
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }
    
    public function reloadTables(){
        $data ['error']= EXIT_ERROR;
        $data['msj'] = null;
        try{
            /*
           $data['tbPep1SubPro'] = $this->makeHTLMTablaPepSubPro($this->m_subproy_pep_grafo->getPep1SubProy(''));
           $data['tbPep1Presu'] = $this->makeHTLMTablaPepPresupuesto($this->m_subproy_pep_grafo->getPepPresupuesto());
           $data['tbPepGrafo'] = $this->makeHTLMTablaPepGrafo($this->m_subproy_pep_grafo->getPep2Grafos());	           
           $data['tbPep1Pep2'] = $this->makeHTLMTablaPep1Pep2($this->m_subproy_pep_grafo->getPep1Pep2());
           */
            $data['tbPep1SubPro'] = $this->makeHTLMTablaPepSubPro(null);
            $data['tbPep1Presu'] = $this->makeHTLMTablaPepPresupuesto(null);
            $data['tbPepGrafo'] = $this->makeHTLMTablaPepGrafo($this->m_subproy_pep_grafo->getPep2Grafos());
            $data['tbPep1Pep2'] = $this->makeHTLMTablaPep1Pep2(null);
           $data['error']= EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function uploadPep2Grafo(){
        $data ['error']= EXIT_ERROR;
        $data['msj'] = null;
        try{
            $uploaddir =  'uploads/grafos/';//ruta final del file
            $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
            if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
                $fp = fopen($uploadfile, "r");
                $linea = fgets($fp);
                $comp = preg_split("/[\t]/", $linea);
                fclose($fp);
                if(count($comp)==NUM_COLUM_TXT_CARGA_PEP2_GRAFO){
                    $this->session->set_flashdata('rutaFileP2G',$uploadfile);
                    $data['error'] = EXIT_SUCCESS;
                }else{
                    throw new Exception('El archivo no cuenta con la estructura correcta (2 columnas separados por tabulaciones.)');
                }
    
            } else {
                throw new Exception('Hubo un problema con la carga del archivo al servidor, comuniquese con el administrador.');
            }
    
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }
    
    public function uploadPep2Grafo2(){
        $data ['error']= EXIT_ERROR;
        $data['msj'] = null;
        try{
            $data = $this->m_subproy_pep_grafo->loadDataImportPep2Grafo($this->session->flashdata('rutaFileP2G'));
            if($data['error']==EXIT_ERROR){
                throw new Exception($data['msj']);                
            }
            $data['tbPepGrafo'] = $this->makeHTLMTablaPepGrafo($this->m_subproy_pep_grafo->getPep2Grafos());
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
       echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function makeHTLMTablaSisegoPepGrafo($lista){
        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th style="width:10px;"></th>
                            <th>SISEGO</th>
                            <th>PEP2</th>
                            <th>GRAFO</th>
							<th>ESTADO</th>
                        </tr>
                    </thead>
                    <tbody>';
        if($lista!=null){
            foreach($lista->result() as $row){
        
                $html .=' <tr>
                                <th><a data-id_pp ="'.$row->id.'" onclick="delSisePep2Grafo(this)"><img alt="Eliminar" height="20px" width="20px" src="public/img/iconos/delete.png"></a></th>
    							<td>'.$row->sisego.'</td>
    							<td>'.$row->pep2.'</td>
    							<td>'.$row->grafo.'</td>
    							<td>'.$row->situacion.'</td>
    						</tr>';
            }
        }
        $html .='</tbody>
                </table>';
    
        return utf8_decode($html);
    }
    
    public function delSisegoPepGrafo(){
        $data['error']    = EXIT_ERROR;
        $data['msj'] = null;
        try{
    
            $idPep1Pep2 = $this->input->post('id_pp');
            $data = $this->m_subproy_pep_grafo->deleteSisegoPepGrafo($idPep1Pep2);
            if($data['error'] == EXIT_ERROR){
                throw new Exception($data['msj']);
            }
	        $data['tbSisegoPepGrafo'] = $this->makeHTLMTablaSisegoPepGrafo($this->m_subproy_pep_grafo->getSisegoPep2Grafo());
                
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
         
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function uploadSisegoPep2Grafo(){
        $data ['error']= EXIT_ERROR;
        $data['msj'] = null;
        try{
            $uploaddir =  'uploads/grafos/';//ruta final del file
            $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
            if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
                $fp = fopen($uploadfile, "r");
                $linea = fgets($fp);
                $comp = preg_split("/[\t]/", $linea);
                fclose($fp);
                if(count($comp)==NUM_COLUM_TXT_CARGA_SISEGO_PEP2_GRAFO){
                    $this->session->set_flashdata('rutaFileSP2G',$uploadfile);
                    $data['error'] = EXIT_SUCCESS;
                }else{
                    throw new Exception('El archivo no cuenta con la estructura correcta (3 columnas separados por tabulaciones.)');
                }
    
            } else {
                throw new Exception('Hubo un problema con la carga del archivo al servidor, comuniquese con el administrador.');
            }
    
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }
    
    public function uploadSisegoPep2Grafo2(){
        $data ['error']= EXIT_ERROR;
        $data['msj'] = null;
        try{
            $data = $this->m_subproy_pep_grafo->loadDataImportSisegoPep2Grafo($this->session->flashdata('rutaFileSP2G'));
            if($data['error']==EXIT_ERROR){
                throw new Exception($data['msj']);
            }
            $data['tbSisegoPepGrafo'] = $this->makeHTLMTablaSisegoPepGrafo($this->m_subproy_pep_grafo->getSisegoPep2Grafo());
            }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function makeHTLMTablaItemPepGrafo($lista){
        $html = '<table id="data-table6" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th style="width:10px;"></th>
                            <th>ITEMPLAN</th>
                            <th>AREA</th>
                            <th>PEP2</th>
                            <th>GRAFO</th>
                        </tr>
                    </thead>
                    <tbody>';
        if($lista!=null){
            foreach($lista->result() as $row){        
                $html .=' <tr>
                                <th><a data-id_pp ="'.$row->id.'" onclick="delItemPep2Grafo(this)"><img alt="Eliminar" height="20px" width="20px" src="public/img/iconos/delete.png"></a></th>
    							<td>'.$row->itemplan.'</td>
    							<td>'.$row->area.'</td>
    							<td>'.$row->pep2.'</td>
    							<td>'.$row->grafo.'</td>
    						</tr>';
            }
        }
        $html .='</tbody>
                </table>';
    
        return utf8_decode($html);
    }
    
    public function uploadItemPep2Grafo(){
        $data ['error']= EXIT_ERROR;
        $data['msj'] = null;
        try{
            $uploaddir =  'uploads/grafos/';//ruta final del file
            $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
            if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
                $fp = fopen($uploadfile, "r");
                $linea = fgets($fp);
                $comp = preg_split("/[\t]/", $linea);
                fclose($fp);
                if(count($comp)==NUM_COLUM_TXT_CARGA_ITEM_PEP2_GRAFO){
                    $this->session->set_flashdata('rutaFileIP2G',$uploadfile);
                    $data['error'] = EXIT_SUCCESS;
                }else{
                    throw new Exception('El archivo no cuenta con la estructura correcta (3 columnas separados por tabulaciones.)');
                }
    
            } else {
                throw new Exception('Hubo un problema con la carga del archivo al servidor, comuniquese con el administrador.');
            }
    
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }
    
    public function uploadItemPep2Grafo2(){
        $data ['error']= EXIT_ERROR;
        $data['msj'] = null;
        try{
            $data = $this->m_subproy_pep_grafo->loadDataImportItemPep2Grafo($this->session->flashdata('rutaFileIP2G'));
            if($data['error']==EXIT_ERROR){
                throw new Exception($data['msj']);
            }
	           $data['tbItemPepGrafo'] = $this->makeHTLMTablaItemPepGrafo($this->m_subproy_pep_grafo->getItemplanPep2Grafo());
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function delItemPepGrafo(){
        $data['error']    = EXIT_ERROR;
        $data['msj'] = null;
        try{
    
            $idPep1Pep2 = $this->input->post('id_pp');
            $data = $this->m_subproy_pep_grafo->deleteItemPepGrafo($idPep1Pep2);
            if($data['error'] == EXIT_ERROR){
                throw new Exception($data['msj']);
            }
	         $data['tbItemPepGrafo'] = $this->makeHTLMTablaItemPepGrafo($this->m_subproy_pep_grafo->getItemplanPep2Grafo());
                
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
         
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    
     /*********************miguel rios 12062018******************************/

    public function getHTMLGrafoPep2(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $pep2 = $this->input->post('pep2');
            $listaGrafoPep2 = $this->m_utils->getAllGrafoxPep2($pep2);
            $html = '';
            
            foreach($listaGrafoPep2->result() as $row){
                $html .= '<option value="'.$row->grafo.'">'.$row->grafo.'</option>';
              
            }
            $data['listaGrafoPep2'] = $html;
           
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function eliminaPep2Grafo(){
        $data['error']    = EXIT_ERROR;
        $data['msj'] = null;
        try{
    
            $idPep2 = $this->input->post('selectPEP2');
            $idGrafo = $this->input->post('selectGRAFO');

            $data = $this->m_subproy_pep_grafo->deletePep2Grafo($idPep2,$idGrafo);
            if($data['error'] == EXIT_ERROR){
                throw new Exception($data['msj']);
            }
             $data['tbPepGrafo'] = $this->makeHTLMTablaPepGrafo($this->m_subproy_pep_grafo->getPep2Grafos());   
                
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
         
        echo json_encode(array_map('utf8_encode', $data));
    } 


     public function addupdatePEPMonto(){
        $data['error']    = EXIT_ERROR;
        $data['msj'] = null;
        try{
    
            $idPep = strtoupper($this->input->post('addpep'));
            $monto = $this->input->post('addmonto');

            $existe = doubleval($this->m_subproy_pep_grafo->verificaPEP($idPep));

            if($existe>0){
                $data = $this->m_subproy_pep_grafo->insert_update_MontoPEP($idPep,$monto,1);
                if($data['error'] == EXIT_ERROR){
                    throw new Exception($data['msj']);
                }
            }else{
                $data = $this->m_subproy_pep_grafo->insert_update_MontoPEP($idPep,$monto,0);
                if($data['error'] == EXIT_ERROR){
                    throw new Exception($data['msj']);
                }
            }

             $data['tbPep1Presu'] = $this->makeHTLMTablaPepPresupuesto($this->m_subproy_pep_grafo->getPepPresupuesto());  
                
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
         
        echo json_encode(array_map('utf8_encode', $data));
    }


    /***************************************************/
    
    public function insertSisePep2GrafoFromSisego(){
        $data['error']    = EXIT_ERROR;
        $data['msj'] = null;
        try{
            header('Access-Control-Allow-Origin: *');
            /***DATOS FROM SISEGOS***/
            $jsonLog        = null;
            $indicador      = trim($this->input->post('sisego'));
            $pep2           = trim($this->input->post('pep2'));
            $grafo          = trim($this->input->post('grafo'));
            $fecha_envio    = $this->input->post('envio');            
            $itemplan       = $this->input->post('itemplan');
			$flg_update_pep = $this->input->post('flg_update_pep');
			
			
			$jsonLog = array(
								 'sisego' => $indicador,
								 'pep2'   => $pep2,
								 'grafo'  => $grafo,
								 'fecha_envio' => $fecha_envio,
								 'itemplan'    => $itemplan,
								 'flg_update_pep' => $flg_update_pep
							);
			
			// if($indicador){
                // throw new Exception('Trama bloqueada, comunicarse con Owen S.');
            // }
			
			$this->m_utils->saveLogSigoplus('INFO SISEGO PEP2 GRAFO RESPUESTA', '', $itemplan, '', $indicador, '', '', 'INFO DE TRAMA', 'INFO DE TRAMA', '1', null, $jsonLog, json_encode($data));
						
            if($indicador == '' || $pep2 == '' || $grafo == '' || $itemplan == null || $itemplan == ''){
                 throw new Exception('Datos incompletos');
            }
			
			if(!is_numeric($grafo)) {
				throw new Exception('se envio el grafo de manera incorrecta, el grafo debe ser numerico');
			}
			
			$fecha = $this->m_utils->fechaActual();
			
			$countExistSisego = $this->m_utils->getCountExistSisego($indicador);
			if($countExistSisego > 0) {
				$data = $this->m_subproy_pep_grafo->updateSisegoPep2Grafo($indicador, $pep2, $grafo, $itemplan, $fecha);
			} else {
				throw new Exception('El sisego : '.$indicador.' no existe en la Web PO.');
			}
			
			// else {
				// $data = $this->m_subproy_pep_grafo->insertSisegoPep2Grafo($indicador, $pep2, $grafo, $itemplan, $fecha);
			// }
            
            if($data['error'] == EXIT_SUCCESS){
				$data = $this->m_utils->saveLogSigoplus('SISEGO PEP2 GRAFO RESPUESTA DE WEP PO', '', $itemplan, '', $indicador, '', '', 'TRAMA COMPLETADA', 'OPERACION REALIZADA CON EXITO', '1', null, $jsonLog);                
            } else {
				throw new Exception('No se actualizo la pep.');
			}
        }catch(Exception $e){
            $data = $this->m_utils->saveLogSigoplus('SISEGO PEP2 GRAFO RESPUESTA DE WEP PO', '', $itemplan, '', $indicador, '', '', 'TRAMA IMCOMPLETA', $e->getMessage(), '2', null, $jsonLog);            
            $data['msj'] = $e->getMessage();
        }
         
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function sendVRTosisego(){       
        try{
          
        $dataSend = ['ptr' 		    => '2018-31155370',
                     'itemplan'     => '18-0320500216',
                     'eecc' 		=> 'LARI',
                     'jefatura' 	=> 'HUANCAYO',
                     'fecha' 		=> '2018-07-09',
                     'vr' 			=> '4118095',
                     'sisego' 		=> '2018-05-44471',];
        //$url = 'https://gicsapps.com:8080/obras2/recibir_dis.php';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://gicsapps.com:8080/obras2/recibir_dis.php');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataSend);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        $resp = array();
        $resp = json_decode($response);
        }catch(Exception $e){
            _log('$response:::'.$e->getMessage());
        }
        echo json_decode($response);
    }
    
    
    
}