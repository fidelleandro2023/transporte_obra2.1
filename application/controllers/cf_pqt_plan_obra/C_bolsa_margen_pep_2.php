<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_bolsa_margen_pep_2 extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_presupuesto/m_bolsa_margen_pep');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index(){
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
    	       $data['listaProy'] = $this->m_utils->getAllProyecto();        	           
    	       $data['listafase'] = $this->m_utils->getAllFase();
    	       $data['listaEstacion'] = $this->m_utils->getAllEstacion();
      	       _log('entro aca');             
               $data['tablaSiom']     = $this->getTablaSiom($this->m_bolsa_margen_pep->getPepBolsaList("", "", "",""));               
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
               $permisos =  $this->session->userdata('permisosArbolTransporte');
			   $canDelete = true;
               $perfiles = explode(',',$this->session->userdata('idPerfilSession'));
        	   if(in_array(53, $perfiles)){
                    if(in_array($this->session->userdata('idPersonaSession'),array(17,18,42,32,15,545,47,924,23))){//usuarios con perfil 53 y con acceso
                        $canDelete = true;
                    }else{
                        $canDelete = false;
                    }
                }
               $data['cantAddConfig'] = $canDelete;
               $result = $this->lib_utils->getHTMLPermisos($permisos, 54, 335, ID_MODULO_PAQUETIZADO);
               $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	         $this->load->view('vf_pqt_plan_obra/v_bolsa_margen_pep_2',$data);
        	   }else{
        	       redirect('login','refresh');
        	   }
    	 }else{
        	 redirect('login','refresh');
	    }
             
    }

    
    function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone','America/Lima');
        setlocale(LC_TIME, "es_ES","esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }
        
    public function getHTMLChoiceSubProy(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $idProyecto = $this->input->post('proyecto');    
            $listaSubProy = $this->m_utils->getAllSubProyectoByIdProyecto($idProyecto);
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


    
    
    function filtrarTabla() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
         
            $pep1           = ($this->input->post('pep1') == '')    ? null : $this->input->post('pep1');
            $pep2           = ($this->input->post('pep2') == '')    ? null : $this->input->post('pep2');
            $data['tablaBolsaPep'] = $this->getTablaSiom($this->m_bolsa_margen_pep->getPepBolsaList($idProyecto, $idSubProyecto, $pep1, $pep2));
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getTablaSiom($dataList) {

        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th style="text-align: center;">PEP</th>
                            <th style="text-align: center;">PRESUPUESTO</th>
							<th style="text-align: center;">REAL</th>
							<th style="text-align: center;">COMPROMETIDO</th>
							<th style="text-align: center;">PLANRES</th>
							<th style="text-align: center;">DISPONIBLE</th>
                            <th style="text-align: center;">DISPONIBLE PROYECTADO</th>
                            <th style="text-align: center;">MARGEN DE MANIOBRA</th>
                            <th style="text-align: center;">TIPO</th>
                            
                                                  
                        </tr>
                    </thead>
                    <tbody>';
        if($dataList != null){
            foreach($dataList as $row){               
                $html .=' <tr>
                            <td>'.$row->pep1.'</td>
                            <td>'.($row->presupuesto==''?'SIN PRESUPUESTO': number_format($row->presupuesto,2)).'</td>
							<td>'.($row->real).'</td>
							<td>'.($row->comprometido).'</td>
							<td>'.($row->planresord).'</td>
							<td>'.($row->disponible).'</td>
                            <td>'.($row->monto_temporal==''?'SIN SAP': number_format($row->monto_temporal,2)).'</td>
                            <td>'.($row->margen==''?'SIN MARGEN ASIGNADO':number_format($row->margen,2)).'</td>
                            <td>'.$row->tipo.'</td>
                           
                         </tr>';
            }
        }
        $html .='</tbody>
                </table>';
    
        return $html;
    }
    
    
}