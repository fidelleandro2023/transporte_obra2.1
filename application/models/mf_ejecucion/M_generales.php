<?php
class M_generales extends CI_Model{

	function __construct(){
		parent::__construct();
		
	}
	
    function idUsuarioItem($id){
    $Query="SELECT p.idEmpresaColab,
                   e.empresaColabDesc 
              FROM planobra p join empresacolab e on p.idEmpresaColab=e.idEmpresaColab 
             WHERE p.itemPlan='".$id."'";
        $result = $this->db->query($Query,array());	   
	    if($result->row() != null) {  
        return $result->row_array();
        }else {return null;}
	}
    function ListarActividadesEstacion($id_estacion){
        $Query="SELECT DISTINCT 
                       s.nombre snombre,
                       s.id_subactividad 
                  from actividad a 
                  left join subactividad s on a.id_actividad=s.id_actividad 
                 where a.id_estacion=".$id_estacion;
        $result = $this->db->query($Query,array());
        return $result;
    }
    function Porcentaje($id_planobra_actividad){
    $Query="select sum(porcentaje) valor from agenda where id_planobra_actividad=".$id_planobra_actividad;
    $result = $this->db->query($Query,array()); 
    if($result->row() != null) {    
        return $result->row_array();
    }else {
        return 0;
    }
    }
    function PorcentajeZ($id_planobra_actividad_z){
    $Query="SELECT sum(porcentaje)valor 
             FROM agenda_z 
            WHERE id_planobra_actividad_z=".$id_planobra_actividad_z;
    $result = $this->db->query($Query,array()); 
    if($result->row() != null) {    
        return $result->row_array();
    }else {
        return 0;
    }
    }
    function IdCuadrilaGenerica($nombre){
    $Query="select id_usuario from usuario where usuario='cuadrilla_generica_".strtolower($nombre)."'";
    $result = $this->db->query($Query,array()); 
    if($result->row() != null) {    
        return $result->row_array();
    }else {
        return 0;
    }        
    }
    function ListarCuadrillaEmpresa($idUsuario){
    $Query="SELECT id_usuario, 
                   usuario 
              FROM usuario 
             WHERE id_perfil=12 
               AND id_eecc=".$idUsuario;
    $result = $this->db->query($Query,array());
        return $result;        
    }
    // function ItemPlanId($id){
    //     $query="SELECT po.idSubproyecto,
    //                    po.idEstadoPlan,
    //                    po.nombreProyecto,
    //                    (SELECT idZonal 
    //                       FROM central
    //                      WHERE idCentral = po.idCentral)idZonal
    //               FROM planobra po 
    //              WHERE po.itemPlan='".$id."'";
    //     $result = $this->db->query($query); 
    //     return $result->row_array();
    // }

    function itemPlanI($itemPlan) {
        $query= "SELECT po.idSubproyecto AS idSubProyecto,
                        po.idEstadoPlan,
                        po.nombreProyecto,
                        CASE WHEN po.paquetizado_fg IS NULL THEN
							(SELECT idZonal 
							   FROM central
							  WHERE idCentral = po.idCentral)
						     WHEN po.paquetizado_fg = 2 OR po.paquetizado_fg = 1 THEN 
							 (SELECT idZonal 
							   FROM pqt_central
							  WHERE idCentral = po.idCentralPqt) END idZonal
                   FROM planobra po 
                  WHERE po.itemPlan='".$itemPlan."'";
        $result = $this->db->query($query);
        return $result->row_array();          
    }
    
    function IngresarPlanObraActividad($id_planobra_actividad,$id_planobra,$id_actividad,$id_subactividad,$id_usuario){
    $post_data = array(
        'id_planobra_actividad'=>$id_planobra_actividad,        
        'id_planobra'=>$id_planobra,
        'id_actividad'=>$id_actividad,
        'id_subactividad'=>$id_subactividad,
        'id_cuadrilla'=>$id_usuario,
        'fecha'=>date("Y-m-d H:i:s"),
        'estado'=>1
    );
    $this->db->insert('planobra_actividad', $post_data);
    }
    function IngresarPlanObraActividadZ($id_planobra_actividad_z,$id_planobra,$idEstacion,$id_usuario){
    $post_data = array(
        'id_planobra_actividad_z'=>$id_planobra_actividad_z,        
        'id_planobra'=>$id_planobra,
        'idEstacion'=>$idEstacion,
        'id_usuario'=>$id_usuario,
        'fecha'=>date("Y-m-d H:i:s"),
        'estado'=>1
    );
    $this->db->insert('planobra_actividad_z', $post_data);
    }
    function IngresarAgenda($id_agenda,$id_planobra_actividad,$id_usuario,$coordenadas,$conversacion,$porcentaje){
    $post_data = array(
        'id_agenda'=>$id_agenda,        
        'id_planobra_actividad'=>$id_planobra_actividad,
        'id_usuario'=>$id_usuario,
        'coordenadas'=>$coordenadas,
        'fecha'=>date("Y-m-d H:i:s"),
        'conversacion'=>$conversacion,
        'porcentaje'=>$porcentaje,
        'estado_l'=>1
    );
    $this->db->insert('agenda', $post_data);
}
    function IngresarAgendaz($id_agenda,$id_planobra_actividad_z,$coordenadas,$id_usuario,$conversacion,$porcentaje){
    $post_data = array(
        'id_agenda_z'=>$id_agenda,        
        'id_planobra_actividad_z'=>$id_planobra_actividad_z,
        'coordenadas'=>$coordenadas,
        'conversacion'=>$id_usuario,
        'id_usuario'=>$id_usuario,
        'fecha'=>date("Y-m-d H:i:s"),
        'conversacion'=>$conversacion,
        'porcentaje'=>$porcentaje,
        'estado'=>1
    );
    $this->db->insert('agenda_z', $post_data);
}
    function IngresarAgendaImagen($id_agenda_imagen,$id_agenda,$valor,$fecha){
    $post_data = array(
        'id_agenda_imagen'=>$id_agenda_imagen,        
        'id_agenda'=>$id_agenda,
        'valor'=>$valor,
        'fecha'=>$fecha,
        'estado'=>1
    );
    $this->db->insert('agenda_imagen', $post_data);    
    }
    function Actividad100($id_planobra_actividad){
        $Query="update agenda 
                set estado_l = 2 
                where id_planobra_actividad=".$id_planobra_actividad." 
                and estado_l = 1"; 
        $this->db->query($Query);        
    }
    function Actividad100z($id_planobra_actividad_z){
    $Query="update agenda_z set estado=2 where id_planobra_actividad_z=".$id_planobra_actividad_z." and estado=1"; 
    $this->db->query($Query);        
    }
    function AgendaImagenId($idAgenda){
    $Query="select valor from agenda_imagen where id_agenda=".$idAgenda;
    $result = $this->db->query($Query,array()); 
    if($result->row() != null) {    
        return $result;
    }else {
        return 0;
    }     
    }
    function ultimo_registro($id,$tabla){
    $Query="select $id from $tabla order by $id DESC limit 0,1";
    $result = $this->db->query($Query,array())->row_array(); 
    return $result[$id];
}
    function CantidadAgendaId($id_planobra_actividad){
    if(!$this->session->userdata("zonasSession")){    
        $tabla="agenda";
        $id="id_planobra_actividad";
    }else{
        $tabla="agenda_z";
        $id="id_planobra_actividad_z";
    }
    $Query="select * from ".$tabla." where ".$id."=".$id_planobra_actividad;    
    $result = $this->db->query($Query);
    if($result->row() != null) {
        return $result->num_rows();
    }else{
        return 0;
    }
    }
    function SubactividadId($id_subactividad){
    $Query="select * from subactividad where id_subactividad=".$id_subactividad;
    $result = $this->db->query($Query,array())->row_array();
    return $result;    
    } 
    function AgendaId($id_planobra_actividad){
    if(!$this->session->userdata("zonasSession")){    
        $tabla="agenda";
        $id="id_planobra_actividad";
    }else{
        $tabla="agenda_z";
        $id="id_planobra_actividad_z";
    }    
    $Query="select * from ".$tabla." where ".$id."=".$id_planobra_actividad;
    $result = $this->db->query($Query,array());
    return $result;   
    }
    function UsuarioId($idUsuario){
    $Query="select usuario from usuario where id_usuario=".$idUsuario;
    $result = $this->db->query($Query,array())->row_array();
    return $result;   
    }
    function ListarImagenAgenda($id_planobra_actividad){
        if(!$this->session->userdata("zonasSession")){
            $tabla="agenda";
            $id="id_agenda";
            $sid="id_planobra_actividad";
        }else{
            $tabla="agenda_z";
            $id="id_agenda_z";
            $sid="id_planobra_actividad_z";
        }
    $Query="select ai.valor from agenda_imagen ai, ".$tabla." a where ai.id_agenda=a.".$id." and a.".$sid."=".$id_planobra_actividad;
    

    $result = $this->db->query($Query,array());
    return $result; 
    } 
    function EstacionId($id_estacion){
    $Query="select estacionDesc from estacion where idEstacion=".$id_estacion;    
    $result = $this->db->query($Query,array())->row_array();
    return $result;
    }
    function PlanObraActividadZ($id,$idEstacion){
    $Query="SELECT id_planobra_actividad_z 
              FROM planobra_actividad_z 
             WHERE id_planobra='".$id."' 
               AND idEstacion=".$idEstacion;
    $result = $this->db->query($Query,array());
    if($result->row() != null) {    
        return $result->row_array();
    }else {
        return 0;
    }    
    
    }
    function ListarProyecto($flgQuery = null){
        $sql = " ";
        if($flgQuery != null){
            $sql = " AND proyectoDesc NOT LIKE '%PIN%'";
        }
        $idEec = $this->session->userdata('eeccSession');
        $Query="SELECT * 
                FROM proyecto
                WHERE CASE WHEN ".$idEec." = 7 OR ".$idEec." = 8 THEN idProyecto = 5 ELSE idProyecto = idProyecto END 
                ".$sql."
                order by proyectoDesc";
        $result = $this->db->query($Query,array());
        return $result;
    }
    
    function ListarCategoriaToro(){
    $Query="select * from categoria_toro order by nombre";
    $result = $this->db->query($Query,array());
    return $result;
    }
    function ListarTipoToro(){
    $Query="select * from tipo_toro order by nombre";
    $result = $this->db->query($Query,array());
    return $result;
    }
    function ListarSubProyectoId($id){
    $Query="SELECT * 
              FROM subproyecto 
             WHERE idProyecto = ".$id;    
    $result = $this->db->query($Query,array());
    return $result;
    }
    function ListarSubProyecto($idProyecto){
    $Query="SELECT * 
              FROM subproyecto
             WHERE idProyecto = COALESCE(?, idProyecto)";      
    $result = $this->db->query($Query,array($idProyecto));
    return $result;
    }
    function PepDescc($id){
    $Query="select sap_coaxial6 descripcion from sap_coaxial where sap_coaxial6!='' and pep1='".$id."'";
    $result = $this->db->query($Query,array());
    return $result->row_array();
    }
    function PepDescf($id){
    $Query="select sap_coaxialcol4 descripcion from sap_fija where sap_coaxialcol4!='' and pep1='".$id."'";
    $result = $this->db->query($Query,array());
    return $result->row_array();    
    }
    
    function ListarSubProyectoNo2017($idProyecto){
        $Query="SELECT *
                FROM subproyecto
                WHERE idProyecto = COALESCE(?, idProyecto)
                AND	subProyectoDesc NOT REGEXP('2017')";
        $result = $this->db->query($Query,array($idProyecto));
        return $result;
    }
    
    function ListarSubProyectoNo2017Edit($idSubProyecto){
        $Query="SELECT *
                FROM subproyecto
                WHERE subProyectoDesc NOT REGEXP('2017')
                OR idSubProyecto = ?";
        $result = $this->db->query($Query,array($idSubProyecto));
        return $result;
    }
} 
