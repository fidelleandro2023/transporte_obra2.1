<?php
class M_bandeja_presupuesto_oc_capex_eecc extends CI_Model{

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
														COALESCE(sr.requerido, 0) requerido
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
												AND po.flg_presupuesto_oc = 2
												AND soc.tipo_solicitud IN (1,2,3)
												AND soc.estado NOT IN (2,3)
												AND po.idMotivoQuiebre = 114
												AND po.comentarioQuiebre = 'REVISION DE ACTAS'
										) tb1 LEFT JOIN sap_detalle sp ON tb1.pep1 = sp.pep1
							)tb2";


        $result = $this->db->query($sql);
        return $result->result();
    }

	
}