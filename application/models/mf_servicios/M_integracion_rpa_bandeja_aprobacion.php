<?php
class M_integracion_rpa_bandeja_aprobacion extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
	public function getPoMaterialToRpa()
	{
	    $sql = "SELECT ppo.itemplan, ppo.codigo_po, ppo.pep1, ppo.pep2, ppo.grafo  
                FROM      planobra_po ppo, log_planobra_po lppo, planobra po 
                WHERE     ppo.codigo_po = lppo.codigo_po
                AND	      lppo.idPoestado = ppo.estado_po
                AND       ppo.itemplan = po.itemplan 
                AND	      ppo.flg_tipo_area = 1 
                AND       ppo.estado_po = 2
                AND  	  po.has_paralizado IS NULL
                AND 	  ppo.grafo NOT IN ('SIN PRESUPUESTO','SIN CONFIGURACION','NO HAY GRAFO')
				#AND       po.idSubProyecto = 540
				AND       (ppo.activo_rpa = 0 OR ppo.activo_rpa is null)  
                LIMIT 1";
	    $result = $this->db->query($sql,array());
        if($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
	}
	
	public function getPoMaterialToRpaList()
	{
	    $sql = "SELECT ppo.idestacion, po.idEstadoPlan, ppo.itemplan, ppo.codigo_po, ppo.pep1, ppo.pep2, ppo.grafo
                FROM      planobra po, planobra_po ppo LEFT JOIN pre_diseno pd ON ppo.itemplan = pd.itemplan
                and ppo.idEstacion = pd.idEstacion
                WHERE     ppo.itemplan = po.itemplan
                AND	      ppo.flg_tipo_area = 1
                AND       ppo.estado_po = 2
                AND  	  po.has_paralizado IS NULL
                AND 	  ppo.grafo NOT IN ('SIN PRESUPUESTO','SIN CONFIGURACION','NO HAY GRAFO', 'PEP NO EXISTE EN SAP')
				AND 	  po.idEstadoPlan IN (20,3,7)
				AND 	  CASE WHEN po.flg_update_adm IS NULL THEN   (CASE WHEN po.paquetizado_fg = 2 THEN 
																(CASE WHEN ppo.idEstacion IN (2,5) THEN 
																										(CASE WHEN pd.requiere_licencia = 2 THEN TRUE ELSE pd.liquido_licencia = 1 END) 
																ELSE (CASE WHEN ppo.idEstacion IN (6,15,16/*OC_FO,FO_ALIM,FO_DIST*/) THEN (CASE WHEN (select requiere_licencia from pre_diseno where itemplan = po.itemplan and idEstacion = 5/*FO*/ limit 1) = 2 THEN TRUE ELSE (select liquido_licencia from pre_diseno where itemplan = po.itemplan and idEstacion = 5/*FO*/ limit 1) = 1 END) 
																		   WHEN ppo.idEstacion IN (3/*OC_COAXIAL*/) THEN (CASE WHEN (select requiere_licencia from pre_diseno where itemplan = po.itemplan and idEstacion = 2/*COAXIAL*/ limit 1) = 2 THEN TRUE ELSE (select liquido_licencia from pre_diseno where itemplan = po.itemplan and idEstacion = 2/*COAXIAL*/ limit 1) = 1 END)
																	  ELSE po.idEstadoPlan in (20,3/*EN APROB, EN OBRA*/) END) 
																END) 
							ELSE TRUE END) 
						 ELSE TRUE END
			    AND 	po.solicitud_oc is not null
                AND 	po.estado_sol_oc = 'ATENDIDO'
	            AND     (ppo.activo_rpa = 0 OR ppo.activo_rpa is null)
				AND 	ppo.grafo <> 0";
	       $result = $this->db->query($sql, array());
	    return $result->result();
	}
	
	public function getMaterialesToSap($itemplan,$codigoPO){
	    $sql = " SELECT ppod.codigo_material as material,
						jse.codCentro as centro,
						ppod.cantidad_final as ctd,
						'L' AS t,
						jse.codAlmacen as almacen,						
                        DATE_FORMAT(CURDATE(), '%d.%m.%Y')  AS fecha
				   FROM (
				        planobra_po ppo,
						planobra_po_detalle ppod,
						planobra po,
						central c
						)
						LEFT JOIN
						(
						jefatura_sap jsap,
						jefatura_sap_x_empresacolab jse) ON (jse.idEmpresaColab = ppo.id_eecc_reg
						 AND jsap.idJefatura = jse.idJefatura
						 AND (CASE WHEN  (jsap.idZonal IS NULL OR jsap.idZonal = '') THEN c.jefatura = jsap.descripcion ELSE jsap.idZonal = c.idZonal END )
						 )
	
				WHERE ppo.codigo_po = ppod.codigo_po
					AND ppo.itemplan = po.itemplan
					AND po.idCentral = c.idCentral
					AND ppo.itemplan = ?
					AND ppo.codigo_po = ?";
	    $result = $this->db->query($sql, array($itemplan, $codigoPO));
	    return $result->result();
	}
	
	public function getMaterialesToSapPqt($itemplan, $codigoPO){
	    $sql = " SELECT ppod.codigo_material as material,
						jse.codCentro as centro,
						ppod.cantidad_final as ctd,
						'L' AS t,
						jse.codAlmacen as almacen,						
                        DATE_FORMAT(CURDATE(), '%d.%m.%Y')  AS fecha
				   FROM (
				        planobra_po ppo,
						planobra_po_detalle ppod,
						planobra po,
						pqt_central c
						)
						LEFT JOIN
						(
						jefatura_sap jsap,
						jefatura_sap_x_empresacolab jse) ON (jse.idEmpresaColab = ppo.id_eecc_reg
						 AND jsap.idJefatura = jse.idJefatura
						 AND (CASE WHEN  (jsap.idZonal IS NULL OR jsap.idZonal = '') THEN c.jefatura = jsap.descripcion ELSE jsap.idZonal = c.idZonal END )
						 )
	
				WHERE ppo.codigo_po = ppod.codigo_po
					AND ppo.itemplan = po.itemplan
					AND po.idCentralPqt = c.idCentral
					AND ppo.itemplan = ?
					AND ppo.codigo_po = ?";
	    $result = $this->db->query($sql, array($itemplan, $codigoPO));
	    return $result->result();
	}

	function tramaNoOkRpa($dataEstadoInsertLog, $dataPlanobraPo, $codigo_po, $itemplan){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $this->db->insert('log_tramas_rpa_sap', $dataEstadoInsertLog);
	        if($this->db->affected_rows() != 1) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar en log_tramas_rpa_sap');
	        }else{
	            $this->db->where('codigo_po', $codigo_po);
	            $this->db->where('itemplan', $itemplan);
	            $this->db->update('planobra_po', $dataPlanobraPo);
	            if($this->db->trans_status() === FALSE) {
	                $this->db->trans_rollback();
	                throw new Exception('Error al modificar el siom_obra');
	            }else{
	                $data['error'] = EXIT_SUCCESS;
	                $data['msj'] = 'Se actualizo correctamente!';
	                $this->db->trans_commit();
	            }
	        }
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
	
	function tramaOkRpa($dataEstadoInsertLog){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $this->db->insert('log_tramas_rpa_sap', $dataEstadoInsertLog);
	        if($this->db->affected_rows() != 1) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar en log_tramas_rpa_sap');
	        }else{
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se actualizo correctamente!';
                $this->db->trans_commit();
	        }
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
	
	public function aprobarPtrFromWebUnificada($ptr, $vale_re, $itemplan)
	{
	    $dataSalida['error'] = EXIT_ERROR;
	    $dataSalida['msj'] = null;
	    try {
	        $from = $this->getGrafoFromWU($ptr, $itemplan);
	        if($from==null){
	            throw new Exception('Grafo From no Detectado.');
	        }
	        $dataFrom = explode("-", $from);
	        $this->db->trans_begin();
	
	        $data = array(
	            "vale_reserva"         => $vale_re,
	            "estado_asig_grafo"    => ESTADO_CON_GRAFO_ULTILIZADO,
	            "fecha_asig_grafo"     => $this->fechaActual(),
	            "usua_asig_grafo"      => 'RPA SAP',
	        );
	        $this->db->where('ptr', $ptr);
	        $this->db->update('web_unificada_det', $data);
	        if ($this->db->affected_rows() == 0) {
	            throw new Exception('Hubo un error al actualizar en web_unificada_det');
	        }
	
	        if ($dataFrom[0] == DATA_FROM_ITEMPLAN_PEP2_GRAFO) {
	            $data = array(
	                "estado" => ESTADO_CON_GRAFO_ULTILIZADO,
	            );
	            $this->db->where('id', intval($dataFrom[1]));
	            $this->db->update('itemplan_pep2_grafo', $data);
	            /* if($this->db->affected_rows() != 1) {
	             //throw new Exception($this->db->);
	             throw new Exception('Hubo un error al actualizar en pep2_grafo itemplan');
	            }*/
	        } else if ($dataFrom[0] == DATA_FROM_SISEGO_PEP2_GRAFO) {
	            /*
				$data = array(
	                "estado" => ESTADO_CON_GRAFO_ULTILIZADO,
	            );
	            $this->db->where('id', intval($dataFrom[1]));
	            $this->db->update('sisego_pep2_grafo', $data);
	            if($this->db->affected_rows() != 1) {
	             //throw new Exception($this->db->);
	             throw new Exception('Hubo un error al actualizar en pep2_grafo sisego');
	            }*/
	
	        } else if ($dataFrom[0] == DATA_FROM_PEP2_GRAFO) {
	            $data = array(
	                "estado" => ESTADO_CON_GRAFO_ULTILIZADO,
	            );
	            $this->db->where('id', intval($dataFrom[1]));
	            $this->db->update('pep2_grafo', $data);
	            if ($this->db->affected_rows() != 1) {
	                //throw new Exception($this->db->);
	                throw new Exception('Hubo un error al actualizar en pep2_grafo');
	            }
	        }
	
	        $data = array(
	            'idEstadoPtr' => '2',
	            'est_innova' => '02 - VALORIZADA CON VALE DE RESERVA',
	            'rangoPtr' => '2',
	        );
	        $this->db->where('ptr', $ptr);
	        $this->db->update('web_unificada', $data);
	        if ($this->db->trans_status() === false) {
	            throw new Exception('Hubo un error al actualizar en web_unificada');
	        }
	
	        //Fin
	        if ($this->db->trans_status() === false) {
	            $this->db->trans_rollback();
	        } else {
	            $dataSalida['error'] = EXIT_SUCCESS;
	            $dataSalida['msj'] = 'Se actualizo correctamente!';
	            $this->db->trans_commit();
	        }
	
	    } catch (Exception $e) {
	        $dataSalida['msj'] = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $dataSalida;
	}
	
	function aprobarPOWebPO($ptr, $itemplan, $vale_reserva)
	{
	    $data['error'] = EXIT_ERROR;
	    $data['msj'] = null;
	
	    try {
	        
	        $from = $this->getGrafoFromPO($ptr, $itemplan);
	        if($from==null){
	            throw new Exception('Grafo From no Detectado.');
	        }
	        $dataFrom = explode("-", $from);
	        $this->db->trans_begin();
	        $arrayUpdate = array(
	            "estado_po" => 3,//APROBADO
	            "vale_reserva" => $vale_reserva
	
	        );
	        $this->db->where('itemplan', $itemplan);
	        $this->db->where('codigo_po', $ptr);
	        $this->db->update('planobra_po', $arrayUpdate);
	        if ($this->db->trans_status() === false) {
	            $this->db->trans_rollback();
	            throw new Exception('Hubo un error al actualizar el monto.');
	        } else {
	            if ($dataFrom[0] == DATA_FROM_ITEMPLAN_PEP2_GRAFO) {
	                $data = array(
	                    "estado" => ESTADO_CON_GRAFO_ULTILIZADO,
	                );
	                $this->db->where('id', intval($dataFrom[1]));
	                $this->db->update('itemplan_pep2_grafo', $data);
	                /* if($this->db->affected_rows() != 1) {
	                 //throw new Exception($this->db->);
	                 throw new Exception('Hubo un error al actualizar en pep2_grafo itemplan');
	                }*/
	            } else if ($dataFrom[0] == DATA_FROM_SISEGO_PEP2_GRAFO) {
	                $data = array(
	                    "estado" => ESTADO_CON_GRAFO_ULTILIZADO,
	                );
	                $this->db->where('id', intval($dataFrom[1]));
	                $this->db->update('sisego_pep2_grafo', $data);
	                /*if($this->db->affected_rows() != 1) {
	                 //throw new Exception($this->db->);
	                 throw new Exception('Hubo un error al actualizar en pep2_grafo sisego');
	                }*/
	            } else if ($dataFrom[0] == DATA_FROM_PEP2_GRAFO) {
	                $data = array(
	                    "estado" => ESTADO_CON_GRAFO_ULTILIZADO,
	                );
	                $this->db->where('id', intval($dataFrom[1]));
	                $this->db->update('pep2_grafo', $data);
	                if ($this->db->affected_rows() != 1) {
	                    //throw new Exception($this->db->);
	                    $this->db->trans_rollback();
	                    throw new Exception('Hubo un error al actualizar en pep2_grafo');
	                }
	            }
	
	                $arrayInsertLog = array(
	                    "codigo_po" => $ptr,
	                    "itemplan" => $itemplan,
	                    "idUsuario" => ID_USUARIO_RAP_SAP,//rpa sap user id tabla usuario.
	                    "fecha_registro" => $this->fechaActual(),
	                    "idPoestado" => 3,//APROBADO
	                    "controlador" => 'C_integracion RPA SAP'
	                );
	                $this->db->insert('log_planobra_po', $arrayInsertLog);
	                if ($this->db->affected_rows() != 1) {
	                    $this->db->trans_rollback();
	                    throw new Exception('Error al insertar tabla log_planobra_po');
	                } else {
	                    $count = $this->countMatByPo($ptr);
	                    if($count == 1) { //SI ES MATERIAL
	                        $this->db->where('itemplan'     , $itemplan);
	                        $this->db->where('codigo_po'    , $ptr);
	                        $this->db->where('flg_tipo_area', 1);
	                        $this->db->where('estado_po'    , 3);
	                        $this->db->update('planobra_po', array('estado_po' => 4));
	
	                        if($this->db->affected_rows() < 1) {
	                            $this->db->trans_rollback();
	                            throw new Exception('Error al liquidar PO');
	                        } else {
	                            $arrayLog = array(
	                                "codigo_po"      => $ptr,
	                                "itemplan"       => $itemplan,
	                                "idUsuario"      => ID_USUARIO_RAP_SAP,
	                                "fecha_registro" => $this->fechaActual(),
	                                "idPoestado"     => 4,//liquidado
	                                "controlador"    => 'C_integracion RPA SAP'
	                            );
	                            $this->db->insert('log_planobra_po', $arrayLog);
	                            if ($this->db->affected_rows() != 1) {
	                                $this->db->trans_rollback();
	                                throw new Exception('Error al insertar tabla log_planobra_po');
	                            } else {
	                                $this->db->trans_commit();
	                                $data['error'] = EXIT_SUCCESS;
	                                $data['msj'] = 'Se actualiz&oacute; correctamente!!';
	                            }
	                        }
	                    } else {
	                        $this->db->trans_commit();
	                        $data['error'] = EXIT_SUCCESS;
	                        $data['msj'] = 'Se actualiz&oacute; correctamente!!';
	                    }
	                }
	         
	
	        }
	
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	
	    return $data;
	}
	
	public function fechaActual()
	{
	    $zonahoraria = date_default_timezone_get();
	    ini_set('date.timezone', 'America/Lima');
	    setlocale(LC_TIME, "es_ES", "esp");
	    $hoy = strftime("%Y-%m-%d %H:%M:%S");
	    return $hoy;
	}
	
	function countMatByPo($po) {
	    $sql = "SELECT 1 as flg
                  FROM planobra_po ppo,
                       itemplanestacionavance i
                 WHERE ppo.codigo_po = ?
                   AND ppo.flg_tipo_area = 1
                   AND ppo.itemplan   = i.itemplan
                   AND ppo.idEstacion = i.idEstacion
                   AND i.porcentaje   = 100";
	    $result = $this->db->query($sql,array($po));
	    return $result->row_array()['flg'];
	}
	
	function getGrafoFromWU($ptr, $itemplan) {
	    $sql = "SELECT grafo_from 
                from web_unificada_det 
    	        where ptr = ? 
    	        and itemplan = ?";
	    $result = $this->db->query($sql,array($ptr, $itemplan));
	    return $result->row_array()['grafo_from'];
	}
	
	function getGrafoFromPO($ptr, $itemplan) {
	    $sql = "SELECT grafo_from 
	           from planobra_po
    	        where codigo_po = ?
    	        and itemplan = ?";
	    $result = $this->db->query($sql,array($ptr, $itemplan));
	    return $result->row_array()['grafo_from'];
	}
	
	public function estacionEjecutadaDiseno($itemplan, $estacion)
	{
	    $query = " SELECT COUNT(1) as cont
                 FROM   pre_diseno
                 WHERE  itemplan = ?
                 AND    idEstacion = ?
                 AND    estado = " . ESTADO_ESTACION_DISENO_EJECUTADO;
	    $result = $this->db->query($query, array($itemplan, $estacion));
	    return $result->row()->cont;
	}
	
	public function changeEstadoEnObraPlan($itemplan, $estadoPlan)
	{
	    $data['error'] = EXIT_ERROR;
	    $data['msj'] = null;
	    try {
	
	        $this->db->trans_begin();
	        $dataUpdate = array(
	            'idEstadoPlan' 	=> $estadoPlan,
				'fecha_upd' 	=> $this->fechaActual(),
				'usu_upd' 		=> ID_USUARIO_RAP_SAP,
				'descripcion' 	=> 'GENERACION VALE RESERVA'
	        );
	        $this->db->where('itemplan', $itemplan);
	        $this->db->update('planobra', $dataUpdate);
	        if ($this->db->trans_status() === false) {
	            throw new Exception('Hubo un error al actualizar el estadoplan.');
	        } else {
	
	            $dataUpdateLog = array(
	                'tabla'     => 'planobra',
	                'actividad' => 'update',
	                'itemplan'  => $itemplan,
	                'itemplan_default' => 'idEstadoPlan=' . $estadoPlan . '|FROM_RPA_ROBOT',
	                'fecha_registro' => $this->fechaActual(),
	                'id_usuario' => ID_USUARIO_RAP_SAP,
	                'idEstadoPlan' => $estadoPlan
	            );
	
	            $this->db->insert('log_planobra', $dataUpdateLog);
	            if ($this->db->affected_rows() != 1) {
	                $this->db->trans_rollback();
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
	
	function getEmplazamientoIdSiomByidCentral($idCentral) {
	    $sql = "SELECT count(1) as cant, sn.empl_id
                FROM siom_nodo sn JOIN central c ON sn.empl_nemonico = c.codigo
                and idCentral = ?";
	    $result = $this->db->query($sql,array($idCentral));
	    return $result->row_array();
	}
	
	function insertLogTramaSiomSoloLog($dataLogSiom) {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $this->db->insert('log_tramas_siom', $dataLogSiom);
	        if($this->db->affected_rows() != 1) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar en log_tramas_siom');
	        }else{
	            $data['error'] = EXIT_SUCCESS;
	            $data['msj'] = 'Se actualizo correctamente!';
	            $this->db->trans_commit();
	        }
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
	
	function insertSiom($dataSiom, $dataLogPo, $dataEstado) {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $this->db->insert('siom_obra', $dataSiom);
	        if($this->db->affected_rows() != 1) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar en siom_obra');
	        }else{
	            $this->db->insert('log_planobra', $dataLogPo);
	            if($this->db->affected_rows() != 1) {
	                $this->db->trans_rollback();
	                throw new Exception('Error al insertar en log_planobra');
	            }else{
	                $this->db->insert('log_tramas_estados_siom', $dataEstado);
	                if($this->db->affected_rows() != 1) {
	                    $this->db->trans_rollback();
	                    throw new Exception('Error al insertar en log_tramas_estados_siom');
	                }else{
	                    $data['error'] = EXIT_SUCCESS;
	                    $data['msj'] = 'Se actualizo correctamente!';
	                    $this->db->trans_commit();
	                }
	            }
	        }
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
	
	function insertLogTramaSiom($dataLogSiom, $dataSiom) {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->trans_begin();
            $this->db->insert('log_tramas_siom', $dataLogSiom);
            if($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar en log_tramas_siom');
            }else{                
                $this->db->insert('siom_obra', $dataSiom);
                if($this->db->affected_rows() != 1) {
                    $this->db->trans_rollback();
                    throw new Exception('Error al insertar en siom_obra');
                }else{
                    $data['error'] = EXIT_SUCCESS;
                    $data['msj'] = 'Se actualizo correctamente!';
                    $this->db->trans_commit();                    
                }
            }
        }catch(Exception $e){
            $data['msj']   = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
	
	 /**metodos de ws vale reserva **/
    
    public function getVRToRpaList()
    {
        $sql = "SELECT  svr.itemplan, svr.ptr, svr.codigo, ppo.vale_reserva
                FROM    solicitud_vale_reserva svr, planobra_po ppo
                WHERE   svr.ptr = ppo.codigo_po
                #AND		(svr.flg_estado IS NULL OR svr.flg_estado = 0)
				AND		svr.flg_estado IS NULL
				AND     svr.flg_tipo_solicitud not in (2,3)
				AND 	svr.send_rpa = 1
                GROUP BY svr.itemplan, svr.ptr, svr.codigo, ppo.vale_reserva";
        $result = $this->db->query($sql, array());
        return $result->result();
    }
    
    public function getSolicitudToSap($itemplan, $codigoPO, $codigo){
        $sql = "SELECT  svr.ptr codigo_po, svr.material, svr.cantidadInicio, svr.flg_tipo_solicitud,
						jse.codCentro as centro,
						'L' AS t,
						jse.codAlmacen as almacen,
                        DATE_FORMAT(CURDATE(), '%d.%m.%Y')  AS fecha,
                        ppo.pep1, ppo.pep2, ppo.grafo
				   FROM (
				        planobra_po ppo,
                        planobra po,
                        central c,
                        solicitud_vale_reserva svr
						)
						LEFT JOIN
						(
						jefatura_sap jsap,
						jefatura_sap_x_empresacolab jse) ON (jse.idEmpresaColab = ppo.id_eecc_reg
						 AND jsap.idJefatura = jse.idJefatura
						 AND (CASE WHEN  (jsap.idZonal IS NULL OR jsap.idZonal = '') THEN c.jefatura = jsap.descripcion ELSE jsap.idZonal = c.idZonal END )
						 )
    
				WHERE  ppo.itemplan = po.itemplan
					AND po.idCentral = c.idCentral
					AND ppo.itemplan = svr.itemplan
					AND ppo.codigo_po = svr.ptr
					AND	(svr.flg_estado is null OR svr.flg_estado = 0)
					AND svr.flg_tipo_solicitud not in (2,3)
					AND svr.send_rpa = 1
					AND (po.paquetizado_fg IS NULL OR po.paquetizado_fg = 0 OR po.paquetizado_fg = 1)
                    AND CASE WHEN svr.flg_tipo_solicitud = 4 THEN ppo.pep1 IS NOT NULL
                             ELSE svr.pep1 IS NULL END
					AND svr.itemplan = ?	
					AND svr.ptr = ?
					AND svr.codigo = ?";
        $result = $this->db->query($sql, array($itemplan, $codigoPO, $codigo));
        return $result->result();
    }
    
    public function getSolicitudToSapPqt($itemplan, $codigoP, $codigo){
        $sql = "SELECT  svr.ptr as codigo_po, svr.material, svr.cantidadInicio, svr.flg_tipo_solicitud,
						jse.codCentro as centro,
						'L' AS t,
						jse.codAlmacen as almacen,
                        DATE_FORMAT(CURDATE(), '%d.%m.%Y')  AS fecha,
                        ppo.pep1, ppo.pep2, ppo.grafo
				   FROM (
				        planobra_po ppo,
                        planobra po,
                        pqt_central c,
                        solicitud_vale_reserva svr
						)
						LEFT JOIN
						(
						jefatura_sap jsap,
						jefatura_sap_x_empresacolab jse) ON (jse.idEmpresaColab = ppo.id_eecc_reg
						 AND jsap.idJefatura = jse.idJefatura
						 AND (CASE WHEN  (jsap.idZonal IS NULL OR jsap.idZonal = '') THEN c.jefatura = jsap.descripcion ELSE jsap.idZonal = c.idZonal END )
						 )
    
				WHERE  ppo.itemplan = po.itemplan
					AND po.idCentralPqt = c.idCentral
					AND ppo.itemplan = svr.itemplan
					AND ppo.codigo_po = svr.ptr
                    AND	(svr.flg_estado is null OR svr.flg_estado = 0)
					AND svr.flg_tipo_solicitud not in (2,3)
					AND svr.send_rpa = 1
					AND po.paquetizado_fg IN (1,2)
                    AND CASE WHEN svr.flg_tipo_solicitud = 4 THEN ppo.pep1 IS NOT NULL
                             ELSE svr.pep1 IS NULL END
					AND svr.itemplan = ?
					AND svr.ptr = ?	
					AND svr.codigo = ?";
        $result = $this->db->query($sql, array($itemplan, $codigoP, $codigo));
        return $result->result();
    }
	
	function tramaOkRpaVR($dataEstadoInsertLog){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $this->db->insert('log_tramas_rpa_sap_vr', $dataEstadoInsertLog);
	        if($this->db->affected_rows() != 1) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar en log_tramas_rpa_sap');
	        }else{
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se actualizo correctamente!';
                $this->db->trans_commit();
	        }
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}

	function ingresarDetallePoRpa($arrayDetallePo, $arrayUpdateSolicitud, $updateDetallePo, $arrayLogVr) {

        if(count($arrayDetallePo) > 0) {
            $this->db->insert_batch('planobra_po_detalle', $arrayDetallePo);

            if ($this->db->trans_status() === FALSE) {
                $data['msj']   = 'error al ingresar el detalle PO';
                $data['error'] = EXIT_ERROR;
                return $data;
            }else{
				$this->db->update_batch('solicitud_vale_reserva', $arrayUpdateSolicitud, 'idSolicitudValeReserva');
        
				if($this->db->trans_status() === FALSE) {
					//$this->db->trans_rollback();
					$data['msj']   = 'error no se valido';
					$data['error'] = EXIT_ERROR;
					return $data;
				}else{
					$this->db->insert_batch('log_solicitud_vr', $arrayLogVr);
					  
					if($this->db->trans_status() === FALSE) {
							//$this->db->trans_rollback();
							$data['msj']   = 'error no ingreso log';
							$data['error'] = EXIT_ERROR;
							return $data;
					}else{
						//$this->db->trans_commit();
						$data['error'] = EXIT_SUCCESS;
						return $data;
					}
				}
            }
        }  

        if(count($updateDetallePo) > 0) {
            foreach($updateDetallePo as $row) {
                $this->db->where('codigo_po'      , $row['codigo_po']);
                $this->db->where('codigo_material', $row['codigo_material']);
                $this->db->update('planobra_po_detalle', array('cantidad_final' => $row['cantidad_final']));

                if ($this->db->trans_status() === FALSE) {
                    $data['error'] = EXIT_ERROR;
                    return $data;
                 } else {
					$this->db->update_batch('solicitud_vale_reserva', $arrayUpdateSolicitud, 'idSolicitudValeReserva');
        
					if($this->db->trans_status() === FALSE) {
						//$this->db->trans_rollback();
						$data['msj']   = 'error no se valido';
						$data['error'] = EXIT_ERROR;
						return $data;
					}else{
						$this->db->insert_batch('log_solicitud_vr', $arrayLogVr);
						  
						if($this->db->trans_status() === FALSE) {
								//$this->db->trans_rollback();
								$data['msj']   = 'error no ingreso log';
								$data['error'] = EXIT_ERROR;
								return $data;
						}else{
							//$this->db->trans_commit();
							$data['error'] = EXIT_SUCCESS;
							return $data;
						}
					}
				 }
            }
        }
        $data['error'] = EXIT_SUCCESS;
        return $data;
	}
	
	function insertLogVr($arrayLogVr) {
		$this->db->insert('log_solicitud_vr', $arrayLogVr);
					  
		if($this->db->trans_status() === FALSE) {
			$data['msj']   = 'error no ingreso log Individual';
			$data['error'] = EXIT_ERROR;
			return $data;
		}else{
			$data['error'] = EXIT_SUCCESS;
			return $data;
		}
	}
	
	function validSendTramaSisego($codigo_solicitud, $material) {
		$sql = " SELECT * 
		           FROM solicitud_vale_reserva 
				  WHERE codigo   = ?
				    AND material = ?
					AND (comentario IS NULL OR comentario = '')";
		$result = $this->db->query($sql, array($codigo_solicitud, $material));
		return $result->row_array();
	}

	function getExistPoRpa($idMaterial, $codigo_po) {
        $sql = "SELECT 1 flgExist
                  FROM planobra_po_detalle
                 WHERE codigo_material = ?
                   AND codigo_po       = ?";
        $result = $this->db->query($sql, array($idMaterial, $codigo_po));
        return $result->row_array()['flgExist'];           
	}
	
	function updateTotalPoRpa($po, $vrRobot) {
        $sql = "UPDATE planobra_po ppo,
                       (SELECT pd.codigo_po, ROUND(SUM(ma.costo_material*pd.cantidad_final),2) total
                          FROM material ma, 
                               planobra_po_detalle pd 
                         WHERE pd.codigo_material = ma.id_material 
                        GROUP BY codigo_po)t
                   SET ppo.costo_total = t.total,
				       vr_robot        = ?
                 WHERE t.codigo_po = ppo.codigo_po 
                   AND t.codigo_po = ?";
        $this->db->query($sql, array($vrRobot, $po));    
 
        if($this->db->trans_status() === FALSE) {
            return array('error' => EXIT_ERROR, 'msj' => 'error no se actualiz&oacute; el total');
        }else{
            return array('error' => EXIT_SUCCESS);
        }           
    }
	   
	function getCountFechaPoAprob($po) {
		$sql = "SELECT COUNT(1)count
				  FROM log_planobra_po 
				 WHERE idPoestado = 3
				   AND DATE(fecha_registro) >= '2020-01-01'
				   AND codigo_po = ?";
		$result = $this->db->query($sql, array($po));
		return $result->row_array()['count'];
	}
	
	function getCountPepBianual($pep) {
		$sql = "SELECT COUNT(1) count
				 FROM pep_bianual
				WHERE pep = ?
				  AND estado = 1";
		$result = $this->db->query($sql, array($pep));
		return $result->row_array()['count'];
	}
	
	function getDataJson() {
		$error = '%error": 0%';
		$sql = "SELECT dataGet
				  FROM log_tramas_rpa_sap_vr 
				 WHERE exception = 'No enviaron materiales'
				   AND dataGet like '".$error."'";
		$result = $this->db->query($sql);
		return $result->result_array();
	}
}