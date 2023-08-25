<?php
class M_subproyecto_fases_cant_itemplan extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
	function registrarCantidadItemPlanPorFase($idSubProyecto, $fases){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $arrayInsert = array();
	        
	        foreach($fases as $key=>$value){
	            $datatrans['idSubProyecto']  = $idSubProyecto;
	            $datatrans['fase'] = $value->fase;
	            $datatrans['cantItemPlan'] = $value->cantidadplanificada;
	            array_push($arrayInsert, $datatrans);
	        }
	        $this->db->insert_batch('subproyecto_fases_cant_itemplan', $arrayInsert);
	        if ($this->db->trans_status() === FALSE) {
	            $this->db->trans_rollback();
	            throw new Exception('Hubo un error al insertar el subproyectoestacion.');
	        }else{
	            $this->db->trans_commit();
	            $data['error']    = EXIT_SUCCESS;
	            $data['msj']      = 'Se inserto correctamente!';
	        }
	
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
	
	function getFasesPorSubProyecto($idSubProyecto){
		$Query = " select x.fase, x.cantItemPlan,
						  (SELECT idFase FROM fase WHERE faseDesc = x.fase) AS idFase,
						  (select count(*) from planobra p INNER JOIN fase f on p.idfase = f.idfase where p.idSubProyecto = x.idSubProyecto and f.faseDesc = x.fase  AND p.idEstadoPlan NOT IN (6)) registrado
                    from subproyecto_fases_cant_itemplan x where x.idSubProyecto = ?";
	    $result = $this->db->query($Query,array($idSubProyecto));
	    
	    //log_message('error', '$idSubProyecto ' . $idSubProyecto . ' $Query ' . $Query );
	    
        return $result->result();
	}
	
	function getFasesPorSubProyectoFase($idSubProyecto, $idFase){
	    $Query = " select x.fase, x.cantItemPlan , (select count(*) from planobra p INNER JOIN fase f on p.idfase = f.idfase where p.idSubProyecto = x.idSubProyecto and f.faseDesc = x.fase) registrado
            from subproyecto_fases_cant_itemplan x where x.idSubProyecto = ? and x.fase = ?";
	    $result = $this->db->query($Query,array($idSubProyecto, $idFase));
	     
	    //log_message('error', '$idSubProyecto ' . $idSubProyecto .' $idFase ' . $idFase . ' $Query ' . $Query );
	     
	    return $result->result();
	}
	
	function getMaxMinFasesPorProyecto($idProyecto){
	    $Query = " select min(x.fase) faseMin, max(x.fase) faseMax
                    from subproyecto sp
                    left join subproyecto_fases_cant_itemplan x
                    on sp.idSubProyecto = x.idSubProyecto
                    where sp.idproyecto = ?";
	    $result = $this->db->query($Query,array($idProyecto));
	     
	    //log_message('error', '$idProyecto ' . $idProyecto . ' $Query ' . $Query );
	     
	    return $result->result();
	}
	
	function getSubProyectosPorProyecto($idProyecto){
	    $Query = " SELECT idsubproyecto, subproyectoDesc FROM subproyecto where idproyecto = ? order by idsubproyecto";
	    $result = $this->db->query($Query,array($idProyecto));
	
	    //log_message('error', '$idProyecto ' . $idProyecto . ' $Query ' . $Query );
	
	    return $result->result();
	}
	
	function getCantItemplanFasesByIdSubProyecto($idsubproyecto){
	    $Query = " SELECT idSubProyecto, fase, cantItemPlan, 
                    (SELECT count(1) FROM planobra p INNER JOIN fase f on p.idfase = f.idfase where p.idSubProyecto = x.idSubProyecto and f.faseDesc = x.fase
                     AND idEstadoPlan NOT IN (6)
                    ) itemsplan
                    FROM subproyecto_fases_cant_itemplan x
	                WHERE idsubproyecto = ?";
	    $result = $this->db->query($Query,array($idsubproyecto));
	
	    log_message('error', '$idProyecto ' . $idsubproyecto . ' $Query ' . $Query );
	    log_message('error', $this->db->last_query());
	
	    return $result->result();
	}
	
	function upd_subproyecto_fases_cant_itemplan($idSubProyecto, $fase, $txtNuevaCantidad){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        
	        $this->db->where('idSubProyecto', $idSubProyecto);
	        $this->db->where('fase', $fase);
	        $this->db->update('subproyecto_fases_cant_itemplan', array("cantItemPlan" => $txtNuevaCantidad));
	        
	        if($this->db->trans_status() === FALSE) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al modificar el subproyecto_fases_cant_itemplan');
	        }else{
	            $this->db->trans_commit();
	            $data['error']    = EXIT_SUCCESS;
	            $data['msj']      = 'Actualizo correctamente!';
	        }
	    
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
	
}