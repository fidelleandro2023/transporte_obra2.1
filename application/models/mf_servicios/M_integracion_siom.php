<?php
class M_integracion_siom extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
	function cambiarEstadoSiom($dataSiomUpdate, $dataEstadoInsert, $codigo_siom){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $this->db->insert('log_tramas_estados_siom', $dataEstadoInsert);
	        if($this->db->affected_rows() != 1) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar en log_tramas_estados_siom');
	        }else{
	            $this->db->where('codigoSiom', $codigo_siom);
	            $this->db->update('siom_obra', $dataSiomUpdate);	
	            if($this->db->trans_status() === FALSE) {
	                $this->db->trans_rollback();
	                throw new Exception('Error al modificar el siom_obra');
	            }else{
	                $data['error'] = EXIT_SUCCESS;
	                $data['msj'] = 'Se actualizo correctamente!';
	                $this->db->trans_commit();
	            }
	        }
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
	
	function insertFailCambioEstado($dataEstadoInsert){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $this->db->insert('log_tramas_estados_siom', $dataEstadoInsert);
	        if($this->db->affected_rows() != 1) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar en log_tramas_estados_siom');
	        }else{
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se actualizo correctamente!';
                $this->db->trans_commit();	            
	        }
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
	
	public function getInfoSIomByCodigoSiom($codigo_siom)
	{
	    $sql = "SELECT so.*, po.idEstadoPlan, po.idSubProyecto, sp.esta_anclas_siom, sp.idProyecto, po.indicador
                FROM siom_obra so, planobra po, subproyecto sp where so.itemplan = po.itemplan
	            AND po.idSubProyecto = sp.idSubProyecto
                AND so.codigoSiom = ? LIMIT 1";
	    $result = $this->db->query($sql,array($codigo_siom));
        if($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
	}
	
	public function getDataEstados($itemplan, $estaciones)
	{
	    $sql = "SELECT e.idEstacion, SUM(CASE WHEN so.id_siom_obra is not null then 1 else 0 end) as num_os,
                COUNT(CASE WHEN so.ultimo_estado = 'VALIDANDO' THEN 1 ELSE NULL END) as validando, 
                COUNT(CASE WHEN so.ultimo_estado = 'APROBADA' THEN 1 ELSE NULL END) as aprobada,
                COUNT(CASE WHEN so.ultimo_estado = 'RECHAZADA' THEN 1 ELSE NULL END) as rechazada,
	            COUNT(CASE WHEN so.ultimo_estado = 'ANULADA' THEN 1 ELSE NULL END) as anulada
                FROM estacion e left join siom_obra so ON e.idEstacion = so.idEstacion and so.itemplan = ? 
                where e.idEstacion in ?
                GROUP BY e.idEstacion";
	    $result = $this->db->query($sql,array($itemplan, $estaciones));
        return $result->result();
	}
	
	public function getDataEstadosAnclaCero($itemplan)
	{
	    $sql = "SELECT so.idEstacion, count(1) as num_os,
            	COUNT(CASE WHEN so.ultimo_estado = 'VALIDANDO' THEN 1 ELSE NULL END) as validando,
            	COUNT(CASE WHEN so.ultimo_estado = 'APROBADA' THEN 1 ELSE NULL END) as aprobada,
            	COUNT(CASE WHEN so.ultimo_estado IN ('RECHAZADA','ANULADA') THEN 1 ELSE NULL END) as rechazada
            	from siom_obra so where so.itemplan = ?
            	GROUP by so.idEstacion";
	    $result = $this->db->query($sql,array($itemplan));
	    return $result->result();
	}
	
	public function getInfoItemplanEstacionAvance($codigo_siom)
	{
	    $sql = "SELECT so.itemplan, so.idEstacion, iea.idItemplanEstacion, iea.porcentaje
                FROM siom_obra so
                left join itemplanestacionavance iea
                on iea.itemplan = so.itemplan and iea.idEstacion = so.idEstacion
                where so.codigoSiom = ? LIMIT 1;";
	    $result = $this->db->query($sql,array($codigo_siom));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
	function insertItemplanEstacionAvance($dataInsert){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $this->db->insert('itemplanestacionavance', $dataInsert);
	        if($this->db->affected_rows() != 1) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar en itemplanestacionavance');
	        }else{
	            $data['error'] = EXIT_SUCCESS;
	            $data['msj'] = 'Se actualizo correctamente!';
	            $this->db->trans_commit();
	        }
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
	
	function updateItemplanEstacionAvance($dataUpdate, $idItemEstacion){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $this->db->where('idItemplanEstacion', $idItemEstacion);
    		$this->db->update('itemplanestacionavance', $dataUpdate);   
        	if ($this->db->trans_status() === FALSE) {
        	    throw new Exception('Hubo un error al actualizar itemplanestacionavance');
        	}else{
	            $data['error'] = EXIT_SUCCESS;
	            $data['msj'] = 'Se actualizo correctamente!';
	            $this->db->trans_commit();
	        }
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
	
	function updatePlanObra($itemPlan, $arrayData){
	    $data['error'] = EXIT_ERROR;
	    $data['msj'] = null;
	     
	    try {
	        $this->db->trans_begin();
	        $this->db->where('itemplan', $itemPlan);
	        $this->db->update('planobra', $arrayData);
	        if ($this->db->trans_status() === false) {
	            $this->db->trans_rollback();
	            throw new Exception('Hubo un error al actualizar el material.');
	        } else {
	            $this->db->trans_commit();
	            $data['error'] = EXIT_SUCCESS;
	            $data['msj'] = 'Se actualiz&oacute; correctamente!!';
	        }
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    return $data;
	}
	
	public function getDJValidadasByItemplan($itemplan, $switch_esta)
	{
	    $sql = "SELECT e.idEstacion, ft.id_ficha_tecnica 
	               FROM estacion e
            	LEFT JOIN ficha_tecnica ft ON ft.itemplan = ?
            	    AND ft.id_estacion = e.idEstacion
            	    AND ft.flg_activo = 1
            	    AND ft.estado_validacion = 1
            	    where e.idEstacion IN  ?
            	    AND e.idEstacion IN (5,2)";
	    $result = $this->db->query($sql,array($itemplan, $switch_esta));
	    //log_message('error', $this->db->last_query());	     
	    return $result->result();
	}
	
	public function getValidadosByItemplanEstacion($itemplan, $idEstacion)
	{
	    $sql = "SELECT count(1) num_os, SUM(CASE WHEN ultimo_estado IN ('VALIDANDO','APROBADA') THEN 1 ELSE 0 END) as validados
            	FROM siom_obra 
	           WHERE itemplan = ? 
	           AND idEstacion = ? 
	           AND ultimo_estado NOT IN ('ANULADA','RECHAZADA')
        	   GROUP BY itemplan, idEstacion";
	    $result = $this->db->query($sql,array($itemplan, $idEstacion));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
	/*valida si existe en la tabla switch formulario para el envio a sisego web*/
	public function validSenSisegoWeb($itemplan)
	{
	    $sql = "SELECT count(1) as cant
            	FROM planobra po, switch_formulario sf
            	WHERE po.idSubProyecto = sf.idSubProyecto
            	AND po.idZonal = sf.idZonal
            	AND sf.flg_tipo = 1
            	AND po.itemplan = ?";
	    $result = $this->db->query($sql,array($itemplan));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
    
	function saveLogSigoplusFromSIOM($datosInsert){
	    $rpta['error'] = EXIT_ERROR;
	    $rpta['msj']   = null;
	    try{
	        $this->db->trans_begin();	       
	        $this->db->insert('log_tramas_sigoplus',$datosInsert);
	        if($this->db->affected_rows() != 1) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar en log_tramas_sigoplus');
	        }else{
	            $this->db->trans_commit();
	            $rpta['error']    = EXIT_SUCCESS;
	            $rpta['msj']      = 'Se agrego correctamente!';
	        }
	    }catch(Exception $e){
	        $rpta['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $rpta;
	}
	
	/*valida si existe en la tabla switch formulario para el envio a sisego web*/
	public function validSendTramaSisego($itemplan, $idEstacion)
	{
	    $sql = "SELECT count(1) as cant
            	FROM log_tramas_sigoplus 
	            where itemplan = ?
	            and idEstacion = ?
            	and origen = 'INTEGRACION SIOM';";
	    $result = $this->db->query($sql,array($itemplan, $idEstacion));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
	public function getMatPoListByitemplanEstacion($itemplan, $idEstacion)
	{//FLG_TIPO_AREA 1 = MATERIALES
	    $sql = "SELECT  *
            	FROM    planobra_po
            	WHERE	idEstacion = ?
            	AND 	itemplan = ?
            	AND 	flg_tipo_area = 1
            	AND 	estado_po = ".PO_APROBADO;
	    $result = $this->db->query($sql,array($idEstacion, $itemplan));
	    return $result->result();
	}
	
	function liquidarPoMateriales($updateDataPo, $insertDataLog){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();	    
            $this->db->update_batch('planobra_po', $updateDataPo, 'codigo_po');
            log_message('error', $this->db->last_query());
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al update planobra_po.');
            }else{	               
                $this->db->insert_batch('log_planobra_po', $insertDataLog);
                log_message('error', $this->db->last_query());                
                if($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    throw new Exception('Error al insertar en log_planobra_po');
                }else{
                    $data['error']    = EXIT_SUCCESS;
                    $data['msj']      = 'Se actualizo correctamente!';
                    $this->db->trans_commit();
                }
            }
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
}