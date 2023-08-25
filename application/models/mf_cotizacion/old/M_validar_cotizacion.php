<?php

class M_validar_cotizacion extends CI_Model {

    //http://www.codeigniter.com/userguide3/database/results.html
    function __construct() {
        parent::__construct();
    }

    public function createOC($itemfaultData, $idOpex, $idUsuario) {
        $sql = "SELECT createOCToItemplanOpexBanCoti('$itemfaultData','$idOpex','$idUsuario') as result";
        $result = $this->db->query($sql);
        $resultado = $result->row()->result;
        return $resultado;
    }

    function aprobarCotizacionOpex($dataCotizacion, $dataPlanobra, $itemfaultData, $idOpex, $idUsuario) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $this->db->trans_begin();

            $this->db->where('itemplan', $dataCotizacion['itemplan']);
            $this->db->update('planobra_cotizacion', $dataCotizacion);
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Hubo un error al actualizar en planobra_cotizacion.');
            } else {
                $this->db->where('itemplan', $dataPlanobra['itemplan']);
                $this->db->update('planobra', $dataPlanobra);
                if ($this->db->trans_status() === FALSE) {
                    throw new Exception('Hubo un error al actualizar en planobra.');
                } else {
                    $sql = "SELECT createOCToItemplanOpexBanCoti('$itemfaultData','$idOpex','$idUsuario') as result";
                    $this->db->query($sql);
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

    public function countOpex($idSubproyecto) {
        $sql = "SELECT
	sub.* 
FROM
	subproyectoopexitemplan sub,
	cuenta_opex_pex opex 
WHERE
	sub.idOpex = opex.idOpex 
	AND opex.idEstadoOpex = 1 
	AND sub.idSubproyecto ='$idSubproyecto'";
        $result = $this->db->query($sql);
        return $result->num_rows();
    }

    public function getOpex($idSubproyecto, $monto) {
        $sql = "SELECT
	sub.*,
	opex.monto_temporal
FROM
	subproyectoopexitemplan sub,
	cuenta_opex_pex opex
	WHERE sub.idOpex = opex.idOpex
        AND opex.idEstadoOpex = 1 
	AND
	sub.idSubproyecto='$idSubproyecto' AND
	opex.monto_temporal > '$monto'
	LIMIT 1;";
        $result = $this->db->query($sql);
        return $result->result();
    }

    public function getMOplanObra($itemplan) {
        $sql = "SELECT monto FROM planobra_cotizacion WHERE itemplan='$itemplan'";
        $result = $this->db->query($sql);
        $monto = $result->row()->monto;
        return $monto;
    }

    function getItemplanPreRegistro() {
        $Query = ' SELECT 	po.itemplan,	p.proyectoDesc,	sp.subProyectoDesc,	e.empresaColabDesc,	c.jefatura, 
                            c.region,	ep.estadoPlanDesc, pco.estado, DATE_FORMAT(pco.fecha_envio_cotizacion, "%d/%m/%Y") as fecha_creacion,
                            pco.ruta_pdf,
                            FORMAT(pco.monto,2) as monto_mo, 
                            FORMAT(pco.monto_mat,2) as monto_mat,
                            FORMAT((pco.monto+pco.monto_mat),2) as monto_total, 
                            pco.usuario_envio_cotizacion
                    FROM	planobra po, 
                    		planobra_cotizacion pco, 
                    		subproyecto sp, 
                            proyecto p, 
                            central c, 
                            empresacolab e, 
                            estadoplan ep
                    WHERE 	po.itemplan 		= pco.itemplan
                    AND 	po.idSubProyecto 	= sp.idSubProyecto
                    AND 	sp.idProyecto 		= p.idProyecto
                    AND 	po.idCentral 		= c.idCentral
                    AND 	c.idEmpresaColab 	= e.idEmpresaColab
                    AND 	po.idEstadoPlan 	= ep.idEstadoPlan
                    AND     pco.estado          = 3
					and		po.paquetizado_fg is null
                    AND 	po.idEstadoPlan 	= ' . ESTADO_PLAN_PRE_REGISTRO.'
					UNION ALL
					SELECT 	po.itemplan,	p.proyectoDesc,	sp.subProyectoDesc,	e.empresaColabDesc,	c.jefatura, 
                            c.region,	ep.estadoPlanDesc, pco.estado, DATE_FORMAT(pco.fecha_envio_cotizacion, "%d/%m/%Y") as fecha_creacion,
                            pco.ruta_pdf,
                            FORMAT(pco.monto,2) as monto_mo, 
                            FORMAT(pco.monto_mat,2) as monto_mat,
                            FORMAT((pco.monto+pco.monto_mat),2) as monto_total, 
                            pco.usuario_envio_cotizacion
                    FROM	planobra po, 
                    		planobra_cotizacion pco, 
                    		subproyecto sp, 
                            proyecto p, 
                            pqt_central c, 
                            empresacolab e, 
                            estadoplan ep
                    WHERE 	po.itemplan 		= pco.itemplan
                    AND 	po.idSubProyecto 	= sp.idSubProyecto
                    AND 	sp.idProyecto 		= p.idProyecto
                    AND 	po.idCentralPqt		= c.idCentral
                    AND 	po.idEmpresaColab 	= e.idEmpresaColab
                    AND 	po.idEstadoPlan 	= ep.idEstadoPlan
                    AND     pco.estado          = 3
					AND		po.paquetizado_fg in (1,2)
                    AND 	po.idEstadoPlan 	= ' . ESTADO_PLAN_PRE_REGISTRO;

        $result = $this->db->query($Query, array());
        return $result;
    }

    function rechazarCotizacion($itemplan) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $this->db->trans_begin();
            $dataimg = array(
                "usua_aprueba_cotizacion" => $this->session->userdata('userSession'),
                "fecha_aprueba_cotizacion" => date("Y-m-d H:i:s"),
                "estado" => 6//cotizacion rechazada
            );
            $this->db->where('itemplan', $itemplan);
            $this->db->update('planobra_cotizacion', $dataimg);
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Hubo un error al actualizar el estadoplan.');
            } else {
                $dataPlan = array(
                    "idEstadoPlan" => ID_ESTADO_CANCELADO
                );
                $this->db->where('itemplan', $itemplan);
                $this->db->update('planobra', $dataPlan);
                if ($this->db->trans_status() === FALSE) {
                    throw new Exception('Hubo un error al actualizar el estadoplan.');
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

    function getInfoItemplan($itemplan) {
        $Query = "SELECT
	po.idSubProyecto,
	c.idCentral,
	c.idEmpresaColab,
	c.idEmpresacolab,
	po.costo_unitario_mo_crea_oc,
	po.costo_unitario_mo,
	sub.flg_opex
FROM
	planobra po
	LEFT JOIN subproyecto sub on sub.idSubProyecto = po.idSubProyecto
	LEFT JOIN central c on c.idCentral = po.idCentral
WHERE
    po.itemplan = ?";
        $result = $this->db->query($Query, array($itemplan));
        if ($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }

    function getInfoCotizacion($itemplan) {
        $Query = "SELECT   *
	               FROM    planobra_cotizacion 
	               WHERE   itemplan     = ?";
        $result = $this->db->query($Query, array($itemplan));
        if ($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }

    function aprobarCotizacionPreDiseno($itemplan, $monto_mo) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $this->db->trans_begin();
            $dataimg = array(
                "usua_aprueba_cotizacion" => $this->session->userdata('userSession'),
                "fecha_aprueba_cotizacion" => date("Y-m-d H:i:s"),
                "estado" => 4//cotizacion aprobada
            );
            $this->db->where('itemplan', $itemplan);
            $this->db->update('planobra_cotizacion', $dataimg);
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Hubo un error al actualizar el estadoplan.');
            } else {
                $dataPlan = array(
                    "idEstadoPlan" => ESTADO_PLAN_PRE_DISENO,
                    "costo_unitario_mo" => $monto_mo
                );
                $this->db->where('itemplan', $itemplan);
                $this->db->update('planobra', $dataPlan);
                if ($this->db->trans_status() === FALSE) {
                    throw new Exception('Hubo un error al actualizar el estadoplan.');
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

    function aprobarCotizacionSolo($dataCotizacion, $dataPlanobra, $solicitud_oc_creacion, $item_x_sol, $dataSapDetalle) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $this->db->trans_begin();

            $this->db->where('itemplan', $dataCotizacion['itemplan']);
            $this->db->update('planobra_cotizacion', $dataCotizacion);
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Hubo un error al actualizar en planobra_cotizacion.');
            } else {
                $this->db->where('itemplan', $dataPlanobra['itemplan']);
                $this->db->update('planobra', $dataPlanobra);
                if ($this->db->trans_status() === FALSE) {
                    throw new Exception('Hubo un error al actualizar en planobra.');
                } else {
                    $this->db->insert('solicitud_orden_compra', $solicitud_oc_creacion);
                    if ($this->db->affected_rows() != 1) {
                        $this->db->trans_rollback();
                        throw new Exception('Error al insertar en solicitud_orden_compra');
                    } else {
                        $this->db->insert('itemplan_x_solicitud_oc', $item_x_sol);
                        if ($this->db->affected_rows() != 1) {
                            $this->db->trans_rollback();
                            throw new Exception('Error al insertar en itemplan_x_solicitud_oc');
                        } else {
                            $this->db->where('pep1', $dataSapDetalle['pep1']);
                            $this->db->update('sap_detalle', $dataSapDetalle);
                            if ($this->db->trans_status() === FALSE) {
                                throw new Exception('Hubo un error al actualizar en sap_detalle.');
                            } else {
                                $data['error'] = EXIT_SUCCESS;
                                $data['msj'] = 'Se actualizo correctamente!';
                                $this->db->trans_commit();
                            }
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    function devolverCotizacion($itemplan) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $this->db->trans_begin();
            $dataimg = array(
                "usua_aprueba_cotizacion" => $this->session->userdata('userSession'),
                "fecha_aprueba_cotizacion" => date("Y-m-d H:i:s"),
                "estado" => 5//cotizacion rechazada
            );
            $this->db->where('itemplan', $itemplan);
            $this->db->update('planobra_cotizacion', $dataimg);
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Hubo un error al actualizar el estadoplan.');
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

    function getPEPSITemplanPep2GrafoByItemplan($itemplan) {
        $Query = "SELECT   DISTINCT ip2.itemplan, sd.pep1, sd.monto_temporal
            	FROM
            	itemplan_pep2_grafo ip2 LEFT JOIN sap_detalle sd ON substring(ip2.pep2,1,20) = sd.pep1
            	WHERE  ip2.itemplan = ?
        	    AND    (ip2.area LIKE '%DISENO%' OR ip2.area LIKE '%MO%')";
        $result = $this->db->query($Query, array($itemplan));
        return $result->result();
    }

    function getPEPSBolsaPepByItemplan($itemplan) {
        $Query = "SELECT DISTINCT bp.pep1, sd.monto_temporal 
                FROM planobra po, subproyecto sp, bolsa_pep bp LEFT JOIN sap_detalle sd ON bp.pep1 = sd.pep1
                WHERE po.idSubProyecto = sp.idSubProyecto
                AND sp.idSubProyecto = bp.idSubProyecto
                AND bp.tipo_pep IN (2,3) 
                AND	bp.estado = 1
                AND po.itemplan = ?";
        $result = $this->db->query($Query, array($itemplan));
        return $result->result();
    }

}
