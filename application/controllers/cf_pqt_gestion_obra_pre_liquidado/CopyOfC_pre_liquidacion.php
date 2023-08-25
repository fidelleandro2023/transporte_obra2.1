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
	        
	        log_message('error', '-->C_pre_liquidacion Usuario OK');
	        
	        $itemplan = (isset($_GET['itemplan']) ? $_GET['itemplan'] : '');
	        
	        log_message('error', '-->C_pre_liquidacion $itemplan '.$itemplan);
	        
	        $data['itemPlan'] = ''.$itemplan.'';
	        $data['idEstacion'] = ''.ID_ESTACION_FO.'';
	        
	        $permisos =  $this->session->userdata('permisosArbol');
	        $result = $this->lib_utils->getHTMLPermisos($permisos, NULL, ID_PERMISO_HIJO_PQT_PRE_DISENO, ID_MODULO_PAQUETIZADO);
	        
	        $pendiente=$this->M_pqt_pendientes->getListaPendiente($itemplan, ID_ESTADO_PRE_LIQUIDADO);
	        /***************************/
	        
	        $data['indicador'] = null;
	        $data['flg_from'] = null;
	        $data['jefatura'] = null;
	        $data['id_estado_plan'] = null;
	        $data['desc_emp_colab'] = null;
	        $data['id_estacion'] = ID_ESTACION_FO;
	        $btnFormulario = '';
	        $btnUM = '';
	    foreach($pendiente->result() as $row){
        
        $countParalizados = $this->m_utils->countParalizados($row->itemPlan, FLG_ACTIVO, ORIGEN_SINFIX);
       
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
                    
                    $data['indicador'] = $indicador;
                    $data['flg_from'] = $flg;
                    $data['jefatura'] = $jefatura;
                    $data['id_estado_plan'] = $idEstadoPlan;
                    $data['desc_emp_colab'] = $empresaColabdesc;
                    $data['id_estacion'] = ID_ESTACION_FO;
                    
                    $this->load->view('vf_pqt_gestion_obra_pre_liquidado/v_bandeja_ejecucion_fuera',$data);
                }
                if($infoItenForm['countSwitchObPublicas'] == 1 && $countFormObrap == 0 && $infoOS['num'] >= 1 && ($infoOS['has_validando'] == $infoOS['num'])) {
                    
                    $data['indicador'] = $indicador;
                    $data['flg_from'] = $flg;
                    $data['jefatura'] = $jefatura;
                    $data['id_estado_plan'] = $idEstadoPlan;
                    $data['desc_emp_colab'] = $empresaColabdesc;
                    $data['id_estacion'] = ID_ESTACION_FO;
                    $this->load->view('vf_pqt_gestion_obra_pre_liquidado/v_form_ob_pub',$data);
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
                        $this->load->view('vf_pqt_gestion_obra_pre_liquidado/v_form_um',$data);
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
     }
	        /***************/
     //test
     //$data['flg_from'] = 2;
     //$this->load->view('vf_pqt_gestion_obra_pre_liquidado/v_bandeja_ejecucion_fuera',$data);
     //$this->load->view('vf_pqt_gestion_obra_pre_liquidado/v_form_ob_pub',$data);
     //$this->load->view('vf_pqt_gestion_obra_pre_liquidado/v_form_um',$data);
            log_message('error', 'C_pre_liquidacion.btnFormulario --> '.$btnFormulario);
            log_message('error', 'C_pre_liquidacion.btnUM --> '.$btnUM);
            
            $html = '';
            $titulo = '';
            foreach($this->M_pqt_pre_liquidacion->getItemsPlan()->result() as $row){
                $titulo = 'ItemPlan: '.$row->itemplan.'<br>Proyecto: '.$row->ProyectoDesc.'<br>SubProyecto: '.$row->subProyectoDesc;
                $html .= '<br><br>'.$titulo.'<br>'.$this->makeHtmlTablaLiquidacion($row->itemplan);
            }
            $data['html'] =$html;
            //$data['html'] = $this->makeHtmlTablaLiquidacion($itemplan);
            
            
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
                    
                    /**Registrar estado Pre Liquidado Gustavo Sedano 2019 09 17**/
                    $arrayData = array('idEstadoPlan'        => ID_ESTADO_PRE_LIQUIDADO,
                        'fechaPreLiquidacion' => $this->fechaActual(),
                        'id_usuario_preliquidacion' => $this->session->userdata('idPersonaSession'),
                        'usu_upd' => (($this->session->userdata('userSession') != null) ? $this->session->userdata('userSession') : 'AUTOMATICO'),
                        'fecha_upd' => $this->fechaActual());
                    $this->M_porcentaje->updateEstadoPlanObra($arrayJson['itemplan'], $arrayData);
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
            /*
            $arrayData = array('idEstadoPlan'        => ID_ESTADO_PRE_LIQUIDADO,
                'fechaPreLiquidacion' => $this->fechaActual(),
                'id_usuario_preliquidacion' => $this->session->userdata('idPersonaSession'),
                'usu_upd' => (($this->session->userdata('userSession') != null) ? $this->session->userdata('userSession') : 'AUTOMATICO'),
                'fecha_upd' => $this->fechaActual());
            $this->M_porcentaje->updateEstadoPlanObra($itemplan, $arrayData);
            */
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
    
    function makeHtmlTablaLiquidacion($itemPlan = null){
        $pendiente=$this->M_pqt_pre_liquidacion->getTrabajosEnSiom($itemPlan);
        $html = '<table border="2px">
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
}