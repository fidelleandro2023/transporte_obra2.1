<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 *
 *
 */
class C_integracion_dominion extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_servicios/m_integracion_dominion');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index()
    {      
        
    }
    public function MakeFilesDominion()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $folderName = $this->fechaActual(2);   
            $pathFolder = PATH_FILE_UPLOAD_DOMINION.$folderName;
            mkdir($pathFolder);          

            $data   =   $this->makeFileMatDominion($pathFolder);
            if($data['error']    ==  EXIT_SUCCESS){
                $data   =   $this->makeFileMoDominion($pathFolder);
                if($data['error']    ==  EXIT_SUCCESS){
                    $data = $this->makeFileDetallePlanDominion($pathFolder);
                    if($data['error']    ==  EXIT_SUCCESS){
                        $data = $this->makeFilePlanObraDominion($pathFolder);
                        if($data['error']    ==  EXIT_SUCCESS){
                            log_message('error', 'todo OK! reportes dominion');
                        }
                    }
                }
            }   
            $this->sendFilesToFTP($folderName);
            
        }catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }
    
    
    public function makeFileMatDominion($pathFolder)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $detalleplan = $this->m_integracion_dominion->getMatToReporteDomion();
            if(count($detalleplan) > 0) {
            
                $file = fopen($pathFolder.'/'.NAME_REPORT_MAT_DOMINION, "w");
                fputcsv($file, explode('\t',"ITEMPLAN"."\t".
                    "CODIGO PO"."\t".
                    "CODIGO MATERIAL"."\t".
                    "DESCRIPCION MATERIAL"."\t".
                    "CANTIDAD INGRESADA"."\t".
                    "CANTIDAD FINAL"."\t".
                    "AREA"));
                foreach ($detalleplan as $row){
                    fputcsv($file, explode('\t', utf8_decode($row->itemplan."\t".
                            $row->codigo_po."\t".
                            $row->codigo_material."\t".
                            $row->descrip_material."\t".
                            $row->cantidad_ingreso."\t".
                            $row->cantidad_final."\t".
                            $row->area)));
                }            
                fclose($file);               
            }
            $data['error']= EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
    public function makeFileMoDominion($pathFolder)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $arrayMO = $this->m_integracion_dominion->getMoToReporteDomion();
            if(count($arrayMO) > 0) {    
                $file = fopen($pathFolder.'/'.NAME_REPORT_MO_DOMINION, "w");
                fputcsv($file, explode('\t',"ITEMPLAN"."\t".
                                            "ESTADO ITEMPLAN"."\t".
                                            "PO"."\t".
                                            "ESTADO PO"."\t".
                                            "AREA"."\t".
                                            "CODIGO PARTIDA"."\t".
                                            "PARTIDA"."\t".
                                            "TIPO PRECIO"."\t".
                                            "BAREMO"."\t".
                                            "CANTIDAD"."\t".
                                            "COSTO"."\t".
                                            "TOTAL"));
                foreach ($arrayMO as $row){                  
                    fputcsv($file, explode('\t',  utf8_decode($row->itemplan."\t".
                                                                $row->estadoPlanDesc."\t".
                                                                $row->codigo_po."\t".
                                                                $row->po_estado."\t".
                                                                $row->area."\t".
                                                                $row->codigo_partida."\t".
                                                                $row->partidaDesc."\t".
                                                                $row->descPrecio."\t".
                                                                $row->baremo."\t".
                                                                $row->cantidad_final."\t".
                                                                $row->costo."\t".
                                                                $row->monto_final
                                                        )));
    
                }
    
                fclose($file);
            }
            $data['error']= EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
    public function makeFileDetallePlanDominion($pathFolder)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $arrayDP    = $this->m_integracion_dominion->getDetallePlanToDominion();
             if (count($arrayDP) > 0) {
                    $file = fopen($pathFolder.'/'.NAME_REPORT_DET_PLAN_DOMINION, "w");
                    fputcsv($file, explode('\t', "ITEMPLAN" . "\t" . "PO" . "\t" . "AREA" . "\t" . "PROYECTO" . "\t" . "SUBPROYECTO" . "\t" . "FASE". "\t" ."SISEGO TROBA" . "\t" . "FECHA CREACION IP" . "\t" . "FECHA INICIO" . "\t" . "FECHA PREVISTA" . "\t" . "FECHA TERMINO" . "\t" . "FECHA CANCELACION" . "\t" . "FECHA PRE LIQUIDACION" . "\t" . "ESTADO" . "\t" . "TITULO" . "\t" . "JEFATURA" . "\t" . "ZONAL" . "\t" . "MDF" . "\t" . "GRAFO" . "\t" . "EMP. COLABORADORA" . "\t" . "VALORIZ MANO DE OBRA" . "\t" . "VALORIZ MATERIAL" . "\t" . "VR" . "\t" . "FECHA ULT. ESTADO" . "\t" . "FECHA CREACION" . "\t" . "USUARIO" . "\t" . "ESTADO PLAN". "\t" . "DISTRITO"));
                    foreach ($arrayDP as $row) {
                        fputcsv($file, explode('\t', utf8_decode($row->itemPlan . "\t" . $row->poCod . "\t" . $row->areaDesc . "\t" . $row->proyectoDesc . "\t" . $row->subProyectoDesc . "\t" .$row->faseDesc . "\t" .
                        $row->indicador . "\t" . $row->fecha_registro . "\t" . $row->fechaInicio . "\t" . $row->fechaPrevEjec . "\t" . $row->fechaEjecucion . "\t" . $row->fechaCancelacion . "\t" . $row->fechaPreLiquidacion . "\t" .
                        $row->est_innova . "\t" . $row->titulo_trabajo . "\t" . $row->jefatura . "\t" . $row->zonal . "\t" . $row->mdf . "\t" .$row->grafo . "\t" . $row->eecc . "\t" . $row->valoriz_m_o . "\t" . $row->valoriz_material . "\t" . 
                        $row->vr . "\t" . $row->f_ult_est . "\t" . $row->f_creac_prop . "\t" . $row->usu_registro. "\t" . $row->estadoPlanDesc. "\t" . $row->distrito)));
                    }
                fclose($file);
            }                     
            $data['error']= EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
    public function makeFilePlanObraDominion($pathFolder)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $arrayPO    = $this->m_integracion_dominion->getPlanObraToDominion();
            if (count($arrayPO) > 0) {
                $file = fopen($pathFolder.'/'.NAME_REPORT_PLAN_OBRA_DOMINION, "w");
                fputcsv($file, explode('\t', "ITEMPLAN" . "\t" .
                                            "PROYECTO" . "\t" .
                                            "SUBPROYECTO" . "\t" .
                                            "INDICADOR" . "\t" .
                                            "NOMBRE PROYECTO" . "\t" .
                                            "FASE" . "\t" .
                                            "EMPRESA ELECTRICA" . "\t" .
                                            "UIP" . "\t" .
                                            "COORDX" . "\t" .
                                            "COORDY" . "\t" .
                                            "ESTADO PLAN" . "\t" .
                                            "FECHA CREACION IP" . "\t" .
                                            "FECHA INICIO" . "\t" .
                                            "FECHA PREVISTA EJECUCION" . "\t" .
                                            "FECHA ADJUDICACION DISENO" . "\t" .
                                            "FECHA EJECUCION DISENO" . "\t" .
                                            "FECHA TERMINO" . "\t" .
                                            "FECHA DE PRE LIQUIDACION" . "\t" .
                                            "CENTRAL" . "\t" .
                                            "JEFATURA" . "\t" .
                                            "REGION" . "\t" .
                                            "ZONAL" . "\t" .
                                            "EMPRESA COLABORADORA" . "\t" .
                                            "EMPRESA ADJUDICACION" . "\t" .
                                            "ADELANTO" . "\t" .
                                            "MARCA PARALIZACION" . "\t" .
                                            "FECHA PARALIZACION" . "\t" .
                                            "MOTIVO PARALIZACION" . "\t" .
                                            "FECHA DESPARALIZACION" . "\t" .
                                            "SIROPE" . "\t" .
                                            "CODIGO DE TRABAJO"
                        ));
            
                foreach ($arrayPO as $row) {
                    fputcsv($file, explode('\t', utf8_decode($row->itemPlan . "\t" .
                        $row->proyectoDesc . "\t" .
                        $row->subProyectoDesc . "\t" .
                        $row->indicador . "\t" .
                        $row->nombreProyecto . "\t" .
                        $row->faseDesc . "\t" .
                        $row->ec_elec . "\t" .
                        $row->uip . "\t" .
                        $row->coordX . "\t" .
                        $row->coordY . "\t" .
                        $row->estadoPlanDesc . "\t" .
                        $row->fecha_creacion . "\t" .
                        $row->fechaInicio . "\t" .
                        $row->fechaPrevEjec . "\t" .
                        $row->fec_ult_adju_diseno. "\t" .
                        $row->fec_ult_ejec_diseno. "\t" .
                        $row->fechaTermino . "\t" .
                        $row->fechaPreLiquidacion . "\t" .
                        $row->tipoCentralDesc . "\t" .
                        $row->jefatura . "\t" .
                        $row->region . "\t" .
                        $row->zonalDesc . "\t" .
                        $row->empresaColabDesc . "\t" .
                        $row->ec_diseno . "\t" .
                        $row->hasAdelanto . "\t" .
                        $row->flagParalizacion . "\t" .
                        $row->fechaRegistro . "\t" .
                        $row->motivoDesc . "\t" .
                        $row->fechaReactivacion . "\t" .
                        $row->ult_estado_sirope. "\t" .
                        $row->ult_codigo_sirope)));
                }
            
                fclose($file);
            }
            $data['msj']= 'SE COMPLETO LA GENERACION DE ARCHIVOS DOMINION.';
            $data['error']= EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
    
    function fechaActual($type) {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone','America/Lima');
        setlocale(LC_TIME, "es_ES","esp");
        if($type==1){//normal
            $hoy = strftime("%Y-%m-%d %H:%M:%S");
        }else if($type==2){//para carpetas
            $hoy = strftime("%Y%m%d_%H%M%S");
        }        
        return $hoy;
    }
    
    function sendFilesToFTP($nameFolder){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->load->library('ftp');
            $config['hostname'] = 'http://181.65.188.38';
            $config['username'] = 'obrastdp';
            $config['password'] = '1telefonica$';
            $config['debug']        = TRUE;            
            $this->ftp->connect($config);   
            $this->ftp->mkdir('/ObrasTDP/'.$nameFolder);
            $this->ftp->upload(PATH_FILE_UPLOAD_DOMINION.$nameFolder.'/'.NAME_REPORT_MAT_DOMINION, '/ObrasTDP/'.$nameFolder.'/'.NAME_REPORT_MAT_DOMINION);            
            $this->ftp->upload(PATH_FILE_UPLOAD_DOMINION.$nameFolder.'/'.NAME_REPORT_MO_DOMINION, '/ObrasTDP/'.$nameFolder.'/'.NAME_REPORT_MO_DOMINION);
            $this->ftp->upload(PATH_FILE_UPLOAD_DOMINION.$nameFolder.'/'.NAME_REPORT_DET_PLAN_DOMINION, '/ObrasTDP/'.$nameFolder.'/'.NAME_REPORT_DET_PLAN_DOMINION);
            $this->ftp->upload(PATH_FILE_UPLOAD_DOMINION.$nameFolder.'/'.NAME_REPORT_PLAN_OBRA_DOMINION, '/ObrasTDP/'.$nameFolder.'/'.NAME_REPORT_PLAN_OBRA_DOMINION);
            $this->ftp->close();
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
}