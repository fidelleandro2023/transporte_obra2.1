<?php
class M_bandeja_edit_cv extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
	function   getAllCVPreRegistro($itemplan, $nomProyecto, $estado_plan, $operador){
	    $Query = " SELECT 	cv.itemplan, ep.estadoPlanDesc,
                        	cv.nombre_proyecto,
                        	sp.subProyectoDesc,
                        	cv.nombre_constructora,
	                        cv.estado_aprob,
	                        po.idEstadoPlan,
	                        f.faseDesc AS fase_desc,
	                        cv.operador,
                            idTipoSubProyecto,
                            sp.idSubProyecto
                	FROM   planobra po,
						   planobra_detalle_cv cv,
                	       subproyecto sp,
                           estadoplan ep,
                           fase f
                	WHERE po.itemplan = cv.itemplan
                      AND cv.idSubProyecto = sp.idSubProyecto
                      AND po.idEstadoPlan = ep.idEstadoPlan
                      AND po.idFase = f.idFase
                      AND sp.idTipoSubProyecto IN (1,2) " ;
                       
                   //AND    po.idSubProyecto not IN (".ID_SUBPROYECTO_CV_NEGOCIO_2_BUCLE.")
        if($itemplan!=null){
	        $Query .= " AND po.itemplan = '".$itemplan."'";
	    }
	    if($nomProyecto!=null){
	        $Query .= " AND cv.nombre_proyecto LIKE '%".$nomProyecto."%'";
	    }
	    if($estado_plan!=null){
	        $Query .= " AND po.idEstadoPlan = ".$estado_plan;
	    }
	    if($operador!=null){
	        $Query .= " AND cv.operador LIKE '%".$operador."%'";
	    }
        $result = $this->db->query($Query,array());
	    return $result;
	}

	function aprobarItemplan($estado, $itemplan){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	         
	        $this->db->trans_begin();
	        $dataUpdate = array(
	            "estado_aprob" =>  $estado,
	            "usua_aprob"   =>  $this->session->userdata('userSession'),
	            "fec_aprob"    =>  date("d/m/Y H:i:s")
	        );
	         
	        $this->db->where('itemplan', $itemplan);
	        $this->db->update('planobra_detalle_cv', $dataUpdate);
	        
	        if($this->db->trans_status() === FALSE) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al modificar el updateEstadoPlanObra');
	        }else{
	            if($estado == ESTADO_CV_APROBADO){
	                $dataUpdate = array(
	                    "idEstadoPlan"       =>  ESTADO_PLAN_PRE_DISENO
	                );
	                
	                $this->db->where('itemplan', $itemplan);
	                $this->db->update('planobra', $dataUpdate);
	                 
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
        }catch(Exception $e){
            $data['msj']   = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
	}
	
	
	//////////////////////17-09-2018////////////////////////////
	public function getReporteVR($idEmpresaColab, $porConsumo, $fechaInicio, $fechaFin)
    {
        // $sql = "SELECT e.empresaColabDesc,rv.*
        //           FROM rep_vr_vreecc_ip_mat rv,
        //               empresacolab e
        //          WHERE rv.idEmpresaColab = e.idEmpresaColab
        //           AND rv.idEmpresaColab    = COALESCE(?, rv.idEmpresaColab)
        //           AND rv.porcentaje_consumo >= COALESCE(?, rv.porcentaje_consumo)";
         $sql = " SELECT rv.*,
                        e.empresaColabDesc
                   FROM (

                            SELECT  tb.idEmpresaColab,
                                    tb.id_material,
                                    tb.descrip_material,
                                    tb.cantidad_VR,
                                    tb.total_cant_nec,
                                    tb.total_cant_red,
                                    tb.total_cant_dif,
                                    tb2.total_liqui,
                                    (tb.total_cant_nec - tb2.total_liqui) AS stock_actual,
                                    (ROUND(((tb2.total_liqui)/tb.total_cant_nec)*100,0)) AS porcentaje_consumo
                            FROM (   SELECT vre.idEmpresaColab, vr.id_material, vr.descrip_material,
                                            SUM(vr.cant_nec) AS total_cant_nec, 
                                            SUM(vr.cant_red) AS total_cant_red,
                                            SUM(vr.cant_dif) AS total_cant_dif,
                                            COUNT(vr.codigo_vr) AS cantidad_VR
                                        FROM valereserva_x_empresacolab vre, 
                                            valereserva vr
                                        WHERE vre.codigo_vr = vr.codigo_vr
                                    GROUP BY idEmpresaColab, id_material) AS tb, 
                                    (   SELECT c.idEmpresaColabCV, imc.id_material, SUM(imc.total) AS total_liqui
                                        FROM itemplan_material im, planobra po, central c, 
                                            itemplan_material_cantidad imc, ficha_tecnica ft
                                        WHERE im.itemplan = po.itemplan 
                                        AND im.itemplan = ft.itemplan
                                        AND po.idCentral = c.idCentral
                                        AND im.itemplan = po.itemPlan
                                        AND imc.id_itemplan_material = im.id_itemplan_material ";
        if($fechaInicio != null && $fechaFin != null){
	        $sql .=" AND  DATE_FORMAT(ft.fecha_validacion, '%Y-%m-%d') >= '".$fechaInicio."' AND DATE_FORMAT(ft.fecha_validacion, '%Y-%m-%d') <= '".$fechaFin."' ";
	    }
        $sql .= "
                                    GROUP BY c.idEmpresaColabCV, id_material) AS tb2
                            WHERE tb2.idEmpresaColabCV = tb.idEmpresaColab
                                AND tb2.id_material = tb.id_material
                        ORDER BY tb2.idEmpresaColabCV,tb2.id_material  ) AS rv,
                        empresacolab e
                  WHERE rv.idEmpresaColab = e.idEmpresaColab
                    AND rv.idEmpresaColab = COALESCE(?, rv.idEmpresaColab)
                    AND rv.porcentaje_consumo >= COALESCE(?, rv.porcentaje_consumo)  ";
                    
        $result = $this->db->query($sql, array($idEmpresaColab, $porConsumo));
        return $result->result();
    }
	
	public function getDetalleItemsPlanByMaterial($idMaterial,$idEmpresaColab){
        $sql = "   SELECT po.itemPlan,po.nombreProyecto, eecc.empresaColabDesc,sp.subProyectoDesc,imc.total
                     FROM itemplan_material im,
                          itemplan_material_cantidad imc,
                          planobra po,
                          central c,
                          empresacolab eecc,
                          subproyecto sp
                    WHERE im.id_itemplan_material = imc.id_itemplan_material
                      AND imc.id_material = ".$idMaterial."
                      AND im.itemplan = po.itemPlan
                      AND po.idCentral = c.idCentral
                      AND c.idEmpresaColabCV = eecc.idEmpresaColab
                      AND po.idSubProyecto = sp.idSubProyecto
                      AND eecc.idEmpresaColab = ".$idEmpresaColab."";
        $result = $this->db->query($sql);
        return $result->result();
}

public function getSolicitudMatReq($idEmpresaColab)
    {
        $sql = "   SELECT tb3.empresaColabDesc,
                          tb3.idEmpresaColab,
                          tb3.descrip_material,
                          tb1.id_material,
                          (CASE WHEN tb1.tipo_edif = 0 THEN 'AEREO' ELSE 'SUBTERRANEO' END) AS edif_aereo,
                          tb1.q_edif,
                          tb1.q_material,
                          (CASE WHEN tb2.tipo_edif = 0 THEN 'AEREO' ELSE 'SUBTERRANEO' END) AS edif_subterraneo,
                          tb2.q_edif,
                          tb2.q_material,
                          ROUND(((tb1.q_edif*tb1.q_material+tb2.q_edif*tb2.q_material)/(tb1.q_edif+tb2.q_edif)),0) AS prom_ponderado,
                          tb2.q_pedido,
                          ((ROUND(((tb1.q_edif*tb1.q_material+tb2.q_edif*tb2.q_material)/(tb1.q_edif+tb2.q_edif)),0))*tb2.q_pedido) AS q_mat_pedido,
                          tb3.porcentaje_consumo
                     FROM (SELECT * FROM mat_x_edif WHERE tipo_edif = 0) AS tb1,
                          (SELECT * FROM mat_x_edif WHERE tipo_edif = 1) AS tb2,
                          (SELECT e.empresaColabDesc,rv.*
                             FROM rep_vr_vreecc_ip_mat rv,
                                  empresacolab e
                            WHERE rv.idEmpresaColab = e.idEmpresaColab
                              AND rv.idEmpresaColab = COALESCE(?, rv.idEmpresaColab)
                              AND rv.porcentaje_consumo >= 70) AS tb3
                    WHERE tb1.id_material = tb2.id_material
                      AND tb3.id_material = tb2.id_material";
        $result = $this->db->query($sql, array($idEmpresaColab));
        return $result->result();
    }
    
    
    function cancelItemplanCV($itemplan, $comentario, $idMotivo, $estadoPlan){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->trans_begin();
            $dataUpdate = array(
                'idEstadoPlan' => ID_ESTADO_CANCELADO,
                'fechaCancelacion' => date("Y-m-d H:i:s")
            );
            $this->db->where('itemplan', $itemplan);
            $this->db->update('planobra',$dataUpdate);
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Hubo un error al actualizar el estadoplan.');
            }else{
                 
                $dataUpdateLog= array(
                    'tabla' => 'planobra',
                    'actividad' => 'update',
                    'itemplan' => $itemplan,
                    'itemplan_default' => 'idEstadoPlan='.ID_ESTADO_CANCELADO,
                    'fecha_registro' =>date("Y-m-d H:i:s"),
                    'id_usuario' =>  $this->session->userdata('idPersonaSession')
                );
                 
                $this->db->insert('log_planobra',$dataUpdateLog);
                if ($this->db->affected_rows() != 1) {
                    $this->db->trans_rollback();
                    throw new Exception('Hubo un error al actualizar el estadoplan.');
                }else{
                    $dataPoCancel= array(                       
                        'id_itemplan'   =>  $itemplan,
                        'motivo'        =>  $idMotivo,
                        'comentario'    =>  $comentario,
                        'fecha'         =>  date("Y-m-d H:i:s"),
                        'usuario'       =>  $this->session->userdata('idPersonaSession'),
                        'estado'        =>  $estadoPlan
                    );
                     
                    $this->db->insert('planobra_cancelar',$dataPoCancel);
                    if ($this->db->affected_rows() != 1) {
                        $this->db->trans_rollback();
                        throw new Exception('Hubo un error al actualizar el estadoplan.');
                    }else{                        
                        $data['error']    = EXIT_SUCCESS;
                        $data['msj']      = 'Se actualizo correctamente!';
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
    
    
    public function getItemplanCV($itemplan)
    {
        $sql = "     SELECT p.itemPlan,
                            c.idCentral,
                            p.idSubProyecto,
                            s.subProyectoDesc,
                            p.nombreProyecto,
                            c.codigo, c.tipoCentralDesc,
                            z.zonalDesc,
                            ec.empresaColabDesc,
                            c.idZonal,
                            c.idEmpresaColab,
                            c.idEmpresaColabCV,
                            p.idEstadoPlan,
                            pd.avance,
                            t.estadoPlanDesc
                       FROM planobra p,subproyecto s,
                            planobra_detalle_cv pd,
                            estadoplan t,
                            central c,
                            zonal z,
                            empresacolab ec
                      WHERE s.idSubProyecto = p.idSubProyecto
                        AND c.idCentral = p.idCentral
                        AND c.idZonal = z.idZonal
                        AND p.idEstadoPlan = t.idEstadoPlan
                        AND p.itemPlan = pd.itemplan
                        AND p.idEstadoPlan NOT IN (9,4,5,6)
                        AND p.idSubProyecto IN (96,97,98,99,395,396,463,464)
                        AND	(CASE WHEN p.idSubProyecto = 97 THEN c.idEmpresaColabCV = ec.idEmpresaColab ELSE c.idEmpresaColab = ec.idEmpresaColab END)
                        AND p.itemPlan = '" . $itemplan . "'";
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    public function updatePlanObra($itemplan, $arrayData)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        try {
            $this->db->trans_begin();
            $this->db->where('itemPlan', $itemplan);
            $this->db->update('planobra', $arrayData);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al actualizar el itemplan.');
            } else {
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se actualiz&oacute; correctamente!';
                $this->db->trans_commit();
            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        return $data;
    }

    public function updatePlanObraDetCV($itemplan, $arrayData)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        try {
            $this->db->trans_begin();
            $this->db->where('itemplan', $itemplan);
            $this->db->update('planobra_detalle_cv', $arrayData);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al actualizar el planobra_detalle_cv.');
            } else {
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se actualiz&oacute; correctamente!';
                $this->db->trans_commit();
            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        return $data;
    }

    public function insertarLogPlanObra($arrayInsert)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert('log_planobra', $arrayInsert);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar tabla log plan obra!!');
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

    public function getCountPTRByItemplan($itemplan)
    {
        $sql = " SELECT COUNT(*) AS conteo FROM detalleplan WHERE itemplan = '" . $itemplan . "'";
        $result = $this->db->query($sql);
        return $result->row()->conteo;
    }

    public function getEmpresaColabDiseno($itemplan)
    {
        $sql = " SELECT idEmpresaColabDiseno FROM planobra WHERE itemplan = '" . $itemplan . "'";
        $result = $this->db->query($sql);
        return $result->row()->idEmpresaColabDiseno;
    }

    public function getUserName($idUsuario)
    {
        $sql = " SELECT nombre FROM usuario WHERE id_usuario = '" . $idUsuario . "'";
        $result = $this->db->query($sql);
        return $result->row()->nombre;
    }

    public function insertarPreDiseno($arrayInsert)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->insert_batch('pre_diseno', $arrayInsert);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al insertar en la tabla pre_diseno');
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

    public function getCountPreDisenoByItemplan($itemplan)
    {
        $sql = " SELECT COUNT(*) AS conteo,
                         GROUP_CONCAT(idEstacion) AS string_estaciones 
                   FROM pre_diseno 
                  WHERE itemplan = '" . $itemplan . "'";
        $result = $this->db->query($sql);
        return $result->row_array();
    }

    public function updatePreDiseno($itemplan,$arrayData)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;

        try {
            $this->db->trans_begin();
            $this->db->where('itemplan', $itemplan);
            $this->db->update('pre_diseno', $arrayData);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al actualizar.');
            } else {
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se actualiz&oacute; correctamente!';
                $this->db->trans_commit();
            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        return $data;
    }

    public function getItemplanMasivoCV($arrayItemplan)
    {
        $sql = "     SELECT p.itemPlan,
                            c.idCentral,
                            p.idSubProyecto,
                            s.subProyectoDesc,
                            p.nombreProyecto,
                            c.codigo, c.tipoCentralDesc,
                            z.zonalDesc,
                            ec.empresaColabDesc,
                            c.idZonal,
                            c.idEmpresaColab,
                            c.idEmpresaColabCV,
                            p.idEstadoPlan,
                            pd.avance,
                            t.estadoPlanDesc
                       FROM planobra p,subproyecto s,
                            planobra_detalle_cv pd,
                            estadoplan t,
                            central c,
                            zonal z,
                            empresacolab ec
                      WHERE s.idSubProyecto = p.idSubProyecto
                        AND c.idCentral = p.idCentral
                        AND c.idZonal = z.idZonal
                        AND p.idEstadoPlan = t.idEstadoPlan
                        AND p.itemPlan = pd.itemplan
                        AND p.idEstadoPlan NOT IN (9,4,5,6,8)
                        AND p.idSubProyecto IN (96,97)
                        AND	(CASE WHEN p.idSubProyecto = 97 THEN c.idEmpresaColabCV = ec.idEmpresaColab ELSE c.idEmpresaColab = ec.idEmpresaColab END)
                        AND p.itemPlan IN ? ";
        $result = $this->db->query($sql,array($arrayItemplan));
        return $result->result();
    }
	
	public function getCantidadesReporteCV($idEmpresaColab)
    {
        $sql = " SELECT ( SELECT COUNT(po.itemplan)
                            FROM planobra po,
                                 central c,
                                 empresacolab ec
                           WHERE po.idSubProyecto IN (96,97)
                             AND po.idCentral = c.idCentral
                             AND (CASE WHEN po.idSubProyecto = 97 THEN c.idEmpresaColabCV = ec.idEmpresaColab ELSE c.idEmpresaColab = ec.idEmpresaColab END)
                             AND ec.idEmpresaColab = '" . $idEmpresaColab . "') AS cant_itemplan,
                        ( SELECT COUNT(pdc.coordenada_x)
                            FROM planobra po,
                                 planobra_detalle_cv pdc,
                                 central c,
                                 empresacolab ec
                           WHERE po.itemplan = pdc.itemplan
                             AND po.idSubProyecto IN (96,97)
                             AND po.idCentral = c.idCentral
                             AND (CASE WHEN po.idSubProyecto = 97 THEN c.idEmpresaColabCV = ec.idEmpresaColab ELSE c.idEmpresaColab = ec.idEmpresaColab END)
                             AND ec.idEmpresaColab = '" . $idEmpresaColab . "'
                             AND pdc.coordenada_x IS NOT NULL
                             AND pdc.coordenada_x != '') AS cant_coord_x,
                        ( SELECT COUNT(pdc.coordenada_y)
                            FROM planobra po,
                                 planobra_detalle_cv pdc,
                                 central c,
                                 empresacolab ec
                           WHERE po.itemplan = pdc.itemplan
                             AND po.idSubProyecto IN (96,97)
                             AND po.idCentral = c.idCentral
                             AND (CASE WHEN po.idSubProyecto = 97 THEN c.idEmpresaColabCV = ec.idEmpresaColab ELSE c.idEmpresaColab = ec.idEmpresaColab END)
                             AND ec.idEmpresaColab = '" . $idEmpresaColab . "'
                             AND pdc.coordenada_y IS NOT NULL
                             AND pdc.coordenada_y != '') AS cant_coord_y,
                        ( SELECT COUNT(pdc.depa)
                            FROM planobra po,
                                 planobra_detalle_cv pdc,
                                 central c,
                                 empresacolab ec
                           WHERE po.itemplan = pdc.itemplan
                             AND po.idSubProyecto IN (96,97)
                             AND po.idCentral = c.idCentral
                             AND (CASE WHEN po.idSubProyecto = 97 THEN c.idEmpresaColabCV = ec.idEmpresaColab ELSE c.idEmpresaColab = ec.idEmpresaColab END)
                             AND ec.idEmpresaColab = '" . $idEmpresaColab . "'
                             AND pdc.depa <= 4) AS cant_dpto,
                        ( SELECT COUNT(po.itemplan)
                            FROM planobra po,
                                 central c,
                                 empresacolab ec
                           WHERE po.idSubProyecto IN (96,97)
                             AND po.idCentral = c.idCentral
                             AND (CASE WHEN po.idSubProyecto = 97 THEN c.idEmpresaColabCV = ec.idEmpresaColab ELSE c.idEmpresaColab = ec.idEmpresaColab END)
                             AND ec.idEmpresaColab = '" . $idEmpresaColab . "'
                             AND po.idEstadoPlan = 8) AS cant_ip_pre_regi,
                        ( SELECT COUNT(po.itemplan)
                            FROM planobra po,
                                 central c,
                                 empresacolab ec
                           WHERE po.idSubProyecto IN (96,97)
                             AND po.idCentral = c.idCentral
                             AND (CASE WHEN po.idSubProyecto = 97 THEN c.idEmpresaColabCV = ec.idEmpresaColab ELSE c.idEmpresaColab = ec.idEmpresaColab END)
                             AND ec.idEmpresaColab = '" . $idEmpresaColab . "'
                             AND po.idEstadoPlan = 3) AS cant_ip_obra";
        $result = $this->db->query($sql);
        return $result->row_array();
    }

    public function getMesesCV($idEmpresaColab)
    {
        $sql = "     SELECT SUBSTR(pdcM.fec_termino_constru,6,2) AS num_mes,
                            CASE
                                WHEN SUBSTR(pdcM.fec_termino_constru,6,2) = '01'  THEN  'ENE'
                                WHEN SUBSTR(pdcM.fec_termino_constru,6,2) = '02'  THEN  'FEB'
                                WHEN SUBSTR(pdcM.fec_termino_constru,6,2) = '03'  THEN  'MAR'
                                WHEN SUBSTR(pdcM.fec_termino_constru,6,2) = '04'  THEN  'ABR'
                                WHEN SUBSTR(pdcM.fec_termino_constru,6,2) = '05'  THEN  'MAY'
                                WHEN SUBSTR(pdcM.fec_termino_constru,6,2) = '06'  THEN  'JUN'
                                WHEN SUBSTR(pdcM.fec_termino_constru,6,2) = '07'  THEN  'JUL'
                                WHEN SUBSTR(pdcM.fec_termino_constru,6,2) = '08'  THEN  'AGO'
                                WHEN SUBSTR(pdcM.fec_termino_constru,6,2) = '09'  THEN  'SEP'
                                WHEN SUBSTR(pdcM.fec_termino_constru,6,2) = '10'  THEN  'OCT'
                                WHEN SUBSTR(pdcM.fec_termino_constru,6,2) = '11'  THEN  'NOV'
                                WHEN SUBSTR(pdcM.fec_termino_constru,6,2) = '12'  THEN  'DIC'
                            ELSE 'SIN FECHA'
                            END AS mes,
                            SUBSTR(pdcM.fec_termino_constru,1,4) AS anio
                    FROM planobra po,
                            planobra_detalle_cv pdcM,
                            central c,
                            empresacolab ec
                    WHERE po.itemplan = pdcM.itemplan
                        AND po.idSubProyecto IN (96,97)
                        AND po.idCentral = c.idCentral
                        AND (CASE WHEN po.idSubProyecto = 97 THEN c.idEmpresaColabCV = ec.idEmpresaColab ELSE c.idEmpresaColab = ec.idEmpresaColab END)
                        AND ec.idEmpresaColab = '".$idEmpresaColab."'
                    GROUP BY SUBSTR(pdcM.fec_termino_constru,1,4),SUBSTR(pdcM.fec_termino_constru,6,2)
                    ORDER BY SUBSTR(pdcM.fec_termino_constru,1,4),SUBSTR(pdcM.fec_termino_constru,6,2)";
        $result = $this->db->query($sql);
        return $result->result();
    }
    
     public function getMesesPODetLOGCV($idEmpresaColab)
    {
         $sql = " SELECT t.anio,
                        CASE
                            WHEN t.mes = 1   THEN  'ENE'
                            WHEN t.mes = 2   THEN  'FEB'
                            WHEN t.mes = 3   THEN  'MAR'
                            WHEN t.mes = 4   THEN  'ABR'
                            WHEN t.mes = 5   THEN  'MAY'
                            WHEN t.mes = 6   THEN  'JUN'
                            WHEN t.mes = 7   THEN  'JUL'
                            WHEN t.mes = 8   THEN  'AGO'
                            WHEN t.mes = 9   THEN  'SEP'
                            WHEN t.mes = 10  THEN  'OCT'
                            WHEN t.mes = 11  THEN  'NOV'
                            WHEN t.mes = 12  THEN  'DIC'
                        ELSE 'SIN FECHA'
                        END AS desc_mes,
                        t.mes,
                        COUNT(t.itemplan) AS cant_ip,
                        GROUP_CONCAT(DISTINCT t.itemplan) AS cadena_ip
                FROM(
                        SELECT EXTRACT(YEAR FROM pl.fecha_registro)anio,
                                EXTRACT(MONTH FROM pl.fecha_registro)mes,
                                pl.itemplan, 
                                DATE(MAX(pl.fecha_registro)) fecha,
                                pc.fec_termino_constru
                        FROM planobra_detalle_cv_log pl,
                                planobra_detalle_cv pc,
                                planobra po,
                                central c,
                                empresacolab ec
                        WHERE pl.itemplan = pc.itemplan 
                            AND po.itemplan = pc.itemplan
                            AND po.itemplan = pl.itemplan
                            AND po.idSubProyecto IN (96,97)
                            AND po.idCentral = c.idCentral
                            AND (CASE WHEN po.idSubProyecto = 97 THEN c.idEmpresaColabCV = ec.idEmpresaColab ELSE c.idEmpresaColab = ec.idEmpresaColab END)
                            AND ec.idEmpresaColab = '" . $idEmpresaColab . "'
                            AND SUBSTR(pl.fecha_registro,6,2) = SUBSTR(DATE(pc.fec_termino_constru),6,2)
                            AND SUBSTR(pl.fecha_registro,1,4) = SUBSTR(DATE(pc.fec_termino_constru),1,4)
                        GROUP BY pl.itemplan
                        )t
                    GROUP BY t.mes, t.anio ";
        $result = $this->db->query($sql);
        return $result->result();
    }

    public function quitarLimiteGroupConcat()
    {
        $sql = "SET @@session.group_concat_max_len = 10000; ";
        $result = $this->db->query($sql);
        return $result;
    }

    public function getCountxMesCV($idEmpresaColab, $mes, $anio)
    {
        // if ($mes == null || $mes == '') {
        //     $condicion = " AND SUBSTR(pdcM.fec_termino_constru,6,2) = ''
        //                    AND SUBSTR(pdcM.fec_termino_constru,1,4) = '' ";
        // }else{
        //     $condicion = " AND SUBSTR(pdcM.fec_termino_constru,6,2) = '" . $mes . "'
        //                    AND SUBSTR(pdcM.fec_termino_constru,6,2) = '" . $anio . "'";
        // }
        // $sql = "SET @@session.group_concat_max_len = 10000; ";

        $sql = " SELECT
                        ( SELECT CONCAT(COUNT(pdc.itemplan),'|', (CASE WHEN (group_concat(pdc.itemplan)) IS NULL THEN '0' ELSE (group_concat(pdc.itemplan)) END ) )
                            FROM planobra po,
                                 planobra_detalle_cv pdc,
                                 central c,
                                 empresacolab ec
                           WHERE po.itemplan = pdc.itemplan
                             AND po.idSubProyecto IN (96,97)
                             AND po.idCentral = c.idCentral
                             AND (CASE WHEN po.idSubProyecto = 97 THEN c.idEmpresaColabCV = ec.idEmpresaColab ELSE c.idEmpresaColab = ec.idEmpresaColab END)
                             AND ec.idEmpresaColab = '" . $idEmpresaColab . "'
                             AND pdc.avance = 0
                             AND po.idEstadoPlan = 3
                             AND SUBSTR(pdc.fec_termino_constru,6,2) = COALESCE(?, NULL)
                             AND SUBSTR(pdc.fec_termino_constru,1,4) = COALESCE(?, NULL)  ) AS cant_ip_avance_0,
                        ( SELECT CONCAT(COUNT(pdc.itemplan),'|', (CASE WHEN (group_concat(pdc.itemplan)) IS NULL THEN '0' ELSE (group_concat(pdc.itemplan)) END ) )
                            FROM planobra po,
                                 planobra_detalle_cv pdc,
                                 central c,
                                 empresacolab ec
                           WHERE po.itemplan = pdc.itemplan
                             AND po.idSubProyecto IN (96,97)
                             AND po.idCentral = c.idCentral
                             AND (CASE WHEN po.idSubProyecto = 97 THEN c.idEmpresaColabCV = ec.idEmpresaColab ELSE c.idEmpresaColab = ec.idEmpresaColab END)
                             AND ec.idEmpresaColab = '" . $idEmpresaColab . "'
                             AND pdc.avance BETWEEN 1 AND 25
                             AND po.idEstadoPlan = 3
                             AND SUBSTR(pdc.fec_termino_constru,6,2) = COALESCE(?, NULL)
                             AND SUBSTR(pdc.fec_termino_constru,1,4) = COALESCE(?, NULL)  ) AS cant_ip_avance_25,
                        ( SELECT CONCAT(COUNT(pdc.itemplan),'|', (CASE WHEN (group_concat(pdc.itemplan)) IS NULL THEN '0' ELSE (group_concat(pdc.itemplan)) END ) )
                            FROM planobra po,
                                 planobra_detalle_cv pdc,
                                 central c,
                                 empresacolab ec
                           WHERE po.itemplan = pdc.itemplan
                             AND po.idSubProyecto IN (96,97)
                             AND po.idCentral = c.idCentral
                             AND (CASE WHEN po.idSubProyecto = 97 THEN c.idEmpresaColabCV = ec.idEmpresaColab ELSE c.idEmpresaColab = ec.idEmpresaColab END)
                             AND ec.idEmpresaColab = '" . $idEmpresaColab . "'
                             AND pdc.avance BETWEEN 26 AND 50
                             AND po.idEstadoPlan = 3
                             AND SUBSTR(pdc.fec_termino_constru,6,2) = COALESCE(?, NULL)
                             AND SUBSTR(pdc.fec_termino_constru,1,4) = COALESCE(?, NULL) ) AS cant_ip_avance_50,
                        ( SELECT CONCAT(COUNT(pdc.itemplan),'|', (CASE WHEN (group_concat(pdc.itemplan)) IS NULL THEN '0' ELSE (group_concat(pdc.itemplan)) END ) )
                            FROM planobra po,
                                 planobra_detalle_cv pdc,
                                 central c,
                                 empresacolab ec
                           WHERE po.itemplan = pdc.itemplan
                             AND po.idSubProyecto IN (96,97)
                             AND po.idCentral = c.idCentral
                             AND (CASE WHEN po.idSubProyecto = 97 THEN c.idEmpresaColabCV = ec.idEmpresaColab ELSE c.idEmpresaColab = ec.idEmpresaColab END)
                             AND ec.idEmpresaColab = '" . $idEmpresaColab . "'
                             AND pdc.avance BETWEEN 51 AND 75
                             AND po.idEstadoPlan = 3
                             AND SUBSTR(pdc.fec_termino_constru,6,2) = COALESCE(?, NULL)
                             AND SUBSTR(pdc.fec_termino_constru,1,4) = COALESCE(?, NULL) ) AS cant_ip_avance_75,
                        ( SELECT CONCAT(COUNT(pdc.itemplan),'|', (CASE WHEN (group_concat(pdc.itemplan)) IS NULL THEN '0' ELSE (group_concat(pdc.itemplan)) END ) )
                            FROM planobra po,
                                 planobra_detalle_cv pdc,
                                 central c,
                                 empresacolab ec
                           WHERE po.itemplan = pdc.itemplan
                             AND po.idSubProyecto IN (96,97)
                             AND po.idCentral = c.idCentral
                             AND (CASE WHEN po.idSubProyecto = 97 THEN c.idEmpresaColabCV = ec.idEmpresaColab ELSE c.idEmpresaColab = ec.idEmpresaColab END)
                             AND ec.idEmpresaColab = '" . $idEmpresaColab . "'
                             AND pdc.avance BETWEEN 76 AND 100
                             AND po.idEstadoPlan = 3
                             AND SUBSTR(pdc.fec_termino_constru,6,2) = COALESCE(?, NULL)
                             AND SUBSTR(pdc.fec_termino_constru,1,4) = COALESCE(?, NULL) ) AS cant_ip_avance_100";

        $result = $this->db->query($sql, array($mes, $anio, $mes, $anio, $mes, $anio, $mes, $anio, $mes, $anio));
        return $result->row_array();

    }

    public function getCountReporte2CV($idEmpresaColab, $mes, $anio)
    {
        $sql = " SELECT
                ( (CASE WHEN (SELECT CONCAT(COUNT(pdcl.itemplan),'|', (CASE WHEN (group_concat(pdcl.itemplan)) IS NULL THEN '0' ELSE (group_concat(pdcl.itemplan)) END ) )
                                FROM planobra po,
                                    planobra_detalle_cv pdc,
                                    planobra_detalle_cv_log pdcl,
                                    central c,
                                    empresacolab ec
                            WHERE po.itemplan = pdc.itemplan
                                AND po.itemplan = pdcl.itemplan
                                AND pdc.itemplan = pdcl.itemplan
                                AND po.idSubProyecto IN (96,97)
                                AND po.idCentral = c.idCentral
                                AND (CASE WHEN po.idSubProyecto = 97 THEN c.idEmpresaColabCV = ec.idEmpresaColab ELSE c.idEmpresaColab = ec.idEmpresaColab END)
                                AND ec.idEmpresaColab = '" . $idEmpresaColab . "'
                                AND pdc.avance = 0
                                AND SUBSTR(pdcl.fecha_registro,6,2) = COALESCE(?, NULL)
                                AND SUBSTR(pdcl.fecha_registro,1,4) = COALESCE(?, NULL) 
                                AND pdcl.fecha_registro = (SELECT MAX(fecha_registro) 
                                                            FROM planobra_detalle_cv_log
                                                            WHERE itemplan = pdcl.itemplan)) IS NULL THEN 0 ELSE (SELECT CONCAT(COUNT(pdcl.itemplan),'|', (CASE WHEN (group_concat(pdcl.itemplan)) IS NULL THEN '0' ELSE (group_concat(pdcl.itemplan)) END ) )
                                                                                                                    FROM planobra po,
                                                                                                                        planobra_detalle_cv pdc,
                                                                                                                        planobra_detalle_cv_log pdcl,
                                                                                                                        central c,
                                                                                                                        empresacolab ec
                                                                                                                WHERE po.itemplan = pdc.itemplan
                                                                                                                    AND po.itemplan = pdcl.itemplan
                                                                                                                    AND pdc.itemplan = pdcl.itemplan
                                                                                                                    AND po.idSubProyecto IN (96,97)
                                                                                                                    AND po.idCentral = c.idCentral
                                                                                                                    AND (CASE WHEN po.idSubProyecto = 97 THEN c.idEmpresaColabCV = ec.idEmpresaColab ELSE c.idEmpresaColab = ec.idEmpresaColab END)
                                                                                                                    AND ec.idEmpresaColab = '" . $idEmpresaColab . "'
                                                                                                                    AND pdc.avance = 0
                                                                                                                    AND SUBSTR(pdcl.fecha_registro,6,2) = COALESCE(?, NULL)
                                                                                                                    AND SUBSTR(pdcl.fecha_registro,1,4) = COALESCE(?, NULL) 
                                                                                                                    AND pdcl.fecha_registro = (SELECT MAX(fecha_registro) 
                                                                                                                                                FROM planobra_detalle_cv_log
                                                                                                                                                WHERE itemplan = pdcl.itemplan)) END) ) AS cant_ip_avance_0,
                ((CASE WHEN ( SELECT CONCAT(COUNT(pdcl.itemplan),'|', (CASE WHEN (group_concat(pdcl.itemplan)) IS NULL THEN '0' ELSE (group_concat(pdcl.itemplan)) END ) )
                                FROM planobra po,
                                    planobra_detalle_cv pdc,
                                    planobra_detalle_cv_log pdcl,
                                    central c,
                                    empresacolab ec
                            WHERE po.itemplan = pdc.itemplan
                                AND po.itemplan = pdcl.itemplan
                                AND pdc.itemplan = pdcl.itemplan
                                AND po.idSubProyecto IN (96,97)
                                AND po.idCentral = c.idCentral
                                AND (CASE WHEN po.idSubProyecto = 97 THEN c.idEmpresaColabCV = ec.idEmpresaColab ELSE c.idEmpresaColab = ec.idEmpresaColab END)
                                AND ec.idEmpresaColab = '" . $idEmpresaColab . "'
                                AND pdc.avance BETWEEN 1 AND 25
                                AND SUBSTR(pdcl.fecha_registro,6,2) = COALESCE(?, NULL)
                                AND SUBSTR(pdcl.fecha_registro,1,4) = COALESCE(?, NULL) 
                                AND pdcl.fecha_registro = (SELECT MAX(fecha_registro) 
                                                            FROM planobra_detalle_cv_log
                                                            WHERE itemplan = pdcl.itemplan) ) IS NULL THEN 0 ELSE ( SELECT CONCAT(COUNT(pdcl.itemplan),'|', (CASE WHEN (group_concat(pdcl.itemplan)) IS NULL THEN '0' ELSE (group_concat(pdcl.itemplan)) END ) )
                                                                                                                      FROM planobra po,
                                                                                                                           planobra_detalle_cv pdc,
                                                                                                                           planobra_detalle_cv_log pdcl,
                                                                                                                           central c,
                                                                                                                           empresacolab ec
                                                                                                                     WHERE po.itemplan = pdc.itemplan
                                                                                                                       AND po.itemplan = pdcl.itemplan
                                                                                                                       AND pdc.itemplan = pdcl.itemplan
                                                                                                                       AND po.idSubProyecto IN (96,97)
                                                                                                                       AND po.idCentral = c.idCentral
                                                                                                                       AND (CASE WHEN po.idSubProyecto = 97 THEN c.idEmpresaColabCV = ec.idEmpresaColab ELSE c.idEmpresaColab = ec.idEmpresaColab END)
                                                                                                                       AND ec.idEmpresaColab = '" . $idEmpresaColab . "'
                                                                                                                       AND pdc.avance BETWEEN 1 AND 25
                                                                                                                       AND SUBSTR(pdcl.fecha_registro,6,2) = COALESCE(?, NULL)
                                                                                                                       AND SUBSTR(pdcl.fecha_registro,1,4) = COALESCE(?, NULL) 
                                                                                                                       AND pdcl.fecha_registro = (SELECT MAX(fecha_registro) 
                                                                                                                                                    FROM planobra_detalle_cv_log
                                                                                                                                                    WHERE itemplan = pdcl.itemplan) ) END) ) AS cant_ip_avance_25,
                ( (CASE WHEN (SELECT CONCAT(COUNT(pdcl.itemplan),'|', (CASE WHEN (group_concat(pdcl.itemplan)) IS NULL THEN '0' ELSE (group_concat(pdcl.itemplan)) END ) )
                               FROM planobra po,
                                    planobra_detalle_cv pdc,
                                    planobra_detalle_cv_log pdcl,
                                    central c,
                                    empresacolab ec
                              WHERE po.itemplan = pdc.itemplan
                                AND po.itemplan = pdcl.itemplan
                                AND pdc.itemplan = pdcl.itemplan
                                AND po.idSubProyecto IN (96,97)
                                AND po.idCentral = c.idCentral
                                AND (CASE WHEN po.idSubProyecto = 97 THEN c.idEmpresaColabCV = ec.idEmpresaColab ELSE c.idEmpresaColab = ec.idEmpresaColab END)
                                AND ec.idEmpresaColab = '" . $idEmpresaColab . "'
                                AND pdc.avance BETWEEN 26 AND 50
                                AND SUBSTR(pdcl.fecha_registro,6,2) = COALESCE(?, NULL)
                                AND SUBSTR(pdcl.fecha_registro,1,4) = COALESCE(?, NULL) 
                                AND pdcl.fecha_registro = (SELECT MAX(fecha_registro) 
                                                            FROM planobra_detalle_cv_log
                                                            WHERE itemplan = pdcl.itemplan)) IS NULL THEN 0 ELSE (SELECT CONCAT(COUNT(pdcl.itemplan),'|', (CASE WHEN (group_concat(pdcl.itemplan)) IS NULL THEN '0' ELSE (group_concat(pdcl.itemplan)) END ) )
                                                                                                                    FROM planobra po,
                                                                                                                         planobra_detalle_cv pdc,
                                                                                                                         planobra_detalle_cv_log pdcl,
                                                                                                                         central c,
                                                                                                                         empresacolab ec
                                                                                                                  WHERE po.itemplan = pdc.itemplan
                                                                                                                    AND po.itemplan = pdcl.itemplan
                                                                                                                    AND pdc.itemplan = pdcl.itemplan
                                                                                                                    AND po.idSubProyecto IN (96,97)
                                                                                                                    AND po.idCentral = c.idCentral
                                                                                                                    AND (CASE WHEN po.idSubProyecto = 97 THEN c.idEmpresaColabCV = ec.idEmpresaColab ELSE c.idEmpresaColab = ec.idEmpresaColab END)
                                                                                                                    AND ec.idEmpresaColab = '" . $idEmpresaColab . "'
                                                                                                                    AND pdc.avance BETWEEN 26 AND 50
                                                                                                                    AND SUBSTR(pdcl.fecha_registro,6,2) = COALESCE(?, NULL)
                                                                                                                    AND SUBSTR(pdcl.fecha_registro,1,4) = COALESCE(?, NULL) 
                                                                                                                    AND pdcl.fecha_registro = (SELECT MAX(fecha_registro) 
                                                                                                                                                 FROM planobra_detalle_cv_log
                                                                                                                                                 WHERE itemplan = pdcl.itemplan)) END) ) AS cant_ip_avance_50,
                ( (CASE WHEN (SELECT CONCAT(COUNT(pdcl.itemplan),'|', (CASE WHEN (group_concat(pdcl.itemplan)) IS NULL THEN '0' ELSE (group_concat(pdcl.itemplan)) END ) )
                               FROM planobra po,
                                    planobra_detalle_cv pdc,
                                    planobra_detalle_cv_log pdcl,
                                    central c,
                                    empresacolab ec
                              WHERE po.itemplan = pdc.itemplan
                                AND po.itemplan = pdcl.itemplan
                                AND pdc.itemplan = pdcl.itemplan
                                AND po.idSubProyecto IN (96,97)
                                AND po.idCentral = c.idCentral
                                AND (CASE WHEN po.idSubProyecto = 97 THEN c.idEmpresaColabCV = ec.idEmpresaColab ELSE c.idEmpresaColab = ec.idEmpresaColab END)
                                AND ec.idEmpresaColab = '" . $idEmpresaColab . "'
                                AND pdc.avance BETWEEN 51 AND 75
                                AND SUBSTR(pdcl.fecha_registro,6,2) = COALESCE(?, NULL)
                                AND SUBSTR(pdcl.fecha_registro,1,4) = COALESCE(?, NULL) 
                                AND pdcl.fecha_registro = (SELECT MAX(fecha_registro) 
                                                            FROM planobra_detalle_cv_log
                                                            WHERE itemplan = pdcl.itemplan) ) IS NULL THEN 0 ELSE (SELECT CONCAT(COUNT(pdcl.itemplan),'|', (CASE WHEN (group_concat(pdcl.itemplan)) IS NULL THEN '0' ELSE (group_concat(pdcl.itemplan)) END ) )
                                                                                                                     FROM planobra po,
                                                                                                                          planobra_detalle_cv pdc,
                                                                                                                          planobra_detalle_cv_log pdcl,
                                                                                                                          central c,
                                                                                                                          empresacolab ec
                                                                                                                    WHERE po.itemplan = pdc.itemplan
                                                                                                                     AND po.itemplan = pdcl.itemplan
                                                                                                                     AND pdc.itemplan = pdcl.itemplan
                                                                                                                     AND po.idSubProyecto IN (96,97)
                                                                                                                     AND po.idCentral = c.idCentral
                                                                                                                     AND (CASE WHEN po.idSubProyecto = 97 THEN c.idEmpresaColabCV = ec.idEmpresaColab ELSE c.idEmpresaColab = ec.idEmpresaColab END)
                                                                                                                     AND ec.idEmpresaColab = '" . $idEmpresaColab . "'
                                                                                                                     AND pdc.avance BETWEEN 51 AND 75
                                                                                                                     AND SUBSTR(pdcl.fecha_registro,6,2) = COALESCE(?, NULL)
                                                                                                                     AND SUBSTR(pdcl.fecha_registro,1,4) = COALESCE(?, NULL) 
                                                                                                                     AND pdcl.fecha_registro = (SELECT MAX(fecha_registro) 
                                                                                                                                                 FROM planobra_detalle_cv_log
                                                                                                                                                 WHERE itemplan = pdcl.itemplan) ) END) ) AS cant_ip_avance_75,
                ( (CASE WHEN (SELECT CONCAT(COUNT(pdcl.itemplan),'|', (CASE WHEN (group_concat(pdcl.itemplan)) IS NULL THEN '0' ELSE (group_concat(pdcl.itemplan)) END ) )
                                FROM planobra po,
                                     planobra_detalle_cv pdc,
                                     planobra_detalle_cv_log pdcl,
                                     central c,
                                     empresacolab ec
                               WHERE po.itemplan = pdc.itemplan
                                 AND po.itemplan = pdcl.itemplan
                                 AND pdc.itemplan = pdcl.itemplan
                                 AND po.idSubProyecto IN (96,97)
                                 AND po.idCentral = c.idCentral
                                 AND (CASE WHEN po.idSubProyecto = 97 THEN c.idEmpresaColabCV = ec.idEmpresaColab ELSE c.idEmpresaColab = ec.idEmpresaColab END)
                                 AND ec.idEmpresaColab = '" . $idEmpresaColab . "'
                                 AND pdc.avance BETWEEN 76 AND 100
                                 AND SUBSTR(pdcl.fecha_registro,6,2) = COALESCE(?, NULL)
                                 AND SUBSTR(pdcl.fecha_registro,1,4) = COALESCE(?, NULL) 
                                 AND pdcl.fecha_registro = (SELECT MAX(fecha_registro) 
                                                              FROM planobra_detalle_cv_log
                                                             WHERE itemplan = pdcl.itemplan)) IS NULL THEN 0 ELSE (SELECT CONCAT(COUNT(pdcl.itemplan),'|', (CASE WHEN (group_concat(pdcl.itemplan)) IS NULL THEN '0' ELSE (group_concat(pdcl.itemplan)) END ) )
                                                                                                                     FROM planobra po,
                                                                                                                          planobra_detalle_cv pdc,
                                                                                                                          planobra_detalle_cv_log pdcl,
                                                                                                                          central c,
                                                                                                                          empresacolab ec
                                                                                                                    WHERE po.itemplan = pdc.itemplan
                                                                                                                      AND po.itemplan = pdcl.itemplan
                                                                                                                      AND pdc.itemplan = pdcl.itemplan
                                                                                                                      AND po.idSubProyecto IN (96,97)
                                                                                                                      AND po.idCentral = c.idCentral
                                                                                                                      AND (CASE WHEN po.idSubProyecto = 97 THEN c.idEmpresaColabCV = ec.idEmpresaColab ELSE c.idEmpresaColab = ec.idEmpresaColab END)
                                                                                                                      AND ec.idEmpresaColab = '" . $idEmpresaColab . "'
                                                                                                                      AND pdc.avance BETWEEN 76 AND 100
                                                                                                                      AND SUBSTR(pdcl.fecha_registro,6,2) = COALESCE(?, NULL)
                                                                                                                      AND SUBSTR(pdcl.fecha_registro,1,4) = COALESCE(?, NULL) 
                                                                                                                      AND pdcl.fecha_registro = (SELECT MAX(fecha_registro) 
                                                                                                                                                   FROM planobra_detalle_cv_log
                                                                                                                                                  WHERE itemplan = pdcl.itemplan)) END) ) AS cant_ip_avance_100";

        $result = $this->db->query($sql, array($mes, $anio, $mes, $anio, $mes, $anio, $mes, $anio, $mes, $anio,$mes, $anio, $mes, $anio, $mes, $anio, $mes, $anio, $mes, $anio));
        return $result->row_array();
    }

    public function getDetIPSByArryIPS($arrayIPS)
    {
        $sql = "     SELECT p.itemPlan,
                            c.idCentral,
                            p.idSubProyecto,
                            s.subProyectoDesc,
                            p.nombreProyecto,
                            c.codigo, c.tipoCentralDesc,
                            z.zonalDesc,
                            ec.empresaColabDesc,
                            c.idZonal,
                            c.jefatura,
                            p.idEstadoPlan,
                            (CASE WHEN pd.fec_termino_constru IS NULL  OR pd.fec_termino_constru = '' THEN '-' ELSE pd.fec_termino_constru END) AS fec_termino_constru,
                            (CASE WHEN pd.estado_edificio IS NULL  OR pd.estado_edificio = '' THEN 'NO TIENE' ELSE pd.estado_edificio END) AS estado_edifico,
                            pd.coordenada_x,
                            pd.coordenada_y,
                            pd.avance,
                            t.estadoPlanDesc
                       FROM planobra p,subproyecto s,
                            planobra_detalle_cv pd,
                            estadoplan t,
                            central c,
                            zonal z,
                            empresacolab ec
                      WHERE s.idSubProyecto = p.idSubProyecto
                        AND c.idCentral = p.idCentral
                        AND c.idZonal = z.idZonal
                        AND p.idEstadoPlan = t.idEstadoPlan
                        AND p.itemPlan = pd.itemplan
                        AND p.idSubProyecto IN (96,97)
                        AND	(CASE WHEN p.idSubProyecto = 97 THEN c.idEmpresaColabCV = ec.idEmpresaColab ELSE c.idEmpresaColab = ec.idEmpresaColab END)
                        AND p.itemPlan IN ? ";
        $result = $this->db->query($sql, array($arrayIPS));
        return $result->result();
    }
    
     public function deletePrediseno($itemplan,$idEstacion)
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $this->db->trans_begin();
            $this->db->where('itemplan', $itemplan);
            $this->db->where('idEstacion', $idEstacion);
            $this->db->delete('pre_diseno');

            if ($this->db->trans_status() === true) {
                $this->db->trans_commit();
                $data['error'] = EXIT_SUCCESS;
                $data['msj'] = 'Se elimin&oacute; correctamente la estacion en pre_diseno.';
            } else {
                $this->db->trans_rollback();
                throw new Exception('Error al eliminar la estacion en pre_diseno');
            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }

        return $data;
    }
    
	public function getEstadosItemplan(){
        $Query = "SELECT idEstadoPlan, UPPER(estadoPlanDesc) AS  estadoPlanDesc
					FROM estadoplan
	                WHERE idEstadoPlan IN (".ESTADO_PLAN_PRE_REGISTRO.",".ESTADO_PLAN_PRE_DISENO.",".ESTADO_PLAN_DISENO.",".ESTADO_PLAN_EN_OBRA.",".ID_ESTADO_PRE_LIQUIDADO.",".ESTADO_PLAN_DISENO_EJECUTADO.",".ID_ESTADO_DISENIO_PARCIAL.",".ID_ESTADO_TERMINADO.",".ID_ESTADO_CANCELADO.")
					ORDER BY idEstadoPlan";
        $result = $this->db->query($Query,array());
        return $result;
    }
    
    
     public function getDetalleIPCV($itemplan)
    {
        $sql = "     SELECT p.itemPlan,
                            c.idCentral,
                            p.idSubProyecto,
                            s.subProyectoDesc,
                            p.nombreProyecto,
                            c.idZonal,
                            c.idEmpresaColab,
                            c.idEmpresaColabCV,
                            p.idEstadoPlan
                       FROM planobra p,
                            subproyecto s,
                            central c
                      WHERE p.idSubProyecto = s.idSubProyecto
                        AND p.idCentral = c.idCentral
                        AND p.idEstadoPlan NOT IN (9,4,5,6,8)
                        AND p.idSubProyecto IN (96,97)
                        AND p.itemPlan = '" . $itemplan . "'";
        $result = $this->db->query($sql);
        return $result->row_array();
    }
	
}