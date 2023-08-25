<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_bandeja_simulador_mo extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_certificacion/m_bandeja_simulador_mo');
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
               $data['tablaSiom']     = $this->getTablaHojaGestion(null);               
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
               $permisos =  $this->session->userdata('permisosArbol');
        	   $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_CERTIFICACION_MO, ID_PERMISO_HIJO_GESTIONAR_HOJA_GESTION, ID_MODULO_ADMINISTRATIVO);
        	   $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	         $this->load->view('vf_certificacion/v_bandeja_simulador_mo',$data);
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
                            <th>PROYECTO</th>                                  
                            <th>PEP1</th>
                            <th># TOTAL PO</th>
							<th>MONTO INICIAL</th>
                            <th># PO A COMPROMETER</th>                            
                            <th>MONTO A COMPROMETER</th>        
                            <th>NUEVO DISPONIBLE</th>               
                           <!-- <th># PO SIN PRESUPUESTO</th>  
                            <th>MONTO SIN PRESUPUESTO</th>  -->
                        </tr>
                    </thead>                    
                    <tbody>';
             if($listaHojaGestion != null){                                                                                                  
                foreach($listaHojaGestion as $row){     
                    $html .=' <tr>
                                <td>'.$row->proyectoDesc.'</td> 
                                <td>'.$row->pep1.'</td>                            
                                <td>'.$row->total_po.'</td>
                                <td>'.$row->monto_inicial.'</td>
    							<td>'.$row->total_po_si.'</td>
                                <td>'.$row->casteado_si.'</td>
                                <td>'.$row->disponible_si.'</td>
                              <!--  <td>'.$row->total_po_no.'</td>
                                <td>'.$row->casteado_no.'</td>     -->                           
                            </tr>';
                }
             }
            $html .='</tbody>
                </table>';
                    
            return $html;
    }    
    
    
    
    function filtrarTablaSimuladorMO() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            $promotor    = ($this->input->post('tipObra')=='')      ? null : $this->input->post('tipObra');
            $sinDJ       = ($this->input->post('sinDJ')=='')        ? null : $this->input->post('sinDJ');
            $conDJ       = ($this->input->post('conDJ') == '')      ? null : $this->input->post('conDJ');
            $conExpe     = ($this->input->post('conExpe') == '')    ? null : $this->input->post('conExpe');
            $validadas   = ($this->input->post('validadas') == '')  ? null : $this->input->post('validadas');
            
            $criterios = array();
            
            if($sinDJ!=null){//CRITERIO 2
                array_push($criterios, 2);
            }
            if($conDJ!=null){//CRITERIO 3
                array_push($criterios, 3);
            }
            if($conExpe!=null){//CRITERIO 4
                array_push($criterios, 4);
            }
            
            $data['tablaBandejaHG'] = $this->getTablaHojaGestion($this->m_bandeja_simulador_mo->getBandejaSimulador($criterios, $promotor));
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    /**ANTIGUO****/
    function getPtrsByHojaGestion() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            $hojaGestion = $this->input->post('hg');
            $id_hoja_gestion = $this->input->post('idhg');
            if($hojaGestion == null) {
                throw new Exception("ERROR Hoja Gestion No valido.");
            }                
            $listaEstado = $this->m_bandeja_simulador_mo->getPtrsByHojaGestion($hojaGestion);
            $infoHg = $this->m_bandeja_simulador_mo->getBolsaHgDataByHG($id_hoja_gestion);
            $tablaSiom = $this->getTablaListarPtrByHojaGestion($listaEstado);
            $data['tablaSiom'] = $tablaSiom;
            $data['cesta']  = $infoHg['cesta'];
            $data['oc']     = $infoHg['orden_compra'];
            $data['estado'] = $infoHg['estado'];
            $data['nro_cert'] = $infoHg['nro_certificacion'];            
            $data['error']  = EXIT_SUCCESS;            
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getTablaListarPtrByHojaGestion($listaEstado) {
    
        $html = '<table id="data-table2" class="table table-bordered container">
                        <thead class="thead-default">
                            <tr>
                                <th>ITEMPLAN</th>
                                <th>CODIGO PO</th>
                                <th>MONTO MO</th>
                                <th>PEP 1</th>
								<th>PEP 2</th>
                                <th>HOJA GESTION</th>
                            </tr>
                        </thead>
    
                        <tbody>';
            foreach($listaEstado as $row){
                $html .='<tr>
                            <td>'.$row->itemplan.'</td>
                            <td>'.$row->ptr.'</td>
                            <td>'.$row->monto_mo_format.'</td>
                            <td>'.$row->pep1.'</td>
							<td>'.$row->pep2.'</td>
                            <td>'.$row->hoja_gestion.'</td>
                          </tr>';
            }
       
         
        $html .='</tbody>
                </table>';
        return utf8_decode($html);
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
            $data['tablaBandejaHG'] = $this->getTablaHojaGestion($this->m_bandeja_simulador_mo->getBandejaHojaGestion($tipoObra, $eecc, $estado, $hojaGestion, $codigoPo, $cesta));
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
            
            $infoHg = $this->m_bandeja_simulador_mo->getBolsaHgDataByHG($idHojaGestion);
            if($infoHg['estado']==1){
                $arrayData = array( 'estado'        => 2,
                    'cesta'         => $cesta,
                    'orden_compra'  => $orden_compra,
                    'usuario_en_proceso' => $this->session->userdata('idPersonaSession'),
                    'fecha_en_proceso' => $this->fechaActual());
                
                $data = $this->m_bandeja_simulador_mo->updateHojaGestion($idHojaGestion, $arrayData);
            }else{
                throw new Exception('Estado de Hoja No Valida.');
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
            $orden_compra        = $this->input->post('txtOrdenCompra');
            $nro_certificacion   = $this->input->post('txtNroCertificacion');
    
            $infoHg = $this->m_bandeja_simulador_mo->getBolsaHgDataByHG($idHojaGestion);
            if($infoHg['estado']==2){
                $arrayData = array( 'estado'        => 3,
                    'orden_compra'  => $orden_compra,
                    'nro_certificacion' => $orden_compra,
                    'usuario_cetificacion' => $this->session->userdata('idPersonaSession'),
                    'fecha_certificacion' => $this->fechaActual());
               
                $data = $this->m_bandeja_simulador_mo->updateHojaGestion($idHojaGestion, $arrayData);
                
                /**FALTA CERTIFICAR LAS POS AGREGARLES SU OC Y NRO CERTIFICACION****/
                
            }else{
                throw new Exception('Estado de Hoja No Valida.');
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
            $detalleplan = $this->m_bandeja_simulador_mo->getDataToExcelReport();
            if(count($detalleplan->result()) > 0){
                fputcsv($outputBuffer,  explode('\t',"PROYECTO"."\t"."SUBPROYECTO"."\t"."EECC"."\t"."AREA"."\t"."PEP 1"."\t"."PEP 2"."\t"."ITEMPLAN"."\t"."PTR"."\t"."MONTO"."\t"."CESTA"."\t"."HOJA GESTION"."\t"."ORDEN COMPRA"."\t"."NRO CERTIFICACION"."\t"."PROMOTOR"."\t"."ESTADO HOJA GESTION"));
                foreach ($detalleplan->result() as $row){
                    fputcsv($outputBuffer, explode('\t', utf8_decode($row->proyectoDesc."\t". $row->subProyectoDesc."\t". $row->empresaColabDesc."\t".$row->areaDesc."\t". $row->pep1."\t". $row->pep2."\t".$row->itemplan."\t".
                        $row->ptr."\t". $row->monto_mo."\t". $row->cesta."\t". $row->hoja_gestion."\t". $row->orden_compra."\t". $row->nro_certificacion."\t". $row->tipoObraDesc."\t". $row->tipoEstadoDesc)));
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
}