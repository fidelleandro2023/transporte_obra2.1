<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class C_info_sisego_planobra extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_plan_obra/m_planobra');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
        $this->load->library('excel'); 
    }
    
	public function index()
	{  	   
	    
	   $item = (isset($_GET['item']) ? $_GET['item'] : '');
	   $infoItem = $this->m_planobra->getInfoItemplanSisegoPlanObra($item);
	   $infoOc   = $this->m_planobra->getInfoOc($item);
	   $data['itemplan'] = $item;
	   $data['estado_oc']    = $infoOc['estado_oc'];
	   $data['orden_compra'] = $infoOc['orden_compra'];
	   $data['pep1']         = $infoOc['pep1'];
	   $data['estatus_pep']  = $infoOc['estatus_pep'];
	   foreach ($infoItem->result() as $row){
	       if($row->origen== ID_TIPO_OBRA_FROM_DISENIO){
	           $data['idTipoObra_1'] = $row->tipo_obra;
	           if($row->tipo_obra == ID_TIPO_OBRA_CREACION_NAP){
	               $data['tipo_obra_1']        = $row->descripcion;
	               $data['nap_nombre_1']       = $row->nap_nombre;
	               $data['nap_num_troncal_1']  = $row->nap_num_troncal;
	               $data['nap_cant_hilos_1']   = $row->nap_cant_hilos_habi;
	               $data['nap_nodo_1']         = $row->nap_nodo;
	               $data['nap_coord_x_1']      = $row->nap_coord_x;
	               $data['nap_coord_y_1']      = $row->nap_coord_y;
	               $data['nap_ubicacion_1']    = $row->nap_ubicacion;
	               $data['nap_num_piso_1']     = $row->nap_num_pisos;
				   $data['nap_zona_1']         = $row->nap_zona;
				   $data['nro_odf']            = $row->nro_odf;
				   $data['piso_g']         	   = $row->piso_g;
				   $data['sala']         	   = $row->sala;
				   $data['bandeja']            = $row->bandeja;
				   $data['nro_hilo']           = $row->nro_hilo;
	           }else if($row->tipo_obra == ID_TIPO_OBRA_FO_OSCURA){
	               $data['tipo_obra_1']            = $row->descripcion;
	               $data['fo_oscu_cant_hilos_1']   = $row->fo_oscu_cant_hilos;
				   $data['fo_oscu_cant_nodos_1']   = $row->fo_oscu_cant_nodos;
				   $data['nro_odf']                = $row->nro_odf;
				   $data['piso_g']         	   	   = $row->piso_g;
				   $data['sala']         	   	   = $row->sala;
				   $data['bandeja']            	   = $row->bandeja;
				   $data['nro_hilo']           	   = $row->nro_hilo;
	               $data['nodos_1']                = $this->m_planobra->getNodosByTipoObraItemplan($item, $row->origen);
	           }else if($row->tipo_obra == ID_TIPO_OBRA_TRASLADO){
	               $data['tipo_obra_1']                 = $row->descripcion;
	               $data['trasla_re_cable_externo_1']   = $row->trasla_re_cable_externo;
				   $data['trasla_re_cable_interno_1']   = $row->trasla_re_cable_interno;
				   $data['nro_odf']            			= $row->nro_odf;
				   $data['piso_g']         	   			= $row->piso_g;
				   $data['sala']         	   			= $row->sala;
				   $data['bandeja']            			= $row->bandeja;
				   $data['nro_hilo']           			= $row->nro_hilo;
	           }else if($row->tipo_obra == ID_TIPO_OBRA_FO_TRADICIONAL){
	               $data['tipo_obra_1']                = $row->descripcion;
	               $data['fo_tra_cant_hilos_1']        = $row->fo_tra_cant_hilos;
	               $data['fo_tra_cant_hilos_hab_1']    = $row->fo_tra_cant_hilos_hab;
	           }
	       }else if($row->origen== ID_TIPO_OBRA_FROM_EJECUCION){
	           $data['idTipoObra_2'] = $row->tipo_obra;
	           if($row->tipo_obra == ID_TIPO_OBRA_CREACION_NAP){
	               $data['tipo_obra_2']        = $row->descripcion;
	               $data['nap_nombre_2']       = $row->nap_nombre;
	               $data['nap_num_troncal_2']  = $row->nap_num_troncal;
	               $data['nap_cant_hilos_2']   = $row->nap_cant_hilos_habi;
	               $data['nap_nodo_2']         = $row->nap_nodo;
	               $data['nap_coord_x_2']      = $row->nap_coord_x;
	               $data['nap_coord_y_2']      = $row->nap_coord_y;
	               $data['nap_ubicacion_2']    = $row->nap_ubicacion;
	               $data['nap_num_piso_2']     = $row->nap_num_pisos;
				   $data['nap_zona_2']         = $row->nap_zona;
				   $data['nro_odf']            = $row->nro_odf;
				   $data['piso_g']         	   = $row->piso_g;
				   $data['sala']         	   = $row->sala;
				   $data['bandeja']            = $row->bandeja;
				   $data['nro_hilo']           = $row->nro_hilo;
	           }else if($row->tipo_obra == ID_TIPO_OBRA_FO_OSCURA){
	               $data['tipo_obra_2']            = $row->descripcion;
	               $data['fo_oscu_cant_hilos_2']   = $row->fo_oscu_cant_hilos;
	               $data['fo_oscu_cant_nodos_2']   = $row->fo_oscu_cant_nodos;
				   $data['nodos_2']                  = $this->m_planobra->getNodosByTipoObraItemplan($item, $row->origen);
				   $data['nro_odf']            = $row->nro_odf;
				   $data['piso_g']         	   = $row->piso_g;
				   $data['sala']         	   = $row->sala;
				   $data['bandeja']            = $row->bandeja;
				   $data['nro_hilo']           = $row->nro_hilo;
	           }else if($row->tipo_obra == ID_TIPO_OBRA_TRASLADO){
	               $data['tipo_obra_2']                 = $row->descripcion;
	               $data['trasla_re_cable_externo_2']   = $row->trasla_re_cable_externo;
				   $data['trasla_re_cable_interno_2']   = $row->trasla_re_cable_interno;
				   $data['nro_odf']            = $row->nro_odf;
				   $data['piso_g']         	   = $row->piso_g;
				   $data['sala']         	   = $row->sala;
				   $data['bandeja']            = $row->bandeja;
				   $data['nro_hilo']           = $row->nro_hilo;
	           }else if($row->tipo_obra == ID_TIPO_OBRA_FO_TRADICIONAL){
	               $data['tipo_obra_2']                = $row->descripcion;
	               $data['fo_tra_cant_hilos_2']        = $row->fo_tra_cant_hilos;
				   $data['fo_tra_cant_hilos_hab_2']    = $row->fo_tra_cant_hilos_hab;
				   $data['nro_odf']            = $row->nro_odf;
				   $data['piso_g']         	   = $row->piso_g;
				   $data['sala']         	   = $row->sala;
				   $data['bandeja']            = $row->bandeja;
				   $data['nro_hilo']           = $row->nro_hilo;
	           }
	       }
	   }    
    
        $this->load->view('vf_plan_obra/v_info_sisego_planobra',$data);
        	 
    } 
    
}