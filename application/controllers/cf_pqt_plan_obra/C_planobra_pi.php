<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * 
 * 
 *
 */
class C_planobra_pi extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_plan_obra/m_planobra_pi');
        $this->load->model('mf_plan_obra/m_planobra');
        //$this->load->model('mf_pre_diseno/m_bandeja_adjudicacion');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }
    
	public function index()
	{  	   
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
                /*carga de la tabla */           
               //$data['listartabla'] = $this->makeHTMLTablaCentral($this->m_planobra->getAllPlanesObra());
                /*carga de proyecto*/
               /*********miguel rios 13062018***********/
               /***$data['listaProy'] = $this->m_utils->getAllProyecto();***/
                $data['listaProy'] = $this->m_utils->getAllProyectoPI();
               /****************************************/
               $data['listaTiCen'] = $this->m_utils->getAllCentral();
			   log_message('error','test C_planobra_pi');
              /*carga de empresas electricas*/
               $data['listaeelec'] = $this->m_utils->getAllEELEC();
     
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');	
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');	               
        	   $permisos =  $this->session->userdata('permisosArbol');
               // permiso para registro individual modificar
        	   $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_PLANTA_INTERNA, ID_PERMISO_HIJO_REGINDPI_OBRA);
        	   
               $data['opciones'] = $result['html'];
        	   $this->load->view('vf_plan_obra/v_registro_individual_pi',$data);
        	   
	   }else{
	       redirect('login','refresh');
	   }
    }
   
    
    public function makeHTMLTablaCentral($listartabla){
     
        $html = '
        <table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>Itemplan</th>                            
                            <th>Nombre Plan</th>
                            <th>Subproyecto</th>
                            <th>Central</th>
                            <th>Zonal</th>
                            <th>EECC</th>
                            <th>fecha Inicio</th>
                            <th>fecha PrevEjecucion</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>';
		   																			                           
                foreach($listartabla->result() as $row){ 
                    
                $html .=' <tr>
							<td>'.$row->ItemPlan.'</td> 
                            <td>'.$row->Nombre.'</td> 
                            <td>'.$row->Subproyecto.'</td>                                 
                            <td>'.$row->Central.'</td>
                            <td>'.$row->Zonal.'</td>
                            <td>'.$row->EmpresaColab.'</td>
                            <td>'.$row->fechaInicio.'</td>
                            <td>'.$row->fechaPreviaEjecucion.'</td>
                            <td>'.$row->Estado.'</td>      
                                               
						</tr>';
                 }
			 $html .='</tbody>
                </table>';
                    
        return utf8_decode($html);
    }
    
    public function createPlanobraPI(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $idProy     = $this->input->post('selectProy');
            $idSubproy  = $this->input->post('selectSubproy');
            $idCentral  = $this->input->post('selectCentral');
            $idzonal    = $this->input->post('selectZonal');
            $eecc       = $this->input->post('selectEmpresaColab');
            $eelec      = $this->input->post('selectEmpresaEle');
            $estadoplan = 1;
           
            $indicador   = $this->input->post('inputIndicador');
     
            $fechaInicio = $this->input->post('inputFechaInicio');
            $nombreplan  = $this->input->post('inputNombrePlan');
            $itemplanPE  = $this->input->post('inputItemPlanPE');
          
             $fase = $this->m_utils->getCodigoFase($this->input->post('inputFase'));

             if ($fase==''){
                $fase=1;
             }

             

            $this->m_planobra->deleteLogImportPlanObraSub();

            $itemplan=$this->m_planobra->generarCodigoItemPlan($idProy,$idzonal);

/*
             $time_end = microtime(true);
             $time_total = $time_end - $time_start;

             $time_start = microtime(true);
             */


            $data = $this->m_planobra_pi->insertarPlanobraPI($itemplan,$idProy, $idSubproy, $idCentral, $idzonal, $eecc, $eelec, $estadoplan, $fase, $fechaInicio, $nombreplan,$indicador,$itemplanPE);

/*
            $time_end = microtime(true);
             $time_total = $time_end - $time_start;
*/
            
            if($data['error']==EXIT_ERROR){
                throw new Exception('Error al Insertar planobra');
            }else{
                $itemplanData= $this->m_planobra->obtenerUltimoRegistro();
                
                $data2 =$this->m_planobra->insertarLogPlanObra($itemplanData,$this->session->userdata('idPersonaSession'), ID_TIPO_PLANTA_INTERNA);
                if($data2['error']==EXIT_ERROR){
                    throw new Exception('Error al Insertar en el log de planobra');
                }
                $data['itemplannuevo']=$itemplanData;

                  $textmensaje='<div class="alert alert-success" role="alert">
                                Se registro el plan de obra con número : <strong>'.$itemplanData.'</strong>.
                            </div>';

                $data['notify']=utf8_decode($textmensaje);


            }
            
            //$data['listartabla'] = $this->makeHTMLTablaCentral($this->m_planobra->getAllPlanesObra());
            
    
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    /*ENLAZA CON VIEW PARA ENVIAR A MODEL Y recibir DATOS DEL ITEMPLAN PARA LA EDICION*/
  
    public function getHTMLChoiceSubProyPI(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $idProyecto = $this->input->post('proyecto');
            $listaSubProy = $this->m_utils->getAllSubProyectoByProyecto($idProyecto);
            $html = '';
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
    
     public function getFechaPreEjecuCalculoPI(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        
        try{
            $fechaInicio = $this->input->post('fecha');
            $subproy = $this->input->post('subproyecto');
          
            $fechaCalculado = $this->m_utils->getCalculoTiempoSubproyecto($fechaInicio,$subproy);
            
            $data['fechaCalculado'] = $fechaCalculado;
            $data['anioFase'] = date('Y',strtotime($fechaCalculado));

            $data['error']    = EXIT_SUCCESS;

        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }


    public function getHTMLChoiceZonalPI(){
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

    public function getHTMLChoiceEECCPI(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $idCentral = $this->input->post('central');
            $listaEECC = $this->m_utils->getEECCXCentral($idCentral);
            $html = '';
            $idEECCselect='';
            foreach($listaEECC->result() as $row){
                $html .= '<option value="'.$row->idEmpresaColab.'">'.$row->empresaColabDesc.'</option>';
                 $idEECCselect=$row->idEmpresaColab;
            }
            $data['listaEECC'] = $html;
            $data['idEECCSelec'] = $idEECCselect;
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }


    public function getItemPlanSearch(){
        $itemplanPE = $this->input->post('itemplanPE');  
        $cant = null;
        if($itemplanPE != null){
            $res  = $this->m_utils->existeItemplan($itemplanPE);
            $cant = $res->num_rows() == 1 ? ($res->row()->cant >= 1 ? '1' : '0') : '0';
        }else{
            $cant = '1';//Si hay un error que simule que si existe
        }
        echo $cant;

    }

}