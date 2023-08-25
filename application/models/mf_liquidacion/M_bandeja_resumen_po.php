<?php
class M_bandeja_resumen_po extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
    }

    function getBandejaResumenPO($idEmpresaColab, $idJefatura) {

        $sql = " SELECT t.situacion,
                        t.idFlujo,
                        r.flujo,
                        SUM(CASE WHEN DATEDIFF(CURDATE(), DATE(fecha)) <= 7 AND flujo = 1 THEN 1 
                                WHEN DATEDIFF(CURDATE(), DATE(fecha)) <= 7 AND flujo = 2 THEN 1 
                                WHEN DATEDIFF(CURDATE(), DATE(fecha)) <= 7 AND flujo = 3 THEN 1 
                                WHEN DATEDIFF(CURDATE(), DATE(fecha)) <= 7 AND flujo = 4 THEN 1 
                                WHEN DATEDIFF(CURDATE(), DATE(fecha)) <= 7 AND flujo = 5 THEN 1 
                                WHEN DATEDIFF(CURDATE(), DATE(fecha)) <= 7 AND flujo = 6 THEN 1 
                                WHEN DATEDIFF(CURDATE(), DATE(fecha)) <= 7 AND flujo = 7 THEN 1 
                                WHEN DATEDIFF(CURDATE(), DATE(fecha)) <= 7 AND flujo = 8 THEN 1 
                                WHEN DATEDIFF(CURDATE(), DATE(fecha)) <= 7 AND flujo = 9 THEN 1 
                                WHEN DATEDIFF(CURDATE(), DATE(fecha)) <= 7 AND flujo = 10 THEN 1 
                                ELSE 0 END) AS dia_0_7,
                        SUM(CASE WHEN DATEDIFF(CURDATE(), DATE(fecha)) > 7 AND DATEDIFF(CURDATE(), DATE(fecha)) <= 10 AND flujo = 1 THEN 1 
                                WHEN DATEDIFF(CURDATE(), DATE(fecha)) > 7 AND DATEDIFF(CURDATE(), DATE(fecha)) <= 10 AND flujo = 2 THEN 1 
                                WHEN DATEDIFF(CURDATE(), DATE(fecha)) > 7 AND DATEDIFF(CURDATE(), DATE(fecha)) <= 10 AND flujo = 3 THEN 1 
                                WHEN DATEDIFF(CURDATE(), DATE(fecha)) > 7 AND DATEDIFF(CURDATE(), DATE(fecha)) <= 10 AND flujo = 4 THEN 1 
                                WHEN DATEDIFF(CURDATE(), DATE(fecha)) > 7 AND DATEDIFF(CURDATE(), DATE(fecha)) <= 10 AND flujo = 5 THEN 1 
                                WHEN DATEDIFF(CURDATE(), DATE(fecha)) > 7 AND DATEDIFF(CURDATE(), DATE(fecha)) <= 10 AND flujo = 6 THEN 1 
                                WHEN DATEDIFF(CURDATE(), DATE(fecha)) > 7 AND DATEDIFF(CURDATE(), DATE(fecha)) <= 10 AND flujo = 7 THEN 1 
                                WHEN DATEDIFF(CURDATE(), DATE(fecha)) > 7 AND DATEDIFF(CURDATE(), DATE(fecha)) <= 10 AND flujo = 8 THEN 1 
                                WHEN DATEDIFF(CURDATE(), DATE(fecha)) > 7 AND DATEDIFF(CURDATE(), DATE(fecha)) <= 10 AND flujo = 9 THEN 1 
                                WHEN DATEDIFF(CURDATE(), DATE(fecha)) > 7 AND DATEDIFF(CURDATE(), DATE(fecha)) <= 10 AND flujo = 10 THEN 1 
                                ELSE 0 END) AS dia_7_10, 
                        SUM(CASE WHEN DATEDIFF(CURDATE(), DATE(fecha)) > 10 AND DATEDIFF(CURDATE(), DATE(fecha)) <= 30 AND flujo = 1 THEN 1 
                                WHEN DATEDIFF(CURDATE(), DATE(fecha)) > 10 AND DATEDIFF(CURDATE(), DATE(fecha)) <= 30 AND flujo = 2 THEN 1 
                                WHEN DATEDIFF(CURDATE(), DATE(fecha)) > 10 AND DATEDIFF(CURDATE(), DATE(fecha)) <= 30 AND flujo = 3 THEN 1 
                                WHEN DATEDIFF(CURDATE(), DATE(fecha)) > 10 AND DATEDIFF(CURDATE(), DATE(fecha)) <= 30 AND flujo = 4 THEN 1 
                                WHEN DATEDIFF(CURDATE(), DATE(fecha)) > 10 AND DATEDIFF(CURDATE(), DATE(fecha)) <= 30 AND flujo = 5 THEN 1 
                                WHEN DATEDIFF(CURDATE(), DATE(fecha)) > 10 AND DATEDIFF(CURDATE(), DATE(fecha)) <= 30 AND flujo = 6 THEN 1 
                                WHEN DATEDIFF(CURDATE(), DATE(fecha)) > 10 AND DATEDIFF(CURDATE(), DATE(fecha)) <= 30 AND flujo = 7 THEN 1 
                                WHEN DATEDIFF(CURDATE(), DATE(fecha)) > 10 AND DATEDIFF(CURDATE(), DATE(fecha)) <= 30 AND flujo = 8 THEN 1
                                WHEN DATEDIFF(CURDATE(), DATE(fecha)) > 10 AND DATEDIFF(CURDATE(), DATE(fecha)) <= 30 AND flujo = 9 THEN 1
                                WHEN DATEDIFF(CURDATE(), DATE(fecha)) > 10 AND DATEDIFF(CURDATE(), DATE(fecha)) <= 30 AND flujo = 10 THEN 1 
                                ELSE 0 END) AS dia_10_30,
                        SUM(CASE WHEN (DATEDIFF(CURDATE(), DATE(fecha)) > 30 OR fecha IS NULL) AND flujo = 1 THEN 1
                                WHEN (DATEDIFF(CURDATE(), DATE(fecha)) > 30 OR fecha IS NULL) AND flujo = 2 THEN 1
                                WHEN (DATEDIFF(CURDATE(), DATE(fecha)) > 30 OR fecha IS NULL) AND flujo = 3 THEN 1 
                                WHEN (DATEDIFF(CURDATE(), DATE(fecha)) > 30 OR fecha IS NULL) AND flujo = 4 THEN 1 
                                WHEN (DATEDIFF(CURDATE(), DATE(fecha)) > 30 OR fecha IS NULL) AND flujo = 5 THEN 1
                                WHEN (DATEDIFF(CURDATE(), DATE(fecha)) > 30 OR fecha IS NULL) AND flujo = 6 THEN  1
                                WHEN (DATEDIFF(CURDATE(), DATE(fecha)) > 30 OR fecha IS NULL) AND flujo = 7 THEN 1
                                WHEN (DATEDIFF(CURDATE(), DATE(fecha)) > 30 OR fecha IS NULL) AND flujo = 8 THEN 1
                                WHEN (DATEDIFF(CURDATE(), DATE(fecha)) > 30 OR fecha IS NULL) AND flujo = 9 THEN 1 
                                WHEN (DATEDIFF(CURDATE(), DATE(fecha)) > 30 OR fecha IS NULL) AND flujo = 10 THEN 1 
                            ELSE 0 END) AS dia_30_mas,
                    SUM(CASE WHEN DATEDIFF(CURDATE(), DATE(fecha)) <= 7 AND flujo = 1 THEN ppo.costo_total 
                                WHEN DATEDIFF(CURDATE(), DATE(fecha)) <= 7 AND flujo = 2 THEN ppo.costo_total  
                                WHEN DATEDIFF(CURDATE(), DATE(fecha)) <= 7 AND flujo = 3 THEN ppo.costo_total 
                                WHEN DATEDIFF(CURDATE(), DATE(fecha)) <= 7 AND flujo = 4 THEN ppo.costo_total 
                                WHEN DATEDIFF(CURDATE(), DATE(fecha)) <= 7 AND flujo = 5 THEN ppo.costo_total  
                                WHEN DATEDIFF(CURDATE(), DATE(fecha)) <= 7 AND flujo = 6 THEN ppo.costo_total  
                                WHEN DATEDIFF(CURDATE(), DATE(fecha)) <= 7 AND flujo = 7 THEN ppo.costo_total  
                                WHEN DATEDIFF(CURDATE(), DATE(fecha)) <= 7 AND flujo = 8 THEN ppo.costo_total  
                                WHEN DATEDIFF(CURDATE(), DATE(fecha)) <= 7 AND flujo = 9 THEN ppo.costo_total  
                                WHEN DATEDIFF(CURDATE(), DATE(fecha)) <= 7 AND flujo = 10 THEN ppo.costo_total  
                                ELSE 0 END) AS total_0_7,
                        SUM(CASE WHEN DATEDIFF(CURDATE(), DATE(fecha)) > 7 AND DATEDIFF(CURDATE(), DATE(fecha)) <= 10 AND flujo = 1 THEN ppo.costo_total  
                                WHEN DATEDIFF(CURDATE(), DATE(fecha)) > 7 AND DATEDIFF(CURDATE(), DATE(fecha)) <= 10 AND flujo = 2 THEN ppo.costo_total  
                                WHEN DATEDIFF(CURDATE(), DATE(fecha)) > 7 AND DATEDIFF(CURDATE(), DATE(fecha)) <= 10 AND flujo = 3 THEN ppo.costo_total  
                                WHEN DATEDIFF(CURDATE(), DATE(fecha)) > 7 AND DATEDIFF(CURDATE(), DATE(fecha)) <= 10 AND flujo = 4 THEN ppo.costo_total  
                                WHEN DATEDIFF(CURDATE(), DATE(fecha)) > 7 AND DATEDIFF(CURDATE(), DATE(fecha)) <= 10 AND flujo = 5 THEN ppo.costo_total  
                                WHEN DATEDIFF(CURDATE(), DATE(fecha)) > 7 AND DATEDIFF(CURDATE(), DATE(fecha)) <= 10 AND flujo = 6 THEN ppo.costo_total  
                                WHEN DATEDIFF(CURDATE(), DATE(fecha)) > 7 AND DATEDIFF(CURDATE(), DATE(fecha)) <= 10 AND flujo = 7 THEN ppo.costo_total  
                                WHEN DATEDIFF(CURDATE(), DATE(fecha)) > 7 AND DATEDIFF(CURDATE(), DATE(fecha)) <= 10 AND flujo = 8 THEN ppo.costo_total  
                                WHEN DATEDIFF(CURDATE(), DATE(fecha)) > 7 AND DATEDIFF(CURDATE(), DATE(fecha)) <= 10 AND flujo = 9 THEN ppo.costo_total  
                                WHEN DATEDIFF(CURDATE(), DATE(fecha)) > 7 AND DATEDIFF(CURDATE(), DATE(fecha)) <= 10 AND flujo = 10 THEN ppo.costo_total  
                                ELSE 0 END) AS total_7_10, 
                        SUM(CASE WHEN DATEDIFF(CURDATE(), DATE(fecha)) > 10 AND DATEDIFF(CURDATE(), DATE(fecha)) <= 30 AND flujo = 1 THEN ppo.costo_total  
                                WHEN DATEDIFF(CURDATE(), DATE(fecha)) > 10 AND DATEDIFF(CURDATE(), DATE(fecha)) <= 30 AND flujo = 2 THEN ppo.costo_total  
                                WHEN DATEDIFF(CURDATE(), DATE(fecha)) > 10 AND DATEDIFF(CURDATE(), DATE(fecha)) <= 30 AND flujo = 3 THEN ppo.costo_total  
                                WHEN DATEDIFF(CURDATE(), DATE(fecha)) > 10 AND DATEDIFF(CURDATE(), DATE(fecha)) <= 30 AND flujo = 4 THEN ppo.costo_total  
                                WHEN DATEDIFF(CURDATE(), DATE(fecha)) > 10 AND DATEDIFF(CURDATE(), DATE(fecha)) <= 30 AND flujo = 5 THEN ppo.costo_total  
                                WHEN DATEDIFF(CURDATE(), DATE(fecha)) > 10 AND DATEDIFF(CURDATE(), DATE(fecha)) <= 30 AND flujo = 6 THEN ppo.costo_total  
                                WHEN DATEDIFF(CURDATE(), DATE(fecha)) > 10 AND DATEDIFF(CURDATE(), DATE(fecha)) <= 30 AND flujo = 7 THEN ppo.costo_total  
                                WHEN DATEDIFF(CURDATE(), DATE(fecha)) > 10 AND DATEDIFF(CURDATE(), DATE(fecha)) <= 30 AND flujo = 8 THEN ppo.costo_total 
                                WHEN DATEDIFF(CURDATE(), DATE(fecha)) > 10 AND DATEDIFF(CURDATE(), DATE(fecha)) <= 30 AND flujo = 9 THEN ppo.costo_total 
                                WHEN DATEDIFF(CURDATE(), DATE(fecha)) > 10 AND DATEDIFF(CURDATE(), DATE(fecha)) <= 30 AND flujo = 10 THEN ppo.costo_total  
                                ELSE 0 END) AS total_10_30,
                        SUM(CASE WHEN (DATEDIFF(CURDATE(), DATE(fecha)) > 30 OR fecha IS NULL) AND flujo = 1 THEN ppo.costo_total 
                                WHEN (DATEDIFF(CURDATE(), DATE(fecha)) > 30 OR fecha IS NULL) AND flujo = 2 THEN ppo.costo_total 
                                WHEN (DATEDIFF(CURDATE(), DATE(fecha)) > 30 OR fecha IS NULL) AND flujo = 3 THEN ppo.costo_total  
                                WHEN (DATEDIFF(CURDATE(), DATE(fecha)) > 30 OR fecha IS NULL) AND flujo = 4 THEN ppo.costo_total  
                                WHEN (DATEDIFF(CURDATE(), DATE(fecha)) > 30 OR fecha IS NULL) AND flujo = 5 THEN ppo.costo_total 
                                WHEN (DATEDIFF(CURDATE(), DATE(fecha)) > 30 OR fecha IS NULL) AND flujo = 6 THEN ppo.costo_total 
                                WHEN (DATEDIFF(CURDATE(), DATE(fecha)) > 30 OR fecha IS NULL) AND flujo = 7 THEN ppo.costo_total 
                                WHEN (DATEDIFF(CURDATE(), DATE(fecha)) > 30 OR fecha IS NULL) AND flujo = 8 THEN ppo.costo_total 
                                WHEN (DATEDIFF(CURDATE(), DATE(fecha)) > 30 OR fecha IS NULL) AND flujo = 9 THEN ppo.costo_total  
                                WHEN (DATEDIFF(CURDATE(), DATE(fecha)) > 30 OR fecha IS NULL) AND flujo = 10 THEN ppo.costo_total  
                            ELSE 0 END) AS total_30_mas      
                FROM (  SELECT 1 AS idFlujo, 'PDTE DJ' AS situacion UNION ALL
                        SELECT 2,'PDTE VALIDACION DJ' UNION ALL
                        SELECT 3,'REGISTRO EN SIROPE' UNION ALL
                        SELECT 4,'PDTE GESTION VR' UNION ALL
                        SELECT 5,'PDTE LIQUIDACION MO' UNION ALL
                        SELECT 6, 'PDTE REGISTRO EXPEDIENTE' UNION ALL
                        SELECT 7,'PDTE VALIDACION EXPEDIENTE' UNION ALL
                        SELECT 8,'BANDEJA ALARMA' UNION ALL
                        SELECT 9,'VENTANILLA UNICA' UNION ALL
                        SELECT 10,'CERTIFICACION')t LEFT JOIN (reporte_resumen_po r, 
                                                               planobra_po ppo, 
                                                               planobra po, 
                                                               central ce) ON (r.flujo = t.idFlujo 
                                                                            AND ppo.codigo_po = r.ptr
                                                                            AND po.itemplan   = r.itemplan
                                                                            AND r.itemplan    = ppo.itemplan
                                                                            AND ce.idCentral  = po.idCentral
                                                                            AND ppo.id_eecc_reg = COALESCE(?, ppo.id_eecc_reg)
                                                                            AND ce.idJefatura   = COALESCE(?, ce.idJefatura))
                GROUP BY t.idFlujo
                ORDER BY t.idFlujo ASC";

        $result = $this->db->query($sql, array($idEmpresaColab, $idJefatura));
        //_log($this->db->last_query());
        return $result->result_array();
    }

    function getDetallePO($flujo, $interval, $idEmpresaColab, $idJefatura) {
        $sql = "SELECT r.itemplan,
                       r.ptr,
                       r.fecha,
                       (SELECT empresaColabDesc 
                          FROM empresacolab 
                         WHERE idEmpresaColab = ppo.id_eecc_reg) empresaColabDesc,
                       e.estacionDesc,
                       ce.jefatura
                  FROM reporte_resumen_po r,
                       planobra_po ppo,
                       estacion e,
                       central ce,
                       planobra po
                 WHERE r.flujo = ?
                   AND po.idCentral = ce.idCentral
                   AND po.itemplan  = ppo.itemplan
                   AND r.itemplan    = ppo.itemplan
                   AND ppo.codigo_po = r.ptr
                   AND e.idEstacion  = ppo.idEstacion
                   AND ppo.id_eecc_reg = COALESCE(?, ppo.id_eecc_reg)
                   AND ce.idJefatura   = COALESCE(?, idJefatura)
                   AND CASE WHEN ? = 'dia_0_7'    THEN DATEDIFF(CURDATE(), DATE(fecha)) <= 7 
                            WHEN ? = 'dia_7_10' THEN DATEDIFF(CURDATE(), DATE(fecha)) > 7 AND DATEDIFF(CURDATE(), DATE(fecha)) <= 10
                            WHEN ? = 'dia_10_30'  THEN DATEDIFF(CURDATE(), DATE(fecha)) > 10 AND DATEDIFF(CURDATE(), DATE(fecha)) <= 30
                            WHEN ? = 'dia_30_mas' THEN DATEDIFF(CURDATE(), DATE(fecha)) > 30 OR fecha IS NULL END";
        $result = $this->db->query($sql, array($flujo, $idEmpresaColab, $idJefatura, $interval, $interval, $interval, $interval));
        return $result->result_array();
    }
}
