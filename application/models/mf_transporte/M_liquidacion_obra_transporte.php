<?php
class M_liquidacion_obra_transporte extends CI_Model{
	
	function __construct(){
		parent::__construct();
		
	}
	
	function getDataPlanobraLiqui($itemPlan, $arrayIdEstadoPlan, $idSubProyecto = NULL){
	    $Query = "SELECT po.itemplan,
                         UPPER(e.estadoPlanDesc) estadoPlanDesc,
                         p.proyectoDesc,
                         s.subProyectoDesc,
                         em.empresaColabDesc,
                         f.faseDesc,
                         c.tipoCentralDesc,
                         po.fechaPreLiquidacion,
                         po.fechaEjecucion,
                         po.fechaInicio,
                         z.zonalDesc,
                         po.fechaPrevEjec,
                         po.idEstadoPlan,
                         po.idSubProyecto,
                         po.indicador
                    FROM planobra po,
                         subproyecto s,
                         estadoplan e,
                         proyecto p,
                         empresacolab em,
                         fase f,
                         pqt_central c,
                         zonal z
                   WHERE po.itemplan      = COALESCE(?, po.itemplan)
                     AND po.idSubProyecto = COALESCE(?, po.idSubProyecto)
                     AND po.idEstadoPlan IN (" . implode(',', $arrayIdEstadoPlan) . ")
                     AND f.idFase = po.idFase
                     AND po.idSubProyecto = s.idSubProyecto
                     AND e.idEstadoPlan   = po.idEstadoPlan
                     AND p.idProyecto     = s.idProyecto
                     AND po.idCentralPqt  = c.idCentral
                     AND po.idZonal       = z.idZonal 
                     AND po.idEmpresaColab = em.idEmpresaColab";
	    $result = $this->db->query($Query, array($itemPlan, $idSubProyecto));
	    return $result->result_array();
	}
}