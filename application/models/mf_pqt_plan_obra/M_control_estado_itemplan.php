<?php
class M_control_estado_itemplan extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
	function getLastEstadoByItemPlan($itemPlan){
	    $Query = 'SELECT c.*, po.has_paralizado
                    FROM control_estado_itemplan c, planobra po
					WHERE c.itemplan = po.itemplan 
                    AND c.id_control_estado_itemplan =
	                   (SELECT MAX(id_control_estado_itemplan) FROM control_estado_itemplan WHERE itemplan = ?);';
	    $result = $this->db->query($Query, array($itemPlan));
	    return $result;
	}
}