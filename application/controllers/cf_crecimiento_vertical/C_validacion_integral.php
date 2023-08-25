<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_validacion_integral extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_crecimiento_vertical/m_validacion_integral');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index(){
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
			/*
    	       $data['listaEECC']     = $this->m_utils->getAllEECC();
               $data['listaZonal']    = $this->m_utils->getAllZonalGroup();
               $data['cmbJefatura']   = $this->m_utils->getJefaturaCmb();
        	   $data['listaSubProy']  = $this->m_utils->getAllSubProyecto();
        	   $data['listafase']     = $this->m_utils->getAllFase();*/
               $data['tablaData']     = $this->getTablaHojaGestion($this->m_validacion_integral->getObrasValidadosIntegral());               
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
               $permisos =  $this->session->userdata('permisosArbol');
        	   $result = $this->lib_utils->getHTMLPermisos($permisos, 248, 305, ID_MODULO_ADMINISTRATIVO);
        	   $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	         $this->load->view('vf_crecimiento_vertical/v_validacion_integral',$data);
        	   }else{
        	       redirect('login','refresh');
        	   }
    	 }else{
        	 redirect('login','refresh');
	    }
             
    }
    
    function getTablaHojaGestion($listaHojaGestion) {
        
        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th style="width: 100px;"></th>  
			 				<th>Itemplan</th> 
                            <th>Orden Compra</th>                           
							<th>SubProyecto</th>
                            <th>Fase</th>
                            <th>Nombre Proyecto</th>
                            <th>Estado Plan</th>
                            <th>EECC</th>
                            <th>Sirope</th>
                            <th>Total</th>			
                        </tr>
                    </thead>                    
                    <tbody>';
             if($listaHojaGestion != null){                                                                        
                foreach($listaHojaGestion as $row){
                    $html .=' <tr>  <td>
                                        <a data-itm="'.$row->itemplan.'" class="valPartidas">
											<i title="Validar" class="zmdi zmdi-hc-2x zmdi-check-circle" style="color: green;"></i>
										</a>
                                        <a  data-itm="'.$row->itemplan.'" style="margin-left: 10px" class="editPartidas">
											<i title="Editar Partidas" class="zmdi zmdi-hc-2x zmdi-border-color" style="color: red;"></i>
										</a>
                                    </td>                      
        							<td>'.$row->itemplan.'</td>
        							<td>'.$row->orden_compra.'</td>
							        <td>'.$row->subProyectoDesc.'</td>
							        <td>'.$row->faseDesc.'</td>
							        <td>'.$row->nombreProyecto.'</td>
						            <td>'.$row->estadoPlanDesc.'</td>
								    <td>'.$row->empresaColabDesc.'</td>
							        <td>'.$row->sirope.'</td>
								    <td>'.$row->total.'</td>
                            </tr>';
                }
             }
            $html .='</tbody>
                </table>';
                    
            return $html;
    }
    
    public function getMaterialesPartidasByItem(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = 'No se obtuvo Informacion';
        $data['cabecera'] = 'Alerta';
        try{
            $itemplan       = $this->input->post('itemplan');
            $contenido = $this->makeHTLMDetalleMatPartidasByItem($itemplan);
            $data['htmlDetPartidas'] = $contenido['html'];
            $data['valiPartidas']    = json_encode($contenido['valiManoObra']);            
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function makeHTLMDetalleMatPartidasByItem($itemplan){
        $data = array();
        $arrayInputManoObras = array();       
    
        $html = '<div id="contTablaMaterialesList" class="table-responsive">';
        $listaMoDet    =   $this->m_validacion_integral->getPartidasByItemplan($itemplan);
        $html .= '<form id="formEditPartidas" method="get">
                <table class="table table-bordered" style="font-size: smaller;">
                <thead class="thead-default">
                <tr>
                    <th>PARTIDA</th>                    
                    <th>ESTACION</th>
                    <th>TIPO</th>
                    <th>PRECIO</th>
                    <th>CANTIDAD</th>
                    <th>TOTAL</th>
                    <th>CANTIDAD NUEVA</th>
                </tr>
                </thead>
    
                <tbody>';
        
        foreach($listaMoDet as $row){    
            $html .='<tr>
                        <td>'.$row->descripcion.'</td>
						<td>'.$row->estacionDesc.'</td>
						<td>'.$row->tipo_partida.'</td>
						<td>'.$row->monto.'</td>
						<td style="background-color:antiquewhite">'.$row->cantidad.'</td>
					    <td>'.$row->total.'</td>
				        <td><input name="'.$row->id_partida_itemplan.'" id="'.$row->id_partida_itemplan.'" placeholder="" style="border-bottom: 1px solid grey;"></td>
                    </tr>';
            $moValidator = array( 'name' => $row->id_partida_itemplan,
                'min'  => 0,
                'max'  => (($row->cantidad == null) ? 0 : $row->cantidad)
            );
            array_push($arrayInputManoObras, $moValidator);
        }
        $html .='</tbody>
                </table>
                <div id="mensajeFormMo"></div>
                <div class="form-group" style="text-align: right;">
                <div class="col-sm-12">
                <button data-itemplan="'.$itemplan.'" id="btnRegPartidas" type="submit" class="btn btn-primary">Save changes</button>
                </div>
                </div>
                </form>
                </div>';
        $data['html'] = utf8_decode($html);
        $data['valiManoObra']      = $arrayInputManoObras;
        return $data;
    }
    
    public function updateMoPartidas(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            if($this->session->userdata('idPersonaSession') == null){
                throw new Exception('Su Sesion ha caducado, favor vuelva a iniciar sesion.');
            }
            $itemplan = $this->input->post('itemplan');
            $arrayPartidas      =   array();
            $arrayLogEdit       =   array();
            $listaMoParti       =   $this->m_validacion_integral->getPartidasByItemplan($itemplan);           
            foreach($listaMoParti as $row){
                $cantidad_ingresa       = $this->input->post($row->id_partida_itemplan);
                $cantidad_final         = $row->cantidad;
                if($cantidad_ingresa != $cantidad_final && $cantidad_ingresa!= ''){    
                    $dataCMO = array();
                    $dataCMO['id_partida_itemplan']   = $row->id_partida_itemplan;
                    $dataCMO['cantidad']              = $cantidad_ingresa;
					$dataCMO['old_cantidad']          = $cantidad_final;
                    $dataCMO['total']                 = ($row->monto*$cantidad_ingresa);
                    array_push($arrayPartidas, $dataCMO);    
                    
                    $dataEdit = array();
                    $dataEdit['itemplan']             = $itemplan;
                    $dataEdit['id_partida_itemplan']  = $row->id_partida_itemplan;
                    $dataEdit['cantidad_actual']      = $cantidad_final;
                    $dataEdit['cantidad_nueva']       = $cantidad_ingresa;
                    $dataEdit['idUsuario']            = $this->session->userdata('idPersonaSession');
                    $dataEdit['fecha_registro']       = $this->fechaActual();
                    array_push($arrayLogEdit, $dataEdit);
                }
            }           
            $data = $this->m_validacion_integral->updatePartidasMO($arrayPartidas, $arrayLogEdit);
            $data['tablaData']     = $this->getTablaHojaGestion($this->m_validacion_integral->getObrasValidadosIntegral());
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone', 'America/Lima');
        setlocale(LC_TIME, "es_ES", "esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }
    
    public function validarObrasIntegral(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;
            if($idUsuario   !=     null){
                $itemplan       = $this->input->post('itemplan');
                $arraySolicitud         = array();
                $arrayItemXSolicitud    = array();
                log_message('error', $itemplan);
                $infoCreateSol      = $this->m_validacion_integral->getInfoSolCreacionByItem($itemplan);//getinfo solicitud de creacion
                if($infoCreateSol    ==  null){
                    throw new Exception('La obra no cuenta con Orden de Compra.');
                    /*
                    $arrayPoUpdate = array();
                    $arrayPoInserLogPo = array();
                    
    
                    $dataUpdateSolicitud = array (  'estado'            => 2,
                        'usua_val_nivel_2'  => $idUsuario,
                        'fec_val_nivel_2'   => $this->fechaActual(),
                        'itemplan'          =>  $itemplan,
                        'idEstacion'        =>  $idEstacion
                    );
    
    
                    $dataExpediente = array('estado_final'  => 'FINALIZADO',
                        'fecha_valida'  => $this->fechaActual() ,
                        'usuario_valida'=> $idUsuario);
    
                    $data = $this->m_pqt_terminado->validarEstacionFOPqt2NivelRutasSinOc($itemplan, $arrayPoInserLogPo, $arrayPoUpdate, $idEstacion, $dataUpdateSolicitud, $dataExpediente);*/
                }else{
                    $infoCertiEdicionOC = $this->m_validacion_integral->getInfoCostoByItemOfIntegral($itemplan);//costos mo
                    if($infoCertiEdicionOC    ==  null){
                        throw new Exception('No se pudo obtener los costos de MO para la obra.');
                    }
                    //sol edicion
                    $codigo_solicitud   = $this->m_validacion_integral->getNextCodigoSolicitud();//nuevo cod solicitud
                    if($codigo_solicitud    ==  null){
                        throw new Exception('No se pudo obtener el codigo de Solicitud refresque la pantalla y vuelva a intentarlo.');
                    }
    
                    $solicitud_oc_edi_certi = array('codigo_solicitud'  => $codigo_solicitud,
                                                    'idEmpresaColab'    =>  $infoCreateSol['idEmpresaColab'],
                                                    'estado'            =>  1,//pendiente
                                                    'fecha_creacion'    =>  $this->fechaActual(),
                                                    'idSubProyecto'     =>  $infoCreateSol['idSubProyecto'],
                                                    'plan'              =>  $infoCreateSol['plan'],
                                                    'pep1'              =>  $infoCreateSol['pep1'],
                                                    'pep2'              =>  $infoCreateSol['pep2'],
                                                    'cesta'             =>  $infoCreateSol['cesta'],
                                                    'orden_compra'      =>  $infoCreateSol['orden_compra'],
                                                    'estatus_solicitud' => 'NUEVO',
                                                    'tipo_solicitud'    =>  2//tipo edicion
                                
                                                );
                    array_push($arraySolicitud, $solicitud_oc_edi_certi);
    
                    $item_x_sol = array('itemplan'              =>   $itemplan,
                                        'codigo_solicitud_oc'   => $codigo_solicitud,
                                        'costo_unitario_mo'     => $infoCertiEdicionOC['total']
                                    );
    
                    array_push($arrayItemXSolicitud, $item_x_sol);
                    //sol certificacion
                    $codigo_solicitud_2   = $this->m_validacion_integral->getNextCodigoSolicitud();//nuevo cod solicitud
                    if($codigo_solicitud_2    ==  null){
                        throw new Exception('No se pudo obtener el codigo de Solicitud refresque la pantalla y vuelva a intentarlo.');
                    }
    
                    $solicitud_oc_edi_certi_2 = array(  'codigo_solicitud'  => $codigo_solicitud_2,
                                                        'idEmpresaColab'    =>  $infoCreateSol['idEmpresaColab'],
                                                        'estado'            =>  4,//pendiente
                                                        'fecha_creacion'    =>  $this->fechaActual(),
                                                        'idSubProyecto'     =>  $infoCreateSol['idSubProyecto'],
                                                        'plan'              =>  $infoCreateSol['plan'],
                                                        'pep1'              =>  $infoCreateSol['pep1'],
                                                        'pep2'              =>  $infoCreateSol['pep2'],
                                                        'cesta'             =>  $infoCreateSol['cesta'],
                                                        'orden_compra'      =>  $infoCreateSol['orden_compra'],
                                                        'estatus_solicitud' => 'NUEVO',
                                                        'tipo_solicitud'    =>  3//tipo certificacion                                    
                                                    );
                    array_push($arraySolicitud, $solicitud_oc_edi_certi_2);
    
                    $item_x_sol_2 = array('itemplan'            =>  $itemplan,
                                        'codigo_solicitud_oc'   =>  $codigo_solicitud_2,
                                        'costo_unitario_mo'     =>  $infoCertiEdicionOC['total']
                                    );
                    array_push($arrayItemXSolicitud, $item_x_sol_2);
    
                    if($infoCreateSol['idEstadoPlan']==ID_ESTADO_TERMINADO){//pasar a en certificacion
                        $updatePlanObra = array('idEstadoPlan'  => ID_ESTADO_EN_CERTIFICACION,
                            'usu_upd'       => $idUsuario,
                            'fecha_upd'     => $this->fechaActual(),
                            'descripcion'   => 'VALIDACION INTEGRALL',
                            'solicitud_oc_certi' => $codigo_solicitud_2,
                            'costo_unitario_mo_certi' => $infoCertiEdicionOC['total'],
                            'solicitud_oc_dev' => $codigo_solicitud,
                            'costo_devolucion'  =>  $infoCertiEdicionOC['total']);
                    }else{
                        $updatePlanObra = array(
                            'solicitud_oc_certi' => $codigo_solicitud_2,
                            'costo_unitario_mo_certi' => $infoCertiEdicionOC['total'],
                            'solicitud_oc_dev' => $codigo_solicitud,
                            'costo_devolucion'  =>  $infoCertiEdicionOC['total']);
                    }    
                    $data = $this->m_validacion_integral->validarIntegral($arraySolicitud, $arrayItemXSolicitud, $updatePlanObra, $itemplan);
                }
            }else{
                throw new Exception('Su sesion expiro, porfavor vuelva a logearse.');
            }
             
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
}