<?php
class M_toro extends CI_Model{

	function __construct(){
		parent::__construct();
		
	}
	
    function CrearToro($id_toro,$monto,$ae,$idProyecto,$usuario){
    $post_data = array(
        'id_toro'=>$id_toro,        
        'monto'=>$monto,
        'ae'=>$ae,
        'idProyecto'=>$idProyecto,
        'fecha'=>date("Y-m-d H:i:s"),
        'usuario'=>$usuario,
        'estado'=>1
    );
    $this->db->insert('toro', $post_data);	   
	   
	}
    function CrearToroDetalle($id_toro_detalle,$id_toro,$id_pep,$detalle,$precio,$cantidad,$id_usuario){
    $post_data = array(
        'id_toro_detalle'=>$id_toro_detalle,        
        'id_toro'=>$id_toro,
        'id_pep'=>$id_pep,
        'detalle'=>$detalle,
        'precio'=>$precio,
        'cantidad'=>$cantidad,
        'fecha'=>date("Y-m-d H:i:s"),
        'id_usuario'=>$id_usuario,
        'estado'=>1
    );
    $this->db->insert('toro_detalle', $post_data);     
       
    }
    function ToroLog($id_toro_log,$antes,$despues,$usuario){
    $post_data = array(
        'id_toro_log'=>$id_toro_log,        
        'antes'=>$antes,
        'despues'=>$despues,
        'usuario'=>$usuario,
        'fecha'=>date("Y-m-d H:i:s")
    );
    $this->db->insert('toro_log', $post_data);     
       
    }
    function ListarToro($id_toro,$idProyecto){
        $extra="";
        $orden="";
        if($idProyecto!="-1"){
        if($id_toro){
            $extra=" and t.id_toro='".trim($id_toro)."'";
        }else{
            if(($idProyecto||$idProyecto==0)&&$idProyecto!=""){
                $extra=" and t.idProyecto=".$idProyecto;
            }
        }
    }
    $Query="select t.*,p.proyectoDesc 
            from toro t 
            left join proyecto p on p.idProyecto=t.idProyecto 
            where t.estado=1 ".$extra."";
    $result = $this->db->query($Query,array());
    return $result;    
    }
    function ListarToroS(){
    $Query="select t.*,p.proyectoDesc,d.id_pep,sd.monto_inicial 
            from toro t 
            left join proyecto p on p.idProyecto=t.idProyecto
            left join toro_detalle d on t.id_toro=d.id_toro
            left join sap_detalle sd on d.id_pep=sd.pep1
            where t.estado=1 order by t.id_toro";
    $result = $this->db->query($Query,array());
    return $result;    
    }
    function ListarToroDetalle($id){
    $Query="SELECT id_pep,(CASE 
            WHEN SUBSTR(id_pep,1,1)='P' THEN (SELECT sap_coaxialcol4 FROM sap_fija where pep1=CONCAT('PEP ',id_pep) and nivel=1)

            WHEN SUBSTR(id_pep,1,1)='S' THEN (SELECT sap_coaxial6 FROM sap_coaxial where pep1=CONCAT('PEP ',id_pep) and nivel=1)
            ELSE
            NULL
            END
            ) as detalle,(CASE 
            WHEN SUBSTR(id_pep,1,1)='P' THEN (SELECT presupuesto FROM sap_fija where pep1=CONCAT('PEP ',id_pep) and nivel=1)

            WHEN SUBSTR(id_pep,1,1)='S' THEN (SELECT presupuesto FROM sap_coaxial where pep1=CONCAT('PEP ',id_pep) and nivel=1)
            ELSE
            NULL
            END
            ) as presupuesto
            from toro_detalle p 
            where p.id_toro='".$id."'
            ";
    $result = $this->db->query($Query,array());
    if($result->row()!=null){
    return $result;
    }else{
    return 0;    
    }
    }
    function toroId($id){
    $Query="select * from toro where id_toro='".$id."'";
    $result = $this->db->query($Query,array());
    if($result->row() != null) {    
        return $result->row_array();
    }else {
        return 0;
    }
    
    }
    function pepId($id){
    $Query="SELECT (CASE 
            WHEN SUBSTR('".$id."',1,1)='P' THEN (SELECT presupuesto FROM sap_fija where pep1=CONCAT('PEP ','".$id."') and nivel=1)

            WHEN SUBSTR('".$id."',1,1)='S' THEN (SELECT presupuesto FROM sap_coaxial where pep1=CONCAT('PEP ','".$id."') and nivel=1)
            ELSE
            NULL
            END
            ) as presupuesto
            
            ";
    $result=$this->db->query($Query,array());
    if($result->row()!=null){return $result->row_array();}else{return 0;}         
    }
    function pepExisteRegistro($id_toro,$id){
    $Query='select * from toro_detalle where id_pep="'.trim($id).'"';
    $result=$this->db->query($Query,array());
    if($result->row()!=null){
    $Query='select * from toro_detalle where id_pep="'.trim($id).'" and id_toro="'.$id_toro.'"';
    $result=$this->db->query($Query,array());
    if($result->row()!=null){
        return 2;
    }else{
        return 1;
    }    
    }else{
        return 0;
    }    
    }
    function MaxPep(){
        $Query="select max(m) as cantidad from (select count(id_pep) as m from toro_detalle group by id_toro) t";
        $result=$this->db->query($Query,array());
        return $result->row_array()["cantidad"];
    }

    function ActualizarToro($id,$ae,$idProyecto,$monto){
        $Query="update toro set ae='".$ae."',idProyecto=".$idProyecto.",monto='".$monto."' where id_toro='".$id."'";    
        $this->db->query($Query);
    }
    
        
    function ListarPep(){
    $Query="select * from sap_detalle s, toro_detalle t where t.id_pep!=s.pep1";
    $result = $this->db->query($Query,array());
    return $result;
    }

    function ToroDetalleId($id){
        $Query="select t.*, sd.monto_inicial, u.nombre, s.subProyectoDesc, p.proyectoDesc 
                from toro_detalle t 
                left join sap_detalle sd on t.id_pep=sd.pep1 
                left join usuario u on u.id_usuario=t.id_usuario
                left join peptoro pt on pt.id_pep=t.id_pep 
                left join subproyecto s on s.idSubProyecto=pt.idSubProyecto 
                left join proyecto p on s.idProyecto=p.idProyecto 
                where t.id_toro='".$id."'";

        $result = $this->db->query($Query,array());
    return $result;
    }
	function SumaDetalle($id){
        $Query="SELECT (CASE 
            WHEN SUBSTR(id_pep,1,1)='P' THEN (SELECT SUM(REPLACE(REPLACE(presupuesto,'\"',''),',','')) FROM sap_fija where pep1=CONCAT('PEP ',id_pep) and nivel=1)

            WHEN SUBSTR(id_pep,1,1)='S' THEN (SELECT SUM(REPLACE(REPLACE(presupuesto,'\"',''),',','')) FROM sap_coaxial where pep1=CONCAT('PEP ',id_pep) and nivel=1)
            ELSE
            NULL
            END
            ) as valor
            from toro_detalle p 
            where p.id_toro='".$id."'";
        $result = $this->db->query($Query,array());
        return $result->row_array();
    }
    function ActualizarPepR($id_pep,$idSubProyecto,$id_tipo_toro,$id_categoria_toro){
        $Query="select id_pep from peptoro where id_pep='".$id_pep."'";
        $result = $this->db->query($Query,array());
        if($result->row()!=null){
        $Query="update peptoro set idSubProyecto=".$idSubProyecto.", id_tipo_toro=".$id_tipo_toro.", id_categoria_toro=".$id_categoria_toro." where id_pep='".$id_pep."'";
        $this->db->query($Query,array());
        }else{
        $post_data = array(
        'id_peptoro'=>'',        
        'id_pep'=>$id_pep,
        'idSubProyecto'=>$idSubProyecto,
        'id_tipo_toro'=>$id_tipo_toro,
        'id_categoria_toro'=>$id_categoria_toro,
        'fecha'=>date("Y-m-d H:i:s"),
        'estado'=>1
    );
    $this->db->insert('peptoro', $post_data);    
        }
    }
    function ListarPepR($idSubProyecto){
        $filtro = '';
        if($idSubProyecto != null && $idSubProyecto != ''){
            $filtro = 'WHERE s.idSubProyecto = '.$idSubProyecto;
        }
    $Query="select *, format(sd.monto_temporal,2) as monto_temporal from (
            SELECT pt.*,tt.nombre tnombre,su.subProyectoDesc, t.id_toro_detalle, t.id_toro id_toro,o.monto,SUBSTR(s.pep1,5) pep1,s.sap_coaxialcol4 as detalle,s.plan,s.presupuesto,s.real,s.comprometido,s.planresord,s.disponible 
            FROM sap_fija s 
            LEFT JOIN toro_detalle t on SUBSTR(s.pep1,5)=t.id_pep
            LEFT JOIN peptoro pt on pt.id_pep=SUBSTR(s.pep1,5) 
            LEFT JOIN toro o on o.id_toro=t.id_toro
            LEFT JOIN tipo_toro tt on tt.id_tipo_toro=pt.id_tipo_toro 
            LEFT JOIN subproyecto su on su.idSubProyecto=pt.idSubProyecto          
            WHERE s.nivel=1 
            UNION
            SELECT pt.*,tt.nombre tnombre,su.subProyectoDesc, t.id_toro_detalle, t.id_toro id_toro,o.monto,SUBSTR(c.pep1,5) pep1,c.sap_coaxial6 as detalle,c.plan,c.presupuesto,c.real,c.comprometido,c.planresord,c.disponible 
            FROM sap_coaxial c
            LEFT JOIN toro_detalle t on SUBSTR(c.pep1,5)=t.id_pep
            LEFT JOIN peptoro pt on pt.id_pep=SUBSTR(c.pep1,5)
            LEFT JOIN toro o on o.id_toro=t.id_toro  
            LEFT JOIN tipo_toro tt on tt.id_tipo_toro=pt.id_tipo_toro
            LEFT JOIN subproyecto su on su.idSubProyecto=pt.idSubProyecto         
            WHERE c.nivel=1     
        UNION
         SELECT pt.*,tt.nombre tnombre,su.subProyectoDesc, t.id_toro_detalle, t.id_toro id_toro,o.monto,pt.id_pep as pep1,null ,null ,null, null , null, null,null
         FROM peptoro pt
            LEFT JOIN toro_detalle t on pt.id_pep=t.id_pep
            LEFT JOIN toro o on o.id_toro=t.id_toro  
                    LEFT JOIN tipo_toro tt on tt.id_tipo_toro=pt.id_tipo_toro
                    LEFT JOIN subproyecto su on su.idSubProyecto=pt.idSubProyecto 
        WHERE pt.reg_tmp = 2
            ) s  LEFT JOIN sap_detalle sd ON sd.pep1 = s.id_pep  ".$filtro." ORDER BY s.idSubProyecto, s.fecha_programacion ";

    $result=$this->db->query($Query,array());
    return $result;
    }
    function Crearpeptoro($id_peptoro,$id_pep,$idSubProyecto,$id_tipo_toro,$id_categoria_toro){
    $post_data = array(
        'id_peptoro'=>$id_peptoro,        
        'id_pep'=>$id_pep,
        'idSubProyecto'=>$idSubProyecto,
        'id_tipo_toro'=>$id_tipo_toro,
        'id_categoria_toro'=>$id_categoria_toro,
        'fecha'=>date("Y-m-d H:i:s"),
        'estado'=>1
    );
    $this->db->insert('peptoro', $post_data);    
    }
    function GetPepId($id){
        $Query="SELECT * FROM toro_detalle WHERE id_pep='".$id."'";
        $result=$this->db->query($Query);
        if($result->row()!=null){
            return $result->row_array();
        }else{
            return 0;
        }
    }
    function ListarToroId(){
        $Query="SELECT id_toro FROM toro";
        $result=$this->db->query($Query);
        return $result;
        
    }
    function detallePep($pep){
        $Query="SELECT * FROM peptoro WHERE id_pep='".$pep."'";
        $result=$this->db->query($Query);
        return $result->row_array();
    }
    function ListarTipoPep(){
        $Query="SELECT * FROM tipo_toro";
        $result=$this->db->query($Query);
        return $result;
    }
    function FiltroProyecto($id){
        $Query="SELECT DISTINCT(idSubProyecto) idSubProyecto FROM peptoro";
        $result=$this->db->query($Query);
        $t=0;
        $im="";
        $extra="";
        if($result->row()){
        foreach ($result->result_array() as $row) {
           $t++;
           if($t!=1){
             $im.=",";
           }
           $im.=$row["idSubProyecto"];
        }
        if($im){
            $extra=" and idSubProyecto in (".$im.")";
        }   
    }

        $Query="SELECT * FROM subproyecto where idProyecto in (".$id.")".$extra;

        $result=$this->db->query($Query);
        if($result->row()!=null){
            return $result;
        }else{
            return 0;
        }
    }
    
    function   getAllSubProyectosByIdProyectos($idProyecto){
        $Query = " SELECT * FROM subproyecto where idProyecto IN (".$idProyecto.") order by idProyecto, subProyectoDesc; " ;
        $result = $this->db->query($Query,array());
        return $result;
    }   
    
    
    function FiltrarPep($idProyecto,$idSubProyecto,$tipo){
        $extra="";
        if($idProyecto){
            $extra.=" and o.idProyecto in (".implode(",",$idProyecto).")";
        }
        if($idSubProyecto){
            $extra=" and pt.idSubProyecto in (".implode(",",$idSubProyecto).")";
        }
        if($tipo){
            $extra.=" and pt.id_tipo_toro in (".implode(",",$tipo).")";
        }

        $Query="select * from (
            SELECT pt.*,tt.nombre tnombre,su.subProyectoDesc,t.id_toro id_toro,o.monto,SUBSTR(s.pep1,5) pep1,s.sap_coaxialcol4 as detalle,s.plan,s.presupuesto,s.real,s.comprometido,s.planresord,s.disponible 
            FROM sap_fija s 
            LEFT JOIN toro_detalle t on SUBSTR(s.pep1,5)=t.id_pep
            LEFT JOIN peptoro pt on pt.id_pep=SUBSTR(s.pep1,5) 
            LEFT JOIN toro o on o.id_toro=t.id_toro
            LEFT JOIN tipo_toro tt on tt.id_tipo_toro=pt.id_tipo_toro
            LEFT JOIN subproyecto su on su.idSubProyecto=pt.idSubProyecto           
            WHERE s.nivel=1 ".$extra."
            UNION
            SELECT pt.*,tt.nombre tnombre,su.subProyectoDesc,t.id_toro id_toro,o.monto,SUBSTR(c.pep1,5) pep1,c.sap_coaxial6 as detalle,c.plan,c.presupuesto,c.real,c.comprometido,c.planresord,c.disponible 
            FROM sap_coaxial c
            LEFT JOIN toro_detalle t on SUBSTR(c.pep1,5)=t.id_pep
            LEFT JOIN peptoro pt on pt.id_pep=SUBSTR(c.pep1,5)
            LEFT JOIN toro o on o.id_toro=t.id_toro  
            LEFT JOIN tipo_toro tt on tt.id_tipo_toro=pt.id_tipo_toro
            LEFT JOIN subproyecto su on su.idSubProyecto=pt.idSubProyecto         
            WHERE c.nivel=1 ".$extra."
            ) s ORDER BY s.idSubProyecto, s.fecha_programacion ";
    $result=$this->db->query($Query,array());
    return $result;
    
    }
    function ListarProyecto(){
        $Query="SELECT DISTINCT (CASE WHEN p.idProyecto IS NULL THEN 0 ELSE p.idProyecto END) AS idProyecto,
(CASE WHEN p.proyectoDesc IS NULL THEN 'SIN PROYECTO' ELSE p.proyectoDesc END) AS proyectoDesc
                FROM peptoro t
                LEFT JOIN subproyecto sp ON sp.idSubProyecto = t.idSubProyecto
                LEFT JOIN proyecto p ON sp.idProyecto = p.idProyecto
ORDER BY p.proyectoDesc;";
        $result=$this->db->query($Query);
        return $result;
    }
    
    /*************************27-06-2018*****************************/
    function Eliminar_Toro($id){
        $Query="DELETE FROM toro WHERE id_toro='".$id."'";
        $this->db->query($Query);
        $Query="DELETE FROM toro_detalle WHERE id_toro='".$id."'";
        $this->db->query($Query);
    }
    
    /***********************************************************/
    
    
    function savePepToro($id_pep, $id_toro, $idSubProyecto, $detalle, $precio, $cantidad, $id_tipo_toro, $fec_programacion, $idArea){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->trans_begin();
            $post_data = array(
                'id_pep'            =>  $id_pep,
                'idSubProyecto'     =>  $idSubProyecto,
                'id_tipo_toro'      =>  $id_tipo_toro,
                'id_categoria_toro' =>  0,//NO SE QUE ES?
                'fecha'             =>  date("Y-m-d H:i:s"),
                'estado'            =>  1,
                'reg_tmp'           =>  2,
                'fecha_programacion'=>  $fec_programacion
            );
            $this->db->insert('peptoro', $post_data);  
            if($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar en peptoro');
            }else{
                $post_data = array(
                    
                    'id_toro'   =>  $id_toro,
                    'id_pep'    =>  $id_pep,
                    'detalle'   =>  $detalle,
                    'precio'    =>  $precio,
                    'cantidad'  =>  $cantidad, 
                    'fecha'     =>  date("Y-m-d H:i:s"),
                    'id_usuario'=>  $this->session->userdata("idPersonaSession"),
                    'estado'    =>  1
                );
                $this->db->insert('toro_detalle', $post_data);	                
                if($this->db->affected_rows() != 1) {
                    $this->db->trans_rollback();
                    throw new Exception('Error al insertar en toro_detalle');
                }else{
                    //log_message('error', 'datos1:'.$idArea);
                    //log_message('error', 'datos2:'.print_r($idArea, true));
                    $subProyectoDesc =  $this->getIdSubProyectoDescByIdSubPro($idSubProyecto);                    
                    $arrayPep1SubPro = array();
                    foreach($idArea as $uniArea){
                        $datosArea = $this->getEstadoPlanByItemplanNombre($uniArea);
                        if($datosArea   !=  null && $subProyectoDesc != null){
                            $subProPep = array(
                                'subproyecto'   =>  $subProyectoDesc,
                                'estacion'      =>  $datosArea['estacionDesc'],
                                'area'          =>  $datosArea['areaDesc'],
                                'pep1'          =>  $id_pep,
                                'idSubProyecto' =>  $idSubProyecto,
                                'idFase'        =>  ID_FASE_2019
                            );
                            
                            array_push($arrayPep1SubPro, $subProPep);
                        }else{
                            $this->db->trans_rollback();
                            throw new Exception('Error al insertar en area no encontrada');
                        }
                    }
                            $this->db->insert_batch('pep1_subpro', $arrayPep1SubPro);
                            if($this->db->trans_status() === FALSE) {
                                $this->db->trans_rollback();
                                throw new Exception('Error al insertar en pep1_subpro');
                            }else{
                                $arrayPep1Pep2 = array();
                                $mes = date("m");
                                for($i=$mes; $i<=12 ; $i++){                                    
                                    $formateado = '000';
                                    if(strlen($i)==1){
                                        $formateado = '00'.$i;
                                    }else if(strlen($i)==2){
                                        $formateado = '0'.$i;
                                    }
                                    $arrayIn =   array('pep1'   =>  $id_pep,
                                                        'pep2'  =>  $id_pep.'-'.$formateado);
                                    
                                    array_push($arrayPep1Pep2, $arrayIn);
                                }                                
                                    $this->db->insert_batch('pep1_pep2', $arrayPep1Pep2);
        	                    if ($this->db->trans_status() === FALSE) {
        	                        $this->db->trans_rollback();
        	                        throw new Exception('Hubo un error al insertar el pep1_pep2.');
        	                    }else{	
            	                    $data['error']    = EXIT_SUCCESS;
            	                    $data['msj']      = 'Se actualizo correctamente!';
            	                    $this->db->trans_commit();
        	                    }
                            }
                        }             
                    }
               
        }catch(Exception $e){
            $data['msj']   = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
    }
    
    function ActualizarPEP($id_pep,$id_toro,$precio,$cantidad,$detalle,$idSubProyecto,$id_tipo_toro, $fec_programacion, $idArea, $oldSubPro){
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            
            $idFase = ID_FASE_2019;
            $this->db->trans_begin();
                       
            $dataUpdate = array(
                "idSubProyecto" => $idSubProyecto,
                "id_tipo_toro" => $id_tipo_toro,
                "fecha_programacion" => $fec_programacion
            );
             
            $this->db->where('id_pep', $id_pep);
            $this->db->update('peptoro', $dataUpdate);
            
             
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Hubo un error al actualizar la tabla peptoro');
            }else{
                
                $Query = "SELECT distinct idFase, count(1) as cont from pep1_subpro 
                            where idSubProyecto = ? 
                            and pep1 = ? 
                            LIMIT 1;";
                $result = $this->db->query($Query,array($idSubProyecto,$id_pep));
                if($result->row()->cont >= 1){// si la existe
                    $idFase =   $result->row()->idFase;
                }
                
                $this->db->where('idSubProyecto', $oldSubPro);
                $this->db->where('idFase', $idFase);
                $this->db->where('pep1', $id_pep);
                $this->db->delete('pep1_subpro');
                 
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    throw new Exception('ERROR DELETE pep1_subpro');
                } else {
                    
                    $subProyectoDesc =  $this->getIdSubProyectoDescByIdSubPro($idSubProyecto);
                    $arrayPep1SubPro = array();
                    foreach($idArea as $uniArea){
                        $datosArea = $this->getEstadoPlanByItemplanNombre($uniArea);
                        if($datosArea   !=  null && $subProyectoDesc != null){
                            $subProPep = array(
                                'subproyecto'   =>  $subProyectoDesc,
                                'estacion'      =>  $datosArea['estacionDesc'],
                                'area'          =>  $datosArea['areaDesc'],
                                'pep1'          =>  $id_pep,
                                'idSubProyecto' =>  $idSubProyecto,
                                'idFase'        =>  $idFase
                            );
                    
                            array_push($arrayPep1SubPro, $subProPep);
                        }else{
                            $this->db->trans_rollback();
                            throw new Exception('Error al insertar en area no encontrada');
                        }
                    }
                    $this->db->insert_batch('pep1_subpro', $arrayPep1SubPro);
                    if($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();
                        throw new Exception('Error al insertar en pep1_subpro');
                    }else{                   
                        
                        
                        $Query = "SELECT COUNT(1) as cont FROM pep1_pep2 where pep1= ?";
                        $result = $this->db->query($Query,array($id_pep));
                        if($result->row()->cont == 0){// si la existe
                            $arrayPep1Pep2 = array();
                            $mes = date("m");
                            for($i=$mes; $i<=12 ; $i++){
                                $formateado = '000';
                                if(strlen($i)==1){
                                    $formateado = '00'.$i;
                                }else if(strlen($i)==2){
                                    $formateado = '0'.$i;
                                }
                                $arrayIn =   array('pep1'   =>  $id_pep,
                                    'pep2'  =>  $id_pep.'-'.$formateado);
                            
                                array_push($arrayPep1Pep2, $arrayIn);
                            }
                            $this->db->insert_batch('pep1_pep2', $arrayPep1Pep2);
                            if ($this->db->trans_status() === FALSE) {
                                $this->db->trans_rollback();
                                throw new Exception('Hubo un error al insertar el pep1_pep2.');
                            }
                        }

                        $Query="SELECT id_pep FROM toro_detalle WHERE id_pep='".$id_pep."'";
                        if($this->db->query($Query)->row()!=null){
                            
                            $dataUpdate = array(
                                "id_toro" => $id_toro,
                                "precio" => $precio,
                                "cantidad" => $cantidad,
                                "detalle"   =>  $detalle
                            );
                             
                            $this->db->where('id_pep', $id_pep);
                            $this->db->update('toro_detalle', $dataUpdate);
                            if ($this->db->trans_status() === FALSE) {
                                throw new Exception('Hubo un error al actualizar la tabla toro_detalle');
                            }else{
                                $data['error'] = EXIT_SUCCESS;
                                $data['msj'] = 'Se actualizo correctamente!';
                                $this->db->trans_commit();
                            }
                            
                        }else{
                            $post_data = array(
                                'id_toro_detalle'=>'',
                                'id_toro'=>$id_toro,
                                'id_pep'=>$id_pep,
                                'detalle'=>$detalle,
                                'precio'=>$precio,
                                'cantidad'=>$cantidad,
                                'fecha'=>date("Y-m-d H:i:s"),
                                'id_usuario'=>$this->session->userdata("idPersonaSession"),
                                'estado'=>1
                            );
                            $this->db->insert('toro_detalle', $post_data);
                            if($this->db->affected_rows() != 1) {
                                $this->db->trans_rollback();
                                throw new Exception('Error al insertar toro_detalle');
                            }else{
                                $data['error'] = EXIT_SUCCESS;
                                $data['msj'] = 'Se actualizo correctamente!';
                                $this->db->trans_commit();
                            }
                        }
                        
                        
                    }                  
                }                
                
          }        
      
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
         
        return $data;
    }
    
    
    
    
    function ListarPepReporte(){
    $Query = 'SELECT tb.*, sd.monto_temporal, timestampdiff(month,tb.fecha_programacion ,curdate()) as dif_meses FROM (
                select * from (
                SELECT pt.*,tt.nombre tnombre,su.subProyectoDesc,t.id_toro id_toro,o.monto,SUBSTR(s.pep1,5) pep1,s.sap_coaxialcol4 as detalle,s.plan,s.presupuesto,s.real,s.comprometido,s.planresord,s.disponible, t.detalle as comentario
                FROM sap_fija s
                LEFT JOIN toro_detalle t on SUBSTR(s.pep1,5)=t.id_pep
                LEFT JOIN peptoro pt on pt.id_pep=SUBSTR(s.pep1,5)
                LEFT JOIN toro o on o.id_toro=t.id_toro
                LEFT JOIN tipo_toro tt on tt.id_tipo_toro=pt.id_tipo_toro
                LEFT JOIN subproyecto su on su.idSubProyecto=pt.idSubProyecto
                WHERE s.nivel=1
                UNION
                SELECT pt.*,tt.nombre tnombre,su.subProyectoDesc,t.id_toro id_toro,o.monto,SUBSTR(c.pep1,5) pep1,c.sap_coaxial6 as detalle,c.plan,c.presupuesto,c.real,c.comprometido,c.planresord,c.disponible, t.detalle as comentario
                FROM sap_coaxial c
                LEFT JOIN toro_detalle t on SUBSTR(c.pep1,5)=t.id_pep
                LEFT JOIN peptoro pt on pt.id_pep=SUBSTR(c.pep1,5)
                LEFT JOIN toro o on o.id_toro=t.id_toro
                LEFT JOIN tipo_toro tt on tt.id_tipo_toro=pt.id_tipo_toro
                LEFT JOIN subproyecto su on su.idSubProyecto=pt.idSubProyecto
                WHERE c.nivel=1 ) s ) as tb, sap_detalle sd
                where tb.id_pep = sd.pep1
                ORDER BY tb.idSubProyecto, tb.fecha_programacion';
    $result=$this->db->query($Query,array());
    return $result;
    }
    
    function FiltrarPepReporte($idProyecto,$idSubProyecto,$tipo){
        $extra="";
       if($idProyecto!=''){
            
            $listPro = explode(',', $idProyecto);
            if (in_array('0', $listPro)){
                $extra.=" and (su.idProyecto in (".$idProyecto.") or su.idProyecto IS NULL) ";
            }else{
                if($idProyecto == 22 || $idProyecto == 8){
                    $idProyecto = '22,8';
                }
                $extra.=" and su.idProyecto in (".$idProyecto.")";
            }           
        }
        if($idSubProyecto!=''){
            $extra.=" and pt.idSubProyecto in (".$idSubProyecto.")";
        }
        if($tipo!=''){
            $extra.=" and pt.id_tipo_toro in (".$tipo.")";
        }
    
        $Query="SELECT tb.*, sd.monto_temporal, timestampdiff(month,tb.fecha_programacion ,curdate()) as dif_meses FROM (
                select * from (
                SELECT pt.*,tt.nombre tnombre,su.subProyectoDesc,t.id_toro id_toro,o.monto,SUBSTR(s.pep1,5) pep1,s.sap_coaxialcol4 as detalle,s.plan,s.presupuesto,s.real,s.comprometido,s.planresord,s.disponible, t.detalle as comentario
                FROM sap_fija s
				LEFT JOIN pep_bianual_2 b ON s.pep1 = CONCAT('PEP ',b.pep)
                LEFT JOIN toro_detalle t on SUBSTR(s.pep1,5)=t.id_pep
                LEFT JOIN peptoro pt on pt.id_pep=SUBSTR(s.pep1,5)
                LEFT JOIN toro o on o.id_toro=t.id_toro
                LEFT JOIN tipo_toro tt on tt.id_tipo_toro=pt.id_tipo_toro
                LEFT JOIN subproyecto su on su.idSubProyecto=pt.idSubProyecto
                WHERE s.nivel=1 ".$extra."
				  AND b.pep IS NULL
                UNION
                SELECT pt.*,tt.nombre tnombre,su.subProyectoDesc,t.id_toro id_toro,o.monto,SUBSTR(c.pep1,5) pep1,c.sap_coaxial6 as detalle,c.plan,c.presupuesto,c.real,c.comprometido,c.planresord,c.disponible, t.detalle as comentario
                FROM sap_coaxial c
                LEFT JOIN toro_detalle t on SUBSTR(c.pep1,5)=t.id_pep
                LEFT JOIN peptoro pt on pt.id_pep=SUBSTR(c.pep1,5)
                LEFT JOIN toro o on o.id_toro=t.id_toro
                LEFT JOIN tipo_toro tt on tt.id_tipo_toro=pt.id_tipo_toro
                LEFT JOIN subproyecto su on su.idSubProyecto=pt.idSubProyecto
                WHERE c.nivel=1 ".$extra."
                    
                    ) s ) as tb, sap_detalle sd
                WHERE tb.id_pep = sd.pep1 
                ORDER BY tb.idSubProyecto, tb.fecha_programacion";
        $result=$this->db->query($Query,array());
        return $result;
    
    }
	
	function getDetallePepBianual($flg_tipo) {
		$sql = "SELECT null as id_peptoro,
                       pb.pep as id_pep, 
                       null as idSubProyecto,
                       null as id_tipo_toro,
                       null as id_categoria_toro,
                       null as fecha,
                       pb.estado,
                       null as reg_tmp,
                       null as fecha_programacion,
                       null as tnombre,
                       'PEP BIANUAL' as subProyectoDesc,
                       null as id_toro,
                       null as monto,
                       SUBSTR(s.pep1,5) pep1,
                       s.sap_coaxialcol4 as detalle,
                       s.plan,
                       s.presupuesto,
                       s.real,
                       s.comprometido,
                       s.planresord,
                       s.disponible,
                       null as comentario,
                       sd.monto_temporal,
                       null as dif_meses
			      FROM  sap_fija s, 
                       pep_bianual_2 pb left join sap_detalle sd on pb.pep = sd.pep1
                 WHERE concat('PEP ',pb.pep) = s.pep1
				   AND s.nivel = 1
				   AND pb.flg_tipo = ?";
		$result=$this->db->query($sql,array($flg_tipo));
        return $result;
	}
    
    function deletePepTemp($id_td, $id_pt, $idSub, $pep){
        $rpta['error'] = EXIT_ERROR;
        $rpta['msj'] = null;
        try{
    
            $this->db->trans_begin();
            $this->db->where('id_toro_detalle', $id_td);
            $this->db->delete('toro_detalle');
            
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                throw new Exception("Error al Eliminar Sisego Toro - Detalle");
            }else {
                $this->db->where('id_peptoro', $id_pt);
                $this->db->delete('peptoro');
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    throw new Exception("Error al Eliminar Pep - Toro");
                }else {
                    
                    $this->db->where('idSubProyecto', $idSub);
                    $this->db->where('idFase', ID_FASE_2019);//TODO LO NUEVO SERA 2019 HARCODEAMOS
                    $this->db->where('pep1', $pep);
                    $this->db->delete('pep1_subpro');
                     
                    if ($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();
                        throw new Exception('ERROR DELETE pep1_subpro');
                    } else {
                        $this->db->where('pep1', $pep);
                        $this->db->delete('pep1_pep2');                        
                        if ($this->db->trans_status() === FALSE) {
                            $this->db->trans_rollback();
                            throw new Exception("Error al Eliminar Sisego Toro - Detalle");
                        }else {
                            $this->db->trans_commit();
                            $rpta['msj'] = 'Se elimino correctamente Toro - Pep';
                            $rpta['error']  = EXIT_SUCCESS;
                        }   
                    }
                }
            }
    
        }catch(Exception $e){
            $rpta['msj'] = $e->getMessage();
        }
        return $rpta;
    }
    
    function getEstatusPresupuesto(){
        $Query = "SELECT (CASE WHEN tb.idProyecto IS NULL THEN 0 ELSE tb.idProyecto END) AS idProyecto, 
							(CASE WHEN tb.proyectoDesc IS NULL THEN 'SIN PROYECTO' ELSE tb.proyectoDesc END) AS proyectoDesc,
							ROUND(SUM(presupuesto),2) AS presupuesto, 
							ROUND(SUM(reall),2) as reall, 
							ROUND(SUM(comprometido),2) as comprometido, 
							ROUND(SUM(planresord),2) as planresord, 
							ROUND(SUM(disponible),2) as disponible,
							ROUND(((SUM(disponible)*100)/SUM(presupuesto))) as percent,
							NULL as flg_tipo
					FROM (
							SELECT 	p.idProyecto, p.proyectoDesc, 
									REPLACE(REPLACE(s.presupuesto,'\"',''),',','') as presupuesto,	
									REPLACE(REPLACE(s.real,'\"',''),',','') as reall,	
									REPLACE(REPLACE(s.comprometido,'\"',''),',','') as comprometido,	
									REPLACE(REPLACE(s.planresord,'\"',''),',','') as planresord,	
									REPLACE(REPLACE(s.disponible,'\"',''),',','') as disponible
							FROM 	sap_fija s
									LEFT JOIN pep_bianual_2 b ON s.pep1 = CONCAT('PEP ',b.pep)
									LEFT JOIN	peptoro t ON SUBSTR(s.pep1,5)	=	t.id_pep
									LEFT JOIN	subproyecto sp	ON	sp.idSubProyecto = t.idSubProyecto
									LEFT JOIN	proyecto p	ON	sp.idProyecto = p.idProyecto 	
							WHERE 	s.nivel	=	1
                              AND b.pep IS NULL
						
					) AS tb
					GROUP BY proyectoDesc
					UNION ALL
					SELECT 	(CASE WHEN tb.idProyecto IS NULL THEN 0 ELSE tb.idProyecto END) AS idProyecto, 
							(CASE WHEN tb.proyectoDesc IS NULL THEN 'SIN PROYECTO' ELSE tb.proyectoDesc END) AS proyectoDesc,
							ROUND(SUM(presupuesto),2) AS presupuesto, 
							ROUND(SUM(reall),2) as reall, 
							ROUND(SUM(comprometido),2) as comprometido, 
							ROUND(SUM(planresord),2) as planresord, 
							ROUND(SUM(disponible),2) as disponible,
							ROUND(((SUM(disponible)*100)/SUM(presupuesto))) as percent,
							tb.flg_tipo
					FROM (
							SELECT 9999 as idProyecto, CASE WHEN pb.flg_tipo = 1 THEN 'BIANUAL COMPROMETIDA' 
															WHEN pb.flg_tipo = 2 THEN 'BIANUAL DISPONIBLE'	
															WHEN pb.flg_tipo = 3 THEN 'BIANUAL VR' END as proyectoDesc ,  
									REPLACE(REPLACE(s.presupuesto,'\"',''),',','') as presupuesto,	
											REPLACE(REPLACE(s.real,'\"',''),',','') as reall,	
											REPLACE(REPLACE(s.comprometido,'\"',''),',','') as comprometido,	
											REPLACE(REPLACE(s.planresord,'\"',''),',','') as planresord,	
											REPLACE(REPLACE(s.disponible,'\"',''),',','') as disponible,
											pb.flg_tipo
							  FROM pep_bianual_2 pb, sap_fija s where concat('PEP ',pb.pep) = s.pep1
							   AND s.nivel = 1
						 ) AS tb
					GROUP BY proyectoDesc";    
        $result=$this->db->query($Query,array());
        return $result;
    }
    
     function   getSeriesByProyecto($proyecto, $limite_day){
    
        $query = "SELECT tb.fecha_registro, lp.descripcion, lp.percent FROM (
            	select distinct fecha_registro from log_toro_proyecto_estatus order by DATE(fecha_registro) desc limit ".$limite_day.") as tb
            	LEFT JOIN log_toro_proyecto_estatus  lp ON
            	lp.fecha_registro = tb.fecha_registro AND lp.descripcion = ?";
         
        $result = $this->db->query($query,array($proyecto));
        return $result;
    }
    
    function   getCategoriasByLimitDays($limite_day){
    
        $query = '	SELECT tb.fecha_registro FROM (
                	select distinct DATE_FORMAT(fecha_registro,"%d/%m/%Y") as fecha_registro from log_toro_proyecto_estatus order by DATE(fecha_registro) desc limit '.$limite_day.') as tb
                	order by tb.fecha_registro;';
    
        $result = $this->db->query($query,array());
        return $result;
    }
    
    function   getSeriesBySubProyectoAndLimitDays($limite_day){
    
        $query = "	select distinct descripcion from log_toro_proyecto_estatus where fecha_registro in (SELECT tb.* FROM (
                	select distinct fecha_registro from log_toro_proyecto_estatus order by DATE(fecha_registro) desc limit ".$limite_day.") as tb
                	order by tb.fecha_registro);";
    
        $result = $this->db->query($query,array());
        return $result;
    }
    
    
    function getDetallePTRByPEB($pep1){

        $variable = '"';

        $sql = "      SELECT TRIM(RIGHT(sf.desc_pep2,14)) AS po,
                             d.poCod AS ptr,
                             wu.est_innova,
                             REPLACE(sf.comprometido,'".$variable."','') AS comprometido,
                             wu.valoriz_m_o AS monto_mo,
                             wu.valoriz_material AS valor_mat,
                             (CASE WHEN d.itemPlan IS NULL THEN '-' ELSE d.itemPLan END) AS itemPlan,
                             (CASE WHEN po.nombreProyecto IS NULL THEN '-' ELSE po.nombreProyecto END) AS nombreProyecto,
                             (CASE WHEN sp.subProyectoDesc IS NULL THEN '-' ELSE sp.subProyectoDesc END) AS subProyectoDesc,
                             SUBSTRING(sf.pep2,4,24) AS pep2,
                             SUBSTRING(sf.grafo1,4,16) AS grafo
                        FROM sap_fija sf 
                   LEFT JOIN detalleplan d    ON TRIM(RIGHT(sf.desc_pep2,13)) = d.poCod
                   LEFT JOIN planobra po      ON po.itemPlan = d.itemPlan
                   LEFT JOIN subproyecto sp   ON sp.idSubProyecto = po.idSubProyecto
                   LEFT JOIN web_unificada wu ON TRIM(RIGHT(sf.desc_pep2,13)) = wu.ptr
                       WHERE sf.pep1 = '" . $pep1 . "'
                         AND sf.nivel = 3
                         AND REPLACE(sf.comprometido,'".$variable."','') > 1";

        $result = $this->db->query($sql);
        return $result->result();
    }

    function getDetallePTR2($pep1){

        $variable = '"';

        $sql = "    SELECT TRIM(RIGHT(tab1.desc_pep2,14)) AS ptr,
                             tab1.est_innova,
                             (CASE WHEN tab2.plan IS NULL THEN ( CASE WHEN (FORMAT(REPLACE (REPLACE(tab1.plan,'".$variable."',''),',','') - REPLACE (REPLACE(tab1.real,'".$variable."',''),',',''),2)) < 0 THEN 0
                                                          ELSE (FORMAT(REPLACE (REPLACE(tab1.plan,'".$variable."',''),',','') - REPLACE (REPLACE(tab1.real,'".$variable."',''),',',''),2)) END) 
                                   WHEN tab2.plan IS NOT NULL THEN ( CASE WHEN (FORMAT(REPLACE (REPLACE(tab1.real,'".$variable."',''),',','') + REPLACE (REPLACE(tab2.real,'".$variable."',''),',',''),2)) < 0 THEN 0
                                                          ELSE (FORMAT(REPLACE (REPLACE(tab1.real,'".$variable."',''),',','') + REPLACE (REPLACE(tab2.plan,'".$variable."',''),',',''),2)) END)
                                   ELSE 0 END) AS real_planresord,
                             tab1.valor_mat,
                             tab1.itemPlan,
                             tab1.nombreProyecto,
                             tab1.subProyectoDesc,
                             SUBSTRING(tab1.pep2,4,24) AS pep2,
                             SUBSTRING(tab1.grafo1,4,18) AS grafo
                        FROM (  SELECT  sf.*,
                                        wu.est_innova,
                                        wu.valoriz_m_o AS monto_mo,
                                        wu.valoriz_material AS valor_mat,
                                        (CASE WHEN d.itemPlan IS NULL THEN '-' ELSE d.itemPLan END) AS itemPlan,
                                        (CASE WHEN po.nombreProyecto IS NULL THEN '-' ELSE po.nombreProyecto END) AS nombreProyecto,
                                        (CASE WHEN sp.subProyectoDesc IS NULL THEN '-' ELSE sp.subProyectoDesc END) AS subProyectoDesc
                                   FROM sap_fija sf 
                              LEFT JOIN detalleplan d    ON TRIM(RIGHT(sf.desc_pep2,13)) = d.poCod
                              LEFT JOIN planobra po      ON po.itemPlan = d.itemPlan
                              LEFT JOIN subproyecto sp   ON sp.idSubProyecto = po.idSubProyecto
                              LEFT JOIN web_unificada wu ON TRIM(RIGHT(sf.desc_pep2,13)) = wu.ptr
                                  WHERE sf.pep1 = '" . $pep1 . "'
                                    AND sf.nivel = 3
                                    AND SUBSTRING(sf.desc_pep2,1,2) = 'MT'
                               GROUP BY sf.desc_pep2 ) AS tab1
                   LEFT JOIN (   SELECT  sf.*,
                                         wu.est_innova,
                                         wu.valoriz_m_o AS monto_mo,
                                         wu.valoriz_material AS valor_mat,
                                         (CASE WHEN d.itemPlan IS NULL THEN '-' ELSE d.itemPLan END) AS itemPlan,
                                         (CASE WHEN po.nombreProyecto IS NULL THEN '-' ELSE po.nombreProyecto END) AS nombreProyecto,
                                         (CASE WHEN sp.subProyectoDesc IS NULL THEN '-' ELSE sp.subProyectoDesc END) AS subProyectoDesc
                                    FROM sap_fija sf 
                               LEFT JOIN detalleplan d    ON TRIM(RIGHT(sf.desc_pep2,13)) = d.poCod
                               LEFT JOIN planobra po      ON po.itemPlan = d.itemPlan
                               LEFT JOIN subproyecto sp   ON sp.idSubProyecto = po.idSubProyecto
                               LEFT JOIN web_unificada wu ON TRIM(RIGHT(sf.desc_pep2,13)) = wu.ptr
                                   WHERE sf.pep1 = '" . $pep1 . "'
                                     AND sf.nivel = 3
                                     AND SUBSTRING(sf.desc_pep2,1,2) = 'DV'
                                GROUP BY sf.desc_pep2) AS tab2 ON TRIM(RIGHT(tab1.desc_pep2,13)) = TRIM(RIGHT(tab2.desc_pep2,13))
                                WHERE (CASE WHEN tab2.plan IS NULL THEN ( CASE WHEN (FORMAT(REPLACE (REPLACE(tab1.plan,'".$variable."',''),',','') - REPLACE (REPLACE(tab1.real,'".$variable."',''),',',''),2)) < 0 THEN 0
                                                          ELSE (FORMAT(REPLACE (REPLACE(tab1.plan,'".$variable."',''),',','') - REPLACE (REPLACE(tab1.real,'".$variable."',''),',',''),2)) END) 
                                   WHEN tab2.plan IS NOT NULL THEN ( CASE WHEN (FORMAT(REPLACE (REPLACE(tab1.real,'".$variable."',''),',','') + REPLACE (REPLACE(tab2.real,'".$variable."',''),',',''),2)) < 0 THEN 0
                                                          ELSE (FORMAT(REPLACE (REPLACE(tab1.real,'".$variable."',''),',','') + REPLACE (REPLACE(tab2.plan,'".$variable."',''),',',''),2)) END)
                                   ELSE 0 END) >= 1";

        $result = $this->db->query($sql);
        return $result->result();
    }
    
    function getEstadoPlanByItemplanNombre($idArea){
        $Query = "SELECT a.areaDesc, e.estacionDesc
                    FROM   area a, estacionarea ea, estacion e
                    WHERE  a.idArea = ea.idArea
                    AND	ea.idEstacion = e.idEstacion
                    AND	a.idArea = ? limit 1;";
        $result = $this->db->query($Query,array($idArea));
        if($result->row() != null) {
            return $result->row_array();
        } else {
            return null;
        }
    }
    
    function getIdSubProyectoDescByIdSubPro($idSubProyecto){
        $Query = "SELECT subProyectoDesc from subproyecto where idSubProyecto = ?;";
        $result = $this->db->query($Query,array($idSubProyecto));
        if($result->row() != null) {
            return $result->row_array()['subProyectoDesc'];
        } else {
            return null;
        }
    }
    
    function   getAreasBySubProyectoPep($idSubPro, $pep1){    
        $query = "SELECT    a.idArea
                    FROM    pep1_subpro sp, area a 
                    WHERE   a.areaDesc          = sp.area
                    AND     sp.idSubProyecto    = ? 
                    AND     pep1                = ?";    
        $result = $this->db->query($query,array($idSubPro, $pep1));
        $listaAreas = array();
        foreach($result->result() as $row){
            array_push($listaAreas, $row->idArea);
        }
        return $listaAreas;
    }
    
    function getEstatusPresupuesto2018(){
        $Query = "SELECT * FROM log_toro_proyecto_estatus where date(fecha_registro) = '".FECHA_LIMITE_REPORTE_PRESUPUESTO_2018."'";
        $result=$this->db->query($Query,array());
        return $result;
    }
    
    function   getCategoriasByLimitDays2018($limite_day){
    
        $query = '	SELECT tb.fecha_registro FROM (
                	select distinct DATE_FORMAT(fecha_registro,"%d/%m/%Y") as fecha_registro from log_toro_proyecto_estatus WHERE DATE(fecha_registro) <= "'.FECHA_LIMITE_REPORTE_PRESUPUESTO_2018.'" order by DATE(fecha_registro) desc limit '.$limite_day.') as tb
                	order by tb.fecha_registro;';
    
        $result = $this->db->query($query,array());
        //log_message('error', $this->db->last_query());
        return $result;
    }
    
    function   getSeriesBySubProyectoAndLimitDays2018($limite_day){
    
        $query = "	select distinct descripcion from log_toro_proyecto_estatus where fecha_registro in (SELECT tb.* FROM (
                	select distinct fecha_registro from log_toro_proyecto_estatus where DATE(fecha_registro) <= '".FECHA_LIMITE_REPORTE_PRESUPUESTO_2018."' order by DATE(fecha_registro) desc limit ".$limite_day.") as tb
                	order by tb.fecha_registro);";
    
        $result = $this->db->query($query,array());
        return $result;
    }
    
    function   getSeriesByProyecto2018($proyecto, $limite_day){
    
        $query = "SELECT tb.fecha_registro, lp.descripcion, lp.percent FROM (
            	select distinct fecha_registro from log_toro_proyecto_estatus where DATE(fecha_registro) <= '".FECHA_LIMITE_REPORTE_PRESUPUESTO_2018."' order by DATE(fecha_registro) desc limit ".$limite_day.") as tb
            	LEFT JOIN log_toro_proyecto_estatus  lp ON
            	lp.fecha_registro = tb.fecha_registro AND lp.descripcion = ?";
         
        $result = $this->db->query($query,array($proyecto));
        return $result;
    }
    
	function getEstatusPresupuesto2019(){
        $Query = "SELECT * FROM log_toro_proyecto_estatus where date(fecha_registro) = '".FECHA_LIMITE_REPORTE_PRESUPUESTO_2019."'
                   group by descripcion";
        $result=$this->db->query($Query,array());
        return $result;
    }
    
    function   getCategoriasByLimitDays2019($limite_day){
    
        $query = '	SELECT tb.fecha_registro FROM (
                	select distinct DATE_FORMAT(fecha_registro,"%d/%m/%Y") as fecha_registro from log_toro_proyecto_estatus WHERE DATE(fecha_registro) <= "'.FECHA_LIMITE_REPORTE_PRESUPUESTO_2019.'" order by DATE(fecha_registro) desc limit '.$limite_day.') as tb
                	order by tb.fecha_registro;';
    
        $result = $this->db->query($query,array());
        //log_message('error', $this->db->last_query());
        return $result;
    }
    
    function   getSeriesBySubProyectoAndLimitDays2019($limite_day){
    
        $query = "	select distinct descripcion from log_toro_proyecto_estatus where fecha_registro in (SELECT tb.* FROM (
                	select distinct fecha_registro from log_toro_proyecto_estatus where DATE(fecha_registro) <= '".FECHA_LIMITE_REPORTE_PRESUPUESTO_2019."' order by DATE(fecha_registro) desc limit ".$limite_day.") as tb
                	order by tb.fecha_registro);";
    
        $result = $this->db->query($query,array());
        return $result;
    }
    
    function   getSeriesByProyecto2019($proyecto, $limite_day){
    
        $query = "SELECT tb.fecha_registro, lp.descripcion, lp.percent FROM (
            	select distinct fecha_registro from log_toro_proyecto_estatus where DATE(fecha_registro) <= '".FECHA_LIMITE_REPORTE_PRESUPUESTO_2019."' order by DATE(fecha_registro) desc limit ".$limite_day.") as tb
            	LEFT JOIN log_toro_proyecto_estatus  lp ON
            	lp.fecha_registro = tb.fecha_registro AND lp.descripcion = ?";
         
        $result = $this->db->query($query,array($proyecto));
        return $result;
    }
}