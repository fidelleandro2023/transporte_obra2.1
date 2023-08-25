<?php
class M_bandeja_adjudicacion extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
	
	function getBandejaAdjudicacion($SubProy,$zonal, $eecc,$mesEjec, $idEstacion, $idTipoPlanta, $jefatura, $idProyecto, $arraySubProy){
	    $Query = 'SELECT DISTINCT po.itemplan, po.indicador, sp.subProyectoDesc, z.zonalDesc, ec.empresaColabDesc, po.fechaPrevEjec, ep.estadoPlanDesc, po.idEstadoPlan,
                    count(DISTINCT CASE WHEN idEstacion = '.ID_ESTACION_COAXIAL.' THEN 1 ELSE NULL END) as coaxial, 
                    count(DISTINCT CASE WHEN idEstacion = '.ID_ESTACION_FO.' THEN 1 ELSE NULL END) as fo 
                    FROM planobra po, subproyecto sp, subproyectoestacion se, estacionarea ea, central c, estadoplan ep, zonal z, empresacolab ec
                    WHERE	po.idEstadoPlan = '.ID_ESTADO_PRE_DISENIO.'
                    AND 	ea.idEstacion IN ('.ID_ESTACION_COAXIAL.','.ID_ESTACION_FO.')
                    AND		po.idCentral = c.idCentral
                    AND 	c.idZonal = z.idZonal
                    AND 	c.idEmpresacolab = ec.idEmpresaColab
                    AND		se.idEstacionArea = ea.idEstacionArea
                    AND		sp.idSubProyecto = se.idSubProyecto
                    AND		po.idSubProyecto = sp.idSubProyecto
                    and 	po.idEstadoPlan = ep.idEstadoPlan';
                      if($eecc!=''){
	           $Query .= " AND ec.empresaColabDesc = '".$eecc."'";
	       }
	       if($jefatura!=''){
	           $Query .= " AND z.zonalDesc like '%".$jefatura."%'";
	       }
	       if($SubProy!=''){
	           $Query .= " AND sp.idSubProyecto IN (".$SubProy.")";
	       }
                   $Query .= ' AND   po.fechaInicio != "0000-00-00"
                               GROUP BY po.itemplan;';
	    	  	     	   
		$result = $this->db->query($Query, array());
		return $result;
	}	
	
	function adjudicarItemplan($itemplan,$subproyecto,$central,$empresaColabDiseno, $idFechaPreAtencionCoax, $idFechaPreAtencionFo){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
			$this->db->trans_begin();

			$has_fo      = $this->session->userdata('has_fo');
			$has_coax    = $this->session->userdata('has_coax');
						
			
			if($has_fo == 1){
    			$arrayData = array (
    				'itemPlan'                => $itemplan,
    			    'idEstacion'              => ID_ESTACION_FO,
    				'fecha_prevista_atencion' => $idFechaPreAtencionFo,
    				'fecha_adjudicacion'	  => date("Y-m-d h:m:s"),
    				'estado'                  => ESTADO_PLAN_DISENO,
    				'usuario_adjudicacion'    => (($this->session->userdata('userSession') != null) ? $this->session->userdata('userSession') : 'AUTOMATICO')
    				);
    
    			 $this->db->insert('pre_diseno', $arrayData);
    			 if($this->db->affected_rows() != 1) {
    			     $this->db->trans_rollback();
    			     throw new Exception('Error al modificar el updateEstadoPlanObra');
    			 }
			}
			
			if($has_coax == 1) {
				$idEstacion = ID_ESTACION_COAXIAL;
				$arrayData2 = array (
					'itemPlan'                => $itemplan,
				    'idEstacion'              => ID_ESTACION_COAXIAL,
					'fecha_prevista_atencion' => $idFechaPreAtencionCoax,
					'fecha_adjudicacion'	  => date("Y-m-d h:m:s"),
					'estado'                  => ESTADO_PLAN_DISENO,
					'usuario_adjudicacion'    => (($this->session->userdata('userSession') != null) ? $this->session->userdata('userSession') : 'AUTOMATICO')
					);
			
			     $this->db->insert('pre_diseno', $arrayData2);
			     if($this->db->affected_rows() != 1) {
			         $this->db->trans_rollback();
			         throw new Exception('Error al modificar el updateEstadoPlanObra');
			     }
			}

	        if($this->db->trans_status() === FALSE) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al modificar el updateEstadoPlanObra');
	        }else{
	            
	            $dataUpdate = array(
	                "idEstadoPlan" => ESTADO_PLAN_DISENO,
	                "idSubProyecto" 	   => $subproyecto,
	                "idCentral" 		   => $central,
	                "idEmpresaColabDiseno" => $empresaColabDiseno,
	                "fec_ult_adju_diseno" =>  $this->fechaActual(),
					"usu_upd" => (($this->session->userdata('userSession') != null) ? $this->session->userdata('userSession') : 'AUTOMATICO'),
	                "fecha_upd" => $this->fechaActual()
	            );
	            
	            $this->db->where('itemPlan', $itemplan);
	            $this->db->update('planobra', $dataUpdate);
	            if($this->db->trans_status() === FALSE) {
	                $this->db->trans_rollback();
	                throw new Exception('Error al modificar el updateEstadoPlanObra');
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

	function registrarNombreArchivo($itemplan, $filName, $idEstacion) {
		$this->db->where('itemplan'  , $itemplan);
		$this->db->where('idEstacion', $idEstacion);
		$this->db->update('pre_diseno', array('nomArchivo' => $filName));
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
	
	function   countFOAndCoaxByItemplan($itemplan){
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
	
	function fechaActual() {
	    $zonahoraria = date_default_timezone_get();
	    ini_set('date.timezone','America/Lima');
	    setlocale(LC_TIME, "es_ES","esp");
	    $hoy = strftime("%Y-%m-%d %H:%M:%S");
	    return $hoy;
	}
}