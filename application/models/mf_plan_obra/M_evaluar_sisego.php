<?php

class M_evaluar_sisego extends CI_Model {

    //http://www.codeigniter.com/userguide3/database/results.html
    function __construct() {
        parent::__construct();
    }

    public function getTablaAll() {
        $sql = "  SELECT pep.*, 
						 CASE WHEN pep.flg_estado = 0 THEN 'PENDIENTE'
							  ELSE 'EVALUADO' END AS estado
					FROM evalua_nuevo_pep pep
			   LEFT JOIN sap_detalle sd ON pep.pep = sd.pep1
				   WHERE sd.pep1 IS NULL";
        $result = $this->db->query($sql);
        return $result->result();
    }
	
	function getTablaPepConsulta() {
        $sql = "  SELECT pep,
                          fecha_registro,
						  CASE WHEN flg_estado = 3 AND sa.pep1 IS NOT NULL THEN 'LA PEP EVALUADA SE CARGO'
						       WHEN flg_estado = 3 AND sa.pep1 IS NULL THEN 'LA PEP SE CARGO ANTERIORMENTE Y NO VINO EN LA ULTIMA CARGA'
						       WHEN flg_estado = 2 THEN 'PENDIENTE' END estado
                     FROM evalua_nuevo_pep ev 
			    LEFT JOIN sap_detalle sa ON (ev.pep = sa.pep1)
				  WHERE sa.pep1 IS NULL
					GROUP BY pep";
		$result = $this->db->query($sql);
		return $result->result();
    }
}
