<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_bandeja_cambio_po extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_plan_obra/m_bandeja_cambio_po');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

	public function index()
	{  	   
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){       
                $data['tablaBandeja'] = $this->makeHTLMTablaBandeja();
                $data['listaTiCen'] = $this->m_utils->getAllCentral();
                $data['nombreUsuario'] =  $this->session->userdata('usernameSession');	
                $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
                $permisos =  $this->session->userdata('permisosArbol');
                #$result = $this->lib_utils->getHTMLPermisos($permisos, 10, 186);
                $result = $this->lib_utils->getHTMLPermisos($permisos, 237, 186, ID_MODULO_PAQUETIZADO);
                $data['opciones'] = $result['html'];
                if($result['hasPermiso'] == true){
                    $this->load->view('vf_plan_obra/v_bandeja_cambio_po',$data);
                }else{
                    redirect('login','refresh');
                }
        }else{
            redirect('login','refresh');
        }
    }
        
    function makeHTLMTablaBandeja() {
        $data = $this->m_bandeja_cambio_po->getTablaBandejaCambio();

        $html = '<table class="table table-bordered" style="font-size: 10px;">
                    <thead class="thead-default">
                        <tr>    
                            <th>ACCI&Oacute;N</th>               
                            <th>ITEMPLAN</th>
                            <th>SUBPROYECTO</th>                            
                            <th>ESTACION</th>                              
                            <th>PO</th>
                        </tr>
                    </thead>
                   
                    <tbody>';															                                                   
            foreach($data as $row){
                $btnGenerarPo = null;
                if(trim($row['codigo_po']) != '' && trim($row['codigo_po']) != NULL) {
                    $btnGenerarPo = '<i class="zmdi zmdi-hc-2x zmdi-assignment-o" data-itemplan="'.$row['itemplan'].'" 
                                        data-id_estacion="'.$row['idEstacion'].'" data-codigo_po="'.$row['codigo_po'].'"
                                        onclick="openModalGenerarPO($(this));">
                                    </i>';
                }      
                $html .='<tr>                                                         
                            <td>
                                <a>
                                   '.$btnGenerarPo.'
                                </a>
                            </td>
                            <td>'.$row['itemplan'].'</td>
                            <td>'.$row['subProyectoDesc'].'</td>
                            <td>'.$row['estacionDesc'].'</td>	
                            <td>'.$row['codigo_po'].'</td>						
                        </tr>';
            }  
   			  
			 $html .='</tbody>
                </table>';                    
        return utf8_decode($html);
    }

    function getCmbEstacionCambioPo() {
        $itemplan = $this->input->post('itemplan');

        $cmbEstacion = __buildComboEstacionByItemplan($itemplan, null, 1);//SOLO DISEÑO

        $data['cmbEstacion'] = $cmbEstacion;
        echo json_encode(array_map('utf8_encode', $data));
    }

    function getCmbCodigoPo() {
        $itemplan   = $this->input->post('itemplan');
        $idEstacion = $this->input->post('idEstacion');

        $cmbCodigoPo = __buildCmbCodigoPoByEstacionItemplan($itemplan, 1); // traigo las PO de diseno 

        $data['cmbCodigoPo'] = $cmbCodigoPo;
        echo json_encode(array_map('utf8_encode', $data));
    }

    function registrarData() {
        $data['msj']   = null;
        $data['error'] = EXIT_ERROR;
        try {
            $idUsuario = $this->session->userdata('idPersonaSession');

            if($idUsuario == null || $idUsuario == '') {
                throw new Exception('error, la sesi&oacute;n a caducado.');
            }

            $itemplan   = $this->input->post('itemplan');
            $idEstacion = $this->input->post('idEstacion');
            $codigo_po  = $this->input->post('codigo_po');
    
            if($itemplan == null) {
                throw new Exception('error, No se ingres&oacute; itemplan.');
            }
    
            if($idEstacion == null) {
                throw new Exception('error, No se ingres&oacute; idEstacion.');
            }
            
            $existPo = $this->m_utils->getCodigoPoByEstacionItemplan($itemplan, $idEstacion, 1);
            
            if($existPo != null) {
                if($codigo_po == null) {
                    throw new Exception('error, No se ingres&oacute; PO.');
                }
            }

            $arrayData = array(
                                'itemplan'       => $itemplan,
                                'idEstacion'     => $idEstacion,
                                'codigo_po'      => $codigo_po,
                                'fecha_registro' => $this->fechaActual(),
                                'idUsuario'      => $idUsuario
                             );
            $data = $this->m_bandeja_cambio_po->insertData($arrayData);

            $data['tablaBandeja'] = $this->makeHTLMTablaBandeja();
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function openModalGenerarPO() {
		$data['msj']   = null;
        $data['error'] = EXIT_ERROR;
        try {
			$itemplan  = $this->input->post('itemplan');
			$codigo_po = $this->input->post('codigo_po');
			//$idTipoComplejidad = $this->input->post('idTipoComplejidad');
			$idEstacion = $this->input->post('idEstacion');
			_log("CODIGO : ".$codigo_po);
			if($itemplan == null || $codigo_po == null) {
				throw new Exception("No tiene PO");
			}
			
			$flg = $this->m_utils->validarComplejidadDiseno($itemplan);

			$idProyecto  = $this->m_utils->getIdProyectoByItemplan($itemplan);
			$data['input'] = null;
			$inputATro = null;
			if($flg == 1) {
				if($idEstacion == 2 && $idProyecto == 1) {
					$inputATro = '<label>CANT. AMPLIFICADOR</label>
											<input id="cant_amplificador" type="text" class="form-control" />';
					$data['input'] = 1;                        
				} else if($idEstacion == 5 && $idProyecto == 1) {
					$data['input'] = 2;  
					$inputATro = '<label>CANT. TROBA</label>
											<input id="cant_troba" type="text" class="form-control" />';
				}
			}
			$data['inputAmTro'] = $inputATro;
			$data['cmbComplejidad'] = __buildComboComplejidad($codigo_po);
			$data['error'] = EXIT_SUCCESS;
		} catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function generarPOComplejidadDiseno() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
           $idUsuario =  $this->session->userdata('idPersonaSession');

           if($idUsuario == null || $idUsuario == '') {
               throw new Exception('error, su sesi&oacute;n a finalizado, actualice la p&aacute;gina.');
           }

            $itemplan          = $this->input->post('itemplan');
            $codigo_po         = $this->input->post('codigo_po');
            $idEstacion        = $this->input->post('idEstacion');
            $idTipoComplejidad = $this->input->post('idTipoComplejidad');
            $nro_amplificador  = $this->input->post('nro_amplificador');
            $nro_troba         = $this->input->post('nro_troba');

            if($itemplan == null || $codigo_po == null || $idEstacion == null || $idTipoComplejidad == null) {
                throw new Exception('error, comunicarse con el programador');
            }

            $resp = $this->m_bandeja_cambio_po->generarPoComplej($itemplan, $idEstacion, $idUsuario, $nro_amplificador, $nro_troba, $idTipoComplejidad, $codigo_po);

            if($resp == 4) {
                throw new Exception('error, No est&aacute; liquidado.');
            }
            else if($resp == 2) {
                throw new Exception('error, ya tiene PO dise&ntilde;o.');
            }
            else if($resp == 3) {
                throw new Exception('error, No tiene PO material.');
            } else if($resp == 5) {
                throw new Exception('error, No se gener&oacute; la PO.');
            } else if($resp == 7) {
                throw new Exception('error, No se gener&oacute; la PO, se debe agregar la partida de la complejidad que desea crear.');
            }

            $data['error'] = EXIT_SUCCESS;
        }catch(Exception $e) {
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