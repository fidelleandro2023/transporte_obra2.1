<?php
class M_registro_partidas_adicionales_mo_pqt extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	 
	function   getPartidasByProyectoEstacion($itemplan, $codigo_po){
	    $Query = "SELECT     
                    pa.codigo, pa.descripcion, tp.descripcion as descPrecio, pa.baremo, pq.costo, pdmo.cantidad_final
                    FROM                        
                        pqt_tipo_preciario tp,
                        pqt_preciario pq,
                        planobra po,
                        pqt_central c,
                        partidas pa 
                        LEFT JOIN planobra_po_detalle_mo pdmo ON pdmo.idActividad = pa.idActividad AND pdmo.codigo_po =  ?

                    WHERE
                        pa.idPrecioDiseno = tp.id
                    AND tp.id = pq.idTipoPreciario
                    AND pa.is_partida_adicional_pqt = 1
                    AND po.idEmpresaColab = pq.idEmpresaColab
                    AND	po.idCentralPqt = c.idCentral
                    AND po.itemplan = ?
                    AND pq.tipoJefatura = (CASE WHEN c.jefatura = 'LIMA' THEN 1 ELSE 2 END)";
	
	    $result = $this->db->query($Query,array($codigo_po, $itemplan));
	    log_message('error', $this->db->last_query());
	    return $result->result();
	}
	
	function getInfoCodigoMaterial($itemplan, $idEstacion, $codigo){
	    $Query = "SELECT     
                    pa.idActividad, pa.codigo, pa.descripcion, tp.descripcion as descPrecio, pa.baremo, pq.costo
                    FROM
                        partidas pa,
                        pqt_tipo_preciario tp,
                        pqt_preciario pq,
                        planobra po,
                        pqt_central c
                    WHERE
                        pa.idPrecioDiseno = tp.id
                    AND tp.id = pq.idTipoPreciario
                    AND pa.is_partida_adicional_pqt = 1
                    AND po.idEmpresaColab = pq.idEmpresaColab
                    AND	po.idCentralPqt = c.idCentral
                    AND po.itemplan = ?
                    AND pa.codigo = ?
                    AND pq.tipoJefatura = (CASE WHEN c.jefatura = 'LIMA' THEN 1 ELSE 2 END)";
	    $result = $this->db->query($Query,array($itemplan, $codigo));
	    //log_message('error', $this->db->last_query());
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}

	function getSubProyectoEstacionByItemplanEstacion($itemplan, $idEstacion){
	    $Query = "SELECT 	se.idSubProyectoEstacion 
                    FROM 	planobra po, subproyectoestacion se, estacionarea ea, area a
                    WHERE 	po.idSubProyecto   = se.idSubProyecto
                    AND		se.idEstacionArea  = ea.idEstacionArea
                    AND 	ea.idArea      = a.idArea
                    AND 	a.tipoArea     = 'MO'
                    AND 	ea.idEstacion  = ?
                    AND 	po.itemplan    = ? LIMIT 1";
	    $result = $this->db->query($Query,array($idEstacion, $itemplan));
	    if($result->row() != null) {
	        return $result->row_array()['idSubProyectoEstacion'];
	    } else {
	        return null;
	    }
	}
	
	/***************************************************************/

	function createPartidasAdicionalesTmp($arrayFinalInsert, $dataPqtTmp, $itemplan, $idEstacion, $array_detalle_po) {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $this->db->where('itemplan', $itemplan);
	        $this->db->where('idEstacion', $idEstacion);
	        $this->db->delete('pqt_partidas_adicionales_tmp');
	        if($this->db->trans_status() === FALSE) {
	            $this->db->trans_rollback();
	            throw new Exception('Error en borrar pqt_partidas_adicionales_tmp');
	        }else{
	            $this->db->where('itemplan', $itemplan);
	            $this->db->where('idEstacion', $idEstacion);
	            $this->db->delete('pqt_partidas_adicionales_detalle_tmp');
	            if($this->db->trans_status() === FALSE) {
	                $this->db->trans_rollback();
	                throw new Exception('Error en borrar pqt_partidas_adicionales_detalle_tmp');
	            }else{
        	        $this->db->insert_batch('pqt_partidas_adicionales_detalle_tmp', $arrayFinalInsert);
        	        if ($this->db->trans_status() === FALSE) {
        	            $this->db->trans_rollback();
        	            throw new Exception('Error al insertar en pqt_partidas_adicionales_detalle_tmp');
        	        }else{
        	            $this->db->insert('pqt_partidas_adicionales_tmp', $dataPqtTmp);
        	            if($this->db->affected_rows() != 1) {
        	                $this->db->trans_rollback();
        	                throw new Exception('Error al insertar en pqt_partidas_adicionales_tmp');
        	            }else{    	           
            	            $this->db->insert_batch('planobra_po_detalle_mo', $array_detalle_po);
                            if ($this->db->trans_status() === FALSE) {
                                $this->db->trans_rollback();
                                throw new Exception('Error al insertar en planobra_po_detalle_mo');
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
	
	function getEeccDisenoOperaByItemPlan($itemplan){
	    $Query = "SELECT   po.idEmpresaColabDiseno, 
	                       c.idEmpresaColab,
						   c.idEmpresaColabFuente,
						   c.jefatura
	               FROM    planobra po, 
	                       central c 
	               WHERE   po.idCentral    = c.idCentral
	               AND     po.itemplan     = ?";
	    $result = $this->db->query($Query,array($itemplan));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
	function getEeccDisenoOperaByItemPlanPqt($itemplan){
	    $Query = "SELECT   po.idEmpresaColabDiseno, 
	                       c.idEmpresaColab,
						   c.idEmpresaColabFuente,
						   c.jefatura
	               FROM    planobra po, 
	                       pqt_central c 
	               WHERE   po.idCentralPqt = c.idCentral
	               AND     po.itemplan     = ?";
	    $result = $this->db->query($Query,array($itemplan));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
	/**propio de partidas adicionales nueva version **/
	
	function   getInfoPo($codigo_po){
	    $Query = "SELECT pdmo.codigo_po, sum(monto_final) as monto_pqt FROM planobra_po_detalle_mo pdmo, partidas p where pdmo.idActividad = p.idActividad
                    and (p.flg_tipo = 3 or p.idActividad in (".ID_PARTIDA_FERRETERIA."))
                    and pdmo.codigo_po = ?";	
	    $result = $this->db->query($Query,array($codigo_po));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
	function createSolExcesoPartidasAdicionalesPqt($arrayFinalInsert, $dataSolExceso) {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();            
            $this->db->insert('solicitud_exceso_obra', $dataSolExceso);
            $insert_id = $this->db->insert_id();
            if($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar en pqt_partidas_adicionales_tmp');
            }else{
                $arrayWithId = array();
                foreach ($arrayFinalInsert as $detPartida){
                    $detPartida['id_solicitud'] = $insert_id;
                    array_push($arrayWithId, $detPartida);
                }
                $this->db->insert_batch('solicitud_exceso_obra_detalle_liqui', $arrayWithId);
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    throw new Exception('Error al insertar en pqt_partidas_adicionales_detalle_tmp');
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
	
	
	function adicPartidasTOPoPqt($arrayFinalInsert) {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	       $this->db->insert_batch('planobra_po_detalle_mo', $arrayFinalInsert);
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    throw new Exception('Error al insertar en planobra_po_detalle_mo');
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
	
	function   getInfoItemplan($itemplan){
	    $Query = "SELECT * FROM planobra where itemplan = ?";
	    $result = $this->db->query($Query,array($itemplan));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
}