<?php
class M_bandeja_aprob_cv extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
	function   getAllCVPreRegistro(){
	    $Query = "  SELECT 	cv.itemplan,
                        	cv.nombre_proyecto,
                        	sp.subProyectoDesc,
                        	cv.nombre_constructora,
	                        cv.estado_aprob
                	FROM   planobra_detalle_cv cv,
                	       subproyecto sp
                	WHERE  cv.idSubProyecto = sp.idSubProyecto
                	AND    estado_aprob = '0';" ;
	    $result = $this->db->query($Query,array());
	    return $result;
	}

	function aprobarItemplan($estado, $itemplan, $idEstadoPlan){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	         
	        $this->db->trans_begin();
	        $dataUpdate = array(
	            "estado_aprob" =>  $estado,
	            "usua_aprob"   =>  $this->session->userdata('userSession'),
	            "fec_aprob"    =>  date("d/m/Y H:i:s")
	        );
	         
	        $this->db->where('itemplan', $itemplan);
	        $this->db->update('planobra_detalle_cv', $dataUpdate);
	        
	        if($this->db->trans_status() === FALSE) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al modificar el updateEstadoPlanObra');
	        }else{
	            if($estado == ESTADO_CV_APROBADO && $idEstadoPlan == ESTADO_PLAN_PRE_REGISTRO){
	                $dataUpdate = array(
						"idEstadoPlan"   => ESTADO_PLAN_PRE_DISENO,
						"paquetizado_fg" => 2,
						"fecha_upd"		 => $this->fechaActual(),
						"usu_upd"		 => $this->session->userdata('idPersonaSession'),
						"idCentralPqt"   => $this->getCentralByMDFPlanobra($itemplan)
	                );
	                
	                $this->db->where('itemplan', $itemplan);
	                $this->db->update('planobra', $dataUpdate);
	                 
	                if($this->db->trans_status() === FALSE) {
	                    $this->db->trans_rollback();
	                    throw new Exception('Error al modificar el updateEstadoPlanObra');
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
        }catch(Exception $e){
            $data['msj']   = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
	}

	public function fechaActual(){
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone', 'America/Lima');
        setlocale(LC_TIME, "es_ES", "esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
	}
	
	function getCentralByMDFPlanobra($itemplan) {
		$sql = "SELECT pc.idCentral
				  FROM planobra    po,
					   central      c,
					   pqt_central pc
				 WHERE po.itemplan  = ?
				   AND po.idCentral = c.idCentral
				   AND c.codigo     = pc.codigo
				 LIMIT 1";
		$result = $this->db->query($sql,array($itemplan));
		return $result->num_rows() == 1 ? $result->row_array()['idCentral'] : null;
	}
}