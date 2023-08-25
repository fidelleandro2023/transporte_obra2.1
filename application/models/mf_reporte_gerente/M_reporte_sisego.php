<?php
class M_reporte_sisego extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
    }
    
    function getTablaReporteSisego($idJefatura, $idEmpresaColab) {
        $sql = "SELECT po.itemplan,
                       s.subproyectoDesc, 
                       po.nombreProyecto,
                       es.estadoPlanDesc, 
                       po.fechaPreliquidacion,
                       e.empresaColabDesc,
                       ce.jefatura,
                       po.operador,
                       po.duracion
                  FROM planobra po,
                       subproyecto s,
                       estadoplan es,                    
                       central    ce,
                       empresacolab e
                WHERE po.idSubProyecto = s.idSubProyecto
                AND es.idEstadoPlan    = po.idEstadoPlan
                AND ce.idEmpresaColab  = e.idEmpresaColab
                AND ce.idCentral       = po.idCentral
                AND ce.idJefatura      = COALESCE(?, ce.idJefatura)
                AND ce.idEmpresaColab  = COALESCE(?, ce.idEmpresaColab)
                AND s.idProyecto = 3
                AND po.idEstadoPlan NOT IN(4)";
        $result = $this->db->query($sql, array($idJefatura, $idEmpresaColab));
        return $result->result_array();
    }

    function getTablaDiseno($itemplan) {
        $sql = "SELECT e.estacionDesc,
                       pre.fecha_adjudicacion,
                       fecha_ejecucion
                  FROM pre_diseno pre,
                       estacion e
                 WHERE itemplan = ?
                   AND e.idEstacion = pre.idEstacion";
        $result = $this->db->query($sql, array($itemplan));
        return $result->result_array();
    }
}