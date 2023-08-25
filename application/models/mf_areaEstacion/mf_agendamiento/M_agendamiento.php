<?php
class M_agendamiento extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
    }

	function getBandaHorariaByItemplan($itemplan, $fechaAgendamiento, $arrayEstado) {
        $sql = "SELECT *
                  FROM (  
                        SELECT ba.idBandaHoraria,
                            CONCAT('BH',' ',ba.horaInicio, ' - ', ba.horaFin)horaInFin,
                            cu.cantidad,
                            cu.idCuotaAgenda,
                            c.jefatura,
                            c.idEmpresaColab,
                            e.estadoPlanDesc,
                            (SELECT e.empresaColabDesc 
                                FROM empresacolab e
                                WHERE e.idEmpresaColab = c.idEmpresaColab)empresaColabDesc,
                            (SELECT COUNT(1) 
                                FROM agendamiento a,
                                     cuotas_agenda c 
                                WHERE DATE(a.fecha_agendamiento) = COALESCE(?, DATE(a.fecha_agendamiento))
                                AND a.idCuotaAgenda = c.idCuotaAgenda 
                                AND c.idBandaHoraria = cu.idBandaHoraria
                                GROUP BY c.idBandaHoraria
                            )countAgenda,
                            (SELECT COUNT(1) 
                               FROM agendamiento a,
                                    cuotas_agenda c 
                              WHERE a.itemplan = '".$itemplan."'
                                AND a.idCuotaAgenda = c.idCuotaAgenda 
                                AND c.idBandaHoraria = cu.idBandaHoraria
                            )countItemplanAgendamiento,
                             s.subProyectoDesc,
                             po.nombreProyecto
                        FROM (planobra po,
                              central c,
                              subproyecto s,
                              estadoplan e) LEFT JOIN cuotas_agenda cu  ON (cu.idEmpresaColab = c.idEmpresaColab AND cu.jefatura = c.jefatura)
                              LEFT JOIN banda_horaria ba ON(ba.idBandaHoraria = cu.idBandaHoraria)
                        WHERE c.idCentral       = po.idCentral
                          AND po.idSubProyecto  = s.idSubProyecto
                         -- AND po.idEstadoPlan   IN (".implode(',', $arrayEstado).")
                          AND po.idEstadoPlan   = e.idEstadoPlan
                          AND po.itemplan       = '".$itemplan."'
                        GROUP BY ba.idBandaHoraria
                        )t
                 -- WHERE t.countAgenda IS NULL OR t.cantidad <> t.countAgenda";
		$result = $this->db->query($sql, array($fechaAgendamiento));
        return $result->result();         
    }

    function getAgendamiento($fecha) {
        $ideecc  = $this->session->userdata("eeccSession");
        $sql = "SELECT a.idAgendamiento,
                       a.idCuotaAgenda,
                       a.fecha_registro,
                       a.itemplan,
                       DATE(a.fecha_agendamiento)fecha_agendamiento,
                       UNIX_TIMESTAMP(a.fecha_agendamiento)*1000 fechaMilisec,
                       cu.jefatura,
                       e.empresaColabDesc,
                       (SELECT nombre 
                          FROM usuario 
						 WHERE id_usuario = a.idUsuarioRegistro)usuarioRegistro,
					   (SELECT nombre 
                          FROM usuario 
						 WHERE id_usuario = a.idUsuarioConfirmacion)usuarioConfirmacion,
                       CONCAT(ba.horaInicio,'-',ba.horaFin) bandaHoraria,
                       CASE WHEN a.flg_estado = ".FLG_CONFIRMADO." THEN 'CONFIRMADO'
                            WHEN a.flg_estado = ".FLG_CANCELADO." THEN 'CANCELADO'
                            ELSE 'SIN CONFIRMAR' END estado,
                        a.flg_estado    
                  FROM agendamiento a, 
                       cuotas_agenda cu, 
                       empresacolab e, 
                       banda_horaria ba 
                 WHERE DATE(fecha_agendamiento) = COALESCE(?, DATE(fecha_agendamiento))
                   AND a.idCuotaAgenda   = cu.idCuotaAgenda
                   AND cu.idEmpresacolab = e.idEmpresaColab
                   AND ba.idBandaHoraria = cu.idBandaHoraria
                   AND CASE WHEN ".$ideecc." = 0 OR ".$ideecc." = 6 THEN e.idEmpresaColab = e.idEmpresaColab        
                            ELSE e.idEmpresaColab = ".$ideecc." END";
        $result = $this->db->query($sql, array($fecha)); 
        return $result->result_array();          
    }

    function registrarAgendamiento($arrayData) {
        $this->db->insert('agendamiento', $arrayData);

        if($this->db->affected_rows() == 0) {
            $data['error'] = EXIT_ERROR;
			return $data;
		} else {
            $data['error'] = EXIT_SUCCESS;
			return $data;
		}
    }

    function getFlgFecha($fecha) {
        $sql = "SELECT CASE WHEN '".$fecha."' >= DATE_ADD(CURDATE(), INTERVAL 2 DAY) AND '".$fecha."' <= DATE_ADD(CURDATE(), INTERVAL 9 DAY) THEN 1 
                            ELSE 0 END flg_fecha_agenda";
        $result = $this->db->query($sql, array($fecha)); 
        return $result->row()->flg_fecha_agenda;    
    }

    function updateConfirmarAgendamiento($arrayData, $idAgendamiento) {
        $this->db->where('idAgendamiento', $idAgendamiento);
        $this->db->update('agendamiento', $arrayData);

        if($this->db->affected_rows() < 1) {
            $data['error'] = EXIT_ERROR;
			return $data;
		} else {
            $data['error'] = EXIT_SUCCESS;
			return $data;
		}
    }

    function getMatrizAgendamiento($idEmpresaColab, $jefatura) {
        $sql = "SELECT  '' idBandaHoraria,
                        '' bandaHoraria,
                        DATE_ADD(CURDATE(), INTERVAL 2 DAY) dia_dos,
                        DATE_ADD(CURDATE(), INTERVAL 3 DAY) dia_tres,
                        DATE_ADD(CURDATE(), INTERVAL 4 DAY) dia_cuatro,
                        DATE_ADD(CURDATE(), INTERVAL 5 DAY) dia_cinco,
                        DATE_ADD(CURDATE(), INTERVAL 6 DAY) dia_seis,
                        DATE_ADD(CURDATE(), INTERVAL 7 DAY) dia_siete,
                        DATE_ADD(CURDATE(), INTERVAL 8 DAY) dia_ocho,
                        DATE_ADD(CURDATE(), INTERVAL 9 DAY) dia_nueve
                    UNION ALL                
                    SELECT  ba.idBandaHoraria,
                        CONCAT(ba.horaInicio, ' - ', ba.horaFin)horaInFin,
                        CASE WHEN SUM(CASE WHEN DATE(a.fecha_agendamiento) = t.dia_dos THEN 1 
                                        ELSE 0 END) = ( SELECT c.cantidad
														  FROM agendamiento a,
															   cuotas_agenda c 
														 WHERE DATE(a.fecha_agendamiento) = COALESCE(t.dia_dos, DATE(a.fecha_agendamiento))
														   AND a.idCuotaAgenda = c.idCuotaAgenda 
														   AND c.idBandaHoraria = ba.idBandaHoraria
                                                           AND c.idEmpresaColab = ".$idEmpresaColab."
                                                           AND c.jefatura       = '".$jefatura."'
                                                           AND flg_estado <> ".FLG_CANCELADO."
														 GROUP BY c.idCuotaAgenda) THEN '#D8D8D8' 
                            ELSE '#FFFFFF' END dia_dos,
                        CASE WHEN SUM(CASE WHEN DATE(a.fecha_agendamiento) = t.dia_tres THEN 1 
                                        ELSE 0 END) = ( SELECT c.cantidad
														  FROM agendamiento a,
															   cuotas_agenda c 
														 WHERE DATE(a.fecha_agendamiento) = COALESCE(t.dia_tres, DATE(a.fecha_agendamiento))
														   AND a.idCuotaAgenda = c.idCuotaAgenda 
                                                           AND c.idBandaHoraria = ba.idBandaHoraria
                                                           AND c.idEmpresaColab = ".$idEmpresaColab."
                                                           AND c.jefatura       = '".$jefatura."'
                                                           AND flg_estado <> ".FLG_CANCELADO."
														 GROUP BY c.idCuotaAgenda) THEN '#D8D8D8' 
                            ELSE '#FFFFFF' END dia_tres,
                        CASE WHEN SUM(CASE WHEN DATE(a.fecha_agendamiento) = t.dia_cuatro THEN 1 
                                        ELSE 0 END) = ( SELECT c.cantidad
														  FROM agendamiento a,
															   cuotas_agenda c 
														 WHERE DATE(a.fecha_agendamiento) = COALESCE(t.dia_cuatro, DATE(a.fecha_agendamiento))
														   AND a.idCuotaAgenda = c.idCuotaAgenda 
                                                           AND c.idBandaHoraria = ba.idBandaHoraria
                                                           AND c.idEmpresaColab = ".$idEmpresaColab."
                                                           AND c.jefatura       = '".$jefatura."'
                                                           AND flg_estado <> ".FLG_CANCELADO."
														 GROUP BY c.idCuotaAgenda) THEN '#D8D8D8' 
                            ELSE '#FFFFFF' END dia_cuatro,  
                        CASE WHEN SUM(CASE WHEN DATE(a.fecha_agendamiento) = t.dia_cinco THEN 1 
                                        ELSE 0 END) = ( SELECT c.cantidad
														  FROM agendamiento a,
															   cuotas_agenda c 
														 WHERE DATE(a.fecha_agendamiento) = COALESCE(t.dia_cinco, DATE(a.fecha_agendamiento))
														   AND a.idCuotaAgenda = c.idCuotaAgenda 
                                                           AND c.idBandaHoraria = ba.idBandaHoraria
                                                           AND c.idEmpresaColab = ".$idEmpresaColab."
                                                           AND c.jefatura       = '".$jefatura."'
                                                           AND flg_estado <> ".FLG_CANCELADO."
														 GROUP BY c.idCuotaAgenda) THEN '#D8D8D8'   
                            ELSE '#FFFFFF' END dia_cinco,
                        CASE WHEN SUM(CASE WHEN DATE(a.fecha_agendamiento) = t.dia_seis THEN 1 
                                        ELSE 0 END) = ( SELECT c.cantidad
														  FROM agendamiento a,
															   cuotas_agenda c 
														 WHERE DATE(a.fecha_agendamiento) = COALESCE(t.dia_seis, DATE(a.fecha_agendamiento))
														   AND a.idCuotaAgenda = c.idCuotaAgenda 
                                                           AND c.idBandaHoraria = ba.idBandaHoraria
                                                           AND c.idEmpresaColab = ".$idEmpresaColab."
                                                           AND c.jefatura       = '".$jefatura."'
                                                           AND flg_estado <> ".FLG_CANCELADO."
														 GROUP BY c.idCuotaAgenda) THEN '#D8D8D8' 
                            ELSE '#FFFFFF' END dia_seis, 
                        CASE WHEN SUM(CASE WHEN DATE(a.fecha_agendamiento) = t.dia_siete THEN 1 
                                        ELSE 0 END) = ( SELECT c.cantidad
														  FROM agendamiento a,
															   cuotas_agenda c 
														 WHERE DATE(a.fecha_agendamiento) = COALESCE(t.dia_siete, DATE(a.fecha_agendamiento))
														   AND a.idCuotaAgenda = c.idCuotaAgenda 
                                                           AND c.idBandaHoraria = ba.idBandaHoraria
                                                           AND c.idEmpresaColab = ".$idEmpresaColab."
                                                           AND c.jefatura       = '".$jefatura."'
                                                           AND flg_estado <> ".FLG_CANCELADO."
														 GROUP BY c.idCuotaAgenda) THEN '#D8D8D8' 
                            ELSE '#FFFFFF' END dia_siete,    
                        CASE WHEN SUM(CASE WHEN DATE(a.fecha_agendamiento) = t.dia_ocho THEN 1 
                                        ELSE 0 END) = ( SELECT c.cantidad
														  FROM agendamiento a,
															   cuotas_agenda c 
														 WHERE DATE(a.fecha_agendamiento) = COALESCE(t.dia_ocho, DATE(a.fecha_agendamiento))
														   AND a.idCuotaAgenda = c.idCuotaAgenda 
                                                           AND c.idBandaHoraria = ba.idBandaHoraria
                                                           AND c.idEmpresaColab = ".$idEmpresaColab."
                                                           AND c.jefatura       = '".$jefatura."'
                                                           AND flg_estado <> ".FLG_CANCELADO."
														 GROUP BY c.idCuotaAgenda) THEN '#D8D8D8'  
                            ELSE '#FFFFFF' END dia_ocho,
                        CASE WHEN SUM(CASE WHEN DATE(a.fecha_agendamiento) = t.dia_nueve THEN 1 
                                        ELSE 0 END) = ( SELECT c.cantidad
														  FROM agendamiento a,
															   cuotas_agenda c 
														 WHERE DATE(a.fecha_agendamiento) = COALESCE(t.dia_nueve, DATE(a.fecha_agendamiento))
														   AND a.idCuotaAgenda = c.idCuotaAgenda 
                                                           AND c.idBandaHoraria = ba.idBandaHoraria
                                                           AND c.idEmpresaColab = ".$idEmpresaColab."
                                                           AND c.jefatura       = '".$jefatura."'
                                                           AND flg_estado <> ".FLG_CANCELADO."
														 GROUP BY c.idCuotaAgenda) THEN '#D8D8D8' 
                            ELSE '#FFFFFF' END dia_nueve   
                FROM (banda_horaria ba, 
                        cuotas_agenda cu,
                        (SELECT DATE_ADD(CURDATE(), INTERVAL 2 DAY) dia_dos,
                                DATE_ADD(CURDATE(), INTERVAL 3 DAY) dia_tres,
                                DATE_ADD(CURDATE(), INTERVAL 4 DAY) dia_cuatro,
                                DATE_ADD(CURDATE(), INTERVAL 5 DAY) dia_cinco,
                                DATE_ADD(CURDATE(), INTERVAL 6 DAY) dia_seis,
                                DATE_ADD(CURDATE(), INTERVAL 7 DAY) dia_siete,
                                DATE_ADD(CURDATE(), INTERVAL 8 DAY) dia_ocho,
                                DATE_ADD(CURDATE(), INTERVAL 9 DAY) dia_nueve)t ) LEFT JOIN agendamiento a ON (cu.idCuotaAgenda = a.idCuotaAgenda)
                WHERE ba.idBandaHoraria = cu.idBandaHoraria
                    AND ba.estado = 1
                    GROUP BY ba.idBandaHoraria";
        $result = $this->db->query($sql, array()); 
        return $result->result();   
    }

    function countAgendamientoByFecha($idCuota, $fechaAgendamiento) {
        $sql = "SELECT COUNT(1)count 
                  FROM agendamiento 
                 WHERE idCuotaAgenda = ".$idCuota." 
                   AND DATE(fecha_agendamiento) = '".$fechaAgendamiento."'
                   AND flg_estado <> ".FLG_CANCELADO;
        $result = $this->db->query($sql, array()); 
        return $result->row()->count; 
    }

    function getCodigoAgendamiento() {
        $sql = "(SELECT CASE WHEN codigo IS NULL OR codigo = '' THEN CONCAT(YEAR(NOW()),(SELECT ROUND(RAND()*100000)))
                            ELSE MAX(codigo)+1 END AS codigo_agendamiento
                    FROM agendamiento)";
        $result = $this->db->query($sql);
        return $result->row()->codigo_agendamiento;
    }
}
