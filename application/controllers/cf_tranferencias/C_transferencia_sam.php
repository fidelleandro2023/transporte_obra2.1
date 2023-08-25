<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_transferencia_sam extends CI_Controller {
    private $_arrayItemplan;
    private $_idUsuario;

	function __construct(){    
        $this->_arrayItemplan = array();
        

        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_tranferencias/m_transferencia_sam');
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
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_TRANFERENCIAS, 184);
            $data['opciones'] = $result['html'];
            
            $this->load->view('vf_tranferencias/v_transferencia_sam',$data);
        	  
    	 }else{
        	 redirect('login','refresh');
	    }     
    }

    function insertTransferenciaSam() {
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
                    $cont=0;
                    for($row=2; $row<=$highestRow; $row++) {
                        $cont++;
                        $CodigoPresupuestal      = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                        $CodigoUnicoPresupuestal = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                        $NombreEstacion          = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                        $CodigoUnico             = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                        $NombrePlanning          = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                        $TecnologiaNueva         = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                        $TecnologiaActual        = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                        $Continuidad             = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                        $ModeloEstacionBase      = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                        $TipoSite                = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                        $Gestor                  = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
                        $Region                  = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
                        $RegionDetalle           = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
                        $LimaProvincia           = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
                        $Departamento            = $worksheet->getCellByColumnAndRow(14, $row)->getValue();
                        $Provincia               = $worksheet->getCellByColumnAndRow(15, $row)->getValue();
                        $Distrito                = $worksheet->getCellByColumnAndRow(16, $row)->getValue();
                        $StatusDetallado         = $worksheet->getCellByColumnAndRow(17, $row)->getValue();
                        $StatusGlobal            = $worksheet->getCellByColumnAndRow(18, $row)->getValue();
                        $EstadoByS               = $worksheet->getCellByColumnAndRow(19, $row)->getValue();
                        $EstadoGabinete          = $worksheet->getCellByColumnAndRow(20, $row)->getValue();
                        $MotivoReemplazo         = $worksheet->getCellByColumnAndRow(21, $row)->getValue();
                        $TipoPredio              = $worksheet->getCellByColumnAndRow(22, $row)->getValue();
                        $Propietario             = $worksheet->getCellByColumnAndRow(23, $row)->getValue();
                        $ProveedorByS            = $worksheet->getCellByColumnAndRow(24, $row)->getValue();
                        $ProveedorOOCC           = $worksheet->getCellByColumnAndRow(25, $row)->getValue();
                        $OTOOCC                  = $worksheet->getCellByColumnAndRow(26, $row)->getValue();
                        $ProveedorRF             = $worksheet->getCellByColumnAndRow(27, $row)->getValue();
                        $OTRF                    = $worksheet->getCellByColumnAndRow(28, $row)->getValue();
                        $Vendor                  = $worksheet->getCellByColumnAndRow(29, $row)->getValue();
                        $MedioTX                 = $worksheet->getCellByColumnAndRow(30, $row)->getValue();
                        $EstatusPext             = $worksheet->getCellByColumnAndRow(31, $row)->getValue();
                        $EstatusTX               = $worksheet->getCellByColumnAndRow(32, $row)->getValue();
                        $Puerto                  = $worksheet->getCellByColumnAndRow(33, $row)->getValue();
                        $IPs                     = $worksheet->getCellByColumnAndRow(34, $row)->getValue();
                        $FechaPext               = $worksheet->getCellByColumnAndRow(35, $row)->getValue();
                        $FechaOOCCByS            = $worksheet->getCellByColumnAndRow(36, $row)->getValue();
                        $FechaEnergiaDef         = $worksheet->getCellByColumnAndRow(37, $row)->getValue();
                        $FechaOOCCDef            = $worksheet->getCellByColumnAndRow(38, $row)->getValue();
                        $FechaGabinete           = $worksheet->getCellByColumnAndRow(39, $row)->getValue();
                        $FechaFinAdecuacion      = $worksheet->getCellByColumnAndRow(40, $row)->getValue();
                        $FechaTX                 = $worksheet->getCellByColumnAndRow(41, $row)->getValue();
                        $FechaPes                = $worksheet->getCellByColumnAndRow(42, $row)->getValue();
                        $FechaBaseLine           = $worksheet->getCellByColumnAndRow(43, $row)->getValue();
                        $LatitudProbable         = $worksheet->getCellByColumnAndRow(44, $row)->getValue();
                        $LongituProbable         = $worksheet->getCellByColumnAndRow(45, $row)->getValue();
                        $LatitudDefinitiva       = $worksheet->getCellByColumnAndRow(46, $row)->getValue();
                        $LongitudDefinitiva      = $worksheet->getCellByColumnAndRow(47, $row)->getValue();
                        $Direccion               = $worksheet->getCellByColumnAndRow(48, $row)->getValue();
                        $Planning                = $worksheet->getCellByColumnAndRow(49, $row)->getValue();
                        $IngresoFibra            = $worksheet->getCellByColumnAndRow(50, $row)->getValue();
                        $itemplan                = $worksheet->getCellByColumnAndRow(51, $row)->getValue();
                        $prioridad               = $worksheet->getCellByColumnAndRow(52, $row)->getValue();
                        $PlanDeObras             = $worksheet->getCellByColumnAndRow(53, $row)->getValue();
                        $PlanControl             = $worksheet->getCellByColumnAndRow(54, $row)->getValue();
                        $EquipoRF                = $worksheet->getCellByColumnAndRow(55, $row)->getValue();
                        $ConfiguracionDetalle    = $worksheet->getCellByColumnAndRow(56, $row)->getValue();
                        $Corporativo             = $worksheet->getCellByColumnAndRow(57, $row)->getValue();
                        $CorporativoControl      = $worksheet->getCellByColumnAndRow(58, $row)->getValue();
                        $BanderaServicio         = $worksheet->getCellByColumnAndRow(59, $row)->getValue();
                        $EstadoGeolocalizacion   = $worksheet->getCellByColumnAndRow(60, $row)->getValue();
                        $ArchivoAlta             = $worksheet->getCellByColumnAndRow(61, $row)->getValue();
                        $EstadoFicha             = $worksheet->getCellByColumnAndRow(62, $row)->getValue();
                        $ModeloAntena            = $worksheet->getCellByColumnAndRow(63, $row)->getValue();
                        $BanderaSitio            = $worksheet->getCellByColumnAndRow(64, $row)->getValue();
                        
                        
                        // if($itemplan == null || $itemplan == '' || $CodigoUnico == '' || $CodigoUnico == null) {
                        //     throw new Exception('no ingres&oacute; itemplan o CodigoUnico');
                        // }


                        $arrayJsonSam[] = array(
                           'cod_presupuestal'        => $CodigoPresupuestal,      
                            'cod_unico_presupuestal' => $CodigoUnicoPresupuestal, 
                            'nom_estacion'           => $NombreEstacion,
                            'cod_unico'              => $CodigoUnico,
                            'nom_planning'           => $NombrePlanning,
                            'tecnologia_nueva'       => $TecnologiaNueva,
                            'tecnologia_actual'      => $TecnologiaActual,
                            'continuidad'            => $Continuidad,
                            'modelo_estacion_base'   => $ModeloEstacionBase,
                            'tipo_site'              => $TipoSite,
                            'gestor'                 => $Gestor,         
                            'region'                 => $Region,   
                            'region_detalle'         => $RegionDetalle,      
                            'lima_provincia'         => $LimaProvincia,
                            'departamento'           => $Departamento,        
                            'provincia'              => $Provincia, 
                            'distrito'               => $Distrito,         
                            'status_detallado'       => $StatusDetallado,
                            'status_global'          => $StatusGlobal,    
                            'estado_bys'             => $EstadoByS,               
                            'estado_gabinete'        => $EstadoGabinete,
                            'motivo_reemplazo'       => $MotivoReemplazo, 
                            'tipo_predio'            => $TipoPredio,         
                            'propietario'            => $Propietario,         
                            'proveedor_bys'          => $ProveedorByS,     
                            'proveedor_oocc'         => $ProveedorOOCC,      
                            'otoocc'                 => $OTOOCC,
                            'proveedor_rf'           => $ProveedorRF,
                            'otrf'                   => $OTRF,           
                            'vendor'                 => $Vendor,        
                            'medio_tx'               => $MedioTX,            
                            'estatus_pext'           => $EstatusPext,                
                            'estatus_tx'             => $EstatusTX,               
                            'puerto'                 => $Puerto,
                            'ips'                    => $IPs,
                            'fecha_pext'             => $FechaPext,
                            'fecha_oocc_bys'         => $FechaOOCCByS,   
                            'fecha_energia_def'      => $FechaEnergiaDef,
                            'fecha_oocc_desp'        => $FechaOOCCDef,
                            'fecha_gabinete'         => $FechaGabinete,     
                            'fecha_fin_adecuacion'   => $FechaFinAdecuacion,             
                            'fecha_tx'               => $FechaTX,            
                            'fecha_pes'              => $FechaPes,         
                            'fecha_base_line'        => $FechaBaseLine,
                            'latitud_probable'       => $LatitudProbable,             
                            'longitud_probable'      => $LongituProbable,         
                            'latitud_definitiva'     => $LatitudDefinitiva,       
                            'longitud_definitiva'    => $LongitudDefinitiva,        
                            'direccion'              => $Direccion,         
                            'planning'               => $Planning,
                            'ingreso_fibra'          => $IngresoFibra,
                            'itemplan'               => $itemplan,             
                            'prioridad'              => $prioridad,                  
                            'plan_obras'             => $PlanDeObras,
                            'plan_control'           => $PlanControl,
                            'equipo_rf'              => $EquipoRF,
                            'config_detalle'         => $ConfiguracionDetalle, 
                            'corporativo'            => $Corporativo,
                            'corporativo_control'    => $CorporativoControl,
                            'bandera_servicio'       => $BanderaServicio,              
                            'estado_geolocalizacion' => $EstadoGeolocalizacion,  
                            'archivo_alta'           => $ArchivoAlta,      
                            'estado_ficha'           => $EstadoFicha,     
                            'modelo_antena'          => $ModeloAntena,
                            'bandera_sitio'          => $BanderaSitio
                            );
                        }  
                    $data = $this->m_transferencia_sam->insertDetalleSam($arrayJsonSam);
                }
            } 
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data, JSON_NUMERIC_CHECK);
    }

    function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone','America/Lima');
        setlocale(LC_TIME, "es_ES","esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }
}