<?php
class M_expediente2 extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
    function   getPtrToLiquidacion($subPry, $eecc, $zonal, $itemPlan, $mesEjec, $area){
        $Query = "  SELECT po.itemPlan, wu.ptr, wu.est_innova, wu.valoriz_material, wu.valoriz_m_o, sp.subProyectoDesc, wu.jefatura, wu.eecc, wu.f_ult_est, SEC_TO_TIME(TIMESTAMPDIFF(SECOND, STR_TO_DATE(wu.f_ult_est, '%d/%m/%Y %H:%i:%s'), NOW())) as horas, (CASE
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
			END) AS fecha, po.fechaPrevEjec, wu.desc_area, ep.id_expediente, ep.tpo_espera
			FROM web_unificada wu LEFT JOIN detalleplan dp
			ON wu.ptr = dp.poCod
			LEFT JOIN planobra po
			ON dp.itemPlan = po.itemPlan
			LEFT JOIN  subproyectoestacion se
			ON dp.idSubProyectoEstacion = se.idSubProyectoEstacion
			LEFT JOIN subproyecto sp
			ON sp.idSubProyecto = se.idSubProyecto

			LEFT JOIN expediente_ptr ep
			ON wu.ptr = ep.ptr
			OR dp.itemPlan = ep.itemPlan

			WHERE wu.est_innova LIKE '05 -%'" ;

        if($subPry!=''){
            $Query .= " AND sp.subProyectoDesc LIKE '%".$subPry."%' ";
        }
        if($eecc!=''){
            $Query .= " AND wu.eecc LIKE '%".$eecc."%' ";
        }
        if($zonal!=''){
             $Query .= " AND wu.jefatura REGEXP '".str_replace(',','|',$zonal)."'";
        }
        if($itemPlan=='SI'){
            $Query .=  " AND po.itemPlan is not null";
        } 
        if($itemPlan=='NO'){
            $Query .=  " AND po.itemPlan is null";
        }
        if($mesEjec!=''){
            $Query .=  " HAVING fecha ='".$mesEjec."'";
        }

        if($area!=''){
            if(strlen($area)>3){
                $Query .=  " AND area_desc = '".$area."'";
            }else{
                $Query .=  " AND desc_area = '".$area."'";
            }            
        }/*
        if($estado!=''){
            $Query .= " AND estado LIKE '".$estado."%' ";
        } */       
       
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