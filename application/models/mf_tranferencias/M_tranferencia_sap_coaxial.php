<?php
class M_tranferencia_sap_coaxial extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
    function   loadDataImportSapCoaxial($pathFinal){
        $data ['error']= EXIT_ERROR;
        $data['msj'] = null;
        try{
            $this->db->trans_begin();
            $this->db->from('sap_coaxial');
            $this->db->truncate();
            if ($this->db->trans_status() === TRUE) {
                $this->db->query("DELETE FROM sap_detalle WHERE tipo = '".FROM_SAP_COAXIAL."'");
                if ($this->db->trans_status() === TRUE) {
                     $this->db->query("LOAD DATA LOCAL INFILE '".$pathFinal."' INTO TABLE sap_coaxial");
                     if ($this->db->trans_status() === TRUE) {
                            $this->db->trans_commit();
                            $data ['error']= EXIT_SUCCESS;
                    }else{
                        $this->db->trans_rollback();
                        throw new Exception('ERROR loadDataImportSapCoaxial');
                    }
                } else {
                    $this->db->trans_rollback();
                    throw new Exception('ERROR TRUNCATE delete sap_detalle');
                }
            }else {
                $this->db->trans_rollback();
                throw new Exception('ERROR TRUNCATE sap_coaxial');
            }
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        return $data;
	}		
	
}