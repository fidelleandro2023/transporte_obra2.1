<?php
class M_integracion_sisego_web extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
	function getInfoItempla($itemplan) {
	    $Query = "SELECT * FROM planobra where itemplan = ?";
	    $result = $this->db->query($Query, array($itemplan));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
		
	function updateTramaCancelSisego($arrayUpdate, $arrayLogPo) {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $this->db->where('itemplan', $arrayUpdate['itemplan']);
	        $this->db->update('planobra', $arrayUpdate);
	        if($this->db->trans_status() === FALSE) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al modificar el planobra');
	        }else{
	            $this->db->insert('log_planobra', $arrayLogPo);
	            if($this->db->affected_rows() != 1) {
	                $this->db->trans_rollback();
	                throw new Exception('Error al insertar en log_planobra');
	            }else{	               
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