<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *
 * @author Gustavo Sedano L.
 * 05/09/2019
 *
 */
class C_pre_liquidacion extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_plan_obra/m_planobra');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_ejecucion/M_generales');
        $this->load->model('mf_ejecucion/M_porcentaje');
        $this->load->model('mf_pqt_ejecucion/M_pqt_pendientes');
        $this->load->model('mf_pqt_pre_liquidacion/M_pqt_pre_liquidacion');
        $this->load->model('mf_servicios/m_integracion_siom');
		$this->load->model('mf_detalle_obra/m_detalle_obra');
        $this->load->library('lib_utils');
        $this->load->helper('url');
        $this->load->library('zip');
        $this->load->library('table');
    }

	public function index()
	{
	    
        $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
	        
	        $itemplan = (isset($_GET['itemplan']) ? $_GET['itemplan'] : '');
	        //$itemplan = '19-0211000003';
	        //$itemplan = '19-0410900010';
	        $data['itemPlan'] = ''.$itemplan.'';
	        $data['idEstacion'] = ''.ID_ESTACION_FO.'';
	        
	        $permisos =  $this->session->userdata('permisosArbol');
	        $result = $this->lib_utils->getHTMLPermisos($permisos, NULL, ID_PERMISO_HIJO_PQT_PRE_DISENO, ID_MODULO_PAQUETIZADO);
	        
	        $data['id_estacion'] = ID_ESTACION_FO;

            $data['htmlTabla'] = $this->makeHtmlTablaLiquidacion($itemplan);

            $this->load->view('vf_pqt_gestion_obra_pre_liquidado/v_form_pre_liquidado',$data);
	   }else{
	       redirect('login','refresh');
	   }
    }
        
    function makeHtmlTablaLiquidacion($itemplan = null){
        $countSiom = $this->m_utils->countVaSiom($itemplan);
        $dataInfo = $this->m_utils->getInfoItemplanWhitSubProyecto($itemplan);
        $tabla = null;
        if($countSiom > 0) {
            $tabla = $this->tablaLiquidacionSiom($itemplan);
        } else if($dataInfo['idTipoSubProyecto'] == 3 || $dataInfo['flg_cambio_hab'] == 12 || $dataInfo['idProyecto'] == 4){
            $tabla = $this->tablaLiquidacionSinSiom($itemplan);
        }

        return $tabla;
    }
    
    function tablaLiquidacionSiom($itemPlan = null){
        $pendiente=$this->M_pqt_pre_liquidacion->getEstacionesEnSiomXItemPlan($itemPlan);
        $html = '<table class="table display  pb-30 table-striped table-bordered nowrap dataTable no-footer">
                    <thead>
                    <tr>
                    <th colspan="3" style="text-align:center">SIOM</th>
                    <th colspan="3" style="text-align:center">PLAN DE OBRA</th>
                    </tr>
                    <tr>
                    <th style="background-color: var(--celeste_telefonica);text-align:center"><strong>ESTACION</strong></th>
                    <th style="background-color: var(--celeste_telefonica);text-align:center"><strong>OS</strong></th>
                    <th style="background-color: var(--celeste_telefonica);text-align:center"><strong>ESTADO</strong></th>
                    <th style="background-color: var(--celeste_telefonica);text-align:center"><strong>ACCION</strong></th>
                    <th style="background-color: var(--celeste_telefonica);text-align:center"><strong>% AVANCE</strong></th>
                    <th style="background-color: var(--celeste_telefonica);text-align:center"><strong>ESTADO IP</strong></th>
                    </tr>
                    </thead>
                    <tbody>';

        $html_tmp = '';
        foreach($pendiente->result() as $row){
            $botonZipEvidencia = '';
            $tmpcodigosiom = '';
            $tmpcodigosiomestado = '';
            $porcentaje = '0%';
            foreach($this->M_pqt_pre_liquidacion->getOSEnSiomXEstacionItemPlan($row->itemplan, $row->idEstacion)->result() as $rowOS){
                $tmpcodigosiom .= ($tmpcodigosiom==''?$rowOS->codigoSiom:'<br>'.$rowOS->codigoSiom);
                $tmpcodigosiomestado .= ($tmpcodigosiomestado==''?$rowOS->ultimo_estado:'<br>'.$rowOS->ultimo_estado);
            }
            
            $btnFormulario = '';
            $flg =null;
            if($row->total == $row->validando && $row->total>0 && $row->subioEvidencias == 0){
                $btnFormulario = '<button title="Subir Evidencias" class="btn btn-success btn-rounded  btn-anim mt-10" 
                                                     data-item_plan="'.$row->itemplan.'"  data-id_estacion="'.$row->idEstacion.'" 
                                                         data-estacion="'.$row->estacion.'"
                                                    onclick="openModalEvidencias($(this));">
                                                    <i class="fa fa-eye"></i>
                                                    <span class="btn-text">Evidencias</span>
                                               </button>';
                
                if($row->idEstacion == ID_ESTACION_FO){
                    if($row->countSwitchForm == 1 && $row->countFicha == 0 && $row->total >= 1 && ($row->validando == $row->total)) {
                        $flg=2;
                        $indicador = $row->indicador;
                        $jefatura = $row->jefatura;
                        $empresaColabdesc = $row->descEmpresaColab;
                        $idEstadoPlan = $row->idEstadoPlan;
                        $btnFormulario = '<button title="Ver" class="btn btn-success btn-rounded  btn-anim mt-10" data-indicador="'.$indicador.'" data-flg_from="'.$flg.'"
                                                    data-jefatura="'.$jefatura.'" data-id_estado_plan="'.$idEstadoPlan.'" data-item_plan="'.$row->itemplan.'" data-desc_emp_colab="'.$empresaColabdesc.'" data-id_estacion="'.ID_ESTACION_FO.'"
                                                     onclick="openModalBandejaEjecucionFuera($(this));">
                                                    <i class="fa fa-eye"></i>
                                                    <span class="btn-text">FORM. FO</span>
                                              </button>';
                    }else if($row->countSwitchObPublicas == 1 && $row->countFormObrap == 0 && $row->total >= 1 && ($row->validando == $row->total)) {
                        $indicador = $row->indicador;
                        $jefatura = $row->jefatura;
                        $empresaColabdesc = $row->descEmpresaColab;
                        $idEstadoPlan = $row->idEstadoPlan;
                        $btnFormulario = '<button title="Ver" class="btn btn-success btn-rounded  btn-anim mt-10" data-indicador="'.$indicador.'" data-flg_from="'.$flg.'"
                                                    data-jefatura="'.$jefatura.'" data-item_plan="'.$row->itemplan.'" data-desc_emp_colab="'.$empresaColabdesc.'" data-id_estacion="'.ID_ESTACION_FO.'"
                                                    onclick="openModalFormObPub($(this));">
                                                    <i class="fa fa-eye"></i>
                                                    <span class="btn-text">Formulario</span>
                                               </button>';
                    }else{
                        $hasFTValidad = $this->M_pqt_pre_liquidacion->hasFTValidada($itemPlan, ID_ESTACION_FO);
                        if($hasFTValidad == 0){
                            $btnFormulario= 'NO CUENTA CON DJ VALIDADA';
                        }
                    }
                    /*if($row->countSwitchForm == 1 || $row->countSwitchObPublicas == 1){
                        $btnFormulario = 'EVIDENCIA CARGADA';
                    }*/
                }else if($row->idEstacion == ID_ESTACION_UM){
                    if($row->idSubProyecto == ID_SUBPROYECTO_ACELERACION_MOVIL || $row->idProyecto == ID_PROYECTO_SISEGOS){
                        if($row->total >= 1 && ($row->total == $row->validando) &&  $row->has_form_um == 0){
                            $btnFormulario  = '<button title="FORMULARIO UM" class="btn btn-success btn-rounded  btn-anim mt-10" data-item_plan="'.$row->itemplan.'"
                                        onclick="openFormUM($(this));">
                                        <i class="fa fa-eye"></i>
                                        <span class="btn-text">FORM. UM</span>
                                   </button>';
                        }else if($row->has_form_um  == 1){
                            $btnFormulario = '';
                            $botonZipEvidencia = ' <a data-toggle="tooltip" data-trigger="hover" data-placement="top" data-original-title="descarga zip de las evidencias" data-estaciondesc="'.$row->estacion.'" data-itemplan="'.$itemPlan.'" style="cursor:pointer" onclick="zipItemPlan($(this));">EVIDENCIA CARGADA</i></a>';
                        }
                    }
                }
            }else if($row->subioEvidencias > 0){
                $botonZipEvidencia = ' <a data-toggle="tooltip" data-trigger="hover" data-placement="top" data-original-title="descarga zip de las evidencias" data-estaciondesc="'.$row->estacion.'" data-itemplan="'.$itemPlan.'" style="cursor:pointer" onclick="zipItemPlan($(this));">EVIDENCIA CARGADA</a>';
                $btnFormulario = '';
                $porcentaje = '100%';
            }else{
                $btnFormulario = 'SUBIR_EVIDENCIAS_PDTE';
            }
            
            $html .= '<tr>
                    <td>'.$row->estacion.'</td>
                    <td>'.$tmpcodigosiom.'</td>
                    <td>'.$tmpcodigosiomestado.'</td>
                    <td>'.$btnFormulario.$botonZipEvidencia.'</td>
                    <td>'.$porcentaje.'</td>
                    <td>'.$row->estadoPlanDesc.'</td>
                    </tr>';
        }
        
        $html .= '</tbody>
        </table>';
        return $html;
    }

    function tablaLiquidacionSinSiom($itemplan){
        $pendiente=$this->m_utils->getDataObra($itemplan, array(3,9));
        $html = '<table id="" class="table display  pb-30 table-striped table-bordered nowrap dataTable no-footer">
                    <thead class="thead-default">
                    <tr>
                        <th>ACCI&Oacute;N</th>
                        <th>ITEMPLAN</th>
                        <th>PROYECTO</th>
                        <th>SUBPROYECTO</th>
                        <th>EECC</th>
                        <th>ESTADO</th>
                    </tr>
                    </thead>
                    <tbody>';

        $html_tmp = '';
        $botonZipEvidencia = '';
        $btnCheckLiq = null;
        foreach($pendiente as $row){
            $btnPorcentraje    = '<a data-toggle="tooltip" data-trigger="hover" data-placement="bottom" data-original-title="Porcentaje" data-item_plan ="'.$row['itemplan'].'"  onclick="openModalPorcentaje($(this));"><i class="zmdi zmdi-hc-2x zmdi zmdi-hourglass-alt"></i></a>';
            $botonZipEvidencia = '<a data-toggle="tooltip" data-trigger="hover" data-placement="bottom" data-original-title="descarga zip de las evidencias" data-item_plan="'.$row['itemplan'].'" style="cursor:pointer" onclick="zipItemPlanPqt($(this));"><i class="zmdi zmdi-hc-2x zmdi-download"></i></a>';
            if($row['idProyecto'] == 4) {
				$btnCheckLiq = '<a data-toggle="tooltip" data-trigger="hover" data-placement="bottom" data-original-title="Liquidar Obra" data-item_plan="'.$row['itemplan'].'" style="cursor:pointer" onclick="liquidarSinSiom($(this));"><i class="zmdi zmdi-hc-2x zmdi-check-circle"></i></a>';
			} else {
				if($row['idEstadoPlan'] == 3 && $row['count_porcentaje'] > 0 && $row['flg_update_mat'] > 0) {
					$btnCheckLiq = '<a data-toggle="tooltip" data-trigger="hover" data-placement="bottom" data-original-title="Liquidar Obra" data-item_plan="'.$row['itemplan'].'" style="cursor:pointer" onclick="liquidarSinSiom($(this));"><i class="zmdi zmdi-hc-2x zmdi-check-circle"></i></a>';
				}
			}
			
            $porcentaje = '0%';
            $html .= '  <tr>
                            <td>'.$btnPorcentraje.' '.$botonZipEvidencia.' '.$btnCheckLiq.'</td>
                            <td>'.$row['itemplan'].'</td>
                            <td>'.$row['proyectoDesc'].'</td>
                            <td>'.$row['subProyectoDesc'].'</td>
                            <td>'.$row['empresaColabDesc'].'</td>
                            <td>'.$row['estadoPlanDesc'].'</td>
                        </tr>';
        }
        
        $html .= '</tbody>
        </table>';
        return $html;
    }

    function getDataEstacionesLiquidacion() {
        $itemplan = $this->input->post('itemplan');

        $htmlEstacion = $this->getHtmlEstaciones($itemplan);

        $data['htmlEstaciones'] = $htmlEstacion;

        echo json_encode(array_map('utf8_encode', $data));
    }

    function getHtmlEstaciones($itemPlan) {
        $arrayGetEstaciones = $this->M_porcentaje->ListarEstacion($itemPlan)->result();
        $obra = $this->M_generales->itemPlanI($itemPlan);
        $estaPlanOC = $obra["idEstadoPlan"];
        $zonal = $obra["idZonal"];

        $html="";
        $idEstadoPlan = null;
        $indicador = null;
        $cont = 0;
        $arrayPorcentaje = $this->m_utils->getPorcentajeLiqui();
        $html = '<div class="row">';
        foreach($arrayGetEstaciones as $row) {
            $buttonPtr = null;

            $s=0;
            $porcentaje_total=0;
            $racti=$this->M_porcentaje->ActividadEstacion($row->idEstacion);

            $dataItemplanEstacionAvance = $this->m_utils->getPorcentajeAvanceByItemplanEstacion($itemPlan, $row->idEstacion);

            if($dataItemplanEstacionAvance != null) {
                $cant          = $dataItemplanEstacionAvance['porcentaje'];
                $flg_evidencia = $dataItemplanEstacionAvance['flg_evidencia'];
            } else {
                $cant          = 0;
                $flg_evidencia = null;
            }
            
            $flgZonal = 1; 

            $buttonFoto = null;
            $ubic='uploads/evidencia_fotos/'.$itemPlan;
            
            if(is_dir($ubic)) {
                $nroArchivos = count(scandir('uploads/evidencia_fotos/'.$itemPlan)) - 2;
            }

            $ubicacion        = 'uploads/evidencia_fotos/'.$itemPlan.'/'.$row->estacionDesc;
            $ubicacionArch    = 'uploads/evidencia_fotos/'.$itemPlan.'/archivos_estacion/'.$row->estacionDesc;

            $buttonArchivo = null;
            if($row->idEstadoPlan == ID_ESTADO_TERMINADO && $cant == 100) {
                $buttonArchivo    = null;
                $btnFormulario    = null;
                $buttonKitMateriales = null;
            }
            

            $bcolor = null;
            $msjSerie = null;
            $nota     = null;
            $msjArchivo = null;
            $msjEditMat = null;
            // list($bcolor, $cant, $nota, $msjSerie, $buttonSelecSerie, $buttonArchivo, $msjArchivo, $btnVs, $arrayFlgActiFo   
            $flg =null;
 
            $buttonFoto    = '<button data-scroll-nav="1" class="btn btn-success btn-rounded  btn-anim mt-10" 
                                data-item_plan="'.$itemPlan.'"  data-id_estacion="'.$row->idEstacion.'" data-flg_edit_po="'.$row->flg_edit_po.'"
                                data-estacion="'.$row->estacionDesc.'"
                                onclick="openModalEvidenciasSinSiom($(this))">
                               <span class="btn-text">Eviden.</span></button>';
            $btnFormulario       = null;
            $buttonKitMateriales = null;

            $cmbPorcentaje = null;
            $selected      = null;
            $cmbPorcentaje .= '<select id="cmbPorcentaje_'.$row->idEstacion.'" data-itemplan="'.$itemPlan.'" data-id_estacion="'.$row->idEstacion.'"
                                    class="form-control col-md-2" onchange="ingresarPorcentajeLiqui($(this));">
                                    <option value="0">0%</option>';

                                    foreach($arrayPorcentaje as $rowPor) {
                                        if($rowPor['porcentaje'] == $cant) {
                                            $selected = 'selected';
                                        } else {
                                            $selected = null;
                                        }
                                        $cmbPorcentaje .= '<option value="'.$rowPor['porcentaje'].'" '.$selected.'>'.$rowPor['porcentaje'].'%</option>';
                                    }
            $cmbPorcentaje .= '</select>';
            
            
			if($flg_evidencia == null || $flg_evidencia == '') {
				$msjArchivo = 'FALTA EVIDENCIA';
			} else if($row->idEstadoPlan <> 3) {
				$buttonFoto = null;
			}                    

        
            
			$btnEditPo = '<button data-scroll-nav="1" class="btn btn-success btn-rounded  btn-anim mt-10" 
                                data-itemplan="'.$row->itemPlan.'" data-id_estado_plan="'.$row->idEstadoPlan.'"
                                data-estacion="'.$row->estacionDesc.'" data-id_subproyecto="'.$row->idSubProyecto.'"
                                onclick="openModalPTR($(this))">
								<span class="btn-text">PO</span>
						 </button>';
            $html.='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel panel-default card-view">
                                        <div class="panel-heading '.$bcolor.'">
                                            <div class="pull-left">
                                                <h6 class="panel-title txt-dark">'.$row->estacionDesc.'</h6>
                                                <h5 style="color:red">'.$nota.'</h5>
                                                <div id="contMsjEvidencia_'.$row->idEstacion.'">
                                                    <h5 style="color:red">'.$msjArchivo.' '.$msjEditMat.'</h5>
                                                </div>                                                    
                                            </div>
                                            <div class="pull-right" id="cont_porcentaje_estacion_'.$row->idEstacion.'">
                                                    <span style="font-size:18px" class="label label-primary capitalize-font inline-block ml-10">'.$cant.'%</span>
                                                </div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="panel-wrapper collapse in">
                        <div class="panel-body">';
                
                $html.=     '   <div class="container-fluid">
                                    <div class="col-md-12">'
                                        .$buttonFoto.' '.$btnEditPo.' '.$buttonArchivo.' '.$btnFormulario.' '.$buttonPtr.'
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';            
        }
        $html .= '</div>';
        return $html;
    }

    function ingresarPorcentajeLiqui() {
        try {
            $itemplan   = $this->input->post('itemplan');
            $idEstacion = $this->input->post('idEstacion');
            $porcentaje = $this->input->post('porcentaje');
            $idUsuario  = $this->session->userdata('idPersonaSession');
            $fechaActual = $this->fechaActual();
            
            $countExistItemplansEstacAvanc = $this->m_utils->countItemplanEstacionAvance($itemplan, $idEstacion);
            $flg_evidencia = null;
            if($countExistItemplansEstacAvanc > 0) {
                $dataArrayPorcentaje = array('porcentaje'     => $porcentaje,
                                             'fecha'          => $fechaActual,
                                             'id_usuario_log' => $idUsuario);
                $data = $this->m_utils->updatePorcentajeLiqui($itemplan, $idEstacion, $dataArrayPorcentaje);
            } else {
                $dataArrayPorcentaje = array('itemplan'       => $itemplan,
                                             'idEstacion'     => $idEstacion,
                                             'porcentaje'     => $porcentaje,
                                             'fecha'          => $fechaActual,
                                             'id_usuario_log' => $idUsuario);
                $data = $this->m_utils->insertPorcentajeLiqui($dataArrayPorcentaje);
            }
            
            if($data['error'] == EXIT_ERROR) {
                throw new Exception($data['msj']);
            }
            $dataItemplanEstacionAvance = $this->m_utils->getPorcentajeAvanceByItemplanEstacion($itemplan, $idEstacion);
            $dataObra = $this->m_utils->getPlanobraByItemplan($itemplan);
            if($dataObra['flg_cambio_hab'] == 12) {
                if($idEstacion == ID_ESTACION_OC_COAXIAL || $idEstacion == ID_ESTACION_OC_FO) {//OC
                    if($porcentaje == 100 && $dataItemplanEstacionAvance['flg_evidencia'] == 1) {
                        $this->liquidarPoMatByItemplanEstacion($itemplan, $idEstacion);
                        $this->preliquidarSinSiom($itemplan);
                    }
                } else {
                    throw new Exception('Al liquidar el diseno se indico que solo se liquide OC, verificar.');
                }
            } else {
                if($dataItemplanEstacionAvance['flg_update_mat'] == 1) {//SI ES QUE SE EDITO LOS MATERIALES
                    $this->preliquidarSinSiom($itemplan);
                }
                
                if($porcentaje == 100 && $dataItemplanEstacionAvance['flg_evidencia'] == 1) {
                    $this->liquidarPoMatByItemplanEstacion($itemplan, $idEstacion);
                }
            }

            $data['tablaLiquidacion'] = $this->makeHtmlTablaLiquidacion($itemplan);
        }catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function registrarEvidencias() {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try {
    
            $itemplan = $this->input->post('itemplan');
            $idEstacion = $this->input->post('idEstacion');
            $descEstacion = $this->input->post('descEstacion');

            $fileNamePruebas = $_FILES ["filePruebas"]["name"];
            $filePruebasTemp = $_FILES['filePruebas']['tmp_name'];

            $fileNamePerfil  = $_FILES['filePerfil']['name'];
            $filePerfilTemp  = $_FILES['filePerfil']['tmp_name'];

            
            $data = $this->cargarArchivoEvidencia($itemplan, $idEstacion, $descEstacion, $fileNamePruebas, $filePruebasTemp, $fileNamePerfil, $filePerfilTemp);

            //PRE LIQUIDAR ESTACION ITEMPLAN
            $this->liquidarEstacion($itemplan, $idEstacion);
            //PRE LIQUIDAR EL ITEMPLAN
            $this->preliquidar($itemplan);
        }catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function saveSisegoPlanObra(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try{
            $itemplan                   = $this->input->post('itemplan');
            $from                       = $this->input->post('from');
            $tipo_obra                  = $this->input->post('tipo_obra');
            $nap_nombre                 = $this->input->post('nap_nombre');
            $nap_num_troncal            = $this->input->post('nap_num_troncal');
            $nap_cant_hilos_habi        = $this->input->post('nap_cant_hilos_habi');
            $nap_nodo                   = $this->input->post('nap_nodo');
            $nap_coord_x                = $this->input->post('nap_coord_x');
            $nap_coord_y                = $this->input->post('nap_coord_y');
            $nap_ubicacion              = $this->input->post('nap_ubicacion');
            $nap_num_pisos              = $this->input->post('nap_num_pisos');
            $nap_zona                   = $this->input->post('nap_zona');
            $fo_oscu_cant_hilos         = $this->input->post('fo_oscu_cant_hilos');
            $fo_oscu_cant_nodos         = $this->input->post('fo_oscu_cant_nodos');
            $trasla_re_cable_externo    = $this->input->post('trasla_re_cable_externo');
            $trasla_re_cable_interno    = $this->input->post('trasla_re_cable_interno');
            $fo_tra_cant_hilos          = $this->input->post('fo_tra_cant_hilos');
            $fo_tra_cant_hilos_hab      = $this->input->post('fo_tra_cant_hilos_hab');
            $nap_idCmbUbicacion         = $this->input->post('nap_idCmbUbi');
            $licenciaAfirm              = $this->input->post('licenciaAfirm');
            $descEmpresaColab           = $this->input->post('descEmpresaColab');
            $indicador                  = $this->input->post('indicador');
            $jefatura                   = $this->input->post('jefatura');
            /**NODOS QUE PROVIENEN DEL FORMULARIO  "N"**/
            $listaNomNodos             = json_decode($this->input->post('nodos'), true);
            $idEstacion                = $this->input->post('idEstacion');
            $idEstadoPlan              = $this->input->post('idEstadoPlan');
    
            $pisoGlobal         = $this->input->post('pisoGlobal');
            $sala               = $this->input->post('sala');
            $nroODF             = $this->input->post('nroODF');
            $bandeja            = $this->input->post('bandeja');
            $nroHilo            = $this->input->post('nroHilo');
            //DATA FICHA TECNICA
            $arrayJson          = $this->input->post('arrayJsonData');
            $observacion        = $this->input->post('observacion');
            $idEstacion         = $this->input->post('idEstacion');
            $idFichaTecnicaBase = $this->input->post('idFichaTecnicaBase');
    
    
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception('La sesi&oacute;n caduc&oacute;, recargue la p&aacute;gina nuevamente.');
            }
    
            if($licenciaAfirm == NULL) {
                throw new Exception('Confirmar si es con licencia o no.');
            }
    
            if($tipo_obra == NULL || $tipo_obra == 0) {
                throw new Exception('Seleccionar tipo de obra.');
            }
            $arrayNodos = array();
            if($tipo_obra == ID_TIPO_OBRA_CREACION_NAP) {
                if($nap_nombre == null || $nap_num_troncal==null || $nap_cant_hilos_habi==null || $nap_nodo ==null || $nap_coord_x == null || $nap_coord_y == null || $nap_idCmbUbicacion == 0) {
                    throw new Exception('Faltan ingresar datos');
                }
                if($nap_idCmbUbicacion == 3) {
                    if($nap_num_pisos == null) {
                        throw new Exception('No ingreso el n&uacute;mero de pisos');
                    }
                } else if($nap_idCmbUbicacion == 4) {
                    if($nap_zona == null) {
                        throw new Exception('No ingreso zona');
                    }
                }
            }
            else if($tipo_obra == ID_TIPO_OBRA_FO_OSCURA) {
                if(count($listaNomNodos) == 0) {
                    throw new Exception('ingresar nombre de nodos');
                }
                if($fo_oscu_cant_hilos == null || $fo_oscu_cant_nodos==null) {
                    throw new Exception('Faltan ingresar datos');
                }
            } else if($tipo_obra == ID_TIPO_OBRA_TRASLADO) {
                if($trasla_re_cable_externo == null || $trasla_re_cable_interno == null) {
                    throw new Exception('falta ingresar datos');
                }
            } else if($tipo_obra == ID_TIPO_OBRA_FO_TRADICIONAL) {
                if($fo_tra_cant_hilos == null || $fo_tra_cant_hilos_hab == null) {
                    throw new Exception('falta ingresar datos');
                }
            }
             
            foreach($listaNomNodos as $nodo) {
                $nodo_tmp = array();
                $nodo_tmp['itemplan']   = $itemplan;
                $nodo_tmp['origen']     = $from;
                $nodo_tmp['nodo']       = $nodo['value'];
                array_push($arrayNodos, $nodo_tmp);
            }
    
            $data = $this->m_planobra->saveSisegoPlanObra(  $itemplan, $from,  $tipo_obra, $nap_nombre,    $nap_num_troncal,   $nap_cant_hilos_habi,
                $nap_nodo,  $nap_coord_x,   $nap_coord_y,   $nap_ubicacion, $nap_num_pisos, $nap_zona,
                $fo_oscu_cant_hilos,   $fo_oscu_cant_nodos,    $trasla_re_cable_externo,
                $trasla_re_cable_interno,   $fo_tra_cant_hilos, $fo_tra_cant_hilos_hab, $arrayNodos, $licenciaAfirm,
                $pisoGlobal, $sala, $nroODF, $bandeja, $nroHilo);
            if($data['error'] == EXIT_ERROR){
                throw new Exception('1) Error interno al registrar el SisegoPlanObra.');
            } else {
                $arrayDataLog = array(
                    'tabla'            => 'sinfix',
                    'actividad'        => 'Registro Formulario',
                    'itemplan'         => $itemplan,
                    'fecha_registro'   => $this->fechaActual(),
                    'id_usuario'       => $this->session->userdata('idPersonaSession'),
                );
                $this->m_utils->registrarLogPlanObra($arrayDataLog);
    
                $countFichaTec = $this->m_utils->countFichaTecnica($itemplan);
    
                if($countFichaTec == 0) {
                    $this->registrarFichaSinfix($arrayJson, $itemplan, $observacion, $idEstacion, $idFichaTecnicaBase);
                }
    
                if($from == 2 && $idEstadoPlan == ID_ESTADO_PLAN_EN_OBRA) {
                    $cant = $this->M_porcentaje->cantPorcentajeRegistroSisego($itemplan, $idEstacion);
                    if(isset($cant->porcentaje)) {
                        $porcentaje = 50;//$porcentaje = ($cant->porcentaje >= 90) ? 100 :  $cant->porcentaje + 10;
                        // if($cant->porcentaje == 90) {
                         
                        if($porcentaje == 100) {
                            $this->M_porcentaje->updateEstadoPO($itemplan, $idEstacion);
                            $arrayData = array('idEstadoPlan'        => ID_ESTADO_PRE_LIQUIDADO,
                                'fechaPreLiquidacion' => $this->fechaActual(),
                                'id_usuario_preliquidacion' => $this->session->userdata('idPersonaSession'));
    
                            $flgValid = $this->M_porcentaje->getValidaSubProyecto($itemplan, $idEstacion, $porcentaje);
    
                            if($flgValid->flg_focoaxial == 1 || $flgValid->flg_focoaxial == 2 || $flgValid->flg_focoaxial == 3 || $flgValid->flg_focoaxial == 4 || $flgValid->flg_acti_fo == 2) {
                                $flg = $this->M_porcentaje->updateEstadoPlanObra($itemplan, $arrayData);
                            } else {
                                $flg = null;
                            }
    
                            $countTrama = $this->M_porcentaje->countTrama($itemplan, 'LIQUIDACION OBRA');
                            if($countTrama == 0 && $flgValid->flg_sisego == 1) {
                                $this->enviarTrama($itemplan, $indicador, 2, $jefatura ,$descEmpresaColab);
                            }
                            if($flg == 0) {
                                _log("FALLO AL ACTUALIZAR EL ESTADO");
                            } else if($flg == 1) {
                                $arrayDataLog = array(
                                    'tabla'            => 'planobra',
                                    'actividad'        => 'Obra Pre-Liquidada',
                                    'itemplan'         => $itemplan,
                                    'fecha_registro'   => $this->fechaActual(),
                                    'id_usuario'       => $this->session->userdata('idPersonaSession'),
                                    'idEstadoPlan'     => ID_ESTADO_PRE_LIQUIDADO
                                );
    
                                $this->m_utils->registrarLogPlanObra($arrayDataLog);
                            }
                        }
                        $arrayData = array(
                            'porcentaje'   => $porcentaje,
                            'fecha'        => $this->fechaActual(),
                        );
                        $this->M_porcentaje->updateItemPlanEstacionAvance($arrayData, $itemplan, $idEstacion);
                    } else {
                        $arrayData = array(
                            'itemplan'     => $itemplan,
                            'idEstacion'   => $idEstacion,
                            'porcentaje'   => 10,
                            'fecha'        => $this->fechaActual(),
                        );
                        $this->M_porcentaje->insertItemPlanEstacionAvance($arrayData);
                    }
    
                    if($from == 2 && $idEstacion == ID_ESTACION_FO){
    
                        $ubicacion = 'uploads/evidencia_fotos/'.$itemplan.'/'.'FO';
                        $cdir = scandir($ubicacion);
                        foreach ($cdir as $key => $value)
                        {
                            if (!in_array($value,array(".","..")))
                            {
                                $pendiente = $this->M_pqt_pre_liquidacion->getEvidenciasXEstacionItemPlan($itemplan, $idEstacion);
                                $i = 0;
                                foreach($pendiente->result() as $row){
                                    if($row->path_pdf_pruebas == $ubicacion.'/'.$value || $row->path_pdf_perfil == $ubicacion.'/'.$value){
                                        $i = $i + 1;
                                    }
                                }
                                if($i == 0){
                                    $dataFormularioEvidencias = array(
                                        'itemplan'          =>  $itemplan,
                                        'fecha_registro'    => $this->fechaActual(),
                                        'usuario_registro'  => $this->session->userdata('idPersonaSession'),
                                        'idEstacion'        => $idEstacion,
                                        'path_pdf_pruebas'  => ($this->startsWith($value,'PR_')?$ubicacion.'/'.$value:null),
                                        'path_pdf_perfil'   => ($this->startsWith($value,'PP_')?$ubicacion.'/'.$value:null)
                                    );
    
                                    $data = $this->M_pqt_pre_liquidacion->registrarEvidencias($dataFormularioEvidencias);
                                }
                            }
                        }
                    }
    
                }
            }
            //PRE LIQUIDAR ESTACION ITEMPLAN
            $this->liquidarEstacion($itemplan, $idEstacion);
            
            //PRE LIQUIDAR EL ITEMPLAN
            $this->preliquidar($itemplan);
        }catch(Exception $e){
            $data = null;
            $data['error']    = EXIT_ERROR;
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function registrarFicha($itemPlan, $observacion, $idEstacion, $idFichaTecnicaBase, $arrayJson) {
        $idCuadrilla = $this->M_porcentaje->getCuadrillaOne($itemPlan, $idEstacion);
        $coordenada  = $this->M_porcentaje->getCoordenadas($itemPlan);
        $dataInsert  = array(
								'jefe_c_nombre'         => $idCuadrilla,
								'observacion'           => $observacion,
								'itemplan'              => $itemPlan,
								'fecha_registro'        => date("Y-m-d H:m:s"),
								'usuario_registro'      => $this->session->userdata('idPersonaSession'),
								'coordenada_x'          => $coordenada['coordX'],
								'coordenada_y'          => $coordenada['coordY'],
								'flg_activo'            => '1',
								'id_ficha_tecnica_base' => $idFichaTecnicaBase,
								'id_estacion'           => $idEstacion,
								'estado_validacion'     => 1,
								'fecha_validacion'      => date("Y-m-d H:m:s"),
								'usuario_validacion'    => 'APROBACION AUTOMATICA'
							);

        $dataSub = $this->m_utils->getDataSubProyectoByItemplan($itemPlan);

        if($dataSub['idProyecto'] == 21 ||$dataSub['idProyecto'] == 4) {
            $val = $this->M_porcentaje->isertFichaTecnicaPub($dataInsert, $arrayJson);
        } else {
            $val = $this->M_porcentaje->isertFichaTecnica($dataInsert, $arrayJson);
        }
        
        return $val;
    }
    
	function testSendLiquidacion(){
		$this->enviarTrama('21-0320400071', '2021-04-199998-0', 2, 'PUNO' ,'EZENTIS');
	}
	
    function enviarTrama($itemPlan, $indicador, $from, $jefatura ,$descEmpresaColab){//modificado czavala 23.07.2021        
        $dataSend = ['itemplan' => $itemPlan,
            'fecha'    => $this->fechaActual(),
            'sisego'   => $indicador];
    
        $url = 'https://172.30.5.10:8080/obras2/recibir_eje.php';
    
        $response = $this->m_utils->sendDataToURL($url, $dataSend);
		log_message('error', print_r($response, true));
        if($response!=null){
			if($response->error	== EXIT_SUCCESS){
				$this->m_utils->saveLogSigoplus('PQT LIQUIDACION OBRA', null , $itemPlan, null, $indicador, $descEmpresaColab, $jefatura, 'TRAMA COMPLETADA', strtoupper($response->mensaje), 1, 4);
			}else{
				$this->m_utils->saveLogSigoplus('PQT LIQUIDACION OBRA', null, $itemPlan, null, $indicador, $descEmpresaColab, $jefatura, 'FALLA EN LA RESPUESTA DEL HOSTING', 'OPERACION NO COMPLETADA ERROR EN EL SERVIDOR DEL CLIENTE:'. strtoupper($response->mensaje), '2');
			}    
		}else{
			$this->m_utils->saveLogSigoplus('PQT LIQUIDACION OBRA', null, $itemPlan, null, $indicador, $descEmpresaColab, $jefatura, 'FALLA EN LA RESPUESTA DEL HOSTING', 'OPERACION NO COMPLETADA SERVIDOR NO RESPONDE', '3');
		}			
    }
    
    function startsWith ($string, $startString)
    {
        $len = strlen($startString);
        return (substr($string, 0, $len) === $startString);
    }
    
    function liquidarPoMatByItemplanEstacion($itemplan, $idEstacion){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try{
            $listaPoMat = $this->m_utils->getPoSinSiom($itemplan, $idEstacion);
            $updateDataPo = array();
            $insertDataLog = array();
            foreach($listaPoMat as $row){
                $dataUp = array(
                    'estado_po' =>  PO_LIQUIDADO,
                    'codigo_po' =>  $row->codigo_po
                );

                array_push($updateDataPo, $dataUp);
                $dataIn = array('codigo_po' =>  $row->codigo_po,
                    'itemplan' =>  $row->itemplan,
                    'idUsuario' =>  ID_USUARIO_SIOM_WEB,
                    'fecha_registro' => $this->fechaActual(),
                    'idPoestado'    =>  PO_LIQUIDADO,
                    'controlador'   => 'SIOM WEB'
                );
                array_push($insertDataLog, $dataIn);
    
            }
            $data = $this->m_integracion_siom->liquidarPoMateriales($updateDataPo, $insertDataLog);
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
    function zipItemPlan() {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $itemPlan = $this->input->post('itemPlan');
            $estacionDesc = $this->input->post('estacionDesc');
            
            if($itemPlan == null) {
                throw new Exception('accion no permitida');
            }
            $ubicacion = 'uploads/evidencia_fotos/'.$itemPlan.'/'.$estacionDesc;
            $ubicacionZip = 'uploads/evidencia_zip/'.$itemPlan;
            if(!is_dir($ubicacionZip)) {
                mkdir($ubicacionZip, 0777);
            }
    
            if(is_dir($ubicacion)) {
                if (is_dir($ubicacionZip)) {
                    $this->rrmdir($ubicacionZip);
                    mkdir($ubicacionZip, 0777);
    
                    $fechaActual = $this->fechaActual();
                    $this->zip->read_dir($ubicacion,false);
                    $fileName = $itemPlan.'_fe_'.date("d_m").'.zip';
                    $this->zip->archive($ubicacionZip.'/'.$fileName);
                }
                $data['directorioZip'] =  $ubicacionZip.'/'.$fileName;
            }
            $data['error'] = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }


    function zipItemPlanPqt() {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $itemPlan = $this->input->post('itemPlan');
            
            if($itemPlan == null) {
                throw new Exception('accion no permitida');
            }
            $ubicacion = 'uploads/evidencia_fotos/'.$itemPlan;
            $ubicacionZip = 'uploads/evidencia_zip/'.$itemPlan;
            if(!is_dir($ubicacionZip)) {
                mkdir($ubicacionZip, 0777);
            }
    
            if(is_dir($ubicacion)) {
                if (is_dir($ubicacionZip)) {
                    $this->rrmdir($ubicacionZip);
                    mkdir($ubicacionZip, 0777);
    
                    $fechaActual = $this->fechaActual();
                    $this->zip->read_dir($ubicacion,false);
                    $fileName = $itemPlan.'_fe_'.date("d_m").'.zip';
                    $this->zip->archive($ubicacionZip.'/'.$fileName);
                }
                $data['directorioZip'] =  $ubicacionZip.'/'.$fileName;
            }
            $data['error'] = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    
    function rrmdir($src) {
        $dir = opendir($src);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                $full = $src . '/' . $file;
                if ( is_dir($full) ) {
                    $this->rrmdir($full);
                }
                else {
                    unlink($full);
                }
            }
        }
        closedir($dir);
        rmdir($src);
    }
	
    function evaluarToPreliquidarObra()
    {
		$idEstacion = 5;
		$itemplan = '21-0821300002';
        $this->liquidarEstacion($itemplan, $idEstacion);            
		//PRE LIQUIDAR EL ITEMPLAN

		$this->preliquidar($itemplan);
		$data['error'] = EXIT_SUCCESS;
		echo json_encode(array_map('utf8_encode', $data));
    }
	
    function ingresaItemPlanEstacionAvance($itemPlan, $idEstacion, $porcentaje, $conversacion, $idCuadrilla) {
        $valid = $this->M_porcentaje->validarItemPlanEstacionAvance($itemPlan, $idEstacion);
        if($valid == 0) {
            if($idCuadrilla == null) {
    
                $arrayData = array(
                    'itemplan'     => $itemPlan,
                    'idEstacion'   => $idEstacion,
                    'porcentaje'   => $porcentaje,
                    'fecha'        => $this->fechaActual(),
                    'comentario'   => $conversacion
                );
            } else if($conversacion == null) {
                $arrayData = array(
                    'itemplan'     => $itemPlan,
                    'idEstacion'   => $idEstacion,
                    'porcentaje'   => $porcentaje,
                    'fecha'        => $this->fechaActual(),
                    'id_usuario_log' => $this->session->userdata('idPersonaSession'),
                    'id_cuadrilla' => $idCuadrilla
                );
            } else if($idCuadrilla == null && $conversacion == null) {
                $arrayData = array(
                    'itemplan'     => $itemPlan,
                    'idEstacion'   => $idEstacion,
                    'porcentaje'   => $porcentaje,
                    'fecha'        => $this->fechaActual(),
                    'id_usuario_log' => $this->session->userdata('idPersonaSession')
                );
            } else {
                $arrayData = array(
                    'itemplan'     => $itemPlan,
                    'idEstacion'   => $idEstacion,
                    'porcentaje'   => $porcentaje,
                    'fecha'        => $this->fechaActual(),
                    'id_usuario_log' => $this->session->userdata('idPersonaSession'),
                    'id_cuadrilla' => $idCuadrilla,
                    'comentario'   => $conversacion
                );
            }
            $data = $this->M_porcentaje->insertItemPlanEstacionAvance($arrayData);
            if($data == 0) {
                throw new Exception('error');
            }
        } else {
            if($idCuadrilla == null && $conversacion == null) {
                $arrayData = array(
                    'porcentaje'   => $porcentaje,
                    'fecha'        => $this->fechaActual(),
                    'id_usuario_log' => $this->session->userdata('idPersonaSession')
                );
            }else if($idCuadrilla == null) {
                $arrayData = array(
                    'porcentaje'   => $porcentaje,
                    'fecha'        => $this->fechaActual(),
                    'id_usuario_log' => $this->session->userdata('idPersonaSession'),
                    'comentario'   => $conversacion
                );
            } else if($conversacion == null){
                $arrayData = array(
                    'porcentaje'   => $porcentaje,
                    'fecha'        => $this->fechaActual(),
                    'id_usuario_log' => $this->session->userdata('idPersonaSession'),
                    'id_cuadrilla' => $idCuadrilla
                );
            } else {
                $arrayData = array(
                    'porcentaje'   => $porcentaje,
                    'fecha'        => $this->fechaActual(),
                    'id_cuadrilla' => $idCuadrilla,
                    'id_usuario_log' => $this->session->userdata('idPersonaSession'),
                    'comentario'   => $conversacion
                );
            }
    
            $data = $this->M_porcentaje->updateItemPlanEstacionAvance($arrayData, $itemPlan, $idEstacion);
            if($data == 0) {
                throw new Exception('error');
            }
        }
    }
    
    function registrarFormularioUM() {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try {
    
            $itemplan = $this->input->post('itemplan');
            $descEstacion = 'UM';
            $cliente    = $this->input->post('txtCliente');
            $direccion  = $this->input->post('txtDireccion');
            $fibrasCli  = $this->input->post('txtFibrasCliente');
            $fecTermino = $this->input->post('txtFecTermino');
            $nodo       = $this->input->post('txtNodo');
            $ubicacion  = $this->input->post('txtUbicacion');
            $numODF     = $this->input->post('txtNumODF');
            $conectores = $this->input->post('txtBanConectores');
            $fibras     = $this->input->post('txtFibras');
            //DE NO EXISTIR LA CARPETA ITEMPLAN LA CREAMOS
            $pathItemplan = 'uploads/evidencia_fotos/'.$itemplan;
            if (!is_dir($pathItemplan)) {
                mkdir ($pathItemplan, 0777);
            }
    
            //DE NO EXISTIR LA CARPETA ITEMPLAN ESTACION LA CREAMOS
            $pathItemEstacion = $pathItemplan.'/'.$descEstacion;
            if (!is_dir($pathItemEstacion)) {
                mkdir ($pathItemEstacion, 0777);
            }
            
            //CREAMOS CARPETA DE PRUEBAS REFLECTOMETRICAS
            $pathReflectometricas = $pathItemEstacion.'/P_REFLECTOMETRICAS';
            if (!is_dir($pathReflectometricas)) {
                mkdir ($pathReflectometricas, 0777);
            }
            
            //CREAMOS CARPETA DE PRUEBAS DE PERFIL
            $pathPerfil = $pathItemEstacion.'/P_PERFIL';
            if (!is_dir($pathPerfil)) {
                mkdir ($pathPerfil, 0777);
            }
    
            $uploadfile1 = $pathReflectometricas.'/'. basename($_FILES['filePruebas']['name']);
    
            if (move_uploaded_file($_FILES['filePruebas']['tmp_name'], $uploadfile1)) {
                log_message('error', 'Se movio el archivo a la ruta 1.'.$uploadfile1);
            }else {
                throw new Exception('Hubo un problema con la carga del archivo 1 al servidor, comuniquese con el administrador.');
            }
    
            $uploadfile2 = $pathPerfil.'/'. basename($_FILES['filePerfil']['name']);
    
            if (move_uploaded_file($_FILES['filePerfil']['tmp_name'], $uploadfile2)) {
                log_message('error', 'Se movio el archivo a la ruta 2.'.$uploadfile2);
            }else {
                throw new Exception('Hubo un problema con la carga del archivo 2 al servidor, comuniquese con el administrador.');
            }
    
            $dataFormulario = array('itemplan'          =>  $itemplan,
                'cliente'           =>  $cliente,
                'direccion'         =>  $direccion,
                'fibras_cliente'    =>  $fibrasCli,
                'fecha_termino'     =>  $fecTermino,
                'nodo'              =>  $nodo,
                'ubicacion'         =>  $ubicacion,
                'numero_odf'        =>  $numODF,
                'bandeja_conectores'=>  $conectores,
                'fibras'            =>  $fibras,
                'path_pdf_pruebas'  =>  $uploadfile1,
                'path_pdf_perfil'   =>  $uploadfile2
            );
    
            $dataFichaTecnica = array(  'itemplan'              => $itemplan,
                'fecha_registro'        => $this->fechaActual(),
                'usuario_registro'      => $this->session->userdata('idPersonaSession'),
                'estado_validacion'     => '',
                'flg_activo'            => 1,
                'id_ficha_tecnica_base' => FICHA_BASE_UM,
                'id_estacion'           => ID_ESTACION_UM);
    
    
            $data = $this->M_porcentaje->saveFormularioUM($dataFormulario, $dataFichaTecnica);
            
            /**Registrar estado Pre Liquidado Gustavo Sedano 2019 09 17**/
            $dataFormularioEvidencias = array(
                'itemplan'          =>  $itemplan,
                'fecha_registro'    => $this->fechaActual(),
                'usuario_registro'  => $this->session->userdata('idPersonaSession'),
                'idEstacion'        => ID_ESTACION_UM,
                'path_pdf_pruebas'  =>  $uploadfile1,
                'path_pdf_perfil'   =>  $uploadfile2
            );
            
            $data = $this->M_pqt_pre_liquidacion->registrarEvidencias($dataFormularioEvidencias);
            
            //PRE LIQUIDAR ESTACION ITEMPLAN
            $this->liquidarEstacion($itemplan, ID_ESTACION_UM);
            
            //PRE LIQUIDAR EL ITEMPLAN
            $this->preliquidar($itemplan);
        }catch(Exception $e) {
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

    function ingresarEvidenciaLiqui() {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try {
    
            $itemplan     = $this->input->post('itemplan');
            $idEstacion   = $this->input->post('idEstacion');

            $descEstacion = $this->m_utils->getEstaciondescByIdEstacion($idEstacion);
            $idUsuario    = $this->session->userdata('idPersonaSession');
            
            $fileNamePruebas = $_FILES["filePruebas"]["name"];
            $filePruebasTemp = $_FILES['filePruebas']['tmp_name'];

            $fileNamePerfil  = $_FILES['filePerfil']['name'];
            $filePerfilTemp  = $_FILES['filePerfil']['tmp_name'];

            $fechaActual = $this->fechaActual();
            $data = $this->cargarArchivoEvidencia($itemplan, $idEstacion, $descEstacion, $fileNamePruebas, $filePruebasTemp, $fileNamePerfil, $filePerfilTemp);
           //$dataItemplanEstacionAvance = array();

            $countExistItemplansEstacAvanc = $this->m_utils->countItemplanEstacionAvance($itemplan, $idEstacion);
            
            if($countExistItemplansEstacAvanc > 0) {
                $dataItemplanEstacionAvance    = $this->m_utils->getPorcentajeAvanceByItemplanEstacion($itemplan, $idEstacion);
                if($dataItemplanEstacionAvance['flg_evidencia'] == null || $dataItemplanEstacionAvance['flg_evidencia'] == '') {
                    $dataArrayPorcentaje = array(
                                                    'flg_evidencia' => 1
                                                );
                    $data = $this->m_utils->updateItemplanEstacionAvance($itemplan, $idEstacion, $dataArrayPorcentaje);
                } else {
                    $data['error'] = EXIT_SUCCESS;
                }
            } else {
                $dataArrayPorcentaje = array('itemplan'       => $itemplan,
                                             'idEstacion'      => $idEstacion,
                                             'porcentaje'      => 0,
                                             'fecha'           => $fechaActual,
                                             'id_usuario_log'  => $idUsuario,
                                             'flg_evidencia'   => 1);
                $data = $this->m_utils->insertPorcentajeLiqui($dataArrayPorcentaje);
				$dataItemplanEstacionAvance = $this->m_utils->getPorcentajeAvanceByItemplanEstacion($itemplan, $idEstacion);
            }

            if($data['error'] == EXIT_ERROR) {
                throw new Exception($data['msj']);
            }
			
            if($dataItemplanEstacionAvance['flg_cambio_hab'] == 12) {
                if($idEstacion == ID_ESTACION_OC_FO && $dataItemplanEstacionAvance['porcentaje'] == 100) {
                    $data = $this->preliquidarSinSiom($itemplan);

                    if($data['error'] == EXIT_ERROR) {
                        throw new Exception('No se pudo liquidar, verificar');
                    }

                    $this->liquidarPoMatByItemplanEstacion($itemplan, $idEstacion);
                }
            } else {
                if($dataItemplanEstacionAvance['porcentaje'] == 100) {
                    $this->liquidarPoMatByItemplanEstacion($itemplan, $idEstacion);
                }
                if($dataItemplanEstacionAvance['flg_update_mat'] == 1) {
                    $this->preliquidarSinSiom($itemplan);
                }
            }
        }catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function liquidarEstacion($itemplan, $idEstacion){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
    
        $infEstacion = 'QUERY PARA TRAER INFORMACION DE LA ESTACION Y OS';
        #EVALUACION PARA PASE A PRELIQUIDACION SISEGOS
        $liquidarEstacion = false;
        
        $idProyecto = null;
        $idSubProyecto = null;
        $validando = null;
        $total = null;
        $countSwitchForm = null;
        $countSwitchObPublicas = null;
        $countFormObrap = null;
        $countFicha = null;
        $has_form_um = null;
        $subioEvidencias = null;
        
        $pendiente=$this->M_pqt_pre_liquidacion->getEstacionesEnSiomXItemPlan($itemplan);
        
        $porcentaje = '0';
        $comentario = '';
        $flg_evidencia = '0';
        
        foreach($pendiente->result() as $row){
            $idProyecto = $row->idProyecto;
            $idSubProyecto = $row->idSubProyecto;
            
            if($idEstacion == $row->idEstacion){
                $validando = $row->validando;
                $total = $row->total;
                $countSwitchForm = $row->countSwitchForm;
                $countSwitchObPublicas = $row->countSwitchObPublicas;
                $countFormObrap = $row->countFormObrap;
                $countFicha = $row->countFicha;
                $has_form_um = $row->has_form_um;
                $subioEvidencias = $row->subioEvidencias;
                
                if($subioEvidencias > 0){
                    $comentario = 'TIENE EVIDENCIA';
                    $flg_evidencia = '1';
                }
                
                if($countFormObrap == 1){
                    $comentario = 'TIENE FORMULARIO OBRA PUB';
                }else if($countFicha == 1){
                    $comentario = 'TIENE FORMULARIO FICHA';
                }else if($has_form_um == 1){
                    $comentario = 'TIENE FORMULARIO UM';
                }
            }
        }
        
        if($idEstacion == ID_ESTACION_FO){
            if($idProyecto == ID_PROYECTO_SISEGOS/* || $idProyecto == ID_PROYECTO_MOVILES*/){//czavala 29.04.2021
                /**
                 * VALIDAMOS OS VALIDANDO O APROBADAS (NO CONTAMOS ANULADAS, RECHAZADAS Y NO ACTIVOS)
                 * VALIDAMOS FORMULARIO 
                 * VALIDAMOS EVIDENCIA
                 */
                if($total>0 && $subioEvidencias>0 && $total==$validando && ($countFormObrap==1||$countFicha==1)){
                    $liquidarEstacion = true;
                    $porcentaje = '100';
                }
            }else{
                /**
                 * VALIDAMOS OS VALIDANDO O APROBADAS (NO CONTAMOS ANULADAS, RECHAZADAS Y NO ACTIVOS)
                 * VALIDAMOS EVIDENCIA
                 */
                if($total>0 && $subioEvidencias>0 && $total==$validando){
                    $liquidarEstacion = true;
                    $porcentaje = '100';
                }
            }                
        }else if($idEstacion == ID_ESTACION_UM){
            if($idProyecto == ID_PROYECTO_SISEGOS/* || $idProyecto == ID_PROYECTO_MOVILES*/){//czavala 29.04.202
                /**
                 * VALIDAMOS OS VALIDANDO O APROBADAS (NO CONTAMOS ANULADAS, RECHAZADAS Y NO ACTIVOS)
                 * VALIDAMOS FORMULARIO
                 * VALIDAMOS EVIDENCIA
                 */
                if($total>0 && $subioEvidencias>0 && $total==$validando && $has_form_um==1){
                    $liquidarEstacion = true;
                    $porcentaje = '100';
                }
            }else{
                /**
                 * VALIDAMOS OS VALIDANDO O APROBADAS (NO CONTAMOS ANULADAS, RECHAZADAS Y NO ACTIVOS)
                 * VALIDAMOS EVIDENCIA
                 */
                if($total>0 && $subioEvidencias>0 && $total==$validando){
                    $liquidarEstacion = true;
                    $porcentaje = '100';
                }
            }
        }else{//cualquier otra estacion
            /**
             * VALIDAMOS OS VALIDANDO O APROBADAS (NO CONTAMOS ANULADAS, RECHAZADAS Y NO ACTIVOS)
             * VALIDAMOS EVIDENCIA
             */
            if($total>0 && $subioEvidencias>0 && $total==$validando){
                $liquidarEstacion = true;
                $porcentaje = '100';
            }
        }
        
        if($liquidarEstacion){
            //LIQUIDAR ESTACION
            /**
             * validar itemplanestacionavance si existe update si no insert
             * itemplan, idEstacion, porcentaje, fecha, id_usuario_log, comentario, flg_evidencia
             */
            $dataInsert = array(
                'itemplan'         => $itemplan,
                'idEstacion'           => $idEstacion,
                'porcentaje'              => $porcentaje,
                'fecha'        => $this->fechaActual(),
                'id_usuario_log'      =>  $this->session->userdata('idPersonaSession'),
                'comentario'          => $comentario,
                'flg_evidencia'          => $flg_evidencia
            );
            
            $existeRegistro = $this->M_pqt_pre_liquidacion->countEstacionAvanceByItemplanEstacion($itemplan, $idEstacion);
            if($existeRegistro['count']==0){
                //REGISTRAR
                $this->M_pqt_pre_liquidacion->insertItemPlanEstacionAvance($dataInsert);
            }else{
                //ACTUALIZAR
                $this->M_pqt_pre_liquidacion->updateItemPlanEstacionAvance($itemplan, $idEstacion, $dataInsert);
            }
            
            $this->liquidarPoMatByItemplanEstacion($itemplan, $idEstacion);
        }
        
    }

    public function preliquidar($itemplan){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        #EVALUACION PARA PASE A PRELIQUIDACION SISEGOS
        //DATA A RECORRER
        //obtEstacionesParaLiquidar

        try {
            $listDatosEstacionItemPlan=$this->M_pqt_pre_liquidacion->obtEstacionesParaLiquidar($itemplan);
            
            $estadoItemPlan = null;
            $idProyecto = null;
            $idSubProyecto = null;
            $idTipoSubProyecto = null;
            $has_estacionAnclaFO = '';
            $has_estacionAnclaCOAX = '';
            $has_estacionAnclaUM = '';
            $estacionAnclaFO_culminada = 0;
            $estacionAnclaCOAX_culminada = 0;
            $estacionAnclaUM_culminada = 0;
            $hasVRActivoCoaxial = 0;
            $hasVRActivoFo 		= 0;
			$flg_oc_liquida = null;
            foreach($listDatosEstacionItemPlan->result() as $row){
                $estadoItemPlan = $row->idEstadoPlan;
                $idProyecto = $row->idproyecto;
                $idSubProyecto = $row->idSubProyecto;
                $idTipoSubProyecto = $row->idTipoSubProyecto;
                $flg_cambio_hab    = $row->flg_cambio_hab;
                if($row->idEstacion == ID_ESTACION_FO){
                    //TIENE ALMENOS OS EN ESTADO (CREADA, ASIGNADA, EJECUTANDO, VALIDANDO, APROBADA)
                    if($row->total > 0){
                        $has_estacionAnclaFO = '1';
                    }
                    //TIENE OS CULMINADA PARA LA ESTACION FO
                    if($row->total > 0 && $row->total == $row->validado && $row->pct_avance == 100 && $row->subioEvidencias > 0){
                        $estacionAnclaFO_culminada = 1;
                    }

                    $countPOMat = $this->m_utils->getCountPo($itemplan, ID_ESTACION_FO, 1);
                    if($countPOMat > 0) {
                        $hasVRActivoCoaxial = $this->m_utils->getCountItemplanAptopLiquida($itemplan, ID_ESTACION_FO);//SI ES 0 SI SE PUEDE LIQUIDAR, SI ES 1 NO SE PUEDE YA QUE TIENE VR
                    
                        if($hasVRActivoCoaxial == 1) {
                            throw new Exception('COAX tiene VR verificar');
                        }
                    }
                }
                if($row->idEstacion == ID_ESTACION_COAXIAL){
                    //TIENE ALMENOS OS EN ESTADO (CREADA, ASIGNADA, EJECUTANDO, VALIDANDO, APROBADA)
                    if($row->total > 0){
                        $has_estacionAnclaCOAX = '1';
                    }
                    //TIENE OS CULMINADA PARA LA ESTACION COAXIAL
                    if($row->total > 0 && $row->total == $row->validado && $row->pct_avance == 100 && $row->subioEvidencias > 0){
                        $estacionAnclaCOAX_culminada = 1;
                    }
                    
                    $countPOMat = $this->m_utils->getCountPo($itemplan, ID_ESTACION_COAXIAL, 1);
                    if($countPOMat > 0) {
                        $hasVRActivoFo = $this->m_utils->getCountItemplanAptopLiquida($itemplan, ID_ESTACION_COAXIAL);//SI ES 0 SI SE PUEDE LIQUIDAR, SI ES 1 NO SE PUEDE YA QUE TIENE VR
                
                        if($hasVRActivoFo == 1) {
                            throw new Exception('FO tiene VR verificar');
                        }
                    }
                }
                if($row->idEstacion == ID_ESTACION_UM){
                    //TIENE ALMENOS OS EN ESTADO (CREADA, ASIGNADA, EJECUTANDO, VALIDANDO, APROBADA)
                    if($row->total > 0){
                        $has_estacionAnclaUM = '1';
                    }
                    //TIENE OS CULMINADA PARA LA ESTACION UM
                    if($row->total>0 && $row->total == $row->validado && $row->pct_avance == 100 && $row->subioEvidencias > 0){
                        $estacionAnclaUM_culminada = 1;
                    }
                }
				
				if($row->idEstacion == ID_ESTACION_OC_COAXIAL || $row->idEstacion == ID_ESTACION_OC_FO) {
                    if($flg_cambio_hab == 12 && $row->pct_avance == 100 && $row->subioEvidencias > 0) {
                        $flg_oc_liquida = 1;
                    }
                }
            }
            
            $liquidarObra = false;
            if($estadoItemPlan    ==  ID_ESTADO_PLAN_EN_OBRA || $estadoItemPlan == ID_ESTADO_TRUNCO){
                if($idProyecto == ID_PROYECTO_SISEGOS){
					if($flg_oc_liquida == 1) {
                        $liquidarObra = true;
                    } else {
						/**criterios de liquidacion de la obra
						 * FO AL 100% Y NO TIENE OS SIOM IGUAL (CREADA, ASIGNADA, EJECUTANDO, VALIDANDO, APROBADA) EN UM ||
						 * UM AL 100% Y NO TIENE OS SIOM IGUAL (CREADA, ASIGNADA, EJECUTANDO, VALIDANDO, APROBADA) EN FO ||
						 * FO AL 100% Y UM AL 100%
						 * **/
						if($estacionAnclaFO_culminada == 1 && $has_estacionAnclaUM == ''){
							$liquidarObra = true;
						}
						
						if($estacionAnclaUM_culminada == 1 && $has_estacionAnclaFO == ''){
							$liquidarObra = true;
						}
						
						if($estacionAnclaFO_culminada == 1 && $estacionAnclaUM_culminada == 1 && $has_estacionAnclaUM == '1' && $has_estacionAnclaFO == '1'){
							$liquidarObra = true;
						}
					}
                }else if($idProyecto == ID_PROYECTO_MOVILES){
                    /**criterios de liquidacion de la obra                 
                     * UM AL 100% Y NO TIENE OS SIOM IGUAL (CREADA, ASIGNADA, EJECUTANDO, VALIDANDO, APROBADA) EN FO ||
                     * FO AL 100% Y UM AL 100%
                     * **/
					 /**czavala  moviles 2021 no tiene um**/
					 
				    if($estacionAnclaFO_culminada == 1 && $has_estacionAnclaUM == ''){//czavala 29.04.202
                        $liquidarObra = true;
                    }
                    if($estacionAnclaUM_culminada == 1 && $has_estacionAnclaFO == ''){
                        $liquidarObra = true;
                    }
                    
                    if($estacionAnclaFO_culminada == 1 && $estacionAnclaUM_culminada == 1 && $has_estacionAnclaUM == '1' && $has_estacionAnclaFO == '1'){
                        $liquidarObra = true;
                    }
                }else if($idProyecto == ID_PROYECTO_CRECIMIENTO_VERTICAL){/**MODIFICADO 19.04.2021 CZAVALACAS**/
                    if($idTipoSubProyecto    ==  TIPO_SUBPROYECTO_BUCLE){
                        /**criterios de liquidacion de la obra
                        * COAXIAL AL 100% Y NO TIENE OS SIOM IGUAL (CREADA, ASIGNADA, EJECUTANDO, VALIDANDO, APROBADA) EN FO ||
                        * FO AL 100% Y Y NO TIENE OS SIOM IGUAL (CREADA, ASIGNADA, EJECUTANDO, VALIDANDO, APROBADA) EN COAXIAL 
                        * FO AL 100% Y COAXIAL AL 100%
                        * **/
                        if($estacionAnclaFO_culminada == 1 && ($has_estacionAnclaCOAX == '' || $hasVRActivoCoaxial == 0)){
                            $liquidarObra = true;
                        }else if($estacionAnclaCOAX_culminada == 1 && ($has_estacionAnclaFO == '' || $hasVRActivoFo == 0)){
                            $liquidarObra = true;
                        }else if($estacionAnclaFO_culminada == 1 && $estacionAnclaCOAX_culminada == 1){
                            $liquidarObra = true;
                        }
                    }else if($idTipoSubProyecto ==   TIPO_SUBPROYECTO_INTEGRAL){
                        /**criterios de liquidacion de la obra
                         * FO AL 100% 
                         * **/
                        if($estacionAnclaFO_culminada == 1){
                            $liquidarObra = true;
                        }
                    }                
                }else if($idProyecto == ID_PROYECTO_OBRA_PUBLICA){
                    /**criterios de liquidacion de la obra                 
                     * LA PRIMERA ESTACION ANCLA QUE LLEGUE AL 100% LIQUIDA LA OBRA ( FO O COXIAL )
                     * **/
                    
                    /*if($estacionAnclaFO_culminada == 1 || $estacionAnclaCOAX_culminada == 1){
                        $liquidarObra = true;
                    }*/
                    
                    if($estacionAnclaFO_culminada == 1 && ($has_estacionAnclaCOAX == '' || $hasVRActivoCoaxial == 0)){
                        $liquidarObra = true;
                    }else if($estacionAnclaCOAX_culminada == 1 && ($has_estacionAnclaFO == '' || $hasVRActivoFo == 0)){
                        $liquidarObra = true;
                    }else if($estacionAnclaFO_culminada == 1 && $estacionAnclaCOAX_culminada == 1){
                        $liquidarObra = true;
                    }
                }else {
                    /**criterios de liquidacion de la obra                 
                     * NECESITA QUE TODAS SUS ESTACIONES ANCLAS ESTEN AL 100%  PARA LIQUIDAR LA OBRA ( FO Y COXIAL )
                     * **/
                    if($estacionAnclaFO_culminada == 1 && $estacionAnclaCOAX_culminada == 1 && $has_estacionAnclaCOAX == '1' && $has_estacionAnclaFO == '1'){
                        $liquidarObra = true;
                    }else if($estacionAnclaCOAX_culminada == 1 && $has_estacionAnclaCOAX == '1' && $has_estacionAnclaFO == ''){
                        $liquidarObra = true;
                    }else if($estacionAnclaFO_culminada == 1 && $has_estacionAnclaCOAX == '' && $has_estacionAnclaFO == '1'){
                        $liquidarObra = true;
                    }
                }              
            }
            
            if($liquidarObra){
                /**
                 * pasar a pre liqudiado y registrar un log!!
                 */
				if($estadoItemPlan == ID_ESTADO_TRUNCO) {
					$arrayUpdate = array(
											"trunco_situacion" => ID_ESTADO_PRE_LIQUIDADO,
											"fechaPreLiquidacion" =>  $this->fechaActual()
										);
					$data = $this->M_pqt_pre_liquidacion->updateEstadoPlanObraToPreLiquidado($itemplan, $arrayUpdate);
					if($data['error'] == EXIT_ERROR){
						throw new Exception($data['msj']);
					}else{
						$arrayDataLog = array(
							'tabla'            => 'planobra - trunco_situacion',
							'actividad'        => 'Trunca Pre-Liquidada',
							'itemplan'         => $itemplan,
							'fecha_registro'   => $this->fechaActual(),
							'id_usuario'       => $this->session->userdata('idPersonaSession'),
							'idEstadoPlan'     => ID_ESTADO_PRE_LIQUIDADO
						);
						$this->m_utils->registrarLogPlanObra($arrayDataLog);
					}
				} else {
					$arrayUpdate = array(
						"idEstadoPlan" => ID_ESTADO_PRE_LIQUIDADO,
						"usu_upd" => $this->session->userdata('idPersonaSession'),
						"fecha_upd" => $this->fechaActual(),
						"descripcion" => 'PRE LIQUIDACION',
						"fechaPreLiquidacion" =>  $this->fechaActual()
					);
					$data = $this->M_pqt_pre_liquidacion->updateEstadoPlanObraToPreLiquidado($itemplan, $arrayUpdate);
					if($data['error'] == EXIT_ERROR){
						throw new Exception($data['msj']);
					}else{
					
						$arrayDataLog = array(
							'tabla'            => 'planobra',
							'actividad'        => 'Obra Pre-Liquidada',
							'itemplan'         => $itemplan,
							'fecha_registro'   => $this->fechaActual(),
							'id_usuario'       => $this->session->userdata('idPersonaSession'),
							'idEstadoPlan'     => ID_ESTADO_PRE_LIQUIDADO
						);
						$this->m_utils->registrarLogPlanObra($arrayDataLog);
					}
				}
            } else {
                $data['error'] = EXIT_ERROR;
                $data['msj']   = 'No se pudo liquidar la obra, verificar que necesita.';
            }

        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }

    function liquidarSinSiom() {
        try {
            $data['error']    = EXIT_ERROR;
            $data['msj']      = null;

            $itemplan = $this->input->post('itemplan');

            if($itemplan == null || $itemplan == '') {
                throw new Exception('No se encontro el itemplan, verificar');
            }
            $data = $this->preliquidarSinSiom($itemplan);
            $data['tablaLiquidacion'] = $this->makeHtmlTablaLiquidacion($itemplan);
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function preliquidarSinSiom($itemplan){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        #EVALUACION PARA PASE A PRELIQUIDACION SISEGOS
        //DATA A RECORRER
        //obtEstacionesParaLiquidar

        try {
            $listDatosEstacionItemPlan=$this->M_pqt_pre_liquidacion->obtEstacionesParaLiquidar($itemplan);
            
            $estadoItemPlan = null;
            $idProyecto = null;
            $idSubProyecto = null;
            $idTipoSubProyecto = null;
            $has_estacionAnclaFO = '';
            $has_estacionAnclaCOAX = '';
            $has_estacionAnclaUM = '';
            $estacionAnclaFO_culminada = 0;
            $estacionAnclaCOAX_culminada = 0;
            $estacionAnclaUM_culminada = 0;
            $hasVRActivoCoaxial = 0;
            $hasVRActivoFo 		= 0;
            $flg_oc_liquida = null;
            foreach($listDatosEstacionItemPlan->result() as $row){
                $estadoItemPlan = $row->idEstadoPlan;
                $idProyecto = $row->idproyecto;
                $idSubProyecto = $row->idSubProyecto;
                $idTipoSubProyecto = $row->idTipoSubProyecto;
                $flg_cambio_hab    = $row->flg_cambio_hab;

                if($row->idEstacion == ID_ESTACION_FO){
                    //TIENE ALMENOS OS EN ESTADO (CREADA, ASIGNADA, EJECUTANDO, VALIDANDO, APROBADA)
                    if($row->total > 0){
                        $has_estacionAnclaFO = '1';
                    }
                    //TIENE OS CULMINADA PARA LA ESTACION FO
                    if($row->pct_avance == 100 && $row->subioEvidencias > 0){
                        $estacionAnclaFO_culminada = 1;
                    }
                    
                    $hasVRActivoCoaxial = $this->m_utils->getCountItemplanAptopLiquida($itemplan, ID_ESTACION_FO);//SI ES 0 SI SE PUEDE LIQUIDAR, SI ES 1 NO SE PUEDE YA QUE TIENE VR
                    
                    if($hasVRActivoCoaxial == 1) {
                        throw new Exception('COAX tiene VR verificar');
                    }
                }
                if($row->idEstacion == ID_ESTACION_COAXIAL){
                    //TIENE ALMENOS OS EN ESTADO (CREADA, ASIGNADA, EJECUTANDO, VALIDANDO, APROBADA)
                    if($row->total > 0){
                        $has_estacionAnclaCOAX = '1';
                    }
                    //TIENE OS CULMINADA PARA LA ESTACION COAXIAL
                    if($row->pct_avance == 100 && $row->subioEvidencias > 0){
                        $estacionAnclaCOAX_culminada = 1;
                    }
                    
                    $hasVRActivoFo = $this->m_utils->getCountItemplanAptopLiquida($itemplan, ID_ESTACION_COAXIAL);//SI ES 0 SI SE PUEDE LIQUIDAR, SI ES 1 NO SE PUEDE YA QUE TIENE VR
                
                    if($hasVRActivoFo == 1) {
                        throw new Exception('FO tiene VR verificar');
                    }
                }
                if($row->idEstacion == ID_ESTACION_UM){
                    //TIENE ALMENOS OS EN ESTADO (CREADA, ASIGNADA, EJECUTANDO, VALIDANDO, APROBADA)
                    if($row->total > 0){
                        $has_estacionAnclaUM = '1';
                    }
                    //TIENE OS CULMINADA PARA LA ESTACION UM
                    if($row->total>0 && $row->total == $row->validado && $row->pct_avance == 100 && $row->subioEvidencias > 0){
                        $estacionAnclaUM_culminada = 1;
                    }
                }

                if(($row->idEstacion == ID_ESTACION_OC_COAXIAL || $row->idEstacion == ID_ESTACION_OC_FO) &&
                    $row->pct_avance == 100 && $row->subioEvidencias > 0) {
                    if($flg_cambio_hab == 12) {
                        $flg_oc_liquida = 1;
                        $coutPoFoCoax = $this->m_utils->getCountPoCoaxFoByItem($itemplan);

                        if($coutPoFoCoax > 0) {
                            throw new Exception('Tiene PO COAX o FO, solo se permite OC');
                        }
                    }
                }
            }
            
            $liquidarObra = false;
            if($estadoItemPlan    ==  ID_ESTADO_PLAN_EN_OBRA){
                if($idProyecto == ID_PROYECTO_SISEGOS){
                    if($flg_oc_liquida == 1) {
                        $liquidarObra = true;
                    } else {
                        /**criterios de liquidacion de la obra
                         * FO AL 100% Y NO TIENE OS SIOM IGUAL (CREADA, ASIGNADA, EJECUTANDO, VALIDANDO, APROBADA) EN UM ||
                         * UM AL 100% Y NO TIENE OS SIOM IGUAL (CREADA, ASIGNADA, EJECUTANDO, VALIDANDO, APROBADA) EN FO ||
                         * FO AL 100% Y UM AL 100%
                         * **/
                        if($estacionAnclaFO_culminada == 1 && $has_estacionAnclaUM == ''){
                            $liquidarObra = true;
                        }
                        
                        if($estacionAnclaUM_culminada == 1 && $has_estacionAnclaFO == ''){
                            $liquidarObra = true;
                        }
                        
                        if($estacionAnclaFO_culminada == 1 && $estacionAnclaUM_culminada == 1 && $has_estacionAnclaUM == '1' && $has_estacionAnclaFO == '1'){
                            $liquidarObra = true;
                        } 
                    }
                }else if($idProyecto == ID_PROYECTO_MOVILES){
                    /**criterios de liquidacion de la obra                 
                     * UM AL 100% Y NO TIENE OS SIOM IGUAL (CREADA, ASIGNADA, EJECUTANDO, VALIDANDO, APROBADA) EN FO ||
                     * FO AL 100% Y UM AL 100%
                     * **/
                    if($estacionAnclaUM_culminada == 1 && $has_estacionAnclaFO == ''){
                        $liquidarObra = true;
                    }
                    
                    if($estacionAnclaFO_culminada == 1 && $estacionAnclaUM_culminada == 1 && $has_estacionAnclaUM == '1' && $has_estacionAnclaFO == '1'){
                        $liquidarObra = true;
                    }
                }else if($idProyecto == ID_PROYECTO_CRECIMIENTO_VERTICAL){/**MODIFICADO 19.04.2021 CZAVALACAS**/
                    if($idTipoSubProyecto    ==  TIPO_SUBPROYECTO_BUCLE || $idTipoSubProyecto == 3){
                        /**criterios de liquidacion de la obra
                        * COAXIAL AL 100% Y NO TIENE OS SIOM IGUAL (CREADA, ASIGNADA, EJECUTANDO, VALIDANDO, APROBADA) EN FO ||
                        * FO AL 100% Y Y NO TIENE OS SIOM IGUAL (CREADA, ASIGNADA, EJECUTANDO, VALIDANDO, APROBADA) EN COAXIAL 
                        * FO AL 100% Y COAXIAL AL 100%
                        * **/
                        if($estacionAnclaFO_culminada == 1 && ($has_estacionAnclaCOAX == '' || $hasVRActivoCoaxial == 0)){
                            $liquidarObra = true;
                        }else if($estacionAnclaCOAX_culminada == 1 && ($has_estacionAnclaFO == '' || $hasVRActivoFo == 0)){
                            $liquidarObra = true;
                        }else if($estacionAnclaFO_culminada == 1 && $estacionAnclaCOAX_culminada == 1){
                            $liquidarObra = true;
                        }
                    }else if($idTipoSubProyecto ==   TIPO_SUBPROYECTO_INTEGRAL){
                        /**criterios de liquidacion de la obra
                         * FO AL 100% 
                         * **/
                        if($estacionAnclaFO_culminada == 1){
                            $liquidarObra = true;
                        }
                    }                
                }else if($idProyecto == ID_PROYECTO_OBRA_PUBLICA){
                    /**criterios de liquidacion de la obra                 
                     * LA PRIMERA ESTACION ANCLA QUE LLEGUE AL 100% LIQUIDA LA OBRA ( FO O COXIAL )
                     * **/
                    
                    /*if($estacionAnclaFO_culminada == 1 || $estacionAnclaCOAX_culminada == 1){
                        $liquidarObra = true;
                    }*/
                    
                    if($estacionAnclaFO_culminada == 1 && ($has_estacionAnclaCOAX == '' || $hasVRActivoCoaxial == 0)){
                            $liquidarObra = true;
                        }else if($estacionAnclaCOAX_culminada == 1 && ($has_estacionAnclaFO == '' || $hasVRActivoFo == 0)){
                            $liquidarObra = true;
                        }else if($estacionAnclaFO_culminada == 1 && $estacionAnclaCOAX_culminada == 1){
                            $liquidarObra = true;
                        }
                }else {
                    /**criterios de liquidacion de la obra                 
                     * NECESITA QUE TODAS SUS ESTACIONES ANCLAS ESTEN AL 100%  PARA LIQUIDAR LA OBRA ( FO Y COXIAL )
                     * **/
                    if($estacionAnclaFO_culminada == 1 && $estacionAnclaCOAX_culminada == 1 && $has_estacionAnclaCOAX == '1' && $has_estacionAnclaFO == '1'){
                        $liquidarObra = true;
                    }else if($estacionAnclaCOAX_culminada == 1 && $has_estacionAnclaCOAX == '1' && $has_estacionAnclaFO == ''){
                        $liquidarObra = true;
                    }else if($estacionAnclaFO_culminada == 1 && $has_estacionAnclaCOAX == '' && $has_estacionAnclaFO == '1'){
                        $liquidarObra = true;
                    }
                }              
            }
            
            if($liquidarObra){
                /**
                 * pasar a pre liqudiado y registrar un log!!
                 */
                $arrayUpdate = array(
                    "idEstadoPlan" => ID_ESTADO_PRE_LIQUIDADO,
                    "usu_upd" => $this->session->userdata('idPersonaSession'),
                    "fecha_upd" => $this->fechaActual(),
                    "descripcion" => 'PRE LIQUIDACION',
                    "fechaPreLiquidacion" =>  $this->fechaActual()
                );
                $data = $this->M_pqt_pre_liquidacion->updateEstadoPlanObraToPreLiquidado($itemplan, $arrayUpdate);
                if($data['error'] == EXIT_ERROR){
                    throw new Exception($data['msj']);
                }else{
                
                    $arrayDataLog = array(
                        'tabla'            => 'planobra',
                        'actividad'        => 'Obra Pre-Liquidada',
                        'itemplan'         => $itemplan,
                        'fecha_registro'   => $this->fechaActual(),
                        'id_usuario'       => $this->session->userdata('idPersonaSession'),
                        'idEstadoPlan'     => ID_ESTADO_PRE_LIQUIDADO
                    );
                    $this->m_utils->registrarLogPlanObra($arrayDataLog);
                }
            } else {
                $data['error'] = EXIT_ERROR;
                $data['msj']   = 'No se pudo liquidar la obra, verificar que necesita.';
            }

        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }

    function cargarArchivoEvidencia($itemplan, $idEstacion, $descEstacion, $fileNamePruebas, $filePruebasTemp, $fileNamePerfil, $filePerfilTemp) {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        //DE NO EXISTIR LA CARPETA ITEMPLAN LA CREAMOS
        try {
            $pathItemplan = 'uploads/evidencia_fotos/'.$itemplan;
            if (!is_dir($pathItemplan)) {
                mkdir ($pathItemplan, 0777);
            }
            
            //DE NO EXISTIR LA CARPETA ITEMPLAN ESTACION LA CREAMOS
            $pathItemEstacion = $pathItemplan.'/'.$descEstacion;
			
            if(is_dir($pathItemEstacion)) {
                $this->rrmdir($pathItemEstacion);    
            }
			
			
            if (!is_dir($pathItemEstacion)) {
                mkdir ($pathItemEstacion, 0777);
            }
            
            //CREAMOS CARPETA DE PRUEBAS REFLECTOMETRICAS
            $pathReflectometricas = $pathItemEstacion.'/P_REFLECTOMETRICAS';
            if (!is_dir($pathReflectometricas)) {
                mkdir ($pathReflectometricas, 0777);
            }
            
            //CREAMOS CARPETA DE PRUEBAS DE PERFIL
            $pathPerfil = $pathItemEstacion.'/P_PERFIL';
            if (!is_dir($pathPerfil)) {
                mkdir ($pathPerfil, 0777);
            }

            $uploadfile1 = $pathReflectometricas.'/'. basename($fileNamePruebas);

            if (move_uploaded_file($filePruebasTemp, $uploadfile1)) {
                log_message('error', 'Se movio el archivo a la ruta 1.'.$uploadfile1);
            }else {
                throw new Exception('Hubo un problema con la carga del archivo 1 al servidor, comuniquese con el administrador.');
            }

            $uploadfile2 = $pathPerfil.'/'. basename($fileNamePerfil);
           
            if (move_uploaded_file($filePerfilTemp, $uploadfile2)) {
                log_message('error', 'Se movio el archivo a la ruta 2.'.$uploadfile2);
            }else {
                throw new Exception('Hubo un problema con la carga del archivo 2 al servidor, comuniquese con el administrador.');
            }
            
            $dataFormularioEvidencias = array(
                'itemplan'          =>  $itemplan,
                'fecha_registro'    => $this->fechaActual(),
                'usuario_registro'  => $this->session->userdata('idPersonaSession'),
                'idEstacion'        => $idEstacion,
                'path_pdf_pruebas'  =>  $uploadfile1,
                'path_pdf_perfil'   =>  $uploadfile2
            );

            $data = $this->M_pqt_pre_liquidacion->registrarEvidencias($dataFormularioEvidencias);
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }    
        return $data;     
    }

    ///////////////////////////////// FERNANDO DE MIERDA

    public function getMaterialesxPO()
    {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {

            $itemplan = $this->input->post('itemplan') ? $this->input->post('itemplan'): null;
			$idEstacion = $this->input->post('idEstacion') ? $this->input->post('idEstacion'): null;
			$idUsuarioSession = $this->session->userdata('idPersonaSession');

			if (!isset($idUsuarioSession)) {
                throw new Exception('Su sesi&oacute;n ha expirado, ingrese nuevamente!!');
            }

            if ($itemplan == null) {
                throw new Exception('Hubo un error al recibir el itemplan');
            }
			if ($idEstacion == null) {
                throw new Exception('Hubo un error al recibir la estación');
            }
			$listaPO = $this->m_utils->getPOsByIPEstacion($itemplan, $idEstacion);
			list($arrayGlobMat,$htmlCabeTab,$htmlBodyTab,$arrayCount) = $this->makeHTMLTabsPO($listaPO);
			$data['error'] = EXIT_SUCCESS;
			$data['htmlCabeTabs'] = $htmlCabeTab;
			$data['htmlBodyTabs'] = $htmlBodyTab;
			$data['arrayGlobMat'] = $arrayGlobMat;
			$data['arrayCount'] = $arrayCount;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        echo json_encode($data);

    }

	public function makeHTMLTabsPO($listaPO)
    {
		$htmlCabeTab= '';
		$htmlBodyTab = '';
		$count = 0;
		$arrayGlobMat = array();
		$flgIni = 0;
		$arrayCount = array();
        
		foreach ($listaPO as $row) {
			$htmlCabeTab .= '<li class="nav-item">
								<a class="nav-link '.($flgIni == 0 ? 'active' : '').'" data-toggle="tab" data-count="'.$count.'" data-codigopo="'.$row->codigo_po.'" href="#contPO'.$count.'" role="tab" id="tabPO'.$count.'" onclick="onTab(this)">'.$row->codigo_po.'</a>
							</li>';
			list($html, $arrayMat) =  $this->makeHTMLTablaVR($this->m_detalle_obra->getPPODetalle2($row->codigo_po,$row->idSubProyecto),$count);
			$htmlBodyTab .= '<div class="'.($flgIni == 0 ? 'tab-pane active fade show' : 'tab-pane fade').'" id="contPO'.$count.'" role="tabpanel">

								<div>
									<br>
									<div class="col-sm-12 form-group">
										<div class="row">
											<div class="col-sm-12 col-md-12" style="text-align: center;">
												<div class="form-group">
													<div class="btn-group" role="group" aria-label="Basic example">
														<button class="btn btn-success" data-estacion="'.$row->idEstacion.'" data-idsubproy="'.$row->idSubProyecto.'"
														    data-count="'.$count.'" data-count="'.$count.'" data-codigo_po="'.$row->codigo_po.'"
															type="button" onclick="openModalKitMat(this)" id="btnKit'.$count.'">Vizualizar Kit
														</button>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							 	<div id="contTablaPO'.$count.'" class="table-responsive">'
							 	.$html.'</div></div>';

			$arrayGlobMat[] = $arrayMat;
			$flgIni = 1;
			$arrayCount[] = $count;
			$count++;
		}

       
        return array($arrayGlobMat,$htmlCabeTab,$htmlBodyTab,$arrayCount);
    }

	public function makeHTMLTablaVR($ListaDetallePO,$contador)
    {
        $html = '
				<table id="table_po_'.$contador.'" class="table table-bordered">
					<thead class="thead-default">
						<tr>
							<th style="text-align: center; vertical-align: middle;">MATERIAL</th>
							<th style="text-align: center; vertical-align: middle;">DESCRIPCION</th>
							<th style="text-align: center; vertical-align: middle;">UDM</th>
							<th style="text-align: center; vertical-align: middle;">CANT. ING.</th>
							<th style="text-align: center; vertical-align: middle;">CANT. NUEVA.</th>
							<th style="background-color: #0095ff; width: 10%;">Registro en SAP/Ctd.nec.</th>
							<th style="background-color: #0095ff; width: 10%;">Contabilizado SAP/Ctd.red.</th>
							<th style="background-color: #0095ff; width: 10%;">Pdte contabilizar</th>
							<th style="background-color: #0095ff; width: 10%;">Mat devuelto</th>
						</tr>
					</thead>
					<tbody>';

        $count = 0;
        $htmlBody = '';
		$arrayMat = array();

        if ($ListaDetallePO != '') {
            foreach ($ListaDetallePO as $row) {

				$arrayMat[] = array(
					"codigo_po" => $row->codigo_po,
					"codigo_material" => $row->codigo_material,
					"cantidad_final_anterior" => (double) $row->cantidad_final,
					"cantidad_final" => (double) $row->cantidad_final,
					"cantidad_ingreso" => (double) $row->cantidad_ingreso,
					"costo_material" => (double) $row->costo_material
				);


				$htmlBody .= '
								<tr>
									<th>' . $row->codigo_material. '</th>
									<td>
										<div style="width: 250px;">
											' . utf8_encode($row->descrip_material) . '
										</div>
									</td>
									<td>' . $row->unidad_medida . '</td>
									<td>' . $row->cantidad_final . '</td>
									<td style="text-align: center;">
										<input type="text" id="material_'.$contador.'_'.$row->codigo_material.'" name="material_'.$contador.'_'.$row->codigo_material.'"
										data-idmaterial="'.$row->codigo_material.'" data-contador="'.$contador.'" data-posicion="'.$count.'" onblur="changeMontoMaterial(this)"
										style="text-align: center;"	class="numerico form-control" value="' . (int) $row->cantidad_final . '">
									</td>
									<td>' . (isset($row->cantidad_solicitada) ? number_format($row->cantidad_solicitada,2) : $row->cantidad_solicitada) . '</td>
									<td>' . (isset($row->cantidad_retirada) ? number_format($row->cantidad_retirada,2) : $row->cantidad_retirada) . '</td>
									<td>' . (isset($row->cantidad_pdt_retirada) ? number_format($row->cantidad_pdt_retirada,2) : $row->cantidad_pdt_retirada) . '</td>
									<td>' . (isset($row->cantidad_devuelta) ? number_format($row->cantidad_devuelta,2) : $row->cantidad_devuelta) . '</td>
								</tr>  ';
                $count++;

            }

            $html .= $htmlBody . '</tbody>
                </table>';

        } else {
            $html .= '</tbody>
                </table>';
        }

        return array(utf8_decode($html), $arrayMat);
    }

	public function updateDetallePO()
    {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;

        try {

            $itemplan = $this->input->post('itemplan') ? $this->input->post('itemplan') : null;
            $codigo_po = $this->input->post('codigo_po') ? $this->input->post('codigo_po') : null;
			$idEstacion = $this->input->post('idEstacion') ? $this->input->post('idEstacion') : null;
			$posicion = $this->input->post('posicion');
			$arrayMateriales = $this->input->post('arrayMat') ? $this->input->post('arrayMat') : array();
			
			$idUsuarioSession = $this->session->userdata('idPersonaSession');

            $this->db->trans_begin();

			if (!isset($arrayMateriales) || !is_array($arrayMateriales)) {
                throw new Exception('Hubo un error al recibir los materiales!!');
            }
			if ($idEstacion == null) {
				throw new Exception('Hubo un error al recibir la estacion!!');
			}
			if (count($arrayMateriales) == 0) {
                throw new Exception('Debe tener materiales para guardar!!');
            }

			if (!isset($idUsuarioSession)) {
                throw new Exception('Su sesi&oacute;n ha expirado, ingrese nuevamente!!');
            }

			if ($itemplan == null) {
				throw new Exception('Hubo un error al recibir el itemplan!!');
			}

			if ($codigo_po == null) {
				throw new Exception('Hubo un error al recibir la po!!');
			}
			
			if ($idEstacion == null) {
				throw new Exception('Hubo un error al recibir la estacion!!');
			}
			
			if ($posicion == null) {
				throw new Exception('Hubo un error al recibir la posicion!!');
			}

			$arrayInfoIP = $this->m_utils->getInfoIP($itemplan);
			if($arrayInfoIP == null){
				throw new Exception('Hubo un error al traer los datos del itemplan!!');
			}
			if($arrayInfoIP['idEstadoPlan'] != 3){//SI LA OBRA ESTA EN TERMINADO
				throw new Exception('Sólo puede guardar si el itemplan está en obra!!');
			}

			$arrayLog = array();

			foreach ($arrayMateriales as $row){
				
				//$responseUpdateDet = $this->m_utils->updateDetPlanobraPO($itemplan, $row['codigo_po'], $row['codigo_material'], array("cantidad_final" => $row['cantidad_final']));
				//if($responseUpdateDet['error'] == EXIT_ERROR){
				//	throw new Exception($responseUpdateDet['msj']);
				//}
				$arrayInsertDet[] = array(
					"codigo_po" => $row['codigo_po'],
					"codigo_material" => $row['codigo_material'],
					"cantidad_ingreso" => $row['cantidad_ingreso'],
					"cantidad_final" => $row['cantidad_final'],
					"costo_material" => $row['costo_material']
				);
				$arrayLog[] = array(
					"itemplan" =>  $itemplan,
					"idEstadoPlan" => $arrayInfoIP['idEstadoPlan'],
					"codigo_po" => $row['codigo_po'],
					"codigo_material" => $row['codigo_material'],
					"cantidad_final_anterior" =>  $row['cantidad_final_anterior'],
					"cantidad_final_nuevo" =>  $row['cantidad_final'],
					"id_usuario_reg" => $idUsuarioSession,
					"fecha_registro" => date("Y-m-d H:i:s")
				);
			}
			
			$countDetalle = $this->m_detalle_obra->getCountDetallePoByCod($codigo_po);
			if($countDetalle > 0){
				$responseDelete = $this->m_detalle_obra->deleteDetallePoMat($codigo_po);
				if($responseDelete['error'] == EXIT_ERROR){
					throw new Exception($responseDelete['msj']);
				}
			}
			
            $responseInsertBatchDetPO = $this->m_detalle_obra->insertBatchDetallePoMat($arrayInsertDet);
			if($responseInsertBatchDetPO['error'] == EXIT_ERROR){
				throw new Exception($responseInsertBatchDetPO['msj']);
			}
			$responseInsertBatchLog = $this->m_detalle_obra->insertBatchLogDetallePO($arrayLog);
			if ($responseInsertBatchLog['error'] == EXIT_ERROR) {
				throw new Exception($responseInsertBatchLog['msj']);
			}
            
			$costoTotal = $this->m_detalle_obra->getCostoTotalPOMAT($codigo_po);
			if($costoTotal == null){
				throw new Exception('Hubo un error al traer el costo total de la PO!!');
			}
			$arrayUpdatePO = array(
				"costo_total" => $costoTotal
			);
			$responseUpdatePO = $this->m_detalle_obra->updatePO_2($itemplan, $codigo_po, $idEstacion, $arrayUpdatePO);
			if ($responseUpdatePO['error'] == EXIT_ERROR) {
				throw new Exception($responseUpdatePO['msj']);
			}
			$data['msj'] = $responseUpdatePO['msj'];
			$data['error'] = $responseUpdatePO['error'];

            
            $this->preliquidarSinSiom($itemplan);
            if ($data['error'] == EXIT_SUCCESS) {
                $this->db->trans_commit();
				list($html, $arrayMat) =  $this->makeHTMLTablaVR($this->m_detalle_obra->getPPODetalle2($codigo_po,$arrayInfoIP['idSubProyecto']),$posicion);
				$data['tablaVR'] = $html;
				$data['arrayMat'] = $arrayMat;
                $data['htmlEstaciones'] = $this->getHtmlEstaciones($itemplan);
            }

        } catch (Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }
	
	public function getKitMatPO()
    {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {

			$codigo_po = $this->input->post('codigo_po') ? $this->input->post('codigo_po') : null;
			$idSubProyecto = $this->input->post('idSubProyecto') ? $this->input->post('idSubProyecto') : null;
			$idEstacion = $this->input->post('idEstacion') ? $this->input->post('idEstacion') : null;
			$posicion = $this->input->post('posicion');
			$arrayMateriales = $this->input->post('arrayMat') ? $this->input->post('arrayMat') : array();

			if ($codigo_po == null) {
				throw new Exception('Hubo un error al recibir la po!!');
			}
            if ($idSubProyecto == null) {
                throw new Exception('Hubo un error al recibir el SubProyecto');
            }
			if ($idEstacion == null) {
                throw new Exception('Hubo un error al recibir la estación');
            }
			if ($posicion == null) {
                throw new Exception('Hubo un error al recibir la posición');
            }
			if (!isset($arrayMateriales) || !is_array($arrayMateriales)) {
                throw new Exception('Hubo un error al recibir los materiales!!');
            }

			if (count($arrayMateriales) == 0) {
                throw new Exception('Debe tener materiales para guardar!!');
            }
			$arrayIdsMat = array();
			foreach($arrayMateriales as $row){
				$arrayIdsMat[] = $row['codigo_material'];
			}

            list($html, $error) = $this->makeHTMLListaKit($codigo_po, $posicion, $this->m_detalle_obra->getKitMatBySubProyEstacion($idSubProyecto,$idEstacion,$arrayIdsMat));
            $data['htmlKitMat'] = $html;
            $data['error'] = $error;

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        echo json_encode($data);

    }
	
	public function makeHTMLListaKit($codigo_po,$posicion,$listaKitMat)
    {
		$html = '
				<table id="table_kit" class="table table-bordered">
					<thead class="thead-default">
						<tr>
							<td style="text-align: center; background-color: #004062; color: white;">MATERIAL</td>
							<td style="text-align: center; background-color: #004062; color: white;">DESCRIPCION</td>
							<td style="text-align: center; background-color: #004062; color: white;">CANT. INGRESADA</td>
							<td style="text-align: center; background-color: #004062; color: white;">ACCION</td>
						</tr>
					</thead>
					<tbody>';

        $count = 1;
        $error = 1;


        if (is_array($listaKitMat) && count($listaKitMat) > 0) {
            foreach ($listaKitMat as $row) {
				$html .= '<tr id="trKit_'.$count.'">
				          	<td style="text-align: center" colspan="1">'.$row->codigo_material.'</td>
				          	<td style="text-align: center" colspan="1">'.utf8_encode($row->descrip_material).'</td>
							<td style="text-align: center" colspan="1">
								<input type="text" id="kit_'.$count.'_'.$row->codigo_material.'" name="kit_'.$count.'_'.$row->codigo_material.'"
								style="text-align: center;"	class="numerico form-control">
							</td>
							<td style="text-align: center" colspan="1">
								<a title="Añadir material"data-codigo_po="'.$codigo_po.'" data-codigomat="'.$row->codigo_material.'"   
								data-costomat="'.$row->costo_material.'" data-posicion="'.$posicion.'" data-poskit="'.$count.'"
								data-descmat="'.utf8_encode($row->descrip_material).'" data-unidadm="'.$row->unidad_medida.'" onclick="agregarMat(this)">
									<i class="zmdi zmdi-hc-2x zmdi-plus-circle" style="color: #005ac2;"></i>
								</a>
							</td>
						  </tr>
				';
                $count++;
            }
        } else {
			$html .= '<tr>
						<td style="text-align: center; font-weight: bold; color: red;" colspan="4">Esta PO ya cuenta con todos los materiales del KIT..</td>
						<td style="display:none;" colspan="1"></td>
						<td style="display:none;" colspan="1"></td>
						<td style="display:none;" colspan="1"></td>
			          </tr>';
        }
		$html .= '</tbody>
		</table>';

		$error = 0;

        return array(utf8_decode($html), $error);
    }
	
	

    ////////////////////////////////////////// FIN FERNANDO DE MIERDA

    function registrarFormObraPub() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $this->db->trans_begin();

            $arrayJson = json_decode($this->input->post('jsonFormObrasP'));
            //$arrayJson = $this->input->post('jsonFormObrasP'); $jsonDataForm->itemplan
            $arrayJson->usuario_registro = $this->session->userdata('idPersonaSession');
            $arrayJson->fecha_registro   = $this->fechaActual();
    
            $flg = $this->M_porcentaje->insertFormObraP($arrayJson);
            if($flg == 1) {
                $val = $this->registrarFicha($arrayJson->itemplan, null, $arrayJson->idEstacion, 5, null);
    
                if($val == 1) {
                    list($html, $cant, $idProyecto, $idEstadoPlan, $indicador, $flgZonal) = $this->Estaciones($arrayJson->itemplan, $arrayJson->idEstacion, 1);
                    $this->ingresaItemPlanEstacionAvance($arrayJson->itemplan, $arrayJson->idEstacion, $cant, null, null);
                    $data['error'] = EXIT_SUCCESS;
                    $data['msj']   = 'Formulario a asd registrado correctamente';
                    
                    //DE NO EXISTIR LA CARPETA ITEMPLAN LA CREAMOS
                    $pathItemplan = 'uploads/evidencia_fotos/'.$arrayJson->itemplan;
                    if (!is_dir($pathItemplan)) {
                        mkdir ($pathItemplan, 0777);
                    }
                    
                    //DE NO EXISTIR LA CARPETA ITEMPLAN ESTACION LA CREAMOS
                    $pathItemEstacion = $pathItemplan.'/'.'FO';
                    if (!is_dir($pathItemEstacion)) {
                        mkdir ($pathItemEstacion, 0777);
                    }
                    
                    //CREAMOS CARPETA DE PRUEBAS REFLECTOMETRICAS
                    $pathReflectometricas = $pathItemEstacion.'/P_REFLECTOMETRICAS';
                    if (!is_dir($pathReflectometricas)) {
                        mkdir ($pathReflectometricas, 0777);
                    }
                    
                    //CREAMOS CARPETA DE PRUEBAS DE PERFIL
                    $pathPerfil = $pathItemEstacion.'/P_PERFIL';
                    if (!is_dir($pathPerfil)) {
                        mkdir ($pathPerfil, 0777);
                    }
                    
                    $uploadfile1 = $pathReflectometricas.'/'. basename($_FILES['pruebasReflectonometricas']['name']);
                    
                    if (move_uploaded_file($_FILES['pruebasReflectonometricas']['tmp_name'], $uploadfile1)) {
                        log_message('error', 'Se movio el archivo a la ruta 1.'.$uploadfile1);
                    }else {
                        throw new Exception('Hubo un problema con la carga del archivo 1 al servidor, comuniquese con el administrador.');
                    }
                    
                    $uploadfile2 = $pathPerfil.'/'. basename($_FILES['pruebasPerfil']['name']);
                    
                    if (move_uploaded_file($_FILES['pruebasPerfil']['tmp_name'], $uploadfile2)) {
                        log_message('error', 'Se movio el archivo a la ruta 2.'.$uploadfile2);
                    }else {
                        throw new Exception('Hubo un problema con la carga del archivo 2 al servidor, comuniquese con el administrador.');
                    }
                    
                    $dataFormularioEvidencias = array(
                        'itemplan'          =>  $arrayJson->itemplan,
                        'fecha_registro'    => $this->fechaActual(),
                        'usuario_registro'  => $this->session->userdata('idPersonaSession'),
                        'idEstacion'        => $arrayJson->idEstacion,
                        'path_pdf_pruebas'  =>  $uploadfile1,
                        'path_pdf_perfil'   =>  $uploadfile2
                    );
                    
                    $data = $this->M_pqt_pre_liquidacion->registrarEvidencias($dataFormularioEvidencias);
                    
                    if($data['error'] == EXIT_ERROR) {
                        throw new Exception($data['msj']);
                    }
                } else {
                    throw new Exception('NDP');
                }
            } else {
                throw new Exception('NDP');
            }
            
            //PRE LIQUIDAR ESTACION ITEMPLAN
            $this->liquidarEstacion($arrayJson->itemplan, $arrayJson->idEstacion);
            
            //PRE LIQUIDAR EL ITEMPLAN
            $data = $this->preliquidar($arrayJson->itemplan);

            if($data['error'] == EXIT_ERROR) {
                throw new Exception($data['msj']);
            }

            $this->db->trans_commit();
        } catch(Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function Estaciones($itemPlan, $idEstacionTra, $flgEstado) {
        $arr = $this->M_porcentaje->ListarEstacion($itemPlan)->result();
        $obra = $this->M_generales->itemPlanI($itemPlan);
        $zonal = $obra["idZonal"];
        $idEstadoPlanJson=null;
        if($flgEstado==1) {
            $idEstadoPlanJson = $obra["idEstadoPlan"];
        }
        $html="";
        $test=count($arr);
        $css="";
        $idProyecto = null;
        $porcentajeEstacion = null;
        $idEstadoPlan = null;
        $indicador = null;
        $cont = 0;
        
        foreach($arr as $row) {
            $buttonPtr = null;
    
            if($row->idTipoPlanta == 2) {
                $buttonPtr = '<a data-scroll-nav="1" class="btn btn-success btn-rounded  btn-anim mt-10"
                                data-itemplan="'.$itemPlan.'" data-id_estado_plan="'.$row->idEstadoPlan.'" data-id_subproyecto="'.$row->idSubProyecto.'" onclick="openModalPTR($(this));"><i class="fa fa-pencil"></i><span class="btn-text">Consulta PTR</span>
                              </a>';
            }
            $s=0;
            $porcentaje_total=0;
            $racti=$this->M_porcentaje->ActividadEstacion($row->idEstacion);
            if(in_array($zonal,array(8,9,10,11,12)) && $racti->result()) {
                if($racti){
                    foreach($racti->result() as $act) {
                        $avalor=$this->M_porcentaje->ActividadItemPlan($itemPlan, $act->id_subactividad);
                        if($avalor["id_planobra_actividad"]){
                            $aporcentaje=$this->M_porcentaje->Porcentaje($avalor["id_planobra_actividad"]);
                            $porcentaje=$aporcentaje["valor"];
                            if(!$porcentaje){$porcentaje=0;}
                        }else{
                            $porcentaje=0;
                        }
                        $arrr[$s]= '<span class="font-12 head-font txt-dark">'.utf8_decode($act->nombre).'<span class="pull-right">'.$porcentaje.'%</span></span>
                                                <div class="progress mt-10 mb-30">
                                                <div class="progress-bar progress-bar-info" aria-valuenow="<?php echo $cant;?>" aria-valuemin="0" aria-valuemax="100" style="width: '.$porcentaje.'%" role="progressbar"> <span class="sr-only">'.$porcentaje.'% Completado</span> </div></div>';
                        $s++;
                        $porcentaje_total=$porcentaje_total+$porcentaje;
                    }
                }
                 
                $cant=$porcentaje_total/count($arrr);
                $flgZonal = 0;
            }else{
                $cant = $this->M_porcentaje->getPorcentajeItPlanAvance($itemPlan, $row->idEstacion);
                $flgZonal = 1;
            }
            //$cant = $this->M_porcentaje->getPorcentajeItPlanAvance($itemPlan, $row->idEstacion);
            $buttonFoto = null;
            $ubic='uploads/evidencia_fotos/'.$itemPlan;
    
            if(is_dir($ubic)) {
                $nroArchivos = count(scandir('uploads/evidencia_fotos/'.$itemPlan)) - 2;
            }
    
            $ubicacion        = 'uploads/evidencia_fotos/'.$itemPlan.'/'.$row->estacionDesc;
            $ubicacionArch    = 'uploads/evidencia_fotos/'.$itemPlan.'/archivos_estacion/'.$row->estacionDesc;
            $buttonPorcentaje = '<a data-scroll-nav="1" class="btn btn-success btn-rounded  btn-anim mt-10 agregar_avance"
                                        data-id_proyecto="'.$row->idProyecto.'" data-id_serie_troba="'.$row->idSerieTroba.'" data-itemplan="'.$itemPlan.'" data-desc_estacion="'.$row->estacionDesc.'" data-id_estacion="'.$row->idEstacion.'" data-id_zonal="'.$zonal.'"
                                        onclick="openFormPorcentaje($(this));"><i class="fa fa-pencil"></i><span class="btn-text">Porcentaje</span>
                                </a>';
    
            $buttonArchivo = null;
            if($row->idEstadoPlan == ID_ESTADO_TERMINADO && $cant == 100) {
                $buttonPorcentaje = null;
                $buttonSelecSerie = null;
                $buttonArchivo    = null;
                $btnFormulario    = null;
                $buttonKitMateriales = null;
                //$buttonPtr           = null;
            }
    
            list($bcolor, $cant, $nota, $msjSerie, $buttonSelecSerie, $buttonArchivo, $msjArchivo, $btnVs, $arrayFlgActiFo) = $this->colorPorcentaje($cant,
                $row->idEstacion,
                $row->idSerieTroba,
                $zonal,
                $ubicacion,
                $row->estacionDesc,
                $ubicacionArch,
                $row->idEstadoPlan,
                $flgEstado,
                $row->idProyecto,
                $row->jefatura,
                $row->descEmpresaColab,
                $row->indicador,
                $row->countSwitchForm,
                $row->countSwitchObPublicas,
                $row->idTipoPlanta,
                $row->flgFecha,
                $flgZonal);
             
            $flg =null;
            $buttonFoto    = '<button data-scroll-nav="1" class="btn btn-success btn-rounded  btn-anim mt-10" onclick="openModalSubirFoto(\''.$row->estacionDesc.'\',\''.$row->idEstacion.'\',\''.$flg.'\',\''.$flg.'\',\''.$itemPlan.'\',\''.$row->idProyecto.'\',\''.$ubicacion.'\',\''.$row->idSubProyecto.'\')">
                                    <i class="fa fa-camera"><span class="btn-text">Evidencia</span></button>';
            
            $btnFormulario       = null;
            $buttonKitMateriales = null;
            if($row->idEstacion == ID_ESTACION_FO) {
                $countFicha     = $this->M_porcentaje->countFichaTecnica($itemPlan);
                $countFormObrap = $this->M_porcentaje->countFormObrap($itemPlan, $row->idEstacion);
    
                if($row->countSwitchForm == 1 && $countFicha == 0) {
                    $flg=2;
                    //$arrayData = $this->m_bandeja_ficha_tecnica->getTrabajosFichaTecnica(3);
                    $btnFormulario = '<button title="Ver" class="btn btn-success btn-rounded  btn-anim mt-10" data-indicador="'.$row->indicador.'" data-flg_from="'.$flg.'"
                                            data-jefatura="'.$row->jefatura.'" data-id_estado_plan="'.$row->idEstadoPlan.'" data-item_plan="'.$itemPlan.'" data-desc_emp_colab="'.$row->descEmpresaColab.'" data-id_estacion="'.$row->idEstacion.'"
                                             onclick="openModalBandejaEjecucion($(this));">
                                            <i class="fa fa-eye"></i>
                                            <span class="btn-text">Formulario</span>
                                      </button>';
                }
                if($row->countSwitchObPublicas == 1 && $countFormObrap == 0) {
                    $btnFormulario = '<button title="Ver" class="btn btn-success btn-rounded  btn-anim mt-10" data-indicador="'.$row->indicador.'" data-flg_from="'.$flg.'"
                                            data-jefatura="'.$row->jefatura.'" data-item_plan="'.$itemPlan.'" data-desc_emp_colab="'.$row->descEmpresaColab.'" data-id_estacion="'.$row->idEstacion.'"
                                            onclick="openModalFormObPub($(this));">
                                            <i class="fa fa-eye"></i>
                                            <span class="btn-text">Formulario</span>
                                       </button>';
                }
    
                if($row->countSwitchForm == 1) {
                    if($row->flgHoras == 1) {
                        //$buttonFoto = null;
                        //$btnFormulario = null;
                    }
                }
    
                if($row->idSubProyecto == ID_SUB_PROYECTO_CV_RESIDENCIA_FTTH || $row->idSubProyecto == 98 || $row->idSubProyecto == 396 || $row->idSubProyecto == 463) {
                    $hasMaterial = $this->M_porcentaje->hasRegistroMaterialByItemplan($itemPlan);
                    $onclick='';
                    $disabled='';
                    $icon = 'pencil';
                    if($hasMaterial == null){
                        $onclick='onclick="registrarKit($(this));"';
                        $accion = CREAR_REGISTRO;
                    }else if($hasMaterial != null){
                        if($hasMaterial==1){//PUEDE EDITAR
                            $onclick='onclick="registrarKit($(this));"';
                            $accion = EDITAR_REGISTRO;
                        }else if($hasMaterial==0){//NO PUEDE EDITAR
                            $accion = null;
                            $disabled = 'disabled="true"';
                            $icon = 'check';
                        }
    
    
                    }
                    $buttonKitMateriales = '<a data-scroll-nav="1" class="btn btn-success btn-rounded  btn-anim mt-10 agregar_avance"
                                                   '.$disabled.'  '.$onclick.'   data-accion="'.$accion.'" data-idsubpro="'.$row->idSubProyecto.'" data-itemplan="'.$itemPlan.'"
                                                    ><i class="fa fa-'.$icon.'"></i><span class="btn-text">Kit Mat.</span>
                                                </a>';
                }
            }
            if(in_array(0, $arrayFlgActiFo) && $row->idEstacion == ID_ESTACION_UM) {
                $btnFormulario       = null;
                $buttonKitMateriales = null;
                $buttonPorcentaje = null;
                $buttonFoto = null;
            }
    
            $html.='<div class="col-lg-3 col-md-6 col-sm-12 col-xs-12 mt-10">
                                    <div class="panel panel-default card-view">
                                        <div class="panel-heading '.$bcolor.'">
                                            <div class="pull-left">
                                                <h6 class="panel-title txt-dark">'.$row->estacionDesc.'</h6>
                                                <h5 style="color:red">'.$nota.'</h5>
                                                <h5 style="color:red">'.$msjArchivo.$msjSerie.'</h5>
                                            </div>
                                            <div class="pull-right">
                                                    <span style="font-size:18px" class="label label-primary capitalize-font inline-block ml-10">'.$cant.'%</span>
                                                </div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="panel-wrapper collapse in">
                        <div class="panel-body">';
            if(in_array($zonal,array(8,9,10,11,12))||!$this->session->userdata('zonasSession')){
                if(@count($arrr)){
                    foreach ($arrr as $akey){
                        $html.=$akey;
                    }
                }
            }
    
    
            $html.=     '<div class="container-fluid">'
                .$buttonPorcentaje.' '.$buttonFoto.' '.$buttonSelecSerie.' '.$buttonArchivo.' '.$btnFormulario.' '.$buttonKitMateriales.' '.$buttonPtr.' '.$btnVs.'</div>
                            </div>
                        </div>
                    </div>
                </div>';
    
            unset($arrr);
            unset($aporcentaje);
            $idProyecto = $row->idProyecto;
            $indicador  = $row->indicador;
            if($idEstacionTra != null) {
                if($idEstacionTra == $row->idEstacion) {
                    $porcentajeEstacion = $cant;
                }
            }
        }
        return array($html, $porcentajeEstacion, $idProyecto, $idEstadoPlanJson, $indicador, $flgZonal);
        // unset($cant);
    }

    function colorPorcentaje($cant, $idEstacion, $serieTroba, $zonal, $ubicacion, $estacionDesc, $ubicacionArch, $idEstadoPlan, $flgEstado, $idProyecto, $jefatura, $descEmpresaColab, $indicador, $countSwitchForm, $countSwitchObPublicas, $tipoPlanta, $flgFecha, $flgZonal) {
    
        $msjArchivo = null;
        $itemPlan = $this->session->userdata('itemPlanIdFoto');
        $nota             = null;
        $buttonFoto       = null;
        $buttonSelecSerie = null;
        $nroArchivos      = null;
        $msjSerie         = null;
        $buttonArchivo    = null;
        $notaFormulario   = null;
        $btnVs            = null;
        $arrayFlgActiFo        = array();
    
        if(!is_dir($ubicacion)) {
            $nota="Estaci&oacute;n sin evidencia";
        }
        if(in_array($idEstadoPlan, array(ID_ESTADO_PLAN_EN_OBRA, ID_ESTADO_PRE_LIQUIDADO)) && $flgFecha == 1) {
            if($idEstacion == ID_ESTACION_INS_TROBA) {
                $buttonSelecSerie = '<button data-scroll-nav="1" class="btn btn-success btn-block btn-rounded  btn-anim mt-30" onclick="openModalSeleccionarSerie(\''.$itemPlan.'\',\''.$idEstacion.'\', \''.$estacionDesc.'\', \''.$serieTroba.'\');">
                                        <i class="fa fa-camera"></i><span class="btn-text">Seleccionar Serie</span></button>';
            }
    
            if($countSwitchObPublicas == 1) {
                $count = $this->M_porcentaje->countFormObrap($itemPlan, $idEstacion);
            }
    
            if($countSwitchForm == 1) {
                $count = $this->M_porcentaje->countFormularioSisego($itemPlan, 2);
    
                if($count > 0 && $idEstacion == ID_ESTACION_FO) {
                    $btnVs = '<button title="Ver" class="btn btn-success btn-rounded  btn-anim mt-10" data-item_plan="'.$itemPlan.'"
                                    onclick="openVs($(this));">
                                    <i class="fa fa-eye"></i>
                                    <span class="btn-text">Consulta Formulario</span>
                                </button>';
                }
            }
    
            if($countSwitchObPublicas == 1 || $countSwitchForm == 1) {
                if($count == 0 && $idEstacion == ID_ESTACION_FO && $cant > 10) {
                    $notaFormulario = "Registrar Formulario";
                    if($flgZonal == 0) {
                        $cant = $cant-10;
                    }
                }
            }
            // if($idEstacion == ID_ESTACION_COAXIAL || $idEstacion == ID_ESTACION_FO || $idEstacion == ID_ESTACION_UM
            // || $idEstacion == ID_ESTACION_MULTIPAR || $idEstacion == ID_ESTACION_PIN) {
            if($flgZonal == 0) {
                if(!is_dir($ubicacion) && $cant>10) {
                    $cant = ($cant > 0) ? $cant - 10 : $cant;
                    $nota="Subir Evidencia";
                }
            }
            // if($cant >= 85) {
    
            $flg=1;
            $descActividad = null;
            if($cant == 100) {
                //$arrayPO = $this->m_utils->getPoByItemplan($itemPlan, $idEstacion, FLG_TIPO_AREA_MAT, 3);
    
                if(is_dir($ubicacion)) {
                    $this->M_porcentaje->ingresarFlagEvidencia($itemPlan, 1, $idEstacion);
                }
                $countTrama = $this->M_porcentaje->countTrama($itemPlan, 'LIQUIDACION OBRA');
                if($countSwitchForm == 1 && $countTrama == 0 && $idEstacion == ID_ESTACION_FO && $idProyecto == 3) {
    
                   // $url = 'https://gicsapps.com:8080/obras2/recibir_eje.php';
                    $this->enviarTrama($itemPlan, $indicador, 2, $jefatura ,$descEmpresaColab);
                }
    
                if($countSwitchForm == 1 && $countTrama == 0 && $idEstacion == ID_ESTACION_UM && $idProyecto == 3) {
					/*
                    $url = 'https://www.gicsapps.com:8080/obras2/recibir_ejeUm.php';
    
                    $this->enviarTrama($itemPlan, $indicador, 2, $jefatura ,$descEmpresaColab, $url);
					*/
                }
                $arrayData = array('idEstadoPlan'              => ID_ESTADO_PRE_LIQUIDADO,
                    'fechaPreLiquidacion'       => $this->fechaActual(),
                    'id_usuario_preliquidacion' => $this->session->userdata('idPersonaSession'));
    
                
            } else {
                if($idEstadoPlan == ID_ESTADO_PLAN_EN_OBRA) {
                    if($cant == 'NR'){//NO REQUIERE
                        $cant = 0;
                    }
                    $flgValid = $this->M_porcentaje->getValidaSubProyecto($itemPlan, $idEstacion, $cant);
    
                    if($flgValid->flg_acti_fo == 0 || $flgValid->flg_acti_fo == 1) {
                        $arrayFlgActiFo = array($flgValid->flg_acti_fo);
                    }
                }
            }
    
        }
        $count = $this->M_porcentaje->countFormularioSisego($itemPlan, 2);
        if($count == 0 && $idEstacion == ID_ESTACION_FO && $cant > 10 && $countSwitchForm == 1) {
            $notaFormulario = "Registrar Formulario";
        }
    
        if($flgZonal == 0) {
            if($idEstacion == ID_ESTACION_INS_TROBA) {
                if($serieTroba == 0) {
                    $cant = ($cant > 4) ? $cant - 5 : $cant;
                    $msjSerie = 'Ingresar Serie';
                } else {
                    $cant = $cant + 5;
                    if($cant == 100) {
                        $this->M_porcentaje->ingresarFlagEvidencia($itemPlan, 1, $idEstacion);
                    }
                }
            }
        }
    
    
        if($cant == 100) {
            $this->M_porcentaje->ingresarFlagEvidencia($itemPlan, 1, $idEstacion);
        }
    
        if($cant == 100) {
            $this->M_porcentaje->updateEstadoPO($itemPlan, $idEstacion);
        }
    
        // }
        if($cant>=0 && $cant<25) {
            $bcolor="bg-red";
        }
        else if($cant>=25 && $cant<50) {
            $bcolor="bg-pink";
        }
    
        else if($cant>=50&&$cant<=99) {
            $bcolor="bg-yellow";
        }
        else if($cant == 100) {
            $bcolor="bg-green";
        }
        return array($bcolor, $cant, $nota, $msjSerie, $buttonSelecSerie, $buttonArchivo, $notaFormulario, $btnVs, $arrayFlgActiFo);
    }

    function registrarFichaSinfix($arrayJson, $itemPlan, $observacion, $idEstacion, $idFichaTecnicaBase) {
        $val = $this->registrarFicha($itemPlan, $observacion, $idEstacion, $idFichaTecnicaBase, $arrayJson);
    
        if($val == 1) {
            $data['error'] = EXIT_SUCCESS;
            $arrayDataLog = array(
                'tabla'            => 'sinfix',
                'actividad'        => 'Registro Ficha',
                'itemplan'         => $itemPlan,
                'fecha_registro'   => $this->fechaActual(),
                'id_usuario'       => $this->session->userdata('idPersonaSession'),
            );
    
            $this->m_utils->registrarLogPlanObra($arrayDataLog);
        } else {
            $data['error'] = EXIT_ERROR;
        }
    }
}