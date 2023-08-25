<?php
class M_analisis_economico extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
    }

    function getDataTablaAnalisis($itemplan) {
        $sql = "SELECT DISTINCT 
                      ppo.codigo_po,
                      e.estacionDesc,
                      a.tipoArea,
                      poe.estado,
                      ppo.pep2,
                      ppo.grafo,
                      CASE WHEN flg_tipo_area = 1 THEN ppo.costo_total 
                           ELSE 0 END AS total_mat,
                      CASE WHEN flg_tipo_area = 2 THEN ppo.costo_total 
                           ELSE 0 END AS total_mo
                 FROM planobra_po ppo,
                       estacion e,
                       po_estado poe,
                       detalleplan dp,
                       subproyectoestacion sp,
                       estacionarea ea,
                	   area a
                WHERE e.idEstacion      = ppo.idEstacion
                  AND poe.idPoEstado    = ppo.estado_po
                  AND dp.poCod          = ppo.codigo_po
                  AND dp.itemplan       = ppo.itemplan
                  AND sp.idEstacionarea = ea.idEstacionArea
                  AND a.idArea          = ea.idArea
                  AND ppo.estado_po    <> 8
                  AND sp.idSubProyectoEstacion = dp.idSubProyectoEstacion
                  AND ppo.itemplan = COALESCE(?, ppo.itemplan)
                  UNION ALL
                  SELECT we.ptr,
                        e.estacionDesc,
                        we.desc_area,
                        ep.estadoPoDesc,
                        we.pep,
                        we.grafo,
                        valoriz_material AS total_mat,
                        valoriz_m_o AS total_mo 
                    FROM web_unificada we,
                        detalleplan dp,
                        estadoptr ep,
                        estacion e,
                        estacionarea ea,
                        subproyectoestacion se
                    WHERE we.ptr                 = dp.poCod
                    AND we.idEstadoPtr           = ep.idEstadoPo
                    AND e.idEstacion             = ea.idEstacion 
                    AND ea.idEstacionArea        = se.idEstacionArea
                    AND dp.idSubProyectoEstacion = se.idSubProyectoEstacion
                    AND ep.idEstadoPo           <> 6
                    AND dp.itemPlan             = COALESCE(?, dp.itemPlan)";
        $result = $this->db->query($sql, array($itemplan, $itemplan));
        return $result->result();
    }
    
    function getTotalAnalisis($itemplan) {
        $sql = "SELECT FORMAT(SUM(t.costo_total),2) AS totalFinal 
                  FROM
                        (
                            SELECT ppo.costo_total
                              FROM planobra_po ppo
                             WHERE ppo.itemplan = COALESCE(?, ppo.itemplan)
                               AND ppo.estado_po <> 8
                             UNION ALL
							SELECT (valoriz_material+valoriz_m_o)
                               FROM web_unificada we,
                                    detalleplan dp,
                                    estadoptr ep,
                                    subproyectoestacion se
                              WHERE we.ptr = dp.poCod
                                AND we.idEstadoPtr = ep.idEstadoPo
                                AND dp.idSubProyectoEstacion = se.idSubProyectoEstacion
                                AND ep.idEstadoPo  <> 6
                                AND dp.itemPlan    = COALESCE(?, dp.itemPlan)       
                        )t";
                $result = $this->db->query($sql, array($itemplan, $itemplan));
        return $result->row_array()['totalFinal'];
    }
	
	/*
	function getDataAnalisisSisego($itemplan) {
		$sql = "				
            SELECT t.*,
				   (CASE WHEN t.has_paralizado = 1 THEN 'SI'
					     WHEN t.has_paralizado IS NULL THEN 'NO' END) paralizado,
				  (SELECT mo.motivoDesc 
                     FROM motivo mo
				    WHERE mo.idMotivo = t.motivo_paralizado
					  AND t.has_paralizado = 1)motivoDesc,
					CASE WHEN costo_unitario_mat_mo > total_po THEN 'CON PRESUPUESTO'
                             WHEN monto_disponible > total_po THEN 'CON PRESUPUESTO'
                             ELSE 'SIN PRESUPUESTO' END AS situacion
              FROM (   
                SELECT 'MANO DE OBRA' AS tipo_area,
                       po.itemplan, 
					   po.indicador, 
					   substring_index(pep2,'-',5)pep1, 
					   pep2, 
					   grafo, 
					   po.costo_unitario_mo as costo_unitario_mat_mo,
					   ROUND((po.costo_unitario_mat + po.costo_unitario_mo),2) total,
					   solicitud_oc,
					   s_dos.monto_disponible,
                       (SELECT CASE WHEN costo_total IS NULL THEN 0 
                                    ELSE ROUND(SUM(costo_total), 2) END 
						  FROM planobra_po ppo WHERE ppo.flg_tipo_area = '2' 
						    AND ppo.itemplan = po.itemplan) total_po,
						(SELECT CASE WHEN estado_po IN (1,2) THEN 'VR EN PROCESO'
                                    ELSE 'VR REALIZADO' END 
						  FROM planobra_po ppo 
						 WHERE ppo.flg_tipo_area = '1' 
                           AND ppo.itemplan = po.itemplan) AS estado_gestion,
					    po.has_paralizado,
						po.motivo_paralizado
				  FROM sisego_pep2_grafo s, 
					   planobra po,
					   sap_detalle_2 s_dos
				 WHERE po.indicador = s.sisego
				   AND itemplan = ?
				   AND substring_index(pep2,'-',5) = s_dos.pep1
                   UNION ALL
				   SELECT 'MATERIAL' AS tipo_area,
                           po.itemplan, 
						   po.indicador, 
						   substring_index(pep2,'-',5)pep1, 
						   pep2, 
						   grafo, 
						   po.costo_unitario_mat,
						   ROUND((po.costo_unitario_mat + po.costo_unitario_mo),2) total,
						   solicitud_oc,
						   s_dos.monto_disponible,
                           (SELECT CASE WHEN costo_total IS NULL THEN 0 
                                    ELSE ROUND(SUM(costo_total), 2) END 
							  FROM planobra_po ppo WHERE ppo.flg_tipo_area = '1' 
							   AND ppo.itemplan = po.itemplan) total_po,
						   (CASE WHEN solicitud_oc IS NULL THEN 'SIN OC'
                                 ELSE 'CON OC' END),
                            po.has_paralizado,
							po.motivo_paralizado
					  FROM sisego_pep2_grafo s, 
						   planobra po,
						   sap_detalle_2 s_dos
					 WHERE po.indicador = s.sisego
					   AND itemplan = ?
					   AND substring_index(pep2,'-',5) = s_dos.pep1
                       )t";
		$result = $this->db->query($sql, array($itemplan, $itemplan));
		return $result->result();
	}
    */
	
	function getDataAnalisisSisego($itemplan) {
		$sql = "
			SELECT
	'MANO DE OBRA' AS tipo_area,
	po.itemplan,
	po.indicador,
	sp2.pep2,
	sp2.grafo,
	sd2.monto_disponible,
	FORMAT(
		(
			po.costo_unitario_mat + po.costo_unitario_mo
		),
		2
	) AS costo_total_obra,
	/*
				(CASE WHEN sd2.monto_disponible IS NULL THEN 'SIN PRESUPUESTO' 
					  WHEN sd2.monto_disponible IS NOT NULL THEN (
							(CASE WHEN po.solicitud_oc IS NOT NULL THEN 'SI' 
								  WHEN po.solicitud_oc IS NULL THEN (
									(CASE WHEN sd2.monto_disponible >= (po.costo_unitario_mat + po.costo_unitario_mo) THEN 'SI'
										ELSE 'NO' END)
								  )
								  END )
						) END) as estado_presu*/
	(
		CASE
		WHEN po.idSubProyecto IN (13, 14, 15) THEN
			(
				CASE
				WHEN po.solicitud_oc IS NOT NULL THEN
					'CON PRESUP'
				WHEN po.motivo_paralizado = 11 THEN
					'SIN PRESUP'
				WHEN po.motivo_paralizado = 67 THEN
					'SIN COTIZACION'
				ELSE
					'SIN PRESUP'
				END
			)
		END
	) AS estado_presu,
	FORMAT((po.costo_unitario_mo), 2) AS costo_unitario,
	FORMAT(SUM(ppo.costo_total), 2) AS costo_total_po,
	(
		CASE
		WHEN po.has_paralizado = 1 THEN
			'SI'
		ELSE
			'NO'
		END
	) AS paralizado,
	m.motivoDesc,
	(
		CASE
		WHEN soc.estado = 2 THEN
			'CON OC'
		ELSE
			'SIN OC'
		END
	) AS estado_gestion
FROM
	planobra po
LEFT JOIN sisego_pep2_grafo sp2 ON sp2.sisego = po.indicador
AND sp2.estado IN (0, 1, 2)
LEFT JOIN sap_detalle_2 sd2 ON SUBSTRING(sp2.pep2, 1, 20) = sd2.pep1
LEFT JOIN planobra_po ppo ON po.itemplan = ppo.itemplan
AND ppo.flg_tipo_area = 2
AND ppo.estado_po NOT IN (7, 8)
LEFT JOIN motivo m ON po.motivo_paralizado = m.idMotivo
LEFT JOIN solicitud_orden_compra soc ON po.solicitud_oc = soc.codigo_solicitud
WHERE
	po.itemplan = ?
UNION ALL
	SELECT
		'MATERIAL' AS tipo_area,
		po.itemplan,
		po.indicador,
		sp2.pep2,
		sp2.grafo,
		sd2.monto_disponible,
		FORMAT(
			(
				po.costo_unitario_mat + po.costo_unitario_mo
			),
			2
		) AS costo_total_obra,
		/*
				(CASE WHEN sd2.monto_disponible IS NULL THEN 'SIN PRESUPUESTO' 
					  WHEN sd2.monto_disponible IS NOT NULL THEN (
							(CASE WHEN po.solicitud_oc IS NOT NULL THEN 'SI' 
								  WHEN po.solicitud_oc IS NULL THEN (
									(CASE WHEN sd2.monto_disponible >= (po.costo_unitario_mat + po.costo_unitario_mo) THEN 'SI'
										ELSE 'NO' END)
								  )
								  END )
						) END) as estado_presu,*/
		(
			CASE
			WHEN po.idSubProyecto IN (13, 14, 15) THEN
				(
					CASE
					WHEN po.solicitud_oc IS NOT NULL THEN
						'CON PRESUP'
					WHEN po.motivo_paralizado = 11 THEN
						'SIN PRESUP'
					WHEN po.motivo_paralizado = 67 THEN
						'SIN COTIZACION'
					ELSE
						'SIN PRESUP'
					END
				)
			END
		) AS estado_presu,
		FORMAT((po.costo_unitario_mat), 2) AS costo_unitario,
		FORMAT(SUM(ppo.costo_total), 2) AS costo_total_po,
		(
			CASE
			WHEN po.has_paralizado = 1 THEN
				'SI'
			ELSE
				'NO'
			END
		) AS paralizado,
		m.motivoDesc,
		(
			CASE
			WHEN SUM(
				CASE
				WHEN ppo.estado_po IN (3, 4, 5, 6) THEN
					1
				ELSE
					0
				END
			) > 0 THEN
				'CON VR'
			ELSE
				'SIN VR'
			END
		) AS estado_gestion
	FROM
		planobra po
	LEFT JOIN sisego_pep2_grafo sp2 ON sp2.sisego = po.indicador
	AND sp2.estado IN (0, 1, 2)
	LEFT JOIN sap_detalle_2 sd2 ON SUBSTRING(sp2.pep2, 1, 20) = sd2.pep1
	LEFT JOIN planobra_po ppo ON po.itemplan = ppo.itemplan
	AND ppo.flg_tipo_area = 1
	AND ppo.estado_po NOT IN (7, 8)
	LEFT JOIN motivo m ON po.motivo_paralizado = m.idMotivo
	LEFT JOIN solicitud_orden_compra soc ON po.solicitud_oc = soc.codigo_solicitud
	WHERE
		po.itemplan = ?";
		$result = $this->db->query($sql, array($itemplan, $itemplan));
		return $result->result();
	}
	
	function getDataTablaItemCerrado($itemplan) {
        $sql = "SELECT t.tipoArea,
					   SUM(t.total_mat) total_mat, 
					   SUM(t.total_mo) total_mo
		          FROM (
						SELECT DISTINCT 
							  ppo.codigo_po,
							  e.estacionDesc,
							  a.tipoArea,
							  poe.estado,
							  ppo.pep2,
							  ppo.grafo,
							  CASE WHEN flg_tipo_area = 1 THEN ppo.costo_total 
								   ELSE 0 END AS total_mat,
							  CASE WHEN flg_tipo_area = 2 THEN ppo.costo_total 
								   ELSE 0 END AS total_mo
						 FROM planobra_po ppo,
							   estacion e,
							   po_estado poe,
							   detalleplan dp,
							   subproyectoestacion sp,
							   estacionarea ea,
							   area a
						WHERE e.idEstacion      = ppo.idEstacion
						  AND poe.idPoEstado    = ppo.estado_po
						  AND dp.poCod          = ppo.codigo_po
						  AND dp.itemplan       = ppo.itemplan
						  AND sp.idEstacionarea = ea.idEstacionArea
						  AND a.idArea          = ea.idArea
						  AND ppo.estado_po    <> 8
						  AND sp.idSubProyectoEstacion = dp.idSubProyectoEstacion
						  AND ppo.itemplan = COALESCE(?, ppo.itemplan)
					UNION ALL
						  SELECT we.ptr,
								e.estacionDesc,
								we.desc_area,
								ep.estadoPoDesc,
								we.pep,
								we.grafo,
								valoriz_material AS total_mat,
								valoriz_m_o AS total_mo 
							FROM web_unificada we,
								detalleplan dp,
								estadoptr ep,
								estacion e,
								estacionarea ea,
								subproyectoestacion se
							WHERE we.ptr                 = dp.poCod
							AND we.idEstadoPtr           = ep.idEstadoPo
							AND e.idEstacion             = ea.idEstacion 
							AND ea.idEstacionArea        = se.idEstacionArea
							AND dp.idSubProyectoEstacion = se.idSubProyectoEstacion
							AND ep.idEstadoPo           <> 6
							AND dp.itemPlan             = COALESCE(?, dp.itemPlan)
					)t";
        $result = $this->db->query($sql, array($itemplan, $itemplan));
        return $result->result();
    }
}
