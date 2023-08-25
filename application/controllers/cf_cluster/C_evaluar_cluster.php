<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * @author CARLOS ZAVALA C.
 * 18/01/2018
 *
 */
class C_evaluar_cluster extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_cluster/m_bandeja_cluster');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->library('zip');
        $this->load->helper('url');
    }
    
	public function index()
	{  	   
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
	           $codCluster = (isset($_GET['cod']) ? $_GET['cod'] : '');
	           $infoCluster = $this->m_bandeja_cluster->getEstadoClusterByCod($codCluster);
	           if($infoCluster!=null){	
	               if($infoCluster['estado'] ==  0){ //pendiente de cotizacion              
        	           $data['codClust']    =   $codCluster;
                       $data['tablaHijos'] = $this->makeHTLMTablaBandejaHijos($this->m_bandeja_cluster->getHijosClusterByItemplan($codCluster));
        	           $data['listaTiCen'] = $this->m_utils->getAllCentral();
        	           $data['nombreUsuario'] =  $this->session->userdata('usernameSession');	
                       $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
                	   $permisos =  $this->session->userdata('permisosArbol');
                	   $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_CLUSTER_SISEGO, ID_PERMISO_HIJO_BANDEJA_COTIZACION_SISEGO);
                	   $data['opciones'] = $result['html'];
                	   if($result['hasPermiso'] == true){
                	       $this->load->view('vf_cluster/v_evaluar_cluster',$data);
                	   }else{
                	       redirect('login','refresh');
        	           }
	               }else{
	                   redirect('cotclus','refresh');
	               }
	           }else{
	               redirect('cotclus','refresh');
	           }
	   }else{
	       redirect('login','refresh');
	   }
    }
        
    public function makeHTLMTablaBandejaHijos($listaPTR){     
        $html = '<table class="table table-bordered" style="font-size: 10px;">
                    <thead class="thead-default">
                        <tr>                 
                            <th>SISEGO</th>                            
                            <th>NOMBRE</th>                              
                            <th>DEPARTAMENTO</th>  
                            <th>DIAS</th>                                   
                            <th>NODO</th>
                            <th>MDF</th>
                            <th>MONTO</th>
                            <th>LATITUD</th>
                            <th>LONGITUD</th>
                        </tr>
                    </thead>
                   
                    <tbody>';
            if($listaPTR!=null){																                                                   
                foreach($listaPTR->result() as $row){        
                   
                $html .='<tr>                                                         
                            <td>'.$row->sisego.'</td>
							<td>'.$row->nombre.'</td>
							<td>'.$row->departamento.'</td>	
                            <td>'.$row->dias.'</td>						
                            <td>'.$row->nodo.'</td>   
                            <td>'.$row->codigoCentral.'</td>
                            <td style="text-align: right;">'.number_format($row->total_soles,2,'.', ',').'</td>    
                            <td>'.$row->latitud.'</td>   
                            <td>'.$row->longitud.'</td>    		
						</tr>';
                    }  
   			  }
			 $html .='</tbody>
                </table>';                    
        return utf8_decode($html);
    }
    
    function sendCotizacionCluster(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try{    
            $nodoPrincipal  = $this->input->post('selectCentral');    
            $nodoRespaldo   = $this->input->post('selectCentral2');
            $facilidadRed   = $this->input->post('inputFacRed');
            $cantCto        = $this->input->post('inputCantCTO');
            $metroTendido   = $this->input->post('inputMetroTen');
            $metroCanali    = $this->input->post('inputMetroCana');
            $cantCamaraNue  = $this->input->post('cantCamaNue');
            $cantPostesNue  = $this->input->post('inputPostNue');
            $cantPostesApo  = $this->input->post('inputCantPostApo');
            $requiereSeia   = $this->input->post('selectRequeSeia');
            $requeAproMtc   = $this->input->post('selectRequeAproMmlMtc');
            $requeAproInc   = $this->input->post('selectRequeAprobINC');
            
            $costoMat       = $this->input->post('inputCostoMat');
            $costmoMo       = $this->input->post('inputCostMo');
            $costoDiseno    = $this->input->post('inputCostoDiseno');            
            $costoExpe      = $this->input->post('inputCostoExpe');
            $costoAdic      = $this->input->post('inputCostoAdicZona');
            $costoTotalMo   = $costmoMo+$costoDiseno+$costoExpe+$costoAdic;
            $cod_cluster    =  $this->input->post('cod_cluster');
            $tiempoEjec     = 30;//30 DIAS SISEGO ESTANDAR
            
            
            if($requeAproMtc    ==  'SI' || $requiereSeia == 'SI'){
                $tiempoEjec = 60;
            }
            if($requeAproInc    ==  'SI'){
                $tiempoEjec = 90;
            }
            
            $dataUpdate = array(
                                'nodo_principal'        => $nodoPrincipal,
                                'nodo_respaldo'         => $nodoRespaldo,
                                'facilidades_de_red'    => $facilidadRed,
                                'cant_cto'              => $cantCto,
                                'metros_tendido'        => $metroTendido,
                                'metors_canalizacion'   => $metroCanali,
                                'cant_camaras_nuevas'   => $cantCamaraNue,
                                'cant_postes_nuevos'    => $cantPostesNue,
                                'cant_postes_apoyo'     => $cantPostesApo,
                                'requiere_seia'         => $requiereSeia,
                                'requiere_aprob_mml_mtc' => $requeAproMtc,
                                'requiere_aprob_inc'    => $requeAproInc,
                                'costo_materiales'      => $costoMat,
                                'costo_mano_obra'       => $costmoMo,
                                'costo_diseno'          => $costoDiseno,
                                'costo_expe_seia_cira_pam'       => $costoExpe,
                                'costo_adicional_rural' => $costoAdic,
                                'costo_total'           => ($costoTotalMo+$costoMat),
                                'tiempo_ejecu_planta_externa'   => $tiempoEjec,
                                'estado'                => 1,
                                'fecha_envio_cotizacion'        => $this->fechaActual(),
                                'usuario_envio_cotizacion'      => $this->session->userdata('idPersonaSession')
            );
            $data = $this->m_bandeja_cluster->updateClusterPadre($cod_cluster, $dataUpdate);
            if($data['error'] == EXIT_ERROR){
                throw new Exception('Error interno al registrar el planobra_cluster - hijos.');
            }else{
                $dataSend = [   'codigo' 		=> $cod_cluster,
                                'materiales'    => $costoMat,
                                'mano_obra'     => $costoTotalMo];
                //       'nodo' 	        => 'MI']; NODO YA NO ES EL QUE ME ENVIA
                
            
                
                 $url = 'https://172.30.5.10:8080/sisego/Cluster/cotizarCluster';
                
                 $response = $this->m_utils->sendDataToURL($url, $dataSend);
                 log_message('error', '$response:'.print_r($response,true));
                
                 if($response->error == EXIT_SUCCESS){
                 log_message('error', 'no error'.$response->error);
                 $this->m_utils->saveLogSigoplus('COTIZACION CLUSTER', $cod_cluster, NULL, NULL, NULL, NULL, NULL, 'TRAMA COMPLETADA', 'OPERACION REALIZADA CON EXITO', 1);
                 }else{
                 $this->m_utils->saveLogSigoplus('COTIZACION CLUSTER', $cod_cluster, NULL, NULL, NULL, NULL, NULL, 'FALLA EN LA RESPUESTA DEL HOSTING', 'OPERACION NO COMPLETADA ERROR EN EL SERVIDOR DEL CLIENTE:'. strtoupper($response->mensaje), '2');
                 }
                 
            }            
            $data['codigo'] = $cod_cluster;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
            //$this->m_utils->saveLogSigoplus('TRAMA CREAR ITEMPLAN FROM SISEGO', $segmento, 'ERROR EN RECEPCION DE TRAMA', $e->getMessage(), 2);
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function fechaActual()
    {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone', 'America/Lima');
        setlocale(LC_TIME, "es_ES", "esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }
}