<?php

class M_creacion_oc extends CI_Model {

    //http://www.codeigniter.com/userguide3/database/results.html
    function __construct() {
        parent::__construct();
    }

    //////// . ITEMPLAM MADRE ////////

    function getBandejaSolOC($cod_solicitud, $itemplan, $estado) {
        $Query = "SELECT
	tb.*,
	(
	CASE
			
			WHEN tipo_solicitud = 1 THEN
			( SUM( CASE WHEN po_crea.itemplan_m IS NOT NULL THEN 1 ELSE 0 END ) ) 
			WHEN tipo_solicitud = 2 THEN
			( SUM( CASE WHEN po_dev.itemplan_m IS NOT NULL THEN 1 ELSE 0 END ) ) 
			WHEN tipo_solicitud = 3 THEN
			( SUM( CASE WHEN po_cer.itemplan_m IS NOT NULL THEN 1 ELSE 0 END ) ) 
			WHEN tipo_solicitud = 4 THEN
			( SUM( CASE WHEN po_anula_pos.itemplan_m IS NOT NULL THEN 1 ELSE 0 END ) ) 
		END 
		) AS numItemplan,
		(
		CASE
				
				WHEN tipo_solicitud = 1 THEN
				FORMAT( SUM( po_crea.costoEstimado ), 2 ) 
				WHEN tipo_solicitud = 2 THEN
				FORMAT( SUM( po_dev.costo_devolucion ), 2 ) 
				WHEN tipo_solicitud = 3 THEN
				FORMAT( SUM( po_cer.costo_unitario_mo_certi ), 2 ) 
				WHEN tipo_solicitud = 4 THEN
				FORMAT( SUM( po_anula_pos.costo_unitario_mo_anula_pos ), 2 ) 
			END 
			) AS costo_total 
		FROM
			(
			SELECT
				so.*,
				( CASE WHEN tipo_solicitud = 1 THEN 'CREACION OC' WHEN tipo_solicitud = 2 THEN 'EDICION OC' WHEN tipo_solicitud = 3 THEN 'CERTIFICACION OC' WHEN tipo_solicitud = 4 THEN 'ANULACION POS. OC' END ) AS tipoSolicitud,
				p.proyectoDesc,
				sp.subProyectoDesc,
				e.empresaColabDesc,
				( CASE WHEN so.estado = 1 THEN 'PENDIENTE' WHEN so.estado = 2 THEN 'ATENDIDO' WHEN so.estado = 3 THEN 'CANCELADO' END ) AS estado_sol,
				CONCAT( u.nombres, ' ', u.ape_paterno ) AS nombreCompleto 
			FROM
				empresacolab e,
				subproyecto sp,
				proyecto p,
				itemplan_madre_solicitud_orden_compra so
				LEFT JOIN usuario u ON so.usuario_valida = u.id_usuario 
			WHERE
				so.idEmpresaColab = e.idEmpresaColab 
				AND so.idSubProyecto = sp.idSubProyecto 
				AND sp.idProyecto = p.idProyecto 
				AND so.estado IN ( 1, 2 ) 
			) AS tb
			LEFT JOIN itemplan_madre po_crea ON tb.codigo_solicitud = po_crea.solicitud_oc
			LEFT JOIN itemplan_madre po_dev ON tb.codigo_solicitud = po_dev.solicitud_oc_dev
			LEFT JOIN itemplan_madre po_cer ON tb.codigo_solicitud = po_cer.solicitud_oc_certi
			LEFT JOIN itemplan_madre po_anula_pos ON tb.codigo_solicitud = po_anula_pos.solicitud_oc_anula_pos
								WHERE 1  =  1 ";
        if ($cod_solicitud != null) {
            $Query .= " and tb.codigo_solicitud = '" . $cod_solicitud . "'";
        }
        if ($itemplan != null) {
            $Query .= " and (po_crea.itemplan_m = '$itemplan'  or po_dev.itemplan_m = '$itemplan' or po_cer.itemplan_m = '$itemplan' or po_anula_pos.itemplan_m = '$itemplan')";
        }
        if ($estado != null) {
            $Query .= " and tb.estado= " . $estado;
        }
        $Query .= " Group by tb.id, tb.codigo_solicitud, tb.idEmpresaColab, tb.tipo_solicitud, 
	   tb.tipoSolicitud, tb.estado, tb.usuario_valida, tb.fecha_valida, tb.fecha_creacion, tb.idSubProyecto, 
	   tb.plan, tb.pep1, tb.pep2, tb.proyectoDesc, tb.subProyectoDesc,tb.empresaColabDesc, tb.estado_sol, 
	   tb.nombreCompleto, tb.cesta, tb.orden_compra, tb.path_oc";
        $result = $this->db->query($Query, array());
        #  log_message('error', $this->db->last_query());
        return $result->result();
    }

    public function getPtrsByHojaGestion($hoja_gestion) {
        $sql = "SELECT
	po.*,
	subProyectoDesc,
	FORMAT( po.costoEstimado, 2 ) AS limite_costo_mo
FROM
	itemplan_madre po,
	subproyecto sp 
WHERE
	po.idSubProyecto = sp.idSubProyecto
                and po.solicitud_oc = ?";
        $result = $this->db->query($sql, array($hoja_gestion));
        log_message('error', $this->db->last_query());
        return $result->result();
    }

    public function getItemOrdenCompraEdicion($codigo_solicitud) {
        $sql = "SELECT
	po.itemplan_m,
	po.cesta,
	po.orden_compra,
	po.posicion,
	subProyectoDesc,
	FORMAT( po.costo_devolucion, 2 ) AS costo_devolucion,
	pr.proyectoDesc as nombreProyecto
FROM
	itemplan_madre po,
	subproyecto sp,
	proyecto pr
WHERE
	po.idSubProyecto = sp.idSubProyecto AND
	pr.idProyecto = sp.idProyecto
	AND po.solicitud_oc_dev = ?";
        $result = $this->db->query($sql, array($codigo_solicitud));
        log_message('error', $this->db->last_query());
        return $result->result();
    }

    public function getItemOrdenCompraCerti($codigo_solicitud) {
        $sql = "SELECT
	po.itemplan_m,
	po.cesta,
	po.orden_compra,
	po.posicion,
	subProyectoDesc,
	FORMAT( po.costo_unitario_mo_certi, 2 ) AS costo_unitario_mo_certi,
	pr.proyectoDesc as nombreProyecto,
	po.solicitud_oc_certi solicitud_oc
FROM
	itemplan_madre po,
	subproyecto sp ,
	proyecto pr
WHERE
	po.idSubProyecto = sp.idSubProyecto  AND
	pr.idProyecto = sp.idProyecto
	AND po.solicitud_oc_certi = ?";
        $result = $this->db->query($sql, array($codigo_solicitud));
        log_message('error', $this->db->last_query());
        return $result->result();
    }

    public function getItemOrdenCompraAnulaPosOC($codigo_solicitud) {
        $sql = "select po.*, subProyectoDesc, FORMAT(po.costo_unitario_mo_anula_pos,2) as costo_unitario_mo_anula_pos
			    from planobra po, subproyecto sp 
				where po.idSubProyecto = sp.idSubProyecto
                and po.solicitud_oc_anula_pos = ?";
        $result = $this->db->query($sql, array($codigo_solicitud));
        log_message('error', $this->db->last_query());
        return $result->result();
    }

    function update_solicitud_oc($codigo_solicitud, $arrayData) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->where('codigo_solicitud', $codigo_solicitud);
            $this->db->update('itemplan_madre_solicitud_orden_compra', $arrayData);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al actualizar la informacion.');
            } else {
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se actualizo correctamente!';
                $this->db->trans_commit();
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    public function update_costo_update($codigo_solicitud) {
        $sql = "UPDATE itemplan_madre SET costoEstimado = (costo_devolucion) WHERE solicitud_oc_dev = '$codigo_solicitud'";
        $this->db->query($sql);
        return true;
    }
    
    function getItemplan($Itemplan) {
        $Query = 'SELECT * FROM itemplan_madre where solicitud_oc_dev = ?';
        $result = $this->db->query($Query, array($Itemplan));
        $idEstadoPlan = $result->row()->itemplan_m;
        return $idEstadoPlan;
    }
    
    function getItemplanCer($Itemplan) {
        $Query = 'SELECT * FROM itemplan_madre where solicitud_oc_certi = ?';
        $result = $this->db->query($Query, array($Itemplan));
        $idEstadoPlan = $result->row()->itemplan_m;
        return $idEstadoPlan;
    }
    
    function update_itemplan($arrayDataItem, $itemplan) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->where('itemplan_m', $itemplan);
            $this->db->update('itemplan_madre', $arrayDataItem);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al actualizar la informacion.');
            } else {
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se actualizo correctamente!';
                $this->db->trans_commit();
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function getMontoDev($Itemplan) {
        $Query = 'SELECT * FROM itemplan_madre where solicitud_oc_dev = ?';
        $result = $this->db->query($Query, array($Itemplan));
        $idEstadoPlan = $result->row()->costo_devolucion;
        return $idEstadoPlan;
    }
    
     function getMontoDevCer($Itemplan) {
        $Query = 'SELECT * FROM itemplan_madre where solicitud_oc_certi = ?';
        $result = $this->db->query($Query, array($Itemplan));
        $idEstadoPlan = $result->row()->costo_devolucion;
        return $idEstadoPlan;
    }

}
