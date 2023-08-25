<?php
class M_bandeja_ejecucion extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
	
	function getBandejaEjecucion($idEstacion, $idTipoPlanta, $jefatura, $idSubProyecto, $idProyecto, $fecha=null, $eccSession){
                $Query = "SELECT distinct po.itemPlan,
                       sp.subProyectoDesc,
                       p.idProyecto,
                       p.idProyecto,po.itemPlan, 
                        e.idEstacion,
                        po.indicador ,
                        pd.estado AS idEstadoPlan,
                        p.proyectoDesc,
                        sp.subProyectoDesc,
                       (SELECT empresaColabDesc 
                          FROM empresacolab 
                         WHERE po.idEmpresaColab = idEmpresaColab)empresaColabDesc,		
                       (CASE WHEN po.idEmpresaColabDiseno IS NOT NULL THEN (SELECT empresaColabDesc 
                                                                              FROM empresacolab 
                                                                             WHERE idEmpresaColab = po.idEmpresaColabDiseno) 
                             ELSE NULL END) as empresaColabDiseno,
                       e.estacionDesc,
                       pd.nomArchivo,
                       ep.estadoPlanDesc,
                       DATE(pd.fecha_prevista_atencion) AS fecha_prevista_atencion,
                       (SELECT jefatura 
                         FROM central c
                        WHERE c.idCentral = po.idCentral) jefatura,
                        (CASE WHEN p.idProyecto = ".ID_PROYECTO_SISEGOS." THEN (SELECT    COUNT(1) as count  
							FROM    sisego_planobra 
						   WHERE    itemplan = po.itemPlan 
							 AND    origen = ".ID_TIPO_OBRA_FROM_DISENIO.") ELSE 0 END) as hasSisegoPlanObra
                  FROM pre_diseno pd LEFT JOIN 
                       planobra po    ON (pd.itemplan      = po.itemplan) INNER JOIN
                       subproyecto sp ON (po.idSubProyecto = sp.idSubProyecto) INNER JOIN
                       proyecto     p ON (sp.idProyecto    = p.idProyecto) INNER JOIN
                       estacion     e ON (pd.idEstacion = e.idEstacion) INNER JOIN
                       estadoplan   ep ON (ep.idEstadoPlan = po.idEstadoPlan)
                   AND pd.estado = 2
                   AND pd.idEstacion     = COALESCE(?, pd.idEstacion)
                   AND sp.idTipoPlanta   = COALESCE(?, sp.idTipoPlanta)
                   AND sp.idSubProyecto  = COALESCE(?, sp.idSubProyecto) 
                   AND p.idProyecto      = COALESCE(?, p.idProyecto)
                   AND CASE WHEN sp.idTipoSubProyecto IS NOT NULL THEN sp.idTipoSubProyecto <> 2
                                     ELSE true END
                   AND CASE WHEN ? IS NOT NULL THEN DATE(pd.fecha_prevista_atencion) = '".$fecha."' 
                            WHEN ? IS NULL AND pd.fecha_prevista_atencion IS NULL THEN pd.fecha_prevista_atencion IS NULL 
                            ELSE pd.fecha_prevista_atencion = pd.fecha_prevista_atencion END   
                   AND CASE WHEN ? IS NOT NULL THEN
                            po.idCentral  IN (SELECT idCentral 
                                                    FROM central
                                                    WHERE jefatura = '".$jefatura."'
                                                    GROUP BY idCentral)
                             ELSE po.idCentral = po.idCentral END
                AND po.idEstadoPlan not IN (6,5,10)
				AND po.paquetizado_fg is null
				AND CASE WHEN ".$eccSession." NOT IN (0,6) THEN po.idEmpresaColabDiseno = ".$eccSession."
                         ELSE TRUE END				
                -- AND po.idSubProyecto != ".ID_SUB_PROYECTO_CV_RESIDENCIA_FTTH;  
				
	    // if($eccSession != 0 && $eccSession !=6){
	         // $Query .= " AND po.idEmpresaColabDiseno = ".$eccSession;
        // }    
	    $result = $this->db->query($Query,array($idEstacion, $idTipoPlanta, $idSubProyecto, $idProyecto, $fecha, $fecha, $jefatura));
        return $result;
	}	
	
	
	
	function actualizarDatosDiseno($itemplan,$idEstacion, $fechaPrevDise){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	
	        $this->db->trans_begin();
	        $dataUpdate = array(
	            'fecha_prevista_atencion' => $fechaPrevDise
	        );
	        $this->db->where('itemplan', $itemplan);
	        $this->db->where('idEstacion', $idEstacion);
	        $this->db->update('pre_diseno',$dataUpdate);
	        if ($this->db->trans_status() === FALSE) {
	            throw new Exception('Hubo un error al actualizar el estadoplan.');
	        }else{	            
	                $data['error']    = EXIT_SUCCESS;
	                $data['msj']      = 'Se actualizo correctamente!';
	                $this->db->trans_commit();
	        }
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
		
	function ejecutarDiseno($itemplan, $idEstacion){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	
	        $this->db->trans_begin();
	        
    	    $arrayData = array (
        	    'fecha_ejecucion'	=> date("Y-m-d H:i:s"),	    
        	    'usuario_ejecucion' => $this->session->userdata('userSession'),
    	        'estado'            => 3
    	    );
    	    $this->db->where('itemPlan', $itemplan);
    	    $this->db->where('idEstacion', $idEstacion);
	        $this->db->update('pre_diseno', $arrayData);
        	if($this->db->trans_status() === FALSE) {
        	    $this->db->trans_rollback();
        	    throw new Exception('Error al modificar el updateEstadoPlanObra');
        	}else{
	            $data['error']    = EXIT_SUCCESS;
	            $data['msj']      = 'Se actualizo correctamente!';
	            $this->db->trans_commit();
	        }
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
	
	function getOperador($itemplan) {
	    $sql = "SELECT operador
	              FROM planobra 
	             WHERE itemplan = ?";
	    $result = $this->db->query($sql,array($itemplan));
	    return $result->row_array()['operador'];
	}
	
	function getCountPtrsEstacionesByItemplan($itemplan){
        $Query = "  SELECT 		sp.idProyecto, 
                    			count(CASE WHEN ea.idEstacion = ".ID_ESTACION_COAXIAL." THEN 1 ELSE NULL END) AS conCoax,  
                                count(CASE WHEN ea.idEstacion = ".ID_ESTACION_FO." THEN 1 ELSE NULL END) AS conFo,
                                count(CASE WHEN ea.idEstacion = ".ID_ESTACION_MULTIPAR." THEN 1 ELSE NULL END) AS conMul,
								count(CASE WHEN ea.idEstacion = ".ID_ESTACION_UM." THEN 1 ELSE NULL END) AS conUM
                      FROM 
								(planobra po, 
								subproyecto sp, 
								subproyectoestacion se, 
								estacionarea ea) LEFT JOIN detalleplan dp ON(po.itemplan = dp.itemplan 
																		AND se.idSubProyectoEstacion = dp.idSubProyectoEstacion)
                    WHERE 		po.idSubProyecto = sp.idSubProyecto
					AND 		se.idEstacionArea = ea.idEstacionArea
					AND 		CASE WHEN UPPER(po.operador) = 'ESTUDIO DE CAMPO' THEN TRUE
									 ELSE dp.idSubProyectoEstacion = se.idSubProyectoEstacion END
                    AND			po.itemplan = ?
                    GROUP BY 	sp.idProyecto;";
	    $result = $this->db->query($Query,array($itemplan));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
/**OLD
	function havePTRAprobada($itemplan, $idEstacion){
	    $Query = "select   po.itemplan, 
	                       SUM(CASE WHEN SUBSTRING(wu.est_innova,1,2)='02' THEN 1 ELSE null end) as numAprob 
                   FROM    planobra po, detalleplan dp, subproyectoestacion se, estacionarea ea, web_unificada wu
                	where  po.itemplan = dp.itemplan
                	and    dp.poCod = wu.ptr
                	and    dp.idSubProyectoEstacion = se.idSubProyectoEstacion
                	and    se.idEstacionArea = ea.idEstacionArea
                	and    ea.idEstacion = ?
                	and    po.itemplan = ?
                	group by po.itemplan;";
	    $result = $this->db->query($Query,array($idEstacion, $itemplan));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	*/
	function havePTRAprobada($itemplan, $idEstacion){
	    $Query = "SELECT 
                    ((SELECT 
                            COUNT(1)
                        FROM
                            planobra po,
                            detalleplan dp,
                            subproyectoestacion se,
                            estacionarea ea,
                            web_unificada_det wud
                        WHERE
                            po.itemplan = dp.itemplan
                                AND dp.poCod = wud.ptr
                                AND dp.idSubProyectoEstacion = se.idSubProyectoEstacion
                                AND se.idEstacionArea = ea.idEstacionArea
                                AND ea.idEstacion = ?
                                AND po.itemplan = ?
                                AND wud.estado_asig_grafo = 2
	                            AND wud.itemplan = po.itemplan) + (SELECT 
                            COUNT(1) AS pt
                        FROM
                            planobra_po ppo
                        WHERE
                            ppo.itemplan = ?
                                AND ppo.idEstacion = ?
                                AND ppo.estado_po IN (3,4,5,6)
	                            AND ppo.flg_tipo_area = 1)) AS numAprob;";
	    $result = $this->db->query($Query,array($idEstacion, $itemplan, $itemplan, $idEstacion));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
	function generarPODiseno($itemplan, $idEstacion, $nro_amplificadores, $nro_trobas) {
		$sql = "SELECT fn_insertPODiseno(?, ?, 4, ?, ?) AS resp limit 1";
		$result = $this->db->query($sql, array($itemplan, $idEstacion, $nro_amplificadores, $nro_trobas));
		return $result->row_array()['resp'];
	}
	
	function deleteAnclaByItemplaEstacion($itemplan, $idEstacion) {
		$sql = "DELETE 
				  FROM pre_diseno 
				 WHERE itemplan = ?
				   AND CASE WHEN ? = 5 THEN idEstacion = 2
							ELSE idEstacion = 5 END
				   AND itemplan IN ( SELECT po.itemplan 
									   FROM planobra po, 
										    subproyecto s  
									  WHERE s.idSubProyecto = po.idSubProyecto
										AND s.idTipoSubProyecto = 2)";
		$this->db->query($sql, array($itemplan, $idEstacion));
	}
	
	function getTramasPendientesEstacionNoEjec($itemplan, $estacion) {
	    $sql = "SELECT * FROM siom_obra 
	            WHERE  ultimo_estado = 'ESTACION NO EJECUTADA'
	            AND    itemplan = ? 
	            AND    idEstacion = ?";
	    $result = $this->db->query($sql, array($itemplan, $estacion));
	    return $result->result();
	}
	
	function updateSiomToNodoNoEncontrado($dataLogPo, $dataSiom, $id_siom_obra) {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $this->db->where('id_siom_obra', $id_siom_obra);
	        $this->db->update('siom_obra', $dataSiom);
	        if ($this->db->trans_status() === FALSE) {
	            throw new Exception('Hubo un error al actualizar siom_obra');
	        }else{
	           $this->db->insert('log_tramas_siom', $dataLogPo);
                if($this->db->affected_rows() != 1) {
                    $this->db->trans_rollback();
                    throw new Exception('Error al insertar en log_tramas_siom');
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
	
	function updatePathDisenoExpediente($dataUpdate, $itemplan, $idEstacion){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	
	        $this->db->trans_begin();
	        $this->db->where('itemplan', $itemplan);
	        $this->db->where('idEstacion', $idEstacion);
	        $this->db->update('pre_diseno',$dataUpdate);
	        if ($this->db->trans_status() === FALSE) {
	            throw new Exception('Hubo un error al actualizar el estadoplan.');
	        }else{
	            $data['error']    = EXIT_SUCCESS;
	            $data['msj']      = 'Se actualizo correctamente!';
	            $this->db->trans_commit();
	        }
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
	
	function getTipoComplejidadByItemplan($itemplan) {
		$sql = " SELECT s.idTipoComplejidad
				  FROM planobra po, 
					   subproyecto s 
				 WHERE po.idSubProyecto = s.idSubProyecto
				   AND po.itemplan      = ?";
		$result = $this->db->query($sql, array($itemplan));
	    return $result->row_array()['idTipoComplejidad'];		   
	}
	
	function getDetallePODiseno($__itemplan, $__id_estacion, $__nro_amplificador, $__nro_troba) {
		$sql = "SELECT  ttt.idPartida,
						ttt.idPrecioDiseno,
						ttt.idEmpresaColab,
						ttt.idZonal,
						ttt.cantidad,
						ttt.idTipoComplejidad,
						ttt.has_sirope_diseno,
						ttt.baremo,
						ttt.costoPreciario,
						ROUND(ttt.costoPreciario*ttt.baremo*ttt.cantidad,2) AS totalDiseno,
						   (SELECT 1
							  FROM (estacionarea ea,
								   area a,
								   subproyectoestacion se,
								   planobra p) 
							LEFT JOIN detalleplan dp ON (p.itemplan = dp.itemplan 
														 AND se.idSubProyectoEstacion = dp.idSubProyectoEstacion)
								   
							 WHERE (CASE WHEN ttt.idEstacion = 5 THEN ea.idEstacion IN  (15,16,6,5) 
										 ELSE ea.idEstacion = ttt.idEstacion END)
							   -- AND se.idSubProyecto  = ttt.idSubProyecto
							   AND p.itemplan 		 = ttt.itemplan
							  -- AND se.idSubProyecto  = p.idSubProyecto
							   AND a.idArea 		 = ea.idArea
							   AND CASE WHEN UPPER(p.operador) = 'ESTUDIO DE CAMPO' THEN TRUE
										ELSE dp.idSubProyectoEstacion = se.idSubProyectoEstacion END
							   AND ea.idEstacionArea = se.idEstacionArea
							   AND a.tipoArea 		 = 'MAT'
							   limit 1)flgMat
					FROM (
						  SELECT t.idPartida,
								  t.idPrecioDiseno,
								  t.idEmpresaColab,
								  t.idZonal,
								  t.cantidad,
								  t.idTipoComplejidad,
								  t.has_sirope_diseno,
								  t.baremo,
								  t.costoPreciario,
								  t.idSubProyecto,
								  t.itemplan,
								  t.idEstacion
							 FROM
								(SELECT tc.idTipoComplejidad,
										tc.complejidadDesc,
										pa.idActividad AS idPartida,
										pa.codigo AS codigoPartida,
										pa.descripcion AS descripcionPartida,
										pd.idPrecioDiseno,
										s.idSubProyecto,
										pd.descPrecio,
										s.subProyectoDesc,
										c.idZonal,
										pred.fecha_ejecucion,
										CASE WHEN po.has_sirope_diseno IS NULL THEN po.has_sirope 
											 ELSE po.has_sirope_diseno END AS has_sirope_diseno,
										prec.idEmpresaColab,
										prec.costo AS costoPreciario,
										pred.idEstacion,
										po.itemplan,
										s.idProyecto,
										CASE WHEN pred.idEstacion = 2 AND s.idProyecto = 1 AND ? >= 7 THEN pa.baremo + 4 /*  HFC si la cantidad de amplificador es mayor igual a 7 se considera 4 baremos adicionales  */
											 ELSE pa.baremo END baremo,
										CASE WHEN pred.idEstacion = 5 THEN 
																			CASE WHEN s.idProyecto = 1 THEN  ? 
																				ELSE CASE WHEN itemplanPE IS NOT NULL AND itemplanPE <> '' THEN (SELECT COUNT(pla.itemplanPE) 
																																					FROM planobra pla 
																																					WHERE po.itemplan = pla.itemplan)
																						  ELSE 1 END 
																			END
											WHEN pred.idEstacion = 2 THEN 
																			CASE WHEN s.idProyecto = 1 THEN 1  
																				ELSE CASE WHEN itemplanPE IS NOT NULL AND itemplanPE <> '' THEN (SELECT COUNT(pla.itemplanPE)+1 
																																					FROM planobra pla 
																																					WHERE po.itemplan = pla.itemplan)
																							ELSE 1 END 
																			END                                  
										END cantidad
								   FROM tipo_complejidad tc,
										partidas pa,
										partida_subproyecto ps,
										precio_diseno pd,
										subproyecto   s,
										planobra      po,
										pre_diseno    pred,
										preciario     prec,
										central       c
								WHERE s.idTipoComplejidad  = tc.idTipoComplejidad
									AND s.idSubProyecto      = po.idSubProyecto
									AND ps.idSubProyecto     = s.idSubProyecto  
									AND pa.idActividad       = ps.idPartida
									AND pa.idPrecioDiseno    = pd.idPrecioDiseno
									AND pa.estado            = 1
									AND pd.flg_activo        = 1
									AND pred.itemplan        = po.itemplan
									AND CASE WHEN po.paquetizado_fg = 2 THEN false
											 WHEN po.paquetizado_fg = 1 THEN po.idCentralPqt = c.idCentral 								 
											 ELSE po.idCentral      = c.idCentral END
									AND prec.idZonal         = c.idZonal
									AND pred.idEstacion      = ?
									AND pa.idTipoComplejidad = tc.idTipoComplejidad
									AND tc.idTipoComplejidad = 1
									AND prec.idEstacion      = pred.idEstacion
									AND prec.idEmpresaColab  = po.idEmpresaColab
									AND prec.idPrecioDiseno  = pd.idPrecioDiseno
									AND po.itemplan          = ?
									AND pa.flg_reg_grafico IS NULL)t
				 UNION ALL
					(SELECT tt.idPartida, 
							1 AS idPrecioDiseno,
							tt.idEmpresaColab,
							tt.idZonal,
							ROUND(tt.cantidad, 2),
							tt.idTipoComplejidad,
							tt.has_sirope_diseno,
							tt.baremo, 
							(SELECT pee.costo
							   FROM preciario pee
							  WHERE pee.idEstacion = tt.idEstacion 
								AND pee.idPrecioDiseno = 1 
								AND pee.idEmpresaColab = tt.idEmpresaColab 
								AND tt.idZonal = pee.idZonal) AS costoPreciario,
							tt.idSubProyecto,
							tt.itemplan,
							tt.idEstacion
						FROM (
								SELECT DISTINCT
										tc.idTipoComplejidad,
										tc.complejidadDesc,
										pa.idActividad AS idPartida,
										pa.codigo AS codigoPartida,
										pa.descripcion AS descripcionPartida,
										pd.idPrecioDiseno,
										s.idSubProyecto,
										pd.descPrecio,
										po.itemplan,
										s.subProyectoDesc,
										c.idZonal,
										pred.fecha_ejecucion,
										CASE WHEN po.has_sirope_diseno IS NULL THEN po.has_sirope 
											 ELSE po.has_sirope_diseno END AS has_sirope_diseno,
										prec.idEmpresaColab,
										prec.costo AS costoPreciario,
										pred.idEstacion,
										s.idProyecto,
										pa.baremo,
										CASE WHEN f.intervaloFin IS NULL AND f.intervaloInicio IS NOT NULL THEN t.puntos - f.intervaloInicio
											 WHEN f.intervaloInicio IS NOT NULL AND f.intervaloFin IS NOT NULL THEN CASE WHEN t.puntos > f.intervaloFin THEN t.puntos - (t.puntos - f.intervaloFin) 
																														 ELSE t.puntos END 
											 ELSE t.puntos END AS cantidad
										FROM subproyecto s, 
											planobra po,
											tipo_complejidad tc,
											pre_diseno    pred,
											partidas pa,
											partida_subproyecto ps,
											preciario     prec,
											precio_diseno pd,
											central c,
											formula_baremo f,
											(SELECT CASE WHEN pa.idPrecioDiseno = 3 THEN 1 ELSE pa.idPrecioDiseno END idPrecioDiseno,
													pd.descPrecio,
													CASE WHEN pd.idPrecioDiseno IN (1,3) THEN SUM(ROUND(pa.baremo*ppd.cantidad_final, 2)) 
														 WHEN pd.idPrecioDiseno = 2 THEN SUM(ROUND(pa.baremo*ppd.cantidad_final, 2)) END puntos
											  FROM planobra_po_detalle_mo ppd,
												   partidas pa,
												   precio_diseno pd,
												   planobra_po ppo
											 WHERE pa.idActividad = ppd.idActividad       
											   AND pd.idPrecioDiseno = pa.idPrecioDiseno
											   AND ppo.codigo_po  = ppd.codigo_po
											   AND CASE WHEN ? = 5 THEN ppo.idEstacion IN(5,6,15,16,8)
														WHEN ? = 2 THEN ppo.idEstacion IN(2,3)
														ELSE ppo.idEstacion = ? END
											   AND ppo.itemplan    = ?
											   AND ppo.flg_tipo_area = 2
											  -- AND pd.idPrecioDiseno = 1
											   AND codigo NOT IN ('21004-8', '21001-3', '69900-4', '69901-2', '36900-4', '36901-2')
											   GROUP BY CASE WHEN pd.idPrecioDiseno = 2 THEN pd.idPrecioDiseno END)t
									WHERE s.idTipoComplejidad  = tc.idTipoComplejidad
										AND s.idSubProyecto      = po.idSubProyecto
										AND ps.idSubProyecto     = s.idSubProyecto
										AND f.idPartida          = pa.idActividad  
										AND pa.idActividad       = ps.idPartida
										-- AND pa.idPrecioDiseno    = pd.idPrecioDiseno
										AND pa.estado            = 1
										AND pd.flg_activo        = 1
										AND pred.itemplan        = po.itemplan
										AND prec.idZonal         = c.idZonal 
										AND prec.idEmpresaColab  = po.idEmpresaColab
										AND prec.idPrecioDiseno  = pd.idPrecioDiseno
										AND prec.idEstacion      = pred.idEstacion
										AND pred.idEstacion      = ?
										AND prec.idPrecioDiseno  = CASE WHEN pa.idActividad = 456 THEN 2 ELSE true END -- CANALIZADOR
										AND pa.idTipoComplejidad = tc.idTipoComplejidad
										AND tc.idTipoComplejidad = 2
										AND CASE WHEN po.paquetizado_fg = 2 THEN false
												 WHEN po.paquetizado_fg = 1 THEN po.idCentralPqt = c.idCentral 								 
												 ELSE po.idCentral      = c.idCentral END
										AND po.itemplan          = ?
										-- AND CASE WHEN s.idTipoComplejidad = 2 THEN (t.puntos > f.intervaloFin OR f.intervaloInicio < t.puntos) END
										AND  prec.idPrecioDiseno = t.idPrecioDiseno 
										AND pa.flg_reg_grafico IS NULL
									   GROUP BY CASE WHEN t.puntos > 1000 OR f.idPartida = 456 THEN f.idPartida END
									)tt
								   ORDER BY tt.idPrecioDiseno)
							   )ttt
		ORDER BY ttt.idPrecioDiseno, ttt.idPartida";
		$result = $this->db->query($sql, array($__nro_amplificador, $__nro_troba, $__id_estacion, $__itemplan, $__id_estacion, $__id_estacion, 
		                                        $__id_estacion, $__itemplan, $__id_estacion, $__itemplan));
		return $result->result_array();
	}
	
    function registrarPoDiseno($arrayPO, $arrayLogPO, $arrayDetalleplan, $arrayDetallePO) {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;

        $this->db->insert('planobra_po', $arrayPO);

        if($this->db->affected_rows() != 1) { _log("LOG 1");
            $data['msj']   = 'Error al insertar en Planobra Po';
            return $data;
        }else{
            $this->db->insert_batch('log_planobra_po', $arrayLogPO);
            if($this->db->trans_status() === FALSE) { _log("LOG 2");
                $data['msj']   = 'Error al insertar en log_planobra_po';
                return $data;
            }else{
	            $this->db->insert('detalleplan', $arrayDetalleplan);
	            if($this->db->affected_rows() != 1) { _log("LOG 3");
	                $this->db->trans_rollback();
	                $data['msj']   = 'Error al insertar en detalleplan';
                    return $data;
	            }else{
	                $this->db->insert_batch('planobra_po_detalle_partida', $arrayDetallePO);
	                if ($this->db->trans_status() === FALSE) { _log("LOG 4");
	                    $data['msj']   = 'Error al insertar en planobra_po_detalle_partida';
                        return $data;
	                }else{
	                    $data['error'] = EXIT_SUCCESS;
	                    $data['msj'] = 'Se ingreso correctamente!';
	                    
	                    return $data;
	                }
	            }
            }
        }	        	        
	}
	
	function getFlgMo($itemplan, $idEstacion) {
	    $sql = "SELECT 1 flg_mo
            	  FROM planobra_po_detalle_mo ppd,
            		   partidas pa,
            		   precio_diseno pd,
            		   planobra_po ppo
            	 WHERE pa.idActividad = ppd.idActividad       
            	   AND pd.idPrecioDiseno = pa.idPrecioDiseno
            	   AND ppo.codigo_po  = ppd.codigo_po
            	   AND CASE WHEN ? = 5 THEN ppo.idEstacion IN(5,6,15,16,8)
            				WHEN ? = 2 THEN ppo.idEstacion IN(2,3,8)
            				ELSE ppo.idEstacion = 5 END
            	   AND ppo.itemplan    = ?
            	   AND ppo.flg_tipo_area = 2
            	   AND codigo NOT IN ('21004-8', '21001-3', '69900-4', '69901-2', '36900-4', '36901-2')
            	   GROUP BY ppo.idEstacion";
   		$result = $this->db->query($sql, array($idEstacion, $idEstacion, $itemplan));
		return $result->row_array()['flg_mo'];
	}
	
	function getIdSubProyecEstacionByitemplanAndEstacion($itemplan, $idEstacion) {
	    $sql = " SELECT idSubProyectoEstacion 
				   FROM subproyectoestacion 
				  WHERE idSubProyecto = ( SELECT idSubProyecto 
											FROM planobra 
										   WHERE itemplan = ?) 
					AND idEstacionArea IN (
											SELECT idEstacionArea 
											  FROM estacionarea 
											 WHERE idEstacion = 1
											   AND CASE WHEN ? = 5 THEN idArea = 2 
														WHEN ? = 2 THEN idArea = 1 END
										) limit 1";
		$result = $this->db->query($sql, array($itemplan, $idEstacion, $idEstacion));
		return $result->row_array()['idSubProyectoEstacion'];
	}
	
	function getFlgPoDiseno($itemplan, $idSubProyectoEstacion) {
	    $sql = "SELECT 1 flgPoDiseno
				  FROM detalleplan dp LEFT JOIN
                       planobra_po po ON(dp.poCod = po.codigo_po)
			     WHERE dp.itemplan   = ?
				   AND dp.idSubProyectoEstacion = ?
				   AND CASE WHEN po.codigo_po IS NOT NULL THEN po.estado_po <> 8
							ELSE dp.poCod = dp.poCod END
                   limit 1";
   		$result = $this->db->query($sql, array($itemplan, $idSubProyectoEstacion));
		return $result->row_array()['flgPoDiseno'];
	}
	
	function getDetallePODisenoPqt($__itemplan, $__id_estacion, $__nro_amplificador, $__nro_troba) {
		$sql = "SELECT  ttt.idPartida,
						ttt.idPrecioDiseno,
						ttt.idEmpresaColab,
						ttt.idZonal,
						ttt.cantidad,
						ttt.idTipoComplejidad,
						ttt.has_sirope_diseno,
						ttt.baremo,
						ttt.costoPreciario,
						ROUND(ttt.costoPreciario*ttt.baremo*ttt.cantidad,2) AS totalDiseno,
						   (SELECT 1
							  FROM (estacionarea ea,
								   area a,
								   subproyectoestacion se,
								   planobra p) 
							LEFT JOIN detalleplan dp ON (p.itemplan = dp.itemplan 
														 AND se.idSubProyectoEstacion = dp.idSubProyectoEstacion)
								   
							 WHERE (CASE WHEN ttt.idEstacion = 5 THEN ea.idEstacion IN  (15,16,6,5) 
										 ELSE ea.idEstacion = ttt.idEstacion END)
							   -- AND se.idSubProyecto  = ttt.idSubProyecto
							   AND p.itemplan 		 = ttt.itemplan
							  -- AND se.idSubProyecto  = p.idSubProyecto
							   AND a.idArea 		 = ea.idArea
							   AND CASE WHEN UPPER(p.operador) = 'ESTUDIO DE CAMPO' THEN TRUE
										ELSE dp.idSubProyectoEstacion = se.idSubProyectoEstacion END
							   AND ea.idEstacionArea = se.idEstacionArea
							   AND a.tipoArea 		 = 'MAT'
							   limit 1)flgMat
					FROM (
						  SELECT t.idPartida,
								  t.idPrecioDiseno,
								  t.idEmpresaColab,
								  t.idZonal,
								  t.cantidad,
								  t.idTipoComplejidad,
								  t.has_sirope_diseno,
								  t.baremo,
								  t.costoPreciario,
								  t.idSubProyecto,
								  t.itemplan,
								  t.idEstacion
							 FROM
								(SELECT tc.idTipoComplejidad,
										tc.complejidadDesc,
										pa.idActividad AS idPartida,
										pa.codigo AS codigoPartida,
										pa.descripcion AS descripcionPartida,
										pd.idPrecioDiseno,
										s.idSubProyecto,
										pd.descPrecio,
										s.subProyectoDesc,
										c.idZonal,
										pred.fecha_ejecucion,
										CASE WHEN po.has_sirope_diseno IS NULL THEN po.has_sirope 
											 ELSE po.has_sirope_diseno END AS has_sirope_diseno,
										prec.idEmpresaColab,
										prec.costo AS costoPreciario,
										pred.idEstacion,
										po.itemplan,
										s.idProyecto,
										CASE WHEN pred.idEstacion = 2 AND s.idProyecto = 1 AND ? >= 7 THEN pa.baremo + 4 /*  HFC si la cantidad de amplificador es mayor igual a 7 se considera 4 baremos adicionales  */
											 ELSE pa.baremo END baremo,
										CASE WHEN pred.idEstacion = 5 THEN 
																			CASE WHEN s.idProyecto = 1 THEN  ? 
																				ELSE CASE WHEN itemplanPE IS NOT NULL AND itemplanPE <> '' THEN (SELECT COUNT(pla.itemplanPE) 
																																					FROM planobra pla 
																																					WHERE po.itemplan = pla.itemplan)
																						  ELSE 1 END 
																			END
											WHEN pred.idEstacion = 2 THEN 
																			CASE WHEN s.idProyecto = 1 THEN 1  
																				ELSE CASE WHEN itemplanPE IS NOT NULL AND itemplanPE <> '' THEN (SELECT COUNT(pla.itemplanPE)+1 
																																					FROM planobra pla 
																																					WHERE po.itemplan = pla.itemplan)
																							ELSE 1 END 
																			END                                  
										END cantidad
								   FROM tipo_complejidad tc,
										partidas pa,
										partida_subproyecto ps,
										precio_diseno pd,
										subproyecto   s,
										planobra      po,
										pre_diseno    pred,
										pqt_preciario     prec,
										central       c
								WHERE s.idTipoComplejidad  = tc.idTipoComplejidad
									AND s.idSubProyecto      = po.idSubProyecto
									AND ps.idSubProyecto     = s.idSubProyecto  
									AND pa.idActividad       = ps.idPartida
									AND pa.idPrecioDiseno    = pd.idPrecioDiseno
									AND pa.estado            = 1
									AND pd.flg_activo        = 1
									AND pred.itemplan        = po.itemplan
									AND po.idCentral         = c.idCentral
									AND prec.idZonal         = c.idZonal
									AND pred.idEstacion      = ?
									AND pa.idTipoComplejidad = tc.idTipoComplejidad
									AND tc.idTipoComplejidad = 1
									AND prec.idEstacion      = pred.idEstacion
									AND prec.idEmpresaColab  = po.idEmpresaColabDiseno
									AND prec.idPrecioDiseno  = pd.idPrecioDiseno
									AND po.itemplan          = ?
									AND pa.flg_reg_grafico IS NULL)t
				 UNION ALL
					(SELECT tt.idPartida, 
							1 AS idPrecioDiseno,
							tt.idEmpresaColab,
							tt.idZonal,
							ROUND(tt.cantidad, 2),
							tt.idTipoComplejidad,
							tt.has_sirope_diseno,
							tt.baremo, 
							(SELECT pee.costo
							   FROM pqt_preciario pee
							  WHERE pee.idEstacion = tt.idEstacion 
								AND pee.idPrecioDiseno = 1 
								AND pee.idEmpresaColab = tt.idEmpresaColab 
								AND tt.idZonal = pee.idZonal) AS costoPreciario,
							tt.idSubProyecto,
							tt.itemplan,
							tt.idEstacion
						FROM (
								SELECT DISTINCT
										tc.idTipoComplejidad,
										tc.complejidadDesc,
										pa.idActividad AS idPartida,
										pa.codigo AS codigoPartida,
										pa.descripcion AS descripcionPartida,
										pd.idPrecioDiseno,
										s.idSubProyecto,
										pd.descPrecio,
										po.itemplan,
										s.subProyectoDesc,
										c.idZonal,
										pred.fecha_ejecucion,
										CASE WHEN po.has_sirope_diseno IS NULL THEN po.has_sirope 
											 ELSE po.has_sirope_diseno END AS has_sirope_diseno,
										prec.idEmpresaColab,
										prec.costo AS costoPreciario,
										pred.idEstacion,
										s.idProyecto,
										pa.baremo,
										CASE WHEN f.intervaloFin IS NULL AND f.intervaloInicio IS NOT NULL THEN t.puntos - f.intervaloInicio
											 WHEN f.intervaloInicio IS NOT NULL AND f.intervaloFin IS NOT NULL THEN CASE WHEN t.puntos > f.intervaloFin THEN t.puntos - (t.puntos - f.intervaloFin) 
																														 ELSE t.puntos END 
											 ELSE t.puntos END AS cantidad
										FROM subproyecto s, 
											planobra po,
											tipo_complejidad tc,
											pre_diseno    pred,
											partidas pa,
											partida_subproyecto ps,
											pqt_preciario     prec,
											precio_diseno pd,
											central c,
											formula_baremo f,
											(SELECT CASE WHEN pa.idPrecioDiseno = 3 THEN 1 ELSE pa.idPrecioDiseno END idPrecioDiseno,
													pd.descPrecio,
													CASE WHEN pd.idPrecioDiseno IN (1,3) THEN SUM(ROUND(pa.baremo*ppd.cantidad_final, 2)) 
														 WHEN pd.idPrecioDiseno = 2 THEN SUM(ROUND(pa.baremo*ppd.cantidad_final, 2)) END puntos
											  FROM planobra_po_detalle_mo ppd,
												   partidas pa,
												   precio_diseno pd,
												   planobra_po ppo
											 WHERE pa.idActividad = ppd.idActividad       
											   AND pd.idPrecioDiseno = pa.idPrecioDiseno
											   AND ppo.codigo_po  = ppd.codigo_po
											   AND CASE WHEN ? = 5 THEN ppo.idEstacion IN(5,6,15,16,8)
														WHEN ? = 2 THEN ppo.idEstacion IN(2,3,8)
														ELSE ppo.idEstacion = ? END
											   AND ppo.itemplan    = ?
											   AND ppo.flg_tipo_area = 2
											  -- AND pd.idPrecioDiseno = 1
											   AND codigo NOT IN ('21004-8', '21001-3', '69900-4', '69901-2', '36900-4', '36901-2')
											   GROUP BY CASE WHEN pd.idPrecioDiseno = 2 THEN pd.idPrecioDiseno END)t
									WHERE s.idTipoComplejidad  = tc.idTipoComplejidad
										AND s.idSubProyecto      = po.idSubProyecto
										AND ps.idSubProyecto     = s.idSubProyecto
										AND f.idPartida          = pa.idActividad  
										AND pa.idActividad       = ps.idPartida
										-- AND pa.idPrecioDiseno    = pd.idPrecioDiseno
										AND pa.estado            = 1
										AND pd.flg_activo        = 1
										AND pred.itemplan        = po.itemplan
										AND po.idCentral         = c.idCentral
										AND prec.idZonal         = c.idZonal 
										AND prec.idEmpresaColab  = po.idEmpresaColabDiseno
										AND prec.idPrecioDiseno  = pd.idPrecioDiseno
										AND prec.idEstacion      = pred.idEstacion
										AND pred.idEstacion      = ?
										AND prec.idPrecioDiseno  = CASE WHEN pa.idActividad = 456 THEN 2 ELSE true END -- CANALIZADOR
										AND pa.idTipoComplejidad = tc.idTipoComplejidad
										AND tc.idTipoComplejidad = 2
										AND po.itemplan          = ?
										-- AND CASE WHEN s.idTipoComplejidad = 2 THEN (t.puntos > f.intervaloFin OR f.intervaloInicio < t.puntos) END
										AND  prec.idPrecioDiseno = t.idPrecioDiseno 
										AND pa.flg_reg_grafico IS NULL
									   GROUP BY CASE WHEN t.puntos > 1000 OR f.idPartida = 456 THEN f.idPartida END
									)tt
								   ORDER BY tt.idPrecioDiseno)
							   )ttt
		ORDER BY ttt.idPrecioDiseno, ttt.idPartida";
		$result = $this->db->query($sql, array($__nro_amplificador, $__nro_troba, $__id_estacion, $__itemplan, $__id_estacion, $__id_estacion, 
		                                        $__id_estacion, $__itemplan, $__id_estacion, $__itemplan));
		return $result->result_array();
	}
}