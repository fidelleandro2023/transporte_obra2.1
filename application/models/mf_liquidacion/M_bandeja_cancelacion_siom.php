<?php
class M_bandeja_cancelacion_siom extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
    }

    /**21.06.2109 se agrego left join a itemplanestacionavance para obtener porcentaje**/
    function getBandejaSiom($idSubProyecto=null, $idEmpresaColab=null, $jefatura=null, $noEnviado=null) {
        $idEECC  = $this->session->userdata("eeccSession");
        $Query = "  SELECT 
                        po.itemplan, sp.subProyectoDesc, so.ptr, e.idEstacion, e.estacionDesc, ep.estadoPlanDesc, so.fechaRegistro, c.codigo, 
                        concat(u.nombres,' ',u.ape_paterno,' ',u.ape_materno) as usua_registro, 
                        c.jefatura, ec.empresaColabDesc, so.codigoSiom, so.ultimo_estado, so.fecha_ultimo_estado, so.id_siom_obra, iea.porcentaje
                    FROM planobra po, subproyecto sp, estadoplan ep, estacion e, central c, empresacolab ec, siom_obra so 
                    LEFT JOIN usuario u ON so.idUsuarioRegistro = u.id_usuario 
                    LEFT JOIN itemplanestacionavance iea ON iea.itemplan = so.itemplan AND iea.idEstacion = so.idEstacion
                    WHERE so.itemplan = po.itemplan
                    AND po.idSubProyecto = sp.idSubProyecto
                    AND po.idCentral = c.idCentral
                    AND po.idEstadoPlan = ".ID_ESTADO_CANCELADO."
                    AND so.ultimo_estado != 'ANULADA'
                    AND (CASE WHEN po.idSubProyecto = 97 THEN c.idEmpresaColabCV = ec.idEmpresaColab
                    	      ELSE c.idEmpresaColab = ec.idEmpresaColab END)
                    AND	so.idEstacion = e.idEstacion
                    AND po.idEstadoPlan = ep.idEstadoPlan";
        
        if($idEECC == ID_EECC_QUANTA || $idEECC == ID_EECC_CAMPERU){
            $Query .= " AND  c.idEmpresaColabCV = ".$idEECC." AND po.idSubProyecto = 97 ";
        }else if($idEECC != ''  && $idEECC != '0' && $idEECC != '6'){
            $Query .= " AND  c.idEmpresaColab = ".$idEECC." AND po.idSubProyecto != 97 ";
        }
        
        $Query .= ' AND po.idSubProyecto  = COALESCE(?, po.idSubProyecto)
                    AND c.jefatura        = COALESCE(?, c.jefatura)
                    AND ec.idEmpresaColab  = COALESCE(?, ec.idEmpresaColab)
                    AND UPPER(so.ultimo_estado)  = COALESCE(UPPER(?), so.ultimo_estado)
                    AND so.estado is null';
        $result = $this->db->query($Query, array($idSubProyecto, $jefatura, $idEmpresaColab, $noEnviado));
        //log_message('error', $this->db->last_query());
        return $result->result();           
    }

}