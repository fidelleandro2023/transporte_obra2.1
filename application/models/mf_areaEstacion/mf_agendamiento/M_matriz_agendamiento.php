<?php
class M_matriz_agendamiento extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
    }

    function getMatrizAgendamiento($ecc) {
		$sql = "SELECT c.idEmpresaColab, 
                       c.jefatura, 
                       a.idCuotaAgenda,
                       a.horaInicio, 
                       a.horafin as horaFin,
                       a.cantidad,
                       CASE WHEN empresaColabDesc = 'COBRA'    AND ".$ecc." IN (0,6)  THEN 1 END cobra,
                       CASE WHEN empresaColabDesc = 'LARI'     AND ".$ecc." IN (0,6)  THEN 1 END lari,
                       CASE WHEN empresaColabDesc = 'DOMINION' AND ".$ecc." IN (0,6) THEN 1 END dominion,
                       CASE WHEN empresaColabDesc = 'EZENTIS'  AND ".$ecc." IN (0,6) THEN 1 END ezentis,
               
                       CASE WHEN empresaColabDesc = 'QUANTA'   AND ".$ecc." IN (0,6) THEN 1 END quanta,
                       CASE WHEN empresaColabDesc = 'CAMPERU'  AND ".$ecc." IN (0,6) THEN 1 END camperu
				FROM (central c,
					 empresacolab e) LEFT JOIN 
                     cuotas_agenda a ON(a.idEmpresaColab = c.idEmpresaColab AND a.jefatura = c.jefatura)
                WHERE e.idEmpresaColab = c.idEmpresaColab 
                  AND e.idEmpresaColab NOT IN (".ID_EECC_TDP.", ".ID_EECC_CALATEL.", ".ID_EECC_HUAWEI.")     
				GROUP BY c.jefatura, c.idEmpresaColab";
		$result = $this->db->query($sql); 
		return $result->result_array();  
    }
    
    function countCuotasAgenda($idEmpresaColab, $jefatura) {
        $sql = "SELECT COUNT(1) as count
                  FROM cuotas_agenda 
                 WHERE idEmpresaColab = ".$idEmpresaColab."
                   AND jefatura       = '".$jefatura."'";
        $result = $this->db->query($sql); 
        return $result->row()->count; 
    }

    function insertAgendamiento($arrayData) {
        $this->db->insert('cuotas_agenda', $arrayData);
        if($this->db->affected_rows() == 0) {
            $data['error'] = EXIT_ERROR;
			return $data;
		} else {
            $data['error'] = EXIT_SUCCESS;
			return $data;
		}
    }

    function updateAgendamiento($arrayData, $idEmpresaColab, $jefatura) {
        $this->db->where('idEmpresaColab', $idEmpresaColab);
        $this->db->where('jefatura', $jefatura);
        $this->db->update('cuotas_agenda', $arrayData);
        if($this->db->affected_rows() == 0) {
            $data['error'] = EXIT_ERROR;
			return $data;
		} else {
            $data['error'] = EXIT_SUCCESS;
			return $data;
		}
    }

    function getBandaHoraria($idEmpresaColab, $jefatura) {
        $sql = "SELECT idBandaHoraria, 
                       horaInicio, 
                       horaFin, 
                       estado,
                       CONCAT(horaInicio, ' - ', horaFin)horaInFin 
                  FROM banda_horaria
                 WHERE estado = ".FLG_ACTIVO."
                   AND CASE WHEN '".$idEmpresaColab."' IS NOT NULL THEN
                                 idBandaHoraria NOT IN(SELECT idBandaHoraria 
                                                         FROM cuotas_agenda 
                                                        WHERE jefatura       = '".$jefatura."' 
                                                          AND idEmpresaColab = COALESCE(?, idEmpresaColab))
                            ELSE  estado = estado  END";
        $result = $this->db->query($sql, array($idEmpresaColab));
        return $result->result();         
    }

    function getCuotasAgenda($idCuotaAgenda, $jefatura, $idEmpresaColab) {
        $sql = "SELECT cu.idCuotaAgenda, 
                       cu.jefatura, 
                       cu.idEmpresaColab, 
                       cu.cantidad, 
                       cu.idBandaHoraria,
                       CONCAT(ba.horaInicio, ' - ', ba.horaFin)horaInFin
                  FROM cuotas_agenda cu,
                       banda_horaria ba
                 WHERE ba.idBandaHoraria = cu.idBandaHoraria
                   AND cu.idCuotaAgenda     = COALESCE(?, cu.idCuotaAgenda)
                   AND cu.jefatura          = COALESCE(?, cu.jefatura)
                   AND cu.idEmpresaColab    = COALESCE(?, cu.idEmpresaColab)";
        $result = $this->db->query($sql, array($idCuotaAgenda, $jefatura, $idEmpresaColab));
        return $result->result();     
    }

    function actualizarCuotas($dataUpdate) {
        $this->db->trans_begin();

        $this->db->update_batch('cuotas_agenda', $dataUpdate, 'idCuotaAgenda');
        // $this->db->insert_batch('log_solicitud_vr', $arrayLogVr);
        
        if($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $data['error'] = EXIT_ERROR;
			return $data;
        }else{
            $this->db->trans_commit();
            $data['error'] = EXIT_SUCCESS;
			return $data;
        }
    }
}