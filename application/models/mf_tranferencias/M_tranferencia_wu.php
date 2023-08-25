<?php
class M_tranferencia_wu extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
   function   getWebUnificadaFa($pathFinal){
        $data ['error']= EXIT_ERROR;
        $data['msj'] = null;
        try{
            $this->db->trans_begin();
            $this->db->from('import_web_unificada');
            $this->db->truncate();
            if ($this->db->trans_status() === TRUE) {
				//log_message('error', 'path:'.$pathFinal);
                $this->db->query("LOAD DATA LOCAL INFILE '".$pathFinal."' INTO TABLE import_web_unificada");
				log_message('error', 'path:'.$pathFinal);
				log_message('error', $this->db->last_query());				
                if ($this->db->trans_status() === TRUE) {
                    $this->db->query("DELETE FROM import_web_unificada WHERE PTR LIKE '%PTR%'");
                    if ($this->db->trans_status() === TRUE) {
                        $this->db->query("DELETE FROM import_web_unificada
                                            WHERE EXISTS (SELECT NULL
                                            FROM equivalencias b
                                            WHERE est_innova LIKE CONCAT('%', b.descripcion, '%') AND b.id_proceso = 2)");
                        if ($this->db->trans_status() === TRUE) {
                                $this->db->trans_commit();
                                $data ['error']= EXIT_SUCCESS;
                            }else{
                                $this->db->trans_rollback();
                                throw new Exception('ERROR DELETE FROM import_web_unificada 2');
                            }                           
                        }else{
                            $this->db->trans_rollback();
                            throw new Exception('ERROR DELETE FROM import_web_unificada 1');
                        }
                }else{
                    $this->db->trans_rollback();
                    throw new Exception('ERROR LOAD DATA LOCAL INFILE');
                }
            } else {
                $this->db->trans_rollback();
                throw new Exception('ERROR TRUNCATE import_web_unificada');
            }
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        return $data;
	}	
	
	function   saveLogGenerarDP($fechaActual){
	    $data['error']= EXIT_ERROR;
	    $data['msj'] = null;
	    try{
	        $dataUpdateLog= array(
	            'tabla' => 'detalle_plan_file',
	            'actividad' => 'generar',
	            'fecha_registro' =>$fechaActual,
	            'id_usuario' =>  $this->session->userdata('idPersonaSession')
	        );
	        
	        $this->db->insert('log_planobra',$dataUpdateLog);
	        if ($this->db->affected_rows() != 1) {
	            $this->db->trans_rollback();
	            throw new Exception('Hubo un error al actualizar el estadoplan.');
	        }else{
	            $data['error']    = EXIT_SUCCESS;
	            $data['msj']      = 'Se actualizo correctamente!';
	            $this->db->trans_commit();
	        }
    }catch(Exception $e){
        $data['msj'] = $e->getMessage();
    }
    return $data;
}
	
	
	function   loadWebUnificada(){
	    $data ['error']= EXIT_ERROR;
	    $data['msj'] = null;
	    try{
	        $this->db->trans_begin();
	        $this->db->from('web_unificada');
	        $this->db->truncate();
	        if ($this->db->trans_status() === TRUE) {
	            $this->db->query("INSERT INTO web_unificada
                                SELECT ptr,
                                est_innova,
                                (SELECT id_equivalencia FROM equivalencias where descripcion = UPPER(est_innova) AND id_proceso = 1 ) as idEstadoPtr,
                                imputacion,
                                tipo_planta,
                                tipo_proyecto,
                                categoria,
                                titulo_trabajo,
                                jefatura_ptr,
                                jefatura,
                                zonal,
                                mdf,
                                f_aprob,
                                cco,
                                af,
                                grafo,
                                (SELECT idEmpresaColab FROM empresacolab where empresaColabDesc = UPPER(eecc) ) as idEmpreColab,
                                eecc,
                                REPLACE(REPLACE(pto_can, ',', ''),'\"',''),
                                REPLACE(REPLACE(pto_cel, ',', ''),'\"',''),
                                REPLACE(REPLACE(pto_emp, ',', ''),'\"',''),
                                REPLACE(REPLACE(valoriz_m_o, ',', ''),'\"',''),
                                REPLACE(REPLACE(valoriz_material, ',', ''),'\"',''),
                                vr,
                                (SELECT id_equivalencia FROM equivalencias where descripcion = UPPER(est_innova) AND id_proceso = 1  ) as rangoPtr,
                                vd,
                                pep,
                                solpe,
                                h_gestion,
                                f_hg,
                                obs,
                                departamento,
                                provincia,
                                distrito,
                                f_ult_est,
                                f_creac_prop,
                                usu_registro,
                                monto_licencia,
                                CASE 
            				   		  WHEN 	   REPLACE(REPLACE(valoriz_m_o, ',', ''),'\"','') 	> 0	AND REPLACE(REPLACE(valoriz_material, ',', ''),'\"','') = 0	 THEN 'MO'
            	   				 	  WHEN 	   REPLACE(REPLACE(valoriz_m_o, ',', ''),'\"','') 	= 0	AND REPLACE(REPLACE(valoriz_material, ',', ''),'\"','') > 0	 THEN 'MAT' 
                                      WHEN 	   REPLACE(REPLACE(valoriz_m_o, ',', ''),'\"','') 	> 0	AND REPLACE(REPLACE(valoriz_material, ',', ''),'\"','') > 0	 THEN 'DUO' 
			                    ELSE NULL END AS desc_area, null
                                FROM import_web_unificada");
	             if ($this->db->trans_status() === TRUE) {
	               /* $this->db->query("DELETE FROM web_unificada_det where (estado_asig_grafo = 0 or estado_asig_grafo = 1)  AND has_pre_aprob is null;");
	                if ($this->db->trans_status() === TRUE) {
    	                $this->db->trans_commit();	                
    	                $data ['error']= EXIT_SUCCESS;
	                }else{
	                    $this->db->trans_rollback();
	                    throw new Exception('ERROR: DELETE FROM web_unificada_det');
	                }*/
	              
	                    $this->db->trans_commit();
	                    $data ['error']= EXIT_SUCCESS;
	            }else{
	                $this->db->trans_rollback();
	                throw new Exception('ERROR INSERT INTO web_unificada');
	            }
	        } else {
	            $this->db->trans_rollback();
	            throw new Exception('ERROR TRUNCATE web_unificada');
	        }
	    }catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    return $data;
	}

	function   execLoadWebUnificada(){
	    $data ['error']= EXIT_ERROR;
	    $data['msj'] = null;
	    try{
	        $this->db->trans_begin();
                    $this->db->query("UPDATE web_unificada JOIN web_unificada_det ON web_unificada.ptr = web_unificada_det.ptr  SET exist_wud = '1'");
	             if ($this->db->trans_status() === TRUE) {  
	                $this->db->trans_commit();
	                $data ['error']= EXIT_SUCCESS;
	                /*     
	            $this->db->query("INSERT INTO web_unificada_det 
				 SELECT DISTINCT wu.ptr, wu.est_innova, wu.jefatura, wu.eecc, sp.subProyectoDesc,NULL,NULL,
                wu.f_creac_prop,
                wu.f_ult_est,
				NULL,NULL,NULL,NULL,0,
                (CASE WHEN wu.desc_area != '' THEN  wu.desc_area ELSE a.tipoArea END),
                dp.itemPlan,
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
                e.estacionDesc,
                a.areaDesc,
                po.fechaPrevEjec,
                NULL,
                CASE WHEN wu.desc_area = '' AND  a.tipoArea = 'MAT' then '5000.00' else wu.valoriz_material END,
                CASE WHEN wu.desc_area = '' AND  a.tipoArea = 'MO' then '5000.00' else wu.valoriz_m_o END,
				po.indicador,
                NULL,
                NULL,
                '0',
	            NULL
                FROM web_unificada wu
                LEFT JOIN detalleplan dp		    
                ON wu.ptr = dp.poCod 
                LEFT JOIN  subproyectoestacion se
                ON dp.idSubProyectoEstacion = se.idSubProyectoEstacion
                LEFT JOIN subproyecto sp
                ON sp.idSubProyecto = se.idSubProyecto
                LEFT JOIN estacionarea ea
				ON	se.idEstacionArea = ea.idEstacionArea				
                LEFT JOIN estacion e                   
				ON 	ea.idEstacion = e.idEstacion
                LEFT JOIN area a
                ON ea.idArea = a.idArea   
                LEFT JOIN planobra po
                ON dp.itemPlan = po.itemPlan
                WHERE STR_TO_DATE(wu.f_creac_prop, '%d/%m/%Y %H:%i') >= STR_TO_DATE('01/01/2018 00:00', '%d/%m/%Y %H:%i')   
                  AND (CASE WHEN wu.desc_area = 'MO' THEN (substring(wu.est_innova,1,3) != '007' AND substring(wu.est_innova,1,2) != '04' AND substring(wu.est_innova,1,3) != '041' AND substring(wu.est_innova,1,2) != '06 ' AND substring(wu.est_innova,1,2) != '08') ELSE
					(substring(wu.est_innova,1,3) = '003' OR substring(wu.est_innova,1,2) = '01' OR substring(wu.est_innova,1,3) = '001' OR substring(wu.est_innova,1,3) = '002' OR substring(wu.est_innova,1,3) = '004' OR substring(wu.est_innova,1,3) = '005')
                    END)
                AND wu.exist_wud is null
                HAVING dp.itemplan is not null");
	            if ($this->db->trans_status() === TRUE) {
	                $this->db->trans_commit();
	                $data ['error']= EXIT_SUCCESS;
	            }else{	               
	                $this->db->trans_rollback();
	                throw new Exception('ERROR execLoadWebUnificada');
	            }*/
	      }else{	               
	                $this->db->trans_rollback();
	                throw new Exception('ERROR execLoadWebUnificada');
	            }
	    }catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    return $data;
	}
	
	function   execGetUpdateExternos(){
	    $data ['error']= EXIT_ERROR;
	    $data['msj'] = null;
	    try{
	         
	        $this->db->query("SELECT getUpdatesExternos();");
	        if ($this->db->trans_status() === TRUE) {
	            $this->db->query("SELECT fn_auto_aprob_firma_digital();");
	            if ($this->db->trans_status() === TRUE) {
	                $data ['error']= EXIT_SUCCESS;
	            }else{
	                _log('fn_auto_aprob_firma_digital: ');
	            }
	            
	        }else{
	            _log('getUpdatesExternos WU: ');
	        }
	         
	    }catch(Exception $e){
	       $data['msj'] = 'Ocurrio un problema!';
	    }
	    return $data;
	}
	
	function   execGetGrafos(){
	    $data ['error']= EXIT_ERROR;
	    $data['msj'] = null;
	    try{
	        $this->db->trans_begin();
	        $this->db->query("SELECT getGrafos();");
	        if ($this->db->trans_status() === TRUE) {
	            $this->db->trans_commit();
	            
	            $arrayGlobLogTrama = array();
                $arrayGlobLogTramaDespara = array();
                $arrayIPParalizados = array();
                $arrayIPDespara = array();

				$listaIPParalizados = $this->getIPParalizados();
                if ($listaIPParalizados != null) {
                    foreach ($listaIPParalizados as $row) {
                        $arrayTemp = array(
                            "origen" => 'MODELO TRANFERENCIA WU',
                            "itemplan" => $row->itemplan,
                            "sisego" => $row->indicador,
                            "fecha_registro" => $this->fechaActual(),
                            "motivo_error" => null,
                            "descripcion" => null,
                            "estado" => null,
                        );
                        array_push($arrayIPParalizados, $row->itemplan);
                        array_push($arrayGlobLogTrama, $arrayTemp);

                    }
                } else {
                    $arrayIPParalizados = array();
                }
                $motivo = 'SIN PRESUPUESTO_(PEP)';
                $comentario = 'FALTA DE PRESUPUESTO';
                $nombreUsuario = $this->session->userdata('usernameSession');
                $correo = $this->session->userdata('correo');
                $flgJson = 1;
                if (count($arrayIPParalizados) > 0) {
                    $dataSend = [
                        'itemplan' => json_encode($arrayIPParalizados),
                        'motivo' => $motivo,
                        'flg_activo' => 1,
                        'comentario' => $comentario,
                        'nombreUsuario' => $nombreUsuario,
                        'correo' => $correo,
                        'json' => $flgJson,
                        'fecha' => date("Y-m-d")];
						
					log_message('error', 'data send:'.print_r($dataSend,true));
                    $url = 'https://172.30.5.10:8080/obras2/recibir_par_masivo.php';
					$response = $this->sendDataToURL($url, $dataSend);					
									
                    $motivoError = '';
                    $descripcion = '';
                    $estado = null;
					
                    if (!$response) {
                        $motivoError = 'FALLA EN LA RESPUESTA DEL HOSTING';
						$descripcion = 'OPERACION NO COMPLETADA ERROR EN EL SERVIDOR DEL CLIENTE NO SE LOGRO LA CONEXION';
						$estado = 3;
                    } else {
						if($response->error == EXIT_SUCCESS){
							$motivoError = 'TRAMA COMPLETADA';
							$descripcion = 'OPERACION REALIZADA CON EXITO';
							$estado = 1;				
						}else{
							$motivoError = 'FALLA EN LA RESPUESTA DEL HOSTING';
							$descripcion = 'OPERACION NO COMPLETADA ERROR EN EL SERVIDOR DEL CLIENTE:' . strtoupper($response->mensaje ? $response->mensaje : '');
							$estado = 2;
						}
					}					
					
					$arrayTempEnvio = array();
					$arrayGlobEnvio = array();
                    foreach ($arrayGlobLogTrama as $row) {
						$arrayTempEnvio = array(
							"origen" => $row['origen'],
							"ptr"    => 'PARALIZACION PRESUPUESTO',
                            "itemplan" => $row['itemplan'],
                            "sisego" => $row['sisego'],
                            "fecha_registro" => $row['fecha_registro'],
                            "motivo_error" => $motivoError,
                            "descripcion" => $descripcion,
                            "estado" => $estado
						);
						array_push($arrayGlobEnvio, $arrayTempEnvio);
					}
                    $data = $this->insertBatchLogSigoplus($arrayGlobEnvio);
                    if ($data['error'] == EXIT_SUCCESS) {
                        $listaIPDespara = $this->getIPDesParalizados();
                        if ($listaIPDespara != null) {
                            foreach ($listaIPDespara as $row) {
                                $arrayTempDes = array(
                                    "origen" => 'MODELO TRANFERENCIA WU',
                                    "itemplan" => $row->itemplan,
                                    "sisego" => $row->indicador,
                                    "fecha_registro" => $this->fechaActual(),
                                    "motivo_error" => null,
                                    "descripcion" => null,
                                    "estado" => null,
                                );
                                array_push($arrayIPDespara, $row->itemplan);
                                array_push($arrayGlobLogTramaDespara, $arrayTempDes);
                            }
                        } else {
                            $arrayIPDespara = array();
                        }
                        if (count($arrayIPDespara) > 0) {
                            $dataSend2 = [
                                'itemplan' => json_encode($arrayIPDespara),
                                'motivo' => 'DESPARALIZAR',
                                'flg_activo' => 0,
                                'comentario' => 'DESPARALIZADOS',
                                'nombreUsuario ' => $nombreUsuario,
                                'correo' => $correo,
                                'json' => $flgJson,
                                'fecha' => date("Y-m-d")];
                            $response2 = $this->sendDataToURL($url, $dataSend2);
                            $motivoError = '';
                            $descripcion = '';
                            $estado = null;
							if (!$response2) {
								$motivoError = 'FALLA EN LA RESPUESTA DEL HOSTING';
								$descripcion = 'OPERACION NO COMPLETADA ERROR EN EL SERVIDOR DEL CLIENTE NO SE LOGRO LA CONEXION';
								$estado = 3;
							} else {
								if($response2->error == EXIT_SUCCESS){
									$motivoError = 'TRAMA COMPLETADA';
									$descripcion = 'OPERACION REALIZADA CON EXITO';
									$estado = 1;							
								}else{
									$motivoError = 'FALLA EN LA RESPUESTA DEL HOSTING';
									$descripcion = 'OPERACION NO COMPLETADA ERROR EN EL SERVIDOR DEL CLIENTE:' . strtoupper($response2->mensaje ? $response2->mensaje : '');
									$estado = 2;
								}
							}
							$arrayTempEnvio2 = array();
							$arrayGlobEnvio2 = array();
                            foreach ($arrayGlobLogTramaDespara as $row) {
                                $arrayTempEnvio2 = array(
									"origen" => $row['origen'],
									"ptr"    => 'DESPARALIZACION PRESUPUESTO',
									"itemplan" => $row['itemplan'],
									"sisego" => $row['sisego'],
									"fecha_registro" => $row['fecha_registro'],
									"motivo_error" => $motivoError,
									"descripcion" => $descripcion,
									"estado" => $estado
								);
								array_push($arrayGlobEnvio2, $arrayTempEnvio2);
                            }
                            $data = $this->insertBatchLogSigoplus($arrayGlobEnvio2);
                        }
                    }

                }else{
					log_message('error', 'es 0:');
				}
	            $data ['error']= EXIT_SUCCESS;
	        }else{	
	            $this->db->trans_rollback();
	            throw new Exception('Error execGetGrafos()');
	        }
	
	    }catch(Exception $e){
	        $data['msj'] = 'Ocurrio un problema!';               
	    }
	    return $data;
	}
	
	function   execGetEstMate(){
	    $data['error']= EXIT_ERROR;
	    $data['msj'] = null;
	    try{
	        $this->db->trans_begin();
	        $this->db->query("SELECT getEstMate();");
	        if ($this->db->trans_status() === TRUE) {
	            $this->db->trans_commit();
	            $data['error']= EXIT_SUCCESS;
	        }else{	
	            $this->db->trans_rollback();
	            throw new Exception('Error execGetEstMO()');
	        }
	
	    }catch(Exception $e){
	        $data['msj'] = 'Ocurrio un problema!';             
	    }
	    return $data;
	}

    function   execGetEstMO(){
	    $data['error']= EXIT_ERROR;
	    $data['msj'] = null;
	    try{
	        $this->db->trans_begin();
	        $this->db->query("SELECT getEstMO();");
	        if ($this->db->trans_status() === TRUE) {
	            $this->db->trans_commit();
	            $data['error']= EXIT_SUCCESS;
	        }else{
	            $this->db->trans_rollback();
	            throw new Exception('Error execGetEstMO()');	            
	        }
	
	    }catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    return $data;
	}
	
	

/************************MIGUEL RIOS 13062018****************************/


function   execUpdateSISEGOENM(){
	    $data ['error']= EXIT_ERROR;
	    $data['msj'] = null;
	    try{
	         
	        $this->db->query("SELECT setUpdateSISEGOENM();");
	         /**modificacion para obtener el usuario que realizo el refresh 24.06.2019**/
	            $log_refresh = array('descripcion'    =>  'REFRESH PRESUPUESTO',
	                'fecha_registro' =>  $this->fechaActual(),
	                'idUsuario'     =>  $this->session->userdata('idPersonaSession')
	            );
            $this->db->insert('log_tranferencia_wu', $log_refresh);
            $data ['error']= EXIT_SUCCESS;
	         
	    }catch(Exception $e){
	       $data['msj'] = 'Ocurrio un problema!';
	    }
	    return $data;
	}


/**********************************************************************/

function   execLoadCertificacion(){
	    $data ['error']= EXIT_ERROR;
	    $data['msj'] = null;
	    try{
	        $this->db->trans_begin();
	        $this->db->query("SELECT loadCertificacion();");
	        if ($this->db->trans_status() === TRUE) {
	            $this->db->trans_commit();
	            $data ['error']= EXIT_SUCCESS;
	        }else{
	            $this->db->trans_rollback();
	            throw new Exception('Error loadCertificacion()');
	        }
	
	    }catch(Exception $e){
	        $data['msj'] = 'Ocurrio un problema!';
	    }
	    return $data;
	}
	
	function   execLoadCertificacionMO(){
	    $data ['error']= EXIT_ERROR;
	    $data['msj'] = null;
	    try{
	        $this->db->trans_begin();
	        $this->db->query("SELECT loadCertificacion_diseno();");
	        if ($this->db->trans_status() === TRUE) {
	           $this->db->query("SELECT loadPresupuestoMat();");
    	        if ($this->db->trans_status() === TRUE) {
        	         $this->db->query("SELECT loadPresupuestoMo();");
            	        if ($this->db->trans_status() === TRUE) {
							$this->db->query("SELECT loadCertificacionItemPep2();");
							if ($this->db->trans_status() === TRUE) {
								$this->db->query("SELECT getGrafosByItemplanPep2();");
								if ($this->db->trans_status() === TRUE) {
									$this->db->query("SELECT makeBolsasHojaGestion();");
									if ($this->db->trans_status() === TRUE) {
										$this->db->trans_commit();
										$data ['error']= EXIT_SUCCESS;
									}else{
										$this->db->trans_rollback();
										throw new Exception('Error makeBolsasHojaGestion()');
									}
								}else{
									$this->db->trans_rollback();
									throw new Exception('Error getGrafosByItemplanPep2()');
								}
							}else{
								$this->db->trans_rollback();
								throw new Exception('Error loadCertificacionItemPep2()');
							}
            	        }else{
							$this->db->trans_rollback();
							throw new Exception('Error loadPresupuestoMo()');
						}
    	        }else{
    	            $this->db->trans_rollback();
    	            throw new Exception('Error loadPresupuestoMat()');
    	        }
	        }else{
	            $this->db->trans_rollback();
	            throw new Exception('Error loadCertificacion_diseno()');
	        }
	    }catch(Exception $e){
	        $data['msj'] = 'Ocurrio un problema!';
	    }
	    return $data;
	}

    function   execAutoAprobSirope(){
	    $data ['error']= EXIT_ERROR;
	    $data['msj'] = null;
	    try{
	        $this->db->trans_begin();
	        $this->db->query("SELECT autoAprobDisenoFOWithSirope();");
	        if ($this->db->trans_status() === TRUE) {
	            $this->db->trans_commit();
	            $data ['error']= EXIT_SUCCESS;
	        }else{
	            $this->db->trans_rollback();
	            throw new Exception('Error autoAprobDisenoFOWithSirope()');
	        }
	
	    }catch(Exception $e){
	        $data['msj'] = 'Ocurrio un problema!';
	    }
	    return $data;
	}

	function   execAutoAprobMATBySubPro(){
	    $data ['error']= EXIT_ERROR;
	    $data['msj'] = null;
	    try{
	        $this->db->trans_begin();
	        $this->db->query("SELECT autoPreAprobMatBySubProyecto();");
	        if ($this->db->trans_status() === TRUE) {
	            $this->db->trans_commit();
	            $data ['error']= EXIT_SUCCESS;
	        }else{
	            $this->db->trans_rollback();
	            throw new Exception('Error autoPreAprobMatBySubProyecto()');
	        }
	
	    }catch(Exception $e){
	        $data['msj'] = 'Ocurrio un problema!';
	    }
	    return $data;
	}
	
	public function getIPParalizados()
    {
        $sql = "SELECT po.indicador,pa.itemplan
		          FROM paralizacion pa,
				       planobra po
                 WHERE pa.itemplan = po.itemPlan
				   AND pa.idMotivo = 11
		           AND pa.idUsuario = 265
		           AND pa.comentario = 'FALTA DE PRESUPUESTO'
		           AND pa.flg_activo = 1
		           AND pa.fechaReactivacion IS NULL
                   and pa.send = 1;";

        $result = $this->db->query($sql);
        return $result->result();
    }

    public function getIPDesParalizados()
    {
        $sql = "SELECT po.indicador,pa.itemplan
		          FROM paralizacion pa,
				       planobra po
                 WHERE pa.itemplan = po.itemPlan
				   AND pa.idMotivo = 11
		           AND pa.idUsuario = 265
		           AND pa.comentario = 'FALTA DE PRESUPUESTO'
		           AND pa.flg_activo = 0
		           AND pa.fechaReactivacion IS NOT NULL
                   and pa.send = 1;";

        $result = $this->db->query($sql);
        return $result->result();
    }

    public function sendDataToURL($url, $dataSend)
    {
        $data = array();
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataSend);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);
        } catch (Exception $e) {
            //insert log
            log_message('error', 'catch sendDataToURL');
        }
        return json_decode($response);
    }

    public function insertBatchLogSigoplus($arrayInsert)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert_batch('log_tramas_sigoplus', $arrayInsert);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al insertar el log_tramas_sigoplus.');
            } else {
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se insert&oacute; correctamente!';
                $this->db->trans_commit();
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
    
    function   execLoadActivaciones(){
        $data ['error']= EXIT_ERROR;
        $data['msj'] = null;
        try{
            $this->db->trans_begin();
            $this->db->query("SELECT makeDataToReportActivaciones();");
            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                $data ['error']= EXIT_SUCCESS;
            }else{
                $this->db->trans_rollback();
                throw new Exception('Error makeDataToReportActivaciones()');
            }
    
        }catch(Exception $e){
            $data['msj'] = 'Ocurrio un problema!';
        }
        return $data;
    }
    
	function insertReporte($fechaReg) {
		$this->db->delete('reporte_planobra');
		$comillas = '"';
		if ($this->db->trans_status() === TRUE) {
			$sql = "LOAD DATA LOCAL INFILE 'download/planobra/planobraCSV.csv' REPLACE INTO TABLE reporte_planobra
					LINES STARTING BY '".$comillas."'";
			$this->db->query($sql);
			if ($this->db->trans_status() === TRUE) {
				$arrayDataPo = array (
										'cant_po'  		 => $this->getCountPo(),
										'cant_rep'       => $this->getCountReportePO(),
										'fecha_registro' => $fechaReg,
										'flg_tipo'       => 1
									);
				$this->db->insert('reporte_transferencia', $arrayDataPo);					
				if ($this->db->trans_status() === TRUE) {
					$this->db->delete('reporte_detalleplan');
					if ($this->db->trans_status() === TRUE) {
					    $sql2 = "LOAD DATA LOCAL INFILE 'download/detalleplan/detalleplanCSV.csv' REPLACE INTO TABLE reporte_detalleplan
								 LINES STARTING BY '".$comillas."'";
						$this->db->query($sql2);
						if ($this->db->trans_status() === TRUE) {
							$arrayDataDp = array(
													'cant_po'  		 => $this->getCountDetallePlan(),
													'cant_rep'       => $this->getCountReporteDp(),
													'fecha_registro' => $fechaReg,
													'flg_tipo'       => 2
												);
							$this->db->insert('reporte_transferencia', $arrayDataDp);				 
						}					
					}
				}	
			}	
		}
	}

	function getCountPo() {
		$sql = "SELECT COUNT(1) countPo 
				  FROM planobra";
		$result = $this->db->query($sql);
		return $result->row_array()['countPo'];
	}

	function getCountReportePO() {
		$sql = "SELECT COUNT(1) countReporte
				  FROM reporte_planobra re, 
					   planobra po 
				 WHERE re.itemplan = po.itemplan";
		$result = $this->db->query($sql);
		return $result->row_array()['countReporte'];		 
	}
	
	function getCountDetallePlan() {
		$sql = "  SELECT COUNT(1) countRepDp
    			    FROM ( SELECT itemplan, 
    			                  poCod 
    			             FROM detalleplan 
    			            GROUP BY itemplan, poCod )t";
		$result = $this->db->query($sql);
		return $result->row_array()['countRepDp'];		
	}

	function getCountReporteDp() {
		$sql = "SELECT COUNT(1) countRepDp 
				  FROM reporte_detalleplan rp,
					   detalleplan         dp
				 WHERE rp.itemplan = dp.itemplan
				   AND rp.po       = dp.poCod";
		$result = $this->db->query($sql);
		return $result->row_array()['countRepDp'];		
	}

}