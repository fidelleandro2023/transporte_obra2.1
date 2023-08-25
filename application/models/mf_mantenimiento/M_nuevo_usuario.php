<?php
class M_nuevo_usuario extends CI_Model
{
    //http://www.codeigniter.com/userguide3/database/results.html
    function __construct()
    {
        parent::__construct();
    }

    function   getUsuario()
    {
        $Query = "  SELECT usuario.id_usuario,usuario.usuario,usuario.nombres,usuario.ape_paterno,
                usuario.ape_materno,usuario.pass,usuario_tipo.nombre_usuario_tipo,
                usuario_estado.nombre_usuario_estado,usuario.id_eecc,perfil.desc_perfil,usuario.estado,usuario.firma
                FROM usuario,usuario_tipo,usuario_estado,perfil
                WHERE usuario.id_usuario_tipo=usuario_tipo.id_usuario_tipo
                AND usuario.id_usuario_estado=usuario_estado.id_usuario_estado
                AND usuario.id_perfil=perfil.id_perfil
                AND usuario.id_usuario!=1
                AND usuario.id_usuario!=2
                ORDER BY usuario.id_usuario ASC";
        $result = $this->db->query($Query, array());
        return $result;
    }
    function insertUsuario($nombre, $usuario, $password, $empresa, $perfiles, $nombres, $materno, $paterno, $dni, $email, $zonas, $accesoSINFIX, $firma)
    {

        //INSERT INTO `usuario` (`id_usuario`, `nombre`, `usuario`, `pass`, `id_usuario_tipo`, `id_usuario_estado`, `id_eecc`, `id_perfil`, `nombres`, `ape_materno`, `ape_paterno`, `estado`, `dni`, `email`) VALUES (NULL, 'Claudio Pizarro', 'cpizarro', '123', '1', '1', '0', '3', 'Claudio', 'Pizarro', 'Materno', '1', '12345678', 'cpizarro@email.com');

        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try {

            $data = array(
                'nombre' => $nombre,
                'usuario' => $usuario,
                'pass' => $password,
                'id_usuario_tipo' => 1,
                'id_usuario_estado' => 1,
                'id_eecc' => $empresa,
                'id_perfil' => $perfiles,
                'nombres' => $nombres,
                'ape_materno' => $materno,
                'ape_paterno' => $paterno,
                'estado' => 1,
                'dni' => $dni,
                'email' => $email,
                'zonas' => $zonas,
                'idUsuarioSinfix' => $accesoSINFIX,
                'firma' => $firma
            );

            $this->db->insert('usuario', $data);
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        //echo json_encode(array_map('utf8_encode', $data));

    }

    public function verificaUsername($username)
    {

        $sql = "SELECT COUNT(1) AS conteo FROM usuario WHERE trim(usuario) LIKE '%" . $username . "%'";

        $result = $this->db->query($sql);

        if ($result->row() != null) {
            return $result->row()->conteo;
        } else {
            return null;
        }
    }
}
