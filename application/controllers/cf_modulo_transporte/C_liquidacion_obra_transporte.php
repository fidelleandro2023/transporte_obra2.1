<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_liquidacion_obra_transporte extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_plan_obra/m_planobra');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_ejecucion/M_generales');
        $this->load->model('mf_ejecucion/M_porcentaje');
        $this->load->model('mf_pqt_ejecucion/M_pqt_pendientes');
        $this->load->model('mf_transporte/m_liquidacion_obra_transporte');
        $this->load->model('mf_servicios/m_integracion_siom');
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
            
	        $data['itemPlan'] = ''.$itemplan.'';
	        $data['idEstacion'] = ''.ID_ESTACION_FO.'';
	        
	        $permisos =  $this->session->userdata('permisosArbolTransporte');
	        $result = $this->lib_utils->getHTMLPermisos($permisos, 54, 314, 2);
	        
	        $data['indicador'] = null;
	        $data['flg_from'] = null;
	        $data['jefatura'] = null;
	        $data['id_estado_plan'] = null;
	        $data['desc_emp_colab'] = null;
	        $data['id_estacion'] = ID_ESTACION_FO;
	        $btnFormulario = '';
	        $btnUM = '';
	        
	        $pendiente=$this->M_pqt_pendientes->getListaPendiente($itemplan, ID_ESTADO_PRE_LIQUIDADO);

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
                
                $terminar = null;
                $obra=$this->M_generales->itemPlanI($row->itemPlan);
                
	            $eyeConsulta       = null;
	            $zipSinfixAnterior = null;
	            $boton_motivo      = null;
	            $color = null;
	            $botonParalizacion = null;
	            $boton_truncar = null;
	            $boton_cancelar = null;
	            
	            $botonPorcentaje   = '<a data-toggle="tooltip" data-trigger="hover" data-placement="top" data-original-title="Porcentaje" data-item_plan="'.$row->itemPlan.'" style="cursor:pointer" onclick="openModalPorcentaje($(this));"><i class="fa fa-book"></i></a>';
	            if($this->session->userdata("eeccSession")==6||$this->session->userdata("eeccSession")==0||$this->session->userdata("idPerfilSession")==10){
	                if($row->idEstadoPlan == ID_ESTADO_TRUNCO) {
	                    $botonZipEvidencia = null;
	                    $botonParalizacion = null;
	                    $botonPorcentaje   = null;
	                    $boton_truncar='<a data-toggle="tooltip" data-trigger="hover" data-placement="top" style="cursor:pointer"  data-original-title="Regresar Obra" data-itemplan="'.$row->itemPlan.'" onclick="regresarTrunco($(this))"><i class="fa fa-retweet"></i></a>';	        
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
	                    $boton_cancelar = ' <a data-toggle="tooltip" data-trigger="hover" data-placement="top" style="cursor:pointer"  data-original-title="Cancelar"
                                                data-id_estadoplan="'.$row->idEstadoPlan.'" data-itemplan="'.$row->itemPlan.'" onclick="openModalAlert($(this))"><i class="fa fa-ban"></i></a>';
	                }
	        
	                if($countParalizados > 0) {
	                    $color = '#FDBDBD';
	                    $botonPorcentaje = null;
	                    $boton_truncar = null;
	                    $boton_cancelar = null;
	                }
                }
                
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
	        }
            $data['htmlTabla'] = $this->makeHtmlTablaLiquidacion(null);
           
            $data['opciones'] = $result['html'];
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_modulo_transporte/v_liquidacion_obra_transporte',$data);
            } else {
                redirect('login', 'refresh');
            }
	   }else{
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
    
    function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone','America/Lima');
        setlocale(LC_TIME, "es_ES","esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }

    function makeHtmlTablaLiquidacion($itemplan){
        $pendiente=$this->m_liquidacion_obra_transporte->getDataPlanobraLiqui($itemplan, array(3,9));
        $html = '<table id="data-table" class="table table-bordered">
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
        foreach($pendiente as $row){
            $btnPorcentraje    = '<a data-toggle="tooltip" data-trigger="hover" data-placement="bottom" data-original-title="Porcentaje" data-item_plan ="'.$row['itemplan'].'"  onclick="openModalPorcentaje($(this));"><i class="zmdi zmdi-hc-2x zmdi zmdi-hourglass-alt"></i></a>';
            $botonZipEvidencia = '<a data-toggle="tooltip" data-trigger="hover" data-placement="bottom" data-original-title="descarga zip de las evidencias" data-item_plan="'.$row['itemplan'].'" style="cursor:pointer" onclick="zipItemPlan($(this));"><i class="zmdi zmdi-hc-2x zmdi-download"></i></a>';
            $porcentaje = '0%';
            $html .= '  <tr>
                            <td>'.$btnPorcentraje.' '.$botonZipEvidencia.'</td>
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
            
            $data = $this->m_liquidacion_obra_transporte->registrarEvidencias($dataFormularioEvidencias);
            
            //PRE LIQUIDAR ESTACION ITEMPLAN
            $this->liquidarEstacion($itemplan, $idEstacion);
            
            //PRE LIQUIDAR EL ITEMPLAN
            $this->preliquidar($itemplan);
        }catch(Exception $e) {
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
                                                    <i class="fa fa-camera"></i><span class="btn-text">Evidencia</span></button>';
            
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
        log_message('Error', 'C_pre_liquidacion.saveSisegoPlanObra ');
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
                                $pendiente = $this->m_liquidacion_obra_transporte->getEvidenciasXEstacionItemPlan($itemplan, $idEstacion);
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
    
                                    $data = $this->m_liquidacion_obra_transporte->registrarEvidencias($dataFormularioEvidencias);
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
        
        $pendiente=$this->m_liquidacion_obra_transporte->getEstacionesEnSiomXItemPlan($itemplan);
        
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
            if($idProyecto == ID_PROYECTO_SISEGOS || $idProyecto == ID_PROYECTO_MOVILES){
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
            if($idProyecto == ID_PROYECTO_SISEGOS || $idProyecto == ID_PROYECTO_MOVILES){
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
            
            $existeRegistro = $this->m_liquidacion_obra_transporte->countEstacionAvanceByItemplanEstacion($itemplan, $idEstacion);
            if($existeRegistro['count']==0){
                //REGISTRAR
                $this->m_liquidacion_obra_transporte->insertItemPlanEstacionAvance($dataInsert);
            }else{
                //ACTUALIZAR
                $this->m_liquidacion_obra_transporte->updateItemPlanEstacionAvance($itemplan, $idEstacion, $dataInsert);
            }
            
            $this->liquidarPoMatByItemplanEstacion($itemplan, $idEstacion);
        }
        
    }
    
    
    public function preliquidar($itemplan){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        log_message('Error', 'C_pre_liquidacion.preliquidar ');
        #EVALUACION PARA PASE A PRELIQUIDACION SISEGOS
        //DATA A RECORRER
        //obtEstacionesParaLiquidar
        $listDatosEstacionItemPlan=$this->m_liquidacion_obra_transporte->obtEstacionesParaLiquidar($itemplan);
        
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
        
        foreach($listDatosEstacionItemPlan->result() as $row){
            $estadoItemPlan = $row->idEstadoPlan;
            $idProyecto = $row->idproyecto;
            $idSubProyecto = $row->idSubProyecto;
            $idTipoSubProyecto = $row->idTipoSubProyecto;
            
            if($row->idEstacion == ID_ESTACION_FO){
                //TIENE ALMENOS OS EN ESTADO (CREADA, ASIGNADA, EJECUTANDO, VALIDANDO, APROBADA)
                if($row->total > 0){
                    $has_estacionAnclaFO = '1';
                }
                //TIENE OS CULMINADA PARA LA ESTACION FO
                if($row->total>0 && $row->total == $row->validado && $row->pct_avance == 100 && $row->subioEvidencias > 0){
                    $estacionAnclaFO_culminada = 1;
                }
            }
            if($row->idEstacion == ID_ESTACION_COAXIAL){
                //TIENE ALMENOS OS EN ESTADO (CREADA, ASIGNADA, EJECUTANDO, VALIDANDO, APROBADA)
                if($row->total > 0){
                    $has_estacionAnclaCOAX = '1';
                }
                //TIENE OS CULMINADA PARA LA ESTACION COAXIAL
                if($row->total>0 && $row->total == $row->validado && $row->pct_avance == 100 && $row->subioEvidencias > 0){
                    $estacionAnclaCOAX_culminada = 1;
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
        }
        
        $liquidarObra = false;
        if($estadoItemPlan    ==  ID_ESTADO_PLAN_EN_OBRA){
            if($idProyecto == ID_PROYECTO_SISEGOS){
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
            }else if($idProyecto == ID_PROYECTO_CRECIMIENTO_VERTICAL){
                if($idTipoSubProyecto    ==  TIPO_SUBPROYECTO_BUCLE){
                    /**criterios de liquidacion de la obra
                    * COAXIAL AL 100% Y NO TIENE OS SIOM IGUAL (CREADA, ASIGNADA, EJECUTANDO, VALIDANDO, APROBADA) EN FO ||
                    * FO AL 100% Y Y NO TIENE OS SIOM IGUAL (CREADA, ASIGNADA, EJECUTANDO, VALIDANDO, APROBADA) EN COAXIAL 
                    * FO AL 100% Y COAXIAL AL 100%
                    * **/
                    if($estacionAnclaFO_culminada == 1 && $has_estacionAnclaCOAX == ''){
                        $liquidarObra = true;
                    }
                    
                    if($estacionAnclaCOAX_culminada == 1 && $has_estacionAnclaFO == ''){
                        $liquidarObra = true;
                    }
                    
                    if($estacionAnclaFO_culminada == 1 && $estacionAnclaCOAX_culminada == 1 && $has_estacionAnclaCOAX == '1' && $has_estacionAnclaFO == '1'){
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
                if($estacionAnclaFO_culminada == 1 || $estacionAnclaCOAX_culminada == 1){
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
                "fecha_upd" => $this->fechaActual()
            );
            $data = $this->m_liquidacion_obra_transporte->updateEstadoPlanObraToPreLiquidado($itemplan, $arrayUpdate);
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
            $listaPoMat = $this->m_integracion_siom->getMatPoListByitemplanEstacion($itemplan, $idEstacion);
            $updateDataPo = array();
            $insertDataLog = array();
            foreach($listaPoMat as $row){
                $dataUp = array('estado_po' =>  PO_LIQUIDADO,
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
    
    function zipItemPlanTransp() {
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

    function filtrarTablaLiquiTransp() {
        $itemplan = $this->input->post('itemplan');
        $data['htmlTabla'] = $this->makeHtmlTablaLiquidacion($itemplan);
        echo json_encode(array_map('utf8_encode', $data));
    }
}