<?php

/**
 * Description of M_exceso
 *
 * @author ivan.more
 */
class M_exceso extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    function getBandejaExceso($situacion, $area, $itemplan) {
        $Query = "SELECT so.id_solicitud, so.itemfault, sp.elementoDesc,
                        (CASE   WHEN so.tipo_po = 2 THEN 'MATERIAL'
				                WHEN so.tipo_po = 1 THEN 'MO' END) as tipo_po, 
                        FORMAT(so.costo_inicial,2)      as costo_inicial, 
                        FORMAT(so.exceso_solicitado,2)  as exceso_solicitado, 
                        FORMAT(so.costo_final,2)        as costo_final,
                        so.costo_final                  as costo_final_nf,
                		UPPER(CONCAT(u_sol.nombres,' ', u_sol.ape_paterno,' ', u_sol.ape_materno)) AS usua_solicita, 
                        so.fecha_solicita,
                        UPPER(CONCAT(u_val.nombres,' ', u_val.ape_paterno,' ', u_val.ape_materno)) AS usua_valida, 
                        so.fecha_valida,
                        (CASE   WHEN so.estado_valida IS NULL THEN 'PENDIENTE'
                                WHEN so.estado_valida = 1 THEN 'APROBADO'
                                WHEN so.estado_valida = 2 THEN 'RECHAZADO' END) as situacion
                FROM itemfault po, servicio_elemento  sp, itemfault_solicitud_exceso so 
                LEFT JOIN  usuario u_sol on so.usuario_solicita =  u_sol.id_usuario
                LEFT JOIN  usuario u_val on so.usuario_valida 	=  u_val.id_usuario
                WHERE 
                po.idServicioElemento  =  sp.idServicioElemento
                and  po.itemfault  =  so.itemfault ";
        if ($situacion != null) {//SITUACION
            if ($situacion == 0) {//PENDIENTE
                $Query .= " AND  so.estado_valida is null";
            } else if ($situacion == 1) {//APROBADA
                $Query .= " AND  so.estado_valida = 1";
            } else if ($situacion == 2) {//VALIDADA
                $Query .= " AND  so.estado_valida = 2";
            }
        }
        if ($area != null) {
            if ($area == 1) {//MATERIAL
                $Query .= " AND  so.tipo_po =1";
            } else if ($area == 2) {//MO
                $Query .= " AND  so.tipo_po = 2";
            }
        }
        if ($itemplan != null) {
            $Query .= " AND so.itemfault = '" . $itemplan . "'";
        }
        $result = $this->db->query($Query, array());
        log_message('error', $this->db->last_query());
        return $result->result();
    }

    function CreateExceso($dataInsert) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert('itemfault_solicitud_exceso', $dataInsert);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar la Solicitud de Exceso');
            } else {
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se registro correctamente la Solicitud de Exceso';
                $this->db->trans_commit();
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    function getInfoObraByIdSolicitud($idSolicitud) {
        $Query = 'SELECT se.itemfault,se.tipo_po, po.montoMAT, po.montoMO
                    FROM
                        itemfault po,
                        itemfault_solicitud_exceso se
                    WHERE
                        po.itemfault = se.itemfault
                    AND se.id_solicitud = ?
					LIMIT 1';
        $result = $this->db->query($Query, array($idSolicitud));
        if ($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }

    function aprobSolicitud($dataItemplan, $itemplan, $dataUpdateSolicitud, $idSolicitud) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->where('id_solicitud', $idSolicitud);
            $this->db->update('itemfault_solicitud_exceso', $dataUpdateSolicitud);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al actualizar la informacion.');
            } else {
                $this->db->where('itemfault', $itemplan);
                $this->db->update('itemfault', $dataItemplan);
                if ($this->db->affected_rows() != 1) {
                    $this->db->trans_rollback();
                    throw new Exception('Error al actualizar la informacion.');
                } else {
                    $data['error'] = EXIT_SUCCESS;
                    $data['msj'] = 'Se actualizo correctamente!';
                    $this->db->trans_commit();
                }
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    function rejectSolicitud($dataUpdateSolicitud, $idSolicitud) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->where('id_solicitud', $idSolicitud);
            $this->db->update('itemfault_solicitud_exceso', $dataUpdateSolicitud);
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

}
