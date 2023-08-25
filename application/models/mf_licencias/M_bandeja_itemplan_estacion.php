<?php
class M_bandeja_itemPlan_estacion extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getEmpresasColab()
    {
        $sql = "SELECT * FROM empresacolab";
        $result = $this->db->query($sql);
        return $result->result();

    }
    
    public function getFase()
    {
        $sql = "SELECT f.idFase,f.faseDesc FROM fase f WHERE f.idFase != 1 ORDER by f.faseDesc";
        $result = $this->db->query($sql);
        return $result->result();

    }

    public function getBandejaItemPlanEstacion($cadena, $idEmpresaColab)
    {
        if ($cadena == null) {
            if ($idEmpresaColab != 0) {
                $cadena = " AND tb.idEmpresaColab = " . $idEmpresaColab;
            }else{
                $cadena = " ";
            }
        }
        
        $sql = "
                                   SELECT tb.*,tb1.total_entidades
                                     FROM (        SELECT DISTINCT po.idEmpresaColab,
                                                                   ea.idEstacion,
                                                                   po.itemPlan,
                                                                   po.idEstadoPlan,
                                                                   po.indicador,
                                                                   sp.idSubProyecto,
                                                                   p.idProyecto,
                                                                   sp.subProyectoDesc,
                                                                   z.zonalDesc,
                                                                   ec.empresaColabDesc,
                                                                   ep.estadoPlanDesc,
                                                                   CASE
                                                                         WHEN substr(po.fechaPrevEjec,6,2) = '01'  THEN  'ENE'
                                                                         WHEN substr(po.fechaPrevEjec,6,2) = '02'  THEN  'FEB'
                                                                         WHEN substr(po.fechaPrevEjec,6,2) = '03'  THEN  'MAR'
                                                                         WHEN substr(po.fechaPrevEjec,6,2) = '04'  THEN  'ABR'
                                                                         WHEN substr(po.fechaPrevEjec,6,2) = '05'  THEN  'MAY'
                                                                         WHEN substr(po.fechaPrevEjec,6,2) = '06'  THEN  'JUN'
                                                                         WHEN substr(po.fechaPrevEjec,6,2) = '07'  THEN  'JUL'
                                                                         WHEN substr(po.fechaPrevEjec,6,2) = '08'  THEN  'AGO'
                                                                         WHEN substr(po.fechaPrevEjec,6,2) = '09'  THEN  'SEP'
                                                                         WHEN substr(po.fechaPrevEjec,6,2) = '10'  THEN  'OCT'
                                                                         WHEN substr(po.fechaPrevEjec,6,2) = '11'  THEN  'NOV'
                                                                         WHEN substr(po.fechaPrevEjec,6,2) = '12'  THEN  'DIC'
                                                                    ELSE NULL
                                                                    END as fechaPrevEjec,
                                                                    e.estacionDesc,
                                                                    c.jefatura,
                                                                    (CASE WHEN (  SELECT c.jefatura
                                                                                    FROM central AS c,
                                                                                         planobra AS p
                                                                                   WHERE c.idCentral = p.idCentral
                                                                                     AND p.itemPlan = po.itemplan) = 'LIMA' THEN 1 ELSE 0 END) AS flg_provincia,
                                                                    ( SELECT COUNT(1)
                                                                        FROM itemplan_estacion_licencia_det ie,
                                                                             reembolso r
                                                                       WHERE ie.itemplan = po.itemplan
                                                                         AND ie.idEstacion = e.idEstacion
                                                                         AND ie.iditemplan_estacion_licencia_det = r.iditemplan_estacion_licencia_det
                                                                         AND r.estado_valida = 2) AS cant_ent_gestionada,
                                        f.idFase
                                                     FROM proyecto p, subproyecto sp, zonal z, central c,empresacolab ec, estadoplan ep, subproyectoestacion se, estacionarea ea ,
                                                          estacion e, planobra po,  itemplan_estacion_licencia_det ie, fase f
                                                    WHERE po.idSubProyecto = sp.idSubProyecto
                                                      AND sp.idProyecto = p.idProyecto
                                                      AND po.idZonal = z.idZonal
                                                      AND po.idCentral = c.idCentral
                                                      AND c.idEmpresaColab = ec.idEmpresaColab
                                                      AND po.idEstadoPlan = ep.idEstadoPlan
                                                      AND po.idSubProyecto = se.idSubProyecto
                                                      AND se.idEstacionArea = ea.idEstacionArea
                                                      AND ea.idEstacion = e.idEstacion
                                                      AND ea.idEstacion in (2,5)
                                                      AND ep.idEstadoPlan NOT IN (1,2,5,6,8)
                                                      AND po.itemplan = ie.itemPlan
                                                      AND e.idEstacion = ie.idEstacion
                                                      AND po.idSubProyecto != 97
                                                      AND po.idFase = f.idFase) AS tb,
                                          (SELECT DISTINCT po.idEmpresaColab,
                                                           ea.idEstacion,
                                                           po.itemPlan,
                                                           po.idEstadoPlan,
                                                           sp.idSubProyecto,
                                                           p.idProyecto,
                                                           ( SELECT COUNT(1)
                                                               FROM itemplan_estacion_licencia_det
                                                              WHERE itemplan = po.itemplan
                                                                AND idEstacion = e.idEstacion) AS total_entidades,
                                        f.idFase
                                                      FROM proyecto p, subproyecto sp, zonal z, central c,empresacolab ec, estadoplan ep, subproyectoestacion se, estacionarea ea ,
                                                           estacion e, planobra po,  itemplan_estacion_licencia_det ie, fase f
                                                     WHERE po.idSubProyecto = sp.idSubProyecto
                                                       AND sp.idProyecto = p.idProyecto
                                                       AND po.idZonal = z.idZonal
                                                       AND po.idCentral = c.idCentral
                                                       AND c.idEmpresaColab = ec.idEmpresaColab
                                                       AND po.idEstadoPlan = ep.idEstadoPlan
                                                       AND po.idSubProyecto = se.idSubProyecto
                                                       AND se.idEstacionArea = ea.idEstacionArea
                                                       AND ea.idEstacion = e.idEstacion
                                                       AND ea.idEstacion in (2,5)
                                                       AND ep.idEstadoPlan NOT IN (1,2,5,6,8)
                                                       AND po.itemplan = ie.itemPlan
                                                       AND e.idEstacion = ie.idEstacion
                                                       AND po.idSubProyecto != 97 AND po.idFase = f.idFase) AS tb1
                                                     WHERE tb.idEmpresaColab = tb1.idEmpresaColab
                                                       AND tb.idEstacion = tb1.idEstacion
                                                       AND tb.itemplan = tb1.itemplan
                                                       AND tb.idEstadoPlan = tb1.idEstadoPlan
                                                       AND tb.idSubProyecto = tb1.idSubproyecto
                                                       AND tb.idProyecto = tb1.idProyecto
                                                       AND tb.cant_ent_gestionada != tb1.total_entidades" . $cadena;

        $result = $this->db->query($sql);
        return $result->result();
    }

    public function getItemPlansPreliquidados($idEmpresaColab)
    {
        $extraQuery = " ";
        if ($idEmpresaColab != 0 && $idEmpresaColab != null) {
            $extraQuery .= "AND tb.idEmpresaColab = " . $idEmpresaColab;
        }
        $sql = "                SELECT DISTINCT po.idEmpresaColab,
                                       ea.idEstacion,
                                       po.itemPlan,
                                       po.idEstadoPlan,
                                       po.indicador,
                                       p.idProyecto,
                                       p.proyectoDesc,
                                       sp.idSubProyecto,
                                       sp.subProyectoDesc,
                                       z.zonalDesc,
                                       ec.empresaColabDesc,
                                       e.estacionDesc,
                                       ( SELECT COUNT(1)
                                          FROM itemplan_estacion_licencia_det ie
                                         WHERE ie.itemplan = po.itemplan
                                           AND ie.idEstacion = e.idEstacion
                                           AND ie.flg_validado = 3) AS cant_liqui,
                                       ( SELECT COUNT(1)
                                           FROM itemplan_estacion_licencia_det ie
                                          WHERE ie.itemplan = po.itemplan
                                           AND ie.idEstacion = e.idEstacion
                                           AND ie.flg_validado = 2) AS cant_preliqui
                                  FROM proyecto p, subproyecto sp, zonal z, central c,empresacolab ec, estadoplan ep, subproyectoestacion se, estacionarea ea,
                                       estacion e, planobra po,  itemplan_estacion_licencia_det ie
                                 WHERE po.idSubProyecto = sp.idSubProyecto
                                   AND sp.idProyecto = p.idProyecto
                                   AND po.idZonal = z.idZonal
                                   AND po.idCentral = c.idCentral
                                   AND c.idEmpresaColab = ec.idEmpresaColab
                                   AND po.idEstadoPlan = ep.idEstadoPlan
                                   AND po.idSubProyecto = se.idSubProyecto
                                   AND se.idEstacionArea = ea.idEstacionArea
                                   AND ea.idEstacion = e.idEstacion
                                   AND ea.idEstacion in (2,5)
                                   AND ep.idEstadoPlan NOT IN (1,2,5,6,8)
                                   AND po.idSubProyecto != 97
                                   AND po.itemplan = ie.itemPlan
                                   AND e.idEstacion = ie.idEstacion
                                   AND ie.flg_validado = 2" . $extraQuery;
        $result = $this->db->query($sql);
        return $result->result();
    }

    public function getItemPLanEstacionLincenciaDet($itemPlan, $idEstacion)
    {
        $sql = " SELECT ie.iditemplan_estacion_licencia_det,
                        e.desc_entidad,
                        ie.ruta_pdf,
                        DATE(ie.fecha_inicio) AS fecha_inicio,
                        DATE(ie.fecha_fin)  AS fecha_fin,
                        ie.flg_validado,
                        ie.flg_acotacion_valida,
                        ie.nro_cheque,
                        ie.correo_usuario_valida,
                        ie.idDistrito,
                        (CASE WHEN (  SELECT c.jefatura
                                        FROM central AS c,
                                             planobra AS p
                                       WHERE c.idCentral = p.idCentral
                                         AND p.itemPlan = '" . $itemPlan . "') = 'LIMA' THEN 1 ELSE 0 END) AS flg_provincia,
                        (CASE WHEN ie.idEntidad = 2 THEN 1 ELSE 2 END) AS flg_combo,
                        ie.codigo_expediente,
                        ie.flg_tipo
                   FROM itemplan_estacion_licencia_det as ie,
                        entidad as e
                  WHERE ie.idEntidad = e.idEntidad
                    AND ie.idEstacion = '" . $idEstacion . "'
                    AND ie.itemPlan = '" . $itemPlan . "' ";

        $result = $this->db->query($sql);
        return $result->result();
    }

    public function getEntPreliquiLicDet($itemPlan, $idEstacion)
    {
        $sql = "       SELECT ie.iditemplan_estacion_licencia_det,
                              e.desc_entidad,
                              ie.ruta_pdf_finalizacion,
                              ie.flg_validado,
                              d.distritoDesc,
                              DATE(r.fecha_modificacion) as fecha_preliqui
                         FROM itemplan_estacion_licencia_det ie,
                              entidad e,
                              distrito d,
                              reembolso r
                        WHERE ie.idEntidad = e.idEntidad
                          AND ie.idEstacion = '" . $idEstacion . "'
                          AND ie.itemPlan = '" . $itemPlan . "'
                          AND ie.flg_validado IN (2,3)
                          AND ie.idDistrito = d.idDistrito
                          AND ie.iditemplan_estacion_licencia_det = r.iditemplan_estacion_licencia_det ";

        $result = $this->db->query($sql);
        //log_message('error', $this->db->last_query());
        return $result->result();
    }

    public function getComprobantesxItemPlanDet($idItemPlanDetalle)
    {
        $sql = "   SELECT r.idReembolso,
                          r.desc_reembolso,
                          DATE(r.fecha_emision) AS fecha_emision,
                          r.monto,
                          r.fecha_registro,
                          r.ruta_foto,
                          r.estado_valida,
                          r.flg_valida_evidencia,
                          r.flg_preliqui_admin,
                          r.iditemplan_estacion_licencia_det
                     FROM reembolso AS r,
                          itemplan_estacion_licencia_det as ie
                    WHERE ie.iditemplan_estacion_licencia_det = r.iditemplan_estacion_licencia_det
                      AND r.iditemplan_estacion_licencia_det = '" . $idItemPlanDetalle . "'";
        $result = $this->db->query($sql);
        return $result->result();
    }

    public function getAcotacionesxItemPlanDet($idItemPlanDetalle)
    {
        $sql = "   SELECT a.idAcotacion,
                          a.desc_acotacion,
                          DATE(a.fecha_acotacion) AS fecha_acotacion,
                          a.monto,
                          a.fecha_registro,
                          a.ruta_foto,
                          a.estado_valida,
                          a.iditemplan_estacion_licencia_det
                     FROM acotacion AS a,
                          itemplan_estacion_licencia_det as ie
                    WHERE ie.iditemplan_estacion_licencia_det = a.iditemplan_estacion_licencia_det
                      AND a.iditemplan_estacion_licencia_det = '" . $idItemPlanDetalle . "'";
        $result = $this->db->query($sql);
        return $result->result();
    }

    public function updateRutaImagenItemPlanEstaLicencia($idItemPlanEstaDetalle, $arrayData)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        try {
            $this->db->trans_begin();
            $this->db->where('iditemplan_estacion_licencia_det', $idItemPlanEstaDetalle);
            $this->db->update('itemplan_estacion_licencia_det', $arrayData);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al actualizar el itemplan_estacion_licencia_det.');
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

    public function updateComprobanteLicencia($idIComprobanteDetalle, $arrayData)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        try {
            $this->db->trans_begin();
            $this->db->where('idReembolso', $idIComprobanteDetalle);
            $this->db->update('reembolso', $arrayData);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al actualizar el comprobante.');
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

    public function updateAcotacionLicencia($idAcotacion, $arrayData)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        try {
            $this->db->trans_begin();
            $this->db->where('idAcotacion', $idAcotacion);
            $this->db->update('acotacion', $arrayData);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al actualizar la acotaci&oacuten.');
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

    public function getEntidades($itemPlan, $idEstacion)
    {

        $sql = "  SELECT e.*,
                         (  SELECT 1
                              FROM itemplan_estacion_licencia_det
                             WHERE itemplan = '" . $itemPlan . "'
                               AND idEstacion = '" . $idEstacion . "'
                               AND idEntidad = e.idEntidad LIMIT 1) AS marcado,
                        (   SELECT 1
		                      FROM itemplan_estacion_licencia_det
                             WHERE itemplan = '" . $itemPlan . "'
                               AND idEstacion = '" . $idEstacion . "'
                               AND idEntidad = e.idEntidad
                               AND fecha_inicio IS NOT NULL
                               AND fecha_fin IS NOT NULL
                               AND ruta_pdf IS NOT NULL
                               AND flg_validado = 1 LIMIT 1) AS disabled
                    FROM entidad e;";

        $result = $this->db->query($sql);
        return $result->result();
    }

    public function getEntidadesForRegi($itemPlan, $idEstacion, $cantEntMTC)
    {
        if ($cantEntMTC == 0 || $cantEntMTC == 1) {
            $sql = "  SELECT e.*,
                             (  SELECT 1
                                  FROM itemplan_estacion_licencia_det
                                 WHERE itemplan = '" . $itemPlan . "'
                                   AND idEstacion = '" . $idEstacion . "'
                                   AND idEntidad = e.idEntidad) AS marcado,
                             (   SELECT 1
                                   FROM itemplan_estacion_licencia_det
                                  WHERE itemplan = '" . $itemPlan . "'
                                    AND idEstacion = '" . $idEstacion . "'
                                    AND idEntidad = e.idEntidad) AS disabled
                        FROM entidad e;";
        } else if ($cantEntMTC > 1) {
            $sql = "  SELECT e.*,
                             (  SELECT 1
                                  FROM itemplan_estacion_licencia_det
                                 WHERE itemplan = '" . $itemPlan . "'
                                   AND idEstacion = '" . $idEstacion . "'
                                   AND idEntidad = e.idEntidad
                                   AND idEntidad != 1) AS marcado,
                             (  SELECT 1
                                  FROM itemplan_estacion_licencia_det
                                 WHERE itemplan = '" . $itemPlan . "'
                                   AND idEstacion = '" . $idEstacion . "'
                                   AND idEntidad = e.idEntidad
                                   AND idEntidad != 1) AS disabled
                        FROM entidad e;";
        }

        $result = $this->db->query($sql);
        return $result->result();
    }

    public function getAllEntidades(){
        $sql = "  SELECT e.idEntidad,
                         e.desc_entidad
                    FROM entidad e";
        $result = $this->db->query($sql);
        return $result->result();
    }

    public function getCantItemPlanEstacionLincencia($itemPlan, $idEstacion, $arrayStringIdEnt)
    {
        $sql = "SELECT COUNT(1) AS cantidad
                  FROM itemplan_estacion_licencia_det
                 WHERE itemplan = '" . $itemPlan . "'
                   AND idEstacion = '" . $idEstacion . "'
                   AND idEntidad = IN ('" . $arrayStringIdEnt . "') ";

        $result = $this->db->query($sql);
        return $result->row()->cantidad;
    }

    public function deleteItemPlanEstaDetalleLic($itemPlan, $idEstacion, $cadIdsEnt, $arrayInsert)
    {

        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->where('itemPlan', $itemPlan);
            $this->db->where('idEstacion', $idEstacion);
            $this->db->where("idEntidad " . $cadIdsEnt);
            $this->db->delete('itemplan_estacion_licencia_det');

            if ($this->db->trans_status() === true) {

                $this->db->insert_batch('itemplan_estacion_licencia_det', $arrayInsert);

                if ($this->db->trans_status() === false) {
                    $this->db->trans_rollback();
                    throw new Exception('Hubo un error al insertar el itemplan_estacion_licencia_det.');
                } else {
                    $data['error'] = EXIT_SUCCESS;
                    $data['msj'] = 'Se inserto correctamente!';
                    $this->db->trans_commit();
                }
            } else {
                log_message('error', 'error en deleteItemPlanEstaDet: ', true);
                $this->db->trans_rollback();
                throw new Exception('ERROR TRANSACCION deleteItemPlanEstaDet');
            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        return $data;
    }

    public function registrarEntidadesItemPlanEstaLic($arrayInsert)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert_batch('itemplan_estacion_licencia_det', $arrayInsert);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al insertar el itemplan_estacion_licencia_det.');
            } else {
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se insert&oacute; correctamente!';
                $this->db->trans_commit();
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        return $data;
    }

    public function editarPlanObra($itemplan, $arrayUpdate)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->where('itemPlan', $itemplan);
            $this->db->update('planobra', $arrayUpdate);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al actualizar en editarPlanObraLite');
            } else {
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se actualizo correctamente!';
                $this->db->trans_commit();
            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        return $data;

    }

    public function getCountComprobanteLicencia($idIComprobanteDetalle)
    {
        $sql = "  SELECT COUNT(1) AS cantidad
                    FROM reembolso
                   WHERE idReembolso = '" . $idIComprobanteDetalle . "'";
        $result = $this->db->query($sql);
        return $result->row()->cantidad;
    }

    public function getCountAcotacionLicencia($idAcotacion)
    {
        $sql = "  SELECT COUNT(1) AS cantidad
                    FROM acotacion
                   WHERE idAcotacion = '" . $idAcotacion . "'";
        $result = $this->db->query($sql);
        return $result->row()->cantidad;
    }

    public function getCountEntValida($itemPlan, $idEstacion)
    {
        $sql = " SELECT COUNT(1) cant_ent
                  FROM itemplan_estacion_licencia_det
                 WHERE itemplan = '" . $itemPlan . "'
                   AND idEstacion = '" . $idEstacion . "'
                   AND idEntidad = 1";
        $result = $this->db->query($sql);
        return $result->row()->cant_ent;
    }

    public function getFlgValidaComprobante($idReembolso)
    {
        $sql = "  SELECT flg_valida_evidencia
                    FROM reembolso
                   WHERE idReembolso = '" . $idReembolso . "'";
        $result = $this->db->query($sql);
        return $result->row()->flg_valida_evidencia;
    }

    public function insertarComprobanteLicencia($arrayInsert)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert('reembolso', $arrayInsert);
            $idReembolso = $this->db->insert_id();
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar el reembolso');
            } else {
                $this->db->trans_commit();
                $data['idReembolso'] = $idReembolso;
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se insert&oacute; correctamente!';
            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    public function insertarAcotacionLicencia($arrayInsert)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert('acotacion', $arrayInsert);
            $idAcotacion = $this->db->insert_id();
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar la acotacion');
            } else {
                $this->db->trans_commit();
                $data['idAcotacion'] = $idAcotacion;
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se insert&oacute; correctamente!';
            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    public function getRutaEvidencia($idDetalle, $flgConsulta, $flgPDF)
    {
        if ($flgConsulta == 1) {
            if ($flgPDF == 1) {
                $sql = "  SELECT ie.ruta_pdf_finalizacion
                    FROM itemplan_estacion_licencia_det AS ie
                   WHERE iditemplan_estacion_licencia_det = '" . $idDetalle . "'";
            } else {
                $sql = "  SELECT ie.ruta_pdf
                FROM itemplan_estacion_licencia_det AS ie
               WHERE iditemplan_estacion_licencia_det = '" . $idDetalle . "'";
            }
        } else if ($flgConsulta == 2) {
            $sql = "  SELECT r.ruta_foto
                    FROM reembolso AS r
                   WHERE idReembolso = '" . $idDetalle . "'";
        } else {
            $sql = "  SELECT a.ruta_foto
                    FROM acotacion AS a
                   WHERE idAcotacion = '" . $idDetalle . "'";
        }
        $result = $this->db->query($sql);
        return ($flgConsulta == 1 ? ($flgPDF == 1 ? $result->row()->ruta_pdf_finalizacion : $result->row()->ruta_pdf) : $result->row()->ruta_foto);
    }

    public function getJefaturaByItemPlan($itemPlan)
    {
        $sql = "  SELECT c.jefatura
                    FROM central AS c,
                         planobra AS p
                   WHERE c.idCentral = p.idCentral
                     AND p.itemPlan = '" . $itemPlan . "'";
        $result = $this->db->query($sql);
        return $result->row()->jefatura;
    }
	
	public function getJefaturaByItemPlanPqt($itemPlan)
    {
        $sql = "  SELECT c.jefatura
                    FROM pqt_central AS c,
                         planobra AS p
                   WHERE c.idCentral = p.idCentralPqt
                     AND p.itemPlan = '" . $itemPlan . "'";
        $result = $this->db->query($sql);
        return $result->row()->jefatura;
    }
	

    public function deleteComprobante($idReembolso)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->where('idReembolso', $idReembolso);
            $this->db->delete('reembolso');

            if ($this->db->trans_status() === true) {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se elimin&oacute; correctamente este comprobante.';
            } else {
                $this->db->trans_rollback();
                throw new Exception('ERROR TRANSACCION deletePTR');
            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        return $data;
    }

    public function getEntidadesEviLic($itemPlan)
    {
        $sql = " SELECT ie.iditemplan_estacion_licencia_det,
                        ie.itemplan,
                        e.desc_entidad,
                        ie.ruta_pdf,
                        DATE(ie.fecha_inicio) AS fecha_inicio,
                        DATE(ie.fecha_fin)  AS fecha_fin
                   FROM itemplan_estacion_licencia_det as ie,
                        entidad as e
                  WHERE ie.idEntidad = e.idEntidad
                    AND itemPlan = '" . $itemPlan . "' ";

        $result = $this->db->query($sql);
        return $result->result();
    }

    public function getAcotacionesByItemPlan($itemPlan)
    {
        $sql = "   SELECT a.idAcotacion,
                          a.desc_acotacion,
                          DATE(a.fecha_acotacion) AS fecha_acotacion,
                          a.ruta_foto,
                          a.iditemplan_estacion_licencia_det
                     FROM acotacion a,
                          itemplan_estacion_licencia_det ie
                    WHERE ie.iditemplan_estacion_licencia_det = a.iditemplan_estacion_licencia_det
                      AND ie.itemPlan = '" . $itemPlan . "'";
        $result = $this->db->query($sql);
        return $result->result();
    }

    public function getAllDistritos()
    {
        $sql = " SELECT d.idDistrito,d.distritoDesc from distrito d ORDER BY d.distritoDesc;";
        $result = $this->db->query($sql);
        return $result->result();
    }
    
     /**cambio realizado el 23.07.2019 czavalacas**/
    public function insertEntidadesFromEjecucionDiseno($arrayInsert)    {
    
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
    
                $this->db->insert_batch('itemplan_estacion_licencia_det', $arrayInsert);    
                if ($this->db->trans_status() === false) {
                    $this->db->trans_rollback();
                    throw new Exception('Hubo un error al insertar el itemplan_estacion_licencia_det.');
                } else {
                    $data['error'] = EXIT_SUCCESS;
                    $data['msj'] = 'Se inserto correctamente!';
                    $this->db->trans_commit();
                }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
    
        return $data;
    }

}
