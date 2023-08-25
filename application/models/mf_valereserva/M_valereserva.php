<?php
class M_valereserva extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	////**************carga para la correccion del vale de reserva*****************////
   function   getImportValeReserva($pathFinal){
        $data ['error']= EXIT_ERROR;
        $data['msj'] = null;
        try{
            $this->db->trans_begin();
            $this->db->from('import_valereserva');
            $this->db->truncate();
            if ($this->db->trans_status() === TRUE) {
                $this->db->query("LOAD DATA LOCAL INFILE '".$pathFinal."' INTO TABLE import_valereserva");
                if ($this->db->trans_status() === TRUE) {
                         $this->db->trans_commit();
                         $data ['error']= EXIT_SUCCESS; 
                }else{
                    $this->db->trans_rollback();
                    throw new Exception('ERROR LOAD DATA LOCAL INFILE');
                }
            } else {
                $this->db->trans_rollback();
                throw new Exception('ERROR TRUNCATE import_valereserva');
            }
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        return $data;
	}	
	
	function   exeUpdateWUfromVR(){
	    $data ['error']= EXIT_ERROR;
	    $data['msj'] = null;
	    try{
	         
	        $this->db->query("SELECT getUpdateWUfromVRFILE();");
	        if ($this->db->trans_status() === TRUE) {
	            $data ['error']= EXIT_SUCCESS;
	        }else{
	           //  $this->db->trans_rollback();
                throw new Exception('ERROR getUpdateWUfromVRFILE valereserva');
	        }
	         
	    }catch(Exception $e){
	       $data['msj'] = 'Ocurrio un problema!';
	    }
	    return $data;
	}
	
		
	function   saveLogUpdateVRenWU(){
	    $data['error']= EXIT_ERROR;
	    $data['msj'] = null;
	    try{
	        $dataUpdateLog= array(
	            'tabla' => 'web_unificada',
	            'actividad' => 'correccion',
	            'itemplan_default' => 'Modificacion VR Local por VR Global',
	            'fecha_registro' =>date("Y-m-d H:i:s"),
	            'id_usuario' =>  $this->session->userdata('idPersonaSession')
	        );
	        
	        $this->db->insert('log_planobra',$dataUpdateLog);
	        if ($this->db->affected_rows() != 1) {
	            $this->db->trans_rollback();
	            throw new Exception('Hubo un error al actualizar el estadoplan.');
	        }else{
	            $data['error']    = EXIT_SUCCESS;
	            $data['msj']      = 'Se actualizo correctamente!';
	            $this->db->trans_commit();
	        }
    }catch(Exception $e){
        $data['msj'] = $e->getMessage();
    }
    return $data;
}

//********importacion carga vale de reserva *******///
function   getImportValeReservaLoad($pathFinal){
        $data ['error']= EXIT_ERROR;
        $data['msj'] = null;
        try{
            $this->db->trans_begin();
            $this->db->from('valereserva');
            $this->db->truncate();
            if ($this->db->trans_status() === TRUE) {
                $this->db->query("LOAD DATA LOCAL INFILE '".$pathFinal."' INTO TABLE valereserva");
                if ($this->db->trans_status() === TRUE) {
                         $this->db->trans_commit();
                         $data ['error']= EXIT_SUCCESS; 
                }else{
                    $this->db->trans_rollback();
                    throw new Exception('ERROR LOAD DATA LOCAL INFILE');
                }
            } else {
                $this->db->trans_rollback();
                throw new Exception('ERROR TRUNCATE valereserva');
            }
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        return $data;
	}


function   exeUpdateEstadoVR(){
        $data ['error']= EXIT_ERROR;
        $data['msj'] = null;
        try{
             
            $this->db->query("SELECT getUpdateCargaVR();");
            if ($this->db->trans_status() === TRUE) {
                $data['msj'] ="El archivo fue cargado al sistema.</br>El proceso de creacion del reporte tomara unos minutos.</br> Por favor espere";
                $data ['error']= EXIT_SUCCESS;
            }
             
        }catch(Exception $e){
           $data['msj'] = 'Ocurrio un problema!';
        }
        return $data;
    }

public function loadInsertWUVRMaterial(){
    $data ['error']= EXIT_ERROR;
        $data['msj'] = null;
        try{
             
            $this->db->query("SELECT loadPlanObraWUVRMaterial();");
            if ($this->db->trans_status() === TRUE) {
                $data ['error']= EXIT_SUCCESS;
            }
             
        }catch(Exception $e){
           $data['msj'] = 'Ocurrio un problema!';
        }
        return $data;


}

public function loadIdMatPendiente(){
    $data ['error']= EXIT_ERROR;
        $data['msj'] = null;
        try{
             
            $this->db->query("SELECT loadIdMaterialPendienteAll();");
            if ($this->db->trans_status() === TRUE) {
                $data ['error']= EXIT_SUCCESS;
            }
             
        }catch(Exception $e){
           $data['msj'] = 'Ocurrio un problema!';
        }
        return $data;


}

public function loadIdMatNoActivo(){
    $data ['error']= EXIT_ERROR;
        $data['msj'] = null;
        try{
             
            $this->db->query("SELECT loadIdMaterialNoActivo();");
            if ($this->db->trans_status() === TRUE) {
                $data ['error']= EXIT_SUCCESS;
            }
        }catch(Exception $e){
           $data['msj'] = 'Ocurrio un problema!';
        }
        return $data;


}

public function loadIdMatSubproyecto(){
    $data ['error']= EXIT_ERROR;
        $data['msj'] = null;
        try{
             
            $this->db->query("SELECT loadIdMaterialSubproyecto();");
            if ($this->db->trans_status() === TRUE) {
                $data ['error']= EXIT_SUCCESS;
            }
        }catch(Exception $e){
           $data['msj'] = 'Ocurrio un problema!';
        }
        return $data;


}








public function getVR_WU_MATERIAL($codigovr, $idmaterial, $descripcion, $mes, $anio, $estvr,$ptr,$subproyecto){
	
	    $query = " 	SELECT  po.itemplan,
                            subpo.subProyectoDesc,
                            TbWU.ptr,
                            TbVR.codigo_vr,
                            TbVR.id_material,
                            TbVR.descrip_material,
                            TbVR.fech_nec,
                            TbVR.cant_nec,
                            TbVR.cant_red,
                            TbVR.cant_dif,
                            TbVR.sfin,
                            TbVR.bor,
                            TbVR.mov,
                            TbVR.costo_material,
                            TbVR.total,
                            TbVR.totalParcial,
                            TbVR.estadovr,
                            TbVR.observacion,
                            TbWU.est_innova
                    FROM    valereserva TbVR, 
                            (SELECT trim(RIGHT(vr,8)) as vrfil, 
                                    ptr,estado,est_innova
                               FROM web_unificada 
                              WHERE trim(vr)!='' 
                                AND trim(RIGHT(vr,8))!='0000000'
                                AND trim(RIGHT(vr,8))!='0') as TbWU, 
                            detalleplan dp, 
                            planobra po, 
                            subproyecto subpo
                    WHERE   dp.poCod=TbWU.ptr
                      AND   po.itemplan=dp.itemplan
                      AND   po.idsubproyecto=subpo.idSubProyecto
                      AND   TbVR.codigo_vr=TbWU.vrfil
                      AND   po.idEstadoPlan=3 ";
       if($anio!=''){
            if($mes!=''){
                $query.= " AND MONTH(str_to_date(TbVR.fech_nec,'%d/%m/%Y')) IN (".$mes.") AND YEAR(str_to_date(TbVR.fech_nec,'%d/%m/%Y')) IN (".$anio.") ";
            }else{
                 $query.= " AND YEAR(str_to_date(TbVR.fech_nec,'%d/%m/%Y')) IN (".$anio.")  ";
            }

           
        }else{
            if($mes!=''){
                $query.= " AND MONTH(str_to_date(TbVR.fech_nec,'%d/%m/%Y')) IN (".$mes.") ";
            }
            
        }

        if($codigovr!=''){
        	$query.= " AND TbVR.codigo_vr in (".$codigovr.") ";
        }

        if($idmaterial!=''){
        	$query.= " AND TbVR.id_material in (".$idmaterial.") ";
        }
        
        if($descripcion!=''){
            $query .= " AND tabMat.descrip_material LIKE '%".$descripcion."%' ";
        } 

         if($estvr!=''){
            $arreglo = explode(",", $estvr);
            $temporal='';
            $indicador=0;
            foreach($arreglo as $estadosele){
                if ($indicador==0){
                    $temporal= "TbVR.estadovr LIKE '%".TRIM($estadosele)."%' ";
                    $indicador++;
                }else{
                    $temporal .= " OR TbVR.estadovr LIKE '%".TRIM($estadosele)."%' ";
                }
                
            }

            $query .= " AND (".$temporal.") ";
            $temporal='';

        } 
        
        if($subproyecto!=''){
            $query.= " AND po.idsubproyecto in (".$subproyecto.") ";
        }

        if($ptr!=''){
            $query.= " AND TbWU.ptr LIKE '%".TRIM($ptr)."%' ";
        }
        
	    $result = $this->db->query($query,array());
	    return $result;
	}


public function getVRPlanObraNewTabla(){
   
        $query = "SELECT itemplan,
                         subProyectoDesc,
                         ptr,
                         codigo_vr,
                         id_material,
                         descrip_material,
                         fech_nec,
                         cant_nec,
                         cant_red,
                         cant_dif,
                         sfin,
                         bor,
                         mov,
                         costo_material,
                         total,
                         totalParcial,
                         estadovr,
                         observacion,
                         estadoptr,
                         estadoplandesc
                    from planobra_wu_vr_material
                    where idestadoplan in (3)";
        $result = $this->db->query($query,array());
        return $result;
    }

	function getAllVR(){
        $Query = "  SELECT codigo_vr 
                    FROM valereserva
                    group BY codigo_vr" ;	
	    $result = $this->db->query($Query,array());	   
	    return $result;
	}

	function getAllVRIDMAT(){
        $Query = "  SELECT id_material 
                    FROM valereserva
                    group BY id_material" ;	
	    $result = $this->db->query($Query,array());	   
	    return $result;
	}

	function getAllVRDESCMAT(){
        $Query = "  SELECT descrip_material 
        			from material 
        			WHERE exists (SELECT  ID_MATERIAL from valereserva 
        						 WHERE valereserva.id_material=material.id_material) 
        			order by descrip_material" ;	
	    $result = $this->db->query($Query,array());	   
	    return $result;
	}

    function getAllAnioVR(){
        $Query = "  SELECT DISTINCT YEAR(str_to_date(fech_nec,'%d/%m/%Y')) as anio 
                    from valereserva;" ; 
        $result = $this->db->query($Query,array());    
        return $result;
    }


     function getAllEstadoVR(){
        $Query = "  SELECT estadovr 
                    from valereserva
                    group by estadovr;" ; 
        $result = $this->db->query($Query,array());    
        return $result;
    }
    
    //////////////17-09-2018////////////////////////////////
    public function exceLoadRepVR()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $this->db->query("SELECT load_rep_vr_vreec_ip_mat();");
            if ($this->db->trans_status() === true) {
                $data['msj'] = "Se insert&oacute; correctamente en la tabla reporte.</br>El proceso de creaci&oacute;n del reporte tomara unos minutos.</br> Por favor espere";
                $data['error'] = EXIT_SUCCESS;
            } else {
                $this->db->trans_rollback();
                throw new Exception('Hubo un error al insertar.');
            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        return $data;
    }
    
    
    


}