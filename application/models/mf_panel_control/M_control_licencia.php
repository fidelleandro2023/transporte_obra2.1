<?php
class M_control_licencia extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
    }

    /* function getBandejaGestion() {
        $sql = "SELECT DISTINCT
                        i.itemplan,
                        s.subProyectoDesc,
                        i.idEstacion,
                        e.estacionDesc,
                        i.fecha,
                        dp.poCod,
                        i.porcentaje,
                        (SELECT COUNT(1)  
						   FROM itemplan_estacion_licencia_det ied
						  WHERE ied.itemplan = po.itemplan
                            AND ied.flg_validado = 2
						AND ied.idEstacion   = i.idEstacion) counTrabajados
                  FROM (itemplanestacionavance i,
                        planobra po,
                        subproyecto s,
                        estacion e)
             LEFT JOIN (detalleplan dp, 
                        subproyectoestacion se) 
                    ON (dp.itemplan = i.itemplan
                        AND se.idEstacionArea = CASE WHEN i.idEstacion = 2 THEN 55 
                                                     WHEN i.idEstacion = 5 THEN 56 END
                        AND dp.idSubProyectoEstacion = se.idSubProyectoEstacion)
                 WHERE i.itemplan      = po.itemplan
                   AND e.idEstacion    = i.idEstacion
                   AND s.idSubProyecto = po.idSubProyecto
                   AND i.idEstacion IN (2,5)
                   AND EXTRACT(YEAR FROM i.fecha) = EXTRACT(YEAR FROM CURDATE())
                   AND i.porcentaje    = 100
                   AND dp.poCod IS NULL";
    $result = $this->db->query($sql);
    return $result->result_array(); 
    }
    */
    
    function getBandejaGestion() {
        $sql = "SELECT DISTINCT
                        i.itemplan,
                        s.subProyectoDesc,
                        i.idEstacion,
                        e.estacionDesc,
                        i.fecha,
                        dp.poCod,
                        i.porcentaje,
                        COUNT(1) AS counTrabajados
                  FROM (itemplanestacionavance i,
                        planobra po,
                        subproyecto s,
                        estacion e,
                        itemplan_estacion_licencia_det ied)
             LEFT JOIN (detalleplan dp, 
                        subproyectoestacion se) 
                    ON (dp.itemplan = i.itemplan
                        AND se.idEstacionArea = CASE WHEN i.idEstacion = 2 THEN 55 
                                                     WHEN i.idEstacion = 5 THEN 56 END
                        AND dp.idSubProyectoEstacion = se.idSubProyectoEstacion)
                 WHERE i.itemplan      = po.itemplan
                   AND e.idEstacion    = i.idEstacion
                   AND s.idSubProyecto = po.idSubProyecto
                   AND i.idEstacion IN (2,5)
                   AND EXTRACT(YEAR FROM i.fecha) = EXTRACT(YEAR FROM CURDATE())
                   AND i.porcentaje    = 100
                   AND ied.itemplan = po.itemplan
                   AND ied.flg_validado = 2
                   AND ied.idEstacion   = i.idEstacion
                   AND s.idTipoSubProyecto <> 2
                   AND dp.poCod IS NULL
                   GROUP BY po.itemplan, ied.idEstacion,s.idSubProyecto ";
    $result = $this->db->query($sql);
    return $result->result_array();
    }
}

