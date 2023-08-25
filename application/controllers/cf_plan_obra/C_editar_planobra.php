<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 *
 */
class C_editar_planobra extends CI_Controller {

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
               $data['listaEstados'] = $this->m_utils->getEstadosItemplan();
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
        	   $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_PLAN_DE_OBRA, ID_PERMISO_HIJO_EDIT_OBRA);
        	   $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	         $this->load->view('vf_plan_obra/v_editar_planobra_lite',$data);
        	   }else{
        	       redirect('login','refresh');
        	   }
    	 }else{
            log_message('error', '-->editar plan obra');
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
            foreach($listaPTR->result() as $row){
                $boton="";
                $estilo="";



                if($row->idEstadoPlan!=5){
                       $boton='<button class="btn btn-warning" data-toggle="modal"  data-id="'.$row->itemPlan.'" data-idsubpro="'.$row->idSubProyecto.'"  data-target="#modal-large" onclick="editItemPlan(this)"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/editar.ico"></button>';

                        $estilo=' style="background-color: var(--celeste_telefonica3);"';
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


/************************************************* EDIT PLAN OBRA LITE **********************************************/
 public function getInfoItemPlanEditlite(){
              
   
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
              
        try{
            $itemplan = $this->input->post('id');
                      
             $resultado = $this->m_editar_planobra->getInfoEditItemplanlite($itemplan);

             $data['indicador']=$resultado["indicador"];
             $data['nombreRealProyecto']=$resultado["nombreRealProyecto"];
             $data['nombreproyecto']=$resultado["nombreproyecto"];
             $data['idfase']=$resultado["idfase"];
             $data['cantidadTroba']=$resultado["cantidadTroba"];
             $data['uip']=$resultado["uip"];
             $data['coordY']=$resultado["coordY"];
             $data['coordX']=$resultado["coordX"];
             $data['idEmpresaElec']=$resultado["idEmpresaElec"];

             $data['idCentral']=$resultado["idCentral"];
             $data['idZonal']=$resultado["idZonal"];

             $data['empresaColabDesc']=$resultado["empresaColabDesc"];
             $data['idEmpresaColabDiseno']=$resultado["idEmpresaColabDiseno"];

             $this->session->set_flashdata('iditemplan',$itemplan);
              $data['error']    = EXIT_SUCCESS;

        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }

        echo json_encode(array_map('utf8_encode', $data));
    }


public function editPlanObralite(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            
            $indicador =$this->input->post('inputIndicador');
            $fase =$this->input->post('selectFase');
            $coordX =$this->input->post('inputCoordX');
            $coordY =$this->input->post('inputCoordY');
            $nombrePlan =$this->input->post('inputNombrePlan');
            $eelec =$this->input->post('selectEmpresaEle');
            $cantobra =$this->input->post('inputCantObra');
            $uip =$this->input->post('inputUIP');

            $idcentral= $this->input->post('selectCentral');
            $idzonal= $this->input->post('selectZonalEdit');

            $idEmpresaColabN = $this->input->post('idEmpresaColab');

            // $idEmpresaColabN=$this->m_editar_planobra->getEmpresaColabBucle($idcentral);

            $idEmpresaColabDis= $this->input->post('selectEECCDISEdit');

            $flagChngIndica=$this->input->post('flagCIndica'); 


            $itemplan =$this->input->post('id');

           
            $data = $this->m_editar_planobra->editarPlanObralite($itemplan,$indicador,$fase, $coordX,
                                                                    $coordY,$nombrePlan,$eelec,$cantobra,$uip,$idcentral,$idzonal,$idEmpresaColabN, $idEmpresaColabDis);
            
            if($data['error']==EXIT_ERROR){
                throw new Exception('Error al editar permiso por perfil');
            }else{
                    
                     $modificaciones="indicador=".$indicador."|fase=".$fase."|coordX=".$coordX."|coordY=".$coordY.
                    "|nombrePlan=".$nombrePlan."|eelec=".$eelec."|cantobra=".$cantobra."|uip=".$uip."|idcentral=".$idcentral.
                    "|idzonal=".$idzonal."|idEmpresaColab=".$idEmpresaColabN."|idEmpresaColabDise�«Ðo=".$idEmpresaColabDis;

                    $idusuario = $this->session->userdata('idPersonaSession');
                    $itemp=$data['itemplan'];
                    $indicador=$data['indicador'];

                    $dataLogPO=$this->m_editar_planobra->insertarLogPlanObra($itemp,$idusuario, $modificaciones,'planobra','','');

                    if($dataLogPO['error']==EXIT_ERROR){
                        throw new Exception('Error al INGRESAR el log de Plan Obra');
                    }

                    if ($flagChngIndica!=0){
                        $resultad=$this->m_editar_planobra->getInfoEditWUDetlite($itemp);
                        if ($resultad!=null){
                                $dataWUDET=$this->m_editar_planobra->editarWebUnificadalite($itemp,$indicador);

                                if($dataWUDET['error']==EXIT_ERROR){
                                    throw new Exception('Error al editar la web_unificada_det el indicador');
                                }else{
                                    $modificacioneswud="indicador=".$indicador;
                                   
                                    foreach($resultad->result()  as $row){
                                        $ptr=$row->ptr;
                                        $dataLogPOWUD=$this->m_editar_planobra->insertarLogPlanObra($itemp,$idusuario, 
                                                                                        '','web_unificada_det',
                                                                                        $ptr,$modificacioneswud);
                                        if($dataLogPOWUD['error']==EXIT_ERROR){
                                            throw new Exception('Error al INGRESAR el log de PlanObra-web_unificada_det');
                                        }
                                    }
                                }
                             
                        }
                         
                    }
            }
            $data['tablaEditItemplan'] = $this->makeHTLMTablaEditItemPlan('');
            $data['itemplanmodificado'] = $itemplan;
        
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

/**************************************************************************************************************************/




public function getHTMLZonalEditlite(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $idCentral = $this->input->post('central');
            $listaZonal = $this->m_utils->getZonalXCentral($idCentral);
            $html = '';
            $idzonalselect='';
            foreach($listaZonal->result() as $row){
                $html .= '<option value="'.$row->idzonal.'">'.$row->zonalDesc.'</option>';
                $idzonalselect=$row->idzonal;
            }
           
            $data['listaZonal'] = $html;
            $data['idZonalSelec'] = $idzonalselect;
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }


public function getHTMLEECCEditlite(){
         $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $idCentral = $this->input->post('central');
            $idSubProyecto = $this->input->post('idsuproc');

            $itemplan = $this->input->post('itemplan');
            
				$flg_paquetizado = $this->m_utils->getFlgPaquetizadoPo($itemplan);
			
				if($flg_paquetizado == 2) {
					$listaEECC = $this->m_utils->getEECCXCentralXSubProyectoPqt($itemplan);
				} else {
					$listaEECC = $this->m_utils->getEECCXCentralXSubProyecto($idCentral);
				}
                $EECCselect = NULL;
				$EECCidselect = NULL;
                foreach($listaEECC->result() as $row){
                    if($idSubProyecto == 97){
                        $EECCselect = $row->empresaColabCV;
                        $EECCidselect = $row->idEmpresaColabCV;
                    }else{
                        $EECCselect = $row->empresaColabDesc;
                        $EECCidselect = $row->idEmpresaColab;
                    }
                    
                }
                 $data['EECCselect'] = $EECCselect;
                 $data['EECCidselect'] = $EECCidselect;
           
            
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

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



}