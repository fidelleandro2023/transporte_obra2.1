<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_lineaopex_fase_subproyecto extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_lineaopex/m_linea_opex_fase_subproyecto');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index(){
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
    	       $data['listaProy'] = $this->m_utils->getAllProyecto();  
               

               $data['listaSubProy'] = $this->m_utils->getAllSubProyectoDesc();  
                      	           
    	       $data['listafase'] = $this->m_utils->getAllFase();
    	       $data['listaEstacion'] = $this->m_utils->getAllEstacion();
               $data['fases'] = $this->m_linea_opex_fase_subproyecto->getFases();
               $data['combinatoria'] = $this->m_linea_opex_fase_subproyecto->getcombinatoria();
               $data['lineaxfase'] = $this->m_linea_opex_fase_subproyecto->getlineaxfase();
      	       //$data['tablaSiom']     = $this->getTablaSiom(null);               
               $data['tablaSiom']     = $this->getTablaSiom($this->m_linea_opex_fase_subproyecto->getPepBolsaList("", "", "",""));               
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
               $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_ADMINISTRATIVO_PRESUPUESTO, 327, ID_MODULO_ADMINISTRATIVO);
               $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	         $this->load->view('vf_lineaopex/v_linea_opex_fase_subproyecto',$data);
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
            $data['tablaBolsaPep'] = $this->getTablaSiom($this->m_linea_opex_fase_subproyecto->getPepBolsaList($idProyecto, $idSubProyecto, $pep1, $pep2));
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function getTablaSiom($dataList) {

        $datacombinatoria=$this->m_linea_opex_fase_subproyecto->getcombinatoria();
        $datasubproyecto=$this->m_linea_opex_fase_subproyecto->getSubproyecto();
        $datalineaopexfase= $this->m_linea_opex_fase_subproyecto->getlineaxfase();


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
                            <th style="text-align: center;">Combinatoria</th>
                            <th style="text-align: center;">Subproyecto</th>
                            <th style="text-align: center;">Linea Opex</th>
                          
                            
                                                  
                        </tr>
                    </thead>
                    <tbody>';
        if($dataList != null){
            foreach($dataList as $row){               
                $html .=' <tr>
                            <td></td>
                            <td>'.$this->combinatoria($datacombinatoria,$row->idcombinatoria).'</td>
                            <td>'.$this->subproyecto($datasubproyecto,$row->idSubProyecto).'</td>
                            <td>'.$this->lineaopex($datalineaopexfase,$row->idlineaopex_fase).'</td>
                            
                           
                           
                         </tr>';
            }
        }
        $html .='</tbody>
                </table>';
    
        return $html;
    }


    function combinatoria($datacombinatoria,$valor)
    {

        $combinatoria;
        foreach ($datacombinatoria as $row) {

            if($row->idcombinatoria==$valor){
                $combinatoria="CECO:".$row->ceco." - "."CUENTA:".$row->cuenta."- AREA FUNCIONAL: ".$row->areafuncional;
                break;

            }

        }
     
        return $combinatoria;
    }

    function subproyecto($datasubproyecto,$valor)
    {

        $descripcion="";
        foreach ($datasubproyecto as $row) {

            if($row->idSubProyecto==$valor){
                $descripcion=$row->subProyectoDesc;
                break;

            }

        }
     
        return $descripcion;
    }


    function lineaopex($datalineaopexfase,$valor)
    {

        $descripcion="";
        foreach ($datalineaopexfase as $row) {

            if($row->idlineaopex_fase==$valor){
                $descripcion="LINEA OPEX: ".$row->descripcion." / FASE: ". $row->faseDesc;
                break;

            }

        }
     
        return $descripcion;
    }




  


    public function saveTableOpex() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            //
            $inputLinea = $this->input->post('inputLinea');
            $inputCombinatoria = $this->input->post('inputCombinatoria');
            $selectProy = $this->input->post('selectProy');
           
           
            //
            //
            $arrayDataOP = array(
                'idlineaopex_fase' => $inputLinea,
                'idcombinatoria' => $inputCombinatoria,
                'idSubProyecto' => $selectProy
               
              
            );
            $data = $this->m_linea_opex_fase_subproyecto->saveConfigOpexLinea($arrayDataOP,$inputLinea,$selectProy);
          
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function addLineaOpex() {
        $data['msj'] = null;
        $data['error'] = EXIT_ERROR;
        try {
            if($this->session->userdata('idPersonaSession') == null || $this->session->userdata('idPersonaSession') == '') {
                throw new Exception("Su sesi&oacute;n a caducado, actualizar  la p&aacute;gina");
            }
            $lineaOpex   = $this->input->post('lineaOpex');
            

            $arrayData = array('descripcion' =>$lineaOpex);//2 = estado inactivo
            $data = $this->m_linea_opex_fase_subproyecto->addLinea($arrayData);
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
            $data = $this->m_linea_opex_fase_subproyecto->tipoMargen($idPepBolsa, $arrayData,$pep,$tipo);
            //$data['tablaBandejaSiom'] = $this->getTablaSiom();
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
}