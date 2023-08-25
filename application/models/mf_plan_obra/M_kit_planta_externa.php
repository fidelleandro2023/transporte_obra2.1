<?php
class M_kit_planta_externa extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
    }

    function insertMaterial($idSubProyecto, $idMaterial, $cantidadKit, $factorPorcentual, $idEstacion) {
        $this->db->insert('kit_material', array('id_material'       => $idMaterial,
                                                'idSubProyecto'     => $idSubProyecto,
                                                'cantidad_kit'      => $cantidadKit,
                                                'factor_porcentual' => $factorPorcentual,
                                                'idEstacion'        => $idEstacion));
        if($this->db->affected_rows() != 1) {
            return array('error' => EXIT_ERROR, 'msj' => 'error No se ingreso el material');
        }else{
            return array('error' => EXIT_SUCCESS);
        }
    }

    function eliminarMaterial($idSubProyecto, $idMaterial, $idEstacion) {
        $this->db->where('idSubProyecto', $idSubProyecto);
        $this->db->where('idEstacion'   , $idEstacion);
        $this->db->where('id_material'  , $idMaterial);
        $this->db->delete('kit_material');
        if($this->db->affected_rows() != 1) {
            return array('error' => EXIT_ERROR, 'msj' => 'error No se ingreso el material');
        }else{
            return array('error' => EXIT_SUCCESS);
        }
    }

    function insertMasivoKit($arrayData) {
        $this->db->insert_batch('kit_material', $arrayData);

        if($this->db->affected_rows() < 0) {
            _log("ENTRO");
            return array('error' => EXIT_ERROR, 'msj' => 'NO SE INGRESO EL KIT');
        } else {
            _log("ENTRO2");
            return array('error' => EXIT_SUCCESS, 'msj' => 'SE INGRESO EL KIT CORRECTAMENTE');
        }
    }
}