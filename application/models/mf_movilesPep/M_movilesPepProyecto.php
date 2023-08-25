<?php

class M_movilesPepProyecto extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getMovilesPep() {
        $Query = "SELECT
CASE
		
	WHEN
		tb.idProyecto IS NULL THEN
			0 ELSE tb.idProyecto 
			END AS idProyecto,
	CASE
			
			WHEN tb.proyectoDesc IS NULL THEN
			'SIN PROYECTO' ELSE tb.proyectoDesc 
		END AS proyectoDesc,
		round( sum( tb.presupuesto ), 2 ) AS presupuesto,
		round( sum( tb.reall ), 2 ) AS reall,
		round( sum( tb.comprometido ), 2 ) AS comprometido,
		round( sum( tb.planresord ), 2 ) AS planresord,
		round( sum( tb.disponible ), 2 ) AS disponible,
		round( sum( tb.disponible ) * 100 / sum( tb.presupuesto ), 0 ) AS percent,
		NULL AS flg_tipo 
	FROM
		(
		SELECT
			p.idMovilesProyecto AS idProyecto,
			p.movilProyectoDesc AS proyectoDesc,
			REPLACE ( s.presupuesto, ',', '' ) AS presupuesto,
			REPLACE ( s.real, ',', '' ) AS reall,
			REPLACE ( s.comprometido , ',', '' ) AS comprometido,
			REPLACE ( s.planresord, ',', '' ) AS planresord,
			REPLACE (s.disponible, ',', '' ) AS disponible 
		FROM
			(
				(
					(
						( gics_sisego.sap_moviles_fija s LEFT JOIN gics_sisego.pep_bianual_2 b ON ( s.pep1 = concat( 'PEP ', b.pep ) ) )
						LEFT JOIN gics_sisego.moviles_pep_ t ON ( substr( s.pep1, 5 ) = t.movilesPepDesc ) 
					)
					LEFT JOIN gics_sisego.moviles_subproyecto sp ON ( sp.idMovilesSubproyecto = t.idSubproyecto ) 
				)
				LEFT JOIN gics_sisego.moviles_proyecto p ON ( sp.idMovilesProyecto = p.idMovilesProyecto ) 
			) 
		WHERE
			s.nivel = 1 
			AND b.pep IS NULL 
		) tb 
	GROUP BY
		tb.proyectoDesc UNION ALL
	SELECT
	CASE
			
		WHEN
			tb.idProyecto IS NULL THEN
				0 ELSE tb.idProyecto 
				END AS idProyecto,
		CASE
				
				WHEN tb.proyectoDesc IS NULL THEN
				'SIN PROYECTO' ELSE tb.proyectoDesc 
			END AS proyectoDesc,
			round( sum( tb.presupuesto ), 2 ) AS presupuesto,
			round( sum( tb.reall ), 2 ) AS reall,
			round( sum( tb.comprometido ), 2 ) AS comprometido,
			round( sum( tb.planresord ), 2 ) AS planresord,
			round( sum( tb.disponible ), 2 ) AS disponible,
			round( sum( tb.disponible ) * 100 / sum( tb.presupuesto ), 0 ) AS percent,
			tb.flg_tipo AS flg_tipo 
		FROM
			(
			SELECT
				9999 AS idProyecto,
			CASE
					
					WHEN pb.flg_tipo = 1 THEN
					'BIANUAL COMPROMETIDA' 
					WHEN pb.flg_tipo = 2 THEN
					'BIANUAL DISPONIBLE' 
					WHEN pb.flg_tipo = 3 THEN
					'BIANUAL VR' 
				END AS proyectoDesc,
				REPLACE ( s.presupuesto, ',', '' ) AS presupuesto,
				REPLACE ( s.real, ',', '' ) AS reall,
				REPLACE ( s.comprometido, ',', '' ) AS comprometido,
				REPLACE ( s.planresord, ',', '' ) AS planresord,
				REPLACE ( s.disponible, ',', '' ) AS disponible,
				pb.flg_tipo AS flg_tipo 
			FROM
				( gics_sisego.pep_bianual_2 pb JOIN gics_sisego.sap_moviles_fija s ) 
			WHERE
				concat( 'PEP ', pb.pep ) = s.pep1 
				AND s.nivel = 1 
			) tb 
	GROUP BY
	tb.proyectoDesc";
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

}
