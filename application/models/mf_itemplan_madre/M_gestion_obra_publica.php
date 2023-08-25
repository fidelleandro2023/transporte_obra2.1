<?php

class M_gestion_obra_publica extends CI_Model {

    //http://www.codeigniter.com/userguide3/database/results.html
    function __construct() {
        parent::__construct();
    }

    function getInfoItemplan($itemplan) {
        $sql = "SELECT 
                    itemplan_madre_detalle_obras_publicas.*,
                    subproyecto.subproyectoDesc,
                    proyecto.proyectoDesc,
                    itemplan_madre.coordenadaX coordY,
                    itemplan_madre.coordenadaY coordX,
                    usua_reg.usuario 			as usua_reg,
                    usua_carta_coti.usuario 	as usua_envio_ct,
	                usua_carta_respu.usuario 	as usua_envio_cr,
	                usua_convenio.usuario 		as usua_convenio,
                    usua_kickoff.usuario		as usuario_kickoff
                FROM
                    itemplan_madre,
                    subproyecto,
                    proyecto,
                    itemplan_madre_detalle_obras_publicas
	                left join usuario as usua_reg          ON itemplan_madre_detalle_obras_publicas.usuario_envio_carta      = usua_reg.id_usuario
	                left join usuario as usua_carta_coti   ON itemplan_madre_detalle_obras_publicas.usuario_carta_cotizacion = usua_carta_coti.id_usuario
	                left join usuario as usua_carta_respu  ON itemplan_madre_detalle_obras_publicas.usuario_carta_respuesta  = usua_carta_respu.id_usuario
	                left join usuario as usua_convenio     ON itemplan_madre_detalle_obras_publicas.usuario_reg_convenio     = usua_convenio.id_usuario
					left join usuario as usua_kickoff	   ON itemplan_madre_detalle_obras_publicas.usuario_ejecuta_kickoff  = usua_kickoff.id_usuario

	            WHERE	itemplan_madre.idSubProyecto 	= subproyecto.idSubProyecto
				AND 	subproyecto.idProyecto 	= proyecto.idProyecto
				AND 	itemplan_madre.itemplan_m 		= itemplan_madre_detalle_obras_publicas.itemplan
				AND 	itemplan_madre_detalle_obras_publicas.itemplan =  ?";
        $result = $this->db->query($sql, array($itemplan));
        if ($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }

    function getInfoItemplanPE($itemplan) {
        $sql = "SELECT FORMAT(costoEstimado,2) as costoEstimado FROM itemplan_madre WHERE itemplan_m =  ?";
        $result = $this->db->query($sql, array($itemplan));
        if ($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }

    function getTotalLicencia($itemplan) {
        $sql = "SELECT FORMAT(SUM(total),2) AS total  FROM itemplan_po_licencia_simu WHERE itemplanPE= ?";
        $result = $this->db->query($sql, array($itemplan));
        if ($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }

    function saveFileCartaRespuesta($itemplan, $dataCR) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $this->db->trans_begin();
            $this->db->where('itemplan', $itemplan);
            $this->db->update('itemplan_madre_detalle_obras_publicas', $dataCR);
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Hubo un error al actualizar Carta Respuesta.');
            } else {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se insert&oacute; correctamente!!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    function getPtrsAprobadasByItemplan($itemplan) {
        $Query = "SELECT 
                    	planobra_po.*, UPPER(po_estado.estado) as estado, estacion.estacionDesc, area.areaDesc, area.tipoArea
                    FROM
                    	planobra_po,
                    	po_estado,
                        detalleplan,
                        subproyectoestacion,
                        estacionarea,
                        estacion,
                        area
                    WHERE
                    	planobra_po.codigo_po 				= detalleplan.poCod
                    AND	planobra_po.itemplan  				= detalleplan.itemplan
                    AND detalleplan.idSubProyectoEstacion 	= subproyectoestacion.idSubProyectoEstacion
                    AND	subproyectoestacion.idEstacionArea 	= estacionarea.idEstacionArea
                    AND	estacionarea.idEstacion 			= estacion.idEstacion
                    AND	estacionarea.idArea 				= area.idArea
                    AND	planobra_po.estado_po 				= po_estado.idPoEstado
                    AND planobra_po.itemplan 				= ?
                    AND estado_po IN (3,4,5,6)";

        $result = $this->db->query($Query, array($itemplan));
        return $result;
    }

    function saveLogCotizacion($dataCR) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert('itemplan_madre_detalle_obras_publicas_cotizacion', $dataCR);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar tabla po_detalle_op_cotizacion');
            } else {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se insert&oacute; correctamente!!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    function getCotizacionesLog($itemplan) {
        $Query = "SELECT       pdop.*, 
	                           u.usuario as usua_envio_ct
                	FROM       itemplan_madre_detalle_obras_publicas_cotizacion pdop, 
	                           usuario u
                	WHERE      pdop.usuario_carta_cotizacion = u.id_usuario
	                AND        pdop.itemplan = ?
	               ORDER BY    pdop.fecha_carta_cotizacion DESC";
        $result = $this->db->query($Query, array($itemplan));
        return $result;
    }

    function saveLogRespuCotizacion($dataCR) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert('itemplan_madre_detalle_obras_publicas_respu', $dataCR);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar tabla po_detalle_op_respu');
            } else {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se insert&oacute; correctamente!!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    function getRespuestaLog($itemplan) {
        $Query = "SELECT   pdop.*,
                    	   u.usuario as usua_envio_cr
            	FROM       itemplan_madre_detalle_obras_publicas_respu pdop,
            	           usuario u
            	WHERE      pdop.usuario_carta_respuesta = u.id_usuario
            	AND        pdop.itemplan = ?
        	    ORDER BY   pdop.fecha_carta_respuesta DESC";
        $result = $this->db->query($Query, array($itemplan));
        return $result;
    }

    function hasParalizaCotizacion($itemplan) {
        $Query = " SELECT COUNT(1) as cont
                	FROM itemplan_madre_paralizacion
                	WHERE itemplan = ?
                	AND flg_activo = 1
                	AND flgEstado  = 1
	                AND idMotivo = 23";
        $result = $this->db->query($Query, array($itemplan));
        if ($result->row() != null) {
            return $result->row_array()['cont'];
        } else {
            return null;
        }
    }

    function insertParalizacionCoti($dataCR) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert('itemplan_madre_paralizacion', $dataCR);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar tabla paralizacion');
            } else {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se insert&oacute; correctamente!!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    function updateParalizacionCoti($dataCR, $itemplan) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->where('itemplan', $itemplan);
            $this->db->where('flg_activo', 1);
            $this->db->where('flgEstado', 1);
            $this->db->where('idMotivo', 23);
            $this->db->update('itemplan_madre_paralizacion', $dataCR);
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Hubo un error al actualizar Carta Respuesta.');
            } else {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se insert&oacute; correctamente!!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    ////////

    function hijosItemMadreDetalle($itemplan_madre) {
        $Query = "SELECT
	po.*,
	sb.subProyectoDesc,
	pr.proyectoDesc,
	em.empresaColabDesc,
	est.estadoPlanDesc
        FROM
	planobra po,
	subproyecto sb,
	proyecto pr ,
	empresacolab em,
	estadoplan est
        WHERE
	po.idSubProyecto = sb.idSubProyecto 
	AND sb.idProyecto = pr.idProyecto
	AND em.idEmpresaColab = po.idEmpresaColab 
	AND est.idEstadoPlan = po.idEstadoPlan AND
	itemPlanPE = '$itemplan_madre';";
        $result = $this->db->query($Query, array());
        return $result->result();
    }

    function montoToltal($itemplan) {
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

    function costoTotal($Itemplan) {
        $Query = 'SELECT
	SUM(po.costo_total) AS  total
        FROM
	planobra_po po
	INNER JOIN planobra pln on pln.itemPlan = po.itemplan
        WHERE
	pln.itemPlanPE = ?
	AND po.idEstacion = 1';
        $result = $this->db->query($Query, array($Itemplan));
        $idEstadoPlan = $result->row()->total;
        return $idEstadoPlan;
    }

    function getCodSol($itemplan) {
        $sql = 'SELECT  * FROM itemplan_madre WHERE itemplan_m = ? LIMIT 1';
        $result = $this->db->query($sql, array($itemplan));
        if ($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }

    function createEdit_Certf($itemplan_m, $monto) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $sql = "SELECT editOCByItemplanMadre(?,?);";
            $result = $this->db->query($sql, array($itemplan_m, $monto));
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                throw new Exception('Error al crear solicitud de edicion');
            } else {
                $sql = "SELECT certOCByItemplanMadre(?,?);";
                $result = $this->db->query($sql, array($itemplan_m, $monto));
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    throw new Exception('Error al crear solicitud de certificacion');
                } else {
                    $data['error'] = EXIT_SUCCESS;
                    $data['msj'] = 'Se creo correctamente!';
                    $this->db->trans_commit();
                }
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    public function updateMontoPEP($idpep, $textMonto) {
        $sql = "UPDATE sap_detalle SET monto_temporal=(monto_temporal-'$textMonto') WHERE pep1='$idpep'";
        $this->db->query($sql);
        return true;
    }

    function costoTotalMO($Itemplan) {
        $Query = "SELECT
	( SUM( t.costo_total ) ) AS totalFinal 
FROM
	(
	SELECT
		ppo.costo_total 
	FROM
		planobra_po ppo 
	WHERE
		ppo.itemplan = COALESCE ( '$Itemplan', ppo.itemplan )
	AND ppo.flg_tipo_area = 2	
        
		AND NOT ppo.idEstacion = 1
		AND ppo.estado_po <> 8 UNION ALL
	SELECT
		(valoriz_m_o ) 
	FROM
		web_unificada we,
		detalleplan dp,
		estadoptr ep,
		subproyectoestacion se 
	WHERE
		we.ptr = dp.poCod 
		AND we.idEstadoPtr = ep.idEstadoPo 
		AND dp.idSubProyectoEstacion = se.idSubProyectoEstacion 
		AND ep.idEstadoPo <> 6 
	AND dp.itemPlan = COALESCE ( '$Itemplan', dp.itemPlan ) 
	) t";
        $result = $this->db->query($Query, array($Itemplan));
        $idEstadoPlan = $result->row()->totalFinal;
        return $idEstadoPlan;
    }

    function getTotalLicenciaIPM($itemplan) {
        $sql = "SELECT SUM(total) AS total  FROM itemplan_po_licencia_simu WHERE itemplanPE= ?";
        $result = $this->db->query($sql, array($itemplan));
        if ($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }

    function getItemplanHijos($itemplan) {
        $Query = "SELECT * FROM planobra WHERE itemPlanPE=?;";
        $result = $this->db->query($Query, array($itemplan));
        return $result->result();
    }

    function getPEPSBolsaPepByItemplan($itemplan) {
        $Query = "SELECT DISTINCT bp.pep1, sd.monto_temporal 
                FROM planobra po, subproyecto sp, bolsa_pep bp LEFT JOIN sap_detalle sd ON bp.pep1 = sd.pep1
                WHERE po.idSubProyecto = sp.idSubProyecto
                AND sp.idSubProyecto = bp.idSubProyecto
                AND bp.tipo_pep IN (2,3) 
                AND	bp.estado = 1
                AND po.itemplan = '$itemplan'";
        $result = $this->db->query($Query, array($itemplan));
        return $result->result();
    }

    function aprobarCotizacionSolo($dataPlanobra, $solicitud_oc_creacion, $item_x_sol, $dataSapDetalle) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->where('itemplan', $dataPlanobra['itemplan']);
            $this->db->update('planobra', $dataPlanobra);
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Hubo un error al actualizar en planobra para aprobar la cotizacion');
            } else {
                $this->db->insert('solicitud_orden_compra', $solicitud_oc_creacion);
                if ($this->db->affected_rows() != 1) {
                    $this->db->trans_rollback();
                    throw new Exception('Error al insertar en solicitud_orden_compra');
                } else {
                    $this->db->insert('itemplan_x_solicitud_oc', $item_x_sol);
                    if ($this->db->affected_rows() != 1) {
                        $this->db->trans_rollback();
                        throw new Exception('Error al insertar en itemplan_x_solicitud_oc');
                    } else {
                        $this->db->where('pep1', $dataSapDetalle['pep1']);
                        $this->db->update('sap_detalle', $dataSapDetalle);
                        if ($this->db->trans_status() === FALSE) {
                            throw new Exception('Hubo un error al actualizar en sap_detalle.');
                        } else {
                            $data['error'] = EXIT_SUCCESS;
                            $data['msj'] = 'Se actualizo correctamente!';
                            $this->db->trans_commit();
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    
    function insertIPsin($item_sin_sol) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert('itemplan_madre_itemplan_sin_sol', $item_sin_sol);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar tabla itemplan_madre_itemplan_sin_sol');
            } else {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se insert&oacute; correctamente!!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function getTotalLicenciaIP($itemplan) {
        $sql = "SELECT SUM(total) AS total  FROM itemplan_po_licencia_simu WHERE itemplan= ?";
        $result = $this->db->query($sql, array($itemplan));
        if ($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }

}
