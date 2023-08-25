<?php
class M_bandeja_certificacion_cv extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
	function   getAllPreCertificacionCVDet($eecc, $fechaInicio, $fechaFin, $itemplan,$idFase = null){
	    $Query = " SELECT pi.itemplan, ec.empresaColabDesc, DATE_FORMAT(ft.fecha_validacion , '%d-%m-%Y') as fecha_registro, 
                    	   (CASE WHEN pi.idTipoPartida = 1 THEN 'SUBTERRANEO'
                    			 WHEN pi.idTipoPartida = 2 THEN 'AEREO' 
	                             WHEN pi.idTipoPartida = 3 THEN 'OBRA CIVIL'
                            END) AS tipo_partida , im.instalacion_cto,            
                            cv.descripcion, pdcv.depa, pi.monto, pi.cantidad, pi.total, f.faseDesc
                    FROM    partida_itemplan pi, planobra po, empresacolab ec, partidas_cv cv, planobra_detalle_cv pdcv,  fase f, ficha_tecnica ft LEFT JOIN itemplan_material im ON im.itemplan = ft.itemplan
                    WHERE   pi.itemplan = po.itemplan
                    AND     po.idFase = f.idFase
                    AND     pi.idEmpresaColab = ec.idEmpresaColab
                    AND     pi.id_item_partida = cv.id_item_partida
                    #AND    pi.itemplan = im.itemplan
                    AND     pi.itemplan = pdcv.itemplan
	                #AND 	im.itemplan = ft.itemplan
	                AND     ft.itemplan = pi.itemplan
	                AND     ft.id_estacion = pi.idEstacion
					AND 	ft.estado_validacion = 1 " ;
	    
	    if($idFase!= null){
	        $Query .=" AND po.idFase = '".$idFase."'";
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
	    
	    if($itemplan!= null){
	        $Query .=" AND pi.itemplan = '".$itemplan."'";
	    }
	    
	    if($fechaInicio!=null && $fechaFin !=null){	       
	        $Query .=" AND  DATE_FORMAT(ft.fecha_validacion, '%Y-%m-%d') >= '".$fechaInicio."' AND DATE_FORMAT(ft.fecha_validacion, '%Y-%m-%d') <= '".$fechaFin."' ";	       
	    }
	    $result = $this->db->query($Query,array());
		//log_message('error', 'DETALLE->'.$this->db->last_query());
	    return $result;
	}
	
	
	function   getAllPreCertificacionCV($eecc, $fechaInicio, $fechaFin, $itemplan,$idFase = null){
	    $Query = " SELECT tb.*, (CASE WHEN tb.idTipoPartida = 1 THEN SUM(imc.total * m.costo_material) ELSE 0 END ) as total_mat, im.instalacion_cto, im.camara  FROM (SELECT pi.itemplan, ec.empresaColabDesc,f.faseDesc, DATE_FORMAT(ft.fecha_validacion , '%d-%m-%Y') as fecha_registro, 
                    	   (CASE WHEN pi.idTipoPartida = 1 THEN 'SUBTERRANEO'
                    			 WHEN pi.idTipoPartida = 2 THEN 'AEREO' 
	                             WHEN pi.idTipoPartida = 3 THEN 'OBRA CIVIL'
                            END) AS tipo_partida , pdcv.depa,            
                            SUM(pi.total) as total_mo, (CASE WHEN ft.estado_validacion = 1 then 'VALIDADO' ELSE 'PENDIENTE' END) as validado,
                            pi.idTipoPartida,
							pi.idEstacion
                            
                    FROM    partida_itemplan pi, planobra po, empresacolab ec, partidas_cv cv, planobra_detalle_cv pdcv, ficha_tecnica ft,fase f
                    WHERE   pi.itemplan = po.itemplan
                    AND     po.idFase = f.idFase
                    AND     pi.idEmpresaColab = ec.idEmpresaColab
                    AND     pi.id_item_partida = cv.id_item_partida
                    AND     pi.itemplan = pdcv.itemplan        
	                AND     ft.itemplan = pi.itemplan            
	                AND     ft.id_estacion = pi.idEstacion
	                AND 	ft.estado_validacion = 1 ";
	                
    	if($idFase!= null){
    	    $Query .=" AND po.idFase = '".$idFase."'";
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
	     
	    if($itemplan!= null){
	        $Query .=" AND pi.itemplan = '".$itemplan."'";
	    }
	     
	    if($fechaInicio!=null && $fechaFin !=null){
	        $Query .=" AND  DATE_FORMAT(ft.fecha_validacion, '%Y-%m-%d') >= '".$fechaInicio."' AND DATE_FORMAT(ft.fecha_validacion, '%Y-%m-%d') <= '".$fechaFin."' ";
	    }
	    
	    $Query .=  ' GROUP BY pi.itemplan, pi.idTipoPartida, pi.idEstacion) as tb 
            		LEFT JOIN itemplan_material im 
            				JOIN 	itemplan_material_cantidad imc ON  im.id_itemplan_material = imc.id_itemplan_material 
            				JOIN 	material m ON imc.id_material = m.id_material
            		ON	tb.itemplan = im.itemplan
                    GROUP BY tb.itemplan, tb.idTipoPartida, tb.idEstacion';
	    
	    $result = $this->db->query($Query,array());
	    return $result;
	}
	
	function   getDataItemplanCertificacion($itemplan, $idEstacion){
	    $Query = " SELECT   cv.descripcion, pi.monto, pi.cantidad, pi.total
                	FROM    partida_itemplan pi, planobra po, empresacolab ec, itemplan_material im, partidas_cv cv
                	WHERE   pi.itemplan = po.itemplan
                	AND     pi.idEmpresaColab = ec.idEmpresaColab
                	AND     pi.id_item_partida = cv.id_item_partida
                	AND     pi.itemplan = im.itemplan
                	AND	    pi.itemplan = ?
					AND     pi.idEstacion = ?";
	    $result = $this->db->query($Query,array($itemplan, $idEstacion));
	    return $result;
	}
	
	function getInfoItemplanRepo($itemplan){
	    $Query = "SELECT pi.itemPlan, 
                        (CASE WHEN pi.idTipoPartida = 1 THEN 'SUBTERRANEO'
						      WHEN pi.idTipoPartida = 2 THEN 'AEREO' END) as tipoPartida, 
                        ec.empresaColabDesc,
                        im.instalacion_cto, im.microcanolizado, 
                        cv.depa,imc.id_material, 
                        m.descrip_material, 
                        imc.total as total_mat,
	                    im.camara
                    FROM partida_itemplan pi, empresacolab ec, itemplan_material im, planobra_detalle_cv cv, itemplan_material_cantidad imc, material m
                    WHERE pi.idEmpresaColab = ec.idEmpresaColab
                    AND pi.itemplan = im.itemplan
                    AND im.itemplan = cv.itemplan
                    AND im.id_itemplan_material = imc.id_itemplan_material
                    AND imc.id_material = '10402530004'
                    AND imc.id_material = m.id_material
                    AND pi.itemplan = ? LIMIT 1;";
	    $result = $this->db->query($Query,array($itemplan));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
	function   getDataToReportCVMateriales(){
	    $Query = ' SELECT   im.itemplan, ec.empresaColabDesc, 
                    		DATE_FORMAT(im.fecha_registro,"%d/%m/%Y") as fecha_registro, 
                    		(CASE 	WHEN im.idTipoPartida = 1 THEN "SUBTERRANEO"
                    				WHEN im.idTipoPartida = 2 THEN "AEREO" END) as tipo_partida, 
                    		im.instalacion_cto,
                    		pdcv.depa, 
                            m.id_material,
                    		m.descrip_material, 
                            ROUND(m.costo_material,2) AS costo_material, 
                    		imc.total, 		
                    		ROUND((imc.total*m.costo_material),2) AS costo_total,f.faseDesc
                            
                    FROM 	itemplan_material im, itemplan_material_cantidad imc, 
                    		material m, planobra_detalle_cv pdcv, planobra po, central c,
                            empresacolab ec, fase f
                    WHERE 	im.id_itemplan_material = imc.id_itemplan_material
                    AND 	im.itemplan = pdcv.itemplan
                    AND 	im.itemplan = po.itemplan
                    AND 	po.idCentral = c.idCentral
                    AND     po.idFase = f.idFase
                    AND 	imc.id_material = m.id_material
                    AND		ec.idEmpresaColab = c.idEmpresaColabCV;';
	    $result = $this->db->query($Query,array());
	    return $result;
	}
	
}