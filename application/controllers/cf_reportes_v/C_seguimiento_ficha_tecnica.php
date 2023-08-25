<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * @author CARLOS ZAVALA C.
 * 18/01/2018
 *
 */
class C_seguimiento_ficha_tecnica extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_reportes_v/m_seguimiento_ficha_tecnica');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }
    
	public function index(){
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
	           $data['getAllJefatura'] = $this->m_utils->getJefaturaCmb();  	           
	           $data['listaEecc'] = $this->m_utils->getAllEECC();
        	   $data['tablaAsigGrafo'] = $this->makeHTMLTablaSeguimientoFicha($this->m_seguimiento_ficha_tecnica->getSeguimientoFichaTecnica('',''));
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
               $permisos   =  $this->session->userdata('permisosArbol');
        	   $result     = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_REPORTES_V, ID_PERMISO_HIJO_SEGUIMIENTO_AVANCE_PO);
        	   $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	         $this->load->view('vf_reportes_v/v_seguimiento_ficha_tecnica',$data);
        	   }else{
        	       redirect('login','refresh');
        	   }
    	 }else{
        	 redirect('login','refresh');
	    }
             
    }

    public function makeHTMLTablaSeguimientoFicha($listDatos){
       
        $html = '
                  <table id="data-table" class="table table-bordered" style="font-size: 10px;">
                    <thead class="thead-default">
                      
                       <tr role="row">	                    
                            <th colspan="1">JEFATURA</th>
	                        <th colspan="1">EECC</th>
	                        <th colspan="1">PENDIENTE</th>
                            <th colspan="1">TOTAL</th> 
                            <th colspan="1">'.date("d/m/Y").'</th>  
                            <th colspan="1">'.date("d/m/Y", strtotime(date("Y-m-d").' - 1 day')).'</th>
	                        <th colspan="1">'.date("d/m/Y", strtotime(date("Y-m-d").' - 2 day')).'</th>
                            <th colspan="1">'.date("d/m/Y", strtotime(date("Y-m-d").' - 3 day')).'</th>     
                            <th colspan="1">'.date("d/m/Y", strtotime(date("Y-m-d").' - 4 day')).'</th>
	                        <th colspan="1">'.date("d/m/Y", strtotime(date("Y-m-d").' - 5 day')).'</th>
                            <th colspan="1">'.date("d/m/Y", strtotime(date("Y-m-d").' - 6 day')).'</th>  
                            <th colspan="1">'.date("d/m/Y", strtotime(date("Y-m-d").' - 7 day')).'</th>
                        </tr>
                    </thead>                    
                    <tbody>';
        if($listDatos!=null){                                           
                foreach($listDatos->result() as $row){
                   $html .= ' <tr>  
                                <td>'.$row->jefatura.'</td>
                                <td>'.$row->empresaColabDesc.'</td>
                                <td>'.$row->pndt.'</td>
                                <td>'.$row->total.'</td>
                                <td '.(($row->hoy!='') ? 'style="text-align: center;"' : 'style="background-color: #d0c9c9;"').'>'.$row->hoy.'</td>
                                <td '.(($row->menos1!='') ? 'style="text-align: center;"' : 'style="background-color: #d0c9c9;"').'>'.$row->menos1.'</td>
                                <td '.(($row->menos2!='') ? 'style="text-align: center;"' : 'style="background-color: #d0c9c9;"').'>'.$row->menos2.'</td>
                                <td '.(($row->menos3!='') ? 'style="text-align: center;"' : 'style="background-color: #d0c9c9;"').'>'.$row->menos3.'</td>
                                <td '.(($row->menos4!='') ? 'style="text-align: center;"' : 'style="background-color: #d0c9c9;"').'>'.$row->menos4.'</td>
                                <td '.(($row->menos5!='') ? 'style="text-align: center;"' : 'style="background-color: #d0c9c9;"').'>'.$row->menos5.'</td>
                                <td '.(($row->menos6!='') ? 'style="text-align: center;"' : 'style="background-color: #d0c9c9;"').'>'.$row->menos6.'</td>
                                <td '.(($row->menos7!='') ? 'style="text-align: center;"' : 'style="background-color: #d0c9c9;"').'>'.$row->menos7.'</td>
                    		</tr>';
                 }
        }
			 $html .='</tbody>
                </table>';
                    
        return $html;
    }
    
    function filtrarTabla(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $eecc       = $this->input->post('eecc');
            $jefatura   = $this->input->post('jefatura');
            $data['tablaAsigGrafo'] = $this->makeHTMLTablaSeguimientoFicha($this->m_seguimiento_ficha_tecnica->getSeguimientoFichaTecnica($jefatura, $eecc));
            $data['error']    = EXIT_SUCCESS;
            
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
}