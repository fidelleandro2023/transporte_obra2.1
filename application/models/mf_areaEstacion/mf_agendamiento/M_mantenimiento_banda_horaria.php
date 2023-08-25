<?php
class M_mantenimiento_banda_horaria extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
    }

    function getBandaHoraria($idBandaHoraria) {
        $sql = "SELECT ba.idBandaHoraria, 
                       ba.horaInicio, 
                       ba.horaFin, 
                       ba.estado, 
                       CONCAT('BH', ' ',ba.horaInicio, ' - ', ba.horaFin)horaInFin 
                  FROM banda_horaria ba
                 WHERE ba.idBandaHoraria = COALESCE(?, ba.idBandaHoraria)";
        $result = $this->db->query($sql, array($idBandaHoraria));     
        return $result->result_array();    
    }

    function insertBandaHoraria($arrayData) {
        $this->db->insert('banda_horaria', $arrayData);

        if($this->db->affected_rows() == 0) {
            $data['error'] = EXIT_ERROR;
			return $data;
		} else {
            $data['error'] = EXIT_SUCCESS;
			return $data;
		}
    }

    function deleteBandaHoraria($idBandaHoraria) {
        $this->db->where('idBandaHoraria', $idBandaHoraria);
        $this->db->delete('banda_horaria');

        if($this->db->affected_rows() < 1) {
            $data['error'] = EXIT_ERROR;
			return $data;
		} else {
            $data['error'] = EXIT_SUCCESS;
			return $data;
		}
    }
}