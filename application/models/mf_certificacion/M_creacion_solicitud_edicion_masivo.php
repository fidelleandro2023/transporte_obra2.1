<?php
class M_creacion_solicitud_edicion_masivo extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
	function getInfoSolicitudOCCreaByCodigo($itemplan){
	    $Query = "select ixs.itemplan, 
	            (case when soc.tipo_solicitud = 1 AND soc.estado IN (2) THEN soc.orden_compra else null END) as orden_compra, 
                SUM(case when soc.tipo_solicitud = 1 AND soc.estado IN (1,2) THEN 1 else 0 END) 		as has_sol_creacion, 
                SUM(case when soc.tipo_solicitud = 1 AND soc.estado IN (1) THEN 1 else 0 END) 		as has_sol_creacion_pdt,
                SUM(case when soc.tipo_solicitud = 1 AND soc.estado IN (2) THEN 1 else 0 END) 		as has_sol_creacion_aten,
                SUM(case when soc.tipo_solicitud = 2 AND soc.estado IN (1,2) THEN 1 else 0 END) 		as has_sol_edicion,
                SUM(case when soc.tipo_solicitud = 2 AND soc.estado IN (1) THEN 1 else 0 END) 		as has_sol_edicion_pdt,
                SUM(case when soc.tipo_solicitud = 2 AND soc.estado IN (2) THEN 1 else 0 END) 		as has_sol_edicion_aten,
                SUM(case when soc.tipo_solicitud = 4 AND soc.estado IN (1,2) THEN 1 else 0 END) 		as has_sol_anulacion,
                SUM(case when soc.tipo_solicitud = 4 AND soc.estado IN (1) THEN 1 else 0 END) 		as has_sol_anulacion_pdt,
                SUM(case when soc.tipo_solicitud = 4 AND soc.estado IN (2) THEN 1 else 0 END) 		as has_sol_anulacion_aten,
                SUM(case when soc.tipo_solicitud = 3 AND soc.estado IN (1,2,4,5) THEN 1 else 0 END) 	as has_sol_certificacion,
                SUM(case when soc.tipo_solicitud = 3 AND soc.estado IN (1,4,5) THEN 1 else 0 END) 	as has_sol_certificacion_pdt,
                SUM(case when soc.tipo_solicitud = 3 AND soc.estado IN (2) THEN 1 else 0 END) 		as has_sol_certificacion_aten
                FROM itemplan_x_solicitud_oc ixs, solicitud_orden_compra soc where ixs.codigo_solicitud_oc = soc.codigo_solicitud
                AND ixs.itemplan = ?
                GROUP BY ixs.itemplan
	            LIMIT 1";
	    $result = $this->db->query($Query,array($itemplan));
	    #log_message('error', $this->db->last_query());
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}	
	
    function generarSolicitudesDeAnulacion($arraySolicitud, $arrayItemXSolicitud, $dataItemplan){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $this->db->insert_batch('solicitud_orden_compra', $arraySolicitud);
	        if($this->db->affected_rows() == 0) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar en solicitud_orden_compra');
	        }else{
	            $this->db->insert_batch('itemplan_x_solicitud_oc', $arrayItemXSolicitud);
	            if($this->db->affected_rows() == 0) {
	                $this->db->trans_rollback();
	                throw new Exception('Error al insertar en itemplan_x_solicitud_oc');
	            }else{
                    $this->db->update_batch('planobra', $dataItemplan, 'itemplan');
                    if($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();
                        throw new Exception('Error al modificar el updateEstadoPlanObra');
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