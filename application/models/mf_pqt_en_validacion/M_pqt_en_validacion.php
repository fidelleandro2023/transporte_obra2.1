<?php
class M_pqt_en_validacion extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
		
	function getEstacionesConPoNoCanceladas($itemPlan) {
	    $Query = "SELECT tb.*, iea.porcentaje FROM (SELECT DISTINCT ppo.itemplan, e.idEstacion, e.estacionDesc 
	               from planobra_po ppo, estacion e 
                    where ppo.idEstacion = e.idEstacion
                    and ppo.estado_po not in (7,8)
                    and ppo.itemplan = ?
	                AND ppo.idEstacion not in (1,20)) as tb
                    LEFT JOIN itemplanestacionavance iea on iea.itemplan = tb.itemplan and iea.idEstacion = tb.idEstacion";
	    $result = $this->db->query($Query, array($itemPlan));
	    return $result->result();
	}
	
	function getPoByItemplanEstacion($itemPlan, $idEstacion) {
	    $Query = "SELECT
                	ppo.codigo_po, (CASE WHEN ppo.flg_tipo_area = 1 THEN 'MAT' 
						 WHEN ppo.flg_tipo_area = 2 THEN 'MO' ELSE NULL END) as tipoArea, ppo.flg_tipo_area, e.estacionDesc,  FORMAT(ppo.costo_total,2) as costo_total, ep.estado, ppo.estado_po
                	FROM
                	planobra_po ppo, po_estado ep, estacion e
                	WHERE
                	ppo.estado_po = ep.idPoestado
                	AND ppo.idEstacion = e.idEstacion
                	AND ppo.itemplan = ?
                	AND ppo.idEstacion = ?
	                AND ppo.estado_po not in (7,8)
                	ORDER BY ppo.flg_tipo_area ASC";
	    $result = $this->db->query($Query, array($itemPlan, $idEstacion));
	    return $result->result();
	}
	
	function getOSSiomFromItemplanEstacion($itemPlan, $idEstacion) {
	    $Query = "SELECT 
                        so.ptr, e.estacionDesc, so.codigoSiom, so.ultimo_estado, so.fecha_ultimo_estado
                    FROM
                        siom_obra so,
                        estacion e
                    WHERE
                        so.idEstacion = e.idEstacion
                            AND so.itemplan     = ?
                            AND so.idEstacion   = ?";
	    $result = $this->db->query($Query, array($itemPlan, $idEstacion));
	    return $result->result();
	}
	
	function getcodSolVRPDTByItemPanPtr($itemplan, $codigo_po) {
	    $Query = "SELECT codigo
                    FROM solicitud_vale_reserva 
                    WHERE flg_estado is null 
	                AND itemplan = ? AND ptr = ?
                    LIMIT 1";
	    $result = $this->db->query($Query, array($itemplan, $codigo_po));
	    if ($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
	function getInfoSiropeByItemplan($itemplan) {
	    $Query = "SELECT 
                        po.itemplan, po.idSubProyecto, po.has_sirope, po.has_sirope_fecha, po.ult_estado_sirope, po.ult_codigo_sirope,
                        po.has_sirope_diseno, po.has_sirope_diseno_fecha, po.utilmo_codigo_sirope_ac, po.ultimo_estado_sirope_ac,
                        po.has_sirope_ac, po.fecha_sirope_ac, po.has_sirope_ac_diseno, po.fecha_sirope_ac_diseno,
                        lt1.codigo_ot as ot_prin, lt2.codigo_ot as ot_ac, lt3.codigo_ot as ot_coax,
                        po.has_sirope_coax, po.has_sirope_fecha_coax, po.ult_estado_sirope_coax, po.ult_codigo_sirope_coax
                    FROM
                        planobra po
                    	LEFT JOIN log_tramas_sirope lt1 on lt1.codigo_ot = CONCAT(po.itemplan,'FO')   and lt1.estado = 1
                        LEFT JOIN log_tramas_sirope lt2 on lt2.codigo_ot = CONCAT(po.itemplan,'AC')   and lt2.estado = 1
                        LEFT JOIN log_tramas_sirope lt3 on lt3.codigo_ot = CONCAT(po.itemplan,'COAX') and lt3.estado = 1
                    WHERE
                        po.itemplan = ?
                        LIMIT 1;";
	    $result = $this->db->query($Query, array($itemplan));
	    if ($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
}