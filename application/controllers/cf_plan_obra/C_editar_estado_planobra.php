<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 *
 */
class C_editar_estado_planobra extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_plan_obra/m_editar_planobra');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }
    
	public function index(){
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
            
    	       $data['listaEECC'] = $this->m_utils->getAllEECC();
               $data['listaNodos'] = $this->m_utils->getAllNodos();
               $data['listaProyectos'] = '';
               //$data['listaEstados'] = $this->m_utils->getEstadosItemplan();
               $data['listaEstados'] = $this->m_utils->getEstadosModItemplan();

               $data['listaTipoPlanta'] = $this->m_utils->getAllTipoPlantal();
               $data['listaZonal'] = $this->m_editar_planobra->getAllZonal();
               $data['listaeelec'] = $this->m_utils->getAllEELEC();
               $data['listafase'] = $this->m_utils->getAllFase();
               $data['listaTiCen'] = $this->m_utils->getAllCentral();//NUEVO
               

               // Trayendo zonas permitidas al usuario
               $zonas = $this->session->userdata('zonasSession');        
               $data['listaSubProy'] = '';
               $data['tablaEditItemplan'] = '';
               $data['tablaEditItemplan'] = $this->makeHTLMTablaEditItemPlan('');
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
               $permisos =  $this->session->userdata('permisosArbol');
        	   $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_PLAN_DE_OBRA, ID_PERMISO_HIJO_EDIT_ESTADO_OBRA);
        	   $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	         $this->load->view('vf_plan_obra/v_editar_estado_planobra',$data);
        	   }else{
        	       redirect('login','refresh');
        	   }
    	 }else{
            
        	 redirect('login','refresh');
	    }
             
    }
    
   public function makeHTLMTablaEditItemPlan($listaPTR){
     
        $html = '
                <table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th></th>
                            <th>ITEMPLAN</th>
                            <th>SUBPROYECTO</th>                            
                            <th>NOMBRE</th>
                            <th>MDF/NODO</th>
                            <th>ZONAL</th>
                            <th>EECC</th>
                            <th>FEC. INICIO</th>
                            <th>FEC. PREV. EJECUCION</th>
                            <th>FEC EJEC.</th>
                            <th>ESTADO</th>                         
                        </tr>
                    </thead>
                    
                    <tbody>';
        if($listaPTR != ''){


            $idvar=1;

            foreach($listaPTR->result() as $row){
                $boton="";
                $estilo="";
                $combobox="";
                $idcombobox="";

                
/*|| $row->idEstadoPlan==6 se retiro el estado cancelado para la edicion*/
                if($row->idEstadoPlan==1 || $row->idEstadoPlan==2 || $row->idEstadoPlan==7 || $row->idEstadoPlan==3 || $row->idEstadoPlan==6 || $row->idEstadoPlan==10 ){

                         $boton='<button class="btn btn-warning" data-toggle="modal"  data-id="'.$row->itemPlan.'" data-id_proyecto="'.$row->idProyecto.'" data-target="#modal-large" onclick="editEstadoItemPlan(this)"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/editar.ico"></button>';

                        $estilo=' style="color:white; background-color: #7cbcb4;"';
               }  
               
               
               
                    
                $html .=' 
                        <tr '.$estilo.'>
                            <td>'.$boton.'</td>
                            <td>'.$row->itemPlan.'</td>
                            <td>'.$row->subProyectoDesc.'</td>  
                            <td>'.$row->nombreProyecto.'</td>
                            <td>'.$row->codigo.'-'.$row->tipoCentralDesc.'</td>
                            <td>'.$row->zonalDesc.'</td>
                            <td>'.$row->empresaColabDesc.'</td>
                            <th>'.$row->fechaInicio.'</th>
                            <th>'.$row->fechaPrevEjec.'</th> 
                            <th>'.$row->fechaEjecucion.'</th>
                            <th>'.$row->estadoPlanDesc.'</th>     
                        </tr>
                        ';
                 }
             $html .='</tbody>
                </table>';

        }else{
            $html .= '</tbody>
                </table>';
        }
		   																			                                                   
                
                    
        return utf8_decode($html);
    }

    function filtrarTabla(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{

            //$zonas = $this->session->userdata('zonasSession');
            $itemPlan = $this->input->post('itemplan');
            $nombreproyecto = $this->input->post('nombreproyecto');
            $nodo = $this->input->post('nodo');
            $zonal = $this->input->post('zonal');
            $proy = $this->input->post('proy');
            $subProy = $this->input->post('subProy');
            $estado = $this->input->post('estado');
            $tipoPlanta = $this->input->post('tipoPlanta');
            //$selectMesPrevEjec = $this->input->post('selectMesPrevEjec');
            $filtroPrevEjec = $this->input->post('filtroPrevEjec');
            
             $data['tablaEditItemplan'] = $this->makeHTLMTablaEditItemPlan($this->m_editar_planobra->getConsultaEditItemPlan($itemPlan,$nombreproyecto,$nodo,$zonal,$proy,$subProy,$estado,$filtroPrevEjec,$tipoPlanta));
            
            $data['error']    = EXIT_SUCCESS;            
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }



public function getInfoItemPlanEditEstado(){
              
   
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
              
        try{
            $itemplan = $this->input->post('id');
            $htmlEstados="<option>&nbsp;</option>";
            $htmlMotivos="<option>&nbsp;</option>";
            $resultado = $this->m_editar_planobra->getInfoEditItemplanEstado($itemplan);

             $obtenielistaEstados=$this->m_utils->getEstadosModItemplan();
             $obtenielistaMotivos=$this->m_utils->getMotivoAllByOrigen(2);

             foreach($obtenielistaEstados->result() as $row){
                 if($row->idEstadoPlan == ID_ESTADO_CANCELADO){//SOLO PARA EL ESTADO CANCELADO SE VALIDA LOS SIGUIENTES ESTADOS
                     if($resultado['idestado'] == ESTADO_PLAN_PRE_DISENO || $resultado['idestado'] == ESTADO_PLAN_DISENO  ||
                         $resultado['idestado'] == ESTADO_PLAN_EN_OBRA  ||  $resultado['idestado'] == ESTADO_PLAN_DISENO_EJECUTADO){
                         $htmlEstados.="<option value=".$row->idEstadoPlan.">".utf8_decode($row->estadoPlanDesc)."</option>";
                     }
                 }
                 /*if($resultado['idestado'] == 10) {
                     if($row->idEstadoPlan == 3) {
                        $htmlEstados.="<option value=".$row->idEstadoPlan.">".utf8_decode($row->estadoPlanDesc)."</option>";
                     }
                      
                 } else {
                        if($row->idEstadoPlan == ID_ESTADO_CANCELADO){//SOLO PARA EL ESTADO CANCELADO SE VALIDA LOS SIGUIENTES ESTADOS
                           if($resultado['idestado'] == ESTADO_PLAN_PRE_DISENO || $resultado['idestado'] == ESTADO_PLAN_DISENO  ||
                               $resultado['idestado'] == ESTADO_PLAN_EN_OBRA  ||  $resultado['idestado'] == ESTADO_PLAN_DISENO_EJECUTADO){
                                $htmlEstados.="<option value=".$row->idEstadoPlan.">".utf8_decode($row->estadoPlanDesc)."</option>";
                           }
                        }else{
                            $htmlEstados.="<option value=".$row->idEstadoPlan.">".utf8_decode($row->estadoPlanDesc)."</option>";
                        }
                 }
                 */
             
             }


             foreach($obtenielistaMotivos as $row){ 
                $htmlMotivos.="<option value=".$row->idMotivo.">".strtoupper (utf8_decode($row->motivoDesc))."</option>";
             }



            $data['lispopupestados'] = $htmlEstados;
            $data['listaMotivo'] = $htmlMotivos;
             $data['estadotexto']=$resultado["estado"];
             $data['idestado']=$resultado["idestado"];

             $this->session->set_flashdata('iditemplan',$itemplan);
              $data['error']    = EXIT_SUCCESS;

        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }

        echo json_encode(array_map('utf8_encode', $data));
    }

/************************************************* EDIT estado PLAN OBRA **********************************************/

public function editPlanobraEstado(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{

             $antiguoestado = $this->input->post('inputIDEstado');
             $nuevoestado   = $this->input->post('estadoN');
             $itemplan      = $this->input->post('inputItemPlan');
             $motivo        = $this->input->post('motivo');
             $observaciones = $this->input->post('observaciones');
             $idProyecto    = $this->input->post('idProyecto');
             $motivoDesc    = $this->input->post('motivoDesc');

            if($idProyecto == null) {
                throw new Exception('error comunicarse con el programador a cargo');
            }
            
             if ($nuevoestado==1){//va a prediseño
                    //elimina registros en pre_diseño
                    $data = $this->m_editar_planobra->eliminarPreDiseno($itemplan);

                   if($data['error']==EXIT_ERROR){
                        throw new Exception('Error al eliminar registro pre diseno');
                   }else{
                        //verificar si cuenta con avance en las estaciones
                        $conteodato=$this->m_editar_planobra->verificarItemPlanEstacionAvance($itemplan);

                        if ($conteodato>0){
                             //poner registros en 0 porcentaje en itemplanestacionavance
                             $data1 = $this->m_editar_planobra->modificaPorcentajeIPEAvance($itemplan);
                            if($data1['error']==EXIT_ERROR){
                                 throw new Exception('Error al modificar los porcentajes');
                            }else{
                                //registrar en log_cuadrilla
                                 $data2 = $this->m_editar_planobra->insertarLogPorcentajeIPEAvance($itemplan);
                                  if($data2['error']==EXIT_ERROR){
                                    throw new Exception('Error al insertar log cuadrilla los porcentajes');
                                  }
                            }
                        }
                        //cambio de estado
                        $dataPO=$this->m_editar_planobra->editarPlanObraEstado($itemplan,$nuevoestado);

                        if($dataPO['error']==EXIT_ERROR){
                             throw new Exception('Error al modificar el itemplan');
                        }else{
                            //registrar en el log
                            $modificaciones="idEstadoPlan=1|fechaCancelacion:null|fechaTermino:null|fechaEjecucion:null|fechaPreLiquidacion:null|motivoCambioEstado=".$observaciones;

                            $idusuario = $this->session->userdata('idPersonaSession');
                            $itemp=$dataPO['itemplan'];

                            $dataLogPO=$this->m_editar_planobra->insertarLogPlanObra($itemp,$idusuario, $modificaciones,'planobra','','');

                            if($dataLogPO['error']==EXIT_ERROR){
                                throw new Exception('Error al INGRESAR el log de Plan Obra');
                            }else{
                                //registrar en planobra cancelar
                                $data=$this->m_editar_planobra->insertarLogplanobra_cancelar($itemp, $motivo,$observaciones,$nuevoestado, $idusuario);
                                if($data['error']==EXIT_ERROR){
                                    throw new Exception('Error al INGRESAR el log de Plan Obra cancelar');
                                }else{
                                    $data['itemplanmodificado']=$itemp;
                                }
                            }
                        }
                   }
             }

            if ($nuevoestado==2){//va a diseño
                //si tiene pre_diseno, 
                $conteoPred=$this->m_editar_planobra->verificarExistePreDiseno($itemplan);

                if ($conteoPred>0){
                        //modificar los datos para que pueda ser ejecutado
                       $data = $this->m_editar_planobra->modificaPreDiseno($itemplan);
                       if($data['error']==EXIT_ERROR){
                            throw new Exception('Error al modificar los porcentajes');
                       }
                }

                 $conteodato=$this->m_editar_planobra->verificarItemPlanEstacionAvance($itemplan);

                if ($conteodato>0){
                    $data1 = $this->m_editar_planobra->modificaPorcentajeIPEAvance($itemplan);
                    if($data1['error']==EXIT_ERROR){
                        throw new Exception('Error al modificar los porcentajes');
                    }else{
                    //registrar en log_cuadrilla
                        $data2 = $this->m_editar_planobra->insertarLogPorcentajeIPEAvance($itemplan);
                        if($data2['error']==EXIT_ERROR){
                            throw new Exception('Error al insertar log cuadrilla los porcentajes');
                        }
                    }
                }


                //cambio de estado
                $dataPO=$this->m_editar_planobra->editarPlanObraEstado($itemplan,$nuevoestado);

                if($dataPO['error']==EXIT_ERROR){
                    throw new Exception('Error al modificar el itemplan');
                }else{
                    //registrar en el log
                    $modificaciones="idEstadoPlan=2|fechaCancelacion:null|fechaTermino:null|fechaEjecucion:null|fechaPreLiquidacion:null|motivoCambioEstado=".$observaciones;

                    $idusuario = $this->session->userdata('idPersonaSession');
                    $itemp=$dataPO['itemplan'];

                    $dataLogPO=$this->m_editar_planobra->insertarLogPlanObra($itemp,$idusuario, $modificaciones,'planobra','','');

                    if($dataLogPO['error']==EXIT_ERROR){
                        throw new Exception('Error al INGRESAR el log de Plan Obra');
                    }else{
                          //registrar en planobra cancelar
                            $data=$this->m_editar_planobra->insertarLogplanobra_cancelar($itemp, $motivo,$observaciones,$nuevoestado, $idusuario);
                            if($data['error']==EXIT_ERROR){
                                throw new Exception('Error al INGRESAR el log de Plan Obra cancelar');
                            }else{
                                $data['itemplanmodificado']=$itemp;
                            }
                    }
                }
            }

            if ($nuevoestado==3){//va a obra

                $conteodato=$this->m_editar_planobra->verificarItemPlanEstacionAvance($itemplan);

                if ($conteodato>0){
                      //poner registros en 0 porcentaje en itemplanestacionavance
                     $data1 = $this->m_editar_planobra->modificaPorcentajeIPEAvance($itemplan);

                    if($data1['error']==EXIT_ERROR){
                        throw new Exception('Error al modificar los porcentajes');
                    }else{
                        //registrar en log_cuadrilla
                        $data2 = $this->m_editar_planobra->insertarLogPorcentajeIPEAvance($itemplan);
                        if($data2['error']==EXIT_ERROR){
                            throw new Exception('Error al insertar log cuadrilla los porcentajes');
                        }
                    }

                }

                //cambio de estado
                $dataPO=$this->m_editar_planobra->editarPlanObraEstado($itemplan,$nuevoestado);

                if($dataPO['error']==EXIT_ERROR){
                    throw new Exception('Error al modificar el itemplan');
                }else{
                    //registrar en el log
                    $modificaciones="idEstadoPlan=3|fechaCancelacion:null|fechaTermino:null|fechaEjecucion:null|fechaPreLiquidacion:null|motivoCambioEstado=".$observaciones;

                    $idusuario = $this->session->userdata('idPersonaSession');
                    $itemp=$dataPO['itemplan'];

                    $dataLogPO=$this->m_editar_planobra->insertarLogPlanObra($itemp,$idusuario, $modificaciones,'planobra','','');

                    if($dataLogPO['error']==EXIT_ERROR){
                        throw new Exception('Error al INGRESAR el log de Plan Obra');
                    }else{
                      //registrar en planobra cancelar
                        $data=$this->m_editar_planobra->insertarLogplanobra_cancelar($itemp, $motivo,$observaciones,$nuevoestado, $idusuario);
                        if($data['error']==EXIT_ERROR){
                            throw new Exception('Error al INGRESAR el log de Plan Obra cancelar');
                        }else{
                            $data['itemplanmodificado']=$itemp;
                        }
                    }
                }
            }

            if ($nuevoestado==6){//va a cancelado
                //cambio de estado
                $dataPO=$this->m_editar_planobra->editarPlanObraEstado($itemplan,$nuevoestado);

                if($dataPO['error']==EXIT_ERROR){
                    throw new Exception('Error al modificar el itemplan');
                }else{
                    //registrar en el log
                    $modificaciones="idEstadoPlan=6|fechaCancelacion:null|fechaTermino:null|fechaEjecucion:null|fechaPreLiquidacion:null|motivoCambioEstado=".$observaciones;

                    $idusuario = $this->session->userdata('idPersonaSession');
                    $itemp=$dataPO['itemplan'];

                    $dataLogPO=$this->m_editar_planobra->insertarLogPlanObra($itemp,$idusuario, $modificaciones,'planobra','','');

                    if($dataLogPO['error']==EXIT_ERROR){
                        throw new Exception('Error al INGRESAR el log de Plan Obra');
                    }else{
                        if($idProyecto == 3) {
							$arrayDataLog = array(
                                    'tabla'            => 'web PO',
                                    'actividad'        => 'Cancelar Obra',
                                    'itemplan_default' => 'idEstado:6',
                                    'itemplan'         => $itemplan,
                                    'fecha_registro'   => $this->fechaActual(),
                                    'id_usuario'       => $this->session->userdata('idPersonaSession'),
                                    'idMotivo'         => $motivo,
                                    'comentario'       => $observaciones 
                                 );

                            $this->m_utils->registrarLogPlanObra($arrayDataLog);
                            
                            $dataSend = ['itemplan' => $itemplan,
                                         'fecha'    => $this->fechaActual(),
                                         'estado'   => FLG_CANCELACION_CONFIRMADA,
                                         'motivo'   => $motivoDesc ];
                
                            $url = 'https://172.30.5.10:8080/obras2/recibir_can.php';
                
                            $data = _trama_sisego($dataSend, $url, 10, $itemplan, 'BANDEJA CANCELACION', NULL); 
                        }
                        //registrar en planobra cancelar
                        $data=$this->m_editar_planobra->insertarLogplanobra_cancelar($itemp, $motivo,$observaciones,$nuevoestado, $idusuario);
                        if($data['error']==EXIT_ERROR){
                            throw new Exception('Error al INGRESAR el log de Plan Obra cancelar');
                        }else{
                            $data['itemplanmodificado']=$itemp;
                        }
                        
                        /**10.07.2019 RFP CANCELACION ITEMPLAN CZAVALACAS ***/
                        log_message('error', 'ESTADO ACTUAL:'.$antiguoestado);
                        if($antiguoestado == ESTADO_PLAN_PRE_DISENO || $antiguoestado == ESTADO_PLAN_DISENO){//SE CANCELAN TODAS LAS PO AUTOMATICAMENTE.
                                $listaPo = $this->m_editar_planobra->getAllPoByItemplan($itemplan);
                                $updateDataPo = array();
                                $insertDataLog = array();
                                $listaPoCancelar = array();
                                
                                foreach($listaPo as $row){
                                    
                                    $dataUp = array('estado_po' =>  (($row->flg_tipo_area   == TIPO_PO_MANO_OBRA) ? PO_CANCELADO : PO_PRECANCELADO),
                                                    'codigo_po' =>  $row->codigo_po
                                    );
                                    array_push($updateDataPo, $dataUp);
                                    
                                    $dataIn = array('codigo_po' =>  $row->codigo_po,
                                                    'itemplan' =>  $row->itemplan,
                                                    'idUsuario' =>  $this->session->userdata('idPersonaSession'),
                                                    'fecha_registro' => $this->fechaActual(),
                                                    'idPoestado'    =>  (($row->flg_tipo_area   == TIPO_PO_MANO_OBRA) ? PO_CANCELADO : PO_PRECANCELADO),
                                                    'controlador'   => 'EDITAR ESTADO PLAN'
                                    );
                                    array_push($insertDataLog, $dataIn);
                                    
                                    $dataInPoCan = array('codigo_po' =>  $row->codigo_po,
                                        'itemplan' =>  $row->itemplan,
                                        'idMotivo'  =>  $motivo,//motivo CANCELADO
                                        'observacion'   =>  $observaciones,
                                        'id_usuario' =>  $this->session->userdata('idPersonaSession'),
                                        'fecha_registro' => $this->fechaActual(),
                                        'idPoestado'    =>  (($row->flg_tipo_area   == TIPO_PO_MANO_OBRA) ? PO_CANCELADO : PO_PRECANCELADO)
                                    );
                                    
                                    array_push($listaPoCancelar, $dataInPoCan);
                                }            
                                $data = $this->m_utils->cambiarEstadoPoMasivo($updateDataPo, $insertDataLog);
                                if($data['error']==EXIT_ERROR){
                                    throw new Exception('Error al cancelar PO');
                                }else{
                                    $data = $this->m_editar_planobra->insertPoCancelar($listaPoCancelar);
                                }
                        }else if($antiguoestado == ESTADO_PLAN_DISENO_EJECUTADO || $antiguoestado == ESTADO_PLAN_EN_OBRA){
                            $listaPo = $this->m_editar_planobra->getAllPoByItemplan($itemplan);
                            $updateDataPo = array();
                            $insertDataLog = array();
                            $listaPoCancelar = array();
                            foreach($listaPo as $row){
                                if($row->idEstacion != ID_ESTACION_DISENIO){//SOLO SI NO ES DISENO SE CANCELA Y PRE CANCELA
                                    
                                    $dataUp = array('estado_po' =>  (($row->flg_tipo_area   == TIPO_PO_MANO_OBRA) ? PO_CANCELADO : PO_PRECANCELADO),
                                                    'codigo_po' =>  $row->codigo_po
                                    );
                                    array_push($updateDataPo, $dataUp);
                                    
                                    $dataIn = array('codigo_po' =>  $row->codigo_po,
                                                    'itemplan' =>  $row->itemplan,
                                                    'idUsuario' =>  $this->session->userdata('idPersonaSession'),
                                                    'fecha_registro' => $this->fechaActual(),
                                                    'idPoestado'    =>  (($row->flg_tipo_area   == TIPO_PO_MANO_OBRA) ? PO_CANCELADO : PO_PRECANCELADO),
                                                    'controlador'   => 'EDITAR ESTADO PLAN'
                                    );
                                    array_push($insertDataLog, $dataIn);
                                    
                                    $dataInPoCan = array('codigo_po' =>  $row->codigo_po,
                                                        'itemplan' =>  $row->itemplan,
                                                        'idMotivo'  =>  $motivo,//motivo CANCELADO
                                                        'observacion'   =>  $observaciones,
                                                        'id_usuario' =>  $this->session->userdata('idPersonaSession'),
                                                        'fecha_registro' => $this->fechaActual(),
                                                        'idPoestado'    =>  (($row->flg_tipo_area   == TIPO_PO_MANO_OBRA) ? PO_CANCELADO : PO_PRECANCELADO)
                                    );                                    
                                    array_push($listaPoCancelar, $dataInPoCan);
                                }
                            }
                            $data = $this->m_utils->cambiarEstadoPoMasivo($updateDataPo, $insertDataLog);
                            if($data['error']==EXIT_ERROR){
                                throw new Exception('Error al cancelar PO');
                            }else{
                                $data = $this->m_editar_planobra->insertPoCancelar($listaPoCancelar);
                            }                      
                        }
                        
                        //$this->m_utils->updateEstadoPOByItemplan($itemplan, 7);
                    }
                }
            }
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

/**************************************************************************************************************************/
    public function getHTMLProyectoConsulta(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $idplanta = $this->input->post('tipoplanta');
            $listaProy = $this->m_utils->getProyectoxTipoPlanta($idplanta);
            $html = '<option>&nbsp;</option>';
            foreach($listaProy->result() as $row){
                $html .= '<option value="'.$row->idproyecto.'">'.$row->proyectoDesc.'</option>';
            }
            $data['listaProyectos'] = $html;
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    
    public function getHTMLSubProyectoConsulta(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $idProyecto = $this->input->post('proyecto');

            $listaSubProy = $this->m_utils->getAllSubProyectoByProyecto($idProyecto);
            $html = '<option>&nbsp;</option>';
            foreach($listaSubProy->result() as $row){
                $html .= '<option value="'.$row->idSubProyecto.'">'.$row->subProyectoDesc.'</option>';
            }
            $data['listaSubProy'] = $html;
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone','America/Lima');
        setlocale(LC_TIME, "es_ES","esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }




}