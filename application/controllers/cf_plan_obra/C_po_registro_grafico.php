<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 *
 *
 */
class C_po_registro_grafico extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_plan_obra/m_consulta');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_plan_obra/m_po_registro_grafico');
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
            #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_PLAN_DE_OBRA, ID_PERMISO_HIJO_PO_REG_GRAFICOS);
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_DISENO_ADMINISTRATIVO, ID_PERMISO_HIJO_PO_REG_GRAFICOS, ID_MODULO_ADMINISTRATIVO);
            $data['opciones'] = $result['html'];

            $this->load->view('vf_plan_obra/v_po_registro_grafico', $data);
        } else {
            redirect('login', 'refresh');
        }

    }

    function getTablaPartidasGrafico() {
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
        $arrayDataTipoRecurso = $this->m_po_registro_grafico->getDataDetalle($itemplan);

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
                                        <td><input id="cantidad_'.$cont.'" type="text" class="form-control" onchange="getDataInsert('.$row['idActividad'].','.$row['baremo'].','.$row['costo'].', '.$cont.')"/></td>
                                        <td><input id="total_'.$cont.'" class="form-control" disabled/>
                                    </tr>';   
                        }
                    '</tbody>
                </table>';
        return $html;
    }

    function generarPOGraf() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            $itemplan      = $this->input->post('itemplan');
            $arrayPartidas = $this->input->post('arrayPartidas');
            $idUsuario = $this->session->userdata('idPersonaSession');
            
            $count = $this->m_utils->countPoExist($itemplan, 1, 40);
            if($count > 0) {
                throw new Exception("Error ya tiene una PO.");
            }
            
            if($idUsuario == null || $idUsuario == '') {
                throw new Exception("Error su sesi&oacute;n a caducado, cargar nuevamente la p&aacute;gina.");
            }

            if($itemplan == null || $itemplan == '') {
                throw new Exception("Error Itemplan null");
            }

            if(count($arrayPartidas) == 0) {
                throw new Exception("Error debe tener partida con sus respectivas cantidades");
            }
            $codigoPO = $this->m_utils->getCodigoPO($itemplan);
            $idEmp = $this->m_utils->getEmpresaColabCvByInt($itemplan);
            
            if($idEmp['idEmpresaColabDiseno'] == null) {
                $idEmpresaColab = $idEmp['idEmpresaColab'];
            } else {
                $idEmpresaColab = $idEmp['idEmpresaColabDiseno'];
            }
            $idSubProyectoEstacion = $this->m_po_registro_grafico->getIdSubProyectoEstacionGraf($itemplan);
            
            if($idSubProyectoEstacion == null || $idSubProyectoEstacion == '') {
                throw new Exception("Error idsubProyectoEstacion NULL");
            }

            $totalFinal = 0;
            foreach($arrayPartidas as $row) {
                $detalle = $this->m_po_registro_grafico->getDataDetalle($itemplan, $row['idPartida']);
                $totalPartida = $detalle['baremo'] * $row['cantidad'] * $detalle['costo'];
                $arrayDetallePO = array();
                $arrayDetalle['codigo_po']      = $codigoPO;
                $arrayDetalle['idPartida']      = $row['idPartida'];
                $arrayDetalle['idPrecioDiseno'] = $detalle['idPrecioDiseno'];
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
                'estado_po'     => 1, 
                'idEstacion'    => 1,
                'from'          => FROM_DISENIO,
                'costo_total'   => $totalFinal,
                'idUsuario'     => $idUsuario,
                'fechaRegistro' => $this->fechaActual(),
                'estado_asig_grafo' => 0,
                'flg_tipo_area'     => 2,
                'id_eecc_reg'        => $idEmpresaColab
            );
            
            $arrayLogPO = array(
                                'codigo_po'         =>  $codigoPO,
                                'itemplan'          =>  $itemplan,
                                'idUsuario'         =>  $idUsuario,
                                'fecha_registro'    =>  $this->fechaActual(),
                                'idPoestado'        =>  1,
                                'controlador'       =>  'CREACION PO REGISTRO GRAFICO'
                            );

            $arrayDetalleplan = array(
                                        'itemPlan' =>  $itemplan,
                                        'poCod'    => $codigoPO,
                                        'idSubProyectoEstacion' =>  $idSubProyectoEstacion
                                    );       
        
            $data = $this->m_po_registro_grafico->registrarPoMOGraf($arrayPO, $arrayLogPO, $arrayDetalleplan, $arrayDetallePO);
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