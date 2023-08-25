<?php
class M_pqt_consulta extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
	function getPtrConsulta($itemPlan,$nombreproyecto,$nodo,$zonal,$proy,$subPry,$estado,$filtroPrevEjec,$tipoPlanta,$ideecc, $idUsuario=null, $mesEjec=null, $idFase=null){
		
		//*p.fechaEjecucion,**//
		
		$Query = "  SELECT (CASE WHEN py.idProyecto = ".ID_PROYECTO_SISEGOS." THEN (SELECT grafo from sisego_pep2_grafo where sisego = p.indicador LIMIT 1) ELSE null END) as grafo, 
						   py.idProyecto, 
						   p.indicador, 
						   p.itemPlan, 
						   c.idCentral, 
						   p.idSubProyecto, 
						   s.subProyectoDesc, 
						   p.nombreProyecto,
						   (CASE WHEN s.idTipoSubProyecto = 2 THEN ( SELECT empresaColabDesc 
																	   FROM empresacolab 
																	  WHERE idEmpresaColab = c.idEmpresaColabCV) ELSE b.empresaColabDesc  END) as empresaColabDesc ,
						    f.faseDesc,
							c.codigo, 
							c.tipoCentralDesc, 
							e.empresaElecDesc, 
							p.fechaInicio, 
							p.fechaPrevEjec, 
							
							(case when (not p.fechaEjecucion is null and p.fechaEjecucion<'2018-07-07') then p.fechaEjecucion
				 else  date(p.fechaPreliquidacion) end) as fechaEjecucion,
							 
							c.idZonal, 
							z.zonalDesc, 
							p.idEstadoPlan, 
							t.estadoPlanDesc, 
							p.hasAdelanto, 
							s.idTipoPlanta,
							(SELECT 1 
							   FROM siom_obra 
							  WHERE itemplan = p.itemplan limit 1) flg_bandeja_siom,
							s.idTipoSubProyecto,
							p.ubic_tss_cv,
							p.ubic_exped_cv,
							p.comentario_cv,
							p.solicitud_oc
					   FROM planobra p,
					        fase f,
							subproyecto s,
							proyecto py, 
							empresaelec e, 
							central c 
							RIGHT JOIN zonal z 
							ON c.idZonal=z.idZonal 
							RIGHT JOIN empresacolab b on c.idEmpresaColab = b.idEmpresaColab, 
							estadoplan t
					  WHERE s.idSubProyecto = p.idSubProyecto 
					    AND p.idFase = f.idFase 
					    AND c.idCentral     = p.idCentral 
						AND e.idEmpresaElec = p.idEmpresaElec 
						AND p.idEstadoPlan  = t.idEstadoPlan 
						AND s.idProyecto    = py.idProyecto
						AND p.flg_transporte IS NULL
						AND CASE WHEN ".$ideecc." = 0 OR ".$ideecc." = 6 THEN c.idEmpresaColab = c.idEmpresaColab
						         WHEN c.idEmpresaColabFuente = ".$ideecc." THEN  c.idEmpresaColabFuente = ".$ideecc."
								WHEN ".$ideecc." IN (SELECT idSubProyecto 
														FROM subproyecto 
													WHERE idProyecto = 5)
								THEN  c.idEmpresaColabCV =  ".$ideecc."               
								 ELSE c.idEmpresaColab = ".$ideecc." END" ;
		if($zonal!=''){
	            if($zonal == 8 || $zonal == 9 || $zonal == 10 || $zonal == 11 || $zonal == 12 ){
	                $Query .= " AND z.idZonal IN (8,9,10,11,12)";
	            }else{
	                $Query .= " AND z.idZonal IN (".$zonal.")";
	            }
        }
		if($proy!=''){
            $Query .= " AND py.idProyecto = ".$proy;
        }
        if($subPry!=''){
            $Query .= " AND s.idSubProyecto = ".$subPry;
        }
		if($itemPlan!=''){
            $Query .= " AND p.itemPlan ='".$itemPlan."' ";
        }
        if($nombreproyecto!=''){
            $Query .= ' AND p.nombreProyecto LIKE "%'.$nombreproyecto.'%"';
            //$Query .= " AND p.nombreProyecto LIKE '%".$nombreproyecto."%' ";
        }
        if($nodo!=''){
            $Query .= " AND c.idCentral = ".$nodo;
        }
        if($estado!=''){
            $Query .= " AND p.idEstadoPlan = ".$estado;
        }        
        if($tipoPlanta!=''){
            $Query .= " AND s.idTipoPlanta = ".$tipoPlanta;
        } 
        if($filtroPrevEjec!=''){
            $Query .= " ".$filtroPrevEjec." ";
		}
		if($mesEjec!=NULL){
            $Query .=  " AND EXTRACT(MONTH FROM DATE(p.fechaPrevEjec)) = $mesEjec";
		}
		if($idUsuario!=NULL) {
			$Query .=  " AND p.itemplan IN ( SELECT l.itemplan
											   FROM log_planobra l
											  WHERE l.tipoPlanta = ".ID_TIPO_PLANTA_INTERNA."
												AND l.actividad  = 'ingresar'  
												AND l.id_usuario = '".$idUsuario."'
												GROUP BY l.itemplan)";
		}
		if($idFase!=''){
            $Query .= " AND p.idFase = ".$idFase;
        } 
        
		$result = $this->db->query($Query,array());
	    return $result;
	}
	

	function getAllZonalGroup($zonasRest){
		$Query = "SELECT SUBSTRING_INDEX( zonalDesc , ' ', 1 ) as zonalDesc FROM zonal GROUP BY SUBSTRING_INDEX( zonalDesc , ' ', 1 ) ORDER BY zonalDesc" ;
	    $result = $this->db->query($Query,array());
	    return $result;
	}
	function getAllZonalIndex($zonasRest){
		$Query = "SELECT idZonal, SUBSTRING_INDEX( zonalDesc , ' ', 1 ) as zona 
				FROM zonal " ;
		if($zonasRest!=''&&$zonasRest!=','){
            $Query .= " WHERE zonal.idzonal IN (".$zonasRest.")";
        }

        $Query .= "  GROUP BY (zona)";


	    $result = $this->db->query($Query,array());
	    return $result;
	}

function   getPtrConsultaDiseno($itemPlan,$nombreproyecto,$nodo,$zonal,$proy,$subPry,$estado,$filtroPrevEjec,$tipoPlanta){
	    
	    $Query = "  SELECT p.itemPlan, c.idCentral, p.idSubProyecto, s.subProyectoDesc, p.nombreProyecto, b.empresaColabDesc , c.codigo, c.tipoCentralDesc, e.empresaElecDesc, p.fechaInicio, p.fechaPrevEjec, p.fechaEjecucion, c.idZonal, z.zonalDesc, p.idEstadoPlan, t.estadoPlanDesc, p.hasAdelanto, s.idTipoPlanta
					FROM planobra p, subproyecto s,proyecto py, empresaelec e, central c
					RIGHT JOIN zonal z
					ON c.idZonal=z.idZonal
					RIGHT JOIN empresacolab b on c.idEmpresaColab=b.idEmpresaColab, estadoplan t
					WHERE s.idSubProyecto = p.idSubProyecto and c.idCentral= p.idCentral
					AND e.idEmpresaElec= p.idEmpresaElec and p.idEstadoPlan = t.idEstadoPlan and s.idProyecto=py.idProyecto" ;
	    if($zonal!=''){
	        if($zonal == 8 || $zonal == 9 || $zonal == 10 || $zonal == 11 || $zonal == 12 ){
	            $Query .= " AND z.idZonal IN (8,9,10,11,12)";
	        }else{
	            $Query .= " AND z.idZonal IN (".$zonal.")";
	        }
	    }
	    if($proy!=''){
	        $Query .= " AND py.idProyecto = ".$proy;
	    }
	    if($subPry!=''){
	        $Query .= " AND s.idSubProyecto = ".$subPry;
	    }
	    if($itemPlan!=''){
	        $Query .= " AND p.itemPlan ='".$itemPlan."' ";
	    }
	    if($nombreproyecto!=''){
	        $Query .= ' AND p.nombreProyecto LIKE "%'.$nombreproyecto.'%"';
	        //$Query .= " AND p.nombreProyecto LIKE '%".$nombreproyecto."%' ";
	    }
	    if($nodo!=''){
	        $Query .= " AND c.idCentral = ".$nodo;
	    }
	    if($estado!=''){
	        $Query .= " AND p.idEstadoPlan = ".$estado;
	    }
	    if($tipoPlanta!=''){
	        $Query .= " AND s.idTipoPlanta = ".$tipoPlanta;
	    }
	    if($filtroPrevEjec!=''){
	        $Query .= " ".$filtroPrevEjec." ";
	    }
	    $Query .= " AND p.idEstadoPlan IN  (".ID_ESTADO_DISENIO.",".ID_ESTADO_DISENIO_EJECUTADO.",".ID_ESTADO_DISENIO_PARCIAL.") ";
	    $result = $this->db->query($Query,array());
	    return $result;
	}
	
    function getPtrConsultaPqt($itemPlan,$nombreproyecto,$proy,$subPry,$tipoPlanta,$ideecc, $idUsuario=null, $mesEjec=null, $idFase=null, $itemMadre=null, $gestorObra){
	
	    //*p.fechaEjecucion,**//
	
	    $Query = "  SELECT (CASE WHEN py.idProyecto = ".ID_PROYECTO_SISEGOS." THEN (SELECT grafo from sisego_pep2_grafo where sisego = p.indicador LIMIT 1) ELSE null END) as grafo,
						   py.idProyecto,
						   p.indicador,
						   p.orden_compra,
						   p.solicitud_oc,
						   p.itemPlan,
						   c.idCentral,
						   p.idSubProyecto,
						   s.subProyectoDesc,
						   p.nombreProyecto,
						   b.empresaColabDesc ,
						    f.faseDesc,
							c.codigo,
							c.tipoCentralDesc,
							e.empresaElecDesc,
							p.fechaInicio,
							p.fechaPrevEjec,
							(case when (not p.fechaEjecucion is null and p.fechaEjecucion<'2018-07-07') then p.fechaEjecucion
				 else  date(p.fechaPreliquidacion) end) as fechaEjecucion,
				 			c.idZonal,
							z.zonalDesc,
							p.idEstadoPlan,
							t.estadoPlanDesc,
							p.hasAdelanto,
							s.idTipoPlanta,
							(SELECT 1
							   FROM siom_obra
							  WHERE itemplan = p.itemplan limit 1) flg_bandeja_siom,
							s.idTipoSubProyecto,
							p.ubic_tss_cv,
							p.ubic_exped_cv,
							p.comentario_cv,
							p.solicitud_oc,
							p.estado_sol_oc,
							p.orden_compra,
							p.itemPlanPE,
							p.idContrato,
							COALESCE(
									  (SELECT CASE WHEN tipo_solicitud = 1 AND estado = 1 THEN 'SOLICITUD OC CREADA'
												  WHEN (tipo_solicitud = 1 AND estado = 2) OR (tipo_solicitud IN (2,3,4) AND estado = 1) THEN 'OC CREADA'
												  WHEN tipo_solicitud = 3 AND estado = 2 THEN 'OC CERTIFICADA'
												  WHEN tipo_solicitud = 4 AND estado = 2 THEN 'OC ANULADA' END estado_oc
										FROM itemplan_x_solicitud_oc i,
										     (   SELECT codigo_solicitud, estado, tipo_solicitud, fecha_creacion 
													 FROM solicitud_orden_compra
												   UNION ALL
												   SELECT codigo_solicitud, estado, tipo_solicitud, fecha_creacion  
													 FROM itemplan_solicitud_orden_compra)s
									   WHERE s.codigo_solicitud = i.codigo_solicitud_oc
										 AND i.itemplan = p.itemplan
										 AND s.estado <> 3
										 AND tipo_solicitud <> 2
										 AND CASE WHEN tipo_solicitud = 3 THEN s.estado = 2 ELSE tipo_solicitud <> 3 END -- SOLO QUE ME TOME EN CUENTA LA CERTIFICADAS APROBADAS
										ORDER BY fecha_creacion DESC
										limit 1), 'SIN SOLICITUD OC'
									) as estado_oc,
							con.alias as contrato_padre,
							p.codigo_unico,
							FORMAT(p.costo_unitario_mo_crea_oc, 2) as costo_unitario_mo_crea_oc, 
							p.costo_unitario_mo, 
							FORMAT(p.costo_sap, 2) costo_sap,
							con.contrato_marco,
							con.tipo_moneda,
							p.ruta_excel_evidencia,
							p.ruta_pdf_evidencia,
							p.has_evidencia,
							#====JORGE V.L=====
							(CASE WHEN p.flg_opex=1 THEN 'CAPEX' ELSE 'OPEX' END) AS flg_opex,
							p.pep2,
							p.ceco,
							p.cuenta,
							p.area_funcional,
							p.codigo_certificacion,
							FORMAT(
								CASE WHEN p.idEstadoPlan = 9 AND p.costo_mo_pdt_val IS NOT NULL THEN p.costo_mo_pdt_val 
									 ELSE (  SELECT SUM(total) 
											   FROM ptr_planta_interna po, ptr_x_actividades_x_zonal ppd 
											   WHERE po.ptr = ppd.ptr
												 AND rangoPtr <> 6
												 AND po.itemplan = p.itemplan
											GROUP BY po.ptr
											   LIMIT 1) END , 2
							) costo_po_fin,
							u.nombre as usua_registro,
							p.flgLicencia,
							CASE WHEN est.Nombre IS NULL THEN ma.NombreEstacion
                                 ELSE est.Nombre END as nombreEstacion,
							p.requiere_hardware,
							p.flgInstrumentoAmbiental,
							DATE_FORMAT(p.fecha_creacion, '%d/%m/%Y %H:%i:%s') AS fecha_creacion_fmt,
							py.proyectoDesc,
							(COALESCE((  SELECT soe.descripcion AS estado_sol
                                                               FROM itemplan_x_solicitud_oc i,
                                                                    (   SELECT codigo_solicitud, estado, tipo_solicitud, fecha_creacion 
                                                                          FROM solicitud_orden_compra
                                                                         WHERE tipo_solicitud = 3
                                                                         UNION ALL
                                                                        SELECT codigo_solicitud, estado, tipo_solicitud, fecha_creacion  
                                                                          FROM itemplan_solicitud_orden_compra
                                                                         WHERE tipo_solicitud = 3
                                                                    ) s,
                                                                    solicitud_orden_compra_estado soe
                                                              WHERE s.codigo_solicitud = i.codigo_solicitud_oc
                                                                AND s.estado = soe.id
                                                                AND i.itemplan = p.itemplan
                                                                AND s.estado <> 3
                                                              ORDER BY fecha_creacion DESC
                                                              LIMIT 1),'SIN SOLICITUD OC')
							) AS estado_sol_certi,
							(SELECT zz.nombre 
							   FROM zona zz 
							  WHERE zz.id_zona = p.idZona ) zona,
							p.solicitud_oc_certi,
							p.estado_oc_certi
					   FROM (planobra p LEFT JOIN usuario u ON p.usuario_registro = u.id_usuario,
						    empresacolab b,
					        fase f,
							subproyecto s,
							proyecto py,
							empresaelec e,
							pqt_central c,
							contrato con,
                            zonal z,
							estadoplan t)
				  LEFT JOIN sam_db_020123.estacionconsolidado est
						 ON p.codigo_unico = est.CodigoUnico
                  LEFT JOIN sam_db_020123.matrizseguimiento ma
				         ON (p.codigo_unico = ma.CodigoUnico COLLATE utf8_general_ci)
					  WHERE p.paquetizado_fg IS NOT NULL 
					    AND s.idSubProyecto = p.idSubProyecto
					    AND p.idEmpresaColab = b.idEmpresaColab
                        AND c.idZonal = z.idZonal
						AND p.idFase = f.idFase
					    AND c.idCentral     = p.idCentralPqt
						AND e.idEmpresaElec = p.idEmpresaElec
						AND p.idEstadoPlan  = t.idEstadoPlan
						AND s.idProyecto    = py.idProyecto
						AND con.id_contrato = p.idContrato
						AND CASE WHEN (? IS NULL OR ? = '') THEN true 
								 ELSE p.itemPlanPE   = ? END
					/*	AND CASE WHEN ".$ideecc." = 0 OR ".$ideecc." = 6 THEN TRUE
								 ELSE p.idEmpresaColab = ".$ideecc." END */
								 ";
	    if($proy!=''){
	        $Query .= " AND py.idProyecto = ".$proy;
	    }
	    if($subPry!=''){
	        $Query .= " AND s.idSubProyecto = ".$subPry;
	    }
	    if($itemPlan!=''){
	        $Query .= " AND p.itemPlan ='".$itemPlan."' ";
	    }
	    if($nombreproyecto!=''){
	        $Query .= ' AND p.nombreProyecto LIKE "%'.$nombreproyecto.'%"';
	        //$Query .= " AND p.nombreProyecto LIKE '%".$nombreproyecto."%' ";
	    }
		if($gestorObra){
	        $Query .= '  AND u.nombre LIKE "%'.$gestorObra.'%"';
	        //$Query .= " AND p.nombreProyecto LIKE '%".$nombreproyecto."%' ";
	    }
	    if($tipoPlanta!=''){
	        $Query .= " AND s.idTipoPlanta = ".$tipoPlanta;
	    }
	    if($mesEjec!=NULL){
	        $Query .=  " AND EXTRACT(MONTH FROM DATE(p.fechaPrevEjec)) = $mesEjec";
	    }
	    if($idUsuario!=NULL) {
	        $Query .=  " AND p.itemplan IN ( SELECT l.itemplan
											   FROM log_planobra l
											  WHERE l.tipoPlanta = ".ID_TIPO_PLANTA_INTERNA."
												AND l.actividad  = 'ingresar'
												AND l.id_usuario = '".$idUsuario."'
												GROUP BY l.itemplan)";
	    }
	    if($idFase!=''){
	        $Query .= " AND p.idFase = ".$idFase;
	    }
		$Query .= "GROUP BY p.itemplan";
	    $result = $this->db->query($Query,array($itemMadre, $itemMadre, $itemMadre));
	    return $result;
	}  
	
	function getBandejaDeAdjudicacionXItemPlan($itemPlan){
	    
	    $Query = 'SELECT DISTINCT po.itemplan, po.indicador, sp.subProyectoDesc, po.fechaPrevEjec, po.idEstadoPlan,
                    count(DISTINCT CASE WHEN idEstacion = '.ID_ESTACION_COAXIAL.' THEN 1 ELSE NULL END) as coaxial,
                    count(DISTINCT CASE WHEN idEstacion = '.ID_ESTACION_FO.' THEN 1 ELSE NULL END) as fo
                    FROM planobra po, subproyecto sp, subproyectoestacion se, estacionarea ea
                    WHERE	po.idEstadoPlan = '.ID_ESTADO_PRE_DISENIO.'
                    AND 	ea.idEstacion IN ('.ID_ESTACION_COAXIAL.','.ID_ESTACION_FO.')
					AND 	po.fechaInicio != "0000-00-00"
                    AND		se.idEstacionArea = ea.idEstacionArea
                    AND		sp.idSubProyecto = se.idSubProyecto
                    AND		po.idSubProyecto = sp.idSubProyecto
					AND 	po.itemplan = ?
					GROUP BY po.itemplan;';
	    $result = $this->db->query($Query, array($itemPlan));
		// _log($this->db->last_query());
	    return $result;
	}
	
	function mostrarLog($itemPlan){
	    $Query = "SELECT ep.estadoPlanDesc, 
                    (CASE WHEN usu_upd IS NULL
                    	THEN (SELECT nombre FROM usuario WHERE id_usuario = c.usu_reg) 
                    	ELSE (SELECT nombre FROM usuario WHERE id_usuario = c.usu_upd OR usuario = c.usu_upd)END) usuario,
                    (CASE WHEN usu_upd IS NULL
                    	THEN fecha_reg 
                    	ELSE fecha_upd END) fecha_registro,
                    COALESCE((SELECT estadoPlanDesc FROM estadoplan WHERE idEstadoPlan = c.idEstadoPlanAnt),'') estado_ant_a_c_t_p,
	                COALESCE(m.motivoDesc,'') motivo,
                    COALESCE(descripcion,'') comentario
                    FROM control_estado_itemplan c
                    INNER JOIN estadoplan ep
                    ON c.idEstadoPlan = ep.idEstadoPlan
	                LEFT JOIN motivo m
                    ON m.idMotivo = c.idMotivo
                    WHERE itemplan = ?
                    ORDER BY itemplan, c.id_control_estado_itemplan ASC";
	    $result = $this->db->query($Query, array($itemPlan));
	    return $result;
	}
	
	function mostrarLog2_0($itemPlan){
	    $Query = "select (	           
	                CASE WHEN ci.idEstadoPlan IN(1,8)  THEN 'Registro'
					     WHEN ci.idEstadoPlan = 2  THEN 'Pre diseño'
        	             WHEN ci.idEstadoPlan = 19 THEN 'Diseño' 
                         WHEN ci.idEstadoPlan = 20 THEN 'En Licencia'
                         WHEN ci.idEstadoPlan = 3  THEN 'En Aprobacion'
                         WHEN ci.idEstadoPlan = 9  THEN 'En Obra'
                         WHEN ci.idEstadoPlan = 21 THEN 'Pre liquidado'
                         WHEN ci.idEstadoPlan = 4  THEN 'En Validacion'ELSE ep.estadoPlanDesc end) as estado_prev,
        	    ep.estadoPlanDesc, (CASE WHEN ci.idEstadoPlan IN (1,8) THEN COALESCE(ci.fecha_upd,ci.fecha_reg) ELSE ci.fecha_upd END) as fecha_upd,
        	    (CASE WHEN ci.idEstadoPlan IN (1,8) 
				THEN  (case when u.usuario is null then u2.usuario else u.usuario end)
				#THEN  (case when u2.usuario is null then ci.usu_reg else u2.usuario end)
				ELSE  (case when u.usuario is null then ci.usu_upd else u.usuario end) END) as usuario,
        	    COALESCE(m.motivoDesc,'') motivo,
                COALESCE(ci.descripcion,'') comentario
        	    from
        	    planobra po, estadoplan ep, control_estado_itemplan ci 
				LEFT JOIN usuario u  ON ci.usu_upd = u.id_usuario
				LEFT JOIN usuario u2 ON ci.usu_reg = u2.id_usuario
        	    LEFT JOIN motivo m
                ON m.idMotivo = ci.idMotivo
        	    where ci.itemplan = po.itemplan
        	    and ci.idEstadoPlan = ep.idEstadoPlan
        	    and po.itemplan = ?
        	    #and ci.idEstadoPlan NOT IN (1)
	            -- ORDER BY fecha_upd
			UNION ALL
				  SELECT 'Paralizado',
						 'Paralizado',
						 pa.fechaRegistro,
						 CASE WHEN u.nombre IS NULL THEN pa.nombreUsuarioTrama
                              ELSE u.nombre END as nombre,
						 mo.motivoDesc,
						 pa.comentario
					FROM (paralizacion pa,
						 motivo mo) 
			   LEFT JOIN usuario u ON (u.id_usuario = pa.idUsuario)
				  WHERE pa.idMotivo = mo.idMotivo
					AND flg_activo = 1
					AND pa.itemplan = ?
				ORDER BY fecha_upd";
	    $result = $this->db->query($Query, array($itemPlan, $itemPlan));
	    return $result;
	}
	
	
	function isContratoPaquetizadoItemPlan($itemPlan, $indicador=NULL, $itemMadre=NULL){
	    $Query = "SELECT COUNT(1) count FROM planobra 
	               WHERE itemplan = COALESCE(?,itemplan) 
		             AND paquetizado_fg IS NULL 
					 AND indicador = COALESCE(?, indicador)
					 AND CASE WHEN (? = '' OR ? IS NULL) THEN true 
					          ELSE itemPlanPE = ? END";
	    $result = $this->db->query($Query, array($itemPlan, $indicador, $itemMadre, $itemMadre, $itemMadre));
		//_log($this->db->last_query())
	    return $result->row_array();
	}

///////
        
        function getDataDiseno($itemplan) {
        $Query = "SELECT DISTINCT 
                         po.itemPlan,
                         sp.subProyectoDesc,
                         p.idProyecto,
                         p.idProyecto,po.itemPlan, 
                         e.idEstacion,
                         po.indicador ,
                         pd.estado,
                         po.idEstadoPlan,
                         (SELECT COUNT(1) FROM diseno_planobra_cluster poc WHERE poc.itemplan = ?) AS has_cotizacion,
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
						CASE WHEN DATE(fecha_creacion) > '2020-03-15' THEN CASE WHEN po.orden_compra IS NOT NULL THEN 1
						                                                        ELSE 0 END
						     ELSE 1 END as flg_orden_compra,
                        (SELECT jefatura 
                            FROM central c
                            WHERE c.idCentral = po.idCentral) jefatura,
                            (CASE WHEN p.idProyecto = " . ID_PROYECTO_SISEGOS . " THEN (SELECT    COUNT(1) as count  
                                FROM    sisego_planobra 
                            WHERE    itemplan = po.itemPlan 
                                AND    origen = " . ID_TIPO_OBRA_FROM_DISENIO . ") ELSE 0 END) as hasSisegoPlanObra,
                                pd.path_expediente_diseno,
                                pd.fecha_ejecucion,
                                pd.usuario_ejecucion,
                                (CASE WHEN pd.requiere_licencia = 2 THEN 'NO' ELSE 'SI' END) as licencia
                                  
                    FROM pre_diseno pd LEFT JOIN 
                         planobra po    ON (pd.itemplan      = po.itemplan) INNER JOIN
                         subproyecto sp ON (po.idSubProyecto = sp.idSubProyecto) INNER JOIN
                         proyecto     p ON (sp.idProyecto    = p.idProyecto) INNER JOIN
                         estacion     e ON (pd.idEstacion = e.idEstacion) INNER JOIN
                         estadoplan   ep ON (ep.idEstadoPlan = po.idEstadoPlan)
                     AND pd.estado in (2,3)
                     AND pd.itemplan = ?
                     AND pd.estado = 2
                     AND CASE WHEN sp.idTipoSubProyecto IS NOT NULL THEN sp.idTipoSubProyecto <> 2
                                        ELSE true END
                     #AND po.idEstadoPlan not IN (6,5,10)";
        $result = $this->db->query($Query, array($itemplan, $itemplan));
        return $result->result();
    }
	
	public function updateItemplan($itemplan,$arrayUpdate)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        try {
            $this->db->trans_begin();
            $this->db->where('itemplan', $itemplan);
            $this->db->update('planobra', $arrayUpdate);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al actualizar el planobra!!');
            } else {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se actualizó correctamente!!';
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();
            $data['msj'] = $e->getMessage();
        }

        return $data;
    }
	
	function getCountTicketInstalacionByCodigoUnico($codigoUnico,$itemplan){
	    
	    $sql = "  SELECT COUNT(*) total_ticket, 
					     SUM(CASE WHEN io.instalationorderStatus = 5 THEN 1 ELSE 0 END) cant_instalado
			        FROM inventario_db.instalationorder io
		           WHERE io.instalationorderStatus != 4
				     AND io._codigo_unico = ?
                     AND io.itemplan = ? ";
	    $result = $this->db->query($sql, array($codigoUnico,$itemplan));
	    return $result->row_array();
	}
	
	function getCostoTotalMoByItemplan($itemplan){
	    
	    $sql = "  SELECT ROUND(SUM(total), 2) AS costo_mo 
		            FROM ptr_x_actividades_x_zonal dp,
				         ptr_planta_interna po				  
	               WHERE dp.ptr = po.ptr
	                 AND po.itemplan = ?
                     AND po.rangoPtr <> 6 ";
	    $result = $this->db->query($sql, array($itemplan));
	    return $result->row_array()['costo_mo'];
	}

	function getPoMoByItemplan($itemplan){
	    
	    $sql = "  SELECT po.*
					FROM ptr_planta_interna po				  
				   WHERE po.itemplan = ?
				     AND po.rangoPtr <> 6 ";
	    $result = $this->db->query($sql, array($itemplan));
	    return $result->result();
	}

	public function updateBatchPo($listaPoUpdate) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->update_batch('ptr_planta_interna', $listaPoUpdate, 'ptr');
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al actualizar la tabla ptr_planta_interna.');
            } else {
				$data['error'] = EXIT_SUCCESS;
				$data['msj'] = 'Se actualizo correctamente!';
				$this->db->trans_commit();
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
}