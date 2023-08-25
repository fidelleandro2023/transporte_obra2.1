<?php
class M_registro_oc_solicitud_masivo_cancela extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
	function getInfoSolicitudOCCreaByCodigo($codigo_solicitud){
	    $Query = "SELECT   soc.codigo_solicitud, soc.estado
	               FROM    solicitud_orden_compra soc 	                       
                   WHERE   soc.codigo_solicitud = ?	           
                   LIMIT 1";
	    $result = $this->db->query($Query,array($codigo_solicitud));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}	
	
	function masiveUpdateAtencionCreateSolOC($solicitudes_list) {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
            $this->db->update_batch('solicitud_orden_compra',$solicitudes_list, 'codigo_solicitud');
            log_message('error', $this->db->last_query());
            if($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                throw new Exception('Error al modificar el solicitud_orden_compra');
            }else{	               
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se actualizo correctamente!';
                $this->db->trans_commit();    	                          
            }            
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
	
}