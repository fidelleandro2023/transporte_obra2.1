<?php
class M_detalle_po_transporte extends CI_Model{

	function __construct(){
		parent::__construct();
		
	}
	
	
	
	function   getItemEstado($item){
        $Query = "SELECT idEstadoPlan FROM planobra_transporte  WHERE itemPlan = ?" ;
        $result = $this->db->query($Query,array($item));
        return $result;
    }


	function   getAllEstaciones($item){
	    $Query = "  SELECT planobra_transporte.itemPlan, estacion.estacionDesc,  estacion.idEstacion, area.areaDesc, subproyectoestacion.idSubProyectoEstacion 
					FROM planobra_transporte, subproyectoestacion, estacionarea, estacion, area 
					WHERE planobra_transporte.idSubProyecto = subproyectoestacion.idSubProyecto 
					and subproyectoestacion.idEstacionArea = estacionarea.idEstacionArea 
					AND estacionarea.idEstacion = estacion.idEstacion 
					AND estacionarea.idArea = area.idArea 
					AND planobra_transporte.itemPlan = ? 
					GROUP BY estacion.estacionDesc
					ORDER BY estacion.estacionDesc, area.areaDesc" ;
	    $result = $this->db->query($Query,array($item));
	    return $result;
	}

	function   getAllAreasByEstacion($item, $estacion){
	    $Query = " SELECT planobra_transporte.itemPlan, 
                          subproyectoestacion.idSubProyectoEstacion, 
                          estacion.idEstacion, 
                          estacion.estacionDesc, 
                          area.idArea, 
                          area.areaDesc, 
                          area.tipoArea, 
                          planobra_transporte.idEstadoPlan,
                          planobra_transporte.idSubProyecto
					 FROM planobra_transporte, 
                          subproyectoestacion, 
                          estacionarea, 
                          estacion, 
                          area 
					WHERE planobra_transporte.idSubProyecto = subproyectoestacion.idSubProyecto 
					  AND subproyectoestacion.idEstacionArea = estacionarea.idEstacionArea 
					  AND estacionarea.idEstacion = estacion.idEstacion 
					  AND estacionarea.idArea = area.idArea 
					  AND planobra_transporte.itemPlan = ? 
					  AND estacion.idEstacion = ?
					GROUP BY estacion.estacionDesc, area.areaDesc
					ORDER BY estacion.estacionDesc, area.areaDesc" ;
	    $result = $this->db->query($Query,array($item, $estacion));
	    return $result;
	}
	
	function   getAllAreasByEstacion_old($item, $estacion){
	    $Query = " SELECT planobra_transporte.itemPlan, 
                          subproyectoestacion.idSubProyectoEstacion, 
                          estacion.idEstacion, 
                          estacion.estacionDesc, 
                          area.idArea, 
                          area.areaDesc, 
                          area.tipoArea, 
                          planobra_transporte.idEstadoPlan,
                          planobra_transporte.idSubProyecto
					 FROM planobra_transporte, 
                          subproyectoestacion, 
                          estacionarea,
                          estacion, 
                          area
					WHERE planobra_transporte.idSubProyecto = subproyectoestacion.idSubProyecto
				 	  AND subproyectoestacion.idEstacionArea = estacionarea.idEstacionArea
					  AND estacionarea.idEstacion = estacion.idEstacion
					  AND estacionarea.idArea = area.idArea
					  AND planobra_transporte.itemPlan = ?
					  AND estacion.estacionDesc = ?
					GROUP BY estacion.estacionDesc, area.areaDesc
					ORDER BY estacion.estacionDesc, area.areaDesc" ;
	    $result = $this->db->query($Query,array($item, $estacion));
	    return $result;
	}

	function getAllPTRbyArea($item, $estacion, $area){
		
		$Query = "SELECT planobra_transporte.itemPlan, 
                         subproyectoestacion.idSubProyectoEstacion, 
                         estacion.estacionDesc, 
                         area.areaDesc, 
                         ppo.codigo_po AS ptr, 
                         ppo.estado_po AS rangoPtr, 
                         estacion.idEstacion
					FROM planobra_transporte, 
                         subproyectoestacion, 
                         estacionarea, 
                         estacion, 
                         area, 
                         planobra_po_transporte ppo 
				   WHERE planobra_transporte.itemPlan = ppo.itemplan
					 AND ppo.idSubProyectoEstacion = subproyectoestacion.idSubProyectoEstacion
					 AND subproyectoestacion.idEstacionArea = estacionarea.idEstacionArea
					 AND estacionarea.idEstacion = estacion.idEstacion
					 AND estacionarea.idArea = area.idArea
					 AND planobra_transporte.itemPlan = ?
					 AND estacion.estacionDesc = ?
					 AND area.areaDesc = ?
					ORDER BY estacion.estacionDesc, area.areaDesc, ppo.codigo_po" ;
        $result = $this->db->query($Query,array($item, $estacion, $area));
	    return $result;
	}
    
	function getAllPTRbyAreaMAT($item, $estacion, $area){
	
	    $Query = "  SELECT planobra_transporte.itemPlan, subproyectoestacion.idSubProyectoEstacion, estacion.estacionDesc, area.areaDesc, detalleplan.poCod as ptr, estacion.idEstacion,
                    	(CASE WHEN	planobra_po.estado_po in (1,2) THEN 1
                    	WHEN planobra_po.estado_po in (3) THEN 2
                    	WHEN planobra_po.estado_po in (4) THEN 4
                    	WHEN planobra_po.estado_po in (5,6) THEN 5
                    	WHEN planobra_po.estado_po in (7,8) THEN 6 end) as rangoPtr
                    	FROM planobra_transporte, subproyectoestacion, estacionarea, estacion, area, detalleplan, planobra_po
                    	WHERE planobra_transporte.itemPlan = planobra_po.itemplan
                    	and planobra_po.itemplan = detalleplan.itemplan
                    	and planobra_po.codigo_po = detalleplan.poCod
                    	and detalleplan.idSubProyectoEstacion = subproyectoestacion.idSubProyectoEstacion
                    	AND subproyectoestacion.idEstacionArea = estacionarea.idEstacionArea
                    	AND estacionarea.idEstacion = estacion.idEstacion
                    	AND estacionarea.idArea = area.idArea
                    	AND planobra_transporte.itemPlan = ?
                	    AND estacion.estacionDesc = ?
            	        AND area.areaDesc = ?
	                   ORDER BY estacion.estacionDesc, area.areaDesc, detalleplan.poCod" ;
	    $result = $this->db->query($Query,array($item, $estacion, $area));
	    return $result;
	}
	
	function updatePTR($newPTR, $item, $oldPTR, $idsubproyestacionEdit){
		$data['error'] = EXIT_ERROR;
	    $data['msj']   = null;

	    try{
	    	$data = array(

            'poCod' => $newPTR
	        );
	        $this->db->where('itemPlan', $item);
	        $this->db->where('poCod', $oldPTR);
	        $this->db->where('idSubProyectoEstacion', $idsubproyestacionEdit);


	        $this->db->update('detalleplan', $data);

	        if ($this->db->trans_status() === TRUE) {
                $data ['error']= EXIT_SUCCESS;
                $data['msj']   = 'Actualizacion realizada correctamente.';
            }else{
                throw new Exception('ERROR TRANSACCION updatePTR');
            }
	    }catch(Exception $e){
	    	$data['msj'] = $e->getMessage();
	    }


        return $data;
    }
    function deletePTR($item, $oldPTR, $idsubproyestacionEdit){
    	$data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    //DDELETE FROM detalleplan WHERE itemPlan = '18-0110900005' AND poCod = 'rrrr' AND idSubProyectoEstacion = 16
	    try{
	        $this->db->trans_begin();
	        $this->db->where('itemPlan', $item);
	        $this->db->where('poCod', $oldPTR);
	        $this->db->where('idSubProyectoEstacion', $idsubproyestacionEdit);

	        $this->db->delete('detalleplan');

	        if ($this->db->trans_status() === TRUE) {
	            $this->db->where('ptr', $oldPTR);
	            $this->db->where('itemPlan', $item);
	            $this->db->where("(estado_asig_grafo='0' OR estado_asig_grafo='1')");
	            $this->db->delete('web_unificada_det');
	            if ($this->db->trans_status() === TRUE) {
	                $this->db->trans_commit();
                    $data ['error']= EXIT_SUCCESS;
                    $data['msj']   = 'Eliminacion realizada correctamente a este itemplan .';
	            }else{
	                $this->db->trans_rollback();
	                throw new Exception('ERROR TRANSACCION deletePTR');
	            }
            }else{
                $this->db->trans_rollback();
                throw new Exception('ERROR TRANSACCION deletePTR');
            }
	    }catch(Exception $e){
	    	$data['msj'] = $e->getMessage();
	    }

        return $data;
    }

    function insertPTR($item, $ptr, $idsubproyectoestacion){

		$data = array(
		 //'idDetallePlan' => NULL,
		 'itemPlan' => $item,
		 'poCod' => $ptr,
		 'idSubProyectoEstacion' => $idsubproyectoestacion
		 );

		 $this->db->insert('detalleplan',$data);
	}
	
	    public function getDetallePOPqt($codigoPO, $idEstacion) {
        $sql = "	SELECT tb1.*,
                		   jse.codAlmacen,
                           iea.porcentaje
                      FROM (            
                                SELECT po.itemPlan,
                						c.idCentral,
                						po.idSubProyecto,
                						s.subProyectoDesc,
                						po.nombreProyecto,
                						c.codigo,
                						c.tipoCentralDesc,
                						z.zonalDesc,
                			            ppo.id_eecc_reg,
                                        ec.empresaColabDesc,
                						c.idZonal,
                						c.jefatura,
                						po.idEstadoPlan,
                						t.estadoPlanDesc,
                						s.idProyecto,
                						ppo.costo_total,
                						ppo.codigo_po,
                						poe.estado,
                						poe.idPoestado,
                                        ppo.idSubProyectoEstacion,
                                        ppo.vale_reserva,
                                        ppo.pep1,
                                        ppo.pep2,
                                        ppo.grafo,
                                        ppo.flg_tipo_area,
                                        ppo.idEstacion,
                                        e.estacionDesc, 
                                        s.idTipoPlanta
                				   FROM planobra_po_transporte ppo,
                				   		planobra_transporte po,
                				        subproyecto s,
                						estadoplan t,
                						pqt_central c,
                						zonal z,
                						empresacolab ec,
                						po_estado poe,
                                        estacion e
                				  WHERE ppo.itemplan = po.itemPlan
                				    AND ppo.estado_po = poe.idPoestado
                				    AND s.idSubProyecto = po.idSubProyecto
                					AND c.idCentral = po.idCentralPqt
                					AND c.idZonal = z.idZonal
                					AND po.idEstadoPlan = t.idEstadoPlan
                					AND	ppo.id_eecc_reg = ec.idEmpresaColab
                                    AND ppo.codigo_po = '" . $codigoPO . "'
                                    AND ppo.idEstacion = '" . $idEstacion . "'
                                    AND ppo.idEstacion = e.idEstacion ) AS tb1 
                LEFT JOIN jefatura_sap jsap ON (CASE WHEN  (jsap.idZonal IS NULL OR jsap.idZonal = '') THEN tb1.jefatura = jsap.descripcion ELSE jsap.idZonal = tb1.idZonal END )
                LEFT JOIN jefatura_sap_x_empresacolab jse ON jse.idJefatura = jsap.idJefatura AND jse.idEmpresaColab = tb1.id_eecc_reg
                LEFT JOIN itemplanestacionavance iea ON iea.itemplan = tb1.itemPlan AND iea.idEstacion = tb1.idEstacion
                ";
        $result = $this->db->query($sql);
        return $result->row_array();
    }

   function getInfoPOTransporteMo($ptr){
		$Query = "SELECT pp.codigo_po as ptr, 
		                 pp.vale_reserva, 
						 pp.itemplan, 
						 sp.subProyectoDesc, 
						 z.zonalDesc, 
						 ec.empresaColabDesc, 
						 a.areaDesc,  
						 poe.estado,
						 c.jefatura,
						 ROUND(SUM(ptraz.costo_mo),2) as costo_mo, 
						 ROUND(SUM(ptraz.costo_mat),2) as costo_mat, 
						 a.tipoArea, 
						 po.idEstadoPlan
                    FROM 
						planobra_po_transporte pp, 
						planobra_transporte po, 
						empresacolab ec,
						zonal z,
						subproyecto sp,
						subproyectoestacion se,
						estacionarea ea,
						area a,
						planobra_po_transporte_detalle ptraz,
						pqt_central c,
						po_estado poe
			      WHERE pp.itemplan = po.itemplan
				    AND pp.estado_po = poe.idPoestado
					AND po.idEmpresaColab = ec.idEmpresaColab
					AND po.idZonal = z.idZonal
					AND po.idCentralPqt = c.idCentral
					AND po.idSubProyecto = sp.idSubProyecto
					AND pp.idSubProyectoEstacion = se.idSubProyectoEstacion
					AND	se.idEstacionArea = ea.idEstacionArea 
					AND ea.idArea = a.idArea
					AND pp.codigo_po = ptraz.codigo_po
					AND pp.codigo_po = ?
                
                    GROUP BY pp.codigo_po " ;
	    $result = $this->db->query($Query,array($ptr));
           if($result->row() != null) {
               return $result->row_array();
           } else {
               return null;
           }
	}

	function findPTR($ptr){
		$Query = " SELECT detalleplan.poCod, detalleplan.itemPlan, area.areaDesc, area.tipoArea 
					FROM detalleplan, subproyectoestacion, estacionarea, estacion, area
					WHERE detalleplan.idSubProyectoEstacion = subproyectoestacion.idSubProyectoEstacion
					AND subproyectoestacion.idEstacionArea = estacionarea.idEstacionArea
					AND estacionarea.idEstacion = estacion.idEstacion
					AND estacionarea.idArea = area.idArea AND poCod LIKE ?" ;
	    $result = $this->db->query($Query,array($ptr));
	    return $result;



	}
	function deleteEnWebUnitDet($ptr){

		//DELETE FROM web_unificada_det where ptr = 'PTR' AND ( itemPlan is null OR pep2 is null);
		$Query = "DELETE FROM web_unificada_det where ptr = ? AND ( itemPlan is null OR pep2 is null)" ;
	    $result = $this->db->query($Query,array($ptr));

	    return $result;
	}
	function selectDet($ptr){
		$Query = "INSERT INTO web_unificada_det SELECT DISTINCT wu.ptr, wu.est_innova, wu.jefatura, wu.eecc, sp.subProyectoDesc,NULL,NULL,
                wu.f_creac_prop, 
                wu.f_ult_est, 
                NULL,NULL,NULL,NULL,0, 
                wu.desc_area,
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
                wu.valoriz_material,
                wu.valoriz_m_o,
                po.indicador,
                NULL,
                NULL
                FROM web_unificada wu 
                LEFT JOIN detalleplan dp
                ON wu.ptr = dp.poCod 
                LEFT JOIN  subproyectoestacion se
                ON dp.idSubProyectoEstacion = se.idSubProyectoEstacion
                LEFT JOIN subproyecto sp
                ON sp.idSubProyecto = se.idSubProyecto
                LEFT JOIN estacionarea ea
                ON  se.idEstacionArea = ea.idEstacionArea               
                LEFT JOIN estacion e                   
                ON  ea.idEstacion = e.idEstacion
                LEFT JOIN area a
                ON ea.idArea = a.idArea   
                LEFT JOIN planobra_transporte po
                ON dp.itemPlan = po.itemPlan
                WHERE   STR_TO_DATE(wu.f_creac_prop, \"%d/%m/%Y %H:%i\") >= STR_TO_DATE('01/01/2018 00:00', \"%d/%m/%Y %H:%i\")
                AND (substring(wu.est_innova,1,3) = '003' OR substring(wu.est_innova,1,2) = '01' OR substring(wu.est_innova,1,3) = '001' OR substring(wu.est_innova,1,3) = '002' OR substring(wu.est_innova,1,3) = '004' OR substring(wu.est_innova,1,3) = '005')
                AND CASE WHEN desc_area = 'MO' AND estacionDesc != 'DISENO' THEN po.idEstadoPlan = 4 or sp.idProyecto = 4 ELSE TRUE END 
                AND wu.ptr NOT IN (SELECT ptr from web_unificada_det)
                and wu.ptr = ?";
                $result = $this->db->query($Query,array($ptr));

	    return $result;
	}


	function insertPTRenLog($itemInsert, $ptr, $idUser){

		$Query = "INSERT INTO log_planobra (tabla, actividad, itemplan, ptr, fecha_registro, id_usuario) VALUES ('detalleplan', 'ingresar','".$itemInsert."', '".$ptr."', NOW(), ".$idUser.")" ;
	    $result = $this->db->query($Query);

	    return $result;


	}




	function getGrafoOnePTR($ptr){
		$Query = "  SELECT getGrafoOnePTR('".$ptr."')" ;
	    $result = $this->db->query($Query);
	    return $result;

	}
        // Insertando en WUDET

	function findEnWU($ptr){
		$Query = "SELECT * FROM `web_unificada` WHERE ptr = ?";
		$result = $this->db->query($Query,array($ptr));

		return $result;
	}

        function insertEnWUDet($ptr){
		$Query = "INSERT INTO web_unificada_det SELECT detalleplan.poCod, '003 - PROPUESTA COMPLETADA' AS estado, zonal.zonalDesc, empresacolab.empresaColabDesc, subproyecto.subproyectoDesc, NULL as pep1, NULL as pep2, '' as fecCrea, '' as fecSol,NULL,NULL,NULL,NULL, '0' as estado_asig_grafo, area.tipoArea, detalleplan.itemPlan, 
				CASE
                    WHEN substr(planobra_transporte.fechaPrevEjec,6,2) = '01'  THEN  'ENE'
                    WHEN substr(planobra_transporte.fechaPrevEjec,6,2) = '02'  THEN  'FEB'
                    WHEN substr(planobra_transporte.fechaPrevEjec,6,2) = '03'  THEN  'MAR'
                    WHEN substr(planobra_transporte.fechaPrevEjec,6,2) = '04'  THEN  'ABR' 
                    WHEN substr(planobra_transporte.fechaPrevEjec,6,2) = '05'  THEN  'MAY'
                    WHEN substr(planobra_transporte.fechaPrevEjec,6,2) = '06'  THEN  'JUN'
                    WHEN substr(planobra_transporte.fechaPrevEjec,6,2) = '07'  THEN  'JUL'
                    WHEN substr(planobra_transporte.fechaPrevEjec,6,2) = '08'  THEN  'AGO'
                    WHEN substr(planobra_transporte.fechaPrevEjec,6,2) = '09'  THEN  'SEP'
                    WHEN substr(planobra_transporte.fechaPrevEjec,6,2) = '10'  THEN  'OCT'
                    WHEN substr(planobra_transporte.fechaPrevEjec,6,2) = '11'  THEN  'NOV'
                    WHEN substr(planobra_transporte.fechaPrevEjec,6,2) = '12'  THEN  'DIC'
                ELSE NULL 
                END AS fechaPrevEjec,
estacion.estacionDesc, area.areaDesc, planobra_transporte.fechaPrevEjec AS fec_prev_ejec, '' AS usuario_asig_grafo, CASE WHEN area.tipoArea = 'MAT' THEN '5000.00' ELSE '' END, CASE WHEN area.tipoArea = 'MO' THEN '5000.00' ELSE '' END, '', '', ''
				FROM planobra_transporte, detalleplan, subproyecto, central, zonal, subproyectoestacion, estacionarea,estacion, area, empresacolab
				WHERE planobra_transporte.itemPlan = detalleplan.itemPlan
				AND planobra_transporte.idSubProyecto = subproyecto.idSubProyecto
				and planobra_transporte.idCentral = central.idCentral
				AND central.idZonal = zonal.idZonal
				and detalleplan.idSubProyectoEstacion = subproyectoestacion.idSubProyectoEstacion
				AND subproyectoestacion.idEstacionArea = estacionarea.idEstacionArea
				AND estacionarea.idEstacion = estacion.idEstacion
				AND estacionarea.idArea = area.idArea
				AND planobra_transporte.idEmpresaColab = empresacolab.idEmpresaColab				
				AND CASE WHEN area.tipoArea = 'MO' AND estacion.estacionDesc != 'DISENO' THEN planobra_transporte.idEstadoPlan = 4 OR subproyecto.idProyecto = 4 ELSE TRUE END 
				AND detalleplan.poCod = ?";
                $result = $this->db->query($Query,array($ptr));

	    return $result;

	}

	function getCountPoTransExist($itemplan, $idEstacion, $tipoArea) {
	    $sql = "SELECT COUNT(1) count
                FROM 	planobra_po_transporte ptr, subproyectoestacion se, estacionarea ea, area a
                WHERE 	ptr.idSubProyectoEstacion = se.idSubProyectoEstacion
                AND		se.idEstacionArea = ea.idEstacionArea
                AND 	ea.idArea = a.idArea
                AND		ptr.itemplan = ?
                AND 	ea.idEstacion = ?
                AND 	a.tipoarea = ?
	            AND     ptr.estado_po != 8";
	    $result = $this->db->query($sql, array($itemplan, $idEstacion, $tipoArea));
	    return $result->row()->count;
	}
	
	function getDetalleLogPOTransporte($codigoPO) {
        $sql = " SELECT  poe.estado,
						 lppo.fecha_registro,
						 u.nombre
				    FROM log_planobra_po_transporte lppo,
						 po_estado poe,
						 usuario u
				   WHERE lppo.idPoestado = poe.idPoestado
					AND lppo.idUsuario = u.id_usuario
					AND lppo.codigo_po = '" . $codigoPO . "'";
        $result = $this->db->query($sql);
        return $result->result_array();
    }

}