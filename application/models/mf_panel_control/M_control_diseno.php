<?php
class M_control_diseno extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
    }

    function getDataSinValid() {
        $sql = "SELECT DISTINCT 
                       ppo.itemplan,
                       s.subProyectoDesc,
                       ppo.codigo_po, 
                       ppo.estado_po,
                       UPPER(pe.estado) estado
                  FROM planobra po,
                       pre_diseno pre, 
                       subproyecto s, 
                       planobra_po ppo,
                       po_estado pe,
                       detalleplan dp,
                       subproyectoestacion se
                 WHERE (has_sirope_diseno = 1 OR has_sirope = 1)
                   AND ppo.estado_po = pe.idPoEstado
                   AND ppo.itemplan = po.itemplan 
                   AND ppo.idEstacion = 1
                   AND estado_po NOT IN (5,6,7,8)
                   AND s.idSubProyecto = po.idSubProyecto 
                   AND s.idTipoComplejidad = 1
                   AND pre.itemPlan = po.itemplan
                   AND pre.idEstacion = 5
                   AND dp.poCod  = ppo.codigo_po
                   AND se.idSubProyectoEstacion = dp.idSubProyectoEstacion
                   AND se.idEstacionArea = 2
                   AND pre.fecha_ejecucion IS NOT NULL";
    $result = $this->db->query($sql);
    return $result->result_array();
    }

    function getDataSinPoDiseno() {
        $sql = "SELECT po.itemplan,
                       pre.idEstacion,
                       s.subProyectoDesc,
                       tc.complejidadDesc,
                       tc.idTipoComplejidad,
                       (SELECT CASE WHEN s.idTipoComplejidad = 1 THEN SUM(CASE WHEN flg_tipo_area = 1 THEN 1 ELSE 0 END)
                                    WHEN s.idTipoComplejidad = 2 THEN SUM(CASE WHEN flg_tipo_area = 2 THEN 1 ELSE 0 END) END

                          FROM planobra_po ppo
                         WHERE ppo.itemplan = po.itemplan
                           AND ppo.idEstacion = pre.idEstacion)countMoMat,
                        e.estacionDesc
                FROM (pre_diseno pre,
                        planobra    po,
                        fase        f,
                        tipo_complejidad tc,
                        subproyecto s,
                        estacion e)
                LEFT JOIN (detalleplan dp, 
                        subproyectoestacion se) 
                    ON (dp.itemplan = po.itemplan
                        AND idEstacionArea IN (1,2)
                        AND dp.idSubProyectoEstacion = se.idSubProyectoEstacion)
                WHERE pre.itemplan = po.itemplan
                    AND f.faseDesc = EXTRACT(YEAR FROM CURDATE())
                    AND f.idFase = po.idFase
                    AND pre.fecha_ejecucion IS NOT NULL
                    AND dp.poCod IS NULL
                    AND s.idSubProyecto = po.idSubProyecto
                    AND tc.idTipoComplejidad = s.idTipoComplejidad
                    AND s.idTipoSubProyecto <> 2
                    AND po.idEstadoPlan NOT IN (6,10)
                    AND pre.idEstacion = e.idEstacion";
    $result = $this->db->query($sql);
    return $result->result_array();
    }
    
    function getCountComplejAltaPO($itemplan, $idEstacion) {
        $sql = "SELECT COUNT(1) as count
                  FROM planobra_po ppo,
                       planobra_po_detalle_mo dp,
                       partidas pa
                 WHERE ppo.idEstacion = ?
                   AND ppo.itemplan   = ?
                   AND ppo.flg_tipo_area = 2
                   AND dp.idActividad   = pa.idActividad
                   AND dp.codigo_po = ppo.codigo_po
                   AND pa.codigo NOT IN ('21004-8', '21001-3', '69900-4', '69901-2', '36900-4', '36901-2')";
        $result = $this->db->query($sql, array($idEstacion, $itemplan));
        return $result->row_array()['count'];          
    }
}

