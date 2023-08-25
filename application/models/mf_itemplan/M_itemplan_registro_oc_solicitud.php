<?php

class M_itemplan_registro_oc_solicitud extends CI_Model {

    //http://www.codeigniter.com/userguide3/database/results.html
    function __construct() {
        parent::__construct();
    }

    function getObrasBysolicitud($solicitud) {
        $Query = 'SELECT * FROM planobra where solicitud_oc = ?';
        $result = $this->db->query($Query, array($solicitud));
        #log_message('error', $this->db->last_query());
        return $result->result();
    }

	function createOCAndAsiento($dataPo, $dataSolicitud, $solicitud, $idTipoPlanta, $itemplan) {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
			$this->db->update_batch('planobra',$dataPo, 'itemplan');
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				throw new Exception('Error al update el planobra_po_detalle_mo.');
			}else{
				$this->db->where('codigo_solicitud', $solicitud);
				$this->db->update('itemplan_solicitud_orden_compra', $dataSolicitud);
				if($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					throw new Exception('Error al modificar el planobra_po');
				}else{
					$fechaActual = $this->m_utils->fechaActual();
					$data = array(
									"estado"              => ESTADO_02_TEXTO,
									"ultimo_estado"       => ESTADO_01_TEXTO,
									"fecha_ultimo_estado" => $fechaActual,
									"usua_ultimo_estado"  => $this->session->userdata('userSession'),
									"fecha_aprob"         => $fechaActual,
									"usua_aprob"          => $this->session->userdata('userSession'),
									"rangoPtr"            => 2,
									"flg_rechazado"       => FLG_APROBADO
								);
					$this->db->where('itemplan', $itemplan);
					$this->db->where('rangoPtr  <>', 6);
					$this->db->update('ptr_planta_interna', $data);
					
					if($this->db->affected_rows() > 0) {
						$data['error'] = EXIT_SUCCESS;
						$data['msj'] = 'Se actualizo correctamente!';
						$this->db->trans_commit();
					} else {
						$this->db->trans_rollback();
						throw new Exception('Error al modificar el po');
					}
					
				}
			}

	                       
	        	  
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
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
        $Query = " SELECT * FROM  planobra WHERE itemPlan='$solicitud';";
        $result = $this->db->query($Query, array());
        if ($result->row()->costo_unitario_mo) {
            return $result->row()->costo_unitario_mo;
        } else {
            return false;
        }
    }

//    function updateMontoReservado($idOpex, $montoMO) {
//        $Query = "UPDATE cuentaopexitemplan SET monto_temporal = (monto_temporal - '$montoMO') WHERE idOpex='$idOpex';";
//        $this->db->query($Query, array());
//        return true;
//    }
//
//    function updateMontoConsumido($idOpex, $montoMO) {
//        $Query = "UPDATE cuentaopexitemplan SET monto_real = (monto_real + '$montoMO') WHERE idOpex='$idOpex';";
//        $this->db->query($Query, array());
//        return true;
//    }

    function updateMontoDisponible($ceco, $cuenta, $areFuncional, $montoMO) {
        $Query = "UPDATE cuenta_opex_pex SET monto_real = (monto_real - '$montoMO') WHERE ceco='$ceco' AND cuenta='$cuenta' AND areFuncional='$areFuncional';";
        $this->db->query($Query, array());
        return true;
    }

    function updateTranss($solicitud) {
        $Query = "UPDATE transaccionopexitemplan SET estadoTransaccion = 2 WHERE codigo='$solicitud';";
        $this->db->query($Query, array());
        return true;
    }

    function selectItemfault($solicitud) {
        $Query = " SELECT * FROM  planobra WHERE solicitud_oc='$solicitud';";
        $result = $this->db->query($Query, array());
        if ($result->row()->itemPlan) {
            return $result->row()->itemPlan;
        } else {
            return false;
        }
    }

    function selectIdOpex($solicitud) {
        $Query = " SELECT * FROM  itemplan_solicitud_orden_compra WHERE codigo_solicitud='$solicitud';";
        $result = $this->db->query($Query, array());
        if ($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }

    //////

    function UpdateItemplan($itemplaArray) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->update_batch('planobra', $itemplaArray, 'itemplan');
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                throw new Exception('Error al update el planobra.');
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

    function getItemplanId($Itemplan) {
        $Query = 'SELECT * FROM planobra where itemplan = ?';
        $result = $this->db->query($Query, array($Itemplan));
        $idEstadoPlan = $result->row()->idEstadoPlan;
        return $idEstadoPlan;
    }
	
	
    function getInfoItemplan($itemplan) {
        $Query = "SELECT * from planobra where itemplan = ?;";
        $result = $this->db->query($Query, array($itemplan));
        if ($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }	
	
   function actualizarMontoReal($idfase,$monto) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $this->db->trans_begin();
            $data = array(
              
                "monto_real" => $monto
            );
            $this->db->where('idlineaopex_fase', $idfase);
            $this->db->update('lineaopex_fase', $data);
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Hubo un error al actualizar el Plan Obra.');
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
	
	function getLineaOPexCapturar($ceco,$cuenta,$areafuncional,$idFase,$idSubProyecto) {
        $Query = "SELECT * FROM ((subproyecto_lineaopex_combinatoria as sub INNER JOIN combinatoria_opex as com ON sub.idcombinatoria = com.idcombinatoria) INNER JOIN lineaopex_fase as li ON sub.idlineaopex_fase = li.idlineaopex_fase)
        WHERE sub.idSubProyecto='$idSubProyecto'
        AND com.ceco='$ceco'
        AND com.areafuncional='$areafuncional'
        AND com.cuenta='$cuenta'
        AND li.idfase='$idFase' ";
        $result = $this->db->query($Query, array($idSubProyecto));
        if ($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }
	
    function actualizacionMonto($itemplan,$codigo) {
        $Query = "SELECT * FROM itemplan_x_solicitud_oc WHERE itemplan='$itemplan' and codigo_solicitud_oc='$codigo'";
        $result = $this->db->query($Query, array($itemplan));
        if ($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }
}
