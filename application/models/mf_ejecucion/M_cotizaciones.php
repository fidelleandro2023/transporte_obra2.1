<?php
class M_cotizaciones extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllCotizaciones($flgValidado, $idResponsable){

        $sql = "              SELECT tb.idCotizacion,
                                     tb.itemPlan,
                                     tb.desc_cotizacion,
                                     tb.costo,
                                     tb.ruta_pdf,
                                     tb.usu_regi,
                                     tb1.usu_responsable,
                                     tb.fecha_registro,
                                     tb.flg_estado,
                                     tb.fecha_aprob
                                FROM (    SELECT c.idCotizacion,
                                                 c.itemPlan,
                                                 c.desc_cotizacion,
                                                 c.costo,
                                                 c.ruta_pdf,
                                                 u.nombre AS usu_regi,
                                                 DATE(c.fecha_registro) AS fecha_registro,
                                                 (CASE WHEN c.flg_validado = 0 THEN 'PENDIENTE'
                                                       WHEN c.flg_validado = 1 THEN 'APROBADA'
                                                       ELSE 'RECHAZADA' END) AS flg_estado,
                                                 c.id_usuario_aprob,
                                                 DATE(c.fecha_aprob) AS fecha_aprob
                                            FROM cotizacion c,
                                                 usuario u
                                           WHERE c.id_usuario_reg = u.id_usuario 
                                             AND c.flg_validado    = COALESCE(?, c.flg_validado)
                                             AND c.id_responsable  = COALESCE(?, c.id_responsable) ) as tb,
             
                                      ( SELECT c.idCotizacion,
                                               c.itemPlan,
                                               u.nombre AS usu_responsable
                                          FROM cotizacion c,
                                               usuario u
                                         WHERE c.id_responsable = u.id_usuario 
                                           AND c.flg_validado    = COALESCE(?, c.flg_validado)
                                           AND c.id_responsable  = COALESCE(?, c.id_responsable) ) as tb1
                                WHERE tb.idCotizacion = tb1.idCotizacion
                                  AND tb.itemPlan = tb1.itemPlan";

        $result = $this->db->query($sql,array($flgValidado, $idResponsable,$flgValidado, $idResponsable));
        return $result->result();
    }

    public function getItemPlanById($itemplan)
    {
        $sql = " SELECT po.itemPlan,po.nombreProyecto,eecc.empresaColabDesc,sp.subProyectoDesc,po.indicador
                   FROM planobra po,
                        central c,
                        empresacolab eecc,
                        subproyecto sp
                  WHERE po.idCentral = c.idCentral
                    AND CASE WHEN s.idTipoSubProyecto = 2 && c.flg_subproByNodoCV = 97
                             THEN c.idEmpresaColabCV = eecc.idEmpresaColab
						     ELSE c.idEmpresaColab = eecc.idEmpresaColab END
                    AND po.idSubProyecto = sp.idSubProyecto
                    AND po.idEstadoPlan = " . ID_ESTADO_PLAN_EN_OBRA . "
                    AND po.itemplan = '" . $itemplan . "'";
        $result = $this->db->query($sql);
        return $result->result();
    }

    public function deleteLogTempCotizacion()
    {
        $sql = " DELETE FROM log_temp_cotizacion where 1;";
        $result = $this->db->query($sql);
        return $result;
    }

    public function generarCodigoCotizacion()
    {
        try {

            $sql = "SELECT CONCAT('COT-',SUBSTRING(year(NOW()),3,2)) AS idCotizacion;";
            $result = $this->db->query($sql, array());

            return $result->row()->idCotizacion;

        } catch (Exception $e) {
            return $e;
        }
    }

    public function insertarCotizacion($arrayInsert)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert('cotizacion', $arrayInsert);
            $idCotizacion = $this->db->insert_id();
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar la cotizaci&oacute;n!!');
            } else {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se insert&oacute; correctamente la cotizaci&oacute;n!!';
            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    public function obtenerUltimoRegistroCotizacion()
    {
        $sql = " SELECT * from log_temp_cotizacion order by idCotizacion desc limit 1;";
        $result = $this->db->query($sql, array());
        return $result->row()->idCotizacion;
    }

    public function updateCotizacion($idCotizacion, $arrayData)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        try {
            $this->db->trans_begin();
            $this->db->where('idCotizacion', $idCotizacion);
            $this->db->update('cotizacion', $arrayData);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al actualizar la cotizaci&oacute;n.');
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

    public function getCountCotiByItemplan($itemplan)
    {
        $sql = "  SELECT COUNT(*) AS cantidad
                    FROM cotizacion
                   WHERE itemplan = '" . $itemplan . "'";

        $result = $this->db->query($sql);
        return $result->row()->cantidad;
    }

}
