
<?php
class M_po_registro_cotizacion extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
    }

    // function getPartidasRegGrafico($itemplan) {
    //     $sql = "SELECT pa.idActividad,
    //                    pa.descripcion,
    //                    pa.baremo
    //               FROM partida_subproyecto ps, partidas pa, planobra po
    //              WHERE ps.idPartida = pa.idActividad
    //                AND po.idSubProyecto = ps.idSubProyecto
    //                AND po.itemplan = ?
    //                AND pa.flg_reg_grafico = 4";
    //     $result = $this->db->query($sql,array($itemplan));	   
    //     return $result->result_array();
    // }

    function getDataDetalle($itemplan, $idPartida=null) {
        $sql = " SELECT pa.baremo,
                        pre.idEmpresaColab,
                        pre.idPrecioDiseno,
                        pa.idActividad,
						pa.descripcion,
                        c.idZonal,
                        pre.costo
                   FROM planobra po, 
                        central c,
                        preciario pre,
                        partidas pa,
                        partida_subproyecto ps
                  WHERE c.idCentral        = po.idCentral
                    AND pre.idEmpresaColab = CASE WHEN po.idEmpresaColabDiseno IS NULL 
                                                  THEN c.idEmpresaColab ELSE po.idEmpresaColabDiseno END
                    AND pre.idZonal 	   = c.idZonal
                    AND pa.idActividad     = ps.idPartida
                    AND po.idSubProyecto   = ps.idSubProyecto
                    AND pa.idPrecioDiseno  = pre.idPrecioDiseno
                    AND po.itemplan        = ?
					AND pre.idEstacion     = 1
					AND pa.flg_reg_grafico = 4
                    AND pa.idActividad = COALESCE(?, pa.idActividad)";
        $result = $this->db->query($sql, array($itemplan, $idPartida));
		return ($idPartida == null) ?$result->result_array() : $result->row_array();		
    }

    function getIdSubProyectoEstacionGraf($itemplan) {
		$sql = " SELECT idSubProyectoEstacion 
				   FROM subproyectoestacion 
				  WHERE idSubProyecto = ( SELECT idSubProyecto 
										    FROM planobra 
										   WHERE itemplan = '".$itemplan."') 
					AND idEstacionArea = (
											SELECT idEstacionArea 
											  FROM estacionarea 
											 WHERE idEstacion = 1
											   AND idArea     = 46
										)";
		$result = $this->db->query($sql);
		return $result->row_array()['idSubProyectoEstacion'];		
	}
	
	function countPOGraf($itemplan) {
		$sql = " SELECT COUNT(1) count
				   FROM detalleplan
			      WHERE itemplan = '".$itemplan."'
					AND idSubProyectoEstacion = (
													SELECT idSubProyectoEstacion 
														FROM subproyectoestacion 
														WHERE idSubProyecto = ( SELECT idSubProyecto 
																				FROM planobra 
																				WHERE itemplan = '".$itemplan."') 
														AND idEstacionArea = (
																				SELECT idEstacionArea 
																					FROM estacionarea 
																				WHERE idEstacion = 1
																					AND idArea = 46
																			)
												)";
		$result = $this->db->query($sql);
		return $result->row_array()['count'];	
	}
    
    function registrarPoMOGraf($arrayPO, $arrayLogPO, $arrayDetalleplan, $arrayDetallePO, $itemplan, $arrayUpdateEstado, $codigoPO, $idUsuario) {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();	

	        $this->db->insert('planobra_po', $arrayPO);
	        if($this->db->affected_rows() != 1) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar en Planobra Po');
	        }else{
	            $flg = $this->insertFn_LogPo($itemplan, $codigoPO, $idUsuario, 5);
	            //$this->db->insert('log_planobra_po', $arrayLogPO);
	            if($flg != 1) {
	                $this->db->trans_rollback();
	                throw new Exception('Error al insertar en log_planobra_po');
	            }else{
    	            $this->db->insert('detalleplan', $arrayDetalleplan);
    	            if($this->db->affected_rows() != 1) {
    	                $this->db->trans_rollback();
    	                throw new Exception('Error al insertar en detalleplan');
    	            }else{
    	                $this->db->insert_batch('planobra_po_detalle_partida', $arrayDetallePO);
    	                if ($this->db->trans_status() === FALSE) {
    	                    $this->db->trans_rollback();
    	                    throw new Exception('Hubo un error al insertar el planobra_po_detalle_partida.');
    	                }else{
    	                    $this->db->where('itemplan', $itemplan);
    	                    $this->db->update('planobra', $arrayUpdateEstado);
    	                    if ($this->db->trans_status() === FALSE) {
        	                    $this->db->trans_rollback();
        	                    throw new Exception('Hubo un error al actualizar el estado plan.');
    	                    } else {
	                            $data['error'] = EXIT_SUCCESS;
        	                    $data['msj'] = 'Se actualizo correctamente!';
        	                    $this->db->trans_commit();
    	                    }
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
	
	function insertFn_LogPo($itemplan, $po, $idUsuario, $idEstadoPo) {
		$sql = "SELECT insert_log_planobra_diseno('".$itemplan."', '".$po."', '".$idUsuario."', '".$idUsuario."', ".$idEstadoPo.") AS flgValida";
		$result = $this->db->query($sql);
		return $result->row_array()['flgValida'];
	}
}