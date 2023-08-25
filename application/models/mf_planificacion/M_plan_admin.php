<?php
class M_plan_admin extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
    }

    function getObrasByPlan($id_plan) {
        $sql = " SELECT s.nombre_plan,
                        po.itemplan,
                        s.cantidad,
                        s.idSubProyecto,
                        e.empresaColabDesc,
                        su.subProyectoDesc,
                        po.solicitud_oc,
                        po.orden_compra,
                        (SELECT CASE WHEN cv.estado_aprob = 1 THEN 'SI'
                                     ELSE 'NO' END 
                           FROM planobra_detalle_cv cv
                          WHERE cv.itemplan = po.itemplan) situacion
                   FROM subproyecto_fases_cant_item_planificacion s,
                        planobra po,
                        empresacolab e,
                        subproyecto su
                  WHERE s.id_plan = po.id_plan
                    AND su.idSubProyecto = po.idSubProyecto
                    AND po.idSubProyecto = s.idSubProyecto
                    AND e.idEmpresaColab = po.idEmpresaColab
                    AND s.id_plan = ?
                    GROUP BY po.id_plan, po.itemplan";
        $result = $this->db->query($sql, array($id_plan));
        return $result->result_array();          
    }

    function getPlanobraPlaniAll($itemplan, $idSubProyecto, $idEmpresaColab) {
		$sql = "SELECT po.itemplan,
                       po.fecha_creacion,
                       s.idSubProyecto,
                       s.subProyectoDesc,
                       e.empresaColabDesc
		          FROM planobra po, 
					   subproyecto s,
                       empresacolab e
                 WHERE po.idSubProyecto = s.idSubProyecto
                   AND e.idEmpresaColab = po.idEmpresaColab
                   AND po.itemplan      = COALESCE(?, po.itemplan)
                   AND po.idSubPRoyecto = COALESCE(?, po.idSubProyecto)
                   AND e.idEmpresaColab = COALESCE(?, po.idEmpresaColab)
                   AND po.id_plan IS NULL";
		$result = $this->db->query($sql, array($itemplan, $idSubProyecto, $idEmpresaColab));				
		return $result->result_array();
    }

    function getCountPlan($id_plan, $countAsigItem) {
		$sql = "SELECT t.nombre_plan, 
						CASE WHEN t.cantidad < t.countPo + ".$countAsigItem." THEN 1 
							ELSE 0 END flg_top_cantidad,
						t.cantidad,
						t.countPo
				  FROM (
						SELECT s.id_plan,
								s.idSubProyecto,
								s.idFase,
								s.nombre_plan,
								s.cantidad,
								COUNT(1) countPo
						   FROM planobra po,
								subproyecto_fases_cant_item_planificacion s
						  WHERE po.id_plan       = s.id_plan
							AND po.id_plan       = ".$id_plan.")t";
        $result = $this->db->query($sql);
		return $result->row_array();
	}

    function updatePlanItemplan($arrayData, $arrayLog) {
        $this->db->trans_begin();
        $this->db->update_batch('planobra', $arrayData, 'itemplan');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $data['error'] = EXIT_ERROR;
            $data['msj']   = 'No se asigno los itemplans.';
        } else {
            $this->db->insert_batch('log_planificacion_asig', $arrayLog);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $data['error'] = EXIT_ERROR;
                $data['msj']   = 'No se asigno los itemplans.';
            } else {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
            }
        }
        return $data;
    }

    function insertPlanifica($arrayPlan) {
		$this->db->insert('subproyecto_fases_cant_item_planificacion', $arrayPlan);
		if($this->db->affected_rows() != 1) {
			$data['error'] = EXIT_ERROR;
			$data['msj'] = 'Error al ingresar la planificacion';
		} else {
			$data['error'] = EXIT_SUCCESS;
		}
		return $data;
    }
    
    function getFlgTopePlanCuota($idSubProyecto, $idFase, $cantPlan) {
        $sql = "  SELECT CASE WHEN cantCuotas < cantPlan + ".$cantPlan." THEN 1 
                              ELSE 0 END flg_tope_plan
                    FROM (
                            SELECT si.cantItemPlan as cantCuotas,
                                COALESCE(SUM(cantidad), 0) cantPlan  
                            FROM subproyecto_fases_cant_item_planificacion su,
                                subproyecto_fases_cant_itemplan si,
                                fase f
                            WHERE su.idSubProyecto = si.idSubProyecto
                            AND f.faseDesc = si.fase
                            AND su.idFase = f.idFase
                            AND su.idSubProyecto = ?
                            AND f.idFase         = ?
                        )t";
        $result = $this->db->query($sql, array($idSubProyecto, $idFase));
        return $result->row_array()['flg_tope_plan'];
    }
	
	function update_plan($id_plan, $arrayData) {
        $this->db->where('id_plan', $id_plan);
        $this->db->update('subproyecto_fases_cant_item_planificacion', $arrayData);

        if($this->db->affected_rows() == 0) {
            $data['error'] = EXIT_ERROR;
            $data['msj']   = 'No puede actualizar la info ingresada';
        } else {
            $data['error'] = EXIT_SUCCESS;
        }
        return $data;
    }
}