<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_en_certificacion extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_certificacion/m_creacion_oc');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index(){
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
	           log_message('error', '--->');
	           $itemplan = (isset($_GET['itemplan']) ? $_GET['itemplan'] : '');
               $data['tablaSiom']     = $this->getTablaHojaGestion($this->m_creacion_oc->getBandejaSolOC('',$itemplan,''));               
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
               $permisos =  $this->session->userdata('permisosArbol');
        	  // $result = $this->lib_utils->getHTMLPermisos($permisos, 250, ID_PERMISO_HIJO_GESTIONAR_ORDEN_COMPRA, ID_MODULO_ADMINISTRATIVO);
        	   $this->load->view('vf_pqt_gestion_obra_en_certificacion/v_en_certificacion',$data);
        	   /*$data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	         $this->load->view('vf_certificacion/v_creacion_oc',$data);
        	   }else{
        	       redirect('login','refresh');
        	   }*/
    	 }else{
        	 redirect('login','refresh');
	    }
    }

    function getTablaHojaGestion($listaHojaGestion) {
        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th></th>
                            <th>Solicitud</th>
							<th>Transaccion</th>
							<th>Proyecto</th>
							<th>Subproyecto</th>
                            <th>EECC</th>
							<th># Itemplan</th>
							<th>Costo Total</th>
                            <th>Plan</th>
							<th>PEP 1</th>
							<th>PEP 2</th>
							<th>Fecha creacion</th>
                            <th>Usua Valida</th>
                            <th>Fecha Valida</th>
							<th>Cesta</th>
							<th>Orden Compra</th>
							<th>Cod. Certificacion</th>
                            <th>Estado</th>
							<th>Fecha Cancelado</th>
							<th>Situacion</th>
                        </tr>
                    </thead>
                    <tbody>';
        if($listaHojaGestion != null){
            foreach($listaHojaGestion as $row){
                $btnEnProceso = '';
                if($row->estado == 1){//PENDIENTE
                    /*
                    if($row->tipo_solicitud == 1){
                        $btnEnProceso = '<a data-hg="'.$row->id.'" data-hgtxt="'.$row->codigo_solicitud.'" href="reSolOc?sol='.$row->codigo_solicitud.'"><i title="buscar" style="color:#A4A4A4" class="zmdi zmdi-hc-2x zmdi-time-restore-setting"></i></a>';
                    }else if($row->tipo_solicitud == 2){
                        $btnEnProceso = '<a data-hg="'.$row->id.'" data-hgtxt="'.$row->codigo_solicitud.'" onclick="validarEdicionOc(this)" ><img alt="Editar" height="20px" width="20px" src="public/img/iconos/circle-check-128.png"></a>';
                    }else if($row->tipo_solicitud == 3) {
                        $btnEnProceso = '<a data-hg="'.$row->id.'" data-codigo_sol="'.$row->codigo_solicitud.'" onclick="openModalCertificacion($(this))">
												<i title="Ingresar Codigo Cert." style="color:#A4A4A4" class="zmdi zmdi-hc-2x zmdi-money"></i>
											</a>';
                    } else if($row->tipo_solicitud == 4) {
                        $btnEnProceso = '<a data-hg="'.$row->id.'" data-codigo_sol="'.$row->codigo_solicitud.'" onclick="validarAnulacionOc($(this))">
												<i title="Validar OC" style="color:#A4A4A4" class="zmdi zmdi-hc-2x zmdi-case-check"></i>
											</a>';
                    }
                    */
                }else if($row->estado == 2){//ATENDIDO
                    if($row->path_oc!=null){
                        $btnEnProceso = '<a href="'.$row->path_oc.'" download=""><i class="zmdi zmdi-hc-2x zmdi-download"></i></a>';
                    }else{
                        $btnEnProceso='S/E';
                    }
                }else if($row->estado == 3){//CANCELADO
                    if($row->path_oc!=null){
                        $btnEnProceso = '<a href="'.$row->path_oc.'" download=""><i class="zmdi zmdi-hc-2x zmdi-download"></i></a>';
                    }else{
                        $btnEnProceso='S/E';
                    }
                }
                $html .=' <tr>
                            <th style="width:7%">
                               <a data-hg="'.$row->codigo_solicitud.' "data-ts="'.$row->tipo_solicitud.'" onclick="getPtrByHojaGestion(this)"><i title="buscar" style="color:#A4A4A4" class="zmdi zmdi-hc-2x zmdi-search"></i></a>
                                 '.$btnEnProceso.'
                            </th>
							<td>'.$row->codigo_solicitud.'</td>
							<td>'.$row->tipoSolicitud.'</td>
							<td>'.$row->proyectoDesc.'</td>
							<td>'.$row->subProyectoDesc.'</td>
                            <td>'.$row->empresaColabDesc.'</td>
							<td>'.$row->numItemplan.'</td>
							<td>'.$row->costo_total.'</td>
                            <td>'.$row->plan.'</td>
							<td>'.$row->pep1.'</td>
							<td>'.$row->pep2.'</td>
                            <td>'.$row->fecha_creacion.'</td>
                            <td>'.$row->nombreCompleto.'</td>
							<td>'.$row->fecha_valida.'</td>
							<td>'.$row->cesta.'</td>
							<td>'.$row->orden_compra.'</td>
							<td>'.$row->codigo_certificacion.'</td>
							<td>'.$row->estado_sol.'</td>
							<td>'.$row->fecha_cancelacion.'</td>
							<td>'.$row->estatus_solicitud.'</td>
                        </tr>';
            }
        }
        $html .='</tbody>
                </table>';
    
        return $html;
    }
    
}