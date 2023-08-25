<?php
class M_registro_po_mo extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
	function   getPartidasByProyectoEstacion($itemplan, $idEstacion){
	    $Query = 'SELECT pa.codigo, pa.descripcion, co.nombre as descPrecio, pa.baremo, pxc.costo  
				    FROM planobra po,
					     partida_x_contrato pxc,
					     partidas pa,
					     contrato co
				   WHERE po.itemplan = ?
				     AND po.idContrato      = pxc.id_contrato
					 AND po.idEmpresaColab  = pxc.idEmpresaColab 
				     AND pa.idActividad     = pxc.id_partida
				     AND pxc.id_contrato    = co.id_contrato';
	    $result = $this->db->query($Query,array($itemplan));
	    return $result->result();
	}
	/*
	function   getPartidasByProyectoEstacion($itemplan, $idEstacion){
	    $Query = "SELECT tb.idActividad, tb.codigo, tb.descripcion, tb.descPrecio, tb.baremo, pre.costo FROM (
					SELECT 
						p.idActividad, p.codigo, p.descripcion, tp.descripcion as descPrecio, p.baremo, c.jefatura, po.idEmpresaColab ,tp.id
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
						END)
					WHERE
						po.idCentral = c.idCentral
						and	po.idSubProyecto = sp.idSubProyecto
						and sp.idProyecto = pep.idProyecto
						and pep.idPartida = p.idActividad
					AND po.itemplan = ?
					and po.paquetizado_fg is null
					AND pep.idEStacion = ?) as tb LEFT JOIN pqt_preciario pre ON tb.id = pre.idTipoPreciario AND pre.idEmpresaColab = tb.idEmpresaColab AND pre.tipoJefatura = (CASE WHEN tb.jefatura = 'LIMA' THEN 1 ELSE 2 END)

					UNION ALL

					SELECT tb.idActividad, tb.codigo, tb.descripcion, tb.descPrecio, tb.baremo, pre.costo FROM (
					SELECT 
						p.idActividad, p.codigo, p.descripcion,  tp.descripcion as descPrecio, p.baremo, c.jefatura, po.idEmpresaColab ,tp.id
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
						END)
					WHERE
						po.idCentralPqt = c.idCentral
						and	po.idSubProyecto = sp.idSubProyecto
						and sp.idProyecto = pep.idProyecto
						and pep.idPartida = p.idActividad
					AND po.itemplan = ?
					and (po.paquetizado_fg = 2 or po.paquetizado_fg = 1)
					AND pep.idEStacion = ?) as tb LEFT JOIN pqt_preciario pre ON tb.id = pre.idTipoPreciario AND pre.idEmpresaColab = tb.idEmpresaColab AND pre.tipoJefatura = (CASE WHEN tb.jefatura = 'LIMA' THEN 1 ELSE 2 END)";
						
	    $result = $this->db->query($Query,array($idEstacion, $idEstacion, $itemplan,  $idEstacion, $idEstacion, $idEstacion, $itemplan,  $idEstacion));
	    return $result->result();
	}*/
	
	function getInfoCodigoMaterial($itemplan, $idEstacion, $codigo){
	    $Query = "SELECT pa.idActividad, pa.codigo, pa.descripcion, co.nombre as descPrecio, pa.baremo, pxc.costo  
				    FROM planobra po,
					     partida_x_contrato pxc,
					     partidas pa,
					     contrato co
				   WHERE po.itemplan = ?
				     AND pa.codigo   = ?
				     AND po.idContrato      = pxc.id_contrato
				     AND pa.idActividad     = pxc.id_partida
				     AND pxc.id_contrato    = co.id_contrato
					 AND pxc.idEmpresaColab = po.idEmpresaColab";
	    $result = $this->db->query($Query,array($itemplan, $codigo));
	    //log_message('error', $this->db->last_query());
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	/*
	
	function getInfoCodigoMaterial($itemplan, $idEstacion, $codigo){
	    $Query = "SELECT tb.idActividad, tb.codigo, tb.descripcion, tb.descPrecio, tb.baremo, pre.costo FROM (
					SELECT 
						p.idActividad, p.codigo, p.descripcion, tp.descripcion as descPrecio, p.baremo, c.jefatura, po.idEmpresaColab ,tp.id
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
						END)
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

					SELECT tb.idActividad, tb.codigo, tb.descripcion, tb.descPrecio, tb.baremo, pre.costo FROM (
					SELECT 
						p.idActividad, p.codigo, p.descripcion,  tp.descripcion as descPrecio, p.baremo, c.jefatura, po.idEmpresaColab ,tp.id
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
						END)
					WHERE
						po.idCentralPqt = c.idCentral
						and	po.idSubProyecto = sp.idSubProyecto
						and sp.idProyecto = pep.idProyecto
						and pep.idPartida = p.idActividad
					AND po.itemplan = ?
					and (po.paquetizado_fg = 2 or po.paquetizado_fg = 1)
					AND pep.idEStacion = ?
					AND p.codigo = ?) as tb LEFT JOIN pqt_preciario pre ON tb.id = pre.idTipoPreciario AND pre.idEmpresaColab = tb.idEmpresaColab AND pre.tipoJefatura = (CASE WHEN tb.jefatura = 'LIMA' THEN 1 ELSE 2 END)
					LIMIT 1";
						
	    $result = $this->db->query($Query,array($idEstacion, $idEstacion, $itemplan,  $idEstacion, $codigo, $idEstacion, $idEstacion, $itemplan,  $idEstacion, $codigo));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	*/
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

	function createPoMO($dataPO, $dataLogPO, $dataDetalleplan, $arrayFinalInsert) {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();	

	        $this->db->insert('planobra_po', $dataPO);
	        if($this->db->affected_rows() != 1) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar en Planobra Po');
	        }else{
	            $this->db->insert('log_planobra_po', $dataLogPO);
	            if($this->db->affected_rows() != 1) {
	                $this->db->trans_rollback();
	                throw new Exception('Error al insertar en log_planobra_po');
	            }else{
    	            $this->db->insert('detalleplan', $dataDetalleplan);
    	            if($this->db->affected_rows() != 1) {
    	                $this->db->trans_rollback();
    	                throw new Exception('Error al insertar en detalleplan');
    	            }else{
    	                $this->db->insert_batch('planobra_po_detalle_mo', $arrayFinalInsert);
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
	               AND     po.itemplan     = ?
                   AND	   po.paquetizado_fg is null                  
                   UNION ALL                   
                   SELECT  po.idEmpresaColabDiseno, 
	                       po.idEmpresaColab,
						   c.idEmpresaColabFuente,
						   c.jefatura
	               FROM    planobra po, 
	                       pqt_central c 
	               WHERE   po.idCentralPqt    = c.idCentral
	               AND     po.itemplan     = ?
                   AND	   po.paquetizado_fg IN (1,2)";
	    $result = $this->db->query($Query,array($itemplan, $itemplan));
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
	/***************CREACION MANUAL DE PO DE DISENO COTIZACION***///////////
	
	function createPoMODiseno($dataPO, $dataLogPO, $dataDetalleplan, $arrayFinalInsert) {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	
	        $this->db->insert('planobra_po', $dataPO);
	        if($this->db->affected_rows() != 1) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar en Planobra Po');
	        }else{
	            $this->db->insert('log_planobra_po', $dataLogPO);
	            if($this->db->affected_rows() != 1) {
	                $this->db->trans_rollback();
	                throw new Exception('Error al insertar en log_planobra_po');
	            }else{
	                $this->db->insert('detalleplan', $dataDetalleplan);
	                if($this->db->affected_rows() != 1) {
	                    $this->db->trans_rollback();
	                    throw new Exception('Error al insertar en detalleplan');
	                }else{
	                    $this->db->insert_batch('planobra_po_detalle_partida', $arrayFinalInsert);
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
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
	
	function getSubProyectoEstacionByItemplanEstacionCotizacion($itemplan, $idEstacion){
	    $Query = "SELECT 	se.idSubProyectoEstacion
                    FROM 	planobra po, subproyectoestacion se, estacionarea ea, area a
                    WHERE 	po.idSubProyecto   = se.idSubProyecto
                    AND		se.idEstacionArea  = ea.idEstacionArea
                    AND 	ea.idArea      = a.idArea
                    AND 	a.tipoArea     = 'MO'
                    AND 	ea.idEstacion  = ?
                    AND 	po.itemplan    = ? 
	                AND 	a.idArea = 46 LIMIT 1";
	    $result = $this->db->query($Query,array($idEstacion, $itemplan));
	    if($result->row() != null) {
	        return $result->row_array()['idSubProyectoEstacion'];
	    } else {
	        return null;
	    }
	}
	
	function   getPartidasByProyectoEstacionPODiseno($itemplan, $idEstacion){
	    $Query = " 	SELECT 	p.idActividad, p.codigo, p.descripcion, pd.descPrecio, p.baremo, pr.costo
            	FROM 	planobra po,
            	subproyecto sp,
            	partidas p,
            	preciario pr,
            	central c,
            	precio_diseno pd
            	WHERE 	po.idCentral = c.idCentral
            	AND		p.idPrecioDiseno    = pd.idPrecioDiseno
            	AND		po.idSubProyecto 	= sp.idSubProyecto
            	AND		p.idPrecioDiseno 	= pr.idPrecioDiseno
            	AND 	c.idEmpresaColab    = pr.idEmpresaColab
            	AND     c.idZonal			= pr.idzonal
            	AND 	pr.idEstacion       = ?
            	AND 	p.estado 			= 1
            	AND 	p.flg_tipo 			= 2
            	AND 	po.itemplan 		= ?
				AND		po.paquetizado_fg is null
                AND     p.codigo            = '15009-7' LIMIT 1
				UNION ALL
				SELECT 	p.idActividad, p.codigo, p.descripcion, pd.descPrecio, p.baremo, pr.costo
            	FROM 	planobra po,
            	subproyecto sp,
            	partidas p,
            	preciario pr,
            	pqt_central c,
            	precio_diseno pd
            	WHERE 	po.idCentralPqt 	= c.idCentral
            	AND		p.idPrecioDiseno    = pd.idPrecioDiseno
            	AND		po.idSubProyecto 	= sp.idSubProyecto
            	AND		p.idPrecioDiseno 	= pr.idPrecioDiseno
            	AND 	c.idEmpresaColab    = pr.idEmpresaColab
            	AND     c.idZonal			= pr.idzonal
            	AND 	pr.idEstacion       = ?
            	AND 	p.estado 			= 1
            	AND 	p.flg_tipo 			= 2
            	AND 	po.itemplan 		= ?
				AND		po.paquetizado_fg IN (1,2)
                AND     p.codigo            = '15009-7' LIMIT 1";
	
	    $result = $this->db->query($Query,array($idEstacion,   $itemplan, $idEstacion,   $itemplan));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
	function createPoMOPin($dataPO, $dataLogPO, $arrayFinalInsert) {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();	

	        $this->db->insert('ptr_planta_interna', $dataPO);
	        if($this->db->affected_rows() != 1) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar en Planobra Po');
	        }else{
	            $this->db->insert('log_planobra_po', $dataLogPO);
	            if($this->db->affected_rows() != 1) {
	                $this->db->trans_rollback();
	                throw new Exception('Error al insertar en log_planobra_po');
	            }else{
					$this->db->insert_batch('ptr_x_actividades_x_zonal', $arrayFinalInsert);
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
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
}