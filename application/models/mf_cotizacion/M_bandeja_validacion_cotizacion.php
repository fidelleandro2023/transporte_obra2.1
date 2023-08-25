<?php
class M_bandeja_validacion_cotizacion extends CI_Model {
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
    }

    function getBandejaDataValidacion($idSubProyecto, $codigo, $idJefatura, $idEmpresaColab) {
        $sql = " SELECT DISTINCT
                        cov.codigo_cluster, 
                        cov.fecha_registro,
                        pc.sisego,
						ROUND( COALESCE(costo_mano_obra, 0)+
							   COALESCE(costo_expe_seia_cira_pam, 0)+
							   COALESCE(costo_adicional_rural, 0)+
							   COALESCE(costo_oc, 0), 2)costo_mano_obra_sisego,	  
						costo_materiales,	
					    ROUND( COALESCE(costo_mano_obra, 0)+
							   COALESCE(costo_diseno, 0)+
							   COALESCE(costo_expe_seia_cira_pam, 0)+
							   COALESCE(costo_adicional_rural, 0)+
							   COALESCE(costo_oc, 0), 2)costoTotalMo,
                        pc.costo_total,
                        CASE WHEN pc.estado = 0 THEN 'PDT COTIZACION'
                             WHEN pc.estado = 1 THEN 'PDT APROBACION'
                             WHEN pc.estado = 2 THEN 'APROBADO'
                             WHEN pc.estado = 3 THEN 'RECHAZADO'
                             WHEN pc.estado = 4 THEN 'PDT CONFIRMACION' END estadoDesc,
                        e.empresaColabDesc,
                        ce.codigo AS codigoMdf,
                        ce.jefatura,
                        s.subProyectoDesc,
                        pc.duracion,
                        pc.costo_diseno,
                        CONCAT(ce.codigo,'-',ce.tipoCentralDesc) AS nodo,
                        pc.id_tipo_diseno,
                        CASE WHEN pc.estado = 4 THEN 0
                             ELSE 1	END flg_validacion,
                        pc.comentario,
						CASE WHEN pc.tipo = 'EBC' THEN pc.facilidades_de_red 
						     ELSE NULL END cod_ebc
                   FROM cotizacion_validar cov,
                        planobra_cluster   pc,
                        subproyecto s,
                        pqt_central ce,
                        empresacolab e
                  WHERE cov.codigo_cluster = pc.codigo_cluster
                    AND s.idSubProyecto    = pc.idSubProyecto
                    AND e.idEmpresaColab   = ce.idEmpresaColab
                    AND ce.idCentral       = pc.idCentral
                    -- AND cov.flg_validacion NOT IN (1,2)
                    AND pc.estado = 4
                    AND pc.idSubProyecto   = COALESCE(?, pc.idSubProyecto)
                    AND pc.codigo_cluster  = COALESCE(?, pc.codigo_cluster)
                    AND ce.idJefatura      = COALESCE(?, ce.idJefatura)
                    AND ce.idEmpresaColab  = COALESCE(?, ce.idEmpresaColab)";
        $result = $this->db->query($sql, array($idSubProyecto, $codigo, $idJefatura, $idEmpresaColab));
        return $result->result_array();          
    }

    function updateValid($codigoCotizacion, $arrayData) {
        $this->db->where('codigo_cluster', $codigoCotizacion);
        $this->db->where('flg_validacion', 0);
        $this->db->update('cotizacion_validar', $arrayData);

        if($this->db->affected_rows() != 1) {
            return array('error' => EXIT_ERROR, 'error' => 'error al actualizar validaci&oacute;n.');
        } else {
            return array('error' => EXIT_SUCCESS);
        }
    }
}