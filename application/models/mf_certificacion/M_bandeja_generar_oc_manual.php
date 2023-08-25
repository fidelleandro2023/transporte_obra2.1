<?php

class m_bandeja_generar_oc_manual extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getDataBandeja()
    {
       $sql ="			     
				 SELECT po.itemplan,
						po.fecha_creacion,
						e.estadoPlanDesc,
						s.subProyectoDesc,
						em.empresaColabDesc,
						soc.pep1
				   FROM planobra po, 
				       itemplan_x_solicitud_oc ixso, 
				       solicitud_orden_compra soc,
				       estado_solicitud_orden_compra esoc,
					   subproyecto s,
					   empresacolab em,
					   estadoplan e
				 WHERE po.itemPlan = ixso.itemplan 
				   AND soc.codigo_solicitud = ixso.codigo_solicitud_oc
				   AND po.idEstadoPlan NOT IN (6,23,5,10,18)
				   AND esoc.id = soc.estado
				   AND soc.tipo_solicitud = 1
				   AND soc.estado = 2
				   AND s.idSubProyecto = po.idSubProyecto
				   AND po.idEmpresaColab = em.idEmpresaColab
				   AND po.idEstadoPlan = e.idEstadoPlan
				  GROUP BY po.itemplan";
		$result = $this->db->query($sql);
		return $result->result_array();
    }
}
 