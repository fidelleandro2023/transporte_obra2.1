<?php
class M_bandeja_sub_sin_diseno extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
    }

    function getSubProyecto() {
        $sql = " SELECT s.idSubProyecto AS idSubProy,
                        s.subProyectoDesc,
                        sc.idSubProyecto 
                   FROM subproyecto s LEFT JOIN 
                        subproyecto_cambio_estado sc ON (s.idSubProyecto = sc.idSubProyecto)
                 HAVING sc.idSubProyecto IS NULL";
        $result = $this->db->query($sql);
        return $result->result_array();
    }

    function getSubProyectoSinDiseno() {
        $sql = "SELECT sc.idSubProyecto,
                       sc.idEstadoPlan,
                       s.subProyectoDesc
                  FROM subproyecto_cambio_estado sc,
                        subproyecto s
                 WHERE flgActivo = 1
                   AND s.idSubProyecto = sc.idSubProyecto";
        $result = $this->db->query($sql);
        return $result->result_array();
    }

    function insertSubProyecto($arraySubProyecto) {
        $this->db->insert_batch('subproyecto_cambio_estado', $arraySubProyecto);
        if($this->db->affected_rows() < 1) {
            return array('error' => EXIT_ERROR, 'msj' => 'Error, no se ingreso el subproyecto');
        } else {
            return array('error' => EXIT_SUCCESS);
        }
    }

    function deleteSubProyectoSinDiseno($idSubProyecto) {
        $this->db->where('idSubProyecto', $idSubProyecto);
        $this->db->delete('subproyecto_cambio_estado');
        if($this->db->affected_rows() != 1) {
            return array('error' => EXIT_ERROR, 'msj' => 'Error, no se ingreso el subproyecto');
        } else {
            return array('error' => EXIT_SUCCESS);
        }
    }
}