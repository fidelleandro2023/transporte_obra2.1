<?php

class M_pqt_instrumento_ambiental extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function registrarInstrumentoAmbiental($arrData)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->insert('entidad_itemplan_estacion', $arrData);
            if ($this->db->affected_rows() <= 0) {
                $data['msj'] = 'No se registró el la tabla itemplan_estacion_licencia_det';
                $data['error'] = EXIT_ERROR;
            } else {
                $data['msj'] = 'Se registró correctamente';
                $data['error'] = EXIT_SUCCESS;
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        return $data;
    }

    function getEntidadByItemplanEstacion($itemplan, $idEstacion, $idEntidad = null, $id = null)
    {
        $sql = "SELECT ei.*,
                       e.desc_entidad,
                       t.flg_comprobante,
                       po.idEstadoPlan,
					   po.flgLicencia,
                       po.flgInstrumentoAmbiental
                  FROM (entidad_itemplan_estacion ei,
                       entidad e,
					   planobra po)
             LEFT JOIN tipo_entidad t ON (t.id_tipo_entidad = ei.idTipoEntidad)
                 WHERE 
                   ei.idEntidad = e.idEntidad
				   AND ei.itemplan = po.itemplan
                   AND ei.itemplan   = ?
                   AND ei.idEstacion = COALESCE(?, ei.idEstacion)
                   AND ei.idEntidad = COALESCE(?, ei.idEntidad)
				   AND ei.id = COALESCE(?, ei.id);
                    ";
        $result = $this->db->query($sql, array($itemplan, $idEstacion, $idEntidad, $id));
		_log($this->db->last_query());

        return $result->result_array();
    }

    function getCountEntidadAmbientalPendiente($itemplan)
    {
        $sql = 'SELECT COUNT(*) count FROM entidad_itemplan_estacion WHERE itemplan = ? AND (idEntidadEstado != 2 OR idEntidadEstado IS NULL);';
        $result = $this->db->query($sql, [$itemplan]);
        return $result->row_array();
    }

    function registrarEntidadEvidencia($arrEntidadEvidencia)
    {
        $this->db->insert('entidad_evidencia', $arrEntidadEvidencia);

        if ($this->db->affected_rows() != 1) {
            $data['msj'] = 'Error al registrar, contacte con el encargado de TI';
            $data['error'] = EXIT_ERROR;
        } else {
            $data['msj'] = 'Se registro correctamente';
            $data['error'] = EXIT_SUCCESS;
        }

        return $data;
    }

    function getEntidadEvidenciaAll($idEntidadEstacion)
    {
        $sql = "SELECT t1.*, t2.descripcionTipoEvidencia
                  FROM entidad_evidencia t1,
                       entidad_tipo_evidencia t2
                 WHERE t1.idEntidadTipoEvidencia = t2.idEntidadTipoEvidencia
                  AND t1.idEntidadEstacion = ?";
        $result = $this->db->query($sql, [$idEntidadEstacion]);
        return $result->result_array();
    }

    function eliminarEvidencia($id)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->where('id', $id);
            $this->db->delete('entidad_evidencia');

            if ($this->db->trans_status() === true) {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se eliminó correctamente!!';
            } else {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al eliminar!!');
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        return $data;
    }

    function getCompromisoAll($id)
    {
        $sql = "SELECT t1.idCompromiso,
                       t1.compromisoDesc,
                       t1.estado 
                  FROM compromiso t1 
                 WHERE t1.estado = 1 AND 
                       t1.idCompromiso not in ( select idCompromiso from compromiso_entidad where idEntidadEstacion = ?);";
        $result = $this->db->query($sql, [$id]);
        return $result->result_array();
    }

    function getCompromisoEntidadAll($id)
    {
        $sql = 'SELECT t1.id,
                        t1.idEntidad,
                        t1.itemplan,
                        t2.idCompromisoEntidad,
                        t2.idCompromiso,
                        t3.compromisoDesc,
                        t2.fechaInicioCompromiso,
                        t2.fechaFinCompromiso,
                        t2.idEstadoCompromiso,
                        t1.estadoFinalCompromiso,
                        IFNULL(t2.idUsuarioCompromiso, t3.idUsuarioCompromiso) AS idUsuarioCompromiso,
                        t2.rutaCompromiso
                FROM entidad_itemplan_estacion t1
                JOIN compromiso_entidad t2 ON t1.id = t2.idEntidadEstacion
                JOIN compromiso t3 ON t2.idCompromiso = t3.idCompromiso
                WHERE t1.id = ?';
        $result = $this->db->query($sql, $id);
        return $result->result_array();
    }

    function registrarCompromiso($arrayData)
    {
        $this->db->insert_batch('compromiso_entidad', $arrayData);
        if ($this->db->affected_rows() > 0) {
            $data['error'] = EXIT_SUCCESS;
            $data['msj'] = 'Se insertó correctamente!';
        } else {
            $data['error'] = EXIT_ERROR;
            $data['msj'] = 'Error al insertar en la tabla compromiso.';
        }

        return $data;
    }

    function eliminarCompromiso($idCompromisoEntidad)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->where('idCompromisoEntidad', $idCompromisoEntidad);
            $this->db->delete('compromiso_entidad');

            if ($this->db->trans_status() === true) {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se eliminó correctamente!!';
            } else {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al eliminar!!');
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        return $data;
    }

    function actualizarCompromisoEntidad($arrCompromisoEntidad)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->where('idCompromisoEntidad', $arrCompromisoEntidad['idCompromisoEntidad']);
            $this->db->update('compromiso_entidad', $arrCompromisoEntidad);

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Error al modificar el compromiso_entidad');
            } else {
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se Actualizo correctamente!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }

    function getComprobanteEntidad($id)
    {
        $sql = 'SELECT t1.id,
                       t1.idEntidadEstacion,
                       t1.nroComprobante,
                       t1.fechaEmisionComprobante,
                       t1.montoComprobante,
                       t1.fileComprobante,
                       t2.idEntidadEstado
                    FROM entidad_comprobante t1,
                         entidad_itemplan_estacion t2
                    WHERE t1.idEntidadEstacion = t2.id
                      AND t1.idEntidadEstacion = COALESCE(?, t1.idEntidadEstacion);';
        $result = $this->db->query($sql, [$id]);
        return $result->result_array();
    }

    function insertarComprobanteEntidad($arrData)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->insert('entidad_comprobante', $arrData);
            if ($this->db->affected_rows() <= 0) {
                $data['msj'] = 'No se registró el la tabla entidad_comprobante';
                $data['error'] = EXIT_ERROR;
            } else {
                $data['msj'] = 'Se registró correctamente';
                $data['error'] = EXIT_SUCCESS;
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        return $data;
    }

    public function eliminarComprobante($id)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->where('id', $id);
            $this->db->delete('entidad_comprobante');

            if ($this->db->trans_status() === true) {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se eliminó correctamente!!';
            } else {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al eliminar!!');
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        return $data;
    }

    public function eliminarEntidadAmbiental($id)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->where('idEntidadEstacion', $id);
            $this->db->delete('compromiso_entidad');

            $this->db->where('idEntidadEstacion', $id);
            $this->db->delete('entidad_evidencia');

            $this->db->where('idEntidadEstacion', $id);
            $this->db->delete('entidad_comprobante');

            if ($this->db->trans_status() === true) {

                $this->db->where('id', $id);
                $this->db->delete('entidad_itemplan_estacion');

                if ($this->db->trans_status() === true) {
                    $this->db->trans_commit();
                    $data['error'] = EXIT_SUCCESS;
                    $data['msj'] = 'Se elimino correctamente!!';
                } else {
                    $this->db->trans_rollback();
                    throw new Exception('Hubo un error al eliminar la entidad!!');
                }
            } else {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al eliminar la entidad!!');
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        return $data;
    }

    function getCountEntidadAmbientalByItemplan($itemplan)
    {
        $sql = 'SELECT COUNT(*) count FROM entidad_itemplan_estacion WHERE itemplan = ?;';
        $result = $this->db->query($sql, [$itemplan]);

        return $result->row_array();
    }
}
