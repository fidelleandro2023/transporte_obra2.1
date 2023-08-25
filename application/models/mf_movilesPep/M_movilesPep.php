<?php

class M_movilesPep extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getMovilesPep($subProy, $Pep) {
        if ($subProy) {
            $sql = "SELECT * FROM view_pep_moviles WHERE idSubProyecto = '$subProy'";
        } else if ($Pep) {
            $sql = "SELECT * FROM view_pep_moviles WHERE movilesPepDesc = '$Pep'";
        } else if ($Pep && $subProy) {
            $sql = "SELECT * FROM view_pep_moviles WHERE idSubProyecto = '$Pep' AND  movilesPepDesc = '$subProy'";
        } else {
            $sql = "SELECT * FROM view_pep_moviles";
        }

        $result = $this->db->query($sql);
        return $result->result();
    }

    public function getSubProyecto() {
        if ($this->session->userdata('idPerfilSession') == 48) {
            $sql = "SELECT
	sb.idMovilesSubproyecto,
	CONCAT( sb.movilesSuproyectoDesc, ' - ', pr.movilProyectoDesc ) AS movilesSuproyectoDesc 
FROM
	moviles_subproyecto sb,
	moviles_proyecto pr 
WHERE
	pr.idMovilesProyecto = sb.idMovilesProyecto";
        } else {
            $sql = "SELECT
	sb.idMovilesSubproyecto,
	CONCAT( sb.movilesSuproyectoDesc, ' - ', pr.movilProyectoDesc ) AS movilesSuproyectoDesc 
FROM
	moviles_subproyecto sb,
	moviles_proyecto pr 
WHERE
	pr.idMovilesProyecto = sb.idMovilesProyecto";
        }

        $result = $this->db->query($sql);
        return $result->result();
    }

    function getTipo() {
        $sql = "SELECT * FROM moviles_tipo";
        $result = $this->db->query($sql);
        return $result->result();
    }

    function saveTablePepMoviles($dataInsert) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert('moviles_pep_', $dataInsert);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar la PEP movil');
            } else {
                $data['idOpex'] = $this->db->insert_id();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se registro la PEP movil';
                $this->db->trans_commit();
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

}
