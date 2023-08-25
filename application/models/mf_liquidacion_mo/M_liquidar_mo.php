<?php
class M_liquidar_mo extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	 
	function   getPartidasByProyectoEstacion($codigo_po, $itemplan, $idEstacion){
	    $Query = 'SELECT 	p.codigo, p.descripcion, pd.descPrecio, p.baremo, pr.costo , pdmo.cantidad_inicial
                    FROM 	planobra po, 
                    		subproyecto sp, 
                    		proyecto_estacion_partida_mo pep,                    		
                            preciario pr,
                            central c,
                            precio_diseno pd,
                            partidas p 
				LEFT JOIN 	planobra_po_detalle_mo pdmo 
                ON 			p.idActividad = pdmo.idActividad 
                AND 		pdmo.codigo_po = ?
                    WHERE 	po.idCentral = c.idCentral
                    AND		p.idPrecioDiseno = pd.idPrecioDiseno
                    AND		po.idSubProyecto 	= sp.idSubProyecto
                    AND 	sp.idProyecto 		= pep.idProyecto
                    AND 	pep.idPartida 		= p.idActividad
                    AND		p.idPrecioDiseno 	= pr.idPrecioDiseno
                    AND 	c.idEmpresaColab    = pr.idEmpresaColab
                    AND     c.idZonal			= pr.idzonal
	                AND     (po.paquetizado_fg is null or po.paquetizado_fg = 1)
                    and 	pr.idEstacion       = ?
                    AND 	p.estado 			= 1
                    AND 	p.flg_tipo 			= 2
                    AND 	po.itemplan 		= ?
                    AND 	pep.idEstacion 		= ?
	                   UNION ALL
	               SELECT 	p.codigo, p.descripcion, pd.descPrecio, p.baremo, pr.costo , pdmo.cantidad_inicial
                    FROM 	planobra po, 
                    		subproyecto sp, 
                    		proyecto_estacion_partida_mo pep,                    		
                            preciario pr,
                            precio_diseno pd,
                            partidas p 
				LEFT JOIN 	planobra_po_detalle_mo pdmo 
                ON 			p.idActividad = pdmo.idActividad 
                AND 		pdmo.codigo_po = ?
                    WHERE 	p.idPrecioDiseno    = pd.idPrecioDiseno
                    AND		po.idSubProyecto 	= sp.idSubProyecto
                    AND 	sp.idProyecto 		= pep.idProyecto
                    AND 	pep.idPartida 		= p.idActividad
                    AND		p.idPrecioDiseno 	= pr.idPrecioDiseno
                    AND 	po.idEmpresaColab    = pr.idEmpresaColab
                    AND     po.idZonal			= pr.idzonal
	                AND     po.paquetizado_fg   = 2
                    and 	pr.idEstacion       = ?
                    AND 	p.estado 			= 1
                    AND 	p.flg_tipo 			= 2
                    AND 	po.itemplan 		= ?
                    AND 	pep.idEstacion 		= ?';
	
	    $result = $this->db->query($Query,array($codigo_po, $idEstacion, $itemplan, $idEstacion, $codigo_po, $idEstacion, $itemplan, $idEstacion));
	    log_message('error', $this->db->last_query());
	    return $result->result();
	}
	
	/*
	function   getPartidasByProyectoEstacion($codigo_po, $itemplan, $idEstacion){
	    $Query = "SELECT tb.idActividad, tb.codigo, tb.descripcion, tb.descPrecio, tb.baremo, pre.costo, tb.cantidad_inicial FROM (
					SELECT 
						p.idActividad, p.codigo, p.descripcion, tp.descripcion as descPrecio, p.baremo, c.jefatura, po.idEmpresaColab ,tp.id, pdmo.cantidad_inicial
					FROM
						planobra po,
						subproyecto sp,
						central c,
						proyecto_estacion_partida_mo pep,
						partidas p
						LEFT JOIN pqt_tipo_preciario tp ON 
						(CASE 
							WHEN p.idActividad = 399 THEN tp.id = 2
							WHEN p.idPrecioDiseno = 2 THEN tp.id = 4 
							WHEN p.idPrecioDiseno = 1 THEN tp.id = 1 
							WHEN p.idPrecioDiseno = 3 THEN (
																CASE WHEN 5 = ? THEN  tp.id = 3 
																	 WHEN 2 = ? THEN  tp.id = 2
																	 ELSE tp.id = 0 END
																)
						END)	LEFT JOIN 	planobra_po_detalle_mo pdmo 
								ON 			p.idActividad = pdmo.idActividad 
								AND 		pdmo.codigo_po = ?
					WHERE
						po.idCentral = c.idCentral
						and	po.idSubProyecto = sp.idSubProyecto
						and sp.idProyecto = pep.idProyecto
						and pep.idPartida = p.idActividad
					AND po.itemplan = ?
					and po.paquetizado_fg is null
					AND pep.idEStacion = ?) as tb LEFT JOIN pqt_preciario pre ON tb.id = pre.idTipoPreciario AND pre.idEmpresaColab = tb.idEmpresaColab AND pre.tipoJefatura = (CASE WHEN tb.jefatura = 'LIMA' THEN 1 ELSE 2 END)

					UNION ALL

					SELECT tb.idActividad, tb.codigo, tb.descripcion, tb.descPrecio, tb.baremo, pre.costo, tb.cantidad_inicial FROM (
					SELECT 
						p.idActividad, p.codigo, p.descripcion,  tp.descripcion as descPrecio, p.baremo, c.jefatura, po.idEmpresaColab ,tp.id, pdmo.cantidad_inicial
					FROM
						planobra po,
						subproyecto sp,
						pqt_central c,
						proyecto_estacion_partida_mo pep,
						partidas p
						LEFT JOIN pqt_tipo_preciario tp ON 
						(CASE 
							WHEN p.idActividad = 399 THEN tp.id = 2
							WHEN p.idPrecioDiseno = 2 THEN tp.id = 4 
							WHEN p.idPrecioDiseno = 1 THEN tp.id = 1 
							WHEN p.idPrecioDiseno = 3 THEN (
																CASE WHEN 5 = ? THEN  tp.id = 3 
																	 WHEN 2 = ? THEN  tp.id = 2
																	 ELSE tp.id = 0 END
																)
						END)	LEFT JOIN 	planobra_po_detalle_mo pdmo 
								ON 			p.idActividad = pdmo.idActividad 
								AND 		pdmo.codigo_po = ?
					WHERE
						po.idCentralPqt = c.idCentral
						and	po.idSubProyecto = sp.idSubProyecto
						and sp.idProyecto = pep.idProyecto
						and pep.idPartida = p.idActividad
					AND po.itemplan = ?
					and (po.paquetizado_fg = 2 or po.paquetizado_fg = 1)
					AND pep.idEStacion = ?) as tb LEFT JOIN pqt_preciario pre ON tb.id = pre.idTipoPreciario AND pre.idEmpresaColab = tb.idEmpresaColab AND pre.tipoJefatura = (CASE WHEN tb.jefatura = 'LIMA' THEN 1 ELSE 2 END)";
						
	    $result = $this->db->query($Query,array($idEstacion, $idEstacion, $codigo_po, $itemplan,  $idEstacion, $idEstacion, $idEstacion,$codigo_po, $itemplan,  $idEstacion));
	    return $result->result();
	}
	*/
	
	function getInfoCodigoMaterial($codigo_po, $itemplan, $idEstacion, $codigo){
	    $Query = "SELECT 	p.idActividad, p.codigo, p.descripcion, pd.descPrecio, p.baremo, pr.costo, pod.id_planobra_po_detalle_po, pod.cantidad_inicial
                	FROM 	planobra po,
                        	subproyecto sp,
                        	proyecto_estacion_partida_mo pep,                        	
                        	preciario pr,
                        	central c,
                        	precio_diseno pd,
	                        partidas p
                            LEFT JOIN planobra_po_detalle_mo pod
							ON 		pod.codigo_po   = ?
                            AND 	pod.idACtividad = p.idActividad
                	WHERE 	po.idCentral = c.idCentral
                	AND		p.idPrecioDiseno    = pd.idPrecioDiseno
                	AND		po.idSubProyecto 	= sp.idSubProyecto
                	AND 	sp.idProyecto 		= pep.idProyecto
                	AND 	pep.idPartida 		= p.idActividad
                	AND		p.idPrecioDiseno 	= pr.idPrecioDiseno
                	AND 	c.idEmpresaColab    = pr.idEmpresaColab
                	AND     c.idZonal			= pr.idzonal
					AND     (po.paquetizado_fg is null or po.paquetizado_fg = 1)
                	AND 	pr.idEstacion       = ?
            	    AND 	p.estado 			= 1
            	    AND 	p.flg_tipo 			= 2
            	    AND 	po.itemplan 		= ?
        	        AND 	pep.idEstacion 		= ?
    	            AND     p.codigo            = ?
					UNION ALL
	                SELECT 	p.idActividad, p.codigo, p.descripcion, pd.descPrecio, p.baremo, pr.costo, pod.id_planobra_po_detalle_po, pod.cantidad_inicial
                	FROM 	planobra po,
                        	subproyecto sp,
                        	proyecto_estacion_partida_mo pep,                        	
                        	preciario pr,                        	
                        	precio_diseno pd,
	                        partidas p
                            LEFT JOIN planobra_po_detalle_mo pod
							ON 		pod.codigo_po   = ?
                            AND 	pod.idACtividad = p.idActividad
                	WHERE 	p.idPrecioDiseno    = pd.idPrecioDiseno
                	AND		po.idSubProyecto 	= sp.idSubProyecto
                	AND 	sp.idProyecto 		= pep.idProyecto
                	AND 	pep.idPartida 		= p.idActividad
                	AND		p.idPrecioDiseno 	= pr.idPrecioDiseno
                	AND 	po.idEmpresaColab    = pr.idEmpresaColab
                	AND     po.idZonal			= pr.idzonal
	                AND     po.paquetizado_fg   = 2
                	AND 	pr.idEstacion       = ?
            	    AND 	p.estado 			= 1
            	    AND 	p.flg_tipo 			= 2
            	    AND 	po.itemplan 		= ?
        	        AND 	pep.idEstacion 		= ?
    	            AND     p.codigo            = ?";
	    $result = $this->db->query($Query,array($codigo_po, $idEstacion, $itemplan, $idEstacion, $codigo, $codigo_po, $idEstacion, $itemplan, $idEstacion, $codigo));
	    //log_message('error', $this->db->last_query());
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
	/*
	function getInfoCodigoMaterial($codigo_po, $itemplan, $idEstacion, $codigo){
	    $Query = "SELECT tb.idActividad, tb.codigo, tb.descripcion, tb.descPrecio, tb.baremo, pre.costo, tb.cantidad_inicial, tb.id_planobra_po_detalle_po FROM (
					SELECT 
						p.idActividad, p.codigo, p.descripcion, tp.descripcion as descPrecio, p.baremo, c.jefatura, po.idEmpresaColab ,tp.id, pdmo.cantidad_inicial, pdmo.id_planobra_po_detalle_po
					FROM
						planobra po,
						subproyecto sp,
						central c,
						proyecto_estacion_partida_mo pep,
						partidas p
						LEFT JOIN pqt_tipo_preciario tp ON 
						(CASE 
							WHEN p.idActividad = 399 THEN tp.id = 2
							WHEN p.idPrecioDiseno = 2 THEN tp.id = 4 
							WHEN p.idPrecioDiseno = 1 THEN tp.id = 1 
							WHEN p.idPrecioDiseno = 3 THEN (
																CASE WHEN 5 = ? THEN  tp.id = 3 
																	 WHEN 2 = ? THEN  tp.id = 2
																	 ELSE tp.id = 0 END
																)
						END)	LEFT JOIN 	planobra_po_detalle_mo pdmo 
								ON 			p.idActividad = pdmo.idActividad 
								AND 		pdmo.codigo_po = ?
					WHERE
						po.idCentral = c.idCentral
						and	po.idSubProyecto = sp.idSubProyecto
						and sp.idProyecto = pep.idProyecto
						and pep.idPartida = p.idActividad
					AND po.itemplan = ?
					and po.paquetizado_fg is null
					AND pep.idEStacion = ?
					AND p.codigo = ?) as tb LEFT JOIN pqt_preciario pre ON tb.id = pre.idTipoPreciario AND pre.idEmpresaColab = tb.idEmpresaColab AND pre.tipoJefatura = (CASE WHEN tb.jefatura = 'LIMA' THEN 1 ELSE 2 END)

					UNION ALL

					SELECT tb.idActividad, tb.codigo, tb.descripcion, tb.descPrecio, tb.baremo, pre.costo, tb.cantidad_inicial, tb.id_planobra_po_detalle_po FROM (
					SELECT 
						p.idActividad, p.codigo, p.descripcion,  tp.descripcion as descPrecio, p.baremo, c.jefatura, po.idEmpresaColab ,tp.id, pdmo.cantidad_inicial, pdmo.id_planobra_po_detalle_po
					FROM
						planobra po,
						subproyecto sp,
						pqt_central c,
						proyecto_estacion_partida_mo pep,
						partidas p
						LEFT JOIN pqt_tipo_preciario tp ON 
						(CASE 
							WHEN p.idActividad = 399 THEN tp.id = 2
							WHEN p.idPrecioDiseno = 2 THEN tp.id = 4 
							WHEN p.idPrecioDiseno = 1 THEN tp.id = 1 
							WHEN p.idPrecioDiseno = 3 THEN (
																CASE WHEN 5 = ? THEN  tp.id = 3 
																	 WHEN 2 = ? THEN  tp.id = 2
																	 ELSE tp.id = 0 END
																)
						END)	LEFT JOIN 	planobra_po_detalle_mo pdmo 
								ON 			p.idActividad = pdmo.idActividad 
								AND 		pdmo.codigo_po = ?
					WHERE
						po.idCentralPqt = c.idCentral
						and	po.idSubProyecto = sp.idSubProyecto
						and sp.idProyecto = pep.idProyecto
						and pep.idPartida = p.idActividad
					AND po.itemplan = ?
					and (po.paquetizado_fg = 2 or po.paquetizado_fg = 1)
					AND pep.idEStacion = ?
					AND p.codigo = ?) as tb LEFT JOIN pqt_preciario pre ON tb.id = pre.idTipoPreciario AND pre.idEmpresaColab = tb.idEmpresaColab AND pre.tipoJefatura = (CASE WHEN tb.jefatura = 'LIMA' THEN 1 ELSE 2 END)";
						
	    $result = $this->db->query($Query,array($idEstacion, $idEstacion, $codigo_po, $itemplan,  $idEstacion, $codigo, $idEstacion, $idEstacion,$codigo_po, $itemplan,  $idEstacion, $codigo));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	*/
	
	function   getActividadesByPo($codigo_po){
	    $Query = 'SELECT id_planobra_po_detalle_po, idACtividad FROM planobra_po_detalle_mo where codigo_po = ?';	
	    $result = $this->db->query($Query,array($codigo_po));
	    //log_message('error', $this->db->last_query());
	    return $result->result();
	}
	
	
	
	
	
	function getSubProyectoEstacionByItemplanEstacion($itemplan, $idEstacion){
	    $Query = "SELECT 	se.idSubProyectoEstacion 
                    FROM 	planobra po, subproyectoestacion se, estacionarea ea, area a
                    WHERE 	po.idSubProyecto   = se.idSubProyecto
                    AND		se.idEstacionArea  = ea.idEstacionArea
                    AND 	ea.idArea      = a.idArea
                    AND 	a.tipoArea     = 'MO'
                    AND 	ea.idEstacion  = ?
                    AND 	po.itemplan    = ?";
	    $result = $this->db->query($Query,array($idEstacion, $itemplan));
	    if($result->row() != null) {
	        return $result->row_array()['idSubProyectoEstacion'];
	    } else {
	        return null;
	    }
	}
	
	/***************************************************************/

	function updatePoMO($dataLogPO, $arrayFinalUpdate, $arrayFinalInsert, $dataUpdatePO, $itemplan, $codigo_po) {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();	
	        $this->db->insert('log_planobra_po_edit', $dataLogPO);
	        if($this->db->affected_rows() != 1) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar en log_planobra_po_edit');
	        }else{	           
                $this->db->update_batch('planobra_po_detalle_mo',$arrayFinalUpdate, 'id_planobra_po_detalle_po');
	            if ($this->db->trans_status() === FALSE) {
	                $this->db->trans_rollback();
	                throw new Exception('Error al update el planobra_po_detalle_mo.');
	            }else{
	                $this->db->insert_batch('planobra_po_detalle_mo', $arrayFinalInsert);
	                if ($this->db->trans_status() === FALSE) {
	                    $this->db->trans_rollback();
	                    throw new Exception('Hubo un error al insertar el planobra_po_detalle_mo.');
	                }else{
	                    $this->db->where('codigo_po', $codigo_po);
	                    $this->db->where('itemplan', $itemplan);
	                    $this->db->update('planobra_po', $dataUpdatePO);
	                    if($this->db->trans_status() === FALSE) {
	                        $this->db->trans_rollback();
	                        throw new Exception('Error al modificar el planobra_po');
	                    }else{
    	                    $data['error'] = EXIT_SUCCESS;
    	                    $data['msj'] = 'Se actualizo correctamente!';
    	                    $this->db->trans_commit();
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
	
	function   getPartidasBasicByPtr($ptr){
	    $Query = ' SELECT  pa.codigo,pa.descripcion, pd.descPrecio, pod.costo, 
	                       pod.baremo, pod.cantidad_inicial, pod.monto_inicial,
	                       pod.cantidad_final, pod.monto_final 
	               FROM planobra_po_detalle_mo pod, partidas pa, precio_diseno pd
                	where pod.idActividad = pa.idActividad
                	AND 	pa.idPrecioDiseno = pd.idPrecioDiseno
                	AND pod.codigo_po = ?';	
	    $result = $this->db->query($Query,array($ptr));
	    return $result->result();
	}
	
	function liquidarPO($dataLogPO, $dataUpdate, $codigo_po, $itemplan){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $this->db->insert('log_planobra_po', $dataLogPO);
	        if($this->db->affected_rows() != 1) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar en log_planobra_po');
	        }else{
	            $this->db->where('codigo_po', $codigo_po);
	            $this->db->where('itemplan', $itemplan);
	            $this->db->update('planobra_po', $dataUpdate);
	             
	            if($this->db->trans_status() === FALSE) {
	                $this->db->trans_rollback();
	                throw new Exception('Error al modificar el updateEstadoPlanObra');
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
	
	function gestEstadoPoByItemplanPoCod($itemplan, $codigo_po){
	    $Query = "SELECT estado_po FROM planobra_po WHERE itemplan = ? and codigo_po = ?";
	    $result = $this->db->query($Query,array($itemplan, $codigo_po));
	    if($result->row() != null) {
	        return $result->row_array()['estado_po'];
	    } else {
	        return null;
	    }
	}
	
	function regSolicitudMO($dataSolicitud, $dataDetalleSolicitud) {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();	
	        $this->db->insert('solicitud_presupuestal_mo', $dataSolicitud);
	        if($this->db->affected_rows() != 1) {_log("ERROR 1");
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar la solicitud');
	        }else{
				$this->db->insert_batch('solicitud_presupuestal_mo_detalle', $dataDetalleSolicitud);
	            if($this->db->trans_status() === FALSE) { _log("ERROR 2");
	                $this->db->trans_rollback();
	                throw new Exception('Error al insertar la solicitud.');
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

	function getCodigoSolicitud() {
        $sql = "  SELECT CASE WHEN codigo_solicitud IS NULL THEN CONCAT(YEAR(NOW()),(SELECT ROUND(RAND()*100000)))
							ELSE MAX(codigo_solicitud)+1 END AS codigo_solicitud
					FROM solicitud_presupuestal_mo";
        $result = $this->db->query($sql);
        return $result->row()->codigo_solicitud;
	}
	
	function getCountControlPresupuestal($codigo_po) {
		$sql = "SELECT COUNT(1) count
				  FROM solicitud_presupuestal_mo
				 WHERE codigo_po = ?
				   AND estado = 0";
		$result = $this->db->query($sql, array($codigo_po));
		return $result->row_array()['count'];
	}
	
	function getCountObrasPasadas($codigo_po) {
		$sql = "SELECT COUNT(1) count
				  FROM obras_certificables_pasados
				 WHERE ptr = ?";
		$result = $this->db->query($sql, array($codigo_po));
		return $result->row_array()['count'];
	}
	
	function getCostoSolicitudMo($codigo_po) {
		$sql = "SELECT MAX(t.total) as total
				  FROM 
						(  SELECT MAX(total)total
							 FROM solicitud_presupuestal_mo
							WHERE estado = 1
							  AND codigo_po = ?
							UNION ALL 
						   SELECT MAX(costo_final)total
							  FROM solicitud_exceso_obra
							 WHERE codigo_po = ?
							   AND estado_valida = 1
						)t";
		$result = $this->db->query($sql, array($codigo_po, $codigo_po));
		return $result->row_array()['total'];
	}
}