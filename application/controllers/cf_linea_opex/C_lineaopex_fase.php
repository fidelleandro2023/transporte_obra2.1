<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_lineaopex_fase extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_lineaopex/m_linea_opex_fase');
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
               $data['fases'] = $this->m_linea_opex_fase->getFases();
               $data['lineaopex'] = $this->m_linea_opex_fase->getLineas();
               $data['lineaxfase'] = $this->m_linea_opex_fase->getlineaxfase();
      	       //$data['tablaSiom']     = $this->getTablaSiom(null);               
               $data['tablaSiom']     = $this->getTablaSiom($this->m_linea_opex_fase->getPepBolsaList("", "", "",""));               
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
               $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_ADMINISTRATIVO_PRESUPUESTO, 325, ID_MODULO_ADMINISTRATIVO);
               $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	         $this->load->view('vf_lineaopex/v_linea_opex_fase',$data);
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
            $data['tablaBolsaPep'] = $this->getTablaSiom($this->m_linea_opex_fase->getPepBolsaList($idProyecto, $idSubProyecto, $pep1, $pep2));
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
                            <th style="text-align: center;">linea opex</th>
                            <th style="text-align: center;">FASE</th>
                            <th style="text-align: center;">PRESUPUESTO</th>
                            <th style="text-align: center;">DISPONIBLE PROYECTADO</th>
                            <th style="text-align: center;">MONTO REAL</th>
                          
                            
                                                  
                        </tr>
                    </thead>
                    <tbody>';
        if($dataList != null){
            foreach($dataList as $row){               
                $html .=' <tr>
                            <td>   <a data-presupuesto ="'.$row->presupuesto.'"      <a data-disponible ="'.$row->disponible_proyectado.'"   <a data-real ="'.$row->monto_real.'"     data-id ="'.$row->idlineaopex_fase.'" onclick="modificarPresupuesto(this);" ><i class="zmdi zmdi-hc-2x zmdi-assignment-check"></i></a></td>
                            <td>'.$row->descripcion.'</td>
                            <td>'.$row->faseDesc.'</td>
                            <td>'.number_format($row->presupuesto,2,",",".").'</td>
                            <td>'.number_format($row->disponible_proyectado,2,",",".").'</td>
                            <td>'.number_format($row->monto_real,2,",",".").'</td>
                            
                            
                           
                           
                         </tr>';
            }
        }
        $html .='</tbody>
                </table>';
    
        return $html;
    }


    public function saveTableOpexPresupuestotransferencia() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            //
            $inputPresupuesto = $this->input->post('inputPresupuesto');
            $inputLinearecepcion = $this->input->post('inputLinearecepcion');
            $inputLineaenvio = $this->input->post('inputLineaenvio');


            $inputenvioproyectado = $this->input->post('inputenvioproyectado');
            $inputenviopresupuesto = $this->input->post('inputenviopresupuesto');
            $inputenvioreal = $this->input->post('inputenvioreal');
            
            $inputrecepcionproyectado = $this->input->post('inputrecepcionproyectado');
            $inputrecepcionpresupuesto = $this->input->post('inputrecepcionpresupuesto');
            $inputrecepcionreal = $this->input->post('inputrecepcionreal');


        

            $calculoenvioproyectado=$inputenvioproyectado-$inputPresupuesto;
            $calculoenviopresupuesto=$inputenviopresupuesto-$inputPresupuesto;
            $calculoenvioreal=$inputenvioreal-$inputPresupuesto;


            $calculorecepcionproyectado=$inputrecepcionproyectado+$inputPresupuesto;
            $calculorecepcionpresupuesto=$inputrecepcionpresupuesto+$inputPresupuesto;
            $calculorecepcionreal=$inputrecepcionreal+$inputPresupuesto;


            if($inputLinearecepcion==$inputLineaenvio){
                throw new Exception("No se puede transferir a la misma linea y fase");
            }

            if($calculoenvioproyectado<0){
                throw new Exception("No existe el monto suficiente");
            }

                    
            $arrayDataEnvio = array(
               
                'presupuesto' => $calculoenviopresupuesto,
                'disponible_proyectado' => $calculoenvioproyectado,
                'monto_real' => $calculoenvioreal
             
            );


            
            $arrayDataRecepcion= array(
               
                'presupuesto' => $calculorecepcionpresupuesto,
                'disponible_proyectado' => $calculorecepcionproyectado,
                'monto_real' => $calculorecepcionreal
             
            );

           
           
            $data = $this->m_linea_opex_fase->saveConfigOpexLineaPresupuesto($inputLinearecepcion,$inputLineaenvio,$arrayDataEnvio,$arrayDataRecepcion);
          
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }





    public function saveTableOpexPresupuesto() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            //

            $inputPresupuesto = $this->input->post('inputPresupuesto');
            $inputLinea = $this->input->post('id');

            $presu = $this->input->post('presu');
            $disponible = $this->input->post('disponible');
            $real = $this->input->post('real');


            $presu_=0;
            $disponible_=0; 
            $real_=0;
            

             if($inputPresupuesto>$presu){
                  $acumulador=$inputPresupuesto-$presu;

                  $presu_ = $presu+$acumulador;
                  $disponible_ =$disponible+$acumulador;
                  $real_= $real+$acumulador;

             }else{

                 

                $acumulador=$presu-$inputPresupuesto;

                $presu_= $presu-$acumulador;

                if($presu_<0){
                    throw new Exception("LA CANTIDAD A REDUCIR ES MAYOR AL DISPONIBLE PROYECTADO ACTUAL");
                }
                $disponible_=$disponible-$acumulador;

                if($disponible_<0){
                    throw new Exception("LA CANTIDAD A REDUCIR ES MAYOR AL DISPONIBLE DISPONIBLE ACTUAL");
                }
                $real_= $real-$acumulador;

                if($real_<0){
                    throw new Exception("NO ES POSIBLE RESTAR ESE MONTO POR NO TENER REAL");
                }


             }
           
            $arrayDataOP = array(
                
                'presupuesto' => $presu_,
                'disponible_proyectado' => $disponible_,
                'monto_real' =>  $real_
               
              
            );
            $data = $this->m_linea_opex_fase->saveConfigOpexLineaActuaizar($arrayDataOP,$inputLinea);
          
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }


    public function saveTableOpex() {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            //
            $inputPresupuesto = $this->input->post('inputPresupuesto');
            $inputLinea = $this->input->post('inputLinea');
            $inputFase = $this->input->post('inputFase');
           
            $arrayDataOP = array(
                'idlineaopex' => $inputLinea,
                'idfase' => $inputFase,
                'presupuesto' => $inputPresupuesto,
                'disponible_proyectado' => $inputPresupuesto,
                'monto_real' => $inputPresupuesto
               
              
            );
            $data = $this->m_linea_opex_fase->saveConfigOpexLinea($arrayDataOP,$inputLinea,$inputFase);
          
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
            $data = $this->m_linea_opex_fase->addLinea($arrayData);
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
            $data = $this->m_linea_opex_fase->tipoMargen($idPepBolsa, $arrayData,$pep,$tipo);
            //$data['tablaBandejaSiom'] = $this->getTablaSiom();
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }
    
}