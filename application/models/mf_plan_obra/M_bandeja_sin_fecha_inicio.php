<?php
class M_bandeja_sin_fecha_inicio extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
    function   getItemNoFechaInicio(){
        $Query = "SELECT 
                    		po.itemplan,
                    		p.proyectoDesc,
                    		sp.subProyectoDesc,
                    		ep.estadoPlanDesc,
                    		po.fechaInicio,
                            po.idSubProyecto
                    FROM
                    		planobra po,
                    		subproyecto sp,
                    		proyecto p,
                    		estadoplan ep
                    WHERE	po.idSubProyecto  = sp.idSubProyecto
                    AND 	sp.idProyecto     = p.idProyecto
                    AND 	po.idEstadoPlan   = ep.idEstadoPlan
                    AND 	po.fechaInicio    = '0000-00-00'
                    AND     sp.idProyecto != ".ID_PROYECTO_OBRA_PUBLICA;
	    $result = $this->db->query($Query,array());	   
	    log_message('error',$this->db->last_query());
	    return $result;
	}
	
	function updateFecInicioItemplan($itemplan, $fecInicio, $fecPrevista){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	
	        $this->db->trans_begin();
	        $dataItem = array (
	            "fechaInicio"      => $fecInicio,
	            "fechaPrevEjec"    => $fecPrevista
	        );
	        $this->db->where('itemplan', $itemplan);
	        $this->db->update('planobra',  $dataItem);
	        if ($this->db->trans_status() === FALSE) {
	            throw new Exception('Hubo un error al actualizar el estadoplan.');
	        }else{
	            $data['error']    = EXIT_SUCCESS;
	            $data['msj']      = 'Se actualizo correctamente!';
	            $this->db->trans_commit();
	        }
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
	
}