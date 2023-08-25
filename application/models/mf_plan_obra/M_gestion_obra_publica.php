<?php
class M_gestion_obra_publica extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
	function getInfoItemplan($itemplan) {
	    $sql = "SELECT 
                    planobra_detalle_obras_publicas.*,
                    subproyecto.subproyectoDesc,
                    proyecto.proyectoDesc,
                    planobra.coordX,
                    planobra.coordY,
                    usua_reg.usuario 			as usua_reg,
                    usua_carta_coti.usuario 	as usua_envio_ct,
	                usua_carta_respu.usuario 	as usua_envio_cr,
	                usua_convenio.usuario 		as usua_convenio,
                    usua_kickoff.usuario		as usuario_kickoff
                FROM
                    planobra,
                    subproyecto,
                    proyecto,
                    planobra_detalle_obras_publicas
	                left join usuario as usua_reg          ON planobra_detalle_obras_publicas.usuario_envio_carta      = usua_reg.id_usuario
	                left join usuario as usua_carta_coti   ON planobra_detalle_obras_publicas.usuario_carta_cotizacion = usua_carta_coti.id_usuario
	                left join usuario as usua_carta_respu  ON planobra_detalle_obras_publicas.usuario_carta_respuesta  = usua_carta_respu.id_usuario
	                left join usuario as usua_convenio     ON planobra_detalle_obras_publicas.usuario_reg_convenio     = usua_convenio.id_usuario
					left join usuario as usua_kickoff	   ON planobra_detalle_obras_publicas.usuario_ejecuta_kickoff  = usua_kickoff.id_usuario

	            WHERE	planobra.idSubProyecto 	= subproyecto.idSubProyecto
				AND 	subproyecto.idProyecto 	= proyecto.idProyecto
				AND 	planobra.itemplan 		= planobra_detalle_obras_publicas.itemplan
				AND 	planobra_detalle_obras_publicas.itemplan =  ?";
	    $result = $this->db->query($sql, array($itemplan));
	    if($result->row() != null){
	        return $result->row_array();
	    } else{
	        return null;
	    }	    
	}	
	
	function saveFileCartaRespuesta($itemplan, $dataCR){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	
	        $this->db->trans_begin();	        
	        $this->db->where('itemplan', $itemplan);
	        $this->db->update('planobra_detalle_obras_publicas', $dataCR);
	        if ($this->db->trans_status() === FALSE) {
	            throw new Exception('Hubo un error al actualizar Carta Respuesta.');
	        }else{	          
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se insert&oacute; correctamente!!';	                       
	        }
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
	
	function getPtrsAprobadasByItemplan($itemplan){
	    $Query = "SELECT 
                    	planobra_po.*, UPPER(po_estado.estado) as estado, estacion.estacionDesc, area.areaDesc, area.tipoArea
                    FROM
                    	planobra_po,
                    	po_estado,
                        detalleplan,
                        subproyectoestacion,
                        estacionarea,
                        estacion,
                        area
                    WHERE
                    	planobra_po.codigo_po 				= detalleplan.poCod
                    AND	planobra_po.itemplan  				= detalleplan.itemplan
                    AND detalleplan.idSubProyectoEstacion 	= subproyectoestacion.idSubProyectoEstacion
                    AND	subproyectoestacion.idEstacionArea 	= estacionarea.idEstacionArea
                    AND	estacionarea.idEstacion 			= estacion.idEstacion
                    AND	estacionarea.idArea 				= area.idArea
                    AND	planobra_po.estado_po 				= po_estado.idPoEstado
                    AND planobra_po.itemplan 				= ?
                    AND estado_po IN (3,4,5,6)";
	     
	    $result = $this->db->query($Query,array($itemplan));
	    return $result;
	}
	
	function saveLogCotizacion($dataCR){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{	
	        $this->db->trans_begin();	       
            $this->db->insert('planobra_detalle_obras_publicas_cotizacion', $dataCR);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar tabla po_detalle_op_cotizacion');
            } else {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se insert&oacute; correctamente!!';
            }	        
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
	
	function getCotizacionesLog($itemplan){
	    $Query = "SELECT       pdop.*, 
	                           u.usuario as usua_envio_ct
                	FROM       planobra_detalle_obras_publicas_cotizacion pdop, 
	                           usuario u
                	WHERE      pdop.usuario_carta_cotizacion = u.id_usuario
	                AND        pdop.itemplan = ?
	               ORDER BY    pdop.fecha_carta_cotizacion DESC";	
	    $result = $this->db->query($Query,array($itemplan));
	    return $result;
	}
	
	function saveLogRespuCotizacion($dataCR){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $this->db->insert('planobra_detalle_obras_publicas_respu', $dataCR);
	        if ($this->db->affected_rows() != 1) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar tabla po_detalle_op_respu');
	        } else {
	            $this->db->trans_commit();
	            $data['error'] = EXIT_SUCCESS;
	            $data['msj'] = 'Se insert&oacute; correctamente!!';
	        }
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
	
	function getRespuestaLog($itemplan){
	    $Query = "SELECT   pdop.*,
                    	   u.usuario as usua_envio_cr
            	FROM       planobra_detalle_obras_publicas_respu pdop,
            	           usuario u
            	WHERE      pdop.usuario_carta_respuesta = u.id_usuario
            	AND        pdop.itemplan = ?
        	    ORDER BY   pdop.fecha_carta_respuesta DESC";
	    $result = $this->db->query($Query,array($itemplan));
	    return $result;
	}
	
	function hasParalizaCotizacion($itemplan){
	    $Query = " SELECT COUNT(1) as cont
                	FROM paralizacion
                	WHERE itemplan = ?
                	AND flg_activo = 1
                	AND flgEstado  = 1
	                AND idMotivo = 23";
	    $result = $this->db->query($Query,array($itemplan));
	    if($result->row() != null) {
	        return $result->row_array()['cont'];
	    } else {
	        return null;
	    }	    
	}
	
	function insertParalizacionCoti($dataCR){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $this->db->insert('paralizacion', $dataCR);
	        if ($this->db->affected_rows() != 1) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar tabla paralizacion');
	        } else {
	            $this->db->trans_commit();
	            $data['error'] = EXIT_SUCCESS;
	            $data['msj'] = 'Se insert&oacute; correctamente!!';
	        }
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
	
	function updateParalizacionCoti($dataCR, $itemplan){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $this->db->where('itemplan', $itemplan);
	        $this->db->where('flg_activo', 1);
	        $this->db->where('flgEstado', 1);
	        $this->db->where('idMotivo', 23);
	        $this->db->update('paralizacion', $dataCR);
	        if ($this->db->trans_status() === FALSE) {
	            throw new Exception('Hubo un error al actualizar Carta Respuesta.');
	        }else{	          
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se insert&oacute; correctamente!!';	                       
	        }
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
}