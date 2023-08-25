<?php
class M_bolsa_presupuesto extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function deleteLogTempRetiro()
    {
        $sql = " DELETE FROM log_temp_retiro where 1;";
        $result = $this->db->query($sql);
        return $result;
    }

    public function generarCodigoRetiro()
    {
        try {

            $sql = "SELECT CONCAT('R',SUBSTRING(year(NOW()),3,2),'-') AS nro_retiro;";
            $result = $this->db->query($sql, array());

            return $result->row()->nro_retiro;

        } catch (Exception $e) {
            return $e;
        }
    }

    public function deleteLogTempBolsa()
    {
        $sql = " DELETE FROM log_temp_bolsa where 1;";
        $result = $this->db->query($sql);
        return $result;
    }

    public function generarCodigoCuenta()
    {
        try {

            $sql = "SELECT CONCAT('CTA-',SUBSTRING(year(NOW()),3,2)) AS nro_cuenta;";
            $result = $this->db->query($sql, array());

            return $result->row()->nro_cuenta;

        } catch (Exception $e) {
            return $e;
        }
    }

    public function deleteLogTempTransaccionBolsa()
    {
        $sql = " DELETE FROM log_temp_transacc_bolsa where 1;";
        $result = $this->db->query($sql);
        return $result;
    }

    public function generarCodigoTransaccionBolsa()
    {
        try {

            $sql = "SELECT CONCAT('TRA-',SUBSTRING(year(NOW()),3,2)) AS nro_transaccion;";
            $result = $this->db->query($sql, array());

            return $result->row()->nro_transaccion;

        } catch (Exception $e) {
            return $e;
        }
    }

    public function insertarBolsaPresupuesto($arrayInsert)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert('bolsa_presupuesto', $arrayInsert);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar la bolsa de presupuesto!!');
            } else {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se insert&oacute; correctamente la bolsa!!';
            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    public function insertarSolicitudRetiro($arrayInsert)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert('retiro', $arrayInsert);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar la solicitud de  retiro!!');
            } else {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se insert&oacute; correctamente la solicitud de retiro!!';
            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    public function insertarLogBolsa($arrayInsert)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert('log_bolsa', $arrayInsert);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar tabla log bolsa de presupuesto!!');
            } else {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se insert&oacute; correctamente!!';
            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    public function obtenerUltimoRegistroRetiro()
    {
        $sql = " SELECT * from log_temp_retiro order by nro_retiro desc limit 1;";
        $result = $this->db->query($sql, array());
        return $result->row()->nro_retiro;
    }

    public function obtenerUltimoRegistroBolsaPresu()
    {
        $sql = " SELECT * from log_temp_bolsa order by nro_cuenta desc limit 1;";
        $result = $this->db->query($sql, array());
        return $result->row()->nro_cuenta;
    }

    public function updateBolsaPresupuesto($idBolsa, $arrayData)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        try {
            $this->db->trans_begin();
            $this->db->where('idBolsa', $idBolsa);
            $this->db->update('bolsa_presupuesto', $arrayData);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al actualizar el monto.');
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

    public function getAllBolsaPresupuesto($descCuenta, $idUsuarioResponsable, $flgCombo)
    {
        $sql = "   SELECT bp.idBolsa,
                          bp.nro_cuenta,
                          bp.desc_cuenta,
                          bp.id_usuario_responsable,
                          FORMAT(bp.monto_inicial,2) AS monto_inicial,
                          FORMAT(bp.monto_stock,2) AS monto_stock,
                          bp.monto_inicial AS montoIni_calculo,
                          bp.monto_stock AS montoStock_calculo,
                          bp.id_usuario_regi,
                          DATE(fecha_registro) as fecha_registro,
                          u.nombre as desc_responsable
                     FROM bolsa_presupuesto bp,
                          usuario u
                    WHERE bp.id_usuario_responsable = u.id_usuario ";

        if($flgCombo != '' && $flgCombo != null ){
            $sql .= "AND bp.monto_stock > 0 ";
        }
        if ($descCuenta != '' && $descCuenta != null) {
            $sql .= "AND bp.desc_cuenta = '" . $descCuenta . "' ";
        }
        if ($idUsuarioResponsable != '' && $idUsuarioResponsable != null) {
            $sql .= "AND bp.id_usuario_responsable = '" . $idUsuarioResponsable . "' ";
        }

        $result = $this->db->query($sql);
        return $result->result();
    }

    public function getAllSolicitudesRetiro($itemPlan, $nroRetiro, $estadoSoli, $fechaRegistro, $idUsuarioResponsable)
    {
        $sql = "   SELECT r.idBolsa,
                          b.desc_cuenta,
                          r.itemplan,
                          r.nro_retiro,
                          r.motivo,
                          u.nombre AS usu_solicitante,
                          FORMAT(r.monto_solicitado,2) AS monto_solicitado,
                          r.monto_solicitado AS montoSoli_calcu,
                          r.flg_solicitud,
                          DATE(r.fecha_registro) AS fecha_registro,
                          (CASE WHEN r.flg_solicitud = 0 THEN 'SOLICITUD PENDIENTE'
                                WHEN r.flg_solicitud = 1 THEN 'SOLICITUD APROBADA'
                                WHEN r.flg_solicitud = 2 THEN 'SOLICITUD LIQUIDADA'
                                WHEN r.flg_solicitud = 4 THEN 'SOLICITUD VALIDADA'
                                ELSE 'SOLICITUD RECHAZADA' END) AS estado_solicitud,
                          r.monto_aprob,
                          DATE(r.fecha_aprobacion) as fecha_aprobacion
                     FROM retiro r,
                          bolsa_presupuesto b,
                          usuario u
                    WHERE r.idBolsa = b.idBolsa
                      AND r.id_usuario_solic = u.id_usuario
                      AND r.flg_solicitud    = COALESCE(?, r.flg_solicitud) ";
        if ($itemPlan != '' && $itemPlan != null) {
            $sql .= "AND r.itemplan = '" . $itemPlan . "' ";
        }
        if ($nroRetiro != '' && $nroRetiro != null) {
            $sql .= "AND r.nro_retiro = '" . $nroRetiro . "' ";
        }
        if ($fechaRegistro != '' && $fechaRegistro != null) {
            $sql .= "AND DATE(r.fecha_registro) = '" . $fechaRegistro . "' ";
        }
        if ($idUsuarioResponsable != '' && $idUsuarioResponsable != null) {
            $sql .= "AND b.id_usuario_responsable = '" . $idUsuarioResponsable . "' ";
        }
        $sql .= " ORDER BY r.fecha_registro ASC";

        $result = $this->db->query($sql, array($estadoSoli));
        return $result->result();
    }

    public function getCountBolsaPresupuesto($descCuenta)
    {
        $sql = "  SELECT COUNT(1) AS cantidad
                    FROM bolsa_presupuesto
                   WHERE desc_cuenta = '" . $descCuenta . "'";
        $result = $this->db->query($sql);
        return $result->row()->cantidad;
    }

    public function getItemPlanById($itemplan)
    {
        $sql = " SELECT po.itemPlan,po.nombreProyecto,eecc.empresaColabDesc,sp.subProyectoDesc,po.indicador
                   FROM planobra po,
                        central c,
                        empresacolab eecc,
                        subproyecto sp
                  WHERE po.idCentral = c.idCentral
                    AND CASE WHEN sp.idTipoSubProyecto = 2 AND c.flg_subproByNodoCV = 97
                             THEN c.idEmpresaColabCV = eecc.idEmpresaColab
						     ELSE c.idEmpresaColab = eecc.idEmpresaColab END
                    AND po.idSubProyecto = sp.idSubProyecto
                    AND po.idEstadoPlan = " . ID_ESTADO_PLAN_EN_OBRA . "
                    AND po.itemplan = '" . $itemplan . "'";
        $result = $this->db->query($sql);
        return $result->result();
    }

    public function updateSolicitudRetiro($nroRetiro, $arrayData)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        try {
            $this->db->trans_begin();
            $this->db->where('nro_retiro', $nroRetiro);
            $this->db->update('retiro', $arrayData);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al actualizar el monto.');
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

    public function getMontoStockByIdBolsa($idBolsa)
    {
        $sql = "  SELECT monto_stock
                    FROM bolsa_presupuesto
                   WHERE idBolsa = '" . $idBolsa . "'";
        $result = $this->db->query($sql);
        return $result->row()->monto_stock;
    }

    public function getCountRetiro($idBolsa, $itemplan)
    {
        $sql = "  SELECT COUNT(*) AS cant_retiro
                    FROM retiro
                   WHERE idBolsa = '" . $idBolsa . "'
                     AND itemplan = '" . $itemplan . "'
                     AND flg_solicitud = 0";
        $result = $this->db->query($sql);
        return $result->row()->cant_retiro;
    }

    public function getAllResponsables()
    {
        $sql = " SELECT u.id_usuario,
                        u.nombre
                   FROM usuario u
                  WHERE u.id_usuario IN (16,17,18)";
        $result = $this->db->query($sql);
        return $result->result();
    }

    public function getAllSoliRet($itemPlan, $nroRetiro, $estadoSoli, $fechaAprob, $idUsuarioSolic)
    {
        $sql = "   SELECT r.idBolsa,
                          b.desc_cuenta,
                          r.itemplan,
                          r.nro_retiro,
                          r.motivo,
                          u.nombre AS usu_solicitante,
                          FORMAT(r.monto_solicitado,2) AS monto_solicitado,
                          r.flg_solicitud,
                          (CASE WHEN r.flg_solicitud = 0 THEN 'SOLICITUD PENDIENTE'
                                WHEN r.flg_solicitud = 1 THEN 'SOLICITUD APROBADA'
                                ELSE 'SOLICITUD RECHAZADA' END) AS estado_solicitud,
                          FORMAT(r.monto_aprob,2) AS monto_aprob,
                          r.monto_aprob AS montoAprob_calculo,
                          DATE(r.fecha_aprobacion) as fecha_aprobacion
                     FROM retiro r,
                          bolsa_presupuesto b,
                          usuario u
                    WHERE r.idBolsa = b.idBolsa
                      AND r.id_usuario_solic = u.id_usuario
                      AND r.flg_solicitud = 1 ";

        if ($itemPlan != '' && $itemPlan != null) {
            $sql .= " AND r.itemplan = '" . $itemPlan . "' ";
        }
        if ($nroRetiro != '' && $nroRetiro != null) {
            $sql .= " AND r.nro_retiro = '" . $nroRetiro . "' ";
        }
        if ($estadoSoli != '' && $estadoSoli != null) {
            $sql .= " AND r.flg_solicitud = '" . $estadoSoli . "' ";
        }
        if ($fechaAprob != '' && $fechaAprob != null) {
            $sql .= " AND DATE(r.fecha_aprobacion) = '" . $fechaAprob . "' ";
        }
        if ($idUsuarioSolic != '' && $idUsuarioSolic != null) {
            $sql .= " AND r.id_usuario_solic = '" . $idUsuarioSolic . "' ";
        }
        $sql .= " ORDER BY DATE(r.fecha_aprobacion) ASC";

        $result = $this->db->query($sql);
        return $result->result();
    }

    public function insertarLiquiRetiro($arrayInsert)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert('liquidacion_retiro', $arrayInsert);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar la liquidacion de retiro!!');
            } else {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se insert&oacute; correctamente la liquidacion de retiro!!';
            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    public function updateLiquiRetiro($idBolsa, $itemplan, $arrayData)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        try {
            $this->db->trans_begin();
            $this->db->where('idBolsa', $idBolsa);
            $this->db->where('itemplan', $itemplan);
            $this->db->update('liquidacion_retiro', $arrayData);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al actualizar la  liquidaci&oacute;n de retiro.');
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

    public function updateRetiro($idBolsa, $itemplan, $arrayData)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        try {
            $this->db->trans_begin();
            $this->db->where('idBolsa', $idBolsa);
            $this->db->where('itemplan', $itemplan);
            $this->db->update('retiro', $arrayData);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al actualizar el retiro.');
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

    public function getAllSoliRetLiquidados($itemPlan, $nroRetiro, $estadoSoli, $fechaLiquidacion, $idUsuarioResponsable)
    {
        $sql = "   SELECT r.idBolsa,
                          b.desc_cuenta,
                          r.itemplan,
                          r.nro_retiro,
                          r.motivo,
                          u.nombre AS usu_liquida,
                          FORMAT(r.monto_solicitado,2) AS monto_solicitado,
                          r.flg_solicitud,
                          (CASE WHEN r.flg_solicitud = 0 THEN 'SOLICITUD PENDIENTE'
                                WHEN r.flg_solicitud = 1 THEN 'SOLICITUD APROBADA'
                                WHEN r.flg_solicitud = 2 THEN 'SOLICITUD LIQUIDADA'
                                WHEN r.flg_solicitud = 3 THEN 'SOLICITUD RECHAZADA'
                                ELSE 'SOLICITUD VALIDADA' END) AS estado_solicitud,
                          FORMAT(r.monto_aprob,2) AS monto_aprob,
                          r.monto_aprob AS montoAprob_calculo,
                          FORMAT(lr.monto_liquidado,2) AS monto_liquidado,
                          lr.monto_liquidado AS montoLiqui_calculo,
                          DATE(lr.fecha_registro) AS fecha_liquidacion,
                          lr.desc_comprobante,
                          lr.ruta_pdf,
                          r.id_usuario_valida,
                          r.monto_validado,
                          DATE(r.fecha_validacion) AS fecha_validacion
                     FROM retiro r,
                          bolsa_presupuesto b,
                          liquidacion_retiro lr,
                          usuario u
                    WHERE r.idBolsa = b.idBolsa
                      AND r.idBolsa = lr.idBolsa
                      AND r.itemplan = lr.itemplan
                      AND lr.id_usuario_regi = u.id_usuario
                      AND r.flg_solicitud IN (2,4) ";

        if ($itemPlan != '' && $itemPlan != null) {
            $sql .= " AND r.itemplan = '" . $itemPlan . "' ";
        }
        if ($nroRetiro != '' && $nroRetiro != null) {
            $sql .= " AND r.nro_retiro = '" . $nroRetiro . "' ";
        }
        if ($estadoSoli != '' && $estadoSoli != null) {
            $sql .= " AND r.flg_solicitud = '" . $estadoSoli . "' ";
        }
        if ($fechaLiquidacion != '' && $fechaLiquidacion != null) {
            $sql .= " AND DATE(lr.fecha_registro) = '" . $fechaLiquidacion . "' ";
        }
        if ($idUsuarioResponsable != '' && $idUsuarioResponsable != null) {
            $sql .= " AND b.id_usuario_responsable = '" . $idUsuarioResponsable . "' ";
        }
        $sql .= " ORDER BY DATE(lr.fecha_registro) ASC";

        $result = $this->db->query($sql);
        return $result->result();
    }

    public function getTrasanccionesByNroCuenta($nroCuenta)
    {
        $sql = " SELECT lb.idLog_bolsa,
                        lb.nro_transaccion,
                        u.nombre,
                        FORMAT(lb.monto_agregado,2) AS monto_agregado,
                        DATE(lb.fecha_modificacion) AS fecha_registro,
                        lb.nro_cuenta
                   FROM log_bolsa lb,
                        usuario u
                  WHERE lb.nro_cuenta = '" . $nroCuenta . "'
                    AND lb.id_usuario_regis = u.id_usuario
               ORDER BY lb.nro_transaccion";
        $result = $this->db->query($sql);
        return $result->result();
    }

}
