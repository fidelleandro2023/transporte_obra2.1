<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 *
 *
 */
class C_po_registro_cotizacion extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_plan_obra/m_consulta');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_plan_obra/m_po_registro_cotizacion');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    function index() {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {
            $zonas = $this->session->userdata('zonasSession');
            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $permisos = $this->session->userdata('permisosArbol');
            #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_PLAN_DE_OBRA, ID_PERMISO_HIJO_PO_REG_COTIZACION);
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_DISENO_ADMINISTRATIVO, ID_PERMISO_HIJO_PO_REG_COTIZACION, ID_MODULO_ADMINISTRATIVO);
            $data['opciones'] = $result['html'];

            $this->load->view('vf_plan_obra/v_po_registro_cotizacion', $data);
        } else {
            redirect('login', 'refresh');
        }

    }

    function getTablaPartidasCotizacion() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            $itemplan = $this->input->post('itemplan');

            if($itemplan == null || $itemplan == '') {
                throw new Exception('error Itemplan null');
            }
            $data['error'] = EXIT_SUCCESS;
            $data['tbPartidasGraf'] = $this->getTbPartidas($itemplan);
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function getTbPartidas($itemplan) {
        $cont = 0;
        $arrayDataTipoRecurso = $this->m_po_registro_cotizacion->getDataDetalle($itemplan);
        $html = '
                <table id="tbPartidasGraf" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>Nro</th>
                            <th>PARTIDA</th>
                            <th>BAREMO</th>
                            <th>COSTO</th>
                            <th>CANTIDAD</th>
                            <th>TOTAL</th>
                        </tr>
                    </thead>

                    <tbody>';
                        foreach($arrayDataTipoRecurso as $row) {
                            $cont++;
                            $html.='<tr>
                                        <td>'.$cont.'</td>
                                        <td>'.utf8_decode(mb_strtoupper($row['descripcion'])).'</td>
                                        <td>'.$row['baremo'].'</td>
                                        <td>'.$row['costo'].'</td>
                                        <td><input id="cantidad_'.$cont.'" data-baremo="'.$row['baremo'].'" data-costo="'.$row['costo'].'" type="text" class="form-control" onchange="getDataInsert('.$row['idActividad'].', '.$cont.', $(this))"/></td>
                                        <td><input class="form-control" id="total_'.$cont.'" disabled/></td>
                                    </tr>';   
                        }
                    '</tbody>
                </table>';
        return $html;
    }

    function generarPOCotizacion() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            $itemplan      = $this->input->post('itemplan');
            $arrayPartidas = $this->input->post('arrayPartidas');
            $idUsuario = $this->session->userdata('idPersonaSession');
            
            if($idUsuario == null || $idUsuario == '') {
                throw new Exception("Error su sesi&oacute;n a caducado, cargar nuevamente la p&aacute;gina.");
            }
            
            $count = $this->m_utils->countPoExist($itemplan, 1, 46); // estacion: diseño y Area : cotización
            
            if($count > 0) {
                throw new Exception("Error, Ya tiene una PO de cotizaci&oacute;n");
            }

            if($itemplan == null || $itemplan == '') {
                throw new Exception("Error Itemplan null");
            }

            if(count($arrayPartidas) == 0) {
                throw new Exception("Error debe tener partida con sus respectivas cantidades");
            }
            $codigoPO = $this->m_utils->getCodigoPO($itemplan);
            $idSubProyectoEstacion = $this->m_po_registro_cotizacion->getIdSubProyectoEstacionGraf($itemplan);
            
            if($idSubProyectoEstacion == null || $idSubProyectoEstacion == '') {
                throw new Exception("Error idsubProyectoEstacion NULL");
            }

            $totalFinal = 0;
            foreach($arrayPartidas as $row) {
                $detalle = $this->m_po_registro_cotizacion->getDataDetalle($itemplan, $row['idPartida']);
                $idEmpresaColab                 = $detalle['idEmpresaColab'];
                $totalPartida = $detalle['baremo'] * $row['cantidad'] * $detalle['costo'];
                $arrayDetallePO = array();
                $arrayDetalle['codigo_po']      = $codigoPO;
                $arrayDetalle['idPartida']      = $row['idPartida'];
                $arrayDetalle['idPrecioDiseno'] = $detalle['baremo'];
                $arrayDetalle['idEmpresaColab'] = $detalle['idEmpresaColab'];
                $arrayDetalle['idZonal']        = $detalle['idZonal'];
                $arrayDetalle['cantidad']       = $row['cantidad'];
                $arrayDetalle['baremo']         = $detalle['baremo'];
                $arrayDetalle['costo']          = $detalle['costo'];
                $arrayDetalle['total']          = $totalPartida;
                array_push($arrayDetallePO, $arrayDetalle);

                $totalFinal = $totalPartida + $totalFinal;
            }

            $arrayPO = array(
                'itemplan'      => $itemplan,
                'codigo_po'     => $codigoPO,
                'estado_po'     => PO_VALIDADO, 
                'idEstacion'    => 1,
                'from'          => FROM_DISENIO,
                'costo_total'   => $totalFinal,
                'idUsuario'     => $idUsuario,
                'fechaRegistro' => $this->fechaActual(),
                'estado_asig_grafo' => 0,
                'flg_tipo_area'     => 2,
                'fecha_validacion'  => $this->fechaActual(),
                'id_eecc_reg'       => $idEmpresaColab
            );
            
            $arrayLogPO = array(
                                'codigo_po'         =>  $codigoPO,
                                'itemplan'          =>  $itemplan,
                                'idUsuario'         =>  $idUsuario,
                                'fecha_registro'    =>  $this->fechaActual(),
                                'idPoestado'        =>  PO_VALIDADO,
                                'controlador'       =>  'CREACION PO REGISTRO COTIZACION'
                            );

            $arrayDetalleplan = array(
                                        'itemPlan' =>  $itemplan,
                                        'poCod'    => $codigoPO,
                                        'idSubProyectoEstacion' =>  $idSubProyectoEstacion
                                    );       
            
            $arrayUpdateEstado  = array('idEstadoPlan'   => 4,
                                        'fechaEjecucion' => $this->fechaActual());
                                        
            $data = $this->m_po_registro_cotizacion->registrarPoMOGraf($arrayPO, $arrayLogPO, $arrayDetalleplan, $arrayDetallePO, $itemplan, $arrayUpdateEstado, $codigoPO, $idUsuario);
            
            $arrayDataLog = array(
                                    'tabla'            => 'planobra',
                                    'actividad'        => 'Obra Terminada',
                                    'itemplan'         => $itemplan,
                                    'fecha_registro'   => $this->fechaActual(),
                                    'id_usuario'       => $this->session->userdata('idPersonaSession'),
                                    'tipoPlanta'       => 1
                                );
        
            $this->m_utils->registrarLogPlanObra($arrayDataLog);
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function getProvincia() {
        $data['msj']   = null;
        $data['error'] = EXIT_ERROR;
        try {
            $idDepartamento = $this->input->post('idDepartamento');
            
            if($idDepartamento == null || $idDepartamento == '') {
                throw new Exception('seleccionar Departamento');
            }

            $arrayProvincia = $this->m_utils->getProvincia($idDepartamento);
    
            $cmbProvincia = '<option value="">Selec. Departamento</option>';
            foreach($arrayProvincia AS $row) {
                $cmbProvincia .= '<option value="'.$row['idProvincia'].'">'.utf8_decode(mb_strtoupper($row['provinciaDesc'])).'</option>';         
            }
            $data['error'] = EXIT_SUCCESS;
            $data['cmbProvincia'] = $cmbProvincia;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data)); 
    }

    function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone', 'America/Lima');
        setlocale(LC_TIME, "es_ES", "esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }
}