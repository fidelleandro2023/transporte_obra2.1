<?php
class M_termino_ficha_tecnica extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
		
	function  getBandejaFichaTecnicaEvaluacion($SubProy,$eecc,$zonal,$situacion,$mesEjec, $itemplan){
	    $Query = " SELECT	po.itemPlan, 			po.indicador ,			sp.subProyectoDesc, 
		ec.empresaColabDesc, 	ep.estadoPlanDesc,		z.zonalDesc, 
		ft.id_ficha_tecnica, 	ft.estado_validacion, DATE_FORMAT(po.fechaPrevEjec, '%d/%m/%Y') as fechaPrevEjec,
	CASE WHEN ft.flg_auditado	!= '' THEN 'VALIDADO'
		 WHEN (ft.flg_auditado	= '' OR ft.flg_auditado IS NULL) THEN 'PENDIENTE' END as estado_vali,
	ft.flg_auditado, ft.id_ficha_tecnica_base, e.estacionDesc, e.idEstacion
FROM 	subproyecto sp, 
		zonal z, 
		empresacolab ec, 
		estadoplan ep, 
		estacion e, 
		planobra po,
		ficha_tecnica ft, 
		itemplan_expediente ie  
WHERE po.idSubProyecto = sp.idSubProyecto
AND po.idZonal = z.idZonal
AND po.idEmpresaColab = ec.idEmpresaColab 
AND po.idEstadoPlan = ep.idEstadoPlan 
AND	po.itemplan = ie.itemplan
AND po.idEstadoPlan NOT IN (6,10)
AND DATE(po.fechaPreliquidacion) >= '2018-07-07'
AND po.itemplan = ft.itemplan
AND ft.id_estacion = e.idEstacion 
AND ft.id_ficha_tecnica is not null 
AND ft.flg_activo = 1 
AND ft.estado_validacion = 1
AND po.idFase != 4
AND e.idEstacion = ie.idEstacion
AND ie.estado = 'ACTIVO' 
AND ie.estado_final = 'FINALIZADO'";
              //  AND ft.flg_auditado is null";
        if($itemplan != ''){
            $Query .= " AND po.itemplan = '".$itemplan."' ";
        }
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
	            $Query .= " AND ft.flg_auditado = ''";
	        }else{
	            $Query .= " AND ft.flg_auditado != ''";
	        }
	        
	    }
	    if($mesEjec!=''){
	        $Query .= " HAVING fechaPrevEjec = '".$mesEjec."'";
	    }
	    
	    $result = $this->db->query($Query,array());
	    return $result;
	}
	
	
	
	function getInfoItemPlanFichaTecnica($itemplan){	    
	      $Query = " SELECT    po.fechaPreLiquidacion, po.itemplan, po.idSerieTroba, po.coordX, po.coordY, po.fechaEjecucion, sp.subProyectoDesc, MIN(STR_TO_DATE(wu.f_aprob, '%d/%m/%Y')) AS fec_inicio, po.indicador, c.codigo, ec.empresaColabDesc, st.serie
        				FROM   subproyecto sp, detalleplan dp, web_unificada wu, central c, empresacolab ec, planobra po
                        LEFT JOIN serie_troba st ON po.idSerieTroba = st.idSerieTroba
        				WHERE  po.itemplan = dp.itemplan
        				AND    po.idCentral = c.idCentral
        				AND    c.idEmpresaColab = c.idEmpresaColab
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
	    $Query = " SELECT * 
                    FROM    ficha_tecnica 
                    WHERE   itemplan = ?
                    AND     flg_activo = 1
	                AND    id_ficha_tecnica_base = ?";
	    $result = $this->db->query($Query,array($itemplan, $tipo_ficha));
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
	
	function getTrabajosFichaTecnica(){
	    $Query = " SELECT * FROM ficha_tecnica_trabajo;";
	    $result = $this->db->query($Query,array());
	    return $result;
	}
	
	function getTipoTrabajoFichaTecnica(){
	    $Query = " SELECT * FROM ficha_tecnica_tipo_trabajo;";
	    $result = $this->db->query($Query,array());
	    return $result;
	}	
	
	function getTrabajosFichatecnicaByItemplan($itemplan, $tipo_ficha){
	    $Query = " SELECT   ftxtt.id_ficha_tecnica_x_tipo_trabajo, ftxtt.id_ficha_tecnica_trabajo, ftt.descripcion, ftxtt.cantidad,fttt.descripcion as tipo_trabajo, ftxtt.observacion, ftxtt.flg_validado, ftxtt.comentario_vali, fttt.id_ficha_tecnica_tipo_trabajo, ftxtt.opc_aud, ftoa.descripcion as desc_opc_aud, ftxtt.comentario_aud 
                	FROM    ficha_tecnica ft,
                			ficha_tecnica_trabajo ftt,     
                			ficha_tecnica_x_tipo_trabajo ftxtt
                	LEFT JOIN
                		   ficha_tecnica_tipo_trabajo fttt
                	ON     ftxtt.id_ficha_tecnica_tipo_trabajo = fttt.id_ficha_tecnica_tipo_trabajo
                    LEFT JOIN
                		   ficha_tecnica_opc_auditor ftoa
                	ON     ftxtt.opc_aud = ftoa.id
                	WHERE  ft.id_ficha_tecnica = ftxtt.id_ficha_tecnica
                	AND    ftxtt.id_ficha_tecnica_trabajo = ftt.id_ficha_tecnica_trabajo
                	AND    ft.itemplan = ?
	                AND    ft.flg_activo = 1
	                AND    ft.id_ficha_tecnica_base = ?";
	    $result = $this->db->query($Query,array($itemplan, $tipo_ficha));
	    return $result;
	}
	
	function getNivelesCalibracionByItemplan($itemplan){
	    $Query = "SELECT  ftxnc.id_ficha_tecnica_x_nivel_calibra, ftnc.descripcion, ftxnc.opt_recep, ftxnc.opt_tx, ftxnc.ch_30,  ftxnc.ch_75,  ftxnc.ch_113,  ftxnc.snr_ruido, ftxnc.flg_validado, ftxnc.comentario_vali, ftxnc.id_ficha_tecnica_nivel_calibra, ftxnc.opc_aud,  ftoa.descripcion as desc_opc_aud, ftxnc.comentario_aud  
                    FROM    ficha_tecnica ft,
                    		ficha_tecnica_nivel_calibra ftnc,		
                    		ficha_tecnica_x_nivel_calibra ftxnc
                            LEFT JOIN ficha_tecnica_opc_auditor ftoa
                            ON ftxnc.opc_aud = ftoa.id
                    WHERE  	ft.id_ficha_tecnica = ftxnc.id_ficha_tecnica
                    AND    	ftxnc.id_ficha_tecnica_nivel_calibra = ftnc.id_ficha_tecnica_nivel_calibra
                	AND     ft.itemplan = ?
                    AND     ft.flg_activo = 1";
	    $result = $this->db->query($Query,array($itemplan));
	    return $result;
	}
	
	function saveFichaAuditoria($idFicha, $arrayTrabajos, $arrayNiveles){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $dataUpdate = array(
	            'flg_auditado' => 1,
	            'fecha_auditoria' =>date("Y-m-d H:i:s"),
	            'usuario_auditoria' =>  $this->session->userdata('idPersonaSession')
	        );
	        $this->db->where('id_ficha_tecnica', $idFicha);
	        $this->db->update('ficha_tecnica', $dataUpdate);
	        if ($this->db->trans_status() === FALSE) {
	            throw new Exception('Hubo un error al actualizar en ficha_tecnica');
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
	
	function getPTRSByItemplan($itemplan){
	    $Query = " SELECT  dp.poCod, a.areaDesc, wu.valoriz_material, wu.valoriz_m_o, wu.est_innova 
                    FROM   detalleplan dp, 
                           subproyectoestacion se, 
                           estacionarea ea, 
                           web_unificada wu,  
                           area a
                	WHERE  dp.itemplan = ?
                	AND    wu.ptr = dp.poCod
                	AND    dp.idSubProyectoEstacion = se.idSubProyectoEstacion
                	AND    se.idEstacionArea = ea.idEstacionArea
                	AND    ea.idArea = a.idArea;";
	    $result = $this->db->query($Query,array($itemplan));
	    return $result;
	}
	
	function saveAudiOBP($idFicha, $idfichaAudi, $observacionAudi){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $dataUpdate = array(
	            'flg_auditado'      => 1,
	            'fecha_auditoria'   =>date("Y-m-d H:i:s"),
				'usuario_auditoria' =>  $this->session->userdata('idPersonaSession'),
				'observacion_audi'  => $observacionAudi,
				'id_ficha_audi'     => $idfichaAudi
	        );
	        $this->db->where('id_ficha_tecnica', $idFicha);
			$this->db->update('ficha_tecnica', $dataUpdate);
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	    }
	    return $data;
	}
}