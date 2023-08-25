<?php
class M_detalle_obra_pin extends CI_Model
{
    //http://www.codeigniter.com/userguide3/database/results.html
    public function __construct()
    {
        parent::__construct();

    }

    public function getItemEstado($item)
    {
        $Query = "SELECT idEstadoPlan FROM planobra  WHERE itemPlan = ?";
        $result = $this->db->query($Query, array($item));
        if ($result->row() != null) {
            return $result->row_array()['idEstadoPlan'];
        } else {
            return null;
        }
    }
    
    public function getIPSubProy($item)
    {
        $Query = "SELECT idSubProyecto FROM planobra  WHERE itemPlan = ?";
        $result = $this->db->query($Query, array($item));
        if ($result->row() != null) {
            return $result->row_array()['idSubProyecto'];
        } else {
            return null;
        }
    }

/*
    public function getAllEstaciones($item)
    {
        $Query = "  SELECT planobra.itemPlan, estacion.estacionDesc, area.areaDesc, subproyectoestacion.idSubProyectoEstacion,estacion.idEstacion,
	    (SELECT COUNT(1) FROM itemplan_expediente WHERE itemplan = planobra.itemplan AND idEstacion = estacion.idEstacion and estado = 'ACTIVO') as has_vali
					FROM planobra, subproyectoestacion, estacionarea, estacion, area
					WHERE planobra.idSubProyecto = subproyectoestacion.idSubProyecto
					and subproyectoestacion.idEstacionArea = estacionarea.idEstacionArea
					AND estacionarea.idEstacion = estacion.idEstacion
					AND estacionarea.idArea = area.idArea
					AND planobra.itemPlan = ?
					GROUP BY estacion.estacionDesc
					ORDER BY estacion.estacionDesc, area.areaDesc";
        $result = $this->db->query($Query, array($item));
        //log_message('error', $this->db->last_query());
        return $result;
    }
    */
    
     public function getAllEstaciones($item)
    {
        $Query = " SELECT DISTINCT tb.* , 
                        (CASE WHEN ie.estado_final = 'PENDIENTE' THEN 1 ELSE NULL END) as has_pen,
                        (CASE WHEN ie.estado_final = 'FINALIZADO' THEN 1 ELSE NULL END) as has_fin,
                        (CASE WHEN ie.estado = 'ACTIVO' THEN 1 ELSE NULL END) as has_vali FROM (
                        SELECT planobra.itemPlan, estacion.estacionDesc, area.areaDesc, subproyectoestacion.idSubProyectoEstacion,estacion.idEstacion
            		FROM planobra, subproyectoestacion, estacionarea, estacion, area
            		WHERE planobra.idSubProyecto = subproyectoestacion.idSubProyecto
            		and subproyectoestacion.idEstacionArea = estacionarea.idEstacionArea
            		AND estacionarea.idEstacion = estacion.idEstacion
            		AND estacionarea.idArea = area.idArea
            		AND planobra.itemPlan = ?
					AND planobra.flg_transporte IS NULL
            		GROUP BY estacion.estacionDesc
            		ORDER BY estacion.estacionDesc, area.areaDesc) as tb LEFT JOIN 
                    itemplan_expediente ie ON ie.itemplan = tb.itemplan AND ie.idEstacion = tb.idEstacion and ie.estado = 'ACTIVO'";
        $result = $this->db->query($Query, array($item));
        //log_message('error', $this->db->last_query());
        return $result;
    }    

    public function getAllAreasByEstacion($item, $estacion)
    {
        $Query = "  SELECT planobra.itemPlan, subproyectoestacion.idSubProyectoEstacion, estacion.idEstacion, estacion.estacionDesc, area.idArea, area.areaDesc, area.tipoArea, planobra.idEstadoPlan
					FROM planobra, subproyectoestacion, estacionarea, estacion, area
					WHERE planobra.idSubProyecto = subproyectoestacion.idSubProyecto
					and subproyectoestacion.idEstacionArea = estacionarea.idEstacionArea
					AND estacionarea.idEstacion = estacion.idEstacion
					AND estacionarea.idArea = area.idArea
					AND planobra.itemPlan = ?
					AND planobra.flg_transporte IS NULL
					AND estacion.estacionDesc = ?
					GROUP BY estacion.estacionDesc, area.areaDesc
					ORDER BY estacion.estacionDesc, area.areaDesc";
        $result = $this->db->query($Query, array($item, $estacion));
        //log_message('error', $this->db->last_query());
        return $result;
    }

    public function getAllPTRbyArea($item, $estacion, $area)
    {

        $Query = "   SELECT po.itemPlan, spe.idSubProyectoEstacion, e.idEstacion,e.estacionDesc, a.areaDesc, 
                            dp.idDetallePlan, dp.poCod,po.idSubProyecto, a.idArea,
                            CASE WHEN wu.rangoPtr IS NULL THEN (CASE WHEN (  SELECT ppo.estado_po 
                                                                               FROM planobra_po ppo
																			  WHERE ppo.itemplan = po.itemPlan
                                                                                AND ppo.codigo_po = dp.poCod
                                                                                AND ppo.idEstacion = e.idEstacion) = 1 THEN 1
																	 WHEN (  SELECT ppo.estado_po 
                                                                               FROM planobra_po ppo
																			  WHERE ppo.itemplan = po.itemPlan
                                                                                AND ppo.codigo_po = dp.poCod
                                                                                AND ppo.idEstacion = e.idEstacion) = 2 THEN 1
																	WHEN (SELECT ppo.estado_po 
																			 FROM planobra_po ppo
																			WHERE ppo.itemplan = po.itemPlan
                                                                              AND ppo.codigo_po = dp.poCod
                                                                              AND ppo.idEstacion = e.idEstacion) = 3 THEN 2
																	 WHEN (  SELECT ppo.estado_po 
                                                                               FROM planobra_po ppo
																			  WHERE ppo.itemplan = po.itemPlan
                                                                                AND ppo.codigo_po = dp.poCod
                                                                                AND ppo.idEstacion = e.idEstacion) = 5 THEN 4
																	WHEN (SELECT ppo.estado_po 
																			 FROM planobra_po ppo
																			WHERE ppo.itemplan = po.itemPlan
                                                                              AND ppo.codigo_po = dp.poCod
                                                                              AND ppo.idEstacion = e.idEstacion) = 4 THEN 3
																    WHEN (SELECT ppo.estado_po 
																            FROM planobra_po ppo
																            WHERE ppo.itemplan = po.itemPlan
																              AND ppo.codigo_po = dp.poCod
																              AND ppo.idEstacion = e.idEstacion) = 6 THEN 5
																	WHEN (SELECT ppo.estado_po 
																            FROM planobra_po ppo
																            WHERE ppo.itemplan = po.itemPlan
																              AND ppo.codigo_po = dp.poCod
																              AND ppo.idEstacion = e.idEstacion) = 7 THEN 6
																	 WHEN (  SELECT ppo.estado_po 
                                                                               FROM planobra_po ppo
																			  WHERE ppo.itemplan = po.itemPlan
                                                                                AND ppo.codigo_po = dp.poCod
                                                                                AND ppo.idEstacion = e.idEstacion) = 8 THEN 6
																	 ELSE '' END) ELSE wu.rangoPtr END AS rangoPtr
							FROM planobra po,
							subproyectoestacion spe, estacionarea ea, estacion e, area a, 
                            detalleplan dp LEFT JOIN web_unificada wu ON dp.poCod = wu.ptr
							WHERE po.itemPlan = dp.itemPlan
							and dp.idSubProyectoEstacion = spe.idSubProyectoEstacion
							AND spe.idEstacionArea = ea.idEstacionArea
							AND ea.idEstacion = e.idEstacion
							AND ea.idArea = a.idArea
							AND po.flg_transporte IS NULL
							AND po.itemPlan = ?
							AND e.estacionDesc = ?
							AND a.areaDesc = ?
							-- AND po.idSubProyecto = spe.idSubProyecto
					   ORDER BY e.estacionDesc, a.areaDesc, dp.idDetallePlan";
        $result = $this->db->query($Query, array($item, $estacion, $area));

        //log_message('error', $this->db->last_query());
        return $result;
    }

    public function updatePTR($newPTR, $item, $oldPTR, $idsubproyestacionEdit)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        try {
            $data = array(

                'poCod' => $newPTR,
            );
            $this->db->where('itemPlan', $item);
            $this->db->where('poCod', $oldPTR);
            $this->db->where('idSubProyectoEstacion', $idsubproyestacionEdit);

            $this->db->update('detalleplan', $data);

            if ($this->db->trans_status() === true) {
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Actualizacion realizada correctamente.';
            } else {
                throw new Exception('ERROR TRANSACCION updatePTR');
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        return $data;
    }
    public function deletePTR($item, $oldPTR, $idsubproyestacionEdit)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        //DDELETE FROM detalleplan WHERE itemPlan = '18-0110900005' AND poCod = 'rrrr' AND idSubProyectoEstacion = 16
        try {
            $this->db->trans_begin();
            $this->db->where('itemPlan', $item);
            $this->db->where('poCod', $oldPTR);
            $this->db->where('idSubProyectoEstacion', $idsubproyestacionEdit);

            $this->db->delete('detalleplan');

            if ($this->db->trans_status() === true) {
                $this->db->where('ptr', $oldPTR);
                $this->db->where('itemPlan', $item);
                $this->db->where("(estado_asig_grafo='0' OR estado_asig_grafo='1')");
                $this->db->delete('web_unificada_det');
                if ($this->db->trans_status() === true) {
                    $this->db->trans_commit();
                    $data['error'] = EXIT_SUCCESS;
                    $data['msj'] = 'Eliminacion realizada correctamente a este itemplan .';
                } else {
                    $this->db->trans_rollback();
                    throw new Exception('ERROR TRANSACCION deletePTR');
                }
            } else {
                $this->db->trans_rollback();
                throw new Exception('ERROR TRANSACCION deletePTR');
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        return $data;
    }

    public function insertPTR($item, $ptr, $idsubproyectoestacion)
    {

        $data = array(
            //'idDetallePlan' => NULL,
            'itemPlan' => $item,
            'poCod' => $ptr,
            'idSubProyectoEstacion' => $idsubproyectoestacion,
        );

        $this->db->insert('detalleplan', $data);
    }
    /*
    function insertPTR($item, $ptr, $idsubproyectoestacion){

    $data['error'] = EXIT_ERROR;
    $data['msj']   = null;
    try{
    $this->db->trans_begin();

    $data = array(
    'itemPlan' => $item,
    'poCod' => $ptr,
    'idSubProyectoEstacion' => $idsubproyectoestacion
    );

    $this->db->insert('detalleplan',$data);
    if($this->db->affected_rows() == 0) {
    throw new Exception('Hubo un error al actualizar en web_unificada_det');
    }

    $this->db->query("SELECT getGrafoOnePTR(".$ptr.");");
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

    }*/

    public function getAllWebUnificada($ptr)
    {

        $Query = "  SELECT ptr,est_innova,imputacion,tipo_planta,tipo_proyecto,categoria,titulo_trabajo,
        jefatura_ptr,jefatura,zonal,mdf,f_aprob,cco,af,grafo,eecc,pto_can,pto_cel,pto_emp,valoriz_m_o,
        valoriz_material,vr,vd,pep,solpe,h_gestion,f_hg,obs,departamento,provincia,distrito,f_ult_est,
        f_creac_prop,usu_registro,monto_licencia
        FROM web_unificada
        WHERE ptr = ?
        ORDER BY ptr";
        $result = $this->db->query($Query, array($ptr));
        return $result->result();
    }

    public function findPTR($ptr)
    {
        $Query = " SELECT detalleplan.poCod, detalleplan.itemPlan, area.areaDesc, area.tipoArea
					FROM detalleplan, subproyectoestacion, estacionarea, estacion, area
					WHERE detalleplan.idSubProyectoEstacion = subproyectoestacion.idSubProyectoEstacion
					AND subproyectoestacion.idEstacionArea = estacionarea.idEstacionArea
					AND estacionarea.idEstacion = estacion.idEstacion
					AND estacionarea.idArea = area.idArea AND poCod LIKE ?";
        $result = $this->db->query($Query, array($ptr));

        //log_message('error', $result,true);
        return $result;

    }
    public function deleteEnWebUnitDet($ptr)
    {

        //DELETE FROM web_unificada_det where ptr = 'PTR' AND ( itemPlan is null OR pep2 is null);
        $Query = "DELETE FROM web_unificada_det where ptr = ? AND ( itemPlan is null OR pep2 is null)";
        $result = $this->db->query($Query, array($ptr));

        return $result;
    }
    public function selectDet($ptr)
    {
        $Query = "INSERT INTO web_unificada_det
				 SELECT DISTINCT wu.ptr, wu.est_innova, wu.jefatura, wu.eecc, sp.subProyectoDesc,NULL,NULL,
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
                CASE WHEN wu.desc_area != '' AND  a.tipoArea = 'MAT' then '5000.00' else wu.valoriz_material END,
                CASE WHEN wu.desc_area != '' AND  a.tipoArea = 'MO' then '5000.00' else wu.valoriz_m_o END,
				po.indicador,
                NULL,
                NULL,
                (SELECT COUNT(1) FROM itemplan_expediente WHERE itemplan = po.itemplan and idEstacion = e.idEstacion AND estado_final = 'FINALIZADO'), NULL
                FROM web_unificada wu
                LEFT JOIN detalleplan dp
                ON wu.ptr = dp.poCod
                LEFT JOIN  subproyectoestacion se
                ON dp.idSubProyectoEstacion = se.idSubProyectoEstacion
                LEFT JOIN subproyecto sp
                ON sp.idSubProyecto = se.idSubProyecto
                LEFT JOIN estacionarea ea
				ON	se.idEstacionArea = ea.idEstacionArea
                LEFT JOIN estacion e
				ON 	ea.idEstacion = e.idEstacion
                LEFT JOIN area a
                ON ea.idArea = a.idArea
                LEFT JOIN planobra po
                ON dp.itemPlan = po.itemPlan
                WHERE STR_TO_DATE(wu.f_creac_prop, '%d/%m/%Y %H:%i') >= STR_TO_DATE('01/01/2018 00:00', '%d/%m/%Y %H:%i')
                AND (substring(wu.est_innova,1,3) = '003' OR substring(wu.est_innova,1,2) = '01' OR substring(wu.est_innova,1,3) = '001' OR substring(wu.est_innova,1,3) = '002' OR substring(wu.est_innova,1,3) = '004' OR substring(wu.est_innova,1,3) = '005')
                AND wu.exist_wud is null
                AND CASE WHEN
                (CASE WHEN wu.desc_area != '' THEN wu.desc_area ELSE a.tipoArea END) = 'MO'
                AND e.estacionDesc != 'DISEÑO'
				THEN (SELECT COUNT(1) FROM itemplan_expediente WHERE itemplan = po.itemplan and idEstacion = e.idEstacion AND estado_final = 'FINALIZADO') >= 1 or sp.idProyecto = 4 ELSE TRUE END
                and wu.ptr = ?
                AND wu.ptr NOT IN (SELECT ptr from web_unificada_det)
                HAVING dp.itemplan is not null";
        $result = $this->db->query($Query, array($ptr));

        return $result;
    }

    public function insertPTRenLog($itemInsert, $ptr, $idUser)
    {

        $Query = "INSERT INTO log_planobra (tabla, actividad, itemplan, ptr, fecha_registro, id_usuario) VALUES ('detalleplan', 'ingresar','" . $itemInsert . "', '" . $ptr . "', NOW(), " . $idUser . ")";
        $result = $this->db->query($Query);

        return $result;

    }

    public function getGrafoOnePTR($ptr)
    {
        $Query = "  SELECT getGrafoOnePTR('" . $ptr . "')";
        $result = $this->db->query($Query);
        return $result;

    }
    // Insertando en WUDET

    public function findEnWU($ptr)
    {

        $Query = "SELECT * FROM `web_unificada` WHERE ptr = ?";
        $result = $this->db->query($Query, array($ptr));

        return $result;
    }

    public function insertEnWUDet($ptr)
    {

        $Query = "INSERT INTO web_unificada_det SELECT detalleplan.poCod, '003 - PROPUESTA COMPLETADA' AS estado, zonal.zonalDesc, empresacolab.empresaColabDesc, subproyecto.subproyectoDesc, NULL as pep1, NULL as pep2, '' as fecCrea, '' as fecSol,NULL,NULL,NULL,NULL, '0' as estado_asig_grafo, area.tipoArea, detalleplan.itemPlan,
				CASE
                    WHEN substr(planobra.fechaPrevEjec,6,2) = '01'  THEN  'ENE'
                    WHEN substr(planobra.fechaPrevEjec,6,2) = '02'  THEN  'FEB'
                    WHEN substr(planobra.fechaPrevEjec,6,2) = '03'  THEN  'MAR'
                    WHEN substr(planobra.fechaPrevEjec,6,2) = '04'  THEN  'ABR'
                    WHEN substr(planobra.fechaPrevEjec,6,2) = '05'  THEN  'MAY'
                    WHEN substr(planobra.fechaPrevEjec,6,2) = '06'  THEN  'JUN'
                    WHEN substr(planobra.fechaPrevEjec,6,2) = '07'  THEN  'JUL'
                    WHEN substr(planobra.fechaPrevEjec,6,2) = '08'  THEN  'AGO'
                    WHEN substr(planobra.fechaPrevEjec,6,2) = '09'  THEN  'SEP'
                    WHEN substr(planobra.fechaPrevEjec,6,2) = '10'  THEN  'OCT'
                    WHEN substr(planobra.fechaPrevEjec,6,2) = '11'  THEN  'NOV'
                    WHEN substr(planobra.fechaPrevEjec,6,2) = '12'  THEN  'DIC'
                ELSE NULL
                END AS fechaPrevEjec,
estacion.estacionDesc, area.areaDesc, planobra.fechaPrevEjec AS fec_prev_ejec, '' AS usuario_asig_grafo, CASE WHEN area.tipoArea = 'MAT' THEN '5000.00' ELSE '' END, CASE WHEN area.tipoArea = 'MO' THEN '5000.00' ELSE '' END, '', '', '',
                (SELECT COUNT(1) FROM itemplan_expediente WHERE itemplan = planobra.itemplan and idEstacion = estacion.idEstacion AND estado_final = 'FINALIZADO'), NULL
				FROM planobra, detalleplan, subproyecto, central, zonal, subproyectoestacion, estacionarea,estacion, area, empresacolab
				WHERE planobra.itemPlan = detalleplan.itemPlan
				AND planobra.idSubProyecto = subproyecto.idSubProyecto
				and planobra.idCentral = central.idCentral
				AND central.idZonal = zonal.idZonal
				and detalleplan.idSubProyectoEstacion = subproyectoestacion.idSubProyectoEstacion
				AND subproyectoestacion.idEstacionArea = estacionarea.idEstacionArea
				AND estacionarea.idEstacion = estacion.idEstacion
				AND estacionarea.idArea = area.idArea
				AND planobra.idEmpresaColab = empresacolab.idEmpresaColab
				AND CASE WHEN area.tipoArea = 'MO' AND estacion.estacionDesc != 'DISENO' THEN planobra.idEstadoPlan = 4 OR subproyecto.idProyecto = 4 ELSE TRUE END
				AND detalleplan.poCod = ?";
        $result = $this->db->query($Query, array($ptr));

        return $result;

    }

    public function insertEnWU($ptr)
    {
        $Query = "INSERT INTO `web_unificada` (`ptr`, `est_innova`, `idEstadoPtr`, `imputacion`, `tipo_planta`, `tipo_proyecto`, `categoria`, `titulo_trabajo`, `jefatura_ptr`, `jefatura`, `zonal`, `mdf`, `f_aprob`, `cco`, `af`, `grafo`, `idEmpreColab`, `eecc`, `pto_can`, `pto_cel`, `pto_emp`, `valoriz_m_o`, `valoriz_material`, `vr`, `rangoPtr`, `vd`, `pep`, `solpe`, `h_gestion`, `f_hg`, `obs`, `departamento`, `provincia`, `distrito`, `f_ult_est`, `f_creac_prop`, `usu_registro`, `monto_licencia`, `desc_area`)
		    VALUES ('" . $ptr . "', '003 - PROPUESTA COMPLETADA', '1', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1', '', '', '', '', '', '', '', '', '', '', '', '', '', '')";

        $result = $this->db->query($Query);

        return $result;
    }

    /**************10-08-2018********************/
    public function updatePTRenLog($itemInsert, $accion, $ptr, $idUser)
    {

        $Query = "INSERT INTO log_planobra (tabla, actividad, itemplan, ptr, ptr_default,fecha_registro, id_usuario)
						 VALUES ('detalleplan',
						 		'update','" . $itemInsert . "', '" . $ptr . "','" . $accion . "' ,NOW(), " . $idUser . ")";
        $result = $this->db->query($Query);

        return $result;

    }
    /**********************************/

    public function getDetalleIP($itemplan)
    {
        $sql = " SELECT po.itemPlan,
						c.idCentral,
						po.idSubProyecto,
						s.subProyectoDesc,
						po.nombreProyecto,
						c.codigo,
						c.tipoCentralDesc,
						z.zonalDesc,
						ec.idEmpresaColab,
						ec.empresaColabDesc,
						c.idZonal,
						c.jefatura,
						po.idEstadoPlan,
						t.estadoPlanDesc,
						s.idProyecto
				   FROM planobra po,
				        subproyecto s,
						estadoplan t,
						central c,
						zonal z,
						empresacolab ec
				  WHERE s.idSubProyecto = po.idSubProyecto
					AND c.idCentral = po.idCentralPqt
					AND c.idZonal = z.idZonal
					AND po.flg_transporte IS NULL
					AND po.idEstadoPlan = t.idEstadoPlan
					AND	(CASE WHEN s.idTipoSubProyecto = 2 THEN c.idEmpresaColabCV = ec.idEmpresaColab ELSE c.idEmpresaColab = ec.idEmpresaColab END)
					AND po.itemPlan = '" . $itemplan . "'";
        $result = $this->db->query($sql);
        return $result->row_array();
    }

    public function getDetallePO($itemplan, $codigoPO, $idEstacion)
    {
    //     $sql = " SELECT tb.*, iea.porcentaje FROM (SELECT po.itemPlan,
				// 		c.idCentral,
				// 		po.idSubProyecto,
				// 		s.subProyectoDesc,
				// 		po.nombreProyecto,
				// 		c.codigo,
				// 		c.tipoCentralDesc,
				// 		z.zonalDesc,
			 //           ppo.id_eecc_reg,
    //                     ec.empresaColabDesc,
				// 		c.idZonal,
				// 		c.jefatura,
				// 		po.idEstadoPlan,
				// 		t.estadoPlanDesc,
				// 		s.idProyecto,
				// 		ppo.costo_total,
				// 		ppo.codigo_po,
				// 		poe.estado,
				// 		poe.idPoestado,
    //                     jse.codAlmacen,
    //                     dp.idSubProyectoEstacion,
    //                     ppo.vale_reserva,
    //                     ppo.pep1,
    //                     ppo.pep2,
    //                     ppo.grafo,
    //                     ppo.flg_tipo_area,
    //                     ppo.idEstacion,
    //                     e.estacionDesc
				//   FROM planobra_po ppo,
				//   		planobra po,
				//         subproyecto s,
				// 		estadoplan t,
				// 		central c,
				// 		zonal z,
				// 		empresacolab ec,
				// 		po_estado poe,
    //                     jefatura_sap jsap,
				// 		jefatura_sap_x_empresacolab jse,
				// 		detalleplan dp,
    //                     estacion e
				//   WHERE ppo.itemplan = po.itemPlan
				//     AND ppo.estado_po = poe.idPoestado
				//     AND s.idSubProyecto = po.idSubProyecto
				// 	AND c.idCentral = po.idCentral
				// 	AND c.idZonal = z.idZonal
    //                 AND (CASE WHEN  (jsap.idZonal IS NULL OR jsap.idZonal = '') THEN c.jefatura = jsap.descripcion ELSE jsap.idZonal = c.idZonal END )
				// 	AND jsap.idJefatura = jse.idJefatura
    //                 AND jse.idEmpresaColab = ec.idEmpresaColab
				// 	AND po.idEstadoPlan = t.idEstadoPlan
				// 	AND	ppo.id_eecc_reg = ec.idEmpresaColab
				// 	AND ppo.itemplan = dp.itemplan
    //                 AND ppo.codigo_po = dp.poCod
				// 	AND ppo.itemPlan = '" . $itemplan . "'
    //                 AND ppo.codigo_po = '" . $codigoPO . "'
    //                 AND ppo.idEstacion = '" . $idEstacion . "'
    //                 AND ppo.idEstacion = e.idEstacion) as tb LEFT JOIN itemplanestacionavance iea
    //                 ON iea.itemplan = tb.itemplan AND iea.idEstacion = tb.idEstacion";
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
                                        dp.idSubProyectoEstacion,
                                        ppo.vale_reserva,
                                        ppo.pep1,
                                        ppo.pep2,
                                        ppo.grafo,
                                        ppo.flg_tipo_area,
                                        ppo.idEstacion,
                                        e.estacionDesc
                				   FROM planobra_po ppo,
                				   		planobra po,
                				        subproyecto s,
                						estadoplan t,
                						central c,
                						zonal z,
                						empresacolab ec,
                						po_estado poe,
                						detalleplan dp,
                                        estacion e
                				  WHERE ppo.itemplan = po.itemPlan
                				    AND ppo.estado_po = poe.idPoestado
                				    AND s.idSubProyecto = po.idSubProyecto
                					AND c.idCentral = po.idCentral
                					AND c.idZonal = z.idZonal
                					AND po.idEstadoPlan = t.idEstadoPlan
									AND po.flg_transporte IS NULL
                					AND	ppo.id_eecc_reg = ec.idEmpresaColab
                					AND ppo.itemplan = dp.itemplan
                                    AND ppo.codigo_po = dp.poCod
                					AND ppo.itemPlan = '" . $itemplan . "'
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

    public function getDetalleLogPO($itemplan, $codigoPO)
    {
        $sql = " SELECT  poe.estado,
						 lppo.fecha_registro,
						 u.nombre
				    FROM log_planobra_po lppo,
						 po_estado poe,
						 usuario u
				   WHERE lppo.idPoestado = poe.idPoestado
					AND lppo.idUsuario = u.id_usuario
					AND lppo.itemplan = '" . $itemplan . "'
					AND lppo.codigo_po = '" . $codigoPO . "'";
        $result = $this->db->query($sql);
        return $result->result_array();
    }

    public function getDetalleMaterial($codMaterial, $idSubProyecto, $idEstacion)
    {
        $sql = " SELECT m.id_material AS codigo_material,
						m.descrip_material,
						m.costo_material,
                        m.unidad_medida AS unidad_medida,
                        m.flg_tipo,
						(CASE WHEN m.flg_tipo = 1 THEN 'BUCLE'
							  WHEN m.flg_tipo = 0 THEN 'NO BUCLE'
						 ELSE 'SIN ESTADO' END) AS tipo_material,
						(ROUND(km.cantidad_kit,0)) AS cant_kit_material,
						km.factor_porcentual
				   FROM material m
		      LEFT JOIN kit_material km ON m.id_material = km.id_material
                    AND km.idSubProyecto = ?
                    AND km.idEstacion = ? 
                WHERE m.id_material = ? 
                  AND m.estado_material != 'phase out' ";
        $result = $this->db->query($sql, array($idSubProyecto, $idEstacion, $codMaterial));
        return $result->row_array();
    }

    public function generarCodigoPO($itemplan)
    {
        $sql = " SELECT CONCAT(2019,'-',
						(SELECT CONCAT(t.idProyecto, t.idJefatura)
						FROM (
									SELECT CASE WHEN LENGTH(s.idProyecto ) = 1 THEN  CONCAT('0',s.idProyecto)
												ELSE s.idProyecto END AS idProyecto,
										CASE WHEN LENGTH(j.idJefatura ) = 1 THEN  CONCAT('0',j.idJefatura)
												ELSE j.idJefatura END AS idJefatura
									FROM planobra po,
											subproyecto s,
											jefatura j,
											central c
									WHERE s.idSubProyecto = po.idSubProyecto
										AND c.idCentral     = po.idCentral
										AND j.descripcion   = c.jefatura
										AND itemplan        = '" . $itemplan . "'
										GROUP BY c.jefatura, po.itemplan
							)t),
						(SELECT CASE WHEN LENGTH(t.correlativo) = 1 THEN CONCAT('000',t.correlativo)
									 WHEN LENGTH(t.correlativo) = 2 THEN CONCAT('00',t.correlativo)
									 WHEN LENGTH(t.correlativo) = 3 THEN CONCAT('0',t.correlativo)
									 WHEN LENGTH(t.correlativo) = 4 THEN t.correlativo END
						FROM(
								SELECT COUNT(1)+1 correlativo
								FROM planobra_po
							)t
						)
				)po";
        $result = $this->db->query($sql);
        return $result->row()->po;
    }

    public function insertarPO($arrayInsert)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert('planobra_po', $arrayInsert);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar tabla planobra_po');
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

    public function getArraySubProyectosFTTHOP()
    {
        $sql = " SELECT GROUP_CONCAT(idSubProyecto) AS stringIDSubproyectos FROM subproyecto WHERE `subProyectoDesc` LIKE '%FTTH%'";
        $result = $this->db->query($sql);
        return $result->row()->stringIDSubproyectos;
    }

    public function countPOByItemplanAndEstacion($itemplan, $idEstacion, $from)
    {
        $sql = "SELECT COUNT(1)count
				  FROM planobra_po ppo,
					   planobra  po
				 WHERE ppo.itemplan = po.itemplan
				   AND po.idSubProyecto NOT IN(SELECT idSubProyecto
												 FROM subproyecto
												WHERE idProyecto IN (5, 4)) -- FTT Y OBRAS PUBLICAS
				   AND ppo.idEstacion = " . $idEstacion . "
				   AND ppo.from = '" . $from . "'
				   AND ppo.estado_po NOT IN ( ".PO_CANCELADO.",".PO_PRECANCELADO.")
				   AND po.itemplan    = '" . $itemplan . "'
				   AND po.flg_transporte IS NULL
				   AND ppo.flg_tipo_area = 1 ";
        $result = $this->db->query($sql);
        return $result->row_array()['count'];
    }

    public function insertarDetallePO($arrayInsert)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert_batch('planobra_po_detalle', $arrayInsert);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al insertar el planobra_po_detalle.');
            } else {
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se insert&oacute; correctamente!';
                $this->db->trans_commit();
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        return $data;
    }

    public function insertarLOGPO($arrayInsert)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert('log_planobra_po', $arrayInsert);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar tabla log_planobra_po');
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

    public function insertarDetallePlan($arrayInsert)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert('detalleplan', $arrayInsert);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar tabla detalleplan');
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

    public function insertarPO_LogPlanobra($arrayInsert)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert('log_planobra', $arrayInsert);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar tabla log_planobra');
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

    public function getMateriales_x_Material($idSubProyecto,$idEstacion)
    {
        $sql = " SELECT m.id_material AS codigo_material,
						m.descrip_material,
						m.costo_material,
                        m.unidad_medida AS unidad_medida,
                        m.flg_tipo,
						(CASE WHEN m.flg_tipo = 1 THEN 'BUCLE'
							WHEN m.flg_tipo = 0 THEN 'NO BUCLE'
						ELSE 'SIN ESTADO' END) AS tipo_material,
						(ROUND(km.cantidad_kit,0)) AS cant_kit_material
				   FROM material m,
						kit_material km
				  WHERE m.id_material = km.id_material
				    AND m.estado_material != 'Phase out'
				    AND m.flg_tipo != 1
					AND km.idSubProyecto = ?
                    AND km.idEstacion = ? ";
        $result = $this->db->query($sql, array($idSubProyecto,$idEstacion));
        return $result->result();
    }

    public function countMatxKit($codMaterial, $idSubProyecto, $idEstacion)
    {
        $sql = " SELECT COUNT(id_material) AS cantidad
		           FROM kit_material
                  WHERE idSubProyecto = " . $idSubProyecto . "
                    AND idEstacion = " . $idEstacion . "
				    AND id_material = '" . $codMaterial . "' ";
        $result = $this->db->query($sql);
        return $result->row()->cantidad;
    }
    
    public function updatePO($itemplan, $codigoPO, $idEstacion, $arrayUpdate)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        try {
            $this->db->trans_begin();
            $this->db->where('itemplan', $itemplan);
            $this->db->where('codigo_po', $codigoPO);
            $this->db->where('idEstacion', $idEstacion);
            $this->db->update('planobra_po', $arrayUpdate);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al actualizar la PO.');
            } else {
                $this->db->trans_commit();
				$data['error'] = EXIT_SUCCESS;
				$data['msj'] = 'Se actualiz&oacute; correctamente!!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        return $data;
	}
	
	public function insertarPOCancelar($arrayInsert)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert('po_cancelar', $arrayInsert);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar tabla po_cancelar');
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
	
	public function updatePOCancelar($itemplan, $codigoPO, $arrayUpdate)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        try {
            $this->db->trans_begin();
            $this->db->where('itemplan', $itemplan);
            $this->db->where('codigo_po', $codigoPO);
            $this->db->update('po_cancelar', $arrayUpdate);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al actualizar la tabla po_cancelar.');
            } else {
				$this->db->trans_commit();
				$data['error'] = EXIT_SUCCESS;
				$data['msj'] = 'Se actualiz&oacute; correctamente!!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
     public function getEstadoPO($itemplan, $codigoPO, $idEstacion)
    {
        $sql = " SELECT estado_po AS estado_po
		           FROM planobra_po
                  WHERE itemplan = '" . $itemplan . "'
                    AND codigo_po = '" . $codigoPO . "'
				    AND idEstacion = " . $idEstacion . " ";
        $result = $this->db->query($sql);
        return $result->row_array()['estado_po'];
    }

    public function deletePO($itemplan,$codigoPO,$idEstacion,$idSubProyectoEstacion)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->where('codigo_po', $codigoPO);
            $this->db->delete('planobra_po_detalle');

            if ($this->db->trans_status() === true) {

                $this->db->where('itemplan', $itemplan);
                $this->db->where('codigo_po', $codigoPO);
                $this->db->where('idEstacion', $idEstacion);
                $this->db->delete('planobra_po');

                if ($this->db->trans_status() === true) {
                    $this->db->where('itemPlan', $itemplan);
                    $this->db->where('poCod', $codigoPO);
                    $this->db->where('idSubProyectoEstacion', $idSubProyectoEstacion);
                    $this->db->delete('detalleplan');

                    if ($this->db->trans_status() === true) {

                        $this->db->trans_commit();
                        $data['error'] = EXIT_SUCCESS;
                        $data['msj'] = 'Se elimin&oacute; correctamente la PO!!.';

                    }else{
                        $this->db->trans_rollback();
                        throw new Exception('ERROR TRANSACCION DELETE PO EN DETALLEPLAN !!');
                    }
                }else{
                    $this->db->trans_rollback();
                    throw new Exception('ERROR TRANSACCION DELETE PO');
                }
                
            } else {
                $this->db->trans_rollback();
                throw new Exception('ERROR TRANSACCION DELETE DETALLE PO');
            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        return $data;
    }
    
     public function updateLOGPPO($itemplan, $codigoPO, $arrayUpdate)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        try {
            $this->db->trans_begin();
            $this->db->where('itemplan', $itemplan);
            $this->db->where('codigo_po', $codigoPO);
            $this->db->update('log_planobra_po', $arrayUpdate);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al actualizar el LOG PO.');
            } else {
                $this->db->trans_commit();
				$data['error'] = EXIT_SUCCESS;
				$data['msj'] = 'Se actualiz&oacute; correctamente!!';
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        return $data;
	}
	
	 public function getPPODetalle($codigoPO)
    {
        $sql = "     SELECT ppod.codigo_po,
                            ppod.codigo_material,
                            m.descrip_material,
                            m.unidad_medida,
                            ROUND(m.costo_material,2) as costo_material,
                            ppod.cantidad_ingreso,
                            ppod.cantidad_final
                    FROM planobra_po_detalle ppod,
                            material m
                    WHERE ppod.codigo_material = m.id_material
                      AND ppod.codigo_po = '" . $codigoPO . "'";
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    public function getDetallePresu_wu($itemplan, $ptr, $idSubProyecto, $pep1)
    {
        $sql = " SELECT cm.itemplan,cm.ptr,cm.pep1,cm.idSubProyecto,cm.areaDesc,cm.monto_mo,cm.orden_compra,cm.nro_certificacion
                  FROM certificacion_mo cm
                 WHERE cm.itemplan = '" . $itemplan . "'
                   AND cm.ptr = '" . $ptr . "'
                   AND cm.pep1 = '" . $pep1 . "'
                   AND cm.idSubProyecto = " . $idSubProyecto . " ";
        $result = $this->db->query($sql);
        return $result->row_array();
    }
    
    public function insertBatchLOGPPO($arrayInsert)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert_batch('log_planobra_po', $arrayInsert);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al insertar en la tabla log_planobra_po');
            } else {
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se insert&oacute; correctamente!';
                $this->db->trans_commit();
            }
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        return $data;
    }
    
    function getDetalleDisenioAutomatico($po) {
        $sql = "SELECT  DISTINCT ppd.codigo_po,
                        UPPER(pa.descripcion) AS descPartida,
                        ppd.idPartida,
                        ppd.idPrecioDiseno,
                        pa.codigo,
                        ppd.cantidad,
                        ppd.baremo,
                        pd.descPrecio,
                        ppd.costo,
                        ppd.total,
                        FORMAT(pop.costo_total,2) AS costo_total,
                        ppd.idEntidad,
                        (SELECT desc_entidad 
                           FROM entidad
                          WHERE idEntidad = ppd.idEntidad)descEntidad
                 FROM planobra_po_detalle_partida ppd,
                        partidas pa,
                        planobra_po pop,
                        precio_diseno pd
                WHERE pa.idActividad = ppd.idPartida
                    AND pop.codigo_po      = ppd.codigo_po
                    AND pd.idPrecioDiseno  = ppd.idPrecioDiseno
                    AND pa.flg_tipo           = 2
                    AND pop.codigo_po      = '".$po."'";
        $result = $this->db->query($sql);
        return $result->result_array();           
    }  
    
    function   getPartidasBasicByPtr($ptr){
        $Query = ' SELECT  pa.codigo,pa.descripcion, pd.descPrecio, pod.costo,
	                       pod.baremo, pod.cantidad_inicial, pod.monto_inicial,
	                       pod.cantidad_final, pod.monto_final
	               FROM planobra_po_detalle_mo pod, partidas pa, precio_diseno pd
                	where pod.idActividad = pa.idActividad
                	AND 	pa.idPrecioDiseno = pd.idPrecioDiseno
                	AND pod.codigo_po = ?';
        $result = $this->db->query($Query,array($ptr));
        return $result->result();
    }
    
    public function deletePOMONoDiseno($itemplan,$codigoPO,$idEstacion,$idSubProyectoEstacion)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->where('codigo_po', $codigoPO);
            $this->db->delete('planobra_po_detalle_mo');
    
            if ($this->db->trans_status() === true) {
    
                $this->db->where('itemplan', $itemplan);
                $this->db->where('codigo_po', $codigoPO);
                $this->db->where('idEstacion', $idEstacion);
                $this->db->delete('planobra_po');
    
                if ($this->db->trans_status() === true) {
                    $this->db->where('itemPlan', $itemplan);
                    $this->db->where('poCod', $codigoPO);
                    $this->db->where('idSubProyectoEstacion', $idSubProyectoEstacion);
                    $this->db->delete('detalleplan');
    
                    if ($this->db->trans_status() === true) {
    
                        $this->db->trans_commit();
                        $data['error'] = EXIT_SUCCESS;
                        $data['msj'] = 'Se elimin&oacute; correctamente la PO!!.';
    
                    }else{
                        $this->db->trans_rollback();
                        throw new Exception('ERROR TRANSACCION DELETE PO EN DETALLEPLAN !!');
                    }
                }else{
                    $this->db->trans_rollback();
                    throw new Exception('ERROR TRANSACCION DELETE PO');
                }
    
            } else {
                $this->db->trans_rollback();
                throw new Exception('ERROR TRANSACCION DELETE DETALLE PO');
            }
    
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
    
        return $data;
    }
    
     public function getDetalleCertMOByPO($codigoPO)
    {
 
        $sql = "	SELECT * FROM certificacion_mo WHERE ptr = '" . $codigoPO . "' ";
        $result = $this->db->query($sql);
        return $result->row_array();
    }
    
     public function countMatPhaseOut($codMaterial)
    {
        $sql = " SELECT COUNT(id_material) AS cantidad
		           FROM material
                  WHERE id_material = '" . $codMaterial . "' 
                    AND estado_material = 'phase out' ";
        $result = $this->db->query($sql);
        return $result->row()->cantidad;
    }
    
    function countPtrPlantaInterna($itemplan) {
        $sql = "SELECT COUNT(1) as count
                  FROM ptr_planta_interna 
                 WHERE itemplan = '".$itemplan."'
                   AND rangoptr != 6";
        $result = $this->db->query($sql);
        return $result->row()->count;         
    }    
}
