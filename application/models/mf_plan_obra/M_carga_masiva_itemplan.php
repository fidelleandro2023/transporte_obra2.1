<?php
class M_carga_masiva_itemplan extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
    function insertTmpPO($arrayData) {
        $idUsuario = $this->session->userdata('idPersonaSession');

        $this->db->trans_begin();
        $this->db->where('idUsuario', $idUsuario);
        $this->db->delete('tmp_planobra_po');
        if ($this->db->trans_status() === TRUE) {
            $this->db->insert_batch('tmp_planobra_po', $arrayData);
            $this->db->trans_commit();
            return array('error' => EXIT_SUCCESS);
        } else {
            $this->db->trans_rollback();
            return array('error' => EXIT_ERROR, 'msj' => 'error Comunicarse con el programador');
        }
        // if($this->db->affected_rows() != 1) {
        //     return array('error' => EXIT_ERROR, 'msj' => 'error Comunicarse con el programador');
        // } else {
        //     return array('error' => EXIT_SUCCESS);
        // }
    }
    
    function insertPO($arrayPlanPO, $arrayDetallePlan, $arrayDetallePO, $arrayTmpUpdateFlgIngreso, $arrayLogPO) {
        $data ['error']= EXIT_ERROR;
	    $data['msj'] = null;
	    try{
            $this->db->trans_begin();
            $this->db->insert_batch('planobra_po', $arrayPlanPO);
            if ($this->db->trans_status() === TRUE) {
                $this->db->insert_batch('detalleplan', $arrayDetallePlan);
                if ($this->db->trans_status() === TRUE) {
                    $this->db->insert_batch('planobra_po_detalle', $arrayDetallePO);
                    if ($this->db->trans_status() === TRUE) {
                        $this->db->update_batch('tmp_planobra_po', $arrayTmpUpdateFlgIngreso, 'idTmpPlanObraPo');
                        if($this->db->trans_status() === TRUE) {
                            $this->db->insert_batch('log_planobra_po', $arrayLogPO);
                            if($this->db->trans_status() === TRUE) {
                                $this->db->trans_commit();
                                $data['error']= EXIT_SUCCESS;
                            } else {
                                $this->db->trans_rollback();
                                throw new Exception('error al insertar log');
                            }
                        } else {
                            $this->db->trans_rollback();
                            throw new Exception('error al actualizar flg');
                        }
                    } else {
                        $this->db->trans_rollback();
                        throw new Exception('error al ingresar PO DETALLE');
                    }                
                } else{
                    $this->db->trans_rollback();
                    throw new Exception('error al ingresar DETALLE PLAN');
                }
            } else {
                $this->db->trans_rollback();
                throw new Exception('ERROR al ingresar PLAN OBRA PO');
            }
	      
	    }catch(Exception $e){
	        $data['msj'] = utf8_decode($e->getMessage());
	        $this->db->trans_rollback();
	    }
	    return $data;
    }

    function getDataTmpPO($idUsuario) {
		$sql = "SELECT tmp.itemplan, 
					   GROUP_CONCAT(DISTINCT tmp.codigo_material,'|',tmp.cantidad_ingreso,'|',tmp.idEstacion,'|',kit.cantidad_kit,'|',tmp.cantidad_ingreso*ma.costo_material)arraydata 
                  FROM (tmp_planobra_po tmp, planobra po)  LEFT JOIN 
                        kit_material kit ON (kit.id_material = tmp.codigo_material AND kit.idSubProyecto = po.idSubProyecto)INNER JOIN material ma ON 
                        tmp.codigo_material = ma.id_material  
                 WHERE po.itemplan = tmp.itemplan
                   AND tmp.flg_ingreso = 0
                   AND tmp.idUsuario = ".$idUsuario."
                   AND po.idEstadoPlan IN (".ESTADO_PLAN_PRE_DISENO.", ".ESTADO_PLAN_DISENO.", ".ESTADO_PLAN_DISENO_EJECUTADO.")
				GROUP BY tmp.itemplan";
        $result = $this->db->query($sql);
		return $result->result();		
    }
    
    function getTablaTmpPO($itemplan, $idUsuario, $idEstacion) {
        $sql = "SELECT tmp.itemplan, 
                       tmp.codigo_material,
                       tmp.cantidad_ingreso,
                       tmp.idEstacion,
                       ma.descrip_material,
                       (SELECT estacionDesc 
                          FROM estacion 
                         WHERE idEstacion = tmp.idEstacion) estacionDesc,
                       kit.cantidad_kit,
                       ppo.codigo_po,
                       ma.costo_material,
                       kit.id_material as kitIdMaterial,
                       COUNT(tmp.codigo_material) countMaterial,
                       (SELECT subproyectoDesc 
                          FROM subproyecto 
                         WHERE po.idSubProyecto = idSubProyecto) subproyectoDesc,
                         CASE  WHEN kit.cantidad_kit IS NOT NULl AND 
                                    kit.id_material  IS NOT NULL AND
                                    ppo.codigo_po    IS NULL         THEN  
                                                                        CASE WHEN FORMAT(kit.cantidad_kit*factor_porcentual/100, 2) > tmp.cantidad_ingreso THEN '#00b300'
                                                                             WHEN cantidad_kit < tmp.cantidad_ingreso THEN '#ff6600' 
                                                                             ELSE null END 
                                ELSE '#FDBDBD'  END color,   
                       FORMAT(tmp.cantidad_ingreso*ma.costo_material,2) as costo,
                       ma.flg_tipo
                  FROM (
                        (tmp_planobra_po tmp, planobra po)  LEFT JOIN 
                         kit_material kit ON (kit.id_material = tmp.codigo_material AND kit.idSubProyecto = po.idSubProyecto AND kit.idEstacion = tmp.idEstacion) INNER JOIN 
                         material ma ON tmp.codigo_material = ma.id_material) LEFT JOIN 
                         planobra_po ppo ON (ppo.itemplan = tmp.itemplan AND ppo.idEstacion = tmp.idEstacion AND ppo.estado_po <> 8)
                 WHERE po.itemplan = tmp.itemplan
                   AND po.itemplan     = COALESCE(?, po.itemplan)
                   AND tmp.idEstacion  = COALESCE(?, tmp.idEstacion)
                   AND tmp.flg_ingreso = 0
                   AND tmp.idUsuario   = ".$idUsuario."
                    GROUP BY tmp.codigo_material
                    ORDER BY ma.flg_tipo ASC";
        $result = $this->db->query($sql, array($itemplan, $idEstacion));
        return $result->result();	
    }

    function totalTmp($itemplan, $idEstacion) {
        $idUsuario = $this->session->userdata('idPersonaSession');

        $sql = "  SELECT CASE WHEN t.total IS NOT NULL THEN FORMAT(SUM(t.total),2)
                            ELSE 0 END totalFormat,
                         CASE WHEN t.total IS NOT NULL THEN ROUND(SUM(t.total),2)
                            ELSE 0 END costoTotal
                    FROM (       
                            SELECT tmp.cantidad_ingreso,ma.costo_material, 
                                   CASE WHEN ma.flg_tipo = ".FLG_MATERIAL_NO_BUCLE." THEN CASE WHEN (ppo.codigo_po IS NOT NULL OR 
                                                                                                        ppo.codigo_po <> '' OR 
                                                                                                        kit.id_material IS NULL OR
                                                                                                        kit.id_material = '') THEN NULL
                                                                                                ELSE tmp.cantidad_ingreso*ma.costo_material END 
                                        ELSE tmp.cantidad_ingreso*ma.costo_material END total                                
                              FROM (
                                    (tmp_planobra_po tmp, planobra po)  LEFT JOIN 
                                    kit_material kit ON (kit.id_material = tmp.codigo_material AND kit.idSubProyecto = po.idSubProyecto AND kit.idEstacion = tmp.idEstacion) INNER JOIN 
                                    material ma ON tmp.codigo_material = ma.id_material) LEFT JOIN 
                                    planobra_po ppo ON (ppo.itemplan = tmp.itemplan AND ppo.idEstacion = tmp.idEstacion AND ppo.estado_po <> 8) 
                             WHERE po.itemplan = tmp.itemplan
                               AND po.itemplan = '".$itemplan."'
                               AND tmp.idEstacion = '".$idEstacion."'
                               AND tmp.flg_ingreso = 0
                               -- AND kit.id_material IS NOT NULL
                               AND tmp.idUsuario = ".$idUsuario."
                            GROUP BY tmp.itemplan, costo_material
                         )t";
        $result = $this->db->query($sql);
        return $result->row_array();	
    }
    
    function getDataInsertPO($itemplan, $idUsuario, $idEstacion) {
        $sql = "SELECT  tmp.idTmpPlanObraPo,
                        tmp.itemplan, 
                        tmp.codigo_material,
                        tmp.cantidad_ingreso,
                        tmp.idEstacion,
                        kit.cantidad_kit,
                        ma.costo_material,
                        ppo.codigo_po,
                        CASE WHEN tmp.idEstacion = 4 AND c.jefatura = 'LIMA'          THEN c.idEmpresaColabFuente
                             ELSE po.idEmpresaColabDiseno END idEmpresaColab,
                        (SELECT estacionDesc 
                          FROM estacion 
                         WHERE idEstacion = tmp.idEstacion) estacionDesc,
                        GROUP_CONCAT(DISTINCT tmp.codigo_material,'|',
                                              tmp.cantidad_ingreso,'|',
                                              CASE WHEN (kit.id_material IS NOT NULL OR ma.flg_tipo = ".FLG_MATERIAL_BUCLE.") THEN 1 ELSE 0 END,'|',
                                              tmp.idTmpPlanObraPo,'|',tmp.costo_mat ORDER BY ma.flg_tipo ASC)arrayMaterial,
                        SUM(CASE WHEN kit.id_material IS NOT NULL THEN 1
								 ELSE 0 END) as sumNotNull,
                        (SELECT COUNT(1) 
                           FROM material m 
                          WHERE m.id_material = tmp.codigo_material)countMaterial
                 FROM (
                        (tmp_planobra_po tmp, planobra po, central c)  LEFT JOIN 
                         kit_material kit ON (kit.id_material = tmp.codigo_material AND kit.idSubProyecto = po.idSubProyecto) INNER JOIN 
                         material ma ON tmp.codigo_material = ma.id_material) LEFT JOIN 
                         planobra_po ppo ON (ppo.itemplan = tmp.itemplan AND ppo.idEstacion = tmp.idEstacion AND ppo.estado_po <> 8)
                WHERE po.itemplan = tmp.itemplan
                    AND c.idCentral = po.idCentral 
                    AND po.itemplan = CASE WHEN '".$itemplan."' IS NULL OR '".$itemplan."' = '' THEN po.itemplan
                                           ELSE '".$itemplan."' END
                    AND tmp.flg_ingreso = 0
                    AND tmp.idUsuario = ".$idUsuario."
                    AND po.idEstadoPlan IN (".ESTADO_PLAN_DISENO.")
                    AND tmp.idEstacion = COALESCE(?, tmp.idEstacion)
                    GROUP BY tmp.itemplan, tmp.idEstacion
                    ORDER BY tmp.itemplan
                    limit 10";
        $result = $this->db->query($sql, array($idEstacion));
        return $result->result();	
    }
    
    function aprobEstadoAuto($itemplan) {
        $sql = "SELECT CASE WHEN DATEDIFF(po.fechaPrevEjec, NOW()) <= 60 AND cpo.idSubProyecto IS NOT NULL THEN ".PO_PREAPROBADO."
                            ELSE ".PO_REGISTRADO." END  estado     
                  FROM planobra po LEFT JOIN 
                       config_autoaprob_po cpo ON(po.idSubProyecto = cpo.idSubProyecto)	   
                 WHERE po.itemplan = '".$itemplan."'
                   -- AND po.fechaPrevEjec IS NOT NULL
                 ";
        $result = $this->db->query($sql);
        return $result->row_array()['estado'];	        
    }

    function getCostoMat($codigo_mat) {
        $sql = "SELECT costo_material 
                  FROM material 
                 WHERE id_material = ?";
        $result = $this->db->query($sql, array($codigo_mat));
        return $result->row_array()['costo_material'];	  
    }
}