<?php
class M_bandeja_pre_certifica extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
    function   getPtrToLiquidacion($subPry, $eecc, $zonal, $itemPlan, $mesEjec, $area, $estado, $from){
        $Query = "   SELECT wud.*,SEC_TO_TIME(TIMESTAMPDIFF(SECOND, STR_TO_DATE(wud.fecSol, '%d/%m/%Y %H:%i:%s'), NOW())) as horas, po.idEstadoPlan  
                    FROM web_unificada_det wud LEFT JOIN planobra po ON wud.itemplan = po.itemplan LEFT JOIN subproyecto sp ON po.idSubProyecto = sp.idSubProyecto
                    WHERE ( wud.estado_asig_grafo = '".ESTADO_SIN_GRAFO."' OR wud.estado_asig_grafo = '".ESTADO_CON_GRAFO_TEMPORAL."' )" ;
        if($subPry!=''){
            $Query .= " AND wud.subProy REGEXP '".str_replace(',','|',$subPry)."'";
        }
        if($eecc!=''){
            $Query .= " AND wud.eecc LIKE '%".$eecc."%' ";
        }
        if($zonal!=''){
            $Query .= " AND wud.zonal REGEXP '".str_replace(',','|',$zonal)."'";
        } 
        if($itemPlan=='SI'){
            $Query .=  " AND wud.itemPlan is not null";
        }
        if($itemPlan=='NO'){
            $Query .=  " AND wud.itemPlan is null";
        }
        if($mesEjec!=''){
            $Query .=  " AND wud.fec_prevista_ejec ='".$mesEjec."'";
        }
        if($area!=''){
            if(strlen($area)>3){
                $Query .=  " AND wud.area_desc = '".$area."'";
            }else{
                $Query .=  " AND wud.desc_area = '".$area."'";
            }            
        }
        if($from  == FROM_BANDEJA_APROBACION){
            if($estado!=''){
                $count = 0;
                $array = explode(',', $estado);
                foreach($array as $row){
                    if ($count == 0){
                        $Query .= " AND ( substring(wud.estado,1,".strlen($row).") =  '".$row."'";
                        $count++;
                    }else{
                        $Query .= " OR substring(wud.estado,1,".strlen($row).") =  '".$row."'";
                    }
                    
                }
                $Query .= " )";
            }else{
                $Query .= " AND ( substring(wud.estado,1,3) =  '".ESTADO_PRE_APROB."' OR substring(wud.estado,1,2) =  '".ESTADO_APROB_01."') ";
            }
            $Query .= " AND CASE WHEN po.idEstadoPlan = 4  and desc_area = 'MO' and estacion_desc != 'DISENO' THEN (SELECT COUNT(1) as count FROM itemplan_expediente where estado = 'ACTIVO' AND estado_final = 'FINALIZADO' and itemplan = wud.itemPlan) > 0  ELSE TRUE END 
AND CASE 
	WHEN estacion_desc = 'DISENO'
	THEN SUBSTRING(estado,1,2) ='01'  
ELSE TRUE 
END ";
        }else if($from == FROM_BANDEJA_PRE_APROBACION){
            if($estado!=''){
                $count = 0;
                $array = explode(',', $estado);
                foreach($array as $row){
                    if ($count == 0){
                        $Query .= " AND ( substring(wud.estado,1,".strlen($row).") =  '".$row."'";
                        $count++;
                    }else{
                        $Query .= " OR substring(wud.estado,1,".strlen($row).") =  '".$row."'";
                    }
                    
                }
                $Query .= " )";
            }else{
                $Query .= " AND  substring(wud.estado,1,2) !=  '".ESTADO_APROB_01."' ";
                
            }
            $Query .= " AND CASE WHEN wud.desc_area = 'MO' AND po.idEstadoPlan = 4  THEN (SELECT COUNT(1) as count FROM itemplan_expediente where estado = 'ACTIVO' AND estado_final = 'FINALIZADO' and itemplan = wud.itemPlan) > 0  ELSE TRUE END
                        AND estacion_desc != 'DISENO'";
        }else if($from == FROM_BANDEJA_PRE_APROBACION_DISENO){
            if($estado!=''){
                $count = 0;
                $array = explode(',', $estado);
                foreach($array as $row){
                    if ($count == 0){
                        $Query .= " AND ( substring(wud.estado,1,".strlen($row).") =  '".$row."'";
                        $count++;
                    }else{
                        $Query .= " OR substring(wud.estado,1,".strlen($row).") =  '".$row."'";
                    }
                    
                }
                $Query .= " )";
            }else{
                $Query .= " AND  substring(wud.estado,1,2) !=  '".ESTADO_APROB_01."' ";
                
            }
            $Query .= " AND estacion_desc = 'DISENO' ";
        }
	    $result = $this->db->query($Query,array());	   
	    return $result;
	}

	function updateDetalleProducto($idPtr, $grafo, $from, $vale_re, $area_desc, $itemP){
	    $dataSalida['error'] = EXIT_ERROR;
	    $dataSalida['msj']   = null;
	    try{
	        $dataFrom = explode("-", $from);
	        $this->db->trans_begin();	
	        
	        $data = array(
	            "vale_reserva"  => $vale_re,
	            "estado_asig_grafo"  => ESTADO_CON_GRAFO_ULTILIZADO,
	            "fecha_asig_grafo" => date("d/m/Y H:i:s"),
	            "usua_asig_grafo" => $this->session->userdata('userSession')
	        );
	        $this->db->where('ptr', $idPtr);
	        $this->db->update('web_unificada_det', $data);
	        if($this->db->affected_rows() == 0) {
	            throw new Exception('Hubo un error al actualizar en web_unificada_det');
	        }
	        	        
	        if($dataFrom[0] == DATA_FROM_ITEMPLAN_PEP2_GRAFO){
	            $data = array(
	                "estado"  => ESTADO_CON_GRAFO_ULTILIZADO
	            );
	            $this->db->where('id', intval($dataFrom[1]));
	            $this->db->update('itemplan_pep2_grafo', $data);
	           /* if($this->db->affected_rows() != 1) {
	                //throw new Exception($this->db->);
	                throw new Exception('Hubo un error al actualizar en pep2_grafo itemplan');
	            }*/
	        }else if($dataFrom[0] == DATA_FROM_SISEGO_PEP2_GRAFO){
	            /*
				$data = array(
	                "estado"  => ESTADO_CON_GRAFO_ULTILIZADO
	            );
	            $this->db->where('id', intval($dataFrom[1]));
	            $this->db->update('sisego_pep2_grafo', $data);
	            if($this->db->affected_rows() != 1) {
	                //throw new Exception($this->db->);
	                throw new Exception('Hubo un error al actualizar en pep2_grafo sisego');
	            }*/
	        }else if($dataFrom[0] == DATA_FROM_PEP2_GRAFO){
	            $data = array(
	                "estado"  => ESTADO_CON_GRAFO_ULTILIZADO
	            );
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
	         
                if($campo_des!=''){
	           $data = array(
	               $campo_ptr => $idPtr,
	               $campo_des => '02 - VALORIZADA CON VALE DE RESERVA'
	            
	           );
	           $this->db->where('itemPlan', $itemP);
	           $this->db->update('web_unificada_fa', $data);
	           if ($this->db->trans_status() === FALSE) {
	               throw new Exception('Hubo un error al actualizar en web_unificada_fa');
	           }
	        }
	        $data = array(
	            'idEstadoPtr' => '2',
	            'est_innova' => '02 - VALORIZADA CON VALE DE RESERVA'
	      
	        );
	        $this->db->where('ptr', $idPtr);
	        $this->db->update('web_unificada', $data);
	        if ($this->db->trans_status() === FALSE) {
	            throw new Exception('Hubo un error al actualizar en web_unificada');
	        }
	        
	        //Fin
	        if ($this->db->trans_status() === FALSE) {
	            $this->db->trans_rollback();
	        } else {
	            $dataSalida['error']    = EXIT_SUCCESS;
	            $dataSalida['msj']      = 'Se actualizo correctamente!';	            
	            $this->db->trans_commit();
	        }
	         
	    }catch(Exception $e){
	        $dataSalida['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $dataSalida;
	}
	
	 function updatePtrTo01($idPtr, $grafo){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $data = array(
	            "estado"  => '01 - APROBADA VALORIZADA',
	            "fec_aprob" => date("d/m/Y H:i:s"),
	            "usua_aprob" => $this->session->userdata('userSession'),
	            "estado_asig_grafo" => '0'
	        );
	        $this->db->where('ptr', $idPtr);
	        $this->db->update('web_unificada_det', $data);
	        if($this->db->affected_rows() == 0) {
	            throw new Exception('Hubo un error al actualizar en web_unificada_det');
	        }	        
	        
	        $this->db->query("SELECT getGrafoOnePTR('".$idPtr."');");
	        if ($this->db->trans_status() === TRUE) {
	            $this->db->trans_commit();
	            $data['error']    = EXIT_SUCCESS;
	            $data['msj']      = 'Se actualizo correctamente!';
	        }else{
	            $this->db->trans_rollback();
	        }	         
	
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
	
	function   getBandejaPreMo($SubProy,$eecc,$zonal,$itemPlan,$mesEjec,$expediente, $idFase){
	    $Query = " SELECT po.itemPlan, po.idSubProyecto, po.indicador ,sp.subProyectoDesc, z.zonalDesc, ec.empresaColabDesc, ep.estadoPlanDesc,f.faseDesc AS fase_desc,
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
                            (
                     SELECT getCountFinalizados(po.itemplan,sp.idSubProyecto)
                            ) as situacion
						FROM planobra po, fase f,subproyecto sp, zonal z, empresacolab ec, estadoplan ep,
						(SELECT tb1.itemplan FROM (SELECT iea.itemplan FROM itemplanestacionavance iea LEFT JOIN itemplan_expediente ie 
						ON iea.itemplan = ie.itemplan
						AND iea.idEstacion = ie.idEstacion
						WHERE iea.porcentaje = '100'
						AND (ie.estado_final IS NULL OR ie.estado_final != 'FINALIZADO')
						group by iea.itemplan) tb1, planobra po, subproyecto ssp
						where tb1.itemplan = po.itemplan
						AND po.idSubProyecto = ssp.idSubProyecto
	                    AND ssp.idProyecto != 4
                        AND ssp.idSubProyecto NOT IN (146,182,7,8)
						and (po.idEstadoPlan = ".ID_ESTADO_PRE_DISENIO." OR po.idEstadoPlan = ".ID_ESTADO_DISENIO." OR po.idEstadoPlan = ".ID_ESTADO_PLAN_EN_OBRA." OR po.idEstadoPlan = ".ID_ESTADO_PRE_LIQUIDADO." OR po.idEstadoPlan = ".ID_ESTADO_DISENIO_EJECUTADO." OR po.idEstadoPlan = ".ID_ESTADO_TRUNCO.")
						UNION ALL 
						SELECT itemplan FROM planobra WHERE idEstadoPlan = 4
						UNION ALL
						SELECT DISTINCT(tb.itemplan) FROM (
                            SELECT  distinct po.itemplan
                                                FROM 	planobra po, subproyecto sp, planobra_po ppo 
                                                WHERE	po.idSubProyecto = sp.idSubProyecto
                                                AND		po.idEstadoPlan IN (1,2,3,7,9,10)
                                                AND 	(sp.idProyecto  = 4 OR sp.idSubProyecto IN (146,182,7,8))
                                                AND     po.itemplan = ppo.itemplan
                                                AND 	ppo.flg_tipo_area = 2
                                                AND     ppo.estado_po = 4
                            UNION ALL
                            SELECT  distinct po.itemplan
                                                FROM 	planobra po, subproyecto sp, detalleplan dp, web_unificada wu, 
                            							subproyectoestacion se, estacionarea ea, area a
                                                WHERE	po.idSubProyecto = sp.idSubProyecto
                                                AND		po.idEstadoPlan IN (1,2,3,7,9,10)
                                                AND 	(sp.idProyecto  = 4 OR sp.idSubProyecto IN (146,182,7,8))
                                                AND     po.itemplan = dp.itemplan
                                                AND 	dp.poCod = wu.ptr
                                                AND    	dp.idSubProyectoEstacion = se.idSubProyectoEstacion
                                                AND 	se.idEstacionArea = ea.idEstacionArea
                                                AND     ea.idArea = a.idArea
                                                AND 	a.tipoArea = 'MO'
                                                AND     substring(wu.est_innova,1,2) not in (08,04,07)) 
						    as tb) AS tmp  
            WHERE po.itemplan = tmp.itemplan
            AND po.idFase = f.idFase
            AND po.idSubProyecto = sp.idSubProyecto 
            AND po.idZonal = z.idZonal
            AND po.idEmpresaColab = ec.idEmpresaColab
            AND po.idEstadoPlan = ep.idEstadoPlan";
//            and po.idEstadoPlan = 4 and (SELECT COUNT(1) FROM itemplan_expediente WHERE estado_final = 'FINALIZADO' and itemplan = po.itemPlan ) = 0" ;
	    if($SubProy != ''){
	       $Query .= " AND sp.subProyectoDesc REGEXP '".str_replace(',','|',$SubProy)."'";
	    }
	    if($eecc != ''){
	        $Query .= " AND ec.empresaColabDesc LIKE '%".$eecc."%'";
	    }
	    if($zonal != ''){
	        $Query .= " AND z.zonalDesc = '".$zonal."'";
	    }
	    if($itemPlan != ''){
	        $Query .= " AND po.itemplan = '".$itemPlan."'";
	    }
	    if($idFase != ''){
	        $Query .= " AND po.idFase = '".$idFase."'";
	    }
	    
	    $certi = "";
	    if($expediente!=''){
	        if($expediente=='SI'){
	            $certi = ">";
	        }else if($expediente=='NO'){
	            $certi = "=";
	        }
	    }
	    
	    if($expediente!=''){
	        $Query .= " HAVING  situacion = '".$expediente."'";
	    }
	  	     	   
	    $result = $this->db->query($Query,array());
	  //  _log($this->db->last_query());
	    return $result;
	}
	
	function   getPtrByItemplan($itemplan,$idEstacion){
	     $Query = " SELECT 
                    dp.poCod as ptr,
                    dp.itemplan, 
                    s.subproyectoDesc,
                    a.areaDesc, 
                    substring(wud.estado,1,3) as estado_wud,
                    (CASE WHEN  po.estado_po is not null then UPPER(poe.estado) ELSE  substring(wu.est_innova,1,3) end) as estado_wu,
                    a.tipoArea as desc_area, 
					(CASE WHEN  wu.jefatura is not null then UPPER(wu.jefatura) ELSE c.jefatura end) as jefatura,
                    (CASE WHEN  wu.eecc is not null then UPPER(wu.eecc) ELSE ec.empresaColabDesc end) as eecc,
                    wu.idEstadoPtr,
                    wu.f_creac_prop,
					(CASE WHEN  po.estado_po is not null AND po.flg_tipo_area = 1 then po.costo_total else wu.valoriz_material end) as valor_material,
	                (CASE WHEN  po.estado_po is not null AND po.flg_tipo_area = 2 then po.costo_total else wu.valoriz_m_o end) as valor_m_o,
					(CASE WHEN  po.estado_po is not null then UPPER(po.vale_reserva) ELSE  TRIM(SUBSTRING_INDEX(wu.vr,':',-1)) end) as vr_wu,
                    (CASE WHEN  po.estado_po is not null then UPPER(po.vale_reserva) ELSE  wud.vale_reserva end) as vr_wud,
                    CASE WHEN pe.ptr is not null then 1 else 0 end as hasPtrExpe,
                    e.estacionDesc,
					s.idProyecto
                    from detalleplan dp 
                    INNER JOIN subproyectoestacion se ON dp.idSubProyectoEstacion = se.idSubProyectoEstacion 
                    INNER JOIN subproyecto s 		ON se.idSubProyecto = s.idSubProyecto 
                    INNER JOIN estacionarea ea 		ON se.idEstacionArea = ea.idEstacionArea 
                    INNER JOIN area a 				ON ea.idArea = a.idArea 
                    INNER JOIN estacion e 			ON ea.idEstacion = e.idEstacion
                    INNER JOIN planobra plan		ON dp.itemplan = plan.itemplan
                    INNER JOIN central	c			ON plan.idCentral = c.idCentral
                    INNER JOIN empresacolab	ec 		ON c.idEmpresaColab = ec.idEmpresaColab
                    LEFT JOIN web_unificada_det wud ON wud.ptr = dp.poCod 			AND wud.itemplan = dp.itemplan 
                    LEFT JOIN web_unificada wu 		ON wu.ptr = dp.poCod 
                    LEFT JOIN ptr_expediente pe 	ON dp.itemplan = pe.itemplan 	AND dp.poCod = pe.ptr
                    LEFT JOIN planobra_po	po JOIN po_estado poe ON po.estado_po = poe.idPoEstado
					ON dp.itemplan = po.itemplan AND dp.poCod = po.codigo_po
                    where dp.itemplan = ?
                    AND dp.poCod != 'NOREQUIERE'
                    AND (plan.paquetizado_fg = 1 OR plan.paquetizado_fg IS NULL)
                    and e.idEstacion = ?
                    
                    UNION ALL
                    SELECT 
                    dp.poCod as ptr,
                    dp.itemplan, 
                    s.subproyectoDesc,
                    a.areaDesc, 
                    substring(wud.estado,1,3) as estado_wud,
                    (CASE WHEN  po.estado_po is not null then UPPER(poe.estado) ELSE  substring(wu.est_innova,1,3) end) as estado_wu,
                    a.tipoArea as desc_area, 
					(CASE WHEN  wu.jefatura is not null then UPPER(wu.jefatura) ELSE c.jefatura end) as jefatura,
                    (CASE WHEN  wu.eecc is not null then UPPER(wu.eecc) ELSE ec.empresaColabDesc end) as eecc,
                    wu.idEstadoPtr,
                    wu.f_creac_prop,
					(CASE WHEN  po.estado_po is not null AND po.flg_tipo_area = 1 then po.costo_total else wu.valoriz_material end) as valor_material,
	                (CASE WHEN  po.estado_po is not null AND po.flg_tipo_area = 2 then po.costo_total else wu.valoriz_m_o end) as valor_m_o,
					(CASE WHEN  po.estado_po is not null then UPPER(po.vale_reserva) ELSE  TRIM(SUBSTRING_INDEX(wu.vr,':',-1)) end) as vr_wu,
                    (CASE WHEN  po.estado_po is not null then UPPER(po.vale_reserva) ELSE  wud.vale_reserva end) as vr_wud,
                    CASE WHEN pe.ptr is not null then 1 else 0 end as hasPtrExpe,
                    e.estacionDesc,
					s.idProyecto
                    from detalleplan dp 
                    INNER JOIN subproyectoestacion se ON dp.idSubProyectoEstacion = se.idSubProyectoEstacion 
                    INNER JOIN subproyecto s 		ON se.idSubProyecto = s.idSubProyecto 
                    INNER JOIN estacionarea ea 		ON se.idEstacionArea = ea.idEstacionArea 
                    INNER JOIN area a 				ON ea.idArea = a.idArea 
                    INNER JOIN estacion e 			ON ea.idEstacion = e.idEstacion
                    INNER JOIN planobra plan		ON dp.itemplan = plan.itemplan
                    INNER JOIN pqt_central	c		ON plan.idCentralPqt = c.idCentral
                    INNER JOIN empresacolab	ec 		ON c.idEmpresaColab = ec.idEmpresaColab
                    LEFT JOIN web_unificada_det wud ON wud.ptr = dp.poCod 			AND wud.itemplan = dp.itemplan 
                    LEFT JOIN web_unificada wu 		ON wu.ptr = dp.poCod 
                    LEFT JOIN ptr_expediente pe 	ON dp.itemplan = pe.itemplan 	AND dp.poCod = pe.ptr
                    LEFT JOIN planobra_po	po JOIN po_estado poe ON po.estado_po = poe.idPoEstado
					ON dp.itemplan = po.itemplan AND dp.poCod = po.codigo_po
                    where dp.itemplan = ?
                    AND dp.poCod != 'NOREQUIERE'
                    AND plan.paquetizado_fg = 2
                    and e.idEstacion = ?" ;
	     
	    $result = $this->db->query($Query,array($itemplan, $idEstacion, $itemplan, $idEstacion));
	    //log_message('error', $this->db->last_query());
	    return $result;
	}
	
	function   getCertificadoByItemPlan($itemplan, $idEstacion){
	    $Query = "SELECT * FROM itemplan_expediente WHERE itemplan = ? and idEstacion = ? ORDER BY estado;";	     
	    $result = $this->db->query($Query,array($itemplan, $idEstacion));
	    return $result;
	}
	
	function haveActivo($itemplan, $idEstacion) {
	    $sql = "SELECT COUNT(1) as count FROM itemplan_expediente where estado = 'ACTIVO' and itemplan = ? and idEstacion = ? ;";
	    $result = $this->db->query($sql,array($itemplan, $idEstacion));
	    return ($result->row()->count);
	}
	
	function haveFichaValidadaPorTDP($itemplan, $idEstacion) {
	    $sql = "SELECT COUNT(1) as count FROM ficha_tecnica where itemplan = ? and id_estacion = ? and estado_validacion = ".FICHA_TECNICA_APROBADA." and flg_activo = 1;";
	    $result = $this->db->query($sql,array($itemplan, $idEstacion));
	    return ($result->row()->count);
	}	
	
	function cancelCertificado($id){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $data = array(
	            "estado"  => 'DEVUELTO',
	            "estado_final"  => 'DEVUELTO',
	            "usuario" => $this->session->userdata('userSession')	            
	        );
	        $this->db->where('id', $id);
	        $this->db->update('itemplan_expediente', $data);
	        if($this->db->affected_rows() == 0) {
	            throw new Exception('Hubo un error al actualizar en itemplan_expediente');
	        }else{
	            $this->db->trans_commit();
	            $data['error'] = EXIT_SUCCESS;
	            $data['msj']      = 'Se actualizo correctamente!';	            
	        }
	
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
	
	function saveCertificado($itemplan, $fecha, $comentario, $estacion){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $data = array(
	            "itemplan"  => $itemplan,
	            "fecha" => $fecha,
	            "comentario" => $comentario,
	            "usuario" => $this->session->userdata('userSession'),
	            "estado" => 'ACTIVO',
	            "estado_final" => 'PENDIENTE',
	            "idEstacion" => $estacion
	        ); 
	
	        $this->db->insert('itemplan_expediente', $data);
	        if($this->db->affected_rows() != 1) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar en itemplan_expediente');
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
	
	function preAprobarTerminados($itemplan, $idEstacion, $estacionDesc){
	    $dataeXIT['error'] = EXIT_ERROR;
	    $dataeXIT['msj']   = null;
	    try{
	        $this->db->trans_begin();	       
	        /**
	         * INSERTAMOS EN LA BANDEJA LAS PTRS QUE NO INGRESARON POR NO ESTAR VALIDADAS.
	         */
            $this->db->query("INSERT INTO web_unificada_det
                                SELECT
                                	wu.ptr, wu.est_innova, wu.jefatura, wu.eecc, sp.subProyectoDesc,NULL,NULL,
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
                                	1,
                                    NULL
                                	
                                	FROM			planobra po,
                                	subproyecto sp,
                                	detalleplan dp,
                                	subproyectoestacion se,
                                	estacionarea ea,
                                	estacion e,
                                	area a,
                                	web_unificada wu
                                	WHERE po.itemplan = dp.itemplan
                                	AND	  po.idSubProyecto = sp.idSubProyecto
                                	AND   dp.idSubProyectoEstacion = se.idSubProyectoEstacion
                                	AND	se.idEstacionArea = ea.idEstacionArea
                                	AND ea.idEstacion = e.idEstacion
                                	AND ea.idArea = a.idArea
                                	AND dp.poCod = wu.ptr
                                	AND (substring(wu.est_innova,1,3) = '003' OR substring(wu.est_innova,1,2) = '01' OR substring(wu.est_innova,1,3) = '001' OR substring(wu.est_innova,1,3) = '002' OR substring(wu.est_innova,1,3) = '004' OR substring(wu.est_innova,1,3) = '005')
                                	AND ea.idEstacion = ".$idEstacion."
                                	AND dp.itemplan = '".$itemplan."'
                                	AND dp.poCod not in (select ptr from web_unificada_det where itemplan = '".$itemplan."');");
            
	            if ($this->db->trans_status() === TRUE) {
	                
	                $data = array(
	                    "estado"   => '01 - APROBADA VALORIZADA'
	                );
	                
	                $this->db->where('itemplan', $itemplan);
	                $this->db->where("(estado_asig_grafo='0' OR estado_asig_grafo='1')");
	                $this->db->where("estacion_desc", $estacionDesc);
	                $this->db->update('web_unificada_det', $data);
	                if ($this->db->trans_status() === FALSE) {
	                    throw new Exception('Hubo un error al actualizar en web_unificada_det');
	                }
	                
	                $data = array(
	                    "estado_final"  => 'FINALIZADO',
	                    "usuario_valida" => $this->session->userdata('userSession'),
	                    "fecha_valida" => date("Y-m-d H:i:s")
	                );
	                $this->db->where('itemplan', $itemplan);
	                $this->db->where('estado', 'ACTIVO');
	                $this->db->where('idEstacion', $idEstacion);
	                $this->db->update('itemplan_expediente', $data);
	                if($this->db->affected_rows() == 0) {
	                    throw new Exception('Hubo un error al actualizar en itemplan_expediente');
	                }
	                
	                $this->db->query("SELECT getGrafoByItemplan('".$itemplan."');");
	                 
	                if ($this->db->trans_status() === TRUE) {
	                    $this->db->trans_commit();
	                    $dataeXIT['error']    = EXIT_SUCCESS;
	                    $dataeXIT['msj']      = 'Se actualizo correctamente!';
	                }else{
	                    $this->db->trans_rollback();
	                }
	            }else{
	                throw new Exception('Hubo un error al insertar en web_unificada_det');
	            }
	       
	
	    }catch(Exception $e){
	        $dataeXIT['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    
	    return $dataeXIT;
	}

      function getItemplanExpediente($idEECC){
	    $Query = "  SELECT po.itemplan
                	FROM planobra po, subproyecto sp, central c where 
                	po.idSubProyecto = sp.idSubProyecto 
                	AND	po.idCentral = c.idCentral    
                    AND	po.idEstadoPlan = 4
					AND po.paquetizado_fg IS NULL
                	AND sp.idTipoPlanta = 1
					AND po.idSubProyecto NOT IN (155, 279, 283, 553, 554)";
	        if($idEECC != null){ 
        $Query .= " AND (CASE WHEN sp.idTipoSubProyecto = 2 THEN c.idEmpresaColabCV = ".$idEECC." ELSE c.idEmpresaColab = ".$idEECC."  END)";
	        }
			/*
		$Query .= " UNION ALL
					SELECT po.itemplan
                	FROM planobra po, subproyecto sp, pqt_central c where 
                	po.idSubProyecto = sp.idSubProyecto 
                	AND	po.idCentralPqt = c.idCentral    
                    AND	po.idEstadoPlan = 4
					AND po.paquetizado_fg = 2
                	AND sp.idTipoPlanta = 1";
	        if($idEECC != null){ 
        $Query .= " AND (CASE WHEN sp.idTipoSubProyecto = 2 THEN c.idEmpresaColabCV = ".$idEECC." ELSE c.idEmpresaColab = ".$idEECC."  END)";
	        }
			*/
        $Query .= "	UNION ALL      
                	SELECT tb1.itemplan FROM (SELECT iea.itemplan FROM itemplanestacionavance iea LEFT JOIN itemplan_expediente ie 
                	ON iea.itemplan = ie.itemplan
                	AND iea.idEstacion = ie.idEstacion
                	WHERE iea.porcentaje = '100'
                	AND (ie.estado_final IS NULL OR ie.estado_final != 'FINALIZADO')
                	group by iea.itemplan) tb1, planobra po, subproyecto sp, central c
                	where tb1.itemplan = po.itemplan
                	and po.idSubProyecto = sp.idSubProyecto
                    AND	po.idCentral = c.idCentral   
                	and sp.idTipoPlanta = 1
					AND po.paquetizado_fg IS NULL
                	AND sp.idProyecto != 4
					AND po.idSubProyecto NOT IN (155, 279, 283, 553, 554)
                    AND sp.idSubProyecto NOT IN (146,182,7,8)";//modificado menos obras publicas
        if($idEECC != null){
			$Query .= " AND (CASE WHEN sp.idTipoSubProyecto = 2 THEN c.idEmpresaColabCV = ".$idEECC."  ELSE c.idEmpresaColab = ".$idEECC."  END)";
        }
        $Query .= " AND (po.idEstadoPlan = ".ID_ESTADO_PRE_DISENIO." OR po.idEstadoPlan = ".ID_ESTADO_DISENIO." OR po.idEstadoPlan = ".ID_ESTADO_PLAN_EN_OBRA." OR po.idEstadoPlan = ".ID_ESTADO_PRE_LIQUIDADO." OR po.idEstadoPlan = ".ID_ESTADO_DISENIO_EJECUTADO." OR po.idEstadoPlan = ".ID_ESTADO_TRUNCO.")"; 
        /*
		$Query .= "UNION ALL
		
		SELECT tb1.itemplan FROM (SELECT iea.itemplan FROM itemplanestacionavance iea LEFT JOIN itemplan_expediente ie 
                	ON iea.itemplan = ie.itemplan
                	AND iea.idEstacion = ie.idEstacion
                	WHERE iea.porcentaje = '100'
                	AND (ie.estado_final IS NULL OR ie.estado_final != 'FINALIZADO')
                	group by iea.itemplan) tb1, planobra po, subproyecto sp, pqt_central c
                	where tb1.itemplan = po.itemplan
                	and po.idSubProyecto = sp.idSubProyecto
                    AND	po.idCentralPqt = c.idCentral   
                	and sp.idTipoPlanta = 1
                    AND po.paquetizado_fg = 2
                	AND sp.idProyecto != 4
                    AND sp.idSubProyecto NOT IN (146,182,7,8)";//modificado menos obras publicas
        if($idEECC != null){
			$Query .= " AND (CASE WHEN sp.idTipoSubProyecto = 2 THEN c.idEmpresaColabCV = ".$idEECC."  ELSE c.idEmpresaColab = ".$idEECC."  END)";
        }
        $Query .= " AND (po.idEstadoPlan = ".ID_ESTADO_PRE_DISENIO." OR po.idEstadoPlan = ".ID_ESTADO_DISENIO." OR po.idEstadoPlan = ".ID_ESTADO_PLAN_EN_OBRA." OR po.idEstadoPlan = ".ID_ESTADO_PRE_LIQUIDADO." OR po.idEstadoPlan = ".ID_ESTADO_DISENIO_EJECUTADO." OR po.idEstadoPlan = ".ID_ESTADO_TRUNCO.")
        */
        $Query .= " UNION ALL
		
		SELECT DISTINCT(tb.itemplan) FROM (
					SELECT  distinct po.itemplan
										FROM 	planobra po, subproyecto sp, planobra_po ppo 
										WHERE	po.idSubProyecto = sp.idSubProyecto
										AND		po.idEstadoPlan IN (1,2,3,7,9,10)
										AND 	(sp.idProyecto  = 4 OR sp.idSubProyecto IN (146,182,7,8))
										AND     po.itemplan = ppo.itemplan
										AND 	ppo.flg_tipo_area = 2
										AND     ppo.estado_po = 4
					UNION ALL
					SELECT  distinct po.itemplan
                    FROM 	planobra po, subproyecto sp, detalleplan dp, web_unificada wu, 
							subproyectoestacion se, estacionarea ea, area a
                    WHERE	po.idSubProyecto = sp.idSubProyecto
                    AND		po.idEstadoPlan IN (1,2,3,7,9,10)
                    AND 	(sp.idProyecto  = 4 OR sp.idSubProyecto IN (146,182,7,8))
                    AND     po.itemplan = dp.itemplan
                    AND 	dp.poCod = wu.ptr
                    AND    	dp.idSubProyectoEstacion = se.idSubProyectoEstacion
                    AND 	se.idEstacionArea = ea.idEstacionArea
                    AND     ea.idArea = a.idArea
                    AND 	a.tipoArea = 'MO'
                    AND     substring(wu.est_innova,1,2) not in (08,04,07)) as tb";//ULTIMO PARA obras publicas";
	    $result = $this->db->query($Query,array());
		log_message('error', $this->db->last_query()); 
	    return $result;
	}

	function insertOrDeletePtrExpediente($accion, $ptr, $itemplan){
	    $rpta['error'] = EXIT_ERROR;
	    $rpta['msj']   = null;
	    try{	
	         
	        if($accion=='1'){//INSERT
	            $this->db->trans_begin();
	            $data = array(
	                "ptr"  => $ptr,
	                "itemplan" => $itemplan);
	            $this->db->insert('ptr_expediente', $data);
	            if($this->db->affected_rows() != 1) {
	                $this->db->trans_rollback();
	                throw new Exception('Error al insertar en ptrExpediente');
	            }else{
	                $this->db->trans_commit();
	                $rpta['error']    = EXIT_SUCCESS;
	                $rpta['msj']      = 'Se agrego correctamente!';
	            }
	
	        }else if($accion=='2'){//DELETE
	
	            $this->db->trans_begin();
	            $this->db->where('ptr', $ptr);
	            $this->db->where('itemplan', $itemplan);
	            $this->db->delete('ptr_expediente');
	            $this->db->trans_complete();
	            if ($this->db->trans_status() === FALSE) {
	                $this->db->trans_rollback();
	                throw new Exception("Error al Eliminar ptrExpediente");
	            }else {
	                $this->db->trans_commit();
	                $rpta['msj'] = 'Se elimino correctamente ';
	                $rpta['error']  = EXIT_SUCCESS;
	            }	
	
	        }
	
	    }catch(Exception $e){
	        $rpta['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $rpta;
	}
	
	function   getEstacionPorcentajeByItemPlan($itemplan){
	    $Query = " SELECT de.*,  CASE WHEN ie.porcentaje IS NULL then 0 ELSE ie.porcentaje END AS porcentaje, ie.idItemplanEstacion,
                  (SELECT COUNT(1) FROM itemplan_expediente where itemplan = de.itemplan and idEstacion = de.idEstacion and estado_final = 'FINALIZADO') as certificado FROM (
                        SELECT DISTINCT po.itemplan,e.idEstacion, e.estacionDesc
                        FROM planobra po, subproyectoestacion se, estacionarea ea, estacion e
                        WHERE po.idSubProyecto = se.idSubProyecto
                        AND se.idEstacionArea = ea.idEstacionArea
                        AND ea.idEstacion = e.idEstacion
                        AND po.itemplan = ?
                        AND e.idEstacion != 1) as de LEFT JOIN itemplanestacionavance ie
                        ON de.itemplan = ie.itemplan
                        AND de.idEstacion = ie.idEstacion
                        ORDER BY de.idEstacion; ";
	    
	    $result = $this->db->query($Query,array($itemplan));
	    return $result;
	}
	
	function   getEstacionPorcentajeByItemPlanWithDiseno($itemplan){
	    $Query = " SELECT de.*,  
	                      CASE WHEN ie.porcentaje IS NULL then 0 ELSE ie.porcentaje END AS porcentaje, ie.idItemplanEstacion,
                          (SELECT COUNT(1) FROM itemplan_expediente where itemplan = de.itemplan and idEstacion = de.idEstacion and estado_final = 'FINALIZADO') as certificado 
                     FROM (
                        SELECT DISTINCT po.itemplan,e.idEstacion, e.estacionDesc, se.idSubProyecto
                        FROM planobra po, subproyectoestacion se, estacionarea  ea, estacion e
                        WHERE po.idSubProyecto = se.idSubProyecto
                        AND se.idEstacionArea = ea.idEstacionArea
                        AND ea.idEstacion = e.idEstacion
                        AND po.itemplan = ? ) as de LEFT JOIN itemplanestacionavance ie
                        ON de.itemplan = ie.itemplan
                        AND de.idEstacion = ie.idEstacion
                        ORDER BY de.idEstacion; ";
	    
	    $result = $this->db->query($Query,array($itemplan));
	    return $result;
	}
	
	function haveActivoVR($itemplan, $idEstacion) {
	    $sql = "SELECT COUNT(1) as count FROM detalleplan dp, subproyectoestacion se, estacionarea ea, solicitud_vale_reserva svr
            	where dp.idSubProyectoEstacion = se.idSubProyectoEstacion
            	and se.idEstacionArea = ea.idEstacionArea
            	and dp.poCod = svr.ptr
            	and dp.itemplan = svr.itemplan
            	and flg_estado is null
            	and dp.itemplan = ?
            	and ea.idEstacion = ?";
	    $result = $this->db->query($sql,array($itemplan, $idEstacion));
	    return ($result->row()->count);
	}
	
	function getCountSwitch($idSubProyecto, $idEstacion) {
		$sql = "SELECT COUNT(1)count
				  FROM switch_expediente
				 WHERE idSubProyecto = ".$idSubProyecto."
				   AND idEstacion    = ".$idEstacion;
		$result = $this->db->query($Query,array($itemplan));
		return $result->row();		   
	}
	
	function   getMaterialesPoMatByITemplan($itemplan, $idEstacion, $to){
	    $Query = " SELECT ppod.*, m.descrip_material 
                    FROM planobra_po ppo, planobra_po_detalle ppod, material m 
                    where ppo.codigo_po = ppod.codigo_po
                    and ppod.codigo_material = m.id_material
                    and ppo.estado_po not in (7,8)
                    and ppo.itemplan = ?
	                AND ppo.idEstacion = ?";
	    if($to ==  1){//consulta
	        $Query .= " order by ppod.cantidad_ingreso";
	    }else if($to ==  2){//registro de solicitud vr
	        $Query .= " order by ppod.codigo_po";
	    }
	    $result = $this->db->query($Query,array($itemplan, $idEstacion));
	    return $result;
	}
	
	function   getPartidasPoMotByITemplan($itemplan, $idEstacion, $to){
	    $Query = "  SELECT ppod.*, p.codigo, p.descripcion, p.flg_tipo 
                    FROM planobra_po ppo, planobra_po_detalle_mo ppod, partidas p
                    where ppo.codigo_po = ppod.codigo_po
                    and ppod.idActividad = p.idActividad
                    and ppo.estado_po not in (7,8)
                    and ppo.itemplan = ?
	                AND ppo.idEstacion = ?";                  
	    if($to ==  1){//consulta
	        $Query .= " order by ppod.cantidad_inicial";
	    }else if($to ==  2){//registro de solicitud vr
	        $Query .= " order by ppod.codigo_po";
	    }
	     
	    $result = $this->db->query($Query,array($itemplan, $idEstacion));
	    //log_message('error', $this->db->last_query());
	    return $result;
	}
	
	public function countNoValidadas($itemplan, $idEstacion)
	{
	    $Query = "SELECT count(1) has_ptr, SUM(CASE WHEN estado_po NOT IN (1,2,3) THEN 1 ELSE NULL END) as no_aprob FROM
                	planobra_po
                	where itemplan = ? and idEstacion = ?";
	    $result = $this->db->query($Query,array($itemplan, $idEstacion));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
	function getCodigoSolicitudVrByNum($numero_suma) {
	    $sql = "(SELECT CASE WHEN codigo IS NULL OR codigo = '' THEN CONCAT(YEAR(NOW()),(SELECT ROUND(RAND()*100000)))
                             ELSE MAX(codigo)+".$numero_suma." END AS codigo_vr
                   FROM solicitud_vale_reserva)";
	    $result = $this->db->query($sql);
	    return $result->row()->codigo_vr;
	}
	
	public function getJefaturaSapByItemplan($itemplan)
	{
	    $Query = "SELECT   po.itemplan,  ec.idEmpresaColab, c.jefatura, c.idZonal, 	js.idJefatura AS jefatura_1, js2.idJefatura AS jefatura_2
                	FROM
                            	planobra po,
                            	empresacolab ec,
                            	central c
                	LEFT JOIN
                	           jefatura_sap js ON c.idZonal = js.idZonal
                	LEFT JOIN
                	           jefatura_sap js2 ON c.jefatura = UPPER(js2.descripcion)
                	WHERE
                            	po.idCentral = c.idCentral
                            	AND c.idEmpresaColab = ec.idEmpresaColab
                            	AND po.itemplan = ?
                	LIMIT 1;";
	    $result = $this->db->query($Query,array($itemplan));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
	function createVRDevoluciones($arrayFinalInsert) {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();	       
            $this->db->insert_batch('solicitud_vale_reserva', $arrayFinalInsert);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al insertar el planobra_po_detalle_mo.');
            }else{
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se actualizo correctamente!';
                $this->db->trans_commit();
            }	             
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
	
	function updatePartidasMO($arrayPartidas, $arrayCostoTotal, $arrayLogPO){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $this->db->insert_batch('log_planobra_po_edit', $arrayLogPO);
	        if ($this->db->trans_status() === FALSE) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar en log_planobra_po_edit');
	        }else{
                $this->db->update_batch('planobra_po_detalle_mo',$arrayPartidas, 'id_planobra_po_detalle_po');
	            if($this->db->trans_status() === FALSE) {
	                $this->db->trans_rollback();
	                throw new Exception('Error al modificar el updateEstadoPlanObra');
	            }else{
	                $this->db->update_batch('planobra_po',$arrayCostoTotal, 'codigo_po');
	                if($this->db->trans_status() === FALSE) {
	                    $this->db->trans_rollback();
	                    throw new Exception('Error al modificar el updateEstadoPlanObra');
	                }else{
    	                $data['error'] = EXIT_SUCCESS;
    	                $data['msj'] = 'Se actualizo correctamente!';
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
	
	function haveActivoFinalizado($itemplan, $idEstacion) {
	    $sql = "SELECT      COUNT(1) as count 
                   FROM     itemplan_expediente 
                   WHERE    estado          = 'ACTIVO' 
	               AND      estado_final    = 'FINALIZADO' 
                   AND      itemplan        = ? 
	               AND      idEstacion      = ?";
	    $result = $this->db->query($sql,array($itemplan, $idEstacion));
	    return ($result->row()->count);
	}
	
	public function getPtrsByItemplanEstacionEstado($itemplan, $idEstacion, $estado_po)
	{
	    $Query = "SELECT * FROM
                	planobra_po
                	where itemplan = ? and idEstacion = ? and estado_po = ?";
	    $result = $this->db->query($Query,array($itemplan, $idEstacion, $estado_po));
	    return $result;	    
	}
	
	function validarPoLiquidadas($arrayPoToValidate, $arrayLogPo){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $this->db->insert_batch('log_planobra_po', $arrayLogPo);
	        if ($this->db->trans_status() === FALSE) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar en log_planobra_po_edit');
	        }else{	            
                $this->db->update_batch('planobra_po',$arrayPoToValidate, 'codigo_po');
                if($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    throw new Exception('Error al modificar el updateEstadoPlanObra');
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
	
	public function countNoValidadasSoloMO($itemplan, $idEstacion)
	{
	    $Query = "SELECT count(1) has_ptr, SUM(CASE WHEN estado_po NOT IN (1,2,3) THEN 1 ELSE NULL END) as no_aprob FROM
                	planobra_po
                	where itemplan = ? and idEstacion = ?
                	and flg_tipo_area = 2
                	and idEstacion != 1";
	    $result = $this->db->query($Query,array($itemplan, $idEstacion));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
	public function canCerticablesPasado($ptr){#VALIDACION PEDIDO DE OWEN 13.01.2020 no crear po mat obras menores al 17 nov 2019
	    $sql = "SELECT count(1) as cant FROM obras_certificables_pasados WHERE ptr = ?";
	    $result = $this->db->query($sql, array($ptr));
	    return $result->row_array()['cant'];
	}
	
	/***nuevo modelo pqt 17.06.2020 czavala**/	
	function   getEstacionPorcentajeByItemPlanWithDisenoModelPqt($itemplan){
	    $Query = " SELECT de.*,
	                      CASE WHEN ie.porcentaje IS NULL then 0 ELSE ie.porcentaje END AS porcentaje, ie.idItemplanEstacion,
                          (SELECT COUNT(1) FROM itemplan_expediente where itemplan = de.itemplan and idEstacion = de.idEstacion and estado_final = 'FINALIZADO') as certificado
                     FROM (
                        SELECT DISTINCT po.itemplan,e.idEstacion, e.estacionDesc, se.idSubProyecto
                        FROM planobra po, subproyectoestacion se, estacionarea  ea, estacion e
                        WHERE po.idSubProyecto = se.idSubProyecto
                        AND se.idEstacionArea = ea.idEstacionArea
                        AND ea.idEstacion = e.idEstacion
                        AND po.itemplan = ? 
	                    AND e.idEstacion in (2,3,4,5,6,7)) as de LEFT JOIN itemplanestacionavance ie
                        ON de.itemplan = ie.itemplan
                        AND de.idEstacion = ie.idEstacion
                        ORDER BY de.idEstacion; ";
	     
	    $result = $this->db->query($Query,array($itemplan));
	    return $result;
	}
	
	public function getJefaturaSapByItemplanPqt($itemplan)
	{
	    $Query = "SELECT   po.itemplan,  ec.idEmpresaColab, c.jefatura, c.idZonal, 	js.idJefatura AS jefatura_1, js2.idJefatura AS jefatura_2
                	FROM
                            	planobra po,
                            	empresacolab ec,
                            	central c
                	LEFT JOIN
                	           jefatura_sap js ON c.idZonal = js.idZonal
                	LEFT JOIN
                	           jefatura_sap js2 ON c.jefatura = UPPER(js2.descripcion)
                	WHERE
                            	po.paquetizado_fg is null
                                AND	po.idCentral = c.idCentral
                            	AND c.idEmpresaColab = ec.idEmpresaColab
                            	AND po.itemplan = ?
                    UNION ALL
                    SELECT   po.itemplan,  ec.idEmpresaColab, c.jefatura, c.idZonal, 	js.idJefatura AS jefatura_1, js2.idJefatura AS jefatura_2
                	FROM
                            	planobra po,
                            	empresacolab ec,
                            	pqt_central c
                	LEFT JOIN
                	           jefatura_sap js ON c.idZonal = js.idZonal
                	LEFT JOIN
                	           jefatura_sap js2 ON c.jefatura = UPPER(js2.descripcion)
                	WHERE
                            	po.paquetizado_fg  IN (1,2)
                                AND	po.idCentralPqt = c.idCentral
                            	AND c.idEmpresaColab = ec.idEmpresaColab
                            	AND po.itemplan = ?
                	LIMIT 1";
	    $result = $this->db->query($Query,array($itemplan, $itemplan));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
	function haveVrPendiente($itemplan, $idEstacion) {
	    $sql = "select count(1) as cant 
               from planobra_po ppo, solicitud_vale_reserva vr
               where ppo.codigo_po = vr.ptr
               and vr.flg_estado is null
               and ppo.itemplan = ? 
               and ppo.idEstacion = ?";
	    $result = $this->db->query($sql,array($itemplan, $idEstacion));
	    return ($result->row()->cant);
	}
	
	
	public function countNoValidadasSoloMOPqt($itemplan, $idEstacion)
	{
	    $Query = "SELECT count(1) has_ptr, SUM(CASE WHEN estado_po NOT IN (1,2,3) THEN 1 ELSE NULL END) as no_aprob FROM
                	planobra_po
                	where itemplan = ? and idEstacion = ?
                	and flg_tipo_area = 2
                	and idEstacion != 1
	               #and isPoPqt != 1";
	    $result = $this->db->query($Query,array($itemplan, $idEstacion));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
	function getCountPendienteValidVr($itemplan) {
	    $sql = "SELECT COUNT(1) AS count
                  FROM solicitud_vale_reserva
                 WHERE itemplan = ?
                   AND flg_estado IS NULL";
	    $result = $this->db->query($sql, array($itemplan));
	    return $result->row_array()['count'];
	}
}