<?php
class M_registro_oc_solicitud_masivo extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
	function getInfoSolicitudOCCreaByCodigo($codigo_solicitud){
	    $Query = "SELECT po.itemplan, po.idEstadoPlan, po.idSubProyecto, po.paquetizado_fg, sp.idTipoPlanta, 
						 soc.codigo_solicitud, soc.estado, count(1) as cant, ixs.costo_unitario_mo,
                         (SELECT tipo_moneda 
						    FROM contrato
						   WHERE id_contrato = po.idContrato
						  LIMIT 1) tipo_moneda				 
	               FROM  (solicitud_orden_compra soc,
						 itemplan_x_solicitud_oc ixs)
	                       left join planobra po       ON po.solicitud_oc     = soc.codigo_solicitud 
	                       left join subproyecto sp    ON po.idSubProyecto    = sp.idSubProyecto 
                   WHERE   soc.codigo_solicitud = ?
	               AND     tipo_solicitud = 1 
				   AND ixs.codigo_solicitud_oc = soc.codigo_solicitud
                   GROUP BY soc.codigo_solicitud, soc.estado 
                   LIMIT 1";
	    $result = $this->db->query($Query,array($codigo_solicitud));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
	function   getEstacionesAnclasByItemplan($itemplan){
	    $Query = "SELECT DISTINCT
                            	count(DISTINCT CASE WHEN idEstacion = 2 THEN 1 ELSE NULL END) as coaxial,
                            	count(DISTINCT CASE WHEN idEstacion = 5 THEN 1 ELSE NULL END) as fo
                            	FROM planobra po, subproyecto sp, subproyectoestacion se, estacionarea ea
            	WHERE	ea.idEstacion IN (5,2)
            	AND		se.idEstacionArea = ea.idEstacionArea
            	AND		sp.idSubProyecto = se.idSubProyecto
            	AND		po.idSubProyecto = sp.idSubProyecto
            	AND 	po.itemplan = ?";
	    $result = $this->db->query($Query,array($itemplan));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
	function   getDiaAdjudicacionBySubProyecto($idSubProyecto){
	    $Query = "SELECT    dias_fec_prev_atencion
                    FROM    subproyecto_adjudicacion
                   WHERE    idSubProyecto = ?";
	    $result = $this->db->query($Query,array($idSubProyecto));
	    if($result->row() != null) {
	        return $result->row_array()['dias_fec_prev_atencion'];
	    } else {
	        return null;
	    }
	}	
	
	function masiveUpdateAtencionCreateSolOC($itemplans_list, $solicitudes_list, $pre_disenosList, $ptrPi_list) {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();	       
            $this->db->update_batch('planobra',$itemplans_list, 'itemplan');
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                throw new Exception('Error al update el planobra.');
            }else{
                $this->db->update_batch('solicitud_orden_compra',$solicitudes_list, 'codigo_solicitud');
                if($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    throw new Exception('Error al modificar el solicitud_orden_compra');
                }else{
	                $this->db->insert_batch('pre_diseno', $pre_disenosList);
                    if ($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();
                        throw new Exception('Error al insertar en pre_diseno');
                    }else{
                        $this->db->update_batch('ptr_planta_interna',$ptrPi_list, 'itemplan');
    	                if($this->db->trans_status() === FALSE) {
    	                    $this->db->trans_rollback();
    	                    throw new Exception('Error al modificar el ptr_planta_interna');
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
	
	function insertLogErrorCreacioPO($logPoPqtNoCreada) {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();	    
            $this->db->insert('log_po_pqt_no_creada', $logPoPqtNoCreada);
	        if($this->db->affected_rows() != 1) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar en log_po_pqt_no_creada');
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
		
}