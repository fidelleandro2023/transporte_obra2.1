<?php
class M_pqt_bandeja_adjudicacion extends CI_Model{
	
	function __construct(){
		parent::__construct();
		
	}
	
	function getSubProyectoConCoaxFO($idSubProyecto){
	    $Query = 'SELECT DISTINCT sp.subProyectoDesc, sp.adjudicacionAutomatica_fg, COALESCE(paquetizado_fg,1) paquetizado_fg,
                    count(DISTINCT CASE WHEN idEstacion = '.ID_ESTACION_COAXIAL.' THEN 1 ELSE null END) as coaxial,
                    count(DISTINCT CASE WHEN idEstacion = '.ID_ESTACION_FO.' THEN 1 ELSE null END) as fo
                    FROM subproyecto sp, subproyectoestacion se, estacionarea ea
                    WHERE ea.idEstacion IN ('.ID_ESTACION_COAXIAL.','.ID_ESTACION_FO.')
                    AND	se.idEstacionArea = ea.idEstacionArea
                    AND	sp.idSubProyecto = se.idSubProyecto
					AND sp.idSubProyecto = '.$idSubProyecto.';';
	    $result = $this->db->query($Query, array());
	    return $result;
	}
	
	function   countFOAndCoaxByItemplan($itemplan){
	    $Query = "SELECT DISTINCT
                            	count(DISTINCT CASE WHEN idEstacion = 2 THEN 1 ELSE NULL END) as coaxial,
                            	count(DISTINCT CASE WHEN idEstacion = 5 THEN 1 ELSE NULL END) as fo
                            	FROM planobra po, subproyecto sp, subproyectoestacion se, estacionarea ea
            	WHERE	ea.idEstacion IN (5,2)
            	AND		se.idEstacionArea = ea.idEstacionArea
            	AND		sp.idSubProyecto = se.idSubProyecto
            	AND		po.idSubProyecto = sp.idSubProyecto
            	AND 	po.itemplan = ?";
	    $result = $this->db->query($Query,array($itemplan));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
}