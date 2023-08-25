<?php
class M_bandeja_cotizacion_individual extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
    function getItemplanPreRegistro($idSubProyecto, $codigo, $estado, $idJefatura, $idEmpresaColab, $flgBandConf){
        $ideecc  = $this->session->userdata("eeccSession");
        $sql = " SELECT DISTINCT
                        CASE WHEN co.flg_robot = 1 THEN 'BANDEJA ROBOT'
						     WHEN co.flg_robot = 2 THEN 'BANDEJA EECC' END bandejEecRobot,
						co.flg_robot,
						co.codigo_cluster AS codigo,
                        co.id,
                        CASE WHEN co.estado  = 0 THEN 'PDT COTIZACION'
                             WHEN co.estado  = 1 THEN 'PDT APROBACION'
                             WHEN co.estado  = 2 THEN 'APROBADO'
                              WHEN co.estado = 3 THEN 'RECHAZADO'
                             WHEN co.estado  = 4 THEN 'PDT CONFIRMACION' END estadoDesc, 
                        s.subProyectoDesc,
                        co.sisego,
                        co.cliente,
                        co.descripcion,
                        co.acceso_cliente,
                        co.tendido_externo,
                        co.tipo_cliente,
                        co.nro_pisos,
                        co.departamento,
                        co.provincia,
                        UPPER(co.distrito) as distrito,
                        co.direccion,
                        co.piso,
                        co.interior,
                        co.latitud,
                        co.longitud,
                        co.nombre_estudio,
                        co.idSubProyecto,
                        co.estado,
                        co.fecha_registro,
                        co.fecha_envio_cotizacion,
                        (SELECT UPPER(nombre) 
                           FROM usuario
                          WHERE id_usuario = co.usuario_envio_cotizacion)usuarioEnviaCotizacion,
                        (SELECT UPPER(nombre) 
                           FROM usuario
                          WHERE id_usuario = cv.id_usuario_valida)usuarioConfirmaRechaz,
                        ROUND(COALESCE(co.costo_materiales, 0)+ COALESCE(co.costo_mat_edif, 0), 2) as costo_materiales,
                        ROUND(COALESCE(co.costo_mano_obra, 0)+ COALESCE(co.costo_mo_edif, 0) + COALESCE(co.costo_oc, 0) + COALESCE(co.costo_oc_edif, 0), 2) costo_mano_obra,
                        ce.jefatura,
                        e.empresaColabDesc,
                        ce.codigo AS codigoMdf,
                        co.flg_principal,
                        CASE WHEN co.flg_lan_to_lan = 1 THEN co.nombre_estudio
						     WHEN co.flg_principal  = 0 THEN 'PRINCIPAL'
						     WHEN co.flg_principal  = 1 THEN 'RESPALDO' END AS tipoSisego,
                        cv.fecha_validacion,
                        co.clasificacion,
                        cv.observacion,
                        CASE WHEN co.flg_rech_conf_ban_conf = 1 THEN 'APROBADO EN LA BANDEJA CONFIRMACIÓN'
                             WHEN co.flg_rech_conf_ban_conf = 2 THEN 'RECHAZADO EN LA BANDEJA CONFIRMACIÓN' END AS statusBandjConfirmacion
                   FROM (planobra_cluster co,
                         subproyecto s,
                         central ce,
                         empresacolab e)
               LEFT JOIN cotizacion_validar cv 
                     ON (cv.codigo_cluster = co.codigo_cluster AND cv.flg_validacion = 2 AND cv.fecha_validacion = ((SELECT MAX(fecha_validacion)  fecha_validacion
                         																							   FROM cotizacion_validar 
                                                                                                                      WHERE codigo_cluster = cv.codigo_cluster LIMIT 1) ))
                  WHERE s.idSubProyecto   = co.idSubProyecto
                    AND e.idEmpresaColab  = co.idEmpresaColab
                    AND ce.idCentral      = co.idCentral
                    AND co.idSubProyecto  = COALESCE(?, co.idSubProyecto)
                    AND co.codigo_cluster = COALESCE(?, co.codigo_cluster)
                    AND co.estado         = COALESCE(?, co.estado)
                    AND ce.idJefatura     = COALESCE(?, ce.idJefatura)
                    AND co.idEmpresaColab = COALESCE(?, co.idEmpresaColab)
                    AND CASE WHEN ? IS NOT NULL THEN co.flg_rech_conf_ban_conf = ?
                             ELSE TRUE END
                    AND co.estado IN (0,1,4)
                    AND co.flg_tipo = 2
					AND co.flg_paquetizado IS NULL
					-- AND co.fecha_registro < '2020-01-24'
                    AND CASE WHEN ".$ideecc." = 0 OR ".$ideecc." = 6 THEN true
                             ELSE co.idEmpresaColab = ".$ideecc." AND co.flg_robot = 2 END
		        
				UNION ALL
				SELECT DISTINCT
						CASE WHEN co.flg_robot = 1 THEN 'BANDEJA ROBOT'
						     WHEN co.flg_robot = 2 THEN 'BANDEJA EECC' END bandejEecRobot,
						co.flg_robot,
                        co.codigo_cluster AS codigo,
                        co.id,
                        CASE WHEN co.estado  = 0 THEN 'PDT COTIZACION'
                             WHEN co.estado  = 1 THEN 'PDT APROBACION'
                             WHEN co.estado  = 2 THEN 'APROBADO'
                              WHEN co.estado = 3 THEN 'RECHAZADO'
                             WHEN co.estado  = 4 THEN 'PDT CONFIRMACION' END estadoDesc, 
                        s.subProyectoDesc,
                        co.sisego,
                        co.cliente,
                        co.descripcion,
                        co.acceso_cliente,
                        co.tendido_externo,
                        co.tipo_cliente,
                        co.nro_pisos,
                        co.departamento,
                        co.provincia,
                        UPPER(co.distrito) as distrito,
                        co.direccion,
                        co.piso,
                        co.interior,
                        co.latitud,
                        co.longitud,
                        co.nombre_estudio,
                        co.idSubProyecto,
                        co.estado,
                        co.fecha_registro,
                        co.fecha_envio_cotizacion,
                        (SELECT UPPER(nombre) 
                           FROM usuario
                          WHERE id_usuario = co.usuario_envio_cotizacion)usuarioEnviaCotizacion,
                        (SELECT UPPER(nombre) 
                           FROM usuario
                          WHERE id_usuario = cv.id_usuario_valida)usuarioConfirmaRechaz,
                        ROUND(COALESCE(co.costo_materiales, 0)+ COALESCE(co.costo_mat_edif, 0), 2) as costo_materiales,
                        ROUND(COALESCE(co.costo_mano_obra, 0)+ COALESCE(co.costo_mo_edif, 0) + COALESCE(co.costo_oc, 0) + COALESCE(co.costo_oc_edif, 0), 2) costo_mano_obra,
                        ce.jefatura,
                        e.empresaColabDesc,
                        ce.codigo AS codigoMdf,
                        co.flg_principal,
                        CASE WHEN co.flg_lan_to_lan = 1 THEN co.nombre_estudio
						     WHEN co.flg_principal  = 0 THEN 'PRINCIPAL'
						     WHEN co.flg_principal  = 1 THEN 'RESPALDO' END AS tipoSisego,
                        cv.fecha_validacion,
                        co.clasificacion,
                        cv.observacion,
                        CASE WHEN co.flg_rech_conf_ban_conf = 1 THEN 'APROBADO EN LA BANDEJA CONFIRMACIÓN'
                             WHEN co.flg_rech_conf_ban_conf = 2 THEN 'RECHAZADO EN LA BANDEJA CONFIRMACIÓN' END AS statusBandjConfirmacion
                   FROM (planobra_cluster co,
                        subproyecto s,
                        pqt_central ce,
                        empresacolab e)
              LEFT JOIN cotizacion_validar cv 
                     ON (cv.codigo_cluster = co.codigo_cluster AND cv.flg_validacion = 2 AND cv.fecha_validacion = ((SELECT MAX(fecha_validacion)  fecha_validacion
                         																							FROM cotizacion_validar 
                                                                                                                  WHERE codigo_cluster = cv.codigo_cluster LIMIT 1) ))
                  WHERE s.idSubProyecto   = co.idSubProyecto
                    AND e.idEmpresaColab  = co.idEmpresaColab
                    AND ce.idCentral      = co.idCentral
                    AND co.idSubProyecto  = COALESCE(?, co.idSubProyecto)
                    AND co.codigo_cluster = COALESCE(?, co.codigo_cluster)
                    AND co.estado         = COALESCE(?, co.estado)
                    AND ce.idJefatura     = COALESCE(?, ce.idJefatura)
                    AND co.idEmpresaColab = COALESCE(?, co.idEmpresaColab)
                    AND CASE WHEN ? IS NOT NULL THEN co.flg_rech_conf_ban_conf = ?
                             ELSE TRUE END
                    AND co.estado IN (0,1,4)
                    AND co.flg_tipo = 2
					AND co.flg_paquetizado = 2
					-- AND co.fecha_registro < '2020-01-24'
                    AND CASE WHEN ".$ideecc." = 0 OR ".$ideecc." = 6 THEN true
                             ELSE co.idEmpresaColab = ".$ideecc." AND co.flg_robot = 2 END
                UNION ALL

                SELECT  CASE WHEN co.flg_robot = 1 THEN 'BANDEJA ROBOT'
						     WHEN co.flg_robot = 2 THEN 'BANDEJA EECC' END bandejEecRobot,
						co.flg_robot,
				        co.codigo_cluster AS codigo,
                        co.id,
                        CASE WHEN co.estado  = 0 THEN 'PDT COTIZACION'
                             WHEN co.estado  = 1 THEN 'PDT APROBACION'
                             WHEN co.estado  = 2 THEN 'APROBADO'
                              WHEN co.estado = 3 THEN 'RECHAZADO'
                             WHEN co.estado  = 4 THEN 'PDT CONFIRMACION' END estadoDesc, 
                        s.subProyectoDesc,
                        co.sisego,
                        co.cliente,
                        co.descripcion,
                        co.acceso_cliente,
                        co.tendido_externo,
                        co.tipo_cliente,
                        co.nro_pisos,
                        co.departamento,
                        co.provincia,
                        UPPER(co.distrito) as distrito,
                        co.direccion,
                        co.piso,
                        co.interior,
                        co.latitud,
                        co.longitud,
                        co.nombre_estudio,
                        co.idSubProyecto,
                        co.estado,
                        co.fecha_registro,
                        co.fecha_envio_cotizacion,
                        (SELECT UPPER(nombre) 
                           FROM usuario
                          WHERE id_usuario = co.usuario_envio_cotizacion)usuarioEnviaCotizacion,
                        (SELECT UPPER(nombre) 
                           FROM usuario
                          WHERE id_usuario = cv.id_usuario_valida)usuarioConfirmaRechaz,
                        ROUND(COALESCE(co.costo_materiales, 0)+ COALESCE(co.costo_mat_edif, 0), 2) as costo_materiales,
                        ROUND(COALESCE(co.costo_mano_obra, 0)+ COALESCE(co.costo_mo_edif, 0) + COALESCE(co.costo_oc, 0) + COALESCE(co.costo_oc_edif, 0), 2) costo_mano_obra,
                        NULL,
                        NULL,
                        NULL,
                        co.flg_principal,
                        CASE WHEN co.flg_principal = 0 THEN 'PRINCIPAL'
                             ELSE 'RESPALDO' END AS tipoSisego,
                        cv.fecha_validacion,
                        co.clasificacion,
                        cv.observacion,
                        CASE WHEN co.flg_rech_conf_ban_conf = 1 THEN 'APROBADO EN LA BANDEJA CONFIRMACIÓN'
                             WHEN co.flg_rech_conf_ban_conf = 2 THEN 'RECHAZADO EN LA BANDEJA CONFIRMACIÓN' END AS statusBandjConfirmacion
                 FROM (planobra_cluster co,
                       subproyecto s)
            LEFT JOIN cotizacion_validar cv 
            	   ON (cv.codigo_cluster = co.codigo_cluster AND cv.flg_validacion = 2 AND cv.fecha_validacion = ((SELECT MAX(fecha_validacion)  fecha_validacion
                         																							FROM cotizacion_validar 
                                                                                                                  WHERE codigo_cluster = cv.codigo_cluster LIMIT 1) ))
            	WHERE idCentral IS NULL
				  -- AND co.fecha_registro < '2020-01-24'
            	  AND co.estado IN (0,1,4)
            	  AND co.codigo_cluster = COALESCE(?, co.codigo_cluster)
                  AND co.idSubProyecto = s.idSubProyecto
                  AND CASE WHEN ".$ideecc." = 0 OR ".$ideecc." = 6 THEN true
                           ELSE false END"; 
	    $result = $this->db->query($sql, array($idSubProyecto, $codigo, $estado, $idJefatura, $idEmpresaColab, $flgBandConf, $flgBandConf,$idSubProyecto, 
		                                       $codigo, $estado, $idJefatura, $idEmpresaColab, $flgBandConf, $flgBandConf, $codigo));
		return $result->result_array();
	}
	
	function   getHijosClusterByItemplan($codCluster){
	    $Query = ' SELECT * FROM planobra_cluster_hijos where codigo_cluster = ?';
	    $result = $this->db->query($Query,array($codCluster));
	    return $result;
	}
	
function updateClusterPadre($codigo_cluster, $dataCluster){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->trans_begin();
            $this->db->where('codigo_cluster', $codigo_cluster);
			$this->db->update('planobra_cluster', $dataCluster);			
            if($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                throw new Exception('Error al modificar el updateClusterPadre');
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

    function updateEmpresaColab($codigo, $idCentral) {
        $this->db->where('codigo_cluster', $codigo);
        $this->db->update('planobra_cluster', array('idCentral' => $idCentral));

        if($this->db->trans_status() === FALSE) {
            return array('error' => EXIT_ERROR, 'msj' => 'error al actualizar la EECC.');
        } else {
            return array('error' => EXIT_SUCCESS);
        }
    }
    
    function   getEstadoClusterByCod($codigo){
        $Query = "SELECT estado FROM  planobra_cluster WHERE codigo_cluster  = ? LIMIT 1";
        $result = $this->db->query($Query,array($codigo));
        if($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }
	
	function updateFlgRobot($codigo_coti) {
		$this->db->where('codigo_cluster', $codigo_coti);
		$this->db->update('planobra_cluster', array('flg_robot' => 2));
		_log($this->db->last_query());
		if($this->db->affected_rows() != 1) {
            return array('error' => EXIT_ERROR, 'msj' => 'error al cambiar la cotizacion.');
        } else {
            return array('error' => EXIT_SUCCESS);
        }
	}
	
}