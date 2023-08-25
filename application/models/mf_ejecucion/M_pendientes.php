<?php
class M_pendientes extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
    function getListarPendientes($itemplan,$proyecto,$subproyecto,$indicador){
    $sent = null;    
    if($this->session->userdata('idPerfilSession')==5){
        $sent.=" and p.idEmpresaColab=".$this->session->userdata('eeccSession');	
    }
    //if($this->session->userdata("zonasSession")){
      //  $sent.=" and p.idZonal in (".$this->session->userdata("zonasSession"//).")"; 
    //}
    if($itemplan){
        $sent="AND itemplan='".trim($itemplan)."'";
    }else{
        if($indicador){
            $sent="AND indicador='".trim($indicador)."'";
        }else{
            if($proyecto&&!$subproyecto){
            $sent.="AND p.idSubProyecto in (select idSubProyecto from subproyecto where idProyecto=".$proyecto.")";
            }
            if($subproyecto){
            $sent.="AND p.idSubProyecto =".$subproyecto; 
            }
        }
    }

    	$Query = " SELECT z.zonalDesc,
                          c.tipoCentralDesc,
                          pro.ProyectoDesc,
                          s.subProyectoDesc,
                          e.empresaColabDesc,
                          p.itemPlan,
                          p.nombreProyecto,
                          p.indicador,
                          p.fechaPrevEjec,
                          DATE(p.fechaPreLiquidacion)AS fechaPreLiquidacion,
                          p.fechaInicio,
                          p.idEmpresaColab,
                          (CASE WHEN s.idTipoSubProyecto = 2 THEN (SELECT empresaColabDesc FROM empresacolab WHERE idEmpresaColab = c.idEmpresaColabCV) ELSE e.empresaColabDesc END) as empresaColabDesc,
                          (SELECT estadoPlanDesc 
                             FROM estadoplan e
                            WHERE e.idEstadoPlan = p.idEstadoPlan) estadoPlanDesc
                    
                    FROM planobra p 

                    left join empresacolab e on p.idEmpresaColab=e.idEmpresaColab
                    left join subproyecto s on s.idSubProyecto=p.idSubProyecto
                    left join proyecto pro on pro.idProyecto=s.idProyecto
                    left join central c on p.idCentral=c.idCentral
                    left join zonal z on z.idZonal=p.idZonal
                    left join planobra_config_flujo pf on (pf.idSubProyecto = p.idSubProyecto  
                                                           AND pf.idZonal = (SELECT idZonal 
																			   FROM central c
																			  WHERE c.idCentral = p.idCentral))
                    where CASE WHEN (pf.id_planobra_config_flujo IS NULL AND idEstadoplan = ".ID_ESTADO_PRE_LIQUIDADO.")
                                    THEN idEstadoplan = ".ID_ESTADO_PRE_LIQUIDADO." AND pf.id_planobra_config_flujo IS NULL
                                    ELSE idEstadoplan = ".ID_ESTADO_PLAN_EN_OBRA." END ".$sent;
        $result = $this->db->query($Query,array());	  
	    return $result;
    }
    
    function getListaPendiente($itemPlan, $idProyecto, $idSubProyecto, $indicador, $idEstado, $Estado2, $idTipoPlanta=null, $arrayEstadoPlan, $idFase = null) {
        $inZonal = $this->session->userdata("zonasSession");
        $ideecc  = $this->session->userdata("eeccSession");
        $inZonal = ($inZonal == ',') ? NULL :  $inZonal;
        $inZonal = ($inZonal == '')  ? NULL :  $inZonal;
        
   

        $sql ="SELECT p.idSubProyecto,s.idProyecto,f.faseDesc,
                        z.zonalDesc,
                        (CASE WHEN s.idProyecto = ".ID_PROYECTO_SISEGOS." THEN (SELECT grafo 
                                                                                  FROM sisego_pep2_grafo 
                                                                                 WHERE sisego = p.indicador LIMIT 1) 
                              ELSE null END) as grafo,
                        c.tipoCentralDesc,
                        s.idTipoPlanta, 
                        pro.ProyectoDesc,
                        s.subProyectoDesc,
                        (SELECT empresaColabDesc FROM empresacolab WHERE idEmpresaColab = c.idEmpresaColabFuente) AS empresaColabDescFuente,
                        p.itemPlan,
                        p.nombreProyecto,
                        p.indicador,
                        p.fechaPrevEjec,
                        p.fechaEjecucion,
                        DATE(p.fechaPreLiquidacion)AS fechaPreLiquidacion,
                        p.fechaInicio,
                        p.idEmpresaColab,
                        p.idEstadoPlan,
                        UPPER(p.operador) AS operador,
                        (CASE WHEN  s.idTipoSubProyecto = 2 THEN (SELECT empresaColabDesc 
                                                                    FROM empresacolab 
                                                                   WHERE idEmpresaColab = c.idEmpresaColabCV) 
                                ELSE e.empresaColabDesc END) as empresaColabDesc,
                        (SELECT estadoPlanDesc 
                        FROM estadoplan e
                        WHERE e.idEstadoPlan = p.idEstadoPlan)estadoPlanDesc,
                         (CASE WHEN c.jefatura = 'LIMA' 
                              THEN (SELECT COUNT(*) AS cantidad 
                                      FROM itemplan_estacion_licencia_det 
									 WHERE itemplan = p.itemplan
                                       AND ruta_pdf IS NOT NULL)
							  ELSE (SELECT COUNT(*) AS cantidad 
						              FROM acotacion a,
								           itemplan_estacion_licencia_det ie
									 WHERE ie.itemplan = p.itemplan
                                       AND ie.iditemplan_estacion_licencia_det = a.iditemplan_estacion_licencia_det
                                       AND a.ruta_foto IS NOT NULL) END) AS flgLicencia,
                         c.jefatura,
                         p.has_evidencia
                FROM planobra p 
                left join subproyecto s  on s.idSubProyecto  = p.idSubProyecto
                left join proyecto pro   on pro.idProyecto   = s.idProyecto
                left join central c      on p.idCentral      = c.idCentral
                left join empresacolab e on e.idEmpresaColab = p.idEmpresaColab
                left join zonal z        on z.idZonal=c.idZonal
                left join fase f         on f.idFase = p.idFase
                WHERE p.itemplan   = COALESCE(?,p.itemplan)
                    AND p.indicador  = COALESCE(?,p.indicador)
                    AND s.idProyecto    =  COALESCE(?, s.idProyecto)  
                    AND p.idEstadoPlan IN (".implode(',',$arrayEstadoPlan).")
                    #AND p.idEstadoPlan IN (".implode(',',$arrayEstadoPlan).")
                    AND p.idSubProyecto = COALESCE(?, p.idSubProyecto) 
                    AND CASE WHEN ".$ideecc." = 0 OR ".$ideecc." = 6 THEN c.idEmpresaColab = c.idEmpresaColab
                             WHEN ".$ideecc." IN (SELECT idSubProyecto 
                                                    FROM subproyecto 
                                                   WHERE idProyecto = 5)
                             THEN c.idEmpresaColabCV =  ".$ideecc."               
                             ELSE p.idEmpresaColab = ".$ideecc." END
                    AND p.idFase = COALESCE(?, p.idFase) 
					AND p.has_log_pi is null
					AND p.paquetizado_fg IS NULL
                    -- ORDER BY p.fechaEjecucion desc
				UNION ALL
				 SELECT p.idSubProyecto,s.idProyecto,f.faseDesc,
                        z.zonalDesc,
                        (CASE WHEN s.idProyecto = ".ID_PROYECTO_SISEGOS." THEN (SELECT grafo 
                                                                                  FROM sisego_pep2_grafo 
                                                                                 WHERE sisego = p.indicador LIMIT 1) 
                              ELSE null END) as grafo,
                        c.tipoCentralDesc,
                        s.idTipoPlanta, 
                        pro.ProyectoDesc,
                        s.subProyectoDesc,
                        (SELECT empresaColabDesc FROM empresacolab WHERE idEmpresaColab = c.idEmpresaColabFuente) AS empresaColabDescFuente,
                        p.itemPlan,
                        p.nombreProyecto,
                        p.indicador,
                        p.fechaPrevEjec,
                        p.fechaEjecucion,
                        DATE(p.fechaPreLiquidacion)AS fechaPreLiquidacion,
                        p.fechaInicio,
                        p.idEmpresaColab,
                        p.idEstadoPlan,
                        UPPER(p.operador) AS operador,
                        e.empresaColabDesc,
                        (SELECT estadoPlanDesc 
                        FROM estadoplan e
                        WHERE e.idEstadoPlan = p.idEstadoPlan)estadoPlanDesc,
                         (CASE WHEN c.jefatura = 'LIMA' 
                              THEN (SELECT COUNT(*) AS cantidad 
                                      FROM itemplan_estacion_licencia_det 
									 WHERE itemplan = p.itemplan
                                       AND ruta_pdf IS NOT NULL)
							  ELSE (SELECT COUNT(*) AS cantidad 
						              FROM acotacion a,
								           itemplan_estacion_licencia_det ie
									 WHERE ie.itemplan = p.itemplan
                                       AND ie.iditemplan_estacion_licencia_det = a.iditemplan_estacion_licencia_det
                                       AND a.ruta_foto IS NOT NULL) END) AS flgLicencia,
                         c.jefatura,
                         p.has_evidencia
                FROM planobra p 
                left join subproyecto s  on s.idSubProyecto  = p.idSubProyecto
                left join proyecto pro   on pro.idProyecto   = s.idProyecto
                left join pqt_central c  on p.idCentralPqt   = c.idCentral
                left join empresacolab e on e.idEmpresaColab = p.idEmpresaColab
                left join zonal z        on z.idZonal=c.idZonal
                left join fase f         on f.idFase = p.idFase
                WHERE p.itemplan   = COALESCE(?,p.itemplan)
                    AND p.indicador  = COALESCE(?,p.indicador)
                    AND s.idProyecto    =  COALESCE(?, s.idProyecto)  
                    AND p.idEstadoPlan IN (".implode(',',$arrayEstadoPlan).")
                    #AND p.idEstadoPlan IN (".implode(',',$arrayEstadoPlan).")
                    AND p.idSubProyecto = COALESCE(?, p.idSubProyecto) 
                    AND CASE WHEN ".$ideecc." = 0 OR ".$ideecc." = 6 THEN TRUE              
                             ELSE p.idEmpresaColab = ".$ideecc." END
                    AND p.idFase = COALESCE(?, p.idFase) 
					AND p.has_log_pi is null
					AND (p.paquetizado_fg = 2 OR p.paquetizado_fg = 1)
                    ORDER BY fechaEjecucion desc";
        $result = $this->db->query($sql, array($itemPlan, $indicador, $idProyecto, $idSubProyecto, $idFase,
		                                       $itemPlan, $indicador, $idProyecto, $idSubProyecto, $idFase));
        return $result;
        // --   AND CASE WHEN ? IS NOT NULL THEN z.idZonal IN(?)
                                    //  ELSE z.idZonal=z.idZonal END      
    }
    
    function getListarPreLiquidadas(){
    $sent="";
    if($this->session->userdata('idPerfilSession')==5){
    $sent.=" and p.idEmpresaColab=".$this->session->userdata('eeccSession'); 
    }
   // if($this->session->userdata("zonasSession")){
     //   $sent.=" and p.idZonal in (".$this->session->userdata("zonasSession").")"; 
    //}
        $Query = " SELECT z.zonalDesc,c.tipoCentralDesc,pro.ProyectoDesc,s.subProyectoDesc,e.empresaColabDesc,p.itemPlan,p.nombreProyecto,p.indicador,p.fechaPrevEjec,p.fechaInicio,p.idEmpresaColab,e.empresaColabDesc 
        FROM planobra p 
        left join empresacolab e on p.idEmpresaColab=e.idEmpresaColab
        left join subproyecto s on s.idSubProyecto=p.idSubProyecto
        left join proyecto pro on pro.idProyecto=s.idProyecto
        left join central c on p.idCentral=c.idCentral
        left join zonal z on z.idZonal=p.idZonal
        where p.idEstadoPlan=9 ".$sent;
        $result = $this->db->query($Query,array());    
        return $result;
    }
	function getListarLiquidadas(){
    $sent="";
    if($this->session->userdata('idPerfilSession')==5){
    $sent.=" and p.idEmpresaColab=".$this->session->userdata('eeccSession'); 
    }
    if($this->session->userdata("zonasSession")){
        $sent.=" and p.idZonal in (".$this->session->userdata("zonasSession").")"; 
    }
        $Query = " SELECT   p.fechaEjecucion,z.zonalDesc,c.tipoCentralDesc,pro.ProyectoDesc,s.subProyectoDesc,e.empresaColabDesc,p.itemPlan,p.nombreProyecto,p.indicador,p.fechaPrevEjec,p.fechaInicio,p.idEmpresaColab,e.empresaColabDesc 
        FROM planobra p 
        left join empresacolab e on p.idEmpresaColab=e.idEmpresaColab
        left join subproyecto s on s.idSubProyecto=p.idSubProyecto
        left join proyecto pro on pro.idProyecto=s.idProyecto
        left join central c on p.idCentral=c.idCentral
        left join zonal z on z.idZonal=p.idZonal
        where p.idEstadoPlan=4 ".$sent."";
        $result = $this->db->query($Query,array());    
        return $result;
    }

    function cambiarEstado($itemPlan, $idSubProyecto) {
        $this->db->where('itemPlan'     , $itemPlan);
        $this->db->where('idSubProyecto', $idSubProyecto);
        $this->db->update('planobra', array('idEstadoPlan' => ID_ESTADO_PRE_LIQUIDADO,
                                            'fechaPreLiquidacion' => $this->fechaActual(),
                                            'id_usuario_preliquidacion' => $this->session->userdata('idPersonaSession')));
    }
    
   function getListarTruncar(){
        $sent="";
        if($this->session->userdata('idPerfilSession')==5){
        $sent.=" and p.idEmpresaColab=".$this->session->userdata('eeccSession'); 
        }
        //if($this->session->userdata("zonasSession")){
          //  $sent.=" and p.idZonal in (".$this->session->userdata//("zonasSession").")"; 
        //}
        $Query = " SELECT z.zonalDesc,c.tipoCentralDesc,pro.ProyectoDesc,s.subProyectoDesc,e.empresaColabDesc,p.itemPlan,p.nombreProyecto,p.indicador,p.fechaPrevEjec,p.fechaInicio,p.idEmpresaColab,e.empresaColabDesc,p.fechaTrunca,
                          p.motivoTrunco  
                    FROM planobra p 
                    left join subproyecto s on s.idSubProyecto=p.idSubProyecto
                    left join proyecto pro on pro.idProyecto=s.idProyecto
                    left join central c on p.idCentral=c.idCentral
                    left join empresacolab e on c.idEmpresaColab=e.idEmpresaColab
                    left join zonal z on z.idZonal=p.idZonal
                    where p.idEstadoPlan=10 ".$sent." 
                    ORDER BY p.fechaTrunca ASC";
        $result = $this->db->query($Query,array());    
        return $result;
    }

    function buscarubicZip($itemPlan) {
        $sql = "SELECT nombre 
                  FROM planobra_terminar 
                 WHERE id_planobra = '".$itemPlan."'";
        $result = $this->db->query();
        return $result->result();         
    }
    
    function insertParalizacion($data) {
        $this->db->insert('paralizacion', $data);
        if($this->db->affected_rows() != 1) {
			throw new Exception('Error al insertar la paralizaci&oacute;n.');
		}else{
			return array("error" => EXIT_SUCCESS, "msj" => 'OPERACION REALIZADA CON EXITO');
		}
    }
	
	function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone','America/Lima');
        setlocale(LC_TIME, "es_ES","esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }
}