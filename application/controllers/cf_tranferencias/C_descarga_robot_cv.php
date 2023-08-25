<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_descarga_robot_cv extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
		$this->load->library('excel');
        $this->load->helper('url');
        $this->load->library('zip');
    }

    public function index()
    {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {
            // Trayendo zonas permitidas al usuario
            $arrayIdActidad = (isset($_GET['arryAct']) ? $_GET['arryAct'] : '');
            if ($arrayIdActidad != null) {
                $listaProyEstPart = $this->m_utils->getAllProyEstPartida(null, null, null, $arrayIdActidad);
            } else {
                $listaProyEstPart = '';
            }
            $zonas = $this->session->userdata('zonasSession');
			$infoMdf     = $this->getCoordMDF();
			$infoCtoEdif = $this->getCoordCTOEdificios();
            $idPersonaSession = $this->session->userdata('idPersonaSession');
            $descPerfilSesion = $this->session->userdata('descPerfilSession');
			
			$data['marcadores_mdf']    =  $infoMdf['marcaMdf'];
            $data['info_markers_mdf']  =  $infoMdf['infoMarcaMdf'];
			$data['marcadores_cto_edif']   =  $infoCtoEdif['marcaCtoEdif'];
			$data['info_markers_cto_edif'] =  $infoCtoEdif['infoMarcaCtoEdif'];
			
            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');

            $data['cmbEmpresaColab'] = __buildCmbEmpresaColab();
			$this->m_utils->deleteRobotCv();
			$data['tbRobotCv'] = $this->getTablaRobotCv();
            $permisos = $this->session->userdata('permisosArbol');
            #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_MANTENIMIENTO, ID_PERMISO_HIJO_MANT_PROY_EST_PARTIDA);
			$result = $this->lib_utils->getHTMLPermisos($permisos, 233, 306, 2);
            $data['opciones'] = $result['html'];
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_tranferencias/v_descarga_robot_cv', $data);
            } else {
                redirect('login', 'refresh');
            }
        } else {

            redirect('login', 'refresh');
        }

    }

    function getInfoRobotCV() {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null; 
        try {
            $arrayJson = array();
            $arrayItemplan = array();
            $cont = 0;
            $idEstacion = null;
            $flgValida = 0;
            $flgPartidasNoExist = 0;

            if($this->session->userdata('idPersonaSession') == null) {
                throw new Exception('La sesi&oacute;n a expirado, recargue la p&aacute;gina');
            }
            $pathItemplan = 'uploads/actasOc';
            
            $files = glob('uploads/actasOc/*'); //obtenemos todos los nombres de los ficheros
            foreach($files as $file){
                if(is_file($file))
                unlink($file); //elimino el fichero
            }

            if(isset($_FILES['file']['name'])) {
                $path   = $_FILES['file']['tmp_name'];
                $object = PHPExcel_IOFactory::load($path);
                foreach($object->getWorksheetIterator() as $worksheet) {
                    $highestRow    = $worksheet->getHighestRow();
                    $highestColumn = $worksheet->getHighestColumn();
                    for($row=2; $row<=$highestRow; $row++) {
                        $itemplan = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                        $latitud  = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
						$longitud = $worksheet->getCellByColumnAndRow(2, $row)->getValue();

                        if($itemplan != null) {
                            //array_push($arrayItemplan, $itemplan);
                            $this->insertDataSimuladorCv($itemplan, $latitud, $longitud);
                        }
                    }
                }
            }

			$data['tablaRobotCv'] = $this->getTablaRobotCv();
            $data['error'] = EXIT_SUCCESS;
			
        }catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
	
	function insertDataSimuladorCv($itemplan, $latitud, $longitud) {
		$dataCTO = $this->m_utils->getCtoByCoordEdifCV($latitud, $longitud);
		//$flg_vias = $this->m_utils->getFlgViasMetrosPolitanas($longitud, $latitud);
		if($latitud != $longitud) {
			foreach($dataCTO as $row) {
				$arrayInsert[] = array(
										'itemplan' 		   => $itemplan,
										'latitud'  		   => $latitud,
										'longitud'		   => $longitud,
										'distancia_lineal' => $row['distancia'],
										'tendido' 		   => $row['tendido'],
										'facilidad' 	   => $row['codigo'],
										'tipo'      	   => 'CTO',
										'flg_vias_metro'   => $row['flg_cruce']
									);
			}
						
			$this->m_utils->insertRobotCv($arrayInsert);
		}	
	}
	
	function getTablaRobotCv() {
        $data = $this->m_utils->getDataRobotCv();

        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>NRO</th>
                            <th>ITEMPLAN</th>
                            <th>LATITUD</th>
                            <th>LONGITUD</th>
                            <th>FACILIDAD</th>
                            <th>DISTANCIA LINEAL</th>
                            <th>TENDIDO</th>
							<th>ESTATUS VIA METRO.</th>
							<th>TIPO</th>
                        </tr>
                    </thead>                    
                    <tbody>';
                $cont = 1;
                                                                                                                  
                foreach($data as $row){
                    $html .=' <tr>
                                <td>'.$cont.'</td>
                                <td>'.$row['itemplan'].'</td>
                                <td>'.$row['latitud'].'</td>
                                <td>'.$row['longitud'].'</td>
                                <td>'.$row['facilidad'].'</td>
                                <td>'.$row['distancia_lineal'].'</td>
                                <td>'.$row['tendido'].'</td>
								<td>'.$row['estatus_via'].'</td>
								<td>'.$row['tipo'].'</td>
                            </tr>';
                        $cont++;    
				}
			$html .='
					</tbody>
                </table>';
                    
            return $html;
    }
	
	function getCoordMDF() {
		$marcaMdf   = array();
        $infoMarcaMdf = array();
        $dataArray = $this->m_utils->getDataCoordenadasNodo();
        foreach($dataArray as $row){
            $temp = array($row['codigo'], $row['longitud'], $row['latitud']);
            array_push($marcaMdf, $temp);
    
            $temp2 = array('<table style="text-align: left;">
                                <tbody>
                                    <tr>
                                        <td><strong>Codigo:</strong></td>
                                        <td>'.$row['codigo'].'</td>
                                    </tr>
                                </tbody>
                            </table>');
            array_push($infoMarcaMdf, $temp2);
        }
        $data['marcaMdf'] 	  = $marcaMdf;
        $data['infoMarcaMdf'] = $infoMarcaMdf;
        return $data;
	}
	
	function getCoordCTOEdificios(){
        //$data = array();
        $marcaCto   = array();
        $infoMarcaCto = array();
        $dataArray = $this->m_utils->getAllCtoEdificios();
        foreach($dataArray as $row){
            $temp = array(
                            'codigo'   => $row['codigo'],
                            'longitud' => $row['longitud'],
                            'latitud'  => $row['latitud'],
                            'icon_cto' => ($row['hilos_disponibles'] == 0) ? base_url().'public/img/iconos/cto_edif_2_red.png' : base_url().'public/img/iconos/cto_edif_2.png'
                         );
            //$temp = array($row['codigo'], $row['longitud'], $row['latitud']);
            array_push($marcaCto, $temp);
    
            $temp2 = array('<table style="text-align: left;">
                                <tbody>
                                    <tr>
                                        <td><strong>Tipo:</strong></td>
                                        <td>CTO</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Codigo:</strong></td>
                                        <td>'.$row['codigo'].'</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Hilos Disponibles:</strong></td>
                                        <td>'.$row['hilos_disponibles'].'</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total Hilos:</strong></td>
                                        <td>'.$row['total_hilos'].'</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Coordenadas:</strong></td>
                                        <td>('.$row['longitud'].','.$row['latitud'].')</td>
                                    </tr>
									<tr>
                                        <td><strong>Cant. aprob. Cotizaciones:</strong></td>
                                        <td>'.$row['cant_coti_aprob'].'</td>
                                    </tr>
						
									<tr>
                                        <td><strong>Tecnologia:</strong></td>
                                        <td>'.$row['tecnologia'].'</td>
                                    </tr>
									<tr>
                                        <td><strong>Ubicacion:</strong></td>
                                        <td>'.$row['tipo_pa'].'</td>
                                    </tr>
                                </tbody>
                            </table>');
            array_push($infoMarcaCto, $temp2);
        }
        $data['marcaCtoEdif'] 	  = $marcaCto;
        $data['infoMarcaCtoEdif'] = $infoMarcaCto;
        return $data;
    }
	
	function getPostesMap() {
		//$infoEbc     = $this->getPostesCv();
		
		$dataArray = $this->m_utils->getAllPostes();
		$data['img'] = base_url().'public/img/iconos/postes_1.png';
		$data['objDataPoste'] = $dataArray;
		// $data['marcaPoste']   = $infoEbc['marcaPoste'];
		// $data['infoMarcaPoste'] = $infoEbc['infoMarcaPoste'];
		
		echo json_encode($data);
	}
}
