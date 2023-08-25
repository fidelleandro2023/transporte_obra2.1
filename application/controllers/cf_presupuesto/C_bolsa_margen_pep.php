<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_bolsa_margen_pep extends CI_Controller {

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
      	       //$data['tablaSiom']     = $this->getTablaSiom(null);               
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
               $result = $this->lib_utils->getHTMLPermisos($permisos, 319, 323, ID_MODULO_ADMINISTRATIVO);
               $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	         $this->load->view('vf_presupuesto/v_bolsa_margen_pep',$data);
        	   }else{
        	       redirect('login','refresh');
        	   }
    	 }else{
        	 redirect('login','refresh');
	    }
             
    }
    
    function savePep1Pep2BolsaPep() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }
            
            $subproyectoMul = $this->input->post('selectSubproyMulti');
            $faseMulti      = $this->input->post('selectFaseMulti');
            $estacionMulti  = $this->input->post('selectEstacionMulti');
            $pep1           = $this->input->post('inputP1P');
            $fec_progra     = $this->input->post('inputFecProgramacion');
            $idUsuario      = $this->session->userdata('idPersonaSession');
            $tipo_pep       = $this->input->post('selectTipoArea');
            $pep2       = $this->input->post('pep2');            
            $listSubPro     = explode(',', $subproyectoMul);
            $listFase       = explode(',', $faseMulti);
            $listEstaciones = explode(',', $estacionMulti);
            $arrayInsert = array();
            foreach ($listSubPro as $idSubProyecto){                
                foreach($listFase as $idFase){                    
                    foreach ($listEstaciones as $idEstacion){
                        $insertData = array('pep1'              => $pep1,
                                            'idSubProyecto'     => $idSubProyecto,
                                            'idEstacion'        => $idEstacion,
                                            'idFase'            => $idFase,
                                            'fecha_programacion'=> $fec_progra,
                                            'fecha_registro'    => $this->fechaActual(),
                                            'usuario_registro'  => $idUsuario,
                                            'estado'            =>  1,
                                            'tipo_pep'          =>  $tipo_pep,
                                            'pep2'          =>  $pep2
                        );
                        array_push($arrayInsert, $insertData);
                    }
                }                
            }
            $data = $this->m_bolsa_margen_pep->insertPepSubProyectoBolsa($arrayInsert,$pep1,$idFase);

            if(strlen($pep2)<=1)
            {
               $data = $this->m_bolsa_margen_pep->insertPep2correlativo($pep1,"0");
            }
            $data['msj'] = EXIT_SUCCESS;
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
			   $canDelete = true;
               $perfiles = explode(',',$this->session->userdata('idPerfilSession'));
        	   if(in_array(53, $perfiles)){
                    if(in_array($this->session->userdata('idPersonaSession'),array(17,18,42,32,15,545,47,924))){
                        $canDelete = true;
                    }else{
                        $canDelete = false;
                    }
                }
        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th></th>
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
                            <td>'.(($canDelete) ? '<a  '.($row->tipo!='SIN TIPO'?'style="display:none"':'style="display:inherit"').' data-tipo ="'.$row->tipo.'" data-pep ="'.$row->pep1.'"  data-id ="'.$row->idpepmar.'" onclick="tipoMargenPep(this);" ><i class="zmdi zmdi-hc-2x zmdi-bookmark"></i>  </a><a data-id ="'.$row->idpepmar.'" onclick="addMargenPep(this);" ><i class="zmdi zmdi-hc-2x zmdi-assignment"></i></a>' : '').'</td>
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
    
    function addMargen() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }
            $idPepBolsa   = $this->input->post('idPepBolsa');
            $margen   = $this->input->post('margen');

            $arrayData = array('margen' =>$margen);//2 = estado inactivo
            $data = $this->m_bolsa_margen_pep->addMargen($idPepBolsa, $arrayData);
            //$data['tablaBandejaSiom'] = $this->getTablaSiom();
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function tipoMargen() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }
            $idPepBolsa   = $this->input->post('idPepBolsa');
            $tipo   = $this->input->post('tipo');
            $pep   = $this->input->post('pep');

            $arrayData = array('tipo' =>$tipo);
            $data = $this->m_bolsa_margen_pep->tipoMargen($idPepBolsa, $arrayData,$pep,$tipo);
            //$data['tablaBandejaSiom'] = $this->getTablaSiom();
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
}