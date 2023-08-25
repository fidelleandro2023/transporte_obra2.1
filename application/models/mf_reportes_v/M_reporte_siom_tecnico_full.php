<?php
class M_reporte_siom_tecnico_full extends CI_Model{
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
	
	function getObrasByIdProyectoAndTecnico($idProyecto, $idJefatura, $idEECC, $estado, $fechaInicio, $fechaFin, $tipoTecnico){
	    $Query = "  SELECT  DISTINCT
                       so.itemplan,                       
                	(CASE WHEN po.coordX is null OR coordX = '' THEN  sn.empl_coord_x ELSE po.coordX END) coordenada_x,
                	(CASE WHEN po.coordX is null OR coordX = '' THEN  sn.empl_coord_y ELSE po.coordY END) coordenada_y,
                   (CASE WHEN so.tecnico_asignado IS NULL THEN 'SIN TECNICO' ELSE so.tecnico_asignado END) as tecnico_asignado
                	from siom_obra so
	                LEFT JOIN planobra po ON so.itemplan = po.itemplan
                    LEFT JOIN subproyecto sp ON po.idSubProyecto = sp.idSubProyecto 
                    LEFT JOIN central c  ON po.idCentral = c.idCentral
                    LEFT JOIN siom_nodo sn ON c.codigo = sn.empl_nemonico 
                    LEFT JOIN siom_tecnico st ON st.nombre = so.tecnico_asignado
                	WHERE sp.idProyecto = ?
	                and c.idJefatura = ?                	
	                and c.idEmpresaColab = ?
	                and so.codigoSiom is not null 
	                and so.ultimo_estado not in ('ANULADA','NOACTIVO')";
    	    if($tipoTecnico != ''){
    	        $Query .= "  and st.tipo = '".$tipoTecnico."' ";
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
	        
              $Query .= " having coordenada_x is not null";
	    
	    $result = $this->db->query($Query,array($idProyecto, $idJefatura, $idEECC));
	    log_message('error', $this->db->last_query());
	    return $result->result();
	}
	
	function getObrasByIdProyectoAndTecnicoToTable($idProyecto, $idJefatura, $idEECC, $estado, $fechaInicio, $fechaFin, $tipoTecnico){
	    $Query = " SELECT  
                        (CASE WHEN so.tecnico_asignado IS NULL THEN 'SIN TECNICO' ELSE so.tecnico_asignado END) as tecnico_asignado,
                        SUM(CASE WHEN so.ultimo_estado IN ('CREADA','ASIGNANDO') THEN 1 ELSE 0 END) as creada,
                        SUM(CASE WHEN so.ultimo_estado = 'ASIGNADA' THEN 1 ELSE 0 END) as asignada,
                        SUM(CASE WHEN so.ultimo_estado = 'EJECUTANDO' THEN 1 ELSE 0 END) as ejecutando,
                        SUM(CASE WHEN so.ultimo_estado = 'VALIDANDO' THEN 1 ELSE 0 END) as validando,
                        SUM(CASE WHEN so.ultimo_estado = 'APROBADA' THEN 1 ELSE 0 END) as aprobada,
                         count(1) as total
                	from siom_obra so
	                LEFT JOIN planobra po ON so.itemplan = po.itemplan
                    LEFT JOIN subproyecto sp ON po.idSubProyecto = sp.idSubProyecto 
                    LEFT JOIN central c  ON po.idCentral = c.idCentral
                    LEFT JOIN siom_nodo sn ON c.codigo = sn.empl_nemonico 
                    LEFT JOIN siom_tecnico st ON st.nombre = so.tecnico_asignado
                	WHERE sp.idProyecto = ?
	                and c.idJefatura = ?             	
	                and c.idEmpresaColab = ?
	                and so.ultimo_estado not in ('ANULADA','NOACTIVO','RECHAZADA') 
                    and so.codigoSiom is not null ";
	    if($tipoTecnico != ''){
	        $Query .= "  and st.tipo = '".$tipoTecnico."' ";
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
	    $Query .= " GROUP BY so.tecnico_asignado";
	    $result = $this->db->query($Query,array($idProyecto, $idJefatura, $idEECC));
	    log_message('error', $this->db->last_query());
	    return $result->result();
	}
	
}