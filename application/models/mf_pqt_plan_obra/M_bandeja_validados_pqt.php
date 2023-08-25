<?php
class M_bandeja_validados_pqt extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
    }

    function getObrasToBandejaEspera() {
        $Query = "SELECT 	po.itemplan, p.proyectoDesc, sp.subProyectoDesc, ec.empresaColabDesc, po.indicador, f.faseDesc, ep.estadoPlanDesc, c.jefatura, sp2.pep2, FORMAT(pqa.costo_inicial,2) as costo_unitario_mo, FORMAT(sd.monto_inicial,2) as monto_inicial, FORMAT(sd.monto_temporal,2) as monto_temporal
					FROM 	pqt_solicitud_aprob_partidas_adicionales pqa, proyecto p, subproyecto sp, empresacolab ec, fase f, estadoplan ep, pqt_central c, planobra po
					LEFT JOIN sisego_pep2_grafo sp2	ON sp2.sisego = po.indicador AND sp2.estado in (0,1,2)
					LEFT JOIN sap_detalle sd ON substring(sp2.pep2,1,20) = sd.pep1
					where 	po.itemplan = pqa.itemplan
					and 	po.idSubProyecto = sp.idSubProyecto
					and 	sp.idProyecto = p.idProyecto
					and 	po.idFase = f.idFase
					and 	po.idEmpresaColab = ec.idEmpresaColab
					and 	po.idCentralPqt = c.idCentral
					and 	po.idEstadoPlan = ep.idEstadoPlan
					and 	po.orden_compra is null
					and 	pqa.estado = 2;";
                        $result = $this->db->query($Query, array());
        #log_message('error', $this->db->last_query());
        return $result->result();           
    }    
   
}