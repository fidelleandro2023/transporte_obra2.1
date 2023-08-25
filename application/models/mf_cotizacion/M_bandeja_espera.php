<?php

class M_bandeja_espera extends CI_Model {

    //http://www.codeigniter.com/userguide3/database/results.html
    function __construct() {
        parent::__construct();
    }

    public function getTablaAll() {
        $sql = "SELECT codigo_cluster,
					   pc.sisego,
					   e.empresaColabDesc,
					   CASE WHEN flg_principal = 0 THEN 'PRINCIPAL'
							WHEN flg_principal = 1 THEN 'RESPALDO' END flg_principal,
					   DATE_FORMAT(fecha_registro, '%d%-%m%-%y') fecha_registro,
					   CASE WHEN pc.estado = 0 THEN 'PDT COTIZACION'
                            WHEN pc.estado = 1 THEN 'PDT APROBACION'
                            WHEN pc.estado = 2 THEN 'APROBADO'
                            WHEN pc.estado = 3 THEN 'RECHAZADO'
                            WHEN pc.estado = 4 THEN 'PDT CONFIRMACION' END estadoDesc
				  FROM planobra_cluster pc,
					   pqt_central c,
					   empresacolab e
				 WHERE pc.idCentral = c.idCentral
				   AND estado = 0
				   AND e.idEmpresaColab = c.idEmpresaColab
				   AND pc.idEmpresaColab = 12";
        $result = $this->db->query($sql);
        return $result->result_array();
    }
}
