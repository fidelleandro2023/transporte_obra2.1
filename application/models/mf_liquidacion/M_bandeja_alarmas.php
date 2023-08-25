<?php
class M_bandeja_alarmas extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
	function   getBandejaAlarmasMO(){
	    $Query = "SELECT (CASE WHEN mo.estado = 2 THEN 'SIN PRESUPUESTO'
            			       WHEN mo.estado = 3 THEN 'SIN CONFIGURACION'
            			 	   WHEN mo.estado = 4 THEN 'SIN ITEMPLAN' END ) as situacion, 
                          mo.pep1, 
                          mo.estado, 
                          SUM(CASE WHEN mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -3 DAY)) AND NOW() THEN 1 ELSE NULL END) as hasta3,
            			  SUM(CASE WHEN mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -3 DAY)) AND NOW() THEN mo.monto_mo ELSE NULL END) as total_hasta3,
            			  SUM(CASE WHEN mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -7 DAY)) AND DATE(date_add(NOW(), INTERVAL -4 DAY)) THEN 1 ELSE NULL END) as hasta7,
            			  SUM(CASE WHEN mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -7 DAY)) AND DATE(date_add(NOW(), INTERVAL -4 DAY)) THEN mo.monto_mo ELSE NULL END) as total_hasta7,
            			  SUM(CASE WHEN mo.fecha  <=  DATE(date_add(NOW(), INTERVAL -8 DAY)) THEN 1 ELSE NULL END) as todo,
            			  SUM(CASE WHEN mo.fecha  <=  DATE(date_add(NOW(), INTERVAL -8 DAY)) THEN mo.monto_mo ELSE NULL END) as total_todo,
                          mo.idSubProyecto, sp.subProyectoDesc, p.proyectoDesc
                    FROM
                		 certificacion_mo mo LEFT JOIN subproyecto sp ON mo.idSubProyecto = sp.idSubProyecto 
						 				 	 LEFT JOIN proyecto p     ON sp.idProyecto    = p.idProyecto
                   WHERE mo.estado NOT IN (".CERTIFICACION_MO_CON_PRESUPUESTO.",".CERTIFICACION_MO_CON_ORDEN_COMPRA.",".CERTIFICACION_MO_SIN_ITEMPLAN_ASOCIADO.")
                   GROUP BY mo.pep1, mo.estado, mo.idSubProyecto
                   ORDER BY mo.estado, p.proyectoDesc, sp.subProyectoDesc";
	    $result = $this->db->query($Query,array());
	    return $result;
	}

	function   getPtrsCertificacionMO($pep, $estado, $subpro,$rango_fecha){
	    $Query = "SELECT mo.itemplan, 
						 wu.eecc as empresaColabDesc, 
						 mo.ptr, 
						 mo.monto_mo, 
						 sp.subProyectoDesc, 
						 mo.areaDesc, 
						 DATE_FORMAT(mo.fecha,'%d/%m/%Y') as fecha, 
						 mo.pep1
                	FROM certificacion_mo mo, web_unificada wu, planobra po, subproyecto sp
                   WHERE mo.itemplan = po.itemplan
                	 AND mo.ptr      = wu.ptr
                	 AND po.idSubProyecto = sp.idSubProyecto                	   
					 AND mo.estado   = ?
					 AND CASE WHEN '".$estado."' = 2 THEN mo.pep1 = '".$pep."'
							  ELSE '".$estado."' = '".$estado."' END
					 AND CASE WHEN '".$subpro."' <> '' THEN mo.idSubProyecto = '".$subpro."'
							  ELSE '".$subpro."' = '".$subpro."' END
					 AND CASE WHEN '".$rango_fecha."' = 1 THEN	mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -3 DAY)) AND NOW() 
							  WHEN '".$rango_fecha."' = 2 THEN mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -7 DAY)) AND DATE(date_add(NOW(), INTERVAL -4 DAY))
							  WHEN '".$rango_fecha."' = 3 THEN mo.fecha  <=  DATE(date_add(NOW(), INTERVAL -8 DAY)) 
							  ELSE '".$rango_fecha."' = '".$rango_fecha."' END
				UNION ALL
				 (SELECT mo.itemplan, 
						 ec.empresaColabDesc, 
						 mo.ptr, 
						 mo.monto_mo, 
					  	 sp.subProyectoDesc, 
						 mo.areaDesc, 
						 DATE_FORMAT(mo.fecha,'%d/%m/%Y') as fecha, 
						 mo.pep1
					FROM certificacion_mo mo, planobra_po ppo, planobra po, subproyecto sp, empresacolab ec
				   WHERE mo.itemplan = po.itemplan
					 AND ppo.itemplan = po.itemplan
					 AND ppo.id_eecc_reg = ec.idEmpresaColab
					 AND mo.ptr      = ppo.codigo_po
					 AND po.idSubProyecto = sp.idSubProyecto                	   
					 AND mo.estado   = ?
					 AND CASE WHEN '".$estado."' = 2 THEN mo.pep1 = '".$pep."'
							  ELSE '".$estado."' = '".$estado."' END
					 AND CASE WHEN '".$subpro."' <> '' THEN mo.idSubProyecto = '".$subpro."'
							  ELSE '".$subpro."' = '".$subpro."' END
					 AND CASE WHEN '".$rango_fecha."' = 1 THEN	mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -3 DAY)) AND NOW() 
							  WHEN '".$rango_fecha."' = 2 THEN mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -7 DAY)) AND DATE(date_add(NOW(), INTERVAL -4 DAY))
							  WHEN '".$rango_fecha."' = 3 THEN mo.fecha  <=  DATE(date_add(NOW(), INTERVAL -8 DAY)) 
							  ELSE '".$rango_fecha."' = '".$rango_fecha."' END
					 ORDER BY mo.fecha)";
	    // if($estado == 2){
	    //     $Query .= " AND mo.pep1 = '".$pep."'";
	    // }
	    // if($subpro != ''){
	    //     $Query .= " AND mo.idSubProyecto = '".$subpro."'";
	    // }
	    // if($rango_fecha    ==  1){
	    //     $Query .=  " AND mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -3 DAY)) AND NOW()";
	    // }else  if($rango_fecha    ==  2){
	    //     $Query .=  " AND mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -7 DAY)) AND DATE(date_add(NOW(), INTERVAL -4 DAY))";
	    // }else  if($rango_fecha    ==  3){
	    //     $Query .=  " AND mo.fecha  <=  DATE(date_add(NOW(), INTERVAL -8 DAY))";
	    // }
	    
	    // $Query .= " ORDER BY mo.fecha";    	
	   	
           
		$result = $this->db->query($Query,array($estado, $estado));
	    return $result;
	}	
	
	function   getPtrsNoAsociadas($rango_fecha){
	    $Query = " SELECT mo.itemplan, 
						  wu.eecc as empresaColabDesc, 
						  mo.ptr, 
						  mo.monto_mo, 
						  '' as subProyectoDesc, 
						  mo.areaDesc, 
						  DATE_FORMAT(mo.fecha,'%d/%m/%Y') as fecha, 
						  mo.pep1
                	 FROM certificacion_mo mo, web_unificada wu
                	WHERE mo.ptr = wu.ptr
					  AND mo.estado = 4
					  AND CASE WHEN '".$rango_fecha."' = 1 THEN mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -3 DAY)) AND NOW()
							   WHEN '".$rango_fecha."' = 2 THEN mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -7 DAY)) AND DATE(date_add(NOW(), INTERVAL -4 DAY))
							   WHEN '".$rango_fecha."' = 3 THEN mo.fecha  <=  DATE(date_add(NOW(), INTERVAL -8 DAY))
							   ELSE '".$rango_fecha."' = '".$rango_fecha."' END
					UNION ALL 
					(SELECT mo.itemplan, 
							ec.empresaColabDesc, 
							mo.ptr, 
							mo.monto_mo, 
							'' as subProyectoDesc, 
							mo.areaDesc, 
							DATE_FORMAT(mo.fecha,'%d/%m/%Y') as fecha, 
							mo.pep1
					  FROM certificacion_mo mo, planobra_po ppo, empresacolab ec
					 WHERE mo.ptr = ppo.codigo_po
					   AND mo.itemplan = ppo.itemplan
					   AND ppo.id_eecc_reg = ec.idEmpresaColab
					   AND mo.estado = 4
					   AND CASE WHEN '".$rango_fecha."' = 1 THEN mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -3 DAY)) AND NOW()
								WHEN '".$rango_fecha."' = 2 THEN mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -7 DAY)) AND DATE(date_add(NOW(), INTERVAL -4 DAY))
								WHEN '".$rango_fecha."' = 3 THEN mo.fecha  <=  DATE(date_add(NOW(), INTERVAL -8 DAY))
								ELSE '".$rango_fecha."' = '".$rango_fecha."' END)
					ORDER BY mo.fecha";
                	 
	    // if($rango_fecha    ==  1){
	    //     $Query .=  " AND mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -3 DAY)) AND NOW()";
	    // }else  if($rango_fecha    ==  2){
	    //     $Query .=  " AND mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -7 DAY)) AND DATE(date_add(NOW(), INTERVAL -4 DAY))";
	    // }else  if($rango_fecha    ==  3){
	    //     $Query .=  " AND mo.fecha  <=  DATE(date_add(NOW(), INTERVAL -8 DAY))";
	    // }  	     
	        
	        // $Query .= 'ORDER BY mo.fecha';
	    $result = $this->db->query($Query,array());
	    log_message('error', $this->db->last_query());
	    return $result;
	}
	
	
	function   getDetalleCertificacionAlarmMO(){
	    $Query = "SELECT (CASE WHEN mo.estado = 2 THEN 'SIN PRESUPUESTO' 
							   WHEN mo.estado = 3 THEN 'SIN CONFIGURACION'
							   WHEN mo.estado = 4 THEN 'SIN ITEMPLAN' end) AS situacion, 
						 p.proyectoDesc, 
						 sp.subProyectoDesc, 
						 wu.eecc as empresaColabDesc, 
						 mo.areaDesc, 
						 mo.pep1, 
						 mo.itemplan, 
						 mo.ptr, 
						 mo.monto_mo, 
						 DATE_FORMAT(mo.fecha,'%d/%m/%Y') as fecha
					FROM web_unificada wu , 
					     certificacion_mo mo
			   LEFT JOIN planobra po ON  mo.itemplan = po.itemplan
			   LEFT JOIN subproyecto sp ON po.idSubProyecto = sp.idSubProyecto
			   LEFT JOIN proyecto p ON sp.idProyecto = p.idProyecto
				   WHERE mo.ptr = wu.ptr
					 AND mo.estado  NOT IN (".CERTIFICACION_MO_CON_PRESUPUESTO.",".CERTIFICACION_MO_CON_ORDEN_COMPRA.",".CERTIFICACION_MO_SIN_ITEMPLAN_ASOCIADO.")  
					UNION ALL
					(
						SELECT (CASE WHEN mo.estado = 2 THEN 'SIN PRESUPUESTO' 
							   WHEN mo.estado = 3 THEN 'SIN CONFIGURACION'
							   WHEN mo.estado = 4 THEN 'SIN ITEMPLAN' end) AS situacion, 
							   p.proyectoDesc, 
							   sp.subProyectoDesc, 
							   ec.empresaColabDesc, 
							   mo.areaDesc, 
							   mo.pep1, 
							   mo.itemplan, 
							   mo.ptr, 
							   mo.monto_mo, 
							   DATE_FORMAT(mo.fecha,'%d/%m/%Y') as fecha
						  FROM planobra_po ppo, 
						       empresacolab ec,
							   certificacion_mo mo
					 LEFT JOIN planobra po ON  mo.itemplan = po.itemplan
					 LEFT JOIN subproyecto sp ON po.idSubProyecto = sp.idSubProyecto
					 LEFT JOIN proyecto p ON sp.idProyecto = p.idProyecto
						 WHERE mo.ptr      = ppo.codigo_po
						   AND ppo.id_eecc_reg = ec.idEmpresaColab
						   AND mo.itemplan = ppo.itemplan
						   AND mo.estado  NOT IN (".CERTIFICACION_MO_CON_PRESUPUESTO.",".CERTIFICACION_MO_CON_ORDEN_COMPRA.",".CERTIFICACION_MO_SIN_ITEMPLAN_ASOCIADO.")  
					)
					ORDER BY  proyectoDesc, subProyectoDesc";
	    $result = $this->db->query($Query,array());
	    return $result;
	}
	
	
}