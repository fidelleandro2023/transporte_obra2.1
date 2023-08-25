<?php
class M_prediseno extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
    function   getPtrToLiquidacion($subPry, $eecc, $zonal, $itemPlan, $mesEjec, $area, $estado){
        $Query = "  SELECT * 
                      FROM pre_diseno";
                
       
	    $result = $this->db->query($Query,array());	   
	    return $result;
	}



















	/* MODELO
	function   getPtrToLiquidacion($subPry, $eecc, $zonal, $itemPlan, $mesEjec, $area, $estado){
        $Query = "  SELECT *,SEC_TO_TIME(TIMESTAMPDIFF(SECOND, STR_TO_DATE(fecSol, '%d/%m/%Y %H:%i:%s'), NOW())) as horas 
                    FROM web_unificada_det 
                    WHERE ( estado_asig_grafo = '".ESTADO_SIN_GRAFO."' OR estado_asig_grafo = '".ESTADO_CON_GRAFO_TEMPORAL."' )" ;
        if($subPry!=''){
            $Query .= " AND subProy LIKE '%".$subPry."%' ";
        }
        if($eecc!=''){
            $Query .= " AND eecc LIKE '%".$eecc."%' ";
        }
        if($zonal!=''){
             $Query .= " AND zonal REGEXP '".str_replace(',','|',$zonal)."'";
        } 
        if($itemPlan=='SI'){
            $Query .=  " AND itemPlan is not null";
        }
        if($itemPlan=='NO'){
            $Query .=  " AND itemPlan is null";
        }
        if($mesEjec!=''){
            $Query .=  " AND fec_prevista_ejec ='".$mesEjec."'";
        }
        if($area!=''){
            if(strlen($area)>3){
                $Query .=  " AND area_desc = '".$area."'";
            }else{
                $Query .=  " AND desc_area = '".$area."'";
            }            
        }
        if($estado!=''){
            $Query .= " AND estado LIKE '".$estado."%' ";
        }        
       
	    $result = $this->db->query($Query,array());	   
	    return $result;
	}
	*/

	/*
	function updateDetalleProducto($idPtr, $grafo, $from, $vale_re, $area_desc, $itemP){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $dataFrom = explode("-", $from);
	        $this->db->trans_begin();	
	        
	        $data = array(
	            "vale_reserva"  => $vale_re	            
	        );
	        $this->db->where('ptr', $idPtr);
	        $this->db->update('web_unificada_det', $data);
	        if($this->db->affected_rows() != 1) {
	            throw new Exception('Hubo un error al actualizar en web_unificada');
	        }
	        
	        $data = array(
	            "estado_asig_grafo"  => ESTADO_CON_GRAFO_ULTILIZADO,
	            "fecha_asig_grafo" => date("d/m/Y H:i:s"),
	            "usua_asig_grafo" => $this->session->userdata('userSession')
	        );
	        $this->db->where('ptr', $idPtr);
	        $this->db->update('web_unificada_det', $data);
	        if($this->db->affected_rows() != 1) {
	            throw new Exception('Hubo un error al actualizar en web_unificada_det');
	        }
	        	        
	        if($dataFrom[0] == DATA_FROM_ITEMPLAN_PEP2_GRAFO){
	            $data = array(
	                "estado"  => ESTADO_CON_GRAFO_ULTILIZADO
	            );
	            log_message('error', '$grafo:'.$dataFrom[1],true);
	            $this->db->where('id', intval($dataFrom[1]));
	            $this->db->update('itemplan_pep2_grafo', $data);
	            if($this->db->affected_rows() != 1) {
	                //throw new Exception($this->db->);
	                throw new Exception('Hubo un error al actualizar en pep2_grafo');
	            }
	        }else if($dataFrom[0] == DATA_FROM_SISEGO_PEP2_GRAFO){
	            $data = array(
	                "estado"  => ESTADO_CON_GRAFO_ULTILIZADO
	            );
	            log_message('error', '$grafo:'.$dataFrom[1],true);
	            $this->db->where('id', intval($dataFrom[1]));
	            $this->db->update('sisego_pep2_grafo', $data);
	            if($this->db->affected_rows() != 1) {
	                //throw new Exception($this->db->);
	                throw new Exception('Hubo un error al actualizar en pep2_grafo');
	            }
	        }else if($dataFrom[0] == DATA_FROM_PEP2_GRAFO){
	            $data = array(
	                "estado"  => ESTADO_CON_GRAFO_ULTILIZADO
	            );
	            log_message('error', '$grafo:'.$dataFrom[1],true);
	            $this->db->where('id', intval($dataFrom[1]));
	            $this->db->update('pep2_grafo', $data);
	            if($this->db->affected_rows() != 1) {
	                //throw new Exception($this->db->);
	                throw new Exception('Hubo un error al actualizar en pep2_grafo');
	            }
	        }	        
	        
	        $campo_ptr = '';
	        $campo_des = '';
	        SWITCH ($area_desc){
	            case 'MAT_COAX';
	               $campo_ptr = 'mat_coax_ptr';
	               $campo_des = 'mat_coax_est';
	                break;
                case 'MAT_COAX_OC';
                    $campo_ptr = 'mat_coax_oc_ptr';
                    $campo_des = 'mat_coax_oc_est';
	                break;
                case 'MAT_FUENTE';
                    $campo_ptr = 'mat_fuente_ptr';
                    $campo_des = 'mat_fuente_est';
	                break;
                case 'MAT_FO';
                    $campo_ptr = 'mat_fo_ptr';
                    $campo_des = 'mat_fo_est';
	                break;
                case 'MAT_FO_OC';
                    $campo_ptr = 'mat_fo_oc_ptr';
                    $campo_des = 'mat_fo_oc_est';
	                break;
                case 'MAT_ENER';
                    $campo_ptr = 'mat_ener_ptr';
                    $campo_des = 'mat_ener_est';
	                break;
	        }
	         
	        $data = array(
	            $campo_ptr => $idPtr,
	            $campo_des => '02 - VALORIZADA CON VALE DE RESERVA'
	            
	        );
	        $this->db->where('itemPlan', $itemP);
	        $this->db->update('web_unificada_fa', $data);
	        if($this->db->affected_rows() != 1) {
	            throw new Exception('Hubo un error al actualizar en web_unificada_fa');
	        }
	        
	        //Fin
	        if ($this->db->trans_status() === FALSE) {
	            $this->db->trans_rollback();
	        } else {
	            $data['error']    = EXIT_SUCCESS;
	            $data['msj']      = 'Se actualizo correctamente!';	            
	            $this->db->trans_commit();
	        }	      
	         
	    }catch(Exception $e){
	        $data['msj']   = 'Error en la transaccion!';
	        $this->db->trans_rollback();
	    }
	    return $data;
	}*/
	/*
	function updatePtrTo01($idPtr, $grafo){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $data = array(
	            "estado"  => '01 - APROBADA VALORIZADA',
	            "fec_aprob" => date("d/m/Y H:i:s"),
	            "usua_aprob" => $this->session->userdata('userSession')
	        );
	        $this->db->where('ptr', $idPtr);
	        $this->db->update('web_unificada_det', $data);
	        if($this->db->affected_rows() != 1) {
	            throw new Exception('Hubo un error al actualizar en web_unificada_det');
	        }
	         	      
	        //Fin
	        if ($this->db->trans_status() === FALSE) {
	            $this->db->trans_rollback();
	        } else {
	            $data['error']    = EXIT_SUCCESS;
	            $data['msj']      = 'Se actualizo correctamente!';
	            $this->db->trans_commit();
	        }
	         
	
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}*/
	
}