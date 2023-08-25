<?php
class M_switch_siom extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
    }

    function getSwitch($idSubProyecto=null, $jefatura=null, $idEmpresaColab=null) {
        $sql = "SELECT s.idSwitchSiom,
                       e.empresaColabDesc,
                       e.idEmpresaColab,
                       s.jefatura,
                       s.idSubProyecto,
                       sb.subProyectoDesc,
                       DATE(s.fecha) fecha
                  FROM switch_siom s,
                       empresacolab e,
                       subproyecto sb
                 WHERE s.idEmpresaColab = e.idEmpresaColab
                   AND sb.idSubProyecto = s.idSubProyecto
                   AND s.idSubProyecto  = COALESCE(?, s.idSubProyecto)
                   AND s.jefatura       = COALESCE(?, s.jefatura)
                   AND e.idEmpresaColab = COALESCE(?, e.idEmpresaColab)";
        $result = $this->db->query($sql, array($idSubProyecto, $jefatura, $idEmpresaColab));
        return $result->result();         
    }

    function insertSwitch($arrayData) {
        $this->db->insert('switch_siom', $arrayData);

        if($this->db->affected_rows() != 1) {
            return array('error' => EXIT_ERROR, 'msj' => 'Error al actualizar');
        } else {
            return array('error' => EXIT_SUCCESS, 'msj' => 'Actualizaci&oacute;n correcta');
        }
    }

    function actualizarSwitch($idSwitchSiom, $jsonUpdate) {
        $this->db->where('idSwitchSiom', $idSwitchSiom);
        $this->db->update('switch_siom', $jsonUpdate);

        if($this->db->affected_rows() != 1) {
            return array('error' => EXIT_ERROR,   'msj' => 'Error al actualizar');
        } else {
            return array('error' => EXIT_SUCCESS, 'msj' => 'Actualizaci&oacute;n correcta');
        }
    }
}