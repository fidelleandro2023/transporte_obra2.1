<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_transferencia_siom extends CI_Controller {
    private $_arrayItemplan;
    private $_idUsuario;

	function __construct(){    
        $this->_arrayItemplan = array();
        

        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_tranferencias/m_transferencia_siom');
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
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_TRANFERENCIAS, ID_PERMISO_HIJO_TRANSFERENCIA_SIOM);
            $data['opciones'] = $result['html'];
            
            $this->load->view('vf_tranferencias/v_transferencia_siom',$data);
        	  
    	 }else{
        	 redirect('login','refresh');
	    }     
    }

    function insertDetalleSiom() {
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
                        $codigo_siom             = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                        $tipoOS                  = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                        $especialidadOs          = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                        $alarmaOS                = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                        $eeccOS                  = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                        $usuarioCreadorOS        = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                        $fechaCreacionOS         = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                        $fechaProgramadaOS       = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                        $fechaIngresoSitio       = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                        $fechaSalidaSitio        = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                        $fechaFinalizacionOS     = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
                        $JefeCuadrillaAsignadoOS = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
                        $descripcionOS           = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
                        $tecnicoAsignadoOS       = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
                        $valorTotal              = $worksheet->getCellByColumnAndRow(14, $row)->getValue();
                        $estadoProcesoOS         = $worksheet->getCellByColumnAndRow(15, $row)->getValue();
                        $estadoOS                = $worksheet->getCellByColumnAndRow(16, $row)->getValue();
                        $nombreEmp               = $worksheet->getCellByColumnAndRow(17, $row)->getValue();
                        $direccionEmp            = $worksheet->getCellByColumnAndRow(18, $row)->getValue();
                        $clasificacionEmp        = $worksheet->getCellByColumnAndRow(19, $row)->getValue();
                        $clasificacionProgEmp    = $worksheet->getCellByColumnAndRow(20, $row)->getValue();
                        $duenioTorreEmp          = $worksheet->getCellByColumnAndRow(21, $row)->getValue();
                        $requiereAccesoEmp       = $worksheet->getCellByColumnAndRow(22, $row)->getValue();
                        $permisosDeAccesoEmp     = $worksheet->getCellByColumnAndRow(23, $row)->getValue();
                        $regionEmp               = $worksheet->getCellByColumnAndRow(24, $row)->getValue();
                        $zonaMovistar            = $worksheet->getCellByColumnAndRow(25, $row)->getValue();
                        $zonaContratoCIM         = $worksheet->getCellByColumnAndRow(26, $row)->getValue();
                        $cluster                 = $worksheet->getCellByColumnAndRow(27, $row)->getValue();
                        $responsableEmp          = $worksheet->getCellByColumnAndRow(28, $row)->getValue();
                        $codigoIncidencia        = $worksheet->getCellByColumnAndRow(29, $row)->getValue();

                        if($codigo_siom) {
                            if($cont <= 2) {
                                $sum = 1;
                            } else {
                                $sum = 0;   
                            }
                            
    
                            $fechaCreacionOS     = PHPExcel_Shared_Date::ExcelToPHP($fechaCreacionOS+$sum);
                            $fechaProgramadaOS   = PHPExcel_Shared_Date::ExcelToPHP($fechaProgramadaOS+$sum);
                            $fechaIngresoSitio   = PHPExcel_Shared_Date::ExcelToPHP($fechaIngresoSitio+$sum);
                            $fechaSalidaSitio    = PHPExcel_Shared_Date::ExcelToPHP($fechaSalidaSitio+$sum);
                            $fechaFinalizacionOS = PHPExcel_Shared_Date::ExcelToPHP($fechaFinalizacionOS+$sum);
    
                            $arrayJsonSiom[] = array(
                                                        'codigo_siom'             => $codigo_siom,
                                                        'tipoOS'                  => $tipoOS,  
                                                        'especialidadOs'          => $especialidadOs,
                                                        'alarmaOS'                => $alarmaOS,
                                                        'eeccOS'                  => $eeccOS,
                                                        'usuarioCreadorOS'        => $usuarioCreadorOS,
                                                        'fechaCreacionOS'         => date('Y-m-d',$fechaCreacionOS),
                                                        'fechaProgramadaOS'       => date('Y-m-d',$fechaProgramadaOS),
                                                        'fechaIngresoSitio'       => date('Y-m-d',$fechaIngresoSitio),
                                                        'fechaSalidaSitio'        => date('Y-m-d',$fechaSalidaSitio),
                                                        'fechaFinalizacionOS'     => date('Y-m-d',$fechaFinalizacionOS),
                                                        'JefeCuadrillaAsignadoOS' => $JefeCuadrillaAsignadoOS,
                                                        'descripcionOS'           => $descripcionOS,
                                                        'tecnicoAsignadoOS'       => $tecnicoAsignadoOS,
                                                        'valorTotalUltimoPpto'    => $valorTotal,
                                                        'estadoProcesoOS'         => $estadoProcesoOS,
                                                        'estadoOS'                => $estadoOS,
                                                        'fecha_registro'          => $this->fechaActual(),
                                                        'id_usuario'              => $this->session->userdata('idPersonaSession'),
                                                        'nombreEmp'               => $nombreEmp,
                                                        'direccionEmp'            => $direccionEmp,
                                                        'clasificacionEmp'        => $clasificacionEmp,
                                                        'clasificacionProgEmp'    => $clasificacionProgEmp,
                                                        'duenioTorreEmp'          => $duenioTorreEmp,
                                                        'requiereAccesoEmp'       => $requiereAccesoEmp,
                                                        'permisosDeAccesoEmp'     => $permisosDeAccesoEmp,
                                                        'regionEmp'               => $regionEmp,
                                                        'zonaMovistar'            => $zonaMovistar,
                                                        'zonaContratoCIM'         => $zonaContratoCIM,
                                                        'cluster'                 => $cluster,
                                                        'responsableEmp'          => $responsableEmp,
                                                        'codigoIncidencia'        => $codigoIncidencia
                                                    );
                            }                    
                        }
                        
                    $data = $this->m_transferencia_siom->insertDetalleSiom($arrayJsonSiom);
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