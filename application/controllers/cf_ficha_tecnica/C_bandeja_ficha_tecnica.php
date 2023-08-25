<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * @author CARLOS ZAVALA C.
 * 18/01/2018
 *
 */
class C_bandeja_ficha_tecnica extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
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
        	   $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_bandeja_ficha_tecnica->getBandejaFichaTecnicaEvaluacion('','','','',''));	
        	   $data['listaTrabajos'] = $this->m_bandeja_ficha_tecnica->getTrabajosFichaTecnica(TIPO_fICHA_COAXIAL_GENERICO);
        	   $data['listaNivelesCali'] = $this->m_bandeja_ficha_tecnica->getNivelesCalibracion();
        	   $data['optionsTipoTra'] = $this->makeHTMLOptionsChoiceTipoTrabajo($this->m_bandeja_ficha_tecnica->getTipoTrabajoFichaTecnica());
        	   $data['nombreUsuario'] =  $this->session->userdata('usernameSession');	
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');	               
        	   $permisos =  $this->session->userdata('permisosArbol');
        	   $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_INSPECCIONES, ID_PERMISO_HIJO_REGISTRO_FICHA);
        	   $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){        	       
        	       $this->load->view('vf_ficha_tecnica/v_bandeja_ficha_tecnica',$data);
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
            $html .= '<option value="'.$row->id_ficha_tecnica_tipo_trabajo.'">'.$row->descripcion.'</option>';
        }       
        return $html;
    }

    public function makeHTLMTablaBandejaAprobMo($listaPTR){  
                $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th></th>
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
                    $btnFotos = '';
                    /*
$btnFotos = '<a data-item_plan="'.$row->itemPlan.'"onclick="openModalFotos($(this));" style="cursor:pointer;margin-left: 10px;">
                                <img alt="Editar" height="20px" width="30px" src="public/img/iconos/camara.jpg">
                            </a>';*/
$btnPDFContrata = '';
$btnViewFichaEval = '';
$btnEvaluarTDP = '';
if($row->id_ficha_tecnica_base == FICHA_COAXIAL_GENERICA){
    $btnPDFContrata = '<a href="makePDF?itm='.$row->itemPlan.'" target="_blank"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/iconpdf.svg"></a>';
    if($row->estado_validacion != null){
        $btnViewFichaEval = '<a style="margin-left: 10px;" data-itm ="'.$row->itemPlan.'" onclick="viewFichaEval(this)"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/iconview.svg"></a>';
    }else{
        $btnEvaluarTDP = '<a style="margin-left: 10px;" data-itm ="'.$row->itemPlan.'" onclick="getFichaToEval(this)"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/editar.ico"></a>';
    }
}else if($row->id_ficha_tecnica_base == FICHA_FO_FTTH_Y_OP){
    $btnPDFContrata = '<a href="makePDFFO?itm='.$row->itemPlan.'" target="_blank"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/iconpdf.svg"></a>';
    if($row->estado_validacion != null){
        $btnViewFichaEval = '<a style="margin-left: 10px;" data-itm ="'.$row->itemPlan.'" onclick="viewFichaEvalFO(this)"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/iconview.svg"></a>';
    }else{
        $btnEvaluarTDP = '<a style="margin-left: 10px;" data-itm ="'.$row->itemPlan.'" onclick="getFichaToEvalFO(this)"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/editar.ico"></a>';
    }
}else if($row->id_ficha_tecnica_base == FICHA_FO_SISEGOS_SMALLCELL_EBC){
    $btnPDFContrata = '<a href="makePDFSI?itm='.$row->itemPlan.'" target="_blank"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/iconpdf.svg"></a>';
    if($row->estado_validacion != null){
        $btnViewFichaEval = '<a style="margin-left: 10px;" data-itm ="'.$row->itemPlan.'" onclick="viewFichaEvalSI(this)"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/iconview.svg"></a>';
    }else{
        $btnEvaluarTDP = '<a style="margin-left: 10px;" data-itm ="'.$row->itemPlan.'" onclick="getFichaToEvalSI(this)"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/editar.ico"></a>';
    }
}else if($row->id_ficha_tecnica_base == FICHA_FO_OBRAS_PUBLICAS){
    $btnPDFContrata = '<a href="makePDFOBP?itm='.$row->itemPlan.'&&flg=2" target="_blank"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/iconpdf.svg"></a>';
    if($row->estado_validacion != null){
        $btnViewFichaEval = '<a style="margin-left: 10px;" data-itm ="'.$row->itemPlan.'" onclick="viewFichaEvalOBP(this)"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/iconview.svg"></a>';
    }else{
        $btnEvaluarTDP = '<a style="margin-left: 10px;" data-itm ="'.$row->itemPlan.'" onclick="viewFichaEvalOBP(this)"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/editar.ico"></a>';
    }
}

                $html .=' <tr>
                            <th>'.$btnPDFContrata.$btnViewFichaEval.$btnEvaluarTDP.$btnFotos.'</th>
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
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_bandeja_ficha_tecnica->getBandejaFichaTecnicaEvaluacion($SubProy,$eecc,$zonal,$situacion,$mesEjec));
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
            $datosItem = $this->m_bandeja_ficha_tecnica->getInfoItemPlanFichaTecnica($itemplan);
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
    /*
    function registrarFichaTecnica(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $itemplan       = $this->input->post('itemplan');
            $nombreJefe     = $this->input->post('txtNombreJefeCuadrilla');
            $codigoJefe     = $this->input->post('txtCodigo');
            $celularJefe    = $this->input->post('txtCelular');
            $hasPlano       = $this->input->post('radioPlano');
            $observacion    = $this->input->post('inputObservacion');
            $observacionAdi = $this->input->post('inputObservacionAdicional');
            $coorx          = $this->input->post('coorX');
            $coory          = $this->input->post('coorY');

            $arrayTrabajo = array();
            $arrayNivelesCali = array();
            $listaTrabajos = $this->m_bandeja_ficha_tecnica->getTrabajosFichaTecnica();
            foreach($listaTrabajos->result() as $row){
                $datatrans = array();      
                $datatrans['id_ficha_tecnica_trabajo'] = $row->id_ficha_tecnica_trabajo;
                $datatrans['id_ficha_tecnica_tipo_trabajo']  = $this->input->post('selectTrabajo'.$row->id_ficha_tecnica_trabajo);
                $datatrans['cantidad'] = $this->input->post('inputCantidadTrabajo'.$row->id_ficha_tecnica_trabajo);
                $datatrans['observacion'] = strtoupper($this->input->post('inputComentarioTrabajo'.$row->id_ficha_tecnica_trabajo));
                array_push($arrayTrabajo, $datatrans);              
            }            

            $listaNiveles = $this->m_bandeja_ficha_tecnica->getNivelesCalibracion();
            foreach($listaNiveles->result() as $row){
                $datatrans = array();
                $datatrans['id_ficha_tecnica_nivel_calibra']  = $row->id_ficha_tecnica_nivel_calibra;
                $datatrans['opt_recep']  = $this->input->post('opt1_'.$row->id_ficha_tecnica_nivel_calibra);
                $datatrans['opt_tx']  = $this->input->post('opt2_'.$row->id_ficha_tecnica_nivel_calibra);
                $datatrans['ch_30']  = $this->input->post('ch30_'.$row->id_ficha_tecnica_nivel_calibra);
                $datatrans['ch_75']  = $this->input->post('ch75_'.$row->id_ficha_tecnica_nivel_calibra);
                $datatrans['ch_113']  = $this->input->post('ch113_'.$row->id_ficha_tecnica_nivel_calibra);
                $datatrans['snr_ruido']  = $this->input->post('snr_'.$row->id_ficha_tecnica_nivel_calibra);                
                array_push($arrayNivelesCali, $datatrans); 
            } 
            $data = $this->m_bandeja_ficha_tecnica->insertFichaTecnica($coorx, $coory, $itemplan, strtoupper($nombreJefe), strtoupper($codigoJefe), $celularJefe, $hasPlano, strtoupper($observacion), strtoupper($observacionAdi), $arrayTrabajo, $arrayNivelesCali);
            
            $SubProy = $this->input->post('subProy');
            $eecc = $this->input->post('eecc');
            $zonal = $this->input->post('zonal');
            $itemPlan = $this->input->post('itemplanFil');
            $mesEjec = $this->input->post('mes');
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_bandeja_ficha_tecnica->getBandejaFichaTecnica($SubProy,$eecc,$zonal,$itemPlan,$mesEjec));
            
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }    
    */
    function makePDF(){
        $item = (isset($_GET['itm']) ? $_GET['itm'] : '');
        
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
        
        $dataItem = $this->m_bandeja_ficha_tecnica->getInfoItemPlanFichaTecnica($item);
        $dataFicha = $this->m_bandeja_ficha_tecnica->getInfoFichaTecnicaByItemplan($item, FICHA_COAXIAL_GENERICA);
        $tbl ='<img style="width: 100px; heigth:40px" src="'.base_url().'public/img/logo/tdp.png">
            <p style="text-align: center;"><strong>CHECKLIST DE TRABAJOS EN PLANTA INTERNA</strong></p>
            <p style="text-align: center;">&nbsp;</p>
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
            <table style="height: 100%; width: 100%; margin-left: auto; margin-right: auto;" border="1">
            <tbody>
            <tr>
            <th style="width: 20%;">&nbsp;</th>
            <th style="width: 15%;text-align: center;"><strong>CANTIDAD</strong></th>
            <th style="width: 15%;text-align: center;"><strong>TIPO</strong></th>
            <th style="width: 50%;text-align: center;"><strong>OBSERVACIONES</strong></th>
            </tr>';
        $listaTrabajos = $this->m_bandeja_ficha_tecnica->getTrabajosFichatecnicaByItemplan($item, TIPO_fICHA_COAXIAL_GENERICO);
        foreach($listaTrabajos->result() as $row){
            $tbl .='<tr>
            <th style="text-align: left;"><strong>'.$row->descripcion.'</strong></th>
            <th style="text-align: center;">'.$row->cantidad.'</th>
            <th>'.$row->tipo_trabajo.'</th>
            <th>'.$row->observacion.'</th>
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
            <td style="width: 100%;"><strong>Comentario: </strong></td>
            </tr>
            <tr>
            <td>'.$dataFicha['observacion'].'</td>
            </tr>
            </tbody>
            </table>
            <p><span style="text-decoration: underline;"><strong>NIVELES DE CALIBRACION</strong></span></p>
            <table style="height: 100%; width: 100%;" border="1">
            <thead class="thead-default">
            <tr role="row">
                <th style="text-align: center;" colspan="1">&nbsp;</th>
                <th style="text-align: center;" colspan="2">POT. OPT</th>
                <th style="text-align: center;" colspan="1">CH 30</th>
                <th style="text-align: center;" colspan="1">CH 75</th>
                <th style="text-align: center;" colspan="1">CH 113</th>
                <th style="text-align: center;" colspan="1">SNR - RUIDO</th>
            </tr>
            <tr role="row">
                <th style="text-align: center;" colspan="1">&nbsp;</th>
                <th style="text-align: center;" colspan="1">0 - 3 DB</th>
                <th style="text-align: center;" colspan="1">3 - 7 DB</th>
                <th style="text-align: center;" colspan="1">36 - 39 DB</th>
                <th style="text-align: center;" colspan="1">40 - 42 DB</th>
                <th style="text-align: center;" colspan="1">44 - 45 DB</th>
                <th style="text-align: center;" colspan="1">&gt; 32 DB</th>
            </tr>
            </thead>
            <tbody>';
        $listaNivCalibra = $this->m_bandeja_ficha_tecnica->getNivelesCalibracionByItemplan($item);
        foreach($listaNivCalibra->result() as $row){
            $tbl .=' <tr>
                        <th style="text-align: left;"><strong>'.$row->descripcion.'</strong></th>
                        <th style="text-align: center;">'.$row->opt_recep.'</th>
                        <th style="text-align: center;">'.$row->opt_tx.'</th>
                        <th style="text-align: center;">'.$row->ch_30.'</th>
                        <th style="text-align: center;">'.$row->ch_75.'</th>
                        <th style="text-align: center;">'.$row->ch_113.'</th>
                        <th style="text-align: center;">'.$row->snr_ruido.'</th>
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
    
    function getFichaToEvaluacionFO(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $itemplan = $this->input->post('itemplan');
            $data['dataHTML']  = $this->makeHTMLFIchaToEvaluacionFO($itemplan);
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function makeHTMLFIchaToEvaluacionFO($item){
        $dataItem = $this->m_bandeja_ficha_tecnica->getInfoItemPlanFichaTecnica($item);
        $dataFicha = $this->m_bandeja_ficha_tecnica->getInfoFichaTecnicaByItemplan($item, FICHA_FO_FTTH_Y_OP);
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
                                    <td style="width: 25%;"><strong>Fecha Fin:</strong>'.$dataItem['fechaPreLiquidacion'].'</td>
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
                    <table style="height: 100%; width: 100%; margin-left: auto; margin-right: auto;" border="1">
                    <tbody>
                    <tr style="background:#e4e4e4">
                        <th style="width: 10%;">&nbsp;</th>
                        <th style="width: 5%;text-align: center;"><strong>CANTIDAD</strong></th>
                        <th style="width: 5%;text-align: center;"><strong>TIPO</strong></th>
                        <th style="width: 35%;text-align: center;"><strong>OBSERVACIONES</strong></th>
                        <th style="width: 5%;text-align: center;"><strong>CHECK</strong></th>
                        <th style="width: 40%;text-align: center;"><strong>OBSERVACIONES</strong></th>
                    </tr>';
        $listaTrabajos = $this->m_bandeja_ficha_tecnica->getTrabajosFichatecnicaByItemplan($item, FICHA_FO_FTTH_Y_OP);
        foreach($listaTrabajos->result() as $row){
            $tbl .='<tr>
                    <th style="text-align: left;"><strong>'.$row->descripcion.'</strong></th>
                    <th style="text-align: center;">'.$row->cantidad.'</th>
                    <th style="text-align: center;">'.$row->tipo_trabajo.'</th>
                    <th>'.$row->observacion.'</th>
                    <th style="text-align: center;"><label class="custom-control custom-checkbox"><input name="checkTrabajos" value="'.$row->id_ficha_tecnica_x_tipo_trabajo.'" type="checkbox" class="custom-control-input"><span class="custom-control-indicator"></span></label></th>
                    <th><input id="inputComentarioTrabajo'.$row->id_ficha_tecnica_x_tipo_trabajo.'" name="inputComentarioTrabajo'.$row->id_ficha_tecnica_x_tipo_trabajo.'" type="text" class="form-control form-control-sm"></th>
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
                    <td style="width: 100%;"><strong>Comentario: </strong></td>
                    </tr>
                    <tr>
                    <td>'.$dataFicha['observacion'].'</td>
                    </tr>
                    </tbody>
                    </table>
                    <p><span style="text-decoration: underline;"><strong>MEDIDAS REFLECTOMETRICAS</strong></span></p>
                    
                    <table style="height: 100%; width: 100%;" border="1">
            <thead class="thead-default">
                <tr role="row">                           
                    <th style="width: 68%; text-align: center;" colspan="9"></th>
                    <th style="width: 16%; text-align: center;" colspan="2"><strong>1310 nm</strong></th>
                    <th style="width: 16%; TEXT-ALIGN: center;" colspan="2"><strong>1550 nm</strong></th>
                  	                                                                                   
               </tr>
               <tr role="row">      
                	<th style="width: 4%;" colspan="1"></th> 
                    <th style="width: 8%;" colspan="1"><strong>NODO</strong></th> 
                    <th style="width: 8%;" colspancolspan="1"><strong>ODF</strong></th>                          
                    <th style="width: 8%;" colspancolspan="1"><strong>CABLE PRIM</strong></th>
                    <th style="width: 8%;" colspancolspan="1"><strong>NÂ° FIBRA</strong></th>
                    <th style="width: 8%;" colspancolspan="1"><strong>DIVICAU</strong></th>
                    <th style="width: 8%;" colspancolspan="1"><strong>DIVISOR</strong></th>
                    <th style="width: 8%;" colspancolspan="1"><strong>CTO</strong></th>      
                    <th style="width: 8%;" colspancolspan="1"><strong>DISTANCIA (KM)</strong></th>
                    <th style="width: 8%;" colspancolspan="1"><strong>Atten. Total(db) >23db</strong></th>
                    <th style="width: 8%;" colspancolspan="1"><strong>Reflectancia(ORL) >40 db</strong></th>               
                    <th style="width: 8%;" colspancolspan="1"><strong>Atten. Total(db) >23db</strong></th>
                    <th style="width: 8%;" colspancolspan="1"><strong>Reflectancia(ORL) >40 db</strong></th> 
                </tr>
            </thead>
            <tbody>';
        $listaNivCalibra = $this->m_bandeja_ficha_tecnica->getMedidasReflectometricasByItemplan($item);
        $cont = 1;
        foreach($listaNivCalibra->result() as $row){
            $tbl .=' <tr>
                        <th style="width: 4%; text-align: left;"><strong>'.$cont.'</strong></th>
                        <th style="width: 8%; text-align: center;">'.$row->nodo.'</th>
                        <th style="width: 8%; text-align: center;">'.$row->odf.'</th>
                        <th style="width: 8%; text-align: center;">'.$row->cable_prim.'</th>
                        <th style="width: 8%; text-align: center;">'.$row->num_fibra.'</th>
                        <th style="width: 8%; text-align: center;">'.$row->divicau.'</th>
                        <th style="width: 8%; text-align: center;">'.$row->divisor.'</th>
                        <th style="width: 8%; text-align: center;">'.$row->cto.'</th>
                        <th style="width: 8%; text-align: center;">'.$row->distancia.'</th>
                        <th style="width: 8%; text-align: center;">'.$row->atten_total_1310.'</th>
                        <th style="width: 8%; text-align: center;">'.$row->reflectancia_1310.'</th>
                        <th style="width: 8%; text-align: center;">'.$row->atten_total_1550.'</th>
                        <th style="width: 8%; text-align: center;">'.$row->reflectancia_1550.'</th>
                    </tr>';
            $cont ++;
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
                    </table>
                        <div class="form-group" style="text-align: right;width: 100%;">
                                <div class="col-sm-12">
                                    <button data-item="'.$item.'" data-fic="'.$dataFicha['id_ficha_tecnica'].'" data-acc="2" onclick="validarFic(this)" type="button" class="btn btn-danger">RECHAZAR</button>
                                    <button data-item="'.$item.'" data-fic="'.$dataFicha['id_ficha_tecnica'].'" data-acc="1" onclick="validarFic(this)" type="button" class="btn btn-primary">APROBAR</button>
                                </div>
                            </div>
            </div>
        ';
        return $tbl;
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
    
    function makeHTMLFIchaToEvaluacion($item){
        $dataItem = $this->m_bandeja_ficha_tecnica->getInfoItemPlanFichaTecnica($item);
        $dataFicha = $this->m_bandeja_ficha_tecnica->getInfoFichaTecnicaByItemplan($item, FICHA_COAXIAL_GENERICA);
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
                                    <td style="width: 25%;"><strong>Fecha Fin:</strong>'.$dataItem['fechaPreLiquidacion'].'</td>
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
                    <table style="height: 100%; width: 100%; margin-left: auto; margin-right: auto;" border="1">
                    <tbody>
                    <tr style="background:#e4e4e4">
                        <th style="width: 10%;">&nbsp;</th>
                        <th style="width: 5%;text-align: center;"><strong>CANTIDAD</strong></th>
                        <th style="width: 5%;text-align: center;"><strong>TIPO</strong></th>
                        <th style="width: 35%;text-align: center;"><strong>OBSERVACIONES</strong></th>
                        <th style="width: 5%;text-align: center;"><strong>CHECK</strong></th>
                        <th style="width: 40%;text-align: center;"><strong>OBSERVACIONES</strong></th>
                    </tr>';
                $listaTrabajos = $this->m_bandeja_ficha_tecnica->getTrabajosFichatecnicaByItemplan($item, FICHA_COAXIAL_GENERICA);
                foreach($listaTrabajos->result() as $row){
                    $tbl .='<tr>
                    <th style="text-align: left;"><strong>'.$row->descripcion.'</strong></th>
                    <th style="text-align: center;">'.$row->cantidad.'</th>
                    <th style="text-align: center;">'.$row->tipo_trabajo.'</th>
                    <th>'.$row->observacion.'</th>
                    <th style="text-align: center;"><label class="custom-control custom-checkbox"><input name="checkTrabajos" value="'.$row->id_ficha_tecnica_x_tipo_trabajo.'" type="checkbox" class="custom-control-input"><span class="custom-control-indicator"></span></label></th>
                    <th><input id="inputComentarioTrabajo'.$row->id_ficha_tecnica_x_tipo_trabajo.'" name="inputComentarioTrabajo'.$row->id_ficha_tecnica_x_tipo_trabajo.'" type="text" class="form-control form-control-sm"></th>
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
                    <td style="width: 100%;"><strong>Comentario: </strong></td>
                    </tr>
                    <tr>
                    <td>'.$dataFicha['observacion'].'</td>
                    </tr>
                    </tbody>
                    </table>
                    <p><span style="text-decoration: underline;"><strong>NIVELES DE CALIBRACION</strong></span></p>
                    <table style="height: 100%; width: 100%;" border="1">
                    <thead class="thead-default">
                    <tr role="row" style="background:#e4e4e4">
                        <th style="text-align: center;" colspan="1">&nbsp;</th>
                        <th style="text-align: center;" colspan="2">POT. OPT</th>
                        <th style="text-align: center;" colspan="1">CH 30</th>
                        <th style="text-align: center;" colspan="1">CH 75</th>
                        <th style="text-align: center;" colspan="1">CH 113</th>
                        <th style="text-align: center;" colspan="1">SNR - RUIDO</th>
                        <th style="text-align: center;" colspan="2">VALIDACION</th>
                    </tr>
                    <tr role="row" style="background:#e4e4e4">
                        <th style="text-align: center;" colspan="1">&nbsp;</th>
                        <th style="text-align: center;" colspan="1">0 - 3 DB</th>
                        <th style="text-align: center;" colspan="1">3 - 7 DB</th>
                        <th style="text-align: center;" colspan="1">36 - 39 DB</th>
                        <th style="text-align: center;" colspan="1">40 - 42 DB</th>
                        <th style="text-align: center;" colspan="1">44 - 45 DB</th>
                        <th style="text-align: center;" colspan="1">&gt; 32 DB</th>
                        <th style="text-align: center;" colspan="1"><strong>CHECK</strong></th>
                        <th style="text-align: center;" colspan="1"><strong>OBSERVACIONES</strong></th>
                    </tr>
                    </thead>
                    <tbody>';
                $listaNivCalibra = $this->m_bandeja_ficha_tecnica->getNivelesCalibracionByItemplan($item);
                foreach($listaNivCalibra->result() as $row){
                    $tbl .=' <tr>
                                <th style="text-align: left;"><strong>'.$row->descripcion.'</strong></th>
                                <th style="text-align: center;">'.$row->opt_recep.'</th>
                                <th style="text-align: center;">'.$row->opt_tx.'</th>
                                <th style="text-align: center;">'.$row->ch_30.'</th>
                                <th style="text-align: center;">'.$row->ch_75.'</th>
                                <th style="text-align: center;">'.$row->ch_113.'</th>
                                <th style="text-align: center;">'.$row->snr_ruido.'</th>
                                <th style="text-align: center;"><label class="custom-control custom-checkbox"><input name="checkNiveles" value="'.$row->id_ficha_tecnica_x_nivel_calibra.'" type="checkbox" class="custom-control-input"><span class="custom-control-indicator"></span></label></th>
                                <th><input id="inputComentarioNivel'.$row->id_ficha_tecnica_x_nivel_calibra.'" name="inputComentarioNivel'.$row->id_ficha_tecnica_x_nivel_calibra.'" type="text" class="form-control form-control-sm"></th>
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
                    </table>
                        <div class="form-group" style="text-align: right;width: 100%;">
                                <div class="col-sm-12">
                                    <button data-item="'.$item.'" data-fic="'.$dataFicha['id_ficha_tecnica'].'" data-acc="2" onclick="validarFic(this)" type="button" class="btn btn-danger">RECHAZAR</button>
                                    <button data-item="'.$item.'" data-fic="'.$dataFicha['id_ficha_tecnica'].'" data-acc="1" onclick="validarFic(this)" type="button" class="btn btn-primary">APROBAR</button>
                                </div>
                            </div>
            </div>                         
        ';
        return $tbl;
    }
    
    function saveValidacionFicha(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $trabajos = $this->input->post('checksTrabajos');
            $niveles = $this->input->post('checksNiveles');
            $estado  = $this->input->post('estado');
            $idFicha  = $this->input->post('ficha');
            $itemplan  = $this->input->post('itemplan');
            $listaEstacion = $this->m_bandeja_ficha_tecnica->getEstacionPorcentajeByItemPlanAll($itemplan);
            $arrayInsert =  array();
            $arrayUpdate = array();
            
            foreach($listaEstacion->result() as $row){
                $datatrans = array();
                if($row->idItemplanEstacion == NULL){
                    $datatrans['porcentaje'] = '100';
                    $datatrans['idEstacion'] = $row->idEstacion;
                    $datatrans['itemplan'] = $row->itemplan;
                    array_push($arrayInsert, $datatrans);
                }else{
                    $datatrans['idItemplanEstacion'] = $row->idItemplanEstacion;
                    $datatrans['porcentaje'] = '100';
                    array_push($arrayUpdate, $datatrans);
                }
            }
            
            $data = $this->m_bandeja_ficha_tecnica->saveFichaTecnicaValidacion($idFicha, $estado, json_decode($trabajos), json_decode($niveles),$itemplan, $arrayInsert, $arrayUpdate);          
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_bandeja_ficha_tecnica->getBandejaFichaTecnicaEvaluacion('','','','',''));
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
            $data['dataHTML']  = $this->makeHTMLViewFIchaEvaluacion($itemplan);
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    
    function makeHTMLViewFIchaEvaluacion($item){
        $dataItem = $this->m_bandeja_ficha_tecnica->getInfoItemPlanFichaTecnica($item);
        $dataFicha = $this->m_bandeja_ficha_tecnica->getInfoFichaTecnicaByItemplan($item, FICHA_COAXIAL_GENERICA);
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
                                    <td style="width: 25%;"><strong>Fecha Fin:</strong>'.$dataItem['fechaPreLiquidacion'].'</td>
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
                    <div class="form-group col-sm-12 table-responsive">
                        <table style="height: 100%; width: 100%; margin-left: auto; margin-right: auto;" border="1">
                        <tbody>
                        <tr style="background:#e4e4e4">
                            <th style="width: 10%;">&nbsp;</th>
                            <th style="width: 5%;text-align: center;"><strong>CANTIDAD</strong></th>
                            <th style="width: 5%;text-align: center;"><strong>TIPO</strong></th>
                            <th style="width: 35%;text-align: center;"><strong>OBSERVACIONES</strong></th>
                            <th style="width: 5%;text-align: center;"><strong>CHECK</strong></th>
                            <th style="width: 40%;text-align: center;"><strong>OBSERVACIONES</strong></th>
                        </tr>';
        $listaTrabajos = $this->m_bandeja_ficha_tecnica->getTrabajosFichatecnicaByItemplan($item, FICHA_COAXIAL_GENERICA);
                            foreach($listaTrabajos->result() as $row){
                                $tbl .='<tr>
                                        <th style="text-align: left;"><strong>'.$row->descripcion.'</strong></th>
                                        <th style="text-align: center;">'.$row->cantidad.'</th>
                                        <th style="text-align: center;">'.$row->tipo_trabajo.'</th>
                                        <th>'.$row->observacion.'</th>
                                        <th style="text-align: center;"><label class="custom-control custom-checkbox"><input '.(($row->flg_validado == '1') ? 'checked' : '').' disabled type="checkbox" class="custom-control-input"><span class="custom-control-indicator"></span></label></th>
                                        <th>'.utf8_decode($row->comentario_vali).'</th>
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
                    </div>
                    <div class="form-group col-sm-12">
                        <p><span style="text-decoration: underline;"><strong>NIVELES DE CALIBRACION</strong></span></p>
                        <table style="height: 100%; width: 100%;" border="1">
                        <thead class="thead-default">
                        <tr role="row" style="background:#e4e4e4">
                            <th style="text-align: center;" colspan="1">&nbsp;</th>
                            <th style="text-align: center;" colspan="2">POT. OPT</th>
                            <th style="text-align: center;" colspan="1">CH 30</th>
                            <th style="text-align: center;" colspan="1">CH 75</th>
                            <th style="text-align: center;" colspan="1">CH 113</th>
                            <th style="text-align: center;" colspan="1">SNR - RUIDO</th>
                            <th style="text-align: center;" colspan="2">VALIDACION</th>
                        </tr>
                        <tr role="row" style="background:#e4e4e4">
                            <th style="text-align: center;" colspan="1">&nbsp;</th>
                            <th style="text-align: center;" colspan="1">0 - 3 DB</th>
                            <th style="text-align: center;" colspan="1">3 - 7 DB</th>
                            <th style="text-align: center;" colspan="1">36 - 39 DB</th>
                            <th style="text-align: center;" colspan="1">40 - 42 DB</th>
                            <th style="text-align: center;" colspan="1">44 - 45 DB</th>
                            <th style="text-align: center;" colspan="1">&gt; 32 DB</th>
                            <th style="text-align: center;" colspan="1"><strong>CHECK</strong></th>
                            <th style="text-align: center;" colspan="1"><strong>OBSERVACIONES</strong></th>
                        </tr>
                        </thead>
                        <tbody>';
            $listaNivCalibra = $this->m_bandeja_ficha_tecnica->getNivelesCalibracionByItemplan($item);
            foreach($listaNivCalibra->result() as $row){
                $tbl .=' <tr>
                                    <th style="text-align: left;"><strong>'.$row->descripcion.'</strong></th>
                                    <th style="text-align: center;">'.$row->opt_recep.'</th>
                                    <th style="text-align: center;">'.$row->opt_tx.'</th>
                                    <th style="text-align: center;">'.$row->ch_30.'</th>
                                    <th style="text-align: center;">'.$row->ch_75.'</th>
                                    <th style="text-align: center;">'.$row->ch_113.'</th>
                                    <th style="text-align: center;">'.$row->snr_ruido.'</th>
                                    <th style="text-align: center;"><label class="custom-control custom-checkbox"><input '.(($row->flg_validado == '1') ? 'checked' : '').' disabled type="checkbox" class="custom-control-input"><span class="custom-control-indicator"></span></label></th>
                                    <th>'.utf8_decode($row->comentario_vali).'</th>
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
                        <td style="width: 100%;"><strong>Comentario Adicional: </strong></td>
                        </tr>
                        <tr>
                        <td>'.$dataFicha['observacion_adicional'].'</td>
                        </tr>
                        </tbody>
                        </table>
                    </div>
            </div>
        ';
        return $tbl;
    }
    
    function viewFichaEvaluacionSI(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $itemplan = $this->input->post('itemplan');
            $data['dataHTML']  = $this->makeHTMLViewFIchaEvaluacionSI($itemplan);
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function makeHTMLViewFIchaEvaluacionSI($item){
        $dataItem = $this->m_bandeja_ficha_tecnica->getInfoItemPlanFichaTecnica($item);
        $dataFicha = $this->m_bandeja_ficha_tecnica->getInfoFichaTecnicaByItemplan($item, FICHA_FO_SISEGOS_SMALLCELL_EBC);
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
                                    <td style="width: 25%;"><strong>Serie Troba:</strong>'.$dataItem['serie'].'</td>
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
                    <div class="form-group col-sm-12 table-responsive">
                        <table style="width: 100%; margin-left: auto; margin-right: auto;" class="table table-hover display  pb-30 table-striped table-bordered nowrap dataTable no-footer">
                        <tbody>
                        <tr style="background:#e4e4e4">
                            <th style="width: 10%;">&nbsp;</th>
                            <th style="width: 5%;text-align: center;"><strong>CANTIDAD</strong></th>
                            <th style="width: 5%;text-align: center;"><strong>TIPO</strong></th>
                            <th style="width: 35%;text-align: center;"><strong>OBSERVACIONES</strong></th>
                            <th style="width: 5%;text-align: center;"><strong>CHECK</strong></th>
                            <th style="width: 40%;text-align: center;"><strong>OBSERVACIONES</strong></th>
                        </tr>';
        $listaTrabajos = $this->m_bandeja_ficha_tecnica->getTrabajosFichatecnicaByItemplan($item, FICHA_FO_SISEGOS_SMALLCELL_EBC);
        foreach($listaTrabajos->result() as $row){
            $tbl .='<tr>
                                        <th style="text-align: left;"><strong>'.$row->descripcion.'</strong></th>
                                        <th style="text-align: center;">'.$row->cantidad.'</th>
                                        <th style="text-align: center;">'.$row->tipo_trabajo.'</th>
                                        <th>'.$row->observacion.'</th>
                                        <th style="text-align: center;"><label class="custom-control custom-checkbox"><input '.(($row->flg_validado == '1') ? 'checked' : '').' disabled type="checkbox" class="custom-control-input"><span class="custom-control-indicator"></span></label></th>
                                        <th>'.utf8_decode($row->comentario_vali).'</th>
                                        </tr>';
        }
                $tbl .='</tbody>
                    </table>
                </div>
                    <div class="form-group col-sm-12">
                        <table style="height: 100%; width: 100%;">
                        <tbody>                        
                        <tr>
                        <td style="width: 100%;"><strong>Comentario: </strong></td>
                        </tr>
                        <tr>
                        <td>'.$dataFicha['observacion'].'</td>
                        </tr>
                        </tbody>
                        </table>
                    </div>';/*

                    <div class="form-group col-sm-12">                            
                    <p><span style="text-decoration: underline;"><strong>2)Medidas Reflectometricas End To End:</strong></span></p>';
                    $tbl .='<table style="height: 100%; width: 100%;" border="1">
                                <thead class="thead-default">
                                   <tr role="row">
                                    	<th style="width: 4%; TEXT-ALIGN: center;" colspan="1"></th> 
                                        <th style="width: 16%; TEXT-ALIGN: center;" colspan="1"><strong>CABLE</strong></th> 
                                        <th style="width: 16%; TEXT-ALIGN: center;" colspan="1"><strong>URA</strong></th>                          
                                        <th style="width: 16%; TEXT-ALIGN: center;" colspan="1"><strong>ASIGNACION ORIGEN</strong></th>
                                        <th style="width: 16%; TEXT-ALIGN: center;" colspan="1"><strong>ASIGNACION EXTREMO</strong></th>
                                        <th style="width: 16%; TEXT-ALIGN: center;" colspan="1"><strong>DISTANCIA OPTICA(KM)</strong></th>   
                                        <th style="width: 16%; TEXT-ALIGN: center;" colspan="1"><strong>ATT TOTAL(DB)</strong></th>
                                    </tr>
                                </thead>
                                <tbody>';
                            $medEndToEnd = $this->m_bandeja_ficha_tecnica->getMedReflecEndToEnd($item);
                            $cont = 1;
                            foreach($medEndToEnd->result() as $row){
                                $tbl .=' <tr>
                                            <th style="width: 4%; text-align: left;"><strong>'.$cont.'</strong></th>
                                            <th style="width: 16%; text-align: center;">'.$row->cable.'</th>
                                            <th style="width: 16%; text-align: center;">'.$row->ura.'</th>
                                            <th style="width: 16%; text-align: center;">'.$row->asig_origen.'</th>
                                            <th style="width: 16%; text-align: center;">'.$row->asig_extremo.'</th>
                                            <th style="width: 16%; text-align: center;">'.$row->distancia_optica.'</th>
                                            <th style="width: 16%; text-align: center;">'.$row->att_total.'</th>
                                        </tr>';
                                $cont ++;
                            }
                            $tbl .='</tbody>
                                </table>
                    </div>
<div class="form-group col-sm-12">    <br> 
                     <p><span style="text-decoration: underline;"><strong>3)Medidas De Potencia : Atenuacion Max : >-11db En CTO / >-12db Cliente Sisego/Small Cell / EBC:</strong></span></p>';
                    $tbl .='<table style="height: 100%; width: 100%;" border="1">
                                <thead class="thead-default">
                                    <tr role="row">
                                   		<th style="width: 4%; text-align: center;" colspan="1"></th>    
                                        <th style="width: 19%; text-align: center;" colspan="1"><strong>Equipo Origen</strong></th>
                                        <th style="width: 10%; text-align: center;" colspan="1"><strong>URA</strong></th>    
                                        <th style="width: 10%; text-align: center;" colspan="1"><strong>Long. FO /Ura - CTO</strong></th> 
                                        <th style="width: 29%; text-align: center;" colspan="2"><strong>CTO / NAP</strong></th> 
                                        <th style="width: 18%; text-align: center;" colspan="1"><strong>Long. FO Acomet.</strong></th> 
                                        <th style="width: 10%; text-align: center;" colspan="1"><strong>CLIENTE</strong></th> 
                                   </tr>
                                  
                                   <tr role="row">
                                    	<th style="width: 4%; TEXT-ALIGN: center;" colspan="1"></th> 
                                        <th style="width: 19%; TEXT-ALIGN: center;" colspan="1"><strong>PUERTO ORIGEN</strong></th> 
                                        <th style="width: 10%; TEXT-ALIGN: center;" colspan="1"><strong>INPUT (DB)</strong></th>                          
                                        <th style="width: 10%; TEXT-ALIGN: center;" colspan="1"><strong>APROX. (KM)</strong></th>
                                        <th style="width: 19%; TEXT-ALIGN: center;" colspan="1"><strong>N° CTO CUENTA</strong></th>
                                        <th style="width: 10%; TEXT-ALIGN: center;" colspan="1"><strong>OUTPUT (dB)</strong></th>   
                                        <th style="width: 18%; TEXT-ALIGN: center;" colspan="1"><strong>APROX. (KM)</strong></th>
                                        <th style="width: 10%; TEXT-ALIGN: center;" colspan="1"><strong>OUTPUT (dB)</strong></th>                            
                                    </tr>
                                </thead>
                                <tbody>';
                            $medidasPotencia = $this->m_bandeja_ficha_tecnica->getMedidasDePotencia($item);
                            $cont = 1;
                            foreach($medidasPotencia->result() as $row){
                                $tbl .=' <tr>
                                            <th style="width: 4%; text-align: left;"><strong>'.$cont.'</strong></th>
                                            <th style="width: 19%; text-align: center;">'.$row->puerto_origen.'</th>
                                            <th style="width: 10%; text-align: center;">'.$row->ura_db_input.'</th>
                                            <th style="width: 10%; text-align: center;">'.$row->long_ura_cto_aprox.'</th>
                                            <th style="width: 19%; text-align: center;">'.$row->cro_cto_cuenta.'</th>
                                            <th style="width: 10%; text-align: center;">'.$row->cto_nap_output_db.'</th>
                                            <th style="width: 18%; text-align: center;">'.$row->long_acomet_aprox.'</th>
                                            <th style="width: 10%; text-align: center;">'.$row->output_db_cliente.'</th>                       
                                        </tr>';
                                $cont ++;
                            }
                            $tbl .='</tbody>
                                </table>
                    </div>
                    <div class="form-group col-sm-12"><br>
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
            </div>
        ';*/
        return $tbl;
    }
    
    function viewFichaEvaluacionFO(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $itemplan = $this->input->post('itemplan');
            $data['dataHTML']  = $this->makeHTMLViewFIchaEvaluacionFO($itemplan);
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function makeHTMLViewFIchaEvaluacionFO($item){
        $dataItem = $this->m_bandeja_ficha_tecnica->getInfoItemPlanFichaTecnica($item);
        $dataFicha = $this->m_bandeja_ficha_tecnica->getInfoFichaTecnicaByItemplan($item, FICHA_FO_FTTH_Y_OP);
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
                                    <td style="width: 25%;"><strong>Fecha Fin:</strong>'.$dataItem['fechaPreLiquidacion'].'</td>
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
                    <div class="form-group col-sm-12 table-responsive">
                        <table style="height: 100%; width: 100%; margin-left: auto; margin-right: auto;" border="1">
                        <tbody>
                        <tr style="background:#e4e4e4">
                            <th style="width: 10%;">&nbsp;</th>
                            <th style="width: 5%;text-align: center;"><strong>CANTIDAD</strong></th>
                            <th style="width: 5%;text-align: center;"><strong>TIPO</strong></th>
                            <th style="width: 35%;text-align: center;"><strong>OBSERVACIONES</strong></th>
                            <th style="width: 5%;text-align: center;"><strong>CHECK</strong></th>
                            <th style="width: 40%;text-align: center;"><strong>OBSERVACIONES</strong></th>
                        </tr>';
        $listaTrabajos = $this->m_bandeja_ficha_tecnica->getTrabajosFichatecnicaByItemplan($item, FICHA_FO_FTTH_Y_OP);
        foreach($listaTrabajos->result() as $row){
            $tbl .='<tr>
                                        <th style="text-align: left;"><strong>'.$row->descripcion.'</strong></th>
                                        <th style="text-align: center;">'.$row->cantidad.'</th>
                                        <th style="text-align: center;">'.$row->tipo_trabajo.'</th>
                                        <th>'.$row->observacion.'</th>
                                        <th style="text-align: center;"><label class="custom-control custom-checkbox"><input '.(($row->flg_validado == '1') ? 'checked' : '').' disabled type="checkbox" class="custom-control-input"><span class="custom-control-indicator"></span></label></th>
                                        <th>'.utf8_decode($row->comentario_vali).'</th>
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
                    </div>
                    <div class="form-group col-sm-12">
                        <p><span style="text-decoration: underline;"><strong>NIVELES DE CALIBRACION</strong></span></p>
                        <table style="height: 100%; width: 100%;" border="1">
            <thead class="thead-default">
                <tr role="row">                           
                    <th style="width: 68%; text-align: center;" colspan="9"></th>
                    <th style="width: 16%; text-align: center;" colspan="2"><strong>1310 nm</strong></th>
                    <th style="width: 16%; TEXT-ALIGN: center;" colspan="2"><strong>1550 nm</strong></th>
                  	                                                                                   
               </tr>
               <tr role="row">      
                	<th style="width: 4%;" colspan="1"></th> 
                    <th style="width: 8%;" colspan="1"><strong>NODO</strong></th> 
                    <th style="width: 8%;" colspancolspan="1"><strong>ODF</strong></th>                          
                    <th style="width: 8%;" colspancolspan="1"><strong>CABLE PRIM</strong></th>
                    <th style="width: 8%;" colspancolspan="1"><strong>NÂ° FIBRA</strong></th>
                    <th style="width: 8%;" colspancolspan="1"><strong>DIVICAU</strong></th>
                    <th style="width: 8%;" colspancolspan="1"><strong>DIVISOR</strong></th>
                    <th style="width: 8%;" colspancolspan="1"><strong>CTO</strong></th>      
                    <th style="width: 8%;" colspancolspan="1"><strong>DISTANCIA (KM)</strong></th>
                    <th style="width: 8%;" colspancolspan="1"><strong>Atten. Total(db) >23db</strong></th>
                    <th style="width: 8%;" colspancolspan="1"><strong>Reflectancia(ORL) >40 db</strong></th>               
                    <th style="width: 8%;" colspancolspan="1"><strong>Atten. Total(db) >23db</strong></th>
                    <th style="width: 8%;" colspancolspan="1"><strong>Reflectancia(ORL) >40 db</strong></th> 
                </tr>
            </thead>
            <tbody>';
        $listaNivCalibra = $this->m_bandeja_ficha_tecnica->getMedidasReflectometricasByItemplan($item);
        $cont = 1;
        foreach($listaNivCalibra->result() as $row){
            $tbl .=' <tr>
                        <th style="width: 4%; text-align: left;"><strong>'.$cont.'</strong></th>
                        <th style="width: 8%; text-align: center;">'.$row->nodo.'</th>
                        <th style="width: 8%; text-align: center;">'.$row->odf.'</th>
                        <th style="width: 8%; text-align: center;">'.$row->cable_prim.'</th>
                        <th style="width: 8%; text-align: center;">'.$row->num_fibra.'</th>
                        <th style="width: 8%; text-align: center;">'.$row->divicau.'</th>
                        <th style="width: 8%; text-align: center;">'.$row->divisor.'</th>
                        <th style="width: 8%; text-align: center;">'.$row->cto.'</th>
                        <th style="width: 8%; text-align: center;">'.$row->distancia.'</th>
                        <th style="width: 8%; text-align: center;">'.$row->atten_total_1310.'</th>
                        <th style="width: 8%; text-align: center;">'.$row->reflectancia_1310.'</th>
                        <th style="width: 8%; text-align: center;">'.$row->atten_total_1550.'</th>
                        <th style="width: 8%; text-align: center;">'.$row->reflectancia_1550.'</th>
                    </tr>';
            $cont ++;
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
                        <td style="width: 100%;"><strong>Comentario Adicional: </strong></td>
                        </tr>
                        <tr>
                        <td>'.$dataFicha['observacion_adicional'].'</td>
                        </tr>
                        </tbody>
                        </table>
                    </div>
            </div>
        ';
        return $tbl;
    }
    
    function makePDFEvaluacion(){
        $item = (isset($_GET['itm']) ? $_GET['itm'] : '');
        
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
        $dataFicha = $this->m_bandeja_ficha_tecnica->getInfoFichaTecnicaByItemplan($item);
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
        $listaTrabajos = $this->m_bandeja_ficha_tecnica->getTrabajosFichatecnicaByItemplan($item);
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
            <table style="font-size: small; height: 100%; width: 100%;" border="1">
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
    
    function makeHTMLFIchaToEvaluacionSisegos($item){
        $dataItem = $this->m_bandeja_ficha_tecnica->getInfoItemPlanFichaTecnica($item);
        $dataFicha = $this->m_bandeja_ficha_tecnica->getInfoFichaTecnicaByItemplan($item, FICHA_FO_SISEGOS_SMALLCELL_EBC);
        $tbl ='
            
                <div class="row">
                    <div class="form-group col-sm-12">
                       <table style="height: 100%; width: 100%;">
                            <tbody>
                                <tr>
                                    <td style="width: 30%;"><strong>Itemplan: </strong>'.$dataItem['itemplan'].'</td>
                                    <td style="width: 45%;"><strong>Sub Proyecto: </strong>'.$dataItem['subProyectoDesc'].'</td>
                                    <td style="width: 25%;"><strong>Sisego:</strong>'.$dataItem['indicador'].'</td>
                                    
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
                                    <td style="width: 25%;"><strong>Serie Troba: </strong>'.$dataItem['serie'].'</td>
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
                    <table style="height: 100%; width: 100%; margin-left: auto; margin-right: auto;" border="1">
                    <tbody>
                    <tr style="background:#e4e4e4">
                        <th style="width: 10%;">&nbsp;</th>
                        <th style="width: 5%;text-align: center;"><strong>CANTIDAD</strong></th>
                        <th style="width: 5%;text-align: center;"><strong>TIPO</strong></th>
                        <th style="width: 35%;text-align: center;"><strong>OBSERVACIONES</strong></th>
                        <th style="width: 5%;text-align: center;"><strong>CHECK</strong></th>
                        <th style="width: 40%;text-align: center;"><strong>OBSERVACIONES</strong></th>
                    </tr>';
        $listaTrabajos = $this->m_bandeja_ficha_tecnica->getTrabajosFichatecnicaByItemplan($item, FICHA_FO_SISEGOS_SMALLCELL_EBC);
        foreach($listaTrabajos->result() as $row){
            $tbl .='<tr>
                    <th style="text-align: left;"><strong>'.$row->descripcion.'</strong></th>
                    <th style="text-align: center;">'.$row->cantidad.'</th>
                    <th style="text-align: center;">'.$row->tipo_trabajo.'</th>
                    <th>'.$row->observacion.'</th>
                    <th style="text-align: center;"><label class="custom-control custom-checkbox"><input name="checkTrabajos" value="'.$row->id_ficha_tecnica_x_tipo_trabajo.'" type="checkbox" class="custom-control-input"><span class="custom-control-indicator"></span></label></th>
                    <th><input id="inputComentarioTrabajo'.$row->id_ficha_tecnica_x_tipo_trabajo.'" name="inputComentarioTrabajo'.$row->id_ficha_tecnica_x_tipo_trabajo.'" type="text" class="form-control form-control-sm"></th>
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
                    <td style="width: 100%;"><strong>Comentario: </strong></td>
                    </tr>
                    <tr>
                    <td>'.$dataFicha['observacion'].'</td>
                    </tr>
                    </tbody>
                    </table>';/*
<p><span style="text-decoration: underline;"><strong>2)Medidas Reflectometricas End To End:</strong></span></p>';
$tbl .='<table style="height: 100%; width: 100%;" border="1">
            <thead class="thead-default">
               <tr role="row">
                	<th style="width: 4%; TEXT-ALIGN: center;" colspan="1"></th> 
                    <th style="width: 16%; TEXT-ALIGN: center;" colspan="1"><strong>CABLE</strong></th> 
                    <th style="width: 16%; TEXT-ALIGN: center;" colspan="1"><strong>URA</strong></th>                          
                    <th style="width: 16%; TEXT-ALIGN: center;" colspan="1"><strong>ASIGNACION ORIGEN</strong></th>
                    <th style="width: 16%; TEXT-ALIGN: center;" colspan="1"><strong>ASIGNACION EXTREMO</strong></th>
                    <th style="width: 16%; TEXT-ALIGN: center;" colspan="1"><strong>DISTANCIA OPTICA(KM)</strong></th>   
                    <th style="width: 16%; TEXT-ALIGN: center;" colspan="1"><strong>ATT TOTAL(DB)</strong></th>
                </tr>
            </thead>
            <tbody>';
        $medEndToEnd = $this->m_bandeja_ficha_tecnica->getMedReflecEndToEnd($item);
        $cont = 1;
        foreach($medEndToEnd->result() as $row){
            $tbl .=' <tr>
                        <th style="width: 4%; text-align: left;"><strong>'.$cont.'</strong></th>
                        <th style="width: 16%; text-align: center;">'.$row->cable.'</th>
                        <th style="width: 16%; text-align: center;">'.$row->ura.'</th>
                        <th style="width: 16%; text-align: center;">'.$row->asig_origen.'</th>
                        <th style="width: 16%; text-align: center;">'.$row->asig_extremo.'</th>
                        <th style="width: 16%; text-align: center;">'.$row->distancia_optica.'</th>
                        <th style="width: 16%; text-align: center;">'.$row->att_total.'</th>
                    </tr>';
            $cont ++;
        }
        $tbl .='</tbody>
            </table>
 <p><span style="text-decoration: underline;"><strong>3)Medidas De Potencia : Atenuacion Max : >-11db En CTO / >-12db Cliente Sisego/Small Cell / EBC:</strong></span></p>';
$tbl .='<table style="height: 100%; width: 100%;" border="1">
            <thead class="thead-default">
                <tr role="row">
               		<th style="width: 4%; text-align: center;" colspan="1"></th>    
                    <th style="width: 19%; text-align: center;" colspan="1"><strong>Equipo Origen</strong></th>
                    <th style="width: 10%; text-align: center;" colspan="1"><strong>URA</strong></th>    
                    <th style="width: 10%; text-align: center;" colspan="1"><strong>Long. FO /Ura - CTO</strong></th> 
                    <th style="width: 29%; text-align: center;" colspan="2"><strong>CTO / NAP</strong></th> 
                    <th style="width: 18%; text-align: center;" colspan="1"><strong>Long. FO Acomet.</strong></th> 
                    <th style="width: 10%; text-align: center;" colspan="1"><strong>CLIENTE</strong></th> 
               </tr>
              
               <tr role="row">
                	<th style="width: 4%; TEXT-ALIGN: center;" colspan="1"></th> 
                    <th style="width: 19%; TEXT-ALIGN: center;" colspan="1"><strong>PUERTO ORIGEN</strong></th> 
                    <th style="width: 10%; TEXT-ALIGN: center;" colspan="1"><strong>INPUT (DB)</strong></th>                          
                    <th style="width: 10%; TEXT-ALIGN: center;" colspan="1"><strong>APROX. (KM)</strong></th>
                    <th style="width: 19%; TEXT-ALIGN: center;" colspan="1"><strong>N° CTO CUENTA</strong></th>
                    <th style="width: 10%; TEXT-ALIGN: center;" colspan="1"><strong>OUTPUT (dB)</strong></th>   
                    <th style="width: 18%; TEXT-ALIGN: center;" colspan="1"><strong>APROX. (KM)</strong></th>
                    <th style="width: 10%; TEXT-ALIGN: center;" colspan="1"><strong>OUTPUT (dB)</strong></th>                            
                </tr>
            </thead>
            <tbody>';
        $medidasPotencia = $this->m_bandeja_ficha_tecnica->getMedidasDePotencia($item);
        $cont = 1;
        foreach($medidasPotencia->result() as $row){
            $tbl .=' <tr>
                        <th style="width: 4%; text-align: left;"><strong>'.$cont.'</strong></th>
                        <th style="width: 19%; text-align: center;">'.$row->puerto_origen.'</th>
                        <th style="width: 10%; text-align: center;">'.$row->ura_db_input.'</th>
                        <th style="width: 10%; text-align: center;">'.$row->long_ura_cto_aprox.'</th>
                        <th style="width: 19%; text-align: center;">'.$row->cro_cto_cuenta.'</th>
                        <th style="width: 10%; text-align: center;">'.$row->cto_nap_output_db.'</th>
                        <th style="width: 18%; text-align: center;">'.$row->long_acomet_aprox.'</th>
                        <th style="width: 10%; text-align: center;">'.$row->output_db_cliente.'</th>                       
                    </tr>';
            $cont ++;
        }
        $tbl .='</tbody>
            </table>';
        $tbl .='
            
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
                    </table>*/
        $tbl .='<div class="form-group" style="text-align: right;width: 100%;">
                                <div class="col-sm-12">
                                    <button data-item="'.$item.'" data-fic="'.$dataFicha['id_ficha_tecnica'].'" data-acc="2" onclick="validarFic(this)" type="button" class="btn btn-danger">RECHAZAR</button>
                                    <button data-item="'.$item.'" data-fic="'.$dataFicha['id_ficha_tecnica'].'" data-acc="1" onclick="validarFic(this)" type="button" class="btn btn-primary">APROBAR</button>
                                </div>
                            </div>
            </div>
        ';
        return $tbl;
    }
    
    function getFichaToEvaluacionSisego(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $itemplan = $this->input->post('itemplan');
            $data['dataHTML']  = $this->makeHTMLFIchaToEvaluacionSisegos($itemplan);
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function saveValidacionFichaOBP(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $estado      = $this->input->post('estado');
            $idFicha     = $this->input->post('ficha');
            $itemplan    = $this->input->post('itemplan');
            $observacion = $this->input->post('observacion');
            $listaEstacion = $this->m_bandeja_ficha_tecnica->getEstacionPorcentajeByItemPlanAll($itemplan);
            $arrayInsert =  array();
            $arrayUpdate = array();
            
            foreach($listaEstacion->result() as $row){
                $datatrans = array();
                if($row->idItemplanEstacion == NULL){
                    $datatrans['porcentaje'] = '100';
                    $datatrans['idEstacion'] = $row->idEstacion;
                    $datatrans['itemplan']   = $row->itemplan;
                    array_push($arrayInsert, $datatrans);
                }else{
                    $datatrans['idItemplanEstacion'] = $row->idItemplanEstacion;
                    $datatrans['porcentaje'] = '100';
                    array_push($arrayUpdate, $datatrans);
                }
            }
            
            $data = $this->m_bandeja_ficha_tecnica->saveFichaTecnicaValidacionOBP($idFicha, $estado, $itemplan, $arrayInsert, $arrayUpdate, $observacion);          
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_bandeja_ficha_tecnica->getBandejaFichaTecnicaEvaluacion('','','','',''));
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
}