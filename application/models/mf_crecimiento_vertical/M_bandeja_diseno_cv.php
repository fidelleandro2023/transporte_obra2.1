<?php
class M_bandeja_diseno_cv extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}

    function getBandejaDisenoCv($idJefatura, $idEmpresaColab) {
        $sql = "SELECT po.itemplan,
                       e.estadoPlanDesc,
                       po.nombreProyecto,
                       s.subProyectoDesc,
                       ce.jefatura,
                       em.empresaColabDesc,
                       CASE WHEN DATE(fecha_creacion) > '2020-03-15' THEN CASE WHEN po.orden_compra IS NOT NULL THEN 1
						                                                        ELSE 0 END
						     ELSE 1 END as flg_orden_compra
                  FROM planobra po,
                       subproyecto s,
                       estadoplan e,
                       central ce,
                       empresacolab em
                 WHERE po.idSubProyecto = s.idSubProyecto
                   AND po.idCentral     = ce.idCentral
                   AND s.idTipoSubProyecto = 2 -- INTEGRAL
                   AND e.idEstadoPlan = 2
                   AND e.idEstadoPlan    = po.idEstadoPlan
                   AND ce.idEmpresaColabCV = em.idEmpresaColab
                   AND ce.idJefatura     = COALESCE(?, ce.idJefatura)
                   AND ce.idEmpresaColabCV = COALESCE(?, ce.idEmpresaColabCV)";
        $result = $this->db->query($sql, array($idJefatura, $idEmpresaColab));
        return $result->result_array();           
    }

    function updateData($arrayData, $itemplan, $arrayLog) {
        $this->db->trans_begin();
        
        $this->db->where('itemplan', $itemplan);
        $this->db->update('planobra', $arrayData);

        if($this->db->affected_rows() != 1) {
            $this->db->trans_rollback();
            return array('error' => EXIT_ERROR, 'msj' => 'error, no se ingreso la ubicaci&oacute;n.');
        } else {
            $this->db->insert('log_planobra', $arrayLog);
            if($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                return array('error' => EXIT_ERROR, 'msj' => 'error, no se ingreso log.');
            } else {
                $this->db->trans_commit();
                return array('error' => EXIT_SUCCESS);
            }
        }
    }
}