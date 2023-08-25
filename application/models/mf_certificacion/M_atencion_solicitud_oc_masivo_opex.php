<?php
class M_atencion_solicitud_oc_masivo_opex extends CI_Model{
	function __construct(){
		parent::__construct();
		
	}
	
	function getInfoSolicitudOCCreaByCodigo($codigo_solicitud, $tipo_solicitud){
	    $Query = "SELECT   po.itemplan, 
                           po.idEstadoPlan, 
                           po.idSubProyecto, 
                           po.paquetizado_fg, 
                           s.idTipoPlanta, 
                           soc.codigo_solicitud, 
                           soc.estado, 
                           count(1) as cant 
	                FROM  itemplan_solicitud_orden_compra soc,
                          itemplan_x_solicitud_oc ixs,
                          planobra po,
                          subproyecto s
                   WHERE  soc.codigo_solicitud = ?
                     AND po.itemplan = ixs.itemplan
                     AND ixs.codigo_solicitud_oc = soc.codigo_solicitud
	                 AND tipo_solicitud = ? 
                     AND po.idSubProyecto    = s.idSubProyecto 
                   GROUP BY soc.codigo_solicitud, soc.estado 
                   LIMIT 1";
	    $result = $this->db->query($Query,array($codigo_solicitud, $tipo_solicitud));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
	function   getEstacionesAnclasByItemplan($itemplan){
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
	
	function masiveUpdateAtencionCreateSolOC($itemplans_list, $solicitudes_list, $pre_disenosList, $ptrPi_list) {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();	       
            $this->db->update_batch('planobra',$itemplans_list, 'itemplan');
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                throw new Exception('Error al update el planobra.');
            }else{
                $this->db->update_batch('itemplan_solicitud_orden_compra',$solicitudes_list, 'codigo_solicitud');
                if($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    throw new Exception('Error al modificar el solicitud_orden_compra');
                }else{
	                $this->db->insert_batch('pre_diseno', $pre_disenosList);
                    if ($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();
                        throw new Exception('Error al insertar en pre_diseno');
                    }else{
                        $this->db->update_batch('ptr_planta_interna',$ptrPi_list, 'itemplan');
    	                if($this->db->trans_status() === FALSE) {
    	                    $this->db->trans_rollback();
    	                    throw new Exception('Error al modificar el ptr_planta_interna');
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
	
	function insertLogErrorCreacioPO($logPoPqtNoCreada) {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();	    
            $this->db->insert('log_po_pqt_no_creada', $logPoPqtNoCreada);
	        if($this->db->affected_rows() != 1) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar en log_po_pqt_no_creada');
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

    function masiveUpdateAtencionCreateSolOCEdit($itemplans_list, $solicitudes_list, $solCertis) {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();	       
            $this->db->update_batch('planobra',$itemplans_list, 'itemplan');
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                throw new Exception('Error al update el planobra.');
            }else{
                $this->db->update_batch('itemplan_solicitud_orden_compra',$solicitudes_list, 'codigo_solicitud');
                if($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    throw new Exception('Error al modificar el solicitud_orden_compra');
                }else{	 $this->db->update_batch('itemplan_solicitud_orden_compra',$solCertis, 'codigo_solicitud');
                    if($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();
                        throw new Exception('Error al modificar el solicitud_orden_compra 2');
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

    function getSolCertPndEdicion($codigo_solicitud, $tipo_solicitud) {
	    $Query = "select soc.codigo_solicitud 
                    from itemplan_x_solicitud_oc ixs, itemplan_solicitud_orden_compra soc
                	where ixs.codigo_solicitud_oc = soc.codigo_solicitud
                	and soc.tipo_solicitud = ?
                	and soc.estado = 4
                	and ixs.itemplan = (select itemplan from itemplan_x_solicitud_oc where codigo_solicitud_oc = ?) LIMIT 1";
	    $result = $this->db->query($Query, array($codigo_solicitud, $tipo_solicitud));
	    if ($result->row() != null) {
	        return $result->row_array()['codigo_solicitud'];
	    } else {
	        return null;
	    }
	}

    function getCountPoByItemplanAndEstado($arrayItemplan, $estado_po) {
		$sql = "SELECT COUNT(1) as count
		          FROM planobra_po 
				 WHERE itemplan IN ?
				   AND estado_po = ?";
		$result = $this->db->query($sql, array($arrayItemplan, $estado_po));
		return $result->row_array()['count'];
	}

    function masiveUpdateAtencionCreateSolOCCerti($solicitudes_list, $itemplanList, $arrayItemplan, $idUsuario, $countPoValidadas) {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();            
            $this->db->update_batch('itemplan_solicitud_orden_compra',$solicitudes_list, 'codigo_solicitud');
            if($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                throw new Exception('Error al modificar el solicitud_orden_compra');
            }else{
				$this->db->update_batch('planobra',$itemplanList, 'itemplan');
				if($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					throw new Exception('Error al modificar planobra');
				}else{
					$data = $this->updateCertiPoPin($arrayItemplan, 5, $idUsuario);
					if($countPoValidadas > 0) {
						$data = $this->insertLogPO($arrayItemplan, 5, 'CERTIFICADO OC', $idUsuario, 6);

						if($data['error'] == EXIT_ERROR) {
							throw new Exception($data['msj']);
						} else {
							$data = $this->updateCertiPOByItemplan($arrayItemplan, 5, 6);
							
							if($data['error'] == EXIT_ERROR) {
								throw new Exception($data['msj']);
							} else {
								$data['error'] = EXIT_SUCCESS;
								$data['msj'] = 'Se actualizo correctamente!';
								$this->db->trans_commit();    
							}
						}
					} else {
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

    function updateCertiPoPin($arrayItemplan, $estadoPo, $usuario) {
		$sql = "UPDATE ptr_planta_interna
		           SET rangoPtr = ?, 
				       fecha_ultimo_estado = NOW(), 
					   usua_ultimo_estado = ?
		         WHERE itemplan IN ?
				   AND rangoPtr NOT IN (5,6)";
				   
		$result = $this->db->query($sql, array($estadoPo, $usuario, $arrayItemplan));
		
		if ($this->db->trans_status() === FALSE) {
			$dataSalida['error']    = EXIT_ERROR;
			$dataSalida['msj']      = 'Error al actualizar po pin!';
		} else {
			$dataSalida['error']    = EXIT_SUCCESS;
			$dataSalida['msj']      = 'Operacion exitosa!';
		}
		
		return $dataSalida;
	}

    function insertLogPO($arrayItemplan, $po_estado_actual, $descripcion, $idUsuario, $po_estado_final) {
		$sql = "INSERT INTO log_planobra_po (codigo_po, itemplan, 
											 idUsuario, fecha_registro, 
											 idPoestado, controlador)
				SELECT ppo.codigo_po, ppo.itemplan,?, NOW(), ?, ? 
				  FROM planobra_po ppo
				 WHERE ppo.estado_po = ?
				   AND ppo.itemplan IN ?";
		$result = $this->db->query($sql,array($idUsuario, $po_estado_final, $descripcion, $po_estado_actual, $arrayItemplan));
		if($this->db->affected_rows() == 0) {
			$data['error'] = EXIT_ERROR;
			$data['msj']   = 'error al registrar el log de po';
		}else{
			$data['error'] = EXIT_SUCCESS;
			$data['msj']   = 'Se registro correctamente!';
		}
		return $data;
	}

    function updateCertiPOByItemplan($arrayItemplan, $po_estado_actual, $po_estado_final) {
		$sql = "UPDATE planobra_po
		           SET estado_po = ?
		         WHERE itemplan IN ?
				   AND estado_po = ?";
		$result = $this->db->query($sql, array($po_estado_final, $arrayItemplan, $po_estado_actual));
		if($this->db->affected_rows() == 0) {
			$data['error'] = EXIT_ERROR;
			$data['msj'] = 'No hay POs validadas, las POs deben estar validadas para ser certificadas.';
		}else{              
			$data['error'] = EXIT_SUCCESS;
			$data['msj'] = 'Se actualizo correctamente!';
		}
		return $data;
	}
	
	function existeItemplanConOC($ordenCompra) {
        $sql = " SELECT COUNT(*) cantidad
		           FROM planobra WHERE orden_compra = ? ";
        $result = $this->db->query($sql, array($ordenCompra));
        return $result->row()->cantidad;
    }
}