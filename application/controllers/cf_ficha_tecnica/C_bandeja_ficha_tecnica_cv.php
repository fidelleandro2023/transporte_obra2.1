<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * @author CARLOS ZAVALA C.
 * 18/01/2018
 *
 */
class C_bandeja_ficha_tecnica_cv extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_ficha_tecnica/m_bandeja_ficha_tecnica_cv');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->library('zip');
        $this->load->helper('url');
    }
    
	public function index()
	{  	   
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){	             	   
               $data['tablaAsigGrafo']  = $this->makeHTLMTablaBandejaAprobMo($this->m_bandeja_ficha_tecnica_cv->getBandejaFichaTecnicaEvaluacionCV());
               $data['nombreUsuario']   =  $this->session->userdata('usernameSession');	
               $data['perfilUsuario']   =  $this->session->userdata('descPerfilSession');	               
        	   $permisos =  $this->session->userdata('permisosArbol');
        	   $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_INSPECCIONES, ID_PERMISO_HIJO_FICHA_TECNICA_CV);
        	   $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	       $this->load->view('vf_ficha_tecnica/v_bandeja_ficha_tecnica_cv',$data);
        	   }else{
        	       redirect('login','refresh');
	           }
	   }else{
	       redirect('login','refresh');
	   }
    }
    
    public function makeHTLMTablaBandejaAprobMo($listaPTR){  
        $html = '<table id="data-table" class="table table-bordered" style="font-size: 10px;">
                    <thead class="thead-default">
                        <tr>     
                            <th></th>
                            <th>ITEMPLAN</th>          
                            <th>SUBPROYECTO</th>  
                            <th>ZONAL</th>      
                            <th>EECC</th>  
                            <th>ESTADO PLAN</th>  
                            <th>ESTACION</th> 
                            <th>FECHA REGISTRO</th>
                            <th>SITUACION</th>                          
                        </tr>
                    </thead>
                   
                    <tbody>';
            if($listaPTR!=null){																                                                   
                foreach($listaPTR->result() as $row){           
                    $html .='<tr>  
                                <td>
                                    '.(($row->estado_validacion ==   '') ? '<a data-id_ficha="'.$row->id_ficha_tecnica.'" data-accion="'.EDITAR_REGISTRO.'" data-idsubpro="'.ID_SUB_PROYECTO_CV_INTEGRAL.'" data-itemplan="'.$row->itemPlan.'" onclick="registrarKit($(this));"><i title="Validar Declaracion Jurada" style="color:#A4A4A4" class="zmdi zmdi-hc-2x zmdi-check-circle"></i></a>' : '').
                               '<a style="margin-left: 10px;" href="makePDFCV?itm='.$row->itemPlan.'" target="_blank"><i title="Ver Declaracion Jurada PDF" class="zmdi zmdi-hc-2x zmdi-collection-pdf" style="color:#A4A4A4"></i></a>
                                <a data-item_plan="'.$row->itemPlan.'" style="cursor:pointer" onclick="zipItemPlan($(this));"><i title="Descargar Evidencias" class="zmdi zmdi-hc-2x zmdi-download" style="color:#A4A4A4"></i></a>
                                </td>                             
    							<td>'.$row->itemPlan.'</td>
                                <td>'.$row->subProyectoDesc.'</td>	
                                <td>'.$row->zonalDesc.'</td>
                                <td>'.$row->empresaColabDesc.'</td>
                                <td>'.$row->estadoPlanDesc.'</td>
                                <td>'.$row->estacionDesc.'</td> 
                                <td>'.$row->fecha_registro.'</td>
                                <td>'.$row->estado_vali.'</td>    
                            </tr>';
                    }  
   			  }
		 $html .='</tbody>
                </table>';		
        return utf8_decode($html);
    }
    
    function evaluarFichatecnicaCV() {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $accion         = $this->input->post('accion');
            $idFichaTecnica = $this->input->post('ficha');
            $itemplan       = $this->input->post('itemplan');
            $comentario     = $this->input->post('comentario');
            
            $arrayDatos = array('usuario_validacion'  =>  $this->session->userdata('idPersonaSession'),
                                'fecha_validacion'  =>  $this->fechaActual(),
                                'estado_validacion' =>  $accion,
                                'observacion_tdp'   =>  $comentario
            );
            $idEstadoPlan = $this->m_utils->getEstadoPlanByItemplan($itemplan);
            $data       = $this->m_bandeja_ficha_tecnica_cv->updateFichaTecnicaCV($itemplan, $idFichaTecnica, $arrayDatos, $idEstadoPlan);
            if($data['error']==EXIT_ERROR){
                throw new Exception('Error Interno');
            }
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_bandeja_ficha_tecnica_cv->getBandejaFichaTecnicaEvaluacionCV());
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));    
    }    
    
    function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        log_message('error', '$zonahoraria:'.$zonahoraria);
        ini_set('date.timezone','America/Lima');
        setlocale(LC_TIME, "es_ES","esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }
}