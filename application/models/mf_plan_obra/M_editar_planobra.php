<?php
class M_editar_planobra extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
    
   function   getConsultaEditItemPlan($itemPlan,$nombreproyecto,$nodo,$zonal,$proy,$subPry,$estado,$filtroPrevEjec,$tipoPlanta){
    	
        $Query = "  SELECT p.itemPlan, c.idCentral, p.idSubProyecto, s.subProyectoDesc, p.nombreProyecto, b.empresaColabDesc , c.codigo, c.tipoCentralDesc, e.empresaElecDesc, p.fechaInicio, p.fechaPrevEjec, p.fechaEjecucion, c.idZonal, z.zonalDesc, p.idEstadoPlan, t.estadoPlanDesc, p.hasAdelanto, s.idTipoPlanta, py.idProyecto
					FROM planobra p, subproyecto s,proyecto py, empresaelec e,empresacolab b, estadoplan t, central c 
					RIGHT JOIN zonal z 	ON c.idZonal=z.idZonal 
					WHERE s.idSubProyecto = p.idSubProyecto and c.idCentral= p.idCentral 
					 #AND p.idEmpresaColab = b.idEmpresaColab
					 AND (CASE WHEN s.idTipoSubProyecto = 2 THEN b.idEmpresaColab = c.idEmpresaColabCV ELSE 
                        c.idEmpresaColab = b.idEmpresaColab END)
					 AND e.idEmpresaElec= p.idEmpresaElec and p.idEstadoPlan = t.idEstadoPlan and s.idProyecto=py.idProyecto
					 AND (p.paquetizado_fg is null or p.paquetizado_fg = 1) " ;
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
        
		$Query .= " UNION ALL
					SELECT p.itemPlan, c.idCentral, p.idSubProyecto, s.subProyectoDesc, p.nombreProyecto, b.empresaColabDesc , c.codigo, c.tipoCentralDesc, e.empresaElecDesc, p.fechaInicio, p.fechaPrevEjec, p.fechaEjecucion, c.idZonal, z.zonalDesc, p.idEstadoPlan, t.estadoPlanDesc, p.hasAdelanto, s.idTipoPlanta, py.idProyecto
					FROM planobra p, subproyecto s,proyecto py, empresaelec e,empresacolab b, estadoplan t, pqt_central c 
					RIGHT JOIN zonal z 	ON c.idZonal=z.idZonal 
					WHERE s.idSubProyecto = p.idSubProyecto 
					AND c.idCentral= p.idCentralPqt 
					 #AND p.idEmpresaColab = b.idEmpresaColab
					 AND (CASE WHEN s.idTipoSubProyecto = 2 THEN b.idEmpresaColab = c.idEmpresaColabCV ELSE 
                        c.idEmpresaColab = b.idEmpresaColab END)
					 AND e.idEmpresaElec= p.idEmpresaElec and p.idEstadoPlan = t.idEstadoPlan and s.idProyecto=py.idProyecto
					 AND p.paquetizado_fg = 2 " ;
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

	
	function getAllZonal(){
		$Query = "SELECT idZonal, SUBSTRING_INDEX( zonalDesc , ' ', 1 ) as zona 
				FROM zonal " ;
		
        $Query .= "  GROUP BY (zona)";


	    $result = $this->db->query($Query,array());
	    return $result;
	}


	
	/****************************************************EDICIONDE PLAN OBRA LITE*********************************************/

	function   getInfoEditItemplanlite($idPlanobra){
	    $Query = " SELECT indicador, 
	    				(case when trim(indicador)!='' then REPLACE(nombreProyecto,concat(indicador,' - '),'') else nombreProyecto end) as nombreRealProyecto, 
	    				nombreproyecto, 
						idfase, 
						cantidadTroba, 
						uip, 
						coordY,
						coordX,
						(select empresacolab.empresaColabDesc from empresacolab, central  where (CASE WHEN planobra.idSubProyecto IN (97,98, 396, 463) THEN empresacolab.idEmpresaColab=central.idEmpresaColabCV ELSE empresacolab.idEmpresaColab=central.idEmpresaColab END) AND planobra.idCentral = central.idCentral) as empresaColabDesc,
						idEmpresaColabDiseno, 
						idEmpresaElec, 
						cantidadTroba,
						idCentral,
						idZonal 
				    from planobra where itemplan = ?";	
	    $result = $this->db->query($Query,array($idPlanobra));

	    if($result->row() != null) {

	        return $result->row_array();
	    } else {
	        return null;
	    }
	}

	function editarPlanObralite($itemplan,$indicador,$fase, $cordx,$cordy,$nombrePlan,$eelec,$cantidadTroba,$uip,
                                                                $idcentral,$idzonal,
                                                                $idEmpresaColabN, $idEmpresaColabDis){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	    	
	    	$this->db->trans_begin();


	    	if (trim($idEmpresaColabDis)==''){
	    		$regEECCDiseno=null;
	    	}else{
	    		$regEECCDiseno=intval($idEmpresaColabDis);
	    	}

	    	 $dataUpdate = array(
	           
	            "nombreProyecto" => strtoupper($nombrePlan),
	            "coordX" => $cordx,
	            "coordY" => $cordy,
	            "indicador" => $indicador,
	            "cantidadTroba" => intval($cantidadTroba), 
	            "uip" => intval($uip),
	            "idFase" =>intval($fase),
	            "idEmpresaElec" =>intval($eelec),
	            "idCentral" =>intval($idcentral),
	            "idzonal" =>intval($idzonal),
	            "idEmpresaColab" =>intval($idEmpresaColabN),
	            "idEmpresaColabDiseno"=>$regEECCDiseno

	        );


	      	$this->db->where('itemPlan', $itemplan);
        	$this->db->update('planobra', $dataUpdate);

        	if ($this->db->trans_status() === FALSE) {
        	    throw new Exception('Hubo un error al actualizar en editarPlanObraLite');
        	}else{

        	    $data['error']    = EXIT_SUCCESS;
        	    $data['msj']      = 'Se actualizo correctamente!';
        	   
        	    $data['indicador']     =  $indicador;
        	    $data['itemplan']      =  $itemplan;
        	    $this->db->trans_commit();
        	}
      
	    }catch(Exception $e){
    	    $data['msj']   = $e->getMessage();
    	    $this->db->trans_rollback();
	    }
	    return $data;
	}



	
function editarWebUnificadalite($itemplan,$indicador){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	    	
	    	$this->db->trans_begin();

			$where = "itemPlan='".$itemplan."' AND estado_asig_grafo!=2";

    		$this->db->set('indicador',$indicador);
    		$this->db->where($where);
    		$this->db->update('web_unificada_det');

        	if ($this->db->trans_status() === FALSE) {
        	    throw new Exception('Hubo un error al actualizar en editarWebUnificadalite');
        	}else{
        	    $data['error']    = EXIT_SUCCESS;
        	    $data['msj']      = 'Se actualizo correctamente!';
        	    $this->db->trans_commit();
        	}
      
	    }catch(Exception $e){
    	    $data['msj']   = $e->getMessage();
    	    $this->db->trans_rollback();
	    }
	    return $data;
	}


function getInfoEditWUDetlite($idPlanobra){
	    $Query = " SELECT ptr 
				    from web_unificada_det where estado_asig_grafo!=2 and itemplan = ?";	
	    $result = $this->db->query($Query,array($idPlanobra));
	    if($result->row() != null) {
	        return $result;
	    } else {
	        return null;
	    }
	}

function getEmpresaColabBucle($idcentral){
	$Query = " SELECT idEmpresaColab from central where idCentral= ?";	
	    $result = $this->db->query($Query,array($idcentral));
		$idempresaC =$result->row()->idEmpresaColab;
		return $idempresaC ;
}




/***********************************************************************************************************************/

function insertarLogPlanObra($itemplan,$idusuario, $modificaciones,$tabla,$ptr,$modificaptr){
		$data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	    	
	    	$this->db->trans_begin();
   		    	
   		    $fechainicio=date("Y-m-d H:i:s");
  	 
       		$dataInsert = array(
	            
	            "tabla" => $tabla,
	            "actividad" => "update",
	            "itemplan" => $itemplan,
	            "itemplan_default"=>$modificaciones,
	            "ptr"=>$ptr, 
	            "ptr_default"=>$modificaptr,
	            "id_usuario" =>$idusuario,
	            "fecha_registro" => $fechainicio
	        );

	       $this->db->insert('log_planobra', $dataInsert);

	        if($this->db->affected_rows() != 1) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar el plan de obra');
	        }else{
	           	$this->db->trans_commit();

	            $data['error']    = EXIT_SUCCESS;
	            $data['msj']      = 'Se inserto correctamente!';
	        }
	        
	         
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
	}

/*****************************************16-08-2018********************************************************/

/*****************************************cambio de estado***************************************************/
/****************************************obtener itemplan***************************************************/
function   getInfoEditItemplanEstado($idPlanobra){
	    $Query = " SELECT po.idEstadoPlan as idestado, 
	    				  est.estadoPlanDesc as estado
				    from planobra po, estadoplan est 
				    where po.idEstadoPlan=est.idEstadoPlan
				    and po.itemplan = ?";	
	    $result = $this->db->query($Query,array($idPlanobra));

	    if($result->row() != null) {

	        return $result->row_array();
	    } else {
	        return null;
	    }
	}


/*************************************eliminacion y/o modificacion de pre_diseno***********************************************/
function eliminarPreDiseno($idPlanobra){

	 $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	    	
        	$this->db->trans_begin();
       	 
        	$this->db->where('itemplan', $idPlanobra);
			$this->db->delete('pre_diseno');

            	if ($this->db->trans_status() === FALSE) {
            		$this->db->trans_rollback();
            	    throw new Exception('Hubo un error al eliminar en pre_diseno');
            	}else{
            	    
            	    $data['error']    = EXIT_SUCCESS;
            	    $data['msj']      = 'Se elimin¨® correctamente!';
            	    $this->db->trans_commit();
            	}
            
    	}catch(Exception $e){
    	    
    	    $data['msj']   = $e->getMessage();
    	    $this->db->trans_rollback();
    	}
        return $data;

}

function verificarExistePreDiseno($idPlanobra){

	 $Query = " SELECT count(1) as conteo
				    from pre_diseno
				    where itemplan = ?";	
	    $result = $this->db->query($Query,array($idPlanobra));

	    $cont =$result->row()->conteo;

		return $cont;

}

function modificaPreDiseno($idPlanobra){

	 $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	    	
        	$this->db->trans_begin();
       	 
        	
        	$dataUpdate = array(
	           
	            "estado" => 2,
	            "usuario_ejecucion" => null,
	            "fecha_ejecucion" => null

	        );


	      	$this->db->where('itemPlan', $idPlanobra);
        	$this->db->update('pre_diseno', $dataUpdate);

            	if ($this->db->trans_status() === FALSE) {
            		$this->db->trans_rollback();
            	    throw new Exception('Hubo un error al modificar en pre_diseno');
            	}else{
            	    
            	    $data['error']    = EXIT_SUCCESS;
            	    $data['msj']      = 'Se modific¨® correctamente!';
            	    $this->db->trans_commit();
            	}
            
    	}catch(Exception $e){
    	    
    	    $data['msj']   = $e->getMessage();
    	    $this->db->trans_rollback();
    	}
        return $data;

}



/*************************************eliminacion y/o modificacion de pre_diseno***********************************************/

/*************************************verificacion y modificacion de itemplanestacionavance***********************************************/
function verificarItemPlanEstacionAvance($idPlanobra){

	 $Query = " SELECT count(1) as conteo
				    from itemplanestacionavance
				    where porcentaje=100 
				    and itemplan = ?";	
	    $result = $this->db->query($Query,array($idPlanobra));

	    $cont =$result->row()->conteo;

		return $cont;

}


function modificaPorcentajeIPEAvance($idPlanobra){

	 $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	    	
        	$this->db->trans_begin();
       	 
        	$fechamod=date("Y-m-d H:i:s");
        	$id_usuario_log= $this->session->userdata('idPersonaSession');


        	$dataUpdate = array(
	           
	            "porcentaje" => 0,
	            "fecha" => $fechamod,
	            "id_usuario_log" => $id_usuario_log

	        );


	      	$this->db->where('itemplan', $idPlanobra);
        	$this->db->update('itemplanestacionavance', $dataUpdate);

        	if ($this->db->trans_status() === FALSE) {
        		$this->db->trans_rollback();
        	    throw new Exception('Hubo un error al eliminar en pre_diseno');
        	}else{
        	    
        	    $data['error']    = EXIT_SUCCESS;
        	    $data['msj']      = 'Se elimin¨® correctamente!';
        	    $this->db->trans_commit();
        	}
            
    	}catch(Exception $e){
    	    
    	    $data['msj']   = $e->getMessage();
    	    $this->db->trans_rollback();
    	}
        return $data;

}


function insertarLogPorcentajeIPEAvance($idPlanobra){

	 $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	    	
        	$this->db->trans_begin();
       	 
        	$fechamod=date("Y-m-d H:i:s");
        	$id_usuario_log= $this->session->userdata('idPersonaSession');

        	$selectinsert=" SELECT 0,'".$fechamod."',".$id_usuario_log.", itemplan , idEstacion, 'REINICIO A SOLICITUD DEL REGISTRADOR' from itemplanestacionavance where itemplan='".$idPlanobra."'";

        	$query="INSERT INTO log_porcentaje_cuadrilla (porcentaje, fecha_registro, usuario_registro, itemplan, idEstacion, detalle) ".$selectinsert;


        	$result = $this->db->query($query);
	      	

            	if ($this->db->trans_status() === FALSE) {
            		$this->db->trans_rollback();
            	    throw new Exception('Hubo un error al registrar en el log de cuadrilla');
            	}else{
            	    
            	    $data['error']    = EXIT_SUCCESS;
            	    $data['msj']      = 'Se registro correctamente!';
            	    $this->db->trans_commit();
            	}
            
    	}catch(Exception $e){
    	    
    	    $data['msj']   = $e->getMessage();
    	    $this->db->trans_rollback();
    	}
        return $data;

}

/*************************************verificacion y modificacion de itemplanestacionavance***********************************************/

/**************************************************modificaion de estado itemplan********************************************************/
function editarPlanObraEstado($itemplan,$estado){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	    	
	    	$this->db->trans_begin();

	    	if($estado==1){
	    		 $dataUpdate = array(
		           
		            "idEstadoPlan" => 1,
		            "fechaEjecucion" => null,
		            "fechaCancelacion" => null,
		            "fechaTermino" => null,
		            "fechaPreLiquidacion" => null, 
		            "idEmpresaColabDiseno" => null,
		            "flg_evidencia" =>0,
		            "idSerieTroba" =>0,
		            "fechaTrunca" =>null,
		            "motivoTrunco" =>null,
		            "idMotivo" =>null

	        	);
	    	}


	    	if($estado==2||$estado==3){
	    		 $dataUpdate = array(
		           
		            "idEstadoPlan" => $estado,
		            "fechaEjecucion" => null,
		            "fechaCancelacion" => null,
		            "fechaTermino" => null,
		            "fechaPreLiquidacion" => null, 
		            "flg_evidencia" =>0,
		            "idSerieTroba" =>0,
		            "fechaTrunca" =>null,
		            "motivoTrunco" =>null,
		            "idMotivo" =>null

	        	);
	    	}

	    	
	    	if($estado==6){
	    		$fechamod=date("Y-m-d H:i:s");
	    		 $dataUpdate = array(
		           
		            "idEstadoPlan" => 6,
		            "fechaEjecucion" => null,
		            "fechaCancelacion" => $fechamod,
		            "fechaTermino" => null,
		            "fechaPreLiquidacion" => null 
	        	);
	    	}

	      	$this->db->where('itemPlan', $itemplan);
        	$this->db->update('planobra', $dataUpdate);

        	if ($this->db->trans_status() === FALSE) {
        	    throw new Exception('Hubo un error al actualizar en estado del plan obra ');
        	}else{

        	    $data['error']    = EXIT_SUCCESS;
        	    $data['msj']      = 'Se actualizo correctamente!';
        	   
        	    $data['itemplan']      =  $itemplan;
        	    $this->db->trans_commit();
        	}
      
	    }catch(Exception $e){
    	    $data['msj']   = $e->getMessage();
    	    $this->db->trans_rollback();
	    }
	    return $data;
	}


	/**************************************************modificaion de estado itemplan********************************************************/
	/********************************************registro en log_planobra_cancelar***********************************************************/
	function insertarLogplanobra_cancelar($itemplan,$motivo,$comentario,$estado,$usuario){
		$data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	    	
	    	$this->db->trans_begin();
   		    	
   		    $fecha=date("Y-m-d H:i:s");
  	 
       		$dataInsert = array(
	            
	            "id_itemplan" => $itemplan,
	            "motivo" => $motivo,
	            "comentario" => $comentario,
	            "usuario" => $usuario,
	            "estado" => $estado, 
	            "fecha" => $fecha
	        );

	       $this->db->insert('planobra_cancelar', $dataInsert);

	        if($this->db->affected_rows() != 1) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar el plan de obra');
	        }else{
	           	$this->db->trans_commit();

	            $data['error']    = EXIT_SUCCESS;
	            $data['msj']      = 'Se inserto correctamente!';
	        }
	        
	         
	    }catch(Exception $e){
	        $data['msj']   = $e->getMessage();
	        $this->db->trans_rollback();
	    }
	    return $data;
}
	/********************************************registro en log_planobra_cancelar***********************************************************/
/****************10.07.2019************/

    function getAllPoByItemplan($itemplan){
        $Query = "SELECT * FROM planobra_po WHERE itemplan = ?" ;
        $result = $this->db->query($Query,array($itemplan));
        return $result->result();
    }

    function insertPoCancelar($listaPoCancelar){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
    
            $this->db->trans_begin();
            $this->db->insert_batch('po_cancelar', $listaPoCancelar);    
            if($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar el plan de obra');
            }else{
                $this->db->trans_commit();    
                $data['error']    = EXIT_SUCCESS;
                $data['msj']      = 'Se inserto correctamente!';
            }
        }catch(Exception $e){
            $data['msj']   = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }

}