<?php
class M_bandeja_reporte extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}

function getDataPlantaInterna($idEstadoPlan, $tipoPlanta, $fechaIn, $fechaFin, $idEecc) {
    $ideecc  = $this->session->userdata("eeccSession");
        $sql = "SELECT paz.itemplan, 
						po.nombreProyecto,
						p.proyectoDesc,
                        ai.descripcion as partida,
						s.idSubProyecto,
						s.subProyectoDesc,
						po.fechaPreLiquidacion,
						po.fechaEjecucion,
						c.idZonal,
						z.zonalDesc,
						c.jefatura,
                        paz.baremo,
						e.empresaColabDesc,
                        ai.costo_material as costo_kit,
						paz.ptr, 
						c.codigo as mdf,
						paz.precio,
                        CASE WHEN paz.cantidad_final IS NULL THEN paz.cantidad 
                             ELSE paz.cantidad_final END cantidad,
						FORMAT(SUM(paz.costo_mo),2)costo_mo, 
						FORMAT(SUM(paz.costo_mat),2)costo_mat, 
						FORMAT(SUM(paz.total),2)total
				 FROM   ptr_x_actividades_x_zonal paz,
                        partidas ai, 
						planobra po,
						subproyecto s,
						proyecto p,
						central c,
						empresacolab e,
						zonal z,
						ptr_planta_interna ppi
				WHERE paz.itemplan = po.itemplan
				    AND po.flg_transporte IS NULL
                    AND po.idEstadoPlan = ".$idEstadoPlan."
                    AND ai.idActividad  = paz.id_actividad
					AND po.idSubProyecto = s.idSubProyecto
					AND p.idProyecto = s.idProyecto
					AND s.idTipoPlanta = ".$tipoPlanta."
					AND po.idCentral = c.idCentral
					AND ai.flg_tipo     = 1
					AND c.idZonal    = z.idZonal
                    AND e.idEmpresaColab = c.idEmpresaColab
                    AND CASE WHEN ".$ideecc." = 0 OR ".$ideecc." = 6 THEN e.idEmpresaColab = e.idEmpresaColab        
                             ELSE e.idEmpresaColab = ".$ideecc." END
					AND c.idEmpresaColab = COALESCE(?, c.idEmpresaColab)
					AND CASE WHEN ? IS NOT NULL AND ? IS NULL     THEN po.fechaEjecucion BETWEEN '".$fechaIn."' AND NOW() 
							 WHEN ? IS NOT NULL AND ? IS NOT NULL THEN po.fechaEjecucion BETWEEN '".$fechaIn."' AND '".$fechaFin."'
							 WHEN ? IS NULL     AND ? IS NOT NULL THEN po.fechaEjecucion <= '".$fechaFin."'
							 ELSE po.fechaEjecucion = po.fechaEjecucion END
					AND po.has_log_pi is null
					AND ppi.rangoPtr <> 6
                    AND ppi.ptr = paz.ptr	
					AND po.paquetizado_fg is null
					GROUP BY paz.id_ptr_x_actividades_x_zonal
					UNION ALL
					SELECT paz.itemplan, 
						po.nombreProyecto,
						p.proyectoDesc,
                        ai.descripcion as partida,
						s.idSubProyecto,
						s.subProyectoDesc,
						po.fechaPreLiquidacion,
						po.fechaEjecucion,
						c.idZonal,
						z.zonalDesc,
						c.jefatura,
                        paz.baremo,
						e.empresaColabDesc,
                        ai.costo_material as costo_kit,
						paz.ptr, 
						c.codigo as mdf,
						paz.precio,
                        CASE WHEN paz.cantidad_final IS NULL THEN paz.cantidad 
                             ELSE paz.cantidad_final END cantidad,
						FORMAT(SUM(paz.costo_mo),2)costo_mo, 
						FORMAT(SUM(paz.costo_mat),2)costo_mat, 
						FORMAT(SUM(paz.total),2)total
				 FROM   ptr_x_actividades_x_zonal paz,
                        partidas ai, 
						planobra po,
						subproyecto s,
						proyecto p,
						pqt_central c,
						empresacolab e,
						zonal z,
						ptr_planta_interna ppi
				WHERE paz.itemplan = po.itemplan
				    AND po.flg_transporte IS NULL
                    AND po.idEstadoPlan = ".$idEstadoPlan."
                    AND ai.idActividad  = paz.id_actividad
					AND po.idSubProyecto = s.idSubProyecto
					AND p.idProyecto = s.idProyecto
					AND s.idTipoPlanta = ".$tipoPlanta."
					AND po.idCentralPqt = c.idCentral
					AND ai.flg_tipo     = 1
					AND c.idZonal    = z.idZonal
                    AND e.idEmpresaColab = c.idEmpresaColab
                    AND CASE WHEN ".$ideecc." = 0 OR ".$ideecc." = 6 THEN e.idEmpresaColab = e.idEmpresaColab        
                             ELSE e.idEmpresaColab = ".$ideecc." END
					AND c.idEmpresaColab = COALESCE(?, c.idEmpresaColab)
					AND CASE WHEN ? IS NOT NULL AND ? IS NULL     THEN po.fechaEjecucion BETWEEN '".$fechaIn."' AND NOW() 
							 WHEN ? IS NOT NULL AND ? IS NOT NULL THEN po.fechaEjecucion BETWEEN '".$fechaIn."' AND '".$fechaFin."'
							 WHEN ? IS NULL     AND ? IS NOT NULL THEN po.fechaEjecucion <= '".$fechaFin."'
							 ELSE po.fechaEjecucion = po.fechaEjecucion END
					AND po.has_log_pi is null
					AND ppi.rangoPtr <> 6
                    AND ppi.ptr = paz.ptr	
					AND (po.paquetizado_fg  = 2 or po.paquetizado_fg  = 1)
					GROUP BY paz.id_ptr_x_actividades_x_zonal";
		$result = $this->db->query($sql, array($idEecc, $fechaIn, $fechaFin, $fechaIn, $fechaFin, $fechaIn, $fechaFin,$idEecc, $fechaIn, $fechaFin, $fechaIn, $fechaFin, $fechaIn, $fechaFin));
		return $result->result();						
    }
}