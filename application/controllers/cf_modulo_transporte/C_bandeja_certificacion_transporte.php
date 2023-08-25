<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_bandeja_certificacion_transporte extends CI_Controller {
  
    function __construct(){
        parent::__construct();
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_transporte/m_bandeja_certificacion_transporte');     
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
            $permisos =  $this->session->userdata('permisosArbol');
            #$result   = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_PLANTA_INTERNA, ID_PERMISO_HIJO_BANDEJA_CERTIFICACION_II);
            $result   = $this->lib_utils->getHTMLPermisos($permisos, 316, 317, 8);
			$data['opciones'] = $result['html'];
            if($result['hasPermiso'] == true){
                $this->load->view('vf_modulo_transporte/v_bandeja_certificacion_transporte',$data);
            }else{
                redirect('login','refresh');
            }
        }else{
            redirect('login','refresh');
        }
    }
    

    function getBandejaCertificacion($fechaIn, $fechaFin, $idEecc) {
        $arrayData = $this->m_bandeja_certificacion_transporte->getDataCertificacion(ID_ESTADO_TERMINADO, $fechaIn, $fechaFin, $idEecc);
        $btnTerminar = null;
        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr style="color: white ; background-color: #3b5998" width="10%">
							<th>Zip</th>
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
                            <th>Orden Compra</th>
                            <th>Nro Certificacion</th>
                        </tr>
                    </thead>                    
                    <tbody>';
    foreach($arrayData as $row) {  
	
        $botonZipEvidencia = ' <a data-toggle="tooltip" data-trigger="hover" data-original-title="descarga zip de las evidencias" data-item_plan="'.$row->itemplan.'" style="cursor:pointer" onclick="zipItemPlan($(this));"><i class="zmdi zmdi-hc-2x zmdi zmdi-download"></i></a>';
        
        $html.= '<tr>
					<td>'.$botonZipEvidencia.'</td>
                    <td><a style="color:blue" data-itemplan="'.$row->itemplan.'" onclick="getDetalleItemPlan(this)">'.$row->itemplan.'</a></td> 
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
                    <td>'.$row->pi_oc.'</td>
                    <td>'.$row->pi_nro_cert.'</td>
                </tr>';
    }
        $html .='   </tbody>
            </table>';

    return utf8_decode($html);
    }

    function filtrarCertificacionTransp() {
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
    
    public function getDetTranspIp(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
    
            $itemplan    = $this->input->post('itemplan');           
            $data['tablaDetItemplan'] = $this->getBandejaReporte($itemplan);
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    
    function getBandejaReporte($itemplan) {
        $arrayData = $this->m_bandeja_certificacion_transporte->getPartidasByItemplan($itemplan);
        $btnTerminar = null;
        $html = '<table id="tabla_detalle" class="table table-bordered">
                    <thead class="thead-default">
                        <tr style="color: white ; background-color: #3b5998" width="10%">
                            <th>ItemPlan</th>
                            <th>ptr</th>
                            <th>Proyecto</th>
                            <th>SubProyecto</th>
                            <th>Partida</th>
                            <th>Empresa Colaboradora</th>
                            <th>MDF</th>
                            <th>Fecha Validaci&oacute;n</th>
                            <th>precio</th>
                            <th>Baremo</th>
                            <th>Cantidad</th>
                            <th>Costo Kit</th>
                            <th>Costo MO</th>
                            <th>Costo MAT</th>
                            <th>Costo Total</th>
                        </tr>
                    </thead>
                    <tbody>';
        foreach($arrayData as $row) {
            //$botonZipEvidencia = ' <a data-toggle="tooltip" data-trigger="hover" data-original-title="descarga zip de las evidencias" data-item_plan="'.$row->itemplan.'" style="cursor:pointer" onclick="zipItemPlan($(this));"><i class="zmdi zmdi-hc-2x zmdi zmdi-download"></i></a>';
    
            $html.= '<tr>
                    <td>'.$row->itemplan.'</td>
                    <td>'.$row->ptr.'</td>
                    <td>'.utf8_encode($row->proyectoDesc).'</td>
                    <td>'.utf8_encode($row->subProyectoDesc).'</td>
                    <td>'.utf8_encode($row->partida).'</td>
                    <td>'.$row->empresaColabDesc.'</td>
                    <td>'.$row->mdf.'</td>
                    <td>'.$row->fechaEjecucion.'</td>
                    <td>'.$row->precio.'</td>
                    <td>'.$row->baremo.'</td>
                    <td>'.$row->cantidad.'</td>
                    <td>'.$row->costo_kit.'</td>
                    <td>'.$row->costo_mo.'</td>
                    <td>'.$row->costo_mat.'</td>
                    <td>'.$row->total.'</td>
                </tr>';
        }
        $html .='   </tbody>
            </table>';
    
        return utf8_decode($html);
    }
}