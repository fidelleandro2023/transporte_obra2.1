<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_carga_archivo_robot extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_cotizacion/m_carga_archivo_robot');
        $this->load->library('lib_utils');
        $this->load->library('excel');
        $this->load->helper('url');
    }

	public function index() {  	   
        $logedUser = $this->session->userdata('usernameSession');
        $idEcc     = $this->session->userdata('eeccSession');
	    if($logedUser != null){
            $data['nombreUsuario'] =  $this->session->userdata('usernameSession');	
            $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');	               
            $permisos =  $this->session->userdata('permisosArbol');
            #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_MANTENIMIENTO, ID_PERMISO_HIJO_MANTENIMIENTO_KIT_EXT);
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_MANTENIMIENTO_CERTIFICACION, ID_PERMISO_HIJO_MANTENIMIENTO_KIT_EXT, ID_MODULO_MANTENIMIENTO);
            $data['title'] = 'CARGAR ARCHIVO ROBOT V2';
            $data['listaSubProy']  = $this->m_utils->getAllSubProyecto();
            // $data['tablaMaterial'] = $this->tablaMaterial();
            //$data['listCmbItemplan'] = $this->m_utils->getListItemplanxEecc($idEcc);
            $data['opciones'] = $result['html'];
            $this->load->view('vf_cotizacion/v_carga_archivo_robot',$data);
	   }else{
	       redirect('login','refresh');
	   }
    }

    function getCmbEstacion() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idSubProyecto = $this->input->post('idSubProyecto');

            if($idSubProyecto == null) {
                throw new Exception('error No se encontr&oacute; subproyecto, comunicarse con el programador');
            }

            $arrayEstacion = $this->m_utils->getEstacionBySubProyecto($idSubProyecto);
            $cmb = null;
            $cmb.='<option value="">seleccionar estacion</option>';
            foreach($arrayEstacion AS $row) {
                $cmb.='<option value="'.$row['idEstacion'].'">'.utf8_decode($row['estacionDesc']).'</option>';
            }
            $data['error'] = EXIT_SUCCESS;
            $data['cmbEstacion'] = $cmb;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function getKitMateriales() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idSubProyecto = $this->input->post('idSubProyecto');
            $idEstacion    = $this->input->post('idEstacion');

            if($idSubProyecto == null) {
                throw new Exception('error No se encontr&oacute; subproyecto, comunicarse con el programador');
            }

            if($idEstacion == null) {
                throw new Exception('error No se encontr&oacute; idEstacion, comunicarse con el programador');
            }

            $data['tablaKit']      = $this->tablakit($idSubProyecto, $idEstacion);
            //$data['tablaMaterial'] = $this->tablaMaterial($idSubProyecto, $idEstacion);
            $data['error']    = EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function tablaCtoUbic() {
        $arrayData = $this->m_utils->getAllCto();
        $html = '
                <table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>Nro.</th>
                            <th>C&Oacute;DIGO</th>
                            <th>UBCACION CTO</th>    
                            <th>LATITUD</th>                         
                            <th>LONGITUD</th>
                            <th>TECNOLOGIA</th>
                            <th>SEGMENTO</th>   
                            <th>OCUPACION HILOS</th>
                            <th>TOTAL HILOS</th>              
                        </tr>
                    </thead>                    
                    <tbody>';
        $style = null;
        $arrayStyle = array();
        $cont = 0;
        foreach($arrayData as $row){
            $cont++;
            // $checked  = ($row['kitIdMaterial'] == 1) ? 'checked' : null;
            // $disabled = ($row['kitIdMaterial'] == 1) ? 'disabled' : null;
            $html .='   <tr>
                            <td style="background:'.$style.'">'.$cont.'</td>
                            <td style="background:'.$style.'">'.$row['codigo'].'</td>
                            <td style="background:'.$style.'">'.$row['ubicacion_cto'].'</td>
                            <th style="background:'.$style.'">'.$row['latitud'].'</th>
                            <td style="background:'.$style.'">'.$row['longitud'].'</td>	
                            <td style="background:'.$style.'">'.$row['tecnologia'].'</td>
                            <td style="background:'.$style.'">'.$row['segmento'].'</td>   
                            <td style="background:'.$style.'">'.$row['ocupacion_hilos'].'</td>   
                            <td style="background:'.$style.'">'.$row['total_hilos'].'</td>                                                		                        
                        </tr>';
        }
            $html .='</tbody>
            </table>';
            return utf8_decode($html);
    }

    function insertCtoCotizacion() {
        $data['msj'] = '';
        $data['error'] = EXIT_ERROR;
        try {
            ini_set('memory_limit', '1000M');
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

            if(isset($_FILES['file']['name'])) {
                $path   = $_FILES['file']['tmp_name'];
                $object = PHPExcel_IOFactory::load($path);
                foreach($object->getWorksheetIterator() as $worksheet) {
                    $highestRow    = $worksheet->getHighestRow();
                    $highestColumn = $worksheet->getHighestColumn();
                    for($row=2; $row<=$highestRow; $row++) {
						$idTerminal      = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                        $codigo          = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                        $ubicacion_cto   = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                        $longitud        = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                        $latitud         = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                        $tecnologia      = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                        $segmento        = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                        $ocupacion_hilos = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                        $total_hilos     = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                        
                        if($latitud != null && $latitud != '') {
                            $arrayJson[] = array(
							    'id_cto'          => $idTerminal,
                                'codigo'          => $codigo,
                                'ubicacion_cto'   => $ubicacion_cto,
                                'latitud' 		  => $latitud,
                                'longitud'        => $longitud,
                                'tecnologia'      => $tecnologia,
                                'segmento'        => $segmento,
                                'ocupacion_hilos' => $ocupacion_hilos,
                                'total_hilos'     => $total_hilos
                            );
                        }
                    }  
                        $data = $this->m_carga_archivo_robot->insertCtoMasivo($arrayJson);
                }
                // $data['tablaCto'] = $this->tablaCtoUbic();
            } 
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function insertReservasCotizacion() {
        $data['msj'] = '';
        $data['error'] = EXIT_ERROR;
        try {
            ini_set('memory_limit', '1000M');
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

            if(isset($_FILES['file']['name'])) {
                $path   = $_FILES['file']['tmp_name'];
                $object = PHPExcel_IOFactory::load($path);
                foreach($object->getWorksheetIterator() as $worksheet) {
                    $highestRow    = $worksheet->getHighestRow();
                    $highestColumn = $worksheet->getHighestColumn();
                    for($row=2; $row<=$highestRow; $row++) {
                        $id_terminal         = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                        $codigo              = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                        $ubicacion_reserva   = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                        $longitud            = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                        $latitud             = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                        $cable               = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                        $hilos_disponibles   = $worksheet->getCellByColumnAndRow(6, $row)->getValue();

                        $arrayJson[] = array(
                                                'id_terminal'       => $id_terminal,
                                                'codigo'            => $codigo,
                                                'ubicacion_reserva' => $ubicacion_reserva,
                                                'longitud'          => $longitud,
                                                'latitud' 		    => $latitud,
                                                'cable'             => $cable,
                                                'hilos_disponibles' => $hilos_disponibles
                                            );
                        }  
                        $data = $this->m_carga_archivo_robot->insertReservaMasivo($arrayJson);
                }
                // $data['tablaCto'] = $this->tablaCtoUbic();
            } 
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
	
	function insertEbcCotizacion() {_log("ENTRO11");
        $data['msj'] = '';
        $data['error'] = EXIT_ERROR;
        try {
            ini_set('memory_limit', '1000M');
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

            if(isset($_FILES['file']['name'])) {
                $path   = $_FILES['file']['tmp_name'];
                $object = PHPExcel_IOFactory::load($path);
                foreach($object->getWorksheetIterator() as $worksheet) {
                    $highestRow    = $worksheet->getHighestRow();
                    $highestColumn = $worksheet->getHighestColumn();
                    for($row=2; $row<=$highestRow; $row++) {
                        $codigo         = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                        $nombre              = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                        $propietario         = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                        $latitud             = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                        $longitud             = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                        $departamento         = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                        $provincia            = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
						$distrito             = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
						$direccion            = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
						$zona                 = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
						
                        $arrayJson[] = array(
                                                'codigo'       	=> $codigo,
                                                'nom_estacion'  => $nombre,
                                                'propietario' 	=> $propietario,
                                                'longitud'      => $longitud,
                                                'latitud' 		=> $latitud,
                                                'departamento'  => $departamento,
                                                'provincia' 	=> $provincia,
												'distrito'  	=> $distrito,
												'direccion' 	=> $direccion,
												'zona'      	=> $zona
                                            );
                        }  
                        $data = $this->m_carga_archivo_robot->insertEbcEdifMasivo($arrayJson);
                }
                // $data['tablaCto'] = $this->tablaCtoUbic();
            } 
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
	
	function insertCtoCotizacionEdif() {
        $data['msj'] = '';
        $data['error'] = EXIT_ERROR;
        try {
			_log("ENTRO1");
            ini_set('memory_limit', '1000M');
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

            if(isset($_FILES['file']['name'])) {
                $path   = $_FILES['file']['tmp_name'];
                $object = PHPExcel_IOFactory::load($path);
                foreach($object->getWorksheetIterator() as $worksheet) {
                    $highestRow    = $worksheet->getHighestRow();
                    $highestColumn = $worksheet->getHighestColumn();
                    for($row=2; $row<=$highestRow; $row++) {
						$idTerminal      = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                        $ubicacion_cto   = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                        $tipo_t          = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                        $codigo          = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                        $pa_id           = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                        $tipo_pa         = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                        $longitud        = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                        $latitud         = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                        $tecnologia      = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
						$tipo      	     = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
						$ocupacion_hilos = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
						$total_hilos     = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
                        
                        if($latitud != null && $latitud != '') {
                            $arrayJson[] = array(
							    'id_terminal'     => $idTerminal,
                                'ubicacion_cto'   => $ubicacion_cto,
                                'tipo_t'   		  => $tipo_t,
                                'codigo' 		  => $codigo,
                                'pa_id'       	  => $pa_id,
                                'tipo_pa'         => $tipo_pa,
                                'latitud'         => $latitud,
                                'longitud'        => $longitud,
                                'tecnologia'      => $tecnologia,
								'tipo'            => $tipo,
								'ocupacion_hilos' => $ocupacion_hilos,
								'total_hilos'     => $total_hilos
                            );
                        }
                    }
					$data = $this->m_carga_archivo_robot->insertCtoEdifMasivo($arrayJson);
                }
                // $data['tablaCto'] = $this->tablaCtoUbic();
            } 
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