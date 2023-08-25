<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * 
 * 
 *
 */
class C_central extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_mantenimiento/m_central');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }
    
	public function index()
	{  	   
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){           
               $data['listartabla'] = $this->makeHTMLTablaCentral($this->m_central->getAllCentrales());
               $data['listaeecc'] = $this->m_utils->getAllEECC();
               $data['listaTiCen'] = $this->m_utils->getAllTipoCentral();
               $data['listazonas'] = $this->m_utils->getAllZonal();
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');	
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');	               
        	   $permisos =  $this->session->userdata('permisosArbol');
        	   #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_MANTENIMIENTO, ID_PERMISO_HIJO_MAN_CENTRAL);
        	   $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_MANTENIMIENTO_GESTION_DE_OBRA, ID_PERMISO_HIJO_MAN_CENTRAL, ID_MODULO_MANTENIMIENTO);
        	   $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	       $this->load->view('vf_mantenimiento/v_central',$data);
        	   }else{
        	       redirect('login','refresh');
	           }
	   }else{
	       redirect('login','refresh');
	   }
    }
   
    
    public function makeHTMLTablaCentral($listartabla){
     
        $html = '
        <table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>Descripcion</th>                            
                            <th>Codigo</th>
                            <th>Tipo</th>
                            <th>Zonal</th>
                            <th>EECC</th>
                            <th>Jefatura</th>
                            <th>Region</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>';
		   																			                           
                foreach($listartabla->result() as $row){ 
                    
                $html .=' <tr>
							<td>'.$row->centralDesc.'</td> 
                            <td>'.$row->codigo.'</td> 
                            <td>'.$row->tipoCentralDesc.'</td>                                 
                            <td>'.$row->zonalDesc.'</td>
                            <td>'.$row->empresaColabDesc.'</td>
                            <td>'.$row->jefatura.'</td>
                            <td>'.$row->region.'</td>      
                            <td><a data-id_cen="'.$row->idCentral.'" onclick="editCentral(this)"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/editar.ico"></a></td>                   
						</tr>';
                 }
			 $html .='</tbody>
                </table>';
                    
        return utf8_decode($html);
    }
    
    
    function existeCodigoCentral(){
        $codigoCentral = $this->input->post('codigo');        
       
        $cant = null;
        if($codigoCentral != null){
            $res  = $this->m_central->existeCentral($codigoCentral);
            $cant = $res->num_rows() == 1 ? ($res->row()->cant >= 1 ? '1' : '0') : '0';
        }else{
            $cant = '1';//Si hay un error que simule que si existe
        }
        echo $cant;
    }
      
    public function createCentral(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $idTipocentral = $this->input->post('selectTipoCentral');
            $descripcion = $this->input->post('inputDescripcion');
            $codigo = $this->input->post('inputCodigo');
            $zonal = $this->input->post('selectZonal');
            $eecc = $this->input->post('selectEECC');
            $jefatura = $this->input->post('cmbJefatura1');
            $region = $this->input->post('inputRegion');

            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;

            $this->db->trans_begin();

            if($jefatura == null){
                throw new Exception('Debe seleccionar una jefatura para registrar!!');
            }
            if ($idUsuario == null) {
                throw new Exception('Su sesion ha expirado, ingrese nuevamente!!');
            }

            $arrayJefatura = $this->m_utils->getDetJefatura($jefatura);
            
            $data = $this->m_central->insertarCentral($idTipocentral, $descripcion, $codigo, $zonal, $eecc, $arrayJefatura['descripcion'], $region,$jefatura);
            if($data['error'] == EXIT_ERROR){
                throw new Exception('Error al Insertar createCentral');
            }else{

                $arrayInsertLogCentral = array(
                    "idtipoCentral"  => $idTipocentral,
                    "codigo" => strtoupper($codigo),
                    "tipoCentralDesc" => strtoupper($descripcion),
                    "idZonal" => $zonal,
                    "idEmpresacolab" => $eecc,
                    "jefatura" => strtoupper($arrayJefatura['descripcion']),
                    "region" => strtoupper($region),
                    "idJefatura" => $jefatura,
                    "desc_actividad" => 'insert',
                    "id_usuario" => $idUsuario,
                    "fecha_registro" => $this->m_utils->fechaActual()
                );
                $data = $this->m_utils->insertarLogCentral($arrayInsertLogCentral);
                if ($data['error'] == EXIT_SUCCESS) {
                    $this->db->trans_commit();
                    $data['listartabla'] = $this->makeHTMLTablaCentral($this->m_central->getAllCentrales());
                }else{
                    throw new Exception('Error al insertar en log central');
                }
            }
           
            
    
        }catch(Exception $e){
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function getInfoCentral(){
         
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $id = $this->input->post('id');            
            $central = $this->m_central->getCentraInfo($id);   
            $data = $this->makeCMBJefatura($this->m_utils->getAllJefatura(),$central['jefatura']);  
            $data['cmbJefatura'] = $data['comboHTML'];   
            
            $data['tipoCentral'] = $central['idTipoCentral'];
            $data['codigo'] = $central['codigo'];
            $data['descripcion'] = $central['tipoCentralDesc'];
            $data['zonal'] = $central['idZonal'];
            $data['eecc'] = $central['idEmpresaColab'];
            $data['jefatura'] = $central['jefatura'];
            $data['region'] = $central['region'];
            $this->session->set_flashdata('idCentralEdit',$id);
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    public function editarCentral(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $idTipocentral = $this->input->post('selectTipoCentral2');
            $descripcion = $this->input->post('inputDescripcion2');
            $codigo = $this->input->post('inputCodigo2');
            $zonal = $this->input->post('selectZonal2');
            $eecc = $this->input->post('selectEECC2');
            $jefatura = $this->input->post('inputJefatura2');
            $region = $this->input->post('inputRegion2');
            $id = $this->input->post('id');

            $idJefatura = $this->input->post('cmbJefatura');
            $descJefatura = $this->input->post('descJefatura');

            $data = $this->m_central->editarCentralModelo($id, $idTipocentral, $descripcion, $codigo, $zonal, $eecc, $jefatura, $region, $idJefatura, $descJefatura);
            if($data['error']==EXIT_ERROR){
                throw new Exception('Error al Insertar createCentral');
            }
            $data['listartabla'] = $this->makeHTMLTablaCentral($this->m_central->getAllCentrales());
    
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function makeCMBJefatura($listaJefatura, $jefatura = null)
    {
        $html = '<option value="">Seleccionar Jefatura</option>';

        foreach ($listaJefatura as $row) {
            $selected = ($row->descripcion == $jefatura) ? 'selected' : null;
            $html .= '<option value="' . $row->idJefatura . '" ' . $selected . ' >' . $row->descripcion . '</option>';
        }
        $data['comboHTML'] = utf8_decode($html);
        return $data;
    }

    public function getCmbJefaturaReg(){
         
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $data = $this->makeCMBJefatura($this->m_utils->getAllJefatura());  
            $data['cmbJefatura'] = $data['comboHTML'];   
            $data['error'] = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    
}