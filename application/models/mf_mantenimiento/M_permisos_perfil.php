<?php
class M_permisos_perfil extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
    
    function getAllPermisosPerfil(){
	    $Query = "SELECT perprf.idpermisos_x_perfil as id, 
					prf.desc_perfil as perfil, 
					per.descripcion as permiso 
					from permisos per, permisos_x_perfil perprf left join perfil prf 
					on prf.id_perfil=perprf.id_perfil where perprf.id_permiso=per.id_permiso
					order by prf.desc_perfil,per.descripcion ;";

	    $result = $this->db->query($Query,array());
	    return $result;
	}

	
	
function insertarPermisoPerfil($id_permiso,$id_perfil){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	    	
			$this->db->trans_begin();
	    	 $dataInsert = array(
	            "id_permiso" => $id_permiso,
	           "id_perfil" => $id_perfil
	        );

	    	$this->db->insert('permisos_x_perfil', $dataInsert);
	        if($this->db->affected_rows() != 1) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar el permiso por perfil');
	        }else{
	            $this->db->trans_commit();
	            $data['error']    = EXIT_SUCCESS;
	            $data['msj']      = 'Se inserto correctamente!';
	        }
	        
	         
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
	
	/*OBTIENE DATOS DEL PLAN PARA LA EDICION*/

	function   getPermisoPerfilInfo($id){
	    $Query = " SELECT * 
	                 FROM permisos_x_perfil where idpermisos_x_perfil = ?";	
	    $result = $this->db->query($Query,array($id));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
	
	function editarPermisoPerfil($id_permisoperfil, $id_perfil, $id_permiso){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
        	$this->db->trans_begin();
        	$dataUpdate = array(
	           "id_permiso" => $id_permiso,
	           "id_perfil" => $id_perfil
	        );
        	
        	$this->db->where('idpermisos_x_perfil', $id_permisoperfil);
        	$this->db->update('permisos_x_perfil', $dataUpdate);
            	if ($this->db->trans_status() === FALSE) {
            	    throw new Exception('Hubo un error al actualizar en editarPermisoPerfil');
            	}else{
            	    $data['error']    = EXIT_SUCCESS;
            	    $data['msj']      = 'Se actualizo correctamente!';
            	    $this->db->trans_commit();
            	}
            
    	}catch(Exception $e){
    	    $data['msj']   = $e->getMessage();
    	    $this->db->trans_rollback();
    	}
        	return $data;
	}


	function eliminarPermisoPerfil($id_permisoperfil){
		 $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	    	
        	$this->db->trans_begin();
        	$this->db->where('idpermisos_x_perfil', $id_permisoperfil);
			$this->db->delete('permisos_x_perfil');

            	if ($this->db->trans_status() === FALSE) {
            	    throw new Exception('Hubo un error al eliminar en editarPermisoPerfil');
            	}else{
            	    $data['error']    = EXIT_SUCCESS;
            	    $data['msj']      = 'Se eliminó correctamente!';
            	    $this->db->trans_commit();
            	}
            
    	}catch(Exception $e){
    	    $data['msj']   = $e->getMessage();
    	    $this->db->trans_rollback();
    	}
        	return $data;

	}


	function   existePermisoxPerfil($permiso,$perfil){
	    $sql = "SELECT COUNT(1) cant
    	              FROM permisos_x_perfil
    	             WHERE id_permiso = ".$permiso.
    	             " AND id_perfil =".$perfil.
    	             " LIMIT 1";	   
	    $result = $this->db->query($sql,array());
	    return $result;
	}

	
}