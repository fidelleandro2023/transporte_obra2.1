<?php
class M_bandeja_ficha_tecnica extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
	function   getBandejaFichaTecnica($SubProy,$eecc,$zonal,$itemPlan,$mesEjec, $idEECC){
	    $Query = " SELECT tb1.*, ft.id_ficha_tecnica, ft.fecha_registro, ftse.id_ficha_tecnica_base , ft.id_ficha_tecnica, ft.estado_validacion, 
                                     CASE 
										 WHEN ft.id_ficha_tecnica is null AND tb1.flg_evidencia IS NULL THEN 'SIN EVIDENCIA'
                                         WHEN ft.id_ficha_tecnica is null AND tb1.flg_evidencia = 1 THEN 'PDT D.J'
                                         WHEN ft.id_ficha_tecnica is not null AND (ft.estado_validacion  is null	OR ft.estado_validacion = '') THEN 
                                                     CASE WHEN tb1.idSubProyecto = 97 THEN 
														  CASE WHEN tb1.has_sirope is null THEN 'PDTE SIROPE' 
	                                                      ELSE 'D.J. PDTE VALIDACION' END  
	                                                 ELSE  'D.J. PDTE VALIDACION' END
										 WHEN ft.estado_validacion = '1' THEN 'D.J. APROBADA'
        								 WHEN ft.estado_validacion = '2' THEN 'D.J. RECHAZADO' 
                                         ELSE '' END as estado_vali
		FROM ( SELECT distinct po.idEmpresaColab, ea.idEstacion, po.itemPlan, po.idEstadoPlan, po.indicador ,sp.idSubProyecto, p.idProyecto, sp.subProyectoDesc, z.zonalDesc, 
(CASE WHEN po.idSubProyecto = 97 THEN (SELECT empresaColabDesc FROM empresacolab WHERE idEmpresaColab = c.idEmpresaColabCV) ELSE ec.empresaColabDesc END) as empresaColabDesc, 
            ep.estadoPlanDesc, 
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
						END as fechaPrevEjec, iea.porcentaje, e.estacionDesc, iea.flg_evidencia, po.has_sirope,f.faseDesc
		FROM proyecto p, subproyecto sp, zonal z, empresacolab ec, estadoplan ep, subproyectoestacion se, estacionarea ea , estacion e,
					planobra po, itemplanestacionavance iea, central c, fase f		
		WHERE po.idSubProyecto = sp.idSubProyecto 
	    AND sp.idProyecto = p.idProyecto
        AND po.idCentral = c.idCentral
        AND po.idFase = f.idFase
		AND c.idZonal = z.idZonal       
        AND CASE WHEN po.fechaPreliquidacion IS NULL THEN po.fechaPreliquidacion IS NULL
                 ELSE DATE(po.fechaPreliquidacion) >= '2018-07-07' END
		AND c.idEmpresaColab = ec.idEmpresaColab
		AND po.idEstadoPlan = ep.idEstadoPlan
		and po.idSubProyecto = se.idSubProyecto
		and se.idEstacionArea = ea.idEstacionArea
		and ea.idEstacion = e.idEstacion
		and ea.idEstacion in (2,5)
		AND po.paquetizado_fg is null
		AND e.idEstacion = iea.idEstacion
		AND  iea.itemplan = po.itemplan
		AND  iea.porcentaje = '100'";
		 if($itemPlan != ''){
	        $Query .= " AND po.itemplan = '".$itemPlan."'";
	    }
	    if($SubProy != ''){
	        $Query .= " AND po.idSubProyecto = ".$SubProy;
	    }
		if($idEECC == ID_EECC_QUANTA || $idEECC == ID_EECC_CAMPERU){
        $Query .= " AND  c.idEmpresaColabCV = ".$idEECC." AND po.idSubProyecto = 97 ";
        }else if($idEECC != ''  && $idEECC != '0' && $idEECC != '6'){
            $Query .= " AND  c.idEmpresaColab = ".$idEECC." AND po.idSubProyecto != 97 ";
        }
		$Query .= " UNION ALL
		SELECT distinct po.idEmpresaColab, ea.idEstacion, po.itemPlan, po.idEstadoPlan, po.indicador ,sp.idSubProyecto, p.idProyecto, sp.subProyectoDesc, z.zonalDesc, 
(CASE WHEN po.idSubProyecto = 97 THEN (SELECT empresaColabDesc FROM empresacolab WHERE idEmpresaColab = c.idEmpresaColabCV) ELSE ec.empresaColabDesc END) as empresaColabDesc, 
            ep.estadoPlanDesc, 
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
						END as fechaPrevEjec, 0 as porcentaje, e.estacionDesc, 1 as flg_evidencia, po.has_sirope,f.faseDesc
		FROM proyecto p, subproyecto sp, zonal z, empresacolab ec, estadoplan ep, subproyectoestacion se, estacionarea ea , estacion e,
					planobra po, pqt_central c, fase f		
		WHERE po.idSubProyecto = sp.idSubProyecto 
	    AND sp.idProyecto = p.idProyecto
        AND po.idCentralPqt = c.idCentral
        AND po.idFase = f.idFase
		AND c.idZonal = z.idZonal       
        AND CASE WHEN po.fechaPreliquidacion IS NULL THEN po.fechaPreliquidacion IS NULL                  ELSE DATE(po.fechaPreliquidacion) >= '2018-07-07' END
		AND po.idEmpresaColab = ec.idEmpresaColab
		AND po.idEstadoPlan = ep.idEstadoPlan
		and po.idSubProyecto = se.idSubProyecto
		and se.idEstacionArea = ea.idEstacionArea
		and ea.idEstacion = e.idEstacion
		and ea.idEstacion in (2,5)
        AND po.idEstadoPlan not in (8,1,2,6,5,23,22,21)
		AND po.paquetizado_fg in (1,2)";
		 if($itemPlan != ''){
	        $Query .= " AND po.itemplan = '".$itemPlan."'";
	    }
	    if($SubProy != ''){
	        $Query .= " AND po.idSubProyecto = ".$SubProy;
	    }
		if($idEECC == ID_EECC_QUANTA || $idEECC == ID_EECC_CAMPERU){
        $Query .= " AND  c.idEmpresaColabCV = ".$idEECC." AND po.idSubProyecto = 97 ";
        }else if($idEECC != ''  && $idEECC != '0' && $idEECC != '6'){
            $Query .= " AND  c.idEmpresaColab = ".$idEECC." AND po.idSubProyecto != 97 ";
        }
		$Query .= " )  tb1 
		LEFT JOIN ficha_tenica_subproyecto_estacion ftse 
		ON ftse.idSubProyecto = tb1.idSubProyecto AND ftse.idEstacion = tb1.idEstacion
		LEFT JOIN 	ficha_tecnica ft	ON	tb1.itemplan = ft.itemplan	AND	ft.flg_activo = 1 AND  ft.id_estacion = tb1.idEstacion";
		
	    
	    $Query .= " ORDER BY tb1.flg_evidencia, tb1.itemplan";
	    
	    $result = $this->db->query($Query,array());
		_log($this->db->last_query());
	    return $result;
	}
	
	function   getBandejaFichaTecnicaEvaluacion($SubProy,$eecc,$zonal,$situacion,$mesEjec){
	    $Query = " SELECT po.itemPlan, po.indicador ,sp.subProyectoDesc, z.zonalDesc, ec.empresaColabDesc, ep.estadoPlanDesc,
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
            				END as fechaPrevEjec, ft.id_ficha_tecnica, ft.estado_validacion, 
                            CASE WHEN ft.estado_validacion = '1' THEN 'APROBADO'
								 WHEN ft.estado_validacion = '2' THEN 'RECHAZADO' 
                                 ELSE 'PENDIENTE' END as estado_vali,
                            ft.id_ficha_tecnica_base, e.estacionDesc
			    FROM subproyecto sp, zonal z, empresacolab ec, estadoplan ep, estacion e, planobra po
				LEFT JOIN ficha_tecnica ft ON po.itemplan = ft.itemplan
                WHERE po.idSubProyecto = sp.idSubProyecto
                AND po.idZonal = z.idZonal
                AND po.idEmpresaColab = ec.idEmpresaColab
                AND po.idEstadoPlan = ep.idEstadoPlan
                AND ft.id_estacion = e.idEstacion
                AND po.idEstadoPlan IN('".ID_ESTADO_PRE_LIQUIDADO."', '".ID_ESTADO_TERMINADO."', '".ID_ESTADO_PLAN_EN_OBRA."')
                AND ft.id_ficha_tecnica is not null 
                AND ft.flg_activo = 1
                AND po.idSubProyecto != ".ID_SUB_PROYECTO_CV_INTEGRAL."";
	    if($SubProy != ''){
	        $Query .= " AND sp.subProyectoDesc REGEXP '".str_replace(',','|',$SubProy)."'";
	    }
	    if($eecc != ''){
	        $Query .= " AND ec.empresaColabDesc LIKE '%".$eecc."%'";
	    }
	    if($zonal != ''){
	        $Query .= " AND z.zonalDesc = '".$zonal."'";
	    }
	    if($situacion!= ''){
	        if($situacion  ==  '0'){
	            $Query .= " AND ft.estado_validacion = ''";
	        }else{
	            $Query .= " AND ft.estado_validacion = '".$situacion."'";
	        }
	        
	    }
	    if($mesEjec!=''){
	        $Query .= " HAVING fechaPrevEjec = '".$mesEjec."'";
	    }
	    
	    $result = $this->db->query($Query,array());
	    _log($this->db->last_query());
	    return $result;
	}
	
	function getInfoItemPlanFichaTecnica($itemplan){
	      $Query = " SELECT    po.itemplan, po.idSerieTroba, po.coordX, po.coordY, po.fechaEjecucion, sp.subProyectoDesc, MIN(STR_TO_DATE(wu.f_aprob, '%d/%m/%Y')) AS fec_inicio, po.indicador, c.codigo, ec.empresaColabDesc, st.serie, po.fechaPreLiquidacion
        				FROM   subproyecto sp, detalleplan dp, web_unificada wu, central c, empresacolab ec, planobra po
                        LEFT JOIN serie_troba st ON po.idSerieTroba = st.idSerieTroba
        				WHERE  po.itemplan = dp.itemplan
        				AND    po.idCentral = c.idCentral
        				AND    c.idEmpresaColab = ec.idEmpresaColab
        				AND    dp.poCod = wu.ptr
        				AND    po.idSubProyecto = sp.idSubProyecto
        				AND    po.itemplan = ?
        				AND    wu.f_aprob != ''";
	      $result = $this->db->query($Query,array($itemplan));
	      if($result->row() != null) {
	          return $result->row_array();
	      } else {
	          return null;
	      }
	}
	
	function getInfoFichaTecnicaByItemplan($itemplan, $tipo_ficha){
	    $Query = "SELECT f.id_ficha_tecnica,
	                     f.observacion_tdp,
	                     f.observacion_audi,
	                     (SELECT ft.descripcion 
						    FROM ficha_tecnica_opc_auditor ft 
						   WHERE ft.id = f.id_ficha_audi)descFichaAudi,
						 CASE WHEN f.jefe_c_nombre IS NULL THEN (SELECT u.nombre
																   FROM cuadrilla c,
																		usuario u
																  WHERE c.id_usuario  = u.id_usuario
																	AND c.idCuadrilla = i.id_cuadrilla)
							  ELSE f.jefe_c_nombre END jefe_c_nombre, 
						 f.jefe_c_celular,
						 f.jefe_c_codigo,
						 f.has_plano,
						 f.observacion,
						 f.observacion_adicional,
						 f.itemplan,
					 	 f.fecha_registro,
						 f.usuario_registro,
						 f.coordenada_x,
						 f.coordenada_y,
						 f.estado_validacion,
						 f.flg_activo,
						 f.flg_auditado,
						 f.usuario_auditoria,
						 f.id_ficha_tecnica_base,
						 f.id_estacion
					FROM ficha_tecnica f LEFT JOIN itemplanestacionavance i ON (i.itemplan = f.itemplan AND i.idEstacion = f.id_estacion)
				   WHERE f.id_ficha_tecnica_base = ?
					 AND f.itemplan              = ?
					 AND flg_activo              = 1";
	    $result = $this->db->query($Query,array($tipo_ficha, $itemplan));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
	function getNivelesCalibracion(){
	    $Query = " SELECT * FROM ficha_tecnica_nivel_calibra;";
	    $result = $this->db->query($Query,array());
	    return $result;
	}
	
	function getTrabajosFichaTecnica($tipo_ficha){
	    $Query = " SELECT * FROM ficha_tecnica_trabajo WHERE tipo_ficha = ".$tipo_ficha;
	    $result = $this->db->query($Query,array());
	    return $result;
	}
	
	function getTipoTrabajoFichaTecnica(){
	    $Query = " SELECT * FROM ficha_tecnica_tipo_trabajo;";
	    $result = $this->db->query($Query,array());
	    return $result;
	}	
	
	/**
	 * @Ficha : Registro de ficha tecnica Coaxial
	 * @Area:COAXIAL
	 * @Proyecto: TODOS
	 * @Constante: FICHA_TECNICA_TODAS_COAXIAL
	 **/
	function insertFichaTecnica($coorx, $coory, $itemplan, $nombreJefe, $codigoJefe, $celularJefe, $hasPlano, $observacion, $observacionAdi, $arrayTipoTrabajo, $arrayNivelesCali, $type){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	     
	        $this->db->trans_begin();
	               
	        if($type == '2'){
    	            $dataUpdate = array(
    	                'flg_activo' => 2
    	            );
    	            $this->db->where('itemplan', $itemplan);
    	            $this->db->where('id_ficha_tecnica_base', FICHA_COAXIAL_GENERICA);
	                $this->db->where('flg_activo', 1);
    	            $this->db->update('ficha_tecnica', $dataUpdate);
    	            if ($this->db->trans_status() === FALSE) {
    	                throw new Exception('Hubo un error al actualizar en ficha_tecnica');
    	            }
	           }
	               
	            $dataInsert= array(
	                'jefe_c_nombre' => $nombreJefe,
	                'jefe_c_celular' => $celularJefe,
	                'jefe_c_codigo' => $codigoJefe,
	                'has_plano' => $hasPlano,
	                'observacion' => $observacion,
	                'observacion_adicional' =>  $observacionAdi,
	                'itemplan' => $itemplan,
	                'fecha_registro' =>date("Y-m-d H:i:s"),
	                'usuario_registro' =>  $this->session->userdata('idPersonaSession'),
	                'coordenada_x' => $coorx,
	                'coordenada_y' => $coory,
	                'flg_activo' => '1',
	                'id_ficha_tecnica_base' => FICHA_COAXIAL_GENERICA,
	                'id_estacion' => ID_ESTACION_COAXIAL
	            );	            
	            $this->db->insert('ficha_tecnica',$dataInsert);
	            if ($this->db->affected_rows() != 1) {
	                $this->db->trans_rollback();
	                throw new Exception('Hubo un error al actualizar el estadoplan.');
	            }else{
	                
	                $idFicha = $this->db->insert_id();
	                for($i=0; $i < count($arrayTipoTrabajo); $i++){
	                    $arrayTipoTrabajo[$i]['id_ficha_tecnica'] = $idFicha;
	                }
	                
	                $this->db->insert_batch('ficha_tecnica_x_tipo_trabajo', $arrayTipoTrabajo);
	                if ($this->db->trans_status() === FALSE) {
	                    $this->db->trans_rollback();
	                    throw new Exception('Hubo un error al insertar el log_porcentaje_cuadrilla.');
	                }else{	                    
	                    
	                    for($i=0; $i < count($arrayNivelesCali); $i++){
	                        $arrayNivelesCali[$i]['id_ficha_tecnica'] = $idFicha;
	                    }	                    
	                    $this->db->insert_batch('ficha_tecnica_x_nivel_calibra', $arrayNivelesCali);
	                    if ($this->db->trans_status() === FALSE) {
	                        $this->db->trans_rollback();
	                        throw new Exception('Hubo un error al insertar el log_porcentaje_cuadrilla.');
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
	
	
	/** @Ficha : Registro de ficha tecnica Fo
	 * @Area: FO
	 * @Proyecto: FTTH Y OBRAS PUBLICAS
	 * @constante: FICHA_TECNICA_FTTH_OP_FO
	 */
	
	function insertFichaTecnicaFoFTTHOP($coorx, $coory, $itemplan, $nombreJefe, $codigoJefe, $celularJefe, $hasPlano, $observacion, $observacionAdi, $arrayTipoTrabajo, $arrayMedReflec, $type){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        
	        $this->db->trans_begin();
	        
	        if($type == '2'){
	            $dataUpdate = array(
	                'flg_activo' => 2
	            );
	            $this->db->where('itemplan', $itemplan);
	            $this->db->where('id_ficha_tecnica_base', FICHA_FO_FTTH_Y_OP);
	            $this->db->where('flg_activo', 1);
	            $this->db->update('ficha_tecnica', $dataUpdate);
	            if ($this->db->trans_status() === FALSE) {
	                throw new Exception('Hubo un error al actualizar en ficha_tecnica');
	            }
	        }
	        
	        $dataInsert= array(
	            'jefe_c_nombre' => $nombreJefe,
	            'jefe_c_celular' => $celularJefe,
	            'jefe_c_codigo' => $codigoJefe,
	            'has_plano' => $hasPlano,
	            'observacion' => $observacion,
	            'observacion_adicional' =>  $observacionAdi,
	            'itemplan' => $itemplan,
	            'fecha_registro' =>date("Y-m-d H:i:s"),
	            'usuario_registro' =>  $this->session->userdata('idPersonaSession'),
	            'coordenada_x' => $coorx,
	            'coordenada_y' => $coory,
	            'flg_activo' => '1',
	            'id_ficha_tecnica_base' => FICHA_FO_FTTH_Y_OP,
	            'id_estacion' => ID_ESTACION_FO
	            
	        );
	        $this->db->insert('ficha_tecnica',$dataInsert);
	        if ($this->db->affected_rows() != 1) {
	            $this->db->trans_rollback();
	            throw new Exception('Hubo un error al actualizar el estadoplan.');
	        }else{
	            
	            $idFicha = $this->db->insert_id();
	            for($i=0; $i < count($arrayTipoTrabajo); $i++){
	                $arrayTipoTrabajo[$i]['id_ficha_tecnica'] = $idFicha;
	            }
	            
	            $this->db->insert_batch('ficha_tecnica_x_tipo_trabajo', $arrayTipoTrabajo);
	            if ($this->db->trans_status() === FALSE) {
	                $this->db->trans_rollback();
	                throw new Exception('Hubo un error al insertar el log_porcentaje_cuadrilla.');
	            }else{
	                
	                for($i=0; $i < count($arrayMedReflec); $i++){
	                    $arrayMedReflec[$i]['id_ficha_tecnica'] = $idFicha;
	                }
	                $this->db->insert_batch('ficha_tecnica_medidas_reflecto', $arrayMedReflec);
	                if ($this->db->trans_status() === FALSE) {
	                    $this->db->trans_rollback();
	                    throw new Exception('Hubo un error al insertar el log_porcentaje_cuadrilla.');
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
	
	function getTrabajosFichatecnicaByItemplan($itemplan, $tipo_ficha){
	    $Query = " SELECT   ftxtt.id_ficha_tecnica_x_tipo_trabajo, ftxtt.id_ficha_tecnica_trabajo, ftt.descripcion, ftxtt.cantidad,fttt.descripcion as tipo_trabajo,ftxtt.observacion, ftxtt.flg_validado, ftxtt.comentario_vali, fttt.id_ficha_tecnica_tipo_trabajo 
                    FROM    ficha_tecnica ft,
                        	ficha_tecnica_trabajo ftt,
                        	ficha_tecnica_x_tipo_trabajo ftxtt
                	LEFT JOIN
                	       ficha_tecnica_tipo_trabajo fttt
                	ON     ftxtt.id_ficha_tecnica_tipo_trabajo = fttt.id_ficha_tecnica_tipo_trabajo
                	WHERE  ft.id_ficha_tecnica = ftxtt.id_ficha_tecnica
                	AND    ftxtt.id_ficha_tecnica_trabajo = ftt.id_ficha_tecnica_trabajo
                	AND    ft.itemplan = ?
                    AND    ft.flg_activo = 1
                    AND    ft.id_ficha_tecnica_base = ?";
	    $result = $this->db->query($Query,array($itemplan, $tipo_ficha));
	    return $result;
	}
	
	function getNivelesCalibracionByItemplan($itemplan){
	    $Query = "SELECT    ftxnc.id_ficha_tecnica_x_nivel_calibra, ftnc.descripcion, ftxnc.opt_recep, ftxnc.opt_tx, ftxnc.ch_30,  ftxnc.ch_75,  ftxnc.ch_113,  ftxnc.snr_ruido, ftxnc.flg_validado, ftxnc.comentario_vali, ftxnc.id_ficha_tecnica_nivel_calibra  
                    FROM    ficha_tecnica ft,
                        	ficha_tecnica_nivel_calibra ftnc,
                        	ficha_tecnica_x_nivel_calibra ftxnc
                	WHERE  ft.id_ficha_tecnica = ftxnc.id_ficha_tecnica
                	AND    ftxnc.id_ficha_tecnica_nivel_calibra = ftnc.id_ficha_tecnica_nivel_calibra
                	and    ft.itemplan = ?
                    AND    ft.flg_activo = 1";
	    $result = $this->db->query($Query,array($itemplan));
	    return $result;
	}
	
	function saveFichaTecnicaValidacion($idFicha, $estado, $arrayTrabajos, $arrayNiveles, $itemplan, $arrayInsert, $arrayUpdate){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $dataUpdate = array(
	            'estado_validacion' => $estado,	            
	            'fecha_validacion' =>date("Y-m-d H:i:s"),
	            'usuario_validacion' =>  $this->session->userdata('idPersonaSession')
	        );
	        $this->db->where('id_ficha_tecnica', $idFicha);
	        $this->db->update('ficha_tecnica', $dataUpdate);
	        if ($this->db->trans_status() === FALSE) {
	            throw new Exception('Hubo un error al actualizar en web_unificada');
	        }else{
    	        $this->db->update_batch('ficha_tecnica_x_tipo_trabajo',$arrayTrabajos, 'id_ficha_tecnica_x_tipo_trabajo');
    	        if ($this->db->trans_status() === FALSE) {
    	            $this->db->trans_rollback();
    	            throw new Exception('Hubo un error al actualizar el saveFichaTecnicaValidacion.');
    	        }else{
    	            $this->db->update_batch('ficha_tecnica_x_nivel_calibra',$arrayNiveles, 'id_ficha_tecnica_x_nivel_calibra');
    	            if ($this->db->trans_status() === FALSE) {
    	                $this->db->trans_rollback();
    	                throw new Exception('Hubo un error al actualizar el saveFichaTecnicaValidacion.');
    	            }else{
    	                if($estado == FICHA_TECNICA_APROBADA){
    	                    $dataUpdate = array(
    	                        "idEstadoPlan" => ID_ESTADO_TERMINADO,
    	                        'fechaEjecucion' =>date("Y-m-d")
    	                    );
    	                    
    	                    $this->db->where('itemPlan', $itemplan);
    	                    $this->db->update('planobra', $dataUpdate);
    	                    if($this->db->trans_status() === FALSE) {
    	                        $this->db->trans_rollback();
    	                        throw new Exception('Error al modificar el updateEstadoPlanObra');
    	                    }else{
    	                        $this->db->insert_batch('itemplanestacionavance', $arrayInsert);
    	                        if ($this->db->trans_status() === FALSE) {
    	                            throw new Exception('Hubo un error al insertar el itemplanestacionavance.');
    	                        }else{
    	                            $this->db->update_batch('itemplanestacionavance',$arrayUpdate, 'idItemplanEstacion');
    	                            if ($this->db->trans_status() === FALSE) {
    	                                $this->db->trans_rollback();
    	                                throw new Exception('Hubo un error al actualizar el itemplanestacionavance.');
    	                            }else{
    	                                $data['error']    = EXIT_SUCCESS;
    	                                $data['msj']      = 'Se actualizo correctamente!';
    	                                $this->db->trans_commit();
    	                            }
    	                        }    	                       
    	                    }
    	                }else{
    	                    $data['error'] = EXIT_SUCCESS;
            	            $data['msj'] = 'Se actualizo correctamente!';
            	            $this->db->trans_commit();
    	                }        	            
    	            }
    	        }
	        }
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
		
	function   getEstacionPorcentajeByItemPlanAll($itemplan){
	    $Query = "SELECT de.*,  CASE WHEN ie.porcentaje IS NULL then 0 ELSE ie.porcentaje END AS porcentaje, ie.idItemplanEstacion FROM (
            	    SELECT DISTINCT po.itemplan,e.idEstacion, e.estacionDesc, po.idEmpresaColab, po.idZonal
            	    FROM planobra po, subproyectoestacion se, estacionarea ea, estacion e
            	    WHERE po.idSubProyecto = se.idSubProyecto
            	    AND se.idEstacionArea = ea.idEstacionArea
            	    AND ea.idEstacion = e.idEstacion
            	    AND po.itemplan = ? ) as de LEFT JOIN itemplanestacionavance ie
            	    ON de.itemplan = ie.itemplan
            	    AND de.idEstacion = ie.idEstacion
            	    ORDER BY de.idEstacion;";
	    $result = $this->db->query($Query,array($itemplan));
	    return $result;
	}	
	
	function   getMedidasReflectometricasByItemplan($itemplan){
	    $Query = " SELECT ftm.* FROM ficha_tecnica ft, ficha_tecnica_medidas_reflecto ftm
                	WHERE ft.id_ficha_tecnica = ftm.id_ficha_tecnica
                	AND ft.itemplan = ?
                	and flg_activo = 1";
	    $result = $this->db->query($Query,array($itemplan));
	    return $result;
	}	
	
	function   getMedidasReflecoByItemplan($itemplan){
	    $Query = " SELECT ftm.* FROM
                	ficha_tecnica ft, ficha_tecnica_medidas_reflecto ftm
                	where ft.id_ficha_tecnica = ftm.id_ficha_tecnica
                	and ft.itemplan = ?
                	and ft.flg_activo = 1";
	    $result = $this->db->query($Query,array($itemplan));
	    return $result;
	}
	
	
	/** @Ficha : Registro de ficha tecnica Fo
	 * @Area: FO
	 * @Proyecto: FTTH Y OBRAS PUBLICAS
	 * @constante: FICHA_TECNICA_FTTH_OP_FO
	 */
	
	function insertFichaTecnicaFoSisegos($coorx, $coory, $itemplan, $nombreJefe, $codigoJefe, $celularJefe, $hasPlano, $observacion, $observacionAdi, $arrayTipoTrabajo, $arrayMedReflec, $arrayMedPotencia, $type){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        
	        $this->db->trans_begin();
	        
	        if($type == '2'){
	            $dataUpdate = array(
	                'flg_activo' => 2
	            );
	            $this->db->where('itemplan', $itemplan);
	            $this->db->where('id_ficha_tecnica_base', FICHA_FO_SISEGOS_SMALLCELL_EBC);
	            $this->db->where('flg_activo', 1);
	            $this->db->update('ficha_tecnica', $dataUpdate);
	            if ($this->db->trans_status() === FALSE) {
	                throw new Exception('Hubo un error al actualizar en ficha_tecnica');
	            }
	        }
	        
	        $dataInsert= array(
	            'jefe_c_nombre'    => $nombreJefe,
	            'jefe_c_celular'   => $celularJefe,
	            'jefe_c_codigo'    => $codigoJefe,
	            'has_plano'        => $hasPlano,
	            'observacion'      => $observacion,
	            'observacion_adicional' =>  $observacionAdi,
	            'itemplan'         =>  $itemplan,
	            'fecha_registro'   =>  date("Y-m-d H:i:s"),
	            'usuario_registro' =>  $this->session->userdata('idPersonaSession'),
	            'coordenada_x'     =>  $coorx,
	            'coordenada_y'     =>  $coory,
	            'flg_activo'       => '1',
	            'id_ficha_tecnica_base' => FICHA_FO_SISEGOS_SMALLCELL_EBC,
	            'id_estacion'      => ID_ESTACION_FO
	            
	        );
	        $this->db->insert('ficha_tecnica',$dataInsert);
	        if ($this->db->affected_rows() != 1) {
	            $this->db->trans_rollback();
	            throw new Exception('Hubo un error al actualizar el estadoplan.');
	        }else{
	            
	            $idFicha = $this->db->insert_id();
	            for($i=0; $i < count($arrayTipoTrabajo); $i++){
	                $arrayTipoTrabajo[$i]['id_ficha_tecnica'] = $idFicha;
	            }
	            
	            $this->db->insert_batch('ficha_tecnica_x_tipo_trabajo', $arrayTipoTrabajo);
	            if ($this->db->trans_status() === FALSE) {
	                $this->db->trans_rollback();
	                throw new Exception('Hubo un error al insertar el log_porcentaje_cuadrilla.');
	            }else{
	                
	                for($i=0; $i < count($arrayMedReflec); $i++){
	                    $arrayMedReflec[$i]['id_ficha_tecnica'] = $idFicha;
	                }
	                $this->db->insert_batch('ficha_tecnica_med_reflec_end_to_end', $arrayMedReflec);
	                if ($this->db->trans_status() === FALSE) {
	                    $this->db->trans_rollback();
	                    throw new Exception('Hubo un error al insertar el log_porcentaje_cuadrilla.');
	                }else{
	                    
	                    for($i=0; $i < count($arrayMedPotencia); $i++){
	                        $arrayMedPotencia[$i]['id_ficha_tecnica'] = $idFicha;
	                    }
	                    $this->db->insert_batch('ficha_tecnica_medidas_potencia', $arrayMedPotencia);
	                    if ($this->db->trans_status() === FALSE) {
	                        $this->db->trans_rollback();
	                        throw new Exception('Hubo un error al insertar el log_porcentaje_cuadrilla.');
	                    }else{
    	                    
    	                    $data['error']    = EXIT_SUCCESS;
    	                    $data['msj']      = 'Se actualizo correctamente!';
    	                    $this->db->trans_commit();
	                    }
	                }
	            }
	        }
	        
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
	
	function   getMedidasDePotencia($itemplan){
	    $Query = " SELECT ftxt.* FROM   ficha_tecnica ft,  ficha_tecnica_medidas_potencia ftxt
                	WHERE	ftxt.id_ficha_tecnica	=	ft.id_ficha_tecnica
                	AND		ft.itemplan = ?
                	AND		ft.flg_activo = 1;";
	    $result = $this->db->query($Query,array($itemplan));
	    return $result;
	}
		
	function   getMedReflecEndToEnd($itemplan){
	    $Query = " SELECT ftxt.* FROM  ficha_tecnica ft,ficha_tecnica_med_reflec_end_to_end ftxt
                    WHERE	ftxt.id_ficha_tecnica	=	ft.id_ficha_tecnica
                    AND		ft.itemplan = ?
                    AND		ft.flg_activo = 1 ;";
	    $result = $this->db->query($Query,array($itemplan));
	    return $result;
	}
	
    function getMaterialesCVByItemplan($itemplan) {
	    $sql="SELECT 	imc.id, m.id_material, m.descrip_material, imc.total
            	FROM 	itemplan_material im,
                    	itemplan_material_cantidad imc,
                    	material m
            	WHERE 	im.id_itemplan_material = imc.id_itemplan_material
            	AND		imc.id_material 	= m.id_material
            	AND		im.itemplan 		= ?";
	    $result = $this->db->query($sql,array($itemplan));
	    return $result;
	}	
	
	function getInfoItemPlanFichaTecnicaCV($itemplan){
	    $Query = " SELECT    	po.itemplan, po.idSerieTroba, po.coordX, po.coordY, sp.subProyectoDesc, 
                    			c.codigo, ec.empresaColabDesc, po.fechaPreLiquidacion, pdc.direccion, pdc.numero,
                                pdc.depa, pdc.pisos, im.instalacion_cto, im.microcanolizado
                    	FROM   subproyecto sp, central c, empresacolab ec, itemplan_material im, planobra po
                    	LEFT JOIN planobra_detalle_cv pdc ON  pdc.itemplan = po.itemplan 
                    	WHERE  po.idCentral = c.idCentral
                    	AND    po.itemplan = im.itemplan
                       	AND	   c.idEmpresaColabCV = ec.idEmpresaColab        			
                    	AND    po.idSubProyecto = sp.idSubProyecto
        				AND    po.itemplan = ?";
	    $result = $this->db->query($Query,array($itemplan));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
	function getDataFormularioObrasPublicas($itemplan) {
		$sql = "SELECT *
				  FROM form_obra_publica f LEFT JOIN 
					   detalleplan dp ON f.itemplan = dp.itemPlan
				 WHERE f.itemplan = '".$itemplan."'";
		$result = $this->db->query($sql);
		return $result->result();		 
	}
	
	function saveFichaTecnicaValidacionOBP($idFicha, $estado, $itemplan, $arrayInsert, $arrayUpdate, $observacion){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $dataUpdate = array(
	            'estado_validacion'  => $estado,	            
	            'fecha_validacion'   => date("Y-m-d H:i:s"),
				'usuario_validacion' => $this->session->userdata('idPersonaSession'),
				'observacion_tdp'    => $observacion
	        );
	        $this->db->where('id_ficha_tecnica' , $idFicha);
	        $this->db->update('ficha_tecnica'   , $dataUpdate);
	        if ($this->db->trans_status() === FALSE) {
	            throw new Exception('Hubo un error al actualizar en web_unificada');
	        }else{
				if($estado == FICHA_TECNICA_APROBADA){
					$dataUpdate = array(
						"idEstadoPlan"   => ID_ESTADO_TERMINADO,
						'fechaEjecucion' => date("Y-m-d")
					);
					
					$this->db->where('itemPlan' , $itemplan);
					$this->db->update('planobra', $dataUpdate);
					if($this->db->trans_status() === FALSE) {
						$this->db->trans_rollback();
						throw new Exception('Error al modificar el updateEstadoPlanObra');
					}else{
						$this->db->insert_batch('itemplanestacionavance', $arrayInsert);
						if ($this->db->trans_status() === FALSE) {
							throw new Exception('Hubo un error al insertar el itemplanestacionavance.');
						}else{
							$this->db->update_batch('itemplanestacionavance',$arrayUpdate, 'idItemplanEstacion');
							if ($this->db->trans_status() === FALSE) {
								$this->db->trans_rollback();
								throw new Exception('Hubo un error al actualizar el itemplanestacionavance.');
							}else{
								$data['error']    = EXIT_SUCCESS;
								$data['msj']      = 'Se actualizo correctamente!';
								$this->db->trans_commit();
							}
						}    	                       
					}
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
	
	function reactivarFichaCV($itemplan, $idFichaTecnica, $informacion){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        //log_message('error', 'reactivarFichaCV');
	        $this->db->trans_begin();
	        $this->db->where('itemplan', $itemplan);
	        $this->db->where('flg_activo', 1);   //ACTIVO
	        $this->db->where('id_ficha_tecnica', $idFichaTecnica);
	        $this->db->update('ficha_tecnica',  $informacion);
	        //log_message('error', 'query:'.$this->db->last_query());
	        if ($this->db->trans_status() === FALSE) {
	            throw new Exception('Hubo un error al actualizar el ficha tecnica.');
	        }else{
	             $data['error']    = EXIT_SUCCESS;
	             $data['msj']      = 'Se actualizo correctamente!';
	             $this->db->trans_commit();
	        }
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
}