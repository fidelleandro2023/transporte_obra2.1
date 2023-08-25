<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_bolsa_pep extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_presupuesto/m_bolsa_pep');
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
      	       $data['tablaSiom']     = $this->getTablaSiom(null);               
               //$data['tablaSiom']     = $this->getTablaSiom($this->m_bolsa_pep->getPepBolsaList("", "", "",""));               
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
               $permisos =  $this->session->userdata('permisosArbol');
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
               $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_ADMINISTRATIVO_PRESUPUESTO, ID_PERMISO_HIJO_BOLSA_PEP, ID_MODULO_ADMINISTRATIVO);
               $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	         $this->load->view('vf_presupuesto/v_bolsa_pep',$data);
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
            
            log_message('error', '$faseMulti:'.$faseMulti);
            $listSubPro     = explode(',', $subproyectoMul);
            $listFase       = explode(',', $faseMulti);
            $listEstaciones = explode(',', $estacionMulti);
            log_message('error', '$listFase:'.print_r($listFase,true));
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
            $data = $this->m_bolsa_pep->insertPepSubProyectoBolsa($arrayInsert);

            if(strlen($pep2)<=1)
            {
               $data = $this->m_bolsa_pep->insertPep2correlativo($pep1,"0");
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
            $idProyecto     = ($this->input->post('proyecto')=='')  ? null : $this->input->post('proyecto');
            $idSubProyecto  = ($this->input->post('subpro')=='')    ? null : $this->input->post('subpro');
            $pep1           = ($this->input->post('pep1') == '')    ? null : $this->input->post('pep1');
            $pep2           = ($this->input->post('pep2') == '')    ? null : $this->input->post('pep2');
            $data['tablaBolsaPep'] = $this->getTablaSiom($this->m_bolsa_pep->getPepBolsaList($idProyecto, $idSubProyecto, $pep1, $pep2));
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
                            <th style="text-align: center;">PROYECTO</th>
                            <th style="text-align: center;">SUBPROYECTO</th>
                            <th style="text-align: center;">ESTACION</th>
                            <th style="text-align: center;">FASE</th>
                            <th style="text-align: center;">PEP 1</th>                            
                            <th style="text-align: center;">FECHA PROGRAMACION</th>
                            <th style="text-align: center;">ESTADO</th>
                            <th style="text-align: center;">DIAS OPER.</th>
                            <th style="text-align: center;">TIPO PEP</th>                           
                        </tr>
                    </thead>
                    <tbody>';
        if($dataList != null){
            foreach($dataList as $row){               
                $html .=' <tr>
                            <td>'.(($canDelete) ? '<a data-id ="'.$row->id.'" onclick="deletPepBolsa(this);" ><i class="zmdi zmdi-hc-2x zmdi-delete"></i></a>' : '').'</td>
                            <td>'.$row->proyectoDesc.'</td>
                            <td>'.$row->subProyectoDesc.'</td>
                            <td>'.utf8_decode($row->estacionDesc).'</td>
                            <td>'.$row->faseDesc.'</td>
                            <td>'.$row->pep1.'</td>
                            <td>'.$row->fecha_programacion.'</td>
                            <td>'.$row->estado.'</td>
                            <td>'.$row->dias_operativos.'</td>
                            <td>'.$row->tipo_pep.'</td>
                         </tr>';
            }
        }
        $html .='</tbody>
                </table>';
    
        return $html;
    }
    
    function deletePepbolsa() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }
            $idPepBolsa   = $this->input->post('idPepBolsa');
            $arrayData = array('estado' => 2,
                                'fecha_cancela' => $this->fechaActual(),
                                'usuario_cancela' => $this->session->userdata('idPersonaSession')
            );//2 = estado inactivo
            $data = $this->m_bolsa_pep->deletePepbolsaM($idPepBolsa, $arrayData);
            //$data['tablaBandejaSiom'] = $this->getTablaSiom();
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
}