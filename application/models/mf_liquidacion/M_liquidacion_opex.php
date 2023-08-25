<?php
class M_liquidacion_opex extends CI_Model
{
    //http://www.codeigniter.com/userguide3/database/results.html
    public function __construct()
    {
        parent::__construct();

    }

    public function getPoOpexToAprobacion(){
        $Query = "SELECT 
                        p.proyectoDesc, sp.subProyectoDesc, f.faseDesc, ec.empresaColabDesc, e.estacionDesc, ep.estado, ppo.*
                    FROM
                        planobra po,
                        subproyecto sp,
                        planobra_po ppo,
                        proyecto p,
                        empresacolab ec,
                        fase f,
                        estacion e,
                        po_estado ep
                    WHERE	sp.flg_opex = 2
                    AND 	po.itemplan = ppo.itemplan
                    AND 	po.idSubProyecto = sp.idSubProyecto
                    AND		sp.idProyecto = p.idProyecto
                    AND		ppo.id_eecc_reg = ec.idEmpresaColab
                    AND		ppo.idEstacion = e.idEstacion
                    AND		po.idFase = f.idFase
                    AND		ppo.estado_po = ep.idPoEstado
                    AND 	ppo.flg_tipo_area = 1
                    AND  	ppo.estado_po in (1,2)";
        $result = $this->db->query($Query, array());
        return $result;
    }
    
    
    function getInfoPoByCodPo($codigo_po) {
        $Query = "SELECT ppo.*, po.idEstadoPlan, e.estacionDesc, po.idCentralPqt
                    FROM planobra_po ppo, planobra po, estacion e
                   WHERE ppo.itemplan   = po.itemplan
					 AND ppo.idEstacion = e.idEstacion
                     AND ppo.codigo_po  = ? LIMIT 1";
        $result = $this->db->query($Query, array($codigo_po));
        return $result->row_array();
    }

    public function asignarVrOpex($dataPo, $dataLog_po, $dataItemplan, $infoPo)
    {
        $dataSalida['error'] = EXIT_ERROR;
        $dataSalida['msj'] = null;
        try {
            $this->db->trans_begin();  
            $sql = "UPDATE cuenta_opex_pex SET monto_real = (monto_real-".$infoPo['costo_total'].") WHERE idOpex = ? ";
            $result = $this->db->query($sql, array($infoPo['idCuentaOpex']));        
            log_message('error', $this->db->last_query());    
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                throw new Exception('Error al actualizar en cuenta_opex_pex');
            }else{
    	        $this->db->where('codigo_po', $infoPo['codigo_po']);
                $this->db->update('planobra_po', $dataPo);
    	        if($this->db->affected_rows() != 1) {
    	            $this->db->trans_rollback();
    	            throw new Exception('Error al actualizar en planobra_po');
    	        }else{
    	            $this->db->insert('log_planobra_po', $dataLog_po);
        	        if($this->db->affected_rows() != 1) {
        	            $this->db->trans_rollback();
        	            throw new Exception('Error al insertar en log_planobra_po');
        	        }else{
        	            if($infoPo['idEstadoPlan'] ==  ID_ESTADO_EN_APROBACION){
        	                $this->db->where('itemplan', $infoPo['itemplan']);
        	                $this->db->update('planobra', $dataItemplan);
        	                if($this->db->affected_rows() != 1) {
        	                    $this->db->trans_rollback();
        	                    throw new Exception('Error al actualizar en planobra');
        	                }else{
        	                    $data['error'] = EXIT_SUCCESS;
        	                    $data['msj'] = 'Se actualizo correctamente!';
        	                    $this->db->trans_commit();    	                    
        	                }
        	            }else{
        	                $data['error'] = EXIT_SUCCESS;
        	                $data['msj'] = 'Se actualizo correctamente!';
        	                $this->db->trans_commit();
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

   
   function insertSiom($dataSiom, $dataLogPo, $dataEstado) {
		$data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();	
	        $this->db->insert('siom_obra', $dataSiom);
	        if($this->db->affected_rows() != 1) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar en siom_obra');
	        }else{
	            $this->db->insert('log_planobra', $dataLogPo);
    	        if($this->db->affected_rows() != 1) {
    	            $this->db->trans_rollback();
    	            throw new Exception('Error al insertar en log_planobra');
    	        }else{
    	            $this->db->insert('log_tramas_estados_siom', $dataEstado);
    	            if($this->db->affected_rows() != 1) {
    	                $this->db->trans_rollback();
    	                throw new Exception('Error al insertar en log_tramas_estados_siom');
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
	
	function updateSiom($dataSiom, $dataLogPo, $dataEstado, $id_siom_obra) {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $this->db->where('id_siom_obra', $id_siom_obra);
    		$this->db->update('siom_obra', $dataSiom);
        	if ($this->db->trans_status() === FALSE) {
        	    throw new Exception('Hubo un error al actualizar siom_obra');
        	}else{
	            $this->db->insert('log_planobra', $dataLogPo);
	            if($this->db->affected_rows() != 1) {
	                $this->db->trans_rollback();
	                throw new Exception('Error al insertar en log_planobra');
	            }else{
	                $this->db->insert('log_tramas_estados_siom', $dataEstado);
	                if($this->db->affected_rows() != 1) {
	                    $this->db->trans_rollback();
	                    throw new Exception('Error al insertar en log_tramas_estados_siom');
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
    
    function insertLogTramaSiom($dataLogSiom, $dataSiom) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->trans_begin();
            $this->db->insert('log_tramas_siom', $dataLogSiom);
            if($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar en log_tramas_siom');
            }else{                
                $this->db->insert('siom_obra', $dataSiom);
                if($this->db->affected_rows() != 1) {
                    $this->db->trans_rollback();
                    throw new Exception('Error al insertar en siom_obra');
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
    
    function getEmplazamientoIdSiomByidCentral($idCentral) {
        $sql = "SELECT count(1) as cant, sn.empl_id
                FROM siom_nodo sn JOIN central c ON sn.empl_nemonico = c.codigo
                and idCentral = ?";
        $result = $this->db->query($sql,array($idCentral));
        log_message('error', $this->db->last_query());
        return $result->row_array();
    }
    
     function   getItemPtrSendSiom(){
        $Query = " SELECT * FROM a_data_siom_import WHERE id NOT IN (0)" ;
        $result = $this->db->query($Query,array());
        return $result;
    }
	
	function insertLogTramaSiomSoloLog($dataLogSiom) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->trans_begin();
            $this->db->insert('log_tramas_siom', $dataLogSiom);
            if($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar en log_tramas_siom');
            }else{           
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se actualizo correctamente!';
                $this->db->trans_commit();
            }
        }catch(Exception $e){
            $data['msj']   = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
}