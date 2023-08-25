<?php
class M_porcentaje extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
    function ListarEstacion($id){
    	$Query="SELECT DISTINCT(t.estacionDesc),
    				t.idEstacion,
    				p.itemPlan,
    				idEstadoPlan,
    				p.indicador,
    				(SELECT c.jefatura 
    				   FROM central c
    				  WHERE c.idCentral = p.idCentral)jefatura,
    				(SELECT empresaColabDesc 
    				   FROM empresacolab ep
    				  WHERE ep.idEmpresaColab = (SELECT c.idEmpresaColab 
    											   FROM central c 
    											  WHERE idCentral = p.idCentral))descEmpresaColab,
    				(SELECT c.jefatura 
    				   FROM central c
    				  WHERE c.idCentral = p.idCentral)jefatura,
    				(SELECT sp.idProyecto 
    				   FROM subproyecto sp 
    				  WHERE sp.idSubProyecto = p.idSubProyecto)idProyecto,
    				(SELECT spr.idTipoPlanta 
    				   FROM subproyecto spr 
    				  WHERE spr.idSubProyecto = p.idSubProyecto)idTipoPlanta,
    				(SELECT COUNT(1)  
    				  FROM switch_formulario
    				 WHERE idSubProyecto = p.idSubProyecto
    				   AND idZonal       = p.idZonal
    				   AND flg_tipo      = ".FLG_TIPO_FORM_SISEGO.")countSwitchForm,
    				(SELECT COUNT(1)  
    				   FROM switch_formulario
    				  WHERE idSubProyecto = p.idSubProyecto
    					AND idZonal       = p.idZonal
    					AND flg_tipo      = ".FLG_TIPO_FORM_OB_PUBLICAS.")countSwitchObPublicas,   	  
    				p.idSerieTroba,
    				p.idSubProyecto,
    				CASE WHEN fechaPreLiquidacion < DATE('2018-09-01') THEN 0 ELSE 1 END AS flgFecha,
    				CASE WHEN NOW() < fechaPreLiquidacion + INTERVAL 24 HOUR OR fechaPreLiquidacion IS NULL THEN 0 ELSE 1 END AS flgHoras,
					p.flg_edit_po
    			FROM planobra p LEFT JOIN subproyectoestacion s on p.idSubProyecto=s.idSubProyecto 
    			                LEFT JOIN estacionarea e on s.idEstacionArea=e.idEstacionArea 
    							LEFT JOIN estacion t on e.idEstacion=t.idEstacion 
    			WHERE p.itemPlan = '".$id."' 
    			AND t.idEstacion!=1";
    	$result = $this->db->query($Query,array());
    	//_log($this->db->last_query());
    	return $result;	
    }
function ActividadEstacion($idEstacion){
	$Query="SELECT DISTINCT
	               s.id_actividad,
				   s.nombre,
				   s.id_subactividad 
			  FROM actividad a 
			join subactividad s 
				on a.id_actividad=s.id_actividad 
			WHERE a.id_estacion=".$idEstacion;
	$result = $this->db->query($Query,array());	 
	return $result;

}
function ActividadItemPlan($id,$idSubactividad){
	$Query="SELECT *  
	          FROM planobra_actividad 
			 WHERE id_planobra='".$id."' 
			   AND id_subactividad=".$idSubactividad;

	$result = $this->db->query($Query,array());	 
	return $result->row_array();
}
function Porcentaje($idActividad) {
	$Query="SELECT sum(porcentaje) valor 
			  FROM agenda 
			 WHERE id_planobra_actividad=".$idActividad."
			   AND estado_l = 1";
	$result = $this->db->query($Query,array());
		if($result->row() != null) {    
			return $result->row_array();
		}else {
			return 0;
		}
	}

	function getIdPreDisenio($itemPlan, $idEstacion) {
		$query = "SELECT idpre_diseno 
			        FROM pre_diseno
			       WHERE itemPlan   = '".$itemPlan."'
			         AND idEstacion = '".$idEstacion."' 
			         limit 1"; 	
		$result = $this->db->query($query, array());
		if($result->row() != null) {
			return $result->row_array();
		}else {
			return 0;
		}
	}

	function insertPreDisenioCord($arrayInsert) {
		$this->db->insert('pre_diseno', $arrayInsert);
	}

	function updateFOCOXY($x, $y, $idPreDisenio) {	
		$array=array(
			'coordX' => $x,
			'coordY' => $y
		);
		$this->db->where('idpre_diseno', $idPreDisenio);
		$this->db->update('pre_diseno' , $array);
	}

	function insertXY($x,$y, $itemPlan) {	
		$array=array(
			'coordX' => $x,
			'coordY' => $y
		);
		$this->db->where('itemplan', $itemPlan);
		$this->db->update('planobra', $array);
	}
	
	function ingresarFlagEvidencia($itemPlan, $flg, $idEstacion) {
		$array=array(
			'flg_evidencia' => $flg
		);
		$this->db->where('itemplan'  , $itemPlan);
		$this->db->where('idEstacion', $idEstacion);
		$this->db->update('itemplanestacionavance', $array);
	}

	function ingresarSerieTroba($itemPlan, $serieTroba) {
		$this->db->where('itemplan' , $itemPlan);
		$this->db->update('planobra', array('idSerieTroba' => $serieTroba));
		
		$this->db->where('idSerieTroba' , $serieTroba);
		$this->db->update('serie_troba', array('flgUtilizado' => 1));

        return array("error" => EXIT_SUCCESS);
	}

	function validarSerieTroba($itemPlan, $idSerieTroba) {
		$query = "SELECT idSerieTroba
		            FROM planobra 
				   WHERE itemplan = '".$itemPlan."'";
		$result = $this->db->query($query);
		if($result->row() != null) {    
			return $result->row()->idSerieTroba;
		}else {
			return null;
		}			 
	}

	function cambiarSerieTrobaAnterior($idSerieTrobaAn, $flgUtilizado) {
		$this->db->where('idSerieTroba' , $idSerieTrobaAn);
		$this->db->update('serie_troba', array('flgUtilizado' => $flgUtilizado));
	}
	function ValidarBotonTerminar($idSubProyecto,$idZonal){
		$Query="SELECT id_planobra_config_flujo 
		          from planobra_config_flujo 
				 where idSubProyecto = ".$idSubProyecto."
				   and idZonal       = ".$idZonal;
		$result=$this->db->query($Query,array());
		if($result->row()!=null){
			return 1;
		}else{
			return 0;
		}
	}
	function BuscarAvance($itemplan){
		$Query="SELECT idEstacion,
		               porcentaje 
				  FROM itemplanestacionavance 
				 WHERE itemplan='".$itemplan."'";
		$result=$this->db->query($Query,array());
		if($result->row()!=null){	
			return $result;
		}
		else{
			return 0;
		}
	}

	///////////////////////////////////////////////////////////////////////7

	function getPorcentajeItPlanAvance($itemPlan, $idEstacion) {
		$sql = "SELECT porcentaje AS porcent 
				  FROM itemplanestacionavance 
			     WHERE itemplan   = '".$itemPlan."'
				   AND idEstacion = '".$idEstacion."'";
		$result = $this->db->query($sql);
		if($result->row() != null) {
			return $result->row()->porcent;
		} else {
			return 0;
		}
	}

	function validarItemPlanEstacionAvance($itemPlan, $idEstacion) {
		$sql = "SELECT count(1)count 
				  FROM itemplanestacionavance
				 WHERE itemplan   = '".$itemPlan."'
				   AND idEstacion = ".$idEstacion;
		$result = $this->db->query($sql);		  
		return $result->row()->count; 
	}

	function insertItemPlanEstacionAvance($arrayData) {
		$this->db->insert('itemplanestacionavance', $arrayData);
		if($this->db->affected_rows() == 0) {
			return 0;
		} else {
			return 1;
		}
		// if($this->db->affected_rows() != 1) {
			
		// } 
	}

	function updateItemPlanEstacionAvance($arrayData, $itemPlan, $idEstacion) {
		$sql = "SELECT idItemplanEstacion
				  FROM itemplanestacionavance
				 WHERE itemplan = '".$itemPlan."'
				   AND idEstacion = ".$idEstacion." 
					limit 1";
		
		$result = $this->db->query($sql);

		$this->db->where('idItemplanEstacion', $result->row()->idItemplanEstacion);
		$this->db->update('itemplanestacionavance', $arrayData);
		if($this->db->affected_rows() == 0) {
			return 0;
		} else {
			return 1;
		}
	}

	function countActivadByEstacion($idEstacion) {
		$sql = "SELECT COUNT(1) count
				  FROM actividad a 
					   join subactividad s 
					     on a.id_actividad  = s.id_actividad
				  WHERE id_estacion = ".$idEstacion;
		$result = $this->db->query($sql);
		return 	$result->row()->count;	  
	}

	function getValidFechaEjucion($itemPlan) {
		$sql = "SELECT fechaEjecucion 
				  FROM planobra 
				 WHERE itemplan = '".$itemPlan."'";
		$result = $this->db->query($sql);
		return $result->row()->fechaEjecucion;		 
	}

	function updateFechaEjecucion($itemPlan) {
		$array = array('fechaEjecucion' => date('Y-m-d'),
	                   'idEstadoPlan'   => ID_ESTADO_PRE_LIQUIDADO);
		$this->db->where('itemplan', $itemPlan);
		$this->db->update('planobra', $array);
	}

	// function getCuadrillas($idZonal, $idEstacion, $itemPlan) {
	// 	$sql = "SELECT id_usuario,
	// 			       nombre AS nombreCuadrilla,
	// 				   COALESCE((SELECT 1 
	// 							   FROM itemplanestacionavance ic
	// 							  WHERE ic.itemplan   = '".$itemPlan."'
	// 								AND ic.idEstacion = '".$idEstacion."'	
	// 								AND ic.id_usuario_cuadrilla = u.id_usuario), 0) AS flgCuadrilla 
	// 			  FROM usuario u 
	// 			 WHERE u.id_perfil IN (12)
	// 			   AND u.zonas IN($idZonal)";
	// 	$result = $this->db->query($sql);	
	// 	return $result->result();
	// }

	function getCuadrilla($idZonal, $itemPlan, $idEstacion, $idPlanobraActividad) {
		$idEcc = $this->session->userdata('eeccSession');
		$sql="SELECT DISTINCT cu.idCuadrilla,
					 cu.idEecc,
					 cu.idZonal,
					 cu.idEstacion,
					 cu.descripcion,
					 cu.estado,
					 cu.id_usuario,
					 COALESCE((SELECT 1 
							     FROM agenda a
							    WHERE a.id_cuadrilla = cu.idCuadrilla
								  AND id_planobra_actividad = '".$idPlanobraActividad."'
								  limit 1),0)AS flg_cuad_act,
					 COALESCE((SELECT 1 
								 FROM itemplanestacionavance iea
								WHERE iea.itemplan   = '".$itemPlan."'
								  AND iea.idEstacion = '".$idEstacion."'
								  AND iea.id_cuadrilla = cu.idCuadrilla),0)AS flg_cuad_estacion              
				FROM cuadrilla cu
			   WHERE CASE WHEN ".$idZonal." IN(8,9,10,11,12) THEN idZonal = 8
                          ELSE idZonal = ".$idZonal." END 
			     AND idEecc  = CASE WHEN ".$idEcc." =0 or ".$idEcc."=6 THEN idEecc 
				 				    ELSE ".$idEcc." END
				 AND estado  = 1
				 ORDER BY cu.descripcion ASC";
		$result = $this->db->query($sql);
		return $result->result();		 
	}

	function getPorcentajeEstacion($itemPlan, $idEstacion) {
		$sql = "SELECT porcentaje,
		               comentario
				  FROM itemplanestacionavance iea
				 WHERE iea.itemplan   = '".$itemPlan."'
				   AND iea.idEstacion = '".$idEstacion."'";
		$result = $this->db->query($sql);
		if($result->row()) {
			return $result->row_array();
		} else {
			return 0;
		}
	}

	function getPorcentajeActividad($itemPlan, $idEstacion, $idActividad) {
		$sql = "SELECT a.porcentaje,
		               a.conversacion 
				  FROM agenda a
				 WHERE id_planobra_actividad IN (SELECT id_planobra_actividad
												   FROM planobra_actividad poa
												  WHERE poa.id_subactividad = ".$idActividad."
													AND estado = 1  
													AND poa.id_planobra  = '".$itemPlan."'
													AND (SELECT EXTRACT(year FROM poa.fecha)) = (SELECT EXTRACT(year FROM NOW()))
													AND poa.id_actividad = (SELECT id_actividad 
																				FROM actividad
																			WHERE id_estacion = ".$idEstacion.")
												)
					AND estado_l = 1";
		$result = $this->db->query($sql);
		if($result->row_array()) {
			return $result->row_array();
		} else {
			return 0;
		}
		
	}

	function validarPlanObraActividad($itemPlan, $idEstacion, $idActividad) {
		$sql = "SELECT count(1) count
				  FROM planobra_actividad
			     WHERE id_planobra = '".$itemPlan."' 
				   AND id_actividad = (SELECT id_actividad 
                                         FROM actividad
                                        WHERE id_estacion = ".$idEstacion.")
				   AND id_subactividad = ".$idActividad;
		$result = $this->db->query($sql);
		return $result->row()->count;		   
	}

	function insertDataPlanObraActividad($arrayData) {
		$this->db->insert('planobra_actividad', $arrayData);
		return $this->db->insert_id();
	}

	function getIdActividadJorge($idEstacion) {
		$sql = "SELECT id_actividad 
				  FROM actividad
				 WHERE id_estacion = ".$idEstacion;
		$result = $this->db->query($sql);
		return $result->row()->id_actividad;
	}

	function updateDataPlanObraActividad($arrayData, $itemPlan, $idEstacion, $id_planobra_actividad) {
		$this->db->where('id_planobra', $itemPlan);
		$this->db->where('id_planobra_actividad', $id_planobra_actividad);
		$this->db->where('estado', 1);
		$this->db->where('id_actividad',"(SELECT id_actividad 
											FROM actividad
										   WHERE id_estacion = ".$idEstacion.")");
		$this->db->update('planobra_actividad', $arrayData);
		if($this->db->affected_rows() == 0) {
			return 0;
		} else {
			return 1;
		}

	}

	function insertAgenda($array) {
		$sql = "SELECT MAX(id_agenda)+1 AS idAgenda 
				  FROM agenda";
		$result = $this->db->query($sql);	
		$array['id_agenda'] = $result->row()->idAgenda;

		$this->db->insert('agenda', $array);
	}

	function updateAgenda($array, $id, $arrayInsert) {
		$this->db->where('id_planobra_actividad', $id);
		$this->db->where('estado_l', 1);
		$this->db->update('agenda', $array);
		if($this->db->affected_rows() == 0) {
			$this->insertAgenda($arrayInsert);
			if($this->db->affected_rows() == 0) {
				return 0;
			} else {
				return 1;
			}
		} else {
			return 1;
		}
	}

	function insertLogPorcentaje($arrayData) {
		$this->db->insert('log_porcentaje_cuadrilla', $arrayData);

		if($this->db->affected_rows() == 0) {
			return 0;
		} else {
			return 1;
		}
	}

	function getPorcetajeByestacionAct($itemPlan, $idEstacion) {
		$sql = "SELECT ROUND((SUM(porcentaje)*100)/(COUNT(1)*100),2)porcentajeE
				  FROM agenda 
				 WHERE id_planobra_actividad IN(
												SELECT id_planobra_actividad
												FROM planobra_actividad 
												WHERE id_planobra = '".$itemPlan."'
												  AND id_actividad = (SELECT id_actividad 
																		FROM actividad 
																		WHERE id_estacion = ".$idEstacion.")
												  AND estado = 1                     
												)
					AND estado_l = 1";
		$result = $this->db->query($sql);
		return $result->row()->porcentajeE;			
	}

	function updateEstadoPlanObra($itemPlan, $arrayData) {
		$this->db->where('itemplan', $itemPlan);
		$this->db->update('planobra', $arrayData);
		if($this->db->affected_rows() < 1) {
			return 0;
		} else {
			return 1;
		}
	}
	
	function TerminarObra($itemplan, $fecha){
		$Query="UPDATE planobra 
		           SET idEstadoPlan=4,
				       fechaEjecucion = '".$fecha."' 
				 WHERE itemplan       = '".$itemplan."'";
		$this->db->query($Query);

		$avance=$this->BuscarAvance($itemplan);
		if($avance=='0'){
			$estacion=$this->ListarEstacion($itemplan);
			foreach ($estacion->result() as $row) {			
				$post_data = array(
		        'idItemplanEstacion'=>'',        
		        'itemplan'=>$itemplan,
		        'idEstacion'=>$row->idEstacion,
		        'porcentaje'=>100,
		        'fecha'=>$fecha
		    					  );
				$this->db->insert('itemplanestacionavance', $post_data);
			}
		}else{

			foreach ($avance->result() as $row) {
				if($row->porcentaje=='100'){
					$nr[$row->idEstacion]=$row->idEstacion;
				}
			}
			$im=implode(",",$nr);
			$Query="DELETE FROM itemplanestacionavance where itemplan='".$itemplan."' and idEstacion not in(".$im.")";
			$this->db->query($Query);
			$estacion=$this->ListarEstacion($itemplan);
			foreach ($estacion->result() as $row) {				
			    if(!array_key_exists($row->idEstacion,$nr)){	
					$post_data = array(
			        'idItemplanEstacion'=>'',        
			        'itemplan'=>$itemplan,
			        'idEstacion'=>$row->idEstacion,
			        'porcentaje'=>100,
			        'fecha'=>date("Y-m-d H:i:s"));
					$this->db->insert('itemplanestacionavance', $post_data);
				}

			}
		}    
	}

	function getTramaSinfix($itemPlan, $idProyecto) {
		$sql ="SELECT DISTINCT po.itemplan,
								(SELECT GROUP_CONCAT(d.poCod)
								   FROM detalleplan d 
								  WHERE d.itemPlan = dp.itemPlan) AS ptr,
								c.jefatura,
								(SELECT empresaColabDesc 
								   FROM empresacolab ec
								  WHERE c.idEmpresacolab = ec.idEmpresaColab)empresaColab,
								po.fechaEjecucion  
				 FROM detalleplan dp INNER JOIN 
					  planobra po ON (dp.itemPlan = po.itemplan) INNER JOIN
					  central c ON (po.idCentral = c.idCentral)
				WHERE po.itemplan = '".$itemPlan."'
				  AND dp.idSubProyectoEstacion IN(SELECT idSubProyectoEstacion 
													FROM subproyectoestacion
												   WHERE idSubProyecto IN (SELECT idSubProyecto
																			 FROM subproyecto  
																			WHERE idProyecto = COALESCE(?,idProyecto)
																		  GROUP BY idSubProyecto))";
		$result = $this->db->query($sql, array($idProyecto));
		return $result->result();
	}

	function getIdEstadoPlan($itemPlan) {
		$sql="SELECT idEstadoPlan 
				FROM planobra
			   WHERE itemplan = '".$itemPlan."'";
		$result = $this->db->query($sql);
		return $result->row()->idEstadoPlan;
	}

	function countFormularioSisego($itemPlan, $origen) {
		$sql="SELECT COUNT(1)count 
		        FROM sisego_planobra 
			   WHERE itemplan = '".$itemPlan."'
			     AND origen   = ".$origen;
		$result = $this->db->query($sql);
		return $result->row()->count;
	}
	
	function countFormObrap($itemPlan, $idEstacion) {
		$sql ="SELECT COUNT(1)count
				 FROM form_obra_publica
				WHERE itemplan   = '".$itemPlan."'
				  AND idEstacion = ".$idEstacion;
		$result = $this->db->query($sql);
		return $result->row()->count;		 
	}

	function cantPorcentajeRegistroSisego($itemPlan, $idEstacion) {
		$sql ="SELECT porcentaje 
				 FROM itemplanestacionavance
			    WHERE itemplan   = '".$itemPlan."'
				  AND idEstacion = ".$idEstacion;
		$result = $this->db->query($sql);
		return $result->row();		 
	}
	
	function saveKitMateCantidad($itemplan, $cantidadCTO, $datosArray, $dataFicha, $dataDetCV, $microCanoni, $arrayPartidaItemplan, $tipoPartida, $camara){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $this->db->insert('ficha_tecnica',$dataFicha);
	        if ($this->db->affected_rows() == 0) {
	            $this->db->trans_rollback();
	        } else {
    	        $data = array(
    	            "itemplan"         => $itemplan,	           
    	            "usuario_registro" => $this->session->userdata('userSession'),
    	            "instalacion_cto"  =>  $cantidadCTO,
    	            "microcanolizado"  =>  $microCanoni,
    	            "idTipoPartida"    =>  $tipoPartida,
    	            "camara"           =>  $camara
    	        );	        
    	        $this->db->insert('itemplan_material', $data);
    	        if($this->db->affected_rows() != 1) {
    	            $this->db->trans_rollback();
    	            throw new Exception('Error al insertar en itemplan_expediente');
    	        }else{
    	            $id_itemplan_material  = $this->db->insert_id();
    	            for($i=0; $i < count($datosArray); $i++){
    	                $datosArray[$i]['id_itemplan_material'] = $id_itemplan_material;
    	            }
    	            
    	            $this->db->insert_batch('itemplan_material_cantidad', $datosArray);
    	            if ($this->db->trans_status() === FALSE) {
    	                $this->db->trans_rollback();
    	                throw new Exception('Hubo un error al insertar el itemplan_material_cantidad.');
    	            }else{
    	                $this->db->where('itemplan', $itemplan);
    	                $this->db->update('planobra_detalle_cv', $dataDetCV);
    	                if($this->db->trans_status() === FALSE) {
    	                    $this->db->trans_rollback();
    	                    throw new Exception('Error al insertar el plan de obra');
    	                }else{
    	                    $this->db->insert_batch('partida_itemplan', $arrayPartidaItemplan);
    	                    if($this->db->trans_status() === FALSE) {
    	                        $this->db->trans_rollback();
    	                        throw new Exception('Error al insertar el plan de obra');
    	                    }else{
        	                    $data['error']    = EXIT_SUCCESS;
            	                $data['msj']      = 'Se actualizo correctamente!';
            	                $this->db->trans_commit();
    	                    }
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
	
	function hasRegistroMaterialByItemplan($itemplan){
	    $Query = "SELECT (CASE WHEN  po.fechaPreliquidacion > im.fecha_registro THEN 
                    			(CASE WHEN NOW() <= po.fechaPreliquidacion + INTERVAL ".NUMERO_HORAS_EDITAR_MATERIALES_CV." HOUR THEN 1 ELSE 0 END)
                    		ELSE 
                                (CASE WHEN NOW() <= im.fecha_registro + INTERVAL ".NUMERO_HORAS_EDITAR_MATERIALES_CV." HOUR THEN 1 ELSE 0 END)
                    		END) as canEdit
                                      FROM    	itemplan_material im, planobra po
                                      WHERE   	im.itemplan = po.itemplan
                                      AND		im.itemplan  = ? LIMIT 1";
	    $result = $this->db->query($Query,array($itemplan));
	    if($result->row() != null) {
	        return $result->row_array()['canEdit'];
	    } else {
	        return null;
	    }
	}
	
	function getCuadrillaOne($itemPlan, $idEstacion) {
		$sql = "SELECT (SELECT u.nombre 
						  FROM cuadrilla cu,
								usuario    u
						 WHERE cu.id_usuario = u.id_usuario
						   AND id_cuadrilla = cu.idCuadrilla)nombCruadilla
		          FROM itemplanestacionavance 
				 WHERE itemplan = '".$itemPlan."' 
				   AND idEstacion = ".$idEstacion;
		$result = $this->db->query($sql);
		if($result->row_array() == null) {
			return null;
		} else {
			return $result->row_array()['nombCruadilla'];
		}	   	   
	}

	function getCoordenadas($itemPlan) {
		$sql = "SELECT coordX, 
		               coordY 
		          FROM planobra 
				 WHERE itemplan = '".$itemPlan."'";
		$result = $this->db->query($sql);
		return $result->row_array();	   
	}

	function isertFichaTecnica($dataInsert, $arrayJson) {
		$this->db->trans_begin();
		$this->db->insert('ficha_tecnica',$dataInsert);

		if ($this->db->affected_rows() == 0) {
			$this->db->trans_rollback();
		} else {
			if($arrayJson != null) {
				$idFicha = $this->db->insert_id();
			
				$arrayDataJson = array();
				foreach($arrayJson AS $row) {
					$row['id_ficha_tecnica'] = $idFicha;
					array_push($arrayDataJson, $row);
				}
				$this->db->insert_batch('ficha_tecnica_x_tipo_trabajo', $arrayDataJson);
				
				if ($this->db->affected_rows() == 0) {
					$this->db->trans_rollback();
				} else {
					$this->db->trans_commit();
					return 1;
				} 
			} else {
				$this->db->trans_commit();
				return 1;
			}
		}
	}
    
    function isertFichaTecnicaPub($dataInsert, $arrayJson) {
		$this->db->insert('ficha_tecnica',$dataInsert);

		if ($this->db->affected_rows() == 0) {
            return 0;
		} else {
			return 1;
		}
	}
	
	function countFichaTecnica($itemplan) {
		$sql = "SELECT COUNT(1) count
				  FROM sisego_planobra 
				 WHERE itemplan = '".$itemplan."'
				   AND origen   = 2";
		$result = $this->db->query($sql);
		return $result->row_array()['count'];	 
	}
	
	function insertFormObraP($arrayJson) {
		$this->db->insert('form_obra_publica', $arrayJson);
		if ($this->db->affected_rows() == 0) {
			return 0;
		} else {
			return 1;
		}
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
	    return $result->result();
	}
	    
	function updateKitMaterialCV($itemplan, $cantidadCTO, $datosArray, $dataDetCV, $microCanoni, $arrayPartidaItemplan, $cambio, $tipoPartida, $camara){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();	       
	            $dataUpdate = array(	                
	                "instalacion_cto"  =>  $cantidadCTO,
	                "microcanolizado"  =>  $microCanoni,
	                "idTipoPartida"    =>  $tipoPartida,
	                "camara"           =>  $camara
	            );	            
	            $this->db->where('itemplan', $itemplan);
	            $this->db->update('itemplan_material', $dataUpdate);
	            if ($this->db->trans_status() === FALSE){
	                throw new Exception('Hubo un error al actualizar en itemplan_material');
	            }else{
	                $this->db->update_batch('itemplan_material_cantidad', $datosArray, 'id');
	                if ($this->db->trans_status() === FALSE) {
	                    $this->db->trans_rollback();
	                    throw new Exception('Hubo un error al insertar el itemplan_material_cantidad.');
	                }else{
	                    $this->db->where('itemplan', $itemplan);
	                    $this->db->update('planobra_detalle_cv', $dataDetCV);
	                    if($this->db->trans_status() === FALSE) {
	                        $this->db->trans_rollback();
	                        throw new Exception('Error al insertar el plan de obra');
	                    }else{
	                        if($cambio){
	                            $this->db->where('itemPlan', $itemplan);
	                            $this->db->where('idEstacion', ID_ESTACION_FO);
	                            $this->db->delete('partida_itemplan');
	                            if ($this->db->trans_status() === FALSE) {
	                                $this->db->trans_rollback();
	                                throw new Exception('Error al insertar el plan de obra');
	                            }else{
	                                $this->db->insert_batch('partida_itemplan', $arrayPartidaItemplan);
	                                if($this->db->trans_status() === FALSE) {
	                                    $this->db->trans_rollback();
	                                    throw new Exception('Error al insertar el plan de obra');
	                                }else{
	                                    $data['error']    = EXIT_SUCCESS;
	                                    $data['msj']      = 'Se actualizo correctamente!';
	                                    $this->db->trans_commit();
	                                }
	                            }	                            
	                        }else{
	                            $this->db->update_batch('partida_itemplan', $arrayPartidaItemplan, 'id_partida_itemplan');
	                            if ($this->db->trans_status() === FALSE) {
	                                $this->db->trans_rollback();
	                                throw new Exception('Hubo un error al insertar el partida_itemplan.');
	                            }else{
	                                $data['error']    = EXIT_SUCCESS;
	                                $data['msj']      = 'Se actualizo correctamente!';
	                                $this->db->trans_commit();
	                            }
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
	
	function getPartidasByTipoAndEmpresacolab($idTipoPartida, $idEmpresaColab) {
	    $sql=" SELECT  pc.descripcion, pec.* 
                FROM   partidas_cv pc, partida_tipo_eecc_cv pec
            	WHERE  pc.id_item_partida = pec.id_item_partida
            	AND    pec.idTipoPartida = ?
            	AND    pec.idEmpresaColab = ?";
	    $result = $this->db->query($sql,array($idTipoPartida, $idEmpresaColab));
	    return $result->result();
	}	
	
	function getInfoItemPlanToCertificacion($itemPlan) {
	    $query = "SELECT c.idEmpresaColabCV,
                	(CASE WHEN c.jefatura = 'LIMA' THEN 1 ELSE 0 END) AS isLima
                	FROM planobra po, central c where po.idCentral = c.idCentral AND
                	po.itemplan = ? ";
	    $result = $this->db->query($query, array($itemPlan));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
	function getPartidasByItemplan($itemplan) {
	    $sql=" SELECT * FROM partida_itemplan where itemplan = ?";
	    $result = $this->db->query($sql,array($itemplan));
	    return $result->result();
	}
	
    function countTrama($itemplan, $origen) {
		$sql = "SELECT COUNT(1)count 
		          FROM log_tramas_sigoplus 
				 WHERE itemplan = '".$itemplan."' 
				   AND origen = '".$origen."'";
		$result = $this->db->query($sql);		   
		return $result->row_array()['count'];		   
	}
	
	function hasUnidadCTO($itemplan) {
	    $sql = " SELECT count(1) AS cont, id_partida_itemplan FROM partida_itemplan where  itemplan = ? and id_item_partida = ".ITEM_PARTIDA_UNIDAD_SINGULAR_DE_OBRA_CTO;
	    $result = $this->db->query($sql,array($itemplan));
	    return $result->row_array();
	}
	
	function willBeHaveUnidadCTO($idEmpresacolab, $tipoPartida, $idItemPartida) {
	    $sql = " SELECT count(1) as cont FROM
            	partida_tipo_eecc_cv WHERE
            	idEmpresaColab = ?  AND  idTipoPartida = ? AND	id_item_partida = ?";
	    $result = $this->db->query($sql,array($idEmpresacolab, $tipoPartida, $idItemPartida));
	    return $result->row_array()['cont'];
	}	
	
	function savePartida($datos){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $this->db->insert('partida_itemplan', $datos);
	        if($this->db->affected_rows() != 1) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar en gant_detalle');
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
	
	function deletePartida($id_partida_itemplan)
	{
	    $data['error'] = EXIT_ERROR;
	    $data['msj'] = null;
	    try {
	        $this->db->trans_begin();	      
	        $this->db->where('id_partida_itemplan', $id_partida_itemplan);
	        $this->db->delete('partida_itemplan');	        
	        if ($this->db->trans_status() === true) {	         
	                $data['error'] = EXIT_SUCCESS;
	                $data['msj'] = 'Se Elimino correctamente!';
	                $this->db->trans_commit();
	        } else {
	            $this->db->trans_rollback();
	            throw new Exception('ERROR TRANSACCION deleteItemPlanEstaDet');
	        }
	        
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }	    
	    return $data;
	}
	
	function getInfoPartidaUnidadSingular($idTipoPartida, $idEmpresaColab, $idItemPartida) {
	    $sql=" SELECT  pc.descripcion, pec.*
            	FROM   partidas_cv pc, partida_tipo_eecc_cv pec
            	WHERE  pc.id_item_partida = pec.id_item_partida
            	AND    pec.idTipoPartida = ?
            	AND    pec.idEmpresaColab = ?
            	AND    pec.id_item_partida = ?";
            	    $result = $this->db->query($sql,array($idTipoPartida, $idEmpresaColab, $idItemPartida));
            return $result->row_array();
	}
	
	function getPtrByItemplan($itemplan) {
		$sql = "SELECT ptr, FORMAT(SUM(total_anterior), 2) as total_anterior, ROUND(SUM(total), 2) total 
				  FROM (
						SELECT ptr,
							   ppo.total as total_anterior,
							   CASE WHEN pe.idActividad IS NOT NULL THEN pe.total ELSE ppo.total END total
						  FROM ptr_x_actividades_x_zonal ppo
						LEFT JOIN planobra_po_detalle_edit pe
						   ON ppo.ptr = pe.codigo_po
						   AND pe.idActividad = ppo.id_actividad
						 WHERE ppo.itemplan = '".$itemplan."' 
					UNION ALL 
						SELECT pe.codigo_po,
							   0 as total_anterior,
							   CASE WHEN pe.idActividad IS NOT NULL THEN pe.total ELSE ppo.total END total
						  FROM planobra_po_detalle_edit pe 
						LEFT JOIN ptr_x_actividades_x_zonal ppo
						   ON ppo.ptr = pe.codigo_po
						   AND pe.idActividad = ppo.id_actividad
						 WHERE pe.itemplan = '".$itemplan."'
						   AND ppo.ptr IS NULL
						)t GROUP BY ptr";
		$result = $this->db->query($sql);
		return $result->result();		
	}

	function actualizarPTR($arrayData, $ptr, $itemplan) {
		$this->db->update_batch('ptr_x_actividades_x_zonal', $arrayData, 'id_ptr_x_actividades_x_zonal');
		if($this->db->affected_rows() == 0) {
			return 0;
		} else {
			return 1;
		}
	}

	function insertarPTR($arrayInsert) {
		$this->db->insert_batch('ptr_x_actividades_x_zonal', $arrayInsert);

		if($this->db->affected_rows() == 0) {
			return 0;
		} else {
			return 1;
		}
	}
	
	function hasCamaraByItemplan($itemplan) {
	    $sql = "SELECT camara FROM itemplan_material where itemplan = ?";
	    $result = $this->db->query($sql,$itemplan);
	    return $result->row_array()['camara'];
	}
	
	function deletePartidasFromCamara($itemplan)
	{
	    $data['error'] = EXIT_ERROR;
	    $data['msj'] = null;
	    try {
	        $this->db->trans_begin();
	        $this->db->where('itemplan', $itemplan);
	        	         $this->db->where("(id_item_partida = '".ITEM_UNIDAD_SINGULAR_OBRA."' OR id_item_partida = '".ITEM_CONSTRUIR_CAMARA_REGISTRO."')");

	        //$this->db->where('id_item_partida', ITEM_UNIDAD_SINGULAR_OBRA);
	       // $this->db->or_where('id_item_partida', ITEM_CONSTRUIR_CAMARA_REGISTRO);
	        $this->db->delete('partida_itemplan');
	        if ($this->db->trans_status() === true) {
	            $data['error'] = EXIT_SUCCESS;
	            $data['msj'] = 'Se Elimino correctamente!';
	            $this->db->trans_commit();
	        } else {
	            $this->db->trans_rollback();
	            throw new Exception('ERROR TRANSACCION deleteItemPlanEstaDet');
	        }
	         
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	    }
	    return $data;
	}	
	
	function validarItemPlanEstacionAvanceByItemplan($itemPlan) {
		$sql = "SELECT count(1)count 
				  FROM itemplanestacionavance
				 WHERE itemplan   = '".$itemPlan;
		$result = $this->db->query($sql);		  
		return $result->row()->count; 
	}
	
	function validarItemplanEstAvaEstacion($itemplan, $idEstacion) {
		$sql = "SELECT COUNT(1)count
				  FROM itemplanestacionavance
			     WHERE itemplan = '".$itemplan."'
				   AND CASE WHEN $idEstacion = ".ID_ESTACION_COAXIAL." THEN porcentaje = 100 AND idEstacion = ".ID_ESTACION_FO."  
						    ELSE porcentaje = 100 AND idEstacion = ".ID_ESTACION_COAXIAL." END";
		$result = $this->db->query($sql);		  
		return $result->row()->count; 
	}

	function validarItemplanEstaAvance($itemplan, $idEstacion) {// SE COMPARA SI EL ITEMPLAN TIENE FO Y COAXIAL, SI NO ES ASI
		$sql = "SELECT COUNT(1)count
				  FROM itemplanestacionavance it
				 WHERE it.itemplan = '".$itemplan."' 
				   AND CASE WHEN 2 =  (SELECT COUNT(1) count 
										FROM (               
												SELECT t.idEstacion
												  FROM planobra p 
										     LEFT JOIN subproyectoestacion s on p.idSubProyecto=s.idSubProyecto 
										     LEFT JOIN estacionarea e        on s.idEstacionArea=e.idEstacionArea 
										     LEFT JOIN estacion t 		   on e.idEstacion=t.idEstacion 
										         WHERE p.itemplan = '".$itemplan."' 
												   AND t.idEstacion IN (2,5)
												GROUP BY t.idEstacion
											  )t
									  ) THEN CASE WHEN ".$idEstacion." = ".ID_ESTACION_COAXIAL." THEN porcentaje = 100 AND idEstacion = ".ID_ESTACION_FO."    
												  ELSE porcentaje = 100 AND idEstacion = ".ID_ESTACION_COAXIAL." END
						ELSE it.idEstacion = it.idEstacion END	";
		$result = $this->db->query($sql);		  
		return $result->row()->count; 
	}

	function getCountEstacionesByItemplan($itemplan, $arrayEstacion) {
		$sql = "SELECT COUNT(1) count 
				  FROM (               
						SELECT t.idEstacion
						  FROM planobra p 
				     LEFT JOIN subproyectoestacion s on p.idSubProyecto=s.idSubProyecto 
					 LEFT JOIN estacionarea e on s.idEstacionArea=e.idEstacionArea 
					 LEFT JOIN estacion t on e.idEstacion=t.idEstacion 
					 	 WHERE p.itemplan = '".$itemplan."' 
							AND t.idEstacion IN (".implode(',', $arrayEstacion).")
						GROUP BY t.idEstacion
					  )t";
		$result = $this->db->query($sql);		  
		return $result->row()->count; 			  
	}

	function getValidaSubProyecto($itemplan, $idEstacion, $porcentaje) {
		$sql = "  SELECT CASE WHEN SUM(countFOCOAX) = 2 THEN -- tiene FO y COAXIAL
								   CASE WHEN ".$idEstacion." = 2 
								        AND tt.idSubProyecto NOT IN (SELECT idSubProyecto 
																	   FROM switch_formulario swi
																	  WHERE swi.flg_acti_fo <> 1) THEN 
																									CASE WHEN tt.porcentaje = 100 AND idEstacion = 5  THEN 1
																											ELSE 0 END
										WHEN ".$idEstacion." = 5 
											AND tt.idSubProyecto NOT IN (SELECT idSubProyecto 
																		FROM switch_formulario swi
																		WHERE swi.flg_acti_fo <> 1)  THEN   
																										CASE WHEN tt.porcentaje = 100 AND idEstacion = 2  THEN 2
																												ELSE 0 END			
									END
							  WHEN SUM(countFOCOAX) = 1 THEN
															CASE WHEN ".$idEstacion." = 13 AND (SELECT SUM(CASE WHEN idEstacion IN (5,13) AND porcentaje = 100 THEN 1 
																												ELSE 0 END)flgPorcentajeFoUm 
																								  FROM itemplanestacionavance 
																								 WHERE itemplan = '".$itemplan."') = 2 THEN 3 
																 WHEN ".$idEstacion." IN (5,2) THEN CASE WHEN tt.idSubProyecto NOT IN (SELECT idSubProyecto 
																																		FROM switch_formulario swi
																																		WHERE swi.flg_acti_fo = 1) THEN  -- solo tiene FO o COAXIAL 
																																									CASE WHEN ".$porcentaje." = 100 THEN 4 END 
															                                             END
							                                      END 
							  WHEN ".$porcentaje."  = 100 THEN 5
							   
							   ELSE 0 
						 END flg_focoaxial,
						 CASE WHEN tt.idSubProyecto IN (SELECT idSubProyecto 
														  FROM switch_formulario swi
														 WHERE swi.flg_acti_fo = 1) THEN CASE WHEN (SELECT porcentaje 
																								  FROM itemplanestacionavance 
																							     WHERE itemplan = '".$itemplan."' 
																								   AND idEstacion = 5) = 100 THEN 1 ELSE 0 END 
							  ELSE 2 END flg_acti_fo,
						 CASE WHEN tt.idSubProyecto IN ( SELECT idSubProyecto
														   FROM switch_formulario
														  WHERE (flg_acti_fo IS NULL OR flg_acti_fo = 0) 
															AND flg_tipo = 1) THEN 1 ELSE 0 END flg_sisego																
					FROM (                       
						SELECT SUM(CASE WHEN t.idEstacion IN (5,2) THEN 1 ELSE 0 END) countFOCOAX,
							   SUM(CASE WHEN t.idEstacion = 11 THEN 1 ELSE 0 END) countPlantaInterna,
							   SUM(CASE WHEN t.idEstacion NOT IN (5,2,11) THEN 1 ELSE 0 END) countRestoEstacion,
							   t.idEstacion,
							   it.porcentaje,
							   t.idSubProyecto
						  FROM       
								( 
									SELECT s.idSubProyecto,
											t.idEstacion,
											p.itemplan
									  FROM planobra p 
									  LEFT JOIN subproyectoestacion s on p.idSubProyecto=s.idSubProyecto 
									  LEFT JOIN estacionarea e on s.idEstacionArea=e.idEstacionArea 
									  LEFT JOIN estacion t on e.idEstacion=t.idEstacion 
									 WHERE p.itemplan = '".$itemplan."' 
									   AND t.idEstacion != 1
								   GROUP BY t.idEstacion
								)t 
						  LEFT JOIN itemplanestacionavance it ON(it.idEstacion = t.idEstacion AND it.itemplan = t.itemplan)
						GROUP BY t.idEstacion
						)tt";
			$result = $this->db->query($sql);
			return $result->row(); 	
	}
	
	function updateEstadoPO($itemplan, $idEstacion) {
		$sql = "UPDATE planobra_po SET estado_po = 4
				 WHERE itemplan = '".$itemplan."'
				  AND flg_tipo_area IN (1,2)
				  AND (CASE WHEN flg_tipo_area = 1 THEN estado_po = 3
							WHEN flg_tipo_area = 2 THEN isPoPqt = 1 AND estado_po = 1 
							END)
				  AND idEstacion = ".$idEstacion;
		$result = $this->db->query($sql);
		if($this->db->affected_rows() < 1) {
			return 0;
		} else {
			$this->insertLogPO($itemplan, $idEstacion);
		}
	}

	function insertLogPO($itemplan, $idEstacion) {
	    $idUsuario = $this->session->userdata('idPersonaSession');
		$sql = "INSERT log_planobra_po
				SELECT '', codigo_po, itemplan, ".$idUsuario.", NOW(), 4, 'liquidacion PO auto',null 
				  FROM planobra_po
				 WHERE idEstacion = ".$idEstacion."
				   AND itemplan = '".$itemplan."'
				   AND flg_tipo_area IN (1,2)
				   AND estado_po = 4";
		$result = $this->db->query($sql);
		if($this->db->affected_rows() != 1) {
			return 0;
		} else {
			return 1;
		}
	}
	
	/**czvalacas 28.05.2019**/
	function getInfoEstacionByItemplanFormulario($itemplan, $idEstacion){
	    $Query="SELECT DISTINCT(t.estacionDesc),
    				t.idEstacion,
    				p.itemPlan,
    				idEstadoPlan,
    				p.indicador,
    				(SELECT c.jefatura
    				   FROM central c
    				  WHERE c.idCentral = p.idCentral)jefatura,
    				(SELECT empresaColabDesc
    				   FROM empresacolab ep
    				  WHERE ep.idEmpresaColab = (SELECT c.idEmpresaColab
    											   FROM central c
    											  WHERE idCentral = p.idCentral))descEmpresaColab,
    				(SELECT c.jefatura
    				   FROM central c
    				  WHERE c.idCentral = p.idCentral)jefatura,
    				(SELECT sp.idProyecto
    				   FROM subproyecto sp
    				  WHERE sp.idSubProyecto = p.idSubProyecto)idProyecto,
    				(SELECT spr.idTipoPlanta
    				   FROM subproyecto spr
    				  WHERE spr.idSubProyecto = p.idSubProyecto)idTipoPlanta,
    				(SELECT COUNT(1)
    				  FROM switch_formulario
    				 WHERE idSubProyecto = p.idSubProyecto
    				   AND idZonal       = p.idZonal
    				   AND flg_tipo      = ".FLG_TIPO_FORM_SISEGO.")countSwitchForm,
    				(SELECT COUNT(1)
    				   FROM switch_formulario
    				  WHERE idSubProyecto = p.idSubProyecto
    					AND idZonal       = p.idZonal
    					AND flg_tipo      = ".FLG_TIPO_FORM_OB_PUBLICAS.")countSwitchObPublicas,
    				p.idSerieTroba,
    				p.idSubProyecto,
    				CASE WHEN fechaPreLiquidacion < DATE('2018-09-01') THEN 0 ELSE 1 END AS flgFecha,
    				CASE WHEN NOW() < fechaPreLiquidacion + INTERVAL 24 HOUR OR fechaPreLiquidacion IS NULL THEN 0 ELSE 1 END AS flgHoras
    			FROM planobra p LEFT JOIN subproyectoestacion s on p.idSubProyecto=s.idSubProyecto
    			                LEFT JOIN estacionarea e on s.idEstacionArea=e.idEstacionArea
    							LEFT JOIN estacion t on e.idEstacion=t.idEstacion
    			WHERE p.itemPlan = ?
    		    AND t.idEstacion = ? LIMIT 1";
	    $result = $this->db->query($Query,array($itemplan, $idEstacion));
		_log($this->db->last_query());
	    if($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
	}
	
	function getEstadoDJByItemplanEstacion($itemplan, $idEstacion){
	    $Query = "SELECT count(1) as cant, estado_validacion
	               FROM ficha_tecnica WHERE itemplan = ? and id_estacion = ? LIMIT 1";
	    $result = $this->db->query($Query,array($itemplan, $idEstacion));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
	function existOnSiomObra($itemplan){
	    $Query = "SELECT count(1) as cant
	               FROM siom_obra so, planobra po WHERE so.itemplan = po.itemplan
                   and so.itemplan = ?
				   AND po.fecha_creacion >= '2019-11-26' 
                   AND ultimo_estado NOT IN ('ANULADA','RECHAZADA','NOACTIVO')";
	    $result = $this->db->query($Query,array($itemplan));
	    if($result->row() != null) {
	        return $result->row_array()['cant'];
	    } else {
	        return null;
	    }
	}
	
	function has_os_vali($itemplan, $idEstacion){
	    $Query = "SELECT   SUM(CASE WHEN ultimo_estado = 'VALIDANDO' || ultimo_estado = 'APROBADA' THEN 1 ELSE 0 END) has_validando, 
	                       SUM(CASE WHEN ultimo_estado != 'RECHAZADA' && ultimo_estado != 'ANULADA' THEN 1 ELSE 0 END) num
	               FROM    siom_obra 
	               WHERE   itemplan    = ? 
	               AND     idEstacion  = ?";
	    $result = $this->db->query($Query,array($itemplan, $idEstacion));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }	    
	}
	/**nuevo para formulario UM czavalacas 18.06.2019**/
	function saveFormularioUM($datos, $dataFichaTecnica){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $this->db->insert('formulario_um', $datos);
	        if($this->db->affected_rows() != 1) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar en formulario_um');
	        }else{
	            $this->db->insert('ficha_tecnica', $dataFichaTecnica);
	            if($this->db->affected_rows() != 1) {
	                $this->db->trans_rollback();
	                throw new Exception('Error al insertar en ficha_tecnica UM');
	            }else{
    	            $this->db->trans_commit();
    	            $data['error']    = EXIT_SUCCESS;
    	            $data['msj']      = 'Se inserto correctamente!';
	            }
	        }
	         
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
	
	function valid_um_form($itemplan, $idEstacion){
	    $Query = "SELECT itemplan, idEstacion, 
	                SUM(CASE WHEN ultimo_estado = 'VALIDANDO' || ultimo_estado = 'APROBADA' THEN 1 ELSE 0 END) as valis, 
	                SUM(CASE WHEN ultimo_estado != 'RECHAZADA' && ultimo_estado != 'ANULADA' THEN 1 ELSE 0 END) num, 
	                (SELECT COUNT(1) FROM formulario_um WHERE itemplan = ? ) as has_form
                    FROM siom_obra WHERE itemplan = ? and idEstacion = ?
                    GROUP BY itemplan, idEstacion";
	    $result = $this->db->query($Query,array($itemplan, $itemplan, $idEstacion));
	    log_message('error', $this->db->last_query());
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
	
	function validaSisego($idEstacion, $porcentaje, $itemplan) {
	    $sql = "SELECT CASE WHEN ? = 5  AND ? = 100 THEN 1 
                            WHEN ? = 13 AND ? = 100 AND (SELECT 1 
                                                           FROM itemplanestacionavance ii
                                                          WHERE ii.idEstacion = 5
        												    AND po.itemplan = ii.itemplan
                                                            AND ii.porcentaje > 0) IS NULL THEN 2
                            ELSE 3 END AS flgSisegoValid                                  
                 FROM planobra po
            	WHERE  po.itemplan = ?";
	    $result = $this->db->query($sql, array($idEstacion, $porcentaje, $idEstacion, $porcentaje, $itemplan));
	    return $result->row_array()['flgSisegoValid'];
	}
	
	/********* liquidar OC 08.08.2019 ***********/
	
	function saveLiquiOC($arrayPartidaItemplan, $fichaTecnica, $planobraDetCv, $itemplan){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();	
	        $this->db->insert('ficha_tecnica',$fichaTecnica);
	        if ($this->db->affected_rows() == 0) {
	            $this->db->trans_rollback();
	        } else {
                $this->db->insert_batch('partida_itemplan', $arrayPartidaItemplan);
                if($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    throw new Exception('Error al insertar el plan de obra');
                }else{
                    $this->db->where('itemplan', $itemplan);
	                $this->db->update('planobra_detalle_cv', $planobraDetCv);
	                if($this->db->trans_status() === FALSE) {
	                    $this->db->trans_rollback();
	                    throw new Exception('Error al update Planobra Detalle Cv');
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
	
	function canEditOCLiqui($itemplan) {
	    $sql = "SELECT ft.itemplan,
            	(CASE WHEN NOW() >= ft.fecha_registro + INTERVAL 48 HOUR THEN 1 ELSE 0 END)
            	as canEdit, count(1) as cont
            	FROM    	ficha_tecnica ft
            	WHERE   	ft.id_estacion = ".ID_ESTACION_OC_FO."
            	AND 		ft.itemplan  = ?";
	    $result = $this->db->query($sql, array($itemplan));
	    log_message('error', $this->db->last_query());
	   if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
	function getPartidasOCToEdit($itemplan, $idEmpresaColab) {
	    $sql = "SELECT  pc.descripcion, pec.*, pi.cantidad as cant_edit, pi.id_partida_itemplan
                FROM   partidas_cv pc, partida_tipo_eecc_cv pec LEFT JOIN partida_itemplan pi ON 
					   pec.id_item_partida = pi.id_item_partida
                       AND pec.idTipoPartida = pi.idTipoPartida
                       AND pi.itemplan = ?
                WHERE  pc.id_item_partida = pec.id_item_partida
            	AND    pec.idTipoPartida = 3
            	AND    pec.idEmpresaColab = ?";
	    $result = $this->db->query($sql, array($itemplan, $idEmpresaColab));
	    return $result->result();
	}
	
	function saveLiquiOCOrUpdate($arrayUpdate, $arrayInsert, $planobraDetCv, $itemplan){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
            $this->db->update_batch('partida_itemplan', $arrayUpdate, 'id_partida_itemplan');
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al insertar el itemplan_material_cantidad.');
            }else{
	            $this->db->insert_batch('partida_itemplan', $arrayInsert);
	            if($this->db->trans_status() === FALSE) {
	                $this->db->trans_rollback();
	                throw new Exception('Error al insertar el plan de obra');
	            }else{
	                $this->db->where('itemplan', $itemplan);
	                $this->db->update('planobra_detalle_cv', $planobraDetCv);
	                if($this->db->trans_status() === FALSE) {
	                    $this->db->trans_rollback();
	                    throw new Exception('Error al update Planobra Detalle Cv');
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
	
	function goToSiomByItemplan($itemplan){
	    $Query = " SELECT 	count(1) as cant from planobra where  itemplan =  ?
	               AND      fecha_creacion >= '2019-11-26'";
	    $result = $this->db->query($Query,array($itemplan));
	    if($result->row() != null) {
	        return $result->row_array()['cant'];
	    } else {
	        return null;
	    }
	}
	
	function getPartidasByItemplanAndIdEstacion($itemplan, $idEstacion) {
	    $sql=" SELECT * FROM partida_itemplan where itemplan = ? and idEstacion = ?";
	    $result = $this->db->query($sql,array($itemplan,  $idEstacion));
	    return $result->result();
	}
}
