<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_bandeja_cancelacion_siom extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_liquidacion/m_bandeja_cancelacion_siom');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index(){
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
    	       $data['listaEECC']     = $this->m_utils->getAllEECC();
               $data['listaZonal']    = $this->m_utils->getAllZonalGroup();
               $data['cmbJefatura']   = $this->m_utils->getJefaturaCmb();
        	   $data['listaSubProy']  = $this->m_utils->getAllSubProyecto();
        	   $data['listafase']     = $this->m_utils->getAllFase();
               $data['tablaSiom']     = $this->getTablaSiom();               
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
               $permisos =  $this->session->userdata('permisosArbol');
        	   #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_GESTION_SIOM, ID_PERMISO_HIJO_BANDEJA_CANCELACION_SIOM);
               $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_CAP_SIOM, ID_PERMISO_HIJO_BANDEJA_CANCELACION_SIOM, ID_MODULO_CAP);
               $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	         $this->load->view('vf_liquidacion/v_bandeja_cancelacion_siom',$data);
        	   }else{
        	       redirect('login','refresh');
        	   }
    	 }else{
        	 redirect('login','refresh');
	    }
             
    }

    function getTablaSiom($idSubProyecto=null, $jefatura=null, $idEmpresaColab=null, $noEnviado=null) {
        $data = $this->m_bandeja_cancelacion_siom->getBandejaSiom($idSubProyecto, $idEmpresaColab, $jefatura, $noEnviado);

        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th>Itemplan</th>                            
                            <th>SubProyecto</th>
                            <th>ptr</th>                            
                            <th>Fec. registro</th>                         
                            <th>Estacion</th>
                            <th>Avance</th>
                            <th>Estado Plan</th>
                            <th>Jefatura</th>
                            <th>MDF</th>
                            <th>EECC</th>
                            <th>Codigo Siom</th>                            
                            <th>Ultimo Estado</th>
                            <th>Fec. Ultimo Estado</th>
                        </tr>
                    </thead>                    
                    <tbody>';
                                                                                                                                        
                foreach($data as $row){      
                          
                $html .=' <tr>                          
                            <td>'.$row->itemplan.'</td>
                            <td>'.$row->subProyectoDesc.'</td>
                            <td>'.$row->ptr.'</td>
                            <td>'.$row->fechaRegistro.'</td>
                            <td>'.$row->estacionDesc.'</td>
                            <td>'.(($row->porcentaje==null) ? 0 : $row->porcentaje).'</td>       
                            <td>'.utf8_decode($row->estadoPlanDesc).'</td>
                            <td>'.$row->jefatura.'</td>
                            <td>'.$row->codigo.'</td>
                            <td>'.$row->empresaColabDesc.'</td>
                            <td>'.$row->codigoSiom.'</td>
                            <th>'.$row->ultimo_estado.'</th>
                            <th>'.$row->fecha_ultimo_estado.'</th>
                        </tr>';
                }
            $html .='</tbody>
                </table>';
                    
            return $html;
    }

    function filtrarTablaSiom() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            $idEmpresaColab = ($this->input->post('idEmpresaColab')=='') ? null : $this->input->post('idEmpresaColab');
            $idSubProyecto  = ($this->input->post('idSubProyecto')=='')  ? null : $this->input->post('idSubProyecto');
            $jefatura       = ($this->input->post('jefatura') == '')     ? null : $this->input->post('jefatura');
            $noEnviado      = ($this->input->post('noEnviado') == '')     ? null : $this->input->post('noEnviado');
            
            $data['tablaBandejaSiom'] = $this->getTablaSiom($idSubProyecto, $jefatura, $idEmpresaColab, $noEnviado);
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e) {
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