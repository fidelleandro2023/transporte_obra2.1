<?php
class M_form_solicitud_usuario extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
    }

    function insertSolicitudUsuario($dataArray) {
        $this->db->insert('solicitud_usuario',$dataArray);
        if($this->db->affected_rows() != 1) {
            $data['error']    = EXIT_ERROR;
            $data['msj']      = 'No se ingres&oacute; por problemas internos!';
        } else {
            $data['error']    = EXIT_SUCCESS;
            $data['msj']      = 'Se ingres&oacute; correctamente!';
        }
        return $data;    
    }

    function getDataModificacion($dni) {
        $sql = " SELECT u.nombre,
                        u.idContrato,
                        u.id_perfil as array_perfil,
                        u.id_eecc as idEmpresaColab,
                        u.dni,
                        u.email,
                        u.celular as telefono,
                        u.imei,
                        u.zonas as array_zona,
                        u.usuario,
                        u.estado
                   FROM usuario_siom u
                  WHERE u.dni    = ?";
        $result = $this->db->query($sql, array($dni));
        return $result->row_array();          
    }
	
	function getCodigoSolicitud() {
        $sql = "(SELECT CASE WHEN codigo IS NULL OR codigo = '' THEN CONCAT(YEAR(NOW()),(SELECT ROUND(RAND()*100000)))
                             ELSE MAX(codigo)+1 END AS codigo
                   FROM solicitud_usuario)";
        $result = $this->db->query($sql);
        return $result->row()->codigo;
    }
}