<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *
 *
 *
 *
 */
class C_edit_ptr_planta_interna extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_plantaInterna/M_edit_ptr_planta_interna');
        $this->load->model('mf_control_presupuestal/m_control_presupuestal');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index()
    {

        $logedUser = $this->session->userdata('usernameSession');
        if($logedUser != null){
            $item = (isset($_GET['item']) ? $_GET['item'] : '');
            $ptr  = (isset($_GET['ptr']) ? $_GET['ptr'] : '');
            $flg_rechazado = (isset($_GET['flg_rechazado']) ? $_GET['flg_rechazado'] : '');
            $allActividades = $this->M_edit_ptr_planta_interna->getAllActividadesByPTR($ptr);
            $dataITemplan  = $this->M_edit_ptr_planta_interna->getZonalByItemplan($item);
            $idZonal       = $dataITemplan['idZonal'];
            $idSubProyecto = $dataITemplan['idSubProyecto'];
            $idSubProyectoEstacion = (isset($_GET['idSub']) ? $_GET['idSub'] : '');
            $data['itemplan'] = $item;
            $data['ptr'] = $ptr;
            $data['flg_rechazado'] = $flg_rechazado;
            $data['idSubProyectoEstacion'] = $idSubProyectoEstacion;
            $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
            $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
            $permisos =  $this->session->userdata('permisosArbolTransporte');
            #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_PLANTA_INTERNA,ID_PERMISO_HIJO_BANDEJA_EDITAR_PTR);
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_PLANTA_INTERNA,ID_PERMISO_HIJO_BANDEJA_EDITAR_PTR, ID_MODULO_PAQUETIZADO);
            $data['opciones'] = $result['html'];
            $actividadesPTR = $this->M_edit_ptr_planta_interna->getAllActividadesTableTotal($idSubProyecto, $idZonal, $ptr);
            $data['actividadesTable'] = $this->getArrayToEdit($actividadesPTR);
            $dataToView = $this->makeHTMLBodyActividades($actividadesPTR);
            $data['bodyTablaActividades'] = $dataToView['html'];
            $data['monto_total_ptr'] = $dataToView['monto_total'];
            $data['monto_total_mo']  = $dataToView['monto_total'];
            $data['tablaActividades'] = $this->makeHTLMTablaActividades($this->M_edit_ptr_planta_interna->getAllActividadesEdit($idSubProyecto, $idZonal, $ptr));


            if($result['hasPermiso'] == true){
                $this->load->view('vf_plantaInterna/V_edit_ptr_planta_interna',$data);
            }else{
                redirect('login','refresh');
            }
        }else{
            redirect('login','refresh');
        }
    }

    public function getArrayToEdit($listaActividades){
        $datos = array();
        foreach($listaActividades->result() as $row){
            $datos[$row->idActividad] = $row;
        }
        return $datos;
    }

    public function  makeHTMLBodyActividades($listaActividades){
        $data = array();
        $html = '';
        $monto_total    = 0.00;
        $monto_total_mo = 0.00;
        foreach($listaActividades->result() as $row){
            $html .= '  <tr id="actividad'.$row->idActividad.'">
                            <td>'.$row->descripcion.'</td>
                            <td id="costo'.$row->idActividad.'">'.$row->costo.'</td>
                            <td id="baremo'.$row->idActividad.'">'.$row->baremo.'</td>
                            <td style="max-width: 100px"><input type="text" value="'.$row->cantidad.'" class="form-control" id="cantidad'.$row->idActividad.'" onkeyup="calculaTotal('.$row->idActividad.')" style="border-style: ridge; border-width: 4px; text-align: center"></td>
                            <td id="totalBaremo'.$row->idActividad.'">'.$row->costo_mo.'</td>
                            <td id="precioKit'.$row->idActividad.'">'.$row->costo_material.'</td>
                            <td id="totalMaterial'.$row->idActividad.'">'.$row->costo_mat.'</td>
                            <td id="total'.$row->idActividad.'">'.$row->total.'</td>';
            $html .= "     <td><img src='/obra2.1/public/img/iconos/delete.png' style='cursor: pointer;' width='20px' onclick='addActividad(".json_encode($row).")'></td> ";
            $html .= '   </tr>';
            $monto_total = $monto_total + $row->total;
            $monto_total_mo = $monto_total_mo + $row->costo_mo;
        }
        $data['html'] = $html;
        $data['monto_total'] = $monto_total;
        $data['monto_total_mo'] = $monto_total_mo;
        return $data;
    }

    public function makeHTLMTablaActividades($lista){
        $html = '                 
                    <table style="font-size: 10px;" id="idTablaActividades" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>ACTIVIDAD</th>
                            <th>BAREMO</th>
                            <th>COSTO KIT</th>
                            <th>CHECK</th>
                        </tr>
                    </thead>             
                    <tbody>';

        foreach($lista->result() as $row){


            $html .=' <tr>
							<td>'.$row->descripcion.'</td>
							<td STYLE="text-align: center">'.$row->baremo.'</td>							
							<td  STYLE="text-align: center">'.$row->costo_material.'</td>
							<td  STYLE="text-align: center" ><label class="custom-control custom-checkbox">';
            $html .= "<input type='checkbox' id='checkBoxActividad".$row->idActividad."' ".(($row->id_ptr_x_actividades_x_zonal!=null) ? 'checked' : '')." class='custom-control-input' onchange='addActividad(".json_encode($row).")'>";
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
            $actividades   = array();
            $actividades   = $this->input->post('actividades');
            $itemp         = $this->input->post('itemplan');
            $ptr           = $this->input->post('ptr');
            $flg_rechazado = $this->input->post('flg_rechazado');

            $finalDelete = $this->M_edit_ptr_planta_interna->getAllActividadesByPTR($ptr)->result();

            $arrayInsert = array();
            $arrayUpdate = array();
            $arrayDelete = array();
            foreach ($actividades as $ar) {
                if ($ar != null) {

                    if($ar['id_ptr_x_actividades_x_zonal'] == NULL){
                        $datatrans['ptr'] = $ptr;
                        $datatrans['id_actividad'] = $ar['idActividad'];
                        $datatrans['cantidad']     = $ar['cantidad'];
                        $datatrans['costo_mo']     = $ar['costoMO'];
                        $datatrans['costo_mat']    = $ar['costoMAT'];
                        $datatrans['total']        = $ar['total'];
                        $datatrans['precio']       = $ar['costo'];
                        $datatrans['baremo']       = $ar['baremo'];
                        $datatrans['descripcion']  = $ar['descripcion'];
                        $datatrans['itemplan']     = $itemp;
                        $datatrans['id_ptr_x_actividades_x_zonal'] = '';
                        array_push($arrayInsert, $datatrans);
                    }else{
                        $datatrans['id_ptr_x_actividades_x_zonal'] = $ar['id_ptr_x_actividades_x_zonal'];
                        $datatrans['id_actividad'] = $ar['idActividad'];
                        $datatrans['cantidad']     = $ar['cantidad'];
                        $datatrans['costo_mo']     = $ar['costoMO'];
                        $datatrans['costo_mat']    = $ar['costoMAT'];
                        $datatrans['total']        = $ar['total'];
                        $datatrans['precio']       = $ar['costo'];
                        $datatrans['baremo']       = $ar['baremo'];
                        $datatrans['descripcion']  = $ar['descripcion'];
                        $datatrans['itemplan']     = $itemp;
                        array_push($arrayUpdate, $datatrans);

                        array_push($arrayDelete, $ar['id_ptr_x_actividades_x_zonal']);
                    }
                }
            }

            if($flg_rechazado == FLG_RECHAZADO) {
                $arrayData = array( 
                                    'ultimo_estado' => NULL,
                                    'flg_rechazado' => NULL
                                  );
                $this->M_edit_ptr_planta_interna->updateEstadoUltimo($itemp, $ptr, $arrayData);
            }

            $arrayToDeletes = array();
            $arrayFinalDelete = array();
            for ($i = 0; $i < count($finalDelete); $i++) {
                    if (in_array($finalDelete[$i]->id_ptr_x_actividades_x_zonal, $arrayDelete)) {
                        array_push($arrayToDeletes,$i);
                    }
                $arrayFinalDelete[$i] = $finalDelete[$i]->id_ptr_x_actividades_x_zonal;
            }

            foreach ($arrayToDeletes as $row){
                unset($arrayFinalDelete[$row]);
            }

            $data = $this->M_edit_ptr_planta_interna->editActividadesPTRPlantaInterna($arrayInsert, $arrayUpdate, $arrayFinalDelete);
            
            $countPendienteExceso = $this->m_control_presupuestal->getCountValida($itemp, NULL);

            if($countPendienteExceso > 0) {
                throw new Exception('Esta obra tiene una solicitud de exceso pendiente.');
            }

            if($data['error']==EXIT_ERROR){
                throw new Exception('ERROR INSERT PTR');
            }
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();

        }
        echo json_encode(array_map('utf8_encode', $data));
    }


} // LLAVE FINAL DE D