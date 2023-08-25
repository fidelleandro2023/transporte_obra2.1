<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *
 *
 *
 *
 */
class C_plantaInterna extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_plantaInterna/M_plantaInterna');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index()
    {

        $logedUser = $this->session->userdata('usernameSession');
        if($logedUser != null){
            $item = (isset($_GET['item']) ? $_GET['item'] : '');
            $dataITemplan = $this->M_plantaInterna->getZonalByItemplan($item);
            $idZonal = $dataITemplan['idZonal'];
            $idSubProyecto = $dataITemplan['idSubProyecto'];

            $idSubProyectoEstacion = (isset($_GET['idSub']) ? $_GET['idSub'] : '');
            $data['itemplan'] = $item;
            $data['idSubProyectoEstacion'] = $idSubProyectoEstacion;
            $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
            $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
            $permisos =  $this->session->userdata('permisosArbolTransporte');
            #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_PLANTA_INTERNA,ID_PERMISO_HIJO_BANDEJA_REGISTRO_PTR_INTERNA);
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_PLANTA_INTERNA,ID_PERMISO_HIJO_BANDEJA_REGISTRO_PTR_INTERNA, ID_MODULO_PAQUETIZADO);
            $data['opciones'] = $result['html'];
			
			$flgPqt = $this->m_utils->getFlgPaquetizadoPo($item);
			$rowArray = $this->m_utils->getPlanobraByItemplan($item);
			
			$idEmpresacolab = $dataITemplan['idEmp'];
			$tablaActividades = $this->makeHTLMTablaActividades($this->M_plantaInterna->getAllActividadesByContrato($item));
			
			// if($flgPqt == 2) {
				// $idEmpresacolab = $dataITemplan['idEmp'];
				// $tablaActividades = $this->makeHTLMTablaActividades($this->M_plantaInterna->getAllActividadesPqt($idZonal,$idSubProyecto, $idEmpresacolab));
			// } else {
				// $tablaActividades = $this->makeHTLMTablaActividades($this->M_plantaInterna->getAllActividades($idZonal,$idSubProyecto));
			// }
            $data['tablaActividades'] = $tablaActividades;

            $idUsuario = $this->session->userdata('idPersonaSession');
            if($result['hasPermiso'] == true || $idUsuario == 3){
                $this->load->view('vf_plantaInterna/V_plantaInterna',$data);
            }else{
                redirect('login','refresh');
            }
        }else{
            redirect('login','refresh');
        }
    }

    public function makeHTLMTablaActividades($lista){
        $html = '                 
                    <table style="font-size: 12px;" id="idTablaActividades" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th style="font-weight: bolder; color: white; background-color: #0154a0; text-align: center">ACTIVIDAD</th>
                            <th style="font-weight: bolder; color: white; background-color: #0154a0; text-align: center">BAREMO</th>
                            <th style="font-weight: bolder; color: white; background-color: #0154a0; text-align: center">COSTO KIT</th>
                            <th style="font-weight: bolder; color: white; background-color: #0154a0; text-align: center">CHECK</th>
                        </tr>
                    </thead>             
                    <tbody>';

        foreach($lista->result() as $row){
            $html .=' <tr>
							<td>'.$row->descripcion.'</td>
							<td STYLE="text-align: center">'.$row->baremo.'</td>							
							<td  STYLE="text-align: center">'.$row->costo_material.'</td>
							<td  STYLE="text-align: center" ><label class="custom-control custom-checkbox">';
            $html .= "<input type='checkbox' id='checkBoxActividad".$row->idActividad."' class='custom-control-input' onchange='addActividad(".json_encode($row).")'>";
            $html .='  <span class="custom-control-indicator"></span>
                                </label></td>                           
						</tr>';
        }
        $html .='</tbody>
                </table>';

        return utf8_decode($html);
    }

    public function guardarPTR()
    {

        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $actividades = array();
            $actividades = $this->input->post('actividades');
            $itemp = $this->input->post('itemplan');
            $idSubPr = $this->input->post('idSubEsta');
            $vale_reserva = null;
            $usua_crea = $this->session->userdata('idPersonaSession');
            $fecha_crea = date('Y-m-d H:i:s');
            $ultimo_estado = null;
            $fecha_ultimo_estado = null;
            $usua_ultimo_estado = null;
            $itemplan = $itemp;
            $idSubProyectoEstacion = $idSubPr;
            
            $count = $this->M_plantaInterna->countPtrPlantaInterna($itemplan);
			
			$codigoPO = $this->m_utils->getCodigoPO($itemplan);
            
			if($count > 0) {
                throw new Exception('Ya tiene una ptr asignada a este itemplan');
            }
			
			if ($codigoPO == null) {
				throw new Exception('Hubo un error al generar el codigo PO ');
			}
			
            $data = $this->M_plantaInterna->insertPTRPlantaInterna(ESTADO_01_TEXTO, $vale_reserva,
																	$usua_crea,$fecha_crea,
																	$ultimo_estado,$fecha_ultimo_estado,
																	$usua_ultimo_estado,$itemplan,
																	$idSubProyectoEstacion, $actividades, $codigoPO);
            if($data['error']==EXIT_ERROR){
                throw new Exception('ERROR INSERT PTR');
            }
			


            $idEstadoPlan = $this->m_utils->getEstadoPlanByItemplan($itemplan);
            $needMatPo = $this->M_plantaInterna->hasMatPinValidationByItemplan($itemplan);
            if($needMatPo > 0){//validamos si tiene configurada la estacion mat pin 22.07.2019
                 $hasPoMat = $this->M_plantaInterna->hasPoMatPinActivo($itemplan);
                 //if($hasPoMat > 0){//si ya cuenta con po mat
                     if($idEstadoPlan==ESTADO_PLAN_PRE_DISENO){
                         $data = $this->m_utils->updateEstadoPlanObra($itemplan,ESTADO_PLAN_DISENO);
                     }
                 //}
            }else{//si no la tiene continuar con el flujo anterior
                if($idEstadoPlan==ESTADO_PLAN_PRE_DISENO){
                    $data = $this->m_utils->updateEstadoPlanObra($itemplan,ESTADO_PLAN_DISENO);
                }
            }
            
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }


} // LLAVE FINAL DE D