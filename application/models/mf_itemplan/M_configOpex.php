<?php

class M_configOpex extends CI_Model {

    //http://www.codeigniter.com/userguide3/database/results.html
    function __construct() {
        parent::__construct();
    }

    function saveConfigOpex($dataInsert,$ceco,$cuenta,$anho,$Area,$opex) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        
        $iteracion_opex=0;

        $eventos=[];

        $eventos=explode(",",$opex);

        for ($x = 0; $x < count($eventos); $x++) {
        $subproyecto;
        $sql = "select * from cuenta_opex_pex INNER JOIN (SELECT evenOp.*, eve.subProyectoDesc FROM subproyectoopexitemplan evenOp INNER JOIN subproyecto eve ON eve.idSubProyecto = evenOp.idSubProyecto ORDER BY `evenOp`.`idOpex` DESC) as nuevo on cuenta_opex_pex.idOpex = nuevo.idOpex where idSubproyecto='$opex' AND anho='$anho'";
        $query = $this->db->query($sql);
        $subproyecto=$query->num_rows();
        $iteracion_opex=$iteracion_opex+$subproyecto;
        }



        $valor;
        $sql = "SELECT * FROM cuenta_opex_pex WHERE ceco='$ceco' AND cuenta='$cuenta' AND areaFuncional='$Area' AND anho='$anho';";
        $query = $this->db->query($sql);
        $valor=$query->num_rows();
        if ($valor == 0 && $iteracion_opex==0)
        //if (false)
        {
        try {
            $this->db->trans_begin();
            $this->db->insert('cuenta_opex_pex', $dataInsert);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar la configuracion Opex');
            } else {
                $data['idOpex'] = $this->db->insert_id();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se registro la configuracion Opex';
                $this->db->trans_commit();
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        }else{
            if($subproyecto>0){
                throw new Exception('El SUBPROYECTO en fase seleccionada ya cuenta con una configuracion activa');
            }else{
                throw new Exception('Error al insertar  CECO, CUENTA y AREA FUNCIONAL en la fase: '.$anho.' ya se encuentra REGISTRADO');
            }
            
            
        }
        return $data;
    }

    function saveEventoOpex($dataInsert) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert('subproyectoopexitemplan', $dataInsert);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar la configuracion Opex');
            } else {
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se registro correctamente la configuracion Opex!';
                $this->db->trans_commit();
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    function updateTableOpex($dataUpdate, $idOpex, $dataInsert,$ceco,$cuenta,$anho,$Area,$opex) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        $iteracion_opex=0;

        $eventos=[];

        $eventos=explode(",",$opex);

        for ($x = 0; $x < count($eventos); $x++) {
        $subproyecto;
        $sql = "select * from cuenta_opex_pex INNER JOIN (SELECT evenOp.*, eve.subProyectoDesc FROM subproyectoopexitemplan evenOp INNER JOIN subproyecto eve ON eve.idSubProyecto = evenOp.idSubProyecto ORDER BY `evenOp`.`idOpex` DESC) as nuevo on cuenta_opex_pex.idOpex = nuevo.idOpex where idSubproyecto='$opex' AND anho='$anho'";
        $query = $this->db->query($sql);
        $subproyecto=$query->num_rows();
        $iteracion_opex=$iteracion_opex+$subproyecto;
        }

        if ($iteracion_opex==0)
        {
         try {
            $this->db->trans_begin();
            $this->db->where('idOpex', $idOpex);
            $this->db->update('cuenta_opex_pex', $dataUpdate);
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Hubo un error al actualizar el OPEX.');
            } else {
                $this->db->insert('log_cuenta_opex_pex', $dataInsert);
                if ($this->db->trans_status() === FALSE) {
                    throw new Exception('Hubo un error al actualizar el OPEX.');
                } else {
                    $data['error'] = EXIT_SUCCESS;
                    $data['msj'] = 'Se actualizo correctamente!';
                    $this->db->trans_commit();
                }
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }

    }
    else{
        if($subproyecto>0){
            throw new Exception('El SUBPROYECTO en fase seleccionada ya cuenta con una configuracion activa');
        }else{
            //throw new Exception('Error al insertar  CECO, CUENTA y AREA FUNCIONAL en la fase: '.$anho.' ya se encuentra REGISTRADO');
        }
        
        
    }
        return $data;
    }

    public function deleteEventoOpex($idOpex) {
        $sql = "DELETE FROM subproyectoopexitemplan WHERE idOpex='$idOpex'";
        $this->db->query($sql);
        return true;
    }

    public function selectEventoOpex($idOpex) {
        $sql = "SELECT
                evenOp.*,
                eve.subProyectoDesc
                FROM
                subproyectoopexitemplan evenOp
                INNER JOIN subproyecto eve ON eve.idSubProyecto = evenOp.idSubProyecto WHERE idOpex=$idOpex";
        $result = $this->db->query($sql);
        return $result->result();
    }

    public function selectEventoOpexSub($idSubproyecto) {
        $sql = "SELECT * 
		          FROM (
						SELECT evenOp.*, 
							   eve.subProyectoDesc 
						  FROM subproyectoopexitemplan evenOp 
					INNER JOIN subproyecto eve ON eve.idSubProyecto = evenOp.idSubProyecto 
				         WHERE eve.idSubproyecto=$idSubproyecto
						) as query1 
			 INNER JOIN cuenta_opex_pex 
			         ON query1.idOpex = cuenta_opex_pex.idOpex";
        $result = $this->db->query($sql);
        return $result->result();
    }

    public function getDatoUnico($idSubproyecto) {
        $sql = "SELECT * FROM subproyecto  WHERE idSubProyecto=$idSubproyecto";
        $result = $this->db->query($sql);
        return $result->result();
    }

    function getFase($idFase) {
        $Query = "SELECT * FROM fase WHERE idFase= ?";
        $result = $this->db->query($Query, array($idFase));
        if ($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }

    public function getFasesItemplan($idSubproyecto,$fase) {

      
        $Query = " select x.fase, x.cantItemPlan , (select count(*) from planobra p INNER JOIN fase f on p.idfase = f.idfase where p.idSubProyecto = x.idSubProyecto and f.faseDesc = x.fase) registrado
        from subproyecto_fases_cant_itemplan x where x.idSubProyecto = ? and x.fase = ?";
        $result = $this->db->query($Query,array($idSubproyecto, $fase));
        return $result->result();
    }


    public function getcantidadItemplan($idSubproyecto,$fase) {

      
        $Query = " select * from planobra where idSubProyecto = ? and idFase = ?";
        $result = $this->db->query($Query,array($idSubproyecto, $fase));
        return $result->result();
    }



    public function getTableOpexId($idOpex) {
        $sql = "SELECT * FROM cuenta_opex_pex WHERE idOpex='$idOpex'";
        $result = $this->db->query($sql);
        return $result->result();
    }

    public function getEventoOpex($idOpex) {
        $sql = "SELECT
                evenOp.idSubproyecto
                FROM
                subproyectoopexitemplan evenOp
                INNER JOIN subproyecto eve ON eve.idSubProyecto = evenOp.idSubProyecto WHERE idOpex=$idOpex;";
        $result = $this->db->query($sql);
        return $result->result();
    }

    public function getPepBolsaList($idProyecto, $idSubProyecto)
    {
        $sql = "SELECT  bp.id, bp.pep1,bp.pep2, p.proyectoDesc, sp.subProyectoDesc, e.estacionDesc, f.faseDesc, bp.fecha_programacion,
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
     
        $result = $this->db->query($sql,array());
        log_message('error', $this->db->last_query());
        return $result->result();
    }

    public function getPepCorrelativo($pep1)
    {
        $sql = "SELECT  * from pep1xcorrelativo  where pep1='$pep1'";
     
     
        $result = $this->db->query($sql,array());
        log_message('error', $this->db->last_query());
        return $result->result();
    }


    public function getFases() {
        $sql = "SELECT * FROM fase";

        $result = $this->db->query($sql);
        return $result->result();
    }

    

    public function getEvento() {
        if ($this->session->userdata('idPerfilSession') == 48) {
            $sql = "SELECT * FROM subproyecto";
        } else {
            $sql = "SELECT * FROM subproyecto";
        }

        $result = $this->db->query($sql);
        return $result->result();
    }

    public function getTablaOpex($selectEvento, $selectAno) {
        if ($selectAno !== null || $selectEvento !== null) {
            $mas = " WHERE YEAR(fecha_registro)  LIKE '%$selectAno%' AND NOT idEstadoOpex=3 ORDER BY cuenta_opex_pex.idEstadoOpex DESC";
        } else {
            $mas = " WHERE NOT idEstadoOpex=3 ORDER BY cuenta_opex_pex.idEstadoOpex DESC";
        }
        $sql = "SELECT
	cuenta_opex_pex.*,
	FORMAT( monto_inicial, 2 ) AS monto_inicial_for,
	FORMAT( monto_temporal, 2 ) AS monto_temporal_for,
	FORMAT( ( monto_real - monto_temporal ), 2 ) AS monto_reservado_for,
	FORMAT( ( monto_inicial - monto_real ), 2 ) AS monto_consumido_for,
	FORMAT( monto_real, 2 ) AS monto_real_for 
FROM
	cuenta_opex_pex " . $mas;
        $result = $this->db->query($sql);
        return $result->result();
    }

    //Datos configuracion cuenta Opex
    public function getTablaOpexAll() {
        $sql = "SELECT
	cuenta_opex_pex.*,
	FORMAT( monto_inicial, 2 ) AS monto_inicial_for,
	FORMAT( monto_temporal, 2 ) AS monto_temporal_for,
	FORMAT( ( monto_real - monto_temporal ), 2 ) AS monto_reservado_for,
	FORMAT( ( monto_inicial - monto_real ), 2 ) AS monto_consumido_for,
	FORMAT( monto_real, 2 ) AS monto_real_for 
FROM
	cuenta_opex_pex WHERE NOT idEstadoOpex=3 ORDER BY cuenta_opex_pex.idEstadoOpex DESC";
        $result = $this->db->query($sql);
        return $result->result();
    }

    public function historialOpex($ceco, $cuenta, $area) {
        $sql = "SELECT * FROM transaccionopexitemplan WHERE ceco='$ceco' AND cuenta='$cuenta' AND areaFuncional='$area';";
        $result = $this->db->query($sql);
        return $result->result();
    }

    public function historialLog($idOpex) {
        $sql = "SELECT log.*,
                usu.usuario	
                FROM
	log_cuenta_opex_pex log,
	usuario usu
WHERE 
log.usr = usu.id_usuario
AND
log.idOpex ='$idOpex'";
        $result = $this->db->query($sql);
        return $result->result();
    }

}
