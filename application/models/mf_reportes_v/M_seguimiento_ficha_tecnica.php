<?php
class M_seguimiento_ficha_tecnica extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
    function   getSeguimientoFichaTecnica($jefatura, $eecc){            
        
       $query = "SELECT c.jefatura, 
                		e.empresaColabDesc, 
                		 (SELECT getPDT_DJ_By_Jefatura_EmpresaColab(c.jefatura, e.idEmpresaColab)) as pndt,   
                		SUM(1) as total,
                		SUM(CASE WHEN DATE(ft.fecha_registro) = DATE(NOW()) THEN 1 ELSE NULL END) as hoy,
                        SUM(CASE WHEN DATE(ft.fecha_registro) = DATE(date_add(NOW(), INTERVAL -1 DAY)) THEN 1 ELSE NULL END) as menos1,
                        SUM(CASE WHEN DATE(ft.fecha_registro) = DATE(date_add(NOW(), INTERVAL -2 DAY)) THEN 1 ELSE NULL END) as menos2,
                        SUM(CASE WHEN DATE(ft.fecha_registro) = DATE(date_add(NOW(), INTERVAL -3 DAY)) THEN 1 ELSE NULL END) as menos3,
                        SUM(CASE WHEN DATE(ft.fecha_registro) = DATE(date_add(NOW(), INTERVAL -4 DAY)) THEN 1 ELSE NULL END) as menos4,
                        SUM(CASE WHEN DATE(ft.fecha_registro) = DATE(date_add(NOW(), INTERVAL -5 DAY)) THEN 1 ELSE NULL END) as menos5,
                        SUM(CASE WHEN DATE(ft.fecha_registro) = DATE(date_add(NOW(), INTERVAL -6 DAY)) THEN 1 ELSE NULL END) as menos6,
                        SUM(CASE WHEN DATE(ft.fecha_registro) = DATE(date_add(NOW(), INTERVAL -7 DAY)) THEN 1 ELSE NULL END) as menos7
                FROM	
                		planobra po, ficha_tecnica ft, central c, empresacolab e
                WHERE 	po.itemplan 		= ft.itemplan
                AND 	po.idCentral 		= c.idCentral
                AND 	c.idEmpresacolab 	= e.idEmpresaColab
                AND		(DATE(ft.fecha_registro) BETWEEN  DATE(date_add(NOW(), INTERVAL -7 DAY)) AND DATE(NOW()))
                GROUP BY c.jefatura, e.empresaColabDesc
                HAVING 1 = 1 ";
       
       if($jefatura != ''){
           $query .= " AND c.jefatura = '".$jefatura."'";
       }       
       if($eecc != ''){
           $query .= " AND e.empresaColabDesc = '".$eecc."'";
       }
	    $result = $this->db->query($query,array());	   
	    return $result;
	}

}