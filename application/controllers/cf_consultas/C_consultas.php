<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_consultas extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_utils/m_utils');
        $this->load->model('mf_consultas/m_consultas');
        $this->load->model('mf_ficha_tecnica/m_bandeja_ficha_tecnica');
        $this->load->library('lib_utils');
        $this->load->helper('url');       
    }

    function index() {
        $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){
	           $data["extra"]=' <link rel="stylesheet" href="'.base_url().'public/bower_components/notify/pnotify.custom.min.css">
                                <link rel="stylesheet" href="'.base_url().'public/bower_components/bootstrap-validator/bootstrapValidator.min.css"></link>   
                                <link href="'.base_url().'public/vendors/bower_components/datatables/media/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/><link rel="stylesheet" href="'.base_url().'public/fancy/source/jquery.fancybox.css" type="text/css" media="screen">
                                <link rel="stylesheet" href="'.base_url().'public/css/jasny-bootstrap.min.css">';
	           $data["pagina"]="consultas";
        	   $permisos =  $this->session->userdata('permisosArbol');
        	  // $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_INSPECCIONES, ID_PERMISO_HIJO_REGISTRO_FICHA);
        	  // $data['opciones'] = $result['html'];
        	  // if($result['hasPermiso'] == true){
                $this->load->view('vf_layaout_sinfix/header',$data);
                $this->load->view('vf_layaout_sinfix/cabecera');
                $this->load->view('vf_layaout_sinfix/menu');
                $this->load->view('vf_consultas/v_consultaFormulario',$data);
                $this->load->view('vf_layaout_sinfix/footer');
        	       
        	  // }else{
        	  //     redirect('login','refresh');
	          // }
	   }else{
	       redirect('login','refresh');
	   }
    }

    function getConsultaFormulario() {
        $arrayData = $this->tablaFormulario(NULL, NULL);
        $arrayMaterial = $this->getDataMaterial(NULL, NULL);

        $data['arrayData']     = $arrayData;
        $data['arrayMaterial'] = $arrayMaterial;
        echo json_encode($data);
    }

    function getDetalleFormulario() {
        $itemPlan    = $this->input->post('itemplan');
        $idTipo_obra = $this->input->post('idTipo_obra');

        $arrayData = $this->tablaFormulario($itemPlan, $idTipo_obra);

        $data['arrayDataDetalle'] = $arrayData;

        echo json_encode($data);
    }

    function tablaFormulario($itemPlan, $idTipo_obra) {
        $arrayForm = $this->m_utils->getDataFormularioSisego($itemPlan, $idTipo_obra, ORIGEN_SINFIX);
        $arrayData = array();

        foreach($arrayForm as $row) {
            array_push($arrayData, $row);
        }
        return $arrayData;
    }

    function actualizarDetalleForm() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = '';
        try {
            $jsonDetalle   = $this->input->post('json');
            $listaNomNodos = json_decode($this->input->post('arrayCodigoSelec'), true);

            $jsonDetalle['tipo_obra'] =$jsonDetalle['idTipo_obra'];
            //$this->remover($jsonDetalle);
            unset($jsonDetalle['idTipo_obra']);
            unset($jsonDetalle['cod_nodos']);
            unset($jsonDetalle['usuario']);
    
            $dataVal = $this->m_consultas->actualizarDetalleForm($jsonDetalle['itemplan'], $jsonDetalle['origen'], $jsonDetalle['tipo_obra'], $jsonDetalle);

            if($dataVal == 1) {
                $idUsuario = $this->session->userdata('idPersonaSession');
                $arrayData = array(
                    'tabla'          => 'sinfix',
                    'actividad'      => 'actualizar formulario',
                    'itemplan'       => $jsonDetalle['itemplan'],
                    'fecha_registro' => $this->fechaActual(),
                    'id_usuario'     => $idUsuario  
                );
                $dataVali = $this->m_utils->registrarLogPlanObra($arrayData);
                if($dataVali == 1) {
                    $data['error'] = EXIT_SUCCESS;
                } else {
                    throw new Exception("No se inserto en el log");
                }
            } else {
                throw new Exception("No se actualizo");
            } 
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function remover($valor,$arr) {
        foreach(array_keys($arr, $valor) as $key) 
        {
            unset($arr[$key]);
        }
        return $arr;
    }

    function getArrayComboCodigo() {
        $itemPlan = $this->input->post('itemplan');
        $jefatura    = $this->m_utils->getJefaturaByitemPlan($itemPlan);

        $arrayCodigo = $this->m_utils->getCodigoCentral($jefatura);
        $arrayCmbCodigo = array();
        foreach($arrayCodigo AS $row) {
            array_push($arrayCmbCodigo, $row);
        }
        $data['cmbCodigo']   = $arrayCmbCodigo;
        echo json_encode($data); 
    }

    function getDataMaterial($itemplan, $idFichaTecnica) {
        $dataMaterial = $this->m_consultas->getDataMaterial($itemplan, $idFichaTecnica);
        $arrayMaterial = array();
        foreach($dataMaterial AS $row) {
            array_push($arrayMaterial, $row);
        }
        return $arrayMaterial;
        // $data['arrayMaterial']   = $arrayMaterial;
        // echo json_encode($data); 
    }

    function getDataMaterialRadioButton() {
        $itemplan       = $this->input->post('itemplan');
        $idFichaTecnina = $this->input->post('idFichaTecnica');
        $arrayDataMaterial = $this->getDataMaterial($itemplan, $idFichaTecnina);
        $data['arrayMaterial'] = $arrayDataMaterial;
        echo json_encode($data); 
    }

    function getMaterialDetalle() {
        $idFichaTecnina = $this->input->post('idFichaTecnica');
        $dataDetalleMaterial = $this->m_consultas->getMaterialDetalle($idFichaTecnina);
        $arrayTipoFichaSisego = $this->m_bandeja_ficha_tecnica->getTipoTrabajoFichaTecnica();
        $arrayTipoFicha = array();

        foreach($arrayTipoFichaSisego->result() as $row) {
            array_push($arrayTipoFicha, $row); 
        }

        $arrayDetalleMaterial = array();
        foreach($dataDetalleMaterial AS $row) {
            array_push($arrayDetalleMaterial, $row);
        }
        $data['arrayDetalleMaterial']   = $arrayDetalleMaterial;
        $data['arrayTipoFicha'] = $arrayTipoFicha;
        echo json_encode($data); 
    }

    function updateFichaTecnica() {
        $array = array();
        $arrayJson      = $this->input->post('arrayJsonData');
        $idFichaTecnica = $this->input->post('idFichaTecnica');
        foreach($arrayJson as $row) {
            
            $id=$this->m_consultas->getIdFichaTecnicaXTipoTrabajo($row['id_ficha_tecnica'],
                                                                  $row['id_ficha_tecnica_trabajo']);    
 
            $row['id_ficha_tecnica_x_tipo_trabajo'] = $id;
            array_push($array, $row);
            //$val = $this->m_consultas->updateFichaTecnica($idFichaTecnica, $row['id_ficha_tecnica_trabajo'], $row);
        }
        $val = $this->m_consultas->updateFichaTecnica($idFichaTecnica, $array);
        $data['val'] = $val;
        echo json_encode($data);
    }

    function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone','America/Lima');
        setlocale(LC_TIME, "es_ES","esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }
}