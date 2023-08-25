<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_carga_masiva_itemplan extends CI_Controller {
    private $_arrayItemplan;
    private $_idUsuario;

	function __construct(){    
        $this->_arrayItemplan = array();
        

        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_plan_obra/m_carga_masiva_itemplan');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->library('excel');
        $this->load->helper('url');
    }
    
	public function index(){
        $this->_idUsuario     = $this->session->userdata('idPersonaSession');
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
            $data['limitGroup'] = $this->m_utils->quitarLimiteGroupConcat();    	       
            $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
            $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
            $permisos =  $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_PLAN_DE_OBRA, ID_PERMISO_HIJO_GENERAR_PO_MASIVO);
            $data['opciones'] = $result['html'];
            
            $this->load->view('vf_plan_obra/v_carga_masiva_itemplan',$data);
        	  
    	 }else{
        	 redirect('login','refresh');
	    }     
    }

    function insertTbTemporal() {
        $data['msj'] = '';
        $data['error'] = EXIT_ERROR;
        try {
            $arrayEstacionItemplan = array();
            $arrayPlanPO           = array();
            $arrayDetallePlan      = array();
            $arrayError            = array();
            $arraySuccess          = array();
            $arrayDetallePO        = array();
            $arrayItemplanTabs     = array();

            $cont = 0;
            $idEstacion = null;
            $flgValida = 0;

            if($this->session->userdata('idPersonaSession') == null) {
                throw new Exception('La sesi&oacute;n a expirado, recargue la p&aacute;gina');
            }

            if(isset($_FILES["file"]["name"])) {
                $path   = $_FILES["file"]["tmp_name"];
                $object = PHPExcel_IOFactory::load($path);
                foreach($object->getWorksheetIterator() as $worksheet) {
                    $highestRow    = $worksheet->getHighestRow();
                    $highestColumn = $worksheet->getHighestColumn();
                    for($row=2; $row<=$highestRow; $row++) {
                        $itemplan     = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                        $descEstacion = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                        $codigo       = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                        $cantidad     = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                        
                        if($itemplan != null && $descEstacion != null && $codigo != null) {
                            $idEstacion = $this->m_utils->getIdEstacion($descEstacion);
                        }
                        
                        if($idEstacion == null || $idEstacion == '') {
                            throw new Exception('alg&uacute;n registro no tiene estaci&oacute;n o est&aacute; mal escrito');
                        }
                        $costo_mat = $this->m_carga_masiva_itemplan->getCostoMat($codigo);
                        // $existeItemplan = $this->m_utils->countItmeplan($itemplan);
                        // $existeMaterial = $this->m_utils->countMaterial($codigo);
                        $countCodPO     = $this->m_utils->countPOByItemplanAndEstacion($itemplan, $idEstacion, FROM_DISENIO);

                        $colorError   = '#FDBDBD';

                        $arrayJsonTmp[] = array(
                                                    'itemplan'         => $itemplan,
                                                    'idEstacion'       => $idEstacion,
                                                    'codigo_material'  => $codigo,
                                                    'cantidad_ingreso' => $cantidad,
                                                    'costo_mat'        => $costo_mat,
                                                    'flg_ingreso'      => 0,
                                                    'idUsuario'        => $this->session->userdata('idPersonaSession'),
                                                    'fechaRegistro'    => $this->fechaActual()
                                                );
                        }  
                    $this->m_carga_masiva_itemplan->insertTmpPO($arrayJsonTmp);
                }
                list($tabsTablasItemplan, $data) = $this->getSoloTabs();
                $data['tablaTabsTmp'] = $tabsTablasItemplan;
            } 
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data, JSON_NUMERIC_CHECK);
    }

    function getSoloTabs() {
        $data['error'] = EXIT_SUCCESS; 
        $tabsTablasItemplan = null;
        $idUsuario = $this->session->userdata('idPersonaSession');
        $dataTablaTmp = $this->m_carga_masiva_itemplan->getDataInsertPO(null, $idUsuario, null);
        
        if(count($dataTablaTmp) == 0) {
            $data['error'] = EXIT_ERROR; 
            $data['msj']   = 'no subio el archivo, Fijarse en el estado del itemplan(Solo ingresan Dise&ntilde;o)';
        }

        if(count($dataTablaTmp) > 0) {
            $btnMasivo = '<div class="form-group">
            <button class="btn btn-success" onclick="openModalAlertaMasivo();">Generar PO MASIVO</button>
        </div>';
            $tabsTablasItemplan .= '<div class="tab-container">
                                        <ul class="nav nav-tabs nav-fill" role="tablist">';
            $cont=0;                            
            foreach($dataTablaTmp as $rowTabs) {
                $cont++;
                if($cont<=10) {
                $tabsTablasItemplan .=  '<li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" data-itemplan="'.$rowTabs->itemplan.'" data-id_estacion="'.$rowTabs->idEstacion.'" data-estacion_desc="'.$rowTabs->estacionDesc.'" onclick="getTablaItemplan($(this));" role="tab">'.$rowTabs->itemplan.'</a>
                                        </li>';
                }                    
            }
            $tabsTablasItemplan .='     </ul>
                                    </div>'; 
            $tabsTablasItemplan .=  '<div class="tab-content">';
        }

        return array($tabsTablasItemplan, $data); 
    }

    function getTablaTabs() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $itemplan     = $this->input->post('itemplan');
            $idEstacion   = $this->input->post('idEstacion');
            $estacionDesc = $this->input->post('estacionDesc');
            
            if($itemplan == null) {
                throw new Exception('error Comunicarse con el programador');
            }
            if($idEstacion == null) {
                throw new Exception('error Comunicarse con el programador');
            }
            if($estacionDesc == null) {
                throw new Exception('error Comunicarse con el programador');
            }
            $data['error'] = EXIT_SUCCESS;
            $data['tablasTabs'] = $this->tablaTabs($itemplan, $idEstacion, $estacionDesc);
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function tablaTabs($itemplan, $idEstacion, $estacionDesc) {
        $idUsuario = $this->session->userdata('idPersonaSession');

        $dataTablaTmp = $this->m_carga_masiva_itemplan->getTablaTmpPO($itemplan, $idUsuario, $idEstacion);
        $total        = $this->m_carga_masiva_itemplan->totalTmp($itemplan, $idEstacion);
        
        $cont  = 0;
        $tabsTablasItemplan = null;
        if(count($dataTablaTmp) > 0)  {
            $tabsTablasItemplan .=  '<div class="col-md-1">
                                        <button class="btn btn-success" onclick="openModalAlerta($(this));">GENERAR PO</button>
                                     </div>
                                     <h2>ESTACI&Oacute;N : '.$estacionDesc.'</h2>
                                     <div>                                    
                                        TOTAL : '.$total['totalFormat'].'
                                     </div>
                                     <div>
                                        <table id="data-table" class="table table-striped">
                                            <thead>
                                                <th class="text-center">Nro.</th>
                                                <th>Itemplan</th>
                                                <th class="text-center">Estaci&oacute;n</th>
                                                <th class="text-center">C&oacute;digo</th>
                                                <th class="text-center">Material</th>
                                                <th class="text-center">Cantidad Ingresada</th>
                                                <th class="text-center">Precio</th>
                                                <th class="text-center">Costo</th>
                                                <th class="text-center">Motivo</th>
                                            <thead>
                                            <tbody>';
        
            foreach($dataTablaTmp as $rowTabs) {
                $countCodPO = $this->m_utils->countPOByItemplanAndEstacion($rowTabs->itemplan, $rowTabs->idEstacion, FROM_DISENIO);
                $motivo          = null;
                $colorBackground = null;

                 if($rowTabs->flg_tipo == FLG_MATERIAL_NO_BUCLE && ($rowTabs->kitIdMaterial == '' ||  $rowTabs->kitIdMaterial == null)) {
                    $motivo = 'El material no pertenece al kit del subproyecto '.$rowTabs->subproyectoDesc;
                    $colorBackground = '#FDBDBD';
                } else if($rowTabs->codigo_po != null || $rowTabs->codigo_po != '') {
                    $motivo = 'Este itemplan ya tiene una PO de dise&ntilde;o en esta estaci&oacute;n';
                    $colorBackground = '#FDBDBD';
                } else if($rowTabs->color == '#00b300') {
                    $motivo = 'La cantidad ingresada es menor al rango del kit (Alerta!)';
                } else if($rowTabs->color == '#ff6600') {
                    $motivo = 'La cantidad ingresada es mayor al rango del kit (Alerta!)';
                }
                //$motivo = ($rowTabs->color == '#FDBDBD') ? 'El material no pertenece al kit del subproyecto '.$rowTabs->subproyectoDesc : null;
                $cont++;
                $tabsTablasItemplan .= ' 	
                                                    <tr style="background:'.$colorBackground.'">
                                                        <td>'.$cont.'</td>
                                                        <td>'.$rowTabs->itemplan.'</td>
                                                        <td>'.$rowTabs->estacionDesc.'</td>
                                                        <td>'.$rowTabs->codigo_material.'</td>
                                                        <td>'.$rowTabs->descrip_material.'</td>
                                                        <td style="color:'.$rowTabs->color.'">'.$rowTabs->cantidad_ingreso.'</td>
                                                        <td>'.$rowTabs->costo_material.'</td>
                                                        <td>'.$rowTabs->costo.'</td>
                                                        <td>'.$motivo.'</td>
                                                    </tr>
                                        ';
                
            }    
            $tabsTablasItemplan .=  '       </tbody>
                                        </table>
                                    </div>';
        }
        return $tabsTablasItemplan; 
    }

    function insertPODetallePlan() {
        $idUsuario = $this->session->userdata('idPersonaSession');

        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }
            
            $itemplan     = $this->input->post('itemplan');
            $idEstacion   = $this->input->post('idEstacion');
            $estacionDesc = $this->input->post('estacionDesc');

            if($itemplan == null) {
                throw new Exception('error Comunicarse con el programador');
            }
            
            if($idEstacion == null) {
                throw new Exception('error Comunicarse con el programador');
            }

            if($estacionDesc == null) {
                throw new Exception('error Comunicarse con el programador');
            }
            $estado     = $this->m_carga_masiva_itemplan->aprobEstadoAuto($itemplan);
            $costoTotal = $this->m_carga_masiva_itemplan->totalTmp($itemplan, $idEstacion);
            
            if($estado == null OR $estado == '') {
                throw new Exception('error Estado Comunicarse con el programador');
            }
            
            if($costoTotal == null OR $costoTotal == '') {
                throw new Exception('error Costo Total Comunicarse con el programador');
            }
            $arrayPlanPO      = array();
            $arrayDetallePlan = array();
            $arrayDetallePO   = array();
            $arrayLogPO       = array();
            $arrayTmpUpdateFlgIngreso = array();
            $arrayData = $this->m_carga_masiva_itemplan->getDataInsertPO($itemplan, $idUsuario, $idEstacion);
            $cont = 0;
            $flgInsert = 0;
            $po = null;
            foreach($arrayData as $row) {
                if($row->idEmpresaColab == null || $row->idEmpresaColab == 0) {
                    throw new Exception('error, empresacolab Null');
                }
                $data1 = explode(',', $row->arrayMaterial);
                //$countCodPO = $this->m_utils->countPOByItemplanAndEstacion($itemplan, $row->idEstacion, FROM_DISENIO);
                // if($countCodPO > 0) {
                //     throw new Exception('Este itemplan ya tiene una PO en esta estaci&oacute;n');
                // }

                //GENERAR LA PO AL ITEMPLAN Y ESTACION A LOS QUE TIENEN KIT
                if($row->sumNotNull > 0 && ($row->codigo_po == null || $row->codigo_po == '')) {
                    $cont++;
                    $idSubProyectoEstacion = $this->m_utils->getIdSubProyectoEstacionByItemplanAndEstacion($itemplan, $row->idEstacion, 'MAT');
                    
                    if($idSubProyectoEstacion == null || $idSubProyectoEstacion == '') {
                        throw new Exception('error id SubProyecto Estacion null');
                    }

                    $po = $this->m_utils->getCodigoPO($itemplan);
                    
                    if($po == null || $po == '') {
                        throw new Exception('error PO null');
                    }

                    $jsonArrayData['itemplan']      = $itemplan;
                    $jsonArrayData['codigo_po']     = $po; 
                    $jsonArrayData['estado_po']     = $estado; 
                    $jsonArrayData['idEstacion']    = $row->idEstacion;
                    $jsonArrayData['from']          = FROM_DISENIO;
                    $jsonArrayData['idUsuario']     = $idUsuario; 
                    $jsonArrayData['fechaRegistro'] = $this->fechaActual();
                    $jsonArrayData['costo_total']   = $costoTotal['costoTotal'];
                    $jsonArrayData['flg_tipo_area'] = 1;
                    $jsonArrayData['id_eecc_reg']   = $row->idEmpresaColab;
                    array_push($arrayPlanPO, $jsonArrayData);
        
                    $jsonArrayDetallePlan['idSubProyectoEstacion'] = $idSubProyectoEstacion;
                    $jsonArrayDetallePlan['poCod']                 = $po;
                    $jsonArrayDetallePlan['itemPlan']              = $itemplan;
                    array_push($arrayDetallePlan, $jsonArrayDetallePlan);

                    $jsonLog['itemplan']       = $row->itemplan;
                    $jsonLog['codigo_po']      = $po;
                    $jsonLog['idUsuario']      = $idUsuario; 
                    $jsonLog['fecha_registro'] = $this->fechaActual();
                    $jsonLog['idPoestado']     = $estado; 
                    $jsonLog['controlador']    = 'C_CARGA_MASIVA_ITEMPLAN';

                    array_push($arrayLogPO, $jsonLog);
                }

                $arrayValidMaterial = array();
                foreach($data1 as $row1) {
                    $dataMaterial = explode('|', $row1);
                    //SI EXISTE EL ID MATERIAL EN EL KIT O SI ES MATERIAL BUCLE
                    if($dataMaterial[2] == 1 && ($row->codigo_po == null || $row->codigo_po == '') && !in_array($dataMaterial[0], $arrayValidMaterial)) {
                        if($po == null || $po == '') {
                            throw new Exception('debe ingresar materiales no bucles');
                        }
                        $flgInsert = 1;
                        array_push($arrayValidMaterial, $dataMaterial[0]);
                        $arrayDetallePO[] = array(
                                                    'codigo_material'  => $dataMaterial[0],
                                                    'codigo_po'        => $po,
                                                    'cantidad_ingreso' => $dataMaterial[1],
                                                    'cantidad_final'   => $dataMaterial[1],
                                                    'costo_material'   => $dataMaterial[4]
                                                 );

                        $arrayTmpUpdateFlgIngreso[] = array (
                                                                'idTmpPlanObraPo' => $dataMaterial[3],
                                                                'flg_ingreso'     => 1
                                                            );
                    }
                }
                
    
            }

            list($tabsTablasItemplan, $data) = $this->getSoloTabs();
            if($flgInsert == 1) {
                $data = $this->m_carga_masiva_itemplan->insertPO($arrayPlanPO, $arrayDetallePlan, $arrayDetallePO, $arrayTmpUpdateFlgIngreso, $arrayLogPO); 
                if($data['error'] == EXIT_SUCCESS) {
                    $data['msj'] = 'Se gener&oacute; la PO: '.$po;
                } 
            } else {
                $data['error'] = EXIT_ERROR;
                throw new Exception('No hay ningun material apto para registrarse');
            }
            
            $data['tablaTabsTmp'] = $tabsTablasItemplan;
            $data['tablasTabs']   = $this->tablaTabs($itemplan, $idEstacion, $estacionDesc);
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
        
    function generarMasivoPO() {
        try {
            $idUsuario = $this->session->userdata('idPersonaSession');
            $arrayData = $this->m_carga_masiva_itemplan->getDataInsertPO(null, $idUsuario, null);
            $cont = 0;
            $flgInsert = 0;
            $arrayLogPO       = array();
            $arrayPlanPO      = array();
            $arrayDetallePlan = array();
            $arrayDetallePO   = array();
            $arrayTmpUpdateFlgIngreso = array();
            foreach($arrayData as $row) {
                $data1 = explode(',', $row->arrayMaterial);

                if($row->sumNotNull > 0 && ($row->codigo_po == null || $row->codigo_po == '')) {
                    $cont++;
                    $idSubProyectoEstacion = $this->m_utils->getIdSubProyectoEstacionByItemplanAndEstacion($row->itemplan, $row->idEstacion, 'MAT');
                    
                    if($idSubProyectoEstacion == null || $idSubProyectoEstacion == '') {
                        throw new Exception('error id SubProyecto Estacion null');
                    }

                    $po = $this->m_utils->getCodigoPO($itemplan);

                    $jsonArrayData['itemplan']    = $row->itemplan;
                    $jsonArrayData['codigo_po']   = $po; 
                    $jsonArrayData['estado_po']   = ID_ESTADO_PO_REGISTRADO; 
                    $jsonArrayData['idEstacion']  = $row->idEstacion;
                    $jsonArrayData['from']        = FROM_DISENIO;

                    array_push($arrayPlanPO, $jsonArrayData);
        
                    $jsonArrayDetallePlan['idSubProyectoEstacion'] = $idSubProyectoEstacion;
                    $jsonArrayDetallePlan['poCod']                 = $po;
                    $jsonArrayDetallePlan['itemPlan']              = $row->itemplan;
                    array_push($arrayDetallePlan, $jsonArrayDetallePlan);
                    
                    $jsonLog['itemplan']       = $row->itemplan;
                    $jsonLog['codigo_po']      = $po;
                    $jsonLog['idUsuario']      = $idUsuario; 
                    $jsonLog['fecha_registro'] = $this->fechaActual();
                    $jsonLog['idPoestado']     = ID_ESTADO_PO_REGISTRADO; 
                    $jsonLog['controlador']    = 'C_CARGA_MASIVA_ITEMPLAN';

                    array_push($arrayLogPO, $jsonLog);
                }

                $arrayValidMaterial = array();
                foreach($data1 as $row1) {
                    $dataMaterial = explode('|', $row1);
                    //SI EXISTE EL ID MATERIAL EN EL KIT
                    if($dataMaterial[2] == 1 && ($row->codigo_po == null || $row->codigo_po == '') && !in_array($dataMaterial[0], $arrayValidMaterial)) {
                        $flgInsert = 1;
                        array_push($arrayValidMaterial, $dataMaterial[0]);
                        $arrayDetallePO[] = array(
                                                    'codigo_material'  => $dataMaterial[0],
                                                    'codigo_po'        => $po,
                                                    'cantidad_ingreso' => $dataMaterial[1]
                                                 );

                        $arrayTmpUpdateFlgIngreso[] = array (
                                                                'idTmpPlanObraPo' => $dataMaterial[3],
                                                                'flg_ingreso'     => 1
                                                            );
                    }
                }
            }

            list($tabsTablasItemplan, $data) = $this->getSoloTabs();
            if($flgInsert == 1) {
                $data = $this->m_carga_masiva_itemplan->insertPO($arrayPlanPO, $arrayDetallePlan, $arrayDetallePO, $arrayTmpUpdateFlgIngreso, $arrayLogPO);  
            } else {
                $data['error'] = EXIT_ERROR;
                throw new Exception('No hay ningun material apto para registrarse');
            }
            
            $data['tablaPO']      = $this->getTablaPO($arrayPlanPO);
            $data['tablaTabsTmp'] = $tabsTablasItemplan;
            $data['tablasTabs']   = $this->tablaTabs(null, null, null);
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function getTablaPO($arrayPO) {
        $tb = null;
        $cont = 0;
        $tb .= '<table id="data-table" class="table table-striped">
                    <thead style="align-text:cennter">
                        <th class="text-center">Nro.</th>
                        <th class="text-center">Itemplan</th>
                        <th class="text-center">Estacion</th>
                        <th class="text-center">PO</th>
                    <thead>
                    <tbody>';

        if(is_array($arrayPO) || is_object($arrayPO)) {
            foreach($arrayPO as $row) {
                $descEstacion = $this->m_utils->getEstaciondescByIdEstacion($row['idEstacion']);
                $cont++;
                $tb .= '<tr>
                            <td>'.$cont.'</td>
                            <td>'.$row['itemplan'].'</td>
                            <td>'.$descEstacion.'</td>
                            <td>'.$row['codigo_po'].'</td>
                        </tr>';
            }    
        }

        $tb .=      '</tbody>
                </table>';
        return $tb;  
    }







    function getTablaExcelPO($arrayError) {
        $tb = null;
        $cont = 0;
        $tb .= '<table id="data-table" class="table table-striped">
                    <thead style="align-text:cennter">
                        <th class="text-center">Nro.</th>
                        <th class="text-center">Itemplan</th>
                        <th class="text-center">Estaci&oacute;n</th>
                        <th class="text-center">C&oacute;digo</th>
                        <th class="text-center">Cantidad</th>
                        <th class="text-center">Motivo</th>
                    <thead>
                    <tbody>';

        if(is_array($arrayError) || is_object($arrayError)) {
            foreach($arrayError as $row) {
                $cont++;
                $tb .= '<tr style="background:'.$row['color'].'">
                            <td>'.$cont.'</td>
                            <td>'.$row['itemplan'].'</td>
                            <td>'.$row['descEstacion'].'</td>
                            <td>'.$row['codigoMaterial'].'</td>
                            <td>'.$row['cantidad'].'</td>
                            <td>'.$row['motivo'].'</td>
                        </tr>';
            }    
        }

        $tb .=      '</tbody>
                </table>';
        return $tb;  
    }
    
    // function getTabs($arraySuccess) {
    //     $result = array();

    //     foreach($arraySuccess as $row) {
    //         $repeat=false;
    //         for($i=0;$i<count($row);$i++)
    //         {
    //             if($result[$i]['itemplan']==$row['itemplan'])
    //             {
    //                 $repeat=true;
    //                 break;
    //             }
    //     }
    //     if($repeat==false) {
    //         $result[] = array('itemplan' => $row['itemplan']);
    //     }
    // }

    function importExcelPO2() {
        $data['msj'] = '';
        $data['error'] = EXIT_ERROR;
        try {
            $arrayEstacionItemplan = array();
            $arrayPlanPO           = array();
            $arrayDetallePlan      = array();
            $arrayError            = array();
            $arraySuccess          = array();
            $arrayDetallePO        = array();
            
            $arrayItemplanTabs     = array();
            $arrayFlgJsonEstacion  = array();
            $arrayFlgJsonItemplan  = array();
            
            $arrayItemplamPlanDos  = array();
            $arrayEstaciones = array();

            $arrayMaterialData    = array();
            $arrayMaterialEstacion = array();
            // $jsonItemplan = array();
            // $jsonItemplan['itemplan'] = null;
            $cont = 0;
            $flgEstacionIgual = 0;
            $flgItemplanIgual = 0;
            if(isset($_FILES["file"]["name"])) {
                $path   = $_FILES["file"]["tmp_name"];
                $object = PHPExcel_IOFactory::load($path);
                foreach($object->getWorksheetIterator() as $worksheet) {
                    
                    $highestRow    = $worksheet->getHighestRow();
                    $highestColumn = $worksheet->getHighestColumn();
                    for($row=2; $row<=$highestRow; $row++) {
                        $arrayFlgJsonItemplan = array();
                        $arrayFlgJsonEstacion = array();
                        
                        $itemplan     = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                        $descEstacion = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                        $codigo       = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                        $cantidad     = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                        
                        $idEstacion = $this->m_utils->getIdEstacion($descEstacion);

                        $existeItemplan = $this->m_utils->countItmeplan($itemplan);
                        $existeMaterial = $this->m_utils->countMaterial($codigo);
                        $countCodPO     = $this->m_utils->countPOByItemplanAndEstacion($itemplan, $idEstacion, FROM_DISENIO);

                        $colorError   = '#FDBDBD';
                        $arrayEstaciones = array();
                        $keyEstacion = null;
           
                        for($i=0; $i<count($arrayItemplanTabs);$i++){
                            if($arrayItemplanTabs[$i]['itemplan'] == $itemplan) {

                                for($j=0;$j<count($arrayItemplanTabs[$i]['estacion']);$j++) {
                                    array_push($arrayEstaciones, $arrayItemplanTabs[$i]['estacion'][$j]['idEstacion']);

                                    if($arrayItemplanTabs[$i]['estacion'][$j]['idEstacion'] == $idEstacion) {
                                        $keyEstacion = $j;                                        
                                    }
                                    // if(in_array($arrayEstaciones, $idEstacion)) {
                                    //     array_push($arrayFlgJsonEstacion, 1);
                                    // } else{
                                    //     array_push($arrayFlgJsonEstacion, 0);
                                    // }
                                }    
                                // $arrayItemplamPlanDos = array();                                                                    
                            } 
                            // else {
                            //     array_push($arrayFlgJsonItemplan, 0);
                            //     //array_push($arrayItemplamPlanDos, $itemplan);
                            // }
                        }
                        $flgItemplan = 0;
                        if(count($arrayEstaciones) > 0) {
                         
                            for($i=0; $i<count($arrayItemplanTabs);$i++){
                                if($arrayItemplanTabs[$i]['itemplan'] == $itemplan) {
                                    $flgItemplan = 1;
                                    for($j=0;$j<count($arrayItemplanTabs[$i]['estacion']);$j++) {
                                        _log("idEstacionAntes: ".$arrayItemplanTabs[$i]['estacion'][$j]['idEstacion']."  -  IDESTACIONINGRESADA: ".$idEstacion);
                                        if($arrayItemplanTabs[$i]['estacion'][$j]['idEstacion'] != null || $arrayItemplanTabs[$i]['estacion'][$j]['idEstacion'] != '') {
                                            //array_push($arrayEstaciones, $arrayItemplanTabs[$i]['estacion'][$j]['idEstacion']);
                                               
                                            if(in_array($idEstacion, $arrayEstaciones)) {
                                                $arrayMaterialEstacion[] = array(
                                                                                    'codigo_material' => $codigo,
                                                                                    'cantidad'        => $cantidad
                                                                                );
                                                $arrayItemplanTabs[$i]['estacion'][$keyEstacion] = array(
                                                                                                            'idEstacion' => $idEstacion,
                                                                                                            'data'       => $arrayMaterialEstacion
                                                                                                        );
                                                break;                                            
                                            } else {
                                                _log("ENTRO DISTINTO ESTACION");
                                                $arrayMaterialData = array();
                                                $arrayMaterialData[] = array(
                                                                                'codigo_material' => $codigo,
                                                                                'cantidad'        => $cantidad
                                                                            );
                           
                                                $arrayItemplanTabs[$i]['estacion'][] =   array(
                                                                                                'idEstacion' => $idEstacion,
                                                                                                'data'       => $arrayMaterialData  
                                                                                            );                                                   
                                                break;
                                            }
                                        }
                                    }   
                                } else {
                                    $jsonItemplan['estacion'] = array();
                                    $arrayMaterialData[] = array(
                                                                'codigo_material' => $codigo,
                                                                'cantidad'        => $cantidad
                                                                );
            
                                    $jsonItemplan['itemplan'] = $itemplan;
                                    $jsonItemplan['estacion'][] = array(
                                                                        'idEstacion' => $idEstacion,
                                                                        'data'       => $arrayMaterialData  
                                                                        );
                                    array_push($arrayItemplanTabs, $jsonItemplan);
                                }
                            }
                        }
                        

                        // for($i=0; $i<count($arrayItemplanTabs);$i++){
                        //      //SI POR LO MENOS TIENE UN ITEMPLAN EN EL ARRAY QUE SEA IGUAL AL QUE SE INGRESO
                        //     if(in_array(1, $arrayFlgJsonItemplan)) {
          
                        //     } else {
                        //         $flgActivJsonItemplan = 0;
                        //         $jsonItemplan['itemplan']               = $itemplan;
                        //         //SI LA ESTACION NO ES IGUAL AL QUE INGRESO
                        //         if(!in_array(1, $arrayFlgJsonEstacion)) {
                        //             $arrayMaterialData[] = array(
                        //                                             'codigo_material' => $codigo,
                        //                                             'cantidad'        => $cantidad
                        //                                         );
                        //         } else {

                        //         }
                        //         $jsonItemplan['estacion']['data'][$i] =  array('codigo_material' => $codigo,
                        //                                                        'cantidad'        => $cantidad);
                        //         array_push($arrayItemplanTabs, $jsonItemplan);  
                        //     }
                        // }

                        if(count($arrayItemplanTabs) == 0 || $flgItemplan == 0) {
                            $arrayMaterialData = array();
                            $arrayMaterialData[] = array(
                                                            'codigo_material' => $codigo,
                                                            'cantidad'        => $cantidad
                                                        );

                            $jsonItemplan['itemplan'] = $itemplan;
                            $jsonItemplan['estacion'][] = array(
                                                                'idEstacion' => $idEstacion,
                                                                'data'       => $arrayMaterialData  
                                                                );
                            array_push($arrayItemplanTabs, $jsonItemplan);
                            $jsonItemplan = array();
                        }


                        
                    }
                }
            }
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data, JSON_NUMERIC_CHECK);
    }

    function importExcelPO() {
        $data['msj'] = '';
        $data['error'] = EXIT_ERROR;
        try {
            $arrayEstacionItemplan = array();
            $arrayPlanPO           = array();
            $arrayDetallePlan      = array();
            $arrayError            = array();
            $arraySuccess          = array();
            $arrayDetallePO        = array();
            $arrayItemplanTabs     = array();

            $cont = 0;
            $flgValida = 0;
            if(isset($_FILES["file"]["name"])) {
                $path   = $_FILES["file"]["tmp_name"];
                $object = PHPExcel_IOFactory::load($path);
                foreach($object->getWorksheetIterator() as $worksheet) {
                    $highestRow    = $worksheet->getHighestRow();
                    $highestColumn = $worksheet->getHighestColumn();
                    for($row=2; $row<=$highestRow; $row++) {
                        $itemplan     = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                        $descEstacion = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                        $codigo       = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                        $cantidad     = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                        
                        $idEstacion = $this->m_utils->getIdEstacion($descEstacion);

                        $existeItemplan = $this->m_utils->countItmeplan($itemplan);
                        $existeMaterial = $this->m_utils->countMaterial($codigo);
                        $countCodPO     = $this->m_utils->countPOByItemplanAndEstacion($itemplan, $idEstacion, FROM_DISENIO);

                        $colorError   = '#FDBDBD';
                        // throw new Exception("PRUEBA");
                        //$colorSuccess = '#'

                        $arrayJsonTmp[] = array(
                                                    'itemplan'         => $itemplan,
                                                    'idEstacion'       => $idEstacion,
                                                    'codigo_material'  => $codigo,
                                                    'cantidad_ingreso' => $cantidad
                                                );


                        if($countCodPO > 0) {
                            $motivo = 'Solo puede tener m&aacute;s de 1 PO por estaci&oacute;n';
                            $flgValida = 1;
                            $arrayError = $this->pushErrorExcel($itemplan, $descEstacion, $codigo, $cantidad, $motivo, $arrayError, $colorError);                            
                        }
                        
                        else if($existeItemplan == 0) {
                            $motivo = 'Itemplan mal escrito';
                            $flgValida = 1;
                            $arrayError = $this->pushErrorExcel($itemplan, $descEstacion, $codigo, $cantidad, $motivo, $arrayError, $colorError);                            
                        }

                        else if($existeMaterial == 0 || $codigo == null) {
                            $motivo = 'No existe material &oacute; No tiene c&oacute;digo de material';
                            $flgValida = 1;
                            $arrayError = $this->pushErrorExcel($itemplan, $descEstacion, $codigo, $cantidad, $motivo, $arrayError, $colorError);
                        }

                        else if($idEstacion == ID_ESTACION_DISENIO) {
                            $flgValida = 1;
                            $motivo = 'Solo se permite material';

                            $arrayError = $this->pushErrorExcel($itemplan, $descEstacion, $codigo, $cantidad, $motivo, $arrayError, $colorError);
                            $data['tablaExcel'] = $this->getTablaExcelPO($itemplan, $descEstacion, $codigo, $cantidad, $motivo);
                            throw new Exception('error Solo se permite materiales');
                        }

                        else if($idEstacion == null) {
                            $flgValida = 1;
                            $motivo = 'Estaci&oacute;n mal escrita';
                            //$data['tablaExcel'] = $this->getTablaExcelPO($itemplan, $descEstacion, $codigo, $cantidad, $motivo);
                            $arrayError = $this->pushErrorExcel($itemplan, $descEstacion, $codigo, $cantidad, $motivo, $arrayError, $colorError);                            
                            $data['tablaExcel'] = $this->getTablaExcelPO($arrayError);
                            throw new Exception('error estacion vac&iacute;o o mal escrita');
                        }

                        else if($itemplan == null) {
                            $flgValida = 1;
                            $motivo = 'No tiene itemplan';
                            $arrayError = $this->pushErrorExcel($itemplan, $descEstacion, $codigo, $cantidad, $motivo, $arrayError, $colorError);                            
                            $data['tablaExcel'] = $this->getTablaExcelPO($arrayError);
                            throw new Exception('error itemplan vac&iacute;o');
                        }

                        else if($descEstacion == null) {
                            $motivo = 'No tiene estacion';
                            $flgValida = 1;
                            $arrayError = $this->pushErrorExcel($itemplan, $descEstacion, $codigo, $cantidad, $motivo, $arrayError, $colorError);                           
                        }

                        else if($cantidad == null) {
                            $motivo = 'No tiene cantidad';
                            
                            $flgValida = 1;
                            $arrayError = $this->pushErrorExcel($itemplan, $descEstacion, $codigo, $cantidad, $motivo, $arrayError, $colorError);
                        } 
                        
                        else {
                            $motivo = 'Correcto';
                            $arraySuccess = $this->pushErrorExcel($itemplan, $descEstacion, $codigo, $cantidad, $motivo, $arraySuccess, null);
                        
                            $idSubProyectoEstacion = $this->m_utils->getIdSubProyectoEstacionByItemplanAndEstacion($itemplan, $idEstacion, 'MAT');

                            // if($idSubProyectoEstacion == null) {
                            //     throw new Exception('null idSubProyectoEstacion');
                            // }
                            
    
                            //SE ASIGNA LA PO POR ESTACION E ITEMPLAN
                            $flgPO = 0;
                            $arrayFlgPO = array();
                            for($i=0; $i<count($arrayEstacionItemplan); $i++) {
                                if($arrayEstacionItemplan[$i]['idEstacion'] == $idEstacion && $arrayEstacionItemplan[$i]['itemplan'] == $itemplan) {
                                    $flgPO = 1;
                                } else {
                                    $flgPO = 0;
                                }
                                array_push($arrayFlgPO, $flgPO);
                            }
    
                            //SI NO TIENE UNA PO EL ITEMPLAN + ESTACION
                            if(!in_array(1, $arrayFlgPO)) {
                                $cont++;
                                $po = $this->m_utils->getPO($itemplan, $cont);
                                
                                $jsonArrayData['itemplan']   = $itemplan;
                                $jsonArrayData['codigo_po']  = $po; 
                                $jsonArrayData['estado_po']  = ID_ESTADO_PO_REGISTRADO; 
                                $jsonArrayData['idEstacion'] = $idEstacion;
                                $jsonArrayData['from']       = FROM_DISENIO;
                                array_push($arrayPlanPO, $jsonArrayData);
        
                                $jsonArrayDetallePlan['idSubProyectoEstacion'] = $idSubProyectoEstacion;
                                $jsonArrayDetallePlan['poCod']                 = $po;
                                $jsonArrayDetallePlan['itemPlan']              = $itemplan;
                                array_push($arrayDetallePlan, $jsonArrayDetallePlan);
    
    
                                $jsonArrayEstacionItemplan['itemplan']   = $itemplan;
                                $jsonArrayEstacionItemplan['idEstacion'] = $idEstacion;
                                array_push($arrayEstacionItemplan, $jsonArrayEstacionItemplan);
                            }
                            
                            //SI ES LA PRIMERA VEZ QUE INGRESA
                            if(count($arrayEstacionItemplan) == 0) {
                                $po = $this->m_utils->getPO($itemplan, $cont);
                                    
                                $jsonArrayData['itemplan']   = $itemplan;
                                $jsonArrayData['codigo_po']  = $po; 
                                $jsonArrayData['estado_po']  = ID_ESTADO_PO_REGISTRADO; 
                                $jsonArrayData['idEstacion'] = $idEstacion;
                                $jsonArrayData['from']       = FROM_DISENIO;
                                array_push($arrayPlanPO, $jsonArrayData);
        
                                $jsonArrayDetallePlan['idSubProyectoEstacion'] = $idSubProyectoEstacion;
                                $jsonArrayDetallePlan['poCod']                 = $po;
                                $jsonArrayDetallePlan['itemPlan']              = $itemplan;
                                array_push($arrayDetallePlan, $jsonArrayDetallePlan);
                                
                                //ARRAY PARA COMPARAR LUEGO LOS ITEMPLAN Y ESTACION QUE YA PASARON
                                $jsonArrayEstacionItemplan['itemplan']   = $itemplan;
                                $jsonArrayEstacionItemplan['idEstacion'] = $idEstacion;
    
                                array_push($arrayEstacionItemplan, $jsonArrayEstacionItemplan);
                            }
        
                            //RECORRO EL ARRAY DE LA ESTACION CON SU RESPECTIVA PO Y LO ASOCIO CON EL MATERIAL.
                            for($i=0; $i<count($arrayPlanPO); $i++) {
                                if($arrayPlanPO[$i]['idEstacion'] == $idEstacion && $arrayPlanPO[$i]['itemplan'] == $itemplan) {
                                    $arrayDetallePO[] = array(
                                                                'codigo_material'  => $codigo,
                                                                'codigo_po'        => $arrayPlanPO[$i]['codigo_po'],
                                                                'cantidad_ingreso' => $cantidad
                                                            );
                                }
                            }
                        }
                    }

                    $this->m_carga_masiva_itemplan->insertTmpPO($arrayJsonTmp);
                    if($flgValida == 0) {
                        //$data = $this->m_carga_masiva_po->insertPO($arrayPlanPO, $arrayDetallePlan, $arrayDetallePO);
                        $data['tablaExcelCorrecto'] = $this->getTablaExcelPO($arraySuccess);
                    } else {
                        $data['tablaExcelCorrecto'] = $this->getTablaExcelPO($arraySuccess);
                        $data['tablaExcelError']    = $this->getTablaExcelPO($arrayError);
                        $data['arrayPlanPO']        = $arrayPlanPO;
                        $data['arrayDetallePlan']   = $arrayDetallePlan;
                        $data['arrayDetallePO']     = $arrayDetallePO;
                    }
                }
            } 
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data, JSON_NUMERIC_CHECK);
    }

    function pushErrorExcel($itemplan, $descEstacion, $codigo, $cantidad, $motivo , $arrayError, $color) {
        $jsonError['itemplan']       = $itemplan;
        $jsonError['descEstacion']   = $descEstacion;
        $jsonError['codigoMaterial'] = $codigo;
        $jsonError['cantidad']       = $cantidad;
        $jsonError['motivo']         = $motivo;
        $jsonError['color']          = $color;

        array_push($arrayError, $jsonError);
        return $arrayError;
    }

    function insertarPO() {
        $data['msj'] = '';
        $data['error'] = EXIT_ERROR;
        try {
            $arrayPlanPO      = $this->input->post('arrayPlanPO');
            $arrayDetallePlan = $this->input->post('arrayDetallePlan');
            $arrayDetallePO   = $this->input->post('arrayDetallePO');
            
            if(!is_array($arrayPlanPO) || !is_array($arrayDetallePlan) || !is_array($arrayDetallePO)) {
                throw new Exception('error Comunicarse con el programador');
            }

            if(count($arrayPlanPO) == 0 || count($arrayDetallePlan) == 0 || count($arrayDetallePO) == 0) {
                throw new Exception('error Comunicarse con el programador');
            }

            $data = $this->m_carga_masiva_itemplan->insertPO($arrayPlanPO, $arrayDetallePlan, $arrayDetallePO);
        } catch(Exception $e) {
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
}