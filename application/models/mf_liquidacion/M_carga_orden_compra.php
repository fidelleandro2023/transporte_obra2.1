<?php
class M_carga_orden_compra extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
	function   getBandejaAlarmasMO($eecc){
	    $Query = "SELECT 'GESTION DE VENTANILLA UNICA' as descripcion, 2 as origen,
            			 SUM(CASE WHEN mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -3 DAY)) AND NOW() THEN 1 ELSE NULL END) as hasta3,
            			 SUM(CASE WHEN mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -3 DAY)) AND NOW() THEN mo.monto_mo ELSE NULL END) as total_hasta3,
            			 SUM(CASE WHEN mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -7 DAY)) AND DATE(date_add(NOW(), INTERVAL -4 DAY)) THEN 1 ELSE NULL END) as hasta7,
            			 SUM(CASE WHEN mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -7 DAY)) AND DATE(date_add(NOW(), INTERVAL -4 DAY)) THEN mo.monto_mo ELSE NULL END) as total_hasta7,
            			 SUM(CASE WHEN mo.fecha  <=  DATE(date_add(NOW(), INTERVAL -8 DAY)) THEN 1 ELSE NULL END) as todo,
            			 SUM(CASE WHEN mo.fecha  <=  DATE(date_add(NOW(), INTERVAL -8 DAY)) THEN mo.monto_mo ELSE NULL END) as total_todo	           
                FROM    certificacion_mo mo, web_unificada wu
                WHERE	mo.ptr = wu.ptr
                AND 	mo.estado_validado = 0
                AND		mo.estado = ".CERTIFICACION_MO_CON_PRESUPUESTO." ";
             if($eecc!=''){
                 $Query .= "AND wu.eecc = '".$eecc."'";
             }
    $Query .= " UNION ALL 
                SELECT 'GESTION DE INGENIERIA' as descripcion, 1 as origen,
                                         SUM(CASE WHEN mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -3 DAY)) AND NOW() THEN 1 ELSE NULL END) as hasta3,
                            			 SUM(CASE WHEN mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -3 DAY)) AND NOW() THEN mo.monto_mo ELSE NULL END) as total_hasta3,
                            			 SUM(CASE WHEN mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -7 DAY)) AND DATE(date_add(NOW(), INTERVAL -4 DAY)) THEN 1 ELSE NULL END) as hasta7,
                            			 SUM(CASE WHEN mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -7 DAY)) AND DATE(date_add(NOW(), INTERVAL -4 DAY)) THEN mo.monto_mo ELSE NULL END) as total_hasta7,
                            			 SUM(CASE WHEN mo.fecha  <=  DATE(date_add(NOW(), INTERVAL -8 DAY)) THEN 1 ELSE NULL END) as todo,
                            			 SUM(CASE WHEN mo.fecha  <=  DATE(date_add(NOW(), INTERVAL -8 DAY)) THEN mo.monto_mo ELSE NULL END) as total_todo
                FROM
                		certificacion_mo mo, web_unificada wu
                WHERE	mo.ptr = wu.ptr 
                AND 	mo.estado not in (".CERTIFICACION_MO_CON_PRESUPUESTO.",".CERTIFICACION_MO_CON_ORDEN_COMPRA.") ";
    if($eecc!=''){
        $Query .= "AND wu.eecc = '".$eecc."'";
    }                      
   $Query .= "  UNION ALL 
                SELECT 'CERTIFICADO' as descripcion, 3 as origen,
                		 SUM(CASE WHEN mo.fec_reg_oc  BETWEEN  DATE(date_add(NOW(), INTERVAL -3 DAY)) AND NOW() THEN 1 ELSE NULL END) as hasta3,
                		 SUM(CASE WHEN mo.fec_reg_oc  BETWEEN  DATE(date_add(NOW(), INTERVAL -3 DAY)) AND NOW() THEN mo.monto_mo ELSE NULL END) as total_hasta3,
                		 SUM(CASE WHEN mo.fec_reg_oc  BETWEEN  DATE(date_add(NOW(), INTERVAL -7 DAY)) AND DATE(date_add(NOW(), INTERVAL -4 DAY)) THEN 1 ELSE NULL END) as hasta7,
                		 SUM(CASE WHEN mo.fec_reg_oc  BETWEEN  DATE(date_add(NOW(), INTERVAL -7 DAY)) AND DATE(date_add(NOW(), INTERVAL -4 DAY)) THEN mo.monto_mo ELSE NULL END) as total_hasta7,
                		 SUM(CASE WHEN mo.fec_reg_oc  <=  DATE(date_add(NOW(), INTERVAL -8 DAY)) THEN 1 ELSE NULL END) as todo,
                		 SUM(CASE WHEN mo.fec_reg_oc  <=  DATE(date_add(NOW(), INTERVAL -8 DAY)) THEN mo.monto_mo ELSE NULL END) as total_todo
                FROM
                		certificacion_mo mo, web_unificada wu
                WHERE	mo.ptr = wu.ptr 
                AND 	mo.estado = ".CERTIFICACION_MO_CON_ORDEN_COMPRA."
                AND		mo.fec_reg_oc  BETWEEN  DATE(date_add(NOW(), INTERVAL -30 DAY)) AND NOW() ";
    if($eecc!=''){
        $Query .= "AND wu.eecc = '".$eecc."'";
    } 
   
	    $result = $this->db->query($Query,array());

	    return $result;
	}
	
	function   getDetalleCertificacionMOGI($rango_fecha, $eecc){
	    $Query = "SELECT  (CASE WHEN mo.estado = 2 THEN 'SIN PRESUPUESTO'
            			 WHEN mo.estado = 3 THEN 'SIN CONFIGURACION'
            			 WHEN mo.estado = 4 THEN 'SIN ITEMPLAN' END ) as situacion,  mo.itemplan, p.proyectoDesc, sp.subProyectoDesc, mo.ptr, mo.monto_mo, mo.areaDesc, wu.jefatura, 
                		wu.eecc, mo.orden_compra, mo.nro_certificacion, mo.hoja_gestion
                FROM    web_unificada wu, certificacion_mo mo LEFT JOIN planobra po ON mo.itemplan = po.itemplan
                LEFT JOIN subproyecto sp ON po.idSubProyecto = sp.idSubProyecto LEFT JOIN proyecto p ON p.idProyecto = sp.idProyecto
                WHERE	mo.ptr = wu.ptr
                AND		mo.estado_validado = 0
                AND		mo.estado  NOT IN (".CERTIFICACION_MO_CON_PRESUPUESTO.",".CERTIFICACION_MO_CON_ORDEN_COMPRA.")";
	    if($eecc!=''){
	        $Query .= " AND wu.eecc = '".$eecc."'";
	    }
	    if($rango_fecha    ==  1){
	        $Query .=  " AND mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -3 DAY)) AND NOW()";
	    }else  if($rango_fecha    ==  2){
	        $Query .=  " AND mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -7 DAY)) AND DATE(date_add(NOW(), INTERVAL -4 DAY))";
	    }else  if($rango_fecha    ==  3){
	        $Query .=  " AND mo.fecha  <=  DATE(date_add(NOW(), INTERVAL -8 DAY))";
	    }
	     
	    $result = $this->db->query($Query,array());	
	    return $result;
	}
    
	function   getDetalleCertificacionMOGVU($rango_fecha, $eecc){
	    $Query = "SELECT (CASE WHEN mo.estado_validado = 0 THEN 'SIN H.G'
                    	       WHEN mo.estado_validado = 1 THEN 'CON H.G' END ) as situacion, mo.itemplan, p.proyectoDesc, sp.subProyectoDesc, mo.ptr, mo.monto_mo, mo.areaDesc, wu.jefatura, 
                    wu.eecc, mo.orden_compra, mo.nro_certificacion, mo.hoja_gestion
                    FROM    certificacion_mo mo, web_unificada wu, planobra po, subproyecto sp, proyecto p
                    WHERE	mo.ptr = wu.ptr
                    AND		mo.itemplan = po.itemplan
                    AND 	po.idSubProyecto = sp.idSubProyecto
                    AND		p.idProyecto = sp.idProyecto
                    AND		mo.estado = ".CERTIFICACION_MO_CON_PRESUPUESTO." ";
	    if($eecc!=''){
	        $Query .= " AND wu.eecc = '".$eecc."'";
	    }
	    if($rango_fecha    ==  1){
	        $Query .=  " AND mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -3 DAY)) AND NOW()";
	    }else  if($rango_fecha    ==  2){
	        $Query .=  " AND mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -7 DAY)) AND DATE(date_add(NOW(), INTERVAL -4 DAY))";
	    }else  if($rango_fecha    ==  3){
	        $Query .=  " AND mo.fecha  <=  DATE(date_add(NOW(), INTERVAL -8 DAY))";
	    }
	
	        $result = $this->db->query($Query,array());
	        return $result;
	}
	
	function   getDetalleCertificacionMOCertificado($rango_fecha, $eecc){
	    $Query = "SELECT   'CON O.C' as situacion, mo.itemplan, p.proyectoDesc, sp.subProyectoDesc, mo.ptr, mo.monto_mo, mo.areaDesc, wu.jefatura, 
                            wu.eecc, mo.orden_compra, mo.nro_certificacion, mo.hoja_gestion
                    FROM    certificacion_mo mo, web_unificada wu, planobra po, subproyecto sp, proyecto p
                    WHERE	mo.ptr = wu.ptr
                    AND		mo.itemplan = po.itemplan
                    AND 	po.idSubProyecto = sp.idSubProyecto
                    AND		p.idProyecto = sp.idProyecto
                    AND		mo.estado = ".CERTIFICACION_MO_CON_ORDEN_COMPRA."
                    AND		mo.fec_reg_oc  BETWEEN  DATE(date_add(NOW(), INTERVAL -30 DAY)) AND NOW() ";
	    if($eecc!=''){
	        $Query .= " AND wu.eecc = '".$eecc."'";
	    }
	    if($rango_fecha    ==  1){
	        $Query .=  " AND mo.fec_reg_oc  BETWEEN  DATE(date_add(NOW(), INTERVAL -3 DAY)) AND NOW()";
	    }else  if($rango_fecha    ==  2){
	        $Query .=  " AND mo.fec_reg_oc  BETWEEN  DATE(date_add(NOW(), INTERVAL -7 DAY)) AND DATE(date_add(NOW(), INTERVAL -4 DAY))";
	    }else  if($rango_fecha    ==  3){
	        $Query .=  " AND mo.fec_reg_oc  <=  DATE(date_add(NOW(), INTERVAL -8 DAY))";
	    }
	
	        $result = $this->db->query($Query,array());
	        return $result;
	}
	
	function getInfoPtr($ptr){
	    $Query = " SELECT ptr, orden_compra, hoja_gestion FROM certificacion_mo WHERE ptr = ?";
	    $result = $this->db->query($Query,array($ptr));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
	function liquidarOCptrCertificacion($arrayPtr) {
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $this->db->update_batch('certificacion_mo',$arrayPtr, 'ptr');
	        if ($this->db->trans_status() === FALSE) {
	            $this->db->trans_rollback();
	            throw new Exception('Hubo un error al actualizar el liquidarOCptrCertificacion.');
	        }else{
	            $data['error'] = EXIT_SUCCESS;
	            $data['msj'] = 'Se actualizo correctamente!';
	            $this->db->trans_commit();
	        }
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}
	
}