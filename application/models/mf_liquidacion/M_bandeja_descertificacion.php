<?php
class M_bandeja_descertificacion extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
	function   getBandejaDesCertificacionMO(){
	    $Query = "SELECT   p.proyectoDesc, sp.subProyectoDesc, mo.* 
	               FROM    certificacion_mo mo, subproyecto sp, proyecto p where 
                           mo.idSubProyecto        = sp.idSubProyecto 
	               AND     sp.idProyecto           = p.idProyecto
                   AND     mo.estado_validado      = 1
				   AND     mo.estado = 1" ;
	    $result = $this->db->query($Query,array());
	    return $result;
	}

	
	
    function liberarPtrCertificacion($arrayPtr) {
      $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $this->db->update_batch('certificacion_mo',$arrayPtr, 'id');
	        if ($this->db->trans_status() === FALSE) {
	            $this->db->trans_rollback();
	            throw new Exception('Hubo un error al actualizar el saveFichaTecnicaValidacion.');
	        }else{
                $data['error'] = EXIT_SUCCESS;
	            $data['msj'] = 'Se actualizo correctamente!';
	            $this->db->trans_commit();
            } 
        }catch(Exception $e){
            $data['msj']   = $e->getMessage();
            $this->db->trans_rollback();
        }
	                return $data;
      }
    
	
}