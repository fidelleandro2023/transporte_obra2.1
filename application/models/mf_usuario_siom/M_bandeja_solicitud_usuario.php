<?php
class M_bandeja_solicitud_usuario extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
    }

    function getBandejaSolicitudUsuario($dni, $idEmpresaColab, $estado, $idECCsesion=null) {
        $sql = " SELECT s.idSolicitudUsuario,
                        UPPER(s.nombre) nombre, 
                        c.nombre AS contratoDesc,
                        GROUP_CONCAT(DISTINCT pe.desc_perfil) arrayPerfilDesc,
                        GROUP_CONCAT(DISTINCT z.nombre) arrayZonaDesc,
                        e.empresaColabDesc,
                        s.dni,
                        s.email,
                        s.telefono,
                        s.imei,
                        s.usuario,
						s.clave,
                        s.flg_tipo_solicitud,
                        CASE WHEN estado = 1 THEN 'PENDIENTE'
                             WHEN estado = 2 THEN 'APROBADO'
                             WHEN estado = 3 THEN 'RECHAZADO' END estado,
                        CASE WHEN flg_tipo_solicitud = 1 THEN 'CREACION USUARIO'
                             WHEN flg_tipo_solicitud = 2 THEN 'MODIFICACION USUARIO'
                             WHEN flg_tipo_solicitud = 3 THEN 'BAJA USUARIO' END flg_tipo_estado,
						s.codigo,
						s.observacion_rechazo,
                        s.fecha_registro,
                        s.fecha_aprob,
                        e.empresaColabDesc					
                   FROM solicitud_usuario s,
                        contratos c,
                        perfil pe,
                        zona z,
                        empresacolab e
                  WHERE s.idContrato = c.id_contratos      
                    AND FIND_IN_SET(pe.id_perfil, s.array_perfil)
                    AND FIND_IN_SET(z.id_zona, s.array_zona)
                    AND e.idEmpresaColab = s.idEmpresaColab
                    AND s.dni            = COALESCE(?, s.dni)
                    AND s.idEmpresaColab = COALESCE(?, s.idEmpresaColab)
                    AND s.estado         = COALESCE(?, s.estado)
                    AND CASE WHEN ? IS NULL OR ? = 0 OR ? = 6 THEN true 
                             ELSE ? = s.idEmpresaColab END
                GROUP BY idSolicitudUsuario
                ORDER BY idSolicitudUsuario";
        $result = $this->db->query($sql, array($dni, $idEmpresaColab, $estado, $idECCsesion, $idECCsesion, $idECCsesion ,$idECCsesion));
        return $result->result_array();
    }

    function insertDataUsuario($idSolicitud, $idUsuario, $fechaActual, $usuario, $clave) {
        $sql = "INSERT usuario_siom
                 SELECT dni, 
                        nombre,
                        ?,
                        ?,
                        idEmpresaColab,
                        array_perfil,
                        1,
                        email,
                        array_zona,
                        telefono,
                        imei,
                        idContrato,
                        ?,
                        ?
                    FROM solicitud_usuario
                   WHERE idSolicitudUsuario = ?";
        $this->db->query($sql, array($usuario, $clave, $fechaActual, $idUsuario, $idSolicitud));
        // _log($this->db->last_query());
        if($this->db->affected_rows() != 1) {
            $data['error']    = EXIT_ERROR;
            $data['msj']      = 'No se ingresó el usuario!';
        } else {
            $data['error']    = EXIT_SUCCESS;
            $data['msj']      = 'Se agrego correctamente!';
        }
        return $data;
    }

    function modificacionUsuario($idSolicitudUsuario) {
        $sql = " UPDATE usuario_siom u, solicitud_usuario s
                    SET u.id_eecc = s.idEmpresaColab,
                        u.id_perfil = s.array_perfil,
                        u.email     = s.email,
                        u.zonas     = s.array_zona,
                        u.idContrato = s.idContrato,
                        u.imei       = s.imei,
                        u.estado     = s.estado_usuario,
						u.celular   = s.telefono,
                        u.dni        = s.dni
                  WHERE u.dni = s.dni     
                    AND s.idSolicitudUsuario = ?";
        $this->db->query($sql, array($idSolicitudUsuario));

        if($this->db->affected_rows() < 1) {
            $data['error']    = EXIT_ERROR;
            $data['msj']      = 'No se modifico el usuario, ya que no hay nada que modificar!';
        } else {
            $data['error']    = EXIT_SUCCESS;
            $data['msj']      = 'Se modificó correctamente!';
        }
        return $data;
    }

    function bajaUsuario($idSolicitudUsuario) {
        $sql = " UPDATE usuario_siom u, solicitud_usuario s
                    SET u.estado = 0
                  WHERE u.dni = s.dni     
                    AND s.idSolicitudUsuario = ?";
        $this->db->query($sql, array($idSolicitudUsuario));

        if($this->db->affected_rows() < 1) {
            $data['error']    = EXIT_ERROR;
            $data['msj']      = 'No se dio de baja al usuario, ya que no hay nada que modificar!';
        } else {
            $data['error']    = EXIT_SUCCESS;
            $data['msj']      = 'Se modificó correctamente!';
        }
        return $data;
    }

    function updateSolicitud($idSolicitudUsuario, $arrayUpdate) {
        $this->db->where('idSolicitudUsuario', $idSolicitudUsuario);
        $this->db->update('solicitud_usuario', $arrayUpdate);

        if($this->db->affected_rows() != 1) {
            $data['error']    = EXIT_ERROR;
            $data['msj']      = 'No se ingresó el usuario!';
        } else {
            $data['error']    = EXIT_SUCCESS;
            $data['msj']      = 'Se agrego correctamente!';
        }
        return $data;
    }
	
	function countUsuarioByusuario($usuario) {
		$sql = "SELECT COUNT(1) count  
		          FROM usuario_siom
				 WHERE UPPER(usuario) = UPPER(?)";
		$result = $this->db->query($sql, array($usuario));
		return $result->row_array()['count'];
	}
}