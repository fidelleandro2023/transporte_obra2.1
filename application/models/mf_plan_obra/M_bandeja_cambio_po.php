<?php
class M_bandeja_cambio_po extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
    }


    function getTablaBandejaCambio() {
        $sql = "  SELECT pc.itemplan, 
                         pc.codigo_po,
                         e.estacionDesc,
                         pc.idEstacion,
                         s.subProyectoDesc
                    FROM (po_cambio pc,
                          detalleplan dp,
                          estacion e,
                          planobra po,
                          subproyecto s)
               LEFT JOIN planobra_po ppo 
                      ON (pc.codigo_po  = ppo.codigo_po AND ppo.codigo_po = dp.poCod)
                   WHERE pc.idEstacion = e.idEstacion
                     AND po.itemplan = pc.itemplan
                     AND po.idSubProyecto = s.idSubProyecto
                     GROUP BY pc.itemplan, pc.idEstacion";
        $result = $this->db->query($sql);
        return $result->result_array();     
    }

    // function getTablaBandejaCambio() {
    //     $sql = "SELECT pc.itemplan, 
    //                    pc.idEstacion, 
    //                    ppo.codigo_po,
    //                    e.estacionDesc,
    //                    esa.idArea,
    //                    s.subProyectoDesc
    //               FROM (po_cambio pc,
    //                    estacion e,
    //                    subproyecto s,
    //                    subproyectoestacion se,
    //                    detalleplan dp,
    //                    estacionarea esa)
    //          LEFT JOIN planobra_po ppo 
    //                 ON (pc.codigo_po  = ppo.codigo_po AND ppo.codigo_po = dp.poCod)
    //              WHERE e.idEstacion  = pc.idEstacion
    //                AND s.idSubProyecto = se.idSubProyecto
    //                AND dp.idSubProyectoEstacion = se.idSubProyectoEstacion
    //                AND esa.idEstacionArea = se.idEstacionArea";
    //     $result = $this->db->query($sql);
    //     return $result->result_array();     
    // }

    function insertData($arrayData) {
        $this->db->insert('po_cambio', $arrayData);

        if($this->db->affected_rows() != 1) {
            return array('error' => EXIT_ERROR, 'msj' => 'error, no se insert&oacute.');
        } else {
            return array('error' => EXIT_SUCCESS, 'msj' => 'Se insert&oacute correctamente.');
        }
    }

    function generarPoComplej($itemplan, $idEstacion, $idUsuario, $nroAmp, $nroTroba, $idTipoComplejidad, $codigo_po) {
        $sql = "SELECT fn_insertPODiseno_complejidad(?, ?, ?, ?, ?, ?, ?) AS resp";
        $result = $this->db->query($sql, array($itemplan, $idEstacion, $idUsuario, $nroAmp, $nroTroba, $idTipoComplejidad, $codigo_po));
        return $result->row_array()['resp'];
    }
}