<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_bandeja_alarmas extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_liquidacion/m_bandeja_alarmas');       
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }
    
    public function index(){
        $logedUser = $this->session->userdata('usernameSession');
        if($logedUser != null){
            $data['listaEECC'] = $this->m_utils->getAllEECC();
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_bandeja_alarmas->getBandejaAlarmasMO());
            $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
            $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
            $permisos =  $this->session->userdata('permisosArbol');
            #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_CERTIFICACION_MO, ID_PERMISO_HIJO_BANDEJA_ALARMA_MO);
            $result = $this->lib_utils->getHTMLPermisos($permisos, 250, ID_PERMISO_HIJO_BANDEJA_ALARMA_MO, ID_MODULO_ADMINISTRATIVO);
            $data['opciones'] = $result['html'];
            if($result['hasPermiso'] == true){
                $this->load->view('vf_liquidacion/v_bandeja_alarmas',$data);
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
                            <th colspan="4" style="text-align: center;"></th>
                            <th colspan="2" style="text-align: center; background-color: palegoldenrod;">0 - 3 DIAS</th>
	                        <th colspan="2" style="text-align: center; background-color: darksalmon;">4 - 7 DIAS</th>
                            <th colspan="2" style="text-align: center; background-color: lightblue;"> > 7 DIAS</th>
                        </tr
                        <tr role="row">   
                            <th colspan="1" style="text-align:center">SITUACION</th>                      
                            <th colspan="1" style="text-align:center">PROYECTO</th>
                            <th colspan="1" style="text-align:center">SUB PROYECTO</th>
                            <th colspan="1" style="text-align:center">PEP</th>                            
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
                            <th>'.$row->situacion.'</th>  
                            <th>'.$row->proyectoDesc.'</th>
                            <th>'.$row->subProyectoDesc.'</th>
                            <th>'.$row->pep1.'</th>                                                      
							<td style="text-align:center"><a style="color:blue" data-estado="'.$row->estado.'" data-rango="1" data-pep="'.$row->pep1.'" data-subp="'.$row->idSubProyecto.'" onclick="getDetalle(this)">'.(($row->hasta3 == null) ? 0 : $row->hasta3).'</a></td>
							<td style="text-align:right">'.number_format($row->total_hasta3, 2, '.', ',').'</td>
							<td style="text-align:center"><a style="color:blue" data-estado="'.$row->estado.'" data-rango="2" data-pep="'.$row->pep1.'" data-subp="'.$row->idSubProyecto.'" onclick="getDetalle(this)">'.(($row->hasta7 == null) ? 0 : $row->hasta7).'</a></td>
							<td style="text-align:right">'.number_format($row->total_hasta7, 2, '.', ',').'</td>
							<td style="text-align:center"><a style="color:blue" data-estado="'.$row->estado.'" data-rango="3" data-pep="'.$row->pep1.'" data-subp="'.$row->idSubProyecto.'" onclick="getDetalle(this)">'.(($row->todo  == null) ? 0 : $row->todo).'</a></td>
						    <td style="text-align:right">'.number_format($row->total_todo, 2, '.', ',').'</td>
						</tr>';
        }
        $html .='</tbody>
                </table>';
        
        return utf8_decode($html);
    }
    
    public function getTableDetalleAlarPTRS(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            
            $pep    = $this->input->post('pep');
            $estado   = $this->input->post('estado');
            $subpro   = $this->input->post('subpro');
            $rango  = $this->input->post('rango');
            $data['pep'] = $pep;
            $datos_tabla = null;
            if($estado == 4){
                $datos_tabla = $this->m_bandeja_alarmas->getPtrsNoAsociadas($rango);
            }else{
                $datos_tabla = $this->m_bandeja_alarmas->getPtrsCertificacionMO($pep, $estado, $subpro,$rango);
            }
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaPTRS($datos_tabla);
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
                            <th style="text-align:center">PEP</th>
                            <th style="text-align:center">ITEMPLAN</th>
                            <th style="text-align:center">EECC</th>
                            <th style="text-align:center">PTR</th>
                            <th style="text-align:center">MONTO</th>
                            <th style="text-align:center">SUBPROYECTO</th>
                            <th style="text-align:center">AREA</th>
                            <th style="text-align:center">FECHA</th>
                        </tr>
                    </thead>
    
                    <tbody>';
    
        foreach($listaPTR->result() as $row){
    
            $html .=' <tr>
                            <td>'.$row->pep1.'</td>
                            <td>'.$row->itemplan.'</td>
                            <td>'.$row->empresaColabDesc.'</td>    
                            <td>'.$row->ptr.'</td>                                
							<td style="text-align:right">'.(($row->monto_mo == null) ? 0.00 : number_format($row->monto_mo, 2, '.', ',')).'</td>
						    <th>'.$row->subProyectoDesc.'</td>
						    <td>'.$row->areaDesc.'</td>
						    <td>'.$row->fecha.'</td>
						</tr>';
        }
        $html .='</tbody>
                </table>';
    
        return utf8_decode($html);
    }    
    
    public function makeCSVCertificacionAlarmMO(){
        $data['error']= EXIT_ERROR;
        $data['msj'] = null;
        try{
            //cabeceras para descarga
            header('Content-Type: application/octet-stream');
            header("Content-Transfer-Encoding: Binary");
            header("Content-disposition: attachment; filename=\"Detalle_Alarmas_MO.csv\"");
            //preparar el wrapper de salida
            $outputBuffer = fopen("php://output", 'w');
            $detalleplan = $this->m_bandeja_alarmas->getDetalleCertificacionAlarmMO();
            if(count($detalleplan->result()) > 0){
                fputcsv($outputBuffer,  explode('\t',"SITUACION"."\t"."PROYECTO"."\t"."SUBPROYECTO"."\t"."EECC"."\t"."AREA"."\t"."PEP"."\t"."ITEMPLAN"."\t"."PTR"."\t"."MONTO"."\t"."FECHA"));
                foreach ($detalleplan->result() as $row){
                    fputcsv($outputBuffer, explode('\t', utf8_decode($row->situacion."\t". $row->proyectoDesc."\t". $row->subProyectoDesc."\t". $row->empresaColabDesc."\t".$row->areaDesc."\t". $row->pep1."\t".$row->itemplan."\t".
                        $row->ptr."\t". number_format($row->monto_mo, 2, '.', ',')."\t". $row->fecha)));
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
    
    function filtrarTabla(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
           
            $eecc = $this->input->post('eecc');         
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_bandeja_certificacion->getBandejaCertificacionMO($eecc));
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
}