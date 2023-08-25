<?php
class M_reporte_ba_vr extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
	function   getDaysInterval($fechaInicio, $fechaFin){
	    $Query = " select d.date as dia from 
                    (select adddate('1970-01-01',t4.i*10000 + t3.i*1000 + t2.i*100 + t1.i*10 + t0.i) date from
                     (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t0,
                     (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t1,
                     (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t2,
                     (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t3,
                     (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t4) d
                    where d.date between '".$fechaInicio."' and '".$fechaFin."'
                    group by d.date
                    order by d.date ";
	    
	    $result = $this->db->query($Query,array());
	    return $result;
	}
    
	function   getUSuariosAprobByInterval($fechaInicio, $fechaFin){
	    $Query = "	SELECT distinct usua_asig_grafo
	FROM web_unificada_det WHERE estado_asig_grafo = 2 and usua_asig_grafo != ''
	AND STR_TO_DATE(fecha_asig_grafo,'%d/%m/%Y') >= '".$fechaInicio."' AND STR_TO_DATE(fecha_asig_grafo,'%d/%m/%Y') <= '".$fechaFin."' ";
	
	    $result = $this->db->query($Query,array());
	    return $result;
	}
	
	function   getCantAprobByUsuarioAndInterval($fechaInicio, $fechaFin, $usuario){
	    $Query = " select d.date, SUM(CASE WHEN wu.usua_asig_grafo = '".$usuario."' THEN 1 ELSE NULL END) as total_aprob from
                	(select adddate('1970-01-01',t4.i*10000 + t3.i*1000 + t2.i*100 + t1.i*10 + t0.i) date from
                	(select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t0,
                	(select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t1,
                	(select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t2,
                	(select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t3,
                	(select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t4) d
                	left join web_unificada_det wu on d.date = STR_TO_DATE(wu.fecha_asig_grafo,'%d/%m/%Y')
                	where d.date between '".$fechaInicio."' and '".$fechaFin."'
                	group by d.date
                	order by d.date ";
	     
	    $result = $this->db->query($Query,array());
	    return $result;
	}
	
	function   getUSuariosAprobByIntervalVR($fechaInicio, $fechaFin){
	    $Query = "	SELECT distinct vr.idUsuario, u.usuario
                	FROM log_solicitud_vr vr, usuario u
                	WHERE vr.idUsuario = u.id_usuario
                	AND DATE(vr.fecha_registro) >= '".$fechaInicio."' AND DATE(vr.fecha_registro) <= '".$fechaFin."' 
            	    AND vr.flg_estado = 1 ";
	
	    $result = $this->db->query($Query,array());
	    return $result;
	}
	
	function   getCantAprobByUsuarioAndIntervalVR($fechaInicio, $fechaFin, $usuario){
	    $Query = " select d.date, SUM(CASE WHEN vr.idUsuario = ".$usuario." THEN 1 ELSE NULL END) as total_aprob from
                	(select adddate('1970-01-01',t4.i*10000 + t3.i*1000 + t2.i*100 + t1.i*10 + t0.i) date from
                	(select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t0,
                	(select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t1,
                	(select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t2,
                	(select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t3,
                	(select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t4) d
                	left join log_solicitud_vr vr on d.date = DATE(vr.fecha_registro) AND flg_estado = 1
                	where d.date between '".$fechaInicio."' and '".$fechaFin."'
                	group by d.date
                	order by d.date  ";
	
	    $result = $this->db->query($Query,array());
	    return $result;
	}
	
	
}