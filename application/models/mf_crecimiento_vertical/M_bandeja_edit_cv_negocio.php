<?php
class M_bandeja_edit_cv_negocio extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
	function   getAllCVPreRegistro($itemplan, $nomProyecto, $estado_plan, $distrito){
	    $Query = "  SELECT 	cv.itemplan, ep.estadoPlanDesc,
                        	cv.nombre_proyecto,
                        	sp.subProyectoDesc,
                        	cv.nombre_constructora,
	                        cv.estado_aprob,
	                        po.idEstadoPlan,
	                        f.faseDesc AS fase_desc,
	                        c.distrito
                	FROM   planobra po,
						   planobra_detalle_cv cv,
                	       subproyecto sp,
                           estadoplan ep,
                           fase f,
	                       central c
                	WHERE  po.itemplan = cv.itemplan
                    AND	   cv.idSubProyecto = sp.idSubProyecto
                    AND	   po.idEstadoPlan = ep.idEstadoPlan
                    AND    po.idFase = f.idFase
	                AND    po.idCentral = c.idCentral
	                AND    po.idSubProyecto in (".ID_SUBPROYECTO_CV_NEGOCIO_2_BUCLE.")";
	    if($itemplan!=null){
	       $Query .= " AND po.itemplan = '".$itemplan."'";
	    }
	    if($nomProyecto!=null){
	        $Query .= " AND cv.nombre_proyecto LIKE '%".$nomProyecto."%'";
	    }
	    if($estado_plan!=null){
	        $Query .= " AND po.idEstadoPlan = ".$estado_plan;
	    }
	    if($distrito!=null){
	        $Query .= " AND c.distrito = '".$distrito."'";
	    }
	    $result = $this->db->query($Query,array());
	    return $result;
	}

	function getEstadosItemplan(){
	    $Query = "  SELECT idEstadoPlan, UPPER(estadoPlanDesc) AS  estadoPlanDesc
					FROM estadoplan		
	                WHERE idEstadoPlan IN (".ESTADO_PLAN_PRE_REGISTRO.",".ESTADO_PLAN_PRE_DISENO.",".ESTADO_PLAN_DISENO.",".ESTADO_PLAN_EN_OBRA.",".ID_ESTADO_PRE_LIQUIDADO.",".ESTADO_PLAN_DISENO_EJECUTADO.",".ID_ESTADO_DISENIO_PARCIAL.",".ID_ESTADO_TERMINADO.",".ID_ESTADO_CANCELADO.")		
					ORDER BY idEstadoPlan";
	    $result = $this->db->query($Query,array());
	    return $result;
	}	
}