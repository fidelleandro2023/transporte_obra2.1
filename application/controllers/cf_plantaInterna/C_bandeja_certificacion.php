<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_bandeja_certificacion extends CI_Controller {
    private $_idZonal  = null;
    private $_itemPlan = null;
    function __construct(){
        parent::__construct();
        $this->load->model('mf_ejecucion/M_pendientes');
        $this->load->model('mf_ejecucion/M_generales');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_ejecucion/M_actualizar_porcentaje');   
        $this->load->model('mf_plantaInterna/M_aprobacion_interna');     
        $this->load->helper('url');
        $this->load->library('lib_utils');
        $this->load->library('zip');
        $this->load->library('table');
    }

    function index() {
        $logedUser = $this->session->userdata('usernameSession');
        if($logedUser != null){
            $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
            $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
            $data['tablaBandejaCertificacion'] = $this->getBandejaCertificacion(NULL, NULL, NULL);
            $data['listaEECC'] = $this->m_utils->getAllEECC();
            $data['title'] = 'BANDEJA DE CERTIFICACI&Oacute;N';
            $permisos =  $this->session->userdata('permisosArbolTransporte');
            #$result   = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_PLANTA_INTERNA, ID_PERMISO_HIJO_BANDEJA_CERTIFICACION);
            $result   = $this->lib_utils->getHTMLPermisos($permisos, 247, ID_PERMISO_HIJO_BANDEJA_CERTIFICACION, ID_MODULO_ADMINISTRATIVO);
            $data['opciones'] = $result['html'];
            if($result['hasPermiso'] == true){
                $this->load->view('vf_plantaInterna/V_bandeja_certificacion',$data);
            }else{
                redirect('login','refresh');
            }
        }else{
            redirect('login','refresh');
        }
    }
    

    function getBandejaCertificacion($fechaIn, $fechaFin, $idEecc) {
        $arrayData = $this->m_utils->getDataPlantaInterna(ID_ESTADO_TERMINADO, ID_TIPO_PLANTA_INTERNA, $fechaIn, $fechaFin, $idEecc);
        $btnTerminar = null;
        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr style="color: white ; background-color: #3b5998" width="10%">
                            <th>ItemPlan</th>
                            <th>Proyecto</th>
                            <th>SubProyecto</th>
                            <th>fecha Liquidaci&oacute;n</th>
                            <th>fecha Validaci&oacute;n</th> 
                            <th>Empresa Colaboradora</th>
                            <th>Zonal</th>
                            <th>Costo MO</th>
                            <th>Costo MAT</th>
                            <th>Costo Total</th> 
                            <th>MDF</th>
                            <th>Usuario Validado</th>
                            <th>Usuario Registro Itemplan</th>
                        </tr>
                    </thead>                    
                    <tbody>';
    foreach($arrayData as $row) {        
        //$botonZipEvidencia = ' <a data-toggle="tooltip" data-trigger="hover" data-original-title="descarga zip de las evidencias" data-item_plan="'.$row->itemplan.'" style="cursor:pointer" onclick="zipItemPlan($(this));"><i class="zmdi zmdi-hc-2x zmdi zmdi-download"></i></a>';
        
        $html.= '<tr>
                    <td>'.$row->itemplan.'</td> 
                    <td>'.$row->proyectoDesc.'</td>
                    <td>'.$row->subProyectoDesc.'</td>                
                    <td>'.$row->fechaPreLiquidacion.'</td>
                    <td>'.$row->fechaEjecucion.'</td>
                    <td>'.$row->empresaColabDesc.'</td>
                    <td>'.$row->zonalDesc.'</td>
                    <td>'.$row->costo_mo.'</td>
                    <td>'.$row->costo_mat.'</td>
                    <td>'.$row->total.'</td>
                    <td>'.$row->codigo.'</td>
                    <td>'.$row->usuarioTerm.'</td>
                    <td>'.$row->usuarioRegis.'</td>
                </tr>';
    }
        $html .='   </tbody>
            </table>';

    return utf8_decode($html);
    }

    function filtrarCertificacion() {
        $fechaIn  = $this->input->post('fechaIn');
        $fechaFin = $this->input->post('fechaFin');
        $idEecc   = $this->input->post('idEecc');
       
        $fechaIn  = ($fechaIn != '')  ? $fechaIn  : NULL;
        $fechaFin = ($fechaFin != '') ? $fechaFin : NULL;
        $idEecc   = ($idEecc != 0)   ? $idEecc   : NULL;
        
        $tabla = $this->getBandejaCertificacion($fechaIn, $fechaFin, $idEecc);

        $data['tablaBandejaCertificacion'] = $tabla;

        echo json_encode(array_map('utf8_encode', $data));
    }
}