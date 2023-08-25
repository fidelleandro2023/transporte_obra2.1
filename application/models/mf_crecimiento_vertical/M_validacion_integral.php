<?php
class M_validacion_integral extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
    }

    function getObrasValidadosIntegral() {
        $Query = "SELECT (CASE WHEN po.estado_sol_oc = 'ATENDIDO' THEN po.orden_compra ELSE 'SIN OC' END) as orden_compra, po.has_sirope,
                po.itemplan, ep.estadoPlanDesc, sp.subProyectoDesc, f.faseDesc, po.nombreProyecto, ec.empresaColabDesc, (CASE WHEN po.has_sirope = 1 THEN 'SI' ELSE 'NO' END) sirope, format(sum(pi.total),2) as total 
                FROM partida_itemplan pi, planobra po, subproyecto sp, empresacolab ec, fase f, estadoplan ep
                where pi.itemplan = po.itemplan 
                and po.idSubProyecto = sp.idSubProyecto
                and po.idEmpresaColab = ec.idEmpresaColab
                and po.idFase = f.idFase
                and po.idEstadoPlan = ep.idEstadoPlan
                and po.idEstadoPlan IN (4,21) 
                and po.idSubproyecto = sp.idSubProyecto
                and sp.idTipoSubProyecto = 2
                and po.solicitud_oc_certi is null
                group by po.itemplan, ep.estadoPlanDesc, sp.subProyectoDesc, f.faseDesc, po.nombreProyecto, ec.empresaColabDesc, sirope";
                $result = $this->db->query($Query, array());
        return $result->result();           
    }    
   
    function getPartidasByItemplan($itemplan) {
        $Query = "select pi.id_partida_itemplan,	pi.itemPlan,	pi.idTipoPartida,	pi.id_item_partida,	pi.idEmpresaColab,	format(pi.monto,2) as monto,	pi.cantidad,	format(pi.total,2) as total,	pi.idEstacion, 
						(CASE WHEN pi.idTipoPartida = 1 THEN 'SUBTERRANEO'
                	  WHEN pi.idTipoPartida = 2 THEN 'AEREO'
                	  WHEN pi.idTipoPartida = 3 THEN 'OBRA CIVIL'
                	  END) as tipo_partida, pcv.descripcion, e.estacionDesc 
                	  FROM partida_itemplan pi, partidas_cv pcv, estacion e 
                	  WHERE pi.id_item_partida = pcv.id_item_partida
                and pi.idEstacion = e.idEstacion
                and pi.itemplan = ?";
        $result = $this->db->query($Query, array($itemplan));
        return $result->result();
    }
    
    function updatePartidasMO($arrayPartidas, $arrayLogPartidas){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->trans_begin();
            $this->db->insert_batch('log_edit_partidas_cv_integral', $arrayLogPartidas);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar en log_planobra_po_edit');
            }else{
                $this->db->update_batch('partida_itemplan',$arrayPartidas, 'id_partida_itemplan');
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
    
    function getInfoSolCreacionByItem($itemplan){
        $Query = "SELECT
                	soc.*, po.itemplan, po.idEstadoPlan
                	FROM
                	solicitud_orden_compra soc,
                	planobra po
                	WHERE
                	soc.codigo_solicitud = po.solicitud_oc
                	AND po.itemplan = ?
                	LIMIT 1";
        $result = $this->db->query($Query,array($itemplan));
        if($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }

    function getInfoCostoByItemOfIntegral($itemplan){
        $Query = "select pi.itemplan, sum(total) as total
                    FROM partida_itemplan pi
                    WHERE pi.itemplan = ?
                    group by pi.itemplan";
        $result = $this->db->query($Query,array($itemplan));
        if($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }
    
    function getNextCodigoSolicitud() {
        $Query = "SELECT getNextCodigoSolicitudOC() as codigo_solicitud";
        $result = $this->db->query($Query, array());
        if ($result->row() != null) {
            return $result->row_array()['codigo_solicitud'];
        } else {
            return null;
        }
    }
    
    function validarIntegral($arraySolicitud, $arrayItemXSolicitud, $dataItemplan, $itemplan){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->trans_begin();
            $this->db->insert_batch('solicitud_orden_compra', $arraySolicitud);
            if($this->db->affected_rows() == 0) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar en solicitud_orden_compra');
            }else{
                $this->db->insert_batch('itemplan_x_solicitud_oc', $arrayItemXSolicitud);
                if($this->db->affected_rows() == 0) {
                    $this->db->trans_rollback();
                    throw new Exception('Error al insertar en itemplan_x_solicitud_oc');
                }else{
                    $this->db->where('itemplan', $itemplan);
                    $this->db->update('planobra', $dataItemplan);
                    if($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();
                        throw new Exception('Error al modificar el planobra');
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
}