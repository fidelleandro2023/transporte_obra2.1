<?php
class M_seguimiento_ec extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}

    function   getSeguimientoPDO($mesEjecucion,$hasFiltroFec,$cadenaPl,$cadenaPa,$idProyecto,$idSubProyecto){
    
         
        
        
        //$estadosAprobados="02|05|06|08";
        $query = "  SELECT p.idProyecto, p.proyectoDesc ,sp.idSubProyecto, wuf.subProyectoDesc, count(wuf.itemPlan) as total_obras, 

(SELECT count(1) FROM web_unificada_fa fa, planobra pb 
WHERE fa.subProyectoDesc = wuf.subProyectoDesc
AND fa.itemPlan = pb.itemPlan ";
/*AND fa.mesEjecucion = 'ENE' */
if($cadenaPl!=''){
                            
    $query .= " AND ".$cadenaPl." ";
}
$query .= " AND pb.idEstadoPlan = 3)  as en_obra,

(SELECT count(1) FROM web_unificada_fa fa, planobra pb 
WHERE fa.subProyectoDesc = wuf.subProyectoDesc
AND fa.itemPlan = pb.itemPlan ";
if($cadenaPl!=''){
                            
    $query .= " AND ".$cadenaPl." ";
}

$query .= " AND fa.zonal = wuf.zonal 
AND pb.idEstadoPlan = 4)  as terminado,

(SELECT count(1) FROM web_unificada_fa fa, planobra pb 
WHERE fa.subProyectoDesc = wuf.subProyectoDesc
AND fa.itemPlan = pb.itemPlan ";
if($cadenaPl!=''){
                            
    $query .= " AND ".$cadenaPl." ";
}

$query .= " AND pb.idEstadoPlan = 5)  as cerrado,

(SELECT count(1) FROM web_unificada_fa fa, planobra pb 
WHERE fa.subProyectoDesc = wuf.subProyectoDesc 
AND fa.itemPlan = pb.itemPlan ";
if($cadenaPl!=''){
                            
    $query .= " AND ".$cadenaPl." ";
}

$query .= " AND pb.idEstadoPlan = 6)  as cancelado,

(SELECT count(1) FROM web_unificada_fa fa, planobra pb 
WHERE fa.subProyectoDesc = wuf.subProyectoDesc
AND fa.itemPlan = pb.itemPlan ";
if($cadenaPl!=''){
                            
    $query .= " AND ".$cadenaPl." ";
}
$query .= " AND pb.idEstadoPlan <> 6 
AND fa.eecc = 'COBRA')  as totalCOBRA,

(SELECT count(1) FROM web_unificada_fa fa, planobra pb 
WHERE fa.subProyectoDesc = wuf.subProyectoDesc 
AND fa.itemPlan = pb.itemPlan ";
if($cadenaPl!=''){
                            
    $query .= " AND ".$cadenaPl." ";
}
$query .= " AND pb.idEstadoPlan <> 6 
AND fa.eecc = 'LARI')  as totalLARI,

(SELECT count(1) FROM web_unificada_fa fa, planobra pb 
WHERE fa.subProyectoDesc = wuf.subProyectoDesc
AND fa.itemPlan = pb.itemPlan ";
if($cadenaPl!=''){
                            
    $query .= " AND ".$cadenaPl." ";
}
$query .= " AND pb.idEstadoPlan <> 6 
AND fa.eecc = 'DOMINION')  as totalDOMINION,

(SELECT count(1) FROM web_unificada_fa fa, planobra pb 
WHERE fa.subProyectoDesc = wuf.subProyectoDesc
AND fa.itemPlan = pb.itemPlan ";
if($cadenaPl!=''){
                            
    $query .= " AND ".$cadenaPl." ";
}
$query .= " AND pb.idEstadoPlan <> 6 
AND fa.eecc = 'EZENTIS')  as totalEZENTIS,

/***********************MAT_COAX*************************/
/***COBRA****/
(SELECT count(fa.mat_coax_est) FROM web_unificada_fa fa, planobra pb
WHERE fa.subProyectoDesc = wuf.subProyectoDesc
AND fa.itemPlan = pb.itemPlan 
 AND pb.idEstadoPlan <> 6 ";
if($cadenaPl!=''){
                            
    $query .= " AND ".$cadenaPl." ";
}
$query .= " AND fa.eecc = 'COBRA')  as creadosMatCoaxCOBRA,

(SELECT count(fa.mat_coax_est) FROM web_unificada_fa fa, planobra pb 
WHERE fa.subProyectoDesc = wuf.subProyectoDesc
AND fa.itemPlan = pb.itemPlan
 AND pb.idEstadoPlan <> 6 ";
if($cadenaPl!=''){
                            
    $query .= " AND ".$cadenaPl." ";
}
$query .= " AND fa.eecc = 'COBRA'
AND fa.mat_coax_est  REGEXP '02|05|06|08')  as aproMatCoaxCOBRA,


/***LARI****/
(SELECT count(fa.mat_coax_est) FROM web_unificada_fa fa, planobra pb
WHERE fa.subProyectoDesc = wuf.subProyectoDesc
AND fa.itemPlan = pb.itemPlan
 AND pb.idEstadoPlan <> 6 ";
if($cadenaPl!=''){
                            
    $query .= " AND ".$cadenaPl." ";
}
$query .= " AND fa.eecc = 'LARI')  as creadosMatCoaxLARI,

(SELECT count(fa.mat_coax_est) FROM web_unificada_fa fa, planobra pb 
WHERE fa.subProyectoDesc = wuf.subProyectoDesc
AND fa.itemPlan = pb.itemPlan
 AND pb.idEstadoPlan <> 6 ";
if($cadenaPl!=''){
                            
    $query .= " AND ".$cadenaPl." ";
}
$query .= " AND fa.eecc = 'LARI'
AND fa.mat_coax_est  REGEXP '02|05|06|08')  as aproMatCoaxLARI,

/***DOMINION****/
(SELECT count(fa.mat_coax_est) FROM web_unificada_fa fa, planobra pb 
WHERE fa.subProyectoDesc = wuf.subProyectoDesc
AND fa.itemPlan = pb.itemPlan
 AND pb.idEstadoPlan <> 6 ";
if($cadenaPl!=''){
                            
    $query .= " AND ".$cadenaPl." ";
}
 $query .= " AND fa.eecc = 'DOMINION')  as creadosMatCoaxDOMINION,

(SELECT count(fa.mat_coax_est) FROM web_unificada_fa fa, planobra pb 
WHERE fa.subProyectoDesc = wuf.subProyectoDesc
AND fa.itemPlan = pb.itemPlan
 AND pb.idEstadoPlan <> 6 ";
if($cadenaPl!=''){
                            
    $query .= " AND ".$cadenaPl." ";
}
$query .= " AND fa.eecc = 'DOMINION'
AND fa.mat_coax_est  REGEXP '02|05|06|08')  as aproMatCoaxDOMINION,

/***EZENTIS****/
(SELECT count(fa.mat_coax_est) FROM web_unificada_fa fa, planobra pb 
WHERE fa.subProyectoDesc = wuf.subProyectoDesc
AND fa.itemPlan = pb.itemPlan
 AND pb.idEstadoPlan <> 6 ";
if($cadenaPl!=''){
                            
    $query .= " AND ".$cadenaPl." ";
}
$query .= " AND fa.eecc = 'EZENTIS')  as creadosMatCoaxEZENTIS,

(SELECT count(fa.mat_coax_est) FROM web_unificada_fa fa, planobra pb  
WHERE fa.subProyectoDesc = wuf.subProyectoDesc
AND fa.itemPlan = pb.itemPlan
 AND pb.idEstadoPlan <> 6 ";
if($cadenaPl!=''){
                            
    $query .= " AND ".$cadenaPl." ";
}
$query .= " AND fa.eecc = 'EZENTIS'
AND fa.mat_coax_est  REGEXP '02|05|06|08')  as aproMatCoaxEZENTIS,

/******************************MAT_FO********************************/
/***COBRA****/
(SELECT count(fa.mat_fo_est) FROM web_unificada_fa fa, planobra pb
WHERE fa.subProyectoDesc = wuf.subProyectoDesc
AND fa.itemPlan = pb.itemPlan
 AND pb.idEstadoPlan <> 6 ";
if($cadenaPl!=''){
                            
    $query .= " AND ".$cadenaPl." ";
}
$query .= " AND fa.eecc = 'COBRA')  as creadosMatFoCOBRA,

(SELECT count(fa.mat_fo_est) FROM web_unificada_fa fa, planobra pb  
WHERE fa.subProyectoDesc = wuf.subProyectoDesc
AND fa.itemPlan = pb.itemPlan
 AND pb.idEstadoPlan <> 6 ";
if($cadenaPl!=''){
                            
    $query .= " AND ".$cadenaPl." ";
}
$query .= " AND fa.eecc = 'COBRA'
AND fa.mat_fo_est  REGEXP '02|05|06|08')  as aproMatFoCOBRA,


/***LARI****/
(SELECT count(fa.mat_fo_est) FROM web_unificada_fa fa, planobra pb
WHERE fa.subProyectoDesc = wuf.subProyectoDesc
AND fa.itemPlan = pb.itemPlan
 AND pb.idEstadoPlan <> 6 ";
if($cadenaPl!=''){
                            
    $query .= " AND ".$cadenaPl." ";
}
$query .= " AND fa.eecc = 'LARI')  as creadosMatFoLARI,

(SELECT count(fa.mat_fo_est) FROM web_unificada_fa fa, planobra pb 
WHERE fa.subProyectoDesc = wuf.subProyectoDesc
AND fa.itemPlan = pb.itemPlan
 AND pb.idEstadoPlan <> 6 ";
if($cadenaPl!=''){
                            
    $query .= " AND ".$cadenaPl." ";
}
$query .= " AND fa.eecc = 'LARI'
AND fa.mat_fo_est  REGEXP '02|05|06|08')  as aproMatFoLARI,

/***DOMINION****/
(SELECT count(fa.mat_fo_est) FROM web_unificada_fa fa, planobra pb
WHERE fa.subProyectoDesc = wuf.subProyectoDesc
AND fa.itemPlan = pb.itemPlan
 AND pb.idEstadoPlan <> 6 ";
if($cadenaPl!=''){
                            
    $query .= " AND ".$cadenaPl." ";
}
$query .= " AND fa.eecc = 'DOMINION')  as creadosMatFoDOMINION,

(SELECT count(fa.mat_fo_est) FROM web_unificada_fa fa, planobra pb 
WHERE fa.subProyectoDesc = wuf.subProyectoDesc
AND fa.itemPlan = pb.itemPlan
 AND pb.idEstadoPlan <> 6 ";
if($cadenaPl!=''){
                            
    $query .= " AND ".$cadenaPl." ";
}
$query .= " AND fa.eecc = 'DOMINION'
AND fa.mat_fo_est  REGEXP '02|05|06|08')  as aproMatFoDOMINION,

/***EZENTIS****/
(SELECT count(fa.mat_fo_est) FROM web_unificada_fa fa, planobra pb
WHERE fa.subProyectoDesc = wuf.subProyectoDesc
AND fa.itemPlan = pb.itemPlan
 AND pb.idEstadoPlan <> 6 ";
if($cadenaPl!=''){
                            
    $query .= " AND ".$cadenaPl." ";
}
$query .= " AND fa.eecc = 'EZENTIS')  as creadosMatFoEZENTIS,

(SELECT count(fa.mat_fo_est) FROM web_unificada_fa fa, planobra pb 
WHERE fa.subProyectoDesc = wuf.subProyectoDesc
AND fa.itemPlan = pb.itemPlan
 AND pb.idEstadoPlan <> 6 ";
if($cadenaPl!=''){
                            
    $query .= " AND ".$cadenaPl." ";
}
$query .= " AND fa.eecc = 'EZENTIS'
AND fa.mat_fo_est  REGEXP '02|05|06|08')  as aproMatFoEZENTIS,
/******************************MAT_FUENTE********************************/
/***COBRA****/
(SELECT count(fa.mat_fuente_est) FROM web_unificada_fa fa, planobra pb 
WHERE fa.subProyectoDesc = wuf.subProyectoDesc
AND fa.itemPlan = pb.itemPlan
 AND pb.idEstadoPlan <> 6 ";
if($cadenaPl!=''){
                            
    $query .= " AND ".$cadenaPl." ";
}
$query .= " AND fa.eecc = 'COBRA')  as creadosMatFuenteCOBRA,

(SELECT count(fa.mat_fuente_est) FROM web_unificada_fa fa, planobra pb  
WHERE fa.subProyectoDesc = wuf.subProyectoDesc
AND fa.itemPlan = pb.itemPlan
 AND pb.idEstadoPlan <> 6 ";
if($cadenaPl!=''){
                            
    $query .= " AND ".$cadenaPl." ";
}
$query .= " AND fa.eecc = 'COBRA'
AND fa.mat_fuente_est  REGEXP '02|05|06|08')  as aproMatFuenteCOBRA,


/***LARI****/
(SELECT count(fa.mat_fuente_est) FROM web_unificada_fa fa, planobra pb
WHERE fa.subProyectoDesc = wuf.subProyectoDesc
AND fa.itemPlan = pb.itemPlan
 AND pb.idEstadoPlan <> 6 ";
if($cadenaPl!=''){
                            
    $query .= " AND ".$cadenaPl." ";
}
$query .= " AND fa.eecc = 'LARI')  as creadosMatFuenteLARI,

(SELECT count(fa.mat_fuente_est) FROM web_unificada_fa fa, planobra pb 
WHERE fa.subProyectoDesc = wuf.subProyectoDesc
AND fa.itemPlan = pb.itemPlan
 AND pb.idEstadoPlan <> 6 ";
if($cadenaPl!=''){
                            
    $query .= " AND ".$cadenaPl." ";
}
$query .= " AND fa.eecc = 'LARI'
AND fa.mat_fuente_est  REGEXP '02|05|06|08')  as aproMatFuenteLARI,

/***DOMINION****/
(SELECT count(fa.mat_fuente_est) FROM web_unificada_fa fa, planobra pb
WHERE fa.subProyectoDesc = wuf.subProyectoDesc
AND fa.itemPlan = pb.itemPlan
 AND pb.idEstadoPlan <> 6 ";
if($cadenaPl!=''){
                            
    $query .= " AND ".$cadenaPl." ";
}
$query .= " AND fa.eecc = 'DOMINION')  as creadosMatFuenteDOMINION,

(SELECT count(fa.mat_fuente_est) FROM web_unificada_fa fa, planobra pb 
WHERE fa.subProyectoDesc = wuf.subProyectoDesc
AND fa.itemPlan = pb.itemPlan
 AND pb.idEstadoPlan <> 6 ";
if($cadenaPl!=''){
                            
    $query .= " AND ".$cadenaPl." ";
}
$query .= " AND fa.eecc = 'DOMINION'
AND fa.mat_fuente_est  REGEXP '02|05|06|08')  as aproMatFuenteDOMINION,

/***EZENTIS****/
(SELECT count(fa.mat_fuente_est) FROM web_unificada_fa fa, planobra pb
WHERE fa.subProyectoDesc = wuf.subProyectoDesc
AND fa.itemPlan = pb.itemPlan
 AND pb.idEstadoPlan <> 6 ";
if($cadenaPl!=''){
                            
    $query .= " AND ".$cadenaPl." ";
}
$query .= " AND fa.eecc = 'EZENTIS')  as creadosMatFuenteEZENTIS,

(SELECT count(fa.mat_fuente_est) FROM web_unificada_fa fa, planobra pb 
WHERE fa.subProyectoDesc = wuf.subProyectoDesc
AND fa.itemPlan = pb.itemPlan
 AND pb.idEstadoPlan <> 6 ";
if($cadenaPl!=''){
                            
    $query .= " AND ".$cadenaPl." ";
}
$query .= " AND fa.eecc = 'EZENTIS'
AND fa.mat_fuente_est  REGEXP '02|05|06|08')  as aproMatFuenteEZENTIS,
/*******************************MAT_FO_OC*********************************/

/***COBRA****/
(SELECT count(fa.mat_fo_oc_est) FROM web_unificada_fa fa, planobra pb
WHERE fa.subProyectoDesc = wuf.subProyectoDesc
AND fa.itemPlan = pb.itemPlan
 AND pb.idEstadoPlan <> 6 ";
if($cadenaPl!=''){
                            
    $query .= " AND ".$cadenaPl." ";
}
$query .= " AND fa.eecc = 'COBRA')  as creadosMatFoOcCOBRA,

(SELECT count(fa.mat_fo_oc_est) FROM web_unificada_fa fa, planobra pb 
WHERE fa.subProyectoDesc = wuf.subProyectoDesc
AND fa.itemPlan = pb.itemPlan
 AND pb.idEstadoPlan <> 6 ";
if($cadenaPl!=''){
                            
    $query .= " AND ".$cadenaPl." ";
}
$query .= " AND fa.eecc = 'COBRA'
AND fa.mat_fo_oc_est  REGEXP '02|05|06|08')  as aproMatFoOcCOBRA,


/***LARI****/
(SELECT count(fa.mat_fo_oc_est) FROM web_unificada_fa fa, planobra pb
WHERE fa.subProyectoDesc = wuf.subProyectoDesc
AND fa.itemPlan = pb.itemPlan
 AND pb.idEstadoPlan <> 6 ";
if($cadenaPl!=''){
                            
    $query .= " AND ".$cadenaPl." ";
}
$query .= " AND fa.eecc = 'LARI')  as creadosMatFoOcLARI,

(SELECT count(fa.mat_fo_oc_est) FROM web_unificada_fa fa, planobra pb 
WHERE fa.subProyectoDesc = wuf.subProyectoDesc
AND fa.itemPlan = pb.itemPlan
 AND pb.idEstadoPlan <> 6 ";
if($cadenaPl!=''){
                            
    $query .= " AND ".$cadenaPl." ";
}
$query .= " AND fa.eecc = 'LARI'
AND fa.mat_fo_oc_est  REGEXP '02|05|06|08')  as aproMatFoOcLARI,

/***DOMINION****/
(SELECT count(fa.mat_fo_oc_est) FROM web_unificada_fa fa, planobra pb
WHERE fa.subProyectoDesc = wuf.subProyectoDesc
AND fa.itemPlan = pb.itemPlan
 AND pb.idEstadoPlan <> 6 ";
if($cadenaPl!=''){
                            
    $query .= " AND ".$cadenaPl." ";
}
$query .= " AND fa.eecc = 'DOMINION')  as creadosMatFoOcDOMINION,

(SELECT count(fa.mat_fo_oc_est) FROM web_unificada_fa fa, planobra pb 
WHERE fa.subProyectoDesc = wuf.subProyectoDesc
AND fa.itemPlan = pb.itemPlan
 AND pb.idEstadoPlan <> 6 ";
if($cadenaPl!=''){
                            
    $query .= " AND ".$cadenaPl." ";
}
$query .= " AND fa.eecc = 'DOMINION'
AND fa.mat_fo_oc_est  REGEXP '02|05|06|08')  as aproMatFoOcDOMINION,

/***EZENTIS****/
(SELECT count(fa.mat_fo_oc_est) FROM web_unificada_fa fa, planobra pb
WHERE fa.subProyectoDesc = wuf.subProyectoDesc
AND fa.itemPlan = pb.itemPlan
 AND pb.idEstadoPlan <> 6 ";
if($cadenaPl!=''){
                            
    $query .= " AND ".$cadenaPl." ";
}
$query .= " AND fa.eecc = 'EZENTIS')  as creadosMatFoOcEZENTIS,

(SELECT count(fa.mat_fo_oc_est) FROM web_unificada_fa fa, planobra pb 
WHERE fa.subProyectoDesc = wuf.subProyectoDesc
AND fa.itemPlan = pb.itemPlan
 AND pb.idEstadoPlan <> 6 ";
if($cadenaPl!=''){
                            
    $query .= " AND ".$cadenaPl." ";
}
$query .= " AND fa.eecc = 'EZENTIS'
AND fa.mat_fo_oc_est  REGEXP '02|05|06|08')  as aproMatFoOcEZENTIS,
/******************************MAT_COAX_OC*********************************/

/***COBRA****/
(SELECT count(fa.mat_coax_oc_est) FROM web_unificada_fa fa, planobra pb
WHERE fa.subProyectoDesc = wuf.subProyectoDesc
AND fa.itemPlan = pb.itemPlan
 AND pb.idEstadoPlan <> 6 ";
if($cadenaPl!=''){
                            
    $query .= " AND ".$cadenaPl." ";
}
$query .= " AND fa.eecc = 'COBRA')  as creadosMatCoaxOcCOBRA,

(SELECT count(fa.mat_coax_oc_est) FROM web_unificada_fa fa, planobra pb 
WHERE fa.subProyectoDesc = wuf.subProyectoDesc
AND fa.itemPlan = pb.itemPlan
 AND pb.idEstadoPlan <> 6 ";
if($cadenaPl!=''){
                            
    $query .= " AND ".$cadenaPl." ";
}
$query .= " AND fa.eecc = 'COBRA'
AND fa.mat_coax_oc_est  REGEXP '02|05|06|08')  as aproMatCoaxOcCOBRA,


/***LARI****/
(SELECT count(fa.mat_coax_oc_est) FROM web_unificada_fa fa, planobra pb
WHERE fa.subProyectoDesc = wuf.subProyectoDesc
AND fa.itemPlan = pb.itemPlan
 AND pb.idEstadoPlan <> 6 ";
if($cadenaPl!=''){
                            
    $query .= " AND ".$cadenaPl." ";
}
$query .= " AND fa.eecc = 'LARI')  as creadosMatCoaxOcLARI,

(SELECT count(fa.mat_coax_oc_est) FROM web_unificada_fa fa, planobra pb 
WHERE fa.subProyectoDesc = wuf.subProyectoDesc
AND fa.itemPlan = pb.itemPlan
 AND pb.idEstadoPlan <> 6 ";
if($cadenaPl!=''){
                            
    $query .= " AND ".$cadenaPl." ";
}
$query .= " AND fa.eecc = 'LARI'
AND fa.mat_coax_oc_est  REGEXP '02|05|06|08')  as aproMatCoaxOcLARI,

/***DOMINION****/
(SELECT count(fa.mat_coax_oc_est) FROM web_unificada_fa fa, planobra pb
WHERE fa.subProyectoDesc = wuf.subProyectoDesc
AND fa.itemPlan = pb.itemPlan
 AND pb.idEstadoPlan <> 6 ";
if($cadenaPl!=''){
                            
    $query .= " AND ".$cadenaPl." ";
}
$query .= " AND fa.eecc = 'DOMINION')  as creadosMatCoaxOcDOMINION,

(SELECT count(fa.mat_coax_oc_est) FROM web_unificada_fa fa, planobra pb 
WHERE fa.subProyectoDesc = wuf.subProyectoDesc
AND fa.itemPlan = pb.itemPlan
 AND pb.idEstadoPlan <> 6 ";
if($cadenaPl!=''){
                            
    $query .= " AND ".$cadenaPl." ";
}
$query .= " AND fa.eecc = 'DOMINION'
AND fa.mat_fo_oc_est  REGEXP '02|05|06|08')  as aproMatCoaxOcDOMINION,

/***EZENTIS****/
(SELECT count(fa.mat_coax_oc_est) FROM web_unificada_fa fa, planobra pb
WHERE fa.subProyectoDesc = wuf.subProyectoDesc
AND fa.itemPlan = pb.itemPlan
 AND pb.idEstadoPlan <> 6 ";
if($cadenaPl!=''){
                            
    $query .= " AND ".$cadenaPl." ";
}
$query .= " AND fa.eecc = 'EZENTIS')  as creadosMatCoaxOcEZENTIS,

(SELECT count(fa.mat_coax_oc_est) FROM web_unificada_fa fa, planobra pb 
WHERE fa.subProyectoDesc = wuf.subProyectoDesc
AND fa.itemPlan = pb.itemPlan
 AND pb.idEstadoPlan <> 6 ";
if($cadenaPl!=''){
                            
    $query .= " AND ".$cadenaPl." ";
}
$query .= " AND fa.eecc = 'EZENTIS'
AND fa.mat_coax_oc_est  REGEXP '02|05|06|08')  as aproMatCoaxOcEZENTIS,
/*********************************MAT_ENER**********************************/

/***COBRA****/
(SELECT count(fa.mat_ener_est) FROM web_unificada_fa fa, planobra pb
WHERE fa.subProyectoDesc = wuf.subProyectoDesc
AND fa.itemPlan = pb.itemPlan
 AND pb.idEstadoPlan <> 6 ";
if($cadenaPl!=''){
                            
    $query .= " AND ".$cadenaPl." ";
}
$query .= " AND fa.eecc = 'COBRA')  as creadosMatEnerCOBRA,

(SELECT count(fa.mat_ener_est) FROM web_unificada_fa fa, planobra pb 
WHERE fa.subProyectoDesc = wuf.subProyectoDesc
AND fa.itemPlan = pb.itemPlan
 AND pb.idEstadoPlan <> 6 ";
if($cadenaPl!=''){
                            
    $query .= " AND ".$cadenaPl." ";
}
$query .= " AND fa.eecc = 'COBRA'
AND fa.mat_ener_est  REGEXP '02|05|06|08')  as aproMatEnerCOBRA,


/***LARI****/
(SELECT count(fa.mat_ener_est) FROM web_unificada_fa fa, planobra pb
WHERE fa.subProyectoDesc = wuf.subProyectoDesc
AND fa.itemPlan = pb.itemPlan
 AND pb.idEstadoPlan <> 6 ";
if($cadenaPl!=''){
                            
    $query .= " AND ".$cadenaPl." ";
}
$query .= " AND fa.eecc = 'LARI')  as creadosMatEnerLARI,

(SELECT count(fa.mat_ener_est) FROM web_unificada_fa fa, planobra pb 
WHERE fa.subProyectoDesc = wuf.subProyectoDesc
AND fa.itemPlan = pb.itemPlan
 AND pb.idEstadoPlan <> 6 ";
if($cadenaPl!=''){
                            
    $query .= " AND ".$cadenaPl." ";
}
$query .= " AND fa.eecc = 'LARI'
AND fa.mat_ener_est  REGEXP '02|05|06|08')  as aproMatEnerLARI,

/***DOMINION****/
(SELECT count(fa.mat_ener_est) FROM web_unificada_fa fa, planobra pb
WHERE fa.subProyectoDesc = wuf.subProyectoDesc
AND fa.itemPlan = pb.itemPlan
 AND pb.idEstadoPlan <> 6 ";
if($cadenaPl!=''){
                            
    $query .= " AND ".$cadenaPl." ";
}
$query .= " AND fa.eecc = 'DOMINION')  as creadosMatEnerDOMINION,

(SELECT count(fa.mat_ener_est) FROM web_unificada_fa fa, planobra pb 
WHERE fa.subProyectoDesc = wuf.subProyectoDesc
AND fa.itemPlan = pb.itemPlan
 AND pb.idEstadoPlan <> 6 ";
if($cadenaPl!=''){
                            
    $query .= " AND ".$cadenaPl." ";
}
$query .= " AND fa.eecc = 'DOMINION'
AND fa.mat_ener_est  REGEXP '02|05|06|08')  as aproMatEnerDOMINION,

/***EZENTIS****/
(SELECT count(fa.mat_ener_est) FROM web_unificada_fa fa, planobra pb
WHERE fa.subProyectoDesc = wuf.subProyectoDesc
AND fa.itemPlan = pb.itemPlan
 AND pb.idEstadoPlan <> 6 ";
if($cadenaPl!=''){
                            
    $query .= " AND ".$cadenaPl." ";
}
$query .= " AND fa.eecc = 'EZENTIS')  as creadosMatEnerEZENTIS,

(SELECT count(fa.mat_ener_est) FROM web_unificada_fa fa, planobra pb 
WHERE fa.subProyectoDesc = wuf.subProyectoDesc
AND fa.itemPlan = pb.itemPlan
 AND pb.idEstadoPlan <> 6 ";
if($cadenaPl!=''){
                            
    $query .= " AND ".$cadenaPl." ";
}
$query .= " AND fa.eecc = 'EZENTIS'
AND fa.mat_ener_est  REGEXP '02|05|06|08')  as aproMatEnerEZENTIS

/***********************************FROM************************************/
FROM web_unificada_fa wuf, subproyecto sp, proyecto p, planobra pa 

WHERE wuf.subProyectoDesc = sp.subProyectoDesc 
AND p.idProyecto = sp.idProyecto 
AND wuf.itemPlan = pa.itemPlan ";

if($idProyecto!=''){
    $query .= " AND sp.idProyecto = ".$idProyecto;
}

if($idSubProyecto!=''){
                            
    $query .= " AND sp.idSubProyecto IN (".$idSubProyecto.")";
}

if($cadenaPa!=''){
                            
    $query .= " AND ".$cadenaPa." ";
}

$query .= " GROUP BY p.proyectoDesc, wuf.subProyectoDesc
            ORDER BY p.proyectoDesc, wuf.subProyectoDesc ";
        $result = $this->db->query($query,array());    
        return $result;
    }
	/*wuf.mesEjecucion = 'ENE'
AND*/ 
    
    /*function   getSeguimientoPDO1($mesEjecucion,$hasFiltroFec,$fechaInicio,$fechaFin,$idProyecto,$idSubProyecto,$zonal){
         
        
        
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
	}	*/

        function getDetalle(){
        $query = " SELECT * 
                    FROM web_unificada_fa fa, planobra pl, subproyecto sp, proyecto p
                    WHERE fa.itemPlan = pl.itemPlan
                    AND pl.idSubProyecto = sp.idSubProyecto
                    AND p.idProyecto = sp.idProyecto ";

        $result = $this->db->query($query,array());    
        return $result;

    }

	
}