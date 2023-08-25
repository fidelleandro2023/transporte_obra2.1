<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_creacion_oc extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_certificacion/m_creacion_oc');
		$this->load->model('mf_certificacion/m_registro_oc_solicitud_masivo_dev');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
		$this->load->library('zip');
    }

    public function index()
    {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {
            $data['listaEECC']     = $this->m_utils->getAllEECC();
            $data['listaZonal']    = $this->m_utils->getAllZonalGroup();
            $data['cmbJefatura']   = $this->m_utils->getJefaturaCmb();
            $data['listaSubProy']  = $this->m_utils->getAllSubProyecto();
            $data['listafase']     = $this->m_utils->getAllFase();
            #$data['tablaSiom']     = $this->getTablaHojaGestion($this->m_creacion_oc->getBandejaSolOC('','',''));      
            $data['tablaSiom']     = $this->getTablaHojaGestion(null);
            $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
            $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
            $permisos =  $this->session->userdata('permisosArbolTransporte');
            $result = $this->lib_utils->getHTMLPermisos($permisos, 250, 256, ID_MODULO_ADMINISTRATIVO);
            $data['opciones'] = $result['html'];
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_certificacion/v_creacion_oc', $data);
            } else {
                redirect('login', 'refresh');
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    function getTablaHojaGestion($listaHojaGestion)
    {
        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th></th>              
                            <th>Solicitud</th> 
							<th>Transaccion</th> 
							<th>Proyecto</th>	
							<th>Subproyecto</th>
                            <th>EECC</th>
							<th>Gestor</th>
							<th># Itemplan</th>
							<th>Costo Total</th>
							<th>Costo Sap</th>							
                            <th>Plan</th>
							
							<th>Contrato Padre</th>
                            <th>Numero de CM</th>
                            <th>Moneda</th>
							
							<th>PEP 1</th>
							<th>PEP 2</th>
							
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
							<th>Cod. Certificacion</th>							
                            <th>Estado</th>
							<th>Fecha Cancelado</th>
							<th>Situacion</th>
                        </tr>
                    </thead>                    
                    <tbody>';
        if ($listaHojaGestion != null) {
            $btnPdf = null;
            $flgPdf = 0;
            $btnEvidencia = null;
            $btnDesactivar = null;
            foreach ($listaHojaGestion as $row) {
                $btnEnProceso = '';
				$ubicacion = 'uploads/evidencia_fotos/'.$row->itemplan;
				$ubicacionItemExp = 'uploads/evidencia_fotos/'.$row->itemplan;

                $style_pep_upd = null;
                if($row->flg_cambio_pep == 1) {
                    $style_pep_upd = 'blue';
                }

				if(is_dir($ubicacion) || is_dir($ubicacionItemExp)) {
					$btnEvidencia = '<a data-hg="" data-itemplan="' . $row->itemplan . '" onclick="zipItemPlan($(this))">
										<i title="Descargar Evidencia" style="color:#A4A4A4" class="zmdi zmdi-hc-2x zmdi-folder-outline"></i>
									</a>';
				} else {
					$btnEvidencia = '  <a data-itemplan ="' . $row->itemplan . '" onclick="abrirEvidenciaFotos($(this));">
											<i style="color:#A4A4A4" title="evidencia" class="zmdi zmdi-hc-2x zmdi-folder"></i>
										</a>';
				}
				
                
                if ($row->estado == 1) { //PENDIENTE
                    $btnDesactivar = '	<a data-hg="" data-itemplan="' . $row->itemplan . '" data-codigo-solicitud="' . $row->codigo_solicitud . '" onclick="openModalQuiebre($(this))">
												<i title="Desactivar Solicitud." style="color:#A4A4A4" class="zmdi zmdi-hc-2x zmdi-close-circle"></i>
											</a>';
                    if ($row->tipo_solicitud == 1) {
                        $btnEnProceso = '<a data-hg="" data-hgtxt="' . $row->codigo_solicitud . '" href="reSolOc?sol=' . $row->codigo_solicitud . '"><i title="buscar" style="color:#A4A4A4" class="zmdi zmdi-hc-2x zmdi-time-restore-setting"></i></a>';
                    } else if ($row->tipo_solicitud == 2) {
                        $btnEnProceso = '<a data-hg="" data-hgtxt="' . $row->codigo_solicitud . '" onclick="validarEdicionOc(this)" ><img alt="Editar" height="20px" width="20px" src="public/img/iconos/circle-check-128.png"></a>';
                    } else if ($row->tipo_solicitud == 3) {
                        $btnEnProceso = '<a data-hg="" data-codigo_sol="' . $row->codigo_solicitud . '" onclick="openModalCertificacion($(this))">
												<i title="Ingresar Codigo Cert." style="color:#A4A4A4" class="zmdi zmdi-hc-2x zmdi-money"></i>
											</a>';
                        /*$btnPdf = '<a data-hg="" data-itemplan="'.$row->itemplan.'" onclick="zipItemPlan($(this))">
                                        <i title="Descargar Evidencia" style="color:#A4A4A4" class="zmdi zmdi-hc-2x zmdi-folder-outline"></i>
                                    </a>';*/
                    } else if ($row->tipo_solicitud == 4) {
                        $btnEnProceso = '<a data-hg="" data-codigo_sol="' . $row->codigo_solicitud . '" onclick="validarAnulacionOc($(this))">
												<i title="Validar OC" style="color:#A4A4A4" class="zmdi zmdi-hc-2x zmdi-case-check"></i>
											</a>';
                    }
                } else if ($row->estado == 2) { //ATENDIDO
                    if ($row->path_oc != null) {
                        $btnEnProceso = '<a href="' . $row->path_oc . '" download=""><i class="zmdi zmdi-hc-2x zmdi-download"></i></a>';
                    } else {
                        $btnEnProceso = 'S/E';

                        if ($row->flg_pdf_edi == 1) {
                            $flgPdf = 1;
                        } else {
                            if ($row->tipo_solicitud == 3 || $row->tipo_solicitud == 1) {
                                $flgPdf = 1;
                            } else {
                                $flgPdf = 0;
                            }
                        }
                    }
                } else if ($row->estado == 3) { //CANCELADO
                    if ($row->path_oc != null) {
                        $btnEnProceso = '<a href="' . $row->path_oc . '" download=""><i class="zmdi zmdi-hc-2x zmdi-download"></i></a>';
                    } else {
                        $btnEnProceso = 'S/E';
                    }
                } else if (($row->estado == 4 || $row->estado == 5) && $row->flg_presupuesto_oc == 1) {
                    $btnEnProceso = '<i class="zmdi zmdi-hc-2x zmdi-alert-circle-o" title="SOl. EDIC. SIN PRESUPUESTO" style="cursor:pointer;color:red" onclick="alertaSinPresupuestoCerti();"></i>';
                } else if (($row->estado == 4 || $row->estado == 5) && ($row->flg_presupuesto_oc == null && $row->flg_presupuesto_oc == '')) {
                    $btnDesactivar = '	<a data-hg="" data-itemplan="' . $row->itemplan . '" data-codigo-solicitud="' . $row->codigo_solicitud . '" onclick="openModalQuiebre($(this))">
												<i title="Desactivar Solicitud." style="color:#A4A4A4" class="zmdi zmdi-hc-2x zmdi-close-circle"></i>
											</a>';
                }
				
				if($row->tipo_solicitud == 3 && $row->count_config_firma > 0) {
					$btnPdf = '<a donwload href="getActaCertificacion?codigo_solicitud=' . $row->codigo_solicitud . '" target="_blank" title="ver pdf acta"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/iconpdf.svg"></a>';
				} else if($row->tipo_solicitud == 3 && $row->estado == 2) {
					$btnPdf = '<a donwload href="getActaCertificacion?codigo_solicitud=' . $row->codigo_solicitud . '" target="_blank" title="ver pdf acta"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/iconpdf.svg"></a>';
				}
                // if ($flgPdf == 1) {
                    // $btnPdf = '<a href="getActaCertificacion?codigo_solicitud=' . $row->codigo_solicitud . '" target="_blank" title="ver pdf acta"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/iconpdf.svg"></a>';
                // }
				
				$btnEvidenciaCancel = null;
				if($row->ruta_cancelacion != null) {
					$btnEvidenciaCancel = ' <a href="'.$row->ruta_cancelacion.'" title="Evidencia Cancelacion" download>
												<i class="zmdi zmdi-hc-2x zmdi-case-download"></i>
											</a>';
				}

                $html .= ' <tr>
                            <th style="width:7%">
                               <a data-hg="' . $row->codigo_solicitud . ' "data-ts="' . $row->tipo_solicitud . '" onclick="getPtrByHojaGestion(this)"><i title="buscar" style="color:#A4A4A4" class="zmdi zmdi-hc-2x zmdi-search"></i></a> 
                                 ' . $btnEnProceso . $btnPdf . $btnEvidencia . ' ' . $btnDesactivar . ' '.$btnEvidenciaCancel.'                     
                            </th>
							<td>' . $row->codigo_solicitud . '</td>
							<td>' . $row->tipoSolicitud . '</td>  
							<td>' . $row->proyectoDesc . '</td>							
							<td>' . $row->subProyectoDesc . '</td>
                            <td>' . $row->empresaColabDesc . '</td>
							<td>' . $row->gestor . '</td>
							<td>' . $row->itemplan . '</td>
							<td>' . $row->costo_total . '</td>
							<td>' . $row->costo_sap . '</td>
                            <td>' . $row->plan . '</td>
							
							<td>' . $row->contrato_padre . '</td>
                            <td>' . $row->contrato_marco . '</td>
                            <td>' . $row->tipo_moneda . '</td>
							
							<td style="color:'.$style_pep_upd.'">' . $row->pep1 . '</td>
							<td style="color:'.$style_pep_upd.'">' . $row->pep2 . '</td>
							
							<td>' . $row->codigo_unico . '</td>
							<td>' . $row->departamento_matriz . '</td>
							<td>' . $row->provincia_matriz . '</td>
							<td>' . $row->distrito_matriz . '</td>
							<td>' . $row->nom_estacion_matriz . '</td>
							
                            <td>' . $row->fecha_creacion . '</td>
                            <td>' . $row->nombreCompleto . '</td>
							<td>' . $row->fecha_valida . '</td>
							<td>' . $row->cesta . '</td>
							<td>' . $row->orden_compra . '</td>
							<td>' . $row->codigo_certificacion . '</td>
							<td>' . $row->estado_sol . '</td>
							<td>' . $row->fecha_cancelacion . '</td>
							<td>' . $row->estatus_solicitud . '</td>
                        </tr>';
            }
        }
        $html .= '</tbody>
                </table>';

        return $html;
    }

    function getPtrsByHojaGestion()
    {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            $hojaGestion     = $this->input->post('hg');
            $tipo_solicitud = $this->input->post('ts');
            $id_hoja_gestion = $this->input->post('idhg');
            if ($hojaGestion == null) {
                throw new Exception("ERROR Hoja Gestion No valido.");
            }

            $estado = 1;

            $listaEstado = $this->m_creacion_oc->getPtrsByHojaGestion($hojaGestion);
            list($tablaSiom, $itemplan) =  $this->getTablaListarPtrByHojaGestion($listaEstado, $estado);
            $tablaDetallePo = $this->getTablaDetallePO($itemplan);

            // if($tipo_solicitud == 1){//crecion oc
            // $listaEstado = $this->m_creacion_oc->getPtrsByHojaGestion($hojaGestion);
            // $tablaSiom =  $this->getTablaListarPtrByHojaGestion($listaEstado, $estado);
            // }else if($tipo_solicitud == 2){//edicion oc
            // $listaEstado = $this->m_creacion_oc->getItemOrdenCompraEdicion($hojaGestion);
            // $tablaSiom =  $this->getTablaListaItemPlanOCEdicion($listaEstado, $estado);
            // }else if($tipo_solicitud == 3){//edicion oc
            // $listaEstado = $this->m_creacion_oc->getItemOrdenCompraCerti($hojaGestion);
            // $tablaSiom =  $this->getTablaListaItemPlanOCCertificacion($listaEstado, $estado);
            // }else if($tipo_solicitud == 4){//anulacionPos oc
            // $listaEstado = $this->m_creacion_oc->getItemOrdenCompraAnulaPosOC($hojaGestion);
            // $tablaSiom =  $this->getTablaListaItemPlanOCAnulaPos($listaEstado, $estado);
            // }
            $data['tablaSiom'] = $tablaSiom;
            $data['tablaDetallePo'] = $tablaDetallePo;
            /*  $infoPtrs = $this->getTablaListarPtrByHojaGestion($listaEstado, $infoHg);         
            $data['cesta']  = $infoHg['cesta'];
            $data['oc']     = $infoHg['orden_compra'];
            $data['estado'] = $infoHg['estado'];
            $data['nro_cert'] = $infoHg['nro_certificacion']; 
            $data['jsonDataFIleValido'] = json_encode(array_map('utf8_encode', $infoPtrs['array']));*/
            $data['error']  = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function getTablaListaItemPlanOCAnulaPos($listaEstado, $estado)
    {

        $html = '<table id="data-table2" class="table table-bordered container">
                        <thead class="thead-default">
                            <tr>
                                <th>ITEMPLAN</th>
                                <th>SUBPROYECTO</th> 
							    <th>NOMBRE PROYECTO</th>
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
                            <td>' . $row->itemplan . '</td>
                            <td>' . $row->subProyectoDesc . '</td>
							<td>' . $row->nombreProyecto . '</td>
                            <td>' . $row->costo_unitario_mo_anula_pos . '</td>
							<td>' . $row->cesta . '</td>
							<td>' . $row->orden_compra . '</td>
                            <td>' . $row->posicion . '</td>
                          </tr>';
            $indice++;
            array_push($array_valido, $row->codigo_solicitud_oc . '|' . $row->itemplan);
        }


        $html .= '</tbody>
                </table>';

        return  utf8_decode($html);
    }

    function getTablaListaItemPlanOCCertificacion($listaEstado, $estado)
    {

        $html = '<table id="data-table2" class="table table-bordered container">
                        <thead class="thead-default">
                            <tr>
                                <!--<th></th>-->
                                <th>ITEMPLAN</th>
                                <th>SUBPROYECTO</th> 
							    <th>NOMBRE PROYECTO</th>
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

        return  utf8_decode($html);
    }

    function getTablaListarPtrByHojaGestion($listaEstado, $estado)
    {

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
        foreach ($listaEstado as $row) {
            $itemplan = $row->itemPlan;
            $html .= '<tr id="tr' . $indice . '">
                            <!--td style="width: 5px;">' . (($estado == 2) ? '<a style="cursor:pointer;" data-indice="' . $indice . '"data-solicitud_oc="' . $row->solicitud_oc . '" onclick="removeTRreservado(this)"><img class="delete_ptr" alt="Eliminar" height="20px" width="20px" src="public/img/iconos/delete.png"></a>' : '') . '</td>-->               
                            <td>' . $row->itemPlan . '</td>
                            <td>' . $row->subProyectoDesc . '</td>
							<td>' . $row->nombreProyecto . '</td>
                            <td>' . $row->limite_costo_mo . '</td>
							<td>' . $row->limite_costo_mat . '</td>	
							<td>' . $row->cesta . '</td>
							<td>' . $row->orden_compra . '</td>
                            <td>' . $row->posicion . '</td>
                          </tr>';
            $indice++;
            array_push($array_valido, $row->solicitud_oc . '|' . $row->itemPlan);
        }


        $html .= '</tbody>
                </table>';

        return  array(utf8_decode($html), $itemplan);
    }

    function getTablaListaItemPlanOCEdicion($listaEstado, $estado)
    {

        $html = '<table id="data-table2" class="table table-bordered container">
                        <thead class="thead-default">
                            <tr>
                                <!--<th></th>-->
                                <th>ITEMPLAN</th>
                                <th>SUBPROYECTO</th> 
							    <th>NOMBRE PROYECTO</th>
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

        return  utf8_decode($html);
    }

    function getTablaDetallePO($itemplan)
    {
        $htmlPartidas = null;
        $total = 0;
        $detallePODisenio = $this->m_utils->getDetallePoTransp(null, $itemplan);
        $htmlPartidas .= '<table id="tb_detalle_po" class="table table-bordered">
						<thead class="thead-default">
							<tr>
								<th>C&Oacute;DIGO PARTIDA</th>
								<th>PARTIDA</th>
								<th>BAREMO</th>
								<th>COSTO</th>
								<th>CANTIDAD INICIAL</th>
								<th>CANTIDAD FINAL</th>
								<th>TOTAL</th>
							</tr>
						</thead>
						<tbody>';

        foreach ($detallePODisenio as $row) {
            $total = $row['total'] + $total;
            $htmlPartidas .= ' <tr>
											<th>' . $row['codigo'] . '</th>
											<td>' . utf8_decode($row['descPartida']) . '</td>
											<td>' . $row['baremo'] . '</td>
											<td>' . $row['precio'] . '</td>
											<td>' . $row['cantidad_inicial'] . '</td>
											<td>' . $row['cantidad_final'] . '</td>
											<td>' . $row['total'] . '</td>
										</tr>';
        }
        $htmlPartidas .= '</tbody>
						</table>
						<a>TOTAL: ' . $total . '</a>';
        return $htmlPartidas;
    }

    function filtrarTabaCOC()
    {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            $solicitud      = ($this->input->post('soli') == '')          ? null : $this->input->post('soli');
            $itemplan       = ($this->input->post('item') == '')          ? null : $this->input->post('item');
            $estado         = ($this->input->post('estado') == '')      ? null : $this->input->post('estado');
            $data['tablaBandejaHG'] = $this->getTablaHojaGestion($this->m_creacion_oc->getBandejaSolOC($solicitud, $itemplan, $estado));
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function validarSolicitudEdicionOC()
    {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            $this->db->trans_begin();

            $codigo_solicitud   = $this->input->post('txtOC');
            $idPersona          = $this->session->userdata('idPersonaSession');
            $costoSap           = $this->input->post('costoSap');

            if ($costoSap == null || $costoSap == '' || $costoSap == 0) {
                throw new Exception('Debe ingresar el costo sap');
            }

            if (!is_numeric($costoSap)) {
                throw new Exception('Debe ingresar el costo correctamente.');
            }

            if ($idPersona != null) {
				$infoSol = $this->m_registro_oc_solicitud_masivo_dev->getInfoSolicitudOCCreaByCodigo(trim($codigo_solicitud));
				
				if($infoSol['tipo_moneda'] != 'USD') {
					$costo_resta = abs($infoSol['costo_unitario_mo'] - $costoSap);

					if ($costo_resta > 1) {
						throw new Exception('LA DIFERENCIA DEL COSTO SAP SUPERA A 1 AL DE LA WEB PO.');
					}
				}
				
				
				$arrayData = array(
                    'estado'           => 2,
                    'usuario_valida'   => $this->session->userdata('idPersonaSession'),
                    'fecha_valida'     => $this->fechaActual(),
                    'costo_sap'        => $costoSap
                );


                $dataCerti = $this->m_creacion_oc->getSolCertPndEdicionFirma($codigo_solicitud);

                if ($dataCerti != null) {
                    $cod_solicitud_certi = $dataCerti['codigo_solicitud'];
                    $estadoCerti         = $dataCerti['estado_certi'];
                    $dataSolCert = array(
											'codigo_solicitud' => $cod_solicitud_certi,
											'estado'  		   =>  $estadoCerti //pnd de acta
										);

                    $data = $this->m_creacion_oc->update_solicitud_ocV2($codigo_solicitud, $arrayData, $dataSolCert);

                } else {
                    $data = $this->m_creacion_oc->update_solicitud_oc($codigo_solicitud, $arrayData);
                }

                if ($data['error'] == EXIT_SUCCESS) {
                    $data = $this->m_creacion_oc->actualizarMontoSap($codigo_solicitud, $costoSap);

                    if ($data['error'] == EXIT_ERROR) {
                        throw new Exception($data['msj']);
                    }
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
    /***antiguo****/





    function setHojaGestionCertificado()
    {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {

            $idHojaGestion       = $this->input->post('id_hg');
            $cesta               = $this->input->post('txtCestaCa');
            $orden_compra        = $this->input->post('txtOrdenCompra');
            $nro_certificacion   = $this->input->post('txtNroCertificacion');
            $idPersona           = $this->session->userdata('idPersonaSession');
            if ($idPersona != null) {
                $infoHg = $this->m_creacion_oc->getBolsaHgDataByHG($idHojaGestion);
                if ($infoHg['estado'] == 3) {
                    /**FALTA CERTIFICAR LAS POS AGREGARLES SU OC Y NRO CERTIFICACION****/
                    $jsonDataFile   = $this->input->post('jsonDataFile');
                    $arrayFile = json_decode($jsonDataFile);
                    if ($arrayFile != null) { //si al menos viene 1 ptr en la hoja de gestion.
                        $arrayUpCertificacionMo = array();
                        $arrayUpPlanobraPo = array();
                        $arrayUpDetallePlan = array();
                        $arraylogPlanobraPoData = array();
                        foreach ($arrayFile as $row) {
                            if ($row != null) {
                                $row_split = explode('|', $row);
                                log_message('error', print_r($row_split, true));
                                $ptr        = $row_split[0];
                                $itemplan   = $row_split[1];
                                /**actualizamos certificacion_mo**/
                                $dataCMO = array();
                                $dataCMO['ptr']                 = $ptr;
                                $dataCMO['orden_compra']        = $orden_compra;
                                $dataCMO['nro_certificacion']   = $nro_certificacion;
                                $dataCMO['estado']              = CERTIFICACION_MO_CON_ORDEN_COMPRA;
                                $dataCMO['usua_reg_oc']         = $this->session->userdata('userSession');
                                $dataCMO['fec_reg_oc']          = date("Y-m-d");
                                array_push($arrayUpCertificacionMo, $dataCMO);

                                /**pasamos a certificado las po**/
                                $dataUpPlanobraPo = array();
                                $dataUpPlanobraPo['estado_po'] = 6;
                                $dataUpPlanobraPo['codigo_po'] = $ptr;
                                array_push($arrayUpPlanobraPo, $dataUpPlanobraPo);

                                /**registro en log_planobra_po**/
                                $logPlanobraPoData = array();
                                $logPlanobraPoData['codigo_po'] = $ptr;
                                $logPlanobraPoData['itemplan'] = $itemplan;
                                $logPlanobraPoData['idUsuario'] = $idPersona;
                                $logPlanobraPoData['idPoestado'] = 6;
                                $logPlanobraPoData['fecha_registro'] = $this->fechaActual();
                                $logPlanobraPoData['controlador'] = 'Gestionar Hoja Gestion';
                                array_push($arraylogPlanobraPoData, $logPlanobraPoData);

                                /**detalleplan**/
                                $dataUpDetallePlan = array();
                                $dataUpDetallePlan['oc']    = $orden_compra;
                                $dataUpDetallePlan['ncert'] = $nro_certificacion;
                                $dataUpDetallePlan['poCod'] = $ptr;
                                array_push($arrayUpDetallePlan, $dataUpDetallePlan);
                            }
                        }

                        /**datos de la hoja de gestion**/
                        $dataHojagestion = array(
                            'cesta'               => $cesta,
                            'estado'                => 4,
                            'orden_compra'          => $orden_compra,
                            'nro_certificacion'     => $nro_certificacion,
                            'usuario_cetificacion'  => $idPersona,
                            'fecha_certificacion'   => $this->fechaActual()
                        );
                        /*
                            log_message('error', '$arrayUpCertificacionMo:'.print_r($arrayUpCertificacionMo,true));
                            log_message('error', '$arrayUpPlanobraPo:'.print_r($arrayUpPlanobraPo,true));
                            log_message('error', '$arraylogPlanobraPoData:'.print_r($arraylogPlanobraPoData,true));
                            log_message('error', '$arrayUpDetallePlan:'.print_r($arrayUpDetallePlan,true));
                            log_message('error', '$dataHojagestion:'.print_r($dataHojagestion,true));
                       */
                        $data = $this->m_creacion_oc->updateHojaGestionCertificacion($arraylogPlanobraPoData, $arrayUpCertificacionMo, $arrayUpPlanobraPo, $arrayUpDetallePlan, $dataHojagestion, $idHojaGestion);
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

    function fechaActual()
    {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone', 'America/Lima');
        setlocale(LC_TIME, "es_ES", "esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }

    public function makeCSVHojaGestionMO()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            //cabeceras para descarga
            header('Content-Type: application/octet-stream');
            header("Content-Transfer-Encoding: Binary");
            header("Content-disposition: attachment; filename=\"Detalle_hoja_gestion.csv\"");
            //preparar el wrapper de salida
            $outputBuffer = fopen("php://output", 'w');
            $detalleplan = $this->m_creacion_oc->getDataToExcelReport();
            if (count($detalleplan->result()) > 0) {
                fputcsv($outputBuffer,  explode('\t', "PROYECTO" . "\t" . "SUBPROYECTO" . "\t" . "EECC" . "\t" . "AREA" . "\t" . "PEP 1" . "\t" . "PEP 2" . "\t" . "ITEMPLAN" . "\t" . "PTR" . "\t" . "MONTO" . "\t" . "CESTA" . "\t" . "HOJA GESTION" . "\t" . "ORDEN COMPRA" . "\t" . "NRO CERTIFICACION" . "\t" . "PROMOTOR" . "\t" . "ESTADO HOJA GESTION"));
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

    function removeOnePtrFromHojaGestion()
    {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {

            $ptr                = $this->input->post('ptr');
            $idHojaGestion      = $this->input->post('id_hg');
            $arrayData = array(
                'ptr' => $ptr,
                'hoja_gestion'         => null,
                'estado_validado'       => 0,
                'usua_remove_hg'    => $this->session->userdata('idPersonaSession')
            );
            $data = $this->m_creacion_oc->updatePtrFromHojaGestion($ptr, $arrayData, $idHojaGestion);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function certificarSolicitudOc()
    {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            $this->db->trans_begin();

            $codigo_solicitud = $this->input->post('codigo_solicitud');
            $codigo_certifica = $this->input->post('codigo_certifica');
            $idUsuario        = $this->session->userdata('idPersonaSession');

            if ($idUsuario != null) {
                $arrayData = array(
                    'estado'               => 2,
                    'usuario_valida'       => $idUsuario,
                    'fecha_valida'         => $this->fechaActual(),
                    'codigo_certificacion' => $codigo_certifica
                );
                //falta actualizar el estado de litemplan y las po a certificado. 21.10.2020 czavala
                $arrayDataObra = $this->m_utils->getDataItemplanByCodOcCerti($codigo_solicitud);

                if ($arrayDataObra['idEstadoPlan'] == 10) {
                    $dataPlanObra = array(
                        'solicitud_oc_certi'    => $codigo_solicitud,
                        'estado_oc_certi'        => 'ATENDIDO',
                        'fecha_certifica'        => $this->fechaActual(),
                        'trunco_situacion'    => 23,
                        'usu_upd'             => $idUsuario,
                        'fecha_upd'           => $this->fechaActual(),
                        'descripcion'         => 'CERTIFICADO OC-TRUNCO',
                        'codigo_certificacion' => $codigo_certifica
                    );
                } else { //SI NO ES TRUNCO SE CERTIFICA LA OBRA
                    $dataPlanObra = array(
                        'solicitud_oc_certi'    => $codigo_solicitud,
                        'estado_oc_certi'        => 'ATENDIDO',
                        'fecha_certifica'        => $this->fechaActual(),
                        'idEstadoPlan'        => 23,
                        'usu_upd'             => $idUsuario,
                        'fecha_upd'           => $this->fechaActual(),
                        'descripcion'         => 'CERTIFICADO OC',
                        'codigo_certificacion' => $codigo_certifica
                    );
                }

                // $data = $this->m_creacion_oc->update_solicitud_oc_certi($codigo_solicitud, $arrayData, $dataPlanObra);

                $countValida = $this->m_creacion_oc->getCountPoByItemplanAndEstado($arrayDataObra['itemplan'], 5);

                $data = $this->m_creacion_oc->update_solicitud_oc_certi($codigo_solicitud, $arrayData, $dataPlanObra, $idUsuario, $countValida);

                if ($data['error'] == EXIT_ERROR) {
                    throw new Exception($data['msj']);
                }

                // $resp_valida = $this->m_utils->get_fn_registro_vr_certi($arrayDataObra['itemplan']);

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

    function validarAnulacionOc()
    {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {

            $codigo_solicitud = $this->input->post('cod_solicitud');
            $idPersona        = $this->session->userdata('idPersonaSession');

            if ($idPersona != null) {
                $arrayData = array(
                    'estado'               => 2,
                    'usuario_valida'       => $idPersona,
                    'fecha_valida'         => $this->fechaActual()
                );

                $data = $this->m_creacion_oc->update_solicitud_oc($codigo_solicitud, $arrayData);
            } else {
                throw new Exception('Su sesion a terminado, Refresque la pantalla y vuelva a iniciar Sesion.');
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function getActaCertificacion()
    {
        $cod_solicitud = (isset($_GET['codigo_solicitud']) ? $_GET['codigo_solicitud'] : '');
        $fechaActual = $this->m_utils->fechaActual();

        $this->load->library('Pdf');
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetTitle('Acta Certificacion');
        $pdf->SetHeaderMargin(30);
        //$pdf->SetTopMargin(20);
        $pdf->setFooterMargin(20);
        $pdf->SetAutoPageBreak(true);
        $pdf->SetAuthor('Author');
        $pdf->SetDisplayMode('real', 'default');
        //$pdf->Write(5, 'CodeIgniter TCPDF Integration');
        // set font
        $pdf->SetFont('helvetica', 'B', 20);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        // add a page
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 8);

        $dataSolicitud = $this->m_creacion_oc->getDataSolicitudPdf($cod_solicitud);
		
		/*$url = "https://www.plandeobras.com/sam/api_listar_validate_codigounico";
		$request = "POST";
		$headers = [
			#'Accept: application/json;',
			'Content-Type: application/json',
		];
		$dataSend = json_encode(['CodigoUnico' => $dataSolicitud['codigo_unico']]);
		_log($dataSend);
		$response = $this->m_utils->sendDataToURL($url, $request, $headers, $dataSend);
		_log(print_r($response,true));*/
		$dataSitio = $this->m_creacion_oc->getDataCodigoUnicoSAM($dataSolicitud['codigo_unico']);
				
        $tablaFirma = null;

        $firmaGerente = null;
        $firmaJefe    = null;
        $datosJefeEmp = null;
        $dataUsuaGerente = $this->m_utils->getUsuarioByIdUsuario($dataSolicitud['idUsuarioFirmaGerente']);
        $dataUsuaJefe    = $this->m_utils->getUsuarioByIdUsuario($dataSolicitud['idUsuarioFirmaJefeTdp']);
        $dataUsuaJefeEmp = $this->m_utils->getUsuarioByIdUsuario($dataSolicitud['idUsuarioFirmaJefeEmp']);
        $dataUsuaSup     = $this->m_utils->getUsuarioByIdUsuario($dataSolicitud['idUsuarioFirmaSup']);
		
        $datosGerente = '<td style="width: 35%;height:70px;"><span><BR><BR><BR>Nombre: </span><BR>Cargo:</td>';
        $datosJefe    = '<td style="width: 32%;height:70px;"><span><BR><BR><BR>Nombre: </span><BR>Cargo:</td>';
        $datosSup     = '<td style="width: 33%;height:70px;"><span><BR><BR><BR>Nombre: </span><BR>Cargo:</td>';
		
		// if($dataUsuaJefe['nombre'] == null || $dataUsuaJefe['nombre'] == '') {
			// $dataUsuaJefe['nombre'] = $dataSolicitud['responsable'];
		// }
		
		// if($dataUsuaGerente['nombre'] == null || $dataUsuaGerente['nombre'] == '') {
			// $dataUsuaGerente['nombre'] = $dataSolicitud['gerencia_desc'];
		// }
		
        if ($dataSolicitud['idEstadoFirma'] == 2) {
            $firmaJefeEmp = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="' . RUTA_IMG . $dataUsuaJefeEmp['firma'] . '" width="100" height="50">';
            $datosJefeEmp = '<td style="width: 32%;height:70px;"><span><BR>' . $firmaJefeEmp . '<BR><BR>Nombre: ' . $dataUsuaJefeEmp['nombre'] . '</span><BR>Cargo:  ' . utf8_decode($dataUsuaJefeEmp['cargo']) . '</td>';
        } else if ($dataSolicitud['idEstadoFirma'] == 3) {
            $firmaJefeEmp = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="' . RUTA_IMG . $dataUsuaJefeEmp['firma'] . '" width="100" height="50">';
            $datosJefeEmp = '<td style="width: 32%;height:70px;"><span><BR>' . $firmaJefeEmp . '<BR><BR>Nombre: ' . $dataUsuaJefeEmp['nombre'] . '</span><BR>Cargo:  ' . utf8_decode($dataUsuaJefeEmp['cargo']) . '</td>';

            $firmaSup = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="' . RUTA_IMG . $dataUsuaSup['firma'] . '" width="100" height="50">';
            $datosSup = '<td style="width: 33%;height:70px;"><span><BR>' . $firmaSup . '<BR><BR>Nombre: ' . $dataUsuaSup['nombre'] . '</span><BR>Cargo:  ' . utf8_decode($dataUsuaSup['cargo']) . '</td>';
        } else if ($dataSolicitud['idEstadoFirma'] == 4) {
            $firmaJefeEmp = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="' . RUTA_IMG . $dataUsuaJefeEmp['firma'] . '" width="100" height="50">';
            $datosJefeEmp = '<td style="width: 32%;height:70px;"><span><BR>' . $firmaJefeEmp . '<BR><BR>Nombre: ' . $dataUsuaJefeEmp['nombre'] . '</span><BR>Cargo:  ' . utf8_decode($dataUsuaJefeEmp['cargo']) . '</td>';

            $firmaSup = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="' . RUTA_IMG . $dataUsuaSup['firma'] . '" width="100" height="50">';
            $datosSup = '<td style="width: 33%;height:70px;"><span><BR>' . $firmaSup . '<BR><BR>Nombre: ' . $dataUsuaSup['nombre'] . '</span><BR>Cargo:  ' . utf8_decode($dataUsuaSup['cargo']) . '</td>';

            $firmaJefe = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="' . RUTA_IMG . $dataUsuaJefe['firma'] . '" width="100" height="50">';
            $datosJefe = '<td style="width: 32%;height:70px;"><span><BR>' . $firmaJefe . '<BR><BR>Nombre: ' . $dataUsuaJefe['nombre'] . '</span><BR>Cargo:  ' . utf8_decode($dataUsuaJefe['cargo']) . '</td>';
        }

        if ($dataSolicitud['idEstadoFirma'] == 5) {
            $firmaJefeEmp = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="' . RUTA_IMG . $dataUsuaJefeEmp['firma'] . '" width="100" height="50">';
            $datosJefeEmp = '<td style="width: 32%;height:70px;"><span><BR>' . $firmaJefeEmp . '<BR><BR>Nombre: ' . $dataUsuaJefeEmp['nombre'] . '</span><BR>Cargo:  ' . $dataUsuaJefeEmp['cargo'] . '</td>';

            $firmaSup = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="' . RUTA_IMG . $dataUsuaSup['firma'] . '" width="100" height="50">';
            $datosSup = '<td style="width: 33%;height:70px;"><span><BR>' . $firmaSup . '<BR><BR>Nombre: ' . $dataUsuaSup['nombre'] . '</span><BR>Cargo:  ' . $dataUsuaSup['cargo'] . '</td>';

            $firmaJefe = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="' . RUTA_IMG . $dataUsuaJefe['firma'] . '" width="100" height="50">';
            $datosJefe = '<td style="width: 32%;height:70px;"><span><BR>' . $firmaJefe . '<BR><BR>Nombre: ' . $dataUsuaJefe['nombre'] . '</span><BR>Cargo:  ' . $dataUsuaJefe['cargo'] . '</td>';

            $firmaGerente = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="' . RUTA_IMG . $dataUsuaGerente['firma'] . '" width="100" height="50">';
            $datosGerente = '<td style="width: 33%;height:70px;"><span><BR>' . $firmaGerente . '<BR><BR>Nombre: ' . $dataUsuaGerente['nombre'] . '</span><BR>Cargo: ' . $dataUsuaGerente['cargo'] . '</td>';
        }
		
		// if($dataSolicitud['flg_reparo_acta'] == 1) {
			// $reparo = 'SI';
		// } else {
			$reparo = 'NO';
		// }
		
		$dataPartidas = $this->m_utils->getPartidasActaDetalle($cod_solicitud);
		
		$htmPartidas = null;
		$tablaEvidenciaAws = null;
		$link_evidencia = null;
		$totalFin = 0;
		$ubicacion = 'uploads/evidencia_fotos/'.$dataSolicitud['itemplan'];
        if($dataSolicitud['flg_evidencia'] != null) {
			$tablaEvidenciaAws = $this->tablaEvidenciaAws($dataSolicitud['itemplan']);
		} else {
			$link_evidencia = '<a donwload href="descargarEvidenciaActa?itemplan=' . $dataSolicitud['itemplan'].'" target="_blank">DESCARGAR EVIDENCIA</a>';
		}
		

		foreach ($dataPartidas as $row) {
			$totalFin = $row['total'] + $totalFin;
			$htmPartidas .= '<tr>
								<td style="width:80px;text-align: center;">'.$row['codigo'].'</td>
								<td style="width:200px;height:25px;">'.$row['partidaDesc'].'</td>
								<td style="width:50px;text-align: center;">'.$row['cantidad'].'</td>
								<td style="width:80px;text-align: center;">'.$row['baremo'].'</td>
								<td style="width:80px;text-align: center;">'.$row['costo'].'</td>
								<td style="width:50px;text-align: center;">'.$row['total'].'</td>
							 </tr>';
		}
		$htmPartidas .= '<tr>
								<td style="width:80px;text-align: center;">TOTAL</td>
								<td style="width:200px;height:25px;"></td>
								<td style="width:50px;text-align: center;"></td>
								<td style="width:80px;text-align: center;"></td>
								<td style="width:80px;text-align: center;"></td>
								<td style="width:50px;text-align: center;">'.$totalFin.'</td>
							 </tr>';
	    
        $img = base_url() . 'public/img/logo/tdp.png';
        $tbl = ' <img style="width: 100px; heigth:40px" src="">
                <p style="text-align: center;"><strong>ACTA CERTIFICACI&Oacute;N</strong></p>
                <p style="text-align: center;">&nbsp;</p>
                <table style="height: 100%; width: 100%;" border="1">
                    <tbody>
                        <tr>
                            <td style="width: 25%;" style="text-align: left;background-color:#F2F27C"><strong><BR>T&Iacute;TULO DEL PROYECTO / OBRA: </strong></td>
                            <td style="width: 25%;" style="text-align: center;"><strong><BR>' . $dataSolicitud['proyectoDesc'] . '</strong></td>
                            <td style="width: 25%;" style="text-align: left;background-color:#F2F27C"><strong><BR>FECHA: </strong></td>
                            <td style="width: 25%;" style="text-align: center;"><strong><BR>' . date("d/m/Y",  strtotime($fechaActual)) . '</strong></td>
                        </tr>
                    </tbody>
                </table>
                <table style="height: 100%; width: 100%;" border="1">
                    <tbody>
                        <tr>
                            <td style="width: 25%;" style="text-align: left;background-color:#F2F27C"><strong><BR><BR>GERENCIA:</strong></td>
                            <td style="width: 25%;" style="text-align: center;"><strong><BR>' . utf8_decode($dataSolicitud['gerencia_desc']) . '</strong></td>
                            <td style="width: 25%; background-color:#F2F27C; text-align: left;"><strong><BR><BR>GESTOR RESPONSABLE: </strong></td>
                            <td style="width: 25%;" style="text-align: center;"><strong><BR>' . utf8_decode($dataUsuaGerente['nombre']). '</strong></td>
                        </tr>
                    </tbody>
                </table>
                <table style="height: 100%; width: 100%;" border="1">
                    <tbody>
                        <tr>
                            <td style="width: 25%; background-color:#F2F27C"><strong><BR>PROVEEDOR: </strong></td>
                            <td style="width: 25%;" style="text-align: center;"><strong><BR>' . $dataSolicitud['empresaColabDesc'] . '</strong></td>
                            <td style="width: 25%; background-color:#F2F27C"><strong><BR>POSICIONES A CERTIFICAR: </strong></td>
                            <td style="width: 25%;" style="text-align: center;"><strong><BR>TODAS</strong></td>
                        </tr>
                    </tbody>
                </table>
                <table style="height: 100%; width: 100%;" border="1">
                    <tbody>
                        <tr>
                            <td style="width: 25%; background-color:#F2F27C"><strong><BR>IMPORTE TOTAL DE LA OC: </strong></td>
                            <td style="width: 25%;" style="text-align: center;"><strong><BR>'.$dataSolicitud['tipo_moneda'].' ' . $dataSolicitud['costo_sap'] . '</strong></td>
                            <td style="width: 25%; background-color:#F2F27C"><strong><BR>IMPORTE A CERTIFICAR: </strong></td>
                            <td style="width: 25%;" style="text-align: center;"><strong><BR>'.$dataSolicitud['tipo_moneda'].' '. $dataSolicitud['costo_sap'] . '</strong></td>
                        </tr>
                    </tbody>
                </table>
                <table style="height: 100%; width: 100%;" border="1">
                    <tbody>
                        <tr>
                            <td style="width: 25%;"></td>
                            <td style="width: 25%;"></td>
                            <td style="width: 25%; background-color:#F2F27C"><strong><BR>NRO O/C: </strong></td>
                            <td style="width: 25%;" style="text-align: center;"><strong><BR>' . $dataSolicitud['orden_compra'] . '</strong></td>
                        </tr>
                    </tbody>
                </table>
                <p><br/><br/></p>
                <table style="height: 100%; width: 100%;" border="1">
                    <tbody>
                        <tr>
                            <td style="width: 25%; height:30px">DEPARTAMENTO</td>
                            <td style="width: 10%;">' . $dataSitio['departamento'] . '</td>
                            <td style="width: 25%;">PROVINCIA</td>
                            <td style="width: 10%;">' . $dataSitio['provincia'] . '</td>
                            <td style="width: 15%;">DISTRITO</td>
                            <td style="width: 15%;">' . $dataSitio['distrito'] . '</td>
                        </tr>
                    </tbody>
                </table>
                <p><br/><br/></p>
                <table>
                    <tbody>
                        <tr>
                            <td  style="width: 40%;">
                                <table style="height: 100%; width: 100%; margin-left: auto; margin-right: auto;" border="1">
                                    <tbody>
                                        <tr>
                                            <th style="width: 50%;text-align: center;height:50px" rowspan="2"><strong><BR><BR>REPARO</strong></th>
                                            <th style="width: 50%;height:30px">Aplica (Si o No): </th> 
                                        </tr>
                                        <tr>
                                            <th style="text-align: center">'.$reparo.'</th>
                                            <th style="width: 20%;height:30%" style=""></th>
                                        </tr>
                                    
                                    </tbody>
                                </table>
                            </td>
                            <td  style="width: 40%;">
                                <table style="height: 100%; width: 100%; margin-left: auto; margin-right: auto;" border="1">
                                    <tbody>
                                        <tr>
                                            <th style="width: 50%;text-align: center;height:50px" rowspan="2"><strong><BR><BR>PENALIDAD</strong></th>
                                            <th style="width: 50%;height:30px">Aplica (Si o No): </th> 
                                        </tr>
                                        <tr>
                                            <th style="text-align: center">NO</th>
                                            <th style="width: 20%;height:30%" style=""></th>
                                        </tr>
                                    
                                    </tbody>
                                </table>
                            </td>    
                        </tr>
                    </tbody>
                </table>
                
                <p><br/><br/></p>
                <table style="height: 100%; width: 100%;" border="1">
                    <tbody>
                        <tr>
                            <td style="width: 40%; height:20px">FECHA TERMINO DE OBRA</td>
                            <td style="width: 20%;">' . date("d/m/Y",  strtotime($dataSolicitud['fechaEjecucion'])) . '</td>
                        </tr>
                        <tr>
                            <td style="width: 40%; height:20px">FECHA PREVISTA DE PUESTA EN SERVICIO</td>
                            <td style="width: 20%;">' . date("d/m/Y",  strtotime($dataSolicitud['fechaEjecucion'])) . '</td>
                        </tr>  
                    </tbody>
                </table>
                <p><br/><br/></p>
                <table style="height: 100%; width: 100%;">
                    <tbody>
                        <tr>
                            <td style="height:20px">1.- La firma de la presente ACTA DE ACEPTACI&Oacute;N es constancia que la obra, suministro o servicio que ampara se ha ejecutado de conformidad con el Proyecto asignado.</td>
                        </tr>
                        <tr>
                            <td style="height:20px">2.- La Recepci&oacute;n definitiva de la obra, suministro o servicio, queda condicionada a la comprobaci&oacute;n y levantamiento de la Hoja de Reparos. As&iacute; como a las normas y penalidades estipuladas en la Orden de Compra generada para este caso.</td>
                        </tr>
                        <tr>
                            <td>3.- Para la aplicaci&oacute;n de PENALIDADES por demoras en la entrega o en el levantamiento de los reparos, se respetar&aacute;n las condiciones pactadas en el Contrato Marco o Contrato Particular de cada obra, suministro o servicio firmado entre las partes. </td>
                        </tr>  
                    </tbody>
                </table>
                <p><br/><br/></p>
                <table style="height: 100%; width: 100%;" border="1">
                    <tbody>
                        <tr>
                            <td style="height:20px">FIRMA POR TELEF&Oacute;NICA</td>
                        </tr>
                        <tr>
                            
                            ' . utf8_decode($datosJefe) . '
                            ' . utf8_decode($datosGerente) . '
                            ' . utf8_decode($datosSup) . '
                        </tr>
                    </tbody>
                </table>
                <p><br/><br/></p>
                <table style="height: 100%; width: 100%;" border="1">
                    <tbody>
                        <tr>
                            <td style="height:20px">FIRMA POR EL PROVEEDOR O CONTRATISTA EJECUTOR DE LA OBRA, SUMINISTRO O SERVICIO</td>
                        </tr>
                        <tr>
                            ' . utf8_decode($datosJefeEmp) . '
                            <td style="width: 33%;height:70px;"><span><BR><BR><BR><BR><BR><BR><BR>Nombre:</span><BR>Cargo:</td>
                            <td style="width: 35%;height:70px;"><span><BR><BR><BR><BR><BR><BR><BR>Nombre:</span><BR>Cargo:</td>
                        </tr>
                    </tbody>
                </table>
				<p><br/><br/></p>
				 <p><br/><br/></p>
				 <p><br/><br/></p>
				 <p><br/><br/></p>
				 <p><br/><br/></p>
				 <p><br/><br/></p>
				 <p><br/><br/></p>
				 <p><br/><br/></p>
				 <p><br/><br/></p>
				 <p><br/><br/></p>
				 <p><br/><br/></p>
				 <p style="text-align: center;"><strong>EVIDENCIAS</strong></p>
				 '.$link_evidencia.'
				 <p><br/><br/></p>'
				 
				 .$tablaEvidenciaAws.'
				 <p><br/><br/></p>
				 <p style="text-align: center;"><strong>PARTIDAS</strong></p>
				 <p><br/><br/></p>
                 <table id="tbComprobantes"  border="1">
					 <thead class="thead-default">
						  <tr>
							  <th style="width:80px;text-align: center;">CODIGO</th>
							  <th style="width:200px;text-align: center;">PARTIDA</th>
							  <th style="width:50px;text-align: center;">CANTIDAD</th>
							  <th style="width:80px;text-align: center;">BAREMO</th>
							  <th style="width:80px;text-align: center;">COSTO</th>
							  <th style="width:50px;text-align: center;">TOTAL</th>
						  </tr>
					  </thead>
					  <tbody>
                         '.utf8_decode($htmPartidas).'
                     </tbody>
                 </table><p><br/><br/></p>';

        $pdf->writeHTML(utf8_encode($tbl), true, false, false, false, '');
        $pdf->Output('acta_certificacion_' . $dataSolicitud['orden_compra'] . '.pdf', 'I');
    }

    function openModalQuiebreSolOc()
    {
        $data['cmbMotivo'] = __buildCmbMotivo(7);

        echo json_encode(array_map('utf8_encode', $data));
    }

    function enviarQuiebreSolOc()
    {
        try {
            $codigoSolicitud = $this->input->post('codigoSolicitud');
            $itemplan = $this->input->post('itemplan');
            $idMotivo = $this->input->post('idMotivo');
            $desMotivo = $this->input->post('desMotivo');
            $comentario = $this->input->post('comentario');

            if ($itemplan == null) {
                throw new Exception('error interno, no cuenta con itemplan');
            }

            if ($idMotivo == null) {
                throw new Exception('Debe seleccionar un motivo');
            }

            if ($idMotivo == 85) {
                $flg_presupuesto_oc = 1;
            } else {
                $flg_presupuesto_oc = 2;
            }

            $this->m_utils->insertLogBandejaQuiebre([
                'usuario_log' => $this->session->userdata('idPersonaSession'),
                'fecha_log' => _fechaActual(),
                'tipo_log' => 'RECHAZADO',
                'modulo_log' => 'MAS DESPLIEGUE',
                'itemplan' => $itemplan,
                'codigo_solicitud' => $codigoSolicitud,
                'motivo' => $desMotivo,
                'comentario' => $comentario,
            ]);

            $this->m_utils->updatePlanObraQuiebre([
                'itemplan' => $itemplan,
                'codigo_solicitud' => $codigoSolicitud,
                'emisor' => 'GETEC',
                'usuario_rechazo' => $this->session->userdata('idPersonaSession'),
                'fecha_rechazo' => _fechaActual(),
                'usuario_liberacion' => NULL,
                'fecha_liberacion' => NULL,
            ]);

            $dataUpdate = array(
                'flg_presupuesto_oc' => $flg_presupuesto_oc,
                'idMotivoQuiebre'    => $idMotivo,
                'comentarioQuiebre'  => $comentario
            );

            $data = $this->m_utils->simpleUpdateEstadoPlanObra($itemplan, $dataUpdate);

            if ($data['error'] == EXIT_ERROR) {
                throw new Exception('no se actualizo la obra, verificar.');
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
	
	function descargarEvidenciaActa() {
		$itemplan = $_GET['itemplan'];
		
		list($comprimidoZip, $fileName) = $this->zipActasOcMasivo($itemplan);
		header($_SERVER['SERVER_PROTOCOL'].' 200 OK');
		header("Content-Transfer-Encoding: Binary");    
		header("Content-Disposition:attachment;filename=\"".basename($fileName)."\"");
		header("Content-Type: application/zip");
		
		readfile(FCPATH.$comprimidoZip);
	}
	
	function zipActasOcMasivo($itemPlan) {
		$data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
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
                return array($ubicacionZip.'/'.$fileName, $fileName);
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
	
	function tablaEvidenciaWebPO($itemplan) {
		$arrayEvidenciaFotos = $this->m_utils->getSiomObraEvidencia($itemplan);
		$ubicacionZip = 'uploads/evidencia_zip/'.$itemplan;
		$ubicacion = 'uploads/evidencia_fotos/'.$itemplan;
		$count = 1;
		$html  = null;
		$evidencia3 = null;
		$html = '<table id="tb_expedientes" class="style="height: 100%; width: 100%;" border="1"">
			<thead class="thead-default">
				<tr>
					<th style="text-align: center; vertical-align: middle;" colspan="1">NRO</th>
					<th style="text-align: center; vertical-align: middle;" colspan="1">ESTACION</th>
					<th style="text-align: center; vertical-align: middle;" colspan="1">EVIDENCIA</th>
				</tr>
			</thead>
			<tbody>';
		if(is_dir($ubicacionZip)) {
			$nombreZip = $this->enviarEvidenciaFotosZip($itemplan);
			$evidencia3 = '<a donwload href="'.$ubicacionZip.'/'.$nombreZip.'" target="_blank">Evidencia Zip</a>';
		}	
		foreach($arrayEvidenciaFotos as $row) {
			$evidencia1 = null;
			$evidencia2 = null;
			
			if($row['path_pdf_pruebas'] != null && is_dir($ubicacion)) {
				$evidencia1 = '<a donwload href="'.$row['path_pdf_pruebas'].'" target="_blank">Evidencia 1</a>';
			}
			
			if($row['path_pdf_perfil'] != null && is_dir($ubicacion)) {
				$evidencia2 = '<a donwload href="'.$row['path_pdf_perfil'].'" target="_blank">Evidencia 2</a>';
			}

			
			$html .= '<tr>
						<td style="text-align: center" colspan="1">
							' . $count . '
						</td>
						<td style="text-align: center" colspan="1">
							' . $row['estacionDesc'] . '
						</td>
						<td style="text-align: center" colspan="1">
							<div style="width: 150px;">
								'.$evidencia1.'
								'.$evidencia2.'
								'.$evidencia3.'
							</div>
						</td>								
					 </tr>
			';
			$count++;
		}
		$html .= '</tbody>
		</table>';
		return $html;
	}
	
	function tablaEvidenciaAws($itemplan) {
		$arrayEvidenciaFotos = $this->m_utils->getSiomObraEvidencia($itemplan);
		$count = 1;
		$html  = null;
		
		$html = '<table id="tb_expedientes" class="style="height: 100%; width: 100%;" border="1"">
			<thead class="thead-default">
				<tr>
					<th style="text-align: center; vertical-align: middle;" colspan="1">NRO</th>
					<th style="text-align: center; vertical-align: middle;" colspan="1">ESTACION</th>
					<th style="text-align: center; vertical-align: middle;" colspan="1">EVIDENCIA</th>
				</tr>
			</thead>
			<tbody>';
		foreach($arrayEvidenciaFotos as $row) {
			$evidencia1 = null;
			$evidencia2 = null;
			if($row['url_pdf_pruebas'] != null) {
				$evidencia1 = '<a href="'.$row['url_pdf_pruebas'].'">Evidencia 1</a>';
			}
			
			if($row['url_pdf_perfil'] != null) {
				$evidencia2 = '<a href="'.$row['url_pdf_perfil'].'">Evidencia 2</a>';
			}
			
			$html .= '<tr>
						<td style="text-align: center" colspan="1">
							' . $count . '
						</td>
						<td style="text-align: center" colspan="1">
							' . $row['estacionDesc'] . '
						</td>
						<td style="text-align: center" colspan="1">
							<div style="width: 150px;">
								'.$evidencia1.'
								'.$evidencia2.'
							</div>
						</td>								
					 </tr>
			';
			$count++;
		}
		$html .= '</tbody>
		</table>';
		return $html;
	}
}
