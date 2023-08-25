<?php
class M_cotizacion_alcance_robot extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
    }

    function getDataTablaAlcance() {
        $sql ="SELECT id,
                      UPPER(clasificacion)clasificacion,
                      flg_cotizacion_automatica,
                      flg_envio_sisego,
                      flg_crea_itemplan
                 FROM cotizacion_alcance_robot";
        $result = $this->db->query($sql);
        return $result->result_array();
    }

    function getDataTablaCostoxIntervalo() {
        $sql = "SELECT id,
                       met_in,
                       met_fin,
                       mo_total,
                       mat_total,
                       diseno_total,
					   eia_total,
					   inc_total,
                       total
                  FROM cotizacion_matriz_costos";
        $result = $this->db->query($sql);
        return $result->result_array();
    }
	
	function getDataPqtCentralByCodigo($codigo) {
        $sql = "SELECT codigo,
                       longitud,
                       latitud,
                       jefatura 
                  FROM pqt_central 
                 WHERE codigo = ?
                   AND idTipoCentral = 1";
        $result = $this->db->query($sql, array($codigo));
        return $result->row_array();         
    }
	
	function getCostoPaquetizado($idSubProyecto, $idEmpresaColab, $idEstacion, $jefatura){
		$sql = " SELECT pqe.id_tipo_partida,
						ptp.descripcion as tipoPreciario,
						pqe.descripcion as partidaPqt,
						pbs.baremo,
						#po.cantFactorPlanificado,
						1,
						pre.costo,
						#(pbs.baremo * po.cantFactorPlanificado * pre.costo) AS total,
						(pbs.baremo * 1 * pre.costo) AS total,
						FORMAT((pbs.baremo * 1 * pre.costo),2) as form,
						ROUND((pbs.baremo * 1 * pre.costo),2) as round,
						pqe.idActividad,
						CASE WHEN pre.tipoJefatura = 1 THEN 'LIMA'
                             WHEN pre.tipoJefatura = 2 THEN 'PROVINCIA' END tipoJefatura
                   FROM pqt_baremo_x_subpro_x_partida_mo pbs,
						pqt_partidas_paquetizadas_x_estacion pqe,
						pqt_tipo_preciario ptp,
						pqt_preciario pre
				  WHERE pbs.id_pqt_partida_mo_x_estacion    = pqe.id_tipo_partida
					AND pqe.id_pqt_tipo_preciario           = ptp.id
					AND ptp.id              = pre.idTipoPreciario
					AND pqe.idEstacion      = COALESCE(?, pqe.idEstacion) 
					AND pbs.idSubProyecto   = COALESCE(?, pbs.idSubProyecto)
					AND pre.idEmpresaColab  = COALESCE(?, pre.idEmpresaColab) 
					AND CASE WHEN ? = 'LIMA' THEN pre.tipoJefatura = 1
                             ELSE pre.tipoJefatura = 2 END
			  UNION ALL 			  
				  SELECT NULL,
						t.tipoPreciario,
						t.descPartida,
						t.baremo, 1, t.costo,
						(t.baremo * t.cantidad* t.costo) AS total,
						FORMAT((t.baremo * t.cantidad * t.costo),2) as form,
						ROUND((t.baremo * t.cantidad * t.costo),2) as round,
						t.idActividad,
						t.tipoJefatura
					FROM (
						  SELECT ptp.descripcion as tipoPreciario,
								 pa.descripcion as descPartida,
                                 pre.costo,
								 pa.baremo,
									pa.idActividad,
									CASE WHEN pre.tipoJefatura = 1 THEN 'LIMA'
										 WHEN pre.tipoJefatura = 2 THEN 'PROVINCIA' END tipoJefatura,
								  ROUND(167.4/(baremo*costo),2) AS cantidad
							FROM
								partidas pa,
								pqt_tipo_preciario ptp,
								pqt_preciario pre
							WHERE pa.idPrecioDiseno = ptp.id
							  AND ptp.id = pre.idTipoPreciario
							  AND pa.codigo = '69901-2'
							  AND pre.idEmpresaColab  = COALESCE(?, pre.idEmpresaColab) 
							  AND CASE WHEN ? = 'LIMA' THEN pre.tipoJefatura = 1
									   ELSE pre.tipoJefatura = 2 END
					)t";
        $result = $this->db->query($sql, array($idEstacion, $idSubProyecto, $idEmpresaColab, $jefatura, $idEmpresaColab, $jefatura));
        return $result->result_array();  
	}
	
	function getCostoTotalPaquetizado($idSubProyecto, $idEmpresaColab, $idEstacion, $jefatura){
		$sql = " SELECT SUM(tt.total_mo_pqt)total_mo_pqt
		           FROM (
					 SELECT pqe.id_tipo_partida,
							ptp.descripcion as tipoPreciario,
							pqe.descripcion as partidaPqt,
							pbs.baremo,
							1,
							pre.costo,
							ROUND(SUM(pbs.baremo * 1 * pre.costo),0) AS total_mo_pqt,
							FORMAT((pbs.baremo * 1 * pre.costo),2) as form,
							ROUND((pbs.baremo * 1 * pre.costo),2) as round,
							pqe.idActividad,
							CASE WHEN pre.tipoJefatura = 1 THEN 'LIMA'
								 WHEN pre.tipoJefatura = 2 THEN 'PROVINCIA' END tipoJefatura
					   FROM pqt_baremo_x_subpro_x_partida_mo pbs,
							pqt_partidas_paquetizadas_x_estacion pqe,
							pqt_tipo_preciario ptp,
							pqt_preciario pre
					  WHERE pbs.id_pqt_partida_mo_x_estacion    = pqe.id_tipo_partida
						AND pqe.id_pqt_tipo_preciario           = ptp.id
						AND ptp.id              = pre.idTipoPreciario
						AND pqe.idEstacion      = COALESCE(?, pqe.idEstacion) 
						AND pbs.idSubProyecto   = COALESCE(?, pbs.idSubProyecto)
						AND pre.idEmpresaColab  = COALESCE(?, pre.idEmpresaColab)  
						AND CASE WHEN ? = 'LIMA' THEN pre.tipoJefatura    = 1
								 ELSE pre.tipoJefatura = 2 END
					UNION ALL
						SELECT NULL,
							t.tipoPreciario,
							t.descPartida,
							t.baremo, 
							1, 
							t.costo,
							ROUND(SUM(t.baremo *  t.cantidad * t.costo),0) AS total_mo_pqt,
							FORMAT((t.baremo * t.cantidad * t.costo),2) as form,
							ROUND((t.baremo * t.cantidad * t.costo),2) as round,
							t.idActividad,
							t.tipoJefatura
						FROM (
							  SELECT ptp.descripcion as tipoPreciario,
									 pa.descripcion as descPartida,
									 pre.costo,
									 pa.baremo,
										pa.idActividad,
										CASE WHEN pre.tipoJefatura = 1 THEN 'LIMA'
											 WHEN pre.tipoJefatura = 2 THEN 'PROVINCIA' END tipoJefatura,
									  ROUND(167.4/(baremo*costo),2) AS cantidad
								FROM
									partidas pa,
									pqt_tipo_preciario ptp,
									pqt_preciario pre
								WHERE pa.idPrecioDiseno = ptp.id
								  AND ptp.id = pre.idTipoPreciario
								  AND pa.codigo = '69901-2'
								  AND pre.idEmpresaColab  = COALESCE(?, pre.idEmpresaColab) 
								  AND CASE WHEN ? = 'LIMA' THEN pre.tipoJefatura = 1
										   ELSE pre.tipoJefatura = 2 END
						)t
					)tt";
        $result = $this->db->query($sql, array($idEstacion, $idSubProyecto, $idEmpresaColab, $jefatura, $idEmpresaColab, $jefatura));
        return $result->row_array();  
	}
}
