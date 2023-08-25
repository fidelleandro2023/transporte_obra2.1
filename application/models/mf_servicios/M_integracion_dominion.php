<?php
class M_integracion_dominion extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
	function getMatToReporteDomion(){
	   $Query = "SELECT 
                        ppo.itemplan,
                        ppo.codigo_po,
                        ppd.codigo_material,
                        m.descrip_material,
                        ppd.cantidad_ingreso,
                        ppd.cantidad_final,
                         (SELECT areaDesc
                           FROM estacionarea ea,
                                area a
                          WHERE ea.idEstacion = ppo.idEstacion
                            AND ea.idArea   = a.idArea
                            AND tipoArea    = 'MAT'
                        limit 1)area
                    FROM
                        planobra_po ppo,
                        planobra_po_detalle ppd,
                        material m
                    WHERE
                        ppo.codigo_po = ppd.codigo_po
                            AND ppd.codigo_material = m.id_material
	                       AND ppo.id_eecc_reg = ".ID_EECC_DOMINION."
                    ORDER BY ppo.itemplan;";
        $result = $this->db->query($Query);
        return $result->result();
	}
	
	function getMoToReporteDomion() {
	    $sql = "SELECT  po.itemplan,
                        po.codigo_po,
                        pa.codigo AS codigo_partida,
                        pa.descripcion AS partidaDesc,
                        ppd.baremo,
                        ppd.cantidad_final,
                        ppd.costo,
                        ppd.monto_final,
                        po.idEstacion,
                        pre.descPrecio,
                        a.areaDesc AS area,
                       (SELECT estado
                          FROM po_estado
                         WHERE idPoEstado = po.estado_po)po_estado,
                        ep.estadoPlanDesc
                   FROM planobra_po_detalle_mo ppd,
                        planobra_po po,
                        planobra ppo,
                        partidas pa,
                        detalleplan dp,
                        precio_diseno pre,
                        subproyectoestacion se,
                        estacionarea ea,
                        area a,
                        estadoplan ep
                  WHERE pa.idActividad     = ppd.idActividad
                    AND po.codigo_po       = ppd.codigo_po
                    AND ppo.itemplan       = po.itemplan
                    AND pre.idPrecioDiseno = pa.idPrecioDiseno
                    AND dp.itemplan        = ppo.itemplan
                    AND dp.poCod           = po.codigo_po
                    AND dp.idSubProyectoEstacion = se.idSubProyectoEstacion
                    AND ppo.idSubProyecto  = se.idSubProyecto
                    AND se.idEstacionArea = ea.idEstacionArea
                    AND ea.idEstacion      = po.idEstacion
                    AND ea.idArea          = a.idArea
                    AND ep.idEstadoPlan = ppo.idEstadoPlan
	                AND po.id_eecc_reg = ".ID_EECC_DOMINION."
                    UNION ALL
                    SELECT DISTINCT po.itemplan,
                        po.codigo_po,
                        pa.codigo AS codigo_partida,
                        pa.descripcion AS partidaDesc,
                        ppd.baremo,
                        ppd.cantidad,
                        ppd.costo,
                        ppd.total AS monto_final,
                        po.idEstacion,
                        pre.descPrecio,
                        a.areaDesc AS area,
                        (SELECT estado
                          FROM po_estado
                         WHERE idPoEstado = po.estado_po)po_estado,
                        ep.estadoPlanDesc
                   FROM planobra_po_detalle_partida ppd,
                        planobra_po po,
                        planobra ppo,
                        partidas pa,
                        precio_diseno pre,
                        estacionarea ea,
						area a,
                        subproyectoestacion se,
                        detalleplan dp,
                        estadoplan ep
                  WHERE pa.idActividad     = ppd.idPartida
                    AND ppo.itemplan       = po.itemplan
                    AND po.codigo_po       = ppd.codigo_po
                   -- AND pre.idPrecioDiseno = pa.idPrecioDiseno
                    AND ppd.idPrecioDiseno = pre.idPrecioDiseno
                    AND ea.idEstacion      = po.idEstacion
                    AND ea.idArea          = a.idArea
                    AND dp.itemplan        = ppo.itemplan
                    AND dp.poCod           = po.codigo_po
                    AND dp.idSubProyectoEstacion = se.idSubProyectoEstacion
                    AND ppo.idSubProyecto  = se.idSubProyecto
                    AND se.idEstacionArea = ea.idEstacionArea
                    AND ep.idEstadoPlan   = ppo.idEstadoPlan
                    AND po.id_eecc_reg = ".ID_EECC_DOMINION;
	    $result = $this->db->query($sql);
	    return $result->result();
	}
	
	public function getDetallePlanToDominion()
	{
	    $Query = "SELECT                    detalleplan.itemPlan,
                                            detalleplan.poCod,
                                            area.areaDesc,
                                            proyecto.proyectoDesc,
                                            subproyecto.subProyectoDesc,
                                            planobra.indicador,
                                            date(planobra.fecha_creacion) as fecha_registro,
                                            planobra.fechaInicio,
                                            planobra.fechaPrevEjec,
                                            planobra.fechaEjecucion,
                                            date(planobra.fechaPreLiquidacion) as fechaPreLiquidacion,
                                            planobra.fechaCancelacion,
                                            web_unificada.est_innova,
                                            web_unificada.titulo_trabajo,
                                            web_unificada.jefatura,
                                            web_unificada.zonal,
                                            web_unificada.mdf,
                                            web_unificada.grafo,
                                            'DOMINION' as eecc,
                                            detalleplan.oc,
                                            detalleplan.ncert,
                                            web_unificada.valoriz_m_o,
                                            web_unificada.valoriz_material,
                                            web_unificada.vr,
                                            web_unificada.f_ult_est,
                                            web_unificada.f_creac_prop,
                                            web_unificada.usu_registro,
                                            estadoplan.estadoPlanDesc,
                                            fase.faseDesc,
                                            web_unificada.distrito
                FROM detalleplan, web_unificada, planobra, fase, estadoplan, subproyecto, proyecto, zonal,
                subproyectoestacion, estacionarea, area
                WHERE detalleplan.poCod = web_unificada.ptr
                AND detalleplan.itemPlan=planobra.itemPlan
                AND planobra.idFase = fase.idFase
                AND estadoplan.idEstadoPlan=planobra.idEstadoPlan
                AND planobra.idSubProyecto = subproyecto.idSubProyecto
                AND subproyecto.idProyecto = proyecto.idProyecto
                AND planobra.idzonal=zonal.idzonal
                AND detalleplan.idSubProyectoEstacion=subproyectoestacion.idSubProyectoEstacion
                AND subproyectoestacion.idEstacionArea=estacionarea.idEstacionArea
                AND estacionarea.idArea=area.idArea
                AND web_unificada.eecc = 'DOMINIONPERU SOLUCIONES Y SERVICIOS S.A.C.'
                UNION ALL
                SELECT dp.itemplan,
                        dp.poCod,
                        a.areaDesc,
                        p.proyectoDesc,
                        sp.subProyectoDesc,
                        po.indicador,
                        (DATE(po.fecha_creacion)) AS fecha_registro,
                        po.fechaInicio,
                        po.fechaPrevEjec,
                        po.fechaEjecucion,
                        DATE(po.fechaPreLiquidacion) AS fechaPreLiquidacion,
                        po.fechaCancelacion,
                        UPPER(poe.estado) AS est_innova,
                        '-' AS titulo_trabajo,
                        c.jefatura AS jefatura,
                        z.zonalDesc AS zonal,
                        c.codigo AS mdf,
                        (CASE WHEN ppo.estado_po IN (3,4,5,6) THEN ppo.grafo ELSE '' END) as grafo,
                        ec.empresaColabDesc AS eecc,
                        dp.oc,
                        dp.ncert,
                        (CASE WHEN ppo.flg_tipo_area = 2 THEN ppo.costo_total ELSE '-' END) AS valoriz_m_o,
                        (CASE WHEN ppo.flg_tipo_area = 1 THEN ppo.costo_total ELSE '-' END) AS valoriz_material,
                        ppo.vale_reserva AS vr,
                        ppo.fechaRegistro AS f_creac_prop,
                        u.usuario AS usu_registro,
                        lppo.fecha_registro AS f_ult_est,
                        ep.estadoPlanDesc,
                        f.faseDesc,
                        c.distrito
                FROM detalleplan dp,
                        planobra_po ppo LEFT JOIN usuario u ON ppo.idUsuario = u.id_usuario,
                        po_estado poe,
                        log_planobra_po lppo,
                        planobra po,
                        fase f,
                        estadoplan ep,
                        subproyecto sp,
                        proyecto p,
                        central c,
                        zonal z,
                        subproyectoestacion spe,
                        estacionarea ea,
                        area a,
                        empresacolab ec
                WHERE dp.itemplan = ppo.itemplan
                    AND dp.poCod = ppo.codigo_po
                    AND ppo.estado_po = poe.idPoEstado
                    AND lppo.codigo_po = ppo.codigo_po 
                    AND lppo.idPoestado = ppo.estado_po
                    AND dp.itemplan = po.itemPlan
                    AND po.idFase = f.idFase
                    AND po.idEstadoPlan = ep.idEstadoPlan
                    AND po.idSubProyecto = sp.idSubProyecto
                    AND sp.idProyecto = p.idProyecto
                    AND po.idCentral = c.idCentral
                    AND po.idzonal = z.idzonal
                    AND dp.idSubProyectoEstacion = spe.idSubProyectoEstacion
                    AND spe.idEstacionArea = ea.idEstacionArea
                    AND ea.idArea = a.idArea
                    AND SUBSTR(dp.poCod,1,4) = '2019'
                    AND	(CASE WHEN sp.idTipoSubProyecto = 2 THEN c.idEmpresaColabCV = ec.idEmpresaColab ELSE c.idEmpresaColab = ec.idEmpresaColab END)
                    AND ppo.id_eecc_reg = ".ID_EECC_DOMINION;
	    $result = $this->db->query($Query);
	    return $result->result();
	}
	
	public function getPlanObraToDominion()
	{
	    $Query = "SELECT 
                    po.itemPlan,
                    p.proyectoDesc,
                    sp.subProyectoDesc,
                    po.indicador,
                    po.nombreProyecto,
                    f.faseDesc,
                    po.uip,
                    po.coordX,
                    po.coordY,
                    ep.estadoPlanDesc,
                    DATE(po.fecha_creacion) as fecha_creacion,
                    DATE(po.fechaInicio) as fechaInicio,
                    DATE(po.fechaPrevEjec) as fechaPrevEjec,
                    DATE(po.fechaTermino) as fechaTermino,
                    DATE(po.fechaPreLiquidacion) as fechaPreLiquidacion,
                    (CASE WHEN po.hasAdelanto = 0 THEN 'NO' ELSE 'SI' END) as hasAdelanto,
                    c.tipoCentralDesc,
                    c.jefatura,
                    c.region,
                    z.zonalDesc,
                    ec.empresaColabDesc,
                    (CASE WHEN pz.idParalizacion IS NOT NULL THEN 'X' ELSE '' END) as flagParalizacion,
                    DATE(pz.fechaRegistro) as fechaRegistro,
                    m.motivoDesc,
                    DATE(pz.fechaReactivacion) as fechaReactivacion,
                    DATE(po.fec_ult_adju_diseno) as fec_ult_adju_diseno,
                    DATE(po.fec_ult_ejec_diseno) as fec_ult_ejec_diseno,
                    ec_dise.empresaColabDesc ec_diseno,
                    ec_elec.empresaElecDesc ec_elec,
                    po.ult_estado_sirope,
                    po.ult_codigo_sirope
                FROM
                    subproyecto sp,
                    proyecto p,
                    fase f,
                    estadoplan ep,
                    central c,
                    zonal z,
                    empresacolab ec,
                    planobra po
					LEFT JOIN
							paralizacion pz	JOIN motivo m ON pz.idMotivo = m.idMotivo												
					ON po.itemplan = pz.itemplan	AND pz.flg_activo = 1                             
					LEFT JOIN empresacolab ec_dise ON po.idEmpresaColabDiseno = ec_dise.idEmpresaColab
                    LEFT JOIN empresaelec ec_elec ON po.idEmpresaElec = ec_elec.idEmpresaElec 
                WHERE
                    po.idSubProyecto = sp.idSubProyecto
                        AND sp.idProyecto = p.idProyecto
                        AND po.idFase = f.idFase
                        AND po.idEstadoPlan = ep.idEstadoPlan
                        AND po.idCentral = c.idCentral
                        AND c.idZonal = z.idZonal                        
                        AND po.idSubProyecto != 97 
                        AND c.idEmpresaColab = ec.idEmpresaColab						
                        AND c.idEmpresaColab = ".ID_EECC_DOMINION;
	    $result = $this->db->query($Query);
	    return $result->result();
	}
}