<?php
class M_editar_vr_po extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
    }
    


    function getPtrByItemplan($itemplan,$ideecc) {

        $sql = " SELECT *, 
                        CONCAT(ptr,' (',(SELECT estacionDesc FROM estacion WHERE idEstacion = t.idEstacion), ')') AS ptrEstacion 
                FROM (
                        SELECT  dp.poCod AS ptr,
                                w.vale_reserva vr,
                                c.jefatura,
                                e.empresaColabDesc,
                                ( SELECT GROUP_CONCAT(je.codAlmacen,'|',je.codCentro,'|', je.idJefatura, '|', je.idEmpresaColab) 
                                    FROM jefatura_sap j, 
                                        jefatura_sap_x_empresacolab je 
                                    WHERE j.idJefatura = je.idJefatura
                                    AND je.idEmpresacolab = e.idEmpresacolab
                                    AND CASE WHEN j.idZonal IS NULL THEN c.jefatura = j.descripcion ELSE j.idZonal = c.idZonal END ) AS dataJefaturaEmp,
                                ea.idEstacion,
                                SUBSTRING_INDEX(w.estado, '-', 1) est_innova,
                                po.idSubProyecto,
                                ep.estadoPlanDesc,
                                (CASE WHEN w.ptr IS NOT NULL THEN 1 ELSE 2 END) AS flg_origen
                        FROM (
                                ( detalleplan dp LEFT JOIN web_unificada_det w ON (w.ptr = dp.poCod) ) LEFT JOIN planobra_po ppo ON (ppo.codigo_po = dp.poCod),
                                planobra po,
                                estadoplan ep,
                                central c,
                                empresacolab e,
                                subproyectoestacion sp,
                                estacionarea ea,
                                area a
                            ) 
                        WHERE sp.idSubProyectoEstacion = dp.idSubProyectoEstacion
                        AND sp.idEstacionarea        = ea.idEstacionArea
                        AND a.idArea                 = ea.idArea
                        AND a.tipoArea 			     = 'MAT'
                        AND c.idCentral 			 = po.idCentral
                        AND c.idEmpresaColab 		 = e.idEmpresaColab
                        AND ea.idEstacion <> 1 
                        AND CASE WHEN ".$ideecc."  = 0 OR ".$ideecc."  = 6 THEN c.idEmpresaColab = c.idEmpresaColab
                                    WHEN ".$ideecc."  IN ( SELECT idSubProyecto 
                                                FROM subproyecto 
                                                WHERE idProyecto = 5)
                                    THEN c.idEmpresaColabCV = ".$ideecc."                
                                    ELSE c.idEmpresaColab = ".$ideecc." END
                        AND po.itemplan = dp.itemplan
                        AND po.idEstadoPlan = ep.idEstadoPlan
                        AND CASE WHEN w.ptr IS NOT NULL THEN 
                                    w.estado_asig_grafo = '2'
                                ELSE ppo.estado_po IN (3,4,5,6) END
                        AND po.idEstadoPlan IN (11,3, 4, 9, 10, 6)
                        AND dp.itemplan = '".$itemplan."' ) AS t";
	    
		$result = $this->db->query($sql);
		return $result->result();  
    }
    
    public function updateVRByPTR($ptr, $arrayUpdate)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        try {
            $this->db->trans_begin();
            $this->db->where('ptr', $ptr);
            $this->db->update('web_unificada_det', $arrayUpdate);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al actualizar la PTR.');
            } else {
                $this->db->trans_commit();
				$data['error'] = EXIT_SUCCESS;
				$data['msj'] = 'Se actualiz&oacute; correctamente!!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        return $data;
    }
    
    public function insertarLogEditVRPOPTR($arrayInsert)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert('log_edit_vr_po_ptr', $arrayInsert);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar tabla log_edit_vr_po_ptr');
            } else {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se insert&oacute; correctamente!!';
            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
	}
    
   
}