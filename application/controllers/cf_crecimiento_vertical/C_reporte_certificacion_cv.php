<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_reporte_certificacion_cv extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_crecimiento_vertical/m_reporte_certificacion_cv');       
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }
    
    public function index(){
        $logedUser = $this->session->userdata('usernameSession');
        if($logedUser != null){
            $data['listaEECC'] = $this->m_utils->getAllEECC();
            
           // $data['contenido'] = '';
                        
            $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
            $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
            $permisos =  $this->session->userdata('permisosArbol');
            #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_CV, ID_PERMISO_HIJO_REPORTE_CERTI_CV);
            $result = $this->lib_utils->getHTMLPermisos($permisos, 248, ID_PERMISO_HIJO_REPORTE_CERTI_CV, ID_MODULO_ADMINISTRATIVO);
            $data['opciones'] = $result['html'];
            if($result['hasPermiso'] == true){
                $this->load->view('vf_crecimiento_vertical/v_reporte_certificacion_cv',$data);
            }else{
                redirect('login','refresh');
            }
        }else{
            redirect('login','refresh');
        }
    }
    
   
    public function makeHTLMTablaBandejaAprobMo($eecc, $fechaInicio, $fechaFin, $isLima){
        if($eecc == ID_EECC_CAMPERU){
            $contrata = 'CAMPERU';
        }else if($eecc == ID_EECC_QUANTA){
            $contrata = 'QUANTA';
        }
        
        if($fechaInicio!=''){
            $fec_ini    =  DateTime::createFromFormat('Y-m-d', $fechaInicio)->format('d/m/Y');
        }
        if($fechaFin!=''){
            $fec_fin   =  DateTime::createFromFormat('Y-m-d', $fechaFin)->format('d/m/Y');
        }else{
            $fec_fin   =  date('d/m/Y');
        }
        $cantidadSubte = $this->m_reporte_certificacion_cv->cantidadItemPlan($eecc, $fechaInicio, $fechaFin, $isLima, 1);//SUBTERRANEO
        $html = '<div style="text-align: center;">
                        <h5>'.$contrata.' - '.(($isLima) ? 'LIMA' :'PROVINCIA').' '.(($fechaInicio!='') ? (($fechaFin!='') ? '- DEL '.$fec_ini.' AL '.$fec_fin : '- DEL '.$fec_ini.' AL '.$fec_fin): '').'</h5>
                </div><br>
             <div id="contTablaSubte" class="table-responsive">
                <table style="width:80%; margin: 0 auto;" id="tabla_subte">
                    <thead class="thead-default">
                        <tr role="row">   
                            <th style="text-align: left;" colspan="1">SUBTERRANEO</th>
                            <th style="text-align: center;" colspan="3">CANTIDAD IP: '.$cantidadSubte.'</th>
                        </tr>
                        <tr role="row">
                            <th colspan="1" style="text-align: center;">PARTIDAS</th>
                            <th colspan="1" style="text-align: center;">MONTO</th>
                            <th colspan="1" style="text-align: center;">CANTIDAD</th>
                            <th colspan="1" style="text-align: center;">TOTAL</th>
                           
                        </tr>
                    </thead>
                    
                    <tbody>';
        $total_subte = 0;
        $listaPTR = $this->m_reporte_certificacion_cv->getReporteCertificacion($eecc, $fechaInicio, $fechaFin, $isLima,1);//SUBTERRANEO
        foreach($listaPTR->result() as $row){            
            $html .=' <tr>
							<td style="text-align: left;">'.utf8_decode($row->descripcion).'</td>
							<td style="text-align: right;">'.number_format($row->monto, 2, '.', ',').'</td>
							<td style="text-align: center;">'.$row->cantidad.'</td>
							<td style="text-align: right;">'.number_format($row->total, 2, '.', ',').'</td>
						
						</tr>';
            $total_subte = ($total_subte + $row->total);
        }
        
        $html .=' <tr>
							<td style="text-align: left;"></td>
							<td style="text-align: right;"></td>
							<td style="text-align: center;">TOTAL</td>
							<td style="text-align: right;">'.number_format($total_subte, 2, '.', ',').'</td>
        
						</tr>
				    </tbody>
                </table>
            </div>
		    <br><br><br>';
        
        $cantidadAereo = $this->m_reporte_certificacion_cv->cantidadItemPlan($eecc, $fechaInicio, $fechaFin, $isLima, 2);//AEREO
         $html .='  <div id="contTablaAereo" class="table-responsive">
                <table style="width:80%; margin: 0 auto;" id="tabla_aereo">
                    <thead class="thead-default">
                        <tr role="row">   
                            <th style="text-align: left;" colspan="1">AEREO</th>
                            <th style="text-align: center;" colspan="3">CANTIDAD IP: '.$cantidadAereo.'</th>
                        </tr>
                        <tr role="row">
                            <th colspan="1" style="text-align: center;">PARTIDAS</th>
                            <th colspan="1" style="text-align: center;">MONTO</th>
                            <th colspan="1" style="text-align: center;">CANTIDAD</th>
                            <th colspan="1" style="text-align: center;">TOTAL</th>
                           
                        </tr>
                    </thead>
                    
                    <tbody>';
        $total_aereo = 0;
        $listaPTRA = $this->m_reporte_certificacion_cv->getReporteCertificacion($eecc, $fechaInicio, $fechaFin, $isLima,2);//AEREO
        foreach($listaPTRA->result() as $row){            
            $html .=' <tr>
							<td style="text-align: left;">'.utf8_decode($row->descripcion).'</td>
							<td style="text-align: right;">'.number_format($row->monto, 2, '.', ',').'</td>
							<td style="text-align: center;">'.$row->cantidad.'</td>
							<td style="text-align: right;">'.number_format($row->total, 2, '.', ',').'</td>
						
						</tr>';
            $total_aereo = ($total_aereo + $row->total);
        }
        
        $html .=' <tr>
							<td style="text-align: left;"></td>
							<td style="text-align: right;"></td>
							<td style="text-align: center;">TOTAL</td>
							<td style="text-align: right;">'.number_format($total_aereo, 2, '.', ',').'</td>
        
						</tr>';
        
        $html .='</tbody>
                </table>
            </div>
            <br><br><br>';//nuevo oc
      $cantidadOC = $this->m_reporte_certificacion_cv->cantidadItemPlan($eecc, $fechaInicio, $fechaFin, $isLima, 3);//OC        
      $html .='<div id="contTablaOC" class="table-responsive">
                <table style="width:80%; margin: 0 auto;" id="tabla_aereo">
                    <thead class="thead-default">
                        <tr role="row">   
                            <th style="text-align: left;" colspan="1">OBRA CIVIL</th>
                            <th style="text-align: center;" colspan="3">CANTIDAD IP: '.$cantidadOC.'</th>
                        </tr>
                        <tr role="row">
                            <th colspan="1" style="text-align: center;">PARTIDAS</th>
                            <th colspan="1" style="text-align: center;">MONTO</th>
                            <th colspan="1" style="text-align: center;">CANTIDAD</th>
                            <th colspan="1" style="text-align: center;">TOTAL</th>
                           
                        </tr>
                    </thead>
                    
                    <tbody>';
        $total_oc = 0;
        $listaPTROC = $this->m_reporte_certificacion_cv->getReporteCertificacion($eecc, $fechaInicio, $fechaFin, $isLima,3);//OC
        foreach($listaPTROC->result() as $row){            
            $html .=' <tr>
							<td style="text-align: left;">'.utf8_decode($row->descripcion).'</td>
							<td style="text-align: right;">'.number_format($row->monto, 2, '.', ',').'</td>
							<td style="text-align: center;">'.$row->cantidad.'</td>
							<td style="text-align: right;">'.number_format($row->total, 2, '.', ',').'</td>
						
						</tr>';
            $total_oc = ($total_oc + $row->total);
        }
        
        $html .=' <tr>
							<td style="text-align: left;"></td>
							<td style="text-align: right;"></td>
							<td style="text-align: center;">TOTAL</td>
							<td style="text-align: right;">'.number_format($total_oc, 2, '.', ',').'</td>
        
						</tr>';
        
        $html .='</tbody>
                </table>
            </div>';
                //total de las sumas                
        $html .='<br><br>
            <div id="contTablaTotal" class="table-responsive">
                <table style="width:80%; margin: 0 auto;" id="tabla_total">
                    <thead class="thead-default">                      
                        <tr role="row">
                            <th style="text-align: center;width:30%"></th>
                            <th style="text-align: center;width:30%"></th>
                            <th style="text-align: RIGHT;width:10%">TOTAL S + A + OC</th>
                            <th style="text-align: RIGHT;width:10%">'.number_format(($total_subte + $total_aereo + $total_oc), 2, '.', ',').'</th>                           
                        </tr>
                    </thead>
                        
                </table>
            </div>';
        
        return utf8_decode($html);
    }
    
    public function filtrarReporte(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            
            $eecc           = $this->input->post('eecc');
            $fechaInicio    = $this->input->post('fechaInicio');
            $fechaFin       = $this->input->post('fechaFin');
            $region       = $this->input->post('region');            
            $isLima = (($region == 1) ? true : false);
            $html   = $this->makeHTLMTablaBandejaAprobMo($eecc, $fechaInicio, $fechaFin, $isLima);
            $data['contenido'] = $html;
            $this->session->set_userdata('filtrosPDF', array( 'eecc'       => $eecc,
                                                'fec_inicio' => $fechaInicio,
                                                'fec_fin'    => $fechaFin,
                                                'region'     => $isLima));
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
function makePDFReporteCerti(){
        $filtrosPDF = $this->session->userdata('filtrosPDF');        
        $contenidoHtml = $this->makeHTLMToPDFReport($filtrosPDF['eecc'], $filtrosPDF['fec_inicio'], $filtrosPDF['fec_fin'], $filtrosPDF['region']);
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
        
        $tbl ='<img style="width: 100px; heigth:40px" src="'.base_url().'public/img/logo/tdp.png">'.
            $contenidoHtml;
            
        $pdf->writeHTML(utf8_encode($tbl), true, false, false, false, '');
        //ob_clean();
        $pdf->Output('example.pdf', 'I');
        return true;
    }
    
    public function makeHTLMToPDFReport($eecc, $fechaInicio, $fechaFin, $isLima){
        if($eecc == ID_EECC_CAMPERU){
            $contrata = 'CAMPERU';
        }else if($eecc == ID_EECC_QUANTA){
            $contrata = 'QUANTA';
        }
    
        if($fechaInicio!=''){
            $fec_ini    =  DateTime::createFromFormat('Y-m-d', $fechaInicio)->format('d/m/Y');
        }
        if($fechaFin!=''){
            $fec_fin   =  DateTime::createFromFormat('Y-m-d', $fechaFin)->format('d/m/Y');
        }else{
            $fec_fin   =  date('d/m/Y');
        }
        $cantidadSubte = $this->m_reporte_certificacion_cv->cantidadItemPlan($eecc, $fechaInicio, $fechaFin, $isLima, 1);//SUBTERRANEO
        $html = '<div style="text-align: center;">
                        <h3>'.$contrata.' - '.(($isLima) ? 'LIMA' :'PROVINCIA').' '.(($fechaInicio!='') ? (($fechaFin!='') ? '- DEL '.$fec_ini.' AL '.$fec_fin : '- DEL '.$fec_ini.' AL '.$fec_fin): '').'</h3>
                </div><br>
             <div id="contTablaSubte" class="table-responsive">
                <table style="width:100%; margin: 0 auto;" id="tabla_subte">
                    <thead class="thead-default">
                        <tr role="row">
                            <th style="width:70%;text-align: left;" colspan="1"><b>SUBTERRANEO</b></th>
                            <th style="width:30%;text-align: center;" colspan="3"><b>CANTIDAD IP: '.$cantidadSubte.'</b></th>
                        </tr>
                        <tr role="row">
                            <th colspan="1" style="width:70%; text-align: center;">PARTIDAS</th>
                            <th colspan="1" style="width:10%; text-align: center;">MONTO</th>
                            <th colspan="1" style="width:10%; text-align: center;">CANTIDAD</th>
                            <th colspan="1" style="width:10%; text-align: center;">TOTAL</th>
              
                        </tr>
                    </thead>
    
                    <tbody>';
        $total_subte = 0;
        $listaPTR = $this->m_reporte_certificacion_cv->getReporteCertificacion($eecc, $fechaInicio, $fechaFin, $isLima,1);//SUBTERRANEO
        foreach($listaPTR->result() as $row){
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
        $cantidadAereo = $this->m_reporte_certificacion_cv->cantidadItemPlan($eecc, $fechaInicio, $fechaFin, $isLima, 2);//AEREO
        $html .='</tbody>
                </table>
            </div><br><br><br>
            <div id="contTablaAereo" class="table-responsive">
                <table style="width:100%; margin: 0 auto;" id="tabla_aereo">
                    <thead class="thead-default">
                        <tr role="row">
                            <th style="width:70%;text-align: left;" colspan="1"><b>AEREO</b></th>
                            <th style="width:30%;text-align: center;" colspan="3"><b>CANTIDAD IP: '.$cantidadAereo.'</b></th>
                        </tr>
                        <tr role="row">
                            <th colspan="1" style="width:70%;text-align: center;">PARTIDAS</th>
                            <th colspan="1" style="width:10%;text-align: center;">MONTO</th>
                            <th colspan="1" style="width:10%;text-align: center;">CANTIDAD</th>
                            <th colspan="1" style="width:10%;text-align: center;">TOTAL</th>
              
                        </tr>
                    </thead>
    
                    <tbody>';
        $total_aereo = 0;
        $listaPTRA = $this->m_reporte_certificacion_cv->getReporteCertificacion($eecc, $fechaInicio, $fechaFin, $isLima,2);//AEREO
        foreach($listaPTRA->result() as $row){
            $html .=' <tr>
							<td style="width:70%; text-align: left;">'.utf8_decode($row->descripcion).'</td>
							<td style="width:10%; text-align: right;">'.number_format($row->monto, 2, '.', ',').'</td>
							<td style="width:10%; text-align: center;">'.$row->cantidad.'</td>
							<td style="width:10%; text-align: right;">'.number_format($row->total, 2, '.', ',').'</td>
    
						</tr>';
            $total_aereo = ($total_aereo + $row->total);
        }
    
        $html .=' <tr>
							<td style="width:70%;text-align: left;"></td>
							<td style="width:10%;text-align: right;"></td>
							<td style="width:10%;text-align: center;"><b>TOTAL:</b></td>
							<td style="width:10%;text-align: right;">'.number_format($total_aereo, 2, '.', ',').'</td>
    
						</tr>';
    
        $html .='</tbody>
                </table>
            </div>
            <br><br>
            <div id="contTablaTotal" class="table-responsive">
                <table style="width:100%; margin: 0 auto;" id="tabla_total">
                    <thead class="thead-default">
                        <tr role="row">
                            <th style="text-align: center;width:60%"></th>
                            <th style="text-align: center;width:10%"></th>
                            <th style="text-align: RIGHT;width:20%"><b>TOTAL S + A:</b></th>
                            <th style="text-align: RIGHT;width:10%">'.number_format(($total_subte + $total_aereo), 2, '.', ',').'</th>
                        </tr>
                    </thead>
    
                </table>
            </div>';
    
        return utf8_decode($html);
    }
}