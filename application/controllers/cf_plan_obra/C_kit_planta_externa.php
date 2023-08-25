<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_kit_planta_externa extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_plan_obra/m_kit_planta_externa');
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
			$data['tablaKit']      = $this->tablakit('', '');			
            $permisos =  $this->session->userdata('permisosArbol');
            #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_MANTENIMIENTO, ID_PERMISO_HIJO_MANTENIMIENTO_KIT_EXT);
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_MANTENIMIENTO_CERTIFICACION, ID_PERMISO_HIJO_MANTENIMIENTO_KIT_EXT, ID_MODULO_MANTENIMIENTO);
            $data['title'] = 'REGISTRAR KIT MATERIAL PLANTA EXTERNA';
            $data['listaSubProy']  = $this->m_utils->getAllSubProyecto();
            $data['tablaMaterial'] = $this->tablaMaterial();
            //$data['listCmbItemplan'] = $this->m_utils->getListItemplanxEecc($idEcc);
            $data['opciones'] = $result['html'];
            $this->load->view('vf_plan_obra/v_kit_planta_externa',$data);
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

    function tablakit($idSubProyecto, $idEstacion) {
        $dataMaterial = $this->m_utils->getKitByIdSubProyecto($idSubProyecto, $idEstacion);
        $html = '
                <table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>Nro.</th>
                            <th>C&Oacute;DIGO</th>
                            <th>MATERIAL</th>    
                            <th>FACTOR PORCENTUAL</th>                         
                            <th>CANTIDAD KIT</th>
                            <th>COSTO MATERIAL</th>
                            <th>COSTO KIT</th>
							<th>ESTADO</th>
                            <th>ACCI&Oacute;N</th>              
                        </tr>
                    </thead>                    
                    <tbody>';
        
        $style = null;
        $arrayStyle = array();
        $cont = 0;
        foreach($dataMaterial as $row){
            $cont++;
            // $checked  = ($row['kitIdMaterial'] == 1) ? 'checked' : null;
            // $disabled = ($row['kitIdMaterial'] == 1) ? 'disabled' : null;
            $html .='   <tr>
                            <td style="background:'.$style.'">'.$cont.'</td>
                            <td style="background:'.$style.'">'.$row['id_material'].'</td>
                            <td style="background:'.$style.'">'.$row['descrip_material'].'</td>
                            <th style="background:'.$style.'">'.$row['factor_porcentual'].'</th>
                            <td style="background:'.$style.'">'.$row['cantidad_kit'].'</td>	
                            <td style="background:'.$style.'">'.$row['costo_material'].'</td>
                            <td style="background:'.$style.'">'.$row['totalMaterial'].'</td>
							<td style="background:'.$style.'">'.$row['estado_material'].'</td>														
                            <td><i class="zmdi zmdi-hc-2x zmdi-delete" style="cursor:pointer" data-id_material="'.$row['id_material'].'" onclick="openModalEliminarMat($(this));"></i></td>                                                 		                        
                        </tr>';
        }
            $html .='</tbody>
            </table>';
            return utf8_decode($html);
    }

    function tablaMaterial($idSubProyecto=null, $idEstacion=null) {
        $idSubProyecto = ($idSubProyecto == '') ? null : $idSubProyecto;
        $dataMaterial = $this->m_utils->getAllMaterial($idSubProyecto, $idEstacion);
        $html = '<table id="tbKitMaterial" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>Nro.</th>
                            <th>C&Oacute;DIGO</th>
                            <th>MATERIAL</th>                            
                            <th>COSTO</th>
                            <th>TIPO</th>   
                            <th>ACCI&Oacute;N</th>              
                        </tr>
                    </thead>                    
                    <tbody>';
        
        $style = null;
        $arrayStyle = array();
        $cont = 0;
        foreach($dataMaterial as $row){
            $cont++;
            $html .='   <tr>
                            <td style="background:'.$row['colorSelec'].'">'.$cont.'</td>
                            <td style="background:'.$row['colorSelec'].'">'.$row['id_material'].'</td>
                            <td style="background:'.$row['colorSelec'].'">'.$row['descrip_material'].'</td>
                            <td style="background:'.$row['colorSelec'].'">'.number_format($row['costo_material'], 2, '.', ',').'</td>							
                            <td style="background:'.$row['colorSelec'].'">'.$row['tipo'].'</td>	
                            <td style="background:'.$row['colorSelec'].'"><i data-id_material="'.$row['id_material'].'" style="cursor:pointer" onclick="openModalValorPorcentualCant($(this))" class="zmdi zmdi-hc-2x zmdi-plus-circle-o"></i></td>                                                 		                        
                        </tr>';
        }
            $html .='</tbody>
            </table>';
            return utf8_decode($html);
    }

    function insertMaterial() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idSubProyecto    = $this->input->post('idSubProyecto');
            $idMaterial       = $this->input->post('idMaterial');
            $cantidadKit      = $this->input->post('cantidadKit');
            $factorPorcentual = $this->input->post('factorPorcentual');
            $idEstacion       = $this->input->post('idEstacion');

            if($idSubProyecto == null) {
                throw new Exception('error No se encontr&oacute; subproyecto, comunicarse con el programador');
            }

            if($idMaterial == null) {
                throw new Exception('error No se encontr&oacute; material, comunicarse con el programador');
            }

            if($cantidadKit == null) {
                throw new Exception('error No se encontr&oacute; cantidad kit, comunicarse con el programador');
            }

            if($factorPorcentual == null) {
                throw new Exception('error No se encontr&oacute; factor porcentual, comunicarse con el programador');
            }

            if($idEstacion == null) {
                throw new Exception('error No se encontr&oacute; estacion, comunicarse con el programador');                
            }

            $data = $this->m_kit_planta_externa->insertMaterial($idSubProyecto, $idMaterial, $cantidadKit, $factorPorcentual, $idEstacion);
            $data['tablaKit']      = $this->tablakit($idSubProyecto, $idEstacion);
            //$data['tablaMaterial'] = $this->tablaMaterial($idSubProyecto, $idEstacion);
            $data['error']    = EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function eliminarMaterial() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $idSubProyecto    = $this->input->post('idSubProyecto');
            $idMaterial       = $this->input->post('idMaterial');
            $idEstacion       = $this->input->post('idEstacion');

         
            if($idSubProyecto == null) {
                throw new Exception('error No se encontr&oacute; subproyecto, comunicarse con el programador');
            }

            if($idMaterial == null) {
                throw new Exception('error No se encontr&oacute; material, comunicarse con el programador');
            }

            if($idEstacion == null) {
                throw new Exception('error No se encontr&oacute; estacion, comunicarse con el programador');                
            }

            $data = $this->m_kit_planta_externa->eliminarMaterial($idSubProyecto, $idMaterial, $idEstacion);
            $data['tablaKit']      = $this->tablakit($idSubProyecto, $idEstacion);
           // $data['tablaMaterial'] = $this->tablaMaterial($idSubProyecto, $idEstacion);
            $data['error']    = EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function insertTbkitMaterialMasivo() {
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
            $idSubProyecto = $this->input->post('idSubProyecto');
            $idEstacion    = $this->input->post('idEstacion');

            if($this->session->userdata('idPersonaSession') == null) {
                throw new Exception('La sesi&oacute;n a expirado, recargue la p&aacute;gina');
            }

            if($idSubProyecto == null) {
                throw new Exception('Debe seleccionar subproyecto');
            }

            if($idEstacion == null) {
                throw new Exception('Debe seleccionar estaci&oacute;n');
            }

            _log($_FILES['file']['name']);
            if(isset($_FILES['file']['name'])) {
                $path   = $_FILES['file']['tmp_name'];
                $object = PHPExcel_IOFactory::load($path);
                foreach($object->getWorksheetIterator() as $worksheet) {
                    $highestRow    = $worksheet->getHighestRow();
                    $highestColumn = $worksheet->getHighestColumn();
                    for($row=2; $row<=$highestRow; $row++) {
                        $idMaterial = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                        $cantidad   = $worksheet->getCellByColumnAndRow(1, $row)->getValue();

                        $arrayJson[] = array(
                                                    'id_material'   => $idMaterial,
                                                    'idSubProyecto' => $idSubProyecto,
                                                    'idEstacion'    => $idEstacion,
                                                    'cantidad_kit'  => $cantidad,
                                                    'factor_porcentual' => 80
                                                );
                        }  
                    $data = $this->m_kit_planta_externa->insertMasivoKit($arrayJson);
                }
                $data['tablaKitMat'] = $this->tablakit($idSubProyecto, $idEstacion);
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