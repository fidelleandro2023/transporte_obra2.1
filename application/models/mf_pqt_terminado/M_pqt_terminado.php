<?php
class M_pqt_terminado extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
	function getEstacionesAnclasByItemplan($itemPlan) {
	    $Query = "SELECT DISTINCT
                    po.itemplan, ea.idEstacion, e.estacionDesc
                FROM
                    planobra po, subproyectoestacion se, estacionarea ea,
                    estacion e
                WHERE
                    po.idSubProyecto = se.idSubProyecto
                AND se.idEstacionArea = ea.idEstacionArea
                AND ea.idEstacion = e.idEstacion
                AND ea.idEstacion IN ('".ID_ESTACION_COAXIAL."','".ID_ESTACION_FO."')
                AND po.itemplan = ?";
	    $result = $this->db->query($Query, array($itemPlan));
	    return $result->result();
	}
		
	function getDataPartidaByCodigo($codigoPartida) {
	    $Query = "SELECT * from partidas where codigo = ? LIMIT 1";
	    $result = $this->db->query($Query, array($codigoPartida));
	   if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
	function getGroupFerreteria($itemplan, $idEstacion) {
	    $Query = "SELECT * FROM itemplan_material_x_estacion_pqt	 
	               WHERE itemplan = ? and idEstacion = ? LIMIT 1";
	    $result = $this->db->query($Query, array($itemplan, $idEstacion));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
	/*
	function getPartidasPaquetizadasByItemEccJefaTuraEstacion($idEstacion, $idSubProyecto, $idEmpresaColab, $isLima, $itemplan) {
	    $Query = "SELECT 
                    pqe.id_tipo_partida,
	                ptp.descripcion as tipoPreciario,
                    pqe.descripcion as partidaPqt,
                    pbs.baremo,
                    po.cantFactorPlanificado,
                    pre.costo,
                    (pbs.baremo * po.cantFactorPlanificado * pre.costo) AS total,
                    FORMAT((pbs.baremo * po.cantFactorPlanificado * pre.costo),2) as form,
                    ROUND((pbs.baremo * po.cantFactorPlanificado * pre.costo),2) as round,
	                pqe.idActividad
                FROM
                    planobra po,
                    pqt_baremo_x_subpro_x_partida_mo pbs,
                    pqt_partidas_paquetizadas_x_estacion pqe,
                    pqt_tipo_preciario ptp,
                    pqt_preciario pre
                WHERE
                    po.idSubProyecto    = pbs.idSubProyecto
                AND pbs.id_pqt_partida_mo_x_estacion    = pqe.id_tipo_partida
                AND pqe.id_pqt_tipo_preciario           = ptp.id
                AND ptp.id              = pre.idTipoPreciario
                AND pqe.idEstacion      = ?
                AND pbs.idSubProyecto   = ?
                AND pre.idEmpresaColab  = ?
                AND pre.tipoJefatura    = ?
                AND po.itemplan         = ?";
	    $result = $this->db->query($Query, array($idEstacion, $idSubProyecto, $idEmpresaColab, $isLima, $itemplan));
	    return $result->result();
	}
	*/
	
	function getPartidasPaquetizadasByItemEccJefaTuraEstacion($idEstacion, $idSubProyecto, $idEmpresaColab, $isLima, $itemplan) {
	    $Query = "SELECT 
                    tb.id_tipo_partida,
                    tb.tipoPreciario,
                    tb.partidaPqt,
                    tb.baremo,
                    tb.cantFactorPlanificado,
                    tb.costo,
                    (tb.baremo * tb.cantFactorPlanificado * tb.costo) AS total,
                                        FORMAT((tb.baremo * tb.cantFactorPlanificado * tb.costo),2) as form,
                                        ROUND((tb.baremo * tb.cantFactorPlanificado * tb.costo),2) as round,
                    tb.idActividad
                    FROM (
                    SELECT 
                        pqe.id_tipo_partida,
    	                ptp.descripcion as tipoPreciario,
                        pqe.descripcion as partidaPqt,
                        (CASE WHEN sp.idTipoSubProyecto = 1 THEN 
    						(CASE WHEN (po.cantFactorPlanificado <= 0  OR po.cantFactorPlanificado IS NULL) THEN 0
							      WHEN po.cantFactorPlanificado > 0  AND po.cantFactorPlanificado <= 10 THEN pbs.baremo  
    							  WHEN po.cantFactorPlanificado > 10 AND po.cantFactorPlanificado <= 25 THEN pbs.baremo_cv_11_25 
                                  WHEN po.cantFactorPlanificado > 25 AND  pqe.id_tipo_partida IN (3,4,7,8) THEN ((po.cantFactorPlanificado-25)*pbs.baremo_cv_dpto_adic+pbs.baremo_cv_11_25)  
    									ELSE pbs.baremo_cv_11_25 
                                  END) 
                        ELSE pbs.baremo END) as baremo,
                        (CASE WHEN sp.idTipoSubProyecto = 1 THEN 1 ELSE	po.cantFactorPlanificado END) as cantFactorPlanificado,
                        pre.costo,                 
    	                pqe.idActividad
                    FROM
                        planobra po,
	                    subproyecto sp,
                        pqt_baremo_x_subpro_x_partida_mo pbs,
                        pqt_partidas_paquetizadas_x_estacion pqe,
                        pqt_tipo_preciario ptp,
                        pqt_preciario pre
                    WHERE
                        po.idSubProyecto    = pbs.idSubProyecto
	                AND po.idSubProyecto	= sp.idSubProyecto
                    AND pbs.id_pqt_partida_mo_x_estacion    = pqe.id_tipo_partida
                    AND pqe.id_pqt_tipo_preciario           = ptp.id
                    AND ptp.id              = pre.idTipoPreciario
                    AND pqe.idEstacion      = ?
                    AND pbs.idSubProyecto   = ?
                    AND pre.idEmpresaColab  = ?
                    AND pre.tipoJefatura    = ?
                    AND po.itemplan         = ?
    	        ) as tb";
	    $result = $this->db->query($Query, array($idEstacion, $idSubProyecto, $idEmpresaColab, $isLima, $itemplan));
	    return $result->result();
	}
	
	function haveLiquidacionDiseno($itemplan, $idEstacion){
	    $Query = "SELECT   fecha_ejecucion, requiere_licencia, liquido_licencia
	               FROM    pre_diseno 
	               WHERE   itemplan = ? 
	               AND     idEstacion = ? 
	               AND     fecha_ejecucion is not null
	               LIMIT 1";
	    $result = $this->db->query($Query,array($itemplan, $idEstacion));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
	function haveEstaLiquidada($itemplan, $idEstacion){
	    $Query = "SELECT * from itemplanestacionavance 
        	        WHERE itemplan = ?
        	        AND idEstacion = ?
	               LIMIT 1";
	    $result = $this->db->query($Query,array($itemplan, $idEstacion));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
	function haveExpedienteValdiadoEstacion($itemplan, $idEstacion){
	    $Query = "SELECT 	count(1) as cant
                	FROM	itemplan_expediente
                	WHERE	itemplan = ?
                	AND 	idEstacion = ?
                	AND 	estado = 'ACTIVO'
                	AND 	estado_final = 'FINALIZADO'";
	    $result = $this->db->query($Query,array($itemplan, $idEstacion));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
	function getPartidasAdicionalesTmpByItemplanEstacion($itemplan, $idEstacion) {
	    $Query = "SELECT   pt.*, p.descripcion, p.codigo, p.idActividad, FORMAT(pt.total,2) as total_form
	               FROM    pqt_partidas_adicionales_detalle_tmp pt, partidas p 
	               WHERE   pt.idActividad = p.idActividad
                   AND     pt.itemplan = ? 
	               AND     pt.idEstacion = ?";
	    $result = $this->db->query($Query, array($itemplan, $idEstacion));
	    return $result->result();
	}
	
	function getPartidasAdicionalesTmpByItemplanEstacionv2($codigo_po) {
	    $Query = "SELECT   pt.*, p.descripcion, p.codigo, p.idActividad, FORMAT(pt.monto_final,2) as total_form
                	FROM    planobra_po_detalle_mo pt, partidas p
                	WHERE   pt.idActividad = p.idActividad
                	AND     pt.codigo_po  = ?
            	    AND    p.flg_tipo not in (3) ";
	    $result = $this->db->query($Query, array($codigo_po));
	    return $result->result();
	}
	
	
	function has_partidas_adicionales_pdt_aprob($itemplan, $idEstacion) {
	    $Query = "SELECT   count(1) as total, SUM(CASE WHEN estado = 1 THEN 1 ELSE 0 END) as cant_validados 
	               FROM    pqt_partidas_adicionales_detalle_tmp 
	               WHERE   itemplan    = ? 
	               AND     idEstacion  = ?";
	    $result = $this->db->query($Query, array($itemplan, $idEstacion));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
/*comentado por czavala ahora se utlizara el precio pqt
	function getInfoPartidaByCodPartida($itemplan, $idEstacion, $codigo_partida){
	    $Query = "SELECT 	p.idActividad, p.codigo, p.descripcion, pd.descPrecio, p.baremo, pr.costo
                	FROM 	planobra po,
                	subproyecto sp,
                	proyecto_estacion_partida_mo pep,
                	partidas p,
                	preciario pr,
                	precio_diseno pd
                	WHERE 	p.idPrecioDiseno = pd.idPrecioDiseno
                	AND		po.idSubProyecto 	= sp.idSubProyecto
                	AND 	sp.idProyecto 		= pep.idProyecto
                	AND 	pep.idPartida 		= p.idActividad
                	AND		p.idPrecioDiseno 	= pr.idPrecioDiseno
                	AND 	po.idEmpresaColab    = pr.idEmpresaColab
                	AND     po.idZonal			= pr.idzonal
                	and 	pr.idEstacion       = ?
                	AND 	p.estado 			= 1
                	AND 	p.flg_tipo 			= 2
                	AND 	po.itemplan 		= ?
            	    AND 	pep.idEstacion 		= ?
            	    AND     po.paquetizado_fg 	= 2
            	    AND 	p.codigo 		    = ?
	                LIMIT 1";
	    $result = $this->db->query($Query,array($idEstacion, $itemplan, $idEstacion, $codigo_partida));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}	
	*/
	
	function getInfoPartidaByCodPartida($itemplan, $idEstacion, $codigo_partida){
	    $Query = "SELECT 
					pa.idActividad,
					pa.codigo, pa.descripcion,  tp.descripcion as descPrecio, pa.baremo, pq.costo
				FROM
					partidas pa,
					pqt_tipo_preciario tp,
					pqt_preciario pq,
					planobra po,
					pqt_central c
				WHERE
					pa.idPrecioDiseno = tp.id
				AND tp.id = pq.idTipoPreciario
				AND pa.codigo = ?
				AND po.idEmpresaColab = pq.idEmpresaColab
				AND	po.idCentralPqt = c.idCentral
				AND po.itemplan = ?
				AND pq.tipoJefatura = (CASE WHEN c.jefatura = 'LIMA' THEN 1 ELSE 2 END)
	                LIMIT 1";
	    $result = $this->db->query($Query,array($codigo_partida, $itemplan));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}	
	
	function getEeccDisenoOperaByItemPlan($itemplan){
	    $Query = "SELECT   po.idEmpresaColabDiseno,
	                       c.idEmpresaColab,
						   c.idEmpresaColabFuente,
						   c.jefatura
	               FROM    planobra po,
	                       central c
	               WHERE   po.idCentral    = c.idCentral
	               AND     po.itemplan     = ?";
	    $result = $this->db->query($Query,array($itemplan));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
	function getEstatusEstacionItemplan($itemplan, $idEstacion){
	    $Query = "SELECT   tmp.*, ppo.estado_po
	               FROM    pqt_partidas_adicionales_tmp tmp, planobra_po ppo
	               WHERE   tmp.codigo_po = ppo.codigo_po
				   AND	   tmp.itemplan    = ?
	               AND     tmp.idEstacion  = ?
				   AND     ppo.estado_po not in (7,8)
	               LIMIT 1";
	    $result = $this->db->query($Query,array($itemplan, $idEstacion));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
    function getSubProyectoEstacionByItemplanEstacion($itemplan, $idEstacion){
	    $Query = "SELECT 	se.idSubProyectoEstacion 
                    FROM 	planobra po, subproyectoestacion se, estacionarea ea, area a
                    WHERE 	po.idSubProyecto   = se.idSubProyecto
                    AND		se.idEstacionArea  = ea.idEstacionArea
                    AND 	ea.idArea      = a.idArea
                    AND 	a.tipoArea     = 'MO'
                    AND 	ea.idEstacion  = ?
                    AND 	po.itemplan    = ? LIMIT 1";
	    $result = $this->db->query($Query,array($idEstacion, $itemplan));
	    if($result->row() != null) {
	        return $result->row_array()['idSubProyectoEstacion'];
	    } else {
	        return null;
	    }
	}
	
	function createPoMO($dataPO, $dataLogPO, $dataDetalleplan, $arrayFinalInsert, $tipoTmpPoCreate, $dataPqtTmp, $dataUpdateSolicitud) {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $this->db->where('itemplan', $dataUpdateSolicitud['itemplan']);
	        $this->db->where('idEstacion', $dataUpdateSolicitud['idEstacion']);
	        $this->db->where('estado', 1);//suponiendo que solo cuenta con una solicitud en ese itemplan estacion en estado 1
	        $this->db->update('pqt_solicitud_aprob_partidas_adicionales', $dataUpdateSolicitud);
	        if($this->db->trans_status() === FALSE) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al modificar a pqt_solicitud_aprob_partidas_adicionales');
	        }else{   
    	        $this->db->insert('planobra_po', $dataPO);
    	        if($this->db->affected_rows() != 1) {
    	            $this->db->trans_rollback();
    	            throw new Exception('Error al insertar en Planobra Po');
    	        }else{
    	            $this->db->insert_batch('log_planobra_po', $dataLogPO);
    	            if($this->db->affected_rows() === FALSE) {
    	                $this->db->trans_rollback();
    	                throw new Exception('Error al insertar en log_planobra_po');
    	            }else{
    	                $this->db->insert('detalleplan', $dataDetalleplan);
    	                if($this->db->affected_rows() != 1) {
    	                    $this->db->trans_rollback();
    	                    throw new Exception('Error al insertar en detalleplan');
    	                }else{
    	                    $this->db->insert_batch('planobra_po_detalle_mo', $arrayFinalInsert);
    	                    if ($this->db->trans_status() === FALSE) {
    	                        $this->db->trans_rollback();
    	                        throw new Exception('Hubo un error al insertar el planobra_po_detalle_mo.');
    	                    }else{
    	                        if($tipoTmpPoCreate == 1){//insert
    	                            $this->db->insert('pqt_partidas_adicionales_tmp', $dataPqtTmp);
    	                            if($this->db->affected_rows() != 1) {
    	                                $this->db->trans_rollback();
    	                                throw new Exception('Error al insertar en pqt_partidas_adicionales_tmp');
    	                            }else{
    	                                $data['error'] = EXIT_SUCCESS;
    	                                $data['msj'] = 'Se actualizo correctamente!';
    	                                $this->db->trans_commit();
    	                            }
    	                                
    	                        }else if($tipoTmpPoCreate == 2){//update
    	                            $this->db->where('id_pqt_partidas', $dataPqtTmp['id_pqt_partidas']);
    	                            $this->db->update('pqt_partidas_adicionales_tmp', $dataPqtTmp);
    	                            if($this->db->trans_status() === FALSE) {
    	                                $this->db->trans_rollback();
    	                                throw new Exception('Error al modificar el pqt_partidas_adicionales_tmp');
    	                            }else{
    	                                $data['error'] = EXIT_SUCCESS;
    	                                $data['msj'] = 'Se actualizo correctamente!';
    	                                $this->db->trans_commit();
    	                            }
    	                        }	                       
    	                    }
    	                }
    	            }
    	        }
	        }
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
	
	function sendValidarPartidasAdicionales($itemplan, $idEstacion, $materiales, $partidas, $dataSolValidacion, $expediente, $arrayPartidasInsert, $codigo_po) {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();	 
            $this->db->where('itemplan', $itemplan);
            $this->db->where('idEstacion', $idEstacion);
            $this->db->update('itemplan_material_x_estacion_pqt', $materiales);
            if($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                throw new Exception('Error al modificar el itemplan_material_x_estacion_pqt');
            }else{
                $this->db->where('itemplan', $itemplan);
                $this->db->where('idEstacion', $idEstacion);
                $this->db->update('pqt_partidas_adicionales_tmp', $partidas);
                if($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    throw new Exception('Error al modificar el pqt_partidas_adicionales_tmp');
                }else{
                    $this->db->where('itemplan',   $itemplan);
                    $this->db->where('idEstacion', $idEstacion);
                    $this->db->where('activo', 1);
                    $this->db->update('pqt_solicitud_aprob_partidas_adicionales', array('activo' => 0));
                    if($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();
                        throw new Exception('Error al desactivar solicitudes pendientes.');
                    }else{
                        $this->db->insert('pqt_solicitud_aprob_partidas_adicionales', $dataSolValidacion);
                        if($this->db->affected_rows() != 1) {
                            $this->db->trans_rollback();
                            throw new Exception('Error al insertar en pqt_solicitud_aprob_partidas_adicionales');
                        }else{
                            $this->db->insert('itemplan_expediente', $expediente);
                	        if($this->db->affected_rows() != 1) {
                	            $this->db->trans_rollback();
                	            throw new Exception('Error al insertar en itemplan_expediente');
                	        }else{
                	            $sql = "delete pdmo from planobra_po_detalle_mo pdmo JOIN partidas p ON pdmo.idActividad = p.idActividad AND p.flg_tipo = 3 where pdmo.codigo_po = ?";
                    	        $result = $this->db->query($sql, array($codigo_po));
                    	        if($this->db->trans_status() === FALSE) {
                    	            $this->db->trans_rollback();
                    	            throw new Exception('Error al modificar a pqt_solicitud_aprob_partidas_adicionales');
                    	        }else{
                    	            $this->db->insert_batch('planobra_po_detalle_mo', $arrayPartidasInsert);
                    	             if($this->db->trans_status() === FALSE) {
                    	                $this->db->trans_rollback();
                    	                throw new Exception('Error al insertar en planobra_po_detalle_mo');
                    	            }else{
                    	                $sql = "update planobra_po set costo_total = (select sum(monto_final) from planobra_po_detalle_mo where codigo_po = ?) where codigo_po = ?";
                    	                $result = $this->db->query($sql, array($codigo_po,$codigo_po));
                                        if($this->db->trans_status() === FALSE) {
                                            $this->db->trans_rollback();
                                            throw new Exception('Error al actualizar en planobra_po');
                                        }else{
                                            $data['error'] = EXIT_SUCCESS;
                                            $data['msj'] = 'Se actualizo correctamente!';
                                            $this->db->trans_commit();	                        
                                        }	                
                    	            }
                    	        }                                          
                	        }
                        }
                    }
                }
            }	          
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
	
	function getSolicitudPartidasAdicionales($itemplan, $idEstacion){
	    $Query = "SELECT   tmp.*, 
                            CONCAT(usu_1.nombres, ' ', usu_1.ape_paterno, ' ', usu_1.ape_materno) as usuario_nivel_1, 
                            CONCAT(usu_2.nombres, ' ', usu_2.ape_paterno, ' ', usu_2.ape_materno) as usuario_nivel_2 
	               FROM    pqt_solicitud_aprob_partidas_adicionales tmp
                   LEFT JOIN usuario usu_1 ON usu_1.id_usuario = tmp.usua_val_nivel_1
                   LEFT JOIN usuario usu_2 ON usu_1.id_usuario = tmp.usua_val_nivel_2
	               WHERE   tmp.itemplan    = ?
	               AND     tmp.idEstacion  = ?
	               AND     tmp.activo = 1
	               LIMIT 1";
	    $result = $this->db->query($Query,array($itemplan, $idEstacion));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
	function getPoOCByItemplan($itemplan, $idEstacion) {#utilizado en c_terminadov2
	    $Query = "SELECT
                	   e.estacionDesc, (CASE WHEN ppo.flg_tipo_area = 1 THEN 'MAT'
											 WHEN ppo.flg_tipo_area = 2 THEN 'MO' END) as tipoPo,
                                             ppo.codigo_po, ep.estado, ppo.costo_total, ppo.estado_po
            	   FROM
                	   planobra_po ppo, po_estado ep, estacion e
            	   WHERE
            	       ppo.itemplan = ?
                	AND	ppo.estado_po = ep.idPoEstado
	                AND ppo.idEstacion = e.idEstacion
                	AND ppo.idEstacion = ?
                	AND ppo.flg_tipo_area = 2
                	AND ppo.estado_po NOT IN (7,8)";
	    $result = $this->db->query($Query, array($itemplan, $idEstacion));
	    log_message('error', $this->db->last_query());
	    return $result->result();
	}	
	
	function getInfoBasicToGeneratePartidasByItemplan($itemPlan) {
	    $query = "SELECT po.idEmpresaColab, po.idSubProyecto,
                	(CASE WHEN c.jefatura = 'LIMA' THEN 1 ELSE 2 END) AS isLima
                	FROM planobra po, pqt_central c where po.idCentralPqt = c.idCentral AND
                	po.itemplan = ? ";
	    $result = $this->db->query($query, array($itemPlan));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
	/**nuevo version bandeja validaciones**/
	
	function getPoOCByItemplanPqtV2FO($itemplan) {
	    $Query = "SELECT
                	   e.estacionDesc, (CASE WHEN ppo.flg_tipo_area = 1 THEN 'MAT'
											 WHEN ppo.flg_tipo_area = 2 THEN 'MO' END) as tipoPo,
                                             ppo.codigo_po, ep.estado, ppo.costo_total, ppo.estado_po
            	   FROM
                	   planobra_po ppo, po_estado ep, estacion e
            	   WHERE
            	        ppo.itemplan = ?
                    AND ppo.idEstacion = e.idEstacion	
	                AND	ppo.estado_po = ep.idPoEstado
                	AND ppo.idEstacion IN (5,6)
                	#AND ppo.flg_tipo_area = 2
                	AND ppo.estado_po NOT IN (7,8)";
	    $result = $this->db->query($Query, array($itemplan));
	    log_message('error', $this->db->last_query());
	    return $result->result();
	}
	
	function getPoOCByItemplanPqtV2Coax($itemplan) {
	    $Query = "SELECT
                	  e.estacionDesc, (CASE WHEN ppo.flg_tipo_area = 1 THEN 'MAT'
											 WHEN ppo.flg_tipo_area = 2 THEN 'MO' END) as tipoPo,
                                             ppo.codigo_po, ep.estado, ppo.costo_total, ppo.estado_po
            	   FROM
                	   planobra_po ppo, po_estado ep
            	   WHERE
            	        ppo.itemplan = ?
                	AND ppo.idEstacion = e.idEstacion
	                AND	ppo.estado_po = ep.idPoEstado
                	AND ppo.idEstacion in (2,3,4,7)
                	#AND ppo.flg_tipo_area = 2
                	AND ppo.estado_po NOT IN (7,8)";
	    $result = $this->db->query($Query, array($itemplan));
	    log_message('error', $this->db->last_query());
	    return $result->result();
	}
	
	function updatePartidasPoPqt($arrayFinalInsert, $codigo_po) {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $sql = "delete pdmo from planobra_po_detalle_mo pdmo JOIN partidas p ON pdmo.idActividad = p.idActividad AND p.flg_tipo = 3 where pdmo.codigo_po = ?";
	        $result = $this->db->query($sql, array($codigo_po));
	        if($this->db->trans_status() === FALSE) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al modificar a pqt_solicitud_aprob_partidas_adicionales');
	        }else{
	            $this->db->insert('planobra_po_detalle_mo', $arrayFinalInsert);
	             if($this->db->trans_status() === FALSE) {
	                $this->db->trans_rollback();
	                throw new Exception('Error al insertar en planobra_po_detalle_mo');
	            }else{
	                $sql = "update planobra_po set costo_total = (select sum(monto_final) from planobra_po_detalle_mo where codigo_po = ?) where codigo_po = ?";
	                $result = $this->db->query($sql, array($codigo_po,$codigo_po));
                    if($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();
                        throw new Exception('Error al actualizar en planobra_po');
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
	
	function getDataToSolicitudEdicionCertiOC($itemplan){
	    $Query = "SELECT po.itemplan, po.costo_unitario_mat, po.costo_unitario_mo, tb.total
                    FROM planobra po    LEFT JOIN (SELECT ppo.itemplan, SUM(costo_total) AS total FROM planobra_po ppo
                    WHERE ppo.itemplan = ?
                        AND ppo.estado_po IN (4,5)
                        AND ppo.flg_tipo_area = 2
                        GROUP BY ppo.itemplan) AS tb
                        ON po.itemplan = tb.itemplan
                        WHERE po.itemplan = ?
                            LIMIT 1;";
	    $result = $this->db->query($Query,array($itemplan, $itemplan));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
	function getInfoPoPQT($itemplan, $idEstacion){
	    $Query = "SELECT * FROM planobra_po 
	               WHERE   itemplan    = ? 
	               AND     idEstacion  = ?
	               AND     isPoPqt     = 1 
	               AND     estado_po   = ".PO_LIQUIDADO."
                   LIMIT 1;";
	    $result = $this->db->query($Query,array($itemplan, $idEstacion));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
	function getNextCodigoSolicitud() {
	    $Query = "SELECT getNextCodigoSolicitudOC() as codigo_solicitud";
	    $result = $this->db->query($Query, array());
	    if ($result->row() != null) {
	        return $result->row_array()['codigo_solicitud'];
	    } else {
	        return null;
	    }
	}
	
	function getInfoSolCreacionByItem($itemplan){
	    $Query = "SELECT
                	soc.*, po.itemplan, po.idEstadoPlan, po.costo_unitario_mo, po.posicion
                	FROM
                	solicitud_orden_compra soc,
                	planobra po
                	WHERE
                	soc.codigo_solicitud = po.solicitud_oc
                	AND po.itemplan = ?
                	LIMIT 1";
	    $result = $this->db->query($Query,array($itemplan));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
	function validarEstacionFOPqt2Nivel($arraySolicitud, $arrayItemXSolicitud, $dataItemplan, $itemplan, $arrayPoInserLogPo, $arrayPoUpdate, $codigo_po, $idEstacion, $dataSolicitud, $dataExpediente){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $this->db->insert_batch('solicitud_orden_compra', $arraySolicitud);
	        if($this->db->affected_rows() == 0) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar en solicitud_orden_compra');
	        }else{
	            $this->db->insert_batch('itemplan_x_solicitud_oc', $arrayItemXSolicitud);
	            if($this->db->affected_rows() == 0) {
	                $this->db->trans_rollback();
	                throw new Exception('Error al insertar en itemplan_x_solicitud_oc');
	            }else{
    	            $this->db->where('itemplan', $itemplan);
    	            $this->db->update('planobra', $dataItemplan);	
    	            if($this->db->trans_status() === FALSE) {
    	                $this->db->trans_rollback();
    	                throw new Exception('Error al modificar el planobra');
    	            }else{
        	            $this->db->insert_batch('log_planobra_po', $arrayPoInserLogPo);
            	        if($this->db->trans_status() === FALSE) {
            	            $this->db->trans_rollback();
            	            throw new Exception('Error al insertar en log_planobra_po');
            	        }else{
            	            $this->db->update_batch('planobra_po', $arrayPoUpdate, 'codigo_po');            	             
            	            if($this->db->trans_status() === FALSE) {
            	                $this->db->trans_rollback();
            	                throw new Exception('Error al modificar el updateEstadoPlanObra');
            	            }else{
            	                $this->db->where('itemplan', $itemplan);
            	                $this->db->where('idEstacion', $idEstacion);
            	                $this->db->where('estado', 1);//suponiendo que solo cuenta con una solicitud en ese itemplan estacion en estado 1
            	                $this->db->update('pqt_solicitud_aprob_partidas_adicionales', $dataSolicitud);
            	                if($this->db->trans_status() === FALSE) {
            	                    $this->db->trans_rollback();
            	                    throw new Exception('Error al modificar a pqt_solicitud_aprob_partidas_adicionales');
            	                }else{ 
            	                    $this->db->where('codigo_po', $codigo_po);
                    	            $this->db->where('itemplan', $itemplan);
                    	            $this->db->update('pqt_partidas_adicionales_tmp', array('estado' => 4));                	             
                    	            if($this->db->trans_status() === FALSE) {
                    	                $this->db->trans_rollback();
                    	                throw new Exception('Error al modificar a pqt_partidas_adicionales_tmp');
            	                   }else{
                	                    $this->db->where('idEstacion', $idEstacion);
                        	            $this->db->where('itemplan', $itemplan);
                        	            $this->db->update('itemplan_material_x_estacion_pqt', array('estado' => 4));                	             
                        	            if($this->db->trans_status() === FALSE) {
                        	                $this->db->trans_rollback();
                        	                throw new Exception('Error al modificar a pqt_partidas_adicionales_tmp');
                	                   }else{
                	                        $this->db->where('idEstacion', $idEstacion);
                            	            $this->db->where('itemplan', $itemplan);
                            	            $this->db->update('itemplan_expediente', $dataExpediente);                	             
                            	            if($this->db->trans_status() === FALSE) {
                            	                $this->db->trans_rollback();
                            	                throw new Exception('Error al modificar a pqt_partidas_adicionales_tmp');
                    	                   }else{
                    	                       $data['error'] = EXIT_SUCCESS;
                    	                       $data['msj'] = 'Se actualizo correctamente!';
                    	                       $this->db->trans_commit();
                    	                   }
                	                   }
            	                   }
            	                }
                            }	            
            	        }
    	            }
	            }
	        }
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
	
	function hasPoDisenoActivo($itemplan){
	    $Query = "SELECT
                	count(1) as has_po_diseno
                	FROM
                	planobra_po ppo, detalleplan dp, subproyectoestacion se, estacionarea ea, area a
                	WHERE
                	ppo.itemplan = dp.itemplan
                	AND ppo.codigo_po = dp.poCod
                	AND	dp.idSubProyectoEstacion = se.idSubProyectoEstacion
                	AND se.idEstacionArea = ea.idEstacionArea
                	AND	ea.idArea = a.idArea
                	AND a.idArea in (1,2,17,40)
                	AND ppo.itemplan = ?
                	AND ppo.estado_po not in (7,8)
                	AND ppo.idEstacion = 1";
	    $result = $this->db->query($Query,array($itemplan));
	    if($result->row() != null) {
	        return $result->row_array()['has_po_diseno'];
	    } else {
	        return null;
	    }
	}
	
	
	function hasPoLicenciaActivo($itemplan){
	    $Query = "SELECT
                	count(1) as has_po_diseno
                	FROM
                	planobra_po ppo, detalleplan dp, subproyectoestacion se, estacionarea ea, area a
                	WHERE
                	ppo.itemplan = dp.itemplan
                	AND ppo.codigo_po = dp.poCod
                	AND	dp.idSubProyectoEstacion = se.idSubProyectoEstacion
                	AND se.idEstacionArea = ea.idEstacionArea
                	AND	ea.idArea = a.idArea
                	AND a.idArea in (41,42,43,44)
                	AND ppo.itemplan = ?
                	AND ppo.estado_po not in (7,8)
                	AND ppo.idEstacion = 20";
	    $result = $this->db->query($Query,array($itemplan));
	    if($result->row() != null) {
	        return $result->row_array()['has_po_diseno'];
	    } else {
	        return null;
	    }
	}
	
	/**nuevo rutas**/
	function getAllPoMoByItemplan($itemplan, $idEstacion){
	    $Query = "SELECT
                	   e.estacionDesc, (CASE WHEN ppo.flg_tipo_area = 1 THEN 'MAT'
											 WHEN ppo.flg_tipo_area = 2 THEN 'MO' END) as tipoPo,
                                             ppo.codigo_po, ep.estado, ppo.costo_total, ppo.estado_po
            	   FROM
                	   planobra_po ppo, po_estado ep, estacion e
            	   WHERE
            	       ppo.itemplan = ?
                	AND	ppo.estado_po = ep.idPoEstado
	                AND ppo.idEstacion = e.idEstacion            
	               AND ppo.idEstacion = ?    	
                	AND ppo.flg_tipo_area = 2
                	AND ppo.estado_po NOT IN (7,8)";
	    $result = $this->db->query($Query, array($itemplan, $idEstacion));
	    log_message('error', $this->db->last_query());
	    return $result->result();
	}
	
	function sendValidarRutas($dataSolValidacion, $expediente){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();	       
            $this->db->insert('pqt_solicitud_aprob_partidas_adicionales', $dataSolValidacion);
            if($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar en pqt_solicitud_aprob_partidas_adicionales');
            }else{
                $this->db->insert('itemplan_expediente', $expediente);
                if($this->db->affected_rows() != 1) {
                    $this->db->trans_rollback();
                    throw new Exception('Error al insertar en itemplan_expediente');
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
	
	function getEstacionesAnclasRutas($itemPlan) {
	    $Query = "SELECT DISTINCT
                    po.itemplan, ea.idEstacion, e.estacionDesc
                FROM
                    planobra po, subproyectoestacion se, estacionarea ea,
                    estacion e
                WHERE
                    po.idSubProyecto = se.idSubProyecto
                AND se.idEstacionArea = ea.idEstacionArea
                AND ea.idEstacion = e.idEstacion
                AND ea.idEstacion IN ('19','23')
                AND po.itemplan = ?";
	    $result = $this->db->query($Query, array($itemPlan));
	    return $result->result();
	}
	
	function haveSolPdtValidacion($itemplan, $idEstacion){
	    $Query = "SELECT 
                    COUNT(1) as cant
                FROM
                    pqt_solicitud_aprob_partidas_adicionales
                WHERE
                    itemplan    = ?
                AND idEstacion  = ?
                AND estado IN (0 , 1)";
	    $result = $this->db->query($Query,array($itemplan, $idEstacion));
	    if($result->row() != null) {
	        return $result->row_array()['cant'];
	    } else {
	        return null;
	    }
	}
	
	function getPOToValidateToFOByItemplan($itemPlan) {
	    $Query = "SELECT * FROM	planobra_po
	               WHERE   itemplan = ?
            	    AND    idEstacion IN (5 , 6)
            	    AND    estado_po = 4";
	    $result = $this->db->query($Query, array($itemPlan));
	    return $result->result();
	}
	
	function getPOToValidateToCOAXByItemplan($itemPlan) {
	    $Query = "SELECT * FROM	planobra_po
	               WHERE   itemplan = ?
            	    AND    idEstacion IN (2,3,4,7)
            	    AND    estado_po = 4";
	    $result = $this->db->query($Query, array($itemPlan));
	    return $result->result();
	}
	function getPOToValidateToItemplanRuta($itemPlan, $idEstacion) {
	    $Query = "SELECT * FROM	planobra_po
	               WHERE   itemplan = ?
            	    AND    idEstacion = ?
            	    AND    estado_po = 4";
	    $result = $this->db->query($Query, array($itemPlan, $idEstacion));
	    return $result->result();
	}
	
	function validarEstacionFOPqt2NivelRutas($arraySolicitud, $arrayItemXSolicitud, $dataItemplan, $itemplan, $arrayPoInserLogPo, $arrayPoUpdate, $idEstacion, $dataSolicitud, $dataExpediente){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $this->db->insert_batch('solicitud_orden_compra', $arraySolicitud);
	        if($this->db->affected_rows() == 0) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar en solicitud_orden_compra');
	        }else{
	            $this->db->insert_batch('itemplan_x_solicitud_oc', $arrayItemXSolicitud);
	            if($this->db->affected_rows() == 0) {
	                $this->db->trans_rollback();
	                throw new Exception('Error al insertar en itemplan_x_solicitud_oc');
	            }else{
	                $this->db->where('itemplan', $itemplan);
	                $this->db->update('planobra', $dataItemplan);
	                if($this->db->trans_status() === FALSE) {
	                    $this->db->trans_rollback();
	                    throw new Exception('Error al modificar el planobra');
	                }else{
	                    $this->db->insert_batch('log_planobra_po', $arrayPoInserLogPo);
	                    if($this->db->trans_status() === FALSE) {
	                        $this->db->trans_rollback();
	                        throw new Exception('Error al insertar en log_planobra_po');
	                    }else{
	                        $this->db->update_batch('planobra_po', $arrayPoUpdate, 'codigo_po');
	                        if($this->db->trans_status() === FALSE) {
	                            $this->db->trans_rollback();
	                            throw new Exception('Error al modificar el updateEstadoPlanObra');
	                        }else{
	                            $this->db->where('itemplan', $itemplan);
	                            $this->db->where('idEstacion', $idEstacion);
	                            $this->db->where('estado', 1);//suponiendo que solo cuenta con una solicitud en ese itemplan estacion en estado 1
	                            $this->db->update('pqt_solicitud_aprob_partidas_adicionales', $dataSolicitud);
	                            if($this->db->trans_status() === FALSE) {
	                                $this->db->trans_rollback();
	                                throw new Exception('Error al modificar a pqt_solicitud_aprob_partidas_adicionales');
	                            }else{	                               
        	                       $this->db->where('idEstacion', $idEstacion);
        	                       $this->db->where('itemplan', $itemplan);
        	                       $this->db->update('itemplan_expediente', $dataExpediente);
        	                       if($this->db->trans_status() === FALSE) {
        	                           $this->db->trans_rollback();
        	                           throw new Exception('Error al modificar a pqt_partidas_adicionales_tmp');
            	                   }else{
            	                       $data['error'] = EXIT_SUCCESS;
            	                       $data['msj'] = 'Se actualizo correctamente!';
            	                       $this->db->trans_commit();
            	                   }
        	                   }
	            	                  
	                        }
	                    }
	                }
	            }
	        }
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
	
	function validarEstacionFOPqt2NivelsINoc($arrayPoInserLogPo, $itemplan, $arrayPoUpdate, $codigo_po, $idEstacion, $dataSolicitud, $dataExpediente){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();	      
	        $this->db->insert_batch('log_planobra_po', $arrayPoInserLogPo);
	        if($this->db->trans_status() === FALSE) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar en log_planobra_po');
	        }else{  
                $this->db->update_batch('planobra_po', $arrayPoUpdate, 'codigo_po');
                if($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    throw new Exception('Error al modificar el updateEstadoPlanObra');
                }else{
                    $this->db->where('itemplan', $itemplan);
                    $this->db->where('idEstacion', $idEstacion);
                    $this->db->where('estado', 1);//suponiendo que solo cuenta con una solicitud en ese itemplan estacion en estado 1
                    $this->db->update('pqt_solicitud_aprob_partidas_adicionales', $dataSolicitud);
                    if($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();
                        throw new Exception('Error al modificar a pqt_solicitud_aprob_partidas_adicionales');
                    }else{
                        $this->db->where('codigo_po', $codigo_po);
                        $this->db->where('itemplan', $itemplan);
                        $this->db->update('pqt_partidas_adicionales_tmp', array('estado' => 4));
                        if($this->db->trans_status() === FALSE) {
                            $this->db->trans_rollback();
                            throw new Exception('Error al modificar a pqt_partidas_adicionales_tmp');
    	                   }else{
    	                       $this->db->where('idEstacion', $idEstacion);
    	                       $this->db->where('itemplan', $itemplan);
    	                       $this->db->update('itemplan_material_x_estacion_pqt', array('estado' => 4));
    	                       if($this->db->trans_status() === FALSE) {
    	                           $this->db->trans_rollback();
    	                           throw new Exception('Error al modificar a pqt_partidas_adicionales_tmp');
        	                   }else{
        	                       $this->db->where('idEstacion', $idEstacion);
        	                       $this->db->where('itemplan', $itemplan);
        	                       $this->db->update('itemplan_expediente', $dataExpediente);
        	                       if($this->db->trans_status() === FALSE) {
        	                           $this->db->trans_rollback();
        	                           throw new Exception('Error al modificar a pqt_partidas_adicionales_tmp');
            	                   }else{
            	                       $data['error'] = EXIT_SUCCESS;
            	                       $data['msj'] = 'Se actualizo correctamente!';
            	                       $this->db->trans_commit();
            	                   }
        	                   }
    	                   }
                    }
    	        }
	        }
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
	
	function validarEstacionFOPqt2NivelRutasSinOc($itemplan, $arrayPoInserLogPo, $arrayPoUpdate, $idEstacion, $dataUpdateSolicitud, $dataExpediente){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();	        
            $this->db->insert_batch('log_planobra_po', $arrayPoInserLogPo);
            if($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar en log_planobra_po');
            }else{
                $this->db->update_batch('planobra_po', $arrayPoUpdate, 'codigo_po');
                if($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    throw new Exception('Error al modificar el updateEstadoPlanObra');
                }else{
                    $this->db->where('itemplan', $itemplan);
                    $this->db->where('idEstacion', $idEstacion);
                    $this->db->where('estado', 1);//suponiendo que solo cuenta con una solicitud en ese itemplan estacion en estado 1
                    $this->db->update('pqt_solicitud_aprob_partidas_adicionales', $dataUpdateSolicitud);
                    if($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();
                        throw new Exception('Error al modificar a pqt_solicitud_aprob_partidas_adicionales');
                    }else{
	                       $this->db->where('idEstacion', $idEstacion);
	                       $this->db->where('itemplan', $itemplan);
	                       $this->db->update('itemplan_expediente', $dataExpediente);
	                       if($this->db->trans_status() === FALSE) {
	                           $this->db->trans_rollback();
	                           throw new Exception('Error al modificar a pqt_partidas_adicionales_tmp');
    	                   }else{
    	                       $data['error'] = EXIT_SUCCESS;
    	                       $data['msj'] = 'Se actualizo correctamente!';
    	                       $this->db->trans_commit();
    	                   }
	                   }
                }
            }	               
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
	
	function getEstacionHasPoToExpediente($itemPlan) {
	    $Query = "SELECT DISTINCT
                    po.itemplan, ea.idEstacion, e.estacionDesc
                FROM
                    planobra po, subproyectoestacion se, estacionarea ea,
                    estacion e, planobra_po ppo
                WHERE
                    po.idSubProyecto = se.idSubProyecto
                AND se.idEstacionArea = ea.idEstacionArea
                AND po.itemplan = ppo.itemplan
                AND ppo.estado_po not in (7,8)
                AND ea.idEstacion = e.idEstacion
                AND e.idEstacion = ppo.idEstacion
                AND po.itemplan = ?";
	    $result = $this->db->query($Query, array($itemPlan));
	    return $result->result();
	}
	
	function getAllPoMoBySoloItemplan($itemplan){
	    $Query = "SELECT
                	   e.estacionDesc, (CASE WHEN ppo.flg_tipo_area = 1 THEN 'MAT'
											 WHEN ppo.flg_tipo_area = 2 THEN 'MO' END) as tipoPo,
                                             ppo.codigo_po, ep.estado, ppo.costo_total, ppo.estado_po
            	   FROM
                	   planobra_po ppo, po_estado ep, estacion e
            	   WHERE
            	       ppo.itemplan = ?
                	AND	ppo.estado_po = ep.idPoEstado
	                AND ppo.idEstacion = e.idEstacion            
                	AND ppo.flg_tipo_area = 2
                	AND ppo.estado_po NOT IN (7,8)";
	    $result = $this->db->query($Query, array($itemplan));
	    return $result->result();
	}
	
	function haveSolPdtValidacionByObra($itemplan){
	    $Query = "SELECT
                    COUNT(1) as cant
                FROM
                    pqt_solicitud_aprob_partidas_adicionales
                WHERE
                    itemplan    = ?
                AND estado IN (0 , 1)";
	    $result = $this->db->query($Query,array($itemplan));
	    if($result->row() != null) {
	        return $result->row_array()['cant'];
	    } else {
	        return null;
	    }
	}
	
	function haveSolAprobadaByObra($itemplan){
	    $Query = "SELECT
                    COUNT(1) as cant
                FROM
                    pqt_solicitud_aprob_partidas_adicionales
                WHERE
                    itemplan    = ?
                AND estado IN (2)";
	    $result = $this->db->query($Query,array($itemplan));
	    if($result->row() != null) {
	        return $result->row_array()['cant'];
	    } else {
	        return null;
	    }
	}
	
	function getSolicitudPartidasAdicionalesByItemplanSolo($idSolicitud){
	    $Query = "SELECT   tmp.*,
                            CONCAT(usu_1.nombres, ' ', usu_1.ape_paterno, ' ', usu_1.ape_materno) as usuario_nivel_1,
                            CONCAT(usu_2.nombres, ' ', usu_2.ape_paterno, ' ', usu_2.ape_materno) as usuario_nivel_2
	               FROM    pqt_solicitud_aprob_partidas_adicionales tmp
                   LEFT JOIN usuario usu_1 ON usu_1.id_usuario = tmp.usua_val_nivel_1
                   LEFT JOIN usuario usu_2 ON usu_1.id_usuario = tmp.usua_val_nivel_2
	               WHERE   tmp.id_solicitud    = ?
	               AND     tmp.activo = 1
	               LIMIT 1";
	    $result = $this->db->query($Query,array($idSolicitud));
	    log_message('error', $this->db->last_query());
	     
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	/*czavala se reemplaza solo por id_solicitud
	function getSolicitudPartidasAdicionalesByItemplanSolo($itemplan){
	    $Query = "SELECT   tmp.*,
                            CONCAT(usu_1.nombres, ' ', usu_1.ape_paterno, ' ', usu_1.ape_materno) as usuario_nivel_1,
                            CONCAT(usu_2.nombres, ' ', usu_2.ape_paterno, ' ', usu_2.ape_materno) as usuario_nivel_2
	               FROM    pqt_solicitud_aprob_partidas_adicionales tmp
                   LEFT JOIN usuario usu_1 ON usu_1.id_usuario = tmp.usua_val_nivel_1
                   LEFT JOIN usuario usu_2 ON usu_1.id_usuario = tmp.usua_val_nivel_2
	               WHERE   tmp.itemplan    = ?
	               AND     tmp.activo = 1
	               LIMIT 1";
	    $result = $this->db->query($Query,array($itemplan));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	*/
	function getInfoSolCreacionByItemOPEX($itemplan){
	    $Query = "SELECT soc.*, po.itemplan, po.idEstadoPlan, po.idSubProyecto 
	               FROM planobra po, itemplan_solicitud_orden_compra soc 
	               WHERE po.solicitud_oc = soc.codigo_solicitud
	               AND po.itemplan =   ?
	               AND soc.tipo_solicitud = 1 
	               AND soc.estado = 2
                   LIMIT 1";
	    $result = $this->db->query($Query,array($itemplan));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
		

	function getPOToValidateToItemplanNoPqt($itemPlan) {
	    $Query = "SELECT * FROM	planobra_po
	               WHERE   itemplan = ?
            	    AND    estado_po = 4";
	    $result = $this->db->query($Query, array($itemPlan));
	    return $result->result();
	}
	
	function validarEstacionFOPqt2NivelNopqtOpex($arraySolicitud, $arrayItemXSolicitud, $dataItemplan, $itemplan, $arrayPoInserLogPo, $arrayPoUpdate, $idEstacion, $dataSolicitud, $dataExpediente){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	       $this->db->trans_begin();
	        $this->db->insert_batch('itemplan_solicitud_orden_compra', $arraySolicitud);
	        if($this->db->affected_rows() == 0) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar en solicitud_orden_compra');
	        }else{
	            $this->db->insert_batch('itemplan_x_solicitud_oc', $arrayItemXSolicitud);
	            if($this->db->affected_rows() == 0) {
	                $this->db->trans_rollback();
	                throw new Exception('Error al insertar en itemplan_x_solicitud_oc');
	            }else{
	                $this->db->where('itemplan', $itemplan);
	                $this->db->update('planobra', $dataItemplan);
	                if($this->db->trans_status() === FALSE) {
	                    $this->db->trans_rollback();
	                    throw new Exception('Error al modificar el planobra');
	                }else{
	                    $this->db->insert_batch('log_planobra_po', $arrayPoInserLogPo);
	                    if($this->db->trans_status() === FALSE) {
	                        $this->db->trans_rollback();
	                        throw new Exception('Error al insertar en log_planobra_po');
	                    }else{
	                        $this->db->update_batch('planobra_po', $arrayPoUpdate, 'codigo_po');
	                        if($this->db->trans_status() === FALSE) {
	                            $this->db->trans_rollback();
	                            throw new Exception('Error al modificar el updateEstadoPlanObra');
	                        }else{
	                            $this->db->where('itemplan', $itemplan);
	                            //$this->db->where('idEstacion', null);
	                            $this->db->where('estado', 1);//suponiendo que solo cuenta con una solicitud en ese itemplan estacion en estado 1
	                            $this->db->update('pqt_solicitud_aprob_partidas_adicionales', $dataSolicitud);
	                            log_message('error', $this->db->last_query());
	                            if($this->db->trans_status() === FALSE) {
	                                $this->db->trans_rollback();
	                                throw new Exception('Error al modificar a pqt_solicitud_aprob_partidas_adicionales');
	                            }else{	                               
        	                       //$this->db->where('idEstacion', $idEstacion);
        	                       $this->db->where('itemplan', $itemplan);
        	                       $this->db->update('itemplan_expediente', $dataExpediente);
        	                       if($this->db->trans_status() === FALSE) {
        	                           $this->db->trans_rollback();
        	                           throw new Exception('Error al modificar a pqt_partidas_adicionales_tmp');
            	                   }else{
            	                       $data['error'] = EXIT_SUCCESS;
            	                       $data['msj'] = 'Se actualizo correctamente!';
            	                       $this->db->trans_commit();
            	                   }
        	                   }
	            	                  
	                        }
	                    }
	                }
	            }
	        }
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
	
	function validarEstacionFOPqt2NivelNoPqtCapex($arraySolicitud, $arrayItemXSolicitud, $dataItemplan, $itemplan, $arrayPoInserLogPo, $arrayPoUpdate, $idEstacion, $dataSolicitud, $dataExpediente){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $this->db->insert_batch('solicitud_orden_compra', $arraySolicitud);
	        if($this->db->affected_rows() == 0) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar en solicitud_orden_compra');
	        }else{
	            $this->db->insert_batch('itemplan_x_solicitud_oc', $arrayItemXSolicitud);
	            if($this->db->affected_rows() == 0) {
	                $this->db->trans_rollback();
	                throw new Exception('Error al insertar en itemplan_x_solicitud_oc');
	            }else{
	                $this->db->where('itemplan', $itemplan);
	                $this->db->update('planobra', $dataItemplan);
	                if($this->db->trans_status() === FALSE) {
	                    $this->db->trans_rollback();
	                    throw new Exception('Error al modificar el planobra');
	                }else{
	                    $this->db->insert_batch('log_planobra_po', $arrayPoInserLogPo);
	                    if($this->db->trans_status() === FALSE) {
	                        $this->db->trans_rollback();
	                        throw new Exception('Error al insertar en log_planobra_po');
	                    }else{
	                        $this->db->update_batch('planobra_po', $arrayPoUpdate, 'codigo_po');
	                        if($this->db->trans_status() === FALSE) {
	                            $this->db->trans_rollback();
	                            throw new Exception('Error al modificar el updateEstadoPlanObra');
	                        }else{
	                            $this->db->where('itemplan', $itemplan);
	                            #$this->db->where('idEstacion', $idEstacion);
	                            $this->db->where('estado', 1);//suponiendo que solo cuenta con una solicitud en ese itemplan estacion en estado 1
	                            $this->db->update('pqt_solicitud_aprob_partidas_adicionales', $dataSolicitud);
	                            if($this->db->trans_status() === FALSE) {
	                                $this->db->trans_rollback();
	                                throw new Exception('Error al modificar a pqt_solicitud_aprob_partidas_adicionales');
	                            }else{
	        	                       //$this->db->where('idEstacion', $idEstacion);
	        	                       $this->db->where('itemplan', $itemplan);
	        	                       $this->db->update('itemplan_expediente', $dataExpediente);
	        	                       if($this->db->trans_status() === FALSE) {
	        	                           $this->db->trans_rollback();
	        	                           throw new Exception('Error al modificar a pqt_partidas_adicionales_tmp');
	            	                   }else{
	            	                       $data['error'] = EXIT_SUCCESS;
	            	                       $data['msj'] = 'Se actualizo correctamente!';
	            	                       $this->db->trans_commit();
	            	                   }
	        	                   }
	
	                        }
	                    }
	                }
	            }
	        }
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
}