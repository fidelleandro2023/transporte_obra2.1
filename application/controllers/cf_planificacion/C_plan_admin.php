<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class C_plan_admin extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');

        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_planificacion/m_plan_admin');
        $this->load->library('lib_utils');
        $this->load->library('zip');
        $this->load->helper('url');
    }

    function index() {
        $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
                $data['cmbPlan']       = __buildCmbPlanificacionItem(null, null);
                $data['cmbMes']  = __buildCmbMes();
                $data['cmbSubProyecto'] = __buildSubProyecto(null, null, 1);
                $data['cmbFase'] = __buildCmbFase();
               $permisos =  $this->session->userdata('permisosArbol');
            	$data['tablaItemsPlani'] = $this->getTablaItemByPlan(null);
               $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_NUEVO_MODELO_GESTION_INTEGRAL, ID_PERMISO_HIJO_PQT_REGIND_OBRA, ID_MODULO_PAQUETIZADO);
               $data['opciones'] = $result['html'];
        	   $this->load->view('vf_planificacion/v_plan_admin',$data);
        	   
        }else{
            redirect('login','refresh');
        }
    }

    function getObrasPlanificacion() {
        $id_plan = $this->input->post('id_plan');

        $data['tablaItemsPlani'] = $this->getTablaItemByPlan($id_plan);

        echo json_encode(array_map('utf8_encode', $data));
    }

    function getTablaItemByPlan($id_plan) {
        $data = $this->m_plan_admin->getObrasByPlan($id_plan);
        $html = '';

        if (count($data) == 0) {
            $html .= '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th></th>
                            <th>NOMPRE PLAN</th>
                            <th>ITEMPLAN</th>
                            <th>SUBPROYECTO</th>
                            <th>EECC</th>
                            <th>COD. SOLICITUD</th>
                            <th>ORDEN COMPRA</th>
                            <th>ABRE PUERTA/PORCENTAJE</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>';
        } else {
            $html .= '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th></th>
                            <th>NOMPRE PLAN</th>
                            <th>ITEMPLAN</th>
                            <th>SUBPROYECTO</th>
                            <th>EECC</th>
                            <th>COD. SOLICITUD</th>
                            <th>ORDEN COMPRA</th>
                            <th>ABRE PUERTA/PORCENTAJE</th>
                        </tr>
                    </thead>
                    <tbody>';

            foreach ($data as $row) {
                $ubicHref = base_url() . 'editCV2?item=' . $row['itemplan'];
                $botonCancelar = '<a data-itemplan="' . $row['itemplan'] . '" data-accion="cancelar" onclick="abrirModalEliminar($(this))"><img alt="Cancelar" height="20px" width="20px" src="public/img/iconos/cancelar.png" title="Cancelar"></a>';
                $btnEyesVertical = '<a data-itemplan ="' . $row['itemplan'] . '"  href="' . $ubicHref . '">
                                        <i style="color:#A4A4A4" title="ver" class="zmdi  zmdi-hc-2x zmdi-eye"></i>
                                    </a>';
                $html .= ' <tr>
                                <td>
                                    ' . $btnEyesVertical . '
                                </td>
                                <td>' . $row['nombre_plan'] . '</td>
                                <td>' . $row['itemplan'] . '</td>
                                <td>' . $row['subProyectoDesc'] . '</td>
                                <td>' . $row['empresaColabDesc'] . '</td>
                                <td>' . $row['solicitud_oc'] . '</td>
                                <td>' . $row['orden_compra'] . '</td>
                                <td>' . $row['situacion'] . '</td>
                            </tr>';
            }
            $html .= '</tbody>
                </table>';
        }


        return utf8_decode($html);
    }

    function getItemplanAllBySubPlan() {
        $id_plan = $this->input->post('id_plan');

        $dataArray = $this->m_utils->getIdSubProyectoByIdPLan($id_plan);
        $data['tablaItems'] = $this->getTablaObraAll(null, $dataArray['idSubProyecto'], null);

        echo json_encode(array_map('utf8_encode', $data));
    }

    function getTablaObraAll($itemplan, $idSubProyecto, $idEmpresaColab) {
        $data = $this->m_plan_admin->getPlanobraPlaniAll($itemplan, $idSubProyecto, $idEmpresaColab);
        $cont = 0;
        $html = '<table id="data-table2" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th></th>
                            <th>ITEMPLAN</th>
                            <th>SUBPROYECTO</th>
                            <th>EECC</th>
                            <th>FECHA CREACION</th>
                        </tr>
                    </thead>
                    <tbody>';

            foreach ($data as $row) {
                $cont++;
                $html .= ' <tr>
                                <td><input type="checkbox" id="check_'.$cont.'" data-itemplan="'.$row['itemplan'].'" onchange="checkListItem($(this), 1)" /></td>
                                <td>' . $row['itemplan'] . '</td>
                                <td>' . $row['subProyectoDesc'] . '</td>
                                <td>' . $row['empresaColabDesc'] . '</td>
                                <td>' . $row['fecha_creacion'] . '</td>
                            </tr>';
            }
            $html .= '</tbody>
                </table>';

        return utf8_decode($html);
    }

    function asignarItemPlani() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $arrayData = $this->input->post('arrayData');
            $id_plan   = $this->input->post('id_plan');


            $countAsigItems = count($arrayData);

            _log("COUNT ".$countAsigItems);
            $arrayCountPlan = $this->m_plan_admin->getCountPlan($id_plan, $countAsigItems);
                
            if($arrayCountPlan['flg_top_cantidad'] == 1) {//SI LLEGO AL TOPE DE LA CANTIDAD DEL PLAN
                _log("Restriccion");
                throw new Exception('El plan ya llego al limite, seleccionar otra planificaci&oacute;n.');
            }
            
            $idUsuario = $this->session->userdata('idPersonaSession');
            $fechaActual = $this->m_utils->fechaActual();

            $arrayLog = array();

            foreach($arrayData as $row) {
                $arrayLog[] = array (
                                        'itemplan' => $row['itemplan'],
                                        'id_plan'  => $row['id_plan'],
                                        'fecha_registro' => $fechaActual,
                                        'id_usuario_reg' => $idUsuario,
                                        'flg_asig'       => 1 //SE ASIGNO
                                    );
            }

            $data = $this->m_plan_admin->updatePlanItemplan($arrayData, $arrayLog);

            $dataArray = $this->m_utils->getIdSubProyectoByIdPLan($id_plan);
            
            $data['tablaItems'] = $this->getTablaObraAll(null, $dataArray['idSubProyecto'], null);
            $data['tablaItemsPlani'] = $this->getTablaItemByPlan($id_plan);
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function getDataCuotasPlan() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            $faseDesc      = $this->input->post('faseDesc');
            $idSubProyecto = $this->input->post('idSubProyecto');
            $idFase        = $this->input->post('idFase');

            if($faseDesc == null || $faseDesc == '' || $idSubProyecto == null || $idSubProyecto == '') {
                throw new Exception('Error no tiene fase o subproyecto');
            }

            $cantidadCuotas = $this->m_utils->getDataCuotasBySubProyecto($faseDesc, $idSubProyecto);
            $tablaPlanifica = $this->getDataTablaPlanificacion($idSubProyecto, $idFase);

            $data['tbPlanifica'] = $tablaPlanifica;
            $data['cantidadCuotas'] = $cantidadCuotas;
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function insertPlanifica() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $idFase   = $this->input->post('idFase');
            $idSubProyecto = $this->input->post('idSubProyecto');
            $nomPlan  = $this->input->post('nomPlan');
            $cantidad = $this->input->post('cantidad');
            $idMes    = $this->input->post('idMes');
            $cantidadCuotas = $this->input->post('cantidadCuotas');
            
            $fecha = $this->m_utils->fechaActual();
            $idUsuario = $this->session->userdata('idPersonaSession');

            if($idUsuario == null || $idUsuario == '') {
                throw new Exception('Se finalizo la sesion, recargue la pagina.');
            }

            if($cantidadCuotas <= 0) {
                throw new Exception('No puede tener cuota 0.');
            }

            $flgTopePlanByCuota = $this->m_plan_admin->getFlgTopePlanCuota($idSubProyecto, $idFase, $cantidad);

            if($flgTopePlanByCuota == 1) {
                throw new Exception('Llego al maximo de planificaciones permitidas, ingresar el plan en otro subproyecto.');
            }

            $arrayPlan = array( 'idSubProyecto' => $idSubProyecto,
                                'idFase'        => $idFase,
                                'nombre_plan'   => $nomPlan,
                                'cantidad'      => $cantidad,
                                'id_mes'        => $idMes,
                                'fecha_reg'     => $fecha,
                                'id_usuario_reg' => $idUsuario);

            $data = $this->m_plan_admin->insertPlanifica($arrayPlan);
            $tablaPlanifica = $this->getDataTablaPlanificacion($idSubProyecto, $idFase);

            $data['tbPlanifica'] = $tablaPlanifica;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function getDataTablaPlanificacion($idSubProyecto, $idFase) {
        $data = $this->m_utils->getDataPlanificacionItem($idSubProyecto, $idFase);

        $html = '<table id="data-table4" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>ACCI&Oacute;N</th>
                            <th>NOMBRE PLAN</th>
                            <th>MES</th>
                            <th>CANTIDAD</th>
                        </tr>
                    </thead>
                    <tbody>';

            foreach ($data as $row) {

            $html .= ' <tr>
                            <td><a title="Editar" data-id_plan="'.$row['id_plan'].'" data-cantidad_plan="'.$row['cantidad'].'" data-nom_plan="'.$row['nombre_plan'].'" onclick="openModalEditPlan($(this));">
                                    <img alt="Editar" height="20px" width="20px" src="public/img/iconos/editar.ico">
                                </a>
                            </td>
                            <td>' . $row['nombre_plan'] . '</td>
                            <td>' . $row['nombreMes'] . '</td>
                            <td>' . $row['cantidad'] . '</td>
                        </tr>';
            }
            $html .= '</tbody>
                </table>';

            return utf8_decode($html);
    }

    function generarOcByPlan() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $id_plan = $this->input->post('id_plan');

            $flgAbrePorOk = $this->m_utils->getFlgPorcAbrePue($id_plan);

            if($flgAbrePorOk == 1) {
                $respSolOc = $this->m_utils->generarSolicitudOC($id_plan);

                if($respSolOc == 3) {
                    $data['error'] = EXIT_ERROR;
                    throw new Exception('NO TIENE PRESUPUESTO PARA CREAR UNA SOLICITUD OC.');
                }
        
                if($respSolOc == 6) {
                    $data['error'] = EXIT_ERROR;
                    throw new Exception('NO TIENE PEP CONFIGURADA.');
                }
        
                if($respSolOc == 2 || $respSolOc == 5) {
                    $data['error'] = EXIT_ERROR;
                    throw new Exception("No se pudo cotizar por error del presupuesto.");
                }

                $data['error'] = EXIT_SUCCESS;
            } else {
                throw new Exception('Tiene itemplans que no paso por el abrepuerta o porcentaje.');
            }
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
	
	function actualizarPlanAsig() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $id_plan       = $this->input->post('id_plan');
            $cantidad_plan = $this->input->post('cantidad_plan');
            $nom_plan      = $this->input->post('nom_plan');
            $idFase        = $this->input->post('idFase');
            $idSubProyecto = $this->input->post('idSubProyecto');

            if($id_plan == null || $id_plan == ''){
                throw new Exception('Error con el plan, comunicarse con el programador.');
            }

            if($cantidad_plan == null || $cantidad_plan == '' || $cantidad_plan == 0){
                throw new Exception('Ingresar Cantidad del plan.');
            }

            if($nom_plan == null || $nom_plan == ''){
                throw new Exception('Ingresar nombre del plan.');
            }

            $rowDataPlan = $this->m_utils->getIdSubProyectoByIdPLan($id_plan);

            if($cantidad_plan < $rowDataPlan['cantidad']) {
                throw new Exception('No esta permitido disminuir la cantidad del plan.');
            }

            $fecha = $this->m_utils->fechaActual();
            $idUsuario = $this->session->userdata('idPersonaSession');

            $arrayData = array  (
                                    'nombre_plan'       => utf8_decode($nom_plan),
                                    'cantidad'          => $cantidad_plan,
                                    'id_usuario_update' => $idUsuario,
                                    'fecha_update'      => $fecha
                                );

            $data = $this->m_plan_admin->update_plan($id_plan, $arrayData);
            $tablaPlanifica = $this->getDataTablaPlanificacion($idSubProyecto, $idFase);

            $data['tbPlanifica'] = $tablaPlanifica;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
}