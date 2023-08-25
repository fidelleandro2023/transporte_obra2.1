<?php
class M_reporte_bandeja_aprob_horas extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
  
	function getReporteBandejaProbHoras(){
	    $Query = "SELECT 
                        (CASE WHEN ppo.grafo IN ('SIN PRESUPUESTO','SIN CONFIGURACION','NO HAY GRAFO') THEN (CASE WHEN po.has_paralizado = 1 THEN CONCAT('PARALIZADO',' ',ppo.grafo) ELSE ppo.grafo END) 
                        ELSE (CASE WHEN po.has_paralizado = 1 THEN 'PARALIZADO PDT APROBAR' ELSE 'PDT APROBAR' END) END) as estado, 
                        SUM((CASE WHEN TIMESTAMPDIFF(HOUR, lppo.fecha_registro, NOW()) >= 0 && TIMESTAMPDIFF(HOUR, lppo.fecha_registro, NOW()) <= 12 THEN 1 ELSE 0 END)) as d_0_12,
                        SUM((CASE WHEN TIMESTAMPDIFF(HOUR, lppo.fecha_registro, NOW()) >= 13 && TIMESTAMPDIFF(HOUR, lppo.fecha_registro, NOW()) <= 24 THEN 1 ELSE 0 END)) as d_13_24,
                        SUM((CASE WHEN TIMESTAMPDIFF(HOUR, lppo.fecha_registro, NOW()) >= 25 && TIMESTAMPDIFF(HOUR, lppo.fecha_registro, NOW()) <= 48 THEN 1 ELSE 0 END)) as d_25_48,
                        SUM((CASE WHEN TIMESTAMPDIFF(HOUR, lppo.fecha_registro, NOW()) >= 49 THEN 1 ELSE 0 END)) as d_mayor_48,
                        count(1) as total
                    FROM      planobra_po ppo, log_planobra_po lppo, planobra po 
            		WHERE     ppo.codigo_po = lppo.codigo_po
            		AND	      lppo.idPoestado = ppo.estado_po
	                AND       ppo.itemplan = po.itemplan 
            		AND	      ppo.flg_tipo_area = 1 
            		AND       ppo.estado_po = 2
                    GROUP BY  estado";
	    $result = $this->db->query($Query,array());
	    //log_message('error', $this->db->last_query());
	    return $result->result();
	}
	
	function getDataToPieReportBA($estado, $rango){
	    $Query = "SELECT tb.proyectoDesc, count(1) as total
                    FROM (select p.proyectoDesc, ppo.codigo_po,
                    (CASE WHEN ppo.grafo IN ('SIN PRESUPUESTO','SIN CONFIGURACION','NO HAY GRAFO') THEN (CASE WHEN po.has_paralizado = 1 THEN CONCAT('PARALIZADO',' ',ppo.grafo) ELSE ppo.grafo END) 
                    ELSE (CASE WHEN po.has_paralizado = 1 THEN 'PARALIZADO PDT APROBAR' ELSE 'PDT APROBAR' END) END) as estado
                    FROM planobra_po ppo, log_planobra_po lppo, planobra po, subproyecto sp, proyecto p
                    		WHERE ppo.codigo_po = lppo.codigo_po
                    		AND	lppo.idPoestado = ppo.estado_po 
                            AND ppo.itemplan = po.itemplan
                            AND po.idSubProyecto = sp.idSubProyecto
                            AND sp.idProyecto = p.idProyecto
                    		AND	ppo.flg_tipo_area = 1 
                    		AND ppo.estado_po = 2";
	    if($rango  ==  1){//0 a 12
	        $Query .=  " AND TIMESTAMPDIFF(HOUR, lppo.fecha_registro, NOW()) >= 0 && TIMESTAMPDIFF(HOUR, lppo.fecha_registro, NOW()) <= 12";
	    }else if($rango  ==  2){//13 a 24
	        $Query .=  "AND TIMESTAMPDIFF(HOUR, lppo.fecha_registro, NOW()) >= 13 && TIMESTAMPDIFF(HOUR, lppo.fecha_registro, NOW()) <= 24";
	    }else if($rango  ==  3){//25 a 48
	        $Query .=  "AND TIMESTAMPDIFF(HOUR, lppo.fecha_registro, NOW()) >= 25 && TIMESTAMPDIFF(HOUR, lppo.fecha_registro, NOW()) <= 48";
	    }else if($rango  ==  4){//48 a mas
	        $Query .= " AND TIMESTAMPDIFF(HOUR, lppo.fecha_registro, NOW()) >= 49";
	    }                            
        $Query .= " having estado = ?) as tb  GROUP BY tb.proyectoDesc";
	    $result = $this->db->query($Query,array($estado));
	    //log_message('error', $this->db->last_query());
	    return $result->result();
	}
	

	function getDetallePieByProyecto($estado, $rango, $proyectoDesc){
	    $Query = "select p.proyectoDesc, sp.subProyectoDesc, po.indicador, ppo.itemplan, ppo.codigo_po, lppo.fecha_registro, TIMESTAMPDIFF(HOUR, lppo.fecha_registro, NOW()) as dif_horas, po.fecha_paralizado,
                    (CASE WHEN ppo.grafo IN ('SIN PRESUPUESTO','SIN CONFIGURACION','NO HAY GRAFO') THEN (CASE WHEN po.has_paralizado = 1 THEN CONCAT('PARALIZADO',' ',ppo.grafo) ELSE ppo.grafo END) 
            ELSE (CASE WHEN po.has_paralizado = 1 THEN 'PARALIZADO PDT APROBAR' ELSE 'PDT APROBAR' END) END) as estado
                    FROM planobra_po ppo, log_planobra_po lppo, planobra po, subproyecto sp, proyecto p
                    		WHERE ppo.codigo_po = lppo.codigo_po
                    		AND	lppo.idPoestado = ppo.estado_po
                            AND ppo.itemplan = po.itemplan
                            AND po.idSubProyecto = sp.idSubProyecto
                            AND sp.idProyecto = p.idProyecto
                    		AND	ppo.flg_tipo_area = 1
                    		AND ppo.estado_po = 2
	                        AND p.proyectoDesc = ?";
	    if($rango  ==  1){//0 a 12
	        $Query .=  " AND TIMESTAMPDIFF(HOUR, lppo.fecha_registro, NOW()) >= 0 && TIMESTAMPDIFF(HOUR, lppo.fecha_registro, NOW()) <= 12";
	    }else if($rango  ==  2){//13 a 24
	        $Query .=  "AND TIMESTAMPDIFF(HOUR, lppo.fecha_registro, NOW()) >= 13 && TIMESTAMPDIFF(HOUR, lppo.fecha_registro, NOW()) <= 24";
	    }else if($rango  ==  3){//25 a 48
	        $Query .=  "AND TIMESTAMPDIFF(HOUR, lppo.fecha_registro, NOW()) >= 25 && TIMESTAMPDIFF(HOUR, lppo.fecha_registro, NOW()) <= 48";
	    }else if($rango  ==  4){//48 a mas
	        $Query .= " AND TIMESTAMPDIFF(HOUR, lppo.fecha_registro, NOW()) >= 49";
	    }
	    $Query .= " having estado = ?";
	    $result = $this->db->query($Query,array($proyectoDesc, $estado));
	    //log_message('error', $this->db->last_query());
	    return $result->result();
	}
	
	/*
	
	public function getInfoNodoSiomByCodigo($codigo_empl)
	{
	    $sql = "SELECT * FROM siom_nodo where empl_nemonico = ? LIMIT 1";
	    $result = $this->db->query($sql,array($codigo_empl));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
*/
	
	
	
}