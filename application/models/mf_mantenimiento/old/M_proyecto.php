<?php
class M_proyecto extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
	function   getProyectoInfo($idProyecto){
	    $Query = " SELECT * FROM proyecto where idProyecto = ?";
	    $result = $this->db->query($Query,array($idProyecto));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
	
	
	function insertProyecto($decripcion, $tipoCentral, $tipoLabel){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $dataInsert = array(
	            "proyectoDesc"  => $decripcion,
	            "idTipoCentral" => $tipoCentral,
	            "idTipoLabel" => $tipoLabel	           
	        );
	        
	        $this->db->insert('proyecto', $dataInsert);
	        if($this->db->affected_rows() != 1) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar el Proyecto');
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
	
	function editarProyecto($id, $idTipocentral, $tipoLabel, $descripcion){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        
	        $dataUpdate = array(
	            "proyectoDesc"  => strtoupper($descripcion),
	            "idTipoCentral" => $idTipocentral,
	            "idTipoLabel" => $tipoLabel
	        );
	        $this->db->where('idProyecto', $id);
	        $this->db->update('proyecto', $dataUpdate);
	        if ($this->db->trans_status() === FALSE) {
	            throw new Exception('Hubo un error al actualizar en editarCentralModelo');
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
	
	function insertSubProyecto($idProyecto, $subProDesc, $tiempo, $planta, $areas, $idComplejidad = null, $idTipoSubProyecto = null){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $dataInsert = array(
	            "subProyectoDesc"  => strtoupper($subProDesc),
	            "tiempo" => $tiempo,
	            "idProyecto" => $idProyecto,
	            "idTipoPlanta" => $planta,
                "idTipoComplejidad" => $idComplejidad,
                "idTipoSubProyecto" => $idTipoSubProyecto,
	            "estado" => 1
	        );
	         
	        $this->db->insert('subproyecto', $dataInsert);
	        if($this->db->affected_rows() != 1) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar el Proyecto');
	        }else{
				$insert_id = $this->db->insert_id();
				$data['idSubProyectoNew'] = $insert_id;

				$sql = "INSERT partida_subproyecto
						 SELECT idActividad, ".$insert_id.", null
						   FROM partidas 
						 WHERE idTipoComplejidad = ".$idComplejidad;
				$this->db->query($sql);

				if($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					throw new Exception('Error al insertar el Proyecto');
				} else {
					$listaAreas = explode(',', $areas);
					$arrayInsert = array();
					foreach($listaAreas as $idAreaEstacion){
						$datatrans['idSubProyecto']  = $insert_id;
						$datatrans['idEstacionArea'] = $idAreaEstacion;
						array_push($arrayInsert, $datatrans);
					}
					$this->db->insert_batch('subproyectoestacion', $arrayInsert);
					if ($this->db->trans_status() === FALSE) {
						$this->db->trans_rollback();
						throw new Exception('Hubo un error al insertar el subproyectoestacion.');
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
	
	// para el caso de paquetizado
	function insertSubProyectoPaquetizado($idProyecto, $subProDesc, $tiempo, $planta, $estaciones, 
	    $idComplejidad = null, $idTipoSubProyecto = null, $idTipoFactorMedicion = null, 
	    $paquetizado_fg = null, $aprobacionAutomatica_fg = null, $adjudicacionAutomatica_fg = null, $flgCheckOpex = null, $flgSinDiseno = null)
	{
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $dataInsert = array(
	            "subProyectoDesc"  		  => strtoupper($subProDesc),
	            "tiempo" 				  => $tiempo,
	            "idProyecto" 			  => $idProyecto,
	            "idTipoPlanta" 			  => $planta,
	            "idTipoComplejidad" 	  => $idComplejidad,
	            "idTipoSubProyecto"       => $idTipoSubProyecto,
	            "idPqtTipoFactorMedicion" => $idTipoFactorMedicion,
	            "paquetizado_fg" 	      => $paquetizado_fg,
	            "aprobacionAutomatica_fg" => $aprobacionAutomatica_fg,
	            "adjudicacionAutomatica_fg" => $adjudicacionAutomatica_fg,
	            "estado" 				  => 1,
				"flg_opex" 				  => $flgCheckOpex,
				"flg_sin_diseno" 		  => $flgSinDiseno
	        );
	
	        $this->db->insert('subproyecto', $dataInsert);
	        if($this->db->affected_rows() != 1) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar el Proyecto');
	        }else{
	            $insert_id = $this->db->insert_id();
	            $data['idSubProyectoNew'] = $insert_id;
	
	            $sql = "INSERT partida_subproyecto
						 SELECT idActividad, ".$insert_id.", null
						   FROM partidas
						 WHERE idTipoComplejidad = ".$idComplejidad;
	            $this->db->query($sql);
	
	            if($this->db->trans_status() === FALSE) {
	                $this->db->trans_rollback();
	                throw new Exception('Error al insertar el Proyecto');
	            } else {
	                
	                $listaEstacionAreas = $this->getIdEstacionAreaByEstaciones($estaciones)->result();
// 	                $listaEstacionAreas = explode(',', $stringEstaciones);
	                
	                $arrayInsert = array();
	                foreach($listaEstacionAreas as $row){
	                    $datatrans['idSubProyecto']  = $insert_id;
	                    $datatrans['idEstacionArea'] = $row->idEstacionArea;
	                    array_push($arrayInsert, $datatrans);
	                }
	                $this->db->insert_batch('subproyectoestacion', $arrayInsert);
	                if ($this->db->trans_status() === FALSE) {
	                    $this->db->trans_rollback();
	                    throw new Exception('Hubo un error al insertar el subproyectoestacion.');
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
	
	function getSubProyectoInfo($idSubProyecto){
	    $Query = " SELECT *,(SELECT GROUP_CONCAT('',estacionarea.idEstacionArea) from subproyectoestacion, estacionarea, estacion
                    WHERE subproyectoestacion.idEstacionArea = estacionarea.idEstacionArea and estacion.idEstacion = estacionarea.idEstacion
                    and subproyectoestacion.idSubProyecto = subproyecto.idSubProyecto) as estaciones FROM subproyecto where idSubProyecto = ?";
	    $result = $this->db->query($Query,array($idSubProyecto));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}

	function editarSubProyecto($id, $idProyecto, $subProDesc, $tiempo, $planta, $estaciones,$oldSubPro,$idComplejidad = null, $idTipoSubProyecto = null){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $dataUpdate = array(
	            "subProyectoDesc"  => strtoupper($subProDesc),
	            "tiempo" => $tiempo,
	            "idProyecto" => $idProyecto,
	            "idTipoPlanta" => $planta,
                "idTipoComplejidad" => $idComplejidad,
                "idTipoSubProyecto" => $idTipoSubProyecto
	        );
	        
	        $this->db->where('idSubProyecto', $id);
	        $this->db->update('subproyecto', $dataUpdate);
	        if($this->db->trans_status() === FALSE) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar el Proyecto');
	        }else{
	            
	            $original = $this->getSubProEstaconBySubPro($id)->result();
	            
	            $listaEstaciones = explode(',', $estaciones);
	            
	            $arrayInsert = array();
	            foreach($listaEstaciones as $idAreaEstacion){
	                if (!in_array($idAreaEstacion, $original)){
	                    $datatrans['idSubProyecto'] = $id;
	                    $datatrans['idEstacionArea'] = $idAreaEstacion;
	                    array_push($arrayInsert, $datatrans);
	                }
	            }
	            $this->db->insert_batch('subproyectoestacion', $arrayInsert);
	            if ($this->db->trans_status() === FALSE) {
	                $this->db->trans_rollback();
	                throw new Exception('Hubo un error al insertar el subproyectoestacion.');
	            }else{	            
	                
	                $this->db->where('idSubProyecto', $id);
	                $this->db->where_not_in('idEstacionArea', $listaEstaciones);
	                $this->db->delete('subproyectoestacion');
	                if ($this->db->trans_status() === FALSE) {
	                    $this->db->trans_rollback();
	                    throw new Exception('Hubo un error al insertar el subproyectoestacion.');
	                }else{	
	                    $dataUpda = array(
	                        "subProy"  => strtoupper($subProDesc)
	                    );
	                    $this->db->where('subProy', strtoupper($oldSubPro));
	                    $this->db->where("(estado_asig_grafo='0' OR estado_asig_grafo='1')");
	                    $this->db->update('web_unificada_det', $dataUpda);
	                    if($this->db->trans_status() === FALSE) {
	                        throw new Exception('Hubo un error al actualizar en web_unificada_det');
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
	
	function editarSubProyectoPaquetizado($id, $idProyecto, $subProDesc, $tiempo, $planta,
	    $estaciones,$oldSubPro,$idComplejidad = null,
	    $idTipoSubProyecto = null, $idTipoFactorMedicion = null, $aprobacionAutomatica_fg = null,
	    $adjudicacionAutomatica_fg = null){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $dataUpdate = array(
	            "subProyectoDesc"  => strtoupper($subProDesc),
	            "tiempo" => $tiempo,
	            "idProyecto" => $idProyecto,
	            "idTipoPlanta" => $planta,
	            "idTipoComplejidad" => $idComplejidad,
	            "idTipoSubProyecto" => $idTipoSubProyecto,
	            "idPqtTipoFactorMedicion" => $idTipoFactorMedicion,
	            "aprobacionAutomatica_fg" => $aprobacionAutomatica_fg,
	            "adjudicacionAutomatica_fg" => $adjudicacionAutomatica_fg
	        );
	         
	        $this->db->where('idSubProyecto', $id);
	        $this->db->update('subproyecto', $dataUpdate);
	        if($this->db->trans_status() === FALSE) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar el Proyecto');
	        }else{
	             
	            $original = $this->getSubProEstaconBySubPro($id)->result();
	             
	            $listaEstaciones = $this->getIdEstacionAreaByEstaciones($estaciones)->result();
	            $stringEstaciones = $this->m_proyecto->getIdEstacionAreaByEstacionesSTR($estaciones);
	            $idEstacionesAreas = "";
	            $arrayInsert = array();
	            foreach($listaEstaciones as $row){
	                $existeRegistro = false;
	                foreach($original as $row2){
	                    if($row->idEstacionArea == $row2->idEstacionArea && $existeRegistro == false){
	                        $existeRegistro = true;
	                    }
	                }
	                if($existeRegistro == false){
	                    $datatrans['idSubProyecto'] = $id;
	                    $datatrans['idEstacionArea'] = $row->idEstacionArea;
	                    array_push($arrayInsert, $datatrans);
	                }
	            }
	            $this->db->insert_batch('subproyectoestacion', $arrayInsert);
	            if ($this->db->trans_status() === FALSE) {
	                $this->db->trans_rollback();
	                log_message('error','Hubo un error al insertar el subproyectoestacion. 1');
	                throw new Exception('Hubo un error al insertar el subproyectoestacion.');
	            }else{
	                 
	                $this->db->where('idSubProyecto', $id);
	                $this->db->where_not_in('idEstacionArea', explode(',', $stringEstaciones));
	                $this->db->delete('subproyectoestacion');
	                if ($this->db->trans_status() === FALSE) {
	                    $this->db->trans_rollback();
	                    log_message('error','Hubo un error al insertar el subproyectoestacion. 2');
	                    throw new Exception('Hubo un error al insertar el subproyectoestacion.');
	                }else{
	                    $dataUpda = array(
	                        "subProy"  => strtoupper($subProDesc)
	                    );
	                    $this->db->where('subProy', strtoupper($oldSubPro));
	                    $this->db->where("(estado_asig_grafo='0' OR estado_asig_grafo='1')");
	                    $this->db->update('web_unificada_det', $dataUpda);
	                    if($this->db->trans_status() === FALSE) {
	                        throw new Exception('Hubo un error al actualizar en web_unificada_det');
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
	
	function getSubProEstaconBySubPro($idSubPro){
	    $Query = "SELECT idEstacionArea FROM    subproyectoestacion WHERE idSubProyecto = ?" ;
	    $result = $this->db->query($Query,array($idSubPro));
	    return $result;
	}



	///////////////////ACTIVIDAD POR SUBPROYECTO////////////////////

    function   getAllSubproyectoActividad($idSubProyecto){
        $Query = " SELECT 
                        asp.idactividad_x_subProyecto,
                        api.idActividad,
                        asp.idSubProyecto,
                        api.descripcion as actividad,
                        api.baremo,
                        api.kit_material,
                        api.costo_material,
                        api.pliego,
                        sp.subProyectoDesc,
                        api.estado
                    FROM actividad_x_subproyecto asp,
                         subproyecto sp,
                         partidas api
                   WHERE asp.idActividad = api.idActividad 
                     AND asp.idSubProyecto = sp.idSubProyecto
                     AND api.flg_tipo      = 1
                     AND asp.idSubProyecto = ?;";
        $result = $this->db->query($Query,array($idSubProyecto));

        if($result->row() != null) {
            return $result;
        } else {
            return null;
        }
    }

    function getAllSubProyectoDesc(){
        $Query = "  SELECT     subproyecto.idSubProyecto, 
	                           subproyecto.tiempo,
	                           proyecto.proyectoDesc, 
	                           subproyecto.subProyectoDesc, 
	                           tipoplanta.tipoPlantadesc,
	                           subproyecto.idTipoPlanta  
	                   FROM    subproyecto, proyecto, tipoplanta
            	       WHERE   subproyecto.idProyecto = proyecto.idProyecto
            	       AND     subproyecto.idTipoPlanta = tipoplanta.idTipoPlanta
            	       AND    subproyecto.idTipoPlanta = 2
            	    ORDER BY   proyectoDesc, subProyectoDesc;" ;
        $result = $this->db->query($Query,array());
        return $result;
    }

    function getAllActividades()
    {
        $Query = "  SELECT     * 
	                   FROM    partidas
	                    WHERE  flg_tipo      = 1
            	    ORDER BY   descripcion;" ;
        $result = $this->db->query($Query);
        return $result;
    }

    function insertActividad($actividades, $idSubProyecto)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $actividades = explode(',', $actividades);
            $this->db->trans_begin();
            foreach ($actividades as $idActividad){
                $dataInsert = array(
                    "idSubProyecto"  => $idSubProyecto,
                    "idActividad" => $idActividad,

                );

                $this->db->insert('actividad_x_subproyecto', $dataInsert);

                if($this->db->affected_rows() != 1) {
                    $this->db->trans_rollback();
                    throw new Exception('Error al insertar el Proyecto');
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

    function getSubProyectoActividadInfo($idSubProyectoAct){
        $Query = " SELECT idSubProyecto, idActividad FROM actividad_x_subproyecto where idactividad_x_subProyecto = ?";
        $result = $this->db->query($Query,array($idSubProyectoAct));
        if($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }

    function editarSubProyectoActividad($id, $idSubProyecto, $idActividad){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->trans_begin();
            $dataUpdate = array(
                "idSubProyecto" => $idSubProyecto,
                "idActividad" => $idActividad
            );

            $this->db->where('idactividad_x_subProyecto', $id);
            $this->db->update('actividad_x_subproyecto', $dataUpdate);
            if($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                throw new Exception('Error al modificar el SubProyecto Actividad');
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

    function existSubProyectoActividad($idSubProyecto, $idActividad){
        $Query = " SELECT * FROM actividad_x_subproyecto where idSubProyecto = ? AND idActividad = ?";
        $result = $this->db->query($Query,array($idSubProyecto, $idActividad));
        if($result->row() != null) {
            return $result->num_rows();
        } else {
            return null;
        }
    }
    
    ///////////////////////////////////28092018//////////////////////////
    function existeNombreProy($descripcion){
		$sql = "SELECT COUNT(1) as count FROM proyecto where UCASE(proyectoDesc) = ?;";	
	    $result=$this->db->query($sql, array(strtoupper($descripcion)));
	    return $result->row()->count;
	}


	function insertLogProyecto($accion,$idproy,$proyecto,$usuario){
		$data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();

	        $fecha=date("Y-m-d H:i:s");

	        $dataInsert = array(
	            "accion" => $accion,
	            "idproyecto" => $idproy,
	            "nombreproyecto"  => $proyecto,
	            "editor" => $usuario,
	            "fecha"  =>	$fecha         
	        );
	        
	        $this->db->insert('log_proyecto', $dataInsert);
	        if($this->db->affected_rows() != 1) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar el Proyecto');
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

	function getEstacionesByIdEstacionArea($estaciones){

		$sql = "SELECT GROUP_CONCAT(idEstacion) AS estaciones FROM estacionarea WHERE idEstacionArea IN (" . $estaciones . ")";
        $result = $this->db->query($sql);
        return $result->row()->estaciones;
	} 

	public function insertFichaTecSubProEstacion($arrayInsert)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
			$this->db->trans_begin();
			$this->db->insert_batch('ficha_tenica_subproyecto_estacion', $arrayInsert);
            if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				throw new Exception('Hubo un error al insertar.');
			}else{
				$this->db->trans_commit();
				$data['error']    = EXIT_SUCCESS;
				$data['msj']      = 'Se inserto correctamente!';
			}

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
     public function getAllIdZonales()
    {
		$sql = " SELECT idZonal FROM zonal";
        $result = $this->db->query($sql);
        return $result->result();
	}
	

	public function insertSwitchFormulario($arrayInsert)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert_batch('switch_formulario', $arrayInsert);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al insertar.');
            } else {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se inserto correctamente!';
            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    public function insertarLogSubProy($arrayInsert)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert('log_subproyecto', $arrayInsert);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar el log_subproyecto!!');
            } else {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se insert&oacute; correctamente!!';
            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function insertPartidaLicencia($idSubProyecto, $idTipoSubProyecto, $flgTipoPlanta) { //TIPO SUB. SI ES INTEGRAL O BUCLE
		$data = array();

		$sql = "INSERT partida_subproyecto
				SELECT pa.idActividad, ".$idSubProyecto.", NULL
				  FROM partidas pa
				 WHERE pa.flg_reg_grafico IN (2,3)
				   AND ? = 1
				   AND ? <> 2";
		$this->db->query($sql, array($flgTipoPlanta, $idTipoSubProyecto));

		if($this->db->trans_status() === FALSE) {
			$data['error'] = EXIT_ERROR;
		} else {
			$data['error'] = EXIT_SUCCESS;
		}
		return $data;
	}
	
	function getIdEstacionAreaByEstaciones($idEstaciones){
	
	    $sql = "SELECT idEstacionArea FROM estacionarea WHERE idEstacion IN (" . $idEstaciones . ")";
	    $result = $this->db->query($sql);
	    return $result;
	}
	
	function getIdEstacionAreaByEstacionesSTR($estaciones){
	
	    $sql = "SELECT GROUP_CONCAT(idEstacionArea) AS estacionAreas FROM estacionarea WHERE idEstacion IN (" . $estaciones . ")";
	    $result = $this->db->query($sql);
	    return $result->row()->estacionAreas;
	}

	function insertPlanifica($idSubProyecto, $idFase, $nomPlan, $cantidad, $idMes, $fecha, $idUsuario) {
		$this->db->insert('subproyecto_fases_cant_item_planificacion', array('idSubProyecto' => $idSubProyecto,
																			 'idFase'        => $idFase,
																			 'nombre_plan'   => $nomPlan,
																			 'cantidad'      => $cantidad,
																			 'id_mes'        => $idMes,
																			 'fecha_reg'     => $fecha,
																			 'id_usuario_reg' => $idUsuario));
		if($this->db->affected_rows() != 1) {
			$data['error'] = EXIT_ERROR;
			$data['msj'] = 'Error al ingresar la planificacion';
		} else {
			$data['error'] = EXIT_SUCCESS;
		}
		return $data;
	}
	
	function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone', 'America/Lima');
        setlocale(LC_TIME, "es_ES", "esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }
	
	function createNewSubProyectoDespliegue($dataInsert, $estacionesArea, $idUsuario, $fases)
	{
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();	
	        $this->db->insert('subproyecto', $dataInsert);
	        if($this->db->affected_rows() != 1) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar el Proyecto');
	        }else{
	            $insert_id = $this->db->insert_id();	            
				$subproyectoEstacionList = array();
				foreach($estacionesArea as $estacionArea){
					$subEsta = array();
					$subEsta['idSubProyecto']	=	$insert_id;
					$subEsta['idEstacionArea']	=	$estacionArea->idEstacionArea;
					array_push($subproyectoEstacionList, $subEsta);
				}
				$this->db->insert_batch('subproyectoestacion', $subproyectoEstacionList);
				if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					throw new Exception('Hubo un error al insertar el subproyectoestacion.');
				}else{	
					$logSubPro = array('idSubProyecto'		=>	$insert_id,
										'idUsuario'			=>	$idUsuario,
										'fecha_registro'	=>	$this->fechaActual(),
										'actividad'			=>	'Registro Nuevo');
					
					 $this->db->insert('log_subproyecto', $logSubPro);
					if($this->db->affected_rows() != 1) {
						$this->db->trans_rollback();
						throw new Exception('Error al insertar el log_subproyecto');
					}else{		
						$arrayInsert = array();
						 foreach($fases as $key=>$value){
							$datatrans['idSubProyecto'] = $insert_id ;
							$datatrans['fase'] 			= $value->fase;
							$datatrans['cantItemPlan'] 	= $value->cantidadplanificada;
							array_push($arrayInsert, $datatrans);
						}
						$this->db->insert_batch('subproyecto_fases_cant_itemplan', $arrayInsert);
						if ($this->db->trans_status() === FALSE) {
							$this->db->trans_rollback();
							throw new Exception('Hubo un error al insertar el subproyecto_fases_cant_itemplan.');
						}else{
							$this->db->trans_commit();
							$data['idSubProyectoNew'] = $insert_id;
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
	
	public function getAllestacionAreaByTipoPlanta($idTipoPlanta)
    {
		$sql 	= "SELECT * FROM estacionarea_x_tipo_planta WHERE idTipoPlanta = ?";
        $result = $this->db->query($sql, array($idTipoPlanta));
        return $result->result();
	}
	
	
}