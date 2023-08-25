<?php
class M_estatus_certificacion extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
	function   getBandejaAlarmasMO($eecc){
	    $iddEECCPO = 0;
	    if($eecc   ==  'CALATEL'){
	        $iddEECCPO = ID_EECC_EZENTIS;
	    }else if($eecc == 'DOMINIONPERU SOLUCIONES Y SERVICIOS S.A.C.'){
	        $iddEECCPO = ID_EECC_DOMINION;
	    }else if($eecc   ==  'LARI'){
	        $iddEECCPO =   ID_EECC_LARI;
	    }else if($eecc   ==  'COBRA'){
	        $iddEECCPO =   ID_EECC_COBRA;
	    }
	    
	    $Query = "SELECT tb.descripcion, tb.origen, 
	                     SUM(hasta3) as hasta3, SUM(total_hasta3) as total_hasta3, 
                         SUM(hasta7) as  hasta7, SUM(total_hasta7) as total_hasta7,
                         SUM(todo) as todo, SUM(total_todo) as total_todo FROM (
	           SELECT 'GESTION DE VENTANILLA UNICA' as descripcion, 2 as origen,
            			 SUM(CASE WHEN mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -3 DAY)) AND NOW() THEN 1 ELSE NULL END) as hasta3,
            			 SUM(CASE WHEN mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -3 DAY)) AND NOW() THEN mo.monto_mo ELSE NULL END) as total_hasta3,
            			 SUM(CASE WHEN mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -7 DAY)) AND DATE(date_add(NOW(), INTERVAL -4 DAY)) THEN 1 ELSE NULL END) as hasta7,
            			 SUM(CASE WHEN mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -7 DAY)) AND DATE(date_add(NOW(), INTERVAL -4 DAY)) THEN mo.monto_mo ELSE NULL END) as total_hasta7,
            			 SUM(CASE WHEN mo.fecha  <=  DATE(date_add(NOW(), INTERVAL -8 DAY)) THEN 1 ELSE NULL END) as todo,
            			 SUM(CASE WHEN mo.fecha  <=  DATE(date_add(NOW(), INTERVAL -8 DAY)) THEN mo.monto_mo ELSE NULL END) as total_todo	           
                FROM    certificacion_mo mo, web_unificada wu
                WHERE	mo.ptr = wu.ptr
                
                AND		mo.estado = ".CERTIFICACION_MO_CON_PRESUPUESTO." ";
             if($eecc!=''){
                 $Query .= " AND wu.eecc = '".$eecc."'";
             }
             
             $Query .= " UNION ALL 
             SELECT 'GESTION DE VENTANILLA UNICA' as descripcion, 2 as origen,
             SUM(CASE WHEN mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -3 DAY)) AND NOW() THEN 1 ELSE NULL END) as hasta3,
             SUM(CASE WHEN mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -3 DAY)) AND NOW() THEN mo.monto_mo ELSE NULL END) as total_hasta3,
             SUM(CASE WHEN mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -7 DAY)) AND DATE(date_add(NOW(), INTERVAL -4 DAY)) THEN 1 ELSE NULL END) as hasta7,
             SUM(CASE WHEN mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -7 DAY)) AND DATE(date_add(NOW(), INTERVAL -4 DAY)) THEN mo.monto_mo ELSE NULL END) as total_hasta7,
             SUM(CASE WHEN mo.fecha  <=  DATE(date_add(NOW(), INTERVAL -8 DAY)) THEN 1 ELSE NULL END) as todo,
             SUM(CASE WHEN mo.fecha  <=  DATE(date_add(NOW(), INTERVAL -8 DAY)) THEN mo.monto_mo ELSE NULL END) as total_todo
             FROM    certificacion_mo mo, planobra_po ppo
             WHERE	 mo.ptr = ppo.codigo_po		             
             AND		mo.estado = ".CERTIFICACION_MO_CON_PRESUPUESTO." ";
             if($eecc!=''){
             $Query .= "AND ppo.id_eecc_reg = '".$iddEECCPO."'";
             }
             
            $Query .= "   ) as tb 
                        GROUP BY tb.descripcion , tb.origen";
             
             
    $Query .= " UNION ALL 
        
                SELECT tb.descripcion, tb.origen, 
	                     SUM(hasta3) as hasta3, SUM(total_hasta3) as total_hasta3, 
                         SUM(hasta7) as  hasta7, SUM(total_hasta7) as total_hasta7,
                         SUM(todo) as todo, SUM(total_todo) as total_todo FROM (
                SELECT 'GESTION DE INGENIERIA' as descripcion, 1 as origen,
                                         SUM(CASE WHEN mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -3 DAY)) AND NOW() THEN 1 ELSE NULL END) as hasta3,
                            			 SUM(CASE WHEN mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -3 DAY)) AND NOW() THEN mo.monto_mo ELSE NULL END) as total_hasta3,
                            			 SUM(CASE WHEN mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -7 DAY)) AND DATE(date_add(NOW(), INTERVAL -4 DAY)) THEN 1 ELSE NULL END) as hasta7,
                            			 SUM(CASE WHEN mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -7 DAY)) AND DATE(date_add(NOW(), INTERVAL -4 DAY)) THEN mo.monto_mo ELSE NULL END) as total_hasta7,
                            			 SUM(CASE WHEN mo.fecha  <=  DATE(date_add(NOW(), INTERVAL -8 DAY)) THEN 1 ELSE NULL END) as todo,
                            			 SUM(CASE WHEN mo.fecha  <=  DATE(date_add(NOW(), INTERVAL -8 DAY)) THEN mo.monto_mo ELSE NULL END) as total_todo
                FROM
                		certificacion_mo mo, web_unificada wu
                WHERE	mo.ptr = wu.ptr 
                AND 	mo.estado not in (".CERTIFICACION_MO_CON_PRESUPUESTO.",".CERTIFICACION_MO_CON_ORDEN_COMPRA.",".CERTIFICACION_MO_SIN_ITEMPLAN_ASOCIADO.") ";
    if($eecc!=''){
        $Query .= "AND wu.eecc = '".$eecc."'";
    }                      
    
    $Query .= " UNION ALL
                
                SELECT 'GESTION DE INGENIERIA' as descripcion, 1 as origen,
                                         SUM(CASE WHEN mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -3 DAY)) AND NOW() THEN 1 ELSE NULL END) as hasta3,
                            			 SUM(CASE WHEN mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -3 DAY)) AND NOW() THEN mo.monto_mo ELSE NULL END) as total_hasta3,
                            			 SUM(CASE WHEN mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -7 DAY)) AND DATE(date_add(NOW(), INTERVAL -4 DAY)) THEN 1 ELSE NULL END) as hasta7,
                            			 SUM(CASE WHEN mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -7 DAY)) AND DATE(date_add(NOW(), INTERVAL -4 DAY)) THEN mo.monto_mo ELSE NULL END) as total_hasta7,
                            			 SUM(CASE WHEN mo.fecha  <=  DATE(date_add(NOW(), INTERVAL -8 DAY)) THEN 1 ELSE NULL END) as todo,
                            			 SUM(CASE WHEN mo.fecha  <=  DATE(date_add(NOW(), INTERVAL -8 DAY)) THEN mo.monto_mo ELSE NULL END) as total_todo
                FROM
                		certificacion_mo mo, planobra_po ppo
                WHERE	mo.ptr = ppo.codigo_po		
                AND 	mo.estado not in (".CERTIFICACION_MO_CON_PRESUPUESTO.",".CERTIFICACION_MO_CON_ORDEN_COMPRA.",".CERTIFICACION_MO_SIN_ITEMPLAN_ASOCIADO.") ";
    if($eecc!=''){
        $Query .= "AND ppo.id_eecc_reg = '".$iddEECCPO."'";
    }                      
    $Query .= "   ) as tb
                        GROUP BY tb.descripcion , tb.origen";
   $Query .= "  UNION ALL 
       
                SELECT tb.descripcion, tb.origen, 
	                     SUM(hasta3) as hasta3, SUM(total_hasta3) as total_hasta3, 
                         SUM(hasta7) as  hasta7, SUM(total_hasta7) as total_hasta7,
                         SUM(todo) as todo, SUM(total_todo) as total_todo FROM (
                SELECT 'CERTIFICADO' as descripcion, 3 as origen,
                		 SUM(CASE WHEN mo.fec_reg_oc  BETWEEN  DATE(date_add(NOW(), INTERVAL -3 DAY)) AND NOW() THEN 1 ELSE NULL END) as hasta3,
                		 SUM(CASE WHEN mo.fec_reg_oc  BETWEEN  DATE(date_add(NOW(), INTERVAL -3 DAY)) AND NOW() THEN mo.monto_mo ELSE NULL END) as total_hasta3,
                		 SUM(CASE WHEN mo.fec_reg_oc  BETWEEN  DATE(date_add(NOW(), INTERVAL -7 DAY)) AND DATE(date_add(NOW(), INTERVAL -4 DAY)) THEN 1 ELSE NULL END) as hasta7,
                		 SUM(CASE WHEN mo.fec_reg_oc  BETWEEN  DATE(date_add(NOW(), INTERVAL -7 DAY)) AND DATE(date_add(NOW(), INTERVAL -4 DAY)) THEN mo.monto_mo ELSE NULL END) as total_hasta7,
                		 SUM(CASE WHEN mo.fec_reg_oc  <=  DATE(date_add(NOW(), INTERVAL -8 DAY)) THEN 1 ELSE NULL END) as todo,
                		 SUM(CASE WHEN mo.fec_reg_oc  <=  DATE(date_add(NOW(), INTERVAL -8 DAY)) THEN mo.monto_mo ELSE NULL END) as total_todo
                FROM
                		certificacion_mo mo, web_unificada wu
                WHERE	mo.ptr = wu.ptr 
                AND 	mo.estado = ".CERTIFICACION_MO_CON_ORDEN_COMPRA."
                AND		mo.fec_reg_oc  BETWEEN  DATE(date_add(NOW(), INTERVAL -90 DAY)) AND NOW() ";
            if($eecc!=''){
                $Query .= "AND wu.eecc = '".$eecc."'";
            } 
                $Query .= "  UNION ALL
                SELECT 'CERTIFICADO' as descripcion, 3 as origen,
                SUM(CASE WHEN mo.fec_reg_oc  BETWEEN  DATE(date_add(NOW(), INTERVAL -3 DAY)) AND NOW() THEN 1 ELSE NULL END) as hasta3,
                SUM(CASE WHEN mo.fec_reg_oc  BETWEEN  DATE(date_add(NOW(), INTERVAL -3 DAY)) AND NOW() THEN mo.monto_mo ELSE NULL END) as total_hasta3,
                SUM(CASE WHEN mo.fec_reg_oc  BETWEEN  DATE(date_add(NOW(), INTERVAL -7 DAY)) AND DATE(date_add(NOW(), INTERVAL -4 DAY)) THEN 1 ELSE NULL END) as hasta7,
                SUM(CASE WHEN mo.fec_reg_oc  BETWEEN  DATE(date_add(NOW(), INTERVAL -7 DAY)) AND DATE(date_add(NOW(), INTERVAL -4 DAY)) THEN mo.monto_mo ELSE NULL END) as total_hasta7,
                SUM(CASE WHEN mo.fec_reg_oc  <=  DATE(date_add(NOW(), INTERVAL -8 DAY)) THEN 1 ELSE NULL END) as todo,
                SUM(CASE WHEN mo.fec_reg_oc  <=  DATE(date_add(NOW(), INTERVAL -8 DAY)) THEN mo.monto_mo ELSE NULL END) as total_todo
                FROM
                certificacion_mo mo, planobra_po ppo
                WHERE	mo.ptr = ppo.codigo_po	
                AND 	mo.estado = ".CERTIFICACION_MO_CON_ORDEN_COMPRA."
                    AND		mo.fec_reg_oc  BETWEEN  DATE(date_add(NOW(), INTERVAL -90 DAY)) AND NOW() ";
                    if($eecc!=''){
                    $Query .= "AND ppo.id_eecc_reg = '".$iddEECCPO."'";
                }
    $Query .= "   ) as tb
                        GROUP BY tb.descripcion , tb.origen";
   
	    $result = $this->db->query($Query,array());
		log_message('error', $this->db->last_query());
	    return $result;
	}
	
	function   getDetalleCertificacionMOGI($rango_fecha, $eecc){
	    $iddEECCPO = 0;
	    if($eecc   ==  'CALATEL'){
	        $iddEECCPO = ID_EECC_EZENTIS;
	    }else if($eecc == 'DOMINIONPERU SOLUCIONES Y SERVICIOS S.A.C.'){
	        $iddEECCPO = ID_EECC_DOMINION;
	    }else if($eecc   ==  'LARI'){
	        $iddEECCPO =   ID_EECC_LARI;
	    }else if($eecc   ==  'COBRA'){
	        $iddEECCPO =   ID_EECC_COBRA;
	    }
	    $Query = "SELECT  (CASE WHEN mo.estado = 2 THEN 'SIN PRESUPUESTO'
            			 WHEN mo.estado = 3 THEN 'SIN CONFIGURACION'
            			 WHEN mo.estado = 4 THEN 'SIN ITEMPLAN' END ) as situacion,  mo.itemplan, p.proyectoDesc, sp.subProyectoDesc, mo.ptr, mo.monto_mo, mo.areaDesc, wu.jefatura, 
                		wu.eecc, mo.orden_compra, mo.nro_certificacion, mo.hoja_gestion, wu.categoria, mo.pep1, f.faseDesc
                FROM    web_unificada wu, certificacion_mo mo LEFT JOIN planobra po ON mo.itemplan = po.itemplan LEFT JOIN fase f ON po.idFase = f.idfase
                LEFT JOIN subproyecto sp ON po.idSubProyecto = sp.idSubProyecto LEFT JOIN proyecto p ON p.idProyecto = sp.idProyecto
                WHERE	mo.ptr = wu.ptr
                AND		mo.estado_validado = 0
                AND		mo.estado  NOT IN (".CERTIFICACION_MO_CON_PRESUPUESTO.",".CERTIFICACION_MO_CON_ORDEN_COMPRA.",".CERTIFICACION_MO_SIN_ITEMPLAN_ASOCIADO.")";
	    if($eecc!=''){
	        $Query .= " AND wu.eecc = '".$eecc."'";
	    }
	    if($rango_fecha    ==  1){
	        $Query .=  " AND mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -3 DAY)) AND NOW()";
	    }else  if($rango_fecha    ==  2){
	        $Query .=  " AND mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -7 DAY)) AND DATE(date_add(NOW(), INTERVAL -4 DAY))";
	    }else  if($rango_fecha    ==  3){
	        $Query .=  " AND mo.fecha  <=  DATE(date_add(NOW(), INTERVAL -8 DAY))";
	    }
	     
	        $Query .= "    UNION ALL
	                   SELECT  (CASE WHEN mo.estado = 2 THEN 'SIN PRESUPUESTO'
            			 WHEN mo.estado = 3 THEN 'SIN CONFIGURACION'
            			 WHEN mo.estado = 4 THEN 'SIN ITEMPLAN' END ) as situacion,  mo.itemplan, p.proyectoDesc, sp.subProyectoDesc, mo.ptr, mo.monto_mo, mo.areaDesc, c.jefatura as jefatura,
                		 ec.empresaColabDesc as eecc, mo.orden_compra, mo.nro_certificacion, mo.hoja_gestion, '' as categoria, mo.pep1, f.faseDesc
                FROM    planobra_po ppo  LEFT JOIN empresacolab ec ON ppo.id_eecc_reg = ec.idEmpresaColab, certificacion_mo mo LEFT JOIN planobra po ON mo.itemplan = po.itemplan LEFT JOIN fase f ON po.idFase = f.idfase
                LEFT JOIN subproyecto sp ON po.idSubProyecto = sp.idSubProyecto LEFT JOIN proyecto p ON p.idProyecto = sp.idProyecto
	            LEFT JOIN central c ON po.idCentral = c.idCentral
                WHERE	mo.ptr = ppo.codigo_po
                AND		mo.estado_validado = 0
                AND		mo.estado  NOT IN (".CERTIFICACION_MO_CON_PRESUPUESTO.",".CERTIFICACION_MO_CON_ORDEN_COMPRA.",".CERTIFICACION_MO_SIN_ITEMPLAN_ASOCIADO.")";
	        if($eecc!=''){
	            $Query .= " AND ppo.id_eecc_reg = '".$iddEECCPO."'";
	        }
	        if($rango_fecha    ==  1){
	            $Query .=  " AND mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -3 DAY)) AND NOW()";
	        }else  if($rango_fecha    ==  2){
	            $Query .=  " AND mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -7 DAY)) AND DATE(date_add(NOW(), INTERVAL -4 DAY))";
	        }else  if($rango_fecha    ==  3){
	            $Query .=  " AND mo.fecha  <=  DATE(date_add(NOW(), INTERVAL -8 DAY))";
	        }
	    $result = $this->db->query($Query,array());	
		#log_message('error', $this->db->last_query());
	    return $result;
	}
    
	function   getDetalleCertificacionMOGVU($rango_fecha, $eecc){
	    $iddEECCPO = 0;
	    if($eecc   ==  'CALATEL'){
	        $iddEECCPO = ID_EECC_EZENTIS;
	    }else if($eecc == 'DOMINIONPERU SOLUCIONES Y SERVICIOS S.A.C.'){
	        $iddEECCPO = ID_EECC_DOMINION;
	    }else if($eecc   ==  'LARI'){
	        $iddEECCPO =   ID_EECC_LARI;
	    }else if($eecc   ==  'COBRA'){
	        $iddEECCPO =   ID_EECC_COBRA;
	    }
	    $Query = "SELECT (CASE WHEN mo.estado_validado = 0 THEN 'SIN H.G'
                    	       WHEN mo.estado_validado = 1 THEN 'CON H.G' END ) as situacion, mo.itemplan, p.proyectoDesc, sp.subProyectoDesc, mo.ptr, mo.monto_mo, mo.areaDesc, wu.jefatura, 
                    wu.eecc, mo.orden_compra, mo.nro_certificacion, mo.hoja_gestion, wu.categoria, mo.pep1
                    FROM    certificacion_mo mo, web_unificada wu, planobra po, subproyecto sp, proyecto p
                    WHERE	mo.ptr = wu.ptr
                    AND		mo.itemplan = po.itemplan
                    AND 	po.idSubProyecto = sp.idSubProyecto
                    AND		p.idProyecto = sp.idProyecto
                    AND		mo.estado = ".CERTIFICACION_MO_CON_PRESUPUESTO." ";
	    if($eecc!=''){
	        $Query .= " AND wu.eecc = '".$eecc."'";
	    }
	    if($rango_fecha    ==  1){
	        $Query .=  " AND mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -3 DAY)) AND NOW()";
	    }else  if($rango_fecha    ==  2){
	        $Query .=  " AND mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -7 DAY)) AND DATE(date_add(NOW(), INTERVAL -4 DAY))";
	    }else  if($rango_fecha    ==  3){
	        $Query .=  " AND mo.fecha  <=  DATE(date_add(NOW(), INTERVAL -8 DAY))";
	    }
			$Query .= "    UNION ALL
	               SELECT (CASE WHEN mo.estado_validado = 0 THEN 'SIN H.G'
                    	       WHEN mo.estado_validado = 1 THEN 'CON H.G' END ) as situacion, mo.itemplan, p.proyectoDesc, sp.subProyectoDesc, mo.ptr, mo.monto_mo, mo.areaDesc, c.jefatura as jefatura,
                    ec.empresaColabdesc as eecc, mo.orden_compra, mo.nro_certificacion, mo.hoja_gestion, '' as categoria, mo.pep1
                    FROM    certificacion_mo mo, planobra_po ppo, planobra po, subproyecto sp, proyecto p, empresacolab ec, central c
                    WHERE	mo.ptr = ppo.codigo_po
                    AND		mo.itemplan = po.itemplan
    	            AND     ppo.id_eecc_reg = ec.idEmpresaColab
    	            AND     po.idcentral = c.idCentral
                    AND 	po.idSubProyecto = sp.idSubProyecto
                    AND		p.idProyecto = sp.idProyecto
					AND		(po.paquetizado_fg is null or po.paquetizado_fg = 1)
                    AND		mo.estado = ".CERTIFICACION_MO_CON_PRESUPUESTO." ";
	        if($eecc!=''){
	            $Query .= " AND ppo.id_eecc_reg = '".$iddEECCPO."'";
	        }
	        if($rango_fecha    ==  1){
	            $Query .=  " AND mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -3 DAY)) AND NOW()";
	        }else  if($rango_fecha    ==  2){
	            $Query .=  " AND mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -7 DAY)) AND DATE(date_add(NOW(), INTERVAL -4 DAY))";
	        }else  if($rango_fecha    ==  3){
	            $Query .=  " AND mo.fecha  <=  DATE(date_add(NOW(), INTERVAL -8 DAY))";
	        }
			
			$Query .= "    UNION ALL
	               SELECT (CASE WHEN mo.estado_validado = 0 THEN 'SIN H.G'
                    	       WHEN mo.estado_validado = 1 THEN 'CON H.G' END ) as situacion, mo.itemplan, p.proyectoDesc, sp.subProyectoDesc, mo.ptr, mo.monto_mo, mo.areaDesc, c.jefatura as jefatura,
                    ec.empresaColabdesc as eecc, mo.orden_compra, mo.nro_certificacion, mo.hoja_gestion, '' as categoria, mo.pep1
                    FROM    certificacion_mo mo, planobra_po ppo, planobra po, subproyecto sp, proyecto p, empresacolab ec, pqt_central c
                    WHERE	mo.ptr = ppo.codigo_po
                    AND		mo.itemplan = po.itemplan
    	            AND     ppo.id_eecc_reg = ec.idEmpresaColab
    	            AND     po.idcentralPqt = c.idCentral
                    AND 	po.idSubProyecto = sp.idSubProyecto
                    AND		p.idProyecto = sp.idProyecto
					AND		po.paquetizado_fg = 2
                    AND		mo.estado = ".CERTIFICACION_MO_CON_PRESUPUESTO." ";
	        if($eecc!=''){
	            $Query .= " AND ppo.id_eecc_reg = '".$iddEECCPO."'";
	        }
	        if($rango_fecha    ==  1){
	            $Query .=  " AND mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -3 DAY)) AND NOW()";
	        }else  if($rango_fecha    ==  2){
	            $Query .=  " AND mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -7 DAY)) AND DATE(date_add(NOW(), INTERVAL -4 DAY))";
	        }else  if($rango_fecha    ==  3){
	            $Query .=  " AND mo.fecha  <=  DATE(date_add(NOW(), INTERVAL -8 DAY))";
	        }
			
	        $result = $this->db->query($Query,array());
			log_message('error', $this->db->last_query());
	        return $result;
	}
	
	function   getDetalleCertificacionMOCertificado($rango_fecha, $eecc){
    	    $iddEECCPO = 0;
    	    if($eecc   ==  'CALATEL'){
    	        $iddEECCPO = ID_EECC_EZENTIS;
    	    }else if($eecc == 'DOMINIONPERU SOLUCIONES Y SERVICIOS S.A.C.'){
    	        $iddEECCPO = ID_EECC_DOMINION;
    	    }else if($eecc   ==  'LARI'){
    	        $iddEECCPO =   ID_EECC_LARI;
    	    }else if($eecc   ==  'COBRA'){
    	        $iddEECCPO =   ID_EECC_COBRA;
    	    }
	    $Query = "SELECT   'CON O.C' as situacion, mo.itemplan, p.proyectoDesc, sp.subProyectoDesc, mo.ptr, mo.monto_mo, mo.areaDesc, wu.jefatura, 
                            wu.eecc, mo.orden_compra, mo.nro_certificacion, mo.hoja_gestion, wu.categoria, mo.pep1
                    FROM    certificacion_mo mo, web_unificada wu, planobra po, subproyecto sp, proyecto p
                    WHERE	mo.ptr = wu.ptr
                    AND		mo.itemplan = po.itemplan
                    AND 	po.idSubProyecto = sp.idSubProyecto
                    AND		p.idProyecto = sp.idProyecto
                    AND		mo.estado = ".CERTIFICACION_MO_CON_ORDEN_COMPRA."
                    AND		mo.fec_reg_oc  BETWEEN  DATE(date_add(NOW(), INTERVAL -90 DAY)) AND NOW() ";
	    if($eecc!=''){
	        $Query .= " AND wu.eecc = '".$eecc."'";
	    }
	    if($rango_fecha    ==  1){
	        $Query .=  " AND mo.fec_reg_oc  BETWEEN  DATE(date_add(NOW(), INTERVAL -3 DAY)) AND NOW()";
	    }else  if($rango_fecha    ==  2){
	        $Query .=  " AND mo.fec_reg_oc  BETWEEN  DATE(date_add(NOW(), INTERVAL -7 DAY)) AND DATE(date_add(NOW(), INTERVAL -4 DAY))";
	    }else  if($rango_fecha    ==  3){
	        $Query .=  " AND mo.fec_reg_oc  <=  DATE(date_add(NOW(), INTERVAL -8 DAY))";
	    }
	        
	        $Query .= "    UNION ALL
	                SELECT   'CON O.C' as situacion, mo.itemplan, p.proyectoDesc, sp.subProyectoDesc, mo.ptr, mo.monto_mo, mo.areaDesc, c.jefatura as jefatura,
                            ec.empresaColabdesc as eecc, mo.orden_compra, mo.nro_certificacion, mo.hoja_gestion, '' as categoria, mo.pep1
                    FROM    certificacion_mo mo, planobra_po ppo, planobra po, subproyecto sp, proyecto p, empresacolab ec, central c
                    WHERE	mo.ptr = ppo.codigo_po
                    AND		mo.itemplan = po.itemplan
	                AND     ppo.id_eecc_reg = ec.idEmpresaColab
    	            AND     po.idCentral = c.idCentral
                    AND 	po.idSubProyecto = sp.idSubProyecto
                    AND		p.idProyecto = sp.idProyecto
                    AND		mo.estado = ".CERTIFICACION_MO_CON_ORDEN_COMPRA."
					AND		(po.paquetizado_fg is null or po.paquetizado_fg = 1)
                    AND		mo.fec_reg_oc  BETWEEN  DATE(date_add(NOW(), INTERVAL -90 DAY)) AND NOW() ";
	        if($eecc!=''){
	            $Query .= " AND ppo.id_eecc_reg = '".$iddEECCPO."'";
	        }
	        if($rango_fecha    ==  1){
	            $Query .=  " AND mo.fec_reg_oc  BETWEEN  DATE(date_add(NOW(), INTERVAL -3 DAY)) AND NOW()";
	        }else  if($rango_fecha    ==  2){
	            $Query .=  " AND mo.fec_reg_oc  BETWEEN  DATE(date_add(NOW(), INTERVAL -7 DAY)) AND DATE(date_add(NOW(), INTERVAL -4 DAY))";
	        }else  if($rango_fecha    ==  3){
	            $Query .=  " AND mo.fec_reg_oc  <=  DATE(date_add(NOW(), INTERVAL -8 DAY))";
	        }
			
			$Query .= "    UNION ALL
	                SELECT   'CON O.C' as situacion, mo.itemplan, p.proyectoDesc, sp.subProyectoDesc, mo.ptr, mo.monto_mo, mo.areaDesc, c.jefatura as jefatura,
                            ec.empresaColabdesc as eecc, mo.orden_compra, mo.nro_certificacion, mo.hoja_gestion, '' as categoria, mo.pep1
                    FROM    certificacion_mo mo, planobra_po ppo, planobra po, subproyecto sp, proyecto p, empresacolab ec, pqt_central c
                    WHERE	mo.ptr = ppo.codigo_po
                    AND		mo.itemplan = po.itemplan
	                AND     ppo.id_eecc_reg = ec.idEmpresaColab
    	            AND     po.idCentralPqt = c.idCentral
                    AND 	po.idSubProyecto = sp.idSubProyecto
                    AND		p.idProyecto = sp.idProyecto
                    AND		mo.estado = ".CERTIFICACION_MO_CON_ORDEN_COMPRA."
					AND		po.paquetizado_fg = 2
                    AND		mo.fec_reg_oc  BETWEEN  DATE(date_add(NOW(), INTERVAL -90 DAY)) AND NOW() ";
	        if($eecc!=''){
	            $Query .= " AND ppo.id_eecc_reg = '".$iddEECCPO."'";
	        }
	        if($rango_fecha    ==  1){
	            $Query .=  " AND mo.fec_reg_oc  BETWEEN  DATE(date_add(NOW(), INTERVAL -3 DAY)) AND NOW()";
	        }else  if($rango_fecha    ==  2){
	            $Query .=  " AND mo.fec_reg_oc  BETWEEN  DATE(date_add(NOW(), INTERVAL -7 DAY)) AND DATE(date_add(NOW(), INTERVAL -4 DAY))";
	        }else  if($rango_fecha    ==  3){
	            $Query .=  " AND mo.fec_reg_oc  <=  DATE(date_add(NOW(), INTERVAL -8 DAY))";
	        }
			
	        $result = $this->db->query($Query,array());
			//log_message('error', $this->db->last_query());
	        return $result;
	}
}