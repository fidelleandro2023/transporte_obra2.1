<?php
class M_bandeja_reg_cv_negocio extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}

	function   getInfoConstructora($ruc){
	    $Query = " SELECT * FROM constructora_cv WHERE ruc = ?" ;
	    $result = $this->db->query($Query,array($ruc));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
	function getInfoItemCV($itemplan){
	    $Query= "SELECT * 
                    FROM    planobra po, 
                            planobra_detalle_cv podc
                    WHERE	po.itemplan =  podc.itemplan 
                    AND	    po.itemplan	= ?";
	   $result = $this->db->query($Query,array($itemplan));
	   if($result->row() != null) {
	       return $result->row_array();
	   } else {
	       return null;
	   }
	}	
	
	function insertItemplanCVNegocio($idProy, $idSubproy, $idEstadoPlan, $eelec, $fechaInicio, $nombreProyecto, $indicador, $uip, $cordx, $cordy, $cantidadTroba, $departamento,
	    $provincia, $distrito, $idTipoUrba, $nombreUrba, $idTipoVia, $direccion, $numero, $manzana, $lote,
	    $blocke, $num_pisos, $num_depa, $num_depa_habi, $avance, $fec_termino, $observacion, $ruc, $nombre_constru, $contacto_1, $telefono_1_1, $telefono_1_2,
		$email_1, $contacto_2, $telefono_2_1, $telefono_2_2, $email_2, $accion, $itemplan, $estado_edifi, $idCentral, $competencia, $prioridad, $idEECC, $tipoSubProy, $fase, $operador, 
		$paquetizado_fg, $idCentralPqt, $costo_unitario_mat, $costo_unitario_mo, $idZonal=null){
	        
	        $data['error'] = EXIT_ERROR;
	        $data['msj']   = null;
	        try{
	            
	            $this->db->trans_begin();
	            $idProvincia=1;
	            $idDepartamento=1;
	            //$hasAdelanto='0';
	            	                        	            
	            $dataInsert = array(
                    "itemPlan"         =>  $itemplan,
	                "nombreProyecto"   =>  strtoupper($nombreProyecto),
	                "coordX"           =>  $cordx,
	                "coordY"           =>  $cordy,
	                "indicador"        =>  $indicador,
	                "cantidadTroba"    =>  intval($cantidadTroba),
	                "uip"              =>  intval($uip),
	                "fechaInicio"      =>  $fechaInicio,
	                "idEstadoPlan"     =>  intval($idEstadoPlan),
	                "idFase"           =>  intval($fase),
	                "idCentral"        =>  intval($idCentral),
	                "idEmpresaElec"    =>  intval($eelec),
	                "idProvincia"      =>  intval($idProvincia),
	                "idDepartamento"   =>  intval($idDepartamento),
	                "idSubProyecto"    =>  intval($idSubproy),
	                "idZonal"          =>  intval($idZonal),
	                "idEmpresaColab"   =>  intval($idEECC),
					"idEmpresaColabDiseno" =>  intval($idEECC),
					"has_cotizacion"   =>  '0',
					"hasAdelanto"      =>  '0',
					"paquetizado_fg"   => $paquetizado_fg,
					"idCentralPqt"     => $idCentralPqt,
					"costo_unitario_mat" => $costo_unitario_mat,
					"costo_unitario_mo"  => $costo_unitario_mo,
					"cantFactorPlanificado" => strtoupper($num_depa),
					"idPqtTipoFactorMedicion" => 5
	            );
	            
                $this->db->insert('planobra', $dataInsert);
	            if($this->db->affected_rows() != 1) {
	                $this->db->trans_rollback();
	                throw new Exception('Error al insertar el plan de obra');
	            }else{
	                
	                $dataInsertCv = array(
						"itemplan"              =>  $itemplan,	                   
	                    "departamento"          =>  strtoupper($departamento),
	                    "provincia"             =>  strtoupper($provincia),
	                    "distrito"              =>  strtoupper($distrito),
	                    "coordenada_x"          =>  $cordx,
	                    "coordenada_y"          =>  $cordy,
	                    "idSubProyecto"         =>  intval($idSubproy),
	                    "tipo_urb_cchh"         =>  $idTipoUrba,
	                    "nombre_urb_cchh"       =>  strtoupper($nombreUrba),
	                    "tipo_via"              =>  $idTipoVia,
	                    "direccion"             =>  strtoupper($direccion),
	                    "numero"                =>  strtoupper($numero),
	                    "manzana"               =>  strtoupper($manzana),
	                    "lote"                  =>  strtoupper($lote),
	                    "nombre_proyecto"       =>  strtoupper($nombreProyecto),
	                    "blocks"                =>  strtoupper($blocke),
	                    "pisos"                 =>  strtoupper($num_pisos),
	                    "depa"                  =>  strtoupper($num_depa),
	                    "depa_habitados"        =>  strtoupper($num_depa_habi),
	                    "avance"                =>  strtoupper($avance),
	                    "fec_termino_constru"   =>  $fec_termino,
	                    "observaciones"         =>  strtoupper($observacion),
	                    "ruc_constructora"      =>  strtoupper($ruc),
	                    "nombre_constructora"   =>  strtoupper($nombre_constru),
	                    "contacto_1"            =>  strtoupper($contacto_1),
	                    "telefono_1_1"          =>  $telefono_1_1,
	                    "telefono_1_2"          =>  $telefono_1_2,
	                    "email_1"               =>  strtoupper($email_1),
	                    "contacto_2"            =>  strtoupper($contacto_2),
	                    "telefono_2_1"          =>  $telefono_2_1,
	                    "telefeono_2_2"         =>  $telefono_2_2,
	                    "email_2"               =>  strtoupper($email_2),
	                    "usuario_edit"          =>  $this->session->userdata('userSession'),
	                    "fecha_edit"            =>  date("Y-m-d h:m:s"),
	                    "estado_edificio"       => $estado_edifi,
	                    "competencia"           => $competencia,
	                    "prioridad"             => $prioridad,
						"tipo_subpro"           => $tipoSubProy,
						"estado_aprob"          => 0,
						"operador"              => $operador
	                );
	                
					$this->db->insert('planobra_detalle_cv', $dataInsertCv);
	                if($this->db->affected_rows() != 1) {
	                    $this->db->trans_rollback();
	                    throw new Exception('Error al insertar el plan de obra CV');
	                }else{                            
                        //IF ACCION $accion  = 1 INSERT ELSE UPDATE
                        if($accion == '1'){//INSERT
                            $dataInsert = array(
                                "ruc"          =>  $ruc,
                                "nombre"       =>  strtoupper($nombre_constru),
                                "contacto_1"   =>  strtoupper($contacto_1),
                                "telefono_1_1" =>  strtoupper($telefono_1_1),
                                "telefono_2_1" =>  strtoupper($telefono_1_2),
                                "email_1"      =>  strtoupper($email_1),
                                "contacto_2"   =>  strtoupper($contacto_2),
                                "telefono_1_2" =>  strtoupper($telefono_2_1),
                                "telefono_2_2" =>  strtoupper($telefono_2_2),
                                "email_2"      =>  strtoupper($email_2)
                            );
                            
                            $this->db->insert('constructora_cv', $dataInsert);
                            if($this->db->affected_rows() != 1) {
                                throw new Exception('Error al insertar el plan de obra');
                            }else{
                                $this->db->trans_commit();
                                $data['error']    = EXIT_SUCCESS;
                                $data['msj']      = 'Se inserto correctamente!';
                            }
                        }else if($accion == '0'){
                            $dataUpdate = array(
                                "nombre"       =>  strtoupper($nombre_constru),
                                "contacto_1"   =>  strtoupper($contacto_1),
                                "telefono_1_1" =>  strtoupper($telefono_1_1),
                                "telefono_2_1" =>  strtoupper($telefono_1_2),
                                "email_1"      =>  strtoupper($email_1),
                                "contacto_2"   =>  strtoupper($contacto_2),
                                "telefono_1_2" =>  strtoupper($telefono_2_1),
                                "telefono_2_2" =>  strtoupper($telefono_2_2),
                                "email_2"      =>  strtoupper($email_2)
                            );
                            
                            $this->db->where('ruc', $ruc);
                            $this->db->update('constructora_cv', $dataUpdate);
                            if($this->db->trans_status() === FALSE) {
                                $this->db->trans_rollback();
                                throw new Exception('Error al modificar el updateEstadoPlanObra');
                            }else{	                                
                                $this->db->trans_commit();
                                $data['error']    = EXIT_SUCCESS;
                                $data['msj']      = 'Se inserto correctamente!';
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
	
	function   existTablaBucle($itemplan){
	    $Query = " SELECT count(1) as cont FROM cv_itemplan_bucle WHERE itemplan = ? " ;
	    $result = $this->db->query($Query,array($itemplan));
	    if($result->row() != null) {
	        return $result->row_array()['cont'];
	    } else {
	        return null;
	    }
	}
	
	function adjudicarItemplanFromCV($itemplan,$subproyecto, $idFechaPreAtencionCoax, $idFechaPreAtencionFo, $idEstadoPlan){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        
	        $has_fo      = $this->session->userdata('has_fo');
	        $has_coax    = $this->session->userdata('has_coax');
	        
	        if($has_fo == 1){
	            $arrayData = array (
	                'itemPlan'                => $itemplan,
	                'idEstacion'              => ID_ESTACION_FO,
	                'fecha_prevista_atencion' => $idFechaPreAtencionFo,
	                'fecha_adjudicacion'	  => date("Y-m-d h:m:s"),
	                'estado'                  => ESTADO_PLAN_DISENO,
	                'usuario_adjudicacion'    => (($this->session->userdata('userSession') != null) ? $this->session->userdata('userSession') : 'AUTOMATICO')
	            );
	            
	            $this->db->insert('pre_diseno', $arrayData);
	            if($this->db->affected_rows() != 1) {
	                $this->db->trans_rollback();
	                throw new Exception('Error al modificar el updateEstadoPlanObra');
	            }
	        }
	        
	        if($has_coax == 1) {
	            $idEstacion = ID_ESTACION_COAXIAL;
	            $arrayData2 = array (
	                'itemPlan'                => $itemplan,
	                'idEstacion'              => ID_ESTACION_COAXIAL,
	                'fecha_prevista_atencion' => $idFechaPreAtencionCoax,
	                'fecha_adjudicacion'	  => date("Y-m-d h:m:s"),
	                'estado'                  => ESTADO_PLAN_DISENO,
	                'usuario_adjudicacion'    => (($this->session->userdata('userSession') != null) ? $this->session->userdata('userSession') : 'AUTOMATICO')
	            );
	            
	            $this->db->insert('pre_diseno', $arrayData2);
	            if($this->db->affected_rows() != 1) {
	                $this->db->trans_rollback();
	                throw new Exception('Error al modificar el updateEstadoPlanObra');
	            }
	        }
	        
	        if($this->db->trans_status() === FALSE) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al modificar el updateEstadoPlanObra');
	        }else{
	            
	            $dataUpdate = array(
	                "estado_aprob" =>  ESTADO_CV_APROBADO,
	                "usua_aprob"   =>  $this->session->userdata('userSession'),
	                "fec_aprob"    =>  date("d/m/Y H:i:s")
	            );
	            
	            $this->db->where('itemplan', $itemplan);
	            $this->db->update('planobra_detalle_cv', $dataUpdate);
	            if($this->db->trans_status() === FALSE) {
	                $this->db->trans_rollback();
	                throw new Exception('Error al modificar el updateEstadoPlanObra');
	            }else{
	                if($idEstadoPlan == ESTADO_PLAN_PRE_REGISTRO || $idEstadoPlan  == ESTADO_PLAN_PRE_DISENO){
	                           $idEstadoPlan  =   ESTADO_PLAN_DISENO;
	                }
	              
	               $dataUpdate = array(
	                    "idEstadoPlan"         => $idEstadoPlan
	                );
	                
	                $this->db->where('itemPlan', $itemplan);
	                $this->db->update('planobra', $dataUpdate);
	                if($this->db->trans_status() === FALSE) {
	                    $this->db->trans_rollback();
	                    throw new Exception('Error al modificar el updateEstadoPlanObra');
	                }else{
	                    
	                    $this->db->trans_commit();
	                    $data['error']    = EXIT_SUCCESS;
	                    $data['msj']      = 'Se inserto correctamente!';
	                }
	            }	            
	        }
	        
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
	
	function   countFOAndCoaxByItemplan($itemplan){
	    $Query = 'SELECT DISTINCT
                            	count(DISTINCT CASE WHEN idEstacion = 2 THEN 1 ELSE NULL END) as coaxial,
                            	count(DISTINCT CASE WHEN idEstacion = 5 THEN 1 ELSE NULL END) as fo
                            	FROM planobra po, subproyecto sp, subproyectoestacion se, estacionarea ea, central c, estadoplan ep, zonal z, empresacolab ec
            	WHERE	ea.idEstacion IN (5,2)
            	AND		po.idCentral = c.idCentral
            	AND 	c.idZonal = z.idZonal
            	AND 	c.idEmpresacolab = ec.idEmpresaColab
            	AND		se.idEstacionArea = ea.idEstacionArea
            	AND		sp.idSubProyecto = se.idSubProyecto
            	AND		po.idSubProyecto = sp.idSubProyecto
            	and 	po.idEstadoPlan = ep.idEstadoPlan
            	AND 	po.itemplan = ?';
	    $result = $this->db->query($Query,array($itemplan));
	    $this->db->trans_complete();
	    
	    if($result->row() != null) {	       
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
	function updateCVToObra($itemplan){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $dataUpdate = array(
	            "idEstadoPlan" => ESTADO_PLAN_EN_OBRA
	        );
	        
	        $this->db->where('itemPlan', $itemplan);
	        $this->db->update('planobra', $dataUpdate);
	        
	        if($this->db->trans_status() === FALSE) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al modificar el updateEstadoPlanObra');
	        }else{
	            
	            $dataUpdate = array(
	                "estado_aprob" =>  ESTADO_CV_APROBADO,
	                "usua_aprob"   =>  $this->session->userdata('userSession'),
	                "fec_aprob"    =>  date("d/m/Y H:i:s")
	            );
	            
	            $this->db->where('itemplan', $itemplan);
	            $this->db->update('planobra_detalle_cv', $dataUpdate);
	            
	            if($this->db->trans_status() === FALSE) {
	                $this->db->trans_rollback();
	                throw new Exception('Error al modificar el updateEstadoPlanObra');
	            }else{
    	            $this->db->trans_commit();
    	            $data['error']    = EXIT_SUCCESS;
    	            $data['msj']      = 'Se Actualizo correctamente!';
	            }
	        }
	        
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
	
	function   getAllMovimientosCV($itemplan, $mes){
	    $Query = " SELECT * FROM planobra_detalle_cv_log 
	               WHERE itemplan = ?               
                   AND MONTH(fecha_registro) = ?
                   ORDER BY fecha_registro DESC" ;
	    $result = $this->db->query($Query,array($itemplan, $mes));
	    return $result;
	}
	
	function   getAllMovimientosCVPorMes($itemplan){
	    $Query = "SELECT tb.*, (CASE WHEN mes = 1 THEN 'ENERO' 
				   WHEN mes = 2 THEN 'FEBRERO' 
                   WHEN mes = 3 THEN 'MARZO' 
                   WHEN mes = 4 THEN 'ABRIL' 
                   WHEN mes = 5 THEN 'MAYO' 
                   WHEN mes = 6 THEN 'JUNIO' 
                   WHEN mes = 7 THEN 'JULIO' 
                   WHEN mes = 8 THEN 'AGOSTO' 
                   WHEN mes = 9 THEN 'SEPTIEMBRE' 
                   WHEN mes = 10 THEN 'OCTUBRE' 
                   WHEN mes = 11 THEN 'NOVIEMBRE' 
                   WHEN mes = 12 THEN 'DICIEMBRE' ELSE NULL END) as desc_mes 
FROM (SELECT DISTINCT itemplan, MONTH(fecha_registro) as mes, count(1) as cont,
                    (CASE WHEN (select EXTRACT(MONTH FROM fecha_termino_obra) as mes_termino
                    from planobra_detalle_cv_log 
                    where  itemplan = '".$itemplan."' 
                    order by fecha_registro desc limit 1) =  MONTH(fecha_registro) THEN 1 ELSE 0 END) as same 
                    FROM planobra_detalle_cv_log where itemplan = '".$itemplan."' 
                    GROUP BY MONTH(fecha_registro)
                    ORDER BY fecha_registro ) as tb;" ;
	    $result = $this->db->query($Query,array());
	    return $result;
	}	

	function getIdSubProyectoCv($subProyectoDesc, $idTipoSub) {
		$sql = "SELECT idSubProyecto,
		               subProyectoDesc
				  FROM subproyecto 
				 WHERE subProyectoDesc like ?
				   AND idTipoSubProyecto = ?";
		$result = $this->db->query($sql,array('%'.$subProyectoDesc.'%', $idTipoSub));
		return $result->row_array();
	}
	
}