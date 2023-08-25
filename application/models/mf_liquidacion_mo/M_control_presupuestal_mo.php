<?php
class M_control_presupuestal_mo extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
    }

    function getBandejaControlPresupuestalMo($itemplan, $codigoSolicitud, $situacion) {
        $Query = "SELECT s.codigo_solicitud, 
                         s.itemplan,
                         s.codigo_po,
                         CASE WHEN s.estado = 0 THEN 'PENDIENTE'
                              WHEN s.estado = 1 THEN 'VALIDADO'
                              WHEN s.estado = 2 THEN 'RECHAZADO' END situacion,
                         ROUND(SUM(monto_final), 2) - ROUND(s.total_actual,2) AS total_excede,
                         ROUND(SUM(monto_final), 2) total,
						 UPPER(CONCAT(u_sol.nombres,' ', u_sol.ape_paterno,' ', u_sol.ape_materno)) AS usuarioReg,
						 UPPER(CONCAT(u_val.nombres,' ', u_val.ape_paterno,' ', u_val.ape_materno)) AS usua_valida,
                         DATE(s.fechaRegistro) fechaRegistro,
                         e.estacionDesc,
						 s.total_actual,
						 DATE(s.fechaValida) fechaValida,
						 su.subProyectoDesc,
						 em.empresaColabDesc,
						 z.zonalDesc,
						 s.comentario_reg,
						 p.proyectoDesc,
						 s.url_archivo,
						 s.comentario_valida
                    FROM (solicitud_presupuestal_mo s,
						 solicitud_presupuestal_mo_detalle sd,
                         usuario u,
                         estacion e,
						 planobra po,
						 subproyecto su,
						 empresacolab em,
						 zonal z,
						 proyecto p) 
			  LEFT JOIN  usuario u_sol on s.idUsuario       = u_sol.id_usuario
              LEFT JOIN  usuario u_val on s.idUsuarioValida =  u_val.id_usuario
                   WHERE em.idEmpresaColab = po.idEmpresaColab
				     AND p.idProyecto       = su.idProyecto
					 AND z.idZonal          = po.idZonal
					 AND s.codigo_solicitud = COALESCE(?, s.codigo_solicitud)
                     AND s.itemplan         = COALESCE(?, s.itemplan)
                     AND s.estado           = COALESCE(?, s.estado)
					 AND sd.codigo_solicitud =  s.codigo_solicitud
					 AND sd.codigo_po = s.codigo_po
                     AND u.id_usuario = s.idUsuario
                     AND e.idEstacion = s.idEstacion
					 AND po.itemplan  = s.itemplan
					 AND po.idSubProyecto = su.idSubProyecto
					 GROUP BY s.codigo_solicitud";
          
        $result = $this->db->query($Query, array($codigoSolicitud, $itemplan, $situacion));
        return $result->result_array();           
    }

    function getBandejaControlPresupuestalMoDetalle($codigoSolicitud, $codigo_po) {
        $Query = " SELECT codigo_po,
                            idActividad,
                            baremo,
                            costo,
                            cantidad_inicial,
                            monto_inicial,
                            cantidad_final,
                            monto_final 
                     FROM solicitud_presupuestal_mo_detalle
                    WHERE codigo_po = ?
                      AND codigo_solicitud = ?";
          
        $result = $this->db->query($Query, array($codigo_po, $codigoSolicitud));
        return $result->result_array();           
    }
    

    function updateEstadoSolicitudMoValida($dataUpdateSolicitud, $codigoSolicitud, $codigo_po, $costoTotal, $itemplan, $dataItem){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->trans_begin();
            $data = $this->updateEstadoSolicitudMo($dataUpdateSolicitud, $codigoSolicitud);
			// if($data['error'] == EXIT_SUCCESS) {
				// $this->db->where('itemplan', $itemplan);
				// $this->db->update('planobra', $dataItem);
				
				if($data['error'] == EXIT_SUCCESS) {
					$sql = "  DELETE ppd
								FROM planobra_po_detalle_mo ppd
							   WHERE codigo_po = ?";
					$this->db->query($sql, array($codigo_po));

					if($this->db->trans_status() === TRUE) {
						$this->db->where('codigo_po', $codigo_po);
						$this->db->update('planobra_po', array('costo_total' => $costoTotal));

						if($this->db->affected_rows() != 1) {
							$this->db->trans_rollback();
							throw new Exception('Error al actualizar la informacion.');
						} else {
							$dataArray = $this->getBandejaControlPresupuestalMoDetalle($codigoSolicitud, $codigo_po);
							foreach($dataArray as $row) {
								
								// $this->db->where('codigo_po' , $codigo_po);              
								$this->db->insert('planobra_po_detalle_mo', array( 'codigo_po' => $row['codigo_po'],
																				'idActividad' => $row['idActividad'],
																				'baremo' => $row['baremo'],
																				'costo' => $row['costo'],
																				'cantidad_inicial' => $row['cantidad_inicial'],
																				'cantidad_final' => $row['cantidad_final'],
																				'monto_final' => $row['monto_final']));
								if ($this->db->trans_status() === FALSE) {
									$this->db->trans_rollback();
									throw new Exception('Error al actualizar la informacion.');
								} else {
									$data['error'] = EXIT_SUCCESS;
									$data['msj'] = 'Se actualizo correctamente!';
									$this->db->trans_commit();
								}
								
							}
						}
					} else {
						$this->db->trans_rollback();
						throw new Exception('Error al actualizar la informacion.');
					}
				} else {
					$this->db->trans_rollback();
					throw new Exception('Error al actualizar la informacion.');
				}
			// } else {
				// $this->db->trans_rollback();
				// throw new Exception('Error al actualizar la informacion.');
			// }
        }catch(Exception $e){
            $data['msj']   = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function updateEstadoSolicitudMo($dataUpdateSolicitud, $codigoSolicitud){
        $this->db->where('codigo_solicitud', $codigoSolicitud);
        $this->db->update('solicitud_presupuestal_mo', $dataUpdateSolicitud);	
        if($this->db->affected_rows() != 1) {
            $data['error'] = EXIT_ERROR;
            $data['msj'] = 'Error al actualizar la informacion.';
        }else{              
            $data['error'] = EXIT_SUCCESS;
            $data['msj'] = 'Se actualizo correctamente!';
        }
        return $data;
    }
    
    function registrarSolicitudCP($dataInsert){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->trans_begin();
            $this->db->insert('solicitud_exceso_obra', $dataInsert);
            if($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar en solicitud_exceso_obra');
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
	
	function getDataSolicitudMo($codigo_po, $cod_sol) {
		$sql = "SELECT pa.codigo,
					   pa.descripcion,
					   s.baremo,
					   s.costo,
					   s.cantidad_final,
                       CASE WHEN pmo.cantidad_final IS NULL THEN 0
					        ELSE pmo.cantidad_final END as cantidad_actual,
                       CASE WHEN pmo.cantidad_final IS NULL THEN 0 
					        ELSE ROUND(pmo.baremo*pmo.costo*pmo.cantidad_final, 2) END total_actual,
					   ROUND(s.baremo*s.costo*s.cantidad_final, 2) total_partida,
					   so.comentario_reg,
					   so.comentario_valida
				  FROM (solicitud_presupuestal_mo_detalle s,
					   partidas pa,
					   solicitud_presupuestal_mo so)
			 LEFT JOIN planobra_po_detalle_mo pmo ON (s.codigo_po = pmo.codigo_po AND pmo.idActividad = s.idActividad)
				 WHERE s.idActividad = pa.idActividad
				   AND s.codigo_po = ?
				   AND s.codigo_solicitud = ?
				   AND so.codigo_solicitud = s.codigo_solicitud
			
			UNION ALL 
			
			SELECT pa.codigo,
					   pa.descripcion,
					   pmo.baremo,
					   pmo.costo,
					   pmo.cantidad_final,
                       CASE WHEN pmo.cantidad_final IS NULL THEN 0
					        ELSE pmo.cantidad_final END as cantidad_actual,
                       CASE WHEN pmo.cantidad_final IS NULL THEN 0 
					        ELSE ROUND(pmo.baremo*pmo.costo*pmo.cantidad_final, 2) END total_actual,
					   ROUND(s.baremo*s.costo*s.cantidad_final, 2) total_partida,
					   so.comentario_reg,
					   so.comentario_valida
				  FROM (planobra_po_detalle_mo pmo,
					   partidas pa)
			 LEFT JOIN (solicitud_presupuestal_mo_detalle s,
					   solicitud_presupuestal_mo so) ON (so.codigo_solicitud = s.codigo_solicitud AND 
															s.idActividad = pa.idActividad 
															AND s.codigo_po = pmo.codigo_po 
															AND pmo.idActividad = s.idActividad
															AND s.codigo_solicitud = ?)
				 WHERE pa.idActividad = pmo.idActividad
				   AND pmo.codigo_po = ?
				   AND s.codigo_po IS NULL";
		$result = $this->db->query($sql, array($codigo_po, $cod_sol, $cod_sol, $codigo_po));
		return $result->result_array();
	}
    
	function getDataPoDetalleMo($codigo_po) {
		$sql = "SELECT pa.codigo,
					   pa.descripcion,
					   s.baremo,
					   s.costo,
					   s.cantidad_final,
					   ROUND(s.baremo*s.costo*s.cantidad_final, 2) total_partida,
					   so.comentario_reg
				  FROM planobra_po_detalle_mo s,
					   partidas pa,
					   solicitud_presupuestal_mo so
				 WHERE s.idActividad = pa.idActividad
				   AND so.codigo_solicitud = s.codigo_solicitud
				   AND s.codigo_po = ?";
		$result = $this->db->query($sql, array($codigo_po));
		return $result->result_array();
	}
}