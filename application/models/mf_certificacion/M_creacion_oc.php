<?php
class M_creacion_oc extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
    }
	
	
	function getBandejaSolOC($cod_solicitud, $itemplan, $estado) {
		
		$Query = "select so.*,(CASE 	WHEN tipo_solicitud = 1 THEN 'CREACION OC'
															WHEN tipo_solicitud = 2 THEN 'EDICION OC'
															WHEN tipo_solicitud = 3 THEN 'CERTIFICACION OC' 
                                                            WHEN tipo_solicitud = 4 THEN 'ANULACION POS. OC' END) as tipoSolicitud,
														p.proyectoDesc, sp.subProyectoDesc, e.empresaColabDesc, 
														(CASE WHEN so.estado = 1 THEN 'PENDIENTE' 
															  WHEN so.estado = 2 THEN 'ATENDIDO' 
															  WHEN so.estado = 3 THEN 'CANCELADO'
                                                              WHEN so.estado = 4 THEN 'EN ESPERA DE EDICION' 
															  WHEN so.estado = 5 THEN 'EN ESPERA DE ACTA' END) as estado_sol,
													CONCAT(u.nombres, ' ' ,u.ape_paterno) AS nombreCompleto,
												    FORMAT((SELECT SUM(ii.costo_unitario_mo)
															   FROM itemplan_x_solicitud_oc ii
															  WHERE ii.codigo_solicitud_oc = so.codigo_solicitud),2) AS costo_total,
                                                    (SELECT COUNT(1)
													   FROM itemplan_x_solicitud_oc ii
													  WHERE ii.codigo_solicitud_oc = so.codigo_solicitud) AS numItemplan,
												so.costo_sap,
												CASE WHEN po.solicitud_oc_dev = so.codigo_solicitud THEN 1 
                                                     ELSE 0 END flg_pdf_edi,
													 con.alias as contrato_padre,con.contrato_marco,con.tipo_moneda,
                                                po.itemplan,
												po.departamento_matriz,
												po.provincia_matriz,
												po.distrito_matriz,
												po.codigo_unico,
												po.nom_estacion_matriz,
												po.flg_presupuesto_oc,
 												u.nombre AS gestor,
												CASE WHEN tipo_solicitud = 4 THEN po.ruta_archivo ELSE null END ruta_cancelacion,
												( SELECT COUNT(1) 
													FROM usuario_x_subproyecto_valida_acta uxv
												   WHERE uxv.idSubProyecto = po.idSubProyecto) count_config_firma,
												so.flg_cambio_pep
										  from  (empresacolab e,
                                                 subproyecto sp, 
                                                 proyecto p, 
                                                 solicitud_orden_compra so,
                                                 itemplan_x_solicitud_oc i,
                                                 planobra po,contrato con) 
									  left join usuario u on so.usuario_valida = u.id_usuario
									  LEFT JOIN usuario u2 ON po.usuario_registro = u2.id_usuario
										 where so.idEmpresaColab = e.idEmpresaColab
											and so.idSubProyecto = sp.idSubProyecto
											and sp.idProyecto = p.idProyecto
											and so.estado in (1,2,3,4,5)
											and i.codigo_solicitud_oc = so.codigo_solicitud 
											AND CASE WHEN po.flg_presupuesto_oc IS NOT NULL AND so.tipo_solicitud = 2 AND so.estado = 1 THEN true
											         WHEN so.estado IN (1,4) AND so.tipo_solicitud NOT IN (2,4) THEN po.flg_presupuesto_oc IS NULL 
													 ELSE true END
											/* and CASE WHEN so.estado IN (1,4) AND so.tipo_solicitud NOT IN (4) THEN po.flg_presupuesto_oc IS NULL ELSE true END */
                                            and i.itemplan = po.itemplan AND con.id_contrato = po.idContrato
											AND CASE WHEN so.tipo_solicitud = 3 AND so.estado <> 3 THEN idEstadoFirma = 5
		            								 ELSE true END";
        if($cod_solicitud  != null){
            $Query .= " and so.codigo_solicitud = '".$cod_solicitud."'";
        }
        if($itemplan    !=  null){
            $Query .= " and po.itemplan = '".$itemplan."'";
        }
        if($estado  !=  null){
            $Query .= " and so.estado= ".$estado;
        }
	   $Query .= " Group by so.codigo_solicitud";
        $result = $this->db->query($Query, array());
        return $result->result();           
    }
	/*
    function getBandejaSolOC($cod_solicitud, $itemplan, $estado) {
        $Query = "SELECT tb.*,
					(CASE WHEN tipo_solicitud = 1 THEN (SUM(CASE WHEN po.itemplan is not null  then 1 else 0 END))
						  WHEN tipo_solicitud = 2 THEN (SUM(CASE WHEN po_dev.itemplan is not null  then 1 else 0 END)) 
						  WHEN tipo_solicitud = 3 THEN (SUM(CASE WHEN po_cer.itemplan is not null  then 1 else 0 END)) END) as numItemplan,
						  (CASE WHEN tipo_solicitud = 1 THEN FORMAT(SUM(po.costo_unitario_mo),2)
								WHEN tipo_solicitud = 2 THEN FORMAT(po_dev.costo_devolucion,2) 
								WHEN tipo_solicitud = 3 THEN FORMAT((SELECT SUM(costo_unitario_mo_certi) 
																	  FROM planobra 
																	 WHERE solicitud_oc_certi = po_cer.solicitud_oc_certi),2) END) as costo_total
						 
								FROM (
										select so.*,(CASE WHEN tipo_solicitud = 1 THEN 'CREACION OC'
														WHEN tipo_solicitud = 2 THEN 'EDICION OC'
														WHEN tipo_solicitud = 3 THEN 'EDICION CERTIFICACION OC' END) as tipoSolicitud,
														p.proyectoDesc, sp.subProyectoDesc, e.empresaColabDesc, 
														(CASE WHEN so.estado = 1 THEN 'PENDIENTE' 
															  WHEN so.estado = 2 THEN 'ATENDIDO' 
															  WHEN so.estado = 3 THEN 'CANCELADO' 
															  WHEN so.estado = 4 THEN 'CERTIFICADO' END) as estado_sol,
													CONCAT(u.nombres, ' ' ,u.ape_paterno) AS nombreCompleto
										  from  empresacolab e,subproyecto sp, proyecto p, solicitud_orden_compra so left join usuario u on so.usuario_valida = u.id_usuario
										 where so.idEmpresaColab = e.idEmpresaColab
											and so.idSubProyecto = sp.idSubProyecto
											and sp.idProyecto = p.idProyecto
											and so.estado in (1,2,3,4)
											#and so.tipo_solicitud = 1
									  ) AS tb 
						   left join planobra po 
								on  tb.codigo_solicitud = po.solicitud_oc
						   left join planobra po_dev 
								on  tb.codigo_solicitud = po_dev.solicitud_oc_dev
						   left join planobra po_cer 
								on  tb.codigo_solicitud = po_cer.solicitud_oc_certi
								WHERE 1  =  1";
        if($cod_solicitud  != null){
            $Query .= " and tb.codigo_solicitud = '".$cod_solicitud."'";
        }
        if($itemplan    !=  null){
            $Query .= " and (po.itemplan = '".$itemplan."'  or po_dev.itemplan = '".$itemplan."')";
        }
        if($estado  !=  null){
            $Query .= " and tb.estado= ".$estado;
        }
	   $Query .= " Group by tb.id, tb.codigo_solicitud, tb.idEmpresaColab, tb.tipo_solicitud, 
	   tb.tipoSolicitud, tb.estado, tb.usuario_valida, tb.fecha_valida, tb.fecha_creacion, tb.idSubProyecto, 
	   tb.plan, tb.pep1, tb.pep2, tb.proyectoDesc, tb.subProyectoDesc,tb.empresaColabDesc, tb.estado_sol, 
	   tb.nombreCompleto, tb.cesta, tb.orden_compra, tb.path_oc";
        $result = $this->db->query($Query, array());
        log_message('error', $this->db->last_query());
        return $result->result();           
    }
	*/
    
   
    public function getPtrsByHojaGestion($hoja_gestion)
    {
        $sql = "   select po.*, subProyectoDesc, FORMAT(i.costo_unitario_mo,2) as limite_costo_mo, FORMAT(po.costo_unitario_mat,2) as limite_costo_mat
                    from planobra po, subproyecto sp, itemplan_x_solicitud_oc i
                    where po.idSubProyecto = sp.idSubProyecto
                    and i.itemplan = po.itemplan
                    and i.codigo_solicitud_oc = ?";
        $result = $this->db->query($sql,array($hoja_gestion));      
        return $result->result();
    }   

	public function getItemOrdenCompraEdicion($codigo_solicitud)
    {
        $sql = "select po.*, subProyectoDesc, FORMAT(po.costo_devolucion,2) as costo_devolucion
			    from planobra po, subproyecto sp 
				where po.idSubProyecto = sp.idSubProyecto
                and po.solicitud_oc_dev = ?";
        $result = $this->db->query($sql,array($codigo_solicitud));
        log_message('error', $this->db->last_query());        
        return $result->result();
    }

	public function getItemOrdenCompraCerti($codigo_solicitud)
    {
        $sql = "select po.*, subProyectoDesc, FORMAT(po.costo_unitario_mo_certi,2) as costo_unitario_mo_certi
			    from planobra po, subproyecto sp 
				where po.idSubProyecto = sp.idSubProyecto
                and po.solicitud_oc_certi = ?";
        $result = $this->db->query($sql,array($codigo_solicitud));
        log_message('error', $this->db->last_query());        
        return $result->result();
    }	
	
	public function getItemOrdenCompraAnulaPosOC($codigo_solicitud)
    {
        $sql = "select pt.codigo_solicitud_oc, po.itemplan, sp.subProyectoDesc, FORMAT(pt.costo_unitario_mo,2) as costo_unitario_mo_anula_pos, pt.posicion, po.orden_compra, po.cesta, po.nombreProyecto
			    from itemplan_x_solicitud_oc pt, planobra po, subproyecto sp 
				where pt.itemplan = po.itemplan
                and po.idSubProyecto = sp.idSubProyecto
                and pt.codigo_solicitud_oc = ?";
        $result = $this->db->query($sql,array($codigo_solicitud));
        return $result->result();
    }
	
	function update_solicitud_oc($codigo_solicitud, $arrayData){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->where('codigo_solicitud', $codigo_solicitud);
            $this->db->update('solicitud_orden_compra', $arrayData);
            if($this->db->affected_rows() != 1) {
                throw new Exception('Error al actualizar la informacion.');
            }else{              
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se actualizo correctamente!';
            }
        }catch(Exception $e){
            $data['msj']   = $e->getMessage();
        }
        return $data;
    }
	
	function getDataSolicitudPdf($id_solicitud) {
        $sql = " 
			   SELECT t.itemplan,
					 t.nombreProyecto,
					 t.cesta,
					 t.orden_compra,
					 t.subProyectoDesc,
					 GROUP_CONCAT(t.group_posicion) group_posicion,
					 t.limite_costo_mo,
					 t.limite_costo_mat,
					 t.empresaColabDesc,
					 t.fecha_reg_sol,
					 t.codigo_solicitud,
					 t.costo_total,
					 t.responsable,
					 t.costo_sap,
					 t.proyectoDesc,
					 t.distrito,
					 t.departamento,
					 t.fechaEjecucion,
					 t.gerencia_desc,
					 t.provincia,
					 t.idSubProyecto,
					 
					 
					 t.idUsuarioFirmaJefeEmp,
					 t.idUsuarioFirmaJefeTdp,
					 t.idUsuarioFirmaGerente,
					 t.idEstadoFirma,
					 t.idUsuarioFirmaSup,
					 t.tipo_moneda,
					 t.codigo_unico,
					 (SELECT url_pdf_pruebas 
					    FROM siom_obra_evidencias 
					   WHERE itemplan = t.itemplan
					   LIMIT 1) flg_evidencia
				FROM (   
					SELECT  po.idSubProyecto,
							po.itemplan,
							po.nombreProyecto,
							s.cesta,
							s.orden_compra, 
							subProyectoDesc,
							GROUP_CONCAT(po.posicion) group_posicion, 
							FORMAT(i.costo_unitario_mo,2) as limite_costo_mo, 
							FORMAT(po.costo_unitario_mat,2) as limite_costo_mat,
							e.empresaColabDesc,
							(s.fecha_creacion) as fecha_reg_sol,
							s.codigo_solicitud,
							SUM(i.costo_unitario_mo) costo_total,
							(SELECT UPPER(u.nombre)
							   FROM gestor_responsable_x_proyecto g, 
									usuario u
							  WHERE g.idUsuario = u.id_usuario
								AND g.idProyecto = sp.idProyecto) as responsable,
							FORMAT(po.costo_sap, 2)costo_sap,
							p.proyectoDesc,
							c.distrito,
							c.departamento,
							c.departamento AS provincia,
							CASE WHEN (po.fechaEjecucion IS NULL OR po.fechaEjecucion = '') THEN CASE WHEN (SELECT fecha_upd
																											 FROM control_estado_itemplan 
																											WHERE idEstadoPlan = 3
																											  AND itemplan = po.itemplan
																											  LIMIT 1) IS NULL THEN po.fec_ult_ejec_diseno
																									  ELSE  (SELECT fecha_upd
																											   FROM control_estado_itemplan 
																											  WHERE idEstadoPlan = 3
																												AND itemplan = po.itemplan
																												LIMIT 1) END
								 ELSE po.fechaEjecucion END fechaEjecucion,
							CASE WHEN p.idProyecto IN (1,26,27) THEN 'GERENCIA DE PLANIFICACION E INGENIERIA ACCESO FIJO'
							     ELSE 'GERENCIA DESPLIEGUE ACCESO FIJO Y PLANTA EXTERNA' END gerencia_desc,
							
							
							s.idUsuarioFirmaJefeEmp,
							s.idUsuarioFirmaJefeTdp,
							s.idUsuarioFirmaGerente,
							s.idEstadoFirma,
							s.idUsuarioFirmaSup,
							(SELECT tipo_moneda
							   FROM contrato
							 WHERE id_contrato = po.idContrato) tipo_moneda,
							po.codigo_unico
					   FROM planobra po, 
							subproyecto sp,
							proyecto p,
							itemplan_x_solicitud_oc i, 
							solicitud_orden_compra s,
							empresacolab e,
							pqt_central c
					  WHERE po.idSubProyecto = sp.idSubProyecto
						AND po.idCentralPqt = c.idCentral
						AND po.paquetizado_fg IN (1,2)
						AND e.idEmpresaColab = po.idEmpresaColab
						AND i.itemplan = po.itemplan
						AND s.codigo_solicitud = i.codigo_solicitud_oc
						AND i.codigo_solicitud_oc = ?
						AND p.idProyecto = sp.idProyecto
					GROUP BY i.codigo_solicitud_oc
				UNION ALL
					SELECT  po.idSubProyecto,
							po.itemplan,
							po.nombreProyecto,
							s.cesta,
							s.orden_compra, 
							subProyectoDesc,
							GROUP_CONCAT(po.posicion) group_posicion, 
							FORMAT(i.costo_unitario_mo,2) as limite_costo_mo, 
							FORMAT(po.costo_unitario_mat,2) as limite_costo_mat,
							e.empresaColabDesc,
							(s.fecha_creacion) as fecha_reg_sol,
							s.codigo_solicitud,
							SUM(i.costo_unitario_mo) costo_total,
							(SELECT UPPER(u.nombre)
							   FROM gestor_responsable_x_proyecto g, 
									usuario u
							  WHERE g.idUsuario = u.id_usuario
								AND g.idProyecto = sp.idProyecto) as responsable,
							FORMAT(po.costo_sap, 2)costo_sap,
							p.proyectoDesc,
							CASE WHEN (c.distrito IS NULL OR c.distrito = '') THEN c.jefatura 
							     ELSE c.distrito END distrito,
							c.jefatura as departamento,
							c.jefatura AS provincia,
							CASE WHEN (po.fechaEjecucion IS NULL OR po.fechaEjecucion = '') THEN CASE WHEN (SELECT fecha_upd
																											 FROM control_estado_itemplan 
																											WHERE idEstadoPlan = 3
																											  AND itemplan = po.itemplan
																											  LIMIT 1) IS NULL THEN po.fec_ult_ejec_diseno
																									  ELSE  (SELECT fecha_upd
																											   FROM control_estado_itemplan 
																											  WHERE idEstadoPlan = 3
																												AND itemplan = po.itemplan
																												LIMIT 1) END
								 ELSE po.fechaEjecucion END fechaEjecucion,
							CASE WHEN p.idProyecto IN (1,26,27) THEN 'GERENCIA DE PLANIFICACION E INGENIERIA ACCESO FIJO'
							     ELSE 'GERENCIA DESPLIEGUE ACCESO FIJO Y PLANTA EXTERNA' END gerencia_desc,
							
							
							s.idUsuarioFirmaJefeEmp,
							s.idUsuarioFirmaJefeTdp,
							s.idUsuarioFirmaGerente,
							s.idEstadoFirma,
							s.idUsuarioFirmaSup,
							(SELECT tipo_moneda
							   FROM contrato
							 WHERE id_contrato = po.idContrato) tipo_moneda,
							 po.codigo_unico
					   FROM planobra po, 
							subproyecto sp,
							proyecto p,
							itemplan_x_solicitud_oc i, 
							solicitud_orden_compra s,
							empresacolab e,
							central c
					  WHERE po.idSubProyecto = sp.idSubProyecto
						AND po.idCentral     = c.idCentral
						AND po.paquetizado_fg IS NULL
						AND e.idEmpresaColab = po.idEmpresaColab
						AND i.itemplan = po.itemplan
						AND s.codigo_solicitud = i.codigo_solicitud_oc
						AND i.codigo_solicitud_oc = ?
						AND p.idProyecto = sp.idProyecto
						AND po.flg_transporte IS NULL
					GROUP BY i.codigo_solicitud_oc 
					)t";
        $result = $this->db->query($sql, array($id_solicitud, $id_solicitud));
        return $result->row_array();
    }
	
	function actualizarMontoSap($codigo_solicitud, $costo_sap) {
		$this->db->where('solicitud_oc_dev', $codigo_solicitud);
		$this->db->update('planobra',array('costo_sap' => $costo_sap));

		if($this->db->trans_status() === FALSE) {
			$data['error'] = EXIT_ERROR;
			$data['msj']   = 'No se guardo el costo Sap, verificar.';
		} else {
			$data['error'] = EXIT_SUCCESS;
		}
		return $data;
	}
	
	function getSolCertPndEdicion($codigo_solicitud) {
	    $Query = "select soc.codigo_solicitud from itemplan_x_solicitud_oc ixs, solicitud_orden_compra soc
                	where ixs.codigo_solicitud_oc = soc.codigo_solicitud
                	and soc.tipo_solicitud = 3
                	and soc.estado = 4
                	and ixs.itemplan = (select itemplan from itemplan_x_solicitud_oc where codigo_solicitud_oc = ?) LIMIT 1";
	    $result = $this->db->query($Query, array($codigo_solicitud));
	    if ($result->row() != null) {
	        return $result->row_array()['codigo_solicitud'];
	    } else {
	        return null;
	    }
	}
	
	function update_solicitud_ocV2($codigo_solicitud, $arrayData, $arraySoli){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->where('codigo_solicitud', $codigo_solicitud);
	        $this->db->update('solicitud_orden_compra', $arrayData);
	        if($this->db->affected_rows() != 1) {
	            throw new Exception('Error al actualizar la informacion.');
	        }else{
	            $this->db->where('codigo_solicitud', $arraySoli['codigo_solicitud']);
	            $this->db->update('solicitud_orden_compra', $arraySoli);
	            if($this->db->trans_status() === FALSE) {
	                throw new Exception('Error al actualizar la informacion en solicitud_orden_compra.');
	            }else{
    	            $data['error'] = EXIT_SUCCESS;
    	            $data['msj'] = 'Se actualizo correctamente!';
	            }
	        }
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	    }
	    return $data;
	}
	
	function updatePOByItemplan($codigo_solicitud, $estado_actual, $estado_final) {
		$sql = "UPDATE planobra_po ppo,
				       planobra po
				   SET ppo.estado_po = ?
				 WHERE ppo.estado_po = ?
				   AND ppo.itemplan = po.itemplan
			  	   AND po.solicitud_oc_certi  = ?";
		$result = $this->db->query($sql,array($estado_final, $estado_actual, $codigo_solicitud));
		if($this->db->affected_rows() == 0) {
			$data['error'] = EXIT_ERROR;
			$data['msj'] = 'No hay POs validadas, las POs deben estar validadas para ser certificadas.';
		}else{              
			$data['error'] = EXIT_SUCCESS;
			$data['msj'] = 'Se actualizo correctamente!';
		}
		return $data;
	}
	
	function update_solicitud_oc_certi($codigo_solicitud, $arrayData, $dataPo, $idUsuario, $countPoValidadas){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->where('codigo_solicitud', $codigo_solicitud);
	        $this->db->update('solicitud_orden_compra', $arrayData);
	        if($this->db->affected_rows() != 1) {
	            throw new Exception('Error al actualizar la informacion.');
	        }else{
	            $this->db->where('solicitud_oc_certi', $dataPo['solicitud_oc_certi']);
	            $this->db->update('planobra', $dataPo);
	            if($this->db->affected_rows() != 1) {
	                throw new Exception('Error al actualizar la informacion.');
	            }else{
					$data = $this->updateCertiPoPin($codigo_solicitud, 5, $idUsuario);
					if($countPoValidadas > 0) {
						$data = $this->insertLogPO($codigo_solicitud, 5, 'CERTIFICADO OC', $idUsuario, 6);

						if($data['error'] == EXIT_ERROR) {
							throw new Exception($data['msj']);
						} else {
							$data = $this->updatePOByItemplan($codigo_solicitud, 5, 6);
	
							if($data['error'] == EXIT_ERROR) {
								throw new Exception($data['msj']);
							} else {
								$data['error'] = EXIT_SUCCESS;
								$data['msj'] = 'Se actualizo correctamente!';
							}
						}
					} else {
						$data['msj'] = 'Se actualizo correctamente, ojo que no se encontro POs validadas para pasar a certificado.';
						$data['error'] = EXIT_SUCCESS;
					}
	            }
	        }
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	    }
	    return $data;
	}
	
	function updateCertiPoPin($codigo_solicitud, $estadoPo, $usuario) {
		$sql = "UPDATE ptr_planta_interna pt,
		               planobra po
		           SET rangoPtr = ?, 
				       fecha_ultimo_estado = NOW(), 
					   usua_ultimo_estado = ?
		         WHERE po.itemplan = pt.itemplan
				   AND rangoPtr NOT IN (5,6)
				   AND po.solicitud_oc_certi  = ?";
				   
		$result = $this->db->query($sql, array($estadoPo, $usuario, $codigo_solicitud));
		
		if ($this->db->trans_status() === FALSE) {
			$dataSalida['error']    = EXIT_ERROR;
			$dataSalida['msj']      = 'Error al actualizar po pin!';
		} else {
			$dataSalida['error']    = EXIT_SUCCESS;
			$dataSalida['msj']      = 'Operacion exitosa!';
		}
		
		return $dataSalida;
	}
	
	function getCountPoByItemplanAndEstado($itemplan, $estado_po) {
		$sql = "SELECT COUNT(1) as count
		          FROM planobra_po 
				 WHERE itemplan  = ?
				   AND estado_po = ?";
		$result = $this->db->query($sql, array($itemplan, $estado_po));
		return $result->row_array()['count'];
	}
	
	function insertLogPO($codigo_solicitud, $po_estado_actual, $descripcion, $idUsuario, $po_estado_final) {
		$sql = "INSERT INTO log_planobra_po (codigo_po, itemplan, 
											 idUsuario, fecha_registro, 
											 idPoestado, controlador)
				SELECT ppo.codigo_po, po.itemplan,?, NOW(), ?, ? 
				  FROM planobra_po ppo,
				       planobra po
				 WHERE ppo.estado_po = ?
				   AND po.itemplan = ppo.itemplan
			  	   AND po.solicitud_oc_certi  = ?";
		$result = $this->db->query($sql,array($idUsuario, $po_estado_final, $descripcion, $po_estado_actual, $codigo_solicitud));
		if($this->db->affected_rows() == 0) {
			$data['error'] = EXIT_ERROR;
			$data['msj']   = 'error al registrar el log de po';
		}else{
			$data['error'] = EXIT_SUCCESS;
			$data['msj']   = 'Se registro correctamente!';
		}
		return $data;
	}
	
	function getDataCodigoUnicoSAM($codigoUnico) {
			$sql1 = "  SELECT 
							M.IdMatriz,
							M.CodigoUnico,
							M.LatitudDefinitiva AS Latitud, 
							M.LongitudDefinitiva AS Longitud,
							ET.Latitud AS Latitud2, 
							ET.Longitud AS Longitud2,
							distrito.Nombre AS distrito,
						provincia.`Nombre` AS provincia,
						departamento.Nombre AS departamento,
						NombreEstacion
						FROM sam_db_020123.matrizseguimiento M 
					LEFT JOIN sam_db_020123.estacionconsolidado ET ON (M.CodigoUnico = ET.CodigoUnico COLLATE utf8_general_ci)
						INNER JOIN sam_db_020123.distrito ON (distrito.`IdDistrito` = M.IdDistrito)
						INNER JOIN sam_db_020123.provincia ON (provincia.`IdProvincia` = distrito.`IdProvincia`)
						INNER JOIN sam_db_020123.departamento ON (departamento.`IdDepartamento` = provincia.`IdDepartamento`)
						WHERE M.CodigoUnico= ?
							 ORDER BY IdMatriz DESC LIMIT 1
				 
				";
				
		$result1 = $this->db->query($sql1, array($codigoUnico));
		if ($result1->row() != null) {
	        return $result1->row_array();
	    }else{
			$sql2 = "
            	        SELECT 
						'0' AS IdMatriz,
						ET.CodigoUnico,
						ET.Latitud, 
						ET.Longitud,
						distrito.Nombre AS distrito,
						provincia.`Nombre` AS provincia,
						departamento.Nombre AS departamento,
			            ET.nombre as NombreEstacion
            	FROM  sam_db_020123.estacionconsolidado ET  
            	INNER JOIN sam_db_020123.distrito ON (distrito.`IdDistrito` = ET.IdDistrito)
            	INNER JOIN sam_db_020123.provincia ON (provincia.`IdProvincia` = distrito.`IdProvincia`)
            	INNER JOIN sam_db_020123.departamento ON (departamento.`IdDepartamento` = provincia.`IdDepartamento`)
            	WHERE ET.CodigoUnico= ? ORDER BY ET.IdEstacion DESC LIMIT 1
        	";
        	$result2 = $this->db->query($sql2, array($codigoUnico));
	        return $result2->row_array();
		}
    }
	
	function getSolCertPndEdicionFirma($codigo_solicitud) {
	    $Query = "select soc.codigo_solicitud,
                         CASE WHEN idEstadoFirma = 5 THEN 1
                              ELSE 5 END estado_certi 						 
		            FROM itemplan_x_solicitud_oc ixs, 
					     solicitud_orden_compra soc
                	where ixs.codigo_solicitud_oc = soc.codigo_solicitud
                	and soc.tipo_solicitud = 3
                	and soc.estado = 4
                	and ixs.itemplan = (select itemplan from itemplan_x_solicitud_oc where codigo_solicitud_oc = ?) LIMIT 1";
	    $result = $this->db->query($Query, array($codigo_solicitud));
	    if ($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
}