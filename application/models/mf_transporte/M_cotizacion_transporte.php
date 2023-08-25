<?php
class M_cotizacion_transporte extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
	function getPtrConsulta($itemPlan,$nombreproyecto,$nodo,$zonal,$proy,$subPry,$estado,$filtroPrevEjec,$tipoPlanta,$ideecc, $idUsuario=null, $mesEjec=null, $idFase=null){
		
		//*p.fechaEjecucion,**//
		
		$Query = " SELECT (CASE WHEN py.idProyecto = ".ID_PROYECTO_SISEGOS." THEN (SELECT grafo from sisego_pep2_grafo where sisego = p.indicador LIMIT 1) ELSE null END) as grafo, 
						   py.idProyecto, 
						   p.indicador, 
						   p.itemPlan, 
						   c.idCentral, 
						   p.idSubProyecto, 
						   s.subProyectoDesc, 
						   p.nombreProyecto,
						   (CASE WHEN s.idTipoSubProyecto = 2 THEN ( SELECT empresaColabDesc 
																	   FROM empresacolab 
																	  WHERE idEmpresaColab = c.idEmpresaColabCV) ELSE b.empresaColabDesc  END) as empresaColabDesc ,
						    f.faseDesc,
							c.codigo, 
							c.tipoCentralDesc, 
							e.empresaElecDesc, 
							p.fechaInicio, 
							p.fechaPrevEjec, 
							
							(case when (not p.fechaEjecucion is null and p.fechaEjecucion<'2018-07-07') then p.fechaEjecucion
				 else  date(p.fechaPreliquidacion) end) as fechaEjecucion,
							 
							c.idZonal, 
							z.zonalDesc, 
							p.idEstadoPlan, 
							t.estadoPlanDesc, 
							p.hasAdelanto, 
							s.idTipoPlanta,
							(SELECT 1 
							   FROM siom_obra 
							  WHERE itemplan = p.itemplan limit 1) flg_bandeja_siom,
							s.idTipoSubProyecto,
							p.ubic_tss_cv,
							p.ubic_exped_cv,
							p.comentario_cv
					   FROM planobra_transporte p,
					        fase f,
							subproyecto s,
							proyecto py, 
							empresaelec e, 
							pqt_central c 
							RIGHT JOIN zonal z 
							ON c.idZonal=z.idZonal 
							RIGHT JOIN empresacolab b on c.idEmpresaColab = b.idEmpresaColab, 
							estadoplan t
					  WHERE s.idSubProyecto = p.idSubProyecto 
					    AND p.idFase = f.idFase 
					    AND c.idCentral     = p.idCentralPqt 
						AND e.idEmpresaElec = p.idEmpresaElec 
						AND p.idEstadoPlan  = t.idEstadoPlan 
						AND s.idProyecto    = py.idProyecto
						AND CASE WHEN ".$ideecc." = 0 OR ".$ideecc." = 6 THEN c.idEmpresaColab = c.idEmpresaColab
						         WHEN c.idEmpresaColabFuente = ".$ideecc." THEN  c.idEmpresaColabFuente = ".$ideecc."
								WHEN ".$ideecc." IN (SELECT idSubProyecto 
														FROM subproyecto 
													WHERE idProyecto = 5)
								THEN  c.idEmpresaColabCV =  ".$ideecc."               
								 ELSE c.idEmpresaColab = ".$ideecc." END" ;        
		$result = $this->db->query($Query,array());
	    return $result;
	}
	
	function getPtrConsultaNoPqt($itemPlan,$nombreproyecto,$nodo,$zonal,$proy,$subPry,$estado,$filtroPrevEjec,$tipoPlanta,$ideecc, $idUsuario=null, $mesEjec=null, $idFase=null){
		$Query = "  SELECT (CASE WHEN py.idProyecto = ".ID_PROYECTO_SISEGOS." THEN (SELECT grafo from sisego_pep2_grafo where sisego = p.indicador LIMIT 1) ELSE null END) as grafo, 
						   py.idProyecto, 
						   p.indicador, 
						   p.itemPlan, 
						   c.idCentral, 
						   p.idSubProyecto, 
						   s.subProyectoDesc, 
						   p.nombreProyecto,
						   (CASE WHEN s.idTipoSubProyecto = 2 THEN ( SELECT empresaColabDesc 
																	   FROM empresacolab 
																	  WHERE idEmpresaColab = c.idEmpresaColabCV) ELSE b.empresaColabDesc  END) as empresaColabDesc ,
						    f.faseDesc,
							c.codigo, 
							c.tipoCentralDesc, 
							e.empresaElecDesc, 
							p.fechaInicio, 
							p.fechaPrevEjec, 
							
							(case when (not p.fechaEjecucion is null and p.fechaEjecucion<'2018-07-07') then p.fechaEjecucion
				 else  date(p.fechaPreliquidacion) end) as fechaEjecucion,
							 
							c.idZonal, 
							z.zonalDesc, 
							p.idEstadoPlan, 
							t.estadoPlanDesc, 
							p.hasAdelanto, 
							s.idTipoPlanta,
							(SELECT 1 
							   FROM siom_obra 
							  WHERE itemplan = p.itemplan limit 1) flg_bandeja_siom,
							s.idTipoSubProyecto,
							p.ubic_tss_cv,
							p.ubic_exped_cv,
							p.comentario_cv,
							p.solicitud_oc,
							p.estado_sol_oc,
							p.orden_compra_sol_oc
					   FROM planobra_transporte p,
					        fase f,
							subproyecto s,
							proyecto py, 
							empresaelec e, 
							central c 
							RIGHT JOIN zonal z 
							ON c.idZonal=z.idZonal 
							RIGHT JOIN empresacolab b on c.idEmpresaColab = b.idEmpresaColab, 
							estadoplan t
					  WHERE s.idSubProyecto = p.idSubProyecto 
					    AND p.idFase = f.idFase 
					    AND c.idCentral     = p.idCentral 
						AND e.idEmpresaElec = p.idEmpresaElec 
						AND p.idEstadoPlan  = t.idEstadoPlan 
						AND s.idProyecto    = py.idProyecto
						AND (p.paquetizado_fg is null or p.paquetizado_fg = 1)
						AND CASE WHEN ".$ideecc." = 0 OR ".$ideecc." = 6 THEN c.idEmpresaColab = c.idEmpresaColab
						         WHEN c.idEmpresaColabFuente = ".$ideecc." THEN  c.idEmpresaColabFuente = ".$ideecc."
								WHEN ".$ideecc." IN (SELECT idSubProyecto 
														FROM subproyecto 
													WHERE idProyecto = 5)
								THEN  c.idEmpresaColabCV =  ".$ideecc."               
								 ELSE c.idEmpresaColab = ".$ideecc." END" ;
		if($zonal!=''){
	            if($zonal == 8 || $zonal == 9 || $zonal == 10 || $zonal == 11 || $zonal == 12 ){
	                $Query .= " AND z.idZonal IN (8,9,10,11,12)";
	            }else{
	                $Query .= " AND z.idZonal IN (".$zonal.")";
	            }
        }
		if($proy!=''){
            $Query .= " AND py.idProyecto = ".$proy;
        }
        if($subPry!=''){
            $Query .= " AND s.idSubProyecto = ".$subPry;
        }
		if($itemPlan!=''){
            $Query .= " AND p.itemPlan ='".$itemPlan."' ";
        }
        if($nombreproyecto!=''){
            $Query .= ' AND p.nombreProyecto LIKE "%'.$nombreproyecto.'%"';
            //$Query .= " AND p.nombreProyecto LIKE '%".$nombreproyecto."%' ";
        }
        if($nodo!=''){
            $Query .= " AND c.idCentral = ".$nodo;
        }
        if($estado!=''){
            $Query .= " AND p.idEstadoPlan = ".$estado;
        }        
        if($tipoPlanta!=''){
            $Query .= " AND s.idTipoPlanta = ".$tipoPlanta;
        } 
        if($filtroPrevEjec!=''){
            $Query .= " ".$filtroPrevEjec." ";
		}
		if($mesEjec!=NULL){
            $Query .=  " AND EXTRACT(MONTH FROM DATE(p.fechaPrevEjec)) = $mesEjec";
		}
		if($idUsuario!=NULL) {
			$Query .=  " AND p.itemplan IN ( SELECT l.itemplan
											   FROM log_planobra_transporte l
											  WHERE l.tipoPlanta = ".ID_TIPO_PLANTA_INTERNA."
												AND l.actividad  = 'ingresar'  
												AND l.id_usuario = '".$idUsuario."'
												GROUP BY l.itemplan)";
		}
		if($idFase!=''){
            $Query .= " AND p.idFase = ".$idFase;
        }
		$result = $this->db->query($Query,array());	
	    return $result;
	}
	
	function insertExpediente($comentario, $usuario){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        //$this->db->query("INSERT INTO expediente (id_expediente, comentario, usuario, fecha_registro) VALUES (NULL, '".$comentario."', '".$usuario."', NOW())");
	        $this->db->query("INSERT INTO expediente (id_expediente, comentario, usuario, fecha_registro) VALUES (NULL, '".$comentario."', '".$usuario."', NOW() )");
	        if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();	                
                $data ['error']= EXIT_SUCCESS;
            }else{
                $this->db->trans_rollback();
            }	
	        
	         
	    }catch(Exception $e){
	        $data['msj']   = 'Error en la transaccion!';
	        $this->db->trans_rollback();
	    }
	    return $data;
	}

	function insertPTR($ptr, $item, $fecsol,$subproyecto,$zonal,$eecc,$area){ 
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        //(SELECT SEC_TO_TIME(TIMESTAMPDIFF(SECOND, STR_TO_DATE('14/12/2017 15:38', '%d/%m/%Y %H:%i:%s'), NOW())))
	        $this->db->trans_begin();
	        $this->db->query("INSERT INTO expediente_ptr (id_expediente, ptr, itemPlan, tpo_espera, subproyecto, zonal, eecc, area) VALUES ((SELECT id_expediente FROM expediente ORDER BY id_expediente DESC LIMIT 1), '".$ptr."', '".$item."',  (SELECT SEC_TO_TIME(TIMESTAMPDIFF(SECOND, STR_TO_DATE('".$fecsol."', '%d/%m/%Y %H:%i:%s'), NOW()))), '".$subproyecto."', '".$zonal."','".$eecc."','".$area."' )");
	        if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();	                
                $data ['error']= EXIT_SUCCESS;
            }else{
                $this->db->trans_rollback();
            }	
	        
	         
	    }catch(Exception $e){
	        $data['msj']   = 'Error en la transaccion!';
	        $this->db->trans_rollback();
	    }
	    return $data;
	}

	function getAllZonalGroup($zonasRest){
		$Query = "SELECT SUBSTRING_INDEX( zonalDesc , ' ', 1 ) as zonalDesc FROM zonal GROUP BY SUBSTRING_INDEX( zonalDesc , ' ', 1 ) ORDER BY zonalDesc" ;
	    $result = $this->db->query($Query,array());
	    return $result;
	}
	function getAllZonalIndex($zonasRest){
		$Query = "SELECT idZonal, SUBSTRING_INDEX( zonalDesc , ' ', 1 ) as zona 
				FROM zonal " ;
		if($zonasRest!=''&&$zonasRest!=','){
            $Query .= " WHERE zonal.idzonal IN (".$zonasRest.")";
        }

        $Query .= "  GROUP BY (zona)";


	    $result = $this->db->query($Query,array());
	    return $result;
	}

function   getPtrConsultaDiseno($itemPlan,$nombreproyecto,$nodo,$zonal,$proy,$subPry,$estado,$filtroPrevEjec,$tipoPlanta){
	    
	    $Query = "  SELECT p.itemPlan, c.idCentral, p.idSubProyecto, s.subProyectoDesc, p.nombreProyecto, b.empresaColabDesc , c.codigo, c.tipoCentralDesc, e.empresaElecDesc, p.fechaInicio, p.fechaPrevEjec, p.fechaEjecucion, c.idZonal, z.zonalDesc, p.idEstadoPlan, t.estadoPlanDesc, p.hasAdelanto, s.idTipoPlanta, 'NO' AS desc_paquetizado,p.paquetizado_fg
					FROM planobra_transporte p, subproyecto s,proyecto py, empresaelec e, central c
					RIGHT JOIN zonal z
					ON c.idZonal=z.idZonal
					RIGHT JOIN empresacolab b on c.idEmpresaColab=b.idEmpresaColab, estadoplan t
					WHERE s.idSubProyecto = p.idSubProyecto and c.idCentral= p.idCentral
					AND e.idEmpresaElec= p.idEmpresaElec and p.idEstadoPlan = t.idEstadoPlan and s.idProyecto=py.idProyecto
					AND (p.paquetizado_fg is null or p.paquetizado_fg = 1) " ;
	    if($zonal!=''){
	        if($zonal == 8 || $zonal == 9 || $zonal == 10 || $zonal == 11 || $zonal == 12 ){
	            $Query .= " AND z.idZonal IN (8,9,10,11,12)";
	        }else{
	            $Query .= " AND z.idZonal IN (".$zonal.")";
	        }
	    }
	    if($proy!=''){
	        $Query .= " AND py.idProyecto = ".$proy;
	    }
	    if($subPry!=''){
	        $Query .= " AND s.idSubProyecto = ".$subPry;
	    }
	    if($itemPlan!=''){
	        $Query .= " AND p.itemPlan ='".$itemPlan."' ";
	    }
	    if($nombreproyecto!=''){
	        $Query .= ' AND p.nombreProyecto LIKE "%'.$nombreproyecto.'%"';
	        //$Query .= " AND p.nombreProyecto LIKE '%".$nombreproyecto."%' ";
	    }
	    if($nodo!=''){
	        $Query .= " AND c.idCentral = ".$nodo;
	    }
	    if($estado!=''){
	        $Query .= " AND p.idEstadoPlan = ".$estado;
	    }
	    if($tipoPlanta!=''){
	        $Query .= " AND s.idTipoPlanta = ".$tipoPlanta;
	    }
	    if($filtroPrevEjec!=''){
	        $Query .= " ".$filtroPrevEjec." ";
	    }
		$Query .= " AND p.idEstadoPlan IN  (".ID_ESTADO_DISENIO.",".ID_ESTADO_DISENIO_EJECUTADO.",".ID_ESTADO_DISENIO_PARCIAL.") ";
		
		$Query .= "UNION ALL 
		           SELECT p.itemPlan, c.idCentral, p.idSubProyecto, s.subProyectoDesc, p.nombreProyecto, b.empresaColabDesc , c.codigo, c.tipoCentralDesc, e.empresaElecDesc, p.fechaInicio, p.fechaPrevEjec, p.fechaEjecucion, c.idZonal, z.zonalDesc, p.idEstadoPlan, t.estadoPlanDesc, p.hasAdelanto, s.idTipoPlanta, 'SI' AS desc_paquetizado, p.paquetizado_fg
					 FROM planobra_transporte p, subproyecto s,proyecto py, empresaelec e, pqt_central c
					      RIGHT JOIN zonal        z ON c.idZonal        = z.idZonal
						  RIGHT JOIN empresacolab b ON c.idEmpresaColab = b.idEmpresaColab, 
						             estadoplan   t
					WHERE s.idSubProyecto  = p.idSubProyecto and c.idCentral= p.idCentralPqt
					  AND e.idEmpresaElec  = p.idEmpresaElec and p.idEstadoPlan = t.idEstadoPlan and s.idProyecto=py.idProyecto
					  AND p.paquetizado_fg = 2";
		if($zonal!=''){
			if($zonal == 8 || $zonal == 9 || $zonal == 10 || $zonal == 11 || $zonal == 12 ){
				$Query .= " AND z.idZonal IN (8,9,10,11,12)";
			}else{
				$Query .= " AND z.idZonal IN (".$zonal.")";
			}
		}
		if($proy!=''){
			$Query .= " AND py.idProyecto = ".$proy;
		}
		if($subPry!=''){
			$Query .= " AND s.idSubProyecto = ".$subPry;
		}
		if($itemPlan!=''){
			$Query .= " AND p.itemPlan ='".$itemPlan."' ";
		}
		if($nombreproyecto!=''){
			$Query .= ' AND p.nombreProyecto LIKE "%'.$nombreproyecto.'%"';
			//$Query .= " AND p.nombreProyecto LIKE '%".$nombreproyecto."%' ";
		}
		if($nodo!=''){
			$Query .= " AND c.idCentralPqt = ".$nodo;
		}
		if($estado!=''){
			$Query .= " AND p.idEstadoPlan = ".$estado;
		}
		if($tipoPlanta!=''){
			$Query .= " AND s.idTipoPlanta = ".$tipoPlanta;
		}
		if($filtroPrevEjec!=''){
			$Query .= " ".$filtroPrevEjec." ";
		}
		$Query .= " AND p.idEstadoPlan IN  (".ID_ESTADO_DISENIO.",".ID_ESTADO_EN_LICENCIA.",".ID_ESTADO_EN_APROBACION.") ";
	    $result = $this->db->query($Query,array());
	    return $result;
	}
	
	function changeEstadoPlanConsultaDiseno($itemplan, $estadoPlan, $flg_paquetizado){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        
			$this->db->trans_begin();
			
			$dataUpdate = array('idEstadoPlan'   => $estadoPlan,
								'flg_update_adm' => 1,
								'descripcion'    => 'LIQUIDACION ADMINISTRATIVA',
								'usu_upd'        => $this->session->userdata('usernameSession'),
								'fecha_upd'      => $this->m_utils->fechaActual()
							);
	        $this->db->where('itemplan', $itemplan);
	        $this->db->update('planobra_transporte',$dataUpdate);
	        if ($this->db->trans_status() === FALSE) {
	            throw new Exception('Hubo un error al actualizar el estadoplan.');
	        }else{
	            if($flg_paquetizado == 2) {
					if($estadoPlan == ID_ESTADO_EN_LICENCIA) {
						$sql = "DELETE ie,ppo,dp,ppod
								  FROM itemplan_estacion_licencia_det ie 
							 LEFT JOIN (planobra_transporte_po ppo, 
										detalleplan dp, 
										planobra_transporte_po_detalle_partida ppod)
									ON (ppo.itemplan = ie.itemplan 
										AND ppo.idEstacion = 20 
										AND ppo.estado_po NOT IN (5,6)
										AND dp.poCod = ppo.codigo_po
										AND ppod.codigo_po = ppo.codigo_po)
								  WHERE ie.itemplan = ?";
						$result = $this->db->query($sql, array($itemplan));

						if ($this->db->trans_status() === TRUE) {
							$sql2 = "UPDATE pre_diseno 
									   SET requiere_licencia = 2 
									 WHERE itemplan = ?";
							$result = $this->db->query($sql2, array($itemplan));
							
							if ($this->db->trans_status() === TRUE) {
								$data['error']    = EXIT_SUCCESS;
								$this->db->trans_commit();
							}else {
								$this->db->trans_rollback();
							}
							
						} else {
							$this->db->trans_rollback();
						}
					} else {
						$data['error'] = EXIT_SUCCESS;
						$this->db->trans_commit();
					}
					
				
					
					/*$dataUpdateLog= array(
						'itemPlan'      => $itemplan,
						'idEstadoPlan'  => $estadoPlan,
						'descripcion'   => 'LIQUIDACION ADMINISTRATIVA',
						'usu_upd'       => $this->session->userdata('usernameSession'),
						'fecha_upd'     => $this->m_utils->fechaActual()
					);
	            
					$this->db->insert('control_estado_itemplan',$dataUpdateLog);
					if ($this->db->affected_rows() != 1) {
						$this->db->trans_rollback();
						throw new Exception('Hubo un error al actualizar el estadoplan.');
					}else{
						$data['error']    = EXIT_SUCCESS;
						$data['msj']      = 'Se actualizo correctamente!';
						$this->db->trans_commit();
					}*/
				} else {
					$dataUpdateLog= array(
						'tabla' => 'planobra_transporte',
						'actividad' => 'update - Administrativa',
						'itemplan' => $itemplan,
						'itemplan_default' => 'idEstadoPlan='.$estadoPlan.'|FROM_CONSULTA_DISENIO',
						'fecha_registro' => date("Y-m-d h:m:s"),
						'id_usuario' =>  $this->session->userdata('idPersonaSession')
					);
					
					$this->db->insert('log_planobra_transporte',$dataUpdateLog);
					if ($this->db->affected_rows() != 1) {
						$this->db->trans_rollback();
						throw new Exception('Hubo un error al actualizar el estadoplan.');
					}else{
						$data['error']    = EXIT_SUCCESS;
						$data['msj']      = 'Se actualizo correctamente!';
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
	
	function actualizarCodigoSiom($itemplan, $idEstacion, $codigoSiom) {
		$this->db->where('itemplan', $itemplan);
		$this->db->where('idEstacion', $idEstacion);
		$this->db->update('siom_obra', array('codigoSiom' => $codigoSiom));

		if ($this->db->affected_rows() != 1) {
			return array('error' => EXIT_ERROR, 'msj' => 'error, no se actualiz&oacute; el c&oacute;digo siom');
		}else{
			return array('error' => EXIT_SUCCESS);
		}
	}
}