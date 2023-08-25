<?php

class M_gestion_incidencias extends CI_Model{
    function __construct(){
        parent::__construct();
    }
    
    function getAllEstadosIncidente(){
        $Query  = "SELECT id_estado_incidente, descripcion FROM gi_estado_incidente";
        $result = $this->db->query($Query, array());
        return $result;
    }
    
    function getTipoIncidentes(){
        $Query  = "SELECT id_tipo_incidente, 
		                  descripcion, 
						  comentario, 
						  estado 
		             FROM gi_tipo_incidente 
					WHERE estado = 'A' 
				  ORDER BY descripcion";
        $result = $this->db->query($Query, array());
        return $result;
    }
    
    function getModulos(){
        $Query  = "SELECT id_modulo, descripcion, comentario, estado FROM gi_modulo WHERE estado = 'A' 
		           ORDER BY orden";
        $result = $this->db->query($Query, array());
        return $result;
    }
    
    function getIncidentes($idUsuarioSolicitante = null,$idResponsable = null,
        $codigoIncidente= null, $estado= null, $modulo= null, $tipoIncidente= null){
        $Query  = "SELECT 
                     gi.codigo_incidente, gi.comentario, gi.adjunto_sol, gi.fecha_solicitada, gi.fecha_atendida, 
                     gi.resultado_final, gi.adjunto_resp, gi.procede_fg, gi.motivo_rechazo,
                     gm.id_modulo,
                     gm.descripcion modulo,
                     gti.id_tipo_incidente,
                     gti.descripcion tipo_incidente,
                     gei.id_estado_incidente,
                     gei.descripcion estado,
                     gpi.id_prioridad_incidente,
                     gpi.descripcion prioridad,
                     CASE WHEN (solicitante.nombres <> '' OR solicitante.nombres IS NULL) THEN UPPER(CONCAT(solicitante.nombres,' ',solicitante.ape_paterno,' ',solicitante.ape_materno))
                          ELSE UPPER(solicitante.nombre) END solicitante,
					 CASE WHEN solicitante.id_eecc = 0 THEN 'TDP'
					      ELSE (SELECT empresaColabDesc 
						          FROM empresacolab ee
								 WHERE ee.idEmpresaColab = solicitante.id_eecc) END empColabDescSolicitante,
                     UPPER(CONCAT(responsable.nombres,' ',responsable.ape_paterno,' ',responsable.ape_materno)) responsable,
                     gi.id_responsable,
                     gi.fecha_aprobada,
					 gi.fecha_envio_soporte,
					 gi.itemplan
                    FROM gi_incidentes gi
                    INNER JOIN gi_modulo gm
                    ON gm.id_modulo = gi.id_modulo
                    INNER JOIN gi_tipo_incidente gti
                    ON gti.id_tipo_incidente = gi.id_tipo_incidente
                    INNER JOIN gi_estado_incidente gei
                    ON gei.id_estado_incidente = gi.id_estado_incidente
                    INNER JOIN gi_prioridad_incidente gpi
                    ON gpi.id_prioridad_incidente = gi.id_prioridad_incidente
                    INNER JOIN usuario solicitante
                    ON solicitante.id_usuario = gi.id_solicitante
                    LEFT JOIN gi_responsable_modulo gr
                    ON gr.id_responsable = gi.id_responsable AND gr.id_modulo = gi.id_modulo
                    LEFT JOIN usuario responsable
                    ON responsable.id_usuario = gr.id_responsable
                    WHERE CASE WHEN ('".$codigoIncidente."' IS NOT NULL AND '".$codigoIncidente."' <> '') THEN gi.codigo_incidente = '".$codigoIncidente."'
					           ELSE true END";
        
        if($idResponsable != null || $this->session->userdata('idPersonaSession') == ID_USUARIO_OWEN_SARAVIA || $this->session->userdata('idPersonaSession') == 1677){//COMENTADO LINEA 62 TODOS VEN LO DE TODOS Y AGREGAMOS QUE OWEN VEA / EDUARDO TODO
            // SI ES PERSONAL RESPONSABLE gr.id_responsable
            //$Query .= " ((gr.id_responsable = ? AND gr.id_modulo IN (SELECT id_modulo FROM gi_responsable_modulo WHERE id_responsable = gr.id_responsable)) OR gi.id_solicitante = ?)";
			$Query .= " AND 1 = 1 ";
		}else if($idUsuarioSolicitante != null){
            // SI ES PERSONAL SOLICITANTE gi.id_solicitante
            $Query .= " AND gi.id_solicitante = ?";
        }
        
        //$Query .= " gr.id_responsable = ? AND gr.id_modulo IN (SELECT id_modulo FROM gi_responsable_modulo WHERE id_responsable = gr.id_responsable)";
        //$Query .= " AND gei.id_estado_incidente<>4";
        //$Query .= " gi.id_solicitante = ?";
        
        // if($codigoIncidente != null && $codigoIncidente != ''){
            // $Query .= " AND gi.codigo_incidente = '".$codigoIncidente."'";
        // }
        
        if($estado != null && $estado != ''){
            $Query .= " AND gi.id_estado_incidente = ".$estado." ";
        }
        
        if($modulo != null && $modulo != ''){
            $Query .= " AND gi.id_modulo = ".$modulo." ";
        }
        
        if($tipoIncidente != null && $tipoIncidente != ''){
            $Query .= " AND gi.id_tipo_incidente = ".$tipoIncidente." ";
        }
        
        $Query .= " ORDER BY gi.fecha_solicitada DESC";
        
        if($idResponsable != null){//COMENTADO LINEA 93 TODOS VEN LO DE TODOS
            // SI ES PERSONAL RESPONSABLE gr.id_responsable
            //$result = $this->db->query($Query, array($idResponsable, $idResponsable));
			$result = $this->db->query($Query, array());
        }else if($idUsuarioSolicitante != null){
            // SI ES PERSONAL SOLICITANTE gi.id_solicitante
            $result = $this->db->query($Query, array($idUsuarioSolicitante));
        }
        return $result;
    }
    
    function getModulosOfUsuarioResponsable($idUsuarioResponsable){
        $Query  = "SELECT id_responsable, id_modulo, estado FROM gi_responsable_modulo WHERE id_responsable = ? AND estado = 'A'";
        $result = $this->db->query($Query, array($idUsuarioResponsable));
        return $result;
    }
    
    function obtenerCorrelativo($fechaiso){
        $Query  = "SELECT correlativo FROM gi_correlativo_incidente WHERE fechaiso = ?";
        $result = $this->db->query($Query, array($fechaiso));
        return $result;
    }
    
    function registrarIncidente($dataFormularioIncidente){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->trans_begin();
            $this->db->insert('gi_incidentes', $dataFormularioIncidente);
            if($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar en gi_incidentes');
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
    
    function insertCorrelativo($insert){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->insert('gi_correlativo_incidente', $insert);
            if($this->db->affected_rows() != 1) {
                throw new Exception('Error al insertar en gi_correlativo_incidente');
            }else{
                $data['error']    = EXIT_SUCCESS;
                $data['msj']      = 'Se inserto correctamente!';
            }
             
        }catch(Exception $e){
            $data['msj']   = $e->getMessage();
        }
        return $data;
    }
    
    function updateCorrelativo($update, $fechaiso){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->where('fechaiso', $fechaiso);
	        $this->db->update('gi_correlativo_incidente', $update);
            if($this->db->affected_rows() != 1) {
                throw new Exception('Error al actualizar en gi_correlativo_incidente');
            }else{
                $data['error']    = EXIT_SUCCESS;
                $data['msj']      = 'Se inserto correctamente!';
            }
             
        }catch(Exception $e){
            $data['msj']   = $e->getMessage();
        }
        return $data;
    }
    
    function obtenerResponsableLibre($idModulo){
        $Query  = "SELECT id_responsable, 
                    (SELECT COUNT(*) FROM gi_incidentes WHERE id_responsable = rm.id_responsable AND id_estado_incidente in (2,3)) count
                    FROM gi_responsable_modulo rm
                    WHERE id_modulo = ? AND estado = 'A'
                    ORDER BY 2 ASC
                    LIMIT 1";
        $result = $this->db->query($Query, array($idModulo));
        return $result;
    }
	
	function obtenerResponsablexTipo($idTipoIncidencia){
        $sql  = " SELECT id_responsable_tipo 
				    FROM gi_tipo_incidente
				   WHERE id_tipo_incidente = ? ";
        $result = $this->db->query($sql, array($idTipoIncidencia));
		_log($this->db->last_query());
        return $result->row_array()['id_responsable_tipo'];
    }
    
    function asignarResponsable($update, $codigoIncidente){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->trans_begin();
            $this->db->where('codigo_incidente', $codigoIncidente);
            $this->db->update('gi_incidentes', $update);
            if($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al actualizar en gi_incidentes');
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
    
    function aprobarIncidente($update, $codigoIncidente){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->trans_begin();
            $this->db->where('codigo_incidente', $codigoIncidente);
            $this->db->update('gi_incidentes', $update);
            if($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al actualizar en gi_incidentes');
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
    
    function rechazarIncidente($update, $codigoIncidente){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->trans_begin();
            $this->db->where('codigo_incidente', $codigoIncidente);
            $this->db->update('gi_incidentes', $update);
            if($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al actualizar en gi_incidentes');
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
    
    function cerrarIncidente($update, $codigoIncidente){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->trans_begin();
            $this->db->where('codigo_incidente', $codigoIncidente);
            $this->db->update('gi_incidentes', $update);
            if($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al actualizar en gi_incidentes');
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
    
    function getArchivosDescargaByCodigoIncidente($codigoIncidente){
        $Query  = "SELECT * FROM gi_incidentes WHERE codigo_incidente = ?";
        $result = $this->db->query($Query, array($codigoIncidente));
        return $result;
    }
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function getAllTipoIncidentes(){
        $Query  = "SELECT id_tipo_incidente, descripcion, comentario, estado FROM gi_tipo_incidente";
        $result = $this->db->query($Query, array());
        return $result;
    }
    
    function insertTipoIncidente($insert){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->insert('gi_tipo_incidente', $insert);
            if($this->db->affected_rows() != 1) {
                throw new Exception('Error al insertar en gi_tipo_incidente');
            }else{
                $data['error']    = EXIT_SUCCESS;
                $data['msj']      = 'Se inserto correctamente!';
            }
             
        }catch(Exception $e){
            $data['msj']   = $e->getMessage();
        }
        return $data;
    }
    
    function updateTipoIncidente($update, $tipoIncidente){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->trans_begin();
            $this->db->where('id_tipo_incidente', $tipoIncidente);
            $this->db->update('gi_tipo_incidente', $update);
            if($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al actualizar en gi_tipo_incidente');
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
    
    function countTipoIncidenteById($tipoIncidente){
        $Query  = "SELECT count(*) count FROM gi_tipo_incidente where id_tipo_incidente = ?";
        $result = $this->db->query($Query, array($tipoIncidente));
        return $result;
    }
    
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function getAllModulos(){
        $Query  = "SELECT id_modulo, descripcion, comentario, estado FROM gi_modulo";
        $result = $this->db->query($Query, array());
        return $result;
    }
    
    function insertModulo($insert){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->insert('gi_modulo', $insert);
            if($this->db->affected_rows() != 1) {
                throw new Exception('Error al insertar en gi_tipo_incidente');
            }else{
                $data['error']    = EXIT_SUCCESS;
                $data['msj']      = 'Se inserto correctamente!';
            }
             
        }catch(Exception $e){
            $data['msj']   = $e->getMessage();
        }
        return $data;
    }
    
    function updateModulo($update, $id_modulo){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->trans_begin();
            $this->db->where('id_modulo', $id_modulo);
            $this->db->update('gi_modulo', $update);
            if($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al actualizar en gi_tipo_incidente');
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
    
    function countModuloById($modulo){
        $Query  = "SELECT count(*) count FROM gi_modulo where id_modulo = ?";
        $result = $this->db->query($Query, array($modulo));
        return $result;
    }
    
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function getResponsablesActuales(){
        $Query  = "SELECT
                    rm.id_responsable, u.nombre, u.usuario, 
                    GROUP_CONCAT( CONCAT(rm.estado, '_', m.estado, '_', m.descripcion) SEPARATOR ', ') modulos
                    FROM gi_responsable_modulo rm
                    INNER JOIN usuario u
                    ON rm.id_responsable = u.id_usuario
                    INNER JOIN gi_modulo m
                    ON m.id_modulo = rm.id_modulo
                    GROUP BY id_responsable";
        $result = $this->db->query($Query, array());
        return $result;
    }
    
    function getAllUsuariosCandidatos(){
        $Query  = "SELECT
                    u.id_usuario, u.nombre, u.usuario
                    FROM usuario u WHERE u.id_usuario NOT IN (SELECT rm.id_responsable FROM gi_responsable_modulo rm)";
        $result = $this->db->query($Query, array());
        return $result;
    }
    
    function getModulosLibresPorAsignar($idResponsable){
        $Query  = "SELECT
                    id_modulo, descripcion, comentario, estado
                    FROM gi_modulo 
                    WHERE estado = 'A' ";
        $result = null;
        if($idResponsable != ""){
            $Query .= "AND id_modulo NOT IN (SELECT id_modulo FROM gi_responsable_modulo WHERE id_responsable = ?) ";
            $result = $this->db->query($Query, array($idResponsable));
        }else{
            $result = $this->db->query($Query, array());
        }
        return $result;
    }
    
    function getModulosByIdResponsable($idResponsable){
        $Query  = "SELECT
                    rm.estado,
                    m.descripcion,
                    rm.id_modulo
                    FROM gi_responsable_modulo rm
                    INNER JOIN gi_modulo m
                    ON m.id_modulo = rm.id_modulo
                    WHERE rm.id_responsable = ?";
        $result = $this->db->query($Query, array($idResponsable));
        return $result;
    }
    
    function insertResponsableModulo($idUsuario, $modulos, $estado){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->trans_begin();
            $modulos = explode(',', $modulos);
            $arrayInsert = array();
            foreach($modulos as $idModulo){
                $datatrans['id_responsable']  = $idUsuario;
                $datatrans['id_modulo'] = $idModulo;
                $datatrans['estado'] = $estado;
                array_push($arrayInsert, $datatrans);
            }
            $this->db->insert_batch('gi_responsable_modulo', $arrayInsert);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al insertar el subproyectoestacion.');
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
    
    function updateResponsableModulo($update, $id_responsable, $id_modulo){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->trans_begin();
            $this->db->where('id_responsable', $id_responsable);
            $this->db->where('id_modulo', $id_modulo);
            $this->db->update('gi_responsable_modulo', $update);
            if($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al actualizar en gi_responsable_modulo');
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
}
