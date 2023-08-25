<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class C_itemplan_creacion_oc extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_itemplan/m_itemplan_creacion_oc');
		$this->load->model('mf_certificacion/m_creacion_oc');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index() {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {
            $data['listaEECC'] = $this->m_utils->getAllEECC();
            $data['listaZonal'] = $this->m_utils->getAllZonalGroup();
            $data['cmbJefatura'] = $this->m_utils->getJefaturaCmb();
            $data['listaSubProy'] = $this->m_utils->getAllSubProyecto();
            $data['listafase'] = $this->m_utils->getAllFase();
            $data['tablaSiom'] = $this->getTablaHojaGestion($this->m_itemplan_creacion_oc->getBandejaSolOC(null, null, null));
            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $permisos = $this->session->userdata('permisosArbolTransporte');
            $result = $this->lib_utils->getHTMLPermisos($permisos, 250, 283, ID_MODULO_ADMINISTRATIVO);
            $data['opciones'] = $result['html'];
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_itemplan/v_itemplan_creacion_oc', $data);
            } else {
                redirect('login', 'refresh');
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    function getTablaHojaGestion($listaHojaGestion) {

        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th></th>              
                            <th>Solicitud</th> 
                            <th>Transaccion</th>
                            <th>EECC</th>
                            <th>Costo Total</th>
                            <th>Ceco</th>
                            <th>Cuenta</th>
                            <th>Area Funcional</th>
                            <th>Proyecto</th>	
                            <th>Subproyecto</th>
                            <th># ItemPlan</th>
							
							<th>Codigo Unico</th>
							<th>Departamento</th>
							<th>Provincia</th>
							<th>Distrito</th>
							<th>Nombre Estacion</th>
							
                            <th>Fecha creacion</th>			
                            <th>Usua Valida</th>
                            <th>Fecha Valida</th>
                            <th>Cesta</th>	
                            <th>Orden Compra</th>								
                            <th>Estado</th>
                            <th>Fecha Cancelado</th>
							
							<th>Contrato Marco</th>
                            <th>Tipo Moneda</th>
                        </tr>
                    </thead>                    
                    <tbody>';
        if ($listaHojaGestion != null) {
            foreach ($listaHojaGestion as $row) {
                $btnEnProceso = '';
                if ($row->estado == 1) {//PENDIENTE
                    if ($row->tipo_solicitud == 1) {
                        $btnEnProceso = '<a data-hg="' . $row->id . '" data-hgtxt="' . $row->codigo_solicitud . '" href="reSolOcMantItemplan?sol=' . $row->codigo_solicitud . '"><i title="Gestionar" style="color:#A4A4A4" class="zmdi zmdi-hc-2x zmdi-time-restore-setting"></i></a>';
                    } else if ($row->tipo_solicitud == 2) {
                        $btnEnProceso = '<a data-hg="' . $row->id . '" data-hgtxt="' . $row->codigo_solicitud . '" onclick="validarEdicionOc($(this))" ><img alt="Editar" height="20px" width="20px" src="public/img/iconos/circle-check-128.png"></a>';
                    } else if ($row->tipo_solicitud == 3) {
                        $btnEnProceso = '<a data-hg="' . $row->id . '" data-codigo_sol="' . $row->codigo_solicitud . '" onclick="openModalCertificacion($(this))"><i title="Ingresar Codigo Cert." style="color:#A4A4A4" class="zmdi zmdi-hc-2x zmdi-money"></i></a>';
                    } else if ($row->tipo_solicitud == 4) {
                        $btnEnProceso = '<a data-hg="' . $row->id . '" data-codigo_sol="' . $row->codigo_solicitud . '" onclick="validarAnulacionOc($(this))"><i title="Validar OC" style="color:#A4A4A4" class="zmdi zmdi-hc-2x zmdi-case-check"></i></a>';
                    }
                } else if ($row->estado == 2) {//ATENDIDO
                    if ($row->path_oc != null) {
                        $btnEnProceso = '<a href="' . $row->path_oc . '" download=""><i class="zmdi zmdi-hc-2x zmdi-download"></i></a>';
                    } else {
                        $btnEnProceso = 'S/E';
                    }
                } else if ($row->estado == 3) {//CANCELADO
                    if ($row->path_oc != null) {
                        $btnEnProceso = '<a href="' . $row->path_oc . '" download=""><i class="zmdi zmdi-hc-2x zmdi-download"></i></a>';
                    } else {
                        $btnEnProceso = 'S/E';
                    }
                }
                $html .= ' <tr>
                            <th style="width:7%">
                               <a data-idhg="' . $row->id . '" data-hg="' . $row->codigo_solicitud . ' "data-ts="' . $row->tipo_solicitud . '" onclick="getPtrByHojaGestion($(this))"><i title="Detalle" style="color:#A4A4A4" class="zmdi zmdi-hc-2x zmdi-search"></i></a> 
                                 ' . $btnEnProceso . '                        
                            </th>
							<td>' . $row->codigo_solicitud . '</td>
							<td>' . $row->tipoSolicitud . '</td>
                                                        <td>' . $row->empresaColabDesc . '</td>
							<td>' . $row->costo_total . '</td>
							<td>' . $row->ceco . '</td>
							<td>' . $row->cuenta . '</td>
							<td>' . $row->area_funcional . '</td>  
							<td>' . $row->proyectoDesc . '</td>							
							<td>' . $row->subProyectoDesc . '</td>
							<td>' . $row->numItemplan . '</td>
							
							<td>'.$row->codigo_unico.'</td>
							<td>'.$row->departamento_matriz.'</td>
							<td>'.$row->provincia_matriz.'</td>
							<td>'.$row->distrito_matriz.'</td>
							<td>'.$row->nom_estacion_matriz.'</td>
							
							<td>' . $row->fecha_creacion . '</td>
							<td>' . $row->nombreCompleto . '</td>
							<td>' . $row->fecha_valida . '</td>
							<td>' . $row->cesta . '</td>
							<td>' . $row->orden_compra . '</td>
							<td>' . $row->estado_sol . '</td>
							<td>' . $row->fecha_cancelacion . '</td>
							
							<td>' . $row->contratoMarco . '</td>
                            <td>' . $row->tipo_moneda . '</td>
                        </tr>';
            }
        }
        $html .= '</tbody>
                </table>';

        return $html;
    }

function opex_desc($id) {
        $desc = $this->m_itemplan_creacion_oc->opex_desc($id);
        return $desc;
    }

    function zonalDesc() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone', 'America/Lima');
        setlocale(LC_TIME, "es_ES", "esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }

    function getPtrsByHojaGestion() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            $hojaGestion = $this->input->post('hg');
            $hojaGestion = trim($hojaGestion);
            $tipo_solicitud = $this->input->post('ts');
            $id_hoja_gestion = $this->input->post('idhg');
            if ($hojaGestion == null) {
                throw new Exception("ERROR Hoja Gestion No valido.");
            }

            $estado = 1;
			$listaEstado =  $this->m_creacion_oc->getPtrsByHojaGestion($hojaGestion);
			$tablaSiom = $this->getTablaListarPtrByHojaGestion($listaEstado, $estado);
				
            // if ($tipo_solicitud == 1) {//crecion oc
                // $datos =  $this->m_creacion_oc->getPtrsByHojaGestion($hojaGestion);

                // $listaEstado = $this->m_itemplan_creacion_oc->getTablaListarPxq($datos[0]['idSubProyecto'], $datos[0]['idEmpresaColab'], $datos[0]['id_jefatura'], $datos[0]['paquetizado_fg'], $datos[0]['itemPlan']);
                // $tablaSiom = $this->getTablaListarPtrByHojaGestion($listaEstado, $estado);
            // } else if ($tipo_solicitud == 2) {//edicion oc
                // $listaEstado = $this->m_itemplan_creacion_oc->getItemOrdenCompraEdicion($hojaGestion);
                // $tablaSiom = $this->getTablaListaItemPlanOCEdicion($listaEstado, $estado);
            // } else if ($tipo_solicitud == 3) {//edicion oc
                // $listaEstado = $this->m_itemplan_creacion_oc->getItemOrdenCompraCerti($hojaGestion);
                // $tablaSiom = $this->getTablaListaItemPlanOCCertificacion($listaEstado, $estado);
            // }
            $data['tablaSiom'] = $tablaSiom;
            /*  $infoPtrs = $this->getTablaListarPtrByHojaGestion($listaEstado, $infoHg);         
              $data['cesta']  = $infoHg['cesta'];
              $data['oc']     = $infoHg['orden_compra'];
              $data['estado'] = $infoHg['estado'];
              $data['nro_cert'] = $infoHg['nro_certificacion'];
              $data['jsonDataFIleValido'] = json_encode(array_map('utf8_encode', $infoPtrs['array'])); */
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function getTablaListarPxq($SubProyecto, $EmpresaColab, $Jefatura, $paquetizado_fg, $itemPlan) {
        $listaPxq = $this->m_itemplan_creacion_oc->getTablaListarPxq($SubProyecto, $EmpresaColab, $Jefatura, $paquetizado_fg, $itemPlan);
        $html = '<table id="data-table3" class="table table-bordered container">
                        <thead class="thead-default">
                            <tr>
                                <th>Codigo</th>
                                <th>Tipo</th>
                                <th>Descripcion</th>
                                <th>Baremo</th> 
				<th>Cantidad</th>
                                <th>Precio</th>
				<th>Total</th>
                            </tr>
                        </thead>
    
                        <tbody>';

        foreach ($listaPxq as $row) {
            $html .= '<tr>
                            <td>' . $row->codigo_m . '</td>
                            <td>' . $row->tipoPreciario . '</td>
                            <td>' . $row->partidaPqt . '</td>
                            <td>' . $row->baremo . '</td>
                            <td>' . $row->cantidad . '</td>	
                            <td>' . $row->costo . '</td>
                            <td>' . $row->form . '</td>
                      </tr>';
        }

        $html .= '</tbody>
                </table>';

        return utf8_decode($html);
    }

    function getTablaListaItemPlanOCCertificacion($listaEstado, $estado) {

        $html = '<table id="data-table2" class="table table-bordered container">
                        <thead class="thead-default">
                            <tr>
                                <!--<th></th>-->
                                <th>ITEMPLAN</th>
                                <th>SUBPROYECTO</th> 
				<th>NOMBRE PROYECTO</th>
                                <th>ZONAL</th>
                                <th>COSTO MO</th>
                                <th>CESTA</th>
                                <th>OC</th>
                                <th>POSICION</th>
                            </tr>
                        </thead>
    
                        <tbody>';
        $indice = 0;
        $array_valido = array();
        foreach ($listaEstado as $row) {
            $html .= '<tr id="tr' . $indice . '">
                            <!--td style="width: 5px;">' . (($estado == 2) ? '<a style="cursor:pointer;" data-indice="' . $indice . '"data-solicitud_oc="' . $row->solicitud_oc . '" onclick="removeTRreservado(this)"><img class="delete_ptr" alt="Eliminar" height="20px" width="20px" src="public/img/iconos/delete.png"></a>' : '') . '</td>-->               
                            <td>' . $row->itemPlan . '</td>
                            <td>' . $row->subProyectoDesc . '</td>
                            <td>' . $row->nombreProyecto . '</td>
                            <td>' . $row->zonalDesc . '</td>
                            <td>' . $row->costo_unitario_mo_certi . '</td>
                            <td>' . $row->cesta . '</td>
                            <td>' . $row->orden_compra . '</td>
                            <td>' . $row->posicion . '</td>
                          </tr>';
            $indice++;
            array_push($array_valido, $row->solicitud_oc . '|' . $row->itemPlan);
        }


        $html .= '</tbody>
                </table>';

        return utf8_decode($html);
    }

    function getTablaListarPtrByHojaGestion($listaEstado, $estado) {
		$html = '<table id="data-table2" class="table table-bordered container">
                        <thead class="thead-default">
                            <tr>
                                <!--<th></th>-->
                                <th>ITEMPLAN</th>
                                <th>SUBPROYECTO</th> 
							    <th>NOMBRE PROYECTO</th>
                                <th>COSTO MO</th>
								<th>COSTO MAT</th>
								<th>CESTA</th>
                                <th>OC</th>
                                <th>POSICION</th>
                            </tr>
                        </thead>
    
                        <tbody>';
        $indice = 0;
        $array_valido = array();
            foreach($listaEstado as $row){
                $html .='<tr id="tr'.$indice.'">
                            <!--td style="width: 5px;">'.(($estado==2) ? '<a style="cursor:pointer;" data-indice="'.$indice.'"data-solicitud_oc="'.$row->solicitud_oc.'" onclick="removeTRreservado(this)"><img class="delete_ptr" alt="Eliminar" height="20px" width="20px" src="public/img/iconos/delete.png"></a>' : '').'</td>-->               
                            <td>'.$row->itemPlan.'</td>
                            <td>'.$row->subProyectoDesc.'</td>
							<td>'.$row->nombreProyecto.'</td>
                            <td>'.$row->limite_costo_mo.'</td>
							<td>'.$row->limite_costo_mat.'</td>	
							<td>'.$row->cesta.'</td>
							<td>'.$row->orden_compra.'</td>
                            <td>'.$row->posicion.'</td>
                          </tr>';
                $indice++;
                array_push($array_valido, $row->solicitud_oc.'|'.$row->itemPlan);
            }
       
         
        $html .='</tbody>
                </table>';       
        
        return  utf8_decode($html);
    }

    function getNumeroMaterial($empresaColabDesc, $jefatura, $tipoPreciario) {
        log_message('error', '··············· MORE FLORES ·················');
        log_message('error', $empresaColabDesc);
        log_message('error', $jefatura);
        log_message('error', $tipoPreciario);
        $NumeroMaterial = $this->m_itemplan_creacion_oc->getNumeroMaterial($empresaColabDesc, $jefatura, $tipoPreciario);
        return $NumeroMaterial;
    }

    function getTablaListaItemPlanOCEdicion($listaEstado, $estado) {

        $html = '<table id="data-table2" class="table table-bordered container">
                        <thead class="thead-default">
                            <tr>
                                <!--<th></th>-->
                                <th>ITEMPLAN</th>
                                <th>SUBPROYECTO</th> 
				<th>NOMBRE PROYECTO</th>
                                <th>ZONAL</th>
                                <th>COSTO FINAL</th>
				<th>CESTA</th>
                                <th>OC</th>
                                <th>POSICION</th>
                            </tr>
                        </thead>
    
                        <tbody>';
        $indice = 0;
        //$array_valido = array();
        foreach ($listaEstado as $row) {
            $html .= '<tr id="tr' . $indice . '">
                            <!--td style="width: 5px;">' . (($estado == 2) ? '<a style="cursor:pointer;" data-indice="' . $indice . '"data-solicitud_oc="' . $row->solicitud_oc . '" onclick="removeTRreservado(this)"><img class="delete_ptr" alt="Eliminar" height="20px" width="20px" src="public/img/iconos/delete.png"></a>' : '') . '</td>-->               
                            <td>' . $row->itemPlan . '</td>
                            <td>' . $row->subProyectoDesc . '</td>
                            <td>' . $row->nombreProyecto . '</td>
                            <td>' . $row->zonalDesc . '</td>
                            <td>' . $row->costo_devolucion . '</td>
                            <td>' . $row->cesta . '</td>
                            <td>' . $row->orden_compra . '</td>
                            <td>' . $row->posicion . '</td>
                          </tr>';
            $indice++;
            //array_push($array_valido, $row->solicitud_oc.'|'.$row->itemPlan);
        }


        $html .= '</tbody>
                </table>';

        return utf8_decode($html);
    }

    function filtrarTabaCOC() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            $solicitud = ($this->input->post('soli') == '') ? null : $this->input->post('soli');
            $itemplan = ($this->input->post('item') == '') ? null : $this->input->post('item');
            $estado = ($this->input->post('estado') == '') ? null : $this->input->post('estado');
            $data['tablaBandejaHG'] = $this->getTablaHojaGestion($this->m_itemplan_creacion_oc->getBandejaSolOC($solicitud, $itemplan, $estado));
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function validarSolicitudEdicionOC() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {

            $codigo_solicitud = $this->input->post('txtOC');
            $itemplan = $this->m_itemplan_creacion_oc->getItemplan($codigo_solicitud);
            $montoDev = $this->m_itemplan_creacion_oc->getMontoDev($codigo_solicitud);
            $idPersona = $this->session->userdata('idPersonaSession');
            if ($idPersona != null) {
                $arrayData = array(
                    'estado' => 2,
                    'usuario_valida' => $this->session->userdata('idPersonaSession'),
                    'fecha_valida' => $this->fechaActual());

                $arrayDataItem = array(
                    'estado_oc_dev' => 'ATENDIDO',
                    'costo_unitario_mo' => $montoDev
                );

                $data = $this->m_itemplan_creacion_oc->update_solicitud_oc($codigo_solicitud, $arrayData);
                if ($data['error'] = EXIT_SUCCESS) {
                    $this->m_itemplan_creacion_oc->update_itemplan($arrayDataItem, $itemplan);
                }
            } else {
                throw new Exception('Su sesion a terminado, Refresque la pantalla y vuelva a iniciar Sesion.');
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    /*     * *antiguo*** */

    function setHojaGestionCertificado() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {

            $idHojaGestion = $this->input->post('id_hg');
            $cesta = $this->input->post('txtCestaCa');
            $orden_compra = $this->input->post('txtOrdenCompra');
            $nro_certificacion = $this->input->post('txtNroCertificacion');
            $idPersona = $this->session->userdata('idPersonaSession');
            if ($idPersona != null) {
                $infoHg = $this->m_itemplan_creacion_oc->getBolsaHgDataByHG($idHojaGestion);
                if ($infoHg['estado'] == 3) {
                    /*                     * FALTA CERTIFICAR LAS POS AGREGARLES SU OC Y NRO CERTIFICACION*** */
                    $jsonDataFile = $this->input->post('jsonDataFile');
                    $arrayFile = json_decode($jsonDataFile);
                    if ($arrayFile != null) {//si al menos viene 1 ptr en la hoja de gestion.
                        $arrayUpCertificacionMo = array();
                        $arrayUpPlanobraPo = array();
                        $arrayUpDetallePlan = array();
                        $arraylogPlanobraPoData = array();
                        foreach ($arrayFile as $row) {
                            if ($row != null) {
                                $row_split = explode('|', $row);
                                log_message('error', print_r($row_split, true));
                                $ptr = $row_split[0];
                                $itemplan = $row_split[1];
                                /*                                 * actualizamos certificacion_mo* */
                                $dataCMO = array();
                                $dataCMO['ptr'] = $ptr;
                                $dataCMO['orden_compra'] = $orden_compra;
                                $dataCMO['nro_certificacion'] = $nro_certificacion;
                                $dataCMO['estado'] = CERTIFICACION_MO_CON_ORDEN_COMPRA;
                                $dataCMO['usua_reg_oc'] = $this->session->userdata('userSession');
                                $dataCMO['fec_reg_oc'] = date("Y-m-d");
                                array_push($arrayUpCertificacionMo, $dataCMO);

                                /*                                 * pasamos a certificado las po* */
                                $dataUpPlanobraPo = array();
                                $dataUpPlanobraPo['estado_po'] = 6;
                                $dataUpPlanobraPo['codigo_po'] = $ptr;
                                array_push($arrayUpPlanobraPo, $dataUpPlanobraPo);

                                /*                                 * registro en log_planobra_po* */
                                $logPlanobraPoData = array();
                                $logPlanobraPoData['codigo_po'] = $ptr;
                                $logPlanobraPoData['itemplan'] = $itemplan;
                                $logPlanobraPoData['idUsuario'] = $idPersona;
                                $logPlanobraPoData['idPoestado'] = 6;
                                $logPlanobraPoData['fecha_registro'] = $this->fechaActual();
                                $logPlanobraPoData['controlador'] = 'Gestionar Hoja Gestion';
                                array_push($arraylogPlanobraPoData, $logPlanobraPoData);

                                /*                                 * detalleplan* */
                                $dataUpDetallePlan = array();
                                $dataUpDetallePlan['oc'] = $orden_compra;
                                $dataUpDetallePlan['ncert'] = $nro_certificacion;
                                $dataUpDetallePlan['poCod'] = $ptr;
                                array_push($arrayUpDetallePlan, $dataUpDetallePlan);
                            }
                        }

                        /*                         * datos de la hoja de gestion* */
                        $dataHojagestion = array('cesta' => $cesta,
                            'estado' => 4,
                            'orden_compra' => $orden_compra,
                            'nro_certificacion' => $nro_certificacion,
                            'usuario_cetificacion' => $idPersona,
                            'fecha_certificacion' => $this->fechaActual());
                        /*
                          log_message('error', '$arrayUpCertificacionMo:'.print_r($arrayUpCertificacionMo,true));
                          log_message('error', '$arrayUpPlanobraPo:'.print_r($arrayUpPlanobraPo,true));
                          log_message('error', '$arraylogPlanobraPoData:'.print_r($arraylogPlanobraPoData,true));
                          log_message('error', '$arrayUpDetallePlan:'.print_r($arrayUpDetallePlan,true));
                          log_message('error', '$dataHojagestion:'.print_r($dataHojagestion,true));
                         */
                        log_message('error', 'c a:' . print_r($data, true));
                        $data = $this->m_itemplan_creacion_oc->updateHojaGestionCertificacion($arraylogPlanobraPoData, $arrayUpCertificacionMo, $arrayUpPlanobraPo, $arrayUpDetallePlan, $dataHojagestion, $idHojaGestion);
                        log_message('error', 'c:' . print_r($data, true));
                    } else {
                        throw new Exception('La Hoja de Gestion debe tener almenos 1 PO para Certificarla.');
                    }
                } else {
                    throw new Exception('Estado de Hoja No Valida.');
                }
            } else {
                throw new Exception('Su sesion a terminado, Refresque la pantalla y vuelva a iniciar Sesion.');
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone', 'America/Lima');
        setlocale(LC_TIME, "es_ES", "esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }

    public function makeCSVHojaGestionMO() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            //cabeceras para descarga
            header('Content-Type: application/octet-stream');
            header("Content-Transfer-Encoding: Binary");
            header("Content-disposition: attachment; filename=\"Detalle_hoja_gestion.csv\"");
            //preparar el wrapper de salida
            $outputBuffer = fopen("php://output", 'w');
            $detalleplan = $this->m_itemplan_creacion_oc->getDataToExcelReport();
            if (count($detalleplan->result()) > 0) {
                fputcsv($outputBuffer, explode('\t', "PROYECTO" . "\t" . "SUBPROYECTO" . "\t" . "EECC" . "\t" . "AREA" . "\t" . "PEP 1" . "\t" . "PEP 2" . "\t" . "ITEMPLAN" . "\t" . "PTR" . "\t" . "MONTO" . "\t" . "CESTA" . "\t" . "HOJA GESTION" . "\t" . "ORDEN COMPRA" . "\t" . "NRO CERTIFICACION" . "\t" . "PROMOTOR" . "\t" . "ESTADO HOJA GESTION"));
                foreach ($detalleplan->result() as $row) {
                    fputcsv($outputBuffer, explode('\t', utf8_decode($row->proyectoDesc . "\t" . $row->subProyectoDesc . "\t" . $row->empresaColabDesc . "\t" . $row->areaDesc . "\t" . $row->pep1 . "\t" . $row->pep2 . "\t" . $row->itemplan . "\t" .
                                            $row->ptr . "\t" . $row->monto_mo . "\t" . $row->cesta . "\t" . $row->hoja_gestion . "\t" . $row->orden_compra . "\t" . $row->nro_certificacion . "\t" . $row->tipoObraDesc . "\t" . $row->tipoEstadoDesc)));
                }
            }
            fclose($outputBuffer);
            $data['error'] = EXIT_SUCCESS;
            //cerramos el wrapper
        } catch (Exception $e) {
            $data['msj'] = 'Error interno, al crear archivo detalleplan';
        }
        return $data;
    }

    function removeOnePtrFromHojaGestion() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {

            $ptr = $this->input->post('ptr');
            $idHojaGestion = $this->input->post('id_hg');
            $arrayData = array('ptr' => $ptr,
                'hoja_gestion' => null,
                'estado_validado' => 0,
                'usua_remove_hg' => $this->session->userdata('idPersonaSession')
            );
            $data = $this->m_itemplan_creacion_oc->updatePtrFromHojaGestion($ptr, $arrayData, $idHojaGestion);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function TablaListarPxq() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            $SubProyecto = $this->input->post('SubProyecto');
            $EmpresaColab = $this->input->post('EmpresaColab');
            $Jefatura = $this->input->post('Jefatura');
            $paquetizado_fg = $this->input->post('paquetizado_fg');
            $itemPlan = $this->input->post('itemPlan');
            $data['tablaPxq'] = $this->getTablaListarPxq($SubProyecto, $EmpresaColab, $Jefatura, $paquetizado_fg, $itemPlan);
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    ///////

    function certificarSolicitudOc() {_log("aaaaaaa");
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
			$this->db->trans_begin();
			
            $codigo_solicitud = $this->input->post('codigo_solicitud');
            $codigo_certifica = $this->input->post('codigo_certifica');
            $idPersona = $this->session->userdata('idPersonaSession');
			_log("codigo: ".$codigo_solicitud);
            if ($idPersona != null) {
				$dataItemXsol = $this->m_utils->getDataItemplanByCodOcCerti($codigo_solicitud);
				
                $arrayData = array(	'estado' => 2,
									'usuario_valida' => $this->session->userdata('idPersonaSession'),
									'fecha_valida' => $this->fechaActual(),
									'codigo_certificacion' => $codigo_certifica);
				if($dataItemXsol['idEstadoPlan'] == 10) {
					$dataPlanObra = array(	'itemplan'            => $dataItemXsol['itemplan'],
											'solicitud_oc_certi'  => $codigo_solicitud,
											'estado_oc_certi'	  => 'ATENDIDO',
											'fecha_certifica'	  => $this->fechaActual(),
											'trunco_situacion'    => 23,
											'usu_upd'             => $idPersona, 
											'fecha_upd'           => $this->fechaActual(),
											'descripcion'         => 'CERTIFICADO OC-TRUNCO');
				} else {
					$dataPlanObra = array('itemplan'            => $dataItemXsol['itemplan'],
										  'solicitud_oc_certi'	=> $codigo_solicitud,
										  'estado_oc_certi'		=> 'ATENDIDO',
										  'fecha_certifica'		=> $this->fechaActual(),
										  'idEstadoPlan'        => 23,
										  'usu_upd'             => $idPersona, 
										  'fecha_upd'           => $this->fechaActual(),
										  'descripcion'         => 'CERTIFICADO OC');
				}
				
				$countValida = $this->m_itemplan_creacion_oc->getCountPoByItemplanAndEstado($dataItemXsol['itemplan'], 5);
				_log("ENTROooooo");
				$data = $this->m_itemplan_creacion_oc->update_solicitud_oc_certi_opex($codigo_solicitud, $arrayData, $dataPlanObra, $idPersona, $countValida);
				_log(print_r($data, true));
				if($data['error'] == EXIT_ERROR) {
					throw new Exception($data['msj']);
				}
            } else {
                throw new Exception('Su sesion a terminado, Refresque la pantalla y vuelva a iniciar Sesion.');
            }
			
			$this->db->trans_commit();
        } catch (Exception $e) {
			$this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

}
