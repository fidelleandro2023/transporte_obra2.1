<?php
class M_regularizar_evidencia_itemplan extends CI_Model
{
    public function __construct()
    {
        parent::__construct();

    }

    public function getInfoItemplan($itemplan)
    {
        $sql = "     SELECT po.itemplan,po.idEstadoPlan,po.idSubProyecto,sp.subProyectoDesc,po.idEmpresaColab,
                            (CASE WHEN c.jefatura = 'LIMA' THEN 1 ELSE 2 END) AS isLima,
                            po.paquetizado_fg,po.fecha_creacion,
                            po.cantFactorPlanificado
                       FROM planobra po,
                            subproyecto sp,
                            pqt_central c#,
							#itemplan_valida_evidencia iee
                      WHERE po.idSubProyecto = sp.idSubProyecto
                        AND po.idCentralPqt = c.idCentral
						#AND po.itemplan = iee.itemplan
						#AND has_evidencia = '0' 
                        -- AND po.idEstadoPlan IN (22,9)
                        AND po.itemplan = ?
                   GROUP BY 1 ";

        $result = $this->db->query($sql, array($itemplan));
        if ($result->num_rows() > 0) {
            return $result->row_array();
        } else {
            return null;
        }
    }

	public function insertEvidenciaPIN($arrayInsert)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        try {
            $this->db->insert('itemplan_evidencia', $arrayInsert);
            if($this->db->affected_rows() <= 0) {
                throw new Exception('Hubo un error al insertar en la tabla itemplan_evidencia!!');
            }else{
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se insertó correctamente!!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        return $data;
    }

   public function getCountEvidencia($itemplan)
    {
        $sql = "  	  SELECT COUNT(*) cantidad,SUM(cant_subida) AS cant_subida
                        FROM itemplan_evidencia
                       WHERE itemplan = ?
               ";
        $result = $this->db->query($sql, array($itemplan));
        return $result->row_array();
    }
	
	public function getInfoEstacionByItemplan($itemplan)
    {
        $sql = "      SELECT po.itemplan,ea.idEstacion,e.estacionDesc,COUNT(*) cantidad
					    FROM planobra po,
						     subproyectoestacion se,
						     estacionarea ea,
							 estacion e
					   WHERE po.idSubProyecto = se.idSubProyecto
						 AND se.idEstacionArea = ea.idEstacionArea
						 AND ea.idEstacion = e.idEstacion
						 AND e.idEstacion != 1
						 AND po.itemplan = ?
					GROUP BY 1";

        $result = $this->db->query($sql, array($itemplan));
        if ($result->num_rows() > 0) {
            return $result->row_array();
        } else {
            return null;
        }
    }
	
	function registrarEvidencias($tramaEvidencia,$itemplan,$idEstacion){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
			$this->db->where('itemplan', $itemplan);
	        $this->db->where('idEstacion', $idEstacion);
	        $this->db->delete('siom_obra_evidencias');
			if($this->db->trans_status() === FALSE){
	            $this->db->trans_rollback();
	            throw new Exception('Error al eliminar siom_obra_evidencias.');
	        } else {
				$this->db->insert('siom_obra_evidencias', $tramaEvidencia);
				if($this->db->affected_rows() != 1) {
					$this->db->trans_rollback();
					throw new Exception('Error al insertar en siom_obra_evidencias');
				}else{
					$this->db->trans_commit();
					$data['error'] = EXIT_SUCCESS;
					$data['msj'] = 'Se inserto correctamente!';
				}
			}
	    }catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
	
		public function updateEvidenciaPIN($arrayUpdate)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        try {
			$this->db->where('itemplan', $arrayUpdate['itemplan']);
            $this->db->update('itemplan_evidencia', $arrayUpdate);
            if($this->db->affected_rows() <= 0) {
                throw new Exception('Hubo un error al actualizar en la tabla itemplan_evidencia!!');
            }else{
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se actualizó correctamente!!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        return $data;
    }
	

}
