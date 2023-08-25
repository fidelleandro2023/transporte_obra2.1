<?php
class M_bandeja_pendientes extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	function getTablePrincipal(){
        $Query = "/* SELECT 
                    *,SUM(ptraz.costo_mo) as costo_mo, SUM(ptraz.costo_mat) as costo_mat, SUM(ptraz.total) as total
                    FROM
                    ptr_planta_interna ppi
                    INNER JOIN planobra po on ppi.itemplan = po.itemPlan
                    INNER JOIN subproyecto sp on po.idSubProyecto = sp.idSubProyecto
                    INNER JOIN tipoplanta tp on sp.idTipoPlanta = tp.idTipoPlanta
                    INNER JOIN central ct on po.idCentral = ct.idCentral
                    INNER JOIN zonal z on po.idZonal = z.idZonal
                    INNER JOIN empresacolab ec on po.idEmpresaColab = ec.idEmpresaColab
                    INNER JOIN ptr_x_actividades_x_zonal ptraz on ppi.ptr = ptraz.ptr 
                    and idEstadoPlan = 3
					AND po.paquetizado_fg IS NULL
                    GROUP BY ppi.ptr
					UNION ALL */
					SELECT 
                    *,SUM(ptraz.costo_mo) as costo_mo, SUM(ptraz.costo_mat) as costo_mat, SUM(ptraz.total) as total
                    FROM
                    ptr_planta_interna ppi
                    INNER JOIN planobra po on ppi.itemplan = po.itemPlan
                    INNER JOIN subproyecto sp on po.idSubProyecto = sp.idSubProyecto
                    INNER JOIN tipoplanta tp on sp.idTipoPlanta = tp.idTipoPlanta
                    INNER JOIN pqt_central ct on po.idCentralPqt = ct.idCentral
                    INNER JOIN zonal z on po.idZonal = z.idZonal
                    INNER JOIN empresacolab ec on po.idEmpresaColab = ec.idEmpresaColab
                    INNER JOIN ptr_x_actividades_x_zonal ptraz on ppi.ptr = ptraz.ptr 
                    and idEstadoPlan = 3
					AND (po.paquetizado_fg = 2 OR po.paquetizado_fg = 1)
					AND po.flg_transporte IS NULL
                    GROUP BY ppi.ptr" ;
        $result = $this->db->query($Query,array());
        return $result;

    }








	function   getPtrConsulta($itemPlan,$nombreproyecto,$nodo,$zonal,$proy,$subPry,$estado,$filtroPrevEjec,$tipoPlanta){
    	
        $Query = "  SELECT p.itemPlan, c.idCentral, p.idSubProyecto, s.subProyectoDesc, p.nombreProyecto, b.empresaColabDesc , c.codigo, c.tipoCentralDesc, e.empresaElecDesc, p.fechaInicio, p.fechaPrevEjec, p.fechaEjecucion, c.idZonal, z.zonalDesc, p.idEstadoPlan, t.estadoPlanDesc, p.hasAdelanto, s.idTipoPlanta
					FROM planobra p, subproyecto s,proyecto py, empresaelec e, central c 
					RIGHT JOIN zonal z 
					ON c.idZonal=z.idZonal 
					RIGHT JOIN empresacolab b on c.idEmpresaColab=b.idEmpresaColab, estadoplan t
					WHERE s.idSubProyecto = p.idSubProyecto and c.idCentral= p.idCentral
					AND p.flg_transporte IS NULL
					AND e.idEmpresaElec= p.idEmpresaElec and p.idEstadoPlan = t.idEstadoPlan and s.idProyecto=py.idProyecto" ;
		if($zonal!=''){
	            if($zonal == 8 || $zonal == 9 || $zonal == 10 || $zonal == 11 || $zonal == 12 ){
	                $Query .= " AND z.idZonal IN (8,9,10,11,12)";
	            }else{
	                $Query .= " AND z.idZonal IN (".$zonal.")";
	            }
        }
		if($proy!=''){
            $Query .= " AND py.idProyecto = ".$proy;
        }
        if($subPry!=''){
            $Query .= " AND s.idSubProyecto = ".$subPry;
        }
		if($itemPlan!=''){
            $Query .= " AND p.itemPlan ='".$itemPlan."' ";
        }
        if($nombreproyecto!=''){
            $Query .= ' AND p.nombreProyecto LIKE "%'.$nombreproyecto.'%"';
            //$Query .= " AND p.nombreProyecto LIKE '%".$nombreproyecto."%' ";
        }
        if($nodo!=''){
            $Query .= " AND c.idCentral = ".$nodo;
        }
        if($estado!=''){
            $Query .= " AND p.idEstadoPlan = ".$estado;
        }        
        if($tipoPlanta!=''){
            $Query .= " AND s.idTipoPlanta = ".$tipoPlanta;
        } 
        if($filtroPrevEjec!=''){
            $Query .= " ".$filtroPrevEjec." ";
        }
        
	    $result = $this->db->query($Query,array());	   
	    return $result;
	}
	
	function insertExpediente($comentario, $usuario){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        //$this->db->query("INSERT INTO expediente (id_expediente, comentario, usuario, fecha_registro) VALUES (NULL, '".$comentario."', '".$usuario."', NOW())");
	        $this->db->query("INSERT INTO expediente (id_expediente, comentario, usuario, fecha_registro) VALUES (NULL, '".$comentario."', '".$usuario."', NOW() )");
	        if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();	                
                $data ['error']= EXIT_SUCCESS;
            }else{
                $this->db->trans_rollback();
            }	
	        
	         
	    }catch(Exception $e){
	        $data['msj']   = 'Error en la transaccion!';
	        $this->db->trans_rollback();
	    }
	    return $data;
	}

	function insertPTR($ptr, $item, $fecsol,$subproyecto,$zonal,$eecc,$area){ 
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        //(SELECT SEC_TO_TIME(TIMESTAMPDIFF(SECOND, STR_TO_DATE('14/12/2017 15:38', '%d/%m/%Y %H:%i:%s'), NOW())))
	        $this->db->trans_begin();
	        $this->db->query("INSERT INTO expediente_ptr (id_expediente, ptr, itemPlan, tpo_espera, subproyecto, zonal, eecc, area) VALUES ((SELECT id_expediente FROM expediente ORDER BY id_expediente DESC LIMIT 1), '".$ptr."', '".$item."',  (SELECT SEC_TO_TIME(TIMESTAMPDIFF(SECOND, STR_TO_DATE('".$fecsol."', '%d/%m/%Y %H:%i:%s'), NOW()))), '".$subproyecto."', '".$zonal."','".$eecc."','".$area."' )");
	        if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();	                
                $data ['error']= EXIT_SUCCESS;
            }else{
                $this->db->trans_rollback();
            }	
	        
	         
	    }catch(Exception $e){
	        $data['msj']   = 'Error en la transaccion!';
	        $this->db->trans_rollback();
	    }
	    return $data;
	}

	function getAllZonalGroup($zonasRest){
		$Query = "SELECT SUBSTRING_INDEX( zonalDesc , ' ', 1 ) as zonalDesc FROM zonal GROUP BY SUBSTRING_INDEX( zonalDesc , ' ', 1 ) ORDER BY zonalDesc" ;
	    $result = $this->db->query($Query,array());
	    return $result;
	}


	
}