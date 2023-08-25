<?php

class M_pep_sap extends CI_Model {

    //http://www.codeigniter.com/userguide3/database/results.html
    function __construct() {
        parent::__construct();
    }

    function saveConfigOpex($dataInsert) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert('cuentaOpex', $dataInsert);
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
        return $data;
    }

    function saveEventoOpex($dataInsert) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert('eventoOpex', $dataInsert);
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

    function updateTableOpex($dataUpdate, $idOpex) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->where('idOpex', $idOpex);
            $this->db->update('cuentaOpex', $dataUpdate);
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Hubo un error al actualizar el OPEX.');
            } else {
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se actualizo correctamente!';
                $this->db->trans_commit();
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    public function deleteEventoOpex($idOpex) {
        $sql = "DELETE FROM eventoOpex WHERE idOpex='$idOpex'";
        $this->db->query($sql);
        return true;
    }

    public function selectEventoOpex($idOpex) {
        $sql = "SELECT
                evenOp.*,
                eve.EventoDesc
                FROM
                eventoOpex evenOp
                INNER JOIN evento eve ON eve.idEvento = evenOp.idEvento WHERE idOpex=$idOpex";
        $result = $this->db->query($sql);
        return $result->result();
    }

    public function getTableOpexId($idOpex) {
        $sql = "SELECT * FROM cuentaOpex WHERE idOpex='$idOpex'";
        $result = $this->db->query($sql);
        return $result->result();
    }

    public function getEventoOpex($idOpex) {
        $sql = "SELECT
                evenOp.idEvento
                FROM
                eventoOpex evenOp
                INNER JOIN evento eve ON eve.idEvento = evenOp.idEvento WHERE idOpex=$idOpex;";
        $result = $this->db->query($sql);
        return $result->result();
    }

    public function getEvento() {
        if ($this->session->userdata('idPerfilSession') == 48) {
            $sql = "SELECT * FROM evento WHERE NOT idEvento=3";
        } else {
            $sql = "SELECT * FROM evento";
        }

        $result = $this->db->query($sql);
        return $result->result();
    }

    public function getTablaOpex($selectEvento, $selectAno) {
        $sql = "SELECT
                cuentaOpex.*, 
                FORMAT(monto_final, 2) AS montoFinal,
                FORMAT(monto_provisional, 2) AS montoPro,
                FORMAT(monto_real, 2) AS montoReal,
                FORMAT(monto_dispo, 2) AS montoDisp
                FROM
                cuentaOpex WHERE YEAR(fecha_registro) LIKE '%$selectAno%' AND NOT idEstadoOpex=3 ORDER BY cuentaOpex.idEstadoOpex DESC";
        $result = $this->db->query($sql);
        return $result->result();
    }

    public function get_tabla_origen_pep($pep) {
        $sql = "      
				  SELECT * 
					FROM (    
					  SELECT 'BOLSA PEP' as origen, b.pep1, CASE WHEN s.pep1 IS NOT NULL THEN 'CON SAP'
															   ELSE 'SIN SAP' END situacion, s.monto_inicial, s.monto_temporal
						FROM bolsa_pep b 
				   LEFT JOIN sap_detalle s ON b.pep1 = s.pep1
					   WHERE b.pep1 <> 'P-0000-00-0000-00000'
					  UNION ALL
					  SELECT 'PLAN OBRA' origen, SUBSTRING(pep2,1,20) pep1, CASE WHEN s.pep1 IS NOT NULL THEN 'CON SAP'
																			ELSE 'SIN SAP' END situacion, s.monto_inicial, s.monto_temporal
					   FROM planobra po
				  LEFT JOIN sap_detalle s ON SUBSTRING(pep2,1,20)  = s.pep1
					 WHERE pep2 <> ''
					   AND pep2 IS NOT NULL
					   AND pep2 NOT IN ('P-0000-00-0000-00000-001', 'E-055-17-005-001-446')
					   AND SUBSTRING(pep2,1,20) NOT IN (SELECT pep1 FROM bolsa_pep)
					   GROUP BY pep2
					   )t
					WHERE pep1 like '%$pep%'";
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    public function historialOpex($idOpex) {
        $sql = "SELECT * FROM transaccionOpex WHERE idOpex='$idOpex'";
        $result = $this->db->query($sql);
        return $result->result();
    }

}
