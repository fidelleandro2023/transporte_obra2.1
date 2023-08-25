<?php
class M_log_ingfix extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
   
	/******************************************PLAN DE OBRA************************************************************/
	
  function getPreRegistroLog($itemplan){
         /*
         $Query = " SELECT 'NUEVO',
                           (select estadoPlanDesc from estadoplan where idEstadoPlan=8) as estado,
                            sisego,fecha_registro from log_tramas_sigoplus where itemPlan='".$itemplan."';" ;*/
          $Query = "SELECT distinct  'REGISTRO' as registro, (select estadoPlanDesc from estadoplan where idEstadoPlan=8) as estado,
                           pocv.usua_crea, pa.fecha_creacion from planobra pa inner join planobra_detalle_cv pocv
                           on pocv.itemplan=pa.itemPlan
                           where pa.itemPlan='".$itemplan."';";       
          $result = $this->db->query($Query);
          return $result;
  }

  function getPreDisenoLog($itemplan){
       $Query = " SELECT distinct  'REGISTRO' as registro,(select estadoPlanDesc from estadoplan where idEstadoPlan=1) as estado,usu.nombre, MIN(po.fecha_registro) as fecha_registro
                    from log_planobra po, usuario usu 
                   where usu.id_usuario=po.id_usuario 
                     and itemPlan='".$itemplan."' and actividad='ingresar' and tabla='planobra' AND po.itemplan_default IS NULL" ;
       
      $result = $this->db->query($Query);
      return $result;
  }

  function getPreDisenoUpdateLog($itemplan){
       $Query = " SELECT distinct   replace(po.itemplan_default,'idEstado:1','') as accion,
                          (select estadoPlanDesc from estadoplan where idEstadoPlan=1) as estado,
                          usu.nombre,po.fecha_registro  
                    from  log_planobra po, usuario usu 
                   where  usu.id_usuario=po.id_usuario  
                   and    (po.itemplan_default like '%idEstado:1|%'
                   or    po.itemplan_default like '%idEstadoPlan=1|%')
                   and not po.itemplan_default like '%idEstado:10%' and po.itemPlan='".$itemplan."'";
      $result = $this->db->query($Query);
      return $result;
  }


function getDisenoUpdateLog($itemplan){

 $Query = "  SELECT distinct tab.estado,tab.accion,tab.nombre,tab.fecha_registro from

                    (SELECT po.itemplan, (select estadoPlanDesc from estadoplan where idEstadoPlan=2) as estado,replace(po.itemplan_default,'idEstadoPlan=2','') as accion,usu.nombre,po.fecha_registro  
                    from  log_planobra po, usuario usu 
                   where  usu.id_usuario=po.id_usuario  
                   and    po.itemplan_default like '%idEstadoPlan=2%'
                    union

                    SELECT po.itemplan, (select estadoPlanDesc from estadoplan where idEstadoPlan=2) as estado,replace(po.itemplan_default,'idEstado:2','') as accion,usu.nombre,po.fecha_registro       from  log_planobra po, usuario usu 
                                       where  usu.id_usuario=po.id_usuario  
                                       and    po.itemplan_default like '%idEstado:2%') as tab
              where tab.itemPlan='".$itemplan."'";

      $result = $this->db->query($Query);
      return $result;

}




   function getDisenoLog($itemplan){
       $Query = "SELECT DISTINCT  es.estacionDesc,
                          (SELECT estadoPlanDesc from estadoplan where idEstadoPlan = 2) AS estado,
                          (CASE WHEN pre.usuario_adjudicacion = 'AUTOMATICO' THEN 'AUTOMATICO'
                                ELSE (SELECT nombre FROM usuario WHERE usuario = pre.usuario_adjudicacion) END) AS usuario_adjudicacion,
                          pre.fecha_adjudicacion,'DISEÑO' AS registro
                    FROM  pre_diseno pre, 
                          estacion es
                   WHERE  pre.idEstacion=es.idEstacion
                     AND  pre.itemPlan='".$itemplan."';" ;
       
      $result = $this->db->query($Query);
      return $result;
  }


  function getDisenoEjecutadoLog($itemplan){
       $Query = " SELECT distinct  es.estacionDesc,
                           (select estadoPlanDesc from estadoplan where idEstadoPlan=7) as estado,
                          (select nombre from usuario where usuario=pre.usuario_ejecucion) as usuario_ejecucion,
                          pre.fecha_ejecucion,'DISEÑO' as registro
                    FROM
                          pre_diseno pre, estacion es
                   WHERE
                          pre.idEstacion=es.idEstacion
                     and pre.estado in (3,7)
                     and  pre.itemPlan='".$itemplan."';" ;
       
      $result = $this->db->query($Query);
      return $result;
  }
  
  function getDisenoParcialLog($itemplan){
       $Query = "select distinct  'DISEÑO' AS registro, (select estadoPlanDesc from estadoplan where idEstadoPlan=11) as estado , concat(usu.nombres,' ',usu.ape_paterno,' ',usu.ape_materno) as nombre, lgp.fecha_registro from estadoplan est, usuario usu, planobra pa inner join log_planobra lgp on lgp.itemplan=pa.itemPlan where est.idEstadoPlan=pa.idEstadoPlan and usu.id_usuario=lgp.id_usuario and lgp.itemplan_default like '%idEstadoPlan=11%' and pa.itemPlan='".$itemplan."';" ;
       
      $result = $this->db->query($Query);
      return $result;
  }
  
  
  

  function getEnObraLog($itemplan){ 
       $Query = " SELECT distinct  'OPERACION' as registro, tab.estado, tab.accion, tab.nombre, tab.fecha_registro from 
                      (select lpo.itemplan, (select estadoPlanDesc from estadoplan where idEstadoPlan=3) as estado, replace(itemplan_default,'idEstadoplan=3','') as accion ,usu.nombre, lpo.fecha_registro  from log_planobra lpo, usuario usu where lpo.id_usuario=usu.id_usuario and lpo.itemplan_default like '%idEstadoplan=3%'
                        union
                      select lpo.itemplan, (select estadoPlanDesc from estadoplan where idEstadoPlan=3) as estado, replace(itemplan_default,'idEstado:3','') as accion ,usu.nombre, lpo.fecha_registro from log_planobra lpo, usuario usu where lpo.id_usuario=usu.id_usuario and lpo.itemplan_default like  '%idEstado:3%') as tab
                  where tab.itemplan='".$itemplan."';" ;
       
      $result = $this->db->query($Query);
      return $result;
  }


function getPreLiquidacionLog($itemplan){
   $Query = " SELECT distinct 'LIQUIDACION' as registro, tab.accion, (select estadoPlanDesc from estadoplan where idEstadoPlan=9) as estado,tab.nombre, tab.fecha_registro from 
            (select lpo.itemplan, lpo.actividad, replace(itemplan_default,'idEstadoPlan=9','') as accion ,usu.nombre, lpo.fecha_registro  from log_planobra lpo, usuario usu where lpo.id_usuario=usu.id_usuario and lpo.itemplan_default like '%idEstadoPlan=9%'
              union
              select lpo.itemplan, lpo.actividad, lpo.itemplan_default as accion ,usu.nombre, lpo.fecha_registro  
              from log_planobra lpo, usuario usu where lpo.id_usuario=usu.id_usuario and lpo.tabla='planobra' and lpo.actividad='Obra Pre-Liquidada') as tab
              where tab.itemplan='".$itemplan." ';" ;
       
      $result = $this->db->query($Query);
      return $result;
}


function getTerminadoLog($itemplan){
   $Query = " SELECT distinct 'INSPECCION' as registro, tab.accion, (select estadoPlanDesc from estadoplan where idEstadoPlan=4) as estado ,tab.nombre, tab.fecha_registro from 
            (select lpo.itemplan, lpo.actividad, replace(replace(itemplan_default,'idEstadoPlan=4',''),'idestadoPlan=4','') as accion ,usu.nombre, lpo.fecha_registro  from log_planobra lpo, usuario usu where lpo.id_usuario=usu.id_usuario and lpo.itemplan_default like '%idEstadoPlan=4%'
              union
              select lpo.itemplan, lpo.actividad, lpo.itemplan_default as accion ,usu.nombre, lpo.fecha_registro  
              from log_planobra lpo, usuario usu where lpo.id_usuario=usu.id_usuario and lpo.tabla='sinfix' and lpo.actividad='Terminar obra') as tab
              where tab.itemplan='".$itemplan."' ; " ;
       
      $result = $this->db->query($Query);
      return $result;
}  


function getTruncoLog($itemplan){
   $Query = " SELECT tab.accion, (select estadoPlanDesc from estadoplan where idEstadoPlan=10) as estado,tab.nombre, tab.fecha_registro from 
            (select lpo.itemplan, lpo.actividad, replace(itemplan_default,'idEstadoPlan=10','') as accion ,usu.nombre, lpo.fecha_registro  from log_planobra lpo, usuario usu where lpo.id_usuario=usu.id_usuario and lpo.itemplan_default like '%idEstadoPlan=10%'
              union
            select lpo.itemplan, lpo.actividad, replace(itemplan_default,'idEstado:10','') as accion ,usu.nombre, lpo.fecha_registro from log_planobra lpo, usuario usu where lpo.id_usuario=usu.id_usuario and lpo.itemplan_default like '%idEstado:10%') as tab
              where tab.itemplan='".$itemplan."';" ;
       
      $result = $this->db->query($Query);
      return $result;
}


function getCanceladoLog($itemplan){
   $Query = "SELECT tab.accion, (select estadoPlanDesc from estadoplan where idEstadoPlan=6) as estado,tab.nombre, tab.fecha_registro from 
            (select lpo.itemplan, lpo.actividad,  replace(itemplan_default,'idEstadoPlan=6','') as accion ,usu.nombre, lpo.fecha_registro from log_planobra lpo, usuario usu where lpo.id_usuario=usu.id_usuario and lpo.itemplan_default like '%idEstadoPlan=6%'
              union
            select lpo.itemplan, lpo.actividad, replace(itemplan_default,'idEstado:6','') as accion ,usu.nombre, lpo.fecha_registro from log_planobra lpo, usuario usu where lpo.id_usuario=usu.id_usuario and lpo.itemplan_default like '%idEstado:6%') as tab
              where tab.itemplan='".$itemplan."'
              limit 1;" ;
       
      $result = $this->db->query($Query);
      return $result;
}

function getMotivoTruncoCanceladoLog($itemplan,$fecha,$flag){

  $nfecha=trim(substr($fecha,0,10));

  $Query = "SELECT poc.comentario,usu.nombre,poc.fecha 
              from  planobra_cancelar poc, usuario usu 
              where usu.id_usuario=poc.usuario 
                and DATE(poc.fecha)='". $nfecha."'
                and poc.estado=".$flag." 
                and poc.id_itemplan='".$itemplan."';" ;

      $result = $this->db->query($Query);
      return $result;
}


function getCerradoLog($itemplan){
   $Query = "SELECT distinct 'CERTIFICADO' as registro, tab.accion, (select estadoPlanDesc from estadoplan where idEstadoPlan=5) as estado, tab.nombre, tab.fecha_registro from 
            (select lpo.itemplan, lpo.actividad,  replace(itemplan_default,'idEstadoPlan=5','') as accion ,usu.nombre, lpo.fecha_registro from log_planobra lpo, usuario usu where lpo.id_usuario=usu.id_usuario and lpo.itemplan_default like '%idEstadoPlan=5%'
              union
            select lpo.itemplan, lpo.actividad, replace(itemplan_default,'idEstado:5','') as accion ,usu.nombre, lpo.fecha_registro from log_planobra lpo, usuario usu where lpo.id_usuario=usu.id_usuario and lpo.itemplan_default like '%idEstado:5%') as tab
              where tab.itemplan='".$itemplan." '; " ;
       
      $result = $this->db->query($Query);
      return $result;
}


/******************************************antiguas funciones sin uso*******************************************************************/

	function getListaLogPlanObra($itemplan){
	     $Query = " SELECT lpo.actividad, 
                           lpo.itemplan_default,
                           (select nombre from usuario where id_usuario=lpo.id_usuario) as usuario,
                           lpo.fecha_registro as  fecha_registro
                    from   log_planobra lpo
                    where  lpo.tabla in ('planobra','sinfix','from_sisegos')
                    and    lpo.itemPlan='".$itemplan."';" ;
	     
	    $result = $this->db->query($Query);

	   return $result;
	}

    function getListaLogTramaSIGOPLUS($itemplan){
         $Query = " SELECT descripcion,origen,sisego,fecha_registro from log_tramas_sigoplus where itemPlan='".$itemplan."';" ;
         
        $result = $this->db->query($Query);
      
            return $result;
     
    }

    function getListaLogPrediseno($itemplan){
         $Query = " SELECT  es.estacionDesc,
                            pre.fecha_adjudicacion,
                            pre.usuario_adjudicacion,
                            pre.fecha_prevista_atencion,
                            pre.fecha_ejecucion,
                            pre.usuario_ejecucion
                    FROM
                            pre_diseno pre, estacion es
                   WHERE
                            pre.idEstacion=es.idEstacion
                     and    pre.itemPlan='".$itemplan."';" ;
         
        $result = $this->db->query($Query);
       
          return $result;
       
    }
	
		
    function getListaLogExpediente($itemplan){
         $Query = " SELECT  expe.comentario,
                            expe.fecha, 
                            (select estacionDesc from estacion where idEstacion=expe.idEstacion) as estacion,
                            (select nombre from usuario where usuario=expe.usuario) as usuario_creador,
                            expe.fecha_valida,
                            expe.estado,
                            (select nombre from usuario where usuario=expe.usuario) as usuario_validacion
                    from    itemplan_expediente expe
                   where    expe.itemplan='".$itemplan."';" ;
         
        $result = $this->db->query($Query);
       
            return $result;
       
    }

    function getListaLogBusqueda($itemplan){
         $Query = " SELECT detcv.itemplan, 
                           detcv.avance, 
                           detcv.fecha_registro, 
                           detcv.fecha_termino_obra,
                           usu.nombre
                      FROM planobra_detalle_cv_log detcv, usuario usu
                     where detcv.usuario=usu.usuario
                       and detcv.itemplan='".$itemplan."';" ;
         
        $result = $this->db->query($Query);
       
            return $result;
     
    }
    
    
    
    function getListaCertificacionMOLog($itemplan){
    	 $Query = " SELECT certmo.ptr, (select nombre from usuario where usuario=certmo.usuario) as nombre, certmo.fecha
                      FROM certificacion_mo certmo
                     where  certmo.estado=1
                     and certmo.itemplan='".$itemplan."';" ;
         
        $result = $this->db->query($Query);
       
        return $result;
    
    }

   

   function getListaLogWebUnificadaDet($itemplan){
         $Query = " SELECT ESTADO,PTR,USUA_APROB,fec_aprob from web_unificada_det where itemplan='".$itemplan."';";   
         $result = $this->db->query($Query);
      
         return $result;
        
    }

     function getListaLogPTR($itemplan){
         $Query = " SELECT  CONCAT(lpo.actividad,' ',lpo.ptr_default) as accion,
                            lpo.ptr,
                            (select nombre from usuario where id_usuario=lpo.id_usuario) as usuario ,
                            lpo.fecha_registro as fecha_registro
                      from  log_planobra lpo
                     where  lpo.tabla in ('detalleplan','web_unificada_det','web_unificada_det, detalleplan')
                       and  lpo.itemPlan='".$itemplan."';" ;   
        $result = $this->db->query($Query);
       
            return $result;
       
    }



    function getListaLogPTRTrama($itemplan){
         $Query = " SELECT  origen, ptr, descripcion, fecha_registro from log_tramas_sigoplus where itemplan='".$itemplan."';" ;   
        $result = $this->db->query($Query);
       
            return $result;
       
    }
	/******************************************************************************************************/
     /*************************************************PTR***************************************************/

	function getPTRMOVIMIENTO($itemplan){
	 $Query = "SELECT tabMat.itemplan, tabMat.poCod, tabMat.registro,tabMat.tabla,tabMat.actividad,
		tabMat.ptr_default, tabMat.usuario, tabMat.fecharegistro from 
(select dp.itemplan, 
       dp.poCod,
	   (case when lpo.ptr_default like '%Elimina%' then 'ELIMINAR' 
 			WHEN lpo.ptr_default is null then 'REGISTRO' else ' ' end) as registro,
 		1 as orden, 
		(case when lpo.tabla is null then 'detalleplan' else lpo.tabla end) as tabla, 
		(case when lpo.actividad is null then 'ingresar wu' else lpo.actividad end) as actividad, 
		lpo.ptr_default,
        (case when lpo.id_usuario is null then wu.usu_registro 
         else (select usuario from usuario where id_usuario=lpo.id_usuario) end) as usuario, 
		DATE_FORMAT((case when lpo.fecha_registro is null then wu.f_creac_prop 
         else lpo.fecha_registro end ), '%d/%m/%Y %H:%i:%s')  as fecharegistro
 from 
		detalleplan dp 
     	left join log_planobra lpo on lpo.ptr=dp.poCod
		left join web_unificada_det wud on wud.ptr=dp.pocod
		left join web_unificada wu on wu.ptr=dp.poCod
        where not dp.poCod='NOREQUIERE'
        and dp.itemplan='".$itemplan."'

union

select dp.itemplan, 
       dp.poCod,'PRE-APROBACION' AS registro,
 2 as orden,
        ' ' as tabla,' ' as actividad,' ' as ptr_default,	   
         (CASE WHEN wud.usua_aprob is null then '' 
         	  else wud.usua_aprob end) as usuario,
         (case when wud.fec_aprob is null then '' else wud.fec_aprob end) as fecharegistro
							
                            
 from 
		detalleplan dp 
     	left join log_planobra lpo on lpo.ptr=dp.poCod
		left join web_unificada_det wud on wud.ptr=dp.pocod
        where not dp.poCod='NOREQUIERE'
        and dp.itemplan='".$itemplan."'
		
union
select dp.itemplan, 
       dp.poCod, (case when wud.estado_asig_grafo=2 THEN 'APROBAR' ELSE ' ' END) AS registro, 3 as orden,
       ' ' as tabla,' ' as actividad,' ' as ptr_default,	   
        
        (CASE WHEN wud.usua_asig_grafo is null and wud.estado is null then 'APROBADO DESDE WU' 
         	  else wud.usua_asig_grafo end) as usuario,
         (case when wud.fecha_asig_grafo is null then wu.f_aprob else wud.fecha_asig_grafo end) as fecharegistro
							
                            
 from 
		detalleplan dp 
     	left join log_planobra lpo on lpo.ptr=dp.poCod
		left join web_unificada_det wud on wud.ptr=dp.pocod
		left join web_unificada wu on wu.ptr=dp.poCod
        where not dp.poCod='NOREQUIERE'
        and dp.itemplan='".$itemplan."'

union
		
select dp.itemplan, 
       dp.poCod, (case when itp.estado_final='FINALIZADO' then 'CERTIFICADO' 
                  		when itp.estado_final='PENDIENTE' THEN 'VALIDAR' ELSE ' ' end) AS registro, 4 as orden,
       ' ' as tabla,' ' as actividad,ptrexp.ptr as ptr_default,	   
		itp.usuario_valida as usuario, 
		DATE_FORMAT(itp.fecha_valida, '%d/%m/%Y %H:%i:%s') as fecharegistro
 from 
		detalleplan dp 
        
		left join log_planobra lpo on lpo.ptr=dp.poCod
		left join ptr_expediente ptrexp on ptrexp.ptr=dp.poCod
        left join itemplan_expediente itp on itp.itemplan=dp.itemplan
        where not dp.poCod='NOREQUIERE'
        and dp.itemplan='".$itemplan."'
        AND NOT ptrexp.ptr IS NULL

union
		
select dp.itemplan,dp.pocod, (case when cemo.estado='1' then 'CERTIFICADO MO' ELSE '' end) AS registro, 5 as orden,
        ' ' as tabla,' ' as actividad,' ' as ptr_default,	   
		cemo.usuario as usuario, 
 		DATE_FORMAT(cemo.fecha_valida, '%d/%m/%Y %H:%i:%s')
		 as fecharegistro
 from 
		detalleplan dp 
        
		left join certificacion_mo cemo on cemo.ptr=dp.pocod
        where not dp.poCod='NOREQUIERE'
        and dp.itemplan='".$itemplan."'
        
union
     select dp.itemplan,dp.poCod,
			poe.estado AS registro,
            6 AS orden,'' AS tabla,
            '' AS actividad, '' as ptr_default,
            u.usuario AS usuario,
            DATE_FORMAT(lppo.fecha_registro, '%d/%m/%Y %H:%i:%s') AS fecharegistro
	   FROM detalleplan dp,
            planobra_po ppo,
            po_estado poe,
            log_planobra_po lppo,
            usuario u
	  WHERE dp.itemplan = ppo.itemplan
        AND dp.poCod = ppo.codigo_po
		AND ppo.itemplan = lppo.itemplan
        AND lppo.idPoestado = poe.idPoestado
        AND lppo.idUsuario = u.id_usuario
        AND dp.itemplan = '" . $itemplan . "'   
        
        
        ) as tabMat
        where trim(tabMat.registro)!=''
        order by tabMat.poCod, tabMat.orden asc , tabMat.fecharegistro asc";
	 $result = $this->db->query($Query);
       
            return $result;
	
	}


	


    /***********************************************************************************************************/

    /*********************************************ietmplanestacion avance***************************************/

	function   getListaLogPorcentajeAvance($itemplan){
	    $Query = " SELECT es.estacionDesc, 
                          logpor.porcentaje, 
                          usu.nombre, 
                          max(logpor.fecha_registro) as fecha_registro 
                    FROM  log_porcentaje_cuadrilla logpor, estacion es, usuario usu
                   WHERE  logpor.idEstacion=es.idEstacion
                     AND  logpor.usuario_registro=usu.id_usuario
                     AND  logpor.itemplan='".$itemplan."'
                     group by es.estacionDesc";
	   $result = $this->db->query($Query);
	   return $result;
	}
	
	function   getListaLogPorcentajeAvance2($itemplan){
	    $Query = " SELECT es.estacionDesc, 
                	  ie.porcentaje, 
                	  UPPER(usu.nombre) as nombre, 
                	  ie.fecha as fecha_registro 
                FROM  estacion es, itemplanestacionavance ie LEFT JOIN  usuario usu ON  ie.id_usuario_log = usu.id_usuario
                WHERE  ie.idEstacion = es.idEstacion
                 AND  ie.itemplan='".$itemplan."'
                     group by es.estacionDesc";
	   $result = $this->db->query($Query);
	   return $result;
	}
	
	function getFichaTecnicaLogReg($itemplan){
	    
	    $Query = "   SELECT 'FICHA TECNICA - REGISTRO' AS registro,
	                         ep.estadoPlanDesc,u.usuario,
	                         DATE(ft.fecha_registro) AS fecha_registro
                        FROM ficha_tecnica ft,
                             estadoplan ep,
                             planobra po,
                             usuario u
                       WHERE ft.itemplan = po.itemplan
                         AND po.idEstadoPlan = ep.idEstadoPlan
                         AND ft.usuario_registro = u.id_usuario
                         AND ft.itemplan = '".$itemplan."'";
        
       $result = $this->db->query($Query);
       return $result;
   }

   public function getFichaTecnicaLogVal($itemplan)
    {

        $Query = "    SELECT 'FICHA TECNICA -  VALIDACION' as validacion,
                             ep.estadoPlanDesc,
                             (CASE WHEN u.usuario IS NULL THEN ft.usuario_validacion ELSE u.usuario END) AS usuario,
                             DATE(ft.fecha_validacion) AS fecha_validacion
                        FROM ficha_tecnica ft
                   LEFT JOIN usuario u ON u.id_usuario = ft.usuario_validacion,
                             estadoplan ep,
                             planobra po
                       WHERE ft.itemplan = po.itemplan
                         AND po.idEstadoPlan = ep.idEstadoPlan
                         AND ft.itemplan = '" . $itemplan . "'";

        $result = $this->db->query($Query);
        return $result;
    }

    public function getCertificacionLog($itemplan)
    {

        $Query = "    SELECT 'CERTIFICACION -  VALIDACION' AS accion,
                              ie.estado_final,
                              (CASE WHEN u.usuario IS NULL THEN ie.usuario_valida ELSE u.usuario END) AS usuario,
                              DATE(ie.fecha_valida) AS fecha_valida,
                              CONCAT(e.estacionDesc,' - ',ie.comentario) AS observacion
                         FROM itemplan_expediente  ie
                    LEFT JOIN usuario u ON u.usuario = ie.usuario_valida,
                              estacion e
                        WHERE ie.idEstacion = e.idEstacion
                          AND ie.itemplan = '" . $itemplan . "'";

        $result = $this->db->query($Query);
        return $result;
    }
    
    public function getSolicitudVRByIP($itemplan)
    {
        $sql = " SELECT svr.idSolicitudValeReserva,
                        svr.vr,
                        svr.ptr,
                        svr.material,
                        svr.textoBreve AS desc_material,
                        (CASE WHEN svr.flg_estado = '1' THEN 'APROBADO' ELSE 'RECHAZADO' END) AS estado,
                        u.nombre,
                        svr.fecha_registro,
                        svr.fecha_atencion
                   FROM solicitud_vale_reserva  svr,
                        usuario u
                  WHERE svr.idUsuario = u.id_usuario
                    AND itemplan = '" . $itemplan . "'";

        $result = $this->db->query($sql);
        return $result->result();
    }

    public function getDetalleLogVR($idSolVR)
    {
        $sql = " SELECT lsv.ptr,
                        u.nombre,
                        lsv.fecha_registro,
                        lsv.comentario,
                        (CASE WHEN lsv.flg_estado = '1' THEN 'APROBADO' ELSE 'RECHAZADO' END) AS estado
                   FROM log_solicitud_vr lsv,
                        usuario u
                  WHERE lsv.idUsuario = u.id_usuario
                    AND idSolicitudValeReserva = ".$idSolVR."";

        $result = $this->db->query($sql);
        return $result->result();
    }
    
     public function getFlgCotizacion($itemplan)
    {
        $sql = " SELECT has_cotizacion
		           FROM planobra
                  WHERE itemplan = '" . $itemplan . "' ";
        $result = $this->db->query($sql);
        return $result->row_array()['has_cotizacion'];
    }

    public function getDetalleCotizacion($itemplan)
    {
         $sql = "   SELECT pc.*, u.usuario usuario_registra FROM planobra_cotizacion pc, log_planobra lpo, usuario u where 
                    lpo.itemplan = pc.itemplan and lpo.tabla = 'planobra' and lpo.id_usuario = u.id_usuario 
                    and lpo.actividad = 'ingresar'
                    and pc.itemplan= ? ";

        $result = $this->db->query($sql, array($itemplan));
        return $result->row_array();
    }
    
    
    public function getDetalleIP($itemplan)
    {
        $sql = " SELECT po.itemplan,
                        (CASE WHEN po.fechaInicio IS NULL THEN '-' ELSE DATE(po.fechaInicio) END) AS fechaInicio,
                        (CASE WHEN po.fechaPrevEjec IS NULL THEN '-' ELSE DATE(po.fechaPrevEjec) END) AS fechaPrevEjec,
                        (CASE WHEN po.fechaEjecucion IS NULL THEN '-' ELSE DATE(po.fechaEjecucion) END) AS fechaEjecucion,
                        (CASE WHEN po.fechaCancelacion IS NULL THEN '-' ELSE DATE(po.fechaCancelacion) END) AS fechaCancelacion,
                        (CASE WHEN po.fechaTermino IS NULL THEN '-' ELSE DATE(po.fechaTermino) END) AS fechaTermino,
                        (CASE WHEN po.fechaPreliquidacion IS NULL THEN '-' ELSE DATE(po.fechaPreliquidacion) END) AS fechaPreliquidacion,
                        (CASE WHEN po.fechaTrunca IS NULL THEN '-' ELSE DATE(po.fechaTrunca) END) AS fechaTrunca,
                        (CASE WHEN po.fecha_creacion IS NULL THEN '-' ELSE DATE(po.fecha_creacion) END) AS fecha_creacion
                  FROM planobra po
                 WHERE po.itemplan = '" . $itemplan . "' ";
        $result = $this->db->query($sql);
        return $result->row_array();
    }

    public function getLogCreacionIP($itemplan)
    {
        $sql = " SELECT po.itemplan,
                        (CASE WHEN u.usuario IS NULL THEN '-' ELSE u.usuario END) AS usuario, 
                        DATE(lgp.fecha_registro) AS fecha_registro
                   FROM planobra po,
                        log_planobra lgp LEFT JOIN usuario u ON u.id_usuario = lgp.id_usuario
                  WHERE po.itemPlan = lgp.itemplan
                    AND po.fecha_creacion = lgp.fecha_registro
                    AND lgp.itemplan_default IS NULL
                    AND lgp.tabla = 'planobra'
                    AND lgp.actividad = 'ingresar'
                    AND po.itemPlan = '" . $itemplan . "' ";

        $result = $this->db->query($sql);
        return $result->result();
    }

    public function getAdjuLog($itemplan)
    {
        $sql = "SELECT pd.itemplan,
                       pd.idEstacion,
                       e.estacionDesc,
                       (CASE WHEN pd.fecha_adjudicacion IS NULL THEN '-' ELSE DATE(pd.fecha_adjudicacion) END) AS fecha_adjudicacion,
                       (CASE WHEN pd.usuario_adjudicacion = '' THEN '-' ELSE pd.usuario_adjudicacion END) AS usuario_adjudicacion,
                       pd.estado
                 FROM pre_diseno pd, estacion e
                WHERE pd.idEstacion = e.idEstacion
                  AND pd.fecha_adjudicacion IS NOT NULL
                  AND pd.usuario_adjudicacion IS NOT NULL
                  AND pd.itemplan = '" . $itemplan . "'
             ORDER BY pd.itemPlan,pd.idEstacion";

        $result = $this->db->query($sql);
        return $result->result();
    }

    public function getDisenoEjectLog($itemplan)
    {
        $sql = "SELECT pd.itemplan,
                       pd.idEstacion,
                       e.estacionDesc,
                       (CASE WHEN pd.fecha_ejecucion IS NULL THEN '-' ELSE DATE(pd.fecha_ejecucion) END) AS fecha_ejecucion,
                       (CASE WHEN pd.usuario_ejecucion = '' THEN '-' ELSE pd.usuario_ejecucion END) AS usuario_ejecucion,
                       pd.estado,
                       pd.path_expediente_diseno
                 FROM pre_diseno pd, estacion e
                WHERE pd.idEstacion = e.idEstacion
                  AND pd.fecha_ejecucion IS NOT NULL
                  AND pd.usuario_ejecucion IS NOT NULL
                  AND pd.estado = '3'
                  AND pd.itemplan = '" . $itemplan . "'
             ORDER BY pd.itemPlan,pd.idEstacion";

        $result = $this->db->query($sql);
        return $result->result();
    }
    public function getDisenoParcialLog2($itemplan)
    {
        $sql = " SELECT po.itemplan,
                        u.usuario, 
                        DATE(lgp.fecha_registro) AS fecha_registro,
                        lgp.itemplan_default
                   FROM planobra po,
                        log_planobra lgp,
                        usuario u
                  WHERE po.itemPlan = lgp.itemplan 
                    AND lgp.id_usuario = u.id_usuario 
                    AND lgp.itemplan_default LIKE '%idEstadoPlan=11%' 
                    AND po.itemPlan = '" . $itemplan . "' ";

        $result = $this->db->query($sql);
        return $result->result();
    }

    public function getTerminadoLog2($itemplan)
    {
        $Query = " SELECT po.itemplan,
                         (CASE WHEN u.usuario = '' THEN '-' ELSE u.usuario END) AS usuario, 
                          DATE(lgp.fecha_registro) AS fecha_registro,
                          lgp.itemplan_default
                    FROM planobra po,
                         log_planobra lgp LEFT join usuario u ON u.id_usuario = lgp.id_usuario 
                    WHERE po.itemPlan = lgp.itemplan 
                      AND (lgp.itemplan_default LIKE '%idEstadoPlan=4%' or lgp.actividad = 'Terminar obra-Validado') 
                      AND po.itemPlan = '" . $itemplan . "' ";

        $result = $this->db->query($Query);
        return $result;
    }

    public function getPreLiquidacionLog2($itemplan)
    {
        $Query = "  SELECT lgp.itemplan,
                           (CASE WHEN u.usuario = '' THEN '-' ELSE u.usuario END) AS usuario, 
                           DATE(lgp.fecha_registro) AS fecha_registro,
                           lgp.itemplan_default,
                           lgp.actividad
                      FROM log_planobra lgp LEFT join usuario u ON u.id_usuario = lgp.id_usuario 
                     WHERE lgp.itemPlan = '" . $itemplan . "'
                       AND (lgp.itemplan_default LIKE '%idEstadoPlan=9%'  OR lgp.actividad='Obra Pre-Liquidada')
                  ORDER BY lgp.fecha_registro DESC LIMIT 1 ";

        $result = $this->db->query($Query);
        return $result;
    }
	
	public function getParalizadoObra($itemplan)
    {
	
	$Query = "   SELECT 'Paralizado',
						 itemplan,
						 usuario,
						 pa.fechaRegistro,
						 pa.idMotivo,
						 CASE WHEN u.nombre IS NULL THEN pa.nombreUsuarioTrama
                              ELSE u.nombre END as nombre,
						 mo.motivoDesc,
						 pa.comentario
					FROM (paralizacion pa,
						 motivo mo) 
			   LEFT JOIN usuario u ON (u.id_usuario = pa.idUsuario)
				  WHERE pa.idMotivo = mo.idMotivo
					AND flg_activo = 1
					AND pa.itemplan = ?";

        $result = $this->db->query($Query, array($itemplan));
        return $result;
    }
	
	
}