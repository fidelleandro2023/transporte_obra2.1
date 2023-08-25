<?php
class M_liquidacion extends CI_Model
{
    //http://www.codeigniter.com/userguide3/database/results.html
    public function __construct()
    {
        parent::__construct();

    }

    public function getPtrToLiquidacion($subPry, $eecc, $zonal, $itemPlan, $mesEjec, $area, $estado, $from, $ano, $idFase = '', $flgBandeja = null){
      $ex_sub = null;
        if ($subPry != '') {
            $ex_sub = explode(',', $subPry);
        }
        $Query = "SELECT tab.*, 
                         GROUP_CONCAT(ield.flg_validado SEPARATOR '*') AS info
                    FROM (
           SELECT f.faseDesc AS fase_desc,wud.*,SEC_TO_TIME(TIMESTAMPDIFF(SECOND, STR_TO_DATE(wud.fecSol, '%d/%m/%Y %H:%i:%s'), NOW())) as horas, po.idEstadoPlan,   CASE
					WHEN substr(po.fechaPrevEjec,6,2) = '01'  THEN  'ENE'
					WHEN substr(po.fechaPrevEjec,6,2) = '02'  THEN  'FEB'
					WHEN substr(po.fechaPrevEjec,6,2) = '03'  THEN  'MAR'
					WHEN substr(po.fechaPrevEjec,6,2) = '04'  THEN  'ABR'
					WHEN substr(po.fechaPrevEjec,6,2) = '05'  THEN  'MAY'
					WHEN substr(po.fechaPrevEjec,6,2) = '06'  THEN  'JUN'
					WHEN substr(po.fechaPrevEjec,6,2) = '07'  THEN  'JUL'
					WHEN substr(po.fechaPrevEjec,6,2) = '08'  THEN  'AGO'
					WHEN substr(po.fechaPrevEjec,6,2) = '09'  THEN  'SEP'
					WHEN substr(po.fechaPrevEjec,6,2) = '10'  THEN  'OCT'
					WHEN substr(po.fechaPrevEjec,6,2) = '11'  THEN  'NOV'
					WHEN substr(po.fechaPrevEjec,6,2) = '12'  THEN  'DIC'
				ELSE NULL
				END as mesEjec,
                    substr(po.fechaPrevEjec,1,4) as ano_ejec, po.hasAdelanto,
                    (CASE WHEN sp.idProyecto = 4 AND (po.idEstadoPlan = 3 OR po.idEstadoPlan = 9) AND desc_area = 'MO' AND estacion_desc != 'DISENO'  THEN 1 ELSE 0 END) as esta_validada2,
                    ec.idEmpresaColab, 
                    '1' as origen,
                    NULL AS idArea,
                    po.indicador AS indicador_po,
                    po.per,
                    po.paquetizado_fg,
                    e.idEstacion,
					po.has_paralizado,
					ep.estadoPlanDesc
                    FROM web_unificada_det wud 
					LEFT JOIN planobra po ON wud.itemplan = po.itemplan 
					LEFT JOIN estadoplan ep ON po.idEstadoPlan = ep.idEstadoPlan 
					LEFT JOIN subproyecto sp ON po.idSubProyecto = sp.idSubProyecto
					LEFT JOIN estacion e on e.estacionDesc =  estacion_desc
					LEFT JOIN fase f ON f.idFase = po.idFase
					LEFT JOIN central c ON c.idCentral = po.idCentral
					LEFT JOIN empresacolab ec ON (CASE WHEN sp.idTipoSubProyecto = 2 THEN c.idEmpresaColabCV = ec.idEmpresaColab ELSE c.idEmpresaColab = ec.idEmpresaColab END)
                    WHERE ( wud.estado_asig_grafo = '" . ESTADO_SIN_GRAFO . "' OR wud.estado_asig_grafo = '" . ESTADO_CON_GRAFO_TEMPORAL . "' )
					AND po.idEstadoPlan in (3,20)
					AND (CASE WHEN po.has_paralizado is not null then po.motivo_paralizado in (11,42) else true end )
					AND po.solicitud_oc is not null
					AND po.estado_sol_oc = 'ATENDIDO' ";
        if ($subPry != '') {
            
                       
                $Query .= "  AND wud.subProy IN  ?";
          
            /*
            if ($from == FROM_BANDEJA_PRE_APROBACION_DISENO) {
                $Query .= " AND wud.subProy ='" . str_replace(',', '|', $subPry) . "'";

            } else {
                $Query .= " AND wud.subProy REGEXP '" . str_replace(',', '|', $subPry) . "'";
            }
*/
            /*
        $Query .= " AND wud.subProy REGEXP '".str_replace(',','|',$subPry)."'";*/
        }
        if ($eecc != '') {
            $Query .= " AND wud.eecc LIKE '%" . $eecc . "%' ";
        }
        if ($zonal != '') {
            $Query .= " AND wud.zonal REGEXP '" . str_replace(',', '|', $zonal) . "'";
        }
        if ($itemPlan == 'SI') {
            $Query .= " AND wud.itemPlan is not null";
        }
        if ($itemPlan == 'NO') {
            $Query .= " AND wud.itemPlan is null";
        }
        if ($mesEjec != '') {
            //$Query .=  " AND wud.fec_prevista_ejec ='".$mesEjec."'";
            $Query .= " and substr(po.fechaPrevEjec,6,2) = " . $mesEjec;
        }
        if ($ano != '') {
            $Query .= " and substr(po.fechaPrevEjec,1,4) = " . $ano;
        }
        if ($idFase != '') {
            $Query .= " and po.idFase = " . $idFase;
        }
        if ($area != '') {
            if (strlen($area) > 3) {
                $Query .= " AND wud.area_desc = '" . $area . "'";
            } else {
                $Query .= " AND wud.desc_area = '" . $area . "'";
            }
        }
        if ($from == FROM_BANDEJA_APROBACION) {
            if ($estado != '') {
                $count = 0;
                $array = explode(',', $estado);
                foreach ($array as $row) {
                    if ($count == 0) {
                        $Query .= " AND ( substring(wud.estado,1," . strlen($row) . ") =  '" . $row . "'";
                        $count++;
                    } else {
                        $Query .= " OR substring(wud.estado,1," . strlen($row) . ") =  '" . $row . "'";
                    }

                }
                $Query .= " )";
            } else {
                $Query .= " AND  substring(wud.estado,1,2) =  '" . ESTADO_APROB_01 . "' ";
            }
            $Query .= " AND CASE WHEN desc_area = 'MO' and estacion_desc != 'DISENO' THEN ((SELECT COUNT(1) as count FROM itemplan_expediente where estado = 'ACTIVO' AND estado_final = 'FINALIZADO' and itemplan = wud.itemPlan and idEstacion = e.idEstacion) > 0 OR sp.idProyecto = 4 )   ELSE TRUE END
AND CASE
	WHEN estacion_desc = 'DISENO'
	THEN SUBSTRING(wud.estado,1,2) ='01'
ELSE TRUE
END ";
        } else if ($from == FROM_BANDEJA_PRE_APROBACION) {
            if ($estado != '') {
                $count = 0;
                $array = explode(',', $estado);
                foreach ($array as $row) {
                    if ($count == 0) {
                        $Query .= " AND ( substring(wud.estado,1," . strlen($row) . ") =  '" . $row . "'";
                        $count++;
                    } else {
                        $Query .= " OR substring(wud.estado,1," . strlen($row) . ") =  '" . $row . "'";
                    }

                }
                $Query .= " )";
            } else {
                $Query .= " AND  substring(wud.estado,1,2) !=  '" . ESTADO_APROB_01 . "' ";

            }
            $Query .= " AND CASE WHEN wud.desc_area = 'MO' THEN sp.idProyecto = 4 ELSE TRUE END
                        AND estacion_desc != 'DISENO'
                        AND sp.idTipoPlanta = 1 ";
        } else if ($from == FROM_BANDEJA_PRE_APROBACION_DISENO) {
            if ($estado != '') {
                $count = 0;
                $array = explode(',', $estado);
                foreach ($array as $row) {
                    if ($count == 0) {
                        $Query .= " AND ( substring(wud.estado,1," . strlen($row) . ") =  '" . $row . "'";
                        $count++;
                    } else {
                        $Query .= " OR substring(wud.estado,1," . strlen($row) . ") =  '" . $row . "'";
                    }

                }
                $Query .= " )";
            } else {
                $Query .= " AND  substring(wud.estado,1,2) !=  '" . ESTADO_APROB_01 . "' AND  substring(wud.estado,1,3) !=  '" . ESTADO_PRE_APROB_EECC . "' AND  substring(wud.estado,1,3) !=  '" . ESTADO_PRE_APROB_002 . "'";

            }
            $Query .= " AND estacion_desc = 'DISENO'";
        }

        $Query .= " UNION ALL
						SELECT f.faseDesc AS fase_desc,
						ppo.codigo_po AS ptr,
						poe.estado,
						z.zonalDesc AS zonal,
						ec.empresaColabDesc AS eecc,
						sp.subProyectoDesc AS subProy,
						ppo.pep1 AS pep1,
						ppo.pep2 AS pep2,
						lppo.fecha_registro AS fecCrea,
						lppo.fecha_registro AS fecSol,
						'-' AS usua_aprob,
						'-' AS fec_aprob,
					    ppo.grafo AS grafo,
						'-' AS fecha_asig_grafo,
						'-' AS estado_asig_grafo,
						a.tipoArea AS desc_area,
						ppo.itemplan,
						'-' AS fec_prevista_ejec,
						e.estacionDesc AS estacion_desc,
						a.areaDesc AS area_desc,
						'-' AS fec_prev_ejec,
						'-' AS usua_asig_grafo,
						CASE WHEN ppo.flg_tipo_area = 1 THEN ppo.costo_total
						     ELSE '-' END AS valor_material,
						CASE WHEN ppo.flg_tipo_area = 2 THEN ppo.costo_total
						     ELSE '-' END AS valor_m_o,
						'-' AS indicador,
				    	ppo.grafo_from AS grafo_from,
						ppo.vale_reserva AS vale_reserva,
						'-' AS esta_validada,
						'-' AS has_pre_aprob,
						SEC_TO_TIME(TIMESTAMPDIFF(SECOND, lppo.fecha_registro, NOW())) as horas,
						po.idEstadoPlan,
						CASE
							WHEN substr(po.fechaPrevEjec,6,2) = '01'  THEN  'ENE'
							WHEN substr(po.fechaPrevEjec,6,2) = '02'  THEN  'FEB'
							WHEN substr(po.fechaPrevEjec,6,2) = '03'  THEN  'MAR'
							WHEN substr(po.fechaPrevEjec,6,2) = '04'  THEN  'ABR'
							WHEN substr(po.fechaPrevEjec,6,2) = '05'  THEN  'MAY'
							WHEN substr(po.fechaPrevEjec,6,2) = '06'  THEN  'JUN'
							WHEN substr(po.fechaPrevEjec,6,2) = '07'  THEN  'JUL'
							WHEN substr(po.fechaPrevEjec,6,2) = '08'  THEN  'AGO'
							WHEN substr(po.fechaPrevEjec,6,2) = '09'  THEN  'SEP'
							WHEN substr(po.fechaPrevEjec,6,2) = '10'  THEN  'OCT'
							WHEN substr(po.fechaPrevEjec,6,2) = '11'  THEN  'NOV'
							WHEN substr(po.fechaPrevEjec,6,2) = '12'  THEN  'DIC'
						ELSE NULL
						END as mesEjec,
						substr(po.fechaPrevEjec,1,4) as ano_ejec, po.hasAdelanto,
                        (CASE WHEN sp.idProyecto = 4 AND (po.idEstadoPlan = 3 OR po.idEstadoPlan = 9) AND a.tipoArea = 'MO' AND e.estacionDesc != 'DISEÃ‘O'  THEN 1 ELSE 0 END) as esta_validada2,
                        ec.idEmpresaColab, 
                        '2' as origen,
                        a.idArea,
                        po.indicador AS indicador_po,
                        po.per,
                        po.paquetizado_fg,
                        e.idEstacion,
						po.has_paralizado,
						ep.estadoPlanDesc
				FROM planobra_po ppo,
						planobra po,
						estadoplan ep,
						subproyecto sp,
						detalleplan dp,
						subproyectoestacion se,
						estacionarea ea,
						estacion e,
						area a,
						central c,
						zonal z,
						empresacolab ec,
						fase f,
						po_estado poe,
						log_planobra_po lppo
				WHERE ppo.itemplan = po.itemplan
					AND po.idEstadoPlan = ep.idEstadoPlan
					AND po.idSubProyecto = sp.idSubproyecto
					AND dp.idSubProyectoEstacion = se.idSubProyectoEstacion
					AND dp.itemplan = ppo.itemplan
					AND dp.poCod = ppo.codigo_po
					AND se.idEstacionArea = ea.idEstacionArea
					AND ea.idArea = a.idArea
					AND ppo.idEstacion = e.idEstacion
					AND po.idCentral = c.idCentral
					AND c.idZonal = z.idZonal
					AND	(CASE WHEN sp.idTipoSubProyecto = 2 THEN c.idEmpresaColabCV = ec.idEmpresaColab ELSE ppo.id_eecc_reg = ec.idEmpresaColab END)
					AND po.itemPlan = dp.itemPlan
					AND po.idFase = f.idFase
					AND ppo.estado_po = poe.idPoestado
					AND ppo.codigo_po = lppo.codigo_po
					AND ppo.itemplan = lppo.itemplan
					-- AND po.idEstadoPlan in (3,20)
					AND (CASE WHEN po.has_paralizado is not null then po.motivo_paralizado in (11,42) else true end )
					AND po.paquetizado_fg is null
					AND po.solicitud_oc is not null
					AND po.estado_sol_oc = 'ATENDIDO'";
				if($from    ==  FROM_BANDEJA_PRE_APROBACION){
                    $Query .= " AND ppo.flg_tipo_area = 1
                            AND ppo.estado_po = 1 
                            AND lppo.idPoestado = 1
                            AND sp.idTipoPlanta = 1 ";
                }else if($from    ==  FROM_BANDEJA_APROBACION){
                    $Query .= " AND (CASE WHEN ppo.flg_tipo_area = 2 THEN ppo.idEstacion <> 1 ELSE TRUE END)
                                AND ppo.estado_po = 2
                                AND lppo.idPoestado = 2
								AND (CASE WHEN po.paquetizado_fg = 2 then po.idEstadoPlan IN (20,3,9,21,4) else true end)";                
                }else if($from    ==  FROM_BANDEJA_PRE_APROBACION_DISENO){
                    $Query .= " AND ppo.flg_tipo_area = 2 
        			            AND ppo.estado_po = 1
                                AND ppo.idEstacion = 1
                                AND lppo.idPoestado = ppo.estado_po 
                                AND (SELECT 1 
                                          FROM planobra_po_detalle_partida pod,
                                               partidas pa
                                         WHERE pod.idPartida = pa.idActividad
                                           AND pod.codigo_po = ppo.codigo_po
                                           AND CASE WHEN a.idArea = 2 THEN pa.idTipoComplejidad = 2
                                                    ELSE true END
                                           limit 1) = 1";
                }  
		

        if ($area != '') {
           if($area ==  'MAT'){
                $Query .= " AND ppo.flg_tipo_area   = 1";
           }else if($area   ==  'MO'){
               $Query .= "  AND ppo.flg_tipo_area   = 2";
           }
        }
        /*
		if ($subPry != '') {
		        $Query .= "  AND sp.subProyectoDesc REGEXP '" . str_replace(',', '|', $subPry) . "'";
		}
		*/
        
        if ($subPry != '') {
            //$ex_sub = explode(',', $subPry);
            $Query .= "  AND sp.subProyectoDesc IN  ?";
        }
		if ($idFase != '') {
		    $Query .= " AND po.idFase = " . $idFase;
		}
		
		if ($eecc != '') {
		    $Query .= " AND ec.empresaColabDesc LIKE '%" . $eecc . "%' ";
		}
		
		if ($mesEjec != '') {
		    //$Query .=  " AND wud.fec_prevista_ejec ='".$mesEjec."'";
		    $Query .= " AND substr(po.fechaPrevEjec,6,2) = " . $mesEjec;
		}
		
		if ($ano != '') {
		    $Query .= " AND substr(po.fechaPrevEjec,1,4) = " . $ano;
		}
		
		/**nuevo para pqt central**/
		
		$Query .= " UNION ALL
						SELECT f.faseDesc AS fase_desc,
						ppo.codigo_po AS ptr,
						poe.estado,
						z.zonalDesc AS zonal,
						ec.empresaColabDesc AS eecc,
						sp.subProyectoDesc AS subProy,
						ppo.pep1 AS pep1,
						ppo.pep2 AS pep2,
						lppo.fecha_registro AS fecCrea,
						lppo.fecha_registro AS fecSol,
						'-' AS usua_aprob,
						'-' AS fec_aprob,
					    ppo.grafo AS grafo,
						'-' AS fecha_asig_grafo,
						'-' AS estado_asig_grafo,
						a.tipoArea AS desc_area,
						ppo.itemplan,
						'-' AS fec_prevista_ejec,
						e.estacionDesc AS estacion_desc,
						a.areaDesc AS area_desc,
						'-' AS fec_prev_ejec,
						'-' AS usua_asig_grafo,
						CASE WHEN ppo.flg_tipo_area = 1 THEN ppo.costo_total
						     ELSE '-' END AS valor_material,
						CASE WHEN ppo.flg_tipo_area = 2 THEN ppo.costo_total
						     ELSE '-' END AS valor_m_o,
						'-' AS indicador,
				    	ppo.grafo_from AS grafo_from,
						ppo.vale_reserva AS vale_reserva,
						'-' AS esta_validada,
						'-' AS has_pre_aprob,
						SEC_TO_TIME(TIMESTAMPDIFF(SECOND, lppo.fecha_registro, NOW())) as horas,
						po.idEstadoPlan,
						CASE
							WHEN substr(po.fechaPrevEjec,6,2) = '01'  THEN  'ENE'
							WHEN substr(po.fechaPrevEjec,6,2) = '02'  THEN  'FEB'
							WHEN substr(po.fechaPrevEjec,6,2) = '03'  THEN  'MAR'
							WHEN substr(po.fechaPrevEjec,6,2) = '04'  THEN  'ABR'
							WHEN substr(po.fechaPrevEjec,6,2) = '05'  THEN  'MAY'
							WHEN substr(po.fechaPrevEjec,6,2) = '06'  THEN  'JUN'
							WHEN substr(po.fechaPrevEjec,6,2) = '07'  THEN  'JUL'
							WHEN substr(po.fechaPrevEjec,6,2) = '08'  THEN  'AGO'
							WHEN substr(po.fechaPrevEjec,6,2) = '09'  THEN  'SEP'
							WHEN substr(po.fechaPrevEjec,6,2) = '10'  THEN  'OCT'
							WHEN substr(po.fechaPrevEjec,6,2) = '11'  THEN  'NOV'
							WHEN substr(po.fechaPrevEjec,6,2) = '12'  THEN  'DIC'
						ELSE NULL
						END as mesEjec,
						substr(po.fechaPrevEjec,1,4) as ano_ejec, po.hasAdelanto,
                        (CASE WHEN sp.idProyecto = 4 AND (po.idEstadoPlan = 3 OR po.idEstadoPlan = 9) AND a.tipoArea = 'MO' AND e.estacionDesc != 'DISEÃ‘O'  THEN 1 ELSE 0 END) as esta_validada2,
                        ec.idEmpresaColab, 
                        '2' as origen,
                        a.idArea,
                        po.indicador AS indicador_po,
                        po.per,
                        po.paquetizado_fg,
                        e.idEstacion,
						po.has_paralizado,
						ep.estadoPlanDesc
				FROM planobra_po ppo,
						planobra po,
						estadoplan ep,
						subproyecto sp,
						detalleplan dp,
						subproyectoestacion se,
						estacionarea ea,
						estacion e,
						area a,
						pqt_central c,
						zonal z,
						empresacolab ec,
						fase f,
						po_estado poe,
						log_planobra_po lppo
				WHERE ppo.itemplan = po.itemplan
					AND po.idSubProyecto 	= sp.idSubproyecto
					AND po.idEstadoPlan 	= ep.idEstadoPlan
					AND dp.idSubProyectoEstacion = se.idSubProyectoEstacion
					AND dp.itemplan = ppo.itemplan
					AND dp.poCod = ppo.codigo_po
					AND se.idEstacionArea = ea.idEstacionArea
					AND ea.idArea = a.idArea
					AND ppo.idEstacion = e.idEstacion
					AND po.idCentralPqt = c.idCentral
					AND c.idZonal = z.idZonal
					AND	(CASE WHEN sp.idTipoSubProyecto = 2 THEN c.idEmpresaColabCV = ec.idEmpresaColab ELSE ppo.id_eecc_reg = ec.idEmpresaColab END)
					AND po.itemPlan = dp.itemPlan
					AND po.idFase = f.idFase
					AND ppo.estado_po = poe.idPoestado
					AND ppo.codigo_po = lppo.codigo_po
					AND ppo.itemplan = lppo.itemplan
					-- AND po.idEstadoPlan in (4,3,20)
					AND (CASE WHEN po.has_paralizado is not null then po.motivo_paralizado in (11,42) else true end )
					AND (po.paquetizado_fg = 2 OR po.paquetizado_fg = 1)
					AND po.solicitud_oc is not null
					AND po.estado_sol_oc = 'ATENDIDO' ";
				if($from    ==  FROM_BANDEJA_PRE_APROBACION){
                    $Query .= " AND ppo.flg_tipo_area = 1
                            AND ppo.estado_po = 1 
                            AND lppo.idPoestado = 1
                            AND sp.idTipoPlanta = 1";
                }else if($from    ==  FROM_BANDEJA_APROBACION){
                    $Query .= " AND (CASE WHEN ppo.flg_tipo_area = 2 THEN ppo.idEstacion <> 1 ELSE TRUE END)
                                AND ppo.estado_po = 2
                                AND lppo.idPoestado = 2
								/*AND po.paquetizado_fg is null*/";                
                }else if($from    ==  FROM_BANDEJA_PRE_APROBACION_DISENO){
                    $Query .= " AND ppo.flg_tipo_area = 2 
        			            AND ppo.estado_po = 1
                                AND ppo.idEstacion = 1
                                AND lppo.idPoestado = ppo.estado_po 
                                AND (SELECT 1 
                                          FROM planobra_po_detalle_partida pod,
                                               partidas pa
                                         WHERE pod.idPartida = pa.idActividad
                                           AND pod.codigo_po = ppo.codigo_po
                                           AND CASE WHEN a.idArea = 2 THEN pa.idTipoComplejidad = 2
                                                    ELSE true END
                                           limit 1) = 1";
                }  
		

        if ($area != '') {
           if($area ==  'MAT'){
                $Query .= " AND ppo.flg_tipo_area   = 1";
           }else if($area   ==  'MO'){
               $Query .= "  AND ppo.flg_tipo_area   = 2";
           }
        }
        /*
		if ($subPry != '') {
		        $Query .= "  AND sp.subProyectoDesc REGEXP '" . str_replace(',', '|', $subPry) . "'";
		}
		*/
        
        if ($subPry != '') {
            //$ex_sub = explode(',', $subPry);
            $Query .= "  AND sp.subProyectoDesc IN  ?";
        }
		if ($idFase != '') {
		    $Query .= " AND po.idFase = " . $idFase;
		}
		
		if ($eecc != '') {
		    $Query .= " AND ec.empresaColabDesc LIKE '%" . $eecc . "%' ";
		}
		
		if ($mesEjec != '') {
		    //$Query .=  " AND wud.fec_prevista_ejec ='".$mesEjec."'";
		    $Query .= " AND substr(po.fechaPrevEjec,6,2) = " . $mesEjec;
		}
		
		if ($ano != '') {
		    $Query .= " AND substr(po.fechaPrevEjec,1,4) = " . $ano;
        }
        $Query .= "                 ) tab
                    LEFT JOIN itemplan_estacion_licencia_det ield ON (ield.itemPlan   = tab.itemPlan
                                                                  AND ield.idEstacion = tab.idEstacion
                                                                  AND ield.idEntidad  <> 0)
                    GROUP BY tab.fase_desc, tab.ptr, tab.estado, tab.zonal, tab.eecc, tab.subProy, tab.pep1, tab.pep2, tab.fecCrea, tab.fecSol, tab.usua_aprob, tab.fec_aprob, tab.grafo, tab.fecha_asig_grafo, tab.estado_asig_grafo, tab.desc_area, tab.itemPlan, tab.fec_prevista_ejec, tab.estacion_desc, tab.area_desc, tab.fec_prev_ejec, tab.usua_asig_grafo, tab.valor_material, tab.valor_m_o, tab.indicador, tab.grafo_from, tab.vale_reserva, tab.esta_validada, tab.has_pre_aprob, tab.horas, tab.idEstadoPlan, tab.mesEjec, tab.ano_ejec, tab.hasAdelanto, tab.esta_validada2, tab.idEmpresaColab, tab.origen, tab.idArea, tab.indicador_po, tab.per, tab.paquetizado_fg, tab.idEstacion 
                    ORDER BY tab.itemPlan, tab.ptr";
        $result = $this->db->query($Query, array($ex_sub, $ex_sub));
        return $result;
    }

    public function updateDetalleProducto($idPtr, $grafo, $from, $vale_re, $area_desc, $itemP)
    {
        $dataSalida['error'] = EXIT_ERROR;
        $dataSalida['msj'] = null;
        try {
            $dataFrom = explode("-", $from);
            $this->db->trans_begin();

            $data = array(
                "vale_reserva" => $vale_re,
                "estado_asig_grafo" => ESTADO_CON_GRAFO_ULTILIZADO,
                "fecha_asig_grafo" => date("d/m/Y H:i:s"),
                "usua_asig_grafo" => $this->session->userdata('userSession'),
            );
            $this->db->where('ptr', $idPtr);
            $this->db->update('web_unificada_det', $data);
            if ($this->db->affected_rows() == 0) {
                throw new Exception('Hubo un error al actualizar en web_unificada_det');
            }

            if ($dataFrom[0] == DATA_FROM_ITEMPLAN_PEP2_GRAFO) {
                $data = array(
                    "estado" => ESTADO_CON_GRAFO_ULTILIZADO,
                );
                $this->db->where('id', intval($dataFrom[1]));
                $this->db->update('itemplan_pep2_grafo', $data);
                /* if($this->db->affected_rows() != 1) {
            //throw new Exception($this->db->);
            throw new Exception('Hubo un error al actualizar en pep2_grafo itemplan');
            }*/
            } else if ($dataFrom[0] == DATA_FROM_SISEGO_PEP2_GRAFO) {
                $data = array(
                    "estado" => ESTADO_CON_GRAFO_ULTILIZADO,
                );
                $this->db->where('id', intval($dataFrom[1]));
                $this->db->update('sisego_pep2_grafo', $data);
                /*if($this->db->affected_rows() != 1) {
            //throw new Exception($this->db->);
            throw new Exception('Hubo un error al actualizar en pep2_grafo sisego');
            }*/
            
            } else if ($dataFrom[0] == DATA_FROM_PEP2_GRAFO) {
                $data = array(
                    "estado" => ESTADO_CON_GRAFO_ULTILIZADO,
                );
                $this->db->where('id', intval($dataFrom[1]));
                $this->db->update('pep2_grafo', $data);
                if ($this->db->affected_rows() != 1) {
                    //throw new Exception($this->db->);
                    throw new Exception('Hubo un error al actualizar en pep2_grafo');
                }
            }

            $campo_ptr = '';
            $campo_des = '';
            switch ($area_desc) {
                case 'MAT_COAX';
                    $campo_ptr = 'mat_coax_ptr';
                    $campo_des = 'mat_coax_est';
                    break;
                case 'MAT_COAX_OC';
                    $campo_ptr = 'mat_coax_oc_ptr';
                    $campo_des = 'mat_coax_oc_est';
                    break;
                case 'MAT_FUENTE';
                    $campo_ptr = 'mat_fuente_ptr';
                    $campo_des = 'mat_fuente_est';
                    break;
                case 'MAT_FO';
                    $campo_ptr = 'mat_fo_ptr';
                    $campo_des = 'mat_fo_est';
                    break;
                case 'MAT_FO_OC';
                    $campo_ptr = 'mat_fo_oc_ptr';
                    $campo_des = 'mat_fo_oc_est';
                    break;
                case 'MAT_ENER';
                    $campo_ptr = 'mat_ener_ptr';
                    $campo_des = 'mat_ener_est';
                    break;
            }

            if ($campo_des != '') {
                $data = array(
                    $campo_ptr => $idPtr,
                    $campo_des => '02 - VALORIZADA CON VALE DE RESERVA',

                );
                $this->db->where('itemPlan', $itemP);
                $this->db->update('web_unificada_fa', $data);
                if ($this->db->trans_status() === false) {
                    throw new Exception('Hubo un error al actualizar en web_unificada_fa');
                }
            }
            $data = array(
                'idEstadoPtr' => '2',
                'est_innova' => '02 - VALORIZADA CON VALE DE RESERVA',
                'rangoPtr' => '2',
            );
            $this->db->where('ptr', $idPtr);
            $this->db->update('web_unificada', $data);
            if ($this->db->trans_status() === false) {
                throw new Exception('Hubo un error al actualizar en web_unificada');
            }

            //Fin
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
            } else {
                $dataSalida['error'] = EXIT_SUCCESS;
                $dataSalida['msj'] = 'Se actualizo correctamente!';
                $this->db->trans_commit();
            }

        } catch (Exception $e) {
            $dataSalida['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $dataSalida;
    }

    public function updatePtrTo01($idPtr, $grafo)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $data = array(
                "estado" => '01 - APROBADA VALORIZADA',
                "fec_aprob" => date("d/m/Y H:i:s"),
                "usua_aprob" => $this->session->userdata('userSession'),
                "estado_asig_grafo" => '0',
            );
            $this->db->where('ptr', $idPtr);
            $this->db->update('web_unificada_det', $data);
            if ($this->db->affected_rows() == 0) {
                throw new Exception('Hubo un error al actualizar en web_unificada_det');
            }

            $this->db->query("SELECT getGrafoOnePTR('" . $idPtr . "');");
            if ($this->db->trans_status() === true) {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se actualizo correctamente!';
            } else {
                $this->db->trans_rollback();
            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    public function getBandejaPreMo($SubProy, $eecc, $zonal, $itemPlan, $mesEjec, $expediente)
    {
        $Query = " SELECT po.itemPlan,po.indicador ,sp.subProyectoDesc, z.zonalDesc, ec.empresaColabDesc,
              CASE
            					WHEN substr(po.fechaPrevEjec,6,2) = '01'  THEN  'ENE'
            					WHEN substr(po.fechaPrevEjec,6,2) = '02'  THEN  'FEB'
            					WHEN substr(po.fechaPrevEjec,6,2) = '03'  THEN  'MAR'
            					WHEN substr(po.fechaPrevEjec,6,2) = '04'  THEN  'ABR'
            					WHEN substr(po.fechaPrevEjec,6,2) = '05'  THEN  'MAY'
            					WHEN substr(po.fechaPrevEjec,6,2) = '06'  THEN  'JUN'
            					WHEN substr(po.fechaPrevEjec,6,2) = '07'  THEN  'JUL'
            					WHEN substr(po.fechaPrevEjec,6,2) = '08'  THEN  'AGO'
            					WHEN substr(po.fechaPrevEjec,6,2) = '09'  THEN  'SEP'
            					WHEN substr(po.fechaPrevEjec,6,2) = '10'  THEN  'OCT'
            					WHEN substr(po.fechaPrevEjec,6,2) = '11'  THEN  'NOV'
            					WHEN substr(po.fechaPrevEjec,6,2) = '12'  THEN  'DIC'
            				ELSE NULL
            				END as fechaPrevEjec,
                            (SELECT count(1) FROM estacionarea, subproyectoestacion, detalleplan
LEFT JOIN ptr_expediente
ON detalleplan.itemplan = ptr_expediente.itemplan
AND  detalleplan.poCod =  ptr_expediente.ptr
where detalleplan.idSubProyectoEstacion = subproyectoestacion.idSubProyectoEstacion
AND subproyectoestacion.idEstacionArea = estacionarea.idEstacionArea
AND detalleplan.itemplan = po.itemplan
AND idEstacion = 1
and ptr_expediente.ptr is null) as hasDise,
                            (SELECT COUNT(1) FROM itemplan_expediente where itemplan = po.itemplan and estado = 'ACTIVO') as hasExpe
            from planobra po, subproyecto sp, zonal z, empresacolab ec
            where po.idSubProyecto = sp.idSubProyecto
            and po.idZonal = z.idZonal
            and po.idEmpresaColab = ec.idEmpresaColab
            and po.idEstadoPlan = 4 and (SELECT COUNT(1) FROM itemplan_expediente WHERE estado_final = 'FINALIZADO' and itemplan = po.itemPlan ) = 0";
        if ($SubProy != '') {
            $Query .= " AND sp.subProyectoDesc REGEXP '" . str_replace(',', '|', $SubProy) . "'";
        }
        if ($eecc != '') {
            $Query .= " AND ec.empresaColabDesc LIKE '%" . $eecc . "%'";
        }
        if ($zonal != '') {
            $Query .= " AND z.zonalDesc = '" . $zonal . "'";
        }
        if ($itemPlan != '') {
            $Query .= " AND po.itemplan = '" . $itemPlan . "'";
        }

        $certi = "";
        if ($expediente != '') {
            if ($expediente == 'SI') {
                $certi = ">";
            } else if ($expediente == 'NO') {
                $certi = "=";
            }
        }

        if ($expediente != '' && $mesEjec != '') {
            $Query .= " HAVING  fechaPrevEjec = '" . $mesEjec . "' AND edit " . $certi . " 0 ";
        } else if ($expediente != '' && $mesEjec == '') {
            $Query .= " HAVING  edit " . $certi . " 0 ";
        } else if ($expediente == '' && $mesEjec != '') {
            $Query .= " HAVING  fechaPrevEjec = '" . $mesEjec . "'";
        }

        $result = $this->db->query($Query, array());
        return $result;
    }

    public function getPtrByItemplan($itemplan)
    {
        $Query = " SELECT
                    dp.poCod as ptr,
                    dp.itemplan,
                    s.subproyectoDesc,
                    a.areaDesc,
                    substring(wud.estado,1,3) as estado_wud,
                    substring(wu.est_innova,1,3) as estado_wu,
                    wu.desc_area,
                    wu.jefatura,
                    wu.eecc,
                    wu.idEstadoPtr,
                    wu.f_creac_prop,
                    wud.valor_material,
	                wud.valor_m_o,
                    TRIM(SUBSTRING_INDEX(wu.vr,':',-1)) as vr_wu,
                    wud.vale_reserva as vr_wud, CASE WHEN pe.ptr is not null then 1 else 0 end as hasPtrExpe,
                    e.estacionDesc
                    from detalleplan dp
                    INNER JOIN subproyectoestacion se ON dp.idSubProyectoEstacion = se.idSubProyectoEstacion
                    INNER JOIN subproyecto s ON se.idSubProyecto = s.idSubProyecto
                    INNER JOIN estacionarea ea ON se.idEstacionArea = ea.idEstacionArea
                    INNER JOIN area a ON ea.idArea = a.idArea
                    INNER JOIN estacion e ON ea.idEstacion = e.idEstacion
                    LEFT JOIN web_unificada_det wud ON wud.ptr = dp.poCod
                    LEFT JOIN web_unificada wu ON wu.ptr = dp.poCod
                    LEFT JOIN ptr_expediente pe
                    ON dp.itemplan = pe.itemplan AND dp.poCod = pe.ptr
                    where dp.itemplan = ?
                    AND dp.poCod != 'NOREQUIERE'
                    order by
                    substring(wu.est_innova,1,3) ASC";

        $result = $this->db->query($Query, array($itemplan));
        return $result;
    }

    public function getCertificadoByItemPlan($itemplan)
    {
        $Query = "SELECT * FROM itemplan_expediente WHERE itemplan = ? ORDER BY estado;";
        $result = $this->db->query($Query, array($itemplan));
        return $result;
    }

    public function haveActivo($itemplan)
    {
        $sql = "SELECT COUNT(1) as count FROM itemplan_expediente where estado = 'ACTIVO' and itemplan = ? ;";
        $result = $this->db->query($sql, array($itemplan));
        return ($result->row()->count);
    }

    public function cancelCertificado($id)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $data = array(
                "estado" => 'DEVUELTO',
                "estado_final" => null,
                "usuario" => $this->session->userdata('userSession'),
            );
            $this->db->where('id', $id);
            $this->db->update('itemplan_expediente', $data);
            if ($this->db->affected_rows() == 0) {
                throw new Exception('Hubo un error al actualizar en itemplan_expediente');
            } else {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se actualizo correctamente!';
            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    public function saveCertificado($itemplan, $fecha, $comentario)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $data = array(
                "itemplan" => $itemplan,
                "fecha" => $fecha,
                "comentario" => $comentario,
                "usuario" => $this->session->userdata('userSession'),
                "estado" => 'ACTIVO',
                "estado_final" => 'PENDIENTE',
            );

            $this->db->insert('itemplan_expediente', $data);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar en itemplan_expediente');
            } else {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se inserto correctamente!';
            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    public function preAprobarTerminados($itemplan)
    {
        $dataeXIT['error'] = EXIT_ERROR;
        $dataeXIT['msj'] = null;
        try {
            $this->db->trans_begin();

            $data = array(
                "estado" => '01 - APROBADA VALORIZADA',
            );
            $this->db->where('itemplan', $itemplan);
            $this->db->where("(estado_asig_grafo='0' OR estado_asig_grafo='1')");
            $this->db->update('web_unificada_det', $data);
            if ($this->db->trans_status() === false) {
                throw new Exception('Hubo un error al actualizar en web_unificada_det');
            }

            $data = array(
                "estado_final" => 'FINALIZADO',
                "usuario_valida" => $this->session->userdata('userSession'),
                "fecha_valida" => date("Y-m-d H:i:s"),
            );
            $this->db->where('itemplan', $itemplan);
            $this->db->where('estado', 'ACTIVO');
            $this->db->update('itemplan_expediente', $data);
            if ($this->db->affected_rows() == 0) {
                throw new Exception('Hubo un error al actualizar en itemplan_expediente');
            }

            $this->db->query("SELECT getGrafoByItemplan('" . $itemplan . "');");
            if ($this->db->trans_status() === true) {
                $this->db->trans_commit();
                $dataeXIT['error'] = EXIT_SUCCESS;
                $dataeXIT['msj'] = 'Se actualizo correctamente!';
            } else {
                $this->db->trans_rollback();
            }

        } catch (Exception $e) {
            $dataeXIT['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }

        return $dataeXIT;
    }

    public function getItemplanExpediente()
    {
        $Query = "  SELECT po.itemPlan
            	        FROM planobra po where  po.idEstadoPlan = 4
            	        and (SELECT COUNT(1) FROM itemplan_expediente WHERE estado_final = 'FINALIZADO' and itemplan = po.itemPlan ) = 0";
        $result = $this->db->query($Query, array());
        return $result;
    }

    public function insertOrDeletePtrExpediente($accion, $ptr, $itemplan)
    {
        $rpta['error'] = EXIT_ERROR;
        $rpta['msj'] = null;
        try {

            if ($accion == '1') { //INSERT
                $this->db->trans_begin();
                $data = array(
                    "ptr" => $ptr,
                    "itemplan" => $itemplan);
                $this->db->insert('ptr_expediente', $data);
                if ($this->db->affected_rows() != 1) {
                    $this->db->trans_rollback();
                    throw new Exception('Error al insertar en ptrExpediente');
                } else {
                    $this->db->trans_commit();
                    $rpta['error'] = EXIT_SUCCESS;
                    $rpta['msj'] = 'Se agrego correctamente!';
                }

            } else if ($accion == '2') { //DELETE

                $this->db->trans_begin();
                $this->db->where('ptr', $ptr);
                $this->db->where('itemplan', $itemplan);
                $this->db->delete('ptr_expediente');
                $this->db->trans_complete();
                if ($this->db->trans_status() === false) {
                    $this->db->trans_rollback();
                    throw new Exception("Error al Eliminar ptrExpediente");
                } else {
                    $this->db->trans_commit();
                    $rpta['msj'] = 'Se elimino correctamente ';
                    $rpta['error'] = EXIT_SUCCESS;
                }

            }

        } catch (Exception $e) {
            $rpta['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $rpta;
    }

    public function saveLogSinFix($itemplan, $ptr, $error)
    {
        $rpta['error'] = EXIT_ERROR;
        $rpta['msj'] = null;
        try {
            $this->db->trans_begin();
            $data = array(
                "tabla" => 'sinfix',
                "actividad" => 'ingresar',
                "itemplan" => $itemplan,
                "itemplan_default" => 'En Obra:' . $error,
                "ptr" => $ptr,
                "fecha_registro" => date("Y-m-d h:m:s"),
            );
            $this->db->insert('log_planobra', $data);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar en ptrExpediente');
            } else {
                $this->db->trans_commit();
                $rpta['error'] = EXIT_SUCCESS;
                $rpta['msj'] = 'Se agrego correctamente!';
            }
        } catch (Exception $e) {
            $rpta['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $rpta;
    }

    public function changeEstadoEnObraPlan($itemplan, $estadoPlan)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $this->db->trans_begin();
            $dataUpdate = array(
                'idEstadoPlan' => $estadoPlan,
            );
            $this->db->where('itemplan', $itemplan);
            $this->db->update('planobra', $dataUpdate);
            if ($this->db->trans_status() === false) {
                throw new Exception('Hubo un error al actualizar el estadoplan.');
            } else {

                $dataUpdateLog = array(
                    'tabla'     => 'planobra',
                    'actividad' => 'update',
                    'itemplan'  => $itemplan,
                    'itemplan_default' => 'idEstadoPlan=' . $estadoPlan . '|FROM_BANDEJA_APROBACION',
                    'fecha_registro' => date("Y-m-d H:i:s"),
                    'id_usuario' => $this->session->userdata('idPersonaSession'),
                    'idEstadoPlan' => $estadoPlan
                );

                $this->db->insert('log_planobra', $dataUpdateLog);
                if ($this->db->affected_rows() != 1) {
                    $this->db->trans_rollback();
                    throw new Exception('Hubo un error al actualizar el estadoplan.');
                } else {
                    $data['error'] = EXIT_SUCCESS;
                    $data['msj'] = 'Se actualizo correctamente!';
                    $this->db->trans_commit();
                }

            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
   function insertSiom($dataSiom, $dataLogPo, $dataEstado) {
		$data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();	
	        $this->db->insert('siom_obra', $dataSiom);
	        if($this->db->affected_rows() != 1) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar en siom_obra');
	        }else{
	            $this->db->insert('log_planobra', $dataLogPo);
    	        if($this->db->affected_rows() != 1) {
    	            $this->db->trans_rollback();
    	            throw new Exception('Error al insertar en log_planobra');
    	        }else{
    	            $this->db->insert('log_tramas_estados_siom', $dataEstado);
    	            if($this->db->affected_rows() != 1) {
    	                $this->db->trans_rollback();
    	                throw new Exception('Error al insertar en log_tramas_estados_siom');
    	            }else{
    	                $data['error'] = EXIT_SUCCESS;
    	                $data['msj'] = 'Se actualizo correctamente!';
    	                $this->db->trans_commit();
    	            }
    	        }              	            
	        }	        	        
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
	
	function updateSiom($dataSiom, $dataLogPo, $dataEstado, $id_siom_obra) {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $this->db->where('id_siom_obra', $id_siom_obra);
    		$this->db->update('siom_obra', $dataSiom);
        	if ($this->db->trans_status() === FALSE) {
        	    throw new Exception('Hubo un error al actualizar siom_obra');
        	}else{
	            $this->db->insert('log_planobra', $dataLogPo);
	            if($this->db->affected_rows() != 1) {
	                $this->db->trans_rollback();
	                throw new Exception('Error al insertar en log_planobra');
	            }else{
	                $this->db->insert('log_tramas_estados_siom', $dataEstado);
	                if($this->db->affected_rows() != 1) {
	                    $this->db->trans_rollback();
	                    throw new Exception('Error al insertar en log_tramas_estados_siom');
	                }else{
	                    $data['error'] = EXIT_SUCCESS;
	                    $data['msj'] = 'Se actualizo correctamente!';
	                    $this->db->trans_commit();
	                }
	            }
	        }
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}

    public function estacionEjecutadaDiseno($itemplan, $estacion)
    {
        $query = " SELECT COUNT(1) as cont
                 FROM   pre_diseno
                 WHERE  itemplan = ?
                 AND    idEstacion = ?
                 AND    estado = " . ESTADO_ESTACION_DISENO_EJECUTADO;
        $result = $this->db->query($query, array($itemplan, $estacion));
        return $result->row()->cont;
    }

    public function updatePtrTo01Diseno($idPtr, $grafo, $fechaActual = null, $itemplan = null, $idArea = null)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;
            $arrayPtr = explode('-', $idPtr);
            
            if($arrayPtr[0] > 2018) {
                $this->db->trans_begin();
                $sql = "UPDATE planobra_po 
                           SET estado_po = ".PO_VALIDADO.", 
                               fecha_validacion = '".$fechaActual."'
                         WHERE codigo_po = '".$idPtr."' 
                           AND CASE WHEN ".$idArea." = 2 THEN 
                                    1 = ( SELECT CASE WHEN has_sirope_diseno IS NULL OR has_sirope_diseno = '' THEN has_sirope
                                                      ELSE has_sirope_diseno END 
                                            FROM planobra 
                                            WHERE itemplan='".$itemplan."')
                                   ELSE true END";
                $this->db->query($sql);           

                 if ($this->db->affected_rows() == 0) {
                        throw new Exception('NO SE SUBI&Oacute; SIROPE');
                    } else {
                        $dataLogPO = array(
                            'codigo_po'         =>  $idPtr,
                            'itemplan'          =>  $itemplan,
                            'idUsuario'         =>  $idUsuario,
                            'fecha_registro'    =>  $fechaActual,
                            'idPoestado'        =>  PO_APROBADO,
                            'controlador'       =>  'BANDEJA PRE APROBADO'
                        );
                        $this->db->insert('log_planobra_po', $dataLogPO);
        	            if($this->db->affected_rows() != 1) {
        	                $this->db->trans_rollback();
        	                throw new Exception('Error al insertar en log_planobra_po');
        	            }else{
        	                  $dataLogPO = array(
                                                    'codigo_po'         =>  $idPtr,
                                                    'itemplan'          =>  $itemplan,
                                                    'idUsuario'         =>  $idUsuario,
                                                    'fecha_registro'    =>  $fechaActual,
                                                    'idPoestado'        =>  PO_LIQUIDADO,
                                                    'controlador'       =>  'BANDEJA PRE APROBADO'
                                                );
                            $this->db->insert('log_planobra_po', $dataLogPO);
            	            if($this->db->affected_rows() != 1) {
            	                $this->db->trans_rollback();
            	                throw new Exception('Error al insertar en log_planobra_po');
            	            }else{
            	                $dataLogPO = array(
                                                    'codigo_po'         =>  $idPtr,
                                                    'itemplan'          =>  $itemplan,
                                                    'idUsuario'         =>  $idUsuario,
                                                    'fecha_registro'    =>  $fechaActual,
                                                    'idPoestado'        =>  PO_VALIDADO,
                                                    'controlador'       =>  'BANDEJA PRE APROBADO'
                                                  );
                                $this->db->insert('log_planobra_po', $dataLogPO);
                	            if($this->db->affected_rows() != 1) {
                	                $this->db->trans_rollback();
                	                throw new Exception('Error al insertar en log_planobra_po');
                	            }else{
                	                $this->db->trans_commit();
                                    $data['error'] = EXIT_SUCCESS;
                                    $data['msj'] = 'Se actualizo correctamente!';
                	            }
            	            }
        	            }
                    }
            } else {
                $this->db->trans_begin();
                $data = array(
                    "estado" => '01 - APROBADA VALORIZADA',
                    "fec_aprob" => date("d/m/Y H:i:s"),
                    "usua_aprob" => $this->session->userdata('userSession'),
                    "estado_asig_grafo" => '0',
                    "has_pre_aprob" => '1',
                );
                    $this->db->where('ptr', $idPtr);
                    $this->db->update('web_unificada_det', $data);
                    if ($this->db->affected_rows() == 0) {
                        throw new Exception('Hubo un error al actualizar en web_unificada_det');
                    } else {
                        $this->db->trans_commit();
                        $data['error'] = EXIT_SUCCESS;
                        $data['msj'] = 'Se actualizo correctamente!';
                    }
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }

        return $data;
    }

    public function updatePOTo2($ptr, $itemplan, $arrayUpdate,$fechaActual)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        try {
           

                $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;
                if ($idUsuario != null) {
                    $this->db->trans_begin();
                    $this->db->where('itemplan', $itemplan);
                    $this->db->where('codigo_po', $ptr);
                    $this->db->update('planobra_po', $arrayUpdate);
                    if ($this->db->trans_status() === false) {
                        $this->db->trans_rollback();
                        throw new Exception('Hubo un error al actualizar el monto.');
                    } else {
    					$arrayInsertLog = array(
    						"codigo_po" => $ptr,
    						"itemplan" => $itemplan,
    						"idUsuario" => $idUsuario,
    						"fecha_registro" => $fechaActual,
    						"idPoestado" => 2,
    						"controlador" => 'Bandeja PreAprob'
    					);
    					$this->db->insert('log_planobra_po', $arrayInsertLog);
    					if ($this->db->affected_rows() != 1) {
    						$this->db->trans_rollback();
    						throw new Exception('Error al insertar tabla log_planobra_po');
    					} else {
    					    $this->db->query("SELECT getGrafoOnePTR('" . $ptr . "');");
    					    if ($this->db->trans_status() === true) {
    					        $this->db->trans_commit();
        						$data['error'] = EXIT_SUCCESS;
        						$data['msj'] = 'Se actualiz&oacute; correctamente!!';
    					    } else {
    					        $this->db->trans_rollback();
    					        throw new Exception('Error al asignar grafo.');
    					    }    						
    					}
                    }

            } else {
                $this->db->trans_rollback();
                throw new Exception('Su sesion de usuario ha expirado, intentelo de nuevo!!');
            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        return $data;
    }

    public function getMatSapByPO_IP_EECC($itemplan,$codigoPO,$idEmpresaColab){
		$sql = " SELECT ppod.codigo_po,
						ppod.codigo_material,
						jse.codCentro,
						ppod.cantidad_final as cantidad_ingreso,
						'L' AS t,
						jse.codAlmacen,
						DATE_FORMAT(CURDATE(), '%d.%m.%Y') AS fecha
				   FROM (
				        planobra_po ppo,
						planobra_po_detalle ppod,
						planobra po,
						central c
						) 
						LEFT JOIN 
						(
						jefatura_sap jsap,
						jefatura_sap_x_empresacolab jse) ON (jse.idEmpresaColab = ppo.id_eecc_reg
						 AND jse.idEmpresaColab = ".$idEmpresaColab."
						 AND jsap.idJefatura = jse.idJefatura
						 AND (CASE WHEN  (jsap.idZonal IS NULL OR jsap.idZonal = '') THEN c.jefatura = jsap.descripcion ELSE jsap.idZonal = c.idZonal END )
						 )
						
				WHERE ppo.codigo_po = ppod.codigo_po
					AND ppo.itemplan = po.itemplan
					AND po.idCentral = c.idCentral
					AND ppo.itemplan = '".$itemplan."' 
					AND ppo.codigo_po = '".$codigoPO."'";
		$result = $this->db->query($sql);
		log_message('error', $this->db->last_query());
		return $result->result();
    }
    public function getPOPreCancelados($idSubProyecto, $idEmpresaColab, $idFase, $idZonal)
    {
        $sql = "SELECT f.faseDesc AS fase_desc,
                            ppo.codigo_po AS ptr,
                            poe.estado,
                            z.zonalDesc AS zonal,
                            ec.empresaColabDesc AS eecc,
                            sp.subProyectoDesc AS subProy,
                            '-' AS pep1,
                            '-' AS pep2,
                            ppo.fechaRegistro as fecCrea,
                            '-' AS fecSol,
                            '-' AS usua_aprob,
                            '-' AS fec_aprob,
                            '-' AS grafo,
                            '-' AS fecha_asig_grafo,
                            '-' AS estado_asig_grafo,
                            a.tipoArea AS desc_area,
                            ppo.itemplan,
                            '-' AS fec_prevista_ejec,
                            e.estacionDesc AS estacion_desc,
                            a.areaDesc AS area_desc,
                            '-' AS fec_prev_ejec,
                            '-' AS usua_asig_grafo,
                            ppo.costo_total AS valor_material,
                            '-' AS valor_m_o,
                            '-' AS indicador,
                            '-' AS grafo_from,
                            '-' AS vale_reserva,
                            '-' AS esta_validada,
                            '-' AS has_pre_aprob,
                            '-' AS horas,
                            po.idEstadoPlan,
                            CASE
                                WHEN substr(po.fechaPrevEjec,6,2) = '01'  THEN  'ENE'
                                WHEN substr(po.fechaPrevEjec,6,2) = '02'  THEN  'FEB'
                                WHEN substr(po.fechaPrevEjec,6,2) = '03'  THEN  'MAR'
                                WHEN substr(po.fechaPrevEjec,6,2) = '04'  THEN  'ABR'
                                WHEN substr(po.fechaPrevEjec,6,2) = '05'  THEN  'MAY'
                                WHEN substr(po.fechaPrevEjec,6,2) = '06'  THEN  'JUN'
                                WHEN substr(po.fechaPrevEjec,6,2) = '07'  THEN  'JUL'
                                WHEN substr(po.fechaPrevEjec,6,2) = '08'  THEN  'AGO'
                                WHEN substr(po.fechaPrevEjec,6,2) = '09'  THEN  'SEP'
                                WHEN substr(po.fechaPrevEjec,6,2) = '10'  THEN  'OCT'
                                WHEN substr(po.fechaPrevEjec,6,2) = '11'  THEN  'NOV'
                                WHEN substr(po.fechaPrevEjec,6,2) = '12'  THEN  'DIC'
                            ELSE NULL
                            END as mesEjec,
                            substr(po.fechaPrevEjec,1,4) as ano_ejec, po.hasAdelanto,
                            (CASE WHEN sp.idProyecto = 4 AND (po.idEstadoPlan = 3 OR po.idEstadoPlan = 9) AND a.tipoArea = 'MO' AND e.estacionDesc != 'DISEﾃ前'  THEN 1 ELSE 0 END) as esta_validada2,
                            ec.idEmpresaColab,
                            m.motivoDesc AS motivo,
                            poc.observacion,
                            (SELECT nombre FROM usuario WHERE id_usuario = poc.id_usuario) AS usua_precancela,
                            e.idEstacion
                    FROM planobra_po ppo,
                            planobra po,
                            subproyecto sp,
                            detalleplan dp,
                            subproyectoestacion se,
                            estacionarea ea,
                            estacion e,
                            area a,
                            central c,
                            zonal z,
                            empresacolab ec,
                            fase f,
                            po_estado poe,
                            po_cancelar poc,
                            motivo m
                    WHERE ppo.itemplan = po.itemplan
                        AND po.idSubProyecto = sp.idSubproyecto
                        AND dp.idSubProyectoEstacion = se.idSubProyectoEstacion
                        AND dp.itemplan = ppo.itemplan
                        AND dp.poCod = ppo.codigo_po
                        AND se.idEstacionArea = ea.idEstacionArea
                        AND ea.idArea = a.idArea
                        AND ppo.idEstacion = e.idEstacion
                        AND po.idCentral = c.idCentral
                        AND c.idZonal = z.idZonal
                        AND	(CASE WHEN sp.idTipoSubProyecto = 2 THEN c.idEmpresaColabCV = ec.idEmpresaColab ELSE c.idEmpresaColab = ec.idEmpresaColab END)
                        AND po.itemPlan = dp.itemPlan
                        AND po.idFase = f.idFase
                        AND ppo.estado_po = poe.idPoestado
                
                        AND ppo.itemplan = poc.itemplan 
                        AND ppo.codigo_po = poc.codigo_po
                        AND poc.idMotivo = m.idMotivo
                        AND ppo.estado_po = 7
                        AND poc.idPoestado = 7
						AND po.idSubProyecto  = COALESCE(?, po.idSubProyecto)
						AND ec.idEmpresaColab = COALESCE(?, ec.idEmpresaColab)
						AND f.idFase          = COALESCE(?, f.idFase)
						AND c.idZonal         = COALESCE(?, c.idZonal)
				UNION ALL
					SELECT f.faseDesc AS fase_desc,
                            ppo.codigo_po AS ptr,
                            poe.estado,
                            z.zonalDesc AS zonal,
                            ec.empresaColabDesc AS eecc,
                            sp.subProyectoDesc AS subProy,
                            '-' AS pep1,
                            '-' AS pep2,
                            ppo.fechaRegistro as fecCrea,
                            '-' AS fecSol,
                            '-' AS usua_aprob,
                            '-' AS fec_aprob,
                            '-' AS grafo,
                            '-' AS fecha_asig_grafo,
                            '-' AS estado_asig_grafo,
                            a.tipoArea AS desc_area,
                            ppo.itemplan,
                            '-' AS fec_prevista_ejec,
                            e.estacionDesc AS estacion_desc,
                            a.areaDesc AS area_desc,
                            '-' AS fec_prev_ejec,
                            '-' AS usua_asig_grafo,
                            ppo.costo_total AS valor_material,
                            '-' AS valor_m_o,
                            '-' AS indicador,
                            '-' AS grafo_from,
                            '-' AS vale_reserva,
                            '-' AS esta_validada,
                            '-' AS has_pre_aprob,
                            '-' AS horas,
                            po.idEstadoPlan,
                            CASE
                                WHEN substr(po.fechaPrevEjec,6,2) = '01'  THEN  'ENE'
                                WHEN substr(po.fechaPrevEjec,6,2) = '02'  THEN  'FEB'
                                WHEN substr(po.fechaPrevEjec,6,2) = '03'  THEN  'MAR'
                                WHEN substr(po.fechaPrevEjec,6,2) = '04'  THEN  'ABR'
                                WHEN substr(po.fechaPrevEjec,6,2) = '05'  THEN  'MAY'
                                WHEN substr(po.fechaPrevEjec,6,2) = '06'  THEN  'JUN'
                                WHEN substr(po.fechaPrevEjec,6,2) = '07'  THEN  'JUL'
                                WHEN substr(po.fechaPrevEjec,6,2) = '08'  THEN  'AGO'
                                WHEN substr(po.fechaPrevEjec,6,2) = '09'  THEN  'SEP'
                                WHEN substr(po.fechaPrevEjec,6,2) = '10'  THEN  'OCT'
                                WHEN substr(po.fechaPrevEjec,6,2) = '11'  THEN  'NOV'
                                WHEN substr(po.fechaPrevEjec,6,2) = '12'  THEN  'DIC'
                            ELSE NULL
                            END as mesEjec,
                            substr(po.fechaPrevEjec,1,4) as ano_ejec, po.hasAdelanto,
                            (CASE WHEN sp.idProyecto = 4 AND (po.idEstadoPlan = 3 OR po.idEstadoPlan = 9) AND a.tipoArea = 'MO' AND e.estacionDesc != 'DISEﾃ前'  THEN 1 ELSE 0 END) as esta_validada2,
                            ec.idEmpresaColab,
                            m.motivoDesc AS motivo,
                            poc.observacion,
                            (SELECT nombre FROM usuario WHERE id_usuario = poc.id_usuario) AS usua_precancela,
                            e.idEstacion
                    FROM planobra_po ppo,
                            planobra po,
                            subproyecto sp,
                            detalleplan dp,
                            subproyectoestacion se,
                            estacionarea ea,
                            estacion e,
                            area a,
                            pqt_central c,
                            zonal z,
                            empresacolab ec,
                            fase f,
                            po_estado poe,
                            po_cancelar poc,
                            motivo m
                    WHERE ppo.itemplan = po.itemplan
                        AND po.idSubProyecto = sp.idSubproyecto
                        AND dp.idSubProyectoEstacion = se.idSubProyectoEstacion
                        AND dp.itemplan = ppo.itemplan
                        AND dp.poCod = ppo.codigo_po
                        AND se.idEstacionArea = ea.idEstacionArea
                        AND ea.idArea = a.idArea
                        AND ppo.idEstacion = e.idEstacion
                        AND po.idCentralPqt = c.idCentral
                        AND c.idZonal = z.idZonal
                        AND	(CASE WHEN sp.idTipoSubProyecto = 2 THEN c.idEmpresaColabCV = ec.idEmpresaColab ELSE c.idEmpresaColab = ec.idEmpresaColab END)
                        AND po.itemPlan = dp.itemPlan
                        AND po.idFase = f.idFase
                        AND ppo.estado_po = poe.idPoestado
                
                        AND ppo.itemplan = poc.itemplan 
                        AND ppo.codigo_po = poc.codigo_po
                        AND poc.idMotivo = m.idMotivo
                        AND ppo.estado_po = 7
                        AND poc.idPoestado = 7
						AND po.idSubProyecto  = COALESCE(?, po.idSubProyecto)
						AND ec.idEmpresaColab = COALESCE(?, ec.idEmpresaColab)
						AND f.idFase          = COALESCE(?, f.idFase)
						AND c.idZonal         = COALESCE(?, c.idZonal)";

        // if($idSubProyecto != '' && $idSubProyecto != null ){
            // $sql .= " AND po.idSubProyecto = " . $idSubProyecto . "";
        // }
        // if ($idEmpresaColab != '' && $idEmpresaColab != null) {
            // $sql .= " AND ec.idEmpresaColab = '" . $idEmpresaColab . "' ";
        // }
        // if ($idFase != '' && $idFase != null) {
            // $sql .= " AND f.idFase = '" . $idFase . "' ";
        // }
        // if ($idZonal != '' && $idZonal != null) {
            // $sql .= " AND c.idZonal = '" . $idZonal . "' ";
        // }
        $result = $this->db->query($sql, array($idSubProyecto, $idEmpresaColab, $idFase, $idZonal, $idSubProyecto, $idEmpresaColab, $idFase, $idZonal));
		return $result->result();
    }
    
    function aprobarPOWebPO($ptr, $itemplan, $fechaActual, $from, $vale_reserva)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
    
        try {
            $dataFrom = explode("-", $from);
            $this->db->trans_begin();
            $arrayUpdate = array(
                "estado_po" => 3,//APROBADO
                "vale_reserva" => $vale_reserva
                
            );
            $this->db->where('itemplan', $itemplan);
            $this->db->where('codigo_po', $ptr);
            $this->db->update('planobra_po', $arrayUpdate);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al actualizar el monto.');
            } else {
                if ($dataFrom[0] == DATA_FROM_ITEMPLAN_PEP2_GRAFO) {
                    $data = array(
                        "estado" => ESTADO_CON_GRAFO_ULTILIZADO,
                    );
                    $this->db->where('id', intval($dataFrom[1]));
                    $this->db->update('itemplan_pep2_grafo', $data);
                    /* if($this->db->affected_rows() != 1) {
                     //throw new Exception($this->db->);
                     throw new Exception('Hubo un error al actualizar en pep2_grafo itemplan');
                    }*/
                } else if ($dataFrom[0] == DATA_FROM_SISEGO_PEP2_GRAFO) {
                    /*
					$data = array(
                        "estado" => ESTADO_CON_GRAFO_ULTILIZADO,
                    );
                    $this->db->where('id', intval($dataFrom[1]));
                    $this->db->update('sisego_pep2_grafo', $data);
                    if($this->db->affected_rows() != 1) {
                     //throw new Exception($this->db->);
                     throw new Exception('Hubo un error al actualizar en pep2_grafo sisego');
                    }*/
                } else if ($dataFrom[0] == DATA_FROM_PEP2_GRAFO) {
                    $data = array(
                        "estado" => ESTADO_CON_GRAFO_ULTILIZADO,
                    );
                    $this->db->where('id', intval($dataFrom[1]));
                    $this->db->update('pep2_grafo', $data);
                    if ($this->db->affected_rows() != 1) {
                        //throw new Exception($this->db->);
                        $this->db->trans_rollback();
                        throw new Exception('Hubo un error al actualizar en pep2_grafo');
                    }
                }
                
                
                $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;
                if ($idUsuario != null) {
    
                    $arrayInsertLog = array(
                        "codigo_po" => $ptr,
                        "itemplan" => $itemplan,
                        "idUsuario" => $idUsuario,
                        "fecha_registro" => $fechaActual,
                        "idPoestado" => 3,//APROBADO
                        "controlador" => 'Bandeja Aprob'
                    );
                    $this->db->insert('log_planobra_po', $arrayInsertLog);
                    if ($this->db->affected_rows() != 1) {
                        $this->db->trans_rollback();
                        throw new Exception('Error al insertar tabla log_planobra_po');
                    } else {
                        $count = $this->countMatByPo($ptr);
                        if($count == 1) { //SI ES MATERIAL
                            $this->db->where('itemplan'     , $itemplan);
                            $this->db->where('codigo_po'    , $ptr);
                            $this->db->where('flg_tipo_area', 1);
                            $this->db->where('estado_po'    , 3);
                            $this->db->update('planobra_po', array('estado_po' => 4));

                            if($this->db->affected_rows() < 1) {
                                $this->db->trans_rollback();
                                throw new Exception('Error al liquidar PO');
                            } else {
                                $arrayLog = array(
                                                    "codigo_po"      => $ptr,
                                                    "itemplan"       => $itemplan,
                                                    "idUsuario"      => $idUsuario,
                                                    "fecha_registro" => $fechaActual,
                                                    "idPoestado"     => 4,//liquidado
                                                    "controlador"    => 'Bandeja Aprob'
                                                 );
                                $this->db->insert('log_planobra_po', $arrayLog);
                                if ($this->db->affected_rows() != 1) {
                                    $this->db->trans_rollback();
                                    throw new Exception('Error al insertar tabla log_planobra_po');
                                } else {
                                    $this->db->trans_commit();
                                    $data['error'] = EXIT_SUCCESS;
                                    $data['msj'] = 'Se actualiz&oacute; correctamente!!';
                                }
                            }  
                        } else {
                            $this->db->trans_commit();
                            $data['error'] = EXIT_SUCCESS;
                            $data['msj'] = 'Se actualiz&oacute; correctamente!!';
                        }
                    }
                } else {
                    $this->db->trans_rollback();
                    throw new Exception('Su sesion de usuario ha expirado, intentelo de nuevo!!');
                }
    
            }
    
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
    
        return $data;
    }
    
    function countMatByPo($po) {
        $sql = "SELECT 1 as flg
                  FROM planobra_po ppo,
                       itemplanestacionavance i 
                 WHERE ppo.codigo_po = ?
                   AND ppo.flg_tipo_area = 1
                   AND ppo.itemplan   = i.itemplan
                   AND ppo.idEstacion = i.idEstacion
                   AND i.porcentaje   = 100";
        $result = $this->db->query($sql,array($po));   
        return $result->row_array()['flg'];     
    }
    
    function insertLogTramaSiom($dataLogSiom, $dataSiom) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->trans_begin();
            $this->db->insert('log_tramas_siom', $dataLogSiom);
            if($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar en log_tramas_siom');
            }else{                
                $this->db->insert('siom_obra', $dataSiom);
                if($this->db->affected_rows() != 1) {
                    $this->db->trans_rollback();
                    throw new Exception('Error al insertar en siom_obra');
                }else{
                    $data['error'] = EXIT_SUCCESS;
                    $data['msj'] = 'Se actualizo correctamente!';
                    $this->db->trans_commit();                    
                }
            }
        }catch(Exception $e){
            $data['msj']   = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function getEmplazamientoIdSiomByidCentral($idCentral) {
        $sql = "SELECT count(1) as cant, sn.empl_id
                FROM siom_nodo sn JOIN central c ON sn.empl_nemonico = c.codigo
                and idCentral = ?";
        $result = $this->db->query($sql,array($idCentral));
        return $result->row_array();
    }
    
     function   getItemPtrSendSiom(){
        $Query = " SELECT * FROM a_data_siom_import WHERE id NOT IN (0)" ;
        $result = $this->db->query($Query,array());
        return $result;
    }
	
	function insertLogTramaSiomSoloLog($dataLogSiom) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->trans_begin();
            $this->db->insert('log_tramas_siom', $dataLogSiom);
            if($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar en log_tramas_siom');
            }else{           
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se actualizo correctamente!';
                $this->db->trans_commit();
            }
        }catch(Exception $e){
            $data['msj']   = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
}