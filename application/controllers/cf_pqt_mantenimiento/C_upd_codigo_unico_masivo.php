<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_upd_codigo_unico_masivo extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=UTF-8');
        $this->load->model('mf_utils/m_utils');
		$this->load->model('mf_crecimiento_vertical/m_crecimiento_vertical');
		$this->load->model('mf_crecimiento_vertical/m_bandeja_reg_cv_negocio');
        $this->load->model('mf_plan_obra/m_planobra');
		$this->load->model('mf_servicios/M_integracion_sirope');
		$this->load->model('mf_pqt_terminado/m_pqt_terminado');
		$this->load->model('mf_certificacion/m_registro_oc_solicitud_masivo');
        $this->sam_db = $this->load->database('another_db', TRUE);
        $this->load->library('lib_utils');
		$this->load->library('excel');
        $this->load->helper('url');
        $this->load->library('zip');
    }

    public function index()
    {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {
            $zonas = $this->session->userdata('zonasSession');
            $idPersonaSession = $this->session->userdata('idPersonaSession');
            $descPerfilSesion = $this->session->userdata('descPerfilSession');
			
            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');

            $data['cmbEmpresaColab'] = __buildCmbEmpresaColab();
			$data['tablaItems'] = $this->getTablaItems(array());

            $permisos = $this->session->userdata('permisosArbol');
			$result = $this->lib_utils->getHTMLPermisos($permisos, 54, 333, ID_MODULO_PAQUETIZADO);
            $data['opciones'] = utf8_encode($result['html']);
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_pqt_mantenimiento/v_upd_codigo_unico_masivo', $data);
            } else {
                redirect('login', 'refresh');
            }
        } else {

            redirect('login', 'refresh');
        }

    }

    function updateCodigoUnicoMant() {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null; 
        try {


            $arrayJson = array();
            $arrayItemplan = array();
			$arrayCotizacion = array();
            $cont = 0;
            $idEstacion = null;
            $flgValida = 0;
            $flgPartidasNoExist = 0;
			$arrayUpdate = array();
			$arrayLog = array();
			$idUsuario = $this->session->userdata('idPersonaSession');
			
            if($idUsuario == null) {
                throw new Exception('La sesi&oacute;n a expirado, recargue la p&aacute;gina');
            }

            if(isset($_FILES['file']['name'])) {
                $path   = $_FILES['file']['tmp_name'];
                $object = PHPExcel_IOFactory::load($path);
                foreach($object->getWorksheetIterator() as $worksheet) {
                    $highestRow    = $worksheet->getHighestRow();
                    $highestColumn = $worksheet->getHighestColumn();
					$cont = 1;

                    for($row=2; $row<=$highestRow; $row++) {
                        $itemplan       	  = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                        $codigo               = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
						$descripcion 		  = $worksheet->getCellByColumnAndRow(2, $row)->getValue();

						_log("codigo : ".$codigo);
						$dataSam = $this->m_utils->getDataByCodigoUnicoSam($this->sam_db, $codigo);

                        
						$dataArray['observacion'] = null;

                        if($itemplan == null || $itemplan == '') {
							$dataArray['observacion'] = 'Ingresar itemplan.';
						}

                        if($codigo == null || $codigo == '') {
							$dataArray['observacion'] = 'Ingresar codigo.';
						}
                        _log(print_r($dataSam, true));
                        $departamento = $dataSam['departamento'];
                        $provincia    = $dataSam['provincia'];
                        $distrito     = $dataSam['distrito'];
                        $longitud     = $dataSam['longitud'];
                        $latitud      = $dataSam['latitud'];
                        
                        $arrayIdCentral  = $this->m_utils->getMdfCoord($latitud, $longitud);
 _log(print_r($arrayIdCentral, true));
                        $idCentral = $arrayIdCentral['idCentral'];
                        

                        if($descripcion == null || $descripcion == '') {
							$dataArray['observacion'] = 'Ingresar nombre.';
						}

                        if($idCentral == null || $idCentral == '') {
							$dataArray['observacion'] = 'Ingresar central.';
						}

						if($departamento == null || $departamento == '') {
							$dataArray['observacion'] = 'Ingresar Departamento.';
						}
						
						if($provincia == null || $provincia == '') {
							$dataArray['observacion'] = 'Ingresar provincia.';
						}
						
						if($distrito == null || $distrito == '' ) {
							$dataArray['observacion'] = 'Ingresar distrito';
						}
						
						if($dataArray['observacion'] != null) {
							$dataLog = array(
												"nro"         => $cont,
												"codigo"      => $codigo,
												"itemplan"    => $itemplan,
												"longitud"    => $longitud,
												"latitud"     => $latitud,
												"observacion" => $dataArray['observacion']				
											);
						} else {_log("entro1");
							$dataUpdate = array(
                                                    "codigo_unico"        => $codigo,
													"itemplan"            => $itemplan,
                                                    "nombreProyecto"      => $descripcion,
                                                    "departamento_matriz" => $departamento,
                                                    "provincia_matriz" 	  => $provincia,
                                                    "distrito_matriz" 	  => $distrito,
                                                    "idCentral"           => $idCentral,
                                                    "idCentralPqt"        => $idCentral,
													"coordX"              => $longitud,
													"coordY"              => $latitud
												);
		
							array_push($arrayUpdate, $dataUpdate);
							
							$dataLog = array(
												"nro"         => $cont,
												"codigo"      => $codigo,
												"itemplan"    => $itemplan,
												"longitud"    => $longitud,
                                                "latitud"     => $latitud,
												"observacion" => 'OK'
											);
						}
						
						array_push($arrayLog, $dataLog);
						$cont++;
                    }
                }
            }
			// list($data, $dataTabla) = $this->insertItemplan($arrayRegistro);

			$data = $this->m_utils->actualizarObraMasiva($arrayUpdate);
 _log(print_r($data, true));
			if($data['error'] == EXIT_ERROR) {
				throw new Exception($data['msj']);
			}
			
			$data['tablaItem'] = $this->getTablaItems($arrayLog);
        }catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }
	
	function getTablaItems($dataTabla) {
        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
							<th>NRO</th>
							<th>CODIGO</th>
                            <th>ITEMPLAN</th>
                            <th>LONGITUD</th>
                            <th>LATITUD</th>
							<th>OBSERVACION</th>
                        </tr>
                    </thead>                    
                    <tbody>';
                                                                         
                foreach($dataTabla as $row){
                    $html .=' <tr>
                                <td>'.$row['nro'].'</td>
								<td>'.$row['codigo'].'</td>
                                <td>'.$row['itemplan'].'</td>
								<td>'.$row['longitud'].'</td>
                                <td>'.$row['latitud'].'</td>
								<td>'.$row['observacion'].'</td>
                            </tr>';   
				}
			$html .='
					</tbody>
                </table>';
                    
            return $html;
    }
	

	
}