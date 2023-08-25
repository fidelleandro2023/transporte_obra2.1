<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_bandeja_descertificacion extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_liquidacion/m_bandeja_descertificacion');       
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }
    
    public function index(){
        $logedUser = $this->session->userdata('usernameSession');
        if($logedUser != null){
           
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_bandeja_descertificacion->getBandejaDesCertificacionMO());
            $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
            $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
            $permisos =  $this->session->userdata('permisosArbol');
            #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_CERTIFICACION_MO, ID_PERMISO_HIJO_BANDEJA_LIBERAR_PTR);
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_CERTIFICACION_MO, ID_PERMISO_HIJO_BANDEJA_LIBERAR_PTR, ID_MODULO_ADMINISTRATIVO);
            $data['opciones'] = $result['html'];
            if($result['hasPermiso'] == true){
                $this->load->view('vf_liquidacion/v_bandeja_descertificacion',$data);
            }else{
                redirect('login','refresh');
            }
        }else{
            redirect('login','refresh');
        }             
    }
    
    public function makeHTLMTablaBandejaAprobMo($listaPTR){
        
        $html = '<table style="font-size: 10px;" id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr role="row">   
                            <th colspan="1" style="text-align:center;width: 1px;"><a onclick="liberarPtrs()"><img alt="Editar" height="20px" width="40px" src="public/img/iconos/candado_abierto.png"></a></th>                      
                            <th colspan="1" style="text-align:center">PROYECTO</th>
                            <th colspan="1" style="text-align:center">SUB PROYECTO</th>
                            <th colspan="1" style="text-align:center">ITEMPLAN</th>                            
                            <th colspan="1" style="text-align:center">PTR</th>
                            <th colspan="1" style="text-align:center">AREA</th>
                            <th colspan="1" style="text-align:center">MONTO</th>
                            <th colspan="1" style="text-align:center">PEP1</th>
                            <th colspan="1" style="text-align:center">FEC. VALIDA</th>
                            <th colspan="1" style="text-align:center">HOJA GESTION</th>
                        </tr>
                    </thead>
                    
                    <tbody>';
        
        foreach($listaPTR->result() as $row){
            
            $html .=' <tr>
                            <th style="width: 1px;">
                                <label class="custom-control custom-checkbox">
                                <input onclick="chequed(this)" value="'.$row->id.'"  type="checkbox" class="custom-control-input">
                                <span class="custom-control-indicator"></span>                                
                            </label>
                            </th>  
                            <th>'.$row->proyectoDesc.'</th>
                            <th>'.$row->subProyectoDesc.'</th>
                            <th>'.$row->itemplan.'</th>                                                      
							<th>'.$row->ptr.'</th>
                            <th>'.$row->areaDesc.'</th>  
                            <th>'.$row->monto_mo.'</th>
                            <th>'.$row->pep1.'</th>    
                            <th>'.$row->fecha_valida.'</th>  
                            <th>'.$row->hoja_gestion.'</th>
						</tr>';
        }
        $html .='</tbody>
                </table>';
        
        return utf8_decode($html);
    }
    
    public function liberarPtrs(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            
            $check      = $this->input->post('check');
            $checkList  = json_decode($check);
            $data_array = array();
            foreach($checkList as $row){
                $dato = array( 'id'            => $row,
                    'estado_validado' => 0,
                    'hoja_gestion'  => null,
                    'fecha_valida'  => null,
                    'usuario'       => null
                );
                array_push($data_array, $dato);
            }
            $data = $this->m_bandeja_descertificacion->liberarPtrCertificacion($data_array);
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_bandeja_descertificacion->getBandejaDesCertificacionMO());
            $data['msj']    = 'Se liberaron las ptr!';
            $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
   
}