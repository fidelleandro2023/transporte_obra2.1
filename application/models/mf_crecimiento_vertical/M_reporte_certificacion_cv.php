<?php
class M_reporte_certificacion_cv extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
	function   getReporteCertificacion($eecc, $fechaInicio, $fechaFin, $isLima, $tipoPartida){
	    $Query = " SELECT cv.descripcion, ROUND(monto,2) as monto, sum(cantidad) as cantidad, ROUND(sum(total),2) as total, count(1)
                    FROM partida_itemplan pi, partidas_cv cv, planobra po, central c, itemplan_material im, ficha_tecnica ft 
	               WHERE   pi.id_item_partida = cv.id_item_partida
                    AND pi.itemplan = po.itemplan
                    AND pi.itemplan = im.itemplan
                    AND po.idCentral = c.idCentral
	                AND im.itemplan = ft.itemplan
                    AND ft.estado_validacion = 1                     
                    AND pi.idTipoPartida = ? ";
					
		if($tipoPartida == 3){//OC
	        $Query .= " AND ft.id_estacion = 6";
	    }else{//FO
	        $Query .= " AND ft.id_estacion = 5";
	    }
	    if($isLima){
	        $Query .=" and c.jefatura = 'LIMA' ";
	    }else{
	        $Query .=" and c.jefatura != 'LIMA' ";
	    }       
	    
	    $idEECC = $this->session->userdata('eeccSession');
	    
	    if( $idEECC != ID_EECC_TDP &&    $idEECC != ID_EECC_NA ){
	        if($eecc != null){
	            if($eecc != $idEECC){
	                $Query .=" AND pi.idEmpresaColab = ''";
	            }else{
	                $Query .=" AND pi.idEmpresaColab = '".$idEECC."'";
	            }
	        }else{
	            $Query .=" AND pi.idEmpresaColab = '".$idEECC."'";
	        }
	    }else{
	        if($eecc != null){
	            $Query .=" AND pi.idEmpresaColab = '".$eecc."'";
	        }
	    }
	    
	    if($fechaInicio!=null && $fechaFin !=null){	       
	        $Query .=" AND  DATE_FORMAT(ft.fecha_validacion, '%Y-%m-%d') >= '".$fechaInicio."' AND DATE_FORMAT(ft.fecha_validacion, '%Y-%m-%d') <= '".$fechaFin."' ";	       
	    }	    
	    $Query .= " GROUP BY cv.descripcion, monto";
	    $result = $this->db->query($Query,array($tipoPartida));
		log_message('error', 'last-->'.$this->db->last_query());
	    return $result;
	}
	
	function cantidadItemPlan($eecc, $fechaInicio, $fechaFin, $isLima, $tipoPartida) {
	    $Query=" SELECT COUNT(DISTINCT pi.itemplan) as num
                	FROM partida_itemplan pi, partidas_cv cv, planobra po, central c, itemplan_material im, ficha_tecnica ft 
	                WHERE  pi.id_item_partida = cv.id_item_partida
                	AND    pi.itemplan = po.itemplan
                	AND    pi.itemplan = im.itemplan
                	AND    po.idCentral = c.idCentral
	                AND    im.itemplan = ft.itemplan
                    AND    ft.estado_validacion = 1
                	#AND    c.jefatura = 'LIMA'
            	    AND    pi.idTipoPartida = ? ";
	    if($isLima){
	        $Query .=" and c.jefatura = 'LIMA' ";
	    }else{
	        $Query .=" and c.jefatura != 'LIMA' ";
	    }
	    
	    $idEECC = $this->session->userdata('eeccSession');
	     
	    if( $idEECC != ID_EECC_TDP &&    $idEECC != ID_EECC_NA ){
	        if($eecc != null){
	            if($eecc != $idEECC){
	                $Query .=" AND pi.idEmpresaColab = ''";
	            }else{
	                $Query .=" AND pi.idEmpresaColab = '".$idEECC."'";
	            }
	        }else{
	            $Query .=" AND pi.idEmpresaColab = '".$idEECC."'";
	        }
	    }else{
	        if($eecc != null){
	            $Query .=" AND pi.idEmpresaColab = '".$eecc."'";
	        }
	    }
	    
	    if($fechaInicio!=null && $fechaFin !=null){
	        $Query .=" AND  DATE_FORMAT(ft.fecha_validacion, '%Y-%m-%d') >= '".$fechaInicio."' AND DATE_FORMAT(ft.fecha_validacion, '%Y-%m-%d') <= '".$fechaFin."' ";
	    }
	    $result = $this->db->query($Query, array($tipoPartida));
		//log_message('error', 'last-->'.$this->db->last_query());
	    return $result->row()->num;
	}
	
	
	
}