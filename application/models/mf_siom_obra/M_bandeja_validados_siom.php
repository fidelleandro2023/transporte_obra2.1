<?php
class M_bandeja_validados_siom extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
    }

    function getBandejaSiomValidados($idSubProyecto, $idEmpresaColab, $jefatura, $validado, $sirope, $situacion) {
       // $idEECC  = $this->session->userdata("eeccSession");
        $Query = "  SELECT (CASE WHEN so.ultimo_estado = 'APROBADA' THEN 'APROBADA' ELSE
						   (CASE  WHEN so.estado_observado = 1 THEN 'OBSERVADO' ELSE 'PENDIENTE' END) END) as estadoObser, po.indicador, so.estado_observado, so.itemplan, sp.subProyectoDesc, c.jefatura, ec.empresaColabDesc, e.estacionDesc, so.codigoSiom,
			so.ultimo_estado, so.fecha_ultimo_estado, so.idEstacion, pd.path_expediente_diseno
                    FROM planobra po, subproyecto sp, empresacolab ec, central c, estacion e, siom_obra so LEFT JOIN pre_diseno pd ON so.itemplan = pd.itemplan and so.idEstacion = pd.idEstacion
                    WHERE po.itemplan = so.itemplan
                    AND  po.idSubproyecto = sp.idSubProyecto
                    AND  so.idEstacion = e.idEstacion
                    AND  po.idCentral = c.idCentral
                    AND  so.codigoSiom is not null";
                    
        if($sirope == 'SI'){
            $Query .= " AND po.has_sirope = 1";
        }else{
            $Query .= " AND po.has_sirope is null";
        }
        
        if($validado == 'VALIDANDO'){
            $Query .= " AND so.ultimo_estado = 'VALIDANDO'";
            if($situacion=='OBSERVADO'){
                $Query .= " AND so.estado_observado = 1";
            }else if($situacion=='PENDIENTE'){
                $Query .= " AND (so.estado_observado IS NULL OR so.estado_observado = 0)";
            }
        }else if($validado == 'APROBADA'){
            $Query .= " AND so.ultimo_estado = 'APROBADA'";
        }else{
            $Query .= " AND so.ultimo_estado NOT IN ('VALIDANDO','APROBADA', 'ANULADA', 'RECHAZADA', 'NOACTIVO')";
            if($situacion=='OBSERVADO'){
                $Query .= " AND so.estado_observado = 1";
            }else if($situacion=='PENDIENTE'){
                $Query .= " AND (so.estado_observado IS NULL OR so.estado_observado = 0)";
            }
        }
        
        /*
        if($situacion=='OBSERVADO'){
            $Query .= " AND so.estado_observado = 1";
        }else if($situacion=='PENDIENTE'){
            $Query .= " AND (so.estado_observado IS NULL OR so.estado_observado = 0)";
        }
        */
        
          $Query .= "   AND (CASE WHEN sp.idTipoSubProyecto = 2 THEN c.idEmpresaColabCV = ec.idEmpresaColab                
                            ELSE c.idEmpresaColab = ec.idEmpresaColab END)";
          
        /*
        if($idEECC == ID_EECC_QUANTA || $idEECC == ID_EECC_CAMPERU){
            $Query .= " AND  c.idEmpresaColabCV = ".$idEECC." AND sp.idTipoSubProyecto = 2 ";
        }else if($idEECC != ''  && $idEECC != '0' && $idEECC != '6'){
            $Query .= " AND  c.idEmpresaColab = ".$idEECC." AND sp.idTipoSubProyecto != 2 ";
        }
        */
          
          
        $Query .= ' AND po.idSubProyecto  = COALESCE(?, po.idSubProyecto)
                    AND c.jefatura        = COALESCE(?, c.jefatura)
                    AND ec.idEmpresaColab  = COALESCE(?, ec.idEmpresaColab)';
        $result = $this->db->query($Query, array($idSubProyecto, $jefatura, $idEmpresaColab));
        log_message('error', $this->db->last_query());
        return $result->result();           
    }
    
    function saveObservacionSiom($dataSiomObservado, $codigo_siom, $dataSiomUpdate){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->trans_begin();
            $this->db->insert('siom_observados_log', $dataSiomObservado);
            if($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar en siom_observados_log');
            }else{
                $this->db->where('codigoSiom', $codigo_siom);
                $this->db->update('siom_obra', $dataSiomUpdate);
                if($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    throw new Exception('Error al modificar el siom_obra');
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

    public function listaLogEstadosSIom($codigo_siom)
    {
        $sql = "SELECT 
                    so.*,
                    (CASE
                        WHEN so.tipo = 1 THEN 'OBSERVADO'
                        WHEN so.tipo = 2 THEN 'LIBERADO'
                    END) AS tipoObservacion,
                    CONCAT(u.nombres, ' ', u.ape_paterno) AS nombreCompleto,
                    mv.descripcion
                FROM
                    usuario u,
                    siom_observados_log so
                    left join motivo_observacion_validacion  mv ON so.idMotivoObservado = mv.id
                WHERE
                    so.usuario_registro = u.id_usuario
                AND so.codigo_siom = ?
                ORDER BY fecha_registro ASC ";
        $result = $this->db->query($sql,array($codigo_siom));
        return $result->result();
    }
}