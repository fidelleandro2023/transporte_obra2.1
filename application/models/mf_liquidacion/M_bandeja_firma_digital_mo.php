<?php
class M_bandeja_firma_digital_mo extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
    }

    function getBandejaFirmaDigital($idEmpresaColab, $idJefatura, $fechaInicio, $fechaFin) {
        $sql = "SELECT DISTINCT 
                       ppo.itemplan,
                       ppo.codigo_po,
                       e.estacionDesc,
                       e.idEstacion,
                       ppo.fecha_validacion,
                       ce.jefatura,
                       em.empresaColabDesc
                  FROM planobra_po ppo,
                       estacion e,
                       planobra po,
                       central ce,
                       empresacolab em,
                       switch_firma_digital sw
                 WHERE ppo.estado_po     = 5
                   AND (ppo.flg_firma_dig = 0 OR ppo.flg_firma_dig IS NULL)
                   AND ppo.flg_tipo_area = 2
                   AND ce.idJefatura = sw.idJefatura
                   AND ppo.itemplan  = po.itemplan 
                   AND ce.idCentral  = po.idCentral
                   AND ppo.idEstacion = e.idEstacion
                   AND em.idEmpresaColab = ppo.id_eecc_reg
                   AND ppo.idEstacion <> 1
                   AND em.idEmpresaColab = COALESCE(?, em.idEmpresaColab)
                   AND ce.idJefatura     = COALESCE(?, ce.idJefatura)
                   HAVING CASE WHEN ? IS NOT NULL AND ? IS NOT NULL THEN DATE(fecha_validacion) BETWEEN ? AND ?
                               WHEN ? IS NULL AND ? IS NOT NULL THEN DATE(fecha_validacion) > ? 
                               WHEN ? IS NULL AND ? IS NULL THEN DATE(fecha_validacion) = DATE(fecha_validacion) END";
        $result = $this->db->query($sql, array($idEmpresaColab, $idJefatura, $fechaInicio, $fechaFin, $fechaInicio, $fechaFin, $fechaFin, $fechaInicio, $fechaInicio,
                                               $fechaInicio, $fechaFin));
        return $result->result_array();
    }

    function updateEstadoPO($arrayData) {
        $this->db->update_batch('planobra_po', $arrayData, 'codigo_po');

        if($this->db->affected_rows() < 0) {
            return array('error' => EXIT_ERROR, 'msj' => 'error al actualizar es estado PO');
        } else {
            return array('error' => EXIT_SUCCESS);
        }
    }

    function countUsuarioAprob($itemplan, $idUsuario) {
        $sql = "SELECT COUNT(1) count 
                  FROM switch_firma_digital sw,
                       planobra po,
                       central ce
                 WHERE po.idCentral = ce.idCentral
                   AND ce.idJefatura = sw.idJefatura
                   AND po.itemplan   = ?
                   AND sw.idUsuario  = ?";
        $result = $this->db->query($sql, array($itemplan, $idUsuario));
        return $result->row_array()['count'];           
    }
}