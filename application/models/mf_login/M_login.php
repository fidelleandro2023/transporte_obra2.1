<?php
class M_login extends CI_Model
{
    //http://www.codeigniter.com/userguide3/database/results.html
    public function __construct()
    {
        parent::__construct();

    }

    /*
    function   getUserInfo($usuario, $clave){
    $Query = "  SELECT     p.id_eecc, p.id_usuario, p.nombre, p.id_perfil, r.desc_perfil, p.usuario, p.zonas, p.idUsuarioSinfix, p.email
    FROM     usuario p, perfil r
    WHERE     p.id_perfil = r.id_perfil
    AND     p.estado=1
    AND        UPPER(p.usuario) = UPPER(?)
    AND     p.pass = ?";
    $result = $this->db->query($Query,array($usuario, $clave));
    if($result->row() != null) {    //
    return $result->row_array();
    } else { 
    return null;
    }
    }

     */

    public function getUserInfo($usuario)
    {
        $Query = "  SELECT 	p.id_eecc, p.id_usuario, p.pass, p.nombre, p.nombres, p.ape_paterno, p.id_perfil, r.desc_perfil, p.usuario, p.zonas, p.idUsuarioSinfix, p.email, r.flg_perfil_gestion_mantenimiento
                    FROM 	usuario p, perfil r
                    WHERE 	p.id_perfil = r.id_perfil
                    AND     p.estado=1
                    AND		UPPER(p.usuario) = UPPER(?)";

        $result = $this->db->query($Query, array($usuario));
        if ($result->row() != null) { 
            return $result;
        } else {
            return null;
        }
    }

    public function getPermisosPadre($id_rol, $flg_panel=null)
    {
        /*
        $Query = "  SELECT DISTINCT  p.id_padre, (SELECT descripcion
        FROM permisos
        WHERE id_permiso = p.id_padre) as descripcion
        FROM    perfil r,permisos_x_perfil pr,permisos p
        WHERE   r.id_perfil = pr.id_perfil
        AND        pr.id_permiso = p.id_permiso
        AND     r.id_perfil = ? AND p.id_padre IS NOT NULL ORDER BY p.id_padre";*/

        $Query = "  SELECT p.id_padre, (SELECT descripcion
                                                    FROM permisos
                                                   WHERE id_permiso = p.id_padre) as descripcion,
                           p.icono, fg_modulo, visible_fg
                    FROM    perfil r,permisos_x_perfil pr,permisos p
                    WHERE   r.id_perfil = pr.id_perfil
                    AND    	pr.id_permiso = p.id_permiso
                    AND     r.id_perfil IN (" . $id_rol . ") AND p.id_padre IS NOT NULL
                    AND p.id_padre != ''
                    AND CASE WHEN ? = 2 THEN true 
                             ELSE flg_panel = ? END
                    GROUP BY p.id_padre
                    ORDER BY descripcion";

        $result = $this->db->query($Query, array($flg_panel, $flg_panel));
		//_log($this->db->last_query());
        return $result;
    }

    public function getPermisosHijos($id_rol, $id_padre, $flg_panel = null)
    {
        /*
        $Query = " SELECT  p.id_permiso, p.descripcion, p.route
        FROM    perfil r,permisos_x_perfil pr,permisos p
        WHERE   r.id_perfil = pr.id_perfil
        AND     pr.id_permiso = p.id_permiso
        AND     r.id_perfil    = ?
        AND     p.id_padre  = ? ORDER BY p.orden";*/

        $Query = " SELECT DISTINCT p.id_permiso, p.descripcion, p.route
	                 FROM perfil r,permisos_x_perfil pr,permisos p
	                WHERE r.id_perfil = pr.id_perfil
	                  AND pr.id_permiso = p.id_permiso
                      AND r.id_perfil IN (" . $id_rol .")
                      AND CASE WHEN ? = 2 THEN true 
                               ELSE flg_panel = ? END  
                      AND p.id_padre  = " . $id_padre . " ORDER BY p.descripcion";

        $result = $this->db->query($Query, array($flg_panel, $flg_panel));
        return $result;
    }

    public function getVerificaPassword($usuario)
    {
        $Query = "  SELECT 	p.flagCambio
                    FROM 	usuario p
                    WHERE 	p.estado=1
                    AND		UPPER(p.usuario) = UPPER(?)";

        $result = $this->db->query($Query, array($usuario));
        if ($result->row() != null) { 
            return $result->row()->flagCambio;
        } else {
            return null;
        }
    }

    public function cambioContrasena($usuario, $dni, $nuevopassword)
    {
        $dataSalida['error'] = EXIT_ERROR;
        $dataSalida['msj'] = null;
        try {

            $this->db->trans_begin();

            $passhas = password_hash($nuevopassword, PASSWORD_DEFAULT);

            $data = array(
                "pass" => $passhas,
                "flagCambio" => 1,
            );

            $this->db->where('usuario', $usuario);
            /* $this->db->where('dni', $dni);*/
            $this->db->update('usuario', $data);
            //Fin
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
            } else {
                $dataSalida['error'] = EXIT_SUCCESS;
                $dataSalida['msj'] = 'Se actualizo correctamente!';
                $this->db->trans_commit();
            }

        } catch (Exception $e) {
            $dataSalida['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $dataSalida;
    }

public function getIdPerfil($user) {
        $sql = "SELECT
                p.id_perfil
                FROM
                usuario p
                WHERE 
                UPPER(p.usuario) = UPPER('$user');";
        $result = $this->db->query($sql);
        if ($result->row()->id_perfil) {
            return $result->row()->id_perfil;
        } else {
            return 0;
        }
    }

}
