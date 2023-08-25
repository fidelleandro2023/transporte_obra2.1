<?php
class M_transferencia_sam extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
    }

    function insertDetalleSam($arrayData) {
        $this->db->insert_batch('matriz_sam', $arrayData);
        
        if($this->db->affected_rows() > 0) {
            return array('error' => EXIT_SUCCESS);
        } else {
            return array('error' => EXIT_ERROR, 'msj' => 'error Comunicarse con el programador');
        }
    }
    
}

