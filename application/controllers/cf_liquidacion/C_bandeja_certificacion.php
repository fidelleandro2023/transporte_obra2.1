<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_bandeja_certificacion extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_liquidacion/m_bandeja_certificacion');       
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }
    
    public function index(){
        $logedUser = $this->session->userdata('usernameSession');
        if($logedUser != null){
            $data['listaEECC'] = $this->m_utils->getAllEECC();
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_bandeja_certificacion->getBandejaCertificacionMO(''));
            $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
            $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
            $permisos =  $this->session->userdata('permisosArbol');
            #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_CERTIFICACION_MO, ID_PERMISO_HIJO_BANDEJA_CERTI_MO);
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_CERTIFICACION_MO, ID_PERMISO_HIJO_BANDEJA_CERTI_MO, ID_MODULO_ADMINISTRATIVO);
            $data['opciones'] = $result['html'];
            if($result['hasPermiso'] == true){
                $this->load->view('vf_liquidacion/v_bandeja_certificacion',$data);
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
                            <th colspan="3" style="text-align: center;"></th>
                            <th colspan="2" style="text-align: center; background-color: palegoldenrod;">0 - 3 DIAS</th>
	                        <th colspan="2" style="text-align: center; background-color: darksalmon;">4 - 7 DIAS</th>
                            <th colspan="2" style="text-align: center; background-color: lightblue;"> > 7 DIAS</th>
                        </tr
                        <tr role="row">
                            <th colspan="1">PROYECTO</th>
                            <th colspan="1">SUBPROYECTO</th>
                            <th colspan="1">PEP</th>
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
                            <th>'.$row->proyectoDesc.'</th>
                            <th>'.$row->subProyectoDesc.'</th>
                            <th>'.$row->pep1.'</th>
							<td style="text-align:center"><a style="color:blue" data-rango="1" data-pep="'.$row->pep1.'" data-subp="'.$row->idSubProyecto.'" onclick="getDetalle(this)">'.(($row->hasta3 == null) ? 0 : $row->hasta3).'</a></td>
							<td style="text-align:right">'.number_format($row->total_hasta3, 2, '.', ',').'</td>
							<td style="text-align:center"><a style="color:blue" data-rango="2" data-pep="'.$row->pep1.'" data-subp="'.$row->idSubProyecto.'" onclick="getDetalle(this)">'.(($row->hasta7 == null) ? 0 : $row->hasta7).'</a></td>
							<td style="text-align:right">'.number_format($row->total_hasta7, 2, '.', ',').'</td>
							<td style="text-align:center"><a style="color:blue" data-rango="3" data-pep="'.$row->pep1.'" data-subp="'.$row->idSubProyecto.'" onclick="getDetalle(this)">'.(($row->todo  == null) ? 0 : $row->todo).'</a></td>
						    <td style="text-align:right">'.number_format($row->total_todo, 2, '.', ',').'</td>
						</tr>';
        }
        $html .='</tbody>
                </table>';
        
        return utf8_decode($html);
    }
    
    public function getTableDetallePTRS(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            
            $pep    = $this->input->post('pep');
            $eecc   = $this->input->post('eecc');
            $subPro = $this->input->post('subp');
            $rango  = $this->input->post('rango');
            $data['pep'] = $pep;
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaPTRS($this->m_bandeja_certificacion->getPtrsCertificacionMO($pep, $eecc, $subPro, $rango));
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function makeHTLMTablaPTRS($listaPTR){
    
        $html = '<table id="data-table2" class="table table-bordered" style="font-size: 10px;">
                    <thead class="thead-default">
                      
                        <tr>
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
    
    public function makeCSVCertificacionMO(){
        $data['error']= EXIT_ERROR;
        $data['msj'] = null;
        try{
            //cabeceras para descarga
            header('Content-Type: application/octet-stream');
            header("Content-Transfer-Encoding: Binary");
            header("Content-disposition: attachment; filename=\"Detalle_Certificacion_MO.csv\"");
            //preparar el wrapper de salida
            $outputBuffer = fopen("php://output", 'w');
            $detalleplan = $this->m_bandeja_certificacion->getDetalleCertificacionMO();
            if(count($detalleplan->result()) > 0){
                fputcsv($outputBuffer,  explode('\t',"PROYECTO"."\t"."SUBPROYECTO"."\t"."EECC"."\t"."AREA"."\t"."PEP"."\t"."ITEMPLAN"."\t"."PTR"."\t"."MONTO"."\t"."FECHA"));
                foreach ($detalleplan->result() as $row){
                    fputcsv($outputBuffer, explode('\t', utf8_decode($row->proyectoDesc."\t". $row->subProyectoDesc."\t". $row->empresaColabDesc."\t".$row->areaDesc."\t". $row->pep1."\t".$row->itemplan."\t".
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
            $fechaValidacion = $this->input->post('fechaValidacion');  
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_bandeja_certificacion->getBandejaCertificacionMO($eecc,$fechaValidacion));
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function liquidarHojaGes(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
             
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;
            if($idUsuario   !=     null){
                $hoja = $this->input->post('hoja');
                
                $pep    = $this->input->post('pep');
                $eecc   = $this->input->post('eecc');
                $subPro = $this->input->post('subp');
                $rango  = $this->input->post('rango');
                $listaPtr = $this->m_bandeja_certificacion->getPtrsCertificacionMO($pep, $eecc, $subPro, $rango);
                $listaPTR = array();
                foreach($listaPtr->result() as $row){
                    $dato = array( 'ptr'            => $row->ptr,
                                    'estado_validado' => 1,
                                    'hoja_gestion'  => $hoja,
                                    'fecha_valida'  => date("Y-m-d H:m:i"),
                                    'usuario'       =>  $this->session->userdata('userSession')                  
                    );
                    array_push($listaPTR, $dato);
                }
                
                $listaPTRToCert = array();
                $listaPTRToLog = array();
                /*
                $litaPtrToUpdateCerti = $this->m_bandeja_certificacion->getPtrsCertificacionMOToCerticate($pep, $eecc, $subPro, $rango);
                
                foreach($listaPtr->result() as $row){
                     $dataLogPO = array(
                        'codigo_po'         =>  $row->ptr,
                        'itemplan'          =>  $row->itemplan,
                        'idUsuario'         =>  $idUsuario,
                        'fecha_registro'    =>  $this->fechaActual(),
                        'idPoestado'        =>  PO_CERTIFICADO,
                        'controlador'       =>  'certificacion mo'
                    );                    
                  
                    array_push($listaPTRToLog, $dataLogPO);
                    
                    $dataUpdate = array(
                        'codigo_po'     =>  $row->ptr,
                        'estado_po'     => PO_CERTIFICADO
                    );
                    array_push($listaPTRToCert, $dataUpdate);
                }
                */
                $data = $this->m_bandeja_certificacion->liquidarPtrCertificacion($listaPTR, $listaPTRToLog, $listaPTRToCert);
                
                $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_bandeja_certificacion->getBandejaCertificacionMO($eecc));
                $data['error']    = EXIT_SUCCESS;
            }else{
                throw new Exception('Su sesion expiro, porfavor vuelva a logearse.');
            }
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
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