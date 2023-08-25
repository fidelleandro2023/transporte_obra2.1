<?php
class M_bandeja_presupuesto_oc_capex extends CI_Model{

	function __construct(){
		parent::__construct();
		
    }
	
	function getBandejaSolPdtPresupuesto() {
        $sql = " SELECT tb2.*
					FROM  (
							
								SELECT tb1.*,
									sp.monto_temporal	
								FROM 
									(
										SELECT soc.costo_sap,
											   soc.tipo_solicitud,
													soc.codigo_solicitud, 
													(CASE WHEN soc.tipo_solicitud = 1 THEN 'CREACION OC'
															WHEN soc.tipo_solicitud = 2 THEN 'EDICION OC'
																	WHEN soc.tipo_solicitud = 3 THEN 'CERTIFICACION OC'
																	WHEN soc.tipo_solicitud = 4 THEN 'ANULA POS OC'	END) AS tipo_pc, 
														DATE(soc.fecha_creacion) AS fecha_creacion, 
														DATE(soc.fecha_valida) AS fecha_validacion, 
														soc.pep1,
														soc.pep2, 
														soc.cesta, 
														soc.orden_compra, 
														soc.codigo_certificacion, 
														soe.descripcion AS estado_solicitud,
														ixs.posicion, po.itemplan,
														ixs.costo_unitario_mo AS costo, 
														p.proyectoDesc, 
														sp.subProyectoDesc, 
														e.empresaColabDesc,
														cm.contratoMarco,
														utoc.costo_unitario_mo as ultimo_costo_oc,
														po.flg_presupuesto_oc,
														mo.motivoDesc as motivo,
														po.comentarioQuiebre,
														COALESCE(sr.requerido, 0) requerido,
														po.idMotivoQuiebre,
														po.flg_regu_evi_quiebre,
														po.fecha_presupuesto_oc,
														(SELECT nombre 
														   FROM usuario 
														  WHERE id_usuario = po.usuario_rechazo) nom_quiebre,
														po.fecha_rechazo as fecha_quiebre
												FROM (proyecto p, 
														subproyecto sp, 
														empresacolab e,
														planobra po,
														solicitud_orden_compra_estado soe,
														itemplan_x_solicitud_oc ixs,
														solicitud_orden_compra soc)
											LEFT JOIN motivo mo ON mo.idMotivo = po.idMotivoQuiebre
											LEFT JOIN sap_reporte sr ON sr.codigo_solicitud = soc.codigo_solicitud
											LEFT JOIN contrato_marco cm ON e.idEmpresaColab = cm.idEmpresaColab
											LEFT JOIN itemplan_ultima_transaccion_oc utoc ON po.itemplan = utoc.itemplan
											
											WHERE sp.idSubProyecto = po.idSubProyecto 
												AND po.itemplan = ixs.itemplan
												AND soc.estado = soe.id
												AND ixs.codigo_solicitud_oc = soc.codigo_solicitud
												AND sp.idProyecto = p.idProyecto
												AND po.idEmpresaColab = e.idEmpresaColab
												-- AND p.idProyecto != 21
												AND po.flg_presupuesto_oc IN (1,2)
												-- AND soc.estado IN (1)
												-- AND soc.tipo_solicitud NOT IN (3,4)
												AND soc.tipo_solicitud IN (1,2,3)
											
												AND CASE WHEN soc.tipo_solicitud = 2 THEN soc.estado NOT IN (1,2,3)
												         ELSE soc.estado NOT IN (2,3) END
										) tb1 LEFT JOIN sap_detalle sp ON tb1.pep1 = sp.pep1
							)tb2";


        $result = $this->db->query($sql);
		// _log($this->db->last_query());

        return $result->result();
    }

	function updatePlanobra($itemplan, $arrayData) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        try {
            $this->db->where('itemplan', $itemplan);
            $this->db->update('planobra', $arrayData);
            if ($this->db->trans_status() === false) {
                throw new Exception('Hubo un error al actualizar la tabla planobra.');
            } else {
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se actualizó correctamente!!';		
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }

	function updateSolicitudesPdt($itemplan,$pep1,$pep2) {
        $sql = " UPDATE solicitud_orden_compra s, 
						itemplan_x_solicitud_oc i,
				        planobra po
				    SET s.pep1 = ?, 
				        s.pep2 = ?,
						s.fecha_creacion 	= NOW(),
						s.fecha_entrega_robot = NOW(),
						po.pep2 = ?
				  WHERE s.codigo_solicitud = i.codigo_solicitud_oc
				    AND po.itemplan = i.itemplan
				    AND s.estado IN (1,4)
				    AND po.itemplan = ? ";
        $result = $this->db->query($sql,array($pep1,$pep2,$pep2,$itemplan));
		if ($this->db->trans_status() === FALSE) {
			$data['error'] = EXIT_ERROR;
			$data['msj'] = 'No se actualizo.';
		}else{
			$data['error'] = EXIT_SUCCESS;
			$data['msj'] = 'Se actualizo correctamente!';
		}
		return $data;
    }
	
	function updateSolicitudesPdtNoPep($itemplan) {
        $sql = " UPDATE solicitud_orden_compra s, 
						itemplan_x_solicitud_oc i,
				        planobra po
				    SET s.fecha_creacion 	= now(),
						s.fecha_entrega_robot = NOW()
				  WHERE s.codigo_solicitud = i.codigo_solicitud_oc
				    AND po.itemplan = i.itemplan
				    AND s.estado IN (1,4)
				    AND po.itemplan = ? ";
        $this->db->query($sql,array($itemplan));
		
		
		if ($this->db->trans_status() === FALSE) {
			$data['error'] = EXIT_ERROR;
			$data['msj'] = 'No se actualizo.';
		}else{
			$data['error'] = EXIT_SUCCESS;
			$data['msj'] = 'Se actualizo correctamente!';
		}
		return $data;
    }
	
	function insertarLogSeguimientoSolOcRpa($arrayInsert) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert('log_seguimiento_solicitud_oc_rpa', $arrayInsert);
            if ($this->db->affected_rows() <= 0) {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al insertar en la tabla log_seguimiento_solicitud_oc_rpa.');
            } else {
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se insertó correctamente.';
                $this->db->trans_commit();
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        return $data;
    }
}