<?php
class M_bandeja_certificacion extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	
	function   getBandejaCertificacionMO($eecc,$fechaValidacion = ''){
		$eecc = ($eecc == '') ? NULL : $eecc;
	    $Query = "SELECT p.proyectoDesc, 
						 po.idSubProyecto, 
						 sp.subProyectoDesc, 
						 mo.pep1, 
            			 SUM(CASE WHEN mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -3 DAY)) AND NOW() THEN 1 ELSE NULL END) as hasta3,
            			 SUM(CASE WHEN mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -3 DAY)) AND NOW() THEN mo.monto_mo ELSE NULL END) as total_hasta3,
            			 SUM(CASE WHEN mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -7 DAY)) AND DATE(date_add(NOW(), INTERVAL -4 DAY)) THEN 1 ELSE NULL END) as hasta7,
            			 SUM(CASE WHEN mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -7 DAY)) AND DATE(date_add(NOW(), INTERVAL -4 DAY)) THEN mo.monto_mo ELSE NULL END) as total_hasta7,
            			 SUM(CASE WHEN mo.fecha  <=  DATE(date_add(NOW(), INTERVAL -8 DAY)) THEN 1 ELSE NULL END) as todo,
            			 SUM(CASE WHEN mo.fecha  <=  DATE(date_add(NOW(), INTERVAL -8 DAY)) THEN mo.monto_mo ELSE NULL END) as total_todo
					FROM certificacion_mo mo, 
					     planobra po, 
						 central c, 
						 subproyecto sp, 
						 proyecto p, 
						 web_unificada wu
				   WHERE mo.itemplan = po.itemplan
					 AND mo.ptr = wu.ptr
					 AND po.idCentral = c.idCentral
					 AND po.idSubProyecto = sp.idSubProyecto
					 AND sp.idProyecto = p.idProyecto
					 AND mo.estado_validado = 0
					 AND mo.estado = ".CERTIFICACION_MO_CON_PRESUPUESTO."
					 AND wu.eecc   = COALESCE(?, wu.eecc)
					 AND CASE WHEN '".$fechaValidacion."' <> '' THEN 
								   DATE_FORMAT(mo.fecha, '%Y-%m-%d') <= '" .$fechaValidacion."'
							  ELSE '".$fechaValidacion."' = '".$fechaValidacion."' END 
					 GROUP BY po.idSubProyecto, sp.subProyectoDesc, mo.pep1
					 UNION ALL
					 (
						SELECT  p.proyectoDesc, 
								po.idSubProyecto, 
								sp.subProyectoDesc, 
								mo.pep1, 
								SUM(CASE WHEN mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -3 DAY)) AND NOW() THEN 1 ELSE NULL END) as hasta3,
								SUM(CASE WHEN mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -3 DAY)) AND NOW() THEN mo.monto_mo ELSE NULL END) as total_hasta3,
								SUM(CASE WHEN mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -7 DAY)) AND DATE(date_add(NOW(), INTERVAL -4 DAY)) THEN 1 ELSE NULL END) as hasta7,
								SUM(CASE WHEN mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -7 DAY)) AND DATE(date_add(NOW(), INTERVAL -4 DAY)) THEN mo.monto_mo ELSE NULL END) as total_hasta7,
								SUM(CASE WHEN mo.fecha  <=  DATE(date_add(NOW(), INTERVAL -8 DAY)) THEN 1 ELSE NULL END) as todo,
								SUM(CASE WHEN mo.fecha  <=  DATE(date_add(NOW(), INTERVAL -8 DAY)) THEN mo.monto_mo ELSE NULL END) as total_todo
						  FROM certificacion_mo mo, 
								planobra po, 
								central c, 
								subproyecto sp, 
								proyecto p, 
								planobra_po ppo,
								empresacolab e
						  WHERE mo.itemplan = po.itemplan
							AND ppo.itemplan = po.itemplan
							AND ppo.codigo_po = mo.ptr
							AND po.idCentral = c.idCentral
							AND po.idSubProyecto = sp.idSubProyecto
							AND sp.idProyecto = p.idProyecto
							AND mo.estado_validado = 0
							AND c.idEmpresaColab = e.idEmpresaColab
							#AND po.idEmpresaColabDiseno = e.idEmpresaColab
							AND mo.estado = ".CERTIFICACION_MO_CON_PRESUPUESTO."
							AND e.empresaColabDesc = COALESCE(?, e.empresaColabDesc)
							AND CASE WHEN '".$fechaValidacion."' <> '' THEN 
										DATE_FORMAT(mo.fecha, '%Y-%m-%d') <= '" .$fechaValidacion."'
									ELSE '".$fechaValidacion."' = '".$fechaValidacion."' END 
							GROUP BY po.idSubProyecto, sp.subProyectoDesc, mo.pep1
							ORDER BY p.proyectoDesc, sp.subProyectoDesc
					 )";
       
	    // if ($fechaValidacion != '') {
        //     $Query .= " AND DATE_FORMAT(mo.fecha, '%Y-%m-%d') <= '" . $fechaValidacion . "' ";
        // }
        // $Query .= "  GROUP BY po.idSubProyecto, sp.subProyectoDesc, mo.pep1 ";

        // $Query .= " ORDER BY p.proyectoDesc, sp.subProyectoDesc";
        
		$result = $this->db->query($Query,array($eecc, $eecc));
		// log_message('error',$this->db->last_query());
	    return $result;
	}

	function   getPtrsCertificacionMO($pep, $eecc, $subProyecto, $rango_fecha){
		$eecc = ($eecc == '') ? NULL : $eecc;
	    $Query = "SELECT mo.itemplan, 
		                 wu.eecc AS empresaColabDesc, 
						 mo.ptr, 
						 mo.monto_mo, 
						 sp.subProyectoDesc, 
	                     mo.areaDesc, 
						 DATE_FORMAT(mo.fecha,'%d/%m/%Y') as fecha, mo.pep1
                	FROM certificacion_mo mo, planobra po, central c, subproyecto sp, empresacolab e,  web_unificada wu
                	WHERE mo.itemplan = po.itemplan
	                AND mo.ptr = wu.ptr
                	AND po.idCentral = c.idCentral
                	AND po.idSubProyecto = sp.idSubProyecto
	                AND c.idEmpresaColab = e.idEmpresaColab
	                AND mo.estado_validado = 0
                	AND mo.pep1 = ?
	                AND po.idSubProyecto = ?
					AND mo.estado = ".CERTIFICACION_MO_CON_PRESUPUESTO."
					AND wu.eecc   = COALESCE(?, wu.eecc)
					AND CASE WHEN ".$rango_fecha." = 1 THEN 
									mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -3 DAY)) AND NOW()
							 WHEN ".$rango_fecha." = 2 THEN
									mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -7 DAY)) AND DATE(date_add(NOW(), INTERVAL -4 DAY))
							 WHEN ".$rango_fecha." = 3 THEN
									mo.fecha  <=  DATE(date_add(NOW(), INTERVAL -8 DAY))
							 ELSE   mo.fecha = mo.fecha END
					UNION ALL

					(SELECT mo.itemplan,
							e.empresaColabDesc, 
							mo.ptr, 
							mo.monto_mo, 
							sp.subProyectoDesc, 
							mo.areaDesc, 
							DATE_FORMAT(mo.fecha,'%d/%m/%Y') as fecha, 
							mo.pep1
					   FROM certificacion_mo mo, 
							planobra po, 
							central c, 
							subproyecto sp, 
							empresacolab e, 
							planobra_po ppo
					  WHERE mo.itemplan = po.itemplan
						AND mo.ptr = ppo.codigo_po
						AND po.itemplan = ppo.itemplan
						AND po.idCentral = c.idCentral
						AND po.idSubProyecto = sp.idSubProyecto
						AND c.idEmpresaColab = e.idEmpresaColab
						#AND po.idEmpresaColabDiseno = e.idEmpresaColab
						AND mo.estado_validado = 0
						AND mo.pep1 = ?
						AND po.idSubProyecto = ?
						AND mo.estado = ".CERTIFICACION_MO_CON_PRESUPUESTO."
						AND e.empresaColabDesc = COALESCE(?, e.empresaColabDesc)
						AND CASE WHEN ".$rango_fecha." = 1 THEN 
										mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -3 DAY)) AND NOW()
								 WHEN ".$rango_fecha." = 2 THEN
										mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -7 DAY)) AND DATE(date_add(NOW(), INTERVAL -4 DAY))
								 WHEN ".$rango_fecha." = 3 THEN
										mo.fecha  <=  DATE(date_add(NOW(), INTERVAL -8 DAY))
								 ELSE   mo.fecha = mo.fecha END)";
		$result = $this->db->query($Query,array($pep, $subProyecto, $eecc, $pep, $subProyecto, $eecc));
		//log_message('error',$this->db->last_query());
	    return $result;
	}		
	
	function   getDetalleCertificacionMO(){
	    $Query = "SELECT p.proyectoDesc, 
					     sp.subProyectoDesc, 
						 wu.eecc as empresaColabDesc, 
						 mo.areaDesc, 
						 mo.pep1, 
						 mo.itemplan, 
						 mo.ptr, 
						 mo.monto_mo, 
						 DATE_FORMAT(mo.fecha,'%d/%m/%Y') as fecha
                	FROM certificacion_mo mo, 
					     planobra po, 
						 central c, 
						 subproyecto sp, 
						 proyecto p, 
						 empresacolab e, 
						 web_unificada wu
               	   WHERE mo.itemplan = po.itemplan
	                 AND mo.ptr = wu.ptr
                     AND po.idCentral = c.idCentral
                	 AND po.idSubProyecto = sp.idSubProyecto
                	 AND sp.idProyecto = p.idProyecto
                	 AND c.idEmpresaColab = e.idEmpresaColab
	            	 AND mo.estado_validado = 0
                	 AND mo.estado = ".CERTIFICACION_MO_CON_PRESUPUESTO."
                 UNION ALL
				 (SELECT p.proyectoDesc, 
						  sp.subProyectoDesc, 
						  e.empresaColabDesc, 
						  mo.areaDesc, 
						  mo.pep1, 
						  mo.itemplan, 
						  mo.ptr, 
						  mo.monto_mo, 
						  DATE_FORMAT(mo.fecha,'%d/%m/%Y') as fecha
					 FROM certificacion_mo mo, planobra po, central c, subproyecto sp, proyecto p, empresacolab e, planobra_po ppo
					WHERE mo.itemplan = po.itemplan
					  AND po.itemplan = ppo.itemplan
					 AND     mo.ptr = ppo.codigo_po
					 AND     po.idCentral = c.idCentral
					 AND		po.idSubProyecto = sp.idSubProyecto
					 AND 	sp.idProyecto = p.idProyecto
					 AND     mo.estado_validado = 0
					 AND   ppo.id_eecc_reg = e.idEmpresaColab
					 AND     mo.estado = ".CERTIFICACION_MO_CON_PRESUPUESTO."
					 ORDER BY  p.proyectoDesc, sp.subProyectoDesc)";
	    $result = $this->db->query($Query,array());
	    return $result;
	}
	
   function liquidarPtrCertificacion($arrayPtr, $listaPTRToLog, $listaPTRToCert) {
      $data['error'] = EXIT_ERROR;
	    $data['msj']   = null;
	    try{
	        $this->db->trans_begin();
	        $this->db->update_batch('certificacion_mo',$arrayPtr, 'ptr');
	        if ($this->db->trans_status() === FALSE) {
	            $this->db->trans_rollback();
	            throw new Exception('Hubo un error al actualizar el certificacion_mo.');
	        }else{
	            $this->db->update_batch('planobra_po',$listaPTRToCert, 'codigo_po');
	            if ($this->db->trans_status() === FALSE) {
	                $this->db->trans_rollback();
	                throw new Exception('Hubo un error al actualizar el planobra_po.');
	            }else{
	                $this->db->insert_batch('log_planobra_po', $listaPTRToLog);
	                if ($this->db->trans_status() === FALSE) {
	                    $this->db->trans_rollback();
	                    throw new Exception('Hubo un error al insertar el log_planobra_po.');
	                }else{
                        $data['error'] = EXIT_SUCCESS;
        	            $data['msj'] = 'Se actualizo correctamente!';
        	            $this->db->trans_commit();
	                }
	            }
            } 
        }catch(Exception $e){
            $data['msj']   = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;
      }
    
      function   getPtrsCertificacionMOToCerticate($pep, $eecc, $subProyecto, $rango_fecha){
          $eecc = ($eecc == '') ? NULL : $eecc;
          $Query = "SELECT mo.itemplan,
							e.empresaColabDesc,
							mo.ptr,
							mo.monto_mo,
							sp.subProyectoDesc,
							mo.areaDesc,
							DATE_FORMAT(mo.fecha,'%d/%m/%Y') as fecha,
							mo.pep1
					   FROM certificacion_mo mo,
							planobra po,
							central c,
							subproyecto sp,
							empresacolab e,
							planobra_po ppo
					  WHERE mo.itemplan = po.itemplan
						AND mo.ptr = ppo.codigo_po
						AND po.itemplan = ppo.itemplan
						AND po.idCentral = c.idCentral
						AND po.idSubProyecto = sp.idSubProyecto
						AND po.idEmpresaColabDiseno = e.idEmpresaColab
						AND mo.estado_validado = 0
						AND mo.pep1 = ?
						AND po.idSubProyecto = ?
						AND mo.estado = ".CERTIFICACION_MO_CON_PRESUPUESTO."
						AND e.empresaColabDesc = COALESCE(?, e.empresaColabDesc)
						AND CASE WHEN ".$rango_fecha." = 1 THEN
										mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -3 DAY)) AND NOW()
								 WHEN ".$rango_fecha." = 2 THEN
										mo.fecha  BETWEEN  DATE(date_add(NOW(), INTERVAL -7 DAY)) AND DATE(date_add(NOW(), INTERVAL -4 DAY))
								 WHEN ".$rango_fecha." = 3 THEN
										mo.fecha  <=  DATE(date_add(NOW(), INTERVAL -8 DAY))
								 ELSE   mo.fecha = mo.fecha END";
          $result = $this->db->query($Query,array($pep, $subProyecto, $eecc));
          return $result;
      }

	
}