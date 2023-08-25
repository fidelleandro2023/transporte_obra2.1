<?php
class M_extractor extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	function VerificarPtrEstacion($idEstacion,$itemplan){
		$QUERY="
		select itemplan from itemplanestacionavance where idEstacion=".$idEstacion." and itemplan='".$itemplan."' and porcentaje='100'";
		$result=$this->db->query($QUERY,array());
		if($result->row()!=''){
			return "C";
		}else{
			return "NC";
		}
	}
	function VerificaExpedienteEstacion($idEstacion,$itemplan){
		$QUERY="
		select fecha,estado,estado_final,fecha_valida,usuario_valida from itemplan_expediente where idEstacion=".$idEstacion." and itemplan='".$itemplan."'";
		$result=$this->db->query($QUERY,array());
		if($result->row()!=''){
			return $result->row_array();
		}else{
			return 0;
		}
	}
	function VerificaCheck($ptr,$itemplan){
		$QUERY="
		select itemplan from ptr_expediente where ptr='".$ptr."' and itemplan='".$itemplan."'";
		$result=$this->db->query($QUERY,array());
		if($result->row()!=''){
			return 1;
		}else{
			return 0;
		}
	}
	function Porcentaje($idEstacion,$itemplan){
		$QUERY="select porcentaje from itemplanestacionavance where itemplan='".$itemplan."' and idEstacion=".$idEstacion;
		$result=$this->db->query($QUERY,array());
		if($result->row()!=''){
			return $result->row_array()["porcentaje"];
		}else{
			return 0;
		}
	}
	function ListarExtractor(){
	    /****
		$QUERY="
		select p.itemplan,d.poCod,p.indicador, es.estacionDesc, ar.tipoArea,pr.proyectoDesc,s.subProyectoDesc,p.fechaEjecucion,p.fechaPrevEjec,wu.est_innova,ep.estadoPlanDesc,ec.empresaColabDesc,z.zonalDesc,cen.jefatura,wu.valoriz_m_o,wu.valoriz_material,wu.f_ult_est,wu.usu_registro,p.idEstadoPlan,ea.idEstacion,(case 
		WHEN wu.idEmpreColab=0 then  'DOMINION'
		ELSE (select empresaColabDesc from empresacolab where idEmpresaColab=wu.idEmpreColab) END) AS ptrcolab
		from planobra p 
		inner join empresacolab ec on ec.idEmpresaColab=p.idEmpresaColab
		inner join zonal z on z.idZonal=p.idZonal
		inner join central cen on cen.idCentral=p.idCentral
		inner join detalleplan d on d.itemplan=p.itemplan
		left join estadoplan ep on ep.idEstadoPlan=p.idEstadoPlan
		left join web_unificada wu on d.poCod=wu.ptr 
		inner join subproyectoestacion sp on sp.idSubProyectoEstacion=d.idSubProyectoEstacion 
		inner join estacionarea ea on sp.idEstacionArea=ea.idEstacionArea 
		inner join estacion es on es.idEstacion=ea.idEstacion
		inner join area ar on ar.idArea=ea.idArea 
		left join subproyecto s on p.idSubProyecto=s.idSubProyecto
		left join proyecto pr on s.idProyecto=pr.idProyecto
		
		where p.idEstadoPlan=4

		UNION

		select DISTINCT(i.itemplan),d.poCod,p.indicador, es.estacionDesc, ar.tipoArea,pr.proyectoDesc,s.subProyectoDesc,p.fechaEjecucion,p.fechaPrevEjec,wu.est_innova,ep.estadoPlanDesc,ec.empresaColabDesc,z.zonalDesc,cen.jefatura,wu.valoriz_m_o,wu.valoriz_material,wu.f_ult_est,wu.usu_registro,p.idEstadoPlan,ea.idEstacion,(case 
		WHEN wu.idEmpreColab=0 then  'DOMINION'
		ELSE (select empresaColabDesc from empresacolab where idEmpresaColab=wu.idEmpreColab) END) AS ptrcolab
		from itemplanestacionavance i 
		inner join planobra p on p.itemplan=i.itemplan
		inner join empresacolab ec on ec.idEmpresaColab=p.idEmpresaColab
		inner join zonal z on z.idZonal=p.idZonal
		inner join central cen on cen.idCentral=p.idCentral
		left join estadoplan ep on ep.idEstadoPlan=p.idEstadoPlan
		inner join detalleplan d on d.itemplan=i.itemplan
		left join web_unificada wu on d.poCod=wu.ptr 
		inner join subproyectoestacion sp on sp.idSubProyectoEstacion=d.idSubProyectoEstacion 
		inner join estacionarea ea on sp.idEstacionArea=ea.idEstacionArea 
		inner join estacion es on es.idEstacion=ea.idEstacion 
		inner join area ar on ar.idArea=ea.idArea
		left join subproyecto s on p.idSubProyecto=s.idSubProyecto
		left join proyecto pr on s.idProyecto=pr.idProyecto
		
		where i.porcentaje='100' ";******/
		
		$QUERY="SELECT DISTINCT p.itemplan,
       d.poCod,
       p.indicador, 
       es.estacionDesc, 
       ar.tipoArea,
       pr.proyectoDesc,
       s.subProyectoDesc,
       date(lgpo.fecha_registro) as fecha_registro,
       p.fechaEjecucion,
       p.fechaPrevEjec,
       wu.est_innova,
       ep.estadoPlanDesc,
       ec.empresaColabDesc,
       z.zonalDesc,
       cen.jefatura,
       wu.valoriz_m_o,
       wu.valoriz_material,
       wu.f_ult_est,
       wu.usu_registro,
       p.idEstadoPlan,
       ea.idEstacion,
       (case WHEN wu.idEmpreColab=0 then  'DOMINION'
		    ELSE (select empresaColabDesc from empresacolab where idEmpresaColab=wu.idEmpreColab) END) AS ptrcolab
		
    from log_planobra lgpo, planobra p, detalleplan d, empresacolab ec, zonal z, central cen,estadoplan ep, subproyectoestacion sp, estacionarea ea, estacion es, area ar, subproyecto s,  proyecto pr, web_unificada wu
    where d.poCod=wu.ptr
    and d.itemplan=p.itemplan
    and sp.idSubProyectoEstacion=d.idSubProyectoEstacion
    and sp.idEstacionArea=ea.idEstacionArea
    and es.idEstacion=ea.idEstacion 
    and ar.idArea=ea.idArea
    and ec.idEmpresaColab=p.idEmpresaColab
    and z.idZonal=p.idZonal
    and cen.idCentral=p.idCentral
    and ep.idEstadoPlan=p.idEstadoPlan
	and p.idSubProyecto=s.idSubProyecto
    and s.idProyecto=pr.idProyecto
    and lgpo.itemplan=p.itemPlan
	and p.idEstadoPlan=4
    and lgpo.tabla='planobra'
    and lgpo.actividad='ingresar'";
	
		
		$result=$this->db->query($QUERY,array());
		return $result;
	}
	
	  public function getDetallePropuestaObraMaterial()
    {
        $Query = "SELECT 
                        ppo.itemplan,
                        ppo.codigo_po,
                        ppd.codigo_material,
                        m.descrip_material,
                        ppd.cantidad_ingreso
                    FROM
                        planobra_po ppo,
                        planobra_po_detalle ppd,
                        material m
                    WHERE
                        ppo.codigo_po = ppd.codigo_po
                            AND ppd.codigo_material = m.id_material
                    ORDER BY ppo.itemplan;";
        //$result = $this->db->query($Query,array());
        log_message('error', 'Fin proceso getDetallePlan'.$this->db->last_query());//
        $result = $this->db->query($Query);
        return $result;
    }

    
}