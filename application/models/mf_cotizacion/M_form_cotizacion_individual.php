<?php
class M_form_cotizacion_individual extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
    }

    function insertBandejaCotizacionValidacion($arrayData) {
        $this->db->insert('cotizacion_validar', $arrayData);

        if($this->db->affected_rows() != 1) {
            return array('error' => EXIT_ERROR, 'msj' => 'No se ingreso a la bandeja Validacion');
        } else {
            return array('error' => EXIT_SUCCESS, 'msj' => 'Se ingreso correctamente');
        }
    }
}