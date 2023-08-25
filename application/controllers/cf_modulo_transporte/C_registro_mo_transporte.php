<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *
 *
 *
 *
 */
class C_registro_mo_transporte extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_transporte/m_registro_mo_transporte');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index()
    {

        $logedUser = $this->session->userdata('usernameSession');
        if($logedUser != null){
            $item = (isset($_GET['item']) ? $_GET['item'] : '');
            $dataITemplan = $this->m_registro_mo_transporte->getZonalByItemplan($item);
            $idZonal = $dataITemplan['idZonal'];
            $idSubProyecto = $dataITemplan['idSubProyecto'];

            $idSubProyectoEstacion = (isset($_GET['idSub']) ? $_GET['idSub'] : '');
            $data['itemplan'] = $item;
            $data['idSubProyectoEstacion'] = $idSubProyectoEstacion;
            $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
            $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
            $permisos =  $this->session->userdata('permisosArbol');
            #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_PLANTA_INTERNA,ID_PERMISO_HIJO_BANDEJA_REGISTRO_PTR_INTERNA);
            $result = $this->lib_utils->getHTMLPermisos($permisos, 309, 311, 8);
            $data['opciones'] = $result['html'];
			
			$flgPqt = $this->m_utils->getFlgPaquetizadoPo($item);
			$rowArray = $this->m_utils->getPlanobraByItemplan($item);
			
            $idEmpresacolab = $dataITemplan['idEmp'];
            $tablaActividades = $this->makeHTLMTablaActividades($this->m_registro_mo_transporte->getAllActividades($idZonal,$idSubProyecto));
			
            $data['tablaActividades'] = $tablaActividades;


            if($result['hasPermiso'] == true){
                $this->load->view('vf_modulo_transporte/v_registro_mo_transporte',$data);
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

    public function generarPOTransporte()
    {

        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
			
			$this->db->trans_begin();
			
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
            
            $countExit = $this->m_registro_mo_transporte->countPoTransExit($itemplan);
			
			$codigoPO = $this->m_utils->getCodigoPOTransporte($itemplan);
            
			if($countExit > 0) {
                throw new Exception('Ya tiene una ptr asignada a este itemplan');
            }
			
			if ($codigoPO == null) {
				throw new Exception('Hubo un error al generar el codigo PO ');
			}
			
			$dataArray = $this->m_utils->getDataObraTransporteRow($itemplan);
			
			$idEecc = $dataArray['idEmpresaColab'];
			
			if($idEecc == null || $idEecc == '') {
				throw new Exception('ECC colaboradora no asignada, comunicarse con el programador a cargo');
			}
			
			$idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;
			$fechaActual = $this->m_utils->fechaActual();
			
			$arrayPoDetalle = array();
			$costoTotalPOMO = 0;
			foreach ($actividades as $ar) {
				if ($ar != null) {
					$costoTotalPOMO = $ar['total'] + $costoTotalPOMO;
					
					$datatrans['idPartida']        = $ar['idActividad'];
					$datatrans['cantidad_inicial'] = $ar['cantidad'];
					$datatrans['cantidad_final']   = $ar['cantidad'];
					$datatrans['costo_mo']         = $ar['costoMO'];
					$datatrans['costo_mat']        = $ar['costoMAT'];
					$datatrans['monto_final']      = $ar['total'];
					$datatrans['costo']            = $ar['costo'];
					$datatrans['baremo']           = $ar['baremo'];
					$datatrans['codigo_po']    	   = $codigoPO;
					array_push($arrayPoDetalle, $datatrans);
				}
			}
			
			$dataPO = array(
								'itemplan'      => $itemplan,
								'codigo_po'     => $codigoPO,
								'estado_po'     => PO_REGISTRADO, //ESTADO REGISTRADO
								'idEstacion'    => ID_ESTACION_TRANSPORTE,
								'costo_total'   => $costoTotalPOMO,
								'idUsuario'     => $idUsuario,
								'fechaRegistro' => $this->m_utils->fechaActual(),
								'estado_asig_grafo' => 0,
								'flg_tipo_area' => 2,//MANO DE OBRA
								'id_eecc_reg'   => $idEecc,
								'idSubProyectoEstacion' => $idSubProyectoEstacion
							);
							
			$dataLogPO = array(
								'codigo_po'         =>  $codigoPO,
								'itemplan'          =>  $itemplan,
								'idUsuario'         =>  $idUsuario,
								'fecha_registro'    =>  $fechaActual,
								'idPoestado'        =>  PO_REGISTRADO
							);
			
			$data = $this->m_registro_mo_transporte->createPoMOTransporte($dataPO, $dataLogPO, $arrayPoDetalle);
            // $data = $this->m_registro_mo_transporte->insertPTRPlantaInterna(ESTADO_01_TEXTO, $vale_reserva,
																	// $usua_crea,$fecha_crea,
																	// $ultimo_estado,$fecha_ultimo_estado,
																	// $usua_ultimo_estado,$itemplan,
																	// $idSubProyectoEstacion, $actividades, $codigoPO);
            if($data['error']==EXIT_ERROR){
                throw new Exception('ERROR INSERT PO');
            }
						
            $arrayDataTrans = $this->m_utils->getDataObraTransporteRow($itemplan);

			if($arrayDataTrans['idEstadoPlan'] == ESTADO_PLAN_PRE_DISENO){
				$dataUpdate = array(
										"idEstadoPlan" => ESTADO_PLAN_DISENO,
										"usu_upd"      => $idUsuario,
										"fecha_upd"    => $fechaActual,
										"descripcion"  => 'COTIZADO'
									);
								
				$data = $this->m_utils->updatePlanObraTransporte($itemplan, $dataUpdate);
			}
            
			if($data['error'] == EXIT_ERROR) {
				throw new Exception($data['msj']);
			}
			
			$this->db->trans_commit();
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
			$this->db->trans_rollback();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
} // LLAVE FINAL DE D