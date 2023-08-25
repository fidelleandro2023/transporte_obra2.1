<?php
class M_control_atencion_cotizacion extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
    }

    function getTablaTramaAtencionCotizacion() {
        $sql = " SELECT t.estado,
                        t.estado_desc,
                        t.menor_6h,
                        t.7h_12h,
                        t.13h_24h_exitoso,
                        25h_48h_exitoso,
                        mayor_48h_exitoso,
                        t.menor_6h+ t.7h_12h + t.13h_24h_exitoso + 25h_48h_exitoso +  mayor_48h_exitoso AS sumTotal,
                        t.total
                        
                FROM (
                
                        SELECT CASE WHEN estado = 0 THEN 'PENDIENTE'
                                    WHEN estado = 1 THEN 'PDTE APROBACION'
                                    WHEN estado = 2 THEN 'APROBADO' 
                                   -- WHEN estado = 3 tHEN 'RECHAZADO' 
                                    WHEN estado = 4 THEN 'PENDIENTE DE CONFIRMACION'END AS estado_desc,
                                estado,    
                                COUNT(1) AS total,
                                SUM(CASE WHEN HOUR(TIMEDIFF(NOW(), fecha_registro))  <=  '06' AND estado = 0 THEN 1
                                        WHEN HOUR(TIMEDIFF(NOW(), fecha_envio_cotizacion))  <=  '06' AND estado = 1 THEN 1
                                        WHEN HOUR(TIMEDIFF(NOW(), fecha_aprobacion))  <=  '06' AND estado = 2 THEN 1 
                                        WHEN HOUR(TIMEDIFF(NOW(), fecha_aprobacion))  <=  '06' AND estado = 3 THEN 1 
                                        WHEN HOUR(TIMEDIFF(NOW(), fecha_envio_bandeja_val))  <=  '06' AND estado = 4 THEN 1 
                                        ELSE 0 END) menor_6h,
                                SUM(CASE WHEN HOUR(TIMEDIFF(NOW(), fecha_registro))  >  '06' AND HOUR(TIMEDIFF(NOW(), fecha_registro))  <=  '12' AND estado = 0 THEN 1 
                                        WHEN HOUR(TIMEDIFF(NOW(), fecha_envio_cotizacion))  >  '06' AND HOUR(TIMEDIFF(NOW(), fecha_envio_cotizacion))  <=  '12' AND estado = 1 THEN 1
                                        WHEN HOUR(TIMEDIFF(NOW(), fecha_aprobacion))  >  '06' AND HOUR(TIMEDIFF(NOW(), fecha_aprobacion))  <=  '12' AND estado = 2 THEN 1
                                        WHEN HOUR(TIMEDIFF(NOW(), fecha_aprobacion))  >  '06' AND HOUR(TIMEDIFF(NOW(), fecha_aprobacion))  <=  '12' AND estado = 3 THEN 1
                                        WHEN HOUR(TIMEDIFF(NOW(), fecha_envio_bandeja_val))  >  '06' AND HOUR(TIMEDIFF(NOW(), fecha_envio_bandeja_val))  <=  '12' AND estado = 4 THEN 1
                                       
                                        ELSE 0 END) AS 7h_12h,
                                SUM(CASE WHEN HOUR(TIMEDIFF(NOW(), fecha_registro))  >  '12' AND HOUR(TIMEDIFF(NOW(), fecha_registro))  <=  '24' AND estado = 0 THEN 1 
                                        WHEN HOUR(TIMEDIFF(NOW(), fecha_envio_cotizacion))  >  '12' AND HOUR(TIMEDIFF(NOW(), fecha_envio_cotizacion))  <=  '24' AND estado = 1 THEN 1
                                        WHEN HOUR(TIMEDIFF(NOW(), fecha_aprobacion))  >  '12' AND HOUR(TIMEDIFF(NOW(), fecha_aprobacion))  <=  '24' AND estado = 2 THEN 1
                                        WHEN HOUR(TIMEDIFF(NOW(), fecha_aprobacion))  >  '12' AND HOUR(TIMEDIFF(NOW(), fecha_aprobacion))  <=  '24' AND estado = 3 THEN 1
                                        WHEN HOUR(TIMEDIFF(NOW(), fecha_envio_bandeja_val))  >  '12' AND HOUR(TIMEDIFF(NOW(), fecha_envio_bandeja_val))  <=  '24' AND estado = 4 THEN 1
                                        ELSE 0 END) AS 13h_24h_exitoso,         
                                SUM(CASE WHEN HOUR(TIMEDIFF(NOW(), fecha_registro))  >  '24' AND HOUR(TIMEDIFF(NOW(), fecha_registro))  <=  '48' AND estado = 0 THEN 1 
                                        WHEN HOUR(TIMEDIFF(NOW(), fecha_envio_cotizacion))  >  '24' AND HOUR(TIMEDIFF(NOW(), fecha_envio_cotizacion))  <=  '48' AND estado = 1 THEN 1
                                        WHEN HOUR(TIMEDIFF(NOW(), fecha_aprobacion))  >  '24' AND HOUR(TIMEDIFF(NOW(), fecha_aprobacion))  <=  '48' AND estado = 2 THEN 1
                                        WHEN HOUR(TIMEDIFF(NOW(), fecha_aprobacion))  >  '24' AND HOUR(TIMEDIFF(NOW(), fecha_aprobacion))  <=  '48' AND estado = 3 THEN 1
                                        WHEN HOUR(TIMEDIFF(NOW(), fecha_envio_bandeja_val))  >  '24' AND HOUR(TIMEDIFF(NOW(), fecha_envio_bandeja_val))  <=  '48' AND estado = 4 THEN 1
                                        ELSE 0 END) AS 25h_48h_exitoso,
                                SUM(CASE WHEN HOUR(TIMEDIFF(NOW(), fecha_registro))  >=  '49' AND estado = 0 THEN 1 
                                        WHEN HOUR(TIMEDIFF(NOW(), fecha_envio_cotizacion))  >=  '49' AND estado = 1 THEN 1 
                                        WHEN HOUR(TIMEDIFF(NOW(), fecha_aprobacion))  >=  '49' AND estado = 2 THEN 1 
                                        WHEN HOUR(TIMEDIFF(NOW(), fecha_aprobacion))  >=  '49' AND estado = 3 THEN 1 
                                        WHEN HOUR(TIMEDIFF(NOW(), fecha_envio_bandeja_val))  >=  '49' AND estado = 4 THEN 1 
                                        ELSE 0 END) AS mayor_48h_exitoso
                        FROM planobra_cluster
                       WHERE estado <> 3 
                        GROUP BY estado
                    )t
                    ORDER BY t.estado ASC";
        $result = $this->db->query($sql);
        return $result->result_array();
    }

    function getDetalleCotiAten($estado, $intervalo_h) {
        $sql = "SELECT codigo_cluster,
                       cliente,
                       latitud,
                       longitud,
                       CASE WHEN estado = 0 THEN fecha_registro
                            WHEN estado = 1 THEN fecha_envio_cotizacion
                            WHEN estado = 2 THEN fecha_aprobacion
                            WHEN estado = 3 THEN fecha_aprobacion 
                            WHEN estado = 4 THEN fecha_envio_bandeja_val 
                            ELSE '-' END fecha
                  FROM planobra_cluster
                 WHERE estado = ?
                   AND CASE WHEN ? = 1 AND ? = 0 THEN HOUR(TIMEDIFF(NOW(), fecha_registro))  <=  '06'
                            WHEN ? = 1 AND ? = 1 THEN HOUR(TIMEDIFF(NOW(), fecha_envio_cotizacion))  <=  '06'
                            WHEN ? = 1 AND ? IN (2,3) THEN HOUR(TIMEDIFF(NOW(), fecha_aprobacion))  <=  '06'
                            WHEN ? = 1 AND ? = 4 THEN HOUR(TIMEDIFF(NOW(), fecha_envio_bandeja_val))  <=  '06'

                            WHEN ? = 2 AND ? = 0 THEN HOUR(TIMEDIFF(NOW(), fecha_registro))  >  '06' AND HOUR(TIMEDIFF(NOW(), fecha_registro))  <=  '12'
                            WHEN ? = 2 AND ? = 1 THEN HOUR(TIMEDIFF(NOW(), fecha_envio_cotizacion))  >  '06' AND HOUR(TIMEDIFF(NOW(), fecha_envio_cotizacion))  <=  '12'
                            WHEN ? = 2 AND ? IN (2,3) THEN HOUR(TIMEDIFF(NOW(), fecha_aprobacion))  >  '06' AND HOUR(TIMEDIFF(NOW(), fecha_aprobacion))  <=  '12'
                            WHEN ? = 2 AND ? = 4 THEN HOUR(TIMEDIFF(NOW(), fecha_envio_bandeja_val))  >  '06' AND HOUR(TIMEDIFF(NOW(), fecha_envio_bandeja_val))  <=  '12'

                            WHEN ? = 3 AND ? = 0 THEN HOUR(TIMEDIFF(NOW(), fecha_registro))  >  '12' AND HOUR(TIMEDIFF(NOW(), fecha_registro))  <=  '24'
                            WHEN ? = 3 AND ? = 1 THEN HOUR(TIMEDIFF(NOW(), fecha_envio_cotizacion))  >  '12' AND HOUR(TIMEDIFF(NOW(), fecha_envio_cotizacion))  <=  '24'
                            WHEN ? = 3 AND ? IN (2,3) THEN HOUR(TIMEDIFF(NOW(), fecha_aprobacion))  >  '12' AND HOUR(TIMEDIFF(NOW(), fecha_aprobacion))  <=  '24'
                            WHEN ? = 3 AND ? = 4 THEN HOUR(TIMEDIFF(NOW(), fecha_envio_bandeja_val))  >  '12' AND HOUR(TIMEDIFF(NOW(), fecha_envio_bandeja_val))  <=  '24'

                            WHEN ? = 4 AND ? = 0 THEN HOUR(TIMEDIFF(NOW(), fecha_registro))  >  '24' AND HOUR(TIMEDIFF(NOW(), fecha_registro))  <=  '48'
                            WHEN ? = 4 AND ? = 1 THEN HOUR(TIMEDIFF(NOW(), fecha_envio_cotizacion))  >  '24' AND HOUR(TIMEDIFF(NOW(), fecha_envio_cotizacion))  <=  '48'
                            WHEN ? = 4 AND ? IN (2,3) THEN HOUR(TIMEDIFF(NOW(), fecha_aprobacion))  >  '24' AND HOUR(TIMEDIFF(NOW(), fecha_aprobacion))  <=  '48'
                            WHEN ? = 4 AND ? = 3 THEN HOUR(TIMEDIFF(NOW(), fecha_envio_bandeja_val))  >  '24' AND HOUR(TIMEDIFF(NOW(), fecha_envio_bandeja_val))  <=  '48'

                            WHEN ? = 5 AND ? = 0 THEN HOUR(TIMEDIFF(NOW(), fecha_registro))  >=  '49' 
                            WHEN ? = 5 AND ? = 1 THEN HOUR(TIMEDIFF(NOW(), fecha_envio_cotizacion))  >=  '49'
                            WHEN ? = 5 AND ? IN (2,3) THEN HOUR(TIMEDIFF(NOW(), fecha_aprobacion))  >=  '49'
                            WHEN ? = 5 AND ? = 4 THEN HOUR(TIMEDIFF(NOW(), fecha_envio_bandeja_val))  >=  '49' END";
        $result = $this->db->query($sql, array( $estado, $intervalo_h, $estado, $intervalo_h, $estado, $intervalo_h, $estado, $intervalo_h, $estado,
                                                $intervalo_h, $estado, $intervalo_h, $estado, $intervalo_h, $estado, $intervalo_h, $estado,
                                                $intervalo_h, $estado, $intervalo_h, $estado, $intervalo_h, $estado, $intervalo_h, $estado,
                                                $intervalo_h, $estado, $intervalo_h, $estado, $intervalo_h, $estado, $intervalo_h, $estado,
                                                $intervalo_h, $estado, $intervalo_h, $estado, $intervalo_h, $estado, $intervalo_h, $estado));
        return $result->result_array();
    }
}