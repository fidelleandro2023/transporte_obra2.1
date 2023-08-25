<?php
class M_adm_cuadrilla extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
    }

    function registrarCuadrilla($idEecc, $idZonal, $nomCuadrilla, $fecha) {
			$arrayUsuario = array('nombre'    => $nomCuadrilla,
														'usuario'   => $nomCuadrilla,
														'pass'      => 123,
														'id_perfil' => 12 ,
														'id_eecc'   => $idEecc,
														'zonas'     => $idZonal,
														'id_usuario_tipo'   => 1,
													  'id_usuario_estado' => 1);
				$this->db->insert('usuario', $arrayUsuario);
			
				 $idUsuario = $this->db->insert_id();
				 
				 if($idUsuario == 0 || $idUsuario == null) {
						throw new Exception('ND');
				 }

				$arrayDataInsert = array(
					'idEecc'        => $idEecc,
					'idZonal'       => $idZonal,
					'descripcion'   => $nomCuadrilla,
					'estado'        => 1,
					'id_usuario'    => $idUsuario,
					'fechaRegistro' => $fecha
				);
        $this->db->insert('cuadrilla', $arrayDataInsert);

        if($this->db->affected_rows() == 0) {
			return 0;
		} else {
			return 1;
		}
    }
    
    public function getCountUsuario($usuario)
    {
        $sql = " SELECT COUNT(usuario) AS conteo FROM usuario WHERE usuario = '" . $usuario . "'";
        $result = $this->db->query($sql);
        return $result->row()->conteo;
    }
}