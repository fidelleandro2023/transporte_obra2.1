<?php
class M_crecimiento_vertical extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}

function saveItemplanCV($itemplan, $idProy, $idSubproy, $idCentral, $idzonal, $eecc, $eelec, $estadoplan, $fase,
	                        $fechaInicio, $nombreProyecto, $indicador, $uip, $cordx, $cordy, $cantidadTroba, $departamento, 
	                        $provincia, $distrito, $idTipoUrba, $nombreUrba, $idTipoVia, $direccion, $numero, $manzana, $lote,
	                        $blocke, $num_pisos, $num_depa, $num_depa_habi, $avance, $fec_termino, $observacion, $ruc, $nombre_constru, $contacto_1, $telefono_1_1, $telefono_1_2, 
                            $email_1, $contacto_2, $telefono_2_1, $telefono_2_2, $email_2, $accion, $estado_edifi, $competencia, $prioridad, $operador){
	    
	        $data['error'] = EXIT_ERROR;
	        $data['msj']   = null;
	        try{
	            
	            $this->db->trans_begin();	            
	            $idProvincia=1;
	            $idDepartamento=1;
	            $hasAdelanto='0';
	      
	            $dataInsert = array(
	                "itemPlan"         =>  $itemplan,
	                "nombreProyecto"   =>  strtoupper($nombreProyecto),
	                "coordX"           =>  $cordx,
	                "coordY"           =>  $cordy,
	                "indicador"        =>  $indicador,
	                "cantidadTroba"    =>  intval($cantidadTroba),
	                "uip"              =>  intval($uip),
	                "fechaInicio"      =>  $fechaInicio,
	                "idEstadoPlan"     =>  intval($estadoplan),
	                "idFase"           =>  intval($fase),
                    "idCentral"        =>  intval($idCentral),
	                "idEmpresaElec"    =>  intval($eelec),
	                "idProvincia"      =>  intval($idProvincia),
	                "idDepartamento"   =>  intval($idDepartamento),
	                "idSubProyecto"    =>  intval($idSubproy),
	                "idZonal"          =>  intval($idzonal),
	                "idEmpresaColab"   =>  intval($eecc),
	                "idEmpresaColabDiseno"   =>  intval($eecc),
	                "hasAdelanto"      =>  $hasAdelanto,
	                "has_cotizacion"   =>  '0'
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
	                    "fecha_visita_campo"    =>  date("d/m/Y H:i:s"),
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
	                    "estado_aprob"          => '0',
	                    "usua_crea"             =>  $this->session->userdata('userSession'),
	                    "fec_crea"              =>  date("d/m/Y H:i:s"),
	                    "estado_edificio"       =>  $estado_edifi,
	                    "competencia"           =>  $competencia,
	                    "prioridad"             =>  $prioridad,
	                    "operador"              =>  $operador
	                );
	                
	                $this->db->insert('planobra_detalle_cv', $dataInsertCv);
	                if($this->db->affected_rows() != 1) {
	                    $this->db->trans_rollback();
	                    throw new Exception('Error al insertar el plan de obra');
	                }else{
	                    
	                    //IF ACCION $accion  = 1 INSERT ELSE UPDATE
	                    if($accion == '1'){//INSERT
	                        $dataInsertC = array(
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
	                        
	                        $this->db->insert('constructora_cv', $dataInsertC);
	                        if($this->db->affected_rows() != 1) {
	                            $this->db->trans_rollback();
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

	function   getInfoConstructora($ruc){
	    $Query = " SELECT * FROM constructora_cv WHERE ruc = ?" ;
	    $result = $this->db->query($Query,array($ruc));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
	function   getSubProByNodo($mdf){
	    $Query = "SELECT count(1) as cont, flg_subproByNodoCV FROM central where idCentral = ?" ;
	    $result = $this->db->query($Query,array($mdf));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
	function getBucleIntegralBySubProy($idSubProyecto) {
		$sql = "SELECT idTipoSubProyecto
				  FROM subproyecto
				 WHERE idSubProyecto = ?";
		$result = $this->db->query($sql,array($idSubProyecto));
		return $result->num_rows() == 1 ? $result->row_array()['idTipoSubProyecto'] : null;
	}
}