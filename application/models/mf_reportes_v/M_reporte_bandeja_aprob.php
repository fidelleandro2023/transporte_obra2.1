<?php
class M_reporte_bandeja_aprob extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
    function   getSeguimientoBandejaAprob($proyecto, $subProyecto, $fase, $area){            
        
        $query = "SELECT desc_area, subproy, 
	SUM(CASE 
	 WHEN (estado_asig_grafo = 1) THEN 1
	 ELSE 0
   END) AS con_grafo,
	SUM(CASE 
	 WHEN estacion_desc != 'DISEÑO'  THEN 
		 CASE WHEN      
		  (SELECT COUNT(1) as count FROM itemplan_expediente where estado = 'ACTIVO' AND estado_final = 'FINALIZADO' and itemplan = web_unificada_det.itemPlan) > 0 AND estado_asig_grafo = 0 AND grafo is null AND (substring(estado,1,3) = '003' OR substring(estado,1,2) = '01') THEN 1
		 ELSE 0 END
     ELSE
		 CASE WHEN      
		 estado_asig_grafo = 0 AND grafo is null AND (substring(estado,1,3) = '003' OR substring(estado,1,2) = '01') THEN 1
		 ELSE 0 END
     END) AS sin_grafo,
	SUM(CASE 
	 WHEN (grafo = 'SIN PRESUPUESTO') THEN 1
	 ELSE 0
   END) AS sin_presupuesto,
   (SELECT idProyecto FROM subproyecto WHERE subProyectoDesc = subproy) as idProyecto
   FROM web_unificada_det
WHERE (estado_asig_grafo = '0' or estado_asig_grafo = '1')
AND subproy IS NOT NULL";
            if($area!=''){
                $query .= " AND desc_area = '".(($area=='BLANCO') ? '' : $area)."' ";
            }
             if($subProyecto!=''){
            $subPro='';
            $subProyecto = explode(",",$subProyecto);
            foreach ($subProyecto as $row){
                if($fase != ''){
                    $subPro .= "'".$fase.' '.$row."',";
                }else{
                    $subPro .= "'".$row."',";
                }
                
            }
            $subPro = substr($subPro,0,-1);
            $query .= " AND subproy IN (".$subPro.")";
        }
       $query .= " GROUP BY desc_area, subproy ";


        $query .=" HAVING con_grafo >0 OR sin_grafo > 0 OR sin_presupuesto > 0";

        if($proyecto!=''){
            $query .= " AND  idProyecto = ".$proyecto;
        }
        $query .= "  ORDER BY desc_area DESC, subproy ";
	    $result = $this->db->query($query,array());	   
	    return $result;
	}

    function getPtrConGrafo($areadesc,$subproyecto){
        $query = "SELECT wd.ptr, itemPlan, indicador, subproy, pep, wd.grafo, valor_material, valor_m_o,
                (SELECT idProyecto FROM subproyecto WHERE subProyectoDesc = subproy) as idProyecto
                FROM web_unificada_det wd, web_unificada wu where wd.ptr = wu.ptr
                AND estado_asig_grafo = '1'
               AND NOT wd.subproy IS NULL 
                AND wd.desc_area = '".$areadesc."'         
		        AND subproy = '".$subproyecto."'";
        $result = $this->db->query($query,array());
        return $result;

    }
    function getPtrSinGrafo($area, $subproyecto){
        $query = "SELECT wd.ptr, wd.itemPlan, indicador, pep, wd.grafo, subproy, valor_material, valor_m_o,
                (SELECT idProyecto FROM subproyecto WHERE subProyectoDesc = subproy) as idProyecto
                FROM web_unificada_det wd, web_unificada wu where wd.ptr = wu.ptr
                 AND CASE 
                 WHEN  estacion_desc != 'DISEÑO'  THEN 
                 (SELECT COUNT(1) as count FROM itemplan_expediente where estado = 'ACTIVO' AND estado_final = 'FINALIZADO' and itemplan = wd.itemPlan) > 0 
                 AND estado_asig_grafo = 0 AND wd.grafo is null AND (substring(estado,1,3) = '003' OR substring(estado,1,2) = '01') ELSE 
                  estado_asig_grafo = 0 AND wd.grafo is null AND (substring(estado,1,3) = '003' OR substring(estado,1,2) = '01') END
                  AND wd.desc_area = '".$area."'
                AND subproy = '".$subproyecto."'";
        $result = $this->db->query($query,array());
        return $result;

    }
    function getPtrSinPresupuesto($area, $subproyecto){
        $query = "SELECT wd.ptr, wd.itemPlan, indicador, pep, wd.grafo, subproy, valor_material, valor_m_o,
                (SELECT idProyecto FROM subproyecto WHERE subProyectoDesc = subproy) as idProyecto
                FROM web_unificada_det wd , web_unificada wu where wd.ptr = wu.ptr
                AND wd.grafo = 'SIN PRESUPUESTO'
                AND subproy IS NOT NULL
                AND wd.desc_area = '".$area."'
                AND subproy = '".$subproyecto."'";
        $result = $this->db->query($query,array());
        return $result;




    }
}