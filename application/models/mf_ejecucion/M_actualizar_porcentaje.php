<?php
class M_actualizar_porcentaje extends CI_Model{

	function __construct(){
		parent::__construct();
	$this->load->model('mf_ejecucion/M_generales');	
	}
	    
    function ExisteItemSubActividad($id,$id_subactividad){
    $Query="select pa.id_usuario,
                   pa.id_planobra_actividad,
                   pa.id_actividad 
              from planobra_actividad pa left join 
                   planobra p on pa.id_planobra=p.itemPlan 
             where pa.id_subactividad = ".$id_subactividad." 
               and pa.id_planobra     = '".$id."'";

        $result = $this->db->query($Query,array());
        if($result->row() != null) {  
        return $result->row_array();
        }else {return null;}
    }
    function ExisteItemSubActividad_z($id,$id_estacion){
    $Query="select pa.* from planobra_actividad_z pa left join planobra p on pa.id_planobra=p.itemPlan where pa.idEstacion=".$id_estacion." and pa.id_planobra='".$id."'";
        $result = $this->db->query($Query,array());
        if($result->row() != null) {  
        return $result->row_array();
        }else {return null;}
    }
    function ActualizarPorcentajeM($id,$id_planobra_actividad,$id_subactividad,$select_cuadrilla,$fporcentaje,$conversacion,$cordenadas,$tiempo){
        if($id_planobra_actividad==0){
            $Query="SELECT id_actividad 
                      FROM subactividad 
                     WHERE id_subactividad=".$id_subactividad;
            $actividad = $this->db->query($Query,array())->row_array();
            //INGRESA planobra_actividad 
            $this->M_generales->IngresarPlanObraActividad('',$id,$actividad["id_actividad"],$id_subactividad,$select_cuadrilla);   
            $id_planobra_actividad=$this->db->insert_id();    
        }    
        if($fporcentaje==100){
            $this->M_generales->Actividad100($id_planobra_actividad);   
        }
        $porcentaje=$this->M_generales->Porcentaje($id_planobra_actividad);
        $fporcentaje=$fporcentaje-$porcentaje["valor"];
        $Query="update agenda set estado_l=2 where id_planobra_actividad=".$id_planobra_actividad." and estado_l=1";
        $this->db->query($Query);
        $this->M_generales->IngresarAgenda($tiempo,$id_planobra_actividad,$select_cuadrilla,$cordenadas,$conversacion,$fporcentaje);
        }

    function ActualizarPorcentajeZ($id,$id_planobra_actividad_z,$id_estacion,$select_cuadrilla,$fporcentaje,$conversacion,$cordenadas,$tiempo){
        if($id_planobra_actividad_z==0){            
        $this->M_generales->IngresarPlanObraActividadZ('',$id,$id_estacion,$select_cuadrilla);  
        $id_planobra_actividad_z=$this->db->insert_id();    
        }    
        if($fporcentaje==100){
            $this->M_generales->Actividad100z($id_planobra_actividad_z);   
        }
        $porcentaje=$this->M_generales->PorcentajeZ($id_planobra_actividad_z);
        $fporcentaje=$fporcentaje-$porcentaje["valor"];
        $Query="update agenda_z set estado=2 where id_planobra_actividad_z=".$id_planobra_actividad_z." and estado=1";
        $this->db->query($Query);
        $this->M_generales->IngresarAgendaz($tiempo,$id_planobra_actividad_z,$cordenadas,$select_cuadrilla,$conversacion,$fporcentaje);
    }
    function CambiarCuadrilla($param,$idUsuario,$titulo){
        $var=explode("|",$param);
        $Query="select id_planobra_actividad from planobra_actividad where id_subactividad=".$var[0]." and id_planobra='".$var[1]."'";
        $result = $this->db->query($Query,array());
            if($result->row() != null) { 
            $pa=$result->row_array();
        $Query="update planobra_actividad set id_usuario=".$idUsuario." where id_planobra_actividad=".$pa["id_planobra_actividad"];
        $this->db->query($Query);    
            echo $pa["id_planobra_actividad"]."-".$idUsuario."-".$titulo;
            }else {
        $Query="select id_actividad from subactividad where id_subactividad=".$var[0];
        $result = $this->db->query($Query,array());
        $act=$result->row_array();
        $this->M_generales->IngresarPlanObraActividad('',$var[1],$act["id_actividad"],$var[0],$idUsuario);
        echo $this->db->insert_id()."-".$idUsuario."-".$titulo;        
            }    }
    function CambiarCuadrillaz($param,$idUsuario,$titulo){
    $var=explode("|",$param);
    $Query="select id_planobra_actividad_z from planobra_actividad_z where idEstacion=".$var[0]." and id_planobra='".$var[1]."'";
    $result = $this->db->query($Query,array());
        if($result->row() != null) { 
        $pa=$result->row_array();
    $Query="update planobra_actividad_z set id_usuario=".$idUsuario." where id_planobra_actividad_z=".$pa["id_planobra_actividad_z"];
    $this->db->query($Query);    
        echo $pa["id_planobra_actividad_z"]."-".$idUsuario."-".$titulo;
        }else {    
    $this->M_generales->IngresarPlanObraActividadZ('',$var[1],$var[0],$idUsuario);
    echo $this->db->insert_id()."-".$idUsuario."-".$titulo;        
        }    }        	
}