<?php
class M_planobra_pi extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
    

	
	
function insertarPlanobraPI($dataInsert,$pep1,$iter){
	    $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	    	
	    	$this->db->trans_begin();
	    	
	    	$fechainicio=date("Y-m-d H:i:s");

	    	$idProvincia=1;
	    	$idDepartamento=1;
	    	$hasAdelanto='0';

	       $this->db->insert('planobra', $dataInsert);

	        if($this->db->affected_rows() != 1) {
	            $this->db->trans_rollback();
	            throw new Exception('Error al insertar el plan de obra');
	        }else{

				/*$arrayUpdate = array(
                  
                    "correlativo"  => $iter+1
                );
                 $this->db->where('pep1', $pep1);
                 $this->db->update('pep1xcorrelativo', $arrayUpdate);
				 */


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