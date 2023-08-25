<?php
class M_ejecucion_cuadrilla extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
    function getListarPendientes($id){
    $sent="";    
    	$Query = "select c.tipoCentralDesc,po.indicador,po.itemPlan,s.subProyectoDesc, po.nombreProyecto,po.fechaPrevEjec,po.fechaInicio,p.id_usuario,p.id_subactividad,p.id_planobra_actividad,a.nombre,p.id_actividad from planobra_actividad p, actividad a, planobra po, subproyecto s, central c where p.estado=1 and p.id_usuario=".$id." and p.id_actividad=a.id_actividad and po.itemPlan=p.id_planobra and po.idEstadoPlan in (3) and s.idSubProyecto=po.idSubProyecto and c.idCentral=po.idCentral";
        $result = $this->db->query($Query,array());	   
	    return $result;
	}
	function DetalleObra($id_planobra_actividad){
		$Query="select p.*, pa.id_subactividad,u.id_usuario,u.nombre,u.email,u.celular,u.usuario,a.nombre actividad,pa.fecha from planobra_actividad pa , usuario u, actividad a, planobra p where p.itemplan=pa.id_planobra and a.id_actividad=pa.id_actividad and pa.id_usuario=u.id_usuario and pa.id_planobra_actividad=".$id_planobra_actividad;
				
		$result = $this->db->query($Query,array()); 
    	if($result->row() != null) {    
        return $result->row_array();
   		 }else {
        return 0;
   		 } 
	}
	function DetalleObraZ($id_planobra_actividad){
		$Query="select p.*,pa.idEstacion, e.estacionDesc actividad,u.id_usuario,u.nombre,u.email,u.celular,u.usuario,pa.fecha from estacion e, planobra_actividad_z pa , usuario u, planobra p where p.itemplan=pa.id_planobra and pa.id_usuario=u.id_usuario and pa.idEstacion=e.idEstacion and pa.id_planobra_actividad_z=".$id_planobra_actividad;
			
		$result = $this->db->query($Query,array()); 
    	if($result->row() != null) {    
        return $result->row_array();
   		 }else {
        return 0;
   		 } 
	}

}