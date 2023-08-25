<?php
class M_cambiar_eecc_subpro_itemplan_masivo extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
	function getInfoItemplanToEditEECC($itemplan){
	    $Query = "SELECT 
                        po.idEmpresaColab,
                        po.idSubProyecto,
                        po.idEstadoPlan,
                        po.solicitud_oc,
                        po.estado_sol_oc,
                        ec.empresaColabDesc,
                        sp.subProyectoDesc,
                        soc.estado,
                        soc.codigo_solicitud,
                        ep.estadoPlanDesc,
	                    sp.idProyecto,
	                    sp.idTipoSubProyecto
                    FROM
                        subproyecto sp,
                        empresacolab ec,
	                    estadoplan ep,
                        planobra po
                        LEFT JOIN solicitud_orden_compra soc oN po.solicitud_oc = soc.codigo_solicitud AND soc.tipo_solicitud = 1
                    WHERE
                        po.idSubProyecto    = sp.idSubProyecto
                    AND po.idEmpresaColab   = ec.idEmpresaColab
	                AND	po.idEstadoPlan     = ep.idEstadoPlan
                    AND po.itemplan         = ?
        	        LIMIT 1"; 
	    $result = $this->db->query($Query,array($itemplan));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}	
	
	function getEECCByDescripcion($eeccDesc){
	    $Query = "SELECT
                        *
                    FROM
                       empresacolab
                    WHERE
                        empresaColabDesc = ?
        	        LIMIT 1";
	    $result = $this->db->query($Query,array($eeccDesc));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
	function getSubProyectoByDescripcion($subProyectoDesc){
	    $Query = "SELECT
                        *
                    FROM
                       subproyecto
                    WHERE
                        subProyectoDesc = ?
        	        LIMIT 1";
	    $result = $this->db->query($Query,array($subProyectoDesc));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
    function updatePlanObraMasivo($dataItemplan, $arrayLogEdiPO){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();	   
	        $this->db->insert_batch('log_cambio_eecc_sub_pro_masivo', $arrayLogEdiPO);
	        if ($this->db->trans_status() === FALSE) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar en pre_diseno');
	        }else{
                $this->db->update_batch('planobra', $dataItemplan, 'itemplan');
                log_message('error', $this->db->last_query());
                if($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    throw new Exception('Error al modificar el update en planobra');
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
	
}