<?php
class M_pqt_central extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
    
    function   getAllCentrales(){
	    $Query = " SELECT c.idCentral, c.tipoCentralDesc as centralDesc, tc.tipoCentralDesc, c.codigo, z.zonalDesc, ec.empresaColabDesc, c.jefatura, c.region 
	                FROM pqt_central c, tipocentral tc, zonal z, empresacolab ec
                    WHERE c.idTipoCentral = tc.idTipoCentral
                    AND c.idZonal = z.idZonal
                    AND c.idEmpresaColab = ec.idEmpresaColab 
                    ORDER BY codigo; " ;
	    $result = $this->db->query($Query,array());
	    return $result;
	}

	function   existeCentral($codigo){
	    $sql = "SELECT COUNT(1) cant
    	              FROM pqt_central
    	             WHERE UPPER(codigo) = UPPER(?)
    	             LIMIT 1";	   
	    $result = $this->db->query($sql,array($codigo));
	    return $result;
	}
	
	function insertarCentral($idTipocentral, $descripcion, $codigo, $zonal, $eecc, $jefatura, $region,$idJefatura){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $dataInsert = array(
	            "idtipoCentral"  => $idTipocentral,
	            "codigo" => strtoupper($codigo),
	            "tipoCentralDesc" => strtoupper($descripcion),
	            "idZonal" => $zonal,
	            "idEmpresacolab" => $eecc,
	            "jefatura" => strtoupper($jefatura),
	            "region" => strtoupper($region),
	            "idJefatura" => $idJefatura
	        );
	        
	        $this->db->insert('pqt_central', $dataInsert);
	        if($this->db->affected_rows() != 1) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar el central');
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
	
	function   getCentraInfo($idCentral){
	    $Query = " SELECT * FROM pqt_central where idCentral = ?";	
	    $result = $this->db->query($Query,array($idCentral));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
	
	function editarCentralModelo($id, $idTipocentral, $descripcion, $codigo, $zonal, $eecc, $jefatura, $region, $idJefatura, $descJefatura){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
        	$this->db->trans_begin();
//         	 log_message('error', 'codigo:'.$codigo);
        	$dataUpdate = array(
	            "idtipoCentral"  => $idTipocentral,
	            "codigo" => strtoupper($codigo),
	            "tipoCentralDesc" => strtoupper($descripcion),
	            "idZonal" => $zonal,
	            "idEmpresacolab" => $eecc,
	            "jefatura" => strtoupper($descJefatura),
				"region" => strtoupper($region),
				"idJefatura" => $idJefatura
	        );
//         	log_message('error', 'data:'.print_r($dataUpdate,true).'$id:'.$id);
        	$this->db->where('idCentral', $id);
        	$this->db->update('pqt_central', $dataUpdate);
            	if ($this->db->trans_status() === FALSE) {
            	    throw new Exception('Hubo un error al actualizar en editarCentralModelo');
            	}else{
            	    log_message('error', 'trans_commit');
            	    $data['error']    = EXIT_SUCCESS;
            	    $data['msj']      = 'Se actualizo correctamente!';
            	    $this->db->trans_commit();
            	}
    	}catch(Exception $e){
    	    log_message('error', 'ROLLBACK');
    	    $data['msj']   = $e->getMessage();
    	    $this->db->trans_rollback();
    	}
        	return $data;
	}
	
	function  getPqtAllCentral(){
	    $Query = "  SELECT idCentral ,CONCAT(codigo,'-',tipoCentralDesc) as tipoCentralDesc  FROM pqt_central;" ;
	    $result = $this->db->query($Query,array());
	    return $result;
	}
	
	function  getPqtCentralByCodigo($codigoCentral){
	    $Query = "  SELECT idCentral  FROM pqt_central WHERE codigo = ?;" ;
	    $result = $this->db->query($Query,array($codigoCentral));
	    return $result->row();
	}
}