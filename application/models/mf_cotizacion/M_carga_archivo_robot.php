<?php
class M_carga_archivo_robot extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
    }

    function insertCtoMasivo($arrayData) {
        // $this->db->delete('cto_ubicacion');
        $sql = "DELETE FROM cto_ubicacion";

        $this->db->query($sql);

        if ($this->db->trans_status() === FALSE) {
            return array('error' => EXIT_ERROR, 'msj' => 'ERROR AL INGRESAR LA CTO.');
        } else {
            $this->db->insert_batch('cto_ubicacion', $arrayData);

            if($this->db->affected_rows() == 0) {
                _log("ENTRO");
                return array('error' => EXIT_ERROR, 'msj' => 'NO SE INGRESO LAS CTOS');
            } else {
                _log("ENTRO2");
                return array('error' => EXIT_SUCCESS, 'msj' => 'SE INGRESO LAS CTOS');
            }
        }
    }

    function insertReservaMasivo($arrayData) {
        $sql = "DELETE FROM reservas_ubicacion";

        $this->db->query($sql);

        if ($this->db->trans_status() === FALSE) {
            return array('error' => EXIT_ERROR, 'msj' => 'ERROR AL INGRESAR LA CTO.');
        } else {
            $this->db->insert_batch('reservas_ubicacion', $arrayData);

            if($this->db->affected_rows() == 0) {
                return array('error' => EXIT_ERROR, 'msj' => 'NO SE INGRESO LA RESERVA');
            } else {
                return array('error' => EXIT_SUCCESS, 'msj' => 'SE INGRESARON LAS RESERVAS');
            }
        }
    }
	
	function insertEbcEdifMasivo($arrayData) {
        // $this->db->delete('cto_ubicacion');
        $sql = "DELETE FROM ebc_ubicacion";

        $this->db->query($sql);

        if ($this->db->trans_status() === FALSE) {
            return array('error' => EXIT_ERROR, 'msj' => 'ERROR AL INGRESAR LA EBC.');
        } else {
            $this->db->insert_batch('ebc_ubicacion', $arrayData);
			_log("CANT: ".$this->db->affected_rows());
            if($this->db->affected_rows() == 0) {
                return array('error' => EXIT_ERROR, 'msj' => 'NO SE INGRESO LAS EBC');
            } else {
                return array('error' => EXIT_SUCCESS, 'msj' => 'SE INGRESO LAS EBC');
            }
        }
    }
	
	function insertCtoEdifMasivo($arrayData) {
        // $this->db->delete('cto_ubicacion');
        $sql = "DELETE FROM cto_ubicacion_edificio";

        $this->db->query($sql);

        if ($this->db->trans_status() === FALSE) {
            return array('error' => EXIT_ERROR, 'msj' => 'ERROR AL INGRESAR LA CTO.');
        } else {
            $this->db->insert_batch('cto_ubicacion_edificio', $arrayData);

            if($this->db->affected_rows() == 0) {
                return array('error' => EXIT_ERROR, 'msj' => 'NO SE INGRESO LAS CTOS');
            } else {
                return array('error' => EXIT_SUCCESS, 'msj' => 'SE INGRESO LAS CTOS');
            }
        }
    }
}