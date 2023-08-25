<?php
class M_usuario extends CI_Model
{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct()
	{
		parent::__construct();
	}

	function   getUsuario()
	{
		$Query = "  SELECT u.id_usuario,u.usuario,u.nombres,u.ape_paterno,
                u.ape_materno,u.pass, u.id_perfil, u.dni, u.email, u.zonas,
                u.id_eecc,u.estado, (SELECT ec.empresaColabDesc FROM empresacolab ec WHERE ec.idEmpresaColab = u.id_eecc) AS eecc,
                u.id_perfil AS perfil,
				u.firma
                FROM usuario u
                WHERE u.id_usuario!=1
                AND u.id_usuario!=2
                ORDER BY u.id_usuario ASC";
		$result = $this->db->query($Query, array());
		return $result;
	}
	function insertUsuario($nombres, $paterno, $materno, $pass)
	{

		$data['error']    = EXIT_ERROR;
		$data['msj']      = null;
		$data['cabecera'] = null;
		try {
			$data = array(

				'nombres' => $nombres,
				'ape_paterno' => $paterno,
				'ape_materno' => $materno,
				'pass' => $pass
			);


			$this->db->insert('usuario', $data);
		} catch (Exception $e) {
			$data['msj'] = $e->getMessage();
		}
		echo json_encode(array_map('utf8_encode', $data));
	}

	function updateUsuEstadoD($id)
	{
		$data['error'] = EXIT_ERROR;
		$data['msj']   = null;
		try {
			$data = array(

				'estado' => '0'
			);
			$this->db->where('id_usuario', $id);

			$this->db->update('usuario', $data);
		} catch (Exception $e) {
			$data['msj']   = $e->getMessage();
		}
		return $data;
	}

	//ACTIVAR
	function updateUsuEstadoA($id)
	{
		$data['error'] = EXIT_ERROR;
		$data['msj']   = null;
		try {
			$data = array(

				'estado' => '1'

			);
			$this->db->where('id_usuario', $id);

			$this->db->update('usuario', $data);
		} catch (Exception $e) {
			$data['msj']   = $e->getMessage();
		}
		return $data;
	}

	function   getUsuarioById($idUsuario)
	{
		$Query = "SELECT * FROM usuario where id_usuario = ?;";
		$result = $this->db->query($Query, array($idUsuario));
		if ($result->row() != null) {
			return $result->row_array();
		} else {
			return null;
		}
	}

	function updateUsuario($id, $nombres, $dni, $apePaterno, $apeMaterno, $email, $empresa, $perfil, $zonas, $user, $pass, $accesoSINFIX, $firma = null)
	{
		$data['error'] = EXIT_ERROR;
		$data['msj']   = null;
		try {

			$this->db->trans_begin();

			if (trim($pass) == "") {
				$dataUpdate = array(
					'nombres' => strtoupper($nombres),
					'dni' => $dni,
					'ape_paterno' => strtoupper($apePaterno),
					'ape_materno' => strtoupper($apeMaterno),
					'email' => strtoupper($email),
					'id_eecc' => $empresa,
					'id_perfil' => implode(",", $perfil),
					'zonas' => implode(",", $zonas),
					'idUsuarioSinfix' => $accesoSINFIX,
					'usuario' => $user,
					'flagCambio' => 1,
		            'firma'=>$firma
				);
			} else {
				$dataUpdate = array(
					'nombres' => strtoupper($nombres),
					'dni' => $dni,
					'ape_paterno' => strtoupper($apePaterno),
					'ape_materno' => strtoupper($apeMaterno),
					'email' => strtoupper($email),
					'id_eecc' => $empresa,
					'id_perfil' => implode(",", $perfil),
					'zonas' => implode(",", $zonas),
					'pass' => password_hash($pass, PASSWORD_DEFAULT),
					'idUsuarioSinfix' => $accesoSINFIX,
					'usuario' => $user,
					'flagCambio' => 1,
		            'firma'=>$firma
				);
			}



			$this->db->where('id_usuario', $id);
			$this->db->update('usuario', $dataUpdate);

			if ($this->db->trans_status() === FALSE) {
				throw new Exception('Hubo un error al actualizar el usuario.');
			} else {
				$data['error']    = EXIT_SUCCESS;
				$data['msj']      = 'Se actualizo correctamente!';
				$this->db->trans_commit();
			}
		} catch (Exception $e) {
			$data['msj']   = $e->getMessage();
			$this->db->trans_rollback();
		}
		return $data;
	}

	function updatePermisoNivelesValid($arrayInsertPermisoValid)
	{
		$this->db->where('idUsuario', $id);
		$this->db->delete('usuario_validador_pqt');

		if ($this->db->trans_status() === FALSE) {
			throw new Exception('Hubo un error al actualizar el usuario.');
		} else {
			$this->db->insert_batch('usuario_validador_pqt', $arrayInsertPermisoValid);

			if ($this->db->trans_status() === FALSE) {
				$data['error'] = EXIT_ERROR;
				$data['msj'] = 'Hubo un error al actualizar el usuario.';
			} else {
				$data['error'] = EXIT_SUCCESS;
				$data['msj'] = 'Hubo un error al actualizar el usuario.';
			}
		}

		return $data;
	}

	function updateRestricciones($arrayDataRestricciones)
	{
		$this->db->where('idUsuario', $id);
		$this->db->delete('usuario_x_tipo_restriccion');

		if ($this->db->trans_status() === FALSE) {
			throw new Exception('Hubo un error al actualizar el usuario.');
		} else {
			$this->db->insert_batch('usuario_x_tipo_restriccion', $arrayDataRestricciones);

			if ($this->db->trans_status() === FALSE) {
				$data['error'] = EXIT_ERROR;
				$data['msj'] = 'Hubo un error al actualizar el usuario.';
			} else {
				$data['error'] = EXIT_SUCCESS;
				$data['msj'] = 'Hubo un error al actualizar el usuario.';
			}
		}

		return $data;
	}

	function getDataValidaCerti($idUsuario)
	{
		$sql = "SELECT idUsuario,
					   GROUP_CONCAT(DISTINCT idProyecto)idProyecto,
					   GROUP_CONCAT(DISTINCT idJefatura) arrayJefatura,
					   GROUP_CONCAT(DISTINCT nivel_validacion) arrayNivelValidacion
		          FROM usuario_validador_pqt
				 WHERE idUsuario = ?";
		$result = $this->db->query($sql, array($idUsuario));
		return $result->row_array();
	}

	function getUsuarioRestriccionByUsuario($idUsuario, $estado)
	{
		$sql = "SELECT GROUP_CONCAT(DISTINCT ut.id_tipo_restriccion)id_tipo_restriccion,  
					   descripcion as restriccionDesc
				  FROM tipo_restriccion t,
					   usuario_x_tipo_restriccion ut
			     WHERE t.id_tipo_restriccion = ut.id_tipo_restriccion
				   AND ut.idUsuario = ?
				   AND t.estado = ? ";
		$result = $this->db->query($sql, array($idUsuario, $estado));
		return $result->row_array();
	}
}
