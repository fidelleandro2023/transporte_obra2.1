<?php
class M_reporte_sisego_ejecutados extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
    }
    
    function getTablaReporteSisego() {
        $sql = " SELECT tb.region, sum(tb.sin_ppto) as sin_ppto, sum(tb.pdt_gtec) as pdt_gtec, sum(tb.despliege_diseno) as despliege_diseno, sum(tb.despliege_licencia) as despliege_licencia, sum(tb.despliege_aprob) as despliege_aprob, sum(tb.despliege_obra) as despliege_obra, sum(tb.acelera) as acelera, sum(tb.despliege_paralizado) as despliege_paralizado, sum(tb.terminado) as terminado, sum(tb.trunco) as trunco, sum(tb.cancelado) as cancelado FROM (
                                select c.region, 
                                SUM(CASE WHEN po.idEstadoPlan in (8,1,2,3,19,20) AND (po.solicitud_oc is null OR po.estado_sol_oc = 'CANCELADO') THEN 1 ELSE 0 END) as sin_ppto, 
                                SUM(CASE WHEN po.idEstadoPlan in (8,1,2,3,19,20) AND po.solicitud_oc is not null AND po.estado_sol_oc = 'PENDIENTE' THEN 1 ELSE 0 END) as pdt_gtec,
                                SUM(CASE WHEN po.idEstadoPlan in (2) AND po.solicitud_oc is not null AND po.estado_sol_oc = 'ATENDIDO' AND (CASE WHEN po.idEmpresaColab IN (1,2) THEN c.region != 'LIMA' ELSE TRUE END) AND po.has_paralizado is null THEN 1 ELSE 0 END) as despliege_diseno,
                                SUM(CASE WHEN po.idEstadoPlan in (19) AND po.solicitud_oc is not null AND po.estado_sol_oc = 'ATENDIDO' AND (CASE WHEN po.idEmpresaColab IN (1,2) THEN c.region != 'LIMA' ELSE TRUE END) AND po.has_paralizado is null THEN 1 ELSE 0 END) as despliege_licencia, 
                                SUM(CASE WHEN po.idEstadoPlan in (20) AND po.solicitud_oc is not null AND po.estado_sol_oc = 'ATENDIDO' AND (CASE WHEN po.idEmpresaColab IN (1,2) THEN c.region != 'LIMA' ELSE TRUE END) AND po.has_paralizado is null THEN 1 ELSE 0 END) as despliege_aprob,
                                SUM(CASE WHEN po.idEstadoPlan in (3) AND po.solicitud_oc is not null AND po.estado_sol_oc = 'ATENDIDO' AND (CASE WHEN po.idEmpresaColab IN (1,2) THEN c.region != 'LIMA' ELSE TRUE END) AND po.has_paralizado is null THEN 1 ELSE 0 END) as despliege_obra,
                                SUM(CASE WHEN po.idEstadoPlan in (2,19,20,3) AND po.solicitud_oc is not null AND po.estado_sol_oc = 'ATENDIDO' AND po.idEmpresaColab IN (1,2) AND c.region = 'LIMA' THEN 1 ELSE 0 END) as acelera, 
                                SUM(CASE WHEN po.idEstadoPlan in (2,19,20,3) AND po.solicitud_oc is not null AND po.estado_sol_oc = 'ATENDIDO' AND (CASE WHEN po.idEmpresaColab IN (1,2) THEN c.region != 'LIMA' ELSE TRUE END) AND po.has_paralizado = 1 THEN 1 ELSE 0 END) as despliege_paralizado,
                                SUM(CASE WHEN po.idEstadoPlan in (9,4,5,21,22,23) AND po.fechaPreliquidacion >= '2020-01-01' THEN 1 ELSE 0 END) as terminado,
                                SUM(CASE WHEN po.idEstadoPlan in (10) AND po.fechaTrunca >= '2020-01-01' THEN 1 ELSE 0 END) as trunco,
                                SUM(CASE WHEN po.idEstadoPlan in (6) AND po.fechaCancelacion >= '2020-01-01' THEN 1 ELSE 0 END) as cancelado
                                from planobra po, pqt_central c where po.idCentralPqt = c.idCentral
                                and po.idSubProyecto in (13,14,15,619,583,620) 
                                and po.paquetizado_fg in (1,2)
                                group by c.region
                                UNION ALL
                                select c.region, 
                                SUM(CASE WHEN po.idEstadoPlan in (8,1,2,3,19,20) AND (po.solicitud_oc is null OR po.estado_sol_oc = 'CANCELADO') THEN 1 ELSE 0 END) as sin_ppto, 
                                SUM(CASE WHEN po.idEstadoPlan in (8,1,2,3,19,20) AND po.solicitud_oc is not null AND po.estado_sol_oc = 'PENDIENTE' THEN 1 ELSE 0 END) as pdt_gtec,
                                SUM(CASE WHEN po.idEstadoPlan in (2) AND po.solicitud_oc is not null AND po.estado_sol_oc = 'ATENDIDO' AND (CASE WHEN po.idEmpresaColab IN (1,2) THEN c.region != 'LIMA' ELSE TRUE END) AND po.has_paralizado is null THEN 1 ELSE 0 END) as despliege_diseno,
                                SUM(CASE WHEN po.idEstadoPlan in (19) AND po.solicitud_oc is not null AND po.estado_sol_oc = 'ATENDIDO' AND (CASE WHEN po.idEmpresaColab IN (1,2) THEN c.region != 'LIMA' ELSE TRUE END) AND po.has_paralizado is null THEN 1 ELSE 0 END) as despliege_licencia, 
                                SUM(CASE WHEN po.idEstadoPlan in (20) AND po.solicitud_oc is not null AND po.estado_sol_oc = 'ATENDIDO' AND (CASE WHEN po.idEmpresaColab IN (1,2) THEN c.region != 'LIMA' ELSE TRUE END) AND po.has_paralizado is null THEN 1 ELSE 0 END) as despliege_aprob,
                                SUM(CASE WHEN po.idEstadoPlan in (3) AND po.solicitud_oc is not null AND po.estado_sol_oc = 'ATENDIDO' AND (CASE WHEN po.idEmpresaColab IN (1,2) THEN c.region != 'LIMA' ELSE TRUE END) AND po.has_paralizado is null THEN 1 ELSE 0 END) as despliege_obra,
                                SUM(CASE WHEN po.idEstadoPlan in (2,19,20,3) AND po.solicitud_oc is not null AND po.estado_sol_oc = 'ATENDIDO' AND po.idEmpresaColab IN (1,2) AND c.region = 'LIMA' THEN 1 ELSE 0 END) as acelera, 
                                SUM(CASE WHEN po.idEstadoPlan in (2,19,20,3) AND po.solicitud_oc is not null AND po.estado_sol_oc = 'ATENDIDO' AND (CASE WHEN po.idEmpresaColab IN (1,2) THEN c.region != 'LIMA' ELSE TRUE END) AND po.has_paralizado = 1 THEN 1 ELSE 0 END) as despliege_paralizado,
                                SUM(CASE WHEN po.idEstadoPlan in (9,4,5,21,22,23) AND po.fechaPreliquidacion >= '2020-01-01' THEN 1 ELSE 0 END) as terminado,
                                SUM(CASE WHEN po.idEstadoPlan in (10) AND po.fechaTrunca >= '2020-01-01' THEN 1 ELSE 0 END) as trunco,
                                SUM(CASE WHEN po.idEstadoPlan in (6) AND po.fechaCancelacion >= '2020-01-01' THEN 1 ELSE 0 END) as cancelado
                                from planobra po, central c where po.idCentral = c.idCentral
                                and po.idSubProyecto in (13,14,15,619,583,620) 
                                and po.paquetizado_fg is null
                                group by c.region) as tb
                                group by tb.region";
        $result = $this->db->query($sql, array());
        return $result->result();
    }
    
    
    function getCondicionalToQuery($tipo, $region){
        $condicional = ' 1 = 1 ';
        if($tipo    ==  1){// PENDIENTE TOTAL DE PRESUPUESTO
            $condicional = "po.idEstadoPlan in (8,1,2,3,19,20) AND (po.solicitud_oc is null OR po.estado_sol_oc = 'CANCELADO')";
        }else if($tipo    ==  2){// PENDIENTE DE GETEC
            $condicional = "po.idEstadoPlan in (8,1,2,3,19,20) AND po.solicitud_oc is not null AND po.estado_sol_oc = 'PENDIENTE'";
        }else if($tipo    ==  3){//TOTAL DESPLIGUE RED
            $condicional = "po.idEstadoPlan in (2,19,20,3) AND po.solicitud_oc is not null  AND po.estado_sol_oc = 'ATENDIDO' AND (CASE WHEN po.idEmpresaColab IN (1,2) THEN c.region != 'LIMA' ELSE TRUE END) AND po.has_paralizado is null";
        }else if($tipo    ==  4){//TOTAL ACELERA
            $condicional = "po.idEstadoPlan in (2,19,20,3) AND po.solicitud_oc is not null AND po.estado_sol_oc = 'ATENDIDO' AND po.idEmpresaColab IN (1,2) AND c.region = 'LIMA'";
        }else if($tipo    ==  5){//TOTAL PARALIZADOS
            $condicional = "po.idEstadoPlan in (2,19,20,3) AND po.solicitud_oc is not null AND po.estado_sol_oc = 'ATENDIDO' AND (CASE WHEN po.idEmpresaColab IN (1,2) THEN c.region != 'LIMA' ELSE TRUE END) AND po.has_paralizado = 1";
        }else if($tipo    ==  6){//TOTAL TERMINADO
            $condicional = "po.idEstadoPlan in (9,4,5,21,22,23) AND po.fechaPreliquidacion >= '2020-01-01'";
        }else if($tipo    ==  7){//TOTAL TRUNCO
            $condicional = "po.idEstadoPlan in (10) AND po.fechaTrunca >= '2020-01-01'";
        }else if($tipo    ==  8){//TOTAL DESPLIGUE RED DISENO
            $condicional = "po.idEstadoPlan in (2) AND po.solicitud_oc is not null  AND po.estado_sol_oc = 'ATENDIDO' AND (CASE WHEN po.idEmpresaColab IN (1,2) THEN c.region != 'LIMA' ELSE TRUE END) AND po.has_paralizado is null";
        }else if($tipo    ==  9){//TOTAL DESPLIGUE RED LICENCIA
            $condicional = "po.idEstadoPlan in (19) AND po.solicitud_oc is not null  AND po.estado_sol_oc = 'ATENDIDO' AND (CASE WHEN po.idEmpresaColab IN (1,2) THEN c.region != 'LIMA' ELSE TRUE END) AND po.has_paralizado is null";
        }else if($tipo    ==  10){//TOTAL DESPLIGUE RED EN APROBA
            $condicional = "po.idEstadoPlan in (20) AND po.solicitud_oc is not null  AND po.estado_sol_oc = 'ATENDIDO' AND (CASE WHEN po.idEmpresaColab IN (1,2) THEN c.region != 'LIMA' ELSE TRUE END) AND po.has_paralizado is null";
        }else if($tipo    ==  11){//TOTAL DESPLIGUE RED EN OBRA
            $condicional = "po.idEstadoPlan in (3) AND po.solicitud_oc is not null  AND po.estado_sol_oc = 'ATENDIDO' AND (CASE WHEN po.idEmpresaColab IN (1,2) THEN c.region != 'LIMA' ELSE TRUE END) AND po.has_paralizado is null";
        }else if($tipo    ==  12){//TOTAL SIN PPT POR REGION
            $condicional = "po.idEstadoPlan in (8,1,2,3,19,20) AND (po.solicitud_oc is null OR po.estado_sol_oc = 'CANCELADO') AND c.region = '".$region."'";
        }else if($tipo    ==  13){// PENDIENTE DE GETEC POR REGION
            $condicional = "po.idEstadoPlan in (8,1,2,3,19,20) AND po.solicitud_oc is not null AND po.estado_sol_oc = 'PENDIENTE' AND c.region = '".$region."'";
        }else if($tipo    ==  14){//TOTAL DESPLIGUE RED DISENO POR REGION
            $condicional = "po.idEstadoPlan in (2) AND po.solicitud_oc is not null  AND po.estado_sol_oc = 'ATENDIDO' AND (CASE WHEN po.idEmpresaColab IN (1,2) THEN c.region != 'LIMA' ELSE TRUE END) AND po.has_paralizado is null AND c.region = '".$region."'";
        }else if($tipo    ==  15){//TOTAL DESPLIGUE RED LICENCIA POR REGION
            $condicional = "po.idEstadoPlan in (19) AND po.solicitud_oc is not null  AND po.estado_sol_oc = 'ATENDIDO' AND (CASE WHEN po.idEmpresaColab IN (1,2) THEN c.region != 'LIMA' ELSE TRUE END) AND po.has_paralizado is null AND c.region = '".$region."'";
        }else if($tipo    ==  16){//TOTAL DESPLIGUE RED EN APROBA POR REGION
            $condicional = "po.idEstadoPlan in (20) AND po.solicitud_oc is not null  AND po.estado_sol_oc = 'ATENDIDO' AND (CASE WHEN po.idEmpresaColab IN (1,2) THEN c.region != 'LIMA' ELSE TRUE END) AND po.has_paralizado is null AND c.region = '".$region."'";
        }else if($tipo    ==  17){//TOTAL DESPLIGUE RED EN OBRA POR REGION
            $condicional = "po.idEstadoPlan in (3) AND po.solicitud_oc is not null  AND po.estado_sol_oc = 'ATENDIDO' AND (CASE WHEN po.idEmpresaColab IN (1,2) THEN c.region != 'LIMA' ELSE TRUE END) AND po.has_paralizado is null AND c.region = '".$region."'";
        }else if($tipo    ==  18){//TOTAL DESPLIGUE RED POR REGION
            $condicional = "po.idEstadoPlan in (2,19,20,3) AND po.solicitud_oc is not null  AND po.estado_sol_oc = 'ATENDIDO' AND (CASE WHEN po.idEmpresaColab IN (1,2) THEN c.region != 'LIMA' ELSE TRUE END) AND po.has_paralizado is null AND c.region = '".$region."'";
        }else if($tipo    ==  19){//TOTAL ACELERA POR REGION
            $condicional = "po.idEstadoPlan in (2,19,20,3) AND po.solicitud_oc is not null AND po.estado_sol_oc = 'ATENDIDO' AND po.idEmpresaColab IN (1,2) AND c.region = 'LIMA' AND c.region = '".$region."'";
        }else if($tipo    ==  20){//TOTAL PARALIZADOS POR REGION
            $condicional = "po.idEstadoPlan in (2,19,20,3) AND po.solicitud_oc is not null AND po.estado_sol_oc = 'ATENDIDO' AND (CASE WHEN po.idEmpresaColab IN (1,2) THEN c.region != 'LIMA' ELSE TRUE END) AND po.has_paralizado = 1 AND c.region = '".$region."'";
        }else if($tipo    ==  21){//TOTAL TERMINADO POR REGION
            $condicional = "po.idEstadoPlan in (9,4,5,21,22,23) AND po.fechaPreliquidacion >= '2020-01-01' AND c.region = '".$region."'";
        }else if($tipo    ==  22){//TOTAL TRUNCO POR REGION
            $condicional = "po.idEstadoPlan in (10) AND po.fechaTrunca >= '2020-01-01' AND c.region = '".$region."'";
        }else if($tipo    ==  23){//TOTAL CANCELADO
            $condicional = "po.idEstadoPlan in (6) AND po.fechaCancelacion >= '2020-01-01'";
        }else if($tipo    ==  24){//TOTAL TERMINADO POR REGION
            $condicional = "po.idEstadoPlan in (6) AND po.fechaCancelacion >= '2020-01-01' AND c.region = '".$region."'";
        }
        
        return $condicional;
    }
	
    function getDataToExcelReport($tipo, $region) {
        $condicional = $this->getCondicionalToQuery($tipo, $region);
        $sql = "SELECT sp.subProyectoDesc, po.itemplan, po.indicador, po.nombreProyecto, c.jefatura, z.zonalDesc, ec.empresaColabDesc, ep.estadoPlanDesc, po.has_paralizado, UPPER(m.motivoDesc) as motivoDesc,
                    (CASE 	WHEN po.solicitud_oc IS NOT NULL AND po.estado_sol_oc != 'CANCELADO' THEN 'CON PRESUP'
                    										WHEN  po.motivo_paralizado = 11 THEN 'SIN PRESUP'
                    										WHEN  po.motivo_paralizado = 67 THEN 'SIN COTIZACION' 
                                                            ELSE 'SIN PRESUP' END) AS has_presupuesto,
                                                            (CASE WHEN po.idEmpresaColab IN (1,2) AND c.region = 'LIMA' THEN 'ACELERA'
                    											  WHEN po.idEmpresaColab NOT IN (1,2) AND c.region = 'LIMA' THEN 'DESPLIEGUE'
                                                                  WHEN c.region != 'LIMA' THEN 'ZONALES' END) as responsabilidad,po.fechaPreliquidacion, (case when po.idEstadoPlan = 10 THEN po.fechaTrunca
                    																								when po.idEstadoPlan = 6 THEN po.fechaCancelacion end) as fec_trunc_cancel,
                    									tb.pep2, tb.grafo,
                                                        (CASE WHEN po.idSubProyecto IN (13,14,15) THEN 
																(CASE WHEN soc_crea.estado = 2 THEN  soc_crea.codigo_solicitud else null END)
															  WHEN po.idSubProyecto IN (619,583,620) THEN  
																(CASE WHEN soc_crea_opex.estado = 2 THEN  soc_crea_opex.codigo_solicitud else null  END) 
														END) sol_crea, 
                                                
												(CASE WHEN po.idSubProyecto IN (13,14,15) THEN 
																(CASE WHEN soc_crea.estado = 2 THEN  soc_crea.orden_compra else null end)
															  WHEN po.idSubProyecto IN (619,583,620) THEN  
																(CASE WHEN soc_crea_opex.estado = 2 THEN  soc_crea_opex.orden_compra else null end) 
														END) orden_compra,
                                                        
												 (CASE WHEN po.idSubProyecto IN (13,14,15) THEN 
															(CASE WHEN soc_crea.estado = 2 AND (soc_edic.estado IS NULL OR soc_edic.estado IN (1,3)) AND (soc_anul.estado IS NULL OR soc_anul.estado IN (1,3))  AND (soc_cert.estado IS NULL OR soc_cert.estado IN (1,3)) THEN 'CREADO'
																  WHEN soc_crea.estado = 2 AND soc_edic.estado = 2 AND (soc_anul.estado IS NULL OR soc_anul.estado IN (1,3))  AND (soc_cert.estado IS NULL OR soc_cert.estado IN (1,3)) THEN 'EDITADO'
																  WHEN soc_crea.estado = 2 AND soc_cert.estado = 2 THEN 'CERTIFICADO' 
																  WHEN soc_crea.estado = 2 AND soc_anul.estado = 2 AND (soc_cert.estado IS NULL OR soc_cert.estado IN (1,3)) THEN 'ANULADO' END) 
														  WHEN po.idSubProyecto IN (619,583,620) THEN  
																(CASE WHEN soc_crea_opex.estado = 2 AND (soc_edic_opex.estado IS NULL OR soc_edic_opex.estado IN (1,3)) AND (soc_anul_opex.estado IS NULL OR soc_anul_opex.estado IN (1,3))  AND (soc_cert_opex.estado IS NULL OR soc_cert_opex.estado IN (1,3)) THEN 'CREADO'
																  WHEN soc_crea_opex.estado = 2 AND soc_edic_opex.estado = 2 AND (soc_anul_opex.estado IS NULL OR soc_anul_opex.estado IN (1,3))  AND (soc_cert_opex.estado IS NULL OR soc_cert_opex.estado IN (1,3)) THEN 'EDITADO'
																  WHEN soc_crea_opex.estado = 2 AND soc_cert_opex.estado = 2 THEN 'CERTIFICADO' 
																  WHEN soc_crea_opex.estado = 2 AND soc_anul_opex.estado = 2 AND (soc_cert_opex.estado IS NULL OR soc_cert_opex.estado IN (1,3)) THEN 'ANULADO' END)
												END)	estado_oc,
                                                
												(CASE WHEN po.idSubProyecto IN (13,14,15) THEN 
																(CASE WHEN soc_crea.estado = 2 AND (soc_edic.estado IS NULL OR soc_edic.estado IN (1,3)) AND (soc_anul.estado IS NULL OR soc_anul.estado IN (1,3))  AND (soc_cert.estado IS NULL OR soc_cert.estado IN (1,3)) THEN po.costo_unitario_mo_crea_oc
																	  WHEN soc_crea.estado = 2 AND soc_edic.estado = 2 AND (soc_anul.estado IS NULL OR soc_anul.estado IN (1,3))  AND (soc_cert.estado IS NULL OR soc_cert.estado IN (1,3)) THEN po.costo_devolucion
																	  WHEN soc_crea.estado = 2 AND soc_cert.estado = 2 THEN po.costo_unitario_mo_certi
																	  WHEN soc_crea.estado = 2 AND soc_anul.estado = 2 AND (soc_cert.estado IS NULL OR soc_cert.estado IN (1,3)) THEN po.costo_unitario_mo_anula_pos END)
															WHEN po.idSubProyecto IN (619,583,620) THEN  
																(CASE WHEN soc_crea_opex.estado = 2 AND (soc_edic_opex.estado IS NULL OR soc_edic_opex.estado IN (1,3)) AND (soc_anul_opex.estado IS NULL OR soc_anul_opex.estado IN (1,3))  AND (soc_cert_opex.estado IS NULL OR soc_cert_opex.estado IN (1,3)) THEN po.costo_unitario_mo_crea_oc
																	  WHEN soc_crea_opex.estado = 2 AND soc_edic_opex.estado = 2 AND (soc_anul_opex.estado IS NULL OR soc_anul_opex.estado IN (1,3))  AND (soc_cert_opex.estado IS NULL OR soc_cert_opex.estado IN (1,3)) THEN po.costo_devolucion
																	  WHEN soc_crea_opex.estado = 2 AND soc_cert_opex.estado = 2 THEN po.costo_unitario_mo_certi
																	  WHEN soc_crea_opex.estado = 2 AND soc_anul_opex.estado = 2 AND (soc_cert_opex.estado IS NULL OR soc_cert_opex.estado IN (1,3)) THEN po.costo_unitario_mo_anula_pos END)                                                            
												END) as monto_oc
                    FROM subproyecto sp, pqt_central c, zonal z, empresacolab ec, estadoplan ep, planobra po 
                    LEFT JOIN motivo m ON po.motivo_paralizado = m.idMotivo
                    LEFT JOIN (select sisego, pep2, grafo from sisego_pep2_grafo where estado in (0,1,2) 
                    group by sisego) as tb ON po.indicador = tb.sisego
                    LEFT JOIN solicitud_orden_compra soc_crea ON po.solicitud_oc = soc_crea.codigo_solicitud
                    LEFT JOIN solicitud_orden_compra soc_edic ON po.solicitud_oc_dev = soc_edic.codigo_solicitud
                    LEFT JOIN solicitud_orden_compra soc_cert ON po.solicitud_oc_certi = soc_cert.codigo_solicitud
                    LEFT JOIN solicitud_orden_compra soc_anul ON po.solicitud_oc_anula_pos = soc_anul.codigo_solicitud
					LEFT JOIN itemplan_solicitud_orden_compra soc_crea_opex ON po.solicitud_oc = soc_crea_opex.codigo_solicitud
					LEFT JOIN itemplan_solicitud_orden_compra soc_edic_opex ON po.solicitud_oc_dev = soc_edic_opex.codigo_solicitud
					LEFT JOIN itemplan_solicitud_orden_compra soc_cert_opex ON po.solicitud_oc_certi = soc_cert_opex.codigo_solicitud
					LEFT JOIN itemplan_solicitud_orden_compra soc_anul_opex ON po.solicitud_oc_anula_pos = soc_anul_opex.codigo_solicitud  
                    WHERE ".$condicional."
                    AND po.idSubProyecto in (13,14,15,619,583,620) 
                    AND po.idsubProyecto = sp.idSubProyecto
                    AND po.paquetizado_fg in (1,2)
                    AND po.idCentralPqt = c.idCentral
                    AND po.idEmpresaColab = ec.idEmpresaColab
                    AND c.idZonal = z.idZonal
                    AND po.idEstadoPlan = ep.idEstadoPlan
                    union all
                    SELECT sp.subProyectoDesc, po.itemplan, po.indicador, po.nombreProyecto, c.jefatura, z.zonalDesc, ec.empresaColabDesc, ep.estadoPlanDesc, po.has_paralizado, UPPER(m.motivoDesc) as motivoDesc,
                    (CASE 	WHEN po.solicitud_oc IS NOT NULL AND po.estado_sol_oc != 'CANCELADO' THEN 'CON PRESUP'
                    										WHEN  po.motivo_paralizado = 11 THEN 'SIN PRESUP'
                    										WHEN  po.motivo_paralizado = 67 THEN 'SIN COTIZACION' 
                                                            ELSE 'SIN PRESUP' END) AS has_presupuesto,
                                                            (CASE WHEN po.idEmpresaColab IN (1,2) AND c.region = 'LIMA' THEN 'ACELERA'
                    											  WHEN po.idEmpresaColab NOT IN (1,2) AND c.region = 'LIMA' THEN 'DESPLIEGUE'
                                                                  WHEN c.region != 'LIMA' THEN 'ZONALES' END) as responsabilidad,po.fechaPreliquidacion, (case when po.idEstadoPlan = 10 THEN po.fechaTrunca
                    																								when po.idEstadoPlan = 6 THEN po.fechaCancelacion end) as fec_trunc_cancel,
                    									tb.pep2, tb.grafo,
                                                        (CASE WHEN po.idSubProyecto IN (13,14,15) THEN 
																(CASE WHEN soc_crea.estado = 2 THEN  soc_crea.codigo_solicitud else null END)
															  WHEN po.idSubProyecto IN (619,583,620) THEN  
																(CASE WHEN soc_crea_opex.estado = 2 THEN  soc_crea_opex.codigo_solicitud else null  END) 
														END) sol_crea, 
                                                
												(CASE WHEN po.idSubProyecto IN (13,14,15) THEN 
																(CASE WHEN soc_crea.estado = 2 THEN  soc_crea.orden_compra else null end)
															  WHEN po.idSubProyecto IN (619,583,620) THEN  
																(CASE WHEN soc_crea_opex.estado = 2 THEN  soc_crea_opex.orden_compra else null end) 
														END) orden_compra,
                                                        
												 (CASE WHEN po.idSubProyecto IN (13,14,15) THEN 
															(CASE WHEN soc_crea.estado = 2 AND (soc_edic.estado IS NULL OR soc_edic.estado IN (1,3)) AND (soc_anul.estado IS NULL OR soc_anul.estado IN (1,3))  AND (soc_cert.estado IS NULL OR soc_cert.estado IN (1,3)) THEN 'CREADO'
																  WHEN soc_crea.estado = 2 AND soc_edic.estado = 2 AND (soc_anul.estado IS NULL OR soc_anul.estado IN (1,3))  AND (soc_cert.estado IS NULL OR soc_cert.estado IN (1,3)) THEN 'EDITADO'
																  WHEN soc_crea.estado = 2 AND soc_cert.estado = 2 THEN 'CERTIFICADO' 
																  WHEN soc_crea.estado = 2 AND soc_anul.estado = 2 AND (soc_cert.estado IS NULL OR soc_cert.estado IN (1,3)) THEN 'ANULADO' END) 
														  WHEN po.idSubProyecto IN (619,583,620) THEN  
																(CASE WHEN soc_crea_opex.estado = 2 AND (soc_edic_opex.estado IS NULL OR soc_edic_opex.estado IN (1,3)) AND (soc_anul_opex.estado IS NULL OR soc_anul_opex.estado IN (1,3))  AND (soc_cert_opex.estado IS NULL OR soc_cert_opex.estado IN (1,3)) THEN 'CREADO'
																  WHEN soc_crea_opex.estado = 2 AND soc_edic_opex.estado = 2 AND (soc_anul_opex.estado IS NULL OR soc_anul_opex.estado IN (1,3))  AND (soc_cert_opex.estado IS NULL OR soc_cert_opex.estado IN (1,3)) THEN 'EDITADO'
																  WHEN soc_crea_opex.estado = 2 AND soc_cert_opex.estado = 2 THEN 'CERTIFICADO' 
																  WHEN soc_crea_opex.estado = 2 AND soc_anul_opex.estado = 2 AND (soc_cert_opex.estado IS NULL OR soc_cert_opex.estado IN (1,3)) THEN 'ANULADO' END)
												END)	estado_oc,
                                                
												(CASE WHEN po.idSubProyecto IN (13,14,15) THEN 
																(CASE WHEN soc_crea.estado = 2 AND (soc_edic.estado IS NULL OR soc_edic.estado IN (1,3)) AND (soc_anul.estado IS NULL OR soc_anul.estado IN (1,3))  AND (soc_cert.estado IS NULL OR soc_cert.estado IN (1,3)) THEN po.costo_unitario_mo_crea_oc
																	  WHEN soc_crea.estado = 2 AND soc_edic.estado = 2 AND (soc_anul.estado IS NULL OR soc_anul.estado IN (1,3))  AND (soc_cert.estado IS NULL OR soc_cert.estado IN (1,3)) THEN po.costo_devolucion
																	  WHEN soc_crea.estado = 2 AND soc_cert.estado = 2 THEN po.costo_unitario_mo_certi
																	  WHEN soc_crea.estado = 2 AND soc_anul.estado = 2 AND (soc_cert.estado IS NULL OR soc_cert.estado IN (1,3)) THEN po.costo_unitario_mo_anula_pos END)
															WHEN po.idSubProyecto IN (619,583,620) THEN  
																(CASE WHEN soc_crea_opex.estado = 2 AND (soc_edic_opex.estado IS NULL OR soc_edic_opex.estado IN (1,3)) AND (soc_anul_opex.estado IS NULL OR soc_anul_opex.estado IN (1,3))  AND (soc_cert_opex.estado IS NULL OR soc_cert_opex.estado IN (1,3)) THEN po.costo_unitario_mo_crea_oc
																	  WHEN soc_crea_opex.estado = 2 AND soc_edic_opex.estado = 2 AND (soc_anul_opex.estado IS NULL OR soc_anul_opex.estado IN (1,3))  AND (soc_cert_opex.estado IS NULL OR soc_cert_opex.estado IN (1,3)) THEN po.costo_devolucion
																	  WHEN soc_crea_opex.estado = 2 AND soc_cert_opex.estado = 2 THEN po.costo_unitario_mo_certi
																	  WHEN soc_crea_opex.estado = 2 AND soc_anul_opex.estado = 2 AND (soc_cert_opex.estado IS NULL OR soc_cert_opex.estado IN (1,3)) THEN po.costo_unitario_mo_anula_pos END)                                                            
												END) as monto_oc
                    FROM subproyecto sp, central c, zonal z, empresacolab ec, estadoplan ep, planobra po 
                    LEFT JOIN motivo m ON po.motivo_paralizado = m.idMotivo
                    LEFT JOIN (select sisego, pep2, grafo from sisego_pep2_grafo where estado in (0,1,2) 
                    group by sisego) as tb ON po.indicador = tb.sisego
                    LEFT JOIN solicitud_orden_compra soc_crea ON po.solicitud_oc = soc_crea.codigo_solicitud
                    LEFT JOIN solicitud_orden_compra soc_edic ON po.solicitud_oc_dev = soc_edic.codigo_solicitud
                    LEFT JOIN solicitud_orden_compra soc_cert ON po.solicitud_oc_certi = soc_cert.codigo_solicitud
                    LEFT JOIN solicitud_orden_compra soc_anul ON po.solicitud_oc_anula_pos = soc_anul.codigo_solicitud
					LEFT JOIN itemplan_solicitud_orden_compra soc_crea_opex ON po.solicitud_oc = soc_crea_opex.codigo_solicitud
					LEFT JOIN itemplan_solicitud_orden_compra soc_edic_opex ON po.solicitud_oc_dev = soc_edic_opex.codigo_solicitud
					LEFT JOIN itemplan_solicitud_orden_compra soc_cert_opex ON po.solicitud_oc_certi = soc_cert_opex.codigo_solicitud
					LEFT JOIN itemplan_solicitud_orden_compra soc_anul_opex ON po.solicitud_oc_anula_pos = soc_anul_opex.codigo_solicitud  
                    WHERE ".$condicional."
                    AND po.idSubProyecto in (13,14,15,619,583,620) 
                    AND po.idsubProyecto = sp.idSubProyecto
                    AND po.paquetizado_fg is null
                    AND po.idCentral = c.idCentral
                    AND po.idEmpresaColab = ec.idEmpresaColab
                    AND c.idZonal = z.idZonal
                    AND po.idEstadoPlan = ep.idEstadoPlan";
        $result = $this->db->query($sql, array());
        return $result->result();
    }
    
    function getDetalleTerminados() {
        $sql = "select tb.origen, sum(tb.pdt_expe) as pdt_expe, sum(tb.en_vali) as en_vali, sum(tb.en_certi) as en_certi, sum(tb.certi) as certi FROM (
                SELECT (CASE WHEN po.idEmpresaColab in (1,2) AND c.region = 'LIMA' THEN 'ACELERA'
                			WHEN po.idEmpresaColab not in (1,2) AND c.region = 'LIMA' THEN 'DESPLIGUE'
                            WHEN c.region != 'LIMA' THEN 'ZONALES' END) as origen, po.itemplan, po.idEstadoPlan, 
                (CASE WHEN (pqa.id_solicitud is null or pqa.estado in (3)) then 1 else 0 end) as pdt_expe, 
                (CASE WHEN pqa.id_solicitud is not null AND pqa.estado in (0,1) then 1 else 0 end) as en_vali,
                (CASE WHEN pqa.id_solicitud is not null AND pqa.estado in (2) and idEstadoPlan != 23 then 1 else 0 end) as en_certi,
                (CASE WHEN pqa.id_solicitud is not null AND pqa.estado in (2) and idEstadoPlan = 23 then 1 else 0 end) as certi
                	from pqt_central c, planobra po left join 
                    pqt_solicitud_aprob_partidas_adicionales pqa on po.itemplan = pqa.itemplan and pqa.estado in (0,1,2)
                    where po.idCentralPqt = c.idCentral
                	and po.idSubProyecto in (13,14,15,619,583,620) 
                	and po.paquetizado_fg in (1,2)
                    and po.idEstadoPlan in (9,4,5,21,22,23) AND po.fechaPreliquidacion >= '2020-01-01'
                    union all
                    SELECT (CASE WHEN po.idEmpresaColab in (1,2) AND c.region = 'LIMA' THEN 'ACELERA'
                			WHEN po.idEmpresaColab not in (1,2) AND c.region = 'LIMA' THEN 'DESPLIGUE'
                            WHEN c.region != 'LIMA' THEN 'ZONALES' END) as origen, po.itemplan, po.idEstadoPlan, (CASE WHEN pqa.id_solicitud is null or pqa.estado in (3) then 1 else 0 end) as pdt_expe, 
                (CASE WHEN pqa.id_solicitud is not null AND pqa.estado in (0,1) then 1 else 0 end) as en_vali,
                (CASE WHEN pqa.id_solicitud is not null AND pqa.estado in (2) and idEstadoPlan != 23 then 1 else 0 end) as en_certi,
                (CASE WHEN pqa.id_solicitud is not null AND pqa.estado in (2) and idEstadoPlan = 23 then 1 else 0 end) as certi
                	from central c, planobra po left join 
                    pqt_solicitud_aprob_partidas_adicionales pqa on po.itemplan = pqa.itemplan and pqa.estado in (0,1,2)
                    where po.idCentral = c.idCentral
                	and po.idSubProyecto in (13,14,15,619,583,620) 
                	and po.paquetizado_fg is null
                    and po.idEstadoPlan in (9,4,5,21,22,23) AND po.fechaPreliquidacion >= '2020-01-01'
                    )    as tb group by tb.origen";
        $result = $this->db->query($sql, array());
        return $result->result();
    }
     
    function getDataPieTerminadosPPTO() {
        $sql = "SELECT tb.has_ppto, count(1) as num FROM (
                    SELECT po.itemplan,(CASE WHEN po.idSubProyecto IN (13,14,15,619,583,620) THEN
                    								(CASE 	WHEN po.solicitud_oc IS NOT NULL AND po.estado_sol_oc != 'CANCELADO' THEN 'CON PRESUP'
                    										WHEN  po.motivo_paralizado = 11 THEN 'SIN PRESUP'
                    										WHEN  po.motivo_paralizado = 67 THEN 'SIN COTIZACION' 
                                                            ELSE 'SIN PRESUP' END)
                    						END) AS has_ppto
                    	from pqt_central c, planobra po left join 
                        pqt_solicitud_aprob_partidas_adicionales pqa on po.itemplan = pqa.itemplan and pqa.estado in (0,1,2)
                        where po.idCentralPqt = c.idCentral
                    	and po.idSubProyecto in (13,14,15,619,583,620) 
                    	and po.paquetizado_fg in (1,2)
                        and po.idEstadoPlan in (9,4,5,21,22,23) AND po.fechaPreliquidacion >= '2020-01-01'
                        AND (CASE WHEN po.idEstadoPlan in (23) THEN  pqa.estado not in (2) else true end)
                          union all
                        SELECT po.itemplan,(CASE WHEN po.idSubProyecto IN (13,14,15,619,583,620) THEN
                    								(CASE 	WHEN po.solicitud_oc IS NOT NULL AND po.estado_sol_oc != 'CANCELADO' THEN 'CON PRESUP'
                    										WHEN  po.motivo_paralizado = 11 THEN 'SIN PRESUP'
                    										WHEN  po.motivo_paralizado = 67 THEN 'SIN COTIZACION' 
                                                            ELSE 'SIN PRESUP' END)
                    						END) AS has_ppto
                    	from central c, planobra po left join 
                        pqt_solicitud_aprob_partidas_adicionales pqa on po.itemplan = pqa.itemplan and pqa.estado in (0,1,2)
                        where po.idCentral = c.idCentral
                    	and po.idSubProyecto in (13,14,15,619,583,620) 
                    	and po.paquetizado_fg is null
                        and po.idEstadoPlan in (9,4,5,21,22,23) AND po.fechaPreliquidacion >= '2020-01-01'
                        AND (CASE WHEN po.idEstadoPlan in (23) THEN  pqa.estado not in (2) else true end)
                        ) AS tb 
                        group by has_ppto";
        $result = $this->db->query($sql, array());
        return $result->result();
    }
    
    function getDataPieExcel($estadoPPTO) {
        $sql = "SELECT tb.* FROM
                    (SELECT po.itemplan, po.indicador, po.nombreProyecto, c.jefatura, z.zonalDesc, ec.empresaColabDesc, ep.estadoPlanDesc, po.has_paralizado, UPPER(m.motivoDesc) as motivoDesc,
                    (CASE 	WHEN po.solicitud_oc IS NOT NULL AND po.estado_sol_oc != 'CANCELADO' THEN 'CON PRESUP'
                        WHEN  po.motivo_paralizado = 11 THEN 'SIN PRESUP'
                            WHEN  po.motivo_paralizado = 67 THEN 'SIN COTIZACION'
                                ELSE 'SIN PRESUP' END) AS has_presupuesto,
                                (CASE WHEN po.idEmpresaColab IN (1,2) AND c.region = 'LIMA' THEN 'ACELERA'
                                    WHEN po.idEmpresaColab NOT IN (1,2) AND c.region = 'LIMA' THEN 'DESPLIEGUE'
                                        WHEN c.region != 'LIMA' THEN 'ZONALES' END) as responsabilidad,po.fechaPreliquidacion, (case when po.idEstadoPlan = 10 THEN po.fechaTrunca
                                        when po.idEstadoPlan = 6 THEN po.fechaCancelacion end) as fec_trunc_cancel,
                                        tb.pep2, tb.grafo,
                                        (CASE WHEN po.idSubProyecto IN (13,14,15) THEN 
																(CASE WHEN soc_crea.estado = 2 THEN  soc_crea.codigo_solicitud else null END)
															  WHEN po.idSubProyecto IN (619,583,620) THEN  
																(CASE WHEN soc_crea_opex.estado = 2 THEN  soc_crea_opex.codigo_solicitud else null  END) 
														END) sol_crea, 
                                                
												(CASE WHEN po.idSubProyecto IN (13,14,15) THEN 
																(CASE WHEN soc_crea.estado = 2 THEN  soc_crea.orden_compra else null end)
															  WHEN po.idSubProyecto IN (619,583,620) THEN  
																(CASE WHEN soc_crea_opex.estado = 2 THEN  soc_crea_opex.orden_compra else null end) 
														END) orden_compra,
                                                        
												 (CASE WHEN po.idSubProyecto IN (13,14,15) THEN 
															(CASE WHEN soc_crea.estado = 2 AND (soc_edic.estado IS NULL OR soc_edic.estado IN (1,3)) AND (soc_anul.estado IS NULL OR soc_anul.estado IN (1,3))  AND (soc_cert.estado IS NULL OR soc_cert.estado IN (1,3)) THEN 'CREADO'
																  WHEN soc_crea.estado = 2 AND soc_edic.estado = 2 AND (soc_anul.estado IS NULL OR soc_anul.estado IN (1,3))  AND (soc_cert.estado IS NULL OR soc_cert.estado IN (1,3)) THEN 'EDITADO'
																  WHEN soc_crea.estado = 2 AND soc_cert.estado = 2 THEN 'CERTIFICADO' 
																  WHEN soc_crea.estado = 2 AND soc_anul.estado = 2 AND (soc_cert.estado IS NULL OR soc_cert.estado IN (1,3)) THEN 'ANULADO' END) 
														  WHEN po.idSubProyecto IN (619,583,620) THEN  
																(CASE WHEN soc_crea_opex.estado = 2 AND (soc_edic_opex.estado IS NULL OR soc_edic_opex.estado IN (1,3)) AND (soc_anul_opex.estado IS NULL OR soc_anul_opex.estado IN (1,3))  AND (soc_cert_opex.estado IS NULL OR soc_cert_opex.estado IN (1,3)) THEN 'CREADO'
																  WHEN soc_crea_opex.estado = 2 AND soc_edic_opex.estado = 2 AND (soc_anul_opex.estado IS NULL OR soc_anul_opex.estado IN (1,3))  AND (soc_cert_opex.estado IS NULL OR soc_cert_opex.estado IN (1,3)) THEN 'EDITADO'
																  WHEN soc_crea_opex.estado = 2 AND soc_cert_opex.estado = 2 THEN 'CERTIFICADO' 
																  WHEN soc_crea_opex.estado = 2 AND soc_anul_opex.estado = 2 AND (soc_cert_opex.estado IS NULL OR soc_cert_opex.estado IN (1,3)) THEN 'ANULADO' END)
												END)	estado_oc,
                                                
												(CASE WHEN po.idSubProyecto IN (13,14,15) THEN 
																(CASE WHEN soc_crea.estado = 2 AND (soc_edic.estado IS NULL OR soc_edic.estado IN (1,3)) AND (soc_anul.estado IS NULL OR soc_anul.estado IN (1,3))  AND (soc_cert.estado IS NULL OR soc_cert.estado IN (1,3)) THEN po.costo_unitario_mo_crea_oc
																	  WHEN soc_crea.estado = 2 AND soc_edic.estado = 2 AND (soc_anul.estado IS NULL OR soc_anul.estado IN (1,3))  AND (soc_cert.estado IS NULL OR soc_cert.estado IN (1,3)) THEN po.costo_devolucion
																	  WHEN soc_crea.estado = 2 AND soc_cert.estado = 2 THEN po.costo_unitario_mo_certi
																	  WHEN soc_crea.estado = 2 AND soc_anul.estado = 2 AND (soc_cert.estado IS NULL OR soc_cert.estado IN (1,3)) THEN po.costo_unitario_mo_anula_pos END)
															WHEN po.idSubProyecto IN (619,583,620) THEN  
																(CASE WHEN soc_crea_opex.estado = 2 AND (soc_edic_opex.estado IS NULL OR soc_edic_opex.estado IN (1,3)) AND (soc_anul_opex.estado IS NULL OR soc_anul_opex.estado IN (1,3))  AND (soc_cert_opex.estado IS NULL OR soc_cert_opex.estado IN (1,3)) THEN po.costo_unitario_mo_crea_oc
																	  WHEN soc_crea_opex.estado = 2 AND soc_edic_opex.estado = 2 AND (soc_anul_opex.estado IS NULL OR soc_anul_opex.estado IN (1,3))  AND (soc_cert_opex.estado IS NULL OR soc_cert_opex.estado IN (1,3)) THEN po.costo_devolucion
																	  WHEN soc_crea_opex.estado = 2 AND soc_cert_opex.estado = 2 THEN po.costo_unitario_mo_certi
																	  WHEN soc_crea_opex.estado = 2 AND soc_anul_opex.estado = 2 AND (soc_cert_opex.estado IS NULL OR soc_cert_opex.estado IN (1,3)) THEN po.costo_unitario_mo_anula_pos END)                                                            
												END) as monto_oc
					  FROM pqt_central c, zonal z, empresacolab ec, estadoplan ep, planobra po
					  LEFT JOIN motivo m ON po.motivo_paralizado = m.idMotivo
					  LEFT JOIN (select sisego, pep2, grafo from sisego_pep2_grafo where estado in (0,1,2)
					  group by sisego) as tb ON po.indicador = tb.sisego
					LEFT JOIN solicitud_orden_compra soc_crea ON po.solicitud_oc = soc_crea.codigo_solicitud
					LEFT JOIN solicitud_orden_compra soc_edic ON po.solicitud_oc_dev = soc_edic.codigo_solicitud
					LEFT JOIN solicitud_orden_compra soc_cert ON po.solicitud_oc_certi = soc_cert.codigo_solicitud
					LEFT JOIN solicitud_orden_compra soc_anul ON po.solicitud_oc_anula_pos = soc_anul.codigo_solicitud
					LEFT JOIN itemplan_solicitud_orden_compra soc_crea_opex ON po.solicitud_oc = soc_crea_opex.codigo_solicitud
					LEFT JOIN itemplan_solicitud_orden_compra soc_edic_opex ON po.solicitud_oc_dev = soc_edic_opex.codigo_solicitud
					LEFT JOIN itemplan_solicitud_orden_compra soc_cert_opex ON po.solicitud_oc_certi = soc_cert_opex.codigo_solicitud
					LEFT JOIN itemplan_solicitud_orden_compra soc_anul_opex ON po.solicitud_oc_anula_pos = soc_anul_opex.codigo_solicitud 
                                        										  left join
                                        										  pqt_solicitud_aprob_partidas_adicionales pqa on po.itemplan = pqa.itemplan and pqa.estado in (0,1,2)
                                        										  WHERE
                                        										  po.idEstadoPlan in (9,4,5,21,22,23) AND po.fechaPreliquidacion >= '2020-01-01'
                                        										      and (CASE WHEN po.idEstadoPlan in (23) THEN  pqa.estado not in (2) else true end)
                    
                                        										      AND po.idSubProyecto in (13,14,15,619,583,620)
                                        										      AND po.paquetizado_fg in (1,2)
                                        										      AND po.idCentralPqt = c.idCentral
                                        										      AND po.idEmpresaColab = ec.idEmpresaColab
                                        										      AND c.idZonal = z.idZonal
                                        										      AND po.idEstadoPlan = ep.idEstadoPlan
                                        										      union all
                                        										      SELECT po.itemplan, po.indicador, po.nombreProyecto, c.jefatura, z.zonalDesc, ec.empresaColabDesc, ep.estadoPlanDesc, po.has_paralizado, UPPER(m.motivoDesc) as motivoDesc,
                                        										      (CASE 	WHEN po.solicitud_oc IS NOT NULL AND po.estado_sol_oc != 'CANCELADO' THEN 'CON PRESUP'
                                        										          WHEN  po.motivo_paralizado = 11 THEN 'SIN PRESUP'
                                        										              WHEN  po.motivo_paralizado = 67 THEN 'SIN COTIZACION'
                                        										                  ELSE 'SIN PRESUP' END) AS has_presupuesto,
                                        										                  (CASE WHEN po.idEmpresaColab IN (1,2) AND c.region = 'LIMA' THEN 'ACELERA'
                                        										                      WHEN po.idEmpresaColab NOT IN (1,2) AND c.region = 'LIMA' THEN 'DESPLIEGUE'
                                        										                          WHEN c.region != 'LIMA' THEN 'ZONALES' END) as responsabilidad,po.fechaPreliquidacion, (case when po.idEstadoPlan = 10 THEN po.fechaTrunca
                                        										                          when po.idEstadoPlan = 6 THEN po.fechaCancelacion end) as fec_trunc_cancel,
                                        										                          tb.pep2, tb.grafo,
                                                                            (CASE WHEN po.idSubProyecto IN (13,14,15) THEN 
																(CASE WHEN soc_crea.estado = 2 THEN  soc_crea.codigo_solicitud else null END)
															  WHEN po.idSubProyecto IN (619,583,620) THEN  
																(CASE WHEN soc_crea_opex.estado = 2 THEN  soc_crea_opex.codigo_solicitud else null  END) 
														END) sol_crea, 
                                                
												(CASE WHEN po.idSubProyecto IN (13,14,15) THEN 
																(CASE WHEN soc_crea.estado = 2 THEN  soc_crea.orden_compra else null end)
															  WHEN po.idSubProyecto IN (619,583,620) THEN  
																(CASE WHEN soc_crea_opex.estado = 2 THEN  soc_crea_opex.orden_compra else null end) 
														END) orden_compra,
                                                        
												 (CASE WHEN po.idSubProyecto IN (13,14,15) THEN 
															(CASE WHEN soc_crea.estado = 2 AND (soc_edic.estado IS NULL OR soc_edic.estado IN (1,3)) AND (soc_anul.estado IS NULL OR soc_anul.estado IN (1,3))  AND (soc_cert.estado IS NULL OR soc_cert.estado IN (1,3)) THEN 'CREADO'
																  WHEN soc_crea.estado = 2 AND soc_edic.estado = 2 AND (soc_anul.estado IS NULL OR soc_anul.estado IN (1,3))  AND (soc_cert.estado IS NULL OR soc_cert.estado IN (1,3)) THEN 'EDITADO'
																  WHEN soc_crea.estado = 2 AND soc_cert.estado = 2 THEN 'CERTIFICADO' 
																  WHEN soc_crea.estado = 2 AND soc_anul.estado = 2 AND (soc_cert.estado IS NULL OR soc_cert.estado IN (1,3)) THEN 'ANULADO' END) 
														  WHEN po.idSubProyecto IN (619,583,620) THEN  
																(CASE WHEN soc_crea_opex.estado = 2 AND (soc_edic_opex.estado IS NULL OR soc_edic_opex.estado IN (1,3)) AND (soc_anul_opex.estado IS NULL OR soc_anul_opex.estado IN (1,3))  AND (soc_cert_opex.estado IS NULL OR soc_cert_opex.estado IN (1,3)) THEN 'CREADO'
																  WHEN soc_crea_opex.estado = 2 AND soc_edic_opex.estado = 2 AND (soc_anul_opex.estado IS NULL OR soc_anul_opex.estado IN (1,3))  AND (soc_cert_opex.estado IS NULL OR soc_cert_opex.estado IN (1,3)) THEN 'EDITADO'
																  WHEN soc_crea_opex.estado = 2 AND soc_cert_opex.estado = 2 THEN 'CERTIFICADO' 
																  WHEN soc_crea_opex.estado = 2 AND soc_anul_opex.estado = 2 AND (soc_cert_opex.estado IS NULL OR soc_cert_opex.estado IN (1,3)) THEN 'ANULADO' END)
												END)	estado_oc,
                                                
												(CASE WHEN po.idSubProyecto IN (13,14,15) THEN 
																(CASE WHEN soc_crea.estado = 2 AND (soc_edic.estado IS NULL OR soc_edic.estado IN (1,3)) AND (soc_anul.estado IS NULL OR soc_anul.estado IN (1,3))  AND (soc_cert.estado IS NULL OR soc_cert.estado IN (1,3)) THEN po.costo_unitario_mo_crea_oc
																	  WHEN soc_crea.estado = 2 AND soc_edic.estado = 2 AND (soc_anul.estado IS NULL OR soc_anul.estado IN (1,3))  AND (soc_cert.estado IS NULL OR soc_cert.estado IN (1,3)) THEN po.costo_devolucion
																	  WHEN soc_crea.estado = 2 AND soc_cert.estado = 2 THEN po.costo_unitario_mo_certi
																	  WHEN soc_crea.estado = 2 AND soc_anul.estado = 2 AND (soc_cert.estado IS NULL OR soc_cert.estado IN (1,3)) THEN po.costo_unitario_mo_anula_pos END)
															WHEN po.idSubProyecto IN (619,583,620) THEN  
																(CASE WHEN soc_crea_opex.estado = 2 AND (soc_edic_opex.estado IS NULL OR soc_edic_opex.estado IN (1,3)) AND (soc_anul_opex.estado IS NULL OR soc_anul_opex.estado IN (1,3))  AND (soc_cert_opex.estado IS NULL OR soc_cert_opex.estado IN (1,3)) THEN po.costo_unitario_mo_crea_oc
																	  WHEN soc_crea_opex.estado = 2 AND soc_edic_opex.estado = 2 AND (soc_anul_opex.estado IS NULL OR soc_anul_opex.estado IN (1,3))  AND (soc_cert_opex.estado IS NULL OR soc_cert_opex.estado IN (1,3)) THEN po.costo_devolucion
																	  WHEN soc_crea_opex.estado = 2 AND soc_cert_opex.estado = 2 THEN po.costo_unitario_mo_certi
																	  WHEN soc_crea_opex.estado = 2 AND soc_anul_opex.estado = 2 AND (soc_cert_opex.estado IS NULL OR soc_cert_opex.estado IN (1,3)) THEN po.costo_unitario_mo_anula_pos END)                                                            
												END) as monto_oc
					FROM central c, zonal z, empresacolab ec, estadoplan ep, planobra po
					LEFT JOIN motivo m ON po.motivo_paralizado = m.idMotivo
					LEFT JOIN (select sisego, pep2, grafo from sisego_pep2_grafo where estado in (0,1,2)
					group by sisego) as tb ON po.indicador = tb.sisego
					LEFT JOIN solicitud_orden_compra soc_crea ON po.solicitud_oc = soc_crea.codigo_solicitud
					LEFT JOIN solicitud_orden_compra soc_edic ON po.solicitud_oc_dev = soc_edic.codigo_solicitud
					LEFT JOIN solicitud_orden_compra soc_cert ON po.solicitud_oc_certi = soc_cert.codigo_solicitud
					LEFT JOIN solicitud_orden_compra soc_anul ON po.solicitud_oc_anula_pos = soc_anul.codigo_solicitud
					LEFT JOIN itemplan_solicitud_orden_compra soc_crea_opex ON po.solicitud_oc = soc_crea_opex.codigo_solicitud
					LEFT JOIN itemplan_solicitud_orden_compra soc_edic_opex ON po.solicitud_oc_dev = soc_edic_opex.codigo_solicitud
					LEFT JOIN itemplan_solicitud_orden_compra soc_cert_opex ON po.solicitud_oc_certi = soc_cert_opex.codigo_solicitud
					LEFT JOIN itemplan_solicitud_orden_compra soc_anul_opex ON po.solicitud_oc_anula_pos = soc_anul_opex.codigo_solicitud 
                                                                                        left join
                                                                                        pqt_solicitud_aprob_partidas_adicionales pqa on pqa.itemplan =  po.itemplan and pqa.estado in (0,1,2)
                                                                                        WHERE
                                                                                        po.idEstadoPlan in (9,4,5,21,22,23) AND po.fechaPreliquidacion >= '2020-01-01'
                                                                                            and (CASE WHEN po.idEstadoPlan in (23) THEN  pqa.estado not in (2) else true end)
                                                                                            AND po.idSubProyecto in (13,14,15,619,583,620)
                                                                                            AND po.paquetizado_fg is null
                                                                                            AND po.idCentral = c.idCentral
                                                                                            AND po.idEmpresaColab = ec.idEmpresaColab
                                                                                            AND c.idZonal = z.idZonal
                                                                                            AND po.idEstadoPlan = ep.idEstadoPlan
                    ) as tb
                    having tb.has_presupuesto = ?";
        $result = $this->db->query($sql, array($estadoPPTO));
        return $result->result();
    }
    
    /*po.idEstadoPlan in (9,4,5,21,22,23) AND po.fechaPreliquidacion >= '2020-01-01'
                                        										      and (CASE WHEN po.idEstadoPlan in (23) THEN  pqa.estado not in (2) else true end)
																						and (pqa.id_solicitud is null or pqa.estado in (3))*/
    function getDataTablaDetalleTerminados($condicion) {
        
        $condicional = $this->getCondicionalToQueryDetalle($condicion, null);
        $sql = "SELECT po.itemplan, po.indicador, po.nombreProyecto, c.jefatura, z.zonalDesc, ec.empresaColabDesc, ep.estadoPlanDesc, po.has_paralizado, UPPER(m.motivoDesc) as motivoDesc,
                    (CASE 	WHEN po.solicitud_oc IS NOT NULL AND po.estado_sol_oc != 'CANCELADO' THEN 'CON PRESUP'
                        WHEN  po.motivo_paralizado = 11 THEN 'SIN PRESUP'
                            WHEN  po.motivo_paralizado = 67 THEN 'SIN COTIZACION'
                                ELSE 'SIN PRESUP' END) AS has_presupuesto,
                                (CASE WHEN po.idEmpresaColab IN (1,2) AND c.region = 'LIMA' THEN 'ACELERA'
                                    WHEN po.idEmpresaColab NOT IN (1,2) AND c.region = 'LIMA' THEN 'DESPLIEGUE'
                                        WHEN c.region != 'LIMA' THEN 'ZONALES' END) as responsabilidad,po.fechaPreliquidacion, (case when po.idEstadoPlan = 10 THEN po.fechaTrunca
                                        when po.idEstadoPlan = 6 THEN po.fechaCancelacion end) as fec_trunc_cancel,
                                        tb.pep2, tb.grafo,
                                        (CASE WHEN po.idSubProyecto IN (13,14,15) THEN 
																(CASE WHEN soc_crea.estado = 2 THEN  soc_crea.codigo_solicitud else null END)
															  WHEN po.idSubProyecto IN (619,583,620) THEN  
																(CASE WHEN soc_crea_opex.estado = 2 THEN  soc_crea_opex.codigo_solicitud else null  END) 
														END) sol_crea, 
                                                
												(CASE WHEN po.idSubProyecto IN (13,14,15) THEN 
																(CASE WHEN soc_crea.estado = 2 THEN  soc_crea.orden_compra else null end)
															  WHEN po.idSubProyecto IN (619,583,620) THEN  
																(CASE WHEN soc_crea_opex.estado = 2 THEN  soc_crea_opex.orden_compra else null end) 
														END) orden_compra,
                                                        
												 (CASE WHEN po.idSubProyecto IN (13,14,15) THEN 
															(CASE WHEN soc_crea.estado = 2 AND (soc_edic.estado IS NULL OR soc_edic.estado IN (1,3)) AND (soc_anul.estado IS NULL OR soc_anul.estado IN (1,3))  AND (soc_cert.estado IS NULL OR soc_cert.estado IN (1,3)) THEN 'CREADO'
																  WHEN soc_crea.estado = 2 AND soc_edic.estado = 2 AND (soc_anul.estado IS NULL OR soc_anul.estado IN (1,3))  AND (soc_cert.estado IS NULL OR soc_cert.estado IN (1,3)) THEN 'EDITADO'
																  WHEN soc_crea.estado = 2 AND soc_cert.estado = 2 THEN 'CERTIFICADO' 
																  WHEN soc_crea.estado = 2 AND soc_anul.estado = 2 AND (soc_cert.estado IS NULL OR soc_cert.estado IN (1,3)) THEN 'ANULADO' END) 
														  WHEN po.idSubProyecto IN (619,583,620) THEN  
																(CASE WHEN soc_crea_opex.estado = 2 AND (soc_edic_opex.estado IS NULL OR soc_edic_opex.estado IN (1,3)) AND (soc_anul_opex.estado IS NULL OR soc_anul_opex.estado IN (1,3))  AND (soc_cert_opex.estado IS NULL OR soc_cert_opex.estado IN (1,3)) THEN 'CREADO'
																  WHEN soc_crea_opex.estado = 2 AND soc_edic_opex.estado = 2 AND (soc_anul_opex.estado IS NULL OR soc_anul_opex.estado IN (1,3))  AND (soc_cert_opex.estado IS NULL OR soc_cert_opex.estado IN (1,3)) THEN 'EDITADO'
																  WHEN soc_crea_opex.estado = 2 AND soc_cert_opex.estado = 2 THEN 'CERTIFICADO' 
																  WHEN soc_crea_opex.estado = 2 AND soc_anul_opex.estado = 2 AND (soc_cert_opex.estado IS NULL OR soc_cert_opex.estado IN (1,3)) THEN 'ANULADO' END)
												END)	estado_oc,
                                                
												(CASE WHEN po.idSubProyecto IN (13,14,15) THEN 
																(CASE WHEN soc_crea.estado = 2 AND (soc_edic.estado IS NULL OR soc_edic.estado IN (1,3)) AND (soc_anul.estado IS NULL OR soc_anul.estado IN (1,3))  AND (soc_cert.estado IS NULL OR soc_cert.estado IN (1,3)) THEN po.costo_unitario_mo_crea_oc
																	  WHEN soc_crea.estado = 2 AND soc_edic.estado = 2 AND (soc_anul.estado IS NULL OR soc_anul.estado IN (1,3))  AND (soc_cert.estado IS NULL OR soc_cert.estado IN (1,3)) THEN po.costo_devolucion
																	  WHEN soc_crea.estado = 2 AND soc_cert.estado = 2 THEN po.costo_unitario_mo_certi
																	  WHEN soc_crea.estado = 2 AND soc_anul.estado = 2 AND (soc_cert.estado IS NULL OR soc_cert.estado IN (1,3)) THEN po.costo_unitario_mo_anula_pos END)
															WHEN po.idSubProyecto IN (619,583,620) THEN  
																(CASE WHEN soc_crea_opex.estado = 2 AND (soc_edic_opex.estado IS NULL OR soc_edic_opex.estado IN (1,3)) AND (soc_anul_opex.estado IS NULL OR soc_anul_opex.estado IN (1,3))  AND (soc_cert_opex.estado IS NULL OR soc_cert_opex.estado IN (1,3)) THEN po.costo_unitario_mo_crea_oc
																	  WHEN soc_crea_opex.estado = 2 AND soc_edic_opex.estado = 2 AND (soc_anul_opex.estado IS NULL OR soc_anul_opex.estado IN (1,3))  AND (soc_cert_opex.estado IS NULL OR soc_cert_opex.estado IN (1,3)) THEN po.costo_devolucion
																	  WHEN soc_crea_opex.estado = 2 AND soc_cert_opex.estado = 2 THEN po.costo_unitario_mo_certi
																	  WHEN soc_crea_opex.estado = 2 AND soc_anul_opex.estado = 2 AND (soc_cert_opex.estado IS NULL OR soc_cert_opex.estado IN (1,3)) THEN po.costo_unitario_mo_anula_pos END)                                                            
												END) as monto_oc
					  FROM pqt_central c, zonal z, empresacolab ec, estadoplan ep, planobra po
					  LEFT JOIN motivo m ON po.motivo_paralizado = m.idMotivo
					  LEFT JOIN (select sisego, pep2, grafo from sisego_pep2_grafo where estado in (0,1,2)
					  group by sisego) as tb ON po.indicador = tb.sisego
					  LEFT JOIN solicitud_orden_compra soc_crea ON po.solicitud_oc = soc_crea.codigo_solicitud
					  LEFT JOIN solicitud_orden_compra soc_edic ON po.solicitud_oc_dev = soc_edic.codigo_solicitud
					  LEFT JOIN solicitud_orden_compra soc_cert ON po.solicitud_oc_certi = soc_cert.codigo_solicitud
					  LEFT JOIN solicitud_orden_compra soc_anul ON po.solicitud_oc_anula_pos = soc_anul.codigo_solicitud
					  LEFT JOIN itemplan_solicitud_orden_compra soc_crea_opex ON po.solicitud_oc = soc_crea_opex.codigo_solicitud
					LEFT JOIN itemplan_solicitud_orden_compra soc_edic_opex ON po.solicitud_oc_dev = soc_edic_opex.codigo_solicitud
					LEFT JOIN itemplan_solicitud_orden_compra soc_cert_opex ON po.solicitud_oc_certi = soc_cert_opex.codigo_solicitud
					LEFT JOIN itemplan_solicitud_orden_compra soc_anul_opex ON po.solicitud_oc_anula_pos = soc_anul_opex.codigo_solicitud 
                                        										  left join
                                        										  pqt_solicitud_aprob_partidas_adicionales pqa on po.itemplan = pqa.itemplan and pqa.estado in (0,1,2)
                                        										  WHERE 
                                                                                  po.idEstadoPlan in (9,4,5,21,22,23) AND po.fechaPreliquidacion >= '2020-01-01'
                                                                                  ".$condicional."
                                        										  
                                        										      AND po.idSubProyecto in (13,14,15,619,583,620)
                                        										      AND po.paquetizado_fg in (1,2)
                                        										      AND po.idCentralPqt = c.idCentral
                                        										      AND po.idEmpresaColab = ec.idEmpresaColab
                                        										      AND c.idZonal = z.idZonal
                                        										      AND po.idEstadoPlan = ep.idEstadoPlan
                                        										      union all
                                        										      SELECT po.itemplan, po.indicador, po.nombreProyecto, c.jefatura, z.zonalDesc, ec.empresaColabDesc, ep.estadoPlanDesc, po.has_paralizado, UPPER(m.motivoDesc) as motivoDesc,
                                        										      (CASE 	WHEN po.solicitud_oc IS NOT NULL AND po.estado_sol_oc != 'CANCELADO' THEN 'CON PRESUP'
                                        										          WHEN  po.motivo_paralizado = 11 THEN 'SIN PRESUP'
                                        										              WHEN  po.motivo_paralizado = 67 THEN 'SIN COTIZACION'
                                        										                  ELSE 'SIN PRESUP' END) AS has_presupuesto,
                                        										                  (CASE WHEN po.idEmpresaColab IN (1,2) AND c.region = 'LIMA' THEN 'ACELERA'
                                        										                      WHEN po.idEmpresaColab NOT IN (1,2) AND c.region = 'LIMA' THEN 'DESPLIEGUE'
                                        										                          WHEN c.region != 'LIMA' THEN 'ZONALES' END) as responsabilidad,po.fechaPreliquidacion, (case when po.idEstadoPlan = 10 THEN po.fechaTrunca
                                        										                          when po.idEstadoPlan = 6 THEN po.fechaCancelacion end) as fec_trunc_cancel,
                                        										                          tb.pep2, tb.grafo,
                                                                            (CASE WHEN po.idSubProyecto IN (13,14,15) THEN 
																(CASE WHEN soc_crea.estado = 2 THEN  soc_crea.codigo_solicitud else null END)
															  WHEN po.idSubProyecto IN (619,583,620) THEN  
																(CASE WHEN soc_crea_opex.estado = 2 THEN  soc_crea_opex.codigo_solicitud else null  END) 
														END) sol_crea, 
                                                
												(CASE WHEN po.idSubProyecto IN (13,14,15) THEN 
																(CASE WHEN soc_crea.estado = 2 THEN  soc_crea.orden_compra else null end)
															  WHEN po.idSubProyecto IN (619,583,620) THEN  
																(CASE WHEN soc_crea_opex.estado = 2 THEN  soc_crea_opex.orden_compra else null end) 
														END) orden_compra,
                                                        
												 (CASE WHEN po.idSubProyecto IN (13,14,15) THEN 
															(CASE WHEN soc_crea.estado = 2 AND (soc_edic.estado IS NULL OR soc_edic.estado IN (1,3)) AND (soc_anul.estado IS NULL OR soc_anul.estado IN (1,3))  AND (soc_cert.estado IS NULL OR soc_cert.estado IN (1,3)) THEN 'CREADO'
																  WHEN soc_crea.estado = 2 AND soc_edic.estado = 2 AND (soc_anul.estado IS NULL OR soc_anul.estado IN (1,3))  AND (soc_cert.estado IS NULL OR soc_cert.estado IN (1,3)) THEN 'EDITADO'
																  WHEN soc_crea.estado = 2 AND soc_cert.estado = 2 THEN 'CERTIFICADO' 
																  WHEN soc_crea.estado = 2 AND soc_anul.estado = 2 AND (soc_cert.estado IS NULL OR soc_cert.estado IN (1,3)) THEN 'ANULADO' END) 
														  WHEN po.idSubProyecto IN (619,583,620) THEN  
																(CASE WHEN soc_crea_opex.estado = 2 AND (soc_edic_opex.estado IS NULL OR soc_edic_opex.estado IN (1,3)) AND (soc_anul_opex.estado IS NULL OR soc_anul_opex.estado IN (1,3))  AND (soc_cert_opex.estado IS NULL OR soc_cert_opex.estado IN (1,3)) THEN 'CREADO'
																  WHEN soc_crea_opex.estado = 2 AND soc_edic_opex.estado = 2 AND (soc_anul_opex.estado IS NULL OR soc_anul_opex.estado IN (1,3))  AND (soc_cert_opex.estado IS NULL OR soc_cert_opex.estado IN (1,3)) THEN 'EDITADO'
																  WHEN soc_crea_opex.estado = 2 AND soc_cert_opex.estado = 2 THEN 'CERTIFICADO' 
																  WHEN soc_crea_opex.estado = 2 AND soc_anul_opex.estado = 2 AND (soc_cert_opex.estado IS NULL OR soc_cert_opex.estado IN (1,3)) THEN 'ANULADO' END)
												END)	estado_oc,
                                                
												(CASE WHEN po.idSubProyecto IN (13,14,15) THEN 
																(CASE WHEN soc_crea.estado = 2 AND (soc_edic.estado IS NULL OR soc_edic.estado IN (1,3)) AND (soc_anul.estado IS NULL OR soc_anul.estado IN (1,3))  AND (soc_cert.estado IS NULL OR soc_cert.estado IN (1,3)) THEN po.costo_unitario_mo_crea_oc
																	  WHEN soc_crea.estado = 2 AND soc_edic.estado = 2 AND (soc_anul.estado IS NULL OR soc_anul.estado IN (1,3))  AND (soc_cert.estado IS NULL OR soc_cert.estado IN (1,3)) THEN po.costo_devolucion
																	  WHEN soc_crea.estado = 2 AND soc_cert.estado = 2 THEN po.costo_unitario_mo_certi
																	  WHEN soc_crea.estado = 2 AND soc_anul.estado = 2 AND (soc_cert.estado IS NULL OR soc_cert.estado IN (1,3)) THEN po.costo_unitario_mo_anula_pos END)
															WHEN po.idSubProyecto IN (619,583,620) THEN  
																(CASE WHEN soc_crea_opex.estado = 2 AND (soc_edic_opex.estado IS NULL OR soc_edic_opex.estado IN (1,3)) AND (soc_anul_opex.estado IS NULL OR soc_anul_opex.estado IN (1,3))  AND (soc_cert_opex.estado IS NULL OR soc_cert_opex.estado IN (1,3)) THEN po.costo_unitario_mo_crea_oc
																	  WHEN soc_crea_opex.estado = 2 AND soc_edic_opex.estado = 2 AND (soc_anul_opex.estado IS NULL OR soc_anul_opex.estado IN (1,3))  AND (soc_cert_opex.estado IS NULL OR soc_cert_opex.estado IN (1,3)) THEN po.costo_devolucion
																	  WHEN soc_crea_opex.estado = 2 AND soc_cert_opex.estado = 2 THEN po.costo_unitario_mo_certi
																	  WHEN soc_crea_opex.estado = 2 AND soc_anul_opex.estado = 2 AND (soc_cert_opex.estado IS NULL OR soc_cert_opex.estado IN (1,3)) THEN po.costo_unitario_mo_anula_pos END)                                                            
												END) as monto_oc
					FROM central c, zonal z, empresacolab ec, estadoplan ep, planobra po
					LEFT JOIN motivo m ON po.motivo_paralizado = m.idMotivo
					LEFT JOIN (select sisego, pep2, grafo from sisego_pep2_grafo where estado in (0,1,2)
					group by sisego) as tb ON po.indicador = tb.sisego
					LEFT JOIN solicitud_orden_compra soc_crea ON po.solicitud_oc = soc_crea.codigo_solicitud
					LEFT JOIN solicitud_orden_compra soc_edic ON po.solicitud_oc_dev = soc_edic.codigo_solicitud
					LEFT JOIN solicitud_orden_compra soc_cert ON po.solicitud_oc_certi = soc_cert.codigo_solicitud
					LEFT JOIN solicitud_orden_compra soc_anul ON po.solicitud_oc_anula_pos = soc_anul.codigo_solicitud
					LEFT JOIN itemplan_solicitud_orden_compra soc_crea_opex ON po.solicitud_oc = soc_crea_opex.codigo_solicitud
					LEFT JOIN itemplan_solicitud_orden_compra soc_edic_opex ON po.solicitud_oc_dev = soc_edic_opex.codigo_solicitud
					LEFT JOIN itemplan_solicitud_orden_compra soc_cert_opex ON po.solicitud_oc_certi = soc_cert_opex.codigo_solicitud
					LEFT JOIN itemplan_solicitud_orden_compra soc_anul_opex ON po.solicitud_oc_anula_pos = soc_anul_opex.codigo_solicitud 
                                                                                        left join
                                                                                        pqt_solicitud_aprob_partidas_adicionales pqa on pqa.itemplan =  po.itemplan and pqa.estado in (0,1,2)
                                                                                        WHERE 
                                        										          po.idEstadoPlan in (9,4,5,21,22,23) AND po.fechaPreliquidacion >= '2020-01-01'
                                                                                        ".$condicional."
                                                                                            AND po.idSubProyecto in (13,14,15,619,583,620)
                                                                                            AND po.paquetizado_fg is null
                                                                                            AND po.idCentral = c.idCentral
                                                                                            AND po.idEmpresaColab = ec.idEmpresaColab
                                                                                            AND c.idZonal = z.idZonal
                                                                                            AND po.idEstadoPlan = ep.idEstadoPlan";
        $result = $this->db->query($sql, array());
        return $result->result();
    }
    
    function getCondicionalToQueryDetalle($tipo, $region){
        $condicional = ' 1 = 1 ';
        if($tipo    ==  1){// PENDIENTE TOTAL DE PRESUPUESTO
            $condicional = " and (CASE WHEN po.idEstadoPlan in (23) THEN  pqa.estado not in (2) else true end)
																						and (pqa.id_solicitud is null or pqa.estado in (3))";
        }else if($tipo    ==  2){// PENDIENTE DE GETEC
            $condicional = " and pqa.id_solicitud is not null AND pqa.estado in (0,1)";
        }else if($tipo    ==  3){//TOTAL DESPLIGUE RED
            $condicional = " and pqa.id_solicitud is not null AND pqa.estado in (2) and po.idEstadoPlan != 23";
        }else if($tipo    ==  4){//TOTAL ACELERA
            $condicional = " and pqa.id_solicitud is not null AND pqa.estado in (2) and po.idEstadoPlan = 23";
        }
    
        return $condicional;
    }
}