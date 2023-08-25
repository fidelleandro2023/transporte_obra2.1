<?php
class M_bandeja_valida_po_pqt extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
    }

    function getBandejaValidacionPoPqt($itemplan, $eecc) {
        $Query = "SELECT DISTINCT
                    pa.*,
                    po.indicador,
                    sp.subProyectoDesc,
                    p.proyectoDesc,
                  #  e.estacionDesc,
                    ec.empresaColabDesc,
                    c.jefatura,
                    FORMAT(pa.costo_inicial, 2) AS costo_inicial_form,
                    FORMAT(pa.costo_adicional, 2) AS costo_adicional_form,
                    FORMAT(pa.costo_total, 2) AS costo_total_form,
                    (CASE
                        WHEN pa.estado = 0 THEN 'PDT VAL NIVEL 1'
                        WHEN pa.estado = 1 THEN 'PDT VAL NIVEL 2'
                        WHEN pa.estado = 2 THEN 'APROBADO'
                        WHEN pa.estado = 3 THEN 'RECHAZADO NIVEL 1'
                        WHEN pa.estado = 4 THEN 'RECHAZADO NIVEL 2'
                    END) AS situacion,
                    CONCAT(u.nombres,
                            ' ',
                            u.ape_paterno,
                            ' ',
                            u.ape_materno) AS nombreCompleto
                FROM
                    planobra po,
                    pqt_central c,
                    subproyecto sp,
                    proyecto p,
                   # estacion e,
                    empresacolab ec,
                    usuario_validador_pqt uv,
                    pqt_solicitud_aprob_partidas_adicionales pa
                        LEFT JOIN
                    usuario u ON pa.usua_registro = u.id_usuario
                WHERE
                    pa.itemplan = po.itemplan
                        AND po.idCentralPqt = c.idCentral
                        AND po.idSubProyecto = sp.idSubProyecto
                        AND sp.idProyecto = p.idProyecto
                    #    AND pa.idEstacion = e.idEstacion
                        AND po.idEmpresaColab = ec.idEmpresaColab        
                        AND c.idJefatura 	= uv.idJefatura
                        AND sp.idProyecto 	= uv.idProyecto
                        AND uv.idUsuario = ".$this->session->userdata('idPersonaSession')."
                        AND (CASE
                        WHEN
                            pa.estado IN (0 , 1)
                        THEN
                            (CASE
                                WHEN uv.nivel_validacion = 1 THEN pa.estado = 0
                                WHEN uv.nivel_validacion = 2 THEN pa.estado = 1
                            END)
                        ELSE TRUE
                    END)
                    AND po.itemplan         = COALESCE(?, po.itemplan)
                    AND po.idEmpresaColab   = COALESCE(?, po.idEmpresaColab)";
        $result = $this->db->query($Query, array($itemplan, $eecc));
        return $result->result();           
    }
    
    function validateNivel1($dataUpdate, $idSolicitud){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->trans_begin();
            $this->db->where('id_solicitud', $idSolicitud);
            $this->db->update('pqt_solicitud_aprob_partidas_adicionales', $dataUpdate);
            if($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                throw new Exception('Error al modificar a pqt_solicitud_aprob_partidas_adicionales');
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
    
    function rechazarSolicitud($dataUpdate, $idSolicitud, $itemplan, $idEstacion){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->trans_begin();
            $this->db->where('itemplan', $itemplan);
            $this->db->where('idEstacion', $idEstacion);
            $this->db->update('itemplan_material_x_estacion_pqt', array('estado' => 2));
            log_message('error', $this->db->last_query());
            if($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                throw new Exception('Error al modificar a itemplan_material_x_estacion_pqt');
            }else{
                $this->db->where('itemplan', $itemplan);
                $this->db->where('idEstacion', $idEstacion);
                $this->db->update('pqt_partidas_adicionales_tmp', array('estado' => 3));
                if($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    throw new Exception('Error al modificar a pqt_partidas_adicionales_tmp');
                }else{
                    $this->db->where('id_solicitud', $idSolicitud);
                    $this->db->update('pqt_solicitud_aprob_partidas_adicionales', $dataUpdate);
                    if($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();
                        throw new Exception('Error al modificar a pqt_solicitud_aprob_partidas_adicionales');
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
	
	function getInfoExpedienteLiquidacion($itemplan, $idEstacion) {
        $Query = "SELECT * FROM itemplan_expediente 
                  WHERE itemplan = ? 
                  AND idEstacion = ?
                  AND path_expediente is not null LIMIT 1";
        $result = $this->db->query($Query, array($itemplan, $idEstacion));
        if ($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }
	
	function getInfoExpedienteLiquidacionNoPqtByItem($itemplan) {
        $Query = "SELECT * FROM itemplan_expediente
                  WHERE itemplan = ?
                  AND idEstacion IS NULL
                  AND path_expediente is not null LIMIT 1";
        $result = $this->db->query($Query, array($itemplan));
        if ($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }
    
}