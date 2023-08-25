<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * @author CARLOS ZAVALA C.
 * 18/01/2018
 *
 */
class C_bandeja_sin_fecha_inicio extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_plan_obra/m_bandeja_sin_fecha_inicio');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->library('zip');
        $this->load->helper('url');
    }
    
	public function index()
	{  	   
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){	             	   
               $data['tablaAsigGrafo']  = $this->makeHTLMTablaBandejaAprobMo($this->m_bandeja_sin_fecha_inicio->getItemNoFechaInicio());
               $data['nombreUsuario']   =  $this->session->userdata('usernameSession');	
               $data['perfilUsuario']   =  $this->session->userdata('descPerfilSession');	               
        	   $permisos =  $this->session->userdata('permisosArbol');
        	   #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_COTIZACION, ID_PERMISO_HIJO_REGISTRO_COTIZACION);
			   $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_PLAN_DE_OBRA, ID_PERMISO_HIJO_BANDEJA_SIN_FECHA_INICIO, ID_MODULO_GESTION_OBRA);
        	   $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	       $this->load->view('vf_plan_obra/v_bandeja_sin_fecha_inicio',$data);
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
                            <th>ITEMPLAN</th>                         
                            <th>PROYECTO</th>                            
                            <th>SUBPROYECTO Plan</th>  
                            <th>ESTADO PLAN</th>      
                            <th>FECHA INICIO</th>  
                            <th></th>                             
                        </tr>
                    </thead>
                   
                    <tbody>';
            if($listaPTR!=null){																                                                   
                foreach($listaPTR->result() as $row){           
                    $html .='<tr>                               
    							<td>'.$row->itemplan.'</td>	
                                <td>'.$row->proyectoDesc.'</td>
                                <td>'.$row->subProyectoDesc.'</td>
                                <td>'.$row->estadoPlanDesc.'</td>
                                <td>'.$row->fechaInicio.'</td>      
                                <td><a data-item ="'.$row->itemplan.'" data-subp="'.$row->idSubProyecto.'" onclick="openEditFechaInicio(this)"><i title="Editar Fecha Inicio" style="color:#A4A4A4" class="zmdi zmdi-hc-2x zmdi-calendar"></i></a></td>    
                            </tr>';
                    }  
   			  }
		 $html .='</tbody>
                </table>';
                    
        return utf8_decode($html);
    }
    
    function updateFecIniItemplan() {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $itemplan       = $this->input->post('item');
            $fecInicio      = $this->input->post('fecIni');
            $fecPrevista    = $this->input->post('fecPre');
            $data           = $this->m_bandeja_sin_fecha_inicio->updateFecInicioItemplan($itemplan, $fecInicio, $fecPrevista);
            if($data['error']==EXIT_ERROR){
                throw new Exception('Error Interno');
            }
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_bandeja_sin_fecha_inicio->getItemNoFechaInicio());
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));    
    }    
    
}