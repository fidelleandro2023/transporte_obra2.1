<?php
class M_pqt_proyecto extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
	function getSubProyectoInfoPqt($idSubProyecto){
	    $Query = " SELECT *,( SELECT GROUP_CONCAT('',estacion.idEstacion) 
								FROM subproyectoestacion, 
									 estacionarea, 
									 estacion
							   WHERE subproyectoestacion.idEstacionArea = estacionarea.idEstacionArea 
								 AND estacion.idEstacion = estacionarea.idEstacion
								 AND subproyectoestacion.idSubProyecto = subproyecto.idSubProyecto) as estaciones 
					 FROM subproyecto 
					WHERE idSubProyecto = ?";
	    $result = $this->db->query($Query,array($idSubProyecto));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
	/**nuevo czavalacas 26.11.2019**/
	function getEstacionesPTRBySubProyecto($idSubProyecto){	
	    $Query = "SELECT  e.estacionDesc, ea.idEstacion, SUM(CASE WHEN idDetallePlan is not null then 1 else 0 end) as total
                FROM
                 estacion e, estacionarea ea, subproyectoestacion se left join detalleplan dp on se.idSubProyectoEstacion = dp.idSubProyectoEstacion
                where
                 se.idEstacionArea = ea.idEstacionArea
	            and ea.idEstacion = e.idEstacion
                and se.idSubProyecto = ?
                group by ea.idEstacion";
	    $result = $this->db->query($Query, array($idSubProyecto));
	    return $result->result();
	}
	
	function getEstacionesAreasByEstaciones($idEstaciones){
	    $Query = "select * from estacionarea where idEstacion in ?";
	    $result = $this->db->query($Query, array($idEstaciones));
	    return $result->result();
	}
	
	function getIdSubProyectoEstacion($idEstaciones, $idSubProyecto){
	    $Query = "select se.idSubProyectoEstacion from subproyectoestacion se, estacionarea ea where 
                    se.idEstacionArea = ea.idEstacionArea
                    and ea.idEstacion in ? 
	                and se.idSubProyecto = ?";
	    $result = $this->db->query($Query, array($idEstaciones, $idSubProyecto));
	    log_message('error', $this->db->last_query());
	    return $result->result();
	}
	
	function updateSubProyectoV2($idSubProyecto, $subproyectoData, $logEditSubProyecto, $subProyectoEstacionDelete, $subproyectoEstacionInsert){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        
	        $this->db->where('idSubProyecto', $idSubProyecto);
	        $this->db->update('subproyecto', $subproyectoData);
	        if($this->db->trans_status() === FALSE) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al Actualizar el SubProyecto!');
	        }else{	             
	            $this->db->insert_batch('subproyectoestacion', $subproyectoEstacionInsert);
	            if ($this->db->trans_status() === FALSE) {
	                $this->db->trans_rollback();
	                throw new Exception('Hubo un error al insertar el subproyectoestacion.');
	            }else{
                       $this->db->insert('log_subproyecto', $logEditSubProyecto);
                        if ($this->db->affected_rows() != 1) {
                            $this->db->trans_rollback();
                            throw new Exception('Error al insertar el log_subproyecto.');
                        } else {
                            if($subProyectoEstacionDelete != null){
                                $this->db->where_in('idSubProyectoEstacion', $subProyectoEstacionDelete);
                                $this->db->delete('subproyectoestacion');
                                if ($this->db->trans_status() === FALSE) {
                                    $this->db->trans_rollback();
                                    throw new Exception('Hubo un error al elimnar en subproyectoestacion.');
                                }else{
        	                        $this->db->trans_commit();
        	                        $data['error']    = EXIT_SUCCESS;
        	                        $data['msj']      = 'Se inserto correctamente!';
    	                       }
                            }else{
                                $this->db->trans_commit();
                                $data['error']    = EXIT_SUCCESS;
                                $data['msj']      = 'Se inserto correctamente!';
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
	
	function getSwitchSiom($idSubProyecto) {
		$sql = " SELECT GROUP_CONCAT(DISTINCT idEmpresaColab) arrayEmpresacolab,
						GROUP_CONCAT(DISTINCT jefatura) arrayJefatura
				   FROM switch_siom
                   WHERE idSubPRoyecto = ?";
		$result = $this->db->query($sql, array($idSubProyecto));
		return $result->row_array();
	}
}