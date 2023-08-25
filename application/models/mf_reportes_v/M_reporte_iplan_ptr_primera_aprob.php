<?php
class M_reporte_iplan_ptr_primera_aprob extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
    function   getSeguimientoItemplanPTRFAprob($proyecto, $subProyecto, $mes,$anio){            
        $varianteA="-";
        $varianteB="-";
        $varianteC="-";

        $datoA="";
        $datoB="";
        $datoC="";

        $query = "SELECT poP.itemPlan as itemplan,
                    Proy.proyectoDesc as proyecto,
                    subProy.subProyectoDesc as subproyecto,
                    poP.fechaPrevEjec as fechaPrevEjec, 
                    poP.fechaEjecucion as fechaEjecucion, 
                    poP.fechaCancelacion as fechaCancelacion, 
                    tabA.ptrA as PTR_MAT_FO, 
                    tabA.estadoA as PTR_ESTADO_MAT_FO, 
                    tabA.fechaInicioObraA as PTR_FECAPROB_MAT_FO,
                    tabA.areaDescA as PTR_AREA_MAT_FO,
                    tabB.ptrB as PTR_MAT_COAX, 
                    tabB.estadoB as PTR_ESTADO_MAT_COAX, 
                    tabB.fechaInicioObraB AS PTR_FECAPROB_MAT_COAX, 
                    tabB.areaDescB AS PTR_AREA_MAT_COAX
                    FROM planobra poP   inner join    (select po1.itemPlan, po1.idSubProyecto,
                                                    TabA.ptrA, TabA.estadoA, TabA.fechaInicioObraA, TabA.areaDescA 
                                                    FROM planobra po1 
                                                    left join (SELECT
                                                                dp.itemplan AS itemA,
                                                                wu.ptr AS ptrA,
                                                                wu.est_innova AS estadoA,
                                                                min(str_to_date(wu.f_aprob,'%d/%m/%Y')) AS fechaInicioObraA,
                                                                A.areaDesc AS areaDescA 
                                                                FROM
                                                                    area A,
                                                                    estacionarea EA,
                                                                    subproyectoestacion subest,
                                                                    detalleplan dp 
                                                                LEFT JOIN
                                                                    web_unificada wu 
                                                                        ON wu.ptr = dp.poCod 
                                                                WHERE
                                                                    dp.idSubProyectoEstacion = subest.idSubProyectoEstacion 
                                                                    AND subest.idEstacionArea = EA.idEstacionArea 
                                                                    AND EA.idArea = A.idArea 
                                                                    AND A.idArea = 9 
                                                                    AND (
                                                                        wu.f_aprob IS NOT NULL 
                                                                        AND wu.f_aprob != '0000-00-00' 
                                                                        AND wu.f_aprob != ''
                                                                    ) 
                                                                GROUP BY
                                                                    dp.itemplan 
                                                                ORDER BY
                                                                    NULL) AS TabA 
                                                    ON po1.itemplan = TabA.itemA) as tabA
                                        on  poP.itemPlan= tabA.itemPlan

                                        inner join   (select po2.itemPlan, po2.idSubProyecto,
                                                            TabB.ptrB, TabB.estadoB, TabB.fechaInicioObraB, TabB.areaDescB 
                                                            FROM planobra po2 
                                                            LEFT join
                                                                (SELECT
                                                                        dp.itemplan AS itemB,
                                                                        wu.ptr AS ptrB,
                                                                        wu.est_innova AS estadoB,
                                                                        min(str_to_date(wu.f_aprob,'%d/%m/%Y')) AS fechaInicioObraB,
                                                                        A.areaDesc AS areaDescB 
                                                                    FROM
                                                                        area A,
                                                                        estacionarea EA,
                                                                        subproyectoestacion subest,
                                                                        detalleplan dp 
                                                                    LEFT JOIN
                                                                        web_unificada wu 
                                                                            ON wu.ptr = dp.poCod 
                                                                    WHERE
                                                                        dp.idSubProyectoEstacion = subest.idSubProyectoEstacion 
                                                                        AND subest.idEstacionArea = EA.idEstacionArea 
                                                                        AND EA.idArea = A.idArea 
                                                                        AND A.idArea = 3 
                                                                        AND (
                                                                            wu.f_aprob IS NOT NULL 
                                                                            AND wu.f_aprob != '0000-00-00' 
                                                                            AND wu.f_aprob != ''
                                                                        ) 
                                                                    GROUP BY
                                                                        dp.itemplan 
                                                                    ORDER BY
                                                                        NULL) AS TabB 
                                                            ON po2.itemplan = TabB.itemB) AS tabB
                                        on poP.itemPlan= tabB.itemPlan
                                        inner join subproyecto subProy on subProy.idSubProyecto=poP.idSubProyecto
                                        inner join proyecto Proy on Proy.idProyecto=(select idProyecto 
                                                                                        from subproyecto 
                                                                                        where idSubProyecto=poP.idSubProyecto)";
        if($proyecto!=''){
            $datoA = " Proy.idProyecto = ".$proyecto;
             $varianteA="A";
        }
        if($subProyecto!=''){
            $subPro='';
            $subProyecto = explode(",",$subProyecto);
            foreach ($subProyecto as $row){
                $subPro .= "'".$row."',";
            }
            $subPro = substr($subPro,0,-1);
            $datoB = "   subProy.subProyectoDesc IN (".$subPro.")";
            $varianteB="A";

        }

        if($anio!=''){
            if($mes!=''){
                $datoC = "  MONTH(poP.fechaPrevEjec) IN (".$mes.") AND YEAR(poP.fechaPrevEjec) IN (".$anio.") ";
                $varianteC="A";
            }else{
                 $datoC = "  YEAR(poP.fechaPrevEjec) IN (".$anio.")  ";
                $varianteC="A";
            }

           
        }else{
            if($mes!=''){
                $datoC = "  MONTH(poP.fechaPrevEjec) IN (".$mes.") ";
                $varianteC="A";
            }
            
        }

        
        $sumaVariante=$varianteA.$varianteB.$varianteC;
       
       if($sumaVariante=="AAA") {
            $query .=" WHERE ".$datoA." AND ".$datoB." AND ".$datoC;
       }

       if($sumaVariante=="AA-") {
            $query .=" WHERE ".$datoA." AND ".$datoB;
       }

       if($sumaVariante=="-AA") {
             $query .=" WHERE ".$datoB." AND ".$datoC;
        }

         if($sumaVariante=="A-A") {
             $query .=" WHERE ".$datoA." AND ".$datoC;
        }

       if($sumaVariante=="A--") {
            $query .=" WHERE ".$datoA;
       }

        if($sumaVariante=="-A-") {
             $query .=" WHERE ".$datoB;
        }

        if($sumaVariante=="--A") {
             $query .=" WHERE ".$datoC;
        }

        $query .= "  GROUP BY poP.itemPlan ORDER BY poP.itemPlan LIMIT 200";

	    $result = $this->db->query($query,array());	   
	    return $result;
	}	
    
	
		
	
}