<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_switch_siom extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_liquidacion/m_switch_siom');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index(){
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
    	       $data['listaEECC']     = $this->m_utils->getAllEECC();
            //    $data['listaZonal']    = $this->m_utils->getAllZonalGroup();
               $data['cmbJefatura']   = $this->m_utils->getJefaturaCmb();
        	   $data['listaSubProy']  = $this->m_utils->getAllSubProyecto();
        	//    $data['listafase']     = $this->m_utils->getAllFase();
               $data['tablaSiom']     = $this->getTablaSwitchSiom(null,null,null);               
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
               $permisos =  $this->session->userdata('permisosArbol');
        	   #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_GESTION_SIOM, ID_PERMISO_HIJO_MANTENIMIENTO_SIOM);
               $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_MANTENIMIENTO_SIOM, ID_PERMISO_HIJO_MANTENIMIENTO_SIOM, ID_MODULO_MANTENIMIENTO);
               $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	         $this->load->view('vf_liquidacion/v_switch_siom',$data);
        	   }else{
        	       redirect('login','refresh');
        	   }
    	 }else{
        	 redirect('login','refresh');
	    }
             
    }

    function getTablaSwitchSiom($idSubProyecto=null, $jefatura=null, $idEmpresaColab=null) {
		if($idSubProyecto == null && $jefatura	== null && $idEmpresaColab	== null){
			$data = null;
		}else{
			$data = $this->m_switch_siom->getSwitch($idSubProyecto, $jefatura, $idEmpresaColab);
		}
        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th></th>
                            <th>SubProyecto</th>
                            <th>Empresa Colaboradora</th>
                            <th>Jefatura</th>
                            <th>Fecha Inicio</th>                            
                        </tr>
                    </thead>                    
                    <tbody>';
				if($data != null){																											
					foreach($data as $row){              
						$html .=' <tr>
									<th><i style="cursor:pointer" title="Editar" class="zmdi zmdi-hc-2x zmdi-edit" data-id_empresacolab="'.$row->idEmpresaColab.'" 
										   data-jefatura="'.$row->jefatura.'" data-id_sub_proyecto="'.$row->idSubProyecto.'" data-id_switch="'.$row->idSwitchSiom.'" data-fecha="'.$row->fecha.'" onclick="openModalEditar($(this));"></i></th>
									<td>'.$row->subProyectoDesc.'</td>
									<td>'.$row->empresaColabDesc.'</td>
									<td>'.$row->jefatura.'</td>
									<td>'.$row->fecha.'</td>					
								</tr>';
					}
				}
            $html .='</tbody>
                </table>';
                    
            return $html;
    }

    function getDataEditar() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            $idEmpresaColab = $this->input->post('idEmpresaColab');
            $jefatura       = $this->input->post('jefatura');
            $idSubProyecto       = $this->input->post('idSubProyecto');

            list($cmbEmpresaColab, $cmbJefatura, $cmbSubProyecto) = $this->getCmbSwitch($idEmpresaColab, $jefatura, $idSubProyecto);
            $data['cmbEmpresaColab'] = $cmbEmpresaColab;       
            $data['cmbJefatura']     = $cmbJefatura;
            $data['cmbSubProyecto']  = $cmbSubProyecto;
        } catch(Exceptio $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data, JSON_NUMERIC_CHECK);
    }

    function openModalRegistro() {
        list($cmbEmpresaColab, $cmbJefatura, $cmbSubProyecto) = $this->getCmbSwitch();
        $data['cmbEmpresaColab'] = $cmbEmpresaColab;       
        $data['cmbJefatura']     = $cmbJefatura;
        $data['cmbSubProyecto']  = $cmbSubProyecto;
        echo json_encode($data, JSON_NUMERIC_CHECK);
    }

    function registrarSwitch() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            $jsonInsert   = json_decode($this->input->post('jsonInsert'));

            $data = $this->m_switch_siom->insertSwitch($jsonInsert);
            $data['tablaSwitch'] = $this->getTablaSwitchSiom();
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data, JSON_NUMERIC_CHECK);
    }

    function getCmbSwitch($idEmpresaColab=null, $jefatura=null, $idSubProyecto=null) {
        $cmbEmpresaColab = null;
        $cmbJefatura     = null;
        $cmbSubProyecto  = null;

        $arrayEmpresaColab = $this->m_utils->getAllEECC();
        $arrayJefatura     = $this->m_utils->getJefaturaCmb();
        $arraySubProyecto  = $this->m_utils->getAllSubProyecto();

        $cmbEmpresaColab.='<option value="">Seleccionar ECC</option>';
        foreach($arrayEmpresaColab->result() as $row) {
            $selected = ($idEmpresaColab == $row->idEmpresaColab) ? 'selected' : '';
            $cmbEmpresaColab.= '<option value="'.$row->idEmpresaColab.'" '.$selected.'>'.$row->empresaColabDesc.'</option>';
        }

        $cmbJefatura.='<option value="">Seleccionar Jefatura</option>';
        foreach($arrayJefatura as $row) {
            $selected = ($jefatura == $row->jefatura) ? 'selected' : '';
            $cmbJefatura.= '<option value="'.$row->jefatura.'" '.$selected.'>'.$row->jefatura.'</option>';
        }

        $cmbSubProyecto.='<option value="">Seleccionar SubProyecto</option>';
        foreach($arraySubProyecto->result() as $row) {
            $selected = ($idSubProyecto == $row->idSubProyecto) ? 'selected' : '';
            $cmbSubProyecto.= '<option value="'.$row->idSubProyecto.'" '.$selected.'>'.$row->subProyectoDesc.'</option>';
        }
        return array($cmbEmpresaColab, $cmbJefatura, $cmbSubProyecto);
    }

    function actualizarSwitch() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            $jsonUpdate   = json_decode($this->input->post('jsonUpdate'));
            $idSwitchSiom = $this->input->post('idSwitchSiom');

            if($idSwitchSiom == null) {
                throw new Exception("id null");
            }

            $data = $this->m_switch_siom->actualizarSwitch($idSwitchSiom, $jsonUpdate);
            $data['tablaSwitch'] = $this->getTablaSwitchSiom();
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data, JSON_NUMERIC_CHECK);
    }

    function filtrarTablaSwitchSiom() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            $idEmpresaColab = ($this->input->post('idEmpresaColab')=='') ? null : $this->input->post('idEmpresaColab');
            $idSubProyecto  = ($this->input->post('idSubProyecto')=='')  ? null : $this->input->post('idSubProyecto');
            $jefatura       = ($this->input->post('jefatura') == '')     ? null : $this->input->post('jefatura');

            $data['tablaSwitch'] = $this->getTablaSwitchSiom($idSubProyecto, $jefatura, $idEmpresaColab);
            $data['error'] = EXIT_SUCCESS;
        } catch(Exceptio $e) {
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