<?php
class M_seguimiento_avance extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
    function   getSeguimientoItemplan($proyecto, $subProyecto, $region, $fase, $mes,$idFase = ''){            
        
       $query = "SELECT tb3.*, round(
                    (
                    if(tb3.coaxial = 'NO', 0, tb3.coaxial) + 
                    if(tb3.oc_coaxial = 'NO', 0, tb3.oc_coaxial) + 
                    if(tb3.fuente = 'NO', 0, tb3.fuente) +
                    if(tb3.um = 'NO', 0, tb3.um) +
                    if(tb3.fo = 'NO', 0, tb3.fo) + 
                    if(tb3.oc_fo = 'NO', 0, if(tb3.idSubProyecto not in (171,
																		220,
																		221,
																		219,
																		101,
																		6,
																		156,
																		79,
																		80),tb3.oc_fo, 0)) + 
                    if(tb3.energia = 'NO', 0, if(tb3.idSubProyecto not in (171,
																		220,
																		221,
																		219,
																		101,
																		6,
																		156,
																		79,
																		80),tb3.energia, 0)) +
                    if(tb3.multipar = 'NO', 0, tb3.multipar) + 
                    if(tb3.inst_troba = 'NO', 0, tb3.inst_troba) + 
                    if(tb3.inte_troba = 'NO', 0, tb3.inte_troba)
                    )/(
                    if(tb3.coaxial = 'NO', 0, 1) + 
                    if(tb3.oc_coaxial = 'NO', 0, 1) + 
                    if(tb3.fuente = 'NO', 0, 1) +
                    if(tb3.um = 'NO', 0, 1) +
                    if(tb3.fo = 'NO', 0, 1) + 
                    if(tb3.oc_fo = 'NO', 0, if(tb3.idSubProyecto not in (171,
																		220,
																		221,
																		219,
																		101,
																		6,
																		156,
																		79,
																		80),1, 0)) + 
                    if(tb3.energia = 'NO', 0, if(tb3.idSubProyecto not in (171,
																		220,
																		221,
																		219,
																		101,
																		6,
																		156,
																		79,
																		80),1, 0)) +
                    if(tb3.multipar = 'NO', 0, 1) + 
                    if(tb3.inst_troba = 'NO', 0, 1) + 
                    if(tb3.inte_troba = 'NO', 0, 1)
                    ),0)  as prom
                    FROM 
                    	(SELECT tb1.itemplan,tb1.indicador,tb1.empresaColabDesc,tb1.faseDesc,tb1.jefatura,tb1.estadoPlanDesc,tb1.subproyectoDesc, tb1.idSubProyecto, CASE 
                    				WHEN tb1.has_coaxial >= 1 THEN tb2.coaxial ELSE 'NO' END as coaxial,
                                    CASE 
                    				WHEN tb1.has_oc_coaxial >= 1 THEN tb2.oc_coaxial ELSE 'NO' END as oc_coaxial,
                                    CASE 
                    				WHEN tb1.has_fuente >= 1 THEN tb2.fuente ELSE 'NO' END as fuente,
                    				CASE 
                    				WHEN tb1.has_um >= 1 THEN tb2.um ELSE 'NO' END as um,
                                    CASE 
                    				WHEN tb1.has_fo >= 1 THEN tb2.fo ELSE 'NO' END as fo,
                                    CASE 
                    				WHEN tb1.has_oc_fo >= 1 THEN tb2.oc_fo ELSE 'NO' END as oc_fo,
                                    CASE 
                    				WHEN tb1.has_energia >= 1 THEN tb2.energia ELSE 'NO' END as energia,                
                    				CASE 
                    				WHEN tb1.has_multipar >= 1 THEN tb2.multipar ELSE 'NO' END as multipar,
                                    CASE 
                    				WHEN tb1.has_inst_troba >= 1 THEN tb2.inst_troba ELSE 'NO' END as inst_troba,
                                    CASE 
                    				WHEN tb1.has_inte_troba >= 1 THEN tb2.inte_troba ELSE 'NO' END as inte_troba
                    
                    FROM (SELECT po.itemplan,f.faseDesc,po.indicador, ec.empresaColabDesc, c.jefatura, ep.estadoPlanDesc, sp.subProyectoDesc, po.idSubProyecto,
                    					COUNT(CASE 
                                WHEN ea.idEstacion = 2
                                THEN 1
                                ELSE NULL 
                            END)
                        AS has_coaxial,
                        COUNT(CASE 
                                WHEN ea.idEstacion = 3
                                THEN 1
                                ELSE NULL 
                            END)
                        AS has_oc_coaxial,
                        COUNT(CASE 
                                WHEN ea.idEstacion = 4
                                THEN 1
                                ELSE NULL 
                            END)
                        AS has_fuente,
                        COUNT(CASE 
                                WHEN ea.idEstacion = 13
                                THEN 1
                                ELSE NULL 
                            END)
                        AS has_um,
                        COUNT(CASE 
                                WHEN ea.idEstacion = 5
                                THEN 1
                                ELSE NULL 
                            END)
                        AS has_fo,
                        COUNT(CASE 
                                WHEN ea.idEstacion = 6
                                THEN 1
                                ELSE NULL 
                            END)
                        AS has_oc_fo,
                        COUNT(CASE 
                                WHEN ea.idEstacion = 7
                                THEN 1
                                ELSE NULL 
                            END)
                        AS has_energia,
                        COUNT(CASE 
                                WHEN ea.idEstacion = 8
                                THEN 1
                                ELSE NULL 
                            END)
                        AS has_multipar,
                        COUNT(CASE 
                                WHEN ea.idEstacion = 9
                                THEN 1
                                ELSE NULL 
                            END)
                        AS has_inst_troba,
                        COUNT(CASE 
                                WHEN ea.idEstacion = 10
                                THEN 1
                                ELSE NULL 
                            END)
                        AS has_inte_troba
                                        FROM empresacolab ec, 
                                        estadoplan ep, 
                                        central c, 
                                        subproyecto sp, 
                                        subproyectoestacion se, 
                                        estacionarea ea,
                                        planobra po,
                                        fase f
                                        WHERE po.idEstadoPlan != 6
                                        AND po.idSubProyecto = sp.idSubProyecto
                    					AND sp.idSubProyecto = se.idSubProyecto
                    					AND se.idEstacionArea = ea.idEstacionArea
                                        AND po.idEmpresacolab = ec.idEmpresaColab
                                        AND po.idCentral = c.idCentral
                                        AND po.idEstadoPlan = ep.idEstadoPlan
                                        AND po.idFase = f.idFase ";
        if($idFase!=''){
            $query .= " AND po.idFase = ".$idFase;
        }
        if($proyecto!=''){
            $query .= " AND sp.idProyecto = ".$proyecto;
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
        }
        $query .= " group by po.itemplan) as tb1 LEFT JOIN (SELECT  itemplan,
        SUM(CASE 
            WHEN idEstacion = 2
            THEN porcentaje
            ELSE 0 
        END)
    AS coaxial
    ,    
        SUM(CASE 
            WHEN idEstacion = 3
            THEN porcentaje
            ELSE 0 
        END)
    AS oc_coaxial
    ,    
        SUM(CASE 
            WHEN idEstacion = 4
            THEN porcentaje
            ELSE 0 
        END)
    AS fuente
    ,    
    SUM(CASE 
            WHEN idEstacion = 13
            THEN porcentaje
            ELSE 0 
        END)
    AS um
    ,   
        SUM(CASE 
            WHEN idEstacion = 5
            THEN porcentaje
            ELSE 0 
        END)
    AS fo
    ,    
       SUM(CASE 
            WHEN idEstacion = 6
            THEN porcentaje
            ELSE 0 
        END)
    AS oc_fo
    ,    
        SUM(CASE 
            WHEN idEstacion = 7
            THEN porcentaje
            ELSE 0 
        END)
    AS energia
    ,    
        SUM(CASE 
            WHEN idEstacion = 8
            THEN porcentaje
            ELSE 0 
        END)
    AS multipar
    ,    
        SUM(CASE 
            WHEN idEstacion = 9
            THEN porcentaje
            ELSE 0 
        END)
    AS inst_troba,    
        SUM(CASE 
            WHEN idEstacion = 10
            THEN porcentaje
            ELSE 0 
        END)
    AS inte_troba
FROM    itemplanestacionavance 
GROUP BY itemplan) as tb2
ON tb1.itemplan = tb2.itemplan) as tb3 
ORDER BY prom";
        
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