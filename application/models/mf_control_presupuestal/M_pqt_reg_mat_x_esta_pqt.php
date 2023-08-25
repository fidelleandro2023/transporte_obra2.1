<?php
class M_pqt_reg_mat_x_esta_pqt extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
	function getInfoEstacionItemplanToRegMatXEsta($itemplan, $idEstacion){
	    $Query = "SELECT tb.*, pqt.id, pqt.estado FROM (SELECT DISTINCT
                    po.itemplan, ea.idEstacion, e.estacionDesc
                FROM
                    planobra po, subproyectoestacion se, estacionarea ea,
                    estacion e
                WHERE
                    po.idSubProyecto = se.idSubProyecto
                AND se.idEstacionArea = ea.idEstacionArea
                AND ea.idEstacion = e.idEstacion
                AND ea.idEstacion IN (".ID_ESTACION_FO." ,".ID_ESTACION_COAXIAL.")
                AND po.itemplan = ?
                AND ea.idEstacion = ?
                LIMIT 1) as tb 
                LEFT JOIN itemplan_material_x_estacion_pqt pqt
                ON tb.itemplan = pqt.itemplan	AND tb.idEstacion = pqt.idEstacion";
	    $result = $this->db->query($Query,array($itemplan, $idEstacion));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
	function   getMaterialesNuevoModelo(){
	    $Query = 'SELECT id_material, descrip_material, costo_material, paquetizado
            	    FROM material where
            	    (paquetizado = 1 OR
            	    flg_tipo = 1)
            	    order by paquetizado DESC, id_material';
	    $result = $this->db->query($Query,array());
	    return $result->result();
	}
	
	function getInfoCodigoMaterial($codigo){
	    $Query = "SELECT id_material, descrip_material, costo_material, paquetizado
        	       FROM material 
	               WHERE   (paquetizado = 1 OR flg_tipo = 1)            	   
	               AND id_material = ? LIMIT 1";
	    $result = $this->db->query($Query,array($codigo));
	    //log_message('error', $this->db->last_query());
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
	function registrarMaterialesXEstacion($materialesPadre, $arrayFinalInsert, $itemplan, $idEstacion, $partidasOutPut) {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();	
	        $this->db->where('itemplan', $itemplan);
	        $this->db->where('idEstacion', $idEstacion);
	        $this->db->delete('itemplan_material_x_estacion_pqt');
	        if($this->db->trans_status() === FALSE) {
	            $this->db->trans_rollback();
	            throw new Exception('Error en borrar itemplan_material_x_estacion_pqt');
	        }else{
	            $this->db->where('itemplan', $itemplan);
	            $this->db->where('idEstacion', $idEstacion);
	            $this->db->delete('itemplan_material_x_estacion_pqt_detalle');
	            if($this->db->trans_status() === FALSE) {
	                $this->db->trans_rollback();
	                throw new Exception('Error en borrar itemplan_material_x_estacion_pqt_detalle');
	            }else{
        	        $this->db->insert('itemplan_material_x_estacion_pqt', $materialesPadre);
        	        if($this->db->affected_rows() != 1) {
        	            $this->db->trans_rollback();
        	            throw new Exception('Error al insertar en itemplan_material_x_estacion_pqt Po');
        	        }else{	            
                        $this->db->insert_batch('itemplan_material_x_estacion_pqt_detalle', $arrayFinalInsert);
                        if ($this->db->trans_status() === FALSE) {
                            $this->db->trans_rollback();
                            throw new Exception('Hubo un error al insertar el itemplan_material_x_estacion_pqt_detalle.');
                        }else{
                            $this->db->insert_batch('planobra_po_detalle_mo', $partidasOutPut);
                            if ($this->db->trans_status() === FALSE) {
                                $this->db->trans_rollback();
                                throw new Exception('Hubo un error al insertar el planobra_po_detalle_mo.');
                            }else{
                                $data['error'] = EXIT_SUCCESS;
                                $data['msj'] = 'Se actualizo correctamente!';
                                $this->db->trans_commit();
                            }
                        }
        	        }
	            }
	        }
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
	
	function   getMaterialesByIdEstacionItemplan($itemplan, $idEstacion){
	    $Query = 'SELECT im.*, m.descrip_material
            	    FROM itemplan_material_x_estacion_pqt_detalle  im, material m
	               WHERE im.id_material = m.id_material
                   AND	im.itemplan = ?
	               AND im.idEstacion = ?';
	    $result = $this->db->query($Query,array($itemplan, $idEstacion));
	    return $result->result();
	}
	
	function getCostoMaxMatAndCostoMByItemplan($itemplan){
	    $Query = "SELECT 
                        pmat.*, po.costo_unitario_mo
                    FROM
                        planobra po
                    LEFT JOIN
                        pqt_precio_max_mat_x_subpro pmat ON po.idSubProyecto = pmat.idSubProyecto
                    WHERE
                        po.itemplan = ? LIMIT 1";
	    $result = $this->db->query($Query,array($itemplan));
	    //log_message('error', $this->db->last_query());
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
	function   getInfoPo($codigo_po){
	    $Query = "SELECT pdmo.codigo_po, sum(monto_final) as monto_pqt FROM planobra_po_detalle_mo pdmo, partidas p where pdmo.idActividad = p.idActividad
                    and (p.flg_tipo = 3 or p.idActividad not in (".ID_PARTIDA_FERRETERIA."))
                    and pdmo.codigo_po = ?";	
	    $result = $this->db->query($Query,array($codigo_po));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
	function createSolExcesoPartidasAdicionalesPqt($arrayFinalInsert, $dataSolExceso, $itemplan, $idEstacion, $dataMatPadre, $dataMatDetalle) {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $this->db->insert('solicitud_exceso_obra', $dataSolExceso);
	        $insert_id = $this->db->insert_id();
	        if($this->db->affected_rows() != 1) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar en solicitud_exceso_obra');
	        }else{
	            $arrayWithId = array();
	            foreach ($arrayFinalInsert as $detPartida){
	                $detPartida['id_solicitud'] = $insert_id;
	                array_push($arrayWithId, $detPartida);
	            }
	            $this->db->insert_batch('solicitud_exceso_obra_detalle_liqui', $arrayWithId);
	            if ($this->db->trans_status() === FALSE) {
	                $this->db->trans_rollback();
	                throw new Exception('Error al insertar en solicitud_exceso_obra_detalle_liqui');
	            }else{
	                $this->db->where('itemplan', $itemplan);
	                $this->db->where('idEstacion', $idEstacion);
                    $this->db->delete('itemplan_material_x_estacion_pqt_detalle');              
	                if($this->db->trans_status() === FALSE) {
	                    $this->db->trans_rollback();
	                    throw new Exception('Error al insertar en pqt_partidas_adicionales_detalle_tmp');
	                }else{
    	                $this->db->where('itemplan', $itemplan);
    	                $this->db->where('idEstacion', $idEstacion);
                        $this->db->delete('itemplan_material_x_estacion_pqt');              
    	                if($this->db->trans_status() === FALSE) {
    	                    $this->db->trans_rollback();
    	                    throw new Exception('Error al insertar en pqt_partidas_adicionales_detalle_tmp');
    	                }else{
    	                    $this->db->insert('itemplan_material_x_estacion_pqt', $dataMatPadre);
                	        if($this->db->affected_rows() != 1) {
                	            $this->db->trans_rollback();
                	            throw new Exception('Error al insertar en itemplan_material_x_estacion_pqt');
                	        }else{                	            
                	            $this->db->insert_batch('itemplan_material_x_estacion_pqt_detalle', $dataMatDetalle);
                	            if ($this->db->trans_status() === FALSE) {
                	                $this->db->trans_rollback();
                	                throw new Exception('Error al insertar en itemplan_material_x_estacion_pqt_detalle');
                	            }else{ 
                	                $data['error'] = EXIT_SUCCESS;
                	                $data['msj'] = 'Se actualizo correctamente!';
                	                $this->db->trans_commit();
                	            }
                    	                
        	                }	    
            	        }
	                }	                
	            }
	        }
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
	
	function   getPartidaPadre($itemplan, $idEstacion){
	    $Query = "SELECT * FROM itemplan_material_x_estacion_pqt where itemplan = ? and idEstacion = ?";
	    $result = $this->db->query($Query,array($itemplan, $idEstacion));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
}