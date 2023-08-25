<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_pendientes extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('mf_ejecucion/M_pendientes');
        $this->load->model('mf_ejecucion/M_generales');
        $this->load->model('mf_ejecucion/M_porcentaje');
        $this->load->model('mf_utils/m_utils');
       
        $this->load->model('mf_licencias/M_bandeja_itemplan_estacion');
       
        $this->load->helper('url');
        $this->load->library('zip');
    }
    
    public function index(){
        $logedUser = $this->session->userdata('usernameSession');
        $idUsuario = $this->session->userdata('idPersonaSession');
        $arrayDataLog = array(
                                'tabla'          => 'sinfix',
                                'actividad'      => null,
                                'itemplan'       => null,
                                'fecha_registro' => $this->fechaActual(),
                                'id_usuario'     => $idUsuario
                             );
                             
        if($logedUser != null){
            if(@$_GET["pagina"]=="terminar"){
                $arrayDataLog['actividad']  = 'Terminar obra-Validado';
                $arrayDataLog['itemplan']   = $_GET["id"];
                $arrayDataLog['tipoPlanta'] = $_GET['tipoPlanta'];
                $arrayDataLog['tabla']      = 'Planta Interna-sinfix';
                $arrayData = array('fechaEjecucion' => $this->fechaActual(),
                                   'idEstadoPlan'   => ID_ESTADO_TERMINADO);
                // $this->M_porcentaje->TerminarObra($_GET["id"], $this->fechaActual());
                $this->m_utils->registrarLogPlanObra($arrayDataLog);  
                $this->M_porcentaje->updateEstadoPlanObra($_GET["id"], $arrayData); 
                exit;   
            }
            if(@$_GET["pagina"]=="truncar"){
                $arrayDataLog['actividad'] = 'Truncar obra';
                $arrayDataLog['idEstadoPlan'] = 10;
                $arrayDataLog['itemplan']  = $_GET["id"];
                $arrayData = array('fechaTrunca'  => $this->fechaActual(),
                                   'idEstadoPlan' => 10);
                $this->M_porcentaje->updateEstadoPlanObra($_GET["id"], $arrayData);  
                $this->m_utils->registrarLogPlanObra($arrayDataLog);     
                exit;   
            }
            if(@$_GET["pagina"]=="regresar_truncar"){
                $arrayDataLog['actividad'] = 'Regresar Trunco';
                $arrayDataLog['itemplan']  = $_GET["id"];
                $arrayDataLog['idEstadoPlan'] = 3;
                $arrayData = array('idEstadoPlan' => 3);
                $this->M_porcentaje->updateEstadoPlanObra($_GET["id"], $arrayData); 
                $this->m_utils->registrarLogPlanObra($arrayDataLog);     
                exit;   
            }
            $data["extra"]='<link href="'.base_url().'public/vendors/bower_components/datatables/media/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/><link rel="stylesheet" href="'.base_url().'public/fancy/source/jquery.fancybox.css" type="text/css" media="screen">';
            
            
            $data["tabla"]="";
            if(@$_POST["pagina"]=="pendienteFiltro"){
                if(@!$_POST["proyecto"]) {
                    $_POST["proyecto"]="";
                }
                if(@!$_POST["subproyecto"]) {
                    $_POST["subproyecto"]="";
                }
                if(@!$_POST["selectFase"]) {
                    $_POST["selectFase"]="";
                }
                if($_POST["itemplan"]!="" || ($_POST["proyecto"] != "" && $_POST["subproyecto"]!="" && $_POST["selectFase"]!="") ){
                    $data["tabla"]=$this->TablaPendientes($_POST["itemplan"],$_POST["proyecto"],$_POST["subproyecto"],$_POST["indicador"], ID_ESTADO_PLAN_EN_OBRA, NULL,$_POST["selectFase"]);
                }else{
                    $data["tabla"] = null;
                }
            }
            if($_GET["pagina"]=="preliquidada"){
                $data["pagina"]="preliquidada";
                $data["tabla"]=$this->TablaPreLiquidadas();
            }
            else if($_GET["pagina"]=="liquidada"){
                $data["pagina"]="liquidada";
                $data["tabla"]=$this->TablaLiquidadas();
            }
            else if($_GET["pagina"]=="listar_trunca"){
                $data["pagina"]="Truncas";
                $data["tabla"]=$this->TablaTrunca();
            } 
            else if($_GET["pagina"]=="pendiente") {
                $data["pagina"]="pendientes";
            } 
            else if($_GET["pagina"]=="disenio_parcial") {
                $data["pagina"]= "disenio_parcial";
                $data["tabla"] = $this->TablaPendientes(NULL, NULL, NULL , NULL, ID_ESTADO_DISENIO_PARCIAL, NULL);
            }

            if($_GET["pagina"]=="terminado_preliquidado"){
                $data["pagina"] = "pre-liquidadas y terminadas";
                $itemPlan      = (isset($_POST["itemplan"]))    ? $_POST["itemplan"] : null;
                $idProyecto    = (isset($_POST["proyecto"]))    ? $_POST["proyecto"]    : null;
                $idSubProyecto = (isset($_POST["subproyecto"])) ? $_POST["subproyecto"] : null;
                $indicador     = (isset($_POST["indicador"]))   ? $_POST["indicador"]   : null;
                $idFase = (isset($_POST["selectFase"])) ? $_POST["selectFase"] : null;

                if($itemPlan == null && $idProyecto == null && $idProyecto == null && $idSubProyecto  == null && $indicador == null && $idFase == null) {
                    $data["tabla"] = null;
                } else if($itemPlan != null || $idProyecto != null || $idProyecto != null || $idSubProyecto  != null || $indicador != null || $idFase != null) {
                    $data["tabla"]=$this->TablaPendientes($itemPlan, $idProyecto, $idSubProyecto , $indicador, ID_ESTADO_PRE_LIQUIDADO, NULL, $idFase);
                }
            }
            $data["proyecto"]    = $this->LLenarProyecto();
            $data["subproyecto"] = $this->LLenarSubProyecto(NULL);
            $data["fase"]    = $this->LLenarFase();
            
            $this->load->view('vf_layaout_sinfix/header',$data);
            $this->load->view('vf_layaout_sinfix/cabecera');
            $this->load->view('vf_layaout_sinfix/menu');

            $this->load->view('vf_ejecucion/v_pendientes');

            $this->load->view('vf_layaout_sinfix/footer');
            //$this->load->view('recursos_sinfix/js');
            $this->load->view('recursos_sinfix/datatable',$data);
            $this->load->view('recursos_sinfix/fancy',$data);
         }else{
             redirect('login','refresh');
        }
    }
	
    public function TablaPendientes($itemplan,$proyecto,$subproyecto,$indicador, $estadoPlan, $estadoPlan2, $idFase = null){
        $itemplan    = ($itemplan)    ? $itemplan    : null;
        $proyecto    = ($proyecto)    ? $proyecto    : null;
        $subproyecto = ($subproyecto) ? $subproyecto : null;
        $indicador   = ($indicador)   ? $indicador   : null;
        $idFase      = ($idFase)      ? $idFase      : null;
        $arrayEstadoPlan = array(3,4,9,10,11,5);
        $pendiente=$this->M_pendientes->getListaPendiente($itemplan,$proyecto,$subproyecto,$indicador, $estadoPlan, $estadoPlan2, null, $arrayEstadoPlan, $idFase);   
        $html='
            <table id="simpletable" class="table table-hover display  pb-30 table-striped table-bordered nowrap" >
            <thead>
                    <tr class="table-primary">
                    <th>Acción</th>
                    <th>D.J</th>
                    <th>ItemPlan</th>
                    <th>Subproyecto</th>
                    <th>Fase</th>
                    <th>Indicador</th>
                    <th>Estado</th>
                    <th>Central</th>  
                    <th>F. PreLiquidaci&oacute;n</th>  
                    <th>F. Fin Prev</th>
                    <th>Compromiso</th>
                    <th>F. Inicio Prev</th>
                    <th>eecc</th>
                    <th>eecc fuente</th>
                    <th>Jefat</th>
                    <th>F. Termino</th>
                    </tr>
            </thead>
            <tbody>';            
     foreach($pendiente->result() as $row){
        if($row->fechaPrevEjec){
             $fasig=explode("-",$row->fechaPrevEjec);
             $rfecha=$fasig[2]."/".$fasig[1]."/".$fasig[0];
        }else{
            $rfecha="";
        }

        if($row->fechaInicio){    
            $nuevafecha = strtotime ( '+30 day' , strtotime ( $row->fechaInicio ) ) ;
            $nuevafecha = date ( 'd-m-Y' , $nuevafecha );    
        }else{
            $nuevafecha = "";    
        }

         $puntitos="";   
         if(strlen($row->nombreProyecto)>40){
             $puntitos="...";   
         }
           //  <a data-toggle="tooltip" data-trigger="hover" data-placement="top" data-original-title="Asignar" data-item_plan="'.$row->itemPlan.'"class="asignar" onclick="openModalPorcentaje($(this));" href="#" ><i class="fa fa-book"></i></a>
                                     
        //  $terminar = '<a data-toggle="tooltip" data-trigger="hover" data-placement="top"  data-original-title="Terminar" href="#" class="terminar"><i class="fa fa-crosshairs"></i></a>';
        $terminar = null; 
        $obra=$this->M_generales->itemPlanI($row->itemPlan);

        $eyeConsulta       = null;
        $zipSinfixAnterior = null;
        $boton_motivo      = null;
        $color = null;
        $botonParalizacion = null;
        $boton_truncar = null;
        $boton_cancelar = null;
        $botonZipEvidencia = ' <a data-toggle="tooltip" data-trigger="hover" data-placement="top" data-original-title="descarga zip de las evidencias" data-item_plan="'.$row->itemPlan.'" style="cursor:pointer" onclick="zipItemPlan($(this));"><i class="fa fa-download"></i></a>';
        $botonPorcentaje   = '<a data-toggle="tooltip" data-trigger="hover" data-placement="top" data-original-title="Porcentaje" data-item_plan="'.$row->itemPlan.'" style="cursor:pointer" onclick="openModalPorcentaje($(this));"><i class="fa fa-book"></i></a>';
        if($this->session->userdata("eeccSession")==6||$this->session->userdata("eeccSession")==0||$this->session->userdata("idPerfilSession")==10){
            if($row->idEstadoPlan == ID_ESTADO_TRUNCO) {
                $botonZipEvidencia = null;
                $botonParalizacion = null;
                //$botonPorcentaje   = null;
                $boton_truncar='<a data-toggle="tooltip" data-trigger="hover" data-placement="top" style="cursor:pointer"  data-original-title="Regresar Obra" data-itemplan="'.$row->itemPlan.'" onclick="regresarTrunco($(this))"><i class="fa fa-retweet"></i></a>';
                //$boton_truncar= null;
                
            } else if($row->idEstadoPlan == ID_ESTADO_PLAN_EN_OBRA) {
                $boton_truncar='<a data-toggle="tooltip" data-trigger="hover" data-placement="top"  data-original-title="Truncar Obra" class="truncar_obra" style="cursor:pointer" data-itemplan="'.$row->itemPlan.'" data-fecha="'.$this->fechaActual().'" data-data_fecha="fechaTrunca" data-estado_plan="'.ID_ESTADO_TRUNCO.'" onclick="openModalTruncar($(this))"><i class="fa fa-times-circle-o"></i></a>';
            }  
        }
        $countParalizados = $this->m_utils->countParalizados($row->itemPlan, FLG_ACTIVO, ORIGEN_SINFIX);
       
           if($row->idProyecto == ID_PROYECTO_SISEGOS) {
               if($countParalizados > 0) {
                    $boton_motivo = '<a data-toggle="tooltip" data-trigger="hover" data-placement="top"  data-original-title="Ver motivo" style="cursor:pointer" data-itemplan="'.$row->itemPlan.'" onclick="verMotivoParalizacion($(this))"><i class="fa fa-comments"></i></a>';
               }
               
            $eyeConsulta = '<a data-toggle="tooltip" style="cursor:pointer" data-trigger="hover" data-placement="top"  data-original-title="ver detalle" data-indicador="'.$row->indicador.'" data-grafo="'.$row->grafo.'" data-item_plan="'.$row->itemPlan.'" onclick="openView($(this));"><i class="fa fa-eye"></i></a>';
            $direc = 'uploads/zip/'.$row->itemPlan.'-'.$row->ProyectoDesc.'.zip';
            if(file_exists($direc)) {
                $zipSinfixAnterior = '<a data-toggle="tooltip" data-trigger="hover" data-placement="top" data-original-title="descarga zip de sinfix anterior" data-item_plan="'.$row->itemPlan.'" data-desc_proy="'.$row->ProyectoDesc.'" data-ubicacion="'.$direc.'" style="cursor:pointer" onclick="zipSinfixAnterior($(this));"><i class="fa fa-arrow-circle-down"></i></a>';
            }

            if($row->idEstadoPlan == ID_ESTADO_PLAN_EN_OBRA && $countParalizados == 0 && ($this->session->userdata('eeccSession')==6 || $this->session->userdata('eeccSession')== 0)) { 
                $botonParalizacion   = '<a data-toggle="tooltip" data-trigger="hover" data-placement="top" data-original-title="Paralizaci&oacute;n" data-itemplan="'.$row->itemPlan.'" style="cursor:pointer" onclick="openModalParalizacion($(this),1);"><i class="fa fa-paragraph"></i></a>';
              
            }
            
            if($row->idEstadoPlan == ID_ESTADO_DISENIO_PARCIAL || $row->idEstadoPlan == ID_ESTADO_PLAN_EN_OBRA) {
                  $boton_cancelar = '<a data-toggle="tooltip" data-trigger="hover" data-placement="top" style="cursor:pointer"  data-original-title="Cancelar" 
            data-id_estadoplan="'.$row->idEstadoPlan.'" data-itemplan="'.$row->itemPlan.'" onclick="openModalAlert($(this))"><i class="fa fa-ban"></i></a>';
            }
            
            if($countParalizados > 0) {
                $color = '#FDBDBD';
                $botonPorcentaje = 'Paralizado';
                $boton_truncar = null;
                $boton_cancelar = null;
            }
       }
        //$btnVerMotivos = '<a data-toggle="tooltip" data-trigger="hover" data-placement="top"  data-original-title="Truncar Obra" class="truncar_obra" href="#"><i class="fa fa-times-circle-o"></i></a>' 
        
        ///////////////////11-09-2018//////////////////////////////
        $botonLicencia = '';
        if($row->flgLicencia > 0){
            $botonLicencia = '<a data-toggle="tooltip" data-trigger="hover" data-placement="top" data-original-title="Licencias" data-item_plan="'.$row->itemPlan.'" data-jefatura="'.$row->jefatura.'" style="cursor:pointer" onclick="openModalEviLic($(this));"><i class="fa fa-file-image-o"></i></a>';
        }
        /////////////////////////////////////////////////////////
        
        
        
        if($row->idEstadoPlan == ID_ESTADO_DISENIO_PARCIAL) {
            $terminar          = null;
            $boton_truncar     = null;
            $zipSinfixAnterior = null;
            $boton_motivo      = null;
            $botonZipEvidencia = null;
            $botonPorcentaje   = null;
            $eyeConsulta       = null;
        }
        $infoSwitchItem = $this->m_utils->getInfoToSwitchSiomByItemplan($row->itemPlan);
        //$switcSiom = $this->m_utils->getCountSwitchSiom($infoSwitchItem['idEmpresaColab'], $infoSwitchItem['jefatura'], $infoSwitchItem['idSubProyecto']);
		$switcSiom = 1;//TODO VA A SIOM 01-10-2020 czavala
        ###################Nuevo para DJ czavalacas 28.05.2019###########################
        $btnFormulario = '';
        $btnUM = '';
        $flg =null;
        if($switcSiom  >  0){
            $existSiomObra = $this->M_porcentaje->existOnSiomObra($row->itemPlan);
			#$existSiomObra = $this->M_porcentaje->goToSiomByItemplan($itemplan);#es mayor al 26 de noviembre obligado va a siom.
            if($existSiomObra   ==  0){
                $switcSiom = 0;
            }else{
                $countFicha     = $this->M_porcentaje->countFichaTecnica($row->itemPlan);
                $countFormObrap = $this->M_porcentaje->countFormObrap($row->itemPlan, ID_ESTACION_FO);//FORMULARIO DE OP Y SISEGOS ES OBRA PUBLICA
                $infoItenForm = $this->M_porcentaje->getInfoEstacionByItemplanFormulario($row->itemPlan, ID_ESTACION_FO);
                $infoOS = $this->M_porcentaje->has_os_vali($row->itemPlan, ID_ESTACION_FO);
                
                if($infoItenForm['countSwitchForm'] == 1 && $countFicha == 0 && $infoOS['num'] >= 1 && ($infoOS['has_validando'] == $infoOS['num'])) {
                    $flg=2;
                    $indicador = $infoItenForm['indicador'];
                    $jefatura = $infoItenForm['jefatura'];
                    $empresaColabdesc = $infoItenForm['descEmpresaColab'];
                    $idEstadoPlan = $infoItenForm['idEstadoPlan'];
					/*
                    $btnFormulario = '<button title="Ver" class="btn btn-success btn-rounded  btn-anim mt-10" data-indicador="'.$indicador.'" data-flg_from="'.$flg.'"
                                                    data-jefatura="'.$jefatura.'" data-id_estado_plan="'.$idEstadoPlan.'" data-item_plan="'.$row->itemPlan.'" data-desc_emp_colab="'.$empresaColabdesc.'" data-id_estacion="'.ID_ESTACION_FO.'"
                                                     onclick="openModalBandejaEjecucionFuera($(this));">
                                                    <i class="fa fa-eye"></i>
                                                    <span class="btn-text">FORM. FO</span>
                                              </button>';*/
				    $btnFormulario = 'PDT REGISTRO FORM.';
                }
                if($infoItenForm['countSwitchObPublicas'] == 1 && $countFormObrap == 0 && $infoOS['num'] >= 1 && ($infoOS['has_validando'] == $infoOS['num'])) {
                    $indicador = $infoItenForm['indicador'];
                    $jefatura = $infoItenForm['jefatura'];
                    $empresaColabdesc = $infoItenForm['descEmpresaColab'];
                    $idEstadoPlan = $infoItenForm['idEstadoPlan'];
					$btnFormulario = '<button title="Ver" class="btn btn-success btn-rounded  btn-anim mt-10" data-indicador="'.$indicador.'" data-flg_from="'.$flg.'"
                                                    data-jefatura="'.$jefatura.'" data-item_plan="'.$row->itemPlan.'" data-desc_emp_colab="'.$empresaColabdesc.'" data-id_estacion="'.ID_ESTACION_FO.'"
                                                    onclick="openModalFormObPub($(this));">
                                                    <i class="fa fa-eye"></i>
                                                    <span class="btn-text">Formulario</span>
                                               </button>';
                }
                ##al ultimo##
                if($infoItenForm['countSwitchForm'] == 1 || $infoItenForm['countSwitchObPublicas'] == 1){
                    $estadoDJ = $this->M_porcentaje->getEstadoDJByItemplanEstacion($row->itemPlan, ID_ESTACION_FO);
                    if($estadoDJ['cant'] == 1){
                        if($estadoDJ['estado_validacion']==1){
                            $btnFormulario = 'D.J. APROBADA - FO';
                        }else if($estadoDJ['estado_validacion'] == '' || $estadoDJ['estado_validacion'] == null){
                            $btnFormulario = 'D.J. PDTE VALIDACION - FO';
                        }
                    }
                }    
                /**
                 * BOTON FORMULARIO UM: SOLO SI ES SUBPROYECTO ACLERACION MOVIL O PROYECTO SISEGOS, QUE CUENTEN CON OS EN SIOM UM EN ESTADO VALIDANDO Y NO TENGA FORMULARIO REGISTRADO.
                 */
                if($row->idSubProyecto == ID_SUBPROYECTO_ACELERACION_MOVIL || $row->idProyecto == ID_PROYECTO_SISEGOS){
                    $infoUMForm = $this->M_porcentaje->valid_um_form($row->itemPlan, ID_ESTACION_UM);
                    if($infoUMForm['num'] >= 1 && ($infoUMForm['num'] == $infoUMForm['valis']) &&  $infoUMForm['has_form'] == 0){
                        $btnUM  = '<button title="FORMULARIO UM" class="btn btn-success btn-rounded  btn-anim mt-10" data-item_plan="'.$row->itemPlan.'"
                                        onclick="openFormUM($(this));">
                                        <i class="fa fa-eye"></i>
                                        <span class="btn-text">FORM. UM</span>
                                   </button>';
                    }else if($infoUMForm['has_form'] == 1){
                        $estadoDJUM = $this->M_porcentaje->getEstadoDJByItemplanEstacion($row->itemPlan, ID_ESTACION_UM);
                        if($estadoDJUM['cant'] == 1){
                            if($estadoDJUM['estado_validacion']==1){
                                $btnUM = 'D.J. APROBADA - UM';
                            }else if($estadoDJUM['estado_validacion'] ==  '' || $estadoDJUM['estado_validacion'] == null){
                                $btnUM = 'D.J. PDTE VALIDACION - UM';
                            }
                        }
                    }
                }
            }
        }
        
        if($row->operador == 'ESTUDIO DE CAMPO') {
            $boton_cancelar = null;
            $eyeConsulta    = null;
            $terminar       = null;
            $boton_truncar  = null;
            $zipSinfixAnterior = null;
            $boton_motivo    = null;
            $botonZipEvidencia = null;
            $botonParalizacion = null;
            $botonLicencia     = null;
            $botonPorcentaje   = null;
        }
        
		#############04.11.2019 permitir la liquidacion por la caida de siom, descomentar cuando retorne la integracion.####################		
		#$switcSiom = 0;
		#####################################################################################
        ##############################################

        $html.='<tr style="background:'.$color.'">
                 <td>
                 '.($switcSiom  ==  0 ? $botonPorcentaje : 'GESTION EN SIOM').'<br>
                 '.$boton_cancelar.'
                 '.$eyeConsulta.'
                 '.$terminar.$boton_truncar.' 
                 '.$zipSinfixAnterior.'
                 '.$boton_motivo.'
                 <a data-toggle="tooltip" data-trigger="hover" data-placement="top"  data-original-title="Ver PTR" href="#" class="ver_ptr" data-item_plan="'.$row->itemPlan.'" data-tipo_planta="'.$row->idTipoPlanta.'" onclick="getConsultaPtr($(this))"><i class="fa fa-money"></i></a>
                 '.$botonZipEvidencia.'
                 '.$botonParalizacion.'
                 '.$botonLicencia.'
                 </td>
                 <td>'.$btnFormulario.'<br>'.$btnUM.'</td> 
                 <td>'.$row->itemPlan.'</td> 
                 <td>'.$row->subProyectoDesc.'</td>
                 <td>'.$row->faseDesc.'</td>
                 <td>'.$row->indicador.'</td>
                 <td>'.$row->estadoPlanDesc.'</td>
                 <td>'.$row->tipoCentralDesc.'</td>
                 <td>'.$row->fechaPreLiquidacion.'</td>
                 <td>'.$rfecha.'</td>
                 <td>'.$nuevafecha.'</td>
                 <td>'.$row->fechaInicio.'</td>
                 <td>'.$row->empresaColabDesc.'</td>
                 <td>'.$row->empresaColabDescFuente.'</td>
                 <td>'.$row->zonalDesc.'</td>
                 <td>'.$row->fechaEjecucion.'</td>
                 </tr>';
     }
     $html.="</tbody></table>";    
     return $html;
    }
    public function TablaPreLiquidadas(){
     $html='
            <table id="simpletable" class="table table-hover display  pb-30 table-striped table-bordered nowrap" >
            <thead>
                    <tr class="table-primary">
                    <th>Acción</th>
                    <th>ItemPlan</th>
                    <th>Subproyecto</th>
                    <th>Indicador</th>
                    <th>Central</th>  
                    <th>F. Fin Prev</th>
                    <th>Compromiso</th>
                    <th>F. Inicio Prev</th>
                    <th>eecc</th>
                    <th>Jefatura</th>
                    </tr>
            </thead>
            <tbody>';
     foreach($this->M_pendientes->getListarPreLiquidadas()->result() as $row){
        if($row->fechaPrevEjec){
             $fasig=explode("-",$row->fechaPrevEjec);
             $rfecha=$fasig[2]."/".$fasig[1]."/".$fasig[0];
        }else{
            $rfecha="";
        }

        if($row->fechaInicio){    
            $nuevafecha = strtotime ( '+30 day' , strtotime ( $row->fechaInicio ) ) ;
            $nuevafecha = date ( 'd-m-Y' , $nuevafecha );    
        }else{
            $nuevafecha = "";    
        }

         $puntitos="";   
         if(strlen($row->nombreProyecto)>40){
             $puntitos="...";   
         }
         $html.='<tr>
                 <td>
                 <a data-toggle="tooltip" data-trigger="hover" data-placement="top" data-original-title="Asignar" href="#" class="asignar"><i class="fa fa-book"></i></a>
                 
                 <a data-toggle="tooltip" data-trigger="hover" data-placement="top"  data-original-title="Ver PTR" href="#" class="ver_ptr"><i class="fa fa-money"></i></a>
                 <a data-toggle="tooltip" data-trigger="hover" data-placement="top"  data-original-title="Terminar" href="#" class="terminar"><i class="fa fa-crosshairs"></i></a>                 
                 </td>
                 <td>'.$row->itemPlan.'</td> 
                 <td>'.$row->subProyectoDesc.'</td>                
                 <td>'.$row->indicador.'</td>
                 <td>'.$row->tipoCentralDesc.'</td>
                 <td>'.$rfecha.'</td>
                 <td>'.$nuevafecha.'</td>
                 <td>'.$row->fechaInicio.'</td>
                 <td>'.$row->empresaColabDesc.'</td>
                 
                 <td>'.$row->zonalDesc.'</td>
                 </tr>';
     }
     $html.="</tbody></table>";    
     return $html;
    }
    
    public function TablaTrunca(){
     $trunca=$this->M_pendientes->getListarTruncar();   
     $html='
        <table id="simpletable" class="table table-hover display  pb-30 table-striped table-bordered nowrap" >
            <thead>
                  <tr class="table-primary">
                  <th>Acción</th>
                  <th>ItemPlan</th>
                  <th>Subproyecto</th>
                  <th>Motivo</th>
                  <th>Fecha Trunca</th>
                  <th>Indicador</th>
                  <th>Central</th>  
                  <th>F. Fin Prev</th>
                  <th>Compromiso</th>
                  <th>F. Inicio Prev</th>
                  <th>eecc</th>
                  <th>Jefatura</th>
                  </tr>
            </thead>
            <tbody>';            
     foreach($trunca->result() as $row){
        $boton_truncar="";
     
        if($this->session->userdata("idPerfilSession")==3||$this->session->userdata("idPerfilSession")==4||$this->session->userdata("idPerfilSession")==10){
                $boton_truncar='<a data-toggle="tooltip" data-trigger="hover" data-placement="top" style="cursor:pointer"  data-original-title="Regresar Obra" data-itemplan="'.$row->itemPlan.'" onclick="regresarTrunco($(this))"><i class="fa fa-retweet"></i></a>';
         }        
        if($row->fechaPrevEjec){
             $fasig=explode("-",$row->fechaPrevEjec);
             $rfecha=$fasig[2]."/".$fasig[1]."/".$fasig[0];
        }else{
            $rfecha="";
        }

        if($row->fechaInicio){    
            $nuevafecha = strtotime ( '+30 day' , strtotime ( $row->fechaInicio ) ) ;
            $nuevafecha = date ( 'd-m-Y' , $nuevafecha );    
        }else{
            $nuevafecha = "";    
        }

         $puntitos="";   
         if(strlen($row->nombreProyecto)>40){
             $puntitos="...";   
         }
         $terminar = '<a data-toggle="tooltip" data-trigger="hover" data-placement="top"  data-original-title="Terminar" href="#" class="terminar"><i class="fa fa-crosshairs"></i></a>';
         $date = date_create($row->fechaTrunca);
         $row->fechaTrunca=date_format($date, 'd-m-Y');
         if($row->fechaTrunca=='30-11--0001'){$row->fechaTrunca="";}      
         $html.='<tr>
                 <td>

                 <a data-toggle="tooltip" data-trigger="hover" data-placement="top"  data-original-title="Ver PTR" href="#" class="ver_ptr"><i class="fa fa-money"></i></a>
                 '.$boton_truncar.'                
                 </td>
                 <td>'.$row->itemPlan.'</td> 
                 <td>'.$row->subProyectoDesc.'</td>
                 <td>'.$row->motivoTrunco.'</td>
                 <td>'.$row->fechaTrunca.'</td>                 
                 <td>'.$row->indicador.'</td>
                 <td>'.$row->tipoCentralDesc.'</td>
                 <td>'.$rfecha.'</td>
                 <td>'.$nuevafecha.'</td>
                 <td>'.$row->fechaInicio.'</td>
                 <td>'.$row->empresaColabDesc.'</td>
                 <td>'.$row->zonalDesc.'</td>
                 </tr>';
     }
     $html.="</tbody></table>";    
     return $html;
    }
    
    public function TablaLiquidadas(){
             $html='
            <table id="simpletable" class="table table-hover display  pb-30 table-striped table-bordered nowrap" >
            <thead>
                          <tr class="table-primary">
                          <th>Acción</th>
                          <th>ItemPlan</th>
                          <th>Subproyecto</th>
                          <th>Indicador</th>
                          <th>Central</th> 
                          <th>F. Liquidación</th> 
                          <th>F. Fin Prev</th>
                          <th>F. Compromiso</th>
                          <th>F. Inicio Prev</th>
                          <th>eecc</th>
                          <th>Jefatura</th>
                          </tr>
            </thead>
            <tbody>';
     foreach($this->M_pendientes->getListarLiquidadas()->result() as $row){
        if($row->fechaPrevEjec){
             $fasig=explode("-",$row->fechaPrevEjec);
             $rfecha=$fasig[2]."/".$fasig[1]."/".$fasig[0];
        }else{
            $rfecha="";
        }
        $row->fechaEjecucion=date_format(date_create($row->fechaEjecucion),"d/m/Y");
        if($row->fechaInicio){    
            $nuevafecha = strtotime ( '+30 day' , strtotime ( $row->fechaInicio ) ) ;
            $nuevafecha = date ( 'd-m-Y' , $nuevafecha );    
        }else{
            $nuevafecha = "";    
        }

         $puntitos="";   
         if(strlen($row->nombreProyecto)>40){
             $puntitos="...";   
         }
         $extra_h="";
if(file_exists("uploads/zip/".$row->itemPlan."-".str_replace(" ","",$row->ProyectoDesc).".zip")){
$extra_h='<a data-toggle="tooltip" data-trigger="hover" data-placement="top"  data-original-title="Descargar Obra" href="uploads/zip/'.$row->itemPlan."-".str_replace(" ","",$row->ProyectoDesc).'.zip" download="uploads/zip/'.$row->itemPlan."-".str_replace(" ","",$row->ProyectoDesc).'.zip"><i class="fa fa-file-zip-o"></i></a>';
}
         $html.='<tr>
                 <td>
                 <a data-toggle="tooltip" data-trigger="hover" data-placement="top"  data-original-title="Ver PTR" href="#" class="ver_ptr"><i class="fa fa-money"></i></a>
                 <a data-toggle="tooltip" data-trigger="hover" data-placement="top"  data-original-title="Archivos" href="#" class="terminar"><i class="fa fa-crosshairs"></i></a> 
                 '.$extra_h.'                 
                 </td>
                 <td>'.$row->itemPlan.'</td> 
                 <td>'.$row->subProyectoDesc.'</td>                
                 <td>'.$row->indicador.'</td>
                 <td>'.$row->tipoCentralDesc.'</td>
                 <td>'.$row->fechaEjecucion.'</td>                 
                 <td>'.$rfecha.'</td>
                 <td>'.$nuevafecha.'</td>
                 <td>'.$row->fechaInicio.'</td>
                 <td>'.$row->empresaColabDesc.'</td>
                 
                 <td>'.$row->zonalDesc.'</td>
                 </tr>';
     }
     $html.="</tbody></table>";    
     return $html;
    }
    function cambiarEstadoItemPlan() {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try {
            $itemPlan = $this->input->post('itemPlan');
            $idSubProyecto = $this->input->post('idSubProyecto');

            if($itemPlan == null) {
                throw new Exception('Acción no permitida');
            }

            if($idSubProyecto == null) {
                throw new Exception('Acción no permitida');
            }
            
            $data = $this->M_pendientes->cambiarEstado($itemPlan, $idSubProyecto);
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }   
    function LLenarProyecto(){
        $option="";
        $proyecto=$this->M_generales->ListarProyecto();
        foreach ($proyecto->result() as $row) {
            $option.='<option value="'.$row->idProyecto.'">'.$row->proyectoDesc.'</option>';
        }
        return $option;
    }
    
    function LLenarFase(){
        $option="";
        $fase = $this->m_utils->getAllFase();
        foreach ($fase->result() as $row) {
            $option.='<option value="'.$row->idFase.'">'.$row->faseDesc.'</option>';
        }
        return $option;
    }

    function getSubProyectoFiltro() {
        $idProyecto = $this->input->post('idProyecto');
        $cmbSubProyecto = $this->LLenarSubProyecto($idProyecto);
        $data['cmbProyecto'] = $cmbSubProyecto;
        echo json_encode(array_map('utf8_encode', $data));
    }

    function LLenarSubProyecto($idProyecto){
        $option='<option value="0">Seleccionar SubProyecto</option>';
        $subproyecto=$this->M_generales->ListarSubProyecto($idProyecto);
        foreach ($subproyecto->result() as $row) {
            $option.='<option value="'.$row->idSubProyecto.'">'.$row->subProyectoDesc.'</option>';
        }
        return $option;
    }

    function zipItemPlan() {
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
    
    function removeZip() {
        $url = $this->input->post('url');
        unlink($url);
    }
    
    function cambiarEstadoObra() {
        $itemPlan    = $this->input->post('itemPlan');
        $estadoPlan  = $this->input->post('estadoPlan');
        $fecha       = $this->input->post('data_fecha');
        $comentario  = $this->input->post('comentario');
        $idMotivo    = $this->input->post('idMotivo');

        $arrayData = array(
                            'idEstadoPlan' => $estadoPlan,
                            'fechaTrunca'  => $this->fechaActual(),
                            'motivoTrunco' => $comentario,
                            'idMotivo'     => $idMotivo         
                          );
        
        $val = $this->m_utils->cambiarEstadoObra($itemPlan, $arrayData);

        if($val == 1) {
            $arrayDataLog = array(
                                    'tabla'            => 'sinfix',
                                    'actividad'        => 'Truncar Obra',
                                    'itemplan'         => $itemPlan,
                                    'fecha_registro'   => $this->fechaActual(),
                                    'id_usuario'       => $this->session->userdata('idPersonaSession'),
                                    'itemplan_default' => $motivo,
                                    'idMotivo'         => $idMotivo,
                                    'idEstadoPlan'     => $estadoPlan
                                 );

            $this->m_utils->registrarLogPlanObra($arrayDataLog);
        }
    }
    
    function getCmbMotivo() {
        $flgTipo = $this->input->post('flgTipo');
        $itemplan = $this->input->post('itemplan');
        $arrayMotivo = $this->m_utils->getMotivoAll($flgTipo);
        $this->session->set_userdata('itemplanParalizacion', $itemplan);
        $data['arrayMotivo'] = $arrayMotivo;
        echo json_encode($data);
    }
    
     function insertFileParalizacion() {
        $itemplan =  $this->session->userdata('itemplanParalizacion');

        $file     = $_FILES ["file"] ["name"];
        $filetype = $_FILES ["file"] ["type"];
        $filesize = $_FILES ["file"] ["size"];

        $ubicacion = 'uploads/evidencia_paralizacion/'.$itemplan;
        if(!is_dir($ubicacion)) {
            mkdir ('uploads/evidencia_paralizacion/'.$itemplan, 0777);
        }

        $file2 = utf8_decode($file);

        if (utf8_decode($file) && move_uploaded_file($_FILES["file"]["tmp_name"], $ubicacion."/".$file2 )) {
        }
        $data['error'] = EXIT_SUCCESS;
        echo json_encode(array_map('utf8_encode', $data));
    }
	
	//SE MANDO A C_bandeja_paralizacion
    function insertParalizacion() {
        $data['msj']   = null;
        $data['error'] = EXIT_ERROR;
        try {
            $idMotivo   = $this->input->post('idMotivo');
            $comentario = $this->input->post('comentario');
            $itemplan   = $this->input->post('itemplan');
            $motivo     = $this->input->post('motivo');
            $origen     = $this->input->post('origen');
            
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }
            
            if($itemplan == '' || $itemplan == null) {
                throw new Exception('Itm');
            }

            if($idMotivo == '' || $idMotivo == null) {
                throw new Exception('debe seleccionar un motivo');
            }
            
            if($origen == '' || $origen == null) {
                throw new Exception('origen null');
            }
            
            $ubicacion = null;
            $dataArray = array(
                                'itemplan'           => $itemplan,
                                'idMotivo'           => $idMotivo,
                                'comentario'         => $comentario,
                                'fechaRegistro'      => $this->fechaActual(),
                                'idUsuario'          => $this->session->userdata('idPersonaSession'),
                                'flg_activo'         => FLG_ACTIVO,
                                'ubicacionEvidencia' => $ubicacion,
                                'flgEstado'          => $origen
                              );
            $data = $this->M_pendientes->insertParalizacion($dataArray);
            
            $arrayDataItem = array('has_paralizado' => 1,
                                'fecha_paralizado'  => $this->fechaActual(),
                                'motivo_paralizado' =>  $idMotivo,
                                'fecha_reactiva_paralizado' =>  null
            );
            $data = $this->m_utils->simpleUpdatePlanObra($itemplan, $arrayDataItem);
            
             $arrayDataLog = array(
                                'tabla'            => 'planobra',
                                'actividad'        => 'Paralizacion Web PO',
                                'itemplan'         => $itemplan,
                                'fecha_registro'   => $this->fechaActual(),
                                'id_usuario'       => $this->session->userdata('idPersonaSession')
                            );

            $this->m_utils->registrarLogPlanObra($arrayDataLog);
            
            $dataSend = ['itemplan'      => $itemplan,
                         'fecha'         => $this->fechaActual(),
                         'flg_activo'    => FLG_ACTIVO,
                         'motivo'        => $motivo,
                         'nombreUsuario' => $this->session->userdata('usernameSession'),
                         'correo'        => $this->session->userdata('email'),
                         'comentario'    => $comentario];

            $url = 'https://172.30.5.10:8080/obras2/recibir_par.php';

			$data = _trama_sisego($dataSend, $url, 7, $itemplan, 'ENVIAR PARALIZACION', NULL);

        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));        
    }
    
	
	//Envian desde sisego WEB
    function insertTramaParalizacion() {
        $data['msj']   = null;
        $data['error'] = EXIT_ERROR;
        try {
            $comentario     = $this->input->post('comentario');
            $itemplan       = $this->input->post('itemplan');
            $nombreUsuario  = $this->input->post('nombreUsuario');
            $flg_activo     = $this->input->post('flg_activo');
			
            if($itemplan == '' || $itemplan == null) {
                throw new Exception('no llego itemplan');
            }
			
			$idEstadoPlan = $this->m_utils->getEstadoPlanByItemplan($itemplan);
			
			if($idEstadoPlan == 4 || $idEstadoPlan == 9) {
				throw new Exception('No se puede paralizar Obras en estado Terminado o Preliquidado.');
			}
			
			if($flg_activo == null || $flg_activo == '') {
				throw new Exception('No se envia el flg de paralizacion.');
			}
			
            if($flg_activo == FLG_ACTIVO) {
				$tipo = 'PARALIZACION';
                $motivo = $this->input->post('motivo');

                if($motivo == '' || $motivo == null) {
                    throw new Exception('no llego motivo');
                }
                
                $countParalizados = $this->m_utils->countParalizados($itemplan, $flg_activo, 2);
				
                if($countParalizados != 0) {
                    throw new Exception('itemplan ya se encuentra paralizado');
                }

                // $idMotivo = $this->m_utils->getIdMotivo($motivo);
                
				// if($idMotivo == null || $idMotivo == '') {
					// $idMotivo = 71; //SE ASIGNA EL MOTIVO GICS SI NO HAY
				// }
				$idMotivo = 71;
                $ubicacion = null;
                $dataArray = array(
                                    'itemplan'           => $itemplan,
                                    'idMotivo'           => $idMotivo,
                                    'comentario'         => $comentario,
                                    'fechaRegistro'      => $this->fechaActual(),
                                    'nombreUsuarioTrama' => $nombreUsuario,
                                    'flg_activo'         => $flg_activo,
                                    'ubicacionEvidencia' => $ubicacion,
                                    'flgEstado'          => 2
                                  );
                $data = $this->M_pendientes->insertParalizacion($dataArray);
                
                $arrayDataItem = array('has_paralizado' => 1,
                    'fecha_paralizado'  => $this->fechaActual(),
                    'motivo_paralizado' =>  $idMotivo,
                    'fecha_reactiva_paralizado' =>  null
                );
                $data = $this->m_utils->simpleUpdatePlanObra($itemplan, $arrayDataItem);
                
				$this->m_utils->saveLogSigoplus('RECIBIR PARALIZACION', null , $itemplan, null, null, null, null, 
				                                'TRAMA COMPLETADA', 'OPERACION REALIZADA CON EXITO', 1, 9, null);
            } else {
				$tipo = 'RECIBIR REVER PARALIZACION';
				
				$countDesp = $this->m_utils->getFlgDespSisego($itemplan);
				
				if($countDesp > 0) {
                    throw new Exception('NO SE PERMITE DESPARALIZAR POR EL MOTIVO QUE TIENE.');
                }
				
                $dataArray = array( 'flg_activo'        => FLG_INACTIVO,
                                    'fechaReactivacion' => $this->fechaActual());

                $data = $this->m_utils->updateFlgParalizacion($itemplan, FLG_ACTIVO, $dataArray);
                
                $arrayDataItem = array('has_paralizado' => null,
                                        'fecha_paralizado'  => null,
                                        'motivo_paralizado' =>  null,
                                        'fecha_reactiva_paralizado' =>  $this->fechaActual()
                                    );
                $data = $this->m_utils->simpleUpdatePlanObra($itemplan, $arrayDataItem);
            
				$this->m_utils->saveLogSigoplus('RECIBIR REVER PARALIZACION', null , 
												$itemplan, null, null, null, null, 
												'TRAMA COMPLETADA', 'OPERACION REALIZADA CON EXITO', 1, 9,null);

			}           
            
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->m_utils->saveLogSigoplus($tipo,  NULL, $itemplan, null, null, NULL, NULL, 'ERROR EN RECEPCION DE TRAMA', $e->getMessage(), 2, 9, null);                        
        }
		echo json_encode(array_map('utf8_encode', $data));       
    }
    
    function updateFileParalizacion() {
        $data['msj']   = null;
        $data['error'] = EXIT_ERROR;
        try {
            $itemplan = $this->input->post('itemplan');

            $ubicacion1 = 'uploads/evidencia_paralizacion/'.$itemplan;
            if(is_dir($ubicacion1)) {
                $ubicacion = $this->comprimirFiles($itemplan);  
                $data = $this->m_utils->updateUbicacion($itemplan, FLG_ACTIVO, $ubicacion);          
            } else {
                $ubicacion = null;
            }
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));         
    }
    
    function comprimirFiles($itemplan) {
        $ubicacion = 'uploads/evidencia_paralizacion/'.$itemplan;
        $this->zip->read_dir($ubicacion,false);
        
        $fileName = $itemplan.'_'.rand(1, 100).date("dmhis").'.zip';   
        $ubicZip = 'uploads/evidencia_paralizacion/'.$fileName;   
        $this->zip->archive($ubicZip);
        $this->rrmdir($ubicacion);
        return $ubicZip;
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

    function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone','America/Lima');
        setlocale(LC_TIME, "es_ES","esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }
    
    
    //////////////////////////////11-09-2018///////////////////////////////
    function makeTableEvidenciaLic($itemPlan,$jefatura){
        if($jefatura == 'LIMA'){
            $listaEntidades = $this->M_bandeja_itemplan_estacion->getEntidadesEviLic($itemPlan);
            $html='
                    <table id="tablaEntidadesLic" class="table table-hover display  pb-30 table-striped table-bordered nowrap" >
                    <thead>
                      <tr class="table-primary">
                      <th>ENTIDAD</th>
                      <th>FECHA DE INICIO</th>
                      <th>FECHA DE FIN</th>
                      <th>EVIDENCIA</th>
                      </tr>
                    </thead>
                    <tbody>';
            foreach($listaEntidades as $row){
                $html.='<tr>                         
                            <td>'.$row->desc_entidad.'</td> 
                            <td>
                                <input type="date" id="txtFechaIni" class="custom-control-input"  value="'.$row->fecha_inicio.'" disabled>
                            </td>                
                            <td>
                                <input type="date" id="txtFechaFin" class="custom-control-input"  value="'.$row->fecha_fin.'" disabled>
                            </td>
                            <td><button type="button" id="btnVerEviEnt'.$row->iditemplan_estacion_licencia_det.'" class="btn btn-success" data-ruta_pdf="'.$row->ruta_pdf.'" onclick="descargarPDFEntLic($(this))" ><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button></td>
                        </tr>';
            }  
            
        }else{
            $listaAcotaciones = $this->M_bandeja_itemplan_estacion->getAcotacionesByItemPlan($itemPlan);
            $html='
                    <table id="tablaEntidadesLic" class="table table-hover display  pb-30 table-striped table-bordered nowrap" >
                        <thead>
                            <tr class="table-primary">
                            <th># ACOTACI&Oacute;N</th>
                            <th>FECHA DE ACOTACI&Oacute;N</th>
                            <th>EVIDENCIA</th>
                            </tr>
                        </thead>
                    <tbody>';
            foreach($listaAcotaciones as $row){
                $html.='<tr>                         
                            <td>'.$row->desc_acotacion.'</td> 
                            <td>
                                <input type="date" id="txtFechaAcota'.$row->idAcotacion.'" class="custom-control-input"  value="'.$row->fecha_acotacion.'" disabled>
                            </td>                
                            <td><button type="button" id="btnVerEviEnt'.$row->iditemplan_estacion_licencia_det.'" class="btn btn-success" data-ruta_pdf="'.$row->ruta_foto.'" onclick="descargarPDFEntLic($(this))" ><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button></td>
                        </tr>'; 
            }
        }
        $html.="</tbody></table>"; 
        return $html;
    }

    function getEviLicencias(){
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $itemPlan = $this->input->post('itemPlan');
            $jefatura = $this->input->post('jefatura');
            $htmlTabla = $this->makeTableEvidenciaLic($itemPlan,$jefatura);
            if(isset($htmlTabla)){
                $data['tablaHtmlEnt'] = $htmlTabla;
                $data['error'] = EXIT_SUCCESS;
            }


        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }
    
    function verMotivoParalizacion() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $itemplan = $this->input->post('itemplan');

            if($itemplan == null) {
                throw new Exception('itemplan no ingresada');
            }
            $arrayData = $this->m_utils->getParalizacion($itemplan, FLG_ACTIVO);

            
            foreach($arrayData as $row) {
                $data['motivo']  = $row->motivo;
                $data['usuario'] = $row->usuario;
                $data['fecha']   = $row->fechaRegistro;
                $data['origen']  = $row->origen;
            }
            $data['error'] = EXIT_SUCCESS;
        }catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }
    
    function getMotivoCancelacion() {
        $this->m_utils->getMotivoAll(3);
        $html =  $this->makeComboMotivo($this->m_utils->getMotivoAll(3));
        $data['comboMotivo'] = $html;
        echo json_encode(array_map('utf8_encode', $data));
    }

    function makeComboMotivo($listaMotivos) {
        $html = '<option value="">Seleccionar Motivo</option>';

        foreach ($listaMotivos as $row) {
            $html .= '<option value="'.$row->idMotivo.'">' . $row->motivoDesc . '</option>';
        }
        $html = utf8_decode($html);
        return $html;
    }
    
    function cancelarItemplanPendiente() {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null; 
        try {
            $itemplan        = $this->input->post('itemplan');  
            $idEstadoPlan    = $this->input->post('idEstadoPlan');
            $motivoDesc      = $this->input->post('motivoDesc');
            $idMotivo        = $this->input->post('idMotivo');
            $comentario      = $this->input->post('comentario');

            if($itemplan == null || $itemplan == '') {
                $data['msj2'] = 'comunicarse con el programador';
                throw new Exception('No se ingreso el itemplan');
            }

            if($motivoDesc == '' || $motivoDesc == null) {
                $data['msj2'] = 'comunicarse con el programador';
                throw new Exception('No se ingreso el motivo');
            }

            if($idEstadoPlan == null || $idEstadoPlan == '') {
                $data['msj2'] = 'comunicarse con el programador';
                throw new Exception('No se ingreso el estado plan');
            }
            
            if(in_array($idEstadoPlan, array(ID_ESTADO_PRE_LIQUIDADO, ID_ESTADO_TERMINADO))) {
                $data['msj2'] = 'motivo: itemplan terminado o preliquidado';
                throw new Exception('No se puede cancelar este itemplan');
            }

            $data = $this->m_utils->simpleUpdateEstadoPlanObra($itemplan,  ID_ESTADO_CANCELADO);
            
            $arrayDataLog = array(
                                    'tabla'            => 'Pendientes Sinfix',
                                    'actividad'        => 'Cancelar Obra',
                                    'itemplan_default' => 'idEstado:6',
                                    'itemplan'         => $itemplan,
                                    'fecha_registro'   => $this->fechaActual(),
                                    'id_usuario'       => $this->session->userdata('idPersonaSession'),
                                    'idMotivo'         => $idMotivo,
                                    'comentario'       => $comentario,
                                    'idEstadoPlan'     => 6
                                 );

            $this->m_utils->registrarLogPlanObra($arrayDataLog);
            
            $dataSend = ['itemplan' => $itemplan,
                         'fecha'    => $this->fechaActual(),
                         'estado'   => FLG_CANCELACION_CONFIRMADA,
                         'motivo'   => $motivoDesc ];

            $url = 'https://172.30.5.10:8080/obras2/recibir_can.php';

            $response = $this->m_utils->sendDataToURL($url, $dataSend);

            if($response->error == EXIT_SUCCESS){
                $data['error'] = EXIT_SUCCESS;
                $this->m_utils->saveLogSigoplus('CANCELACION PENDIENTES', null , $itemplan, null, null, null, null, 'TRAMA COMPLETADA', 'OPERACION REALIZADA CON EXITO', 1, $response);
            }else{
                $this->m_utils->saveLogSigoplus('CANCELACION PENDIENTES', null, $itemplan, null, null, null, null, 'FALLA EN LA RESPUESTA DEL HOSTING', strtoupper($response->mensaje), '2', $response);
            } 
        }catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    /**NUEVO FORMULARIO UM
     * Los archivos se guardan en la carpeta de evidencias_fotos en una subcarpeta por la estacion.
     * @throws Exception***/
    
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
                        
            $uploadfile1 = $pathItemEstacion.'/'. basename($_FILES['filePruebas']['name']);
            
            if (move_uploaded_file($_FILES['filePruebas']['tmp_name'], $uploadfile1)) {
                log_message('error', 'Se movio el archivo a la ruta 1.'.$uploadfile1);            
            }else {
                throw new Exception('Hubo un problema con la carga del archivo 1 al servidor, comuniquese con el administrador.');
            }
            
            $uploadfile2 = $pathItemEstacion.'/'. basename($_FILES['filePerfil']['name']);
            
            if (move_uploaded_file($_FILES['filePerfil']['tmp_name'], $uploadfile2)) {
                log_message('error', 'Se movio el archivo a la ruta 2.'.$uploadfile2);
            }else {
                throw new Exception('Hubo un problema con la carga del archivo 2 al servidor, comuniquese con el administrador.');
            }
			
			$uploadfile3 = $pathItemEstacion.'/'. basename($_FILES['filePruebas2']['name']);
            
            if (move_uploaded_file($_FILES['filePruebas2']['tmp_name'], $uploadfile3)) {
                log_message('error', 'Se movio el archivo a la ruta 3.'.$uploadfile3);
            }else {
                throw new Exception('Hubo un problema con la carga del archivo 3 al servidor, comuniquese con el administrador.');
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
                                    'path_pdf_perfil'   =>  $uploadfile2,
                                    'path_pdf_pruebas_2'=>  $uploadfile3
            );
            
            $dataFichaTecnica = array(  'itemplan'              => $itemplan,
                                        'fecha_registro'        => $this->fechaActual(),
                                        'usuario_registro'      => $this->session->userdata('idPersonaSession'),
                                        'estado_validacion'     => '',
                                        'flg_activo'            => 1,
                                        'id_ficha_tecnica_base' => FICHA_BASE_UM,
                                        'id_estacion'           => ID_ESTACION_UM);
            
            
            $data = $this->M_porcentaje->saveFormularioUM($dataFormulario, $dataFichaTecnica);
        }catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }    
}