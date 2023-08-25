<?php
defined('BASEPATH') or exit('No direct script access allowed');
class C_liquidacion_obra extends CI_Controller
{
/**modificado**/
    public function __construct()
    {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_ejecucion/M_generales');
        $this->load->model('mf_licencias/M_licencias');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index()
    {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {

            $idEECSesion = $this->session->userdata('eeccSession');

            $data["proyecto"] = $this->llenarCMBProyecto();
            $data["jefatura"] = $this->llenarCMBJefatura();
            $data["empresacolab"] = $this->llenarCMBEmpresaColab();
            $data["fase"] = $this->llenarCMBFase();

            if (@$_POST["pagina"] == "pendienteFiltro") {
                
                if (@!$_POST["itemplan"] || $_POST["itemplan"] == '') {
                    $_POST["itemplan"] = null;
                }
                
                if (@!$_POST["proyecto"]) {
                    $_POST["proyecto"] = null;
                }
                if (@!$_POST["subProyecto"]) {
                    $_POST["subProyecto"] = null;
                }
                if (@!$_POST["jefatura"]) {
                    $_POST["jefatura"] = null;
                }
                if ($idEECSesion == 0 || $idEECSesion == 6) {
                    $idEECSesion = null;
                }
                if (@!$_POST["mesPrevEjec"]) {
                    $_POST["mesPrevEjec"] = "";
                    $mesPrevEjec = null;
                } else {
                    switch ($_POST["mesPrevEjec"]) {
                        case 1:$mesPrevEjec = "01";
                            break;
                        case 2:$mesPrevEjec = "02";
                            break;
                        case 3:$mesPrevEjec = "03";
                            break;
                        case 4:$mesPrevEjec = "04";
                            break;
                        case 5:$mesPrevEjec = "05";
                            break;
                        case 6:$mesPrevEjec = "06";
                            break;
                        case 7:$mesPrevEjec = "07";
                            break;
                        case 8:$mesPrevEjec = "08";
                            break;
                        case 9:$mesPrevEjec = "09";
                            break;
                        case 10:$mesPrevEjec = "10";
                            break;
                        case 11:$mesPrevEjec = "11";
                            break;
                        case 12:$mesPrevEjec = "12";
                            break;
                        default:$mesPrevEjec = null;
                            break;

                    }
                }
                if (@!$_POST["fase"]) {
                    $_POST["fase"] = null;
                }

                $data["tabla"] = $this->makeHTLMTablaConsulta($this->M_licencias->getItemPlansPreliquidados($_POST["itemplan"],$_POST["proyecto"], $_POST["subProyecto"], $_POST["jefatura"], $idEECSesion, $_POST["fase"], $mesPrevEjec));

            } else {
                $data['tabla'] = $this->makeHTLMTablaConsulta(null);
            }

            $data["extra"] = ' <link rel="stylesheet" href="' . base_url() . 'public/bower_components/notify/pnotify.custom.min.css">
            <link rel="stylesheet" href="' . base_url() . 'public/bower_components/bootstrap-validator/bootstrapValidator.min.css"></link>
            <link href="' . base_url() . 'public/vendors/bower_components/datatables/media/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/><link rel="stylesheet" href="' . base_url() . 'public/fancy/source/jquery.fancybox.css" type="text/css" media="screen">
            <link rel="stylesheet" href="' . base_url() . 'public/css/jasny-bootstrap.min.css">';
            $data["pagina"] = "Registro";

            $this->load->view('vf_layaout_sinfix/header', $data);
            $this->load->view('vf_layaout_sinfix/cabecera');
            $this->load->view('vf_layaout_sinfix/menu');

            $this->load->view('vf_licencias/v_liquidacion_obra', $data);

            $this->load->view('recursos_sinfix/js_liquidacion_obra', $data);

            $this->load->view('recursos_sinfix/fancy', $data);

        } else {
            redirect('login', 'refresh');
        }

    }

    public function llenarCMBProyecto()
    {
        $option = '<option value="0" selected>Seleccionar Proyecto</option>';

        $listaProyectos = $this->M_generales->ListarProyecto(1);

        foreach ($listaProyectos->result() as $row) {
            $option .= '<option value="' . $row->idProyecto . '">' . $row->proyectoDesc . '</option>';
        }
        return $option;
    }

    public function getSubProyectos()
    {
        $idProyecto = $this->input->post('idProyecto');
        $data['cmbSubproyecto'] = $this->llenarCMBSubProyecto($this->M_generales->ListarSubProyecto($idProyecto));
        echo json_encode($data);
    }

    public function llenarCMBSubProyecto($listaSubProyectos)
    {
        $option = '<option value="0" selected>Seleccionar SubProyecto</option>';

        foreach ($listaSubProyectos->result() as $row) {
            $option .= '<option value="' . $row->idSubProyecto . '">' . $row->subProyectoDesc . '</option>';
        }
        return $option;
    }

    public function llenarCMBJefatura()
    {
        $listaJefaturas = $this->m_utils->getJefaturaCmb();
        $option = '<option value="0" selected>Seleccionar Jefatura</option>';

        foreach ($listaJefaturas as $row) {
            $option .= '<option value="' . $row->jefatura . '">' . $row->jefatura . '</option>';
        }
        return $option;
    }

    public function llenarCMBEmpresaColab()
    {
        $listaEmpresasColab = $this->m_utils->getAllEECC();
        $option = '<option value="0" selected>Seleccionar EECC</option>';

        foreach ($listaEmpresasColab->result() as $row) {
            $option .= '<option value="' . $row->idEmpresaColab . '">' . $row->empresaColabDesc . '</option>';
        }
        return $option;
    }

    public function llenarCMBFase()
    {
        $listaFase = $this->m_utils->getAllFase();
        $option = '<option value="0" selected>Seleccionar Fase</option>';

        foreach ($listaFase->result() as $row) {
            $option .= '<option value="' . $row->idFase . '">' . $row->faseDesc . '</option>';
        }
        return $option;
    }

    public function makeHTLMTablaConsulta($listaItemPlan)
    {

        $html = '
                <table id="simpletable" class="table table-hover display  pb-30 table-striped table-bordered nowrap">
                    <thead>
                        <tr class="table-primary">
                            <th></th>
                            <th>ITEMPLAN</th>
                            <th>ESTACI&Oacute;N</th>
                            <th style="text-align:center"># ENT. LIQUI. </th>
                            <th style="text-align:center"># ENT. CONCLU. </th>
                            <th>PROYECTO</th>
                            <th>SUB PROY</th>
                            <th>ZONAL</th>
                            <th>EECC</th>
                            <th>FEC. PREVISTA</th>
                        </tr>
                    </thead>

                    <tbody>';
        if ($listaItemPlan != null) {
            foreach ($listaItemPlan as $row) {

                $html .= '
                        <tr>
                            <td style="text-align:center">
                                <a style="cursor:pointer; color: var(--verde_telefonica)" data-itemplan="' . $row->itemPlan . '" data-idestacion="' . $row->idEstacion . '"  onclick="mostrarDetalle(this)"><i class="zmdi zmdi-hc-2x zmdi-eye"></i></a>
                            </td>
                            <td>' . $row->itemPlan . '</td>
                            <td>' . $row->estacionDesc . '</td>
                            <td style="text-align:center">' . $row->cant_liqui . '</td>
                            <td style="text-align:center">' . $row->cant_preliqui . '</td>
                            <td>' . $row->proyectoDesc . '</td>
                            <td>' . $row->subProyectoDesc . '</td>
                            <td>' . $row->zonalDesc . '</td>
                            <td>' . $row->empresaColabDesc . '</td>
                            <td style="text-align:center">' . $row->fechaPrevEjec . '</td>
                        </tr>';
            }
            $html .= '</tbody>
                </table>';

        } else {
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

            if ($itemPlan == null || $idEstacion == null) {
                throw new Exception('Hubo un error al traer entidades!!');
            }

            $listaItemPlanDetalle = $this->makeHTLMTablaIPEstaEnt($this->M_licencias->getEntPreliquiLicDet($itemPlan, $idEstacion),$itemPlan, $idEstacion);

            
            $data['tablaEntidades'] = $listaItemPlanDetalle;
            $data['error'] = EXIT_SUCCESS;

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

    public function makeHTLMTablaIPEstaEnt($listaEstacionEntidad,$itemPlan, $idEstacion)
    {
            $html = '
                <table id="tabla_entidades" class="table table-hover display  pb-30 table-striped table-bordered nowrap">
                    <thead>
                        <tr class="table-primary">
                            <th>ENTIDAD</th>
                            <th>DISTRITO</th>
                            <th style="text-align: center">ESTADO</th>
                            <th>FECHA DE CONCLUSI&Oacute;N</th>
                            <th>EXPEDIENTE CARTA FINALIZACION</th>
                            <th>FECHA FINAL</th>
                            <th>(SUBIR/VER) PDF FINALIZACI&Oacute;N</th>
                            <th style="text-align: center">ACCI&Oacute;N</th>
                        </tr>
                    </thead>

                    <tbody>';
            $count = 1;
            $btnSubiEvi = '';
            $btnGuardar = '';

            if ($listaEstacionEntidad != null) {
                foreach ($listaEstacionEntidad as $row) {

                    if ($row->flg_validado == '3') {
                        $btnSubiEvi = '<a style="color:#9c9c63"><i class="zmdi zmdi-hc-2x zmdi-upload"></i></a>';
                        $btnGuardar = '<a style="color:#9c9c63"><i class="zmdi zmdi-hc-2x zmdi-floppy"></i></a>';
                    } else {
                        $btnSubiEvi = '<a id="btnSubirEvi" style="color: var(--verde_telefonica); cursor: pointer" data-idipestlic="' . $row->iditemplan_estacion_licencia_det . '"  onclick="abrirModalEvidencia(this)"><i class="zmdi zmdi-hc-2x zmdi-upload"></i></a>';
                        $btnGuardar = ' <a id="btnSaveIpEstDet" style="color: var(--verde_telefonica); cursor: pointer" data-idipestlic="' . $row->iditemplan_estacion_licencia_det . '" data-identidad="' . $row->idEntidad . '" data-distrito="' . $row->idDistrito . '"  data-flgtipo="' . $row->flg_tipo . '" data-flgtransac="1" onclick="openModalLiqui(this)"><i class="zmdi zmdi-hc-2x zmdi-floppy"></i></a>';
                    }

                    $html .= '
                        <tr>
                            <td style="font-weight: bold;">' . $row->desc_entidad . '</td>
                            <td style="font-weight: bold;">' . utf8_decode(strtoupper($row->distritoDesc)) . '</td>
                            <td style="text-align: center">' . ($row->flg_validado == '2' ? 'CONCLUIDO' : ($row->flg_validado == '3' ? 'LIQUIDADO' : 'PENDIENTE')) . '</td>
                            <td style="text-align: center">' . $row->fecha_preliqui . '</td>
                            <td style="text-align: center">
                                <input type="text" id="txtCodExpFina' . $row->iditemplan_estacion_licencia_det . '" maxlength="20" class="custom-control-input" value="' . ($row->cod_expe_finalizacion ? $row->cod_expe_finalizacion : null) . '"   ' . ($row->flg_validado == '3' ? 'disabled' : '') . '>
                            </td>
                            <td>
                                <input type="date" id="txtFechaFinal' . $row->iditemplan_estacion_licencia_det . '" class="custom-control-input"  value="' . ($row->fecha_final ? $row->fecha_final : null) . '" ' . ($row->flg_validado == '3' ? 'disabled' : '') . '>
                            </td>
                            <td style="text-align: center">
                                <div class="row">
                                    <div class="col-sm-6 col-md-5">
                                        ' . $btnSubiEvi . '
                                    </div>
                                    <div class="col-sm-6 col-md-5">
                                        <a style="color:var(--verde_telefonica);cursor:pointer" id="btnVerEviEnt' . $row->iditemplan_estacion_licencia_det . '" data-idipestlic="' . $row->iditemplan_estacion_licencia_det . '"  data-index="' . $count . '" onclick="descargarPDFEntidad(this)"><i class="zmdi zmdi-hc-2x zmdi-collection-pdf"></i></a>
                                    </div>
                                </div>
                            </td>
                            <td style="text-align:center">
                                ' . $btnGuardar . '
                            </td>
                        </tr>';

                    $count++;
                }
                $html .= '</tbody>
                </table>';

           /* } else {

                $arrayIDEnt = $this->M_licencias->getEntByIPEst($itemPlan, $idEstacion);

                if ($arrayIDEnt != null) {
                    foreach ($arrayIDEnt as $row) {
                      //  if($row->idEntidad == 2 || $row->idEntidad == 6){//MD

                            $html .= '
                                        <tr>
                                            <td style="font-weight: bold;">'. $row->desc_entidad .'</td>
                                            <td style="font-weight: bold;">' . utf8_decode(strtoupper($row->distritoDesc)) . '</td>
                                            <td style="text-align: center">' . 'CONCLUIDO' . '</td>
                                            <td style="text-align: center">' . $row->fecha_preliqui . '</td>
                                            <td style="text-align: center">
                                                <input type="text" id="txtCodExpFina' . $row->iditemplan_estacion_licencia_det . '" maxlength="20" class="custom-control-input" value="' . (null) . '" >
                                            </td>
                                            <td>
                                                <input type="date" id="txtFechaFinal' . $row->iditemplan_estacion_licencia_det . '" class="custom-control-input"  value="' . (null) . '" >
                                            </td>
                                            <td style="text-align: center">
                                                <div class="row">
                                                    <div class="col-sm-6 col-md-5">
                                                        <a id="btnSubirEvi" style="color: var(--verde_telefonica); cursor: pointer" data-idipestlic="' . $row->iditemplan_estacion_licencia_det . '"  onclick="abrirModalEvidencia(this)"><i class="zmdi zmdi-hc-2x zmdi-upload"></i></a>
                                                    </div>
                                                    <div class="col-sm-6 col-md-5">
                                                        <a style="color:var(--verde_telefonica);cursor:pointer" id="btnVerEviEnt' . $row->iditemplan_estacion_licencia_det . '" data-idipestlic="' . $row->iditemplan_estacion_licencia_det . '"  data-index="' . $count . '" onclick="descargarPDFEntidad(this)"><i class="zmdi zmdi-hc-2x zmdi-collection-pdf"></i></a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td style="text-align:center">
                                                <a id="btnSaveIpEstDet" style="color: var(--verde_telefonica); cursor: pointer" data-idipestlic="' . $row->iditemplan_estacion_licencia_det . '" data-distrito="' . $row->idDistrito . '"  data-flgtipo="' . $row->flg_tipo . '" data-identidad="' . $row->idEntidad . '"  data-flgtransac="2"  onclick="openModalLiqui(this)"><i class="zmdi zmdi-hc-2x zmdi-floppy"></i></a>
                                            </td>
                                        </tr>';
             //           }
                    }
                }*/
                $html .= '</tbody>
                </table>';
            }
        

        return utf8_decode($html);
    }


    public function updateIPEstDet()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            
            log_message('error', 'ENTRE CTMARE!!');
            $iditemplanEstaDet  = $this->input->post('iditemplanEstaDet') ? $this->input->post('iditemplanEstaDet') : null;
            $codExpedienteFina  = $this->input->post('codExpedienteFina') ? $this->input->post('codExpedienteFina') : null;
            $fechaFinal         = $this->input->post('fechaFinal') ? $this->input->post('fechaFinal') : null;
            $flgTipoTransac     = $this->input->post('flgTipoTransac') ? $this->input->post('flgTipoTransac') : null;
            $itemplan           = $this->input->post('itemplan') ? $this->input->post('itemplan') : null;
            $idEstacion         = $this->input->post('idEstacion') ? $this->input->post('idEstacion') : null;
            $idEntidad          = $this->input->post('idEntidad') ? $this->input->post('idEntidad') : null;
            $idDistrito         = $this->input->post('idDistrito') ? $this->input->post('idDistrito') : null;
            $flgTipoLic         = $this->input->post('flgTipoLic') ? $this->input->post('flgTipoLic') : null;
            
            log_message('error', 'ENTRE CTMARE!!'.$iditemplanEstaDet);
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;
            
            if ($idUsuario == null) {
                throw new Exception('Su sesion ha experiado, ingrese nuevamente!!');
            }
            if ($iditemplanEstaDet == null || $codExpedienteFina == null || $fechaFinal == null || $flgTipoTransac == null || $itemplan == null || $idEstacion == null || $idEntidad == null || $flgTipoLic == null) {
                throw new Exception('Debe ingresar todos los campos necesarios para guardar!!');
            }

            if($flgTipoTransac == 2){
                log_message('error', 'ENTRE $flgTipoTransac!!'.$flgTipoTransac);

                $arrayInsert = array(
                    "itemPlan" => $itemplan,
                    "idEstacion" => $idEstacion,
                    "idEntidad" => $idEntidad,
                    'id_usuario_liquida' => $idUsuario,
                    "id_usuario_reg" => $idUsuario,
                    'fecha_liquidacion'  => $this->m_utils->fechaActual(),
                    "flg_validado" => '3',
                    "cod_expe_finalizacion" => $codExpedienteFina,
                    "fecha_final" => $fechaFinal,
                    "idDistrito" => $idDistrito,
                    "flg_tipo" =>  $flgTipoLic
                );

                $data = $this->m_utils->insertarIPEstLicDet($arrayInsert);
                log_message('error', '$data[iditemplan_estacion_licencia_det]:'.$data['iditemplan_estacion_licencia_det']);
                $iditemplanEstaDet = $data['iditemplan_estacion_licencia_det'];

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
            }
            $ubicFinal = $ubicEvidencia . '/evidencia_liquidacion';
            log_message('error', 'ubicFinal:'.$ubicFinal);
            if (!is_dir($ubicFinal)) {
                mkdir($ubicFinal, 0777);
            } else { //si existe borramos el archivo existente
                $filesExist = scandir($ubicFinal); //trae arreglo de archivos existentes en esa carpeta
                $ficherosEliminados = 0;
                foreach ($filesExist as $f) {
                    if (is_file($ubicFinal . "/" . $f)) {
                        if (unlink($ubicFinal . "/" . $f)) {
                            $ficherosEliminados++;
                        }
                    }
                }
            }

            $file2 = utf8_decode($file);

            if (utf8_decode($file) && move_uploaded_file($archivo, $ubicFinal . "/" . $file2)) {

                rename($ubicFinal . "/" . $file2, $ubicFinal . "/eviIPEstDet_" . $iditemplanEstaDet . ".pdf");

                if($flgTipoTransac == 1){

                    $arrayUpdate = array(
                        'id_usuario_liquida' => $idUsuario, 
                        'fecha_liquidacion'  => $this->m_utils->fechaActual(),
                        "flg_validado" => '3',
                        "ruta_pdf_finalizacion" => $ubicFinal . "/eviIPEstDet_" . $iditemplanEstaDet . ".pdf",
                        "cod_expe_finalizacion" => $codExpedienteFina,
                        "fecha_final" => $fechaFinal
                    );
                    $data = $this->M_licencias->updateItemplanEstaDetLic($iditemplanEstaDet, $arrayUpdate);

                }else if ($flgTipoTransac == 2){
                    log_message('error', 'ubicFinal:'.print_r($arrayInsert, true));
                    log_message('error', 'ubicFinal:'.$iditemplanEstaDet);
                    $arrayUpdate = array(
                        "ruta_pdf_finalizacion" => $ubicFinal . "/eviIPEstDet_" . $iditemplanEstaDet . ".pdf"
                    );
                    $data = $this->M_licencias->updateItemplanEstaDetLic($iditemplanEstaDet, $arrayUpdate);

                }else{
                    throw new Exception("Hubo un error al guardar la finalizacion!!");
                }

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
            $rutaEvidencia = $this->M_licencias->getRutaEvidencia($idItemPlanEstaDetalle, 1, 1);
            if (isset($rutaEvidencia)) {
                $data['error'] = EXIT_SUCCESS;
                $data['rutaImagen'] = $rutaEvidencia;
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

}
