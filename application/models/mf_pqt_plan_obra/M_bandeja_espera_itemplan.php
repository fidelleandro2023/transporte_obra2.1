<?php
class M_bandeja_espera_itemplan extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
    }

    function getObrasToBandejaEspera() {
        $Query = "SELECT 
                    po.itemplan,po.indicador, p.proyectoDesc, sp.subProyectoDesc, ep.estadoPlanDesc, c.jefatura, e.empresaColabDesc, f.faseDesc
                FROM
                    planobra po, estadoplan ep, pqt_central c, empresacolab e, subproyecto sp, proyecto p, fase f
                WHERE
                    po.idEstadoPlan = ep.idEstadoPlan
                AND	po.idSubProyecto = sp.idSubProyecto
                AND	sp.idProyecto = p.idProyecto
                AND	po.idFase = f.idFase
                AND	po.idEmpresaColab = e.idEmpresaColab
                AND po.idCentralPqt = c.idCentral
                AND po.idEmpresaColab = 12
				AND (po.solicitud_oc is null OR po.estado_sol_oc not in ('ATENDIDO','PENDIENTE'))";
                        $result = $this->db->query($Query, array());
        #log_message('error', $this->db->last_query());
        return $result->result();           
    }    
   
}