<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * @author CARLOS ZAVALA C.
 * 18/01/2018
 *
 */
class C_bandeja_ejecucion extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_pre_diseno/m_bandeja_ejecucion');
        $this->load->model('mf_pre_diseno/m_bandeja_adjudicacion');
        /*************nuevo**********************/
        $this->load->model('mf_licencias/M_bandeja_itemplan_estacion');
        $this->load->model('mf_plan_obra/m_editar_planobra');
        $this->load->model('mf_liquidacion/m_liquidacion');
        
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->library('zip');
        $this->load->helper('url');
    }
    
	public function index()
	{  	   
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
            $data['cmbProyecto']    = __buildComboProyecto();             
            $data['listaZonal']     = $this->m_utils->getAllZonal();
            $data['listaProy']      = $this->m_utils->getAllProyecto();
            $data['cmbEstacion']    = __buildComboEstacion(1);
            $data['cmbPlanta']      = __buildComboPlanta();
            $data['cmbJefatura']    = __buildComboJefatura();
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_bandeja_ejecucion->getBandejaEjecucion(NULL, NULL, NULL, NULL ,NULL , NULL, $this->session->userdata('eeccSession')));
            $data['nombreUsuario']  =  $this->session->userdata('usernameSession');	
            $data['perfilUsuario']  =  $this->session->userdata('descPerfilSession');	               
            $permisos =  $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, ESTADO_PLAN_DISENO, ID_PERMISO_HIJO_BANDEJA_EJECUCION);
            $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	       $this->load->view('vf_prediseno/v_bandeja_ejecucion',$data);
        	   }else{
        	       redirect('login','refresh');
	           }
	   }else{
	       redirect('login','refresh');
	   }
    }    
      
    public function makeHTLMTablaBandejaAprobMo($listaPTR){  
        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>';                                    
             $html .='      <th>APROBAR</th>
                            <th>Item Plan</th>    
                            <th>Estacion</th>                            
                            <th>Indicador</th> 
                            <th>Proyecto</th>    
                            <th>Sub Proy</th>
                            <th>EECC</th>
                            <th>EECC diseno</th>
                            <th>Fec. Prevista</th>
                            <th>Estado</th>
                            <th>Jefatura</th>
                        </tr>
                    </thead>
                   
                    <tbody>';
	    if($listaPTR!=null){			   		                                        
                foreach($listaPTR->result() as $row){
                    $btnFormulario = '';   
                    if($row->idProyecto == ID_PROYECTO_SISEGOS && $row->hasSisegoPlanObra == 0) {
                        $btnFormulario= '<a data-jefatura="'.$row->jefatura.'" data-item_plan="'.$row->itemPlan.'" data-flg_from="1" onclick="openModalBandejaEjecucion($(this))"style="margin-left: 10%;"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/iconodetalle.png"></a>';
//<input type="button" class="btn-info" value="formulario" data-jefatura="'.$row->jefatura.'" data-item_plan="'.$row->itemPlan.'" data-flg_from="1" onclick="openModalBandejaEjecucion($(this));">';                                        
                    } 
                $html .='<tr id="'.$row->itemPlan.$row->idEstacion.'"> 
                            <td>
<!--<a style="margin-left: -20%;" data-has_file="'.(($row->nomArchivo!=NULL) ? 1 : 0).'" data-esta="'.$row->estacionDesc.'" data-itemplan="'.$row->itemPlan.'"  data-idEstacion="'.$row->idEstacion.'"  onclick="editarAdjudicacion(this)"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/editar.ico"></a>-->'.(($row->nomArchivo!=NULL) ? '<a style="margin-left: 10%;" href="'.base_url().'\\uploads\\ejecucion\\'.$row->itemPlan.'\\'.$row->nomArchivo.'" download="'.$row->itemPlan.'_'.$row->estacionDesc.'" ><img alt="Editar" height="20px" width="20px" src="public/img/iconos/iconpicplus.svg"></a>' : '').
                            $btnFormulario.'<a data-item="'.$row->itemPlan.'" data-id_estacion="'.$row->idEstacion.'" onclick="abrirModalAsignarEntidades(this)" style="margin-left: 10%;"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/check_24016.png"></a></td>
                            <th>'.(($row->idEstadoPlan==ESTADO_PLAN_DISENO) ? '<a href="#"  class="ver_ptr" data-idrow="'.$row->itemPlan.$row->idEstacion.'" data-estacion="'.$row->idEstacion.'">'.$row->itemPlan.'</a>' : $row->itemPlan ).'</th>	
                            <th>'.$row->estacionDesc.'</th>                             
                            <th>'.$row->indicador.'</th>							
                            <th>'.$row->proyectoDesc.'</th>
                            <th>'.$row->subProyectoDesc.'</th>
							<th>'.$row->empresaColabDesc.'</th>
							<th>'.$row->empresaColabDiseno.'</th>						
                            <th>'.$row->fecha_prevista_atencion.'</th>
                            <th>'.$row->estadoPlanDesc.'</th>
                            <th>'.$row->jefatura.'</th>
			             </tr>';
                 }
        }
			 $html .='</tbody>
                </table>';
                    
        return utf8_decode($html);
    }
    
      function filtrarTabla(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{        
            
            $idEstacion = $this->input->post('idEstacion');
            $idTipoPlan = $this->input->post('idTipoPlan');
            $jefatura   = $this->input->post('jefatura');
            $idProyecto = $this->input->post('idProyecto');
            $SubProy    = $this->input->post('subProy');
            $fecha      = $this->input->post('fecha');
           
            $idEstacion = ($idEstacion == '') ? NULL : $idEstacion;        
            $idTipoPlan = ($idTipoPlan == '') ? NULL : $idTipoPlan;
            $jefatura   = ($jefatura == '')   ? NULL : $jefatura;
            $idProyecto = ($idProyecto == '') ? NULL : $idProyecto;
            $SubProy    = ($SubProy == '')    ? NULL : $SubProy;
            $fecha      = ($fecha == '')      ? NULL : $fecha;
   
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_bandeja_ejecucion->getBandejaEjecucion($idEstacion, $idTipoPlan, $jefatura, $SubProy, $idProyecto, $fecha, $this->session->userdata('eeccSession')));
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }   
    
    public function validarAprobarDiseno() {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $itemplan   = $this->input->post('itemplan');
            $idEstacion = $this->input->post('idEstacion');
            $reject = false;
           
            $info = $this->m_bandeja_ejecucion->getCountPtrsEstacionesByItemplan($itemplan);
            if($info != null){
                if($idEstacion == ID_ESTACION_COAXIAL){
                    if($info['conCoax']   ==   0){
                        $reject = true;
                    }
                }else if($idEstacion == ID_ESTACION_FO){
                    if($info['conFo']   ==   0){
                        if($info['idProyecto'] == ID_PROYECTO_SISEGOS){
                            if($info['conMul']  ==  0 && $info['conUM'] ==  0){
                                $reject = true;
                           }
                        }
                    }
                }
            }else if($info == null){
                $reject = true;
            }
            
            if($reject) {
                throw new Exception('NO SE PUEDE APROBAR, AGREGUE PTR DE LA MISMA ESTACI&Oacute;');
            }else{
                if($info['idProyecto'] == ID_PROYECTO_SISEGOS){
                    $hasFormSisego = $this->m_utils->hasSisegoPlanObra($itemplan, ID_TIPO_OBRA_FROM_DISENIO);               
                    // if($hasFormSisego   ==  0){
                    //     throw new Exception('NO SE PUEDE APROBAR, AGREGUE INFROMACION EN EL FORMULARIO DISEÃ‘O DE OBRA');
                    // }
                }
            }
            $data['error'] = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));     
    }

    /***************************** antiguo 1708018
    public function ejecutarDiseno(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
                        
            $itemplan   = $this->input->post('item');           
            $idEstacion = $this->input->post('idEstacion');
            
            $idEstaFil  = $this->input->post('idEstacionFil');
            $idTipoPlan = $this->input->post('idTipoPlan');
            $jefatura   = $this->input->post('jefatura');
            $idProyecto = $this->input->post('idProyecto');
            $SubProy    = $this->input->post('subProy');
            $fecha      = $this->input->post('fecha');
            
            $idEstaFil  = ($idEstaFil == '') ? NULL : $idEstaFil;
            $idTipoPlan = ($idTipoPlan == '') ? NULL : $idTipoPlan;
            $jefatura   = ($jefatura == '')   ? NULL : $jefatura;
            $idProyecto = ($idProyecto == '') ? NULL : $idProyecto;
            $SubProy    = ($SubProy == '')    ? NULL : $SubProy;
            $fecha      = ($fecha == '')      ? NULL : $fecha;
            
           

            $idPersonaSession = $this->session->userdata('idPersonaSession');
            $arrayIdEntidades = $this->input->post('arrayIdEntidades');
            $flgExpediente = $this->input->post('flgExpediente');
            $flgDisenoSirope = $this->input->post('flgDisenoSirope');
            $arrayInsert = array();
            

            if (!is_array($arrayIdEntidades) && $arrayIdEntidades == null && count($arrayIdEntidades) == 0) {
                throw new Exception('Debe asignar almenos una entidad');
            }
            $cadIds = '';
            foreach ($arrayIdEntidades as $row) {

                if ($row[1] == 1) { //GUARDO TODAS LOS ID DE LAS ENTIDADES QUE NO SE DEBEN ELIMINAR
                    $cadIds .= $row[0] . ',';
                } else {
                    array_push($arrayInsert,
                        array('idEntidad' => $row[0],
                            'idEstacion' => $idEstacion,
                            'itemPlan' => $itemplan,
                            'ruta_pdf' => null,
                            'fecha_inicio' => null,
                            'fecha_fin' => null,
                            'id_usuario_reg' => $idPersonaSession,
                            'fecha_registro' => date("Y-m-d"),
                            'fecha_valida' => null,
                            'flg_validado' => 0,
                            'id_usuario_valida' => $idPersonaSession,
                        )
                    );
                }
            }
            if (strlen($cadIds) == 0) {
                $cadIds = 'IS NOT NULL';
            } else {
                $cadIds = 'NOT IN (' . trim($cadIds, ',') . ')';
            }

            $data = $this->M_bandeja_itemplan_estacion->deleteItemPlanEstaDetalleLic($itemplan, $idEstacion, $cadIds, $arrayInsert);
            if ($data['error'] == EXIT_SUCCESS) {
                $arrayUpdate = array('itemPlan' => $itemplan, 'expediente_diseno' => $flgExpediente, 'plano_diseno_sirope' => $flgDisenoSirope);

                $data = $this->M_bandeja_itemplan_estacion->editarPlanObra($itemplan, $arrayUpdate);
                $modificaciones = "expediente_diseno= " . $flgExpediente;

                $data = $this->m_editar_planobra->insertarLogPlanObra($itemplan, $idPersonaSession, $modificaciones, 'planobra', '', '');
            }
            
            
         
            
            $data = $this->m_bandeja_ejecucion->ejecutarDiseno($itemplan, $idEstacion);
            if($data['error'] == EXIT_ERROR){
                throw new Exception('ERROR AL EJECUTAR EL DISEÃƒâ€˜O');
            }
            
             
            
            $estado_plan = $this->m_utils->getEstadoPlanByItemplan($itemplan);
            if($estado_plan==ESTADO_PLAN_DISENO){
                $data = $this->m_utils->updateEstadoPlanObra($itemplan, ESTADO_PLAN_DISENO_EJECUTADO, $idEstacion);
            }
            
            
            
            
            
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_bandeja_ejecucion->getBandejaEjecucion($idEstaFil, $idTipoPlan, $jefatura, $SubProy, $idProyecto, $fecha, $this->session->userdata('eeccSession')));
            
            
            
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    ***************************/

     public function ejecutarDiseno()
    {
        log_message('error', 'ENTRE ejecutarDiseno');
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {

            $itemplan = $this->input->post('item');
            $idEstacion = $this->input->post('idEstacion');

            $idEstaFil = $this->input->post('idEstacionFil');
            $idTipoPlan = $this->input->post('idTipoPlan');
            $jefatura = $this->input->post('jefatura');
            $idProyecto = $this->input->post('idProyecto');
            $SubProy = $this->input->post('subProy');
            $fecha = $this->input->post('fecha');
            $cantTroba        = $this->input->post('cantTroba');
            $cantAmplificador = $this->input->post('cantAmplificador');
            
            $idEstaFil = ($idEstaFil == '') ? null : $idEstaFil;
            $idTipoPlan = ($idTipoPlan == '') ? null : $idTipoPlan;
            $jefatura = ($jefatura == '') ? null : $jefatura;
            $idProyecto = ($idProyecto == '') ? null : $idProyecto;
            $SubProy = ($SubProy == '') ? null : $SubProy;
            $fecha = ($fecha == '') ? null : $fecha;
            $cantTroba = ($cantTroba == '') ? null : $cantTroba; 
            $cantAmplificador = ($cantAmplificador == '') ? null : $cantAmplificador;
            //LOGICA ENTIDADES

            $idPersonaSession = $this->session->userdata('idPersonaSession');
            $arrayIdEntidades = explode(',',$this->input->post('arrayIdEntidades'));
            $flgExpediente = $this->input->post('flgExpediente');
            $flgDisenoSirope = $this->input->post('flgDisenoSirope');
            $arrayInsert = array();
            
            $this->db->trans_begin();
            
            // if (!is_array($arrayIdEntidades) && $arrayIdEntidades == null && count($arrayIdEntidades) == 0) {
            //     throw new Exception('Debe asignar almenos una entidad');
            // } 
            if($idPersonaSession == null || $idPersonaSession == '') {
                throw new Exception('error, sesi&oacute;n a finalizado, recargue la p&aacute;gina y vuelva a logearse.');
            }
			log_message('error', '$arrayIdEntidades:'.print_r($arrayIdEntidades,true));
            $numEntidades = count($arrayIdEntidades);
            if (is_array($arrayIdEntidades) && $numEntidades > 0) { 
               if($numEntidades ==  1){
                   //foreach ($arrayIdEntidades as $row) {
					    $data_arra  = array(         'idEntidad' => $arrayIdEntidades[0],
                                                       'idEstacion' => $idEstacion,
                                                       'itemPlan' => $itemplan,
                                                       'ruta_pdf' => null,
                                                       'fecha_inicio' => null,
                                                       'fecha_fin' => null,
                                                       'id_usuario_reg' => $idPersonaSession,
                                                       'fecha_registro' => $this->fechaActual(),
                                                       'fecha_valida' => null,
                                                       'flg_validado' => 0,
                                                       'id_usuario_valida' => $idPersonaSession);
                         array_push($arrayInsert,$data_arra);
                   //}
               }else{
                   foreach ($arrayIdEntidades as $row) {
                       if($row != 0){//si cuenta con mas de una entidad seleccionada no tomar en cuenta los 0
                                array_push($arrayInsert,array('idEntidad' => $row,
                                       'idEstacion' => $idEstacion,
                                       'itemPlan' => $itemplan,
                                       'ruta_pdf' => null,
                                       'fecha_inicio' => null,
                                       'fecha_fin' => null,
                                       'id_usuario_reg' => $idPersonaSession,
                                       'fecha_registro' => $this->fechaActual(),
                                       'fecha_valida' => null,
                                       'flg_validado' => 0,
                                       'id_usuario_valida' => $idPersonaSession));
                       }
                   }
               }
                
                //log_message('error', '$$arrayInsert:'.print_r($arrayInsert,true));
                $data = $this->M_bandeja_itemplan_estacion->insertEntidadesFromEjecucionDiseno($arrayInsert);

            }
            $arrayUpdate = array('itemPlan' => $itemplan, 'expediente_diseno' => $flgExpediente, 'plano_diseno_sirope' => $flgDisenoSirope, 'fec_ult_ejec_diseno' => $this->fechaActual());
            $data = $this->M_bandeja_itemplan_estacion->editarPlanObra($itemplan, $arrayUpdate);
            $modificaciones = "expediente_diseno= " . $flgExpediente;
            $data = $this->m_editar_planobra->insertarLogPlanObra($itemplan, $idPersonaSession, $modificaciones, 'planobra', '', '');
            //FIN DE LOGICA DE ENTIDADES

            $data = $this->m_bandeja_ejecucion->ejecutarDiseno($itemplan, $idEstacion);
            if ($data['error'] == EXIT_ERROR) {
                throw new Exception('ERROR AL EJECUTAR EL DISEÃƒÆ’Ã¢â‚¬ËœO');
            }

            $estado_plan = $this->m_utils->getEstadoPlanByItemplan($itemplan);
            $infoPlanObra = $this->m_utils->getPlanobraByItemplan($itemplan);
            
            if ($estado_plan == ESTADO_PLAN_DISENO || $estado_plan == ID_ESTADO_DISENIO_PARCIAL) {
                if($infoPlanObra['operador'] == 'Estudio de Campo') {
                    $data = $this->m_utils->updateEstadoPlanObraToBanEjecucion($itemplan, ID_ESTADO_TERMINADO, $idEstacion);
                    
                    if($data['error'] == EXIT_SUCCESS) {
                         $arrayDataLog = array(
                                                'tabla'            => 'planobra',
                                                'actividad'        => 'Obra Terminado - Estudio de Campo',
                                                'itemplan'         => $itemplan,
                                                'fecha_registro'   => $this->fechaActual(),
                                                'id_usuario'       => $this->session->userdata('idPersonaSession'),
                                                'idEstadoPlan'     => ID_ESTADO_TERMINADO
                                             );
                        $this->m_utils->registrarLogPlanObra($arrayDataLog);                     
                    }
                       
        
                    
                } else {
                    if($estado_plan == ESTADO_PLAN_DISENO){
                        if($infoPlanObra['paquetizado_fg']  ==  2){//paquetizado cambio czavalacas 05.12.2019
                            $data = $this->m_utils->updateEstadoPlanObraToBanEjecucion($itemplan, ID_ESTADO_EN_LICENCIA, $idEstacion);
                            if($data['error'] == EXIT_SUCCESS) {
                                $arrayDataLog = array(
                                    'tabla'            => 'planobra',
                                    'actividad'        => 'Diseno - Bandeja Ejecucion',
                                    'itemplan'         => $itemplan,
                                    'fecha_registro'   => $this->fechaActual(),
                                    'id_usuario'       => $this->session->userdata('idPersonaSession'),
                                    'idEstadoPlan'     => ID_ESTADO_EN_LICENCIA
                                );
                                $this->m_utils->registrarLogPlanObra($arrayDataLog);
                            }
                        }else{
                            $data = $this->m_utils->updateEstadoPlanObraToBanEjecucion($itemplan, ESTADO_PLAN_DISENO_EJECUTADO, $idEstacion);
                            if($data['error'] == EXIT_SUCCESS) {
                                $arrayDataLog = array(
                                    'tabla'            => 'planobra',
                                    'actividad'        => 'Diseno - Bandeja Ejecucion',
                                    'itemplan'         => $itemplan,
                                    'fecha_registro'   => $this->fechaActual(),
                                    'id_usuario'       => $this->session->userdata('idPersonaSession'),
                                    'idEstadoPlan'     => ESTADO_PLAN_DISENO_EJECUTADO
                                );
                                $this->m_utils->registrarLogPlanObra($arrayDataLog);
                            }
                        }
                    }else if ($estado_plan == ID_ESTADO_DISENIO_PARCIAL){
                        $hasPTRApro = $this->m_bandeja_ejecucion->havePTRAprobada($itemplan, $idEstacion);
                        if($hasPTRApro!=null){
                            if($hasPTRApro['numAprob']>=1){
                                $data = $this->m_utils->updateEstadoPlanObraToBanEjecucion($itemplan, ESTADO_PLAN_EN_OBRA, $idEstacion);
                                
                                if($data['error'] == EXIT_SUCCESS) {
                                     $arrayDataLog = array(
                                                            'tabla'            => 'planobra',
                                                            'actividad'        => 'Obra - Bandeja EjecuciÃƒÂ³n',
                                                            'itemplan'         => $itemplan,
                                                            'fecha_registro'   => $this->fechaActual(),
                                                            'id_usuario'       => $this->session->userdata('idPersonaSession'),
                                                            'idEstadoPlan'     => ESTADO_PLAN_EN_OBRA
                                                          );
                                    $this->m_utils->registrarLogPlanObra($arrayDataLog);
                                }
                            }
                        }
                    }
                }
            }
            
            /*$cambioComplejidad = $this->m_utils->getCambioPoComplejidad($itemplan, $idEstacion);
            //log_message('error', $itemplan.'-'.$idEstacion.'-'.$cantAmplificador.'-'.$cantTroba);
            
            if($cambioComplejidad == 1) {
                $idTipoComplejidad = 2;//ALTA COMPLEJIDAD
                $resp = $this->m_utils->generarPoComplej($itemplan, $idEstacion, $idPersonaSession, 0, 0, $idTipoComplejidad, NULL);
            } else {
                $resp = $this->m_bandeja_ejecucion->generarPODiseno($itemplan, $idEstacion, $cantAmplificador, $cantTroba);
            }
            $this->m_bandeja_ejecucion->deleteAnclaByItemplaEstacion($itemplan, $idEstacion); 
            
            if($resp == 5) {
				$idTipoComplejidad = $this->m_bandeja_ejecucion->getTipoComplejidadByItemplan($itemplan);
                $data['error'] = EXIT_ERROR;
				if($idTipoComplejidad == 2) {
					throw new Exception('Crear la PO MO, es de complejidad alta.');
				} else {
					throw new Exception('Se necesita configurar, el tipo complejidad y partidas.');
				} 
            } 
           

            if($resp == 3) {
                $data['error'] = EXIT_ERROR;
                throw new Exception('No tiene PO material');
            } 

            if($resp == null || $resp == '') {
                $data['error'] = EXIT_ERROR;
                throw new Exception('Error Comunicarse con el programador');
            }*/
                        
            $idSubProyectoEstacion  = $this->m_bandeja_ejecucion->getIdSubProyecEstacionByitemplanAndEstacion($itemplan, $idEstacion);
            
            $flgPoDiseno = $this->m_bandeja_ejecucion->getFlgPoDiseno($itemplan, $idSubProyectoEstacion);
            
			$idProyecto = $this->m_utils->getProyectoByItemplan($itemplan);
			$fl_paquetizado = $this->m_utils->getFlgPaquetizadoPo($itemplan);

			if($fl_paquetizado == 2) {
				if($idProyecto == 4  || $idProyecto == 8) {//OBRA PUBLICA O TRANSPORTE SE GENERA PO DISEÃ‘O
					if($flgPoDiseno == '' || $flgPoDiseno == null) {//SI NO TIENE PO DISENO
						$idTipoComplejidad = $this->m_bandeja_ejecucion->getTipoComplejidadByItemplan($itemplan);
						if($idTipoComplejidad == 2) {
							$flg_mo = $this->m_bandeja_ejecucion->getFlgMo($itemplan, $idEstacion);

							if($flg_mo != 1) {
								$data['error'] = EXIT_ERROR;
								throw new Exception('Es de complejidad alta, necesita PO MO');
							}
						}
						$data = $this->generarPODiseno($itemplan, $idEstacion, $cantAmplificador, $cantTroba, $idPersonaSession, $idSubProyectoEstacion); //FUNCION DONDE SE GENERA LA PO
					}
				}
			} else {
				if($flgPoDiseno == '' || $flgPoDiseno == null) {//SI NO TIENE PO DISENO
					$idTipoComplejidad = $this->m_bandeja_ejecucion->getTipoComplejidadByItemplan($itemplan);
					if($idTipoComplejidad == 2) {
						$flg_mo = $this->m_bandeja_ejecucion->getFlgMo($itemplan, $idEstacion);

						if($flg_mo != 1) {
							$data['error'] = EXIT_ERROR;
							throw new Exception('Es de complejidad alta, necesita PO MO');
						}
					}
					$data = $this->generarPODiseno($itemplan, $idEstacion, $cantAmplificador, $cantTroba, $idPersonaSession, $idSubProyectoEstacion); //FUNCION DONDE SE GENERA LA PO
				}
			}

            /**** nuevo carga expediente 30.06.2019 czavalacas***/
            
            //DE NO EXISTIR LA CARPETA ITEMPLAN LA CREAMOS
            $pathItemplan = 'uploads/expedientes_diseno/'.$itemplan;
            if (!is_dir($pathItemplan)) {
                mkdir ($pathItemplan, 0777);
            }
                        
            $descEstacion = $this->m_utils->getEstaciondescByIdEstacion($idEstacion);
            //DE NO EXISTIR LA CARPETA ITEMPLAN ESTACION LA CREAMOS
            $pathItemEstacion = $pathItemplan.'/'.$descEstacion;
            if (!is_dir($pathItemEstacion)) {
                mkdir ($pathItemEstacion, 0777);
            }
            
            $uploadfile1 = $pathItemEstacion.'/'. basename($_FILES['archivoExpediente']['name']);
            
            if (move_uploaded_file($_FILES['archivoExpediente']['tmp_name'], $uploadfile1)) {                
                $dataUpdate = array('path_expediente_diseno' => $uploadfile1);
                $data = $this->m_bandeja_ejecucion->updatePathDisenoExpediente($dataUpdate, $itemplan, $idEstacion);
                if($data['error'] == EXIT_ERROR){
                    throw new Exception('Error al actualizar la ruta del archivo.');
                }
            }else {
                throw new Exception('Hubo un problema con la carga del archivo 1 al servidor, comuniquese con el administrador.');
            }

            /*****************************************************/
            $this->db->trans_commit();
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_bandeja_ejecucion->getBandejaEjecucion($idEstaFil, $idTipoPlan, $jefatura, $SubProy, $idProyecto, $fecha, $this->session->userdata('eeccSession')));
            
            $data['error'] = EXIT_SUCCESS;
            
            /**ENVIAR FAILS SIOM 29.05.2019 czavalacas**/
            $this->ejecutarEnviosFallidosSiom($itemplan, $idEstacion);
            /***************************/
        } catch (Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
	
	function generarPODiseno($__itemplan, $__id_estacion, $__nro_amplificador, $__nro_troba, $__id_usuario, $idSubProyectoEstacion) {
        $data['error'] = EXIT_SUCCESS;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $data = array();
            $dataArray = $this->m_bandeja_ejecucion->getDetallePODiseno($__itemplan, $__id_estacion, $__nro_amplificador, $__nro_troba);
            $codigo_po = $this->m_utils->getCodigoPO($__itemplan);
            $totalFin = 0;
            $idEstadoPo = null;
            $has_sirope = null;
            $idTipoComplejidad = null;
            $idEstacion = null;
            $idEmpresaColab = null;
            $flg_mat = null;
            foreach($dataArray as $row) {
                $totalFin = $row['totalDiseno'] + $totalFin;
                $has_sirope = $row['has_sirope_diseno'];
                $idTipoComplejidad = $row['idTipoComplejidad'];
                $idEmpresaColab    = $row['idEmpresaColab'];
                $flg_mat           = $row['flgMat'];
                
                $arrayDetallePO = array();
                $arrayDetalle['codigo_po']      = $codigo_po;
                $arrayDetalle['idPartida']      = $row['idPartida'];
                $arrayDetalle['idPrecioDiseno'] = $row['idPrecioDiseno'];
                $arrayDetalle['idEmpresaColab'] = $row['idEmpresaColab'];
                $arrayDetalle['idZonal']        = $row['idZonal'];
                $arrayDetalle['cantidad']       = $row['cantidad'];
                $arrayDetalle['baremo']         = $row['baremo'];
                $arrayDetalle['costo']          = $row['costoPreciario'];
                $arrayDetalle['nro_amplificadores'] = ($__nro_amplificador) ? $__nro_amplificador : $__nro_troba;
                $arrayDetalle['total']          = $row['totalDiseno'];
                array_push($arrayDetallePO, $arrayDetalle);
            }
            
            if($__id_estacion == 5) {
                if($has_sirope == 1) {
                    if($idTipoComplejidad == 1) {
                        $estado_po = 5;
                    } else if($idTipoComplejidad == 2) {
                        $estado_po = 1;
                    }
                } else {
                    $estado_po = 1;
                }
                
            } else if($__id_estacion == 2) {
                $estado_po = 1;
            }
            
            if(count($arrayDetallePO) > 0) {
                if($flg_mat != 1) {
                    $data['error'] = EXIT_ERROR;
                    throw new Exception('No tiene PO material');
                }
                
                $arrayPO = array(
                    'itemplan'      => $__itemplan,
                    'codigo_po'     => $codigo_po,
                    'estado_po'     => $estado_po, 
                    'idEstacion'    => 1,
                    'from'          => FROM_DISENIO,
                    'costo_total'   => $totalFin,
                    'idUsuario'     => $__id_usuario,
                    'fechaRegistro' => $this->fechaActual(),
                    'flg_tipo_area'     => 2,
                    'id_eecc_reg'       => $idEmpresaColab
                );
                //ARRAYY PARA EL LOG
                $arrayLogPO = array();
                if($estado_po == 5) {
                    $arrayLog = array(
                                        'codigo_po'         =>  $codigo_po,
                                        'itemplan'          =>  $__itemplan,
                                        'idUsuario'         =>  $__id_usuario,
                                        'fecha_registro'    =>  $this->fechaActual(),
                                        'idPoestado'        =>  1,
                                        'controlador'       =>  'CREACION PO REGISTRO DISENO'
                                    );
                    
                    array_push($arrayLogPO, $arrayLog);
                    
                    $arrayLog = array(
										'codigo_po'         =>  $codigo_po,
										'itemplan'          =>  $__itemplan,
										'idUsuario'         =>  $__id_usuario,
										'fecha_registro'    =>  $this->fechaActual(),
										'idPoestado'        =>  4,
										'controlador'       =>  'CREACION PO REGISTRO DISENO'
									);
                    array_push($arrayLogPO, $arrayLog);
                    
                    $arrayLog = array(
                                            'codigo_po'         =>  $codigo_po,
                                            'itemplan'          =>  $__itemplan,
                                            'idUsuario'         =>  $__id_usuario,
                                            'fecha_registro'    =>  $this->fechaActual(),
                                            'idPoestado'        =>  5,
                                            'controlador'       =>  'CREACION PO REGISTRO DISENO'
                                        );
                    array_push($arrayLogPO, $arrayLog);      
                } else if($estado_po == 1) {
					$arrayLog = array(
										'codigo_po'         =>  $codigo_po,
										'itemplan'          =>  $__itemplan,
										'idUsuario'         =>  $__id_usuario,
										'fecha_registro'    =>  $this->fechaActual(),
										'idPoestado'        =>  1,
										'controlador'       =>  'CREACION PO REGISTRO DISENO'
									);
                    
                    array_push($arrayLogPO, $arrayLog);
                }
    
                if($idSubProyectoEstacion == null || $idSubProyectoEstacion == '') {
                    $data['error'] = EXIT_ERROR;
                    throw new Exception('Debe ingresar el Ã¡rea de diseÃ±o');
                }
                
                
                $arrayDetalleplan = array(
                                            'itemPlan' =>  $__itemplan,
                                            'poCod'    => $codigo_po,
                                            'idSubProyectoEstacion' =>  $idSubProyectoEstacion
                                        );   
                
                $data = $this->m_bandeja_ejecucion->registrarPoDiseno($arrayPO, $arrayLogPO, $arrayDetalleplan, $arrayDetallePO);
            }
            
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }

    function filtrarSubProyecto() {
        $idProyecto = $this->input->post('idProyecto');
        $data['cmbSubProyecto'] = __buildSubProyecto($idProyecto, ID_TIPO_PLANTA_EXTERNA, 1);
        
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function insertFileEjec() {
        log_message('error', '2 ) idEstacion::'.$this->session->userdata('idEstacion_tmp'));        
        $itemPlan = $this->session->userdata('itemplan_tmp');
        $idEstacion = $this->session->userdata('idEstacion_tmp');
        $file     = $_FILES ["file"] ["name"];
        $filetype = $_FILES ["file"] ["type"];
        $filesize = $_FILES ["file"] ["size"];
    
        //log_message('error', 'insert1');
         
        $ubicacion = 'uploads/ejecucion/'.$itemPlan;
        if (!is_dir($ubicacion)) {
            mkdir ('uploads/ejecucion/'.$itemPlan, 0777);
        }
        
        if($idEstacion == ID_ESTACION_COAXIAL){
            $descEstacion = 'COAXIAL';
        }ELSE IF($idEstacion == ID_ESTACION_FO){
            $descEstacion = 'FO';
        }
        
        $subCarpeta = 'uploads/ejecucion/'.$itemPlan.'/'.$itemPlan.'_'.$descEstacion;
        $file2 = utf8_decode($file);
        if (!is_dir($subCarpeta))
            mkdir ( $subCarpeta, 0777 );
        if (utf8_decode($file) && move_uploaded_file($_FILES["file"]["tmp_name"], $subCarpeta."/".$file2 )) {
            log_message('error', 'INSERTO IMG');
        }
        $data['error'] = EXIT_SUCCESS;
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function comprimirFilesEjec() {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $itemplan = $this->session->userdata('itemplan_tmp');
            $idEstacion = $this->session->userdata('idEstacion_tmp');           

            if($idEstacion == ID_ESTACION_COAXIAL){
                $descEstacion = 'COAXIAL'; log_message('error', 'COAXXXXXXX');
            }ELSE IF($idEstacion == ID_ESTACION_FO){
                $descEstacion = 'FO'; log_message('error', 'FOOOOOO');
            }        

            $subCarpeta   = 'uploads/ejecucion/'.$itemplan.'/'.$itemplan.'_'.$descEstacion;
            $this->zip->read_dir($subCarpeta,false);
            
            $fileName = $descEstacion.'_'.rand(1, 100).date("dmhis").'.zip';      
            $this->zip->archive('uploads/ejecucion/'.$itemplan.'/'.$fileName);
            $data = $this->m_bandeja_adjudicacion->registrarNombreArchivo($itemplan, $fileName, $idEstacion);

            $this->rrmdir($subCarpeta);

            $data['error'] = EXIT_SUCCESS;
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_bandeja_ejecucion->getBandejaEjecucion(NULL, NULL, NULL, NULL, NULL, NULL,$this->session->userdata('eeccSession')));
            
            //$this->zip->download($fileName);
        }catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
      // log_message('error', 'data_enviar'.print_r($data, true));
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function editEjecuDi(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            
            $idEstaFil  = $this->input->post('idEstacionFil');
            $idTipoPlan = $this->input->post('idTipoPlan');
            $jefatura   = $this->input->post('jefatura');
            $idProyecto = $this->input->post('idProyecto');
            $SubProy    = $this->input->post('subProy');
            $fecha      = $this->input->post('fecha');
            
            $idEstaFil  = ($idEstaFil == '') ? NULL : $idEstaFil;
            $idTipoPlan = ($idTipoPlan == '') ? NULL : $idTipoPlan;
            $jefatura   = ($jefatura == '')   ? NULL : $jefatura;
            $idProyecto = ($idProyecto == '') ? NULL : $idProyecto;
            $SubProy    = ($SubProy == '')    ? NULL : $SubProy;
            $fecha      = ($fecha == '')      ? NULL : $fecha;
            
            $itemplan = $this->session->userdata('itemplan_tmp');
            $idEstacion = $this->session->userdata('idEstacion_tmp');
            $fechaPrevDise        = $this->input->post('idFechaPreAtencionCoax');
            $data = $this->m_bandeja_ejecucion->actualizarDatosDiseno($itemplan,$idEstacion, $fechaPrevDise);
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_bandeja_ejecucion->getBandejaEjecucion($idEstaFil, $idTipoPlan, $jefatura, $SubProy, $idProyecto, $fecha, $this->session->userdata('eeccSession')));

           //log_message('error',  $data['tablaAsigGrafo']);
            $data['error'] = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function getInfoByItemplanEjec(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
           
            $itemplan    = $this->input->post('itemplan');
            $idEstacion  = $this->input->post('idEstacion');
            
            $array = array('idEstacion_tmp'  => $idEstacion,
                             'itemplan_tmp'  => $itemplan);
            $this->session->set_userdata($array);           
            $data['error']        = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
     function rrmdir($src) {
        $dir = opendir($src);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                $full = $src . '/' . $file;
                if ( is_dir($full) ) {
                    $this->rrmdir($full);
                }
                else {
                    unlink($full);
                }
            }
        }
        closedir($dir);
        rmdir($src);
    }
    

	
	 public function makeFORMEntidades($listaEntidades)
    {
        $html = '';
        $arrayIdEntidades = array();
        $arrayPrueba = array();

        foreach ($listaEntidades as $row) {
            if ($row->marcado == 1) {
                array_push($arrayPrueba, $row->idEntidad, ($row->disabled == null ? 0 : 1));
                array_push($arrayIdEntidades, $arrayPrueba);
            }
            $html .= '  <div class="col-4" id="' . $row->idEntidad . '" >
                            <input type="checkbox" id="Ent' . $row->idEntidad . '" class="custom-control-input" data-ent="' . $row->idEntidad . '" onchange="agregarEntidades(' . $row->idEntidad . ',' . ($row->disabled == null ? 0 : 1) . ')" ' . ($row->marcado == 1 ? 'checked' : '') . '  ' . ($row->disabled == 1 ? 'disabled' : '') . '>
                            <label for="Ent' . $row->idEntidad . '" >' . $row->desc_entidad . '</label>
                       </div>';
            $arrayPrueba = array();
        }
        #999999 = SIN ENTIDAD
        $html .= '  <div class="col-4" id="999999" >
                            <input type="checkbox" id="Ent999999" class="custom-control-input" data-ent="999999" onchange="agregarEntidades(999999,' . ($row->disabled == null ? 0 : 1) . ')" ' . ($row->marcado == 1 ? 'checked' : '') . '  ' . ($row->disabled == 1 ? 'disabled' : '') . '>
                            <label style="color: red;" for="Ent999999" >NO REQUIERE LICENCIA</label>
                       </div>';

        $data['arrayIdEntidades'] = $arrayIdEntidades;
        $data['html'] = utf8_decode($html);
        return $data;
    }
    
    /*******nuevo 15082018***/
      public function getInfoByItemplanLicencia()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $itemPlan = $this->input->post('itemplan');
            $idEstacion = $this->input->post('idEstacion');
            $dataEntidades = $this->makeFORMEntidades($this->M_bandeja_itemplan_estacion->getEntidades($itemPlan, $idEstacion));
            $data['htmlEntidades'] = $dataEntidades['html'];
            $data['arrayIdEntidades'] = $dataEntidades['arrayIdEntidades'];
            $flg = $this->m_utils->validarComplejidadDiseno($itemPlan);
            $idProyecto  = $this->m_utils->getIdProyectoByItemplan($itemPlan); 
            $inputATro = null;
            $array = array('idEstacion_tmp' => $idEstacion,
                            'itemplan_tmp'  => $itemPlan);
                            
            if($flg == 1) {
                if($idEstacion == 2 && $idProyecto == 1) {
                    $inputATro = '<label>Cant. Amplificador</label>
                                        <input id="cant_amplificador" type="text" value="1" class="" />';
                    $data['input'] = 1;                        
                } else if($idEstacion == 5 && $idProyecto == 1) {
                    $data['input'] = 2;  
                    $inputATro = '<label>Cant. Troba</label>
                                            <input id="cant_troba" type="text" value="1" class="" />';
                }
            }
            
            $data['inputAmTro'] = $inputATro;
            $this->session->set_userdata($array);

            $jefatura = $this->M_bandeja_itemplan_estacion->getJefaturaByItemPlan($itemPlan);
            if (isset($jefatura)) {
                $data['error'] = EXIT_SUCCESS;
                $data['jefatura'] = $jefatura;
            } else {
                $data['jefatura'] = null;
            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }
	
	 public function getInfoByItemplanLicenciaPqt()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $itemPlan = $this->input->post('itemplan');
            $idEstacion = $this->input->post('idEstacion');
            $dataEntidades = $this->makeFORMEntidades($this->M_bandeja_itemplan_estacion->getEntidades($itemPlan, $idEstacion));
            $data['htmlEntidades'] = $dataEntidades['html'];
            $data['arrayIdEntidades'] = $dataEntidades['arrayIdEntidades'];
            $flg = $this->m_utils->validarComplejidadDiseno($itemPlan);
            $idProyecto  = $this->m_utils->getIdProyectoByItemplan($itemPlan); 
            $inputATro = null;
            $array = array('idEstacion_tmp' => $idEstacion,
                            'itemplan_tmp'  => $itemPlan);
                            
            if($flg == 1) {
                if($idEstacion == 2 && $idProyecto == 1) {
                    $inputATro = '<label>Cant. Amplificador</label>
                                        <input id="cant_amplificador" type="text" value="1" class="" />';
                    $data['input'] = 1;                        
                } else if($idEstacion == 5 && $idProyecto == 1) {
                    $data['input'] = 2;  
                    $inputATro = '<label>Cant. Troba</label>
                                            <input id="cant_troba" type="text" value="1" class="" />';
                }
            }
            
            $data['inputAmTro'] = $inputATro;
            $this->session->set_userdata($array);

            $jefatura = $this->M_bandeja_itemplan_estacion->getJefaturaByItemPlanPqt($itemPlan);
            if (isset($jefatura)) {
                $data['error'] = EXIT_SUCCESS;
                $data['jefatura'] = $jefatura;
            } else {
                $data['jefatura'] = null;
            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }
    
    function ejecutarEnviosFallidosSiom($itemplan, $estacion){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $listaPendientes = $this->m_bandeja_ejecucion->getTramasPendientesEstacionNoEjec($itemplan, $estacion);
            log_message('error', '$listaPendientes:'.print_r($listaPendientes,true));
            foreach($listaPendientes as $row){
                $ptr = $row->ptr;
                $id_siom_obra = $row->id_siom_obra;
                $infoItemplan = $this->m_utils->getInfoItemplanLiquidacionSisegosWebPo($ptr, $itemplan);
                if($infoItemplan==null){
                    $infoItemplan = $this->m_utils->getInfoItemplanLiquidacionSisegos($ptr, $itemplan);
                }
                log_message('error', '$listaPendientes for');
                $emplazamiento =  $this->m_liquidacion->getEmplazamientoIdSiomByidCentral($infoItemplan['idCentral']);//OBTENEMOS EL ID DEZPLAZAMIENTO DE LA TABLA SIOM_NODOS POR EL ID CENTRAL DE LA PO
                if($emplazamiento['cant'] >= 1){// SE ENCONTRO NODO
                    log_message('error', 'SE ENCONTRO NODO');
                    $codigo_siom = $this->sendDataToSiom($infoItemplan['idEecc'], $infoItemplan['idEstacion'], $infoItemplan['estacionDesc'], $itemplan, $emplazamiento['empl_id'], $ptr);
                    //$codigo_siom = 7766;
                    //validar si no viene nulo
                    if($codigo_siom != null){
                        $dataSiom = array('itemplan'          => $itemplan,
                            'idEstacion'        => $infoItemplan['idEstacion'],
                            'ptr'               => $ptr,
                            'fechaRegistro'     => $this->fechaActual(),
                            'idUsuarioRegistro' => $this->session->userdata('idPersonaSession'),
                            'codigoSiom'        => $codigo_siom,
                            'ultimo_estado'       => 'CREADA',
                            'fecha_ultimo_estado' => $this->fechaActual()
                        );
                
                        $dataLogPo = array( 'tabla'            => 'Siom',
                            'actividad'        => 'Registrar Siom',
                            'itemplan'         => $itemplan,
                            'fecha_registro'   => $this->fechaActual(),
                            'id_usuario'       => $this->session->userdata('idPersonaSession')
                        );
                
                        $dataEstado = array('codigo_siom'           => $codigo_siom,
                            'estado_desc'           => 'CREADA',
                            'fechaRegistro'         => $this->fechaActual(),
                            'usuario_registro'      => $this->session->userdata('usernameSession'),
                            'estado_transaccion'    => 1
                        );
                        $data = $this->m_liquidacion->updateSiom($dataSiom, $dataLogPo, $dataEstado, $id_siom_obra);
                        $data['codigo_siom'] = $codigo_siom;
                    }else{
                        throw new Exception('error', 'No se recepciono un codigo siom');
                    }
                }else{
                    log_message('error', 'NO SE ENCONTRO EMPLAZAMIENTO ID PARA ESE NODO');
                    $motivoError = 'NO SE ENCONTRO EMPLAZAMIENTO ID PARA ESE NODO';
                    $estadoError = 4;
                    $dataLogSiom = array(
                                        'ptr'           => $ptr,
                                        'itemplan'      => $itemplan,
                                        'usuario_envio' => $this->session->userdata('usernameSession'),
                                        'fecha_envio'   => $this->fechaActual());
                    $dataLogSiom['estado']  =  $estadoError;//NODO NO ENCONTRADO = 4
                    $dataLogSiom['mensaje']  =  $motivoError;
                    
                    $dataSiom = array('itemplan'          => $itemplan,
                                    'idEstacion'        => $infoItemplan['idEstacion'],
                                    'ptr'               => $ptr,
                                    'fechaRegistro'     => $this->fechaActual(),
                                    'idUsuarioRegistro' => $this->session->userdata('idPersonaSession'),
                                    'codigoSiom'        => null,
                                    'ultimo_estado'       => $motivoError,
                                    'fecha_ultimo_estado' => $this->fechaActual()
                                );
                    $this->m_bandeja_ejecucion->updateSiomToNodoNoEncontrado($dataLogSiom, $dataSiom, $id_siom_obra);
                }
            }            
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
  
    //REPLICA DEL CONTROLER C_LIQUIDACION...
    public function sendDataToSiom($idEECC, $idEstacion, $estacion_desc, $itemplan, $emplazamiento_id, $ptr){
    
        try{
            $codigo_siom = null;
            $idEEEC_post = ID_EECC_TELEFONICA_SIOM;//POR DEFECTO TDP
            if($idEECC  ==  ID_EECC_COBRA){
                $idEEEC_post    =   ID_EECC_COBRA_SIOM;
            }else if($idEECC  ==  ID_EECC_LARI){
                $idEEEC_post    =   ID_EECC_LARI_SIOM;
            }else if($idEECC  ==  ID_EECC_DOMINION){
                $idEEEC_post    =   ID_EECC_DOMINION_SIOM;
            }else if($idEECC  ==  ID_EECC_EZENTIS){
                $idEEEC_post    =   ID_EECC_EZENTIS_SIOM;
            }else if($idEECC  ==  ID_EECC_COMFICA){
                $idEEEC_post    =   ID_EECC_COMFICA_SIOM;
            }else if($idEECC  ==  ID_EECC_LITEYCA){
                $idEEEC_post    =   ID_EECC_LITEYCA_SIOM;
            }
    
            $idSubEspecialidad_post = null;
            if($idEstacion  ==  ID_ESTACION_FO || $idEstacion  ==  ID_ESTACION_FO_ALIM || $idEstacion  ==  ID_ESTACION_FO_DIST){
                $idSubEspecialidad_post     = ID_SUB_ESPECIALIDAD_FO_SIOM;
                $idFormulario               = ID_FORMULARIO_FO_SIOM;
            }else if($idEstacion  ==  ID_ESTACION_COAXIAL){
                $idSubEspecialidad_post     = ID_SUB_ESPECIALIDAD_COAXIAL_SIOM;
                $idFormulario               = ID_FORMULARIO_COAXIAL_SIOM;
            }else if($idEstacion  ==  ID_ESTACION_OC_FO){
                $idSubEspecialidad_post     = ID_SUB_ESPECIALIDAD_OBRA_CIVIL_SIOM;
                $idFormulario               = ID_FORMULARIO_OBRA_CIVIL_SIOM;
            }else if($idEstacion  ==  ID_ESTACION_OC_COAXIAL){
                $idSubEspecialidad_post     = ID_SUB_ESPECIALIDAD_OBRA_CIVIL_SIOM;
                $idFormulario               = ID_FORMULARIO_OBRA_CIVIL_SIOM;
            }else if($idEstacion  ==  ID_ESTACION_UM || $idEstacion ==  ID_ESTACION_AC_CLIENTE){
                $idSubEspecialidad_post     = ID_SUB_ESPECIALIDAD_ULTIMA_MILLA;
                $idFormulario               = ID_FORMULARIO_ULTIMA_MILLA;
            }else if($idEstacion  ==  ID_ESTACION_FUENTE){
                $idSubEspecialidad_post     = ID_SUB_ESPECIALIDAD_ENERGIA;
                $idFormulario               = ID_FORMULARIO_ENERGIA;
            }
    
            $dataSend = ['cont_id' 		        => ID_CONTRATO_TELFONICA_SIOM,//CODIGO DE CONTRATO = 21
                'empl_id'               => $emplazamiento_id,//CODIGO DE NODO EN BASE A SU TABLA EMPLAZAMIENTO
                'empr_id' 		        => $idEEEC_post,//EEECC 23 = LARI, 31 = DOMINION, 32 = COBRA, 33 = EZENTIS
                'formularios' 	        => [$idFormulario],//idFormulario
                'orse_descripcion' 	    => $itemplan.' '.$estacion_desc,//ITEMPLAN_ESTACION
                'orse_fecha_creacion'   => $this->fechaActual(),//NOW
                'orse_fecha_solicitud'	=> $this->fechaActual(),//NOW
                'orse_indisponibilidad' => 'SI',//siempre si
                'orse_tag' 		        => 111,//????
                'orse_tipo' 		    => 'OSGN',//OSGN SIEMPRE
                'sube_id' 		        => $idSubEspecialidad_post,//SUBESPACIALIDAD EN BASE A LA ESPACIALIDAD ESTACION
                'usua_login_creador'    => 'WSPO2019',
                'usua_pass_creador' 	=> 'WSPO2019' ];
    
            $dataLogSiom = array('data_send'     => json_encode($dataSend),
                'ptr'           => $ptr,
                'itemplan'      => $itemplan,
                'usuario_envio' => $this->session->userdata('usernameSession'),
                'fecha_envio'   => $this->fechaActual());
    
            //$url = 'http://3.215.20.37:8080/crearOS-1.0/api/v1/CrearOS';//QA
            $url = 'http://54.86.187.150:8080/crearOS-1.0/api/v1/CrearOS';//PRODUCCION
            $response = $this->m_utils->sendDataToURLTypePUT($url, json_encode($dataSend));
            if($response->codigo == EXIT_SUCCESS){//SE CREO LA OS
                $codigo_siom = $response->orseid;
                $dataLogSiom['codigo']  =  $response->codigo;
                $dataLogSiom['mensaje'] =  $response->mensaje;
                $dataLogSiom['orseid']  =  $response->orseid;
                $dataLogSiom['estado']  =  1;
                $this->m_liquidacion->insertLogTramaSiomSoloLog($dataLogSiom);
                log_message('error', 'TODO BIEN!');
            }else{//NO SE CREO LA OS
                $dataLogSiom['codigo']  =  $response->codigo;
                $dataLogSiom['mensaje'] =  $response->mensaje;
                $dataLogSiom['estado']  =  2;
                log_message('error', 'TODO MAL!');
                $this->m_liquidacion->insertLogTramaSiomSoloLog($dataLogSiom);
            }
        }catch(Exception $e){//ERROR AL ACCEDER AL SERVIDOR
            log_message('error', 'ERROR EN EL SERVIDOR!!');
            $dataLogSiom['estado']  =  3;
            $this->m_liquidacion->insertLogTramaSiomSoloLog($dataLogSiom);
        }
        return $codigo_siom;
    }
    
    function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone','America/Lima');
        setlocale(LC_TIME, "es_ES","esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }
    
}