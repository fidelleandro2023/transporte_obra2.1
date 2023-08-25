<?php
class M_valereserva_eecc extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
    
    function getAllVREmpresaCC(){
	    $Query = "SELECT vreecc.codigo_vr, eecc.empresaColabDesc 
					FROM valereserva_x_empresacolab vreecc, empresacolab eecc
					where eecc.idEmpresaColab=vreecc.idEmpresaColab
					order by eecc.empresaColabDesc, vreecc.codigo_vr;";

	    $result = $this->db->query($Query,array());
	    return $result;
	}

	
	
function insertarValeReservaEECC($valereserva,$ideecc){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	    	
			$this->db->trans_begin();
    	 
	    	 $dataInsert = array(
	            "codigo_vr" => $valereserva,
	           "idEmpresaColab" => $ideecc
	        );

	    	$this->db->insert('valereserva_x_empresacolab', $dataInsert);
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

	function   getValeRerservaEECC($valereserva){
	    $Query = " SELECT * 
					FROM valereserva_x_empresacolab WHERE codigo_vr= ?";	
	    $result = $this->db->query($Query,array($valereserva));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
	
	function editarValeReservaEECC($valereserva,$ideecc){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	    	
        	$this->db->trans_begin();

        	        	 
        	$dataUpdate = array(
	            "idEmpresaColab" => $ideecc
	        );
        	 
        	$this->db->where('codigo_vr', $valereserva);
        	$this->db->update('valereserva_x_empresacolab', $dataUpdate);
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


	function eliminarValeReservaEECC($valereserva){
		 $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	    	
        	$this->db->trans_begin();

        	$this->db->where('codigo_vr', $valereserva);
			$this->db->delete('valereserva_x_empresacolab');

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


	function   existeValeResevaEECC($valereserva){
	    $sql = " SELECT COUNT(1) as count
					FROM valereserva_x_empresacolab WHERE codigo_vr= ?";   
	    $result = $this->db->query($sql,array($valereserva));
	    return $result->row()->count;
	    
	}

	
}