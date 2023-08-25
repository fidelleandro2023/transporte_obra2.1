<?php
class M_bolsa_pep extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
    }
    
    public function getPepBolsaList($idProyecto, $idSubProyecto, $pep1, $pep2)
    {
        $sql = "SELECT  bp.id, bp.pep1, p.proyectoDesc, sp.subProyectoDesc, e.estacionDesc, f.faseDesc, bp.fecha_programacion,
            (CASE 	WHEN bp.estado = 1 THEN 'ACTIVO'
		WHEN bp.estado = 2 THEN 'INACTIVO' END) as estado,
        (CASE WHEN DATE(NOW()) >= bp.fecha_programacion THEN 
				 DATEDIFF(NOW(), bp.fecha_programacion) 
				ELSE 0 END)  as dias_operativos,
                (CASE WHEN bp.tipo_pep = 1 THEN 'MAT'
					  WHEN bp.tipo_pep = 2 THEN 'MO'
                      WHEN bp.tipo_pep = 3 THEN 'MAT Y MO' END) as tipo_pep
                FROM 	bolsa_pep bp, subproyecto sp, proyecto p, estacion e, fase f
                WHERE 	bp.idSubProyecto = sp.idSubProyecto
                AND		sp.idProyecto = p.idProyecto
                AND 	bp.idEstacion = e.idEstacion
                AND 	bp.idFase = f.idFase
                AND     bp.estado = 1";//estado = 1 : activo
        if($idProyecto  != null){
            $sql .= "  AND		p.idProyecto = ".$idProyecto;
        }
        if($idSubProyecto   !=  null){
            $sql .= "  AND		sp.idSubProyecto = ".$idSubProyecto;
        }
        if($pep1    !=  null){
            $sql .= "  AND		bp.pep1 = '".$pep1."'";
        }
        $result = $this->db->query($sql,array());
        log_message('error', $this->db->last_query());
        return $result->result();
    }
    
    public function insertPepSubProyectoBolsa($arrayInsert){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->trans_begin();            
            $this->db->insert_batch('bolsa_pep', $arrayInsert);
            if($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar en log_planobra_po');
            }else{
                $data['error']    = EXIT_SUCCESS;
                $data['msj']      = 'Se actualizo correctamente!';
                $this->db->trans_commit();
            }            
        }catch(Exception $e){
            $data['msj']   = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    public function deletePepbolsaM($idPepBolsa, $arrayUpdate)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
    
        try {
            $this->db->trans_begin();
            $this->db->where('id', $idPepBolsa);
            $this->db->update('bolsa_pep', $arrayUpdate);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al actualizar bolsa_pep!!');
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
    
}