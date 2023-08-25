<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 * @author CARLOS ZAVALA C.
 * 18/01/2018
 *
 */
class C_adm_cuadrilla extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_mantenimiento/M_adm_cuadrilla');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    function index() {
        $permisos =  $this->session->userdata('permisosArbol');
        $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_MANTENIMIENTO, ID_PERMISO_HIJO_CUADRILLAS);
        $data['opciones'] = $result['html'];
        $this->load->view('vf_mantenimiento/v_adm_cuadrilla', $data);
    }

    function getCmbsCuadrillas() {
        $arrayDataZonal = $this->m_utils->getAllZonalIndex();
        $arrayZonal = array();
        foreach($arrayDataZonal->result() AS $row) {
            array_push($arrayZonal, $row);
        }
        $arrayDataEmprColab = $this->m_utils->getAllEECC();
        $arrayEmpresaColab = array();
        foreach($arrayDataEmprColab->result() AS $row1) {
            array_push($arrayEmpresaColab, $row1);
        }

        $arrayDataUsuarioCuadrilla = $this->m_utils->getUsuarioCuadrilla();
        $arrayUsuarioCuadrilla = array();
        foreach($arrayDataUsuarioCuadrilla->result() AS $row2) {
            array_push($arrayUsuarioCuadrilla, $row2);
        }
        $data['cmbZonal']   = $arrayZonal;
        $data['cmbEecc']    = $arrayEmpresaColab;
        $data['cmbUsuCua']  = $arrayUsuarioCuadrilla;
        echo json_encode($data); 
    }

    function getComboTipoObra() {
        $arrayDataTipoObra = $this->m_planobra->getComboTipoObra();
        $arrayCmbTipo = array();
        foreach($arrayDataTipoObra AS $row) {
            array_push($arrayCmbTipo, $row);
        }

        $data['cmbTipoObra'] = $arrayCmbTipo;
        echo json_encode($data); 
    }

    function registrarCuadrilla() {
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null; 
        try {
            $idZonal      = $this->input->post('idZonal');
            $idEecc       = $this->input->post('idEecc');
            $nomCuadrilla = $this->input->post('nombCuadrilla');
            //$idUsuario    = $this->input->post('idUsuarioCuad');
    
            if($idZonal == 0 || $idZonal == null) {
                throw new Exception('No ingreso zonal');
            } 

            if($idEecc == 0 || $idEecc == null) {
                throw new Exception('No ingreso empresa colaboradora');
            }

            if($nomCuadrilla == null) {
                throw new Exception('No ingreso nombre de cuadrilla');                
            } 

            // if($idUsuario == null) {
            //     throw new Exception('No ingreso Usuario');                
            // } 
            $array =array();
            // if($idZonal == 8) {
            //     $contt=0;
            //     $arryaLima = array(8,9,10,11,12);
            //     foreach($arryaLima AS $row) {
            //         $arrayDataInsert = array(
            //             'idEecc'        => $idEecc,
            //             'idZonal'       => $row,
            //             'descripcion'   => $nomCuadrilla,
            //             'estado'        => 1,
            //             'id_usuario'    => $this->session->userdata('idPersonaSession'),
            //             'fechaRegistro' => $this->fechaActual()
            //         );
            //         array_push($array, $arrayDataInsert);
            //         $contt++;
            //     }
            // } else {
            $idEccSesion = $this->session->userdata('eeccSession');  
            // if($idEccSesion != 0 || $idEccSesion != 6) {
            //     if($idEecc != $idEccSesion) {
            //         throw new Exception('Seleccione su empresa');
            //     }
            // } 

            // $arrayDataInsert = array(
            //     'idEecc'        => $idEecc,
            //     'idZonal'       => $idZonal,
            //     'descripcion'   => $nomCuadrilla,
            //     'estado'        => 1,
            //     'id_usuario'    => $idUsuario,
            //     'fechaRegistro' => $this->fechaActual()
            // );
            // array_push($array, $arrayDataInsert);
            // }

            $count = $this->M_adm_cuadrilla->getCountUsuario($nomCuadrilla);
            if($count > 0){
                throw new Exception('Ya existe un usuario con ese nombre de cuadrilla, cambie el nombre porfavor!!');
            }else{
                $respuesta = $this->M_adm_cuadrilla->registrarCuadrilla($idEecc, $idZonal, $nomCuadrilla, $this->fechaActual());
            }
            
            if($respuesta == 0) {
                throw new Exception('No se registro');
            }
            if($respuesta == 1) {
                $data['error'] = EXIT_SUCCESS;
            }

            $data['array_cuad'] = $this->tablaCuadrilla();
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);         
    }

    function getTablaCuadrilla() {
        $data['array_cuad'] = $this->tablaCuadrilla();
        echo json_encode($data); 
    }

    function tablaCuadrilla() {
        $arrayDataCuadrilla = array();
        $arrayDataCua = $this->m_utils->getCuadrillaAll();
        foreach($arrayDataCua AS $row) {
            array_push($arrayDataCuadrilla, $row);
        }
        return $arrayDataCuadrilla;
    }
    function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone','America/Lima');
        setlocale(LC_TIME, "es_ES","esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }
}