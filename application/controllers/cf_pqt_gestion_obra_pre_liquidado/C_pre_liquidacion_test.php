<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *
 * @author Gustavo Sedano L.
 * 05/09/2019
 *
 */
class C_pre_liquidacion_test extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_plan_obra/m_planobra');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_ejecucion/M_generales');
        $this->load->model('mf_ejecucion/M_porcentaje');
        $this->load->model('mf_pqt_ejecucion/M_pqt_pendientes');
        $this->load->model('mf_pqt_pre_liquidacion/M_pqt_pre_liquidacion');
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
	        
	        $data['indicador'] = null;
	        $data['flg_from'] = null;
	        $data['jefatura'] = null;
	        $data['id_estado_plan'] = null;
	        $data['desc_emp_colab'] = null;
	        $data['id_estacion'] = ID_ESTACION_FO;
	        $btnFormulario = '';
	        $btnUM = '';
	        
	        $pendiente=$this->M_pqt_pendientes->getListaPendiente($itemplan, ID_ESTADO_PRE_LIQUIDADO);
	        /***************************/
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
	                    $botonPorcentaje   = null;
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
	                    $botonPorcentaje = null;
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
	            $switcSiom = $this->m_utils->getCountSwitchSiom($infoSwitchItem['idEmpresaColab'], $infoSwitchItem['jefatura'], $infoSwitchItem['idSubProyecto']);
	            ###################Nuevo para DJ czavalacas 28.05.2019###########################
	            $btnFormulario = '';
	            $btnUM = '';
	            $flg =null;
	            if($switcSiom  >  0){
	                $existSiomObra = $this->M_porcentaje->existOnSiomObra($row->itemPlan);
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
	                        $btnFormulario = '<button title="Ver" class="btn btn-success btn-rounded  btn-anim mt-10" data-indicador="'.$indicador.'" data-flg_from="'.$flg.'"
                                                    data-jefatura="'.$jefatura.'" data-id_estado_plan="'.$idEstadoPlan.'" data-item_plan="'.$row->itemPlan.'" data-desc_emp_colab="'.$empresaColabdesc.'" data-id_estacion="'.ID_ESTACION_FO.'"
                                                     onclick="openModalBandejaEjecucionFuera($(this));">
                                                    <i class="fa fa-eye"></i>
                                                    <span class="btn-text">FORM. FO</span>
                                              </button>';
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
	        
	            ##############################################
	            $data['ftermino'] = $row->fechaEjecucion;
	            $data['jefatura'] = $row->zonalDesc;
	            $data['eeccfuente'] = $row->empresaColabDescFuente;
	            $data['eecc'] = $row->empresaColabDesc;
	            $data['finicioprev'] = $row->fechaInicio;
	            $data['compromiso'] = $nuevafecha;
	            $data['ffinprev'] = $rfecha;
	            $data['fpreliquidacion'] = $row->fechaPreLiquidacion;
	            $data['central'] = $row->tipoCentralDesc;
	            $data['estado'] = $row->estadoPlanDesc;
	            $data['indicador'] = $row->indicador;
	            $data['fase'] = $row->faseDesc;
	            $data['subProyecto'] = $row->subProyectoDesc;
	            $data['proyecto'] = $row->ProyectoDesc;
	            $data['btnFormulario1'] = $btnFormulario;
	            $data['btnFormulario2'] = $btnUM;
	            $data['btnverptr'] = '<a data-toggle="tooltip" data-trigger="hover" data-placement="top"  data-original-title="Ver PTR" href="#" class="ver_ptr" data-item_plan="'.$row->itemPlan.'" data-tipo_planta="'.$row->idTipoPlanta.'" onclick="getConsultaPtr($(this))"><i class="fa fa-money"></i></a>';
	            //$data['btnzipevidencias'] = $botonZipEvidencia;
	            //$data['btnlicencias'] = $botonLicencia;
	            
	            $data['btnlicencias'] = '';
	            $data['btnzipevidencias'] = ($switcSiom  ==  0 ? $botonPorcentaje : 'GESTION EN SIOM').'<br>
                 '.$boton_cancelar.'
                 '.$eyeConsulta.'
                 '.$terminar.$boton_truncar.'
                 '.$zipSinfixAnterior.'
                 '.$boton_motivo.'
                 '.$botonZipEvidencia.'
                 '.$botonParalizacion.'
                 '.$botonLicencia;
	            
	            /*$html.='<tr style="background:'.$color.'">
                 <td>
                 '.($switcSiom  ==  0 ? $botonPorcentaje : 'GESTION EN SIOM').'<br>
                 '.$boton_cancelar.'
                 '.$eyeConsulta.'
                 '.$terminar.$boton_truncar.'
                 '.$zipSinfixAnterior.'
                 '.$boton_motivo.'
                 '.$botonZipEvidencia.'
                 '.$botonParalizacion.'
                 '.$botonLicencia.'
                 </td>
                 </tr>';*/
	        }
	        /***************/
            //log_message('error', 'C_pre_liquidacion.btnFormulario --> '.$btnFormulario);
            //log_message('error', 'C_pre_liquidacion.btnUM --> '.$btnUM);
            
            
            $data['htmlTabla'] = $this->makeHtmlTablaLiquidacion($itemplan);
            
            
            $this->load->view('vf_pqt_gestion_obra_pre_liquidado/v_form_pre_liquidado',$data);
            
        	   /*
        	   if($result['hasPermiso'] == true){
        	       $this->load->view('vf_pqt_gestion_obra_pre_diseno/v_pre_diseno',$data);
        	   }else{
        	       $data['modulo']  =  "Pre Diseño";
        	       $this->load->view('v_permiso_denegado.php',$data);
	           }*/
	   }else{
	       log_message('error', '-->C_pre_diseno Usuario Error');
	       redirect('login','refresh');
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
    
    function makeHtmlTablaLiquidacion1($itemPlan = null){
        $pendiente=$this->M_pqt_pre_liquidacion->getTrabajosEnSiom($itemPlan);
        $html = '<table class="table display  pb-30 table-striped table-bordered nowrap dataTable no-footer">
                    <thead>
                    <tr>
                    <th colspan="3">SIOM</th>
                    <th colspan="3">PLAN DE OBRA</th>
                    </tr>
                    <tr>
                    <th>ESTACION</th>
                    <th>OS</th>
                    <th>ESTADO</th>
                    <th>ACCION</th>
                    <th>% AVANCE</th>
                    <th>ESTADO IP</th>
                    </tr>
                    </thead>
                    <tbody>';

        $html_tmp = '';
        $osArray = '';
        $osestadoArray = '';
        $iTodoValidando = 0;
        $idEstacionTmp = 0;
        $yaTieneFormulario = 0;
        $btnFormulario='';
        foreach($pendiente->result() as $row){
            if($row->idEstacion == $idEstacionTmp){
                // es repetido
                if($iTodoValidando == 1){
                    if($row->ultimo_estado == 'VALIDANDO'){
                        $iTodoValidando = 1;
                    }else{
                        $iTodoValidando = 0;
                        $btnFormulario = '';
                        $yaTieneFormulario=0;
                    }
                }
                if($iTodoValidando ==  1 && $yaTieneFormulario==0 && ($row->idSubProyecto == ID_SUBPROYECTO_ACELERACION_MOVIL || $row->idProyecto == ID_PROYECTO_SISEGOS)){
                    $btnFormulario = 'FORMULARIO';
                    $yaTieneFormulario = 1;
                }
                
                $html_tmp = '<tr>
                    <td>'.$row->estacion.'</td>
                    <td>'.$osArray.'<br>'.$row->codigoSiom.'</td>
                    <td>'.$osestadoArray.'<br>'.$row->ultimo_estado.'</td>
                    <td>'.($iTodoValidando==1?'EVIDENCIAS':'').' '.$btnFormulario.'</td>
                    <td>0%</td>
                    <td>'.$row->estadoPlanDesc.'</td>
                    </tr>';
                
                $osArray .= '<br>'.$row->codigoSiom;
                $osestadoArray .= '<br>'.$row->ultimo_estado;
            }else{
                $html .=$html_tmp;
                //es nuevo
                $iTodoValidando = 0;
                $osArray = '';
                $osestadoArray = '';
                $idEstacionTmp = $row->idEstacion;
                $btnFormulario = '';
                
                if($row->ultimo_estado == 'VALIDANDO'){
                    $iTodoValidando = 1;
                }else{
                    $iTodoValidando = 0;
                }
                if($iTodoValidando ==  1 && $yaTieneFormulario==0 && ($row->idSubProyecto == ID_SUBPROYECTO_ACELERACION_MOVIL || $row->idProyecto == ID_PROYECTO_SISEGOS)){
                    $btnFormulario = 'FORMULARIO';
                    $yaTieneFormulario = 1;
                }
                
                $html_tmp = '<tr>
                    <td>'.$row->estacion.'</td>
                    <td>'.$row->codigoSiom.'</td>
                    <td>'.$row->ultimo_estado.'</td>
                    <td>'.($iTodoValidando==1?'EVIDENCIAS':'').' '.$btnFormulario.'</td>
                    <td>0%</td>
                    <td>'.$row->estadoPlanDesc.'</td>
                    </tr>';
                
                $osArray .= $row->codigoSiom;
                $osestadoArray .= $row->ultimo_estado;
            }
        }
        
        //cerrar el ultimo
        $html .=$html_tmp;
        
        $html .= '</tbody>
        </table>';
        return $html;
    }
    
    function makeHtmlTablaLiquidacion($itemPlan = null){
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
                    }
                    if($row->countSwitchObPublicas == 1 && $row->countFormObrap == 0 && $row->total >= 1 && ($row->validando == $row->total)) {
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
                            $btnFormulario = 'EVIDENCIA CARGADA';
                        }
                    }
                }
            }else if($row->subioEvidencias > 0){
                $btnFormulario = 'EVIDENCIA CARGADA';
                $porcentaje = '100%';
            }else{
                $btnFormulario = 'SUBIR_EVIDENCIAS_PDTE';
            }
            
            $html .= '<tr>
                    <td>'.$row->estacion.'</td>
                    <td>'.$tmpcodigosiom.'</td>
                    <td>'.$tmpcodigosiomestado.'</td>
                    <td>'.$btnFormulario.'</td>
                    <td>'.$porcentaje.'</td>
                    <td>'.$row->estadoPlanDesc.'</td>
                    </tr>';
        }
        
        $html .= '</tbody>
        </table>';
        return $html;
    }
    
    function registrarEvidencias() {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try {
    
            $itemplan = $this->input->post('itemplan');
            $idEstacion = $this->input->post('idEstacion');
            $descEstacion = $this->input->post('descEstacion');
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
    
            $dataFormularioEvidencias = array(
                'itemplan'          =>  $itemplan,
                'fecha_registro'    => $this->fechaActual(),
                'usuario_registro'  => $this->session->userdata('idPersonaSession'),
                'idEstacion'        => $idEstacion,
                'path_pdf_pruebas'  =>  $uploadfile1,
                'path_pdf_perfil'   =>  $uploadfile2
            );
            
            $data = $this->M_pqt_pre_liquidacion->registrarEvidencias($dataFormularioEvidencias);
            
            //PRE LIQUIDAR EL ITEMPLAN
            $this->preliquidar($itemplan);
        }catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function registrarFormObraPub() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            log_message('error', 'C_pre_liquidacion.registrarFormObraPub');
            $arrayJson = json_decode($this->input->post('jsonFormObrasP'));
            //$arrayJson = $this->input->post('jsonFormObrasP'); $jsonDataForm->itemplan
            $arrayJson->usuario_registro = $this->session->userdata('idPersonaSession');
            $arrayJson->fecha_registro   = $this->fechaActual();
    
            $flg = $this->M_porcentaje->insertFormObraP($arrayJson);
            log_message('error', 'C_pre_liquidacion.registrarFormObraPub.insertFormObraP...$flg='.$flg);
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
                    
                    $this->M_pqt_pre_liquidacion->registrarEvidencias($dataFormularioEvidencias);
                } else {
                    throw new Exception('NDP');
                }
            } else {
                throw new Exception('NDP');
            }
            //PRE LIQUIDAR EL ITEMPLAN
            $this->preliquidar($itemplan);
        } catch(Exception $e) {
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
        // if(!in_array($zonal,array(8,9,10,11,12))||$this->session->userdata('zonasSession')) {
        //     if($test<=4) { $css='style="min-height:496px"';}
        // } else {
        //     $css='style="min-height:496px"';
        // }
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
    
                    $url = 'https://gicsapps.com:8080/obras2/recibir_eje.php';
                    $this->enviarTrama($itemPlan, $indicador, 2, $jefatura ,$descEmpresaColab, $url);
                }
    
                if($countSwitchForm == 1 && $countTrama == 0 && $idEstacion == ID_ESTACION_UM && $idProyecto == 3) {
    
                    $url = 'https://www.gicsapps.com:8080/obras2/recibir_ejeUm.php';
    
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
    
    function saveSisegoPlanObra(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        try{
            log_message('error', "Ingresa a C_pre_liquidacion.saveSisegoPlanObra");
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
                        $porcentaje = ($cant->porcentaje >= 90) ? 100 :  $cant->porcentaje + 10;
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
            //PRE LIQUIDAR EL ITEMPLAN
            $this->preliquidar($itemplan);
        }catch(Exception $e){
            log_message('error', $e->getMessage());
            $data = null;
            $data['error']    = EXIT_ERROR;
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
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
    
    function registrarFicha($itemPlan, $observacion, $idEstacion, $idFichaTecnicaBase, $arrayJson) {
        $idCuadrilla = $this->M_porcentaje->getCuadrillaOne($itemPlan, $idEstacion);
        $coordenada  = $this->M_porcentaje->getCoordenadas($itemPlan);
        $dataInsert = array(
            'jefe_c_nombre'         => $idCuadrilla,
            'observacion'           => $observacion,
            'itemplan'              => $itemPlan,
            'fecha_registro'        => date("Y-m-d H:m:s"),
            'usuario_registro'      =>  $this->session->userdata('idPersonaSession'),
            'coordenada_x'          => $coordenada['coordX'],
            'coordenada_y'          => $coordenada['coordY'],
            'flg_activo'            => '1',
            'id_ficha_tecnica_base' => $idFichaTecnicaBase,
            'id_estacion'           => $idEstacion
        );
    
        $val = $this->M_porcentaje->isertFichaTecnica($dataInsert, $arrayJson);
        return $val;
    }
    
    function enviarTrama($itemPlan, $indicador, $from, $jefatura ,$descEmpresaColab){
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
    
        $url = 'https://172.30.5.10:8080/obras2/recibir_eje.php';
    
        //$response = $this->m_utils->sendDataToURL($url, $dataSend);
        //if($response['error'] == EXIT_SUCCESS){
        $this->m_utils->saveLogSigoplus('LIQUIDACION OBRA', null , $itemPlan, null, $indicador, $descEmpresaColab, $jefatura, 'TRAMA COMPLETADA', 'OPERACION REALIZADA CON EXITO', 1, 4);
        //}else{
        // $this->m_utils->saveLogSigoplus('SINFIX', null, $itemPlan, null, $indicador, $descEmpresaColab, $jefatura, 'FALLA EN LA RESPUESTA DEL HOSTING', 'OPERACION NO COMPLETADA ERROR EN EL SERVIDOR DEL CLIENTE:'. strtoupper($response->mensaje), '2');
        //}
        //$data['tablaAsigGrafo'] = $this->makeHTLMTablaAsignarGrafo($this->m_liquidacion->getPtrToLiquidacion($SubProy,$eecc,$zonal,$itemPlan,$mesEjec,$area,$estado,FROM_BANDEJA_APROBACION,$ano));
    
        // }catch(Exception $e){
        //     $data['msj'] = $e->getMessage();
        // }
        // echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function preliquidar($itemplan){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        //DATOS DEL ITEMPLAN
        $datosItemPlan = $this->M_pqt_pre_liquidacion->getDatosItemPlan($itemplan);
        
        //DATOS DE ITEMPLAN - ESTACIONES
        $pendiente=$this->M_pqt_pre_liquidacion->getEstacionesEnSiomXItemPlan($itemplan);
        
        $permitirPreLiquidar = 0;
        $enviarTramaSisegoWeb = 0;
        
        if($datosItemPlan['idProyecto'] == ID_PROYECTO_MOVILES){
            //LIQUIDACION FORMA 1
            //1. Para los subproyectos de Moviles pasara a estado Pre-liquidado cuando la estación FO + UM esten al 100%.
            foreach($pendiente->result() as $row){
                if($row->idEstacion == ID_ESTACION_FO || $row->idEstacion == ID_ESTACION_UM){
                    if($row->total>0 && ($row->validando == $row->total) && $row->subioEvidencias>=1){
                        $permitirPreLiquidar = 1;
                    }else{
                        $permitirPreLiquidar = 0;
                    }
                }
            }
        }else if($datosItemPlan['idProyecto'] == ID_PROYECTO_HFC){
            //LIQUIDACION FORMA 2
            //2. Para los proyectos HFC que requieran obra en Coaxial y FO debe estar las dos estaciones al 100%
            //para que el itemplan pase a estado Pre-liquidado.
            foreach($pendiente->result() as $row){
                if($row->idEstacion == ID_ESTACION_FO || $row->idEstacion == ID_ESTACION_COAXIAL){
                    if($row->total>0 && ($row->validando == $row->total) && $row->subioEvidencias>=1){
                        $permitirPreLiquidar = 1;
                    }else{
                        $permitirPreLiquidar = 0;
                    }
                }
            }
            
        }else if($datosItemPlan['has_estacion_fo'] == 0 && $datosItemPlan['has_estacion_coax'] == 0){
            //ir a tabla subproyecto estacion para buscar las estaciones
            // PRE LIQUIDACION DE ITEMPLAN SIN ESTACION ANCLA
            foreach($pendiente->result() as $row){
                if($row->total>0 && ($row->validando == $row->total) && $row->subioEvidencias>=1){
                    $permitirPreLiquidar = 1;
                }else{
                    $permitirPreLiquidar = 0;
                }
                
                if($datosItemPlan['idProyecto'] == ID_PROYECTO_SISEGOS){
                    //LIQUIDACION FORMA 4
                    //4. La liquidación a Sisego Web (envio de trama de liquidación) se enviara cuando la estación FO se encuentre a 100%
                    //y el Itemplan en Pre-liquidado."
                
                    if($row->idEstacion == ID_ESTACION_FO && $permitirPreLiquidar == 1){
                        $enviarTramaSisegoWeb = 1;
                    }else{
                        $enviarTramaSisegoWeb = 0;
                    }
                }
            }
            
        }else{
            //LIQUIDACION FORMA 3
            //3. El Itemplan pasara a estado Pre-liquidado cuando la estación ancla este al 100%;
            //esto aplica a todos los proyectos menos Moviles y HFC
            foreach($pendiente->result() as $row){
                if($row->idEstacion == ID_ESTACION_FO || $row->idEstacion == ID_ESTACION_COAXIAL){
                    if($row->total>0 && ($row->validando == $row->total) && $row->subioEvidencias>=1 && $permitirPreLiquidar == 0){
                        $permitirPreLiquidar = 1;
                    }else{
                        $permitirPreLiquidar = 0;
                    }
                }
                
                if($datosItemPlan['idProyecto'] == ID_PROYECTO_SISEGOS){
                    //LIQUIDACION FORMA 4
                    //4. La liquidación a Sisego Web (envio de trama de liquidación) se enviara cuando la estación FO se encuentre a 100%
                    //y el Itemplan en Pre-liquidado."
                
                    if($row->idEstacion == ID_ESTACION_FO && $permitirPreLiquidar == 1){
                        $enviarTramaSisegoWeb = 1;
                    }else{
                        $enviarTramaSisegoWeb = 0;
                    }
                }
            }
        }
        
        if($permitirPreLiquidar == 1) {
            $arrayUpdate = array(
                "idEstadoPlan" => ID_ESTADO_PRE_LIQUIDADO,
                "usu_upd" => $this->session->userdata('idPersonaSession'),
                "fecha_upd" => $this->fechaActual()
            );
            $data = $this->M_pqt_pre_liquidacion->updateEstadoPlanObraToPreLiquidado($itemplan, $arrayUpdate);
            if($data['error'] == EXIT_ERROR){
                throw new Exception($data['msj']);
            }else{
                if($enviarTramaSisegoWeb == 1){
                    //ENVIAR TRAMA A SISEGO WEB
                }
                
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
        return $data;
    }
}