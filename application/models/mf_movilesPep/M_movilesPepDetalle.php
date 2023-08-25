<?php

class M_movilesPepDetalle extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getMovilesPep() {
        $Query = "SELECT * FROM view_pep_moviles_proyecto";
        $result = $this->db->query($Query, array());
        return $result;
    }

    public function getSubProyecto() {
        if ($this->session->userdata('idPerfilSession') == 48) {
            $sql = "SELECT * FROM subproyecto";
        } else {
            $sql = "SELECT * FROM subproyecto";
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
    
    function FiltrarPepReporte($idProyecto, $idSubProyecto, $tipo) {
        $extra = "";
        if ($idProyecto != '') {

            $listPro = explode(',', $idProyecto);
            if (in_array('0', $listPro)) {
                $extra .= " and (su.idProyecto in (" . $idProyecto . ") or su.idProyecto IS NULL) ";
            } else {
                if ($idProyecto == 22 || $idProyecto == 8) {
                    $idProyecto = '22,8';
                }
                $extra .= " and su.idMovilesSubproyecto in (" . $idProyecto . ")";
            }
        }
        if ($idSubProyecto != '') {
            $extra .= " and pt.idMovilesSubproyecto in (" . $idSubProyecto . ")";
        }
        if ($tipo != '') {
            $extra .= " and pt.promotorPep in (" . $tipo . ")";
        }

        $Query = "
SELECT
	tb.*,
	sd.monto_temporal,
	timestampdiff( MONTH, tb.fecha_registro, curdate( ) ) AS dif_meses 
FROM
	(
	SELECT
		* 
	FROM
		(
		SELECT
			pt.*,
			tt.movilesPromotoresDesc tnombre,
			su.movilesSuproyectoDesc subProyectoDesc,
			SUBSTR( s.pep1, 5 ) pep1,
			s.sap_coaxialcol4 AS detalle,
			s.plan,
			s.presupuesto,
			s.real,
			s.comprometido,
			s.planresord,
			s.disponible
		FROM
			sap_moviles_fija s
			LEFT JOIN pep_bianual_2 b ON s.pep1 = CONCAT( 'PEP ', b.pep )
			LEFT JOIN moviles_pep_ pt ON pt.movilesPepDesc = SUBSTR( s.pep1, 5 )
			LEFT JOIN moviles_promotores tt ON tt.idMovilesPromotores = pt.promotorPep
			LEFT JOIN moviles_subproyecto su ON su.idMovilesSubproyecto = pt.idSubProyecto 
		WHERE
			s.nivel = 1 " . $extra . "
			AND b.pep IS NULL 
			) s 
	) AS tb,
	sap_moviles_detalle sd 
WHERE
	tb.movilesPepDesc = sd.pep1 
ORDER BY
	tb.idSubProyecto,
	tb.fecha_registro";
        $result = $this->db->query($Query, array());
        return $result;
    }
    
    
    function getDetallePepBianual($flg_tipo) {
        $sql = "SELECT null as id_peptoro,
                       pb.pep as id_pep, 
                       null as idSubProyecto,
                       null as id_tipo_toro,
                       null as id_categoria_toro,
                       null as fecha,
                       pb.estado,
                       null as reg_tmp,
                       null as fecha_programacion,
                       null as tnombre,
                       'PEP BIANUAL' as subProyectoDesc,
                       null as id_toro,
                       null as monto,
                       SUBSTR(s.pep1,5) pep1,
                       s.sap_coaxialcol4 as detalle,
                       s.plan,
                       s.presupuesto,
                       s.real,
                       s.comprometido,
                       s.planresord,
                       s.disponible,
                       null as comentario,
                       sd.monto_temporal,
                       null as dif_meses
			      FROM  sap_fija s, 
                       pep_bianual_2 pb left join sap_detalle sd on pb.pep = sd.pep1
                 WHERE concat('PEP ',pb.pep) = s.pep1
				   AND s.nivel = 1
				   AND pb.flg_tipo = ?";
        $result = $this->db->query($sql, array($flg_tipo));
        return $result;
    }

}
