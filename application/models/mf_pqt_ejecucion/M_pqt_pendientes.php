<?php
class M_pqt_pendientes extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
    function getListaPendiente($itemPlan, $estadoPlan) {
        $ideecc  = $this->session->userdata("eeccSession");
        
        $sql ="SELECT p.idSubProyecto,s.idProyecto,f.faseDesc,
                        z.zonalDesc,
                        (CASE WHEN s.idProyecto = ".ID_PROYECTO_SISEGOS." THEN (SELECT grafo 
                                                                                  FROM sisego_pep2_grafo 
                                                                                 WHERE sisego = p.indicador LIMIT 1) 
                              ELSE null END) as grafo,
                        c.tipoCentralDesc,
                        s.idTipoPlanta, 
                        pro.ProyectoDesc,
                        s.subProyectoDesc,
                        e.empresaColabDesc,
                        (SELECT empresaColabDesc FROM empresacolab WHERE idEmpresaColab = c.idEmpresaColabFuente) AS empresaColabDescFuente,
                        p.itemPlan,
                        p.nombreProyecto,
                        p.indicador,
                        p.fechaPrevEjec,
                        p.fechaEjecucion,
                        DATE(p.fechaPreLiquidacion)AS fechaPreLiquidacion,
                        p.fechaInicio,
                        p.idEmpresaColab,
                        p.idEstadoPlan,
                        UPPER(p.operador) AS operador,
                        (CASE WHEN  s.idTipoSubProyecto = 2 THEN (SELECT empresaColabDesc 
                                                                    FROM empresacolab 
                                                                   WHERE idEmpresaColab = c.idEmpresaColabCV) 
                                ELSE e.empresaColabDesc END) as empresaColabDesc,
                        (SELECT estadoPlanDesc 
                        FROM estadoplan e
                        WHERE e.idEstadoPlan = p.idEstadoPlan)estadoPlanDesc,
                         (CASE WHEN c.jefatura = 'LIMA' 
                              THEN (SELECT COUNT(*) AS cantidad 
                                      FROM itemplan_estacion_licencia_det 
									 WHERE itemplan = p.itemplan
                                       AND ruta_pdf IS NOT NULL)
							  ELSE (SELECT COUNT(*) AS cantidad 
						              FROM acotacion a,
								           itemplan_estacion_licencia_det ie
									 WHERE ie.itemplan = p.itemplan
                                       AND ie.iditemplan_estacion_licencia_det = a.iditemplan_estacion_licencia_det
                                       AND a.ruta_foto IS NOT NULL) END) AS flgLicencia,
                         c.jefatura
                FROM planobra p 
                left join subproyecto s  on s.idSubProyecto  = p.idSubProyecto
                left join proyecto pro   on pro.idProyecto   = s.idProyecto
                left join pqt_central c      on p.idCentralPqt      = c.idCentral
                left join empresacolab e on e.idEmpresaColab = c.idEmpresaColab
                left join zonal z        on z.idZonal=c.idZonal
                left join fase f         on f.idFase = p.idFase
                WHERE p.itemplan   = COALESCE(?,p.itemplan)
                    AND p.indicador  = p.indicador
                    AND s.idProyecto    =  s.idProyecto
                    /*AND p.idEstadoPlan = ".$estadoPlan."*/
                    AND p.idSubProyecto = p.idSubProyecto
                    AND CASE WHEN ".$ideecc." = 0 OR ".$ideecc." = 6 THEN c.idEmpresaColab = c.idEmpresaColab
                            WHEN ".$ideecc." IN (SELECT idSubProyecto 
                                                    FROM subproyecto 
                                                   WHERE idProyecto = 5)
                            THEN c.idEmpresaColabCV =  ".$ideecc."               
                             ELSE c.idEmpresaColab = ".$ideecc." END
                    AND s.idTipoPlanta = s.idTipoPlanta
                    AND p.idFase = p.idFase
                    ORDER BY p.fechaEjecucion desc";
        
        $result = $this->db->query($sql, array($itemPlan));
        //log_message('error', 'M_pqt_pendientes.getListaPendiente --> '.$this->db->last_query());
        return $result;  
    }
}