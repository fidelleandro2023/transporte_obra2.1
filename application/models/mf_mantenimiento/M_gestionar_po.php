<?php
class M_gestionar_po extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
    function   getItemplanList($itemplan, $subproyecto){
        $Query = " SELECT   po.itemPlan,
                            po.nombreProyecto, 
                            ep.estadoPlanDesc,
                            sp.subProyectoDesc,
                            c.jefatura,
                            c.region,
                            (SELECT ROUND(AVG(porcentaje)) FROM itemplanestacionavance where itemplan = '".$itemplan."' AND porcentaje != 'NR') as porcentaje 
                    FROM    planobra po, subproyecto sp, zonal z, empresacolab ec, estadoplan ep, central c  
                    WHERE   po.idSubProyecto = sp.idSubProyecto 
                    AND     po.idCentral = c.idCentral
                    AND     po.idZonal = z.idZonal 
                    AND     po.idEmpresaColab = ec.idEmpresaColab 
                    AND     po.idEstadoPlan = ep.idEstadoPlan"; 
            if($itemplan!=''){
                $Query  .= " AND po.itemPlan = '".$itemplan."'";
            }
            if($subproyecto!=''){
                $Query  .= " AND po.nombreProyecto = '".$subproyecto."'";
            }
                
	    $result = $this->db->query($Query,array());	   
	    return $result;
	}
	
	function   getInfoItemplanToEdit($itemplan){
	    $Query = " SELECT 	planobra.itemplan,
                        	planobra.nombreProyecto,
                        	planobra.fechaInicio,
                        	planobra.fechaPrevEjec,
                        	planobra.idEstadoPlan,
                        	subproyecto.subProyectoDesc,
                        	empresacolab.empresaColabDesc,
                            planobra.hasAdelanto,
                            planobra.fechaEjecucion,
                            planobra.fechaCancelacion
                	FROM 	planobra, subproyecto, empresacolab
                	WHERE 	planobra.idSubProyecto = subproyecto.idSubProyecto
                	AND 	planobra.idEmpresaColab = empresacolab.idEmpresaColab
                	AND 	itemplan = ?;";  
	    
	    $result = $this->db->query($Query,array($itemplan));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
	function changeEstadoPlan($itemplan, $idEstadoPlan, $hasAdelanto, $fecEjecucion, $fecTermino){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $dataUpdate = array(
	            'idEstadoPlan' => $idEstadoPlan,
	            'hasAdelanto' => $hasAdelanto,
	            'fechaEjecucion' => $fecEjecucion,
	            'fechaCancelacion' => $fecTermino
	        );
	        $this->db->where('itemplan', $itemplan);
	        $this->db->update('planobra',$dataUpdate);
	        if ($this->db->trans_status() === FALSE) {
	            throw new Exception('Hubo un error al actualizar el estadoplan.');
	        }else{
	            	         
	            $dataUpdateLog= array(
	                'tabla' => 'planobra',
	                'actividad' => 'update',
	                'itemplan' => $itemplan,
	                'itemplan_default' => 'idEstadoPlan='.$idEstadoPlan.'|hasAdelanto:'.$hasAdelanto.'|fechaEjecucion:'.$fecEjecucion.'|fechaTermino:'.$fecTermino,
	                'fecha_registro' =>date("Y-m-d h:m:s"),
	                'id_usuario' =>  $this->session->userdata('idPersonaSession')	                
	            );
	            
	            $this->db->insert('log_planobra',$dataUpdateLog);
	            if ($this->db->affected_rows() != 1) {
	                $this->db->trans_rollback();
	                throw new Exception('Hubo un error al actualizar el estadoplan.');
	            }else{
	                $data['error']    = EXIT_SUCCESS;
    	            $data['msj']      = 'Se actualizo correctamente!';
    	            $this->db->trans_commit();	                
	            }
	            
	        }
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
	
	function   getEstacionPorcentajeByItemPlan($itemplan){
	    $Query = " SELECT de.*,  CASE WHEN ie.porcentaje IS NULL then 0 ELSE ie.porcentaje END AS porcentaje, ie.idItemplanEstacion FROM (
                        SELECT DISTINCT po.itemplan,e.idEstacion, e.estacionDesc 
                        FROM planobra po, subproyectoestacion se, estacionarea ea, estacion e
                        WHERE po.idSubProyecto = se.idSubProyecto
                        AND se.idEstacionArea = ea.idEstacionArea
                        AND ea.idEstacion = e.idEstacion
                        AND po.itemplan = ?
                        AND e.idEstacion != 1) as de LEFT JOIN itemplanestacionavance ie
                        ON de.itemplan = ie.itemplan
                        AND de.idEstacion = ie.idEstacion
                        ORDER BY de.idEstacion; ";
	
	    $result = $this->db->query($Query,array($itemplan));
	    return $result;
	}
	
	function savePorcentaje($arrayInsert, $arrayUpdate){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	       
	        $this->db->trans_begin();
	      
	        $this->db->insert_batch('itemplanestacionavance', $arrayInsert);
	        if ($this->db->trans_status() === FALSE) {
	            throw new Exception('Hubo un error al insertar el itemplanestacionavance.');
	        }else{	             
	         $this->db->update_batch('itemplanestacionavance',$arrayUpdate, 'idItemplanEstacion'); 
	            if ($this->db->trans_status() === FALSE) {
	                $this->db->trans_rollback();
	                throw new Exception('Hubo un error al actualizar el itemplanestacionavance.');
	            }else{
	                $data['error']    = EXIT_SUCCESS;
	                $data['msj']      = 'Se actualizo correctamente!';
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