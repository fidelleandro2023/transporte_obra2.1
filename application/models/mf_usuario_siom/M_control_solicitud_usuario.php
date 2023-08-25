<?php
class M_control_solicitud_usuario extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
    }

    function getDataTablaControlSolUSiom($idEmpresaColab) {
        $sql = "SELECT pe.id_perfil,
                       pe.desc_perfil,
                       COUNT(u.dni) usuariosAct
                  FROM perfil pe 
             LEFT JOIN usuario_siom u ON (FIND_IN_SET(pe.id_perfil, u.id_perfil) 
                                         AND u.estado = 1
                                         AND CASE WHEN ? IS NULL OR ? = 0 OR ? = 6 THEN true 
                                                  ELSE ? = u.id_eecc END)
                 WHERE pe.flg_solicitud_usua_siom = 1
                GROUP BY pe.id_perfil";
        $result = $this->db->query($sql, array($idEmpresaColab, $idEmpresaColab, $idEmpresaColab, $idEmpresaColab));
        return $result->result_array();        
    }

    function getDataTablaUsuariosActivos($idPerfil, $idEmpresaColab) {
        $sql = "SELECT dni,
                       nombre,
                       usuario,
                       email,
                       celular,
                       imei,
                       fecha_registro,
                       id_perfil,
                       empresaColabDesc
                  FROM usuario_siom u,
                       empresacolab e
                 WHERE estado = 1
                   AND idEmpresaColab = id_eecc
                   AND FIND_IN_SET(?, id_perfil)
                   AND CASE WHEN ? IS NULL OR ? = 0 OR ? = 6 THEN true 
                            ELSE ? = id_eecc END";
        $result = $this->db->query($sql, array($idPerfil, $idEmpresaColab, $idEmpresaColab,
                                               $idEmpresaColab, $idEmpresaColab));
        return $result->result_array();        
    }
}
