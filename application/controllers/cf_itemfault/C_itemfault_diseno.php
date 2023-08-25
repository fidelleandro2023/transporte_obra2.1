<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class C_itemfault_diseno extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_itemfault/M_consulta');
        $this->load->model('mf_pqt_obra_diseno/m_pqt_diseno');
        $this->load->model('mf_pre_diseno/m_bandeja_ejecucion');
        $this->load->model('mf_pre_diseno/m_bandeja_adjudicacion');
        /*         * ***********nuevo********************* */
        $this->load->model('mf_licencias/M_bandeja_itemplan_estacion');
        $this->load->model('mf_plan_obra/m_editar_planobra');
        $this->load->model('mf_liquidacion/m_liquidacion');
        $this->load->model('mf_servicios/m_integracion_sirope');

        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->library('zip');
        $this->load->helper('url');
    }

    public function index() {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {
            $data['cmbProyecto'] = __buildComboProyecto();
            $data['listaZonal'] = $this->m_utils->getAllZonal();
            $data['listaProy'] = $this->m_utils->getAllProyecto();
            $data['cmbEstacion'] = __buildComboEstacion(1);
            $data['cmbPlanta'] = __buildComboPlanta();
            $data['cmbJefatura'] = __buildComboJefatura();
            $data['listaTiCen'] = $this->m_utils->getAllCentral();

            $itemplan = (isset($_GET['itemplan']) ? $_GET['itemplan'] : '');

            $infoCotizacion = $this->m_utils->getDataCotizacionByItemplan($itemplan);

            $data['nodo_princ'] = $infoCotizacion['nodo_principal'];
            $data['nodo_resp'] = $infoCotizacion['nodo_respaldo'];
            $data['facilidades_de_red'] = $infoCotizacion['facilidades_de_red'];
            $data['cant_cto'] = $infoCotizacion['cant_cto'];
            $data['metro_tendido_aereo'] = $infoCotizacion['metro_tendido_aereo'];
            $data['metro_tendido_subterraneo'] = $infoCotizacion['metro_tendido_subterraneo'];
            $data['metro_nue_cana'] = $infoCotizacion['metors_canalizacion'];
            $data['cant_camaras_nuevas'] = $infoCotizacion['cant_camaras_nuevas'];
            $data['cant_postes_nuevos'] = $infoCotizacion['cant_postes_nuevos'];
            $data['cant_postes_apoyo'] = $infoCotizacion['cant_postes_apoyo'];
            $data['cant_apertura_camara'] = $infoCotizacion['cant_apertura_camara'];

            $data['requiere_seia'] = $infoCotizacion['requiere_seia'];
            $data['requiere_aprob_mml_mtc'] = $infoCotizacion['requiere_aprob_mml_mtc'];
            $data['requiere_aprob_inc'] = $infoCotizacion['requiere_aprob_inc'];
            $data['duracion'] = $infoCotizacion['duracion'];
            $data['id_tipo_diseno'] = $infoCotizacion['id_tipo_diseno'];
            $data['costo_materiales'] = str_replace('\'', '', str_replace(',', '', $infoCotizacion['costo_materiales']));
            $data['costo_mano_obra'] = str_replace('\'', '', str_replace(',', '', $infoCotizacion['costo_mano_obra']));
            $data['costo_diseno'] = str_replace('\'', '', str_replace(',', '', $infoCotizacion['costo_diseno']));
            $data['costo_expe_seia_cira_pam'] = str_replace('\'', '', str_replace(',', '', $infoCotizacion['costo_expe_seia_cira_pam']));
            $data['costo_adicional_rural'] = str_replace('\'', '', str_replace(',', '', $infoCotizacion['costo_adicional_rural']));
            $data['costo_total'] = str_replace('\'', '', str_replace(',', '', $infoCotizacion['costo_total']));
            $data['flg_principal'] = $infoCotizacion['flg_principal'];

            $data['tablaDiseno'] = $this->getTablaDiseno($itemplan);
            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $data['arrayTipoDiseno'] = $this->m_utils->getTipoDiseno(NULL);
            $data['idProyecto'] = $this->m_utils->getProyectoByItemplan($itemplan);

            $this->load->view('vf_pqt_obra_diseno/v_pqt_diseno', $data);
            // $permisos =  $this->session->userdata('permisosArbol');
            // $result = $this->lib_utils->getHTMLPermisos($permisos, ESTADO_PLAN_DISENO, ID_PERMISO_HIJO_BANDEJA_EJECUCION);
            // $data['opciones'] = $result['html'];
            //    if($result['hasPermiso'] == true){
            //        $this->load->view('vf_prediseno/v_bandeja_ejecucion',$data);
            //    }else{
            //        redirect('login','refresh');
            //    }
        } else {
            redirect('login', 'refresh');
        }
    }

    function getTablaDiseno($itemplan) {
        $dataDiseno = $this->M_consulta->getDataDiseno($itemplan);
        $html = '<table class="table table-bordered">
                    <thead class="thead-default">
                      <tr>';
        $html .= '     <th>APROBAR</th>
                       <th>ITEMFAULT</th>
                       <th>NOMBRE DE URA</th>
                       <th>SERVICIO DE RED</th>
                       <th>ELEMENTO DE RED DE SERVICIO</th>
                       <th>EECC</th>
                       <th>FECHA DE CREACION</th>
                       <th>ESTADO</th>
                      </tr>
                    </thead                   
                    <tbody>';

//        foreach ($dataDiseno as $row) {
//            log_message('error', 'aqui....');
//            $btnFormulario = '';
//            if ($row->idProyecto == ID_PROYECTO_SISEGOS && $row->hasSisegoPlanObra == 0) {
//                $btnFormulario = '<a data-jefatura="' . $row->jefatura . '" data-item_plan="' . $row->itemPlan . '" data-flg_from="1" onclick="openModalBandejaEjecucion($(this))"style="margin-left: 10%;"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/iconodetalle.png"></a>';
//            }
//            $btnInfo = $row->idProyecto == ID_PROYECTO_SISEGOS ? '<a onclick="openModalDetailDiseno($(this))" style="margin-left: 10px;" data-has_cotizacion="' . $row->has_cotizacion . '" data-itemplan="' . $row->itemPlan . '" data-idestacion="' . $row->idEstacion . '"><i class="zmdi zmdi-hc-2x zmdi-eye"></i></a>' : '';
//            $html .= '<tr id="' . $row->itemPlan . $row->idEstacion . '"> 
//                            <td>' . (($row->estado == ESTADO_PLAN_DISENO && $row->idEstadoPlan != ID_ESTADO_CANCELADO && $row->idEstadoPlan != ID_ESTADO_TERMINADO && $row->idEstadoPlan != ID_ESTADO_CERRADO && $row->idEstadoPlan != ID_ESTADO_TRUNCO && $row->idEstadoPlan != ID_ESTADO_SUSPENDIDO) ? '<a data-item="' . $row->itemPlan . '" data-sisegofg="' . ($row->idProyecto == ID_PROYECTO_SISEGOS ? 1 : 0) . '" data-id_estacion="' . $row->idEstacion . '" onclick="abrirModalAsignarEntidades(this)" style="margin-left: 10%;"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/check_24016.png"></a> ' :
//                    (($row->estado == 3) ? 'EXPEDIENTE <a href="' . $row->path_expediente_diseno . '" download=""><i class="zmdi zmdi-hc-2x zmdi-download"></i></a>' . $btnInfo : '')) . '                            
//                            
//                            </td>
//                            <th>' . (($row->estado == ESTADO_PLAN_DISENO) ? '<a href="#"  class="ver_ptr" data-idrow="' . $row->itemPlan . $row->idEstacion . '" data-estacion="' . $row->idEstacion . '">' . $row->itemPlan . '</a>' : $row->itemPlan ) . '</th>	
//                            <th>' . $row->estacionDesc . '</th>                             
//                            <th>' . $row->indicador . '</th>							
//                            <th>' . $row->proyectoDesc . '</th>
//                            <th>' . $row->subProyectoDesc . '</th>
//							<th>' . $row->empresaColabDesc . '</th>
//							<th>' . $row->empresaColabDiseno . '</th>						
//                            <th>' . $row->estadoPlanDesc . '</th>
//                            <th>' . $row->jefatura . '</th>
//                            <th>' . $row->usuario_ejecucion . '</th>
//                            <th>' . $row->fecha_ejecucion . '</th>
//                            <th>' . $row->licencia . '</th>                                
//			             </tr>';
//        }

        $html .= '</tbody>
                </table>';

        return utf8_decode($html);
    }

    function filtrarTabla() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {

            $idEstacion = $this->input->post('idEstacion');
            $idTipoPlan = $this->input->post('idTipoPlan');
            $jefatura = $this->input->post('jefatura');
            $idProyecto = $this->input->post('idProyecto');
            $SubProy = $this->input->post('subProy');
            $fecha = $this->input->post('fecha');

            $idEstacion = ($idEstacion == '') ? NULL : $idEstacion;
            $idTipoPlan = ($idTipoPlan == '') ? NULL : $idTipoPlan;
            $jefatura = ($jefatura == '') ? NULL : $jefatura;
            $idProyecto = ($idProyecto == '') ? NULL : $idProyecto;
            $SubProy = ($SubProy == '') ? NULL : $SubProy;
            $fecha = ($fecha == '') ? NULL : $fecha;

            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_bandeja_ejecucion->getBandejaEjecucion($idEstacion, $idTipoPlan, $jefatura, $SubProy, $idProyecto, $fecha, $this->session->userdata('eeccSession')));
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function validarAprobarDiseno() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $itemplan = $this->input->post('itemplan');
            $idEstacion = $this->input->post('idEstacion');
            $reject = false;

            $info = $this->m_bandeja_ejecucion->getCountPtrsEstacionesByItemplan($itemplan);
            if ($info != null) {
                if ($idEstacion == ID_ESTACION_COAXIAL) {
                    if ($info['conCoax'] == 0) {
                        $reject = true;
                    }
                } else if ($idEstacion == ID_ESTACION_FO) {
                    if ($info['conFo'] == 0) {
                        if ($info['idProyecto'] == ID_PROYECTO_SISEGOS) {
                            if ($info['conMul'] == 0 && $info['conUM'] == 0) {
                                $reject = true;
                            }
                        }
                    }
                }
            } else if ($info == null) {
                $reject = true;
            }

            if ($reject) {
                throw new Exception('NO SE PUEDE APROBAR, AGREGUE PTR DE LA MISMA ESTACI&Oacute;');
            } else {
                if ($info['idProyecto'] == ID_PROYECTO_SISEGOS) {
                    $hasFormSisego = $this->m_utils->hasSisegoPlanObra($itemplan, ID_TIPO_OBRA_FROM_DISENIO);
                    // if($hasFormSisego   ==  0){
                    //     throw new Exception('NO SE PUEDE APROBAR, AGREGUE INFROMACION EN EL FORMULARIO DISEÃ‘O DE OBRA');
                    // }
                }
            }
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function ejecutarPqtDiseno() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            /*
              $itemplan = $this->input->post('item');
              $idEstacion = $this->input->post('idEstacion');
             */
            $sinLicencia = false; #cambio de sin licencia

            $jsonDataForm = json_decode($this->input->post('dataJson'));
            log_message('error', '$arrayIdEntidades:' . print_r($jsonDataForm, true));

            $itemplan = $jsonDataForm->itemplan;
            $idEstacion = $jsonDataForm->idEstacion;

            $cantTroba = $jsonDataForm->cantTroba;
            $cantAmplificador = $jsonDataForm->cantAmplificador;
            //LOGICA ENTIDADES

            $idPersonaSession = $this->session->userdata('idPersonaSession');
            $arrayIdEntidades = $jsonDataForm->arrayIdEntidades;
            $flgExpediente = $jsonDataForm->flgExpediente;
            $flgDisenoSirope = $jsonDataForm->flgDisenoSirope;

            $hfSisegoFG = $jsonDataForm->hfSisegoFG;

            $otActualizacion = $jsonDataForm->otActualizacion;

            $arrayInsert = array();

            //log_message('error', '$arrayIdEntidades:'.print_r($arrayIdEntidades,true));

            $this->db->trans_begin();

            if ($idPersonaSession == null || $idPersonaSession == '') {
                throw new Exception('error, sesi&oacute;n a finalizado, recargue la p&aacute;gina y vuelva a logearse.');
            }
            log_message('error', '$arrayIdEntidades:' . print_r($arrayIdEntidades, true));
            $numEntidades = count($arrayIdEntidades);
            if (is_array($arrayIdEntidades) && $numEntidades > 0) {
                if ($numEntidades == 1) {#solo 0 sin entidades
                    if ($arrayIdEntidades[0][0] == 999999) {
                        $sinLicencia = true;
                    }

                    log_message('error', 'arrayIdEntidades[0]:' . $arrayIdEntidades[0][0]);
                    $data_arra = array('idEntidad' => $arrayIdEntidades[0][0],
                        'idEstacion' => $idEstacion,
                        'itemPlan' => $itemplan,
                        'ruta_pdf' => null,
                        'fecha_inicio' => null,
                        'fecha_fin' => null,
                        'id_usuario_reg' => $idPersonaSession,
                        'fecha_registro' => $this->fechaActual(),
                        'fecha_valida' => null,
                        'flg_validado' => 0,
                        'id_usuario_valida' => $idPersonaSession);
                    array_push($arrayInsert, $data_arra);
                } else {
                    foreach ($arrayIdEntidades as $row) {
                        if ($row != 0) {//si cuenta con mas de una entidad seleccionada no tomar en cuenta los 0
                            log_message('error', 'row[0]:' . $row[0]);
                            if ($row[0] == 999999) {
                                $sinLicencia = true;
                            }
                            array_push($arrayInsert, array('idEntidad' => $row[0],
                                'idEstacion' => $idEstacion,
                                'itemPlan' => $itemplan,
                                'ruta_pdf' => null,
                                'fecha_inicio' => null,
                                'fecha_fin' => null,
                                'id_usuario_reg' => $idPersonaSession,
                                'fecha_registro' => $this->fechaActual(),
                                'fecha_valida' => null,
                                'flg_validado' => 0,
                                'id_usuario_valida' => $idPersonaSession));
                        }
                    }
                }

                //log_message('error', '$$arrayInsert:'.print_r($arrayInsert,true));
                if (!$sinLicencia) {
                    $data = $this->M_bandeja_itemplan_estacion->insertEntidadesFromEjecucionDiseno($arrayInsert);
                }
            }
            log_message('error', 'Paso 1');
            $arrayUpdate = array('itemPlan' => $itemplan, 'expediente_diseno' => $flgExpediente, 'plano_diseno_sirope' => $flgDisenoSirope, 'fec_ult_ejec_diseno' => $this->fechaActual());
            $data = $this->M_bandeja_itemplan_estacion->editarPlanObra($itemplan, $arrayUpdate);
            $modificaciones = "expediente_diseno= " . $flgExpediente;
            $data = $this->m_editar_planobra->insertarLogPlanObra($itemplan, $idPersonaSession, $modificaciones, 'planobra', '', '');
            //FIN DE LOGICA DE ENTIDADES
            log_message('error', 'Paso 2');
            $data = $this->m_bandeja_ejecucion->ejecutarDiseno($itemplan, $idEstacion);
            if ($data['error'] == EXIT_ERROR) {
                throw new Exception('ERROR AL EJECUTAR EL DISENO');
            }

            if ($sinLicencia) {
                $arrayUpdate = array(
                    "requiere_licencia" => 2
                );
                $data = $this->m_pqt_diseno->updatePreDisenoLicenciaLiquidad($itemplan, $idEstacion, $arrayUpdate);
            }

            $estado_plan = $this->m_utils->getEstadoPlanByItemplan($itemplan);
            $infoPlanObra = $this->m_utils->getPlanobraByItemplan($itemplan);
            log_message('error', 'Paso 3');

            if ($estado_plan == ESTADO_PLAN_DISENO) {
                #Se comento pasa con el primero 11.12.2019 czavala
                #if($this->m_pqt_diseno->existenAunEnPreDiseno($itemplan) == 0){
                if ($sinLicencia) {
                    $data = $this->m_pqt_diseno->changeEstadoEnAprobacionFromEjecucionNoLicencia($itemplan);
                } else {
                    $data = $this->m_pqt_diseno->updateEstadoPlanObraToBanEjecucion($itemplan, ID_ESTADO_EN_LICENCIA, $idEstacion);
                    $this->cambiarDisenoObra($itemplan, $estado_plan); //registra log en planobra por el estado en licencia y en log planobra bandeja ejecucion
                }
                #}
            }
            log_message('error', 'Paso 4');

            $idSubProyectoEstacion = $this->m_bandeja_ejecucion->getIdSubProyecEstacionByitemplanAndEstacion($itemplan, $idEstacion);

            $flgPoDiseno = $this->m_bandeja_ejecucion->getFlgPoDiseno($itemplan, $idSubProyectoEstacion);

            $idProyecto = $this->m_utils->getProyectoByItemplan($itemplan);
            if ($idProyecto == 4 || $idProyecto == 8) {//OBRA PUBLICA O TRANSPORTE SE GENERA PO DISEÃ‘O
                if ($flgPoDiseno == '' || $flgPoDiseno == null) {//SI NO TIENE PO DISENO SE GENERA
                    $idTipoComplejidad = $this->m_bandeja_ejecucion->getTipoComplejidadByItemplan($itemplan);
                    if ($idTipoComplejidad == 2) {
                        $flg_mo = $this->m_bandeja_ejecucion->getFlgMo($itemplan, $idEstacion);

                        if ($flg_mo != 1) {
                            $data['error'] = EXIT_ERROR;
                            throw new Exception('Es de complejidad alta, necesita PO MO');
                        }
                    }
                    $data = $this->generarPODiseno($itemplan, $idEstacion, $cantAmplificador, $cantTroba, $idPersonaSession, $idSubProyectoEstacion); //FUNCION DONDE SE GENERA LA PO
                }
            }

            if ($data['error'] == EXIT_ERROR) {
                throw new Exception($data['msj']);
            }
            log_message('error', 'Paso 6');
            //DE NO EXISTIR LA CARPETA ITEMPLAN LA CREAMOS
            $pathItemplan = 'uploads/expedientes_diseno/' . $itemplan;
            if (!is_dir($pathItemplan)) {
                mkdir($pathItemplan, 0777);
            }

            $descEstacion = $this->m_utils->getEstaciondescByIdEstacion($idEstacion);
            //DE NO EXISTIR LA CARPETA ITEMPLAN ESTACION LA CREAMOS
            $pathItemEstacion = $pathItemplan . '/' . $descEstacion;
            if (!is_dir($pathItemEstacion)) {
                mkdir($pathItemEstacion, 0777);
            }

            $uploadfile1 = $pathItemEstacion . '/' . basename($_FILES['archivoExpediente']['name']);

            if (move_uploaded_file($_FILES['archivoExpediente']['tmp_name'], $uploadfile1)) {
                log_message('error', 'Se movio el archivo a la ruta 1.' . $uploadfile1);

                $dataUpdate = array('path_expediente_diseno' => $uploadfile1);
                $data = $this->m_bandeja_ejecucion->updatePathDisenoExpediente($dataUpdate, $itemplan, $idEstacion);
                if ($data['error'] == EXIT_ERROR) {
                    throw new Exception('Error al actualizar la ruta del archivo.');
                }
            } else {
                throw new Exception('Hubo un problema con la carga del archivo 1 al servidor, comuniquese con el administrador.');
            }

            log_message('error', 'Archivo subido ah:' . $uploadfile1);
            log_message('error', 'Paso 7');
            if ($hfSisegoFG == 1) {

                $dataL = array(
                    'itemplan' => $jsonDataForm->itemplan,
                    'planobra_clustercol' => $jsonDataForm->idEstacion,
                    'nodo_principal' => $jsonDataForm->nodo_principal,
                    'nodo_respaldo' => $jsonDataForm->nodo_respaldo,
                    'facilidades_de_red' => $jsonDataForm->facilidades_de_red,
                    'cant_cto' => $jsonDataForm->cant_cto,
                    'metro_tendido_aereo' => $jsonDataForm->metro_tendido_aereo,
                    'metro_tendido_subterraneo' => $jsonDataForm->metro_tendido_subterraneo,
                    'metors_canalizacion' => $jsonDataForm->metors_canalizacion,
                    'cant_camaras_nuevas' => $jsonDataForm->cant_camaras_nuevas,
                    'cant_postes_nuevos' => $jsonDataForm->cant_postes_nuevos,
                    'cant_postes_apoyo' => $jsonDataForm->cant_postes_apoyo,
                    'cant_apertura_camara' => $jsonDataForm->cant_apertura_camara,
                    'requiere_seia' => $jsonDataForm->requiere_seia,
                    'requiere_aprob_mml_mtc' => $jsonDataForm->requiere_aprob_mml_mtc,
                    'requiere_aprob_inc' => $jsonDataForm->requiere_aprob_inc,
                    'duracion' => $jsonDataForm->duracion,
                    'id_tipo_diseno' => $jsonDataForm->id_tipo_diseno,
                    'costo_materiales' => $jsonDataForm->costo_materiales,
                    'costo_mano_obra' => $jsonDataForm->costo_mano_obra,
                    'costo_diseno' => $jsonDataForm->costo_diseno,
                    'costo_expe_seia_cira_pam' => $jsonDataForm->costo_expe_seia_cira_pam,
                    'costo_adicional_rural' => $jsonDataForm->costo_adicional_rural,
                    'costo_total' => $jsonDataForm->costo_total,
                    'comentario' => $jsonDataForm->comentario,
                    'fecha_registro' => $this->fechaActual(),
                    'usuario_envio_cotizacion' => $this->session->userdata('idPersonaSession')
                );
                $this->m_pqt_diseno->insertarDatosDisenoPlanObraCluster($dataL);
            }

            $this->db->trans_commit();

            $data['error'] = EXIT_SUCCESS;
            log_message('error', 'Paso 8');
            $msj = 'Se ejecuto el diseÃ±o correctamente.';

            if ($otActualizacion == 1 && $jsonDataForm->idEstacion == ID_ESTACION_FO) {
                $dataCentral = $this->m_utils->getInfoItemToSendSiropeEjecucionDiseno($jsonDataForm->idEstacion, $itemplan);
                if ($dataCentral != null) {
                    #  if($dataCentral['jefatura']=='LIMA' || $dataCentral['jefatura']=='PIURA' || $dataCentral['jefatura']=='CAJAMARCA' || $dataCentral['jefatura']=='TRUJILLO' || $dataCentral['jefatura']=='CHIMBOTE' || $dataCentral['jefatura']=='AREQUIPA' || $dataCentral['jefatura']=='CUSCO'){//validacion temporal sisegos solo lima
                    //en produccion descomentar
                    $respWS = $this->m_integracion_sirope->execWs($itemplan, $itemplan . 'AC', $dataCentral['fechaInicio'], $dataCentral['fecha_prevista']);
                    if ($respWS['error'] == EXIT_SUCCESS) {
                        $msj .= 'OT de Actualizacion: ' . $itemplan . 'AC';
                    } else {
                        $msj .= 'Error al generar OT(' . $itemplan . 'AC' . ')... ' . $respWS['msj'];
                    }
                    # }
                } else {
                    $msj .= "Pero no se detecto ID Central para el vio de OT.";
                    //no se detecto un idCentral..
                }
            }
            $data['msj'] = $msj;
            // en produccion descomentar
            //$this->ejecutarEnviosFallidosSiom($itemplan, $idEstacion);
        } catch (Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
            log_message('error', 'Error ejecutarPqtDiseno :' . $e->getMessage());
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function generarPODiseno($__itemplan, $__id_estacion, $__nro_amplificador, $__nro_troba, $__id_usuario, $idSubProyectoEstacion) {
        $data['error'] = EXIT_SUCCESS;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $data = array();
            $dataArray = $this->m_bandeja_ejecucion->getDetallePODiseno($__itemplan, $__id_estacion, $__nro_amplificador, $__nro_troba);
            $codigo_po = $this->m_utils->getCodigoPO($__itemplan);
            $totalFin = 0;
            $idEstadoPo = null;
            $has_sirope = null;
            $idTipoComplejidad = null;
            $idEstacion = null;
            $idEmpresaColab = null;
            $flg_mat = null;
            foreach ($dataArray as $row) {
                $totalFin = $row['totalDiseno'] + $totalFin;
                $has_sirope = $row['has_sirope_diseno'];
                $idTipoComplejidad = $row['idTipoComplejidad'];
                $idEmpresaColab = $row['idEmpresaColab'];
                $flg_mat = $row['flgMat'];

                $arrayDetallePO = array();
                $arrayDetalle['codigo_po'] = $codigo_po;
                $arrayDetalle['idPartida'] = $row['idPartida'];
                $arrayDetalle['idPrecioDiseno'] = $row['idPrecioDiseno'];
                $arrayDetalle['idEmpresaColab'] = $row['idEmpresaColab'];
                $arrayDetalle['idZonal'] = $row['idZonal'];
                $arrayDetalle['cantidad'] = $row['cantidad'];
                $arrayDetalle['baremo'] = $row['baremo'];
                $arrayDetalle['costo'] = $row['costoPreciario'];
                $arrayDetalle['nro_amplificadores'] = ($__nro_amplificador) ? $__nro_amplificador : $__nro_troba;
                $arrayDetalle['total'] = $row['totalDiseno'];
                array_push($arrayDetallePO, $arrayDetalle);
            }

            if ($__id_estacion == 5) {
                if ($has_sirope == 1) {
                    if ($idTipoComplejidad == 1) {
                        $estado_po = 5;
                    } else if ($idTipoComplejidad == 2) {
                        $estado_po = 1;
                    }
                } else {
                    $estado_po = 1;
                }
            } else if ($__id_estacion == 2) {
                if ($idTipoComplejidad == 1) {
                    $estado_po = 5;
                } else if ($idTipoComplejidad == 2) {
                    $estado_po = 1;
                }
            }

            if (count($arrayDetallePO) > 0) {
                if ($flg_mat != 1) {
                    $data['error'] = EXIT_ERROR;
                    throw new Exception('No tiene PO material');
                }

                $arrayPO = array(
                    'itemplan' => $__itemplan,
                    'codigo_po' => $codigo_po,
                    'estado_po' => $estado_po,
                    'idEstacion' => 1,
                    'from' => FROM_DISENIO,
                    'costo_total' => $totalFin,
                    'idUsuario' => $__id_usuario,
                    'fechaRegistro' => $this->fechaActual(),
                    'flg_tipo_area' => 2,
                    'id_eecc_reg' => $idEmpresaColab
                );
                //ARRAYY PARA EL LOG
                $arrayLogPO = array();
                if ($estado_po == 5) {
                    $arrayLog = array(
                        'codigo_po' => $codigo_po,
                        'itemplan' => $__itemplan,
                        'idUsuario' => $__id_usuario,
                        'fecha_registro' => $this->fechaActual(),
                        'idPoestado' => 1,
                        'controlador' => 'CREACION PO REGISTRO DISENO'
                    );

                    array_push($arrayLogPO, $arrayLog);

                    $arrayLog = array(
                        'codigo_po' => $codigo_po,
                        'itemplan' => $__itemplan,
                        'idUsuario' => $__id_usuario,
                        'fecha_registro' => $this->fechaActual(),
                        'idPoestado' => 4,
                        'controlador' => 'CREACION PO REGISTRO DISENO'
                    );
                    array_push($arrayLogPO, $arrayLog);

                    $arrayLog = array(
                        'codigo_po' => $codigo_po,
                        'itemplan' => $__itemplan,
                        'idUsuario' => $__id_usuario,
                        'fecha_registro' => $this->fechaActual(),
                        'idPoestado' => 5,
                        'controlador' => 'CREACION PO REGISTRO DISENO'
                    );
                    array_push($arrayLogPO, $arrayLog);
                } else if ($estado_po == 1) {
                    $arrayLog = array(
                        'codigo_po' => $codigo_po,
                        'itemplan' => $__itemplan,
                        'idUsuario' => $__id_usuario,
                        'fecha_registro' => $this->fechaActual(),
                        'idPoestado' => 1,
                        'controlador' => 'CREACION PO REGISTRO DISENO'
                    );

                    array_push($arrayLogPO, $arrayLog);
                }

                if ($idSubProyectoEstacion == null || $idSubProyectoEstacion == '') {
                    $data['error'] = EXIT_ERROR;
                    throw new Exception('Debe ingresar el área de diseño');
                }


                $arrayDetalleplan = array(
                    'itemPlan' => $__itemplan,
                    'poCod' => $codigo_po,
                    'idSubProyectoEstacion' => $idSubProyectoEstacion
                );

                $data = $this->m_bandeja_ejecucion->registrarPoDiseno($arrayPO, $arrayLogPO, $arrayDetalleplan, $arrayDetallePO);
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }

    function filtrarSubProyecto() {
        $idProyecto = $this->input->post('idProyecto');
        $data['cmbSubProyecto'] = __buildSubProyecto($idProyecto, ID_TIPO_PLANTA_EXTERNA, 1);

        echo json_encode(array_map('utf8_encode', $data));
    }

    function insertFileEjec() {
        log_message('error', '2 ) idEstacion::' . $this->session->userdata('idEstacion_tmp'));
        $itemPlan = $this->session->userdata('itemplan_tmp');
        $idEstacion = $this->session->userdata('idEstacion_tmp');
        $file = $_FILES ["file"] ["name"];
        $filetype = $_FILES ["file"] ["type"];
        $filesize = $_FILES ["file"] ["size"];

        //log_message('error', 'insert1');

        $ubicacion = 'uploads/ejecucion/' . $itemPlan;
        if (!is_dir($ubicacion)) {
            mkdir('uploads/ejecucion/' . $itemPlan, 0777);
        }

        if ($idEstacion == ID_ESTACION_COAXIAL) {
            $descEstacion = 'COAXIAL';
        } ELSE IF ($idEstacion == ID_ESTACION_FO) {
            $descEstacion = 'FO';
        }

        $subCarpeta = 'uploads/ejecucion/' . $itemPlan . '/' . $itemPlan . '_' . $descEstacion;
        $file2 = utf8_decode($file);
        if (!is_dir($subCarpeta))
            mkdir($subCarpeta, 0777);
        if (utf8_decode($file) && move_uploaded_file($_FILES["file"]["tmp_name"], $subCarpeta . "/" . $file2)) {
            log_message('error', 'INSERTO IMG');
        }
        $data['error'] = EXIT_SUCCESS;
        echo json_encode(array_map('utf8_encode', $data));
    }

    function comprimirFilesEjec() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $itemplan = $this->session->userdata('itemplan_tmp');
            $idEstacion = $this->session->userdata('idEstacion_tmp');

            if ($idEstacion == ID_ESTACION_COAXIAL) {
                $descEstacion = 'COAXIAL';
                log_message('error', 'COAXXXXXXX');
            } ELSE IF ($idEstacion == ID_ESTACION_FO) {
                $descEstacion = 'FO';
                log_message('error', 'FOOOOOO');
            }

            $subCarpeta = 'uploads/ejecucion/' . $itemplan . '/' . $itemplan . '_' . $descEstacion;
            $this->zip->read_dir($subCarpeta, false);

            $fileName = $descEstacion . '_' . rand(1, 100) . date("dmhis") . '.zip';
            $this->zip->archive('uploads/ejecucion/' . $itemplan . '/' . $fileName);
            $data = $this->m_bandeja_adjudicacion->registrarNombreArchivo($itemplan, $fileName, $idEstacion);

            $this->rrmdir($subCarpeta);

            $data['error'] = EXIT_SUCCESS;
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_bandeja_ejecucion->getBandejaEjecucion(NULL, NULL, NULL, NULL, NULL, NULL, $this->session->userdata('eeccSession')));

            //$this->zip->download($fileName);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        // log_message('error', 'data_enviar'.print_r($data, true));
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function editEjecuDi() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {

            $idEstaFil = $this->input->post('idEstacionFil');
            $idTipoPlan = $this->input->post('idTipoPlan');
            $jefatura = $this->input->post('jefatura');
            $idProyecto = $this->input->post('idProyecto');
            $SubProy = $this->input->post('subProy');
            $fecha = $this->input->post('fecha');

            $idEstaFil = ($idEstaFil == '') ? NULL : $idEstaFil;
            $idTipoPlan = ($idTipoPlan == '') ? NULL : $idTipoPlan;
            $jefatura = ($jefatura == '') ? NULL : $jefatura;
            $idProyecto = ($idProyecto == '') ? NULL : $idProyecto;
            $SubProy = ($SubProy == '') ? NULL : $SubProy;
            $fecha = ($fecha == '') ? NULL : $fecha;

            $itemplan = $this->session->userdata('itemplan_tmp');
            $idEstacion = $this->session->userdata('idEstacion_tmp');
            $fechaPrevDise = $this->input->post('idFechaPreAtencionCoax');
            $data = $this->m_bandeja_ejecucion->actualizarDatosDiseno($itemplan, $idEstacion, $fechaPrevDise);
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_bandeja_ejecucion->getBandejaEjecucion($idEstaFil, $idTipoPlan, $jefatura, $SubProy, $idProyecto, $fecha, $this->session->userdata('eeccSession')));

            //log_message('error',  $data['tablaAsigGrafo']);
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function getInfoByItemplanEjec() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {

            $itemplan = $this->input->post('itemplan');
            $idEstacion = $this->input->post('idEstacion');

            $array = array('idEstacion_tmp' => $idEstacion,
                'itemplan_tmp' => $itemplan);
            $this->session->set_userdata($array);
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function rrmdir($src) {
        $dir = opendir($src);
        while (false !== ( $file = readdir($dir))) {
            if (( $file != '.' ) && ( $file != '..' )) {
                $full = $src . '/' . $file;
                if (is_dir($full)) {
                    $this->rrmdir($full);
                } else {
                    unlink($full);
                }
            }
        }
        closedir($dir);
        rmdir($src);
    }

    public function makeFORMEntidades($listaEntidades) {
        $html = '';
        $arrayIdEntidades = array();
        $arrayPrueba = array();

        foreach ($listaEntidades as $row) {
            if ($row->marcado == 1) {
                array_push($arrayPrueba, $row->idEntidad, ($row->disabled == null ? 0 : 1));
                array_push($arrayIdEntidades, $arrayPrueba);
            }
            $html .= '  <div class="col-4" id="' . $row->idEntidad . '" >
                            <input type="checkbox" id="Ent' . $row->idEntidad . '" class="custom-control-input" data-ent="' . $row->idEntidad . '" onchange="agregarEntidades(' . $row->idEntidad . ',' . ($row->disabled == null ? 0 : 1) . ')" ' . ($row->marcado == 1 ? 'checked' : '') . '  ' . ($row->disabled == 1 ? 'disabled' : '') . '>
                            <label for="Ent' . $row->idEntidad . '" >' . $row->desc_entidad . '</label>
                       </div>';
            $arrayPrueba = array();
        }

        $data['arrayIdEntidades'] = $arrayIdEntidades;
        $data['html'] = utf8_decode($html);
        return $data;
    }

    /*     * *****nuevo 15082018** */

    public function getInfoByItemplanLicencia() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $itemPlan = $this->input->post('itemplan');
            $idEstacion = $this->input->post('idEstacion');
            $dataEntidades = $this->makeFORMEntidades($this->M_bandeja_itemplan_estacion->getEntidades($itemPlan, $idEstacion));
            $data['htmlEntidades'] = $dataEntidades['html'];
            $data['arrayIdEntidades'] = $dataEntidades['arrayIdEntidades'];
            $flg = $this->m_utils->validarComplejidadDiseno($itemPlan);
            $idProyecto = $this->m_utils->getIdProyectoByItemplan($itemPlan);
            $inputATro = null;
            $array = array('idEstacion_tmp' => $idEstacion,
                'itemplan_tmp' => $itemPlan);

            if ($flg == 1) {
                if ($idEstacion == 2 && $idProyecto == 1) {
                    $inputATro = '<label>Cant. Amplificador</label>
                                        <input id="cant_amplificador" type="text" value="1" class="" />';
                    $data['input'] = 1;
                } else if ($idEstacion == 5 && $idProyecto == 1) {
                    $data['input'] = 2;
                    $inputATro = '<label>Cant. Troba</label>
                                            <input id="cant_troba" type="text" value="1" class="" />';
                }
            }

            $data['inputAmTro'] = $inputATro;
            $this->session->set_userdata($array);

            $jefatura = $this->M_bandeja_itemplan_estacion->getJefaturaByItemPlanPqt($itemPlan);
            if (isset($jefatura)) {
                $data['error'] = EXIT_SUCCESS;
                $data['jefatura'] = $jefatura;
            } else {
                $data['jefatura'] = null;
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

    function ejecutarEnviosFallidosSiom($itemplan, $estacion) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $listaPendientes = $this->m_bandeja_ejecucion->getTramasPendientesEstacionNoEjec($itemplan, $estacion);
            log_message('error', '$listaPendientes:' . print_r($listaPendientes, true));
            foreach ($listaPendientes as $row) {
                $ptr = $row->ptr;
                $id_siom_obra = $row->id_siom_obra;
                $infoItemplan = $this->m_utils->getInfoItemplanLiquidacionSisegosWebPo($ptr, $itemplan);
                if ($infoItemplan == null) {
                    $infoItemplan = $this->m_utils->getInfoItemplanLiquidacionSisegos($ptr, $itemplan);
                }
                log_message('error', '$listaPendientes for');
                $emplazamiento = $this->m_liquidacion->getEmplazamientoIdSiomByidCentral($infoItemplan['idCentral']); //OBTENEMOS EL ID DEZPLAZAMIENTO DE LA TABLA SIOM_NODOS POR EL ID CENTRAL DE LA PO
                if ($emplazamiento['cant'] >= 1) {// SE ENCONTRO NODO
                    log_message('error', 'SE ENCONTRO NODO');
                    $codigo_siom = $this->sendDataToSiom($infoItemplan['idEecc'], $infoItemplan['idEstacion'], $infoItemplan['estacionDesc'], $itemplan, $emplazamiento['empl_id'], $ptr);
                    //$codigo_siom = 7766;
                    //validar si no viene nulo
                    if ($codigo_siom != null) {
                        $dataSiom = array('itemplan' => $itemplan,
                            'idEstacion' => $infoItemplan['idEstacion'],
                            'ptr' => $ptr,
                            'fechaRegistro' => $this->fechaActual(),
                            'idUsuarioRegistro' => $this->session->userdata('idPersonaSession'),
                            'codigoSiom' => $codigo_siom,
                            'ultimo_estado' => 'CREADA',
                            'fecha_ultimo_estado' => $this->fechaActual()
                        );

                        $dataLogPo = array('tabla' => 'Siom',
                            'actividad' => 'Registrar Siom',
                            'itemplan' => $itemplan,
                            'fecha_registro' => $this->fechaActual(),
                            'id_usuario' => $this->session->userdata('idPersonaSession')
                        );

                        $dataEstado = array('codigo_siom' => $codigo_siom,
                            'estado_desc' => 'CREADA',
                            'fechaRegistro' => $this->fechaActual(),
                            'usuario_registro' => $this->session->userdata('usernameSession'),
                            'estado_transaccion' => 1
                        );
                        $data = $this->m_liquidacion->updateSiom($dataSiom, $dataLogPo, $dataEstado, $id_siom_obra);
                        $data['codigo_siom'] = $codigo_siom;
                    } else {
                        throw new Exception('error', 'No se recepciono un codigo siom');
                    }
                } else {
                    log_message('error', 'NO SE ENCONTRO EMPLAZAMIENTO ID PARA ESE NODO');
                    $motivoError = 'NO SE ENCONTRO EMPLAZAMIENTO ID PARA ESE NODO';
                    $estadoError = 4;
                    $dataLogSiom = array(
                        'ptr' => $ptr,
                        'itemplan' => $itemplan,
                        'usuario_envio' => $this->session->userdata('usernameSession'),
                        'fecha_envio' => $this->fechaActual());
                    $dataLogSiom['estado'] = $estadoError; //NODO NO ENCONTRADO = 4
                    $dataLogSiom['mensaje'] = $motivoError;

                    $dataSiom = array('itemplan' => $itemplan,
                        'idEstacion' => $infoItemplan['idEstacion'],
                        'ptr' => $ptr,
                        'fechaRegistro' => $this->fechaActual(),
                        'idUsuarioRegistro' => $this->session->userdata('idPersonaSession'),
                        'codigoSiom' => null,
                        'ultimo_estado' => $motivoError,
                        'fecha_ultimo_estado' => $this->fechaActual()
                    );
                    $this->m_bandeja_ejecucion->updateSiomToNodoNoEncontrado($dataLogSiom, $dataSiom, $id_siom_obra);
                }
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }

    //REPLICA DEL CONTROLER C_LIQUIDACION...
    public function sendDataToSiom($idEECC, $idEstacion, $estacion_desc, $itemplan, $emplazamiento_id, $ptr) {

        try {
            $codigo_siom = null;
            $idEEEC_post = ID_EECC_TELEFONICA_SIOM; //POR DEFECTO TDP
            if ($idEECC == ID_EECC_COBRA) {
                $idEEEC_post = ID_EECC_COBRA_SIOM;
            } else if ($idEECC == ID_EECC_LARI) {
                $idEEEC_post = ID_EECC_LARI_SIOM;
            } else if ($idEECC == ID_EECC_DOMINION) {
                $idEEEC_post = ID_EECC_DOMINION_SIOM;
            } else if ($idEECC == ID_EECC_EZENTIS) {
                $idEEEC_post = ID_EECC_EZENTIS_SIOM;
            } else if ($idEECC == ID_EECC_COMFICA) {
                $idEEEC_post = ID_EECC_COMFICA_SIOM;
            } else if ($idEECC == ID_EECC_LITEYCA) {
                $idEEEC_post = ID_EECC_LITEYCA_SIOM;
            }

            $idSubEspecialidad_post = null;
            if ($idEstacion == ID_ESTACION_FO || $idEstacion == ID_ESTACION_FO_ALIM || $idEstacion == ID_ESTACION_FO_DIST) {
                $idSubEspecialidad_post = ID_SUB_ESPECIALIDAD_FO_SIOM;
                $idFormulario = ID_FORMULARIO_FO_SIOM;
            } else if ($idEstacion == ID_ESTACION_COAXIAL) {
                $idSubEspecialidad_post = ID_SUB_ESPECIALIDAD_COAXIAL_SIOM;
                $idFormulario = ID_FORMULARIO_COAXIAL_SIOM;
            } else if ($idEstacion == ID_ESTACION_OC_FO) {
                $idSubEspecialidad_post = ID_SUB_ESPECIALIDAD_OBRA_CIVIL_SIOM;
                $idFormulario = ID_FORMULARIO_OBRA_CIVIL_SIOM;
            } else if ($idEstacion == ID_ESTACION_OC_COAXIAL) {
                $idSubEspecialidad_post = ID_SUB_ESPECIALIDAD_OBRA_CIVIL_SIOM;
                $idFormulario = ID_FORMULARIO_OBRA_CIVIL_SIOM;
            } else if ($idEstacion == ID_ESTACION_UM || $idEstacion == ID_ESTACION_AC_CLIENTE) {
                $idSubEspecialidad_post = ID_SUB_ESPECIALIDAD_ULTIMA_MILLA;
                $idFormulario = ID_FORMULARIO_ULTIMA_MILLA;
            } else if ($idEstacion == ID_ESTACION_FUENTE) {
                $idSubEspecialidad_post = ID_SUB_ESPECIALIDAD_ENERGIA;
                $idFormulario = ID_FORMULARIO_ENERGIA;
            }

            $dataSend = ['cont_id' => ID_CONTRATO_TELFONICA_SIOM, //CODIGO DE CONTRATO = 21
                'empl_id' => $emplazamiento_id, //CODIGO DE NODO EN BASE A SU TABLA EMPLAZAMIENTO
                'empr_id' => $idEEEC_post, //EEECC 23 = LARI, 31 = DOMINION, 32 = COBRA, 33 = EZENTIS
                'formularios' => [$idFormulario], //idFormulario
                'orse_descripcion' => $itemplan . ' ' . $estacion_desc, //ITEMPLAN_ESTACION
                'orse_fecha_creacion' => $this->fechaActual(), //NOW
                'orse_fecha_solicitud' => $this->fechaActual(), //NOW
                'orse_indisponibilidad' => 'SI', //siempre si
                'orse_tag' => 111, //????
                'orse_tipo' => 'OSGN', //OSGN SIEMPRE
                'sube_id' => $idSubEspecialidad_post, //SUBESPACIALIDAD EN BASE A LA ESPACIALIDAD ESTACION
                'usua_login_creador' => 'WSPO2019',
                'usua_pass_creador' => 'WSPO2019'];

            $dataLogSiom = array('data_send' => json_encode($dataSend),
                'ptr' => $ptr,
                'itemplan' => $itemplan,
                'usuario_envio' => $this->session->userdata('usernameSession'),
                'fecha_envio' => $this->fechaActual());

            //$url = 'http://3.215.20.37:8080/crearOS-1.0/api/v1/CrearOS';//QA
            $url = 'http://54.86.187.150:8080/crearOS-1.0/api/v1/CrearOS'; //PRODUCCION
            $response = $this->m_utils->sendDataToURLTypePUT($url, json_encode($dataSend));
            if ($response->codigo == EXIT_SUCCESS) {//SE CREO LA OS
                $codigo_siom = $response->orseid;
                $dataLogSiom['codigo'] = $response->codigo;
                $dataLogSiom['mensaje'] = $response->mensaje;
                $dataLogSiom['orseid'] = $response->orseid;
                $dataLogSiom['estado'] = 1;
                $this->m_liquidacion->insertLogTramaSiomSoloLog($dataLogSiom);
                log_message('error', 'TODO BIEN!');
            } else {//NO SE CREO LA OS
                $dataLogSiom['codigo'] = $response->codigo;
                $dataLogSiom['mensaje'] = $response->mensaje;
                $dataLogSiom['estado'] = 2;
                log_message('error', 'TODO MAL!');
                $this->m_liquidacion->insertLogTramaSiomSoloLog($dataLogSiom);
            }
        } catch (Exception $e) {//ERROR AL ACCEDER AL SERVIDOR
            log_message('error', 'ERROR EN EL SERVIDOR!!');
            $dataLogSiom['estado'] = 3;
            $this->m_liquidacion->insertLogTramaSiomSoloLog($dataLogSiom);
        }
        return $codigo_siom;
    }

    function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone', 'America/Lima');
        setlocale(LC_TIME, "es_ES", "esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }

    function cambiarDisenoObra($itemPlan, $estadoActual) {
        $this->m_pqt_diseno->actualizarEstadoPlanObraADiseno($itemPlan); //log en licencia
        $arrayDataLog = array(
            'tabla' => 'planobra',
            'actividad' => 'Diseno - Bandeja Ejecucion',
            'itemplan' => $itemPlan,
            'fecha_registro' => $this->fechaActual(),
            'id_usuario' => $this->session->userdata('idPersonaSession'),
            'idEstadoPlan' => ID_ESTADO_EN_LICENCIA
        );
        $this->m_utils->registrarLogPlanObra($arrayDataLog); //log planobra
    }

    function getDisenoEjecutado() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $itemPlan = $this->input->post('itemplan');
            $idEstacion = $this->input->post('idestacion');

            $dataInfo = $this->m_pqt_diseno->getDataDisenoEjecutadoByItemplanEstacion($itemPlan, $idEstacion);
            $data['entidadHTML'] = $this->makeFORMEntidadesInfo($this->m_pqt_diseno->getEntidadesSelectedByEstacionItemplan($itemPlan, $idEstacion));
            $data['error'] = EXIT_SUCCESS;
            $data['info'] = $dataInfo;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

    public function makeFORMEntidadesInfo($listaEntidades) {
        $html = '';
        foreach ($listaEntidades as $row) {
            $html .= '  <div class="col-4">
                            <input type="checkbox" class="custom-control-input" ' . $row->checked . ' disabled>
                            <label for="Ent' . $row->idEntidad . '" >' . $row->desc_entidad . '</label>
                       </div>';
        }
        return $html;
    }

}
