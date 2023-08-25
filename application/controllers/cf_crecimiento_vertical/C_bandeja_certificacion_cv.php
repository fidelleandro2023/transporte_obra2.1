<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_bandeja_certificacion_cv extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_crecimiento_vertical/m_bandeja_certificacion_cv');       
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }
    
    public function index(){
        $logedUser = $this->session->userdata('usernameSession');
        if($logedUser != null){
            $data['listaEECC'] = $this->m_utils->getAllEECC();
            
           // $data['listaSubProy'] = $this->m_utils->getAllSubProyecto();
            $data['listafase'] = $this->m_utils->getAllFase();
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_bandeja_certificacion_cv->getAllPreCertificacionCV(null, null, null, null));
            $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
            $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
            $permisos =  $this->session->userdata('permisosArbol');
            #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_CV, ID_PERMISO_HIJO_BANDEJA_CERT_CV);
            $result = $this->lib_utils->getHTMLPermisos($permisos, 248, ID_PERMISO_HIJO_BANDEJA_CERT_CV, ID_MODULO_ADMINISTRATIVO);
            $data['opciones'] = $result['html'];
            if($result['hasPermiso'] == true){
                $this->load->view('vf_crecimiento_vertical/v_bandeja_certificacion_cv',$data);
            }else{
                redirect('login','refresh');
            }
        }else{
            redirect('login','refresh');
        }
    }
    /*
    public function makeHTLMTablaBandejaAprobMo($listaPTR){
        
        $html = '<table style="font-size: 10px;" id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>Itemplan</th>
                            <th>EECC</th>
                            <th>Fecha Registro</th>
                            <th>TIpo</th>
                            <th>CTO</th>
                            <th>Dptos</th>
                            <th>Descripcion</th>
                            <th>Monto</th>
                            <th>Cantidad</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    
                    <tbody>';
        
        foreach($listaPTR->result() as $row){            
            $html .=' <tr>
							<td><a href="makePDFRICV?itm='.$row->itemplan.'" target="_blank">'.$row->itemplan.'</a></td>
							<td>'.$row->empresaColabDesc.'</td>
							<td>'.$row->fecha_registro.'</td>
							<td>'.$row->tipo_partida.'</td>
							<td>'.$row->instalacion_cto.'</td>
                            <td>'.$row->depa.'</td>
                            <td>'.$row->descripcion.'</td>
                            <td>'.$row->monto.'</td>
                            <td>'.$row->cantidad.'</td>
                            <td>'.$row->total.'</td>
						</tr>';
        }
        $html .='</tbody>
                </table>';
        
        return utf8_decode($html);
    }
    */
    
    public function makeHTLMTablaBandejaAprobMo($listaPTR){
    
        $html = '<table style="font-size: 10px;" id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>Itemplan</th>
                            <th>EECC</th>
                            <th>FASE</th>
                            <th>Fecha Validacion</th>
                            <th>TIpo</th>
                            <th>CTO</th>
                            <th>CAMARA</th>
                            <th>Dptos</th>
                            <th style="text-align: center;">Costo MO</th>
                            <th style="text-align: center;">Costo MAT</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
    
                    <tbody>';
    
        foreach($listaPTR->result() as $row){
            $html .=' <tr>
							<td><a href="makePDFRICV?itm='.$row->itemplan.'&&iE='.$row->idEstacion.'" target="_blank">'.$row->itemplan.'</a></td>
							<td>'.$row->empresaColabDesc.'</td>
							<td>'.$row->faseDesc.'</td>
							<td>'.$row->fecha_registro.'</td>
							<td>'.$row->tipo_partida.'</td>
							<td>'.$row->instalacion_cto.'</td>
							<td>'.$row->camara.'</td>
                            <td>'.$row->depa.'</td>                            
                            <td style="text-align: right;">'.number_format($row->total_mo, 2, '.', ',').'</td>
                            <td style="text-align: right;">'.number_format($row->total_mat, 2, '.', ',').'</td>
                            <td>'.$row->validado.'</td>
						</tr>';
        }
        $html .='</tbody>
                </table>';
    
        return utf8_decode($html);
    }
    
    public function filtrarTabla(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            
            $eecc           = $this->input->post('eecc');
            $fechaInicio    = $this->input->post('fechaInicio');
            $fechaFin       = $this->input->post('fechaFin');
            $itemplan       = $this->input->post('itemplan');            
            $idFase         = $this->input->post('idFase'); 
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_bandeja_certificacion_cv->getAllPreCertificacionCV($eecc, $fechaInicio, $fechaFin, $itemplan,$idFase));
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function makePDFCertiItem(){
        $item = (isset($_GET['itm']) ? $_GET['itm'] : '');
		$idEstacion = (isset($_GET['iE']) ? $_GET['iE'] : '');
        $contenidoHtml = $this->makeHTLMToPDFReport($item, $idEstacion);      
        
        $this->load->library('Pdf');
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetTitle('Pdf Example');
        $pdf->SetHeaderMargin(30);
        //$pdf->SetTopMargin(20);
        $pdf->setFooterMargin(20);
        $pdf->SetAutoPageBreak(true);
        $pdf->SetAuthor('Author');
        $pdf->SetDisplayMode('real', 'default');
        //$pdf->Write(5, 'CodeIgniter TCPDF Integration');
        // set font
        $pdf->SetFont('helvetica', 'B', 20);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        // add a page
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 8);
        
        #$tbl ='<img style="width: 100px; heigth:40px" src="'.base_url().'public/img/logo/tdp.png">'.$contenidoHtml;
        
        $pdf->writeHTML(utf8_encode($contenidoHtml), true, false, false, false, '');
        //ob_clean();
        $pdf->Output('example.pdf', 'I');
        return true;
    }   
    
    public function makeHTLMToPDFReport($itemplan, $idEstacion){
        $infoItem = $this->m_bandeja_certificacion_cv->getInfoItemplanRepo($itemplan);
        //                        
        
        $html = '<div style="text-align: center;">
<h3> ITEMPLAN: '.$infoItem['itemPlan'].' '.(($idEstacion==ID_ESTACION_FO) ? 'FO' : (($idEstacion==ID_ESTACION_OC_FO) ? 'OC FO' : '')).'</h3>
<h3>'.$infoItem['empresaColabDesc'].'</h3>
                </div>


            <div  class="table-responsive">
            
                <table  style="width:100%; margin: 0 auto;">
                    <tbody>
                        
                        <tr>
                            <td style="width:40%;" ><strong>TIPO PARTIDA:</strong>&nbsp;'.$infoItem['tipoPartida'].'</td>
                            <td style="width:30%;" ><strong># DPTOS:</strong>&nbsp;'.$infoItem['depa'].'</td>
                            <td style="width:30%;"><strong>MICROCANALIZADO:</strong>&nbsp;'.$infoItem['microcanolizado'].'</td>
                            
                        </tr>
                        
                    </tbody>
                </table>

            </div>


            
            <div  class="table-responsive">
            
                <table  style="width:100%; margin: 0 auto;">
                    <tbody>                       
                        <tr>
                             <td style="width:40%;"><strong>INSTALACION CTO:</strong>&nbsp;'.$infoItem['instalacion_cto'].'</td>                         
                            <td style="width:30%;"><strong>CAMARA:</strong>&nbsp;'.$infoItem['camara'].'</td>
                            <td style="width:30%;"><strong>CABLEADO FO:</strong>&nbsp;'.$infoItem['total_mat'].'</td>
                        </tr>
                    </tbody>
                </table>

            </div>
            

<br>
             <div id="contTablaSubte" class="table-responsive">
                <table border="0" style="width:100%; margin: 0 auto;" id="tabla_subte">
                    <thead class="thead-default">
                        <!--<tr role="row">
                            <th style="width:70%;text-align: left;" colspan="1"><b>'.$infoItem['tipoPartida'].'</b></th>
                            <th style="width:30%;text-align: center;" colspan="3"><b></b></th>
                        </tr>-->
                        <tr role="row">
                            <th colspan="1" style="width:70%; text-align: center;">PARTIDAS</th>
                            <th colspan="1" style="width:10%; text-align: center;">MONTO</th>
                            <th colspan="1" style="width:10%; text-align: center;">CANTIDAD</th>
                            <th colspan="1" style="width:10%; text-align: center;">TOTAL</th>
                                
                        </tr>
                    </thead>
                                
                    <tbody>';
        $total_subte = 0;
        $listaDatos = $this->m_bandeja_certificacion_cv->getDataItemplanCertificacion($itemplan, $idEstacion);        
        foreach($listaDatos->result() as $row){
            $html .=' <tr>
							<td style="width:70%; text-align: left;">'.utf8_decode($row->descripcion).'</td>
							<td style="width:10%;text-align: right;">'.number_format($row->monto, 2, '.', ',').'</td>
							<td style="width:10%;text-align: center;">'.$row->cantidad.'</td>
							<td style="width:10%;text-align: right;">'.number_format($row->total, 2, '.', ',').'</td>							    
					</tr>';
            $total_subte = ($total_subte + $row->total);
        }
        
        $html .=' <tr>
							<td style="text-align: left;"></td>
							<td style="text-align: right;"></td>
							<td style="text-align: center;"><b>TOTAL:</b></td>
							<td style="text-align: right;">'.number_format($total_subte, 2, '.', ',').'</td>
							    
						</tr>';
        $html .='</tbody>
                </table>
        
            </div>';
        
        return $html;
    }
    
    public function crearCSVMaterialesCV(){
        $data['error']= EXIT_ERROR;
        $data['msj'] = null;
        try{
            //cabeceras para descarga
            header('Content-Type: application/octet-stream');
            header("Content-Transfer-Encoding: Binary");
            header("Content-disposition: attachment; filename=\"Extrator_materiales.csv\"");
            //preparar el wrapper de salida
            $outputBuffer = fopen("php://output", 'w');
            $detalleplan = $this->m_bandeja_certificacion_cv->getDataToReportCVMateriales();
            if(count($detalleplan->result()) > 0){
                fputcsv($outputBuffer,  explode('\t',"ITEMPLAN"."\t"."EECC"."\t"."FASE"."\t"."FECHA REGISTRO"."\t"."TIPO PARTIDA"."\t"."CTO"."\t"."DEPARTAMENTOS"."\t"."ID MATERIAL"."\t"."DESCRIPCION MATERIAL"."\t"."COSTO MATERIAL"."\t"."CANTIDAD"."\t"."COSTO TOTAL"));
                foreach ($detalleplan->result() as $row){
                    fputcsv($outputBuffer, explode('\t', utf8_decode($row->itemplan."\t". $row->empresaColabDesc."\t".$row->faseDesc."\t".$row->fecha_registro."\t". $row->tipo_partida."\t".$row->instalacion_cto."\t".
                        $row->depa."\t". $row->id_material."\t". $row->descrip_material."\t". $row->costo_material."\t". $row->total."\t".$row->costo_total)));
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