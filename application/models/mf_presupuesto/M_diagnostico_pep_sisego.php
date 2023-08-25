<?php
class M_diagnostico_pep_sisego extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
    }

    function getBandejaPepSisego($pep1) {
        $Query = "select sp.subProyectoDesc, po.itemplan, po.indicador, e.empresaColabDesc, ep.estadoPlanDesc, f.faseDesc, 
                    FORMAT(po.costo_unitario_mo,2) as costo_unitario_mo, 
                    FORMAT(po.costo_unitario_mat,2) as   costo_unitario_mat,
                    FORMAT((po.costo_unitario_mo + po.costo_unitario_mat),2) as total,
                sp2.pep2, sp2.grafo,po.solicitud_oc, 
				(CASE WHEN solicitud_oc is not null then 'CON PRESUP'  
					  WHEN po.costo_unitario_mo is null then 'SIN COTIZACION'
					  ELSE 'SIN PRESUP' END) as situacion,
				 (CASE WHEN sol.estado = 2 THEN 'CON OC' ELSE 'SIN OC' END) as con_oc
                 , (CASE WHEN (SELECT count(1) FROM planobra_po ppo where ppo.itemplan = po.itemplan and ppo.flg_tipo_area = 1 AND ppo.estado_po in (3,4,5,6)) > 0 THEN 'CON VR' ELSE 'SIN VR' END) as has_vr_aprob
                , (CASE WHEN (SELECT count(1) FROM pre_diseno pd where pd.itemplan = po.itemplan and pd.idEstacion = 5 and pd.fecha_ejecucion is not null) > 0 THEN 'SI' ELSE 'NO' END) as ejec_diseno
                from sisego_pep2_grafo sp2, subproyecto sp, fase f,estadoplan ep, empresacolab e, planobra po
                LEFT JOIN solicitud_orden_compra sol ON po.solicitud_oc = sol.codigo_solicitud
                where sp2.sisego = po.indicador 
                and po.idSubProyecto = sp.idsubProyecto
                and po.idFase = f.idFase
                and po.idEstadoPlan = ep.idEstadoPlan
                and po.idEmpresaColab = e.idEmpresaColab
				AND sp2.estado in (0,1,2)
                and substring(sp2.pep2,1,20) = ?
                order by po.solicitud_oc desc";
        $result = $this->db->query($Query, array($pep1));
        #log_message('error', $this->db->last_query());
        return $result->result();           
    }    
   
	 public function getPepPresupuestoProy2($pep1)
    {
        $sql = "SELECT format(monto_temporal,2) as monto_disponible FROM sap_detalle WHERE pep1 = ?";
        $result = $this->db->query($sql,array($pep1));
        if($result->row() != null) {
            return $result->row_array()['monto_disponible'];
        } else {
            return null;
        }
    }
}