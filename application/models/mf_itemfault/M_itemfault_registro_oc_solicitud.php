<?php

class M_itemfault_registro_oc_solicitud extends CI_Model {

    //http://www.codeigniter.com/userguide3/database/results.html
    function __construct() {
        parent::__construct();
    }

    function getObrasBysolicitud($solicitud) {
        $Query = 'SELECT * FROM itemfault where solicitud_oc = ?';
        $result = $this->db->query($Query, array($solicitud));
        #log_message('error', $this->db->last_query());
        return $result->result();
    }

    function createOCAndAsiento($dataPo, $dataSolicitud, $solicitud) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();

            $this->db->update_batch('itemfault', $dataPo, 'itemfault');
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                throw new Exception('Error al update el planobra_po_detalle_mo.');
            } else {

                $this->db->where('codigo_solicitud', $solicitud);
                $this->db->update('itemfault_solicitud_orden_compra', $dataSolicitud);
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    throw new Exception('Error al modificar el itemfault');
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

    /*     * antiguo* */

    function getInfoCodigoMaterial($itemplan, $idEstacion, $codigo) {
        $Query = "SELECT 	p.idActividad, p.codigo, p.descripcion, pd.descPrecio, p.baremo, pr.costo
                	FROM 	planobra po,
                        	subproyecto sp,
                        	proyecto_estacion_partida_mo pep,
                        	partidas p,
                        	preciario pr,
                        	central c,
                        	precio_diseno pd
                	WHERE 	po.idCentral = c.idCentral
                	AND		p.idPrecioDiseno    = pd.idPrecioDiseno
                	AND		po.idSubProyecto 	= sp.idSubProyecto
                	AND 	sp.idProyecto 		= pep.idProyecto
                	AND 	pep.idPartida 		= p.idActividad
                	AND		p.idPrecioDiseno 	= pr.idPrecioDiseno
                	AND 	c.idEmpresaColab    = pr.idEmpresaColab
                	AND     c.idZonal			= pr.idzonal
                	AND 	pr.idEstacion       = ?
            	    AND 	p.estado 			= 1
            	    AND 	p.flg_tipo 			= 2
            	    AND 	po.itemplan 		= ?
        	        AND 	pep.idEstacion 		= ?
    	            AND     p.codigo            = ?
	               AND     (po.paquetizado_fg is null or po.paquetizado_fg =1)
	               UNION ALL
	              SELECT 	p.idActividad, p.codigo, p.descripcion, pd.descPrecio, p.baremo, pr.costo
                	FROM 	planobra po,
                        	subproyecto sp,
                        	proyecto_estacion_partida_mo pep,
                        	partidas p,
                        	preciario pr,
                        	precio_diseno pd
                	WHERE 	p.idPrecioDiseno    = pd.idPrecioDiseno
                	AND		po.idSubProyecto 	= sp.idSubProyecto
                	AND 	sp.idProyecto 		= pep.idProyecto
                	AND 	pep.idPartida 		= p.idActividad
                	AND		p.idPrecioDiseno 	= pr.idPrecioDiseno
                	AND 	po.idEmpresaColab    = pr.idEmpresaColab
                	AND     po.idZonal			= pr.idzonal
                	AND 	pr.idEstacion       = ?
            	    AND 	p.estado 			= 1
            	    AND 	p.flg_tipo 			= 2
            	    AND 	po.itemplan 		= ?
        	        AND 	pep.idEstacion 		= ?
    	            AND     p.codigo            = ?
	                AND     po.paquetizado_fg  =   2";
        $result = $this->db->query($Query, array($idEstacion, $itemplan, $idEstacion, $codigo, $idEstacion, $itemplan, $idEstacion, $codigo));
        //log_message('error', $this->db->last_query());
        if ($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }

    function getSubProyectoEstacionByItemplanEstacion($itemplan, $idEstacion) {
        $Query = "SELECT 	se.idSubProyectoEstacion 
                    FROM 	planobra po, subproyectoestacion se, estacionarea ea, area a
                    WHERE 	po.idSubProyecto   = se.idSubProyecto
                    AND		se.idEstacionArea  = ea.idEstacionArea
                    AND 	ea.idArea      = a.idArea
                    AND 	a.tipoArea     = 'MO'
                    AND 	ea.idEstacion  = ?
                    AND 	po.itemplan    = ? LIMIT 1";
        $result = $this->db->query($Query, array($idEstacion, $itemplan));
        if ($result->row() != null) {
            return $result->row_array()['idSubProyectoEstacion'];
        } else {
            return null;
        }
    }

    /*     * ************************************************************ */

    function createPoMO($dataPO, $dataLogPO, $dataDetalleplan, $arrayFinalInsert) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();

            $this->db->insert('planobra_po', $dataPO);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar en Planobra Po');
            } else {
                $this->db->insert('log_planobra_po', $dataLogPO);
                if ($this->db->affected_rows() != 1) {
                    $this->db->trans_rollback();
                    throw new Exception('Error al insertar en log_planobra_po');
                } else {
                    $this->db->insert('detalleplan', $dataDetalleplan);
                    if ($this->db->affected_rows() != 1) {
                        $this->db->trans_rollback();
                        throw new Exception('Error al insertar en detalleplan');
                    } else {
                        $this->db->insert_batch('planobra_po_detalle_mo', $arrayFinalInsert);
                        if ($this->db->trans_status() === FALSE) {
                            $this->db->trans_rollback();
                            throw new Exception('Hubo un error al insertar el planobra_po_detalle_mo.');
                        } else {
                            $data['error'] = EXIT_SUCCESS;
                            $data['msj'] = 'Se actualizo correctamente!';
                            $this->db->trans_commit();
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

    function getEeccDisenoOperaByItemPlan($itemplan) {
        $Query = "SELECT   po.idEmpresaColabDiseno, 
	                       c.idEmpresaColab,
						   c.idEmpresaColabFuente,
						   c.jefatura
	               FROM    planobra po, 
	                       central c 
	               WHERE   po.idCentral    = c.idCentral
	               AND     po.itemplan     = ?";
        $result = $this->db->query($Query, array($itemplan));
        if ($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }

    function getEeccDisenoOperaByItemPlanPqt($itemplan) {
        $Query = "SELECT   po.idEmpresaColabDiseno, 
	                       c.idEmpresaColab,
						   c.idEmpresaColabFuente,
						   c.jefatura
	               FROM    planobra po, 
	                       pqt_central c 
	               WHERE   po.idCentralPqt = c.idCentral
	               AND     po.itemplan     = ?";
        $result = $this->db->query($Query, array($itemplan));
        if ($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }

    /*     * *************CREACION MANUAL DE PO DE DISENO COTIZACION** *///////////

    function createPoMODiseno($dataPO, $dataLogPO, $dataDetalleplan, $arrayFinalInsert) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();

            $this->db->insert('planobra_po', $dataPO);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar en Planobra Po');
            } else {
                $this->db->insert('log_planobra_po', $dataLogPO);
                if ($this->db->affected_rows() != 1) {
                    $this->db->trans_rollback();
                    throw new Exception('Error al insertar en log_planobra_po');
                } else {
                    $this->db->insert('detalleplan', $dataDetalleplan);
                    if ($this->db->affected_rows() != 1) {
                        $this->db->trans_rollback();
                        throw new Exception('Error al insertar en detalleplan');
                    } else {
                        $this->db->insert_batch('planobra_po_detalle_partida', $arrayFinalInsert);
                        if ($this->db->trans_status() === FALSE) {
                            $this->db->trans_rollback();
                            throw new Exception('Hubo un error al insertar el planobra_po_detalle_mo.');
                        } else {
                            $data['error'] = EXIT_SUCCESS;
                            $data['msj'] = 'Se actualizo correctamente!';
                            $this->db->trans_commit();
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

    function getSubProyectoEstacionByItemplanEstacionCotizacion($itemplan, $idEstacion) {
        $Query = "SELECT 	se.idSubProyectoEstacion
                    FROM 	planobra po, subproyectoestacion se, estacionarea ea, area a
                    WHERE 	po.idSubProyecto   = se.idSubProyecto
                    AND		se.idEstacionArea  = ea.idEstacionArea
                    AND 	ea.idArea      = a.idArea
                    AND 	a.tipoArea     = 'MO'
                    AND 	ea.idEstacion  = ?
                    AND 	po.itemplan    = ? 
	                AND 	a.idArea = 46 LIMIT 1";
        $result = $this->db->query($Query, array($idEstacion, $itemplan));
        if ($result->row() != null) {
            return $result->row_array()['idSubProyectoEstacion'];
        } else {
            return null;
        }
    }

    function getPartidasByProyectoEstacionPODiseno($itemplan, $idEstacion) {
        $Query = " 	SELECT 	p.idActividad, p.codigo, p.descripcion, pd.descPrecio, p.baremo, pr.costo
            	FROM 	planobra po,
            	subproyecto sp,
            	partidas p,
            	preciario pr,
            	central c,
            	precio_diseno pd
            	WHERE 	po.idCentral = c.idCentral
            	AND		p.idPrecioDiseno    = pd.idPrecioDiseno
            	AND		po.idSubProyecto 	= sp.idSubProyecto
            	AND		p.idPrecioDiseno 	= pr.idPrecioDiseno
            	AND 	c.idEmpresaColab    = pr.idEmpresaColab
            	AND     c.idZonal			= pr.idzonal
            	AND 	pr.idEstacion       = ?
            	AND 	p.estado 			= 1
            	AND 	p.flg_tipo 			= 2
            	AND 	po.itemplan 		= ?
                AND     p.codigo            = '15009-7' LIMIT 1";

        $result = $this->db->query($Query, array($idEstacion, $itemplan));
        if ($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }

    function obtenerMontoMO($solicitud) {
        $Query = " SELECT * FROM  itemfault WHERE itemfault='$solicitud';";
        $result = $this->db->query($Query, array());
        if ($result->row()->montoMO) {
            return $result->row()->montoMO;
        } else {
            return false;
        }
    }

    function updateMontoReal($montoMO) {
        $Query = "UPDATE cuentaOpex SET monto_real = (monto_real-'$montoMO');";
        $this->db->query($Query, array());
        return true;
    }
    
    

    function updateTranss($solicitud) {
        $Query = "UPDATE transaccionOpex SET estadoTransaccion = 2 WHERE codigo='$solicitud';";
        $this->db->query($Query, array());
        return true;
    }

    function selectItemfault($solicitud) {
        $Query = " SELECT * FROM  itemfault WHERE solicitud_oc='$solicitud';";
        $result = $this->db->query($Query, array());
        if ($result->row()->itemfault) {
            return $result->row()->itemfault;
        } else {
            return false;
        }
    }
    
    function updateMontoReservado($idOpex,$montoMO) {
        $Query = "UPDATE cuentaOpex SET monto_provisional = (monto_provisional - '$montoMO') WHERE idOpex='$idOpex';;";
        $this->db->query($Query, array());
        return true;
    }
    
    function updateMontoConsumido($idOpex,$montoMO) {
        $Query = "UPDATE cuentaOpex SET monto_real = (monto_real + '$montoMO') WHERE idOpex='$idOpex'; ;";
        $this->db->query($Query, array());
        return true;
    }
    
    function updateMontoDisponible($idOpex,$montoMO) {
        $Query = "UPDATE cuentaOpex SET monto_dispo = (monto_dispo - '$montoMO') WHERE idOpex='$idOpex';;";
        $this->db->query($Query, array());
        return true;
    }
    
     function selectIdOpex($solicitud) {
        $Query = " SELECT * FROM  transaccionOpex WHERE codigo='$solicitud';";
        $result = $this->db->query($Query, array());
        if ($result->row()->idOpex) {
            return $result->row()->idOpex;
        } else {
            return false;
        }
    }

//

    function getSubEvento($solicitud) {
        $Query = " SELECT * FROM  itemfault WHERE solicitud_oc='$solicitud';";
        $result = $this->db->query($Query, array());
        if ($result->row()->idSubEvento) {
            return $result->row()->idSubEvento;
        } else {
            return false;
        }
    }

    function getEstado($idSubEvento) {
        $Query = " SELECT * FROM  itemfault_en_obra WHERE idSubEvento='$idSubEvento';";
        $result = $this->db->query($Query, array());
        if ($result->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }


}
