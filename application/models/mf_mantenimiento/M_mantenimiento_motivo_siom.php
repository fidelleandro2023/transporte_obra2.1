<?php
class M_mantenimiento_motivo_siom extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
    }

    function getDataMotivoSiom() {
        $sql = " SELECT mo.id,
                        descripcion,
                        GROUP_CONCAT(tm.nomTipoMotivo) AS tipo,
                        CASE WHEN estado = 1 THEN 'ACTIVO'
                                            ELSE 'DESACTIVADO' END estado,
                        fecha_registro,
                        id_usuario_reg
                   FROM motivo_observacion_validacion mo,
                        tipo_motivo_siom tm
                  WHERE FIND_IN_SET(tm.id, mo.flg_tipo)  
                GROUP BY mo.id";
        $result = $this->db->query($sql);
        return $result->result_array();
    }

    function insertMotivo($dataArray) {
        $this->db->insert('motivo_observacion_validacion', $dataArray);

        if($this->db->affected_rows() != 1) {
            $data['error'] = EXIT_ERROR;
            $data['msj']   = 'Error al ingresar el motivo, intentar nuevamente'; 
        } else {
            $data['error'] = EXIT_SUCCESS;
        }

        return $data;
    }

    function getDataMotivoMantSiom($idMotivo) {
        $sql = " SELECT mo.id,
                        descripcion,
                        GROUP_CONCAT(tm.id) AS tipo,
                        estado,
                        fecha_registro,
                        id_usuario_reg
                   FROM motivo_observacion_validacion mo,
                        tipo_motivo_siom tm
                  WHERE FIND_IN_SET(tm.id, mo.flg_tipo)
                    AND mo.id = COALESCE(?, mo.id) 
                GROUP BY mo.id";
        $result = $this->db->query($sql, array($idMotivo));
        return $result->row_array();
    }

    function actualizarMotivoSiom($idMotivoMantenimiento, $dataUpdate) {
        $this->db->where('id', $idMotivoMantenimiento);
        $this->db->update('motivo_observacion_validacion', $dataUpdate);

        if($this->db->affected_rows() != 1) {
            $data['error'] = EXIT_ERROR;
            $data['msj']   = 'Error al actualizar el motivo, intentar nuevamente'; 
        } else {
            $data['error'] = EXIT_SUCCESS;
        }

        return $data;
    }
}