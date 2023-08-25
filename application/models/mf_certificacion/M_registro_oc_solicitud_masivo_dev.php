<?php
class M_registro_oc_solicitud_masivo_dev extends CI_Model{
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
	                       left join planobra po       ON po.solicitud_oc_dev     = soc.codigo_solicitud 
	                       left join subproyecto sp    ON po.idSubProyecto    = sp.idSubProyecto 
                   WHERE   soc.codigo_solicitud = ?
	               AND     tipo_solicitud = 2 
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
	
	function getInfoSolicitudOCCreaByCodigoCrea($codigo_solicitud){
	    $Query = "SELECT po.itemplan, po.idEstadoPlan, po.idSubProyecto, po.paquetizado_fg, sp.idTipoPlanta, 
						 soc.codigo_solicitud, soc.estado, count(1) as cant, ixs.costo_unitario_mo,
                         (SELECT tipo_moneda 
						    FROM contrato
						   WHERE id_contrato = po.idContrato
						  LIMIT 1) tipo_moneda				 
	               FROM  (solicitud_orden_compra soc,
						 itemplan_x_solicitud_oc ixs)
	                       left join planobra po       ON po.solicitud_oc_dev     = soc.codigo_solicitud 
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
	
	function masiveUpdateAtencionCreateSolOC($itemplans_list, $solicitudes_list, $solCertis) {
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
                }else{	 $this->db->update_batch('solicitud_orden_compra',$solCertis, 'codigo_solicitud');
                    if($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();
                        throw new Exception('Error al modificar el solicitud_orden_compra 2');
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
	
	function getSolCertPndEdicion($codigo_solicitud) {
	    $Query = "select soc.codigo_solicitud from itemplan_x_solicitud_oc ixs, solicitud_orden_compra soc
                	where ixs.codigo_solicitud_oc = soc.codigo_solicitud
                	and soc.tipo_solicitud = 3
                	and soc.estado = 4
                	and ixs.itemplan = (select itemplan from itemplan_x_solicitud_oc where codigo_solicitud_oc = ?) LIMIT 1";
	    $result = $this->db->query($Query, array($codigo_solicitud));
	    if ($result->row() != null) {
	        return $result->row_array()['codigo_solicitud'];
	    } else {
	        return null;
	    }
	}
}