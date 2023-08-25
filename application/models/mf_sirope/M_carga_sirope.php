<?php
class M_carga_sirope extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
	function getInfoItem($itemplan, $id_sirope){
	    $Query = "SELECT       po.itemplan, so.itemplan as item_sirope 
	               FROM        planobra po 
	               LEFT JOIN    sirope_orden_trabajo so
                   ON          po.itemplan = so.itemplan 
	               AND		   so.id =  ?
	               WHERE       po.itemplan = ?";
	    $result = $this->db->query($Query,array($id_sirope, $itemplan));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
	function liquidarOCptrCertificacion($arrayFinalInsert, $arrayFinalUpdate) {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();	        
	        $this->db->insert_batch('sirope_orden_trabajo', $arrayFinalInsert);
	        if ($this->db->trans_status() === FALSE) {
	            $this->db->trans_rollback();
	            throw new Exception('Hubo un error al insertar el itemplan_material_cantidad.');
	        }else{
	            	            log_message('error', 'ok insert: sirope_orden_trabajo');

	            $this->db->update_batch('sirope_orden_trabajo',$arrayFinalUpdate, 'id');
	            if ($this->db->trans_status() === FALSE) {
	                $this->db->trans_rollback();
	                throw new Exception('Hubo un error al actualizar el liquidarOCptrCertificacion.');
	            }else{
	                	                log_message('error', 'ok update: sirope_orden_trabajo');	                 

	                $data['error'] = EXIT_SUCCESS;
	                $data['msj'] = 'Se actualizo correctamente!';
	                $this->db->trans_commit();
	            }
	        }
	        
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
	
}