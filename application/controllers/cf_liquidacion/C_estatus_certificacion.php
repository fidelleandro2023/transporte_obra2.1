<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_estatus_certificacion extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_liquidacion/m_estatus_certificacion');       
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }
    
    public function index(){
        $logedUser = $this->session->userdata('usernameSession');
        if($logedUser != null){
            $eecc = '';            
            $idEECC = $this->session->userdata('eeccSession');            
            if($idEECC  ==  ID_EECC_COBRA){
                $eecc   =   'COBRA';
            }else if($idEECC  ==  ID_EECC_LARI){
                $eecc   =   'LARI';
            }else if($idEECC  ==  ID_EECC_DOMINION){
                $eecc   =   'DOMINIONPERU SOLUCIONES Y SERVICIOS S.A.C.';             
            }else if($idEECC  ==  ID_EECC_EZENTIS){
                $eecc   =   'CALATEL';                
            }            
            $data['eecc']    =   $eecc;
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_estatus_certificacion->getBandejaAlarmasMO($eecc));
            $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
            $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
            $permisos =  $this->session->userdata('permisosArbol');
            #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_CERTIFICACION_MO, ID_PERMISO_HIJO_STATUS_CERTI);
            $result = $this->lib_utils->getHTMLPermisos($permisos, 250, ID_PERMISO_HIJO_STATUS_CERTI, ID_MODULO_ADMINISTRATIVO);
            $data['opciones'] = $result['html'];
            if($result['hasPermiso'] == true){
                $this->load->view('vf_liquidacion/v_estatus_certificacion',$data);
            }else{
                redirect('login','refresh');
            }
        }else{
            redirect('login','refresh');
        }             
    }
    
    public function makeHTLMTablaBandejaAprobMo($listaPTR){
        
        $html = '<table style="font-size: 10px;" id="data-table" class="table table-bordered">
                    <thead class="thead-default">                       
                        <tr role="row">
                            <th colspan="1" style="text-align: center;"></th>
                            <th colspan="2" style="text-align: center; background-color: palegoldenrod;">0 - 3 DIAS</th>
	                        <th colspan="2" style="text-align: center; background-color: darksalmon;">4 - 7 DIAS</th>
                            <th colspan="2" style="text-align: center; background-color: lightblue;"> > 7 DIAS</th>
                        </tr
                        <tr role="row">
                            <th colspan="1" style="text-align:center">Descripcion</th>                            
                            <th colspan="1" style="text-align:center"># PTR</th>
                            <th colspan="1" style="text-align:center">TOTAL</th>
                            <th colspan="1" style="text-align:center"># PTR</th>
                            <th colspan="1" style="text-align:center">TOTAL</th>
                            <th colspan="1" style="text-align:center"># PTR</th>
                            <th colspan="1" style="text-align:center">TOTAL</th>
                        </tr>
                    </thead>
                    
                    <tbody>';
        
        foreach($listaPTR->result() as $row){
            
            $html .=' <tr>
                        
                            <th>'.$row->descripcion.'</th>                                                      
							<td style="text-align:center"><a style="color:blue" data-rango="1" data-origen="'.$row->origen.'" onclick="getDetalle(this)">'.(($row->hasta3 == null) ? 0 : $row->hasta3).'</a></td>
							<td style="text-align:right">'.number_format($row->total_hasta3, 2, '.', ',').'</td>
							<td style="text-align:center"><a style="color:blue" data-rango="2" data-origen="'.$row->origen.'" onclick="getDetalle(this)">'.(($row->hasta7 == null) ? 0 : $row->hasta7).'</a></td>
							<td style="text-align:right">'.number_format($row->total_hasta7, 2, '.', ',').'</td>
							<td style="text-align:center"><a style="color:blue" data-rango="3" data-origen="'.$row->origen.'" onclick="getDetalle(this)">'.(($row->todo  == null) ? 0 : $row->todo).'</a></td>
						    <td style="text-align:right">'.number_format($row->total_todo, 2, '.', ',').'</td>
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
             
            $eecc = $this->input->post('eecc');
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_estatus_certificacion->getBandejaAlarmasMO($eecc));
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    
    /***************************************************************/
    
    public function getTableDetalleAlarPTRS(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            
            $origen = $this->input->post('origen');
            $rango_fecha  = $this->input->post('rango');
            $eecc   = $this->input->post('eecc');            
            $datos_tabla = null;
            if($origen == FROM_GESTION_VENTANILLA_UNICA){
                $datos_tabla = $this->m_estatus_certificacion->getDetalleCertificacionMOGVU($rango_fecha, $eecc);
				$data['tablaAsigGrafo'] = $this->makeHTLMTablaPTRS($datos_tabla);
            }else if($origen == FROM_GESTION_INGENIERIA){
                $datos_tabla = $this->m_estatus_certificacion->getDetalleCertificacionMOGI($rango_fecha, $eecc);
				$data['tablaAsigGrafo'] = $this->makeHTLMTablaPTRSWithFase($datos_tabla);
            }else if($origen == FROM_CERTIFICADO_MO){
                $datos_tabla = $this->m_estatus_certificacion->getDetalleCertificacionMOCertificado($rango_fecha, $eecc);
				$data['tablaAsigGrafo'] = $this->makeHTLMTablaPTRS($datos_tabla);
            }                        
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function makeHTLMTablaPTRS($listaPTR){
    
        $html = '<table style="font-size: 10px;" id="tabla_detalle" class="table table-bordered">
                    <thead class="thead-default">
                      
                        <tr role="row">
                            <th style="text-align:center">SITUACION</th>
                            <th style="text-align:center">ITEMPLAN</th>
                            <th style="text-align:center">PROYECTO</th>
                            <th style="text-align:center">SUB PROYECTO</th>
                            <th style="text-align:center">PTR</th>
                            <th style="text-align:center">MONTO</th>
                            <th style="text-align:center">CATEGORIA</th>
                            <th style="text-align:center">AREA</th>
                            <th style="text-align:center">JEFATURA</th>                            
                            <th style="text-align:center">EECC</th>
                            <th style="text-align:center">O.C</th>
                            <th style="text-align:center">NUM CERTI</th>
                            <th style="text-align:center">H.G</th>
                            <th style="text-align:center">PEP 1</th>
                        </tr>
                    </thead>
    
                    <tbody>';
    
        foreach($listaPTR->result() as $row){
    
            $html .=' <tr>
                            <td>'.$row->situacion.'</td>
                            <td>'.$row->itemplan.'</td>
                            <td>'.$row->proyectoDesc.'</td>    
                            <td>'.$row->subProyectoDesc.'</td>  
                            <th>'.$row->ptr.'</td>                              
							<td style="text-align:right">'.(($row->monto_mo == null) ? 0.00 : number_format($row->monto_mo, 2, '.', ',')).'</td>
							<td>'.$row->categoria.'</td>
    					    <td>'.$row->areaDesc.'</td>
						    <td>'.$row->jefatura.'</td>
					        <td>'.$row->eecc.'</td>
				            <td>'.$row->orden_compra.'</td>
			                <td>'.$row->nro_certificacion.'</td>
		                    <td>'.$row->hoja_gestion.'</td>
		                    <td>'.$row->pep1.'</td>
						</tr>';
        }
        $html .='</tbody>
                </table>';
    
        return utf8_decode($html);
    }    
    
	/**PERSONALIZADO 18.03.2020 MAS FASE CZAVALA PEDIDO CRISTIAN**/
	
	public function makeHTLMTablaPTRSWithFase($listaPTR){
    
        $html = '<table style="font-size: 10px;" id="tabla_detalle" class="table table-bordered">
                    <thead class="thead-default">
                      
                        <tr role="row">
                            <th style="text-align:center">SITUACION</th>
                            <th style="text-align:center">ITEMPLAN</th>
							<th style="text-align:center">FASE</th>
                            <th style="text-align:center">PROYECTO</th>
                            <th style="text-align:center">SUB PROYECTO</th>
                            <th style="text-align:center">PTR</th>
                            <th style="text-align:center">MONTO</th>
                            <th style="text-align:center">CATEGORIA</th>
                            <th style="text-align:center">AREA</th>
                            <th style="text-align:center">JEFATURA</th>                            
                            <th style="text-align:center">EECC</th>
                            <th style="text-align:center">O.C</th>
                            <th style="text-align:center">NUM CERTI</th>
                            <th style="text-align:center">H.G</th>
                            <th style="text-align:center">PEP 1</th>
                        </tr>
                    </thead>
    
                    <tbody>';
    
        foreach($listaPTR->result() as $row){
    
            $html .=' <tr>
                            <td>'.$row->situacion.'</td>
                            <td>'.$row->itemplan.'</td>
							<td>'.$row->faseDesc.'</td>
                            <td>'.$row->proyectoDesc.'</td>    
                            <td>'.$row->subProyectoDesc.'</td>  
                            <th>'.$row->ptr.'</td>                              
							<td style="text-align:right">'.(($row->monto_mo == null) ? 0.00 : number_format($row->monto_mo, 2, '.', ',')).'</td>
							<td>'.$row->categoria.'</td>
    					    <td>'.$row->areaDesc.'</td>
						    <td>'.$row->jefatura.'</td>
					        <td>'.$row->eecc.'</td>
				            <td>'.$row->orden_compra.'</td>
			                <td>'.$row->nro_certificacion.'</td>
		                    <td>'.$row->hoja_gestion.'</td>
		                    <td>'.$row->pep1.'</td>
						</tr>';
        }
        $html .='</tbody>
                </table>';
    
        return utf8_decode($html);
    }    
}