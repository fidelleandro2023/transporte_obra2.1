<?php
class M_subproy_pep_grafo extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
    function   getPep1SubProy($subPry){
        $Query = "SELECT ps.*, f.faseDesc FROM pep1_subpro ps, fase f, subproyecto sp, proyecto p
					WHERE ps.idFase = f.idFase 
					AND	ps.idSubProyecto = sp.idSubProyecto 
					AND sp.idProyecto = p.idProyecto
					AND p.idProyecto = 3 " ;  
        if($subPry!=''){
            $Query .= " AND ps.subproyecto REGEXP '".str_replace(',','|',$subPry)."'";
        }    
        $Query .= ' ORDER BY ps.subproyecto, ps.area';
	    $result = $this->db->query($Query,array());	   
	    return $result;
	}
	
	function   getPep1Pep2(){
	    $Query = "SELECT * FROM pep1_pep2 order by pep1 desc" ;	  
	    $result = $this->db->query($Query,array());
	    return $result;
	}
	
	
	function   getPepPresupuesto(){	   
	    $Query = "SELECT sd.pep1, 
						FORMAT(sd.monto_inicial, 2) as monto_inicial,  
						FORMAT(sd.monto_temporal, 2) as monto_temporal, 
						FORMAT(sd.monto_sol_oc, 2) as monto_solicitud_oc,
						FORMAT((sd.monto_inicial-(sd.monto_sol_oc+sd.monto_temporal)), 2) as monto_material
                    FROM  sap_detalle sd";
	    $result = $this->db->query($Query,array());
	    return $result;
	}
	
	function   getPep2Grafos(){
	    $Query = " SELECT pp.pep1, pg.pep2,
                	(select count(1) from pep2_grafo where pep2 = pg.pep2 and estado = 0) as libres ,
                	(select count(1) from pep2_grafo where pep2 = pg.pep2 and estado = 1) as temporales ,
                	(select count(1) from pep2_grafo where pep2 = pg.pep2 and estado = 2) as usados ,
                	count(pg.grafo) as total
                	FROM pep2_grafo pg LEFT JOIN pep1_pep2 pp
                	ON pp.pep2 = pg.pep2
                	GROUP BY pg.pep2
                	ORDER BY libres;";
	    $result = $this->db->query($Query,array());
	    return $result;
	}
	
	function insertPepSubProyecto($subProyecto, $estacion, $area, $pep, $fase){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $dataInsert = array(
	            "idSubProyecto"  => $subProyecto,
	            "estacion" => $estacion,
	            "area" => $area,
	            "pep1" => $pep,
	            "idFase" => $fase
	        );
	        log_message('error', 'toSubPro:'.print_r($dataInsert, true));
	        $this->db->insert('pep1_subpro', $dataInsert);
	        if($this->db->affected_rows() != 1) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar el Subproyecto - Pep');
	        }else{
	            log_message('error', 'commiteo');
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
	
	function   getEstacionByArea($area){
	    $Query = " SELECT   estacionDesc 
	               FROM     estacionarea ea, estacion e, area a 
                   WHERE    ea.idEstacion = e.idEstacion
                   AND      ea.idArea = a.idArea
                   AND      a.areaDesc = ?";
	
	    $result = $this->db->query($Query,array($area));
	    if($result->row() != null) {
	        return $result->row_array()['estacionDesc'];
	    } else {
	        return null;
	    }
	}
	
	function   existPepSubArea($subpro, $area, $pep1){
    	$sql = "SELECT COUNT(1) cant
    	              FROM pep1_subpro
    	             WHERE UPPER(subproyecto) = UPPER(?)
    	             AND   UPPER(area) = UPPER(?)
    	             AND   UPPER(pep1) = UPPER(?)
    	             LIMIT 1";
    	$result = $this->db->query($sql,array($subpro, $area, $pep1));
    	return $result;
	}
	
	function deleteSubPepArea($idSubPep){	    
	    $rpta['error'] = EXIT_ERROR;
	    $rpta['msj'] = null;
	    try{
	        
	        $this->db->trans_begin();	
	        $this->db->where('id_pep1_subpro', $idSubPep);
	        $this->db->delete('pep1_subpro');
	        $this->db->trans_complete();
	        if ($this->db->trans_status() === FALSE) {
	            $this->db->trans_rollback();
	            throw new Exception("Error al Eliminar Subproyecto Pep");
	        }else {	           
	            $this->db->trans_commit();
	            $rpta['msj'] = 'Se elimino correctamente el Subproyecto - PEP';
	            $rpta['error']  = EXIT_SUCCESS;
	        }
	
	    }catch(Exception $e){
	        $rpta['msj'] = $e->getMessage();	        
	    }
	    return $rpta;
	}
	
	function deletePepPep2($idPep1Pep2){
	    $rpta['error'] = EXIT_ERROR;
	    $rpta['msj'] = null;
	    try{
	         
	        $this->db->trans_begin();
	        $this->db->where('id_relacion', $idPep1Pep2);
	        $this->db->delete('pep1_pep2');
	        $this->db->trans_complete();
	        if ($this->db->trans_status() === FALSE) {
	            $this->db->trans_rollback();
	            throw new Exception("Error al Eliminar Pep1 - Pep2");
	        }else {
	            $this->db->trans_commit();
	            $rpta['msj'] = 'Se elimino correctamente el Pep1 - Pep2';
	            $rpta['error']  = EXIT_SUCCESS;
	        }
	
	    }catch(Exception $e){
	        $rpta['msj'] = $e->getMessage();
	    }
	    return $rpta;
	}
	
	function insertPep1Pep2($finalPep1, $finalPep2){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $data = array(
	            "pep1"  => $finalPep1,
	            "pep2" => $finalPep2
	        );
	         
	        $this->db->insert('pep1_pep2', $data);
	        if($this->db->affected_rows() != 1) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar el Pep1 - Pep2');
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
	
	
	
	
	function   loadDataImportPep2Grafo($pathFinal){
	    $data ['error']= EXIT_ERROR;
	    $data['msj'] = null;
	    try{
	        $this->db->trans_begin();
	        $this->db->from('import_pep2_grafo');
	        $this->db->truncate();
	        if ($this->db->trans_status() === TRUE) {
	            $this->db->query("LOAD DATA LOCAL INFILE '".$pathFinal."' INTO TABLE import_pep2_grafo");
	            if ($this->db->trans_status() === TRUE) {
	                $this->db->query("DELETE FROM import_pep2_grafo WHERE (substring(pep2,1,2) != 'P-' AND  substring(pep2,1,2) != 'S.')");
	                if ($this->db->trans_status() === TRUE) {
	                    $this->db->query("UPDATE import_pep2_grafo set grafo = replace(grafo,'\r','');");
	                    if ($this->db->trans_status() === TRUE) {
	                        $this->db->query("INSERT INTO pep2_grafo
	                        SELECT pep2,grafo,null,'0','".$this->session->userdata('userSession')."','".date("Y-m-d h:m:s")."' FROM import_pep2_grafo WHERE grafo NOT IN (SELECT grafo FROM pep2_grafo);");
	                        if ($this->db->trans_status() === TRUE) {
	                            $this->db->trans_commit();
	                            $data['error']    = EXIT_SUCCESS;
	                        }else{
	                            throw new Exception('ERROR INSERT GRAFOS FROM IMPORT PEP2 GRAFO');
	                        }
                        }else{
    	                    throw new Exception('ERROR DELETE SALTOS LINEA import_pep2_grafo WHERE pep2 LIKE %PEP%');
    	                }    	                    
	                }else{
	                    throw new Exception('ERROR DELETE FROM import_pep2_grafo WHERE pep2 LIKE %PEP%');
	                }
	            }else{
	                throw new Exception('ERROR LOAD DATA, Reportar al administrador');
	            }
	        } else {
	            throw new Exception('ERROR LOAD TRUNCATE, Reportar al administrador');
	        }
	    }catch(Exception $e){
	        $this->db->trans_rollback();
	        $data['msj'] = $e->getMessage();
	    }
	    return $data;
	}
	
	function   getSisegoPep2Grafo(){
	    $Query = " SELECT *,(CASE WHEN estado IN (3,4) THEN 'INACTIVO' ELSE 'ACTIVO' END) as situacion FROM sisego_pep2_grafo ORDER BY sisego;";
	    $result = $this->db->query($Query,array());
	    return $result;
	}
	
	function deleteSisegoPepGrafo($idSisegoPepgrafo){
	    $rpta['error'] = EXIT_ERROR;
	    $rpta['msj'] = null;
	    try{
	
	        $this->db->trans_begin();
	        $this->db->where('id', $idSisegoPepgrafo);
	        $this->db->delete('sisego_pep2_grafo');
	        $this->db->trans_complete();
	        if ($this->db->trans_status() === FALSE) {
	            $this->db->trans_rollback();
	            throw new Exception("Error al Eliminar Sisego - Pep2 - Grafo");
	        }else {
	            $this->db->trans_commit();
	            $rpta['msj'] = 'Se elimino correctamente el Sisego - Pep2 - Grafo';
	            $rpta['error']  = EXIT_SUCCESS;
	        }
	
	    }catch(Exception $e){
	        $rpta['msj'] = $e->getMessage();
	    }
	    return $rpta;
	}
	
	function   loadDataImportSisegoPep2Grafo($pathFinal){
	    $data ['error']= EXIT_ERROR;
	    $data['msj'] = null;
	    try{
	        $this->db->trans_begin();
	        $this->db->from('import_sisego_pep2_grafo');
	        $this->db->truncate();
	        if ($this->db->trans_status() === TRUE) {
	            $this->db->query("LOAD DATA LOCAL INFILE '".$pathFinal."' INTO TABLE import_sisego_pep2_grafo");
	            if ($this->db->trans_status() === TRUE) {
	                $this->db->query("DELETE FROM import_sisego_pep2_grafo WHERE (substring(pep2,1,2) != 'P-' AND  substring(pep2,1,2) != 'S.')");
	                if ($this->db->trans_status() === TRUE) {
	                    $this->db->query("UPDATE import_sisego_pep2_grafo set grafo = replace(grafo,'\r','');");
	                    if ($this->db->trans_status() === TRUE) {//temporalmente cambiamos el 0 por el 3 para que no coja ninguna configuracion
    	                    $this->db->query("INSERT INTO sisego_pep2_grafo
						SELECT TRIM(sisego),TRIM(pep2),grafo,(CASE WHEN LENGTH(pep2) >= 20 THEN (CASE WHEN SUBSTRING(pep2,1,20) = 'P-0055-19-0660-00001' THEN 4 
																									  WHEN SUBSTRING(pep2,1,9) = 'P-0055-20' THEN 2 ELSE 3 END) ELSE 3 END) as estado, null,'".$this->session->userdata('userSession')."','".date("Y-m-d h:m:s")."',null,null FROM import_sisego_pep2_grafo WHERE TRIM(sisego) NOT IN (SELECT DISTINCT TRIM(sisego) FROM sisego_pep2_grafo);");
    	                    if ($this->db->trans_status() === TRUE) {
    	                        $this->db->trans_commit();
    	                        $data['error']    = EXIT_SUCCESS;
    	                    }else{
    	                        throw new Exception('ERROR INSERT GRAFOS FROM IMPORT SISEGO PEP2 GRAFO');
    	                    }
	                    }else{
	                        throw new Exception('ERROR DELETE SALTOS DE LINEA GRAFOS FROM IMPORT SISEGO PEP2 GRAFO');
	                    }
	                }else{
	                    throw new Exception('ERROR DELETE FROM import_sisego_pep2_grafo WHERE sisego LIKE %SISEGO%');
	                }
	            }else{
	                throw new Exception('ERROR LOAD DATA, Reportar al administrador');
	            }
	        } else {
	            throw new Exception('ERROR LOAD TRUNCATE, Reportar al administrador');
	        }
	    }catch(Exception $e){
	        $this->db->trans_rollback();
	        $data['msj'] = $e->getMessage();
	    }
	    return $data;
	}
	
	function   getItemplanPep2Grafo(){
	    $Query = " SELECT * FROM itemplan_pep2_grafo WHERE estado <> 3 ORDER BY itemplan;";
	    $result = $this->db->query($Query,array());
	    return $result;
	}
	
	function   loadDataImportItemPep2Grafo($pathFinal){
	    $data ['error']= EXIT_ERROR;
	    $data['msj'] = null;
	    try{
	        $this->db->trans_begin();
	        $this->db->from('import_itemplan_pep2_grafo');
	        $this->db->truncate();
	        if ($this->db->trans_status() === TRUE) {
	            $this->db->query("LOAD DATA LOCAL INFILE '".$pathFinal."' INTO TABLE import_itemplan_pep2_grafo");
	            if ($this->db->trans_status() === TRUE) {
	                $this->db->query("DELETE FROM import_itemplan_pep2_grafo WHERE (substring(pep2,1,2) != 'P-' AND  substring(pep2,1,2) != 'S.')");
	                if ($this->db->trans_status() === TRUE) {
	                    $this->db->query("UPDATE import_itemplan_pep2_grafo set grafo = replace(grafo,'\r','');");
	                    if ($this->db->trans_status() === TRUE) {
	                        //$this->db->query("INSERT INTO itemplan_pep2_grafo SELECT TRIM(itemplan),TRIM(pep2), TRIM(grafo),'0',null, TRIM(area),'".$this->session->userdata('userSession')."','".date("Y-m-d h:m:s")."' FROM import_itemplan_pep2_grafo WHERE grafo NOT IN (SELECT grafo FROM itemplan_pep2_grafo);");
	                        $this->db->query("INSERT INTO itemplan_pep2_grafo SELECT TRIM(itemplan),TRIM(pep2), TRIM(grafo),'0',null, TRIM(area),'".$this->session->userdata('userSession')."','".date("Y-m-d h:m:s")."' FROM import_itemplan_pep2_grafo");
							if ($this->db->trans_status() === TRUE) {
	                            $this->db->trans_commit();
	                            $data['error']    = EXIT_SUCCESS;
	                        }else{
	                            throw new Exception('ERROR INSERT GRAFOS FROM IMPORT ITEMPLAN PEP2 GRAFO');
	                        }	                        
                        }else{
                            throw new Exception('ERROR CLEAR SALTOS DE LINEA FROM IMPORT ITEMPLAN PEP2 GRAFO');
                        }
	                }else{
	                    throw new Exception('ERROR DELETE FROM import_itemplan_pep2_grafo WHERE sisego LIKE %SISEGO%');
	                }
	            }else{
	                throw new Exception('ERROR LOAD DATA, Reportar al administrador');
	            }
	        } else {
	            throw new Exception('ERROR LOAD TRUNCATE, Reportar al administrador');
	        }
	    }catch(Exception $e){
	        $this->db->trans_rollback();
	        $data['msj'] = $e->getMessage();
	    }
	    return $data;
	}
	
	function deleteItemPepGrafo($idSisegoPepgrafo){
	    $rpta['error'] = EXIT_ERROR;
	    $rpta['msj'] = null;
	    try{
	
	        $this->db->trans_begin();
	        $this->db->where('id', $idSisegoPepgrafo);
	        $this->db->delete('itemplan_pep2_grafo');
	        $this->db->trans_complete();
	        if ($this->db->trans_status() === FALSE) {
	            $this->db->trans_rollback();
	            throw new Exception("Error al Eliminar Itemplan - Pep2 - Grafo");
	        }else {
	            $this->db->trans_commit();
	            $rpta['msj'] = 'Se elimino correctamente el Itemplan - Pep2 - Grafo';
	            $rpta['error']  = EXIT_SUCCESS;
	        }
	
	    }catch(Exception $e){
	        $rpta['msj'] = $e->getMessage();
	    }
	    return $rpta;
	}
	
	/**************MIGUEL RIOS 11062018***************************/
	function deletePep2Grafo($idPep2,$idGrafo){
	    $rpta['error'] = EXIT_ERROR;
	    $rpta['msj'] = null;
	    try{
	
	        $this->db->trans_begin();
	        $this->db->where(array('pep2'=>$idPep2,'grafo'=>$idGrafo));
	        $this->db->limit(1);
	        $this->db->delete('pep2_grafo');
	        $this->db->trans_complete();
	        if ($this->db->trans_status() === FALSE) {
	            $this->db->trans_rollback();
	            throw new Exception("Error al Eliminar Pep2 - Grafo");
	        }else {
	            $this->db->trans_commit();
	            $rpta['msj'] = 'Se elimino correctamente el Pep2 - Grafo';
	            $rpta['error']  = EXIT_SUCCESS;
	        }
	
	    }catch(Exception $e){
	        $rpta['msj'] = $e->getMessage();
	    }
	    return $rpta;
	}


	function verificaPEP($idPep){
	    $query="SELECT count(1) as existe from sap_detalle where pep1 = ?;";
	    $result = $this->db->query($query,array(trim($idPep)));
	    $dato =$result->row()->existe;

	    return $dato;
	}


	function insert_update_MontoPEP($idPep,$monto,$flag){
	    $rpta['error'] = EXIT_ERROR;
	    $rpta['msj'] = null;
	    try{
			

	    	if($flag==0){
	    		////***insert pep y monto***/////
	    		$tipoSap=substr($idPep, 0, 1);

	    		if(strtoupper($tipoSap)==='P'){
	    			$tipoSap=1;
	    		}else{
	    			$tipoSap=2;
	    		}

	    		$dataInsert = array(
	            "pep1"  => $idPep,
	            "monto_inicial" => $monto,
	            "monto_temporal" => $monto,
	            "monto_real" => $monto,
	            "tipo" => $tipoSap);

	            $this->db->insert('sap_detalle', $dataInsert);

		        if($this->db->affected_rows() != 1) {
		            $this->db->trans_rollback();
		            throw new Exception('Error al insertar en la tabla SAP Detalle');
		        }else{
		           	$this->db->trans_commit();

		            $data['error']    = EXIT_SUCCESS;
		            $data['msj']      = 'Se inserto correctamente ';
		        }



	    	}

	        if($flag==1){
	        	////***update al monto segun la pep***/////

	        	$dataUpdate = array(
	           		"monto_inicial" => $monto,
	            	"monto_temporal" => $monto,
	            	"monto_real" => $monto
	        	);
        	
        		$this->db->where('pep1', $idPep);
        		$this->db->update('sap_detalle', $dataUpdate);

	        
            	if ($this->db->trans_status() === FALSE) {
            	    throw new Exception('Hubo un error al actualizar la tabla sap_detalle con la pep1='.$idPep);
            	}else{
            	    $data['error']    = EXIT_SUCCESS;
            	    $data['msj']      = 'Se actualizo correctamente ';
            	    $this->db->trans_commit();
            	}
	        }
	
	    }catch(Exception $e){
	        $data['msj'] = $e->getMessage();
	    }
	    return $data;
	}

/**************************************************************************/
	
	function insertSisegoPep2Grafo($sisego, $pep2, $grafo, $itemplan, $fecha){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{//temporalmente cambiamos el 0 por el 3 para que no coja ninguna configuracion
			$estado = 2;	//toda pep de sisegos se desactiva, lo normal es 0       
	        if(strlen($pep2)>=20){
	            $pep1 = substr($pep2,0,20);
	            if($pep1 == 'P-0055-19-0660-00001'){
	                $estado = 4;
	            }else{
					$pep11 = substr($pep2,0,9);
					if($pep11 == 'P-0055-20'){						
						$estado = 2;
					}
				}
	        }
	        $this->db->trans_begin();
	        $insert = array(
	            "sisego"   => $sisego,
	            "pep2"     => $pep2,
	            "grafo"    => $grafo,
	            "estado"   => $estado,
	            "usuario"  => 'from_sisegos',
	            "fec_registro" => $fecha
	        );
	
	        $this->db->insert('sisego_pep2_grafo', $insert);
	        if($this->db->affected_rows() != 1) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar el sisego - Pep2 - grafo');
	        }else{
	            $dataInsert = array(	                 
	                "tabla" => "from_sisegos",
	                "actividad" => "registrar grafo",
	                "itemplan" => $itemplan,
	                "fecha_registro" => $fecha,
	                "id_usuario" => 0,
	                "itemplan_default" => 'pep2='.$pep2.'|grafo='.$grafo.'|sisego='.$sisego
	            );
	            
	            $this->db->insert('log_planobra', $dataInsert);
	            if($this->db->affected_rows() != 1) {
	                $this->db->trans_rollback();
	                throw new Exception('Error al insertar en log_planobra');
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

	function updateSisegoPep2Grafo($sisego, $pep2, $grafo, $itemplan, $fecha){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{			
			$this->db->trans_begin();
			
			$this->db->where('sisego', $sisego);
			$this->db->update('sisego_pep2_grafo', array("estado"   => 3));

			if($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
	            throw new Exception('Error al insertar el sisego - Pep2 - grafo');
			} else {
				$insert = array(
									"sisego"   => $sisego,
									"pep2"     => $pep2,
									"grafo"    => $grafo,
									"estado"   => 2,
									"usuario"  => 'from_sisegos',
									"fec_registro" => $fecha
								);
		
				$this->db->insert('sisego_pep2_grafo', $insert);
				if($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					throw new Exception('Error al insertar el sisego - Pep2 - grafo');
				}else{
					$dataInsert = array(	                 
						"tabla" => "from_sisegos",
						"actividad" => "registrar - update grafo",
						"itemplan" => $itemplan,
						"fecha_registro" => $fecha,
						"id_usuario" => 1645,
						"itemplan_default" => 'pep2='.$pep2.'|grafo='.$grafo.'|sisego='.$sisego
					);
					
					$this->db->insert('log_planobra', $dataInsert);
					if($this->db->affected_rows() != 1) {
						$this->db->trans_rollback();
						throw new Exception('Error al insertar en log_planobra');
					}else{
						$this->db->trans_commit();
						$data['error']    = EXIT_SUCCESS;
						$data['msj']      = 'Se ingreso correctamente!';
					}
				}
			}	
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
}