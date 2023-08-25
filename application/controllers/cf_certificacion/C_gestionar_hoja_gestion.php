<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_gestionar_hoja_gestion extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_certificacion/m_gestionar_hoja_gestion');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index(){
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
    	       $data['listaEECC']     = $this->m_utils->getAllEECC();
               $data['listaZonal']    = $this->m_utils->getAllZonalGroup();
               $data['cmbJefatura']   = $this->m_utils->getJefaturaCmb();
        	   $data['listaSubProy']  = $this->m_utils->getAllSubProyecto();
        	   $data['listafase']     = $this->m_utils->getAllFase();
               $data['tablaSiom']     = $this->getTablaHojaGestion($this->m_gestionar_hoja_gestion->getBandejaHojaGestion(NULL,NULL,NULL,NULL,NULL, NULL));               
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
               $permisos =  $this->session->userdata('permisosArbol');
        	   $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_CERTIFICACION_MO, ID_PERMISO_HIJO_GESTIONAR_HOJA_GESTION, ID_MODULO_ADMINISTRATIVO);
        	   $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	         $this->load->view('vf_certificacion/v_gestionar_hoja_gestion',$data);
        	   }else{
        	       redirect('login','refresh');
        	   }
    	 }else{
        	 redirect('login','refresh');
	    }
             
    }
    
    function getTablaHojaGestion($listaHojaGestion) {
        
        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th></th>              
                            <th>Hoja Gestion</th>                           
                            <th>EECC</th>
                            <th>Promotor</th>
							<th># PO</th>
                            <th>Monto Limite</th>                            
                            <th>Monto Utilizado</th>        
                            <th>Cesta</th>    
							<th>Orden C.</th>
							<th># Certificacion</th>	
							<th>Fecha creacion</th>							
                            <th>Estado</th>
							<th>Fecha Proceso</th>	
							<th>Fecha Certificacion</th>	
                        </tr>
                    </thead>                    
                    <tbody>';
             if($listaHojaGestion != null){                                                                                                  
                foreach($listaHojaGestion as $row){     
                    $btnEnProceso = '';
                    if($row->estado == 1 || $row->estado == 2){
                        $btnEnProceso = '<a data-hg="'.$row->id.'" data-hgtxt="'.$row->hoja_gestion.'" onclick="enProcesoHg(this)"><i title="buscar" style="color:#A4A4A4" class="zmdi zmdi-hc-2x zmdi-time-restore-setting"></i></a>';
                    }
                    $html .=' <tr>
                            <th style="width:7%">
                               <a data-idhg="'.$row->id.'" data-hg="'.$row->hoja_gestion.'" onclick="getPtrByHojaGestion(this)"><i title="buscar" style="color:#A4A4A4" class="zmdi zmdi-hc-2x zmdi-search"></i></a> 
                                 '.$btnEnProceso.'                        
                            </th>
                            <td>'.$row->hoja_gestion.'</td>                            
                            <td>'.$row->empresaColabDesc.'</td>
                            <td>'.$row->tipoObraDesc.'</td>
							<td>'.$row->num_ptr.'</td>
                            <td>'.$row->limite_format.'</td>
                            <td>'.$row->real_format.'</td>
                            <td>'.$row->cesta.'</td>
							<td>'.$row->orden_compra.'</td>
							<td>'.$row->nro_certificacion.'</td>
							<td>'.$row->fecha_creacion.'</td>
                            <td>'.$row->tipoEstadoDesc.'</td> 
							<td>'.$row->fecha_en_proceso.'</td>
                            <td>'.$row->fecha_certificacion.'</td> 							
                        </tr>';
                }
             }
            $html .='</tbody>
                </table>';
                    
            return $html;
    }    
    
    function getPtrsByHojaGestion() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            $hojaGestion = $this->input->post('hg');
            $id_hoja_gestion = $this->input->post('idhg');
            if($hojaGestion == null) {
                throw new Exception("ERROR Hoja Gestion No valido.");
            }
            $listaEstado = $this->m_gestionar_hoja_gestion->getPtrsByHojaGestion($hojaGestion);
            $infoHg = $this->m_gestionar_hoja_gestion->getBolsaHgDataByHG($id_hoja_gestion);
            $infoPtrs = $this->getTablaListarPtrByHojaGestion($listaEstado, $infoHg);
			
			$tablaLog = $this->tablaLogHojaGestion($hojaGestion);
			
            $tablaSiom = $infoPtrs['tabla'];
			$data['tablaLog']  = $tablaLog;
            $data['tablaSiom'] = $tablaSiom;
            $data['cesta']  = $infoHg['cesta'];
            $data['oc']     = $infoHg['orden_compra'];
            $data['estado'] = $infoHg['estado'];
            $data['nro_cert'] = $infoHg['nro_certificacion']; 
            $data['jsonDataFIleValido'] = json_encode(array_map('utf8_encode', $infoPtrs['array']));
            $data['error']  = EXIT_SUCCESS;            
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getTablaListarPtrByHojaGestion($listaEstado, $infoHg) {
        
        $html = '<table id="data-table2" class="table table-bordered container">
                        <thead class="thead-default">
                            <tr>
                                <th></th>
                                <th>ITEMPLAN</th>
                                <th>CODIGO PO</th>
                                <th>MONTO MO</th>
                                <th>PEP 1</th>
								<th>PEP 2</th>
                                <th>HOJA GESTION</th>
                            </tr>
                        </thead>
    
                        <tbody>';
        $indice = 0;
        $array_valido = array();
            foreach($listaEstado as $row){
                $html .='<tr id="tr'.$indice.'">
                            <td style="width: 5px;">'.(($infoHg['estado']==2) ? '<a style="cursor:pointer;" data-indice="'.$indice.'"data-ptr="'.$row->ptr.'" data-id_hg="'.$infoHg['id'].'" data-hg_txt="'.$row->hoja_gestion.'" onclick="removeTRreservado(this)"><img class="delete_ptr" alt="Eliminar" height="20px" width="20px" src="public/img/iconos/delete.png"></a>' : '').'</td>                    
                            <td>'.$row->itemplan.'</td>
                            <td>'.$row->ptr.'</td>
                            <td>'.$row->monto_mo_format.'</td>
                            <td>'.$row->pep1.'</td>
							<td>'.$row->pep2.'</td>
                            <td>'.$row->hoja_gestion.'</td>
                          </tr>';
                $indice++;
                array_push($array_valido, $row->ptr.'|'.$row->itemplan);
            }
       
         
        $html .='</tbody>
                </table>';
        $data['tabla'] = utf8_decode($html);
        $data['array'] = $array_valido;
        return $data;;
    }

    function filtrarTablaHG() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            $tipoObra       = ($this->input->post('tipObra')=='')       ? null : $this->input->post('tipObra');
            $eecc           = ($this->input->post('eecc')=='')          ? null : $this->input->post('eecc');
            $estado         = ($this->input->post('estado') == '')      ? null : $this->input->post('estado');
            $hojaGestion    = ($this->input->post('hojaGestion') == '') ? null : $this->input->post('hojaGestion');
            $codigoPo       = ($this->input->post('codigoPo') == '')    ? null : $this->input->post('codigoPo');
            $cesta          = ($this->input->post('cesta') == '')       ? null : $this->input->post('cesta');
            $data['tablaBandejaHG'] = $this->getTablaHojaGestion($this->m_gestionar_hoja_gestion->getBandejaHojaGestion($tipoObra, $eecc, $estado, $hojaGestion, $codigoPo, $cesta));
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function setHojaGestionEnProceso() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            
            $idHojaGestion  = $this->input->post('id');  
            $cesta          = $this->input->post('txtCesta');
            $orden_compra   = $this->input->post('txtOC');           
            $idPersona           = $this->session->userdata('idPersonaSession');
            if($idPersona != null){
                $infoHg = $this->m_gestionar_hoja_gestion->getBolsaHgDataByHG($idHojaGestion);
                if($infoHg['estado']==2){
                    $arrayData = array( 'estado'            => 3,
                                        'cesta'             => $cesta,
                                        'orden_compra'      => $orden_compra,
                                        'usuario_en_proceso' => $this->session->userdata('idPersonaSession'),
                                        'fecha_en_proceso'  => $this->fechaActual());
                    
                    $data = $this->m_gestionar_hoja_gestion->updateHojaGestion($idHojaGestion, $arrayData);
                }else{
                    throw new Exception('Estado de Hoja No Valida.');
                }
            }else{
                throw new Exception('Su sesion a terminado, Refresque la pantalla y vuelva a iniciar Sesion.');
            }
           
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function setHojaGestionCertificado() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
    
            $idHojaGestion       = $this->input->post('id_hg');
            $cesta               = $this->input->post('txtCestaCa');
            $orden_compra        = $this->input->post('txtOrdenCompra');
            $nro_certificacion   = $this->input->post('txtNroCertificacion');
            $idPersona           = $this->session->userdata('idPersonaSession');
            if($idPersona != null){
                $infoHg = $this->m_gestionar_hoja_gestion->getBolsaHgDataByHG($idHojaGestion);
                if($infoHg['estado']==3){
                    /**FALTA CERTIFICAR LAS POS AGREGARLES SU OC Y NRO CERTIFICACION****/
                    $jsonDataFile   = $this->input->post('jsonDataFile');
                    $arrayFile = json_decode($jsonDataFile);
                    if($arrayFile!=null){//si al menos viene 1 ptr en la hoja de gestion.
                        $arrayUpCertificacionMo = array();
                        $arrayUpPlanobraPo = array();
                        $arrayUpDetallePlan = array();
                        $arraylogPlanobraPoData = array();
                        foreach($arrayFile as $row){
                            if($row!=null){
                                $row_split = explode('|', $row);
                                log_message('error', print_r($row_split,true));
                                $ptr        = $row_split[0];
                                $itemplan   = $row_split[1];
                                /**actualizamos certificacion_mo**/
                                $dataCMO = array();
                                $dataCMO['ptr']                 = $ptr;
                                $dataCMO['orden_compra']        = $orden_compra;
                                $dataCMO['nro_certificacion']   = $nro_certificacion;
                                $dataCMO['estado']              = CERTIFICACION_MO_CON_ORDEN_COMPRA;
                                $dataCMO['usua_reg_oc']         = $this->session->userdata('userSession');
                                $dataCMO['fec_reg_oc']          = date("Y-m-d");
                                array_push($arrayUpCertificacionMo, $dataCMO);
                                
                                /**pasamos a certificado las po**/
                                $dataUpPlanobraPo = array();
                                $dataUpPlanobraPo['estado_po'] = 6;
                                $dataUpPlanobraPo['codigo_po'] = $ptr;
                                array_push($arrayUpPlanobraPo, $dataUpPlanobraPo);
                                
                                /**registro en log_planobra_po**/
                                $logPlanobraPoData = array();
                                $logPlanobraPoData['codigo_po'] = $ptr;
                                $logPlanobraPoData['itemplan'] = $itemplan;
                                $logPlanobraPoData['idUsuario'] = $idPersona;
                                $logPlanobraPoData['idPoestado'] = 6;
                                $logPlanobraPoData['fecha_registro'] = $this->fechaActual();
                                $logPlanobraPoData['controlador'] = 'Gestionar Hoja Gestion';
                                array_push($arraylogPlanobraPoData, $logPlanobraPoData);                            
                               
                                /**detalleplan**/
                                $dataUpDetallePlan = array();                            
                                $dataUpDetallePlan['oc']    = $orden_compra;
                                $dataUpDetallePlan['ncert'] = $nro_certificacion;
                                $dataUpDetallePlan['poCod'] = $ptr;
                                array_push($arrayUpDetallePlan, $dataUpDetallePlan);
                            }
                        }
                            
                            /**datos de la hoja de gestion**/
                            $dataHojagestion = array( 'cesta'               => $cesta,
                                                    'estado'                => 4,
                                                    'orden_compra'          => $orden_compra,
                                                    'nro_certificacion'     => $nro_certificacion,
                                                    'usuario_cetificacion'  => $idPersona,
                                                    'fecha_certificacion'   => $this->fechaActual());
                        /*
                            log_message('error', '$arrayUpCertificacionMo:'.print_r($arrayUpCertificacionMo,true));
                            log_message('error', '$arrayUpPlanobraPo:'.print_r($arrayUpPlanobraPo,true));
                            log_message('error', '$arraylogPlanobraPoData:'.print_r($arraylogPlanobraPoData,true));
                            log_message('error', '$arrayUpDetallePlan:'.print_r($arrayUpDetallePlan,true));
                            log_message('error', '$dataHojagestion:'.print_r($dataHojagestion,true));
                       */ 
                            log_message('error', 'c a:'.print_r($data, true));
                            $data = $this->m_gestionar_hoja_gestion->updateHojaGestionCertificacion($arraylogPlanobraPoData, $arrayUpCertificacionMo, $arrayUpPlanobraPo, $arrayUpDetallePlan, $dataHojagestion, $idHojaGestion);
                            log_message('error', 'c:'.print_r($data, true));
                    }else{
                        throw new Exception('La Hoja de Gestion debe tener almenos 1 PO para Certificarla.');
                    }                    
                }else{
                    throw new Exception('Estado de Hoja No Valida.');
                }
            }else{
                throw new Exception('Su sesion a terminado, Refresque la pantalla y vuelva a iniciar Sesion.');
            }
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone','America/Lima');
        setlocale(LC_TIME, "es_ES","esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }    
	
	public function makeCSVHojaGestionMO(){
        $data['error']= EXIT_ERROR;
        $data['msj'] = null;
        try{
            //cabeceras para descarga
            header('Content-Type: application/octet-stream');
            header("Content-Transfer-Encoding: Binary");
            header("Content-disposition: attachment; filename=\"Detalle_hoja_gestion.csv\"");
            //preparar el wrapper de salida
            $outputBuffer = fopen("php://output", 'w');
            $detalleplan = $this->m_gestionar_hoja_gestion->getDataToExcelReport();
            if(count($detalleplan->result()) > 0){
                fputcsv($outputBuffer,  explode('\t',"PROYECTO"."\t"."SUBPROYECTO"."\t"."EECC"."\t"."AREA"."\t"."PEP 1"."\t"."PEP 2"."\t"."ITEMPLAN"."\t"."PTR"."\t"."MONTO"."\t"."CESTA"."\t"."HOJA GESTION"."\t"."ORDEN COMPRA"."\t"."NRO CERTIFICACION"."\t"."PROMOTOR"."\t"."ESTADO HOJA GESTION"."\t"."FECHA CREACION"."\t"."FECHA EN PROCESO"."\t"."FECHA CERTIFICACION"));
                foreach ($detalleplan->result() as $row){
                    fputcsv($outputBuffer, explode('\t', utf8_decode($row->proyectoDesc."\t". $row->subProyectoDesc."\t". $row->empresaColabDesc."\t".$row->areaDesc."\t". $row->pep1."\t". $row->pep2."\t".$row->itemplan."\t".
                        $row->ptr."\t". $row->monto_mo."\t". $row->cesta."\t". $row->hoja_gestion."\t". $row->orden_compra."\t". $row->nro_certificacion."\t". $row->tipoObraDesc."\t". $row->tipoEstadoDesc."\t". $row->fecha_creacion."\t". $row->fecha_en_proceso."\t". $row->fecha_certificacion)));
                }
            }
            fclose($outputBuffer);
            $data['error'] = EXIT_SUCCESS;
            //cerramos el wrapper
    
        }catch (Exception $e){
            $data['msj'] = 'Error interno, al crear archivo detalleplan';
        }
        return $data;
    }
    
    function removeOnePtrFromHojaGestion() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
			$idPersona           = $this->session->userdata('idPersonaSession');
            if($idPersona != null){
				$ptr                = $this->input->post('ptr');  
				$idHojaGestion      = $this->input->post('id_hg');
				$hoja_gestion		= $this->input->post('hg_txt');
				$arrayData = array( 'ptr' 				=> $ptr,
									'hoja_gestion'      => null,
									'estado_validado'   => 0,
									'usua_remove_hg'    => $this->session->userdata('idPersonaSession')
				); 
				
				$arrayDataLog = array( 'ptr' 			=> $ptr,
									'hoja_gestion'      => $hoja_gestion,
									'fecha_remove'   	=> $this->fechaActual(),
									'usuario_remove'    => $this->session->userdata('idPersonaSession')
				); 				
				
				$data = $this->m_gestionar_hoja_gestion->updatePtrFromHojaGestion($ptr, $arrayData, $idHojaGestion, $arrayDataLog);
           }else{
                throw new Exception('Su sesion a terminado, Refresque la pantalla y vuelva a iniciar Sesion.');
            }
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
		
	function tablaLogHojaGestion($hoja_gestion) {
		$htmlLOG = null;
		$arrayLogPO = $this->m_utils->getDataLogHojaGestion($hoja_gestion);

		$htmlLOG .= '<table id="tabla_log" class="table table-bordered">
						<thead class="thead-default">
							<tr>
								<th>H. GESTION</th>
								<th>PO</th>
								<th>USUARIO</th>
								<th>FECHA</th>
							</tr>
						</thead>
						<tbody>';

		foreach ($arrayLogPO as $row) {
			$htmlLOG .= ' <tr>
							<th>' . $row['hoja_gestion'] . '</th>
							<td>' . $row['ptr'] . '</td>
							<td>' . $row['nombre'] . '</td>
							<td>' . $row['fecha_remove'] . '</td>
						</tr>';
		}
		$htmlLOG .= '</tbody>
				</table>';
				
		return $htmlLOG;
	}
}