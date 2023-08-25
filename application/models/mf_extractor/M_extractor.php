<?php

class M_extractor extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    function getFechaCargaMoMat()
    {
        $sql = "  SELECT MAX(fecha)fecha 
                    FROM log_carga_offline
                   WHERE flg_carga = 1";
        $result = $this->db->query($sql);
        return $result->row_array()['fecha'];
    }

    function getReportePlanobra($idEECC)
    {
        $sql = " 
		
		   

			SELECT tb8.*,
						UPPER(tb9.descripcion) AS linea_opex,
						UPPER(tb9.concepto) AS concepto_opex,
						(SELECT nombre fROM zona z WHERE z.id_zona= idZona) zona
			 FROM
					(	 
			 
						SELECT tb7.*,
							   esc.Nombre AS nombre_sitio
						 FROM
						(
						  SELECT tb5.*,
								 (COALESCE(tb6.estado_sol,'SIN SOLICITUD OC')) AS estado_sol_certi
							
							  FROM 
								
							(	
							SELECT tb3.*,
								   COALESCE(tb4.estado_oc, 'SIN SOLICITUD OC') AS estado_oc
							   FROM 
									(
										 SELECT tb1.*,
																(   CASE WHEN tb1.idEstadoPlan = 9 AND tb1.costo_mo_pdt_val IS NOT NULL 
																					   THEN tb1.costo_mo_pdt_val 
																	   ELSE tb2.total END
												) costo_final_mo
														   FROM 
												(
																					SELECT      po.fecha_creacion,
																								po.fechaCancelacion,
																								po.itemplan,
																								UPPER(ep.estadoPlanDesc) estadoPlanDesc,
																								p.proyectoDesc,
																								sp.subProyectoDesc,
																								(CASE WHEN po.flg_opex = 1 THEN 'CAPEX' ELSE 'OPEX' END) AS tipo_obra,
																								f.faseDesc,
																								u.nombre AS gestor,
																								ec.empresaColabDesc,
																								con.alias AS contrato_padre,
																								con.contrato_marco,
																								po.fechaInicio,
																								po.costo_unitario_mo_crea_oc AS costo_inicial_mo,
																								(CASE WHEN (NOT po.fechaEjecucion IS NULL AND po.fechaEjecucion < '2018-07-07') 
																											THEN po.fechaEjecucion
																											ELSE DATE(po.fechaPreliquidacion) END) AS fechaEjecucion,
																								po.costo_sap,
																								po.orden_compra,
																								po.codigo_certificacion,
																								po.pep2,
																								po.ceco,
																								po.area_funcional,
																								po.codigo_unico AS codigoSitio,
																								po.cuenta,
																								po.nombreProyecto AS nombreCliente,
																								po.indicador,
																								po.idFase,
																								po.idSubProyecto,
																								tt.tipoPlantaDesc,
																								po.tipo_estudio,
																								po.segmento,
																								po.servicio,
																								po.velocidad,
																								con.tipo_moneda,
																								(CASE WHEN po.requiere_hardware = '1' THEN 'SI' ELSE 'NO' END) AS requiere_hardware,
																							    po.idEstadoPlan,
																							    po.costo_mo_pdt_val,
																								(CASE WHEN po.flg_presupuesto_oc IS NOT NULL THEN 'SI' ELSE 'NO' END) AS tiene_quiebre,
																								(CASE WHEN po.flg_presupuesto_oc IS NOT NULL THEN po.comentarioQuiebre ELSE null END) AS comentarioQuiebre,
																								po.idZona
															                               FROM (planobra po 
													                                  LEFT JOIN usuario u ON po.usuario_registro = u.id_usuario,
																								estadoplan ep,
																								subproyecto sp,
																								proyecto p,
																								fase f,
																								empresacolab ec,
																								contrato con,
																								tipoplanta_transporte tt)
																					      WHERE po.idEstadoPlan = ep.idEstadoPlan
																							AND po.idSubProyecto = sp.idSubProyecto
																							AND sp.idProyecto = p.idProyecto
																							AND po.idFase = f.idFase
																							AND po.idEmpresaColab = ec.idEmpresaColab
																							AND po.idContrato = con.id_contrato 
																							AND sp.idTipoPlanta = tt.idTipoPlanta
																					        AND CASE WHEN ? IN (0,6) THEN TRUE
																								ELSE po.idEmpresaColab = ? END
														) tb1 LEFT JOIN view_all_costo_po_mo_activo tb2 ON tb1.itemplan = tb2.itemplan
															) tb3 LEFT JOIN view_itemplan_estado_oc_capex_opex tb4 ON tb3.itemplan = tb4.itemplan
																						
									)	tb5  LEFT JOIN view_itemplan_estado_oc_certi_capex_opex tb6 ON tb5.itemplan = tb6.itemplan 
									
							) tb7 LEFT JOIN sam_db_020123.estacionconsolidado esc ON (tb7.codigoSitio = esc.CodigoUnico COLLATE utf8_general_ci)															
					) tb8 LEFT JOIN (	SELECT slc.idSubroLineaCom,slc.idcombinatoria,slc.idSubProyecto,
											   slc.idlineaopex_fase,lof.idlineaopex,lof.idfase,lo.descripcion,lo.concepto 
										  FROM subproyecto_lineaopex_combinatoria slc,
											   lineaopex_fase lof,
											   lineaopex lo
										 WHERE slc.idlineaopex_fase  = lof.idlineaopex_fase
										   AND lof.idlineaopex = lo.idlineaopex
									) tb9 ON tb8.idSubProyecto = tb9.idSubProyecto AND tb8.idFase = tb9.idfase
                ";
        $result = $this->db->query($sql, array($idEECC,$idEECC));
        return $result->result();
    }

    function getReporteOC($idEECC)
    {
        $sql = "         SELECT tb2.*,
        UPPER(tb3.descripcion) AS linea_opex,
        UPPER(tb3.concepto) AS concepto_opex
   FROM
   (
               
       SELECT tb1.*,
           esc.Nombre AS nombre_sitio		
       FROM
               (
                   SELECT ixs.itemplan,
                               soc.codigo_solicitud,
                                   soc.tipo_solicitud,
                                   (CASE WHEN soc.tipo_solicitud = 1 THEN 'CREACIÓN'
                                           WHEN soc.tipo_solicitud = 2 THEN 'EDICIÓN'
                                               WHEN soc.tipo_solicitud = 3 THEN 'CERTIFICACIÓN'
                                               END 
                                   ) AS tipo_oc,
                                   soc.fecha_creacion,
                                   soc.fecha_valida,
                                   soc.pep1,
                                   soc.pep2,
                                   soc.cesta,
                                   soc.orden_compra,
                                   soc.codigo_certificacion,
                                   soc.estado,
                                   (CASE WHEN soc.estado = 1 THEN 'PENDIENTE'
                                           WHEN soc.estado = 2 THEN 'ATENDIDO'
                                               WHEN soc.estado = 3 THEN 'CANCELADO'
                                               WHEN soc.estado = 4 THEN 'EN ESPERA DE EDICIÓN'
                                               WHEN soc.estado = 5 THEN 'EN ESPERA DE ACTA'
                                               END 
                                   ) AS estado_sol,
                                   ixs.costo_unitario_mo,
                                   p.proyectoDesc,
                                   sp.subProyectoDesc,
                                   (CASE WHEN po.flg_opex = 1 THEN 'CAPEX' ELSE 'OPEX' END) AS tipo_obra,
                                   po.codigo_unico AS codigoSitio,
                                   ec.empresaColabDesc,
                                   con.alias AS contrato_padre,
                   con.contrato_marco,
                   con.tipo_moneda,
                                   '' AS ceco,
                                   '' AS cuenta,
                                   '' AS area_funcional,
                                   po.idSubProyecto,
                                   po.idFase,
                                   ixs.posicion
                   FROM solicitud_orden_compra soc,
                               itemplan_x_solicitud_oc ixs,
                                   planobra po,
                                   subproyecto sp,
                                   proyecto p,
                                   empresacolab ec,
                                   contrato con
                       WHERE soc.codigo_solicitud = ixs.codigo_solicitud_oc
                           AND ixs.itemplan = po.itemplan
                           AND po.idSubProyecto = sp.idSubProyecto
                           AND sp.idProyecto = p.idProyecto
                           AND po.idEmpresaColab = ec.idEmpresaColab
                           AND po.idContrato = con.id_contrato
                           AND soc.tipo_solicitud IN (1,2,3)
						   AND CASE WHEN ? IN (0,6) THEN TRUE ELSE po.idEmpresaColab = ? END
                   #ORDER BY 1,2,6
           UNION ALL
           SELECT ixs.itemplan,
                               soc.codigo_solicitud,
                                   soc.tipo_solicitud,
                                   (CASE WHEN soc.tipo_solicitud = 1 THEN 'CREACIÓN'
                                           WHEN soc.tipo_solicitud = 2 THEN 'EDICIÓN'
                                               WHEN soc.tipo_solicitud = 3 THEN 'CERTIFICACIÓN'
                                               END 
                                   ) AS tipo_oc,
                                   soc.fecha_creacion,
                                   soc.fecha_valida,
                                   soc.pep1,
                                   soc.pep2,
                                   soc.cesta,
                                   soc.orden_compra,
                                   soc.codigo_certificacion,
                                   soc.estado,
                                   (CASE WHEN soc.estado = 1 THEN 'PENDIENTE'
                                           WHEN soc.estado = 2 THEN 'ATENDIDO'
                                               WHEN soc.estado = 3 THEN 'CANCELADO'
                                               WHEN soc.estado = 4 THEN 'EN ESPERA DE EDICIÓN'
                                               WHEN soc.estado = 5 THEN 'EN ESPERA DE ACTA'
                                               END 
                                   ) AS estado_sol,
                                   ixs.costo_unitario_mo,
                                   p.proyectoDesc,
                                   sp.subProyectoDesc,
                                   (CASE WHEN po.flg_opex = 1 THEN 'CAPEX' ELSE 'OPEX' END) AS tipo_obra,
                                   po.codigo_unico AS codigoSitio,
                                   ec.empresaColabDesc,
                                   con.alias AS contrato_padre,
                   con.contrato_marco,
                   con.tipo_moneda,
                                   soc.ceco,
                                   soc.cuenta,
                                   soc.area_funcional,
                                   po.idSubProyecto,
                                   po.idFase,
                                   ixs.posicion
                   FROM itemplan_solicitud_orden_compra soc,
                               itemplan_x_solicitud_oc ixs,
                                   planobra po,
                                   subproyecto sp,
                                   proyecto p,
                                   empresacolab ec,
                                   contrato con
                       WHERE soc.codigo_solicitud = ixs.codigo_solicitud_oc
                           AND ixs.itemplan = po.itemplan
                           AND po.idSubProyecto = sp.idSubProyecto
                           AND sp.idProyecto = p.idProyecto
                           AND po.idEmpresaColab = ec.idEmpresaColab
                           AND po.idContrato = con.id_contrato
                           AND soc.tipo_solicitud IN (1,2,3)
						   AND CASE WHEN ? IN (0,6) THEN TRUE ELSE po.idEmpresaColab = ? END
                   #ORDER BY 1,2,6
           ) tb1 
   LEFT JOIN sam_db_020123.estacionconsolidado esc ON (tb1.codigoSitio = esc.CodigoUnico  COLLATE utf8_general_ci)
   
   ) tb2 LEFT JOIN (
           SELECT slc.idSubroLineaCom,slc.idcombinatoria,slc.idSubProyecto,
                       slc.idlineaopex_fase,lof.idlineaopex,lof.idfase,lo.descripcion,lo.concepto 
               FROM subproyecto_lineaopex_combinatoria slc,
                       lineaopex_fase lof,
                           lineaopex lo
               WHERE slc.idlineaopex_fase  = lof.idlineaopex_fase
                   AND lof.idlineaopex = lo.idlineaopex
       ) tb3 ON tb2.idSubProyecto = tb3.idSubProyecto AND tb2.idFase = tb3.idfase
   ORDER BY 1,2,6";
        $result = $this->db->query($sql, array($idEECC,$idEECC,$idEECC,$idEECC));
        return $result->result();
    }


    function getReportePartidas($idEECC)
    {
        $sql = " SELECT pp.itemplan,
                        pp.ptr,
                        pa.codigo,
                        UPPER(ptraz.descripcion) as descPartida,
                        cantidad as cantidad_inicial,
                        ptraz.cantidad_final,
                        ptraz.baremo,
                        ptraz.total,
                        ptraz.costo_mo,
                        ptraz.precio,
                        pa.idActividad,
                        po.idContrato,
                        po.idEmpresaColab,
                        co.contrato_marco,
                        cp.nombre AS contrato_padre,
                        co.tipo_moneda
                   FROM ptr_planta_interna pp,
                        ptr_x_actividades_x_zonal ptraz,
                        partidas pa,
                        planobra po,
                        contrato co,
                        contrato_padre cp
                  WHERE pp.ptr = ptraz.ptr
                    AND ptraz.id_actividad = pa.idActividad
                    AND pp.itemplan = po.itemplan
                    AND po.idContrato = co.id_contrato
                    AND co.id_contrato_padre = cp.id_contrato_padre
					AND CASE WHEN ? IN (0,6) THEN TRUE ELSE po.idEmpresaColab = ? END
                ORDER BY 1,2,3 ";
        $result = $this->db->query($sql, array($idEECC,$idEECC));
        return $result->result();
    }

    function getLicencia($idTipoEntidad,$idEECC)
    {
        $sql = "SELECT ei.itemplan,
					   ei.montoComp,
					   ei.nroComprobante,
					   ei.fechaEmisionComp,
					   ei.fechaExp,
					   ei.nroExpediente,
					   ei.fechaInicio,
					   ei.fechaFin,
					   e.desc_entidad,
					   (CASE WHEN ei.estado IS NULL THEN 'PENDIENTE'
							 WHEN ei.estado = 1 THEN 'GESTIONADA'
							 WHEN ei.estado = 2 THEN 'CONCLUIDA' END) estadoLic,
						UPPER(t.nombre) as tipoEntidadDesc,
						UPPER(distritoDesc) distritoDesc,
						UPPER(es.estadoPlanDesc) estadoPlanDesc,
						UPPER(u.nombre) as usuarioExp,
						em.empresaColabDesc
				  FROM entidad_itemplan_estacion ei,
					   entidad e,
					   tipo_entidad t,
					   distrito d,
					   planobra po,
					   estadoplan es,
					   usuario u,
					   empresacolab em
				 WHERE ei.idEntidad = e.idEntidad
				   AND idUsuarioExp = u.id_usuario
				   AND t.id_tipo_entidad = ei.idTipoEntidad
				   AND t.id_tipo_entidad = COALESCE(?, t.id_tipo_entidad)
				   AND ei.idDistrito  = d.idDistrito
				   AND po.itemplan = ei.itemplan
				   AND es.idEstadoPlan = po.idEstadoPlan
				   AND em.idEmpresaColab = po.idEmpresaColab
				   AND CASE WHEN ? IN (0,6) THEN TRUE ELSE po.idEmpresaColab = ? END
				   
				   ";
        $result = $this->db->query($sql, array($idTipoEntidad,$idEECC,$idEECC));
        return $result->result_array();
    }

    function getEntidadAmbiental($idEntidad,$idEECC)
    {
        $sql = "SELECT t1.itemPlan AS itemplan,
        t4.estadoPlanDesc,
        t3.empresaColabDesc,
        t5.desc_entidad,
        t1.nroExpediente,
        t6.nombre,
        t7.distritoDesc,
        t1.fechaInicio,
        t1.fechaFin,
        t10.nombre AS id_usuario_reg_lic,
        t1.fechaExp AS fecha_expediente,
        t9.nroComprobante,
        t9.fechaEmisionComprobante,
        t9.montoComprobante,
        t9.fechaRegistroComprobante,
        t1.planCompromiso,
        t1.medidasCompromiso,
        t8.entidadEstadoDesc
        FROM entidad_itemplan_estacion t1
        JOIN planobra t2 ON t1.itemPlan = t2.itemPlan
        JOIN empresacolab t3 ON t2.idEmpresaColab = t3.idEmpresaColab
        JOIN estadoplan t4 ON t2.idEstadoPlan = t4.idEstadoPlan
        JOIN entidad t5 ON t1.idEntidad = t5.idEntidad
        LEFT JOIN tipo_entidad t6 ON t1.idTipoEntidad = t6.id_tipo_entidad
        LEFT JOIN distrito t7 ON t1.idDistrito = t7.idDistrito
        LEFT JOIN entidad_estado t8 ON t1.idEntidadEstado = t8.idEntidadEstado
        LEFT JOIN entidad_comprobante t9 ON t1.id = t9.idEntidadEstacion
        LEFT JOIN usuario t10 ON t10.id_usuario = t1.idUsuarioExp
        WHERE t1.idEntidad = COALESCE(?, t1.idEntidad)
		  AND CASE WHEN ? IN (0,6) THEN TRUE ELSE t2.idEmpresaColab = ? END
        ORDER BY t1.id DESC";
        $result = $this->db->query($sql, [$idEntidad,$idEECC,$idEECC]);
        return $result->result_array();
    }

    function getCompromisoEntidad($idEECC)
    {
        $sql = "SELECT ei.itemplan,
                       cen.idEntidadEstacion,
                       cen.idCompromiso,
                       c.compromisoDesc,
                       cen.fechaInicioCompromiso,
                       cen.fechaFinCompromiso,
                       ces.compromisoEstadoDesc,
                       cus.nombreUsuarioCompromiso,
                       ei.planCompromiso,
                       ei.medidasCompromiso
                  FROM entidad_itemplan_estacion ei,
                       compromiso_entidad cen,
                       compromiso_estado ces,
                       compromiso_usuario cus,
                       compromiso c,
					   planobra po
                  WHERE ei.id = cen.idEntidadEstacion 
                  AND cen.idEstadoCompromiso = ces.idEstadoCompromiso
                  AND cen.idUsuarioCompromiso = cus.idUsuarioCompromiso
                  AND cen.idCompromiso = c.idCompromiso
				  AND ei.itemplan = po.itemplan
				  AND CASE WHEN ? IN (0,6) THEN TRUE ELSE po.idEmpresaColab = ? END
				  ";
        $result = $this->db->query($sql,array($idEECC,$idEECC));
        return $result->result_array();
    }

    function getEntidadEvidencia($idEECC)
    {
        $sql = "SELECT ei.itemplan,
                       et.descripcionTipoEvidencia,
                       e.descripcion,
                       e.fechaInicio,
                       e.fechaFin
                  FROM entidad_itemplan_estacion ei,
                       entidad_evidencia e,
                       entidad_tipo_evidencia et,
					   planobra po
                 WHERE ei.id = e.idEntidadEstacion
                   AND e.idEntidadTipoEvidencia = et.idEntidadTipoEvidencia
				   AND ei.itemplan = po.itemplan
				   AND CASE WHEN ? IN (0,6) THEN TRUE ELSE po.idEmpresaColab = ? END
				   ";
        $result = $this->db->query($sql,array($idEECC,$idEECC));
        return $result->result_array();
    }
	
	function getReporteExtractorOcFirma($iddEECC, $idUsuario)
    {
        $sql = "
				SELECT t.itemplan,
					   t.codigo_solicitud_oc,
					   t.estadoFirmaDesc,
					   t.empresaColabDesc,
					   t.costo_unitario_mo,
					   tt.nombreUsuario,
					   t.idSubProyecto,
					   t.subProyectoDesc,
					   (SELECT fechaRegistro 
						 FROM log_solicitud_firma lg
						 WHERE lg.codigo_solicitud = t.codigo_solicitud_oc
						   AND lg.idEstadoFirma = t.idEstadoFirma) fecha
				  FROM (SELECT ixs.itemplan,
							  ixs.codigo_solicitud_oc,
							  es.estadoFirmaDesc,
							  po.idEmpresaColab,
							  po.idSubProyecto,
							  su.subProyectoDesc,
							  es.idEstadoFirma,
							  e.empresaColabDesc,
							  ixs.costo_unitario_mo
						 FROM itemplan_x_solicitud_oc ixs,
							  solicitud_orden_compra s,
							  estado_firma es,
							  planobra po,
							  empresacolab e,
							  subproyecto su
						WHERE e.idEmpresaColab = po.idEmpresaColab
						  AND po.itemplan = ixs.itemplan
						  AND es.idEstadoFirma = s.idEstadoFirma
						  AND ixs.codigo_solicitud_oc = s.codigo_solicitud
						  AND su.idSubProyecto = po.idSubProyecto
						  AND s.idEstadoFirma = 1
						  AND CASE WHEN ? IN ( 0, 6 ) OR ? IS NULL THEN TRUE ELSE po.idEmpresaColab = ? END)t,
						  
						  (SELECT uxr.idUsuario, 
										UPPER(u.nombre) nombreUsuario,
										r.idRol,
										id_eecc,
										null as idSubProyecto,
										null as subProyectoDesc
								  FROM usuario_x_rol uxr,
									   usuario u,
									   rol r
								 WHERE uxr.idUsuario = u.id_usuario
								   AND uxr.idRol = r.idRol
								   AND u.estado = 1
								   AND r.idRol = 3)tt
					WHERE t.idEmpresaColab = tt.id_eecc
					
				UNION ALL 
					
					
				SELECT t.itemplan,
					   t.codigo_solicitud_oc,
					   t.estadoFirmaDesc,
					   t.empresaColabDesc,
					   t.costo_unitario_mo,
					   tt.nombreUsuario,
					   t.idSubProyecto ,
					   tt.subProyectoDesc,
					   (SELECT fechaRegistro 
						 FROM log_solicitud_firma lg
						 WHERE lg.codigo_solicitud = t.codigo_solicitud_oc
						   AND lg.idEstadoFirma = t.idEstadoFirma
						  limit 1) fecha
				  FROM (SELECT ixs.itemplan,
							  ixs.codigo_solicitud_oc,
							  es.estadoFirmaDesc,
							  po.idEmpresaColab,
							  po.idSubProyecto,
							  es.idEstadoFirma,
							  e.empresaColabDesc,
							  ixs.costo_unitario_mo
						 FROM itemplan_x_solicitud_oc ixs,
							  solicitud_orden_compra s,
							  estado_firma es,
							  planobra po,
							  empresacolab e
						WHERE e.idEmpresaColab = po.idEmpresaColab
						  AND po.itemplan = ixs.itemplan
						  AND es.idEstadoFirma = s.idEstadoFirma
						  AND ixs.codigo_solicitud_oc = s.codigo_solicitud
						  AND CASE WHEN ? IN ( 0, 6 ) OR ? IS NULL THEN TRUE ELSE po.idEmpresaColab = ? END
						  AND s.idEstadoFirma IN (2,3,4,5))t,
						  
						  (SELECT uxr.idUsuario, 
										UPPER(u.nombre) nombreUsuario,
										r.idRol,
										id_eecc,
										s.idSubProyecto,
										s.subProyectoDesc
								  FROM (usuario_x_rol uxr,
										usuario u,
										rol r)
							 LEFT JOIN (usuario_x_subproyecto_valida_acta uxa,
										subproyecto s)
									ON uxa.idUsuario = uxr.idUsuario 
								   AND s.idSubProyecto = uxa.idSubProyecto
								   AND uxr.idRol = uxa.idRol
								 WHERE uxr.idUsuario = u.id_usuario
								   AND uxr.idRol = r.idRol
								   AND u.estado = 1
								   AND r.idRol IN (4,2,1))tt
					WHERE CASE WHEN t.idEstadoFirma = 2 THEN tt.idRol = 4 AND t.idSubProyecto = tt.idSubProyecto
							  WHEN t.idEstadoFirma = 3 THEN tt.idRol = 2 AND t.idSubProyecto = tt.idSubProyecto
							  WHEN t.idEstadoFirma IN (4,5) THEN tt.idRol = 1 AND t.idSubProyecto = tt.idSubProyecto END";
        $result = $this->db->query($sql, array($iddEECC, $iddEECC, $iddEECC, $iddEECC, $iddEECC, $iddEECC));
        return $result->result();
    }
	
	function getReportePartidaSBE($iddEECC, $idUsuario)
    {
        $sql = "
				SELECT pxs.codigo,
					   UPPER(pa.descripcion) partidaDesc,
					   UPPER(s.subProyectoDesc) subProyectoDesc,
					   UPPER(e.empresaColabDesc) empresaColabDesc,
					   z.nombre as zona,
					   pxs.costo
				  FROM partida_x_zona_x_empresacolab_x_subproyecto pxs,
					   partidas pa,
					   subproyecto s,
					   empresacolab e,
					   zona z
				 WHERE pxs.id_partida = pa.idActividad
				   AND pxs.idSubProyecto = s.idSubProyecto
				   AND e.idEmpresaColab = pxs.idEmpresaColab
				   AND z.id_zona = pxs.idZona
				   AND CASE WHEN ? IN ( 0, 6 ) OR ? IS NULL THEN TRUE ELSE FALSE END";
        $result = $this->db->query($sql, array($iddEECC, $iddEECC));
        return $result->result();
    }
	
	function getReporteExtractorOcFirmaItemMadre($iddEECC, $idUsuario) {
		$sql = "SELECT t.itemplan_m,
					   t.codigo_solicitud_oc,
					   t.estadoFirmaDesc,
					   t.empresaColabDesc,
					   t.costo_unitario_mo,
					   tt.nombreUsuario,
					   t.idSubProyecto,
					   t.subProyectoDesc,
					   (SELECT fechaRegistro 
						 FROM log_solicitud_firma lg
						 WHERE lg.codigo_solicitud = t.codigo_solicitud_oc
						   AND lg.idEstadoFirma = t.idEstadoFirma) fecha
				  FROM (SELECT ixs.itemplan_m,
							  ixs.codigo_solicitud_oc,
							  es.estadoFirmaDesc,
							  po.idEmpresaColab,
							  po.idSubProyecto,
							  su.subProyectoDesc,
							  es.idEstadoFirma,
							  e.empresaColabDesc,
							  ixs.costo_unitario_mo
						 FROM itemplan_madre_x_solicitud_oc_madre ixs,
							  itemplan_madre_solicitud_orden_compra s,
							  estado_firma es,
							  itemplan_madre po,
							  empresacolab e,
							  subproyecto su
						WHERE e.idEmpresaColab = po.idEmpresaColab
						  AND po.itemplan_m = ixs.itemplan_m
						  AND es.idEstadoFirma = s.idEstadoFirma
						  AND ixs.codigo_solicitud_oc = s.codigo_solicitud
						  AND su.idSubProyecto = po.idSubProyecto
						  AND s.idEstadoFirma = 1
						  AND CASE WHEN ? IN ( 0, 6 ) OR ? IS NULL THEN TRUE ELSE po.idEmpresaColab = ? END)t,
						  
						  (SELECT uxr.idUsuario, 
										UPPER(u.nombre) nombreUsuario,
										r.idRol,
										id_eecc,
										null as idSubProyecto,
										null as subProyectoDesc
								  FROM usuario_x_rol uxr,
									   usuario u,
									   rol r
								 WHERE uxr.idUsuario = u.id_usuario
								   AND uxr.idRol = r.idRol
								   AND u.estado = 1
								   AND r.idRol = 3)tt
					WHERE t.idEmpresaColab = tt.id_eecc
                    
                    UNION ALL 
					
					
				SELECT t.itemplan_m,
					   t.codigo_solicitud_oc,
					   t.estadoFirmaDesc,
					   t.empresaColabDesc,
					   t.costo_unitario_mo,
					   tt.nombreUsuario,
					   t.idSubProyecto ,
					   tt.subProyectoDesc,
					   (SELECT fechaRegistro 
						 FROM log_solicitud_firma lg
						 WHERE lg.codigo_solicitud = t.codigo_solicitud_oc
						   AND lg.idEstadoFirma = t.idEstadoFirma
						  limit 1) fecha
				  FROM (SELECT ixs.itemplan_m,
							  ixs.codigo_solicitud_oc,
							  es.estadoFirmaDesc,
							  po.idEmpresaColab,
							  po.idSubProyecto,
							  es.idEstadoFirma,
							  e.empresaColabDesc,
							  ixs.costo_unitario_mo
						 FROM itemplan_madre_x_solicitud_oc_madre ixs,
							  itemplan_madre_solicitud_orden_compra s,
							  estado_firma es,
							  itemplan_madre po,
							  empresacolab e
						WHERE e.idEmpresaColab = po.idEmpresaColab
						  AND po.itemplan_m = ixs.itemplan_m
						  AND es.idEstadoFirma = s.idEstadoFirma
						  AND ixs.codigo_solicitud_oc = s.codigo_solicitud
						  AND CASE WHEN ? IN ( 0, 6 ) OR ? IS NULL THEN TRUE ELSE po.idEmpresaColab = ? END
						  AND s.idEstadoFirma IN (2,3,4,5))t,
						  
						  (SELECT uxr.idUsuario, 
										UPPER(u.nombre) nombreUsuario,
										r.idRol,
										id_eecc,
										s.idSubProyecto,
										s.subProyectoDesc
								  FROM (usuario_x_rol uxr,
										usuario u,
										rol r)
							 LEFT JOIN (usuario_x_subproyecto_valida_acta uxa,
										subproyecto s)
									ON uxa.idUsuario = uxr.idUsuario 
								   AND s.idSubProyecto = uxa.idSubProyecto
								   AND uxr.idRol = uxa.idRol
								 WHERE uxr.idUsuario = u.id_usuario
								   AND uxr.idRol = r.idRol
								   AND u.estado = 1
								   AND r.idRol IN (4,2,1))tt
					WHERE CASE WHEN t.idEstadoFirma = 2 THEN tt.idRol = 4 AND t.idSubProyecto = tt.idSubProyecto
							  WHEN t.idEstadoFirma = 3 THEN tt.idRol = 2 AND t.idSubProyecto = tt.idSubProyecto
							  WHEN t.idEstadoFirma IN (4,5) THEN tt.idRol = 1 AND t.idSubProyecto = tt.idSubProyecto END";
		$result = $this->db->query($sql, array($iddEECC, $iddEECC, $iddEECC, $iddEECC, $iddEECC, $iddEECC));
        return $result->result();
	}
	
	function getDataSolicitudOcFijaMasDepliegue($iddEECC) {
		$sql = "SELECT s.codigo_solicitud,
					   ixs.costo_unitario_mo,
					   ixs.itemplan,
					   s.orden_compra,
					   s.pep1,
					   s.costo_sap,
					   tc.capaDesc,
					   p.proyectoDesc,
					   s.fecha_creacion,
					   s.fecha_valida
				  FROM gics_sisego.solicitud_orden_compra s,
					   gics_sisego.planobra po,
					   gics_sisego.itemplan_x_solicitud_oc ixs,
					   gics_sisego.subproyecto su,
					   gics_sisego.proyecto p,
					   gics_sisego.t_capa_presupuestal tc
				 WHERE tipo_solicitud  = 3
				   AND s.estado = 1
				   AND po.itemplan = ixs.itemplan
				   AND ixs.codigo_solicitud_oc = s.codigo_solicitud
				   AND flg_presupuesto_oc IS NULL
				   AND su.idSubProyecto = po.idSubProyecto
				   AND p.idProyecto  = su.idProyecto
				   AND tc.idCapa     = p.IdCapa
				   AND CASE WHEN ? IN ( 0, 6 ) OR ? IS NULL THEN TRUE ELSE po.idEmpresaColab = ? END
				UNION ALL
				SELECT s.codigo_solicitud,
					   ixs.costo_unitario_mo,
					   ixs.itemplan,
					   s.orden_compra,
					   s.pep1,
					   s.costo_sap,
					   null as capaDesc,
					   p.proyectoDesc,
					   s.fecha_creacion,
					   s.fecha_valida
				  FROM transporte_db.solicitud_orden_compra s,
					   transporte_db.planobra po,
					   transporte_db.itemplan_x_solicitud_oc ixs,
					   transporte_db.subproyecto su,
					   transporte_db.proyecto p
				 WHERE tipo_solicitud  = 3
				   AND s.estado = 1
				   AND po.itemplan = ixs.itemplan
				   AND ixs.codigo_solicitud_oc = s.codigo_solicitud
				   AND flg_presupuesto_oc IS NULL
				   AND su.idSubProyecto = po.idSubProyecto
				   AND p.idProyecto     = su.idProyecto
				   AND CASE WHEN ? IN ( 0, 6 ) OR ? IS NULL THEN TRUE ELSE po.idEmpresaColab = ? END";
		$result = $this->db->query($sql, array($iddEECC, $iddEECC, $iddEECC, $iddEECC, $iddEECC, $iddEECC));
        return $result->result();
	}
}
