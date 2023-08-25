<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class C_registro_masivo_planobra_pi extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=UTF-8');
		$this->load->model('mf_plan_obra/m_registro_masivo_planobra_pi');
        $this->load->model('mf_plan_obra/m_planobra');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->library('excel');
        $this->load->helper('url');
    }

    public function index() {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {
            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $data['tbObservacion'] = $this->basicHtml();
            $permisos = $this->session->userdata('permisosArbolTransporte');
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_PLANTA_INTERNA, ID_PERMISO_HIJO_REGINDPI_OBRA, ID_MODULO_PAQUETIZADO);
            $data['opciones'] = utf8_encode($result['html']);
            $data['title'] = 'REGISTRO MASIVO DE OBRA';
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_plan_obra/v_registro_masivo_planobra_pi', $data);
            } else {
                redirect('login', 'refresh');
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function getFormatoExcelCarga() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
          
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
            $hoja->getSheetView()->setZoomScale(85);
			$hoja->setTitle('FORMATO CARGA');
             
            $col = 0;
			$row = 1;

            $hoja->setCellValueByColumnAndRow($col, $row, 'PROYECTO');
            $hoja->getCellByColumnAndRow($col, $row)->getStyle()->getFill()->setFillType('solid')->getStartColor()->setARGB('FFFFFF00');
            $col++;
            $hoja->setCellValueByColumnAndRow($col, $row, 'SUBPROYECTO');
            $hoja->getCellByColumnAndRow($col, $row)->getStyle()->getFill()->setFillType('solid')->getStartColor()->setARGB('FFFFFF00');
            $col++;
            $hoja->setCellValueByColumnAndRow($col, $row, 'FASE');
            $hoja->getCellByColumnAndRow($col, $row)->getStyle()->getFill()->setFillType('solid')->getStartColor()->setARGB('FFFFFF00');
            $col++;
            $hoja->setCellValueByColumnAndRow($col, $row, 'PROVEEDOR');
            $hoja->getCellByColumnAndRow($col, $row)->getStyle()->getFill()->setFillType('solid')->getStartColor()->setARGB('FFFFFF00');
            $col++;
            $hoja->setCellValueByColumnAndRow($col, $row, 'CONTRATO MARCO');
            $hoja->getCellByColumnAndRow($col, $row)->getStyle()->getFill()->setFillType('solid')->getStartColor()->setARGB('FFFFFF00');
            $col++;
            $hoja->setCellValueByColumnAndRow($col, $row, 'CÓDIGO SITIO');
            $col++;
            // $hoja->setCellValueByColumnAndRow($col, $row, 'DEPARTAMENTO');
            // $col++;
            // $hoja->setCellValueByColumnAndRow($col, $row, 'PROVINCIA');
            // $col++;
            // $hoja->setCellValueByColumnAndRow($col, $row, 'DISTRITO');
            // $col++;
            // $hoja->setCellValueByColumnAndRow($col, $row, 'NOMBRE ESTACIÓN');
            // $col++;
            $hoja->setCellValueByColumnAndRow($col, $row, 'COORDENADAS X');
            $col++;
            $hoja->setCellValueByColumnAndRow($col, $row, 'COORDENADAS Y');
            $col++;
            $hoja->setCellValueByColumnAndRow($col, $row, 'REQUIERE HARDWARE?');
            $hoja->getCellByColumnAndRow($col, $row)->getStyle()->getFill()->setFillType('solid')->getStartColor()->setARGB('FFFFFF00');
            $col++;
            $hoja->setCellValueByColumnAndRow($col, $row, 'INDICADOR');
            $hoja->getCellByColumnAndRow($col, $row)->getStyle()->getFill()->setFillType('solid')->getStartColor()->setARGB('FFFFFF00');
            $col++;
            $hoja->setCellValueByColumnAndRow($col, $row, 'DESCRIPCIÓN OPCIONAL');
            $col++;
            $hoja->setCellValueByColumnAndRow($col, $row, 'PEP2');
            $col++;
            $hoja->setCellValueByColumnAndRow($col, $row, 'CECO');
            $col++;
            $hoja->setCellValueByColumnAndRow($col, $row, 'CUENTA');
            $col++;
            $hoja->setCellValueByColumnAndRow($col, $row, 'AREA FUNCIONAL');
            $col++;
            $hoja->setCellValueByColumnAndRow($col, $row, 'TIPO DE ESTUDIO');
            $col++;
            $hoja->setCellValueByColumnAndRow($col, $row, 'SEGMENTO');
            $col++;
            $hoja->setCellValueByColumnAndRow($col, $row, 'SERVICIO');
            $col++;
            $hoja->setCellValueByColumnAndRow($col, $row, 'VELOCIDAD');
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

            $hoja->getStyle('A1:S1')->applyFromArray($estiloTituloColumnas);

            $writer = PHPExcel_IOFactory::createWriter($spreadsheet, 'Excel5');
			ob_start();
            $writer->save('php://output');
            $xlsData = ob_get_contents();
            ob_end_clean();

			$data['error'] = EXIT_SUCCESS;
			$data['archivo'] = "data:application/vnd.ms-excel;base64," . base64_encode($xlsData);
            $data['nombreArchivo'] = 'Fmt_Reg_Masivo_Obra_Pin' . date("YmdHis") . '.xls';

            
        } catch (Exception $e) {
            $data['msj'] = 'Error interno, al crear archivo de carga masiva';
        }
        // return $data;
        echo json_encode($data);
    }


    public function procesarFileRegMasivoObraPin()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

			$idUsuarioSession = $this->session->userdata('idPersonaSession');

			if (!isset($idUsuarioSession)) {
                throw new Exception('Su sesión ha expirado, ingrese nuevamente!!');
            }
			if(count($_FILES) == 0){
				throw new Exception('Debe seleccionar un archivo para procesar data!!');
			}

            $nombreArchivo = $_FILES['file']['name'];
            $tipoArchivo = $_FILES['file']['type'];
            $nombreFicheroTemp = $_FILES['file']['tmp_name'];
            $tamano_archivo = $_FILES['file']['size'];

            $arryNombreArchivo = explode(".", $nombreArchivo);

            $arrayTipos = array(
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
				'application/vnd.ms-excel'
			);

            if (!in_array($tipoArchivo, $arrayTipos)) {
                throw new Exception('Sólo puede subir archivos de tipo excel (.xls , .xlsx)!!');
            }

            if (!file_exists("./uploads/reg_masivo_obra_pin")) {
                if (!mkdir("./uploads/reg_masivo_obra_pin")) {
                    throw new Exception('Hubo un error al crear la carpeta reg_masivo_obra_pin!!');
                }
            }

			$objectExcel = PHPExcel_IOFactory::load($nombreFicheroTemp);

			list($html, $contador, $arrayFinal) = $this->makeHTMLTablaObservacion($objectExcel);
			$data['titulo'] = 'Se muestran la cantidad registros a cargar ('.$contador.') ';
			$data['tbReporte'] = $html;
			$data['jsonDataFile'] = json_encode($arrayFinal);
			$data['msj']  = 'Se procesó correctamente el archivo!!';
			$data['error']  = EXIT_SUCCESS;

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

    public function makeHTMLTablaObservacion($objectExcel)
    {
        $html = '
                <table id="data-table" class="table table-bordered" style="font-size: 10px">
                    <thead class="thead-default">
                        <tr>
                            <th style="text-align: center; vertical-align: middle;">#</th>
                            <th style="text-align: center; vertical-align: middle;">DESCRIPCIÓN OPCIONAL</th>
							<th style="text-align: center; vertical-align: middle;">PROYECTO</th>
                            <th style="text-align: center; vertical-align: middle;">SUBPROYECTO</th>
                            <th style="text-align: center; vertical-align: middle;">TIPO</th>
							<th style="text-align: center; vertical-align: middle;">FASE</th>
                            <th style="text-align: center; vertical-align: middle;">PROVEEDOR</th>
							<th style="text-align: center; vertical-align: middle;">CONTRATO MARCO</th>
                            <th style="text-align: center; vertical-align: middle;">VIGENCIA</th>
                            <th style="text-align: center; vertical-align: middle;">TIPO MONEDA</th>
                            <th style="text-align: center; vertical-align: middle;">CÓDIGO SITIO</th>
                            <th style="text-align: center; vertical-align: middle;">COORDENADAS X</th>
                            <th style="text-align: center; vertical-align: middle;">COORDENADAS Y</th>
                            <th style="text-align: center; vertical-align: middle;">NOMBRE ESTACIÓN</th>
                            <th style="text-align: center; vertical-align: middle;">DEPARTAMENTO</th>
                            <th style="text-align: center; vertical-align: middle;">PROVINCIA</th>
                            <th style="text-align: center; vertical-align: middle;">DISTRITO</th>
                            <th style="text-align: center; vertical-align: middle;">REQUIERE HARDWARE?</th>
                            <th style="text-align: center; vertical-align: middle;">SISEGO</th>
                            <th style="text-align: center; vertical-align: middle;">PEP2</th>
                            <th style="text-align: center; vertical-align: middle;">CECO</th>
                            <th style="text-align: center; vertical-align: middle;">CUENTA</th>
                            <th style="text-align: center; vertical-align: middle;">ÁREA FUNCIONAL</th>
                            <th style="text-align: center; vertical-align: middle;">TIPO DE ESTUDIO</th>
                            <th style="text-align: center; vertical-align: middle;">SEGMENTO</th>
                            <th style="text-align: center; vertical-align: middle;">SERVICIO</th>
                            <th style="text-align: center; vertical-align: middle;">VELOCIDAD</th>
                            <th style="text-align: center; vertical-align: middle;">OBSERVACIÓN</th>
                        </tr>
                    </thead>

                    <tbody>';

        $count = 1;
		$arrayFinal = array();
		$ctnVal = 0;
        $observacion = '';

        if ($objectExcel != '') {
			$col = 1;
			foreach ($objectExcel->getWorksheetIterator() as $worksheet) {
				$highestRow = $worksheet->getHighestRow();
				$highestColumn = $worksheet->getHighestColumn();

				// $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
                // $row_dimension = $objPHPExcel->getActiveSheet()->getHighestRowAndColumn();

				for ($row = 2; $row <= $highestRow; $row++) {
					$col = 0;
					$proyectoDesc = _removeEnterYTabs($worksheet->getCellByColumnAndRow($col, $row)->getValue());
					$col++;
                    $subProyectoDesc = _removeEnterYTabs($worksheet->getCellByColumnAndRow($col, $row)->getValue());
                    $col++;
                    $faseDesc = _removeEnterYTabs($worksheet->getCellByColumnAndRow($col, $row)->getValue());
                    $col++;
                    $empresaColabDesc = _removeEnterYTabs($worksheet->getCellByColumnAndRow($col, $row)->getValue());
                    $col++;
                    $contratoMarco = _removeEnterYTabs($worksheet->getCellByColumnAndRow($col, $row)->getValue());
                    $col++;
                    $codigoSitio = _removeEnterYTabs($worksheet->getCellByColumnAndRow($col, $row)->getValue());
                    $col++;
                    $coodX = _removeEnterYTabs($worksheet->getCellByColumnAndRow($col, $row)->getValue());
                    $col++;
                    $coodY = _removeEnterYTabs($worksheet->getCellByColumnAndRow($col, $row)->getValue());
                    $col++;
                    $requiereHardware = _removeEnterYTabs($worksheet->getCellByColumnAndRow($col, $row)->getValue());
                    $col++;
                    $indicador = _removeEnterYTabs($worksheet->getCellByColumnAndRow($col, $row)->getValue());
                    $col++;
                    $descripcionOpcional = _removeEnterYTabs($worksheet->getCellByColumnAndRow($col, $row)->getValue());
                    $col++;
                    $pep2 = _removeEnterYTabs($worksheet->getCellByColumnAndRow($col, $row)->getValue());
                    $col++;
                    $ceco = _removeEnterYTabs($worksheet->getCellByColumnAndRow($col, $row)->getValue());
                    $col++;
                    $cuenta = _removeEnterYTabs($worksheet->getCellByColumnAndRow($col, $row)->getValue());
                    $col++;
                    $areaFuncional = _removeEnterYTabs($worksheet->getCellByColumnAndRow($col, $row)->getValue());
                    $col++;
                    $tipoEstudio = _removeEnterYTabs($worksheet->getCellByColumnAndRow($col, $row)->getValue());
                    $col++;
                    $segmento = _removeEnterYTabs($worksheet->getCellByColumnAndRow($col, $row)->getValue());
                    $col++;
                    $servicio = _removeEnterYTabs($worksheet->getCellByColumnAndRow($col, $row)->getValue());
                    $col++;
                    $velocidad = _removeEnterYTabs($worksheet->getCellByColumnAndRow($col, $row)->getValue());
                    $col++;


                    $idZonal = null;
                    $idCentral = null;
                    $flgCodigoSitio = 0;
                    $flgHardware = '0';
                    $nombrePlan = null;
                    $observacion = '';

                    $tipoCapexOpex = '';
                    $vigencia = null;
                    $tipoMoneda = null;

                    $departamento_matriz = null;
                    $provincia_matriz = null;
                    $distrito_matriz = null;
                    $nombreEstacion = null;

                    $tipoCentral = null;
                    $idContrato = null;
                    $idFase = null;
                    $idProyecto = null;

                    if($proyectoDesc == null || $proyectoDesc == '' || strlen($proyectoDesc) == 0){
                        $observacion .= 'DEBE INGRESAR UN PROYECTO.<br>';
                    }else{
                        $infoProyecto = $this->m_registro_masivo_planobra_pi->getInfoProyectoByDesc($proyectoDesc);
                        if($infoProyecto == null){
                            $observacion .= 'NO SE ENCONTRO EL PROYECTO.<br>';
                        }else{
                            $idProyecto = $infoProyecto['idProyecto'];
                            // if($infoProyecto['isSisego'] == 1 && strlen($indicador) == 0 || $indicador == null || $indicador == ''){
                            //     $observacion .= 'DEBE INGRESAR UN SISEGO.<br>';
                            // }
                        }
                    }

                    if($faseDesc == null || $faseDesc == '' || strlen($faseDesc) == 0){
                        $observacion .= 'DEBE INGRESAR UNA FASE.<br>';
                    }else{
                        $infoFase = $this->m_registro_masivo_planobra_pi->getInfoFaseByDesc($faseDesc);
                        if($infoFase == null){
                            $observacion .= 'NO SE ENCONTRO LA FASE.<br>';
                        }else{
                            $idFase = $infoFase['idFase'];
                        }
                    }


                    if($subProyectoDesc == null || $subProyectoDesc == '' || strlen($subProyectoDesc) == 0){
                        $observacion .= 'DEBE INGRESAR UN SUBPROYECTO.<br>';
                    }else{
                        $infoSubProyecto = $this->m_registro_masivo_planobra_pi->getInfoSubProyectoByDesc($subProyectoDesc);
                        if($infoSubProyecto == null){
                            $observacion .= 'NO SE ENCONTRO EL SUBPROYECTO.<br>';
                        }else{
                            if($infoSubProyecto['flg_opex'] == 1){#capex
                                $tipoCapexOpex = 'CAPEX';
                                if($infoSubProyecto['flg_reg_item_capex_opex'] == 2){#manual
                                    if($pep2 == null || $pep2 == '' || strlen($pep2) == 0){
                                        $observacion .= 'DEBE INGRESAR UNA PEP2.<br>';
                                    }else{
                                        $arrayPep = explode('-', $pep2);
                                        if(count($arrayPep) != 6 || strlen($pep2) != 24){
                                            $observacion .= 'FORMATO DE PEP2 NO VÁLIDO.<br>';
                                        }
                                        $cantidad = $this->m_utils->existePep1enMargen(substr($pep2,0,20));
                                        if($cantidad == 0){
                                            $observacion .='Debe ingresar una pep2 que exista en el margen.<br>';
                                        }
                                    }
                                    
                                }
                            }else{#opex
                                $tipoCapexOpex = 'OPEX';
                                if($infoSubProyecto['flg_reg_item_capex_opex'] == 2){#manual
                                    if($ceco == null || $ceco == '' || strlen($ceco) == 0){
                                        $observacion .= 'DEBE INGRESAR UN CECO.<br>';
                                    }else{
                                        if(substr($ceco,0,1) == 'E'){
                                            if($areaFuncional == null || $areaFuncional == '' || strlen($areaFuncional) == 0){
                                                $observacion .= 'DEBE INGRESAR UN AREA FUNCIONAL.<br>';
                                            }
                                        }else{
                                            if($cuenta == null || $cuenta == '' || strlen($cuenta) == 0){
                                                $observacion .= 'DEBE INGRESAR UNA CUENTA.<br>';
                                            }
                                            if($areaFuncional == null || $areaFuncional == '' || strlen($areaFuncional) == 0){
                                                $observacion .= 'DEBE INGRESAR UN AREA FUNCIONAL.<br>';
                                            }
                                        }
                                    }
                                }else{#automatico
                                    $infoCombinatoriaOpex = $this->m_registro_masivo_planobra_pi->getInfoCombinatoriaOpex($infoSubProyecto['idSubProyecto'],$idFase);
                                    if($infoCombinatoriaOpex == null){
                                        $observacion .= 'NO SE ENCONTRO LA COMBINATORIA.<br>';
                                    }else{
                                        $ceco = $infoCombinatoriaOpex['ceco'];
                                        $cuenta = $infoCombinatoriaOpex['cuenta'];
                                        $areaFuncional = $infoCombinatoriaOpex['areafuncional'];
                                        if($ceco == null || $ceco == '' || strlen($ceco) == 0){
                                            $observacion .= 'NO SE ENCONTRO CECO EN LA COMBINATORIA.<br>';
                                        }
                                        if($cuenta == null || $cuenta == '' || strlen($cuenta) == 0){
                                            $observacion .= 'NO SE ENCONTRO CUENTA EN LA COMBINATORIA.<br>';
                                        }
                                        if($areaFuncional == null || $areaFuncional == '' || strlen($areaFuncional) == 0){
                                            $observacion .= 'NO SE ENCONTRO AREA FUNCIONAL EN LA COMBINATORIA.<br>';
                                        }
                                    }
                                }
                            }
                        }
                    }

                    if($empresaColabDesc == null || $empresaColabDesc == '' || strlen($empresaColabDesc) == 0){
                        $observacion .= 'DEBE INGRESAR UN PROVEEDOR.<br>';
                    }else{
                        $infoEmpresaColab = $this->m_registro_masivo_planobra_pi->getInfoEECCByDesc($empresaColabDesc);
                        if($infoEmpresaColab == null){
                            $observacion .= 'NO SE ENCONTRO EL PROVEEDOR.<br>';
                        }
                    }

                    if($contratoMarco == null || $contratoMarco == '' || strlen($contratoMarco) == 0){
                        $observacion .= 'DEBE INGRESAR UN CONTRATO MARCO.<br>';
                    }else{
                        $infoContratoMarco = $this->m_registro_masivo_planobra_pi->getInfoContratoMarcoByDesc($contratoMarco);
                        if($infoContratoMarco == null){
                            $observacion .= 'NO SE ENCONTRO EL CONTRATO MARCO.<br>';
                        }else{
                            $vigencia = $infoContratoMarco['vigencia'];
                            $tipoMoneda = $infoContratoMarco['tipo_moneda'];
                            $idContrato = $infoContratoMarco['id_contrato'];
                        }
                    }

                    if($codigoSitio != null && $codigoSitio != '' && strlen($codigoSitio) != 0){
                            $url = "https://www.plandeobras.com/sam/api_listar_validate_codigounico";
                            $request = "POST";
                            $headers = [
                                'Accept: application/json;charset=UTF-8',
                                'Content-Type: application/json',
                            ];
                            $dataSend = json_encode(["CodigoUnico" => $codigoSitio]);

                            $responseWS = json_decode( json_encode(_sendDataToURL($url, $request, $headers, $dataSend )) , true);
                            if($responseWS['flag'] == 0 || $responseWS['flag'] == '0'){
                                $observacion .= 'NO EXISTE EL CÓDIGO ÚNICO.<br>';
                            }else{
                                $coodX = $responseWS['Longitud'];
                                $coodY = $responseWS['Latitud'];
                                $departamento_matriz = $responseWS['Departamento'];
                                $provincia_matriz = $responseWS['Provincia'];
                                $distrito_matriz = $responseWS['Provincia'];
                                $nombreEstacion = $responseWS['NombreEstacion'];
                                $flgCodigoSitio = 1;
                                if(strlen($coodX) == 0 || $coodX == null || $coodX == ''){
                                    $observacion .= 'NO SE ENCONTRARON COORDENADAS X EN EL CÓDIGO DE SITIO.<br>';
                                }
                                if(strlen($coodY) == 0 || $coodY == null || $coodY == ''){
                                    $observacion .= 'NO SE ENCONTRARON COORDENADAS Y EN EL CÓDIGO DE SITIO.<br>';
                                }
                                if(strlen($coodX) != 0 && $coodX != null && $coodX != '' && strlen($coodY) != 0 && $coodY != null && $coodY != ''){
                                    $arrayIdCentral = $this->m_utils->getMdfCoord($coodY, $coodX);
                                    if($arrayIdCentral == null){
                                        $observacion .= 'NO SE ENCONTRO LA CENTRAL.<br>';
                                    }else{
                                        $idCentral = $arrayIdCentral['idCentral'];
                                        $arrayCentralZonal = $this->m_registro_masivo_planobra_pi->getZonalxCentralPqt($idCentral);
                                        if($arrayCentralZonal == null){
                                            $observacion .= 'NO SE ENCONTRO LA ZONAL.<br>';
                                        }else{
                                            $idZonal = $arrayCentralZonal['idZonal'];
                                            $tipoCentral = $arrayCentralZonal['codigo'].''.$arrayCentralZonal['tipoCentralDesc'];
                                        }
                                    }
                                }
                            }
                    }else {
                        if(strlen($coodX) == 0 || $coodX == null || $coodX == ''){
                            $observacion .= 'DEBE INGRESAR COORDENADAS X.<br>';
                        }
                        if(strlen($coodY) == 0 || $coodY == null || $coodY == ''){
                            $observacion .= 'DEBE INGRESAR COORDENADAS Y.<br>';
                        }

                        if($flgCodigoSitio == 0 && strlen($coodX) != 0 && $coodX != null && $coodX != '' && strlen($coodY) != 0 && $coodY != null && $coodY != ''){
                            $arrayIdCentral  = $this->m_utils->getMdfCoord($coodY, $coodX);
                            if($arrayIdCentral == null){
                                $observacion .= 'NO SE ENCONTRO LA CENTRAL.<br>';
                            }else{
                                $idCentral = $arrayIdCentral['idCentral'];
                                $arrayCentralZonal = $this->m_registro_masivo_planobra_pi->getZonalxCentralPqt($idCentral);
                                if($arrayCentralZonal == null){
                                    $observacion .= 'NO SE ENCONTRO LA ZONAL.<br>';
                                }else{
                                    $idZonal = $arrayCentralZonal['idZonal'];
                                    $tipoCentral = $arrayCentralZonal['codigo'].''.$arrayCentralZonal['tipoCentralDesc'];
                                }
                            }
                        }
                    }
                    if(strlen($requiereHardware) == 0 || $requiereHardware == null || $requiereHardware == '' || !in_array($requiereHardware,array('SI','NO'))){
                        $observacion .= 'DEBE INDICAR "SI" O "NO" REQUIERE HARDWARE.<br>';
                    }else{
                        if($requiereHardware == 'SI'){
                            $flgHardware = '1';
                        }else{
                            $flgHardware = '0';
                        }
                    }
                    if(strlen($indicador) == 0 || $indicador == null || $indicador == ''){
                        $indicador = '0';
                    }

                    if(strlen($idCentral) == 0 || $idCentral == null || $idCentral == ''){
                        $observacion .= 'NO SE ENCONTRO CENTRAL.<br>';
                    }

                    if(strlen($idZonal) == 0 || $idZonal == null || $idZonal == ''){
                        $observacion .= 'NO SE ENCONTRO ZONAL.<br>';
                    }

                    $estadoplan = 1;
                    if($observacion == '' && strlen($observacion) == 0){

                        $html .= '<tr id="tr' . $count . '" >
                                    <th style="color:black">
                                        <div style="width: 10px;">
                                            ' . $count . '
                                        </div>
                                    </th>
                                    <th style="color:black">
                                        <div style="width: 80px;">
                                            ' . $descripcionOpcional . '
                                        </div>
                                    </th>
                                    <th style="color:black">
                                        ' . $proyectoDesc . '
                                    </th>
                                    <th style="color:black">
                                        ' . $subProyectoDesc . '
                                    </th>
                                    <th style="color:black;">
                                        ' . $tipoCapexOpex . '
                                    </th>
                                    <th style="color:black;">' . $faseDesc . '</th>
                                    <th style="color:black;">' . $empresaColabDesc . '</th>
                                    <th style="color:black;">' . $contratoMarco . '</th>
                                    <th style="color:black;">
                                        ' . $vigencia . '
                                    </th>
                                    <th style="color:black;">
                                        ' . $tipoMoneda . '
                                    </th>
                                    <th style="color:black;">
                                        ' . $codigoSitio . '
                                    </th>
                                    <th style="color:black;">' . $coodX . '</th>
                                    <th style="color:black;">' . $coodY . '</th>
                                    <th style="color:black;">' . $nombreEstacion . '</th>
                                    <th style="color:black;">' . $departamento_matriz . '</th>
                                    <th style="color:black;">' . $provincia_matriz . '</th>
                                    <th style="color:black;">' . $distrito_matriz . '</th>
                                    <th style="color:black;">' . $requiereHardware . '</th>
                                    <th style="color:black;">' . $indicador . '</th>            
                                    <th style="color:black;">' . $pep2 . '</th>
                                    <th style="color:black;">' . $ceco . '</th>
                                    <th style="color:black;">' . $cuenta . '</th>
                                    <th style="color:black;">' . $areaFuncional . '</th>
                                    <th style="color:black;">' . $tipoEstudio . '</th>
                                    <th style="color:black;">' . $segmento . '</th>
                                    <th style="color:black;">' . $servicio . '</th>
                                    <th style="color:black;">' . $velocidad . '</th>
                                    <th style="color:#22d622;">
                                        <div style="width: 300px;">
                                            REGISTRO VÁLIDO
                                        </div>
                                    </th>
                                </tr>';

                        $dataInsert = array(
                            // "itemPlan"  => $itemplan,
                            // "usu_reg"   => $idUsuario,
                            // "fecha_reg" => $fechaActual,
                            "nombreProyecto" => $descripcionOpcional,
                            "indicador" => $indicador,
                            "cantidadTroba" => 0,
                            "uip" => 0,
                            // "fechaInicio" =>$fechaActual,
                            "idEstadoPlan" =>intval($estadoplan),
                            "idFase" => $idFase,
                            "idCentralPqt" =>intval($idCentral),
                            "idSubProyecto" =>intval($infoSubProyecto['idSubProyecto']),
                            "idZonal" => intval($idZonal),
                            "idEmpresaColab" =>intval($infoEmpresaColab['idEmpresaColab']),
                            "itemPlanPE"=> null,
                            "hasAdelanto" => 0,
                            //"fecha_creacion" =>$fechaActual,
                            "paquetizado_fg" => 1,
                            "idContrato"     => $idContrato,
                            "pep2"           => $pep2,
                            "ceco"           => $ceco,
                            "cuenta"         => $cuenta,
                            "area_funcional" => $areaFuncional,
                            "flg_opex"       => $infoSubProyecto['flg_opex'],
                            "codigo_unico"   => $codigoSitio,
                            "departamento_matriz" => $departamento_matriz,
                            "provincia_matriz" 	  => $provincia_matriz,
                            "distrito_matriz" 	  => $distrito_matriz,
                            //"usuario_registro"	=>	$idUsuario,  
                            "tipo_estudio"	=>	$tipoEstudio,
                            "segmento"		=>	$segmento,
                            "servicio"		=>	$servicio,
                            "velocidad"		=>	$velocidad,
                            "requiere_hardware" => $flgHardware,
                            "idProyecto" => $idProyecto
                        );
                        $arrayFinal []= $dataInsert;
                        $ctnVal++;
                    }else{
                        $html .= '<tr id="tr' . $count . '"  style="background-color: #ffcece;" >
                                    <th style="color:black">
                                        <div style="width: 10px;">
                                            ' . $count . '
                                        </div>
                                    </th>
                                    <th style="color:black">
                                        <div style="width: 80px;">
                                            ' . $descripcionOpcional . '
                                        </div>
                                    </th>
                                    <th style="color:black">
                                        ' . $proyectoDesc . '
                                    </th>
                                    <th style="color:black">
                                        ' . $subProyectoDesc . '
                                    </th>
                                    <th style="color:black;">
                                        ' . $tipoCapexOpex . '
                                    </th>
                                    <th style="color:black;">' . $faseDesc . '</th>
                                    <th style="color:black;">' . $empresaColabDesc . '</th>
                                    <th style="color:black;">' . $contratoMarco . '</th>
                                    <th style="color:black;">
                                        ' . $vigencia . '
                                    </th>
                                    <th style="color:black;">
                                        ' . $tipoMoneda . '
                                    </th>
                                    <th style="color:black;">
                                        ' . $codigoSitio . '
                                    </th>
                                    <th style="color:black;">' . $coodX . '</th>
                                    <th style="color:black;">' . $coodY . '</th>
                                    <th style="color:black;">' . $nombreEstacion . '</th>
                                    <th style="color:black;">' . $departamento_matriz . '</th>
                                    <th style="color:black;">' . $provincia_matriz . '</th>
                                    <th style="color:black;">' . $distrito_matriz . '</th>
                                    <th style="color:black;">' . $requiereHardware . '</th>
                                    <th style="color:black;">' . $indicador . '</th>
                                    <th style="color:black;">' . $pep2 . '</th>
                                    <th style="color:black;">' . $ceco . '</th>
                                    <th style="color:black;">' . $cuenta . '</th>
                                    <th style="color:black;">' . $areaFuncional . '</th>
                                    <th style="color:black;">' . $tipoEstudio . '</th>
                                    <th style="color:black;">' . $segmento . '</th>
                                    <th style="color:black;">' . $servicio . '</th>
                                    <th style="color:black;">' . $velocidad . '</th>
                                    <th style="color:red;">
                                        <div style="width: 300px;">
                                            ' . $observacion . '
                                        </div>
                                    </th>
                                </tr>';
                    }
                   
                    $count++;
				}
			}

            $html .= '</tbody>
                </table>';

        } else {
            $html .= '</tbody>
                </table>';
        }

        return array($html, $ctnVal, $arrayFinal);
    }

    public function basicHtml($dataTabla = null){
        $html = '<table style="font-size: 10px" id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr role="row">
                            <th style="text-align: center; vertical-align: middle;">#</th>
                            <th style="text-align: center; vertical-align: middle;">NOMBRE</th>
                            <th style="text-align: center; vertical-align: middle;">PROYECTO</th>
                            <th style="text-align: center; vertical-align: middle;">SUBPROYECTO</th>
                            <th style="text-align: center; vertical-align: middle;">TIPO</th>
                            <th style="text-align: center; vertical-align: middle;">FASE</th>
                            <th style="text-align: center; vertical-align: middle;">PROVEEDOR</th>
                            <th style="text-align: center; vertical-align: middle;">CONTRATO MARCO</th>
                            <th style="text-align: center; vertical-align: middle;">VIGENCIA</th>
                            <th style="text-align: center; vertical-align: middle;">TIPO MONEDA</th>
                            <th style="text-align: center; vertical-align: middle;">CÓDIGO SITIO</th>
                            <th style="text-align: center; vertical-align: middle;">COORDENADAS X</th>
                            <th style="text-align: center; vertical-align: middle;">COORDENADAS Y</th>
                            <th style="text-align: center; vertical-align: middle;">NOMBRE ESTACIÓN</th>
                            <th style="text-align: center; vertical-align: middle;">DEPARTAMENTO</th>
                            <th style="text-align: center; vertical-align: middle;">PROVINCIA</th>
                            <th style="text-align: center; vertical-align: middle;">DISTRITO</th>
                            <th style="text-align: center; vertical-align: middle;">REQUIERE HARDWARE?</th>
                            <th style="text-align: center; vertical-align: middle;">SISEGO</th>                            
                            <th style="text-align: center; vertical-align: middle;">DESCRIPCIÓN OPCIONAL</th>
                            <th style="text-align: center; vertical-align: middle;">PEP2</th>
                            <th style="text-align: center; vertical-align: middle;">CECO</th>
                            <th style="text-align: center; vertical-align: middle;">CUENTA</th>
                            <th style="text-align: center; vertical-align: middle;">ÁREA FUNCIONAL</th>
                            <th style="text-align: center; vertical-align: middle;">TIPO DE ESTUDIO</th>
                            <th style="text-align: center; vertical-align: middle;">SEGMENTO</th>
                            <th style="text-align: center; vertical-align: middle;">SERVICIO</th>
                            <th style="text-align: center; vertical-align: middle;">VELOCIDAD</th>
                            <th style="text-align: center; vertical-align: middle;">OBSERVACIÓN</th>
                        </tr>
                    </thead>
					<tbody id="contBodyTable"> ';
		if($dataTabla != null){
			$count = 1;
            $html = '<table style="font-size: 10px" id="data-table" class="table table-bordered">
                        <thead class="thead-default">
                            <tr role="row">
                                <th style="text-align: center; vertical-align: middle;">#</th>
                                <th style="text-align: center; vertical-align: middle;">ITEMPLAN</th>
                                <th style="text-align: center; vertical-align: middle;">MENSAJE</th>
                            </tr>
                        </thead>
                        <tbody id="contBodyTable"> ';
			foreach($dataTabla as $row){
				$html .='<tr>
							<td>'.$count.'</td>
							<td>'.$row['itemplan'].'</td>
							<td>'.'REGISTRO EXITOSO'.'</td>
						</tr>';
				$count++;
			}
		}
		$html .= '                
                    </tbody>
                </table>';

        return $html;
    }
    
    public function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone', 'America/Lima');
        setlocale(LC_TIME, "es_ES", "esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }

	public function cargaMasivaObraPin()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

			$idUsuario = $this->session->userdata('idPersonaSession');
			$arrayDataFile = $this->input->post('arrayDataFile') ? json_decode($this->input->post('arrayDataFile'),true) : null;

            $this->db->trans_begin();

			if (!isset($idUsuario) || $idUsuario == null || $idUsuario == '') {
                throw new Exception('Su sesión ha expirado, ingrese nuevamente!!');
            }
			if(count($_FILES) == 0){
				throw new Exception('Debe seleccionar un archivo para procesar data!!');
			}
			if($arrayDataFile == null || count($arrayDataFile) == 0){
				throw new Exception('No se pudo cargar la iformación a actualizar, refresque la página y vuelva a intentarlo.');
			}

            $nombreArchivo = $_FILES['file']['name'];
            $tipoArchivo = $_FILES['file']['type'];
            $nombreArchivoTemp = $_FILES['file']['tmp_name'];
            $tamano_archivo = $_FILES['file']['size'];

            $arryNombreArchivo = explode(".", $nombreArchivo);

            $arrayTipos = array(
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
				'application/vnd.ms-excel'
			);

            if (!in_array($tipoArchivo, $arrayTipos)) {
                throw new Exception('Sólo puede subir archivos de tipo excel (.xls , .xlsx)!!');
            }

            if (!file_exists("./uploads/reg_masivo_obra_pin")) {
                if (!mkdir("./uploads/reg_masivo_obra_pin")) {
                    throw new Exception('Hubo un error al crear la carpeta reg_masivo_obra_pin!!');
                }
            }

            $rutaFinalArchivo = './uploads/reg_masivo_obra_pin/' . date("Y_m_d_His_").$nombreArchivo;

			if (move_uploaded_file($nombreArchivoTemp, $rutaFinalArchivo)) {

                $itemplanList = array();
                $fechaActual = $this->m_utils->fechaActual();

				foreach ($arrayDataFile as $datos) {


                    $this->m_planobra->deleteLogImportPlanObraSub();

                    $itemplan = $this->m_planobra->generarCodigoItemPlan($datos['idProyecto'],$datos['idZonal']);
                    if(strlen($itemplan) == 0 || $itemplan == null || $itemplan == ''){
                        throw new Exception('Hubo un error al traer el código de itemplan.');
                    }

                    $arrayInsert = array(  
                        "itemPlan"  => $itemplan,
                        "usu_reg"   => $idUsuario,
                        "fecha_reg" => $fechaActual,
                        "nombreProyecto" => $datos['nombreProyecto'],
                        "indicador" => $datos['indicador'],
                        "cantidadTroba" => $datos['cantidadTroba'],
                        "uip" => $datos['uip'],
                        "fechaInicio" => $fechaActual,
                        "idEstadoPlan" => $datos['idEstadoPlan'],
                        "idFase" => $datos['idFase'],
                        "idCentralPqt" => $datos['idCentralPqt'],
                        "idSubProyecto" => $datos['idSubProyecto'],
                        "idZonal" => $datos['idZonal'],
                        "idEmpresaColab" => $datos['idEmpresaColab'],
                        "itemPlanPE" => $datos['itemPlanPE'],
                        "hasAdelanto" => $datos['hasAdelanto'],
                        "fecha_creacion" => $fechaActual,
                        "paquetizado_fg" => $datos['paquetizado_fg'],
                        "idContrato"     => $datos['idContrato'],
                        "pep2"           => $datos['pep2'],
                        "ceco"           => $datos['ceco'],
                        "cuenta"         => $datos['cuenta'],
                        "area_funcional" => $datos['area_funcional'],
                        "flg_opex"       => $datos['flg_opex'],
                        "codigo_unico"   => $datos['codigo_unico'],
                        "departamento_matriz" => $datos['departamento_matriz'],
                        "provincia_matriz" => $datos['provincia_matriz'],
                        "distrito_matriz" => $datos['distrito_matriz'],
                        "usuario_registro" => $idUsuario,  
                        "tipo_estudio" => $datos['tipo_estudio'],
                        "segmento"	=> $datos['segmento'],
                        "servicio"	=> $datos['servicio'],
                        "velocidad"	=> $datos['velocidad'],
                        "requiere_hardware" => $datos['requiere_hardware']
                    );

                    $data = $this->m_registro_masivo_planobra_pi->insertarPlanobraPI($arrayInsert);
                    if($data['error'] == EXIT_ERROR){
                        throw new Exception($data['msj']);
                    }else{
                        $itemplanData = $this->m_planobra->obtenerUltimoRegistro();
                        $dataIpTemp = array(
                            "itemplan" => $itemplanData,
                            "tabla" => 'planobra',
                            "actividad" => 'ingresar',
                            "fecha_registro" => $fechaActual,
                            "id_usuario" => $idUsuario,
                            "tipoPlanta" => ID_TIPO_PLANTA_INTERNA
                        );
                        $data = $this->m_registro_masivo_planobra_pi->insertarLogPlanobraPI($dataIpTemp);
                        if($data['error'] == EXIT_ERROR){
                            throw new Exception($data['msj']);
                        }else{
                            $itemplanList []= $dataIpTemp;
                        }
                        
                    }
				}
                _log(print_r($data,true));
                if($data['error'] == EXIT_SUCCESS && count($itemplanList) > 0){
                    $this->db->trans_commit();
                    $data['tbObservacion'] = $this->basicHtml($itemplanList);
                    $data['msj'] = 'REGISTRO MASIVO DE ITEMPLAN EXITOSO.';
                }
				

			}else{
				throw new Exception('No se pudo subir el archivo: ' . date("Y_m_d_His_") . $nombreArchivo . ' !!');
			}

        } catch (Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }
	
}
