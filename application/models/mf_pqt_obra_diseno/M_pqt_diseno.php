<?php

class M_pqt_diseno extends CI_Model {

    //http://www.codeigniter.com/userguide3/database/results.html
    function __construct() {
        parent::__construct();
    }

    function getDataDiseno($itemplan) {
        $Query = "SELECT DISTINCT 
                         po.itemPlan,
                         sp.subProyectoDesc,
                         p.idProyecto,
                         p.idProyecto,po.itemPlan, 
                         e.idEstacion,
                         po.indicador ,
                         pd.estado,
                         po.idEstadoPlan,
                         (SELECT COUNT(1) FROM diseno_planobra_cluster poc WHERE poc.itemplan = ?) AS has_cotizacion,
                         p.proyectoDesc,
                         sp.subProyectoDesc,
                         (SELECT empresaColabDesc 
                            FROM empresacolab 
                            WHERE po.idEmpresaColab = idEmpresaColab)empresaColabDesc,		
                         (CASE WHEN po.idEmpresaColabDiseno IS NOT NULL THEN (SELECT empresaColabDesc 
                                                                                FROM empresacolab 
                                                                               WHERE idEmpresaColab = po.idEmpresaColabDiseno) 
                               ELSE NULL END) as empresaColabDiseno,
                        e.estacionDesc,
                        pd.nomArchivo,
                        ep.estadoPlanDesc,
                        DATE(pd.fecha_prevista_atencion) AS fecha_prevista_atencion,
						CASE WHEN DATE(fecha_creacion) > '2020-03-15' THEN CASE WHEN po.orden_compra IS NOT NULL THEN 1
						                                                        ELSE 0 END
						     ELSE 1 END as flg_orden_compra,
                        (SELECT jefatura 
                            FROM central c
                            WHERE c.idCentral = po.idCentral) jefatura,
                            (CASE WHEN p.idProyecto = " . ID_PROYECTO_SISEGOS . " THEN (SELECT    COUNT(1) as count  
                                FROM    sisego_planobra 
                            WHERE    itemplan = po.itemPlan 
                                AND    origen = " . ID_TIPO_OBRA_FROM_DISENIO . ") ELSE 0 END) as hasSisegoPlanObra,
                                pd.path_expediente_diseno,
                                pd.fecha_ejecucion,
                                pd.usuario_ejecucion,
                                (CASE WHEN pd.requiere_licencia = 2 THEN 'NO' ELSE 'SI' END) as licencia
                                  
                    FROM pre_diseno pd LEFT JOIN 
                         planobra po    ON (pd.itemplan      = po.itemplan) INNER JOIN
                         subproyecto sp ON (po.idSubProyecto = sp.idSubProyecto) INNER JOIN
                         proyecto     p ON (sp.idProyecto    = p.idProyecto) INNER JOIN
                         estacion     e ON (pd.idEstacion = e.idEstacion) INNER JOIN
                         estadoplan   ep ON (ep.idEstadoPlan = po.idEstadoPlan)
                     AND pd.estado in (2,3)
                     AND pd.itemplan = ?
                     AND CASE WHEN sp.idTipoSubProyecto IS NOT NULL THEN sp.idTipoSubProyecto <> 2
                                        ELSE true END
                     #AND po.idEstadoPlan not IN (6,5,10)";
        $result = $this->db->query($Query, array($itemplan, $itemplan));
        return $result->result();
    }

     function existenAunEnPreDiseno($itemplan) {
        $Query = "SELECT
	COUNT( * ) count 
        FROM
	pre_diseno pre,
	planobra po,
	subproyecto sub
        WHERE
	pre.itemPlan = po.itemPlan AND
	sub.idSubProyecto = po.idSubProyecto AND
	pre.itemplan = '$itemplan' 
	AND pre.estado = 2;";
        $result = $this->db->query($Query, array($itemplan));
        return $result->row_array()['count'];
    }

    function updateEstadoPlanObraToBanEjecucion($itemplan, $estadoPlan, $idEstacion) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            /* $dataUpdate = array(
              "idEstadoPlan" => $estadoPlan,
              "usu_upd" => (($this->session->userdata('idPersonaSession') != null) ? $this->session->userdata('idPersonaSession') : 'AUTOMATICO'),
              "fecha_upd" => $this->fechaActual()
              );

              $this->db->where('itemPlan', $itemplan);
              $this->db->update('planobra', $dataUpdate); */

            $this->db->where('itemPlan', $itemplan);
            $this->db->where('idEstacion', $idEstacion);
            $this->db->update('pre_diseno', array("estado" => 3));

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                throw new Exception('Error al modificar el updateEstadoPlanObra');
            } else {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se inserto correctamente!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    function insertarDatosDisenoPlanObraCluster($dataL) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->insert('diseno_planobra_cluster', $dataL);
            if ($this->db->affected_rows() != 1) {
                throw new Exception('Error al insertar en diseno_planobra_cluster');
            } else {
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se actualizo correctamente!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }

    function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone', 'America/Lima');
        setlocale(LC_TIME, "es_ES", "esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }

    function permitirEliminarEstacionAncla($itemPlan, $idEstacion) {
        $Query = 'SELECT idpre_diseno, itemPlan, idEstacion, 
                    (SELECT COUNT(1) FROM planobra_po x WHERE x.itemPlan = pd.itemPlan AND x.idEstacion = pd.idEstacion) count,
                    estado
                    FROM pre_diseno pd 
                    WHERE pd.itemPlan = ? AND pd.idEstacion <> ?';
        $result = $this->db->query($Query, array($itemPlan, $idEstacion));
        log_message('error', $Query);
        return $result->result();
    }

    function getListEstacionesOfPreDisenoByIP($itemPlan) {
        $Query = 'SELECT idpre_diseno, itemPlan, idEstacion,
                    (SELECT COUNT(1) FROM planobra_po x WHERE x.itemPlan = pd.itemPlan AND x.idEstacion = pd.idEstacion) count,
                    estado
                    FROM pre_diseno pd
                    WHERE pd.itemPlan = ?';
        $result = $this->db->query($Query, array($itemPlan));
        log_message('error', $Query);
        return $result->result();
    }

    function eliminarPreDiseno($itemplan, $idEstacion) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->where('itemplan', $itemplan);
            $this->db->where('idEstacion', $idEstacion);
            $this->db->delete('pre_diseno');

            if ($this->db->trans_status() === true) {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se elimin&oacute; correctamente la estacion en pre_diseno.';
            } else {
                $this->db->trans_rollback();
                throw new Exception('Error al eliminar la estacion en pre_diseno');
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        return $data;
    }

    function tieneSirope($itemPlan) {
        $Query = 'SELECT COALESCE(COLESCE(has_sirope_diseno, has_sirope),\'0\') has_sirope 
                        FROM planobra x 
                        WHERE itemPlan = ?';
        $result = $this->db->query($Query, array($itemPlan));
        log_message('error', $Query);
        return $result->row_array();
    }

    function actualizarEstadoPlanObraADiseno($itemplan, $idEstadoPlan) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $dataUpdate = array(
                "idEstadoPlan" => $idEstadoPlan,
                "usu_upd" => (($this->session->userdata('idPersonaSession') != null) ? $this->session->userdata('idPersonaSession') : 'AUTOMATICO'),
                "fecha_upd" => $this->fechaActual()
            );

            $this->db->where('itemPlan', $itemplan);
            $this->db->update('planobra', $dataUpdate);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                throw new Exception('Error al modificar el actualizarEstadoPlanObraADiseno');
            } else {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se inserto correctamente!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    #automatico en aprobacion por configuracion

    public function changeEstadoEnAprobacionFromEjecucionNoLicencia($itemplan) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $this->db->trans_begin();
            $dataUpdate = array(
                'idEstadoPlan' => ID_ESTADO_EN_LICENCIA,
                'usu_upd' => $this->session->userdata('idPersonaSession'),
                'fecha_upd' => $this->fechaActual()
            );
            $this->db->where('itemPlan', $itemplan);
            $this->db->update('planobra', $dataUpdate);
            if ($this->db->trans_status() === false) {
                throw new Exception('Hubo un error al actualizar el estadoplan.');
            } else {

                $dataUpdate = array(
                    'idEstadoPlan' => ID_ESTADO_EN_APROBACION,
                    'usu_upd' => $this->session->userdata('idPersonaSession'),
                    'fecha_upd' => $this->fechaActual(),
                    'descripcion' => 'NO REQUIERE LICENCIA'
                );
                $this->db->where('itemPlan', $itemplan);
                $this->db->update('planobra', $dataUpdate);
                if ($this->db->trans_status() === false) {
                    throw new Exception('Hubo un error al actualizar el estadoplan.');
                } else {
                    $dataUpdateLog = array(
                        'tabla' => 'planobra',
                        'actividad' => 'update',
                        'itemplan' => $itemplan,
                        'itemplan_default' => 'idEstadoPlan=' . ID_ESTADO_EN_APROBACION . '| NO REQUIERE LICENCIA',
                        'fecha_registro' => $this->fechaActual(),
                        'id_usuario' => $this->session->userdata('idPersonaSession'),
                    );

                    $this->db->insert('log_planobra', $dataUpdateLog);
                    log_message('error', $this->db->last_query());
                    if ($this->db->affected_rows() != 1) {
                        $this->db->trans_rollback();
                        throw new Exception('Hubo un error al actualizar el estadoplan.');
                    } else {
                        $data['error'] = EXIT_SUCCESS;
                        $data['msj'] = 'Se actualizo correctamente!';
                        $this->db->trans_commit();
                    }
                }
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    public function updatePreDisenoLicenciaLiquidad($itemplan, $idEstacion, $arrayData) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        try {
            $this->db->trans_begin();
            $this->db->where('itemplan', $itemplan);
            $this->db->where('idEstacion', $idEstacion);
            $this->db->update('pre_diseno', $arrayData);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al actualizar pre_diseno, Finalizacion de En Licencia.');
            } else {
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se actualiz&oacute; correctamente!';
                $this->db->trans_commit();
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        return $data;
    }

    function getDataDisenoEjecutadoByItemplanEstacion($itemplan, $idEstacion) {
        $sql = "SELECT  po.itemplan,
                        po.indicador,
                        pc.fecha_registro,
                        po.fecha_creacion,
                        pc.estado,
                        pc.flg_principal,
                        po.idEstadoPlan,
                        pc.codigo_cluster,
                        pc.nodo_principal,
                        pc.nodo_respaldo,
                        pc.facilidades_de_red, 
                        pc.cant_cto, 
                        pc.metro_tendido_aereo, 
                        pc.metro_tendido_subterraneo,
                        pc.metors_canalizacion,
                        pc.cant_camaras_nuevas, 
                        pc.cant_postes_nuevos,
                        pc.cant_postes_apoyo,
                        pc.cant_apertura_camara,
                        pc.requiere_seia,
                        pc.requiere_aprob_mml_mtc,
                        pc.requiere_aprob_inc,
                        pc.duracion,
                        tp.descripcion AS desc_tipo_diseno,
                        pc.costo_materiales,
                        pc.costo_mano_obra,
                        pc.costo_diseno,
                        pc.costo_expe_seia_cira_pam,
                        pc.costo_adicional_rural,
                        pc.costo_total,
                        pc.comentario
                   FROM planobra po, 
                        diseno_planobra_cluster pc
                        LEFT JOIN tipo_diseno tp ON tp.id_tipo_diseno      = pc.id_tipo_diseno
                  WHERE po.itemplan            = ?
                    AND pc.planobra_clustercol = ?
                    AND po.itemplan            = pc.itemplan;";
        $result = $this->db->query($sql, array($itemplan, $idEstacion));
        return $result->row_array();
    }

    function getEntidadesSelectedByEstacionItemplan($itemplan, $idEstacion) {
        $sql = "SELECT tab.*,
                       CASE WHEN ield.idEntidad IS NOT NULL THEN 'checked' ELSE '' END AS checked
                  FROM (SELECT e.idEntidad,
                               e.desc_entidad
                          FROM entidad e
                         UNION ALL 
                        SELECT 999999 AS idEntidad,
                               'NO REQUIERE LICENCIA' AS desc_entidad) AS tab
                       LEFT JOIN itemplan_estacion_licencia_det ield ON ield.itemplan   = ?
                                                                    AND ield.idEstacion = ?
                                                                    AND tab.idEntidad   = ield.idEntidad";
        $result = $this->db->query($sql, array($itemplan, $idEstacion));
        return $result->result();
    }

    function simularLicencia($itemplan, $arrayEntidades) {

        if ($arrayEntidades == 0) {
            $sql = "INSERT INTO itemplan_po_licencia_simu
SELECT DISTINCT
	   t.itemplan,
		 t.itemplanPE,
	   t.idEstacion,
	   ROUND(SUM(t.baremo * t.cantidad * t.costo),2) AS total
  FROM ( 
SELECT po.itemplanPE,po.itemplan, pre.idEstacion, pa.baremo, pre.costo, (case when pa.idActividad = 267 then " . $arrayEntidades . "
 else 0 end) as cantidad
FROM partida_subproyecto ps, partidas pa, preciario pre, planobra po  WHERE po.idSubProyecto = ps.idSubProyecto
AND ps.idPartida = pa.idActividad
AND pa.idPrecioDiseno  = pre.idPrecioDiseno
AND	pa.flg_reg_grafico = 2
AND pre.idZonal = po.idZonal
AND pre.idEmpresaColab = po.idEmpresaColab
AND pre.idEstacion = 20
AND po.itemplan = '$itemplan'
)t
GROUP BY itemplan, idEstacion";
        } else {
            $sql = "INSERT INTO itemplan_po_licencia_simu
SELECT DISTINCT
	   t.itemplan,
		 t.itemplanPE,
	   t.idEstacion,
	   ROUND(SUM(t.baremo * t.cantidad * t.costo),2) AS total
  FROM ( 
SELECT po.itemplanPE,po.itemplan, pre.idEstacion, pa.baremo, pre.costo, (case when pa.idActividad = 267 then " . $arrayEntidades . "
 else 1 end) as cantidad
FROM partida_subproyecto ps, partidas pa, preciario pre, planobra po  WHERE po.idSubProyecto = ps.idSubProyecto
AND ps.idPartida = pa.idActividad
AND pa.idPrecioDiseno  = pre.idPrecioDiseno
AND	pa.flg_reg_grafico = 2
AND pre.idZonal = po.idZonal
AND pre.idEmpresaColab = po.idEmpresaColab
AND pre.idEstacion = 20
AND po.itemplan = '$itemplan'
)t
GROUP BY itemplan, idEstacion";
        }


        $this->db->query($sql);
        if ($this->db->affected_rows() >= 0) {
            return true;
        } else {
            return false;
        }
    }

    function simularLicenciaDetalle($itemplan, $arrayEntidades) {

        if ($arrayEntidades == 0) {
            $sql = "INSERT INTO itemplan_po_licencia_simu_detalle
SELECT po.itemplan,pa.*, pre.idZonal,pre.idEmpresaColab,pre.costo,pre.idEstacion, (case when pa.idActividad = 267 then " . $arrayEntidades . "
 else 0 end) as cantidad
FROM partida_subproyecto ps, partidas pa, preciario pre, planobra po 
WHERE po.idSubProyecto = ps.idSubProyecto
AND ps.idPartida = pa.idActividad
AND pa.idPrecioDiseno  = pre.idPrecioDiseno
AND	pa.flg_reg_grafico = 2
AND pre.idZonal = po.idZonal
AND pre.idEmpresaColab = po.idEmpresaColab
AND pre.idEstacion = 20
AND po.itemplan = '$itemplan';";
        } else {
            $sql = "INSERT INTO itemplan_po_licencia_simu_detalle
SELECT po.itemplan,pa.*, pre.idZonal,pre.idEmpresaColab,pre.costo,pre.idEstacion, (case when pa.idActividad = 267 then " . $arrayEntidades . "
 else 1 end) as cantidad
FROM partida_subproyecto ps, partidas pa, preciario pre, planobra po 
WHERE po.idSubProyecto = ps.idSubProyecto
AND ps.idPartida = pa.idActividad
AND pa.idPrecioDiseno  = pre.idPrecioDiseno
AND	pa.flg_reg_grafico = 2
AND pre.idZonal = po.idZonal
AND pre.idEmpresaColab = po.idEmpresaColab
AND pre.idEstacion = 20
AND po.itemplan = '$itemplan';";
        }


        $this->db->query($sql);
        if ($this->db->affected_rows() >= 0) {
            return true;
        } else {
            return false;
        }
    }

    function countLicencia($itemplan, $arrayEntidades) {

        if ($arrayEntidades == 0) {
             $sql = "SELECT po.itemplan,pa.*, pre.idZonal,pre.idEmpresaColab,pre.costo,pre.idEstacion, (case when pa.idActividad = 267 then " . $arrayEntidades . " 
 else 0 end) as cantidad
FROM partida_subproyecto ps, partidas pa, preciario pre, planobra po 
WHERE po.idSubProyecto = ps.idSubProyecto
AND ps.idPartida = pa.idActividad
AND pa.idPrecioDiseno  = pre.idPrecioDiseno
AND	pa.flg_reg_grafico = 2
AND pre.idZonal = po.idZonal
AND pre.idEmpresaColab = po.idEmpresaColab
AND pre.idEstacion = 20
AND po.itemplan = '$itemplan';";
        } else {
             $sql = "SELECT po.itemplan,pa.*, pre.idZonal,pre.idEmpresaColab,pre.costo,pre.idEstacion, (case when pa.idActividad = 267 then " . $arrayEntidades . " 
 else 1 end) as cantidad
FROM partida_subproyecto ps, partidas pa, preciario pre, planobra po 
WHERE po.idSubProyecto = ps.idSubProyecto
AND ps.idPartida = pa.idActividad
AND pa.idPrecioDiseno  = pre.idPrecioDiseno
AND	pa.flg_reg_grafico = 2
AND pre.idZonal = po.idZonal
AND pre.idEmpresaColab = po.idEmpresaColab
AND pre.idEstacion = 20
AND po.itemplan = '$itemplan';";
        }

       
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

}
