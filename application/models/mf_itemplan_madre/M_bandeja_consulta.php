<?php

class M_bandeja_consulta extends CI_Model {

    //http://www.codeigniter.com/userguide3/database/results.html
    function __construct() {
        parent::__construct();
    }
    
    public function countItemplanMadre($ItemplanMadre) {
        $sql = "SELECT * FROM itemplan_madre_detalle_obras_publicas WHERE itemplan='$ItemplanMadre'";
        $result = $this->db->query($sql);
        return $result->num_rows();
    }
    
    function saveDetalleObraPublica($dataInsert) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $this->db->trans_begin();
            $this->db->insert('itemplan_madre_detalle_obras_publicas', $dataInsert);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar el sisego_planobra');
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

    function getDataTablaItemMadre($ItemplanmMadre) {

        if ($ItemplanmMadre) {
            $sql = "SELECT
	proyectoDesc,
	subproyectoDesc AS subDesc,
	i.itemplan_m,
	DATE_FORMAT(de.fecha_recepcion,'%Y/%m/%d') fecha_registro,
	i.nombre,
	i.idPrioridad,
	de.nombre_cliente,
	de.numero_carta,
	i.carta_pdf,
	eecc.empresaColabDesc,
	i.solicitud_oc as codigo_solicitud,
	i.orden_compra,
	FORMAT( i.costoEstimado, 2 ) costoEstimado,
        CASE
		WHEN i.idPrioridad = 1 THEN
		'SI' ELSE 'NO' 
	END AS prioridad,
	compra.pep1,
	es.estadoPlanDesc,
	es.idEstadoPlan
        FROM
	itemplan_madre i
	LEFT JOIN subproyecto s ON s.idSubProyecto = i.idSubProyecto
	LEFT JOIN proyecto p ON p.idProyecto = s.idProyecto
	LEFT JOIN empresacolab eecc ON eecc.idEmpresaColab = i.idEmpresaColab
	LEFT JOIN estadoplan es ON es.idEstadoPlan = i.idEstado
	LEFT JOIN itemplan_madre_detalle_obras_publicas de ON de.itemplan = i.itemplan_m #INNER JOIN empresacolab eecc on eecc.
	LEFT JOIN itemplan_madre_solicitud_orden_compra compra ON compra.codigo_solicitud = i.solicitud_oc
        WHERE i.itemplan_m=TRIM('$ItemplanmMadre')
        ORDER BY i.fecha_registro DESC";
        } else {
            $sql = "SELECT
	proyectoDesc,
	subproyectoDesc AS subDesc,
	i.itemplan_m,
	DATE_FORMAT(de.fecha_recepcion,'%Y/%m/%d') fecha_registro,
	i.nombre,
	i.idPrioridad,
	de.nombre_cliente,
	de.numero_carta,
	i.carta_pdf,
	eecc.empresaColabDesc,
	i.solicitud_oc as codigo_solicitud,
	i.orden_compra,
	FORMAT( i.costoEstimado, 2 ) costoEstimado,
        CASE
		WHEN i.idPrioridad = 1 THEN
		'SI' ELSE 'NO' 
	END AS prioridad,
	compra.pep1,
	es.estadoPlanDesc,
	es.idEstadoPlan
        FROM
	itemplan_madre i
	LEFT JOIN subproyecto s ON s.idSubProyecto = i.idSubProyecto
	LEFT JOIN proyecto p ON p.idProyecto = s.idProyecto
	LEFT JOIN empresacolab eecc ON eecc.idEmpresaColab = i.idEmpresaColab
	LEFT JOIN estadoplan es ON es.idEstadoPlan = i.idEstado
	LEFT JOIN itemplan_madre_detalle_obras_publicas de ON de.itemplan = i.itemplan_m #INNER JOIN empresacolab eecc on eecc.
	LEFT JOIN itemplan_madre_solicitud_orden_compra compra ON compra.codigo_solicitud = i.solicitud_oc
  ORDER BY i.fecha_registro DESC";
        }

        $result = $this->db->query($sql);
        return $result->result_array();
    }

    function getSAPdetalle($pep) {
        $Query = "SELECT * FROM sap_detalle WHERE pep1='$pep';";
        $result = $this->db->query($Query, array());
        return $result->result_array();
    }

    function hijosItemMadreDetalle($itemplan_madre) {
        $Query = "SELECT
	po.*,
	sb.subProyectoDesc,
	pr.proyectoDesc,
	em.empresaColabDesc,
	est.estadoPlanDesc
        FROM
	planobra po,
	subproyecto sb,
	proyecto pr ,
	empresacolab em,
	estadoplan est
        WHERE
	po.idSubProyecto = sb.idSubProyecto 
	AND sb.idProyecto = pr.idProyecto
	AND em.idEmpresaColab = po.idEmpresaColab 
	AND est.idEstadoPlan = po.idEstadoPlan AND
	itemPlanPE = '$itemplan_madre';";
        $result = $this->db->query($Query, array());
        return $result->result();
    }

    function montoToltal($itemplan) {
        $sql = "SELECT DISTINCT 
                      ppo.codigo_po,
                      e.estacionDesc,
                      a.tipoArea,
                      poe.estado,
                      ppo.pep2,
                      ppo.grafo,
                      CASE WHEN flg_tipo_area = 1 THEN ppo.costo_total 
                           ELSE 0 END AS total_mat,
                      CASE WHEN flg_tipo_area = 2 THEN ppo.costo_total 
                           ELSE 0 END AS total_mo
                 FROM planobra_po ppo,
                       estacion e,
                       po_estado poe,
                       detalleplan dp,
                       subproyectoestacion sp,
                       estacionarea ea,
                	   area a
                WHERE e.idEstacion      = ppo.idEstacion
                  AND poe.idPoEstado    = ppo.estado_po
                  AND dp.poCod          = ppo.codigo_po
                  AND dp.itemplan       = ppo.itemplan
                  AND sp.idEstacionarea = ea.idEstacionArea
                  AND a.idArea          = ea.idArea
                  AND ppo.estado_po    <> 8
                  AND sp.idSubProyectoEstacion = dp.idSubProyectoEstacion
                  AND ppo.itemplan = COALESCE(?, ppo.itemplan)
                  UNION ALL
                  SELECT we.ptr,
                        e.estacionDesc,
                        we.desc_area,
                        ep.estadoPoDesc,
                        we.pep,
                        we.grafo,
                        valoriz_material AS total_mat,
                        valoriz_m_o AS total_mo 
                    FROM web_unificada we,
                        detalleplan dp,
                        estadoptr ep,
                        estacion e,
                        estacionarea ea,
                        subproyectoestacion se
                    WHERE we.ptr                 = dp.poCod
                    AND we.idEstadoPtr           = ep.idEstadoPo
                    AND e.idEstacion             = ea.idEstacion 
                    AND ea.idEstacionArea        = se.idEstacionArea
                    AND dp.idSubProyectoEstacion = se.idSubProyectoEstacion
                    AND ep.idEstadoPo           <> 6
                    AND dp.itemPlan             = COALESCE(?, dp.itemPlan)";
        $result = $this->db->query($sql, array($itemplan, $itemplan));
        return $result->result();
    }

    function updateConPrioridad($objReg, $itemplanM) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->where('itemplan_m', $itemplanM);
            $this->db->update('itemplan_madre', $objReg);
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Hubo un error al actualizar la carta del Itemplan Madre.');
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

    function cantidadItemplanHijos($Itemplan) {
        $Query = 'SELECT COUNT(*) total FROM planobra WHERE itemPlanPE = ?';
        $result = $this->db->query($Query, array($Itemplan));
        $idEstadoPlan = $result->row()->total;
        return $idEstadoPlan;
    }

    function updateConPrioridadDetalle($objReg, $itemplanM) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->where('itemplan', $itemplanM);
            $this->db->update('itemplan_madre_detalle_obras_publicas', $objReg);
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Hubo un error al actualizar la carta del Itemplan Madre.');
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

    public function getEditItemplanMadre($ItemplanmMadre) {
        $sql = "SELECT
        i.idSubProyecto,
	proyectoDesc,
	subproyectoDesc AS subDesc,
	i.itemplan_m,
	DATE_FORMAT(de.fecha_recepcion,'%Y/%m/%d') fecha_registro,
	i.nombre,
	i.idPrioridad,
	de.nombre_cliente,
	de.numero_carta,
	i.carta_pdf,
	eecc.empresaColabDesc,
	compra.codigo_solicitud,
	compra.orden_compra,
	FORMAT( i.costoEstimado, 2 ) costoEstimado,
        CASE
        WHEN i.idPrioridad = 1 THEN
		'SI' ELSE 'NO' 
	END AS prioridad 
        FROM
	itemplan_madre i
	INNER JOIN subproyecto s ON s.idSubProyecto = i.idSubProyecto
	INNER JOIN proyecto p ON p.idProyecto = s.idProyecto
	INNER JOIN empresacolab eecc ON eecc.idEmpresaColab = i.idEmpresaColab
	LEFT JOIN itemplan_madre_detalle_obras_publicas de ON de.itemplan = i.itemplan_m #INNER JOIN empresacolab eecc on eecc.
	LEFT JOIN itemplan_madre_solicitud_orden_compra compra ON compra.codigo_solicitud = i.solicitud_oc
        WHERE i.itemplan_m=TRIM('$ItemplanmMadre')";
        $result = $this->db->query($sql);
        return $result->result();
    }

}
