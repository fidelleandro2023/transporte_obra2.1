<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_reembolso_licencia extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=UTF-8');
        $this->load->model('mf_plan_obra/m_planobra');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->library('zip');
        $this->load->helper('url');
    }
    
	public function index()
	{  	   
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
        	  
            $data['arrayEstadoFiltro'] = $this->m_utils->getEstadoReembolsoAll();

            $data['tablaAsigGrafo'] = $this->tablaReembolso(null, null);
            $data['nombreUsuario'] =  $this->session->userdata('usernameSession');	
            $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');              
            $permisos =  $this->session->userdata('permisosArbolTransporte');
            $result = $this->lib_utils->getHTMLPermisos($permisos, 54, 331, ID_MODULO_PAQUETIZADO);
            $data['opciones'] = utf8_encode($result['html']);
            if($result['hasPermiso'] == true){
                $this->load->view('vf_certificacion/v_reembolso_licencia',$data);
            }else{
                redirect('login','refresh');
            }
	   }else{
	       redirect('login','refresh');
	   }
    }
        
    function tablaReembolso($itemplan, $estado){
        $data = $this->m_utils->getDataReembolsoByItem($itemplan, 2, $estado);
        $idUsuario = $this->session->userdata('idPersonaSession');
        $html = '<table id="data-table" class="table table-bordered" style="font-size: 10px;">
                    <thead class="thead-default">
                        <tr>                 
                            <th>ACCIÓN</th>                            
                            <th>ITEMPLAN</th>
                            <th>EECC</th>
                            <th>PROYECTO</th>
                            <th>SUBPROYECTO</th>
                            <th>PROMOTOR</th>
                            <th>ZONAL</th>
                            <th>MONTO TOTAL</th>
                            <!--th>ESTADO</th-->
                            <th>CANT. VALID. / TOTAL. CASOS.</th>
                        </tr>
                    </thead>
                   
                    <tbody>';
															                                                   
                foreach($data as $row) {
                    $btnEditaEmpresaColab = null;
                    $btnObs = null;
                    $rechazadoBandjConf = null;
					$btnRechazar = null;
					$btnCambioFlgRobot = null;
                    
					$btnEntidades = '<i style="color:#A4A4A4;cursor:pointer;"
                                        data-itemplan="'.$row['itemplan'].'" class="zmdi zmdi-hc-2x zmdi zmdi-assignment-o" 
                                        title="Ver entidades" onclick="openModalEntidadesReembolso($(this))"></i>';
					
                    $html .=' <tr>                                                         
                                <td>'.$btnEntidades.'</td>
                                <td>'.$row['itemplan'].'</td>
                                <td>'.$row['empresaColabDesc'].'</td>
                                <td>'.$row['proyectoDesc'].'</td>
                                <td>'.$row['subProyectoDesc'].'</td>
                                <td>'.$row['nom_promotor'].'</td>
                                <td>'.$row['zonalDesc'].'</td>
                                <td>'.$row['monto'].'</td>
                                <!--td>'.$row['estado_reembolso'].'</td-->
                                <td style="width:150px">'.$row['count_validado'].' / '.$row['total_casos'].'</td>
                            </tr>';
                    }  
   			  
			 $html .='</tbody>
                </table>';
                    
        return $html;
    }

    function openModalEntidadesReembolso() {
        $data['msj']   = '';
        $data['error'] = EXIT_ERROR;
        try {
            $itemplan = $this->input->post('itemplan');

            if($itemplan == null || $itemplan == '') {
                throw new Exception('Error de itemplan');
            }
            $tablaReembolsoDetalle = $this->tablaReembolsoDetalle($itemplan);
            $dataJsonDetalle = $this->m_utils->getDetalleReembolso($itemplan, 2);
            
            $data['dataJsonDetalle'] = json_encode($dataJsonDetalle);
            $data['tablaDetalleReembolso'] = $tablaReembolsoDetalle;
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

    function tablaReembolsoDetalle($itemplan){
        $data = $this->m_utils->getDetalleReembolso($itemplan, 2);
       
        $checkAll = 1;
        $html = '';

        foreach($data AS $row) {
            $btnEditaEmpresaColab = null;
            $btnObs = null;
            $rechazadoBandjConf = null;
            $btnRechazar = null;
            $btnCambioFlgRobot = null;
            
            $disabled = null;
            $checked  = null;
            if($row['flg_valida_reembolso'] != null) {
                $disabled = 'disabled';
                $checked  = 'checked';
            }

            if($row['idEstadoReembolso'] == 3){
                $checkAll = 0;
            }

            $checkbox = '<input type="checkbox" id="checkBox_'.$row['id_licencia_entidad'].'" data-id_licencia_entidad="'.$row['id_licencia_entidad'].'"
                        data-id_entidad="'.$row['idEntidad'].'" data-monto="'.$row['monto'].'" 
                        id="" '.$disabled.' '.$checked.' onchange="setDataCheck($(this));"/>';
            
            $btnComprobante = '<a href="'.$row['ruta_foto'].'" target="_blank"><i style="color:#A4A4A4;cursor:pointer;"
                                data-itemplan="'.$row['itemplan'].'" class="zmdi zmdi-hc-2x zmdi zmdi-file-text" 
                                title="Ver entidades"></i></a>';            
            $html .=' <tr>                                                         
                        <td>'.$checkbox.'</td>
                        <td>'.$row['nom_promotor'].'</td>
                        <td>'.$row['desc_entidad'].'</td>
                        <td>'.$row['empresaColabDesc'].'</td>
                        <td>'.$row['nro_reembolso'].'</td>
                        <td>'.$row['monto'].'</td>
                        <td style="width:100px"><input class="form-control" data-id_entidad="'.$row['idEntidad'].'" data-id_licencia_entidad="'.$row['id_licencia_entidad'].'"
                            id="text_monto_fin_'.$row['id_licencia_entidad'].'" value="'.$row['monto_fin'].'" onchange="setDataCheck($(this))"></td>
                        <td style="width:100px">'.$btnComprobante.'</td>
                        <td>'.$row['desc_estado_reembolso'].'</td>
                        <td>'.$row['situacion'].'</td>
                    </tr>';
        }
        $chbx = '';
        if($checkAll == 1){
            $chbx = '<input type="checkbox" id="checkBoxAll" onchange="setCheckAll($(this));" />';
        }
        $htmlCabe = '<table id="table_detalle" class="table table-bordered" style="font-size: 10px;">
                        <thead class="thead-default">
                            <tr>                 
                                <th>
                                    '.$chbx.'
                                </th>
                                <th>PROMOTOR</th>                          
                                <th>ENTIDAD</th>
                                <th>EECC</th>
                                <th>NRO VOUCHER</th>
                                <th>MONTO EECC</th>
                                <th>MONTO TDP</th>
                                <th>COMPROB.</th>
                                <th>ESTADO DE EMISIÓN</th>
                                <th>SITUACIÓN PRESUPUESTAL DE EMISIÓN</th>
                            </tr>
                        </thead>
                    
                        <tbody>';
   		$htmlCabe = $htmlCabe.$html.'</tbody>
           </table>';
                    
        return $htmlCabe;
    }

    function validarEntidadReembolso() {
        $data['msj']   = '';
        $data['error'] = EXIT_ERROR;
        try {
            $this->db->trans_begin();
            
            $arrayJson = $this->input->post('arrayDataJson') ? json_decode($this->input->post('arrayDataJson'),true) : array();
            $itemplan  = $this->input->post('itemplan');
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;
            $fechaActual = $this->m_utils->fechaActual();

           
            if(count($arrayJson) == 0) {
                throw new Exception('Debe seleccionar por lo menos una entidad, verificar.');
            }
            if($idUsuario == null) {
                throw new Exception('Su sesión ha expirado, ingrese nuevamente.');
            }
            if($itemplan == null) {
                throw new Exception('No se encuentra el itemplan, verificar.');
            }

            $arrayUpdate = array();
            
            $data = $this->m_utils->updateFlgValidaReembolsoLicencia($arrayJson, $idUsuario, $fechaActual);
            if($data['error'] == EXIT_ERROR) {
                throw new Exception($data['msj']);
            }

            $idEstadoReembolso = $this->m_utils->getEstadoValidaReembolso($itemplan);

            $arrayData = array(
                'idEstadoReembolso' => $idEstadoReembolso
            );
            $data = $this->m_utils->simpleUpdatePlanObra($itemplan, $arrayData);
            if($data['error'] == EXIT_ERROR) {
                throw new Exception($data['msj']);
            }
            $this->db->trans_commit();
            $data['tablaEntidad'] = $this->tablaReembolso(null, null);
        } catch(Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

    function filtrarBandejaReembolsoLicencia() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            $itemplan       = ($this->input->post('item')=='')          ? null : $this->input->post('item');
            $estado         = ($this->input->post('estado') == '')      ? null : $this->input->post('estado');
            $data['tablaBandejaReembolso'] = $this->tablaReembolso($itemplan, $estado);
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }
}