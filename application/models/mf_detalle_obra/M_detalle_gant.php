<?php
class M_detalle_gant extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
	function   getInfoGantItemplan($item, $dias_diseno){
	    $Query = "SELECT 	tb.itemplan, 
        DATE_FORMAT(tb.fecha_adjudicacion , '%d-%m-%Y') AS fecha_inicio_diseno,
        DATE_FORMAT(ADDDATE(tb.fecha_adjudicacion, INTERVAL ".$dias_diseno." DAY) , '%d-%m-%Y') as fecha_inicio_aprobacion,
        DATE_FORMAT(ADDDATE(tb.fecha_adjudicacion, INTERVAL ".($dias_diseno+1)." DAY) , '%d-%m-%Y') as fecha_inicio_operacion,
        
        DATE_FORMAT(tb.fecha_ejecucion , '%d-%m-%Y') AS fecha_ejecucion,
		DATE_FORMAT(tb.fecha_aprobacion_vr , '%d-%m-%Y') AS fecha_aprobacion_real,
        DATE_FORMAT(tb.fechaPreliquidacion , '%d-%m-%Y') AS fecha_preliquidacion_real,
        
        DATEDIFF(tb.fecha_ejecucion, tb.fecha_adjudicacion) as dif_diseno_real,
        DATEDIFF(tb.fecha_aprobacion_vr, ADDDATE(tb.fecha_adjudicacion, INTERVAL ".$dias_diseno." DAY)) as dif_aprobacion_real,
		DATEDIFF(tb.fechaPreliquidacion, ADDDATE(tb.fecha_adjudicacion, INTERVAL ".($dias_diseno+1)." DAY)) as dif_operacion_real,
        
        DATEDIFF(NOW(), tb.fecha_adjudicacion) as dif_diseno_tmp,
		DATEDIFF(NOW(), ADDDATE(tb.fecha_adjudicacion, INTERVAL ".$dias_diseno." DAY)) as dif_aprobacion_tmp,
		DATEDIFF(NOW(), ADDDATE(tb.fecha_adjudicacion, INTERVAL ".($dias_diseno+1)." DAY)) as dif_operacion_tmp,
        DATEDIFF(NOW(), tb.fecha_adjudicacion) as planobra_real_tmp,
        DATEDIFF(tb.fechaPreliquidacion, tb.fecha_adjudicacion) as planobra_real
        FROM (SELECT 	po.itemplan,
				(SELECT fecha_adjudicacion FROM pre_diseno WHERE itemplan = po.itemplan and idEstacion = 5 LIMIT 1) as fecha_adjudicacion,
				(SELECT fecha_ejecucion FROM pre_diseno WHERE itemplan = po.itemplan and idEstacion = 5 LIMIT 1) as fecha_ejecucion,
				(SELECT MIN(STR_TO_DATE(wu.f_aprob, '%d/%m/%Y %H:%i:%s')) as fecha_aprobacion
				FROM detalleplan dp, subproyectoestacion se, estacionarea ea, web_unificada wu 
				WHERE dp.poCod = wu.ptr
				AND dp.idSubProyectoEstacion = se.idSubProyectoEstacion
				AND se.idEstacionArea = ea.idEstacionArea
				AND ea.idEstacion = 5
				AND dp.itemplan = po.itemplan
				AND wu.f_aprob != '') as fecha_aprobacion_vr,
				po.fechaPreliquidacion
				FROM planobra po WHERE po.itemplan = ? ) AS tb;" ;
	    $result = $this->db->query($Query,array($item));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
	
	function saveTareaGant($datos){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();	       
	        $this->db->insert('gant_detalle', $datos);
	        if($this->db->affected_rows() != 1) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar en gant_detalle');
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
	
	function   getTaskByItemPlan($item){
	    $Query = "	SELECT *  FROM gant_detalle WHERE itemplan = ? ORDER BY id_gant;" ;
	    $result = $this->db->query($Query,array($item));
	    return $result->result();
	}
	
	public function deleteTaskGant($itemplan, $id_gant)
	{	    
	    $data['error'] = EXIT_ERROR;
	    $data['msj'] = null;
	    try {
	        $this->db->trans_begin();
	        $this->db->where('itemplan', $itemplan);
	        $this->db->where('id_gant', $id_gant);
	        $this->db->delete('gant_detalle');
	        	        
	        if ($this->db->trans_status() === true) {	           
	            $this->db->where('itemplan', $itemplan);
	            $this->db->where('(source = '.$id_gant.' OR target = '.$id_gant.')');
	            $this->db->delete('gant_detalle_links');
	            
	            if ($this->db->trans_status() === true) {
	                $data['error'] = EXIT_SUCCESS;
	                $data['msj'] = 'Se Elimino correctamente!';
	                $this->db->trans_commit();
	            } else {
	                $this->db->trans_rollback();
	                throw new Exception('ERROR TRANSACCION deleteLinkGant');
	            }
	        } else {
	            $this->db->trans_rollback();
	            throw new Exception('ERROR TRANSACCION deleteItemPlanEstaDet');
	        }
	        
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    
	    return $data;
	}	
    
	function updateTareaGant($itemplan, $idGant, $info){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $this->db->where('itemplan', $itemplan);
	        $this->db->where('id_gant', $idGant);
	        $this->db->update('gant_detalle', $info);
	        if($this->db->trans_status() == FALSE) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al actualizar la tarea');
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
	
	function   getLinksByItemplan($item){
	    $Query = "SELECT * FROM gant_detalle_links WHERE itemplan = ? ORDER BY id_link" ;
	    $result = $this->db->query($Query,array($item));
	    return $result->result();
	}
	
	function saveLinkGant($datos){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $this->db->insert('gant_detalle_links', $datos);
	        if($this->db->affected_rows() != 1) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar en gant_detalle_links');
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
	
	public function deleteLinkGant($itemplan, $id_link)
	{
	    
	    $data['error'] = EXIT_ERROR;
	    $data['msj'] = null;
	    try {
	        $this->db->trans_begin();
	        $this->db->where('itemplan', $itemplan);
	        $this->db->where('id_link', $id_link);
	        $this->db->delete('gant_detalle_links');
	        
	        if ($this->db->trans_status() === true) {
	            $data['error'] = EXIT_SUCCESS;
	            $data['msj'] = 'Se Elimino correctamente!';
	            $this->db->trans_commit();
	        } else {
	            $this->db->trans_rollback();
	            throw new Exception('ERROR TRANSACCION deleteLinkGant');
	        }
	        
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    
	    return $data;
	}
	function getProyectoByItemplan($itemplan){
	    $Query = "SELECT sp.idProyecto 
                    FROM planobra po, subproyecto sp
                    WHERE po.idSubProyecto = sp.idSubProyecto
                    AND po.itemplan = ?";
	    $result = $this->db->query($Query,array($itemplan));
	    if($result->row() != null) {
	        return $result->row_array()['idProyecto'];
	    } else {
	        return null;
	    }
	}
}