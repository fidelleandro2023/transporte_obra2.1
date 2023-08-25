<?php
defined('BASEPATH') or exit('No direct script access allowed');
class C_bandeja_licencias extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_ejecucion/M_generales');
        $this->load->model('mf_licencias/M_licencias');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_detalle_obra/m_detalle_obra');
        $this->load->model('mf_pqt_pre_liquidacion/m_pqt_pre_liquidacion');
        $this->load->model('mf_liquidacion/m_liquidacion');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index()
    {
        try {
            $logedUser = $this->session->userdata('usernameSession');
            if ($logedUser != null) {
            
                //$itemplan = $this->input->post('itemplan');
                $itemplan = (isset($_GET['itemplan']) ? $_GET['itemplan'] : '');
            
                $data['tabla'] = $this->makeHTLMTablaConsulta($this->M_licencias->getBandejaItemPlanEstacionPqt($itemplan));
            
            
                $data["extra"] = ' <link rel="stylesheet" href="' . base_url() . 'public/bower_components/notify/pnotify.custom.min.css">
            <link rel="stylesheet" href="' . base_url() . 'public/bower_components/bootstrap-validator/bootstrapValidator.min.css"></link>
            <link href="' . base_url() . 'public/vendors/bower_components/datatables/media/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/><link rel="stylesheet" href="' . base_url() . 'public/fancy/source/jquery.fancybox.css" type="text/css" media="screen">
            <link rel="stylesheet" href="' . base_url() . 'public/css/jasny-bootstrap.min.css">';
                $data["pagina"] = "regLicencias";
            
                $permisos =  $this->session->userdata('permisosArbol');
                $result = $this->lib_utils->getHTMLPermisos($permisos, NULL, ID_PERMISO_HIJO_PQT_EN_LICENCIA, ID_MODULO_PAQUETIZADO);
            
                if($result['hasPermiso'] == true){
                    $this->load->view('vf_pqt_gestion_obra_en_licencia/v_licencias', $data);
                    $this->load->view('recursos_sinfix/js_bandeja_licencias_pqt', $data);
                    $this->load->view('recursos_sinfix/fancy', $data);
                }else{
                    $data['modulo']  =  "En Licencia";
                    $this->load->view('v_permiso_denegado.php',$data);
                }
            
            } else {
                redirect('login', 'refresh');
            }
        } catch (Exception $e) {
            log_message('error', '-->C_bandeja_licencias index : ' . $e->getMessage());
        }
    }

    
    public function makeHTLMTablaConsulta($listaItemPlan)
    {

        $html = '
                <table class="table table-bordered">
                    <thead>
                        <tr class="thead-default">
                            <th></th>
                            <th>ITEMPLAN</th>
                            <th>ESTACION</th>
                            <th># ENT. CONCLU. </th>
                            <th># TOTAL. ENT. </th>
                            <th>INDICADOR</th>
                            <th>SUB PROY</th>
                            <th>ZONAL</th>
                            <th>EECC</th>
                            <th>FEC. PREVISTA</th>
                            <th>ESTADO PLAN</th>
                        </tr>
                    </thead>

                    <tbody>';
        if ($listaItemPlan != null) {
            log_message('error', '$listaItemPlan no es null ');
            foreach ($listaItemPlan as $row) {

                $html .= '
                        <tr>
                            <td style="text-align:center">
                                <a style="cursor:pointer; color: var(--verde_telefonica)" data-itemplan="' . $row->itemPlan . '" data-idestacion="' . $row->idEstacion . '" data-flgprovincia="' . $row->flg_provincia . '" onclick="mostrarDetalle(this)"><i class="zmdi zmdi-hc-2x zmdi-eye"></i></a>
                            </td>
                            <td>' . $row->itemPlan . '</td>
                            <td>' . $row->estacionDesc . '</td>
                            <td style="text-align:center">' . $row->cant_ent_concluida . '</td>
                            <td style="text-align:center">' . $row->total_entidades . '</td>
                            <td>' . $row->indicador . '</td>
                            <td>' . $row->subProyectoDesc . '</td>
                            <td>' . $row->zonalDesc . '</td>
                            <td>' . $row->empresaColabDesc . '</td>
                            <td style="text-align:center">' . $row->fechaPrevEjec . '</td>
                            <td style="text-align:center">' . $row->estadoPlanDesc . '</td>
                        </tr>';
            }
            $html .= '</tbody>
                </table>';

        } else {
            log_message('error', '$listaItemPlan es null ');
            $html .= '</tbody>
                </table>';
        }

        return utf8_decode($html);
    }

    public function getInfoEntidades()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $itemPlan = $this->input->post('itemPlan') ? $this->input->post('itemPlan') : null;
            $idEstacion = $this->input->post('idEstacion') ? $this->input->post('idEstacion') : null;
            //$flgProvincia = $this->input->post('flgProvincia') ? $this->input->post('flgProvincia') : null;

            if ($itemPlan == null || $idEstacion == null) {
                throw new Exception('Hubo un error al traer entidades!!');
            }
            // log_message('error',' $flgProvincia: '. $flgProvincia);
            $permitirAgregarEntidades = '0';
            $permisosPorEstacionAncla = $this->permitirRegistrarEntidades($itemPlan);
            if($idEstacion == ID_ESTACION_COAXIAL){
                if($permisosPorEstacionAncla["permitirCO"] == 1){
                    $permitirAgregarEntidades = '1';
                }else{
                    $permitirAgregarEntidades = '0';
                }
            }else if($idEstacion == ID_ESTACION_FO){
                if($permisosPorEstacionAncla["permitirFO"] == 1){
                    $permitirAgregarEntidades = '1';
                }else{
                    $permitirAgregarEntidades = '0';
                }
            }

            $listaItemPlanDetalle = $this->makeHTLMTablaIPEstaEnt($this->M_licencias->getItemPLanEstacionLincenciaDet($itemPlan, $idEstacion));

            $data['permitirAgregarEntidades'] = $permitirAgregarEntidades;
            $data['tablaEntidades'] = $listaItemPlanDetalle;
            $data['error'] = EXIT_SUCCESS;

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

    public function makeHTLMTablaIPEstaEnt($listaEstacionEntidad)
    {

        // if ($flgProvincia == 1) {
        $html = '
                <table id="tabla_entidades" class="table table-hover display  pb-30 table-striped table-bordered nowrap">
                    <thead>
                        <tr class="table-primary">
                            <th style="text-align: center;"></th>
                            <th>ENTIDAD</th>
                            <th style="text-align: center">EXPEDIENTE</th>
                            <th style="text-align: center">TIPO</th>
                            <th>SUBIR/VER EVIDENCIA</th>
                            <th style="text-align: center">DISTRITO</th>
                            <th style="text-align: center">FEC. INICIO</th>
                            <th style="text-align: center">FEC. FIN</th>
                            <th>ACCI&Oacute;N</th>
                        </tr>
                    </thead>

                    <tbody>';
        $count = 1;
        $btnSubiEvi = '';
        $btnGuardar = '';
        $btnComprobante = '';
        $selectHasCompro = '';
        $listaDistritos = $this->M_licencias->getAllDistritos();

        if ($listaEstacionEntidad != null) {
            foreach ($listaEstacionEntidad as $row) {

                $htmlCmbTipoLic = $this->makeCmbTipoLic($row->iditemplan_estacion_licencia_det, $row->flg_validado, $row->flg_tipo);
                $htmlCmbDistrito = $this->makeCmbDistrito($row->iditemplan_estacion_licencia_det, $row->flg_validado, $row->idDistrito, $row->flg_combo, $listaDistritos);
                
                //comentado el 23.07.2019 por czavalacas, pedido de owen si ya esta validado no permitir modificar.
                //if ($row->flg_validado == 2 && $row->flg_tipo != 1 && $row->ruta_pdf != null) {
                if (($row->flg_validado == 2 || $row->flg_validado == 3) && $row->ruta_pdf != null) {
                    $btnSubiEvi = '<a style="color:#9c9c63"><i class="zmdi zmdi-hc-2x zmdi-upload"></i></a>';
                    $btnGuardar = '<a style="color:#9c9c63"><i class="zmdi zmdi-hc-2x zmdi-floppy"></i></a>';
                } else {
                    $btnSubiEvi = '<a id="btnSaveIpEstDet" style="color: var(--verde_telefonica); cursor: pointer" data-idipestlic="' . $row->iditemplan_estacion_licencia_det . '"  onclick="abrirModalEvidencia(this,1,null,null)"><i class="zmdi zmdi-hc-2x zmdi-upload"></i></a>';
                    $btnGuardar = ' <a id="btnSaveIpEstDet" style="color: var(--verde_telefonica); cursor: pointer" data-idipestlic="' . $row->iditemplan_estacion_licencia_det . '" onclick="liquidarDetalle(this,' . $count . ')"><i class="zmdi zmdi-hc-2x zmdi-floppy"></i></a>';
                }
                
                if ($row->flg_tipo != 1 && $row->flg_tipo != 3) {
                    if($row->flg_tipo   ==  4){//SI ES  CALA
                        if($row->has_compro_cala == 1){//SOLO SI TIENE COMPROBANTE
                            $btnComprobante = '<div id="hasCompro'.$row->iditemplan_estacion_licencia_det.'"></div><a  id="btnComprobante' . $row->iditemplan_estacion_licencia_det . '" style="color: var(--verde_telefonica); cursor: pointer" data-idipestlic="' . $row->iditemplan_estacion_licencia_det . '" onclick="abrirModalComprobantes(this)"><i class="zmdi zmdi-hc-2x zmdi-money"></i></a>';
                        }
                    }else{//NO SON CALA
                        $btnComprobante = '<div id="hasCompro'.$row->iditemplan_estacion_licencia_det.'"></div><a  id="btnComprobante' . $row->iditemplan_estacion_licencia_det . '" style="color: var(--verde_telefonica); cursor: pointer" data-idipestlic="' . $row->iditemplan_estacion_licencia_det . '" onclick="abrirModalComprobantes(this)"><i class="zmdi zmdi-hc-2x zmdi-money"></i></a>';
                    }
                }

                $html .= '
                        <tr>
                            <td style="text-align:center">
                                ' . $btnComprobante .$selectHasCompro. '
                            </td>
                            <td style="font-weight: bold;">' . $row->desc_entidad . '</td>
                            <td>
                                <input type="text" style="width: 80px" id="txtCodExp' . $row->iditemplan_estacion_licencia_det . '" maxlength="20" value="' . ($row->codigo_expediente ? $row->codigo_expediente : null) . '"   ' . (($row->flg_validado == 2||$row->flg_validado == 3) ? 'disabled' : '') . '>
                            </td>
                            <td>
                                ' . $htmlCmbTipoLic . '
                            </td>
                            <td style="text-align: center">
                                <div class="row">
                                    <div class="col-sm-6 col-md-5">
                                       ' . $btnSubiEvi . '
                                    </div>
                                    <div class="col-sm-6 col-md-5">
                                        <a style="color:var(--verde_telefonica);cursor:pointer" id="btnVerEviEnt' . $row->iditemplan_estacion_licencia_det . '" data-idipestlic="' . $row->iditemplan_estacion_licencia_det . '"  data-index="' . $count . '" onclick="descargarPDFEntidad(this,2,0)"><i class="zmdi zmdi-hc-2x zmdi-collection-pdf"></i></a>
                                    </div>
                                </div>
                            </td>
                            <td>
                                ' . $htmlCmbDistrito . '
                            </td>
                            <td>
                                <input type="date" style="width:120px" id="txtFechaIni' . $row->iditemplan_estacion_licencia_det . '"   value="' . ($row->fecha_inicio ? $row->fecha_inicio : null) . '" ' . (($row->flg_validado == 2||$row->flg_validado == 3) ? 'disabled' : '') . '>
                            </td>
                            <td>
                                <input type="date" style="width:120px" id="txtFechaFin' . $row->iditemplan_estacion_licencia_det . '"   value="' . ($row->fecha_fin ? $row->fecha_fin : null) . '" ' . (($row->flg_validado == 2||$row->flg_validado == 3) ? 'disabled' : '') . '>
                            </td>
                            <td style="text-align:center">
                                <div class="row">
                                    <div class="col-sm-6 col-md-5">
                                        ' . $btnGuardar . '
                                    </div>
                                    <div class="col-sm-6 col-md-5">
                                        <a id="btnDeleteEnt" style="color: var(--verde_telefonica); cursor: pointer" data-idipestlic="' . $row->iditemplan_estacion_licencia_det . '"  onclick="deleteIPEstDetLic(this)"><i class="zmdi zmdi-hc-2x zmdi-delete"></i></a
                                    </div>
                                </div>
                            </td>
                        </tr>';

                $btnComprobante = '';

                $count++;
            }
            $html .= '</tbody>
                </table>';

        } else {
            $html .= '</tbody>
                </table>';
        }
        return utf8_decode($html);
    }

    public function makeCmbTipoLic($count, $flg_validado, $flg_tipo)
    {
		$html = null;
        $selectedComu = ($flg_tipo == 1) ? 'selected' : null;
        $selectedLic = ($flg_tipo == 2) ? 'selected' : null;
        $selectedCal = ($flg_tipo == 4) ? 'selected' : null;
        $selectedTip = ($flg_tipo == 0 || $flg_tipo == null) ? 'selected' : null;
		$dataArrayTipo = $this->m_utils->getTipoEntidad(1);
        $html .= '   <select id="tipoLic' . $count . '"  ' . (($flg_validado == 2 || $flg_validado == 3) ? 'disabled' : '') . '  onchange="desactivaBtnCompro(' . $count . ')">
						<option value="0" ' . $selectedTip . '  >Seleccionar Tipo</option>';
						
		foreach($dataArrayTipo as $row) {
			$selected = ($flg_tipo == $row['id_tipo_entidad']) ? 'selected' : null;
			$html .= '<option value="'.$row['id_tipo_entidad'].'" ' . $selected . ' >'.$row['nombre'].'</option>';
		}
                        
          $html .= '</select>';

        return utf8_decode($html);
    }

    public function makeCmbDistrito($count, $flg_validado, $idDistrito, $flg_combo, $listaDistritos)
    {
        $html = '';

        if ($flg_combo == 1) {

            $html .= '<select style="width:120px"  id="distEnt' . $count . '"  ' . (($flg_validado == 2 || $flg_validado == 3) ? 'disabled' : '') . '>
                        <option value="">Seleccionar Disitrito</option>';

            foreach ($listaDistritos as $row) {
                $selected = ($row->idDistrito == $idDistrito) ? 'selected' : null;
                $html .= '<option value="' . $row->idDistrito . '" ' . $selected . ' >' . $row->distritoDesc . '</option>';
            }

            $html .= '</select>';
        }

        return utf8_decode($html);
    }

    public function getEntidadesLicencia()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        try {
            $itemPlan = $this->input->post('itemplan') ? $this->input->post('itemplan') : null;
            $idEstacion = $this->input->post('idEstacion') ? $this->input->post('idEstacion') : null;

            if ($itemPlan == null || $idEstacion == null) {
                throw new Exception('Hubo unn error al traer los datos!!');
            }
            $dataEntidades = $this->makeFORMEntidades($this->M_licencias->getAllEntidades());
            $data['htmlEntidades'] = $dataEntidades['html'];
            if (isset($data['htmlEntidades'])) {
                $data['error'] = EXIT_SUCCESS;
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

    public function makeFORMEntidades($listaEntidades)
    {
        $html = '<option value="">Seleccionar Entidad</option>';

        foreach ($listaEntidades as $row) {
            $html .= '<option value="' . $row->idEntidad . '">' . $row->desc_entidad . '</option>';
        }
        $data['html'] = utf8_decode($html);
        return $data;
    }

    public function registrarEntidades()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        try {
            $itemplan = $this->input->post('itemplan') ? $this->input->post('itemplan') : null;
            $idEstacion = $this->input->post('idEstacion') ? $this->input->post('idEstacion') : null;
            $idPersonaSession = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;
            $idEntidad = $this->input->post('idEntidad') ? $this->input->post('idEntidad') : null;

            if ($idPersonaSession == null) {
                throw new Exception('Su sesion ha expirado, ingrese nuevamente!!');
            }
            if ($itemplan == null || $idEstacion == null || $idEntidad == null) {
                throw new Exception('Hubo un error en traer los datos de registro!!');
            }

            $arrayInsert = array();
            array_push($arrayInsert,
                array('idEntidad' => $idEntidad,
                    'idEstacion' => $idEstacion,
                    'itemPlan' => $itemplan,
                    'ruta_pdf' => null,
                    'fecha_inicio' => null,
                    'fecha_fin' => null,
                    'id_usuario_reg' => $idPersonaSession,
                    'fecha_registro' => date("Y-m-d"),
                    'fecha_valida' => null,
                    'flg_validado' => 0,
                    'id_usuario_valida' => $idPersonaSession,
                )
            );
            $data = $this->M_licencias->registrarEntidadesItemPlanEstaLic($arrayInsert);

            if ($data['error'] == EXIT_SUCCESS) {
                $data['tablaEntLic'] = $this->makeHTLMTablaIPEstaEnt($this->M_licencias->getItemPLanEstacionLincenciaDet($itemplan, $idEstacion));
                
                $permitirAgregarEntidades = '0';
                $permisosPorEstacionAncla = $this->permitirRegistrarEntidades($itemplan);
                if($idEstacion == ID_ESTACION_COAXIAL){
                    if($permisosPorEstacionAncla["permitirCO"] == 1){
                        $permitirAgregarEntidades = '1';
                    }else{
                        $permitirAgregarEntidades = '0';
                    }
                }else if($idEstacion == ID_ESTACION_FO){
                    if($permisosPorEstacionAncla["permitirFO"] == 1){
                        $permitirAgregarEntidades = '1';
                    }else{
                        $permitirAgregarEntidades = '0';
                    }
                }
                $data['permitirAgregarEntidades'] = $permitirAgregarEntidades;
            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

    public function updateIPEstDet()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            
            $iditemplanEstaDet = $this->input->post('iditemplanEstaDet') ? $this->input->post('iditemplanEstaDet') : null;
            $codExpediente = $this->input->post('codExpediente') ? $this->input->post('codExpediente') : null;
            $flgTipoLic = $this->input->post('flgTipoLic') ? $this->input->post('flgTipoLic') : null;
            $distrito = $this->input->post('distrito') ? $this->input->post('distrito') : null;
            $fechaInicio = $this->input->post('fechaInicio') ? $this->input->post('fechaInicio') : null;
            $fechaFin = $this->input->post('fechaFin') ? $this->input->post('fechaFin') : null;

            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;
            
            $reqComprobante = $this->input->post('reqComprobante') ? $this->input->post('reqComprobante') : null;
            /*
            log_message('error', 'iditemplanEstaDet: ' . $iditemplanEstaDet);
            log_message('error', 'codExpediente: ' . $codExpediente);
            log_message('error', 'flgTipoLic: ' . $flgTipoLic);
            log_message('error', 'distrito: ' . $distrito);
            log_message('error', 'fechaInicio: ' . $fechaInicio);
            log_message('error', 'fechaFin: ' . $fechaFin);
            */
            if ($idUsuario == null) {
                throw new Exception('Su sesion ha experiado, ingrese nuevamente!!');
            }
            if ($iditemplanEstaDet == null || $codExpediente == null || $flgTipoLic == null || $fechaInicio == null || $fechaFin == null) {
                throw new Exception('Debe ingresar todos los campos necesarios para guardar!!');
            }
            if ($distrito == 0) {
                $distrito = null;
            }

            $file = $_FILES["file"]["name"];
            $filetype = $_FILES["file"]["type"];
            $filesize = $_FILES["file"]["size"];
            $archivo = $_FILES["file"]["tmp_name"];

            if ($file == null) {
                throw new Exception("Debe subir un archivo pdf para poder guardar!!");
            }

            if (!isset($archivo) || $filesize == 0) {
                throw new Exception("Este archivo est&aacute; da&ntilde;ado, ingrese otro porfavor!!");
            }

            $ubicacion = 'uploads/licencias';
            if (!is_dir($ubicacion)) {
                mkdir('uploads/licencias', 0777);
            }
            $subCarpeta = 'uploads/licencias/evidencia_fotos';
            if (!is_dir($subCarpeta)) {
                mkdir('uploads/licencias/evidencia_fotos', 0777);
            }
            $ubicEvidencia = 'uploads/licencias/evidencia_fotos/itemPlanEstaDet' . $iditemplanEstaDet;
            if (!is_dir($ubicEvidencia)) {
                mkdir('uploads/licencias/evidencia_fotos/itemPlanEstaDet' . $iditemplanEstaDet, 0777);
            } else { //si existe borramos el archivo existente
                $filesExist = scandir($ubicEvidencia); //trae arreglo de archivos existentes en esa carpeta
                $ficherosEliminados = 0;
                foreach ($filesExist as $f) {
                    if (is_file($ubicEvidencia . "/" . $f)) {
                        if (unlink($ubicEvidencia . "/" . $f)) {
                            $ficherosEliminados++;
                        }
                    }
                }
            }

            $file2 = utf8_decode($file);

            if (utf8_decode($file) && move_uploaded_file($archivo, $ubicEvidencia . "/" . $file2)) {

                rename($ubicEvidencia . "/" . $file2, $ubicEvidencia . "/eviIPEstDet_" . $iditemplanEstaDet . ".pdf");
                $flag_validado = 1;//por defecto
                if($flgTipoLic  ==  4){//si es cala valida que tenga uso de comprobante
                    if($reqComprobante  ==  1){//si en 1
                        $flag_validado = 1;
                    }else{//no usa comprobante en 2
                        $flag_validado = 2;
                    }
                }else if($flgTipoLic == 1 || $flgTipoLic == 3){//comunicativa o EIA no necesita comprobante
                    $flag_validado = 2;
                    $reqComprobante = 2;//NO NECESITA COMPROBANTE
                }else{//licencia si necesita comprobante
                    $flag_validado = 1;
                    $reqComprobante = 1;//SI NECESITA COMPROBANTE
                }
                
                $arrayUpdate = array(
                    "codigo_expediente" => $codExpediente,
                    "flg_tipo" => $flgTipoLic,
                    "idDistrito" => $distrito,
                    "fecha_inicio" => $fechaInicio,
                    "fecha_fin" => $fechaFin,
                    "flg_validado" => $flag_validado,
                    "ruta_pdf" => $ubicEvidencia . "/eviIPEstDet_" . $iditemplanEstaDet . ".pdf",
                    "has_compro_cala" => $reqComprobante
                );
                $data = $this->M_licencias->updateItemplanEstaDetLic($iditemplanEstaDet, $arrayUpdate);

                //PREVIAMENTE VALIDA SI TODO SE HA CERRADO PARA PASAR A LA SIGUIENTE FASE
                $this->cerrarFaseEnLicencia($iditemplanEstaDet);

            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function getRutaEvidenciaItemPlanEsta()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $idItemPlanEstaDetalle = $this->input->post('idItemPlanEstaDetalle') ? $this->input->post('idItemPlanEstaDetalle') : null;

            if ($idItemPlanEstaDetalle == null) {
                throw new Exception('Hubo un error al recibir los datos!!');
            }
            $rutaEvidencia = $this->M_licencias->getRutaEvidencia($idItemPlanEstaDetalle, 1, null);
            if (isset($rutaEvidencia)) {
                $data['error'] = EXIT_SUCCESS;
                $data['rutaImagen'] = $rutaEvidencia;
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

    public function getComprobantes()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $idItemPlanEstaDetalle = $this->input->post('idItemPlanEstaDetalle') ? $this->input->post('idItemPlanEstaDetalle') : null;

            if ($idItemPlanEstaDetalle == null) {
                throw new Exception('Hubo un error al recibir los datos!!');
            }
            $flgValidado = $this->M_licencias->getFlgValidadoByIdIPEstDet($idItemPlanEstaDetalle);

            if ($flgValidado != 0 && $flgValidado != null) {
                $listaComprobantes = $this->M_licencias->getComprobantesxItemPlanDet($idItemPlanEstaDetalle);

                $tablaComprobantes = $this->makeHTLMTablaComprobantes($this->M_licencias->getComprobantesxItemPlanDet($idItemPlanEstaDetalle));

                $data['tablaComprobantes'] = $tablaComprobantes;
                $data['error'] = EXIT_SUCCESS;
            } else {
                $data['error'] = EXIT_ERROR;
                $data['msj'] = 'Debe registrar todo los datos de la licencia para poder registrar un comprobante!!';
            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

    public function makeHTLMTablaComprobantes($listaComprobantes)
    {

        $html = '
                <table id="tabla_comprobantes" class="table table-hover display  pb-30 table-striped table-bordered nowrap">
                    <thead>
                        <tr class="table-primary">
                            <th style="text-align: center;"># COMPROBANTE</th>
                            <th style="text-align: center;">FECHA DE EMISI&Oacute;N</th>
                            <th style="text-align: center">MONTO(S/)</th>
                            <th>SUBIR/VER COMPROBANTE</th>
                            <th style="text-align: center">ESTADO</th>
                            <th style="text-align: center">VALIDA COMPROBANTE</th>
                            <th style="text-align: center">PRELIQUI ADMINISTRATIVA</th>
                            <th>ACCI&Oacute;N</th>
                        </tr>
                    </thead>

                    <tbody>';
        $count = 1;
        $btnGuardar = '';

        if ($listaComprobantes != null) {
            foreach ($listaComprobantes as $row) {

                if ($row->estado_valida == 2) {
                    $btnGuardar = '<a style="color:#9c9c63"><i class="zmdi zmdi-hc-2x zmdi-floppy"></i></a>';
                    $btnSubiEvi = '<a style="color:#9c9c63"><i class="zmdi zmdi-hc-2x zmdi-upload"></i></a>';
                } else {
                    $btnGuardar = '  <a id="btnSaveComprobante' . $row->idReembolso . '"  data-idreembolso="' . $row->idReembolso . '" data-rutapdf="' . $row->ruta_foto . '"  style="color: var(--verde_telefonica); cursor: pointer"  onclick="saveComprobante(this,2)"><i class="zmdi zmdi-hc-2x zmdi-floppy"></i></a>';
                    $btnSubiEvi = '<a id="btnSubirEviCompro" style="color: var(--verde_telefonica); cursor: pointer" onclick="abrirModalEvidencia(null,2,1)"><i class="zmdi zmdi-hc-2x zmdi-upload"></i></a>';
                }

                $html .= ' <tr>
                                <td>
                                    <input type="text" id="txtDescCompro' . $row->idReembolso . '"  value="' . ($row->desc_reembolso ? $row->desc_reembolso : null) . '" ' . ($row->estado_valida == 2 ? 'disabled' : '') . '>
                                </td>
                                <td>
                                    <input type="date" id="txtFechaEmiCompro' . $row->idReembolso . '"  value="' . ($row->fecha_emision ? $row->fecha_emision : null) . '" ' . ($row->estado_valida == 2 ? 'disabled' : '') . '>
                                </td>
                                <td>
                                    <input type="number" id="txtMontoCompro' . $row->idReembolso . '"  value="' . ($row->monto ? $row->monto : null) . '" ' . ($row->estado_valida == 2 ? 'disabled' : '') . '>
                                </td>
                                <td style="text-align:center">

                                    <div class="row">
                                        <div class="col-sm-6 col-md-5">
                                            ' . $btnSubiEvi . '
                                        </div>
                                        <div class="col-sm-6 col-md-5">
                                            <a style="color: var(--verde_telefonica);cursor: pointer" id="btnVerEviCompro" data-idreembolso="' . $row->idReembolso . '" onclick="descargarPDFCompro(this,2,0)"><i class="zmdi zmdi-hc-2x zmdi-collection-pdf"></i></a>
                                        </div>
                                    </div>

                                </td>
                                <td>' . ($row->estado_valida == 1 ? 'ATENDIDO' : ($row->estado_valida == 2 ? 'PRELIQUIDADO' : 'PENDIENTE')) . '</td>
                                <td style="text-align:center">
                                    <input type="checkbox" id="chkValidaCompro' . $row->idReembolso . '"  onchange="validaCompro(this,' . $row->idReembolso . ')" ' . ($row->estado_valida == 2 ? 'disabled' : '') . '  style="display:  ' . ($row->flg_preliqui_admin == '1' ? 'none' : 'block') . '"  ' . ($row->flg_valida_evidencia == '1' ? 'checked' : '') . '>
                                </td>
                                <td style="text-align:center">
                                    <input id="chkxPreLiquiAd' . $row->idReembolso . '" type="checkbox"   onchange="preliqAdmin(this,' . $row->idReembolso . ')" ' . ($row->estado_valida == 2 ? 'disabled' : '') . '  style="display:  ' . ($row->flg_valida_evidencia == '1' ? 'none' : 'block') . '" ' . ($row->flg_preliqui_admin == '1' ? 'checked' : '') . '>
                                </td>
                                <td style="text-align:center">
                                   ' . $btnGuardar . '
                                </td>
                           </tr>';

                $count++;
            }

        } else {

            $html .= ' <tr>
                            <td>
                                <input type="text" id="txtDescCompro" >
                            </td>
                            <td>
                                <input type="date" id="txtFechaEmiCompro" >
                            </td>
                            <td>
                                <input type="number" id="txtMontoCompro" >
                            </td>
                            <td style="text-align:center">

                                <div class="row">
                                    <div class="col-sm-6 col-md-5">
                                        <a id="btnSubirEviCompro" style="color: var(--verde_telefonica); cursor: pointer" onclick="abrirModalEvidencia(null,2,1)"><i class="zmdi zmdi-hc-2x zmdi-upload"></i></a>
                                    </div>
                                    <div class="col-sm-6 col-md-5">
                                        <a style="color:#9c9c63;" id="btnVerEviCompro"><i class="zmdi zmdi-hc-2x zmdi-collection-pdf"></i></a>
                                    </div>
                                </div>

                            </td>
                            <td>

                            </td>
                            <td style="text-align:center">
                                <input  type="checkbox" id="chkValidaCompro"  onchange="validaCompro(this,null)">
                            </td>
                            <td style="text-align:center">
                                <input  type="checkbox" id="chkxPreLiquiAd"   onchange="preliqAdmin(this,null)">
                            </td>
                            <td style="text-align:center">
                                <a id="btnSaveComprobante" style="color: var(--verde_telefonica); cursor: pointer"  onclick="saveComprobante(this,1)"><i class="zmdi zmdi-hc-2x zmdi-floppy"></i></a>
                            </td>
                        </tr>';
        }
        $html .= '</tbody>
                </table>';

        return utf8_decode($html);
    }

    public function saveUpdateComprobante()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $iditemplanEstaDet = $this->input->post('iditemplanEstaDet') ? $this->input->post('iditemplanEstaDet') : null;
            $idReembolso = $this->input->post('idReembolso') ? $this->input->post('idReembolso') : null;
            $desc_reembolso = $this->input->post('desc_reembolso') ? $this->input->post('desc_reembolso') : null;
            $fecha_emision = $this->input->post('fecha_emision') ? $this->input->post('fecha_emision') : null;
            $monto = $this->input->post('monto') ? $this->input->post('monto') : null;
            $flgPreliquiAdmin = $this->input->post('flgPreliqui') ? $this->input->post('flgPreliqui') : null;
            $flgValidaCompro = $this->input->post('flgValidaCompro') ? $this->input->post('flgValidaCompro') : null;
            $flgTipoTransacGlob = $this->input->post('flgTipoTransacGlob') ? $this->input->post('flgTipoTransacGlob') : null;
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;

            // log_message('error', 'iditemplanEstaDet:  ' . $iditemplanEstaDet);
            // log_message('error', 'idReembolso:  ' . $idReembolso);
            // log_message('error', 'desc_reembolso:  ' . $desc_reembolso);
            // log_message('error', 'fecha_emision:  ' . $fecha_emision);
            // log_message('error', 'monto:  ' . $monto);
            // log_message('error', 'flgPreliquiAdmin:  ' . $flgPreliquiAdmin);
            // log_message('error', 'flgValidaCompro:  ' . $flgValidaCompro);
            // log_message('error', 'flgTipoTransacGlob:  ' . $flgTipoTransacGlob);

            $this->db->trans_begin();

            if ($idUsuario == null) {
                throw new Exception('Su sesion ha experiado, ingrese nuevamente!!');
            }
            if ($iditemplanEstaDet == null || $desc_reembolso == null || $fecha_emision == null || $monto == null || $flgTipoTransacGlob == null) {
                throw new Exception('Hubo un error al recibir los datos!!');
            }
            if ($flgPreliquiAdmin == null) {
                $flgPreliquiAdmin = '0';
            }
            if ($flgValidaCompro == null) {
                $flgValidaCompro = '0';
            }

            $file = $_FILES["file"]["name"];
            $filetype = $_FILES["file"]["type"];
            $filesize = $_FILES["file"]["size"];
            $archivo = $_FILES["file"]["tmp_name"];

            $estadoValida = null;

            if ($flgPreliquiAdmin == '1' || $flgValidaCompro == '1') {
                $estadoValida = 2;
            } else {
                $estadoValida = 1;
            }

            if ($flgTipoTransacGlob == 1) {
                if ($file == null) {
                    throw new Exception('Debe subir un archivo para poder guardar!!');
                }
                $arrayInsert = array(
                    "desc_reembolso" => $desc_reembolso,
                    "fecha_emision" => $fecha_emision,
                    "monto" => $monto,
                    "fecha_registro" => date("Y-m-d h:m:s"),
                    "estado_valida" => $estadoValida,
                    "iditemplan_estacion_licencia_det" => $iditemplanEstaDet,
                    "flg_valida_evidencia" => $flgValidaCompro,
                    "flg_preliqui_admin" => $flgPreliquiAdmin,
                );
                $data = $this->M_licencias->insertarComprobanteLicencia($arrayInsert);
                $idReembolso = $data['idReembolso'];
            } else {
                $arrayUpdate = array(
                    "desc_reembolso" => $desc_reembolso,
                    "fecha_emision" => $fecha_emision,
                    "monto" => $monto,
                    "fecha_modificacion" => date("Y-m-d h:m:s"),
                    "estado_valida" => $estadoValida,
                    "iditemplan_estacion_licencia_det" => $iditemplanEstaDet,
                    "flg_valida_evidencia" => $flgValidaCompro,
                    "flg_preliqui_admin" => $flgPreliquiAdmin);

                $data = $this->M_licencias->updateComprobanteLicencia($idReembolso, $arrayUpdate);
            }

            if ($estadoValida == 2) {
                $arrayUpdateIPEst = array('flg_validado' => '2', 'id_usuario_valida' => $idUsuario, 'fecha_valida' => date("Y-m-d h:m:s"));
                if ($data['error'] == EXIT_SUCCESS) {
                    $data = $this->M_licencias->updateItemplanEstaDetLic($iditemplanEstaDet, $arrayUpdateIPEst);
                    //PREVIAMENTE VALIDA SI TODO SE HA CERRADO PARA PASAR A LA SIGUIENTE FASE
                    $this->cerrarFaseEnLicencia($iditemplanEstaDet);
                }
            }

            $ubicacion = 'uploads/licencias';
            if (!is_dir($ubicacion)) {
                mkdir('uploads/licencias', 0777);
            }
            $subCarpeta = 'uploads/licencias/evidencia_fotos';
            if (!is_dir($subCarpeta)) {
                mkdir('uploads/licencias/evidencia_fotos', 0777);
            }
            $subCarpeta2 = 'uploads/licencias/evidencia_fotos/comprobantes';
            if (!is_dir($subCarpeta2)) {
                mkdir('uploads/licencias/evidencia_fotos/comprobantes', 0777);
            }
            $ubicComprobante = 'uploads/licencias/evidencia_fotos/comprobantes/comprobante' . $idReembolso;

            if (!is_dir($ubicComprobante)) {
                mkdir('uploads/licencias/evidencia_fotos/comprobantes/comprobante' . $idReembolso, 0777);
            } else { //si existe borramos el archivo existente
                if ($file != null) {
                    $filesExist = scandir($ubicComprobante); //trae arreglo de archivos existentes en esa carpeta
                    $ficherosEliminados = 0;
                    foreach ($filesExist as $f) {
                        if (is_file($ubicComprobante . "/" . $f)) {
                            if (unlink($ubicComprobante . "/" . $f)) {
                                $ficherosEliminados++;
                            }
                        }
                    }
                }
            }

            $file2 = utf8_decode($file);

            if ($file != null) {

                if (utf8_decode($file) && move_uploaded_file($archivo, $ubicComprobante . "/" . $file2)) {

                    rename($ubicComprobante . "/" . $file2, $ubicComprobante . "/eviCoti_" . $idReembolso . ".pdf");

                    $arrayUpdate = array('ruta_foto' => $ubicComprobante . "/eviCoti_" . $idReembolso . ".pdf");
                    if ($data['error'] == EXIT_SUCCESS) {
                        $data = $this->M_licencias->updateComprobanteLicencia($idReembolso, $arrayUpdate);
                    }
                }
            }


            //PREVIAMENTE VALIDA SI TODO SE HA CERRADO PARA PASAR A LA SIGUIENTE FASE
            $this->cerrarFaseEnLicencia($iditemplanEstaDet);

            if ($data['error'] == EXIT_SUCCESS) {
                $this->db->trans_commit();
            }

        } catch (Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function getRutaEvidenciaReembolso()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $idReembolso = $this->input->post('idReembolso') ? $this->input->post('idReembolso') : null;
            if ($idReembolso == null) {
                throw new Exception('Hubo un error en recibir los datos!!');
            }
            $rutaEvidencia = $this->M_licencias->getRutaEvidencia($idReembolso, 2, null);
            if (isset($rutaEvidencia)) {
                $data['error'] = EXIT_SUCCESS;
                $data['rutaImagen'] = $rutaEvidencia;
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

    public function deleteIPEstDetLic()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        try {
            $itemplan = $this->input->post('itemplan') ? $this->input->post('itemplan') : null;
            $idEstacion = $this->input->post('idEstacion') ? $this->input->post('idEstacion') : null;
            $idItemPlanEstaDetalle = $this->input->post('idItemPlanDet') ? $this->input->post('idItemPlanDet') : null;
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;
            
            $this->db->trans_begin();

            if ($idUsuario == null) {
                throw new Exception('La sesion de usuario a expirado, ingrese nuevamente porfavor!!');
            }
            
            if ($idItemPlanEstaDetalle == null || $itemplan == null || $idEstacion == null) {
                throw new Exception('Hubo un error en recibir los datos!!');
            }

            $arryCountPO = $this->M_licencias->getCountPOByIP($itemplan);

            if ($arryCountPO['cantidad'] == 0) {

                $arryCountCompro = $this->M_licencias->getCountComprobanteByIdIPEstDet($idItemPlanEstaDetalle);
                if ($arryCountCompro['cantidad'] == 0) {

                    $rutaImg = $this->M_licencias->getRutaEvidencia($idItemPlanEstaDetalle, 1, null);

                    if (isset($rutaImg)) {
                        $ubicIPEstDet = 'uploads/licencias/evidencia_fotos/itemPlanEstaDet' . $idItemPlanEstaDetalle;
                        rmdir($ubicIPEstDet);
                    }
                    $data = $this->M_licencias->deleteIPEstaDetalleLic($idItemPlanEstaDetalle);
                } else {
                    if (isset($arryCountCompro['ruta_foto'])) {
                        $ubicCompro = 'uploads/licencias/evidencia_fotos/comprobantes/comprobante' . $arryCountCompro['idReembolso'];
                        rmdir($ubicCompro);
                    }
                    $data = $this->M_licencias->deleteComprobante($arryCountCompro['idReembolso']);
                    if ($data['error'] == EXIT_SUCCESS) {

                        $rutaImg = $this->M_licencias->getRutaEvidencia($idItemPlanEstaDetalle, 1, null);

                        if (isset($rutaImg)) {
                            $ubicIPEstDet = 'uploads/licencias/evidencia_fotos/itemPlanEstaDet' . $idItemPlanEstaDetalle;
                            rmdir($ubicIPEstDet);
                        }
                        $data = $this->M_licencias->deleteIPEstaDetalleLic($idItemPlanEstaDetalle);
                    }
                }

            } else {

                throw new Exception('Este itemplan cuenta con una PO de licencia, no puede eliminarla!!');
            }

            if ($data['error'] == EXIT_SUCCESS) {
                $this->db->trans_commit();
            }

        } catch (Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function updateComprobanteV2()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $iditemplanEstaDet = $this->input->post('iditemplanEstaDet') ? $this->input->post('iditemplanEstaDet') : null;
            $idReembolso = $this->input->post('idReembolso') ? $this->input->post('idReembolso') : null;
            $desc_reembolso = $this->input->post('desc_reembolso') ? $this->input->post('desc_reembolso') : null;
            $fecha_emision = $this->input->post('fecha_emision') ? $this->input->post('fecha_emision') : null;
            $monto = $this->input->post('monto') ? $this->input->post('monto') : null;
            $flgPreliquiAdmin = $this->input->post('flgPreliqui') ? $this->input->post('flgPreliqui') : null;
            $flgValidaCompro = $this->input->post('flgValidaCompro') ? $this->input->post('flgValidaCompro') : null;
            $flgTipoTransacGlob = $this->input->post('flgTipoTransacGlob') ? $this->input->post('flgTipoTransacGlob') : null;
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;
            log_message('error', 'entrov2');

            $this->db->trans_begin();

            if ($idUsuario == null) {
                throw new Exception('Su sesion ha experiado, ingrese nuevamente!!');
            }
            if ($iditemplanEstaDet == null || $desc_reembolso == null || $fecha_emision == null || $monto == null || $flgTipoTransacGlob == null) {
                throw new Exception('Hubo un error al recibir los datos!!');
            }
            if ($flgPreliquiAdmin == null) {
                $flgPreliquiAdmin = '0';
            }
            if ($flgValidaCompro == null) {
                $flgValidaCompro = '0';
            }

            $estadoValida = null;

            if ($flgPreliquiAdmin == '1' || $flgValidaCompro == '1') {
                $estadoValida = 2;
            } else {
                $estadoValida = 1;
            }

            if ($flgTipoTransacGlob == 1) {
                if ($file == null) {
                    throw new Exception('Debe subir un archivo para poder guardar!!');
                }
                $arrayInsert = array(
                    "desc_reembolso" => $desc_reembolso,
                    "fecha_emision" => $fecha_emision,
                    "monto" => $monto,
                    "fecha_registro" => date("Y-m-d h:m:s"),
                    "estado_valida" => $estadoValida,
                    "iditemplan_estacion_licencia_det" => $iditemplanEstaDet,
                    "flg_valida_evidencia" => $flgValidaCompro,
                    "flg_preliqui_admin" => $flgPreliquiAdmin,
                );
                $data = $this->M_licencias->insertarComprobanteLicencia($arrayInsert);
                $idReembolso = $data['idReembolso'];
            } else {
                $arrayUpdate = array(
                    "desc_reembolso" => $desc_reembolso,
                    "fecha_emision" => $fecha_emision,
                    "monto" => $monto,
                    "fecha_modificacion" => date("Y-m-d h:m:s"),
                    "estado_valida" => $estadoValida,
                    "iditemplan_estacion_licencia_det" => $iditemplanEstaDet,
                    "flg_valida_evidencia" => $flgValidaCompro,
                    "flg_preliqui_admin" => $flgPreliquiAdmin);

                $data = $this->M_licencias->updateComprobanteLicencia($idReembolso, $arrayUpdate);
            }

            if ($estadoValida == 2) {
                $arrayUpdateIPEst = array('flg_validado' => '2', 'id_usuario_valida' => $idUsuario, 'fecha_valida' => date("Y-m-d h:m:s"));
                if ($data['error'] == EXIT_SUCCESS) {
                    $data = $this->M_licencias->updateItemplanEstaDetLic($iditemplanEstaDet, $arrayUpdateIPEst);
                }
            }

            //PREVIAMENTE VALIDA SI TODO SE HA CERRADO PARA PASAR A LA SIGUIENTE FASE
            $this->cerrarFaseEnLicencia($iditemplanEstaDet);


            if ($data['error'] == EXIT_SUCCESS) {
                $this->db->trans_commit();
            }

        } catch (Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function obtEstadoLicenciasEstacionPorItemplan($itemplan){
        $foCerrado = 0;
        $coCerrado = 0;
        $liquidoFo = 0;
        $liquidoCo = 0;
		
        foreach ($this->M_licencias->getControlDeLicenciasPorEstacionAnclaPqt($itemplan) as $row) {            
            if($row->idEstacion == ID_ESTACION_COAXIAL){
                if($row->total_licencias == 0){
                    $coCerrado = 0;
                }else if($row->total_licencias != $row->licencias_liquidadas){
                    $coCerrado = 0;
                }else if($row->total_licencias == $row->licencias_liquidadas){
                    $coCerrado = 1;
                }                
                if($row->licencias_liquidadas_MD_MP > 0){
                    $coCerrado = 1;
                }
                if($row->liquido_licencia == 1){
                    $liquidoCo = 1;
                }
            }else if($row->idEstacion == ID_ESTACION_FO){
                #log_message('error',$row->total_licencias.'--'.$row->licencias_liquidadas.'--'.$row->licencias_liquidadas_MD_MP);
                if($row->total_licencias == 0){
                    $foCerrado = 0;
                }else if($row->total_licencias != $row->licencias_liquidadas){                    
                    $foCerrado = 0;
                } else if($row->total_licencias == $row->licencias_liquidadas){                    
                    $foCerrado = 1;
                }               
                if($row->licencias_liquidadas_MD_MP > 0){
                    $foCerrado = 1;
                }
                if($row->liquido_licencia == 1){
                    $liquidoFo = 1;
                }
            }
        }
        $data["foCerrado"] = $foCerrado;
        $data["coCerrado"] = $coCerrado;
        $data["foLiquidado"] = $liquidoFo;
        $data["coLiquidado"] = $liquidoCo;
        return $data;
    }
    
    public function permitirRegistrarEntidades($itemplan){
        
        $foCerrado = 1;
        $coCerrado = 1;
        
        foreach ($this->M_licencias->getControlDeLicenciasPorEstacionAnclaPqt($itemplan) as $row) {
            if($row->idEstadoPlan == ID_ESTADO_EN_LICENCIA || $row->idEstadoPlan == ID_ESTADO_PLAN_EN_OBRA
                || $row->idEstadoPlan == ID_ESTADO_EN_APROBACION){
                $foCerrado = 0;
                $coCerrado = 0;
            }
        }
        $data["permitirFO"] = $foCerrado;
        $data["permitirCO"] = $coCerrado;
        return $data;
    }
    //AGREGAR LOG DE ERRORES EN TRANSACCIONES...
    public function cerrarFaseEnLicencia($id){
        try{
            $itemplan = null;
            foreach ($this->M_licencias->obtItemplanByIdEstLicDet($id) as $row) {
                $itemplan = $row->itemPlan;
            }
            $dataEstadoFinalLicencias = $this->obtEstadoLicenciasEstacionPorItemplan($itemplan);
            
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;
            if($dataEstadoFinalLicencias["foCerrado"] == 1 || $dataEstadoFinalLicencias["coCerrado"] == 1){
               # $this->aprobacionAutomatica($itemplan); comentado 11.12.2019 czavala cas
               $infoItemplan = $this->m_utils->getInfoItemplan($itemplan);
               if($infoItemplan['idEstadoPlan'] ==  ID_ESTADO_EN_LICENCIA){
                   $arrayUpdate = array(
										   "idEstadoPlan" => ID_ESTADO_EN_APROBACION,
										   "usu_upd"      => $idUsuario,
										   "fecha_upd"    => $this->fechaActual()
									   );
                   $data = $this->M_licencias->updEstadoItemplanEnLicenciaFinalizado($itemplan, $arrayUpdate);                  
               }
               
               if($dataEstadoFinalLicencias["foCerrado"] == 1 && $dataEstadoFinalLicencias["foLiquidado"] == 0){
                   $arrayUpdate = array(
                       "liquido_licencia" => 1,
                       "usua_liquido_licencia" => $this->session->userdata('usernameSession'),
                       "fec_liquido_licencia" => $this->fechaActual()
                   );
                   $data = $this->M_licencias->updatePreDisenoLicenciaLiquidad($itemplan, ID_ESTACION_FO, $arrayUpdate);
               }
               if($dataEstadoFinalLicencias["coCerrado"] == 1 && $dataEstadoFinalLicencias["coLiquidado"] == 0){
                   $arrayUpdate = array(
                       "liquido_licencia" => 1,
                       "usua_liquido_licencia" => $this->session->userdata('usernameSession'),
                       "fec_liquido_licencia" => $this->fechaActual()
                   );
                   $data = $this->M_licencias->updatePreDisenoLicenciaLiquidad($itemplan, ID_ESTACION_COAXIAL, $arrayUpdate);
                   
               }
                
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            // log_message('error', '-->PQT C_bandeja_licencias.cerrarFase ERROR se pudo cerrar fase En Licencia de $id: '.$id.' Error '.$e->getMessage());
        }
    }
    
    function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone','America/Lima');
        setlocale(LC_TIME, "es_ES","esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }
    
    function aprobacionAutomatica($itemPlan){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $infoAprobacionAutomatica = $this->m_pqt_pre_liquidacion->esAprobacionAutomatica($itemPlan);
            
            if($infoAprobacionAutomatica["aprobacionAutomatica_fg"] == 1){
                $listaPTR = $this->m_pqt_pre_liquidacion->getPoPreAprobacion($itemPlan);
                foreach($listaPTR->result() as $row){
                    $ptr = $row->ptr;
                    $grafo = $row->grafo;
                    $itemplanPO = $row->itemPlan;
                    $origen = $row->origen;
                
                    if($origen ==   1){//web_unificada
                        $data = $this->m_liquidacion->updatePtrTo01($ptr,$grafo);
                    }else if($origen ==   2){//web po
                        $arrayUpdate = array(
                            "estado_po" => 2
                        );
                        $fechaActual = $this->fechaActual();
                        $data = $this->m_liquidacion->updatePOTo2($ptr,$itemplanPO,$arrayUpdate,$fechaActual);
                    }
                
                    /**05.07.2019 czavala, despuest de pre aprobar la po aplicarle presupuesto**/
                    $data = $this->m_utils->execGetGrafosOnePtr($ptr);
                    /********************************************************/
                }
            }
            
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }

}
