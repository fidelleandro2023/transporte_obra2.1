<?php
class M_agenda_mapa extends CI_Model{

	function __construct(){
		parent::__construct();
		
	}
	
    function MapaIdAgenda($idAgenda){
    	if(!$this->session->userdata("zonasSession")){
    		$tabla="agenda";
    		$id="id_agenda";
    		$stabla="planobra_actividad";
    		$sid="id_planobra_actividad";
    	}else{
    		$tabla="agenda_z";
    		$id="id_agenda_z";
    		$stabla="planobra_actividad_z";
    		$sid="id_planobra_actividad_z";
    	}
    $Query="select a.coordenadas, p.coordX, p.coordY from ".$tabla." a, ".$stabla." pa, planobra p where a.".$id."=".$idAgenda." and a.".$sid."=pa.".$sid." and pa.id_planobra=p.itemPlan";
        $result = $this->db->query($Query,array());	   
	    if($result->row() != null) {  
        return $result->row_array();
    }else {
                return null;
            }
	}
    
	
}