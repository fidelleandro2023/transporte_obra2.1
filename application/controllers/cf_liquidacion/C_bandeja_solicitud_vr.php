<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_bandeja_solicitud_vr extends CI_Controller {

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
            #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_GESTION_VR, ID_PERMISO_HIJO_BANDEJA_SOLICITUD_VR);
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_NUEVO_MODELO_GESTION_VR, ID_PERMISO_HIJO_BANDEJA_SOLICITUD_VR, ID_MODULO_PAQUETIZADO);
            $data['title'] = 'BANDEJA SOLICITUD VR';
            $data['cmbJefatura'] = $this->m_utils->getJefaturaSapCmb();
            $data['listaEECC']   = $this->m_utils->getAllEECC();
            $data['listafase'] = $this->m_utils->getAllFase();
            $data['cmbTipoSolicitud'] = $this->m_utils->getTipoSolicitud();
            $data['tablaBandejaSolicitud'] = $this->getBandejaSolicitudVr(null);
            $data['opciones'] = $result['html'];
            $this->load->view('vf_liquidacion/v_bandeja_solicitud_vr',$data);
	   }else{
	       redirect('login','refresh');
	   }
    }

    function getBandejaSolicitudVr($arrayData) {
       // $arrayData  =   $this->m_solicitud_Vr->getBandejaSolicitudVr($itemplan, $idJefatura, $idEmpresaColab, $idTipoSolicitud, $ptr, $idFase);
        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>Acci&oacute;n</th>
                            <th >ptr</th>  
                            <th>Itemplan</th>  
                            <th>C&oacute;digo</th>
                            <th>Vr</th>
							<th>Vr Robot</th>
                            <th>Jefatura</th>
                            <th>EECC</th>
                            <th>FASE</th>
                            <th>Tipo Solicitud</th>
                            <th>Tiempo Atenci&oacute;n (hor:Min:Sec)</th>
                            <th>Usuario Validaci&oacute;n</th>
                            <th>Estado</th>
							<th>Pep1</th>
							<th>Pep2</th>
							<th>Grafo</th>
							<th>Proyecto</th>
							<th>Subproyecto</th>							
                        </tr>
                    </thead>                    
                    <tbody>';
            if($arrayData != null){
                foreach($arrayData as $row){
                    $style = '';  
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
                    
                    $btnCheck = '<a data-vr="'.$row->vr.'" data-codigo="'.$row->codigo.'" data-itemplan ="'.$row->itemplan.'" data-ptr ="'.$row->ptr.'" title="editar PTR"  onclick="openModalCheck($(this))"><i class="zmdi zmdi-hc-2x zmdi-border-color"></i></a>';
                    $html .='   <tr style="background:'.$style.'">
                                    <td>'.$btnCheck.'</td>
                                    <td>'.$row->ptr.'</td>
                                    <td>'.$row->itemplan.'</td>	
                                    <td>'.$row->codigo.'</td>
                                    <td>'.$row->vr.'</td>
									<td>'.$row->vr_robot.'</td>
                                    <th>'.$row->jefaturaDesc.'</th>
                                    <th>'.$row->empresacolabDesc.'</th>	
                                    <th>'.$row->faseDesc.'</th>	
                                    <th>'.$row->tipoSolicitudItemplan.'</th>
                                    <th>'.$row->tiempoAtencionSVr.'</th>  
                                    <th>'.$row->nombreUsuario.'</th> 
                                    <th>'.$estado.'</th>
									<th>'.$row->pep1.'</th>
									<th>'.$row->pep2.'</th>
									<th>'.$row->grafo.'</th>
									<th>'.$row->proyectoDesc.'</th>
									<th>'.$row->subProyectoDesc.'</th>									
                                </tr>';
                }
            }
            $html .='</tbody>
            </table>';
            return utf8_decode($html);
    }

    function getModalCheck() {
        $itemplan = $this->input->post('itemplan');
        $ptr      = $this->input->post('ptr');
        $codigo   = $this->input->post('codigo');
        $vr       = $this->input->post('vr');
        $tabla = $this->getTablaCheck($itemplan, $ptr, $codigo, $vr);

        $data['tablaCheck'] = $tabla;

        echo json_encode(array_map('utf8_encode', $data));
    }

    function getTablaCheck($itemplan, $ptr, $codigo, $vr){
        $arrayData = $this->m_solicitud_Vr->getDetalleMaterialesVR($itemplan, $ptr, $codigo, $vr);

        $html = '<table id="checkModal" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th style="width: 10%;">Check RPA</th> 
							<th style="width: 10%;">PTR</th> 
                            <th style="width: 10%;">C&oacute;digo</th> 
                            <th style="width: 10%;">C&oacute;digo Material</th>  
                            <th style="width: 20%;">Material</th> 
                            <th style="width: 5%;">Cantidad</th>
                            <th style="width: 10%;">Tipo Solicitud</th> 
                            <th style="width: 10%;">VR</th>     
                            <th style="width: 20%;">Rechazar</th>   
                            <th style="width: 5%;">Validar</th>                   
                        </tr>
                    </thead>                    
                    <tbody>';
        $cont = 0;            
        foreach($arrayData as $row){
            $cont++;
            $checked  = ($row->flg_estado == 1) ? 'checked' : null;
            $disabled = ($row->flg_estado == 1 || $row->comentario != null) ? 'disabled' : null;
            $btnCheckRpa = null;
            if(($row->idTipoSolicitud == 1 || $row->idTipoSolicitud == 5) && $row->send_rpa == 0) {
                $btnCheckRpa = '<i style="color:#A4A4A4;cursor:pointer" class="zmdi zmdi-hc-2x zmdi-check-circle-u"
                                 title="" data-itemplan="'.$row->itemplan.'" data-ptr="'.$row->ptr.'" 
                                 data-codigo_solicitud="'.$row->codigo.'" data-material="'.$row->material.'" 
                                 onclick="openModalAlertRpa($(this))"></i>';
            }    
			
			$textComentario = '<textarea id="descripcion_'.$cont.'" data-id_solicitud_vale="'.$row->idSolicitudValeReserva.'" data-cont="'.$cont.'" data-id_textarea="descripcion_'.$cont.'" onchange="getData($(this));" style="width: 100%;" '.$disabled.'>'.$row->comentario.'</textarea>';
            $btnCheck = '<adata-itemplan ="'.$row->itemplan.'" title="editar PTR"  onclick="openModalCheck(this)"><i class="zmdi zmdi-hc-2x zmdi-border-color"></i></a>';
            
			$idUsuario = $this->session->userdata('idPersonaSession');
			
			if($idUsuario == 3 || $idUsuario == 5) {
				$checkValid = '<input type="checkbox" id="check_'.$cont.'" data-flg_adicion="'.$row->flg_adicion.'"
                                data-id_solicitud_vale="'.$row->idSolicitudValeReserva.'" data-cont="'.$cont.'" 
                                data-id_textarea="descripcion_'.$cont.'"
                                data-id_material="'.$row->material.'"
                                data-cantidad_fin="'.$row->cantidadFin.'" onchange="getData($(this), 1)" '.$checked.' '.$disabled.'>';

			} else {
				$checkValid = NULL;
			}
						
			$html .='   <tr id="'.$cont.'">
							<td>'.$btnCheckRpa.'</td>
                            <td>'.$row->ptr.'</td>
                            <td>'.$row->codigo.'</td>
                            <td>'.$row->material.'</td>
                            <td>'.strtolower($row->textoBreveDesc).'</td>
                            <td>'.$row->cantidad.'</td>
                            <th>'.$row->tipoSolicitudDesc.'</th>
                            <th>'.$row->vr.'</th>
                            <td>'.$textComentario.'</td>
                            <td>'.$checkValid.'</td>                     
                        </tr>';
                        }
            $html .='</tbody>
            </table>';
            return utf8_decode($html);
    }

    function ingresarFlgDevolucion() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            $arrayLogVr = array();
            $array      = array();
            $arrayDetallePo = array();
            $arrayUpdateSolicitud = array();
            $updateDetallePo = array();

            $arrayData = $this->input->post('arrayData');
            $ptr       = $this->input->post('ptr');
            $itemplan  = $this->input->post('itemplan');
            
            $this->db->trans_begin();
            // $arrayLogVr = $arrayData;
            $arrayPtr = explode('-', $ptr);

            if($arrayPtr[0] == '2019' || $arrayPtr[0] == '2020') {
                foreach($arrayData as $row) {
                    $row['fecha_atencion'] = $this->fechaActual();
                    unset($row['flg_adicion']);
                    unset($row['id_material']);
                    unset($row['cantidad_fin']);
                    array_push($array, $row);
                }

                foreach($arrayData as $row) {
                    $row['fecha_registro'] = $this->fechaActual();
                    $row['idUsuario']      = $this->session->userdata('idPersonaSession');
                    $row['ptr']            = $ptr;
                    $row['itemplan']       = $itemplan;
    
                    if($row['flg_adicion'] == 1) {
                            $arrayDetallePo[] = array (
                                                        'codigo_po'        => $row['ptr'],
                                                        'codigo_material'  => $row['id_material'],
                                                        'cantidad_ingreso' => $row['cantidad_fin'],
                                                        'cantidad_final'   => $row['cantidad_fin'],
                                                    );
                            
                            $arrayUpdateSolicitud[] = array(
                                                                'flg_adicion' => $row['flg_adicion'],
                                                                'itemplan'    => $row['itemplan'],
                                                                'po'         => $row['ptr'],
                                                                'id_material' => $row['id_material']
                                                            );                          
                                              
                    } else {
                        $flgExistPODetalle = $this->m_solicitud_Vr->getExistPo($row['id_material'], $row['ptr']);
                        if($flgExistPODetalle == 1) {
                            $updateDetallePo[] = array (
                                                            'codigo_po'        => $row['ptr'],
                                                            'codigo_material'  => $row['id_material'],
                                                            'cantidad_final'   => $row['cantidad_fin'],
                                                        );
                        }
                    }
                    
                    unset($row['fecha_atencion']);
                    unset($row['flg_adicion']);
                    unset($row['id_material']);
                    unset($row['cantidad_fin']);
                    array_push($arrayLogVr, $row);
                }
                
                $data = $this->m_solicitud_Vr->ingresarDetallePo($arrayDetallePo, $arrayUpdateSolicitud, $updateDetallePo);
 
                if($data['error'] == EXIT_ERROR) {
                    throw new Exception('error NO INGRESO DEVOLUCION1');
                }
                
                
                $data = $this->m_solicitud_Vr->ingresarFlgDevolucion($array, $arrayLogVr);
    
                if($data['error'] == EXIT_ERROR) {
                    throw new Exception('error NO INGRESO DEVOLUCION2');
                }
                
                $data = $this->m_solicitud_Vr->updateTotalPo($ptr);
    
                if($data['error'] == EXIT_ERROR) {
                    throw new Exception($data['msj']);
                }
            } else {
                foreach($arrayData as $row) {
                    $row['fecha_atencion'] = $this->fechaActual();
                    unset($row['flg_adicion']);
                    unset($row['id_material']);
                    unset($row['cantidad_fin']);
                    array_push($array, $row);
                    
                    $row['idUsuario']      = $this->session->userdata('idPersonaSession');
                       $row['fecha_registro'] = $this->fechaActual();
                    $row['idUsuario']      = $this->session->userdata('idPersonaSession');
                    $row['ptr']            = $ptr;
                    $row['itemplan']       = $itemplan;
                    unset($row['fecha_atencion']);
                    unset($row['flg_adicion']);
                    unset($row['id_material']);
                    unset($row['cantidad_fin']);
                    array_push($arrayLogVr, $row);
                }

                $data = $this->m_solicitud_Vr->ingresarFlgDevolucion($array, $arrayLogVr);
    
                if($data['error'] == EXIT_ERROR) {
                    throw new Exception('error NO INGRESO DEVOLUCION');
                }
            }

            
            $this->db->trans_commit();
            $data['tablaBandejaSolicitud'] = $this->getBandejaSolicitudVr(null);
        } catch(Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }


    function filtrarBandejaSolicitudVr() {
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

    function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone','America/Lima');
        setlocale(LC_TIME, "es_ES","esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }
	
	function actualizarFlagRpa() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            $codigo_solicitud = $this->input->post('codigo_solicitud');
            $material         = $this->input->post('material');
            
            $usuario = $this->session->userdata('idPersonaSession');
            $fechaActual = $this->fechaActual();

            $arrayData = array('send_rpa'        => 1,
                               'usua_valida_rpa' => $usuario,
                               'fec_valida_rpa'  => $fechaActual);
            $data = $this->m_solicitud_Vr->updateFlgRobot($codigo_solicitud, $material, $arrayData);
            $tabla = $this->getTablaCheck(NULL, NULL, $codigo_solicitud, NULL);
            
            $data['tablaCheck'] = $tabla;
        }catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
}