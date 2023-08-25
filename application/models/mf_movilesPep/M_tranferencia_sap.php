<?php
class M_tranferencia_sap extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
    function   loadDataImportSapFija($pathFinal){
        $data ['error']= EXIT_ERROR;
        $data['msj'] = null;
        try{
            $this->db->trans_begin();
            $this->db->from('sap_moviles_fija');
            $this->db->truncate();
            if ($this->db->trans_status() === TRUE) {
                $this->db->query("DELETE FROM sap_moviles_detalle WHERE tipo = '".FROM_SAP_FIJA."'");
                if ($this->db->trans_status() === TRUE) {
                     $this->db->query("LOAD DATA LOCAL INFILE '".$pathFinal."' INTO TABLE sap_moviles_fija");
                     if ($this->db->trans_status() === TRUE) {
                            $this->db->query("SELECT saveStatusDayMoviles();");
                         if ($this->db->trans_status() === TRUE) {
                             $this->db->trans_commit();
                             $data['error']= EXIT_SUCCESS;
                         }else{
                             $this->db->trans_rollback();
                             throw new Exception('ERROR saveStatusDayMoviles');
                         }
                    }else{
                        $this->db->trans_rollback();
                        throw new Exception('ERROR loadDataImportSapFijaMoviles');
                    }
                } else {
                    $this->db->trans_rollback();
                    throw new Exception('ERROR TRUNCATE delete sap_moviles_detalle');
                }
            }else {
                $this->db->trans_rollback();
                throw new Exception('ERROR TRUNCATE sap_moviles_fija');
            }
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        return $data;
	}	
	
	function   execFunctionsCargaSap(){
	    $data ['error']= EXIT_ERROR;
	    $data['msj'] = null;
	    try{
	        $this->db->trans_begin();
	        $this->db->query("SELECT loadFunctionsAfterRefresh();");
	        if ($this->db->trans_status() === TRUE) {
	            $this->db->query("SELECT getGrafos();");
	            if ($this->db->trans_status() === TRUE) {
	                $this->db->query("SELECT getGrafosByItemplanPep2();");
	                if ($this->db->trans_status() === TRUE) {
	                    $this->db->query("SELECT loadPresupuestoMat();");
	                    if ($this->db->trans_status() === TRUE) {
	                        $this->db->query("SELECT getUpdatesExternos();");
	                        if ($this->db->trans_status() === TRUE) {
	                            $this->db->query("SELECT createOCSisegosByItemplanByListItemplan();");
	                            if ($this->db->trans_status() === TRUE) {
	                               $this->db->query("SELECT loadFunctionsAfterRefresh_2();");
    	                           if ($this->db->trans_status() === TRUE) {
    	                                $this->db->trans_commit();
    	                                $data ['error']= EXIT_SUCCESS;
    	                            }else{
    	                                $this->db->trans_rollback();
    	                                throw new Exception('Error loadFunctionsAfterRefresh_2()');
    	                            }
	                            }else{
	                                $this->db->trans_rollback();
	                                throw new Exception('Error createOCSisegosByItemplanByListItemplan()');
	                            }
	                        }else{
	                            $this->db->trans_rollback();
	                            throw new Exception('Error getUpdatesExternos()');
	                        }
	                    }else{
	                        $this->db->trans_rollback();
	                        throw new Exception('Error getGrafosByItemplanPep2()');
	                    }
	                }else{
	                    $this->db->trans_rollback();
	                    throw new Exception('Error loadPresupuestoMat()');
	                }
	            }else{
	                $this->db->trans_rollback();
	                throw new Exception('Error getGrafos()');
	            }
	        }else{
	            $this->db->trans_rollback();
	            throw new Exception('Error loadFunctionsAfterRefresh()');
	        }
	    }catch(Exception $e){
	        $data['msj'] = 'Ocurrio un problema interno con la actualizacion de informacion, comuniquese con el Administrador!';
	    }
	    return $data;
	}
	
}