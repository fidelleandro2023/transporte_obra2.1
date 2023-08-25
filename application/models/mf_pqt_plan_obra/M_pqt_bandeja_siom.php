<?php
class M_pqt_bandeja_siom extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
    }

    /**21.06.2109 se agrego left join a itemplanestacionavance para obtener porcentaje**/
    function getBandejaSiom($itemplan) {
        $idEECC  = $this->session->userdata("eeccSession");
        $Query = "  SELECT 
                        po.itemplan, sp.subProyectoDesc, so.ptr, e.idEstacion, e.estacionDesc, ep.estadoPlanDesc, so.fechaRegistro, c.codigo, 
                        concat(u.nombres,' ',u.ape_paterno,' ',u.ape_materno) as usua_registro, 
                        c.jefatura, ec.empresaColabDesc, so.codigoSiom, so.ultimo_estado, so.fecha_ultimo_estado, so.id_siom_obra, iea.porcentaje
                    FROM planobra po, subproyecto sp, estadoplan ep, estacion e, pqt_central c, empresacolab ec, siom_obra so 
                    LEFT JOIN usuario u ON so.idUsuarioRegistro = u.id_usuario 
                    LEFT JOIN itemplanestacionavance iea ON iea.itemplan = so.itemplan AND iea.idEstacion = so.idEstacion
                    WHERE so.itemplan = po.itemplan
                    AND po.idSubProyecto = sp.idSubProyecto
                    AND po.idCentralPqt = c.idCentral
                    AND po.idEstadoPlan != ".ID_ESTADO_CANCELADO."
                    AND (CASE WHEN sp.idTipoSubProyecto = 2 THEN c.idEmpresaColabCV = ec.idEmpresaColab
                    	      ELSE c.idEmpresaColab = ec.idEmpresaColab END)
                    AND	so.idEstacion = e.idEstacion
                    AND po.idEstadoPlan = ep.idEstadoPlan";
        
        if($idEECC == ID_EECC_QUANTA || $idEECC == ID_EECC_CAMPERU){
            $Query .= " AND  c.idEmpresaColabCV = ".$idEECC." AND sp.idTipoSubProyecto = 2 ";
        }else if($idEECC != ''  && $idEECC != '0' && $idEECC != '6'){
            $Query .= " AND  c.idEmpresaColab = ".$idEECC." AND sp.idTipoSubProyecto <> 2 ";
        }
        
        $Query .= ' AND po.itemplan  = ?
                    AND so.estado is null';
        $result = $this->db->query($Query, array($itemplan));
        //log_message('error', $this->db->last_query());
        return $result->result();           
    }

}