<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * @author CARLOS ZAVALA C.
 * 18/01/2018
 *
 */
class C_termino_ficha_tecnica extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_ficha_tecnica/m_termino_ficha_tecnica');
        $this->load->model('mf_ficha_tecnica/m_bandeja_ficha_tecnica');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');       
    }
    
	public function index()
	{  	   
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
        	   $data['listaEECC'] = $this->m_utils->getAllEECC();
        	   $data['listaZonal'] = $this->m_utils->getAllZonal();
        	   $data['listaSubProy'] = $this->m_utils->getAllSubProyecto();
        	   $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo(null);	
        	   $data['listaTrabajos'] = $this->m_termino_ficha_tecnica->getTrabajosFichaTecnica();
        	   $data['listaNivelesCali'] = $this->m_termino_ficha_tecnica->getNivelesCalibracion();
        	   //$data['optionsTipoTra'] = $this->makeHTMLOptionsChoiceTipoTrabajo($this->m_termino_ficha_tecnica->getTipoTrabajoFichaTecnica());
        	   $data['nombreUsuario'] =  $this->session->userdata('usernameSession');	
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');	               
        	   $permisos =  $this->session->userdata('permisosArbol');
        	   $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_INSPECCIONES, ID_PERMISO_HIJO_TERMINO_FICHA);
        	   $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){        	       
        	       $this->load->view('vf_ficha_tecnica/v_termino_ficha_tecnica',$data);
        	   }else{
        	       redirect('login','refresh');
	           }
	   }else{
	       redirect('login','refresh');
	   }
    }
    
    public function makeHTMLOptionsChoiceTipoTrabajo($listaTipoTrabajo){
        $html = '';
        foreach($listaTipoTrabajo->result() as $row){
            $html .= '<option value="'.$row->id.'">'.$row->descripcion.'</option>';
        }       
        return $html;
    }

    public function makeHTLMTablaBandejaAprobMo($listaPTR){  
                $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>CIO</th>
                            <th>TDP</th>
                            <th>Item Plan</th>
                            <th>Estacion</th>
                            <th>Indicador</th>
                            <th>Sub Proy</th>
                            <th>Zonal</th>
                            <th>EECC</th>
                            <th>Fec. Prevista</th>
                            <th>Estado Plan</th>
                            <th>Situacion</th>
                        </tr>
                    </thead>
                   
                    <tbody>';
                if($listaPTR!=null){						
                foreach($listaPTR->result() as $row){
                    
                    if($row->id_ficha_tecnica_base == FICHA_FO_SISEGOS_SMALLCELL_EBC){
                        $viewPDFECIO = 'makePDFASI';
                        $viewFicha = 'onclick="viewFichaEvalCIOSI(this)';
                        $btnFotos = '<a href="makePDFESI?itm='.$row->itemPlan.'&&type='.$row->id_ficha_tecnica_base.'" target="_blank"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/iconpdf.svg"></a>';
                    } else if($row->id_ficha_tecnica_base == FICHA_FO_OBRAS_PUBLICAS) {
                        $viewPDFECIO = 'openPdfCIO';
                        $viewFicha   = 'onclick="openModalOBP(this)';
                        $btnFotos    = '<a href="makePDFOBP?itm='.$row->itemPlan.'&&type='.$row->id_ficha_tecnica_base.'&&flg=2" target="_blank"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/iconpdf.svg"></a>';                        
                    } else {
                        $viewPDFECIO = 'makePDFA';
                        $viewFicha = 'onclick="viewFichaEval(this)';
                        $btnFotos = '<a href="makePDFE?itm='.$row->itemPlan.'&&type='.$row->id_ficha_tecnica_base.'" target="_blank"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/iconpdf.svg"></a>';                        
                    }
                $html .=' <tr>
                            <th>'.
                            (($row->flg_auditado != null) ? '<a href="'.$viewPDFECIO.'?itm='.$row->itemPlan.'&&type='.$row->id_ficha_tecnica_base.'" target="_blank"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/iconpdf.svg"></a> ' :  '<a style="margin-left: 10px;" data-type="'.$row->id_ficha_tecnica_base.'" data-itm ="'.$row->itemPlan.'" '.$viewFicha.'"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/editar.ico"></a>').'</th>
                            <th>'.$btnFotos.'</th>
                            <th>'.$row->itemPlan.'</th>
                            <th>'.$row->estacionDesc.'</th>
                            <th>'.$row->indicador.'</th>							
                            <th>'.$row->subProyectoDesc.'</th>
							<th>'.$row->zonalDesc.'</th>
							<th>'.$row->empresaColabDesc.'</th>							
                            <th>'.$row->fechaPrevEjec.'</th> 
                            <th>'.$row->estadoPlanDesc.'</th>
                            <th>'.$row->estado_vali.'</th>
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
            $zonal = $this->input->post('zonal');
            $situacion = $this->input->post('situacion');
            $mesEjec = $this->input->post('mes');
            $itemplan = $this->input->post('itemplan');
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_termino_ficha_tecnica->getBandejaFichaTecnicaEvaluacion($SubProy,$eecc,$zonal,$situacion,$mesEjec,$itemplan));
             $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getInfoItemFichaTecnica(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $itemplan = $this->input->post('itemplan');
            $datosItem = $this->m_termino_ficha_tecnica->getInfoItemPlanFichaTecnica($itemplan);
            $data['itemplan'] = $datosItem['itemplan'];
            $data['subpro'] = $datosItem['subProyectoDesc'];            
            $data['nodo'] = $datosItem['codigo'];            
            $data['fec_inicio'] = $datosItem['fec_inicio'];            
            $data['fec_fin'] = $datosItem['fechaEjecucion'];
            $data['troba'] = $datosItem['indicador'];
            $data['serie'] = $datosItem['serie'];            
            $data['nombreCuadri'] = '';
            $data['eecc'] = $datosItem['empresaColabDesc'];    
            $data['eecc'] = $datosItem['empresaColabDesc'];    
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
        
    function makePDFTermino(){
        $item = (isset($_GET['itm']) ? $_GET['itm'] : '');
        $type = (isset($_GET['type']) ? $_GET['type'] : '');
         
        $this->load->library('Pdf');
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetTitle('Pdf Example');
        $pdf->SetHeaderMargin(30);
        //$pdf->SetTopMargin(20);
        $pdf->setFooterMargin(20);
        $pdf->SetAutoPageBreak(true);
        $pdf->SetAuthor('Telefonica.pe');
        $pdf->SetDisplayMode('real', 'default');
        //$pdf->Write(5, 'CodeIgniter TCPDF Integration');
        // set font
        $pdf->SetFont('helvetica', 'B', 20);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        // add a page
        $pdf->AddPage('L');
        $pdf->SetFont('helvetica', '', 8);
        
        $dataItem = $this->m_termino_ficha_tecnica->getInfoItemPlanFichaTecnica($item);
        $dataFicha = $this->m_termino_ficha_tecnica->getInfoFichaTecnicaByItemplan($item, $type);
        $tbl ='<img style="width: 100px; heigth:40px" src="'.base_url().'public/img/logo/tdp.png">
            <p style="text-align: center;"><strong>CHECKLIST DE TRABAJOS EN PLANTA INTERNA</strong></p>
           
            <table style="height: 100%; width: 100%;">
            <tbody>
            <tr>
            <td style="width: 30%;"><strong>Itemplan: </strong>'.$dataItem['itemplan'].'</td>
            <td style="width: 50%;"><strong>Sub Proyecto: </strong>'.$dataItem['subProyectoDesc'].'</td>
            <td style="width: 20%;"><strong>Nodo: </strong>'.$dataItem['codigo'].'</td>
            </tr>
            </tbody>
            </table>
            <p></p>
            <table style="height: 100%; width: 100%;">
            <tbody>
            <tr>
            <td style="width: 25%;"><strong>Fecha Inicio:</strong>'.$dataItem['fec_inicio'].'</td>
            <td style="width: 25%;"><strong>Fecha Fin:</strong>'.$dataItem['fechaEjecucion'].'</td>
            <td style="width: 25%;"><strong>Troba:</strong>'.$dataItem['indicador'].'</td>
            <td style="width: 25%;"><strong>Serie:</strong>'.$dataItem['serie'].'</td>
            </tr>
            </tbody>
            </table>
            <p><span style="text-decoration: underline;"><strong>JEFE DE CUADRILLA</strong></span></p>
            <table style="height: 100%; width: 100%;">
            <tbody>
            <tr>
            <td style="width: 40%;"><strong>Nombre:</strong>'.$dataFicha['jefe_c_nombre'].'</td>
            <td style="width: 20%;"><strong>Codigo:</strong>'.$dataFicha['jefe_c_codigo'].'</td>
            <td style="width: 20%;"><strong>EECC:</strong>'.$dataItem['empresaColabDesc'].'</td>
            <td style="width: 20%;"><strong>Celular:</strong>'.$dataFicha['jefe_c_celular'].'</td>
            </tr>
            </tbody>
            </table>
            <p><span style="text-decoration: underline;"><strong>INFORMACION DE TRABAJOS REALIZADOS</strong></span></p>          
            <table style="font-size: small; height: 100%; width: 100%; margin-left: auto; margin-right: auto;" border="1">
            <tbody>
            <tr>
            <th style="width: 10%;">&nbsp;</th>
            <th style="width: 5%;text-align: center;"><strong>CANTIDAD</strong></th>
            <th style="width: 5%;text-align: center;"><strong>TIPO</strong></th>
            <th style="width: 20%;text-align: center;"><strong>OBSERVACIONES</strong></th>
            <th style="width: 5%;text-align: center;"><strong>VALIDADO</strong></th>
            <th style="width: 20%;text-align: center;"><strong>OBERVACION TDP</strong></th>
            <th style="width: 15%;text-align: center;"><strong>MOTIVO AUDITOR</strong></th>
            <th style="width: 20%;text-align: center;"><strong>OBERVACION AUDITOR</strong></th>
            </tr>';
        $listaTrabajos = $this->m_termino_ficha_tecnica->getTrabajosFichatecnicaByItemplan($item, $type);
        foreach($listaTrabajos->result() as $row){
            $tbl .='<tr>
            <th style="text-align: left; height: 40px"><strong>'.$row->descripcion.'</strong></th>
            <th style="text-align: center;">'.$row->cantidad.'</th>
            <th>'.$row->tipo_trabajo.'</th>
            <th>'.((strlen($row->observacion) > 150) ? substr($row->observacion, 0, 150).'...' : $row->observacion).'</th>
            <th>'.(($row->flg_validado == 1) ? 'SI' : 'NO').'</th>
            <th>'.((strlen($row->comentario_vali) > 150) ? substr($row->comentario_vali, 0, 150).'...' : $row->comentario_vali).'</th>
            <th>'.$row->desc_opc_aud.'</th>
            <th>'.((strlen($row->comentario_aud) > 150) ? substr($row->comentario_aud, 0, 150).'...' : $row->comentario_aud).'</th>
            </tr>';
        }
    $tbl .='</tbody>
            </table>
            <table style="height: 100%; width: 100%;">
            <tbody>
            <tr>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td style="width: 100%;"><strong>Comentario: </strong></td>
            </tr>
            <tr>
            <td>'.$dataFicha['observacion'].'</td>
            </tr>
            </tbody>
            </table>
            <p><span style="text-decoration: underline;"><strong>PTRS ASOCIADAS</strong></span></p>
            <table style="font-size: small; height: 100%; width: 100%; margin-left: auto; margin-right: auto;" border="1">';
    $tbl .='    <tbody>
                <tr style="background:#e4e4e4">
                <th style="width: 15%;text-align: center;"><strong>PTR</strong></th>
                <th style="width: 15%;text-align: center;"><strong>AREA</strong></th>
                <th style="width: 15%;text-align: center;"><strong>MONTO MATERIAL</strong></th>
                <th style="width: 15%;text-align: center;"><strong>MONTO MO</strong></th>
                <th style="width: 40%;text-align: center;"><strong>ESTADO</strong></th>
                </tr>';
        
             $listaPtr = $this->m_termino_ficha_tecnica->getPTRSByItemplan($item);
             foreach($listaPtr->result() as $row){
                 $tbl .='<tr>
                             <th style="text-align: left;"><strong>'.$row->poCod.'</strong></th>
                             <th style="text-align: left;"><strong>'.$row->areaDesc.'</strong></th>
                             <th style="text-align: left;"><strong>'.$row->valoriz_material.'</strong></th>
                             <th style="text-align: left;"><strong>'.$row->valoriz_m_o.'</strong></th>
                             <th style="text-align: left;"><strong>'.$row->est_innova.'</strong></th>
                         </tr>';
                        }            
    $tbl .='</tbody>
            </table>
            <table style="height: 100%; width: 100%;">
            <tbody>
            <tr>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td style="width: 100%;"><strong>Comentario Adicional: </strong></td>
            </tr>
            <tr>
            <td>'.$dataFicha['observacion_adicional'].'</td>
            </tr>
            </tbody>
            </table>';
        
        $pdf->writeHTML($tbl, true, false, false, false, '');
        //ob_clean();
        $pdf->Output('pdfexample.pdf', 'I');     
    }
    
    function makePDFTerminoSI(){
        $item = (isset($_GET['itm']) ? $_GET['itm'] : '');
        $type = (isset($_GET['type']) ? $_GET['type'] : '');
        
        $this->load->library('Pdf');
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetTitle('Pdf Example');
        $pdf->SetHeaderMargin(30);
        //$pdf->SetTopMargin(20);
        $pdf->setFooterMargin(20);
        $pdf->SetAutoPageBreak(true);
        $pdf->SetAuthor('Telefonica.pe');
        $pdf->SetDisplayMode('real', 'default');
        //$pdf->Write(5, 'CodeIgniter TCPDF Integration');
        // set font
        $pdf->SetFont('helvetica', 'B', 20);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        // add a page
        $pdf->AddPage('L');
        $pdf->SetFont('helvetica', '', 8);
        
        $dataItem = $this->m_termino_ficha_tecnica->getInfoItemPlanFichaTecnica($item);
        $dataFicha = $this->m_termino_ficha_tecnica->getInfoFichaTecnicaByItemplan($item, $type);
        $tbl ='<img style="width: 100px; heigth:40px" src="'.base_url().'public/img/logo/tdp.png">
            <p style="text-align: center;"><strong>CHECKLIST DE TRABAJOS EN PLANTA INTERNA</strong></p>
            
            <table style="height: 100%; width: 100%;">
            <tbody>
            <tr>
            <td style="width: 30%;"><strong>Itemplan: </strong>'.$dataItem['itemplan'].'</td>
            <td style="width: 45%;"><strong>Sub Proyecto: </strong>'.$dataItem['subProyectoDesc'].'</td>
            <td style="width: 25%;"><strong>Sisego: </strong>'.$dataItem['indicador'].'</td>
            </tr>
            </tbody>
            </table>
            <p></p>
            <table style="height: 100%; width: 100%;">
            <tbody>
            <tr>
            <td style="width: 25%;"><strong>Fecha Inicio: </strong>'.date("d/m/Y",  strtotime($dataItem['fec_inicio'])).'</td>
            <td style="width: 25%;"><strong>Fecha Fin: </strong>'.date("d/m/Y",  strtotime($dataItem['fechaPreLiquidacion'])).'</td>
            <td style="width: 25%;"><strong>Nodo: </strong>'.$dataItem['codigo'].'</td>
            <td style="width: 25%;"><strong>Serie de Troba: </strong>'.$dataItem['serie'].'</td>
            </tr>
            </tbody>
            </table>
            <p><span style="text-decoration: underline;"><strong>JEFE DE CUADRILLA</strong></span></p>
            <table style="height: 100%; width: 100%;">
            <tbody>
            <tr>
            <td style="width: 50%;"><strong>Nombre:</strong>'.$dataFicha['jefe_c_nombre'].'</td>
            <td style="width: 25%;"><strong>Codigo:</strong>'.$dataFicha['jefe_c_codigo'].'</td>
            <td style="width: 25%;"><strong>EECC:</strong>'.$dataItem['empresaColabDesc'].'</td>
            <!--<td style="width: 20%;"><strong>Celular:</strong>'.$dataFicha['jefe_c_celular'].'</td>-->
            </tr>
            </tbody>
            </table>
            <p><span style="text-decoration: underline;"><strong>INFORMACION DE TRABAJOS REALIZADOS</strong></span></p>
            <table style="font-size: small; height: 100%; width: 100%; margin-left: auto; margin-right: auto;" border="1">
            <tbody>
            <tr>
            <th style="width: 10%;">&nbsp;</th>
            <th style="width: 5%;text-align: center;"><strong>CANTIDAD</strong></th>
            <th style="width: 5%;text-align: center;"><strong>TIPO</strong></th>
            <th style="width: 20%;text-align: center;"><strong>OBSERVACIONES</strong></th>
            <th style="width: 5%;text-align: center;"><strong>VALIDADO</strong></th>
            <th style="width: 20%;text-align: center;"><strong>OBERVACION TDP</strong></th>
            <th style="width: 15%;text-align: center;"><strong>MOTIVO AUDITOR</strong></th>
            <th style="width: 20%;text-align: center;"><strong>OBERVACION AUDITOR</strong></th>
            </tr>';
        $listaTrabajos = $this->m_termino_ficha_tecnica->getTrabajosFichatecnicaByItemplan($item, $type);
        foreach($listaTrabajos->result() as $row){
            $tbl .='<tr>
            <th style="text-align: left; height: 40px"><strong>'.$row->descripcion.'</strong></th>
            <th style="text-align: center;">'.$row->cantidad.'</th>
            <th>'.$row->tipo_trabajo.'</th>
            <th>'.((strlen($row->observacion) > 150) ? substr($row->observacion, 0, 150).'...' : $row->observacion).'</th>
            <th>'.(($row->flg_validado == 1) ? 'SI' : 'NO').'</th>
            <th>'.((strlen($row->comentario_vali) > 150) ? substr($row->comentario_vali, 0, 150).'...' : $row->comentario_vali).'</th>
            <th>'.$row->desc_opc_aud.'</th>
            <th>'.((strlen($row->comentario_aud) > 150) ? substr($row->comentario_aud, 0, 150).'...' : $row->comentario_aud).'</th>
            </tr>';
        }
        $tbl .='</tbody>
            </table>
            <table style="height: 100%; width: 100%;">
            <tbody>
            <tr>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td style="width: 100%;"><strong>Comentario: </strong></td>
            </tr>
            <tr>
            <td>'.$dataFicha['observacion'].'</td>
            </tr>
            </tbody>
            </table>';
        
        $pdf->writeHTML($tbl, true, false, false, false, '');
        $pdf->AddPage('L');
        $html2 = '<p><span style="text-decoration: underline;"><strong>PTRS ASOCIADAS</strong></span></p>
            <table style="font-size: small; height: 100%; width: 100%; margin-left: auto; margin-right: auto;" border="1">';
        $html2.='    <tbody>
                <tr style="background:#e4e4e4">
                <th style="width: 15%;text-align: center;"><strong>PTR</strong></th>
                <th style="width: 15%;text-align: center;"><strong>AREA</strong></th>
                <th style="width: 15%;text-align: center;"><strong>MONTO MATERIAL</strong></th>
                <th style="width: 15%;text-align: center;"><strong>MONTO MO</strong></th>
                <th style="width: 40%;text-align: center;"><strong>ESTADO</strong></th>
                </tr>';
        
        $listaPtr = $this->m_termino_ficha_tecnica->getPTRSByItemplan($item);
        foreach($listaPtr->result() as $row){
            $html2.='<tr>
                             <th style="text-align: left;"><strong>'.$row->poCod.'</strong></th>
                             <th style="text-align: left;"><strong>'.$row->areaDesc.'</strong></th>
                             <th style="text-align: left;"><strong>'.$row->valoriz_material.'</strong></th>
                             <th style="text-align: left;"><strong>'.$row->valoriz_m_o.'</strong></th>
                             <th style="text-align: left;"><strong>'.$row->est_innova.'</strong></th>
                         </tr>';
        }
        $html2.='</tbody>
            </table>';
        $pdf->writeHTML($html2, true, false, false, false, '');
        $pdf->Output('pdfexample.pdf', 'I');
    }
    
    function getFichaToEvaluacion(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $itemplan = $this->input->post('itemplan');
            $data['dataHTML']  = $this->makeHTMLFIchaToEvaluacion($itemplan);
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }   
    
    function viewFichaEvaluacion(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $itemplan = $this->input->post('itemplan');
            $type     = $this->input->post('type');
            $data['dataHTML']  = $this->makeHTMLViewFIchaEvaluacion($itemplan, $type);
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function viewFichaEvaluacionSI(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $itemplan = $this->input->post('itemplan');
            $type     = $this->input->post('type');
            $data['dataHTML']  = $this->makeHTMLViewFIchaEvaluacionSI($itemplan, $type);
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function makeHTMLViewFIchaEvaluacion($item, $type){
        $dataItem = $this->m_termino_ficha_tecnica->getInfoItemPlanFichaTecnica($item);
        $dataFicha = $this->m_termino_ficha_tecnica->getInfoFichaTecnicaByItemplan($item, $type);
        $optionsAudi = $this->makeHTMLOptionsChoiceTipoTrabajo($this->m_utils->getOpcFTecnicaAuditor());
        $tbl ='
            
                <div class="row">
                    <div class="form-group col-sm-12">
                       <table style="height: 100%; width: 100%;">
                            <tbody>
                                <tr>
                                    <td style="width: 30%;"><strong>Itemplan: </strong>'.$dataItem['itemplan'].'</td>
                                    <td style="width: 50%;"><strong>Sub Proyecto: </strong>'.$dataItem['subProyectoDesc'].'</td>
                                    <td style="width: 20%;"><strong>Nodo: </strong>'.$dataItem['codigo'].'</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                                        
                    <div class="form-group col-sm-12">
                        <table style="height: 100%; width: 100%;">
                            <tbody>
                                <tr>
                                    <td style="width: 25%;"><strong>Fecha Inicio:</strong>'.$dataItem['fec_inicio'].'</td>
                                    <td style="width: 25%;"><strong>Fecha Fin:</strong>'.$dataItem['fechaEjecucion'].'</td>
                                    <td style="width: 25%;"><strong>Troba:</strong>'.$dataItem['indicador'].'</td>
                                    <td style="width: 25%;"><strong>Serie:</strong>'.$dataItem['serie'].'</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                                        
                    <div class="form-group col-sm-12">
                        <p><span style="text-decoration: underline;"><strong>JEFE DE CUADRILLA</strong></span></p>
                        <table style="height: 100%; width: 100%;">
                            <tbody>
                                <tr>
                                    <td style="width: 40%;"><strong>Nombre:</strong>'.$dataFicha['jefe_c_nombre'].'</td>
                                    <td style="width: 20%;"><strong>Codigo:</strong>'.$dataFicha['jefe_c_codigo'].'</td>
                                    <td style="width: 20%;">&nbsp;<strong>EECC:</strong>'.$dataItem['empresaColabDesc'].'</td>
                                    <td style="width: 20%;"><strong>Celular:</strong>'.$dataFicha['jefe_c_celular'].'</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                                        
                    <div class="form-group col-sm-12">
                        <p><span style="text-decoration: underline;"><strong>INFORMACION DE TRABAJOS REALIZADOS</strong></span></p>                       
                    </div>
                    <div style="height: 100%;" class="form-group col-sm-12 table-responsive">
                        <table style="font-size: x-small; height: 100%; width: 1285px; margin-left: auto; margin-right: auto;" border="1">
                        <tbody>
                        <tr style="background:#e4e4e4">
                            <th style="width: 8%;"></th>
                            <th style="width: 5%;text-align: center;"><strong>CANTIDAD</strong></th>
                            <th style="width: 5%;text-align: center;"><strong>TIPO</strong></th>
                            <th style="width: 20%;text-align: center;"><strong>OBSERVACIONES CONTRATA</strong></th>
                            <th style="width: 3%;text-align: center;"><strong>CHECK</strong></th>
                            <th style="width: 20%;text-align: center;"><strong>OBSERVACIONES TDP</strong></th>
                            <th style="width: 10%;text-align: center; color:red;"><strong>VALIDADION</strong></th>
                            <th style="width: 30%;text-align: center; color:red;"><strong>COMENTARIO</strong></th>
                        </tr>';
        $listaTrabajos = $this->m_termino_ficha_tecnica->getTrabajosFichatecnicaByItemplan($item, $type);
                            foreach($listaTrabajos->result() as $row){
                                $tbl .='<tr>
                                            <th style="text-align: left;"><strong>'.$row->descripcion.'</strong></th>
                                            <th style="text-align: center;">'.$row->cantidad.'</th>
                                            <th style="text-align: center;">'.$row->tipo_trabajo.'</th>
                                            <th>'.$row->observacion.'</th>
                                            <th style="text-align: center;"><label class="custom-control custom-checkbox"><input '.(($row->flg_validado == '1') ? 'checked' : '').' disabled type="checkbox" class="custom-control-input"><span class="custom-control-indicator"></span></label></th>
                                            <th>'.utf8_decode($row->comentario_vali).'</th>
                                            <th><select id="selectTrabajoAuditor'.$row->id_ficha_tecnica_x_tipo_trabajo.'" name="selectTrabajoAuditor'.$row->id_ficha_tecnica_x_tipo_trabajo.'" class="select2 selectForm">'.
                                                 $optionsAudi.'
                                                </select>
                                            </th>
                                            <th>
                                                <textarea style="height: 100%;" id="inputComentTrabaAuditor'.$row->id_ficha_tecnica_x_tipo_trabajo.'" name="inputComentTrabaAuditor'.$row->id_ficha_tecnica_x_tipo_trabajo.'" class="form-control" placeholder="Escriba aqui..."></textarea>
                                               <!-- <input id="inputComentTrabaAuditor'.$row->id_ficha_tecnica_x_tipo_trabajo.'" name="inputComentTrabaAuditor'.$row->id_ficha_tecnica_x_tipo_trabajo.'" type="text" class="form-control form-control-sm"> -->
                                            </th>
                                        </tr>';
                            }
                     $tbl .='</tbody>
                        </table>
                    </div>
                    <div class="form-group col-sm-12">
                        <table style="height: 100%; width: 100%;">
                        <tbody>
                        <tr>
                        <td>&nbsp;</td>
                        </tr>
                        <tr>
                        <td style="width: 100%;"><strong>Comentario: </strong></td>
                        </tr>
                        <tr>
                        <td>'.$dataFicha['observacion'].'</td>
                        </tr>
                        </tbody>
                        </table>
                    </div>';
                     
                     $tbl.= '<div style="height: 100%;" class="form-group col-sm-12 table-responsive">
                        <p><span style="text-decoration: underline;"><strong>PTRS ASOCIADAS</strong></span></p>
                        <table style="font-size: x-small;height: 100%; width: 1285px; margin-left: auto; margin-right: auto;" border="1">
                            <tbody>
                                <tr style="background:#e4e4e4">                                    
                                    <th style="width: 15%;text-align: center;"><strong>PTR</strong></th>
                                    <th style="width: 15%;text-align: center;"><strong>AREA</strong></th>
                                    <th style="width: 15%;text-align: center;"><strong>MONTO MATERIAL</strong></th>
                                    <th style="width: 15%;text-align: center;"><strong>MONTO MO</strong></th>
                                    <th style="width: 40%;text-align: center;"><strong>ESTADO</strong></th>
                                </tr>';
                     
                     $listaPtr = $this->m_termino_ficha_tecnica->getPTRSByItemplan($item);
                     foreach($listaPtr->result() as $row){
                         $tbl .='<tr>
                                    <th style="text-align: left;"><strong>'.$row->poCod.'</strong></th>
                                    <th style="text-align: left;"><strong>'.$row->areaDesc.'</strong></th>
                                    <th style="text-align: left;"><strong>'.$row->valoriz_material.'</strong></th>
                                    <th style="text-align: left;"><strong>'.$row->valoriz_m_o.'</strong></th>
                                    <th style="text-align: left;"><strong>'.$row->est_innova.'</strong></th>
                                 </tr>';
                        }
            $tbl .='</tbody>
                        </table>
                    </div>';
             $tbl .='    <div class="form-group col-sm-12">
                        <table style="height: 100%; width: 100%;">
                        <tbody>
                        <tr>
                        <td>&nbsp;</td>
                        </tr>
                        <tr>
                        <td style="width: 100%;"><strong>Comentario Adicional: </strong></td>
                        </tr>
                        <tr>
                        <td>'.$dataFicha['observacion_adicional'].'</td>
                        </tr>
                        </tbody>
                        </table>
                    </div>
                     <div class="form-group" style="text-align: right;width: 100%;">
                        <div class="col-sm-12">
                          <!--  <button data-item="'.$item.'" data-fic="'.$dataFicha['id_ficha_tecnica'].'" data-acc="2" onclick="validarFic(this)" type="button" class="btn btn-danger">RECHAZAR</button>  -->
                            <button id="btnAuditarFicha" data-type="'.$type.'" data-item="'.$item.'" data-fic="'.$dataFicha['id_ficha_tecnica'].'" type="submit" class="btn btn-primary">CONFIRMAR</button>
                        </div>
                    </div>
            </div>';
        return $tbl;
    }
    
    function makeHTMLViewFIchaEvaluacionSI($item, $type){
        $dataItem = $this->m_termino_ficha_tecnica->getInfoItemPlanFichaTecnica($item);
        $dataFicha = $this->m_termino_ficha_tecnica->getInfoFichaTecnicaByItemplan($item, $type);
        $optionsAudi = $this->makeHTMLOptionsChoiceTipoTrabajo($this->m_utils->getOpcFTecnicaAuditor());
        $tbl ='
            
                <div class="row">
                    <div class="form-group col-sm-12">
                       <table style="height: 100%; width: 100%;">
                            <tbody>
                                <tr>
                                    <td style="width: 30%;"><strong>Itemplan: </strong>'.$dataItem['itemplan'].'</td>
                                    <td style="width: 45%;"><strong>Sub Proyecto: </strong>'.$dataItem['subProyectoDesc'].'</td>
                                    <td style="width: 25%;"><strong>Sisego: </strong>'.$dataItem['indicador'].'</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                                        
                    <div class="form-group col-sm-12">
                        <table style="height: 100%; width: 100%;">
                            <tbody>
                                <tr>
                                    <td style="width: 25%;"><strong>Fecha Inicio: </strong>'.date("d/m/Y",  strtotime($dataItem['fec_inicio'])).'</td>
                                    <td style="width: 25%;"><strong>Fecha Fin: </strong>'.date("d/m/Y",  strtotime($dataItem['fechaPreLiquidacion'])).'</td>
                                    <td style="width: 25%;"><strong>Nodo: </strong>'.$dataItem['codigo'].'</td>
                                    <td style="width: 25%;"><strong>Serie de Troba: </strong>'.$dataItem['serie'].'</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                                        
                    <div class="form-group col-sm-12">
                        <p><span style="text-decoration: underline;"><strong>JEFE DE CUADRILLA</strong></span></p>
                        <table style="height: 100%; width: 100%;">
                            <tbody>
                                <tr>
                                    <td style="width: 50%;"><strong>Nombre:</strong>'.$dataFicha['jefe_c_nombre'].'</td>
                                    <td style="width: 25%;"><strong>Codigo:</strong>'.$dataFicha['jefe_c_codigo'].'</td>
                                    <td style="width: 25%;">&nbsp;<strong>EECC:</strong>'.$dataItem['empresaColabDesc'].'</td>
                                    <!--<td style="width: 20%;"><strong>Celular:</strong>'.$dataFicha['jefe_c_celular'].'</td>-->
                                </tr>
                            </tbody>
                        </table>
                    </div>
                                        
                    <div class="form-group col-sm-12">
                        <p><span style="text-decoration: underline;"><strong>INFORMACION DE TRABAJOS REALIZADOS</strong></span></p>
                    </div>
                    <div style="height: 100%;" class="form-group col-sm-12 table-responsive">
                        <table style="font-size: x-small; height: 100%; width: 1285px; margin-left: auto; margin-right: auto;" border="1">
                        <tbody>
                        <tr style="background:#e4e4e4">
                            <th style="width: 8%;"></th>
                            <th style="width: 5%;text-align: center;"><strong>CANTIDAD</strong></th>
                            <th style="width: 5%;text-align: center;"><strong>TIPO</strong></th>
                            <th style="width: 20%;text-align: center;"><strong>OBSERVACIONES CONTRATA</strong></th>
                            <th style="width: 3%;text-align: center;"><strong>CHECK</strong></th>
                            <th style="width: 20%;text-align: center;"><strong>OBSERVACIONES TDP</strong></th>
                            <th style="width: 10%;text-align: center; color:red;"><strong>VALIDADION</strong></th>
                            <th style="width: 30%;text-align: center; color:red;"><strong>COMENTARIO</strong></th>
                        </tr>';
        $listaTrabajos = $this->m_termino_ficha_tecnica->getTrabajosFichatecnicaByItemplan($item, $type);
        foreach($listaTrabajos->result() as $row){
            $tbl .='<tr>
                                            <th style="text-align: left;"><strong>'.$row->descripcion.'</strong></th>
                                            <th style="text-align: center;">'.$row->cantidad.'</th>
                                            <th style="text-align: center;">'.$row->tipo_trabajo.'</th>
                                            <th>'.$row->observacion.'</th>
                                            <th style="text-align: center;"><label class="custom-control custom-checkbox"><input '.(($row->flg_validado == '1') ? 'checked' : '').' disabled type="checkbox" class="custom-control-input"><span class="custom-control-indicator"></span></label></th>
                                            <th>'.utf8_decode($row->comentario_vali).'</th>
                                            <th><select id="selectTrabajoAuditor'.$row->id_ficha_tecnica_x_tipo_trabajo.'" name="selectTrabajoAuditor'.$row->id_ficha_tecnica_x_tipo_trabajo.'" class="select2 selectForm">'.
                                            $optionsAudi.'
                                                </select>
                                            </th>
                                            <th>
                                                <textarea style="height: 100%;" id="inputComentTrabaAuditor'.$row->id_ficha_tecnica_x_tipo_trabajo.'" name="inputComentTrabaAuditor'.$row->id_ficha_tecnica_x_tipo_trabajo.'" class="form-control" placeholder="Escriba aqui..."></textarea>
                                               <!-- <input id="inputComentTrabaAuditor'.$row->id_ficha_tecnica_x_tipo_trabajo.'" name="inputComentTrabaAuditor'.$row->id_ficha_tecnica_x_tipo_trabajo.'" type="text" class="form-control form-control-sm"> -->
                                            </th>
                                        </tr>';
        }
        $tbl .='</tbody>
                        </table>
                    </div>
                    <div class="form-group col-sm-12">
                        <table style="height: 100%; width: 100%;">
                        <tbody>
                        <tr>
                        <td>&nbsp;</td>
                        </tr>
                        <tr>
                        <td style="width: 100%;"><strong>Comentario: </strong></td>
                        </tr>
                        <tr>
                        <td>'.$dataFicha['observacion'].'</td>
                        </tr>
                        </tbody>
                        </table>
                    </div>';
        
        $tbl.= '<div style="height: 100%;" class="form-group col-sm-12 table-responsive">
                        <p><span style="text-decoration: underline;"><strong>PTRS ASOCIADAS</strong></span></p>
                        <table style="font-size: x-small;height: 100%; width: 1285px; margin-left: auto; margin-right: auto;" border="1">
                            <tbody>
                                <tr style="background:#e4e4e4">
                                    <th style="width: 15%;text-align: center;"><strong>PTR</strong></th>
                                    <th style="width: 15%;text-align: center;"><strong>AREA</strong></th>
                                    <th style="width: 15%;text-align: center;"><strong>MONTO MATERIAL</strong></th>
                                    <th style="width: 15%;text-align: center;"><strong>MONTO MO</strong></th>
                                    <th style="width: 40%;text-align: center;"><strong>ESTADO</strong></th>
                                </tr>';
        
        $listaPtr = $this->m_termino_ficha_tecnica->getPTRSByItemplan($item);
        foreach($listaPtr->result() as $row){
            $tbl .='<tr>
                                    <th style="text-align: left;"><strong>'.$row->poCod.'</strong></th>
                                    <th style="text-align: left;"><strong>'.$row->areaDesc.'</strong></th>
                                    <th style="text-align: left;"><strong>'.$row->valoriz_material.'</strong></th>
                                    <th style="text-align: left;"><strong>'.$row->valoriz_m_o.'</strong></th>
                                    <th style="text-align: left;"><strong>'.$row->est_innova.'</strong></th>
                                 </tr>';
        }
        $tbl .='</tbody>
                        </table>
                    </div>';
        $tbl .='    <div class="form-group" style="text-align: right;width: 100%;">
                        <div class="col-sm-12">
                          <!--  <button data-item="'.$item.'" data-fic="'.$dataFicha['id_ficha_tecnica'].'" data-acc="2" onclick="validarFic(this)" type="button" class="btn btn-danger">RECHAZAR</button>  -->
                            <button id="btnAuditarFicha" data-type="'.$type.'" data-item="'.$item.'" data-fic="'.$dataFicha['id_ficha_tecnica'].'" type="submit" class="btn btn-primary">CONFIRMAR</button>
                        </div>
                    </div>
            </div>';
        return $tbl;
    }
    
    function saveEvaluacionAudi(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $itemplan = $this->input->post('itemplan');
            $idFicha = $this->input->post('idFicha');
            $type = $this->input->post('type');
            
            $arrayTrabajo = array();
            $arrayNivelesCali = array();
            
            $listaTrabajos = $this->m_termino_ficha_tecnica->getTrabajosFichatecnicaByItemplan($itemplan, $type);            
            foreach($listaTrabajos->result() as $row){
                $datatrans = array();
                $datatrans['id_ficha_tecnica_x_tipo_trabajo'] = $row->id_ficha_tecnica_x_tipo_trabajo;
                $datatrans['opc_aud'] = $this->input->post('selectTrabajoAuditor'.$row->id_ficha_tecnica_x_tipo_trabajo);
                $datatrans['comentario_aud'] = $this->input->post('inputComentTrabaAuditor'.$row->id_ficha_tecnica_x_tipo_trabajo);
                array_push($arrayTrabajo, $datatrans);  
                //log_message('error', 'value:'.$row->id_ficha_tecnica_x_tipo_trabajo.'--'.$this->input->post('selectTrabajoAuditor'.$row->id_ficha_tecnica_x_tipo_trabajo).'--'.$this->input->post('inputComentTrabaAuditor'.$row->id_ficha_tecnica_x_tipo_trabajo));
            }
            
            $listaNivCalibra = $this->m_termino_ficha_tecnica->getNivelesCalibracionByItemplan($itemplan);
            foreach($listaNivCalibra->result() as $row){
                $datatrans = array();
                $datatrans['id_ficha_tecnica_x_nivel_calibra'] = $row->id_ficha_tecnica_x_nivel_calibra;
                $datatrans['opc_aud'] = $this->input->post('selectNivel'.$row->id_ficha_tecnica_x_nivel_calibra);
                $datatrans['comentario_aud'] = $this->input->post('inputComentNivelAuditor'.$row->id_ficha_tecnica_x_nivel_calibra);
                array_push($arrayNivelesCali, $datatrans);  
               // log_message('error', 'value:'.$row->id_ficha_tecnica_x_nivel_calibra.'--'.$this->input->post('selectNivel'.$row->id_ficha_tecnica_x_nivel_calibra).'--'.$this->input->post('inputComentNivelAuditor'.$row->id_ficha_tecnica_x_nivel_calibra));
                
            }
            
            $data = $this->m_termino_ficha_tecnica->saveFichaAuditoria($idFicha, $arrayTrabajo, $arrayNivelesCali);
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_termino_ficha_tecnica->getBandejaFichaTecnicaEvaluacion('','','','',''));	
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function makePDFEvaluacion(){
        $item = (isset($_GET['itm']) ? $_GET['itm'] : '');
        $type = (isset($_GET['type']) ? $_GET['type'] : '');
        
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
        $pdf->AddPage('L');
        $pdf->SetFont('helvetica', '', 8);
        
        $dataItem = $this->m_bandeja_ficha_tecnica->getInfoItemPlanFichaTecnica($item);
        $dataFicha = $this->m_termino_ficha_tecnica->getInfoFichaTecnicaByItemplan($item, $type);
        $tbl ='<img style="width: 100px; heigth:40px" src="'.base_url().'public/img/logo/tdp.png">
            <p style="text-align: center;"><strong>CHECKLIST DE TRABAJOS EN PLANTA INTERNA</strong></p>
            
            <table style="height: 100%; width: 100%;">
            <tbody>
            <tr>
            <td style="width: 30%;"><strong>Itemplan: </strong>'.$dataItem['itemplan'].'</td>
            <td style="width: 50%;"><strong>Sub Proyecto: </strong>'.$dataItem['subProyectoDesc'].'</td>
            <td style="width: 20%;"><strong>Nodo: </strong>'.$dataItem['codigo'].'</td>
            </tr>
            </tbody>
            </table>
            <p><br /><br /></p>
            <table style="height: 100%; width: 100%;">
            <tbody>
            <tr>
            <td style="width: 25%;"><strong>Fecha Inicio:</strong>'.$dataItem['fec_inicio'].'</td>
            <td style="width: 25%;"><strong>Fecha Fin:</strong>'.$dataItem['fechaPreLiquidacion'].'</td>
            <td style="width: 25%;"><strong>Troba:</strong>'.$dataItem['indicador'].'</td>
            <td style="width: 25%;"><strong>Serie:</strong>'.$dataItem['serie'].'</td>
            </tr>
            </tbody>
            </table>
            <p><span style="text-decoration: underline;"><strong>JEFE DE CUADRILLA</strong></span></p>
            <table style="height: 100%; width: 100%;">
            <tbody>
            <tr>
            <td style="width: 40%;"><strong>Nombre:</strong>'.$dataFicha['jefe_c_nombre'].'</td>
            <td style="width: 20%;"><strong>Codigo:</strong>'.$dataFicha['jefe_c_codigo'].'</td>
            <td style="width: 20%;"><strong>EECC:</strong>'.$dataItem['empresaColabDesc'].'</td>
            <td style="width: 20%;"><strong>Celular:</strong>'.$dataFicha['jefe_c_celular'].'</td>
            </tr>
            </tbody>
            </table>
            <p><span style="text-decoration: underline;"><strong>INFORMACION DE TRABAJOS REALIZADOS</strong></span></p>
            <table style="font-size: small; height: 100%; width: 100%; margin-left: auto; margin-right: auto;" border="1">
            <tbody>
            <tr>
            <th style="width: 12%;">&nbsp;</th>
            <th style="width: 8%;text-align: center;"><strong>CANTIDAD</strong></th>
            <th style="width: 10%;text-align: center;"><strong>TIPO</strong></th>
            <th style="width: 32%;text-align: center;"><strong>OBSERVACIONES</strong></th>
            <th style="width: 6%;text-align: center;"><strong>VALIDADO</strong></th>
            <th style="width: 32%;text-align: center;"><strong>OBERVACION TDP</strong></th>
            </tr>';
        $listaTrabajos = $this->m_termino_ficha_tecnica->getTrabajosFichatecnicaByItemplan($item, $type);
        foreach($listaTrabajos->result() as $row){
            $tbl .='<tr>
                <th style="text-align: left; height: 40px"><strong>'.$row->descripcion.'</strong></th>
                <th style="text-align: center;">'.$row->cantidad.'</th>
                <th>'.$row->tipo_trabajo.'</th>
                <th>'.$row->observacion.'</th>
                <th>'.(($row->flg_validado == 1) ? 'SI' : 'NO').'</th>
                <th>'.((strlen($row->comentario_vali) > 150) ? substr($row->comentario_vali, 0, 150).'...' : $row->comentario_vali).'</th>
            </tr>';
        }
        $tbl .='</tbody>
            </table>
            <table style="height: 100%; width: 100%;">
            <tbody>
            <tr>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td style="width: 100%;"><strong>Comentario: </strong></td>
            </tr>
            <tr>
            <td>'.$dataFicha['observacion'].'</td>
            </tr>
            </tbody>
            </table>
            <p><span style="text-decoration: underline;"><strong>NIVELES DE CALIBRACION</strong></span></p>
            <table style="font-size: small; height: 100%; width: 100%;" border="1">';
        $tbl .='    <tbody>
                <tr style="background:#e4e4e4">
                <th style="width: 15%;text-align: center;"><strong>PTR</strong></th>
                <th style="width: 15%;text-align: center;"><strong>AREA</strong></th>
                <th style="width: 15%;text-align: center;"><strong>MONTO MATERIAL</strong></th>
                <th style="width: 15%;text-align: center;"><strong>MONTO MO</strong></th>
                <th style="width: 40%;text-align: center;"><strong>ESTADO</strong></th>
                </tr>';
        
        $listaPtr = $this->m_termino_ficha_tecnica->getPTRSByItemplan($item);
        foreach($listaPtr->result() as $row){
            $tbl .='<tr>
                             <th style="text-align: left;"><strong>'.$row->poCod.'</strong></th>
                             <th style="text-align: left;"><strong>'.$row->areaDesc.'</strong></th>
                             <th style="text-align: left;"><strong>'.$row->valoriz_material.'</strong></th>
                             <th style="text-align: left;"><strong>'.$row->valoriz_m_o.'</strong></th>
                             <th style="text-align: left;"><strong>'.$row->est_innova.'</strong></th>
                         </tr>';
        }
        /*
            <thead class="thead-default">
            <tr role="row">
                <th style="width: 10%; text-align: center; height: 10px" colspan="1">&nbsp;</th>
                <th style="width: 12%; text-align: center; font-size: medium;" colspan="2"><strong>POT. OPT</strong></th>
                <th style="width: 6%; text-align: center; font-size: medium;" colspan="1"><strong>CH 30</strong></th>
                <th style="width: 6%; text-align: center; font-size: medium;" colspan="1"><strong>CH 75</strong></th>
                <th style="width: 6%; text-align: center; font-size: medium;" colspan="1"><strong>CH 113</strong></th>
                <th style="width: 6%; text-align: center; font-size: medium;" colspan="1"><strong>SNR - RUIDO</strong></th>
                <th style="width: 54%; text-align: center;font-size: medium;" colspan="2"><strong>VALIDACION</strong></th>
            </tr>
            <tr role="row">
                <th style="width: 10%; text-align: center; height: 10px" colspan="1">&nbsp;</th>
                <th style="width: 6%; text-align: center; font-size: medium;" colspan="1"><strong>0 - 3 DB</strong></th>
                <th style="width: 6%; text-align: center; font-size: medium;" colspan="1"><strong>3 - 7 DB</strong></th>
                <th style="width: 6%; text-align: center; font-size: medium;" colspan="1"><strong>36 - 39 DB</strong></th>
                <th style="width: 6%; text-align: center; font-size: medium;" colspan="1"><strong>40 - 42 DB</strong></th>
                <th style="width: 6%; text-align: center; font-size: medium;" colspan="1"><strong>44 - 45 DB</strong></th>
                <th style="width: 6%; text-align: center; font-size: medium;" colspan="1"><strong>&gt; 32 DB</strong></th>
                <th style="width: 6%; text-align: center;font-size: medium;" colspan="1"><strong>VALIDADO</strong></th>
                <th style="width: 48%; text-align: center;font-size: medium;" colspan="1"><strong>OBERVACION TDP</strong></th>
            </tr>
            </thead>
            <tbody>';
        $listaNivCalibra = $this->m_bandeja_ficha_tecnica->getNivelesCalibracionByItemplan($item);
        foreach($listaNivCalibra->result() as $row){
            $tbl .=' <tr>
                        <th style="width: 10%; text-align: left; height: 40px"><strong>'.$row->descripcion.'</strong></th>
                        <th style="width: 6%; text-align: center;">'.$row->opt_recep.'</th>
                        <th style="width: 6%; text-align: center;">'.$row->opt_tx.'</th>
                        <th style="width: 6%; text-align: center;">'.$row->ch_30.'</th>
                        <th style="width: 6%; text-align: center;">'.$row->ch_75.'</th>
                        <th style="width: 6%; text-align: center;">'.$row->ch_113.'</th>
                        <th style="width: 6%; text-align: center;">'.$row->snr_ruido.'</th>
                        <th style="width: 6%;">'.(($row->flg_validado == 1) ? 'SI' : 'NO').'</th>
                        <th style="width: 48%;">'.((strlen($row->comentario_vali) > 150) ? substr($row->comentario_vali, 0, 150).'...' : $row->comentario_vali).'</th>
                    </tr>';
        }*/
        $tbl .='</tbody>
            </table>
            <table style="height: 100%; width: 100%;">
            <tbody>
            <tr>
            <td>&nbsp;</td>
            </tr>
            <tr>
            <td style="width: 100%;"><strong>Comentario Adicional: </strong></td>
            </tr>
            <tr>
            <td>'.$dataFicha['observacion_adicional'].'</td>
            </tr>
            </tbody>
            </table>';
        
        $pdf->writeHTML($tbl, true, false, false, false, '');
        //ob_clean();
        $pdf->Output('pdfexample.pdf', 'I');
    }
    
    function makePDFEvaluacionCIOSI(){
        $item = (isset($_GET['itm']) ? $_GET['itm'] : '');
        $type = (isset($_GET['type']) ? $_GET['type'] : '');
        
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
        $pdf->AddPage('L');
        $pdf->SetFont('helvetica', '', 8);
        
        $dataItem = $this->m_bandeja_ficha_tecnica->getInfoItemPlanFichaTecnica($item);
        $dataFicha = $this->m_termino_ficha_tecnica->getInfoFichaTecnicaByItemplan($item, $type);
        $tbl ='<img style="width: 100px; heigth:40px" src="'.base_url().'public/img/logo/tdp.png">
                <p style="text-align: center;"><strong>CHECKLIST DE TRABAJOS EN PLANTA INTERNA</strong></p>
            
                <table style="height: 100%; width: 100%;">
                <tbody>
                <tr>
                <td style="width: 30%;"><strong>Itemplan: </strong>'.$dataItem['itemplan'].'</td>
                <td style="width: 45%;"><strong>Sub Proyecto: </strong>'.$dataItem['subProyectoDesc'].'</td>
                <td style="width: 25%;"><strong>Sisego: </strong>'.$dataItem['indicador'].'</td>
                    
                </tr>
                </tbody>
                </table>
                <p><br /><br /></p>
                <table style="height: 100%; width: 100%;">
                <tbody>
                <tr>
                <td style="width: 25%;"><strong>Fecha Inicio: </strong>'.date("d/m/Y",  strtotime($dataItem['fec_inicio'])).'</td>
                <td style="width: 25%;"><strong>Fecha Fin: </strong>'.date("d/m/Y",  strtotime($dataItem['fechaPreLiquidacion'])).'</td>
                <td style="width: 25%;"><strong>Nodo: </strong>'.$dataItem['codigo'].'</td>
                <td style="width: 25%;"><strong>Serie Troba: </strong>'.$dataItem['serie'].'</td>
                </tr>
                </tbody>
                </table>
                <p><span style="text-decoration: underline;"><strong>JEFE DE CUADRILLA</strong></span></p>
                <table style="height: 100%; width: 100%;">
                <tbody>
                <tr>
                <td style="width: 50%;"><strong>Nombre:</strong>'.$dataFicha['jefe_c_nombre'].'</td>
                <td style="width: 25%;"><strong>Codigo:</strong>'.$dataFicha['jefe_c_codigo'].'</td>
                <td style="width: 25%;"><strong>EECC:</strong>'.$dataItem['empresaColabDesc'].'</td>
                <!--<td style="width: 20%;"><strong>Celular:</strong>'.$dataFicha['jefe_c_celular'].'</td>-->
                </tr>
                </tbody>
                </table>
                <p><span style="text-decoration: underline;"><strong>INFORMACION DE TRABAJOS REALIZADOS</strong></span></p>
                <table style="font-size: small; height: 100%; width: 100%; margin-left: auto; margin-right: auto;" border="1">
                <tbody>
                <tr>
                <th style="width: 12%;">&nbsp;</th>
                <th style="width: 8%;text-align: center;"><strong>CANTIDAD</strong></th>
                <th style="width: 10%;text-align: center;"><strong>TIPO</strong></th>
                <th style="width: 32%;text-align: center;"><strong>OBSERVACIONES</strong></th>
                <th style="width: 6%;text-align: center;"><strong>VALIDADO</strong></th>
                <th style="width: 32%;text-align: center;"><strong>OBERVACION TDP</strong></th>
                </tr>';
        $listaTrabajos = $this->m_termino_ficha_tecnica->getTrabajosFichatecnicaByItemplan($item, $type);
        foreach($listaTrabajos->result() as $row){
            $tbl .='<tr>
                    <th style="text-align: left; height: 40px"><strong>'.$row->descripcion.'</strong></th>
                    <th style="text-align: center;">'.$row->cantidad.'</th>
                    <th>'.$row->tipo_trabajo.'</th>
                    <th>'.$row->observacion.'</th>
                    <th>'.(($row->flg_validado == 1) ? 'SI' : 'NO').'</th>
                    <th>'.((strlen($row->comentario_vali) > 150) ? substr($row->comentario_vali, 0, 150).'...' : $row->comentario_vali).'</th>
                </tr>';
        }
        $tbl .='</tbody>
                </table>
                <table style="height: 100%; width: 100%;">
                <tbody>
                <tr>
                <td>&nbsp;</td>
                </tr>
                <tr>
                <td>&nbsp;</td>
                </tr>
                <tr>
                <td style="width: 100%;"><strong>Comentario: </strong></td>
                </tr>
                <tr>
                <td>'.$dataFicha['observacion'].'</td>
                </tr>
                </tbody>
                </table>';
                
               
        $pdf->writeHTML($tbl, true, false, false, false, '');
        $pdf->AddPage('L');
        $html2 = '<p><span style="text-decoration: underline;"><strong>PTR ASOCIADAS</strong></span></p>
                <table style="font-size: small; height: 100%; width: 100%;" border="1">
                <tbody>
                    <tr style="background:#e4e4e4">
                    <th style="width: 15%;text-align: center;"><strong>PTR</strong></th>
                    <th style="width: 15%;text-align: center;"><strong>AREA</strong></th>
                    <th style="width: 15%;text-align: center;"><strong>MONTO MATERIAL</strong></th>
                    <th style="width: 15%;text-align: center;"><strong>MONTO MO</strong></th>
                    <th style="width: 40%;text-align: center;"><strong>ESTADO</strong></th>
                    </tr>';
        
        $listaPtr = $this->m_termino_ficha_tecnica->getPTRSByItemplan($item);
        foreach($listaPtr->result() as $row){
            $html2.='<tr>
                                 <th style="text-align: left;"><strong>'.$row->poCod.'</strong></th>
                                 <th style="text-align: left;"><strong>'.$row->areaDesc.'</strong></th>
                                 <th style="text-align: left;"><strong>'.$row->valoriz_material.'</strong></th>
                                 <th style="text-align: left;"><strong>'.$row->valoriz_m_o.'</strong></th>
                                 <th style="text-align: left;"><strong>'.$row->est_innova.'</strong></th>
                             </tr>';
        }
        $html2.='</tbody>
                </table>';
        $pdf->writeHTML($html2, true, false, false, false, '');
        //ob_clean();
        $pdf->Output('pdfexample.pdf', 'I');
    }
    
function openModalOBP(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $itemplan = $this->input->post('itemplan');
            $type     = $this->input->post('type');
            //flg = 1 modal, flg = 2 PDF
            $flg      = 1;
            $data['dataHTML']  = $this->modelViewFichaCIO($itemplan, $type, $flg);
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function modelViewFichaCIO($item, $type, $flg){
        $form_group = ($flg == 1) ? 'form-group' : null;
        $title      = ($flg == 1) ? null : '<p style="text-align: center;"><strong>CHECKLIST DE TRABAJOS</strong></p>';
        $dataItem    = $this->m_bandeja_ficha_tecnica->getInfoItemPlanFichaTecnica($item);
        $dataFicha   = $this->m_bandeja_ficha_tecnica->getInfoFichaTecnicaByItemplan($item, $type);
        $optionsAudi = $this->makeHTMLOptionsChoiceTipoTrabajo($this->m_utils->getOpcFTecnicaAuditor());

        $tbl = $title.
                '<div class="row">
                    <div class="'.$form_group.' col-sm-12">
                       <table style="height: 100%; width: 100%;">
                            <tbody>
                                <tr>
                                    <td style="width: 30%;"><strong>Itemplan: </strong>'.$dataItem['itemplan'].'</td>
                                    <td style="width: 45%;"><strong>Sub Proyecto: </strong>'.$dataItem['subProyectoDesc'].'</td>
                                    <td style="width: 25%;"><strong>Sisego: </strong>'.$dataItem['indicador'].'</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                                        
                    <div class="'.$form_group.' col-sm-12">
                        <table style="height: 100%; width: 100%;">
                            <tbody>
                                <tr>
                                    <td style="width: 30%;"><strong>Fecha Inicio: </strong>'.date("d/m/Y",  strtotime($dataItem['fec_inicio'])).'</td>
                                    <td style="width: 45%;"><strong>Fecha Fin: </strong>'.date("d/m/Y",  strtotime($dataItem['fechaPreLiquidacion'])).'</td>
                                    <td style="width: 25%;"><strong>Nodo: </strong>'.$dataItem['codigo'].'</td> 
                                </tr>
                            </tbody>
                        </table>
                    </div>
                                        
                    <div class="'.$form_group.' col-sm-12">
                        <p><span style="text-decoration: underline;"><strong>JEFE DE CUADRILLA</strong></span></p>
                        <table style="height: 100%; width: 100%;">
                            <tbody>
                                <tr>
                                    <td style="width: 30%;"><strong>Nombre:</strong>'.$dataFicha['jefe_c_nombre'].'</td>
                                    <!--<td style="width: 25%;"><strong>Codigo:</strong>'.$dataFicha['jefe_c_codigo'].'</td>-->
                                    <td style="width: 0%;">&nbsp;<strong>EECC:</strong>'.$dataItem['empresaColabDesc'].'</td>
                                    <!--<td style="width: 20%;"><strong>Celular:</strong>'.$dataFicha['jefe_c_celular'].'</td>-->
                                </tr>
                            </tbody>
                        </table>
                    </div>
                                        
                    <div class="'.$form_group.' col-sm-12">
                        <p><span style="text-decoration: underline;"><strong>INFORMACION DE TRABAJOS REALIZADOS</strong></span></p>
                    </div>
                    <div style="height: 100%;" class="form-group col-sm-12 table-responsive">
                        <table style="font-size: x-small; height: 100%; width: 1285px; margin-left: auto; margin-right: auto;" border="1">
                        <tbody>
                        <tr style="background:#e4e4e4">
                            <th style="width: 5%;text-align: center;"><strong>PTR</strong></th>
                            <th style="width: 5%;text-align: center;"><strong>Canalizaci&oacute;n KM</strong></th>
                            <th style="width: 5%;text-align: center;"><strong>Camaras Und</strong></th>
                            <th style="width: 5%;text-align:  center;"><strong>C (Postes)</strong></th>
                            <th style="width: 5%;text-align:  center;"><strong>MA (Postes)</strong></th>
                            <th style="width: 5%;text-align: center;"><strong>KM Tritubo</strong></th>
                            <th style="width: 5%;text-align: center;"><strong>Km Par cobre</strong></th>
                            <th style="width: 5%;text-align: center;"><strong>Km Cable coaxial</strong></th>
                            <th style="width: 5%;text-align: center;"><strong>Km FO</strong></th>
                            <th style="width: 15%;text-align: center;"><strong>Observaci&oacute;n</strong></th>
                        </tr>';
                $arrayDataFormulario = $this->m_bandeja_ficha_tecnica->getDataFormularioObrasPublicas($item);
                foreach($arrayDataFormulario as $row) {
                    $tbl .='<tr>
                    <th style="text-align: left;"><strong>'.$row->poCod.'</strong></th>
                    <th style="text-align: center;">'.$row->canalizacion_km.'</th>
                    <th style="text-align: left;">'.$row->camaras_und.'</th>
                    <th style="text-align: left;">'.$row->c_postes.'</th>
                    <th style="text-align: left;">'.$row->ma_postes.'</th>
                    <th style="text-align: left;">'.$row->km_tritubo.'</th>
                    <th style="text-align: left;">'.$row->km_par_cobre.'</th>
                    <th style="text-align: left;">'.$row->km_cable_coax.'</th>
                    <th style="text-align: left;">'.$row->km_fo.'</th>
                    <th style="text-align: center;">'.$row->observacion.'</th>
                    </tr>';
                }
        $tbl .='</tbody>
                        </table>
                    </div>
                    <div class="'.$form_group.' col-sm-12">
                        <table style="height: 100%; width: 100%;">
                            <tbody>
                                <tr>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td style="width: 100%;"><strong>Comentario: </strong></td>
                                </tr>
                                <tr>
                                    <td>'.utf8_decode($dataFicha['observacion_tdp']).'</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>';
        
        $tbl.= '<div style="height: 100%;" class="'.$form_group.' col-sm-12 table-responsive">
                        <p><span style="text-decoration: underline;"><strong>PTRS ASOCIADAS</strong></span></p>
                        <table style="font-size: x-small;height: 100%; width: 1285px; margin-left: auto; margin-right: auto;" border="1">
                            <tbody>
                                <tr style="background:#e4e4e4">
                                    <th style="width: 10%;text-align: center;"><strong>PTR</strong></th>
                                    <th style="width: 10%;text-align: center;"><strong>AREA</strong></th>
                                    <th style="width: 10%;text-align: center;"><strong>MONTO MATERIAL</strong></th>
                                    <th style="width: 10%;text-align: center;"><strong>MONTO MO</strong></th>
                                    <th style="width: 15%;text-align: center;"><strong>ESTADO</strong></th>
                                </tr>';
        
        $listaPtr = $this->m_termino_ficha_tecnica->getPTRSByItemplan($item);
        foreach($listaPtr->result() as $row){
            $tbl .='<tr>
                        <th style="width: 10%;text-align: left;"><strong>'.$row->poCod.'</strong></th>
                        <th style="width: 10%;text-align: left;"><strong>'.utf8_decode($row->areaDesc).'</strong></th>
                        <th style="width: 10%;text-align: left;"><strong>'.$row->valoriz_material.'</strong></th>
                        <th style="width: 10%;text-align: left;"><strong>'.$row->valoriz_m_o.'</strong></th>
                        <th style="width: 15%;text-align: left;"><strong>'.$row->est_innova.'</strong></th>
                    </tr>';
        }

        if($flg == 1) {
            $tbl .='            </tbody>
                            </table>
                        </div>
                        <div class="'.$form_group.'">
                            <div>
                                <label>Evaluar:</label>
                                <select id="selectTrabajoAuditor" name="selectTrabajoAuditor" class="select2 selectForm">'.
                                    $optionsAudi.'
                                </select>
                            </div>
                            <div>   
                                <label>Ingresar Comentario CIO:</label>
                                <textarea id="observacion_audi" style="width:100%"></textarea>
                            </div>    
                        </div>';
            $tbl .='    <div class="'.$form_group.'" style="text-align: right;width: 100%;">
                                    <div class="col-sm-12">
                                    <!--  <button data-item="'.$item.'" data-fic="'.$dataFicha['id_ficha_tecnica'].'" data-acc="2" onclick="validarFic(this)" type="button" class="btn btn-danger">RECHAZAR</button>  -->
                                        <button id="btnAuditarFicha" data-type="'.$type.'" data-item="'.$item.'" data-fic="'.$dataFicha['id_ficha_tecnica'].'" type="submit" class="btn btn-primary">CONFIRMAR</button>
                                    </div>
                                </div>
                        </div>';
        } else {
            $tbl .='            </tbody>
                        </table>
                    </div>
                    <div class="'.$form_group.'">
                        <div>
                            <strong>Evaluaci&oacute;n:</strong>
                            <label>'.$dataFicha['descFichaAudi'].'</label>
                        </div>
                        <div>   
                            <strong>Comentario CIO:</strong>
                            <label>'.utf8_decode($dataFicha['observacion_audi']).'</label>
                        </div>    
                    </div>';
        }
       
        return $tbl;
    }

    function saveAudiOBP() {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $itemplan             = $this->input->post('itemplan');
            $idFicha              = $this->input->post('idFicha');
            $type                 = $this->input->post('type');
            $observacion_audi     = $this->input->post('observacion_audi');
            $selectTrabajoAuditor = $this->input->post('selectTrabajoAuditor');

            $data = $this->m_termino_ficha_tecnica->saveAudiOBP($idFicha, $selectTrabajoAuditor, $observacion_audi);
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_termino_ficha_tecnica->getBandejaFichaTecnicaEvaluacion('','','','',''));	
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function openPdfCIO(){
        $item = (isset($_GET['itm']) ? $_GET['itm'] : '');
        $type = (isset($_GET['type']) ? $_GET['type'] : '');
         
        $this->load->library('Pdf');
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetTitle('Pdf Example');
        $pdf->SetHeaderMargin(30);
        //$pdf->SetTopMargin(20);
        $pdf->setFooterMargin(20);
        $pdf->SetAutoPageBreak(true);
        $pdf->SetAuthor('Telefonica.pe');
        $pdf->SetDisplayMode('real', 'default');
        //$pdf->Write(5, 'CodeIgniter TCPDF Integration');
        // set font
        $pdf->SetFont('helvetica', 'B', 20);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        // add a page
        $pdf->AddPage('L');
        $pdf->SetFont('helvetica', '', 8);
        //FLG=2 cuando es PDF
        $flg = 2;
        $tbl = $this->modelViewFichaCIO($item, $type, $flg);
        $pdf->writeHTML(utf8_encode($tbl), true, false, false, false, '');
        //ob_clean();
        $pdf->Output('pdfexample.pdf', 'I');     
    }
}