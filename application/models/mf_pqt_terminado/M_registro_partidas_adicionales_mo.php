<?php
class M_registro_partidas_adicionales_mo extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
	function   getPartidasByProyectoEstacion($itemplan, $idEstacion){
	    $Query = "SELECT     
                    pa.codigo, pa.descripcion, tp.descripcion as descPrecio, pa.baremo, pq.costo
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
                    AND pq.tipoJefatura = (CASE WHEN c.jefatura = 'LIMA' THEN 1 ELSE 2 END)";
	
	    $result = $this->db->query($Query,array($itemplan));
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
	 /*
	function   getPartidasByProyectoEstacion($itemplan, $idEstacion){
	    $Query = ' SELECT 	p.codigo, p.descripcion, pd.descPrecio, p.baremo, pr.costo 
                    FROM 	planobra po, 
                    		subproyecto sp, 
                    		proyecto_estacion_partida_mo pep,
                    		partidas p,
                            preciario pr,
                            central c,
                            precio_diseno pd
                    WHERE 	po.idCentral = c.idCentral
                    AND		p.idPrecioDiseno = pd.idPrecioDiseno
                    AND		po.idSubProyecto 	= sp.idSubProyecto
                    AND 	sp.idProyecto 		= pep.idProyecto
                    AND 	pep.idPartida 		= p.idActividad
                    AND		p.idPrecioDiseno 	= pr.idPrecioDiseno
                    AND 	c.idEmpresaColab    = pr.idEmpresaColab
                    AND     c.idZonal			= pr.idzonal
                    and 	pr.idEstacion       = ?
                    AND 	p.estado 			= 1
                    AND 	p.flg_tipo 			= 2
                    AND 	po.itemplan 		= ?
                    AND 	pep.idEstacion 		= ?
	               AND     (po.paquetizado_fg is null or po.paquetizado_fg =1)
	               UNION ALL
	               SELECT 	p.codigo, p.descripcion, pd.descPrecio, p.baremo, pr.costo 
                    FROM 	planobra po, 
                    		subproyecto sp, 
                    		proyecto_estacion_partida_mo pep,
                    		partidas p,
                            preciario pr,
                            precio_diseno pd
                    WHERE 	p.idPrecioDiseno = pd.idPrecioDiseno
                    AND		po.idSubProyecto 	= sp.idSubProyecto
                    AND 	sp.idProyecto 		= pep.idProyecto
                    AND 	pep.idPartida 		= p.idActividad
                    AND		p.idPrecioDiseno 	= pr.idPrecioDiseno
                    AND 	po.idEmpresaColab    = pr.idEmpresaColab
                    AND     po.idZonal			= pr.idzonal
                    and 	pr.idEstacion       = ?
                    AND 	p.estado 			= 1
                    AND 	p.flg_tipo 			= 2
                    AND 	po.itemplan 		= ?
                    AND 	pep.idEstacion 		= ?
	               AND     po.paquetizado_fg 	= 2';
	
	    $result = $this->db->query($Query,array($idEstacion,   $itemplan,  $idEstacion, $idEstacion,   $itemplan,  $idEstacion));
	    return $result->result();
	}
	
	function getInfoCodigoMaterial($itemplan, $idEstacion, $codigo){
	    $Query = "SELECT 	p.idActividad, p.codigo, p.descripcion, pd.descPrecio, p.baremo, pr.costo
                	FROM 	planobra po,
                        	subproyecto sp,
                        	proyecto_estacion_partida_mo pep,
                        	partidas p,
                        	preciario pr,
                        	central c,
                        	precio_diseno pd
                	WHERE 	po.idCentral = c.idCentral
                	AND		p.idPrecioDiseno    = pd.idPrecioDiseno
                	AND		po.idSubProyecto 	= sp.idSubProyecto
                	AND 	sp.idProyecto 		= pep.idProyecto
                	AND 	pep.idPartida 		= p.idActividad
                	AND		p.idPrecioDiseno 	= pr.idPrecioDiseno
                	AND 	c.idEmpresaColab    = pr.idEmpresaColab
                	AND     c.idZonal			= pr.idzonal
                	AND 	pr.idEstacion       = ?
            	    AND 	p.estado 			= 1
            	    AND 	p.flg_tipo 			= 2
            	    AND 	po.itemplan 		= ?
        	        AND 	pep.idEstacion 		= ?
    	            AND     p.codigo            = ?
	               AND     (po.paquetizado_fg is null or po.paquetizado_fg =1)
	               UNION ALL
	              SELECT 	p.idActividad, p.codigo, p.descripcion, pd.descPrecio, p.baremo, pr.costo
                	FROM 	planobra po,
                        	subproyecto sp,
                        	proyecto_estacion_partida_mo pep,
                        	partidas p,
                        	preciario pr,
                        	precio_diseno pd
                	WHERE 	p.idPrecioDiseno    = pd.idPrecioDiseno
                	AND		po.idSubProyecto 	= sp.idSubProyecto
                	AND 	sp.idProyecto 		= pep.idProyecto
                	AND 	pep.idPartida 		= p.idActividad
                	AND		p.idPrecioDiseno 	= pr.idPrecioDiseno
                	AND 	po.idEmpresaColab    = pr.idEmpresaColab
                	AND     po.idZonal			= pr.idzonal
                	AND 	pr.idEstacion       = ?
            	    AND 	p.estado 			= 1
            	    AND 	p.flg_tipo 			= 2
            	    AND 	po.itemplan 		= ?
        	        AND 	pep.idEstacion 		= ?
    	            AND     p.codigo            = ?
	                AND     po.paquetizado_fg  =   2";
	    $result = $this->db->query($Query,array($idEstacion, $itemplan, $idEstacion, $codigo, $idEstacion, $itemplan, $idEstacion, $codigo));
	    //log_message('error', $this->db->last_query());
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

	function createPartidasAdicionalesTmp($arrayFinalInsert, $dataPqtTmp, $itemplan, $idEstacion) {
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
                AND     p.codigo            = '15009-7' LIMIT 1";
	
	    $result = $this->db->query($Query,array($idEstacion,   $itemplan));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
}