<?php
class M_bandeja_certificacion_2 extends CI_Model{
    //http://www.codeigniter.com/userguide3/database/results.html
    function __construct(){
        parent::__construct();

    }

    function getDataPlantaInterna($idEstadoPlan, $tipoPlanta, $fechaIn, $fechaFin, $idEecc) {
        $sql = "SELECT paz.itemplan,
						po.nombreProyecto,
						p.proyectoDesc,
						s.idSubProyecto,
						c.codigo,
						(SELECT u.nombre
                           FROM log_planobra l,
                         	    usuario u
                          WHERE l.tipoPlanta = 2
                            AND l.actividad like 'ingresar%'
                            AND l.id_usuario = u.id_usuario
                            AND l.ptr IS NULL
                            AND l.itemplan = paz.itemplan
                          GROUP BY l.id_usuario)as usuarioRegis,
                        (SELECT u.nombre
                           FROM log_planobra l,
                         	    usuario u
                          WHERE l.actividad IN ('Terminar obra-Validado','Terminar obra')
                            AND l.id_usuario = u.id_usuario
                            AND l.ptr IS NULL
                            AND l.itemplan = paz.itemplan
                          GROUP BY l.id_usuario
                          limit 1)as usuarioTerm,
						s.subProyectoDesc,
						po.fechaPreLiquidacion,
						po.fechaEjecucion,
						c.idZonal,
						z.zonalDesc,
						e.empresaColabDesc,
						paz.ptr,
						FORMAT(SUM(paz.costo_mo),2)costo_mo,
						FORMAT(SUM(paz.costo_mat),2)costo_mat,
						FORMAT(SUM(paz.total),2)total,
                        po.pi_oc,
                        po.pi_nro_cert
				 FROM   ptr_x_actividades_x_zonal paz,
						planobra po,
						subproyecto s,
						proyecto p,
						central c,
						empresacolab e,
						zonal z
				WHERE paz.itemplan = po.itemplan
					AND po.idEstadoPlan in (".$idEstadoPlan.",5)
					AND po.idSubProyecto = s.idSubProyecto
					AND p.idProyecto = s.idProyecto
					AND s.idTipoPlanta = ".$tipoPlanta."
					AND po.idCentral = c.idCentral
					AND c.idZonal    = z.idZonal
					AND e.idEmpresaColab = c.idEmpresaColab
					AND po.pi_oc is not null
					AND po.flg_transporte IS NULL
					AND (po.paquetizado_fg is null or po.paquetizado_fg  = 1)
					AND c.idEmpresaColab = COALESCE(?, c.idEmpresaColab)
					AND CASE WHEN ? IS NOT NULL AND ? IS NULL     THEN po.fechaEjecucion BETWEEN '".$fechaIn."' AND NOW()
							 WHEN ? IS NOT NULL AND ? IS NOT NULL THEN po.fechaEjecucion BETWEEN '".$fechaIn."' AND '".$fechaFin."'
							 WHEN ? IS NULL     AND ? IS NOT NULL THEN po.fechaEjecucion <= '".$fechaFin."'
							 ELSE po.fechaEjecucion = po.fechaEjecucion END
					GROUP BY po.itemplan,
							po.idSubProyecto
					UNION ALL
					SELECT paz.itemplan,
						po.nombreProyecto,
						p.proyectoDesc,
						s.idSubProyecto,
						c.codigo,
						(SELECT u.nombre
                           FROM log_planobra l,
                         	    usuario u
                          WHERE l.tipoPlanta = 2
                            AND l.actividad like 'ingresar%'
                            AND l.id_usuario = u.id_usuario
                            AND l.ptr IS NULL
                            AND l.itemplan = paz.itemplan
                          GROUP BY l.id_usuario)as usuarioRegis,
                        (SELECT u.nombre
                           FROM log_planobra l,
                         	    usuario u
                          WHERE l.actividad IN ('Terminar obra-Validado','Terminar obra')
                            AND l.id_usuario = u.id_usuario
                            AND l.ptr IS NULL
                            AND l.itemplan = paz.itemplan
                          GROUP BY l.id_usuario
                          limit 1)as usuarioTerm,
						s.subProyectoDesc,
						po.fechaPreLiquidacion,
						po.fechaEjecucion,
						c.idZonal,
						z.zonalDesc,
						e.empresaColabDesc,
						paz.ptr,
						FORMAT(SUM(paz.costo_mo),2)costo_mo,
						FORMAT(SUM(paz.costo_mat),2)costo_mat,
						FORMAT(SUM(paz.total),2)total,
                        po.pi_oc,
                        po.pi_nro_cert
				 FROM   ptr_x_actividades_x_zonal paz,
						planobra po,
						subproyecto s,
						proyecto p,
						pqt_central c,
						empresacolab e,
						zonal z
				WHERE paz.itemplan = po.itemplan
					AND po.idEstadoPlan in (".$idEstadoPlan.",5)
					AND po.idSubProyecto = s.idSubProyecto
					AND p.idProyecto = s.idProyecto
					AND s.idTipoPlanta = ".$tipoPlanta."
					AND po.idCentralPqt = c.idCentral
					AND c.idZonal    = z.idZonal
					AND po.flg_transporte IS NULL
					AND e.idEmpresaColab = c.idEmpresaColab
					AND po.pi_oc is not null
					AND po.paquetizado_fg  = 2	
					AND c.idEmpresaColab = COALESCE(?, c.idEmpresaColab)
					AND CASE WHEN ? IS NOT NULL AND ? IS NULL     THEN po.fechaEjecucion BETWEEN '".$fechaIn."' AND NOW()
							 WHEN ? IS NOT NULL AND ? IS NOT NULL THEN po.fechaEjecucion BETWEEN '".$fechaIn."' AND '".$fechaFin."'
							 WHEN ? IS NULL     AND ? IS NOT NULL THEN po.fechaEjecucion <= '".$fechaFin."'
							 ELSE po.fechaEjecucion = po.fechaEjecucion END
					GROUP BY po.itemplan,
							po.idSubProyecto";
        $result = $this->db->query($sql, array($idEecc, $fechaIn, $fechaFin, $fechaIn, $fechaFin, $fechaIn, $fechaFin, $idEecc, $fechaIn, $fechaFin, $fechaIn, $fechaFin, $fechaIn, $fechaFin));
        return $result->result();
    }
    
    
    function getPartidasByItemplan($itemplan) {
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
						zonal z
				WHERE paz.itemplan = po.itemplan
                    AND ai.idActividad  = paz.id_actividad
					AND po.idSubProyecto = s.idSubProyecto
					AND p.idProyecto = s.idProyecto
					AND po.idCentral = c.idCentral
					AND ai.flg_tipo     = 1
					AND c.idZonal    = z.idZonal
                    AND e.idEmpresaColab = c.idEmpresaColab
                    AND po.itemplan = ?
					AND po.flg_transporte IS NULL
					GROUP BY paz.id_ptr_x_actividades_x_zonal";
        $result = $this->db->query($sql, array($itemplan));
        return $result->result();
    }
}