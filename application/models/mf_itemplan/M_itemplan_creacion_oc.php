<?php

class M_itemplan_creacion_oc extends CI_Model {

    //http://www.codeigniter.com/userguide3/database/results.html
    function __construct() {
        parent::__construct();
    }

    function getBandejaSolOC($cod_solicitud, $itemplan, $estado) {
        $Query = " select so.*,(CASE 	WHEN tipo_solicitud = 1 THEN 'CREACION OC'
										WHEN tipo_solicitud = 2 THEN 'EDICION OC'
										WHEN tipo_solicitud = 3 THEN 'CERTIFICACION OC' 
										WHEN tipo_solicitud = 4 THEN 'ANULACION POS. OC' END) as tipoSolicitud,
									p.proyectoDesc, sp.subProyectoDesc, e.empresaColabDesc, 
									(CASE WHEN so.estado = 1 THEN 'PENDIENTE' 
										WHEN so.estado = 2 THEN 'ATENDIDO' 
										WHEN so.estado = 3 THEN 'CANCELADO'
										WHEN so.estado = 4 THEN 'EN ESPERA DE EDICION' 
										WHEN so.estado = 5 THEN 'EN ESPERA DE ACTA' END) as estado_sol,
								CONCAT(u.nombres, ' ' ,u.ape_paterno) AS nombreCompleto,
								FORMAT((SELECT SUM(ii.costo_unitario_mo)
										FROM itemplan_x_solicitud_oc ii
										WHERE ii.codigo_solicitud_oc = so.codigo_solicitud),2) AS costo_total,
								(SELECT COUNT(1)
								FROM itemplan_x_solicitud_oc ii
								WHERE ii.codigo_solicitud_oc = so.codigo_solicitud) AS numItemplan,
								so.costo_sap,
								CASE WHEN po.solicitud_oc_dev = so.codigo_solicitud THEN 1 
								ELSE 0 END flg_pdf_edi,
								con.alias as contrato_padre,con.contrato_marco,con.tipo_moneda,
								po.itemplan,
								po.departamento_matriz,
								po.provincia_matriz,
								po.distrito_matriz,
								po.codigo_unico,
								po.nom_estacion_matriz
												
								from  (empresacolab e,
								subproyecto sp, 
								proyecto p, 
								itemplan_solicitud_orden_compra so,
								itemplan_x_solicitud_oc i,
								planobra po,contrato con) left join usuario u on so.usuario_valida = u.id_usuario
								where so.idEmpresaColab = e.idEmpresaColab
								and so.idSubProyecto = sp.idSubProyecto
								and sp.idProyecto = p.idProyecto
								and so.estado in (1,2,3,4,5)
								and i.codigo_solicitud_oc = so.codigo_solicitud 
								and i.itemplan = po.itemplan AND con.id_contrato = po.idContrato ";
        if ($cod_solicitud != null) {
            $Query .= " and so.codigo_solicitud = '" . $cod_solicitud . "'";
        }
        if ($itemplan != null) {
            $Query .= " and po.itemPlan = '" . $itemplan . "'";
        }
        if ($estado != null) {
            $Query .= " and so.estado= " . $estado;
        }
        $Query .= " Group by so.codigo_solicitud";
        $result = $this->db->query($Query, array());
		_log($this->db->last_query());
        return $result->result();
    }

    public function getPtrsByHojaGestion($hoja_gestion) {
        $sql = "SELECT
	po.itemPlan,
	po.idSubProyecto,
	po.paquetizado_fg,
	emp.empresaColabDesc,
	FORMAT( po.costo_unitario_mo, 2 ) AS limite_costo_mo,
CASE
		
		WHEN cen.jefatura = 'LIMA' THEN
		'LIMA' ELSE 'PROVINCIA' 
	END AS jefatura,
	CASE
		
		WHEN cen.jefatura = 'LIMA' THEN
		1  ELSE 2
	END AS id_jefatura,
	sol.ceco,
	sol.cuenta,
	sol.area_funcional,
	sol.orden_compra,
	sol.codigo_certificacion,
	po.solicitud_oc,
	null as contratoMarco,
        emp.idEmpresaColab
FROM
	planobra po,
	subproyecto sp,
	pqt_central cen,
	zonal zon,
	empresacolab emp,
	itemplan_solicitud_orden_compra sol
WHERE
	zon.idZonal = po.idZonal 
	AND po.idSubProyecto = sp.idSubProyecto 
	AND po.idCentralPqt = cen.idCentral 
	AND po.idEmpresaColab = emp.idEmpresaColab
	AND po.solicitud_oc = sol.codigo_solicitud
	and po.solicitud_oc = '$hoja_gestion'";
        
	$result = $this->db->query($sql, array($hoja_gestion));
	return $result->result_array();
    }

    public function getItemOrdenCompraEdicion($codigo_solicitud) {
        $sql = "SELECT
	po.*,
	zon.zonalDesc,
	subProyectoDesc,
	FORMAT( po.costo_devolucion, 2 ) AS costo_devolucion 
FROM
	planobra po,
	subproyecto sp,
	zonal zon 
WHERE
	zon.idZonal = po.idZonal 
	AND po.idSubProyecto = sp.idSubProyecto
                and po.solicitud_oc_dev = ?";
        $result = $this->db->query($sql, array($codigo_solicitud));
        log_message('error', $this->db->last_query());
        return $result->result();
    }

    public function getItemOrdenCompraCerti($codigo_solicitud) {
        $sql = "  SELECT
						po.*,
						subProyectoDesc,
						zon.zonalDesc,
						FORMAT( po.costo_unitario_mo_certi, 2 ) AS costo_unitario_mo_certi 
					FROM
						planobra po,
						subproyecto sp,
						zonal zon 
					WHERE
					zon.idZonal = po.idZonal AND
						po.idSubProyecto = sp.idSubProyecto
									and po.solicitud_oc_certi = ?";
							$result = $this->db->query($sql, array($codigo_solicitud));
							log_message('error', $this->db->last_query());
							return $result->result();
						}

	public function getTablaListarPxq($SubProyecto, $EmpresaColab, $Jefatura, $paquetizado_fg, $itemPlan) {
		$sql = "SELECT
			tp.codigo AS codigo_m,
			tp.descripcion AS tipoPreciario,
				tp.id AS idtipoPreciario,
			pa.idActividad,
			pa.codigo,
			pa.descripcion AS partidaPqt,
			SUM(pa.baremo) AS baremo,
			pq.costo,
			(SUM(pa.baremo)* 1) AS q,
			FORMAT( po.costo_unitario_mo, 2 ) AS round,
			FORMAT( ( po.costo_unitario_mo / pq.costo ), 2 ) AS cantidad,
			po.itemPlan,
			po.idSubProyecto,
			po.paquetizado_fg,
			emp.empresaColabDesc,
			FORMAT( po.costo_unitario_mo, 2 ) AS limite_costo_mo,
		CASE
				
				WHEN cen.jefatura = 'LIMA' THEN
				'LIMA' ELSE 'PROVINCIA' 
			END AS jefatura,
			CASE
				
				WHEN cen.jefatura = 'LIMA' THEN
				1  ELSE 2
			END AS id_jefatura,
			sol.ceco,
			sol.cuenta,
			sol.area_funcional,
			sol.orden_compra,
			sol.codigo_certificacion,
			po.solicitud_oc,
			con.contratoMarco,
				emp.idEmpresaColab
		FROM
			planobra po,
			partidas pa,
			pqt_tipo_preciario tp,
			pqt_preciario pq,
			pqt_central c,
			subproyecto sp,
			pqt_central cen,
			zonal zon,
			empresacolab emp,
			itemplan_solicitud_orden_compra sol,
			contrato_marco con
		WHERE
			pa.idPrecioDiseno = tp.id 
			AND tp.id = pq.idTipoPreciario 
			AND pa.idActividad = 388 
			AND po.idEmpresaColab = pq.idEmpresaColab 
			AND po.idCentralPqt = c.idCentral 
			AND po.itemplan = '$itemPlan' 
			AND zon.idZonal = po.idZonal 
			AND po.idSubProyecto = sp.idSubProyecto 
			AND po.idCentralPqt = cen.idCentral 
			AND po.idEmpresaColab = emp.idEmpresaColab
			AND po.solicitud_oc = sol.codigo_solicitud
			AND con.idEmpresaColab = po.idEmpresaColab
			AND pq.tipoJefatura = ( CASE WHEN c.jefatura = 'LIMA' THEN 1 ELSE 2 END );";


        $result = $this->db->query($sql, array());
        return $result->result();
    }

    function update_solicitud_oc($codigo_solicitud, $arrayData) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->where('codigo_solicitud', $codigo_solicitud);
            $this->db->update('itemplan_solicitud_orden_compra', $arrayData);
            if ($this->db->affected_rows() != 1) {
                throw new Exception('Error al actualizar la informacion.');
            } else {
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se actualizo correctamente!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    function getItemplan($Itemplan) {
        $Query = 'SELECT * FROM planobra where solicitud_oc_dev = ?';
        $result = $this->db->query($Query, array($Itemplan));
        $idEstadoPlan = $result->row()->itemPlan;
        return $idEstadoPlan;
    }

    function getMontoDev($Itemplan) {
        $Query = 'SELECT * FROM planobra where solicitud_oc_dev = ?';
        $result = $this->db->query($Query, array($Itemplan));
        $idEstadoPlan = $result->row()->costo_devolucion;
        return $idEstadoPlan;
    }

    function update_itemplan($arrayDataItem, $itemplan) {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->where('itemPlan', $itemplan);
            $this->db->update('planobra', $arrayDataItem);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al actualizar la informacion.');
            } else {
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se actualizo correctamente!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }

    function getNumeroMaterial($empresaColabDesc, $jefatura, $tipoPreciario) {
        $Query = "SELECT * FROM numero_material where idEmpresa = '$empresaColabDesc' AND idZona='$jefatura' AND idTipo='$tipoPreciario';";
        $result = $this->db->query($Query, array());
        $numero = $result->row()->numero;
        return $numero;
    }

function opex_desc($id) {
	$sisego = "SISEGO";
        $Query = "SELECT * FROM cuenta_opex_pex WHERE idOpex = '$id';";
        $result = $this->db->query($Query, array());
        if ($result->row() != null) {
            return $result->row()->opexDesc;
        } else {
            return $sisego;
        }
    }
	
	
	function update_solicitud_oc_certi_opex($codigo_solicitud, $arrayData, $dataPo, $idUsuario, $countPoValidadas){
		_log("ENTRO0");
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->where('codigo_solicitud', $codigo_solicitud);
	        $this->db->update('itemplan_solicitud_orden_compra', $arrayData);
	        if($this->db->affected_rows() != 1) {
	            throw new Exception('Error al actualizar la informacion.');
	        }else{_log("ENTRO1");
	            $this->db->where('itemplan', $dataPo['itemplan']);
	            $this->db->update('planobra', $dataPo);
	            if($this->db->affected_rows() != 1) {
	                throw new Exception('Error al actualizar la informacion.');
	            }else{_log("ENTRO2");
					$data = $this->updateCertiPoPin($codigo_solicitud, 5, $idUsuario);
					if($countPoValidadas > 0) {_log("ENTRO3");
						$data = $this->insertLogPO($codigo_solicitud, 5, 'CERTIFICADO OC', $idUsuario, 6);
_log("ENTRO4");
						if($data['error'] == EXIT_ERROR) {
							throw new Exception($data['msj']);
						} else {
							$data = $this->updatePOByItemplan($codigo_solicitud, 5, 6);
	_log("ENTRO7");
							if($data['error'] == EXIT_ERROR) {
								throw new Exception($data['msj']);
							} else {
								$data['error'] = EXIT_SUCCESS;
								$data['msj'] = 'Se actualizo correctamente!';
							}
						}
					} else {_log("ENTRO6");
						$data['msj'] = 'Se actualizo correctamente, ojo que no se encontro POs validadas para pasar a certificado.';
						$data['error'] = EXIT_SUCCESS;
					}
	            }
	        }
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	    }
	    return $data;
	}
	
	function updatePOByItemplan($codigo_solicitud, $estado_actual, $estado_final) {
		$sql = "UPDATE planobra_po ppo,
				       planobra po
				   SET ppo.estado_po = ?
				 WHERE ppo.estado_po = ?
				   AND ppo.itemplan = po.itemplan
			  	   AND po.solicitud_oc_certi  = ?";
		$result = $this->db->query($sql,array($estado_final, $estado_actual, $codigo_solicitud));
		if($this->db->affected_rows() == 0) {
			$data['error'] = EXIT_ERROR;
			$data['msj'] = 'No hay POs validadas, las POs deben estar validadas para ser certificadas.';
		}else{              
			$data['error'] = EXIT_SUCCESS;
			$data['msj'] = 'Se actualizo correctamente!';
		}
		return $data;
	}
	
	function insertLogPO($codigo_solicitud, $po_estado_actual, $descripcion, $idUsuario, $po_estado_final) {
		$sql = "INSERT INTO log_planobra_po (codigo_po, itemplan, 
											 idUsuario, fecha_registro, 
											 idPoestado, controlador)
				SELECT ppo.codigo_po, po.itemplan,?, NOW(), ?, ? 
				  FROM planobra_po ppo,
				       planobra po
				 WHERE ppo.estado_po = ?
				   AND po.itemplan = ppo.itemplan
			  	   AND po.solicitud_oc_certi  = ?";
		$result = $this->db->query($sql,array($idUsuario, $po_estado_final, $descripcion, $po_estado_actual, $codigo_solicitud));
		if($this->db->affected_rows() == 0) {
			$data['error'] = EXIT_ERROR;
			$data['msj']   = 'error al registrar el log de po';
		}else{
			$data['error'] = EXIT_SUCCESS;
			$data['msj']   = 'Se registro correctamente!';
		}
		return $data;
	}
	
	function getCountPoByItemplanAndEstado($itemplan, $estado_po) {
		$sql = "SELECT COUNT(1) as count
		          FROM planobra_po 
				 WHERE itemplan  = ?
				   AND estado_po = ?";
		$result = $this->db->query($sql, array($itemplan, $estado_po));
		return $result->row_array()['count'];
	}
	
	function updateCertiPoPin($codigo_solicitud, $estadoPo, $usuario) {
		$sql = "UPDATE ptr_planta_interna pt,
		               planobra po
		           SET rangoPtr = ?, 
				       fecha_ultimo_estado = NOW(), 
					   usua_ultimo_estado = ?
		         WHERE po.itemplan = pt.itemplan
				   AND rangoPtr NOT IN (5,6)
				   AND po.solicitud_oc_certi  = ?";
				   
		$result = $this->db->query($sql, array($estadoPo, $usuario, $codigo_solicitud));
		
		if ($this->db->trans_status() === FALSE) {
			$dataSalida['error']    = EXIT_ERROR;
			$dataSalida['msj']      = 'Error al actualizar po pin!';
		} else {
			$dataSalida['error']    = EXIT_SUCCESS;
			$dataSalida['msj']      = 'Operacion exitosa!';
		}
		
		return $dataSalida;
	}
	
	function update_solicitud_oc_anulado($codigo_solicitud, $arrayData, $dataPo){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->where('codigo_solicitud', $codigo_solicitud);
	        $this->db->update('itemplan_solicitud_orden_compra', $arrayData);
			_log($this->db->last_query());
	        if($this->db->affected_rows() != 1) {
	            throw new Exception('Error al actualizar la informacion.');
	        }else{	            
	            $this->db->where('solicitud_oc_anula_pos', $dataPo['solicitud_oc_anula_pos']);
	            $this->db->update('planobra', $dataPo);
				_log($this->db->last_query());
	            if($this->db->affected_rows() != 1) {
	                throw new Exception('Error al actualizar la informacion.');
	            }else{    	            
    	            $data['error'] = EXIT_SUCCESS;
    	            $data['msj'] = 'Se actualizo correctamente!';
	            }
	        }
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	    }
	    return $data;
	}
	
	function getObrasToEvaluarTrunco($codigo_solicitud)
	{
	    $sql = "select itemplan, idEstadoPlan from planobra where solicitud_oc_anula_pos = ?";
	    $result = $this->db->query($sql,array($codigo_solicitud));
	    return $result->result();
	}
}
