<?php
require_once('vendor/autoload.php');
defined('BASEPATH') or exit('No direct script access allowed');
use Aws\S3\S3Client;

class C_bandeja_presupuesto_oc_capex extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=UTF-8');
        $this->load->model('mf_certificacion/m_bandeja_presupuesto_oc_capex');
		$this->load->model('mf_pqt_plan_obra/m_regularizar_evidencia_itemplan');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->library('excel');
        $this->load->helper('url');
		$this->CI =& get_instance();
        $this->CI->config->load('s3', TRUE);
        $this->s3_config = $this->CI->config->item('s3'); 
    }

    public function index()
    {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {
            $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
            $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
            $permisos =  $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, 308, 389, ID_MODULO_ADMINISTRATIVO);
            $data['opciones'] = $result['html'];
            $data['title'] = 'Bandeja Regularización Presupuesto OC CAPEX';

            $data['tbReporte'] = $this->makeHTLMTablaConsulta('');


            // if($result['hasPermiso'] == true){
            $this->load->view('vf_certificacion/v_bandeja_presupuesto_oc_capex', $data);
            // }else{
            // 	redirect('login','refresh');
            // }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function makeHTLMTablaConsulta($listaReporte)
    {
        $html = '
                <table id="data-table" class="table table-bordered table-sm">
                    <thead class="thead-default">
                        <tr>
                            <th style="text-align: center, vertical-align: middle;" colspan="1">ACCIÓN</th>
							<th style="text-align: center, vertical-align: middle;" colspan="1">FECHA QUIEBRE</th>
							<th style="text-align: center, vertical-align: middle;" colspan="1">ITEMPLAN</th>
							<th style="text-align: center, vertical-align: middle;" colspan="1">PROYECTO</th>
							<th style="text-align: center, vertical-align: middle;" colspan="1">SUBPROYECTO</th>
							<th style="text-align: center, vertical-align: middle;" colspan="1">EE.CC.</th>
							<th style="text-align: center, vertical-align: middle;" colspan="1">SOLICITUD</th>
							<th style="text-align: center, vertical-align: middle;" colspan="1">TRANSACCION</th>
							<th style="text-align: center, vertical-align: middle;" colspan="1">ESTADO SOLICITUD</th>
							<th style="text-align: center, vertical-align: middle;" colspan="1">MOTIVO</th>
							<th style="text-align: center, vertical-align: middle;" colspan="1">COMENTARIO</th>
							<th style="text-align: center, vertical-align: middle;" colspan="1">S/. COSTO TOTAL</th>
							<th style="text-align: center, vertical-align: middle;" colspan="1">S/. ULTIMO COSTO</th>
							<th style="text-align: center, vertical-align: middle;" colspan="1">S/. COSTO REQUE.</th>
                            <th style="text-align: center, vertical-align: middle;" colspan="1">S/. MONTO DISPO. SAP</th>
                            <th style="text-align: center, vertical-align: middle;" colspan="1">PEP 1</th>
							<th style="text-align: center, vertical-align: middle;" colspan="1">PEP 2</th>
                            <th style="text-align: center, vertical-align: middle;" colspan="1">CESTA</th>
                            <th style="text-align: center, vertical-align: middle;" colspan="1">ORDEN DE COMPRA</th>
                            <th style="text-align: center, vertical-align: middle;" colspan="1">USUARIO QUIEBRE</th>
							<th style="text-align: center, vertical-align: middle;" colspan="1">FECHA A QUIEBRE</th>
                        </tr>
                    </thead>

                    <tbody>';

        $count = 1;
        $htmlBody = '';

        $ctnLista = 0;

        if ($listaReporte != '') {
            $ctnLista = count($listaReporte);
            foreach ($listaReporte as $row) {
                $btnAccionLiberar = null;
				$btnCancelaSol    = null;
				$btnRegEvi = null;
				
				if($row->tipo_solicitud != 3) {
					$btnCancelaSol = '	<a title="Cancelar Solicitud" data-itemplan="' . $row->itemplan . '" data-solicitud="' . $row->codigo_solicitud . '" onclick="cancelarSolicitudQuiebre($(this))">
											<i class="zmdi zmdi-hc-2x zmdi-close-circle"></i>
										</a>';
				}


                if ($row->flg_presupuesto_oc == 1) {
                    $btnAccionLiberar = '<a title="Liberar Solicitud" data-itemplan="' . $row->itemplan . '" data-solicitud="' . $row->codigo_solicitud . '" onclick="openModalLiberarSol(this)">
											<i class="zmdi zmdi-hc-2x zmdi-lock-open"></i>
										</a>';
					
                }else if ($row->flg_presupuesto_oc == 2){
					if($row->idMotivoQuiebre == 114 && $row->flg_regu_evi_quiebre == null) {
						$btnRegEvi = '<a title="Regularizar Evidencia" data-itemplan="' . $row->itemplan . '" data-solicitud="' . $row->codigo_solicitud . '" onclick="openModalRegEvi(this)">
											<i class="zmdi zmdi-hc-2x zmdi-file-plus"></i>
									  </a>';
					}
					if($row->idMotivoQuiebre == 114) {
						if($row->flg_regu_evi_quiebre == 1 || $row->flg_regu_evi_quiebre == '1') {
							$btnAccionLiberar = '<a title="Liberar Solicitud" data-itemplan="' . $row->itemplan . '" data-solicitud="' . $row->codigo_solicitud . '" onclick="openModalLiberarSol(this)">
													<i class="zmdi zmdi-hc-2x zmdi-lock-open"></i>
												</a>';
						}
					}else{
						$btnAccionLiberar = '<a title="Liberar Solicitud" data-itemplan="' . $row->itemplan . '" data-solicitud="' . $row->codigo_solicitud . '" onclick="openModalLiberarSol(this)">
											<i class="zmdi zmdi-hc-2x zmdi-lock-open"></i>
										</a>';
					}
					
				}

                $htmlBody .= '
                        <tr>
                            <td style="text-align: center;">
                                ' . $btnAccionLiberar . ' '.$btnCancelaSol.' '.$btnRegEvi.'
                            </td>
							<td style="text-align: center;">' . $row->fecha_presupuesto_oc . '</td>
                            <td style="text-align: center;">' . $row->itemplan . '</td>
							<td style="text-align: center;">' . $row->proyectoDesc . '</td>
                            <td style="text-align: center;">' . $row->subProyectoDesc . '</td>
                            <td style="text-align: center;">' . $row->empresaColabDesc . '</td>
                            <td style="text-align: center;">' . $row->codigo_solicitud . '</td>
                            <td style="text-align: center;">' . $row->tipo_pc . '</td>
                            <td style="text-align: center;">' . $row->estado_solicitud . '</td>
							<td style="text-align: center;">' . $row->motivo . '</td>
							<td style="text-align: center;">' . $row->comentarioQuiebre . '</td>
                            <td style="text-align: center;">' . number_format($row->costo, 2) . '</td>
							<td style="text-align: center;">' . number_format($row->ultimo_costo_oc, 2) . '</td>
							<td style="text-align: center;">' . number_format(($row->costo - $row->ultimo_costo_oc), 2) . '</td>							
                            <td style="text-align: center; background-color: #beff00;">' . (isset($row->monto_temporal) ? number_format($row->monto_temporal, 2)  : $row->monto_temporal) . '</td>
                            <td style="text-align: center;">' . $row->pep1 . '</td>
                            <td style="text-align: center;">' . $row->pep2 . '</td>
                            <td style="text-align: center;">' . $row->cesta . '</td>
                            <td style="text-align: center;">' . $row->orden_compra . '</td>
                            <td style="text-align: center;">' . $row->nom_quiebre . '</td>
							<td style="text-align: center;">' . $row->fecha_quiebre . '</td>
                        </tr>
                        ';
                $count++;
            }

            $html .= $htmlBody . '</tbody>
                </table>';
        } else {
            $html .= '</tbody>
                </table>';
        }

        return $html;
    }

    public function filtrarTabla()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $data['tbReporte'] = $this->makeHTLMTablaConsulta($this->m_bandeja_presupuesto_oc_capex->getBandejaSolPdtPresupuesto());
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }


    function liberarSolicitudRobotRPA()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        try {

            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;
            if ($idUsuario != null) {
                $itemplan = $this->input->post('itemplan');
                $codigoSolicitud = $this->input->post('codigoSolicitud');
                $newPep1 = $this->input->post('newPep1');
                $accion = $this->input->post('accion');

                $this->db->trans_begin();

                if ($itemplan == null || $itemplan == '') {
                    throw new Exception('Hubo un error al recibir el itemplan.');
                }
                if ($codigoSolicitud == null || $codigoSolicitud == '') {
                    throw new Exception('Hubo un error al recibir la solicitud.');
                }
                if ($accion == null || $accion == '') {
                    throw new Exception('Hubo un error al recibir la acción a ejecutar.');
                }
				
				$fechaActual = $this->fechaActual();
                $arrayUpdatePO = array(
                    "flg_presupuesto_oc" => null
                );
                $arrayInsertLogSolRpa = array(
                    "itemplan"          => $itemplan,
                    "codigo_solicitud"  => $codigoSolicitud,
                    "pep1"          => $newPep1,
                    "usuario_registro"  => $idUsuario,
                    "fecha_registro"  => $fechaActual,
                    "accion"  => ($accion == 'S' ? 'LIBERAR Y CAMBIAR PEP' : 'SOLO LIBERAR')
                );
                if ($accion == 'S') {
                    if ($newPep1 == null || $newPep1 == '') {
                        throw new Exception('Hubo un error al recibir la nueva pep.');
                    }
                    $data = $this->m_bandeja_presupuesto_oc_capex->updateSolicitudesPdt($itemplan, $newPep1, $newPep1 . '-001');
                    if ($data['error'] == EXIT_ERROR) {
                        throw new Exception($data['msj']);
                    }
                } else {
                    $data = $this->m_bandeja_presupuesto_oc_capex->updateSolicitudesPdtNoPep($itemplan);

                    if ($data['error'] == EXIT_ERROR) {
                        throw new Exception($data['msj']);
                    }
                }
				
				$countEdic = $this->m_utils->getCountSolEdicionByItemplan($itemplan);
				
				if($countEdic > 0) {
					_log("ENTRO1");
					$data = $this->m_utils->actualizarSolicitudCertiPdt($itemplan, $idUsuario, $fechaActual);
					
					if ($data['error'] == EXIT_ERROR) {
						throw new Exception($data['msj']);
					}
					_log(print_r($data, true));
				}
				
                $this->m_utils->insertLogBandejaQuiebre([
                    'usuario_log' => $this->session->userdata('idPersonaSession'),
                    'fecha_log' => _fechaActual(),
                    'tipo_log' => 'LIBERADO',
                    'modulo_log' => 'MAS DESPLIEGUE',
                    'itemplan' => $itemplan,
                    'codigo_solicitud' => $codigoSolicitud,
                    'motivo' => NULL,
                    'comentario' => NULL,
                ]);
                
                $this->m_utils->updatePlanObraQuiebre([
                    'itemplan' => $itemplan,
                    'codigo_solicitud' => $codigoSolicitud,
                    'usuario_rechazo' => NULL,
                    'fecha_rechazo' => NULL,
                    'idMotivoQuiebre' => NULL,
                    'comentarioQuiebre' => NULL,
                    'usuario_liberacion' => $this->session->userdata('idPersonaSession'),
                    'fecha_liberacion' => _fechaActual(),
                ]);

                $data = $this->m_bandeja_presupuesto_oc_capex->updatePlanobra($itemplan, $arrayUpdatePO);
                if ($data['error'] == EXIT_ERROR) {
                    throw new Exception($data['msj']);
                }
                $data = $this->m_bandeja_presupuesto_oc_capex->insertarLogSeguimientoSolOcRpa($arrayInsertLogSolRpa);
                if ($data['error'] == EXIT_ERROR) {
                    throw new Exception($data['msj']);
                }

                $this->db->trans_commit();
                $data['tbReporte'] = $this->makeHTLMTablaConsulta($this->m_bandeja_presupuesto_oc_capex->getBandejaSolPdtPresupuesto());
            } else {
                throw new Exception('Su sesion expiro, porfavor vuelva a logearse.');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();
            $data['message'] = $e->getMessage();
        }

        echo json_encode($data);
    }

    public function fechaActual()
    {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone', 'America/Lima');
        setlocale(LC_TIME, "es_ES", "esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }

    public function makeHTLMTablaDetalle($situacion, $promotor, $subestado)
    {
        $listaReporte = $this->m_consulta->getDetalleTruncoPromotor($situacion, $promotor, $subestado);
        $html = '
                <table id="tableDetalle" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th style="text-align: center" colspan="1">#</th>
							<th style="text-align: center" colspan="1">SITUACIÓN</th>
							<th style="text-align: center" colspan="1">PROMOTOR</th>
							<th style="text-align: center" colspan="1">ITEMPLAN</th>
                            <th style="text-align: center" colspan="1">PROYECTO</th>
                            <th style="text-align: center" colspan="1">SUBPROYECTO</th>
                            <th style="text-align: center" colspan="1">EECC</th>
                            <th style="text-align: center" colspan="1">SUBESTADO</th>
							<th style="text-align: center" colspan="1">COSTO VALIDADO</th>
                        </tr>
                    </thead>

                    <tbody>';

        $count = 1;
        $htmlBody = '';

        if ($listaReporte != '') {
            foreach ($listaReporte as $row) {

                $htmlBody .= '
                        <tr>
                            <td style="text-align: center;">' . $count . '</td>
                            <td style="text-align: center;">' . $row->situacion . '</td>
                            <td style="text-align: center;">' . $row->promotor . '</td>
                            <td style="text-align: center;">' . $row->itemplan . '</td>
                            <td style="text-align: center;">' . $row->proyecto . '</td>
                            <td style="text-align: center;">' . $row->subproyecto . '</td>
                            <td style="text-align: center;">' . $row->eecc . '</td>
                            <td style="text-align: center;">' . $row->substado_trunco . '</td>
                            <td style="text-align: center;">' . (isset($row->costo_po_mo) ? number_format($row->costo_po_mo) : '') . '</td>
                        </tr>
                        ';
                $count++;
            }

            $html .= $htmlBody . '</tbody>
                </table>';
        } else {
            $html .= '</tbody>
                </table>';
        }

        return $html;
    }
	
	function cancelarSolicitudQuiebre() {
		try {
			$this->db->trans_begin();
			$codigo_solicitud = $this->input->post('codigoSolicitud');
			$itemplan         = $this->input->post('itemplan');
			$idUsuario = $this->session->userdata('idPersonaSession');
			
			if($codigo_solicitud == null || $codigo_solicitud == '' || $itemplan == null || $itemplan == '') {
				throw new Exception('No se encontró el código verificar.');
			}
			
			if($idUsuario == null || $idUsuario == '') {
				throw new Exception('La sesión a caducado, vuelva a cargar la pág.');
			}
			
			$arrayUpdatePO = array('flg_presupuesto_oc' => null);
			
			$this->m_utils->insertLogBandejaQuiebre([
                    'usuario_log' => $idUsuario,
                    'fecha_log' => $this->fechaActual(),
                    'tipo_log' => 'LIBERADO POR CANCELAR SOLICITUD',
                    'modulo_log' => 'MAS DESPLIEGUE',
                    'itemplan' => $itemplan,
                    'codigo_solicitud' => $codigo_solicitud,
                    'motivo' => NULL,
                    'comentario' => NULL,
                ]);
                
			$data = $this->m_bandeja_presupuesto_oc_capex->updatePlanobra($itemplan, $arrayUpdatePO);
			
			if ($data['error'] == EXIT_ERROR) {
				throw new Exception($data['msj']);
			}
			
			$dataSolicitud = array(
									'estado' 		  => 3,
									'usuario_cancela' => $idUsuario,
									'motivo_cancela'  => 'CANCELADO DESDE QUIEBRE'
								);
			
			$data = $this->m_utils->actualizarSolicitudCapex($codigo_solicitud, $dataSolicitud);
			
			if($data['error'] == EXIT_ERROR) {
				throw new Exception($data['msj']);
			}
			
			$this->db->trans_commit();
		} catch(Exception $e) {
			$this->db->trans_rollback();
            $data['message'] = $e->getMessage();
        }

        echo json_encode($data);
	}
	
	 function regularizarEvidenciaQuiebre()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        try {

            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;
            if ($idUsuario != null) {
                $itemplan = $this->input->post('itemplan');
                $codigoSolicitud = $this->input->post('codigoSolicitud');

                $this->db->trans_begin();

                if ($itemplan == null || $itemplan == '') {
                    throw new Exception('Hubo un error al recibir el itemplan.');
                }
                if ($codigoSolicitud == null || $codigoSolicitud == '') {
                    throw new Exception('Hubo un error al recibir la solicitud.');
                }
				if(count($_FILES) == 0){
					throw new Exception('Debe seleccionar un archivo para procesar data!!');
				}
				
				$arrayTipos = array(
					'application/x-zip-compressed',
					'application/octet-stream',
					'application/zip'
				);
				$fechaActual = $this->fechaActual();
			
				$nombreArchivo = $_FILES['file']['name'];
				$tipoArchivo = $_FILES['file']['type'];
				$nombreArchivoTemp = $_FILES['file']['tmp_name'];
				$tamano_archivo = $_FILES['file']['size'];
				
				
				if (!in_array($tipoArchivo, $arrayTipos)) {
                    throw new Exception('Solo puede cargar archivos de tipo .zip!!');
                }
				
				
				$infoEstacion = $this->m_regularizar_evidencia_itemplan->getInfoEstacionByItemplan($itemplan);
				if ($infoEstacion == null) {
					throw new Exception('Hubo un error al traer la informacion de la estacion!!');
				}
				
				$rutaCarpeta ='uploads/evidencia_fotos/'.$itemplan;
				
				
				if (!file_exists($rutaCarpeta)) {
					if (!mkdir($rutaCarpeta)) {
						throw new Exception('Hubo un error al crear la carpeta!!');
					}
				}else{
					$this->rrmdir($rutaCarpeta);
					if (!mkdir($rutaCarpeta)) {
						throw new Exception('Hubo un error al crear la carpeta!!');
					}
				}
							
				$rutaFinalCarpeta = $rutaCarpeta.'/'.$infoEstacion['estacionDesc'];
				if (!file_exists($rutaFinalCarpeta)) {
					if (!mkdir($rutaFinalCarpeta)) {
						throw new Exception('Hubo un error al crear la carpeta!!');
					}
				}

				$rutaFinalArchivo = $rutaFinalCarpeta . '/' .$nombreArchivo;
				
				$s3 = new Aws\S3\S3Client([
				   'region'  => $this->s3_config['region'],
					'version' => 'latest',
					'credentials' => [
						'key'    => $this->s3_config['key'],
						'secret' => $this->s3_config['secret'],
					]
				]);
				
				
				$resp = _enviar_aws_archivo_array($s3, $nombreArchivo, $nombreArchivoTemp, 'obras2.1/'.$rutaFinalCarpeta.'/', $this->s3_config);
				if(count($resp) == 0) {
					throw new Exception("No se guardo la evidencia, verificar.");
				}
				
				if (move_uploaded_file($nombreArchivoTemp, $rutaFinalArchivo)) {

					$evidencia1 = $resp[0];
					$evidencia2 = null;
					if(count($resp) > 1) {
						$evidencia2 = $resp[1];
					}							
					 
					$dataFormularioEvidencias = array(
						'itemplan'          => $itemplan,
						'fecha_registro'    => $fechaActual,
						'usuario_registro'  => $idUsuario,
						'idEstacion'        => $infoEstacion['idEstacion'],
						'path_pdf_pruebas'  => $rutaFinalArchivo,
						'path_pdf_perfil'   => $rutaFinalArchivo,
						'url_pdf_pruebas'	=> $evidencia1,
						'url_pdf_perfil'    => $evidencia2
					);
					
					$data = $this->m_regularizar_evidencia_itemplan->registrarEvidencias($dataFormularioEvidencias,$itemplan,$infoEstacion['idEstacion']);
					if($data['error'] == EXIT_ERROR){
						throw new Exception($data['msj']);
					}

				}else{
					throw new Exception('No se pudo subir el archivo: ' . $nombreArchivo . ' !!');
				}
				
                $arrayUpdatePO = array(
                    "flg_regu_evi_quiebre" => 1,
					"fecha_reg_evi_quiebre" => $fechaActual,
					"usu_reg_evi_quiebre" => $idUsuario
                );

                $data = $this->m_bandeja_presupuesto_oc_capex->updatePlanobra($itemplan, $arrayUpdatePO);
                if ($data['error'] == EXIT_ERROR) {
                    throw new Exception($data['msj']);
                }

                $this->db->trans_commit();
            } else {
                throw new Exception('Su sesion expiro, porfavor vuelva a logearse.');
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();
            $data['message'] = $e->getMessage();
        }

        echo json_encode($data);
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
}
