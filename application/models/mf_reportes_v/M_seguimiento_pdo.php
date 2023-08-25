<?php
class M_seguimiento_pdo extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
    function   getSeguimientoPDO($mesEjecucion,$hasFiltroFec,$fechaInicio,$fechaFin,$idProyecto,$idSubProyecto,$zonal){
         
        
        
        $estadosAprobados="02|05|06|08";
    
        
        $query = "  SELECT sp.idSubProyecto, 
                           wuf.subProyectoDesc, 
                           SUBSTRING_INDEX( wuf.zonal , ' ', 1 ) as zonal, 
                           wuf.eecc, 
                           COUNT(wuf.itemPlan) as total_obras, 
                            (SELECT COUNT(1) FROM web_unificada_fa fa, planobra pl 
                            WHERE fa.subProyectoDesc = wuf.subProyectoDesc
                            AND fa.itemPlan = pl.itemPlan  ";
                    if($mesEjecucion!=""){
       $query .= "          AND fa.mesEjecucion = '".$mesEjecucion."'"; 
                     }
       $query .= "          AND fa.eecc = wuf.eecc 
                            AND SUBSTRING_INDEX( fa.zonal , ' ', 1 ) = SUBSTRING_INDEX( wuf.zonal , ' ', 1 )
                            AND pl.idEstadoPlan = 3)  as en_obra,
                            (SELECT count(1) FROM web_unificada_fa fa, planobra pl 
                            WHERE fa.subProyectoDesc = wuf.subProyectoDesc
                            AND fa.itemPlan = pl.itemPlan";
                    if($mesEjecucion!=""){
       $query .= "          AND fa.mesEjecucion = '".$mesEjecucion."'"; 
                     }
       $query .= "          AND fa.eecc = wuf.eecc 
                            AND SUBSTRING_INDEX( fa.zonal , ' ', 1 ) = SUBSTRING_INDEX( wuf.zonal , ' ', 1 )
                            AND pl.idEstadoPlan = 4)  as terminado,         
                            (SELECT count(1) FROM web_unificada_fa fa, planobra pl 
                            WHERE fa.subProyectoDesc = wuf.subProyectoDesc
                            AND fa.itemPlan = pl.itemPlan";
                    if($mesEjecucion!=""){
       $query .= "          AND fa.mesEjecucion = '".$mesEjecucion."'"; 
                     }
       $query .= "          AND fa.eecc = wuf.eecc 
                            AND SUBSTRING_INDEX( fa.zonal , ' ', 1 ) = SUBSTRING_INDEX( wuf.zonal , ' ', 1 )
                            AND pl.idEstadoPlan = 6)  as cancelado,
                            /**************************************************MAT_COAX**************************************************/
                            COUNT(wuf.mat_coax_est) as mat_coax_crea, 
                            (SELECT count(1) FROM web_unificada_fa fa 
                            WHERE fa.subProyectoDesc = wuf.subProyectoDesc";
                    if($mesEjecucion!=""){
       $query .= "          AND fa.mesEjecucion = '".$mesEjecucion."'"; 
                     }
       $query .= "          AND fa.eecc = wuf.eecc 
                            AND SUBSTRING_INDEX( fa.zonal , ' ', 1 ) = SUBSTRING_INDEX( wuf.zonal , ' ', 1 )
                            AND mat_coax_est  REGEXP '".$estadosAprobados."')  as mat_coax_apro,
                            (SELECT getNumVrAdic(sp.idSubProyecto,".ID_ESTACIONAREA_MAT_COAX.",wuf.eecc,SUBSTRING_INDEX( wuf.zonal , ' ', 1 ),'".$hasFiltroFec."','".$fechaInicio."','".$fechaFin."')) as mat_coax_vradic,
                            (SELECT  
                            SEC_TO_TIME(AVG(TIMESTAMPDIFF(SECOND, STR_TO_DATE((fecSol), '%d/%m/%Y %H:%i:%s'),STR_TO_DATE((fecha_asig_grafo), '%d/%m/%Y %H:%i:%s'))))
                            FROM web_unificada_det
                            where fecha_asig_grafo is not NULL AND  subproy = wuf.subProyectoDesc and zonal = SUBSTRING_INDEX( wuf.zonal , ' ', 1 ) and eecc = wuf.eecc and area_desc = 'MAT_COAX') as mat_coax_tpo_apro,
                            /**************************************************MAT_FO**************************************************/
                            COUNT(wuf.mat_fo_est) as mat_fo_crea, 
                            (SELECT count(1) FROM web_unificada_fa fa 
                            WHERE fa.subProyectoDesc = wuf.subProyectoDesc";
                    if($mesEjecucion!=""){
       $query .= "          AND fa.mesEjecucion = '".$mesEjecucion."'"; 
                     }
       $query .= "          AND fa.eecc = wuf.eecc 
                            AND SUBSTRING_INDEX( fa.zonal , ' ', 1 ) = SUBSTRING_INDEX( wuf.zonal , ' ', 1 )
                            AND mat_fo_est  REGEXP '".$estadosAprobados."')  as mat_fo_apro,
                            (SELECT getNumVrAdic(sp.idSubProyecto, ".ID_ESTACIONAREA_MAT_FO." ,wuf.eecc,SUBSTRING_INDEX( wuf.zonal , ' ', 1 ),'".$hasFiltroFec."','".$fechaInicio."','".$fechaFin."')) as mat_fo_vradic,
                            (SELECT  
                            SEC_TO_TIME(AVG(TIMESTAMPDIFF(SECOND, STR_TO_DATE((fecSol), '%d/%m/%Y %H:%i:%s'),STR_TO_DATE((fecha_asig_grafo), '%d/%m/%Y %H:%i:%s'))))
                            FROM web_unificada_det
                            where fecha_asig_grafo is not NULL AND  subproy = wuf.subProyectoDesc and zonal = SUBSTRING_INDEX( wuf.zonal , ' ', 1 ) and eecc = wuf.eecc and area_desc = 'MAT_FO') as mat_fo_tpo_apro,
                            /**************************************************MAT_FUENTE**************************************************/
                            COUNT(wuf.mat_fuente_est) as mat_fuente_crea, 
                            (SELECT count(1) FROM web_unificada_fa fa 
                            WHERE fa.subProyectoDesc = wuf.subProyectoDesc";
                    if($mesEjecucion!=""){
       $query .= "          AND fa.mesEjecucion = '".$mesEjecucion."'"; 
                     }
       $query .= "          AND fa.eecc = wuf.eecc 
                            AND SUBSTRING_INDEX( fa.zonal , ' ', 1 ) = SUBSTRING_INDEX( wuf.zonal , ' ', 1 )
                            AND mat_fuente_est  REGEXP '".$estadosAprobados."')  as mat_fuente_apro,
                            (SELECT getNumVrAdic(sp.idSubProyecto, ".ID_ESTACIONAREA_MAT_FUENTE." ,wuf.eecc,SUBSTRING_INDEX( wuf.zonal , ' ', 1 ),'".$hasFiltroFec."','".$fechaInicio."','".$fechaFin."')) as mat_fuente_vradic,
                            (SELECT  
                            SEC_TO_TIME(AVG(TIMESTAMPDIFF(SECOND, STR_TO_DATE((fecSol), '%d/%m/%Y %H:%i:%s'),STR_TO_DATE((fecha_asig_grafo), '%d/%m/%Y %H:%i:%s'))))
                            FROM web_unificada_det
                            where fecha_asig_grafo is not NULL AND  subproy = wuf.subProyectoDesc and zonal = SUBSTRING_INDEX( wuf.zonal , ' ', 1 ) and eecc = wuf.eecc and area_desc = 'MAT_FUENTE') as mat_fuente_tpo_apro,
                            /**************************************************MAT_FO_OC**************************************************/
                            COUNT(wuf.mat_fo_oc_est) as mat_fo_oc_crea, 
                            (SELECT count(1) FROM web_unificada_fa fa 
                            WHERE fa.subProyectoDesc = wuf.subProyectoDesc";
                    if($mesEjecucion!=""){
       $query .= "          AND fa.mesEjecucion = '".$mesEjecucion."'"; 
                     }
       $query .= "          AND fa.eecc = wuf.eecc 
                            AND SUBSTRING_INDEX( fa.zonal , ' ', 1 ) = SUBSTRING_INDEX( wuf.zonal , ' ', 1 )
                            AND mat_fo_oc_est  REGEXP '".$estadosAprobados."')  as mat_fo_oc_apro,
                            (SELECT getNumVrAdic(sp.idSubProyecto, ".ID_ESTACIONAREA_MAT_FO_OC." ,wuf.eecc,SUBSTRING_INDEX( wuf.zonal , ' ', 1 ),'".$hasFiltroFec."','".$fechaInicio."','".$fechaFin."')) as mat_fo_oc_vradic,
                            (SELECT  
                            SEC_TO_TIME(AVG(TIMESTAMPDIFF(SECOND, STR_TO_DATE((fecSol), '%d/%m/%Y %H:%i:%s'),STR_TO_DATE((fecha_asig_grafo), '%d/%m/%Y %H:%i:%s'))))
                            FROM web_unificada_det
                            where fecha_asig_grafo is not NULL AND  subproy = wuf.subProyectoDesc and zonal = SUBSTRING_INDEX( wuf.zonal , ' ', 1 ) and eecc = wuf.eecc and area_desc = 'MAT_FO_OC') as mat_fo_oc_tpo_apro,
                            /**************************************************MAT_COAX_OC**************************************************/
                            COUNT(wuf.mat_coax_oc_est) as mat_coax_oc_crea, 
                            (SELECT count(1) FROM web_unificada_fa fa 
                            WHERE fa.subProyectoDesc = wuf.subProyectoDesc";
                    if($mesEjecucion!=""){
       $query .= "          AND fa.mesEjecucion = '".$mesEjecucion."'"; 
                     }
       $query .= "          AND fa.eecc = wuf.eecc 
                            AND SUBSTRING_INDEX( fa.zonal , ' ', 1 ) = SUBSTRING_INDEX( wuf.zonal , ' ', 1 )
                            AND mat_coax_oc_est  REGEXP '".$estadosAprobados."')  as mat_coax_oc_apro,
                            (SELECT getNumVrAdic(sp.idSubProyecto, ".ID_ESTACIONAREA_MAT_COAX_OC." ,wuf.eecc,SUBSTRING_INDEX( wuf.zonal , ' ', 1 ),'".$hasFiltroFec."','".$fechaInicio."','".$fechaFin."')) as mat_coax_oc_vradic,
                            (SELECT  
                            SEC_TO_TIME(AVG(TIMESTAMPDIFF(SECOND, STR_TO_DATE((fecSol), '%d/%m/%Y %H:%i:%s'),STR_TO_DATE((fecha_asig_grafo), '%d/%m/%Y %H:%i:%s'))))
                            FROM web_unificada_det
                            where fecha_asig_grafo is not NULL AND  subproy = wuf.subProyectoDesc and zonal = SUBSTRING_INDEX( wuf.zonal , ' ', 1 ) and eecc = wuf.eecc and area_desc = 'MAT_COAX_OC') as mat_coax_oc_tpo_apro,
                            /**************************************************MAT_ENER**************************************************/
                            COUNT(wuf.mat_ener_est) as mat_ener_crea, 
                            (SELECT count(1) FROM web_unificada_fa fa 
                            WHERE fa.subProyectoDesc = wuf.subProyectoDesc";
                    if($mesEjecucion!=""){
       $query .= "          AND fa.mesEjecucion = '".$mesEjecucion."'"; 
                     }
       $query .= "          AND fa.eecc = wuf.eecc 
                            AND SUBSTRING_INDEX( fa.zonal , ' ', 1 ) = SUBSTRING_INDEX( wuf.zonal , ' ', 1 )
                            AND mat_ener_est  REGEXP '".$estadosAprobados."')  as mat_ener_apro,
                            (SELECT getNumVrAdic(sp.idSubProyecto, ".ID_ESTACIONAREA_MAT_ENER." ,wuf.eecc,SUBSTRING_INDEX( wuf.zonal , ' ', 1 ),'".$hasFiltroFec."','".$fechaInicio."','".$fechaFin."')) as mat_ener_vradic,
                            (SELECT  
                            SEC_TO_TIME(AVG(TIMESTAMPDIFF(SECOND, STR_TO_DATE((fecSol), '%d/%m/%Y %H:%i:%s'),STR_TO_DATE((fecha_asig_grafo), '%d/%m/%Y %H:%i:%s'))))
                            FROM web_unificada_det
                            WHERE fecha_asig_grafo is not NULL AND  subproy = wuf.subProyectoDesc and zonal = SUBSTRING_INDEX( wuf.zonal , ' ', 1 ) and eecc = wuf.eecc and area_desc = 'MAT_ENER') as mat_ener_tpo_apro
                            
                            FROM    web_unificada_fa wuf, 
                                    subproyecto sp 
                            WHERE wuf.subProyectoDesc = sp.subProyectoDesc ";
                 
                        if($idProyecto!=''){
                $query .= " AND sp.idProyecto = $idProyecto";
                        }
                        
                        if($idSubProyecto!=''){
                            
                            $query .= " AND sp.idSubProyecto IN (".$idSubProyecto.")";
                        }                       
                        if($zonal!=''){
                            $query .= " AND SUBSTRING_INDEX( wuf.zonal , ' ', 1 ) = '".$zonal."'";
                        }
                        
                        if($mesEjecucion!=""){
                            $query .= "          AND wuf.mesEjecucion = '".$mesEjecucion."'";
                        }
                $query .= " GROUP BY wuf.subProyectoDesc, SUBSTRING_INDEX( wuf.zonal , ' ', 1 ), wuf.eecc ";
	    $result = $this->db->query($query,array());	   
	    return $result;
	}	

	
}