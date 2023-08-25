<?php
class M_solicitud_Vr extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
    }
    
    //SIN WEB UNIFICADA
    function insertSolicitudKit($arrayInsertKit, $arrayPlanObraPoCantFin, $codigo) {
        $this->db->trans_begin();
        $this->db->insert_batch('solicitud_vale_reserva', $arrayInsertKit);
        if($this->db->trans_status() === TRUE) {
            $this->db->trans_commit();
            return array('error' => EXIT_SUCCESS, 'msj' => 'correcto');
        } else {
            $this->db->trans_rollback();
            return array('error' => EXIT_ERROR  , 'msj' => 'error No se insert&oacute; la solicitud');
        }
    }

    // function ingresarDetallePoDevolucion($arrayDetallePO) {
    //     $this->db->trans_begin();
    //     foreach($arrayDetallePO as $row) {
    //         if($row[idTipo)
    //         $this->db->where('codigo_po'      , $row['codigo_po']);
    //         $this->db->where('codigo_material', $row['codigo_material']);
    //         $this->db->update('planobra_po_detalle', array('cantidad_final' => $row['cantidad_final']));
    //     }
    //     if($this->db->trans_status() === TRUE) {
    //         $this->db->trans_commit();
    //         return array('error' => EXIT_SUCCESS, 'msj' => 'correcto');
    //     } else {
    //         $this->db->trans_rollback();
    //         return array('error' => EXIT_ERROR  , 'msj' => 'error No se actualiz&oacute; la cantidad final');
    //     }
    // }
    //CON WEB UNIFICADA
    function insertSolicitud($ArrayJson, $arrayUpdate, $itemplan, $ptr) {
        $this->db->trans_begin();
        $this->db->insert_batch('solicitud_vale_reserva', $ArrayJson);

        $this->db->where('ptr', $ptr);
        $this->db->where('itemplan', $itemplan);
        $this->db->update('web_unificada_det', $arrayUpdate);

        if($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $data['error'] = EXIT_ERROR;
			return $data;
        }else{
            $this->db->trans_commit();
            $data['error'] = EXIT_SUCCESS;
			return $data;
        }
    }
    
    function getBandejaSolicitudVr($itemplan, $idJefatura, $idEmpresaColab, $idTipoSolicitud, $idFase, $tipoAtencion) {
        $sql = "SELECT tb.*, 
                    (CASE WHEN tb.countValidados  > 0 AND tb.countRechazados > 0 AND tb.countPendientes = 0 THEN 1 -- PARCIALMENTE
                          WHEN tb.countPendientes > 0 														THEN 2 -- PENDIENTE
						  WHEN tb.countRechazados > 0 AND tb.countValidados = 0 THEN 3 -- RECHAZADO
						  WHEN tb.countValidados  > 0 AND tb.countRechazados = 0 THEN 4 END -- VALIDADO
					) as flgEstadoItemplan
                     FROM (SELECT svr.itemplan, svr.ptr, svr.idJefaturaSap, svr.idEmpresaColab, 
                    		SUM(CASE WHEN (svr.comentario IS NULL OR svr.comentario = '' ) AND (svr.flg_estado IS NULL OR svr.flg_estado = 0) AND fecha_atencion IS NULL THEN 1 ELSE 0 END) countPendientes,
                    		SUM(CASE WHEN svr.flg_estado = 0 AND svr.comentario IS NOT NULL AND svr.comentario <> '' THEN 1 ELSE 0 END) countRechazados,
                    		SUM(CASE WHEN svr.flg_estado = 1 THEN 1 ELSE 0 END) countValidados, 
                    svr.fecha_registro, svr.idUsuario, svr.flg_tipo_solicitud, svr.vr, svr.codigo, 
                    j.descripcion as jefaturaDesc,
                    e.empresaColabDesc as empresacolabDesc,
                    f.faseDesc,
                    (CASE WHEN svr.fecha_atencion IS NULL THEN 
                      TIMEDIFF(ADDTIME(NOW(), '01:00:00'), svr.fecha_registro)
                      ELSE  TIMEDIFF(svr.fecha_atencion, svr.fecha_registro) END) as tiempoAtencionSVr,
                    UPPER(GROUP_CONCAT(DISTINCT ts.descripcion)) as tipoSolicitudItemplan,
                    UPPER(GROUP_CONCAT(DISTINCT u.nombre)) as nombreUsuario,
					pep1,
					pep2,
					grafo,
                    MAX(svr.fecha_atencion) as fecha_atencion,
					p.proyectoDesc,
					s.subProyectoDesc,
					vr_robot
                    FROM (planobra po, 
					     fase f, 
						 tipo_solicitud ts, 
						 solicitud_vale_reserva svr,
						 proyecto p,
						 subproyecto s)
                    LEFT JOIN 	jefatura_sap j 			ON j.idJefatura 	= svr.idJefaturaSap
                    LEFT JOIN  	empresacolab e 			ON e.idEmpresaColab = svr.idEmpresaColab
                    LEFT JOIN 	log_solicitud_vr lvr	ON lvr.idSolicitudValeReserva = svr.idSolicitudValeReserva
                    LEFT JOIN 	usuario	u				ON lvr.idUsuario = u.id_usuario
                    WHERE svr.itemplan = po.itemplan
					AND s.idSubProyecto 	= po.idSubProyecto
					AND	s.idProyecto		= p.idProyecto
                    AND po.idFase = f.idFase
                    AND ts.idTipoSolicitud = svr.flg_tipo_solicitud
                    AND po.itemplan         = COALESCE(?, po.itemplan)
                    AND e.idEmpresaColab    = COALESCE(?, e.idEmpresaColab)
                    AND po.idFase           = COALESCE(?, po.idFase)
                    AND j.idJefatura        = COALESCE(?, j.idJefatura)
                    AND ts.idTipoSolicitud  = COALESCE(?, ts.idTipoSolicitud)
                    -- GROUP BY svr.itemplan, svr.ptr, svr.idJefaturaSap, svr.idEmpresaColab, svr.fecha_registro, svr.idUsuario, svr.vr, svr.codigo
					GROUP BY svr.codigo) as tb
                    HAVING flgEstadoItemplan = COALESCE(?, flgEstadoItemplan)";
        $result = $this->db->query($sql, array($itemplan, $idEmpresaColab, $idFase, $idJefatura, $idTipoSolicitud, $tipoAtencion));
        //log_message('error', $this->db->last_query());
        return $result->result();
    }
    
    function getDetalleMaterialesVR($itemplan, $ptr, $codigo, $vr) {
        $sql = "	SELECT svr.*,(CASE  
                					WHEN svr.flg_estado = 1 THEN 'VALIDADO'
                					WHEN (svr.flg_estado IS NULL OR svr.flg_estado = 0) AND fecha_atencion IS NOT NULL THEN 'RECHAZADO'
                					ELSE 'PENDIENTE' 
                				END) AS estadoMaterial, UPPER(ts.descripcion) as tipoSolicitudDesc,
                                 (CASE WHEN svr.textoBreve IS NULL THEN m.descrip_material 
                						ELSE svr.textoBreve 
                				END) as textoBreveDesc,
                				CASE WHEN substring(svr.ptr,1,4) NOT IN ('2018') THEN svr.cantidadInicio
                				     ELSE svr.cantidadFin END AS cantidad,
                                ts.idTipoSolicitud,
								svr.send_rpa								
                	FROM tipo_solicitud ts, 
					      solicitud_vale_reserva svr  
			   LEFT JOIN material m ON svr.material = m.id_material 
                   WHERE svr.flg_tipo_solicitud = ts.idTipoSolicitud
                     AND svr.itemplan    		= ?
                	 AND svr.ptr         		= ?
                	 AND svr.codigo      		= ?
                	 AND svr.vr  like    '%".trim($vr)."%'   ";
        $result  = $this->db->query($sql, array($itemplan, $ptr, $codigo));
        return $result->result();
    }
    
    function getBandejaSolicitudVr2($itemplan, $idJefatura, $idEmpresaColab, $idTipoSolicitud, $ptr, $idFase = null) {
        $sql = "SELECT s.idSolicitudValeReserva,
                       s.itemplan, 
                       f.faseDesc,
                       s.ptr, 
                       j.descripcion as jefatura,
                       e.empresacolabDesc,
                       s.idJefaturaSap, 
                       s.idEmpresaColab, 
                       s.material, 
                       s.fecha_atencion,
                       CASE WHEN s.textoBreve IS NULL THEN m.descrip_material 
                            ELSE s.textoBreve END textoBreve, 
                       s.cantidadInicio, 
                       s.comentario,
                       s.cantidadFin,
                       s.fecha_registro,
                       s.flg_estado,
                       s.codigo,
                       s.vr,
                       CASE  WHEN s.flg_estado = 1 THEN 'VALIDADO'
                             WHEN (s.flg_estado IS NULL OR s.flg_estado = 0) AND fecha_atencion IS NOT NULL THEN 'RECHAZADO'
                             ELSE 'PENDIENTE' END AS estadoMaterial,
                       (SELECT GROUP_CONCAT(tt.descripcion) 
                          FROM( 
								 SELECT so.itemplan, ts.descripcion, so.ptr
								   FROM solicitud_vale_reserva so, 
										tipo_solicitud ts 
								   WHERE ts.idTipoSolicitud = so.flg_tipo_solicitud
								   GROUP BY so.itemplan, so.ptr, ts.idTipoSolicitud)tt
						 WHERE tt.itemplan = s.itemplan
						   AND tt.ptr      = s.ptr
						GROUP BY tt.itemplan, s.ptr)tipoSolicitudItemplan,
                        (SELECT CASE WHEN t.countPendientes > 0 AND (t.countValidados > 0 OR t.countRechazados > 0) THEN 1
                                     WHEN t.countPendientes > 0 THEN 2
									 WHEN t.countRechazados > 0 THEN 3 -- RECHAZADO
									 ELSE 4 END -- VALIDACION TOTAL
						  FROM(
                                SELECT SUM(CASE WHEN (comentario IS NULL OR comentario = '' ) AND (flg_estado IS NULL OR flg_estado = 0) THEN 1 ELSE 0 END) countPendientes,
                                       SUM(CASE WHEN flg_estado = 0 AND comentario IS NOT NULL AND comentario <> '' THEN 1 ELSE 0 END) countRechazados,
                                       SUM(CASE WHEN flg_estado = 1 THEN 1 ELSE 0 END) countValidados,
                                       itemplan,
                                       ptr
                                  FROM solicitud_vale_reserva
                                 WHERE material <> ''
                                    GROUP BY itemplan, ptr)t
                            WHERE t.itemplan = s.itemplan
                            AND t.ptr        = s.ptr) flgEstadoItemplan,
                            t.descripcion tipoSolicitud,
                        CASE WHEN fecha_atencion IS NULL THEN 
                                                              TIMEDIFF(ADDTIME(NOW(), '01:00:00'), fecha_registro)
                              ELSE  TIMEDIFF(fecha_atencion, fecha_registro) END tiempoAtencionSVr,
                        CASE WHEN fecha_atencion IS NOT NULL THEN
                                    TIMEDIFF(fecha_atencion, fecha_registro)
                             ELSE NULL END AS tiempoAtencion,
                        (SELECT GROUP_CONCAT(DISTINCT nombre) 
                           FROM log_solicitud_vr l, 
                                usuario u
                          WHERE l.idUsuario = u.id_usuario
                            AND l.idSolicitudValeReserva = s.idSolicitudValeReserva)nombreUsuario     
                  FROM (solicitud_vale_reserva s LEFT JOIN material m ON (s.material = m.id_material)),
                       empresacolab e,
                       jefatura_sap j,
                       tipo_solicitud t,
                       planobra po,
                       fase f
                 WHERE s.itemplan = COALESCE(?, s.itemplan)
                   AND s.idJefaturaSap   = j.idJefatura
                   AND e.idEmpresaColab  = s.idEmpresaColab
                   AND t.idTipoSolicitud = s.flg_tipo_solicitud
                   AND s.idEmpresaColab  = COALESCE(?, s.idEmpresaColab)
                   AND j.idJefatura      = COALESCE(?, j.idJefatura) 
                   AND t.idTipoSolicitud = COALESCE(?, t.idTipoSolicitud)
                   AND s.ptr             = COALESCE(?, s.ptr)
                   AND s.itemplan        = po.itemPlan
                   AND po.idFase         = f.idFase
                   AND po.idFase         = COALESCE(?, po.idFase)
                   AND t.flg_activo      = ".FLG_ACTIVO." 
                GROUP BY CASE WHEN  ? IS NULL THEN s.ptr 
                              ELSE s.idSolicitudValeReserva END
                 ORDER BY s.fecha_registro DESC";
        $result = $this->db->query($sql, array($itemplan , $idEmpresaColab, $idJefatura, $idTipoSolicitud, $ptr, $idFase, $itemplan));
        //log_message('error', $this->db->last_query());
        return $result->result();          
    }

    function ingresarDetallePo($arrayDetallePo, $arrayUpdateSolicitud, $updateDetallePo) {

        if(count($arrayDetallePo) > 0) {
            $this->db->insert_batch('planobra_po_detalle', $arrayDetallePo);

            if ($this->db->trans_status() === FALSE) {
                $data['msj']   = 'error no se valido';
                $data['error'] = EXIT_ERROR;
                return $data;
            }else{
                foreach($arrayUpdateSolicitud AS $row) {
                    if($row['flg_adicion'] == 1)  {
                        $this->db->where('itemplan' , $row['itemplan']);
                        $this->db->where('ptr'      , $row['po']);     
                        $this->db->where('material' , $row['id_material']);               
                        $this->db->update('solicitud_vale_reserva', array('flg_adicion' => 2));
                            
                        if ($this->db->trans_status() === FALSE) {
                            $data['error'] = EXIT_ERROR;
                            return $data;
                        } 
                    } 
                }
            }
        }  

        if(count($updateDetallePo) > 0) {
            foreach($updateDetallePo as $row) {
                $this->db->where('codigo_po'      , $row['codigo_po']);
                $this->db->where('codigo_material', $row['codigo_material']);
                $this->db->update('planobra_po_detalle', array('cantidad_final' => $row['cantidad_final']));

                if ($this->db->trans_status() === FALSE) {
                    $data['error'] = EXIT_ERROR;
                    return $data;
                 } 
            }
        }
        $data['error'] = EXIT_SUCCESS;
        return $data;
    }

    function getExistPo($idMaterial, $codigo_po) {
        $sql = "SELECT 1 flgExist
                  FROM planobra_po_detalle
                 WHERE codigo_material = ?
                   AND codigo_po       = ?";
        $result = $this->db->query($sql, array($idMaterial, $codigo_po));
        return $result->row_array()['flgExist'];           
    }

    function ingresarFlgDevolucion($dataUpdate, $arrayLogVr) {
        //$this->db->trans_begin();
        $this->db->update_batch('solicitud_vale_reserva', $dataUpdate, 'idSolicitudValeReserva');
        
        if($this->db->trans_status() === FALSE) {
            //$this->db->trans_rollback();
            $data['msj']   = 'error no se valido';
            $data['error'] = EXIT_ERROR;
			return $data;
        }else{
            $this->db->insert_batch('log_solicitud_vr', $arrayLogVr);
              
            if($this->db->trans_status() === FALSE) {
                    //$this->db->trans_rollback();
                    $data['msj']   = 'error no ingreso log';
                    $data['error'] = EXIT_ERROR;
                    return $data;
            }else{
                //$this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                return $data;
            }
        }
    }
    
    function getCodigoSolicitudVr() {
        $sql = "(SELECT CASE WHEN codigo IS NULL OR codigo = '' THEN CONCAT(YEAR(NOW()),(SELECT ROUND(RAND()*100000)))
                             ELSE MAX(codigo)+1 END AS codigo_vr
                   FROM solicitud_vale_reserva)";
        $result = $this->db->query($sql);
        return $result->row()->codigo_vr;
    }

    function getKitMaterialSolicitud($itemplan, $po, $idEstacion) {
        $sql = "  SELECT DISTINCT
                            ma.id_material,
                            ma.descrip_material,
                            t.codigo_po
                    FROM (kit_material k, 
                            material ma, 
                            planobra po) LEFT JOIN ( SELECT pod.codigo_po,
                                                            pod.codigo_material,
                                                            pod.cantidad_ingreso,
                                                            ppo.idEstacion
                                                    FROM planobra_po_detalle pod,
                                                            planobra_po ppo
                                                    WHERE pod.codigo_po = '".$po."'
                                                        AND ppo.codigo_po = pod.codigo_po)t ON (k.id_material = t.codigo_material)
                    WHERE ma.id_material = k.id_material
                    AND po.idSubProyecto = k.idSubProyecto
                    AND po.itemplan      = '".$itemplan."'
                    AND k.idEstacion     = '".$idEstacion."'
                    AND k.flg_solicitud IS NULL
                    AND t.codigo_po IS NULL";
        $result = $this->db->query($sql);
        return $result->result_array();                 
    }

    function updateFlgSolicitud($arrayData) {

        foreach($arrayData AS $row) {
            $this->db->where('id_material'  , $row['id_material']);
            $this->db->where('idSubProyecto', $row['idSubProyecto']);
            $this->db->where('idEstacion'   , $row['idEstacion']);

            $this->db->update('kit_material', array('flg_solicitud' => 1 ));
        }
        if($this->db->affected_rows() != 1) {
            return array('error' => EXIT_ERROR, 'msj' => 'error No se ingreso el material');
        }else{
            return array('error' => EXIT_SUCCESS);
        }
    }

    function getSubProyectoByItemplan($itemplan) {
        $sql = "SELECT idSubProyecto 
                  FROM planobra 
                 WHERE itemplan = '".$itemplan."'";
        $result = $this->db->query($sql);
        return $result->row_array()['idSubProyecto'];    
    }
    
    function updateTotalPo($po) {
        $sql = "UPDATE planobra_po ppo,
                       (SELECT pd.codigo_po, ROUND(SUM(ma.costo_material*pd.cantidad_final),2) total
                          FROM material ma, 
                               planobra_po_detalle pd 
                         WHERE pd.codigo_material = ma.id_material 
                        GROUP BY codigo_po)t
                   SET ppo.costo_total = t.total
                 WHERE t.codigo_po = ppo.codigo_po 
                   AND t.codigo_po = ?";
        $this->db->query($sql, array($po));    
 
        if($this->db->trans_status() === FALSE) {
            return array('error' => EXIT_ERROR, 'msj' => 'error no se actualiz&oacute; el total');
        }else{
            return array('error' => EXIT_SUCCESS);
        }           
    }

    function getCountFechaPoAprob($po) {
		$sql = "SELECT COUNT(1)count
				  FROM log_planobra_po 
				 WHERE idPoestado = 3
				   AND DATE(fecha_registro) >= '2020-01-01'
				   AND codigo_po = ?";
		$result = $this->db->query($sql, array($po));
		return $result->row_array()['count'];
	}
	
	function getCountPepBianual($pep) {
		$sql = "SELECT COUNT(1) count
				 FROM pep_bianual
				WHERE pep = ?
				  AND estado = 1";
        $result = $this->db->query($sql, array($pep));
		return $result->row_array()['count'];
	}

	function getPep1RpaByPo($po) {
		$sql = "SELECT pep1
				  FROM planobra_po
			     WHERE codigo_po = ?";
		$result = $this->db->query($sql, array($po));
		return $result->row_array()['pep1'];
	}
	
	function getPepGrafoByMatPtrSisegos($indicador, $costoTotalDev) {
		$sql = "SELECT getPepGrafoByMatPtrSisegos('".$indicador."', '".$costoTotalDev."') AS resp";
		$result = $this->db->query($sql);
		return $result->row_array()['resp'];
	}
	
	function getPepGrafoByMatPtr($codigo_po, $costoTotalDev) {
		$sql = "SELECT getPepGrafoByMatPtr('".$codigo_po."', '".$costoTotalDev."') AS resp";
		$result = $this->db->query($sql);
		return $result->row_array()['resp'];
	}
	
	function getCountPendienteValidVr($itemplan) {
        $sql = "SELECT COUNT(1) AS count 
                  FROM solicitud_vale_reserva
                 WHERE itemplan = ?
                   AND flg_estado IS NULL";
        $result = $this->db->query($sql, array($itemplan));
        return $result->row_array()['count'];           
    }
	
	function updateFlgRobot($codigoSolicitud, $material, $arrayData) {
        $this->db->where('codigo', $codigoSolicitud);
        $this->db->where('material', $material);
        $this->db->update('solicitud_vale_reserva', $arrayData);

        if($this->db->trans_status() === FALSE) {
            $data['msj']   = 'error se actualiza el material';
            $data['error'] = EXIT_ERROR;
            return $data;
        }else{
            $data['error'] = EXIT_SUCCESS;
            return $data;
        }
    }
	
	function getDataUpdateRobotDevolucion() {
        $sql = "SELECT * 
                  FROM solicitud_vale_reserva  svr, 
                       planobra_po po
                 WHERE svr.ptr = po.codigo_po
                   AND svr.send_rpa = 1
				   AND flg_estado IS NULL
                   GROUP BY  svr.codigo";
        $result = $this->db->query($sql);
        return $result->result_array();
    }

    function getSumTotalSolicitud($codigo) {
        $sql = "SELECT ROUND(SUM(m.costo_material*cantidadFin),2)total
                  FROM solicitud_vale_reserva s, 
                        material m
                 WHERE s.material = m.id_material
                   AND s.codigo = ?";
        $result = $this->db->query($sql, array($codigo));
        return $result->row_array()['total'];
    }

    function updateSolitudVrPep($codigo, $arrayData) {
        $this->db->where('codigo', $codigo);
        $this->db->update('solicitud_vale_reserva', $arrayData);
    }
	
	function countSolicitudEnviadoRobot($po, $codigo_mat) {
		$sql = "SELECT COUNT(1) countSolicitudEnviado
				  FROM solicitud_vale_reserva 
				 WHERE ptr = ?
				   AND send_rpa   = 1
				   AND flg_estado = 1
				   AND material   = ?";
		$result = $this->db->query($sql, array($po, $codigo_mat));
        return $result->row_array()['countSolicitudEnviado'];
	}
	
	function flg_valida_presupuesto($pep, $monto) {
		$sql = "SELECT COUNT(1)countValidPresupuesto
				  FROM sap_detalle
				 WHERE pep1 = ?
				   AND monto_temporal > ?";
		$result = $this->db->query($sql, array($pep, $monto));
        return $result->row_array()['countValidPresupuesto'];
	}
}