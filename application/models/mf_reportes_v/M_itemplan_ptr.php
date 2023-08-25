<?php
class M_itemplan_ptr extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
    function   getWebUnificadaFa($subPry,$eecc,$zonal,$mesEjec){
        $Query = "  SELECT * FROM web_unificada_fa WHERE 1=1 ";
        if($subPry!=''){
            $Query .= " AND subProyectoDesc LIKE '%".$subPry."%' ";
        }
        if($eecc!=''){
            $Query .= " AND eecc LIKE '%".$eecc."%' ";
        }
        if($zonal!=''){
            $Query .= " AND zonal LIKE '%".$zonal."%' ";
        }       
        if($mesEjec!=''){
            $Query .=  " AND mesEjecucion ='".$mesEjec."'";
        }
	    $result = $this->db->query($Query,array());	   
	    return $result;
	}	

	function   getRangoPtr(){
	    $Query = "  SELECT * FROM rangoptr";
	     
	    $result = $this->db->query($Query,array());
	    return $result;
	}
	
}