<?php
class M_subproyecto_partida_pi extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
    
    function   getAllPartidasSubproyecto(){
	    $Query = "  SELECT actpi.idActividad,
						   actpi.codigo,
						   actpi.descripcion,
					       actpi.baremo,
					       actpi.kit_material,
					       actpi.costo_material,
					       actpi.estado,
					       GROUP_CONCAT(DISTINCT sub.subproyectodesc) as subproyecto 
					FROM partidas actpi 
					LEFT JOIN actividad_x_subproyecto actsub ON (actpi.idActividad=actsub.idActividad)
					LEFT JOIN  subproyecto sub on (sub.idsubproyecto=actsub.idsubproyecto)
					WHERE actpi.flg_tipo = 1    
					GROUP BY 
						   actpi.idActividad,
						   actpi.codigo,
						   actpi.descripcion,
					       actpi.baremo,
					       actpi.kit_material,
					       actpi.costo_material,
					       actpi.estado" ;
	    $result = $this->db->query($Query,array());
	    return $result;
	}


     function updatePartidaEstado($id,$flag){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
            $data = array(

             'estado' => $flag
             );
             $this->db->where('idActividad', $id);
            
             $this->db->update('partidas',$data);
            
        }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	    }
	    return $data;
	}
    
    
	
	function   getPartidaInfo($idactividad){
	    $Query = "SELECT * 
	                FROM partidas 
	               WHERE idActividad = ?
	                 AND flg_tipo = 1";	
	    $result = $this->db->query($Query,array($idactividad));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}

	function   getSubProyPartidaInfo($idactividad){
	    $Query = "SELECT * FROM actividad_x_subproyecto where idActividad = ?;";	
	    $result = $this->db->query($Query,array($idactividad));
	    return $result;
	}
	
	function addPartida($codigo, $descripcion,$kitmaterial,$baremo,$CostoMaterial){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        
	        $this->db->trans_begin();
	        $dataInsert = array(	            
	            'codigo' => $codigo,
	            'descripcion' => strtoupper($descripcion),
	            'baremo' => $baremo,
	            'kit_material' => $kitmaterial,
	            'costo_material' => $CostoMaterial,
	            'estado' => 1
	        );

			$this->db->insert('partidas', $dataInsert);

	        if ($this->db->trans_status() === FALSE) {
	            throw new Exception('Hubo un error al actualizar el usuario.');
	        }else{
	            $data['error']    = EXIT_SUCCESS;
	            $data['msj']      = 'Se ingreso el registro correctamente!';
	            $this->db->trans_commit();
	        }

	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}


	function editPartida($idactividad, $descripcion,$kitmaterial,$baremo,$CostoMaterial){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $dataUpdate = array(
	          'descripcion' => strtoupper($descripcion),
	          'baremo' => $baremo,
	          'kit_material' => $kitmaterial,
	          'costo_material' => $CostoMaterial
	        );
        	
        	$this->db->where('idActividad', $idactividad);
        	$this->db->update('partidas', $dataUpdate);

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


	function AddSubProyPartida($idactividad,$subp){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        
	        $this->db->trans_begin();
	        $dataInsert = array(	            
	            'idActividad' => $idactividad,
	            'idSubProyecto' => $subp
	        );

			$this->db->insert('actividad_x_subproyecto', $dataInsert);

	        if ($this->db->trans_status() === FALSE) {
	            throw new Exception('Hubo un error al actualizar el usuario.');
	        }else{
	            $data['error']    = EXIT_SUCCESS;
	            $data['msj']      = 'Se ingreso el registro correctamente!';
	            $this->db->trans_commit();
	        }

	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}

	function EliminaSubProyPartida($idactividad){
		 $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	    	
        	$this->db->trans_begin();
        	$this->db->where('idActividad', $idactividad);
			$this->db->delete('actividad_x_subproyecto');

            	if ($this->db->trans_status() === FALSE) {
            	    throw new Exception('Hubo un error al eliminar en EliminaSubProyPartida');
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


	function VerificaExisteSubProyPartida($idactividad,$subp){
	   $sql = "SELECT COUNT(1) as count 
	   			FROM actividad_x_subproyecto 
	   			where idActividad =".$idactividad.
	   			" and idSubProyecto=".$subp;	
	    $result=$this->db->query($sql, array());
	    return $result->row()->count;
	}



	function   getidPartida($codigopartida){
	    $Query = "SELECT idActividad FROM partidas where codigo = ?;";	
	    $result = $this->db->query($Query,array($codigopartida));
	    if($result->row() != null) {
	        return $result->row()->idActividad;
	    } else {
	        return null;
	    }
	}


	function existeCodigo($codigo){
		$sql = "SELECT COUNT(1) as count FROM partidas where codigo = ?;";	
	    $result=$this->db->query($sql, array($codigo));
	    return $result->row()->count;
	}

	function existeNombre($descripcion){
		$sql = "SELECT COUNT(1) as count FROM partidas where descripcion = ?;";	
	    $result=$this->db->query($sql, array($descripcion));
	    return $result->row()->count;
	}

}