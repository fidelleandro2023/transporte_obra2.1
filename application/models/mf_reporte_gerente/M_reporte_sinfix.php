<?php
class M_reporte_sinfix extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
    }

    function getQueryTablaJefatura() {
        $sql="
        SELECT t.idZonal,
               t.zona,
               COALESCE((SELECT COUNT(1)
                           FROM planobra p,
                                central ce
                          WHERE DATE(p.fechaPreLiquidacion) = DATE_ADD(CURDATE(), INTERVAL -2 DAY)
                            AND ce.idCentral = p.idCentral
                            AND p.idSubProyecto IN (13,14,15)
                            AND CASE WHEN t.idZonal = 8 THEN ce.idZonal IN(8,9,10,11,12)
                                     ELSE ce.idZonal = t.idZonal END
                                limit 1),0)cantidadAntesAyer,
               COALESCE((SELECT COUNT(1)
                           FROM planobra p,
                                central ce
                          WHERE DATE(p.fechaPreLiquidacion) = DATE_ADD(CURDATE(), INTERVAL -1 DAY)
                            AND ce.idCentral = p.idCentral
                            AND p.idSubProyecto IN (13,14,15)
                            AND CASE WHEN t.idZonal = 8 THEN ce.idZonal IN(8,9,10,11,12)
                                        ELSE ce.idZonal = t.idZonal END
                                limit 1),0)cantidadAyer,
               COALESCE((SELECT COUNT(1)
                           FROM planobra p,
                                central ce
                          WHERE DATE(p.fechaPreLiquidacion) = CURDATE()
                            AND ce.idCentral = p.idCentral
                            AND p.idSubProyecto IN (13,14,15)
                            AND CASE WHEN t.idZonal = 8 THEN ce.idZonal IN(8,9,10,11,12)
                                        ELSE ce.idZonal = t.idZonal END
                                 limit 1),0)cantidadHoy                        
          FROM (SELECT idZonal,SUBSTRING_INDEX( zonalDesc , ' ', 1 ) as zona 
                   FROM zonal z GROUP BY (zona))t";
        $result = $this->db->query($sql);
        return $result->result();      
    }
function getQueryTablaEecc() {
        $sql="SELECT ec.empresaColabDesc,
                     ec.idEmpresaColab,
                                (SELECT COUNT(1)
                                                FROM empresacolab e,
                                                        central c, 
                                                        planobra po 
                                        WHERE DATE(po.fechaPreLiquidacion) = DATE_ADD(CURDATE(), INTERVAL -2 DAY)
                                                AND c.idCentral = po.idCentral
                                                AND e.idEmpresaColab = c.idEmpresaColab
                                                AND po.idSubProyecto IN (13,14,15)
                        AND c.idZonal IN (8,9,10,11,12)
                                                AND e.idEmpresaColab = ec.idEmpresaColab)antesAyerLima, 
                        (SELECT COUNT(1)
                                                FROM empresacolab e,
                                                        central c, 
                                                        planobra po 
                                        WHERE DATE(po.fechaPreLiquidacion) = DATE_ADD(CURDATE(), INTERVAL -2 DAY)
                                                AND c.idCentral = po.idCentral
                                                AND e.idEmpresaColab = c.idEmpresaColab
                                                AND po.idSubProyecto IN (SELECT idSubProyecto FROM subproyecto WHERE idProyecto = 3)
                        AND c.idZonal NOT IN (8,9,10,11,12)
                                                AND e.idEmpresaColab = ec.idEmpresaColab)antesAyerProvincia,       
                                (SELECT COUNT(1)
                                                FROM empresacolab e,
                                                        central c, 
                                                        planobra po 
                                        WHERE DATE(po.fechaPreLiquidacion) = DATE_ADD(CURDATE(), INTERVAL -1 DAY)
                                                AND c.idCentral = po.idCentral
                                                AND e.idEmpresaColab = c.idEmpresaColab
                                                AND po.idSubProyecto IN (13,14,15)
                        AND c.idZonal IN (8,9,10,11,12)
                                                AND e.idEmpresaColab = ec.idEmpresaColab)ayerLima,
                        (SELECT COUNT(1)
                                                FROM empresacolab e,
                                                        central c, 
                                                        planobra po 
                                        WHERE DATE(po.fechaPreLiquidacion) = DATE_ADD(CURDATE(), INTERVAL -1 DAY)
                                                AND c.idCentral = po.idCentral
                                                AND e.idEmpresaColab = c.idEmpresaColab
                                                AND po.idSubProyecto IN (13,14,15)
                        AND c.idZonal NOT IN (8,9,10,11,12)
                                                AND e.idEmpresaColab = ec.idEmpresaColab)ayerProvincia,        
                                (SELECT COUNT(1)
                                                FROM empresacolab e,
                                                        central c, 
                                                        planobra po 
                                        WHERE DATE(po.fechaPreLiquidacion) = CURDATE()
                                                AND c.idCentral = po.idCentral
                        AND c.idZonal IN (8,9,10,11,12)
                                                AND e.idEmpresaColab = c.idEmpresaColab
                                                AND po.idSubProyecto IN (13,14,15)
                                                AND e.idEmpresaColab = ec.idEmpresaColab)hoyLima,
                                (SELECT COUNT(1)
                                                FROM empresacolab e,
                                                        central c, 
                                                        planobra po 
                                        WHERE DATE(po.fechaPreLiquidacion)= CURDATE()
                                                AND c.idCentral = po.idCentral
                                                AND e.idEmpresaColab = c.idEmpresaColab
                                                AND po.idSubProyecto IN (13,14,15)
                        AND c.idZonal NOT IN (8,9,10,11,12)
                                                AND e.idEmpresaColab = ec.idEmpresaColab)hoyProvincia
                        FROM empresacolab ec";
        $result = $this->db->query($sql);
        return $result->result(); 
}

function getDetalleDataEmpreColab($idEmpresaColab, $flgZonal) {
        $sql = "SELECT po.itemPlan,
                        c.idEmpresaColab,
                        po.nombreProyecto,
                        po.indicador,
                        TIME(po.fechaPreLiquidacion)hora,
                        c.idZonal,
                        (SELECT z.zonalDesc 
                           FROM zonal z
                          WHERE c.idZonal = z.idZonal)zonalDesc,
                        c.jefatura
                   FROM planobra po,
                        central c
                  WHERE po.idCentral = c.idCentral 
                    AND DATE(po.fechaPreLiquidacion) = CURDATE()
                    AND po.fechaPreLiquidacion IS NOT NULL
                    AND po.idSubProyecto IN (13,14,15)
                    AND c.idEmpresaColab    = ".$idEmpresaColab."
                    AND CASE WHEN $flgZonal = 1 THEN c.idZonal IN (8,9,10,11,12)
                             ELSE c.idZonal NOT IN (8,9,10,11,12)END";
        $result = $this->db->query($sql);
        return $result->result();                      
}
//     function getQueryTablaEecc() {
//         $sql  = "SELECT ec.empresaColabDesc,
//                         (SELECT COUNT(1)
//                                 FROM empresacolab e,
//                                     central c, 
//                                     planobra po 
//                             WHERE DATE(po.fechaPreLiquidacion) = DATE_ADD(CURDATE(), INTERVAL -2 DAY)
//                                 AND c.idCentral = po.idCentral
//                                 AND e.idEmpresaColab = c.idEmpresaColab
//                                 AND e.idEmpresaColab = ec.idEmpresaColab)antesAyer, 
//                         (SELECT COUNT(1)
//                                 FROM empresacolab e,
//                                     central c, 
//                                     planobra po 
//                             WHERE DATE(po.fechaPreLiquidacion) = DATE_ADD(CURDATE(), INTERVAL -1 DAY)
//                                 AND c.idCentral = po.idCentral
//                                 AND e.idEmpresaColab = c.idEmpresaColab
//                                 AND e.idEmpresaColab = ec.idEmpresaColab)ayer,
//                         (SELECT COUNT(1)
//                                 FROM empresacolab e,
//                                     central c, 
//                                     planobra po 
//                             WHERE DATE(po.fechaPreLiquidacion) = CURDATE()
//                                 AND c.idCentral = po.idCentral
//                                 AND e.idEmpresaColab = c.idEmpresaColab
//                                 AND e.idEmpresaColab = ec.idEmpresaColab)hoy
//                     FROM empresacolab ec";
//             $result = $this->db->query($sql);
//             return $result->result();        
//     }
 function getDataJefaturaEcc() {
                $sql ="SELECT tb.empresaColabDesc,
                              tb.idEmpresaColab,
                              tb.zona,
                              GROUP_CONCAT(tb.idEstadoPlan,'|',count)dataZonal
                        FROM
                        (SELECT e.empresaColabDesc,
                                c.idEmpresaColab,
                                SUBSTRING_INDEX( z.zonalDesc , ' ', 1 )zona,
                                p.idEstadoPlan,
                                CASE WHEN p.idEstadoPlan = 3  THEN 'EN OBRA' 
                                WHEN p.idEstadoPlan = 10 THEN 'TRUNCA'
                                WHEN p.idEstadoPlan = 9  THEN 'PRELIQUIDADO'  END estado,
                                CASE WHEN p.idEstadoPlan=9 THEN (SELECT COUNT(1) 
                                                                   FROM planobra po,
                                                                        central ce
                                                                  WHERE ce.idCentral = po.idCentral
                                                                    AND po.idEstadoPlan = 9
                                                                    AND CASE WHEN c.idZonal IN (8,9,10,11,12)  THEN ce.idZonal IN (8,9,10,11,12)
                                                                        ELSE c.idZonal = ce.idZonal END
                                                                    AND c.idEmpresaColab = ce.idEmpresaColab
                                                                    AND po.idSubProyecto IN (13,14,15)
                                                                    AND DATE(po.fechaPreLiquidacion) = CURDATE())	ELSE COUNT(1) END AS count	                  
                
                        FROM planobra p, 
                             central  c,
                             zonal z,
                            empresacolab e
                      WHERE p.idEstadoPlan IN (10, 3, 9)
                        AND c.idCentral = p.idCentral
                        AND z.idZonal   = c.idZonal
                        AND e.idEmpresaColab = c.idEmpresaColab
                        AND p.idSubProyecto IN (13,14,15)
                        GROUP BY zona, c.idEmpresaColab,p.idEstadoPlan
                        ORDER BY c.idEmpresaColab, zona ASC)tb
                        GROUP BY tb.idEmpresaColab, tb.zona";
                $result = $this->db->query($sql);
                return $result->result();        
        }
        
        function getDataPlanObra($arraySubProy) {
                $sql = "SELECT c.jefatura, ec.empresaColabDesc,
                                SUM(CASE WHEN (po.idEstadoPlan = 1 or po.idEstadoPlan = 2 or po.idEstadoPlan = 7 or po.idEstadoPlan = 11) THEN 1 ELSE 0 END) as diseno,
                                SUM(CASE WHEN po.idEstadoPlan = 3 THEN 1 ELSE 0 END) as obra,
                                SUM(CASE WHEN  ((DATE(po.fechaPreLiquidacion) = DATE(NOW()) AND po.idEstadoPlan = 9))  THEN 1 ELSE 0 END) as hoy_pre_liqui,
                                SUM(CASE WHEN  ((DATE(po.fechaTrunca) = DATE(NOW()) AND po.idEstadoPlan = 10) 
                                OR (DATE(po.fechaCancelacion) = DATE(NOW()) AND po.idEstadoPlan = 6) )THEN 1 ELSE 0 END) as hoy_trunco,
                                SUM(CASE WHEN DATE(pa.fechaRegistro)  = CURDATE() && pa.flg_activo = 1  THEN 1 ELSE 0 END) as hoyParalizacion,
                                SUM(CASE WHEN (po.idEstadoPlan = 8)  THEN 1 ELSE 0 END) as pre_registro
                           FROM (planobra po, central c, empresacolab ec, subproyecto s) LEFT JOIN paralizacion pa ON (pa.itemplan = po.itemplan AND pa.flg_activo = 1 AND DATE(fechaRegistro) = CURDATE())
                          WHERE po.idCentral = c.idCentral
                            AND s.idSubProyecto = po.idSubProyecto
                             AND CASE WHEN s.idTipoSubProyecto = 2 && c.flg_subproByNodoCV = 97
							         THEN c.idEmpresaColabCV = ec.idEmpresaColab
                                     ELSE c.idEmpresaColab = ec.idEmpresaColab END
                            AND po.idEstadoPlan IN(1, 2, 3, 7,8, 9, 4, 10, 11)
                            AND po.idSubProyecto IN (".implode(',',$arraySubProy).")
                         GROUP BY c.jefatura, ec.empresaColabDesc";
                $result = $this->db->query($sql);
                return $result->result();
        }
        
        function getDataParalizacion() {
            $sql = "SELECT e.idEmpresaColab,
                           e.empresaColabDesc,
                           SUM(CASE WHEN DATE(pa.fechaRegistro)  = CURDATE() && c.jefatura && pa.flg_activo = 1 like 'LIMA%' THEN 1 ELSE 0 END) as hoyLima,
                           SUM(CASE WHEN DATE(pa.fechaRegistro)  = CURDATE() && c.jefatura && pa.flg_activo = 1 NOT like 'LIMA%' THEN 1 ELSE 0 END) as hoyProvincia
                      FROM empresacolab e LEFT JOIN
                           central c ON (c.idEmpresaColab = e.idEmpresaColab) LEFT JOIN
                           planobra po ON (po.idCentral = c.idCentral) LEFT JOIN
                           paralizacion pa ON (pa.itemplan = po.itemplan)
                    GROUP BY e.empresaColabDesc";
            $result = $this->db->query($sql);
            return $result->result();        
        }
        
        /////////////02102018/////////////////////////////////////////////////
        function getDetalleDataPlanObraCV($jefatura,$eecc,$arraySubProy,$arrayestado,$hoy){

          $Query = "SELECT  pa.itemplan,
                            pa.nombreProyecto,
                            c.jefatura,
                            estp.estadoPlanDesc,
                            eecc.empresaColabDesc, 
                            pcv.fec_termino_constru,
                            date(pa.fecha_creacion) as fecha_creacion,
                              pcv.avance
                      From  planobra_detalle_cv pcv,planobra pa, central c, empresacolab eecc, estadoplan estp, subproyecto s
                      where pa.itemplan=pcv.itemplan
                        AND pa.idCentral = c.idCentral
                        AND s.idSubProyecto = pa.idSubProyecto
                        AND CASE WHEN s.idTipoSubProyecto = 2 && c.flg_subproByNodoCV = 97
                       THEN c.idEmpresaColabCV = eecc.idEmpresaColab
                                     ELSE c.idEmpresaColab = eecc.idEmpresaColab END
                        AND pa.idestadoplan=estp.idestadoplan  
                        AND c.jefatura='".$jefatura."' 
                        AND eecc.empresaColabDesc='".$eecc."'
                        AND pa.idSubProyecto IN (".implode(',',$arraySubProy).")
                        AND pa.idEstadoPlan IN (".implode(',',$arrayestado).") " ;

            if($hoy!=''){
              $Query .= " AND DATE(pa.fechaPreLiquidacion) = DATE(NOW()) ";
            }


          $result = $this->db->query($Query,array());
           return $result->result();

        }
        ///////////////////////////////////////////////////////////////
}