<?php
class M_situacion extends CI_Model{

	function __construct(){
		parent::__construct();
		
	}
	
    function getUltimoEstado($id){
    $Query="select id_subactividad_estado from subactividad_estado_planobra_actividad where id_actividad=1 and id_planobra='".$id."' order by id_subactividad_estado_planobra_actividad DESC limit 0,1";
        $result = $this->db->query($Query,array());	   
	    return $result;
	}
    function getListarSituacion(){
    $Query="select nombre,id_subactividad_estado from subactividad_estado where estado=1 order by nombre";    
        $result = $this->db->query($Query,array());    
        return $result;
    }
    function getSituaciones($id){
    $Query="select  s.comentario, DATE_FORMAT(s.fecha,'%d/%m/%Y') as fechab,b.nombre,u.usuario 
    from subactividad_estado_planobra_actividad s left join subactividad_estado b on b.id_subactividad_estado=s.id_subactividad_estado join
    usuario u on s.id_usuario=u.id_usuario
    where s.id_actividad=1 and s.id_planobra='".$id."' order by s.id_subactividad_estado_planobra_actividad desc"; 

    
    $result = $this->db->query($Query,array());
    return $result;
    }
    function GuardarSituacion($id,$id_actividad,$id_subactividad_estado,$id_actividad,$observacion){
    $Query="update subactividad_estado_planobra_actividad set estado=2 where estado=1 and id_planobra='".$id."' and id_actividad=".$id_actividad;
    $this->db->query($Query);
    $Query="insert into subactividad_estado_planobra_actividad values ('',".$id_subactividad_estado.",".$id_actividad.",".$this->session->userdata('idPersonaSession').",'".$observacion."','".date("Y-m-d H:i:s")."',1,'".$id."')";
    $this->db->query($Query); 
    }
	
}