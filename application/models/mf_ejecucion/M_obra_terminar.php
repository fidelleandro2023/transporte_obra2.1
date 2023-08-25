<?php
class M_obra_terminar extends CI_Model{

	function __construct(){
		parent::__construct();
		
	}
	function ListarArchivos($id){
       $Query="select pt.*, u.usuario from planobra_terminar pt left join usuario u on u.id_usuario=pt.id_usuario where pt.estado=1 and pt.id_planobra='".$id."' ";
     
       $result = $this->db->query($Query,array());
       if($result->row() != null) {    
        return $result;
    }else {
        return 0;
    }
}
    function IngresarAgenda($id,$id_usuario,$img,$fecha,$estado){
    $post_data = array(
        'id_planobra_terminar'=>'',        
        'id_planobra'=>$id,
        'id_usuario'=>$id_usuario,
        'nombre'=>$img,
        'fecha'=>$fecha,
        'estado'=>$estado
    );
    $this->db->insert('planobra_terminar', $post_data);
}
    function PreLiquidar($id){
    $Query="update planobra_terminar set estado=1 where estado=3 and id_planobra='".$id."'";
    $this->db->query($Query);
    $Query="update planobra set idEstadoPlan=9 where itemPlan='".$id."'";    
    $this->db->query($Query);
    }
    function Liquidar($id,$fecha){
    $Query="update planobra set idEstadoPlan=4,fechaEjecucion='".$fecha."' where itemPlan='".$id."'";
    $this->db->query($Query);
    $Query="update planobra_terminar set estado=1 where estado=3 and id_planobra='".$id."'";
    $this->db->query($Query);
    $Query="select proyectoDesc from planobra p, proyecto pro, subproyecto s where p.itemPlan='".$id."' and s.idSubProyecto=p.idSubProyecto and s.idProyecto=pro.idProyecto";
    $result = $this->db->query($Query,array());
    if($result->row() != null) {  
        return $result->row_array();
    }else {
                return null;
            }   
    }
    function ObraTerminarId($id){
    $Query="select nombre from planobra_terminar where estado=1 and id_planobra='".$id."'";
    $result = $this->db->query($Query,array());
    if($result->row() != null) {  
        return $result;
    }else {
                return null;
            }   
    }    
    function EliminarArchivos($id_planobra_terminar){
    $Query="update planobra_terminar set estado=2 where id_planobra_terminar='".$id_planobra_terminar."'"; 
    $this->db->query($Query);   
    }
    }	
