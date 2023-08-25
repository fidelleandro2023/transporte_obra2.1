<?php
class M_control_diseno_ejec extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
    }

    function getTablaControlTab1() {
        $sql = "     SELECT t.proyectoDesc,
                            t.estacionDesc,
                            t.idProyecto,
                            COUNT(1) total,
                            t.estadoPlanDesc,
                            t.idEstacion
                      FROM (               
                                 SELECT pro.proyectoDesc,
                                        e.estacionDesc,
                                        pro.idProyecto,
                                        es.estadoPlanDesc,
                                        e.idEstacion
                                   FROM subproyectoestacion se,
                                        estacionarea ea,
                                        planobra po,
                                        pre_diseno pre,
                                        estacion e,
                                        subproyecto su,
                                        proyecto pro,
                                        estadoplan es
                                  WHERE se.idEstacionArea = ea.idEstacionArea
                                    AND po.idSubProyecto = se.idSubProyecto
                                    AND ea.idEstacion IN (2,5)
                                    AND po.idEstadoPlan = 2
                                    AND ea.idEstacion = e.idEstacion
                                    AND pre.idEstacion = ea.idEstacion
                                    AND su.idSubProyecto = po.idSubProyecto
                                    AND pre.itemplan = po.itemplan
                                    AND pro.idProyecto = su.idProyecto
                                    AND su.idTipoPlanta = 1
                                    AND es.idEstadoPlan = po.idEstadoPlan
                                -- AND pro.idProyecto = 7
                                GROUP BY pro.idProyecto, e.idEstacion, po.itemPlan
                            )t
                    GROUP BY t.idProyecto, t.idEstacion
                    ORDER BY t.proyectoDesc";
        $result = $this->db->query($sql);
        return $result->result_array();          
    }

    function getTablaControlTab2() {
        $sql = "SELECT po.itemplan, 
                       s.subProyectoDesc,
                       e.estadoPlanDesc,
                       po.fecha_creacion
                  FROM (planobra po,
                        subproyecto s,
                        estadoplan e)
             LEFT JOIN pre_diseno pre ON (po.itemplan = pre.itemplan)
                  WHERE po.idEstadoPlan = 2
                    AND pre.itemplan IS NULL
                    AND s.idSubProyecto = po.idSubProyecto
                    AND s.idTipoPlanta = 1
					AND s.idTipoSubPRoyecto <> 2
                    AND e.idEstadoPlan = po.idEstadoPlan
                    AND s.idSubProyecto NOT IN ( SELECT idSubProyecto 
                                                   FROM subproyecto_cambio_estado
                                                  WHERE idEstadoPlan = 3)";
        $result = $this->db->query($sql);
        return $result->result_array();          
    }

    function getDetalleModalTab1($idProyecto, $idEstacion) {
        $sql = "  SELECT pre.itemPlan,
                         pre.fecha_adjudicacion,
                         pre.fecha_prevista_atencion,
                         usuario_adjudicacion,
                         e.estacionDesc,
                         po.itemplan,
						 su.subProyectoDesc
                    FROM subproyectoestacion se,
                         estacionarea ea,
                         planobra po,
                         pre_diseno pre,
                         estacion e,
                         subproyecto su,
                         proyecto pro,
                         estadoplan es
                   WHERE se.idEstacionArea = ea.idEstacionArea
                     AND po.idSubProyecto = se.idSubProyecto
                     AND po.idEstadoPlan = 2
                     AND ea.idEstacion = e.idEstacion
                     AND ea.idEstacion = pre.idEstacion
                     AND su.idSubProyecto = po.idSubProyecto
                     AND pre.itemplan = po.itemplan
                     AND pro.idProyecto = su.idProyecto
                     AND su.idTipoPlanta = 1
                     AND es.idEstadoPlan = po.idEstadoPlan
                     AND pro.idProyecto = ?
                     AND pre.idEstacion = ?
                     GROUP BY pre.itemplan";
        $result = $this->db->query($sql, array($idProyecto, $idEstacion));
        _log($this->db->last_query());
        return $result->result_array();
    }
}