<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * @author CARLOS ZAVALA C.
 * 18/01/2018
 *
 */
class C_bandeja_cluster extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_cluster/m_bandeja_cluster');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->library('zip');
        $this->load->helper('url');
    }
    
	public function index()
	{  	   
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
        	  
	           $data['listaZonal'] = $this->m_utils->getAllZonal();
               $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo(null);
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');	
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');	               
        	   $permisos =  $this->session->userdata('permisosArbol');
        	   $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_CLUSTER_SISEGO, ID_PERMISO_HIJO_BANDEJA_COTIZACION_SISEGO);
        	   $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	       $this->load->view('vf_cluster/v_bandeja_cluster',$data);
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
                            <th>Editar</th>                            
                            <th>CODIGO</th>
                            <th>SUBPROYECTO</th>
                            <th>JEFATURA</th>
                            <th>ZONAL</th>
                            <th>EECC</th>
                            <th>Costo Mat</th>
                            <th>Costo Mo</th>
                            <th style="text-align:center">Fecha Creacion.</th>
                            <th style="text-align:center">ESTADO</th>
                        </tr>
                    </thead>
                   
                    <tbody>';
            if($listaPTR!=null){																                                                   
                foreach($listaPTR->result() as $row){        
                   
                $html .=' <tr>                                                         
                            <td>'.(($row->estado == 0) ? '<a href="evaClus?cod='.$row->codigo_cluster.'"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/editar.ico"></a>' : '').'</td>
							<td>'.$row->codigo_cluster.'</td>
							<td>'.$row->subProyectoDesc.'</td>
					        <td>'.$row->jefatura.'</td>	
					        <td>'.$row->zonalDesc.'</td>	
				            <td>'.$row->empresaColabDesc.'</td>	
						    <td>'.$row->costo_materiales.'</td>
					        <td>'.$row->costo_mano_obra.'</td>
                            <td>'.$row->fecha_registro.'</td>
                            <td>'.(($row->estado == 0) ? 'PDT COTIZACION': 'PDT APROBACION').'</td>		
						</tr>';
                    }  
   			  }
			 $html .='</tbody>
                </table>';
                    
        return utf8_decode($html);
    }
    
    public function filtrarBandejaCluster()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
    
            $idSubPro    = $this->input->post('idSubPro');
            $idZonal     = $this->input->post('idZonal');
            $idEecc      = $this->input->post('idEecc');
            $idSituacion = $this->input->post('idSituacion');
            if($idSubPro != ''  ||  $idZonal != '' ||  $idEecc != '' ||  $idSituacion != ''){
                $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_bandeja_cluster->getItemplanPreRegistro($idSubPro, $idZonal, $idEecc, $idSituacion));
            }else{
                $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo(null);
            }
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }    
        
}