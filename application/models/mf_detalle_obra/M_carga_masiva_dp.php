<?php
class M_carga_masiva_dp extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
    function   loadDataImportDetalleObra($pathFinal){
        $data ['error']= EXIT_ERROR;
        $data['msj'] = null;
        try{
            $this->db->from('import_dp');
            $this->db->truncate();
            if ($this->db->trans_status() === TRUE) {
                $this->db->query("LOAD DATA LOCAL INFILE '".$pathFinal."' INTO TABLE import_dp");
                //$this->db->query("LOAD DATA LOCAL INFILE '".$pathFinal."' INTO TABLE import_dp LINES TERMINATED BY 'string'");
                if ($this->db->trans_status() === TRUE) {
                    $this->db->query("DELETE FROM import_dp WHERE itemPlan LIKE '%ITEMPLAN%'");
                    if ($this->db->trans_status() === TRUE) {
                                $data ['error']= EXIT_SUCCESS;
                    }else{
                        _log('DELETE FROM import_dp WHERE itemPlan LIKE %ITEMPLAN%');
                    }           
                }else{
                    _log('loadDataImportDetalleObra: '.$pathFinal);
                }
            } else {
                _log('ERROR TRUNCATE loadDataImportDetalleObra');
            }
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        return $data;
	}	
	
	
	function   execImportDetallePlanMasivo(){
	    $data ['error']= EXIT_ERROR;
	    $data['msj'] = null;
	    try{
	        
	            $this->db->query("SELECT importDetallePlanMasivo();");
	            if ($this->db->trans_status() === TRUE) {
	                $data ['error']= EXIT_SUCCESS;
	            }else{
	                throw new Exception('ERROR TRANSACCION IMPORT DETALLEPLAN');
	            }
	      
	    }catch(Exception $e){
	        $data['msj'] = utf8_decode($e->getMessage());
	    }
	    return $data;
	}

	function   execLoadWebUniDetalle(){
        $data ['error']= EXIT_ERROR;
	    $data['msj'] = null;
	    try{
	        
	            $this->db->query("SELECT loadWebUniDetalle();");
	            if ($this->db->trans_status() === TRUE) {
	                $data ['error']= EXIT_SUCCESS;
	            }else{
	                throw new Exception('ERROR TRANSACCION IMPORT DETALLEPLAN');
	            }
	      
	    }catch(Exception $e){
	        $data['msj'] = utf8_decode($e->getMessage());
	    }
	    return $data;
	    
	}

function   getUploadPoError(){
	    $Query = "  SELECT ipo.*
                	FROM import_planobra ipo where 
                    
                    (SELECT idCentral from central where (UPPER(codigo) = TRIM(UPPER(central)))) is  null 
                    or 
                    (SELECT idProyecto from subproyecto where UPPER(subproyectoDesc) = TRIM(UPPER(subproyecto))) is  null;";
	   
	    $result = $this->db->query($Query,array());
	    return $result;
	}
	
	function   getUploadPoSuccess(){
	    $Query = "  SELECT *
                	FROM log_import_planobra_su;";
	
	    $result = $this->db->query($Query,array());
	    return $result;
	}
	
	function   execGetGrafos(){
        $data ['error']= EXIT_ERROR;
	    $data['msj'] = null;
	    try{
	        
	            $this->db->query("SELECT getGrafos();");
	            if ($this->db->trans_status() === TRUE) {
	                $data ['error']= EXIT_SUCCESS;
	            }else{
	                throw new Exception('ERROR TRANSACCION IMPORT DETALLEPLAN');
	            }
	      
	    }catch(Exception $e){
	        $data['msj'] = utf8_decode($e->getMessage());
	    }
	    //return $data;
	    
	}
       function ptrGroup(){
		$Query = "  SELECT DISTINCT trim(im.itemPlan) as item, trim(im.ptr) as ptr, se.idSubProyectoEstacion FROM import_dp im, area a, estacionarea ea, planobra pl, subproyectoestacion se WHERE trim(im.area) = a.areaDesc AND a.idArea = ea.idArea AND trim(im.itemPlan) = pl.itemPlan AND se.idSubProyecto = pl.idSubProyecto AND se.idEstacionArea = ea.idEstacionArea LIMIT 40;";
	   
	    $result = $this->db->query($Query,array());
	    return $result;
	}

        function deleteEnWebUnitDet($ptrs){
		
		//DELETE FROM web_unificada_det where ptr = 'PTR' AND ( itemPlan is null OR pep2 is null);
		$Query = "DELETE FROM web_unificada_det where ptr IN (".$ptrs.") AND ( itemPlan is null OR pep2 is null)" ;
	    $result = $this->db->query($Query);

	    return $result;
	}

        function selectDet($ptrs){
		//$Query = "  SELECT insertDet(".$ptrs.")" ;
		$Query = " INSERT INTO web_unificada_det SELECT DISTINCT wu.ptr, wu.est_innova, wu.jefatura, wu.eecc, sp.subProyectoDesc,NULL,NULL,
                wu.f_creac_prop, 
                wu.f_ult_est, 
                NULL,NULL,NULL,NULL,0, 
                wu.desc_area,
                dp.itemPlan,
                 CASE
                    WHEN substr(po.fechaPrevEjec,6,2) = '01'  THEN  'ENE'
                    WHEN substr(po.fechaPrevEjec,6,2) = '02'  THEN  'FEB'
                    WHEN substr(po.fechaPrevEjec,6,2) = '03'  THEN  'MAR'
                    WHEN substr(po.fechaPrevEjec,6,2) = '04'  THEN  'ABR' 
                    WHEN substr(po.fechaPrevEjec,6,2) = '05'  THEN  'MAY'
                    WHEN substr(po.fechaPrevEjec,6,2) = '06'  THEN  'JUN'
                    WHEN substr(po.fechaPrevEjec,6,2) = '07'  THEN  'JUL'
                    WHEN substr(po.fechaPrevEjec,6,2) = '08'  THEN  'AGO'
                    WHEN substr(po.fechaPrevEjec,6,2) = '09'  THEN  'SEP'
                    WHEN substr(po.fechaPrevEjec,6,2) = '10'  THEN  'OCT'
                    WHEN substr(po.fechaPrevEjec,6,2) = '11'  THEN  'NOV'
                    WHEN substr(po.fechaPrevEjec,6,2) = '12'  THEN  'DIC'
                ELSE NULL 
                END as fechaPrevEjec,
                e.estacionDesc,
                a.areaDesc,
                po.fechaPrevEjec,
                NULL,
                wu.valoriz_material,
                wu.valoriz_m_o,
                po.indicador,
                NULL,
                NULL
                FROM web_unificada wu 
                LEFT JOIN detalleplan dp
                ON wu.ptr = dp.poCod 
                LEFT JOIN  subproyectoestacion se
                ON dp.idSubProyectoEstacion = se.idSubProyectoEstacion
                LEFT JOIN subproyecto sp
                ON sp.idSubProyecto = se.idSubProyecto
                LEFT JOIN estacionarea ea
                ON  se.idEstacionArea = ea.idEstacionArea               
                LEFT JOIN estacion e                   
                ON  ea.idEstacion = e.idEstacion
                LEFT JOIN area a
                ON ea.idArea = a.idArea   
                LEFT JOIN planobra po
                ON dp.itemPlan = po.itemPlan
                WHERE   STR_TO_DATE(wu.f_creac_prop, \'%d/%m/%Y %H:%i\') >= STR_TO_DATE('01/01/2018 00:00', \'%d/%m/%Y %H:%i\')
                AND (substring(wu.est_innova,1,3) = '003' OR substring(wu.est_innova,1,2) = '01' OR substring(wu.est_innova,1,3) = '001' OR substring(wu.est_innova,1,3) = '002' OR substring(wu.est_innova,1,3) = '004' OR substring(wu.est_innova,1,3) = '005')
                AND CASE WHEN desc_area = 'MO' AND estacionDesc != 'DISEÑO' THEN po.idEstadoPlan = 4 ELSE TRUE END 
                AND wu.ptr NOT IN (SELECT ptr from web_unificada_det)
                and wu.ptr IN  (".$ptrs.")";
	    //$result = $this->db->query($Query,array($ptrs));
	    $result = $this->db->query($Query);
	    return $result;
	}
        function getGrafoOnePTR($ptr){
		$Query = "  SELECT getGrafoOnePTR(".$ptr.")" ;
	    $result = $this->db->query($Query);
	    return $result;

	}

        // Mandar al log
	function insertPTRenLog($itemInsert, $ptr, $idUser){

		$Query = "INSERT INTO log_planobra (tabla, actividad, itemplan, ptr, fecha_registro, id_usuario) VALUES ('web_unificada_det, detalleplan', 'ingresar masivo','".$itemInsert."', '".$ptr."', NOW(), ".$idUser.")" ;
	    $result = $this->db->query($Query);

	    return $result;
	    

	}
	
}