<?php
class M_seguimiento_pdo_3 extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
    function   getSeguimientoItemplan($proyecto, $subProyecto, $region, $fase, $mes,$idFase = null){            
        
        $query = "SELECT ec.empresaColabDesc, ec.idEmpresaColab, c.jefatura,p.proyectoDesc, sp.subProyectoDesc,f.faseDesc, 
        			count(1)as total, SUM(CASE 
                     WHEN (po.idEstadoPlan = 4 or po.idEstadoPlan = 5) THEN 1
                     ELSE 0
                   END) AS terminados,
			   SUM(CASE 
				 WHEN (wuf.mat_coax_est is not null) THEN 1
				 ELSE 0
			   END) AS con_ptr_coax,
               SUM(CASE 
                     WHEN (substring(wuf.mat_coax_est,1,2) = '02' or substring(wuf.mat_coax_est,1,2) = '05'
        				or substring(wuf.mat_coax_est,1,2) = '06' or substring(wuf.mat_coax_est,1,2) = '08')
        				 THEN 1
                     ELSE 0
                   END) AS con_vr_coax,
			   SUM(CASE 
				 WHEN (wuf.mat_fo_est is not null) THEN 1
				 ELSE 0
			   END) AS con_ptr_fo,
            SUM(CASE 
                     WHEN (substring(wuf.mat_fo_est,1,2) = '02' or substring(wuf.mat_fo_est,1,2) = '05'
        				or substring(wuf.mat_fo_est,1,2) = '06' or substring(wuf.mat_fo_est,1,2) = '08')
        				THEN 1
                     ELSE 0
                   END) AS con_vr_fo,
                   (CASE 
				 WHEN (SELECT COUNT(1) FROM subproyectoestacion where idSubProyecto = sp.idSubproyecto and idEstacionArea = 3) > 0 THEN 1
				 ELSE 0
			   END) AS hasMatCoax
                    FROM planobra po, web_unificada_fa wuf, central c, subproyecto sp, proyecto p, empresacolab ec, fase f    
            WHERE po.itemplan = wuf.itemplan
            AND po.idSubProyecto = sp.idSubProyecto
            AND po.idEmpresacolab = ec.idEmpresaColab
            AND sp.idProyecto = p.idProyecto
            AND po.idCentral = c.idCentral
            AND po.idFase = f.idFase
            AND po.idEstadoPlan != 6 ";
            
        if($idFase!=''){
            $query .= " AND po.idFase = ".$idFase;
        }
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
        if($mes!=''){
            $query .= " AND MONTH(po.fechaPrevEjec) IN (".$mes.") AND YEAR(po.fechaPrevEjec) = ".(($fase=='')? '2018' : $fase);
        }
        if($region!=''){
           $query .= " and c.region = '".$region."'"; 
           if($region=='LIMA'){
               $query .= "  group  by ec.empresaColabDesc, c.jefatura, po.idSubProyecto
            ORDER BY ec.empresaColabDesc, c.jefatura, p.proyectoDesc;";
           }else{
               $query .= "  group  by c.jefatura, po.idSubProyecto
            ORDER BY c.jefatura, p.proyectoDesc;";
           }
               
        }else{
             $query .= "  group  by c.jefatura, po.idSubProyecto
            ORDER BY c.jefatura, p.proyectoDesc;";
        }
       
	    $result = $this->db->query($query,array());	   
	    return $result;
	}	
    
	

	
	public function getTerminados($proyecto, $subProyecto, $jefatura, $mes, $fase, $eecc){
	
	    $query = " 	SELECT po.itemplan,sp.subProyectoDesc, po.fechaTermino, po.indicador
                    FROM planobra po, central c, subproyecto sp, proyecto p               
                    WHERE po.idSubProyecto = sp.idSubProyecto
                    AND sp.idProyecto = p.idProyecto
                    AND po.idCentral = c.idCentral
                    AND (po.idEstadoPlan = 4 or    po.idEstadoPlan = 5)  "; 
            if($mes!=''){
            $query .= " AND MONTH(po.fechaPrevEjec) IN (".$mes.") AND YEAR(po.fechaPrevEjec) = ".(($fase=='')? '2018' : $fase);
            }   
            $query .= " AND p.proyectoDesc = ?
                	AND sp.subproyectoDesc = ?
                	AND c.jefatura = ? ";
            if($jefatura=='LIMA' && $eecc != 0){
                $query .= " AND po.idEmpresaColab = ".$eecc."";
            }
                    
	
            $result = $this->db->query($query,array($proyecto, $subProyecto, $jefatura));
	    return $result;
	}
	
	public function getConPTRMatFo($proyecto, $subProyecto, $jefatura, $mes, $fase, $eecc){
	
	    $query = " 	SELECT po.itemplan, sp.subProyectoDesc, wuf.mat_fo_ptr, po.indicador
                    FROM planobra po, web_unificada_fa wuf, central c, subproyecto sp, proyecto p
                	WHERE po.itemplan = wuf.itemplan
                    and wuf.mat_fo_est is not null
                	AND po.idSubProyecto = sp.idSubProyecto
                	AND sp.idProyecto = p.idProyecto
                	AND po.idCentral = c.idCentral
                	AND po.idEstadoPlan != 6";
        if($mes!=''){
            $query .= " AND MONTH(po.fechaPrevEjec) IN (".$mes.") AND YEAR(po.fechaPrevEjec) = ".(($fase=='')? '2018' : $fase);
            } 
        $query .= " AND p.proyectoDesc = ?
                	AND sp.subproyectoDesc = ?
                	AND c.jefatura = ? ";
        if($jefatura=='LIMA' && $eecc != 0){
            $query .= " AND po.idEmpresaColab = ".$eecc."";
        }
	    $result = $this->db->query($query,array($proyecto, $subProyecto, $jefatura));
	    return $result;
	}
	
	public function getConPTRMatCoax($proyecto, $subProyecto, $jefatura, $mes, $fase, $eecc){
	
	    $query = " 	SELECT po.itemplan, sp.subProyectoDesc, wuf.mat_coax_ptr, po.indicador
                    FROM planobra po, web_unificada_fa wuf, central c, subproyecto sp, proyecto p
                	WHERE po.itemplan = wuf.itemplan
                    and wuf.mat_coax_est is not null
                	AND po.idSubProyecto = sp.idSubProyecto
                	AND sp.idProyecto = p.idProyecto
                	AND po.idCentral = c.idCentral
                	AND po.idEstadoPlan != 6";
        if($mes!=''){
            $query .= " AND MONTH(po.fechaPrevEjec) IN (".$mes.") AND YEAR(po.fechaPrevEjec) = ".(($fase=='')? '2018' : $fase);
            } 	
        $query .= " AND p.proyectoDesc = ?
                	AND sp.subproyectoDesc = ?
                	AND c.jefatura = ? ";
        if($jefatura=='LIMA' && $eecc != 0){
            $query .= " AND po.idEmpresaColab = ".$eecc."";
        }
	    $result = $this->db->query($query,array($proyecto, $subProyecto, $jefatura));
	    return $result;
	}
	
	public function getAprobadosMatFo($proyecto, $subProyecto, $jefatura, $mes, $fase, $eecc){
	
	    $query = " 	SELECT po.itemplan, sp.subProyectoDesc, wuf.mat_fo_ptr, (select vr from web_unificada where ptr = wuf.mat_fo_ptr LIMIT 1) as  vale_reserva, (select f_aprob from web_unificada where ptr = wuf.mat_fo_ptr LIMIT 1) as  fec_aprob, po.indicador,
                    ROUND((SELECT  AVG(porcentaje)  FROM itemplanestacionavance where itemplan = po.itemplan AND porcentaje != 'NR')) as prom
                    FROM planobra po, web_unificada_fa wuf, central c, subproyecto sp, proyecto p
                	WHERE po.itemplan = wuf.itemplan
                    and (substring(wuf.mat_fo_est,1,2) = '02' or substring(wuf.mat_fo_est,1,2) = '05'
        				or substring(wuf.mat_fo_est,1,2) = '06' or substring(wuf.mat_fo_est,1,2) = '08')
                	AND po.idSubProyecto = sp.idSubProyecto
                	AND sp.idProyecto = p.idProyecto
                	AND po.idCentral = c.idCentral
                	AND po.idEstadoPlan != 6";
        if($mes!=''){
            $query .= " AND MONTH(po.fechaPrevEjec) IN (".$mes.") AND YEAR(po.fechaPrevEjec) = ".(($fase=='')? '2018' : $fase);
        }                       
        $query .= " AND p.proyectoDesc = ?             
                	AND sp.subproyectoDesc = ?           
                	AND c.jefatura = ? ";
        if($jefatura=='LIMA' && $eecc != 0){
            $query .= " AND po.idEmpresaColab = ".$eecc."";
        }
            $query .= " ORDER BY prom";
	    $result = $this->db->query($query,array($proyecto, $subProyecto, $jefatura));
	    return $result;
	}
	
	public function getAprobadosMatCoax($proyecto, $subProyecto, $jefatura, $mes, $fase, $eecc){
	
	    $query = " 	SELECT po.itemplan, sp.subProyectoDesc, wuf.mat_coax_ptr, (select vr from web_unificada where ptr = wuf.mat_coax_ptr LIMIT 1) as  vale_reserva, (select f_aprob from web_unificada where ptr = wuf.mat_coax_ptr LIMIT 1) as  fec_aprob, po.indicador,
                    ROUND((SELECT  AVG(porcentaje)  FROM itemplanestacionavance where itemplan = po.itemplan AND porcentaje != 'NR')) as prom
                    FROM planobra po, web_unificada_fa wuf, central c, subproyecto sp, proyecto p
                	WHERE po.itemplan = wuf.itemplan
                    and (substring(wuf.mat_coax_est,1,2) = '02' or substring(wuf.mat_coax_est,1,2) = '05'
        				or substring(wuf.mat_coax_est,1,2) = '06' or substring(wuf.mat_coax_est,1,2) = '08')
                	AND po.idSubProyecto = sp.idSubProyecto
                	AND sp.idProyecto = p.idProyecto
                	AND po.idCentral = c.idCentral
                	AND po.idEstadoPlan != 6";
        if($mes!=''){
            $query .= " AND MONTH(po.fechaPrevEjec) IN (".$mes.") AND YEAR(po.fechaPrevEjec) = ".(($fase=='')? '2018' : $fase);
        }
        $query .= " AND p.proyectoDesc = ?
                	AND sp.subproyectoDesc = ?
                	AND c.jefatura = ? ";
        if($jefatura=='LIMA' && $eecc != 0){
            $query .= " AND po.idEmpresaColab = ".$eecc."";
        }
            $query .= " ORDER BY prom";
	    $result = $this->db->query($query,array($proyecto, $subProyecto, $jefatura));
	    return $result;
	}

	
	public function getDisenoMatFo($proyecto, $subProyecto, $jefatura, $mes, $fase, $eecc){
	
	    $query = " 	SELECT po.itemplan, sp.subProyectoDesc, po.indicador
                    FROM planobra po, web_unificada_fa wuf, central c, subproyecto sp, proyecto p
                	WHERE po.itemplan = wuf.itemplan
                	AND po.idSubProyecto = sp.idSubProyecto
                	AND sp.idProyecto = p.idProyecto
                	AND po.idCentral = c.idCentral
                	AND wuf.mat_fo_est is null 
                	AND po.idEstadoPlan != 6";
                	if($mes!=''){
                	$query .= " AND MONTH(po.fechaPrevEjec) IN (".$mes.") AND YEAR(po.fechaPrevEjec) = ".(($fase=='')? '2018' : $fase);
	}
	$query .= " AND p.proyectoDesc = ?
                	AND sp.subproyectoDesc = ?
                	AND c.jefatura = ? ";
	if($jefatura=='LIMA' && $eecc != 0){
	    $query .= " AND po.idEmpresaColab = ".$eecc."";
	}
	    $result = $this->db->query($query,array($proyecto, $subProyecto, $jefatura));
	    return $result;
	}
	
	public function getDisenoMatCoax($proyecto, $subProyecto, $jefatura, $mes, $fase, $eecc){
	
	    $query = " 	SELECT po.itemplan, sp.subProyectoDesc, po.indicador
                    FROM planobra po, web_unificada_fa wuf, central c, subproyecto sp, proyecto p
                	WHERE po.itemplan = wuf.itemplan
                	AND po.idSubProyecto = sp.idSubProyecto
                	AND sp.idProyecto = p.idProyecto
                	AND po.idCentral = c.idCentral
                	AND wuf.mat_coax_est is null
	                AND po.idEstadoPlan != 6";
        if($mes!=''){
            $query .= " AND MONTH(po.fechaPrevEjec) IN (".$mes.") AND YEAR(po.fechaPrevEjec) = ".(($fase=='')? '2018' : $fase);
        }   
        $query .= " AND p.proyectoDesc = ?
                	AND sp.subproyectoDesc = ?
                	AND c.jefatura = ? ";
        if($jefatura=='LIMA' && $eecc != 0){
            $query .= " AND po.idEmpresaColab = ".$eecc."";
        }
	    $result = $this->db->query($query,array($proyecto, $subProyecto, $jefatura));
	    return $result;
	}
	
	function   getEstacionPorcentajeByItemPlan($itemplan){
	    $Query = " SELECT de.*,  CASE WHEN ie.porcentaje IS NULL then 0 ELSE ie.porcentaje END AS porcentaje, ie.idItemplanEstacion FROM (
                        SELECT DISTINCT po.itemplan,e.idEstacion, e.estacionDesc
                        FROM planobra po, subproyectoestacion se, estacionarea ea, estacion e
                        WHERE po.idSubProyecto = se.idSubProyecto
                        AND se.idEstacionArea = ea.idEstacionArea
                        AND ea.idEstacion = e.idEstacion
                        AND po.itemplan = ?
                        AND e.idEstacion != 1) as de LEFT JOIN itemplanestacionavance ie
                        ON de.itemplan = ie.itemplan
                        AND de.idEstacion = ie.idEstacion
                        ORDER BY de.idEstacion; ";
	
	    $result = $this->db->query($Query,array($itemplan));
	    return $result;
	}
}