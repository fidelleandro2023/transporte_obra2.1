<?php

defined('BASEPATH') or exit('No direct script access allowed');

class C_extractor extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
		$this->load->model('mf_extractor/m_extractor');
		$this->load->model('mf_liquidacion/m_bandeja_siom');
		$this->load->model('mf_utils/m_utils');
		$this->load->library('lib_utils');
		$this->load->helper('url');
		$this->load->library('excel');
	}

	public function index()
	{
		$logedUser = $this->session->userdata('usernameSession');
		if ($logedUser != null) {
			$pepsub = 0;
			$user = $this->session->userdata('idPersonaSession');
			$zonasUser = $this->session->userdata('zonasSession');

			$fecha_dp = $this->m_utils->getMaxFechaFileDetallePlan();
			//$fecha_dp = '2019-00-00';
			$data['listartabla'] = $this->makeHTLMTExtractor($fecha_dp);
			$data['nombreUsuario'] = $this->session->userdata('usernameSession');
			$data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
			$permisos = $this->session->userdata('permisosArbolTransporte');
			$result = $this->lib_utils->getHTMLPermisos($permisos, 54, ID_PERMISO_HIJO_EXTRACTOR, ID_MODULO_PAQUETIZADO);
			$data['opciones'] = $result['html'];
			if ($result['hasPermiso'] == true) {
				$this->load->view('vf_extractor/v_extractor', $data);
			} else {
				redirect('login', 'refresh');
			}
		} else {
			redirect('login', 'refresh');
		}
	}

	public function makeHTLMTExtractor($fecha_dp)
	{
		$fechaMatMo = $this->m_extractor->getFechaCargaMoMat();
		$rutaonline = base_url() . 'public/img/iconos/online.gif';
		$rutaoffline = base_url() . 'public/img/iconos/offline.gif';

		$strPerfil  = $this->session->userdata('idPerfilSession');
		$isEECC = false;
		$flg_perfil = explode(",", $strPerfil);
		if ((in_array(5, $flg_perfil) || in_array(13, $flg_perfil) || in_array(17, $flg_perfil) || in_array(19, $flg_perfil) || in_array(22, $flg_perfil) || in_array(48, $flg_perfil))) {
			$isEECC	= true;
		}
		log_message('error', 'es EECC:' . $isEECC);
		$html = '<div class="tab-container">
                            <ul class="nav nav-tabs nav-fill" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#extract1" role="tab">Online</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#extract2" role="tab">Offline</a>
                                </li>
                            </ul>

                         </div> 
                         <div class="tab-content">
                            <div class="tab-pane fade" id="extract1" role="tabpanel">

                                <table  class="table table-bordered">
                                    <thead class="thead-default">
                                        <tr>
                                            <th>ITEMPLAN</th>
                                            <th>OC CAPEX/OPEX</th>
                                            <th>PARTIDAS</th>
                                            <th>LICENCIA EIA.</th>
                                            <th>IMPACTO AMBIENTAL</th>
                                            <th>OC FIRMA DIGITAL</th>
                                            <th>PARTIDAS SBE</th>
                                            <th>SOL. OC FIJA/+DESP.</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><a onclick="descargarPlanobra();"><img src="' . $rutaonline . '" style="width: 60%;"></a>PLANOBRA.</td>
                                            <td><a onclick="descargarReporteOC();"><img src="' . $rutaonline . '" style="width: 60%;"></a>ORDEN DE COMPRA.</td>
                                            <td><a onclick="descargarReportePartidas();"><img src="' . $rutaonline . '" style="width: 60%;"></a>PARTIDAS.</td>
                                            <td><a download href="generarLicenciaEIA"><img src="' . $rutaonline . '" style="width: 60%;"></a>LICENCIA EIA.</td>
                                            <td><a onclick="descargarImpactoAmbiental();"><img src="' . $rutaonline . '" style="width: 60%;"></a>IMPACTO AMBIENTAL</td>
                                            <td><a onclick="generarFirmaDigital();" ><img src="' . $rutaonline . '" style="width: 60%;"></a>Oc Firma.</td>
                                            <td><a onclick="generarPartidaSBE();" ><img src="' . $rutaonline . '" style="width: 60%;"></a>Partidas SBE</td>
                                            <td><a onclick="generarExcelSolOcFijaMasDep();" ><img src="' . $rutaonline . '" style="width: 60%;"></a>Sol. OC Fija / Mas desp.</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
										</tr>
                                    </tbody>
                                    <tfoot>
                                       
                                    </tfoot>
                                </table>
                        
                            </div>

                                    
                            <div class="tab-pane  active fade show" id="extract2" role="tabpanel">

                                <table  class="table table-bordered">
                                    <thead class="thead-default">
                                        <!--tr>
                                            <th>ITEMPLAN<p>Fec: ' . $fecha_dp . '</p></th>
                                        </tr-->
                                    </thead>
                                    <tbody>
                                        <!--tr>
                                             <td><a download href="download/planobra/planobraCSV.csv" ><img src="' . $rutaoffline . '" style="width: 60%;"></a>PlanObra</td>
                                        </tr-->
                                    </tbody>
                                    <tfoot>
									    <!--tr style="background-color: var(--celeste_telefonica);color:white;">
										    <td>ACTIVACIONES</td>
										</tr>
                                        <tr>
											<td> 
                                                <a download href="download/extractor/reporte_activaciones.csv"><img src="' . $rutaonline . '" style="width: 60%;"></a>Rep. Activaciones
											</td>  
                                        </tr-->
                                        
                                    </tfoot>
                                </table>
                            </div>
	                    </div>';
		return utf8_decode($html);
	}


	function generarExcelPlanobra()
	{
		$data['error'] = EXIT_ERROR;
		$data['msj'] = null;

		try {
			
			$idUsuario = $this->session->userdata('idPersonaSession');
			$idEECC = $this->session->userdata('eeccSessionTransporte');
			
			if ($idUsuario == null || $idUsuario == '') {
                throw new Exception("Su sesion ha expirado, ingrese nuevamente.");
            }
			

			/*AQUI CREAMOS EL EXCEL*/
			$spreadsheet = $this->excel;

			$spreadsheet
				->getProperties()
				->setCreator('COBRA PERU SAC')
				->setLastModifiedBy('COBRA PERU SAC')
				->setTitle('Excel creado con PhpSpreadSheet')
				->setSubject('Excel de prueba')
				->setDescription('Excel generado como prueba')
				->setKeywords('PHPSpreadsheet')
				->setCategory('Categoría de prueba');

			$hoja = $spreadsheet->getActiveSheet();
			$hoja->getSheetView()->setZoomScale(85); // zoom por defecto a la hoja
			$hoja->setTitle('PLANOBRA');

			$col = 0;
			$row = 1;
			$hoja->setCellValueByColumnAndRow($col, $row, 'FECHA CREACION');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'FECHA CANCELACION');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'ITEMPLAN');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'ESTADO PLAN');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'TIPO PLANTA');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'PROYECTO');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'SUBPROYECTO');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'TIPO(CAPEX U OPEX)');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'FASE');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'GESTOR');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'EE.CC');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'CONTRATO PADRE');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'CONTRATO MARCO');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'FECHA INICIO');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'COSTO MO INICIAL');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'FECHA LIQUIDACION');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'COSTO MO FINAL');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'ESTADO DE LA OC');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'MONTO SAP');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'TIPO MONEDA');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'ORDEN DE COMPRA');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'NRO CERTIFICACION');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'ESTADO OC CERTIFICACION');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'PEP2');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'CUENTA CONTABLE');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'CECO');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'AF');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'LINEA OPEX');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'CONCEPTO OPEX');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'ZONA');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'CODIGO DE SITIO');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'NOMBRE DE SITIO');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'NOMBRE DE CLIENTE');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'INDICADOR');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'TIPO ESTUDIO');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'SEGMENTO');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'SERVICIO');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'VELOCIDAD');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'REQUIERE HARDWARE?');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'QUIEBRES');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'COMENTARIO QUIEBRE');
			$col++;

			$estiloTituloColumnas = array(
				'font' => array(
					'name' => 'Calibri',
					'bold' => true,
					'color' => array(
						'rgb' => '000000',
					),
				),
				'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER
				)
			);

			$hoja->getStyle('A1:AN1')->applyFromArray($estiloTituloColumnas);

			$col = 0;
			$row = 2;
			$listaReporte = $this->m_extractor->getReportePlanobra($idEECC);
			foreach ($listaReporte as $fila) {

				$col = 0;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->fecha_creacion);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->fechaCancelacion);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->itemplan);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->estadoPlanDesc);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->tipoPlantaDesc);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->proyectoDesc);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->subProyectoDesc);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->tipo_obra);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->faseDesc);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->gestor);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->empresaColabDesc);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->contrato_padre);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->contrato_marco);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->fechaInicio);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->costo_inicial_mo);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->fechaEjecucion);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->costo_final_mo);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->estado_oc);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->costo_sap);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->tipo_moneda);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->orden_compra);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->codigo_certificacion);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->estado_sol_certi);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->pep2);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->cuenta);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->ceco);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->area_funcional);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->linea_opex);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->concepto_opex);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->zona);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->codigoSitio);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->nombre_sitio);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->nombreCliente);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->indicador);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->tipo_estudio);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->segmento);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->servicio);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->velocidad);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->requiere_hardware);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->tiene_quiebre);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->comentarioQuiebre);
				$col++;
				$row++;
			}

			$estilo = [
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
						'color' => [
							'rgb' => '000000',
						],
					)
				)
			];
			$hoja->getStyle('A1' . ':' . ($hoja->getHighestColumn()) . ($hoja->getHighestRow()))->applyFromArray($estilo);
			$writer = PHPExcel_IOFactory::createWriter($spreadsheet, 'Excel5');

			ob_start();
			$writer->save('php://output');
			$xlsData = ob_get_contents();
			ob_end_clean();

			$data['error'] = EXIT_SUCCESS;
			$data['archivo'] = "data:application/vnd.ms-excel;base64," . base64_encode($xlsData);
		} catch (Exception $e) {
			$data['message'] = $e->getMessage();
		}

		echo json_encode($data);
	}

	function generarExcelOrdenCompra()
	{
		$data['error'] = EXIT_ERROR;
		$data['msj'] = null;

		try {
			
			$idUsuario = $this->session->userdata('idPersonaSession');
			$idEECC = $this->session->userdata('eeccSessionTransporte');
			
			if ($idUsuario == null || $idUsuario == '') {
                throw new Exception("Su sesion ha expirado, ingrese nuevamente.");
            }

			/*AQUI CREAMOS EL EXCEL*/
			$spreadsheet = $this->excel;

			$spreadsheet
				->getProperties()
				->setCreator('Fernando Paolo Luna Villalba')
				->setLastModifiedBy('Fernando Paolo Luna Villalba')
				->setTitle('Excel creado con PhpSpreadSheet')
				->setSubject('Excel de prueba')
				->setDescription('Excel generado como prueba')
				->setKeywords('PHPSpreadsheet')
				->setCategory('Categoría de prueba');

			$hoja = $spreadsheet->getActiveSheet();
			$hoja->getSheetView()->setZoomScale(85); // zoom por defecto a la hoja
			$hoja->setTitle('ORDEN DE COMPRA');

			$col = 0;
			$row = 1;
			$hoja->setCellValueByColumnAndRow($col, $row, 'ITEMPLAN');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'CÓDIGO SOLICITUD');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'PRESUPUESTO');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'TIPO DE OC');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'FECHA DE CREACIÓN');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'FECHA DE VALIDACIÓN');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'PEP1');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'PEP2');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'CESTA');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'ORDEN COMPRA');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'CÓDIGO CERTIFICACIÓN');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'ESTADO SOLICITUD');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'POSICIÓN');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'COSTO');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'PROYECTO');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'SUBPROYECTO');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'CÓDIGO SITIO');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'NOMBRE SITIO');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'EE.CC.');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'CONTRATO PADRE');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'CM');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'LINEA OPEX');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'CECO');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'CUENTA');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'AF');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'TIPO MONEDA');
			$col++;
			$estiloTituloColumnas = array(
				'font' => array(
					'name' => 'Calibri',
					'bold' => true,
					'color' => array(
						'rgb' => '000000',
					),
				),
				'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER
				)
			);

			$hoja->getStyle('A1:Y1')->applyFromArray($estiloTituloColumnas);

			$col = 0;
			$row = 2;
			$listaReporte = $this->m_extractor->getReporteOC($idEECC);
			
			foreach ($listaReporte as $fila) {

				$col = 0;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->itemplan);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->codigo_solicitud);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->tipo_obra);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->tipo_oc);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->fecha_creacion);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->fecha_valida);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->pep1);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->pep2);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->cesta);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->orden_compra);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->codigo_certificacion);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->estado_sol);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->posicion);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->costo_unitario_mo);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->proyectoDesc);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->subProyectoDesc);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->codigoSitio);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->nombre_sitio);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->empresaColabDesc);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->contrato_padre);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->contrato_marco);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->linea_opex);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->ceco);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->cuenta);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->area_funcional);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->tipo_moneda);
				$col++;

				$row++;
			}

			$estilo = [
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
						'color' => [
							'rgb' => '000000',
						],
					)
				)
			];
			$hoja->getStyle('A1' . ':' . ($hoja->getHighestColumn()) . ($hoja->getHighestRow()))->applyFromArray($estilo);
			$writer = PHPExcel_IOFactory::createWriter($spreadsheet, 'Excel5');

			ob_start();
			$writer->save('php://output');
			$xlsData = ob_get_contents();
			ob_end_clean();

			$data['error'] = EXIT_SUCCESS;
			$data['archivo'] = "data:application/vnd.ms-excel;base64," . base64_encode($xlsData);
		} catch (Exception $e) {
			$data['message'] = $e->getMessage();
		}

		echo json_encode($data);
	}

	function generalExcelPartidas()
	{
		$data['error'] = EXIT_ERROR;
		$data['msj'] = null;

		try {
			
			$idUsuario = $this->session->userdata('idPersonaSession');
			$idEECC = $this->session->userdata('eeccSessionTransporte');
			
			if ($idUsuario == null || $idUsuario == '') {
                throw new Exception("Su sesion ha expirado, ingrese nuevamente.");
            }

			/*AQUI CREAMOS EL EXCEL*/
			$spreadsheet = $this->excel;

			$spreadsheet
				->getProperties()
				->setCreator('COBRA PERU SAC')
				->setLastModifiedBy('COBRA PERU SAC')
				->setTitle('Excel creado con PhpSpreadSheet')
				->setSubject('Excel de prueba')
				->setDescription('Excel generado como prueba')
				->setKeywords('PHPSpreadsheet')
				->setCategory('Categoría de prueba');

			$hoja = $spreadsheet->getActiveSheet();
			$hoja->getSheetView()->setZoomScale(85); // zoom por defecto a la hoja
			$hoja->setTitle('PARTIDAS');

			$col = 0;
			$row = 1;
			$hoja->setCellValueByColumnAndRow($col, $row, 'ITEMPLAN');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'CODIGO PO');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'CODIGO');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'PARTIDA');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'CONTRATO PADRE');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'MONEDA');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'PRECIO UNITARIO');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'BAREMO');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'CANTIDAD');
			$col++;
			$hoja->setCellValueByColumnAndRow($col, $row, 'TOTAL');
			$col++;

			$estiloTituloColumnas = array(
				'font' => array(
					'name' => 'Calibri',
					'bold' => true,
					'color' => array(
						'rgb' => '000000',
					),
				),
				'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER
				)
			);

			$hoja->getStyle('A1:J1')->applyFromArray($estiloTituloColumnas);

			$col = 0;
			$row = 2;
			$listaReporte = $this->m_extractor->getReportePartidas($idEECC);

			foreach ($listaReporte as $fila) {

				$col = 0;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->itemplan);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->ptr);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->codigo);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->descPartida);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->contrato_padre);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->tipo_moneda);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->precio);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->baremo);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->cantidad_final);
				$col++;
				$hoja->setCellValueByColumnAndRow($col, $row, $fila->total);
				$col++;

				$row++;
			}

			$estilo = [
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
						'color' => [
							'rgb' => '000000',
						],
					)
				)
			];
			$hoja->getStyle('A1' . ':' . ($hoja->getHighestColumn()) . ($hoja->getHighestRow()))->applyFromArray($estilo);
			$writer = PHPExcel_IOFactory::createWriter($spreadsheet, 'Excel5');

			ob_start();
			$writer->save('php://output');
			$xlsData = ob_get_contents();
			ob_end_clean();

			$data['error'] = EXIT_SUCCESS;
			$data['archivo'] = "data:application/vnd.ms-excel;base64," . base64_encode($xlsData);
		} catch (Exception $e) {
			$data['message'] = $e->getMessage();
		}

		echo json_encode($data);
	}


	function generarLicenciaEIA()
	{
		$data['error'] = EXIT_ERROR;
		$data['msj'] = null;
		try {
			header('Content-Type: application/octet-stream');
			header("Content-Transfer-Encoding: Binary");
			header("Content-disposition: attachment; filename=\"Licencia_EIA.csv\"");
			
			$idUsuario = $this->session->userdata('idPersonaSession');
			$idEECC = $this->session->userdata('eeccSessionTransporte');
			
			if ($idUsuario == null || $idUsuario == '') {
                throw new Exception("Su sesion ha expirado, ingrese nuevamente.");
            }
			
			

			$outputBuffer = fopen("php://output", 'w');
			$flg_tipo = 3; //EIA

			$outputBuffer = fopen("php://output", 'w');
			$listaDetalle = $this->m_extractor->getLicencia($flg_tipo,$idEECC);

			if (count($listaDetalle) > 0) {
				fputcsv($outputBuffer, explode('\t', "ITEMPLAN" . "\t" .
					"ESTADO PLAN" . "\t" .
					"EE.CC." . "\t" .

					"ENTIDAD" . "\t" .
					"COD. EXPEDIENTE" . "\t" .
					"TIPO" . "\t" .
					"DISTRITO" . "\t" .
					"FECHA INICIO" . "\t" .
					"FECHA FIN" . "\t" .
					"USUARIO REG. LIC." . "\t" .
					"FECHA REG. LIC." . "\t" .
					"ESTADO LIC." . "\t" .
					"COMPROBANTE" . "\t" .
					"FECHA EMISION" . "\t" .
					"MONTO"));

				foreach ($listaDetalle as $row) {
					fputcsv($outputBuffer, explode('\t', utf8_decode(
						$row['itemplan'] . "\t" .
							$row['estadoPlanDesc'] . "\t" .
							$row['empresaColabDesc'] . "\t" .
							$row['desc_entidad'] . "\t" .
							$row['nroExpediente'] . "\t" .
							$row['tipoEntidadDesc'] . "\t" .
							$row['distritoDesc'] . "\t" .
							$row['fechaInicio'] . "\t" .

							$row['fechaFin'] . "\t" .
							$row['fechaInicio'] . "\t" .
							$row['fechaInicio'] . "\t" .
							$row['estadoLic'] . "\t" .
							$row['nroComprobante'] . "\t" .
							$row['fechaEmisionComp'] . "\t" .
							$row['montoComp']
					)));
				}
			}

			fclose($outputBuffer);
			$data['error'] = EXIT_SUCCESS;
		} catch (Exception $e) {
			$data['msj'] = 'Error interno, al crear archivo';
		}
		return $data;
	}

	function generarImpactoAmbiental()
	{
		$data['error'] = EXIT_ERROR;
		$data['msj'] = null;

		try {
			
			
			$idUsuario = $this->session->userdata('idPersonaSession');
			$idEECC = $this->session->userdata('eeccSessionTransporte');
			
			if ($idUsuario == null || $idUsuario == '') {
                throw new Exception("Su sesion ha expirado, ingrese nuevamente.");
            }

			/*AQUI CREAMOS EL EXCEL*/
			$spreadsheet = $this->excel;

			$spreadsheet
				->getProperties()
				->setCreator('Cobra Peru Sac')
				->setLastModifiedBy('Cobra Peru Sac')
				->setTitle('Excel creado con PhpSpreadSheet')
				->setSubject('Excel de prueba')
				->setDescription('Excel generado como prueba')
				->setKeywords('PHPSpreadsheet')
				->setCategory('Categoría de prueba');

			$hoja = $spreadsheet->getActiveSheet();
			$hoja->setTitle('IMPACTO AMBIENTAL');

			$hoja->setCellValue('A1', 'ITEMPLAN');
			$hoja->setCellValue('B1', 'EE.CC');
			$hoja->setCellValue('C1', 'ESTADO PLAN');
			$hoja->setCellValue('D1', 'ESTADO DE ESTUDIO AMBIENTAL');
			$hoja->setCellValue('E1', 'TIPO ESTUDIO AMBIENTAL');
			$hoja->setCellValue('F1', 'USUARIO REG ESTUDIO AMBIENTAL');
			$hoja->setCellValue('G1', 'FECHA REG ESTUDIO AMBIENTAL');
			$hoja->setCellValue('H1', 'DISTRITO');
			$hoja->setCellValue('I1', 'FECHA INICIO EXPEDIENTE');
			$hoja->setCellValue('J1', 'FECHA FIN EXPEDIENTE');
			$hoja->setCellValue('K1', 'COD EXPEDIENTE');
			$hoja->setCellValue('L1', 'FECHA REG. COMPROBANTE');
			$hoja->setCellValue('M1', 'FECHA EMISION COMPROBANTE');
			$hoja->setCellValue('N1', 'MONTO COMPROBANTE');
			$hoja->setCellValue('O1', 'NRO COMPROBANTE');
			// $hoja->setCellValue('D1', 'ENTIDAD');

			$impactoAmbiental = $this->m_extractor->getEntidadAmbiental(11,$idEECC);

			$row = 2;
			foreach ($impactoAmbiental as $d) {

				$hoja->setCellValue('A' . $row, $d['itemplan']);
				$hoja->setCellValue('B' . $row, $d['empresaColabDesc']);
				$hoja->setCellValue('C' . $row, $d['estadoPlanDesc']);
				$hoja->setCellValue('D' . $row, $d['entidadEstadoDesc']);
				$hoja->setCellValue('E' . $row, $d['nombre']);
				$hoja->setCellValue('F' . $row, $d['id_usuario_reg_lic']);
				$hoja->setCellValue('G' . $row, $d['fechaInicio']);
				$hoja->setCellValue('H' . $row, $d['distritoDesc']);
				$hoja->setCellValue('I' . $row, $d['fechaInicio']);
				$hoja->setCellValue('J' . $row, $d['fechaFin']);
				$hoja->setCellValue('K' . $row, $d['nroExpediente']);
				$hoja->setCellValue('L' . $row, $d['fechaRegistroComprobante']);
				$hoja->setCellValue('M' . $row, $d['fechaEmisionComprobante']);
				$hoja->setCellValue('N' . $row, $d['montoComprobante']);
				$hoja->setCellValue('O' . $row, $d['nroComprobante']);
				// $hoja->setCellValue('D' . $row, $d['desc_entidad']);

				$row++;
			}

			$hoja2 = $spreadsheet->createSheet(1);
			$hoja2->setTitle('COMPROMISOS');

			$hoja2->setCellValue('A1', 'ITEMPLAN');
			$hoja2->setCellValue('B1', 'COMPROMISO');
			$hoja2->setCellValue('C1', 'FECHA INICIO COMPROMISO');
			$hoja2->setCellValue('D1', 'FECHA FIN COMPROMISO');
			$hoja2->setCellValue('E1', 'RESPONSABLE COMPROMISO');
			$hoja2->setCellValue('F1', 'ESTADO COMPROMISO');
			$hoja2->setCellValue('G1', 'PLAN COMPROMISO');
			$hoja2->setCellValue('H1', 'MEDIDAS COMPROMISO');

			$compromisoEntidad = $this->m_extractor->getCompromisoEntidad($idEECC);

			$row2 = 2;
			foreach ($compromisoEntidad as $d) {

				$hoja2->setCellValue('A' . $row2, $d['itemplan']);
				$hoja2->setCellValue('B' . $row2, $d['compromisoDesc']);
				$hoja2->setCellValue('C' . $row2, $d['fechaInicioCompromiso']);
				$hoja2->setCellValue('D' . $row2, $d['fechaFinCompromiso']);
				$hoja2->setCellValue('E' . $row2, $d['nombreUsuarioCompromiso']);
				$hoja2->setCellValue('F' . $row2, $d['compromisoEstadoDesc']);
				$hoja2->setCellValue('G' . $row2, $d['planCompromiso']);
				$hoja2->setCellValue('H' . $row2, $d['medidasCompromiso']);

				$row2++;
			}

			// HOJA 3
			$hoja3 = $spreadsheet->createSheet(2);
			$hoja3->setTitle('EVIDENCIAS');

			$hoja3->setCellValue('A1', 'ITEMPLAN');
			$hoja3->setCellValue('B1', 'TIPO EVIDENCIA');
			$hoja3->setCellValue('C1', 'DESCRIPCION');
			$hoja3->setCellValue('D1', 'FECHA INICIO');
			$hoja3->setCellValue('E1', 'FECHA FIN');

			$entidadEvidencias = $this->m_extractor->getEntidadEvidencia($idEECC);

			$row3 = 2;
			foreach ($entidadEvidencias as $d) {

				$hoja3->setCellValue('A' . $row3, $d['itemplan']);
				$hoja3->setCellValue('B' . $row3, $d['descripcionTipoEvidencia']);
				$hoja3->setCellValue('C' . $row3, $d['descripcion']);
				$hoja3->setCellValue('D' . $row3, $d['fechaInicio']);
				$hoja3->setCellValue('E' . $row3, $d['fechaFin']);

				$row3++;
			}

			$estilo = [
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
						'color' => [
							'rgb' => '000000',
						],
					)
				)
			];
			$hoja->getStyle('A1' . ':' . ($hoja->getHighestColumn()) . ($hoja->getHighestRow()))->applyFromArray($estilo);
			$writer = PHPExcel_IOFactory::createWriter($spreadsheet, 'Excel5');

			ob_start();
			$writer->save('php://output');
			$xlsData = ob_get_contents();
			ob_end_clean();

			$data['error'] = EXIT_SUCCESS;
			$data['archivo'] = "data:application/vnd.ms-excel;base64," . base64_encode($xlsData);
		} catch (Exception $e) {
			$data['message'] = $e->getMessage();
		}

		echo json_encode($data);
	}
	
	function generarFirmaDigital()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        try {


			$idUsuario = $this->session->userdata('idPersonaSession');
			
			if ($idUsuario == null || $idUsuario == '') {
                throw new Exception("Su sesion ha expirado, ingrese nuevamente.");
            }
			
			
            /*AQUI CREAMOS EL EXCEL*/
            $spreadsheet = $this->excel;

            $spreadsheet
                ->getProperties()
                ->setCreator('Cobra Peru Sac')
                ->setLastModifiedBy('Cobra Peru Sac')
                ->setTitle('Excel creado con PhpSpreadSheet')
                ->setSubject('Excel de prueba')
                ->setDescription('Excel generado como prueba')
                ->setKeywords('PHPSpreadsheet')
                ->setCategory('Categoría de prueba');

            $hoja = $spreadsheet->getActiveSheet();
            $hoja->setTitle('FIRMA');

            $hoja->setCellValue('A1', 'CODIGO SOLICITUD');
            $hoja->setCellValue('B1', 'ITEMPLAN');
			$hoja->setCellValue('C1', 'SUBPROYECTO');
			$hoja->setCellValue('D1', 'EECC');
            $hoja->setCellValue('E1', 'ESTADO FIRMA');
			$hoja->setCellValue('F1', 'RESPONSABLE');
			$hoja->setCellValue('G1', 'FECHA');
			$hoja->setCellValue('H1', 'MONTO');

            $strPerfil  = $this->session->userdata('idPerfilSession');
            $iddEECC = null;
            $flg_perfil = explode(",", $strPerfil);
            if ((in_array(5, $flg_perfil) || in_array(13, $flg_perfil) || in_array(17, $flg_perfil) || in_array(19, $flg_perfil) || in_array(22, $flg_perfil) || in_array(48, $flg_perfil))) {
                $iddEECC = $this->session->userdata('eeccSessionTransporte');
            }

            $detalle = $this->m_extractor->getReporteExtractorOcFirma($iddEECC, $idUsuario);

            $row = 2;
            foreach ($detalle as $key => $d) {

                $hoja->setCellValue('A' . $row, $d->codigo_solicitud_oc);
                $hoja->setCellValue('B' . $row, $d->itemplan);
				$hoja->setCellValue('C' . $row, $d->subProyectoDesc);
				$hoja->setCellValue('D' . $row, $d->empresaColabDesc);
                $hoja->setCellValue('E' . $row, $d->estadoFirmaDesc);
				$hoja->setCellValue('F' . $row, $d->nombreUsuario);
				$hoja->setCellValue('G' . $row, $d->fecha);
				$hoja->setCellValue('H' . $row, $d->costo_unitario_mo);

                $row++;
            }


            $writer = PHPExcel_IOFactory::createWriter($spreadsheet, 'Excel5');

            ob_start();
            $writer->save('php://output');
            $xlsData = ob_get_contents();
            ob_end_clean();

            $data['error'] = EXIT_SUCCESS;
            $data['archivo'] = "data:application/vnd.ms-excel;base64," . base64_encode($xlsData);
        } catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

        echo json_encode($data);
    }
	
	function generarPartidaSBE()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        try {
			
			$idUsuario = $this->session->userdata('idPersonaSession');
			$idEECC = $this->session->userdata('eeccSessionTransporte');
			
			if ($idUsuario == null || $idUsuario == '') {
                throw new Exception("Su sesion ha expirado, ingrese nuevamente.");
            }

            /*AQUI CREAMOS EL EXCEL*/
            $spreadsheet = $this->excel;

            $spreadsheet
                ->getProperties()
                ->setCreator('Cobra Peru Sac')
                ->setLastModifiedBy('Cobra Peru Sac')
                ->setTitle('Excel creado con PhpSpreadSheet')
                ->setSubject('Excel de prueba')
                ->setDescription('Excel')
                ->setKeywords('PHPSpreadsheet')
                ->setCategory('Categoría');

            $hoja = $spreadsheet->getActiveSheet();
            $hoja->setTitle('FIRMA');

            $hoja->setCellValue('A1', 'CODIGO');
            $hoja->setCellValue('B1', 'PARTIDA');
			$hoja->setCellValue('C1', 'SUBPROYECTO');
			$hoja->setCellValue('D1', 'EECC');
            $hoja->setCellValue('E1', 'ZONA');
			$hoja->setCellValue('F1', 'COSTO');

            $strPerfil  = $this->session->userdata('idPerfilSession');
            $flg_perfil = explode(",", $strPerfil);
            // if ((in_array(5, $flg_perfil) || in_array(13, $flg_perfil) || in_array(17, $flg_perfil) || in_array(19, $flg_perfil) || in_array(22, $flg_perfil) || in_array(48, $flg_perfil))) {
                
            // }
            $detalle = $this->m_extractor->getReportePartidaSBE($idEECC, $idUsuario);

            $row = 2;
            foreach ($detalle as $key => $d) {

                $hoja->setCellValue('A' . $row, $d->codigo);
                $hoja->setCellValue('B' . $row, $d->partidaDesc);
				$hoja->setCellValue('C' . $row, $d->subProyectoDesc);
				$hoja->setCellValue('D' . $row, $d->empresaColabDesc);
                $hoja->setCellValue('E' . $row, $d->zona);
				$hoja->setCellValue('F' . $row, $d->costo);

                $row++;
            }


            $writer = PHPExcel_IOFactory::createWriter($spreadsheet, 'Excel5');

            ob_start();
            $writer->save('php://output');
            $xlsData = ob_get_contents();
            ob_end_clean();

            $data['error'] = EXIT_SUCCESS;
            $data['archivo'] = "data:application/vnd.ms-excel;base64," . base64_encode($xlsData);
        } catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

        echo json_encode($data);
    }
	
	function generarFirmaDigitalItemMadre()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        try {


			$idUsuario = $this->session->userdata('idPersonaSession');
			
			if ($idUsuario == null || $idUsuario == '') {
                throw new Exception("Su sesion ha expirado, ingrese nuevamente.");
            }
			
			
            /*AQUI CREAMOS EL EXCEL*/
            $spreadsheet = $this->excel;

            $spreadsheet
                ->getProperties()
                ->setCreator('Cobra Peru Sac')
                ->setLastModifiedBy('Cobra Peru Sac')
                ->setTitle('Excel creado con PhpSpreadSheet')
                ->setSubject('Excel de prueba')
                ->setDescription('Excel generado como prueba')
                ->setKeywords('PHPSpreadsheet')
                ->setCategory('Categoría de prueba');

            $hoja = $spreadsheet->getActiveSheet();
            $hoja->setTitle('FIRMA');

            $hoja->setCellValue('A1', 'CODIGO SOLICITUD');
            $hoja->setCellValue('B1', 'ITEMPLAN');
			$hoja->setCellValue('C1', 'SUBPROYECTO');
			$hoja->setCellValue('D1', 'EECC');
            $hoja->setCellValue('E1', 'ESTADO FIRMA');
			$hoja->setCellValue('F1', 'RESPONSABLE');
			$hoja->setCellValue('G1', 'FECHA');
			$hoja->setCellValue('H1', 'MONTO');

            $strPerfil  = $this->session->userdata('idPerfilSession');
            $iddEECC = null;
            $flg_perfil = explode(",", $strPerfil);
            if ((in_array(5, $flg_perfil) || in_array(13, $flg_perfil) || in_array(17, $flg_perfil) || in_array(19, $flg_perfil) || in_array(22, $flg_perfil) || in_array(48, $flg_perfil))) {
                $iddEECC = $this->session->userdata('eeccSessionTransporte');
            }

            $detalle = $this->m_extractor->getReporteExtractorOcFirmaItemMadre($iddEECC, $idUsuario);

            $row = 2;
            foreach ($detalle as $key => $d) {

                $hoja->setCellValue('A' . $row, $d->codigo_solicitud_oc);
                $hoja->setCellValue('B' . $row, $d->itemplan);
				$hoja->setCellValue('C' . $row, $d->subProyectoDesc);
				$hoja->setCellValue('D' . $row, $d->empresaColabDesc);
                $hoja->setCellValue('E' . $row, $d->estadoFirmaDesc);
				$hoja->setCellValue('F' . $row, $d->nombreUsuario);
				$hoja->setCellValue('G' . $row, $d->fecha);
				$hoja->setCellValue('H' . $row, $d->costo_unitario_mo);

                $row++;
            }


            $writer = PHPExcel_IOFactory::createWriter($spreadsheet, 'Excel5');

            ob_start();
            $writer->save('php://output');
            $xlsData = ob_get_contents();
            ob_end_clean();

            $data['error'] = EXIT_SUCCESS;
            $data['archivo'] = "data:application/vnd.ms-excel;base64," . base64_encode($xlsData);
        } catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

        echo json_encode($data);
    }
	
	
	function generarExcelSolOcFijaMasDep()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        try {


			$idUsuario = $this->session->userdata('idPersonaSession');
			
			if ($idUsuario == null || $idUsuario == '') {
                throw new Exception("Su sesion ha expirado, ingrese nuevamente.");
            }
			
			
            /*AQUI CREAMOS EL EXCEL*/
            $spreadsheet = $this->excel;

            $spreadsheet
                ->getProperties()
                ->setCreator('Cobra Peru Sac')
                ->setLastModifiedBy('Cobra Peru Sac')
                ->setTitle('Excel creado con PhpSpreadSheet')
                ->setSubject('Excel de prueba')
                ->setDescription('Excel generado')
                ->setKeywords('PHPSpreadsheet');

            $hoja = $spreadsheet->getActiveSheet();
            $hoja->setTitle('FIRMA');

            $hoja->setCellValue('A1', 'CAPA');
            $hoja->setCellValue('B1', 'CODIGO SOLICITUD');
			$hoja->setCellValue('C1', 'IMPORTE');
			$hoja->setCellValue('D1', 'ITEMPLAN');
            $hoja->setCellValue('E1', 'ORDEN COMPRA');
			$hoja->setCellValue('F1', 'PEP1');
			$hoja->setCellValue('G1', 'PROYECTO');
			$hoja->setCellValue('H1', 'IMPORTE SAP');
			$hoja->setCellValue('I1', 'FECHA CREACION');
			$hoja->setCellValue('J1', 'FECHA VALIDA');

            $strPerfil  = $this->session->userdata('idPerfilSession');
            $iddEECC = null;
            $iddEECC = $this->session->userdata('eeccSessionTransporte');


            $detalle = $this->m_extractor->getDataSolicitudOcFijaMasDepliegue($iddEECC);

            $row = 2;
            foreach ($detalle as $key => $d) {

                $hoja->setCellValue('A' . $row, $d->capaDesc);
                $hoja->setCellValue('B' . $row, $d->codigo_solicitud);
				$hoja->setCellValue('C' . $row, $d->costo_unitario_mo);
				$hoja->setCellValue('D' . $row, $d->itemplan);
                $hoja->setCellValue('E' . $row, $d->orden_compra);
				$hoja->setCellValue('F' . $row, $d->pep1);
				$hoja->setCellValue('G' . $row, $d->proyectoDesc);
				$hoja->setCellValue('H' . $row, $d->costo_sap);
				$hoja->setCellValue('I' . $row, $d->fecha_creacion);
				$hoja->setCellValue('J' . $row, $d->fecha_valida);

                $row++;
            }


            $writer = PHPExcel_IOFactory::createWriter($spreadsheet, 'Excel5');

            ob_start();
            $writer->save('php://output');
            $xlsData = ob_get_contents();
            ob_end_clean();

            $data['error'] = EXIT_SUCCESS;
            $data['archivo'] = "data:application/vnd.ms-excel;base64," . base64_encode($xlsData);
        } catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

        echo json_encode($data);
    }
}
