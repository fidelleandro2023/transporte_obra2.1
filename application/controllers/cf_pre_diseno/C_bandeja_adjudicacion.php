<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *
 * @author CARLOS ZAVALA C.
 * 18/01/2018
 *
 */
class C_bandeja_adjudicacion extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_pre_diseno/m_bandeja_adjudicacion');
		$this->load->model('mf_servicios/M_integracion_sirope');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
        $this->load->library('zip');
        $this->load->library('table');
    }

	public function index()
	{
        $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
	           $listaEECC = $this->m_utils->getAllEECC();
        	   $data['listaEECC']      = $listaEECC;
              // $data['listaZonal']     = $this->m_utils->getAllZonal();
               $data['cmbProyecto']    = __buildComboProyecto(); 
        	   $data['listaSubProy']   = $this->m_utils->getAllSubProyecto();
        	   $data['listacentral']   = $this->m_utils->getAllNodos();
        	   $data['listEECCDi']     = $listaEECC;
               $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo(NULL,'','','',NULL,NULL,NULL,NULL);
               $data['nombreUsuario']  =  $this->session->userdata('usernameSession');
               $data['perfilUsuario']  =  $this->session->userdata('descPerfilSession');
               $data['cmbEstacion']    = __buildComboEstacion(1);
               $data['cmbPlanta']      = __buildComboPlanta();
               $data['cmbJefatura']    = __buildComboJefatura();
            //    $data['cmbSubProyecto'] = $this->cmbSubProyecto();

        	   $permisos =  $this->session->userdata('permisosArbol');
        	   $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_DISENO, ID_PERMISO_HIJO_BANDEJA_ADJUDICACION);
        	   $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	       $this->load->view('vf_prediseno/v_bandeja_adjudicacion',$data);
        	   }else{
        	       redirect('login','refresh');
	           }
	   }else{
	       redirect('login','refresh');
	   }
    }

    public function getInfoByItemplan(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $itemplan   = $this->input->post('item');
            $has_fo		= $this->input->post('has_fo');
            $has_coax	= $this->input->post('has_coax');

            $datos = $this->m_utils->getInfoItemplan($itemplan);
            
            $array = array('itemPlanPreDi'      => $itemplan,
                            'has_fo'  => $has_fo,
                            'has_coax'    => $has_coax,
                            'fecha_inicio' => $datos['fechaInicio']
            );
            $this->session->set_userdata($array);
            $data['subpro'] = $datos['idSubProyecto'];
          //  $data['mdf'] = $datos[''];
            $data['empresacolab'] = $datos['idEmpresaColab'];
            $data['central']      = $datos['idCentral'];
			$data['fec_inicio'] = $datos['fechaInicio'];
            $data['error']        = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function adjudicarItemplan(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $itemplan           = $this->input->post('itemplan');
            $subproyecto        = $this->input->post('selectSubAdju');
            $empresaColabDiseno = $this->input->post('selectEECCDiseno');
            $central            = $this->input->post('selectCentral');
            $idFechaPreAtencionCoax    = $this->input->post('idFechaPreAtencionCoax');
            $idFechaPreAtencionFo    = $this->input->post('idFechaPreAtencionFo');

            $SubProy = $this->input->post('subProy');
            $eecc    = $this->input->post('eecc');
            $zonal   = $this->input->post('zonal');
            $mesEjec = $this->input->post('mes');

            // date_default_timezone_set(America/Lima);

            $data = $this->m_bandeja_adjudicacion->adjudicarItemplan($itemplan,$subproyecto,$central,$empresaColabDiseno, $idFechaPreAtencionCoax, $idFechaPreAtencionFo);
            
			 //AQUI ENVIO A SIROPE....!
            /**26.09.2019 czavalacas - sisegos al tener adjudicacion automatica crea su ot en sirope**/
            $has_fo      = $this->session->userdata('has_fo');
            if($has_fo == 1){//si cuenta con estacion FO
                $dataCentral = $this->m_utils->getInfocentralByIdCentral($central);
                if($dataCentral != null){
                   # if($dataCentral['jefatura']=='LIMA' || $dataCentral['jefatura']=='PIURA' || $dataCentral['jefatura']=='CAJAMARCA' || $dataCentral['jefatura']=='TRUJILLO' || $dataCentral['jefatura']=='CHIMBOTE' || $dataCentral['jefatura']=='AREQUIPA' || $dataCentral['jefatura']=='CUSCO'){//validacion temporal sisegos solo lima
                        //comentado25.03.2020czavalaonuevomodelonoenviatrama
						//$this->M_integracion_sirope->execWs($itemplan, $itemplan.'FO', $this->session->userdata('fecha_inicio'), $idFechaPreAtencionFo);
                   # }
                }else{
                    //no se detecto un idCentral..
                }
            }
            /****************************************************************************************************/
            /****************************************************************************************************/
            
            $SubProy    = $this->input->post('subProy');
            $eecc       = $this->input->post('eecc');
            $zonal      = $this->input->post('zonal');
            $itemPlan   = $this->input->post('itemplanFil');
            $mesEjec    = $this->input->post('mes');
            $expediente = $this->input->post('expediente');
            $idEstacion = $this->input->post('idEstacion');
            $idTipoPlan = $this->input->post('idTipoPlan');
            $jefatura   = $this->input->post('jefatura');
            $idProyecto = $this->input->post('idProyecto');
            
            $arraySubProy = explode(',', $SubProy)[0];
            $array = null;
            $count = 0;
            
            $idEstacion = ($idEstacion == '') ? NULL : $idEstacion;
            $idTipoPlan = ($idTipoPlan == '') ? NULL : $idTipoPlan;
            $jefatura   = ($jefatura   == '') ? NULL : $jefatura;
            $idProyecto = ($idProyecto == '') ? NULL : $idProyecto;
            $SubProy    = ($SubProy == '')    ? NULL : $SubProy;
            
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($SubProy,$zonal, $eecc,$mesEjec,$idEstacion,$idTipoPlan,$jefatura,$idProyecto, $arraySubProy);
            
           // $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($SubProy,$eecc,$zonal,$mesEjec,null,null,null, null);

        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }


    function makeHTLMTablaBandejaAprobMo($SubProy,$eecc,$zonal,$mesEjec, $idEstacion, $idTipoPlan, $jefatura, $idProyecto, $arraySubProy=null){
        $listaPTR = $this->m_bandeja_adjudicacion->getBandejaAdjudicacion($SubProy, $eecc, $zonal, $mesEjec, $idEstacion, $idTipoPlan, $jefatura, $idProyecto, $arraySubProy);
        $tmpl = array(  'table_open'  => '<table id="data-table" class="table table-bordered">',
                        'table_close' => '</table>');

        $this->table->set_template($tmpl);
        $style= 'font-weight: bolder; color: white; background-color: var(--celeste_telefonica); text-align: center';      
        $head_0 = array('data' => ''              , 'class' => 'text-center', 'style' => $style);
        $head_1 = array('data' => 'Item Plan'     , 'class' => 'text-center', 'style' => $style);
        $head_2 = array('data' => 'Estacion'      , 'class' => 'text-center', 'style' => $style);        
        $head_3 = array('data' => 'Indicador'     , 'class' => 'text-center', 'style' => $style);
        $head_4 = array('data' => 'Sub Proy'      , 'class' => 'text-center', 'style' => $style);
        $head_5 = array('data' => 'Jefatura'      , 'class' => 'text-center', 'style' => $style);
        $head_6 = array('data' => 'EECC'          , 'class' => 'text-center', 'style' => $style);
        $head_7 = array('data' => 'Fec. Prevista' , 'class' => 'text-center', 'style' => $style);
        $head_8 = array('data' => 'Estado'        , 'class' => 'text-center', 'style' => $style);

        $this->table->set_heading($head_0, $head_1, $head_2, $head_3, $head_4, $head_5, $head_6, $head_7, $head_8);
	    // if($listaPTR!=null){
                foreach($listaPTR->result() as $row){
                    $countParalizados = $this->m_utils->countParalizados($row->itemplan, FLG_ACTIVO, ORIGEN_WEB_PO);
                    $icon = '<a data-has_coax="'.$row->coaxial.'" data-has_fo="'.$row->fo.'" data-item="'.$row->itemplan.'" onclick="adjudicarDiseno($(this))" style="margin-left: 30%;"><img alt="Editar" height="25px" width="25px" src="public/img/iconos/check_24016.png"></a>';
                    
                   if($countParalizados > 0) {
                        $icon  = 'PARALIZADO';
                        
                   } 
                    $row_0 = array('data' => $icon                  , 'class' => 'text-left');           
                    $row_1 = array('data' => $row->itemplan, 'class' => 'text-center docen');
                    $row_2 = array('data' => (($row->coaxial == 1 && $row->fo == 1) ? 'DUO' : (($row->coaxial == 1 && $row->fo == 0) ? 'COAXIAL' : (($row->coaxial == 0 && $row->fo == 1) ? 'FO' : ''))) , 'class' => 'text-center');
                    $row_3 = array('data' => $row->indicador        , 'class' => 'text-center');
                    $row_4 = array('data' => $row->subProyectoDesc  , 'class' => 'text-center');
                    $row_5 = array('data' => $row->zonalDesc        , 'class' => 'text-center');
                    $row_6 = array('data' => $row->empresaColabDesc , 'class' => 'text-center');
                    $row_7 = array('data' => $row->fechaPrevEjec    , 'class' => 'text-center');
                    $row_8 = array('data' => $row->estadoPlanDesc   , 'class' => 'text-center');

                    $this->table->add_row($row_0, $row_1, $row_2, $row_3, $row_4, $row_5, $row_6, $row_7, $row_8);
                 }
        // }
        return utf8_decode($this->table->generate());
    }

      function filtrarTabla(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $SubProy    = $this->input->post('subProy');
            $eecc       = $this->input->post('eecc');
            $zonal      = $this->input->post('zonal');
            $itemPlan   = $this->input->post('itemplanFil');
            $mesEjec    = $this->input->post('mes');
            $expediente = $this->input->post('expediente');
            $idEstacion = $this->input->post('idEstacion');
            $idTipoPlan = $this->input->post('idTipoPlan');
            $jefatura   = $this->input->post('jefatura');            
            $idProyecto = $this->input->post('idProyecto');

            $arraySubProy = explode(',', $SubProy)[0];
            $array = null;
            $count = 0;
            // foreach($arraySubProy AS $row) {
            //     $count++;
            //     $array .= $row;
            //     if(count($arraySubProy) > $count) {
            //         $array .= ',';
            //     }
            //   
            // }

            $idEstacion = ($idEstacion == '') ? NULL : $idEstacion;
            $idTipoPlan = ($idTipoPlan == '') ? NULL : $idTipoPlan;
            $jefatura   = ($jefatura   == '') ? NULL : $jefatura;
            $idProyecto = ($idProyecto == '') ? NULL : $idProyecto;
            $SubProy    = ($SubProy == '')    ? NULL : $SubProy;

            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($SubProy,$zonal, $eecc,$mesEjec,$idEstacion,$idTipoPlan,$jefatura,$idProyecto, $arraySubProy);
            
            $data['error'] = EXIT_SUCCESS;
        }catch(Exception $e){
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

    function cmbPlanta() {
        $arrayPlanta = $this->m_utils->getPlantaCmb();
        $cmb = null;
        foreach($arrayPlanta AS $row) {
            $cmb .= '<option value="'.$row->idTipoPlanta.'">'.utf8_decode($row->tipoPlantaDesc).'</option>';
        }
        return $this->cmbHTML($cmb, 'idTipoPlanta');
    }

    function cmbSubProyecto() {
        $arraySubProyecto = $this->m_utils->getAllSubProyecto();
        $cmb = null;
        foreach($arraySubProyecto AS $row) {
            $cmb .= '<option value="'.$row->subProyectoDesc.'">'.utf8_decode($row->subProyectoDesc).'</option>';
        }
        return $cmb;
    }

    function cmbHTML($html, $id) {
            $cmbHtml = '<select id="'.$id.'" class="select2" onchange="filtrarTabla()">
                            <option value="">Seleccionar</option>
                            '.$html.'
                        </select>';
            return $cmbHtml;
        }
        
    function insertFile() {
        $itemPlan = $this->session->userdata('itemPlanPreDi');
        $file     = $_FILES ["file"] ["name"];
        $filetype = $_FILES ["file"] ["type"];
        $filesize = $_FILES ["file"] ["size"];
        
        $ubicacion = 'uploads/ejecucion/'.$itemPlan;
        if (!is_dir($ubicacion)) {
            mkdir ('uploads/ejecucion/'.$itemPlan, 0777);
        }
        $descEstacion = 'COAXIAL';        
        $subCarpeta = 'uploads/ejecucion/'.$itemPlan.'/'.$itemPlan.'_'.$descEstacion;    
        $file2 = utf8_decode($file);
        if (!is_dir($subCarpeta))
            mkdir ( $subCarpeta, 0777 );
            if (utf8_decode($file) && move_uploaded_file($_FILES["file"]["tmp_name"], $subCarpeta."/".$file2 )) {
            }
        $data['error'] = EXIT_SUCCESS;
        echo json_encode(array_map('utf8_encode', $data));
    }
    
    function insertFile2() {
        $itemPlan = $this->session->userdata('itemPlanPreDi');
        $file     = $_FILES ["file"] ["name"];
        $filetype = $_FILES ["file"] ["type"];
        $filesize = $_FILES ["file"] ["size"];
        
        $ubicacion = 'uploads/ejecucion/'.$itemPlan;
        if (!is_dir($ubicacion)) {
            mkdir ('uploads/ejecucion/'.$itemPlan, 0777);
        }
        $descEstacion = 'FO';
        $subCarpeta = 'uploads/ejecucion/'.$itemPlan.'/'.$itemPlan.'_'.$descEstacion;
        $file2 = utf8_decode($file);
        if (!is_dir($subCarpeta))
            mkdir ( $subCarpeta, 0777 );
            if (utf8_decode($file) && move_uploaded_file($_FILES["file"]["tmp_name"], $subCarpeta."/".$file2 )) {
            }
            $data['error'] = EXIT_SUCCESS;
            echo json_encode(array_map('utf8_encode', $data));
    }

    function comprimirFiles() {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
            $from  = $this->input->post('from');
            $descEstacion = (($from == 1 ) ? 'COAXIAL' : 'FO'); 
            $itemplan     = $this->session->userdata('itemPlanPreDi');
            $subCarpeta   = 'uploads/ejecucion/'.$itemplan.'/'.$itemplan.'_'.$descEstacion;
            $idEstacion   = (($from == 1 ) ? ID_ESTACION_COAXIAL : ID_ESTACION_FO); 

            $this->zip->read_dir($subCarpeta,false);
            $fileName = $descEstacion.'_'.rand(1, 100).date("dmhis").'.zip';
            $this->zip->archive('uploads/ejecucion/'.$itemplan.'/'.$fileName);
            $data = $this->m_bandeja_adjudicacion->registrarNombreArchivo($itemplan, $fileName, $idEstacion);
            $this->rrmdir($subCarpeta);
            $data['error'] = EXIT_SUCCESS;
            $this->zip->download($fileName);
        }catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function rrmdir($src) {
        $dir = opendir($src);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                $full = $src . '/' . $file;
                if ( is_dir($full) ) {
                    $this->rrmdir($full);
                }
                else {
                    unlink($full);
                }
            }
        }
        closedir($dir);
        rmdir($src);
    }

    function filtrarSubProyecto() {
        $idProyecto = $this->input->post('idProyecto');
        $data['cmbSubProyecto'] = __buildSubProyecto($idProyecto, ID_TIPO_PLANTA_EXTERNA, 1);
        
        echo json_encode(array_map('utf8_encode', $data));
    }
}