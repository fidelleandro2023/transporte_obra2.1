<?php
class M_licencias extends CI_Model
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

    public function getFlgValidadoByIdIPEstDet($idItemPlanEstaDetalle)
    {
        $sql = "SELECT ie.flg_validado AS flg_validado
                  FROM itemplan_estacion_licencia_det ie
                 WHERE iditemplan_estacion_licencia_det = ? ";

        $result = $this->db->query($sql,array($idItemPlanEstaDetalle));
        return $result->row()->flg_validado;
    }

    public function getBandejaItemPlanEstacion($itemplan = null,$idProyecto = null,$idSubProyecto = null,$jefatura = null,$idEmpresaColab = null,$idFase = null,$mesPrevEjec = null)
    {
        
        $sql = " SELECT tb1.*,
                            (CASE WHEN tb2.cant_ent_concluida IS NULL THEN 0 ELSE tb2.cant_ent_concluida END ) AS cant_ent_concluida,
                            (CASE WHEN tb3.total_entidades IS NULL THEN 0 ELSE tb3.total_entidades END ) AS total_entidades
                                                            
                    FROM                       ( SELECT  po.idEmpresaColab,
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
                                                            po.fechaPrevEjec AS fechaPrevEjec2, 
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
                                                            (CASE WHEN c.jefatura = 'LIMA' THEN 1 ELSE 2 END) AS flg_provincia,
                                                            f.idFase,
                                                            f.faseDesc
                                                            
                                    FROM proyecto p, subproyecto sp, zonal z, central c,empresacolab ec, estadoplan ep, subproyectoestacion se, estacionarea ea,
                                                    estacion e, planobra po, fase f, itemplan_estacion_licencia_det ie
                                            WHERE po.idSubProyecto = sp.idSubProyecto
                                                AND sp.idProyecto = p.idProyecto
                                                AND po.idZonal = z.idZonal
                                                AND po.idCentral = c.idCentral
                                                AND	(CASE WHEN sp.idTipoSubProyecto = 2 THEN c.idEmpresaColabCV = ec.idEmpresaColab ELSE c.idEmpresaColab = ec.idEmpresaColab END)
                                                AND po.idEstadoPlan = ep.idEstadoPlan
                                                AND po.idSubProyecto = se.idSubProyecto
                                                AND se.idEstacionArea = ea.idEstacionArea
                                                AND ea.idEstacion = e.idEstacion
                                                AND ea.idEstacion in (2,5)
                                                AND ep.idEstadoPlan NOT IN (1,2,8)
												AND (po.paquetizado_fg is null or po.paquetizado_fg = 1)
                                                AND po.idFase = f.idFase
                                                AND po.itemplan = ie.itemPlan
                                                AND e.idEstacion = ie.idEstacion
                                                AND po.itemplan = COALESCE(?, po.itemplan)
                                                AND p.idProyecto = COALESCE(?, p.idProyecto)
                                                AND sp.idSubProyecto = COALESCE(?, sp.idSubProyecto)
                                                AND c.jefatura = COALESCE(?, c.jefatura)
                                                AND ec.idEmpresaColab = COALESCE(?, ec.idEmpresaColab)
                                                AND po.idFase = COALESCE(?, po.idFase)
                                                AND CASE WHEN po.fechaPrevEjec IS NOT NULL THEN po.fechaPrevEjec = COALESCE(?, po.fechaPrevEjec)
                                                         ELSE true END
                                        GROUP BY po.itemPlan,e.idEstacion
                                        ORDER BY po.itemPlan ) AS tb1 LEFT JOIN ( SELECT ie.itemplan,ie.idEstacion,
                                                                                         SUM(1) AS cant_ent_concluida
                                                                                    FROM itemplan_estacion_licencia_det ie
                                                                                   WHERE ie.flg_validado = 2
                                             AND ie.idEstacion IN (2,5)
                                                                                GROUP BY itemplan,idEstacion
                                                                                ORDER BY itemplan) AS tb2 ON tb2.itemplan = tb1.itemplan AND tb2.idEstacion = tb1.idEstacion
                                                                     LEFT JOIN (  SELECT ie.itemplan,ie.idEstacion,
                                                                                         COUNT(*) AS total_entidades
                                                                                    FROM itemplan_estacion_licencia_det ie
                                                                                    WHERE ie.idEstacion IN (2,5)
                                                                                    AND ie.idEntidad != 0
                                                                                GROUP BY itemplan,idEstacion
                                                                                ORDER BY itemplan) AS tb3 ON tb3.itemplan = tb1.itemplan AND tb3.idEstacion = tb1.idEstacion ";

        $result = $this->db->query($sql,array($itemplan,$idProyecto,$idSubProyecto,$jefatura,$idEmpresaColab,$idFase,$mesPrevEjec));
        #_log($this->db->last_query());
        return $result->result();
    }

    public function getItemPlansPreliquidados($itemplan = null,$idProyecto = null,$idSubProyecto = null,$jefatura = null,$idEmpresaColab = null,$idFase = null,$mesPrevEjec = null)
    {
        $sql = "  SELECT tb1.*,
                                    (CASE WHEN tb2.cant_liqui IS NULL THEN 0 ELSE tb2.cant_liqui END ) AS cant_liqui,
                                    (CASE WHEN tb3.cant_preliqui IS NULL THEN 0 ELSE tb3.cant_preliqui END ) AS cant_preliqui
                                                                    
                            FROM                       ( SELECT  po.idEmpresaColab,
                                                                    ea.idEstacion,
                                                                    po.itemPlan,
                                                                    po.idEstadoPlan,
                                                                    po.indicador,
                                                                    sp.idSubProyecto,
                                                                    p.idProyecto,
                                                                    p.proyectoDesc,
                                                                    sp.subProyectoDesc,
                                                                    z.zonalDesc,
                                                                    ec.empresaColabDesc,
                                                                    ep.estadoPlanDesc,
                                                                    po.fechaPrevEjec AS fechaPrevEjec2, 
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
                                                                    (CASE WHEN c.jefatura = 'LIMA' THEN 1 ELSE 2 END) AS flg_provincia,
                                                                    f.idFase,
                                                                    f.faseDesc
                                                                    
                                            FROM proyecto p, subproyecto sp, zonal z, central c,empresacolab ec, estadoplan ep, subproyectoestacion se, estacionarea ea,
                                                            estacion e, planobra po, fase f, itemplan_estacion_licencia_det ie
                                                    WHERE po.idSubProyecto = sp.idSubProyecto
                                                        AND sp.idProyecto = p.idProyecto
                                                        AND po.idZonal = z.idZonal
                                                        AND po.idCentral = c.idCentral
                                                        AND	(CASE WHEN sp.idTipoSubProyecto = 2 THEN c.idEmpresaColabCV = ec.idEmpresaColab ELSE c.idEmpresaColab = ec.idEmpresaColab END)
                                                        AND po.idEstadoPlan = ep.idEstadoPlan
                                                        AND po.idSubProyecto = se.idSubProyecto
                                                        AND se.idEstacionArea = ea.idEstacionArea
                                                        AND ea.idEstacion = e.idEstacion
                                                        AND ea.idEstacion in (2,5)
                                                        AND ep.idEstadoPlan NOT IN (1,2)
                                                        AND po.idFase = f.idFase
                                                        AND po.itemplan = ie.itemPlan
                                                        AND e.idEstacion = ie.idEstacion
                                                        AND ie.flg_validado IN (2,3)
                                                        AND po.itemplan = COALESCE(?, po.itemplan)
                                                        AND p.idProyecto = COALESCE(?, p.idProyecto)
                                                        AND sp.idSubProyecto = COALESCE(?, sp.idSubProyecto)
                                                        AND c.jefatura = COALESCE(?, c.jefatura)
                                                        AND ec.idEmpresaColab = COALESCE(?, ec.idEmpresaColab)
                                                        AND po.idFase = COALESCE(?, po.idFase)
														AND (po.paquetizado_fg IS NULL OR po.paquetizado_fg = 1)
                                                        AND CASE WHEN po.fechaPrevEjec IS NOT NULL THEN SUBSTR(po.fechaPrevEjec,6,2) = COALESCE(?, SUBSTR(po.fechaPrevEjec,6,2)) 
                                                                 ELSE true END
                                                GROUP BY po.itemPlan,e.idEstacion
                                                UNION ALL
												SELECT  po.idEmpresaColab,
                                                                    ea.idEstacion,
                                                                    po.itemPlan,
                                                                    po.idEstadoPlan,
                                                                    po.indicador,
                                                                    sp.idSubProyecto,
                                                                    p.idProyecto,
                                                                    p.proyectoDesc,
                                                                    sp.subProyectoDesc,
                                                                    z.zonalDesc,
                                                                    ec.empresaColabDesc,
                                                                    ep.estadoPlanDesc,
                                                                    po.fechaPrevEjec AS fechaPrevEjec2, 
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
                                                                    (CASE WHEN c.jefatura = 'LIMA' THEN 1 ELSE 2 END) AS flg_provincia,
                                                                    f.idFase,
                                                                    f.faseDesc
                                                                    
                                            FROM proyecto p, subproyecto sp, zonal z, pqt_central c,empresacolab ec, estadoplan ep, subproyectoestacion se, estacionarea ea,
                                                            estacion e, planobra po, fase f, itemplan_estacion_licencia_det ie
                                                    WHERE po.idSubProyecto = sp.idSubProyecto
                                                        AND sp.idProyecto = p.idProyecto
                                                        AND po.idZonal = z.idZonal
                                                        AND po.idCentralPqt = c.idCentral
                                                        AND	(CASE WHEN sp.idTipoSubProyecto = 2 THEN c.idEmpresaColabCV = ec.idEmpresaColab ELSE c.idEmpresaColab = ec.idEmpresaColab END)
                                                        AND po.idEstadoPlan = ep.idEstadoPlan
                                                        AND po.idSubProyecto = se.idSubProyecto
                                                        AND se.idEstacionArea = ea.idEstacionArea
                                                        AND ea.idEstacion = e.idEstacion
                                                        AND ea.idEstacion in (2,5)
                                                        AND ep.idEstadoPlan NOT IN (1,2)
                                                        AND po.idFase = f.idFase
                                                        AND po.itemplan = ie.itemPlan
                                                        AND e.idEstacion = ie.idEstacion
                                                        AND ie.flg_validado IN (2,3)
                                                        AND po.itemplan = COALESCE('".$itemplan."', po.itemplan)
                                                        AND p.idProyecto = COALESCE(?, p.idProyecto)
                                                        AND sp.idSubProyecto = COALESCE(?, sp.idSubProyecto)
                                                        AND c.jefatura = COALESCE(?, c.jefatura)
                                                        AND ec.idEmpresaColab = COALESCE(?, ec.idEmpresaColab)
                                                        AND po.idFase = COALESCE(?, po.idFase)
                                                        AND po.paquetizado_fg = 2
                                                GROUP BY po.itemPlan,e.idEstacion) AS tb1 LEFT JOIN (  SELECT ie.itemplan,ie.idEstacion,
                                                                                                COUNT(*) AS cant_liqui
                                                                                            FROM itemplan_estacion_licencia_det ie
                                                                                        WHERE ie.flg_validado = 3
                                                                                        GROUP BY itemplan,idEstacion
                                                                                        ORDER BY itemplan) AS tb2 ON tb2.itemplan = tb1.itemplan AND tb2.idEstacion = tb1.idEstacion
                                                                            LEFT JOIN (  SELECT ie.itemplan,ie.idEstacion,
                                                                                                COUNT(*) AS cant_preliqui
                                                                                            FROM itemplan_estacion_licencia_det ie
                                                                                        WHERE ie.flg_validado = 2
                                                                                        GROUP BY itemplan,idEstacion
                                                                                        ORDER BY itemplan) AS tb3 ON tb3.itemplan = tb1.itemplan AND tb3.idEstacion = tb1.idEstacion ";
                                                                                        
        $result = $this->db->query($sql,array($itemplan,$idProyecto,$idSubProyecto,$jefatura,$idEmpresaColab,$idFase,
		                                      $idProyecto,$idSubProyecto,$jefatura,$idEmpresaColab,$idFase,$mesPrevEjec));
        _log($this->db->last_query());
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
                        ie.flg_tipo,
                        ie.has_compro_cala
                   FROM itemplan_estacion_licencia_det as ie,
                        entidad as e
                  WHERE ie.idEntidad = e.idEntidad
                    AND ie.idEstacion = '" . $idEstacion . "'
                    AND ie.itemPlan = '" . $itemPlan . "' ";

        $result = $this->db->query($sql);
       // log_message('error', $this->db->last_query());
        return $result->result();
    }
/*
    public function getEntPreliquiLicDet($itemPlan, $idEstacion)
    {
        $sql = "       SELECT ie.iditemplan_estacion_licencia_det,
                              ie.itemPlan,
                              ie.idEstacion,
                              e.desc_entidad,
                              ie.ruta_pdf_finalizacion,
                              ie.flg_validado,
                              d.idDistrito,
                              ie.flg_tipo,
                              (CASE WHEN d.distritoDesc IS NULL THEN '-' ELSE d.distritoDesc END) AS distritoDesc,
                              DATE(r.fecha_modificacion) AS fecha_preliqui,
                              ie.cod_expe_finalizacion,
                              DATE(ie.fecha_final) AS fecha_final
                         FROM itemplan_estacion_licencia_det ie 
                    LEFT JOIN distrito d ON ie.idDistrito = d.idDistrito
                    LEFT JOIN reembolso r ON r.iditemplan_estacion_licencia_det = ie.iditemplan_estacion_licencia_det,
                              entidad e
                        WHERE ie.idEntidad = e.idEntidad
                          AND ie.idEstacion = '" . $idEstacion . "'
                          AND ie.itemPlan = '" . $itemPlan . "'
                          AND ie.flg_validado IN (3)
                          AND ie.idEntidad IN (2,6) ";

        $result = $this->db->query($sql);
        log_message('error', $this->db->last_query());
        return $result->result();
    }
*/
    
    public function getEntPreliquiLicDet($itemPlan, $idEstacion)
    {
        $sql = "       SELECT ie.iditemplan_estacion_licencia_det,
                              ie.itemPlan,
                              ie.idEstacion,
                              ie.idEntidad, 
                              e.desc_entidad,
                              ie.ruta_pdf_finalizacion,
                              ie.flg_validado,
                              d.idDistrito,
                              ie.flg_tipo,
                              (CASE WHEN d.distritoDesc IS NULL THEN '-' ELSE d.distritoDesc END) AS distritoDesc,
                              DATE(r.fecha_modificacion) AS fecha_preliqui,
                              ie.cod_expe_finalizacion,
                              DATE(ie.fecha_final) AS fecha_final
                         FROM itemplan_estacion_licencia_det ie
                    LEFT JOIN distrito d ON ie.idDistrito = d.idDistrito
                    LEFT JOIN reembolso r ON r.iditemplan_estacion_licencia_det = ie.iditemplan_estacion_licencia_det,
                              entidad e
                        WHERE ie.idEntidad = e.idEntidad
                          AND ie.idEstacion = '" . $idEstacion . "'
                          AND ie.itemPlan = '" . $itemPlan . "'
                          AND ie.flg_validado IN (2,3)
                          AND ie.flg_tipo = 2
                          #AND ie.idEntidad IN (2,6) ";
    
        $result = $this->db->query($sql);
        log_message('error', $this->db->last_query());
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

    public function updateItemplanEstaDetLic($idItemPlanEstaDetalle, $arrayData)
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
                               AND idEntidad = e.idEntidad) AS marcado,
                        (   SELECT 1
		                      FROM itemplan_estacion_licencia_det
                             WHERE itemplan = '" . $itemPlan . "'
                               AND idEstacion = '" . $idEstacion . "'
                               AND idEntidad = e.idEntidad
                               AND fecha_inicio IS NOT NULL
                               AND fecha_fin IS NOT NULL
                               AND ruta_pdf IS NOT NULL
                               AND flg_validado = 1) AS disabled
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
    
    /**CAMBIO 25.06.2019****/
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
        //log_message('error', $this->db->last_query());
        if($result->row()!=null){
            return ($flgConsulta == 1 ? ($flgPDF == 1 ? $result->row()->ruta_pdf_finalizacion : $result->row()->ruta_pdf) : $result->row()->ruta_foto);
            
        }else{
            return null;
        }
           
        //return ($flgConsulta == 1 ? ($flgPDF == 1 ? $result->row()->ruta_pdf_finalizacion : $result->row()->ruta_pdf) : $result->row()->ruta_foto);
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

    public function deleteIPEstaDetalleLic($idItemPlanEstaDetalle)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->where('iditemplan_estacion_licencia_det', $idItemPlanEstaDetalle);
            $this->db->delete('itemplan_estacion_licencia_det');

            if ($this->db->trans_status() === true) {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se elimin&oacute; correctamente!!';
            } else {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al eliminar el IP Entidad!!');
            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        return $data;
    }

    public function getCountComprobanteByIdIPEstDet($idItemPlanEstaDetalle)
    {
        $sql = "  SELECT *, COUNT(1) AS cantidad
                    FROM reembolso
                   WHERE iditemplan_estacion_licencia_det = ?";
        $result = $this->db->query($sql, $idItemPlanEstaDetalle);
        return $result->row_array();
    }
    
     public function getIPEstaLicDetByCodExp($itemplan = null,$cod_expediente = null)
    {
        
        $sql = " SELECT ie.iditemplan_estacion_licencia_det,
                        ie.itemPlan,
                        ie.idEstacion,
                        est.estacionDesc,
                        e.desc_entidad,
                        ie.ruta_pdf,
                        DATE(ie.fecha_inicio) AS fecha_inicio,
                        DATE(ie.fecha_fin)  AS fecha_fin,
                        ie.flg_validado,
                        ie.flg_acotacion_valida,
                        ie.nro_cheque,
                        ie.correo_usuario_valida,
                        ie.idDistrito,
                        (CASE WHEN ie.idDistrito IS NULL THEN '-' ELSE UPPER(d.distritoDesc) END) AS distritoDesc,
                        (CASE WHEN c.jefatura = 'LIMA' THEN 1 ELSE 2 END) AS flg_provincia,
                        (CASE WHEN ie.idEntidad = 2 THEN 1 ELSE 2 END) AS flg_combo,
                        ie.codigo_expediente,
                        ie.flg_tipo,
                        (CASE WHEN ie.flg_tipo = 1 THEN 'COMUNICATIVA'
                            WHEN ie.flg_tipo = 2 THEN 'LICENCIA' ELSE '-' END) AS tipo_lic
                   FROM entidad as e,
                        planobra po,
                        central c,
                        estacion est,
                        itemplan_estacion_licencia_det as ie LEFT JOIN distrito d ON d.idDistrito = ie.idDistrito
                  WHERE ie.idEntidad = e.idEntidad
                    AND ie.itemplan = po.itemplan
                    AND po.idCentral = c.idCentral
                    AND ie.idEstacion = est.idEstacion
                    AND ie.idEstacion IN (2,5)
                    AND ie.itemplan = COALESCE(?, ie.itemplan)
                    AND ie.codigo_expediente = COALESCE(?, ie.codigo_expediente) ";

        $result = $this->db->query($sql,array($itemplan,$cod_expediente));
        //log_message('error', $this->db->last_query());
        return $result->result();
    }
    
     public function getCountPOByIP($itemplan)
    {
        $sql = "  SELECT COUNT(1) AS cantidad,
                         estado_po,
                         codigo_po,
                         idEstacion
                    FROM planobra_po po
                   WHERE itemplan = '" . $itemplan . "'
                     AND idEstacion = 20";
        $result = $this->db->query($sql);
        return $result->row_array();
    }

    public function getCountIPEstLicDet($itemPlan, $idEstacion)
    {
        $sql = " SELECT COUNT(1) cant_ent
                  FROM itemplan_estacion_licencia_det
                 WHERE itemplan = '" . $itemPlan . "'
                   AND idEstacion = " . $idEstacion . " ";
        $result = $this->db->query($sql);
        return $result->row()->cant_ent;
    }
    
    public function getEntByIPEst($itemPlan, $idEstacion)
    {
        $sql = "  SELECT ie.iditemplan_estacion_licencia_det,
                         ie.itemPlan,
                         ie.idEstacion,
                         ie.idEntidad, 
                         e.desc_entidad,
                         ie.idDistrito, 
                         ie.flg_tipo,
                         (CASE WHEN d.distritoDesc IS NULL THEN '-' ELSE d.distritoDesc END) AS distritoDesc,
                         (CASE WHEN r.fecha_modificacion IS NULL THEN '-' ELSE DATE(r.fecha_modificacion) END) AS fecha_preliqui
                    FROM entidad e,
                         itemplan_estacion_licencia_det ie 
               LEFT JOIN distrito d ON d.idDistrito = ie.idDistrito
               LEFT JOIN reembolso r ON r.iditemplan_estacion_licencia_det = ie.iditemplan_estacion_licencia_det
                   WHERE e.idEntidad = ie.idEntidad
                     AND ie.itemplan = '" . $itemPlan . "'
                     AND ie.idEstacion = " . $idEstacion . " 
               #GROUP BY ie.idEntidad ";
        $result = $this->db->query($sql);
        log_message('error', $this->db->last_query());
        return $result->result();
    }
	
	public function getBandejaItemPlanEstacionPqt($itemplan = null)
    {
    
        $sql = " SELECT tb1.*,
                            (CASE WHEN tb2.cant_ent_concluida IS NULL THEN 0 ELSE tb2.cant_ent_concluida END ) AS cant_ent_concluida,
                            (CASE WHEN tb3.total_entidades IS NULL THEN 0 ELSE tb3.total_entidades END ) AS total_entidades
    
                    FROM                       ( SELECT  po.idEmpresaColab,
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
                                                            po.fechaPrevEjec AS fechaPrevEjec2,
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
                                                            (CASE WHEN c.jefatura = 'LIMA' THEN 1 ELSE 2 END) AS flg_provincia,
                                                            f.idFase,
                                                            f.faseDesc
    
                                    FROM proyecto p, subproyecto sp, zonal z, pqt_central c,empresacolab ec, estadoplan ep, subproyectoestacion se, estacionarea ea,
                                                    estacion e, planobra po, fase f, pre_diseno pd
                                            WHERE po.idSubProyecto = sp.idSubProyecto
                                                AND sp.idProyecto = p.idProyecto
                                                AND po.idZonal = z.idZonal
                                                AND po.idCentralPqt = c.idCentral
                                                AND po.idEmpresaColab = ec.idEmpresaColab
                                                AND po.idEstadoPlan = ep.idEstadoPlan
                                                AND po.idSubProyecto = se.idSubProyecto
                                                AND se.idEstacionArea = ea.idEstacionArea
                                                AND ea.idEstacion = e.idEstacion
                                                AND ea.idEstacion = pd.idEstacion
                                                AND po.itemplan = pd.itemplan
                                                AND (pd.requiere_licencia = 1 or pd.requiere_licencia is null)
                                                #AND pd.estado = 3
												AND (CASE WHEN sp.idTipoSubProyecto != 2 then pd.estado = 3 else true end)
                                                AND ea.idEstacion in (2,5)
                                                /*AND ep.idEstadoPlan = ".ID_ESTADO_EN_LICENCIA."*/
                                                AND po.idFase = f.idFase
                                                /*AND po.itemplan = ie.itemPlan
                                                AND e.idEstacion = ie.idEstacion*/
                                                AND po.itemplan = ?
                                        GROUP BY po.itemPlan,e.idEstacion
                                        ORDER BY po.itemPlan ) AS tb1 LEFT JOIN ( SELECT ie.itemplan,ie.idEstacion,
                                                                                         SUM(1) AS cant_ent_concluida
                                                                                    FROM itemplan_estacion_licencia_det ie
                                                                                   WHERE ie.flg_validado = 2
                                             AND ie.idEstacion IN (2,5)
                                                                                GROUP BY itemplan,idEstacion
                                                                                ORDER BY itemplan) AS tb2 ON tb2.itemplan = tb1.itemplan AND tb2.idEstacion = tb1.idEstacion
                                                                     LEFT JOIN (  SELECT ie.itemplan,ie.idEstacion,
                                                                                         COUNT(*) AS total_entidades
                                                                                    FROM itemplan_estacion_licencia_det ie
                                                                                    WHERE ie.idEstacion IN (2,5)
                                                                                    AND ie.idEntidad != 0
                                                                                GROUP BY itemplan,idEstacion
                                                                                ORDER BY itemplan) AS tb3 ON tb3.itemplan = tb1.itemplan AND tb3.idEstacion = tb1.idEstacion ";
    

        //log_message('error', '-->$$sql : '.$sql);
        $result = $this->db->query($sql,array($itemplan));
        log_message('error', '-->$$sql : '.$this->db->last_query());
        return $result->result();
    }
	
	public function getControlDeLicenciasPorEstacionAnclaPqt($itemplan){
        $sql = "SELECT tb.*, pd.liquido_licencia FROM (
                SELECT po.itemPlan, ea.idEstacion, po.idEstadoPlan,
                (SELECT COUNT(*) FROM itemplan_estacion_licencia_det ie WHERE ie.itemPlan = po.itemPlan AND ie.idEstacion = ea.idEstacion AND ie.idEntidad <> 0) total_licencias,
                (SELECT COUNT(*) FROM itemplan_estacion_licencia_det ie WHERE ie.itemPlan = po.itemPlan AND ie.idEstacion = ea.idEstacion AND flg_validado IN (2,3) AND COALESCE(ruta_pdf,'')<>'') licencias_liquidadas,
                (SELECT COUNT(*) FROM itemplan_estacion_licencia_det ie WHERE ie.itemPlan = po.itemPlan AND ie.idEstacion = ea.idEstacion AND flg_validado IN (2,3) AND ie.idEntidad IN (2,6) and flg_tipo = 2) licencias_liquidadas_MD_MP
                FROM planobra po
                INNER JOIN subproyecto sp
                ON po.idSubProyecto = sp.idSubProyecto
                INNER JOIN subproyectoestacion se
                ON sp.idSubProyecto = se.idSubProyecto
                INNER JOIN estacionarea ea
                ON se.idEstacionArea = ea.idEstacionArea
                WHERE po.itemPlan = ? 
                AND ea.idEstacion IN (".ID_ESTACION_COAXIAL.",".ID_ESTACION_FO.")
                GROUP BY po.itemPlan, ea.idEstacion, po.idEstadoPlan
                ) as tb LEFT JOIN pre_diseno pd ON tb.itemplan  = pd.itemplan AND tb.idEstacion = pd.idEstacion";
        
        $result = $this->db->query($sql,array($itemplan));
        
        return $result->result();
    }
    
    public function updEstadoItemplanEnLicenciaFinalizado($itemplan, $arrayData)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
    
        try {
            $this->db->trans_begin();
            $this->db->where('itemplan', $itemplan);
            $this->db->update('planobra', $arrayData);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al actualizar planobra, Finalizacion de En Licencia.');
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
    
    public function obtItemplanByIdEstLicDet($id){
        $sql = "SELECT itemPlan FROM itemplan_estacion_licencia_det WHERE iditemplan_estacion_licencia_det = ?";
        $result = $this->db->query($sql,array($id));
        
        return $result->result();
    }

    public function updatePreDisenoLicenciaLiquidad($itemplan, $idEstacion, $arrayData)
    {
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
}
