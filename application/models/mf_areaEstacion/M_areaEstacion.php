<?php
class M_areaEstacion extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
    
	function   getAllEstac(){ //getAllCentrales
	    $Query = " SELECT * FROM estacion; " ;
	    $result = $this->db->query($Query,array());
	    return $result;
	}
	
	function getAllAreas(){
	    $Query = " SELECT idArea, areaDesc, tipoArea FROM area; " ;
	    $result = $this->db->query($Query,array());
	    return $result;
	}

	function getAllEstacArea(){
	    $Query = " SELECT ea.idEstacionArea, ea.idArea, ea.idEstacion, e.estacionDesc, a.areaDesc, a.tipoArea FROM estacionarea ea, estacion e, area a
                    where ea.idEstacion = e.idEstacion
                    and ea.idArea = a.idArea; " ;
	    $result = $this->db->query($Query,array());
	    return $result;
	}
	
	function   existeEstaci($estacionDesc){ // existeCentral($codigo)
	    $sql = "SELECT COUNT(1) cant
    	              FROM estacion
    	             WHERE UPPER(EstacionDesc) = UPPER(?) 
    	             LIMIT 1";	   //UPPER(codigo)
	    $result = $this->db->query($sql,array($estacionDesc));//$codigo
	    return $result;
	}
	
	function   existeArea($area){ // existeCentral($codigo)
	    $sql = "SELECT COUNT(1) cant
    	              FROM area
    	             WHERE UPPER(areaDesc) = UPPER(?)
    	             LIMIT 1";	   //UPPER(codigo)
	    $result = $this->db->query($sql,array($area));//$codigo
	    return $result;
	}
	
	function insertarEstacion($estacionDesc){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $dataInsert = array(
	            "EstacionDesc"  => $estacionDesc
	        );
	        
	        $this->db->insert('estacion', $dataInsert);
	        if($this->db->affected_rows() != 1) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar el estacion');
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





	function insertarArea($area, $tipoArea){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $dataInsert = array(
	            "areaDesc"  => $area,
	            "tipoArea" => $tipoArea
	        );
	        
	        $this->db->insert('area', $dataInsert);
	        if($this->db->affected_rows() != 1) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar el area');
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
	
	function insertarEstacionArea($area, $estacion){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $dataInsert = array(
	            "idArea"  => $area,
	            "idEstacion" => $estacion
	        );
	        
	        $this->db->insert('estacionarea', $dataInsert);
	        if($this->db->affected_rows() != 1) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar la estacion area');
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
	
	function getAllTiposAreas(){
	    $Query = "SELECT DISTINCT(tipoArea) FROM area;" ;
	    $result = $this->db->query($Query,array());
	    return $result;
	    
	}






	// ///////////traer los datos para que carge en el editar  y actualizar ESTACION
	
	function   getEstacionInfo($idEstacion){
	    $Query = " SELECT * FROM estacion where idEstacion = ?";
	    $result = $this->db->query($Query,array($idEstacion));//($idCentral)
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
	// FALTA
	function editarAreaModelo($id, $areaDesc, $tipoArea ){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
        	$this->db->trans_begin();
        	$dataUpdate = array(
	            "areaDesc"  => $areaDesc,
                "tipoArea" => $tipoArea
	        );
        	$this->db->where('idArea', $id);
        	$this->db->update('area', $dataUpdate);
            	if ($this->db->trans_status() === FALSE) {
            	    throw new Exception('Hubo un error al actualizar en editarCentralModelo');
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







	// FIN TALTA
	/////////////////////////TRAER DATOS Y ACTUALIZAR AREA////////////
//ACTUALIZACION 18:30
    function   getAreaInfo($idArea){
        $Query = " SELECT * FROM area where idArea = ?";
        $result = $this->db->query($Query,array($idArea));//($idCentral)
        if($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }
// FALTA

    function editEstacionModelo($id, $estacion){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->trans_begin();

            $dataUpdate = array(
                "estacionDesc" => strtoupper($estacion),
            );
            $this->db->where('idEstacion', $id);
            $this->db->update('estacion', $dataUpdate);
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Hubo un error al actualizar en editarCentralModelo');
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

    // FIN FALTA







    /////////////////////////// ESTACION Y AREA /////////////////

        function   getEstacionAreaInfo($idEstacionArea){


            $Query = " SELECT ea.idArea, ea.idEstacion, CONVERT(e.estacionDesc USING utf8), a.areaDesc, a.tipoArea FROM estacionarea ea, estacion e, area a
                    where ea.idEstacion = e.idEstacion
				    and ea.idArea = a.idArea 
                    and idEstacionArea = ?";
            $result = $this->db->query($Query,array($idEstacionArea));//($idCentral)
            if($result->row() != null) {
                return $result->row_array();
            } else {
                return null;
            }
        }


       function editEstacionAreaModelo($id, $idEstacion, $idArea){
            $data['error'] = EXIT_ERROR;
            $data['msj']   = null;
            try{
                $this->db->trans_begin();

                $dataUpdate = array(
                    "idEstacion" => $idEstacion,
                    "idArea" => $idArea
                );
                
                $this->db->where('idEstacionArea', $id);
                $this->db->update('estacionarea', $dataUpdate);
                if ($this->db->trans_status() === FALSE) {
                    throw new Exception('Hubo un error al actualizar en editarCentralModelo');
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



}