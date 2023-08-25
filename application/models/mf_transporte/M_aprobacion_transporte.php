<?php
class M_aprobacion_transporte extends CI_Model{
    //http://www.codeigniter.com/userguide3/database/results.html
    function __construct(){
        parent::__construct();

    }

    function getPtrToLiquidacion($subPry, $eecc, $zonal, $itemPlan, $mesEjec, $area, $estado, $idUsuario, $estadoPtr){
        $ideecc  = $this->session->userdata("eeccSession");
		
        $Query = " SELECT pp.codigo_po AS ptr, 
                            null as flg_rechazado,
                            pp.itemplan, sp.subProyectoDesc, z.zonalDesc, ec.empresaColabDesc, a.areaDesc, pp.estado_po as estado,
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
                    END as fechaPrevEjecMes, 
                    po.fechaPrevEjec,
                    null as costo_mo,
                    pp.costo_total as costo_mat,
                    pp.costo_total as total,
                    1 as tipo_po,
					NULL as orden_compra,
					NULL as solicitud_oc
                FROM planobra_transporte po,
					 planobra_po_transporte pp,                  
                     empresacolab ec,
                     zonal z,
                     subproyecto sp,
                     subproyectoestacion se,
                     estacionarea ea,
                     area a
               WHERE po.itemplan = pp.itemplan
                 AND pp.idSubProyectoEstacion = se.idSubProyectoEstacion
                 AND se.idEstacionArea        = ea.idEstacionArea 
                 AND ea.idArea                = a.idArea
                 AND po.idEmpresaColab        = ec.idEmpresaColab
                 AND po.idZonal               = z.idZonal
                 AND po.idSubProyecto         = sp.idSubProyecto
                 AND pp.estado_po = ".PO_REGISTRADO."
                 AND CASE WHEN ".$ideecc." = 0 OR ".$ideecc." = 6 THEN ec.idEmpresaColab = ec.idEmpresaColab END" ;

        $result = $this->db->query($Query,array());
        return $result;
    }
	
    function updateDetalleProducto($idPtr, $itemplan, $vale_re, $estado, $estado_ultimo, $rangoPtr, $flgRechazado){
		$dataSalida['error'] = EXIT_ERROR;
        $dataSalida['msj']   = null;
        try{

            $this->db->trans_begin();
            //Fin
            if($flgRechazado == 0) {
                $costoMo = $this->getCostoMoPin($idPtr);
				
				if($costoMo == null || $costoMo == '') {
                    throw new Exception('No se ingreso la cotizacion.');
                }
				
                $flgValid = $this->m_utils->generarSolicitudOC(NULL, $itemplan, $costoMo);

                if($flgValid == 6) {
                    throw new Exception('No tiene una pep configurada o la pep no existe, verificar con el encargado.');
                }

                if($flgValid == 2) {
                    throw new Exception('No cuenta con monto disponible.');
                }

                if($flgValid == 3) {
                    throw new Exception('No cuenta con presupuesto.');
                }
                $dataSalida['error']    = EXIT_SUCCESS;
                $dataSalida['msj']      = 'Operacion exitosa, solicitud OC generada correctamente';
                $this->db->trans_commit();
            } else {
                $data = array(
                                "vale_reserva"        => $vale_re,
                                "estado"              => $estado,
                                "ultimo_estado"       => $estado_ultimo,
                                "fecha_ultimo_estado" => date('Y-m-d H:i:s'),
                                "usua_ultimo_estado"  => $this->session->userdata('userSession'),
                                "fecha_aprob"         => date('Y-m-d H:i:s'),
                                "usua_aprob"          => $this->session->userdata('userSession'),
                                "rangoPtr"            => $rangoPtr,
                                "flg_rechazado"       => $flgRechazado
                            );
                $this->db->where('ptr', $idPtr);
                $this->db->where('itemplan', $itemplan);
                $this->db->update('ptr_planta_interna', $data);
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                } else {
                    $dataSalida['error']    = EXIT_SUCCESS;
                    $dataSalida['msj']      = 'Operacion exitosa!';
                    $this->db->trans_commit();
                }
            }
        }catch(Exception $e){
            $dataSalida['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $dataSalida;
    }

    function updatePtrTo01($idPtr, $grafo){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->trans_begin();
            $data = array(
                "estado"  => '01 - APROBADA VALORIZADA',
                "fec_aprob" => date("d/m/Y H:i:s"),
                "usua_aprob" => $this->session->userdata('userSession'),
                "estado_asig_grafo" => '0'
            );
            $this->db->where('ptr', $idPtr);
            $this->db->update('web_unificada_det', $data);
            if($this->db->affected_rows() == 0) {
                throw new Exception('Hubo un error al actualizar en web_unificada_det');
            }

            $this->db->query("SELECT getGrafoOnePTR('".$idPtr."');");
            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                $data['error']    = EXIT_SUCCESS;
                $data['msj']      = 'Se actualizo correctamente!';
            }else{
                $this->db->trans_rollback();
            }

        }catch(Exception $e){
            $data['msj']   = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    function   getBandejaPreMo($SubProy,$eecc,$zonal,$itemPlan,$mesEjec,$expediente){
        $Query = " SELECT po.itemPlan,po.indicador ,sp.subProyectoDesc, z.zonalDesc, ec.empresaColabDesc,
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
                            (SELECT count(1) FROM estacionarea, subproyectoestacion, detalleplan 
LEFT JOIN ptr_expediente 
ON detalleplan.itemplan = ptr_expediente.itemplan
AND  detalleplan.poCod =  ptr_expediente.ptr
where detalleplan.idSubProyectoEstacion = subproyectoestacion.idSubProyectoEstacion
AND subproyectoestacion.idEstacionArea = estacionarea.idEstacionArea
AND detalleplan.itemplan = po.itemplan
AND idEstacion = 1
and ptr_expediente.ptr is null) as hasDise,
                            (SELECT COUNT(1) FROM itemplan_expediente where itemplan = po.itemplan and estado = 'ACTIVO') as hasExpe
            from planobra_transporte po, subproyecto sp, zonal z, empresacolab ec 
            where po.idSubProyecto = sp.idSubProyecto 
            and po.idZonal = z.idZonal
            and po.idEmpresaColab = ec.idEmpresaColab
            and po.idEstadoPlan = 4 and (SELECT COUNT(1) FROM itemplan_expediente WHERE estado_final = 'FINALIZADO' and itemplan = po.itemPlan ) = 0" ;
        if($SubProy != ''){
            $Query .= " AND sp.subProyectoDesc REGEXP '".str_replace(',','|',$SubProy)."'";
        }
        if($eecc != ''){
            $Query .= " AND ec.empresaColabDesc LIKE '%".$eecc."%'";
        }
        if($zonal != ''){
            $Query .= " AND z.zonalDesc = '".$zonal."'";
        }
        if($itemPlan != ''){
            $Query .= " AND po.itemplan = '".$itemPlan."'";
        }

        $certi = "";
        if($expediente!=''){
            if($expediente=='SI'){
                $certi = ">";
            }else if($expediente=='NO'){
                $certi = "=";
            }
        }

        if($expediente!='' && $mesEjec != ''){
            $Query .= " HAVING  fechaPrevEjec = '".$mesEjec."' AND edit ".$certi." 0 ";
        }else if($expediente!='' && $mesEjec == ''){
            $Query .= " HAVING  edit ".$certi." 0 ";
        }else if($expediente=='' && $mesEjec != ''){
            $Query .= " HAVING  fechaPrevEjec = '".$mesEjec."'";
        }

        $result = $this->db->query($Query,array());
        return $result;
    }

    function   getPtrByItemplan($itemplan){
        $Query = " SELECT 
                    dp.poCod as ptr,
                    dp.itemplan, 
                    s.subproyectoDesc,
                    a.areaDesc, 
                    substring(wud.estado,1,3) as estado_wud,
                    substring(wu.est_innova,1,3) as estado_wu,
                    wu.desc_area, 
                    wu.jefatura, 
                    wu.eecc,
                    wu.idEstadoPtr,
                    wu.f_creac_prop,
                    wud.valor_material,
	                wud.valor_m_o,
                    TRIM(SUBSTRING_INDEX(wu.vr,':',-1)) as vr_wu,
                    wud.vale_reserva as vr_wud, CASE WHEN pe.ptr is not null then 1 else 0 end as hasPtrExpe,
                    e.estacionDesc
                    from detalleplan dp 
                    INNER JOIN subproyectoestacion se ON dp.idSubProyectoEstacion = se.idSubProyectoEstacion 
                    INNER JOIN subproyecto s ON se.idSubProyecto = s.idSubProyecto 
                    INNER JOIN estacionarea ea ON se.idEstacionArea = ea.idEstacionArea 
                    INNER JOIN area a ON ea.idArea = a.idArea 
                    INNER JOIN estacion e ON ea.idEstacion = e.idEstacion
                    LEFT JOIN web_unificada_det wud ON wud.ptr = dp.poCod 
                    LEFT JOIN web_unificada wu ON wu.ptr = dp.poCod 
                    LEFT JOIN ptr_expediente pe
                    ON dp.itemplan = pe.itemplan AND dp.poCod = pe.ptr
                    where dp.itemplan = ?
                    AND dp.poCod != 'NOREQUIERE'
                    order by  
                    substring(wu.est_innova,1,3) ASC" ;

        $result = $this->db->query($Query,array($itemplan));
        return $result;
    }

    function   getCertificadoByItemPlan($itemplan){
        $Query = "SELECT * FROM itemplan_expediente WHERE itemplan = ? ORDER BY estado;";
        $result = $this->db->query($Query,array($itemplan));
        return $result;
    }

    function haveActivo($itemplan) {
        $sql = "SELECT COUNT(1) as count FROM itemplan_expediente where estado = 'ACTIVO' and itemplan = ? ;";
        $result = $this->db->query($sql,array($itemplan));
        return ($result->row()->count);
    }

    function cancelCertificado($id){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->trans_begin();
            $data = array(
                "estado"  => 'DEVUELTO',
                "estado_final"  => null,
                "usuario" => $this->session->userdata('userSession')
            );
            $this->db->where('id', $id);
            $this->db->update('itemplan_expediente', $data);
            if($this->db->affected_rows() == 0) {
                throw new Exception('Hubo un error al actualizar en itemplan_expediente');
            }else{
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj']      = 'Se actualizo correctamente!';
            }

        }catch(Exception $e){
            $data['msj']   = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    function saveCertificado($itemplan, $fecha, $comentario){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->trans_begin();
            $data = array(
                "itemplan"  => $itemplan,
                "fecha" => $fecha,
                "comentario" => $comentario,
                "usuario" => $this->session->userdata('userSession'),
                "estado" => 'ACTIVO',
                "estado_final" => 'PENDIENTE'
            );

            $this->db->insert('itemplan_expediente', $data);
            if($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar en itemplan_expediente');
            }else{
                $this->db->trans_commit();
                $data['error']    = EXIT_SUCCESS;
                $data['msj']      = 'Se inserto correctamente!';
            }

        }catch(Exception $e){
            $data['msj']   = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

    function preAprobarTerminados($itemplan){
        $dataeXIT['error'] = EXIT_ERROR;
        $dataeXIT['msj']   = null;
        try{
            $this->db->trans_begin();

            $data = array(
                "estado"   => '01 - APROBADA VALORIZADA',
            );
            $this->db->where('itemplan', $itemplan);
            $this->db->where("(estado_asig_grafo='0' OR estado_asig_grafo='1')");
            $this->db->update('web_unificada_det', $data);
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Hubo un error al actualizar en web_unificada_det');
            }

            $data = array(
                "estado_final"  => 'FINALIZADO',
                "usuario_valida" => $this->session->userdata('userSession'),
                "fecha_valida" => date("Y-m-d H:i:s")
            );
            $this->db->where('itemplan', $itemplan);
            $this->db->where('estado', 'ACTIVO');
            $this->db->update('itemplan_expediente', $data);
            if($this->db->affected_rows() == 0) {
                throw new Exception('Hubo un error al actualizar en itemplan_expediente');
            }

            $this->db->query("SELECT getGrafoByItemplan('".$itemplan."');");
            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                $dataeXIT['error']    = EXIT_SUCCESS;
                $dataeXIT['msj']      = 'Se actualizo correctamente!';
            }else{
                $this->db->trans_rollback();
            }

        }catch(Exception $e){
            $dataeXIT['msj']   = $e->getMessage();
            $this->db->trans_rollback();
        }

        return $dataeXIT;
    }

    function getItemplanExpediente(){
        $Query = "  SELECT po.itemPlan 
            	        FROM planobra_transporte po where  po.idEstadoPlan = 4 
            	        and (SELECT COUNT(1) FROM itemplan_expediente WHERE estado_final = 'FINALIZADO' and itemplan = po.itemPlan ) = 0";
        $result = $this->db->query($Query,array());
        return $result;
    }

    function insertOrDeletePtrExpediente($accion, $ptr, $itemplan){
        $rpta['error'] = EXIT_ERROR;
        $rpta['msj']   = null;
        try{

            if($accion=='1'){//INSERT
                $this->db->trans_begin();
                $data = array(
                    "ptr"  => $ptr,
                    "itemplan" => $itemplan);
                $this->db->insert('ptr_expediente', $data);
                if($this->db->affected_rows() != 1) {
                    $this->db->trans_rollback();
                    throw new Exception('Error al insertar en ptrExpediente');
                }else{
                    $this->db->trans_commit();
                    $rpta['error']    = EXIT_SUCCESS;
                    $rpta['msj']      = 'Se agrego correctamente!';
                }

            }else if($accion=='2'){//DELETE

                $this->db->trans_begin();
                $this->db->where('ptr', $ptr);
                $this->db->where('itemplan', $itemplan);
                $this->db->delete('ptr_expediente');
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    throw new Exception("Error al Eliminar ptrExpediente");
                }else {
                    $this->db->trans_commit();
                    $rpta['msj'] = 'Se elimino correctamente ';
                    $rpta['error']  = EXIT_SUCCESS;
                }

            }

        }catch(Exception $e){
            $rpta['msj']   = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $rpta;
    }

        function consultarCostoPtr($itemplan, $ptr) {
        $sql = " SELECT DISTINCT 
                        acpi.descripcion,
                        ppi.ptr,
                        pxa.precio,
                        pxa.baremo,
                        pxa.cantidad,
                        pxa.costo_mo,
                        acpi.costo_material,
                        pxa.costo_mat,
                        pxa.total,
                        pxa.id_actividad,
                        pxa.cantidad_final,
                        pxa.id_ptr_x_actividades_x_zonal
                   FROM ptr_planta_interna ppi,
                        ptr_x_actividades_x_zonal pxa,
                        partidas acpi
                  WHERE ppi.ptr          = pxa.ptr
                    AND acpi.idActividad = pxa.id_actividad
                    AND acpi.flg_tipo    = 1
                    AND ppi.ptr          = '".$ptr."'
                    AND ppi.itemplan     = '".$itemplan."'";
        $result = $this->db->query($sql);
        return $result->result();
    }

    function getActividadesxSubproyecto($idSubProyecto, $ptr, $itemplan) {
        $sql = "SELECT api.idActividad, 
                       api.codigo, 
                       api.descripcion, 
                       api.baremo, 
                       api.costo_material 
                  FROM partidas api,
                       actividad_x_subproyecto axs
                 WHERE api.idActividad   = axs.idActividad 
                   AND api.flg_tipo      = 1
                   AND axs.idSubProyecto = ".$idSubProyecto."
                   AND api.idActividad NOT IN (
                                                SELECT id_actividad 
                                                  FROM ptr_x_actividades_x_zonal 
                                                 WHERE ptr = '".$ptr."'
                                                   AND itemplan = '".$itemplan."')
                ORDER BY descripcion";
        $result = $this->db->query($sql);
        return $result->result();        
    }
    

    public function getPPODetalle($codigoPO)
    {
        $sql = "     SELECT ppod.codigo_po,
                            ppod.codigo_material,
                            m.descrip_material,
                            m.unidad_medida,
                            ROUND(m.costo_material,2) as costo_material,
                            ppod.cantidad_ingreso,
                            ppod.cantidad_final
                    FROM planobra_po_detalle ppod,
                            material m
                    WHERE ppod.codigo_material = m.id_material
                      AND ppod.codigo_po = '" . $codigoPO . "'";
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    public function aprobOCanlPoMatPI($ptr, $itemplan, $arrayUpdate,$fechaActual, $estado_po)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
    
        try {    
            $idUsuario = $this->session->userdata('idPersonaSession') ? $this->session->userdata('idPersonaSession') : null;
            if ($idUsuario != null) {
                $this->db->trans_begin();
                $this->db->where('itemplan', $itemplan);
                $this->db->where('codigo_po', $ptr);
                $this->db->update('planobra_po', $arrayUpdate);
                if ($this->db->trans_status() === false) {
                    $this->db->trans_rollback();
                    throw new Exception('Hubo un error al actualizar el monto.');
                } else {
                    $arrayInsertLog = array(
                        "codigo_po" => $ptr,
                        "itemplan" => $itemplan,
                        "idUsuario" => $idUsuario,
                        "fecha_registro" => $fechaActual,
                        "idPoestado" => $estado_po,
                        "controlador" => 'Bandeja PreAprob PI'
                    );
                    $this->db->insert('log_planobra_po', $arrayInsertLog);
                    if ($this->db->affected_rows() != 1) {
                        $this->db->trans_rollback();
                        throw new Exception('Error al insertar tabla log_planobra_po');
                    } else {
                        log_message('error', 'estado:'.$estado_po);
                        if($estado_po == PO_PREAPROBADO){
                            $this->db->query("SELECT getGrafoOnePTR('" . $ptr . "');");
                            if ($this->db->trans_status() === true) {
                                $this->db->trans_commit();
                                $data['error'] = EXIT_SUCCESS;
                                $data['msj'] = 'Se actualiz&oacute; correctamente!!';
                            } else {
                                $this->db->trans_rollback();
                                throw new Exception('Error al asignar grafo.');
                            }
                        }else{
                            $this->db->trans_commit();
                            $data['error'] = EXIT_SUCCESS;
                            $data['msj'] = 'Se actualiz&oacute; correctamente!!';
                        }                        
                    }
                }
    
            } else {
                $this->db->trans_rollback();
                throw new Exception('Su sesion de usuario ha expirado, intentelo de nuevo!!');
            }
    
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
    
        return $data;
    }
    
    function hasMatPinValidationByItemplanAprob($itemplan) {
        $sql = "SELECT COUNT(1) as count
                  FROM  planobra_transporte po, subproyecto sp, subproyectoestacion se, estacionarea ea, area a
                 WHERE po.idSubProyecto = sp.idSubProyecto
                   AND sp.idSubProyecto = se.idSubProyecto
                   AND se.idEstacionarea = ea.idEstacionArea
                   AND ea.idArea = a.idArea
                   AND po.itemplan = ?
                   AND ea.idEstacion = 11
                   AND tipoarea = 'MAT'";
        $result = $this->db->query($sql, array($itemplan));
        return $result->row()->count;
    }
	
	function aprobPo($dataPo, $idUsuario, $ptr) {
		$data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();	
			$this->db->update_batch('planobra_transporte',$dataPo, 'itemplan');
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				throw new Exception('Error al update el planobra_po_detalle_mo.');
			}else{
				$this->db->where('codigo_solicitud', $solicitud);
				$this->db->update('solicitud_orden_compra', $dataSolicitud);
				if($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					throw new Exception('Error al modificar el planobra_po');
				}else{
					$fechaActual = $this->m_utils->fechaActual();
					$data = array(
									"estado"              => ESTADO_02_TEXTO,
									"ultimo_estado"       => ESTADO_01_TEXTO,
									"fecha_ultimo_estado" => $fechaActual,
									"usua_ultimo_estado"  => $idUsuario,
									"fecha_aprob"         => $fechaActual,
									"usua_aprob"          => $idUsuario,
									"rangoPtr"            => 2,
									"flg_rechazado"       => FLG_APROBADO
								);
					$this->db->where('ptr', $ptr);
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
	
	//czavala 24.08.2020	
	function updateDetalleProductoOPEX($idPtr, $itemplan, $vale_re, $estado, $estado_ultimo, $rangoPtr, $flgRechazado, $dataPlanobra, $idOpex, $idUSuario, $costoMo){
	    $dataSalida['error'] = EXIT_ERROR;
	    $dataSalida['msj']   = null;
	    try{
	
	        $this->db->trans_begin();
	        //Fin
	        if($flgRechazado == 0) {
				log_message('error', 'pi:>3');
    	        $this->db->where('itemplan', $dataPlanobra['itemplan']);
                $this->db->update('planobra_transporte', $dataPlanobra);
				log_message('error', 'pi:>5');
                if ($this->db->trans_status() === FALSE) {
                    throw new Exception('Hubo un error al actualizar en planobra.');
                } else {
					log_message('error', 'pi:>6');
					log_message('error', 'pi:>'.$itemplan.'|'.$idOpex.'|'.$idUSuario);
                    $sql = "SELECT createSolOCObrasOPexByItemplanYCosto('$itemplan','$costoMo','$idUSuario') as result";					
                    $this->db->query($sql);
					log_message('error', 'pi:>7');
                    $dataSalida['error'] = EXIT_SUCCESS;
                    $dataSalida['msj'] = 'Se actualizo correctamente!';
                    $this->db->trans_commit();
                }	        	            
	            
	        } else {
				log_message('error', 'pi:>4');
	            $data = array(
	                "vale_reserva"        => $vale_re,
	                "estado"              => $estado,
	                "ultimo_estado"       => $estado_ultimo,
	                "fecha_ultimo_estado" => date('Y-m-d H:i:s'),
	                "usua_ultimo_estado"  => $this->session->userdata('userSession'),
	                "fecha_aprob"         => date('Y-m-d H:i:s'),
	                "usua_aprob"          => $this->session->userdata('userSession'),
	                "rangoPtr"            => $rangoPtr,
	                "flg_rechazado"       => $flgRechazado
	            );
	            $this->db->where('ptr', $idPtr);
	            $this->db->where('itemplan', $itemplan);
	            $this->db->update('ptr_planta_interna', $data);
	            if ($this->db->trans_status() === FALSE) {
	                $this->db->trans_rollback();
	            } else {
	                $dataSalida['error']    = EXIT_SUCCESS;
	                $dataSalida['msj']      = 'Operacion exitosa!';
	                $this->db->trans_commit();
	            }
	        }
	    }catch(Exception $e){
	        $dataSalida['msj'] = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $dataSalida;
	}
	
	function getCostoMoPin($po) {
        $sql = "SELECT ROUND(SUM(total), 2) AS costo_mo 
                  FROM ptr_x_actividades_x_zonal 
                 WHERE ptr = ?";
        $result = $this->db->query($sql,array($po));
        return $result->row_array()['costo_mo'];
    }
	
	function aprobarCotizacion($dataPlanobra, $solicitud_oc_creacion, $item_x_sol, $dataSapDetalle) {
	    $data['error'] = EXIT_ERROR;
	    $data['msj'] = null;
	    try {	
	        $this->db->trans_begin();
            $this->db->where('itemplan', $dataPlanobra['itemplan']);
            $this->db->update('planobra_transporte', $dataPlanobra);
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
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
}