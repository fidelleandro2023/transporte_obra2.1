<?php
class M_control_tramas extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
    }

    // function getBandejaTramaCotizacion() {
    //     $sql = "SELECT id,
    //                    origen,
    //                    ptr,
    //                    fecha_registro,
    //                    descripcion,
    //                    estado,
    //                    CASE WHEN estado = 1 THEN 'LE LLEGO BIEN LA TRAMA'
    //                         ELSE 'NO LE LLEGO LA TRAMA' END estadoDesc
    //               FROM log_tramas_sigoplus 
    //              WHERE ptr like '%CL%'
    //                AND estado IN (1,2)
    //              ORDER BY fecha_registro";
    // $result = $this->db->query($sql);
    // return $result->result_array();
    // }

    // function getBandejaTramaCotizacion() {
    //     $sql = "SELECT t.descTipo,
    //                    lg.flg_tipo,
    //                    SUM(CASE WHEN TIMEDIFF(NOW(), lg.fecha_registro)  <=  '04' THEN 1 ELSE 0 END) menor_4h,
    //                    SUM(CASE WHEN TIMEDIFF(NOW(), lg.fecha_registro)  >  '04' AND TIMEDIFF(NOW(), lg.fecha_registro)  <=  '09' THEN 1 ELSE 0 END) AS 5h_9h,
    //                    SUM(CASE WHEN TIMEDIFF(NOW(), lg.fecha_registro)  >  '09' AND TIMEDIFF(NOW(), lg.fecha_registro)  <=  '13' THEN 1 ELSE 0 END) AS 10h_13h,
    //                    SUM(CASE WHEN TIMEDIFF(NOW(), lg.fecha_registro)  >=  '14' THEN 1 ELSE 0 END) AS mayor_14h
    //               FROM                        
    //                     (SELECT 1 AS tipo,'CREAR ITEMPLAN' descTipo UNION ALL
    //                      SELECT 2, 'APROBACION PTR' UNION ALL
    //                      SELECT 3, 'COTIZACION INDIVIDUAL')t,
    //                    log_tramas_sigoplus lg
    //              WHERE lg.flg_tipo = t.tipo
    //             GROUP BY t.tipo";
    //     $result = $this->db->query($sql);
    //     return $result->result_array();
    // }

    function getBandejaTramaCotizacion() {
        $sql = "SELECT tt.descTipo,
                        tt.flg_tipo,
                        tt.orden,
                        SUM(CASE WHEN HOUR(TIMEDIFF(NOW(), tt.fecha_registro))  <=  '04' AND tt.estado = 1 THEN 1 ELSE 0 END) menor_4h_exitoso,
                        SUM(CASE WHEN HOUR(TIMEDIFF(NOW(), tt.fecha_registro))  <=  '04' AND tt.estado <> 1 THEN 1 ELSE 0 END) menor_4h_no_exitoso,
                        SUM(CASE WHEN HOUR(TIMEDIFF(NOW(), tt.fecha_registro))  >  '04' AND HOUR(TIMEDIFF(NOW(), tt.fecha_registro))  <=  '12' AND tt.estado = 1 THEN 1 ELSE 0 END) AS 5h_9h_exitoso,
                        SUM(CASE WHEN HOUR(TIMEDIFF(NOW(), tt.fecha_registro))  >  '04' AND HOUR(TIMEDIFF(NOW(), tt.fecha_registro))  <=  '12' AND tt.estado <> 1 THEN 1 ELSE 0 END) AS 5h_9h_no_exitoso,
                        SUM(CASE WHEN HOUR(TIMEDIFF(NOW(), tt.fecha_registro))  >  '12' AND HOUR(TIMEDIFF(NOW(), tt.fecha_registro))  <=  '48' AND tt.estado = 1 THEN 1 ELSE 0 END) AS 10h_13h_exitoso,
                        SUM(CASE WHEN HOUR(TIMEDIFF(NOW(), tt.fecha_registro))  >  '12' AND HOUR(TIMEDIFF(NOW(), tt.fecha_registro))  <=  '48' AND tt.estado <> 1 THEN 1 ELSE 0 END) AS 10h_13h_no_exitoso,
                        SUM(CASE WHEN HOUR(TIMEDIFF(NOW(), tt.fecha_registro))  >=  '49' AND tt.estado = 1 THEN 1 ELSE 0 END) AS mayor_14h_exitoso,
                        SUM(CASE WHEN HOUR(TIMEDIFF(NOW(), tt.fecha_registro))  >=  '49' AND tt.estado <> 1 THEN 1 ELSE 0 END) AS mayor_14h_no_exitoso
                  FROM   
                        (SELECT lg.fecha_registro,
                                lg.estado,
                                t.descTipo,
                                t.orden,
                                lg.flg_tipo,
                                t.tipo
                        FROM (SELECT lg.id,
                                    lg.sisego,
                                    lg.origen,
                                    lg.ptr,
                                    lg.fecha_registro,
                                    lg.descripcion,
                                    lg.estado,
                                    lg.flg_tipo,
                                    SUM(CASE WHEN estado = 1 THEN 1 ELSE 0 END) afirmativo,
                                    SUM(CASE WHEN estado <> 1 THEN 1 ELSE 0 END) negativo 
                                FROM log_tramas_sigoplus lg
                                WHERE CASE WHEN lg.flg_tipo = 1 THEN lg.sisego IS NOT NULL
                                                                    ELSE true END                             
                                GROUP BY ptr, sisego, flg_tipo -- SUBSTRING_INDEX(lg.sisego,'-', 3)
                                HAVING CASE WHEN afirmativo IN (1,2) THEN estado = 1
                                            WHEN afirmativo = 0 AND negativo <> 0 THEN estado <> 1 END)lg,
                                                        (
                                                         SELECT 5 AS tipo, 'REG. COTIZACION (SW -> PO)' descTipo, 1 orden UNION ALL
                                                         SELECT 3, 'COTIZACION (PO -> SW)', 2 UNION ALL
                                                         SELECT 1 ,'CREAR ITEMPLAN (SW -> PO)' descTipo, 3 UNION ALL
                                                         SELECT 2, 'APROB. PTR (PO -> SW)', 4  UNION ALL
                                                         SELECT 4, 'LIQUI. OBRA (PO -> SW)', 5 
                                                         
                                                         )t
                        WHERE lg.flg_tipo = t.tipo
                          AND TIMESTAMPDIFF(MONTH,lg.fecha_registro, NOW()) <= 3) tt       
                 GROUP BY tt.tipo
                 ORDER BY tt.orden ASC";
        $result = $this->db->query($sql);
        return $result->result_array();
    }

    function getBandejaTramaDetalleCotizacion($flgTipo, $flgExito, $flgIntervalo) {
        $sql = "SELECT DISTINCT
                       id,
                       sisego,
                       origen,
                       ptr,
                       fecha_registro,
                       descripcion,
                       estado,
                       itemplan,
                       CASE WHEN estado = 1 THEN 'LE LLEGO BIEN LA TRAMA'
                            ELSE 'NO LE LLEGO LA TRAMA' END estadoDesc
                  FROM (SELECT lg.id,
							   lg.sisego,
							   lg.origen,
							   lg.ptr,
							   lg.fecha_registro,
							   lg.descripcion,
							   lg.estado,
                               lg.flg_tipo,
                               lg.itemplan,
                               SUM(CASE WHEN estado = 1 THEN 1 ELSE 0 END) afirmativo,
                        	   SUM(CASE WHEN estado <> 1 THEN 1 ELSE 0 END) negativo 
                          FROM log_tramas_sigoplus lg
                          WHERE CASE WHEN lg.flg_tipo = 1 THEN lg.sisego IS NOT NULL
                                                              ELSE true END                              
                          GROUP BY ptr, sisego, flg_tipo
                          HAVING CASE WHEN afirmativo IN(1,2) THEN estado = 1
                                      WHEN afirmativo = 0 AND negativo <> 0 THEN estado <> 1 END)lg,
                                                (SELECT 1 AS tipo,'CREAR ITEMPLAN' descTipo UNION ALL
                                                 SELECT 2, 'APROBACION PTR' UNION ALL
                                                 SELECT 3, 'COTIZACION INDIVIDUAL')t
                 WHERE CASE WHEN ? = 1 THEN estado = 1
                            ELSE estado <> 1 END
                   AND flg_tipo = COALESCE(?, flg_tipo)
                   AND TIMESTAMPDIFF(MONTH,fecha_registro, NOW()) <= 3
                   AND CASE WHEN ? = 1 THEN HOUR(TIMEDIFF(NOW(), fecha_registro)) <= '04'
                            WHEN ? = 2 THEN HOUR(TIMEDIFF(NOW(), fecha_registro)) >  '04' AND HOUR(TIMEDIFF(NOW(), fecha_registro))  <=  '12'
                            WHEN ? = 3 THEN HOUR(TIMEDIFF(NOW(), fecha_registro)) >  '12' AND HOUR(TIMEDIFF(NOW(), fecha_registro))  <=  '48'
                            WHEN ? = 4 THEN HOUR(TIMEDIFF(NOW(), fecha_registro)) >= '49' END";
    $result = $this->db->query($sql, array($flgExito, $flgTipo, $flgIntervalo, $flgIntervalo, $flgIntervalo, $flgIntervalo));
    return $result->result_array();
    }
    
    function getBandejaTramaSiom() {
        $sql = " SELECT t.descTipo,
                        lg.flg_tipo,
                        SUM(CASE WHEN HOUR(TIMEDIFF(NOW(), lg.fecha_envio))  <=  '04' AND estado = 1  THEN 1 ELSE 0 END) menor_4h_exitoso,
                        SUM(CASE WHEN HOUR(TIMEDIFF(NOW(), lg.fecha_envio))  <=  '04' AND estado <> 1 THEN 1 ELSE 0 END) menor_4h_no_exitoso,
                        SUM(CASE WHEN HOUR(TIMEDIFF(NOW(), lg.fecha_envio))  >  '04' AND HOUR(TIMEDIFF(NOW(), lg.fecha_envio))  <=  '12' AND estado = 1  THEN 1 ELSE 0 END) AS 5h_12h_exitoso,
                        SUM(CASE WHEN HOUR(TIMEDIFF(NOW(), lg.fecha_envio))  >  '04' AND HOUR(TIMEDIFF(NOW(), lg.fecha_envio))  <=  '12' AND estado <> 1 THEN 1 ELSE 0 END) AS 5h_12h_no_exitoso,
                        SUM(CASE WHEN HOUR(TIMEDIFF(NOW(), lg.fecha_envio))  >  '12' AND HOUR(TIMEDIFF(NOW(), lg.fecha_envio))  <=  '48' AND estado = 1  THEN 1 ELSE 0 END) AS 13h_48h_exitoso,
                        SUM(CASE WHEN HOUR(TIMEDIFF(NOW(), lg.fecha_envio))  >  '12' AND HOUR(TIMEDIFF(NOW(), lg.fecha_envio))  <=  '48' AND estado <> 1 THEN 1 ELSE 0 END) AS 13h_48h_no_exitoso,
                        SUM(CASE WHEN HOUR(TIMEDIFF(NOW(), lg.fecha_envio))  >=  '49' AND estado = 1  THEN 1 ELSE 0 END) AS mayor_48h_exitoso,
                        SUM(CASE WHEN HOUR(TIMEDIFF(NOW(), lg.fecha_envio))  >=  '49' AND estado <> 1 THEN 1 ELSE 0 END)  AS mayor_48h_no_exitoso 
                  FROM
                        (SELECT tt.flg_tipo, 
                                tt.fecha_envio,
                                tt.estado
                            FROM
                                (SELECT t. flg_tipo,
                                        t.estado,
                                        t.fecha_envio,
                                        SUM(t.afirmativo)afirmativo, SUM(t.negativo)negativo 
                                FROM			 
                                        (SELECT 1 AS flg_tipo, fecha_envio,
                                                estado,
                                                ptr,
                                                itemplan,
                                                SUM(CASE WHEN estado = 1 THEN 1 ELSE 0 END) afirmativo,
                                                SUM(CASE WHEN estado <> 1 THEN 1 ELSE 0 END) negativo 
                                            FROM log_tramas_siom
                                            WHERE TIMESTAMPDIFF(MONTH,fecha_envio, NOW()) <= 3
                                              AND mensaje NOT IN ('NO SE ENCONTRO EMPLAZAMIENTO ID PARA ESE NODO', 'ESTACION NO EJECUTADA')
                                              
                                        GROUP BY ptr, itemplan,estado)t 
                                GROUP BY t.ptr, t.itemplan             
                        HAVING CASE WHEN afirmativo = 1 THEN t.estado = 1 
                                    WHEN afirmativo = 0 AND negativo <> 0 THEN t.estado <> 1 END)tt    
                        UNION ALL
                        SELECT 2, fechaRegistro, estado_transaccion 
                          FROM log_tramas_estados_siom
                          WHERE TIMESTAMPDIFF(MONTH,fechaRegistro, NOW()) <= 3)lg,
                        (SELECT 1 AS tipo,'ENVIO TRAMA SIOM (PO->SIOM)' descTipo UNION ALL
                        SELECT 2, 'CAMBIO ESTADO SIOM (SIOM->PO)' )t
                  WHERE t.tipo = lg.flg_tipo
                 GROUP BY t.tipo";
        $result = $this->db->query($sql);
        return $result->result_array();
    }

    function getDetalleSiomEnvio($flgExito, $flgIntervalo) {
        $sql = "  SELECT *
                    FROM (
                            SELECT t. itemplan,
                                    t.ptr,
                                    t.mensaje,
                                    t.estado,
                                    t.usuario_envio,
                                    t.fecha_envio,
                                    SUM(t.afirmativo)afirmativo, SUM(t.negativo)negativo 
                                FROM			 
                                        (SELECT itemplan,
                                                    ptr,
                                                    mensaje,
                                                    estado,
                                                    usuario_envio,
                                                    fecha_envio,
                                                    SUM(CASE WHEN estado = 1 THEN 1 ELSE 0 END) afirmativo,
                                                    SUM(CASE WHEN estado <> 1 THEN 1 ELSE 0 END) negativo 
                                                FROM log_tramas_siom
                                            WHERE TIMESTAMPDIFF(MONTH,fecha_envio, NOW()) <= 3
                                                AND mensaje NOT IN ('NO SE ENCONTRO EMPLAZAMIENTO ID PARA ESE NODO', 'ESTACION NO EJECUTADA')
                                                AND CASE WHEN ? = 1 THEN HOUR(TIMEDIFF(NOW(), fecha_envio)) <= '04'
                                                         WHEN ? = 2 THEN HOUR(TIMEDIFF(NOW(), fecha_envio)) >  '04' AND HOUR(TIMEDIFF(NOW(), fecha_envio))  <=  '12'
                                                         WHEN ? = 3 THEN HOUR(TIMEDIFF(NOW(), fecha_envio)) >  '12' AND HOUR(TIMEDIFF(NOW(), fecha_envio))  <=  '48'
                                                         WHEN ? = 4 THEN HOUR(TIMEDIFF(NOW(), fecha_envio)) >= '49' END
                                            GROUP BY ptr, itemplan,estado)t
                                    GROUP BY t.ptr, t.itemplan   
                                HAVING CASE WHEN afirmativo = 1 THEN t.estado = 1 
                                            WHEN afirmativo = 0 AND negativo <> 0 THEN t.estado <> 1 END
                            )tt
                    WHERE CASE WHEN ? = 1 THEN estado = 1
                    ELSE estado <> 1 END";
        $result = $this->db->query($sql, array($flgIntervalo, $flgIntervalo, $flgIntervalo, $flgIntervalo, $flgExito));
        return $result->result_array();
    }

    function getDetalleEstadoSiom($flgExito, $flgIntervalo) {
        $sql = " SELECT id,
                        codigo_siom,
                        estado_desc,
                        fechaRegistro,
                        usuario_registro,
                        estado_transaccion,
                        motivo
                   FROM log_tramas_estados_siom
                  WHERE CASE WHEN ? = 1 THEN estado_transaccion = 1
                            ELSE estado_transaccion <> 1 END
                    AND TIMESTAMPDIFF(MONTH,fechaRegistro, NOW()) <= 3         
                    AND CASE WHEN ? = 1 THEN HOUR(TIMEDIFF(NOW(), fechaRegistro)) <= '04'
                             WHEN ? = 2 THEN HOUR(TIMEDIFF(NOW(), fechaRegistro)) >  '04' AND HOUR(TIMEDIFF(NOW(), fechaRegistro))  <=  '12'
                             WHEN ? = 3 THEN HOUR(TIMEDIFF(NOW(), fechaRegistro)) >  '12' AND HOUR(TIMEDIFF(NOW(), fechaRegistro))  <=  '48'
                             WHEN ? = 4 THEN HOUR(TIMEDIFF(NOW(), fechaRegistro)) >= '49' END";
        $result = $this->db->query($sql, array($flgExito, $flgIntervalo, $flgIntervalo, $flgIntervalo, $flgIntervalo));
        return $result->result_array();
    }

    // function getTrama() {
    //     $sql = "SELECT SUM(CASE WHEN po.itemplan IS NOT NULL THEN 1 ELSE 0 END),
    //                    SUM(CASE WHEN t.itemplan IS NOT NULL THEN 1 ELSE 0 END),
    //                    SUM(CASE WHEN t.itemplan IS NULL THEN 1 ELSE 0 END) 
    //               FROM planobra po 
    //          LEFT JOIN 
    //             (SELECT SUBSTRING_INDEX(itemplan,'"', -1) AS itemplan,
    //                     proyecto,
    //                     subproyecto,
    //                     fecha_creacion
    //                 FROM reporte_planobra
    //             WHERE TIMESTAMPDIFF(MONTH,fecha_creacion, NOW()) <= 3)t
    //             ON (po.itemplan = t.itemplan)
    //             WHERE TIMESTAMPDIFF(MONTH,po.fecha_creacion, NOW()) <= 3";
    // }
    
    function getTramaTransferencia() {
        $sql = "  SELECT tt.flg_tipo,
                         GROUP_CONCAT(CASE WHEN tt.flg_tipo = 1 THEN 'PLAN OBRA'
                                           WHEN tt.flg_tipo = 2 THEN 'DETALLE PLAN' END,',',cant_po_hoy,',',cant_po_1d,',',cant_po_2d,',',cant_po_3d,',',cant_po_4d,'|',
                                      'REPORTE',',',cant_rep_hoy,',',cant_rep_1d,',',cant_rep_2d,',',cant_rep_3d,',',cant_rep_4d)  cant_dias
                    FROM 
                        (SELECT t.flg_tipo,
                                MAX(cant_po_hoy)cant_po_hoy,
                                MAX(cant_rep_hoy)cant_rep_hoy,
                                MAX(cant_po_1d)cant_po_1d,
                                MAX(cant_rep_1d)cant_rep_1d,
                                MAX(cant_po_2d)cant_po_2d,
                                MAX(cant_rep_2d)cant_rep_2d,
                                MAX(cant_po_3d)cant_po_3d,
                                MAX(cant_rep_3d)cant_rep_3d,
                                MAX(cant_po_4d)cant_po_4d,
                                MAX(cant_rep_4d)cant_rep_4d
                            FROM (SELECT flg_tipo,
                                        CASE WHEN DATEDIFF(CURDATE(), DATE(fecha_registro)) = 0 THEN cant_po  ELSE 0 END cant_po_hoy,
                                        CASE WHEN DATEDIFF(CURDATE(), DATE(fecha_registro)) = 0 THEN cant_rep ELSE 0 END cant_rep_hoy,
                                        CASE WHEN DATEDIFF(CURDATE(), DATE(fecha_registro)) = 1 THEN cant_po  ELSE 0 END cant_po_1d,
                                        CASE WHEN DATEDIFF(CURDATE(), DATE(fecha_registro)) = 1 THEN cant_rep ELSE 0 END cant_rep_1d,
                                        CASE WHEN DATEDIFF(CURDATE(), DATE(fecha_registro)) = 2 THEN cant_po  ELSE 0 END cant_po_2d,
                                        CASE WHEN DATEDIFF(CURDATE(), DATE(fecha_registro)) = 2 THEN cant_rep ELSE 0 END cant_rep_2d,
                                        CASE WHEN DATEDIFF(CURDATE(), DATE(fecha_registro)) = 3 THEN cant_po  ELSE 0 END cant_po_3d,
                                        CASE WHEN DATEDIFF(CURDATE(), DATE(fecha_registro)) = 3 THEN cant_rep ELSE 0 END cant_rep_3d,
                                        CASE WHEN DATEDIFF(CURDATE(), DATE(fecha_registro)) = 4 THEN cant_po  ELSE 0 END cant_po_4d,
                                        CASE WHEN DATEDIFF(CURDATE(), DATE(fecha_registro)) = 4 THEN cant_rep ELSE 0 END cant_rep_4d
                                FROM reporte_transferencia)t
                            GROUP BY  t.flg_tipo  
                                ORDER BY t.flg_tipo)tt
                        GROUP BY tt.flg_tipo";
        $result = $this->db->query($sql);
        return $result->result_array();            
    }
    
    function getDetalleTransferenciaPo($tipo, $flgDia) {
        $sql = "  SELECT po.itemplan, 
                         s.subproyectoDesc,
                         po.fecha_creacion
                    FROM (planobra po,
                          subproyecto s)
               LEFT JOIN  reporte_planobra t
                      ON (po.itemplan = t.itemplan)
                   WHERE po.idSubProyecto = s.idSubProyecto 
                     AND   CASE WHEN  ? = 'REGISTROS' AND  ? = 1 
                                        THEN  po.itemplan IS NOT NULL AND DATEDIFF(CURDATE(), po.fecha_creacion) = 0 
                                WHEN  ? = 'REGISTROS' AND ? = 2 
                                        THEN  po.itemplan IS NOT NULL AND DATEDIFF(CURDATE(), po.fecha_creacion) = 1 
                                WHEN  ? = 'REGISTROS' AND ? = 3 
                                        THEN  po.itemplan IS NOT NULL AND DATEDIFF(CURDATE(), po.fecha_creacion) = 2 
                                WHEN  ? = 'REGISTROS' AND ? = 4 
                                        THEN  po.itemplan IS NOT NULL AND DATEDIFF(CURDATE(), po.fecha_creacion) = 3 
                                WHEN  ? = 'REGISTROS' AND ? = 5 
                                        THEN  po.itemplan IS NOT NULL AND DATEDIFF(CURDATE(), po.fecha_creacion) = 4
                                WHEN  ? = 'REPORTE PO' AND  ? = 1 
                                        THEN  t.itemplan IS NOT NULL AND DATEDIFF(CURDATE(), po.fecha_creacion) = 0 
                                WHEN  ? = 'REPORTE PO' AND ? = 2 
                                        THEN  t.itemplan IS NOT NULL AND DATEDIFF(CURDATE(), po.fecha_creacion) = 1 
                                WHEN  ? = 'REPORTE PO' AND ? = 3 
                                        THEN  t.itemplan IS NOT NULL AND DATEDIFF(CURDATE(), po.fecha_creacion) = 2
                                WHEN  ? = 'REPORTE PO' AND ? = 4 
                                        THEN  t.itemplan IS NOT NULL AND DATEDIFF(CURDATE(), po.fecha_creacion) = 3 
                                WHEN  ? = 'REPORTE PO' AND ? = 5 
                                        THEN  t.itemplan IS NOT NULL AND DATEDIFF(CURDATE(), po.fecha_creacion) = 4
                                WHEN ? = 'DIFERENCIA' AND  ? = 1 
                                        THEN  t.itemplan IS NULL AND DATEDIFF(CURDATE(), po.fecha_creacion) = 0 
                                WHEN ? = 'DIFERENCIA' AND ? = 2 
                                        THEN  t.itemplan IS NULL AND DATEDIFF(CURDATE(), po.fecha_creacion) = 1 
                                WHEN ? = 'DIFERENCIA' AND ? = 3 
                                        THEN  t.itemplan IS NULL AND DATEDIFF(CURDATE(), po.fecha_creacion) = 2 
                                WHEN ? = 'DIFERENCIA' AND ? = 4 
                                        THEN  t.itemplan IS NULL AND DATEDIFF(CURDATE(), po.fecha_creacion) = 3 
                                WHEN ? = 'DIFERENCIA' AND ? = 5 
                                        THEN  t.itemplan IS NULL AND DATEDIFF(CURDATE(), po.fecha_creacion) = 4 END";
        $result = $this->db->query($sql, array( $tipo, $flgDia,$tipo, $flgDia,$tipo, $flgDia,$tipo, $flgDia,$tipo, $flgDia,$tipo, $flgDia,
                                                $tipo, $flgDia,$tipo, $flgDia,$tipo, $flgDia,$tipo, $flgDia,$tipo, $flgDia,$tipo, $flgDia,
                                                $tipo, $flgDia,$tipo, $flgDia,$tipo, $flgDia));
        return $result->result_array();  
    }
    
    function getCountRegistroPO($tipo, $flgDia) {
        $sql = "  SELECT po.itemplan, 
                         po.fecha_creacion,
                         s.subproyectoDesc
                    FROM planobra po,
                         subproyecto s
                   WHERE  s.idSubProyecto = po.idSubProyecto
                     AND   CASE  WHEN  ? = 'REGISTROS' AND  ? = 1 
                                        THEN  po.itemplan IS NOT NULL AND DATEDIFF(CURDATE(), po.fecha_creacion) = 0 
                                WHEN  ? = 'REGISTROS' AND ? = 2 
                                        THEN  po.itemplan IS NOT NULL AND DATEDIFF(CURDATE(), po.fecha_creacion) = 1 
                                WHEN  ? = 'REGISTROS' AND ? = 3 
                                        THEN  po.itemplan IS NOT NULL AND DATEDIFF(CURDATE(), po.fecha_creacion) = 2 
                                WHEN  ? = 'REGISTROS' AND ? = 4 
                                        THEN  po.itemplan IS NOT NULL AND DATEDIFF(CURDATE(), po.fecha_creacion) = 3 
                                WHEN  ? = 'REGISTROS' AND ? = 5 
                                        THEN  po.itemplan IS NOT NULL AND DATEDIFF(CURDATE(), po.fecha_creacion) = 4 END";
        $result = $this->db->query($sql, array( $tipo, $flgDia,$tipo, $flgDia,$tipo, $flgDia,$tipo, $flgDia, $tipo, $flgDia));
        return $result->result_array();  
    }

    function getCountReportePO($tipo, $flgDia) {
        $sql = "  SELECT itemplan, 
                         subproyecto AS subproyectoDesc,
                         fecha_creacion
                    FROM reporte_planobra  
                   WHERE  CASE  WHEN  ? = 'REPORTE PO' AND  ? = 1 
                                        THEN  itemplan IS NOT NULL AND DATEDIFF(CURDATE(), fecha_creacion) = 0 
                                WHEN  ? = 'REPORTE PO' AND ? = 2 
                                        THEN  itemplan IS NOT NULL AND DATEDIFF(CURDATE(), fecha_creacion) = 1 
                                WHEN  ? = 'REPORTE PO' AND ? = 3 
                                        THEN  itemplan IS NOT NULL AND DATEDIFF(CURDATE(), fecha_creacion) = 2
                                WHEN  ? = 'REPORTE PO' AND ? = 4 
                                        THEN  itemplan IS NOT NULL AND DATEDIFF(CURDATE(), fecha_creacion) = 3 
                                WHEN  ? = 'REPORTE PO' AND ? = 5 
                                        THEN  itemplan IS NOT NULL AND DATEDIFF(CURDATE(), po.fecha_creacion) = 4 END";
        $result = $this->db->query($sql, array( $tipo, $flgDia,$tipo, $flgDia,$tipo, $flgDia,$tipo, $flgDia, $tipo, $flgDia));
        return $result->result_array();  
    }

    function getDetalleDiferenciaPO($flgDia) {
        $sql = " SELECT po.itemplan, 
                        s.subProyectoDesc,
                        po.fecha_creacion
                    FROM (planobra po,
                          subproyecto s)
               LEFT JOIN  reporte_planobra t
                      ON (po.itemplan = t.itemplan)
                   WHERE  t.itemplan IS NULL
                     AND s.idSubProyecto = po.idSubProyecto
                     AND CASE WHEN ? = 1 THEN DATEDIFF(CURDATE(), po.fecha_creacion) = 0
                              WHEN ? = 2 THEN DATEDIFF(CURDATE(), po.fecha_creacion) = 1
                              WHEN ? = 3 THEN DATEDIFF(CURDATE(), po.fecha_creacion) = 2
                              WHEN ? = 4 THEN DATEDIFF(CURDATE(), po.fecha_creacion) = 3
                              WHEN ? = 5 THEN DATEDIFF(CURDATE(), po.fecha_creacion) = 4 END";
        $result = $this->db->query($sql, array($flgDia,$flgDia,$flgDia,$flgDia,$flgDia));
        return $result->result_array();  
    }
	
	/*getTramaSirope modificado 30.10.2019 czavalacas para obtner el ultimo estado de cada itemplan*/
	function getTramaSirope() {
		$sql = " SELECT 'ENVIO TRAMA SIROPE' AS descripcion,
						SUM(CASE WHEN estado = 1 THEN 1 ELSE 0 END) afirmativo,
						SUM(CASE WHEN estado <> 1 THEN 1 ELSE 0 END) negativo,
						SUM(CASE WHEN HOUR(TIMEDIFF(NOW(), lg.fecha_envio))  <=  '04' AND lg.estado = 1 THEN 1 ELSE 0 END) menor_4h_exitoso,
						SUM(CASE WHEN HOUR(TIMEDIFF(NOW(), lg.fecha_envio))  <=  '04' AND lg.estado <> 1 AND lg.mensaje_recibido <> '".MSJ_TRAMA_SIROPE_EXISTE_CODIGO."' THEN 1 ELSE 0 END) menor_4h_no_exitoso,
						SUM(CASE WHEN HOUR(TIMEDIFF(NOW(), lg.fecha_envio))  <=  '04' AND lg.estado <> 1 AND lg.mensaje_recibido = '".MSJ_TRAMA_SIROPE_EXISTE_CODIGO."' THEN 1 ELSE 0 END) menor_4h_no_exitoso_existe_codigo,
						SUM(CASE WHEN HOUR(TIMEDIFF(NOW(), lg.fecha_envio))  >  '04' AND HOUR(TIMEDIFF(NOW(), lg.fecha_envio))  <=  '12' AND lg.estado = 1 THEN 1 ELSE 0 END) AS 5h_9h_exitoso,
						SUM(CASE WHEN HOUR(TIMEDIFF(NOW(), lg.fecha_envio))  >  '04' AND HOUR(TIMEDIFF(NOW(), lg.fecha_envio))  <=  '12' AND lg.estado <> 1 AND lg.mensaje_recibido <> '".MSJ_TRAMA_SIROPE_EXISTE_CODIGO."' THEN 1 ELSE 0 END) AS 5h_9h_no_exitoso,
						SUM(CASE WHEN HOUR(TIMEDIFF(NOW(), lg.fecha_envio))  >  '04' AND HOUR(TIMEDIFF(NOW(), lg.fecha_envio))  <=  '12' AND lg.estado <> 1 AND lg.mensaje_recibido = '".MSJ_TRAMA_SIROPE_EXISTE_CODIGO."' THEN 1 ELSE 0 END) AS 5h_9h_no_exitoso_existe_codigo,
						SUM(CASE WHEN HOUR(TIMEDIFF(NOW(), lg.fecha_envio))  >  '12' AND HOUR(TIMEDIFF(NOW(), lg.fecha_envio))  <=  '48' AND lg.estado = 1 THEN 1 ELSE 0 END) AS 10h_13h_exitoso,
						SUM(CASE WHEN HOUR(TIMEDIFF(NOW(), lg.fecha_envio))  >  '12' AND HOUR(TIMEDIFF(NOW(), lg.fecha_envio))  <=  '48' AND lg.estado <> 1 AND lg.mensaje_recibido <> '".MSJ_TRAMA_SIROPE_EXISTE_CODIGO."' THEN 1 ELSE 0 END) AS 10h_13h_no_exitoso,
						SUM(CASE WHEN HOUR(TIMEDIFF(NOW(), lg.fecha_envio))  >  '12' AND HOUR(TIMEDIFF(NOW(), lg.fecha_envio))  <=  '48' AND lg.estado <> 1 AND lg.mensaje_recibido = '".MSJ_TRAMA_SIROPE_EXISTE_CODIGO."' THEN 1 ELSE 0 END) AS 10h_13h_no_exitoso_existe_codigo,
						SUM(CASE WHEN HOUR(TIMEDIFF(NOW(), lg.fecha_envio))  >=  '49' AND lg.estado = 1 THEN 1 ELSE 0 END) AS mayor_14h_exitoso,
						SUM(CASE WHEN HOUR(TIMEDIFF(NOW(), lg.fecha_envio))  >=  '49' AND lg.estado <> 1 AND lg.mensaje_recibido <> '".MSJ_TRAMA_SIROPE_EXISTE_CODIGO."' THEN 1 ELSE 0 END) AS mayor_14h_no_exitoso,
						SUM(CASE WHEN HOUR(TIMEDIFF(NOW(), lg.fecha_envio))  >=  '49' AND lg.estado <> 1 AND lg.mensaje_recibido = '".MSJ_TRAMA_SIROPE_EXISTE_CODIGO."' THEN 1 ELSE 0 END) AS mayor_14h_no_exitoso_existe_codigo
				   FROM log_tramas_sirope lg join (	
						SELECT itemplan,  MAX(fecha_envio) as fecha_envio 			
						FROM log_tramas_sirope  group by itemplan) as tb ON lg.itemplan = tb.itemplan and lg.fecha_envio = tb.fecha_envio
				  WHERE TIMESTAMPDIFF(MONTH,lg.fecha_envio, NOW()) <= 3";
		$result = $this->db->query($sql);
        return $result->result_array();  		  
	}
	
	/*getTramaDetalleSirope modificado 30.10.2019 czavalacas para obtner el ultimo estado de cada itemplan*/
	function getTramaDetalleSirope($estado, $intervalo) {
		$sql = "SELECT lg.itemplan, 
					   lg.codigo_ot, 
					   lg.fecha_envio, 
					   lg.estado, 
					   lg.mensaje_recibido
				  FROM log_tramas_sirope lg join (	
						SELECT itemplan,  MAX(fecha_envio) as fecha_envio 			
						FROM log_tramas_sirope  group by itemplan) as tb ON lg.itemplan = tb.itemplan and lg.fecha_envio = tb.fecha_envio
				 WHERE CASE WHEN ? = 1 THEN lg.estado = ?
				            WHEN ? = 3 THEN lg.estado <> 1 AND lg.mensaje_recibido = '".MSJ_TRAMA_SIROPE_EXISTE_CODIGO."'
							ELSE lg.estado <> 1 AND lg.mensaje_recibido <> '".MSJ_TRAMA_SIROPE_EXISTE_CODIGO."' END
				   AND TIMESTAMPDIFF(MONTH,lg.fecha_envio, NOW()) <= 3
				   AND CASE WHEN ? = 1 THEN HOUR(TIMEDIFF(NOW(), lg.fecha_envio)) <= '04'
							WHEN ? = 2 THEN HOUR(TIMEDIFF(NOW(), lg.fecha_envio)) >  '04' AND HOUR(TIMEDIFF(NOW(), lg.fecha_envio))  <=  '12'
							WHEN ? = 3 THEN HOUR(TIMEDIFF(NOW(), lg.fecha_envio)) >  '12' AND HOUR(TIMEDIFF(NOW(), lg.fecha_envio))  <=  '48'
							WHEN ? = 4 THEN HOUR(TIMEDIFF(NOW(), lg.fecha_envio)) >= '49' END";
		$result = $this->db->query($sql, array($estado, $estado, $estado, $intervalo, $intervalo, $intervalo, $intervalo));
        return $result->result_array();  
	}
	
	function getTramasRpaSap() {
	    $sql = "SELECT DATE_FORMAT(fecha, '%d-%m-%Y') as fecha, 
				SUM(CASE WHEN estado in (1,2) THEN 1 ELSE 0 END) total_dia, 
				SUM(CASE WHEN DATE_FORMAT(fecha, '%H:%i:%s') > '09:50:00' AND DATE_FORMAT(fecha, '%H:%i:%s') <= '14:50:00' AND estado = 5 THEN num_po_send ELSE 0 END ) as horario_1_send,
				SUM(CASE WHEN DATE_FORMAT(fecha, '%H:%i:%s') > '09:50:00' AND DATE_FORMAT(fecha, '%H:%i:%s') <= '14:50:00' AND estado = 1 THEN 1 ELSE 0 END ) as horario_1_ok,
				SUM(CASE WHEN DATE_FORMAT(fecha, '%H:%i:%s') > '09:50:00' AND DATE_FORMAT(fecha, '%H:%i:%s') <= '14:50:00' AND estado = 2 THEN 1 ELSE 0 END ) as horario_1_no,
				SUM(CASE WHEN DATE_FORMAT(fecha, '%H:%i:%s') > '14:50:00' AND DATE_FORMAT(fecha, '%H:%i:%s') <= '17:50:00' AND estado = 5 THEN num_po_send ELSE 0 END ) as horario_2_send,
				SUM(CASE WHEN DATE_FORMAT(fecha, '%H:%i:%s') > '14:50:00' AND DATE_FORMAT(fecha, '%H:%i:%s') <= '17:50:00' AND estado = 1 THEN 1 ELSE 0 END ) as horario_2_ok,
				SUM(CASE WHEN DATE_FORMAT(fecha, '%H:%i:%s') > '14:50:00' AND DATE_FORMAT(fecha, '%H:%i:%s') <= '17:50:00' AND estado = 2 THEN 1 ELSE 0 END ) as horario_2_no,
				SUM(CASE WHEN DATE_FORMAT(fecha, '%H:%i:%s') > '17:50:00' AND DATE_FORMAT(fecha, '%H:%i:%s') <= '20:50:00' AND estado = 5 THEN num_po_send ELSE 0 END ) as horario_3_send,
				SUM(CASE WHEN DATE_FORMAT(fecha, '%H:%i:%s') > '17:50:00' AND DATE_FORMAT(fecha, '%H:%i:%s') <= '20:50:00' AND estado = 1 THEN 1 ELSE 0 END ) as horario_3_ok,
				SUM(CASE WHEN DATE_FORMAT(fecha, '%H:%i:%s') > '17:50:00' AND DATE_FORMAT(fecha, '%H:%i:%s') <= '20:50:00' AND estado = 2 THEN 1 ELSE 0 END ) as horario_3_no
				from log_tramas_rpa_sap where estado in (1,2,5)
				group by DATE_FORMAT(fecha, '%d-%m-%Y')
				ORDER BY DATE_FORMAT(fecha, '%Y-%m-%d') DESC LIMIT 7";
	    $result = $this->db->query($sql, array());
	    #log_message('error', $this->db->last_query());
	    return $result->result();
	}
	
	function getDetalleTramasRpaSap($fecha, $rango_horario, $exitoso) {
	    $sql = "SELECT lt.*, sp.subproyectoDesc, p.proyectoDesc, epo.estado
	           FROM    log_tramas_rpa_sap lt, planobra po, proyecto p , subproyecto sp, planobra_po ppo, po_estado epo
	           WHERE   lt.itemplan = po.itemplan
               AND     po.idSubProyecto = sp.idSubProyecto
               AND     sp.idProyecto = p.idProyecto 
			   AND     lt.itemplan = ppo.itemplan
               AND     lt.codigo_po = ppo.codigo_po
               AND     ppo.estado_po = epo.idPoEstado
	           AND     DATE_FORMAT(fecha, '%d-%m-%Y') = ? ";
	    
	    if($rango_horario == 1){
	        $sql .= "AND DATE_FORMAT(fecha, '%H:%i:%s') > '09:50:00' AND DATE_FORMAT(fecha, '%H:%i:%s') <= '14:50:00' ";
	    }else if($rango_horario == 2){
            $sql .= "AND DATE_FORMAT(fecha, '%H:%i:%s') > '14:50:00' AND DATE_FORMAT(fecha, '%H:%i:%s') <= '17:50:00' ";
	    }else if($rango_horario == 3){
	        $sql .= "AND DATE_FORMAT(fecha, '%H:%i:%s') > '17:50:00' AND DATE_FORMAT(fecha, '%H:%i:%s') <= '20:50:00' ";
	    }
	    
	    if($exitoso == 1){
	        $sql .= "AND lt.estado = 1";
	    }else if($exitoso == 2){
	        $sql .= "AND lt.estado = 2";
	    }      
	            
	    $result = $this->db->query($sql, array($fecha));
	    #log_message('error', $this->db->last_query());
	    return $result->result();
	}
	
	function getTramaSendByFechaRango($fecha, $rango_horario) {
	    $sql = "	SELECT    lt.*
                	FROM      log_tramas_rpa_sap lt
	        WHERE DATE_FORMAT(lt.fecha, '%d-%m-%Y') = ? ";
	    if($rango_horario == 1){
	        $sql .= "AND DATE_FORMAT(fecha, '%H:%i:%s') > '09:50:00' AND DATE_FORMAT(fecha, '%H:%i:%s') <= '14:50:00' ";
	    }else if($rango_horario == 2){
	        $sql .= "AND DATE_FORMAT(fecha, '%H:%i:%s') > '14:50:00' AND DATE_FORMAT(fecha, '%H:%i:%s') <= '17:50:00' ";
	    }else if($rango_horario == 3){
	        $sql .= "AND DATE_FORMAT(fecha, '%H:%i:%s') > '17:50:00' AND DATE_FORMAT(fecha, '%H:%i:%s') <= '20:50:00' ";
	    }
                
            $sql .= "AND lt.estado = 5
                    limit 1";
	     
	    $result = $this->db->query($sql, array($fecha));
	   if($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
	}	
	
	function getCotizacionRpa() {
		$sql = " SELECT DATE_FORMAT(fecha_registro, '%d-%m-%Y') as fecha, count(1) total_dia, 
						SUM(CASE WHEN DATE_FORMAT(fecha_registro, '%H:%i:%s') > '09:50:00' AND DATE_FORMAT(fecha_registro, '%H:%i:%s') <= '14:50:00' AND estado = 1 THEN 1 ELSE 0 END ) as horario_1_ok,
						SUM(CASE WHEN DATE_FORMAT(fecha_registro, '%H:%i:%s') > '09:50:00' AND DATE_FORMAT(fecha_registro, '%H:%i:%s') <= '14:50:00' AND estado = 2 THEN 1 ELSE 0 END ) as horario_1_no,
						SUM(CASE WHEN DATE_FORMAT(fecha_registro, '%H:%i:%s') > '14:50:00' AND DATE_FORMAT(fecha_registro, '%H:%i:%s') <= '17:50:00' AND estado = 1 THEN 1 ELSE 0 END ) as horario_2_ok,
						SUM(CASE WHEN DATE_FORMAT(fecha_registro, '%H:%i:%s') > '14:50:00' AND DATE_FORMAT(fecha_registro, '%H:%i:%s') <= '17:50:00' AND estado = 2 THEN 1 ELSE 0 END ) as horario_2_no,
						SUM(CASE WHEN DATE_FORMAT(fecha_registro, '%H:%i:%s') > '17:50:00' AND DATE_FORMAT(fecha_registro, '%H:%i:%s') <= '20:50:00' AND estado = 1 THEN 1 ELSE 0 END ) as horario_3_ok,
						SUM(CASE WHEN DATE_FORMAT(fecha_registro, '%H:%i:%s') > '17:50:00' AND DATE_FORMAT(fecha_registro, '%H:%i:%s') <= '20:50:00' AND estado = 2 THEN 1 ELSE 0 END ) as horario_3_no
				   FROM log_tramas_sigoplus 
				  WHERE estado in (1,2)
					AND flg_tipo= 3
				  GROUP BY DATE_FORMAT(fecha_registro, '%d-%m-%Y')
				 ORDER BY fecha_registro DESC LIMIT 7";
		$result = $this->db->query($sql);
		return $result->result();  	
	}

	function getDetalleTramasRpaCotizacion($fecha, $rango_horario, $exitoso) {
		$sql = "SELECT lt.ptr, 
					   lt.fecha_registro,
					   lt.descripcion,
					   (SELECT CONCAT (CASE WHEN pc.flg_robot = 1 THEN 'ROBOT'
											WHEN pc.flg_robot = 2 THEN 'EECC' END,',',pc.sisego ) 
                          FROM planobra_cluster pc
						 WHERE lt.ptr = pc.codigo_cluster limit 1) array_hizo_coti_sisego
					   
				  FROM log_tramas_sigoplus lt
				 WHERE lt.flg_tipo = 3
				   AND DATE_FORMAT(lt.fecha_registro, '%d-%m-%Y') = ? 
				   AND CASE WHEN ? = 1 THEN DATE_FORMAT(lt.fecha_registro, '%H:%i:%s') > '09:50:00' AND DATE_FORMAT(lt.fecha_registro, '%H:%i:%s') <= '14:50:00' 
						    WHEN ? = 2 THEN DATE_FORMAT(lt.fecha_registro, '%H:%i:%s') > '14:50:00' AND DATE_FORMAT(lt.fecha_registro, '%H:%i:%s') <= '17:50:00' 
							WHEN ? = 3 THEN DATE_FORMAT(lt.fecha_registro, '%H:%i:%s') > '17:50:00' AND DATE_FORMAT(lt.fecha_registro, '%H:%i:%s') <= '20:50:00' END
				   AND CASE WHEN ? = 1 THEN lt.estado = 1
				            WHEN ? = 2 THEN lt.estado = 2 END";
				
		// if($rango_horario == 1){
			// $sql .= "AND DATE_FORMAT(lt.fecha_registro, '%H:%i:%s') > '09:50:00' AND DATE_FORMAT(lt.fecha_registro, '%H:%i:%s') <= '14:50:00' ";
		// }else if($rango_horario == 2){
		// $sql .= "AND DATE_FORMAT(lt.fecha_registro, '%H:%i:%s') > '14:50:00' AND DATE_FORMAT(lt.fecha_registro, '%H:%i:%s') <= '17:50:00' ";
		// }else if($rango_horario == 3){
			// $sql .= "AND DATE_FORMAT(lt.fecha_registro, '%H:%i:%s') > '17:50:00' AND DATE_FORMAT(lt.fecha_registro, '%H:%i:%s') <= '20:50:00' ";
		// }
		
		// if($exitoso == 1){
			// $sql .= "AND lt.estado = 1";
		// }else if($exitoso == 2){
			// $sql .= "AND lt.estado = 2";
		// }      		
		$result = $this->db->query($sql, array($fecha, $rango_horario, $rango_horario, $rango_horario, $exitoso, $exitoso));
		return $result->result();
	}
	
	function getVrRpa() {
		$sql = "SELECT DATE_FORMAT(fecha_registro, '%d-%m-%Y') as fecha, count(1) total_dia, 
						SUM(CASE WHEN flg_estado = 1 THEN 1 ELSE 0 END ) as fecha_ok,
						SUM(CASE WHEN flg_estado = 0 THEN 1 ELSE 0 END ) as fecha_no
				  FROM log_solicitud_vr 
				 WHERE dataGet IS NOT NULL
				GROUP BY DATE_FORMAT(fecha_registro, '%d-%m-%Y')
				ORDER BY DATE_FORMAT(fecha_registro, '%Y-%m-%d') DESC LIMIT 7";
		$result = $this->db->query($sql);
		return $result->result();  	
	}
	
	// function getDetalleTramasRpaVr($fecha, $rango_horario, $exitoso) {
	    // $sql = "SELECT dataGet
	              // FROM log_tramas_rpa_sap_vr lt
				 // WHERE DATE_FORMAT(fecha, '%d-%m-%Y') = ? 
			       // AND CASE WHEN ? = 1 THEN lt.estado = 1
				            // WHEN ? = 2 THEN lt.estado = 2 END
				   // AND dataGet IS NOT NULL";        
	    // $result = $this->db->query($sql, array($fecha, $exitoso, $exitoso));
	    // return $result->result_array();
	// }
	
	function getDetalleTramasRpaVr($fecha, $rango_horario, $exitoso) {
	    $sql = "SELECT *
	              FROM log_solicitud_vr lt
				 WHERE DATE_FORMAT(fecha_registro, '%d-%m-%Y') = ? 
			       AND CASE WHEN ? = 1 THEN flg_estado = 1
				            WHEN ? = 2 THEN flg_estado = 0 END
				   AND dataGet IS NOT NULL";        
	    $result = $this->db->query($sql, array($fecha, $exitoso, $exitoso));
	    return $result->result_array();
	}
}




