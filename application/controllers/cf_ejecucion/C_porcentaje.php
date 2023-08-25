<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_porcentaje extends CI_Controller {
    private $_idZonal  = null;
    private $_itemPlan = null;
    function __construct(){
        parent::__construct();
        $this->load->model('mf_ejecucion/M_porcentaje');
        $this->load->model('mf_ejecucion/M_generales');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_ejecucion/M_actualizar_porcentaje');    
        $this->load->model('mf_plantaInterna/M_aprobacion_interna');  
        $this->load->helper('url');
        $this->load->library('lib_utils');
        $this->load->library('zip');
        $this->load->library('table');
    }
    
    public function index(){
        $this->_itemPlan = $_GET["id"];
        $logedUser = $this->session->userdata('usernameSession');
        
        if($logedUser != null){
            $array = array(
                'itemPlanIdFoto' => $this->_itemPlan
            );
            $this->session->set_userdata($array);
        // if(@$_GET["pagina"]=="terminar"){
        //     $this->M_porcentaje->TerminarObra($_GET["id"]);
        //     ?>
             <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
             <script type="text/javascript">
        //     $(document).ready(function(){
        //         parent.$.fancybox.close();parent.location.reload(); 
        //     }); 
        //     </script>              
             <?php 
        // }
        $obra=$this->M_generales->itemPlanI($this->_itemPlan);
        // $data["boton_terminar"]="";
        // if($this->M_porcentaje->ValidarBotonTerminar($obra["idSubProyecto"],$obra["idZonal"]) == '0' && $obra["idEstadoPlan"]!=4){
        //         $data["boton_terminar"]='<div class="pull-right mr-30">
        //                                     <form method="post" action="porcentaje?pagina=terminar&id='.$_GET["id"].'">
        //                                         <button type="submit" class="btn btn-danger mr-10">
        //                                             Terminar Obra 
        //                                         </button>
        //                                     </form>
        //                                 </div>';
        // }
        $data["extra"]="";
        $this->_idZonal = $obra["idZonal"];
        $data["zonal"]=$this->_idZonal;
        list($html, $cant, $idProyecto) = $this->Estaciones($_GET["id"], null, 0);
        $data["estaciones"]= $html;
        $data["pagina"]="";
        $data["pagina_s"]="porcentaje";
        $data["nombre_proyecto"]=$obra["nombreProyecto"];            
        $this->load->view('vf_layaout_sinfix/header',$data);
        
        $this->load->view('vf_ejecucion/v_porcentaje',$data);

        $this->load->view('recursos_sinfix/js',$data);
        $this->load->view('recursos_sinfix/js_registro_ficha_tecnica',$data);
        $this->load->view('script/v_script',$data);
         }else{
             redirect('login','refresh');
        }
    }

    function getEstacionesFoto() {
        $dataJson['error']    = EXIT_ERROR;
        $dataJson['msj']      = null;
        try {
            $itemPlan   = $this->input->post('itemPlan');
            $idEstacion = $this->input->post('idEstacion');
            
            if($itemPlan == null) {
                throw new Exception("error");
            }
            
            if($idEstacion == null) {
                throw new Exception("error");
            }
            $flg=1;
            list($html, $cant, $idProyecto, $idEstadoPlan, $indicador) = $this->Estaciones($itemPlan, $idEstacion, $flg);

            $dataJson['estaciones']   = $html;
            $dataJson['porcentaje']   = $cant;
            $dataJson['idProyecto']   = $idProyecto;
            $dataJson['idEstadoPlan'] = $idEstadoPlan;
            $dataJson['indicador']    = $indicador;
            $dataJson['fecha']        = $this->fechaActual();
            $dataJson['error']        = EXIT_SUCCESS;
        } catch(Exception $e) {
        $dataJson['msj'] = $e->getMessage();
    } 
    echo json_encode(array_map('utf8_encode', $dataJson));
        // $obra     = $this->M_generales->itemPlanI($itemPlan);
        // $this->_idZonal = $obra["idZonal"];

        // $arr = $this->M_porcentaje->ListarEstacion($itemPlan)->result();
        // $this->Estaciones($this->_idZonal, $arr);
    }

    function Estaciones($itemPlan, $idEstacionTra, $flgEstado) {
        $arr = $this->M_porcentaje->ListarEstacion($itemPlan)->result();
        $obra = $this->M_generales->itemPlanI($itemPlan);
        $estaPlanOC = $obra["idEstadoPlan"];
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
       
        /***validacion a nivel estado plan**/
        $needVali = false;
        if(in_array($estaPlanOC,array(8,1,2))){//pre diseno, diseno, pre-registro
            $needVali = true;
        }
        foreach($arr as $row) {
          if(!$needVali || ($needVali && $row->idEstacion == ID_ESTACION_OC_FO)){
            $buttonPtr = null;

            // if($row->idTipoPlanta == 2) {
                // $buttonPtr = '<a data-scroll-nav="1" class="btn btn-success btn-rounded  btn-anim mt-10"
                                // data-itemplan="'.$itemPlan.'" data-id_estado_plan="'.$row->idEstadoPlan.'" data-id_subproyecto="'.$row->idSubProyecto.'" onclick="openModalPTR($(this));"><i class="fa fa-pencil"></i><span class="btn-text">Consulta PTR</span>
                              // </a>';
            // }
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
                                                                                                                            $flgZonal,
																															$row->idSubProyecto);
               
                $flg =null;
                // if($cant >= 80) {   
                 $buttonFoto    = '<button data-scroll-nav="1" class="btn btn-success btn-rounded  btn-anim mt-10" onclick="openModalSubirFoto(\''.$row->estacionDesc.'\',\''.$row->idEstacion.'\',\''.$flg.'\',\''.$flg.'\',\''.$itemPlan.'\',\''.$row->idProyecto.'\',\''.$ubicacion.'\',\''.$row->idSubProyecto.'\')">
                                                    <i class="fa fa-camera"></i><span class="btn-text">Evidencia</span></button>';
                // }

                // if($cant>=50&&$cant<=99){ $bcolor="bg-yellow";}    
                //     if($nroArchivos == 3) {
                //         $this->M_porcentaje->ingresarFlagEvidencia($itemPlan, 1);
                //     } 
                    
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
									  
									  _log($btnFormulario);
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
            
            /*************nuevo oc 08.08.2019 czavalacas****************/
            $buttonLiqOC = null;
            if($row->idEstacion == ID_ESTACION_OC_FO) {
                $canEditOC = $this->M_porcentaje->canEditOCLiqui($itemPlan);
                $onclick='onclick="liquidarOC($(this));"';
                $disabled='';
                $accionOc = 0;
                if($canEditOC != null && $canEditOC['cont'] > 0){
                    $accionOc = 1;
                    if($canEditOC['canEdit'] > 0){
                        $disabled = 'disabled="true"';
                        $onclick  = '';
                    }
                }               
				$onclick='onclick="liquidarOC($(this));"';	
				$disabled = '';				
                $icon = 'pencil';
                if($row->idSubProyecto == ID_SUB_PROYECTO_CV_RESIDENCIA_FTTH || $row->idSubProyecto == 98 || $row->idSubProyecto == 396 || $row->idSubProyecto == 463) {
                    $buttonLiqOC = '<a data-scroll-nav="1" class="btn btn-success btn-rounded  btn-anim mt-10 agregar_avance" data-accion="'.$accionOc.'" 
                                                   '.$disabled.'  '.$onclick.'   data-idEstacion="'.$row->idEstacion.'" data-idsubpro="'.$row->idSubProyecto.'" data-itemplan="'.$itemPlan.'"
                                                    ><i class="fa fa-'.$icon.'"></i><span class="btn-text">Liquidacion OC.</span>
                                                </a>';
                }
            }
            /*****************************************/
            
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
                                .$buttonPorcentaje.' '.$buttonFoto.' '.$buttonSelecSerie.' '.$buttonArchivo.' '.$btnFormulario.' '.$buttonKitMateriales.' '.$buttonPtr.' '.$btnVs.' '.$buttonLiqOC.'</div>
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
         }
        return array($html, $porcentajeEstacion, $idProyecto, $idEstadoPlanJson, $indicador, $flgZonal);
        // unset($cant);
        }

     function colorPorcentaje($cant, $idEstacion, $serieTroba, $zonal, $ubicacion, $estacionDesc, $ubicacionArch, $idEstadoPlan, $flgEstado, $idProyecto, $jefatura, $descEmpresaColab, $indicador, $countSwitchForm, $countSwitchObPublicas, $tipoPlanta, $flgFecha, $flgZonal, $idSubProyecto) {

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
                                
                                $url = 'https://172.30.5.10:8080/obras2/recibir_eje.php';
                                $this->enviarTrama($itemPlan, $indicador, 2, $jefatura ,$descEmpresaColab, $url); 
                            }
                            
                            if($countSwitchForm == 1 && $countTrama == 0 && $idEstacion == ID_ESTACION_UM && $idProyecto == 3) {
                                
                                $url = 'https://172.30.5.10:8080/obras2/recibir_ejeUm.php';
                                
                                $this->enviarTrama($itemPlan, $indicador, 2, $jefatura ,$descEmpresaColab, $url); 
                            }
                            $arrayData = array('idEstadoPlan'              => ID_ESTADO_PRE_LIQUIDADO,
                                               'fechaPreLiquidacion'       => $this->fechaActual(),
                                               'id_usuario_preliquidacion' => $this->session->userdata('idPersonaSession'));

                            if($idEstadoPlan == ID_ESTADO_PLAN_EN_OBRA) {
                                $data = null;
                                 if($cant == 'NR'){//NO REQUIERE
                                    $cant = 0;
                                }
                                $flgValid = $this->M_porcentaje->getValidaSubProyecto($itemPlan, $idEstacion, $cant);

                                if($idProyecto == 2) {
                                    if($idEstacion == ID_ESTACION_UM) {//SOLO EN UM AL LLEGAR A 100 SE LIQUIDA
                                        $data = $this->M_porcentaje->updateEstadoPlanObra($itemPlan, $arrayData);
                                    }
                                } else if($idProyecto == 3){
                                    $flgSisegoValid = $this->M_porcentaje->validaSisego($idEstacion, $cant, $itemPlan);
                                    
                                    if($flgSisegoValid == 1 || $flgSisegoValid == 2) {
                                        $data = $this->M_porcentaje->updateEstadoPlanObra($itemPlan, $arrayData);
                                    }
                                    
                                } else if($idSubProyecto == 96 || $idSubProyecto == 99 || $idSubProyecto == 395 || $idSubProyecto == 464) {//CV BUCLE
									if($idEstacion == 5 || $idEstacion == 2) {
										$data = $this->M_porcentaje->updateEstadoPlanObra($itemPlan, $arrayData);
									}
								} else {
                                    if($flgValid->flg_focoaxial == 1 || $flgValid->flg_focoaxial == 2 || $flgValid->flg_focoaxial == 3 || $flgValid->flg_focoaxial == 4 || $flgValid->flg_focoaxial == 5 ||  $flgValid->flg_acti_fo == 1) {
                                        $data = $this->M_porcentaje->updateEstadoPlanObra($itemPlan, $arrayData);
                                    }
                                }                     

                                
                                if($data == 0) {
                                    _log("FALLO AL ACUALIZAR EL ESTADO");
                                } else {
                                    $arrayDataLog = array(
                                        'tabla'            => 'planobra',
                                        'actividad'        => 'Obra Pre-Liquidada',
                                        'itemplan'         => $itemPlan,
                                        'fecha_registro'   => $this->fechaActual(),
                                        'id_usuario'       => $this->session->userdata('idPersonaSession'),
                                        'tipoPlanta'       => $tipoPlanta,
                                        'idEstadoPlan'     => ID_ESTADO_PRE_LIQUIDADO
                                    );
        
                                    $this->m_utils->registrarLogPlanObra($arrayDataLog);
                                }    
                            }
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
    
    function openModalSeleccionarSerie() {        
        $data['cmbSerieMostrar'] = __buildComboSerie();
        echo json_encode(array_map('utf8_encode', $data));
    }

    function ingresarSerieTroba() {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try {
            $itemPlan           = $this->input->post('itemPlan');
            $idSerieTroba       = $this->input->post('idSerieTroba');
            $idEstacion         = $this->input->post('idEstacion');
            // $descEstacion       = $this->input->post('descEstacion');

            if($itemPlan == NULL || $itemPlan == '') {
                throw new Exception('Acci&oacute;n no permitida');
            }

            $idSerieTrobaActual = $this->M_porcentaje->validarSerieTroba($itemPlan, $idSerieTroba);

            if($idSerieTrobaActual == NULL) {
                throw new Exception('Acci&oacute;n no permitida');
            } 

            if($idSerieTrobaActual != 0) {
                $data = $this->M_porcentaje->cambiarSerieTrobaAnterior($idSerieTrobaActual, 0);
            }
            // $arraySerieTroba = array( 
            //                             'idSerieTroba' => $idSerieTroba
            //                         );
            $data = $this->M_porcentaje->ingresarSerieTroba($itemPlan, $idSerieTroba);
            // $this->ingresarItemplanEstacionAvance_todo($itemPlan, $idEstacion,  $flgConfig, $descEstacion, $idSerieTrobaActual);
            list($html, $cant, $idProyecto, $idEstadoPlan, $indicador, $flgZonal) = $this->Estaciones($itemPlan, $idEstacion, 0);
            
            $this->ingresaItemPlanEstacionAvance($itemPlan, $idEstacion, $cant, null, null);
            //$data['error'] = EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function ejecutarPorcentaje() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            $id                  = $this->input->post('id');
            $idPlanObraActividad = $this->input->post('id_planobra_actividad');
            $idEstacion          = $_POST['idEstacion'];
            $idZonal             = $this->input->post('idZonal');
            $idActividad         = $this->input->post('id_subactividad');
            $idCuadrilla         = null;
            $porcentaje          = $this->input->post('fporcentaje');
            $conversacion        = $this->input->post('conversacion');
            $itemPlan            = $this->input->post('itemPlan');
            $idProyecto          = $this->input->post('idProyecto');
            $desEstacion         = $this->input->post('desEstacion');
            $idSerieActualBd     = $this->input->post('idSerieActualBd');
            
            $this->db->trans_begin();  

            // if($idProyecto == 3) {
            //     $dataSinfix = $this->M_porcentaje->getTramaSinfix($itemPlan, $idProyecto);
            //     // foreach($dataSinfix as $row) {
            //     // }
            // }

            $arrayLog = array ( 
                "idCuadrilla"      => $idCuadrilla,
                "porcentaje"       => $porcentaje,
                "fecha_registro"   => $this->fechaActual(),
                "usuario_registro" => $this->session->userdata('idPersonaSession'),
                "itemplan"         => $itemPlan,
                "idEstacion"       => $idEstacion,
                "detalle"          => $conversacion,
                "idActividad"      => $idActividad
                );
            $valid = $this->M_porcentaje->insertLogPorcentaje($arrayLog);

            if($valid == 0) {
                throw new Exception('error');
            }
            
            $racti=$this->M_porcentaje->ActividadEstacion($idEstacion);

            if(in_array($idZonal, array(8,9,10,11,12)) && $racti->result()) {
                $arrayData = array(
                    'id_planobra'           => $itemPlan,
                    'id_actividad'          => $this->M_porcentaje->getIdActividadJorge($idEstacion),
                    'id_subactividad'       => $idActividad,
                    // 'id_cuadrilla'          => $idCuadrilla,
                    'fecha'                 => $this->fechaActual(),
                    'estado'                => 1
                ); 
                
                $valida = $this->M_porcentaje->validarPlanObraActividad($itemPlan, $idEstacion, $idActividad);
                $arrayUp = array("id_planobra_actividad" => $idPlanObraActividad,
                                 "fecha"                 => $this->fechaActual(),
                                 "id_cuadrilla"          => $idCuadrilla,
                                 "porcentaje"            => $porcentaje,
                                 "conversacion"          => $conversacion,
                                 "estado_l"              => 1);
    
                $arrayInsert = array("id_agenda"			 => "",
                                     "id_planobra_actividad" => $idPlanObraActividad,
                                     "fecha"                 => $this->fechaActual(),
                                     "id_cuadrilla"          => $idCuadrilla,
                                     "porcentaje"            => $porcentaje,
                                     "conversacion"          => $conversacion,
                                     "estado_l"              => 1);                 
                if($valida == 0) {
                    $idPlanObraActividad = $this->M_porcentaje->insertDataPlanObraActividad($arrayData);
                    $arrayInsert['id_planobra_actividad'] = $idPlanObraActividad;
                    $this->M_porcentaje->insertAgenda($arrayInsert);
                } else {
                    $data = $this->M_porcentaje->updateAgenda($arrayUp, $idPlanObraActividad, $arrayInsert);
                    if($data == 0) {
                        throw new Exception('error');
                    }
                }
             
                $conversacion = null;
                list($html, $cant, $idProyecto, $idEstadoPlan, $indicador, $flgZonal) = $this->Estaciones($itemPlan, $idEstacion,1);

                $this->ingresaItemPlanEstacionAvance($itemPlan, $idEstacion, $cant, $conversacion, $idCuadrilla);
            }  else {
                $porcentaje = $this->getPorcentajeRestriccion($itemPlan, $idEstacion, $porcentaje, $desEstacion, null);
                $this->ingresaItemPlanEstacionAvance($itemPlan, $idEstacion, $porcentaje, $conversacion, $idCuadrilla,1);
                list($html, $cant, $idProyecto, $idEstadoPlan, $indicador, $flgZonal) = $this->Estaciones($itemPlan, $idEstacion, 1);
            }

            $dataJson['fecha'] = $this->fechaActual();

            $dataJson['estaciones']   = $html;
            $dataJson['porcentaje']   = $cant;
            $dataJson['idProyecto']   = $idProyecto;
            $dataJson['idEstadoPlan'] = $idEstadoPlan;
            $dataJson['indicador']    = $indicador;
            $this->db->trans_commit();
            $dataJson['error'] = EXIT_SUCCESS;
        } catch(Exception $e) {
            $this->db->trans_rollback();
            $dataJson['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $dataJson));
    }

    function getPorcentajeRestriccion($itemplan,$idEstacion, $porcentaje, $desEstacion, $flgSubio) {
        $ubicacion     = 'uploads/evidencia_fotos/'.$itemplan.'/'.$desEstacion;
        $ubicacionArch = 'uploads/evidencia_fotos/'.$itemplan.'/archivos_estacion/'.$desEstacion;
        $count = $this->M_porcentaje->countFormularioSisego($itemplan, 2);
        
        $idProyecto = $this->m_utils->getProyectoByItemplan($itemplan);
        if($idEstacion == ID_ESTACION_COAXIAL || $idEstacion == ID_ESTACION_FO || $idEstacion == ID_ESTACION_INS_TROBA || $idEstacion == ID_ESTACION_PIN || $idEstacion == ID_ESTACION_TRANSPORTE) {                            
            //Para todo zonal Lima, la foto debe ser obligatoria.
            if($flgSubio == 1) {
                $porcentaje =  100;
            } else {
                if(!is_dir($ubicacion) && $porcentaje>10) {
                    $porcentaje = ($porcentaje > 0) ? $porcentaje - 10 : $porcentaje;
                }
            }
        
        }
        // if($idEstacion == ID_ESTACION_COAXIAL || $idEstacion == ID_ESTACION_FO) {
        //     if(!is_dir($ubicacionArch) && $porcentaje>10) {
        //         $porcentaje = $porcentaje - 5;
        //     }
        // }
        if($idProyecto == 3) {
             if($count == 0 && $idEstacion == ID_ESTACION_FO && $porcentaje > 10) {
                $porcentaje = $porcentaje-10;
            }
        }
        return $porcentaje;
    }

    function ingresarItemplanEstacionAvance_todo($itemPlan, $idEstacion, $flgConfig, $desEstacion, $idSerieTrobaActual) {
        $ubicacion     = 'uploads/evidencia_fotos/'.$itemPlan.'/'.$desEstacion;
        $ubicacionArch = 'uploads/evidencia_fotos/'.$itemPlan.'/archivos_estacion/'.$desEstacion;

        $obra=$this->M_generales->itemPlanI($itemPlan);
        $this->_idZonal = $obra["idZonal"];

        if(in_array($this->_idZonal, array(8,9,10,11,12))) {
            $porcentaje = $this->M_porcentaje->getPorcetajeByestacionAct($itemPlan, $idEstacion);

            if($idEstacion == ID_ESTACION_COAXIAL || $idEstacion == ID_ESTACION_FO || $idEstacion == ID_ESTACION_INS_TROBA) {                            
                //Para todo zonal Lima, la foto debe ser obligatoria.
                if(!is_dir($ubicacion) && $porcentaje>10) {
                    $porcentaje = ($porcentaje > 0) ? $porcentaje - 10 : $porcentaje;
                }
            }
            if($idEstacion == ID_ESTACION_COAXIAL || $idEstacion == ID_ESTACION_FO) {
                if(!is_dir($ubicacionArch) && $porcentaje>10) {
                    $porcentaje = $porcentaje - 5;
                }
                // if($cant == 100 && $idProyecto == 3) {
                //     $data = $this->M_porcentaje->getTramaSinfix($itemPlan, $idProyecto);
                //     echo json_encode(array_map('utf8_encode', $data));
                // }
            }
            
                if($idEstacion == ID_ESTACION_INS_TROBA) {
                    if($idSerieTrobaActual == 0) {
                        $porcentaje = ($porcentaje > 0) ? $porcentaje - 5 : $porcentaje;
                    } else {
                        if($porcentaje == 100) {
                            $this->M_porcentaje->ingresarFlagEvidencia($itemPlan, 1, $idEstacion);
                        }
                        //$buttonPorcentaje = null; 
                    }
                }
            
            $conversacion = null;
            //$this->ingresaItemPlanEstacionAvance($itemPlan, $idEstacion, $porcentaje, $conversacion, $idCuadrilla);
            $valid = $this->M_porcentaje->validarItemPlanEstacionAvance($itemPlan, $idEstacion);
            if($valid == 0) {
                $arrayData = array(
                    'itemplan'     => $itemPlan,
                    'idEstacion'   => $idEstacion,
                    'porcentaje'   => $porcentaje,
                    'fecha'        => $this->fechaActual(),
                    'id_usuario_log' => $this->session->userdata('idPersonaSession')
                );
                $data = $this->M_porcentaje->insertItemPlanEstacionAvance($arrayData);
                if($data == 0) {
                    throw new Exception('error');
                }
            } else {
                $arrayData = array(
                    'porcentaje'   => $porcentaje,
                    'fecha'        => $this->fechaActual(),
                    'id_usuario_log' => $this->session->userdata('idPersonaSession')
                );
                $data = $this->M_porcentaje->updateItemPlanEstacionAvance($arrayData, $itemPlan, $idEstacion);
                if($data == 0) {
                    _log('error al actualizar  ingresarItemplanEstacionAvance_todo');
                    // throw new Exception('error');
                }
            }
            $arrayLog = array ( 
                "porcentaje"       => $porcentaje,
                "fecha_registro"   => $this->fechaActual(),
                "usuario_registro" => $this->session->userdata('idPersonaSession'),
                "itemplan"         => $itemPlan,
                "idEstacion"       => $idEstacion
                );
            $valid = $this->M_porcentaje->insertLogPorcentaje($arrayLog);

            if($valid == 0) {
                _log('error al insertar en log line 536');
            }
        }
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

    function comprimirFiles() {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $descEstacion = $this->session->userdata('descEstacionPreDi');
            $itemplan     = $this->session->userdata('itemPlan2');
            $subCarpeta   = $this->session->userdata('subCarpetaPreDi');
            $idEstacion   = $this->session->userdata('idEstacionPreDi');

            $this->zip->read_dir($subCarpeta,false);
            $fileName = $descEstacion.'_'.rand(1, 100).date("dmhis").'.zip';
            $this->zip->archive('uploads/ejecucion/'.$itemplan.'/'.$fileName);
            $data = $this->m_bandeja_adjudicacion->registrarNombreArchivo($itemplan, $fileName, $idEstacion);
            $this->rrmdir($subCarpeta);
            $data['error'] = EXIT_SUCCESS;
            $this->zip->download($fileName);
        }catch(Exception $e) {
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

    function subirFoto() {
        $descEstacion = $this->input->post('estacionDesc');
        $flgArchivo   = $this->input->post('flgArchivo');
        $descActividad = $this->input->post('descActividad');
        $idEstacion    = $this->input->post('idEstacion');
        $itemplan      = $this->input->post('itemplan');
        
        if($descActividad) {
            $this->session->set_userdata('descActividad', $descActividad);
        }
        
        $this->session->set_userdata('descEstacionFoto', $descEstacion);
        $this->session->set_userdata('flgArchivo', $flgArchivo);    
        $this->session->set_userdata('idEstacionFoto', $idEstacion);
        
        $ubicacion = 'uploads/evidencia_fotos/'.$itemplan.'/'.$descEstacion;
        if(is_dir($ubicacion)) {
            $data['arrayName'] = json_encode(array_diff(scandir($ubicacion), array('.', '..')));
        } else {
            $data['arrayName'] = json_encode(null);
        }
        echo json_encode($data);
    }
    
    function getArrayFiles() {
        $ubicacion = $this->input->post('ubicacion');
        if(is_dir($ubicacion)) {
            $data['arrayName'] = array_diff(scandir($ubicacion), array('.', '..'));
        } else {
            $data['arrayName'] = '';
        }
        echo json_encode($data);
    }

    function insertFoto() {
        $itemPlan = $this->session->userdata('itemPlanIdFoto');
        
        $file     = $_FILES ["file"] ["name"];
        $filetype = $_FILES ["file"] ["type"];
        $filesize = $_FILES ["file"] ["size"];
        
        $ubicacion = 'uploads/evidencia_fotos/'.$itemPlan;
        if (!is_dir($ubicacion)) {
            mkdir ('uploads/evidencia_fotos/'.$itemPlan, 0777);
        }
        $descEstacion = $this->session->userdata('descEstacionFoto');
        $flgArchivo   = $this->session->userdata('flgArchivo');
        $idEstacion   = $this->session->userdata('idEstacionFoto');   
        $subCarpeta   = 'uploads/evidencia_fotos/'.$itemPlan.'/'.$descEstacion;
        
        $typeFile = explode('.', $file);
        /* 12.09.2019 czavalacas permitir varios archivos no tiene la separacion adecuada
        if(is_dir($subCarpeta)) {
            $this->deleteFilexTipo($subCarpeta, $typeFile[1]); 
        }
		*/
        if($flgArchivo == 1) {
            //ARCHIVOS_X_ESTACION
            $ubicArchivo = 'uploads/evidencia_fotos/'.$itemPlan.'/archivos_estacion';
            if (!is_dir($ubicArchivo)) {
                mkdir('uploads/evidencia_fotos/'.$itemPlan.'/archivos_estacion', 0777);
            }
            $subCarpetaArch = 'uploads/evidencia_fotos/'.$itemPlan.'/archivos_estacion/'.$descEstacion;
            if (!is_dir($subCarpetaArch)) {
                mkdir ('uploads/evidencia_fotos/'.$itemPlan.'/archivos_estacion/'.$descEstacion, 0777);
            }
            $file2 = utf8_decode($file);
            if (!is_dir($subCarpetaArch))
            mkdir ( $subCarpetaArch, 0777 );
            if (utf8_decode($file) && move_uploaded_file($_FILES["file"]["tmp_name"], $subCarpetaArch."/".$file2 )) {
            }
        } else if($flgArchivo == 2) {
            //ARCHIVOS_X_ACTIVIDAD
            $descActividad = $this->session->userdata('descActividad');

            $ubicArchivo = 'uploads/evidencia_fotos/'.$itemPlan.'/archivos_actividad';
            if (!is_dir($ubicArchivo)) {
                mkdir('uploads/evidencia_fotos/'.$itemPlan.'/archivos_actividad', 0777);
            }
            $subCarpetaArch = 'uploads/evidencia_fotos/'.$itemPlan.'/archivos_actividad/'.$descEstacion;
            if (!is_dir($subCarpetaArch)) {
                mkdir ('uploads/evidencia_fotos/'.$itemPlan.'/archivos_actividad/'.$descEstacion, 0777);
            }

            $subCarpetaAct = 'uploads/evidencia_fotos/'.$itemPlan.'/archivos_actividad/'.$descEstacion.'/'.$descActividad;
            if (!is_dir($subCarpetaAct)) {
                mkdir ('uploads/evidencia_fotos/'.$itemPlan.'/archivos_actividad/'.$descEstacion.'/'.$descActividad, 0777);
            }

            $file2 = utf8_decode($file);
            if (!is_dir($subCarpetaAct))
            mkdir ( $subCarpetaAct, 0777 );
            if (utf8_decode($file) && move_uploaded_file($_FILES["file"]["tmp_name"], $subCarpetaAct."/".$file2 )) {
            }
        } else {
            //SUBIR FOTO Y ARCHIVO
            $this->session->set_userdata('subCarpetaPreDi',$subCarpeta);
            $this->session->set_userdata('itemPlan2',$itemPlan);
            $file2 = utf8_decode($file);
            if (!is_dir($subCarpeta))
                mkdir ( $subCarpeta, 0777 );
                if (utf8_decode($file) && move_uploaded_file($_FILES["file"]["tmp_name"], $subCarpeta."/".$file2 )) {
                }
        }
        // $this->ingresarItemplanEstacionAvance_todo($itemPlan, $idEstacion,  null, $descEstacion, 0);
        
        list($html, $cant, $idProyecto, $idEstadoPlan, $indicador, $flgZonal) = $this->Estaciones($itemPlan, $idEstacion, 0);
        if($flgZonal == 1) {
            $flgSubio = 1;
            $cant = $this->getPorcentajeRestriccion($itemPlan, $idEstacion, $cant, $descEstacion, $flgSubio);     
        }
        
        $conversacion = (isset($conversacion)) ? $conversacion : NULL;
        $idCuadrilla  = (isset($idCuadrilla))  ? $idCuadrilla  : NULL;
        $this->ingresaItemPlanEstacionAvance($itemPlan, $idEstacion, $cant, $conversacion, $idCuadrilla);

        $data['error'] = EXIT_SUCCESS;
        echo json_encode(array_map('utf8_encode', $data));
    }

    function ingresarCoordenada() {
        $itemPlan = $this->session->userdata('itemPlanIdFoto');
        
        $x            = $this->input->post('x');
        $y            = $this->input->post('y');
        $idEstacion   = $this->input->post('idEstacion');
        // if($x == NULL || $x == '') {
        //     throw Exception()
        // }
        if($x==NULL || $y == NULL) {
            _log("itemplan: ".$itemPlan." NO TIENE COORDENADAS");
        }

        if($idEstacion == ID_ESTACION_INS_TROBA|| $idEstacion == ID_ESTACION_COAXIAL || $idEstacion == ID_ESTACION_FO) {
            $this->M_porcentaje->insertXY($x, $y, $itemPlan);
        } 
        
        if($idEstacion == ID_ESTACION_COAXIAL || $idEstacion == ID_ESTACION_FO) {
            $row = $this->M_porcentaje->getIdPreDisenio($itemPlan, $idEstacion);
            if($row == NULL) {
                $arrayInsert=array(
                                    'coordX'     => $x,
                                    'coordY'     => $y,
                                    'itemplan'   => $itemPlan,
                                    'idEstacion' => $idEstacion
                                  );
                $this->M_porcentaje->insertPreDisenioCord($arrayInsert);                  
            } else {
                $this->M_porcentaje->updateFOCOXY($x, $y, $row['idpre_diseno']);            
            }
        }
        //$this->Estaciones($this->M_porcentaje->ListarEstacion($itemPlan)->result());
    }

    function getFormPorcentaje() {
        $itemPlan     = $this->input->post('itemplan');
        $idZonal      = $this->input->post('idZonal');
        $idEstacion   = $this->input->post('idEstacion');
        $descEstacion = $this->input->post('descEstacion');
        list($tabla, $porcentaje) = $this->formPorcentajeHTML($itemPlan, $idZonal, $idEstacion, $descEstacion);
        $data['formPorcentaje']   = $tabla;
        $data['porcentajeActual'] = $porcentaje;
        $data['eccSession']       = $this->session->userdata('eeccSession');

        echo json_encode(array_map('utf8_encode', $data));
    }

    function formPorcentajeHTML($itemPlan, $idZonal, $idEstacion, $descEstacion) {
        $tmpl = array(  'table_open'  => '<table class="table table-striped">',
                        'table_close' => '</table>');
        $this->table->set_template($tmpl);

        $subEst = (in_array($idZonal, array(8,9,10,11,12))) ? 'Actividad' : 'Estaci&oacute;n';

        $head_0 = array('data' => $subEst     , 'class' => 'text-center');
        // $head_1 = array('data' => 'Cuadrilla' , 'class' => 'text-center');
       // $head_2 = array('data' => 'Contrata'  , 'class' => 'text-center');
        $head_3 = array('data' => 'Porcentaje', 'class' => 'text-center'); 
        $head_4 = array('data' => 'Comentario', 'class' => 'text-center'); 
        $head_5 = array('data' => 'Acci&oacute;n'    , 'class' => 'text-center');        
        $this->table->set_heading($head_0, $head_3, $head_4, $head_5);
        $arrayLisAct= $this->M_generales->ListarActividadesEstacion($idEstacion)->result();
        $html = null;
        $i= 0;
        
        $racti=$this->M_porcentaje->ActividadEstacion($idEstacion);
        if(in_array($idZonal, array(8,9,10,11,12)) && $racti->result()) {
            foreach($arrayLisAct as $row) {
                // $regCuad = '<a style="cursor:pointer" title="Registrar Cuadrilla" onclick="openModalCuadrilla();"><i class="fa fa-book" style="font-size:30px"></i></a>';
                $i++;
                $row2 = $this->M_porcentaje->getPorcentajeActividad($itemPlan, $idEstacion, $row->id_subactividad);
                $porcentajeActual = $row2['porcentaje'];
                $html2 = __buildComboPorcentaje($i, $row2['porcentaje']);
                $isu=$this->M_actualizar_porcentaje->ExisteItemSubActividad($itemPlan, $row->id_subactividad);
                
                $btnActualizar = '<a title="Actualizar" id="'.$i.'" data-id_estacion="'.$idEstacion.'" style="cursor:pointer;" data-desc_estacion="'.$descEstacion.'"
                                    data-id_planobra_actividad="'.$isu["id_planobra_actividad"].'" data-id_actividad="'.$row->id_subactividad.'" data-item_plan="'.$itemPlan.'"
                                    onclick="ejecutarPorcentaje($(this))">
                                    <i class="fa fa-pencil" style="font-size:30px"></i>
                                  </a>';
                $btnDetalle    = '<a title="Ver" class="ver_detallec" id="'.$isu["id_planobra_actividad"].'" style="cursor:pointer;" id="'.$i.'" data-id_estacion="'.$idEstacion.'"
                                    data-id_planobra_actividad="'.$isu["id_planobra_actividad"].'" data-id_actividad="'.$row->id_subactividad.'" data-item_plan="'.$itemPlan.'" onclick="verDetalle($(this));">
                                    <i class="fa fa-eye" style="font-size:30px"></i>
                                  </a>';                
                $texArea       = '<textarea id="conversacion_'.$i.'"  class="form-control" name="conversacion">'.$row2['conversacion'].'</textarea>';
                $html .= '<select title="'.$i.'" name="select_cuadrilla" class="form-control js-example-basic-single cambiar_cuadrilla cuadrilla_'.$i.'">';
                $html .=    '<option value="0">Seleccionar</option>';
                $flagCamara=2;
                $ubicacion = 'uploads/evidencia_fotos/'.$itemPlan.'/'.$descEstacion;
                
                $camara  = '<a style="cursor:pointer" title="Ingresar foto por actividad" onclick="openModalSubirFoto(\''.$descEstacion.'\',\''.$idEstacion.'\',\''.$flagCamara.'\',\''.$row->snombre.'\',\''.$itemPlan.'\', \''.null.'\' ,\''.$ubicacion.'\', \''.null.'\');"><i class="fa fa-camera-retro" style="font-size:30px"></i></a>';
                
                // $arrayCmb = $this->M_porcentaje->getCuadrilla($idZonal, $itemPlan, $idEstacion, $isu["id_planobra_actividad"]);
                // foreach($arrayCmb as $lista) {
                    // $html.= '<option ';
                        // if($lista->flg_cuad_act==1) {
                            // $html.=' selected ';
                        // } 
                    // $html.= 'value="'.$lista->idCuadrilla.'">'.$lista->descripcion.'</option>';
                // }
                // $html .='</select>';

                $row_0 = array('data' => utf8_decode($row->snombre), 'class' => 'subactividad');
                $row_1 = array('data' => $html, 'class' => 'js-example-basic-single');
              // $row_2 = array('data' => 'PRUEBA');
                $row_3 = array('data' => $html2);
                $row_4 = array('data' => $texArea);
                $row_5 = array('data' => $btnActualizar." ".$btnDetalle."".$camara);
                $html = null;
                $this->table->add_row($row_0, $row_3, $row_4, $row_5);
            }
        } else {
            $row2 = $this->M_porcentaje->getPorcentajeEstacion($itemPlan, $idEstacion);
            $porcentajeActual = $row2['porcentaje'];
            $i++;
            $html2 = __buildComboPorcentaje($i, $row2['porcentaje']);
            $texArea = '<textarea id="conversacion_'.$i.'" class="form-control" name="conversacion">'.$row2['comentario'].'</textarea>';
            $btnActualizar = '<input type="button" value="Actualizar" class="btn btn-primary m-b-0" id="'.$i.'" data-item_plan="'.$itemPlan.'" data-desc_estacion="'.$descEstacion.'" data-id_estacion="'.$idEstacion.'" onclick="ejecutarPorcentaje($(this))">';
            //$regCuad       = '<input type="button" value="Registrar Cuadrilla" class="btn btn-primary m-b-0"  onclick="openModalCuadrilla()>';
            
            // $regCuad    = '<button class="btn  btn-info ver_detallec" onclick="openModalCuadrilla();">Registrar Cuadrilla</button>';                
            
            // $html .='<select title="'.$i.'" name="select_cuadrilla" class="form-control js-example-basic-single cambiar_cuadrilla cuadrilla_'.$i.'">';
            // $html .='<option value="0">Seleccionar</option>';

            // $arrayCmb = $this->M_porcentaje->getCuadrilla($idZonal, $itemPlan, $idEstacion, NULL);
            
            // foreach($arrayCmb as $lista) {    
                // $html.= '<option ';
                    // if($lista->flg_cuad_estacion==1) {
                        // $html.=' selected ';
                    // }
                // $html.= 'value="'.$lista->idCuadrilla.'">'.$lista->descripcion.'</option>';
            // }
            // $html .='</select>';
            
            $row_0 = array('data' => $descEstacion, 'class' => 'text-center');
            $row_1 = array('data' => $html);
            // $row_2 = array('data' => 'PRUEBA');
            $row_3 = array('data' => $html2);
            $row_4 = array('data' => $texArea);
            $row_5 = array('data' => $btnActualizar);
            $this->table->add_row($row_0, $row_3, $row_4, $row_5);   
        }
        $tabla = $this->table->generate();
        return array($tabla, $porcentajeActual);
    }
    
    function enviarTrama($itemPlan, $indicador, $from, $jefatura ,$descEmpresaColab, $url){
        // $data['error']    = EXIT_ERROR;
        // $data['msj']      = null;
        // $data['cabecera'] = null;
        // try{        
            // if($data['error'] == EXIT_ERROR){
            //     throw new Exception($data['msj']);
            // }           
            $dataSend = ['itemplan' => $itemPlan,
                         'fecha'    => $this->fechaActual(),
                         'sisego'   => $indicador];
            
            
            
            $response = $this->m_utils->sendDataToURL($url, $dataSend);
            if($response->error == EXIT_SUCCESS){
                $this->m_utils->saveLogSigoplus('LIQUIDACION OBRA', null , $itemPlan, null, $indicador, $descEmpresaColab, $jefatura, 'TRAMA COMPLETADA', 'OPERACION REALIZADA CON EXITO', 1, 4);
            }else{
                $this->m_utils->saveLogSigoplus('LIQUIDACION OBRA', null, $itemPlan, null, $indicador, $descEmpresaColab, $jefatura, 'FALLA EN LA RESPUESTA DEL HOSTING', 'OPERACION NO COMPLETADA ERROR EN EL SERVIDOR DEL CLIENTE:'. strtoupper($response->mensaje), '2', 4);
            }
        //$data['tablaAsigGrafo'] = $this->makeHTLMTablaAsignarGrafo($this->m_liquidacion->getPtrToLiquidacion($SubProy,$eecc,$zonal,$itemPlan,$mesEjec,$area,$estado,FROM_BANDEJA_APROBACION,$ano));
            
        // }catch(Exception $e){
        //     $data['msj'] = $e->getMessage();
        // }
        // echo json_encode(array_map('utf8_encode', $data));
    }

    function getDataFormularioSisego() {
        $this->m_utils->getDataFormularioSisego($itemPlan, 2);
    }

   function makeHTMLToKitMateriales(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $itemplan      =   $this->input->post('itemplan');
            $idSubPro      =   $this->input->post('idSubPro');
            $accion        =   $this->input->post('accion');
            
            $html  = '';
            if($accion  ==  CREAR_REGISTRO){
                $listaMateriales    =   $this->m_utils->getMaterialesBySubProyecto($idSubPro);
                foreach($listaMateriales as $row){
                    $html   .=  '<tr>
                                <th>'.$row->id_material.'</th>
                                <th>'.utf8_decode($row->descrip_material).'</th>
                                <th><input style="height: 1%;text-align: center;" id="cantTotal'.$row->id_material.'" value="0" name="cantTotal'.$row->id_material.'" type="text" class="form-control form-control-sm canclass"></th>
                            </tr>';
                }
            }else if($accion  ==  EDITAR_REGISTRO){
                $listaMateriales    =   $this->M_porcentaje->getMaterialesCVByItemplan($itemplan);
                foreach($listaMateriales as $row){
                    $html   .=  '<tr>
                                <th>'.$row->id_material.'</th>
                                <th>'.utf8_decode($row->descrip_material).'</th>
                                <th><input style="height: 1%;text-align: center;" id="cantTotal'.$row->id.'" value="'.$row->total.'" name="cantTotal'.$row->id.'" type="text" class="form-control form-control-sm canclass"></th>
                            </tr>';
                }                
            }          
            
            $infoDetCV          =   $this->m_utils->getPlanObraDetalleCVByItemplan($itemplan);
            $html   .=  '   <tr style="display: none;">
                                <th></th>
                                <th>MICROCANOLIZADO</th>                               
                                <th><input style="height: 1%;text-align: center;"  id="inputMicroCano" value="'.(($infoDetCV!=null) ? $infoDetCV['microcanolizado'] : '0').'" name="inputMicroCano" type="text" class="form-control form-control-sm canclass"></th>
                            </tr>';
            $data['direccion']  =   (($infoDetCV!=null) ? $infoDetCV['direccion'] : '');
            $data['numero']     =   (($infoDetCV!=null) ? $infoDetCV['numero'] : '');
            $data['pisos']      =   (($infoDetCV!=null) ? $infoDetCV['pisos'] : '');
            $data['dptos']      =   (($infoDetCV!=null) ? $infoDetCV['depa'] : '');
            $data['cto']        =   (($infoDetCV!=null) ? $infoDetCV['instalacion_cto'] : '');
            $data['tipoPartida']=   (($infoDetCV!=null) ? $infoDetCV['idTipoPartida'] : '');
            $data['camara']     =   (($infoDetCV!=null) ? $infoDetCV['camara'] : '');
            $data['accion']     =   $accion;
            $data['idsubPro']   =   $idSubPro;
            $data['itemplan']   =   $itemplan;
            $data['htmlConTabla'] =   $html;
            $data['error']      = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
   function saveKitDeMaterial(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            
            $tipoPartida =  $this->input->post('selectTipoTrabajo');
            //data de cv
            $direccion  = $this->input->post('txtDireccion');
            $numero     = $this->input->post('txtNumero');
            $pisos      = $this->input->post('txtPisos');
            $dptos      = $this->input->post('txtDepartamentos');
            
            $microCanoni = $this->input->post('inputMicroCano');
            $cantidadCTO = $this->input->post('selectInstala');
            
            $camara = $this->input->post('selectCamara');
            
            $cambio = false;            
            
            $dataDetCV  = array(
                'direccion' => $direccion,
                'numero'    => $numero,
                'pisos'     => $pisos,
                'depa'      => $dptos
            );
            
            $idSubPro      =   $this->input->post('idSubPro');
            $itemplan      =   $this->input->post('itemplan');
            $accion        =   $this->input->post('accion');
            
            $infoBasi = $this->M_porcentaje->getInfoItemPlanToCertificacion($itemplan);
            if($infoBasi != null){
                $isLima        =    (($infoBasi['isLima'] == 1 ) ?  true : false);
                $idEmpresaCola =    $infoBasi['idEmpresaColabCV'];
            }else{
                throw new Exception('error interno al obtener informacion basica del itemplan');
            }
            $cantidades  = array();
            
            $arrayPartidaItemplan = array();//nuevo            
           if($accion  ==  CREAR_REGISTRO){
                
                $listaPartidas      =   $this->M_porcentaje->getPartidasByTipoAndEmpresacolab($tipoPartida, $idEmpresaCola);
                foreach($listaPartidas as $row){//ITEM_PARTIDA_CABLEADO_INTERIOR_DE_EDIFICIO
                    $monto = (($isLima) ? $row->monto_lima : $row->monto_prov);
                    $multiplo = 1;
                    $arrayPartidaTmp = array();
                    $arrayPartidaTmp['itemPlan']        =   $itemplan;
                    $arrayPartidaTmp['idTipoPartida']   =   $tipoPartida;
                    $arrayPartidaTmp['id_item_partida'] =   $row->id_item_partida;
                    $arrayPartidaTmp['idEmpresaColab']  =   $idEmpresaCola;
                    $arrayPartidaTmp['monto']           =   $monto;
                    $arrayPartidaTmp['idEstacion']      =   ID_ESTACION_FO;
                    if($row->id_item_partida == ITEM_PARTIDA_CABLEADO_INTERIOR_DE_EDIFICIO){//validacion por departamentos
                        $multiplo = ceil($dptos/20);//revisar bien el multiplo -------------->    
                        if($multiplo    >   1){
                            $multiplo   =   1;
                        }                   
                        $arrayPartidaTmp['cantidad']        =   $multiplo;
                        $arrayPartidaTmp['total']           =   $monto*$multiplo;                        
                    }if($row->id_item_partida == ITEM_PARTIDA_CABLEADO_INTERIOR_DE_EDIFICIO_ADICIONAL){//validacion por departamentos
                        if($dptos>20){
                            $difencia = ($dptos - 20);
                            $multiplo = ceil($difencia/20);//revisar bien el multiplo -------------->                           
                            $arrayPartidaTmp['cantidad']        =   $multiplo;
                            $arrayPartidaTmp['total']           =   $monto*$multiplo;
                            
                        }else{
                            continue;
                        }                        
                    }else if($row->id_item_partida == ITEM_PARTIDA_CABLEADO_EXTERNO_TENDIDO_CABLE){//validacion por CABLE F.OPT.MONOMODO PKP 16 FIBRAS
                        $num = $this->input->post('cantTotal10402530004');//id_materia de CABLE F.OPT.MONOMODO PKP 16 FIBRAS
                        $multiplo = ceil($num/100);//revisar bien el multiplo -------------->
                        $arrayPartidaTmp['cantidad']        =   $multiplo;
                        $arrayPartidaTmp['total']           =   $monto*$multiplo;
                    }else if($row->id_item_partida == ITEM_PARTIDA_CABLEADO_EXTERNO_TENDIDO_EN_MICRO){//validacion por departamentos
                        
                      
                       $multiplo =  ceil($microCanoni/11);//revisar bien el multiplo -------------->
                        
                        
                        if($microCanoni <= 11){                            
                            $arrayPartidaTmp['cantidad']        =   $multiplo;
                            $arrayPartidaTmp['total']           =   $monto*$multiplo;
                        }else{
                            $diferencial = ($microCanoni - 11);
                            $precio_unitario = ($monto/11);
                            $cantidad_nueva = ($microCanoni/11);
                            $arrayPartidaTmp['cantidad']        =   number_format($cantidad_nueva, 3, '.', ',');
                            $arrayPartidaTmp['total']           =   ($monto+($precio_unitario*$diferencial));
                            
                        }                        
                        
                        
                    }else if($row->id_item_partida==  ITEM_PARTIDA_UNIDAD_SINGULAR_DE_OBRA_CTO){
                        if($cantidadCTO ==  'SI'){
                            $arrayPartidaTmp['cantidad']        =   $row->cantidad;
                            $arrayPartidaTmp['total']           =   $monto*$row->cantidad;
                       }else{
                            continue;
                       }
                    }else if($row->id_item_partida ==  ITEM_DISENO_ATENCION_EDIFICIOS){
                        if($cantidadCTO ==  'SI'){
                            $arrayPartidaTmp['cantidad']        =   $row->cantidad*2;
                            $arrayPartidaTmp['total']           =   $monto*$row->cantidad*2;
                        }else{
                            $arrayPartidaTmp['cantidad']        =   $row->cantidad;
                            $arrayPartidaTmp['total']           =   $monto*$row->cantidad;
                        }
                    }else if($row->id_item_partida==  ITEM_UNIDAD_SINGULAR_OBRA){
                        if($camara ==  'SI'){
                            $arrayPartidaTmp['cantidad']        =   $row->cantidad;
                            $arrayPartidaTmp['total']           =   $monto*$row->cantidad;
                       }else{
                            continue;
                       }
                    }else if($row->id_item_partida  ==  ITEM_CONSTRUIR_CAMARA_REGISTRO){                                 
                        if($camara ==  'SI'){
                            $arrayPartidaTmp['cantidad']        =   $row->cantidad;
                            $arrayPartidaTmp['total']           =   $monto*$row->cantidad;
                       }else{
                            continue;
                       }
                    }else{//SI NO SE CUENTA COMO 1
                        $arrayPartidaTmp['cantidad']        =   $row->cantidad;
                        $arrayPartidaTmp['total']           =   $monto*$row->cantidad;
                    }
                    array_push($arrayPartidaItemplan, $arrayPartidaTmp);
                }
                
                $listaMateriales    =   $this->m_utils->getMaterialesBySubProyecto($idSubPro);
                foreach($listaMateriales as $row){
                    $basic = array();
                    $basic['id_material']   = $row->id_material;
                    $basic['total']      = $this->input->post('cantTotal'.$row->id_material);
                    array_push($cantidades, $basic);
                }
                
            }else if($accion  ==  EDITAR_REGISTRO){
                $listaMateriales    =   $this->M_porcentaje->getMaterialesCVByItemplan($itemplan);
                $idMaterial = '';
                foreach($listaMateriales as $row){
                    $basic = array();
                    $basic['id']   = $row->id;
                    $basic['total']      = $this->input->post('cantTotal'.$row->id);
                    array_push($cantidades, $basic);
                    if($row->id_material    ==  '10402530004'){//id_materia de CABLE F.OPT.MONOMODO PKP 16 FIBRAS
                        $idMaterial = $row->id;                        
                    }
                }
                
                $infoDetCV      =   $this->m_utils->getPlanObraDetalleCVByItemplan($itemplan);
                
                if($infoDetCV!=null){
                    $val = $infoDetCV['idTipoPartida'];
                    if($val!=null){
                        if($tipoPartida!=$val){
                            $cambio = true;
                        }
                    }
                } 
                
                if($cambio){
                    
                    $listaPartidas      =   $this->M_porcentaje->getPartidasByTipoAndEmpresacolab($tipoPartida, $idEmpresaCola);
                    foreach($listaPartidas as $row){//ITEM_PARTIDA_CABLEADO_INTERIOR_DE_EDIFICIO
                        $monto = (($isLima) ? $row->monto_lima : $row->monto_prov);
                        $multiplo = 1;
                        $arrayPartidaTmp = array();
                        $arrayPartidaTmp['itemPlan']        =   $itemplan;
                        $arrayPartidaTmp['idTipoPartida']   =   $tipoPartida;
                        $arrayPartidaTmp['id_item_partida'] =   $row->id_item_partida;
                        $arrayPartidaTmp['idEmpresaColab']  =   $idEmpresaCola;
                        $arrayPartidaTmp['monto']           =   $monto;
                        $arrayPartidaTmp['idEstacion']      =   ID_ESTACION_FO;
                        if($row->id_item_partida == ITEM_PARTIDA_CABLEADO_INTERIOR_DE_EDIFICIO){//validacion por departamentos
                            $multiplo = ceil($dptos/20);//revisar bien el multiplo -------------->    
                            if($multiplo    >   1){
                                $multiplo   =   1;
                            }                   
                            $arrayPartidaTmp['cantidad']        =   $multiplo;
                            $arrayPartidaTmp['total']           =   $monto*$multiplo;                        
                        }if($row->id_item_partida == ITEM_PARTIDA_CABLEADO_INTERIOR_DE_EDIFICIO_ADICIONAL){//validacion por departamentos
                            if($dptos>20){
                                $difencia = ($dptos - 20);
                                $multiplo = ceil($difencia/20);//revisar bien el multiplo -------------->                           
                                $arrayPartidaTmp['cantidad']        =   $multiplo;
                                $arrayPartidaTmp['total']           =   $monto*$multiplo;
                                
                            }else{
                                continue;
                            }                        
                        }else if($row->id_item_partida == ITEM_PARTIDA_CABLEADO_EXTERNO_TENDIDO_CABLE){//validacion por CABLE F.OPT.MONOMODO PKP 16 FIBRAS
                            $num = $this->input->post('cantTotal'.$idMaterial);//id_materia de CABLE F.OPT.MONOMODO PKP 16 FIBRAS
                            $multiplo = ceil($num/100);//revisar bien el multiplo -------------->
                            $arrayPartidaTmp['cantidad']        =   $multiplo;
                            $arrayPartidaTmp['total']           =   $monto*$multiplo;
                        }else if($row->id_item_partida == ITEM_PARTIDA_CABLEADO_EXTERNO_TENDIDO_EN_MICRO){//validacion por departamentos
                            $multiplo =  ceil($microCanoni/11);//revisar bien el multiplo -------------->
                            if($microCanoni <= 11){                            
                                $arrayPartidaTmp['cantidad']        =   $multiplo;
                                $arrayPartidaTmp['total']           =   $monto*$multiplo;
                            }else{
                                $diferencial = ($microCanoni - 11);
                                $precio_unitario = ($monto/11);
                                $cantidad_nueva = ($microCanoni/11);
                                $arrayPartidaTmp['cantidad']        =   number_format($cantidad_nueva, 3, '.', ',');
                                $arrayPartidaTmp['total']           =   ($monto+($precio_unitario*$diferencial));
                                
                            }   
                        }else if($row->id_item_partida  ==  ITEM_PARTIDA_UNIDAD_SINGULAR_DE_OBRA_CTO){
                            if($cantidadCTO ==  'SI'){
                                $arrayPartidaTmp['cantidad']        =   $row->cantidad;
                                $arrayPartidaTmp['total']           =   $monto*$row->cantidad;
                            }else{
                                continue;
                            }
                            
                        }else if($row->id_item_partida ==  ITEM_DISENO_ATENCION_EDIFICIOS){
                            if($cantidadCTO ==  'SI'){
                                $arrayPartidaTmp['cantidad']        =   $row->cantidad*2;
                                $arrayPartidaTmp['total']           =   $monto*$row->cantidad*2;
                            }else{
                                $arrayPartidaTmp['cantidad']        =   $row->cantidad;
                                $arrayPartidaTmp['total']           =   $monto*$row->cantidad;
                            }
                        }else if($row->id_item_partida==  ITEM_UNIDAD_SINGULAR_OBRA){
                            if($camara ==  'SI'){
                                $arrayPartidaTmp['cantidad']        =   $row->cantidad;
                                $arrayPartidaTmp['total']           =   $monto*$row->cantidad;
                           }else{
                                continue;
                           }
                        }else if($row->id_item_partida==  ITEM_CONSTRUIR_CAMARA_REGISTRO){
                            if($camara ==  'SI'){
                                $arrayPartidaTmp['cantidad']        =   $row->cantidad;
                                $arrayPartidaTmp['total']           =   $monto*$row->cantidad;
                           }else{
                                continue;
                           }
                        }else{//SI NO SE CUENTA COMO 
                            $arrayPartidaTmp['cantidad']        =   $row->cantidad;
                            $arrayPartidaTmp['total']           =   $monto*$row->cantidad;
                        }
                        array_push($arrayPartidaItemplan, $arrayPartidaTmp);
                    }
                    
                }else{
                    $listaPartidas      =   $this->M_porcentaje->getPartidasByItemplanAndIdEstacion($itemplan, ID_ESTACION_FO);
                    $has    = $this->M_porcentaje->hasUnidadCTO($itemplan);
                    $cant   = $has['cont'];
                    $id_partida_itemplan = $has['id_partida_itemplan'];
                    $willCTo = $this->M_porcentaje->willBeHaveUnidadCTO($idEmpresaCola, $tipoPartida, ITEM_PARTIDA_UNIDAD_SINGULAR_DE_OBRA_CTO);
                    if($cantidadCTO ==  'SI'){
                        if($willCTo == 1){
                            if($cant == 0){
                                $infoCTO = $this->M_porcentaje->getInfoPartidaUnidadSingular($tipoPartida, $idEmpresaCola, ITEM_PARTIDA_UNIDAD_SINGULAR_DE_OBRA_CTO);
                                $arrayPartidaTmp = array();
                                $monto = (($isLima) ? $infoCTO['monto_lima'] : $infoCTO['monto_prov']);
                                $arrayPartidaTmp['itemPlan']        =   $itemplan;
                                $arrayPartidaTmp['idTipoPartida']   =   $tipoPartida;
                                $arrayPartidaTmp['id_item_partida'] =   ITEM_PARTIDA_UNIDAD_SINGULAR_DE_OBRA_CTO;
                                $arrayPartidaTmp['idEmpresaColab']  =   $idEmpresaCola;
                                $arrayPartidaTmp['monto']           =   $monto;
                                $arrayPartidaTmp['cantidad']        =   $infoCTO['cantidad'];
                                $arrayPartidaTmp['total']           =   $monto*$infoCTO['cantidad'];
                                $arrayPartidaTmp['idEstacion']      =   ID_ESTACION_FO;
                                $this->M_porcentaje->savePartida($arrayPartidaTmp);
                            }
                        }else if($willCTo == 0){
                            if($cant == 1){
                                $this->M_porcentaje-> deletePartida($id_partida_itemplan);
                            }
                        }                        
                    }else if($cantidadCTO ==  'NO'){
                        if($cant == 1){
                            $this->M_porcentaje-> deletePartida($id_partida_itemplan);
                        }
                    }
                    
                    
                    //validacion de camara
                    $hasCamara = $this->M_porcentaje->hasCamaraByItemplan($itemplan);
                    if($hasCamara != $camara){
                        _log('$hasCamara:'.$hasCamara.' - $camara:'.$camara);
                        if($camara   ==  'NO'){
                            _log('CAMBIO DE SI A NO...');
                           $this->M_porcentaje->deletePartidasFromCamara($itemplan);
                        }else if($camara ==  'SI'){//SI TIENE CAMARA SE LE INSERTA ESTAS 2 PARTIDAS
                            _log('CAMBIO DE NO A SI...');
                                $willUSO = $this->M_porcentaje->willBeHaveUnidadCTO($idEmpresaCola, $tipoPartida, ITEM_UNIDAD_SINGULAR_OBRA);
                                if($willUSO ==  1){                                    
                                    $infoCTO = $this->M_porcentaje->getInfoPartidaUnidadSingular($tipoPartida, $idEmpresaCola, ITEM_UNIDAD_SINGULAR_OBRA);
                                    $arrayPartidaTmp = array();
                                    $monto = (($isLima) ? $infoCTO['monto_lima'] : $infoCTO['monto_prov']);
                                    $arrayPartidaTmp['itemPlan']        =   $itemplan;
                                    $arrayPartidaTmp['idTipoPartida']   =   $tipoPartida;
                                    $arrayPartidaTmp['id_item_partida'] =   ITEM_UNIDAD_SINGULAR_OBRA;
                                    $arrayPartidaTmp['idEmpresaColab']  =   $idEmpresaCola;
                                    $arrayPartidaTmp['monto']           =   $monto;
                                    $arrayPartidaTmp['cantidad']        =   $infoCTO['cantidad'];
                                    $arrayPartidaTmp['total']           =   $monto*$infoCTO['cantidad'];
                                    $arrayPartidaTmp['idEstacion']      =   ID_ESTACION_FO;
                                    $this->M_porcentaje->savePartida($arrayPartidaTmp);
                                }
                                
                                $willICC = $this->M_porcentaje->willBeHaveUnidadCTO($idEmpresaCola, $tipoPartida, ITEM_CONSTRUIR_CAMARA_REGISTRO);
                                if($willICC ==  1){
                                    $infoCTO = $this->M_porcentaje->getInfoPartidaUnidadSingular($tipoPartida, $idEmpresaCola, ITEM_CONSTRUIR_CAMARA_REGISTRO);
                                    $arrayPartidaTmp = array();
                                    $monto = (($isLima) ? $infoCTO['monto_lima'] : $infoCTO['monto_prov']);
                                    $arrayPartidaTmp['itemPlan']        =   $itemplan;
                                    $arrayPartidaTmp['idTipoPartida']   =   $tipoPartida;
                                    $arrayPartidaTmp['id_item_partida'] =   ITEM_CONSTRUIR_CAMARA_REGISTRO;
                                    $arrayPartidaTmp['idEmpresaColab']  =   $idEmpresaCola;
                                    $arrayPartidaTmp['monto']           =   $monto;
                                    $arrayPartidaTmp['cantidad']        =   $infoCTO['cantidad'];
                                    $arrayPartidaTmp['total']           =   $monto*$infoCTO['cantidad'];
                                    $arrayPartidaTmp['idEstacion']      =   ID_ESTACION_FO;
                                    $this->M_porcentaje->savePartida($arrayPartidaTmp);
                                }
                        }
                    }
                    foreach($listaPartidas as $row){
                        $arrayPartidaTmp = array();
                        if($row->id_item_partida == ITEM_PARTIDA_CABLEADO_INTERIOR_DE_EDIFICIO){
                            $multiplo = ceil($dptos/20);//revisar bien el multiplo -------------->
                            if($multiplo    >   1){
                                $multiplo   =   1;
                            }
                            $arrayPartidaTmp['cantidad']            =   $multiplo;
                            $arrayPartidaTmp['total']               =   $row->monto*$multiplo;
                            $arrayPartidaTmp['id_partida_itemplan'] =   $row->id_partida_itemplan;
                            array_push($arrayPartidaItemplan, $arrayPartidaTmp);
                        }else if($row->id_item_partida == ITEM_PARTIDA_CABLEADO_INTERIOR_DE_EDIFICIO_ADICIONAL){
                            if($dptos>20){
                                $difencia = ($dptos - 20);
                                $multiplo = ceil($difencia/20);//revisar bien el multiplo -------------->
                                $arrayPartidaTmp['cantidad']            =   $multiplo;
                                $arrayPartidaTmp['total']               =   $row->monto*$multiplo;
                                $arrayPartidaTmp['id_partida_itemplan'] =   $row->id_partida_itemplan;
                                array_push($arrayPartidaItemplan, $arrayPartidaTmp);                            
                            }else{
                                $multiplo = 0;
                                $arrayPartidaTmp['cantidad']            =   $multiplo;
                                $arrayPartidaTmp['total']               =   $row->monto*$multiplo;
                                $arrayPartidaTmp['id_partida_itemplan'] =   $row->id_partida_itemplan;
                                array_push($arrayPartidaItemplan, $arrayPartidaTmp);
                            }                            
                        }else if($row->id_item_partida == ITEM_PARTIDA_CABLEADO_EXTERNO_TENDIDO_CABLE){
                            $num = $this->input->post('cantTotal'.$idMaterial);//id_materia de CABLE F.OPT.MONOMODO PKP 16 FIBRAS
                            $multiplo = ceil($num/100);//revisar bien el multiplo -------------->
                            $arrayPartidaTmp['cantidad']            =   $multiplo;
                            $arrayPartidaTmp['total']               =   $row->monto*$multiplo;
                            $arrayPartidaTmp['id_partida_itemplan'] =   $row->id_partida_itemplan;
                            array_push($arrayPartidaItemplan, $arrayPartidaTmp);
                        }else if($row->id_item_partida == ITEM_PARTIDA_CABLEADO_EXTERNO_TENDIDO_EN_MICRO){                            
                            $multiplo =  ceil($microCanoni/11);//revisar bien el multiplo -------------->
                            if($microCanoni <= 11){
                                $arrayPartidaTmp['cantidad']        =   $multiplo;
                                $arrayPartidaTmp['total']           =   $row->monto*$multiplo;
                            }else{
                                $diferencial = ($microCanoni - 11);
                                $precio_unitario = ($row->monto/11);
                                $cantidad_nueva = ($microCanoni/11);
                                $arrayPartidaTmp['cantidad']        =   number_format($cantidad_nueva, 3, '.', ',');
                                $arrayPartidaTmp['total']           =   ($row->monto+($precio_unitario*$diferencial));                            
                            }
                            $arrayPartidaTmp['id_partida_itemplan'] =   $row->id_partida_itemplan;
                            array_push($arrayPartidaItemplan, $arrayPartidaTmp);
                        }else if($row->id_item_partida ==  ITEM_DISENO_ATENCION_EDIFICIOS){
                            if($cantidadCTO ==  'SI'){
                                $arrayPartidaTmp['cantidad']        =   2;
                                $arrayPartidaTmp['total']           =   ($row->monto*2);
                            }else{
                                $arrayPartidaTmp['cantidad']        =   1;//POR MIENTRAS DURO
                                $arrayPartidaTmp['total']           =   ($row->monto*1);
                            }
                            $arrayPartidaTmp['id_partida_itemplan'] =   $row->id_partida_itemplan;
                            array_push($arrayPartidaItemplan, $arrayPartidaTmp);
                        }
                    }
                }
            }
            
            $idCuadrilla = $this->M_porcentaje->getCuadrillaOne($itemplan, ID_ESTACION_FO);
            $coordenada  = $this->M_porcentaje->getCoordenadas($itemplan);
            $dataFicha  = array(
                'jefe_c_nombre'         => $idCuadrilla,
                'itemplan'              => $itemplan,
                'fecha_registro'        => $this->fechaActual(),
                'usuario_registro'      =>  $this->session->userdata('idPersonaSession'),
                'coordenada_x'          => $coordenada['coordX'],
                'coordenada_y'          => $coordenada['coordY'],
                'flg_activo'            => '1',
                'id_ficha_tecnica_base' => TIPO_FICHA_CV_FTTH,
                'id_estacion'           => ID_ESTACION_FO
            );           
            
            if($accion  ==  CREAR_REGISTRO){
                $data   =   $this->M_porcentaje->saveKitMateCantidad($itemplan, $cantidadCTO, $cantidades, $dataFicha, $dataDetCV, $microCanoni, $arrayPartidaItemplan, $tipoPartida, $camara);
            }else if($accion  ==  EDITAR_REGISTRO){
                $data   =   $this->M_porcentaje->updateKitMaterialCV($itemplan, $cantidadCTO, $cantidades, $dataDetCV, $microCanoni, $arrayPartidaItemplan, $cambio, $tipoPartida, $camara);
            }
            
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
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
    
    function registrarFormObraPub() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $arrayJson = $this->input->post('jsonFormObrasP');
            $arrayJson['usuario_registro'] = $this->session->userdata('idPersonaSession');
            $arrayJson['fecha_registro']   = $this->fechaActual(); 

            $flg = $this->M_porcentaje->insertFormObraP($arrayJson);

            if($flg == 1) {
                $val = $this->registrarFicha($arrayJson['itemplan'], null, $arrayJson['idEstacion'], 5, null);
                
                if($val == 1) {
                    list($html, $cant, $idProyecto, $idEstadoPlan, $indicador, $flgZonal) = $this->Estaciones($arrayJson['itemplan'], $arrayJson['idEstacion'], 1);
                    $this->ingresaItemPlanEstacionAvance($arrayJson['itemplan'], $arrayJson['idEstacion'], $cant, null, null);
                    $data['error'] = EXIT_SUCCESS;
                    $data['msj']   = 'Formulario a asd registrado correctamente';
                } else {
                    throw new Exception('NDP');
                }
            } else {
                throw new Exception('NDP');
            }
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }  
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getPtrByItemplan() {
        $itemplan      = $this->input->post('itemplan');
        $idEstadoPlan  = $this->input->post('idEstadoPlan');
        $tb          = $this->getTablaConsultaPTR($itemplan, $idEstadoPlan); 

        $data['tablaConsultaPtr'] = $tb;  

        echo json_encode(array_map('utf8_encode', $data));
    }

    function getTablaConsultaPTR($itemplan, $idEstadoPlan) {
        $tb = null;
        $arrayPtr = $this->M_porcentaje->getPtrByItemplan($itemplan);
        $tb .= '<table class="table">
                    <thead>
                        <th>PTR</th>
						<th>Total Inicial</th>
                        <th>Total Final</th>
                        <th>Acci&oacute;n</th>
                    <thead>
                    <tbody>';
        foreach($arrayPtr as $row) {
            //if($idEstadoPlan == ID_ESTADO_TERMINADO) {
              //  $btnEditar = null;
            //} else {
                $btnEditar = '<input type="button" class="btn-danger" data-costo_mo="'.$row->total.'" 
								data-ptr="'.$row->ptr.'" data-itemplan="'.$itemplan.'" value="editar" onclick="openModalEditarPTR($(this));"/>';                
            //}
            $tb .= '<tr>
                        <td>'.$row->ptr.'</td>
						<td>'.$row->total_anterior.'</td>
                        <td>'.$row->total.'</td>
                        <td>'.$btnEditar.'</td>
                    </tr>';
        }
            $tb .= '    </tbody>
                    </table>';
        return $tb;         
    }

    function getPtrEditar() {
        $itemplan      = $this->input->post('itemplan');
        $ptr           = $this->input->post('ptr');
        $idSubProyecto = $this->input->post('idSubProyecto');
        $cont = 0;
        $arrayData = $this->m_utils->consultaUpdatePOLiquidacion($itemplan, $ptr);
        
        $html = '<table id="tablaPtr" class="table">
                    <thead class="thead-default">
                        <tr>
                            <th>Actividad</th>
                            <th>Precio</th>
                            <th>Baremo</th>                            
                            <th style="background:#E9C603;color:white">Cantidad Inicial</th>
                            <th style="background:#E9C603;color:white">Cantidad Final</th>
                            <th>Costo MO</th>
                            <th>Total</th>                       
                        </tr>
                    </thead>                    
                    <tbody>';
        foreach($arrayData as $row) {
            $cont++;
            $html.= '<tr id="'.$cont.'">
                        <td>'.utf8_decode($row->descripcion).'</td>
                        <td id="precio_'.$cont.'">'.$row->precio.'</td>
                        <td id="baremo_'.$cont.'">'.$row->baremo.'</td>
                        <td style="background:#E9C603;color:white"><input id="cantidad_in_'.$cont.'" class="form-control" value="'.$row->cantidad_inicial.'" disabled></td>
                        <td style="background:#E9C603;color:white"><input id="cantidad_'.$cont.'" type="number" data-descripcion="'.utf8_decode($row->descripcion).'" data-cont="'.$cont.'" data-id_actividad="'.$row->idActividad.'" data-id_ptrxactividad_zonal="'.$row->id_ptr_x_actividades_x_zonal.'" class="form-control" value="'.$row->cantidad_final.'" onchange="calculoCantidad($(this));"></td>
                        <td id="costoMO_'.$cont.'">'.$row->costo_mo.'</td>
                        <td id="costoTotal_'.$cont.'">'.$row->total.'</td>
                    </tr>';
        }
        $html .='   </tbody>
            </table>';
        $tbActividad = $this->getTablaActividadesxSubProyecto($idSubProyecto, $ptr, $itemplan);

        $data['tablaActividad'] = $tbActividad;    
        $data['tablaEditarPtr'] = $html;      
        echo json_encode(array_map('utf8_encode', $data));    
    }

    function actualizarPTR() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $costoMA         = $this->input->post('costoMA');
            $costoMO         = $this->input->post('costoMO');
            $total           = $this->input->post('total');
            $cantidadFinal   = $this->input->post('cantidadFinal');
            $ptr             = $this->input->post('ptr');
            $itemplan        = $this->input->post('itemplan');
            $arrayData       = json_decode($this->input->post('arrayData'));
            $arrayDataInsert = $this->input->post('arrayDataInsert');
            $idEstadoPlan    = $this->input->post('idEstadoPlan');

            if($cantidadFinal == null || $cantidadFinal == '') {
                throw new Exception('debe Ingresar la cantidad');
            }
        
            // $arrayData = array(
            //     'costo_mat'      => $costoMA,
            //     'costo_mo'       => $costoMO,
            //     'total'          => $total,
            //     'cantidad_final' => $cantidadFinal
            // );
            _log(print_r($arrayData, true));
            $val = $this->M_porcentaje->actualizarPTR($arrayData, $ptr, $itemplan);

            if($val == 1) {
                $data['error'] = EXIT_SUCCESS;
                $arrayDataLog = array(
                    'tabla'            => 'sinfix',
                    'actividad'        => 'cantidad ptr actualizada',
                    'itemplan'         => $itemplan,
                    'fecha_registro'   => $this->fechaActual(),
                    'id_usuario'       => $this->session->userdata('idPersonaSession')
                 );

                $this->m_utils->registrarLogPlanObra($arrayDataLog);
            } else {
                $data['error'] = EXIT_ERROR; 
                throw new Exception('No ingreso el costo correctamente');
            }
            
            $val1 = $this->M_porcentaje->insertarPTR($arrayDataInsert);

            if($val1 == 1) {
                $data['error'] = EXIT_SUCCESS;
                $arrayDataLog = array(
                    'tabla'            => 'sinfix',
                    'actividad'        => 'insert ptr',
                    'itemplan'         => $itemplan,
                    'fecha_registro'   => $this->fechaActual(),
                    'id_usuario'       => $this->session->userdata('idPersonaSession')
                 );

                $this->m_utils->registrarLogPlanObra($arrayDataLog);
            }
            $tablaConsultaPtr = $this->getTablaConsultaPTR($itemplan, $idEstadoPlan);
            $data['tablaConsultaPtr'] = $tablaConsultaPtr;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }  
        echo json_encode(array_map('utf8_encode', $data));
    }

    function getTablaActividadesxSubProyecto($idSubProyecto, $ptr, $itemplan) {
        $tb = null;
        $array = $this->M_aprobacion_interna->getActividadesxSubproyecto($idSubProyecto, $ptr, $itemplan);
        $tb .= '<table id="tablaActividad" class="table">
                    <thead>
                        <th>Actividad</th>
                        <th>Baremo</th>
                        <th>costo kit</th>
                        <th>Acci&oacute;n</th>
                    <thead>
                    <tbody>';
        foreach($array as $row) {
            $tb .= '<tr>
                        <td>'.$row->descripcion.'</td>
                        <td>'.$row->baremo.'</td>
                        <td>'.$row->costo_material.'</td>
                        <td><input type="button" class="btn-info" data-descripcion="'.$row->descripcion.'" data-id_actividad="'.$row->idActividad.'" data-baremo="'.$row->baremo.'"
                         data-costo_kit="'.$row->costo_material.'" 
                         value="Agregar" onclick="addActividad($(this));"/>
                    </tr>';
        }
            $tb .= '    </tbody>
                    </table>';
        return $tb;  
    }
    
    
    function registrarFicha($itemPlan, $observacion, $idEstacion, $idFichaTecnicaBase, $arrayJson) {
        $idCuadrilla = $this->M_porcentaje->getCuadrillaOne($itemPlan, $idEstacion);
        $coordenada  = $this->M_porcentaje->getCoordenadas($itemPlan);
        $dataInsert = array(
                                'jefe_c_nombre'         => $idCuadrilla,
                                'observacion'           => $observacion,
                                'itemplan'              => $itemPlan,
                                'fecha_registro'        => date("Y-m-d h:m:s"),
                                'usuario_registro'      =>  $this->session->userdata('idPersonaSession'),
                                'coordenada_x'          => $coordenada['coordX'],
                                'coordenada_y'          => $coordenada['coordY'],
                                'flg_activo'            => '1',
                                'id_ficha_tecnica_base' => $idFichaTecnicaBase,
                                'id_estacion'           => $idEstacion
                            );

        $val = $this->M_porcentaje->isertFichaTecnicaPub($dataInsert, $arrayJson);
                            
        return $val;	
    }
    
    function deleteFilexTipo($ubicacion, $typeFile) {
        $arrayName = array_diff(scandir($ubicacion), array('.', '..'));

        $contFotos   = 0;
        $contArchivo = 0;
        $arrayTypeFotos = array('jpg', 'png', 'jpeg');
        foreach($arrayName as $fileFoto) {
            $type = explode('.', $fileFoto);

            if(in_array($type[1], $arrayTypeFotos) && in_array($typeFile, $arrayTypeFotos)) {
                $contFotos++;
                if($contFotos >= 2){
                    array_map('unlink', glob($ubicacion."/*.".$type[1]));
                }
            } else if(in_array($type[1], array('pdf')) ) { 
                $contArchivo++;
                if($contArchivo >= 3){
                    array_map('unlink', glob($ubicacion."/*.".$type[1]));
                }
            }
        }
    }
    
    function deleteArchivoFoto() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $ubicacion         = $this->input->post('ubicacion');
            $nombreArchivoFoto = $this->input->post('nombreArchivoFoto');  
            
            if($ubicacion == null || $ubicacion == '') {
                throw new Exception('no se encuentra la ubicaci????n');
            }

            if($nombreArchivoFoto == null || $nombreArchivoFoto == '') {
                throw new Exception('no hay nombre de archivo');
            }
            
            $file = $ubicacion.'/'.$nombreArchivoFoto;
            unlink($file);
            
            if(file_exists($file)) {
                throw new Exception('no se pudo eliminar el archivo');
            }

            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function insertFotoFuera() {
        $itemPlan = $this->input->post('itemplan');
    
        $file     = $_FILES ["file"] ["name"];
        $filetype = $_FILES ["file"] ["type"];
        $filesize = $_FILES ["file"] ["size"];
    
        $ubicacion = 'uploads/evidencia_fotos/'.$itemPlan;
        if (!is_dir($ubicacion)) {
            mkdir ('uploads/evidencia_fotos/'.$itemPlan, 0777);
        }
        $descEstacion = $this->input->post('descEstacion');
        $flgArchivo   = '';
        $idEstacion   = $this->input->post('idEstacion');
        $subCarpeta   = 'uploads/evidencia_fotos/'.$itemPlan.'/'.$descEstacion;
    
        $typeFile = explode('.', $file);
		
		/* 12.09.2019 czavalacas evitar que borre multiples archivos
        if(is_dir($subCarpeta)) {
            $this->deleteFilexTipo($subCarpeta, $typeFile[1]);
        }
		*/
    
        if($flgArchivo == 1) {
            //ARCHIVOS_X_ESTACION
            $ubicArchivo = 'uploads/evidencia_fotos/'.$itemPlan.'/archivos_estacion';
            if (!is_dir($ubicArchivo)) {
                mkdir('uploads/evidencia_fotos/'.$itemPlan.'/archivos_estacion', 0777);
            }
            $subCarpetaArch = 'uploads/evidencia_fotos/'.$itemPlan.'/archivos_estacion/'.$descEstacion;
            if (!is_dir($subCarpetaArch)) {
                mkdir ('uploads/evidencia_fotos/'.$itemPlan.'/archivos_estacion/'.$descEstacion, 0777);
            }
            $file2 = $file;
            if (!is_dir($subCarpetaArch))
                mkdir ( $subCarpetaArch, 0777 );
            if ($file && move_uploaded_file($_FILES["file"]["tmp_name"], $subCarpetaArch."/".$file2 )) {
            }
        } else if($flgArchivo == 2) {
            //ARCHIVOS_X_ACTIVIDAD
            $descActividad = $this->session->userdata('descActividad');
    
            $ubicArchivo = 'uploads/evidencia_fotos/'.$itemPlan.'/archivos_actividad';
            if (!is_dir($ubicArchivo)) {
                mkdir('uploads/evidencia_fotos/'.$itemPlan.'/archivos_actividad', 0777);
            }
            $subCarpetaArch = 'uploads/evidencia_fotos/'.$itemPlan.'/archivos_actividad/'.$descEstacion;
            if (!is_dir($subCarpetaArch)) {
                mkdir ('uploads/evidencia_fotos/'.$itemPlan.'/archivos_actividad/'.$descEstacion, 0777);
            }
    
            $subCarpetaAct = 'uploads/evidencia_fotos/'.$itemPlan.'/archivos_actividad/'.$descEstacion.'/'.$descActividad;
            if (!is_dir($subCarpetaAct)) {
                mkdir ('uploads/evidencia_fotos/'.$itemPlan.'/archivos_actividad/'.$descEstacion.'/'.$descActividad, 0777);
            }
    
            $file2 = $file;
            if (!is_dir($subCarpetaAct))
                mkdir ( $subCarpetaAct, 0777 );
            if ($file && move_uploaded_file($_FILES["file"]["tmp_name"], $subCarpetaAct."/".$file2 )) {
            }
        } else {
			if (!is_dir($subCarpeta))
                mkdir ( $subCarpeta, 0777 );
            //SUBIR FOTO Y ARCHIVO
            $prefijo = $this->input->post('tipoPrueba');
            if($prefijo == null){
                $prefijo   = '';
            }
            //SUBIR FOTO Y ARCHIVO
            $this->session->set_userdata('subCarpetaPreDi',$subCarpeta);
            $this->session->set_userdata('itemPlan2',$itemPlan);
            $file2 = $file;
            if (!is_dir($subCarpeta))
                mkdir ( $subCarpeta, 0777 );
            if ($file && move_uploaded_file($_FILES["file"]["tmp_name"], $subCarpeta."/".$file2 )) {
            }
        }
        // $this->ingresarItemplanEstacionAvance_todo($itemPlan, $idEstacion,  null, $descEstacion, 0);
    
        list($html, $cant, $idProyecto, $idEstadoPlan, $indicador, $flgZonal) = $this->Estaciones($itemPlan, $idEstacion, 0);
        if($flgZonal == 1) {
            $flgSubio = 1;
            $cant = $this->getPorcentajeRestriccion($itemPlan, $idEstacion, $cant, $descEstacion, $flgSubio);
        }
    
        $conversacion = (isset($conversacion)) ? $conversacion : NULL;
        $idCuadrilla  = (isset($idCuadrilla))  ? $idCuadrilla  : NULL;
        //$this->ingresaItemPlanEstacionAvance($itemPlan, $idEstacion, $cant, $conversacion, $idCuadrilla); me bajaba a 90%
    
        $data['error'] = EXIT_SUCCESS;
        echo json_encode(array_map('utf8_encode', $data));
    }
    
     /*********21.10.2019 czavalacas edit **********/
    function makeHTMLToLiqOC(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $itemplan      =   $this->input->post('itemplan');
            $idSubPro      =   $this->input->post('idSubPro');
            $accion        =   $this->input->post('accion');
            
            $infoBasi = $this->M_porcentaje->getInfoItemPlanToCertificacion($itemplan);
            if($infoBasi != null){
                $isLima        =    (($infoBasi['isLima'] == 1 ) ?  true : false);
                $idEmpresaCola =    $infoBasi['idEmpresaColabCV'];
            }else{
                throw new Exception('error interno al obtener informacion basica del itemplan');
            }
            
            $html  = '';
            $arrayIdPartidas = array();
            $costo_total = 0;
            if($accion  ==  CREAR_REGISTRO){
                $listaPartidas      =   $this->M_porcentaje->getPartidasByTipoAndEmpresacolab(3, $idEmpresaCola);
                foreach($listaPartidas as $row){//ITEM_PARTIDA_CABLEADO_INTERIOR_DE_EDIFICIO
                    if($row->id_item_partida != ITEM_PARTIDA_CABLEADO_EXTERNO_TENDIDO_EN_MICRO){
                        $monto = (($isLima) ? $row->monto_lima : $row->monto_prov);
                        $html   .=  '<tr>
                                        <th>'.utf8_decode($row->descripcion).'</th>
                                        <th style="text-align: center;"><label id="montTh'.$row->id_item_partida.'">'.$monto.'</label></th>
                                        <th><input onkeyup="calcularTotalPartida('.$row->id_item_partida.');" style="height: 1%;text-align: center;" id="cantTotal'.$row->id_item_partida.'" value="0" name="cantTotal'.$row->id_item_partida.'" type="text" class="form-control form-control-sm canclass"></th>
                                        <th style="text-align: center;"><label id="contTh'.$row->id_item_partida.'">0</label></th>
                                    </tr>';
                        array_push($arrayIdPartidas, $row->id_item_partida);      
                    }                                  
                }
                
            }else if($accion  ==  EDITAR_REGISTRO){
                $listaPartidas      =   $this->M_porcentaje->getPartidasOCToEdit($itemplan, $idEmpresaCola);
                $total_row = 0;
                foreach($listaPartidas as $row){//ITEM_PARTIDA_CABLEADO_INTERIOR_DE_EDIFICIO
                    $monto = (($isLima) ? $row->monto_lima : $row->monto_prov);
                    $total_row = $row->cant_edit*$monto;
                    if($row->id_item_partida == ITEM_PARTIDA_CABLEADO_EXTERNO_TENDIDO_EN_MICRO){//SOLO VISUAL
                        $html   .=  '<tr>
                                        <th>'.utf8_decode($row->descripcion).'</th>
                                        <th style="text-align: center;"><label id="montTh'.$row->id_item_partida.'">'.$monto.'</label></th>
                                        <th style="text-align: center;"><label>'. number_format($row->cant_edit, 3, '.', ',').'</label></th>
                                        <th style="text-align: center;"><label id="contTh'.$row->id_item_partida.'">'.number_format($total_row, 3, '.', ',').'</label></th>
                                    </tr>';
                    }else{
                        $html   .=  '<tr>
                                        <th>'.utf8_decode($row->descripcion).'</th>
                                        <th style="text-align: center;"><label id="montTh'.$row->id_item_partida.'">'.$monto.'</label></th>
                                        <th><input onkeyup="calcularTotalPartida('.$row->id_item_partida.');" style="height: 1%;text-align: center;" id="cantTotal'.$row->id_item_partida.'" value="'.$row->cant_edit.'" name="cantTotal'.$row->id_item_partida.'" type="text" class="form-control form-control-sm canclass"></th>
                                        <th style="text-align: center;"><label id="contTh'.$row->id_item_partida.'">'.number_format($total_row, 3, '.', ',').'</label></th>
                                    </tr>';
                        array_push($arrayIdPartidas, $row->id_item_partida);
                    }
                    
                    $costo_total = $costo_total + $total_row;
                }
            }
            
            $microCanalizado = $this->m_utils->getMicroCanalizadoOCPlanobraDetalleCV($itemplan);
            $html   .=  '   <tr>
                                <th>MICROCANOLIZADO</th>
                                <th></th>
                                <th><input style="height: 1%;text-align: center;"  id="inputMicroCano" name="inputMicroCano" value="'.$microCanalizado.'" type="text" class="form-control form-control-sm canclass"></th>
                                <th></th>
                            </tr>';
            $html   .=  '<tr>
                                <th></th>
                                <th></th>
                                <th style="text-align: center">COSTO TOTAL S/.</th>
                                <th style="text-align: center"><label id="costoTotalPar">'.number_format($costo_total, 3, '.', ',').'</label></th>
                            </tr>';
            $data['accion']     =   $accion;
            $data['idsubPro']   =   $idSubPro;
            $data['itemplan']   =   $itemplan;
            $data['htmlConTabla'] =   $html;
            $data['listItemPartidas']   =   json_encode($arrayIdPartidas);
            $data['error']      = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function saveLiquidacionOC(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $itemplan      =   $this->input->post('itemplan');
            $idSubPro      =   $this->input->post('idSubPro');
            $accion        =   $this->input->post('accion');
            $microCanoni   = $this->input->post('inputMicroCano');
            
            $infoBasi = $this->M_porcentaje->getInfoItemPlanToCertificacion($itemplan);
            if($infoBasi != null){
                $isLima        =    (($infoBasi['isLima'] == 1 ) ?  true : false);
                $idEmpresaCola =    $infoBasi['idEmpresaColabCV'];
            }else{
                throw new Exception('error interno al obtener informacion basica del itemplan');
            }
            
            if($accion  ==  CREAR_REGISTRO){
                $arrayInsert = array();
                $listaPartidas      =   $this->M_porcentaje->getPartidasByTipoAndEmpresacolab(3, $idEmpresaCola);
                foreach($listaPartidas as $row){                    
                    if($row->id_item_partida == ITEM_PARTIDA_CABLEADO_EXTERNO_TENDIDO_EN_MICRO){//validacion por departamentos
                        $monto = (($isLima) ? $row->monto_lima : $row->monto_prov);
                        $oneArray = array(  'itemPlan' => $itemplan,
                            'idTipoPartida' => 3,
                            'id_item_partida'   => $row->id_item_partida,
                            'idEmpresaColab'    => $row->idEmpresaColab,
                            'monto'     =>  $monto,
                            'idEstacion'    =>  ID_ESTACION_OC_FO
                        );
                        
                        if($microCanoni > 0){//nuevo para micro canaliziado de oc.                           
                            $multiplo =  ceil($microCanoni/11);
                            if($microCanoni <= 11){
                                $oneArray['cantidad']        =   $multiplo;
                                $oneArray['total']           =   $monto*$multiplo;
                            }else{
                                $diferencial = ($microCanoni - 11);
                                $precio_unitario = ($monto/11);
                                $cantidad_nueva = ($microCanoni/11);
                                $oneArray['cantidad']        =   number_format($cantidad_nueva, 3, '.', ',');
                                $oneArray['total']           =   ($monto+($precio_unitario*$diferencial));
                            
                            }
                        }else{
                            $oneArray['cantidad']        =   0;
                            $oneArray['total']           =   0;
                        }
                        array_push($arrayInsert, $oneArray);
                    }else{
                        $cantidad = $this->input->post('cantTotal'.$row->id_item_partida);
                        $monto = (($isLima) ? $row->monto_lima : $row->monto_prov);
                        $oneArray = array(  'itemPlan' => $itemplan,
                                            'idTipoPartida' => 3,
                                            'id_item_partida'   => $row->id_item_partida,
                                            'idEmpresaColab'    => $row->idEmpresaColab,
                                            'monto'     =>  $monto,
                                            'cantidad'  => $cantidad,
                                            'total' => ($monto*$cantidad),
                                            'idEstacion'    =>  ID_ESTACION_OC_FO
                        );                
                        array_push($arrayInsert, $oneArray);      
                    }
                }
                
                $dataFicha  = array(
                    'jefe_c_nombre'         => null,
                    'itemplan'              => $itemplan,
                    'fecha_registro'        => $this->fechaActual(),
                    'usuario_registro'      =>  $this->session->userdata('idPersonaSession'),
                    'coordenada_x'          => null,
                    'coordenada_y'          => null,
                    'flg_activo'            => '1',
                    'id_ficha_tecnica_base' => TIPO_FICHA_CV_FTTH,
                    'id_estacion'           => ID_ESTACION_OC_FO
                );
                $planobraDetCv = array('microcanalizado_oc' => $microCanoni);//guardar el microcanaliazdo
                $data = $this->M_porcentaje->saveLiquiOC($arrayInsert, $dataFicha, $planobraDetCv, $itemplan);
            }else if($accion  ==  EDITAR_REGISTRO){
                $listaPartidas      =   $this->M_porcentaje->getPartidasOCToEdit($itemplan, $idEmpresaCola);
                $arrayInsert = array();
                $arrayUpdate = array();
                
                foreach($listaPartidas as $row){//ITEM_PARTIDA_CABLEADO_INTERIOR_DE_EDIFICIO
                if($row->id_item_partida == ITEM_PARTIDA_CABLEADO_EXTERNO_TENDIDO_EN_MICRO){//validacion por departamentos
                        $monto = (($isLima) ? $row->monto_lima : $row->monto_prov);
                        $oneArray = array(  
                            'id_partida_itemplan' => $row->id_partida_itemplan,
                            'itemPlan' => $itemplan,
                            'idTipoPartida' => 3,
                            'id_item_partida'   => $row->id_item_partida,
                            'idEmpresaColab'    => $row->idEmpresaColab,
                            'monto'     =>  $monto,
                            'idEstacion'    =>  ID_ESTACION_OC_FO
                        );
                        
                       if($microCanoni > 0){//nuevo para micro canaliziado de oc.                           
                            $multiplo =  ceil($microCanoni/11);
                            if($microCanoni <= 11){
                                $oneArray['cantidad']        =   $multiplo;
                                $oneArray['total']           =   $monto*$multiplo;
                            }else{
                                $diferencial = ($microCanoni - 11);
                                $precio_unitario = ($monto/11);
                                $cantidad_nueva = ($microCanoni/11);
                                $oneArray['cantidad']        =   number_format($cantidad_nueva, 3, '.', ',');
                                $oneArray['total']           =   ($monto+($precio_unitario*$diferencial));
                            
                            }
                        }else{
                            $oneArray['cantidad']        =   0;
                            $oneArray['total']           =   0;
                        }
                        array_push($arrayUpdate, $oneArray);
                    }else{
                    
                        $cantidad = $this->input->post('cantTotal'.$row->id_item_partida);
                        $monto = (($isLima) ? $row->monto_lima : $row->monto_prov);                    
                        if($row->id_partida_itemplan != null){//update                        
                            $oneArray = array(  'id_partida_itemplan' => $row->id_partida_itemplan,
                                                'itemPlan' => $itemplan,
                                                'idTipoPartida' => 3,
                                                'id_item_partida'   => $row->id_item_partida,
                                                'idEmpresaColab'    => $row->idEmpresaColab,
                                                'monto'     =>  $monto,
                                                'cantidad'  => $cantidad,
                                                'total' => ($monto*$cantidad),
                                                'idEstacion'    =>  ID_ESTACION_OC_FO
                            );
                            array_push($arrayUpdate, $oneArray);
                        }else{//insert
                            $oneArray = array(  'itemPlan' => $itemplan,
                                                'idTipoPartida' => 3,
                                                'id_item_partida'   => $row->id_item_partida,
                                                'idEmpresaColab'    => $row->idEmpresaColab,
                                                'monto'     =>  $monto,
                                                'cantidad'  => $cantidad,
                                                'total' => ($monto*$cantidad),
                                                'idEstacion'    =>  ID_ESTACION_OC_FO
                            );
                            array_push($arrayInsert, $oneArray);
                        }
                    
                    }
                }
                $planobraDetCv = array('microcanalizado_oc' => $microCanoni);//guardar el microcanaliazdo
                $data = $this->M_porcentaje->saveLiquiOCOrUpdate($arrayUpdate, $arrayInsert, $planobraDetCv, $itemplan);
            }
            //id_partida_itemplan
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
}
