<?php
class M_carga_update_fech extends CI_Model{
	function __construct(){
		parent::__construct();
		
	}
	
    function   loadDataImportDetalleObra($pathFinal){
        $data ['error']= EXIT_ERROR;
        $data['msj'] = null;
        try{
            $this->db->from('import_fecha_planobra');
            $this->db->truncate();
            if ($this->db->trans_status() === TRUE) {
                $this->db->query("LOAD DATA LOCAL INFILE '".$pathFinal."' INTO TABLE import_fecha_planobra");
                if ($this->db->trans_status() === TRUE) {
                    $this->db->query("DELETE FROM import_fecha_planobra WHERE itemPlan LIKE '%ITEMPLAN%'");
                    if ($this->db->trans_status() === TRUE) {                   
                                $data ['error']= EXIT_SUCCESS;
                    }else{
                        _log('DELETE FROM import_fecha_planobra WHERE itemPlan LIKE %ITEMPLAN%',true);
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
	
	
	function   updateFechasSQL(){
	    $data ['error']= EXIT_ERROR;
	    $data['msj'] = null;
	    try{
	        
	            $this->db->query('UPDATE planobra C
                                    JOIN import_fecha_planobra E on 
                                           C.itemPlan = E.itemplan
                                           SET C.fechaInicio = STR_TO_DATE(E.fecha_inicio, "%d/%m/%Y"),
                                    	   C.fechaPrevEjec = STR_TO_DATE(E.fecha_pre_ejec, "%d/%m/%Y")');
	            if ($this->db->trans_status() === TRUE) {
	                $data ['error']= EXIT_SUCCESS;
	            }else{
	                throw new Exception('ERROR Update Fechas Masivo');
	            }
	      
	    }catch(Exception $e){
	        $data['msj'] = utf8_decode($e->getMessage());
	    }
	    return $data;
	}

	

}