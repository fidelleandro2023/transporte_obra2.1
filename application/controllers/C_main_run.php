<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_main_run extends CI_Controller {

function __construct(){
		parent::__construct();
		$this->load->library('lib_utils');
		$this->load->library('excel');
		$this->load->helper('url');
		$this->load->model('m_main_run');
	}

		
    public function uploadMaterialesPqtMasivo(){
        $data ['error']= EXIT_ERROR;
        $data['msj'] = null;
        try{
    
            $uploadfile = 'uploads/files_test/mat_pqt.xlsx';//ruta final del file    
            $objPHPExcel = PHPExcel_IOFactory::load($uploadfile);
            $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
            $row_dimension = $objPHPExcel->getActiveSheet()->getHighestRowAndColumn();
            $listaPo = $this->procesarFile($row_dimension, $objPHPExcel);      
            $data = $this->saveMaterialesXEstacion($listaPo);
            
                
            
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
    public function procesarFile($row_dimension, $objPHPExcel){
        
        $arrayListPo = array();
        $arrayMatariales = array();
        $material = array();
        $firstCodPo = null;
        $is_po = false;
        for ($i = 1; $i <= $row_dimension['row']; $i++){//COMIENZA DESDE LA FILA 1
            $AA = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0,$i,true)->getValue();#codigo_po
            $BB = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1,$i,true)->getValue();#codigo_mat
            $CC = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2,$i,true)->getValue();#cantidad 
            if($AA!='' && $BB!='' && $CC !=''){
                if($AA  !=  $firstCodPo){
                    $infoPo = $this->m_main_run->getInfoPoByCodigoPo($AA);
                    if($infoPo!=null){
                        $is_po = true;
                        if($firstCodPo!=null){
                            $arrayMatariales['materiales']  = $material;
                            array_push($arrayListPo, $arrayMatariales);
                        }
                        $firstCodPo = $AA;
                        $arrayMatariales = array();
                        $material = array();
                        $arrayMatariales['codigo_po']   = $AA;   
                        $arrayMatariales['idEstacion'] = $infoPo['idEstacion'];
                        $arrayMatariales['itemplan']    = $infoPo['itemplan'];
                        $mat_cant = array();         
                        $mat_cant['id_material']    =   $BB;
                        $mat_cant['cantidad']       =   $CC;
                        array_push($material, $mat_cant);
                    }else{
                        $is_po = false;
                        log_message('error', 'NO ES PO:'.$AA);
                    }
                }else if($AA == $firstCodPo &&  $firstCodPo !=  null && $is_po){
                        $mat_cant = array();         
                        $mat_cant['id_material']    =   $BB;
                        $mat_cant['cantidad']       =   $CC;
                        array_push($material, $mat_cant);
                }
            }
        }
        
        /**final**/
        if($firstCodPo!=null){
            $arrayMatariales['materiales']  = $material;
            array_push($arrayListPo, $arrayMatariales);
        }
        
        //log_message('error', print_r($arrayListPo, true));
        return $arrayListPo;
    }
    
    public function saveMaterialesXEstacion($listaPo){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $idUsuario = 265;
            $materialesDetalle = array();
            $Listamateriales = array();
            $partidasOutPut = array();
            $listaPoUpdate = array();
            foreach($listaPo as $po_data){
                
                
                $codigo_po  = $po_data['codigo_po'];
                $itemplan   = $po_data['itemplan'];
                $idEstacion = $po_data['idEstacion'];                
                $costoTotalMateriales = 0;
                
                foreach($po_data['materiales'] as $material){
                    $infoMaterial    =   $this->m_main_run->getInfoCodigoMaterial($material['id_material']);
                    if($infoMaterial!=null){
                        $dataCMO = array();
                        $dataCMO['itemplan']                = $itemplan;
                        $dataCMO['idEstacion']              = $idEstacion;
                        $dataCMO['id_material']             = $infoMaterial['id_material'];
                        $dataCMO['costo_inicial_material']  = $infoMaterial['costo_material'];
                        $cantidad = (($material['cantidad'] != null) ? $material['cantidad'] : 0);
                        $dataCMO['cantidad_inicial']        = $cantidad;
                        $monto_final                        = ($cantidad*$infoMaterial['costo_material']);
                        $dataCMO['monto_inicial']           = $monto_final;
                        $dataCMO['costo_final_material']    = $infoMaterial['costo_material'];
                        $dataCMO['cantidad_final']          = $cantidad;
                        $dataCMO['monto_final']             = $monto_final;
                        $dataCMO['usua_registro']           = $idUsuario;
                        $dataCMO['fecha_registro']          = $this->fechaActual();
                        array_push($materialesDetalle, $dataCMO);
                        $costoTotalMateriales = $costoTotalMateriales + $dataCMO['monto_final'];
                    }
                }
                
                $materialesPadre = array(
                    'itemplan'              => $itemplan,
                    'idEstacion'            => $idEstacion,
                    'costo_total_inicial'   => $costoTotalMateriales,
                    'costo_total_final'     => $costoTotalMateriales,
                    'usua_registro'         => $idUsuario,
                    'fecha_registro'        => $this->fechaActual(),
                    'path_evidencia'        => null,
                    'estado'                => 1//validado
                );
                array_push($Listamateriales, $materialesPadre);
                
                $infoCostoMax = $this->m_main_run->getCostoMaxMatAndCostoMByItemplan($itemplan);
                $total_partida_ferreteria = 0;
                if($costoTotalMateriales > $infoCostoMax['monto']){
                    $total_partida_ferreteria = $infoCostoMax['monto'];
                }else{
                    $total_partida_ferreteria = $costoTotalMateriales;
                }
                
                
                $arrayActividades = array();
                $codigoFerreteria = '69901-2';#ponerlo como constante
                $infoPartida =  $this->m_main_run->getInfoPartidaByCodPartida($itemplan, $idEstacion, $codigoFerreteria);
                $precioTmp = 0;
                if($infoPartida != null){
                    $precioTmp = $infoPartida['costo'];
                }
                
                //$ferreteriaInfo = $this->m_pqt_terminado->getGroupFerreteria($itemplan, $idEstacion);
                $cantidadFerr = 0;
                $totalFerr = 0;
                // if($ferreteriaInfo != null){
                $totalFerr = $total_partida_ferreteria;
                $cantidadFerr = ($totalFerr/$precioTmp);
                
                if(!in_array($infoPartida['idActividad'], $arrayActividades)){
                    $dataCMO = array();
                    $dataCMO['codigo_po']        = $codigo_po;
                    $dataCMO['idActividad']      = $infoPartida['idActividad'];
                    $dataCMO['baremo']           = $infoPartida['baremo'];
                    $dataCMO['costo']            = $precioTmp;
                    $dataCMO['cantidad_inicial'] = $cantidadFerr;
                    $dataCMO['monto_inicial']    = $totalFerr;
                    $dataCMO['cantidad_final']   = $cantidadFerr;
                    $dataCMO['monto_final']      = $totalFerr;
                    array_push($arrayActividades, $infoPartida['idActividad']);//metemos idActividad
                    array_push($partidasOutPut, $dataCMO);
                    array_push($listaPoUpdate, $codigo_po);
                }
                
            }            
            $data =  $this->m_main_run->actualizarFerreteriasPqt($Listamateriales, $materialesDetalle, $partidasOutPut, $listaPoUpdate);
            //log_message('error', print_r($partidasOutPut, true));
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
    public function fechaActual()
    {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone', 'America/Lima');
        setlocale(LC_TIME, "es_ES", "esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }
    
}