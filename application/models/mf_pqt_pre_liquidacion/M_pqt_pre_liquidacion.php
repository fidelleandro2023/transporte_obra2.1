<?php
class M_pqt_pre_liquidacion extends CI_Model{
	
	function __construct(){
		parent::__construct();
		
	}
	
	function getTrabajosEnSiom($itemPlan){
	    $Query = 'select
                    x.itemplan,
                    x.idEstacion,
                    (select estaciondesc from estacion where idestacion = x.idestacion) estacion,
                    x.codigoSiom,
                    x.ultimo_estado,
                    po.idSubProyecto,
                    sp.subProyectoDesc,
                    sp.idProyecto,
                    p.ProyectoDesc,
                    ep.idEstadoPlan,
                    ep.estadoPlanDesc
                    from siom_obra x 
                    inner join planobra po
                    on po.itemplan = x.itemplan
                    inner join subproyecto sp
                    on sp.idSubProyecto = po.idSubProyecto
                    inner join proyecto p
                    on sp.idProyecto = p.idProyecto
                    inner join estadoplan ep
                    on ep.idEstadoPlan = po.idEstadoPlan
                    where x.itemplan = ?
	                and ultimo_estado not in (\'RECHAZADA\',\'ANULADA\')
                    order by idestacion';
	    $result = $this->db->query($Query, array($itemPlan));
	    return $result;
	}
	
	function getItemsPlan(){
	    $Query = 'select distinct
                    x.itemplan,
                    sp.subProyectoDesc,
                    sp.idProyecto,
                    p.ProyectoDesc
                    from siom_obra x
                    inner join planobra po
                    on po.itemplan = x.itemplan
                    inner join subproyecto sp
                    on sp.idSubProyecto = po.idSubProyecto
                    inner join proyecto p
                    on sp.idProyecto = p.idProyecto
                    inner join estadoplan ep
                    on ep.idEstadoPlan = po.idEstadoPlan
                    where ultimo_estado not in (\'RECHAZADA\',\'ANULADA\')
                    order by idestacion';
	    $result = $this->db->query($Query, array());
	    return $result;
	}
	
	function getEstacionesEnSiomXItemPlan($itemPlan){
	    $Query = "SELECT
                    x.itemplan,
                    x.idEstacion,
	                (select estaciondesc from estacion where idestacion = x.idestacion) estacion,
                    po.idSubProyecto,
                    sp.idProyecto,
					(SELECT count(*) from siom_obra tmp 
	                       WHERE tmp.itemplan = x.itemplan 
	                       AND tmp.idEstacion = x.idEstacion 
	                       AND tmp.ultimo_estado in ('APROBADA','VALIDANDO')) validando,
					(SELECT count(*) from siom_obra tmp 
	                       WHERE tmp.itemplan = x.itemplan 
	                       AND tmp.idEstacion = x.idEstacion 
	                       AND tmp.ultimo_estado not in ('RECHAZADA','ANULADA')) total,
					(SELECT COUNT(1)
    				  FROM switch_formulario
    				 WHERE idSubProyecto = po.idSubProyecto
    				   AND idZonal       = po.idZonal
    				   AND flg_tipo      = 1)countSwitchForm,
    				(SELECT COUNT(1)
    				   FROM switch_formulario
    				  WHERE idSubProyecto = po.idSubProyecto
    					AND idZonal       = po.idZonal
    					AND flg_tipo      = 2)countSwitchObPublicas,
					(SELECT COUNT(1)count
					   FROM form_obra_publica
					  WHERE itemplan   = x.itemplan
					   AND idEstacion = x.idEstacion)countFormObrap,
					(SELECT COUNT(1) count
					   FROM sisego_planobra 
					  WHERE itemplan = x.itemplan
					   AND origen   = 2) countFicha,
					(SELECT COUNT(1) FROM formulario_um WHERE itemplan = x.itemplan ) as has_form_um,
					po.idEstadoPlan,
	                ep.estadoPlanDesc,
    				po.indicador,
    				(SELECT c.jefatura
    				   FROM pqt_central c
    				  WHERE c.idCentral = po.idCentralpqt)jefatura,
    				(SELECT empresaColabDesc
    				   FROM empresacolab ep
    				  WHERE ep.idEmpresaColab = po.idEmpresaColab)descEmpresaColab,
	               (SELECT COUNT(*) FROM siom_obra_evidencias 
						WHERE itemplan   = x.itemplan
					   AND idEstacion = x.idEstacion) subioEvidencias
                    from siom_obra x 
                    inner join planobra po
                    on po.itemplan = x.itemplan
                    inner join subproyecto sp
                    on sp.idSubProyecto = po.idSubProyecto
                    inner join proyecto p
                    on sp.idProyecto = p.idProyecto
                    inner join estadoplan ep
                    on ep.idEstadoPlan = po.idEstadoPlan
                    where 
					x.itemplan = ?
	                and 
					ultimo_estado not in ('RECHAZADA','ANULADA')
                    group by x.itemplan,
                    x.idEstacion,
                    po.idSubProyecto,
                    sp.idProyecto
					ORDER BY x.idEstacion";
	    $result = $this->db->query($Query, array($itemPlan));
	    return $result;
	}
	
	function getOSEnSiomXEstacionItemPlan($itemPlan, $idEstacion){
	    $Query = "SELECT * from siom_obra tmp 
	                       WHERE tmp.itemplan = ? 
	                       AND tmp.idEstacion = ? 
	                       AND tmp.ultimo_estado not in ('RECHAZADA','ANULADA')";
	    $result = $this->db->query($Query, array($itemPlan,$idEstacion));
	    return $result;
	}
	
	function registrarEvidencias($tramaEvidencia){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $this->db->insert('siom_obra_evidencias', $tramaEvidencia);
	        if($this->db->affected_rows() != 1) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar en siom_obra_evidencias');
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
	
	function getEvidenciasXEstacionItemPlan($itemPlan, $idEstacion){
	    $Query = "SELECT * from siom_obra_evidencias tmp
	                       WHERE tmp.itemplan = ?
	                       AND tmp.idEstacion = ?";
	    $result = $this->db->query($Query, array($itemPlan,$idEstacion));
	    return $result;
	}
	
	function getDatosItemPlan($itemplan) {
	    $sql = "SELECT po.idEstadoPlan, po.idSubProyecto, sp.subProyectoDesc, sp.idProyecto, p.proyectoDesc,
                (select count(1) from siom_obra where itemplan = po.itemplan and idestacion = 2) has_estacion_fo,
                (select count(1) from siom_obra where itemplan = po.itemplan and idestacion = 5) has_estacion_coax
                    FROM planobra po
                    inner join subproyecto sp
                    on sp.idSubProyecto = po.idSubProyecto
                    inner join proyecto p
                    on sp.idProyecto = p.idProyecto
                    inner join estadoplan ep
                    on ep.idEstadoPlan = po.idEstadoPlan
                    where itemplan=  ?";
	    $result = $this->db->query($sql,array($itemplan));
	    return $result->row_array();
	}
	
	function updateEstadoPlanObraToPreLiquidado($itemplan, $dataUpdate){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $this->db->where('itemPlan', $itemplan);
	        $this->db->update('planobra', $dataUpdate);
	         
	        if($this->db->trans_status() === FALSE) {
	            $this->db->trans_rollback();
	            throw new Exception('Error updateEstadoPlanObraToPreLiquidado al modificar el planobra');
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
	
	function obtEstacionesParaLiquidar($itemPlan){
	    $Query = "SELECT po.itemplan, e.idEstacion, e.estacionDesc, sp.idSubProyecto, sp.idproyecto, po.idEstadoPlan, sp.idTipoSubProyecto,
                    (SELECT COUNT(1) FROM siom_obra x WHERE x.itemplan = po.itemplan AND x.idestacion = e.idestacion AND x.ultimo_estado in ('APROBADA','VALIDANDO')) validado,
                    (SELECT COUNT(1) FROM siom_obra x WHERE x.itemplan = po.itemplan AND x.idestacion = e.idestacion AND x.ultimo_estado not in ('RECHAZADA','ANULADA')) total,
                    COALESCE((SELECT x.porcentaje FROM itemplanestacionavance x WHERE x.itemplan = po.itemplan AND x.idestacion = e.idestacion AND x.porcentaje = 100 AND x.flg_evidencia = 1),0) pct_avance,
	                (SELECT COUNT(1) FROM siom_obra_evidencias x WHERE x.itemplan = po.itemplan AND x.idEstacion = e.idEstacion) subioEvidencias
                    FROM planobra po
                    INNER JOIN subproyecto sp
                    ON sp.idsubproyecto = po.idsubproyecto
                    INNER JOIN subproyectoestacion se
                    ON po.idsubproyecto = se.idsubproyecto
                    INNER JOIN estacionarea ea
                    ON ea.idestacionarea = se.idestacionarea
                    INNER JOIN estacion e
                    ON e.idestacion = ea.idestacion
                    WHERE po.itemplan = ?
                    GROUP BY po.itemplan, e.idestacion, e.estaciondesc, sp.idsubproyecto, sp.idproyecto, sp.idTipoSubProyecto
                    ORDER BY e.idestacion";
	    $result = $this->db->query($Query, array($itemPlan));
	    return $result;
	}
	
	function insertItemPlanEstacionAvance($dataInsert){
	    $data['error'] = EXIT_ERROR;
	    $data['msj'] = null;
	    try {
	        $this->db->trans_begin();
	        $this->db->insert('itemplanestacionavance', $dataInsert);
	        if ($this->db->affected_rows() != 1) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar el itemplanestacionavance');
	        } else {
	            $this->db->trans_commit();
	            $data['error'] = EXIT_SUCCESS;
	            $data['msj'] = 'Se insert&oacute; correctamente!';
	        }
	    
	    } catch (Exception $e) {
	        $data['msj'] = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
	
	function updateItemPlanEstacionAvance($itemplan, $idEstacion, $dataUpdate){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $this->db->where('itemPlan', $itemplan);
	        $this->db->where('idEstacion', $idEstacion);
	        $this->db->update('itemplanestacionavance', $dataUpdate);
	    
	        if($this->db->trans_status() === FALSE) {
	            $this->db->trans_rollback();
	            throw new Exception('Error updateItemPlanEstacionAvance al modificar el itemplanestacionavance');
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
	
	function countEstacionAvanceByItemplanEstacion($itemplan, $idEstacion){
	    $Query = "SELECT COUNT(1) count FROM itemplanestacionavance WHERE itemplan = ? AND idEstacion = ?";
	    $result = $this->db->query($Query,array($itemplan, $idEstacion));
	    return $result->row_array();
	}
	
	function getPoPreAprobacion($itemplan){
	    $Query = "SELECT f.faseDesc AS fase_desc,wud.*,SEC_TO_TIME(TIMESTAMPDIFF(SECOND, STR_TO_DATE(wud.fecSol, '%d/%m/%Y %H:%i:%s'), NOW())) as horas, po.idEstadoPlan,   CASE
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
				END as mesEjec,
                    substr(po.fechaPrevEjec,1,4) as ano_ejec, po.hasAdelanto,
                    (CASE WHEN sp.idProyecto = 4 AND (po.idEstadoPlan = 3 OR po.idEstadoPlan = 9) AND desc_area = 'MO' AND estacion_desc != 'DISENO'  THEN 1 ELSE 0 END) as esta_validada2,
                    ec.idEmpresaColab, 
                    '1' as origen,
                    NULL AS idArea,
                    po.indicador,
                    po.per
                    FROM web_unificada_det wud LEFT JOIN planobra po ON wud.itemplan = po.itemplan LEFT JOIN subproyecto sp ON po.idSubProyecto = sp.idSubProyecto
LEFT JOIN estacion e on e.estacionDesc =  estacion_desc
LEFT JOIN fase f ON f.idFase = po.idFase
LEFT JOIN central c ON c.idCentral = po.idCentral
LEFT JOIN empresacolab ec ON (CASE WHEN sp.idTipoSubProyecto = 2 THEN c.idEmpresaColabCV = ec.idEmpresaColab ELSE c.idEmpresaColab = ec.idEmpresaColab END)
                    WHERE ( wud.estado_asig_grafo = '0' OR wud.estado_asig_grafo = '1' ) AND wud.itemPlan is not null AND  substring(wud.estado,1,2) !=  '01'  AND CASE WHEN wud.desc_area = 'MO' THEN sp.idProyecto = 4 ELSE TRUE END
                        AND estacion_desc != 'DISENO'
                        AND sp.idTipoPlanta = 1  UNION ALL
						SELECT f.faseDesc AS fase_desc,
						ppo.codigo_po AS ptr,
						poe.estado,
						z.zonalDesc AS zonal,
						ec.empresaColabDesc AS eecc,
						sp.subProyectoDesc AS subProy,
						ppo.pep1 AS pep1,
						ppo.pep2 AS pep2,
						lppo.fecha_registro AS fecCrea,
						lppo.fecha_registro AS fecSol,
						'-' AS usua_aprob,
						'-' AS fec_aprob,
					    ppo.grafo AS grafo,
						'-' AS fecha_asig_grafo,
						'-' AS estado_asig_grafo,
						a.tipoArea AS desc_area,
						ppo.itemplan,
						'-' AS fec_prevista_ejec,
						e.estacionDesc AS estacion_desc,
						a.areaDesc AS area_desc,
						'-' AS fec_prev_ejec,
						'-' AS usua_asig_grafo,
						CASE WHEN ppo.flg_tipo_area = 1 THEN ppo.costo_total
						     ELSE '-' END AS valor_material,
						CASE WHEN ppo.flg_tipo_area = 2 THEN ppo.costo_total
						     ELSE '-' END AS valor_m_o,
						'-' AS indicador,
				    	ppo.grafo_from AS grafo_from,
						ppo.vale_reserva AS vale_reserva,
						'-' AS esta_validada,
						'-' AS has_pre_aprob,
						SEC_TO_TIME(TIMESTAMPDIFF(SECOND, lppo.fecha_registro, NOW())) as horas,
						po.idEstadoPlan,
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
						END as mesEjec,
						substr(po.fechaPrevEjec,1,4) as ano_ejec, po.hasAdelanto,
                        (CASE WHEN sp.idProyecto = 4 AND (po.idEstadoPlan = 3 OR po.idEstadoPlan = 9) AND a.tipoArea = 'MO' AND e.estacionDesc != 'DISEÃ‘O'  THEN 1 ELSE 0 END) as esta_validada2,
                        ec.idEmpresaColab, 
                        '2' as origen,
                        a.idArea,
                        po.indicador,
                        po.per
				FROM planobra_po ppo,
						planobra po,
						subproyecto sp,
						detalleplan dp,
						subproyectoestacion se,
						estacionarea ea,
						estacion e,
						area a,
						central c,
						zonal z,
						empresacolab ec,
						fase f,
						po_estado poe,
						log_planobra_po lppo
				WHERE ppo.itemplan = po.itemplan
					AND po.idSubProyecto = sp.idSubproyecto
					AND dp.idSubProyectoEstacion = se.idSubProyectoEstacion
					AND dp.itemplan = ppo.itemplan
					AND dp.poCod = ppo.codigo_po
					AND se.idEstacionArea = ea.idEstacionArea
					AND ea.idArea = a.idArea
					AND ppo.idEstacion = e.idEstacion
					AND po.idCentral = c.idCentral
					AND c.idZonal = z.idZonal
					AND	(CASE WHEN sp.idTipoSubProyecto = 2 THEN c.idEmpresaColabCV = ec.idEmpresaColab ELSE ppo.id_eecc_reg = ec.idEmpresaColab END)
					AND po.itemPlan = dp.itemPlan
					AND po.idFase = f.idFase
					AND ppo.estado_po = poe.idPoestado
					AND ppo.codigo_po = lppo.codigo_po
					AND ppo.itemplan = lppo.itemplan AND ppo.flg_tipo_area = 1
                            AND ppo.estado_po = 1 
                            AND lppo.idPoestado = 1
                            AND sp.idTipoPlanta = 1
					AND po.itemplan = ?";
	    $result = $this->db->query($Query, array($itemplan));
	    return $result;
	}
	
	function esAprobacionAutomatica($itemplan){
	    $Query = "SELECT COALESCE(sp.aprobacionAutomatica_fg, 0) aprobacionAutomatica_fg
                    FROM planobra po
                    INNER JOIN subproyecto sp
                    ON po.idSubProyecto = sp.idSubProyecto
                    WHERE itemplan = ?";
	    $result = $this->db->query($Query, array($itemplan));
	    return $result->row_array();
	}
	
	function hasFTValidada($itemplan, $idEstacion){
	    $Query = "SELECT
                	count(1) as has_ft
                	FROM   ficha_tecnica 
	                WHERE  estado_validacion   = 1	                
	                AND    itemplan            = ?
	                AND    id_estacion         = ?";
	    $result = $this->db->query($Query,array($itemplan, $idEstacion));
	    if($result->row() != null) {
	        return $result->row_array()['has_ft'];
	    } else {
	        return null;
	    }
	}
}