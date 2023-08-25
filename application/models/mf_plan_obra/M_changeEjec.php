<?php
class M_changeEjec extends CI_Model{
	function __construct(){
		parent::__construct();
		
	}
	
    function   loadDataImportDetalleObra($pathFinal){
        $data ['error']= EXIT_ERROR;
        $data['msj'] = null;
        try{
            $this->db->from('import_liquidador_masivo');//(import_fecha_planobra)
            $this->db->truncate();
            if ($this->db->trans_status() === TRUE) {
                $this->db->query("LOAD DATA LOCAL INFILE '".$pathFinal."' INTO TABLE import_liquidador_masivo");//(import_fecha_planobra)
                if ($this->db->trans_status() === TRUE) {
                    $this->db->query("DELETE FROM import_liquidador_masivo  WHERE itemPlan LIKE '%ITEMPLAN%'");//(import_fecha_planobra)
                    if ($this->db->trans_status() === TRUE) {                   
                                $data ['error']= EXIT_SUCCESS;
                    }else{
                        _log('DELETE FROM import_liquidador_masivo WHERE itemPlan LIKE %ITEMPLAN%');//(import_fecha_planobra)
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
                                    JOIN import_liquidador_masivo E on 
                                           C.itemPlan = E.itemplan
                                           SET C.fechaEjecucion = STR_TO_DATE(E.fechaEjecucion, "%d/%m/%Y")
                                           ,C.idEstadoPlan = 4');
                   


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