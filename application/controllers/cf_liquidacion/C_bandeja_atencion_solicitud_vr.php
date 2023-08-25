<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_bandeja_atencion_solicitud_vr extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_liquidacion/m_solicitud_Vr');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

	function index() {  	   
        $logedUser = $this->session->userdata('usernameSession');
        $idEcc     = $this->session->userdata('eeccSession');
	    if($logedUser != null){
            $data['nombreUsuario'] =  $this->session->userdata('usernameSession');	
            $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');	               
            $permisos =  $this->session->userdata('permisosArbol');
            #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_GESTION_VR, ID_PERMISO_HIJO_BANDEJA_ATENCION_SOLICITUD_VR);
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_NUEVO_MODELO_GESTION_VR, ID_PERMISO_HIJO_BANDEJA_ATENCION_SOLICITUD_VR, ID_MODULO_PAQUETIZADO);
            $data['title'] = 'ATENCI&Oacute;N DE SOLICITUD VR';
            $data['cmbJefatura'] = $this->m_utils->getJefaturaSapCmb();
            $data['listaEECC']   = $this->m_utils->getAllEECC();
            $data['listafase'] = $this->m_utils->getAllFase();
            $data['cmbTipoSolicitud'] = $this->m_utils->getTipoSolicitud();
            $data['tablaBandejaSolicitud'] = $this->getBandejaSolicitudVr(null);
            $data['opciones'] = $result['html'];
            $this->load->view('vf_liquidacion/v_bandeja_atencion_solicitud_vr',$data);
	   }else{
	       redirect('login','refresh');
	   }
    }

    function getBandejaSolicitudVr($arrayData) {
        //$arrayData = $this->m_solicitud_Vr->getBandejaSolicitudVr($itemplan, $idJefatura, $idEmpresaColab, $idTipoSolicitud, $ptr, $idFase);
        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>Acci&oacute;n</th>
                            <th>Itemplan</th>
                            <th>C&oacute;digo</th>      
                            <th>vr</th>   
							<th>Vr Robot</th>
                            <th>Jefatura</th>
                            <th>EECC</th>
                            <th>FASE</th>
                            <th>Tipo</th>
                            <th>Fecha Atenci&oacute;n</th>
                            <th>Usuario Validaci&oacute;n</th>
                            <th>Estado</th>                          
                        </tr>
                    </thead>                    
                    <tbody>';
        if($arrayData!=null){
                foreach($arrayData as $row){
                    $style = null;
                    
                    if($row->flgEstadoItemplan == FLG_PARCIALMENTE_NIVEL_ITEMPLAN_VR) {
                        $estado = 'Atenci&oacute;n Parcial';
                        $style = "#FFE033";
                    } else if($row->flgEstadoItemplan == FLG_PENDIENTE_NIVEL_ITEMPLAN_VR) {
                        $estado = 'Atenci&oacute;n Pendiente';
                    } else if($row->flgEstadoItemplan == FLG_RECHAZADO_NIVEL_ITEMPLAN_VR) {
                        $style = '#F6A5A5';
                        $estado = 'Atenci&oacute;n Rechazada';
                    } else if($row->flgEstadoItemplan == FLG_VALIDACION_TOTAL_NIVEL_ITEMPLAN_VR) {
                        $style = '#8CE857';
                        $estado = 'Atenci&oacute;n Total';
                    }
                    
        
                    $btnCheck = '<a data-vr="'.$row->vr.'" data-codigo="'.$row->codigo.'" data-itemplan ="'.$row->itemplan.'" data-ptr="'.$row->ptr.'" title="editar PTR"  onclick="getMaterialModal($(this))"><i class="zmdi zmdi-hc-1x zmdi-wrench"></i></a>';
                    $html .='   <tr  style="background:'.$style.'">
                                    <td>'.$btnCheck.'</td>
                                    <td>'.$row->itemplan.'</td>
                                    <td>'.$row->codigo.'</td>
                                    <td>'.$row->vr.'</td>
									<td>'.$row->vr_robot.'</td>
                                    <th>'.$row->jefaturaDesc.'</th>
                                    <th>'.$row->empresacolabDesc.'</th>
                                    <th>'.$row->faseDesc.'</th>	
                                    <th>'.$row->tipoSolicitudItemplan.'</th>		
                                    <th>'.$row->fecha_atencion.'</th>    
                                    <th>'.$row->nombreUsuario.'</th> 
                                    <th>'.$estado.'</th>                              
                                </tr>';
                        }   
            }
            $html .='</tbody>
            </table>';
            return utf8_decode($html);
    }

    function getMaterialModal() {
        $itemplan = $this->input->post('itemplan');
        $ptr      = $this->input->post('ptr');
        $codigo   = $this->input->post('codigo');
        $vr       = $this->input->post('vr');
        $tabla = $this->getTablaMaterial($itemplan, $ptr, $codigo, $vr);
        $data['tablaCheck'] = $tabla;
        echo json_encode(array_map('utf8_encode', $data));
    }

    function getTablaMaterial($itemplan, $ptr, $codigo, $vr){
        $arrayData = $this->m_solicitud_Vr->getDetalleMaterialesVR($itemplan, $ptr, $codigo, $vr);

        $html = '<table id="consultaModal" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>PTR</th>   
                            <th>C&oacute;digo</th>   
                            <th>C&oacute;digo Material</th>  
                            <th>Material</th>
                            <th>Cantidad Solicitud</th>
                            <th>Cantidad Final</th> 
                            <th>Tipo Solicitud</th>   
                            <th>Descripci&oacute;n</th>   
                            <th>Validado</th>                   
                        </tr>
                    </thead>                    
                    <tbody>';
        $cont = 0;            
        foreach($arrayData as $row){
            $cont++;
            $validado   = ($row->flg_estado == 1) ? 'VALIDADO' : 'NO SE VALIDO';
            $inputComen = ($row->comentario != null || $row->comentario != '') ? 'VALIDADO' : 'NO SE VALIDO';
            $disabled = 'disabled';
            // $btnDescrip = null;
            // if($checked == null) {
                $textComentario = '<textarea id="descripcion_'.$cont.'" data-id_solicitud_vale="'.$row->idSolicitudValeReserva.'" data-id_textarea="descripcion_'.$cont.'" style="height:80px" '.$disabled.'>'.$row->comentario.'</textarea>';
            // }
            $btnCheck = '<a data-itemplan ="'.$row->itemplan.'" title="editar PTR"  onclick="getMaterialModal(this)"><i class="zmdi zmdi-hc-2x zmdi-border-color"></i></a>';
            $html .='   <tr>
                            <td>'.$row->ptr.'</td>
                            <td>'.$row->codigo.'</td>
                            <td>'.$row->material.'</td>
                            <td>'.strtolower($row->textoBreveDesc).'</td>
                            <td>'.$row->cantidad.'</td>
                            <td>'.$row->cantidadFin.'</td>
                            <td>'.$row->tipoSolicitudDesc.'</td>
                            <td>'.$textComentario.'</td>
                            <td>'.$row->estadoMaterial.'</td>                     
                        </tr>';
                        }
            $html .='</tbody>
            </table>';
            return utf8_decode($html);
    }

    function filtrarBandejaConsultaSolicitudVr() {
        $idJefatura      = ($this->input->post('idJefatura')        == '' ? null : $this->input->post('idJefatura'));
        $idEmpresaColab  = ($this->input->post('idEmpresaColab')    == '' ? null : $this->input->post('idEmpresaColab'));
        $idTipoSolicitud = ($this->input->post('idTipoSolicitud')   == '' ? null : $this->input->post('idTipoSolicitud') );
        $idFase          = ($this->input->post('idFase')            == '' ? null : $this->input->post('idFase') );
        $tipoAtencion    = ($this->input->post('tipoAtencion')      == '' ? null : $this->input->post('tipoAtencion') );
        $itemplan        = ($this->input->post('itemplan')          == '' ? null : $this->input->post('itemplan') );
        if( $idJefatura == null     && $idEmpresaColab == null && $idTipoSolicitud == null &&
            $idFase == null && $tipoAtencion == null && $itemplan == null){
            $tabla = $this->getBandejaSolicitudVr(null);
        }else{
            $tabla = $this->getBandejaSolicitudVr($this->m_solicitud_Vr->getBandejaSolicitudVr($itemplan, $idJefatura, $idEmpresaColab, $idTipoSolicitud, $idFase, $tipoAtencion));
        }
        $data['tablaBandejaSolicitud'] = $tabla;
        echo json_encode(array_map('utf8_encode', $data));
    }
}