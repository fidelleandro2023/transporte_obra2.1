<?php
class M_transferencia_siom extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
    }

    function insertDetalleSiom($arrayData) {
        $this->db->insert_batch('detalle_siom', $arrayData);
        
        if($this->db->affected_rows() > 0) {
            return array('error' => EXIT_SUCCESS);
        } else {
            return array('error' => EXIT_ERROR, 'msj' => 'error Comunicarse con el programador');
        }
    }
    
    function getCountDetalleSiom($codigoSiom) {
        $sql = "SELECT count(1)count
                  FROM detalle_siom
                 WHERE codigo_siom = ?";
        $result = $this->db->query($sql, array($codigoSiom));
        return $this->db->row_array()['count'];         
    }
}

