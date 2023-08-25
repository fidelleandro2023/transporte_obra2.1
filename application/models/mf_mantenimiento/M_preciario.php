<?php
class M_preciario extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
    }

    function getPreciarioData($idEmpresaColab, $idZonal, $idPrecioDiseno, $idEstacion) {
        $sql = "SELECT pre.idPrecioDiseno,
                       pre.idZonal,
                       pre.idEmpresaColab,
                       z.zonalDesc,
                       e.empresaColabDesc,
                       pre.costo,
                       pre.idEstacion,
                       es.estacionDesc,
                       pd.descPrecio
                  FROM preciario pre,
                       zonal     z,
                       empresacolab e,
                       precio_diseno pd,
                       estacion es
                 WHERE pre.idZonal        = z.idZonal
                   AND e.idEmpresaColab   = pre.idEmpresaColab
                   AND es.idEstacion      = pre.idEstacion
                   AND pd.idPrecioDiseno  = pre.idPrecioDiseno
                   AND pre.idEmpresaColab = COALESCE(?, pre.idEmpresaColab)
                   AND pre.idZonal 		  = COALESCE(?, pre.idZonal)
                   AND pre.idPrecioDiseno = COALESCE(?, pre.idPrecioDiseno) 
                   AND pre.idEstacion     = COALESCE(?, pre.idEstacion) ";
        $result = $this->db->query($sql, array($idEmpresaColab, $idZonal, $idPrecioDiseno, $idEstacion));
        return $result->result_array();           
    }



    function insertPreciario($arrayInsert) {
        $this->db->insert('preciario', $arrayInsert);

        if($this->db->affected_rows() != 1) {
            return array('error' => EXIT_ERROR, 'msj' => 'error No se ingreso el material');
        }else{
            return array('error' => EXIT_SUCCESS);
        }
    }

    function countPreciario($idEmpresaColab, $idZonal, $idPrecioDiseno, $idEstacion) {
        $sql = "SELECT COUNT(1) AS count 
                  FROM preciario 
                 WHERE idZonal        = ".$idZonal." 
                   AND idEmpresaColab = ".$idEmpresaColab."
                   AND idPrecioDiseno = ".$idPrecioDiseno."
                   AND idEstacion     = ".$idEstacion;
        $result = $this->db->query($sql);
        return $result->row_array()['count'];                      
    }
}