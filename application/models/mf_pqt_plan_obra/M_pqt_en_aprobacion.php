<?php
class M_pqt_en_aprobacion extends CI_Model
{
    public function __construct()
    {
        parent::__construct();

    }

    public function getPtrToLiquidacion($itemPlan){

        $Query = "SELECT f.faseDesc AS fase_desc,
            		ppo.codigo_po AS ptr,
            		poe.estado,
                    po.indicador,
            		z.zonalDesc AS zonal,
            		ec.empresaColabDesc AS eecc,
            		sp.subProyectoDesc AS subProy,
            		ppo.pep1 AS pep1,
            		ppo.pep2 AS pep2,
            		ppo.grafo AS grafo,
            		ppo.itemplan,
            		e.estacionDesc AS estacion_desc,
            		a.areaDesc AS area_desc,
            		ppo.costo_total AS valor_material,
            		po.per,
                    pd.requiere_licencia,
                    pd.liquido_licencia
            FROM 
            		planobra po,
            		subproyecto sp,
            		detalleplan dp,
            		subproyectoestacion se,
            		estacionarea ea,
            		estacion e,
            		area a,
            		pqt_central c,
            		zonal z,
            		empresacolab ec,
            		fase f,
            		po_estado poe,
                    planobra_po ppo
                    LEFT JOIN pre_diseno pd ON ppo.itemplan = pd.itemplan AND ppo.idEstacion = pd.idEstacion
            WHERE 	ppo.itemplan = po.itemplan
            	AND po.idSubProyecto = sp.idSubproyecto
            	AND dp.idSubProyectoEstacion = se.idSubProyectoEstacion
            	AND dp.itemplan = ppo.itemplan
            	AND dp.poCod = ppo.codigo_po
            	AND se.idEstacionArea = ea.idEstacionArea
            	AND ea.idArea = a.idArea
            	AND ppo.idEstacion = e.idEstacion
            	AND po.idCentralPqt = c.idCentral
            	AND c.idZonal = z.idZonal
            	AND	ppo.id_eecc_reg = ec.idEmpresaColab
            	AND po.itemPlan = dp.itemPlan
            	AND po.idFase = f.idFase
            	AND ppo.estado_po = poe.idPoestado
                AND po.itemPlan = ?  
            	AND ppo.estado_po IN (1,2)
				AND ppo.flg_tipo_area = 1
				AND CASE WHEN po.flg_update_adm IS NULL THEN (CASE WHEN po.paquetizado_fg = 2 THEN 
																		(CASE WHEN ppo.idEstacion IN (2,5) THEN 
																		(CASE WHEN pd.requiere_licencia = 2 THEN TRUE ELSE pd.liquido_licencia = 1 OR pd.liquido_licencia IS NULL END) 
																		ELSE (CASE WHEN ppo.idEstacion IN (6,15,16/*OC_FO,FO_ALIM,FO_DIST*/) THEN (CASE WHEN (select requiere_licencia from pre_diseno where itemplan = po.itemplan and idEstacion = 5/*FO*/) = 2 THEN TRUE ELSE (select liquido_licencia from pre_diseno where itemplan = po.itemplan and idEstacion = 5/*FO*/) = 1 END) 
																				   WHEN ppo.idEstacion IN (3/*OC_COAXIAL*/) THEN (CASE WHEN (select requiere_licencia from pre_diseno where itemplan = po.itemplan and idEstacion = 2/*COAXIAL*/) = 2 THEN TRUE ELSE (select liquido_licencia from pre_diseno where itemplan = po.itemplan and idEstacion = 2/*COAXIAL*/) = 1 END)
																			  ELSE po.idEstadoPlan in (20,3,9,21/*EN APROB, EN OBRA, PRE-LIQUIDADO, EN VALIDACION*/) END) 
																		END) 
																	ELSE TRUE END)
					     ELSE TRUE END";
    
        $result = $this->db->query($Query, array($itemPlan));
        #_log($this->db->last_query());
        return $result;
    }

    }
