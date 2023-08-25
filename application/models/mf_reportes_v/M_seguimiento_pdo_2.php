<?php
class M_seguimiento_pdo_2 extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
    function   getSeguimientoItemplan($proyecto, $subProyecto, $region, $fase){            
        
        $query = "SELECT c.jefatura,p.proyectoDesc, sp.subProyectoDesc, 
        			count(1)as total, SUM(CASE 
                     WHEN (po.idEstadoPlan = 4 or po.idEstadoPlan = 5) THEN 1
                     ELSE 0
                   END) AS terminados, SUM(CASE 
                     WHEN (substring(wuf.mat_coax_est,1,2) = '01' or substring(wuf.mat_coax_est,1,3) = '001'
        				or substring(wuf.mat_coax_est,1,3) = '003' or substring(wuf.mat_coax_est,1,3) = '002'
                        or substring(wuf.mat_coax_est,1,3) = '004' or substring(wuf.mat_coax_est,1,3) = '005')
        				AND (po.idEstadoPlan = 1 or po.idEstadoPlan = 2  or po.idEstadoPlan = 3  ) THEN 1
                     ELSE 0
                   END) AS pendiente_mat_coax
        			,SUM(CASE 
                     WHEN (substring(wuf.mat_coax_est,1,2) = '02' or substring(wuf.mat_coax_est,1,2) = '05'
        				or substring(wuf.mat_coax_est,1,2) = '06' or substring(wuf.mat_coax_est,1,2) = '08')
        				AND (po.idEstadoPlan = 1 or po.idEstadoPlan = 2  or po.idEstadoPlan = 3  ) THEN 1
                     ELSE 0
                   END) AS oper_mat_coax,
                   SUM(CASE 
                     WHEN (substring(wuf.mat_fo_est,1,2) = '01' or substring(wuf.mat_fo_est,1,3) = '001'
        				or substring(wuf.mat_fo_est,1,3) = '003' or substring(wuf.mat_fo_est,1,3) = '002'
                        or substring(wuf.mat_fo_est,1,3) = '004' or substring(wuf.mat_fo_est,1,3) = '005')
        				AND (po.idEstadoPlan = 1 or po.idEstadoPlan = 2  or po.idEstadoPlan = 3  ) THEN 1
                     ELSE 0
                   END) AS pendiente_mat_fo
        			,SUM(CASE 
                     WHEN (substring(wuf.mat_fo_est,1,2) = '02' or substring(wuf.mat_fo_est,1,2) = '05'
        				or substring(wuf.mat_fo_est,1,2) = '06' or substring(wuf.mat_fo_est,1,2) = '08')
        				AND (po.idEstadoPlan = 1 or po.idEstadoPlan = 2  or po.idEstadoPlan = 3  ) THEN 1
                     ELSE 0
                   END) AS oper_mat_fo
                    FROM planobra po, web_unificada_fa wuf, central c, subproyecto sp, proyecto p 
            WHERE po.itemplan = wuf.itemplan
            AND po.idSubProyecto = sp.idSubProyecto
            AND sp.idProyecto = p.idProyecto
            AND po.idCentral = c.idCentral";
        if($proyecto!=''){
            $query .= " AND p.idProyecto = ".$proyecto;
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
            $query .= " AND sp.subProyectoDesc IN (".$subPro.")";
        }
        if($region!=''){
           $query .= " and c.region = '".$region."'"; 
        }
          $query .= "  group  by c.jefatura, po.idSubProyecto
            ORDER BY c.jefatura, p.proyectoDesc;";
	    $result = $this->db->query($query,array());	   
	    return $result;
	}	
    
	public function getPendientesNoTerminadosMatFo($proyecto, $subProyecto, $jefatura){
	    
	    $query = " SELECT po.itemplan, wuf.mat_fo_ptr, wuf.mat_fo_est, (select grafo from web_unificada_det where ptr = wuf.mat_fo_ptr) as grafo_wu  FROM planobra po, web_unificada_fa wuf, central c, subproyecto sp, proyecto p 
                	WHERE po.itemplan = wuf.itemplan
                	AND po.idSubProyecto = sp.idSubProyecto
                	AND sp.idProyecto = p.idProyecto
                	AND po.idCentral = c.idCentral	
                	AND 
                	(substring(wuf.mat_fo_est,1,2) = '01' or substring(wuf.mat_fo_est,1,3) = '001'
                				or substring(wuf.mat_fo_est,1,3) = '003' or substring(wuf.mat_fo_est,1,3) = '002'
                				or substring(wuf.mat_fo_est,1,3) = '004' or substring(wuf.mat_fo_est,1,3) = '005')
                				AND (po.idEstadoPlan = 1 or po.idEstadoPlan = 2  or po.idEstadoPlan = 3  )                        
                	AND p.proyectoDesc = ?             
                	AND sp.subproyectoDesc = ?           
                	AND c.jefatura = ?;";
	    
	    $result = $this->db->query($query,array($proyecto, $subProyecto, $jefatura));
	    return $result;
	}
	
	public function getPendientesNoTerminadosMatCoax($proyecto, $subProyecto, $jefatura){
	     
	    $query = " SELECT po.itemplan, wuf.mat_coax_ptr, wuf.mat_coax_est, (select grafo from web_unificada_det where ptr = wuf.mat_coax_est) as grafo_wu   FROM planobra po, web_unificada_fa wuf, central c, subproyecto sp, proyecto p 
                	WHERE po.itemplan = wuf.itemplan
                	AND po.idSubProyecto = sp.idSubProyecto
                	AND sp.idProyecto = p.idProyecto
                	AND po.idCentral = c.idCentral	
                	AND 
                	(substring(wuf.mat_coax_est,1,2) = '01' or substring(wuf.mat_coax_est,1,3) = '001'
                				or substring(wuf.mat_coax_est,1,3) = '003' or substring(wuf.mat_coax_est,1,3) = '002'
                				or substring(wuf.mat_coax_est,1,3) = '004' or substring(wuf.mat_coax_est,1,3) = '005')
                				AND (po.idEstadoPlan = 1 or po.idEstadoPlan = 2  or po.idEstadoPlan = 3  )                        
                	AND p.proyectoDesc = ?             
                	AND sp.subproyectoDesc = ?           
                	AND c.jefatura = ?;";
	     
	    $result = $this->db->query($query,array($proyecto, $subProyecto, $jefatura));
	    return $result;
	}
	
	public function getAprobadosNoTerminadoMatCoax($proyecto, $subProyecto, $jefatura){
	
	    $query = " 	SELECT po.itemplan, wuf.mat_coax_ptr, wuf.mat_coax_est, (select vr from web_unificada where ptr = wuf.mat_coax_ptr) as  vale_reserva FROM planobra po, web_unificada_fa wuf, central c, subproyecto sp, proyecto p 
                	WHERE po.itemplan = wuf.itemplan
                	AND po.idSubProyecto = sp.idSubProyecto
                	AND sp.idProyecto = p.idProyecto
                	AND po.idCentral = c.idCentral	
                	AND 
                	(substring(wuf.mat_coax_est,1,2) = '02' or substring(wuf.mat_coax_est,1,2) = '05'
                        				or substring(wuf.mat_coax_est,1,2) = '06' or substring(wuf.mat_coax_est,1,2) = '08')
                        				AND (po.idEstadoPlan = 1 or po.idEstadoPlan = 2  or po.idEstadoPlan = 3  )                        
                	AND p.proyectoDesc = ?             
                	AND sp.subproyectoDesc = ?           
                	AND c.jefatura = ?;";
	
	    $result = $this->db->query($query,array($proyecto, $subProyecto, $jefatura));
	    return $result;
	}
	
	public function getAprobadosNoTerminadoMatFo($proyecto, $subProyecto, $jefatura){
	
	    $query = " 	SELECT po.itemplan, wuf.mat_fo_ptr, wuf.mat_fo_est, (select vr from web_unificada where ptr = wuf.mat_fo_ptr) as vale_reserva FROM planobra po, web_unificada_fa wuf, central c, subproyecto sp, proyecto p 
                	WHERE po.itemplan = wuf.itemplan
                	AND po.idSubProyecto = sp.idSubProyecto
                	AND sp.idProyecto = p.idProyecto
                	AND po.idCentral = c.idCentral	
                	AND 
                	(substring(wuf.mat_fo_est,1,2) = '02' or substring(wuf.mat_fo_est,1,2) = '05'
                        				or substring(wuf.mat_fo_est,1,2) = '06' or substring(wuf.mat_fo_est,1,2) = '08')
                        				AND (po.idEstadoPlan = 1 or po.idEstadoPlan = 2  or po.idEstadoPlan = 3  )                        
                	AND p.proyectoDesc = ?             
                	AND sp.subproyectoDesc = ?           
                	AND c.jefatura = ?;";
	
	    $result = $this->db->query($query,array($proyecto, $subProyecto, $jefatura));
	    return $result;
	}
	
	public function getAprobadosTerminadoMatFo($proyecto, $subProyecto, $jefatura){
	
	    $query = " 	SELECT po.itemplan, wuf.mat_fo_ptr, wuf.mat_fo_est, (select vr from web_unificada where ptr = wuf.mat_fo_ptr) as vale_reserva  FROM planobra po, web_unificada_fa wuf, central c, subproyecto sp, proyecto p
                	WHERE po.itemplan = wuf.itemplan
                	AND po.idSubProyecto = sp.idSubProyecto
                	AND sp.idProyecto = p.idProyecto
                	AND po.idCentral = c.idCentral
                	AND
                	(po.idEstadoPlan = 4 or po.idEstadoPlan = 5)
                	AND p.proyectoDesc = ?
                	AND sp.subproyectoDesc = ?
                	AND c.jefatura = ?;";
	
	    $result = $this->db->query($query,array($proyecto, $subProyecto, $jefatura));
	    return $result;
	}
	
	public function getAprobadosTerminadoMatCoax($proyecto, $subProyecto, $jefatura){
	
	    $query = " 	SELECT po.itemplan, wuf.mat_coax_ptr, wuf.mat_coax_est, po.fechaTermino FROM planobra po, web_unificada_fa wuf, central c, subproyecto sp, proyecto p
                	WHERE po.itemplan = wuf.itemplan
                	AND po.idSubProyecto = sp.idSubProyecto
                	AND sp.idProyecto = p.idProyecto
                	AND po.idCentral = c.idCentral
                	AND
                	(po.idEstadoPlan = 4 or po.idEstadoPlan = 5)
                	AND p.proyectoDesc = ?
                	AND sp.subproyectoDesc = ?
                	AND c.jefatura = ?;";
	
	    $result = $this->db->query($query,array($proyecto, $subProyecto, $jefatura));
	    return $result;
	}
	
	public function getDisenoMatFo($proyecto, $subProyecto, $jefatura){
	
	    $query = " 	SELECT po.itemplan, wuf.mat_fo_ptr, wuf.mat_fo_est  FROM planobra po, web_unificada_fa wuf, central c, subproyecto sp, proyecto p
                	WHERE po.itemplan = wuf.itemplan
                	AND po.idSubProyecto = sp.idSubProyecto
                	AND sp.idProyecto = p.idProyecto
                	AND po.idCentral = c.idCentral
                	AND wuf.mat_fo_est is null
                	AND (po.idEstadoPlan = 1 or po.idEstadoPlan = 2  or po.idEstadoPlan = 3  )
                	AND p.proyectoDesc = ?
                	AND sp.subproyectoDesc = ?
                	AND c.jefatura = ?;";
	
	    $result = $this->db->query($query,array($proyecto, $subProyecto, $jefatura));
	    return $result;
	}
	
	public function getDisenoMatCoax($proyecto, $subProyecto, $jefatura){
	
	    $query = " 	SELECT po.itemplan, wuf.mat_coax_ptr, wuf.mat_coax_est FROM planobra po, web_unificada_fa wuf, central c, subproyecto sp, proyecto p
                	WHERE po.itemplan = wuf.itemplan
                	AND po.idSubProyecto = sp.idSubProyecto
                	AND sp.idProyecto = p.idProyecto
                	AND po.idCentral = c.idCentral
                	AND wuf.mat_coax_est is null
                	AND (po.idEstadoPlan = 1 or po.idEstadoPlan = 2  or po.idEstadoPlan = 3  )
                	AND p.proyectoDesc = ?
                	AND sp.subproyectoDesc = ?
                	AND c.jefatura = ?;";
	
	    $result = $this->db->query($query,array($proyecto, $subProyecto, $jefatura));
	    return $result;
	}
}