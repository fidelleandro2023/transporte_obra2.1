<?php
class M_reporte_siom_tecnico extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
    
	function getAllNodosSiom(){
	    $Query = 'SELECT * FROM siom_nodo';
	    $result = $this->db->query($Query,array());
	    return $result;
	}
	
	public function getInfoNodoSiomByCodigo($codigo_empl)
	{
	    $sql = "SELECT * FROM siom_nodo where empl_nemonico = ? LIMIT 1";
	    $result = $this->db->query($sql,array($codigo_empl));
	    if($result->row() != null) {
	        return $result->row_array();
	    } else {
	        return null;
	    }
	}
	
	/***nuevo para reporte siom***/
	function getTecnicosByProyecto($idProyecto, $idJefatura, $idEECC, $tipoTecnico, $estado, $fechaInicio, $fechaFin){
	    $Query = "  select  
                    tecnico_asignado, count(1) as num, ((count(1)*100)/(
                    select count(1) 
                      from planobra po, subproyecto sp, central c, siom_obra so LEFT JOIN siom_tecnico st ON st.nombre = so.tecnico_asignado
                    WHERE so.itemplan = po.itemplan 
                    and po.idSubProyecto = sp.idSubProyecto
                    and po.idCentral = c.idCentral
                    and sp.idProyecto = ?
                    and c.idJefatura = ?
                    and c.idEmpresaColab = ?
	                and so.ultimo_estado not in ('ANULADA','NOACTIVO') 
                    and so.tecnico_asignado is not null";
	    if($estado  != ''){
	        if($estado  ==  'PENDIENTE'){
	            $Query .= "  and so.ultimo_estado not in ('VALIDANDO','APROBADA')";
	            if($fechaInicio!= '' && $fechaFin !=''){
	                $Query .= "  and DATE(so.fechaRegistro) >= '".$fechaInicio."' and DATE(so.fechaRegistro) <= '".$fechaFin."'";
	            }
	        }else if($estado  ==  'EJECUTADO'){
	            $Query .= "  and so.ultimo_estado in ('VALIDANDO','APROBADA')";
	            if($fechaInicio!= '' && $fechaFin !=''){
	                $Query .= "  and DATE(so.fecha_ultimo_estado) >= '".$fechaInicio."' and DATE(so.fecha_ultimo_estado) <= '".$fechaFin."'";
	            }
	        }
	    }
	   if($tipoTecnico != ''){
             $Query .= "  and st.tipo = '".$tipoTecnico."'";
       }
       $Query .= "  ))as per, st.tipo
                      from planobra po, subproyecto sp, central c, siom_obra so LEFT JOIN siom_tecnico st ON st.nombre = so.tecnico_asignado
                    WHERE so.itemplan = po.itemplan 
                    and po.idSubProyecto = sp.idSubProyecto
                    and po.idCentral = c.idCentral
                    and sp.idProyecto = ?
                    and c.idJefatura = ?
                    and c.idEmpresaColab = ?
                    and so.ultimo_estado not in ('ANULADA','NOACTIVO') 
                    and so.tecnico_asignado is not null";
        if($tipoTecnico != ''){
            $Query .= "  and st.tipo = '".$tipoTecnico."'";
        }
        
        if($estado  != ''){
            if($estado  ==  'PENDIENTE'){
                $Query .= "  and so.ultimo_estado not in ('VALIDANDO','APROBADA')";
                if($fechaInicio!= '' && $fechaFin !=''){
                    $Query .= "  and DATE(so.fechaRegistro) >= '".$fechaInicio."' and DATE(so.fechaRegistro) <= '".$fechaFin."'";
                }
            }else if($estado  ==  'EJECUTADO'){
                $Query .= "  and so.ultimo_estado in ('VALIDANDO','APROBADA')";
                if($fechaInicio!= '' && $fechaFin !=''){
                    $Query .= "  and DATE(so.fecha_ultimo_estado) >= '".$fechaInicio."' and DATE(so.fecha_ultimo_estado) <= '".$fechaFin."'";
                }
            }            
        }
        
        $Query .= " group by tecnico_asignado";
	    $result = $this->db->query($Query,array($idProyecto, $idJefatura, $idEECC, $idProyecto, $idJefatura, $idEECC));
	    log_message('error', $this->db->last_query());
	    return $result->result();
	}
	
	function getObrasByIdProyectoAndTecnico($idProyecto, $idJefatura, $idEECC, $tecnico, $estado, $fechaInicio, $fechaFin){
	    $Query = " SELECT DISTINCT so.itemplan,
                	(CASE WHEN coordX is null OR coordX = '' THEN  sn.empl_coord_x ELSE coordX END) coordenada_x,
                	(CASE WHEN coordX is null OR coordX = '' THEN  sn.empl_coord_y ELSE coordY END) coordenada_y
                	from siom_obra so, planobra po, subproyecto sp, central c LEFT JOIN siom_nodo sn ON c.codigo = sn.empl_nemonico
                	WHERE so.itemplan = po.itemplan
                	and po.idSubProyecto = sp.idSubProyecto
                	and po.idCentral = c.idCentral
                	and sp.idProyecto = ?
	                and c.idJefatura = ?                	
	                and c.idEmpresaColab = ?
	                and so.tecnico_asignado = ?
	                and so.ultimo_estado not in ('ANULADA','NOACTIVO')";
	        if($estado  != ''){
    	        if($estado  ==  'PENDIENTE'){
    	            $Query .= "  and so.ultimo_estado not in ('VALIDANDO','APROBADA')";
    	            if($fechaInicio!= '' && $fechaFin !=''){
    	                $Query .= "  and DATE(so.fechaRegistro) >= '".$fechaInicio."' and DATE(so.fechaRegistro) <= '".$fechaFin."'";
    	            }
    	        }else if($estado  ==  'EJECUTADO'){
    	            $Query .= "  and so.ultimo_estado in ('VALIDANDO','APROBADA')";
    	            if($fechaInicio!= '' && $fechaFin !=''){
    	                $Query .= "  and DATE(so.fecha_ultimo_estado) >= '".$fechaInicio."' and DATE(so.fecha_ultimo_estado) <= '".$fechaFin."'";
    	            }
    	        }
	       } 
	        
              $Query .= " having coordenada_x is not null";
	    
	    $result = $this->db->query($Query,array($idProyecto, $idJefatura, $idEECC, $tecnico));
	    log_message('error', $this->db->last_query());
	    return $result->result();
	}
	
	function getObrasByIdProyectoAndTecnicoToTable($idProyecto, $idJefatura, $idEECC, $tecnico, $estado, $fechaInicio, $fechaFin){
	    $Query = " select so.*, e.estacionDesc,
            	(CASE WHEN coordX is null OR coordX = '' THEN  sn.empl_coord_x ELSE coordX END) coordenada_x,
            	(CASE WHEN coordX is null OR coordX = '' THEN  sn.empl_coord_y ELSE coordY END) coordenada_y,
	           (CASE WHEN ultimo_estado IN ('VALIDANDO','APROBADA') THEN TIMESTAMPDIFF(DAY, so.fechaRegistro, so.fecha_ultimo_estado) 
	                   ELSE TIMESTAMPDIFF(DAY, so.fechaRegistro, NOW()) END) as dif_dias
            	from siom_obra so, planobra po, subproyecto sp, estacion e, central c LEFT JOIN siom_nodo sn ON c.codigo = sn.empl_nemonico
            	WHERE so.itemplan = po.itemplan
            	and po.idSubProyecto = sp.idSubProyecto
            	and po.idCentral = c.idCentral
	            and so.idEstacion = e.idEstacion
	            and sp.idProyecto = ?
                and c.idJefatura = ?                	
                and c.idEmpresaColab = ?
            	and so.tecnico_asignado = ?
	            and so.ultimo_estado not in ('ANULADA','NOACTIVO')";
	    if($estado  != ''){
	        if($estado  ==  'PENDIENTE'){
	            $Query .= "  and so.ultimo_estado not in ('VALIDANDO','APROBADA')";
	            if($fechaInicio!= '' && $fechaFin !=''){
	                $Query .= "  and DATE(so.fechaRegistro) >= '".$fechaInicio."' and DATE(so.fechaRegistro) <= '".$fechaFin."'";
	            }
	        }else if($estado  ==  'EJECUTADO'){
	            $Query .= "  and so.ultimo_estado in ('VALIDANDO','APROBADA')";
	            if($fechaInicio!= '' && $fechaFin !=''){
	                $Query .= "  and DATE(so.fecha_ultimo_estado) >= '".$fechaInicio."' and DATE(so.fecha_ultimo_estado) <= '".$fechaFin."'";
	            }
	        }
	    }
	    $result = $this->db->query($Query,array($idProyecto, $idJefatura, $idEECC, $tecnico));
	    log_message('error', $this->db->last_query());
	    return $result->result();
	}
	
	
	
	
	
}